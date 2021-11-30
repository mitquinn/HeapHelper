<?php

namespace Mitquinn\HeapHelper\Examples;

use DateTime;
use Mitquinn\HeapHelper\Resources\HeapEvent;
use Mitquinn\HeapHelper\Resources\HeapEvents;

/**
 * Quick example of the bulkTrack endpoint.
 */
class BulkTrackExample extends InitializationExample
{

    /**
     * Heap Limits API requests to 30 requests per 30 seconds.
     *
     * For situations where you have a significant amount of events to track (like back filling data to Heap)
     * You can use the BulkTrack endpoint.
     *
     * This is the same as the Track endpoint, but it allows you to pass 1000 events per request.
     *
     * @note You are still limited to 15,000 events per minute per App Id.
     */
    public function bulkTrackExample()
    {

        $heapEvent = new HeapEvent(
            'UserIsBuyingAnItem',
            'alice@example.com',
            [
                "item_number" => "555-item_number"
            ],
            new DateTime('now'), //You can backfill events by passing historical DateTimes in, otherwise this defaults to now
            'UniqueOrderNumber' //If you are worried about the same events being tracked twice you can pass a unique idempotency key. This will only allow the event to be recorded a single time in Heap.
        );

        /**
         * The heapEvents resource allows you to initialize with an array of events.
         *
         * @note It is highly recommend to use idempotency keys with the bulkTrack endpoint. As a singular event could fail causing sequential failures.
         */
        $heapEvents = new HeapEvents([$heapEvent]);

        //
        $heapEvent2 = new HeapEvent(
            'UserIsBuyingAnItem',
            'alice@example.com',
            [
                "item_number" => "444-item_number"
            ],
            new DateTime('now'), //You can backfill events by passing historical DateTimes in, otherwise this defaults to now
            'UniqueOrderNumber' //If you are worried about the same events being tracked twice you can pass a unique idempotency key. This will only allow the event to be recorded a single time in Heap.
        );

        //Or you can add individual heapEvent to the heapEvents resource like so
        $heapEvents->addEvent($heapEvent2);

        //Finally, send the events to Heap use the bulkTrack method.
        $heapResponse = $this->heapHelper->bulkTrack($heapEvents);

        if ($heapResponse->getStatusCode() == 200) {
            echo "The events were recorded to Heap.";
        } else {
            echo "The events were not recorded to Heap";
            echo $heapResponse->getReasonPhrase();
            echo $heapResponse->getBody()->getContents();
        }

    }

}