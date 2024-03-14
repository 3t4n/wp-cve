<?php
/**
 * Welcome class.
 *
 * @since 1.8.1
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Welcome Class
 *
 * @since 1.7.0
 *
 * @package Envira_Gallery
 * @author  Envira Gallery Team <support@enviragallery.com>
 */
class Envira_Welcome {

	/**
	 * Envira Welcome Pages.
	 *
	 * @var array
	 */
	public $pages = [
		'envira-gallery-lite-get-started',
		'envira-gallery-lite-about-us',
		'envira-gallery-lite-upgrade',
		'envira-gallery-lite-litevspro',
	];

	/**
	 * Holds the submenu pagehook.
	 *
	 * @since 1.7.0
	 *
	 * @var string`
	 */
	public $hook;

	/**
	 * Helper method for installed plugins
	 *
	 * @since 1.7.0
	 *
	 * @var array
	 */
	public $installed_plugins;

	/**
	 * Class Hooks
	 *
	 * @since 1.8.7
	 *
	 * @return void
	 */
	public function hooks() {

		if ( ( defined( 'ENVIRA_WELCOME_SCREEN' ) && false === ENVIRA_WELCOME_SCREEN ) || apply_filters( 'envira_whitelabel', false ) === true ) {
			return;
		}

		// Add custom addons submenu.
		add_action( 'admin_menu', [ $this, 'admin_menu' ], 15 );

		// Add custom CSS class to body.
		add_filter( 'admin_body_class', [ $this, 'admin_welcome_css' ], 15 );

		// Add scripts and styles.
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_styles' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_admin_scripts' ] );

		// Misc.
		add_action( 'admin_print_scripts', [ $this, 'disable_admin_notices' ] );
	}


	/**
	 * Add custom CSS to admin body tag.
	 *
	 * @since 1.8.1
	 *
	 * @param string $classes CSS Classes.
	 * @return string
	 */
	public function admin_welcome_css( $classes ) {

		if ( ! is_admin() ) {
			return;
		}

		$classes .= ' envira-welcome-enabled ';

		return $classes;
	}

	/**
	 * Register and enqueue addons page specific JS.
	 *
	 * @since 1.5.0
	 */
	public function enqueue_admin_scripts() {
		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'envira' === wp_unslash( $_GET['post_type'] ) && in_array( wp_unslash( $_GET['page'] ), $this->pages ) ) { // @codingStandardsIgnoreLine

			wp_register_script( ENVIRA_LITE_SLUG . '-welcome-script', plugins_url( 'assets/js/welcome.js', ENVIRA_LITE_FILE ), [ 'jquery' ], ENVIRA_LITE_VERSION, true );
			wp_enqueue_script( ENVIRA_LITE_SLUG . '-welcome-script' );
			wp_localize_script(
				ENVIRA_LITE_SLUG . '-welcome-script',
				'envira_gallery_welcome',
				[
					'activate_nonce'   => wp_create_nonce( 'envira-gallery-activate-partner' ),
					'active'           => __( 'Status: Active', 'envira-gallery-lite' ),
					'activate'         => __( 'Activate', 'envira-gallery-lite' ),
					'get_addons_nonce' => wp_create_nonce( 'envira-gallery-get-addons' ),
					'activating'       => __( 'Activating...', 'envira-gallery-lite' ),
					'ajax'             => admin_url( 'admin-ajax.php' ),
					'deactivate'       => __( 'Deactivate', 'envira-gallery-lite' ),
					'deactivate_nonce' => wp_create_nonce( 'envira-gallery-deactivate-partner' ),
					'deactivating'     => __( 'Deactivating...', 'envira-gallery-lite' ),
					'inactive'         => __( 'Status: Inactive', 'envira-gallery-lite' ),
					'install'          => __( 'Install', 'envira-gallery-lite' ),
					'install_nonce'    => wp_create_nonce( 'envira-gallery-install-partner' ),
					'installing'       => __( 'Installing...', 'envira-gallery-lite' ),
					'proceed'          => __( 'Proceed', 'envira-gallery-lite' ),
				]
			);
		}
	}

	/**
	 * Register and enqueue addons page specific CSS.
	 *
	 * @since 1.8.1
	 *
	 * @return void
	 */
	public function enqueue_admin_styles() {

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'envira' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) && in_array( wp_unslash( $_GET['page'] ), $this->pages, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			wp_register_style( ENVIRA_LITE_SLUG . '-welcome-style', plugins_url( 'assets/css/welcome.css', ENVIRA_LITE_FILE ), [], ENVIRA_LITE_VERSION );
			wp_enqueue_style( ENVIRA_LITE_SLUG . '-welcome-style' );

		}

		// Run a hook to load in custom styles.
		do_action( 'envira_gallery_addons_styles' );
	}

	/**
	 * Making page as clean as possible
	 *
	 * @since 1.8.1
	 *
	 * @return void
	 */
	public function disable_admin_notices() {

		global $wp_filter;

		if ( isset( $_GET['post_type'] ) && isset( $_GET['page'] ) && 'envira' === sanitize_text_field( wp_unslash( $_GET['post_type'] ) ) && in_array( sanitize_text_field( wp_unslash( $_GET['page'] ) ), $this->pages, true ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( isset( $wp_filter['user_admin_notices'] ) ) {
				unset( $wp_filter['user_admin_notices'] );
			}
			if ( isset( $wp_filter['admin_notices'] ) ) {
				unset( $wp_filter['admin_notices'] );
			}
			if ( isset( $wp_filter['all_admin_notices'] ) ) {
				unset( $wp_filter['all_admin_notices'] );
			}
		}
	}

	/**
	 * Helper Method to get AM Plugins
	 *
	 * @since 1.8.7
	 *
	 * @return array
	 */
	public function get_am_plugins() {

		$images_url = trailingslashit( ENVIRA_LITE_URL . 'assets/images/about' );
		$plugins    = [
			'optinmonster'                                 => [
				'icon'        => $images_url . 'plugin-om.png',
				'name'        => esc_html__( 'OptinMonster', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Instantly get more subscribers, leads, and sales with the #1 conversion optimization toolkit. Create high converting popups, announcement bars, spin a wheel, and more with smart targeting and personalization.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/optinmonster/',
				'url'         => 'https://downloads.wordpress.org/plugin/optinmonster.zip',
				'basename'    => 'optinmonster/optin-monster-wp-api.php',
			],
			'google-analytics-for-wordpress'               => [
				'icon'        => $images_url . 'plugin-mi.png',
				'name'        => esc_html__( 'MonsterInsights', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The leading WordPress analytics plugin that shows you how people find and use your website, so you can make data driven decisions to grow your business. Properly set up Google Analytics without writing code.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/google-analytics-for-wordpress/',
				'url'         => 'https://downloads.wordpress.org/plugin/google-analytics-for-wordpress.zip',
				'basename'    => 'google-analytics-for-wordpress/googleanalytics.php',
				'pro'         => [
					'plug'        => 'google-analytics-premium/googleanalytics-premium.php',
					'icon'        => $images_url . 'plugin-mi.png',
					'name'        => esc_html__( 'MonsterInsights Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'The leading WordPress analytics plugin that shows you how people find and use your website, so you can make data driven decisions to grow your business. Properly set up Google Analytics without writing code.', 'envira-gallery-lite' ),
					'url'         => 'https://www.monsterinsights.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'wp-mail-smtp/wp_mail_smtp.php'                => [
				'icon'        => $images_url . 'plugin-smtp.png',
				'name'        => esc_html__( 'WP Mail SMTP', 'envira-gallery-lite' ),
				'description' => esc_html__( "Improve your WordPress email deliverability and make sure that your website emails reach user's inbox with the #1 SMTP plugin for WordPress. Over 3 million websites use it to fix WordPress email issues.", 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/wp-mail-smtp/',
				'url'         => 'https://downloads.wordpress.org/plugin/wp-mail-smtp.zip',
				'basename'    => 'wp-mail-smtp/wp_mail_smtp.php',
				'pro'         => [
					'plug'        => 'wp-mail-smtp-pro/wp_mail_smtp.php',
					'icon'        => $images_url . 'plugin-smtp.png',
					'name'        => esc_html__( 'WP Mail SMTP Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( "Improve your WordPress email deliverability and make sure that your website emails reach user's inbox with the #1 SMTP plugin for WordPress. Over 3 million websites use it to fix WordPress email issues.", 'envira-gallery-lite' ),
					'url'         => 'https://wpmailsmtp.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'all-in-one-seo-pack/all_in_one_seo_pack.php'  => [
				'icon'        => $images_url . 'plugin-aioseo.png',
				'name'        => esc_html__( 'AIOSEO', 'envira-gallery-lite' ),
				'description' => esc_html__( "The original WordPress SEO plugin and toolkit that improves your website's search rankings. Comes with all the SEO features like Local SEO, WooCommerce SEO, sitemaps, SEO optimizer, schema, and more.", 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/all-in-one-seo-pack/',
				'url'         => 'https://downloads.wordpress.org/plugin/all-in-one-seo-pack.zip',
				'basename'    => 'all-in-one-seo-pack/all_in_one_seo_pack.php',
				'pro'         => [
					'plug'        => 'all-in-one-seo-pack-pro/all_in_one_seo_pack.php',
					'icon'        => $images_url . 'plugin-aioseo.png',
					'name'        => esc_html__( 'AIOSEO Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( "The original WordPress SEO plugin and toolkit that improves your website's search rankings. Comes with all the SEO features like Local SEO, WooCommerce SEO, sitemaps, SEO optimizer, schema, and more.", 'envira-gallery-lite' ),
					'url'         => 'https://aioseo.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'coming-soon/coming-soon.php'                  => [
				'icon'        => $images_url . 'plugin-seedprod.png',
				'name'        => esc_html__( 'SeedProd', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The fastest drag & drop landing page builder for WordPress. Create custom landing pages without writing code, connect them with your CRM, collect subscribers, and grow your audience. Trusted by 1 million sites.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/coming-soon/',
				'url'         => 'https://downloads.wordpress.org/plugin/coming-soon.zip',
				'basename'    => 'coming-soon/coming-soon.php',
				'pro'         => [
					'plug'        => 'seedprod-coming-soon-pro-5/seedprod-coming-soon-pro-5.php',
					'icon'        => $images_url . 'plugin-seedprod.png',
					'name'        => esc_html__( 'SeedProd Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'The fastest drag & drop landing page builder for WordPress. Create custom landing pages without writing code, connect them with your CRM, collect subscribers, and grow your audience. Trusted by 1 million sites.', 'envira-gallery-lite' ),
					'url'         => 'https://www.seedprod.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'rafflepress/rafflepress.php'                  => [
				'icon'        => $images_url . 'plugin-rp.png',
				'name'        => esc_html__( 'RafflePress', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Turn your website visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with the most powerful giveaways & contests plugin for WordPress.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/rafflepress/',
				'url'         => 'https://downloads.wordpress.org/plugin/rafflepress.zip',
				'basename'    => 'rafflepress/rafflepress.php',
				'pro'         => [
					'plug'        => 'rafflepress-pro/rafflepress-pro.php',
					'icon'        => $images_url . 'plugin-rp.png',
					'name'        => esc_html__( 'RafflePress Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'Turn your website visitors into brand ambassadors! Easily grow your email list, website traffic, and social media followers with the most powerful giveaways & contests plugin for WordPress.', 'envira-gallery-lite' ),
					'url'         => 'https://rafflepress.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'pushengage/main.php'                          => [
				'icon'        => $images_url . 'plugin-pushengage.png',
				'name'        => esc_html__( 'PushEngage', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Connect with your visitors after they leave your website with the leading web push notification software. Over 10,000+ businesses worldwide use PushEngage to send 15 billion notifications each month.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/pushengage/',
				'url'         => 'https://downloads.wordpress.org/plugin/pushengage.zip',
				'basename'    => 'pushengage/main.php',
			],

			'instagram-feed/instagram-feed.php'            => [
				'icon'        => $images_url . 'plugin-sb-instagram.png',
				'name'        => esc_html__( 'Smash Balloon Instagram Feeds', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Easily display Instagram content on your WordPress site without writing any code. Comes with multiple templates, ability to show content from multiple accounts, hashtags, and more. Trusted by 1 million websites.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/instagram-feed/',
				'url'         => 'https://downloads.wordpress.org/plugin/instagram-feed.zip',
				'basename'    => 'instagram-feed/instagram-feed.php',
				'pro'         => [
					'plug'        => 'instagram-feed-pro/instagram-feed.php',
					'icon'        => $images_url . 'plugin-sb-instagram.png',
					'name'        => esc_html__( 'Smash Balloon Instagram Feeds Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'Easily display Instagram content on your WordPress site without writing any code. Comes with multiple templates, ability to show content from multiple accounts, hashtags, and more. Trusted by 1 million websites.', 'envira-gallery-lite' ),
					'url'         => 'https://smashballoon.com/instagram-feed/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'custom-facebook-feed/custom-facebook-feed.php' => [
				'icon'        => $images_url . 'plugin-sb-fb.png',
				'name'        => esc_html__( 'Smash Balloon Facebook Feeds', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Easily display Facebook content on your WordPress site without writing any code. Comes with multiple templates, ability to embed albums, group content, reviews, live videos, comments, and reactions.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/custom-facebook-feed/',
				'url'         => 'https://downloads.wordpress.org/plugin/custom-facebook-feed.zip',
				'basename'    => 'custom-facebook-feed/custom-facebook-feed.php',
				'pro'         => [
					'plug'        => 'custom-facebook-feed-pro/custom-facebook-feed.php',
					'icon'        => $images_url . 'plugin-sb-fb.png',
					'name'        => esc_html__( 'Smash Balloon Facebook Feeds Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'Easily display Facebook content on your WordPress site without writing any code. Comes with multiple templates, ability to embed albums, group content, reviews, live videos, comments, and reactions.', 'envira-gallery-lite' ),
					'url'         => 'https://smashballoon.com/custom-facebook-feed/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'feeds-for-youtube/youtube-feed.php'           => [
				'icon'        => $images_url . 'plugin-sb-youtube.png',
				'name'        => esc_html__( 'Smash Balloon YouTube Feeds', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Easily display YouTube videos on your WordPress site without writing any code. Comes with multiple layouts, ability to embed live streams, video filtering, ability to combine multiple channel videos, and more.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/feeds-for-youtube/',
				'url'         => 'https://downloads.wordpress.org/plugin/feeds-for-youtube.zip',
				'basename'    => 'feeds-for-youtube/youtube-feed.php',
				'pro'         => [
					'plug'        => 'youtube-feed-pro/youtube-feed.php',
					'icon'        => $images_url . 'plugin-sb-youtube.png',
					'name'        => esc_html__( 'Smash Balloon YouTube Feeds Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'Easily display YouTube videos on your WordPress site without writing any code. Comes with multiple layouts, ability to embed live streams, video filtering, ability to combine multiple channel videos, and more.', 'envira-gallery-lite' ),
					'url'         => 'https://smashballoon.com/youtube-feed/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'custom-twitter-feeds/custom-twitter-feed.php' => [
				'icon'        => $images_url . 'plugin-sb-twitter.png',
				'name'        => esc_html__( 'Smash Balloon Twitter Feeds', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Easily display Twitter content in WordPress without writing any code. Comes with multiple layouts, ability to combine multiple Twitter feeds, Twitter card support, tweet moderation, and more.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/custom-twitter-feeds/',
				'url'         => 'https://downloads.wordpress.org/plugin/custom-twitter-feeds.zip',
				'basename'    => 'custom-twitter-feeds/custom-twitter-feed.php',
				'pro'         => [
					'plug'        => 'custom-twitter-feeds-pro/custom-twitter-feed.php',
					'icon'        => $images_url . 'plugin-sb-twitter.png',
					'name'        => esc_html__( 'Smash Balloon Twitter Feeds Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'Easily display Twitter content in WordPress without writing any code. Comes with multiple layouts, ability to combine multiple Twitter feeds, Twitter card support, tweet moderation, and more.', 'envira-gallery-lite' ),
					'url'         => 'https://smashballoon.com/custom-twitter-feeds/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'trustpulse-api/trustpulse.php'                => [
				'icon'        => $images_url . 'plugin-trustpulse.png',
				'name'        => esc_html__( 'TrustPulse', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Boost your sales and conversions by up to 15% with real-time social proof notifications. TrustPulse helps you show live user activity and purchases to help convince other users to purchase.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/trustpulse-api/',
				'url'         => 'https://downloads.wordpress.org/plugin/trustpulse-api.zip',
				'basename'    => 'trustpulse-api/trustpulse.php',
			],
			'searchwp/index.php'                           => [
				'icon'        => $images_url . 'plugin-searchwp.png',
				'name'        => esc_html__( 'SearchWP', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The most advanced WordPress search plugin. Customize your WordPress search algorithm, reorder search results, track search metrics, and everything you need to leverage search to grow your business.', 'envira-gallery-lite' ),
				'wporg'       => false,
				'url'         => 'https://searchwp.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
				'act'         => 'go-to-url',
			],
			'affiliate-wp/affiliate-wp.php'                => [
				'icon'        => $images_url . 'plugin-affwp.png',
				'name'        => esc_html__( 'AffiliateWP', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The #1 affiliate management plugin for WordPress. Easily create an affiliate program for your eCommerce store or membership site within minutes and start growing your sales with the power of referral marketing.', 'envira-gallery-lite' ),
				'wporg'       => false,
				'url'         => 'https://affiliatewp.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
				'act'         => 'go-to-url',
			],
			'stripe/stripe-checkout.php'                   => [
				'icon'        => $images_url . 'plugin-wp-simple-pay.png',
				'name'        => esc_html__( 'WP Simple Pay', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The #1 Stripe payments plugin for WordPress. Start accepting one-time and recurring payments on your WordPress site without setting up a shopping cart. No code required.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/stripe/',
				'url'         => 'https://downloads.wordpress.org/plugin/stripe.zip',
				'basename'    => 'stripe/stripe-checkout.php',
				'pro'         => [
					'plug'        => 'wp-simple-pay-pro-3/simple-pay.php',
					'icon'        => $images_url . 'plugin-wp-simple-pay.png',
					'name'        => esc_html__( 'WP Simple Pay Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'The #1 Stripe payments plugin for WordPress. Start accepting one-time and recurring payments on your WordPress site without setting up a shopping cart. No code required.', 'envira-gallery-lite' ),
					'url'         => 'https://wpsimplepay.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],

			'easy-digital-downloads/easy-digital-downloads.php' => [
				'icon'        => $images_url . 'plugin-edd.png',
				'name'        => esc_html__( 'Easy Digital Downloads', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The best WordPress eCommerce plugin for selling digital downloads. Start selling eBooks, software, music, digital art, and more within minutes. Accept payments, manage subscriptions, advanced access control, and more.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/easy-digital-downloads/',
				'url'         => 'https://downloads.wordpress.org/plugin/easy-digital-downloads.zip',
				'basename'    => 'easy-digital-downloads/easy-digital-downloads.php',
			],

			'sugar-calendar-lite/sugar-calendar-lite.php'  => [
				'icon'        => $images_url . 'plugin-sugarcalendar.png',
				'name'        => esc_html__( 'Sugar Calendar', 'envira-gallery-lite' ),
				'description' => esc_html__( 'A simple & powerful event calendar plugin for WordPress that comes with all the event management features including payments, scheduling, timezones, ticketing, recurring events, and more.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/sugar-calendar-lite/',
				'url'         => 'https://downloads.wordpress.org/plugin/sugar-calendar-lite.zip',
				'basename'    => 'sugar-calendar-lite/sugar-calendar-lite.php',
				'pro'         => [
					'plug'        => 'sugar-calendar/sugar-calendar.php',
					'icon'        => $images_url . 'plugin-sugarcalendar.png',
					'name'        => esc_html__( 'Sugar Calendar Pro', 'envira-gallery-lite' ),
					'description' => esc_html__( 'A simple & powerful event calendar plugin for WordPress that comes with all the event management features including payments, scheduling, timezones, ticketing, recurring events, and more.', 'envira-gallery-lite' ),
					'url'         => 'https://sugarcalendar.com/?utm_source=enviragallerylite&utm_medium=link&utm_campaign=About%20Envira',
					'act'         => 'go-to-url',
				],
			],
			'charitable/charitable.php'                    => [
				'icon'        => $images_url . 'plugin-charitable.png',
				'name'        => esc_html__( 'WP Charitable', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Top-rated WordPress donation and fundraising plugin. Over 10,000+ non-profit organizations and website owners use Charitable to create fundraising campaigns and raise more money online.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/charitable/',
				'url'         => 'https://downloads.wordpress.org/plugin/charitable.zip',
				'basename'    => 'charitable/charitable.php',
			],
			'insert-headers-and-footers/ihaf.php'          => [
				'icon'        => $images_url . 'plugin-wpcode.png',
				'name'        => esc_html__( 'WPCode', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Future proof your WordPress customizations with the most popular code snippet management plugin for WordPress. Trusted by over 1,500,000+ websites for easily adding code to WordPress right from the admin area.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/insert-headers-and-footers/',
				'url'         => 'https://downloads.wordpress.org/plugin/insert-headers-and-footers.zip',
				'basename'    => 'insert-headers-and-footers/ihaf.php',
			],
			'duplicator/duplicator.php'                    => [
				'icon'        => $images_url . 'plugin-duplicator.png',
				'name'        => esc_html__( 'Duplicator', 'envira-gallery-lite' ),
				'description' => esc_html__( 'Leading WordPress backup & site migration plugin. Over 1,500,000+ smart website owners use Duplicator to make reliable and secure WordPress backups to protect their websites. It also makes website migration really easy.', 'envira-gallery-lite' ),
				'wporg'       => 'https://wordpress.org/plugins/duplicator/',
				'url'         => 'https://downloads.wordpress.org/plugin/duplicator.zip',
				'basename'    => 'duplicator/duplicator.php',
			],
			'soliloquy'                                    => [
				'icon'        => $images_url . 'soliloquy.png',
				'name'        => esc_html__( 'Slider by Soliloquy – Responsive Image Slider for WordPress', 'envira-gallery-lite' ),
				'description' => esc_html__( 'The best WordPress slider plugin. Drag & Drop responsive slider builder that helps you create a beautiful image slideshows with just a few clicks.', 'envira-gallery-lite' ),
				'url'         => 'https://downloads.wordpress.org/plugin/soliloquy-lite.zip',
				'basename'    => 'soliloquy-lite/soliloquy-lite.php',
			],
		];

		return $plugins;
	}

	/**
	 * Register the Welcome submenu item for Envira.
	 *
	 * @since 1.8.1
	 *
	 * @return void
	 */
	public function admin_menu() {

		global $submenu;

		$whitelabel = apply_filters( 'envira_whitelabel', false ) ? '' : esc_html__( 'Envira Gallery ', 'envira-gallery-lite' );

		// Register the submenus.
		add_submenu_page(
			'edit.php?post_type=envira',
			$whitelabel . esc_html__( 'About Us', 'envira-gallery-lite' ),
			'<span style="color:#FFA500"> ' . esc_html__( 'About Us', 'envira-gallery-lite' ) . '</span>',
			apply_filters( 'envira_gallery_menu_cap', 'manage_options' ),
			ENVIRA_LITE_SLUG . '-about-us',
			[ $this, 'about_page' ]
		);

		add_submenu_page(
			'edit.php?post_type=envira',
			$whitelabel . esc_html__( 'Getting Started', 'envira-gallery-lite' ),
			'<span style="color:#FFA500"> ' . esc_html__( 'Getting Started', 'envira-gallery-lite' ) . '</span>',
			apply_filters( 'envira_gallery_menu_cap', 'manage_options' ),
			ENVIRA_LITE_SLUG . '-get-started',
			[ $this, 'help_page' ]
		);

		add_submenu_page(
			'edit.php?post_type=envira',
			$whitelabel . esc_html__( 'Lite vs Pro', 'envira-gallery-lite' ),
			'<span style="color:#FFA500"> ' . esc_html__( 'Lite vs Pro', 'envira-gallery-lite' ) . '</span>',
			apply_filters( 'envira_gallery_menu_cap', 'manage_options' ),
			ENVIRA_LITE_SLUG . '-litevspro',
			[ $this, 'lite_vs_pro_page' ]
		);

		unset( $submenu['edit.php?post_type=envira'][15] );
		unset( $submenu['edit.php?post_type=envira'][16] );
	}

	/**
	 * Output tab navigation
	 *
	 * @since 2.2.0
	 *
	 * @param string $tab Tab to highlight as active.
	 */
	public static function tab_navigation( $tab = 'whats_new' ) {
		?>

		<ul class="envira-nav-tab-wrapper">
			<li>
			<a class="envira-nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'envira-gallery-lite-about-us' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				envira-nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							[
								'post_type' => 'envira',
								'page'      => 'envira-gallery-lite-about-us',
							],
							'edit.php'
						)
					)
				);
				?>
														">
				<?php esc_html_e( 'About Us', 'envira-gallery-lite' ); ?>
			</a>
			</li>
			<li>
			<a class="envira-nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'envira-gallery-lite-get-started' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				envira-nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							[
								'post_type' => 'envira',
								'page'      => 'envira-gallery-lite-get-started',
							],
							'edit.php'
						)
					)
				);
				?>
														">
				<?php esc_html_e( 'Getting Started', 'envira-gallery-lite' ); ?>
			</a>
			</li>
			<li>
			<a class="envira-nav-tab
			<?php
			if ( isset( $_GET['page'] ) && 'envira-gallery-lite-litevspro' === sanitize_text_field( wp_unslash( $_GET['page'] ) ) ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				?>
				envira-nav-tab-active<?php endif; ?>" href="
				<?php
				echo esc_url(
					admin_url(
						add_query_arg(
							[
								'post_type' => 'envira',
								'page'      => 'envira-gallery-lite-litevspro',
							],
							'edit.php'
						)
					)
				);
				?>
														">
				<?php esc_html_e( 'Lite vs Pro', 'envira-gallery-lite' ); ?>
			</a>
			</li>

		</ul>

		<?php
	}

	/**
	 * Output the about screen.
	 *
	 * @since 1.8.5
	 */
	public function about_page() {

		self::tab_navigation( __METHOD__ );
		?>
		<div class="envira-welcome-wrap envira-about">
			<div class="envira-panel envira-lite-about-panel">
				<div class="content">
					<h3><?php esc_html_e( 'Hello and welcome to Envira Gallery, the most beginner-friendly WordPress Gallery Plugin. At Envira Gallery, we build software that helps you create beautiful galleries in minutes.', 'envira-gallery-lite' );?></h3>
					<p><?php esc_html_e( 'Over the years, we found that most WordPress gallery plugins were bloated, buggy, slow, and very hard to use. So, we started with a simple goal: build a WordPress gallery system that’s both easy and powerful.', 'envira-gallery-lite' );?></p>
					<p><?php esc_html_e( 'Our goal is to provide the easiest way to create beautiful galleries.', 'envira-gallery-lite' );?></p>
					<p><?php esc_html_e( 'Envira Gallery is brought to you by the same team that’s behind the largest WordPress resource site, WPBeginner, the most popular lead-generation software, OptinMonster, the best WordPress analytics plugin, MonsterInsights, and more!', 'envira-gallery-lite' );?></p>
					<p><?php esc_html_e( 'Yup, we know a thing or two about building awesome products that customers love.', 'envira-gallery-lite' );?></p>
				</div>
				<div class="image">
					<img src="<?php echo esc_url( trailingslashit( ENVIRA_LITE_URL ) . 'assets/images/about/team.jpg' ); ?> ">
				</div>
			</div>

			<div class="envira-am-plugins-wrap">
				<?php
				foreach ( $this->get_am_plugins() as $partner ) :

					$this->get_plugin_card( $partner );

				endforeach;
				?>
			</div>

		</div> <!-- wrap -->

		<?php
	}

	/**
	 * Output the about screen.
	 *
	 * @since 1.8.1
	 */
	public function help_page() {
		?>
		<?php self::tab_navigation( __METHOD__ ); ?>

		<div class="envira-welcome-wrap envira-help">

			<div class="envira-get-started-main">

				<div class="envira-get-started-section">

						<div class="envira-admin-get-started-panel envira-panel">

							<div class="section-text text-left">
								<h2><?php esc_html_e( 'Creating your first gallery', 'envira-gallery-lite' ); ?></h2>
								<p>Want to get started creating your first gallery? By following the step by step instructions in this walkthrough, you can easily publish your first gallery on your site. To begin, you’ll need to be logged into the WordPress admin area. Once there, click on Envira Gallery in the admin sidebar to go the Add New page. This will launch the Envira Gallery Builder.</p>

								<a href="<?php echo esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( 'https://enviragallery.com/docs/creating-first-envira-gallery/', 'gettingstartedtab', 'readtheguide' ) ); ?>" class="button envira-button envira-button-dark" target="_blank">Read the Setup Guide</a>

							</div>

							<div class="feature-photo-column">
									<img class="feature-photo" src="<?php echo esc_url( plugins_url( 'assets/images/get-started/creating.png', ENVIRA_LITE_FILE ) ); ?>" />
							</div>

						</div> <!-- panel -->

						<div class="envira-admin-upgrade-panel envira-panel">

							<div class="section-text-column text-left">

								<h2>Upgrade to a complete Envira Gallery experience</h2>

								<p>Get the most out of Envira Gallery by <a target="_blank" href="<?php echo esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( false, 'gettingstartedtab', 'upgradetounlockallitspowerfulfeatures' ) ); ?>">upgrading to unlock all of its powerful features</a>.</p>

								<p>With Envira Gallery Pro, you can unlock amazing features like:</p>

								<ul>
									<li>Get your gallery set up in minutes with pre-built customizable templates </li>
									<li>Have more people find you on Google by making your galleries SEO friendly </li>
									<li>Display your photos in all their glory on mobile with a true full-screen experience. No bars, buttons or small arrows</li>
									<li>Tag your images for better organization and gallery display</li>
									<li>Improve load times and visitor experience by splitting your galleries into multiple pages </li>
									<li>Streamline your workflow by sharing your gallery images directly on your favorite social media networks </li>
									</li>
								</ul>
								<a href="<?php echo esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( false, 'gettingstarted', 'unlockpro' ) ); ?>" class="button envira-button envira-button-dark" target="_blank">Unlock Pro</a>
							</div>

							<div class="feature-photo-column">
									<img class="feature-photo" src="<?php echo esc_url( plugins_url( 'assets/images/envira-admin.png', ENVIRA_LITE_FILE ) ); ?>" />
							</div>

						</div> <!-- panel -->

						<div class="envira-admin-3-col envira-help-section">
							<div class="envira-cols">
								<svg mlns="http://www.w3.org/2000/svg" width="50px" viewBox="0 0 512 512" fill="#454346">
								<path d="M432 0H48C21.6 0 0 21.6 0 48v416c0 26.4 21.6 48 48 48h384c26.4 0 48-21.6 48-48V48c0-26.4-21.6-48-48-48zm-16 448H64V64h352v384zM128 224h224v32H128zm0 64h224v32H128zm0 64h224v32H128zm0-192h224v32H128z"></path>
								</svg>

								<h3>Help and Documention</h3>
								<p>The Envira Gallery wiki has helpful documentation, tips, tricks, and code snippets to help you get started.</p>
								<a href="<?php echo esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( 'https://enviragallery.com/docs/', 'gettingstarted', 'helpanddocs' ) ); ?>" class="button envira-button envira-button-dark" target="_blank">Browse the docs</a>
							</div>
							<div class="envira-cols">
								<svg xmlns="http://www.w3.org/2000/svg" width="50px" viewBox="0 0 512 512" fill="#A32323">
									<path d="M256 0C114.615 0 0 114.615 0 256s114.615 256 256 256 256-114.615 256-256S397.385 0 256 0zm-96 256c0-53.02 42.98-96 96-96s96 42.98 96 96-42.98 96-96 96-96-42.98-96-96zm302.99 85.738l-88.71-36.745C380.539 289.901 384 273.355 384 256s-3.461-33.901-9.72-48.993l88.71-36.745C473.944 196.673 480 225.627 480 256s-6.057 59.327-17.01 85.738zM341.739 49.01l-36.745 88.71C289.902 131.461 273.356 128 256 128s-33.901 3.461-48.993 9.72l-36.745-88.711C196.673 38.057 225.628 32 256 32c30.373 0 59.327 6.057 85.739 17.01zM49.01 170.262l88.711 36.745C131.462 222.099 128 238.645 128 256s3.461 33.901 9.72 48.993l-88.71 36.745C38.057 315.327 32 286.373 32 256s6.057-59.327 17.01-85.738zM170.262 462.99l36.745-88.71C222.099 380.539 238.645 384 256 384s33.901-3.461 48.993-9.72l36.745 88.71C315.327 473.942 286.373 480 256 480s-59.327-6.057-85.738-17.01z"></path>
								</svg>
								<h3>Get Support</h3>
								<p>Submit a support ticket and our world class support will be in touch.</p>
								<a href="<?php echo esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( false, 'gettingstarted', 'getsupport' ) ); ?>" class="button envira-button envira-button-dark" target="_blank">Unlock Pro</a>
							</div>
							<div class="envira-cols">
								<svg xmlns="http://www.w3.org/2000/svg" x="0" y="0" version="1.1" viewBox="0 0 256 256" xmlSpace="preserve" width="50px" >
									<path fill="#7CC048" d="M87.59 183.342c42.421 33.075 82.686 19.086 101.079 17.141l33.107 32.912H234l-38.011-37.781C195.696 172.965 247.988 26.3 23 26.3c24.504 84.486 22.163 123.991 64.59 157.042m40.886-62.735c10.723 19.952 29.62 50.381 42.937 60.168 13.302 9.789 27.772 16.947-2.893 4.004-30.661-12.982-53.056-49.711-67.895-77.54-11.414-21.39-21.243-40.903-42.528-55.348-21.284-14.479 2.477-4.298 2.477-4.298 38.9 18.989 53.003 45.217 67.902 73.014"></path>
								</svg>
								<h3>Enjoying Envira Gallery?</h3>
								<p>Submit a support ticket and our world class support will be in touch.</p>
								<a href="<?php echo esc_url( 'https://wordpress.org/support/plugin/envira-gallery-lite/reviews/?filter=5#new-post' ); ?>" class="button envira-button envira-button-dark" target="_blank">Leave a Review</a>
							</div>
						</div>
				</div>

			</div> <!-- wrap -->

		</div>
		<?php
	}

	/**
	 * Output the upgrade screen.
	 *
	 * @since 1.8.1
	 */
	public function lite_vs_pro_page() {
		?>
		<?php self::tab_navigation( __METHOD__ ); ?>

		<div class="envira-welcome-wrap envira-help">

			<div class="envira-get-started-main">

				<div class="envira-get-started-panel">

				<div id="envira-admin-litevspro" class="wrap envira-admin-wrap">

					<div class="envira-panel envira-litevspro-panel">
				<div class="envira-admin-litevspro-section envira-admin-litevspro-section-hero">

				<h2 class="headline-title">
						<strong>Lite</strong> vs <strong>Pro</strong>
					</h2>

					<h4 class="headline-subtitle">Get the most out of Envira by upgrading to Pro and unlocking all the powerful features.</h4>
				</div>
				<div class="envira-admin-litevspro-section no-bottom envira-admin-litevspro-section-table">

						<table cellspacing="0" cellpadding="0" border="0">
							<thead>
								<th>Feature</th>
								<th>Lite</th>
								<th>Pro</th>
							</thead>
							<tbody>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Gallery Themes And Layouts</p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-partial">
											<strong>Basic Gallery Theme</strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Gallery Themes &amp; Layouts</strong>
											More themes to make your Galleries unique and professional.
										</p>
									</td>
								</tr>

								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Lightbox Features</p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-partial">
											<strong>Basic Lightbox</strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Advanced Lightbox Features</strong>
											Multiple themes for your Gallery Lightbox display, Titles, Transitions, Fullscreen, Counter, Thumbnails
										</p>
									</td>
								</tr>

								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Mobile Features</p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-partial">
											<strong>Basic Mobile Gallery  </strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Advanced Mobile Settings</strong>Customize all aspects of your user's mobile gallery display experience to be different than the default desktop</p>
									</td>
								</tr>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Import/Export Options </p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>Limited Import/Export </strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Import/Export </strong> Instagram, Dropbox, NextGen, Flickr, Zip and more</p>
									</td>
								</tr>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Video Galleries  </p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-none">
											<strong> No Videos  </strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Videos Gallery </strong> Import your own videos or from any major video sharing platform</p>
									</td>
								</tr>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Social Sharing   </p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>No Social Sharing     </strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Social Sharing Features</strong>Share your photos on any major social sharing platform</p>
									</td>
								</tr>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Advanced Gallery Features  </p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>  No Advanced Features     </strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong>All Advanced Features</strong>Albums, Ecommerce, Pagination, Deeplinking, and Expanded Gallery Configurations</p>
									</td>
								</tr>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Envira Gallery Addons      </p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>  No Addons Included  </strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong> All Addons Included</strong>WooCommerce, Tags and Filters, Proofing, Schedule, Password Protection, Lightroom, Slideshows, Watermarking and more (28 total)            </p>
									</td>
								</tr>
								<tr class="envira-admin-columns">
									<td class="envira-admin-litevspro-first-column">
										<p>Customer Support </p>
									</td>
									<td class="envira-admin-litevspro-lite-column">
										<p class="features-none">
											<strong>Limited Customer Support</strong>
										</p>
									</td>
									<td class="envira-admin-litevspro-pro-column">
										<p class="features-full">
											<strong> Priority Customer Support</strong>Dedicated prompt service via email from our top tier support team. Your request is assigned the highest priority</p>
									</td>
								</tr>

							</tbody>
						</table>

				</div>

				<div class="envira-admin-litevspro-section envira-admin-litevspro-section-hero">
					<div class="envira-admin-about-section-hero-main no-border">
						<h3 class="call-to-action">
						<a class="envira-button-text" href="<?php echo esc_url( Envira_Gallery_Common_Admin::get_instance()->get_upgrade_link( false, 'litevsprotab', 'getenviragalleryprotoday' ) ); ?>" target="_blank" rel="noopener noreferrer">Get Envira Pro Today and Unlock all the Powerful Features!</a>
					</h3>

						<p>Bonus: Envira Gallery Lite users get <span class="envira-deal 20-percent-off">50% off regular price</span>, automatically applied at checkout.</p>
					</div>
				</div>

				</div>

				</div>
				</div>

			</div>

		</div> <!-- wrap -->


		<?php
	}

	/**
	 * Helper method to get plugin card
	 *
	 * @param mixed $plugin False or plugin data array.
	 * @return void
	 */
	public function get_plugin_card( $plugin = false ) {

		if ( ! $plugin ) {
			return;
		}
		$this->installed_plugins = get_plugins();

		if ( ( isset( $plugin['basename'] ) && ! isset( $this->installed_plugins[ $plugin['basename'] ] ) ) || isset( $plugin['act'] ) ) {
			?>
			<div class="envira-am-plugins">
				<div class="envira-am-plugins-main">
					<div>
						<img src="<?php echo esc_attr( $plugin['icon'] ); ?>" width="64px" />
					</div>
					<div>
						<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
						<p class="envira-am-plugins-excerpt"><?php echo esc_html( $plugin['description'] ); ?></p>
					</div>
				</div>
					<div class="envira-am-plugins-footer">
					<div class="envira-am-plugins-status">Status:&nbsp;<span>Not Installed</span></div>
						<div class="envira-am-plugins-install-wrap">
							<span class="spinner envira-am-plugins-spinner"></span>
							<?php if ( isset( $plugin['basename'] ) ) : ?>
								<a href="#" class="button envira-button-dark envira-am-plugins-install" data-url="<?php echo esc_url( $plugin['url'] ); ?>" data-basename="<?php echo esc_attr( $plugin['basename'] ); ?>">Install Plugin</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank" class="button envira-button-dark" data-url="<?php echo esc_url( $plugin['url'] ); ?>" >Install Plugin</a>
							<?php endif; ?>
						</div>
					</div>
				</div>
			<?php
		} else {
			if ( isset( $plugin['basename'] ) && is_plugin_active( $plugin['basename'] ) ) {
				?>
							<div class="envira-am-plugins">
							<div class="envira-am-plugins-main">
								<div>
									<img src="<?php echo esc_attr( $plugin['icon'] ); ?>" width="64px" />
								</div>
								<div>
									<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
								<p class="envira-am-plugins-excerpt"><?php echo esc_html( $plugin['description'] ); ?></p>
								</div>
							</div>
								<div class="envira-am-plugins-footer">
							<div class="envira-am-plugins-status">Status:&nbsp;<span>Active</span></div>
								<div class="envira-am-plugins-install-wrap">
								<span class="spinner envira-am-plugins-spinner"></span>
							<?php if ( isset( $plugin['basename'] ) ) : ?>
								<a href="#" target="_blank" class="button envira-button-dark envira-am-plugins-deactivate" data-url="<?php echo esc_url( $plugin['url'] ); ?>" data-basename="<?php echo esc_attr( $plugin['basename'] ); ?>">Deactivate</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank" class="button envira-button-dark envira-am-plugins-deactivate" data-url="<?php echo esc_url( $plugin['url'] ); ?>">Activate</a>
							<?php endif; ?>
						</div>
				</div>
						</div>
			<?php } else { ?>
				<div class="envira-am-plugins">
							<div class="envira-am-plugins-main">
								<div>
									<img src="<?php echo esc_attr( $plugin['icon'] ); ?>" width="64px" />
								</div>
								<div>
									<h3><?php echo esc_html( $plugin['name'] ); ?></h3>
								<p class="envira-am-plugins-excerpt"><?php echo esc_html( $plugin['description'] ); ?></p>
								</div>
							</div>
							<div class="envira-am-plugins-footer">
							<div class="envira-am-plugins-status">Status:&nbsp;<span>Inactive</span></div>
							<div class="envira-am-plugins-install-wrap">
							<span class="spinner envira-am-plugins-spinner"></span>

							<?php if ( isset( $plugin['basename'] ) ) : ?>
							<a href="#" target="_blank" class="button envira-button-dark envira-am-plugins-activate" data-url="<?php echo esc_url( $plugin['url'] ); ?>" data-basename="<?php echo esc_attr( $plugin['basename'] ); ?>">Activate</a>
							<?php else : ?>
								<a href="<?php echo esc_url( $plugin['url'] ); ?>" target="_blank" class="button envira-button-dark envira-am-plugins-activate" data-url="<?php echo esc_url( $plugin['url'] ); ?>">Activate</a>
							<?php endif; ?>
						</div>
				</div>
						</div>
				<?php
			}
		}
	}
}
