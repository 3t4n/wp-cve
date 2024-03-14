<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint\ConfigurationFactory;

use CKPL\Pay\Endpoint\Configuration\EndpointConfigurationInterface;

/**
 * Interface EndpointConfigurationFactoryInterface.
 *
 * @package CKPL\Pay\Endpoint\ConfigurationFactory
 */
interface EndpointConfigurationFactoryInterface
{
    /**
     * @type string
     */
    const METHOD_GET = 'get';

    /**
     * @type string
     */
    const METHOD_POST = 'post';

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function toOidc(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function toPayments(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function asGet(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function asPost(): EndpointConfigurationFactoryInterface;

    /**
     * @param string $url
     *
     * @return EndpointConfigurationFactory
     */
    public function url(string $url): EndpointConfigurationFactory;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function encodeWithJson(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function disableJsonEncoding(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function expectSignedResponse(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function expectPlainResponse(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function signRequest(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function plainRequest(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function authorized(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function notAuthorized(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function withCredentials(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationFactoryInterface
     */
    public function withoutCredentials(): EndpointConfigurationFactoryInterface;

    /**
     * @return EndpointConfigurationInterface
     */
    public function build(): EndpointConfigurationInterface;
}
