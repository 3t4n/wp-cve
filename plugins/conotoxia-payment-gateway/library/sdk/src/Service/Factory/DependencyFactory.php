<?php

declare(strict_types=1);

namespace CKPL\Pay\Service\Factory;

use CKPL\Pay\Authentication\AuthenticationManager;
use CKPL\Pay\Authentication\AuthenticationManagerInterface;
use CKPL\Pay\Client\Client;
use CKPL\Pay\Client\ClientInterface;
use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Endpoint\EndpointInterface;
use CKPL\Pay\Exception\DependencyFactoryException;
use CKPL\Pay\Merchant\MerchantManager;
use CKPL\Pay\Merchant\MerchantManagerInterface;
use CKPL\Pay\Notification\NotificationManager;
use CKPL\Pay\Notification\NotificationManagerInterface;
use CKPL\Pay\PayInterface;
use CKPL\Pay\Payment\PaymentManager;
use CKPL\Pay\Payment\PaymentManagerInterface;
use CKPL\Pay\Notification\Payment\Verifier\Verifier as PaymentVerifier;
use CKPL\Pay\Notification\Payment\Verifier\VerifierInterface as PaymentVerifierInterface;
use CKPL\Pay\Refund\RefundManager;
use CKPL\Pay\Refund\RefundManagerInterface;
use CKPL\Pay\Notification\Refund\Verifier\Verifier as RefundVerifier;
use CKPL\Pay\Notification\Refund\Verifier\VerifierInterface as RefundVerifierInterface;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;
use CKPL\Pay\Security\JWT\JWT;
use CKPL\Pay\Security\JWT\JWTInterface;
use CKPL\Pay\Security\SecurityManager;
use CKPL\Pay\Security\SecurityManagerInterface;
use ReflectionClass;
use ReflectionException;
use function array_values;
use function call_user_func_array;
use function in_array;
use function sprintf;
use function substr;

/**
 * Class DependencyFactory.
 *
 * @package CKPL\Pay\Service\Factory
 *
 * @method SecurityManagerInterface       createSecurityManager (PayInterface $pay)
 * @method SecurityManagerInterface       getSecurityManager ()
 * @method SecurityManagerInterface       isSecurityManager ()
 * @method AuthenticationManagerInterface createAuthenticationManager (PayInterface $pay)
 * @method AuthenticationManagerInterface getAuthenticationManager ()
 * @method AuthenticationManagerInterface isAuthenticationManager ()
 * @method MerchantManagerInterface       createMerchantManager (PayInterface $pay)
 * @method MerchantManagerInterface       getMerchantManager ()
 * @method MerchantManagerInterface       isMerchantManager ()
 * @method PaymentManagerInterface        createPaymentManager (PayInterface $pay)
 * @method PaymentManagerInterface        getPaymentManager ()
 * @method PaymentManagerInterface        isPaymentManager ()
 * @method RefundManagerInterface         createRefundManager (PayInterface $pay)
 * @method RefundManagerInterface         getRefundManager ()
 * @method RefundManagerInterface         isRefundManager ()
 * @method NotificationManagerInterface   isNotificationManager ()
 * @method NotificationManagerInterface   getNotificationManager ()
 * @method NotificationManagerInterface   createNotificationManager (PayInterface $pay)
 * @method JWTInterface                   createJWT (ConfigurationInterface $configuration, int $key)
 * @method ClientInterface                createClient (EndpointInterface $e, ConfigurationInterface $c, SecurityManagerInterface $sm, AuthenticationManagerInterface $am = null) //NOSONAR
 * @method PaymentVerifierInterface       createPaymentVerifier (DecodedCollectionInterface $decodedCollection)
 * @method RefundVerifierInterface        createRefundVerifier (DecodedCollectionInterface $decodedCollection)
 */
class DependencyFactory implements DependencyFactoryInterface
{
    /**
     * @var array|object[]
     */
    protected $initialized = [];

    /**
     * @type array
     */
    protected const CLASSMAP = [
        'AuthenticationManager' => AuthenticationManager::class,
        'SecurityManager' => SecurityManager::class,
        'MerchantManager' => MerchantManager::class,
        'PaymentManager' => PaymentManager::class,
        'RefundManager' => RefundManager::class,
        'NotificationManager' => NotificationManager::class,
        'JWT' => JWT::class,
        'Client' => Client::class,
        'PaymentVerifier' => PaymentVerifier::class,
        'RefundVerifier' => RefundVerifier::class,
    ];

    /**
     * @type array
     */
    protected const PRESERVE = [
        'AuthenticationManager' => true,
        'SecurityManager' => true,
        'MerchantManager' => true,
        'PaymentManager' => true,
        'RefundManager' => true,
        'NotificationManager' => true,
    ];

    /**
     * @param string $class
     *
     * @return bool
     */
    public function hasDependency(string $class): bool
    {
        return in_array($class, array_values(static::CLASSMAP));
    }

    /**
     * @param $name
     * @param $arguments
     *
     * @throws DependencyFactoryException
     *
     * @return object
     */
    public function __call($name, $arguments)
    {
        if ('get' === substr($name, 0, 3)) {
            $className = substr($name, 3);
            $method = 'getInstance';
        } elseif ('create' === substr($name, 0, 6)) {
            $className = substr($name, 6);
            $method = 'createInstance';
        } elseif ('is' === substr($name, 0, 2)) {
            $className = substr($name, 2);
            $method = 'isInitialized';
        } else {
            throw new DependencyFactoryException('Unknown call to dependency factory.');
        }

        if (!isset(static::CLASSMAP[$className])) {
            throw new DependencyFactoryException(sprintf('Unknown dependency named %s.', $className));
        }

        return call_user_func_array([$this, $method], [$className, $arguments]);
    }

    /**
     * @param string $className
     *
     * @return bool
     */
    protected function isInitialized(string $className): bool
    {
        return isset($this->initialized[$className]);
    }

    /**
     * @param string $className
     *
     * @throws DependencyFactoryException
     *
     * @return object
     */
    protected function getInstance(string $className)
    {
        if (!isset(static::PRESERVE[$className]) || !static::PRESERVE[$className]) {
            throw new DependencyFactoryException(
                sprintf('%s cannot be preserved in dependency factory.', $className)
            );
        }

        if (!$this->isInitialized($className)) {
            throw new DependencyFactoryException(
                sprintf('%s is not initialized. Use create%s at first.', $className, $className)
            );
        }

        return $this->initialized[$className];
    }

    /**
     * @param string $className
     * @param array  $arguments
     *
     * @throws DependencyFactoryException
     * @throws ReflectionException
     *
     * @return object
     */
    protected function createInstance(string $className, array $arguments)
    {
        if (isset($this->initialized[$className])) {
            throw new DependencyFactoryException(
                sprintf('%s is initialized.', $className)
            );
        }

        $reflectionClass = new ReflectionClass(static::CLASSMAP[$className]);
        $instance = $reflectionClass->newInstanceArgs($arguments);

        if (isset(static::PRESERVE[$className]) && static::PRESERVE[$className]) {
            $this->initialized[$className] = $instance;
        }

        return $instance;
    }
}
