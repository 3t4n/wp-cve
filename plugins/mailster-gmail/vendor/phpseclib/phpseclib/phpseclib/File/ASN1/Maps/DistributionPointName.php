<?php

/**
 * DistributionPointName
 *
 * PHP version 5
 *
 * @author    Jim Wigginton <terrafrost@php.net>
 * @copyright 2016 Jim Wigginton
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @link      http://phpseclib.sourceforge.net
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\phpseclib3\File\ASN1\Maps;

use Mailster\Gmail\phpseclib3\File\ASN1;

/**
 * DistributionPointName
 *
 * @author  Jim Wigginton <terrafrost@php.net>
 */
abstract class DistributionPointName
{
    const MAP = [
        'type' => ASN1::TYPE_CHOICE,
        'children' => [
            'fullName' => [
                'constant' => 0,
                'optional' => true,
                'implicit' => true
            ] + GeneralNames::MAP,
            'nameRelativeToCRLIssuer' => [
                'constant' => 1,
                'optional' => true,
                'implicit' => true
            ] + RelativeDistinguishedName::MAP
        ]
    ];
}
