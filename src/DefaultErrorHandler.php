<?php

namespace Wtsergo\AmpChannelDispatcher;

class DefaultErrorHandler implements ErrorHandler
{
    public function handleError(string $message, int $code = 0, ?Request $request = null): Response
    {
        return $request
            ? new ErrorResponse($message, $code, $request->id())
            : new FatalErrorResponse($message, $code);
    }

}
