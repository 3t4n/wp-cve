<?php

include_once('admin/class-emma-settings.php');
include_once('widget/class-emma-widget.php');
include_once('shortcode/class-emma-shortcode.php');
include_once('class-emma-api.php');
include_once('class-emma-form.php');

/**
 * Main Class for the Emma Emarketing Plugin
 *
 * long desc
 * @package Emma_Emarketing
 * @author ah so
 * @version 1.0
 * @abstract
 * @copyright 2012
 */
class Emma_Emarketing {


    /*
     * the constructor
	 * Fired during plugins_loaded (very very early),
	 * only actions and filters,
	 *
	 */
    function __construct() {

        $emma_settings = new Emma_Settings();

        // Add shortcode support for widgets
        add_filter('widget_text', 'do_shortcode');


    }



} // end Class Emma_Emarketing


