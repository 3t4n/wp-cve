<?php

/**
 * SCSSPHP
 *
 * @copyright 2012-2020 Leaf Corcoran
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace Tangible\ScssPhp\Ast\Sass;

use Tangible\ScssPhp\Ast\Css\CssAtRule;
use Tangible\ScssPhp\Ast\Css\CssMediaRule;
use Tangible\ScssPhp\Ast\Css\CssParentNode;
use Tangible\ScssPhp\Ast\Css\CssStyleRule;
use Tangible\ScssPhp\Ast\Css\CssSupportsRule;
use Tangible\ScssPhp\Exception\SassFormatException;
use Tangible\ScssPhp\Logger\LoggerInterface;
use Tangible\ScssPhp\Parser\AtRootQueryParser;
use Tangible\ScssPhp\Parser\InterpolationMap;

/**
 * A query for the `@at-root` rule.
 *
 * @internal
 */
final class AtRootQuery
{
    /**
     * Whether the query includes or excludes rules with the specified names.
     */
    private readonly bool $include;

    /**
     * The names of the rules included or excluded by this query.
     *
     * There are two special names. "all" indicates that all rules are included
     * or excluded, and "rule" indicates style rules are included or excluded.
     *
     * @var string[]
     */
    private readonly array $names;

    /**
     * Whether this includes or excludes *all* rules.
     */
    private readonly bool $all;

    /**
     * Whether this includes or excludes style rules.
     */
    private readonly bool $rule;

    /**
     * Parses an at-root query from $contents.
     *
     * If passed, $url is the name of the file from which $contents comes.
     *
     * @throws SassFormatException if parsing fails
     */
    public static function parse(string $contents, ?LoggerInterface $logger = null, ?string $url = null, ?InterpolationMap $interpolationMap = null): AtRootQuery
    {
        return (new AtRootQueryParser($contents, $logger, $url, $interpolationMap))->parse();
    }

    /**
     * @param string[] $names
     */
    public static function create(array $names, bool $include): AtRootQuery
    {
        return new AtRootQuery($names, $include, \in_array('all', $names, true), \in_array('rule', $names, true));
    }

    /**
     * The default at-root query
     */
    public static function getDefault(): AtRootQuery
    {
        return new AtRootQuery([], false, false, true);
    }

    /**
     * @param string[] $names
     */
    private function __construct(array $names, bool $include, bool $all, bool $rule)
    {
        $this->include = $include;
        $this->names = $names;
        $this->all = $all;
        $this->rule = $rule;
    }

    public function getInclude(): bool
    {
        return $this->include;
    }

    /**
     * @return string[]
     */
    public function getNames(): array
    {
        return $this->names;
    }

    /**
     * Whether this excludes style rules.
     *
     * Note that this takes {@see include} into account.
     */
    public function excludesStyleRules(): bool
    {
        return ($this->all || $this->rule) !== $this->include;
    }

    /**
     * Returns whether $this excludes $node
     */
    public function excludes(CssParentNode $node): bool
    {
        if ($this->all) {
            return !$this->include;
        }

        if ($node instanceof CssStyleRule) {
            return $this->excludesStyleRules();
        }

        if ($node instanceof CssMediaRule) {
            return $this->excludesName('media');
        }

        if ($node instanceof CssSupportsRule) {
            return $this->excludesName('supports');
        }

        if ($node instanceof CssAtRule) {
            return $this->excludesName(strtolower($node->getName()->getValue()));
        }

        return false;
    }

    /**
     * Returns whether $this excludes an at-rule with the given $name.
     */
    public function excludesName(string $name): bool
    {
        return ($this->all || \in_array($name, $this->names, true)) !== $this->include;
    }
}
