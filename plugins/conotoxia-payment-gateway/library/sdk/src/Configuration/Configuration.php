<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration;

use CKPL\Pay\Configuration\Adjuster\ConfigurationAdjuster;
use CKPL\Pay\Configuration\Adjuster\ConfigurationAdjusterInterface;
use CKPL\Pay\Configuration\Reference\ConfigurationReferenceInterface;
use CKPL\Pay\Configuration\Resolver\ConfigurationResolver;
use CKPL\Pay\Configuration\Resolver\ConfigurationResolverInterface;
use CKPL\Pay\Exception\ConfigurationException;
use CKPL\Pay\Service\Factory\DependencyFactoryInterface;
use CKPL\Pay\Storage\StorageInterface;
use function sprintf;

/**
 * Class Configuration.
 *
 * @package CKPL\Pay\Configuration
 */
class Configuration implements ConfigurationInterface
{
    /**
     * @var array
     */
    protected $configuration;

    /**
     * Configuration constructor.
     *
     * @param ConfigurationReferenceInterface $configurationReference
     * @param string                          $resolverClass
     * @param string                          $adjusterClass
     *
     * @throws ConfigurationException
     */
    public function __construct(
        ConfigurationReferenceInterface $configurationReference,
        string $resolverClass = ConfigurationResolver::class,
        string $adjusterClass = ConfigurationAdjuster::class
    ) {
        $this->resolveConfiguration($configurationReference, $resolverClass);
        $this->adjustConfiguration($adjusterClass);
    }

    /**
     * Payment Service host URL.
     *
     * @return string
     */
    public function getHost(): string
    {
        return $this->configuration[ConfigurationInterface::HOST];
    }

    /**
     * Authorization service host URL.
     *
     * @return string
     */
    public function getOidc(): string
    {
        return $this->configuration[ConfigurationInterface::OIDC];
    }

    /**
     * Client ID from merchant panel.
     *
     * @return string
     */
    public function getClientId(): string
    {
        return $this->configuration[ConfigurationInterface::CLIENT_ID];
    }

    /**
     * Client secret from merchant panel.
     *
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->configuration[ConfigurationInterface::CLIENT_SECRET];
    }

    /**
     * Storage instance that implements `\CKPL\Pay\Storage\StorageInterface`.
     *
     * Available storage classes in library:
     * * `\CKPL\Pay\Storage\FileStorage` - stores data in JSON file.
     * * `\CKPL\Pay\Storage\CallableStorage` - stores data using predefined callable for each action.
     *
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface
    {
        return $this->configuration[ConfigurationInterface::STORAGE];
    }

    /**
     * Signature algorithm.
     *
     * @return int
     */
    public function getSignAlgorithm(): int
    {
        return $this->configuration[ConfigurationInterface::SIGN_ALGORITHM];
    }

    /**
     * Merchant private key for signature creation process.
     *
     * @return string
     */
    public function getPrivateKey(): string
    {
        return $this->configuration[ConfigurationInterface::PRIVATE_KEY];
    }

    /**
     * Merchant public key that is sent to Payment Service for signature verification process.
     *
     * @return string
     */
    public function getPublicKey(): string
    {
        return $this->configuration[ConfigurationInterface::PUBLIC_KEY];
    }

    /**
     * @return DependencyFactoryInterface
     *
     * @internal this method should be used only in library-related classes
     */
    public function getDependencyFactory()
    {
        return $this->configuration[ConfigurationInterface::DEPENDENCY_FACTORY];
    }

    /**
     * Point of sale ID.
     *
     * @return string
     */
    public function getPointOfSale(): string
    {
        return $this->configuration[ConfigurationInterface::POINT_OF_SALE];
    }

    /**
     * Client account category.
     *
     * @return string
     */
    public function getCategory(): string
    {
        return $this->configuration[ConfigurationInterface::CATEGORY];
    }

    /**
     * Error URL.
     *
     * Payment Service will redirect client to this
     * URL on transaction failure.
     *
     * This URL can be set in merchant panel or directly in Payment definition.
     *
     * @return string|null
     */
    public function getErrorUrl(): ?string
    {
        return $this->configuration[ConfigurationInterface::ERROR_URL] ?? null;
    }

    /**
     * Return URL.
     *
     * Payment Service will redirect client to this
     * URL if transaction succeeded.
     *
     * This URL can be set in merchant panel or directly in Payment definition.
     *
     * @return string|null
     */
    public function getReturnUrl(): ?string
    {
        return $this->configuration[ConfigurationInterface::RETURN_URL] ?? null;
    }

    /**
     * Notification URL.
     *
     * Payment Service will send information about
     * the course of the transaction to this URL.
     *
     * This URL can be set in merchant panel or directly in Payment definition.
     *
     * @return string|null
     */
    public function getPaymentsNotificationUrl(): ?string
    {
        return $this->configuration[ConfigurationInterface::PAYMENTS_NOTIFICATION_URL] ?? null;
    }

    /**
     * Notification URL.
     *
     * Payment Service will send information about
     * the course of the refund to this URL.
     *
     * This URL can be set in merchant panel or directly in Refund definition.
     *
     * @return string|null
     */
    public function getRefundsNotificationUrl(): ?string
    {
        return $this->configuration[ConfigurationInterface::REFUNDS_NOTIFICATION_URL] ?? null;
    }

    /**
     * Custom cURL options.
     *
     * @return array
     */
    public function getCurlOptions(): array
    {
        return $this->configuration[ConfigurationInterface::CURL_OPTIONS];
    }

    /**
     * @param ConfigurationReferenceInterface $configurationReference
     * @param string                          $resolverClass
     *
     * @throws ConfigurationException
     */
    protected function resolveConfiguration(
        ConfigurationReferenceInterface $configurationReference,
        string $resolverClass = ConfigurationResolver::class
    ): void {
        $resolver = new $resolverClass();

        if (!($resolver instanceof ConfigurationResolverInterface)) {
            throw new ConfigurationException(
                sprintf('Resolver must implements %s interface.', ConfigurationResolverInterface::class)
            );
        }

        $this->configuration = $resolver
            ->resolveReference($configurationReference);
    }

    /**
     * @param string $adjusterClass
     *
     * @throws ConfigurationException
     *
     * @return void
     */
    protected function adjustConfiguration(string $adjusterClass = ConfigurationAdjuster::class): void
    {
        $adjuster = new $adjusterClass();

        if (!($adjuster instanceof ConfigurationAdjusterInterface)) {
            throw new ConfigurationException(
                sprintf('Adjuster must implements %s interface.', ConfigurationAdjusterInterface::class)
            );
        }

        $adjuster->adjust($this->configuration);
    }
}
