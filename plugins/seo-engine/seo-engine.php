<?php
/*
Plugin Name: SEO Engine
Plugin URI: https://meowapps.com
Description: SEO for AI-Driven Search. As AI takes the lead in search technology, SEO Engine helps you adapt to this evolution by removing traditional SEO hassles and empowering you to create outstanding content for the AI era. Keep it simple stupid, for the win!
Version: 0.2.1
Author: Jordy Meow
Author URI: https://jordymeow.com
Text Domain: seo-engine

Dual licensed under the MIT and GPL licenses:
http://www.opensource.org/licenses/mit-license.php
http://www.gnu.org/licenses/gpl.html
*/

define( 'SEOENGINE_VERSION', '0.2.1' );
define( 'SEOENGINE_PREFIX', 'mwseo' );
define( 'SEOENGINE_DOMAIN', 'seo-engine' );
define( 'SEOENGINE_ENTRY', __FILE__ );
define( 'SEOENGINE_PATH', dirname( __FILE__ ) );
define( 'SEOENGINE_URL', plugin_dir_url( __FILE__ ) );

require_once( 'classes/init.php' );

?>
