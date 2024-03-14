<?php
/**
 * Main plugin entry point
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://codester.com/technodigitz?ref=technodigitz
 * @since             1.0.0
 * @package           touch_fashion_slider
 *
 * @wordpress-plugin
 * Plugin Name:       Touch Fashion Slider - Elementor Addon
 * Plugin URI:        https://wordpress.org/plugins/touch-fashion-slider-elementor-addon
 * Description:       Ultimate Touch Fashion Slider - elementor add-on to add an attracting slider to your website.
 * Version:           1.0.0
 * Elementor tested up to: 3.8
 * Elementor Pro tested up to: 3.8
 * Author:            TechnoDigitz
 * Author URI:        https://codester.com/technodigitz?ref=technodigitz
 * Text Domain:       touch-fashion-slider
 * Domain Path:       /languages
 * Requires at least: 5.0
 * Tested up to:      6.1
 * Requires PHP:      7.0
 * WC tested up to: 7.0.1
 *
 * Copyright 2021-2021 technodigitz (http://technodigitz.com/)
 */

/* If this file is called directly, abort. */
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Define Pro Plugin URL and Directory Path
 */
define( 'TFS_EL_VERSION', '1.0.0' );
define( 'TFS_EL_PLUGIN_URL', plugins_url( '/', __FILE__ ) );  // Define Plugin URL.
define( 'TFS_EL_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );  // Define Plugin Directory Path.
define( 'TFS_EL_DOMAIN', 'touch-fashion-slider' );

/*
 * Load the plugin Category
 */
require_once TFS_EL_PLUGIN_PATH . 'widgets/elementor-helper.php';


if ( ! function_exists( 'tfsel_widget_register' ) ) {
	/**
	 * Register the widgtes file in elementor widgtes.
	 */
	function tfsel_widget_register() {
		require_once TFS_EL_PLUGIN_PATH . 'widgets/hero-slider-1-widget.php';
		require_once TFS_EL_PLUGIN_PATH . 'widgets/hero-slider-2-widget.php';
		require_once TFS_EL_PLUGIN_PATH . 'widgets/hero-slider-3-widget.php';
	}
}
add_action( 'elementor/widgets/widgets_registered', 'tfsel_widget_register' );


if ( ! function_exists( 'tfsel_widget_script_register' ) ) {
	/**
	 * Load profile card scripts and styles
	 *
	 * @since v1.0.0
	 */
	function tfsel_widget_script_register() {

		// Swiper Slider.
		wp_enqueue_style( 'tfsel-slider-swiper-css', plugins_url( 'assets/css/swiper.min.css', __FILE__ ), array( 'elementor-frontend' ), '4.0.7', false );
		wp_enqueue_script( 'tfsel-slider-swiper-js', plugins_url( 'assets/js/swiper.min.js', __FILE__ ), array(), '4.0.7', true );
		// Hero Slider.
		wp_enqueue_script( 'tfsel-hero-slider-1-js', plugins_url( 'assets/js/hero-slider-1.js', __FILE__ ), array( 'tfsel-slider-swiper-js' ), TFS_EL_VERSION, true );
		wp_enqueue_style( 'tfsel-hero-slider-1-css', plugins_url( 'assets/css/hero-slider-1.css', __FILE__ ), array( 'tfsel-slider-swiper-css' ), TFS_EL_VERSION, false );
		wp_enqueue_script( 'tfsel-hero-slider-2-js', plugins_url( 'assets/js/hero-slider-2.js', __FILE__ ), array( 'tfsel-slider-swiper-js' ), TFS_EL_VERSION, true );
		wp_enqueue_style( 'tfsel-hero-slider-2-css', plugins_url( 'assets/css/hero-slider-2.css', __FILE__ ), array( 'tfsel-slider-swiper-css' ), TFS_EL_VERSION, false );
		wp_enqueue_script( 'tfsel-hero-slider-3-js', plugins_url( 'assets/js/hero-slider-3.js', __FILE__ ), array( 'tfsel-slider-swiper-js' ), TFS_EL_VERSION, true );
		wp_enqueue_style( 'tfsel-hero-slider-3-css', plugins_url( 'assets/css/hero-slider-3.css', __FILE__ ), array( 'tfsel-slider-swiper-css' ), TFS_EL_VERSION, false );

		// Circle Animation.
		wp_enqueue_script( 'tfsel-circle-animation-js', TFS_EL_PLUGIN_URL . 'assets/js/circle-animation.js', array(), TFS_EL_VERSION, true );
		wp_enqueue_style( 'tfsel-circle-animation-css', TFS_EL_PLUGIN_URL . 'assets/css/circle-animation.css', array(), TFS_EL_VERSION, false );

		// FontAwesome CDN.
		wp_enqueue_style( 'font-awesome' );

	}
}
add_action( 'wp_enqueue_scripts', 'tfsel_widget_script_register' );


if ( ! function_exists( 'tfsel_plugin_load' ) ) {
	/**
	 * Check current version of Elementor
	 */
	function tfsel_plugin_load() {
		// Load plugin textdomain.
		load_plugin_textdomain( 'TFS_EL_DOMAIN', false, TFS_EL_PLUGIN_PATH . '/languages' );

		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', 'tfsel_widget_fail_load' );
			return;
		}
		$elementor_version_required = '1.1.2';
		if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
			add_action( 'admin_notices', 'tfsel_elementor_update_notice' );
			return;
		}
	}
}
add_action( 'plugins_loaded', 'tfsel_plugin_load' );


if ( ! function_exists( 'tfsel_widget_fail_load' ) ) {
	/**
	 * This notice will appear if Elementor is not installed or activated or both
	 */
	function tfsel_widget_fail_load() {
		$screen = get_current_screen();
		if ( isset( $screen->parent_file ) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id ) {
			return;
		}

		$plugin = 'elementor/elementor.php';

		if ( tfsel_elementor_installed() ) {
			if ( ! current_user_can( 'activate_plugins' ) ) {
				return;
			}
			$activation_url = wp_nonce_url( 'plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin );

			$message  = '<p><strong>' . esc_html__( 'Card Elements Pro', 'touch-fashion-slider' ) . '</strong>' . esc_html__( ' plugin is not working because you need to activate the Elementor plugin.', 'touch-fashion-slider' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $activation_url, esc_html__( 'Activate Elementor Now', 'touch-fashion-slider' ) ) . '</p>';
		} else {
			if ( ! current_user_can( 'install_plugins' ) ) {
				return;
			}

			$install_url = wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=elementor' ), 'install-plugin_elementor' );

			$message  = '<p><strong>' . esc_html__( 'Card Elements Pro', 'touch-fashion-slider' ) . '</strong>' . esc_html__( ' plugin is not working because you need to install the Elemenor plugin', 'touch-fashion-slider' ) . '</p>';
			$message .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $install_url, esc_html__( 'Install Elementor Now', 'touch-fashion-slider' ) ) . '</p>';
		}

		echo '<div class="error"><p>' . esc_html( $message ) . '</p></div>';
	}
}

if ( ! function_exists( 'tfsel_elementor_update_notice' ) ) {

	/**
	 * Display admin notice for Elementor update if Elementor version is old
	 */
	function tfsel_elementor_update_notice() {
		if ( ! current_user_can( 'update_plugins' ) ) {
			return;
		}

		$file_path = 'elementor/elementor.php';

		$upgrade_link = wp_nonce_url( self_admin_url( 'update.php?action=upgrade-plugin&plugin=' ) . $file_path, 'upgrade-plugin_' . $file_path );
		$message      = '<p><strong>' . esc_html__( 'Touch Fashion Slider', 'touch-fashion-slider' ) . '</strong>' . esc_html__( ' plugin is not working because you are using an old version of Elementor.', 'touch-fashion-slider' ) . '</p>';
		$message     .= '<p>' . sprintf( '<a href="%s" class="button-primary">%s</a>', $upgrade_link, esc_html__( 'Update Elementor Now', 'touch-fashion-slider' ) ) . '</p>';
		echo '<div class="error">' . esc_html( $message ) . '</div>';
	}
}

if ( ! function_exists( 'tfsel_elementor_installed' ) ) {

	/**
	 * Action when plugin installed
	 */
	function tfsel_elementor_installed() {

		$file_path         = 'elementor/elementor.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}
}
if ( ! function_exists( 'tfsel_plugin_activation' ) ) {

	/**
	 * Add action on plugin activation
	 */
	function tfsel_plugin_activation() {
		// Add reviews metadata on plugin activation.
		$notices   = get_option( 'tfsel_reviews', array() );
		$notices[] = '';

		update_option( 'tfsel_reviews', $notices );

		// Deactivate tfsel Starter Kit (free) plugin than activate tfsel Starter Kit Pro for elementor (premium) plugin.
		// deactivate_plugins( 'card-elements-for-elementor/card-elements-for-elementor.php' );.
	}
}
register_activation_hook( __FILE__, 'tfsel_plugin_activation' );

if ( ! function_exists( 'tfsel_reviews_notices' ) ) {

	/**
	 * Display admin notice on Card Elements activation for ratings
	 */
	function tfsel_reviews_notices() {
		if ( $notices = get_option( 'tfsel_reviews' ) ) {
			foreach ( $notices as $notice ) { ?>
				<div class='notice notice-success is-dismissible'>
					<p>
						<?php printf( esc_html__( 'Hi, you are now using ', 'touch-fashion-slider' ) ); ?>
						<strong>
							<?php printf( esc_html__( 'Touch Fashion Slider Free Version', 'touch-fashion-slider' ) ); ?>
						</strong>
						<?php printf( esc_html__( ' plugin. If you like This Plugin, Don\'t forget to rate it.', 'touch-fashion-slider' ) ); ?>
					</p>
					<p>
						<?php printf( esc_html__( 'Check out our Premium Plugins', 'touch-fashion-slider' ) ); ?>
						<a target="_blank" href="<?php printf( esc_url( 'https://codester.com/technodigitz?ref=technodigitz' ) ); ?>">
							<?php printf( esc_html( 'Here!' ) ); ?>
						</a>
					</p>
					<a target="_blank"  href="<?php printf( esc_url( 'https://wordpress.org/plugins/touch-fashion-slider-elementor-addon' ) ); ?>" target="_blank" class="rating-link">
					<strong>
						<?php printf( esc_html__( 'Rate This Plugin', 'touch-fashion-slider' ) ); ?>
					</strong>
					</a>
					<br />
					<br />
				</div>
				<?php
			}
			delete_option( 'tfsel_reviews' );
		}
	}

	add_action( 'admin_notices', 'tfsel_reviews_notices' );
}

if ( ! function_exists( 'tfsel_plugin_deactivation' ) ) {
	/**
	 * Remove reviews metadata on plugin deactivation.
	 */
	function tfsel_plugin_deactivation() {
		delete_option( 'tfsel_reviews' );
	}
}
register_deactivation_hook( __FILE__, 'tfsel_plugin_deactivation' );