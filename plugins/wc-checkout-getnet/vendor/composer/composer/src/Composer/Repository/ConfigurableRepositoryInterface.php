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

/**
 * Configurable repository interface.
 *
 * @author Lukas Homza <lukashomz@gmail.com>
 */
interface ConfigurableRepositoryInterface
{
    /**
     * @return mixed[]
     */
    public function getRepoConfig();
}
