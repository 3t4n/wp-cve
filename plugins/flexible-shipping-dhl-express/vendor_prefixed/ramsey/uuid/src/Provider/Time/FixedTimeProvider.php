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
namespace DhlVendor\Ramsey\Uuid\Provider\Time;

use DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface;
use DhlVendor\Ramsey\Uuid\Type\Integer as IntegerObject;
use DhlVendor\Ramsey\Uuid\Type\Time;
/**
 * FixedTimeProvider uses an known time to provide the time
 *
 * This provider allows the use of a previously-generated, or known, time
 * when generating time-based UUIDs.
 */
class FixedTimeProvider implements \DhlVendor\Ramsey\Uuid\Provider\TimeProviderInterface
{
    /**
     * @var Time
     */
    private $fixedTime;
    public function __construct(\DhlVendor\Ramsey\Uuid\Type\Time $time)
    {
        $this->fixedTime = $time;
    }
    /**
     * Sets the `usec` component of the time
     *
     * @param int|string|IntegerObject $value The `usec` value to set
     */
    public function setUsec($value) : void
    {
        $this->fixedTime = new \DhlVendor\Ramsey\Uuid\Type\Time($this->fixedTime->getSeconds(), $value);
    }
    /**
     * Sets the `sec` component of the time
     *
     * @param int|string|IntegerObject $value The `sec` value to set
     */
    public function setSec($value) : void
    {
        $this->fixedTime = new \DhlVendor\Ramsey\Uuid\Type\Time($value, $this->fixedTime->getMicroseconds());
    }
    public function getTime() : \DhlVendor\Ramsey\Uuid\Type\Time
    {
        return $this->fixedTime;
    }
}
