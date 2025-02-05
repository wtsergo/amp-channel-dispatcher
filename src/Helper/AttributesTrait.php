<?php

namespace Wtsergo\AmpChannelDispatcher\Helper;

trait AttributesTrait
{
    protected array $attributes = [];
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    public function setAttributes(array $attributes): static
    {
        $this->attributes = $attributes;
        return $this;
    }
    public function getAttribute(string $name): mixed
    {
        return $this->attributes[$name] ?? null;
    }
    public function setAttribute(string $name, mixed $value): static
    {
        $this->attributes[$name] = $value;
        return $this;
    }
}
