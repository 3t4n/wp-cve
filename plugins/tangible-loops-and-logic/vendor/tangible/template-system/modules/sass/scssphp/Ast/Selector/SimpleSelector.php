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

namespace Tangible\ScssPhp\Ast\Selector;

use Tangible\ScssPhp\Exception\SassFormatException;
use Tangible\ScssPhp\Exception\SassScriptException;
use Tangible\ScssPhp\Logger\LoggerInterface;
use Tangible\ScssPhp\Parser\SelectorParser;
use Tangible\ScssPhp\Util\EquatableUtil;
use Tangible\ScssPhp\Util\ListUtil;

/**
 * An abstract superclass for simple selectors.
 *
 * @internal
 */
abstract class SimpleSelector extends Selector
{
    /**
     * Names of pseudo-classes that take selectors as arguments, and that are
     * subselectors of their arguments.
     *
     * For example, `.foo` is a superselector of `:matches(.foo)`.
     */
    private const SUBSELECTOR_PSEUDOS = [
        'is',
        'matches',
        'where',
        'any',
        'nth-child',
        'nth-last-child',
    ];

    /**
     * Parses a simple selector from $contents.
     *
     * If passed, $url is the name of the file from which $contents comes.
     * $allowParent controls whether a {@see ParentSelector} is allowed in this
     * selector.
     *
     * @throws SassFormatException if parsing fails.
     */
    public static function parse(string $contents, ?LoggerInterface $logger = null, ?string $url = null, bool $allowParent = true): SimpleSelector
    {
        return (new SelectorParser($contents, $logger, $url, $allowParent))->parseSimpleSelector();
    }

    /**
     * This selector's specificity.
     *
     * Specificity is represented in base 1000. The spec says this should be
     * "sufficiently high"; it's extremely unlikely that any single selector
     * sequence will contain 1000 simple selectors.
     */
    public function getSpecificity(): int
    {
        return 1000;
    }

    /**
     * Returns a new {@see SimpleSelector} based on $this, as though it had been
     * written with $suffix at the end.
     *
     * Assumes $suffix is a valid identifier suffix. If this wouldn't produce a
     * valid SimpleSelector, throws a {@see SassScriptException}.
     *
     * @throws SassScriptException
     */
    public function addSuffix(string $suffix): SimpleSelector
    {
        // TODO use a multi-span exception
        throw new SassScriptException("Invalid parent selector \"$this\"");
    }

    /**
     * Returns the components of a {@see CompoundSelector} that matches only elements
     * matched by both this and $compound.
     *
     * By default, this just returns a copy of $compound with this selector
     * added to the end, or returns the original array if this selector already
     * exists in it.
     *
     * Returns `null` if unification is impossible—for example, if there are
     * multiple ID selectors.
     *
     * @param list<SimpleSelector> $compound
     *
     * @return list<SimpleSelector>|null
     */
    public function unify(array $compound): ?array
    {
        if (\count($compound) === 1) {
            $other = $compound[0];

            if ($other instanceof UniversalSelector || $other instanceof PseudoSelector && ($other->isHost() || $other->isHostContext())) {
                return $other->unify([$this]);
            }
        }

        if (EquatableUtil::listContains($compound, $this)) {
            return $compound;
        }

        $result = [];
        $addedThis = false;

        foreach ($compound as $simple) {
            // Make sure pseudo selectors always come last.
            if (!$addedThis && $simple instanceof PseudoSelector) {
                $result[] = $this;
                $addedThis = true;
            }

            $result[] = $simple;
        }

        if (!$addedThis) {
            $result[] = $this;
        }

        return $result;
    }

    public function isSuperselector(SimpleSelector $other): bool
    {
        if ($this === $other || $this->equals($other)) {
            return true;
        }

        if ($other instanceof PseudoSelector && $other->isClass()) {
            $list = $other->getSelector();

            if ($list !== null && \in_array($other->getNormalizedName(), self::SUBSELECTOR_PSEUDOS, true)) {
                foreach ($list->getComponents() as $complex) {
                    if (\count($complex->getComponents()) === 0) {
                        return false;
                    }

                    foreach (ListUtil::last($complex->getComponents())->getSelector()->getComponents() as $simple) {
                        if ($this->isSuperselector($simple)) {
                            continue 2;
                        }
                    }

                    return false;
                }

                return true;
            }
        }

        return false;
    }
}
