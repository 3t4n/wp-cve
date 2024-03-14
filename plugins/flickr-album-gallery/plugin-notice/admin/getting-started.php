<?php
/**
 * Getting Started Page.
 *
 * @package fag
 */

// $plugin_dir = ABSPATH . 'wp-content/plugins/flickr-album-gallery';
require_once plugin_dir_path( __FILE__ ) . '/class-getting-start-plugin-helper.php';

// Adding Getting Started Page in admin menu

if ( ! function_exists( 'fag_getting_started_menu' ) ) :
	function fag_getting_started_menu() {
		$fag_plugin_count = null;
		if ( ! is_plugin_active( 'slider-factory/slider-factory.php' ) ) :
			$fag_plugin_count = '<span class="awaiting-mod action-count">1</span>';
			endif;
		/* translators: %1$s %2$s: Get */
		$title = sprintf( esc_html__( 'Get New Slider Factory Plugin %1$s %2$s', 'flickr-album-gallery' ), esc_html( '' ), $fag_plugin_count );
		/*
		 translators: %1$s: plugin page */
		// add_theme_page( sprintf(esc_html__('Get New %1$s', "flickr-album-gallery"), esc_html( 'Slider Factory' ), esc_html('')), $title, 'edit_theme_options', 'fag-getting-started', 'fag_getting_started_page');
		add_submenu_page( 'edit.php?post_type=fa_gallery', sprintf( esc_html__( 'Get New %1$s', 'flickr-album-gallery' ), esc_html( 'Slider Factory' ), esc_html( 'Plugin' ) ), $title, 'edit_theme_options', 'fag-getting-started', 'fag_getting_started_page' );
	}
endif;
add_action( 'admin_menu', 'fag_getting_started_menu' );

// Load Getting Started styles in the admin
if ( ! function_exists( 'fag_getting_started_admin_scripts' ) ) :
	function fag_getting_started_admin_scripts( $hook ) {
		// Load styles only on our page
		$current_screen = get_current_screen();
		if ( strpos( $current_screen->base, 'fag-getting-started' ) === false ) {
			return;
		} else {
			wp_enqueue_style( 'fag-getting-started', FAG_PLUGIN_URL . '/plugin-notice/admin/css/getting-started.css', false );
			wp_enqueue_script( 'plugin-install' );
			wp_enqueue_script( 'updates' );
			wp_enqueue_script( 'fag-getting-started', FAG_PLUGIN_URL . '/plugin-notice/admin/js/getting-started.js', array( 'jquery' ), true );
			wp_enqueue_script( 'fag-recommended-plugin-install', FAG_PLUGIN_URL . '/plugin-notice/admin/js/recommended-plugin-install.js', array( 'jquery' ), true );
			wp_localize_script( 'fag-recommended-plugin-install', 'fag_start_page', array( 'activating' => __( 'Activating ', 'flickr-album-gallery' ) ) );
		}
	}
endif;
add_action( 'admin_enqueue_scripts', 'fag_getting_started_admin_scripts' );


// Plugin API
if ( ! function_exists( 'fag_call_plugin_api' ) ) :
	function fag_call_plugin_api( $slug ) {
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
		$call_api = get_transient( 'fag_about_plugin_info_' . $slug );
		if ( false === $call_api ) {
				$call_api = plugins_api(
					'plugin_information',
					array(
						'slug'   => $slug,
						'fields' => array(
							'downloaded'        => false,
							'rating'            => false,
							'description'       => false,
							'short_description' => true,
							'donate_link'       => false,
							'tags'              => false,
							'sections'          => true,
							'homepage'          => true,
							'added'             => false,
							'last_updated'      => false,
							'compatibility'     => false,
							'tested'            => false,
							'requires'          => false,
							'downloadlink'      => false,
							'icons'             => true,
						),
					)
				);
				set_transient( 'fag_about_plugin_info_' . $slug, $call_api, 30 * MINUTE_IN_SECONDS );
		}
			return $call_api;
	}
endif;

// Callback function for admin page.
if ( ! function_exists( 'fag_getting_started_page' ) ) :
	function fag_getting_started_page() { ?>
	<div class="wrap getting-started">
		<h2 class="notices"></h2>
		<div class="intro-wrap">
			<div class="intro">
				<h3>Get Slider Factory Plugin</h3>
				<p>Slider Factory provides multiple slider layouts in single dashboard panel</p>
			</div>
			<div class="intro right">
				<a target="_blank" href="<?php echo esc_url( 'https://wpfrank.com/demo/slider-factory-free-wordpress-plugin/' ); ?>">
					<img src="<?php echo esc_url( FAG_PLUGIN_URL . '/plugin-notice/admin/images/slider-factory.gif' ); ?>">
				</a>
			</div>
		</div>
		<div class="panels">
			<ul class="inline-list">
				<li class="current">
					<a id="getting-started-panel" href="#">Slider Factory Pro 
					<?php
					if ( ! is_plugin_active( 'slider-factory/slider-factory.php' ) ) :
						?>
							<span class="plugin-not-active">1</span>
						<?php endif; ?>
					</a>
				</li>
				<li>
					<a id="free-pro-panel" href="#">Free Vs Pro</a>
				</li>
				
			</ul>
			<div id="panel" class="panel">
					<?php $plugin_dir = ABSPATH . 'wp-content/plugins/flickr-album-gallery'; ?>
					<?php require $plugin_dir . '/plugin-notice/admin/tabs/getting-started-panel.php'; ?>
					<?php require $plugin_dir . '/plugin-notice/admin/tabs/free-vs-pro-panel.php'; ?>
			</div>
		</div>
	</div>
		<?php
	}
endif;
