<?php

namespace Mquinn\HeapHelper\Traits;

use InvalidArgumentException;

trait HasHeapUserIdentity
{
    protected string $heapUserIdentity;

    /**
     * @return string
     */
    public function getHeapUserIdentity(): string
    {
        return $this->heapUserIdentity;
    }

    /**
     * @param string $heapUserIdentity
     * @return HasHeapUserIdentity
     */
    public function setHeapUserIdentity(string $heapUserIdentity): static
    {
        if (strlen($heapUserIdentity) > 255) {
            throw new InvalidArgumentException("The identity must be no greater than 255 characters.");
        }
        $this->heapUserIdentity = $heapUserIdentity;
        return $this;
    }




}