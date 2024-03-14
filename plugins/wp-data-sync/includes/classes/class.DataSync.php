<?php
/**
 * DataSync
 *
 * Process post type data.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

use Monolog\Formatter\LogglyFormatter;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class DataSync {

	/**
	 * @var int
	 */

	private $api_item_id;

	/**
	 * @var int
	 */

	private $source_id;

	/**
	 * @var string
	 */

	private $source_name;

	/**
	 * @var bool|array
	 */

	private $primary_id = false;

	/**
	 * @var bool|int
	 */

	private $post_id = false;

    /**
     * @var string
     */

    private $post_type;

	/**
	 * @var bool
	 */

	private $is_accelerated = false;

	/**
	 * @var bool|array
	 */

	private $post_data = false;

	/**
	 * @var bool|array
	 */

	private $post_meta = false;

	/**
	 * @var bool|array
	 */

	private $taxonomies = false;

	/**
	 * @var bool|string
	 */

	private $wc_categories = false;

	/**
	 * @var bool|array
	 */

	private $featured_image = false;

	/**
	 * @var bool|array
	 */

	private $attributes = false;

	/**
	 * @var bool|array
	 */

	private $variations = false;

	/**
	 * @var bool|array
	 */

	private $gallery_images = false;

	/**
	 * @var bool|array
	 */

	private $gallery_details = false;

	/**
	 * @var array|bool
	 */

	private $attachment = false;

	/**
	 * @var bool|array
	 */

	private $selected_options = false;

	/**
	 * @var bool|array
	 */

	private $order_items = false;

    /**
     * @var array
     */

    private $wc_prices = [];

	/**
	 * @var bool|array
	 */

	private $integrations = false;

	/**
	 * @var bool
	 */

	private $is_new = false;

	/**
	 * DataSync constructor.
	 */

	public function __construct() {
		// Empty construct
	}

	/**
	 * Instance.
	 *
	 * @return DataSync
	 */

	public static function instance() {
		return new self();
	}

	/**
	 * Set Properties
	 *
	 * Set property values.
	 *
	 * @param $data
	 */

	public function set_properties( $data ) {

		if ( is_array( $data ) ) {

			foreach ( $data as $key => $value ) {
				$this->$key = apply_filters( "wp_data_sync_set_property_$key", $value );
			}

		}

    }

	/**
	 * Process request data.
	 *
	 * @return mixed
	 */

	public function process() {

		global $wpds_response, $process_id, $wpdb;
		
		$process_id = $this->get_process_id();

		// A primary ID is required!!
		if ( empty( $this->primary_id ) ) {
			$wpds_response['items'][ $process_id ]['error'] = 'Primary ID empty!!';
			return;
		}

        /**
         * Set the post ID.
         */
        $this->set_post_id();

        if ( empty( $this->post_id ) ) {
            $error_msg = __( 'Post ID failed!!', 'wp-data-sync' );

            if ( ! empty( $wpdb->last_error ) ) {
                $error_msg = $wpdb->last_error;
            }

            $wpds_response['items'][ $process_id ]['error'] = $error_msg;
            return;
        }

        $wpds_response['items'][ $process_id ]['post_id'] = $this->post_id;

        /**
         * Check the post type.
         */
        if ( empty( $this->post_type ) ) {

            if ( ! $this->is_accelerated ) {
                $wpds_response['items'][ $process_id ]['error'] = __( 'Post Type Required!!', 'wp-data-sync' );

                return;
            }

            $this->set_post_type();

        }

        $wpds_response['items'][ $process_id ]['post_type'] = $this->post_type;

        /**
         * Check the post sync status.
         */
		if ( get_post_meta( $this->post_id, WPDSYNC_SYNC_DISABLED, true ) ) {
			$wpds_response['items'][ $process_id ]['error'] = __( 'Post Sync Status Disabled!!', 'wp-data-sync' );
			$wpds_response['items'][ $process_id ]['status'] = 'disabled';
			return;
		}

        /**
         * Maybe trash the post.
         */
		if ( $this->maybe_trash_post() ) {
			$wpds_response['items'][ $process_id ]['trash'] = __( 'Post Trashed', 'wp-data-sync' );
			return;
		}

        /**
         * Process the post data.
         */
		if ( $this->post_data ) {

			do_action( 'wp_data_sync_before_post_data', $this->post_data, $this->post_id, $this );

			$this->post_data();

			do_action( 'wp_data_sync_after_post_data', $this->post_data, $this->post_id, $this );

		}

		if ( $this->post_meta ) {

			$this->post_meta();

			do_action( 'wp_data_sync_post_meta', $this->post_id, $this->post_meta, $this );

		}

		if ( $this->taxonomies ) {

			$this->taxonomy();

			do_action( 'wp_data_sync_taxonomies', $this->post_id, $this->taxonomies, $this );

			$this->reset_term_taxonomy_count();

		}

		if ( $this->featured_image ) {

			$this->set_attachment( $this->featured_image  );
			$this->featured_image();

			do_action( 'wp_data_sync_featured_image', $this->post_id, $this->featured_image, $this );

		}

		if ( $this->gallery_images ) {
			$this->gallery_images();
		}

		if ( $this->attachment ) {
			$this->attachment();
		}

		if ( $this->integrations ) {
			$this->integrations();
		}

		do_action( 'wp_data_sync_after_process', $this->post_id, $this );

		$this->update_date();

		return true;

	}

	/**
	 * Get Process ID
     *
	 * @return int
	 */

	public function get_process_id() {
		
		global $process_id;
		
		if ( ! isset( $process_id ) ) {
			return 1;
		}

		$process_id++;

		return $process_id;

	}

	/**
	 * Set post id.
	 *
	 * @param bool $post_id
	 */

	public function set_post_id( $post_id = false ) {

		if ( ! $post_id ) {
            $post_id = $this->fetch_post_id();
		}

		$this->post_id = $post_id;

	}

    /**
     * Set Post Type
     *
     * @param string $post_type
     *
     * @return viod
     */

    public function set_post_type( $post_type = false ) {

        if ( ! $post_type ) {
            $post_type = $this->get_post_type();
        }

        $this->post_type = $post_type;

    }

	/**
	 * Set post data.
	 *
	 * @param $post_data
	 */

	public function set_post_data( $post_data ) {
		$this->post_data = $post_data;
	}

	/**
	 * Set post meta.
	 *
	 * @param $post_meta
	 */

	public function set_post_meta( $post_meta ) {
		$this->post_meta = $post_meta;
	}

	/**
	 * Set taxonomies.
	 *
	 * @param $taxonomies
	 */

	public function set_taxonomies( $taxonomies ) {
		$this->taxonomies = $taxonomies;
	}

	/**
	 * Set WooCommerce Categories
	 *
	 * @param string $wc_categories
	 *
	 * @return void
	 */

	public function set_wc_categories( $wc_categories ) {
		$this->wc_categories = $wc_categories;
	}

	/**
	 * Set featured image.
	 *
	 * @param $featured_image
	 */

	public function set_featured_image( $featured_image ) {
		$this->featured_image = $featured_image;
	}

	/**
	 * Set attachment.
	 *
	 * @param $attachment
	 */

	public function set_attachment( $attachment ) {
		$this->attachment = $attachment;
	}

	/**
	 * Set order items.
	 *
	 * @param $order_items
	 */

	public function set_order_items( $order_items ) {
		$this->order_items = $order_items;
	}

	/**
	 * Is new.
	 *
	 * @return bool
	 */

	public function get_is_new() {
		return $this->is_new;
	}

	/**
	 * Get API Item ID
	 *
	 * @return int
	 */

	public function get_api_item_id() {
		return $this->api_item_id;
	}

	/**
	 * @return int
	 */

	public function get_source_id() {
		return $this->source_id;
	}

	/**
	 * @return string
	 */
	
	public function get_source_name() {
		return $this->source_name;
	}

	/**
	 * Get the primary ID.
	 *
	 * @return int|bool
	 */

	public function get_primary_id() {
		return $this->primary_id;
	}

	/**
	 * Get the post ID.
	 *
	 * @return int|bool
	 */

	public function get_post_id() {
		return $this->post_id;
	}

	/**
	 * Get the post object.
	 *
	 * @return array|bool
	 */

	public function get_post_data() {
		return $this->post_data;
	}

	/**
	 * Get the post meta.
	 *
	 * @return array|bool
	 */

	public function get_post_meta() {
		return $this->post_meta;
	}

	/**
	 * Get Post Type
	 *
	 * @return string
	 */

	public function get_post_type() {

        if ( ! empty( $this->post_type ) ) {
            return $this->post_type;
        }

        if ( ! empty( $this->post_id ) ) {
            return get_post_type( $this->post_id );
        }

        return get_option( 'wp_data_sync_post_type', 'post' );

	}

	/**
	 * Get the taxonomies.
	 *
	 * @return array|bool
	 */

	public function get_taxonomies() {
		return $this->taxonomies;
	}

	/**
	 * Get WooCommerce Categories
	 *
	 * @return string|bool
	 */

	public function get_wc_categories() {
		return $this->wc_categories;
	}

	/**
	 * Get featured image.
	 *
	 * @return array|bool
	 */

	public function get_featured_image() {
		return $this->featured_image;
	}

	/**
	 * Get the attributes.
	 *
	 * @return array|bool
	 */

	public function get_attributes() {
		return apply_filters( 'wp_data_sync_product_attributes', $this->attributes, $this );
	}

	/**
	 * Get variations.
	 *
	 * @return mixed|bool
	 */

	public function get_variations() {
		return apply_filters( 'wp_data_sync_product_variations', $this->variations, $this );
	}

	/**
	 * Get gallery images.
	 *
	 * @return array|bool
	 */

	public function get_gallery_images() {
		return apply_filters( 'wp_data_sync_product_gallery_images', $this->gallery_images, $this );
	}

	/**
	 * Get gallery details.
	 *
	 * @return mixed|void
	 */

	public function get_gallery_details() {
		return apply_filters( 'wp_data_sync_product_gallery_details', $this->gallery_details, $this );
	}

	/**
	 * Get the attachment.
	 *
	 * @return bool|string
	 */

	public function get_attachment() {
		return $this->attachment;
	}

	/**
	 * Get selected options.
	 *
	 * Selected attribute options for WooCommerce variations.
	 *
	 * @return array|bool
	 */

	public function get_selected_options() {
		return apply_filters( 'wp_data_sync_procudt_variation_selected_options', $this->selected_options, $this );
	}

	/**
	 * Get order items.
	 *
	 * @return array|bool
	 */

	public function get_order_items() {
		return $this->order_items;
	}

	/**
	 * Get Integrations.
	 *
	 * @return array|bool
	 */

	public function get_integrations() {
		return $this->integrations;
	}

    /**
     * Get WooCommerce Prices
     *
     * @return array
     */

    public function get_wc_prices() {
        return apply_filters( 'wp_data_sync_get_wc_prices', $this->wc_prices, $this->post_meta, $this );
    }

	/**
	 * Fetch Post ID.
	 *
     * @since 1.0.0
     *        2.7.0 Use post type to find post ID.
     *        2.7.6 Revert and not use post type to find post ID. This is necessary
     *               since we cannot determine a product_variation with accelerated sync.
     *
	 * @return bool|int
	 */

	public function fetch_post_id() {

		global $wpdb;

		extract( $this->primary_id );

		if ( empty( $key ) || empty( $value ) ) {
			return false;
		}

		$sql = $wpdb->prepare(
			"
			SELECT post_id 
    		FROM $wpdb->postmeta
    		WHERE meta_key = %s 
      			AND meta_value = %s 
      		ORDER BY post_id DESC
			",
			esc_sql( $key ),
			esc_sql( $value )
		);

		$post_id = $wpdb->get_var( $sql );

		Log::write( 'fetch-post-id', [
			'is_accelerated' => $this->is_accelerated,
			'query'          => $sql,
			'result'         => $post_id,
			'error'          => $wpdb->last_error
		] );

		if ( empty( $post_id ) || is_wp_error( $post_id ) ) {

			// Do not create a new post if accelerated sync.
			if ( $this->is_accelerated ) {
				return false;
			}

			$this->is_new = true;

			return $this->insert_placeholder();

		}

		return (int) $post_id;

	}

	/**
	 * Fetch Post IDs.
	 *
     * @since 1.0.0
     *        2.7.0 Use post type to find post ID.
     *        2.7.7 Revert and not use post type to find post ID. This is necessary
     *               since we cannot determine a product_variation with accelerated sync.

     * @return bool|array
	 */

	public function fetch_post_ids() {

		global $wpdb;

		extract( $this->primary_id );

		if ( empty( $key ) || empty( $value ) ) {
			return false;
		}

		$post_ids = $wpdb->get_col( $wpdb->prepare(
			"
			SELECT post_id 
    		FROM $wpdb->postmeta
    		WHERE meta_key = %s 
      		    AND meta_value = %s 
			",
			esc_sql( $key ),
			esc_sql( $value )
		) );

		if ( empty( $post_ids ) || is_wp_error( $post_ids ) ) {
			return false;
		}

		return array_map( 'intval', $post_ids );

	}

	/**
	 * Insert a placeholder post.
	 *
	 * We insert a placeholder with WP function to insure the table columns have
	 * vaild values. Then we can update the values later if they are provided from the API.
	 *
	 * @return int|bool
	 *
	 * @since 1.10.0
	 */

	public function insert_placeholder() {

		$post_id = wp_insert_post( [
			'post_title'  => __( 'WP Data Sync Placeholder', 'wp-data-sync' ),
			'post_type'   => $this->get_post_type(),
			'post_status' => 'draft'
		] );

		if ( empty( $post_id ) || is_wp_error( $post_id ) ) {
			return false;
		}

		// Set the primary ID for the placeholder early in case request fails.
		update_post_meta( $post_id, $this->primary_id['key'], $this->primary_id['value'] );

		return $post_id;

	}

	/**
	 * Insert Post Row.
	 *
	 * Insert a row with a specific ID that
	 * does not already exist in the posts table.
	 *
	 * @param $post_id
	 *
	 * @return bool|false|int
	 */

	public function insert_post_row( $post_id ) {

		global $wpdb;

		$success = $wpdb->insert(
			$wpdb->posts,
			[
				'ID'                    => $post_id,
				'post_content'          => '',
				'post_title'            => '',
				'post_excerpt'          => '',
				'to_ping'               => '',
				'pinged'                => '',
				'post_content_filtered' => ''
			],
			[ '%d', '%s', '%s', '%s', '%s', '%s', '%s' ]
		);

		if ( empty( $success ) || is_wp_error( $success ) ) {

			Log::write( 'wpdb-error-insert-post-row',  $success);

			return false;

		}

		return $wpdb->insert_id;

	}

	/**
	 * Get the default value for a post object key.
	 */

	public function post_data_defaults() {

		$keys = $this->post_data_keys();

		foreach ( $keys as $key ) {

			if ( ! isset( $this->post_data[ $key ] ) ) {
				$this->post_data[ $key ] = get_option( "wp_data_sync_$key" );
			}

		}

	}

	/**
	 * Post data apply filter
	 *
	 * @since 1.9.10
	 */

	public function post_data_apply_filters() {

		$keys = $this->post_data_keys();

		foreach ( $keys as $key ) {

			$value = false;

			if ( isset( $this->post_data[ $key ] ) ) {
				$value = $this->post_data[ $key ];
			}

			$this->post_data[ $key ] = apply_filters( "wp_data_sync_{$key}", $value, $this->post_id, $this );

		}

		// Remove the false values.
		$this->post_data = array_filter( $this->post_data );

	}

	/**
	 * Post object keys.
	 *
	 * @return array
	 */

	public function post_data_keys() {

		$post_data_keys = [
			'post_title',
			'post_status',
			'post_author',
			'post_type',
			'post_date',
			'post_content',
			'post_excerpt',
			'post_password',
			'post_parent',
			'ping_status',
			'comment_status'
		];

		return apply_filters( 'wp_data_sync_post_data_keys', $post_data_keys );

	}

	/**
	 * Trash post.
	 *
	 * @return bool
	 */

	public function maybe_trash_post() {

		if ( ! isset( $this->post_data['post_status'] ) ) {
			return false;
		}

		if ( 0 < $this->post_id && 'trash' === $this->post_data['post_status'] ) {

			if ( Settings::is_checked( 'wp_data_sync_force_delete' ) ) {

				if ( wp_delete_post( $this->post_id, true ) ) {
					return true;
				}

			}

			if ( wp_trash_post( $this->post_id ) ) {
				return true;
			}

		}

		return false;

	}

	/**
	 * Post data.
	 *
	 * @return int|\WP_Error
	 *
	 * @since 1.0
	 */

	public function post_data() {

		if ( $this->is_new ) {
			$this->post_data_defaults();
		}

		$this->post_data_apply_filters();

		$this->post_data['ID'] = $this->post_id;

		$result = wp_update_post( $this->post_data );

		if ( is_wp_error( $result ) ) {
			Log::write( 'wp-error-update-post-data', $result );
		}

	}

	/**
	 * Post meta.
	 */

	public function post_meta() {

		if ( is_array( $this->post_meta ) ) {

			foreach( $this->post_meta as $meta_key => $meta_value ) {

				$this->save_post_meta( $this->post_id, $meta_key, $meta_value );

				// We do this action here to prevent a loop when running this hook.
				do_action( "wp_data_sync_duplicate_post_meta_$meta_key", $meta_key, $meta_value, $this );

			}

		}

	}

	/**
	 * Save Post Meta
	 *
	 * @param $post_id
	 * @param $meta_key
	 * @param $meta_value
	 *
	 * @return void
	 */

	public function save_post_meta( $post_id, $meta_key, $meta_value ) {

		$meta_key   = $this->post_meta_key( $meta_key, $meta_value );
		$meta_value = $this->post_meta_value( $meta_value, $meta_key );

        if ( '_regular_price' === $meta_key || '_sale_price' === $meta_key ) {
            $this->set_wc_prices( $meta_key, $meta_value );
        }
		elseif ( ! in_array( $meta_key, $this->restricted_meta_keys() ) ) {

			update_post_meta( $post_id, $meta_key, $meta_value );

			$this->process_acf( $meta_key, $meta_value );

		}

		// We do this action here to allow it to run for every key => value pair.
		do_action( "wp_data_sync_post_meta_$meta_key", $post_id, $meta_value, $this );

	}

    /**
     * Set WooCommerce Prices
     *
     * @param $meta_key
     * @param $meta_value
     *
     * @return void
     */

    public function set_wc_prices( $meta_key, $meta_value ) {
        $this->wc_prices[ $meta_key ] = $meta_value;
    }

	/**
	 * Post meta Key.
	 *
	 * @param $meta_key
	 * @param $meta_value
	 * @param $post_id
	 *
	 * @return mixed|void
	 */

	public function post_meta_key( $meta_key, $meta_value ) {
		return apply_filters( 'wp_data_sync_meta_key', $meta_key, $meta_value, $this->post_id, $this );
	}

	/**
	 * Post meta value.
	 *
	 * @since 1.6.26
	 *        Add meta key specific filter.
	 *
	 * @param $meta_value
	 * @param $meta_key
	 *
	 * @return mixed|void
	 */

	public function post_meta_value( $meta_value, $meta_key ) {

		$meta_value = apply_filters( 'wp_data_sync_meta_value', $meta_value, $meta_key, $this->post_id, $this );

		return apply_filters( "wp_data_sync_{$meta_key}_value", $meta_value, $this->post_id, $this );

	}

	/**
	 * Process ACF
	 *
	 * @param string $meta_key
	 * @param mixed  $meta_value
	 *
	 * @return void
	 */

	public function process_acf( $meta_key, $meta_value ) {

		if ( apply_filters( 'wp_data_sync_is_acf_field_post_meta', false, $meta_key, $this->post_id, $this ) ) {

			Log::write( 'acf-field', [
				'post_id' => $this->post_id,
				'key'     => $meta_key,
				'value'   => $meta_value,
			], 'ACF Field - Post Metta' );

			do_action( 'wp_data_sync_process_acf_field_post_meta', $meta_key, $meta_value, $this->post_id, $this );

		}

	}

	/**
	 * Taxonomies.
	 */

	public function taxonomy() {

		if ( ! is_array( $this->taxonomies ) ) {
			return;
		}

		$append = Settings::is_true( 'wp_data_sync_append_terms' );

		foreach ( $this->taxonomies as $taxonomy => $terms ) {

			$taxonomy = trim( wp_unslash( $taxonomy ) );

			/**
			 * Filter: wp_data_sync_taxonomy
			 *
			 * @param string $taxonomy
			 * @param int    $post_id
			 *
			 * @since 2.0.3
			 */

			$taxonomy = apply_filters( 'wp_data_sync_taxonomy', $taxonomy, $this->post_id );

			if ( empty( $taxonomy ) || ! taxonomy_exists( $taxonomy ) ) {

				Log::write( 'invalid-taxonomy', $taxonomy );

				continue;

			}

			$term_ids = [];

			foreach ( $terms as $term ) {

				/**
				 * Filter: wp_data_sync_term
				 *
				 * @param array  $term
				 * @param string $taxonomy
				 * @param int    $post_id
				 *
				 * @since 2.0.3
				 */

				$term = apply_filters( 'wp_data_sync_term', $term, $taxonomy, $this->post_id );

				if( $term_id = $this->set_term( $term, $taxonomy ) ) {
					$term_ids[] = $term_id;
				}

			}

			Log::write( 'term-id', $term_ids );

			wp_set_object_terms( $this->post_id, $term_ids, $taxonomy, $append );

		}

	}

	/**
	 * Set term..
	 *
	 * @param array  $term
	 * @param string $taxonomy
	 * @param int    $parent_id
	 *
	 * @return int|bool
	 */

	public function set_term( $term, $taxonomy, $parent_id = 0 ) {

		if ( ! is_array( $term ) ) {
			return false;
		}

		/**
		 * Extract $term
		 *
		 * $name
		 * $description
		 * $thumb_url
		 * $term_meta
		 * $parents
		 */

		extract( $term );

		if ( ! is_string( $name ) ) {
			return false;
		}

		if ( ! empty( $parents ) && is_array( $parents  ) ) {

			/**
			 * Filter: wp_data_sync_term_parents
			 *
			 * @param array  $parents
			 * @param array  $term
			 * @param string $taxonomy
			 * @param int    $post_id
			 *
			 * @since 2.0.2
			 *        2.0.3 Add $post_id to args.
			 */

			$parents = apply_filters( 'wp_data_sync_term_parents', $parents, $term, $taxonomy, $this->post_id );

			foreach ( $parents as $parent ) {
				$parent_id = $this->set_term( $parent, $taxonomy, $parent_id );
			}

		}

		$name = trim( wp_unslash( $name ) );

		Log::write( 'term-id', "$name - $taxonomy - $parent_id" );

		/**
		 * Filter: wp_data_sync_term_name
		 *
		 * @param string $name
		 * @param string $taxonomy
		 * @param int    $parent_id
		 * @param int    $post_id
		 *
		 * @since 1.0.0
		 *        2.0.3 Add $post_id to args.
		 */

		$name = apply_filters( 'wp_data_sync_term_name', $name, $taxonomy, $parent_id, $this->post_id );

		/**
		 * Filter: wp_data_sync_term_taxonomy
		 *
		 * @param string $taxonomy
		 * @param string $name
		 * @param int    $parent_id
		 * @param int    $post_id
		 *
		 * @since 1.0.0
		 *        2.0.3 Add $post_id to args.
		 */

		$taxonomy = apply_filters( 'wp_data_sync_term_taxonomy', $taxonomy, $name, $parent_id, $this->post_id );
		$term_id  = $this->term_id( $name, $taxonomy, $parent_id );

		if ( isset( $description ) ) {
			$this->term_desc( $description, $term_id, $taxonomy );
		}

		if ( isset( $thumb_url ) ) {
			$this->term_thumb( $thumb_url, $term_id );
		}

		if ( isset( $term_meta ) ) {
			$this->term_meta( $term_meta, $term_id );
		}

		return $term_id;

	}

	/**
	 * Term exists.
	 *
	 * @param $name
	 * @param $taxonomy
	 * @param $parent_id
	 *
	 * @return bool|int
	 */

	public function term_exists( $name, $taxonomy, $parent_id ) {

		global $wpdb;

		Log::write( 'term-exists', "Name: $name - Taxonomy: $taxonomy - Parent ID: $parent_id" );

		$sql = $wpdb->prepare(
			"
			SELECT SQL_NO_CACHE t.term_id
			FROM $wpdb->terms t
			INNER JOIN $wpdb->term_taxonomy tt
			ON tt.term_id = t.term_id
			WHERE t.name = %s
			AND tt.taxonomy = %s
			AND tt.parent = %d
			",
			esc_sql( $name ),
			esc_sql( $taxonomy ),
			intval( $parent_id )
		);

		Log::write( 'term-exists', $sql );

		$term_id = $wpdb->get_var( $sql );

		if ( empty( $term_id ) || is_wp_error( $term_id ) ) {
			Log::write( 'term-exists', 'Term Does Not Exist' );
			Log::write( 'term-exists', $term_id );
			return false;
		}

		Log::write( 'term-exists', "Term ID: $term_id" );

		return (int) $term_id;

	}

	/**
	 * Term ID.
	 *
	 * @param string $name
	 * @param string $taxonomy
	 * @param int $parent_id
	 *
	 * @return false|int
	 */

	public function term_id( $name, $taxonomy, $parent_id ) {

		if ( ! $term_id = $this->term_exists( $name, $taxonomy, $parent_id ) ) {

			$term = wp_insert_term( $name, $taxonomy, [ 'parent' => $parent_id ] );

			if( is_wp_error( $term ) ) {
				Log::write( 'wp-error-term', $term );

				return false;
			}

			$term_id = $term['term_id'];
		}

		Log::write( 'term-id', $term_id );

		return (int) $term_id;

	}

	/**
	 * Term description.
	 *
	 * @param $description string
	 * @param $term_id     int
	 * @param $taxonomy    string
	 */

	public function term_desc( $description, $term_id, $taxonomy ) {

		$option = 'sync_term_desc';

		if ( ! Settings::is_set( $option ) ) {
			return;
		}

		if ( Settings::is_equal( $option, '-1' ) ) {
			return;
		}

		if ( Settings::is_equal( $option, 'false' ) ) {
			return;
		}

		if ( empty( $description ) ) {

			if ( Settings::is_equal( $option, 'skip_empty' ) ) {
				return;
			}

			$description = '';

		}

		$args = [ 'description' => $description ];

		wp_update_term( $term_id, $taxonomy, $args );

	}

	/**
	 * term thumb.
	 *
	 * @param $thumb_url
	 * @param $term_id
	 */

	public function term_thumb( $thumb_url, $term_id ) {

		$option = 'sync_term_thumb';

		if ( ! Settings::is_set( $option ) ) {
			return;
		}

		if ( Settings::is_equal( $option, '-1' ) ) {
			return;
		}

		if ( Settings::is_equal( $option, 'false' ) ) {
			return;
		}

		if ( empty( $thumb_url ) ) {

			if ( Settings::is_equal( $option, 'skip_empty' ) ) {
				return;
			}

			$attach_id = '';

		}

		else {

			$this->set_attachment( $thumb_url );

			if ( ! $attach_id = $this->attachment() ) {
				$attach_id = '';
			}

		}

		update_term_meta( $term_id, 'thumbnail_id', $attach_id );

	}

	/**
	 * Term meta.
	 *
	 * @param $term_meta
	 * @param $term_id
	 */

	public function term_meta( $term_meta, $term_id ) {

		$option = 'sync_term_meta';

		if ( ! Settings::is_set( $option ) ) {
			return;
		}

		if ( Settings::is_equal( $option, '-1' ) ) {
			return;
		}

		if ( Settings::is_equal( $option, 'false' ) ) {
			return;
		}

		if ( is_array( $term_meta ) && ! empty( $term_meta ) ) {

			$restricted_meta_keys = $this->restricted_meta_keys();

			foreach ( $term_meta as $meta_key => $value ) {

				if ( ! in_array( $meta_key, $restricted_meta_keys ) ) {
					update_term_meta( $term_id, $meta_key, $value );
				}

			}

		}

	}

	/**
	 * Reset the term taxonomy count.
	 *
	 * @since 1.4.22
	 *
	 * @link https://stackoverflow.com/questions/18669256/how-to-update-wordpress-taxonomiescategories-tags-count-field-after-bulk-impo
	 */

	public function reset_term_taxonomy_count() {

		global $wpdb;

		$wpdb->query(
			"
			UPDATE $wpdb->term_taxonomy tt SET count = (
				SELECT COUNT(*) FROM $wpdb->term_relationships tr 
    			LEFT JOIN $wpdb->posts p ON (p.ID = tr.object_id) 
    			WHERE 
        		tr.term_taxonomy_id = tt.term_taxonomy_id 
        		AND 
        		tt.taxonomy NOT IN ('link_category')
        		AND 
        		p.post_status IN ('publish', 'future')
			)
			"
		);

	}

	/**
	 * Featured image.
	 *
	 * @since 1.6.0
	 */

	public function featured_image() {

		if ( $attach_id = $this->attachment() ) {
			set_post_thumbnail( $this->post_id, $attach_id );
		}

	}

	/**
	 * Gallery Images
	 *
	 * @since 1.6.0
	 * @since 2.4.2 Moved here from WC_Product_DataSync
	 * @since 2.4.7 Update meta using update_post_meta since WooCommerce _product_image_gallery is a restricted key.
	 *
	 * @return void
	 */

	public function gallery_images() {

		if ( empty( $this->gallery_details ) ) {
			return;
		}

		$attach_ids = [];

		foreach ( $this->gallery_images as $image ) {

			$image = apply_filters( 'wp_data_sync_product_gallery_image', $image, $this->post_id, $this );

			$this->set_attachment( $image );

			if ( $attach_id = $this->attachment() ) {
				$attach_ids[] = $attach_id;
			}

		}

		$gallery_ids = apply_filters( 'wp_data_sync_gallery_image_ids', $attach_ids, $this->post_id, $this );
		$gallery_key = apply_filters( 'wp_data_sync_gallery_image_meta_key', $this->gallery_details['key'], $this->post_id, $this );

		switch ( $this->gallery_details['format'] ) {

			case 'comma_join' :
				$gallery_ids = join( ',', $gallery_ids );
				break;

		}

		// We must update here since WooCommerce _product_image_gallery is a restricted key.
		update_post_meta( $this->post_id, $gallery_key, $gallery_ids );

		$this->process_acf( $gallery_key, $gallery_ids );

		Log::write( 'gallery-images', [
			'gallery_details' => $this->gallery_details,
			'gallery_images'  => $this->gallery_images,
			'gallery_key'     => $gallery_key,
			'gallery_ids'     => $gallery_ids
		], 'Process Gallery Images' );

		do_action( 'wp_data_sync_gallery_images', $this->post_id, $this->gallery_images, $this );

	}

	/**
	 * Attachemnt.
	 *
	 * @return bool|int|\WP_Post
	 */

	public function attachment() {

		Log::write( 'attachment', $this->attachment, 'Start Process' );

		$image_array = $this->image_array();

		/**
		 * Extract.
		 *
		 * $image_url
		 * $title
		 * $description
		 * $caption
		 * $alt
		 */
		extract( $image_array );

		Log::write( 'attachment', $image_url, 'Image URL' );

		if ( empty( $image_url ) ) {
			return false;
		}

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		if ( ! $image_url = $this->is_valid_image_url( $image_url ) ) {
			return false;
		}

		$basename    = $this->basename( $image_array );
		$image_title = preg_replace( '/\.[^.]+$/', '', $basename );

		Log::write( 'attachment', $basename, 'Basename' );
		Log::write( 'attachment', $image_title, 'Image Title' );

		$attachment = [
			'post_title'   => empty( $title ) ? $image_title : $title,
			'post_content' => $description,
			'post_excerpt' => $caption
		];

		if ( $attachment['ID'] = $this->attachment_exists( $image_url ) ) {

			Log::write( 'attachment', "{$attachment['ID']} - {$attachment['post_title']}", 'Exists' );

			// Update the attachement
			wp_update_post( $attachment );

			// Update image alt
			update_post_meta( $attachment['ID'], '_wp_attachment_image_alt', $alt );

			// Update the image source URL.
			update_post_meta( $attachment['ID'], '_source_url', $image_url );

			return $attachment['ID'];

		}

		if ( $file_type = $this->file_type( $image_url ) ) {

			if ( $image_data = $this->fetch_image_data( $image_url ) ) {

				$upload_dir = wp_upload_dir();
				$file_path  = $this->file_path( $upload_dir, $basename );

				Log::write( 'attachment', $file_path, 'File Path' );

				// Copy the image to image upload dir
				file_put_contents( $file_path, $image_data );

				$attachment = array_merge( [
					'guid'           => "{$upload_dir['url']}/{$basename}",
					'post_mime_type' => $file_type,
					'post_status'    => 'inherit'
				], $attachment );

				// Insert image data
				$attach_id = wp_insert_attachment( $attachment, $file_path, $this->post_id );

				Log::write( 'attachment', $attach_id, 'Attachment ID' );

				if ( is_int( $attach_id ) && 0 < $attach_id ) {

					// Get metadata for featured image
					$attach_data = wp_generate_attachment_metadata( $attach_id, $file_path );

					Log::write( 'attachment', $attach_data, 'Attachment Data' );

					// Update metadata
					wp_update_attachment_metadata( $attach_id, $attach_data );

					// Update image alt
					update_post_meta( $attach_id, '_wp_attachment_image_alt', $alt );

					// Update the image source URL.
					update_post_meta( $attach_id, '_source_url', $image_url );

					do_action( 'wp_data_sync_attachment_created', $attach_id, $image_array, $this );

					return $attach_id;

				}

			}

		}

		return false;

	}

	/**
	 * Is Valid Image URL.
	 *
	 * @param $image_url
	 *
	 * @return mixed|void
	 */

	public function is_valid_image_url( $image_url ) {

		// Check for a valid URL.
		$info = filter_var( $image_url, FILTER_VALIDATE_URL );

		if ( ! $info ) {
			Log::write( 'attachment', $image_url, 'Invalid URL' );
		}

		return apply_filters( 'wp_data_sync_is_valid_image_url', $info, $image_url, $this );

	}

	/**
	 * File type.
	 *
	 * @param $image_url
	 *
	 * @return bool|mixed|string
	 */

	public function file_type( $image_url ) {

		$file_type = wp_check_filetype( $image_url );

		if ( ! empty( $file_type['type'] ) ) {

			Log::write( 'attachment', $file_type['type'], 'File Type' );

			return $file_type['type'];

		}

		$file_type = false;

		if ( $type = exif_imagetype( $image_url ) ) {

			switch ( $type ) {

				case IMAGETYPE_JPEG :
					$file_type = 'image/jpeg';
					break;

				case IMAGETYPE_PNG :
					$file_type = 'image/png';
					break;

				case IMAGETYPE_GIF :
					$file_type = 'image/gif';
					break;

			}

		}

		Log::write( 'attachment', $file_type, 'File Type' );

		return $file_type;

	}

	/**
	 * Fetch image data from an image url.
	 *
	 * @param $image_url
	 *
	 * @return bool|string
	 */

	public function fetch_image_data( $image_url ) {

		$response      = wp_remote_get( $image_url, [ 'sslverify' => $this->ssl_verify() ] );
		$response_code = wp_remote_retrieve_response_code( $response );

		Log::write( 'attachment', $response_code, 'Response Code' );

		if ( 200 === $response_code ) {
			return wp_remote_retrieve_body( $response );
		}

		Log::write( 'attachment', $response, 'Response Failed' );

		return false;

	}

	/**
	 * Verify if SSL certificate is valid.
	 *
	 * @return bool
	 */

	public function ssl_verify() {

		if ( Settings::is_checked( 'wp_data_sync_allow_unsecure_images' ) ) {
			return false;
		}

		return true;

	}

	/**
	 * File path.
	 *
	 * @param $upload_dir
	 * @param $basename
	 *
	 * @return string
	 */

	public function file_path( $upload_dir, $basename ) {

		if( wp_mkdir_p( $upload_dir['path'] ) ) {
			return "{$upload_dir['path']}/{$basename}";
		}

		return "{$upload_dir['basedir']}/{$basename}";

	}

	/**
	 * Check to see if attachment exists.
	 *
	 * @since 1.6.0  Query for _source_url
	 *
	 * @param $image_url
	 *
	 * @return bool|int
	 */

	public function attachment_exists( $image_url ) {

		global $wpdb;

		$attach_id = $wpdb->get_var( $wpdb->prepare(
			"
			SELECT post_id
			FROM $wpdb->postmeta
			WHERE meta_key = '_source_url'
			AND meta_value = %s
			",
			esc_sql( $image_url )
		) );

		if ( null === $attach_id || is_wp_error( $attach_id ) ) {
			return false;
		}

		return (int) $attach_id;

	}

	/**
	 * Basename.
	 *
	 * @param array $image_array
	 *
	 * @return mixed|void
	 */

	public function basename( $image_array ) {

        if ( Settings::is_checked( 'wp_data_sync_hash_image_basename' ) ) {
            $basename = wp_hash( $image_array['image_url'] );
        }
        else {
            $basename = sanitize_file_name( basename( $image_array['image_url'] ) );
        }

		return apply_filters( 'wp_data_sync_basename', $basename, $this->post_id, $image_array );

	}

	/**
	 * Image Array.
	 *
	 * @return mixed|void
	 */

	public function image_array() {

		if ( ! is_array( $this->attachment ) ) {

			$this->attachment = [
				'image_url'   => $this->attachment,
				'title'       => '',
				'description' => '',
				'caption'     => '',
				'alt'         => ''
			];

		}

		return apply_filters( 'wp_data_sync_image', $this->attachment, $this->post_id );

	}

	/**
	 * Filter Restricted Meta Keys.
	 *
	 * An array of restricted meta keys.
	 * Keys are restricted since their meta value may break other functionality.
	 *
	 * @return mixed|void
	 */

	public function restricted_meta_keys() {

		$restricted_meta_keys = [
			'_edit_lock',
			'_edit_last',
			'_thumbnail_id',
			'thumbnail_id',
			'product_count_product_cat'
		];

		return apply_filters( 'wp_data_sync_restricted_meta_keys', $restricted_meta_keys );

	}

	/**
	 * Integrations.
	 */

	private function integrations() {

		foreach ( $this->integrations as $integration => $values ) {
			do_action( "wp_data_sync_integration_$integration", $this->post_id, $values, $this );
		}

	}

	/**
	 * Update the last modified date.
	 *
	 * Update the dates last to insure core WP does not change them.
	 *
	 * @since 2.0.6
	 */

	public function update_date() {

		$post_data = [ 'ID' => $this->post_id ];

		$date_keys = [
			'post_date',
			'post_date_gmt',
			'post_modified',
			'post_modified_gmt'
		];

		foreach ( $date_keys as $date_key ) {

			if ( ! empty( $this->post_data[ $date_key ] ) ) {
				$post_data[ $date_key ] = $this->post_data[ $date_key ];
			}

		}

		Log::write( 'post-date', $post_data, 'Update Post Dates' );

		wp_update_post( $post_data );

	}

}
