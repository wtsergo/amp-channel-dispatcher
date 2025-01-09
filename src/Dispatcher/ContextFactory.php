<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

use Amp\Future;
use Wtsergo\AmpChannelDispatcher\Request;

interface ContextFactory
{
    /**
     * @param \Closure(Request):Future $sendRequest
     * @return Context
     */
    public function create(\Closure $sendRequest): Context;
}
