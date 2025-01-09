<?php

namespace Wtsergo\AmpChannelDispatcher;

function createDataId(): int
{
    static $nextId = 0;
    return $nextId++;
}

