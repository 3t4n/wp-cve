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

namespace CoffeeCode\Composer\Filter\PlatformRequirementFilter;

use CoffeeCode\Composer\Package\BasePackage;
use CoffeeCode\Composer\Pcre\Preg;
use CoffeeCode\Composer\Repository\PlatformRepository;
use CoffeeCode\Composer\Semver\Constraint\Constraint;
use CoffeeCode\Composer\Semver\Constraint\ConstraintInterface;
use CoffeeCode\Composer\Semver\Constraint\MatchAllConstraint;
use CoffeeCode\Composer\Semver\Constraint\MultiConstraint;
use CoffeeCode\Composer\Semver\Interval;
use CoffeeCode\Composer\Semver\Intervals;

final class IgnoreListPlatformRequirementFilter implements PlatformRequirementFilterInterface
{
    /**
     * @var non-empty-string
     */
    private $ignoreRegex;

    /**
     * @var non-empty-string
     */
    private $ignoreUpperBoundRegex;

    /**
     * @param string[] $reqList
     */
    public function __construct(array $reqList)
    {
        $ignoreAll = $ignoreUpperBound = [];
        foreach ($reqList as $req) {
            if (substr($req, -1) === '+') {
                $ignoreUpperBound[] = substr($req, 0, -1);
            } else {
                $ignoreAll[] = $req;
            }
        }
        $this->ignoreRegex = BasePackage::packageNamesToRegexp($ignoreAll);
        $this->ignoreUpperBoundRegex = BasePackage::packageNamesToRegexp($ignoreUpperBound);
    }

    public function isIgnored(string $req): bool
    {
        if (!PlatformRepository::isPlatformPackage($req)) {
            return false;
        }

        return Preg::isMatch($this->ignoreRegex, $req);
    }

    /**
     * @param bool $allowUpperBoundOverride For conflicts we do not want the upper bound to be skipped
     */
    public function filterConstraint(string $req, ConstraintInterface $constraint, bool $allowUpperBoundOverride = true): ConstraintInterface
    {
        if (!PlatformRepository::isPlatformPackage($req)) {
            return $constraint;
        }

        if (!$allowUpperBoundOverride || !Preg::isMatch($this->ignoreUpperBoundRegex, $req)) {
            return $constraint;
        }

        if (Preg::isMatch($this->ignoreRegex, $req)) {
            return new MatchAllConstraint;
        }

        $intervals = Intervals::get($constraint);
        $last = end($intervals['numeric']);
        if ($last !== false && (string) $last->getEnd() !== (string) Interval::untilPositiveInfinity()) {
            $constraint = new MultiConstraint([$constraint, new Constraint('>=', $last->getEnd()->getVersion())], false);
        }

        return $constraint;
    }
}
