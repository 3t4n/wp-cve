<?php
/*
Plugin Name: CP Contact Form with PayPal
Plugin URI: https://cfpaypal.dwbooster.com
Description: Inserts a contact form into your website and lets you connect it to a Paypal payment.
Version: 1.3.47
Author: CodePeople, paypaldev
Author URI: https://cfpaypal.dwbooster.com
License: GPL
Text Domain: cp-contact-form-with-paypal
*/


/* initialization / install / uninstall functions */


// CP Contact Form with PayPal constants

define('CP_CONTACTFORMPP_DEFAULT_CURRENCY_SYMBOL','$');
define('CP_CONTACTFORMPP_GBP_CURRENCY_SYMBOL',chr(163));
define('CP_CONTACTFORMPP_EUR_CURRENCY_SYMBOL_A','EUR ');
define('CP_CONTACTFORMPP_EUR_CURRENCY_SYMBOL_B',chr(128));

define('CP_CONTACTFORMPP_DEFAULT_DEFER_SCRIPTS_LOADING', (get_option('CP_CFPP_LOAD_SCRIPTS',"0") == "1"?false:true));

define('CP_CONTACTFORMPP_DEFAULT_form_structure', '[[{"name":"email","index":0,"title":"Email","ftype":"femail","userhelp":"","csslayout":"","required":true,"predefined":"","size":"medium"},{"name":"subject","index":1,"title":"Subject","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium"},{"name":"message","index":2,"size":"large","required":true,"title":"Message","ftype":"ftextarea","userhelp":"","csslayout":"","predefined":""}],[{"title":"","description":"","formlayout":"top_aligned"}]]');
                                                   

define('CP_CONTACTFORMPP_DEFAULT_fp_subject', 'Contact from the website...');
define('CP_CONTACTFORMPP_DEFAULT_fp_inc_additional_info', 'false');
define('CP_CONTACTFORMPP_DEFAULT_fp_return_page', get_site_url());
define('CP_CONTACTFORMPP_DEFAULT_fp_message', "The following contact message has been sent:\n\n<"."%INFO%".">\n\n");

define('CP_CONTACTFORMPP_DEFAULT_cu_enable_copy_to_user', 'true');
define('CP_CONTACTFORMPP_DEFAULT_cu_user_email_field', '');
define('CP_CONTACTFORMPP_DEFAULT_cu_subject', 'Confirmation: Message received...');
define('CP_CONTACTFORMPP_DEFAULT_cu_message', "Thank you for your message. We will reply you as soon as possible.\n\nThis is a copy of the data sent:\n\n<"."%INFO%".">\n\nBest Regards.");
define('CP_CONTACTFORMPP_DEFAULT_email_format','text');

define('CP_CONTACTFORMPP_DEFAULT_vs_use_validation', 'true');

define('CP_CONTACTFORMPP_DEFAULT_vs_text_is_required', 'This field is required.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('CP_CONTACTFORMPP_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with the format mm/dd/yyyy');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with the format dd/mm/yyyy');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('CP_CONTACTFORMPP_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');


define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_EXPRESSCREDIT_YES', 'Pay with PayPal Credit');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_EXPRESSCREDIT_NO', 'Pay with PayPal Express');

define('CP_CONTACTFORMPP_DEFAULT_cv_enable_captcha', 'false');
define('CP_CONTACTFORMPP_DEFAULT_cv_width', '180');
define('CP_CONTACTFORMPP_DEFAULT_cv_height', '60');
define('CP_CONTACTFORMPP_DEFAULT_cv_chars', '5');
define('CP_CONTACTFORMPP_DEFAULT_cv_font', 'font-1.ttf');
define('CP_CONTACTFORMPP_DEFAULT_cv_min_font_size', '25');
define('CP_CONTACTFORMPP_DEFAULT_cv_max_font_size', '35');
define('CP_CONTACTFORMPP_DEFAULT_cv_noise', '200');
define('CP_CONTACTFORMPP_DEFAULT_cv_noise_length', '4');
define('CP_CONTACTFORMPP_DEFAULT_cv_background', 'ffffff');
define('CP_CONTACTFORMPP_DEFAULT_cv_border', '000000');
define('CP_CONTACTFORMPP_DEFAULT_cv_text_enter_valid_captcha', 'Please enter a valid captcha code.');


define('CP_CONTACTFORMPP_DEFAULT_ENABLE_PAYPAL', 1);
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_MODE', 'production');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_RECURRENT', '0');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_IDENTIFY_PRICES', '0');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_ZERO_PAYMENT', '0');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_EMAIL','sample@email.com');
define('CP_CONTACTFORMPP_DEFAULT_PRODUCT_NAME','Reservation');
define('CP_CONTACTFORMPP_DEFAULT_COST','25');
define('CP_CONTACTFORMPP_DEFAULT_CURRENCY','USD');
define('CP_CONTACTFORMPP_DEFAULT_PAYPAL_LANGUAGE','EN');
define('CP_CONTACTFORMPP_STEP2_VRFY', false);

// database
define('CP_CONTACTFORMPP_FORMS_TABLE', 'cp_contact_form_paypal_settings');

define('CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME_NO_PREFIX', "cp_contact_form_paypal_discount_codes");
define('CP_CONTACTFORMPP_DISCOUNT_CODES_TABLE_NAME', @$wpdb->prefix ."cp_contact_form_paypal_discount_codes");

define('CP_CONTACTFORMPP_POSTS_TABLE_NAME_NO_PREFIX', "cp_contact_form_paypal_posts");
define('CP_CONTACTFORMPP_POSTS_TABLE_NAME', @$wpdb->prefix ."cp_contact_form_paypal_posts");

define('CP_CONTACTFORMPP_AUTH_INCLUDE', true);


// end CP Contact Form with PayPal constants

// code initialization, hooks
// -----------------------------------------

$CP_CFPP_global_form_count_number = 0;
$CP_CPP_global_form_count = "_".$CP_CFPP_global_form_count_number;

include_once dirname( __FILE__ ) . '/cp_contactformpp_functions.php';

register_activation_hook(__FILE__,'cp_contactformpp_install');

add_action('init', 'cp_contact_form_paypal_check_init_actions', 11 );
add_action('plugins_loaded', 'cpcfwpp_plugin_init');
add_action('wp_loaded', 'cp_contactformpp_data_management_loaded' );

//START: activation redirection 
function cpcfwpp_activation_redirect( $plugin ) {   
	  if(
        $plugin == plugin_basename( __FILE__ ) &&
        (!isset($_POST["action"]) || $_POST["action"] != 'activate-selected') &&
        (!isset($_POST["action2"]) || $_POST["action2"] != 'activate-selected')
        ) 
     {
        exit( wp_redirect( admin_url( 'admin.php?page=cp_contact_form_paypal.php' ) ) );
     }
}
add_action( 'activated_plugin', 'cpcfwpp_activation_redirect' );
//END: activation redirection 

if ( is_admin() ) {
    add_action('media_buttons', 'set_cp_contactformpp_insert_button', 100);
    add_action('admin_enqueue_scripts', 'set_cp_contactformpp_insert_adminScripts', 1);
    add_action('admin_menu', 'cp_contactformpp_admin_menu');
    add_action('enqueue_block_editor_assets', 'cp_contactformpp_gutenberg_block' );

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_".$plugin, 'cp_contactformpp_customAdjustmentsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_contactformpp_settingsLink');
    add_filter("plugin_action_links_".$plugin, 'cp_contactformpp_helpLink');

    function cp_contactformpp_admin_menu() {
        add_options_page('CP Contact Form with PayPal Options', 'CP Contact Form with PayPal', 'manage_options', 'cp_contact_form_paypal.php', 'cp_contactformpp_html_post_page' );
        add_menu_page( 'CP Contact Form with PayPal', 'CP Contact Form with PayPal', 'manage_options', 'cp_contact_form_paypal.php', 'cp_contactformpp_html_post_page' );
        
        add_submenu_page( 'cp_contact_form_paypal.php', 'Manage Forms', 'Manage Forms', 'manage_options', "cp_contact_form_paypal.php",  'cp_contactformpp_html_post_page' );
        add_submenu_page( 'cp_contact_form_paypal.php', 'Add ons', 'Add ons', 'manage_options', "cp_contact_form_addons", 'cp_contactformpp_html_post_page' );       
        add_submenu_page( 'cp_contact_form_paypal.php', 'Help: Online demo', 'Help: Online demo', 'manage_options', "cp_contact_form_paypal_demo", 'cp_contactformpp_html_post_page' );       
        add_submenu_page( 'cp_contact_form_paypal.php', 'Help: Documentation', 'Help: Documentation', 'manage_options', "cp_contact_form_paypal_doc", 'cp_contactformpp_html_post_page' );               
        add_submenu_page( 'cp_contact_form_paypal.php', 'Upgrade', 'Upgrade', 'edit_pages', "cp_contact_form_paypal_upgrade", 'cp_contactformpp_html_post_page' );
 

    }
} else { // if not admin
    add_shortcode( 'CP_CONTACT_FORM_PAYPAL', 'cp_contactformpp_filter_content' );
}

// register gutemberg block
if (function_exists('register_block_type'))
{ 
    register_block_type('cpcfwpp/form-rendering', array(
                        'attributes'      => array(
                                'formId'    => array(
                                    'type'      => 'string'
                                ),
                                'instanceId'    => array(
                                    'type'      => 'string'
                                ),
                            ),
                        'render_callback' => 'cp_contactformpp_render_form_admin'
                    )); 
}

// optional opt-in deactivation feedback
require_once 'cp-feedback.php';

// code for compatibility with third party scripts
add_filter('litespeed_cache_optimize_js_excludes', 'cfpaypal_litespeed_cache_optimize_js_excludes' );
function cfpaypal_litespeed_cache_optimize_js_excludes($options)
{
    return  "jquery.validate.min.js\njQuery.stringify.js\njquery.validate.js\njquery.js\n".$options;
}

// code for compatibility with third party scripts
add_filter('option_sbp_settings', 'cpcfwpp_sbp_fix_conflict' );
function cpcfwpp_sbp_fix_conflict($option)
{
    if(!is_admin())
    {
       if(is_array($option) && isset($option['jquery_to_footer'])) 
           unset($option['jquery_to_footer']);
    }
    return $option;
}

// elementor integration
include_once dirname( __FILE__ ) . '/controllers/elementor/cp-elementor-widget.inc.php';

?>