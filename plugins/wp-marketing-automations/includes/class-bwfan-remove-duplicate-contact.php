<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'BWFAN_DEV_Remove_Duplicate_Contacts' ) ) {
	class BWFAN_DEV_Remove_Duplicate_Contacts {
		private static $ins = null;

		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		public function __construct() {
			add_action( 'admin_head', [ $this, 'check_duplicates' ] );
			add_action( 'bwfan_remove_duplicate_contacts', [ $this, 'bwfan_remove_duplicate_contacts' ] );
		}

		public function check_duplicates() {
			$key = filter_input( INPUT_GET, 'bwfan_check_duplicate_contacts' );
			if ( empty( $key ) ) {
				return;
			}

			$this->output_css();

			$contacts = $this->schedule_to_remove_duplicate_contacts();
			$count    = count( $contacts );
			echo "<h3>$count duplicate contact(s) found. An action is scheduled to remove the duplicates.</h3>";
			exit;
		}

		public function schedule_to_remove_duplicate_contacts() {
			if ( bwf_has_action_scheduled( 'bwfan_remove_duplicate_contacts' ) ) {
				echo "<h3>Action already scheduled to removed the duplicate contacts.</h3>";
				exit;
			}
			$contacts = $this->get_duplicate_contacts();
			if ( empty( $contacts ) ) {
				if ( bwf_has_action_scheduled( 'bwfan_remove_duplicate_contacts' ) ) {
					bwf_unschedule_actions( 'bwfan_remove_duplicate_contacts', array(), 'woofunnels' );
				}
				echo "<h3>No duplicate contacts found.</h3>";
				exit;
			}

			$dynamic_string = BWFAN_Common::get_dynamic_string();
			$args           = [ 'key' => $dynamic_string ];
			sort( $contacts );

			update_option( "bwfan_duplicate_contacts_{$dynamic_string}", $contacts );
			bwf_schedule_recurring_action( time(), 60, "bwfan_remove_duplicate_contacts", $args );
			BWFCRM_Common::ping_woofunnels_worker();

			return $contacts;
		}

		public function get_duplicate_contacts() {
			global $wpdb;
			$query = "SELECT `email`, GROUP_CONCAT(`id`) AS `pkey` FROM `{$wpdb->prefix}bwf_contact` WHERE `email` != '' GROUP BY `email` HAVING COUNT(`email`) > 1";

			return $wpdb->get_results( $query, ARRAY_A );
		}

		public function bwfan_remove_duplicate_contacts( $dynamic_str ) {

			$option_key = "bwfan_duplicate_contacts_{$dynamic_str}";
			$contacts   = get_option( $option_key );

			if ( empty( $contacts ) ) {
				delete_option( $option_key );
				bwf_unschedule_actions( 'bwfan_remove_duplicate_contacts' );

				return;
			}
			$updated_contacts = $contacts;
			$start_time       = time();
			foreach ( $contacts as $index => $data ) {
				/**checking 10 seconds of processing */
				if ( ( time() - $start_time ) > 20 ) {
					return;
				}
				$cids = explode( ',', $data['pkey'] );

				$order_ids        = [];
				$contact_data     = [];
				$lists            = [];
				$tags             = [];
				$creation_date    = '';
				$last_modify      = '';
				$unsubscribe_data = [];
				foreach ( $cids as $cid ) {
					$contact        = new WooFunnels_Contact( '', '', '', $cid );
					$wp_id          = $contact->get_wpid();
					$contact_orders = $this->get_orders( $cid );
					$order_ids      = array_unique( array_merge( $order_ids, $contact_orders ) );
					$lists          = array_merge( $lists, $contact->get_lists() );
					$tags           = array_merge( $tags, $contact->get_tags() );

					if ( ! empty( $contact->get_f_name() ) ) {
						$contact_data['f_name'] = $contact->get_f_name();
					}
					if ( ! empty( $contact->get_l_name() ) ) {
						$contact_data['l_name'] = $contact->get_l_name();
					}
					if ( ! empty( $contact->get_contact_no() ) ) {
						$contact_data['contact_no'] = $contact->get_contact_no();
					}
					if ( ! empty( $contact->get_country() ) ) {
						$contact_data['country'] = $contact->get_country();
					}
					if ( ! empty( $contact->get_state() ) ) {
						$contact_data['state'] = $contact->get_state();
					}
					if ( ! empty( $contact->get_timezone() ) ) {
						$contact_data['timezone'] = $contact->get_timezone();
					}
					if ( ! empty( $wp_id ) ) {
						$contact_data['wp_id'] = $wp_id;
					}
					if ( empty( $creation_date ) || $contact->get_creation_date() < $creation_date ) {
						$creation_date = $contact->get_creation_date();
					}
					if ( empty( $last_modify ) || $contact->get_last_modified() > $last_modify ) {
						$last_modify = $contact->get_last_modified();
					}
					if ( empty( $unsubscribe_data ) ) {
						$unsubscribe_data = $this->get_unsubscribe_data( $data['email'], $contact->get_contact_no() );
					}
					$contact_data['status'] = $contact->get_status();
					$contact_data['lists']  = array_unique( $lists );
					$contact_data['tags']   = array_unique( $tags );
				}
				$contact_data['creation_date']        = $creation_date;
				$contact_data['last_modified']        = $last_modify;
				$contact_data['unsubscribe_data']     = $unsubscribe_data;
				$contact_data['contact_field_values'] = $this->get_contact_fields_with_value( $data['pkey'] );

				$this->remove_meta( $order_ids );
				BWFCRM_Model_Contact::delete_multiple_contacts( $cids );

				$this->create_new_contact( $order_ids, $contact_data, $data['email'] );

				unset( $updated_contacts[ $index ] );
				sort( $updated_contacts );
				update_option( $option_key, $updated_contacts );
			}

			if ( empty( $updated_contacts ) ) {
				delete_option( $option_key );
				bwf_unschedule_actions( 'bwfan_remove_duplicate_contacts' );
			}
		}

		public function get_contact_fields_with_value( $cids ) {
			global $wpdb;

			$query = "SELECT * FROM `{$wpdb->prefix}bwf_contact_fields` WHERE `cid` IN (%s)";
			$res   = $wpdb->get_results( $wpdb->prepare( $query, $cids ), ARRAY_A );

			$t = [];
			foreach ( $res as $v ) {
				$z = array_filter( $v );
				unset( $z['ID'] );
				unset( $z['cid'] );
				foreach ( $z as $k => $val ) {
					if ( ! isset( $t[ $k ] ) || empty( $t[ $k ] ) ) {
						$t[ $k ] = $val;
					}
				}
			}

			return $t;
		}

		public function get_unsubscribe_data( $email, $contact_no ) {
			$data = array(
				'recipient' => array( $email, $contact_no ),
			);

			$unsubscribe_data = BWFAN_Model_Message_Unsubscribe::get_message_unsubscribe_row( $data, false );

			return array_map( function ( $unsubscribe ) {
				unset( $unsubscribe['ID'] );
				unset( $unsubscribe['sid'] );
				unset( $unsubscribe['automation_id'] );
				$unsubscribe['c_type'] = 3;

				return $unsubscribe;
			}, $unsubscribe_data );
		}

		public function get_orders( $cid ) {
			global $wpdb;
			$query = " SELECT p.ID FROM {$wpdb->posts} AS p JOIN {$wpdb->postmeta} AS pm ON p.ID = pm.post_id WHERE pm.meta_key = '_woofunnel_cid' AND pm.meta_value = %d ORDER BY p.ID DESC ";
			if ( BWF_WC_Compatibility::is_hpos_enabled() ) {
				$order_table      = $wpdb->prefix . 'wc_orders';
				$order_meta_table = $wpdb->prefix . 'wc_orders_meta';
				$query            = "SELECT ot.id FROM {$order_table} AS ot JOIN {$order_meta_table} AS otm ON ot.id = otm.order_id WHERE otm.meta_key = '_woofunnel_cid' AND otm.meta_value = %d ORDER BY ot.id DESC ";
			}

			return $wpdb->get_col( $wpdb->prepare( $query, $cid ) );
		}

		public function remove_meta( $order_ids ) {
			if ( empty( $order_ids ) ) {
				return;
			}

			foreach ( $order_ids as $order_id ) {
				$order = wc_get_order( $order_id );
				if ( ! $order instanceof WC_Order ) {
					continue;
				}
				$order->delete_meta_data( '_woofunnel_cid' );
				$order->save();
			}
		}

		public function create_new_contact( $order_ids, $data, $email ) {
			foreach ( $order_ids as $order_id ) {
				bwf_create_update_contact( $order_id, array(), 0, true );
			}
			$user        = get_user_by( 'email', $email );
			$bwf_contact = new WooFunnels_Contact( $user->ID, $email );

			if ( 0 === $bwf_contact->get_wpid() && $user->ID > 0 ) {
				$bwf_contact->set_wpid( $user->ID );
			}
			if ( isset( $data['f_name'] ) ) {
				$bwf_contact->set_f_name( $data['f_name'] );
			}
			if ( isset( $data['l_name'] ) ) {
				$bwf_contact->set_l_name( $data['l_name'] );
			}
			if ( isset( $data['contact_no'] ) ) {
				$bwf_contact->set_contact_no( $data['contact_no'] );
			}
			if ( isset( $data['country'] ) ) {
				$bwf_contact->set_country( $data['country'] );
			}
			if ( isset( $data['state'] ) ) {
				$bwf_contact->set_state( $data['state'] );
			}
			if ( isset( $data['status'] ) ) {
				$bwf_contact->set_status( $data['status'] );
			}
			if ( isset( $data['timezone'] ) ) {
				$bwf_contact->set_timezone( $data['timezone'] );
			}
			if ( isset( $data['wp_id'] ) ) {
				$bwf_contact->set_wpid( $data['wp_id'] );
			}

			if ( isset( $data['lists'] ) ) {
				$bwf_contact->set_lists( $data['lists'] );
			}
			if ( isset( $data['tags'] ) ) {
				$bwf_contact->set_tags( $data['tags'] );
			}

			if ( isset( $data['creation_date'] ) ) {
				$bwf_contact->set_creation_date( $data['creation_date'] );
			}
			if ( isset( $data['last_modified'] ) ) {
				$bwf_contact->set_last_modified( $data['last_modified'] );
			}

			$bwf_contact->save();

			if ( isset( $data['unsubscribe_data'] ) ) {
				$this->unsubscribe_contact( $data['unsubscribe_data'] );
			}
			if ( ! isset( $data['contact_field_values'] ) || 0 === $bwf_contact->get_id() ) {
				return;
			}

			$this->update_contact_field( $bwf_contact->get_id(), $data['contact_field_values'] );
		}

		public function update_contact_field( $cid, $fields ) {
			BWF_Model_Contact_Fields::update( $fields, [ 'id' => absint( $cid ) ] );
		}

		public function unsubscribe_contact( $data ) {
			BWFAN_Model_Message_Unsubscribe::insert_multiple( $data, array( 'recipient', 'mode', 'c_date', 'c_type' ), [ '%s', '%d', '%s', '%d' ] );
		}

		public function output_css() {
			?>
            <style>
                h3 {
                    margin: 20px;
                }
            </style>
			<?php
		}
	}

	BWFAN_DEV_Remove_Duplicate_Contacts::get_instance();
}