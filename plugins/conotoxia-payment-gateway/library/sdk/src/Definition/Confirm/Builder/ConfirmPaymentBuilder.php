<?php
declare(strict_types=1);

namespace CKPL\Pay\Definition\Confirm\Builder;

use CKPL\Pay\Definition\Confirm\ConfirmPayment;
use CKPL\Pay\Definition\Confirm\ConfirmPaymentInterface;
use CKPL\Pay\Exception\Definition\ConfirmPaymentException;

class ConfirmPaymentBuilder implements ConfirmPaymentBuilderInterface
{

    /**
     * @var ConfirmPayment
     */
    protected $confirmPayment;

    /**
     * ConfirmPaymentBuilder constructor.
     */
    public function __construct()
    {
        $this->initializeConfirmPayment();
    }

    /**
     * @param string $blikCode
     * @return $this
     */
    public function setBlikCode(string $blikCode): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setBlikCode($blikCode);

        return $this;
    }

    /**
     * @param string $type
     * @return $this
     */
    public function setType(string $type): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setType($type);

        return $this;
    }

    /**
     * @param string $firstName
     * @return $this
     */
    public function setFirstName(string $firstName): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setFirstName($firstName);

        return $this;
    }

    /**
     * @param string $lastName
     * @return $this
     */
    public function setLastName(string $lastName): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setLastName($lastName);

        return $this;
    }

    /**
     * @param string $token
     * @return $this
     */
    public function setToken(string $token): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setToken($token);

        return $this;
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setEmail($email);

        return $this;
    }

    /**
     * @param string|null $acceptLanguage
     * @return $this
     */
    public function setAcceptLanguage(?string $acceptLanguage): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setAcceptLanguage($acceptLanguage);

        return $this;
    }

    /**
     * @param string|null $notificationsLocale
     * @return $this
     */
    public function setNotificationsLocale(?string $notificationsLocale): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setNotificationsLocale($notificationsLocale);

        return $this;
    }

    /**
     * @param string|null $userScreenResolution
     * @return $this
     */
    public function setUserScreenResolution(?string $userScreenResolution): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setUserScreenResolution($userScreenResolution);

        return $this;
    }

    /**
     * @param string|null $userAgent
     * @return $this
     */
    public function setUserAgent(?string $userAgent): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setUserAgent($userAgent);

        return $this;
    }

    /**
     * @param string|null $userIpAddress
     * @return $this
     */
    public function setUserIpAddress(?string $userIpAddress): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setUserIpAddress($userIpAddress);

        return $this;
    }

    /**
     * @param string|null $userPort
     * @return $this
     */
    public function setUserPort(?string $userPort): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setUserPort($userPort);

        return $this;
    }

    /**
     * @param string|null $fingerprint
     * @return $this
     */
    public function setFingerprint(?string $fingerprint): ConfirmPaymentBuilder
    {
        $this->confirmPayment->setFingerprint($fingerprint);

        return $this;
    }

    /**
     * @throws ConfirmPaymentException
     */
    public function getConfirmPayment(): ConfirmPaymentInterface
    {
        if (null === $this->confirmPayment->getToken()) {
            throw new ConfirmPaymentException('Missing token in confirm payment request.');
        }

        if (null === $this->confirmPayment->getLastName()) {
            throw new ConfirmPaymentException('Missing last name in confirm payment request.');
        }

        if (null === $this->confirmPayment->getType()) {
            throw new ConfirmPaymentException('Missing type in confirm payment request.');
        }

        if (null === $this->confirmPayment->getFirstName()) {
            throw new ConfirmPaymentException('Missing first name in confirm payment request.');
        }

        if (null === $this->confirmPayment->getBlikCode()) {
            throw new ConfirmPaymentException('Missing blik code in confirm payment request.');
        }

        if (null === $this->confirmPayment->getEmail()) {
            throw new ConfirmPaymentException('Missing email in confirm payment request.');
        }

        return $this->confirmPayment;
    }

    /**
     * @return void
     */
    protected function initializeConfirmPayment(): void
    {
        $this->confirmPayment = new ConfirmPayment();
    }
}
