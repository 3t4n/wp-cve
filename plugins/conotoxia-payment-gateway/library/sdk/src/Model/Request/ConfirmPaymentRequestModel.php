<?php

namespace CKPL\Pay\Model\Request;

use CKPL\Pay\Endpoint\ConfirmPaymentEndpoint;
use CKPL\Pay\Model\RequestModelInterface;

/**
 * Class ConfirmPaymentRequestModel.
 *
 * @package CKPL\Pay\Model\Request
 */
class ConfirmPaymentRequestModel implements RequestModelInterface
{
    /**
     * @var string
     */
    private $blikCode;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $lastName;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @param string $blikCode
     * @return RequestModelInterface
     */
    public function setBlikCode(string $blikCode): RequestModelInterface
    {
        $this->blikCode = $blikCode;

        return $this;
    }

    /**
     * @param string $type
     * @return RequestModelInterface
     */
    public function setPaymentMethodType(string $type): RequestModelInterface
    {
        $this->type = $type;

        return $this;
    }


    /**
     * @param string $email
     * @return RequestModelInterface
     */
    public function setEmail(string $email): RequestModelInterface
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @param string $firstName
     * @return RequestModelInterface
     */
    public function setFirstName(string $firstName): RequestModelInterface
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * @param string $lastName
     * @return RequestModelInterface
     */
    public function setLastName(string $lastName): RequestModelInterface
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndpoint(): string
    {
        return ConfirmPaymentEndpoint::class;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return static::JSON_OBJECT;
    }

    /**
     * @return array
     */
    public function raw(): array
    {
        $additionalData = (object)[
            'email' => $this->email,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
        ];

        return [
            'blikCode' => $this->blikCode,
            'type' => $this->type,
            'additionalData' => $additionalData
        ];
    }
}
