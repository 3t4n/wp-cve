<?php

/**
 * DSA Parameters
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2015 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\phpseclib3\Crypt\DSA;

use Mailster\Gmail\phpseclib3\Crypt\DSA;

/**
 * DSA Parameters
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
class Parameters extends DSA
{
    /**
     * Returns the parameters
     *
     * @param string $type
     * @param array $options optional
     * @return string
     */
    public function toString($type = 'PKCS1', array $options = [])
    {
        $type = self::validatePlugin('Keys', 'PKCS1', 'saveParameters');

        return $type::saveParameters($this->p, $this->q, $this->g, $options);
    }
}
