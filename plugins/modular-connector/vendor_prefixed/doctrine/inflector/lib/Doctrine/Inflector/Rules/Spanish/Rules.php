<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Spanish;

use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Patterns;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Ruleset;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Substitutions;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Transformations;
/** @internal */
final class Rules
{
    public static function getSingularRuleset() : Ruleset
    {
        return new Ruleset(new Transformations(...Inflectible::getSingular()), new Patterns(...Uninflected::getSingular()), (new Substitutions(...Inflectible::getIrregular()))->getFlippedSubstitutions());
    }
    public static function getPluralRuleset() : Ruleset
    {
        return new Ruleset(new Transformations(...Inflectible::getPlural()), new Patterns(...Uninflected::getPlural()), new Substitutions(...Inflectible::getIrregular()));
    }
}
