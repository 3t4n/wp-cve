<?php

namespace CKPL\Pay\Definition\Confirm;

class ConfirmPayment implements ConfirmPaymentInterface
{
    /**
     * @var string|null
     */
    protected $blikCode;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $token;

    /**
     * @var string
     */
    protected $acceptLanguage;

    /**
     * @var string
     */
    protected $notificationsLocale;

    /**
     * @var string|null
     */
    protected $userPort;

    /**
     * @var string|null
     */
    protected $userIpAddress;

    /**
     * @var string|null
     */
    protected $userScreenResolution;

    /**
     * @var string|null
     */
    protected $userAgent;

    /**
     * @var string|null
     */
    protected $fingerprint;

    /**
     * @return string|null
     */
    public function getBlikCode(): ?string
    {
        return $this->blikCode;
    }

    /**
     * @param string $blikCode
     * @return void
     */
    public function setBlikCode(string $blikCode): void
    {
        $this->blikCode = $blikCode;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     * @return void
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     * @return void
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     * @return void
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;

    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return void
     */
    public function setType(string $type): void
    {
        $this->type = $type;

    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     * @return void
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return string
     */
    public function getAcceptLanguage(): ?string
    {
        return $this->acceptLanguage;
    }

    /**
     * @param string|null $acceptLanguage
     * @return void
     */
    public function setAcceptLanguage(?string $acceptLanguage): void
    {
        $this->acceptLanguage = $acceptLanguage;
    }

    /**
     * @return string
     */
    public function getNotificationsLocale(): ?string
    {
        return $this->notificationsLocale;
    }

    /**
     * @param string|null $notificationsLocale
     * @return void
     */
    public function setNotificationsLocale(?string $notificationsLocale): void
    {
        $this->notificationsLocale = $notificationsLocale;
    }

    /**
     * @param string|null $userScreenResolution
     * @return void
     */
    public function setUserScreenResolution(?string $userScreenResolution): void
    {
        $this->userScreenResolution = $userScreenResolution;
    }

    /**
     * @return string|null
     */
    public function getUserScreenResolution(): ?string
    {
        return $this->userScreenResolution;
    }

    /**
     * @param string|null $userAgent
     * @return void
     */
    public function setUserAgent(?string $userAgent): void
    {
        $this->userAgent = $userAgent;
    }

    /**
     * @return string|null
     */
    public function getUserAgent(): ?string
    {
        return $this->userAgent;
    }

    /**
     * @param string|null $userIpAddress
     * @return void
     */
    public function setUserIpAddress(?string $userIpAddress): void
    {
        $this->userIpAddress = $userIpAddress;
    }

    /**
     * @return string|null
     */
    public function getUserIpAddress(): ?string
    {
        return $this->userIpAddress;
    }

    /**
     * @param string|null $userPort
     * @return void
     */
    public function setUserPort(?string $userPort): void
    {
        $this->userPort = $userPort;
    }

    /**
     * @return string|null
     */
    public function getUserPort(): ?string
    {
        return $this->userPort;
    }

    /**
     * @param string|null $fingerprint
     * @return void
     */
    public function setFingerprint(?string $fingerprint): void
    {
        $this->fingerprint = $fingerprint;
    }

    /**
     * @return string|null
     */
    public function getFingerprint(): ?string
    {
        return $this->fingerprint;
    }
}
