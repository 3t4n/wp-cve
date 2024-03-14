<?php

namespace Baqend\SDK\Serializer;

use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Normalizes an object implementing the {@see \DateTimeInterface} to a date string.
 * Denormalizes a date string to an instance of {@see \DateTime} or {@see \DateTimeImmutable}.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class DateTimeNormalizer implements NormalizerInterface, DenormalizerInterface
{
    private static $supportedTypes = [
        \DateTimeInterface::class => true,
        \DateTimeImmutable::class => true,
        \DateTime::class => true,
    ];

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function normalize($object, $format = null, array $context = []) {
        if ($object === null) {
            return null;
        }

        if (!$object instanceof \DateTimeInterface) {
            throw new InvalidArgumentException('The object must implement the "\DateTimeInterface".');
        }

        $datetime = $object->format('Y-m-d\TH:i:s');
        $macros = $this->normalizeMacros($object->format('u'));
        $timezone = $this->normalizeTimezone($object->format('P'));

        return "$datetime$macros$timezone";
    }

    /**
     * {@inheritdoc}
     */
    public function supportsNormalization($data, $format = null) {
        return $data instanceof \DateTimeInterface;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotNormalizableValueException
     */
    public function denormalize($data, $class, $format = null, array $context = []) {
        if ($data === null) {
            return null;
        }

        if ($data === false) {
            throw new NotNormalizableValueException('Cannot denormalize false');
        }

        try {
            return new \DateTime($data, null);
        } catch (\Exception $e) {
            throw new NotNormalizableValueException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function supportsDenormalization($data, $type, $format = null) {
        return isset(self::$supportedTypes[$type]);
    }

    /**
     * Normalizes the given timezone.
     *
     * @param string $timezone
     * @return string
     */
    private function normalizeTimezone($timezone) {
        if ($timezone === '+00:00' || $timezone === '-00:00') {
            return 'Z';
        }

        return $timezone;
    }

    /**
     * Normalizes the macros of the time.
     *
     * @param string $macros
     * @return string
     */
    private function normalizeMacros($macros) {
        $macros = rtrim($macros, '0');
        if (!empty($macros)) {
            return '.'.$macros;
        }

        return '';
    }
}
