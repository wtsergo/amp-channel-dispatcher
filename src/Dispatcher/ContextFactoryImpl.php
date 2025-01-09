<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

class ContextFactoryImpl implements ContextFactory
{
    public function create(\Closure $sendRequest): Context
    {
        return new ContextImpl($sendRequest);
    }

}
