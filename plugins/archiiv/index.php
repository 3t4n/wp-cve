<?php

/*
 * Plugin Name: Archiiv
 * Plugin URI: https://wordpress.org/plugins/archiiv/
 * Author: Arcbound
 * Author URI: https://arcbound.com/
 * Description: Creates a form via shortcode that can be used on the frontend which automatically connects form submissions with a POST request to your Beehiiv account.
 * Version: 1.2
 * License: GPLv2
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 ============================================================================================================
Archiiv is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.

Archiiv is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
============================================================================================================
 */

//creates menu page
add_action('admin_menu', 'arc_create_menu_page');
function arc_create_menu_page() {
    add_menu_page(
        'Archiiv: Beehiiv Newsletter Integration',
        'Archiiv',
        'administrator',
        'archiiv',
        'arc_beehiiv_menu_page', // see function directly below this
        plugin_dir_url('archiiv') . '/archiiv/arcbound-beehive-sm.png'
    );
}

//includes the menu page settings
function arc_beehiiv_menu_page() {
    include('menu-page-html.php');
}



/* ------------------------------------------- * 
* Setting Registration 
* ------------------------------------------- */ 
add_action('admin_init', 'arc_register_settings');
function arc_register_settings(){


    add_settings_section(
        'arc_archiiv_settings', // section ID
        '', // the title to display on the settings page
        '', // callback –> output the description or HTML that is output
        'archiiv', // the page where it is supposed to show up. The settings pages (like ‘general’, ‘media’, ‘permalink’, etc) can be used here or predefined admin pages
    );

    // setting for API key
    add_settings_field(
        'arc_api_key', // setting ID
        'Beehiiv API Key', // setting title
        'archiiv_display_api_key', // the callback to output the input (checkbox, textarea, etc.)
        'archiiv', // the page where it should show up
        'arc_archiiv_settings', // section ID the field will belong to
        '', // array of args for the callback
    );
    register_setting(
        'archiiv', // page where it should show up
        'arc_api_key', // setting ID
        array(
            'string', //type – could be: 'string', 'boolean', 'integer', 'number', 'array', and 'object'
            '', //description of the data attached to this setting.
            'archiiv_sanitize', // A callback function that sanitizes the option's value
            false, //show_in_rest: Whether data associated with this setting should be included in the REST API.
            null, //default option if calling this setting
        ) 
    );


    // setting for publication ID
    add_settings_field(
        'arc_publication_id', // setting ID
        'Beehiiv Publication ID', // setting title
        'archiiv_display_publication_id', // the callback to output the input (checkbox, textarea, etc.)
        'archiiv', // the page where it should show up
        'arc_archiiv_settings', // section ID the field will belong to
        '', // array of args for the callback
    );
    register_setting(
        'archiiv', // page where it should show up
        'arc_publication_id', // setting ID & option name
        array(
            'string', //type – could be: 'string', 'boolean', 'integer', 'number', 'array', and 'object'
            '', //description of the data attached to this setting.
            'archiiv_sanitize', // A callback function that sanitizes the option's value
            false, //show_in_rest: Whether data associated with this setting should be included in the REST API.
            null, //default option if calling this setting
        ) 
    );


    // setting for publication ID
    add_settings_field(
        'redirect_page', // setting ID
        'Redirect Slug', // setting title
        'archiiv_display_redirect_slug', // the callback to output the input (checkbox, textarea, etc.)
        'archiiv', // the page where it should show up
        'arc_archiiv_settings', // section ID the field will belong to
        '', // array of args for the callback
    );
    register_setting(
        'archiiv', // page where it should show up
        'redirect_page', // setting ID & option name
        array(
            'string', //type – could be: 'string', 'boolean', 'integer', 'number', 'array', and 'object'
            '', //description of the data attached to this setting.
            'archiiv_sanitize', // A callback function that sanitizes the option's value
            false, //show_in_rest: Whether data associated with this setting should be included in the REST API.
            null, //default option if calling this setting
        ) 
    );


}






/* ------------------------------------------- * 
* Setting Output Callbacks 
* ------------------------------------------- */ 
// for setting API key; setting ID is: arc_api_key
function archiiv_display_api_key(){

    //retrieve the db setting
    $arc_options = get_option('arc_api_key');
    if($arc_options){
        $value = $arc_options;
    }else{
        $value = '';
    }
    ?>

    <?php // output html on settings page?>
    <input id="arc_apikey" name="arc_api_key" type="text" value="<?php echo esc_attr($value); ?>" >
    <?php
}

// for setting Publication ID; setting ID is: arc_publication_id
function archiiv_display_publication_id(){

    //retrieve the db setting
    $arc_options = get_option('arc_publication_id');
    if($arc_options){
        $value = $arc_options;
    }else{
        $value = '';
    }
    ?>

    <?php // output html on settings page?>
    <input id="arc_publication_id" name="arc_publication_id" type="text" value="<?= esc_attr($value); ?>" >
    <?php
}

// for setting Publication ID; setting ID is: arc_publication_id
function archiiv_display_redirect_slug(){
    //retrieve the db setting
    $arc_options = get_option('redirect_page');
    if($arc_options){
        $value = $arc_options;
    }else{
        $value = '';
    }
    ?>

    <?php // output html on settings page?>
    <label>Which on-site page should a user be sent to after a successful form fill?</label>
    <input id="redirect_page" name="redirect_page" type="text" value="<?= esc_attr($value); ?>" > <br><em>(Use beginning and ending slashes for all URL slugs. If redirecting to your homepage, simple leave one forward slash ( / ).</em>
    <?php
}





/* ------------------------------------------- * 
* Sanitizing Fiels 
* ------------------------------------------- */
function archiiv_sanitize($input){
    return sanitize_text_field($input);
}







include( plugin_dir_path( __FILE__ ) . 'beehiiv-int-form.php');


// makes the logo centered on the admin page
add_action('admin_head', 'archiiv_update_archiive_plugin');
function archiiv_update_archiive_plugin() {
    ?>
    <style type="text/css">
        #adminmenu #toplevel_page_archiiv .wp-menu-image img{padding-top: 4px;}
    </style>
    <?php 
}


 
?>