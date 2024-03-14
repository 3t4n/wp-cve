<?php 
/**
 * Bootstrap Blocks for WP Editor Template.
 *
 * @version 1.0.4
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2019-03-21
 * 
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function load_mod_gtb_bootstrap_page_template()
{
   GutenbergBootstrap::AddModule('page_template',array(
      'name' => 'Bootstrap page template',
      'version'=>'1.0.4'
   ));

   function init_mod_gtb_bootstrap_page_template()
   {

      include_once( dirname(__FILE__).'/class.gtbPageTemplater.php' );
      new GtbPageTemplater();

   }
   add_action('gtb_init','init_mod_gtb_bootstrap_page_template');
}

add_action('gtb_bootstrap_modules','load_mod_gtb_bootstrap_page_template');