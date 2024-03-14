<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Hash;

use WPPayVendor\BlueMedia\Configuration;
final class HashGenerator
{
    /**
     * Generates hash.
     *
     * @param array $data
     * @param Configuration $configuration
     *
     * @return string
     */
    public static function generateHash(array $data, \WPPayVendor\BlueMedia\Configuration $configuration) : string
    {
        $result = '';
        foreach ($data as $name => $value) {
            if (\mb_strtolower($name) === 'hash') {
                unset($data[$name]);
                continue;
            }
            if (\is_array($value)) {
                $value = \array_filter($value, 'mb_strlen');
                $value = \implode($configuration->getHashSeparator(), $value);
            }
            $result .= $value . $configuration->getHashSeparator();
        }
        $result .= $configuration->getSharedKey();
        return \hash($configuration->getHashAlgo(), $result);
    }
}
