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

namespace CoffeeCode\Composer\Package\Loader;

use CoffeeCode\Composer\Package\CompletePackage;
use CoffeeCode\Composer\Package\CompleteAliasPackage;
use CoffeeCode\Composer\Package\RootAliasPackage;
use CoffeeCode\Composer\Package\RootPackage;
use CoffeeCode\Composer\Package\BasePackage;

/**
 * Defines a loader that takes an array to create package instances
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
interface LoaderInterface
{
    /**
     * Converts a package from an array to a real instance
     *
     * @param  mixed[] $config package data
     * @param  string  $class  FQCN to be instantiated
     *
     * @return CompletePackage|CompleteAliasPackage|RootPackage|RootAliasPackage
     *
     * @phpstan-param class-string<CompletePackage|RootPackage> $class
     */
    public function load(array $config, string $class = 'CoffeeCode\Composer\Package\CompletePackage'): BasePackage;
}
