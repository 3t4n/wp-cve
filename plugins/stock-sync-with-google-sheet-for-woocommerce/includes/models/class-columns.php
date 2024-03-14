<?php
/**
 * Column class for Stock Sync with Google Sheet for WooCommerce.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */

// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Column') ) {

	/**
	 * Column class for Stock Sync with Google Sheet for WooCommerce.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class Column extends Base {
		/**
		 * Get all columns.
		 *
		 * @return array
		 */
		public function get_all_columns() {
			$columns = [
				'ID'             => [
					'label' => __('ID', 'stock-sync-with-google-sheet-for-woocommerce'),
					'type'  => 'field',
					'priority' => 1,
				],
				'product_type'   => [
					'label' => __('Type', 'stock-sync-with-google-sheet-for-woocommerce'),
					'type'  => 'term',
					'priority' => 2,
				],
				'post_title'     => [
					'label' => __('Name', 'stock-sync-with-google-sheet-for-woocommerce'),
					'type'  => 'field',
					'priority' => 3,
				],
				'_stock'         => [
					'label' => __('Stock', 'stock-sync-with-google-sheet-for-woocommerce'),
					'type'  => 'meta',
					'priority' => 4,
				],
				'_stock_status'  => [
					'label'  => __('Stock Status', 'stock-sync-with-google-sheet-for-woocommerce'),
					'column' => false,
					'type'   => 'meta',
					'priority' => 5,
				],
				'_regular_price' => [
					'label'  => __('Regular price', 'stock-sync-with-google-sheet-for-woocommerce'),
					'update' => true,
					'type'   => 'meta',
					'priority' => 6,
				],
				'_sale_price'    => [
					'label' => __('Sale price', 'stock-sync-with-google-sheet-for-woocommerce'),
					'type'  => 'meta',
					'priority' => 7,
				],
				'product_image' => [
					'label' => __('Image', 'stock-sync-with-google-sheet-for-woocommerce'),
					'type'  => 'meta',
					'priority' => 8,
				],
			];
			$custom_meta_value = $this->custom_meta_fields();

			if ( ! empty( $custom_meta_value ) ) {
				$columns = $columns + $custom_meta_value;
			}
			return apply_filters('ssgsw_columns', $columns);
		}
		/**
		 * Format custom meta data
		 *
		 * @return array
		 */
		public function custom_meta_fields() {
			$checked_value    = ssgsw_get_option('show_custom_fileds');
			$on_custom_fields = ssgsw_get_option('show_custom_meta_fileds');
			$all_key          = ssgsw_get_product_custom_fields();
			$on_custom_fields = true === wp_validate_boolean( ssgsw_get_option('show_custom_meta_fileds', false) );
			$custom_array = [];
			 $license_active = ssgsw_is_license_valid();
			if ( $license_active ) {
				if ( $on_custom_fields ) {
					$priority = 15;
					if ( is_array( $checked_value ) && ! empty( $checked_value) ) {
						foreach ( $checked_value as $key => $value ) {
							$check_type = check_ssgsw_file_type( $value );
							if ( 'suported' === $check_type ) {
								$custom_array[ $value ]['label']     = $value;
								$custom_array[ $value ]['type']      = 'meta';
								$custom_array[ $value ]['priority']  = $priority++;
							}
						}
					}
				}
			}
			return $custom_array;
		}

		/**
		 * Get Editable Columns.
		 *
		 * @return array
		 */
		public function get_columns() {
			$columns = $this->get_all_columns();

			$show_short_description = true === wp_validate_boolean( ssgsw_get_option('show_short_description', false) );
			$show_product_category  = true === wp_validate_boolean( ssgsw_get_option('show_product_category', false) );
			$show_total_sales       = true === wp_validate_boolean( ssgsw_get_option('show_total_sales', false) );
			$show_sku               = true === wp_validate_boolean( ssgsw_get_option('show_sku', false) );
			$show_attributes        = true === wp_validate_boolean( ssgsw_get_option('show_attributes', false) );
			$show_product_images    = true === wp_validate_boolean( ssgsw_get_option('show_product_images', false) );

			if ( ! $show_short_description ) {
				unset($columns['post_excerpt']);
			}

			if ( ! $show_product_category ) {
				unset($columns['product_cat']);
			}
			if ( ! $show_product_images ) {
				unset($columns['product_image']);
			}
			if ( ! ssgsw_is_license_valid() ) {
				unset($columns['product_image']);
			}
			if ( ! $show_total_sales ) {
				unset($columns['total_sales']);
			}

			if ( ! $show_sku ) {
				unset($columns['_sku']);
			}

			if ( ! $show_attributes ) {
				unset($columns['_product_attributes']);
			}

			// Sort columns by priority.
			uasort($columns, function ( $a, $b ) {
				return isset($a['priority']) && isset($b['priority']) ? $a['priority'] - $b['priority'] : 0;
			});

			return $columns;
		}


		/**
		 * Get Editable Column Keys.
		 *
		 * @return array
		 */
		public function get_column_keys() {
			$columns = $this->get_columns();

			// Remove columns which are not editable.
			$columns = array_filter($columns, function ( $column ) {
				return isset($column['column']) ? $column['column'] : true;
			});

			$keys = array_keys($columns);

			return $keys;
		}

		/**
		 * Get Editable Column Values.
		 *
		 * @return array
		 */
		public function get_column_names() {
			$columns = array_filter($this->get_columns(), function ( $column ) {
				return isset($column['column']) ? $column['column'] : true;
			});

			$values = array_column(array_values($columns), 'label');

			return $values;
		}

		/**
		 * Get column keys for query.
		 *
		 * @return array
		 */
		public function get_queryable_columns() {
			$columns = $this->get_columns();

			$columns = array_filter($columns, function ( $column ) {
				return isset($column['query']) ? $column['query'] : true;
			});

			return $columns;
		}

		/**
		 * Get column keys for query.
		 *
		 * @return array
		 */
		public function get_queryable_column_keys() {
			$columns = $this->get_queryable_columns();

			$keys = array_keys($columns);

			return $keys;
		}


		/**
		 * Get queryable fields.
		 *
		 * @return array
		 */
		public function get_queryable_fields() {
			$columns = $this->get_queryable_columns();

			$columns = array_filter($columns, function ( $column ) {
				return 'field' === $column['type'];
			});

			return array_keys($columns);
		}

		/**
		 * Get queryable meta fields.
		 *
		 * @return array
		 */
		public function get_queryable_metas() {
			$columns = $this->get_queryable_columns();

			$columns = array_filter($columns, function ( $column ) {
				return 'meta' === $column['type'];
			});

			return apply_filters('ssgsw_queryable_metas', $columns);
		}

		/**
		 * Get queryable term fields.
		 *
		 * @return array
		 */
		public function get_queryable_taxonomies() {
			$columns = $this->get_queryable_columns();

			$columns = array_filter($columns, function ( $column ) {
				return 'term' === $column['type'];
			});

			return array_keys($columns);
		}

		/**
		 * Get queryable relation fields.
		 *
		 * @return array
		 */
		public function get_queryable_relations() {
			$columns = $this->get_queryable_columns();

			$columns = array_filter($columns, function ( $column ) {
				return 'relation' === $column['type'];
			});

			return array_keys($columns);
		}

		/**
		 * Init Getter and Setter Hooks.
		 *
		 * @param mixed $stock_id Stock ID.
		 * @param bool  $reverse Reverse.
		 * @return mixed
		 */
		public function get_stock_status( $stock_id = 'instock', $reverse = false ) {
			if ( is_numeric( $stock_id ) ) {
				return absint( $stock_id );
			}

			if ( $reverse ) {
				$stock_id = strtolower($stock_id);
				return str_replace(' ', '', $stock_id);
			}

			$stocks = [
				'instock'     => __('In Stock', 'stock-sync-with-google-sheet-for-woocommerce'),
				'outofstock'  => __('Out of Stock', 'stock-sync-with-google-sheet-for-woocommerce'),
				'onbackorder' => __('On Backorder', 'stock-sync-with-google-sheet-for-woocommerce'),
			];

			return isset( $stocks[ $stock_id ] ) ? $stocks[ $stock_id ] : $stock_id;
		}
		/**
		 * Init Getter and Setter Hooks.
		 *
		 * @param mixed $stock_id Stock ID.
		 * @param bool  $reverse Reverse.
		 * @return mixed
		 */
		public function get_stock_status_update( $stock_id = 'instock', $reverse = false ) {
			$stocks = [
				'instock'     => __('instock', 'stock-sync-with-google-sheet-for-woocommerce'),
				'outofstock'  => __('outofstock', 'stock-sync-with-google-sheet-for-woocommerce'),
				'onbackorder' => __('onbackorder', 'stock-sync-with-google-sheet-for-woocommerce'),
			];
			return in_array( $stock_id, $stocks ) ? $stocks[ $stock_id ] : 'instock';
		}

		/**
		 * Returns first term.
		 *
		 * @param string $categories Categories.
		 * @return string
		 */
		public function get_first_term( $categories = '0' ) {
			if ($categories !== null) {//phpcs:ignore
				$categories     = explode(',', $categories);
				$first_category = explode(':', $categories[0]);

				return isset( $first_category[1] ) ? $first_category[1] : '';
			}
			return '';
		}

		/**
		 * Get items by comma separated values.
		 *
		 * @param string $categories Categories.
		 * @return string
		 */
		public function get_items_by_comma( $categories = 0 ) {
			if ($categories !== null) {//phpcs:ignore
				$categories = explode(',', $categories);

				$items = [];
				foreach ( $categories as $category ) {
					$category = explode(':', $category);
					$items[]  = isset( $category[1] ) ? $category[1] : '';
				}
				return implode(', ', $items);
			} else {
				return '';
			}
		}

		/**
		 * Get Product Type.
		 *
		 * @param string $product_type Product Type.
		 * @return mixed
		 */
		public function get_product_type( $product_type = 'simple' ) {
			$product_type = $this->get_first_term($product_type);

			$types = [
				'simple'   => __('Simple', 'stock-sync-with-google-sheet-for-woocommerce'),
				'variable' => __('Variable', 'stock-sync-with-google-sheet-for-woocommerce'),
				'grouped'  => __('Grouped', 'stock-sync-with-google-sheet-for-woocommerce'),
				'external' => __('External', 'stock-sync-with-google-sheet-for-woocommerce'),
			];

			return array_key_exists($product_type, $types) ? $types[ $product_type ] : ( empty($product_type) ? __('Variation', 'stock-sync-with-google-sheet-for-woocommerce') : $product_type );
		}
	}
}
