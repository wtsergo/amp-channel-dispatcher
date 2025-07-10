<?php

namespace Wtsergo\AmpChannelDispatcher\Middleware;

use Wtsergo\AmpChannelDispatcher\Middleware;
use Wtsergo\AmpChannelDispatcher\Request;
use Wtsergo\AmpChannelDispatcher\RequestHandler as BaseRequestHandler;
use Wtsergo\AmpChannelDispatcher\Response;

class RequestHandler implements BaseRequestHandler
{
    public function __construct(
        private readonly Middleware $middleware,
        private readonly BaseRequestHandler $requestHandler,
    ) {
    }

    public function handleRequest(Request $request): Response
    {
        return $this->middleware->handleRequest($request, $this->requestHandler);
    }
}
