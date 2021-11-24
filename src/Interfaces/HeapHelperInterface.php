<?php

namespace Mquinn\HeapHelper\Interfaces;

use Mquinn\HeapHelper\HeapResponse;
use Mquinn\HeapHelper\Resources\HeapAccount;
use Mquinn\HeapHelper\Resources\HeapAccounts;
use Mquinn\HeapHelper\Resources\HeapEvent;
use Mquinn\HeapHelper\Resources\HeapEvents;
use Mquinn\HeapHelper\Resources\HeapUser;
use Mquinn\HeapHelper\Resources\HeapUsers;

interface HeapHelperInterface
{
    /**
     * @param HeapEvent $heapEvent
     * @return HeapResponse
     */
    public function track(HeapEvent $heapEvent): HeapResponse;

    /**
     * @param HeapEvents $heapEvents
     * @return HeapResponse
     */
    public function bulkTrack(HeapEvents $heapEvents): HeapResponse;

    /**
     * @param HeapUser $heapUser
     * @return HeapResponse
     */
    public function addUserProperties(HeapUser $heapUser): HeapResponse;


    /**
     * @param HeapUsers $heapUsers
     * @return HeapResponse
     */
    public function bulkAddUserProperties(HeapUsers $heapUsers): HeapResponse;

    /**
     * @param HeapAccount $heapAccount
     * @return HeapResponse
     */
    public function addAccountProperties(HeapAccount $heapAccount): HeapResponse;

    /**
     * @param HeapAccounts $heapAccounts
     * @return HeapResponse
     */
    public function bulkAddAccountProperties(HeapAccounts $heapAccounts): HeapResponse;

    /**
     * @return HeapResponse
     */
    public function authorize(): HeapResponse;

    /**
     * @param HeapUser $heapUser
     * @param string|null $accessToken
     * @return HeapResponse
     */
    public function deleteUser(HeapUser $heapUser, ?string $accessToken = null): HeapResponse;

    /**
     * @param HeapUsers $heapUser
     * @param string|null $accessToken
     * @return HeapResponse
     */
    public function bulkDeleteUser(HeapUsers $heapUser, ?string $accessToken = null): HeapResponse;

    /**
     * @param string $deleteRequestId
     * @param string|null $accessToken
     * @return HeapResponse
     */
    public function verifyUserDeleted(string $deleteRequestId, ?string $accessToken = null): HeapResponse;

}