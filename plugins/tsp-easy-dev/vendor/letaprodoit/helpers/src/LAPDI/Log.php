<?php
/**
 * Log Class
 *
 * @package		LetAProDoIT.Helpers
 * @filename	Log.php
 * @version		2.0.0
 * @author		Sharron Denice, Let A Pro Do IT! (www.letaprodoit.com)
 * @copyright	Copyright 2016 Let A Pro Do IT! (www.letaprodoit.com). All rights reserved
 * @license		APACHE v2.0 (http://www.apache.org/licenses/LICENSE-2.0)
 * @brief		Global functions used by various services
 *
 */	
class LAPDI_Log
{
    public static function info($msg)
    {
        if (file_exists(LAPDI_Settings::$file_debug))
        {
            file_put_contents(LAPDI_Settings::$file_debug, $msg."\n", FILE_APPEND);
        }
        else
        {
            file_put_contents(LAPDI_Settings::$file_debug, $msg."\n");
        }
    }

    public static function write($msg)
    {
        echo $msg."<br>\n";
    }
}

/**
 * TSP_Log
 *
 * @since 1.0.0
 *
 * @deprecated 2.0.0 Please use LAPDI_Log instead
 *
 * @return void
 *
 */
class TSP_Log extends LAPDI_Log
{

}// end class