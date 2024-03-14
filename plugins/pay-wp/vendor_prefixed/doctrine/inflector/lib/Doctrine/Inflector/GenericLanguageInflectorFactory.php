<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector;

use WPPayVendor\Doctrine\Inflector\Rules\Ruleset;
use function array_unshift;
abstract class GenericLanguageInflectorFactory implements \WPPayVendor\Doctrine\Inflector\LanguageInflectorFactory
{
    /** @var Ruleset[] */
    private $singularRulesets = [];
    /** @var Ruleset[] */
    private $pluralRulesets = [];
    public final function __construct()
    {
        $this->singularRulesets[] = $this->getSingularRuleset();
        $this->pluralRulesets[] = $this->getPluralRuleset();
    }
    public final function build() : \WPPayVendor\Doctrine\Inflector\Inflector
    {
        return new \WPPayVendor\Doctrine\Inflector\Inflector(new \WPPayVendor\Doctrine\Inflector\CachedWordInflector(new \WPPayVendor\Doctrine\Inflector\RulesetInflector(...$this->singularRulesets)), new \WPPayVendor\Doctrine\Inflector\CachedWordInflector(new \WPPayVendor\Doctrine\Inflector\RulesetInflector(...$this->pluralRulesets)));
    }
    public final function withSingularRules(?\WPPayVendor\Doctrine\Inflector\Rules\Ruleset $singularRules, bool $reset = \false) : \WPPayVendor\Doctrine\Inflector\LanguageInflectorFactory
    {
        if ($reset) {
            $this->singularRulesets = [];
        }
        if ($singularRules instanceof \WPPayVendor\Doctrine\Inflector\Rules\Ruleset) {
            \array_unshift($this->singularRulesets, $singularRules);
        }
        return $this;
    }
    public final function withPluralRules(?\WPPayVendor\Doctrine\Inflector\Rules\Ruleset $pluralRules, bool $reset = \false) : \WPPayVendor\Doctrine\Inflector\LanguageInflectorFactory
    {
        if ($reset) {
            $this->pluralRulesets = [];
        }
        if ($pluralRules instanceof \WPPayVendor\Doctrine\Inflector\Rules\Ruleset) {
            \array_unshift($this->pluralRulesets, $pluralRules);
        }
        return $this;
    }
    protected abstract function getSingularRuleset() : \WPPayVendor\Doctrine\Inflector\Rules\Ruleset;
    protected abstract function getPluralRuleset() : \WPPayVendor\Doctrine\Inflector\Rules\Ruleset;
}
