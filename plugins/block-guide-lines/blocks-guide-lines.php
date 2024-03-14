<?php
/**
 *
 * @link https://www.marchettidesign.net
 * @since 1.0.0
 *
 * Plugin Name: Block Guide Lines
 * Plugin URI: https://www.marchettidesign.net
 * Description: This plugin add visibile guide lines to Gutenberg Blocks.
 * Author: Marchetti Design
 * Author URI: https://www.marchettidesign.net
 * Version: 1.0
 * License: GPL2+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */





  function gutenberg_boilerplate_block() {


    if(get_option('demo-radio') == 2 ) {

      wp_register_style(
          'gutenberg-style-editor',
          plugins_url( 'style/editor-labels.css', __FILE__ ),
          array( 'wp-edit-blocks' ),
          filemtime( plugin_dir_path( __FILE__ ) . 'style/editor.css' )
      );

    } else {

      wp_register_style(
          'gutenberg-style-editor',
          plugins_url( 'style/editor.css', __FILE__ ),
          array( 'wp-edit-blocks' ),
          filemtime( plugin_dir_path( __FILE__ ) . 'style/editor.css' )
      );

    }


    register_block_type( 'gutenberg-boilerplate/gutenberg-style-block', array(
        'editor_style'  => 'gutenberg-style-editor',
    ));
}
add_action( 'init', 'gutenberg_boilerplate_block' );


/* Settings */


function demo_settings(){
    add_settings_section("section", "Settings", null, "demo");
    add_settings_field("demo-radio", "Layout Lines Style", "demo_radio_display", "demo", "section");
    register_setting("section", "demo-radio");
}

function demo_radio_display(){
   ?>
        <input type="radio" name="demo-radio" value="1" <?php checked(1, get_option('demo-radio'), true); ?>> Layout With Lines <br/><br/>
        <input type="radio" name="demo-radio" value="2" <?php checked(2, get_option('demo-radio'), true); ?>> Layout With Lines + Labels
   <?php
}

add_action("admin_init", "demo_settings");

function demo_page(){
  ?>
      <div class="wrap">
         <h1>Block Guide Lines</h1>

         <form method="post" action="options.php">
            <?php
               settings_fields("section");

               do_settings_sections("demo");

               submit_button();
            ?>
         </form>
      </div>
   <?php
}

function menu_item(){
  add_submenu_page("options-general.php", "Block Guide Lines", "Block Guide Lines", "manage_options", "block-guide-lines", "demo_page");
}

add_action("admin_menu", "menu_item");


 ?>
