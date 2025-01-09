<?php

namespace Wtsergo\AmpChannelDispatcher;

interface ErrorHandler
{
    public function handleError(string $message, int $code = 0, ?Request $request = null): Response;
}
