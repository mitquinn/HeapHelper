<?php

namespace Mquinn\HeapHelper\Resources;


use JetBrains\PhpStorm\ArrayShape;
use JetBrains\PhpStorm\Pure;

class Event
{

    /** @var string $name */
    private string $name;

    /** @var string $identity */
    protected string $identity;

    /** @var array|null $properties */
    private ?array $properties;

    /** @var string|null $timestamp */
    private ?string $timestamp;

    /** @var string|null $idempotencyKey */
    private ?string $idempotencyKey;

    /**
     * TODO: Validation to ensure name of event does not exceed 1024 characters
     * TODO: Validation to ensure property key:value not exceed 1024 characters
     * TODO: Validation to ensure property is limited to single key:value pair
     * TODO: Validation that timestamp is ISO8601 format
     * @param string $name
     * @param string $identity
     * @param array|null $properties
     * @param string|null $timestamp
     * @param string|null $idempotencyKey
     */
    public function __construct(string $name, string $identity, array $properties = null, string $timestamp = null, string $idempotencyKey = null)
    {
        $this->setName($name);
        $this->setIdentity($identity);
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
            'identity' => $this->getIdentity(),
            'event' => $this->getName(),
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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Event
     */
    public function setName(string $name): Event
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * @param string $identity
     * @return Event
     */
    public function setIdentity(string $identity): Event
    {
        $this->identity = $identity;
        return $this;
    }

    /**
     * @return array|null
     */
    public function getProperties(): ?array
    {
        return $this->properties;
    }

    /**
     * TODO: Validation to ensure property key:value not exceed 1024 characters
     * TODO: Validation to ensure property is limited to single key:value pair
     * @param array|null $properties
     * @return Event
     */
    public function setProperties(?array $properties): Event
    {
        $this->properties = $properties;
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
     * @return Event
     */
    public function setTimestamp(?string $timestamp): Event
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
     * @return Event
     */
    public function setIdempotencyKey(?string $idempotencyKey): Event
    {
        $this->idempotencyKey = $idempotencyKey;
        return $this;
    }

    /*** End Getters and Setters ***/

}