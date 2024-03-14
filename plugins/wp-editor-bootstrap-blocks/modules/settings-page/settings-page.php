<?php 
/**
 * Bootstrap Blocks for WP Editor Settings page.
 *
 * @version 1.2.0
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2019-02-14
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function load_mod_gtb_bootstrap_settings_page()
{
   if (is_admin()) :
      GutenbergBootstrap::AddModule('settings_page',array(
         'name' => 'Settings page',
         'version'=>'1.2.1'
      ));

      function init_mod_gtb_bootstrap_settings_page()
      {

         include_once( dirname(__FILE__).'/class.gtbBootstrapSettingsPage.php' );
         new GtbBootstrapSettingsPage();
      }
      add_action('gtb_init','init_mod_gtb_bootstrap_settings_page');
   endif;
}

add_action('gtb_bootstrap_modules','load_mod_gtb_bootstrap_settings_page');