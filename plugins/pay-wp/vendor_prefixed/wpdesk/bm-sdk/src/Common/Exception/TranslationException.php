<?php

declare (strict_types=1);
namespace WPPayVendor\BlueMedia\Common\Exception;

final class TranslationException extends \RuntimeException
{
    public static function missingTranslation(array $keys) : self
    {
        return new self(\sprintf('Missing required translation key(s): %s', \join(', ', $keys)));
    }
}
