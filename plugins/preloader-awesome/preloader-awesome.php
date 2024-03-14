<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://themesawesome.com/
 * @since             1.0.0
 * @package           Preloader_Awesome
 *
 * @wordpress-plugin
 * Plugin Name:       Preloader Awesome
 * Plugin URI:        https://preloader.themesawesome.com/
 * Description:       Preloader Awesome is an awesome plugin that helps You to create the Page Preloader into your WordPress Site berfore serve it contents. 
 * Version:           1.0.0
 * Author:            Themes Awesome
 * Author URI:        https://themesawesome.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       preloader-awesome
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PRELOADER_AWESOME_VERSION', '1.0.0' );

define( 'PRELOADER_AWESOME', __FILE__ );

define( 'PRELOADER_AWESOME_BASENAME', plugin_basename( PRELOADER_AWESOME ) );

define( 'PRELOADER_AWESOME_NAME', trim( dirname( PRELOADER_AWESOME_BASENAME ), '/' ) );

define( 'PRELOADER_AWESOME_DIR', untrailingslashit( dirname( PRELOADER_AWESOME ) ) );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-preloader-awesome-activator.php
 */
function activate_preloader_awesome() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-preloader-awesome-activator.php';
	Preloader_Awesome_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-preloader-awesome-deactivator.php
 */
function deactivate_preloader_awesome() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-preloader-awesome-deactivator.php';
	Preloader_Awesome_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_preloader_awesome' );
register_deactivation_hook( __FILE__, 'deactivate_preloader_awesome' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-preloader-awesome.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_preloader_awesome() {

	$plugin = new Preloader_Awesome();
	$plugin->run();

}
run_preloader_awesome();

// init carbon field
add_action( 'after_setup_theme', 'preloader_awesome_crb_load' );
function preloader_awesome_crb_load() {
	require_once( 'vendor/autoload.php' );
	\Carbon_Fields\Carbon_Fields::boot();
}

// all themesawesome preloader awesome options
require plugin_dir_path( __FILE__ ) . 'preloader-awesome-options.php';
require plugin_dir_path( __FILE__ ) . 'preloader-awesome-page-options.php';
require_once plugin_dir_path( __FILE__ ).'public/inc/custom-loader.php';

function ini_buat_head() {
	global $post;

	// from theme options
	$preloader_awesome_style = carbon_get_post_meta( $post->ID, 'preloader_awesome_style' );
	$preloader_awesome_style_global = carbon_get_theme_option( 'preloader_awesome_style_global' );
	$preloader_awesome_sitewide_global = carbon_get_theme_option( 'preloader_awesome_sitewide_global' );

	if(!empty($preloader_awesome_style)) { ?>
		<div id="ta-pageload">
			<div class="container-pageload">
	<?php }
	elseif(!empty($preloader_awesome_style_global)) {
		if($preloader_awesome_sitewide_global == 'yes') { ?>
			<div id="ta-pageload">
				<div class="container-pageload">
		<?php }
	}
	else {
		if($preloader_awesome_sitewide_global == 'yes') { ?>
			<div id="ta-pageload">
				<div class="container-pageload">
	<?php }
	} ?>

<?php
}

add_action('wp_body_open', 'ini_buat_head');

function custom_content_after_body_open_tag() {

	global $post;

	// from theme options
	$preloader_awesome_style = carbon_get_post_meta( $post->ID, 'preloader_awesome_style' );
	$preloader_awesome_style_global = carbon_get_theme_option( 'preloader_awesome_style_global' );
	$preloader_awesome_sitewide_global = carbon_get_theme_option( 'preloader_awesome_sitewide_global' );

	?>
	</div>
	</div>

	<?php 
	if(!empty($preloader_awesome_style)) {
		if($preloader_awesome_style == 'lazy-stretch') {
			include_once dirname( __FILE__ ) .'/public/styles-page/style-hiji.php';
		}
		elseif ($preloader_awesome_style == 'spill') {
			include_once dirname( __FILE__ ) .'/public/styles-page/style-tilu.php';
		}
		elseif ($preloader_awesome_style == 'parallelogam') {
			include_once dirname( __FILE__ ) .'/public/styles-page/style-dalapan.php';
		}
	}
	elseif(!empty($preloader_awesome_style_global)) {
		if($preloader_awesome_sitewide_global == 'yes') {
			if($preloader_awesome_style_global == 'lazy-stretch') {
				include_once dirname( __FILE__ ) .'/public/styles/style-hiji.php';
			}
			elseif ($preloader_awesome_style_global == 'spill') {
				include_once dirname( __FILE__ ) .'/public/styles/style-tilu.php';
			}
			elseif ($preloader_awesome_style_global == 'parallelogam') {
				include_once dirname( __FILE__ ) .'/public/styles/style-dalapan.php';
			}
		}
	} else {
		if($preloader_awesome_sitewide_global == 'yes') {
			include_once dirname( __FILE__ ) .'/public/styles/style-def.php';
		}
	} ?>
<?php
}

add_action('wp_footer', 'custom_content_after_body_open_tag');

add_action('wp_head', 'preloader_awesome_color_custom_styles', 100);
function preloader_awesome_color_custom_styles()
{ ?>
   
   <style>
		<?php
		global $post;
		// loader background color
		$preloader_awesome_bg_color = carbon_get_post_meta( $post->ID, 'preloader_awesome_bg_color' );

		// loader gif img size
		$preloader_awesome_loader_size = carbon_get_post_meta( $post->ID, 'preloader_awesome_loader_size' );
		$preloader_awesome_css_loader_color = carbon_get_post_meta( $post->ID,'preloader_awesome_css_loader_color' );

		// loader counter style
		$preloader_awesome_counter_size = carbon_get_post_meta( $post->ID, 'preloader_awesome_counter_size' );
		$preloader_awesome_counter_color = carbon_get_post_meta( $post->ID, 'preloader_awesome_counter_color' );

		// loader progress bar style
		$preloader_awesome_prog_color = carbon_get_post_meta( $post->ID, 'preloader_awesome_prog_color' );
		$preloader_awesome_prog_height = carbon_get_post_meta( $post->ID, 'preloader_awesome_prog_height' );
		$preloader_awesome_prog_pos = carbon_get_post_meta( $post->ID, 'preloader_awesome_prog_pos' );

		// ================================= global ======================== //
		// loader background color
		$preloader_awesome_bg_color_global = carbon_get_theme_option( 'preloader_awesome_bg_color_global' );

		// loader gif img size
		$preloader_awesome_loader_size_global = carbon_get_theme_option( 'preloader_awesome_loader_size_global' );
		$preloader_awesome_css_loader_color_global = carbon_get_theme_option( 'preloader_awesome_css_loader_color_global' );

		// loader counter style
		$preloader_awesome_counter_size_global = carbon_get_theme_option( 'preloader_awesome_counter_size_global' );
		$preloader_awesome_counter_color_global = carbon_get_theme_option( 'preloader_awesome_counter_color_global' );

		// loader progress bar style
		$preloader_awesome_prog_color_global = carbon_get_theme_option( 'preloader_awesome_prog_color_global' );
		$preloader_awesome_prog_height_global = carbon_get_theme_option( 'preloader_awesome_prog_height_global' );
		$preloader_awesome_prog_pos_global = carbon_get_theme_option( 'preloader_awesome_prog_pos_global' );

		if(!empty($preloader_awesome_bg_color)) {
			$preloader_awesome_bg_color = $preloader_awesome_bg_color;
		}
		elseif(!empty($preloader_awesome_bg_color_global)) {
			$preloader_awesome_bg_color = $preloader_awesome_bg_color_global;
		}
		else {
			$preloader_awesome_bg_color = '';
		}

		if(!empty($preloader_awesome_loader_size)) {
			$preloader_awesome_loader_size = $preloader_awesome_loader_size;
		}
		elseif(!empty($preloader_awesome_loader_size_global)) {
			$preloader_awesome_loader_size = $preloader_awesome_loader_size_global;
		}
		else {
			$preloader_awesome_loader_size = '';
		}

		if(!empty($preloader_awesome_counter_size)) {
			$preloader_awesome_counter_size = $preloader_awesome_counter_size;
		}
		elseif(!empty($preloader_awesome_counter_size_global)) {
			$preloader_awesome_counter_size = $preloader_awesome_counter_size_global;
		}
		else {
			$preloader_awesome_counter_size = '';
		}

		if(!empty($preloader_awesome_counter_color)) {
			$preloader_awesome_counter_color = $preloader_awesome_counter_color;
		}
		elseif(!empty($preloader_awesome_counter_color_global)) {
			$preloader_awesome_counter_color = $preloader_awesome_counter_color_global;
		}
		else {
			$preloader_awesome_counter_color = '';
		}

		if(!empty($preloader_awesome_prog_color)) {
			$preloader_awesome_prog_color = $preloader_awesome_prog_color;
		}
		elseif(!empty($preloader_awesome_prog_color_global)) {
			$preloader_awesome_prog_color = $preloader_awesome_prog_color_global;
		}
		else {
			$preloader_awesome_prog_color = '';
		}

		if(!empty($preloader_awesome_prog_height)) {
			$preloader_awesome_prog_height = $preloader_awesome_prog_height;
		}
		elseif(!empty($preloader_awesome_prog_height_global)) {
			$preloader_awesome_prog_height = $preloader_awesome_prog_height_global;
		}
		else {
			$preloader_awesome_prog_height = '';
		}

		if(!empty($preloader_awesome_prog_pos)) {
			$preloader_awesome_prog_pos = $preloader_awesome_prog_pos;
		}
		elseif(!empty($preloader_awesome_prog_pos_global)) {
			$preloader_awesome_prog_pos = $preloader_awesome_prog_pos_global;
		}
		else {
			$preloader_awesome_prog_pos = '';
		}

		if(!empty($preloader_awesome_css_loader_color)) {
			$preloader_awesome_css_loader_color = $preloader_awesome_css_loader_color;
		}
		elseif(!empty($preloader_awesome_css_loader_color_global)) {
			$preloader_awesome_css_loader_color = $preloader_awesome_css_loader_color_global;
		}
		else {
			$preloader_awesome_css_loader_color = '';
		}

		if(!empty($preloader_awesome_bg_color)) { ?>
			.pageload-overlay svg path {
				fill: <?php echo esc_html($preloader_awesome_bg_color); ?>;
			}
		<?php }
		if(!empty($preloader_awesome_bg_color)) { ?>
			.pageload-overlay.def {
				background-color: <?php echo esc_html($preloader_awesome_bg_color); ?>;
			}
		<?php }

		if(!empty($preloader_awesome_loader_size)) { ?>
			#ta-gif {
				width: <?php echo esc_html($preloader_awesome_loader_size); ?>px;
			}
		<?php }

		// loader counter
		if(!empty($preloader_awesome_counter_size)) { ?>
			#progstat {
				font-size: <?php echo esc_html($preloader_awesome_counter_size); ?>px;
			}
		<?php }
		if(!empty($preloader_awesome_counter_color)) { ?>
			#progstat {
				color: <?php echo esc_html($preloader_awesome_counter_color); ?>;
			}
		<?php }

		// loader progress bar
		if(!empty($preloader_awesome_prog_color)) { ?>
			#progress {
				background: <?php echo esc_html($preloader_awesome_prog_color); ?>;
			}
		<?php }
		if(!empty($preloader_awesome_prog_height)) { ?>
			#progress {
				height: <?php echo esc_html($preloader_awesome_prog_height); ?>px;
			}
		<?php }
		if(!empty($preloader_awesome_prog_pos)) { ?>
			#progress {
				<?php if($preloader_awesome_prog_pos == 'top') { ?>
					top: 0;
				<?php }
				if($preloader_awesome_prog_pos == 'center') { ?>
					top: 50%;
					-webkit-transform: translateY(-50%);
					-moz-transform: translateY(-50%);
					-ms-transform: translateY(-50%);
					-o-transform: translateY(-50%);
					transform: translateY(-50%);
				<?php }
				if($preloader_awesome_prog_pos == 'bottom') { ?>
					top: auto;
					bottom: 0;
				<?php } ?>
			}
		<?php }

		if(!empty($preloader_awesome_css_loader_color)) { ?>
			#ta-gif.ta-css-load-1, .ta-css-load-2 div:nth-child(1), .ta-css-load-2 div:nth-child(2), .ta-css-load-2 div:nth-child(3), .ta-css-load-2 div:nth-child(4), .ta-css-load-3 div, .ta-css-load-4 > div:nth-child(1) div, .ta-css-load-4 > div:nth-child(2) div, .ta-css-load-5 > div > div div, .ta-css-load-6 div, .ta-css-load-6 div:nth-child(2), .ta-css-load-6 div:nth-child(3), .ta-css-load-6 div:nth-child(4), .ta-css-load-8 > div:nth-child(2) div:before, .ta-css-load-8 > div:nth-child(2) div:after, .ta-css-load-10 div:nth-child(1), .ta-css-load-10 div:nth-child(2), .ta-css-load-10 div:nth-child(3), .ta-css-load-10 div:nth-child(4), .ta-css-load-10 div:nth-child(5), .ta-css-load-11 > div > div > div > div, .ta-css-load-11 > div > div:last-child > div > div {
				background: <?php echo esc_html($preloader_awesome_css_loader_color); ?>;
			}
			.ta-css-load-8 > div {
				border-color: <?php echo esc_html($preloader_awesome_css_loader_color); ?> transparent <?php echo esc_html($preloader_awesome_css_loader_color); ?> transparent;
			}
			.ta-css-load-8 > div:nth-child(2) div:before, .ta-css-load-8 > div:nth-child(2) div:after {
				box-shadow: 0 128px 0 0 <?php echo esc_html($preloader_awesome_css_loader_color); ?>;
			}
			.ta-css-load-8 > div:nth-child(2) div:after { 
				box-shadow: 128px 0 0 0 <?php echo esc_html($preloader_awesome_css_loader_color); ?>;
			}
			.ta-css-load-9 div {
				box-shadow: 0 4px 0 0 <?php echo esc_html($preloader_awesome_css_loader_color); ?>;
			}
			@keyframes ta-css-load-3 {
				0% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
				12.5% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
				12.625% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?>; opacity: 0.5; }
				100% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?>; opacity: 0.5; }
			}
			@keyframes ta-css-load-5-c {
				0%, 25%, 50%, 75%, 100% { animation-timing-function: cubic-bezier(0,1,0,1) }
				0% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
				25% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
				50% { background:<?php echo esc_html($preloader_awesome_css_loader_color); ?> }
				75% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
				100% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
			}

			@keyframes ta-css-load-10-c {
			   0% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
			  25% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
			  50% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
			  75% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
			 100% { background: <?php echo esc_html($preloader_awesome_css_loader_color); ?> }
			}
		<?php }

		wp_reset_postdata(); ?>
	</style>
<?php }