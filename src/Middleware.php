<?php

namespace Wtsergo\AmpChannelDispatcher;

interface Middleware
{
    public function handleRequest(Request $request, RequestHandler $requestHandler): Response;
}
