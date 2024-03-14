<?php
/**
 * HB_WPML_Support
 *
 * @author   ThimPress
 * @package  WP-Hotel-Booking/WPML/Classes
 * @version  1.8.0
 */

// Prevent loading this file directly
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'HB_WPML_Support' ) ) {
	/**
	 * Class HB_WPML_Support
	 */
	class HB_WPML_Support {

		/**
		 * @var null|SitePress
		 */
		public $sitepress = null;

		/**
		 * @var bool|mixed|null
		 */
		public $default_language_code = null;

		/**
		 * @var null
		 */
		public $current_language_code = null;

		/**
		 * HB_WPML_Support constructor.
		 */
		public function __construct() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			// wpml settings
			$wpml_settings = get_option( 'icl_sitepress_settings' );

			// get wpml custom posts, taxonomies
			if ( isset( $wpml_settings['custom_posts_sync_option'] ) && isset( $wpml_settings['taxonomies_sync_option'] ) ) {
				$custom_posts_translate = $wpml_settings['custom_posts_sync_option'];
				$custom_taxs_translate  = $wpml_settings['taxonomies_sync_option'];

				if ( ! ( isset( $custom_posts_translate['hb_room'] ) && isset( $custom_taxs_translate['hb_room_capacity'] ) ) ) {
					return;
				}
			}

			// sitepress
			global $sitepress;

			// sitepress object instance
			$this->sitepress = $sitepress;

			// default language code
			$this->default_language_code = $this->sitepress->get_default_language();
			// current language code
			$this->current_language_code = defined( 'ICL_LANGUAGE_CODE' ) ? ICL_LANGUAGE_CODE : null;

			// filter dropdown rooms
			add_filter( 'hotel_booking_rooms_dropdown', array( $this, 'hotel_booking_rooms_dropdown' ) );

			// init
			//			add_action( 'init', array( $this, 'init' ), 999 );

			if ( $this->current_language_code != $this->default_language_code ) {
				/* disable change some room attributes in other post languages */
				add_filter( 'hb_metabox_room_settings', array( $this, 'disable_change_room_attributes' ) );
				/* disable change some extra attributes in other post languages */
				add_filter( 'hb_metabox_extra_settings', array( $this, 'disable_change_extra_attributes' ) );
				/* disable change some coupon attributes in other post languages */
				add_filter( 'hb_metabox_coupon_settings', array( $this, 'disable_change_coupon_attributes' ) );
			}

			// filter extra
			add_filter( 'hb_filter_extra_option', array( $this, 'filter_extra' ), 10, 2 );

			/* capacity */
			add_filter( 'manage_hb_room_capacity_custom_column', array( $this, 'hb_capacity_order_attr' ), 20, 3 );

			/* get page filter */
			add_filter( 'hb_get_pages', array( $this, 'hb_get_pages' ) );
			/* get page id return page in current language */
			add_filter( 'hb_get_page_id', array( $this, 'hb_get_page_id' ) );
			/* set default page of system in other language page */
			add_filter( 'the_content', array( $this, 'the_content' ) );
			/* setup query search room */
			add_filter( 'hb_search_query', array( $this, 'hb_search_query' ), 99, 2 );
			/* get pricing plan */
			add_filter( 'hb_room_get_pricing_plans', array( $this, 'hb_pricing_plans' ), 10, 2 );
			/* cart generate transaction */
			add_filter( 'hb_generate_transaction_object', array( $this, 'hb_generate_transaction_object' ), 10, 2 );
			add_filter( 'hb_generate_transaction_object_room', array(
				$this,
				'hb_generate_transaction_object_room'
			), 10, 2 );

			add_filter( 'get_max_capacity_of_rooms', array( $this, 'max_capacity_of_rooms' ) );

			add_filter( 'hotel_booking_query_search_parser', array( $this, 'parse_available_rooms' ) );

			add_action( 'icl_make_duplicate', array( $this, 'update_room_meta' ), 10, 4 );

			add_filter( 'hb_thank_you_url', array( $this, 'thank_you_page_url' ), 10, 5 );

			add_action( 'hotel_booking_cart_after_item', array( $this, 'checkout_wpml_lang_field' ) );
			add_action( 'hotel_booking_loop_after_item', array( $this, 'checkout_wpml_lang_field' ) );
			add_action( 'hotel_booking_after_select_extra', array( $this, 'checkout_wpml_lang_field' ) );

			add_filter( 'hotel-booking-order-room-id', array( $this, 'filter_room_id' ) );
			add_filter( 'hotel-booking-order-extra-id', array( $this, 'filter_extra_id' ) );

			add_filter( 'hb_mini_cart_room_name', array( $this, 'item_cart_name' ), 10, 2 );
			add_filter( 'hb_mini_cart_extra_name', array( $this, 'item_cart_name' ), 10, 2 );
			add_filter( 'hb_cart_room_name', array( $this, 'item_cart_name' ), 10, 2 );
			add_filter( 'hb_cart_extra_name', array( $this, 'item_cart_name' ), 10, 2 );
			add_filter( 'hb_checkout_room_name', array( $this, 'item_cart_name' ), 10, 2 );

			add_filter( 'hotel_booking_get_available_room', array( $this, 'get_available_room' ) );

			add_filter( 'woocommerce_cart_item_name', array( $this, 'woo_cart_item_name' ), 10, 3 );

			add_filter( 'hotel_booking_query_search_parser', array( $this, 'query_search_parser' ), 10, 2 );
			add_filter( 'hb_checkout_url', array( $this, 'wpml_hb_checkout_url' ), 10, 1 );
			add_filter( 'hb_cart_url', array( $this, 'wpml_cart_page_url' ), 10, 1 );
			add_filter( 'hb_search_room_url', array( $this, 'wpml_hb_search_room_url' ), 10, 1 );
		}

		public function wpml_hb_checkout_url($url){
			$current_lang = isset( $_POST['wpml_language'] ) ? $_POST['wpml_language'] : ICL_LANGUAGE_CODE;
			if ( $current_lang != $this->default_language_code ) {
				global $wpdb;
				$master_page_id = hb_get_page_id( 'checkout' );
				$duplicate_page_id = $wpdb->get_var(
					$wpdb->prepare( "
				SELECT duplicate_page.element_id FROM {$wpdb->prefix}icl_translations master_page
			    INNER JOIN {$wpdb->prefix}icl_translations duplicate_page ON master_page.trid = duplicate_page.trid
			    WHERE 
			  		master_page.element_id = %d 
			  		AND duplicate_page.element_id != %d
			    	AND master_page.element_type = %s
			    	AND duplicate_page.element_type = %s
			    	AND duplicate_page.language_code = %s
			    ", $master_page_id, $master_page_id, 'post_page', 'post_page', $current_lang ) );

				if ( ! $duplicate_page_id ) {
					return $url;
				} else {
					$url = get_the_permalink( $duplicate_page_id );
				}
				return apply_filters( 'wpml_permalink', $url, $current_lang );
			}

			return $url;
		}

		public function enqueue_scripts() {
		    $current_screen = get_current_screen();
		    if(!empty($current_screen) && $current_screen->post_type === 'hb_extra_room'){
			    wp_enqueue_script( 'hbwp_wpml_support', HOTELBOOKING_WMPL_URI . '/assets/js/hbwp-admin.js', array(), HOTELBOOKING_WMPL_VER );
            }
		}

		public function wpml_hb_search_room_url($url){
			$current_lang = isset( $_POST['wpml_language'] ) ? $_POST['wpml_language'] : ICL_LANGUAGE_CODE;
			if ( $current_lang != $this->default_language_code ) {
				global $wpdb;
				$master_page_id = hb_get_page_id( 'search' );
				$duplicate_page_id = $wpdb->get_var(
					$wpdb->prepare( "
				SELECT duplicate_page.element_id FROM {$wpdb->prefix}icl_translations master_page
			    INNER JOIN {$wpdb->prefix}icl_translations duplicate_page ON master_page.trid = duplicate_page.trid
			    WHERE 
			  		master_page.element_id = %d 
			  		AND duplicate_page.element_id != %d
			    	AND master_page.element_type = %s
			    	AND duplicate_page.element_type = %s
			    	AND duplicate_page.language_code = %s
			    ", $master_page_id, $master_page_id, 'post_page', 'post_page', $current_lang ) );

				if ( ! $duplicate_page_id ) {
					return $url;
				}
				return apply_filters( 'wpml_permalink', $url, $current_lang );
			}

			return $url;
		}

		public function wpml_cart_page_url($url){
			$current_lang = isset( $_POST['wpml_language'] ) ? $_POST['wpml_language'] : ICL_LANGUAGE_CODE;
			if ( $current_lang != $this->default_language_code ) {
				global $wpdb;
				$master_page_id = hb_get_page_id( 'cart' );
				$duplicate_page_id = $wpdb->get_var(
					$wpdb->prepare( "
				SELECT duplicate_page.element_id FROM {$wpdb->prefix}icl_translations master_page
			    INNER JOIN {$wpdb->prefix}icl_translations duplicate_page ON master_page.trid = duplicate_page.trid
			    WHERE 
			  		master_page.element_id = %d 
			  		AND duplicate_page.element_id != %d
			    	AND master_page.element_type = %s
			    	AND duplicate_page.element_type = %s
			    	AND duplicate_page.language_code = %s
			    ", $master_page_id, $master_page_id, 'post_page', 'post_page', $current_lang ) );

				if ( ! $duplicate_page_id ) {
					return $url;
				}
				return apply_filters( 'wpml_permalink', $url, $current_lang );
			}

			return $url;
		}

		/**
		 * @param $room
		 * @param $args
		 *
		 * @return bool
		 */
		public function query_search_parser( $room, $args ) {
			$cap_id = get_post_meta( $room->ID, '_hb_room_origin_capacity', true );

			if ( $cap_id ) {
				$qty = get_term_meta( $cap_id, 'hb_max_number_of_adults', true );
				if ( $qty < $args['adults'] ) {
					return false;
				}
			}

			return $room;
		}

		public function get_available_room( $room_id ) {
			if ( $duplicate_id = $this->get_object_default_language( $room_id, get_post_type( $room_id ), true, $this->default_language_code ) ) {
				return $duplicate_id;
			}

			return $room_id;
		}

		public function woo_cart_item_name( $name, $cart_item, $cart_item_key ) {
			if ( $duplicate_id = $this->get_object_default_language( $cart_item['product_id'], get_post_type( $cart_item['product_id'] ), true, $this->current_language_code ) ) {
				return get_the_title( $duplicate_id );
			}

			return $name;
		}

		/**
		 * @param $name
		 * @param $id
		 *
		 * @return string
		 */
		public function item_cart_name( $name, $id ) {
			if ( $duplicate_id = $this->get_object_default_language( $id, get_post_type( $id ), true, $this->current_language_code ) ) {
				return get_the_title( $duplicate_id );
			}

			return $name;
		}

		/**
		 * Filter product in thank you page, admin order details.
		 *
		 * @param $product_id
		 *
		 * @return mixed
		 */
		public function filter_room_id( $product_id ) {

			$post_type = get_post_type( $product_id );
			if ( $post_type != 'hb_room' ) {
				return $product_id;
			}

			$duplicate_product_id = apply_filters( 'wpml_object_id', $product_id, $post_type, true );
			if ( $duplicate_product_id ) {
				return $duplicate_product_id;
			}

			return $product_id;
		}

		public function filter_extra_id( $product_id ) {
			$post_type = get_post_type( $product_id );
			if ( $post_type != 'hb_extra_room' ) {
				return $product_id;
			}

			$duplicate_product_id = apply_filters( 'wpml_object_id', $product_id, $post_type, false, $this->current_language_code );

			if ( $duplicate_product_id ) {
				return $duplicate_product_id;
			}

			return $product_id;
		}

		/**
		 * Init.
		 */
		public function init() {
			$cart = WP_Hotel_Booking::instance()->cart;
			/**
			 * @var $cart WPHB_Cart
			 */
			$sessions = $cart->sessions;

			if ( ! $sessions->session ) {
				return;
			}

			foreach ( $sessions->session as $cart_id => $param ) {

				$post_type = get_post_type( $param['product_id'] );

				if ( $id = $this->get_object_default_language( $param['product_id'], $post_type, true, $this->current_language_code ) ) {
					$param['product_id'] = $id;
				}

				$qty = isset( $param['quantity'] ) ? absint( $param['quantity'] ) : 1;
				unset( $param['quantity'] );
				$cart->remove_cart_item( $cart_id );
				$cart->add_to_cart( $param['product_id'], $param, $qty );
			}
		}

		/**
		 * @param $extra_id
		 * @param $param
		 * @param $qty
		 */
		public function add_extra_to_cart( $extra_id, $param, $qty ) {
			global $wpdb;
			$duplicate_item_ids = $wpdb->get_results(
				$wpdb->prepare(
					"SELECT duplicate.element_id FROM {$wpdb->prefix}icl_translations AS duplicate
						  	INNER JOIN {$wpdb->prefix}icl_translations AS source ON duplicate.trid = source.trid
							WHERE source.element_id = %d AND duplicate.element_type = %s AND duplicate.language_code != %s",
					$extra_id, "post_hb_extra_room", $this->current_language_code ), ARRAY_A );

			foreach ( $duplicate_item_ids as $duplicate_item ) {
				$param['product_id'] = $duplicate_item['element_id'];
				$cart                = WP_Hotel_Booking::instance()->cart;
				/**
				 * @var $cart WPHB_Cart
				 */
				$cart->add_to_cart( $duplicate_item['element_id'], $param, $qty );
			}
		}

		/**
		 * @param $condition
		 * @param $extra
		 *
		 * @return bool
		 */
		public function filter_extra( $condition, $extra ) {
			$post_language_information = wpml_get_language_information( '', $extra->ID );
			if ( is_array( $post_language_information ) && $post_language_information['language_code'] != $this->current_language_code ) {
				return false;
			}

			return $condition;
		}

		/**
		 * Checkout wpml lang field
		 */
		public function checkout_wpml_lang_field() { ?>
            <input type="hidden" name="wpml_language" value="<?php echo $this->current_language_code; ?>">
		<?php }

		/**
		 * @param $url       | master thank you page url with args
		 * @param $permalink | master thank you page url without args
		 * @param $master_page_id
		 * @param $booking_id
		 * @param $booking_key
		 *
		 * @return string
		 */
		public function thank_you_page_url( $url, $permalink, $master_page_id, $booking_id, $booking_key ) {

			$current_lang = isset( $_POST['wpml_language'] ) ? $_POST['wpml_language'] : ICL_LANGUAGE_CODE;

			if ( ! is_user_logged_in() ) {
				if ( $current_lang != $this->default_language_code ) {

					global $wpdb;

					$duplicate_page_id = $wpdb->get_var(
						$wpdb->prepare( "
				SELECT duplicate_page.element_id FROM {$wpdb->prefix}icl_translations master_page
			    INNER JOIN {$wpdb->prefix}icl_translations duplicate_page ON master_page.trid = duplicate_page.trid
			    WHERE 
			  		master_page.element_id = %d 
			  		AND duplicate_page.element_id != %d
			    	AND master_page.element_type = %s
			    	AND duplicate_page.element_type = %s
			    	AND duplicate_page.language_code = %s
			    ", $master_page_id, $master_page_id, 'post_page', 'post_page', $current_lang ) );

					if ( ! $duplicate_page_id ) {
						return $url;
					}

					return add_query_arg( array(
						'booking' => $booking_id,
						'key'     => $booking_key
					), apply_filters( 'wpml_permalink', $permalink, $current_lang ) );
				}
			}

			return $url;
		}

		/**
		 * Update cap.
		 *
		 * @param $master_post_id
		 * @param $lang
		 * @param $post_array
		 * @param $id
		 */
		public function update_room_meta( $master_post_id, $lang, $post_array, $id ) {
			if ( ( get_post_type( $master_post_id ) == 'hb_room' ) ) {

				// update capacity
				if ( $cap_id = get_post_meta( $master_post_id, '_hb_room_capacity', true ) ) {
					$cap = $this->get_room_capacity( $cap_id, $this->default_language_code, $lang );
					update_post_meta( $id, '_hb_room_capacity', $cap );
					update_post_meta( $id, '_hb_room_origin_capacity', $this->get_room_capacity( $cap_id, $this->default_language_code ) );
				}

				if ( $extra = get_post_meta( $master_post_id, '_hb_room_extra' ) ) {
					update_post_meta( $id, '_hb_room_extra', array() );
				}
			}
		}

		/**
		 * Get cap current lang from default lang.
		 *
		 * @param $default_cap
		 * @param $default_lang
		 * @param $current_lang
		 *
		 * @return null|string
		 */
		private function get_room_capacity( $default_cap, $default_lang, $current_lang = null ) {
			global $wpdb;

			if ( ! $current_lang ) {
				return $default_cap;
			}

			$current_cap = $wpdb->get_var(
				$wpdb->prepare( "
				SELECT cap_current.element_id FROM {$wpdb->prefix}icl_translations cap_current
			    INNER JOIN {$wpdb->prefix}icl_translations cap_default ON cap_default.trid = cap_current.trid
			    WHERE 
			    cap_default.element_id = %d AND cap_default.language_code = %s AND cap_current.language_code = %s AND cap_current.element_type = %s
			    ", (int) $default_cap, $default_lang, $current_lang, 'tax_hb_room_capacity' ) );

			return $current_cap ? $current_cap : $default_cap;
		}


		/**
		 * Parse number rooms available.
		 *
		 * @param $room
		 *
		 * @return mixed
		 */
		public function parse_available_rooms( $room ) {
			global $wpdb;
			$id = $room->ID;

			$check_in_date  = strtotime( $room->get_data( 'check_in_date' ) );
			$check_out_date = strtotime( $room->get_data( 'check_out_date' ) );

			$trid = $wpdb->get_results(
				$wpdb->prepare( "
				SELECT room_lang.element_id FROM {$wpdb->prefix}icl_translations room_lang
			    INNER JOIN {$wpdb->prefix}icl_translations room_sourse ON room_sourse.trid = room_lang.trid
			    WHERE 
			    room_sourse.element_id = %d AND room_lang.element_id != %d
			    ", $id, $id ),
				ARRAY_N );

			$except_ids = array();
			if ( $trid ) {
				foreach ( $trid as $id ) {
					$except_ids[] = implode( ", ", $id );
				}
			}
			$except_ids = implode(', ', $except_ids);

			/*$booked = $wpdb->get_var( $wpdb->prepare( "
			SELECT COUNT( DISTINCT product.hotel_booking_order_item_id ) FROM $wpdb->hotel_booking_order_itemmeta AS product
			LEFT JOIN $wpdb->hotel_booking_order_itemmeta AS check_in ON product.hotel_booking_order_item_id = check_in.hotel_booking_order_item_id
			LEFT JOIN $wpdb->hotel_booking_order_itemmeta AS check_out ON product.hotel_booking_order_item_id = check_out.hotel_booking_order_item_id
			LEFT JOIN $wpdb->hotel_booking_order_items AS items ON product.hotel_booking_order_item_id = items.order_item_id
			LEFT JOIN {$wpdb->posts} AS booking ON booking.ID = items.order_id
			WHERE 
			(product.meta_key = 'product_id' AND product.meta_value IN (" . esc_sql($except_ids) . "))
			AND ( ( check_in.meta_key = 'check_in_date' AND check_out.meta_key = 'check_out_date' AND check_out.meta_value >= %d AND check_in.meta_value <= %d ) 
					OR ( check_in.meta_key = 'check_in_date' AND check_in.meta_key >= %d AND check_in.meta_key <= %d  )
			)
			AND booking.post_status IN ( %s, %s, %s )
		", $check_in_date, $check_in_date, $check_in_date, $check_out_date, 'hb-completed', 'hb-processing', 'hb-pending' ) );*/

			$new_query = $wpdb->prepare( "
			SELECT product.hotel_booking_order_item_id FROM $wpdb->hotel_booking_order_itemmeta AS product
			LEFT JOIN $wpdb->hotel_booking_order_itemmeta AS check_in ON product.hotel_booking_order_item_id = check_in.hotel_booking_order_item_id
			LEFT JOIN $wpdb->hotel_booking_order_itemmeta AS check_out ON product.hotel_booking_order_item_id = check_out.hotel_booking_order_item_id
			LEFT JOIN $wpdb->hotel_booking_order_items AS items ON product.hotel_booking_order_item_id = items.order_item_id
			LEFT JOIN {$wpdb->posts} AS booking ON booking.ID = items.order_id
			WHERE 
			(product.meta_key = 'product_id' AND product.meta_value IN (" . esc_sql($except_ids) . "))
			AND ( ( check_in.meta_key = 'check_in_date' AND check_out.meta_key = 'check_out_date' )
            			AND ( ( check_in.meta_value >= %d AND check_in.meta_value < %d )
						OR 	( check_out.meta_value > %d AND check_out.meta_value < %d )
						OR 	( check_in.meta_value <= %d AND check_out.meta_value > %d ) )
			)
			AND booking.post_status IN ( %s, %s, %s )
		", $check_in_date, $check_out_date, $check_in_date, $check_out_date, $check_in_date, $check_out_date, 'hb-completed', 'hb-processing', 'hb-pending' );
        	$list_order_ids = $wpdb->get_results($new_query);
			$booked = 0;
			if( !empty($list_order_ids) ){
			    foreach ( $list_order_ids as $b_o_id ) {
				    $booked += hb_get_order_item_meta( $b_o_id->hotel_booking_order_item_id, 'qty' );
                }
            }

			$room->post->available_rooms = $room->post->available_rooms - $booked;

			return $room;
		}

		/**
		 * Get default post_id, capacity, room_type by origin post_ID || term_ID
		 *
		 * @param null   $id
		 * @param string $type
		 * @param bool   $default
		 * @param bool   $lang
		 *
		 * @return int|null
		 */
		public function get_object_default_language( $id = null, $type = 'hb_room', $default = false, $lang = false ) {
			if ( ! $id ) {
				return false;
			}
			if ( ! $lang ) {
				$lang = $this->default_language_code;
			}

			return icl_object_id( $id, $type, $default, $lang );
		}

		/**
		 * Disable some attributes of room setting in other language post.
		 *
		 * @param $fields
		 *
		 * @return mixed
		 */
		public function disable_change_room_attributes( $fields ) {
			foreach ( $fields as $k => $field ) {
				if ( in_array( $field['name'], array( 'num_of_rooms', 'room_capacity', 'max_child_per_room' ) ) ) {
					$fields[ $k ]['attr']['disabled'] = 'disabled';
				}
			}

			return $fields;
		}

		/**
		 * Disable some attributes of extra setting in other language post.
		 *
		 * @param $fields
		 *
		 * @return mixed
		 */
		public function disable_change_extra_attributes( $fields ) {
			foreach ( $fields as $k => $field ) {
				if ( in_array( $field['name'], array( 'price', 'respondent', 'required' ) ) ) {
					$fields[ $k ]['attr']['disabled'] = 'disabled';
				}
			}

			return $fields;
		}

		/**
		 * Disable some attributes of coupon setting in other language post.
		 *
		 * @param $fields
		 *
		 * @return mixed
		 */
		public function disable_change_coupon_attributes( $fields ) {
			foreach ( $fields as $k => $field ) {
				if ( $field['name'] !== 'coupon_description' ) {
					$fields[ $k ]['attr']['disabled'] = 'disabled';
				}
			}

			return $fields;
		}

		/**
		 * Filter dropdown rooms.
		 *
		 * @param $posts
		 *
		 * @return array
		 */
		public function hotel_booking_rooms_dropdown( $posts ) {

			$rooms = array();
			foreach ( $posts as $post ) {
				$id      = $post->ID;
				$room_id = $this->get_object_default_language( $id );
				if ( $room_id && ! isset( $rooms[ $room_id ] ) ) {
					$rooms[ $room_id ] = get_post( $room_id );
				}
			}

			return $rooms;
		}

		/**
		 * Capacity ordering.
		 *
		 * @param $content
		 * @param $column_name
		 * @param $term_id
		 *
		 * @return string
		 */
		public function hb_capacity_order_attr( $content, $column_name, $term_id ) {
			if ( $this->current_language_code === $this->default_language_code ) {
				return $content;
			}
			$taxonomy = sanitize_text_field( $_REQUEST['taxonomy'] );
			$term_id  = $this->get_object_default_language( $term_id, 'hb_room_capacity' );
			$term     = get_term( $term_id, $taxonomy );
			switch ( $column_name ) {
				case 'ordering':
					$content = sprintf( '<input class="hb-number-field" type="number" name="%s_ordering[%d]" value="%d" size="3" disabled />', $taxonomy, $term_id, $term->term_group );
					break;
				case 'capacity':
					$capacity = get_term_meta( $term_id, 'hb_max_number_of_adults', true );
					$content  = '<input class="hb-number-field" type="number" name="' . $taxonomy . '_capacity[' . $term_id . ']" value="' . $capacity . '" size="2" disabled />';
					break;
				default:
					break;
			}

			return $content;
		}

		/**
		 * Get pages.
		 *
		 * @param $pages
		 *
		 * @return array|null|object
		 */
		public function hb_get_pages( $pages ) {
			global $wpdb;
			$sql = $wpdb->prepare( "
				SELECT DISTINCT page.ID, page.post_title FROM $wpdb->posts as page
				INNER JOIN {$wpdb->prefix}icl_translations as wpml_translation ON page.ID = wpml_translation.element_id AND wpml_translation.language_code = %s
				WHERE page.post_type = %s
					AND page.post_status = %s
			", $this->default_language_code, 'page', 'publish' );

			return $wpdb->get_results( $sql );
		}

		/**
		 * @param $page_id
		 *
		 * @return int
		 */
		public function hb_get_page_id( $page_id ) {
			return $this->get_object_default_language( $page_id, 'page', true, $this->current_language_code );
		}

		/**
		 * The content setup shortcode atts check available.
		 *
		 * @param $content
		 *
		 * @return string
		 */
		public function the_content( $content ) {
			global $post;
			if ( is_page() && ( $this->get_object_default_language( $post->ID, 'page', true ) == hb_get_page_id( 'search' ) || has_shortcode( $content, 'hotel_booking' ) ) ) {

				// params search result
				$page       = hb_get_request( 'hotel-booking' );
				$start_date = hb_get_request( 'check_in_date' );
				$end_date   = hb_get_request( 'check_out_date' );
				$adults     = hb_get_request( 'adults' );
				$max_child  = hb_get_request( 'max_child' );

				$content = '[hotel_booking page="' . $page . '" check_in_date="' . $start_date . '" check_in_date="' . $end_date . '" adults="' . $adults . '" max_child="' . $max_child . '"]';
			}

			return $content;
		}

		/**
		 * Filter search query.
		 *
		 * @param $query
		 * @param $args
		 *
		 * @return string
		 */
		public function hb_search_query( $query, $args ) {
			global $wpdb;
			$blocked = $wpdb->prepare( "
				SELECT COALESCE( COUNT( blocked_time.meta_value ), 0 )
				FROM $wpdb->postmeta AS blocked_post
				INNER JOIN $wpdb->posts AS calendar ON calendar.ID = blocked_post.meta_value
				INNER JOIN $wpdb->postmeta AS blocked_time ON blocked_time.post_id = calendar.ID
				WHERE
					blocked_post.post_id = rooms.ID
					AND calendar.post_type = %s
					AND calendar.post_status = %s
					AND blocked_post.meta_key = %s
					AND blocked_time.meta_key = %s
					AND blocked_time.meta_value >= %d
					AND blocked_time.meta_value <= %d
			", 'hb_blocked', 'publish', 'hb_blocked_id', 'hb_blocked_time', $args['check_in'], $args['check_out'] );
			$not     = $wpdb->prepare( "
				(
					SELECT COALESCE( SUM( meta.meta_value ), 0 ) FROM {$wpdb->hotel_booking_order_itemmeta} AS meta
						LEFT JOIN {$wpdb->hotel_booking_order_items} AS order_item ON order_item.order_item_id = meta.hotel_booking_order_item_id AND meta.meta_key = %s
						LEFT JOIN {$wpdb->hotel_booking_order_itemmeta} AS itemmeta ON order_item.order_item_id = itemmeta.hotel_booking_order_item_id AND itemmeta.meta_key = %s
						LEFT JOIN {$wpdb->hotel_booking_order_itemmeta} AS checkin ON order_item.order_item_id = checkin.hotel_booking_order_item_id AND checkin.meta_key = %s
						LEFT JOIN {$wpdb->hotel_booking_order_itemmeta} AS checkout ON order_item.order_item_id = checkout.hotel_booking_order_item_id AND checkout.meta_key = %s
						LEFT JOIN {$wpdb->posts} AS booking ON booking.ID = order_item.order_id
					WHERE
							itemmeta.meta_value = rooms.ID
						AND (
								( checkin.meta_value >= %d AND checkin.meta_value < %d )
							OR 	( checkout.meta_value > %d AND checkout.meta_value <= %d )
							OR 	( checkin.meta_value <= %d AND checkout.meta_value > %d )
						)
						AND booking.post_type = %s
						AND booking.post_status IN ( %s, %s, %s )
				)
			", 'qty', 'product_id', 'check_in_date', 'check_out_date', $args['check_in'], $args['check_out'], $args['check_in'], $args['check_out'], $args['check_in'], $args['check_out'], 'hb_booking', 'hb-completed', 'hb-processing', 'hb-pending'
			);

			$query = $wpdb->prepare( "
				SELECT rooms.*, ( number.meta_value - {$not} ) AS available_rooms, ($blocked) AS blocked FROM $wpdb->posts AS rooms
					LEFT JOIN $wpdb->postmeta AS number ON rooms.ID = number.post_id AND number.meta_key = %s
					LEFT JOIN {$wpdb->postmeta} AS pm1 ON pm1.post_id = rooms.ID AND pm1.meta_key = %s
					LEFT JOIN {$wpdb->termmeta} AS term_cap ON term_cap.term_id = pm1.meta_value AND term_cap.meta_key = %s
					LEFT JOIN {$wpdb->postmeta} AS pm2 ON pm2.post_id = rooms.ID AND pm2.meta_key = %s
					LEFT JOIN {$wpdb->prefix}icl_translations AS wpml_translation ON rooms.ID = wpml_translation.element_id
				WHERE
					rooms.post_type = %s
					AND rooms.post_status = %s
					AND term_cap.meta_value >= %d
					AND pm2.meta_value >= %d
					AND wpml_translation.language_code = %s
				GROUP BY rooms.post_name
				HAVING ( available_rooms > 0 AND blocked = 0 )
				ORDER BY term_cap.meta_value ASC
			", '_hb_num_of_rooms', '_hb_room_origin_capacity', 'hb_max_number_of_adults', '_hb_max_child_per_room', 'hb_room', 'publish', $args['adults'], $args['child'], $this->current_language_code );

			return $query;
		}

		/**
		 * Get pricing plans default room language.
		 *
		 * @param $plans
		 * @param $id
		 *
		 * @return mixed
		 */
		public function hb_pricing_plans( $plans, $id ) {
			remove_filter( 'hb_room_get_pricing_plans', array( $this, 'hb_pricing_plans' ), 10 );
			if ( ( $primary_room_id = $this->get_object_default_language( $id, 'hb_room' ) ) && $primary_room_id != $id ) {
				$plans = hb_room_get_pricing_plans( $primary_room_id );
			}
			add_filter( 'hb_room_get_pricing_plans', array( $this, 'hb_pricing_plans' ), 10, 2 );

			return $plans;
		}

		/**
		 * @param $transaction
		 *
		 * @return mixed
		 */
		public function hb_generate_transaction_object( $transaction ) {
			$transaction->booking_info['_hb_wpml_language'] = $this->current_language_code;

			return $transaction;
		}

		/**
		 * Cart generate booking item params.
		 *
		 * @param $params
		 * @param $product
		 *
		 * @return mixed
		 */
		public function hb_generate_transaction_object_room( $params, $product ) {
			if ( get_post_type( $params['product_id'] ) == 'hb_room' ) {
				$params['product_id'] = $this->get_object_default_language( $params['product_id'], $product->post->post_type );
			}

			return $params;
		}

		/**
		 * @param $max
		 *
		 * @return mixed
		 */
		public function max_capacity_of_rooms( $max ) {
			$terms = get_terms( 'hb_room_capacity', array( 'hide_empty' => false ) );
			if ( $terms ) {
				foreach ( $terms as $term ) {
					$default_term = $this->get_object_default_language( $term->term_id, 'hb_room_capacity', true );
					$cap          = get_term_meta( $default_term, 'hb_max_number_of_adults', true );
					// @since  1.1.2, use term meta
					if ( ! $cap ) {
						$cap = get_option( "hb_taxonomy_capacity_{$default_term}" );
					}
					if ( intval( $cap ) > $max ) {
						$max = $cap;
					}
				}
			}

			return $max;
		}
	}
}

new HB_WPML_Support();
