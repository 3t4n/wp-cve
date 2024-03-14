<?php

namespace CTXFeed\V5\Common;


use CTXFeed\V5\Notice\Notices;

/**
 * Class DisplayNotices
 *
 * @package    CTXFeed
 * @subpackage CTXFeed\V5\Common
 */
class DisplayNotices {

	public function __construct() {
		add_action( 'admin_notices', [ $this, 'display_admin_notice' ] );
	}

	public static function init() {
		if ( isset( $_REQUEST['wpf_notice_code'] ) ) {
			$code    = sanitize_text_field( $_REQUEST['wpf_notice_code'] );
			$notices = new Notices();
			$options = [
				'dismissible'   => true,
				'type'          => 'error',
				'capability'    => 'manage_woocommerce',
				'option_prefix' => 'ctx_feed_',
			];

			switch ( $code ) {
				case 'log_file_not_found':
					$title           = esc_html__( 'Log File Not Found.', 'woo-feed' );
					$content         = esc_html__( 'Log file not fount. Please enable log from CTX Feed > Setting or Regenerate the Feed.', 'woo-feed' );
					$options['type'] = 'warning';
					$notices->add( uniqid( 'log_file_not_found', true ), $title, $content, $options );
					break;
				case 'feed_download_failed':
					$title   = esc_html__( 'Feed Download Failed.', 'woo-feed' );
					$content = esc_html__( 'Failed to download feed file. Please regenerate the feed and try again.', 'woo-feed' );
					$notices->add( uniqid( 'feed_download_failed', true ), $title, $content, $options );
					break;
				default:
					break;

				//TODO  add notice for feed import and export
			}

			$notices->boot();
		}
	}

	public function display_admin_notice() {

	}
}
