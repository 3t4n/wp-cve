<?php

declare (strict_types=1);
namespace Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Portuguese;

use Modular\ConnectorDependencies\Doctrine\Inflector\GenericLanguageInflectorFactory;
use Modular\ConnectorDependencies\Doctrine\Inflector\Rules\Ruleset;
/** @internal */
final class InflectorFactory extends GenericLanguageInflectorFactory
{
    protected function getSingularRuleset() : Ruleset
    {
        return Rules::getSingularRuleset();
    }
    protected function getPluralRuleset() : Ruleset
    {
        return Rules::getPluralRuleset();
    }
}
