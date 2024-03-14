<?php

declare(strict_types=1);

namespace CKPL\Pay\Model\Response;

use CKPL\Pay\Endpoint\JwksEndpoint;
use CKPL\Pay\Model\ResponseModelInterface;

/**
 * Class PaymentServiceKeyResponseModel.
 *
 * @package CKPL\Pay\Model\Response
 */
class PaymentServiceKeyResponseModel implements ResponseModelInterface
{
    /**
     * @var string|null
     */
    protected $keyType;

    /**
     * @var string|null
     */
    protected $keyId;

    /**
     * @var string|null
     */
    protected $usage;

    /**
     * @var string|null
     */
    protected $modulus;

    /**
     * @var string|null
     */
    protected $exponent;

    /**
     * @return string|null
     */
    public function getKeyType(): ?string
    {
        return $this->keyType;
    }

    /**
     * @param string $keyType
     *
     * @return PaymentServiceKeyResponseModel
     */
    public function setKeyType(?string $keyType): PaymentServiceKeyResponseModel
    {
        $this->keyType = $keyType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getKeyId(): ?string
    {
        return $this->keyId;
    }

    /**
     * @param string $keyId
     *
     * @return PaymentServiceKeyResponseModel
     */
    public function setKeyId(string $keyId): PaymentServiceKeyResponseModel
    {
        $this->keyId = $keyId;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUsage(): ?string
    {
        return $this->usage;
    }

    /**
     * @param string $usage
     *
     * @return PaymentServiceKeyResponseModel
     */
    public function setUsage(string $usage): PaymentServiceKeyResponseModel
    {
        $this->usage = $usage;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getModulus(): ?string
    {
        return $this->modulus;
    }

    /**
     * @param string $modulus
     *
     * @return PaymentServiceKeyResponseModel
     */
    public function setModulus(string $modulus): PaymentServiceKeyResponseModel
    {
        $this->modulus = $modulus;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getExponent(): ?string
    {
        return $this->exponent;
    }

    /**
     * @param string $exponent
     *
     * @return PaymentServiceKeyResponseModel
     */
    public function setExponent(string $exponent): PaymentServiceKeyResponseModel
    {
        $this->exponent = $exponent;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return JwksEndpoint::class;
    }
}
