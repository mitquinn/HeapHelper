<?php

namespace Mquinn\HeapHelper\Resources;


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;
use Mquinn\HeapHelper\Interfaces\HeapResourceInterface;
use Mquinn\HeapHelper\Traits\HasHeapUserIdentity;
use Mquinn\HeapHelper\Traits\HasProperties;

class HeapEvent implements HeapResourceInterface
{
    use HasHeapUserIdentity, HasProperties;

    /** @var string $heapEventName */
    protected string $heapEventName;

    /** @var string|null $timestamp */
    protected ?string $timestamp;

    /** @var string|null $idempotencyKey */
    protected ?string $idempotencyKey;

    /**
     * TODO: Validation to ensure name of event does not exceed 1024 characters
     * TODO: Validation to ensure property key:value not exceed 1024 characters
     * TODO: Validation to ensure property is limited to single key:value pair
     * TODO: Validation that timestamp is ISO8601 format
     * @param string $heapEventName
     * @param string $heapUserIdentity
     * @param array|null $properties
     * @param string|null $timestamp
     * @param string|null $idempotencyKey
     */
    public function __construct(string $heapEventName, string $heapUserIdentity, array $properties = null, string $timestamp = null, string $idempotencyKey = null)
    {
        $this->setHeapEventName($heapEventName);
        $this->setHeapUserIdentity($heapUserIdentity);
        $this->setProperties($properties);
        $this->setTimestamp($timestamp);
        $this->setIdempotencyKey($idempotencyKey);
    }

    /**
     * @return array
     */
    #[Pure]
    #[ArrayShape([
        'identity' => "string",
        'event' => "string",
        'idempotency_key' => "null|string",
        'timestamp' => "null|string",
        'properties' => "array|null"
    ])]
    public function generateRequestBody(): array
    {
        $body = [
            'identity' => $this->getHeapUserIdentity(),
            'event' => $this->getHeapEventName(),
        ];

        if (!is_null($this->getProperties())) {
            $body['properties'] = $this->getProperties();
        }

        if (!is_null($this->getTimestamp())) {
            $body['timestamp'] = $this->getTimestamp();
        }

        if (!is_null($this->getIdempotencyKey())) {
            $body['idempotency_key'] = $this->getIdempotencyKey();
        }

        return $body;
    }

    /*** Start Getters and Setters ***/

    /**
     * @return string
     */
    public function getHeapEventName(): string
    {
        return $this->heapEventName;
    }

    /**
     * @param string $heapEventName
     * @return HeapEvent
     */
    public function setHeapEventName(string $heapEventName): HeapEvent
    {
        $this->heapEventName = $heapEventName;
        return $this;
    }

    /**
     * TODO: Validation that timestamp is ISO8601 format
     * @return string|null
     */
    public function getTimestamp(): ?string
    {
        return $this->timestamp;
    }

    /**
     * @param string|null $timestamp
     * @return HeapEvent
     */
    public function setTimestamp(?string $timestamp): HeapEvent
    {
        $this->timestamp = $timestamp;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIdempotencyKey(): ?string
    {
        return $this->idempotencyKey;
    }

    /**
     * @param string|null $idempotencyKey
     * @return HeapEvent
     */
    public function setIdempotencyKey(?string $idempotencyKey): HeapEvent
    {
        $this->idempotencyKey = $idempotencyKey;
        return $this;
    }

    /*** End Getters and Setters ***/

}