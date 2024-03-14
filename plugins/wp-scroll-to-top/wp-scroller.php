<?php
/*  
Plugin Name: Scroll To Top
Description: Scrolls the page to top. 
Version: 2.0
Author: umarbajwa
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Donate link: http://web-settler.com/
*/

 

define( 'WSTT_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

define( 'WSTT_PLUGIN_URL', plugins_url( '',plugin_basename( __FILE__ ) ) );

define( 'WSTT_PLUGIN_DIR_NAME', dirname( plugin_basename( __FILE__ ) ) );



function  html_structure(){
 

  $scr_text = get_option('scr_text');
  $scr_font_size = get_option('scr_font_size');
  $scr_width = get_option('scr_width');
  $scr_height = get_option('scr_height');
  $scr_border_radius =get_option('scr_border_radius');
  $scr_color = get_option('scr_color');
  $scr_background_color = get_option('scr_background_color');
  $scr_position = get_option('scr_position');
  $scr_pre_icons =get_option('scr_pre_icons');
  $activate_pre_icon = get_option('activate_pre_icon');
  $activate_text_icon = get_option('activate_text_icon');
  ?>

     <div id='scr_wrapper'>

      <div id="pre_des_icons" style=""><a href="#top"><img src="<?php echo get_option('scr_pre_icons');?>"
      style="width:<?php echo $scr_width;?>; 
  height:<?php echo $scr_height;?>;"
        ></a></div>
      

     <div class='scr_container' style="" >
     
     <a href='#' onclick='return false;'><span class='scr_icon'><b><?php echo $scr_text; ?></b></span></a>
     </div>
     
	</div>
  <?php
}

add_action('wp_footer','html_structure');

//Adding scripts
add_action( 'init', 'register_plugin_styles' );

function register_plugin_styles() {
  wp_register_style( 'scroller-plugin', plugins_url('wp-scroll-to-top-premium/scr_style2.css'));
	wp_enqueue_style( 'scroller-plugin' );
}

 function wstt_scripts() {
  wp_enqueue_script('scroller-js',plugins_url('js/scr_js.js',__FILE__),array( 'jquery' ));
}
add_action( 'init', 'wstt_scripts' );


function wstt_admin_scripts_add() {

  $screen = get_current_screen();
  if($screen->id === 'toplevel_page_scr_options_premium'){
      wp_enqueue_script('jquery' );
      wp_enqueue_style( 'wp-color-picker');
      wp_enqueue_script( 'wp-color-picker');
      wp_enqueue_script('color-picker-js',plugins_url('js/color-picker.js',__FILE__),array( 'jquery' ));
  }

}

add_action('admin_enqueue_scripts', 'wstt_admin_scripts_add');

//Done adding scripts;

add_action('admin_menu','scrp_modify_menu');
add_action('admin_init','scr_reg_function');
add_action('wp_head',"scr_add_head");

register_activation_hook(__FILE__,'scr_activate');
function scr_activate(){

  add_option('scr_text','↑ Back To Top ↑');
  add_option('scr_font_size','15px');
  add_option('scr_width','150px');
  add_option('scr_height','34px');
  add_option('scr_border_radius','3px');
  add_option('scr_color','#fff');
  add_option('scr_background_color','#cccccc');
  add_option('scr_position','90%');
  add_option('scr_pre_icons');
  add_option('activate_pre_icon');
  add_option('activate_text_icon');
  

}
function scrp_modify_menu(){
	add_menu_page(
    'Scroll To Top Settings',
    'Scroll To Top',
    'administrator',
    'scr_options_premium',
    'admin_scrp_options'
    );

  add_submenu_page( 'scr_options_premium', 'Recommendations', 'Recommendations', 'manage_options', 'wstt_recommendations', 'wstt_recommendations' );
}
function scr_add_head(){
  $scr_text = get_option('scr_text');
  $scr_font_size = get_option('scr_font_size');
  $scr_width = get_option('scr_width');
  $scr_height = get_option('scr_height');
  $scr_border_radius =get_option('scr_border_radius');
  $scr_color = get_option('scr_color');
  $scr_background_color = get_option('scr_background_color');
  $scr_position = get_option('scr_position');
  $scr_pre_icons =get_option('scr_pre_icons');
  $activate_pre_icon = get_option('activate_pre_icon');
  $activate_text_icon = get_option('activate_text_icon');

   include 'scr_style.php';
}

function scr_reg_function(){
  register_setting('scr-setting-group','scr_text');
  register_setting('scr-setting-group','scr_font_size');
  register_setting('scr-setting-group','scr_width');
  register_setting('scr-setting-group','scr_height');
  register_setting('scr-setting-group','scr_border_radius');
  register_setting('scr-setting-group','scr_color');
  register_setting('scr-setting-group','scr_background_color');
  register_setting('scr-setting-group','scr_position');
  register_setting('scr-setting-group','scr_pre_icons');
  register_setting('scr-setting-group','activate_pre_icon');
  register_setting('scr-setting-group','activate_text_icon');
}


function admin_scrp_options(){

include 'admin/wstt_ui.php';

}



function wstt_recommendations(){
  include 'wstt_recommendations.php';
}

?>