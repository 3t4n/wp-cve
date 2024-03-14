<?php
/*
Plugin Name: BMo Expo - a  Wordpress and NextGEN Gallery plugin 
Plugin URI: http://software.bmo-design.de/wordpress-plugin-bmo-exhibition.html
Description: BMo Expo is one of the best gallery and exhibition plugins for wordpress. It allows you to replace the default wordpress gallery and NextGen Gallerys with impressive gallery designs. The plugin is easy to use and configure. Slideshow, vertical scroll, lightbox and more could be used. Perfect vor photographers, artists or exhibitor. Try it out or watch the demo video.
Author: Benedikt Morschheuser
Author URI: http://bmo-design.de/
Version: 1.0.15

Copyright 2012-2013 by Benedikt Morschheuser (http://bmo-design.de/kontakt/)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

http://wordpress.org/about/gpl/
                    
#################################################################
*/ 
// Restrictions
  if(preg_match('#' . basename(__FILE__) . '#', $_SERVER['PHP_SELF'])) { die('You are not allowed to call this page directly.'); }

//###############################################################

  define('BMO_EXPO_VERSION','1.0.15');//version  
  define('BMO_EXPO_SITEBASE_URL', get_option('siteurl'));
  define('BMO_EXPO_PLUGINNAME', trim(plugin_basename(dirname(__FILE__))));
  define('BMO_EXPO_URL',      WP_PLUGIN_URL.'/'. dirname(plugin_basename(__FILE__))); // get_bloginfo('wpurl')
  define('BMO_EXPO_BASEPATH', WP_PLUGIN_DIR.'/'. dirname(plugin_basename(__FILE__)));
  define('BMO_EXPO_BASE_FILE', plugin_basename(__FILE__));
  define('BMO_EXPO_CLASSPATH', BMO_EXPO_BASEPATH.'/classes');
  define('BMO_EXPO_OPTIONS','options_bmo_expo');//name of the options
  define('BMO_EXPO_CUSTOM_THEME_URL',      WP_CONTENT_URL.'/bmo-expo-themes');
  define('BMO_EXPO_CUSTOM_THEME_BASEPATH', WP_CONTENT_DIR.'/bmo-expo-themes');

//###############################################################
	
require_once (BMO_EXPO_CLASSPATH . '/theGallery.php');
$obj_bmoExpo = new bmoExpo();

//init hooks, shortcodes etc.
if (isset($obj_bmoExpo)) {
    // Plugin installieren bei aktivate
	register_activation_hook( __FILE__,  array($obj_bmoExpo, 'BMo_Expo_activation'));
	register_deactivation_hook(__FILE__, array($obj_bmoExpo, 'BMo_Expo_deactivation'));
	//shortcodes
    add_shortcode('BMo_scrollGallery', array($obj_bmoExpo, 'BMo_Expo_ScrollGallery'));
    add_shortcode('BMo_scrollLightboxGallery', array($obj_bmoExpo, 'BMo_Expo_ScrollLightboxGallery'));
    //add css
	add_action('wp_enqueue_scripts', array($obj_bmoExpo,'BMo_Expo_enqueueScripts'));
	add_action('wp_head'   , array($obj_bmoExpo,'BMo_Expo_Head'),1);
	add_action('wp_footer' , array($obj_bmoExpo,'BMo_Expo_Foot'),200);
	
	//replace default gallery if option is activated
	if($obj_bmoExpo->BMo_Expo_ReplaceWPGallery()){
		add_filter("post_gallery", array($obj_bmoExpo, 'BMo_Expo_WPGallery'),10,2);//overwrite the code in wordpress media.php Line 690 ff
	}
	//replace old NextGen Scroll Galleries if option is activated
	if($obj_bmoExpo->BMo_Expo_ReplaceNextGENScrollGallery()){
		add_action('plugins_loaded', array($obj_bmoExpo,'BMo_Expo_ReplaceNextGENScrollGalleryShortcodes') );
	}
	
	//translation
	add_action('init' , array($obj_bmoExpo,'BMo_Expo_translation'));
}

//admin
if(is_admin()){
    require_once (BMO_EXPO_CLASSPATH . '/admin/admin.php');
    $obj_bmoExpoAdmin = new bmoExpoAdmin($obj_bmoExpo);
    
    //init hooks, shortcodes etc.
    if (isset($obj_bmoExpo)&&isset($obj_bmoExpoAdmin)) {
        //admin menu
        add_action('admin_menu' , array($obj_bmoExpoAdmin, 'BMo_Expo_admin_menu'));//add menu
		add_action('admin_init', array($obj_bmoExpoAdmin, 'BMo_Expo_admin_init'));//init settings for Admin Page
        add_filter('plugin_row_meta', array($obj_bmoExpoAdmin,'BMo_Expo_RegisterPluginLinks'), 10, 2 );
    }
}


//Deinstall (outside Class)
if ( function_exists('register_uninstall_hook') )
	register_uninstall_hook(__FILE__, 'deinstallBMo_Expo');

function deinstallBMo_Expo() {
	delete_option(BMO_EXPO_OPTIONS);
}
?>