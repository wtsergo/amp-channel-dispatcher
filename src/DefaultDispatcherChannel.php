<?php

namespace Wtsergo\AmpChannelDispatcher;

use Amp\Cancellation;
use Amp\Serialization\SerializationException;
use Amp\Sync\Channel;
use Amp\Sync\ChannelException;

class DefaultDispatcherChannel implements DispatcherChannel
{
    public function __construct(
        public readonly Channel $channel,
    )
    {
    }

    public function receive(?Cancellation $cancellation = null): Message|null
    {
        return $this->channel->receive($cancellation);
    }

    /**
     * @throws SerializationException
     * @throws ChannelException
     */
    public function send(Message $message): void
    {
        $this->channel->send($message);
    }
}
