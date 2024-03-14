<?php

namespace Baqend\SDK\Serializer;

use Baqend\SDK\Model\Acl;
use Baqend\SDK\Model\File;
use Baqend\SDK\Value\MediaType;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\DenormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class FileNormalizer created on 30.01.2018.
 *
 * @author  Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class FileNormalizer implements
    NormalizerInterface,
    DenormalizerInterface,
    NormalizerAwareInterface,
    DenormalizerAwareInterface
{

    const ENDPOINT = 'endpoint';

    use NormalizerAwareTrait;
    use DenormalizerAwareTrait;

    public function denormalize($data, $class, $format = null, array $context = array()) {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('The data must be an array.');
        }

        // Normalize options
        $denormalizer = $this->denormalizer;
        if (isset($data['acl'])) {
            $data['acl'] = $denormalizer->denormalize($data['acl'], Acl::class, $format, $context);
        }
        if (isset($data['mimeType'])) {
            $data['contentType'] = $denormalizer->denormalize($data['mimeType'], MediaType::class, $format, $context);
            unset($data['mimeType']);
        }
        if (isset($data['lastModified'])) {
            $data['lastModified'] = $denormalizer->denormalize($data['lastModified'], \DateTime::class, $format, $context);
        }

        return new File($context[self::ENDPOINT], $data);
    }

    public function supportsDenormalization($data, $type, $format = null) {
        return $type === File::class;
    }

    public function normalize($object, $format = null, array $context = array()) {
        if (!$object instanceof File) {
            throw new \InvalidArgumentException('The object must be an instance of "'.File::class.'".');
        }

        return [
            'id' => $object->getId(),
            'acl' => $this->normalizer->normalize($object->getAcl(), $format, $context),
            'eTag' => $object->getETag(),
            'mimeType' => $this->normalizer->normalize($object->getContentType(), $format, $context),
            'contentLength' => $object->getContentLength() >= 0 ? $object->getContentLength() : null,
            'lastModified' => $this->normalizer->normalize($object->getLastModified(), $format, $context),
        ];
    }

    public function supportsNormalization($data, $format = null) {
        return $data instanceof File;
    }
}
