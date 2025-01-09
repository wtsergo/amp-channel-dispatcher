<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

trait ResponseTrait
{
    use MessageTrait;
    public function requestId(): ?int
    {
        return $this->requestId ?? null;
    }
}
