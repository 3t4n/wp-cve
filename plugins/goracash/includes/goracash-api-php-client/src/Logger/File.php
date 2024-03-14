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

class File extends Primary
{
    /**
     * @var string|resource $file Where logs are written
     */
    private $file;
    /**
     * @var integer $mode The mode to use if the log file needs to be created
     */
    private $mode = 0640;
    /**
     * @var boolean $lock If a lock should be attempted before writing to the log
     */
    private $lock = false;

    /**
     * @var integer $trappedErrorNumber Trapped error number
     */
    private $trappedErrorNumber;
    /**
     * @var string $trappedErrorString Trapped error string
     */
    private $trappedErrorString;

    /**
     * {@inheritdoc}
     */
    public function __construct(Client $client)
    {
        parent::__construct($client);

        $file = $client->getClassConfig('Goracash\Logger\File', 'file');
        if (!is_string($file) && !is_resource($file)) {
            throw new Exception(
                'File logger requires a filename or a valid file pointer'
            );
        }

        $mode = $client->getClassConfig('Goracash\Logger\File', 'mode');
        if (!$mode) {
            $this->mode = $mode;
        }

        $this->lock = (bool) $client->getClassConfig('Goracash\Logger\File', 'lock');
        $this->file = $file;
    }

    /**
     * {@inheritdoc}
     */
    protected function write($message)
    {
        if (is_string($this->file)) {
            $this->open();
        } elseif (!is_resource($this->file)) {
            throw new Exception('File pointer is no longer available');
        }

        if ($this->lock) {
            flock($this->file, LOCK_EX);
        }

        fwrite($this->file, (string) $message);

        if ($this->lock) {
            flock($this->file, LOCK_UN);
        }
    }

    /**
     * Opens the log for writing.
     *
     * @return resource
     * @throws Exception
     */
    private function open()
    {
        // Used for trapping `fopen()` errors.
        $this->trappedErrorNumber = null;
        $this->trappedErrorString = null;

        set_error_handler(
            function($errorNo, $errorString) {
                $this->trapError($errorNo, $errorString);
            }
        );

        $needsChmod = !file_exists($this->file);
        $file = fopen($this->file, 'a');

        restore_error_handler();

        // Handles trapped `fopen()` errors.
        if ($this->trappedErrorNumber) {
            throw new Exception(
                sprintf(
                    "Logger Error: '%s'",
                    $this->trappedErrorString
                ),
                $this->trappedErrorNumber
            );
        }

        if ($needsChmod) {
            @chmod($this->file, $this->mode & ~umask());
        }

        return $this->file = $file;
    }

    /**
     * Closes the log stream resource.
     */
    private function close()
    {
        if (is_resource($this->file)) {
            fclose($this->file);
        }
    }

    /**
     * Traps `fopen()` errors.
     *
     * @param integer $errno The error number
     * @param string $errstr The error string
     */
    private function trapError($errno, $errstr)
    {
        $this->trappedErrorNumber = $errno;
        $this->trappedErrorString = $errstr;
    }

    public function __destruct()
    {
        $this->close();
    }

}