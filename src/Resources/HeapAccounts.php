<?php

namespace Mquinn\HeapHelper\Resources;

use JetBrains\PhpStorm\Pure;
use Mquinn\HeapHelper\Interfaces\HeapResourceInterface;

class HeapAccounts implements HeapResourceInterface
{
    /** @var HeapAccount[]|null $heapAccounts*/
    private ?array $heapAccounts;

    /**
     * @param HeapAccount[]|null $heapAccounts
     */
    public function __construct(?array $heapAccounts = null)
    {
        $this->setHeapAccounts($heapAccounts);
    }

    /**
     * @param HeapAccount $heapAccount
     * @return HeapAccounts
     */
    public function addHeapAccount(HeapAccount $heapAccount): HeapAccounts
    {
        $heapAccounts = $this->getHeapAccounts();
        array_push($heapAccounts, $heapAccount);
        $this->setHeapAccounts($heapAccounts);
        return $this;
    }

    /**
     * @return array
     */
    #[Pure]
    public function generateRequestBody(): array
    {
        $body = [];
        $bodyAccounts = [];
        $heapAccounts = $this->getHeapAccounts();
        foreach ($heapAccounts as $heapAccount) {
            $bodyAccounts[] = $heapAccount->generateRequestBody();
        }
        $body['accounts'] = $bodyAccounts;
        return $body;
    }

    /*** Start Getters and Setters ***/

    /**
     * @return HeapAccount[]|null
     */
    public function getHeapAccounts(): ?array
    {
        return $this->heapAccounts;
    }

    /**
     * @param HeapAccount[]|null $heapAccounts
     */
    public function setHeapAccounts(?array $heapAccounts): void
    {
        $this->heapAccounts = $heapAccounts;
    }

    /*** End Getters and Setters ***/

}