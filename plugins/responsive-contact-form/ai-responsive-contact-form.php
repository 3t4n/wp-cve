<?php
/*
Plugin Name: Responsive Contact Form
Plugin URI: http://www.augustinfotech.com
Description: Add Contact Form to your WordPress website.You can add [ai_contact_form] shortcode where you want to display contact form.OR You can add  do_shortcode("[ai_contact_form]"); shortcode in any template.
Version: 2.8
Text Domain: aicontactform
Author: August Infotech
Author URI: http://www.augustinfotech.com
*/

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
define('AI_PDIR_PATH', plugin_dir_path(__FILE__ ));
add_action('plugins_loaded', 'ai_contact_init');

/** Start Upgrade Notice **/
global $pagenow;
if ( 'plugins.php' === $pagenow )
{
    // Better update message
    $file   = basename( __FILE__ );
    $folder = basename( dirname( __FILE__ ) );
    $hook = "in_plugin_update_message-{$folder}/{$file}";
    add_action( $hook, 'update_notification_message', 20, 2 );
}
function update_notification_message( $plugin_data, $r )
{
    $data = file_get_contents( 'http://plugins.trac.wordpress.org/browser/responsive-contact-form/trunk/readme.txt?format=txt' );
	$upgradetext = stristr( $data, '== Upgrade Notice ==' );	
	$upgradenotice = stristr( $upgradetext, '*' );	
	$output = "<div style='color:#EEC2C1;font-weight: normal;background: #C92727;padding: 10px;border: 1px solid #eed3d7;border-radius: 4px;'><strong style='color:rgb(253, 230, 61)'>Update Notice : </strong> ".$upgradenotice."</div>";

    return print $output;
}
/** End Upgrade Notice **/

/* Activate Hook Plugin */
register_activation_hook(__FILE__,'ai_add_contact_table');

# Load the language files
function ai_contact_init(){
	load_plugin_textdomain( 'aicontactform', false, plugin_basename( dirname( __FILE__ )  . '/languages/' ));
}

/**
 * Adds this plugin to the list of available contact us forms on BPMContext - Intranet Plus
   */
add_action( 'admin_init', 'ai_bpm_options_setup' );
function ai_bpm_options_setup(){

    $plugins_array['name'] = __('Responsive Contact Form', 'aicontactform');
    $plugins_array['url'] = 'https://wordpress.org/plugins/responsive-contact-form/';
    $plugins_array['slug'] = 'responsive-contact-form';
    $plugins_array['plugin_file'] = 'ai-responsive-contact-form.php';
    $plugins_array['shortcode'] = 'ai_contact_form';

    do_action('bpmcontext_add_to_allowed_plugins', $plugins_array);

}

add_action('admin_notices', 'ai_bpm_admin_notice');
function ai_bpm_admin_notice() {
    global $current_user ;
    $user_id = $current_user->ID;
    if ( ! get_user_meta($user_id, 'ai_bpm_ignore_notice') ) {
    echo '<div class="updated"><p>';
    printf(__('Responsive Contact Form is now compatible with Intranet Plus. Using Intranet Plus with Responsive Contact Form will help you track and manage contact inquiries. Click <a class="thickbox" data-title="BPMContext Integration" aria-label="More information about BPMContext Integration" href="%1$s">here</a> for more information and to get the Intranet Plus plugin. | <a href="?ai_bpm_nag_ignore=0">Hide Notice</a>', 'aicontactform'), 'plugin-install.php?tab=plugin-information&plugin=bpmcontext&TB_iframe=true&width=772&height=847');
    echo '</p></div>';
    }
}

add_action('admin_init', 'ai_bpm_nag_ignore');
function ai_bpm_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;
    if ( isset($_GET['ai_bpm_nag_ignore']) && '0' == $_GET['ai_bpm_nag_ignore'] ) {
    add_user_meta($user_id, 'ai_bpm_ignore_notice', 'true', true);
    }
}
/**
 * end of BPMContext Intranet Plus setup modifications
 */

add_action('admin_init', 'ai_register_fields' );
function ai_register_fields(){
	
	include_once( get_home_path().'/wp-load.php' );
	register_setting( 'ai-fields', 'ai_email_address_setting' );
	register_setting( 'ai-fields', 'ai_subject_text' );
	register_setting( 'ai-fields', 'ai_reply_user_message' );
	register_setting( 'ai-fields', 'ai_enable_captcha' ); 
	register_setting( 'ai-fields', 'ai_captcha_site_key' ); 
	register_setting( 'ai-fields', 'ai_captcha_secret_key' ); 
	register_setting( 'ai-fields', 'ai_error_setting' );	
	register_setting( 'ai-fields', 'ai_visible_name' ); 
	register_setting( 'ai-fields', 'ai_enable_require_name' );
	register_setting( 'ai-fields', 'ai_visible_phone' ); 
	register_setting( 'ai-fields', 'ai_enable_require_phone' );
	register_setting( 'ai-fields', 'ai_visible_email' );
	register_setting( 'ai-fields', 'ai_visible_subject' );
	register_setting( 'ai-fields', 'ai_enable_require_subject' );
	register_setting( 'ai-fields', 'ai_visible_website' );
	register_setting( 'ai-fields', 'ai_enable_require_website' );
	register_setting( 'ai-fields', 'ai_visible_comment' );
	register_setting( 'ai-fields', 'ai_enable_require_comment' );	
	register_setting( 'ai-fields', 'ai_visible_sendcopy' );	
	register_setting( 'ai-fields', 'ai_custom_css' );	
	register_setting( 'ai-fields', 'ai_rm_user_list' );	
	register_setting( 'ai-fields', 'ai_success_message' );
}

/*Uninstall Hook Plugin */

if( function_exists('register_uninstall_hook') ){
	register_uninstall_hook(__FILE__,'ai_contact_form_uninstall');			
}

function ai_contact_form_uninstall(){ 
	delete_option('ai_email_address_setting');
	delete_option('ai_enable_captcha');
	delete_option('ai_captcha_site_key' ); 
	delete_option('ai_captcha_secret_key' ); 
	delete_option('ai_error_setting');	
	delete_option('ai_subject_text');
	delete_option('ai_reply_user_message');
	delete_option('ai_visible_name');
	delete_option('ai_enable_require_name');
	delete_option('ai_visible_phone');
	delete_option('ai_enable_require_phone');
	delete_option('ai_visible_email');
	delete_option('ai_visible_subject');
	delete_option('ai_enable_require_subject');
	delete_option('ai_visible_website');
	delete_option('ai_enable_require_website');
	delete_option('ai_visible_comment');	
	delete_option('ai_custom_css' );
	delete_option('ai_enable_require_comment');		 
	delete_option('ai_visible_sendcopy');	 
	delete_option('ai_rm_user_list');	 
	delete_option('ai_success_message');	 

	global $wpdb;	
	$ai_table_contact_drop = $wpdb->prefix . "ai_contact";  
	$wpdb->query("DROP TABLE IF EXISTS ".$ai_table_contact_drop);
}

add_shortcode('ai_contact_form', 'ai_shortcode');
function ai_shortcode(){
	include_once('include/ai-contact-form-template.php');
	return contactFormShortcode();
}

/* Make AI Contact Settings in Admin Menu Item*/
add_action('admin_menu','ai_contact_setting');

/*
* Setup Admin menu item
*/
function ai_contact_setting(){
	add_menu_page(__('AI Contact Form','aicontactform'),__('AI Contact Form','aicontactform'),'manage_options','ai_contact','ai_contact_settings','','79.5');
	$ai_enable_user = get_option('ai_rm_user_list');	
	if($ai_enable_user != 'on'){
	   global $page_options;
	   $page_options = add_submenu_page('ai_contact', __('User List','aicontactform'), __('User List','aicontactform'),'manage_options', 'ai_user_lists', 'ai_user_list');
	}   
}

/*
* Admin menu icons
*/
add_action( 'admin_head', 'ai_cf_add_menu_icons_styles' );
function ai_cf_add_menu_icons_styles() { ?>
	<style type="text/css" media="screen">
		#adminmenu .toplevel_page_ai_contact div.wp-menu-image:before {
			content: '\f314';
		}
	</style>
<?php }

add_action('admin_enqueue_scripts', 'ai_load_admin_scripts');
function ai_load_admin_scripts($hook) {
	global $page_options;
	if( $hook != $page_options )
		return;
	wp_register_style( 'jquery-ui',  '//code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );	
	wp_enqueue_style('jquery-ui');
}

function ai_add_contact_table(){	
	global $wpdb;
	
	$ai_table_contact = $wpdb->prefix . "ai_contact";			
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');	  
	
	$wpdb->query("DROP TABLE IF EXISTS ".$ai_table_contact);

	$ai_sql_contact = "CREATE TABLE IF NOT EXISTS $ai_table_contact (
		user_id int(10) NOT NULL AUTO_INCREMENT,
		username varchar(50) NULL,
		email_id varchar(255) NULL,	
		message varchar(1000) NULL,
		contact_date date NULL,					  					  
		PRIMARY KEY (`user_id`)
	) ";

	dbDelta($ai_sql_contact);

}

function ai_contact_settings(){
	include AI_PDIR_PATH."/include/ai_settings.php";
}

function ai_user_list(){
	include AI_PDIR_PATH."/include/ai_user_list.php";
}

function ai_scripts(){
	if(isset($_GET['page']) && preg_match('/^ai_/', @$_GET['page']) ){
		wp_enqueue_script( 'ai_script', plugins_url( '/js/ai_script.js' , __FILE__ ) );		
		wp_enqueue_script( 'ai_script_table', plugins_url('/js/jquery.dataTables.js' , __FILE__), array( 'jquery' ) );
		wp_enqueue_script( 'jquery-ui', plugins_url('/js/jquery-ui.js' , __FILE__), array( 'jquery' ) );
		wp_enqueue_style('wp-datatable',  plugins_url('/responsive-contact-form/css/data_table.css'));
		wp_enqueue_style('jquery-ui');
	}
}  
add_action( 'admin_enqueue_scripts', 'ai_scripts' );

if(!is_admin()){
	wp_localize_script( 'my-ajax-request', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );	
}

add_action('wp_ajax_ai_action', 'ai_action_call');
add_action('wp_ajax_nopriv_ai_action', 'ai_action_call');
function ai_action_call(){	
	global $wpdb;
	$data = $_POST['fdata'];
	$returndata = array();
	if(empty($data)) return;
	$strArray = explode("&", $data);
	$i = 0;

	foreach($strArray as $item){
		$array = explode("=", $item);
		$returndata[$array[0]] = $array[1];
	}
	
	foreach($returndata as $key => $val){
		if($key == 'ai_name'){
			$ai_name = sanitize_text_field(urldecode($val));
		} elseif($key == 'ai_phone') {
			$ai_phone = urldecode($val);
		} elseif($key == 'ai_email') {
			$ai_email =  sanitize_email(urldecode($val));
		} elseif($key == 'ai_website') {
			$ai_website = urldecode($val);
		} elseif($key == 'ai_subject') {
			$ai_subject = sanitize_text_field(urldecode($val));
		} elseif($key == 'ai_comment') {
			$ai_comment = sanitize_text_field(urldecode($val));
		} elseif($key == 'ai_recaptcha_response') {
			$ai_recaptcha_response = $val;
		} elseif($key == 'ai_sendcopy') { 
			$sendcopy = $val;
		}		
	}

	if(get_option('ai_email_address_setting')==''){
		$ai_emailadmin = sanitize_email(get_option('admin_email'));	

	} else {
		$ai_emailadmin = get_option('ai_email_address_setting');
	}
        
	if(get_option('ai_subject_text')==''){
		$ai_subtext = __('August Infotech','aicontactform');
	} else {
		$ai_subtext = get_option('ai_subject_text');
	}

	if(get_option('ai_reply_user_message')==''){
		$ai_reply_msg = __('Thank you for contacting us...We will get back to you soon...','aicontactform');
	} else {
		$items_for_replacement = array('{name}','{phone}','{website}','{comment}');
		$replacement_items = array($ai_name,$ai_phone,$ai_website,$ai_comment);
		$ai_reply_msg = get_option('ai_reply_user_message');
		$ai_reply_msg = str_replace($items_for_replacement, $replacement_items, $ai_reply_msg);
	}	

	$arr = 1;
	$enable = get_option('ai_enable_captcha');	
	if($enable == 'on'){
		$arr = 2;
		// Build POST request:
	    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
	    $recaptcha_secret = esc_attr(get_option('ai_captcha_secret_key'));
	    $recaptcha_response = $ai_recaptcha_response;

	    // Make and decode POST request:
	    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
	    $recaptcha = json_decode($recaptcha);

	    if ($recaptcha->score >= 0.5) {
	    	$arr=1;
	    }
	}

	// settings for mail received by user	
	$ai_subject_mail = $ai_subtext;	
	$ai_headers = "MIME-Version: 1.0\n";
	$ai_headers .= "Content-type: text/html; charset=UTF-8\n";
	$ai_headers .= "From:".get_bloginfo('name')." <".$ai_emailadmin.">\n";
	$ai_headers .= "Message-Id: <".time()."@".$_SERVER['SERVER_NAME'].">\n";
	$ai_headers .= "X-Mailer: php-mail-function-0.2\n";

	// settings for mail received by admin			
	$ai_admin_usermsg = "<table><tr><td colspan='2'><b>".__('User Details','aicontactform')."</b></td><tr/><tr><td colspan='2' height='40%'></td></tr>";

	if(esc_attr(get_option('ai_visible_name'))=="on" && $ai_name != ''){
		$ai_admin_usermsg .= "<tr><td align='left' width='80px'>".__('Name :','aicontactform')."</td><td>".$ai_name."</td></tr>";
	} 

	$ai_admin_usermsg .= "<tr><td align='left' width='80px'>".__('Email ID :','aicontactform')." </td><td>".$ai_email."</td></tr>";

	if(esc_attr(get_option('ai_visible_phone'))=="on" && $ai_phone != ''){
		$ai_admin_usermsg .= "<tr><td align='left' width='70px'>".__('Phone :','aicontactform')."</td><td>".$ai_phone."</td></tr>";
	}

	if(esc_attr(get_option('ai_visible_website'))=="on" && $ai_website != ''){
		$ai_admin_usermsg .= "<tr><td align='left' width='80px'>".__('Website Url :','aicontactform')."</td><td>".$ai_website."</td></tr>";
	}

	if(esc_attr(get_option('ai_visible_subject'))=="on" && $ai_subject != ''){ 
		$ai_admin_usermsg .= "<tr><td align='left' width='80px'>".__('Subject :','aicontactform')." </td><td>".$ai_subject."</td></tr>";
	}

	if(esc_attr(get_option('ai_visible_comment'))=="on" && $ai_comment != ''){ 
		$ai_admin_usermsg .= "<tr><td align='left' valign='top' width='70px'>".__('Comment : ','aicontactform')."</td><td>".$ai_comment."</td></tr></table>";		
	}

	if($ai_name == '') {	
		$ai_name = 'User';
	}
	$ai_admin_subject = $ai_name.__(' has contacted us','aicontactform');
	$ai_admin_headers = "MIME-Version: 1.0\n";
	$ai_admin_headers .= "Content-type: text/html; charset=UTF-8\n";	
	$ai_admin_headers .= "From: ".$ai_name." <".$ai_email.">\n";
	$ai_admin_headers .= "Message-Id: <".time()."@".$_SERVER['SERVER_NAME'].">\n";
	$ai_admin_headers .= "X-Mailer: php-mail-function-0.2\n";
    $ai_usercopy_subject = __('Copy of form submitted','aicontactform');

	//check to see if the form be routed via BPMContext - Intranet Plus
    //these fields are required
    $bpm_fields = array('contact-email' => $ai_email, 'contact-subject' => $ai_subject , 'contact-message' => $ai_comment, 'form-id' => 'responsive-contact-form');
    //additional optional fields can be placed here
    if($ai_name) $bpm_fields['additional_fields']['contact-name'] = $ai_name;
    if($ai_phone) $bpm_fields['additional_fields']['phone'] = $ai_phone;
    if($ai_website) $bpm_fields['additional_fields']['website'] = $ai_website;

    global $bpm_contact_form;

    $url = wp_get_referer();
    $bpm_page_id = url_to_postid( $url );

    $bpm_contact_form = array('fields' => $bpm_fields, 'form' => $bpm_page_id );

    do_action( 'bpmcontext_before_send_mail', $bpm_contact_form );

    $skip_mail = false || ! empty( $bpm_contact_form['skip_mail'] );

	//end of BPMContext Intranet Plus check

	if($arr == 1) {		
		wp_mail($ai_email, $ai_subject_mail, $ai_reply_msg, $ai_headers);
		if($sendcopy == 1) {
			wp_mail($ai_email, $ai_usercopy_subject, $ai_admin_usermsg, $ai_admin_headers);
		}

		if (! $skip_mail ) wp_mail($ai_emailadmin, $ai_admin_subject, $ai_admin_usermsg, $ai_admin_headers);
		$date = date("Y-m-d");
		$table_name = $wpdb->prefix."ai_contact";
		$date = current_time( 'mysql' );
		
		// Not Inserted User data into database if user list checkbox is checked.		
		$ai_insert_user = get_option('ai_rm_user_list');			
		if($ai_insert_user != 'on') {
			if($ai_name != 'User' && $ai_name != '') {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name(  username, email_id, message, contact_date )VALUES ( %s, %s,  %s,  %s )",  array( $ai_name, $ai_email, $ai_comment, $date ) ) );
			} else {
				$wpdb->query( $wpdb->prepare( "INSERT INTO $table_name(   email_id, message, contact_date )VALUES (  %s,  %s,  %s )",  array( $ai_email, $ai_comment, $date ) ) );
			}
		}		
		//Added contact request data into mailchimp database if mailchimp extension is active
		
		//Check whether mailchimpl extension for Contact plugin is active or not
		if ( is_plugin_active('responsive-contact-form-mailchimp-extension/ai-responsive-contact-form-mailchimp-extension.php' ) ) {	
			$apikey = esc_attr(get_option('ai_me_contactform_api_key'));
			$active_mail_chimp =  get_option('aimclists') ;		
			require_once( WP_PLUGIN_DIR . '/responsive-contact-form-mailchimp-extension/admin/includes/MailChimp.php');			
			$storedata = new MailChimp($apikey);
			if($ai_name != 'User' && $ai_name != '') {
				$ai_merge_vars = array('FNAME'=>$ai_name);
			} else {
				$ai_merge_vars = array();
			}
			if($active_mail_chimp) {
				foreach($active_mail_chimp as $list_id => $list_val) {			
					
					$result = $storedata->post("lists/$list_id/members", [
						'email_address' => $ai_email,
						'status'        => 'subscribed',
						'merge_fields'  => $ai_merge_vars
					]);
				}
				if ($storedata->getLastError()) {
					echo $storedata->getLastError();
				}
			}	
		}
	}
	echo json_encode($arr);	
	die(); 	
}
?>