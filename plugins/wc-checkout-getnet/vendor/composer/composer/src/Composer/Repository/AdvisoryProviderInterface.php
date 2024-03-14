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

namespace CoffeeCode\Composer\Repository;

use CoffeeCode\Composer\Semver\Constraint\ConstraintInterface;
use CoffeeCode\Composer\Advisory\PartialSecurityAdvisory;
use CoffeeCode\Composer\Advisory\SecurityAdvisory;

/**
 * Repositories that allow fetching security advisory data
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 * @internal
 */
interface AdvisoryProviderInterface
{
    public function hasSecurityAdvisories(): bool;

    /**
     * @param array<string, ConstraintInterface> $packageConstraintMap Map of package name to constraint (can be MatchAllConstraint to fetch all advisories)
     * @return ($allowPartialAdvisories is true ? array{namesFound: string[], advisories: array<string, array<PartialSecurityAdvisory|SecurityAdvisory>>} : array{namesFound: string[], advisories: array<string, array<SecurityAdvisory>>})
     */
    public function getSecurityAdvisories(array $packageConstraintMap, bool $allowPartialAdvisories = false): array;
}
