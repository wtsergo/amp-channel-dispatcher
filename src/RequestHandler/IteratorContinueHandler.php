<?php

namespace Wtsergo\AmpChannelDispatcher\RequestHandler;

use Wtsergo\AmpChannelDispatcher\ErrorResponse;
use Wtsergo\AmpChannelDispatcher\Request;
use Wtsergo\AmpChannelDispatcher\RequestHandler;
use Wtsergo\AmpChannelDispatcher\Response;
use Wtsergo\AmpChannelDispatcher\Dispatcher;

class IteratorContinueHandler implements RequestHandler
{
    /**
     * @param Request\IteratorContinue $request
     * @return Response
     */
    public function handleRequest(Request $request): Response
    {
        /** @var Dispatcher\Context $context */
        $context = $request->getAttribute('context');
        $iterator = $context->getLocalIterator($request->iteratorId);
        if ($iterator === null) {
            $response = new ErrorResponse('Iterator not found');
        } else {
            $continue = $iterator->continue();
            $response = new Response\IteratorContinue(
                continue: $continue,
                position: $continue ? $iterator->getPosition() : null,
                value: $continue ? $iterator->getValue() : null,
                requestId: $request->id()
            );
        }
        return $response;
    }
}
