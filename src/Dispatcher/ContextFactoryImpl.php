<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

use Wtsergo\AmpChannelDispatcher\Dispatcher;

class ContextFactoryImpl implements ContextFactory
{
    public function create(
        \Closure $sendRequest,
        Dispatcher $dispatcher,
        IteratorStorage $iteratorStorage
    ): Context
    {
        return new ContextImpl($sendRequest, $dispatcher, $iteratorStorage);
    }

}
