<?php

declare (strict_types=1);
namespace WPPayVendor\Doctrine\Inflector;

use WPPayVendor\Doctrine\Inflector\Rules\Ruleset;
use function array_merge;
/**
 * Inflects based on multiple rulesets.
 *
 * Rules:
 * - If the word matches any uninflected word pattern, it is not inflected
 * - The first ruleset that returns a different value for an irregular word wins
 * - The first ruleset that returns a different value for a regular word wins
 * - If none of the above match, the word is left as-is
 */
class RulesetInflector implements \WPPayVendor\Doctrine\Inflector\WordInflector
{
    /** @var Ruleset[] */
    private $rulesets;
    public function __construct(\WPPayVendor\Doctrine\Inflector\Rules\Ruleset $ruleset, \WPPayVendor\Doctrine\Inflector\Rules\Ruleset ...$rulesets)
    {
        $this->rulesets = \array_merge([$ruleset], $rulesets);
    }
    public function inflect(string $word) : string
    {
        if ($word === '') {
            return '';
        }
        foreach ($this->rulesets as $ruleset) {
            if ($ruleset->getUninflected()->matches($word)) {
                return $word;
            }
            $inflected = $ruleset->getIrregular()->inflect($word);
            if ($inflected !== $word) {
                return $inflected;
            }
            $inflected = $ruleset->getRegular()->inflect($word);
            if ($inflected !== $word) {
                return $inflected;
            }
        }
        return $word;
    }
}
