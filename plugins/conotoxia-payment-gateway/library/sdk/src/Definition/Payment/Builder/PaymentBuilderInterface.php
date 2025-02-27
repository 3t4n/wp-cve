<?php

declare(strict_types=1);

namespace CKPL\Pay\Definition\Payment\Builder;

use CKPL\Pay\Definition\Amount\AmountInterface;
use CKPL\Pay\Definition\Payment\PaymentInterface;
use CKPL\Pay\Definition\StoreCustomer\StoreCustomerInterface;
use CKPL\Pay\Exception\Definition\PaymentException;

/**
 * Interface PaymentBuilderInterface.
 *
 * @package CKPL\Pay\Definition\Payment\Builder
 */
interface PaymentBuilderInterface
{
    /**
     * External payment ID.
     *
     * This ID should be generated by service where
     * library is implemented in. Could be internal payment
     * system ID or shop order ID.
     *
     * Min 1 character, max 36 characters.
     *
     * This value is required!
     *
     * @param string $externalPaymentId
     *
     * @return PaymentBuilderInterface
     */
    public function setExternalPaymentId(string $externalPaymentId): PaymentBuilderInterface;

    /**
     * Sets payment integration platform.
     *
     * Max 64 characters.
     *
     * Value is not required.
     *
     * @param string $integrationPlatform
     *
     * @return PaymentBuilderInterface
     */
    public function setIntegrationPlatform(string $integrationPlatform): PaymentBuilderInterface;

    /**
     * Allows you to control the payment UI domain. Should be compatible with RFC7231 section 5.3.5.
     *
     * @param string $acceptLanguage
     *
     * @return PaymentBuilderInterface
     */
    public function setAcceptLanguage(string $acceptLanguage): PaymentBuilderInterface;

    /**
     * Allows you to control the payment initial UI language. Should be compatible with BCP47.
     *
     * @param string $preferredUserLocale
     *
     * @return PaymentBuilderInterface
     */
    public function setPreferredUserLocale(string $preferredUserLocale): PaymentBuilderInterface;

    /**
     * Sets Notification Url Parameters.
     *
     * @param mixed $notificationUrlParameters
     *
     * @return PaymentBuilderInterface
     */
    public function setNotificationUrlParameters($notificationUrlParameters): PaymentBuilderInterface;

    /**
     * Sets store customer data.
     *
     * @param StoreCustomerInterface $storeCustomer
     *
     * @return PaymentBuilderInterface
     */
    public function setStoreCustomer(StoreCustomerInterface $storeCustomer): PaymentBuilderInterface;

    /**
     * Allows to build store customer definition in callable.
     *
     * @param callable $callable
     *
     * @return PaymentBuilderInterface
     * @throws PaymentException on builder failure
     */
    public function buildStoreCustomer(callable $callable): PaymentBuilderInterface;

    /**
     * Creates store customer builder that allows to create store customer definition.
     *
     * @return StoreCustomerBuilderInterface
     */
    public function createStoreCustomerBuilder(): StoreCustomerBuilderInterface;

    /**
     * Allows to build Amount definition in callable.
     *
     * Example:
     *     function (\CKPL\Pay\Definition\Payment\Builder\AmountBuilderInterface $amountBuilder) {
     *         $amountBuilder->setAmount('12.30')
     *         //other setters
     *     }
     *
     * @param callable $callable
     *
     * @throws PaymentException on builder failure
     *
     * @return PaymentBuilderInterface
     */
    public function buildAmount(callable $callable): PaymentBuilderInterface;

    /**
     * Creates amount builder that allows to create Amount definition.
     *
     * @return AmountBuilderInterface
     */
    public function createAmountBuilder(): AmountBuilderInterface;

    /**
     * Sets Amount definition for payment.
     *
     * This value is required but can be set using `buildAmount` method too.
     *
     * @param AmountInterface $amount
     *
     * @return PaymentBuilderInterface
     */
    public function setAmount(AmountInterface $amount): PaymentBuilderInterface;

    /**
     * Description.
     *
     * Min 1 character, max 128 characters.
     *
     * This value is required!
     *
     * @param string $description
     *
     * @return PaymentBuilderInterface
     */
    public function setDescription(string $description): PaymentBuilderInterface;

    /**
     * Notification URL.
     *
     * Payment Service will send information about
     * the course of the transaction to this URL.
     *
     * Value is not required.
     * Can be set in Merchant panel or as a global value in configuration.
     *
     * Min 1 character, max 256 characters.
     *
     * @param string $notificationUrl
     *
     * @return PaymentBuilderInterface
     */
    public function setNotificationUrl(string $notificationUrl): PaymentBuilderInterface;

    /**
     * Error URL.
     *
     * Payment Service will redirect client to this
     * URL on transaction failure.
     *
     * Value is not required.
     * Can be set in Merchant panel or as a global value in configuration.
     *
     * Min 1 character, max 256 characters.
     *
     * @param string $errorUrl
     *
     * @return PaymentBuilderInterface
     */
    public function setErrorUrl(string $errorUrl): PaymentBuilderInterface;

    /**
     * Return URL.
     *
     * Payment Service will redirect client to this
     * URL if transaction succeeded.
     *
     * Value is not required.
     * Can be set in Merchant panel or as a global value in configuration.
     *
     * Min 1 character, max 256 characters.
     *
     * @param string $returnUrl
     *
     * @return PaymentBuilderInterface
     */
    public function setReturnUrl(string $returnUrl): PaymentBuilderInterface;

    /**
     * Enables the "pay later" function for this payment.
     *
     * @return PaymentBuilderInterface
     */
    public function allowPayLater(): PaymentBuilderInterface;

    /**
     * Disables the "pay later" function for this payment.
     *
     * @return PaymentBuilderInterface
     */
    public function denyPayLater(): PaymentBuilderInterface;

    /**
     * Returns Payment definition.
     *
     * @throws PaymentException if one of required parameters is missing
     *
     * @return PaymentInterface
     */
    public function getPayment(): PaymentInterface;
}
