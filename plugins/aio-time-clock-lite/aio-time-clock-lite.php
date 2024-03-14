<?php
/**
 * Plugin Name: All in One Time Clock Lite - Tracking Employee Time Has Never Been Easier
 * Plugin URI:  https://codebangers.com/product/all-in-one-time-clock-lite/
 * Description: Employees can easily clock in and out.  Managers can run reports, keep track of employees and their time.
 * Author:      Codebangers
 * Author URI:  https://codebangers.com
 * Version:     1.3.323
 */
class AIO_Time_Clock_Plugin_Lite
{
    static function init()
    {
        require_once("aio-time-clock-lite-actions.php");       
        $aio_tcl_actions = new AIO_Time_Clock_Lite_Actions();
        $aio_tcl_actions->setup();        
    }
}

AIO_Time_Clock_Plugin_Lite::init();