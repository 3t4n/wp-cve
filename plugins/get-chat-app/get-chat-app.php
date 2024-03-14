<?php
/*
Plugin Name: Get Chat App
Plugin URI: https://getchat.app
Description: Add a WhatsApp chat button to your website in seconds. Allow visitors to simply tap to chat through WhatsApp.
Version: 1.2.02
Author: Code Cabin
Author URI: https://www.codecabin.io
Text Domain: getchatapp
Domain Path: /languages
*/

/*
 * 1.2.02 - 2023-12-11
 * Refactored code
 * Updated the admin interface and styling
 * Added X button support
 * Added TikTok button support
 * Added LinkedIn button support
 * Added Phone button support
 * Added Custom Link button support
 * Added multiple contacts support
 *
 * 1.2.01 - 2023-10-16
 * Fixed readme file
 * Changed source of SVG image to local
 * 
 * 1.2.00 - 2023-10-02
 * Refactored code
 * Added input helpers
 * Updated the plugin logo/icon
 * Updated the mail button icon
 * Removed Facebook iframe
 * Added Messenger chat
 * Added custom message for Messenger
 * Added reply time for Messenger
 * Added Instagram button support
 * Added Telegram button support
 * 
 * 1.1.00 - 2020-10-29
 * Added in support for Facebook and Email buttons
 * 
 * 1.0.05 - 2020-10-15
 * TrustedOrigin added
 * 
 * 1.0.04 - 2020-09-30
 * Added a support link on the main settings page
 * 
 * 1.0.03 - 2020-09-23
 * Fixed a bug where the demo wasnt updating properly
 * Fixed a styling bug
 * Added support for the new pro feature - Custom Icon
 * 
 * 1.0.02 - 2020-09-14
 * Added UT8 support for some databases that didnt like storing the Emojis
 * 
 * 1.0.01 - 2020-08-31
 * Changes to the Pro menu
 * 
 * 1.0.00 - 2020-08-28
 * Launch!
 * 
 
*/

if ( ! defined( 'ABSPATH' ) ) exit;

global $gcap_version;
global $gcap_version_string;

$gcap_version = "1.2.02";
$gcap_version_string = "Basic";

define( 'GCAP_PLUGIN_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'GCAP_PLUGIN_DIR_PATH', plugin_dir_path(__FILE__) );

/* On Activate */
register_activation_hook( __FILE__, 'gcap_activated');
function gcap_activated() {
	
}

/* On Deactivate */
register_deactivation_hook(__FILE__, 'gcap_deactivated');
function gcap_deactivated() {
	
}

/* On Uninstall */
register_uninstall_hook(__FILE__, 'gcap_uninstalled');
function gcap_uninstalled() {

}

// Add menu items
add_action('admin_menu', 'gcap_add_menu_items');
function gcap_add_menu_items(){
	$icon = GCAP_PLUGIN_DIR_URL . 'assets/img/whatsapp-menu-icon.png';
	add_menu_page( 'GetChatApp', 'Get Chat App', 'manage_options', 'gcap', 'gcap_admin_page', $icon );
}

// Admin settings page
function gcap_admin_page(){
	include_once(GCAP_PLUGIN_DIR_PATH . 'html/admin.html.php');
}

// Pro features button HTML
add_action('gcap_pro_features_show_button','gcap_pro_features_basic_show_button', 10);
function gcap_pro_features_basic_show_button($options) {
	include_once(GCAP_PLUGIN_DIR_PATH . 'html/pro-button.html.php');
}	

// Pro features HTML
add_action('gcap_pro_features','gcap_upsell_pro_features', 10);
function gcap_upsell_pro_features($options) {
	include_once(GCAP_PLUGIN_DIR_PATH . 'html/pro-features.html.php');
}	

// Demo HTML
add_action('gcap_demo','gcap_demo', 10);
function gcap_demo($options) {
	include_once(GCAP_PLUGIN_DIR_PATH . 'html/demo.html.php');
}	


// Save settings JS callback
add_action( 'wp_ajax_gcapSaveSettings', 'gcap_ajax_callback' );
add_action( 'wp_ajax_nopriv_gcapSaveSettings', 'gcap_ajax_callback' );
function gcap_ajax_callback() {
	$check = check_ajax_referer('gcap', 'security');
	if ($check == 1) {
		if ($_POST['action'] == "gcapSaveSettings" ) {
			
			unset( $_POST['action'] );
			unset( $_POST['security'] );

			$tags = array();
			$tags['br'] = array();

			$data_store = array();
			
			if ( isset( $_POST['status'] ) ) { $data_store['status'] = sanitize_text_field( $_POST['status'] ); }

			if ( isset( $_POST['whatsapp'] ) ) { $data_store['whatsapp'] = sanitize_text_field( $_POST['whatsapp'] ); }
			if ( isset( $_POST['facebook'] ) ) { $data_store['facebook'] = sanitize_text_field( $_POST['facebook'] ); }
			if ( isset( $_POST['email'] ) ) { $data_store['email'] = sanitize_text_field( $_POST['email'] ); } 
			if ( isset( $_POST['instagram'] ) ) { $data_store['instagram'] = sanitize_text_field( $_POST['instagram'] ); } 
			if ( isset( $_POST['telegram'] ) ) { $data_store['telegram'] = sanitize_text_field( $_POST['telegram'] ); } 
			if ( isset( $_POST['tiktok'] ) ) { $data_store['tiktok'] = sanitize_text_field( $_POST['tiktok'] ); } 
			if ( isset( $_POST['x'] ) ) { $data_store['x'] = sanitize_text_field( $_POST['x'] ); } 
			if ( isset( $_POST['linkedin'] ) ) { $data_store['linkedin'] = sanitize_text_field( $_POST['linkedin'] ); } 
			if ( isset( $_POST['phone'] ) ) { $data_store['phone'] = sanitize_text_field( $_POST['phone'] ); } 
			if ( isset( $_POST['customLink'] ) ) { $data_store['customLink'] = sanitize_text_field( $_POST['customLink'] ); } 
			
			if ( isset( $_POST['mobileNumber'] ) ) { $data_store['mobileNumber'] = sanitize_text_field( $_POST['mobileNumber'] ); }
			if ( isset( $_POST['titleMessage'] ) ) { $data_store['titleMessage'] = wp_encode_emoji( sanitize_text_field( $_POST['titleMessage'] ) ); }
			if ( isset( $_POST['welcomeMessage'] ) ) { $data_store['welcomeMessage'] = wp_encode_emoji( wp_kses( trim( $_POST['welcomeMessage'] ) , $tags ) ); }
			
			if ( isset( $_POST['facebookPageId'] ) ) { $data_store['facebookPageId'] = sanitize_text_field( $_POST['facebookPageId'] ); }
			if ( isset( $_POST['facebookMessage'] ) ) { $data_store['facebookMessage'] = wp_encode_emoji( wp_kses( trim( $_POST['facebookMessage'] ) , $tags ) ); }
			if ( isset( $_POST['facebookReplyTime'] ) ) { $data_store['facebookReplyTime'] = sanitize_text_field( $_POST['facebookReplyTime'] ); }

			if ( isset( $_POST['gcaEmailAddress'] ) ) { $data_store['gcaEmailAddress'] = sanitize_text_field( $_POST['gcaEmailAddress'] ); }
			if ( isset( $_POST['gcaEmailSubject'] ) ) { $data_store['gcaEmailSubject'] = sanitize_text_field( $_POST['gcaEmailSubject'] ); }

			if ( isset( $_POST['gcaInstagramUsername'] ) ) { $data_store['gcaInstagramUsername'] = sanitize_text_field( $_POST['gcaInstagramUsername'] ); }
			
			if ( isset( $_POST['gcaTelegramUsername'] ) ) { $data_store['gcaTelegramUsername'] = sanitize_text_field( $_POST['gcaTelegramUsername'] ); }
			
			if ( isset( $_POST['gcaTiktokUsername'] ) ) { $data_store['gcaTiktokUsername'] = sanitize_text_field( $_POST['gcaTiktokUsername'] ); }

			if ( isset( $_POST['gcaXUsername'] ) ) { $data_store['gcaXUsername'] = sanitize_text_field( $_POST['gcaXUsername'] ); }

			if ( isset( $_POST['gcaLinkedinUsername'] ) ) { $data_store['gcaLinkedinUsername'] = sanitize_text_field( $_POST['gcaLinkedinUsername'] ); }

			if ( isset( $_POST['gcaPhoneNumber'] ) ) { $data_store['gcaPhoneNumber'] = sanitize_text_field( $_POST['gcaPhoneNumber'] ); }

			if ( isset( $_POST['gcaCustomLink'] ) ) { $data_store['gcaCustomLink'] = sanitize_url( $_POST['gcaCustomLink'] ); }

			if ( isset( $_POST['position'] ) ) { $data_store['position'] = sanitize_text_field( $_POST['position'] ); }
				
			$data_store = apply_filters("gcap_filter_save_settings", $data_store);		
						
			update_option( 'gcap_settings', $data_store );
			wp_die('true');
		}
	}
}


// Load admin styles
add_action( 'admin_enqueue_scripts', 'gcap_load_admin_styles' );
function gcap_load_admin_styles() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'gcap' ) {
		wp_enqueue_style( 'admin_css_gcap', GCAP_PLUGIN_DIR_URL . 'assets/css/admin-style.min.css', false, '1.2.02' );
		wp_enqueue_style( 'admin_css_gcap_flexbox', GCAP_PLUGIN_DIR_URL . 'assets/css/flexboxgrid.min.css', false, '1.2.02' );
	}
} 


// Load admin scripts
add_action( 'admin_enqueue_scripts', 'gcap_load_admin_scripts' );
function gcap_load_admin_scripts() {
	if ( isset( $_GET['page'] ) && $_GET['page'] == 'gcap' ) {
		$gcap_ajaxurl = admin_url('admin-ajax.php');
		$gcap_nonce = wp_create_nonce("gcap");
		wp_enqueue_script( 'gcap_admin', GCAP_PLUGIN_DIR_URL . 'assets/js/gca_admin.js', false, '1.2.02', false );
		wp_localize_script( 'gcap_admin', 'gcap_ajaxurl', $gcap_ajaxurl );
		wp_localize_script( 'gcap_admin', 'gcap_nonce', $gcap_nonce );
		wp_localize_script( 'gcap_admin', 'gcap_plugin_url', GCAP_PLUGIN_DIR_URL );
	}
}



// Instantiate our GetChatApp class from site - For frontend 
add_action( 'wp_footer', 'gcap_instatiate_gca_class', 10 );
function gcap_instatiate_gca_class() {

	$options = get_option('gcap_settings');
	
	if ($options['status']) { $enabled = $options['status']; } else { $enabled = '0'; }
	if ($options['whatsapp']) { $whatsapp = $options['whatsapp']; } else { $whatsapp = 'false'; }
	if ($options['facebook']) { $facebook = $options['facebook']; } else { $facebook = 'false'; }
	if ($options['email']) { $email = $options['email']; } else { $email = 'false'; }
	if ($options['instagram']) { $instagram = $options['instagram']; } else { $instagram = 'false'; }
	if ($options['telegram']) { $telegram = $options['telegram']; } else { $telegram = 'false'; }
	if ($options['tiktok']) { $tiktok = $options['tiktok']; } else { $tiktok = 'false'; }
	if ($options['x']) { $x = $options['x']; } else { $x = 'false'; }
	if ($options['linkedin']) { $linkedin = $options['linkedin']; } else { $linkedin = 'false'; }
	if ($options['phone']) { $phone = $options['phone']; } else { $phone = 'false'; }
	if ($options['customLink']) { $customLink = $options['customLink']; } else { $customLink = 'false'; }
	
	if ($options['mobileNumber']) { $mobileNumber = $options['mobileNumber']; } else { $mobileNumber = ''; }
	if ($options['titleMessage']) { $titleMessage = $options['titleMessage']; } else { $titleMessage = '👋 Chat with me on WhatsApp!'; }
	if ($options['welcomeMessage']) { $welcomeMessage = $options['welcomeMessage']; } else { $welcomeMessage = "Hey there!🙌\n\nGet in touch with me by typing a message here. It will go straight to my phone! 🔥\n\n~Your Name"; }
	
	if ($options['facebookPageId']) { $facebookPageId = $options['facebookPageId']; } else { $facebookPageId = ''; }
	if ($options['facebookMessage']) { $facebookMessage = $options['facebookMessage']; } else { $facebookMessage = "Hey there!\n\nHow can I help you today?"; }
	if ($options['facebookReplyTime']) { $facebookReplyTime = $options['facebookReplyTime']; } else { $facebookReplyTime = "a day"; }
	
	if ($options['gcaEmailAddress']) { $gcaEmailAddress = $options['gcaEmailAddress']; } else { $gcaEmailAddress = ''; }
	if ($options['gcaEmailSubject']) { $gcaEmailSubject = $options['gcaEmailSubject']; } else { $gcaEmailSubject = ''; }

	if ($options['gcaInstagramUsername']) { $gcaInstagramUsername = $options['gcaInstagramUsername']; } else { $gcaInstagramUsername = ''; }

	if ($options['gcaTelegramUsername']) { $gcaTelegramUsername = $options['gcaTelegramUsername']; } else { $gcaTelegramUsername = ''; }

	if ($options['gcaTiktokUsername']) { $gcaTiktokUsername = $options['gcaTiktokUsername']; } else { $gcaTiktokUsername = ''; }

	if ($options['gcaXUsername']) { $gcaXUsername = $options['gcaXUsername']; } else { $gcaXUsername = ''; }

	if ($options['gcaLinkedinUsername']) { $gcaLinkedinUsername = $options['gcaLinkedinUsername']; } else { $gcaLinkedinUsername = ''; }

	if ($options['gcaPhoneNumber']) { $gcaPhoneNumber = $options['gcaPhoneNumber']; } else { $gcaPhoneNumber = ''; }

	if ($options['gcaCustomLink']) { $gcaCustomLink = $options['gcaCustomLink']; } else { $gcaCustomLink = ''; }
	
	
	if ($options['position']) { $position = $options['position']; } else { $position = 'right'; }

	
	if($whatsapp == 'whatsapp'){
		$whatsapp = 'true';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
	}
	if($facebook == 'facebook'){
		$whatsapp = 'false';
		$facebook = 'true';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
	}
	if($email == 'email'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'true';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
	}
	if($instagram == 'instagram'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'true';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
	}
	if($telegram == 'telegram'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'true';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
	} 
	if($tiktok == 'tiktok'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'true';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
	} 
	if($x == 'x'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'true';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'false';
		
	} 
	if($linkedin == 'linkedin'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'true';
		$phone = 'false';
		$customLink = 'false';
		
	} 
	if($phone == 'phone'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'true';
		$customLink = 'false';
		
	} 
	if($customLink == 'customLink'){
		$whatsapp = 'false';
		$facebook = 'false';
		$email = 'false';
		$instagram = 'false';
		$telegram = 'false';
		$tiktok = 'false';
		$x = 'false';
		$linkedin = 'false';
		$phone = 'false';
		$customLink = 'true';
		
	} 
	
	
	if ($mobileNumber == '' && $whatsapp == 'true' ||
			$facebookPageId == '' && $facebook == 'true' ||
			$gcaEmailAddress == '' && $email == 'true' ||
			$gcaInstagramUsername == '' && $instagram == 'true' ||
			$gcaTelegramUsername == '' && $telegram == 'true' ||
			$gcaTiktokUsername == '' && $tiktok == 'true' ||
			$gcaXUsername == '' && $x == 'true' ||
			$gcaLinkedinUsername == '' && $linkedin == 'true' ||
			$gcaPhoneNumber == '' && $phone == 'true' ||
			$gcaCustomLink== '' && $customLink == 'true' ||
			$enabled == '0'
	) {
		?>

		<script>
			console.log("GCA button disabled : Disabled or Required field missing");
		</script>

		<?php
		return false;
	} else {

		wp_enqueue_script( 'get-chat-app', 'https://getchat.app/assets/js/wp/min/getchatapp.js', false,  '1.2.02', false );
		
		?>


		<script>
			document.addEventListener("DOMContentLoaded", function() {
				var gcaMain = new GetChatApp({
					'platforms' : {
						'whatsapp' : <?php echo esc_js($whatsapp); ?>,
						'facebook' : <?php echo esc_js($facebook); ?>,
						'email' :  <?php echo esc_js($email); ?>,
						'instagram' : <?php echo esc_js($instagram); ?>,
						'telegram' : <?php echo esc_js($telegram); ?>,
						'tiktok' : <?php echo esc_js($tiktok); ?>,
						'x' : <?php echo esc_js($x); ?>,
						'linkedin' : <?php echo esc_js($linkedin); ?>,
						'phone' : <?php echo esc_js($phone); ?>,
						'customLink' : <?php echo esc_js($customLink); ?>,
					},
					
					'mobileNumber' : '<?php echo esc_js($mobileNumber); ?>',
					'titleMessage' : '<?php echo esc_js($titleMessage); ?>',
					'welcomeMessage': '<?php echo esc_js($welcomeMessage); ?>',

					'facebookPageId' : '<?php echo esc_js($facebookPageId); ?>',
					'facebookMessage': '<?php echo esc_js($facebookMessage); ?>',
					'facebookReplyTime' : '<?php echo esc_js($facebookReplyTime); ?>',

					'gcaEmailAddress' : '<?php echo esc_js($gcaEmailAddress); ?>',
					'gcaEmailSubject' : '<?php echo esc_js($gcaEmailSubject); ?>',

					'gcaInstagramUsername' : '<?php echo esc_js($gcaInstagramUsername); ?>',

					'gcaTelegramUsername' : '<?php echo esc_js($gcaTelegramUsername); ?>',
					
					'gcaTiktokUsername' : '<?php echo esc_js($gcaTiktokUsername); ?>',
					
					'gcaXUsername' : '<?php echo esc_js($gcaXUsername); ?>',
					
					'gcaLinkedinUsername' : '<?php echo esc_js($gcaLinkedinUsername); ?>',
					
					'gcaPhoneNumber' : '<?php echo esc_js($gcaPhoneNumber); ?>',
					
					'gcaCustomLink' : '<?php echo esc_js($gcaCustomLink); ?>',
					
					'position' : '<?php echo esc_js($position); ?>',
				});
			});
		</script>

		<?php
	}

}