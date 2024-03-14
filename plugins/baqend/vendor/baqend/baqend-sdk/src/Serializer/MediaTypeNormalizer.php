<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Value\MediaType;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class MediaTypeNormalizer created on 13.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class MediaTypeNormalizer implements NormalizerInterface, DenormalizerInterface
{

    public function normalize($object, $format = null, array $context = []) {
        if (!$object instanceof MediaType) {
            throw new InvalidArgumentException('The object must implement the "'.MediaType::class.'".');
        }

        return $object->__toString();
    }

    public function supportsNormalization($data, $format = null) {
        return $data instanceof MediaType;
    }

    public function denormalize($data, $class, $format = null, array $context = []) {
        if ($data === null) {
            return null;
        }

        return MediaType::parse($data);
    }

    public function supportsDenormalization($data, $type, $format = null) {
        return $type === MediaType::class;
    }
}
