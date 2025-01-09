<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

use Amp\Future;
use Wtsergo\AmpChannelDispatcher\Request;

class ContextImpl implements Context
{
    /**
     * @param \Closure(Request):Future $sendRequest
     */
    public function __construct(
        private readonly \Closure $sendRequest,
    )
    {
    }

    public function sendRequest(Request $request): Future
    {
        return ($this->sendRequest)($request);
    }

}
