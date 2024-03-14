<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Model\Entity;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

/**
 * Class EntityNormalizer created on 31.01.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class EntityNormalizer extends ObjectNormalizer
{

    public function normalize($object, $format = null, array $context = []) {
        // Call the object normalizer
        $normalized = parent::normalize($object, $format, $context);

        // Remove null IDs
        if ($object instanceof Entity && $normalized['id'] === null) {
            unset($normalized['id']);
            unset($normalized['version']);
        }

        return $normalized;
    }
}
