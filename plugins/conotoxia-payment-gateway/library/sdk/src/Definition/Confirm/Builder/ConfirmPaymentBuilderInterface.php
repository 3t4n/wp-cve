<?php

namespace CKPL\Pay\Definition\Confirm\Builder;

use CKPL\Pay\Definition\Confirm\ConfirmPaymentInterface;

/**
 * Interface ConfirmPaymentBuilder.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
interface ConfirmPaymentBuilderInterface
{
    /**
     * @param string $blikCode
     * @return ConfirmPaymentBuilder
     */
    public function setBlikCode(string $blikCode): ConfirmPaymentBuilder;

    /**
     * @param string $type
     * @return ConfirmPaymentBuilder
     */
    public function setType(string $type): ConfirmPaymentBuilder;

    /**
     * @param string $firstName
     * @return ConfirmPaymentBuilder
     */
    public function setFirstName(string $firstName): ConfirmPaymentBuilder;

    /**
     * @param string $lastName
     * @return ConfirmPaymentBuilder
     */
    public function setLastName(string $lastName): ConfirmPaymentBuilder;

    /**
     * @param string $email
     * @return ConfirmPaymentBuilder
     */
    public function setEmail(string $email): ConfirmPaymentBuilder;

    /**
     * @param string $token
     * @return ConfirmPaymentBuilder
     */
    public function setToken(string $token): ConfirmPaymentBuilder;

    /**
     * Allows you to control the notifications language. Should be compatible with RFC7231 section 5.3.5. Parameter
     * should be taken from user browser.
     * @param string|null $acceptLanguage
     * @return ConfirmPaymentBuilder
     */
    public function setAcceptLanguage(?string $acceptLanguage): ConfirmPaymentBuilder;

    /**
     * Control the language of notifications. Parameter overwrites accept language header value. Should be compatible
     * with BCP47.
     * @param string|null $notificationsLocale
     * @return ConfirmPaymentBuilder
     */
    public function setNotificationsLocale(?string $notificationsLocale): ConfirmPaymentBuilder;

    /**
     * @param string|null $userScreenResolution
     * @return ConfirmPaymentBuilder
     */
    public function setUserScreenResolution(?string $userScreenResolution): ConfirmPaymentBuilder;

    /**
     * @param string|null $userAgent
     * @return ConfirmPaymentBuilder
     */
    public function setUserAgent(?string $userAgent): ConfirmPaymentBuilder;

    /**
     * @param string|null $userIpAddress
     * @return ConfirmPaymentBuilder
     */
    public function setUserIpAddress(?string $userIpAddress): ConfirmPaymentBuilder;

    /**
     * @param string|null $userPort
     * @return ConfirmPaymentBuilder
     */
    public function setUserPort(?string $userPort): ConfirmPaymentBuilder;

    /**
     * @param string|null $fingerprint
     * @return ConfirmPaymentBuilder
     */
    public function setFingerprint(?string $fingerprint): ConfirmPaymentBuilder;

    /**
     * @return ConfirmPaymentInterface
     */
    public function getConfirmPayment(): ConfirmPaymentInterface;
}
