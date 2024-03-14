<?php

namespace BetterLinks\Admin;

use BetterLinks\Admin\WPDev\PluginUsageTracker;
use Exception;
use PriyoMukul\WPNotice\Notices;
use PriyoMukul\WPNotice\Utils\CacheBank;
use PriyoMukul\WPNotice\Utils\NoticeRemover;

class Notice {
	/**
	 * @var CacheBank
	 */
	private static $cache_bank;

	/**
	 * @var PluginUsageTracker
	 */
	private $opt_in_tracker;

	const ASSET_URL = BETTERLINKS_ASSETS_URI;

	public function __construct() {
		$this->usage_tracker();

		self::$cache_bank = CacheBank::get_instance();
		try {
			$this->notices();
		} catch ( Exception $e ) {
			unset( $e );
		}
				
		add_action( 'in_admin_header', [ $this, 'remove_admin_notice' ] );
	}

	public function remove_admin_notice() {
		$current_screen = get_current_screen();
		$dashboard_notice = get_option('betterlinks_dashboard_notice');
		if( 0 === strpos($current_screen->id, "toplevel_page_betterlinks") || 0 === strpos($current_screen->id, "betterlinks_page_") ){
			remove_all_actions( 'user_admin_notices' );
			remove_all_actions( 'admin_notices' );

			if( BETTERLINKS_MENU_NOTICE !== $dashboard_notice ) {
				add_action('admin_notices', array($this, 'new_feature_notice'));
			}
			
            // To showing notice in BetterLinks page
			add_action( 'admin_notices', function () {
				do_action('btl_admin_notices');
				Notice\PrettyLinks::init();
				Notice\Simple301::init();
				Notice\ThirstyAffiliates::init();
				// Remove OLD notice from 1.0.0 (if other WPDeveloper plugin has notice)
				NoticeRemover::get_instance( '1.0.0' );
			} );
		}
	}

	public function new_feature_notice() {
		printf(
	"<div class='notice notice-success is-dismissible btl-dashboard-notice' id='btl-dashboard-notice'>
				<p>
				%s
				<a target='_blank' href='https://betterlinks.io/docs/split-test-using-dynamic-redirects/'>
					%s
				</a>
				%s
				<a target='_blank' href='https://betterlinks.io/changelog/'>
					%s
				</a>
				%s
				</p>
		</div>", 
		__('📣 NEW: BetterLinks Pro 1.8.3 is here, with Improved ', 'betterlinks'),
		__('Split Test', 'betterlinks'),
		__(' & more! Check out the ', 'betterlinks'),
		__('Changelog', 'betterlinks'),
		__(' for more details 🎉', 'betterlinks')
	);
	}
	public function usage_tracker() {
		$this->opt_in_tracker = PluginUsageTracker::get_instance( BETTERLINKS_PLUGIN_FILE, [
			'opt_in'       => true,
			'goodbye_form' => true,
			'item_id'      => '720bbe6537bffcb73f37',
		] );
		$this->opt_in_tracker->set_notice_options( array(
			'notice'       => __( 'Want to help make <strong>BetterLinks</strong> even more awesome? Be the first to get access to <strong>BetterLinks PRO</strong> with a huge <strong>50% Early Bird Discount</strong> if you allow us to track the non-sensitive usage data.', 'betterlinks' ),
			'extra_notice' => __( 'We collect non-sensitive diagnostic data and plugin usage information. Your site URL, WordPress & PHP version, plugins & themes and email address to send you the discount coupon. This data lets us make sure this plugin always stays compatible with the most popular plugins and themes. No spam, I promise.', 'betterlinks' ),
		) );
		$this->opt_in_tracker->init();
	}

	/**
	 * @throws Exception
	 */
	public function notices() {

		$notices = new Notices( [
			'id'             => 'betterlinks',
			'storage_key'    => 'notices',
			'lifetime'       => 3,
			'stylesheet_url' => self::ASSET_URL . 'css/betterlinks-admin-notice.css',
			'styles' => self::ASSET_URL . 'css/betterlinks-admin-notice.css',
			'priority'       => 5
		] );

		global $betterlinks;
		$current_user = wp_get_current_user();
		$total_links  = ( is_array( $betterlinks ) && isset( $betterlinks['links'] ) ? count( $betterlinks['links'] ) : 0 );

		$review_notice = sprintf(
			'%s, %s! %s',
			__( 'Howdy', 'betterlinks' ),
			$current_user->user_login,
			sprintf(
				__( '👋 You have created %d Shortened URLs so far 🎉 If you are enjoying using BetterLinks, feel free to leave a 5* Review on the WordPress Forum.', 'betterlinks' ),
				$total_links
			)
		);

		$_review_notice = [
			'thumbnail' => self::ASSET_URL . 'images/logo-large.svg',
			'html'      => '<p>' . $review_notice . '</p>',
			'links'     => [
				'later'            => array(
					'link'       => 'https://wordpress.org/plugins/betterlinks/#reviews',
					'target'     => '_blank',
					'label'      => __( 'Ok, you deserve it!', 'betterlinks' ),
					'icon_class' => 'dashicons dashicons-external',
				),
				'allready'         => array(
					'label'      => __( 'I already did', 'betterlinks' ),
					'icon_class' => 'dashicons dashicons-smiley',
					'attributes' => [
						'data-dismiss' => true
					],
				),
				'maybe_later'      => array(
					'label'      => __( 'Maybe Later', 'betterlinks' ),
					'icon_class' => 'dashicons dashicons-calendar-alt',
					'attributes' => [
						'data-later' => true
					],
				),
				'support'          => array(
					'link'       => 'https://wpdeveloper.com/support',
					'label'      => __( 'I need help', 'betterlinks' ),
					'icon_class' => 'dashicons dashicons-sos',
				),
				'never_show_again' => array(
					'label'      => __( 'Never show again', 'betterlinks' ),
					'icon_class' => 'dashicons dashicons-dismiss',
					'attributes' => [
						'data-dismiss' => true
					],
				)
			]
		];

		$notices->add(
			'review',
			$_review_notice,
			[
				'start'       => $notices->strtotime( '+20 day' ),
				'recurrence'  => 30,
				'refresh'     => BETTERLINKS_VERSION,
				'dismissible' => true,
			]
		);

		$notices->add(
			'opt_in',
			[ $this->opt_in_tracker, 'notice' ],
			[
				'classes'     => 'updated put-dismiss-notice',
				'start'       => $notices->strtotime( '+25 day' ),
				'refresh'     => BETTERLINKS_VERSION,
				'dismissible' => true,
				'do_action'   => 'wpdeveloper_notice_clicked_for_betterlinks',
				'display_if'  => ! is_array( $notices->is_installed( 'betterlinks-pro/betterlinks-pro.php' ) )
			]
		);

		$b_message            = '<p style="margin-top: 0; margin-bottom: 10px;">Black Friday Sale: <strong>Supercharge your link management</strong> with advanced features & enjoy up to 40% savings 🔗</p><a class="button button-primary" href="https://wpdeveloper.com/upgrade/betterlinks-bfcm" target="_blank">Upgrade to pro</a> <button data-dismiss="true" class="dismiss-btn button button-link">I don’t want to save money</button>';
		$_black_friday_notice = [
			'thumbnail' => self::ASSET_URL . 'images/full-logo.svg',
			'html'      => $b_message,
		];

		$notices->add(
			'black_friday_notice',
			$_black_friday_notice,
			[
				'start'       => $notices->time(),
				'recurrence'  => false,
				'dismissible' => true,
				'refresh'     => BETTERLINKS_VERSION,
				"expire"      => strtotime( '11:59:59pm 2nd December, 2023' ),
				'display_if'  => ! is_plugin_active( 'betterlinks-pro/betterlinks-pro.php' )
			]
		);

		self::$cache_bank->create_account( $notices );
		self::$cache_bank->calculate_deposits( $notices );
	}

}
