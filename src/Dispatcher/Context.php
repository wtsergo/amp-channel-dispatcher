<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

use Wtsergo\AmpChannelDispatcher\Request;

interface Context
{
    public function sendRequest(Request $request);
}
