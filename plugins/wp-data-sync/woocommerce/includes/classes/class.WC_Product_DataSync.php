<?php
/**
 * WC_Product_DataSync
 *
 * WP Data Sync for WooCommerce methods
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\Woo;

use WP_DataSync\Api\App\Data;
use WP_DataSync\App\DataSync;
use WP_DataSync\App\Log;
use WP_DataSync\App\Settings;
use WC_Product_Variation;
use Automattic\WooCommerce\Internal\ProductAttributesLookup\LookupDataStore;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WC_Product_DataSync {

	/**
	 * @var DataSync
	 */

	private $data_sync;

	/**
	 * @var \WC_Product|\WC_Product_Variable
	 */

	private $product;

	/**
	 * @var int
	 */

	private $product_id;

	/**
	 * @var string
	 */

	private $product_type = 'simple';

    /**
     * WC_Product_DataSync constructor.
     *
     * @param int      $product_id
     * @param DataSync $data_sync
     */

	public function __construct(  $product_id, $data_sync ) {

        $this->set_product( $product_id );
        $this->set_product_id( $product_id );
        $this->set_data_sync( $data_sync );

	}

	/**
	 * Instance.
	 *
     * @param int      $product_id
     * @param DataSync $data_sync
     *
	 * @return WC_Product_DataSync
	 */

	public static function instance( $product_id, $data_sync ) {
		return new self( $product_id, $data_sync );
	}

	/**
	 * Set Product
	 *
	 * @param int $product_id
	 */

	public function set_product( $product_id ) {
		$this->product = wc_get_product( $product_id );
	}

	/**
	 * Set Product ID
	 *
	 * @param int $product_id
	 */

	public function set_product_id( $product_id ) {
		$this->product_id = (int) $product_id;
	}

	/**
	 * Set product type.
	 *
	 * @param $product_type
	 *
	 * @return void
	 */

	public function set_product_type( $product_type ) {
		$this->product_type = $product_type;
	}

	/**
	 * Set Data Sync
	 *
	 * @param DataSync $data_sync
	 */

	public function set_data_sync( $data_sync ) {
		$this->data_sync = $data_sync;
	}

	/**
	 * WooCommerce process data.
	 */

	public function wc_process() {

		if ( $this->data_sync->get_wc_categories() ) {
			$this->categories();
		}

		if ( $this->data_sync->get_attributes() ) {

			$this->attributes();
			$this->data_sync->reset_term_taxonomy_count();

			// Create/update the attribute lookup tables.
			if ( class_exists( 'Automattic\WooCommerce\Internal\ProductAttributesLookup\LookupDataStore' ) ) {
				$data_store = new LookupDataStore();
				$data_store->create_data_for_product( $this->product );
			}

		}

		if (
			has_term( 'variable', 'product_type', $this->product_id )
			||
			has_term( 'variable-subscription', 'product_type', $this->product_id )
		) {
			$this->set_variations_inactive();
		}

		if ( $this->data_sync->get_variations() ) {
			$this->variations();
		}

		if ( $this->data_sync->get_taxonomies() ) {
			$this->product_visibility();
		}

		$this->product_type();

	}

    /**
     * Prices
     *
     * @return void
     */

    public function prices() {

        $prices = $this->data_sync->get_wc_prices();

        extract( $prices );

        // We cannot have an empty regular price.
        if ( empty( $_regular_price ) ) {
           $_regular_price = $this->product->get_regular_price();
        }

        /**
         * If the sale price is provided, but empty, we can still use the empty value.
         */
        if ( ! isset( $_sale_price ) ) {
            $_sale_price = $this->product->get_sale_price();;
        }
        elseif ( isset( $_sale_price ) && empty( $_sale_price ) ) {
            $_sale_price = '';
        }

        /**
         * We must set the prices before we can evalaueare WC_Product::is_on_sale().
         */
        $this->product->set_regular_price( $_regular_price );
        $this->product->set_sale_price( $_sale_price );

        /**
         * Get the price based on the sale status of the product.
         */
        $_price = $this->product->is_on_sale() ? $_sale_price : $_regular_price;

        $this->product->set_price( $_price );

        Log::write( 'wc-prices', [
            'product_id'    => $this->product->get_id(),
            'is_on_sale'    => $this->product->is_on_sale(),
            'regular_price' => $_regular_price,
            'sale_price'    => $_sale_price,
            'price'         => $_price,
            'api_prices'    => $prices
        ], 'Set WC Prices' );

    }

	/**
	 * Product categories.
	 *
	 * @return void
	 */

	public function categories() {

        $separator        = apply_filters( 'wc_data_sync_category_string_separator', ',' );
		$category_strings = explode( $separator, $this->data_sync->get_wc_categories() );

		if ( empty( $category_strings ) ) {
			return;
		}

        $term_ids  = [];
		$append    = Settings::is_true( 'wp_data_sync_append_terms' );
        $delimiter = apply_filters( 'wc_data_sync_category_string_delimiter', '>' );

		foreach ( $category_strings as $category_string ) {

			$parent_id = null;
			$_terms    = array_map( 'trim', explode( $delimiter, $category_string ) );
			$total     = count( $_terms );

			foreach ( $_terms as $index => $_term ) {

                $_term = apply_filters( 'wc_data_sync_category_term', $_term );

				if ( $term_id = $this->data_sync->term_id( $_term, 'product_cat', $parent_id ) ) {

					// Only requires assign the last category.
					if ( ( 1 + $index ) === $total ) {
						$term_ids[] = $term_id;
					} else {
						// Store parent to be able to insert or query categories based in parent ID.
						$parent_id = $term_id;
					}

				}

			}

		}

		Log::write( 'wc-dategories', [
			'product_id' => $this->product_id,
			'strings'    => $category_strings,
			'term_ids'   => $term_ids
		] );

		wp_set_object_terms( $this->product_id, $term_ids, 'product_cat', $append );

	}

	/**
	 * Product attributes.
	 */

	public function attributes() {

		$attributes = $this->data_sync->get_attributes();

		if ( empty( $attributes ) ) {
			return;
		}

		$product_attributes = [];

		foreach ( $attributes as $position => $attribute ) {

			if ( is_array( $attribute ) ) {

				extract( $attribute );

				if ( $is_taxonomy ) {

					$taxonomy = $this->attribute_taxonomy( $name );
					$term_ids = $this->attribute_term_ids( $taxonomy, $attribute );

					wp_set_object_terms( $this->product_id, $term_ids, $taxonomy );

				}

				$product_attributes[ $is_taxonomy ? $taxonomy : $name ] = [
					'name'         => $is_taxonomy ? $taxonomy : $name,
					'value'        => join( '|', $values ),
					'position'     => $position,
					'is_visible'   => (int) $is_visible,
					'is_variation' => (int) $is_variation,
					'is_taxonomy'  => (int) $is_taxonomy
				];

			}

		}

		update_post_meta( $this->product_id, '_product_attributes', $product_attributes );

		do_action( 'wp_data_sync_attributes', $this->product_id, $product_attributes );

	}

	/**
	 * Get the attribute term ids.
	 *
	 * @param $taxonomy
	 * @param $attribute
	 *
	 * @return array
	 */

	public function attribute_term_ids( $taxonomy, $attribute ) {

		extract( $attribute );

		$term_ids = [];

		foreach ( $values as $value ) {

			if ( ! empty( $value ) ) {

				if( $term_id = $this->data_sync->set_term( [ 'name' => $value ], $taxonomy ) ) {
					$term_ids[] = $term_id;
				}

			}

		}

		return $term_ids;

	}

	/**
	 * Attribute taxonomy.
	 *
	 * @param $raw_name
	 *
	 * @return string
	 */

	public function attribute_taxonomy( $raw_name ) {

		// These are exported as labels, so convert the label to a name if possible first.
		$attribute_labels = wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_label', 'attribute_name' );
		$attribute_name   = array_search( $raw_name, $attribute_labels, true );

		if ( ! $attribute_name ) {
			$attribute_name = wc_sanitize_taxonomy_name( $raw_name );
		}

		$attribute_id  = wc_attribute_taxonomy_id_by_name( $attribute_name );
		$taxonomy_name = wc_attribute_taxonomy_name( $attribute_name );

		if ( $attribute_id ) {
			return $taxonomy_name;
		}

		// If the attribute does not exist, create it.
		$attribute_id = wc_create_attribute( [
			'name'         => $raw_name,
			'slug'         => $attribute_name,
			'type'         => 'select',
			'order_by'     => 'menu_order',
			'has_archives' => false,
		] );

		// Register as taxonomy while importing.
		register_taxonomy(
			$taxonomy_name,
			apply_filters( 'woocommerce_taxonomy_objects_' . $taxonomy_name, [ 'product' ] ),
			apply_filters( 'woocommerce_taxonomy_args_' . $taxonomy_name, [
				'labels'       => [
					'name' => $raw_name,
				],
				'hierarchical' => true,
				'show_ui'      => false,
				'query_var'    => true,
				'rewrite'      => false,
			] )
		);

		return $taxonomy_name;

	}

	/**
	 * Set variations inactive.
	 *
	 * We want to set all variations inactive.
	 * Later when variations are updated,
	 * we will set only current variations active.
	 */

	public function set_variations_inactive() {

		global $wpdb;

		$wpdb->update(
			$wpdb->posts,
			[ 'post_status' => 'private' ],
			[ 'post_parent' => $this->product_id ]
		);

	}

	/**
	 * Variations.
	 *
	 * @link https://woocommerce.github.io/code-reference/classes/WC-Product-Variation.html
	 *
	 * @throws \WC_Data_Exception
	 */

	public function variations() {

		$variations = $this->data_sync->get_variations();

		if ( is_array( $variations ) ) {

			$data_sync = DataSync::instance();
			$parent_id = $this->product_id;

			foreach ( $variations as $i => $variation ) {

				// Set the post data for the variation.
				$variation['post_data']['post_parent'] = $parent_id;

				// Set the current vaiation active.
				if ( ! isset( $variation['post_data']['post_status'] ) ) {
					$variation['post_data']['post_status'] = 'publish';
				}

				// Set the variation menu order.
				if ( ! isset( $variation['post_data']['menu_order'] ) ) {
					$variation['post_data']['menu_order'] = ( $i + 1 );
				}

				$data_sync->set_properties( $variation );
				$data_sync->process();

				$variation_id = $data_sync->get_post_id();

				if ( $selected_options =  $data_sync->get_selected_options() ) {
					$this->selected_options( $selected_options, $variation_id );
				}

				// Set all missing product variation defaults
				$_variation = new WC_Product_Variation( $variation_id );
				$_variation->save();

				Log::write( 'variation', [
					'Variation ID'   => $variation_id,
					'Parent ID'      => $parent_id,
					'Variation Data' => $variation
				] );

			}

		}

	}

	/**
	 * Selected Options
	 *
	 * Selected product variation options.
	 *
	 * @param array $selected_options
	 * @param int   $variation_id
	 *
	 * @return void
	 */

	public function selected_options( $selected_options, $variation_id ) {

		if ( is_array( $selected_options ) ) {

			foreach ( $selected_options as $option_name => $option_value ) {

				$taxonomy = $this->attribute_taxonomy( $option_name );

				$term_array = [
					'name'        => $option_value,
					'description' => '',
					'thumb_url'   => '',
					'term_meta'   => '',
					'parents'     => ''
				];

				if( $term_id = $this->data_sync->set_term( $term_array, $taxonomy ) ) {

					$term = get_term( $term_id, $taxonomy );

					update_post_meta( $variation_id, "attribute_$taxonomy", $term->slug );

				}

			}

		}

	}

	/**
	 * Product visibility.
	 *
	 * @since 1.0.0
	 * @since 1.10.4
	 */

	public function product_visibility() {

		/**
		 * Should we preserve the current product visibility?
		 */
		if ( Settings::is_checked( 'wp_data_sync_use_current_product_visibility' ) ) {

			// Check for any product visibility.
			if ( has_term( '', 'product_visibility', $this->product_id ) ) {
				return;
			}

		}

		$term       = NULL;
		$taxonomies = $this->data_sync->get_taxonomies();

		if ( is_array( $taxonomies ) && array_key_exists( 'product_visibility', $taxonomies ) ) {

			foreach( $taxonomies['product_visibility'] as $term_array ) {
				$term = $term_array['name'];
			}

		}

		Log::write( 'product-visibility', "API Term: $term " );

		if ( empty( $term ) ) {

			$term = get_option( 'wp_data_sync_product_visibility', 'visible' );

			Log::write( 'product-visibility', "Default Term: $term " );

		}

		wp_set_object_terms( $this->product_id, $term, 'product_visibility' );

	}

	/**
	 * Product type.
	 *
	 * @since 2.1.21
	 */

	public function product_type() {

		if ( $taxonomies = $this->data_sync->get_taxonomies() ) {

			if ( ! empty( $taxonomies['product_type'] ) && is_array( $taxonomies['product_type'] ) ) {

				foreach( $taxonomies['product_type'] as $term ) {

					if ( ! empty( $term['name'] ) ) {
						$this->set_product_type( $term['name'] );
					}

				}

			}

		}

		Log::write( 'product-type', [
			'product_id' => $this->product_id,
			'product_type' => $this->product_type
		], 'Product Type' );

		$result = wp_set_object_terms( $this->product_id, [ $this->product_type ], 'product_type' );

		if ( is_wp_error( $result ) ) {
			Log::write( 'wp-error', $result, 'Product Type' );
		}

	}

    /**
     * Save
     *
     * @return void
     */

    public function save() {
        $this->product->save();
    }

}
