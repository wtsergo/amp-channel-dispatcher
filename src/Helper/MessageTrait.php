<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

use Wtsergo\Misc\Helper\Dto;

trait MessageTrait
{
    use DataTrait;
    use AttributesTrait;
    use Dto {
        cloneWith as traitCloneWith;
    }

    public function cloneWith(...$args): static
    {
        $clone = $this->traitCloneWith(...$args);
        $clone->setAttributes($this->getAttributes());
        return $clone;
    }
}
