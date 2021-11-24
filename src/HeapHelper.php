<?php

namespace Mquinn\HeapHelper;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Mquinn\HeapHelper\Interfaces\HeapHelperInterface;
use Mquinn\HeapHelper\Resources\HeapAccount;
use Mquinn\HeapHelper\Resources\HeapAccounts;
use Mquinn\HeapHelper\Resources\HeapEvent;
use Mquinn\HeapHelper\Resources\HeapEvents;
use Mquinn\HeapHelper\Resources\HeapUser;
use Mquinn\HeapHelper\Resources\HeapUsers;
use PHPUnit\Util\Xml\ValidationResult;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use function Couchbase\defaultDecoder;

class HeapHelper implements HeapHelperInterface
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
     * @param HeapEvent $heapEvent
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function track(HeapEvent $heapEvent): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/track';
        $body = $heapEvent->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param HeapEvents $heapEvents
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function bulkTrack(HeapEvents $heapEvents): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/track';
        $body = $heapEvents->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param HeapUser $heapUser
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function addUserProperties(HeapUser $heapUser): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/add_user_properties';
        $body = $heapUser->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param HeapUsers $heapUsers
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function bulkAddUserProperties(HeapUsers $heapUsers): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/add_user_properties';
        $body = $heapUsers->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param HeapAccount $heapAccount
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function addAccountProperties(HeapAccount $heapAccount): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/add_account_properties';
        $body = $heapAccount->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param HeapAccounts $heapAccounts
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function bulkAddAccountProperties(HeapAccounts $heapAccounts): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/add_account_properties';
        $body = $heapAccounts->generateRequestBody();

        $request = $this->generateRequestInterface($method, $uri, $body);
        return $this->sendRequest($request);
    }

    /**
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function authorize(): HeapResponse
    {
        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/public/v0/auth_token';
        $headers = [
            'Authorization' => 'Basic '. base64_encode($this->getHeapConfiguration()->getAppId().':'.$this->getHeapConfiguration()->getApiKey()),
            'Content-Type' => 'application/json'
        ];
        $request = new Request($method, $uri, $headers);

        return $this->sendRequest($request);
    }

    /**
     * @param HeapUser $heapUser
     * @param string|null $accessToken
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function deleteUser(HeapUser $heapUser, ?string $accessToken = null): HeapResponse
    {
        if (is_null($accessToken)) {
            $accessToken = self::extractAccessToken($this->authorize());
        }

        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/public/v0/user_deletion';
        $body['users'][] = $heapUser->generateRequestBody();

        $headers = [
            'Authorization' => 'Bearer '. $accessToken,
            'Content-Type' => 'application/json'
        ];
        $request = new Request($method, $uri, $headers, json_encode($body));
        return $this->sendRequest($request);
    }

    /**
     * @param HeapUsers $heapUsers
     * @param string|null $accessToken
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function bulkDeleteUser(HeapUsers $heapUsers, ?string $accessToken = null): HeapResponse
    {
        if (is_null($accessToken)) {
            $accessToken = self::extractAccessToken($this->authorize());
        }

        $method = 'POST';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/public/v0/user_deletion';
        $body = $heapUsers->generateRequestBody();

        $headers = [
            'Authorization' => 'Bearer '. $accessToken,
            'Content-Type' => 'application/json'
        ];
        $request = new Request($method, $uri, $headers, json_encode($body));
        return $this->sendRequest($request);
    }

    /**
     * @param string $deleteRequestId
     * @param string|null $accessToken
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    public function verifyUserDeleted(string $deleteRequestId, ?string $accessToken = null): HeapResponse
    {
        if (is_null($accessToken)) {
            $accessToken = self::extractAccessToken($this->authorize());
        }

        $method = 'GET';
        $uri = $this->getHeapConfiguration()->getBaseUrl().'/api/public/v0/deletion_status/'.$deleteRequestId;

        $headers = [
            'Authorization' => 'Bearer '. $accessToken,
            'Content-Type' => 'application/json'
        ];
        $request = new Request($method, $uri, $headers);
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
     * @param RequestInterface $request
     * @return HeapResponse
     * @throws ClientExceptionInterface
     */
    protected function sendRequest(RequestInterface $request): HeapResponse
    {
        $response = $this->getClient()->sendRequest($request);
        return new HeapResponse($response);
    }

    /**
     * @param HeapResponse $response
     * @return string
     */
    public static function extractAccessToken(HeapResponse $response): string
    {
        $body = json_decode($response->getBody()->getContents(), true);
        return $body['access_token'];
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