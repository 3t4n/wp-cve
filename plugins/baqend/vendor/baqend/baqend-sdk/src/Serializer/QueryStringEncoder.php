<?php

namespace Baqend\SDK\Serializer;

use Symfony\Component\Serializer\Encoder\EncoderInterface;
use Symfony\Component\Serializer\Exception\UnexpectedValueException;

/**
 * Class QueryStringEncoder created on 24.01.18.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\SDK\Serializer
 */
class QueryStringEncoder implements EncoderInterface
{

    /**
     * {@inheritdoc}
     */
    public function encode($data, $format, array $context = []) {
        if (!is_array($data)) {
            throw new UnexpectedValueException('Data needs to be an array');
        }

        if (empty($data)) {
            return '';
        }

        $data = array_filter($data, function ($it) {
            return $it !== null;
        });
        $stringifiedData = array_map([$this, 'convertToString'], $data);

        return http_build_query($stringifiedData);
    }

    /**
     * {@inheritdoc}
     */
    public function supportsEncoding($format) {
        return $format === 'query';
    }

    /**
     * @param mixed $data
     * @return string|array
     */
    private function convertToString($data) {
        if (is_bool($data)) {
            return $data ? 'true' : 'false';
        }

        if (is_scalar($data)) {
            return (string) $data;
        }

        return $data;
    }
}
