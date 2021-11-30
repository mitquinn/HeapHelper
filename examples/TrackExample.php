<?php

namespace Mitquinn\HeapHelper\Examples;

use DateTime;
use Mitquinn\HeapHelper\HeapConfiguration;
use Mitquinn\HeapHelper\HeapHelper;
use Mitquinn\HeapHelper\Resources\HeapEvent;

class TrackExample extends InitializationExample
{

    /**
     * Use this API to send custom events to Heap server-side.
     * It is recommended for using events that need to exactly match your backend,
     * such as completed order transaction info,
     * or events that are not available for Heap to capture on the client-side.
     *
     * You can learn more about this API here:
     * @see https://developers.heap.io/reference/track-1
     */
    public function trackExample()
    {

        $event = new HeapEvent(
            'UserIsBuyingAnItem',
            'alice@example.com',
            [
                "item_number" => "555-item_number"
            ],
            new DateTime('now'), //You can back fill events by passing historical DateTimes in, otherwise this defaults to now
            'UniqueOrderNumber' //If you are worried about the same events being tracked twice you can pass a unique idempotency key. This will only allow the event to be recorded a single time in Heap.
        );

        $heapResponse = $this->heapHelper->track($event);

        if ($heapResponse->getStatusCode() == 200) {
            echo "The event was recorded to Heap.";
        } else {
            echo "The event was not recorded to Heap";
            echo $heapResponse->getReasonPhrase();
            echo $heapResponse->getBody()->getContents();
        }

    }

}