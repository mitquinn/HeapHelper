<?php

namespace Mquinn\HeapHelper\Interfaces;

interface HeapResourceInterface
{
    /**
     * @return array
     */
    public function generateRequestBody(): array;

}