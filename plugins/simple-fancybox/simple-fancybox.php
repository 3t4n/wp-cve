<?php

  /*
    Plugin Name: Simple Fancybox
    Plugin URI: http://wordpress.transformnews.com
    Description: Simple Fancybox for WordPress image gallery. Works for both, shortcode gallery in classic editor and Gutengerg blocks
    Version: 1.0
    Author: m.r.d.a
    License: GPLv2 or later
  */


defined('ABSPATH') or die("Cannot access pages directly.");
define( 'FANCY_VER', '1.0' );


class fanyboxWP

{


    public function __construct() {

      add_action( 'wp_enqueue_scripts', array( $this, 'fancybox_scripts' ) );
      add_filter( 'wp_get_attachment_link', array( $this, 'modify_attachment_link') );
      add_filter( 'the_content', array( $this, 'modify_attachment_link_guttenberg') );

    }


    public function modify_attachment_link ($link) {

      global $post;	
      return str_replace('<a href=', '<a data-fancybox="gallery" rel="group-'.$post->ID.'" href=', $link);

    }


    public function modify_attachment_link_guttenberg ($content) {   

      global $post;
      $pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>(.*?)<\/a>/i";
      $replacement = '<a$1href=$2$3.$4$5  data-fancybox="gallery" rel="group-'.$post->ID.'"$6>$7</a>';
      $content = preg_replace($pattern, $replacement, $content);
      return $content;

    }


    public function fancybox_scripts () {

      wp_enqueue_style( 'fancybox', plugins_url( 'css/jquery.fancybox.min.css', __FILE__ ), array(), FANCY_VER );
      wp_register_script('fancybox', plugins_url( 'js/jquery.fancybox.min.js', __FILE__ ), array('jquery'), FANCY_VER, true);
      wp_enqueue_script( 'fancybox' );
  //  to use it inside functions.php 
  //  wp_enqueue_script( 'fancybox', get_template_directory_uri() . '/js/jquery.fancybox.min.js', array('jquery'), FANCY_VER, true );

    }


}

new fanyboxWP();

?>