<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

use Wtsergo\Misc\Helper\DtoTrait;

trait MessageTrait
{
    use DataTrait;
    use AttributesTrait;
    use DtoTrait {
        cloneWith as traitCloneWith;
    }

    public function cloneWith(...$args): static
    {
        $clone = $this->traitCloneWith(...$args);
        $clone->setAttributes($this->getAttributes());
        return $clone;
    }
}
