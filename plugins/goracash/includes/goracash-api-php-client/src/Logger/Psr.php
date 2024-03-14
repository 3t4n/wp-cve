<?php
/**
 * Copyright 2015 Goracash
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Goracash\Logger;

use Goracash\Client as Client;

class Psr extends Primary
{
    /**
     * @param Psr\Log\LoggerInterface $logger The PSR-3 logger
     */
    private $logger;

    /**
     * @param Goracash\Client $client           The current Goracash client
     * @param Psr\Log\LoggerInterface $logger PSR-3 logger where logging will be delegated.
     */
    public function __construct(Client $client, /*Psr\Log\LoggerInterface*/ $logger = null)
    {
        parent::__construct($client);

        if ($logger) {
            $this->setLogger($logger);
        }
    }

    /**
     * Sets the PSR-3 logger where logging will be delegated.
     *
     * NOTE: The `$logger` should technically implement
     * `Psr\Log\LoggerInterface`, but we don't explicitly require this so that
     * we can be compatible with PHP 5.2.
     *
     * @param Psr\Log\LoggerInterface $logger The PSR-3 logger
     */
    public function setLogger(/*Psr\Log\LoggerInterface*/ $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function shouldHandle($level)
    {
        return isset($this->logger) && parent::shouldHandle($level);
    }

    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = array())
    {
        if (!$this->shouldHandle($level)) {
            return false;
        }

        if ($context) {
            $this->reverseJsonInContext($context);
        }

        $levelName = is_int($level) ? array_search($level, self::$levels) : $level;
        $this->logger->log($levelName, $message, $context);
    }

    /**
     * {@inheritdoc}
     */
    protected function write($message, array $context = array())
    {
    }
}