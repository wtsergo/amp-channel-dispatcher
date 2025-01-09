<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

use Wtsergo\Misc\Helper\Dto;
use function Wtsergo\AmpChannelDispatcher\createDataId;

trait DataTrait
{
    public function id(): int
    {
        return $this->id ??= createDataId();
    }
}
