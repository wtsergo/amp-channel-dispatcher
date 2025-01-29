<?php

namespace Wtsergo\AmpChannelDispatcher;

use Wtsergo\AmpChannelDispatcher\Helper\ResponseTrait;

class AcceptedResponse implements Response
{
    use ResponseTrait;

    public function __construct(
        public readonly int $requestId,
        private ?int $id=null,
    )
    {
        $this->id();
    }
}
