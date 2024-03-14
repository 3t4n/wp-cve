<?php

namespace CKPL\Pay\Definition\Confirm;

interface ConfirmPaymentInterface
{
    /**
     * @return string
     */
    public function getBlikCode(): ?string;

    /**
     * @return string
     */
    public function getEmail(): string;

    /**
     * @return string
     */
    public function getFirstName(): string;

    /**
     * @return string
     */
    public function getLastName(): string;

    /**
     * @return string
     */
    public function getType(): string;

    /**
     * @return string
     */
    public function getToken(): string;

    /**
     * @return string
     */
    public function getAcceptLanguage(): ?string;

    /**
     * @return string
     */
    public function getNotificationsLocale(): ?string;

    /**
     * @return string|null
     */
    public function getUserScreenResolution(): ?string;

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string;

    /**
     * @return string|null
     */
    public function getUserIpAddress(): ?string;

    /**
     * @return string|null
     */
    public function getUserPort(): ?string;

    /**
     * @return string|null
     */
    public function getFingerprint(): ?string;
}
