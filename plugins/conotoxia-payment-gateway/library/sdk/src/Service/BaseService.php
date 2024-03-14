<?php

declare(strict_types=1);

namespace CKPL\Pay\Service;

use CKPL\Pay\Configuration\Configuration;
use CKPL\Pay\PayInterface;
use CKPL\Pay\Service\Factory\DependencyFactory;

/**
 * Class BaseService.
 *
 * @package CKPL\Pay\Service
 */
abstract class BaseService implements ServiceInterface
{
    /**
     * @var Configuration
     */
    protected $configuration;

    /**
     * @var DependencyFactory
     */
    protected $dependencyFactory;

    /**
     * @type string
     */
    const UNSUPPORTED_RESPONSE_MODEL_EXCEPTION = 'Unsupported response model.';

    /**
     * BaseService constructor.
     *
     * @param PayInterface $pay
     */
    public function __construct(PayInterface $pay)
    {
        $this->configuration = $pay->getConfiguration();
        $this->dependencyFactory = $pay->getDependencyFactory();
    }
}
