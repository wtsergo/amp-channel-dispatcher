<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

use Wtsergo\AmpChannelDispatcher\DispatcherException;
use Wtsergo\AmpChannelDispatcher\ErrorResponse;
use Wtsergo\AmpChannelDispatcher\Response;

trait ResponseValidationTrait
{
    /**
     * @param class-string $expectedClass
     * @return void
     */
    protected function validateResponse(Response $response, string $expectedClass): void
    {
        if ($response instanceof ErrorResponse) {
            throw DispatcherException::fromErrorResponse($response);
        }
        if (!is_a($response, $expectedClass, true)) {
            throw new DispatcherException('Unexpected channel response');
        }
    }
}
