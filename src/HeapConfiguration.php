<?php

namespace Mitquinn\HeapHelper;

class HeapConfiguration
{
    /** @var string $apiKey */
    protected string $apiKey;

    /** @var string $appId */
    protected string $appId;

    /** @var string $baseUrl */
    protected string $baseUrl = 'https://heapanalytics.com';

    /**
     * @param string $apiKey
     * @param string $appId
     * @param string|null $baseUrl
     */
    public function __construct(string $apiKey, string $appId, ?string $baseUrl = null)
    {
        $this->setAppId($appId);
        $this->setApiKey($apiKey);
        if (!is_null($baseUrl)) {
            $this->setBaseUrl($baseUrl);
        }
    }

    /*** Start Getters and Setters ***/

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @param string $appId
     * @return HeapConfiguration
     */
    public function setAppId(string $appId): HeapConfiguration
    {
        $this->appId = $appId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->baseUrl;
    }

    /**
     * @param string $baseUrl
     * @return HeapConfiguration
     */
    public function setBaseUrl(string $baseUrl): HeapConfiguration
    {
        $this->baseUrl = $baseUrl;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey(): string
    {
        return $this->apiKey;
    }

    /**
     * @param string $apiKey
     * @return HeapConfiguration
     */
    public function setApiKey(string $apiKey): HeapConfiguration
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /*** End Getters and Setters ***/

}