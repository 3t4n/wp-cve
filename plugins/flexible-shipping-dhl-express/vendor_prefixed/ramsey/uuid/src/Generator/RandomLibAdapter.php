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

use DhlVendor\RandomLib\Factory;
use DhlVendor\RandomLib\Generator;
/**
 * RandomLibAdapter generates strings of random binary data using the
 * paragonie/random-lib library
 *
 * @link https://packagist.org/packages/paragonie/random-lib paragonie/random-lib
 */
class RandomLibAdapter implements \DhlVendor\Ramsey\Uuid\Generator\RandomGeneratorInterface
{
    /**
     * @var Generator
     */
    private $generator;
    /**
     * Constructs a RandomLibAdapter
     *
     * By default, if no Generator is passed in, this creates a high-strength
     * generator to use when generating random binary data.
     *
     * @param Generator|null $generator The generator to use when generating binary data
     */
    public function __construct(?\DhlVendor\RandomLib\Generator $generator = null)
    {
        if ($generator === null) {
            $factory = new \DhlVendor\RandomLib\Factory();
            $generator = $factory->getHighStrengthGenerator();
        }
        $this->generator = $generator;
    }
    public function generate(int $length) : string
    {
        return $this->generator->generate($length);
    }
}
