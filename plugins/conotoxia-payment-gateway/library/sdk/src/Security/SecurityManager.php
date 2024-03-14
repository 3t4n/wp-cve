<?php

declare(strict_types=1);

namespace CKPL\Pay\Security;

use CKPL\Pay\Definition\Payload\Payload;
use CKPL\Pay\Exception\JWTException;
use CKPL\Pay\Exception\StorageException;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;
use CKPL\Pay\Security\JWT\JWTInterface;
use CKPL\Pay\Security\Token\Token;
use CKPL\Pay\Security\Token\TokenInterface;
use CKPL\Pay\Service\BaseService;
use CKPL\Pay\Storage\StorageInterface;
use function is_array;

/**
 * Class SecurityManager.
 *
 * @package CKPL\Pay\Security
 */
class SecurityManager extends BaseService implements SecurityManagerInterface
{
    /**
     * @param array $parameters
     *
     * @return string
     */
    public function encodeRequest(array $parameters): string
    {
        return $this->dependencyFactory->createJWT($this->configuration, JWTInterface::MERCHANT_KEY)
            ->encode(new Payload($parameters));
    }

    /**
     * @param string $response
     *
     * @throws JWTException
     *
     * @return DecodedCollectionInterface
     */
    public function decodeResponse(string $response): DecodedCollectionInterface
    {
        return $this->dependencyFactory->createJWT($this->configuration, JWTInterface::PAYMENT_SERVICE_KEY)
            ->decode($response);
    }

    /**
     * @throws StorageException
     *
     * @return Token|null
     */
    public function getToken(): ?TokenInterface
    {
        /** @var TokenInterface|null $token */
        $token = $this->configuration->getStorage()->hasItem(StorageInterface::TOKEN)
            ? $this->configuration->getStorage()->expectArrayOrNull(StorageInterface::TOKEN)
            : null;

        if (is_array($token)) {
            $token = Token::restore($token);
        }

        return $token;
    }

    /**
     * @throws StorageException
     *
     * @return void
     */
    public function invalidateToken(): void
    {
        if (null !== $this->getToken()) {
            $this->configuration->getStorage()->setItem(StorageInterface::TOKEN, null);
        }
    }
}
