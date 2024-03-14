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

use CoffeeCode\Composer\Package\RootPackageInterface;

/**
 * Root package repository.
 *
 * This is used for serving the RootPackage inside an in-memory InstalledRepository
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 */
class RootPackageRepository extends ArrayRepository
{
    public function __construct(RootPackageInterface $package)
    {
        parent::__construct([$package]);
    }

    public function getRepoName(): string
    {
        return 'root package repo';
    }
}
