<?php
 /*
 Plugin Name: Text Selection Color
 Version: 1.6
 Plugin URI: http://nazmurrahman.com/text-selection-color-wordpress-plugin/
 Author: Nazmur Rahman
 Author URI: http://nazmurrahman.com/
 Description: Change the text selection color easily in the website
 */

 global $wp_version;
 $exit_msg=__('Text Selection Color requires WordPress 3.0 or newer. <a href="http://codex.wordpress.org/Upgrading_WordPress">Please update!</a>','text-selection-color');
 if (version_compare($wp_version,"3.0","<"))
 {
     exit ($exit_msg);
 }

 //If the WordPress version is greater than or equal to 3.5, then load the new WordPress color picker.

    if ( 3.5 <= $wp_version ){

        add_action( 'admin_enqueue_scripts', 'tsc_enqueue_color_picker' );

    }

    //If the WordPress version is less than 3.5 load the older farbtasic color picker.

    else {

         add_action( 'admin_enqueue_scripts', 'tsc_enqueue_farbtastic_color_picker' );

    }

    function tsc_action_init(){
    // Localization
    load_plugin_textdomain('text-selection-color', false, basename( dirname( __FILE__ ) ) . '/languages' );
    }

    // Add actions
add_action('init', 'tsc_action_init');

function tsc_enqueue_color_picker() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', plugins_url('text-selection-color.js', __FILE__ ), array( 'wp-color-picker' ));
}

function tsc_enqueue_farbtastic_color_picker() {
     wp_enqueue_style( 'farbtastic' );
     wp_enqueue_script( 'farbtastic' );
    wp_enqueue_script( 'my-script-handle', plugins_url('text-selection-color.js', __FILE__ ), array( 'farbtastic' ));
}

add_action( 'wp_enqueue_scripts', 'tsc_enqueue_styles' );

function tsc_enqueue_styles(){
$style_html = '<style type="text/css">
::selection{
color: '.get_option('text-color').' !important;
background-color: '.get_option('text-bg-color').' !important;
}
::-moz-selection{
color: '.get_option('text-color').' !important;
background-color: '.get_option('text-bg-color').' !important;
}
</style>';
echo $style_html;
}

add_action('admin_menu', 'tsc_plugin_settings');

function tsc_plugin_settings() {

    //add_menu_page('Text Selection Color Settings', 'Text Selection Color', , 'tsc_settings', 'tsc_display_settings');
add_submenu_page('options-general.php','Text Selection Color Settings','Text Selection Color','administrator','tsc_settings','tsc_display_settings');

}

function tsc_display_settings() {
$html = '<div class="wrap"><form action="options.php" method="post" name="options">
<h2>'.__('Text Selection Color Settings','text-selection-color').'</h2>
' . wp_nonce_field('update-options') . '
<table class="form-table" width="100%" cellpadding="10">
<tbody>
<tr>
<td scope="row" align="left" style="width: 13%;">
<label>'.__('Text Color','text-selection-color').'</label>
</td>
<td>
<input type="text" value="'.get_option('text-color').'" class="text-color" name="text-color" data-default-color="#fff" style="background-color: '.get_option('text-color').'"/>
<div id="colorpicker"></div>
</td>
</tr>
<tr>
<td scope="row" align="left" style="width: 13%;">
 <label>'.__('Text Background Color','text-selection-color').'</label>
</td>
<td>
<input type="text" value="'.get_option('text-bg-color').'" class="text-bg-color" name="text-bg-color" data-default-color="#0982fd" style="background-color: '.get_option('text-bg-color').'"/>
<div id="colorpicker2"></div>
</td>
</tr>
</tbody>
</table>
<h3>'.__('Preview','text-selection-color').'</h3>
<p><span class="preview-text" style="font-size: 16px; background-color: '.get_option('text-bg-color').'; color: '.get_option('text-color').';">Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</span></p>
<input type="hidden" name="action" value="update" />

 <input type="hidden" name="page_options" value="text-color,text-bg-color" />

 <input type="submit" name="Submit" value="'.__('Save','text-selection-color').'" class="button button-primary"/></form>

</div>';

echo $html;

}



?>