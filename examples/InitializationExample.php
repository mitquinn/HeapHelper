<?php
namespace Mitquinn\HeapHelper\Examples;

use Mitquinn\HeapHelper\HeapConfiguration;
use Mitquinn\HeapHelper\HeapHelper;

class InitializationExample
{
    protected HeapHelper $heapHelper;

    /**
     * @param string $apiKey
     * @param string $appId
     */
    public function __construct(string $apiKey, string $appId)
    {
        /**
         * apiKey
         * You get this API Key from Heap.
         * Login > Account > Manage > Privacy & Security > Use the API (an option will appear to generate an API token)
         */

        /**
         * appId
         * You get this App ID from Heap.
         * Login > Account > Manage > Privacy & Security > Use the API - Your App Id will be displayed for you.
         */

        //Initialize the HeapConfiguration class with the apiKey and appId into the configuration.
        $heapConfiguration = new HeapConfiguration($apiKey, $appId);

        //Initialize the HeapHelper class with the configuration.
        $heapHelper = new HeapHelper($heapConfiguration);

        $this->heapHelper = $heapHelper;
    }

}