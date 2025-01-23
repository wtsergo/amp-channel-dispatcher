<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

use Amp\Future;
use Wtsergo\AmpChannelDispatcher\Dispatcher;
use Wtsergo\AmpChannelDispatcher\Request;

interface ContextFactory
{
    /**
     * @param \Closure(Request):Future $sendRequest
     */
    public function create(
        \Closure $sendRequest,
        Dispatcher $dispatcher,
        IteratorStorage $iteratorStorage
    ): Context;
}
