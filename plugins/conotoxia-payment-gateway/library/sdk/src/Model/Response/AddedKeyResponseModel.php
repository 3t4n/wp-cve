<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\SendPublicKeyEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class AddedKeyResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class AddedKeyResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $keyId;

    /**
     * @var string|null
     */
    protected $status;

    /**
     * @return string|null
     */
    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    /**
     * @param string|null $keyId
     *
     * @return ResponseModelInterface
     */
    public function setKeyId(string $keyId): ResponseModelInterface
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     *
     * @return ResponseModelInterface
     */
    public function setStatus(string $status): ResponseModelInterface
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return SendPublicKeyEndpoint::class;
    }
}
