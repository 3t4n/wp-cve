<?php
/**
* Plugin Name: web2application
* Plugin URI:  https://wordpress.org/plugins/web2application/
* Description: Web2application Convert your website to android and IOS apps with push notifications , web push , free ajax products search for woocommerce and many more advanced features
* Version: 5.1
* Author: Tzin Nir
* Author URI:  https://web2application.com
* License:     GPL2
* Text Domain: web2application
**/


// Exit if Accessed Directly
if(!defined('ABSPATH')){
	exit;
}

ini_set('allow_url_fopen', 'on');
ini_set('display_errors', 'off');
ini_set('error_reporting', E_ERROR);

//define('WP_DEBUG', false);
//define('WP_DEBUG_DISPLAY', false);

define('W2A_VERSION', '5.1');
define('W2A_TEXTDOMAIN', 'web2application');


define( 'W2A_PLUGIN', '/web2application/');

// directory define
define( 'W2A_PLUGIN_DIR', WP_PLUGIN_DIR.W2A_PLUGIN);
define( 'W2A_APP_DATA_DIR', W2A_PLUGIN_DIR.'appdata/' );

// BIC Code

require_once plugin_dir_path(__FILE__) . 'search/search.php';
require_once plugin_dir_path(__FILE__) . 'search/show.php';
require_once plugin_dir_path(__FILE__) . 'search/elementor-widget-cat.php';
require_once plugin_dir_path(__FILE__) . 'order/order.php';
require_once plugin_dir_path(__FILE__) . 'search/wordpress-widget.php';

// register elementor widget
/**
 * Register oEmbed Widget.
 *
 * Include widget file and register widget class.
 *
 * @since 1.0.0
 * @param \Elementor\Widgets_Manager $widgets_manager Elementor widgets manager.
 * @return void
 */
function register_oembed_widget( $widgets_manager ) {

	require_once( __DIR__ . '/search/elementor-widget.php' );

	$widgets_manager->register( new \Elementor_oEmbed_Widget() );

}
add_action( 'elementor/widgets/register', 'register_oembed_widget' );


// register wp bakery widget
// require_once('wp-bakery-widget.php');

//BIC Code End

// Global Options Variable
$w2a_options = get_option('w2a_settings');


// Load Scripts
require_once(plugin_dir_path(__FILE__).'includes/web2application-scripts.php');


// Load Content
require_once(plugin_dir_path(__FILE__).'/includes/web2application-content.php');

/* text domain */
add_action( 'plugins_loaded', 'w2a_load_textdomain' );
function w2a_load_textdomain() {
	load_plugin_textdomain( 'web2application', false, basename(dirname(__FILE__)) . '/languages' ); //Loads plugin text domain for the translation
	do_action('web2application');
}
/* text domain */

register_activation_hook( __FILE__,  'w2a_install' );
function w2a_install() {
	global $wpdb;
	$path = W2A_APP_DATA_DIR."/web2appdata.json";
	$appData = '';
	// create a file
	$file = fopen($path, "w");
	fwrite($file, $appData);
	fclose($file);
}

register_deactivation_hook( __FILE__, 'w2a_deactivation' );
function w2a_deactivation() {
	// deactivation process here
}


add_action('admin_menu','web2app_addMenu');

function web2app_addMenu() {
	// the if is for add it only to the admin area
	if(is_admin()){

		$label_webapplication = __('Web2apllication', 'web2application');
		$label_send_push = __('Send Push', 'web2application');
		$label_members_club = __('App Members Club', 'web2application');
		$label_tab_menu_links = __('Tab Menu Links', 'web2application');
		$label_marketing_tools = __('Marketing Tools', 'web2application');
		$label_premium_settings = __('Premium Settings', 'web2application');
		$label_web_push_settings = __('Web Push Settings', 'web2application');
		$label_webapplication_site = __('Web2application site', 'web2application');
		$label_reminder = __('Cart Reminder', 'web2application');

		// page title , menu title, capebilty (who can reach), menu slug Url,the fuction that contain the page, position
		//add_menu_page('Web2apllication', 'Web2Application', 4, 'Web2apllication-main', 'web2applicationMainTab');
		add_menu_page('Web2apllication', $label_webapplication, 4, 'Web2apllication-main', 'w2a_general_settings');

		add_submenu_page('Web2apllication-main','Send Push', $label_send_push ,4, 'send-push', 'w2a_sendPushPage');

		add_submenu_page('Web2apllication-main','App Members Club', $label_members_club ,4, 'web2application-app-members-club', 'w2a_members_club');

		add_submenu_page('Web2apllication-main','Tab Menu Links', $label_tab_menu_links ,4,'web2application-tab-menus','w2a_tab_menus');

		add_submenu_page('Web2apllication-main','Marketing Tools', $label_marketing_tools ,4,'web2application-marketing-tools','w2a_marketing_tools');

		add_submenu_page('Web2apllication-main','Premium Settings', $label_premium_settings ,4,'web2application-premium-settings','w2a_premium_settings');

		add_submenu_page('Web2apllication-main','Web Push Settings', $label_web_push_settings ,4,'web2application-web-push-settings','w2a_web_push_settings');

		add_submenu_page('Web2apllication-main','Web2application site', $label_webapplication_site ,4,'Web2apllication-main-2','web2applicationMainTab');

		// add_submenu_page('Web2apllication-main','Cart Reminder', $label_reminder ,4,'Web2apllication-reminder','web2applicationReminder');

		// add_submenu_page('Web2apllication-main','WooCommerce Setting','WooCommerce Setting',4,'web2application-woocommerce-settings','web2_woocommerce_settings');

// 		add_submenu_page('Web2apllication-main','Users Engagement','Search History',4,'web2application-user-engagement','web2_user_engagement');

		// must register to DB in this section when the plugin start
		add_action('admin_init', 'w2a_register_settings');

		add_action('admin_notices', 'w2a_notify_error_notice' );

		//copy the setting files only when entering the settings or updating the web push settings
		$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
		if (strpos($url,'Web2apllication-main') !== false || strpos($url,'web-push-settings') !== false) {
			// load functions of files
				update_web2app_id();
				ios_universal_link_load();
				web_push_files_load();
		//	echo 'file copied';
		} else {
		//	echo 'NO files copied.';
		}

	}
}



function web2applicationMainTab() {
	//require_once(wp_nonce_url(plugin_dir_path(__FILE__).'/includes/web2application-main-screen.php','w2a_nonces'));
	require_once(plugin_dir_path(__FILE__).'/includes/web2application-main-screen.php');
}

function web2applicationReminder() {
	require_once(plugin_dir_path(__FILE__).'/includes/web2application-reminder.php');
}

function w2a_sendPushPage() {
	require_once(plugin_dir_path(__FILE__).'/includes/web2application-sendpush-screen.php');
}

function w2a_members_club() {
	require_once(plugin_dir_path(__FILE__).'/includes/web2application-app-members-club.php');
}

function w2a_tab_menus() {
	   require_once(plugin_dir_path(__FILE__).'/includes/web2application-tab-menus.php');
}

function w2a_marketing_tools() {
	   require_once(plugin_dir_path(__FILE__).'/includes/web2application-marketing-tools.php');
}

function w2a_general_settings() {
	   require_once(plugin_dir_path(__FILE__).'/includes/web2application-settings.php');
}

function w2a_premium_settings() {
	   require_once(plugin_dir_path(__FILE__).'/includes/web2application-premium-settings.php');
}

function w2a_web_push_settings() {
	   require_once(plugin_dir_path(__FILE__).'/includes/web2application-web-push-settings.php');
}

function web2_woocommerce_settings() {
	   require_once(plugin_dir_path(__FILE__).'/includes/web2application-woocommerce-settings.php');
}

// function web2_woocommerce_orders() {
//     require_once(plugin_dir_path(__FILE__).'/includes/web2application-woocommerce-orders.php');
// }

function w2a_register_settings(){
	//register to the with list
	register_setting('w2a_settings_group', 'w2a_settings');

}

function w2a_notify_error_notice() {
	// check if has api_key
	check_api_key_existence();

	// check if api_key is valid
	validate_api_key();

	// check required version of woocommerce
	$woocommerce_version_required = '3.7.0';
	if ( class_exists( 'Woocommerce' ) && !version_compare(WOOCOMMERCE_VERSION, $woocommerce_version_required, '>=') ) {
	    add_action('admin_notices', 'woocommercer_fail_load_out_of_date');
        return;
	}
}

function woocommerce_fail_load_out_of_date() {
    if (!current_user_can('update_plugins')) {
        return;
    }

    $file_path = 'woocommerce/woocommerce.php';

    $upgrade_link = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
    $message = '<p>' . __('Web2Application is not working because you are using an old version of WooCommercer.', 'web2application') . '</p>';
    $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update WooCommerce Now', 'web2application')) . '</p>';

    echo '<div class="error">' . $message . '</div>';
}

/**
 * Load In-App Visibility
 *
 * Load the plugin after Elementor (and other plugins) are loaded.
 *
 * @since 1.0.0
 */
function inapp_visibility_for_elementor_load() {
	global $w2a_options;

    // Load localization file
    load_plugin_textdomain('web2application');

	// Check if elementor feature is enabled
	if ( isset( $w2a_options['w2a_disable_elementor'] ) && $w2a_options['w2a_disable_elementor'] != "1") {
		// Check if elementor is installed and active
		if (!isElementorActive()) {
			if ( !current_user_can( 'activate_plugins' ) ) {
				return;
			}

			// Notice if the Elementor is not active
			if (!did_action('elementor/loaded')) {
				add_action('admin_notices', 'inapp_visibility_for_elementor_fail_load');
				return;
			}

			// Check required version
		   $elementor_version_required = '1.8.0';
		   if (!version_compare(ELEMENTOR_VERSION, $elementor_version_required, '>=')) {
			   add_action('admin_notices', 'inapp_visibility_for_elementor_fail_load_out_of_date');
			   return;
		   }
		}

		// Require the main plugin file
		require( __DIR__ . '/plugin.php' );
	}
}
add_action('plugins_loaded', 'inapp_visibility_for_elementor_load');

// function to display if elementor is out of date
function inapp_visibility_for_elementor_fail_load_out_of_date() {
    if (!current_user_can('update_plugins')) {
        return;
    }

    $file_path = 'elementor/elementor.php';

    $upgrade_link = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
    $message = '<p>' . __('Web2Application is not working because you are using an old version of Elementor.', 'web2application') . '</p>';
    $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update Elementor Now', 'web2application')) . '</p>';

    echo '<div class="error">' . $message . '</div>';
}

// function to  display if elementor fail to load
function inapp_visibility_for_elementor_fail_load() {
  if ( ! current_user_can( 'activate_plugins' ) ) {
	  return;
  }

	$plugin = 'elementor/elementor.php';

	$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );
	$message = '<p>' . __( 'In order to use Web2Application features in elementor, please activate the Elementor plugin. For more help how elementor can help build your app please read <a href="https://web2application.com/how-to-build-an-app-with-the-popular-wordpress-page-editor-elementor/" target="blank">THIS ARTICLE</a>', 'web2application' ) . '</p>';
	$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, __( 'Activate Elementor Now', 'web2application' ) ) . '</p>';

	echo '<div class="update notice is-dismissible"><p>' . $message . '</p></div>';
}

// function to check if elementor is installed and active
function isElementorActive() {
    if(in_array('elementor/elementor.php', apply_filters('active_plugins', get_option('active_plugins')))){
        return true;
    }
    return false;
}

/**
 * Check API Key if exists
 */
function check_api_key_existence() {
	global $w2a_options;

	if (isset($w2a_options['w2a_api_key'])) {
		if (trim($w2a_options['w2a_api_key']) == "" ) {
			?>
			<div class="error">
				<p><?php _e( 'Web2Application plugin not set. Please go to Web2application -> Setting and fix your API key', 'web2application' ); ?></p>
			</div>
			<?php
		}
	}
}


/**
 * GET Web2App id
 */
function update_web2app_id() {
	global $w2a_options;

	// get appId
	$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';
	$newAppId = http_get_content($url);


	// check
	if ($newAppId != 'Wrong API. Please Check Your API Key' && is_numeric($newAppId)) {
		// create file to save the $appId
		$path = $_SERVER['DOCUMENT_ROOT']."/web2app-id";

		// create a file
		$file = fopen($path, "w");
		fwrite($file, $newAppId);
		fclose($file);

		return $newAppId;
	} else {
		return 0;
	}
}


/**
 * Load Web2App id
 */
function get_web2app_id() {
	$path = $_SERVER['DOCUMENT_ROOT']."/web2app-id";
	$appId = 0;

	//$handle = @fopen($path, 'r');

	//if($handle){
		// get content
		//$content = http_get_content($url);
		$content = http_get_content($path);


		// check
		if (is_numeric($content)) {
			$appId = $content;
		} else {
			return update_web2app_id();
		}

	//} else {
		return update_web2app_id();
	//}

	return $appId;
}


/**
 * Validate API Key if valid
 */
function validate_api_key() {
	global $w2a_options;

	// get appId
    $url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';

	$appId = http_get_content($url);


	// check
	if ($appId == 'Wrong API. Please Check Your API Key') { ?>
		<div class="error">
			<p><?php _e( 'Your API Key is invalid! Please go to web2application -> setting and fix your API key', 'web2application' ); ?></p>
		</div>
<?php
	} else {
		// create file to save the $appId
		$path = $_SERVER['DOCUMENT_ROOT']."/web2app-id";

		// create a file
		$file = fopen($path, "w");
		fwrite($file, $appId);
		fclose($file);
	}
}


/**
 * Load iOS Universal Link
 */
function ios_universal_link_load() {
	global $w2a_options;

	// get appId
	$appId = get_web2app_id();

	// check
	if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {
		// check if gile exist
		$aasaUrl = 'https://www.web2application.com/w2a/webapps/'.$appId.'/apple-app-site-association';
		//$handle = @fopen($aasaUrl, 'r');


		//if($handle) {
			// get content
			$json = http_get_content($aasaUrl);

			// define folder
			$dest 		= $_SERVER['DOCUMENT_ROOT']."/";
			$filename 	= "apple-app-site-association";

			// create a file
			$aasa = fopen($dest.$filename, "w");
			fwrite($aasa, $json);
			fclose($aasa);
		//}
	}
}


/**
 * Load Web Push Files
 */
function web_push_files_load() {
	global $w2a_options;

	// get appId
	$appId = get_web2app_id();

	// check
	if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {

		// check if exist
		$web2app1 = 'https://www.web2application.com/w2a/webapps/'.$appId.'/web2app1.js';
		//$handle = @fopen($web2app1, 'r');

		//if($handle){
			// get content
			$content = http_get_content($web2app1);

			// check
			if (!empty($content)) {
				// get manifest.json file content
				$manifestUrl = 'https://www.web2application.com/w2a/webapps/'.$appId.'/manifest.json';
				//$json = file_get_contents($manifestUrl);
				$json = http_get_content($manifestUrl);

				// get firebase-messaging-sw.js content
				$jsUrl = 'https://www.web2application.com/w2a/webapps/'.$appId.'/firebase-messaging-sw.js';
				//$js = file_get_contents($jsUrl);
				$js = http_get_content($jsUrl);

				// define folder
				$dest = $_SERVER['DOCUMENT_ROOT']."/";

				// create json file
				$manifest = fopen($dest."manifest.json", "w");
				fwrite($manifest, $json);
				fclose($manifest);

				// create js file
				$fm = fopen($dest."firebase-messaging-sw.js", "w");
				fwrite($fm, $js);
				fclose($fm);
			}
		//}
	}
}

if ( isset( $w2a_options['w2a_disable_web_push'] ) && $w2a_options['w2a_disable_web_push'] != "1") {
	add_action('wp_enqueue_scripts', 'footer_scripts');
	/* ?><script>console.log('web2app1.js inserted');</script><?php */
} else {
	/* ?><script>console.log('web2app1.js NOT inserted');</script><?php */
}


/**
 * Add Web Push Files
 */
function footer_scripts() {
	global $w2a_options;

	// get appId
	$appId = get_web2app_id();

	// check
	if ($appId != 'Wrong API. Please Check Your API Key' && is_numeric($appId)) {

		// check if exist
		$web2app1 = 'https://www.web2application.com/w2a/webapps/'.$appId.'/web2app1.js';
		//$handle = @fopen($web2app1, 'r');

		//if($handle){
			// get content
			$content = http_get_content($web2app1);

			// check
			if (!empty($content)) {
			?>

				<!--begin add other files to footer-->

				<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
				<link rel="manifest" href="/manifest.json">
				<script src="https://www.gstatic.com/firebasejs/7.11.0/firebase.js"></script>
				<script type="text/javascript" src="https://web2application.com/w2a/webapps/<?php echo $appId; ?>/web2app1.js"></script>

				<!--end other files to footer-->
			<?php
			}
		//}
	}

}

function http_get_content($url) {
	// get content
	$content = file_get_contents($url);

	// check
	//	if ($appId == "") {
	if ($content == "") {
		// init curl
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$content = curl_exec($ch);
		curl_close($ch);

		return $content;
	}

	return $content;
}





/* Add Send Push Checkbox and save */
add_action( 'load-post.php', 'send_push_post_meta_boxes_setup' );
add_action( 'load-post-new.php', 'send_push_post_meta_boxes_setup' );
add_action( 'save_post', 'send_push_save_post_class_meta', 10, 2 );
function send_push_post_meta_boxes_setup() {
	global $w2a_options;

	if ($w2a_options['w2a_enable_notify_post'] == "1") {
		add_action( 'add_meta_boxes', 'send_push_add_post_meta_boxes' );
	}
}
function send_push_add_post_meta_boxes() {
	$screens = array('post','page','product');
	foreach ($screens as $screen) {
	  	add_meta_box(
	    	'send-push-post-class',
	    	esc_html__( 'Push Notification', 'web2application' ),
	    	'send_push_post_class_meta_box',
	    	$screen,
	    	'normal',
	    	'high'
	  	);
	}
}
function send_push_post_class_meta_box( $post ) {

	wp_nonce_field( basename( __FILE__ ), 'send_push_post_class_nonce' );
	$send_push_meta = get_post_meta( $post->ID, 'send_push_post_class', true );
	$send_push_notify = get_post_meta( $post->ID, 'send_push_notify', true );
	?>
  	<p>
	    <!-- <label for="send-push-post-class"><?php _e( "Push delivery that pushes will not be send by mistake", 'web2application' ); ?></label>
	    <br /> -->
	    <input class="widefat" type="checkbox" name="send-push-post-class" id="send-push-post-class" value="1" />&nbsp;<?php _e('Mark this check box to send this page content as a push notification to all your app users on save', 'web2application'); ?>
	    <?php //echo ($send_push_meta) ? 'checked' : ''; ?>
  	</p>
  	<?php /* ?>
	<div class="components-panel__row">
		<div class="components-base-control components-checkbox-control css-1wzzj1a e1puf3u3">
			<div class="components-base-control__field">
				<p>
					<?php _e( "Mark this check box to send this page content as a push notification to all your app users on save", 'web2application' ); ?>
	    		</p>
				<span class="components-checkbox-control__input-container">
					<input id="send-push-post-class" class="components-checkbox-control__input" type="checkbox" name="send-push-post-class" value="1" <?php echo ($send_push_meta == true) ? 'checked' : ''; ?> />
				</span>
				<label class="components-checkbox-control__label" for="send-push-post-class"><?php _e( "Send Push", 'web2application' ); ?></label>
			</div>
		</div>
	</div>
  	<?php */ ?>
	<?php
}
function send_push_save_post_class_meta( $post_id, $post ) {

	if ( !isset( $_POST['send_push_post_class_nonce'] ) || !wp_verify_nonce( $_POST['send_push_post_class_nonce'], basename( __FILE__ ) ) ) {
    	return $post_id;
  	}

  	$post_type = get_post_type_object( $post->post_type );

  	if ( !current_user_can( $post_type->cap->edit_post, $post_id ) ) {
    	return $post_id;
  	}

  	$new_meta_value = ( isset( $_POST['send-push-post-class'] ) ? true : false );
  	$meta_key = 'send_push_post_class';

  	$meta_value = get_post_meta( $post_id, $meta_key, true );

  	if ( $new_meta_value && '' == $meta_value ) {
    	add_post_meta( $post_id, $meta_key, $new_meta_value, true );
  	}
  	elseif ( $new_meta_value && $new_meta_value != $meta_value ) {
    	update_post_meta( $post_id, $meta_key, $new_meta_value );
  	}
  	elseif ( '' == $new_meta_value && $meta_value ) {
    	delete_post_meta( $post_id, $meta_key, $meta_value );
  	}
}

/* fire when post saved */
add_action( 'save_post', 'publish_post_page_product', 10, 3 );
function publish_post_page_product($post_id, $post, $update) {
	global $w2a_options;

	if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'editpost' && !isset( $_REQUEST['message'] ) ) {

		$send_push = get_post_meta( $post_id, 'send_push_post_class', true );
		$send_push_notify = get_post_meta( $post_id, 'send_push_notify', true );
		/*echo "<pre>";
		print_r($post);
		echo "</pre>";*/

		if ($w2a_options['w2a_enable_notify_post'] == "1") {

			// && !$send_push_notify
			if( $send_push ) {

				$pushfor = array('post','page','product');
				if ( in_array( $post->post_type, $pushfor) ) {

					if ( $post->post_type == 'post' || $post->post_type == 'page' ) {

						// $short_description = $post->post_excerpt;
						$short_description = $post->post_content;
						/* remove image and youtube from the content */
				//		$short_description = preg_replace('/<img[^>]+\>/i','', $short_description);
				//		$short_description = preg_replace('/<iframe.*?\/iframe>/i','', $short_description);
						
						/* remove image and youtube from the content */
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );
						$image = $image[0];
						$post_url = get_permalink( $post->ID );

						$w2aPushTitle = $post->post_title;
						$w2aPushMessage = nl2br($short_description);
						$w2aPushMessage = strip_tags($w2aPushMessage);
					//w2aPushMessage = strip_tags($short_description);
						$w2aPushImage = (!empty($image)) ? $image : '';
						$w2aRichPushImage = (!empty($image)) ? $image : '';
						$w2aPushLink = $post_url;
					}
					else if ( $post->post_type == 'product' ) {

						$product_id = $post->ID;
						$product = wc_get_product( $product_id );
						$product_title = $product->get_title();
						/* remove image and youtube from the content */
				//		$short_description = preg_replace('/<img[^>]+\>/i','', $short_description);
				//		$short_description = preg_replace('/<iframe.*?\/iframe>/i','', $short_description);
						/* remove image and youtube from the content */
				//		$short_description = preg_replace('/<iframe.*?\/iframe>/i','', $short_description);
						$image = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'single-post-thumbnail' );
						$image = $image[0];
						$product_link = $product->get_permalink();

						$w2aPushTitle = $product_title;
						$w2aPushMessage = nl2br($short_description);
						$w2aPushMessage = strip_tags($w2aPushMessage);
					//	$w2aPushMessage = $short_description;
						$w2aPushImage = (!empty($image)) ? $image : '';
						$w2aRichPushImage = (!empty($image)) ? $image : '';
						$w2aPushLink = (!empty($product_link)) ? $product_link : '';
					}

					$push_schedule = 'send_now';
					$w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';

					//send the data for pushing
					$url = 'http://www.web2application.com/w2a/api-process/send_push_from_plugin.php';
					$data = array('api_domain' => $_SERVER['SERVER_NAME'], 'api_key' => trim($w2a_options['w2a_api_key']),'push_title' => $w2aPushTitle, 'push_text' => $w2aPushMessage, 'push_image_url' => $w2aPushImage, 'rich_push_image_url' => $w2aRichPushImage, 'push_link' => $w2aPushLink, 'push_time' => $w2aPushTime);
					$json = json_encode($data);

					$headers = array("Content-type: application/json");

					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
					curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
					curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

					$response = curl_exec($ch);
					curl_close($ch);

					/* set push notify */
					update_post_meta( $post_id, 'send_push_notify', true );
					/* set push notify */
				}
			}
		}
	}
}

add_action('init', 'call_cron');
function call_cron() {
	if( isset( $_REQUEST['cron']) && $_REQUEST['cron'] == 'cartflow') {
		woocommerce_cart_ab_update_func();
	}
	if( isset( $_REQUEST['cron']) && $_REQUEST['cron'] == 'w2a_reminder_cron') {
		do_action('w2a_reminder_cron');
	}
	if( isset( $_REQUEST['cron']) && $_REQUEST['cron'] == 'w2a_miss_you_reminder_cron') {
		do_action('w2a_miss_you_reminder_cron');
	}
}

add_action('cartflows_ca_update_order_status_action', 'woocommerce_cart_ab_update_func', 9 );
function woocommerce_cart_ab_update_func() {
	global $wpdb, $w2a_options;
	$email_history_table    = $wpdb->prefix . CARTFLOWS_CA_EMAIL_HISTORY_TABLE;
	$cart_abandonment_table = $wpdb->prefix . CARTFLOWS_CA_CART_ABANDONMENT_TABLE;
	$email_template_table   = $wpdb->prefix . CARTFLOWS_CA_EMAIL_TEMPLATE_TABLE;

	$current_time = current_time( WCF_CA_DATETIME_FORMAT );
	// phpcs:disable WordPress.DB.PreparedSQL.NotPrepared
	$emails_send_to = $wpdb->get_results(
		$wpdb->prepare(
			'SELECT *, EHT.id as email_history_id, ETT.id as email_template_id FROM ' . $email_history_table . ' as EHT
			INNER JOIN ' . $cart_abandonment_table . ' as CAT ON EHT.`ca_session_id` = CAT.`session_id`
			INNER JOIN ' . $email_template_table . ' as ETT ON ETT.`id` = EHT.`template_id`
			WHERE CAT.`order_status` = %s AND CAT.unsubscribed = 0 AND EHT.`email_sent` = 0 AND EHT.`scheduled_time` <= %s',
			WCF_CART_ABANDONED_ORDER,
			$current_time
		)
	);
	// phpcs:enable WordPress.DB.PreparedSQL.NotPrepared
	foreach ( $emails_send_to as $email_data ) {
		if ( filter_var( $email_data->email, FILTER_VALIDATE_EMAIL ) ) {
			if ( ! Cartflows_Ca_Email_Schedule::get_instance()->check_if_already_purchased_by_email_product_ids( $email_data, $email_data->cart_contents ) ) {
				return false;
			}

			$email = $email_data->email;
			$w2aPushTitle = $email_data->email_subject;
			$message = 'Few days back you left {{cart.product.names}} in your cart.';
			$message = str_replace( '{{cart.product.names}}', Cartflows_Ca_Helper::get_instance()->get_comma_separated_products( $email_data->cart_contents ), $message );
			$w2aPushMessage = $message;
			$w2aPushLink = site_url().'/my-account';

			$url = "https://web2application.com/w2a/webapi/send_push.php";
			$headers = array("Content-type: application/json");

			$custom_logo_id = get_theme_mod( 'custom_logo' );
			$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
			$image = $image[0];

			$push_schedule = 'send_now';
			$w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';

			$data = array("api_domain" => $_SERVER['SERVER_NAME'], "api_key" => trim($w2a_options['w2a_api_key']), "email" => $email, "push_title" => $w2aPushTitle, "push_text" => $w2aPushMessage, "push_link" => $w2aPushLink, "push_image_url" => $image, "rich_push_image_url" => $image);
			$json = json_encode($data);

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
			curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			$response = curl_exec($ch);
			curl_close($ch);
		}
	}
}

/**/
add_action('woocommerce_email_sent', 'woocommerce_email_sent_func', 10, 3);
function woocommerce_email_sent_func( $email_status, $email_id, $data ) {
	global $w2a_options;

	if ($w2a_options['w2a_enable_notify_email'] == "1") {

		$email = $data->recipient;
		$w2aPushTitle = '';
		$w2aPushMessage = '';
		$w2aPushLink = '';

		if( $email_id == 'customer_invoice' ) {

			$order_id = $data->object->id;

			$w2aPushTitle = $data->settings['heading_paid'];
			$w2aPushTitle = str_replace('{order_number}', $order_id, $w2aPushTitle);

			$user_fname = $data->object->data['billing']['first_name'];
			$user_fname = ( !empty( $user_fname ) ) ? $user_fname : '';
			$message = 'Hi '.$user_fname.','.PHP_EOL;
			$order_date = $data->object->get_date_created();
			$order_date = $order_date->date('F d, Y');
			$message .= 'Here are the details of your order placed on '.$order_date.PHP_EOL;
			$additional_content = $data->settings['additional_content'];
			$additional_content = str_replace('{site_url}', site_url(), $additional_content);
			$message .= $additional_content;
			$w2aPushMessage = $message;

			$order_key = $data->object->get_order_key();
			$w2aPushLink = wc_get_checkout_url().'order-received/'.$order_id.'/?key='.$order_key;

		}
		else if( $email_id == 'customer_note' ) {

			$order_id = $data->object->id;

			$w2aPushTitle = $data->settings['heading'];

			$w2aPushMessage = $data->customer_note;

			$order_key = $data->object->get_order_key();
			$w2aPushLink = wc_get_checkout_url().'order-received/'.$order_id.'/?key='.$order_key;
		}
		else if( $email_id == 'new_order' ) {

			$order_id = $data->object->id;

			$w2aPushTitle = $data->settings['heading'];
			$w2aPushTitle = str_replace('{order_number}', $order_id, $w2aPushTitle);

			$additional_content = $data->settings['additional_content'];
			$message .= 'Thank you for your order. ';
			$message .= $additional_content;
			$w2aPushMessage = $message;

			$order_key = $data->object->get_order_key();
			$w2aPushLink = wc_get_checkout_url().'order-received/'.$order_id.'/?key='.$order_key;
		}
		else if( $email_id == 'customer_processing_order' ) {

			$order_id = $data->object->id;

			$w2aPushTitle = $data->settings['heading'];
			$w2aPushTitle = str_replace('{order_number}', $order_id, $w2aPushTitle);

			$additional_content = $data->settings['additional_content'];
			$additional_content = str_replace('{site_url}', site_url(), $additional_content);
			$message .= 'Processing your order. ';
			$message .= $additional_content;
			$w2aPushMessage = $message;

			$order_key = $data->object->get_order_key();
			$w2aPushLink = wc_get_checkout_url().'order-received/'.$order_id.'/?key='.$order_key;
		}
		else if( $email_id == 'customer_completed_order' ) {

			$order_id = $data->object->id;

			$w2aPushTitle = $data->settings['heading'];
			$w2aPushTitle = str_replace('{order_number}', $order_id, $w2aPushTitle);

			/* $additional_content = $data->settings['additional_content'];
			$additional_content = str_replace('{site_url}', site_url(), $additional_content);
			$message .= $additional_content; */
			$message = 'We have finished processing your order.';
			$w2aPushMessage = $message;

			$order_key = $data->object->get_order_key();
			$w2aPushLink = wc_get_checkout_url().'order-received/'.$order_id.'/?key='.$order_key;
		}
		else if( $email_id == 'customer_invoice_paid' ) {
			$order_id = $data->object->id;

			$w2aPushTitle = $data->settings['heading'];
			$w2aPushTitle = str_replace('{order_number}', $order_id, $w2aPushTitle);

			$additional_content = $data->settings['additional_content'];
			$additional_content = str_replace('{site_url}', site_url(), $additional_content);
			$message .= $additional_content;
			$w2aPushMessage = $message;

			$order_key = $data->object->get_order_key();
			$w2aPushLink = wc_get_checkout_url().'order-received/'.$order_id.'/?key='.$order_key;
		}
		else if( $email_id == 'backorder' ) {
		}
		else if( $email_id == 'low_stock' ) {
		}
		else if( $email_id == 'no_stock' ) {
		}
		else if( $email_id == 'customer_new_account' ) {
			$w2aPushTitle = $data->settings['heading'];

			$additional_content = $data->settings['additional_content'];
			$additional_content = str_replace('{site_url}', site_url(), $additional_content);
			$message .= $additional_content;
			$w2aPushMessage = $message;

			$w2aPushLink = site_url();
		}

		/* $myfile = fopen(__DIR__."/newfile.txt", "a") or die("Unable to open file!");
		$txt = $email_id."\n";
		fwrite($myfile, $txt);
		$txt = $w2aPushTitle."\n";
		fwrite($myfile, $txt);
		$txt = $w2aPushMessage."\n";
		fwrite($myfile, $txt);
		$txt = $w2aPushLink."\n";
		fwrite($myfile, $txt);
		$txt = "\n";
		fwrite($myfile, $txt);
		fclose($myfile); */

		$url = "https://web2application.com/w2a/webapi/send_push.php";
		$headers = array("Content-type: application/json");

		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
		$image = $image[0];

		$push_schedule = 'send_now';
	    $w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';

	    $data = array("api_domain" => $_SERVER['SERVER_NAME'], "api_key" => trim($w2a_options['w2a_api_key']), "email" => $email, "push_title" => $w2aPushTitle, "push_text" => $w2aPushMessage, "push_link" => $w2aPushLink, "push_image_url" => $image, "rich_push_image_url" => $image);
	    $json = json_encode($data);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		$response = curl_exec($ch);
		curl_close($ch);
	}

	return $email_status;
}

add_action('woocommerce_add_to_cart', 'add_tocart_func');
function add_tocart_func() {
	global $woocommerce;
	$cart_data = WC()->cart->get_cart();

	$timestamp = date('Y-m-d h:i:s');
	//$timestamp = strtotime($timestamp);

	$user_id = get_current_user_id();
	add_user_meta( $user_id, 'cart_timestamp', $timestamp);
}

add_action( 'w2a_reminder_cron', 'reminder_func' );
function reminder_func() {
	global $wpdb, $w2a_options;

	if( $w2a_options['w2a_enable_reminder'] == 1 ) {

		$usermetas = $wpdb->get_results("SELECT * FROM `{$wpdb->prefix}usermeta` where meta_key like '_woocommerce_persistent_cart%'");

		foreach($usermetas as $usermeta) {

			$user_id = $usermeta->user_id;

			$w2a_reminder_limit = $w2a_options['w2a_reminder_limit'];
			$reminder_limit = get_user_meta($user_id, 'reminder_limit', true);
			$reminder_limit = ( !empty( $reminder_limit ) ) ? $reminder_limit : 0;

			$cart_data = $usermeta->meta_value;
			$cart_data = unserialize($cart_data);

			if( !empty( $cart_data['cart'] ) ) {

				$user = get_userdata( $user_id );
				$user_email = $user->data->user_email;

				$reminder_days = $w2a_options['w2a_reminder_days'];

				$cart_timestamp = get_user_meta( $user_id, 'cart_timestamp', true );
				$cart_reminder_days = date('Y-m-d', strtotime($cart_timestamp. ' + '.$reminder_days.' days'));

				$current_time = date('Y-m-d');

				// if( $current_time <= $cart_reminder_days ) {
				if( $current_time >= $cart_reminder_days ) {

					if( $reminder_limit < $w2a_reminder_limit) {

						$reminder_limit = $reminder_limit + 1;
						update_user_meta($user_id, 'reminder_limit', $reminder_limit);

						$cart_data = $cart_data['cart'];
						$product_names = array();
						$cart_coupon_code = 'CA';
						$cart_checkout_url = wc_get_checkout_url();
						$admin_user = get_users(
							array(
								'role'   => 'Administrator',
								'number' => 1,
							)
						);
						$admin_user         = reset( $admin_user );
						$admin_first_name   = $admin_user->user_firstname ? $admin_user->user_firstname : __( 'Admin', 'woo-cart-abandonment-recovery' );
						$unsubscribe_element = '<a target="_blank" style="color: lightgray" href="' . $cart_checkout_url . '?unsubscribe=true ">' . __( 'Unsubscribe', 'web2application' ) . '</a>';

						foreach ( $cart_data as $cart_item_key => $cart_item ) {
							if( isset( $cart_item['product_id'] ) ) {
								$product_id = $cart_item['product_id'];
								$product = wc_get_product( $product_id );
								$product_name = $product->get_title();
								array_push($product_names, $product_name);
							}
						}
						$product_names = implode(',', $product_names);

						$push_title = $w2a_options['w2a_reminder_push_title'];
						$push_link = $w2a_options['w2a_reminder_push_link'];
						$body_template = stripslashes( $w2a_options['w2a_reminder_body_push'] );

						$body_template = str_replace('{{cart.product.names}}', $product_name, $body_template);
						$body_template = str_replace('{{cart.coupon_code}}', $cart_coupon_code, $body_template);
						$body_template = str_replace('{{cart.checkout_url}}', $cart_checkout_url, $body_template);
						$body_template = str_replace( '{{admin.firstname}}', $admin_first_name, $body_template );
						$body_template = str_replace( '{{admin.company}}', get_bloginfo( 'name' ), $body_template );
						$body_template  = str_replace( '{{cart.unsubscribe}}', $unsubscribe_element, $body_template );

						$body_template_preview = nl2br($body_template);

						$email = $user_email;
						$subject = $push_title;
						$message = $body_template_preview;

						$from_email_name = ucwords( get_bloginfo( 'name' ) );
						$from_email = get_bloginfo( 'admin_email' );

						$headers  = 'From: ' . $from_email_name . ' <' . $from_email . '>' . "\r\n";
						$headers .= 'Content-Type: text/html' . "\r\n";
						$headers .= 'Reply-To:  ' . $from_email . ' ' . "\r\n";
						$mail_result = wp_mail( $email, $subject, stripslashes( $body_template_preview ), $headers );
						die;

						$w2aPushTitle = $push_title;
						$w2aPushMessage = strip_tags( $body_template );
						$w2aPushMessage = html_entity_decode( $w2aPushMessage );
						$w2aPushLink = $push_link;

						$url = "https://web2application.com/w2a/webapi/send_push.php";
						$headers = array("Content-type: application/json");

						$custom_logo_id = get_theme_mod( 'custom_logo' );
						$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
						$image = $image[0];

						$push_schedule = 'send_now';
						$w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';

						$data = array("api_domain" => $_SERVER['SERVER_NAME'], "api_key" => trim($w2a_options['w2a_api_key']), "email" => $email, "push_title" => $w2aPushTitle, "push_text" => $w2aPushMessage, "push_link" => $w2aPushLink, "push_image_url" => $image, "rich_push_image_url" => $image);
						$json = json_encode($data);

						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, $url);
						curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
						curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
						curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
						$response = curl_exec($ch);
						curl_close($ch);
					}
					else {
						// echo 'limit exceeds.!!'; die;
					}

				}

			}
		}
	}
	die();
}

add_action( 'w2a_miss_you_reminder_cron', 'miss_you_reminder_func' );
function miss_you_reminder_func() {
	global $wpdb, $w2a_options;

	if( $w2a_options['w2a_enable_miss_you_reminder'] == 1 ) {
		$miss_you_reminder_days = $w2a_options['w2a_miss_you_reminder_days'];

		$w2a_miss_you_reminder_limit = $w2a_options['w2a_miss_you_reminder_limit'];
		$miss_you_reminder_limit = get_user_meta($user_id, 'miss_you_reminder_limit', true);
		$miss_you_reminder_limit = ( !empty( $miss_you_reminder_limit ) ) ? $miss_you_reminder_limit : 0;

		$users = get_users();
		foreach( $users as $user ) {
			$user_id = $user->ID;
			$user_email = $user->data->user_email;

			$args = array(
				'customer_id' => $user_id,
				'type'=> 'shop_order',
				'status'=> array( 'wc-completed','wc-refunded' ),
				'limit' => -1,
			);
			$orders = wc_get_orders($args);

			if( !empty( $orders ) ) {
				foreach($orders as $order) {

					$order_date = $order->order_date;
					$reminder_days = date('Y-m-d', strtotime($order_date. ' + '.$miss_you_reminder_days.' days'));
					$current_time = date('Y-m-d');

					/*echo $user_email.'<br>';
					echo $reminder_days.'<br>';
					echo '----- <br>';*/

					// if( $current_time <= $reminder_days ) {
					if( $current_time >= $reminder_days ) {

						if( $miss_you_reminder_limit < $w2a_miss_you_reminder_limit) {

							$miss_you_reminder_limit = $miss_you_reminder_limit + 1;
							update_user_meta($user_id, 'miss_you_reminder_limit', $miss_you_reminder_limit);

							$product_names = array();
							$cart_coupon_code = 'CA';
							$order_key = $order->order_key;
							$cart_checkout_url = wc_get_checkout_url().'/'.$order_key;
							$admin_user         = get_users(
								array(
									'role'   => 'Administrator',
									'number' => 1,
								)
							);
							$admin_user         = reset( $admin_user );
							$admin_first_name   = $admin_user->user_firstname ? $admin_user->user_firstname : __( 'Admin', 'woo-cart-abandonment-recovery' );
							$unsubscribe_element = '<a target="_blank" style="color: lightgray" href="' . $cart_checkout_url . '?unsubscribe=true ">' . __( 'Unsubscribe', 'web2application' ) . '</a>';

							$items = $order->get_items();
							foreach ($items as $item) {

								$product_name = $item['name'];
								$product_id = $item['product_id'];
								$product_variation_id = $item['variation_id'];
								$product_description = get_post_meta($item['product_id'])->post_content;
								array_push($product_names, $product_name);
							}
							$product_names = implode(',', $product_names);

							$push_title = $w2a_options['w2a_miss_you_reminder_push_title'];
							$push_link = $w2a_options['w2a_miss_you_reminder_push_link'];
							$body_template = stripslashes( $w2a_options['w2a_miss_you_reminder_body_push'] );

							/*$body_template = str_replace('{{cart.product.names}}', $product_names, $body_template);
							$body_template = str_replace('{{cart.coupon_code}}', $cart_coupon_code, $body_template);
							$body_template = str_replace('{{cart.checkout_url}}', $cart_checkout_url, $body_template);*/
							$body_template = str_replace( '{{admin.firstname}}', $admin_first_name, $body_template );
							$body_template = str_replace( '{{admin.company}}', get_bloginfo( 'name' ), $body_template );
							$body_template  = str_replace( '{{cart.unsubscribe}}', $unsubscribe_element, $body_template );

							$body_template_preview = nl2br($body_template);

							$email = $user_email;
							$subject = $push_title;
							$message = $body_template_preview;

							$from_email_name = ucwords( get_bloginfo( 'name' ) );
							$from_email = get_bloginfo( 'admin_email' );

							$headers  = 'From: ' . $from_email_name . ' <' . $from_email . '>' . "\r\n";
							$headers .= 'Content-Type: text/html' . "\r\n";
							$headers .= 'Reply-To:  ' . $from_email . ' ' . "\r\n";
							$mail_result = wp_mail( $email, $subject, stripslashes( $body_template_preview ), $headers );

							$w2aPushTitle = $push_title;
							$w2aPushMessage = strip_tags( $body_template );
							$w2aPushMessage = html_entity_decode( $w2aPushMessage );
							$w2aPushLink = $push_link;

							$url = "https://web2application.com/w2a/webapi/send_push.php";
							$headers = array("Content-type: application/json");

							$custom_logo_id = get_theme_mod( 'custom_logo' );
							$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
							$image = $image[0];

							$push_schedule = 'send_now';
							$w2aPushTime = ($push_schedule == "send_now") ? date('Y/m/d H:i:s') : sanitize_text_field($_POST['push_date']).' '.sanitize_text_field($_POST['push_time']).':00';

							$data = array("api_domain" => $_SERVER['SERVER_NAME'], "api_key" => trim($w2a_options['w2a_api_key']), "email" => $email, "push_title" => $w2aPushTitle, "push_text" => $w2aPushMessage, "push_link" => $w2aPushLink, "push_image_url" => $image, "rich_push_image_url" => $image);
							$json = json_encode($data);

							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL, $url);
							curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
							curl_setopt($ch, CURLOPT_POSTFIELDS, $json);
							curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
							$response = curl_exec($ch);
							curl_close($ch);
						}
					}
				}
			}
		}
	}
	die();
}

// apply coupon for user when app discount is enable
add_action('woocommerce_before_checkout_form', 'w2a_apply_coupon_app_discount');
add_action( 'woocommerce_before_cart', 'w2a_apply_coupon_app_discount' );
function w2a_apply_coupon_app_discount() {
	global $wpdb, $w2a_options;

	// checking API
	/*$url = 'https://www.web2application.com/w2a/api-process/get_app_id.php?api_domain='.$_SERVER['SERVER_NAME'].'&api_key='.trim($w2a_options['w2a_api_key']).'&version=new';
	$appId = http_get_content($url);

 
 
	// check
	if ($appId == 'Wrong API. Please Check Your API Key') {	*/
	
	$userIsInsideTheAppCheck = '0';
	if (isset($_COOKIE['appUserToken'])) {
	//	echo "appusertoken found !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!! " . $_COOKIE['appUserToken'];
		$userIsInsideTheAppCheck = '1';
	} else { 
		// echo "cookie not found"; 	
	}
	if (isset($_REQUEST['dev'])) {
		$userIsInsideTheAppCheck = '1';
	}
	
	
	if( $w2a_options['w2a_enable_app_discount'] != 0 ) {
	//	if( !isset( $_REQUEST['dev'] ) ) {
		if( $userIsInsideTheAppCheck != '1' ) {	
			$coupon_code = __('In-app Discount', 'web2application');
			WC()->cart->remove_coupon( $coupon_code );
		}
		else {
			if( $w2a_options['w2a_enable_app_discount'] == 1 ) {

				$coupon_code = __('In-app Discount', 'web2application');
				if ( WC()->cart->has_discount( $coupon_code ) ) return;
				WC()->cart->apply_coupon( $coupon_code );
				wc_print_notices();
			}
			else {

				$coupon_code = __('In-app Discount', 'web2application');
				WC()->cart->remove_coupon( $coupon_code );

				do_action('woocommerce_calculate_totals');
				?>
				<script>location.reload();</script>
				<?php
			}
		}
	}else {
			$coupon_code = __('In-app Discount', 'web2application');
			WC()->cart->remove_coupon( $coupon_code );
	}
}


// Shortcode for save user search
/*add_shortcode('wc-search-save', 'custom_save_user_search');
function custom_save_user_search() {
	echo '<div class="elementor-column-wrap elementor-element-populated">
	    <div class="elementor-widget-wrap">
	        <div class="elementor-element elementor-element-b004b20 elementor-widget elementor-widget-text-editor" data-id="b004b20" data-element_type="widget" data-widget_type="text-editor.default">
	            <div class="elementor-widget-container">
	                <div class="elementor-text-editor elementor-clearfix">
	                    <p>
	                        <font style="vertical-align: inherit;">
	                            <font style="vertical-align: inherit;">custom search bar</font>
	                        </font>
	                    </p>
	                </div>
	            </div>
	        </div>
	        <div class="elementor-element elementor-element-5b34d0a elementor-search-form--skin-classic elementor-search-form--button-type-icon elementor-search-form--icon-search elementor-widget elementor-widget-search-form" data-id="5b34d0a" data-element_type="widget" data-settings="{&quot;skin&quot;:&quot;classic&quot;}" data-widget_type="search-form.default">
	            <div class="elementor-widget-container">
	                <form class="elementor-search-form" id="saveSearchForm" method="post">
	                    <div class="elementor-search-form__container">
	                        <input placeholder="search..." class="elementor-search-form__input" type="search" name="s" title="Search" value="">
	                        <button class="elementor-search-form__submit" type="submit" title="Search" aria-label="Search">
	                            <i aria-hidden="true" class="fas fa-search"></i> <span class="elementor-screen-only">
	                                <font style="vertical-align: inherit;">
	                                    <font style="vertical-align: inherit;">Search</font>
	                                </font>
	                            </span>
	                        </button>
	                    </div>
	                </form>
	            </div>
	        </div>
	    </div>
	</div>
	<script>var ajax_url = "'.admin_url('admin-ajax.php').'";</script>';
}*/


function web2_user_engagement() {
	global $wpdb;

	$custom_search_table = $wpdb->prefix.'custom_search';
	$guest_users = $wpdb->get_results("SELECT * FROM $custom_search_table WHERE cookie_id != '' AND search_value != '' ");
	
	/*$user_meta_table = $wpdb->prefix.'usermeta';
	$users = $wpdb->get_results("SELECT * FROM $user_meta_table WHERE meta_key = 'custom_search' AND meta_value != '' ");*/

	$users = get_users(array(
		'meta_key'     => 'custom_search',
	));

	?>
	<style type="text/css">
	.table {
		width: 100%;
	}
	.table tr th, .table tr td {
		border: #ccc solid 1px;
		padding: 5px;
	}
	</style>
	<h3>Logged In User's Search</h3>
	<table class="table table-striped table-bordered" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Search Text</th>
			<th>User Email</th>
			<th>Date Time</th>
		</tr>
		<?php
		if( !empty( $users ) ) {
			foreach( $users as $user ) {
				$user_id = $user->ID;
				$custom_search_value = get_user_meta( $user_id, 'custom_search', true );
				//foreach( $custom_search_value as $search_value ) {
					// $search_value_date_time = date('F j, Y H:i:s', strtotime($search_value['date_time']));
					$search_value_date_time = date('F j, Y H:i:s', strtotime($custom_search_value[0]['date_time']));
				?>
				<tr>
					<td><?php echo home_url().'?s='.$custom_search_value[0]['text']; ?></td>
					<td><?php echo $user->data->user_email; ?></td>
					<td><?php echo $search_value_date_time; ?></td>
				</tr>
				<?php
				//}
			}
		}
		else {
		?>
		<tr>
			<td colspan="3">No Records Found...!!</td>
		</tr>
		<?php
		}
		?>
	</table>
	<hr>
	<h3>Guest User's Search</h3>
	<table class="table table-striped table-bordered" border="0" cellpadding="0" cellspacing="0">
		<tr>
			<th>Search Text</th>
			<th>Cookie ID</th>
			<th>Date Time</th>
		</tr>
		<?php
		if( !empty( $guest_users ) ) {
			foreach( $guest_users as $user ) {
				$custom_search_value = json_decode( $user->search_value, true );
				//foreach( $custom_search_value as $search_value ) {
					//$search_value_date_time = date('F j, Y H:i:s', strtotime($search_value['date_time']));
					$search_value_date_time = date('F j, Y H:i:s', strtotime($custom_search_value[0]['date_time']));
				?>
				<tr>
					<td><?php echo home_url().'?s='.$custom_search_value[0]['text']; ?></td>
					<td><?php echo $user->cookie_id; ?></td>
					<td><?php echo $search_value_date_time; ?></td>
				</tr>
				<?php
				//}
			}
		}
		else {
		?>
		<tr>
			<td colspan="3">No Records Found...!!</td>
		</tr>
		<?php
		}
		?>
	</table>
	<?php
}

//Enable REST API

// Register a custom REST API endpoint - nir . works well
//get orders
add_action('rest_api_init', 'we2application_engage_get_categories_api_endpoint');

	function we2application_engage_get_categories_api_endpoint() {
		register_rest_route('web2application/v1', '/getorders', array(
			'methods' => 'POST',
			'callback' => 'we2application_engage_get_categories',
		));
	}
	function we2application_engage_get_categories($request) {
		// Your custom data retrieval logic goes here

		include 'api/get_orders.php';

		// Return the response
		return rest_ensure_response($data);
	}

//get carts
add_action('rest_api_init', 'we2application_engage_get_abcarts_api_endpoint');

	function we2application_engage_get_abcarts_api_endpoint() {
		register_rest_route('web2application/v1', '/getabcarts', array(
			'methods' => 'POST',
			'callback' => 'we2application_engage_get_abcarts',
		));
	}
	function we2application_engage_get_abcarts($request) {
		// Your custom data retrieval logic goes here

		include 'api/get_abcarts.php';

		// Return the response
		return rest_ensure_response($data);
	}


	
?>
