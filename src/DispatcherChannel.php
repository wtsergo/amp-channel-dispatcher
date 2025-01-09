<?php

namespace Wtsergo\AmpChannelDispatcher;

use Amp\Cancellation;

interface DispatcherChannel
{
    public function receive(?Cancellation $cancellation = null): Message|null;

    public function send(Message $message): void;
}
