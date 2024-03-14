<?php
/*
 * Plugin Name:     Customizer Login Page
 * Description: 	Easily customize your "WordPress login page" with our intuitive tool which enhances WordPress customizer with editability of WordPress login Page. Personalize colors, designs, and logos to match your style, and enhance user experience with customizable forms and buttons. Make WordPress login page uniquely yours.
 * Version: 		2.0.2
 * Author: 			farazfrank
 * Author URI: 		https://awplife.com/
 * License: 		GPL-2.0+
 * License URI: 	https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     loginpc
 * Domain Path:     /languages
*/

/** Exit if accessed directly **/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** Define Plugin URL and Directory */
define( 'LOGINPC_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'LOGINPC_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
define( 'LOGINPC_ADMIN_URL', admin_url() );

if ( is_multisite() ) {
	/**
	 * Register CUstom Login Page.
	 * */
	function lpc_reg_custom_login_page() {
		// Check page exists.
		$lpc_exist_page = get_page_by_title( 'Customizer Login Page Plugin' );

		if ( ! $lpc_exist_page ) {
			$lpc_page = array(
				'post_title'   => 'Customizer Login Page Plugin',
				'post_content' => 'This page is used by Customizer Login Page Plugin.',
				'post_status'  => 'publish',
				'post_type'    => 'page',
			);

			// Insert the page and get its ID.
			$lpc_page_id = wp_insert_post( $lpc_page );

			// Store the page ID for later use.
			update_option( 'lpc_login_page_id', $lpc_page_id );
		} else {
			// Page already exists, so fetch and store its ID.
			update_option( 'lpc_login_page_id', $lpc_exist_page->ID );
		}
	}
	add_action( 'after_setup_theme', 'lpc_reg_custom_login_page' );

	/**
	 * Custom template handling for multisite plugin.
	 */
	function lpc_login_page_template_include( $template ) {
		global $post;

		// Check if the current post has a specific slug.
		if ( $post->post_name === 'customizer-login-page-plugin' ) {
			// Get the path to your template file in the plugin.
			$plugin_template = plugin_dir_path( __FILE__ ) . 'customize/template-lpc-custom-login-page.php';

			// Use the plugin's template file for the specific page.
			return $plugin_template;
		}

		return $template; // For other pages, return the original template.
	}

	// Add a filter to the template include to use your custom template for the specific page.

	add_filter( 'template_include', 'lpc_login_page_template_include' );
}
/**
 * Add Menu page.
 */
function loginpc_add_menu_page() {
	add_menu_page(
		'LoginPC',                  // Page title.
		'LoginPC',                  // Menu title.
		'manage_options',           // Capability required to access the page.
		'loginpc-settings',         // Menu slug.
		'loginpc_callback',         // Callback function to render the page.
		'dashicons-admin-generic',  // Icon URL or dashicon class.
		80                          // Position in the menu.
	);
}


/**
 * Menu page Callback.
 * Render the menu page.
 */
function loginpc_callback() {
	// Define the base path for the images.
	$lpc_image_base_path = LOGINPC_PLUGIN_URL . 'assets/presets/images/';

	// Define the URL for the Pro purchase link.
	$pro_purchase_link = 'https://awplife.com/wordpress-plugins/customizer-login-page-premium/';

	// Array of image names (without '_thumb' and file extension).
	$lpc_image_names = array( 'anime', 'celebrate', 'chirp', 'circle', 'colorful', 'crypto', 'crystal', 'darkaqua', 'education', 'gaming', 'gradient', 'invite', 'lock', 'medical', 'naturetech', 'park', 'portal', 'secure' /* ... other image names ... */ );
	?>
	<div class="loginpc-wrap">
		<h1 class="loginpc-title"><?php esc_html_e( 'Customizer Login Page ', 'customizer-login-page' ); ?></h1>
		<p class="loginpc-description"><?php esc_html_e( 'Welcome to Customizer Login Page ! Let\'s start customizing your login page by using WordPress customizer.', 'customizer-login-page' ); ?></p>
		<a href="<?php echo esc_url( loginpc_get_customizer_url() ); ?>" id="submit" class="button button-primary loginpc-button">
			<?php esc_html_e( 'Start Customizing Login Page', 'customizer-login-page' ); ?>&nbsp;<span class="dashicons dashicons-admin-customizer"></span>
		</a>
	</div>
	<div class="loginpc-wrap">
		<h1 class="loginpc-pro-title"><span class="loginpc-pro-word"><?php esc_html_e( ' Pro', 'customizer-login-page' ); ?></span><?php esc_html_e( ' Presets - Customizer Login Page ', 'customizer-login-page' ); ?></h1>
		<div class="loginpc-image-grid">
			<?php foreach ( $lpc_image_names as $name ) : ?>
				<div class="loginpc-grid-item">
					<a href="<?php echo esc_url( $pro_purchase_link ); ?>" class="image-link" target="_blank">
						<img src="<?php echo esc_url( $lpc_image_base_path . $name . '_demo.png' ); ?>" alt="<?php echo esc_attr( $name ); ?>">
						<span class="image-name-link"><?php echo esc_html( ucfirst( $name ) ); ?></span>
					</a>
				</div>
			<?php endforeach; ?>
		</div>
	</div>
	<style>
		.loginpc-wrap {
			margin: 20px 20px 20px 0;
			background: #fff;
			padding: 15px 20px;
			box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
			text-align: center;
			border-radius: 15px;
			width: auto;
		}
		.loginpc-title {
			font-size: 40px;
			font-weight: 700;
			color: #333;
			margin-bottom: 40px;
			padding: 10px 0px;
		}
		.loginpc-pro-title {
			font-size: 30px;
			color: #333;
			margin-bottom: 40px;
			margin-top: 5px;
			padding: 10px 0px;
		}
		.loginpc-pro-word{
			/* color: #2271b1;
			background: #1D2327; */
		}
		.loginpc-description {
			font-size: 18px;
			font-weight: 500;
			color: #666;
			margin-bottom: 30px;
		}
		.loginpc-button {
			display: inline-flex;
			align-items: center;
			font-size: 15px !important;
			font-weight: 600;
			padding: 10px 15px !important;
			transition: transform 0.2s, box-shadow 0.2s;
			text-decoration: none;
		}
		.loginpc-button:hover {
			transform: translateY(-3px);
			box-shadow: 0 5px 30px rgba(0, 0, 0, 0.1);
			background-color: #0056b3;
			color: #fff;
		}
		.loginpc-button .dashicons {
			margin-left: 8px;
			font-size: 20px;
		}
		.loginpc-image-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
			gap: 10px;
		}
		.loginpc-grid-item {
			overflow: hidden;
			position: relative;
			text-align: center;
		}
		.loginpc-grid-item img {
			width: 100%;
			height: auto;
			transition: transform 0.3s ease;
		}
		.loginpc-grid-item:hover img {
			transform: scale(1.1);
		}
		.loginpc-grid-item .image-link {
			text-decoration: none; /* Remove underline from links */
			color: inherit; /* Inherit text color to prevent default anchor tag color */
		}
		.loginpc-grid-item .image-name-link {
			display: block;
			margin-top: 7px;
			font-size: 18px;
			color: #0056b3;
			font-weight: bold;
			background-color: rgba(255, 255, 255, 0.8);
			padding: 10px 10px;
			border-radius: 0px  0px 5px 5px;
			box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
			transition: background-color 0.3s ease, color 0.6s ease;
		}
		.loginpc-grid-item:hover .image-name-link {
			background-color: #2271b1;
			color: #f0f0f1;
		}
	</style>
	<?php
}
/**
 * Generates the customizer URL for single site or multisite environment.
 */
function loginpc_get_customizer_url() {
	if ( is_multisite() ) {
		// Handle multisite environment.
		$lpc_page_id  = get_option( 'lpc_login_page_id' );
		$lpc_page_url = get_permalink( $lpc_page_id );
		// Generate the redirect url with safe redirection.
		return add_query_arg(
			array(
				'url'               => $lpc_page_url,
				'loginpc_customize' => 'true',
			),
			admin_url( 'customize.php' )
		);
	} else {
		// Handle single site.
		$lpc_page_url = wp_login_url();
		// Generate the redirect url with safe redirection.
		return add_query_arg(
			array(
				'url'               => $lpc_page_url,
				'loginpc_customize' => 'true',
			),
			admin_url( 'customize.php' )
		);
	}
}
add_action( 'admin_menu', 'loginpc_add_menu_page' );
/** Enqueue Customizer preview scripts */
function lpc_customizer_preview_scripts() {
	wp_enqueue_script( 'customize-preview' );
}
add_action( 'customize_preview_init', 'lpc_customizer_preview_scripts' );
/** Customizer Custom Controls. */
function lpc_custom_controls( $wp_customize ) {
	require LOGINPC_PLUGIN_DIR . 'customize/lpc-custom-controls/lpc-custom-controls.php';
}
add_action( 'customize_register', 'lpc_custom_controls' );
/** Font Selector */
function lpc_font_customize_classes( $wp_customize ) {
	require LOGINPC_PLUGIN_DIR . 'customize/customizer-font-selector/functions.php';
}
add_action( 'customize_register', 'lpc_font_customize_classes', 0 );
/** Load LPC Customizer settings */
require LOGINPC_PLUGIN_DIR . 'customize/class-loginpc-customizer.php';
new loginpc_Customize();

/**
 * Function to remove panels.
 *
 * @param array $components Loaded components in the Customizer.
 * @return array Modified array of loaded components.
 */
function loginpc_hide_other_panels( $components ) {
	// Check if the customizer is accessed through the plugin's "Start Customizing" button.
	if ( isset( $_GET['loginpc_customize'] ) && $_GET['loginpc_customize'] === 'true' ) {
		// Keep only the 'lpc-main-panel' and remove all other panels.
		$panels_to_keep = array( 'lpc-main-panel' );

		// Remove all panels except 'lpc-main-panel'.
		$components = array_intersect( $components, $panels_to_keep );
	}

	return $components;
}
add_filter( 'customize_loaded_components', 'loginpc_hide_other_panels' );


/** Function to remove sections.
 *
 * @param wp_customize $wp_customize required to register customization.
 */
function loginpc_hide_other_sections( $wp_customize ) {
	// Check if the customizer is accessed through the plugin's "Start Customizing" button.
	if ( isset( $_GET['loginpc_customize'] ) && $_GET['loginpc_customize'] === 'true' ) {
		// Define the sections to remove.
		$sections_to_remove = array( 'themes', 'options', 'cover_template_options', 'background_image', 'nav_menus', 'static_front_page', 'custom_css' ); // Add more section IDs as needed.

		// Remove specific core sections.
		foreach ( $sections_to_remove as $section ) {
			$wp_customize->remove_section( $section );
		}
	}
}
add_action( 'customize_register', 'loginpc_hide_other_sections', 999 );

/** Function to get LPC Options */
function lpc_get_mod( $setting_name, $default = '' ) {
	// Fetch the entire set of options.
	$options = get_option( 'lpc_opts', array() );

	// Check if the setting exists within the options and return it.
	if ( isset( $options[ $setting_name ] ) ) {
		return $options[ $setting_name ];
	}

	// If the setting wasn't found, return the provided default value.
	return $default;
}
/** Function to set LPC options */
function lpc_set_mod( $setting_name, $value ) {
	// Get the current settings.
	$lpc_opts = get_option( 'lpc_opts', array() );

	// Update the specified setting.
	$lpc_opts[ $setting_name ] = $value;

	// Save the updated settings back to the database.
	update_option( 'lpc_opts', $lpc_opts );
}

/** Enqueue styles and scripts for customizer items. */
function loginpc_enqueue_customizer_items() {
		wp_register_style( 'lpc-customizer-styles-css', LOGINPC_PLUGIN_URL . 'assets/css/login-page-customizer.css', array(), '1.0.0' );
		wp_enqueue_style( 'lpc-customizer-styles-css' );
		wp_register_style( 'lpc-customizer-hide-css', LOGINPC_PLUGIN_URL . 'customize/customizer-hide.css', array(), '1.0.0' );
		wp_enqueue_style( 'lpc-customizer-hide-css' );
		wp_register_style( 'lpc-custom-controls-css', LOGINPC_PLUGIN_URL . 'customize/lpc-custom-controls/lpc-custom-controls.css', array(), '1.0.0' );
		wp_enqueue_style( 'lpc-custom-controls-css' );
		wp_register_script( 'lpc-custom-controls-js', LOGINPC_PLUGIN_URL . 'customize/lpc-custom-controls/lpc-custom-controls.js', array( 'jquery' ), '1.0.0', true );
		wp_enqueue_script( 'lpc-custom-controls-js' );
		wp_register_script( 'lpc-customize-script-js', LOGINPC_PLUGIN_URL . 'customize/lpc-customize-scripts.js', array( 'jquery', 'customize-controls' ), '1.0.0', true );
		wp_enqueue_script( 'lpc-customize-script-js' );
		// Array for localize.
		$lpc_localize = array(
			'plugin_url'           => plugins_url(),
			'admin_url'            => admin_url(),
			'title_ctrl_image_url' => esc_url( plugin_dir_url( __FILE__ ) . 'assets/images/customizer/lpc_page_title.png' ),
			'lpc_bg_img_url'       => lpc_get_mod( 'lpc-background-image' ),
			'lpc_bg_video_url'     => lpc_get_mod( 'lpc-background-video' ),
		);
		wp_localize_script( 'lpc-customize-script-js', 'lpc_script', $lpc_localize );
		wp_localize_script( 'lpc-custom-controls-js', 'lpcExportURL', array( 'exportURL' => admin_url( 'admin.php?lpc-export=1&_wpnonce=' . wp_create_nonce( 'lpc-export-nonce' ) ) ) );
		wp_localize_script(
			'lpc-custom-controls-js',
			'lpcAjax',
			array(
				'url'   => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'lpc-import-nonce' ),
			)
		);
		wp_register_script( 'lpc-preset-handler-js', LOGINPC_PLUGIN_URL . 'customize/lpc-preset-handler.js', array( 'customize-controls' ), '1.0.0', true );
		wp_enqueue_script( 'lpc-preset-handler-js' );
		wp_localize_script(
			'lpc-preset-handler-js',
			'lpcpluginurl',
			array(
				'url'  => plugins_url(),
				'site' => LOGINPC_PLUGIN_URL,
			)
		);
}
add_action( 'customize_controls_enqueue_scripts', 'loginpc_enqueue_customizer_items' );

/** Base Style and Script */
function lpc_enqueue_login_base_scripts() {
	wp_register_style( 'lpc-style-main', LOGINPC_PLUGIN_URL . 'assets/css/lpc-style-main.css', array(), '1.0.0' );
	wp_enqueue_style( 'lpc-style-main' );
	wp_register_script( 'lpc-script-main', LOGINPC_PLUGIN_URL . 'assets/js/lpc-script-main.js', array( 'jquery' ), '1.0.0', true );
	wp_enqueue_script( 'lpc-script-main' );
}
add_action( 'login_enqueue_scripts', 'lpc_enqueue_login_base_scripts' );

/** Export Function */
function lpc_export_customizer_settings() {
	if ( ! isset( $_GET['lpc-export'] ) ) {
		return;
	}

	check_admin_referer( 'lpc-export-nonce' );

	$settings = get_option( 'lpc_opts' ); // Fetch all settings.

	$filename = 'loginpc-settings-export-' . gmdate( 'Ymd-His' ) . '.json';

	header( 'Content-Description: File Transfer' );
	header( 'Content-Disposition: attachment; filename=' . $filename );
	header( 'Content-Type: application/json; charset=' . get_option( 'blog_charset' ), true );

	echo wp_json_encode( $settings );

	exit;
}
add_action( 'admin_init', 'lpc_export_customizer_settings' );

function lpc_import_customizer_settings() {
	check_ajax_referer( 'lpc-import-nonce', 'nonce' );

	$file = $_FILES['lpc-import-file'];

	// Check for uploaded file type.
	$file_type = wp_check_filetype(
		basename( $file['name'] ),
		array(
			'json' => 'application/json',
		)
	);

	if ( $file_type['type'] != 'application/json' ) {
		wp_send_json_error( array( 'message' => 'Invalid file type.' ) );
		return;
	}

	// Initialize WP filesystem.
	require_once ABSPATH . 'wp-admin/includes/file.php';
	WP_Filesystem();
	global $wp_filesystem;

	$settings = $wp_filesystem->get_contents( $file['tmp_name'] );

	if ( $settings = json_decode( $settings, true ) ) {
		update_option( 'lpc_opts', $settings );
		wp_send_json_success();
	} else {
		wp_send_json_error( array( 'message' => 'Invalid file.' ) );
	}
}

add_action( 'wp_ajax_lpc_import_customizer_settings', 'lpc_import_customizer_settings' );

/** Login Page Title.
 *
 * @param string $title Loaded components in the Customizer.
 */
function lpc_custom_login_title( $title ) {
	$custom_title = lpc_get_mod( 'lpc-title-text' );

	// If a custom title is set, replace the default title.
	if ( $custom_title ) {
		$title = $custom_title;
	}

	return $title;
}
add_filter( 'login_title', 'lpc_custom_login_title' );

/** Login Pc Customizer Notice */
function loginpc_modify_customizer_preview_notice() {
	if ( isset( $_GET['loginpc_customize'] ) && $_GET['loginpc_customize'] === 'true' ) {
		?>
		<script>
			jQuery(document).ready(function ($) {
				var previewNotice = $('#customize-info .preview-notice');
				var newNoticeText = 'Customizer Login Page';
				previewNotice.html('You are customizing with <strong class="panel-title site-title">' + newNoticeText + '</strong>');
			});
		</script>
		<?php
	}
}
add_action( 'customize_controls_print_footer_scripts', 'loginpc_modify_customizer_preview_notice' );

/** Login Logo Functionality Core. */
function lpc_login_logo() {
	?>
	<style type="text/css">
		#login h1 a, .login h1 a {
		<?php
		// Login Logo Image.
		$lpc_logo_url           = lpc_get_mod( 'lpc-logo-image', LOGINPC_ADMIN_URL . 'images/wordpress-logo.svg' );
		$lpc_logo_height        = lpc_get_mod( 'lpc-logo-height', '65' );
		$lpc_logo_width         = lpc_get_mod( 'lpc-logo-width', '320' );
		$lpc_logo_padding       = lpc_get_mod( 'lpc-logo-padding', '30' );
		$lpc_logo_margin_top    = lpc_get_mod( 'lpc-logo-margin-top', '0' );
		$lpc_logo_margin_bottom = lpc_get_mod( 'lpc-logo-margin-bottom', '25' );
		// display Logo.
		if ( lpc_get_mod( 'lpc-logo-enable', 1 ) == 0 ) {
			?>
		display:none; <?php } ?>
		position: relative;
		background-image: url(
		<?php
		if ( empty( $lpc_logo_url ) ) {
			echo esc_url( admin_url() . 'images/wordpress-logo.svg' );
		} else {
			echo esc_url( $lpc_logo_url );}
		?>
		);
		height:<?php echo esc_attr( $lpc_logo_height ); ?>px;
		width:<?php echo esc_attr( $lpc_logo_width ); ?>px;
		background-size: contain;
		background-repeat: no-repeat;
		padding-bottom: <?php echo esc_attr( $lpc_logo_padding ); ?>px;
		margin-top: <?php echo esc_attr( $lpc_logo_margin_top ); ?>px;
		margin-bottom: <?php echo esc_attr( $lpc_logo_margin_bottom ); ?>px;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_login_logo' );

/** Logo Url
 *
 * @param string $url Loaded components in the Customizer.
 */
function lpc_login_logo_url( $url ) {
	// Retrieve the logo link URL from the Customizer setting.
	$logo_link = lpc_get_mod( 'lpc-logo-link', '' );

	// If a logo link URL is set, use it; otherwise, use the default URL.
	$url = ! empty( $logo_link ) ? esc_url( $logo_link ) : '#';

	return $url;
}
add_filter( 'login_headerurl', 'lpc_login_logo_url' );

/** Login Background Image */
function lpc_login_background_img() {
	$lpc_bg_image        = lpc_get_mod( 'lpc-background-image' );
	$lpc_bg_color_choice = lpc_get_mod( 'lpc-background-color-choice', 'solid' );
	$lpc_bg_solid_color  = lpc_get_mod( 'lpc-background-color', '#C3C4C7' );
	?>
	<style type="text/css">
		body.login {
			position: relative;
			background-image: url(<?php echo esc_attr( $lpc_bg_image ); ?>);
		<?php if ( $lpc_bg_color_choice == 'solid' ) { ?>
			background-color: <?php echo esc_attr( $lpc_bg_solid_color ); ?>;
		<?php } elseif ( ( $lpc_bg_color_choice == 'gradient' ) && empty( $lpc_bg_image ) ) { ?>
			background-color: <?php echo esc_attr( $lpc_bg_solid_color ); ?>;
		<?php } ?>
			background-repeat: no-repeat;
			background-position: center;
			background-size: cover;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_login_background_img' );

/** Login Background Form Related */
function lpc_login_form() {
	/** Outer Form */
	$lpc_from_width        = lpc_get_mod( 'lpc-form-width', '320' );
	$lpc_from_height       = lpc_get_mod( 'lpc-form-height', '600' );
	$lpc_from_padding_tb   = lpc_get_mod( 'lpc-form-padding-tb', '24' );
	$lpc_from_padding_lr   = lpc_get_mod( 'lpc-form-padding-lr', '34' );
	$lpc_from_bg_color     = lpc_get_mod( 'lpc-form-bg-color', '#fff' );
	$lpc_form_border_style = lpc_get_mod( 'lpc-form-border-style', 'none' );
	$lpc_form_border_width = lpc_get_mod( 'lpc-form-border-width', '2' );
	$lpc_form_border_color = lpc_get_mod( 'lpc-form-border-color', '#000' );
	$lpc_form_position_top = lpc_get_mod( 'lpc-form-position-top', '5' );
	/** Inner Form */
	$lpc_inner_form_width          = lpc_get_mod( 'lpc-inner-form-width', '270' );
	$lpc_inner_form_height         = lpc_get_mod( 'lpc-inner-form-height', '230' );
	$lpc_inner_form_padding_top    = lpc_get_mod( 'lpc-inner-form-padding-top', '26' );
	$lpc_inner_form_padding_right  = lpc_get_mod( 'lpc-inner-form-padding-right', '24' );
	$lpc_inner_form_padding_bottom = lpc_get_mod( 'lpc-inner-form-padding-bottom', '34' );
	$lpc_inner_form_padding_left   = lpc_get_mod( 'lpc-inner-form-padding-left', '24' );
	$lpc_inner_form_margin_top     = lpc_get_mod( 'lpc-inner-form-margin-top', '24' );
	$lpc_inner_form_margin_right   = lpc_get_mod( 'lpc-inner-form-margin-right', '24' );
	$lpc_inner_form_margin_bottom  = lpc_get_mod( 'lpc-inner-form-margin-bottom', '0' );
	$lpc_inner_form_margin_left    = lpc_get_mod( 'lpc-inner-form-margin-left', '0' );
	$lpc_inner_form_border_style   = lpc_get_mod( 'lpc-inner-form-border-style', 'solid' );
	$lpc_inner_form_border_width   = lpc_get_mod( 'lpc-inner-form-border-width', '2' );
	$lpc_inner_form_border_color   = lpc_get_mod( 'lpc-inner-form-border-color', '#c3c4c7' );
	$lpc_inner_from_bg_color       = lpc_get_mod( 'lpc-inner-form-bg-color', '#fff' );
	?>
	<style>
	body.login div#login {
		position: relative;
		min-width: 240px;
		min-height: 500px;	
		width: <?php echo esc_attr( $lpc_from_width ); ?>px;
		height: <?php echo esc_attr( $lpc_from_height ); ?>px;
		background-color: <?php echo esc_attr( $lpc_from_bg_color ); ?>;
		padding: <?php echo esc_attr( $lpc_from_padding_tb ); ?>px <?php echo esc_attr( $lpc_from_padding_lr ); ?>px;
		border-style: <?php echo esc_attr( $lpc_form_border_style ); ?>;
		border-width: <?php echo esc_attr( $lpc_form_border_width ); ?>px;
		border-color: <?php echo esc_attr( $lpc_form_border_color ); ?>;
		border-radius: 15px;
		top: <?php echo esc_attr( $lpc_form_position_top ); ?>%;
	}
	body.login div#login form#loginform {
		position: relative;
		min-height: 180px;
		min-width: 180px;
		width:<?php echo esc_attr( $lpc_inner_form_width ); ?>px;
		height:<?php echo esc_attr( $lpc_inner_form_height ); ?>px;
		padding-top: <?php echo esc_attr( $lpc_inner_form_padding_top ); ?>px;
		padding-right: <?php echo esc_attr( $lpc_inner_form_padding_right ); ?>px;
		padding-bottom: <?php echo esc_attr( $lpc_inner_form_padding_bottom ); ?>px;
		padding-left: <?php echo esc_attr( $lpc_inner_form_padding_left ); ?>px;
		margin-top: <?php echo esc_attr( $lpc_inner_form_margin_top ); ?>px;
		margin-right: <?php echo esc_attr( $lpc_inner_form_margin_right ); ?>px;
		margin-bottom: <?php echo esc_attr( $lpc_inner_form_margin_bottom ); ?>px;
		margin-left: <?php echo esc_attr( $lpc_inner_form_margin_left ); ?>px;
		border-style: <?php echo esc_attr( $lpc_inner_form_border_style ); ?>;
		border-width: <?php echo esc_attr( $lpc_inner_form_border_width ); ?>px;
		border-color: <?php echo esc_attr( $lpc_inner_form_border_color ); ?>;
		border-radius: 15px;
		background-color: <?php echo esc_attr( $lpc_inner_from_bg_color ); ?>;
	}
	body.login div#login form#lostpasswordform,
	body.login div#login form#registerform {
		position: relative;
		/* min-height: 180px; */
		min-width: 180px;
		width:<?php echo esc_attr( $lpc_inner_form_width ); ?>px;
		/* height:<?php echo esc_attr( $lpc_inner_form_height ); ?>px; */
		padding-top: <?php echo esc_attr( $lpc_inner_form_padding_top ); ?>px;
		padding-right: <?php echo esc_attr( $lpc_inner_form_padding_right ); ?>px;
		padding-bottom: <?php echo esc_attr( $lpc_inner_form_padding_bottom ); ?>px;
		padding-left: <?php echo esc_attr( $lpc_inner_form_padding_left ); ?>px;
		margin-top: <?php echo esc_attr( $lpc_inner_form_margin_top ); ?>px;
		margin-right: <?php echo esc_attr( $lpc_inner_form_margin_right ); ?>px;
		margin-bottom: <?php echo esc_attr( $lpc_inner_form_margin_bottom ); ?>px;
		margin-left: <?php echo esc_attr( $lpc_inner_form_margin_left ); ?>px;
		border-style: <?php echo esc_attr( $lpc_inner_form_border_style ); ?>;
		border-width: <?php echo esc_attr( $lpc_inner_form_border_width ); ?>px;
		border-color: <?php echo esc_attr( $lpc_inner_form_border_color ); ?>;
		border-radius: 15px;
		background-color: <?php echo esc_attr( $lpc_inner_from_bg_color ); ?>;
	}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_login_form' );

/** Login Form Inputs */
function lpc_form_inputs() {
	$lpc_inputs_labels_color       = lpc_get_mod( 'lpc-form-inputs-labels-color', '#3c434a' );
	$lpc_inputs_labels_size        = lpc_get_mod( 'lpc-form-inputs-labels-size', '14' );
	$lpc_inputs_text_width         = lpc_get_mod( 'lpc-form-inputs-text-width', '100' );
	$lpc_inputs_text_background    = lpc_get_mod( 'lpc-form-inputs-tb-color', '#ffffff' );
	$lpc_inputs_text_color         = lpc_get_mod( 'lpc-form-inputs-text-color', '#2c3338' );
	$lpc_inputs_text_height        = lpc_get_mod( 'lpc-form-inputs-text-height', '40' );
	$lpc_inputs_text_margin_top    = lpc_get_mod( 'lpc-form-inputs-text-margin-top', '0' );
	$lpc_inputs_text_margin_bottom = lpc_get_mod( 'lpc-form-inputs-text-margin-bottom', '16' );
	$lpc_inputs_text_font_size     = lpc_get_mod( 'lpc-form-inputs-text-font-size', '18' );
	$lpc_inputs_text_padding_tb    = lpc_get_mod( 'lpc-form-inputs-text-padding-tb', '3' );
	$lpc_inputs_text_padding_lr    = lpc_get_mod( 'lpc-form-inputs-text-padding-lr', '5' );
	?>
	<style>
		.login #login #loginform p label[for="user_login"],
		.login #login #loginform p label[for="user_pass"],
		.login #login #loginform p:has(label[for="user_login"]),
		.login #login #registerform p:has(label[for="user_login"]),
		.login #login #loginform .user-pass-wrap label[for="user_pass"] {
			display:block;
		}
		.login #login #loginform .user-pass-wrap .wp-pwd {
			display: inline-block;
		}
		.login #login #loginform label {
			color: <?php echo esc_attr( $lpc_inputs_labels_color ); ?>;
			font-size: <?php echo esc_attr( $lpc_inputs_labels_size ); ?>px;
		}
		.login #login #lostpasswordform label,
		.login #login #registerform label,
		.login #login #registerform #reg_passmail {
			color: <?php echo esc_attr( $lpc_inputs_labels_color ); ?>;
			font-size: <?php echo esc_attr( $lpc_inputs_labels_size ); ?>px;
		}
		.login #login #loginform #user_login,
		.login #login #registerform #user_login,
		.login #login #loginform .wp-pwd {
			width: <?php echo esc_attr( $lpc_inputs_text_width ); ?>%;
		}
		.login #login #loginform input#user_login,
		.login #login #loginform input#user_pass,
		.login #login #lostpasswordform input#user_login,
		.login #login #registerform input#user_login,
		.login #login #registerform input#user_email
		{
			height:	<?php echo esc_attr( $lpc_inputs_text_height ); ?>px;
			padding: <?php echo esc_attr( $lpc_inputs_text_padding_tb ); ?>px <?php echo esc_attr( $lpc_inputs_text_padding_lr ); ?>px;
			font-size: <?php echo esc_attr( $lpc_inputs_text_font_size ); ?>px;
			margin-top: <?php echo esc_html( $lpc_inputs_text_margin_top ); ?>px;
			margin-bottom: <?php echo esc_html( $lpc_inputs_text_margin_bottom ); ?>px;
			background-color: <?php echo esc_html( $lpc_inputs_text_background ); ?>;
			color: <?php echo esc_html( $lpc_inputs_text_color ); ?>;
		}
		.login #login #loginform p.forgetmenot {
			float:none;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_form_inputs' );

function lpc_form_button_settings() {
		$lpc_form_button_align              = lpc_get_mod( 'lpc-form-button-align', 'center' );
		$lpc_form_button_width              = lpc_get_mod( 'lpc-form-button-width', '50' );
		$lpc_form_button_height             = lpc_get_mod( 'lpc-form-button-height', '32' );
		$lpc_form_button_font_size          = lpc_get_mod( 'lpc-form-button-font-size', '13' );
		$lpc_form_button_margin_top         = lpc_get_mod( 'lpc-form-button-margin-top', '20' );
		$lpc_form_button_margin_right       = lpc_get_mod( 'lpc-form-button-margin-right', '0' );
		$lpc_form_button_margin_bottom      = lpc_get_mod( 'lpc-form-button-margin-bottom', '0' );
		$lpc_form_button_margin_left        = lpc_get_mod( 'lpc-form-button-margin-left', '0' );
		$lpc_form_button_padding_tb         = lpc_get_mod( 'lpc-form-button-padding-tb', '1' );
		$lpc_form_button_padding_lr         = lpc_get_mod( 'lpc-form-button-padding-lr', '12' );
		$lpc_form_button_color              = lpc_get_mod( 'lpc-form-button-color', '#2271b1' );
		$lpc_form_button_color_hover        = lpc_get_mod( 'lpc-form-button-color-hover', '#135e96' );
		$lpc_form_button_text_color         = lpc_get_mod( 'lpc-form-button-text-color', '#fff' );
		$lpc_form_button_text_color_hover   = lpc_get_mod( 'lpc-form-button-text-color-hover', '#fff' );
		$lpc_form_button_border_style       = lpc_get_mod( 'lpc-form-button-border-style', 'solid' );
		$lpc_form_button_border_width       = lpc_get_mod( 'lpc-form-button-border-width', '2' );
		$lpc_form_button_border_color       = lpc_get_mod( 'lpc-form-button-border-color', '#2271b1' );
		$lpc_form_button_border_hover_color = lpc_get_mod( 'lpc-form-button-border-hover-color', '#135e96' );
		$lpc_form_button_box_shadow         = lpc_get_mod( 'lpc-form-button-box-shadow' );
	?>
	<style>
		.login #login #loginform p.submit,
		.login #login #lostpasswordform p.submit,
		.login #login #registerform p.submit {
			display: flex;
			justify-content: center;
		}
		.login #login #loginform p.submit #wp-submit,
		.login #login #lostpasswordform p.submit #wp-submit,
		.login #login #registerform p.submit #wp-submit {
			width: <?php echo esc_attr( $lpc_form_button_width ); ?>%;
			height: <?php echo esc_attr( $lpc_form_button_height ); ?>%;
			min-height: 10px;
			float: none;
			font-size: <?php echo esc_attr( $lpc_form_button_font_size ); ?>px;
			margin-top: <?php echo esc_attr( $lpc_form_button_margin_top ); ?>px;
			margin-right: <?php echo esc_attr( $lpc_form_button_margin_right ); ?>px;
			margin-bottom: <?php echo esc_attr( $lpc_form_button_margin_bottom ); ?>px;
			margin-left: <?php echo esc_attr( $lpc_form_button_margin_left ); ?>px;
			padding: <?php echo esc_attr( $lpc_form_button_padding_tb ); ?>px <?php echo esc_attr( $lpc_form_button_padding_lr ); ?>px;
			background: <?php echo esc_attr( $lpc_form_button_color ); ?>;
			color: <?php echo esc_attr( $lpc_form_button_text_color ); ?>;
			border-style: <?php echo esc_attr( $lpc_form_button_border_style ); ?>;
			border-width: <?php echo esc_attr( $lpc_form_button_border_width ); ?>px;
			border-color: <?php echo esc_attr( $lpc_form_button_border_color ); ?>;
			border-radius: 3px;
		}
		.login #login #loginform p.submit #wp-submit:hover,
		.login #login #lostpasswordform p.submit #wp-submit:hover,
		.login #login #registerform p.submit #wp-submit:hover{
			background: <?php echo esc_attr( $lpc_form_button_color_hover ); ?>;
			color: <?php echo esc_attr( $lpc_form_button_text_color_hover ); ?>;
			border-color: <?php echo esc_attr( $lpc_form_button_border_hover_color ); ?>;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_form_button_settings' );

function lpc_lost_pass_link_settings() {
			$lpc_form_lostpass_enable           = lpc_get_mod( 'lpc-form-lostpass-enable', 1 );
			$lpc_form_lostpass_font_size        = lpc_get_mod( 'lpc-form-lostpass-font-size', '13' );
			$lpc_form_lostpass_text_color       = lpc_get_mod( 'lpc-form-lostpass-text-color', '#50575e' );
			$lpc_form_lostpass_text_color_hover = lpc_get_mod( 'lpc-form-lostpass-text-color-hover', '#135e96' );

	if ( $lpc_form_lostpass_enable == 0 ) {
		?>
		<style>
		.login #login p#nav {
			display: none;
		}
		</style>
		<?php
	}
	?>
		<style>
		.login #login p#nav {
			position:relative;
			text-align: left;
			color: <?php echo esc_attr( $lpc_form_lostpass_text_color ); ?>;
			font-size: <?php echo esc_attr( $lpc_form_lostpass_font_size ); ?>px;
		}
		.login #login p#nav a {
			color: <?php echo esc_attr( $lpc_form_lostpass_text_color ); ?>;
			font-size: <?php echo esc_attr( $lpc_form_lostpass_font_size ); ?>px;
		}
		.login #login p#nav a:hover {
			color: <?php echo esc_attr( $lpc_form_lostpass_text_color_hover ); ?>;
		}
		</style>
		<?php
}
add_action( 'login_enqueue_scripts', 'lpc_lost_pass_link_settings' );

function lpc_lost_pass_form_settings() {
	$lpc_form_lostpass_label_size = lpc_get_mod( 'lpc-form-lostpass-box-label-size', '14' );
	?>
	<style>
		.login #lostpasswordform label[for="user_login"] {
			font-size: <?php echo esc_attr( $lpc_form_lostpass_label_size ); ?>px;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_lost_pass_form_settings' );

function lpc_backto_link_settings() {
	$lpc_backtolink_enable           = lpc_get_mod( 'lpc-backtolink-enable', '1' );
	$lpc_backtolink_text_font_size   = lpc_get_mod( 'lpc-backtolink-font-size', '13' );
	$lpc_backtolink_text_color       = lpc_get_mod( 'lpc-backtolink-text-color', '#50575e' );
	$lpc_backtolink_text_color_hover = lpc_get_mod( 'lpc-backtolink-text-color-hover', '#135e96' );
	if ( $lpc_backtolink_enable == 0 ) {
		?>
		<style>
		.login #login p#backtoblog {
			display: none;
		}
		</style>
		<?php
	}
	?>
	<style>
		.login #login p#backtoblog a {
			font-size: <?php echo esc_attr( $lpc_backtolink_text_font_size ); ?>px;
			color: <?php echo esc_attr( $lpc_backtolink_text_color ); ?>;
		}
		.login #login p#backtoblog a:hover {
			color: <?php echo esc_attr( $lpc_backtolink_text_color_hover ); ?>;
		}
		.login #login p#backtoblog {
			position: relative;
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_backto_link_settings' );

function loginpc_customizer_preview_script() {
	wp_enqueue_script( 'loginpc-customizer-preview', LOGINPC_PLUGIN_URL . 'assets/js/customizer-preview.js', array( 'customize-preview' ), '1.0.0', true );
	wp_enqueue_style( 'loginpc-customizer-preview-styles', LOGINPC_PLUGIN_URL . 'assets/css/customizer-preview.css', array(), '1.0.0' );
}
add_action( 'customize_preview_init', 'loginpc_customizer_preview_script' );



function lpc_footer_copyright() {
	$lpc_copyright_text = lpc_get_mod( 'lpc-footer-copyright-text', '© 2023 WordPress, All Rights Reserved.' );
	echo wp_kses_post( '<div class="lpc-footer-wrap">' );
	echo wp_kses_post( '<div class="lpc-poweredby"> Powered by: <a href="https://wordpress.org/plugins/customizer-login-page/" target="_blank"> Customizer Login Page </a></div>' );
	echo wp_kses_post( '<div class="lpc-copyright">' . $lpc_copyright_text . '</div>' );
	echo wp_kses_post( '</div>' );
}
add_action( 'login_footer', 'lpc_footer_copyright' );

function lpc_footer_styles() {
	$lpc_footer_enable        = lpc_get_mod( 'lpc-footer-enable', 1 );
	$lpc_footer_background    = lpc_get_mod( 'lpc-footer-background', '#fff' );
	$lpc_copyright_enable     = lpc_get_mod( 'lpc-copyright-enable', 0 );
	$lpc_copyright_color      = lpc_get_mod( 'lpc-footer-copyright-color', '#000' );
	$lpc_copyright_fontsize   = lpc_get_mod( 'lpc-footer-copyright-font-size', '14' );
	$lpc_copyright_fontweight = lpc_get_mod( 'lpc-footer-copyright-font-weight', '400' );
	$lpc_poweredby_enable     = lpc_get_mod( 'lpc-poweredby-enable', 1 );
	$lpc_poweredby_position   = lpc_get_mod( 'lpc-poweredby-position', 'right' );
	$lpc_poweredby_color      = lpc_get_mod( 'lpc-footer-poweredby-color', '#000' );
	?>
	<style>
		.login .lpc-footer-wrap {
			<?php
			if ( $lpc_footer_enable == 0 ) {
				echo esc_attr( 'display: none;' );
			}
			if ( $lpc_copyright_enable == 1 ) {
				echo esc_attr( 'padding: 5px;' );
			}
			?>
			position:sticky;
			top:97vh;
			background: <?php echo esc_attr( $lpc_footer_background ); ?>;
		}
		.login .lpc-footer-wrap .lpc-copyright {
			<?php
			if ( $lpc_copyright_enable == 0 ) {
				echo esc_attr( 'display: none;' );
			}
			?>
			text-align: center;
			font-size: <?php echo esc_attr( $lpc_copyright_fontsize ); ?>px;
			font-weight: <?php echo esc_attr( $lpc_copyright_fontweight ); ?>;
			color: <?php echo esc_attr( $lpc_copyright_color ); ?>;
		}
		.login .lpc-footer-wrap .lpc-poweredby{
			<?php
			if ( $lpc_poweredby_enable == 0 ) {
				echo esc_attr( 'display: none;' );
			}
			if ( $lpc_poweredby_position === 'left' ) {
				echo esc_attr( 'left: 0;' );
			} elseif ( $lpc_poweredby_position === 'right' ) {
				echo esc_attr( 'right: 0;' );
			}
			?>
			color:<?php echo esc_attr( $lpc_poweredby_color ); ?>;
			font-style: italic;
			font-size: 13px;
			padding-bottom: 3px;
			text-align: center;
			position: absolute;
			padding-right: 15px;
			padding-left: 15px;
		}
		.login .lpc-footer-wrap .lpc-poweredby a{
			color:<?php echo esc_attr( $lpc_poweredby_color ); ?>;
		}
		@media ( max-width: 675px ) {
			.login .lpc-footer-wrap .lpc-poweredby {
				position: relative;
			}
		}
	</style>
	<?php
}
add_action( 'login_enqueue_scripts', 'lpc_footer_styles' );

// function dump_my_var_in_footer() {
// global $my_var_to_dump;
// echo '<pre>';
// var_dump($my_var_to_dump);
// echo '</pre>';
// }
// add_action('login_footer', 'dump_my_var_in_footer');

// function get_my_plugin_customizer_setting_names_in_json($wp_customize) {

// Fetch all customizer settings
// $all_settings = $wp_customize->settings();

// Filter out only your plugin's settings based on some prefix or criteria
// $my_plugin_settings = array_filter($all_settings, function($setting_id) {
// Assuming your setting IDs start with 'lpc_opts[' (adjust as per your naming convention)
// return strpos($setting_id, 'lpc_opts[') === 0;
// }, ARRAY_FILTER_USE_KEY);

// Clean up the setting names (IDs) by removing the "lpc_opts[" prefix and the trailing "]"
// $cleaned_setting_names = array_map(function($setting_id) {
// return str_replace(['lpc_opts[', ']'], '', $setting_id);
// }, array_keys($my_plugin_settings));

// Set their values to empty strings
// $settings_array = array_fill_keys($cleaned_setting_names, "");

// Convert to JSON format
// return json_encode($settings_array, JSON_PRETTY_PRINT);
// }

// add_action('customize_register', function($wp_customize) {
// $settings_json = get_my_plugin_customizer_setting_names_in_json($wp_customize);

// Print or do whatever you want with the JSON output
// error_log($settings_json); // This will log the JSON output in your error log
// });

function customize_pre_update_option_filter( $value, $option, $old_value ) {
	// Check if the option being updated is 'lpc_preset_select'.
	if ( 'lpc_preset_select' == $option ) {
		// Return the old value to prevent the new value from being saved.
		return $old_value;
	}

	return $value; // Return the original value for all other options.
}
add_filter( 'pre_update_option', 'customize_pre_update_option_filter', 10, 3 );
