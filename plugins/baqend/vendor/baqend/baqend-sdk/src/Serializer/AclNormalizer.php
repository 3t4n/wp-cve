<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Model\Acl;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class AclNormalizer created on 12.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class AclNormalizer implements NormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    public function normalize($object, $format = null, array $context = []) {
        if (!$object instanceof Acl) {
            throw new \InvalidArgumentException('The object must be an instance of "'.Acl::class.'".');
        }

        $return = new \stdClass();
        if (!$object->getRead()->isEmpty()) {
            $return->read = $this->normalizer->normalize($object->getRead(), $format, $context);
        }
        if (!$object->getWrite()->isEmpty()) {
            $return->write = $this->normalizer->normalize($object->getWrite(), $format, $context);
        }

        return $return;
    }

    public function supportsNormalization($data, $format = null) {
        return $data instanceof Acl;
    }
}
