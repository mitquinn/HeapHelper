<?php

namespace Mquinn\HeapHelper;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Mquinn\HeapHelper\Resources\Event;
use Mquinn\HeapHelper\Resources\Events;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class HeapHelper
{

    /** @var HeapConfiguration $heapConfiguration */
    protected HeapConfiguration $heapConfiguration;

    /** @var ClientInterface $client */
    protected ClientInterface $client;

    /**
     * @param ClientInterface|null $client
     */
    public function __construct(HeapConfiguration $heapConfiguration, ClientInterface $client = null)
    {
        $this->setHeapConfiguration($heapConfiguration);
        if (is_null($client)) {
            $client = new Client([
                'headers' => [
                    'Content-Type' => 'application/json'
                ]
            ]);
        }
        $this->setClient($client);
    }

    /**
     * TODO: Should I be returning response interface? Could return a bool?
     * @param Event $event
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function track(Event $event): ResponseInterface
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/track';
        $body = $event->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param Events $events
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    public function bulkTrack(Events $events): ResponseInterface
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/track';
        $body = $events->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }


    /**
     * @param string $method
     * @param string $uri
     * @param array $body
     * @param array $header
     * @return RequestInterface
     */
    protected function generateRequestInterface(
        string $method,
        string $uri,
        array $body,
        array $header = []
    ): RequestInterface
    {
        $body['app_id'] = $this->getHeapConfiguration()->getAppId();

        return new Request(
            $method,
            $uri,
            $header,
            json_encode($body)
        );
    }


    /**
     * TODO: Add some exception handling and response checking
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     */
    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        return $this->getClient()->sendRequest($request);
    }


    /*** Start Getters and Setters ***/

    /**
     * @return ClientInterface
     */
    public function getClient(): ClientInterface
    {
        return $this->client;
    }

    /**
     * @param ClientInterface $client
     * @return HeapHelper
     */
    public function setClient(ClientInterface $client): HeapHelper
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return HeapConfiguration
     */
    public function getHeapConfiguration(): HeapConfiguration
    {
        return $this->heapConfiguration;
    }

    /**
     * @param HeapConfiguration $heapConfiguration
     * @return HeapHelper
     */
    public function setHeapConfiguration(HeapConfiguration $heapConfiguration): HeapHelper
    {
        $this->heapConfiguration = $heapConfiguration;
        return $this;
    }

    /*** End Getters and Setters ***/

}