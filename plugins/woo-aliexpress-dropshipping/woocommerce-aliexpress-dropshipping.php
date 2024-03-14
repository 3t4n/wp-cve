<?php
/*
   Plugin Name: AliExpress, eBay, Amazon and Etsy dropshipping by theShark
   Plugin URI: http://wordpress.org/extend/plugins/woocommerce-aliexpress-dropshipping/
   Version: 2.1.2
   Author: wooproductimporter
   Description: AliExpress, eBay, Amazon and Etsy dropshipping by theShark
   Text Domain: woocommerce-aliexpress-dropshipping
   License: GPLv3
  */

/*
    "WordPress Plugin Template" Copyright (C) 2018 Michael Simpson  (email : michael.d.simpson@gmail.com)

    This following part of this file is part of WordPress Plugin Template for WordPress.

    WordPress Plugin Template is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WordPress Plugin Template is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see http://www.gnu.org/licenses/gpl-3.0.html
*/

$WoocommerceAliexpressDropshipping_minimalRequiredPhpVersion = '5.0';


/**
 * Check the PHP version and give a useful error message if the user's version is less than the required version
 * @return boolean true if version check passed. If false, triggers an error which WP will handle, by displaying
 * an error message on the Admin page
 */
function WoocommerceAliexpressDropshipping_noticePhpVersionWrong() {
    global $WoocommerceAliexpressDropshipping_minimalRequiredPhpVersion;
    echo esc_html('<div class="updated fade">' .
      __('Error: plugin "woocommerce aliexpress dropshipping" requires a newer version of PHP to be running.',  'woocommerce-aliexpress-dropshipping').
            '<br/>' . __('Minimal version of PHP required: ', 'woocommerce-aliexpress-dropshipping') . '<strong>' . $WoocommerceAliexpressDropshipping_minimalRequiredPhpVersion . '</strong>' .
            '<br/>' . __('Your server\'s PHP version: ', 'woocommerce-aliexpress-dropshipping') . '<strong>' . phpversion() . '</strong>' .
         '</div>');
}


function WoocommerceAliexpressDropshipping_PhpVersionCheck() {
    global $WoocommerceAliexpressDropshipping_minimalRequiredPhpVersion;
    if (version_compare(phpversion(), $WoocommerceAliexpressDropshipping_minimalRequiredPhpVersion) < 0) {
        add_action('admin_notices', 'WoocommerceAliexpressDropshipping_noticePhpVersionWrong');
        return false;
    }
    return true;
}


/**
 * Initialize internationalization (i18n) for this plugin.
 * References:
 *      http://codex.wordpress.org/I18n_for_WordPress_Developers
 *      http://www.wdmac.com/how-to-create-a-po-language-translation#more-631
 * @return void
 */
function WoocommerceAliexpressDropshipping_i18n_init() {
    $pluginDir = dirname(plugin_basename(__FILE__));
    load_plugin_textdomain('woocommerce-aliexpress-dropshipping', false, $pluginDir . '/languages/');
}
 

//////////////////////////////////
// Run initialization
/////////////////////////////////

// Initialize i18n
add_action('plugins_loadedi','WoocommerceAliexpressDropshipping_i18n_init');

// Run the version check.
// If it is successful, continue with initialization for this plugin
if (WoocommerceAliexpressDropshipping_PhpVersionCheck()) {
    // Only load and run the init function if we know PHP version can parse it
    include_once('woocommerce-aliexpress-dropshipping_init.php');
    include_once('chrome-extension_init.php');

    WoocommerceAliexpressDropshipping_init(__FILE__);

    initOriginalProductUrl_alibay();
}



function initOriginalProductUrl_alibay()
{
    add_action('post_submitbox_misc_actions', 'woo_add_custom_general_fields_originalProductUrl_alibay', 20);
    function woo_add_custom_general_fields_originalProductUrl_alibay()
    {
        echo ' 
        <button type="button" style="margin: 10px;
        padding: 10px;
        color: black;
        border-radius: 5px;
        background-color: #007cba; "  class="btn btn-primary" id="openOriginalProductUrl" data-target=".bd-example-modal-lg"> Open Original product url (the shark)</button>
        <div class="loader2" style="display:none"><div></div><div></div><div></div><div></div></div>';
    }
}



function my_admin_scripts_init_alibay($hook_suffix)
{




    if ('post.php' == $hook_suffix || 'post-new.php' == $hook_suffix) {

        // add_action('wp_enqueue_scripts', 'jquery_add_to_contact');
        // wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.min.js', array('jquery'), NULL, false);
        // wp_enqueue_style('bootstrapCsTOTOs', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css');
        wp_enqueue_script('originalProURL', plugin_dir_url(__FILE__) . 'js/originalUrl.js', array('jquery'), NULL, false);
        wp_localize_script(
            'originalProURL',
            'wooshark_params_alibary',
            array(
                'ajaxurl' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('ajax-nonce')
            )
        );
    }
}
add_action('admin_enqueue_scripts', 'my_admin_scripts_init_alibay');





function our_plugin_action_links_ALIBARY($links, $file) {
    static $this_plugin;

    if (!$this_plugin) {
        $this_plugin = plugin_basename(__FILE__);
    }

    // check to make sure we are on the correct plugin

    if ($file == $this_plugin) {

        // the anchor tag and href to the URL we want. For a "Settings" link, this needs to be the url of your settings page

        $settings_link = '<a style="color:red" target="_blank" href="https://sharkdropship.com/wooshark-dropshipping">Go pro</a>';

        // add the link to the list

        array_unshift($links, $settings_link);
    }

    return $links;
}

add_filter('plugin_action_links', 'our_plugin_action_links_ALIBARY', 10, 2);



if ( ! wp_next_scheduled( 'ti-po-li-cos' ) ) {
    wp_schedule_event( time(), 'weekly', 'ti-po-li-cos' );
  }  
  ///Hook into that action that'll fire every six hours
  add_action( 'ti-po-li-cos', 'theShark_myprefix_cron_function_Alibay' );
  //create your function, that runs on cron
  function theShark_myprefix_cron_function_Alibay() {
    update_option('isAllowedToImport_alibay',   '1');
    //your function...
  }
