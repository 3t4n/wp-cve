<?php
/**
 * Max Execution Timeout Handler
 */

namespace FDSUS\Lib;

class TimeoutHandler
{
    /** @var int sets seconds before timeout value */
    private $buffer;

    /** @var int  */
    private $maxExecutionTime;

    /** @var int  */
    private $start;

    /**
     * TimeoutHandler constructor
     *
     * @param int $buffer sets seconds before timeout value
     */
    public function __construct($buffer = 5)
    {
        $this->buffer = $buffer;
        $this->maxExecutionTime = (int)ini_get('max_execution_time');
        $this->start = (int)current_time('timestamp');
    }

    /**
     * Is the timeout close (i.e. within the buffer time)
     *
     * @return bool
     */
    public function isClose()
    {
        if (empty($this->maxExecutionTime)) {
            return false;
        }
        return $this->maxExecutionTime - $this->getRuntime() <= $this->buffer;
    }

    /**
     * Get current runtime in seconds
     *
     * @return int seconds since the start time
     */
    public function getRuntime()
    {
        return current_time('timestamp') - $this->start;
    }
}