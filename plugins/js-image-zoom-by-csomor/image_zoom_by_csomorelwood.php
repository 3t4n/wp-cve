<?php
/**
* Plugin Name: JS Image Zoom by Csömör
* Plugin URI: https://vassgergo.me
* Description: The plugin allows you to zoom on images just by adding a class for them.
* Version: 1.0
* Author: Csömör
* Author URI: https://profiles.wordpress.org/csomorelwood/
**/

function register_the_magical_styles_for_image_zoom_by_csomorelwood() {
  wp_register_style('image_zoom_css', plugins_url('assets/css/style.css',__FILE__ ));
  wp_enqueue_style('image_zoom_css');
}

add_action( 'init','register_the_magical_styles_for_image_zoom_by_csomorelwood');

function register_the_magical_scripts_for_image_zoom_by_csomorelwood() {

  wp_register_script('image_zoom_script', plugin_dir_url( __FILE__ ) . 'assets/js/image_zoom.js' );
  wp_enqueue_script( 'image_zoom_script');
 }
add_action('init', 'register_the_magical_scripts_for_image_zoom_by_csomorelwood');

function image_zoom_register_options_page() {
  add_options_page('JS Image Zoom', 'JS Image Zoom by Csömör', 'manage_options', 'image_zoom', 'image_zoom_options_page');
}
add_action('admin_menu', 'image_zoom_register_options_page');

/*
**
**  Set up Options page
**
*/
function image_zoom_options_page(){ ?>
  <div>
    <h1>JS Image Zoom by Csömör</h1>
    <h2>Thanks for downloading my plugin! :)</h2>
    <h3>If you like it, you can donate me on paypal -----> <a href="https://paypal.me/csomorelwood">Donate!</a></h3>
    

    <h3>Usage:</h3>
    <p>
      Just add the "csomor-image-zoom" class to the image wrapper, and the magic will happen automatically.
    </p>
  </div>
<?php } ?>
