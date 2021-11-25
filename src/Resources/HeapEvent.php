<?php

namespace Mquinn\HeapHelper\Resources;


use DateTime;
use InvalidArgumentException;
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

    /** @var DateTime|null $timestamp */
    protected ?DateTime $timestamp;

    /** @var string|null $idempotencyKey */
    protected ?string $idempotencyKey;

    /**
     * @param string $heapEventName
     * @param string $heapUserIdentity
     * @param array|null $properties
     * @param DateTime|null $timestamp
     * @param string|null $idempotencyKey
     */
    public function __construct(string $heapEventName, string $heapUserIdentity, ?array $properties = null, ?DateTime $timestamp = null, ?string $idempotencyKey = null)
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
        if (strlen($heapEventName) > 1024) {
            throw new InvalidArgumentException("The event name must be no greater than 1024 characters.");
        }
        $this->heapEventName = $heapEventName;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getTimestamp(): ?string
    {
        if (is_null($this->timestamp)) {
            return $this->timestamp;
        }
        return $this->timestamp->format(DateTime::ATOM);
    }

    /**
     * @param DateTime|null $timestamp
     * @return HeapEvent
     */
    public function setTimestamp(?DateTime $timestamp): HeapEvent
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