<?php

namespace SmashBalloon\YouTubeFeed\Services\Admin\Settings;

use SmashBalloon\YouTubeFeed\Helpers\Util;

class AboutPage extends BaseSettingPage {
	protected $menu_slug = 'about';
	protected $menu_title = 'About Us';
	protected $page_title = 'About Us';
	protected $has_menu = true;
	protected $template_file = 'settings.index';
	protected $has_assets = true;
	protected $menu_position = 4;
	protected $menu_position_free_version = 3;

	public function register() {
		parent::register();

		add_filter( 'sby_localized_settings', [ $this, 'filter_settings_object' ] );
		add_action( 'wp_ajax_sby_install_addon', [ $this, 'ajax_install_addon' ] );
		add_action( 'wp_ajax_sby_activate_addon', [ $this, 'ajax_activate_addon' ] );


	}

	public function ajax_activate_addon() {

		Util::ajaxPreflightChecks();

		// Check for permissions.
		if ( ! current_user_can( 'activate_plugins' ) ) {
			wp_send_json_error();
		}

		if ( isset( $_POST['plugin'] ) ) {

			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}

			$activate = activate_plugins( preg_replace( '/[^a-z-_\/]/', '', wp_unslash( str_replace( '.php', '', $_POST['plugin'] ) ) ) . '.php' );

			if ( ! is_wp_error( $activate ) ) {
				if ( 'plugin' === $type ) {
					wp_send_json_success( esc_html__( 'Plugin activated.', 'feeds-for-youtube' ) );
				} else {
					wp_send_json_success( esc_html__( 'Addon activated.', 'feeds-for-youtube' ) );
				}
			}
		}

		wp_send_json_error( esc_html__( 'Could not activate addon. Please activate from the Plugins page.', 'feeds-for-youtube' ) );
	}

	public function ajax_install_addon() {

		// Run a security check.
		Util::ajaxPreflightChecks();

		// Check for permissions.
		if ( ! current_user_can( 'install_plugins' ) ) {
			wp_send_json_error();
		}

		$error = esc_html__( 'Could not install addon. Please download from wpforms.com and install manually.', 'feeds-for-youtube' );

		if ( empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		// Only install plugins from the .org repo
		if ( strpos( $_POST['plugin'], 'https://downloads.wordpress.org/plugin/' ) !== 0 ) {
			wp_send_json_error( $error );
		}

		// Set the current screen to avoid undefined notices.
		set_current_screen( 'youtube-feed-about' );

		// Prepare variables.
		$url = esc_url_raw(
			add_query_arg(
				array(
					'page' => 'youtube-feed-about',
				),
				admin_url( 'admin.php' )
			)
		);

		$creds = request_filesystem_credentials( $url, '', false, false, null );

		// Check for file system permissions.
		if ( false === $creds ) {
			wp_send_json_error( $error );
		}

		if ( ! WP_Filesystem( $creds ) ) {
			wp_send_json_error( $error );
		}

		/*
		 * We do not need any extra credentials if we have gotten this far, so let's install the plugin.
		 */

		// Do not allow WordPress to search/download translations, as this will break JS output.
		remove_action( 'upgrader_process_complete', array( 'Language_Pack_Upgrader', 'async_upgrade' ), 20 );

		if(!class_exists("Plugin_Upgrader")) {
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/class-plugin-upgrader.php';
			include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader-skin.php';
		}

		// Create the plugin upgrader with our custom skin.
		$installer = new \Plugin_Upgrader( new \WP_Upgrader_Skin() );

		// Error check.
		if ( empty( $_POST['plugin'] ) ) {
			wp_send_json_error( $error );
		}

		$installer->install( esc_url_raw( wp_unslash( $_POST['plugin'] ) ) );

		// Flush the cache and return the newly installed plugin basename.
		wp_cache_flush();

		$plugin_basename = $installer->plugin_info();

		if ( $plugin_basename ) {

			$type = 'addon';
			if ( ! empty( $_POST['type'] ) ) {
				$type = sanitize_key( $_POST['type'] );
			}

			// Activate the plugin silently.
			$activated = activate_plugin( $plugin_basename );

			if ( ! is_wp_error( $activated ) ) {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed & activated.', 'feeds-for-youtube' ) : esc_html__( 'Addon installed & activated.', 'feeds-for-youtube' ),
						'is_activated' => true,
						'basename'     => $plugin_basename,
					)
				);
			} else {
				wp_send_json_success(
					array(
						'msg'          => 'plugin' === $type ? esc_html__( 'Plugin installed.', 'feeds-for-youtube' ) : esc_html__( 'Addon installed.', 'feeds-for-youtube' ),
						'is_activated' => false,
						'basename'     => $plugin_basename,
					)
				);
			}
		}

		wp_send_json_error( $error );
	}

	public function filter_settings_object( $settings ) {
		$sb_other_plugins = sby_get_active_plugins_info();
		$installed_plugins = $sb_other_plugins['installed_plugins'];
		$license_key = get_option( 'sby_license_key', null );

		$settings['pluginInfo'] = [
			"plugins" => [
				'instagram' => array(
					'plugin'          => $sb_other_plugins['instagram_plugin'],
					'download_plugin' => 'https://downloads.wordpress.org/plugin/feeds-for-youtube.zip',
					'title'           => __( 'Instagram Feed', 'feeds-for-youtube' ),
					'description'     => __( 'A quick and elegant way to add your Instagram posts to your website. ', 'feeds-for-youtube' ),
					'icon'            => 'insta-icon.svg',
					'installed'       => isset( $sb_other_plugins['is_instagram_installed'] ) && $sb_other_plugins['is_instagram_installed'] == true,
					'activated'       => is_plugin_active( $sb_other_plugins['instagram_plugin'] ),
				),
				'facebook'  => array(
					'plugin'      => $sb_other_plugins['facebook_plugin'],
					'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-facebook-feed.zip',
					'title'       => __( 'Custom Facebook Feed', 'feeds-for-youtube' ),
					'description' => __( 'Add Facebook posts from your timeline, albums and much more.', 'feeds-for-youtube' ),
					'icon'        => 'fb-icon.svg',
					'installed'   => isset( $sb_other_plugins['is_facebook_installed'] ) && $sb_other_plugins['is_facebook_installed'] == true,
					'activated'   => is_plugin_active( $sb_other_plugins['facebook_plugin'] ),
				),
				'twitter'   => array(
					'plugin'          => $sb_other_plugins['twitter_plugin'],
					'download_plugin' => 'https://downloads.wordpress.org/plugin/custom-twitter-feeds.zip',
					'title'           => __( 'Custom Twitter Feeds', 'feeds-for-youtube' ),
					'description'     => __( 'A customizable way to display tweets from your Twitter account. ', 'feeds-for-youtube' ),
					'icon'            => 'twitter-icon.svg',
					'installed'       => isset( $sb_other_plugins['is_twitter_installed'] ) && $sb_other_plugins['is_twitter_installed'] == true,
					'activated'       => is_plugin_active( $sb_other_plugins['twitter_plugin'] ),
				),
				'youtube'   => array(
					'plugin'          => $sb_other_plugins['youtube_plugin'],
					'download_plugin' => 'https://downloads.wordpress.org/plugin/feeds-for-youtube.zip',
					'title'           => __( 'Feeds for YouTube', 'feeds-for-youtube' ),
					'description'     => __( 'A simple yet powerful way to display videos from YouTube. ', 'feeds-for-youtube' ),
					'icon'            => 'youtube-icon.svg',
					'installed'       => isset( $sb_other_plugins['is_youtube_installed'] ) && $sb_other_plugins['is_youtube_installed'] == true,
					'activated'       => is_plugin_active( $sb_other_plugins['youtube_plugin'] ),
				)
			],
			'social_wall'         => array(
				'plugin'      => 'social-wall/social-wall.php',
				'title'       => __( 'Social Wall', 'feeds-for-youtube' ),
				'description' => __( 'Combine feeds from all of our plugins into a single wall', 'feeds-for-youtube' ),
				'graphic'     => 'social-wall-graphic.png',
				'permalink'   => sprintf( 'https://smashballoon.com/social-wall/demo?edd_license_key=%s&upgrade=true&utm_campaign='. sby_utm_campaign() .'&utm_source=about&utm_medium=social-wall', $license_key ),
				'installed'   => isset( $sb_other_plugins['is_social_wall_installed'] ) && $sb_other_plugins['is_social_wall_installed'] == true,
				'activated'   => is_plugin_active( $sb_other_plugins['social_wall_plugin'] ),
			),
			'recommendedPlugins'  => array(
				'wpforms'         => array(
					'plugin'          => 'wpforms-lite/wpforms.php',
					'download_plugin' => 'https://downloads.wordpress.org/plugin/wpforms-lite.zip',
					'title'           => __( 'WPForms', 'feeds-for-youtube' ),
					'description'     => __( 'The most beginner friendly drag & drop WordPress forms plugin allowing you to create beautiful contact forms, subscription forms, payment forms, and more in minutes, not hours!', 'feeds-for-youtube' ),
					'icon'            => 'plugin-wpforms.png',
					'installed'       => isset( $installed_plugins['wpforms-lite/wpforms.php'] ),
					'activated'       => is_plugin_active( 'wpforms-lite/wpforms.php' ),
				),
				'monsterinsights' => array(
					'plugin'          => 'google-analytics-for-wordpress/googleanalytics.php',
					'download_plugin' => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
					'title'           => __( 'MonsterInsights', 'feeds-for-youtube' ),
					'description'     => __( 'MonsterInsights makes it “effortless” to properly connect your WordPress site with Google Analytics, so you can start making data-driven decisions to grow your business.', 'feeds-for-youtube' ),
					'icon'            => 'plugin-mi.png',
					'installed'       => isset( $installed_plugins['google-analytics-for-wordpress/googleanalytics.php'] ),
					'activated'       => is_plugin_active( 'google-analytics-for-wordpress/googleanalytics.php' ),
				),
				'optinmonster'    => array(
					'plugin'          => 'optinmonster/optin-monster-wp-api.php',
					'download_plugin' => 'https://downloads.wordpress.org/plugin/optinmonster.zip',
					'title'           => __( 'OptinMonster', 'feeds-for-youtube' ),
					'description'     => __( 'Our high-converting optin forms like Exit-Intent® popups, Fullscreen Welcome Mats, and Scroll boxes help you dramatically boost conversions and get more email subscribers.', 'feeds-for-youtube' ),
					'icon'            => 'plugin-om.png',
					'installed'       => isset( $installed_plugins['optinmonster/optin-monster-wp-api.php'] ),
					'activated'       => is_plugin_active( 'optinmonster/optin-monster-wp-api.php' ),
				),
				'wp_mail_smtp'    => array(
					'plugin'          => 'wp-mail-smtp/wp_mail_smtp.php',
					'download_plugin' => 'https://downloads.wordpress.org/plugin/wp-mail-smtp.zip',
					'title'           => __( 'WP Mail SMTP', 'feeds-for-youtube' ),
					'description'     => __( 'Make sure your website\'s emails reach the inbox. Our goal is to make email deliverability easy and reliable. Trusted by over 1 million websites.', 'feeds-for-youtube' ),
					'icon'            => 'plugin-smtp.png',
					'installed'       => isset( $installed_plugins['wp-mail-smtp/wp_mail_smtp.php'] ),
					'activated'       => is_plugin_active( 'wp-mail-smtp/wp_mail_smtp.php' ),
				),
				'rafflepress'     => array(
					'plugin'          => 'rafflepress/rafflepress.php',
					'download_plugin' => 'https://downloads.wordpress.org/plugin/rafflepress.zip',
					'title'           => __( 'RafflePress', 'feeds-for-youtube' ),
					'description'     => __( 'Turn your visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with powerful viral giveaways & contests.', 'feeds-for-youtube' ),
					'icon'            => 'plugin-rp.png',
					'installed'       => isset( $installed_plugins['rafflepress/rafflepress.php'] ),
					'activated'       => is_plugin_active( 'rafflepress/rafflepress.php' ),
				),
				'aioseo'          => array(
					'plugin'          => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
					'download_plugin' => 'https://downloads.wordpress.org/plugin/all-in-one-seo-pack.zip',
					'title'           => __( 'All in One SEO Pack', 'feeds-for-youtube' ),
					'description'     => __( 'Out-of-the-box SEO for WordPress. Features like XML Sitemaps, SEO for custom post types, SEO for blogs, business sites, or ecommerce sites, and much more.', 'feeds-for-youtube' ),
					'icon'            => 'plugin-seo.png',
					'installed'       => isset( $installed_plugins['all-in-one-seo-pack/all_in_one_seo_pack.php'] ),
					'activated'       => is_plugin_active( 'all-in-one-seo-pack/all_in_one_seo_pack.php' ),
				),
			),
		];
		return $settings;
	}
}