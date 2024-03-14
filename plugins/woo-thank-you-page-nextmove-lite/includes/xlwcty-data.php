<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class XLWCTY_Data
 * @package NextMove
 * @author XlPlugins
 */
class XLWCTY_Data {

	private static $ins = null;
	public $page_id = false;
	public $page_link = false;
	private $order_id = false;
	private $order = false;
	private $page_component_raw_meta = false;
	private $page_component_meta = array();
	private $page_layout = false;
	private $page_layout_info = false;
	private $options = null;

	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * @param $order_id
	 * @param bool $return_key
	 * @param bool $skip_rules
	 *
	 * @return $this|void
	 */
	public function setup_thankyou_post( $order_id, $skip_rules = false ) {

		if ( ! is_numeric( $order_id ) ) {
			return;
		}
		$this->load_order( $order_id );

		$args = array(
			'post_type'        => XLWCTY_Common::get_thank_you_page_post_type_slug(),
			'post_status'      => 'publish',
			'nopaging'         => true,
			'meta_key'         => '_xlwcty_menu_order',
			'orderby'          => 'meta_value_num',
			'order'            => 'ASC',
			'fields'           => 'ids',
			'suppress_filters' => false,
		);

		$xl_transient_obj = XL_Transient::get_instance();
		$xl_cache_obj     = XL_Cache::get_instance();

		$key = 'xlwcty_instances';

		// handling for WPML
		if ( defined( 'ICL_LANGUAGE_CODE' ) && ICL_LANGUAGE_CODE !== '' ) {
			$key .= '_' . ICL_LANGUAGE_CODE;
		}

		$contents = array();
		do_action( 'xlwcty_before_query', $order_id );

		/**
		 * Setting xl cache and transient for NextMove pages query
		 */
		$cache_data = $xl_cache_obj->get_cache( $key, 'nextmove' );
		if ( false !== $cache_data ) {
			$contents = $cache_data;
		} else {
			$transient_data = $xl_transient_obj->get_transient( $key, 'nextmove' );

			if ( false !== $transient_data ) {
				$contents = $transient_data;
			} else {
				$query_result = new WP_Query( $args );
				if ( $query_result instanceof WP_Query && $query_result->have_posts() ) {
					$contents = $query_result->posts;
					$xl_transient_obj->set_transient( $key, $contents, 21600, 'nextmove' );
				}
			}
			$xl_cache_obj->set_cache( $key, $contents, 'nextmove' );
		}

		do_action( 'xlwcty_after_query', $order_id );

		$contents = apply_filters( 'xlwcty_before_rules_validation', $contents, $order_id, $this, $skip_rules );

		if ( is_array( $contents ) && count( $contents ) > 0 ) {
			foreach ( $contents as $content_single ) {

				/**
				 * post instance extra checking added as some plugins may modify wp_query args on pre_get_posts filter hook
				 */
				$content_id = ( $content_single instanceof WP_Post && is_object( $content_single ) ) ? $content_single->ID : $content_single;

				if ( $skip_rules || XLWCTY_Common::match_groups( $content_id, $order_id ) ) {
					$custom_pages = get_option( 'xlwcty_custom_thank_you_pages', array() );
					if ( isset( $custom_pages[ $content_id ] ) && ! empty( $custom_pages[ $content_id ] ) ) {
						$content_id = $custom_pages[ $content_id ];
					}

					$this->page_id   = $content_id;
					$this->page_link = get_permalink( $content_id );

					break;
				}
			}
		}

		return $this;
	}

	public function load_order( $order_id = 0 ) {
		if ( $order_id instanceof WP ) {
			$order_id = 0;
		}

		if ( 0 === $order_id ) {
			$order_id = ( isset( $_GET['order_id'] ) && ( $_GET['order_id'] !== '' ) ) ? wc_clean( $_GET['order_id'] ) : 0;
		}
		if ( 0 !== $order_id ) {
			$this->order_id = $order_id;
			$this->order    = wc_get_order( $order_id );
		}
	}

	public function load_order_wp( $order_id = 0 ) {
		if ( ! is_order_received_page() ) {
			return;
		}

		if ( $order_id instanceof WP ) {
			$order_id = 0;
		}

		if ( 0 === $order_id ) {
			$order_id = ( isset( $_GET['order_id'] ) && ( $_GET['order_id'] !== '' ) ) ? wc_clean( $_GET['order_id'] ) : 0;
		}

		$this->order_id = $order_id;
		$this->order    = wc_get_order( $order_id );
	}

	public function get_page_link() {

		return $this->page_link;
	}

	public function load_thankyou_metadata() {

		global $wpdb;
		$xl_cache_obj     = XL_Cache::get_instance();
		$xl_transient_obj = XL_Transient::get_instance();

		if ( false === $this->page_id ) {
			return;
		}
		$meta_query = apply_filters( 'xlwcty_product_meta_query', $wpdb->prepare( "SELECT meta_key,meta_value  FROM $wpdb->postmeta WHERE post_id = %d AND meta_key LIKE %s", $this->page_id, '%_xlwcty_%' ) );
		$cache_key  = 'xlwcty_thankyou_meta_' . $this->page_id;

		/**
		 * Setting xl cache and transient for NextMove page meta
		 */
		$cache_data = $xl_cache_obj->get_cache( $cache_key, 'nextmove' );
		if ( false !== $cache_data ) {
			$parseObj = $cache_data;
		} else {
			$transient_data = $xl_transient_obj->get_transient( $cache_key, 'nextmove' );

			if ( false !== $transient_data ) {
				$parseObj = $transient_data;
			} else {
				$get_product_xlwcty_meta = $wpdb->get_results( $meta_query, ARRAY_A );
				$product_meta            = XLWCTY_Common::get_parsed_query_results_meta( $get_product_xlwcty_meta );
				$parseObj                = $product_meta;
				$xl_transient_obj->set_transient( $cache_key, $parseObj, 21600, 'nextmove' );
			}
			$xl_cache_obj->set_cache( $cache_key, $parseObj, 'nextmove' );
		}

		$this->page_component_raw_meta = $parseObj;

		if ( isset( $this->page_component_raw_meta['_xlwcty_builder_template'] ) ) {
			$this->page_layout = $this->page_component_raw_meta['_xlwcty_builder_template'];
		}

		if ( isset( $this->page_component_raw_meta['_xlwcty_builder_layout'] ) ) {
			$layout_info            = $this->page_component_raw_meta['_xlwcty_builder_layout'];
			$this->page_layout_info = json_decode( $layout_info, true );
		}

		$components = xlwcty_components::retrieve_components();

		foreach ( $components as $slug => $component ) {
			$component_properties = $component->get_component();
			if ( $component->has_multiple_fields() ) {
				for ( $i = 1; $i <= $component_properties['fields']['count']; $i ++ ) {
					$this->parse_key_value( $parseObj, $component, $i );
					$this->page_component_meta[ $component->get_slug() ][ $i ] = wp_parse_args( $this->page_component_meta[ $component->get_slug() ][ $i ], $component->get_defaults() );
				}
			} else {
				$this->parse_key_value( $parseObj, $component );
				$this->page_component_meta[ $component->get_slug() ] = wp_parse_args( $this->page_component_meta[ $component->get_slug() ], $component->get_defaults() );
			}
		}

		do_action( 'xlwcty_page_meta_setup_completed', $this );
	}

	/**
	 * Parse and prepare data for single trigger
	 *
	 * @param $data Array Options data
	 * @param $trigger String Trigger slug
	 *
	 */
	public function parse_key_value( $data, $trigger, $index = false ) {
		if ( false === $index ) {
			$this->page_component_meta[ $trigger->get_slug() ] = array();

			foreach ( $trigger->fields as $key => $meta_key ) {

				if ( isset( $data[ $meta_key ] ) ) {

					$this->page_component_meta[ $trigger->get_slug() ][ $key ] = $data[ $meta_key ];
				}
			}
		} else {
			$this->page_component_meta[ $trigger->get_slug() ][ $index ] = array();
			foreach ( $trigger->fields as $key => $meta_key ) {
				if ( isset( $data[ $meta_key . '_' . $index ] ) ) {
					if ( false === $index ) {
						$this->page_component_meta[ $trigger->get_slug() ][ $key ] = $data[ $meta_key . '_' . $index ];
					} else {
						$this->page_component_meta[ $trigger->get_slug() ][ $index ][ $key ] = $data[ $meta_key . '_' . $index ];
					}
				}
			}
		}
	}

	public function get_meta( $key = '', $mode = 'parsed' ) {
		$prop = ( 'raw' === $mode ) ? $this->page_component_raw_meta : $this->page_component_meta;
		if ( $prop && '' === $key ) {
			return $prop;
		}
		if ( $prop && ! empty( $key ) ) {
			return ( isset( $prop[ $key ] ) ? $prop[ $key ] : '' );
		}

		return '';
	}

	public function get_page() {
		return $this->page_id;
	}

	public function get_order( $id = 0 ) {
		if ( 0 !== $id ) {
			$this->load_order( $id );
		}

		return $this->order;
	}

	public function reset_order( $order = 0 ) {
		if ( 0 === $order ) {
			$this->order = false;
		}

		$this->order = $order;
	}

	public function set_page( $id = null ) {
		global $post;

		if ( $post instanceof WP_Post && XLWCTY_Common::get_thank_you_page_post_type_slug() === $post->post_type ) {
			$this->page_id = $post->ID;
		}
	}

	public function get_layout() {
		return $this->page_layout;
	}

	public function set_layout( $layout ) {
		$this->page_layout = $layout;
	}

	public function get_layout_info() {
		return $this->page_layout_info;
	}

	public function set_layout_info( $data ) {
		$this->page_layout_info = $data;
	}

	public function setup_options() {
		if ( ! $this->options ) {
			$options = get_option( 'xlwcty_global_settings' );

			$this->options = wp_parse_args( $options, XLWCTY_Common::get_options_defaults() );

			/**
			 * Compatibility with WPML
			 */
			if ( function_exists( 'icl_t' ) ) {
				$translated_google_map_error_text      = icl_t( 'admin_texts_xlwcty_global_settings', '[xlwcty_global_settings]google_map_error_txt', $this->options['google_map_error_txt'] );
				$this->options['google_map_error_txt'] = $translated_google_map_error_text;
			}
		}
	}

	public function get_option( $key = '' ) {
		if ( '' !== $key ) {
			return ( isset( $this->options[ $key ] ) ? $this->options[ $key ] : '' );
		}

		return $this->options;
	}

}

if ( class_exists( 'XLWCTY_Data' ) ) {
	XLWCTY_Core::register( 'data', 'XLWCTY_Data' );
}

