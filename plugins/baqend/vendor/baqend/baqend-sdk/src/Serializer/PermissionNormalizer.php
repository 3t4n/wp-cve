<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Model\Permission;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class PermissionNormalizer created on 12.11.18.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class PermissionNormalizer implements NormalizerInterface, DenormalizerInterface
{

    public function normalize($object, $format = null, array $context = []) {
        if (!$object instanceof Permission) {
            throw new \InvalidArgumentException('The object must be an instance of "'.Permission::class.'".');
        }

        $result = new \stdClass();
        foreach ($object->getRules() as $scope => $rule) {
            $result->$scope = $rule;
        }

        return $result;
    }

    public function supportsNormalization($data, $format = null) {
        return $data instanceof Permission;
    }

    public function denormalize($data, $class, $format = null, array $context = []) {
        return new Permission($data);
    }

    public function supportsDenormalization($data, $type, $format = null) {
        return $type === Permission::class;
    }
}
