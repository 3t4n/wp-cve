<?php

declare(strict_types=1);

namespace CKPL\Pay\Client\Response;

use CKPL\Pay\Model\ProcessedInputInterface;
use CKPL\Pay\Model\ProcessedOutputInterface;

/**
 * Class Response.
 *
 * @package CKPL\Pay\Client\Response
 */
class Response implements ResponseInterface
{
    /**
     * @var ProcessedInputInterface|null
     */
    protected $processedInput;

    /**
     * @var ProcessedOutputInterface
     */
    protected $processedOutput;

    /**
     * Response constructor.
     *
     * @param ProcessedInputInterface|null $processedInput
     * @param ProcessedOutputInterface     $processedOutput
     */
    public function __construct(?ProcessedInputInterface $processedInput, ProcessedOutputInterface $processedOutput)
    {
        $this->processedInput = $processedInput;
        $this->processedOutput = $processedOutput;
    }

    /**
     * @return ProcessedInputInterface|null
     */
    public function getProcessedInput(): ?ProcessedInputInterface
    {
        return $this->processedInput;
    }

    /**
     * @return ProcessedOutputInterface
     */
    public function getProcessedOutput(): ProcessedOutputInterface
    {
        return $this->processedOutput;
    }
}
