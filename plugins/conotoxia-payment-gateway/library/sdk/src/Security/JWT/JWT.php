<?php

declare(strict_types=1);

namespace CKPL\Pay\Security\JWT;

use CKPL\Pay\Configuration\ConfigurationInterface;
use CKPL\Pay\Definition\Header\Factory\HeaderFactory;
use CKPL\Pay\Definition\Header\Header;
use CKPL\Pay\Definition\Header\HeaderInterface;
use CKPL\Pay\Definition\Payload\Payload;
use CKPL\Pay\Definition\Payload\PayloadInterface;
use CKPL\Pay\Definition\Signature\Signature;
use CKPL\Pay\Exception\IncompatibilityException;
use CKPL\Pay\Exception\InvalidSignatureException;
use CKPL\Pay\Exception\JsonFunctionException;
use CKPL\Pay\Exception\JWTException;
use CKPL\Pay\Exception\StorageException;
use CKPL\Pay\Pay;
use CKPL\Pay\Security\JWT\Collection\DecodedCollection;
use CKPL\Pay\Security\JWT\Collection\DecodedCollectionInterface;
use CKPL\Pay\Security\JWT\Part\Part;
use CKPL\Pay\Security\JWT\Part\PartInterface;
use CKPL\Pay\Storage\StorageInterface;
use function CKPL\Pay\base64url_decode;
use function CKPL\Pay\base64url_encode;
use function count;
use function explode;
use function is_array;
use function join;
use function openssl_sign;
use function openssl_verify;
use function sprintf;

/**
 * Class JWT.
 *
 * @package CKPL\Pay\Security\JWT
 */
class JWT implements JWTInterface
{
    /**
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * @var int
     */
    protected $key;

    /**
     * JWT constructor.
     *
     * @param ConfigurationInterface $configuration
     * @param int                    $key
     */
    public function __construct(ConfigurationInterface $configuration, int $key = JWTInterface::MERCHANT_KEY)
    {
        $this->configuration = $configuration;
        $this->key = JWTInterface::PAYMENT_SERVICE_KEY === $key ? JWTInterface::PAYMENT_SERVICE_KEY : JWTInterface::MERCHANT_KEY;
    }

    /**
     * @param PayloadInterface $payload
     *
     * @throws JWTException
     * @throws IncompatibilityException
     * @throws StorageException
     *
     * @return string
     */
    public function encode(PayloadInterface $payload): string
    {
        $headerPart = new Part($this->createHeader()->raw());
        $payloadPart = new Part($payload->raw());

        $signature = $this->sign($headerPart, $payloadPart);

        return join('.', [$headerPart->encoded(), $payloadPart->encoded(), $signature]);
    }

    /**
     * @param string $encodedData
     *
     * @throws IncompatibilityException
     * @throws InvalidSignatureException
     * @throws JWTException
     * @throws JsonFunctionException
     * @throws StorageException
     *
     * @return DecodedCollectionInterface
     */
    public function decode(string $encodedData): DecodedCollectionInterface
    {
        $encodedParts = explode('.', $encodedData);

        if (3 !== count($encodedParts)) {
            throw new JWTException('Encoded data must contain 3 parts: header, payload and signature.');
        }

        list($encodedHeader, $encodedPayload, $encodedSignature) = $encodedParts;

        $headerPart = Part::fromEncoded($encodedHeader);
        $payloadPart = Part::fromEncoded($encodedPayload);

        $headerRaw = $headerPart->raw();
        $payloadRaw = $payloadPart->raw();

        if (!isset($headerRaw['alg'])) {
            throw new JWTException('Missing sign algorithm in JWT header.');
        }

        if (!isset($headerRaw['kid'])) {
            throw new JWTException('Missing key ID in JWT header.');
        }

        if (!$this->isSignatureValid($headerRaw['kid'], $encodedSignature, $headerPart, $payloadPart)) {
            throw new InvalidSignatureException('Signature is invalid.');
        }

        return new DecodedCollection(new Header($headerRaw), new Payload($payloadRaw), new Signature($encodedSignature));
    }

    /**
     * @param PartInterface[] $parts
     *
     * @throws JWTException
     *
     * @return string
     */
    public function sign(PartInterface ...$parts): string
    {
        $encodedParts = [];

        foreach ($parts as $part) {
            $encodedParts[] = $part->encoded();
        }

        $signatureContent = join('.', $encodedParts);
        $signature = '';

        openssl_sign(
            $signatureContent,
            $signature,
            $this->getPrivateKey(),
            $this->configuration->getSignAlgorithm()
        );

        return base64url_encode($signature);
    }

    /**
     * @param string|null     $keyId
     * @param string          $signature
     * @param PartInterface[] $parts
     *
     * @throws JWTException
     * @throws StorageException
     *
     * @return bool
     */
    public function isSignatureValid(?string $keyId, string $signature, PartInterface ...$parts): bool
    {
        $signature = base64url_decode($signature);
        $encodedParts = [];

        /** @var string|null $foundAlgorithm */
        $foundAlgorithm = null;

        foreach ($parts as $part) {
            if (null === $foundAlgorithm && isset($part->raw()['alg'])) {
                $foundAlgorithm = $part->raw()['alg'];
            }

            $encodedParts[] = $part->encoded();
        }

        $parts = join('.', $encodedParts);

        $foundAlgorithm = null === $foundAlgorithm ? $foundAlgorithm : $this->getAlgorithm($foundAlgorithm);

        $success = openssl_verify(
            $parts,
            $signature,
            $this->getPublicKey($keyId),
            (
                JWTInterface::MERCHANT_KEY === $this->key
                    ? $this->configuration->getSignAlgorithm()
                    : $foundAlgorithm
            )
        );

        return 1 === $success;
    }

    /**
     * @param string $keyId
     *
     * @throws JWTException
     * @throws StorageException
     *
     * @return string
     */
    protected function getPublicKey(string $keyId = null): string
    {
        if (JWTInterface::PAYMENT_SERVICE_KEY === $this->key) {
            if (!$this->configuration->getStorage()->hasItem(StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS)) {
                throw new JWTException(
                    sprintf(
                        'There is no Payment Service public key in storage. Use %s to get one.',
                        Pay::class.':pickSignatureKey'
                    )
                );
            }

            if (null === $keyId) {
                throw new JWTException('Key ID is required for Payment Service public key.');
            }

            $publicKeys = $this->configuration->getStorage()->expectArrayOrNull(StorageInterface::PAYMENT_SERVICE_PUBLIC_KEYS);

            if (!is_array($publicKeys)) {
                throw new JWTException('Public keys storage corrupted.');
            }

            if (!isset($publicKeys[$keyId])) {
                throw new JWTException(
                    sprintf('Public key with ID [%s] does not exist in local storage.', $keyId)
                );
            }

            $publicKey = $publicKeys[$keyId];
        } else {
            $publicKey = $this->configuration->getPublicKey();
        }

        return $publicKey;
    }

    /**
     * @throws JWTException
     *
     * @return string
     */
    protected function getPrivateKey(): string
    {
        if (JWTInterface::PAYMENT_SERVICE_KEY === $this->key) {
            throw new JWTException('Access for Payment Service private key is denied.');
        } else {
            return $this->configuration->getPrivateKey();
        }
    }

    /**
     * @throws JWTException
     * @throws StorageException
     *
     * @return HeaderInterface
     */
    protected function createHeader(): HeaderInterface
    {
        if (!$this->configuration->getStorage()->hasItem(StorageInterface::PUBLIC_KEY_ID)) {
            throw new JWTException(sprintf('Unable to get "%s" from storage.', StorageInterface::PUBLIC_KEY_ID));
        }

        $publicKeyId = $this->configuration->getStorage()->expectStringOrNull(StorageInterface::PUBLIC_KEY_ID);

        return (new HeaderFactory())
            ->setKeyId($publicKeyId)
            ->setSignatureType('JWT')
            ->setContentType('application/json')
            ->setAlgorithm($this->getAlgorithmId())
            ->build();
    }

    /**
     * @throws JWTException
     *
     * @return string
     */
    protected function getAlgorithmId(): string
    {
        switch ($this->configuration->getSignAlgorithm()) {
            case OPENSSL_ALGO_SHA256:
                $result = 'RS256';
                break;
            case OPENSSL_ALGO_SHA384:
                $result = 'RS384';
                break;
            case OPENSSL_ALGO_SHA512:
                $result = 'RS512';
                break;
            default: throw new JWTException('Unsupported algorithm.');
        }

        return $result;
    }

    /**
     * @param string $algorithmId
     *
     * @throws JWTException
     *
     * @return int
     */
    protected function getAlgorithm(string $algorithmId): int
    {
        switch ($algorithmId) {
            case 'RS256':
                $result = OPENSSL_ALGO_SHA256;
                break;
            case 'RS384':
                $result = OPENSSL_ALGO_SHA384;
                break;
            case 'RS512':
                $result = OPENSSL_ALGO_SHA512;
                break;
            default: throw new JWTException('Unsupported algorithm.');
        }

        return $result;
    }
}
