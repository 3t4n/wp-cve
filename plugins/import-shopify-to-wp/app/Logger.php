<?php

namespace S2WPImporter;

class Logger
{
    /**
     * @var bool
     */
    private $enabled;

    /**
     * @var array
     */
    private $logs = [];

    public function __construct($enabled = true)
    {
        $this->enabled = $enabled;
    }

    public function add($message)
    {
        if ($this->enabled) {
            $this->logs[] = $message;
        }
    }

    public function getAll()
    {
        return $this->logs;
    }
}
