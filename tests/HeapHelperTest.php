<?php

use Mquinn\HeapHelper\HeapConfiguration;
use Mquinn\HeapHelper\HeapHelper;
use Mquinn\HeapHelper\Resources\HeapEvent;
use Mquinn\HeapHelper\Resources\HeapEvents;
use Mquinn\HeapHelper\Resources\HeapUser;
use Mquinn\HeapHelper\Resources\HeapUsers;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientExceptionInterface;

class HeapHelperTest extends TestCase
{
    /** @var HeapHelper $heapHelper */
    protected HeapHelper $heapHelper;

    protected function setUp(): void
    {
        parent::setUp();

        //Load configuration
        $dotenv = new Symfony\Component\Dotenv\Dotenv();
        $dotenv->load(__DIR__.'/../.env.dev');

        $appId = $_ENV['HEAP_APP_ID'];
        $apiKey = $_ENV['HEAP_API_KEY'];

        $heapConfiguration = new HeapConfiguration($apiKey, $appId);
        $heapHelper = new Mquinn\HeapHelper\HeapHelper($heapConfiguration);
        $this->setHeapHelper($heapHelper);
    }

    /**
     * TODO: Add Timestamp and IdempotencyKey
     * @throws ClientExceptionInterface
     */
    public function testTrack()
    {
        //
        $event = new HeapEvent(
            'API_UNIT_TEST',
            'api_unit_test@example.com',
            ['api_unit_test' => 'api_unit_test']
        );

        $response = $this->getHeapHelper()->track($event);
        $statusCode = $response->getStatusCode();
        self::assertEquals(200, $statusCode);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testBulkTrack()
    {
        $event1 = new HeapEvent(
            'API_UNIT_TEST',
            'api_unit_test@example.com',
            ['api_unit_test' => 'api_unit_test']
        );

        $event2 = new HeapEvent(
            'API_UNIT_TEST2',
            'api_unit_test2@example.com',
            ['api_unit_test' => 'api_unit_test2']
        );

        $events = new HeapEvents([$event1]);

        //TODO: Split this into its own test
        $events = $events->addEvent($event2);

        $response = $this->getHeapHelper()->bulkTrack($events);
        var_dump($response->getBody()->getContents());
        static::assertEquals(200, $response->getStatusCode());
    }

    public function testAuthorize()
    {
        $response = $this->getHeapHelper()->authorize();
        static::assertEquals(200, $response->getStatusCode());
    }

    public function testDeleteUser()
    {
        $heapUser = new HeapUser('api_unit_test@example.com');
        $deleteUserResponse = $this->getHeapHelper()->deleteUser($heapUser);
        static::assertEquals(201, $deleteUserResponse->getStatusCode());
    }

    public function testBulkDeleteUsers()
    {
        $heapUser = new HeapUser('api_unit_test@example.com');
        $heapUsers = new HeapUsers([$heapUser]);

        $bulkDeleteUserResponse = $this->getHeapHelper()->bulkDeleteUser($heapUsers);
        static::assertEquals(201, $bulkDeleteUserResponse->getStatusCode());
    }

    public function testVerifyDeleteUser()
    {
        //TODO: Should add the creation of user here.

        //Deleting the User
        $heapUser = new HeapUser('api_unit_test2@example.com');
        $deleteUserResponse = $this->getHeapHelper()->deleteUser($heapUser);
        static::assertEquals(201, $deleteUserResponse->getStatusCode());
        $deleteBody = json_decode($deleteUserResponse->getBody()->getContents(), true);

        //Verifying deletion of user.
        $verifyDeleteUserResponse = $this->getHeapHelper()->verifyUserDeleted($deleteBody['deletion_request_id']);
        static::assertEquals(200, $verifyDeleteUserResponse->getStatusCode());
    }



    /**
     * @return HeapHelper
     */
    public function getHeapHelper(): HeapHelper
    {
        return $this->heapHelper;
    }

    /**
     * @param HeapHelper $heapHelper
     * @return HeapHelperTest
     */
    public function setHeapHelper(HeapHelper $heapHelper): HeapHelperTest
    {
        $this->heapHelper = $heapHelper;
        return $this;
    }


}
