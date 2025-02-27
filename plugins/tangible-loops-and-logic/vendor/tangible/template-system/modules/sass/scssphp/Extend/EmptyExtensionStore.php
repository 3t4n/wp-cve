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

namespace Tangible\ScssPhp\Extend;

use Tangible\ScssPhp\Ast\Sass\Statement\ExtendRule;
use Tangible\ScssPhp\Ast\Selector\SelectorList;
use Tangible\ScssPhp\Ast\Selector\SimpleSelector;
use Tangible\ScssPhp\Extend\ExtensionStore;
use Tangible\ScssPhp\Util\Box;

/**
 * An {@see ExtensionStore} that contains no extensions and can have no extensions
 * added.
 *
 * @internal
 */
final class EmptyExtensionStore implements ExtensionStore
{
    public function isEmpty(): bool
    {
        return true;
    }

    public function getSimpleSelectors(): array
    {
        return [];
    }

    public function extensionsWhereTarget(callable $callback): iterable
    {
        return [];
    }

    public function addSelector(SelectorList $selector, ?array $mediaContext): Box
    {
        throw new \BadMethodCallException("addSelector() can't be called for a const ExtensionStore.");
    }

    public function addExtension(SelectorList $extender, SimpleSelector $target, ExtendRule $extend, ?array $mediaContext): void
    {
        throw new \BadMethodCallException("addExtension() can't be called for a const ExtensionStore.");
    }

    public function addExtensions(iterable $extensionStores): void
    {
        throw new \BadMethodCallException("addExtensions() can't be called for a const ExtensionStore.");
    }

    public function clone(): array
    {
        /** @var \SplObjectStorage<SelectorList, Box<SelectorList>> $map */
        $map = new \SplObjectStorage();

        return [new EmptyExtensionStore(), $map];
    }
}
