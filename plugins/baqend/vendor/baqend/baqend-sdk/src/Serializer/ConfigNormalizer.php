<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Model\Config\Config;

use Symfony\Component\Serializer\Exception\NotNormalizableValueException;

use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Normalizer\scalar;

/**
 * Class ConfigNormalizer created on 24.01.2018.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class ConfigNormalizer implements NormalizerInterface, DenormalizerInterface
{

    /**
     * {@inheritdoc}
     */
    public function denormalize($data, $class, $format = null, array $context = []) {
        if (!is_array($data)) {
            throw new NotNormalizableValueException('Can only denormalize arrays');
        }

        $config = new Config();
        $config->setData($data);

        return $config;
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null) {
        return $type === Config::class;
    }

    /**
     * {@inheritdoc}
     */
    public function normalize($object, $format = null, array $context = []) {
        if ($object instanceof Config) {
            return $object->getData();
        }

        throw new \InvalidArgumentException('The object must be an instance of "\Baqend\SDK\Model\Config\Config".');
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null) {
        return $data instanceof Config;
    }
}
