<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector\Rules\Turkish;

use WPPayVendor\Doctrine\Inflector\GenericLanguageInflectorFactory;
use WPPayVendor\Doctrine\Inflector\Rules\Ruleset;
final class InflectorFactory extends \WPPayVendor\Doctrine\Inflector\GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : \WPPayVendor\Doctrine\Inflector\Rules\Ruleset
    {
        return \WPPayVendor\Doctrine\Inflector\Rules\Turkish\Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : \WPPayVendor\Doctrine\Inflector\Rules\Ruleset
    {
        return \WPPayVendor\Doctrine\Inflector\Rules\Turkish\Rules::getPluralRuleset();
    }
}
