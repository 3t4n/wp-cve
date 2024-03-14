<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal;

use WPPayVendor\Doctrine\Inflector\Rules\Patterns;
use WPPayVendor\Doctrine\Inflector\Rules\Ruleset;
use WPPayVendor\Doctrine\Inflector\Rules\Substitutions;
use WPPayVendor\Doctrine\Inflector\Rules\Transformations;
final class Rules
{
    public static function getSingularRuleset() : \WPPayVendor\Doctrine\Inflector\Rules\Ruleset
    {
        return new \WPPayVendor\Doctrine\Inflector\Rules\Ruleset(new \WPPayVendor\Doctrine\Inflector\Rules\Transformations(...\WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getSingular()), new \WPPayVendor\Doctrine\Inflector\Rules\Patterns(...\WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\Uninflected::getSingular()), (new \WPPayVendor\Doctrine\Inflector\Rules\Substitutions(...\WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : \WPPayVendor\Doctrine\Inflector\Rules\Ruleset
    {
        return new \WPPayVendor\Doctrine\Inflector\Rules\Ruleset(new \WPPayVendor\Doctrine\Inflector\Rules\Transformations(...\WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getPlural()), new \WPPayVendor\Doctrine\Inflector\Rules\Patterns(...\WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\Uninflected::getPlural()), new \WPPayVendor\Doctrine\Inflector\Rules\Substitutions(...\WPPayVendor\Doctrine\Inflector\Rules\NorwegianBokmal\Inflectible::getIrregular()));
    }
}
