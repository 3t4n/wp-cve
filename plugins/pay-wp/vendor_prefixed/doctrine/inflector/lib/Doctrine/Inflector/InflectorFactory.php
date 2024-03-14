<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector;

use WPPayVendor\Doctrine\Inflector\Rules\English;
use WPPayVendor\Doctrine\Inflector\Rules\French;
use WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal;
use WPPayVendor\Doctrine\Inflector\Rules\Portuguese;
use WPPayVendor\Doctrine\Inflector\Rules\Spanish;
use WPPayVendor\Doctrine\Inflector\Rules\Turkish;
use InvalidArgumentException;
use function sprintf;
final class InflectorFactory
{
    public static function create() : \WPPayVendor\Doctrine\Inflector\LanguageInflectorFactory
    {
        return self::createForLanguage(\WPPayVendor\Doctrine\Inflector\Language::ENGLISH);
    }
    public static function createForLanguage(string $language) : \WPPayVendor\Doctrine\Inflector\LanguageInflectorFactory
    {
        switch ($language) {
            case \WPPayVendor\Doctrine\Inflector\Language::ENGLISH:
                return new \WPPayVendor\Doctrine\Inflector\Rules\English\InflectorFactory();
            case \WPPayVendor\Doctrine\Inflector\Language::FRENCH:
                return new \WPPayVendor\Doctrine\Inflector\Rules\French\InflectorFactory();
            case \WPPayVendor\Doctrine\Inflector\Language::NORWEGIAN_BOKMAL:
                return new \WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\InflectorFactory();
            case \WPPayVendor\Doctrine\Inflector\Language::PORTUGUESE:
                return new \WPPayVendor\Doctrine\Inflector\Rules\Portuguese\InflectorFactory();
            case \WPPayVendor\Doctrine\Inflector\Language::SPANISH:
                return new \WPPayVendor\Doctrine\Inflector\Rules\Spanish\InflectorFactory();
            case \WPPayVendor\Doctrine\Inflector\Language::TURKISH:
                return new \WPPayVendor\Doctrine\Inflector\Rules\Turkish\InflectorFactory();
            default:
                throw new \InvalidArgumentException(\sprintf('Language "%s" is not supported.', $language));
        }
    }
}
