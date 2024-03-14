<?php

namespace Modular\ConnectorDependencies;

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
use Modular\ConnectorDependencies\Symfony\Component\VarDumper\VarDumper;
if (!\function_exists('Modular\\ConnectorDependencies\\dump')) {
    /**
     * @author Nicolas Grekas <p@tchwork.com>
     * @internal
     */
    function dump($var, ...$moreVars)
    {
        VarDumper::dump($var);
        foreach ($moreVars as $v) {
            VarDumper::dump($v);
        }
        if (1 < \func_num_args()) {
            return \func_get_args();
        }
        return $var;
    }
}
if (!\function_exists('Modular\\ConnectorDependencies\\dd')) {
    /**
     * @return never
     * @internal
     */
    function dd(...$vars)
    {
        if (!\in_array(\PHP_SAPI, ['cli', 'phpdbg'], \true) && !\headers_sent()) {
            \header('HTTP/1.1 500 Internal Server Error');
        }
        foreach ($vars as $v) {
            VarDumper::dump($v);
        }
        exit(1);
    }
}
