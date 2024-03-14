<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\RawClient;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\Configuration\EndpointConfigurationInterface;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Interface RawClientInterface.
 *
 * @package CKPL\Pay\Client\RawClient
 */
interface RawClientInterface
{
    /**
     * @param string $url
     *
     * @return RawClientInterface
     */
    public function setUrl(string $url): RawClientInterface;

    /**
     * @param array                          $parameters
     * @param RequestModelInterface|null     $requestModel
     * @param EndpointConfigurationInterface $endpointConfiguration
     *
     * @return RawClientInterface
     */
    public function prepare(
        array $parameters,
        ?RequestModelInterface $requestModel,
        EndpointConfigurationInterface $endpointConfiguration
    ): RawClientInterface;

    /**
     * @param string $key
     * @param string $value
     *
     * @return RawClientInterface
     */
    public function addHeader(string $key, string $value): RawClientInterface;

    /**
     * @param string $username
     * @param string $password
     *
     * @return RawClientInterface
     */
    public function setUser(string $username, string $password): RawClientInterface;

    /**
     * @param bool $signedOutput
     *
     * @throws ClientException
     *
     * @return RawOutputInterface
     */
    public function execute(bool $signedOutput = false): RawOutputInterface;
}
