<?php
/**
 * @license MIT
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */ declare(strict_types=1);

/*
 * This file is part of Composer.
 *
 * (c) Nils Adermann <naderman@naderman.de>
 *     Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CoffeeCode\Composer\Util;

use CoffeeCode\Composer\Package\CompletePackageInterface;
use CoffeeCode\Composer\Package\PackageInterface;

class PackageInfo
{
    public static function getViewSourceUrl(PackageInterface $package): ?string
    {
        if ($package instanceof CompletePackageInterface && isset($package->getSupport()['source']) && '' !== $package->getSupport()['source']) {
            return $package->getSupport()['source'];
        }

        return $package->getSourceUrl();
    }

    public static function getViewSourceOrHomepageUrl(PackageInterface $package): ?string
    {
        $url = self::getViewSourceUrl($package) ?? ($package instanceof CompletePackageInterface ? $package->getHomepage() : null);

        if ($url === '') {
            return null;
        }

        return $url;
    }
}
