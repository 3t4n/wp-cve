<?php

declare(strict_types=1);

namespace CKPL\Pay\Model;

/**
 * Interface ProcessedInputInterface.
 *
 * @package CKPL\Pay\Model
 */
interface ProcessedInputInterface
{
    /**
     * @return array
     */
    public function raw(): array;
}
