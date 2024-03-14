<?php

declare(strict_types=1);

namespace CKPL\Pay\Cryptography;

use CKPL\Pay\Cryptography\BigInteger\BigInteger;
use CKPL\Pay\Cryptography\BigInteger\BigIntegerInterface;
use CKPL\Pay\Cryptography\BigInteger\Value\StringValue;
use CKPL\Pay\Cryptography\Component\ComponentInterface;
use CKPL\Pay\Cryptography\Component\Exponent;
use CKPL\Pay\Cryptography\Component\Modulus;
use CKPL\Pay\Cryptography\Key\PublicKey;
use CKPL\Pay\Cryptography\Key\PublicKeyInterface;
use CKPL\Pay\Cryptography\Utils\EncodeLengthTrait;
use CKPL\Pay\Exception\BigIntegerException;
use CKPL\Pay\Model\Response\PaymentServiceKeyResponseModel;
use function base64_encode;
use function chr;
use function chunk_split;
use function pack;
use function strlen;

/**
 * Class PublicKeyCalculator.
 *
 * @package CKPL\Pay\Cryptography
 */
class PublicKeyCalculator implements PublicKeyCalculatorInterface
{
    use EncodeLengthTrait;

    /**
     * @type string
     */
    protected const MODULUS = 'modulus';

    /**
     * @type string
     */
    protected const EXPONENT = 'exponent';

    /**
     * @type string
     */
    protected const COMPONENTS_FORMAT = 'Ca*a*';

    /**
     * @type string
     */
    protected const PUBLIC_KEY_FORMAT = 'Ca*a*a*';

    /**
     * @type string
     */
    protected const RSA_ID = '300d06092a864886f70d0101010500';

    /**
     * @type string
     */
    protected const RSA_ID_FORMAT = 'H*';

    /**
     * @param PaymentServiceKeyResponseModel $model
     *
     * @throws BigIntegerException
     *
     * @return PublicKeyInterface
     */
    public function calculateRsaFromModel(PaymentServiceKeyResponseModel $model): PublicKeyInterface
    {
        return $this->calculateRsa(new Modulus($model->getModulus()), new Exponent($model->getExponent()));
    }

    /**
     * @param ComponentInterface $modulus
     * @param ComponentInterface $exponent
     *
     * @throws BigIntegerException
     *
     * @return PublicKeyInterface
     */
    public function calculateRsa(ComponentInterface $modulus, ComponentInterface $exponent): PublicKeyInterface
    {
        $modulusProcessed = new BigInteger(new StringValue($modulus->getDecodedComponent()), BigIntegerInterface::BASE_256);
        $exponentProcessed = new BigInteger(new StringValue($exponent->getDecodedComponent()), BigIntegerInterface::BASE_256);

        $modulus = $modulusProcessed->toBytes(true);
        $exponent = $exponentProcessed->toBytes(true);

        $components = $this->createComponents($modulus, $exponent);
        $rsaPublicKey = $this->createRsaFirstLayer($components[static::MODULUS], $components[static::EXPONENT]);

        $rsaId = $this->createRsaId();

        $rsaPublicKey = $this->createRsaSecondLayer($rsaPublicKey, $rsaId);
        $rsaPublicKey = $this->createRsaFinalLayer($rsaPublicKey);

        return new PublicKey($rsaPublicKey);
    }

    /**
     * @return string
     */
    protected function createRsaId(): string
    {
        return pack(static::RSA_ID_FORMAT, static::RSA_ID);
    }

    /**
     * @param string $modulus
     * @param string $exponent
     *
     * @return array
     */
    protected function createComponents(string $modulus, string $exponent): array
    {
        return [
            static::MODULUS => pack(static::COMPONENTS_FORMAT, 2, $this->encodeLength(strlen($modulus)), $modulus),
            static::EXPONENT => pack(static::COMPONENTS_FORMAT, 2, $this->encodeLength(strlen($exponent)), $exponent),
        ];
    }

    /**
     * @param string $formattedModulus
     * @param string $formattedExponent
     *
     * @return string
     */
    protected function createRsaFirstLayer(string $formattedModulus, string $formattedExponent): string
    {
        return pack(
            static::PUBLIC_KEY_FORMAT,
            48,
            $this->encodeLength(
                strlen($formattedModulus) + strlen($formattedExponent)
            ),
            $formattedModulus,
            $formattedExponent
        );
    }

    /**
     * @param string $firstLayerRsa
     * @param string $rsaId
     *
     * @return string
     */
    protected function createRsaSecondLayer(string $firstLayerRsa, string $rsaId): string
    {
        $rsaPublicKey = chr(0).$firstLayerRsa;
        $rsaPublicKey = chr(3).$this->encodeLength(strlen($rsaPublicKey)).$rsaPublicKey;

        return pack(
            static::COMPONENTS_FORMAT,
            48,
            $this->encodeLength(strlen($rsaId.$rsaPublicKey)),
            $rsaId.$rsaPublicKey
        );
    }

    /**
     * @param string $secondLayerRsa
     *
     * @return string
     */
    protected function createRsaFinalLayer(string $secondLayerRsa): string
    {
        return "-----BEGIN PUBLIC KEY-----\r\n". chunk_split(base64_encode($secondLayerRsa), 64).'-----END PUBLIC KEY-----';
    }
}
