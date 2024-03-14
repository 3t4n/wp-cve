<?php
/*
Plugin Name: Booking Calendar Contact Form
Plugin URI: https://bccf.dwbooster.com
Description:  Booking forms for a start and end date or a single date: hotel booking, car rental, room booking, etc. Connected to a PayPal payment button.
Version: 1.2.49
Author: CodePeople
Author URI: https://bccf.dwbooster.com/download
License: GPL
Text Domain: booking-calendar-contact-form
*/


/* initialization / install / uninstall functions */

define('DEX_BCCF_DEFAULT_form_structure', '[[{"name":"email","index":0,"title":"Email","ftype":"femail","userhelp":"","csslayout":"","required":true,"predefined":"","size":"medium"},{"name":"subject","index":1,"title":"Subject","required":true,"ftype":"ftext","userhelp":"","csslayout":"","predefined":"","size":"medium"},{"name":"message","index":2,"size":"large","required":true,"title":"Message","ftype":"ftextarea","userhelp":"","csslayout":"","predefined":""}],[{"title":"","description":"","formlayout":"top_aligned"}]]');

define('DEX_BCCF_DEFAULT_DEFER_SCRIPTS_LOADING', (get_option('CP_BCCF_LOAD_SCRIPTS',"1") == "1"?true:false));

define('DEX_BCCF_DEFAULT_SERVICES_FIELDS', 4);

define('DEX_BCCF_DEFAULT_COLOR_STARTRES', '#CAFFCA');
define('DEX_BCCF_DEFAULT_COLOR_HOLIDAY', '#FF8080');

define('DEX_BCCF_DEFAULT_CALENDAR_LANGUAGE', '');
define('DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT', 'false');
define('DEX_BCCF_DEFAULT_CALENDAR_WEEKDAY', '0');
define('DEX_BCCF_DEFAULT_CALENDAR_MINDATE', 'today');
define('DEX_BCCF_DEFAULT_CALENDAR_MAXDATE', '');
define('DEX_BCCF_DEFAULT_CALENDAR_PAGES', 2);
define('DEX_BCCF_DEFAULT_CALENDAR_OVERLAPPED', 'false');
define('DEX_BCCF_DEFAULT_CALENDAR_ENABLED', 'true');

define('DEX_BCCF_DEFAULT_cu_user_email_field', 'email');
define('TDE_BCCFCALENDAR_DEFAULT_COLOR', '6FF');
define('TDE_BCCFCALENDAR_DEFAULT_SELCOLOR', 'F66');

define('DEX_BCCF_DEFAULT_ENABLE_PAYPAL', 1);
define('DEX_BCCF_DEFAULT_PAYPAL_EMAIL','your@email.here.com');
define('DEX_BCCF_DEFAULT_PRODUCT_NAME','Booking');
define('DEX_BCCF_DEFAULT_COST','25');
define('DEX_BCCF_DEFAULT_OK_URL',get_site_url());
define('DEX_BCCF_DEFAULT_CANCEL_URL',get_site_url());
define('DEX_BCCF_DEFAULT_CURRENCY','USD');
define('DEX_BCCF_DEFAULT_PAYPAL_LANGUAGE','EN');

define('DEX_BCCF_DEFAULT_PAYPAL_OPTION_YES', 'Pay with PayPal.');
define('DEX_BCCF_DEFAULT_PAYPAL_OPTION_NO', 'Pay later.');

define('DEX_BCCF_DEFAULT_vs_text_is_required', 'This field is required.');
define('DEX_BCCF_DEFAULT_vs_text_is_email', 'Please enter a valid email address.');

define('DEX_BCCF_DEFAULT_vs_text_datemmddyyyy', 'Please enter a valid date with this format(mm/dd/yyyy)');
define('DEX_BCCF_DEFAULT_vs_text_dateddmmyyyy', 'Please enter a valid date with this format(dd/mm/yyyy)');
define('DEX_BCCF_DEFAULT_vs_text_number', 'Please enter a valid number.');
define('DEX_BCCF_DEFAULT_vs_text_digits', 'Please enter only digits.');
define('DEX_BCCF_DEFAULT_vs_text_max', 'Please enter a value less than or equal to {0}.');
define('DEX_BCCF_DEFAULT_vs_text_min', 'Please enter a value greater than or equal to {0}.');


define('DEX_BCCF_DEFAULT_SUBJECT_CONFIRMATION_EMAIL', 'Thank you for your request...');
define('DEX_BCCF_DEFAULT_CONFIRMATION_EMAIL', "We have received your request with the following information:\n\n%INFORMATION%\n\nThank you.\n\nBest regards.");
define('DEX_BCCF_DEFAULT_SUBJECT_NOTIFICATION_EMAIL','New reservation requested...');
define('DEX_BCCF_DEFAULT_NOTIFICATION_EMAIL', "New reservation made with the following information:\n\n%INFORMATION%\n\nBest regards.");

define('DEX_BCCF_DEFAULT_CP_CAL_CHECKBOXES',"");
define('DEX_BCCF_DEFAULT_CP_CAL_CHECKBOXES_TYPE',"0");
define('DEX_BCCF_DEFAULT_EXPLAIN_CP_CAL_CHECKBOXES',"1.00 | Service 1 for us$1.00\n5.00 | Service 2 for us$5.00\n10.00 | Service 3 for us$10.00");


// tables

define('DEX_BCCF_TABLE_NAME_NO_PREFIX', "bccf_dex_bccf_submissions");
define('DEX_BCCF_TABLE_NAME', @$wpdb->prefix . DEX_BCCF_TABLE_NAME_NO_PREFIX);

define('DEX_BCCF_CALENDARS_TABLE_NAME_NO_PREFIX', "bccf_reservation_calendars_data");
define('DEX_BCCF_CALENDARS_TABLE_NAME', @$wpdb->prefix ."bccf_reservation_calendars_data");

define('DEX_BCCF_CONFIG_TABLE_NAME_NO_PREFIX', "bccf_reservation_calendars");
define('DEX_BCCF_CONFIG_TABLE_NAME', @$wpdb->prefix ."bccf_reservation_calendars");

define('DEX_BCCF_DISCOUNT_CODES_TABLE_NAME_NO_PREFIX', "bccf_dex_discount_codes");
define('DEX_BCCF_SEASON_PRICES_TABLE_NAME_NO_PREFIX', "bccf_dex_season_prices");
define('DEX_BCCF_DISCOUNT_CODES_TABLE_NAME', @$wpdb->prefix ."bccf_dex_discount_codes");

define('DEX_BCCF_REP_ARR', '[+arr1237]');

// calendar constants

define("TDE_BCCFDEFAULT_CALENDAR_ID","1");
define("TDE_BCCFDEFAULT_CALENDAR_LANGUAGE","EN");
define("DEX_BCCF_DEFAULT_CALENDAR_MODE","true");

define("TDE_BCCFCAL_PREFIX", "RCalendar");
define("TDE_BCCFCONFIG",DEX_BCCF_CONFIG_TABLE_NAME);
define("TDE_BCCFCONFIG_ID","id");
define("TDE_BCCFCONFIG_TITLE","title");
define("TDE_BCCFCONFIG_USER","uname");
define("TDE_BCCFCONFIG_PASS","passwd");
define("TDE_BCCFCONFIG_LANG","lang");
define("TDE_BCCFCONFIG_CPAGES","cpages");
define("TDE_BCCFCONFIG_MSG","msg");
define("TDE_BCCFCALDELETED_FIELD","caldeleted");
define("DEX_BCCF_STEP2_VRFY",false);

define("TDE_BCCFCALENDAR_DATA_TABLE",DEX_BCCF_CALENDARS_TABLE_NAME);
define("TDE_BCCFDATA_ID","id");
define("TDE_BCCFDATA_IDCALENDAR","reservation_calendar_id");
define("TDE_BCCFDATA_DATETIME_S","datatime_s");
define("TDE_BCCFDATA_DATETIME_E","datatime_e");
define("TDE_BCCFDATA_TITLE","title");
define("TDE_BCCFDATA_DESCRIPTION","description");
// end calendar constants

define('TDE_BCCFDEFAULT_dexcv_enable_captcha', 'true');
define('TDE_BCCFDEFAULT_dexcv_width', '180');
define('TDE_BCCFDEFAULT_dexcv_height', '60');
define('TDE_BCCFDEFAULT_dexcv_chars', '5');
define('TDE_BCCFDEFAULT_dexcv_font', 'font-1.ttf');
define('TDE_BCCFDEFAULT_dexcv_min_font_size', '30');
define('TDE_BCCFDEFAULT_dexcv_max_font_size', '35');
define('TDE_BCCFDEFAULT_dexcv_noise', '200');
define('TDE_BCCFDEFAULT_dexcv_noise_length', '4');
define('TDE_BCCFDEFAULT_dexcv_background', 'ffffff');
define('TDE_BCCFDEFAULT_dexcv_border', '000000');
define('DEX_BCCF_DEFAULT_dexcv_text_enter_valid_captcha', 'Please enter a valid captcha code.');

$DEX_DEFAULT_COLORS = array("CCC","F66","FF6","6F9","6FF","F9F",
                                   "999","C00","FC3","3C0","36F","C3C",
                                   "333","600","963","060","009","636");

$codepeople_promote_banner_plugins[ 'booking-calendar-contact-form' ] = array( 
                      'plugin_name' => 'Booking Calendar Contact Form', 
                      'plugin_url'  => 'https://wordpress.org/support/plugin/booking-calendar-contact-form/reviews/?filter=5#new-post'
);
require_once 'banner.php';

register_activation_hook(__FILE__,'dex_bccf_install');
register_deactivation_hook( __FILE__, 'dex_bccf_remove' );

function dex_bccf_plugin_init() {
  load_plugin_textdomain( 'booking-calendar-contact-form', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
  $ao_options = get_option('autoptimize_js_exclude',"seal.js, js/jquery/jquery.js");
  if (!strpos($ao_options,'stringify.js'))
     update_option('autoptimize_js_exclude',"jQuery.stringify.js,jquery.validate.js,".$ao_options);
}
add_action('plugins_loaded', 'dex_bccf_plugin_init');


// register gutemberg block
if (function_exists('register_block_type'))
{
    register_block_type('cpbccf/form-rendering', array(
                        'attributes'      => array(
                                'formId'    => array(
                                    'type'      => 'string'
                                ),
                                'instanceId'    => array(
                                    'type'      => 'string'
                                ),
                            ),
                        'render_callback' => 'bccf_render_form_admin'
                    )); 
}


//START: activation redirection 
function dex_activation_redirect( $plugin ) {
    if(
        $plugin == plugin_basename( __FILE__ ) &&
        (!isset($_POST["action"]) || $_POST["action"] != 'activate-selected') &&
        (!isset($_POST["action2"]) || $_POST["action2"] != 'activate-selected') 
      )
    {
        exit( wp_redirect( admin_url( 'admin.php?page=dex_bccf.php' ) ) );
    }
}
add_action( 'activated_plugin', 'dex_activation_redirect' );
//END: activation redirection 

function dex_bccf_install($networkwide)  {
	global $wpdb;

	if (function_exists('is_multisite') && is_multisite()) {
		// check if it is a network activation - if so, run the activation function for each blog id
		if ($networkwide) {
	                $old_blog = $wpdb->blogid;
			// Get all blog ids
			$blogids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ($blogids as $blog_id) {
				switch_to_blog($blog_id);
				_dex_bccf_install();
			}
			switch_to_blog($old_blog);
			return;
		}
	}
	_dex_bccf_install();
}

function _dex_bccf_install() {
    global $wpdb;
    
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE ".$wpdb->prefix.DEX_BCCF_DISCOUNT_CODES_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         cal_id mediumint(9) NOT NULL DEFAULT 1,
         code VARCHAR(250) DEFAULT '' NOT NULL,
         discount VARCHAR(250) DEFAULT '' NOT NULL,
         expires datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         availability int(10) unsigned NOT NULL DEFAULT 0,
         used int(10) unsigned NOT NULL DEFAULT 0,
         UNIQUE KEY id (id)
         )".$charset_collate.";";
    $wpdb->query($sql);

    $sql = "CREATE TABLE ".$wpdb->prefix.DEX_BCCF_SEASON_PRICES_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         cal_id mediumint(9) NOT NULL DEFAULT 1,
         price VARCHAR(250) DEFAULT '' NOT NULL,
         date_from datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         date_to datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         UNIQUE KEY id (id)
         )".$charset_collate.";";
    $wpdb->query($sql);


    $sql = "CREATE TABLE ".$wpdb->prefix.DEX_BCCF_TABLE_NAME_NO_PREFIX." (
         id mediumint(9) NOT NULL AUTO_INCREMENT,
         calendar INT NOT NULL,
         time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
         booked_time_s VARCHAR(250) DEFAULT '' NOT NULL,
         booked_time_e VARCHAR(250) DEFAULT '' NOT NULL,
         booked_time_unformatted_s VARCHAR(250) DEFAULT '' NOT NULL,
         booked_time_unformatted_e VARCHAR(250) DEFAULT '' NOT NULL,
         name VARCHAR(250) DEFAULT '' NOT NULL,
         email VARCHAR(250) DEFAULT '' NOT NULL,
         phone VARCHAR(250) DEFAULT '' NOT NULL,
         notifyto VARCHAR(250) DEFAULT '' NOT NULL,
         question mediumtext,
         buffered_date mediumtext,
         UNIQUE KEY id (id)
         )".$charset_collate.";";
    $wpdb->query($sql);


    $sql = "CREATE TABLE `".$wpdb->prefix.DEX_BCCF_CONFIG_TABLE_NAME."` (".
                   "`".TDE_BCCFCONFIG_ID."` int(10) unsigned NOT NULL auto_increment,".
                   "`".TDE_BCCFCONFIG_TITLE."` varchar(255) NOT NULL default '',".
                   "`".TDE_BCCFCONFIG_USER."` varchar(100) default NULL,".
                   "`".TDE_BCCFCONFIG_PASS."` varchar(100) default NULL,".
                   "`".TDE_BCCFCONFIG_LANG."` varchar(50) default NULL,".
                   "`".TDE_BCCFCONFIG_CPAGES."` tinyint(3) unsigned default NULL,".
                   "`".TDE_BCCFCONFIG_MSG."` varchar(255) NOT NULL default '',".
                   "`".TDE_BCCFCALDELETED_FIELD."` tinyint(3) unsigned default NULL,".
                   "`conwer` INT NOT NULL,".
                   "`form_structure` mediumtext,".
                   "`master` varchar(50) DEFAULT '' NOT NULL,".
                   "`calendar_language` varchar(10) DEFAULT '' NOT NULL,".
                   "`calendar_mode` varchar(10) DEFAULT '' NOT NULL,".
                   "`calendar_dateformat` varchar(10) DEFAULT '',".
                   "`calendar_overlapped` varchar(10) DEFAULT '',".
                   "`calendar_enabled` varchar(10) DEFAULT '',".
                   "`calendar_pages` varchar(10) DEFAULT '' NOT NULL,".
                   "`calendar_weekday` varchar(10) DEFAULT '' NOT NULL,".
                   "`calendar_mindate` varchar(255) DEFAULT '' NOT NULL,".
                   "`calendar_maxdate` varchar(255) DEFAULT '' NOT NULL,".
                   "`calendar_minnights` varchar(255) DEFAULT '0' NOT NULL,".
                   "`calendar_maxnights` varchar(255) DEFAULT '365' NOT NULL,".
                   "`calendar_suplement` varchar(255) DEFAULT '0' NOT NULL,".
                   "`calendar_suplementminnight` varchar(255) DEFAULT '0' NOT NULL,".
                   "`calendar_suplementmaxnight` varchar(255) DEFAULT '0' NOT NULL,".
                   "`calendar_startres` text,".
                   "`calendar_holidays` text,".
                   "`calendar_fixedmode` varchar(10) DEFAULT '0' NOT NULL,".
                   "`calendar_holidaysdays` varchar(20) DEFAULT '1111111' NOT NULL,".
                   "`calendar_startresdays` varchar(20) DEFAULT '1111111' NOT NULL,".
                   "`calendar_fixedreslength` varchar(20) DEFAULT '1' NOT NULL,".
                   "`calendar_showcost` varchar(1) DEFAULT '1' NOT NULL,".
                   // paypal
                   "`enable_paypal` varchar(10) DEFAULT '' NOT NULL,".
                   "`paypal_email` text,".
                   "`request_cost` text,".
                   "`max_slots` varchar(20) DEFAULT '0' NOT NULL ,".
                   
                   "`calendar_mwidth` varchar(20) DEFAULT '' NOT NULL ,".
                   "`calendar_minmwidth` varchar(20) DEFAULT '' NOT NULL ,".
                   "`calendar_maxmwidth` varchar(20) DEFAULT '' NOT NULL ,".
                   "`calendar_height` varchar(20) DEFAULT '' NOT NULL ,".
                   
                   "`paypal_product_name` varchar(255) DEFAULT '' NOT NULL,".
                   "`currency` varchar(10) DEFAULT '' NOT NULL,".
                   "`request_taxes` varchar(20) DEFAULT '' NOT NULL ,".
                   "`url_ok` text,".
                   "`url_cancel` text,".
                   "`paypal_language` varchar(10) DEFAULT '' NOT NULL,".
                   // copy to user
                   "`cu_user_email_field` text,".
                   "`notification_from_email` text,".
                   "`notification_destination_email` text,".
                   "`email_subject_confirmation_to_user` text,".
                   "`email_confirmation_to_user` text,".
                   "`email_subject_notification_to_admin` text,".
                   "`email_notification_to_admin` text,".
                   // validation
                   "`enable_paypal_option_yes` text,".
                   "`enable_paypal_option_no` text,".                  
                   "`vs_use_validation` VARCHAR(10) DEFAULT '' NOT NULL,".
                   "`vs_text_is_required` text ,".
                   "`vs_text_is_email` text,".
                   "`vs_text_datemmddyyyy` text,".
                   "`vs_text_dateddmmyyyy` text,".
                   "`vs_text_number` text,".
                   "`vs_text_digits` text,".
                   "`vs_text_max` text,".
                   "`vs_text_min` text,".

                   "`vs_text_submitbtn` text,".
                   "`vs_text_previousbtn` text,".
                   "`vs_text_nextbtn` text,  ".                  

                   "`calendar_depositenable` VARCHAR(20) DEFAULT '' NOT NULL,  ".
                   "`calendar_depositamount` VARCHAR(20) DEFAULT '' NOT NULL,  ".
                   "`calendar_deposittype` VARCHAR(20) DEFAULT '' NOT NULL,  ".                   

                   "`calendar_defrcolor` VARCHAR(20) DEFAULT '' NOT NULL, ".  
                   "`calendar_deselcolor` VARCHAR(20) DEFAULT '' NOT NULL, ".
                   
                   // captcha
                   "`dexcv_enable_captcha` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_width` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_height` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_chars` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_min_font_size` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_max_font_size` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_noise` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_noise_length` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_background` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_border` varchar(10) DEFAULT '' NOT NULL,".
                   "`dexcv_font` varchar(100) DEFAULT '' NOT NULL,".
                   "`cv_text_enter_valid_captcha` VARCHAR(250) DEFAULT '' NOT NULL,".
                   // services field
                   "`cp_cal_checkboxes` text,".
                   "`cp_cal_checkboxes_type` varchar(10) DEFAULT '' NOT NULL,".
                   "PRIMARY KEY (`".TDE_BCCFCONFIG_ID."`))".$charset_collate."; ";
    $wpdb->query($sql);

    $sql = 'INSERT INTO `'.$wpdb->prefix.DEX_BCCF_CONFIG_TABLE_NAME.'` (`'.TDE_BCCFCONFIG_ID.'`,`form_structure`,`'.TDE_BCCFCONFIG_TITLE.'`,`'.TDE_BCCFCONFIG_USER.'`,`'.TDE_BCCFCONFIG_PASS.'`,`'.TDE_BCCFCONFIG_LANG.'`,`'.TDE_BCCFCONFIG_CPAGES.'`,`'.TDE_BCCFCONFIG_MSG.'`,`'.TDE_BCCFCALDELETED_FIELD.'`,calendar_mode,enable_paypal) VALUES("1","'.esc_sql(DEX_BCCF_DEFAULT_form_structure).'","cal1","Calendar Item 1","","ENG","1","Please, select your reservation.","0","true","0");';
    $wpdb->query($sql);

    $sql = "CREATE TABLE `".$wpdb->prefix.DEX_BCCF_CALENDARS_TABLE_NAME."` (".
                   "`".TDE_BCCFDATA_ID."` int(10) unsigned NOT NULL auto_increment,".
                   "`".TDE_BCCFDATA_IDCALENDAR."` int(10) unsigned default NULL,".
                   "`".TDE_BCCFDATA_DATETIME_S."`datetime NOT NULL default '0000-00-00 00:00:00',".
                   "`".TDE_BCCFDATA_DATETIME_E."`datetime NOT NULL default '0000-00-00 00:00:00',".
                   "`".TDE_BCCFDATA_TITLE."` varchar(250) default NULL,".
                   "`".TDE_BCCFDATA_DESCRIPTION."` mediumtext,".
                   "`viadmin` varchar(10) DEFAULT '0' NOT NULL,".
                   "`reference` varchar(20) DEFAULT '' NOT NULL,".
                   "`statuscancel` varchar(1) DEFAULT '0' NOT NULL,".
                   "`color` varchar(10),".
                   "PRIMARY KEY (`".TDE_BCCFDATA_ID."`)) ".$charset_collate.";";
    $wpdb->query($sql);

    $rcode = get_option('BCCF_RCODE','');
    if ($rcode == '')
    {
        $rcode = wp_generate_uuid4();
        update_option( 'BCCF_RCODE', $rcode);
    }

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
}

function dex_bccf_remove() {

}


/* Filter for placing the maps into the contents */

function dex_bccf_filter_content($atts) {
    global $wpdb;
    extract( shortcode_atts( array(
		'calendar' => '',
		'user' => '',
	), $atts ) );
    if ($calendar != '')
        define ('DEX_BCCF_CALENDAR_FIXED_ID',intval($calendar));
    else if ($user != '')
    {
        $users = $wpdb->get_results( "SELECT user_login,ID FROM ".$wpdb->users." WHERE user_login='".esc_sql($user)."'" );
        if (isset($users[0]))
            define ('DEX_CALENDAR_USER',$users[0]->ID);
        else
            define ('DEX_CALENDAR_USER',0);
    }
    else
        define ('DEX_CALENDAR_USER',0);
    ob_start();
    dex_bccf_get_public_form();
    $buffered_contents = ob_get_contents();
    ob_end_clean();
    return $buffered_contents;
}


function dex_bccf_filter_content_allcalendars($atts) {
    global $wpdb;    
    define ('DEX_CALENDAR_USER',0);
    ob_start();
    wp_enqueue_script( "jquery" );
    wp_enqueue_script( "jquery-ui-core" );
    wp_enqueue_script( "jquery-ui-datepicker" );
    $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME );
    define('DEX_AUTH_INCLUDE', true);
    @include dirname( __FILE__ ) . '/addons/dex_allcals.inc.php';    
    $buffered_contents = ob_get_contents();
    ob_end_clean();
    return $buffered_contents;
}

function dex_bccf_get_public_form() {
    global $wpdb;

    if (defined('DEX_CALENDAR_USER') && DEX_CALENDAR_USER != 0)
        $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME." WHERE conwer=".intval(DEX_CALENDAR_USER) );
    else if (defined('DEX_BCCF_CALENDAR_FIXED_ID'))
        $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME." WHERE id=".intval(DEX_BCCF_CALENDAR_FIXED_ID) );
    else
        $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME );

    if (!defined('CP_BCCF_CALENDAR_ID'))
        define ('CP_BCCF_CALENDAR_ID',$myrows[0]->id);

    $previous_label = dex_bccf_get_option('vs_text_previousbtn', 'Previous');
    $previous_label = ($previous_label==''?'Previous':$previous_label);
    $next_label = dex_bccf_get_option('vs_text_nextbtn', 'Next');
    $next_label = ($next_label==''?'Next':$next_label);  
    
    // for the additional services field if needed
    $dex_buffer = array();
    for ($k=1;$k<=DEX_BCCF_DEFAULT_SERVICES_FIELDS; $k++)
    {
        $dex_buffer[$k] = "";
        $services = explode("\n",dex_bccf_get_option('cp_cal_checkboxes'.$k, DEX_BCCF_DEFAULT_CP_CAL_CHECKBOXES));
        foreach ($services as $item)
            if (trim($item) != '')
            {
                $dex_buffer[$k] .= '<option value="'.esc_attr($item).'">'.__(trim(substr($item,strpos($item,"|")+1)),'booking-calendar-contact-form').'</option>';
            }
    }

    $calendar_language = dex_bccf_get_option('calendar_language',DEX_BCCF_DEFAULT_CALENDAR_LANGUAGE);
    if ($calendar_language == '') $calendar_language = dex_bccf_autodetect_language();

    if (DEX_BCCF_DEFAULT_DEFER_SCRIPTS_LOADING)
    {
        wp_deregister_script('query-stringify');
        wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));

        wp_deregister_script('cp_contactformpp_validate_script');
        wp_register_script('cp_contactformpp_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));
        
        wp_deregister_script('cp_contactformpp_rcalendar');
        wp_register_script('cp_contactformpp_rcalendar', plugins_url('/js/jquery.rcalendar.js', __FILE__));        
       
        $dependencies = array("jquery","jquery-ui-core","jquery-ui-button","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","query-stringify","cp_contactformpp_validate_script", "cp_contactformpp_rcalendar"); 
        if ($calendar_language != '') {
            wp_deregister_script('cp_contactformpp_rclang');
            wp_register_script('cp_contactformpp_rclang', plugins_url('/js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__));        
            $dependencies[] = 'cp_contactformpp_rclang';
        }    

        wp_enqueue_script( 'dex_bccf_builder_script',
        plugins_url('/js/fbuilder.jquery.js?nc=1', __FILE__),$dependencies, false, true );

        // localize script
        wp_localize_script('dex_bccf_builder_script', 'dex_bccf_fbuilder_config', array('obj'  	=>
        '{"pub":true,"messages": {
        	                	"required": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_is_required', DEX_BCCF_DEFAULT_vs_text_is_required),'booking-calendar-contact-form')).'",
        	                	"email": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_is_email', DEX_BCCF_DEFAULT_vs_text_is_email),'booking-calendar-contact-form')).'",
        	                	"datemmddyyyy": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_datemmddyyyy', DEX_BCCF_DEFAULT_vs_text_datemmddyyyy),'booking-calendar-contact-form')).'",
        	                	"dateddmmyyyy": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_dateddmmyyyy', DEX_BCCF_DEFAULT_vs_text_dateddmmyyyy),'booking-calendar-contact-form')).'",
        	                	"number": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_number', DEX_BCCF_DEFAULT_vs_text_number),'booking-calendar-contact-form')).'",
        	                	"digits": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_digits', DEX_BCCF_DEFAULT_vs_text_digits),'booking-calendar-contact-form')).'",
        	                	"max": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_max', DEX_BCCF_DEFAULT_vs_text_max),'booking-calendar-contact-form')).'",
        	                	"min": "'.str_replace(array('"'),array('\\"'),__(dex_bccf_get_option('vs_text_min', DEX_BCCF_DEFAULT_vs_text_min),'booking-calendar-contact-form')).'",
    	                    	"previous": "'.str_replace(array('"'),array('\\"'),$previous_label).'",
    	                    	"next": "'.str_replace(array('"'),array('\\"'),$next_label).'"
        	                }}'
        ));
    }
    else
    {
        wp_enqueue_script( "jquery" );
        wp_enqueue_script( "jquery-ui-core" );
        wp_enqueue_script( "jquery-ui-datepicker" );
    }
    
    wp_enqueue_style('bccf-pubstyle', plugins_url('css/stylepublic.css', __FILE__));
    wp_enqueue_style('bccf-fbstyle', plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__));
    wp_enqueue_style('bccf-cstyle', plugins_url('css/calendar.css', __FILE__));

    $option_calendar_enabled = dex_bccf_get_option('calendar_enabled', DEX_BCCF_DEFAULT_CALENDAR_ENABLED);

    $button_label = dex_bccf_get_option('vs_text_submitbtn', 'Continue');
    $button_label = ($button_label==''?'Continue':$button_label);
    define('DEX_AUTH_INCLUDE', true);       
    
    if (!DEX_BCCF_DEFAULT_DEFER_SCRIPTS_LOADING) {
        
        $prefix_ui = '';
        if (@file_exists(dirname( __FILE__ ).'/../../../wp-includes/js/jquery/ui/jquery.ui.core.min.js'))
            $prefix_ui = 'jquery.ui.'; 
                    
?>
<?php $plugin_url = plugins_url('', __FILE__); ?>
<script> if( typeof jQuery != 'undefined' ) var jQueryBK = jQuery.noConflict(); </script>
<script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/jquery.js'; ?>'></script>
<script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'core.min.js'; ?>'></script>
<script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'datepicker.min.js'; ?>'></script>
<?php if (@file_exists(dirname( __FILE__ ).'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'widget.min.js')) { ?><script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'widget.min.js'; ?>'></script><?php } ?>
<?php if (@file_exists(dirname( __FILE__ ).'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'position.min.js')) { ?><script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'position.min.js'; ?>'></script><?php } ?>
<script type='text/javascript' src='<?php echo $plugin_url.'/../../../wp-includes/js/jquery/ui/'.$prefix_ui.'tooltip.min.js'; ?>'></script>
<script> 
        var myjQuery = jQuery.noConflict( ); 
        if( typeof jQueryBK != 'undefined' ) {jQuery = jQueryBK;};
</script>
<script type='text/javascript' src='<?php echo plugins_url('js/jQuery.stringify.js', __FILE__); ?>'></script>
<script type='text/javascript' src='<?php echo plugins_url('js/jquery.validate.js', __FILE__); ?>'></script>
<?php if ($calendar_language != '') { ?><script type="text/javascript" src="<?php echo plugins_url('/js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__); ?>"></script><?php } ?>
<script type='text/javascript' src='<?php echo plugins_url('js/jquery.rcalendar.js', __FILE__); ?>'></script>
<script type='text/javascript'>
/* <![CDATA[ */
var dex_bccf_fbuilder_config = {"obj":"{\"pub\":true,\"messages\": {\n    \t                \t\"required\": \"This field is required.\",\n    \t                \t\"email\": \"Please enter a valid email address.\",\n    \t                \t\"datemmddyyyy\": \"Please enter a valid date with this format(mm\/dd\/yyyy)\",\n    \t                \t\"dateddmmyyyy\": \"Please enter a valid date with this format(dd\/mm\/yyyy)\",\n    \t                \t\"number\": \"Please enter a valid number.\",\n    \t                \t\"digits\": \"Please enter only digits.\",\n    \t                \t\"max\": \"Please enter a value less than or equal to {0}.\",\n    \t                \t\"min\": \"Please enter a value greater than or equal to {0}.\"\n    \t                }}"};
/* ]]> */
</script>
<?php
    }
    @include dirname( __FILE__ ) . '/dex_scheduler.inc.php';
    if (!DEX_BCCF_DEFAULT_DEFER_SCRIPTS_LOADING) {    
        ?><script type='text/javascript' src='<?php echo plugins_url('js/fbuilder.jquery.js?nc=1', __FILE__); ?>'></script><?php
    }
}


function dex_bccf_show_booking_form($id = "")
{
    if ($id != '')
        define ('DEX_BCCF_CALENDAR_FIXED_ID',$id);
    define('DEX_AUTH_INCLUDE', true);
    @include dirname( __FILE__ ) . '/dex_scheduler.inc.php';
}


/* Code for the admin area */

if ( is_admin() ) {
    add_action('media_buttons', 'set_dex_bccf_insert_button', 100);
    add_action('admin_enqueue_scripts', 'set_dex_bccf_insert_adminScripts', 1);
    add_action('admin_menu', 'dex_bccf_admin_menu');
    add_action('enqueue_block_editor_assets', 'dex_bccf_gutenberg_block' );
    add_action('wp_loaded', 'dex_bccf_data_management_loaded' );

    $plugin = plugin_basename(__FILE__);
    add_filter("plugin_action_links_".$plugin, 'dex_bccf_customAdjustmentsLink');
    add_filter("plugin_action_links_".$plugin, 'dex_bccf_settingsLink');
    add_filter("plugin_action_links_".$plugin, 'dex_bccf_helpLink');


    function dex_bccf_admin_menu() {
        add_options_page('Booking Calendar Contact Form Options', 'Booking Calendar Contact Form', 'manage_options', 'dex_bccf.php', 'dex_bccf_html_post_page' );
        add_menu_page( 'Booking Calendar Contact Form Options', 'Booking Calend. Contact Form', 'read', 'dex_bccf.php', 'dex_bccf_html_post_page' );
        
        add_submenu_page( 'dex_bccf.php', 'Manage Calendar', 'Manage Calendar', 'read', "dex_bccf.php",  'dex_bccf_html_post_page' );
        add_submenu_page( 'dex_bccf.php', 'Help: Online demo', 'Help: Online demo', 'read', "dex_bccf_demo", 'dex_bccf_html_post_page' );       
        add_submenu_page( 'dex_bccf.php', 'Upgrade', 'Upgrade', 'read', "dex_bccf_upgrade", 'dex_bccf_html_post_page' );
                 
    }
}
else
{
    add_shortcode( 'CP_BCCF_FORM', 'dex_bccf_filter_content' );
    add_shortcode( 'CP_BCCF_ALLCALS', 'dex_bccf_filter_content_allcalendars' );
}


function dex_bccf_gutenberg_block() {
    global $wpdb;
    
    wp_enqueue_script( 'cpbccf_gutenberg_editor', plugins_url('/js/block.js?nc=1', __FILE__));

    $calendar_language = dex_bccf_get_option('calendar_language',DEX_BCCF_DEFAULT_CALENDAR_LANGUAGE);
    if ($calendar_language == '') $calendar_language = dex_bccf_autodetect_language();
    
    wp_enqueue_style('bccf-pubstyle', plugins_url('css/stylepublic.css', __FILE__));
    wp_enqueue_style('bccf-fbstyle', plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__));
    wp_enqueue_style('bccf-cstyle', plugins_url('css/calendar.css', __FILE__));
     
    wp_deregister_script('query-stringify');
    wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));

    wp_deregister_script('cp_contactformpp_validate_script');
    wp_register_script('cp_contactformpp_validate_script', plugins_url('/js/jquery.validate.js', __FILE__));
    
    wp_deregister_script('cp_contactformpp_rcalendar');
    wp_register_script('cp_contactformpp_rcalendar', plugins_url('/js/jquery.rcalendar.js', __FILE__));        
    
    $dependencies = array("jquery","jquery-ui-core","jquery-ui-button","jquery-ui-datepicker","jquery-ui-widget","jquery-ui-position","jquery-ui-tooltip","query-stringify","cp_contactformpp_validate_script", "cp_contactformpp_rcalendar"); 
    if ($calendar_language != '') {
        wp_deregister_script('cp_contactformpp_rclang');
        wp_register_script('cp_contactformpp_rclang', plugins_url('/js/languages/jquery.ui.datepicker-'.$calendar_language.'.js', __FILE__));        
        $dependencies[] = 'cp_contactformpp_rclang';
    }    

    wp_enqueue_script( 'dex_bccf_builder_script',
        plugins_url('/js/fbuilder.jquery.js?nc=1', __FILE__),$dependencies, false, true );
           
    $forms = array();
    $rows = $wpdb->get_results("SELECT id,title FROM ".$wpdb->prefix.DEX_BCCF_CONFIG_TABLE_NAME_NO_PREFIX." ORDER BY title");
    foreach ($rows as $item)
       $forms[] = array (
                        'value' => $item->id,
                        'label' => $item->title,
                        );

    wp_localize_script( 'cpbccf_gutenberg_editor', 'cpbccf_forms', array(
                        'forms' => $forms,
                        'siteUrl' => get_site_url()
                      ) );

    wp_localize_script('cpbccf_gutenberg_editor', 'BCCFURLS', array( 'siteurl' => cp_bccf_get_site_url()."/" ));                      
}


function bccf_render_form_admin ($atts) {
    global $wpdb;
    $is_gutemberg_editor = defined( 'REST_REQUEST' ) && REST_REQUEST && ! empty( $_REQUEST['context'] ) && 'edit' === $_REQUEST['context'];
    if (!$is_gutemberg_editor)
        return dex_bccf_filter_content (array('id' => $atts["formId"]));
    else if ($atts["formId"])
    {
        ob_start();
        @include dirname( __FILE__ ) . '/dex_scheduler_block.inc.php';
        $buffered_contents = ob_get_contents();
        ob_end_clean();
        return '<input type="hidden" name="form_structure'.$atts["instanceId"].'" id="form_structure'.$atts["instanceId"].'" value="'.esc_attr(dex_bccf_get_option('form_structure' ,DEX_BCCF_DEFAULT_form_structure, $atts["formId"])).'" /><fieldset class="ahbgutenberg_editor" disabled>'.$buffered_contents.'</fieldset>';
    }
    else
        return __('Booking form inserted. <b>Save and reload this page</b> to render the booking form.','booking-calendar-contact-form');
}


function dex_bccf_settingsLink($links) {
    $settings_link = '<a href="admin.php?page=dex_bccf.php">'.__('Settings','booking-calendar-contact-form').'</a>';
	array_unshift($links, $settings_link);
	return $links;
}


function dex_bccf_helpLink($links) {
    $help_link = '<a href="https://wordpress.org/support/plugin/booking-calendar-contact-form#new-post">'.__('Support','booking-calendar-contact-form').'</a>';
	array_unshift($links, $help_link);
	$s_link = '<a href="https://bccf.dwbooster.com/support?priority=1">'.__('Documentation','booking-calendar-contact-form').'</a>';
	array_unshift($links, $s_link);
	return $links;
}

function dex_bccf_customAdjustmentsLink($links) {
    $customAdjustments_link = '<a href="https://bccf.dwbooster.com/download">'.__('Upgrade','booking-calendar-contact-form').'</a>';
	array_unshift($links, $customAdjustments_link);
	return $links;
}

function dex_bccf_html_post_page() {
    global $wpdb;
    if ((isset($_GET["cal"]) && $_GET["cal"] != '') || 
        ( 
          (isset($_GET["cal"]) && @$_GET["cal"] == '0')
          || 
          (isset($_GET["pwizard"]) && $_GET["pwizard"] == '1')
         )
       )
    {
        if (!empty($_GET["cal"])) $_GET["cal"] = intval($_GET["cal"]);
        if (isset($_GET["edit"]) && $_GET["edit"] == '1')
            @include_once dirname( __FILE__ ) . '/cp_admin_int_edition.inc.php';
        else if (isset($_GET["list"]) && $_GET["list"] == '1')
            @include_once dirname( __FILE__ ) . '/dex_bccf_admin_int_bookings_list.inc.php';
        else if (!empty($_GET["pwizard"]) && $_GET["pwizard"] == '1')
            @include_once dirname( __FILE__ ) . '/dex-publish-wizzard.inc.php';
        else if (!empty($_GET["addbk"]) && $_GET["addbk"] == '1')
            @include_once dirname( __FILE__ ) . '/cp_admin_int_add_booking.inc.php';
        else
            @include_once dirname( __FILE__ ) . '/dex_bccf_admin_int.inc.php';
    }
    else
    {
        if (isset($_GET["page"]) &&$_GET["page"] == 'dex_bccf_upgrade')
        {
            echo("Redirecting to upgrade page...<script type='text/javascript'>document.location='https://bccf.dwbooster.com/download';</script>");
            exit;
        } 
        else if (isset($_GET["page"]) &&$_GET["page"] == 'dex_bccf_demo')
        {
            echo("Redirecting to demo page...<script type='text/javascript'>document.location='https://bccf.dwbooster.com/home#demos';</script>");
            exit;
        } 
        else        
            @include_once dirname( __FILE__ ) . '/dex_bccf_admin_int_calendar_list.inc.php';
    }

}

function set_dex_bccf_insert_button() {
    print '<a href="javascript:send_to_editor(\'[CP_BCCF_FORM]\')" title="'.__('Insert Booking Calendar','booking-calendar-contact-form').'"><img hspace="5" src="'.plugins_url('/images/dex_apps.gif', __FILE__).'" alt="'.__('Insert  Booking Calendar','booking-calendar-contact-form').'" /></a>';
}

function set_dex_bccf_insert_adminScripts($hook) {
    if (isset($_GET["page"]) && $_GET["page"] == "dex_bccf.php")
    {
        wp_deregister_script( 'bootstrap-datepicker-js' );
        wp_register_script('bootstrap-datepicker-js', plugins_url('/js/nope.js', __FILE__));   
        wp_deregister_script('query-stringify');
        wp_register_script('query-stringify', plugins_url('/js/jQuery.stringify.js', __FILE__));
        wp_deregister_script('cp_contactformpp_rcalendar');
        wp_register_script('cp_contactformpp_rcalendar', plugins_url('/js/jquery.rcalendar.js', __FILE__));
        wp_deregister_script('cp_contactformpp_rcalendaradmin');
        wp_register_script('cp_contactformpp_rcalendaradmin', plugins_url('/js/jquery.rcalendaradmin.js', __FILE__));
        
        if (isset($_GET["cal"]) && !isset($_GET["pwizard"]) && !isset($_GET["addbk"]) && !isset($_GET["item"]))
        {
            if (!isset($_GET["list"]))
                wp_enqueue_script( 'dex_bccf_builder_script', plugins_url('/js/fbuilder.jquery.js?nc=1', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-dialog","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker","query-stringify","cp_contactformpp_rcalendar","cp_contactformpp_rcalendaradmin") );
            else
                wp_enqueue_script( 'dex_bccf_builder_script', plugins_url('/js/fbuilder.jquery.js?nc=1', __FILE__),array("jquery","jquery-ui-core","jquery-ui-sortable","jquery-ui-tabs","jquery-ui-dialog","jquery-ui-droppable","jquery-ui-button","jquery-ui-datepicker","query-stringify","cp_contactformpp_rcalendar") );
        }

        wp_enqueue_style('bccf-fbstyle', plugins_url('css/cupertino/jquery-ui-1.8.20.custom.css', __FILE__));
        if (isset($_GET["addbk"]) && $_GET["addbk"] == '1')
            wp_enqueue_style('bccf-gpstyle', plugins_url('css/stylepublic.css', __FILE__));
        else
            wp_enqueue_style('bccf-gstyle', plugins_url('css/style.css', __FILE__));         
        wp_enqueue_style('bccf-cstyle', plugins_url('css/calendar.css', __FILE__));
        wp_enqueue_style('bccf-adminstyle', plugins_url('css/admin.css', __FILE__));
        wp_enqueue_style('bccf-newadminstyle', plugins_url('css/newadminlayout.css', __FILE__));           

    }
    if( 'post.php' != $hook  && 'post-new.php' != $hook )
        return;    
}

function dex_bccf_export_iCal() {
    global $wpdb;
	
	if (!($_GET["id"] != '' && substr(md5($_GET["id"].get_option('BCCF_RCODE',$_SERVER["DOCUMENT_ROOT"])),0,10) == $_GET["verify"]))
    {
        echo 'Access denied - verify value is not correct.';
        exit;
    }
			
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=events".date("Y-M-D_H.i.s").".ics");

    define('DEX_CAL_TIME_ZONE_MODIFY',"");

    echo "BEGIN:VCALENDAR\n";
    echo "PRODID:-//CodePeople//Booking Calendar Contact Form for WordPress//EN\n";
    echo "VERSION:2.0\n";
    echo "CALSCALE:GREGORIAN\n";
    echo "METHOD:PUBLISH\n";
    echo "X-WR-CALNAME:Bookings\n";
    //echo "X-WR-TIMEZONE:Europe/London\n";
    //echo "BEGIN:VTIMEZONE\n";
    //echo "TZID:Europe/Stockholm\n";
    //echo "X-LIC-LOCATION:Europe/London\n";
    //echo "BEGIN:DAYLIGHT\n";
    //echo "TZOFFSETFROM:+0000\n";
    //echo "TZOFFSETTO:+0100\n";
    //echo "TZNAME:CEST\n";
    //echo "DTSTART:19700329T020000\n";
    //echo "RRULE:FREQ=YEARLY;BYMONTH=3;BYDAY=-1SU\n";
    //echo "END:DAYLIGHT\n";
    //echo "BEGIN:STANDARD\n";
    //echo "TZOFFSETFROM:+0100\n";
    //echo "TZOFFSETTO:+0000\n";
    //echo "TZNAME:CET\n";
    //echo "DTSTART:19701025T030000\n";
    //echo "RRULE:FREQ=YEARLY;BYMONTH=10;BYDAY=-1SU\n";
    //echo "END:STANDARD\n";
    //echo "END:VTIMEZONE\n";

    $mycalendarrows = $wpdb->get_results( 'SELECT * FROM '.DEX_BCCF_CONFIG_TABLE_NAME .' WHERE `'.TDE_BCCFCONFIG_ID.'`='.intval($_GET["id"]));
    $mode = !($mycalendarrows[0]->calendar_mode == 'false');

    $events = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CALENDARS_TABLE_NAME." WHERE reservation_calendar_id=".intval($_GET["id"])." ORDER BY datatime_s ASC" );
    foreach ($events as $event)
    {

        echo "BEGIN:VEVENT\n";
        //echo "DTSTART:".date("Ymd",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."T".date("His",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."Z\n";
        //echo "DTEND:".date("Ymd",strtotime($event->datatime_e.DEX_CAL_TIME_ZONE_MODIFY))."T".date("His",strtotime($event->datatime_e.DEX_CAL_TIME_ZONE_MODIFY." +15 minutes"))."Z\n";
        echo "DTSTART;VALUE=DATE:".date("Ymd",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."\n";
        echo "DTEND;VALUE=DATE:".date("Ymd",strtotime($event->datatime_e.DEX_CAL_TIME_ZONE_MODIFY.(!$mode?"":"")))."\n";
        echo "DTSTAMP:".date("Ymd",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."T".date("His",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."Z\n";
        echo "UID:uid".$event->id."@".$_SERVER["SERVER_NAME"]."\n";
        echo "CREATED:".date("Ymd",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."T".date("His",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."Z\n";
        echo "DESCRIPTION:".str_replace("<br>",'\n',str_replace("<br />",'\n',str_replace("\r",'',str_replace("\n",'\n',$event->description)) ))."\n";
        echo "LAST-MODIFIED:".date("Ymd",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."T".date("His",strtotime($event->datatime_s.DEX_CAL_TIME_ZONE_MODIFY))."Z\n";
        echo "LOCATION:\n";
        echo "SEQUENCE:0\n";
        echo "STATUS:CONFIRMED\n";
        echo "SUMMARY:Booking from ".str_replace("\n",'\n',$event->title)."\n";
        echo "TRANSP:OPAQUE\n";
        echo "END:VEVENT\n";


    }
    echo 'END:VCALENDAR';
    exit;
}


function dex_bccf_get_default_paypal_email() 
{
    return get_the_author_meta('user_email', get_current_user_id());
}


function dex_bccf_get_default_from_email() 
{
    $default_from = strtolower(get_the_author_meta('user_email', get_current_user_id()));
    $domain = str_replace('www.','', strtolower($_SERVER["HTTP_HOST"]));                                  
    while (substr_count($domain,".") > 1)
        $domain = substr($domain, strpos($domain, ".")+1);                 
    $pos = strpos($default_from, $domain);
    if (substr_count($domain,".") == 1 && $pos === false)
        return 'admin@'.$domain;
    else    
        return $default_from;
}


/* hook for checking posted data for the admin area */


add_action( 'init', 'dex_bccf_check_posted_data', 11 );

function dex_bccf_check_posted_data()
{
    global $wpdb;

    if (isset($_GET["dex_item"]) && $_GET["dex_item"] != '')
        $_POST["dex_item"] = intval($_GET["dex_item"]);
    if (!defined('CP_BCCF_CALENDAR_ID') && isset($_POST["dex_item"]) && $_POST["dex_item"] != '')
        define ('CP_BCCF_CALENDAR_ID',intval($_POST["dex_item"]));

    // define which action is being requested
    //-------------------------------------------------
    if (isset($_GET["dex_bccf"]) && $_GET["dex_bccf"] == 'getcost')
    {
        $default_price = dex_bccf_get_option('request_cost', DEX_BCCF_DEFAULT_COST);
        $services_formatted = array();
        for ($k=1;$k<=DEX_BCCF_DEFAULT_SERVICES_FIELDS; $k++)
        {
            $services_formatted[$k] = array();
            if (isset($_GET["ser".$k]))
                $services_formatted[$k] = explode("|",$_GET["ser".$k]);
        }
        echo number_format (dex_bccf_caculate_price_overall(strtotime($_GET["from"]), strtotime($_GET["to"]), $_POST["dex_item"], $default_price, $services_formatted), 2, ".", ",");        
        exit;
    }
	
    if (isset($_GET["dex_bccf"]) && $_GET["dex_bccf"] == 'calfeed')
        dex_bccf_export_iCal();


    if (isset($_GET["bccf_export"]) && $_GET["bccf_export"] == '1' && is_admin() && current_user_can('manage_options'))
    {
        if (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'uname_bccf' ))    
            $message = "Access verification error. Cannot export form.";
        else
        {            
            $myrows = $wpdb->get_row( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME." WHERE id=".intval($_GET['name']), ARRAY_A);
            $form = serialize($myrows);        
            header("Content-type: application/octet-stream");
            header("Content-Disposition: attachment; filename=export.bccf");        
            echo $form;
            exit;         
        }
    }
   
    if (isset( $_GET['dex_bccf'] ) &&  $_GET['dex_bccf'] == 'bccf_loadmindate' && is_admin() && current_user_can('edit_posts'))
    {
        if ($_GET["code"] == '')
            echo '';
        else
        {
            $date = date("Y-m-d",strtotime($_GET["code"]));
            if (date("Y",strtotime($_GET["code"])) == '1970')
                echo '<span style="color:#DD0000;">Error! Invalid date format!. Calculated min date for today: '.$date.'</span>';
            else
                echo '<span style="color:#008800;">Calculated min date for today: '.$date.'</span>';
        }
        exit;
    }
    
    if (isset( $_GET['dex_bccf'] ) &&  $_GET['dex_bccf'] == 'bccf_loadmaxdate' && is_admin() && current_user_can('edit_posts'))
    {
        if ($_GET["code"] == '')
            echo '';
        else
        {
            $date = date("Y-m-d",strtotime($_GET["code"]));
            if (date("Y",strtotime($_GET["code"])) == '1970')
                echo '<span style="color:#DD0000;">Error! Invalid date format!. Calculated max date for today: '.$date.'</span>';
            else
            {
                echo '<span style="color:#008800;">Calculated max date for today: '.$date.'</span>';                
                $date2 = date("Y-m-d H:i",strtotime($_GET["code2"]));
                if ($date2 >= $date)
                     echo '<br /><span style="color:#DD0000;">Error! Max date is smaller than min date, so no days will be available in the calendar.</span>';
            }
        }
        exit;
    }    
    
    if (isset($_GET["dex_bccf"]) && $_GET["dex_bccf"] == 'loadseasonprices')
    {
        if (wp_verify_nonce( $_REQUEST['_wpnonce'], 'seasons_bccf' )) 
           dex_bccf_load_season_prices();
        else
        {
            echo 'Access verification error';
            exit;
        }   
    }    

    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['dex_bccf_post_options'] ) && current_user_can('edit_pages') && is_admin() )
    {
        dex_bccf_save_options();
        return;
    }
    
    if ( 'POST' == $_SERVER['REQUEST_METHOD'] && isset( $_POST['CP_BCCF_post_edition'] ) && current_user_can('edit_pages') && is_admin() )
    {
        dex_bccf_save_edition();
        return;
    }  
    
    // if this isn't the expected post and isn't the captcha verification then nothing to do
	if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['dex_bccf_post'] ) )
		if ( 'GET' != $_SERVER['REQUEST_METHOD'] || !isset( $_GET['hdcaptcha_dex_bccf_post'] ) )
		    return;

    // captcha verification
    //-------------------------------------------------
    if (function_exists('session_start')) @session_start();
    if (!isset($_GET['hdcaptcha_dex_bccf_post']) || $_GET['hdcaptcha_dex_bccf_post'] == '') $_GET['hdcaptcha_dex_bccf_post'] = (!empty($_POST['hdcaptcha_dex_bccf_post']) ? $_POST['hdcaptcha_dex_bccf_post'] : '');
    if (
           (!is_admin() && dex_bccf_get_option('dexcv_enable_captcha', TDE_BCCFDEFAULT_dexcv_enable_captcha) != 'false') &&
           ( (strtolower($_GET['hdcaptcha_dex_bccf_post']) != strtolower($_SESSION['rand_code'])) ||
             ($_SESSION['rand_code'] == '')
           )
           &&
           ( (md5(strtolower($_GET['hdcaptcha_dex_bccf_post'])) != ($_COOKIE['rand_code'])) ||
             ($_COOKIE['rand_code'] == '')
           )
       )
    {
        $_SESSION['rand_code'] = '';
        setCookie('rand_code', '', time()+36000,"/");        
        echo 'captchafailed';
        exit;
    }

	// if this isn't the real post (it was the captcha verification) then echo ok and exit
    if ( 'POST' != $_SERVER['REQUEST_METHOD'] || ! isset( $_POST['dex_bccf_post'] ) )
	{
	    echo 'ok';
        exit;
	}

    $_SESSION['rand_code'] = '';
    
    
    if (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'bccf_bookingf' ))    
    {
        $message = "Access verification error.";
        exit;
    }
    

    // get calendar and selected date
    //-------------------------------------------------
    $selectedCalendar = intval($_POST["dex_item"]);
    $selectedCalendar_sfx = 'calarea'.$selectedCalendar;
    $option_calendar_enabled = dex_bccf_get_option('calendar_enabled', DEX_BCCF_DEFAULT_CALENDAR_ENABLED);
    if ($option_calendar_enabled != 'false')
    {
        $_POST["dateAndTime_s"] =  $_POST["selYear_start".$selectedCalendar_sfx]."-".$_POST["selMonth_start".$selectedCalendar_sfx]."-".$_POST["selDay_start".$selectedCalendar_sfx];
        $_POST["dateAndTime_e"] =  $_POST["selYear_end".$selectedCalendar_sfx]."-".$_POST["selMonth_end".$selectedCalendar_sfx]."-".$_POST["selDay_end".$selectedCalendar_sfx];

        if (dex_bccf_get_option('calendar_dateformat', DEX_BCCF_DEFAULT_CALENDAR_DATEFORMAT))
        {
            $_POST["Date_s"] = date("d/m/Y",strtotime($_POST["dateAndTime_s"]));
            $_POST["Date_e"] = date("d/m/Y",strtotime($_POST["dateAndTime_e"]));
        }
        else
        {
            $_POST["Date_s"] = date("m/d/Y",strtotime($_POST["dateAndTime_s"]));
            $_POST["Date_e"] = date("m/d/Y",strtotime($_POST["dateAndTime_e"]));
        }

        // calculate days
        $days = round(
                       (strtotime($_POST["dateAndTime_e"]) - strtotime($_POST["dateAndTime_s"])) / (24 * 60 * 60)
                     );
        if (dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE) == 'false')
            $days++;
    }
    else
    {
        $days = 1;
        $_POST["dateAndTime_s"] = date("Y-m-d", time());
        $_POST["dateAndTime_e"] = date("Y-m-d", time());
        $_POST["Date_s"] = date("m/d/Y",strtotime($_POST["dateAndTime_s"]));
        $_POST["Date_e"] = date("m/d/Y",strtotime($_POST["dateAndTime_e"]));
    }

    $services_formatted = array();
    $services_text = "";


    // calculate price from services field or dates
    //-------------------------------------------------   
    $default_price = dex_bccf_get_option('request_cost', DEX_BCCF_DEFAULT_COST);    
    $price = dex_bccf_caculate_price_overall(strtotime($_POST["dateAndTime_s"]), strtotime($_POST["dateAndTime_e"]), CP_BCCF_CALENDAR_ID, @$default_price, $services_formatted);

    $taxes = trim(str_replace("%","",dex_bccf_get_option('request_taxes', '0')));

    // check discount codes
    //-------------------------------------------------
    $discount_note = "";
    $coupon = false;
    $codes = array();


    // get form info
    //---------------------------
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    $form_data = json_decode(dex_bccf_cleanJSON(dex_bccf_get_option('form_structure', DEX_BCCF_DEFAULT_form_structure)));
    $fields = array();
    foreach ($form_data[0] as $item)
    {
        $fields[$item->name] = $item->title;
    }

    // grab posted data
    //---------------------------
    $buffer = "";
    $params = array();
    $params["startdate"] = sanitize_text_field($_POST["Date_s"]);
    $params["enddate"] = sanitize_text_field($_POST["Date_e"]);
    $params["discount"] = @$discount_note;
    $params["coupon"] = "";
    $params["service"] = $services_text;
    $params["totalcost"] = dex_bccf_get_option('currency', DEX_BCCF_DEFAULT_CURRENCY).' '.number_format ($price, 2);

    foreach ($_POST as $item => $value)
        if (isset($fields[$item]))
        {
            $buffer .= $fields[$item] . ": ". (is_array($value)?(implode(", ",$value)):($value)) . "\n\n";
            $params[$item] = $value;
        }
    $buffer_A = trim($buffer)."\n\n";
    $buffer_A .= ($services_text != ''?$services_text."\n\n":"").
                 ($coupon?"\nCoupon code: ".$coupon->code.$discount_note."\n\n":"");

    if ($price != 0) $buffer_A .= __('Total Cost','booking-calendar-contact-form').': '.dex_bccf_get_option('currency', DEX_BCCF_DEFAULT_CURRENCY).' '.$price."\n\n";

    $buffer = sanitize_text_field($_POST["selMonth_start".$selectedCalendar_sfx]."/".$_POST["selDay_start".$selectedCalendar_sfx]."/".$_POST["selYear_start".$selectedCalendar_sfx]."-".
              $_POST["selMonth_end".$selectedCalendar_sfx]."/".$_POST["selDay_end".$selectedCalendar_sfx]."/".$_POST["selYear_end".$selectedCalendar_sfx])."\n".
              $buffer_A."*-*\n";

    $_SESSION['rand_code'] = '';
    setCookie('rand_code', '', time()+36000,"/");        

	/**
	 * Action called before insert the data into database. 
	 * To the function is passed an array with submitted data.
	 */							
	$params['formid'] = $selectedCalendar;    
    do_action_ref_array( 'dexbccf_process_data_before_insert',  array(&$params) );
    
    // insert into database
    //---------------------------
    $to = dex_bccf_get_option('cu_user_email_field', DEX_BCCF_DEFAULT_cu_user_email_field);
    $rows_affected = $wpdb->insert( DEX_BCCF_TABLE_NAME, array( 'calendar' => $selectedCalendar,
                                                                        'time' => current_time('mysql'),
                                                                        'booked_time_s' => sanitize_text_field($_POST["Date_s"]),
                                                                        'booked_time_e' => sanitize_text_field($_POST["Date_e"]),
                                                                        'booked_time_unformatted_s' => sanitize_text_field($_POST["dateAndTime_s"]),
                                                                        'booked_time_unformatted_e' => sanitize_text_field($_POST["dateAndTime_e"]),
                                                                        'question' => $buffer_A,
                                                                        'notifyto' => sanitize_email("".@$_POST[$to]),
                                                                        'buffered_date' => serialize($params)
                                                                         ) );
    if (!$rows_affected)
    {
        echo 'Error saving data! Please try again.';
        echo '<br /><br />If the error persists  please be sure you are using the latest version and in that case contact support service at https://bccf.dwbooster.com/contact-us?debug=db';
        exit;
    }


    $myrows = $wpdb->get_results( "SELECT MAX(id) as max_id FROM ".DEX_BCCF_TABLE_NAME );

 	// save data here
    $item_number = $myrows[0]->max_id;
    
	// Call action for data processing
	//---------------------------------
	$params[ 'itemnumber' ] = $item_number; 
    
	/**
	 * Action called after inserted the data into database. 
	 * To the function is passed an array with submitted data.
	 */							
	do_action( 'dexbccf_process_data', $params );    
   
    $paypal_optional = (dex_bccf_get_option('enable_paypal',DEX_BCCF_DEFAULT_ENABLE_PAYPAL) == '2');

    if (dex_bccf_get_option('calendar_depositenable','0') == '1')
    {
        if (dex_bccf_get_option('calendar_deposittype','0') == '0')
            $price = round($price * dex_bccf_get_option('calendar_depositamount','0')/100,2);
        else
            $price =  dex_bccf_get_option('calendar_depositamount','0');
    }
    
    if (is_admin())
    {
        dex_process_ready_to_go_bccf($item_number, "", $params);
        return;
    }
    if (dex_bccf_get_option('enable_paypal',DEX_BCCF_DEFAULT_ENABLE_PAYPAL))
    {
?>
<html>
<head><title>Redirecting to Paypal...</title></head>
<body>
<form action="https://www.paypal.com/cgi-bin/webscr" name="ppform3" method="post">
<input type="hidden" name="cmd" value="_xclick" />
<input type="hidden" name="business" value="<?php echo esc_attr(trim(dex_bccf_get_option('paypal_email', dex_bccf_get_default_paypal_email()))); ?>" />
<input type="hidden" name="item_name" value="<?php echo esc_attr(dex_bccf_get_option('paypal_product_name', DEX_BCCF_DEFAULT_PRODUCT_NAME).($services_text!=''?", ".str_replace("\n\n",", ",$services_text).". ":"").$discount_note); ?>" />
<input type="hidden" name="item_number" value="<?php echo esc_attr($item_number); ?>" />
<input type="hidden" name="custom" value="<?php echo esc_attr($item_number); ?>" />
<input type="hidden" name="amount" value="<?php echo floatval($price); ?>" />
<?php if ($taxes != '0' && $taxes != '') { ?>
<input type="hidden" name="tax_rate"  value="<?php echo esc_attr($taxes); ?>" />
<?php } ?>
<input type="hidden" name="page_style" value="Primary" />
<input type="hidden" name="charset" value="utf-8">
<input type="hidden" name="no_shipping" value="1" />
<input type="hidden" name="return" value="<?php echo esc_attr(trim(dex_bccf_get_option('url_ok', DEX_BCCF_DEFAULT_OK_URL))); ?>">
<input type="hidden" name="cancel_return" value="<?php echo esc_attr(dex_bccf_get_option('url_cancel', DEX_BCCF_DEFAULT_CANCEL_URL)); ?>" />
<input type="hidden" name="currency_code" value="<?php echo esc_attr(dex_bccf_clean_currency(dex_bccf_get_option('currency', DEX_BCCF_DEFAULT_CURRENCY))); ?>" />
<input type="hidden" name="lc" value="<?php echo esc_attr(dex_bccf_get_option('paypal_language', DEX_BCCF_DEFAULT_PAYPAL_LANGUAGE)); ?>" />
<input type="hidden" name="bn" value="NetFactorSL_SI_Custom" />
<input type="hidden" name="notify_url" value="<?php echo esc_attr(cp_bccf_get_FULL_site_url()); ?>/?dex_bccf_ipn=<?php echo esc_attr($item_number); ?>" />
</form>
<script type="text/javascript">
document.ppform3.submit();
</script>
</body>
</html>
<?php
        exit(); 
    }
    else
    {
        dex_process_ready_to_go_bccf($item_number, "", $params);

        $location = dex_bccf_get_option('url_ok', DEX_BCCF_DEFAULT_OK_URL);
        header("Location: ".$location);
        exit;
       
    }    
}


function dex_bccf_clean_currency($currency)
{
	$currency = trim(strtoupper($currency));
	if ($currency == 'GPB')
		return 'GBP';
    else if ($currency == 'POUNDS')
		return 'GBP';
	else if ($currency == 'CDN')
		return 'CAD';
	else if ($currency == '$')
		return 'USD';
    else if ($currency == 'DOLLAR')
		return 'USD';
    else if ($currency == 'DOLLARS')
		return 'USD'; 
    else if ($currency == 'EURO')
		return 'EUR';
	else if ($currency == 'MXP')
		return 'MXN';
	else if ($currency == 'AUS')
		return 'AUD';    
	else
		return $currency;
}


function dex_bccf_check_upload($uploadfiles) {
    $filetmp = $uploadfiles['tmp_name'];
    //clean filename and extract extension
    $filename = $uploadfiles['name'];
    // get file info
    $filetype = wp_check_filetype( basename( $filename ), null );

    if ( in_array ($filetype["ext"],array("php","asp","aspx","cgi","pl","perl","exe","js","com","bat","msi","ini","bat","com")) )
        return false;
    else
        return true;
}


function dex_bccf_caculate_price_overall($startday, $enddate, $calendar, $default_price, $services_formatted)
{
    $days = round(
                   ($enddate - $startday) / (24 * 60 * 60)
                  );
    if (dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE) == 'false')
        $days++;

    $min_nights = intval(dex_bccf_get_option('calendar_suplementminnight','0'));
    $max_nights = intval(dex_bccf_get_option('calendar_suplementmaxnight','365'));
    $suplement = 0;

    if ($days >= $min_nights && $days <= $max_nights)
        $suplement  = floatval(dex_bccf_get_option('calendar_suplement', 0));

    $default_price = dex_bccf_get_option('request_cost', DEX_BCCF_DEFAULT_COST);    
    $price = dex_bccf_caculate_price($startday, $enddate, CP_BCCF_CALENDAR_ID, $default_price);

    return $price+$suplement;
}

function dex_bccf_caculate_price($startday, $enddate, $calendar, $default_price) {
    global $wpdb;
 
    $default_price_array = explode (';', $default_price);
    $default_price = $default_price_array[0];
    $season_prices = array();
    $days = 0;
    $price = 0;
    $codes = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.DEX_BCCF_SEASON_PRICES_TABLE_NAME_NO_PREFIX.' WHERE `cal_id`='.intval($calendar));
    $mode = (dex_bccf_get_option('calendar_mode',DEX_BCCF_DEFAULT_CALENDAR_MODE) == 'false');
    
    $saved_startday = $startday;
    $season = array();    
    while (
           (($enddate>$startday) && !$mode) ||
           (($enddate>=$startday) && $mode)
           )
    {
        foreach ($codes as $value)
        {
           $sfrom = strtotime($value->date_from);
           $sto = strtotime($value->date_to);
           if (
                isset($value->id) && 
                $startday >= $sfrom && 
                ((($startday <= $sto) && $mode) || (($startday <= $sto) && !$mode))
                )
           {
               if (!isset($season[$value->id])) $season[$value->id] = 0;
               $season[$value->id]++;
               $season_prices["x".$value->id] = $value;
               $found = true;
               break;
           }    
        }   
        $startday = strtotime (date("Y-m-d", $startday)." +1 day"); //60*60*24;    
        
        $days++;
    }
  
    
    $price = 0;

    foreach ($season as $seasonid => $daysseason)
    {
        $sprice = explode (';', $season_prices["x".$seasonid]->price); 
        if (isset($sprice[$daysseason]) && floatval($sprice[$daysseason]))
            $price += $sprice[$daysseason];
        else 
            $price += $sprice[0]*$daysseason;
        $days -= $daysseason;
    }

    if ($days > 0 && isset($default_price_array[$days]) && trim(@$default_price_array[$days]))
        $price += trim($default_price_array[$days]);
    else 
        $price += floatval($default_price)*floatval($days);    
    
    return $price;
}


function dex_bccf_load_season_prices() {
    global $wpdb;

    if ( ! current_user_can('edit_pages') ) // prevent loading coupons from outside admin area
    {
        echo 'No enough privilegies to load this content.';
        exit;
    }

    if (!defined('CP_BCCF_CALENDAR_ID'))
        define ('CP_BCCF_CALENDAR_ID', intval($_GET["dex_item"]));

    if (isset($_GET["add"]) && $_GET["add"] == "1")
        $wpdb->insert( $wpdb->prefix.DEX_BCCF_SEASON_PRICES_TABLE_NAME_NO_PREFIX, array('cal_id' => CP_BCCF_CALENDAR_ID,
                                                                         'price' => sanitize_text_field($_GET["price"]),
                                                                         'date_from' => sanitize_text_field($_GET["dfrom"]),
                                                                         'date_to' => sanitize_text_field($_GET["dto"]),
                                                                         ));
    if (isset($_GET["delete"]) && $_GET["delete"] == "1")
        $wpdb->query( $wpdb->prepare( "DELETE FROM ".$wpdb->prefix.DEX_BCCF_SEASON_PRICES_TABLE_NAME_NO_PREFIX." WHERE id = %d", $_GET["code"] ));

    $codes = $wpdb->get_results( 'SELECT * FROM '.$wpdb->prefix.DEX_BCCF_SEASON_PRICES_TABLE_NAME_NO_PREFIX.' WHERE `cal_id`='.intval(CP_BCCF_CALENDAR_ID));
    $maxcosts = 0;
    foreach ($codes as $value)
        if (substr_count($value->price,';') > $maxcosts)
            $maxcosts = substr_count($value->price,';');
    if (count ($codes))
    {
        echo '<table>';
        echo '<tr>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Default Cost</th>';
        for ($k=1; $k<=$maxcosts; $k++)
            echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">'.$k.' day'.($k==1?'':'s').'</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">From</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">To</th>';
        echo '  <th style="padding:2px;background-color: #cccccc;font-weight:bold;">Options</th>';
        echo '</tr>';
        foreach ($codes as $value)
        {
           echo '<tr>';
           $price = explode(';',$value->price);
           echo '<td>'.esc_html($price[0]).'</td>';
           for ($k=1; $k<=$maxcosts; $k++)
               echo '<td>'.esc_html(@$price[$k]).'</td>';
           echo '<td>'.esc_html(substr($value->date_from,0,10)).'</td>';
           echo '<td>'.esc_html(substr($value->date_to,0,10)).'</td>';
           echo '<td>[<a href="javascript:dex_delete_season_price('.esc_attr($value->id).')">Delete</a>]</td>';
           echo '</tr>';
        }
        echo '</table>';
    }
    else
        echo 'No season prices listed for this calendar yet.';
    exit;
}

add_action( 'plugins_loaded', 'dex_bccf_check_IPN_verification', 11 );

function dex_bccf_check_IPN_verification() {

    global $wpdb;

	if ( ! isset( $_GET['dex_bccf_ipn'] ) || !intval($_GET['dex_bccf_ipn']) )
		return;

	$itemnumber = intval($_GET['dex_bccf_ipn']);

    $item_number = $itemnumber;
    $payment_status = sanitize_text_field($_POST['payment_status']);    
    $payer_email = sanitize_email($_POST['payer_email']);
    $payment_type = sanitize_text_field($_POST['payment_type']);

    if (DEX_BCCF_STEP2_VRFY)
    {
	    if ($payment_status != 'Completed' && $payment_type != 'echeck')
	        return;
    
	    if ($payment_type == 'echeck' && $payment_status != 'Pending')
	        return;
	}        

    $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_TABLE_NAME." WHERE id=".intval($itemnumber) );
    $params = unserialize($myrows[0]->buffered_date);

    dex_process_ready_to_go_bccf($itemnumber, $payer_email, $params);

    echo 'OK';

    exit();

}

function dex_process_ready_to_go_bccf($itemnumber, $payer_email = "", $params = array())
{
   global $wpdb;

   dex_bccf_add_field_verify(TDE_BCCFCALENDAR_DATA_TABLE, "reference", "varchar(20) DEFAULT '' NOT NULL"); 
   
   $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_TABLE_NAME." WHERE id=".intval($itemnumber) );

   $mycalendarrows = $wpdb->get_results( 'SELECT * FROM '.DEX_BCCF_CONFIG_TABLE_NAME .' WHERE `'.TDE_BCCFCONFIG_ID.'`='.intval($myrows[0]->calendar));
   
   if (!defined('CP_BCCF_CALENDAR_ID'))
        define ('CP_BCCF_CALENDAR_ID', intval($myrows[0]->calendar));
    
   $to = dex_bccf_get_option('cu_user_email_field', DEX_BCCF_DEFAULT_cu_user_email_field);
   $_POST[$to] = $myrows[0]->notifyto;

   $SYSTEM_EMAIL = dex_bccf_get_option('notification_from_email', dex_bccf_get_default_from_email() );
   $SYSTEM_RCPT_EMAIL = dex_bccf_get_option('notification_destination_email', dex_bccf_get_default_paypal_email());


   $email_subject1 = __(dex_bccf_get_option('email_subject_confirmation_to_user', DEX_BCCF_DEFAULT_SUBJECT_CONFIRMATION_EMAIL),'booking-calendar-contact-form');
   $email_content1 = __(dex_bccf_get_option('email_confirmation_to_user', DEX_BCCF_DEFAULT_CONFIRMATION_EMAIL),'booking-calendar-contact-form');
   $email_subject2 = __(dex_bccf_get_option('email_subject_notification_to_admin', DEX_BCCF_DEFAULT_SUBJECT_NOTIFICATION_EMAIL),'booking-calendar-contact-form');
   $email_content2 = __(dex_bccf_get_option('email_notification_to_admin', DEX_BCCF_DEFAULT_NOTIFICATION_EMAIL),'booking-calendar-contact-form');

   $option_calendar_enabled = dex_bccf_get_option('calendar_enabled', DEX_BCCF_DEFAULT_CALENDAR_ENABLED);
   if ($option_calendar_enabled != 'false')
   {
       $information = __('Item','booking-calendar-contact-form').": ".$mycalendarrows[0]->uname."\n\n".
                      __('Date From-To','booking-calendar-contact-form').": ".$myrows[0]->booked_time_s." - ".$myrows[0]->booked_time_e."\n\n".
                      $myrows[0]->question;
   }
   else
   {
       $information = "Item: ".$mycalendarrows[0]->uname."\n\n".
                      $myrows[0]->question;
   }

   $email_content1 = str_replace("%INFORMATION%", $information, $email_content1);
   $email_content2 = str_replace("%INFORMATION%", $information, $email_content2);

   dex_bccf_add_field_verify(TDE_BCCFCALENDAR_DATA_TABLE, "color", "varchar(10)");

   $rows_affected = $wpdb->insert( TDE_BCCFCALENDAR_DATA_TABLE, array( 'reservation_calendar_id' => $myrows[0]->calendar,
                                                                       'datatime_s' => date("Y-m-d H:i:s", strtotime($myrows[0]->booked_time_unformatted_s)),
                                                                       'datatime_e' => date("Y-m-d H:i:s", strtotime($myrows[0]->booked_time_unformatted_e)),
                                                                       'title' => sanitize_text_field((@$_POST[$to]?@$_POST[$to]:"Booked")),
                                                                       'description' => str_replace("\n","<br />", $information),
                                                                       'reference' => $itemnumber,
                                                                       'color' => dex_bccf_get_option('calendar_defrcolor', TDE_BCCFCALENDAR_DEFAULT_COLOR)
                                                                        ) );    
  
   $newitemnum = $wpdb->insert_id;
   $email_content1 = str_replace("<%itemnumber%>", $newitemnum, $email_content1);
   $email_content2 = str_replace("<%itemnumber%>", $newitemnum, $email_content2);

   $attachments = array();
   foreach ($params as $item => $value)
    {
        $email_content1 = str_replace('<%'.$item.'%>',(is_array($value)?(implode(", ",$value)):($value)),$email_content1);
        $email_content2 = str_replace('<%'.$item.'%>',(is_array($value)?(implode(", ",$value)):($value)),$email_content2);
        if (strpos($item,"_link"))
            $attachments[] = $value;
    }

   if (!strpos($SYSTEM_EMAIL,">"))
       $SYSTEM_EMAIL = '"'.$SYSTEM_EMAIL.'" <'.$SYSTEM_EMAIL.'>';
               
   // don't send emails if admin opt for that
   if (!is_admin() || isset($_POST["sendemails_admin"]))
   {
       // SEND EMAIL TO USER   
       $replyto = '';
       if (trim($_POST[$to]) != '')
       {
           wp_mail(trim($_POST[$to]), $email_subject1, $email_content1,
                    "From: ".$SYSTEM_EMAIL."\r\n".
                    "Content-Type: text/plain; charset=utf-8\n".                
                    "X-Mailer: PHP/" . phpversion());
           $replyto = trim($_POST[$to]);
       }
       if ($payer_email && strtolower($payer_email) != strtolower($_POST[$to]))
           wp_mail($payer_email , $email_subject1, $email_content1,
                    "From: ".$SYSTEM_EMAIL."\r\n".
                    "Content-Type: text/plain; charset=utf-8\n".
                    "X-Mailer: PHP/" . phpversion());
       
       
       // SEND EMAIL TO ADMIN
       if ($SYSTEM_RCPT_EMAIL != '')
       {
           $to = explode(",",$SYSTEM_RCPT_EMAIL);
           foreach ($to as $item)
               if (trim($item) != '')
               {        
                   wp_mail($item, $email_subject2, $email_content2,
                        "From: ".$SYSTEM_EMAIL."\r\n".
                        ($replyto!=''?"Reply-To: ".$replyto."\r\n":'').
                        "Content-Type: text/plain; charset=utf-8\n".
                        "X-Mailer: PHP/" . phpversion(), $attachments);
               }         
       }
   }   
   
   // repeat options for admin   
   if (is_admin() && isset($_POST["repeatbooking"]) && $_POST["repeatbooking"] == '1' && intval(@$_POST["repeattimes"]) && intval(@$_POST["repeatevery"])) 
   {
       $repeattimes = intval($_POST["repeattimes"]);  
       $repeatevery = intval($_POST["repeatevery"]);
       $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_TABLE_NAME." WHERE id=".intval($itemnumber) , ARRAY_A );
       for ($rt=1; $rt<$repeattimes; $rt++)
       {        
            $old_str = "Date From-To: ".$myrows[0]["booked_time_s"]." - ".$myrows[0]["booked_time_e"];
            unset($myrows[0]["id"]);
            $myrows[0]["booked_time_unformatted_s"] = date("Y-m-d", strtotime($myrows[0]["booked_time_unformatted_s"]." +".$repeatevery." days"));
            $myrows[0]["booked_time_unformatted_e"] = date("Y-m-d", strtotime($myrows[0]["booked_time_unformatted_e"]." +".$repeatevery." days"));
            $myrows[0]["booked_time_s"] = date("Y-m-d", strtotime($myrows[0]["booked_time_unformatted_s"]));
            $myrows[0]["booked_time_e"] = date("Y-m-d", strtotime($myrows[0]["booked_time_unformatted_e"]));
            $new_str = "Date From-To: ".$myrows[0]["booked_time_s"]." - ".$myrows[0]["booked_time_e"];
            $information = str_replace($old_str, $new_str, $information);
            
            $params["rawstartdate"] = $myrows[0]["booked_time_unformatted_s"];
            $params["startdate"] = $myrows[0]["booked_time_s"];
            $params["rawenddate"] = $myrows[0]["booked_time_unformatted_e"];
            $params["enddate"] = $myrows[0]["booked_time_e"];            
            $myrows[0]["buffered_date"] = serialize($params);
            
            $wpdb->insert(DEX_BCCF_TABLE_NAME, $myrows[0]); 
            
            $wpdb->insert( TDE_BCCFCALENDAR_DATA_TABLE, array( 'reservation_calendar_id' => $myrows[0]["calendar"],
                                                                       'datatime_s' => date("Y-m-d H:i:s", strtotime($myrows[0]["booked_time_unformatted_s"])),
                                                                       'datatime_e' => date("Y-m-d H:i:s", strtotime($myrows[0]["booked_time_unformatted_e"])),
                                                                       'title' => (@$_POST[$to]?@$_POST[$to]:"Booked"),
                                                                       'description' => str_replace("\n","<br />", $information),
                                                                       'reference' => $wpdb->insert_id,
                                                                       'color' => dex_bccf_get_option('calendar_defrcolor', TDE_BCCFCALENDAR_DEFAULT_COLOR)
                                                                        ) ); 
       }           
   }   

}


function dex_bccf_add_field_verify ($table, $field, $type = "text")
{
    global $wpdb;
    $results = $wpdb->get_results("SHOW columns FROM `".esc_sql($table)."` where field='".esc_sql($field)."'");
    if (!count($results))
    {
        $sql = "ALTER TABLE  `".esc_sql($table)."` ADD `".esc_sql($field)."` ".$type;
        $wpdb->query($sql);
    }
}



function dex_bccf_save_edition()
{
    $verify_nonce = wp_verify_nonce( $_POST['rsave'], 'bccf_update_actions_custom');
    if (!$verify_nonce)
    {
        echo 'Error: Form cannot be authenticated. Please contact our <a href="https://bccf.dwbooster.com/contact-us">support service</a> for verification and solution. Thank you.';
        return;
    }
        
    foreach ($_POST as $item => $value)
        if (!is_array($value))
            $_POST[$item] = stripcslashes($value);    
    if (substr_count($_POST['editionarea'],"\\\""))
        $_POST["editionarea"] = stripcslashes($_POST["editionarea"]);
    if ($_POST["cfwpp_edit"] == 'js')   
        update_option('CP_BCCF_JS', base64_encode($_POST["editionarea"]));  
    else if ($_POST["cfwpp_edit"] == 'css')  
        update_option('CP_BCCF_CSS', base64_encode($_POST["editionarea"]));  
}


function dex_bccf_save_options()
{
    global $wpdb;
    if (!defined('CP_BCCF_CALENDAR_ID'))
        define ('CP_BCCF_CALENDAR_ID', intval($_POST["dex_item"]));
    
    if (!wp_verify_nonce( $_REQUEST['_wpnonce'], 'uname_bccf' ))    
    {
        echo "Access verification error. Cannot update settings. Please contact our support service if you think this is an error.";
        return;
    }    
 
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_mwidth", "varchar(20) DEFAULT '' NOT NULL");    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_minmwidth", "varchar(20) DEFAULT '' NOT NULL");    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_maxmwidth", "varchar(20) DEFAULT '' NOT NULL");   
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_height", "varchar(20) DEFAULT '' NOT NULL");    
    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "max_slots", "varchar(20) DEFAULT '0' NOT NULL");    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "enable_paypal_option_yes", "varchar(250) DEFAULT '' NOT NULL");  
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "enable_paypal_option_no", "varchar(250) DEFAULT '' NOT NULL"); 

    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_deselcolor", "varchar(20) DEFAULT '' NOT NULL");
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_defrcolor", "varchar(20) DEFAULT '' NOT NULL");    
    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "vs_text_submitbtn", "varchar(250) DEFAULT '' NOT NULL");  
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "vs_text_previousbtn", "varchar(250) DEFAULT '' NOT NULL");  
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "vs_text_nextbtn", "varchar(250) DEFAULT '' NOT NULL");  
    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_depositenable", "varchar(20) DEFAULT '' NOT NULL");
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_depositamount", "varchar(20) DEFAULT '' NOT NULL");
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_deposittype", "varchar(20) DEFAULT '' NOT NULL"); 
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "calendar_showcost", "varchar(1) DEFAULT '0' NOT NULL");
    
    dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME,'request_taxes'," varchar(20) NOT NULL default ''");    
    
    $calendar_holidaysdays = (@$_POST["wd1"]?"1":"0").(@$_POST["wd2"]?"1":"0").(@$_POST["wd3"]?"1":"0").(@$_POST["wd4"]?"1":"0").(@$_POST["wd5"]?"1":"0").(@$_POST["wd6"]?"1":"0").(@$_POST["wd7"]?"1":"0");
    $calendar_startresdays = (!empty($_POST["sd1"]) &&$_POST["sd1"]?"1":"0").
                             (!empty($_POST["sd2"]) &&$_POST["sd2"]?"1":"0").
                             (!empty($_POST["sd3"]) &&$_POST["sd3"]?"1":"0").
                             (!empty($_POST["sd4"]) &&$_POST["sd4"]?"1":"0").
                             (!empty($_POST["sd5"]) &&$_POST["sd5"]?"1":"0").
                             (!empty($_POST["sd6"]) &&$_POST["sd6"]?"1":"0").
                             (!empty($_POST["sd7"]) &&$_POST["sd7"]?"1":"0");    
    
    

    for ($k=1;$k<=DEX_BCCF_DEFAULT_SERVICES_FIELDS; $k++)
    {
        dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "cp_cal_checkboxes_label".$k);
        dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "cp_cal_checkboxes_type".$k);
        dex_bccf_add_field_verify(DEX_BCCF_CONFIG_TABLE_NAME, "cp_cal_checkboxes".$k);
    }

    if ((substr_count($_POST['form_structure_control'],"\\") > 1) || substr_count($_POST['form_structure_control'],"\\\"title\\\":"))
        foreach ($_POST as $item => $value)
            if (!is_array($value))
                $_POST[$item] = stripcslashes($value);

    for ($k=1;$k <= intval($_POST["max_slots"]); $k++)
        $_POST["request_cost"] .= ";".$_POST["request_cost_".$k];


    $data = array(
         'form_structure' => bccf_clean_and_sanitize($_POST["form_structure"]),
         'calendar_language' => sanitize_text_field($_POST["calendar_language"]),
         'calendar_dateformat' => sanitize_text_field($_POST["calendar_dateformat"]),
         'calendar_overlapped' => sanitize_text_field($_POST["calendar_overlapped"]),
         'calendar_enabled' => sanitize_text_field($_POST["calendar_enabled"]),
         'calendar_mode' => sanitize_text_field($_POST["calendar_mode"]),
         'calendar_pages' => sanitize_text_field(isset($_POST["calendar_pages"])?$_POST["calendar_pages"]:1),
         'calendar_weekday' => sanitize_text_field($_POST["calendar_weekday"]),
         'calendar_mindate' => sanitize_text_field($_POST["calendar_mindate"]),
         'calendar_maxdate' => sanitize_text_field($_POST["calendar_maxdate"]),
         
         'calendar_mwidth' => sanitize_text_field(@$_POST["calendar_mwidth"]),   
         'calendar_minmwidth' => sanitize_text_field(@$_POST["calendar_minmwidth"]),
         'calendar_maxmwidth' => sanitize_text_field(@$_POST["calendar_maxmwidth"]),
         'calendar_height' => sanitize_text_field(@$_POST["calendar_height"]),            

         'calendar_minnights' => sanitize_text_field(@$_POST["calendar_minnights"]),
         'calendar_maxnights' => sanitize_text_field(@$_POST["calendar_maxnights"]),
         'calendar_suplement' => sanitize_text_field(@$_POST["calendar_suplement"]),
         'calendar_suplementminnight' => sanitize_text_field(@$_POST["calendar_suplementminnight"]),
         'calendar_suplementmaxnight' => sanitize_text_field(@$_POST["calendar_suplementmaxnight"]),
         'calendar_fixedmode' => sanitize_text_field( (!empty($_POST["calendar_fixedmode"]) && $_POST["calendar_fixedmode"]?"1":"0") ),         
         'calendar_holidaysdays' => sanitize_text_field($calendar_holidaysdays),
         'calendar_startresdays' => sanitize_text_field($calendar_startresdays),
         'calendar_fixedreslength' => sanitize_text_field(@$_POST["calendar_fixedreslength"]),
         
         'calendar_defrcolor' => sanitize_text_field(@$_POST["calendar_defrcolor"]),
         'calendar_deselcolor' => sanitize_text_field(@$_POST["calendar_deselcolor"]),         
         
         'calendar_startres' => sanitize_text_field(@$_POST["calendar_startres"]),
         'calendar_holidays' => sanitize_text_field(@$_POST["calendar_holidays"]),
         'calendar_showcost' => sanitize_text_field(@$_POST["calendar_showcost"]),

         'cu_user_email_field' => bccf_clean_and_sanitize(@$_POST['cu_user_email_field']),

         'enable_paypal' =>sanitize_text_field( @$_POST["enable_paypal"]),
         'paypal_email' => sanitize_text_field($_POST["paypal_email"]),
         'request_cost' => sanitize_text_field($_POST["request_cost"]),
         'max_slots' => sanitize_text_field(@$_POST["max_slots"]),
         'paypal_product_name' => bccf_clean_and_sanitize($_POST["paypal_product_name"]),
         'currency' => sanitize_text_field($_POST["currency"]),
         'url_ok' => sanitize_text_field($_POST["url_ok"]),
         'url_cancel' => sanitize_text_field($_POST["url_cancel"]),
         'paypal_language' => sanitize_text_field($_POST["paypal_language"]),
         'request_taxes' => sanitize_text_field($_POST["request_taxes"]),

         'notification_from_email' => bccf_clean_and_sanitize($_POST["notification_from_email"]),
         'notification_destination_email' => bccf_clean_and_sanitize($_POST["notification_destination_email"]),
         'email_subject_confirmation_to_user' => bccf_clean_and_sanitize($_POST["email_subject_confirmation_to_user"]),
         'email_confirmation_to_user' => bccf_clean_and_sanitize($_POST["email_confirmation_to_user"]),
         'email_subject_notification_to_admin' => bccf_clean_and_sanitize($_POST["email_subject_notification_to_admin"]),
         'email_notification_to_admin' => bccf_clean_and_sanitize($_POST["email_notification_to_admin"]),

         'enable_paypal_option_yes' => sanitize_text_field(!empty($_POST['enable_paypal_option_yes']) && $_POST['enable_paypal_option_yes']?$_POST['enable_paypal_option_yes']:DEX_BCCF_DEFAULT_PAYPAL_OPTION_YES),
         'enable_paypal_option_no' => sanitize_text_field(!empty($_POST['enable_paypal_option_no']) && $_POST['enable_paypal_option_no']?$_POST['enable_paypal_option_no']:DEX_BCCF_DEFAULT_PAYPAL_OPTION_NO),

         'vs_text_is_required' => sanitize_text_field($_POST['vs_text_is_required']),
         'vs_text_is_email' => sanitize_text_field($_POST['vs_text_is_email']),
         'vs_text_datemmddyyyy' => sanitize_text_field($_POST['vs_text_datemmddyyyy']),
         'vs_text_dateddmmyyyy' => sanitize_text_field($_POST['vs_text_dateddmmyyyy']),
         'vs_text_number' => sanitize_text_field($_POST['vs_text_number']),
         'vs_text_digits' => sanitize_text_field($_POST['vs_text_digits']),
         'vs_text_max' => sanitize_text_field($_POST['vs_text_max']),
         'vs_text_min' => sanitize_text_field($_POST['vs_text_min']),

         'vs_text_submitbtn' => sanitize_text_field($_POST['vs_text_submitbtn']),
         'vs_text_previousbtn' => sanitize_text_field($_POST['vs_text_previousbtn']),
         'vs_text_nextbtn' => sanitize_text_field($_POST['vs_text_nextbtn']),

         'calendar_depositenable' => sanitize_text_field($_POST['calendar_depositenable']),
         'calendar_depositamount' => sanitize_text_field($_POST['calendar_depositamount']),
         'calendar_deposittype' => sanitize_text_field($_POST['calendar_deposittype']),
                           
         'dexcv_enable_captcha' => sanitize_text_field($_POST["dexcv_enable_captcha"]),
         'dexcv_width' => sanitize_text_field($_POST["dexcv_width"]),
         'dexcv_height' => sanitize_text_field($_POST["dexcv_height"]),
         'dexcv_chars' => sanitize_text_field($_POST["dexcv_chars"]),
         'dexcv_min_font_size' => sanitize_text_field($_POST["dexcv_min_font_size"]),
         'dexcv_max_font_size' => sanitize_text_field($_POST["dexcv_max_font_size"]),
         'dexcv_noise' => sanitize_text_field($_POST["dexcv_noise"]),
         'dexcv_noise_length' => sanitize_text_field($_POST["dexcv_noise_length"]),
         'dexcv_background' => sanitize_text_field($_POST["dexcv_background"]),
         'dexcv_background' => sanitize_text_field(str_replace('#','',$_POST['dexcv_background'])),
         'dexcv_border' => sanitize_text_field(str_replace('#','',$_POST['dexcv_border'])),
         'cv_text_enter_valid_captcha' => sanitize_text_field($_POST['cv_text_enter_valid_captcha']),

         'cp_cal_checkboxes' => sanitize_textarea_field( (!empty($_POST["cp_cal_checkboxes"]) ? $_POST["cp_cal_checkboxes"] : '' ) ),
         'cp_cal_checkboxes_type' => sanitize_text_field( (!empty($_POST["cp_cal_checkboxes_type"]) ? $_POST["cp_cal_checkboxes_type"] : '' ) )
	);

    $wpdb->update ( DEX_BCCF_CONFIG_TABLE_NAME, $data, array( 'id' => CP_BCCF_CALENDAR_ID ));
}


function bccf_clean_and_sanitize ($str)
{
    if ( is_object( $str ) || is_array( $str ) ) {
        return '';
    }
    $str = (string) $str; 
    $filtered = wp_check_invalid_utf8( $str );    
    while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) 
        $filtered = str_replace( $match[0], '', $filtered );
    return trim($filtered);
}


add_action( 'plugins_loaded', 'dex_bccf_calendar_ajaxevent', 11 );

function dex_bccf_calendar_ajaxevent() {
    if ( ! isset( $_GET['dex_bccf_calendar_load2'] ))
		return;

	if (!ini_get("zlib.output_compression")) 
	{ 
	    @ob_clean();
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Pragma: no-cache");
    }    

    $ret = array();
    $ret['events'] = array();
    $ret['isSuccess'] = true;
    $ret['msg'] = "";

    switch ($_GET['dex_bccf_calendar_load2']) {
        case 'list':
            $ret = dex_bccf_calendar_load2($ret);
            break;
        case 'add':
            if ( ! current_user_can('edit_pages') )
            {
                $ret['isSuccess'] = false;
                $ret['msg'] = "Permissions error: No enough privilegies to add an item.";
            }
            else
                $ret = dex_bccf_calendar_add($ret);
            break;
        case 'edit':
            if ( ! current_user_can('edit_pages') )
            {
                $ret['isSuccess'] = false;
                $ret['msg'] = "Permissions error: No enough privilegies to edit an item.";
            }
            else
                $ret = dex_bccf_calendar_update($ret);
            break;
        case 'delete':
            if ( ! current_user_can('edit_pages') )
            {
                $ret['isSuccess'] = false;
                $ret['msg'] = "Permissions error: No enough privilegies to delete an item.";
            }
            else
                $ret = dex_bccf_calendar_delete($ret);
            break;
        default:
          $ret['isSuccess'] = false;
          $ret['msg'] = "Unknown calendar action: ".esc_html($_GET['dex_bccf_calendar_load2']);
    }

    echo json_encode($ret);
    exit();
}

function dex_bccf_calendar_load2($ret) {
    global $wpdb;

    $calid = intval(str_replace  (TDE_BCCFCAL_PREFIX, "",@$_GET["id"]));
    if (!defined('CP_BCCF_CALENDAR_ID') && $calid != '-1')
        define ('CP_BCCF_CALENDAR_ID',$calid);

    $option = dex_bccf_get_option('calendar_overlapped', DEX_BCCF_DEFAULT_CALENDAR_OVERLAPPED);

    if ($calid == '-1')
        $query = "SELECT * FROM ".TDE_BCCFCALENDAR_DATA_TABLE." where (1=1)";
    else
        $query = "SELECT * FROM ".TDE_BCCFCALENDAR_DATA_TABLE." where ".TDE_BCCFDATA_IDCALENDAR."='".esc_sql($calid)."'";
    if ($option == 'true')
        $query.= " AND viadmin='1'";
    
    $result = $wpdb->get_results($query, ARRAY_A);
    foreach ($result as $row)
    {
        if (@$row["color"] == '')
            $row["color"] = dex_bccf_get_option('calendar_defrcolor', TDE_BCCFCALENDAR_DEFAULT_COLOR);
        $ret['events'][] = array(
                                  "id"=>$row["id"],
                                  "dl"=>date("m/d/Y", strtotime($row["datatime_s"])),
                                  "du"=>date("m/d/Y", strtotime($row["datatime_e"])),
                                  "title"=> (is_admin() && current_user_can('edit_posts') ? $row["title"] : 'Booked'),
                                  "description"=> (is_admin() && current_user_can('edit_posts') ? $row["description"] : 'OK'),
                                  "c"=>@$row["color"]    // falta annadir este campo
                                );
    }    
    
    return $ret;
}


function dex_bccf_calendar_add($ret) {
    global $wpdb;

    $calid = intval(str_replace  (TDE_BCCFCAL_PREFIX, "",@$_GET["id"]));
    if (!defined('CP_BCCF_CALENDAR_ID'))
        define ('CP_BCCF_CALENDAR_ID',$calid);

    dex_bccf_add_field_verify(TDE_BCCFCALENDAR_DATA_TABLE, "viadmin", "varchar(10) DEFAULT '0' NOT NULL");
    dex_bccf_add_field_verify(TDE_BCCFCALENDAR_DATA_TABLE, "color", "varchar(10)");

    $wpdb->query("insert into ".TDE_BCCFCALENDAR_DATA_TABLE."(viadmin,reservation_calendar_id,datatime_s,datatime_e,title,description,color) ".
                " values(1,".intval($calid).",'".esc_sql($_POST["startdate"])."','".esc_sql($_POST["enddate"])."','".esc_sql($_POST["title"])."','".esc_sql($_POST["description"])."','".esc_sql($_POST["color"])."')");
    $ret['events'][0] = array("id"=>$wpdb->insert_id,"dl"=>date("m/d/Y", strtotime($_POST["startdate"])),"du"=>date("m/d/Y", strtotime($_POST["enddate"])),"title"=>$_POST["title"],"description"=>$_POST["description"],"c"=>$_POST["color"]);
    return $ret;
}

function dex_bccf_calendar_update($ret) {
    global $wpdb;

    dex_bccf_add_field_verify(TDE_BCCFCALENDAR_DATA_TABLE, "viadmin", "varchar(10) DEFAULT '0' NOT NULL");
    dex_bccf_add_field_verify(TDE_BCCFCALENDAR_DATA_TABLE, "color", "varchar(10)");

    $wpdb->query("update ".TDE_BCCFCALENDAR_DATA_TABLE." set title='".esc_sql($_POST["title"])."',description='".esc_sql($_POST["description"])."',color='".esc_sql($_POST["color"])."' where id=".intval($_POST["id"]) );
    return $ret;
}

function dex_bccf_calendar_delete($ret) {
    global $wpdb;
    $wpdb->query( "delete from ".TDE_BCCFCALENDAR_DATA_TABLE." where id=".intval($_POST["id"]) );
    return $ret;
}

function dex_bccf_data_management_loaded() 
{
    global $wpdb, $bccf_postURL;

    if (empty($_POST['dex_bccf_do_action_loaded']))
        return;
    
    $action = sanitize_text_field($_POST['dex_bccf_do_action_loaded']);
	if (!$action) return; // go out if the call isn't for this one

    if ($_POST['dex_bccf_id']) $item = intval($_POST['dex_bccf_id']);

    if ($action == "wizard" && wp_verify_nonce( $_POST['nonce'], 'bccf_update_actions_pwizard' ) && current_user_can('manage_options'))
    {
        $shortcode = '[CP_BCCF_FORM calendar="'.$item .'"]';
        $bccf_postURL = bccf_publish_on($_POST["whereto"], $_POST["publishpage"], $_POST["publishpost"], $shortcode, $_POST["posttitle"]);            
        return;
    }

    // ...
    echo 'Some unexpected error happened. If you see this error contact the support service at https://bccf.dwbooster.com/contact-us';

    exit();
}    


function bccf_publish_on($whereto, $publishpage = '', $publishpost = '', $content = '', $posttitle = 'Booking Form')
{
    global $wpdb;
    $id = '';
    if ($whereto == '0' || $whereto =='1') // new page
    {
        $my_post = array(
          'post_title'    => $posttitle,
          'post_type' => ($whereto == '0'?'page':'post'),
          'post_content'  => 'This is a <b>preview</b> page, remember to publish it if needed. You can edit the full form settings into the admin settings page.<br /><br /> '.$content,
          'post_status'   => 'draft'
        );
        
        // Insert the post into the database
        $id = wp_insert_post( $my_post );
    }
    else 
    {
        $id = ($whereto == '2'?$publishpage:$publishpost);
        $post = get_post( $id );
        $pos = strpos($post->post_content,$content);
        if ($pos === false)
        {
            $my_post = array(
                  'ID'           => $id,
                  'post_content' => $content.$post->post_content,
              );
            // Update the post into the database
            wp_update_post( $my_post );
        }
    }
    return get_permalink($id);
}


function cp_bccf_get_site_url($admin = false)
{
    $blog = get_current_blog_id();
    if( $admin ) 
        $url = get_admin_url( $blog );	
    else 
        $url = get_home_url( $blog );	

    $url = parse_url($url);
    if (array_key_exists("path", $url))
        $url = rtrim(@$url["path"],"/");
    else
        $url = '';
    if (is_ssl())
        $url = str_replace('http://', 'https://', $url);      
    return $url;
}

function cp_bccf_get_FULL_site_url($admin = false)
{
    $blog = get_current_blog_id();
    if( $admin ) 
        $url = get_admin_url( $blog );	
    else 
        $url = get_home_url( $blog );	

    $url = parse_url($url);
    if (array_key_exists("path", $url))
        $url = rtrim(@$url["path"],"/");
    else
        $url = '';
    $pos = strpos($url, "://");
    if ($pos === false)
        $url = 'http://'.$_SERVER["HTTP_HOST"].$url;
    if (is_ssl())
        $url = str_replace('http://', 'https://', $url);        
    return $url;
}

function dex_bccf_cleanJSON($str)
{
    $str = str_replace('&qquot;','"',$str);
    $str = str_replace('	',' ',$str);
    $str = str_replace("\n",'\n',$str);
    $str = str_replace("\r",'',$str);
    return $str;
}

function dex_bccf_translate_json($str)
{
    $form_data = json_decode(dex_bccf_cleanJSON($str));          
    
    $form_data[1][0]->title = __($form_data[1][0]->title,'booking-calendar-contact-form');   
    $form_data[1][0]->description = __($form_data[1][0]->description,'booking-calendar-contact-form');   
    
    
    for ($i=0; $i < count($form_data[0]); $i++)    
    {
        $form_data[0][$i]->title = dexbccf_filter_allowed_tags(__($form_data[0][$i]->title,'booking-calendar-contact-form'));   
        if (isset($form_data[0][$i]->userhelpTooltip))
            $form_data[0][$i]->userhelpTooltip = dexbccf_filter_allowed_tags(__($form_data[0][$i]->userhelpTooltip,'booking-calendar-contact-form')); 
        $form_data[0][$i]->userhelp = dexbccf_filter_allowed_tags(__($form_data[0][$i]->userhelp,'booking-calendar-contact-form')); 
        $form_data[0][$i]->csslayout = sanitize_text_field($form_data[0][$i]->csslayout);
        
        if ($form_data[0][$i]->ftype == 'fCommentArea')
            $form_data[0][$i]->userhelp = __($form_data[0][$i]->userhelp,'booking-calendar-contact-form');   
        else 
            if ($form_data[0][$i]->ftype == 'fradio' || $form_data[0][$i]->ftype == 'fcheck' || $form_data[0][$i]->ftype == 'fradio')    
            {
                for ($j=0; $j < count($form_data[0][$i]->choices); $j++)  
                    $form_data[0][$i]->choices[$j] = __($form_data[0][$i]->choices[$j],'booking-calendar-contact-form'); 
            }    
    }    
    $str = json_encode($form_data);
    return $str;
}


function dexbccf_filter_allowed_tags($content)
{
    $tags_allowed = array(
                              'a' => array(
                                  'href' => array(),
                                  'title' => array(),
                                  'style' => array(),
                                  'class' => array(),
                              ),
                              'br' => array(),
                              'em' => array(),
                              'b' => array(),
                              'strong' => array(),
                              'img' => array(
                                        'src' => array(),
                                        'width' => array(),
                                        'height' => array(),
                                        'border' => array(),
                                        'style' => array(),
                                        'class' => array(),
                                        ),
                          );       
    //$allowed_tags = wp_kses_allowed_html( 'post' );
    //return  wp_kses( $content, $allowed_tags );
    return  wp_kses( $content, $tags_allowed );
}
    

function dex_bccf_autodetect_language()
{
    $basename = '/js/languages/jquery.ui.datepicker-';
    
    $options = array (get_bloginfo('language'),
                      strtolower(get_bloginfo('language')),
                      substr(strtolower(get_bloginfo('language')),0,2)."-".substr(strtoupper(get_bloginfo('language')),strlen(strtoupper(get_bloginfo('language')))-2,2),
                      substr(strtolower(get_bloginfo('language')),0,2),
                      substr(strtolower(get_bloginfo('language')),strlen(strtolower(get_bloginfo('language')))-2,2)                      
                      );
    foreach ($options as $option)
    {
        if (file_exists(dirname( __FILE__ ).$basename.$option.'.js'))
            return $option;
        $option = str_replace ("-","_", $option);    
        if (file_exists(dirname( __FILE__ ).$basename.$option.'.js'))
            return $option;
    }  
    return '';
}


// dex_dex_bccf_get_option:
$dex_option_buffered_item = false;
$dex_option_buffered_id = -1;

function dex_bccf_get_option ($field, $default_value = '')
{
    global $wpdb, $dex_option_buffered_item, $dex_option_buffered_id;
    if (!defined("CP_BCCF_CALENDAR_ID"))
        return  $default_value;
    $calid = intval(CP_BCCF_CALENDAR_ID);    
    if ($dex_option_buffered_id == $calid)
        //$value = @$dex_option_buffered_item->$field;
        $value = (property_exists($dex_option_buffered_item, $field) ? @$dex_option_buffered_item->$field : $default_value);
    else
    {
       $myrows = $wpdb->get_results( "SELECT * FROM ".DEX_BCCF_CONFIG_TABLE_NAME." WHERE id=".intval($calid) );
       if (count($myrows)) 
       {
           $value = @$myrows[0]->$field;
           $dex_option_buffered_item = $myrows[0];
           $dex_option_buffered_id  = $calid;
       }
       else  
           $value = $default_value;
    }
    if ($value == '' && $dex_option_buffered_item->calendar_language == '')
        $value = $default_value;
    return $value;
}

function cp_bccf_is_administrator()
{
    return current_user_can('manage_options');
}

// optional opt-in deactivation feedback
require_once 'cp-feedback.php';

// code for compatibility with third party scripts
add_filter('option_sbp_settings', 'bccf_sbp_fix_conflict' );
function bccf_sbp_fix_conflict($option)
{
    if(!is_admin())
    {
       if(is_array($option) && isset($option['jquery_to_footer'])) 
           unset($option['jquery_to_footer']);
    }
    return $option;
}

// code for compatibility with third party scripts
add_filter('litespeed_cache_optimize_js_excludes', 'dexbccf_litespeed_cache_optimize_js_excludes' );
function dexbccf_litespeed_cache_optimize_js_excludes($options)
{
    return  "jquery.validate.min.js\njQuery.stringify.js\njquery.validate.js\njquery.js\n".$options;
}


// code for compatibility with third party scripts
add_filter( 'smush_skip_image_from_cdn', function( $status, $src, $image ) {
	$skip_images = array(
		plugins_url('/captcha/captcha.php')		
	);
    
	if ( in_array( $src, $skip_images ) ) {
		return true;
	}
}, 10, 3 );

?>