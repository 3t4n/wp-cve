<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

if (\PHP_VERSION_ID < 80200) {
    #[WooCustomGateway_Attribute(Attribute::TARGET_PARAMETER)]
    final class WooCustomGateway_SensitiveParameter
    {
        public function __construct()
        {
        }
    }
}
