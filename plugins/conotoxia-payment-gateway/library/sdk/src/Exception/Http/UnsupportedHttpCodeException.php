<?php

declare(strict_types=1);

namespace CKPL\Pay\Exception\Http;

use function sprintf;

/**
 * Class UnsupportedHttpCodeException.
 *
 * @package CKPL\Pay\Exception\Http
 */
class UnsupportedHttpCodeException extends HttpException
{
    /**
     * @var int
     */
    protected $statusCode;

    /**
     * UnsupportedHttpCodeException constructor.
     *
     * @param int $statusCode
     */
    public function __construct(int $statusCode)
    {
        parent::__construct(sprintf('Unsupported HTTP code %d.', $statusCode));

        $this->statusCode = $statusCode;
    }

    /**
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }
}
