<?php

namespace Mitquinn\HeapHelper\Resources;

use JetBrains\PhpStorm\Pure;
use Mitquinn\HeapHelper\Interfaces\HeapResourceInterface;

class HeapEvents implements HeapResourceInterface
{
    /** @var HeapEvent[] $heapEvents  */
    protected array $heapEvents;

    /**
     * TODO: Validation to ensure array elements are really EVENTs objects
     * @param HeapEvent[] $heapEvents
     */
    public function __construct( array $heapEvents = [])
    {
        $this->setHeapEvents($heapEvents);
    }

    /**
     * TODO: Validation to ensure number of events does not exceed 1000
     * @param HeapEvent $heapEvent
     * @return HeapEvents
     */
    public function addEvent(HeapEvent $heapEvent): HeapEvents
    {
        $events = $this->getHeapEvents();
        array_push($events, $heapEvent);
        $this->setHeapEvents($events);
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
        $heapEvents = $this->getHeapEvents();
        foreach ($heapEvents as $heapEvent) {
            $bodyEvents[] = $heapEvent->generateRequestBody();
        }

        $body['events'] = $bodyEvents;
        return $body;
    }


    /*** Start Getters and Setters ***/

    /**
     * @return HeapEvent[]
     */
    public function getHeapEvents(): array
    {
        return $this->heapEvents;
    }

    /**
     * * TODO: Validation to ensure number of events does not exceed 1000
     * @param HeapEvent[] $heapEvents
     * @return HeapEvents
     */
    public function setHeapEvents(array $heapEvents): HeapEvents
    {
        $this->heapEvents = $heapEvents;
        return $this;
    }

    /*** End Getters and Setters ***/
}