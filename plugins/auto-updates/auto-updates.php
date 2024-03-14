<?php
/*
* Plugin Name: Auto Updates
* Description: Let WordPress to automatically update his core, plugins and themes - silently in the background.
* Version: 1.2
* Author: Michal Nov&aacute;k
* Author URI: https://www.novami.cz
* License: GPL3
* Text Domain: auto-updates
*/

add_filter( 'allow_major_auto_core_updates', '__return_true' );
add_filter( 'auto_update_plugin', '__return_true' );
add_filter( 'auto_update_theme', '__return_true' );