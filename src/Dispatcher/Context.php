<?php

namespace Wtsergo\AmpChannelDispatcher\Dispatcher;

use Amp\Future;
use Amp\Pipeline\ConcurrentIterator;
use Wtsergo\AmpChannelDispatcher\Dispatcher;
use Wtsergo\AmpChannelDispatcher\Request;

interface Context
{
    public function dispatcherId(): ?int;
    public function dispatcher(): ?Dispatcher;
    public function sendRequest(Request $request): ?Future;
    public function addLocalIterator(ConcurrentIterator $iterator): ?int;
    public function getLocalIterator(int $id): ?ConcurrentIterator;
}
