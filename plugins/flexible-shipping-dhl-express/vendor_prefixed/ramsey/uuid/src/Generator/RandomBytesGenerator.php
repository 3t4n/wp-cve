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
namespace DhlVendor\Ramsey\Uuid\Generator;

use DhlVendor\Ramsey\Uuid\Exception\RandomSourceException;
use Throwable;
/**
 * RandomBytesGenerator generates strings of random binary data using the
 * built-in `random_bytes()` PHP function
 *
 * @link http://php.net/random_bytes random_bytes()
 */
class RandomBytesGenerator implements \DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorInterface
{
    /**
     * @throws RandomSourceException if random_bytes() throws an exception/error
     *
     * @inheritDoc
     */
    public function generate(int $length) : string
    {
        try {
            return \random_bytes($length);
        } catch (\Throwable $exception) {
            throw new \DhlVendor\Ramsey\Uuid\Exception\RandomSourceException($exception->getMessage(), (int) $exception->getCode(), $exception);
        }
    }
}
