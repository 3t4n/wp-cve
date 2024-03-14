<?php
/*
Plugin Name: RSS Featured Image
Description: Add the featured image to your RSS feed (in the media:content). Works with Mailchimp (*|RSSITEM:IMAGE|*). Light and simple, no options and no clutter in your admin.
Version: 1.0.6
Author: Jordy Meow
Author URI: https://meowapps.com
Text Domain: rss-featured-image

Originally developed for two of my websites:
- Jordy Meow (https://offbeatjapan.org)
- Haikyo (https://haikyo.org)
*/

global $rfi_version;
$rfi_version = '1.0.6';

// Core
require( 'core.php' );
new Meow_RFI_Core();
