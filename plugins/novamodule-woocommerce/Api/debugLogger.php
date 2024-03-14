<?php

/**
 * Class NovaLogger file
 */
defined('ABSPATH') || exit;

class NovaLogger {

    public $filename;

    function __construct($filename = "novalogger-logfile.log") {
        $this->filename = $filename;
    }

    /**
     * Debug Messages
     * @param type $message
     * @return type
     */
    public function debugLogger($message) {
        return;
        $dir = dirname(__FILE__) . "/logs/" . $this->filename;
        $file = fopen($dir, "a+");
        fwrite($file, $message);
        fwrite($file, "\n\n");
        fclose($file);
    }

}
