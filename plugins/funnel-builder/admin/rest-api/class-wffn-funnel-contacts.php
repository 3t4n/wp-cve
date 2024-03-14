<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Funnel contact class
 * Class WFFN_Funnel_Contacts
 */
if ( ! class_exists( 'WFFN_Funnel_Contacts', false ) ) {
	class WFFN_Funnel_Contacts {
		private static $ins = null;
		protected $namespace = 'funnelkit-app';
		protected $rest_base = 'funnel-analytics';

		/**
		 * WFFN_Funnel_Contacts constructor.
		 */
		public function __construct() {

			add_action( 'rest_api_init', [ $this, 'register_contact_data_endpoint' ], 11 );
		}

		/**
		 * @return WFFN_Funnel_Contacts|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function register_contact_data_endpoint() {
			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/contacts/', array(
				'args'                => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'get_funnel_contacts' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );


			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/contacts/(?P<cid>[\d]+)', array(
				'args' => array(
					'id'  => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'cid' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_contact_single' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),

			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/(?P<id>[\d]+)/contacts/spend/(?P<cid>[\d]+)', array(
				'args' => array(
					'id'  => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'cid' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_funnel_contacts_spend_details' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),

			) );

			// Global Contacts API Routes

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/global/contacts', array(
				'args'                => array(
					'id' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				'methods'             => WP_REST_Server::READABLE,
				'callback'            => array( $this, 'global_funnel_contacts' ),
				'permission_callback' => array( $this, 'get_read_api_permission_check' ),
			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/global/contacts/(?P<cid>[\d]+)', array(
				'args' => array(
					'id'  => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
					'cid' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_contact_single' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),

			) );

			register_rest_route( $this->namespace, '/' . $this->rest_base . '/global/contacts/spend/(?P<cid>[\d]+)', array(
				'args' => array(
					'cid' => array(
						'description' => __( 'Unique identifier for the resource.', 'funnel-builder' ),
						'type'        => 'integer',
					),
				),
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'get_global_contacts_spend_details' ),
					'permission_callback' => array( $this, 'get_read_api_permission_check' ),

				),

			) );

		}

		public function get_read_api_permission_check() {
			return wffn_rest_api_helpers()->get_api_permission_check( 'funnel', 'read' );
		}

		public function prepare_filters( $filters ) {
			if ( ! is_array( $filters ) ) {
				$filters = json_decode( $filters, true );
			}

			$single_data = [];

			if ( ! is_array( $filters ) || count( $filters ) === 0 ) {
				return $single_data;
			}

			foreach ( $filters as $filter ) {
				$single_data[ $filter['filter'] ] = $filter;
				if ( is_array( $filter['data'] ) ) {
					$ids = array_column( $filter['data'], 'id' );
					if ( ! empty( $ids ) ) {
						$single_data[ $filter['filter'] ]['data'] = implode( ',', $ids );
					}
				}
			}

			return $single_data;
		}


		public function get_funnel_contacts( $request ) {
			$args = array(
				'funnel_id'   => isset( $request['id'] ) ? $request['id'] : 0,
				's'           => isset( $request['s'] ) ? $request['s'] : '',
				'limit'       => isset( $request['limit'] ) ? $request['limit'] : get_option( 'posts_per_page' ),
				'page_no'     => isset( $request['page_no'] ) ? $request['page_no'] : 1,
				'orderby'     => isset( $request['orderby'] ) ? $request['orderby'] : 'last_modified',
				'order'       => ( isset( $request['order'] ) && 'DESC' === $request['order'] ) ? $request['order'] : 'ASC',
				'delete_cid'  => isset( $request['delete_cid'] ) ? $request['delete_cid'] : false,
				'filters'     => isset( $request['filters'] ) ? $request['filters'] : false,
				'total_count' => isset( $request['total_count'] ) ? $request['total_count'] : false,
			);

			$filters = [];

			if ( isset( $args['filters'] ) ) {
				$filters = $this->prepare_filters( $args['filters'] );
			}
			$contacts = $this->get_contacts( $args, $filters );

			if ( is_array( $contacts ) ) {
				$contacts['filters_list'] = $this->filters_list( $args );
				$contacts['funnel_data']  = WFFN_REST_Funnels::get_instance()->get_funnel_data( $request['id'] );
			}

			return rest_ensure_response( $contacts );
		}


		public function is_advance_filters( $filters ) {
			if ( isset( $filters['wc_order_bump_accepted'] ) && '' !== $filters['wc_order_bump_accepted'] && ! empty( $filters['wc_order_bump_in'] ) ) {
				return true;
			}


			// Filters for upsell offer
			if ( isset( $filters['offer_accepted'] ) && '' !== $filters['offer_accepted'] && ! empty( $filters['offer_in'] ) ) {
				return true;
			}

			// Filter for Order Value
			if ( ! empty( $filters['order_is'] ) && ! empty( $filters['order_value'] ) ) {
				return true;
			}

			return false;
		}


		/**
		 * Deleting contact for given contact in this funnel
		 *
		 * @param $cid
		 * @param $funnel_id
		 */
		public function delete_funnel_contacts( $cids, $funnel_id ) {

			if ( is_string( $cids ) ) {
				$get_cids = explode( ',', $cids );
			} else {
				$get_cids = [ $cids ];
			}

			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj = WFACP_Contacts_Analytics::get_instance();
				$aero     = $aero_obj->delete_contact( $get_cids, $funnel_id );
				if ( is_array( $aero ) && isset( $aero['db_error'] ) && $aero['db_error'] === true ) {
					return $aero;
				}
			}

			if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
				$optin_obj = WFFN_Optin_Contacts_Analytics::get_instance();
				$optin     = $optin_obj->delete_contact( $get_cids, $funnel_id );
				if ( is_array( $optin ) && isset( $optin['db_error'] ) && $optin['db_error'] === true ) {
					return $optin;
				}

			}

			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj = WFOB_Contacts_Analytics::get_instance();
				$bump     = $bump_obj->delete_contact( $get_cids, $funnel_id );
				if ( is_array( $bump ) && isset( $bump['db_error'] ) && $bump['db_error'] === true ) {
					return $bump;
				}
			}

			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj = WFOCU_Contacts_Analytics::get_instance();
				$upsell     = $upsell_obj->delete_contact( $get_cids, $funnel_id );
				if ( is_array( $upsell ) && isset( $upsell['db_error'] ) && $upsell['db_error'] === true ) {
					return $upsell;
				}
			}

			do_action( 'wffn_delete_funnel_contacts', $cids, $funnel_id );
		}


		public function get_contact_single( $request ) {
			$funnel_id    = (int) isset( $request['id'] ) ? $request['id'] : 0;//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$activity_ids = $this->get_contact_activity_ids( $request['cid'], $funnel_id );

			$cid             = (int) $request['cid'];
			$user_info       = [];
			$bwf_contacts    = BWF_Contacts::get_instance();
			$bwf_contact     = $bwf_contacts->get_contact_by( 'id', $cid );
			$additional_info = [];

			if ( $bwf_contact instanceof WooFunnels_Contact and $bwf_contact->get_id() > 0 ) {
				$contact_type            = ! empty( $bwf_contact->get_type() ) ? ucfirst( $bwf_contact->get_type() ) : __( 'Optin', 'funnel-builder' );
				$user_info['user_type']  = $contact_type;
				$user_info['first_name'] = $bwf_contact->get_f_name();
				$user_info['last_name']  = $bwf_contact->get_l_name();
				$user_info['email']      = $bwf_contact->get_email();
				$user_info['contact_no'] = ! empty( $bwf_contact->get_contact_no() ) ? $bwf_contact->get_contact_no() : '';
				$user_info['created_on'] = $bwf_contact->get_creation_date();
				$additional_info         = [];
				$additional_info[]       = [ 'name' => 'contact_id', 'value' => $bwf_contact->get_id() ];
			}

			$activity_records = $this->get_contact_activity_records( $cid, $activity_ids );

			$user_info['additional'] = $this->get_nice_names_for_keys( $additional_info );

			$is_autonami_active = wffn_is_plugin_active( 'wp-marketing-automations/wp-marketing-automations.php' );
			$view_link          = $is_autonami_active ? admin_url( "admin.php?page=autonami&path=/contact/$cid" ) : '#';
			$funnel_data        = WFFN_REST_Funnels::get_instance()->get_funnel_data( $funnel_id );

			return rest_ensure_response( array(
				'user_info'   => $user_info,
				'records'     => $this->add_links_to_records( $activity_records['records'] ),
				'orders'      => $activity_records['sales_data'],
				'optins'      => $activity_records['optin_data'],
				'is_autonami' => $is_autonami_active,
				'view_link'   => $view_link,
				'overview'    => $activity_records['overview'],
				'funnel_data' => is_array( $funnel_data ) ? $funnel_data : []

			) );
		}


		public function get_funnel_contacts_spend_details( $request ) {

			$id                       = (int) $request['cid'];
			$get_all_upsell_records   = [];
			$get_all_checkout_records = [];
			$get_all_bump_records     = [];
			$funnel_id                = ! empty( $request['id'] ) ? (int) $request['id'] : 0;//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$records                  = [];
			$total                    = 0;


			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj                 = WFACP_Contacts_Analytics::get_instance();
				$get_all_checkout_records = $aero_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_checkout_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_checkout_records );
				}
			}

			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj             = WFOB_Contacts_Analytics::get_instance();
				$get_all_bump_records = $bump_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_bump_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_bump_records );
				}
			}

			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj             = WFOCU_Contacts_Analytics::get_instance();
				$get_all_upsell_records = $upsell_obj->get_all_contacts_records( $funnel_id, $id );

				if ( isset( $get_all_upsell_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_upsell_records );
				}
			}


			$records_by_date = $this->sort_by_date( array_merge( $get_all_checkout_records, $get_all_bump_records, $get_all_upsell_records ) );

			if ( ! empty( $records_by_date ) && is_array( $records_by_date ) ) {
				foreach ( $records_by_date as $record ) {
					if ( ! empty( $record->total_revenue ) && $record->total_revenue > 0 ) {
						$records[] = $record;
						$total     += $record->total_revenue;
					}
				}
			}

			return rest_ensure_response( array(
				'records'       => $this->add_links_to_records( $records, $funnel_id ),
				'total_revenue' => $total
			) );

		}

		public function get_nice_names_for_keys( $user_info ) {

			$nice_names = array(
				'optin_phone'        => __( 'Phone', 'funnel-builder' ),
				'email'              => __( 'Email', 'funnel-builder' ),
				'creation_date'      => __( 'Creation Date', 'funnel-builder' ),
				'user_id'            => __( 'User ID', 'funnel-builder' ),
				'optin_first_name'   => __( 'First Name', 'funnel-builder' ),
				'optin_last_name'    => __( 'Last Name', 'funnel-builder' ),
				'contact_id'         => __( 'Contact ID', 'funnel-builder' ),
				'wfop_optin_country' => __( 'Country Code', 'funnel-builder' ),
				'contact_no'         => __( 'Phone', 'funnel-builder' ),
				'op_created_date'    => __( 'Creation Date', 'funnel-builder' ),
			);
			foreach ( $user_info as $key => &$value ) {
				if ( isset( $nice_names[ $value['name'] ] ) ) {
					$user_info[ $key ]['name'] = $nice_names[ $value['name'] ];
				}
			}

			return $user_info;
		}

		/**
		 * Sorting in descending order
		 *
		 * @param $records
		 *
		 * @return mixed
		 */
		public function sort_by_date( $records ) {
			usort( $records, function ( $a, $b ) {
				if ( strtotime( $a->date ) > strtotime( $b->date ) ) {
					return - 1;
				}
				if ( strtotime( $a->date ) < strtotime( $b->date ) ) {
					return 1;
				}
				if ( strtotime( $a->date ) === strtotime( $b->date ) ) {
					return 1;
				}
			} );

			return $records;
		}

		public function add_links_to_records( $records, $funnel_id = 0 ) {

			foreach ( $records as &$record ) {
				if ( is_null( $record->object_name ) ) {
					continue;
				}
				switch ( $record->type ) {
					case 'optin':
						$record->link = admin_url( 'admin.php?page=wf-op&edit=' . $record->object_id . '&section=design&funnel_id=' . $funnel_id );
						break;
					case 'checkout':
						$record->link = admin_url( 'admin.php?page=wfacp&wfacp_id=' . $record->object_id . '&funnel_id=' . $funnel_id );
						break;
					case 'upsell':
						$record->link = admin_url( 'admin.php?page=upstroke&section=offers&edit=' . $record->object_id . '&funnel_id=' . $funnel_id );
						break;
					case 'bump':
						$record->link = admin_url( 'admin.php?page=wfob&section=products&wfob_id=' . $record->object_id . '&funnel_id=' . $funnel_id );

						break;
					default:
						$record->link = get_permalink( $record->object_id );
				}

			}

			return $records;
		}

		public function get_productlist_from_records( $records ) {
			$products = [];
			$revenue  = [];

			if ( ! empty( $records ) && is_array( $records ) ) {
				foreach ( $records as &$record ) {
					$product = new stdClass();
					if ( ! empty( $record->type ) && 'optin' !== $record->type ) {
						$product->type = $record->type;
						if ( 'upsell' === $record->type ) {
							$product->revenue = ! empty( $record->value ) ? ( float ) $record->value : 0;
						} else {
							$product->revenue = ! empty( $record->total_revenue ) ? ( float ) $record->total_revenue : 0;
						}
						$product->name = ! empty( $record->product_name ) ? $record->product_name : 0;
						$product->qty  = ! empty( $record->qty ) ? $record->qty : '';
						$products[]    = $product;
						if ( ! isset( $revenue[ $product->type ] ) ) {
							$revenue[ $product->type ] = 0;
						}
						$revenue[ $product->type ] += $product->revenue;

					}

				}
			}

			return [ 'products' => $products, 'revenue' => $revenue ];
		}

		public function get_funnel_export_contacts( $args = array(), $total_count = false ) {
			$defaults    = array(
				'funnel_id'   => 0,
				'limit'       => 10,
				'page_no'     => 1,
				'orderby'     => 'creation_date',
				'order'       => 'DESC',
				'total_count' => false,
			);
			$args        = wp_parse_args( $args, $defaults );
			$db_results  = [];
			$contact_ids = $this->global_funnel_contacts( $args, true );
			if ( true === $total_count ) {
				return array(
					'status'  => ( is_array( $contact_ids ) && ! empty( $contact_ids ) > 0 ) ? true : false,
					'records' => [],
					'total'   => count( $contact_ids )
				);
			}
			foreach ( $contact_ids as $cid ) {
				$cid          = (int) $cid;
				$bwf_contacts = BWF_Contacts::get_instance();
				$bwf_contact  = $bwf_contacts->get_contact_by( 'id', $cid );
				if ( $bwf_contact instanceof WooFunnels_Contact and $bwf_contact->get_id() > 0 ) {
					$db_results[] = array(
						'email'  => $bwf_contact->get_email(),
						'f_name' => $bwf_contact->get_f_name(),
						'l_name' => $bwf_contact->get_l_name(),
						'phone'  => $bwf_contact->get_contact_no(),
						'date'   => $bwf_contact->get_last_modified(),
						'status' => '' !== $bwf_contact->get_type() ? $bwf_contact->get_type() : 'lead',
					);
				}
			}
			$total = count( $contact_ids );
			unset( $contact_ids );

			return array(
				'status'  => true,
				'records' => $db_results,
				'total'   => $total
			);
		}




		/**
		 * Prepare Optin Data Comma Separated
		 *
		 * @param $optin_data
		 *
		 * @return string
		 *
		 */
		public function prepare_optin_data( $optin_data ) {

			$output        = [];
			$excluded_keys = array( 'optin_first_name', 'optin_last_name', 'optin_phone', 'wfop_optin_country' );
			$optin_output  = '';
			$data          = json_decode( $optin_data, true );
			foreach ( $data as $k => $v ) {
				if ( in_array( $k, $excluded_keys, true ) ) {
					continue;
				}
				if ( ! isset( $output[ $k ] ) ) {
					$output[ $k ] = [];
				}
				$output[ $k ][] = $v;
			}
			if ( empty( $output ) ) {
				return '';
			}
			foreach ( $output as $key => $value ) {
				$optin_output .= $key . ":" . implode( ',', array_unique( $value ) ) . ',';
			}
			unset( $optin_data );

			return rtrim( $optin_output, ',' );
		}


		public function global_funnel_contacts( $request, $only_contact_ids = false ) {
			$args = array(
				'funnel_id'   => ( isset( $request['funnel_id'] ) && 'undefined' !== $request['funnel_id'] ) ? $request['funnel_id'] : '',
				's'           => isset( $request['s'] ) ? $request['s'] : '',
				'limit'       => isset( $request['limit'] ) ? $request['limit'] : 20,
				'page_no'     => isset( $request['page_no'] ) ? $request['page_no'] : 1,
				'orderby'     => isset( $request['orderby'] ) ? $request['orderby'] : 'creation_date',
				'order'       => ( isset( $request['order'] ) && 'DESC' === $request['order'] ) ? $request['order'] : 'ASC',
				'delete_cid'  => isset( $request['delete_cid'] ) ? $request['delete_cid'] : false,
				'total_count' => isset( $request['total_count'] ) ? $request['total_count'] : false,
				'filters'     => isset( $request['filters'] ) ? $request['filters'] : [],
			);

			if ( isset( $request['offset'] ) ) {
				$args['offset'] = $request['offset'];
			}
			if ( isset( $request['total_count'] ) ) {
				$args['total_count'] = $request['total_count'];
			}

			$contacts = $this->get_global_contacts( $args, $only_contact_ids );

			if ( true === $only_contact_ids ) {
				return $contacts;
			}

			return rest_ensure_response( $contacts );
		}

		public function get_global_contacts( $args, $only_contact_ids = false ) {
			$search_filters = [];
			if ( isset( $args['filters'] ) ) {
				$search_filters = $this->prepare_filters( $args['filters'] );
			}
			$search_filters['need_total_ids_count'] = $only_contact_ids;


			return $this->get_contacts( $args, $search_filters );
		}


		public function get_contacts( $args = array(), $search_filters = [] ) {
			global $wpdb;
			$funnel_id    = $args['funnel_id'];
			$data         = array( 'records' => 0 );
			$defaults     = array(
				's'           => '',
				'limit'       => get_option( 'posts_per_page' ),
				'page_no'     => 1,
				'orderby'     => 'contact.creation_date',
				'order'       => 'DESC',
				'delete_cid'  => 0,
				'total_count' => false,
			);
			$args         = wp_parse_args( $args, $defaults );
			$total_count  = wffn_string_to_bool( $args['total_count'] );
			$total        = null;
			$final_result = [];
			$delete_cid   = $args['delete_cid'];
			if ( ! empty( $delete_cid ) ) {
				$this->delete_funnel_contacts( $delete_cid, $funnel_id );
				$total_count = true;
			}

			$limit   = $args['limit'];
			$page    = $args['page_no'];
			$orderby = $args['orderby'];
			if ( isset( $args['offset'] ) ) {
				$offset = $args['offset'];
			} else {
				$offset = intval( $limit ) * intval( $page - 1 );
			}
			/**
			 * maximum request filters
			 */

			$search = ! empty( $args['s'] ) ? $args['s'] : '';

			if ( isset( $search_filters['s'] ) && isset( $search_filters['s']['data'] ) && ! empty( $search_filters['s']['data'] ) ) {
				$search = $search_filters['s']['data'];
			}

			$filters = [
				's' => $search,
			];
			// Contact Type Filter
			if ( isset( $search_filters['contact_type'] ) ) {
				$filters['contact_type'] = $search_filters['contact_type']['data'];
			}
			// Date Filters
			if ( isset( $search_filters['period'] ) ) {
				$filters['created_on'] = $search_filters['period']['data'];
			}


			$filter_query = '';

			if ( ! empty( $filters['s'] ) ) {
				$filter_query .= "AND (CONCAT(contact.f_name,' ',contact.l_name) like '%" . $filters['s'] . "%' OR contact.email LIKE '%" . $filters['s'] . "%' ) ";
			}

			/*
			 * contact_type -> we get data from direct bwf_contact table base on 'lead/purchased' request => ( purchased = customer and lead = blank )
			 */
			if ( ! empty( $filters['contact_type'] ) ) {
				$filter_query .= ( 'purchased' === $filters['contact_type'] ) ? " AND contact.type = 'customer'" : " AND contact.type = ''";
			}

			/*
			 * created_on -> we get data from direct bwf_contact table base on after and end before request
			 */
			if ( ! empty( $filters['created_on'] ) ) {
				$filter_query .= " AND contact.creation_date BETWEEN '" . $filters['created_on']['after'] . "' AND '" . $filters['created_on']['before'] . "'";
			}


			$funnel_id_Query = '';

			if ( isset( $search_filters['funnels'] ) && isset( $search_filters['funnels']['data'] ) && ! empty( $search_filters['funnels']['data'] ) ) {
				$funnel_id_Query = " AND ( aero.fid IN (" . $search_filters['funnels']['data'] . ") OR optin.funnel_id IN (" . $search_filters['funnels']['data'] . ") )";
			} elseif ( $funnel_id > 0 ) {
				$funnel_id_Query = " AND ( aero.fid IN (" . $funnel_id . ") OR optin.funnel_id IN (" . $funnel_id . ") )";
			}

			$query = "SELECT contact.id as 'id', ( CASE WHEN contact.f_name = '' THEN 'no name' ELSE contact.f_name END ) 'f_name',contact.l_name as 'l_name',contact.contact_no as phone, contact.email as 'email', contact.creation_date as 'date', ( CASE WHEN contact.type = '' THEN 'lead' ELSE 'customer' END ) as 'type' FROM " . $wpdb->prefix . "bwf_contact as contact
			 LEFT JOIN " . $wpdb->prefix . "wfacp_stats as aero ON contact.id = aero.cid 
			 LEFT JOIN " . $wpdb->prefix . "bwf_optin_entries as optin ON contact.id = optin.cid	
			 WHERE 1=1 AND (contact.id IS NOT NULL) AND ( optin.cid IS NOT NULL OR aero.cid IS NOT NULL ) " . $funnel_id_Query . $filter_query;
			$query .= " GROUP BY contact.id";
			$query .= " ORDER BY $orderby DESC";

			if ( false === $this->is_advance_filters( $filters ) && false === $total_count ) {
				$query .= " LIMIT $offset, $limit";
			}
			$contact_data = $wpdb->get_results( $query, ARRAY_A );

			$db_error = WFFN_Common::maybe_wpdb_error( $wpdb );
			if ( true === $db_error['db_error'] ) {
				return rest_ensure_response( $db_error );
			}
			if ( ! is_array( $contact_data ) || count( $contact_data ) === 0 ) {
				return ( $data );
			}
			/**
			 * Filters for upsell/bump/total should be applied here
			 */

			$filtered_ids = wp_list_pluck( $contact_data, 'id' );

			if ( isset( $search_filters['need_total_ids_count'] ) && true === $search_filters['need_total_ids_count'] ) {
				return $filtered_ids;
			}


			if ( $total_count && is_array( $contact_data ) && count( $contact_data ) > 0 ) {
				$page_key            = absint( $page ) - 1;
				$total               = count( $filtered_ids );//phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
				$all_chunks_filters  = array_chunk( $filtered_ids, $limit );
				$filtered_ids        = isset( $all_chunks_filters[ $page_key ] ) ? $all_chunks_filters[ $page_key ] : [];
				$contact_data_chunks = array_chunk( $contact_data, $limit );
				$contact_data        = isset( $contact_data_chunks[ $page_key ] ) ? $contact_data_chunks[ $page_key ] : [];

			}
			if ( is_array( $filtered_ids ) && 0 < count( $filtered_ids ) ) {
				foreach ( $contact_data as $contact ) {
					$contact_type                   = $contact['type'];
					$final_result[ $contact['id'] ] = array(
						'f_name'       => $contact['f_name'],
						'l_name'       => $contact['l_name'],
						'email'        => $contact['email'],
						'phone'        => $contact['phone'],
						'contact_type' => $contact_type,
						'status_text'  => ( 'lead' === $contact_type ) ? __( 'Optin', 'funnel-builder' ) : __( 'Customer', 'funnel-builder' ),
						'status_type'  => $contact_type,
						'date'         => $contact['date'],
					);
				}


				if ( ! empty( $filtered_ids ) ) {
					$filtered_ids = array_map( 'absint', $filtered_ids );
					uksort( $final_result, function ( $a, $b ) use ( $filtered_ids ) {
						return array_search( $a, $filtered_ids, true ) > array_search( $b, $filtered_ids, true ) ? 1 : - 1;
					} );
					$final_result = array_map( function ( $k ) use ( $final_result ) {
						return array_merge( $final_result[ $k ], array( 'cid' => $k ) );
					}, $filtered_ids );
				}

				$data = array( 'records' => $final_result );
				if ( ! is_null( $total ) ) {
					$data['total_records'] = $total;
				}
				$funnel             = new WFFN_Funnel( $funnel_id );
				$data['count_data'] = array(
					'steps' => $funnel->get_step_count(),
				);
			} else {
				$data = array( 'records' => [] );

				$funnel             = new WFFN_Funnel( $funnel_id );
				$data['count_data'] = array(
					'steps' => $funnel->get_step_count(),
				);
			}
			$data['filters_list'] = $this->filters_list( $args );

			return $data;
		}


		public function get_contact_activity_records( $cid, $activity_ids, $funnel_id = '' ) {
			$sales_data      = [];
			$optin_data      = [];
			$all_orders      = [];
			$all_optins      = [];
			$records_by_date = [];
			$bwf_conversion  = array(
				'bump'         => 0,
				'checkout'     => 0,
				'upsell'       => 0,
				'total'        => 0,
				'total_orders' => 0,
				'aov'          => 0,
			);

			$activity_records = array(
				'sales_data' => $sales_data,
				'optin_data' => $optin_data,
				'overview'   => $bwf_conversion,
				'records'    => $records_by_date
			);

			if ( ! is_array( $activity_ids ) || count( $activity_ids ) === 0 ) {
				return $activity_records;
			}

			if ( ! empty( $activity_ids['op_entry_ids'] ) && is_array( $activity_ids['op_entry_ids'] ) ) {
				$entry_ids = ! empty( $activity_ids['op_entry_ids'] ) ? implode( ',', $activity_ids['op_entry_ids'] ) : 0;

				if ( class_exists( 'WFFN_Optin_Contacts_Analytics' ) ) {
					$optin_obj     = WFFN_Optin_Contacts_Analytics::get_instance();
					$optin_records = $optin_obj->get_contacts_optin_records( $cid, $entry_ids );
					if ( ! isset( $optin_records['db_error'] ) ) {
						$optin_data = $optin_records;
					}

				}
			}

			if ( is_array( $activity_ids['order_ids'] ) && count( $activity_ids['order_ids'] ) > 0 ) {
				foreach ( $activity_ids['order_ids'] as $order_id ) {
					$conv_order   = [];
					$product_data = $this->get_single_order_info( $order_id, $cid );

					$conv_data  = apply_filters( 'wffn_conversion_tracking_data_activity', [], $cid, $order_id );
					$conv_order = array_merge( $conv_order, $conv_data );

					$conv_order['products'] = $product_data['products'];
					$sales_data             = array_merge( $sales_data, $product_data['timeline_data'] );
					$funnel_ids             = array_unique( array_column( $conv_order['products'], 'fid' ) );
					$conv_order['order_id'] = $order_id;

					if ( isset( $conv_order['overview'] ) ) {
						unset( $conv_order['overview'] );
					}

					if ( isset( $conv_order['conversion'] ) ) {
						$conv_order['conversion']['funnel_link']  = '';
						$conv_order['conversion']['funnel_title'] = '';
						$s_funnel_id                              = ! empty( $funnel_id ) ? $funnel_id : ( ( is_array( $funnel_ids ) && count( $funnel_ids ) > 0 ) ? $funnel_ids[0] : 0 );
						$conv_order['conversion']['funnel_id']    = $s_funnel_id;

						$get_funnel = new WFFN_Funnel( $s_funnel_id );
						if ( $get_funnel instanceof WFFN_Funnel && 0 !== $get_funnel->get_id() ) {
							$funnel_link                              = ( $get_funnel->get_id() === WFFN_Common::get_store_checkout_id() ) ? admin_url( "admin.php?page=bwf&path=/store-checkout" ) : admin_url( "admin.php?page=bwf&path=/funnels/$s_funnel_id" );
							$conv_order['conversion']['funnel_link']  = $funnel_link;
							$conv_order['conversion']['funnel_title'] = $get_funnel->get_title();
						}
					}

					$conv_order['customer_info'] = array(
						'email'            => '',
						'phone'            => '',
						'billing_address'  => '',
						'shipping_address' => '',
					);

					if ( ! empty( $order_id ) && absint( $order_id ) > 0 && function_exists( 'wc_get_order' ) ) {
						$order_data = wc_get_order( $order_id );
						if ( $order_data instanceof WC_Order ) {
							$conv_order['customer_info'] = [
								'email'            => $order_data->get_billing_email(),
								'phone'            => $order_data->get_billing_phone(),
								'billing_address'  => wp_kses_post( $order_data->get_formatted_billing_address() ),
								'shipping_address' => wp_kses_post( $order_data->get_formatted_shipping_address() ),
								'purchased_on'     => ! empty( $order_data->get_date_created() ) ? $order_data->get_date_created()->date( 'Y-m-d H:i:s' ) : $product_data['date_added'],
								'payment_method'   => $order_data->get_payment_method_title(),
							];
						}
					}

					$all_orders[] = $conv_order;
				}
			}


			$records_by_date = $this->sort_by_date( array_merge( $sales_data, $optin_data ) );

			if ( is_array( $records_by_date ) && count( $records_by_date ) > 0 ) {
				$all_products = $this->get_productlist_from_records( $records_by_date );
				$total_orders = array_filter( $records_by_date, function ( $item ) {
					return $item->type === 'checkout';
				} );

				$bwf_conversion['bump']         = ! empty( $all_products['revenue']['bump'] ) ? $all_products['revenue']['bump'] : 0;
				$bwf_conversion['checkout']     = ! empty( $all_products['revenue']['checkout'] ) ? $all_products['revenue']['checkout'] : 0;
				$bwf_conversion['upsell']       = ! empty( $all_products['revenue']['upsell'] ) ? $all_products['revenue']['upsell'] : 0;
				$bwf_conversion['total']        = $bwf_conversion['bump'] + $bwf_conversion['checkout'] + $bwf_conversion['upsell'];
				$bwf_conversion['total_orders'] = ! empty( $total_orders ) ? count( $total_orders ) : 0;
				$bwf_conversion['aov']          = ( absint( $bwf_conversion['total_orders'] ) !== 0 ) ? round( $bwf_conversion['total'] / $bwf_conversion['total_orders'], 2 ) : 0;
			}

			if ( is_array( $activity_ids['op_entry_ids'] ) && count( $activity_ids['op_entry_ids'] ) > 0 ) {
				foreach ( $activity_ids['op_entry_ids'] as $entry_id ) {
					$conv_optin = apply_filters( 'wffn_conversion_tracking_data_activity', [], $cid, $entry_id );
					$search_key = array_search( $entry_id, wp_list_pluck( $optin_data, 'id' ) ); //phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
					if ( false !== $search_key && isset( $optin_data[ $search_key ] ) ) {
						$op_funnel_id           = $optin_data[ $search_key ]->fid;
						$op_date                = $optin_data[ $search_key ]->date;
						$optin_contact_data     = [];
						$conv_optin['entry_id'] = $entry_id;

						if ( ! empty( $optin_data[ $search_key ]->email ) ) {
							$optin_contact_data[] = [ 'name' => 'email', 'value' => $optin_data[ $search_key ]->email ];
						}
						if ( ! empty( $optin_data[ $search_key ]->date ) ) {
							$optin_contact_data[] = [ 'name' => 'op_created_date', 'value' => $op_date, 'added_date' => 'yes' ];
						}

						$get_last_optin_data = $optin_data[ $search_key ]->data;
						$get_last_optin_data = json_decode( $get_last_optin_data, true );
						if ( is_array( $get_last_optin_data ) && count( $get_last_optin_data ) > 0 ) {
							foreach ( $get_last_optin_data as $k => $d ) {
								$optin_contact_data[] = [ 'name' => $k, 'value' => $d ];
							}
						}
						$conv_optin['fields_data'] = $this->get_nice_names_for_keys( $optin_contact_data );

						if ( is_array( $conv_optin ) && isset( $conv_optin['conversion'] ) ) {
							$conv_optin['conversion']['funnel_link']  = '';
							$conv_optin['conversion']['funnel_title'] = '';
							$conv_optin['conversion']['funnel_id']    = ! empty( $funnel_id ) ? $funnel_id : $op_funnel_id;

							unset( $conv_optin['overview'] );

							$get_funnel = new WFFN_Funnel( $conv_optin['conversion']['funnel_id'] );
							if ( $get_funnel instanceof WFFN_Funnel && 0 !== $get_funnel->get_id() ) {
								$funnel_link                              = ( $get_funnel->get_id() === WFFN_Common::get_store_checkout_id() ) ? admin_url( "admin.php?page=bwf&path=/store-checkout" ) : admin_url( "admin.php?page=bwf&path=/funnels/$op_funnel_id" );
								$conv_optin['conversion']['funnel_link']  = $funnel_link;
								$conv_optin['conversion']['funnel_title'] = $get_funnel->get_title();
							}
						}

						$all_optins[] = $conv_optin;
					}
				}
			}

			return array(
				'sales_data' => $all_orders,
				'optin_data' => $all_optins,
				'overview'   => $bwf_conversion,
				'records'    => $records_by_date
			);
		}

		public function get_single_order_info( $order_id, $cid = '' ) {

			$timeline_data = [];
			$products      = [];
			$data          = [
				'products'      => $products,
				'date_added'    => '',
				'timeline_data' => $timeline_data,
			];

			$order = wc_get_order( $order_id );
			if ( ! $order instanceof WC_Order ) {
				return $data;
			}

			$items    = $order->get_items();
			$subtotal = 0;
			$i        = 0;
			foreach ( $items as $item ) {
				$product       = new stdClass();
				$key           = 'checkout';
				$product->date = '';

				/**
				 * create data for show timeline data
				 */
				if ( class_exists( 'WFACP_Contacts_Analytics' ) && ! empty( $cid ) && 0 === $i ) {
					/*
					 * show checkout in timeline only one time per order
					 */
					$aero_obj         = WFACP_Contacts_Analytics::get_instance();
					$checkout_records = $aero_obj->get_contacts_revenue_records( $cid, $order_id );
					if ( is_array( $checkout_records ) && ! isset( $checkout_records['db_error'] ) && isset( $checkout_records[0] ) ) {
						$data['date_added']      = $checkout_records[0]->date;
						$data['timeline_data'] = array_merge( $data['timeline_data'], $checkout_records );
					}

				}
				if ( 'yes' === $item->get_meta( '_upstroke_purchase' ) ) {
					$key = 'upsell';
					if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
						$upsell_obj     = WFOCU_Contacts_Analytics::get_instance();
						$upsell_records = $upsell_obj->get_contacts_revenue_records( $cid, $order_id );

						if ( is_array( $upsell_records ) && ! isset( $upsell_records['db_error'] ) && isset( $upsell_records[0] ) ) {
							$data['date_added']      = empty( $data['date_added'] ) ? $upsell_records[0]->date : $data['date_added'];
							$data['timeline_data'] = array_merge( $data['timeline_data'], $upsell_records );
						}

					}
				}
				if ( 'yes' === $item->get_meta( '_bump_purchase' ) ) {
					$key = 'bump';
					if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
						$bump_obj     = WFOB_Contacts_Analytics::get_instance();
						$bump_records = $bump_obj->get_contacts_revenue_records( $cid, $order_id );

						if ( is_array( $bump_records ) && ! isset( $bump_records['db_error'] ) && isset( $bump_records[0] ) ) {
							$data['date_added']      = empty( $data['date_added'] ) ? $bump_records[0]->date : $data['date_added'];
							$data['timeline_data'] = array_merge( $data['timeline_data'], $bump_records );
						}
					}
				}

				$sub_total          = WFFN_Common::wffn_round( $item->get_subtotal() );
				$product->name      = $item->get_name();
				$product->revenue   = $sub_total;
				$product->type      = $key;
				$data['products'][] = $product;
				$subtotal           += $sub_total;
				$i ++;
			}
			$order_total      = $order->get_total();
			$total_discount   = $order->get_total_discount();
			$remaining_amount = $order_total - ( $subtotal - $total_discount );
			if ( $remaining_amount > 0 ) {
				$shipping_tax          = new stdClass();
				$shipping_tax->name    = __( 'Including shipping and taxes ,other costs', 'funnel-builder' );
				$shipping_tax->revenue = WFFN_Common::wffn_round( $remaining_amount );
				$shipping_tax->type    = 'shipping';
				$data['products'][]    = $shipping_tax;
			}
			if ( $order->get_total_discount() > 0 ) {
				$discount           = new stdClass();
				$discount->name     = __( 'Discount', 'funnel-builder' );
				$discount->revenue  = WFFN_Common::wffn_round( $order->get_total_discount() );
				$discount->type     = 'discount';
				$data['products'][] = $discount;
			}

			return $data;

		}

		public function get_funnel_from_contact_id( $cid, $all = false ) {
			global $wpdb;
			$funnel_id = [];
			$convert   = '';

			if ( absint( $cid ) > 0 ) {

				// Fetch Funnel ID from Order Bump
				if ( $all ) {
					$convert = " AND converted = 1 ";
				}
				$bump_sql = "SELECT DISTINCT fid as funnel_id FROM " . $wpdb->prefix . "wfob_stats WHERE cid = " . $cid . $convert;

				if ( $all ) {
					$bump_funnel_id = $wpdb->get_col( $bump_sql );
					if ( is_array( $bump_funnel_id ) && count( $bump_funnel_id ) > 0 ) {
						$funnel_id = array_merge( $funnel_id, $bump_funnel_id );
					}
				} else {
					$bump_funnel_id = $wpdb->get_var( $bump_sql );
					if ( ! empty( $bump_funnel_id ) ) {
						$funnel_id[] = $bump_funnel_id;
					}
				}

				// Fetch Funnel ID from Checkout
				$checkout_sql = "SELECT DISTINCT fid as funnel_id FROM " . $wpdb->prefix . "wfacp_stats WHERE cid = " . $cid;
				if ( $all ) {
					$checkout_funnel_id = $wpdb->get_col( $checkout_sql );
					if ( is_array( $checkout_funnel_id ) && count( $checkout_funnel_id ) > 0 ) {
						$funnel_id = array_merge( $funnel_id, $checkout_funnel_id );
					}
				} else {
					$checkout_funnel_id = $wpdb->get_var( $checkout_sql );
					if ( ! empty( $checkout_funnel_id ) ) {
						$funnel_id[] = $checkout_funnel_id;
					}
				}

				// Fetch Funnel ID from Optin
				$optin_sql = "SELECT DISTINCT funnel_id FROM " . $wpdb->prefix . "bwf_optin_entries WHERE cid = " . $cid;
				if ( $all ) {
					$optin_funnel_id = $wpdb->get_col( $optin_sql );
					if ( is_array( $optin_funnel_id ) && count( $optin_funnel_id ) > 0 ) {
						$funnel_id = array_merge( $funnel_id, $optin_funnel_id );
					}
				} else {
					$optin_funnel_id = $wpdb->get_var( $optin_sql );
					if ( ! empty( $optin_funnel_id ) ) {
						$funnel_id[] = $optin_funnel_id;
					}
				}

				// Fetch Funnel ID from Upsell
				$upsell_sql = "SELECT DISTINCT fid as funnel_id FROM " . $wpdb->prefix . "wfocu_session WHERE cid = " . $cid;
				if ( $all ) {
					$upsell_funnel_id = $wpdb->get_col( $upsell_sql );
					if ( is_array( $upsell_funnel_id ) && count( $upsell_funnel_id ) > 0 ) {
						$funnel_id = array_merge( $funnel_id, $upsell_funnel_id );
					}
				} else {
					$upsell_funnel_id = $wpdb->get_var( $upsell_sql );
					if ( ! empty( $upsell_funnel_id ) ) {
						$funnel_id[] = $upsell_funnel_id;
					}
				}

			}

			return array_unique( $funnel_id );

		}

		public function get_contact_activity_ids( $cid, $funnel_id = 0 ) {
			global $wpdb;
			$order_ids = [];
			$optin_ids = [];
			$data      = [
				'order_ids'    => $order_ids,
				'op_entry_ids' => $optin_ids,
			];
			if ( absint( $cid ) === 0 ) {
				return $data;
			}
			$funnel_q = ( intval( $funnel_id ) > 0 ) ? " AND fid = " . $funnel_id . " " : " AND fid != 0 ";

			// Fetch Funnel ID from Optin
			$funnel_optin_q = ( intval( $funnel_id ) > 0 ) ? " AND funnel_id = " . $funnel_id . " " : " AND funnel_id != 0 ";
			$optin_sql      = "SELECT DISTINCT id as 'entry_id' FROM " . $wpdb->prefix . "bwf_optin_entries WHERE 1 = 1" . $funnel_optin_q . " AND cid = " . $cid . " ORDER BY entry_id DESC";

			$optin_funnel_id = $wpdb->get_col( $optin_sql );
			if ( is_array( $optin_funnel_id ) && count( $optin_funnel_id ) > 0 ) {
				$optin_ids = $optin_funnel_id;
			}


			// Fetch Funnel ID from Checkout
			$checkout_sql       = "SELECT DISTINCT order_id as order_id FROM " . $wpdb->prefix . "wfacp_stats WHERE order_id != 0 " . $funnel_q . " AND cid = " . $cid . " ORDER BY order_id DESC";
			$checkout_funnel_id = $wpdb->get_col( $checkout_sql );
			if ( is_array( $checkout_funnel_id ) && count( $checkout_funnel_id ) > 0 ) {
				$order_ids = $checkout_funnel_id;
			}


			$data['order_ids']    = $order_ids;
			$data['op_entry_ids'] = $optin_ids;

			return $data;

		}

		public function get_global_contacts_spend_details( $request ) {

			$funnel_cid = $this->get_funnel_from_contact_id( (int) $request['cid'] );

			$id                       = (int) $request['cid'];
			$get_all_upsell_records   = [];
			$get_all_optin_records    = [];
			$get_all_checkout_records = [];
			$get_all_bump_records     = [];
			$funnel_id                = ! empty( $request['id'] ) ? (int) $request['id'] : $funnel_cid;//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$records                  = [];
			$total                    = 0;


			if ( class_exists( 'WFACP_Contacts_Analytics' ) ) {
				$aero_obj                 = WFACP_Contacts_Analytics::get_instance();
				$get_all_checkout_records = $aero_obj->get_all_contact_record_by_cid( $id );

				if ( isset( $get_all_checkout_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_checkout_records );
				}
			}

			if ( class_exists( 'WFOB_Contacts_Analytics' ) ) {
				$bump_obj             = WFOB_Contacts_Analytics::get_instance();
				$get_all_bump_records = $bump_obj->get_all_contact_record_by_cid( $id );

				if ( isset( $get_all_bump_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_bump_records );
				}
			}

			if ( class_exists( 'WFOCU_Contacts_Analytics' ) ) {
				$upsell_obj             = WFOCU_Contacts_Analytics::get_instance();
				$get_all_upsell_records = $upsell_obj->get_all_contact_record_by_cid( $id );

				if ( isset( $get_all_upsell_records['db_error'] ) ) {
					return rest_ensure_response( $get_all_upsell_records );
				}
			}

			$records_by_date = $this->sort_by_date( array_merge( $get_all_optin_records, $get_all_checkout_records, $get_all_bump_records, $get_all_upsell_records ) );

			if ( ! empty( $records_by_date ) && is_array( $records_by_date ) ) {
				foreach ( $records_by_date as $record ) {
					if ( ! empty( $record->total_revenue ) && $record->total_revenue > 0 ) {
						$records[] = $record;
						$total     += $record->total_revenue;
					}
				}
			}

			return rest_ensure_response( array(
				'records'       => $this->add_links_to_records( $records, $funnel_id ),
				'total_revenue' => $total
			) );

		}

		/**
		 * Contact UI Filters
		 * @return array[]
		 */
		public function filters_list( $args = array() ) {

			$filters = array(
				array(
					"type"  => "sticky",
					"rules" => array(
						array(
							"slug"          => "period",
							"title"         => __( "Date Created", 'funnel-builder' ),
							"type"          => "date-range",
							"op_label"      => __( "Time Period", 'funnel-builder' ),
							"required"      => array( "rule", "data" ),
							"readable_text" => "{{value /}}",
						),
						array(
							"slug"          => "contact_type",
							"title"         => __( "Type", 'funnel-builder' ),
							"type"          => "select",
							"options"       => array(
								"purchased" => __( "Customer", 'funnel-builder' ),
								"lead"      => __( "Optin", 'funnel-builder' ),
							),
							"val_label"     => __( "Type", 'funnel-builder' ),
							"required"      => array( "data" ),
							"readable_text" => "{{value /}}",
						),
					),
				)
			);

			if ( ! isset( $args['funnel_id'] ) || intval( $args['funnel_id'] ) === 0 ) {
				$filters[0]['rules'][] = array(
					'slug'          => 'funnels',
					'title'         => __( 'Funnel' ),
					'type'          => 'search',
					'api'           => '/funnels/?s={{search}}&search_filter',
					'op_label'      => __( 'Funnel' ),
					'required'      => array( 'data' ),
					'readable_text' => '{{rule /}} - {{value /}}',
				);
			}

			return $filters;
		}




	}


	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'wffn_contacts', 'WFFN_Funnel_Contacts' );
	}
}