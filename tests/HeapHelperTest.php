<?php

use Mquinn\HeapHelper\HeapConfiguration;
use Mquinn\HeapHelper\HeapHelper;
use Mquinn\HeapHelper\Resources\HeapAccount;
use Mquinn\HeapHelper\Resources\HeapAccounts;
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

        try {
            $dotenv->load(__DIR__.'/../.env.dev');
        } catch (Exception $exception) {
            //If this fails to load no worries.
        }


        $appId = $_ENV['HEAP_APP_ID'];
        $apiKey = $_ENV['HEAP_API_KEY'];

        $heapConfiguration = new HeapConfiguration($apiKey, $appId);
        $heapHelper = new Mquinn\HeapHelper\HeapHelper($heapConfiguration);
        $this->setHeapHelper($heapHelper);
    }

    /**
     * @throws ClientExceptionInterface
     */
    public function testTrack()
    {
        $event = new HeapEvent(
            'API_UNIT_TEST',
            'api_unit_test@example.com',
            ['api_unit_test' => 'api_unit_test', 'account_id' => 'unit_test']
        );

        $event->setTimestamp(new DateTime('2017-03-10T22:21:56+00:00'));
        $event->setIdempotencyKey(str_shuffle('asdoifhosadfhasfasdf'));

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
            ['api_unit_test' => 'api_unit_test', 'account_id2' => 'unit_test']
        );

        $event2 = new HeapEvent(
            'API_UNIT_TEST2',
            'api_unit_test2@example.com',
            ['api_unit_test' => 'api_unit_test2']
        );

        $events = new HeapEvents([$event1]);
        $events = $events->addEvent($event2);

        $response = $this->getHeapHelper()->bulkTrack($events);
        static::assertEquals(200, $response->getStatusCode());
    }

    public function testAddUserProperties()
    {
        $event = new HeapEvent(
            'API_UNIT_TEST',
            'api_unit_test@example.com',
            ['api_unit_test' => 'api_unit_test']
        );
        $this->getHeapHelper()->track($event);

        $user = new HeapUser('api_unit_test@example.com', ['test_property' => 'set']);
        $response = $this->getHeapHelper()->addUserProperties($user);
        static::assertEquals(200, $response->getStatusCode());
    }

    public function testBulkAddUserProperties()
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
        $events = new HeapEvents([$event1, $event2]);
        $this->getHeapHelper()->bulkTrack($events);

        $user1 = new HeapUser('api_unit_test@example.com', ['test_property' => 'set']);
        $user2 = new HeapUser('api_unit_test2@example.com', ['test_property2' => 'set2']);

        $users = new HeapUsers([$user1]);
        $users->addHeapUser($user2);

        $response = $this->getHeapHelper()->bulkAddUserProperties($users);
        static::assertEquals(200, $response->getStatusCode());
    }

    public function testAddAccountProperties()
    {
        $account = new HeapAccount('account_id', ['account_property' => "unit_test"]);
        $response = $this->getHeapHelper()->addAccountProperties($account);
        static::assertEquals(200, $response->getStatusCode());
    }

    public function testBulkAddAccountProperties()
    {
        $account = new HeapAccount('account_id', ['account_property' => "unit_test"]);
        $account2 = new HeapAccount('account_id2', ['account_property' => "unit_test"]);

        $accounts = new HeapAccounts([$account]);
        $accounts->addHeapAccount($account2);

        $response = $this->getHeapHelper()->bulkAddAccountProperties($accounts);
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
        //Deleting the User
        $heapUser = new HeapUser('api_unit_test2@example.com');
        $deleteUserResponse = $this->getHeapHelper()->deleteUser($heapUser);
        static::assertEquals(201, $deleteUserResponse->getStatusCode());
        $deleteBody = json_decode($deleteUserResponse->getBody()->getContents(), true);

        //Verifying deletion of user.
        $verifyDeleteUserResponse = $this->getHeapHelper()->verifyUserDeleted($deleteBody['deletion_request_id']);
        static::assertEquals(200, $verifyDeleteUserResponse->getStatusCode());
    }

    public function testHeapConfigurationInitialization()
    {
        $heapConfiguration = new HeapConfiguration('dummy_api', 'dummy_app', 'dummy base url');
        static::assertInstanceOf(HeapConfiguration::class, $heapConfiguration);
    }

    public function testHeapUserIdentityIsNotGreaterThan255()
    {
        static::expectException(InvalidArgumentException::class);
        new HeapUser(str_repeat('tests',255));
    }

    public function testHeapProperties1()
    {
        static::expectException(InvalidArgumentException::class);
        new HeapUser('test', ["wow"]);
    }

    public function testHeapProperties2()
    {
        static::expectException(InvalidArgumentException::class);
        $string = str_repeat('tests',255);
        new HeapUser('test', [$string => 'test']);
    }

    public function testHeapProperties3()
    {
        static::expectException(InvalidArgumentException::class);
        new HeapUser('test', ["wow" => ['test']]);
    }

    public function testHeapProperties4()
    {
        static::expectException(InvalidArgumentException::class);
        $string = str_repeat('tests',255);
        new HeapUser('test', ["wow" => $string]);
    }

    public function testHeapEventName1()
    {
        static::expectException(InvalidArgumentException::class);
        $string = str_repeat('tests',255);
        new HeapEvent($string, "test");
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
