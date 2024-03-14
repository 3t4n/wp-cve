<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Plugin Name:       Coming Soon Maintenance Mode - v1.0.6
 * Plugin URI:        https://webenvo.com/
 * Description:       One of the most recommanded and crucial plugin to start your website projects.
 * Version:           1.0.6
 * Requires at least: 4.0
 * Requires PHP:      4.0
 * Author:            A WP Life
 * Author URI:        https://profiles.wordpress.org/webenvo/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       coming-soon-maintenance-mode
 * Domain Path:       /languages

Coming Soon Maintenance Mode is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Coming Soon Maintenance Mode is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Coming Soon Maintenance Mode. If not, see https://webenvo.com/.
 */
 
// CSMM default URLs and Paths
define( 'CSMM_URL', plugin_dir_url( __FILE__ ) );

// CSMM activation
function csmm_activation() {
	// update current plugin version
	if ( is_admin() ) {
		if ( ! function_exists( 'get_plugin_data' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$csmm_plugin_data = get_plugin_data( __FILE__ );

		if ( isset( $csmm_plugin_data['Version'] ) ) {
			$csmm_plugin_version = $csmm_plugin_data['Version'];
			update_option( 'csmm_current_version', $csmm_plugin_version );
		}
	}
	
	// reset admin notice
	delete_user_meta(get_current_user_id(), 'dismissed_custom_notice');
}
register_activation_hook( __FILE__, 'csmm_activation' );

// CSMM deactivation
function csmm_deactivation() {
	// update last active plugin version
	$csmm_last_version = get_option( 'csmm_current_version' );
	if ( $csmm_last_version !== '' ) {
		update_option( 'csmm_last_version', $csmm_last_version );
	}
	
	// reset admin notice
	delete_user_meta(get_current_user_id(), 'dismissed_custom_notice');
}
register_deactivation_hook( __FILE__, 'csmm_deactivation' );

// CSMM uninstall
function csmm_uninstall() {
}
register_uninstall_hook( __FILE__, 'csmm_uninstall' );

// load translation
function csmm_load_translation() {
	load_plugin_textdomain( 'coming-soon-maintenance-mode', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'plugins_loaded', 'csmm_load_translation' );

// CSMM
function csmm_menu_page() {
	// add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	add_menu_page( __( 'Coming Soon Maintenance Mode', 'coming-soon-maintenance-mode' ), __( 'Coming Soon Maintenance Mode', 'coming-soon-maintenance-mode' ), 'manage_options', 'webenvo-csmm', 'webenvo_csmm', 'dashicons-format-gallery', 3 );
	add_submenu_page( 'webenvo-csmm', 'More Products', 'More Products', 'manage_options', 'webenvo-more-products', 'csmm_more_product');
}
add_action( 'admin_menu', 'csmm_menu_page' );

// CSMM main page body
function webenvo_csmm() {
	require 'admin/csmm.php';
}

// Our Other Plugins and Themes Page
function csmm_more_product(){
	wp_enqueue_style( 'csmm-bootstrap-admin-css' );
	wp_enqueue_style( 'cmss-product-css' );
	// Extras Page Template.
	include 'our-products/plugins-and-themes-api.php';
	include 'our-products/our-products.php';
}

// CSMM load admin scripts (CSS/JS) only on plugin pages
function csmm_admin_scripts() {
	if ( current_user_can( 'manage_options' ) ) {
		if ( isset( $_GET['page'] ) ) {
			// load plugin required CSS and JS only on plugin pages
			$sf_current_page_slug = sanitize_text_field( wp_unslash( $_GET['page'] ) );
			if ( strpos( $sf_current_page_slug, 'webenvo-' ) !== false ) {
				//core admin assets
				wp_enqueue_script('media-upload');
				wp_enqueue_media();
				wp_enqueue_script( 'csmm-uploader-js', plugins_url( 'admin/assets/js/csmm-uploader.js', __FILE__ ), array('jquery'), '1.0.0' );

				// CSS
				wp_enqueue_style( 'csmm-admin-style-css', plugin_dir_url( __FILE__ ) . 'admin/assets/css/style.css' );
				wp_enqueue_style( 'csmm-bootstrap-admin-css', plugin_dir_url( __FILE__ ) . 'admin/assets/bootstrap-5.2.3-dist/css/bootstrap.css' );
				wp_enqueue_style( 'csmm-fontawesome-admin-css', plugin_dir_url( __FILE__ ) . 'admin/assets/fontawesome-free-6.2.1-web/css/all.css' );

				// JS
				wp_enqueue_script( 'jquery', 'jquery-ui-tabs' );
    				wp_enqueue_script('jquery-effects-shake', '', '', array('jquery', 'jquery-ui-core', 'jquery-effects-core'));
				// wp_enqueue_script('csmm-color-picker-js', plugin_dir_url( __FILE__ ) . 'admin/assets/js/csmm-color-picker.js', array('jquery'), '' );
				//wp_enqueue_script( 'csmm-bootstrap-js', plugin_dir_url( __FILE__ ) . 'admin/assets/bootstrap-5.2.3-dist/js/bootstrap.js', array( 'jquery' ), '5.2.3' );
				wp_enqueue_script( 'csmm-bootstrap-bundle-js', plugin_dir_url( __FILE__ ) . 'admin/assets/bootstrap-5.2.3-dist/js/bootstrap.bundle.js', array( 'jquery' ), '5.2.3' );
				
				// product page assets
				wp_register_style( 'cmss-product-css', plugin_dir_url( __FILE__ ) . 'our-products/products.css', array(), 1.0, false );
				wp_register_script( 'csmm-product-js', plugin_dir_url( __FILE__ ) . 'our-products/products.js', array( 'jquery' ), '1.0', true );
				wp_enqueue_script( 'csmm-product-js' );
				wp_localize_script(
					'csmm-product-js',
					'CSMMExtrasAjax',
					array(
						'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
						'extnonce' => wp_create_nonce( 'csmm-extra-nonce' ),
					)
				);
			}
		}
	} // current_user_can end
}
add_action( 'admin_enqueue_scripts', 'csmm_admin_scripts' );

// upload logo callback
function csmm_logo_li_callback() {
	if ( isset($_POST['attachment_id']) ) {
		//defaults
		$csmm_logo_url = "";
		$csmm_logo_id = sanitize_text_field($_POST['attachment_id']);
		$csmm_logo_url = wp_get_attachment_image_src($csmm_logo_id, 'medium', true); // attachment medium URL
		?>
		<li class="col-md-4 csmm-logo-<?php echo esc_attr($csmm_logo_id); ?>" data-position="<?php echo esc_attr($csmm_logo_id); ?>">
			<input type="hidden" class="form-control csmm-logo-id" id="csmm-logo-id" name="csmm-logo-id" value="<?php echo esc_attr($csmm_logo_id); ?>">
			<img src="<?php echo esc_url($csmm_logo_url[0]); ?>" class="img-thumbnail mt-3 bg-light">
			<div class="d-grid gap-2">
				<button type="button" id="csmm-remove-logo" onclick="csmm_save('remove-logo', <?php echo esc_attr($csmm_logo_id); ?>);" class="btn btn-danger btn-block"><i class="fa-solid fa-trash"></i> <?php esc_html_e( 'Remove Logo', 'coming-soon-maintenance-mode' ); ?></button>
			</div>
		</li>
		<?php
		wp_die();
	}
}

// custom admin notice start
function custom_admin_notice() {
	$dismissed = get_user_meta(get_current_user_id(), 'dismissed_custom_notice', true);
	if (!$dismissed) {
		if (isset($_GET['page']) && $_GET['page'] === 'webenvo-csmm') {
			$image_url = plugin_dir_url(__FILE__) . 'admin/assets/img/portfolio-wordpress.webp'; // Replace with your image URL
			echo '<div class="notice is-dismissible awp-notice-custom">
			<a href="https://webenvo.com/ultimate-portfolio/" target="_blank"><img src="' . esc_url($image_url) . '"></a>
			</div>';
		}
	}
}
add_action('admin_notices', 'custom_admin_notice');

function custom_admin_notice_script() {
    // Create a nonce and pass it to the JavaScript
    $cmss_ajax_nonce = wp_create_nonce('dismiss_custom_notice_nonce');
    ?>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            jQuery(document).on('click', '.awp-notice-custom .notice-dismiss', function(e) {
                e.preventDefault();
                var notice = jQuery(this).closest('.awp-notice-custom');
                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: {
                        action: "dismiss_custom_notice",
                        security: '<?php echo esc_js($cmss_ajax_nonce); ?>',
                    },
                    success: function(response) {
                        notice.fadeOut(200);
                    }
                });
            });
        });
    </script>
    <style>
	.awp-notice-custom {
		background: #fff;
		box-shadow: 0 1px 1px rgba(0,0,0,.04);
		padding: 0px !important;
		border: none !important;
		position: relative;
	}
	.awp-notice-custom a {
		color: #0073aa;
		text-decoration: none;
	}
	.awp-notice-custom a:hover {
		text-decoration: underline;
	}
	.awp-notice-custom .notice-dismiss {
		background: #ff3030;
	}
	.awp-notice-custom .notice-dismiss:before {
		color:#FFF;
	}
    </style>
    <?php
}
add_action('admin_footer', 'custom_admin_notice_script');
function dismiss_custom_notice() {
    // Check the nonce
    check_ajax_referer('dismiss_custom_notice_nonce', 'security');
    // Update user meta to mark the notice as dismissed
    update_user_meta(get_current_user_id(), 'dismissed_custom_notice', '1');
    wp_send_json_success();
}
add_action('wp_ajax_dismiss_custom_notice', 'dismiss_custom_notice');
// custom admin notice end

// save CSMM start
function csmm_save() {
	if ( current_user_can( 'manage_options' ) ) {
		if ( sanitize_text_field( wp_unslash( isset( $_POST['nonce'] ) ) ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'csmm-save' ) ) {
			// verified action
			//print_r($_POST);
			$tab = sanitize_text_field($_POST['tab']);
			
			// settings data save start
			if($tab == 'settings'){
				$csmm_selected_posts = array();
				$csmm_selected_pages = array();
				$csmm_selected_other_pages = array();
				$csmm_website_mode = sanitize_text_field($_POST['website_mode']);
				if(isset($_POST['selected_posts'])) {
					$csmm_selected_posts = $_POST['selected_posts'];
					array_map('sanitize_text_field', $csmm_selected_posts);
				}
				if(isset($_POST['selected_pages'])) {
					$csmm_selected_pages = $_POST['selected_pages'];
					array_map('sanitize_text_field', $csmm_selected_pages);
				}
				if(isset($_POST['selected_other_pages'])) {
					$csmm_selected_other_pages = $_POST['selected_other_pages'];
					array_map('sanitize_text_field', $csmm_selected_other_pages);
				}
				
				$csmm_settings_array = array(
					'website_mode' => $csmm_website_mode,
					'selected_posts' => $csmm_selected_posts,
					'selected_pages' => $csmm_selected_pages,
					'selected_other_pages' => $csmm_selected_other_pages,
				);
				// unset key if no posts / pages selected
				if(count($csmm_selected_posts) <= 0) {
					unset($csmm_settings_array['selected_posts']);
				}
				if(count($csmm_selected_pages) <= 0) {
					unset($csmm_settings_array['selected_pages']);
				}
				if(count($csmm_selected_other_pages) <= 0) {
					unset($csmm_settings_array['selected_other_pages']);
				}
				update_option('csmm_settings', $csmm_settings_array);
			}
			// settings data save end
			
			// templates data save start
			if($tab == 'templates'){
				$csmm_template_id = sanitize_text_field($_POST['template_id']);
				update_option('csmm_templates', array('template_id' => $csmm_template_id));
			}
			// templates data save end
			
			// content data save start
			if($tab == 'content'){
				$csmm_logo = "";
				if(isset($_POST['logo']))
					$csmm_logo = sanitize_text_field($_POST['logo']);
				$csmm_title = sanitize_text_field($_POST['title']);
				$csmm_description = sanitize_text_field($_POST['description']);
				$csmm_countdown = sanitize_text_field($_POST['countdown']);
				$csmm_countdown_title = sanitize_text_field($_POST['countdown_title']);
				$csmm_countdown_date = sanitize_text_field($_POST['countdown_date']);
				$csmm_countdown_time = sanitize_text_field($_POST['countdown_time']);
				
				$csmm_content_array = array(
					'logo' => $csmm_logo,
					'title' => $csmm_title,
					'description' => $csmm_description,
					'countdown' => $csmm_countdown,
					'countdown_title' => $csmm_countdown_title,
					'countdown_date' => $csmm_countdown_date,
					'countdown_time' => $csmm_countdown_time,
				);
				update_option('csmm_content', $csmm_content_array);
			}
			// content data save end
			
			// social media data save start
			if($tab == 'social-media'){
				$csmm_social_media_array = array(
					'csmm_sm_facebook' => sanitize_text_field($_POST['csmm_sm_facebook']),
					'csmm_sm_twitter' => sanitize_text_field($_POST['csmm_sm_twitter']),
					'csmm_sm_youtube' => sanitize_text_field($_POST['csmm_sm_youtube']),
					'csmm_sm_instagram' => sanitize_text_field($_POST['csmm_sm_instagram']),
					'csmm_sm_linkedin' => sanitize_text_field($_POST['csmm_sm_linkedin']),
					'csmm_sm_pinterest' => sanitize_text_field($_POST['csmm_sm_pinterest']),
					'csmm_sm_tumblr' => sanitize_text_field($_POST['csmm_sm_tumblr']),
					'csmm_sm_snapchat' => sanitize_text_field($_POST['csmm_sm_snapchat']),
					'csmm_sm_behance' => sanitize_text_field($_POST['csmm_sm_behance']),
					'csmm_sm_dribbble' => sanitize_text_field($_POST['csmm_sm_dribbble']),
					'csmm_sm_whatsapp' => sanitize_text_field($_POST['csmm_sm_whatsapp']),
					'csmm_sm_tiktok' => sanitize_text_field($_POST['csmm_sm_tiktok']),
					'csmm_sm_qq' => sanitize_text_field($_POST['csmm_sm_qq']),
				);
				update_option('csmm_social_media', $csmm_social_media_array);
			}
			// social media data save end
			
			// more data save start
			if($tab == 'more'){
				$csmm_more_array = array(
				);
				update_option('csmm_more', $csmm_more_array);
			}
			// more data save end
			
			wp_die(); // this is required to terminate immediately and return a proper response
		} else {
			echo esc_html_e( 'Nonce not verified action.', 'coming-soon-maintenance-mode' );
			die;
		}
	}
}
add_action( 'wp_ajax_csmm_save', 'csmm_save' );
add_action( 'wp_ajax_nopriv_csmm_save', 'csmm_save' );
// save CSMM end

// register CSMM frontend scripts start
function csmm_frontend_scripts() {
	wp_enqueue_script( 'jquery' );
	//template 1
}
add_action( 'wp_enqueue_scripts', 'csmm_frontend_scripts' );
// register CSMM frontend scripts end

// ouput CSMM start
$csmm_website_mode = 3;
$csmm_current_date = date('Y-m-d');
$csmm_launch_dt = date('Y-m-d', strtotime($csmm_current_date . ' +30 days'));
//load CSMM content
$csmm_content = array();
$csmm_settings = array();
$csmm_content = get_option('csmm_content');
if(is_array($csmm_content)){
	
	if(array_key_exists('countdown', $csmm_content)){ $csmm_countdown = $csmm_content['countdown']; }
	if(array_key_exists('countdown_date', $csmm_content)){ $csmm_countdown_date = $csmm_content['countdown_date']; }
	if(array_key_exists('countdown_time', $csmm_content)){ $csmm_countdown_time = $csmm_content['countdown_time']; }
	
	// launch date calculation
	$csmm_launch_dt = date( 'F d, Y H:i:s',strtotime("$csmm_countdown_date $csmm_countdown_time")); // March 25, 2024 15:37:25
	$csmm_today_date = current_datetime()->format('F d, Y H:i:s'); // get time accordingly to wordpres timezone settings
}

$csmm_settings = get_option('csmm_settings');
if(is_array($csmm_settings)){
	if(array_key_exists('website_mode', $csmm_settings)){ $csmm_website_mode = $csmm_settings['website_mode']; }
}

// - coming soon mode start
if($csmm_website_mode == 1) {
	function csmm_website_mode(){
		// chekc user logged in
		if (!is_user_logged_in()) {
			include('loader.php');
			exit();
		} else {
		}
	}
	add_action( 'template_redirect', 'csmm_website_mode' );
}
// - coming soon mode end

// - maintenance soon mode start
if($csmm_website_mode == 2) {
	function csmm_website_mode(){
		// chekc user logged in
		if (!is_user_logged_in()) {
			
			global $post;
			$csmm_post_id = "";
			$csmm_post_type = "";
			$csmm_flag = false;
			$csmm_queried_object = get_queried_object();
			$csmm_posts = array();
			$csmm_pages = array();
			$csmm_other_pages = array();
			if(isset($csmm_queried_object->ID)) {
				$csmm_post_id = $csmm_queried_object->ID;
				$csmm_post_type = $csmm_queried_object->post_type;
			}
			
			$csmm_settings = get_option('csmm_settings');
			if(is_array($csmm_settings)){
				if(array_key_exists('website_mode', $csmm_settings)){ $csmm_website_mode = $csmm_settings['website_mode']; }
				if(array_key_exists('selected_posts', $csmm_settings)){ $csmm_posts = $csmm_settings['selected_posts']; }
				if(array_key_exists('selected_pages', $csmm_settings)){ $csmm_pages = $csmm_settings['selected_pages']; }
				if(array_key_exists('selected_other_pages', $csmm_settings)){ $csmm_other_pages = $csmm_settings['selected_other_pages']; }
			}
			
			// enable maintenance mode on posts
			if($csmm_post_type == "post" || is_single() ) {
				if(in_array( $csmm_post_id, $csmm_posts)) {
					$csmm_flag = true;
				}
			}
			
			// enable maintenance mode on pages - is_page
			if($csmm_post_type == "page" || is_page() ) {
				if(in_array( $csmm_post_id, $csmm_pages)) {
					$csmm_flag = true;
				}
			}
			
			// font page
			if(is_front_page()){
				if(in_array( 'front', $csmm_other_pages)) {
					$csmm_flag = true;
				}
			}
			
			// home page
			if(is_home()) {
				if(in_array( 'home', $csmm_other_pages)) {
					$csmm_flag = true;
				}
			}
			
			// category
			if(is_category()) {
				if(in_array( 'category', $csmm_other_pages)) {
					$csmm_flag = true;
				}
			}
			
			// tag
			if(is_tag()) {
				if(in_array( 'tag', $csmm_other_pages)) {
					$csmm_flag = true;
				}
			}
			
			// search
			if(is_search()) {
				if(in_array( 'search', $csmm_other_pages)) {
					$csmm_flag = true;
				}
			}
			
			if($csmm_flag) {
				include('loader.php');
				exit();
			}
			
		} else {
		}
	}
	add_action( 'template_redirect', 'csmm_website_mode' );
}
// - maintenance soon mode end

// ouput CSMM end

// live preview CSMM start
if((isset($_GET['csmm']) && $_GET['csmm'] == 'true')){
	function csmm_website_mode_preview(){
		// chekc user logged in
		include('loader.php');
		exit();
	}
	add_action( 'template_redirect', 'csmm_website_mode_preview' );
}
// ouput CSMM end

// restrict rest api for maintenance mode start
function cmss_restrict_rest_api_for_maintenance_mode($result, $server, $request) {
    // Check if the maintenance mode is enabled in your plugin's settings
    $csmm_website_mode = 3; // default mode live
    $csmm_settings = get_option('csmm_settings');
	if(is_array($csmm_settings)){
		if(array_key_exists('website_mode', $csmm_settings)){ $csmm_website_mode = $csmm_settings['website_mode']; }
	}

    // Restrict access to posts and pages for unauthenticated users if maintenance mode is enabled
    if ($csmm_website_mode && !is_user_logged_in()) {
        // Check if the request is for posts or pages
        if (strpos($request->get_route(), '/wp/v2/posts') !== false || strpos($request->get_route(), '/wp/v2/pages') !== false) {
            return new WP_Error('rest_forbidden', esc_html__('The site is in maintenance mode.', 'your-plugin-text-domain'), array('status' => rest_authorization_required_code()));
        }
    }

    return $result;
}
add_filter('rest_pre_dispatch', 'cmss_restrict_rest_api_for_maintenance_mode', 10, 3);
// restrict rest api for maintenance mode end
?>