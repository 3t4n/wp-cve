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
namespace WPPayVendor\Monolog\Handler;

use WPPayVendor\Monolog\Logger;
use WPPayVendor\Psr\Log\LogLevel;
/**
 * Blackhole
 *
 * Any record it can handle will be thrown away. This can be used
 * to put on top of an existing stack to override it temporarily.
 *
 * @author Jordi Boggiano <j.boggiano@seld.be>
 *
 * @phpstan-import-type Level from \Monolog\Logger
 * @phpstan-import-type LevelName from \Monolog\Logger
 */
class NullHandler extends \WPPayVendor\Monolog\Handler\Handler
{
    /**
     * @var int
     */
    private $level;
    /**
     * @param string|int $level The minimum logging level at which this handler will be triggered
     *
     * @phpstan-param Level|LevelName|LogLevel::* $level
     */
    public function __construct($level = \WPPayVendor\Monolog\Logger::DEBUG)
    {
        $this->level = \WPPayVendor\Monolog\Logger::toMonologLevel($level);
    }
    /**
     * {@inheritDoc}
     */
    public function isHandling(array $record) : bool
    {
        return $record['level'] >= $this->level;
    }
    /**
     * {@inheritDoc}
     */
    public function handle(array $record) : bool
    {
        return $record['level'] >= $this->level;
    }
}
