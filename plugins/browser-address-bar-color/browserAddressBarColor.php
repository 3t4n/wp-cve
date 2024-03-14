<?php

/**
 * @package Browser Address Bar Color
*/
/*
Plugin Name: Browser Address Bar Color 
Plugin URI: https://github.com/MMBass/theme_color_plugin_for_wordpress
Description: Custom URL bar color for each page of your site. Currently only works in mobile browsers.
Author: Mendi Bass
Author URI: https://github.com/MMBass
Version: 3.3
License: GPL v2 or later
Text Domain: browser-address-bar-color 
Domain Path: /languages
*/

if ( !defined( 'ABSPATH' ) ){
    die;
} 

function babcOnActivate(){
    if(!get_option('babc_pages_list')){
        // add a new option - '$new_babc_pages_list' by default on plugin's start;
        add_option('babc_pages_list',array());
    } 
}
register_activation_hook( __FILE__, 'babcOnActivate' );

add_action("admin_menu", "babcAddMenu");

$babcPluginLocation = plugin_basename(__FILE__); 
add_filter("plugin_action_links_$babcPluginLocation", 'babcLinkToSettingsPage' );

function babcAddMenu(){
    add_submenu_page( 'themes.php', "Browser Address Bar Color", "Address Bar Color", 'manage_options', "babc-options","babcThemeColorSettingsPage", 1 );
}

function babcLinkToSettingsPage($links) { 
    $babc_settings_link = '<a href="themes.php?page=babc-options">Settings</a>'; 
    array_unshift($links, $babc_settings_link);
    return $links; 
}

function babcThemeColorSettingsPage(){
    $new_babc_pages_list = array();

    wp_enqueue_script( 'babc-script', plugins_url('babcScript.js', __FILE__ ));

    if(isset($_POST['color-posted'])){

        $babc_colors_pattern = "(#([\da-f]{3}){1,2}|(rgb|hsl)a\((\d{1,3}%?,\s?){3}(1|0?\.\d+)\)|(rgb|hsl)\(\d{1,3}%?(,\s?\d{1,3}%?){2}\))";
   
        if (isset($_POST['input-color-all']) && isset($_POST['check-color-all'])) {
          $babc_all_color = sanitize_text_field($_POST['input-color-all']);
          if( preg_match($babc_colors_pattern, $babc_all_color) === 1){
            $new_babc_pages_list = array("all" => $babc_all_color);
            update_option('babc_pages_list',$new_babc_pages_list );
          }// regex check valid color pattern
         }// set the same color for all pages
         elseif (!isset($_POST['check-color-all'])){

            if(isset($new_babc_pages_list["all"])){
               unset($new_babc_pages_list["all"]);
            }

           foreach($_POST as $key => $value){
            
             if (strpos($key, 'input-color-') !== false){
                $babc_color_from_input = sanitize_text_field($value);
                if( preg_match($babc_colors_pattern, $babc_color_from_input) === 1){

                    $babc_page_id = str_replace( "input-color-" , "" , sanitize_text_field($key));
              
                    if(isset($_POST['check-color-'.$babc_page_id])){
                    
                        $new_babc_pages_list[$babc_page_id] = $babc_color_from_input;
 
                    }
                } // regex check valid color pattern
             }
           } // loop over the post array;

           update_option('babc_pages_list',$new_babc_pages_list);
         };// check and set for specific pages 
    }

    require_once("babcSettingsPage.php");

}

function babcHeadAdd(){
    if(get_option('babc_pages_list')){

        $babc_pages_list = get_option('babc_pages_list');
        if (array_key_exists("all",$babc_pages_list )){
            if(!empty($babc_pages_list["all"])){
                ?>
                <!-- Chrome mobile, Samsung internet -->
                <meta name="theme-color" content="<?php echo $babc_pages_list["all"]; ?>">
                <?php
            }
            
        }else{
            foreach ($babc_pages_list as $page => $color) {
                if (is_page($page) || is_single($page)){
                    ?>
                    <!-- Chrome mobile, Samsung internet -->
                    <meta name="theme-color" content="<?php echo $color; ?>">
                    <?php
                };
            } //loop over $babc_pages_list, check every page and post, and call by color;
        }

    }
    
};

if(get_option('babc_pages_list')){
   add_action('wp_head','babcHeadAdd' );
}

?>