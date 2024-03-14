<?php
/**
 * @package: Remove_Footer_Links
 * @author: plugindeveloper
 * @version: 1.0.0
 * @author_uri: https://profiles.wordpress.org/plugindeveloper/
 * @since 1.0.0
 */

 /**
  *
  * @since 1.0.0
  *
  * @param string $file_path, path from the plugin
  * @return string full path of file inside plugin
  *
  */
 if( !function_exists('remove_footer_links_directory') ){

     function remove_footer_links_directory( $file_path ){

         $absolute_file_path = wp_normalize_path(REMOVE_FOOTER_LINKS_PATH.'/'.$file_path);
         return wp_normalize_path( $absolute_file_path );

     }

 }

 /**
  *
  * @since 1.0.0
  *
  * @param string $file_path, path from the plugin
  * @return string full path of file inside plugin
  *
  */
if(!function_exists('remove_footer_links_assets_url')){

   function remove_footer_links_assets_url( $file_path='' ){


     $absolute_file_path = REMOVE_FOOTER_LINKS_URL.'assets/'.$file_path;
     return esc_url_raw( $absolute_file_path );
  }

}

 /**
  *
  * @since 1.0.0
  *
  * @param array $data, array value to display on debugging
  * @return boolean $die, Boolean value to die entire website for debugging
  *
  */
 if( !function_exists('remove_footer_links_dump') ){

     function remove_footer_links_dump( $data, $die=true ){

       echo '<pre>';
       print_r($data);
       echo '</pre>';

       if($die){
         die();
       }

     }

 }
