<?php
/**
 * Plugin Name: GazChap's WooCommerce Auto Category Product Thumbnails
 * Plugin URI: https://www.gazchap.com/posts/woocommerce-category-product-thumbnails/
 * Version: 1.5
 * Author: Gareth 'GazChap' Griffiths
 * Author URI: https://www.gazchap.com/
 * Description: Automatically use a product thumbnail as a category thumbnail if no category thumbnail is set
 * Tested up to: 6.2.2
 * WC requires at least: 3.0.0
 * WC tested up to: 7.8.0
 * Text Domain: gazchaps-woocommerce-auto-category-product-thumbnails
 * Domain Path: /lang
 * License: GNU General Public License v2.0
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 * Donate link: https://paypal.me/gazchap
 */

namespace GazChap;

class WC_Category_Product_Thumbnails {

	private $shuffle;
	private $recurse_category_ids;
	private $image_size;
	private $use_transients;
	private $transient_expiry;
	const TRANSIENT_PREFIX = 'gcwacpt_term_thumbnail_product_id_';

	/**
	 * WC_Category_Product_Thumbnails constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'replace_wc_actions' ) );
		register_activation_hook( __FILE__, array( $this, 'activation' ) );
		add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'add_settings_link' ) );
		add_action( 'admin_init', array( $this, 'check_woocommerce_is_activated' ) );

		// declare compatibility for WooCommerce HPOS
		add_action( 'before_woocommerce_init', function() {
			if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', __FILE__, true );
			}
		} );
	}

	public function activation() {
		$default_options = array(
			'gazchaps-woocommerce-auto-category-product-thumbnails_shuffle' => 'yes',
			'gazchaps-woocommerce-auto-category-product-thumbnails_recurse' => 'yes',
			'gazchaps-woocommerce-auto-category-product-thumbnails_category-size' => 'shop_thumbnail',
			'gazchaps-woocommerce-auto-category-product-thumbnails_use-transients' => 'yes',
			'gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry' => 86400,
		);
		foreach( $default_options as $o => $v ) {
			if ( !get_option( $o ) ) {
				update_option( $o, $v );
			}
		}
	}

	public function add_settings_link( $links ) {
		if ( !is_array( $links ) ) {
			$links = array();
		}
		$links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=products&section=gazchaps-woocommerce-auto-category-product-thumbnails' ) . '">' . __( 'Settings', 'gazchaps-woocommerce-auto-category-product-thumbnails' ) . '</a>';
		return $links;
	}

	/**
	 * Check if WooCommerce is active - if not, then deactivate this plugin and show a suitable error message
	 */
	public function check_woocommerce_is_activated(){
	    if ( is_admin() ) {
	        if ( !class_exists( 'WooCommerce' ) ) {
	            add_action( 'admin_notices', array( $this, 'woocommerce_deactivated_notice' ) );
	            deactivate_plugins( plugin_basename( __FILE__ ) );
	        }
	    }
	}

	public function woocommerce_deactivated_notice() {
	    ?>
	    <div class="notice notice-error"><p><?php esc_html_e( 'GazChap\'s WooCommerce Auto Category Product Thumbnails requires WooCommerce to be installed and activated.', 'gazchaps-woocommerce-auto-category-product-thumbnails' ) ?></p></div>
	    <?php
	}

	/**
	 * Removes the action that puts the thumbnail before the subcategory title, and replaces it with our version
	 */
	public function replace_wc_actions() {
		// we need to set these values here so we can detect if they've changed after settings are saved
		// in order to clear transients
		$this->use_transients = ( get_option('gazchaps-woocommerce-auto-category-product-thumbnails_use-transients') == 'yes' ) ? true : false;
		$this->transient_expiry = intval( get_option('gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry') );

		$custom_transient_expiry = get_option('gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry-custom' );
		if ( !empty( $custom_transient_expiry ) && 0 < intval( $custom_transient_expiry ) ) {
			$this->transient_expiry = $custom_transient_expiry;
		}

		remove_action( 'woocommerce_before_subcategory_title', 'woocommerce_subcategory_thumbnail', 10 );
		add_action( 'woocommerce_before_subcategory_title', array( $this, 'auto_subcategory_thumbnail' ) );
		add_filter( 'woocommerce_get_sections_products', array( $this, 'add_setting_section' ) );
		add_filter( 'woocommerce_get_settings_products', array( $this, 'add_settings_to_section' ), 10, 2 );

		add_filter( 'woocommerce_update_options_products', array( $this, 'run_after_settings_saved' ), 10 );
	}

	/**
	 * The function that does all the donkey work.
	 * @param \WP_Term $category - the category that we're dealing with
	 */
	public function auto_subcategory_thumbnail( $category ) {

		$this->shuffle = ( get_option('gazchaps-woocommerce-auto-category-product-thumbnails_shuffle') == 'yes' ) ? true : false;
		$this->recurse_category_ids = ( get_option('gazchaps-woocommerce-auto-category-product-thumbnails_recurse') == 'yes' ) ? true : false;
		$this->image_size = get_option('gazchaps-woocommerce-auto-category-product-thumbnails_category-size');
		$exclude_thumbnail_ids = self::_get_excluded_ids();

		// does this category already have a thumbnail defined? if so, use that instead
		if ( get_term_meta( $category->term_id, 'thumbnail_id', true ) ) {
			woocommerce_subcategory_thumbnail( $category );
			return;
		}

		// are we using transients? if so, check for the existence of one first
		$transient_name = self::TRANSIENT_PREFIX . $category->term_id;
		$transient_value = false;
		if ( $this->use_transients ) {
			$transient_value = get_transient( $transient_name );
		}

		if ( !$transient_value ) {
			// get a list of category IDs inside this category (so we're fetching products from all subcategories, not just the top level one)
			if ( $this->recurse_category_ids ) {
				$category_ids = $this->get_sub_category_ids( $category );
			} else {
				$category_ids = array( $category->term_id );
			}

			$query_args = array(
				'posts_per_page' => 1,
				'post_status' => 'publish',
				'post_type' => 'product',
				'meta_query' => array(
					array(
						'key' => '_thumbnail_id',
						'value' => '',
						'compare' => '!=',
					),
				),
				'tax_query' => array(
					array(
						'taxonomy' => 'product_cat',
						'field' => 'term_id',
						'terms' => $category_ids,
						'operator' => 'IN',
					),
				),
			);
			if ( $this->shuffle ) {
				$query_args['orderby'] = 'rand';
			}

			if ( !empty( $exclude_thumbnail_ids ) ) {
				$query_args['meta_query'][] = array(
					'key' => '_thumbnail_id',
					'value' => $exclude_thumbnail_ids,
					'compare' => 'NOT IN',
				);
			}

			$products = get_posts( $query_args );
			if ( $products ) {
				$product = current( $products );
				$thumbnail_product_id = $product->ID;

				// if we're using transients, save one
				if ( !empty( $thumbnail_product_id ) && $this->use_transients && 0 < $this->transient_expiry ) {
					set_transient( $transient_name, $thumbnail_product_id, $this->transient_expiry );
				}
			}
		} else {
			$thumbnail_product_id = $transient_value;
			$product_thumbnail_id = get_post_thumbnail_id( $thumbnail_product_id );
		}

		if ( !empty( $thumbnail_product_id ) && ( empty( $product_thumbnail_id ) || !in_array( $product_thumbnail_id, $exclude_thumbnail_ids ) ) ) {
			echo get_the_post_thumbnail( $thumbnail_product_id, $this->image_size );
		} else {
			// show the default placeholder category image if there's no products inside this one
			woocommerce_subcategory_thumbnail( $category );
		}
	}

	/**
	 * Recursive function to fetch a list of child category IDs for the one passed
	 *
	 * @param \WP_Term $start - the category to start from
	 * @param array $results - this just stores the results as they're being built up
	 *
	 * @return array - an array of term IDs for each product_cat inside the original one
	 */
	private function get_sub_category_ids( $start, $results = array() ) {
		if ( !is_array( $results ) ) $results = array();

		$results[] = $start->term_id;
		$cats = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => false, 'parent' => $start->term_id ) );
		if ( is_array( $cats ) ) {
			foreach( $cats as $cat ) {
				$results = $this->get_sub_category_ids( $cat, $results );
			}
		}

		return $results;
	}

	function add_setting_section( $sections ) {

		$sections['gazchaps-woocommerce-auto-category-product-thumbnails'] = __( 'Auto Category Thumbnails', 'gazchaps-woocommerce-auto-category-product-thumbnails' );
		return $sections;

	}

	function add_settings_to_section( $settings, $current_section ) {
		/**
		 * Check the current section is what we want
		 **/
		if ( $current_section == 'gazchaps-woocommerce-auto-category-product-thumbnails' ) {
			$new_settings = array();
			// Add Title to the Settings
			$new_settings[] = array( 'name' => __( 'Auto Category Thumbnails Settings', 'gazchaps-woocommerce-auto-category-product-thumbnails' ), 'type' => 'title', 'id' => 'gazchaps-woocommerce-auto-category-product-thumbnails' );

			$temp = $this->_get_all_image_sizes();
			$image_sizes = array();
			foreach( $temp as $image_size => $image_spec_array ) {
				$image_spec = "";
				$image_spec .= ($image_spec_array['width'] > 0) ? $image_spec_array['width'] : __('auto', 'gazchaps-woocommerce-auto-category-product-thumbnails' );
				$image_spec .= ' x ';
				$image_spec .= ($image_spec_array['height'] > 0) ? $image_spec_array['height'] : __('auto', 'gazchaps-woocommerce-auto-category-product-thumbnails' );
				if ( $image_spec_array['crop'] ) {
					$image_spec .= ", " . __('cropped', 'gazchaps-woocommerce-auto-category-product-thumbnails' );
				}
				$image_sizes[ $image_size ] = $image_size . " (" . $image_spec . ")";
			}

			$transient_expiry_options = array(
				300 => __( '5 minutes', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				600 => __( '10 minutes', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				1800 => __( '30 minutes', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				3600 => __( '1 hour', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				7200 => __( '2 hours', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				14400 => __( '4 hours', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				28800 => __( '8 hours', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				43200 => __( '12 hours', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				86400 => __( '24 hours', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				129600 => __( '36 hours', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				172800 => __( '2 days', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				259200 => __( '3 days', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				432000 => __( '5 days', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				604800 => __( '1 week', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				1209600 => __( '2 weeks', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				2419200 => __( '4 weeks', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Thumbnail Size', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_category-size',
				'type'     => 'select',
				'options' => $image_sizes,
				'desc'     => __( 'Choose the image size to use for the thumbnails', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Go into Child Categories', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_recurse',
				'type'     => 'checkbox',
				'desc'     => __( 'If ticked, the plugin will also search for product thumbnails in any child categories. If not ticked, it will stay on the same level.', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Random Thumbnail', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_shuffle',
				'type'     => 'checkbox',
				'desc'     => __( 'If ticked, the plugin will pick a thumbnail at random from those available. If not ticked, it will always use the first one it finds.', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Use Transients', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_use-transients',
				'type'     => 'checkbox',
				'desc'     => __( 'If ticked, the plugin will save the thumbnail ID found in a transient, and re-use that on subsequent page loads until expiry. Will improve general performance, so is enabled by default.', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Transient Expiry', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry',
				'type'     => 'select',
				'options'  => $transient_expiry_options,
				'desc'     => __( 'Choose how long transients last before they expire (and trigger a new thumbnail to be picked)', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Custom Transient Expiry', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry-custom',
				'type'     => 'text',
				'desc'     => __( 'If none of the above are suitable, enter the number of seconds transients should last. 3600 = 1 hour.', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array(
				'name'     => __( 'Exclude Thumbnail IDs', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
				'id'       => 'gazchaps-woocommerce-auto-category-product-thumbnails_exclude-thumbnail-ids',
				'type'     => 'text',
				'desc'     => __( 'Enter a list of attachment IDs separated by commas that you do not want the plugin to ever pick.', 'gazchaps-woocommerce-auto-category-product-thumbnails' ),
			);

			$new_settings[] = array( 'type' => 'sectionend', 'id' => 'gazchaps-woocommerce-auto-category-product-thumbnails' );
			return $new_settings;

		/**
		 * If not, return the standard settings
		 **/
		} else {
			return $settings;
		}
	}

	function run_after_settings_saved() {
		$new_use_transients = ( get_option('gazchaps-woocommerce-auto-category-product-thumbnails_use-transients') == 'yes' ) ? true : false;
		$new_transient_expiry = intval( get_option('gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry') );
		$custom_transient_expiry = get_option('gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry-custom' );
		if ( !empty( $custom_transient_expiry ) && 0 < intval( $custom_transient_expiry ) ) {
			$new_transient_expiry = $custom_transient_expiry;
			update_option( 'gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry-custom', $custom_transient_expiry );
		} else {
			delete_option( 'gazchaps-woocommerce-auto-category-product-thumbnails_transient-expiry-custom' );
		}

		global $wpdb;
		if ( !$new_use_transients || $new_transient_expiry != $this->transient_expiry ) {
			// delete transients
			$query = "DELETE FROM " . $wpdb->options . " WHERE " .
			         "option_name LIKE '_transient_" . self::TRANSIENT_PREFIX . "%' OR " .
			         "option_name LIKE '_transient_timeout_" . self::TRANSIENT_PREFIX . "%'";
			$wpdb->query( $query );
		}

		$excluded_ids = self::_get_excluded_ids();
		sort( $excluded_ids );
		update_option( 'gazchaps-woocommerce-auto-category-product-thumbnails_exclude-thumbnail-ids', implode( ",", $excluded_ids ) );
	}

	private function _get_all_image_sizes() {
	    global $_wp_additional_image_sizes;

	    $default_image_sizes = get_intermediate_image_sizes();

	    foreach ( $default_image_sizes as $size ) {
	        $image_sizes[ $size ][ 'width' ] = intval( get_option( "{$size}_size_w" ) );
	        $image_sizes[ $size ][ 'height' ] = intval( get_option( "{$size}_size_h" ) );
	        $image_sizes[ $size ][ 'crop' ] = get_option( "{$size}_crop" ) ? get_option( "{$size}_crop" ) : false;
	    }

	    if ( isset( $_wp_additional_image_sizes ) && count( $_wp_additional_image_sizes ) ) {
	        $image_sizes = array_merge( $image_sizes, $_wp_additional_image_sizes );
	    }

	    return $image_sizes;
	}

	/**
	 * @return int[]
	 */
	private static function _get_excluded_ids() {
		$exclude_ids = explode( ',', get_option( 'gazchaps-woocommerce-auto-category-product-thumbnails_exclude-thumbnail-ids' ) );
		if ( !empty( $exclude_ids ) ) {
			$exclude_ids = array_filter( array_map( 'trim', $exclude_ids ), 'is_numeric' );
			$exclude_ids = array_map( 'abs', array_map( 'intval', $exclude_ids ) );
			if ( !empty( $exclude_ids ) ) {
				return $exclude_ids;
			}
		}
		return array();
	}

}

new WC_Category_Product_Thumbnails();