<?php

/**
 * sect113r2
 *
 * PHP version 5 and 7
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2017 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://pear.php.net/package/Math_BigInteger
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\phpseclib3\Crypt\EC\Curves;

use Mailster\Gmail\phpseclib3\Crypt\EC\BaseCurves\Binary;
use Mailster\Gmail\phpseclib3\Math\BigInteger;

class sect113r2 extends Binary
{
    public function __construct()
    {
        $this->setModulo(113, 9, 0);
        $this->setCoefficients(
            '00689918DBEC7E5A0DD6DFC0AA55C7',
            '0095E9A9EC9B297BD4BF36E059184F'
        );
        $this->setBasePoint(
            '01A57A6A7B26CA5EF52FCDB8164797',
            '00B3ADC94ED1FE674C06E695BABA1D'
        );
        $this->setOrder(new BigInteger('010000000000000108789B2496AF93', 16));
    }
}
