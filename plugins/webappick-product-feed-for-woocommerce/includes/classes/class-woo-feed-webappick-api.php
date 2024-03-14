<?php
/**
 * WooCommerce Product Feed Plugin Uses Tracker
 * Uses Webappick Insights for tracking
 * @since 3.1.41
 * @version 1.0.2
 */
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

if ( ! class_exists( 'WooFeedWebAppickAPI' ) ) {
	/**
	 * Class WooFeedWebAppickAPI
	 */
	final class WooFeedWebAppickAPI {

		/**
		 * Singleton instance
		 * @var WooFeedWebAppickAPI
		 */
		protected static $instance;

		/**
		 * @var CTXFeed\AppServices\Client
		 */
		protected $client = null;

		/**
		 * @var CTXFeed\AppServices\Insights
		 */
		protected $insights = null;

		/**
		 * Promotions Class Instance
		 * @var CTXFeed\AppServices\Promotions
		 */
		public $promotion = null;

		/**
		 * Plugin License Manager
		 * @var CTXFeed\AppServices\License
		 */
		protected $license = null;

		/**
		 * Plugin Updater
		 * @var CTXFeed\AppServices\Updater
		 */
		protected $updater = null;

		/**
		 * Initialize
		 * @return WooFeedWebAppickAPI
		 */
		public static function getInstance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
		}

		/**
		 * Class constructor
		 *
		 * @return void
		 * @since 1.0.0
		 *
		 */
		private function __construct() {
			if ( ! class_exists( 'CTXFeed\AppServices\Client' ) ) {
				/** @noinspection PhpIncludeInspection */
				require_once WOO_FEED_FREE_LIBS_PATH . 'WebAppick/AppServices/Client.php';
			}
			// Load Client
			$this->client = new CTXFeed\AppServices\Client( '4e68acba-cbdc-476b-b4bf-eab176ac6a16', 'CTX Feed', WOO_FEED_FREE_FILE );
			// Load
			$this->insights  = $this->client->insights(); // Plugin Insights
			$this->promotion = $this->client->promotions(); // Promo offers

			// Setup
			$this->promotion->set_source( 'https://api.bitbucket.org/2.0/snippets/woofeed/RLbyop/files/woo-feed-notice.json' );

			// Initialize
			$this->insightInit();
			$this->promotion->init();

			// Housekeeping.
			add_action( 'admin_menu', array( $this, 'premium_features' ), 999 );

			//show admin notices when woocommerce is activated
			if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
				add_action( 'admin_notices', array( $this, 'woo_feed_review_notice' ) );
				add_action( 'wp_ajax_woo_feed_save_review_notice', array( $this, 'woo_feed_save_review_notice' ) );
				add_action( 'wp_ajax_woo_feed_hide_notice', array( $this, 'woo_feed_hide_notice' ) );
			}

		}

		/**
		 * Cloning is forbidden.
		 * @since 1.0.2
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, esc_html__( 'Cloning is forbidden.', 'woo-feed' ), '1.0.2' );
		}

		/**
		 * Initialize Insights
		 * @return void
		 */
		private function insightInit() {
			global $wpdb;
			$result = $wpdb->get_results( $wpdb->prepare("SELECT * FROM $wpdb->options WHERE option_name LIKE %s;", "wf_feed_%"), 'ARRAY_A' ); // phpcs:ignore
			if ( ! is_array( $result ) ) {
				$result = array();
			}
			$catCount = wp_count_terms(
				array(
					'taxonomy'   => 'product_cat',
					'hide_empty' => false,
					'parent'     => 0,
				)
			);
			if ( is_wp_error( $catCount ) ) {
				$catCount = 0;
			}
			/**
			 * @TODO count products by type
			 * @see wc_get_product_types();
			 */
			// update_option( 'woo_feed_review_notice', $value );
			//				$notices = [ 'rp-wcdpd', 'wpml', 'rating', 'product_limit' ];
			//
			$hidden_notices = array();
			foreach ( array( 'rp-wcdpd', 'wpml', 'rating', 'product_limit' ) as $which ) {
				$hidden_notices[ $which ] = (int) get_option( sprintf( 'woo_feed_%s_notice_hidden', $which ), 0 );
			}
			$tracker_extra = array(
				'products'        => $this->insights->get_post_count( 'product' ),
				'variations'      => $this->insights->get_post_count( 'product_variation' ),
				'batch_limit'     => get_option( 'woo_feed_per_batch' ),
				'feed_configs'    => wp_json_encode( $result ),
				'product_cat_num' => $catCount,
				'review_notice'   => wp_json_encode( get_option( 'woo_feed_review_notice', array() ) ),
				'hidden_notices'  => $hidden_notices,
			);
			$this->insights->add_extra( $tracker_extra );
			$projectSlug = $this->client->getSlug();
			add_filter( $projectSlug . '_what_tracked', array( $this, 'data_we_collect' ), 10, 1 );
			add_filter(
				"WebAppick_{$projectSlug}_Support_Ticket_Recipient_Email",
				function() {
					return 'sales@webappick.com';
				},
				10
			);
			add_filter( "WebAppick_{$projectSlug}_Support_Ticket_Email_Template", array( $this, 'supportTicketTemplate' ), 10 );
			add_filter( "WebAppick_{$projectSlug}_Support_Request_Ajax_Success_Response", array( $this, 'supportResponse' ), 10 );
			add_filter( "WebAppick_{$projectSlug}_Support_Request_Ajax_Error_Response", array( $this, 'supportErrorResponse' ), 10 );
			add_filter(
				"WebAppick_{$projectSlug}_Support_Page_URL",
				function() {
					return 'https://wordpress.org/support/plugin/webappick-product-feed-for-woocommerce/#new-topic-0';
				},
				10
			);
			$this->insights->init();
		}

		/**
		 * Generate Support Ticket Email Template
		 * @return string
		 */
		public function supportTicketTemplate() {
			// dynamic variable format __INPUT_NAME__
			/** @noinspection HtmlUnknownTarget */
			$template  = '<div style="margin: 10px auto;"><p>Website : <a href="__WEBSITE__">__WEBSITE__</a><br>Plugin : %s (v.%s)</p></div>';
			$template  = sprintf( $template, $this->client->getName(), $this->client->getProjectVersion() );
			$template .= '<div style="margin: 10px auto;"><hr></div>';
			$template .= '<div style="margin: 10px auto;"><h3>__SUBJECT__</h3></div>';
			$template .= '<div style="margin: 10px auto;">__MESSAGE__</div>';
			$template .= '<div style="margin: 10px auto;"><hr></div>';
			$template .= sprintf(
				'<div style="margin: 50px auto 10px auto;"><p style="font-size: 12px;color: #009688">%s</p></div>',
				'Message Processed With WebAppick Service Library (v.' . $this->client->getClientVersion() . ')'
			);
			return $template;
		}

		/**
		 * Generate Support Ticket Ajax Response
		 * @return string
		 */
		public function supportResponse() {
			$response        = '';
			$response       .= sprintf( '<h3>%s</h3>', esc_html__( 'Thank you -- Support Ticket Submitted.', 'woo-feed' ) );
			$ticketSubmitted = esc_html__( 'Your ticket has been successfully submitted.', 'woo-feed' );
			$twenty4Hours    = sprintf( '<strong>%s</strong>', esc_html__( '24 hours', 'woo-feed' ) );
			/* translators: %s: Approx. time to response after ticket submission. */
			$notification = sprintf( esc_html__( 'You will receive an email notification from "support@webappick.com" in your inbox within %s.', 'woo-feed' ), $twenty4Hours );
			$followUp     = esc_html__( 'Please Follow the email and WebAppick Support Team will get back with you shortly.', 'woo-feed' );
			$response    .= sprintf( '<p>%s %s %s</p>', $ticketSubmitted, $notification, $followUp );
			$docLink      = sprintf( '<a class="button button-primary" href="https://webappick.helpscoutdocs.com/" target="_blank"><span class="dashicons dashicons-media-document" aria-hidden="true"></span> %s</a>', esc_html__( 'Documentation', 'woo-feed' ) );
			$vidLink      = sprintf( '<a class="button button-primary" href="https://youtube.com/playlist?list=PLapCcXJAoEenI-35wc6YnnsAAgoYRxDr7" target="_blank"><span class="dashicons dashicons-video-alt3" aria-hidden="true"></span> %s</a>', esc_html__( 'Video Tutorials', 'woo-feed' ) );
			$response    .= sprintf( '<p>%s %s</p>', $docLink, $vidLink );
			$response    .= '<br><br><br>';
			$toc          = sprintf( '<a href="https://webappick.com/terms-and-conditions/" target="_blank">%s</a>', esc_html__( 'Terms & Conditions', 'woo-feed' ) );
			$pp           = sprintf( '<a href="https://webappick.com/privacy-policy/" target="_blank">%s</a>', esc_html__( 'Privacy Policy', 'woo-feed' ) );
			/* translators: 1: Link to the Trams And Condition Page, 2: Link to the Privacy Policy Page */
			$policy    = sprintf( esc_html__( 'Please read our %1$s and %2$s', 'woo-feed' ), $toc, $pp );
			$response .= sprintf( '<p style="font-size: 12px;">%s</p>', $policy );
			return $response;
		}

		/**
		 * Set Error Response Message For Support Ticket Request
		 * @return string
		 */
		public function supportErrorResponse() {
			return sprintf(
				'<div class="mui-error"><p>%s</p><p>%s</p><br><br><p style="font-size: 12px;">%s</p></div>',
				esc_html__( 'Something Went Wrong. Please Try The Support Ticket Form On Our Website.', 'woo-feed' ),
				sprintf( '<a class="button button-primary woo-feed-btn-bg-gradient-blue" href="https://wordpress.org/support/plugin/webappick-product-feed-for-woocommerce/#new-topic-0" target="_blank">%s</a>', esc_html__( 'Get Support', 'woo-feed' ) ),
				esc_html__( 'Support Ticket form will open in new tab in 5 seconds.', 'woo-feed' )
			);
		}

		/**
		 * Set Data Collection description for the tracker
		 * @param $data
		 *
		 * @return array
		 */
		public function data_we_collect( $data ) {
			$data = array_merge(
				$data,
				array(
					esc_html__( 'Number of products in your site.', 'woo-feed' ),
					esc_html__( 'Number of product categories in your site.', 'woo-feed' ),
					esc_html__( 'Feed Configuration.', 'woo-feed' ),
					esc_html__( 'Site name, language and url.', 'woo-feed' ),
					esc_html__( 'Number of active and inactive plugins.', 'woo-feed' ),
					esc_html__( 'Your name and email address.', 'woo-feed' ),
				)
			);
			return $data;
		}

		/**
		 * Get Tracker Data Collection Description Array
		 * @return array
		 */
		public function get_data_collection_description() {
			return $this->insights->get_data_collection_description();
		}

		/**
		 * Update Tracker OptIn
		 *
		 * @param bool $override    optional. ignore last send datetime settings if true.
		 *                           @see Insights::send_tracking_data()
		 * @return void
		 */
		public function trackerOptIn( $override = false ) {
			$this->insights->optIn( $override );
		}

		/**
		 * Update Tracker OptOut
		 * @return void
		 */
		public function trackerOptOut() {
			$this->insights->optOut();
		}

		/**
		 * Check if tracking is enable
		 * @return bool
		 */
		public function is_tracking_allowed() {
			return $this->insights->is_tracking_allowed();
		}

		public function premium_features() {
			add_submenu_page( 'webappick-manage-feeds', esc_html__( 'Premium', 'woo-feed' ), '<span class="woo-feed-premium">' . esc_html__( 'Premium', 'woo-feed' ) . '</span>', 'manage_woocommerce', 'webappick-feed-pro-vs-free', array( $this, 'woo_feed_pro_vs_free' ) );
			add_action( 'admin_head', array( $this, 'remove_admin_notices' ), 9999 );
		}

		/**
		 * Render Premium Feature Comparison Page
		 * @return void
		 */
		public function woo_feed_pro_vs_free() {
			/** @define "WOO_FEED_FREE_ADMIN_PATH''./../../admin/" */ // phpcs:ignore
			require WOO_FEED_FREE_ADMIN_PATH . 'partials/woo-feed-pro-vs-free.php';
		}

		/**
		 * Remove Admin Notice in pro features page.
		 * @global string $pagenow
		 * @global string $plugin_page
		 * @return void
		 */
		public function remove_admin_notices() {
			global $pagenow, $plugin_page;
			if ( 'admin.php' == $pagenow && 'webappick-feed-pro-vs-free' == $plugin_page ) {
				remove_all_actions( 'admin_notices' );
			}
		}

		/**
		 * Show Review And Compatibility Notice For Pro Features
		 * @global string $plugin_page
		 * @return void
		 */
		public function woo_feed_review_notice() {
			global $plugin_page;
			$user_id    = get_current_user_id();
			$nonce      = wp_create_nonce( 'woo_feed_pro_notice_nonce' );
			$pluginName = sprintf( '<b>%s</b>', esc_html__( 'CTX Feed', 'woo-feed' ) );
			$proLink    = sprintf( '<b><a href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=freePlugin&utm_medium=go_premium&utm_campaign=free_to_pro&utm_term=wooFeed" target="_blank">%s</a></b>', esc_html__( 'Premium', 'woo-feed' ) );
			$has_notice = false;

			//submit review notice form data
			$this->woo_feed_save_review_notice();

			//get review notice data
			$next_timestamp          = get_option( 'woo_feed_review_notice_next_show_time' );
			$review_notice_dismissed = get_user_meta( $user_id, 'woo_feed_review_notice_dismissed', true );

			if ( ! empty( $next_timestamp ) ) {
				if ( ( time() > $next_timestamp ) ) {
					$show_notice = true;
				} else {
					$show_notice = false;
				}
			} else {
				if ( isset( $review_notice_dismissed ) && ! empty( $review_notice_dismissed ) ) {
					$show_notice = false;
				} else {
					$show_notice = true;
				}
			}

			// Review Notice.
			if ( $show_notice ) {
				$has_notice = true;
				?>
				<div class="woo-feed-notice notice notice-info" style="line-height:1.5;" data-which="rating" data-nonce="<?php echo esc_attr( $nonce ); ?>">
					<form method="post">
						<p>
						<?php
							printf(
							/* translators: 1: plugin name,2: Slightly Smiling Face (Emoji), 3: line break 'br' tag */
								esc_html__( '%3$s %2$s We have spent countless hours developing this free plugin for you, and we would really appreciate it if you dropped us a quick rating. Your opinion matters a lot to us.%4$s It helps us to get better. Thanks for using %1$s.', 'woo-feed' ),
								$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
								'<span style="font-size: 16px;">🙂</span>',
								'<div class="woo-feed-review-notice-logo"></div>',
								'<br>'
							);
						?>
							</p>
						<p>
							<button type="submit" name="woo_feed_review_notice_btn_later" value="later" class="button button-secondary"><?php esc_html_e( 'Remind me later', 'woo-feed' ); ?></button>
							<button type="submit" name="woo_feed_review_notice_btn_never" value="never" class="button button-secondary"><?php esc_html_e( 'I would not', 'woo-feed' ); ?></button>
							<button type="submit" name="woo_feed_review_notice_btn_done" value="done" class="button button-secondary"><?php esc_html_e( 'I already did!', 'woo-feed' ); ?></button>
							<button type="submit" name="woo_feed_review_notice_btn_given" value="given" class="button button-primary woo-feed-btn-bg-gradient-blue"><?php esc_html_e( 'Review Here', 'woo-feed' ); ?></button>
						</p>

						<button type="submit" class="woo-feed-review-btn-dismiss" name="woo_feed_review_notice_submit"><span><?php _e( 'Dismiss', 'woo-feed' ); ?></span></button>
					</form>
				</div>
				<?php
			}

			// Compatibility Notices.
			if ( class_exists( 'SitePress' ) && false === get_option( 'woo_feed_wpml_notice_hidden' ) ) {
				$has_notice = true;
				?>
				<div class="woo-feed-notice notice notice-success is-dismissible" data-which="wpml">
					<p>
					<?php
						printf(
						/* translators: 1: This plugin name, 2: Pro version purchase link */
							esc_html__( 'You are awesome for using %1$s. Using the %2$s version you can make multilingual feed for your WPML languages.', 'woo-feed' ),
							$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							$proLink // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						);
					?>
						</p>
				</div>
				<?php
			}
			if ( class_exists( 'RP_WCDPD', false ) && false === get_option( 'woo_feed_rp-wcdpd_notice_hidden' ) ) {
				$has_notice = true;
				?>
				<div class="woo-feed-notice notice notice-success is-dismissible" data-which="rp-wcdpd">
					<p>
					<?php
						printf(
						/* translators: 1: This plugin Name, 2: Incompatible plugin name, 3: Pro version purchase link */
							esc_html__( '%1$s isn\'t fully compatible with %2$s. Get the %3$s version for full support.', 'woo-feed' ),
							$pluginName, // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							'<b>' . esc_html__( 'WooCommerce Dynamic Pricing & Discounts', 'woo-feed' ) . '</b>',
							$proLink // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						);
					?>
						</p>
				</div>
				<?php
			}
			if ( true === $has_notice ) {
				add_action(
					'admin_print_footer_scripts',
					function() use ( $nonce ) {
						?>
					<script>
						(function($){
							"use strict";
							$(document)
								.on('click', '.woo-feed-notice a.button', function (e) {
									e.preventDefault();
									// noinspection ES6ConvertVarToLetConst
									var self = $(this), notice = self.attr('data-response');
									if ( 'given' === notice ) {
										window.open('https://wordpress.org/support/plugin/webappick-product-feed-for-woocommerce/reviews/?rate=5#new-post', '_blank');
									}
									self.closest(".woo-feed-notice").slideUp( 200, 'linear' );
									wp.ajax.post( 'woo_feed_save_review_notice', { _ajax_nonce: '<?php echo esc_attr( $nonce ); ?>', notice: notice } );
								})
								.on('click', '.woo-feed-notice .notice-dismiss', function (e) {
									e.preventDefault();
									// noinspection ES6ConvertVarToLetConst
									var self = $(this), feed_notice = self.closest('.woo-feed-notice'), which = feed_notice.attr('data-which');
									wp.ajax.post( 'woo_feed_hide_notice', { _wpnonce: '<?php echo esc_attr( $nonce ); ?>', which: which } );
								});
						})(jQuery)
					</script>
						<?php
					},
					99
				);
			}
		}

		/**
		 * Show Review request admin notice
		 */
		public function woo_feed_save_review_notice() {
			$user_id = get_current_user_id();

			$woo_feed_review_notice_submit    = isset( $_POST['woo_feed_review_notice_submit'] ) ? 1 : '';
			$woo_feed_review_notice_btn_given = sanitize_text_field( isset( $_POST['woo_feed_review_notice_btn_given'] ) ? $_POST['woo_feed_review_notice_btn_given'] : '' );
			$woo_feed_review_notice_btn_never = sanitize_text_field( isset( $_POST['woo_feed_review_notice_btn_never'] ) ? $_POST['woo_feed_review_notice_btn_never'] : '' );
			$woo_feed_review_notice_btn_done  = sanitize_text_field( isset( $_POST['woo_feed_review_notice_btn_done'] ) ? $_POST['woo_feed_review_notice_btn_done'] : '' );
			$woo_feed_review_notice_btn_later = sanitize_text_field( isset( $_POST['woo_feed_review_notice_btn_later'] ) ? $_POST['woo_feed_review_notice_btn_later'] : '' );

			//submit review notice dismissal
			if ( $woo_feed_review_notice_submit ) {
				add_user_meta( $user_id, 'woo_feed_review_notice_dismissed', true, true );
				update_option( 'woo_feed_review_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 ) );
			}

			//submit review notice form data
			if ( $woo_feed_review_notice_btn_given ) {
				wp_redirect( 'https://wordpress.org/support/plugin/webappick-product-feed-for-woocommerce/reviews/?rate=5#new-post' );
				exit();
			} elseif ( $woo_feed_review_notice_btn_never || $woo_feed_review_notice_btn_done ) {
				$notice = $woo_feed_review_notice_btn_never ? $woo_feed_review_notice_btn_never : $woo_feed_review_notice_btn_done;
				add_user_meta( $user_id, 'woo_feed_review_notice_dismissed', true, true );
				delete_option( 'woo_feed_review_notice_next_show_time' );
			} elseif ( $woo_feed_review_notice_btn_later ) {
				$notice = $woo_feed_review_notice_btn_later;
				update_option( 'woo_feed_review_notice_next_show_time', time() + ( DAY_IN_SECONDS * 30 ) );
			}

			if ( ! empty( $notice ) ) {
				$value = array(
                    'review_notice' => sanitize_text_field($notice), //phpcs:ignore
					'updated_at'    => time(),
				);

				update_option( 'woo_feed_review_notice', $value );
			}

		}

		/**
		 * Ajax Action For Hiding Compatibility Notices
		 */
		public function woo_feed_hide_notice() {
			check_ajax_referer( 'woo_feed_pro_notice_nonce' );
			$notices = array( 'rp-wcdpd', 'wpml', 'rating', 'product_limit' );
			if ( isset( $_REQUEST['which'] ) && ! empty( $_REQUEST['which'] ) && in_array( $_REQUEST['which'], $notices ) ) {
				$which = sanitize_text_field( $_REQUEST['which'] ); //phpcs:ignore
				update_option( sprintf( 'woo_feed_%s_notice_hidden', $which ), '1', false );
				wp_send_json_success( esc_html__( 'Request Successful.', 'woo-feed' ) );
				wp_die();
			}
			wp_send_json_error( esc_html__( 'Invalid Request.', 'woo-feed' ) );
			wp_die();
		}
	}
}
// End of file class-woo-feed-webappick-api.php
