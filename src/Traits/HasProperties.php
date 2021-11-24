<?php

namespace Mquinn\HeapHelper\Traits;

trait HasProperties
{
    protected ?array $properties;

    /**
     * @return array|null
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * @param array|null $properties
     * @return HasProperties
     */
    public function setProperties(?array $properties): static
    {
        $this->properties = $properties;
        return $this;
    }



}