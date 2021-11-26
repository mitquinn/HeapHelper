<?php

namespace Mitquinn\HeapHelper\Resources;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Mitquinn\HeapHelper\Interfaces\HeapResourceInterface;
use Mitquinn\HeapHelper\Traits\HasHeapUserIdentity;
use Mitquinn\HeapHelper\Traits\HasProperties;

class HeapUser implements HeapResourceInterface
{
    use HasHeapUserIdentity, HasProperties;

    public function __construct(string $heapUserIdentity, ?array $properties = null)
    {
        $this->setHeapUserIdentity($heapUserIdentity);
        $this->setProperties($properties);
    }


    #[Pure]
    #[ArrayShape([
        'identity' => "string",
        'properties' => "array|null"]
    )]
    public function generateRequestBody(): array
    {
        $body = [
            'identity' => $this->getHeapUserIdentity()
        ];

        if (!is_null($this->getProperties())) {
            $body['properties'] = $this->getProperties();
        }

        return $body;
    }

}