<?php

namespace Mquinn\HeapHelper\Traits;

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
        $this->heapUserIdentity = $heapUserIdentity;
        return $this;
    }




}