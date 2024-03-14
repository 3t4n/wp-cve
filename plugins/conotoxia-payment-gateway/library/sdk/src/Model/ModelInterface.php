<?php

declare(strict_types=1);

namespace CKPL\Pay\Model;

/**
 * Interface ModelInterface.
 *
 * @package CKPL\Pay\Model
 */
interface ModelInterface
{
    /**
     * @return string
     */
    public function getEndpoint(): string;
}
