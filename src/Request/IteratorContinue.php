<?php

namespace Wtsergo\AmpChannelDispatcher\Request;

use Wtsergo\AmpChannelDispatcher\Helper\RequestTrait;
use Wtsergo\AmpChannelDispatcher\Request;

class IteratorContinue implements Request
{
    use RequestTrait;

    public function __construct(
        public readonly int $iteratorId,
        private ?int        $id=null,
    )
    {
        $this->id();
    }
}
