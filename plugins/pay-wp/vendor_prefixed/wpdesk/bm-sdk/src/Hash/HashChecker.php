<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Hash;

use WPPayVendor\BlueMedia\Common\Exception\HashNotReturnedFromServerException;
use WPPayVendor\BlueMedia\Configuration;
final class HashChecker
{
    public static function checkHash(\WPPayVendor\BlueMedia\Hash\HashableInterface $data, \WPPayVendor\BlueMedia\Configuration $configuration) : bool
    {
        if (!$data->isHashPresent()) {
            throw \WPPayVendor\BlueMedia\Common\Exception\HashNotReturnedFromServerException::noHash();
        }
        $dataHash = \WPPayVendor\BlueMedia\Hash\HashGenerator::generateHash($data->toArray(), $configuration);
        return $dataHash === $data->getHash();
    }
}
