<?php

declare(strict_types=1);

namespace CKPL\Pay;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Exception\Exception;
use CKPL\Pay\Exception\StorageException;
use CKPL\Pay\Merchant\MerchantManagerInterface;
use CKPL\Pay\Notification\NotificationManagerInterface;
use CKPL\Pay\Payment\PaymentManagerInterface;
use CKPL\Pay\Refund\RefundManagerInterface;
use CKPL\Pay\Service\Factory\DependencyFactoryInterface;

/**
 * Interface PayInterface.
 *
 * Interface for main class that aggregates managers for
 * different parts of payment system.
 *
 * Methods included in this interface must be
 * directly related to the Payment Service.
 *
 * Each manager can be called using its own,
 * dedicated method:
 *  * $this->merchant() - Merchant manager.
 *  * $this->payments() - Payments manager.
 *  * $this->refunds() - Refunds manager.
 *
 * @package CKPL\Pay
 */
interface PayInterface
{
    /**
     * Gets Payment Service public key from service and
     * saves it in storage.
     *
     * @throws ClientException  request-level related problem e.g. HTTP errors, API errors.
     * @throws StorageException storage-level related problem e.g. read/write permission problem.
     * @throws Exception        library-level related problem e.g. invalid data model.
     *
     * @return void
     */
    public function pickSignatureKey(): void;

    /**
     * Merchant related features such as
     * ability to check if public key is synchronized with
     * service, send public key to service, get ID for public key
     * if already sent to service, get list all public keys related to client in service.
     *
     * Example:
     *     foreach ($this->merchant()->getPublicKeys() as $publicKey) {
     *         //do stuff
     *     }
     *
     * @return MerchantManagerInterface
     */
    public function merchant(): MerchantManagerInterface;

    /**
     * Payments related features such as
     * ability to create payment, check payment status,
     * decode return response, get list of all payments related to client in service.
     *
     * Example:
     *     $status = $this->payments()->getPaymentStatus(\file_get_contents('php://input'));
     *
     * @return PaymentManagerInterface
     */
    public function payments(): PaymentManagerInterface;

    /**
     * Refunds related features such as
     * ability to create refund, check refund status,
     * get list of all refunds related to client in service.
     *
     * Example:
     *     $status = $this->refunds()->getRefundStatus(\file_get_contents('php://input'));
     *
     * @return RefundManagerInterface
     */
    public function refunds(): RefundManagerInterface;

    /**
     * Payment and refund notifications manager
     *
     * Example:
     *     $notification = $this->notification()->getNotification(\file_get_contents('php://input'));
     *
     * @return NotificationManagerInterface
     */
    public function notification(): NotificationManagerInterface;

    /**
     * @return ConfigurationInterface
     *
     * @internal this method should be used only in library-related classes
     */
    public function getConfiguration(): ConfigurationInterface;

    /**
     * @return DependencyFactoryInterface
     *
     * @internal this method should be used only in library-related classes
     */
    public function getDependencyFactory(): DependencyFactoryInterface;

    /**
     * SDK version
     *
     * @return string
     */
    public static function getSDKVersion(): string;
}
