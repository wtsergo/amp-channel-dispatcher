<?php

namespace Wtsergo\AmpChannelDispatcher;

interface Response extends Message
{
    public function requestId(): ?int;
}
