<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\Response;

use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;

/**
 * Interface ResponseInterface.
 *
 * @package CKPL\Pay\Client\Response
 */
interface ResponseInterface
{
    /**
     * @return ProcessedInputInterface|null
     */
    public function getProcessedInput(): ?ProcessedInputInterface;

    /**
     * @return ProcessedOutputInterface
     */
    public function getProcessedOutput(): ProcessedOutputInterface;
}
