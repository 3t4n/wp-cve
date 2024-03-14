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

use DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface;
use DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface;
use DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface;
/**
 * TimeGeneratorFactory retrieves a default time generator, based on the
 * environment
 */
class TimeGeneratorFactory
{
    /**
     * @var NodeProviderInterface
     */
    private $nodeProvider;
    /**
     * @var TimeConverterInterface
     */
    private $timeConverter;
    /**
     * @var TimeProviderInterface
     */
    private $timeProvider;
    public function __construct(\DhlVendor\Ramsey\Uuid\Provider\NodeProviderInterface $nodeProvider, \DhlVendor\Ramsey\Uuid\Converter\TimeConverterInterface $timeConverter, \DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface $timeProvider)
    {
        $this->nodeProvider = $nodeProvider;
        $this->timeConverter = $timeConverter;
        $this->timeProvider = $timeProvider;
    }
    /**
     * Returns a default time generator, based on the current environment
     */
    public function getGenerator() : \DhlVendor\Ramsey\Uuid\Generator\TimeGeneratorInterface
    {
        return new \DhlVendor\Ramsey\Uuid\Generator\DefaultTimeGenerator($this->nodeProvider, $this->timeConverter, $this->timeProvider);
    }
}
