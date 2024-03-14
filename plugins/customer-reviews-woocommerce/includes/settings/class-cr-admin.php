<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CR_Admin' ) ) :

	class CR_Admin {

		private $milestones;
		public $ver;

		public function __construct() {
			$this->ver = Ivole::CR_VERSION;
			if ( is_admin() && current_user_can('manage_options') ) {
				$this->milestones = new CR_Milestones();
				add_action( 'wp_ajax_ivole_dismiss_activated_notice', array( $this, 'dismiss_activated_notice' ) );
				add_action( 'wp_ajax_ivole_dismiss_updated_notice', array( $this, 'dismiss_updated_notice' ) );
				add_action( 'wp_ajax_ivole_dismiss_reviews_notice_later', array( $this, 'dismiss_reviews_notice_later' ) );
				add_action( 'wp_ajax_ivole_dismiss_reviews_notice_never', array( $this, 'dismiss_reviews_notice_never' ) );
				add_action( 'current_screen', array( $this, 'check_notices' ) );
				add_action( 'admin_footer', array( $this, 'output_notice_scripts' ) );
			}
		}

		public function output_notice_scripts() {
			?>
			<script type="text/javascript" >
				jQuery(document).ready(function($) {
					jQuery(document).on( 'click', '.ivole-activated .notice-dismiss', function() {
						jQuery.ajax({
							url: ajaxurl,
							data: {
								action: 'ivole_dismiss_activated_notice'
							}
						})
					});

					jQuery(document).on( 'click', '.ivole-updated .notice-dismiss', function() {
						jQuery.ajax({
							url: ajaxurl,
							data: {
								action: 'ivole_dismiss_updated_notice'
							}
						})
					});

					jQuery(document).on( 'click', '.ivole-reviews-milestone .notice-dismiss', function() {
						jQuery.ajax({
							url: ajaxurl,
							data: {
								action: 'ivole_dismiss_reviews_notice_later'
							}
						})
					});

					jQuery(document).on( 'click', '.ivole-reviews-milestone a.ivole-reviews-milestone-later', function() {
						var notice_container = jQuery('.notice.notice-info.is-dismissible.ivole-reviews-milestone');

						if (  notice_container.length > 0 ) {
							notice_container.remove();
						}

						jQuery.ajax({
							url: ajaxurl,
							data: {
								action: 'ivole_dismiss_reviews_notice_later'
							}
						})

						return false;
					});

					jQuery(document).on( 'click', '.ivole-reviews-milestone a.ivole-reviews-milestone-never', function() {
						var notice_container = jQuery('.notice.notice-info.is-dismissible.ivole-reviews-milestone');

						if ( notice_container.length > 0 ) {
							notice_container.remove();
						}

						jQuery.ajax({
							url: ajaxurl,
							data: {
								action: 'ivole_dismiss_reviews_notice_never'
							}
						})

						return false;
					});
				});
			</script>
			<?php
		}

		/**
		 * Function to dismiss activation notice in admin area
		 */
		public function dismiss_activated_notice() {
			update_option( 'ivole_activation_notice', 0 );
		}

		/**
		 * Function to dismiss update notice in admin area
		 */
		public function dismiss_updated_notice() {
			update_option( 'ivole_version', $this->ver );
		}

		/**
		 * Function to dismiss review milestone notice in admin area until the next milestone
		 */
		public function dismiss_reviews_notice_later() {
			$this->milestones->increase_milestone();
		}

		/**
		 * Function to dismiss review milestone notice in admin area forever
		 */
		public function dismiss_reviews_notice_never() {
			$this->milestones->milestone_never();
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_install() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-activated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-settings' );
				$message = sprintf( __( '<strong>Customer Reviews for WooCommerce</strong> plugin has been activated. Please go to the plugin\'s <a href="%s">settings</a> and configure this plugin to start receiving more authentic reviews!', 'customer-reviews-woocommerce' ), $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_update() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-settings' );
				$message = sprintf( __( '<strong>Customer Reviews for WooCommerce</strong> plugin has been updated. This is a big update that makes submission of reviews easier and quicker for your customers. It means that you will receive more customer reviews but first we recommend you to verify <a href="%s">plugin settings</a> by sending several test emails to make sure that everything works fine.', 'customer-reviews-woocommerce' ), $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_update2() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-settings&tab=review_extensions' );
				$message = sprintf( __( '<strong>Customer Reviews for WooCommerce</strong> plugin has been updated. This update adds a new feature that enables visitors to vote for reviews. If you would like to try this feature, you should enable it in the <a href="%s">plugin settings</a>.', 'customer-reviews-woocommerce' ), $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_update3() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-settings&tab=review_reminder' );
				$message = sprintf( __( '<strong>Customer Reviews for WooCommerce</strong> plugin has been updated. This update adds a new feature (Shop Rating) that enables customers to rate your shop in general (website, customer service, and delivery). If you would like to try this feature, you should enable it in the <a href="%s">plugin settings</a>.', 'customer-reviews-woocommerce' ), $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_update4() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-product-feed' );
				$message = sprintf( '<strong>Customer Reviews for WooCommerce</strong> plugin has been updated. This update includes new features: <ul><li>* An option to add fields for product identifiers (GTIN, MPN, Brand) to WooCommerce products and include them in the structured data markup. Google now expects these identifiers to be populated in the structured data and their testing tool displays errors, if they are missing.</li><li>* Generation of Product and Product Reviews XML feeds for Google Shopping.</li><li>* An option to map WooCommerce product categories to Google Shopping product taxonomy. If Trust Badges are enabled, this mapping will be used to tag pages with verified copies of reviews based on the standard product taxonomy.</li></ul>If you would like to try the new features, they can be found <a href="%s">here</a>.', $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_update5() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-settings&tab=referrals' );
				$message = sprintf( '<p><strong>Customer Reviews for WooCommerce</strong> plugin has been updated. This update introduces a possibility to track orders placed by customers who were referred by your previous customers. Referral marketing is one of the most cost-effective ways to acquire new customers. It is based on the idea that your current customers will spread the word about your store and bring in (or refer) new customers. The problem is that it is not easy for them to do so. We help your customers to spread the word by showing their public reviews to other customers at <a href="https://www.cusrev.com" target="_blank">cusrev.com</a>.</p><p>If you would like to track referrals, make sure to enable this option in the plugin\'s <a href="%s">settings</a>.</p>', $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show activation notice in admin area
		 */
		public function admin_notice_update6() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$message = '<p><strong>Customer Reviews for WooCommerce</strong> plugin is optimizing how votes for reviews are stored in the database. This message will disappear as soon as the optimization is completed.</p>';
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show media count update notice in admin area
		 */
		public function admin_notice_update7() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$message = '<p><strong>Customer Reviews for WooCommerce</strong> plugin is optimizing how information about media files uploaded with reviews is stored in the database. This message will disappear as soon as the optimization is completed.</p>';
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show media count update notice in admin area
		 */
		public function admin_notice_update8() {
			if ( current_user_can( 'manage_options' ) ) {
				$class = 'notice notice-info is-dismissible ivole-updated';
				$settings_url = admin_url( 'admin.php?page=cr-reviews-settings&tab=forms' );
				$message = sprintf( '<strong>Customer Reviews for WooCommerce</strong> plugin has been updated. This update includes a new setting \'Review Permissions\' that determines whether a user is eligible to submit a review via on-site review forms. Please check this setting <a href="%s">here</a> and verify that it is configured according to your requirements.', $settings_url );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}

		/**
		 * Function to show milestone in collected reviews notice in admin area
		 */
		public function admin_notice_reviews() {
			if ( current_user_can( 'manage_options' ) ) {
				$reviews_count = $this->milestones->count_reviews();
				$class = 'notice notice-info is-dismissible ivole-reviews-milestone';
				$message = sprintf( '<p style="font-weight:bold;color:#008000">Hey, I noticed you have collected %d reviews with "Customer Reviews for WooCommerce" – that’s awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.<br><span style="font-style:italic;">~ John Brown</span></p><ul style="list-style-type:disc;list-style-position:inside;font-weight:bold;"><li><a href="https://wordpress.org/support/plugin/customer-reviews-woocommerce/reviews/#new-post" target="_blank">OK, you deserve it</a></li><li><a href="#" class="ivole-reviews-milestone-later">Nope, maybe later</a></li><li><a href="#" class="ivole-reviews-milestone-never">I already did</a></li></ul>', $reviews_count );
				printf( '<div class="%1$s"><p>%2$s</p></div>', esc_attr( $class ), $message );
			}
		}


		public static function cr_get_field_description( $value ) {
			// a copy of WC_Admin_Settings::get_field_description() because this function is not included with early versions of WooCommerce
			$description  = '';
			$tooltip_html = '';
			$default = '';
			$variables = array();

			if ( true === $value['desc_tip'] ) {
				$tooltip_html = $value['desc'];
			} elseif ( ! empty( $value['desc_tip'] ) ) {
				$description  = $value['desc'];
				$tooltip_html = $value['desc_tip'];
			} elseif ( ! empty( $value['desc'] ) ) {
				$description  = $value['desc'];
			}

			if ( $description && in_array( $value['type'], array( 'textarea', 'radio' ) ) ) {
				$description = '<p style="margin-top:0">' . wp_kses_post( $description ) . '</p>';
			} elseif ( $description && in_array( $value['type'], array( 'checkbox', 'nobranding', 'verified_badge', 'geolocation' ) ) ) {
				$description = wp_kses_post( $description );
			} elseif ( $description ) {
				$description = '<span class="description">' . wp_kses_post( $description ) . '</span>';
			}

			if ( $tooltip_html && in_array( $value['type'], array( 'checkbox' ) ) ) {
				$tooltip_html = '<p class="description">' . $tooltip_html . '</p>';
			} elseif ( $tooltip_html ) {
				$tooltip_html = self::ivole_wc_help_tip( $tooltip_html );
			}

			if( isset( $value['default'] ) && $value['default'] ) {
				$default = $value['default'];
			}

			if( isset( $value['variables'] ) && is_array( $value['variables'] ) ) {
				$variables = $value['variables'];
			}

			return array(
				'description'  => $description,
				'tooltip_html' => $tooltip_html,
				'default' => $default,
				'variables' => $variables
			);
		}

		public static function ivole_wc_help_tip( $tip, $allow_html = false ) {
			if ( $allow_html ) {
				$tip = wc_sanitize_tooltip( $tip );
			} else {
				$tip = esc_attr( $tip );
			}

			return '<span class="woocommerce-help-tip" data-tip="' . $tip . '"></span>';
		}

		public function check_notices() {
			$no_notices = true;
			if( 1 == get_option( 'ivole_activation_notice', 0 ) ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_install' ) );
				update_option( 'ivole_version', $this->ver );
				$no_notices = false;
			} else {
				$version = get_option( 'ivole_version', 0 );

				if( 0 === $version ) {
					update_option( 'ivole_version', $this->ver );
				} else {
					if ( version_compare( $version, '3.0', '<' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notice_update' ) );
						$no_notices = false;
					} elseif ( version_compare( $version, '3.17', '<' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notice_update2' ) );
						$no_notices = false;
					} elseif ( version_compare( $version, '3.45', '<' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notice_update3' ) );
						$no_notices = false;
					} elseif ( version_compare( $version, '3.100', '<' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notice_update4' ) );
						$no_notices = false;
					} elseif ( version_compare( $version, '3.112', '<' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notice_update5' ) );
						$no_notices = false;
					} elseif ( version_compare( $version, '3.116', '<' ) ) {
						if( CR_Ajax_Reviews::update_reviews_meta() ) {
							update_option( 'ivole_version', '4.38');
						}
						// notice about updating review votes meta
						if( get_option( 'ivole_update_votes_meta', false ) ) {
							add_action( 'admin_notices', array( $this, 'admin_notice_update6' ) );
						}
					} elseif ( version_compare( $version, '4.39', '<' ) ) {
						if( CR_Ajax_Reviews::update_reviews_meta2() ) {
							update_option( 'ivole_version', $this->ver );
						}
						// notice about updating review media count meta
						if( get_option( 'ivole_update_media_meta', false ) ) {
							add_action( 'admin_notices', array( $this, 'admin_notice_update7' ) );
						}
					} elseif ( version_compare( $version, '5.0.0', '<' ) ) {
						update_option( 'ivole_verified_reviews', 'yes', false );
						update_option( 'ivole_mailer_review_reminder', 'cr', false );
						update_option( 'ivole_version', $this->ver );
					} elseif ( version_compare( $version, '5.39.0', '<' ) ) {
						add_action( 'admin_notices', array( $this, 'admin_notice_update8' ) );
						$no_notices = false;
					} elseif ( version_compare( $version, '5.43.1', '<' ) ) {
						$log = new CR_Reminders_Log();
						$log->check_create_table();
						update_option( 'ivole_version', $this->ver );
					} else {
						update_option( 'ivole_version', $this->ver );
					}
				}
			}

			//count reviews
			if ( $no_notices && $this->milestones->show_notices() ) {
				add_action( 'admin_notices', array( $this, 'admin_notice_reviews' ) );
			}
		}
	}

endif;
