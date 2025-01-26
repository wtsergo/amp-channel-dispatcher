<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

use Wtsergo\AmpChannelDispatcher\ErrorResponse;

trait RequestTrait
{
    use MessageTrait;

    public function createErrorResponse(string $message, int $code = 0): ErrorResponse
    {
        return new ErrorResponse($message, $code, $this->id());
    }
}
