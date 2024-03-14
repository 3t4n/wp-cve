<?php

declare(strict_types=1);

namespace CKPL\Pay\Configuration;

use CKPL\Pay\Service\Factory\DependencyFactoryInterface;
use CKPL\Pay\Storage\StorageInterface;

/**
 * Interface ConfigurationInterface.
 *
 * @package CKPL\Pay\Configuration
 */
interface ConfigurationInterface
{
    /**
     * @type string
     */
    const HOST = 'host';

    /**
     * @type string
     */
    const OIDC = 'oidc';

    /**
     * @type string
     */
    const CLIENT_ID = 'client_id';

    /**
     * @type string
     */
    const CLIENT_SECRET = 'client_secret';

    /**
     * @type string
     */
    const STORAGE = 'storage';

    /**
     * @type string
     */
    const SIGN_ALGORITHM = 'sign_algorithm';

    /**
     * @type string
     */
    const PUBLIC_KEY = 'public_key';

    /**
     * @type string
     */
    const PRIVATE_KEY = 'private_key';

    /**
     * @type string
     */
    const POINT_OF_SALE = 'point_of_sale';

    /**
     * @type string
     */
    const CATEGORY = 'category';

    /**
     * @type string
     */
    const DEPENDENCY_FACTORY = 'dependency_factory';

    /**
     * @type string
     */
    const RETURN_URL = 'return_url';

    /**
     * @type string
     */
    const ERROR_URL = 'error_url';

    /**
     * @type string
     */
    const PAYMENTS_NOTIFICATION_URL = 'payments_notification_url';

    /**
     * @type string
     */
    const REFUNDS_NOTIFICATION_URL = 'refunds_notification_url';

    /**
     * @type string
     */
    const CURL_OPTIONS = 'curl_options';

    /**
     * Payment Service host URL.
     *
     * @return string
     */
    public function getHost(): string;

    /**
     * Authorization service host URL.
     *
     * @return string
     */
    public function getOidc(): string;

    /**
     * Client ID from merchant panel.
     *
     * @return string
     */
    public function getClientId(): string;

    /**
     * Client secret from merchant panel.
     *
     * @return string
     */
    public function getClientSecret(): string;

    /**
     * Storage instance that implements `\CKPL\Pay\Storage\StorageInterface`.
     *
     * Available storage classes in library:
     * * `\CKPL\Pay\Storage\FileStorage` - stores data in JSON file.
     * * `\CKPL\Pay\Storage\CallableStorage` - stores data using predefined callable for each action.
     *
     * @return StorageInterface
     */
    public function getStorage(): StorageInterface;

    /**
     * Signature algorithm.
     *
     * @return int
     */
    public function getSignAlgorithm(): int;

    /**
     * Merchant public key that is sent to Payment Service for signature verification process.
     *
     * @return string
     */
    public function getPublicKey(): string;

    /**
     * Merchant private key for signature creation process.
     *
     * @return string
     */
    public function getPrivateKey(): string;

    /**
     * Point of sale ID.
     *
     * @return string
     */
    public function getPointOfSale(): string;

    /**
     * Client account category.
     *
     * @return string
     */
    public function getCategory(): string;

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
    public function getErrorUrl(): ?string;

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
    public function getReturnUrl(): ?string;

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
    public function getPaymentsNotificationUrl(): ?string;

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
    public function getRefundsNotificationUrl(): ?string;

    /**
     * Custom cURL options.
     *
     * @return array
     */
    public function getCurlOptions(): array;

    /**
     * @return DependencyFactoryInterface
     */
    public function getDependencyFactory();
}
