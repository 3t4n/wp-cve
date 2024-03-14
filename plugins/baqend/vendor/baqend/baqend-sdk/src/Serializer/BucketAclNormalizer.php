<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Model\BucketAcl;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class BucketAclNormalizer created on 12.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class BucketAclNormalizer implements NormalizerInterface, NormalizerAwareInterface
{

    use NormalizerAwareTrait;

    public function normalize($object, $format = null, array $context = []) {
        if (!$object instanceof BucketAcl) {
            throw new \InvalidArgumentException('The object must be an instance of "'.BucketAcl::class.'".');
        }

        $return = new \stdClass();
        if (!$object->getLoad()->isEmpty()) {
            $return->load = $this->normalizer->normalize($object->getLoad(), $format, $context);
        }
        if (!$object->getInsert()->isEmpty()) {
            $return->insert = $this->normalizer->normalize($object->getInsert(), $format, $context);
        }
        if (!$object->getUpdate()->isEmpty()) {
            $return->update = $this->normalizer->normalize($object->getUpdate(), $format, $context);
        }
        if (!$object->getDelete()->isEmpty()) {
            $return->delete = $this->normalizer->normalize($object->getDelete(), $format, $context);
        }
        if (!$object->getQuery()->isEmpty()) {
            $return->query = $this->normalizer->normalize($object->getQuery(), $format, $context);
        }

        return $return;
    }

    public function supportsNormalization($data, $format = null) {
        return $data instanceof BucketAcl;
    }
}
