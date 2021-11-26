<?php

namespace Mitquinn\HeapHelper\Interfaces;

interface HeapResourceInterface
{
    /**
     * @return array
     */
    public function generateRequestBody(): array;

}