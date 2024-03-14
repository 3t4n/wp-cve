<?php

/*
Plugin Name: bbP last post
Plugin URI: http://www.rewweb.co.uk/bbp-last-post-plugin/
Description: This Plugin changes the 'freshness ' (eg 4 hours ago) that bbPress displays on topic and forum lists to the date of the last post to that forum or topic (eg 17th January 2014 at 5.15pm)
Version: 1.7
Author: Robin Wilson
Text Domain: bbp-last-post
Author URI: http://www.rewweb.co.uk
License: GPL2
*/
/*  Copyright 2013  PLUGIN_AUTHOR_NAME  (email : wilsonrobine@btinternet.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

	

/*******************************************
* global variables
*******************************************/

// load the plugin options
$rlp_options = get_option( 'rlp_settings' );

if(!defined('RLP_PLUGIN_DIR'))
	define('RLP_PLUGIN_DIR', dirname(__FILE__));

function bbp_last_post_init() {
  load_plugin_textdomain('bbp-last-post', false, basename( dirname( __FILE__ ) ) . '/lang' );
}
add_action('plugins_loaded', 'bbp_last_post_init');


/*******************************************
* file includes
*******************************************/
include(RLP_PLUGIN_DIR . '/includes/settings.php');
include(RLP_PLUGIN_DIR . '/includes/functions.php');
include(RLP_PLUGIN_DIR . '/includes/lp-widgets.php');

