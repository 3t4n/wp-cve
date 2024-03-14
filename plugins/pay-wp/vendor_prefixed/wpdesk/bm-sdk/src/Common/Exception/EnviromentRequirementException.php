<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Exception;

final class EnviromentRequirementException extends \RuntimeException
{
    public static function phpVersionError() : self
    {
        return new self(\sprintf('Required at least PHP version 7.2, current version "%s"', \PHP_VERSION));
    }
    public static function phpExtensionMissing(string $extensionName) : self
    {
        return new self(\sprintf('Extension "%s" is required', $extensionName));
    }
}
