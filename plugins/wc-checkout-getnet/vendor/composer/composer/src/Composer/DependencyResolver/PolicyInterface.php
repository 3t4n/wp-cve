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

namespace CoffeeCode\Composer\DependencyResolver;

use CoffeeCode\Composer\Package\PackageInterface;
use CoffeeCode\Composer\Semver\Constraint\Constraint;

/**
 * @author Nils Adermann <naderman@naderman.de>
 */
interface PolicyInterface
{
    /**
     * @phpstan-param Constraint::STR_OP_* $operator
     */
    public function versionCompare(PackageInterface $a, PackageInterface $b, string $operator): bool;

    /**
     * @param  int[]   $literals
     * @return int[]
     */
    public function selectPreferredPackages(Pool $pool, array $literals, ?string $requiredPackage = null): array;
}
