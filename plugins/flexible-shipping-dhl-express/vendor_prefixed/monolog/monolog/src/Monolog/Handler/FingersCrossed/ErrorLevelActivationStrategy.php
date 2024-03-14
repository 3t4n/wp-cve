<?php

declare (strict_types=1);
/*
 * This file is part of the Monolog package.
 *
 * (c) Jordi Boggiano <j.boggiano@seld.be>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace DhlVendor\Monolog\Handler\FingersCrossed;

use DhlVendor\Monolog\Logger;
use DhlVendor\Psr\Log\LogLevel;
/**
 * Error level based activation strategy.
 *
 * @author Johannes M. Schmitt <schmittjoh@gmail.com>
 *
 * @phpstan-import-type Level from \Monolog\Logger
 * @phpstan-import-type LevelName from \Monolog\Logger
 */
class ErrorLevelActivationStrategy implements \DhlVendor\Monolog\Handler\FingersCrossed\ActivationStrategyInterface
{
    /**
     * @var Level
     */
    private $actionLevel;
    /**
     * @param int|string $actionLevel Level or name or value
     *
     * @phpstan-param Level|LevelName|LogLevel::* $actionLevel
     */
    public function __construct($actionLevel)
    {
        $this->actionLevel = \DhlVendor\Monolog\Logger::toMonologLevel($actionLevel);
    }
    public function isHandlerActivated(array $record) : bool
    {
        return $record['level'] >= $this->actionLevel;
    }
}
