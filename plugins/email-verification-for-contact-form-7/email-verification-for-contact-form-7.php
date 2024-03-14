<?php
/*
Plugin Name: Email Verification for Contact Form 7
Description: Fill out the contact form 7 and submit it with an email address that is verified.
Author: Geek Code Lab
Version: 2.4
Author URI: https://geekcodelab.com/
Text Domain : email-verification-for-contact-form-7
*/
if (!defined('ABSPATH')) exit;

if (!defined("EVCF7_PLUGIN_DIR_PATH"))

	define("EVCF7_PLUGIN_DIR_PATH", plugin_dir_path(__FILE__));

if (!defined("EVCF7_PLUGIN_URL"))
    
    define("EVCF7_PLUGIN_URL", plugins_url() . '/' . basename(dirname(__FILE__)));
    
define("EVCF7_BUILD", '2.4');
define("EVCF7_PRO_PLUGIN_URL", 'https://geekcodelab.com/wordpress-plugins/email-verification-for-contact-form-7-pro/');

/**
 * Admin notice
 */
add_action( 'admin_init', 'evcf7_plugin_load' );

function evcf7_plugin_load(){
	if ( ! ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) ) {
		add_action( 'admin_notices', 'evcf7_install_contact_form_7_admin_notice' );
		deactivate_plugins("email-verification-for-contact-form-7/email-verification-for-contact-form-7.php");
		return;
	}
}

function evcf7_install_contact_form_7_admin_notice(){ ?>
	<div class="error">
		<p>
			<?php
			// translators: %s is the plugin name.
			echo esc_html( sprintf( __( '%s is enabled but not effective. It requires Contact Form 7 in order to work.', 'email-verification-for-contact-form-7' ), 'Email Verification for Contact Form 7' ) );
			?>
		</p>
	</div>
	<?php
}

register_activation_hook( __FILE__, 'evcf7_plugin_activate' );
function evcf7_plugin_activate() {

    if (is_plugin_active( 'email-verification-for-contact-form-7-pro/email-verification-for-contact-form-7-pro.php' ) ) {
		deactivate_plugins('email-verification-for-contact-form-7-pro/email-verification-for-contact-form-7-pro.php');
   	}

    global $wpdb; 
    $db_table_name = $wpdb->prefix . 'evcf7_options';  // table name
    $charset_collate = $wpdb->get_charset_collate();

    if($wpdb->get_var( "show tables like '$db_table_name'" ) != $db_table_name ){
        $sql = "CREATE TABLE " . $db_table_name . " (
            id bigint(20) NOT NULL AUTO_INCREMENT, 
            form_id bigint(20) NOT NULL, 
            email varchar(100) NOT NULL, 
            time datetime NOT NULL, 
            otp bigint(6) NOT NULL,
            PRIMARY KEY  (id)
        ) ". $charset_collate .";";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql );
    }

    $defaults = array(
        'verify_button_text'        => 'Click here to verify your mail',
        'invalid_format_message'    => 'Please enter a valid Email Address. E.g:abc@abc.abc',
        'success_otp_message'       => 'A One Time Passcode has been sent to {email} Please enter the OTP below to verify your Email Address. If you cannot see the email in your inbox, make sure to check your SPAM folder.',
        'error_otp_message'         => 'There was an error in sending the OTP. Please verify your email address or contact site Admin.',
        'invalid_otp_message'       => 'Invalid OTP. Please enter a valid OTP.',
        'email_subject'             => '{site_title} - Your OTP',
        'email_content'             => 'Dear Customer, Your OTP is {otp} Use this Passcode to complete your transaction. Thank you.',
        'success_message_color'     => '#46b450',
        'error_message_color'       => '#dc3232'
    );

    $evcf7_options = get_option('evcf7_options');
    if($evcf7_options == false){
        update_option( 'evcf7_options', $defaults );
    }
}

$plugin = plugin_basename(__FILE__);
add_filter( "plugin_action_links_$plugin", 'evcf7_add_plugin_link');
function evcf7_add_plugin_link( $links ) {
    if ( is_plugin_active( 'contact-form-7/wp-contact-form-7.php' ) ) {
        $support_link = '<a href="https://geekcodelab.com/contact/" target="_blank" >' . __( 'Support', 'email-verification-for-contact-form-7' ) . '</a>';
        array_unshift( $links, $support_link );

        $pro_link = '<a href="'.esc_url(EVCF7_PRO_PLUGIN_URL).'"  target="_blank" style="color:#46b450;font-weight: 600;">' . __( 'Premium Upgrade' ) . '</a>'; 
        array_unshift( $links, $pro_link );	
        
        $setting_link = '<a href="'. admin_url('admin.php?page=evcf7-email-verify') .'">' . __( 'Settings', 'email-verification-for-contact-form-7' ) . '</a>';
        array_unshift( $links, $setting_link );
    }

	return $links;
}

/**
 * Admin init hook
 */
add_action( 'admin_init', 'evcf7_admin_init' );
function evcf7_admin_init() {
    global $wpdb; 
    $db_table_name = $wpdb->prefix . 'evcf7_options';  

    // Alt table for old users (includes 1.2 and greater vesrions)
    if( ! function_exists('get_plugin_data') ){
		require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	}
    $plugin_data = get_plugin_data( __FILE__ );
    
    if(isset($plugin_data) && isset($plugin_data["Version"]) >= 1.9 ) {
        if(is_user_logged_in()) {
            $cur_user_id = get_current_user_id();
            $evcf7_alt_table = get_option('evcf7_alt_table');

            if(get_option('evcf7_alt_table')) { 
                // write code here
            }else{ 
                if( $wpdb->get_var( "show tables like '$db_table_name'" ) != $db_table_name ) {
                    // write code here
                }else{
                    $alt_tabler_sql = "ALTER TABLE $db_table_name MODIFY otp VARCHAR(256);";
                    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
                    dbDelta( $alt_tabler_sql );
                    $wpdb->query($alt_tabler_sql);
                    update_option('evcf7_alt_table',1);
                }
            }
        }
    }
}

add_action('wp_ajax_evcf7_verify_email','evcf7_verify_email_ajax');
add_action( 'wp_ajax_nopriv_evcf7_verify_email', 'evcf7_verify_email_ajax' );

function evcf7_verify_email_ajax() {
    
    $form_id = intval($_POST['form_id']);
    $data_email = sanitize_email($_POST['data_email']);
    
    if(!empty($data_email) && !empty($form_id)){
        $otp  = random_int(100000, 999999);

        $site_admin_email = get_option('admin_email');
        $site_title = get_bloginfo('name'); 
        $site_url   = site_url();
        $search     = array('{otp}', '{email}', '{site_title}', '{site_url}', '[_site_title]', '[_site_url]', '[_site_admin_email]');
        $replace    = array('<b>'.$otp.'</b>', $data_email, $site_title, $site_url, $site_title, $site_url, $site_admin_email);
        $cf7_mail_meta = get_post_meta($form_id, '_mail', true);

        $sender = (isset($cf7_mail_meta['sender']) && !empty($cf7_mail_meta['sender'])) ? str_replace($search, $replace, $cf7_mail_meta['sender']) : $site_title .' <'. $site_admin_email.'>';
        
        // sending mail 
        $to = $data_email;
        $headers =  'MIME-Version: 1.0' . "\r\n"; 
        $headers .= 'From: ' . html_entity_decode($sender) . "\r\n";
        $headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        
        $evcf7_options = get_option('evcf7_options');
        $success_otp_msg = $error_otp_msg = $email_subject = $email_content = "";
        if(isset($evcf7_options['success_otp_message']))    $success_otp_msg  = str_replace($search, $replace, sanitize_textarea_field($evcf7_options['success_otp_message']));
        if(isset($evcf7_options['error_otp_message']))      $error_otp_msg    = str_replace($search, $replace, sanitize_textarea_field($evcf7_options['error_otp_message']));
        if(isset($evcf7_options['email_subject']))          $email_subject    = str_replace($search, $replace, sanitize_text_field($evcf7_options['email_subject']));
        if(isset($evcf7_options['email_content']))          $email_content    = str_replace($search, $replace, sanitize_textarea_field($evcf7_options['email_content']));

        $allowed_elemets = array( 'br' => array(), 'strong' => array(), 'b' => array(), 'i' => array(), 'u' => array() );
        $email_html_decode = wp_kses( html_entity_decode($email_content), $allowed_elemets );

        $mail = wp_mail($to,html_entity_decode($email_subject),$email_html_decode,$headers);
        if($mail == true) { ?>
                <p class="evcf7_email_sent"><?php echo nl2br(esc_html($success_otp_msg,'email-verification-for-contact-form-7')); ?></p>
            <?php
            global $wpdb;
            $db_table_name = $wpdb->prefix . 'evcf7_options';
            $cur_time   = time(); 
            $datetime   = date("Y-m-d H:i:s",$cur_time);

            $results = $wpdb->get_results("SELECT * FROM  $db_table_name WHERE form_id='$form_id' AND email='$data_email'");

            if(isset($results) && !empty($results)){
                $wpdb->query( "UPDATE $db_table_name SET time='$datetime', otp='$otp' WHERE email='$data_email' AND form_id='$form_id'");
            }else{
                $data = array('form_id' => $form_id, 'email' => $data_email, 'time' => $datetime, 'otp' => $otp);
                $wpdb->insert($db_table_name,$data);
            }
        }else{ ?>
                <p class="evcf7_error_sending_mail"><?php echo nl2br(esc_html($error_otp_msg,'email-verification-for-contact-form-7')); ?></p>
            <?php
        }

    }
    die;
}


// admin scripts
add_action('admin_enqueue_scripts','evcf7_plugin_admin_scripts');
function evcf7_plugin_admin_scripts( $hook ) {
    if($hook == 'contact_page_evcf7-email-verify') {
        wp_enqueue_style('evcf7-admin-style', plugins_url() . '/' . basename(dirname(__FILE__)) . '/assets/css/admin-style.css', array( 'wp-color-picker' ), EVCF7_BUILD);
        wp_enqueue_script('evcf7-admin-script', plugins_url() . '/' . basename(dirname(__FILE__)) . '/assets/js/admin-script.js', array( 'jquery','wp-color-picker' ), EVCF7_BUILD);
    }
}

// front scripts
add_action('wp_enqueue_scripts','evcf7_plugin_front_scripts');
function evcf7_plugin_front_scripts(){
    wp_enqueue_style('evcf7-front-style', plugins_url() . '/' . basename(dirname(__FILE__)) . '/assets/css/front-style.css', array(), EVCF7_BUILD);
    wp_enqueue_script('evcf7-front-script', plugins_url() . '/' . basename(dirname(__FILE__)) . '/assets/js/front-script.js', array( 'jquery' ), EVCF7_BUILD, true);
    $evcf7_options = get_option('evcf7_options');
    wp_localize_script( 'evcf7-front-script', 'evcf7Obj', array('ajaxurl' => admin_url( 'admin-ajax.php' ), 'evcf7_options' => $evcf7_options) );
}

require_once(EVCF7_PLUGIN_DIR_PATH . 'admin/settings.php');
require_once(EVCF7_PLUGIN_DIR_PATH . 'functions.php');
require_once(EVCF7_PLUGIN_DIR_PATH . 'admin/class-admin.php');