<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Doctrine\Inflector;

use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\English;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\French;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\NorwegianBokmal;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Portuguese;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Spanish;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Turkish;
use InvalidArgumentException;
use function sprintf;
/** @internal */
final class InflectorFactory
{
    public static function create() : LanguageInflectorFactory
    {
        return self::createForLanguage(Language::ENGLISH);
    }
    public static function createForLanguage(string $language) : LanguageInflectorFactory
    {
        switch ($language) {
            case Language::ENGLISH:
                return new English\InflectorFactory();
            case Language::FRENCH:
                return new French\InflectorFactory();
            case Language::NORWEGIAN_BOKMAL:
                return new NorwegianBokmal\InflectorFactory();
            case Language::PORTUGUESE:
                return new Portuguese\InflectorFactory();
            case Language::SPANISH:
                return new Spanish\InflectorFactory();
            case Language::TURKISH:
                return new Turkish\InflectorFactory();
            default:
                throw new InvalidArgumentException(sprintf('Language "%s" is not supported.', $language));
        }
    }
}
