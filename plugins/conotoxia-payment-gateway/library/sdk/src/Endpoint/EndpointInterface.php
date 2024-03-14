<?php

declare(strict_types=1);

namespace CKPL\Pay\Endpoint;

use CKPL\Pay\Client\RawOutput\RawOutputInterface;
use CKPL\Pay\Endpoint\ConfigurationFactory\EndpointConfigurationFactoryInterface;
use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;

/**
 * Interface EndpointInterface.
 *
 * @package CKPL\Pay\Endpoint
 */
interface EndpointInterface
{
    /**
     * @param EndpointConfigurationFactoryInterface $configurationFactory
     *
     * @return void
     */
    public function configuration(EndpointConfigurationFactoryInterface $configurationFactory): void;

    /**
     * @param array $parameters
     *
     * @return ProcessedInputInterface|null
     */
    public function processRawInput(array $parameters): ?ProcessedInputInterface;

    /**
     * @param RawOutputInterface $rawOutput
     *
     * @return ProcessedOutputInterface
     */
    public function processRawOutput(RawOutputInterface $rawOutput): ProcessedOutputInterface;
}
