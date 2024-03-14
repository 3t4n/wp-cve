<?php
/*
Plugin Name: Media Meta & Force Regenerate
Plugin URI: http://meowapps.com
Description: Adds the metadata information to the Media Library. Regenerates the metadata and thumbnails even if they exist.
Version: 0.0.3
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: media-metadata
Domain Path: /languages

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html

Originally developed for two of my websites:
- Jordy Meow (http://offbeatjapan.org)
- Haikyo (http://haikyo.org)
*/

if ( is_admin() ) {

  global $mmt_version, $mmt_core;
  $mmt_version = '0.0.3';

  // Core
  require( 'core.php' );
	$mmt_core = new Meow_MMT_Core();
}
