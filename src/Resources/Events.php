<?php

namespace Mquinn\HeapHelper\Resources;

use JetBrains\PhpStorm\Pure;

class Events
{
    /** @var Event[] $events  */
    protected array $events;

    /**
     * TODO: Validation to ensure array elements are really EVENTs objects
     * @param Event[] $events
     */
    public function __construct( array $events = [])
    {
        $this->setEvents($events);
    }

    /**
     * TODO: Validation to ensure number of events does not exceed 1000
     * @param Event $event
     * @return Events
     */
    public function addEvent(Event $event): Events
    {
        $events = $this->getEvents();
        array_push($events, $event);
        $this->setEvents($events);
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
        $events = $this->getEvents();
        foreach ($events as $event) {
            $bodyEvents[] = $event->generateRequestBody();
        }

        $body['events'] = $bodyEvents;
        return $body;
    }


    /*** Start Getters and Setters ***/

    /**
     * @return Event[]
     */
    public function getEvents(): array
    {
        return $this->events;
    }

    /**
     * * TODO: Validation to ensure number of events does not exceed 1000
     * @param Event[] $events
     * @return Events
     */
    public function setEvents(array $events): Events
    {
        $this->events = $events;
        return $this;
    }

    /*** End Getters and Setters ***/
}