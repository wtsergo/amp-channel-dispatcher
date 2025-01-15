<?php

namespace Wtsergo\AmpChannelDispatcher;

use Amp\Cancellation;
use Amp\DeferredCancellation;
use Amp\DeferredFuture;
use Amp\Future;
use Amp\Pipeline\ConcurrentIterator;
use Amp\Pipeline\Queue;
use Psr\Log\LoggerInterface;
use Revolt\EventLoop;
use Wtsergo\AmpChannelDispatcher\Dispatcher\ContextFactory;
use Wtsergo\AmpChannelDispatcher\Dispatcher\ContextFactoryImpl;
use function Amp\weakClosure;

class Dispatcher
{
    /** @var Queue<Message> */
    private readonly Queue $writeQueue;

    /** @var ConcurrentIterator<Message> */
    private readonly ConcurrentIterator $writeIterator;

    /** @var \Closure(Request):Future */
    private \Closure $sendRequest;

    /** @var \Closure(Response):void */
    private \Closure $handleResponse;

    /** @var \Closure(Request):void */
    private \Closure $handleRequest;

    /** @var \Closure():void */
    protected \Closure $writeLoop;

    private readonly DeferredCancellation $loopCancellation;

    /**
     * @var array<int, DeferredFuture>
     */
    private array $pendingResponses = [];

    private ?string $writeLoopId=null;

    /**
     * @param DispatcherChannel<Message, Message> $channel
     * @param RequestHandler $requestHandler
     * @param \Closure(Message):void $readLoopCallback
     */
    public function __construct(
        private readonly DispatcherChannel $channel,
        private readonly RequestHandler $requestHandler,
        private readonly ErrorHandler $errorHandler = new DefaultErrorHandler,
        private readonly ContextFactory $contextFactory = new ContextFactoryImpl,
        private readonly ?LoggerInterface $logger = null,
        private readonly ?\Closure $readLoopCallback = null,
    )
    {
        $this->writeQueue = new Queue();
        $this->writeIterator = $this->writeQueue->iterate();
        $this->loopCancellation = new DeferredCancellation();
        $this->sendRequest = weakClosure($this->sendRequest(...));
        $this->handleRequest = weakClosure($this->handleRequest(...));
        $this->handleResponse = weakClosure($this->handleResponse(...));
        $this->writeLoop = weakClosure($this->writeLoop(...));
    }

    public static function selfFactory(
        DispatcherChannel $channel,
        RequestHandler $requestHandler,
        ErrorHandler $errorHandler = new DefaultErrorHandler,
        ContextFactory $contextFactory = new ContextFactoryImpl,
        ?LoggerInterface $logger = null,
        ?\Closure $readLoopCallback = null,
    ): self
    {
        $self = new self($channel, $requestHandler, $errorHandler, $contextFactory, $logger, $readLoopCallback);
        return $self;
    }

    public function run(): void
    {
        $this->writeLoopId = EventLoop::defer($this->writeLoop);
        $this->readLoop();
    }

    public function __destruct()
    {
        if ($this->writeLoopId) EventLoop::cancel($this->writeLoopId);
        $this->stop();
    }

    public function stop(): void
    {
        if (!$this->writeQueue->isComplete()) {
            $this->writeQueue->complete();
        }
        $this->loopCancellation->cancel();
    }

    private function readLoop(): void
    {
        $abortCancellation = $this->loopCancellation->getCancellation();

        try {
            $context = $this->contextFactory->create($this->sendRequest);
            while ($request = $this->channel->receive($abortCancellation)) {
                if ($this->readLoopCallback) ($this->readLoopCallback)($request);
                if ($request instanceof Request) {
                    $request->setAttribute('context', $context);
                    EventLoop::queue($this->handleRequest, $request);
                } elseif ($request instanceof Response) {
                    EventLoop::queue($this->handleResponse, $request);
                } else {
                    throw new DispatcherException(
                        sprintf('Unsupported request %s', \get_debug_type($request))
                    );
                }
            }
        } catch (\Throwable $throwable) {
            $this->logger?->error("$throwable");
        } finally {
            $throwable ??= new DispatcherException('Dispatcher terminated');
            array_walk(
                $this->pendingResponses,
                fn(DeferredFuture $deferred) => $deferred->error($throwable)
            );
            $this->stop();
        }
    }

    /**
     * @return \Traversable<int, Message>
     */
    private function receiveWrite(?Cancellation $cancellation = null): \Traversable
    {
        while ($this->writeIterator->continue($cancellation)) {
            yield $this->writeIterator->getPosition() => $this->writeIterator->getValue();
        }
    }

    private function writeLoop(): void
    {
        $abortCancellation = $this->loopCancellation->getCancellation();
        try {
            foreach ($this->receiveWrite($abortCancellation) as $message) {
                $this->channel->send($message);
            }
        } catch (\Throwable $throwable) {
            $this->logger?->error("$throwable");
        } finally {
            $this->stop();
        }
    }

    public function sendRequest(Request $request): ?Future
    {
        if (!$request instanceof MeekRequest) {
            $deferred = new DeferredFuture;
            $this->pendingResponses[$request->id()] = $deferred;
            $result = $deferred->getFuture();
        } else {
            $result = null;
        }
        $this->enqueueWrite($request);
        return $result;
    }

    private function handleRequest(Request $request): void
    {
        $response = $this->requestHandler->handleRequest($request);
        $this->enqueueWrite($response->cloneWith(requestId: $request->id()));
    }

    private function handleResponse(Response $response): void
    {
        $requestId = $response->requestId();
        if (!isset($this->pendingResponses[$requestId])) {
            $response = $this->errorHandler->handleError(
                sprintf('Cannot find recipient waiting for response with "requestId: %d"', $requestId),
            );
            $this->enqueueWrite($response);
        } else {
            try {
                $deferred = $this->pendingResponses[$requestId];
                $deferred->complete($response);
            } finally {
                unset($this->pendingResponses[$requestId]);
            }
        }
    }

    private function enqueueWrite(Message $message): void
    {
        $this->writeQueue->pushAsync($message);
    }
}
