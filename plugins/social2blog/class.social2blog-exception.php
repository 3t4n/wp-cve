<?php
if( !defined( 'ABSPATH' ) ) exit;
/**
 * Social2blog_Exception  custom exception class
 */
class Social2blog_Exception extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null) {
    	Social2blog_Log::error(": \n[".__CLASS__."]: {$message}\n");
        parent::__construct($message, $code, $previous);
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

   
}