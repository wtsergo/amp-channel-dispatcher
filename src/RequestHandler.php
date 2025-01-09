<?php

namespace Wtsergo\AmpChannelDispatcher;

interface RequestHandler
{
    public function handleRequest(Request $request): Response;
}
