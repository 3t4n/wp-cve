<?php
// Prevent direct file access
  if ( ! defined('WP_UNINSTALL_PLUGIN') ) 
 {

    die( 'Nice try, But not here!!!' );
	
  }

// plugin uninstall function. 
  function seo_breadcrumbs_uninstaller()
{

   $arr = array(
              'separator',
              'default',
              'home',
              'id'
              );
			 
   for( $x=0; $x<count($arr); $x++ )
   {
	   
   delete_option( 'sbc_'.$arr[$x] );
   
   }

if ( shortcode_exists('seo-breadcrumbs')) {
 remove_shortcode ('seo-breadcrumbs');
}

}

// Calling function for uninstallation.
 seo_breadcrumbs_uninstaller();
?>
 