<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'CR_Sender' ) ) :

	class CR_Sender {
		public function __construct() {
			$order_status = get_option( 'ivole_order_status', 'completed' );
			$order_status = 'wc-' === substr( $order_status, 0, 3 ) ? substr( $order_status, 3 ) : $order_status;
			// Triggers for completed orders
			add_action( 'woocommerce_order_status_' . $order_status, array( $this, 'sender_trigger' ), 20, 1 );
			add_action( 'ivole_send_reminder', array( $this, 'sender_action' ), 10, 1 );
			// Trigger for refunded orders
			add_action( 'woocommerce_order_status_refunded', array( $this, 'refund_trigger' ), 20, 1 );
			// Trigger for cancelled orders
			add_action( 'woocommerce_order_status_cancelled', array( $this, 'cancellation_trigger' ), 20, 1 );
		}

		public function sender_trigger( $order_id ) {
			// check if reminders are enabled
			$reminders_enabled = get_option( 'ivole_enable', 'no' );
			if( $reminders_enabled === 'no' ) {
				return;
			}
			if( $order_id ) {
				// compatibility with WooCommerce Subscriptions plugin
				// do not send review reminders for renewal orders of the same subscription
				if( function_exists( 'wcs_order_contains_renewal' ) ) {
					$skip_renewal_order = apply_filters( 'cr_skip_renewal_order', true );
					if( wcs_order_contains_renewal( $order_id ) && $skip_renewal_order ) {
						// this is a renewal order, don't send a review reminder
						return;
					}
				}
				$order = wc_get_order( $order_id );
				// check if the order contains at least one product for which reminders are enabled (if there is filtering by categories)
				$enabled_for = get_option( 'ivole_enable_for', 'all' );
				if( $enabled_for === 'categories' ) {
					$enabled_categories = get_option( 'ivole_enabled_categories', array() );
					$items = $order->get_items();
					$skip = true;
					foreach ( $items as $item_id => $item ) {
						if ( apply_filters( 'woocommerce_order_item_visible', true, $item ) ) {
							$categories = get_the_terms( $item['product_id'], 'product_cat' );
							foreach ( $categories as $category_id => $category ) {
								if( in_array( $category->term_id, $enabled_categories ) ) {
									$skip = false;
									break;
								}
							}
						}
					}
					if( $skip ) {
						// there is no products from enabled categories in the order, skip sending
						//error_log('categories');
						return;
					}
				}
				if( method_exists( $order, 'get_user' ) ) {
					$user = $order->get_user();
					if( isset( $user ) && !empty( $user ) ) {
						if( 'roles' === get_option( 'ivole_enable_for_role', 'all' ) ) {
							$roles = $user->roles;
							$enabled_roles = get_option( 'ivole_enabled_roles', array() );
							$intersection = array_intersect( $enabled_roles, $roles );
							if( count( $intersection ) < 1 ) {
								//the customer does not have roles for which review reminders are enabled
								return;
							}
						}
					} else {
						if( 'no' === get_option( 'ivole_enable_for_guests', 'yes' ) ) {
							//review reminders are disabled for guests
							return;
						}
					}
				}
				$consent = $order->get_meta( '_ivole_cr_consent', true );
				if( 'no' === $consent ) {
					// skip sending because no customer consent was received
					$order->add_order_note( __( 'CR: a review reminder was not scheduled because the customer did not provide their consent.', 'customer-reviews-woocommerce' ) );
					return;
				} else {
					// skip sending because EU customer but no consent was received
					if (
						'yes' !== $consent &&
						self::is_eu_customer( $order ) &&
						'yes' === get_option( 'ivole_verified_reviews', 'no' )
					) {
						$order->add_order_note( __( 'CR: a review reminder was not scheduled because the customer did not provide their consent.', 'customer-reviews-woocommerce' ) );
						return;
					}
				}
				// skip sending because a review reminder for this order was sent with a different mailer previously
				$mailer = get_option( 'ivole_mailer_review_reminder', 'wp' );
				if( 'cr' === $mailer ) {
					$existing_wp_reviews_count = get_comments( array(
						'meta_key' => 'ivole_order_locl',
						'meta_value' => $order_id,
						'count' => true
					) );
					if( 0 < $existing_wp_reviews_count ) {
						$order->add_order_note( __( 'CR: a review reminder was not scheduled because reviews(s) have already been collected with a WordPress mailer for this order.', 'customer-reviews-woocommerce' ) );
						return;
					}
				} else {
					$existing_cr_reviews_count = get_comments( array(
						'meta_key' => 'ivole_order',
						'meta_value' => $order_id,
						'count' => true
					) );
					if( 0 < $existing_cr_reviews_count ) {
						$order->add_order_note( __( 'CR: a review reminder was not scheduled because reviews(s) have already been collected with a CusRev mailer for this order.', 'customer-reviews-woocommerce' ) );
						return;
					}
				}

				// an internal filter to skip scheduling a review reminder - please do not use
				if( apply_filters( 'cr_skip_reminder_internal', false, $order ) ) {
					return;
				}

				// a generic filter to skip scheduling a review reminder
				if( apply_filters( 'cr_skip_reminder_generic', false, $order_id ) ) {
					$order->add_order_note( __( 'CR: a review reminder was not scheduled due to a bespoke filter.', 'customer-reviews-woocommerce' ) );
					return;
				}

				//if (1) no reminders was previously scheduled via WP Cron and CR Cron is currently enabled or (2) a reminder was previously scheduled via CR Cron
				if(
					( 'cr' === get_option( 'ivole_scheduler_type', 'wp' ) && '' === $order->get_meta( '_ivole_review_reminder', true ) ) ||
					$order->get_meta( '_ivole_cr_cron', true )
				) {
					$sender_result = $this->sender_action( $order_id, true );
					if( 0 === $sender_result ) {
						$order->add_order_note( __( 'CR: a review reminder was scheduled via CR Cron. Please log in to your account on <a href="https://www.cusrev.com/login.html" target="_blank" rel="noopener noreferrer">CR website</a> to view and manage the reminders.', 'customer-reviews-woocommerce' ) );
					} else {
						if( is_array( $sender_result ) && count( $sender_result ) > 1 ) {
							/* translators: please keep %s and %d as they are because the plugin will automatically replace them with the error description and code */
							$order->add_order_note( sprintf( __( 'CR: a review reminder could not be scheduled via CR Cron. %s Error code: %d.', 'customer-reviews-woocommerce' ), $sender_result[1], $sender_result[0] ) );
						} elseif( is_array( $sender_result ) && count( $sender_result ) > 0 ) {
							/* translators: please keep %d as it is because the plugin will automatically replace it with the error code */
							$order->add_order_note( sprintf( __( 'CR: a review reminder could not be scheduled via CR Cron. Error %d.', 'customer-reviews-woocommerce' ), $sender_result[0] ) );
						} else {
							/* translators: please keep %d as it is because the plugin will automatically replace it with the error code */
							$order->add_order_note( sprintf( __( 'CR: a review reminder could not be scheduled via CR Cron. Error %d.', 'customer-reviews-woocommerce' ), $sender_result ) );
						}
					}
				} else {
					//the logic for WP Cron otherwise
					$delay_channel = self::get_sending_delay();
					$delay = $delay_channel[0];
					$timestamp = apply_filters( 'cr_reminder_delay', time() + $delay * DAY_IN_SECONDS, $order_id, $delay );
					if( false === wp_schedule_single_event( $timestamp, 'ivole_send_reminder', array( $order_id ) ) ) {
						$order->add_order_note( __( 'CR: a review reminder could not be scheduled.', 'customer-reviews-woocommerce' ) );
					} else {
						$count = $order->get_meta( '_ivole_review_reminder', true );
						if ( ! $count ) {
							$order->update_meta_data( '_ivole_review_reminder', 0 );
							$order->save();
						}
						$local_timestamp = get_date_from_gmt( date( 'Y-m-d H:i:s', $timestamp ), 'F j, Y g:i a (T)' );
						$order->add_order_note( sprintf( __( 'CR: a review reminder was successfully scheduled for %s.', 'customer-reviews-woocommerce' ) , $local_timestamp ) );
					}
				}
			}
		}

		public function sender_action( $order_id, $schedule = false ) {
			//check for duplicate / staging / test site
			if( ivole_is_duplicate_site() ) {
				update_option( 'ivole_enable', 'no' );
				return -1;
			}
			$order = wc_get_order( $order_id );
			if ( $order ) {
				//qTranslate integration
				$lang = $order->get_meta( '_user_language', true );
				$old_lang = '';
				if( $lang ) {
					global $q_config;
					$old_lang = $q_config['language'];
					$q_config['language'] = $lang;

					//WPML integration
					if ( has_filter( 'wpml_current_language' ) ) {
						$old_lang = apply_filters( 'wpml_current_language', NULL );
						do_action( 'wpml_switch_language', $lang );
					}
				}
			}

			$delay_channel = self::get_sending_delay();
			if ( 'wa' === $delay_channel[1] ) {
				$w = new CR_Wtsap( $order_id );
				$result = $w->send_message( $order_id, $schedule );
			} else {
				$e = new Ivole_Email( $order_id );
				$result = $e->trigger2( $order_id, null, $schedule );
				// logging
				$log = new CR_Reminders_Log();
				$l_result = $log->add(
					$order_id,
					'a',
					'email',
					$result
				);
				// end of logging
			}

			//qTranslate integration
			if( $lang ) {
				$q_config['language'] = $old_lang;

				//WPML integration
				if ( has_filter( 'wpml_current_language' ) ) {
					do_action( 'wpml_switch_language', $old_lang );
				}
			}
			return $result;
		}

		public function refund_trigger( $order_id ) {
			if( $order_id ) {
				$order = new WC_Order( $order_id );
				wp_clear_scheduled_hook( 'ivole_send_reminder', array( $order_id ) );
				$order->add_order_note( __( 'CR: a review reminder was cancelled because the order was refunded.', 'customer-reviews-woocommerce' ) );
			}
		}

		public function cancellation_trigger( $order_id ) {
			if( $order_id ) {
				$order = new WC_Order( $order_id );
				wp_clear_scheduled_hook( 'ivole_send_reminder', array( $order_id ) );
				$order->add_order_note( __( 'CR: a review reminder was cancelled because the order was cancelled.', 'customer-reviews-woocommerce' ) );
			}
		}

		public static function is_eu_customer( $order ) {
			$shop_country = '';
			$billing_country = '';
			$shipping_country = '';
			$is_eu = false;
			if ( class_exists( 'WC_Countries' ) ) {
				$countries = new WC_Countries();
				$eu_countries = $countries->get_european_union_countries();
				$base_location = wc_get_base_location();
				if (
					$base_location &&
					is_array( $base_location ) &&
					$base_location['country']
				) {
					$shop_country = $base_location['country'];
				}
				if ( method_exists( $order, 'get_billing_country' ) ) {
					$billing_country = $order->get_billing_country();
				}
				if ( method_exists( $order, 'get_shipping_country' ) ) {
					$shipping_country = $order->get_shipping_country();
				}
				if (
					in_array( $shop_country, $eu_countries ) ||
					in_array( $billing_country, $eu_countries ) ||
					in_array( $shipping_country, $eu_countries )
				) {
					$is_eu = true;
				}
			}
			return $is_eu;
		}

		public static function get_sending_delay() {
			$delay_option = get_option( 'ivole_delay', 5 );
			if ( is_array( $delay_option ) && 0 < count( $delay_option ) ) {
				if (
					isset( $delay_option[0]['delay'] ) &&
					isset( $delay_option[0]['channel'] )
				) {
					return array(
						intval( $delay_option[0]['delay'] ),
						strval( $delay_option[0]['channel'] )
					);
				} else {
					return array(
						5,
						'email'
					);
				}
			} else {
				return array(
					intval( $delay_option ),
					'email'
				);
			}
		}

	}

endif;
