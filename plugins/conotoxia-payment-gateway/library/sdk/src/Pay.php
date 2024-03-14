<?php

declare(strict_types=1);

namespace CKPL\Pay;

use CKPL\Pay\Authentication\AuthenticationManagerInterface;
use CKPL\Pay\Configuration\Configuration;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Cryptography\PublicKeyCalculator;
use CKPL\Pay\Endpoint\JwksEndpoint;
use CKPL\Pay\Exception\ClientException;
use CKPL\Pay\Exception\Exception;
use CKPL\Pay\Exception\StorageException;
use CKPL\Pay\Merchant\MerchantManagerInterface;
use CKPL\Pay\Model\Collection\PaymentServiceKeyResponseModelCollection;
use CKPL\Pay\Notification\NotificationManagerInterface;
use CKPL\Pay\Payment\PaymentManagerInterface;
use CKPL\Pay\Refund\RefundManagerInterface;
use CKPL\Pay\Security\SecurityManagerInterface;
use CKPL\Pay\Security\Token\Token;
use CKPL\Pay\Service\BaseService;
use CKPL\Pay\Service\Factory\DependencyFactory;
use CKPL\Pay\Service\Factory\DependencyFactoryInterface;
use CKPL\Pay\Storage\KeyCollector\KeyCollector;
use CKPL\Pay\Storage\StorageInterface;

/**
 * Class Pay.
 *
 * Main class that aggregates managers for
 * different parts of payment system.
 *
 * Methods included in this class are
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
class Pay implements PayInterface
{
    /**
     * @var DependencyFactory
     */
    protected $dependencyFactory;

    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;

    /**
     * @var Token|null
     */
    protected $authorizationToken;

    /**
     * @var AuthenticationManagerInterface
     */
    protected $authenticationManager;

    /**
     * Pay constructor.
     *
     * @param ConfigurationInterface $configuration
     */
    public function __construct(ConfigurationInterface $configuration)
    {
        $this->configuration = $configuration;
        $this->dependencyFactory = $configuration->getDependencyFactory();

        $this->securityManager = $this->dependencyFactory->createSecurityManager($this);
        $this->authenticationManager = $this->dependencyFactory->createAuthenticationManager($this);

        $this->authorizationToken = $this->securityManager->getToken();
    }

    /**
     * Checks whether the Payment Service public key is in storage.
     *
     * @return bool
     */
    public function hasSignatureKey(): bool
    {
        return $this->configuration->getStorage()->hasItem(StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS);
    }

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
    public function pickSignatureKey(): void
    {
        $keyCollector = new KeyCollector($this->configuration->getStorage(), StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS);

        $client = $this->dependencyFactory->createClient(
            new JwksEndpoint(),
            $this->configuration,
            $this->dependencyFactory->getSecurityManager(),
            $this->dependencyFactory->getAuthenticationManager()
        );

        $client->request()->send();

        $keyCollection = $client->getResponse()->getProcessedOutput();

        if ($keyCollection instanceof PaymentServiceKeyResponseModelCollection) {
            foreach ($keyCollection->all() as $key) {
                $publicKey = (new PublicKeyCalculator())->calculateRsaFromModel($key);
                $keyCollector->addKey($key->getKeyId(), $publicKey->getPublicKey());
            }
        } else {
            throw new Exception(BaseService::UNSUPPORTED_RESPONSE_MODEL_EXCEPTION);
        }
    }

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
    public function merchant(): MerchantManagerInterface
    {
        return $this->dependencyFactory->isMerchantManager()
            ? $this->dependencyFactory->getMerchantManager()
            : $this->dependencyFactory->createMerchantManager($this);
    }

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
    public function payments(): PaymentManagerInterface
    {
        return $this->dependencyFactory->isPaymentManager()
            ? $this->dependencyFactory->getPaymentManager()
            : $this->dependencyFactory->createPaymentManager($this);
    }

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
    public function refunds(): RefundManagerInterface
    {
        return $this->dependencyFactory->isRefundManager()
            ? $this->dependencyFactory->getRefundManager()
            : $this->dependencyFactory->createRefundManager($this);
    }

    /**
     * Payment and refund notifications manager
     *
     * Example:
     *     $notification = $this->notification()->getNotification(\file_get_contents('php://input'));
     *
     * @return NotificationManagerInterface
     */
    public function notification(): NotificationManagerInterface
    {
        return $this->dependencyFactory->isNotificationManager()
            ? $this->dependencyFactory->getNotificationManager()
            : $this->dependencyFactory->createNotificationManager($this);
    }

    /**
     * @return Configuration
     *
     * @internal this method should be used only in library-related classes
     */
    public function getConfiguration(): ConfigurationInterface
    {
        return $this->configuration;
    }

    /**
     * @return DependencyFactory
     *
     * @internal this method should be used only in library-related classes
     */
    public function getDependencyFactory(): DependencyFactoryInterface
    {
        return $this->dependencyFactory;
    }

    /**
     * SDK version
     *
     * @return string
     */
    public static function getSDKVersion(): string
    {
        return '1.24.6';
    }
}
