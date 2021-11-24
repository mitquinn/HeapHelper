<?php

namespace Mquinn\HeapHelper\Resources;

use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Mquinn\HeapHelper\Interfaces\HeapResourceInterface;
use Mquinn\HeapHelper\Traits\HasProperties;

class HeapAccount implements HeapResourceInterface
{
    use HasProperties;

    /** @var string $heapAccountId */
    protected string $heapAccountId;

    /**
     * @param string $heapAccountId
     * @param array|null $properties
     */
    public function __construct(string $heapAccountId, ?array $properties = null)
    {
        $this->setHeapAccountId($heapAccountId);
        $this->setProperties($properties);
    }

    /**
     * @return string[]
     */
    #[Pure]
    #[ArrayShape([
        'account_id' => "string",
        'properties' => "array|null"
    ])]
    public function generateRequestBody(): array
    {
        $body = [
            'account_id' => $this->getHeapAccountId()
        ];

        if (!is_null($this->getProperties())) {
            $body['properties'] = $this->getProperties();
        }

        return $body;
    }


    /*** Start Getters and Setters ***/

    /**
     * @return string
     */
    public function getHeapAccountId(): string
    {
        return $this->heapAccountId;
    }

    /**
     * @param string $heapAccountId
     * @return HeapAccount
     */
    public function setHeapAccountId(string $heapAccountId): HeapAccount
    {
        $this->heapAccountId = $heapAccountId;
        return $this;
    }

    /*** End Getters and Setters ***/

}