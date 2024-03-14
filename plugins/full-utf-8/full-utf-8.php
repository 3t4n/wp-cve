<?php
/*
Plugin Name: Full UTF-8
Plugin URI: http://wordpress.org/extend/plugins/full-utf-8/
Description: Trustfully write anything in your language. Stop worrying about truncated content.
Version: 2.0.1
Author: Andrea Ercolino
Author URI: http://andowebsit.es/blog/noteslog.com/
License: GPLv2 or later
*/

require_once( 'FullUtf8.php' );

//------------------------------------------------------------------------------

register_activation_hook(   __FILE__, array('FullUtf8', 'on_activation') );
register_deactivation_hook( __FILE__, array('FullUtf8', 'on_deactivation') );
