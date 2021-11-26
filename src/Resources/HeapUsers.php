<?php

namespace Mitquinn\HeapHelper\Resources;

use JetBrains\PhpStorm\Pure;
use Mitquinn\HeapHelper\Interfaces\HeapResourceInterface;

class HeapUsers implements HeapResourceInterface
{
    /** @var HeapUser[] $heapUsers */
    protected array $heapUsers;

    /**
     * @param HeapUser[]|null $heapUsers
     */
    public function __construct(?array $heapUsers = null)
    {
        $this->setHeapUsers($heapUsers);
    }

    /**
     * @param HeapUser $heapUser
     * @return HeapUsers
     */
    public function addHeapUser(HeapUser $heapUser): HeapUsers
    {
        $heapUsers = $this->getHeapUsers();
        array_push($heapUsers, $heapUser);
        $this->setHeapUsers($heapUsers);
        return $this;
    }


    /**
     * @return array
     */
    #[Pure]
    public function generateRequestBody(): array
    {
        $body = [];
        $bodyEvents = [];
        $heapUsers = $this->getHeapUsers();
        foreach ($heapUsers as $heapUser) {
            $bodyEvents[] = $heapUser->generateRequestBody();
        }
        $body['users'] = $bodyEvents;
        return $body;
    }

    /*** Start Getters and Setters  ***/

    /**
     * @return HeapUser[]
     */
    public function getHeapUsers(): array
    {
        return $this->heapUsers;
    }

    /**
     * @param HeapUser[] $heapUsers
     * @return HeapUsers
     */
    public function setHeapUsers(array $heapUsers): HeapUsers
    {
        $this->heapUsers = $heapUsers;
        return $this;
    }

    /*** End Getters and Setters  ***/

}