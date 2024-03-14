<?php

namespace Zahls\CommunicationAdapter;

/**
 * Class AbstractCommunication
 * @package Zahls\CommunicationAdapter
 */
abstract class AbstractCommunication
{
    /**
     * Perform an API request
     *
     * @param string $apiUrl
     * @param array  $params
     * @param string $method
     *
     * @return mixed
     */
    abstract public function requestApi($apiUrl, $params = array(), $method = 'POST', $httpHeader = array());
}
