<?php
   /*
   Plugin Name: jClocksGMT - World Clocks for Wordpress
   Plugin URI: http://kingkode.com/jclocksgmt-wp
   Description: Analog and digital clock(s) plugin based on GMT offsets.
   Version: 1.0.2
   Author: KingKode
   Author URI: http://kingkode.com/
   License: GPL2
      __   _             __             __   
     / /__(_)___  ____ _/ /______  ____/ /__ 
    / //_/ / __ \/ __ `/ //_/ __ \/ __  / _ \
   / ,< / / / / / /_/ / ,< / /_/ / /_/ /  __/
  /_/|_/_/_/ /_/\__, /_/|_|\____/\__,_/\___/ 
               /____/                        
*/


   // Check to see if assets are needed and include them if so
   function pw_check_for_shortcode($posts) {

       if ( empty($posts) )
           return $posts;
    
       // False because we have to search through the posts first
       $found = false;

    
       // Search through each post
       foreach ($posts as $post) {

           // Check the post content for the shortcode
           if ( stripos($post->post_content, '[' . 'jclocksgmt') ) {
               // We have found a post with the shortcode
               $found = true;
               // Stop the search
               break;
           }
       }
    
       if ($found){
           // Declare scripts with jQuery dependency, and the stylesheet with no dependencies
           wp_enqueue_script( 'jquery.rotate', plugins_url( 'js/jquery.rotate.js' , __FILE__ ), array('jquery') );
           wp_enqueue_script( 'jclocksgmt', plugins_url( 'js/jClocksGMT.js' , __FILE__ ), array('jquery') ); 
           wp_enqueue_style( 'jclocksgmt', plugins_url( 'css/jClocksGMT.css' , __FILE__ ) );
       }

       return $posts;
   }

   // Perform the check when the_posts() function is called
   add_action('the_posts', 'pw_check_for_shortcode');

   add_shortcode('jclocksgmt', 'wps_jclocksgmt');

   function wps_jclocksgmt($atts) {

      $atts = shortcode_atts( array(
            'title' => 'Greenwich, England',
            'offset' => '0',
            'dst' => true,
            'digital' => true,
            'analog' => true,
            'timeformat' => 'hh:mm A',
            'date' => false,
            'dateformat' => 'MM/DD/YYYY',
            'angleSec' => 0,
            'angleMin' => 0,
            'angleHour' => 0,
            'skin' => 1,
            'imgpath' => plugin_dir_url( __FILE__ )
         ), $atts, 'jclocksgmt' );

      $uid = uniqid();

      $markup = '<' . 'div id="clock_' . $uid . '" class="jclockgmt" '.'>' . '<' . '/div' . '>';

      $initalize = '<' . 'script' . '>jQuery(document).ready(function(){jQuery("#clock_' . $uid . '").jClocksGMT(' .  json_encode($atts) . ');' . '});<' . '/script' . '>';

      return  $markup . $initalize;
   }

?>