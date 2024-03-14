<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint\Configuration;

/**
 * Interface EndpointConfigurationInterface.
 *
 * @package CKPL\Pay\Endpoint\Configuration
 */
interface EndpointConfigurationInterface
{
    /**
     * @return string|null
     */
    public function getHost(): ?string;

    /**
     * @return string|null
     */
    public function getMethod(): ?string;

    /**
     * @return string|null
     */
    public function getUrl(): ?string;

    /**
     * @return bool|null
     */
    public function getJson(): ?bool;

    /**
     * @return bool|null
     */
    public function getSignedRequest(): ?bool;

    /**
     * @return bool|null
     */
    public function getSignedResponse(): ?bool;

    /**
     * @return bool|null
     */
    public function getAuthorized(): ?bool;

    /**
     * @return bool|null
     */
    public function getCredentials(): ?bool;
}
