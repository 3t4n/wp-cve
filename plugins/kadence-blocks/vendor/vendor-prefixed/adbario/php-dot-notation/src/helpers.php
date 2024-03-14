<?php
/**
 * Dot - PHP dot notation access to arrays
 *
 * @author  Riku Särkinen <riku@adbar.io>
 * @link    https://github.com/adbario/php-dot-notation
 * @license https://github.com/adbario/php-dot-notation/blob/2.x/LICENSE.md (MIT License)
 *
 * Modified by kadencewp on 07-March-2024 using {@see https://github.com/BrianHenryIE/strauss}.
 */

use KadenceWP\KadenceBlocks\Adbar\Dot;

if (! function_exists('dot')) {
    /**
     * Create a new Dot object with the given items and optional delimiter
     *
     * @param  mixed  $items
     * @param  string $delimiter
     * @return \KadenceWP\KadenceBlocks\Adbar\Dot
     */
    function dot($items, $delimiter = '.')
    {
        return new Dot($items, $delimiter);
    }
}
