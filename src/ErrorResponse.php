<?php

namespace Wtsergo\AmpChannelDispatcher;

use Wtsergo\AmpChannelDispatcher\Helper\DataTrait;
use Wtsergo\AmpChannelDispatcher\Helper\ResponseTrait;

class ErrorResponse implements Response
{
    use ResponseTrait;

    public function __construct(
        public readonly string $message,
        public readonly int $code = 0,
        public readonly ?int $requestId=null,
        private ?int $id=null,
    )
    {
        $this->id();
    }
}
