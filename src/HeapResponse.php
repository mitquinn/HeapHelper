<?php

namespace Mitquinn\HeapHelper;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;

class HeapResponse extends Response
{

    /**
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        parent::__construct($response->getStatusCode(), $response->getHeaders(), $response->getBody(), $response->getProtocolVersion(), $response->getReasonPhrase());
    }

}