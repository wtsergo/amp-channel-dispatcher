<?php

namespace Wtsergo\AmpChannelDispatcher\Response;

use Wtsergo\AmpChannelDispatcher\Helper\ResponseTrait;
use Wtsergo\AmpChannelDispatcher\Response;

class IteratorContinue implements Response
{
    use ResponseTrait;

    public function __construct(
        public readonly bool $continue,
        public readonly ?int $position,
        public readonly mixed $value,
        public readonly int $requestId,
        private ?int $id=null,
    )
    {
        $this->id();
    }
}
