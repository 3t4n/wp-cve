<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */
declare (strict_types=1);
namespace Modular\ConnectorDependencies\Ramsey\Uuid\Generator;

/**
 * RandomGeneratorFactory retrieves a default random generator, based on the
 * environment
 * @internal
 */
class RandomGeneratorFactory
{
    /**
     * Returns a default random generator, based on the current environment
     */
    public function getGenerator() : RandomGeneratorInterface
    {
        return new RandomBytesGenerator();
    }
}
