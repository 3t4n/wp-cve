<?php
/*
Plugin Name: Which Template Am I
Plugin URI: http://wpbeaches.com
Description: Show Which Template WordPress is Using
Author: Neil Gee
Version: 1.2.0
Author URI:http://wpbeaches.com
License:           GPL-2.0+
License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
*/


// If called direct, refuse
if ( ! defined( 'ABSPATH' ) ) {
    die;
}


function show_wordpress_template() {
	global $template;
if( is_user_logged_in() && current_user_can('administrator') ) {
	?>
    <div class="wtai">
    <a class="ab-item" aria-haspopup="true" href="https://developer.wordpress.org/themes/basics/template-hierarchy/" target="_blank">
      <span class="ab-icon"></span><span class="screen-reader-text">WordPress Template Hierarchy</span>
    </a>  <?php print_r( $template );?>
  	</div>
  	<?php
  }
}

add_action( 'wp_footer', 'show_wordpress_template' );


//Adding CSS inline style to an existing CSS stylesheet
function wtai_add_inline_css() {
        $wtai_custom_css = "
        .wtai {
          background:rgba(35,40,45,.9);
          color:#fff;
          font-size:15px;
          overflow:auto;
          position:fixed;
          bottom:0;
          width:100%;
          text-align:center;
          margin:0;
          padding:0;
          line-height:2;
          font-family: -apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Oxygen-Sans,Ubuntu,Cantarell,'Helvetica Neue',sans-serif;
          z-index: 9999;
        }
        .wtai .ab-icon {
          position: relative;
          float: left;
          font: 400 20px/1 dashicons;
          speak: none;
          padding: 4px 0;
          -webkit-font-smoothing: antialiased;
          -moz-osx-font-smoothing: grayscale;
          background-image: none!important;
          margin-right: 6px;
      }
        .wtai .ab-item .ab-icon:before {
          content: '\\f120';
          top: 2px;
          left:5px;
          color: rgba(240,245,250,.6);
          position: relative;
      }";
  //Add the above custom CSS via wp_add_inline_style
  wp_add_inline_style( 'admin-bar', $wtai_custom_css );
}
add_action( 'wp_enqueue_scripts', 'wtai_add_inline_css' );
