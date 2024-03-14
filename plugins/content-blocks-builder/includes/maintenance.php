<?php
/**
 * A simple maintenance mode
 *
 * @package    BoldBlocks
 * @author     Phi Phan <mrphipv@gmail.com>
 * @copyright  Copyright (c) 2023, Phi Phan
 */

namespace BoldBlocks;

// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( Maintenance::class ) ) :
	/**
	 * A simple maitenance mode for deploying or coming soon message
	 */
	class Maintenance extends CoreComponent {
		/**
		 * Run main hooks
		 *
		 * @return void
		 */
		public function run() {
			// Register setting fields.
			add_action( 'init', [ $this, 'register_settings' ] );

			// Starting point.
			add_action( 'init', [ $this, 'run_maintenance_mode' ] );

			// Add a notice for maintenance mode warning.
			add_action( 'admin_notices', [ $this, 'add_the_admin_notice_for_maintenance_mode' ] );
		}

		/**
		 * Register setting fields
		 *
		 * @return void
		 */
		public function register_settings() {
			register_setting(
				'cbb',
				'cbb_is_maintenance',
				[
					'type'         => 'boolean',
					'show_in_rest' => [
						'name' => 'IsMaintenance',
					],
					'default'      => false,
				]
			);

			register_setting(
				'cbb',
				'cbb_maintenance_ignore_slug',
				[
					'type'         => 'string',
					'show_in_rest' => [
						'name' => 'MaintenanceSlug',
					],
					'default'      => 'wp-login.php',
				]
			);

			register_setting(
				'cbb',
				'cbb_maintenance_enable_custom_page',
				[
					'type'         => 'boolean',
					'show_in_rest' => [
						'name' => 'MaintananceEnableCustomPage',
					],
					'default'      => false,
				]
			);

			register_setting(
				'cbb',
				'cbb_maintenance_page_id',
				[
					'type'         => 'integer',
					'show_in_rest' => [
						'name' => 'MaintanancePageId',
					],
					'default'      => 0,
				]
			);
		}

		/**
		 * Starting point
		 *
		 * @return void
		 */
		public function run_maintenance_mode() {
			// Bail if the setting is disabled.
			if ( ! get_option( 'cbb_is_maintenance' ) ) {
				return;
			}

			// Ignore cron job and ajax.
			if ( wp_doing_cron() || wp_doing_ajax() ) {
				return;
			}

			$ignore_slug = get_option( 'cbb_maintenance_ignore_slug' );

			if ( ! $ignore_slug ) {
				$ignore_slug = [ 'wp-login.php' ];
			} else {
				$ignore_slug = preg_split( "/\r\n|\n|\r/", $ignore_slug );
				$ignore_slug = array_filter(
					$ignore_slug,
					function ( $item ) {
						return trim( $item, ' /' );
					}
				);
			}

			$request_uri = trim( $_SERVER['REQUEST_URI'] ?? '', '/' );

			// Ignore administrator and the default login page.
			if ( current_user_can( 'manage_options' ) || in_array( $request_uri, $ignore_slug, true ) ) {
				return;
			}

			// Handle custom page.
			$enable_custom_page = get_option( 'cbb_maintenance_enable_custom_page' );
			$page_id            = get_option( 'cbb_maintenance_page_id' );
			if ( $enable_custom_page && $page_id ) {
				$custom_page_url = get_permalink( $page_id );
				if ( $custom_page_url ) {
					$parsed_url = wp_parse_url( $custom_page_url );
					if ( $parsed_url && in_array( trim( $parsed_url['path'] ?? '', '/' ), $ignore_slug, true ) ) {
						return;
					}
				}
			}

			$page_title   = __( 'Maintenance' );
			$page_content = __( 'The site is currently under maintenance. <br/>Please check back in a few minutes!', 'content-blocks-builder' );

			$site_icon = get_site_icon_url( 64 );
			if ( $site_icon ) {
				$site_icon = sprintf( '<img class="site-icon" width="64" src="%1$s" alt="%2$s"/>', $site_icon, get_bloginfo( 'name' ) );
			} else {
				$site_icon = sprintf( '<h1 class="site-icon site-title">%s</h1>', get_bloginfo( 'name' ) );
			}

			if ( ! headers_sent() ) {
				header( 'Content-Type: text/html; charset=utf-8; Retry-After: 600;' );
				status_header( 503 );
				nocache_headers();
			}

			$text_direction = 'ltr';
			if ( function_exists( 'is_rtl' ) && is_rtl() ) {
				$text_direction = 'rtl';
			}

			$dir_attr = "dir='$text_direction'";

			if ( $enable_custom_page && $page_id ) {
				$custom_page_url = get_permalink( $page_id );
				if ( $custom_page_url ) {
					wp_safe_redirect( $custom_page_url );
					die();
				}
			}

			ob_start();
			?>
<!DOCTYPE html>
<html <?php echo $dir_attr; ?>>
<head>
	<meta charset="utf-8">
			<?php
			if ( function_exists( 'wp_robots' ) && function_exists( 'wp_robots_no_robots' ) && function_exists( 'add_filter' ) ) {
				add_filter( 'wp_robots', 'wp_robots_no_robots' );
				wp_robots();
			}
			?>
	<title><?php esc_html_e( $page_title ); ?></title>
	<style>
		body { font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif; font-size:1.25rem; color: #333; text-align:center; }
		.site-icon { margin-bottom: 1rem; }
		.container { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); max-width: 600px; width:clamp(320px,50vw,600px); margin: auto; }
		.message { padding: 2rem 2rem 2.5rem; border:1px solid #ddd; border-radius: 4px; }
		.message h1 {
			margin-top: 0;
			margin-top: 0;
		}
		h1 { font-size: 2.5rem;}
	</style>
</head>
<body class="maintenance">
	<div class="container">
			<?php echo $site_icon; ?>
		<div class="message">
			<h1><?php esc_html_e( $page_title ); ?></h1>
			<div><?php echo $page_content; ?></div>
		</div>
	</div>
</body>
</html>
			<?php
			die( ob_get_clean() );
		}

		/**
		 * Add the admin notice for the maintenance mode
		 *
		 * @return void
		 */
		public function add_the_admin_notice_for_maintenance_mode() {
			// Bail if the setting is disabled.
			if ( ! get_option( 'cbb_is_maintenance' ) ) {
				return;
			}

			// Get the current screen.
			$screen = get_current_screen();

			if ( ! $screen ) {
				return;
			}

			if ( ! ( in_array( $screen->base, [ 'dashboard', 'plugins', 'themes' ], true ) || 'edit.php?post_type=boldblocks_block' === $screen->parent_file ) ) {
				return;
			}

			$settings_link = sprintf( '<a href="%1$s">%2$s</a>', admin_url( '/edit.php?post_type=boldblocks_block&page=cbb-settings&tab=developer' ), __( 'Turn it off.', 'content-blocks-buider' ) );

			echo '<div class="notice notice-warning is-dismissible"><p>' . __( 'The site is currently under maintenance. Please remember to turn off this mode when the site is ready. You can do this from the settings page.', 'content-blocks-builder' ) . ' ' . $settings_link . '</p></div>';
		}
	}
endif;
