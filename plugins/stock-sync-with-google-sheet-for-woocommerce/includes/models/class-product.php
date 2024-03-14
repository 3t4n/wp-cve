<?php
/**
 * Product Model for Stock Sync with Google Sheet for WooCommerce.
 *
 * @package StockSyncWithGoogleSheetForWooCommerce
 * @since 1.0.0
 */
// Namespace.
namespace StockSyncWithGoogleSheetForWooCommerce;

// Exit if accessed directly.
defined('ABSPATH') || exit;

if ( ! class_exists('\StockSyncWithGoogleSheetForWooCommerce\Product') ) {

	/**
	 * Product Model for Stock Sync with Google Sheet for WooCommerce.
	 *
	 * @package StockSyncWithGoogleSheetForWooCommerce
	 * @since 1.0.0
	 */
	class Product extends Base {

		/**
		 * Utilities Trait to use in all classes globally.
		 */
		use Utilities;

		/**
		 * Get raw data from database.
		 *
		 * @param array $ids Product ids.
		 * @return mixed
		 */
		public function get_raw_data( array $ids = null ) {
			global $wpdb;

			$column = new Column();

			$fields = $column->get_queryable_fields();

			$main_table = $wpdb->prefix . 'posts';

			$query  = 'SELECT ';
			$query .= implode(', ', array_map(function ( $field ) use ( $main_table ) {
				return "$main_table.$field";
			}, $fields));

			/**
			 * From post meta table
			 */
			$post_meta_table = $wpdb->prefix . 'postmeta';
			$meta_fields     = $column->get_queryable_metas();

			if ( $meta_fields && count($meta_fields) > 0 ) {
				$query .= ', ';

				$meta_query = [];

				foreach ( $meta_fields as $meta_field_key => $meta_field ) {
					$meta_field_key_name = str_replace([ '-', ' ', ',', '.', '?' ], '_', $meta_field_key);
					$escaped_meta_field_key = $wpdb->prepare('%s', $meta_field_key);
					if ( 'product_image' === $meta_field_key ) {
						$file_key = '_wp_attached_file';
						$thubnail_key = '_thumbnail_id';
						$meta_query[] = "(
							SELECT pms.meta_value
							FROM {$post_meta_table} ims
							LEFT JOIN {$post_meta_table} pms ON ims.meta_value = pms.post_id AND pms.meta_key = '{$file_key}'
							WHERE ims.post_id = {$main_table}.ID
							AND ims.meta_key = '{$thubnail_key}'
							LIMIT 1
						) AS {$meta_field_key_name}";
					} else {
						$meta_query[] = "(SELECT {$post_meta_table}.meta_value FROM $post_meta_table WHERE $post_meta_table.post_id = $main_table.ID AND $post_meta_table.meta_key = $escaped_meta_field_key LIMIT 1) as $meta_field_key_name";
					}
				}

				$query .= implode(', ', $meta_query);
			}

			/**
			 * From taxonomy table
			 */

			$taxonomy_fields = $column->get_queryable_taxonomies();

			if ( $taxonomy_fields && count($taxonomy_fields) > 0 ) {
				$query .= ', ';
				$query .= implode(', ', array_map(function ( $field ) use ( $main_table, $wpdb ) {
					return "(SELECT GROUP_CONCAT(CONCAT_WS(':' , t.term_id, t.name)) FROM $wpdb->term_relationships tr INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id INNER JOIN $wpdb->terms t ON t.term_id = tt.term_id WHERE tr.object_id = $main_table.ID AND tt.taxonomy = '$field' LIMIT 1)  as $field";
				}, $taxonomy_fields));

					error_log( print_r( $taxonomy_fields, true ) );
			}

				$relations = $column->get_queryable_relations();

				// order lookup.
				$status = apply_filters( 'ssgswc_wc_order_product_lookup_status', [ 'wc-completed', 'wc-processing', 'wc-hold', 'wc-refunded' ] );

				$order_data_storage_option = get_option('woocommerce_custom_orders_table_enabled');
			if ( 'yes' === $order_data_storage_option ) {
				foreach ( $relations as $relation ) {
					switch ( $relation ) {
						case 'total_sales':
							$status = implode("','", $status);
							$query .= ", (SELECT SUM( order_item_meta__qty.meta_value ) FROM {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta__qty
							INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_item_id = order_item_meta__qty.order_item_id
							INNER JOIN {$wpdb->prefix}wc_orders AS posts ON posts.id = order_items.order_id
							WHERE posts.status IN ( '$status' ) AND posts.type = 'shop_order' AND order_item_meta__qty.meta_key = '_qty' AND order_item_meta__qty.order_item_id = order_items.order_item_id AND order_items.order_item_type = 'line_item' AND order_items.order_item_id IN ( SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta__product_id WHERE order_item_meta__product_id.meta_key IN ( '_product_id', '_variation_id' ) AND order_item_meta__product_id.meta_value = $main_table.ID ) ) AS total_sales";

							break;

						default:
							break;
					}
				}
			} else {
				foreach ( $relations as $relation ) {
					switch ( $relation ) {
						case 'total_sales':
							$status = implode("','", $status);
							$query .= ", (SELECT SUM( order_item_meta__qty.meta_value ) FROM {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta__qty
							INNER JOIN {$wpdb->prefix}woocommerce_order_items AS order_items ON order_items.order_item_id = order_item_meta__qty.order_item_id
							INNER JOIN {$wpdb->prefix}posts AS posts ON posts.ID = order_items.order_id
							WHERE posts.post_status IN ( '$status' ) AND posts.post_type = 'shop_order' AND order_item_meta__qty.meta_key = '_qty' AND order_item_meta__qty.order_item_id = order_items.order_item_id AND order_items.order_item_type = 'line_item' AND order_items.order_item_id IN ( SELECT order_item_id FROM {$wpdb->prefix}woocommerce_order_itemmeta AS order_item_meta__product_id WHERE order_item_meta__product_id.meta_key IN ( '_product_id', '_variation_id' ) AND order_item_meta__product_id.meta_value = $main_table.ID ) ) AS total_sales";

							break;

						default:
							break;
					}
				}
			}

				$query .= " From $main_table";

				// Includes variations.
				$query .= " WHERE $main_table.post_type IN ('product', 'product_variation' )";

				// Not deleted.
				$query .= " AND $main_table.post_status = 'publish' AND $main_table.post_status != 'trash'";

				// Conditional by in ID.
			if ( $ids && count($ids) > 0 ) {
				$query .= " AND $main_table.ID IN (" . implode(',', $ids) . ')';
			}

				// Avoid duplicating IDs.
				$query .= " GROUP BY $main_table.ID ";

				// Order and Order By.
				$order    = esc_sql( apply_filters( 'ssgs_product_order', 'ASC' ) );
				$order_by = esc_sql( apply_filters( 'ssgs_product_order_by', 'post_date' ) );

				$query .= "ORDER BY $main_table.$order_by $order";

				/**
				 * Set limit
				 */
				$limit = apply_filters('ssgsw_product_limit', 100);
			if ( $limit ) {
				$query .= $wpdb->prepare( ' LIMIT %d', $limit );
			}

			// phpcs:ignore
			$results = $wpdb->get_results( $query ); // db call ok; no-cache ok.
			if ( $wpdb->last_error ) {
				return $wpdb->last_error;
			}

			return $results;
		}

		/**
		 * Get Formatted Data.
		 *
		 * @param array $ids Product IDs.
		 * @return mixed
		 */
		public function get_formatted_data( $ids = null ) {
			$raw_data = $this->get_raw_data($ids);
			if ( ! $raw_data || count($raw_data) === 0 ) {
				return false;
			}

			$column = new Column();
			$keys   = $column->get_column_keys();
			foreach ( $keys as &$value ) {
				$value = str_replace([ '-', ' ', ',', '.', '?' ], '_', $value);
			}
			error_log( print_r( $keys, true ) );
			$formatted_data = array_map(function ( $row ) use ( $keys ) {
				$formatted_row = [];
				foreach ( $keys as $key ) {

					$formatted_row[ $key ] = apply_filters('ssgs_get_column', $row->$key, $key, $row);
				}
				if ( '_stock_status' === $key ) {
					unset($formatted_row[ $key ]);
				}
				return $formatted_row;
			}, $raw_data);
			$formatted_data = array_map(function ( $row ) { //phpcs:ignore
				if ( array_key_exists( 'product_image', $row) && ! empty($row['product_image']) ) {
					$image_url_convart = $row['product_image'];
					$base_url = home_url('/wp-content/uploads/');
					$absolute_url = trailingslashit($base_url) . ltrim($image_url_convart, '/');
					$row['product_image'] = '=IMAGE("' . $absolute_url . '")';
					return array_values( (array) $row);
				} else {
					return array_values( (array) $row);
				}
			}, $formatted_data);
			return $formatted_data;
		}
		/**
		 * Get All Products.
		 *
		 * @param array $ids List of ids.
		 * @return array
		 */
		public function get_single_product( $ids = [] ) {
			$formatted_data = $this->get_formatted_data($ids);

			$columns = new Column();
			$headers = $columns->get_column_names();

			if ( $formatted_data && count($formatted_data) > 0 ) {
				array_unshift($formatted_data, $headers);
			}

			return $formatted_data;
		}
		/**
		 * Get All Products.
		 *
		 * @return array
		 */
		public function get_all_products() {
			$formatted_data = $this->get_formatted_data();

			$columns = new Column();
			$headers = $columns->get_column_names();

			if ( $formatted_data && count($formatted_data) > 0 ) {
				array_unshift($formatted_data, $headers);
			}

			return $formatted_data;
		}
		/**
		 * Get first sheets data and compare id exits and resturn range
		 *
		 * @param mixed $id product id.
		 * @param mixed $data first sheets data.
		 * @param mixed $sheet_ob object.
		 *
		 * @return mixed
		 */
		public function find_out_range( $id, $data, $sheet_ob ) {
			$matching_row_index = null;
			if ( is_array( $data ) && ! empty( $data ) ) {
				$matching_row_index = $this->find_out_range_row($data, $id );
			} else {
				$data = $sheet_ob->get_first_columns();
				if ( is_array( $data ) && ! empty( $data ) ) {
					$matching_row_index = $this->find_out_range_row( $data, $id );
				}
			}
			return $matching_row_index;
		}
		/**
		 * Get first sheets data and compare id exits and resturn range
		 *
		 * @param mixed $id product id.
		 * @param mixed $data first sheets data.
		 * @param mixed $sheet_ob object.
		 * @param mixed $product_name object.
		 *
		 * @return mixed
		 */
		public function find_out_range2( $id, $data, $sheet_ob, $product_name ) {
			$matching_row_index = [
				'range' => null,
				'name' => null,
			];
			if ( is_array( $data ) && ! empty( $data ) ) {
				$matching_row_index = $this->find_out_range_row2($data, $id, $product_name );
			} else {
				$data = $sheet_ob->get_first_columns();
				if ( is_array( $data ) && ! empty( $data ) ) {
					$matching_row_index = $this->find_out_range_row2( $data, $id, $product_name );
				}
			}
			return $matching_row_index;
		}
		/**
		 * Find out row key form google sheets info
		 *
		 * @param array $data sheet information.
		 * @param mixed $id product id.
		 *
		 * @return mixed
		 */
		public function find_out_range_row( $data, $id ) {
			$new_index = null;
			foreach ( $data as $row => $row_data ) {
				if ( is_array($row_data) && array_key_exists( 0, $row_data ) ) {
					if ( $row_data[0] == $id ) { //phpcs:ignore
						$new_index = $row + 1;
						break;
					}
				}
			}
			return $new_index;
		}
		/**
		 * Find out row key form google sheets info
		 *
		 * @param array $data sheet information.
		 * @param mixed $id product id.
		 * @param mixed $name product name.
		 *
		 * @return mixed
		 */
		public function find_out_range_row2( $data, $id, $name ) {
			$new_index = [
				'range' => null,
				'name'  => null,
			];
			foreach ( $data as $row => $row_data ) {
				if ( is_array($row_data) && array_key_exists( 0, $row_data ) ) {
					if ( $row_data[0] == $id ) { //phpcs:ignore
						$new_index['range'] = $row + 1;
						if ( $row_data[2] !== $name ) {
							$new_index['name'] = true;
						}
						break;
					}
				}
			}
			return $new_index;
		}
		/**
		 * Update delete and append product in google sheets check by id
		 *
		 * @param mixed  $product_id product id.
		 * @param string $type update type.
		 * @param string $type2 update type2.
		 * @param array  $sheets sheets value.
		 *
		 * @return boolean
		 */
		public function batch_update_delete_and_append( $product_id, $type = 'update', $type2 = 'test', $sheets = [] ) {
			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			$sheet = new Sheet();
			$get_product = $this->get_single_product([ $product_id ]);

			$find_range = $this->find_out_range($product_id, $sheets, $sheet);

			if ( 'update' == $type && null == $find_range ) { //phpcs:ignore
				$license_active = ssgsw_is_license_valid();
				if ( ! $license_active ) {
					$sheet_count = $this->sheet_row_count( $sheet, $sheets );
					if ( $sheet_count < 101 && $sheet_count != 0 ) { //phpcs:ignore
						return $sheet->append_new_row( $get_product[1], $type2 );
					}
				} else {
					if ( is_array( $get_product ) && array_key_exists( '1', $get_product ) ) {
						return $sheet->append_new_row( $get_product[1], $type2 );
					}
				}
			} else {
				if ( $find_range != null ) { //phpcs:ignore
					if ( 'update' == $type ) { //phpcs:ignore
						return $sheet->update_single_row_values($find_range,$get_product[1]);
					} else {
						return $sheet->delete_single_row($find_range);
					}
				}
			}
			return false;
		}
		/**
		 * Update delete and append product in google sheets check by id
		 *
		 * @param mixed  $product_id product id.
		 * @param string $type update type.
		 * @param string $type2 update type2.
		 * @param array  $sheets sheets value.
		 *
		 * @return boolean
		 */
		public function batch_update_delete_and_append2( $product_id, $type = 'update', $type2 = 'test', $sheets = [] ) {
			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}
			$sheet = new Sheet();
			$get_product = $this->get_single_product([ $product_id ]);
			if ( is_array( $get_product ) ) {
				$get_product_name = $get_product[1][2];
				$product_type = $get_product[1][1];
				$find_range = $this->find_out_range2($product_id, $sheets, $sheet, $get_product_name);
				$range_value = $find_range['range'];
				$change_name = $find_range['name'];
				if ( 'Variable' === $product_type ) {
					$child_product_ids = $this->get_children_id($product_id);
					if ( is_array( $child_product_ids ) && ! empty( $child_product_ids ) ) {
						foreach ( $child_product_ids as $child_id ) {
							$get_products = $this->get_single_product([ $child_id ]);
							$find_ranges = $this->find_out_range($child_id, $sheets, $sheet);
							$this->variable_product_formating_sync_method($type, $type2, $find_ranges, $sheet, $sheets, $get_products );
						}
					}
				}
				$this->variable_product_formating_sync_method($type, $type2, $range_value, $sheet, $sheets, $get_product );
			}
			return false;
		}
		/**
		 * Retrieve children id for product
		 *
		 * @param int $product_id product id.
		 *
		 * @return array product IDs
		 */
		public function get_children_id( $product_id ) {
			global $wpdb;
			$child_product_ids = $wpdb->get_col(
				$wpdb->prepare(
					"
					SELECT ID
					FROM {$wpdb->posts}
					WHERE post_parent = %d
					AND (post_type = 'product' OR post_type = 'product_variation')
					",
					$product_id
				)
			);
			return $child_product_ids;
		}

		/**
		 * Variable product formating and sync method
		 *
		 *  @param mixed $type product type.
		 *  @param mixed $type2 product type.
		 *  @param mixed $find_range range of the product.
		 *  @param mixed $sheet google sheet.
		 *  @param mixed $sheets google sheet.
		 *  @param mixed $get_product get the products.
		 *
		 *  @return mixed different value
		 */
		public function variable_product_formating_sync_method( $type, $type2, $find_range, $sheet, $sheets, $get_product ) {
			if ( 'update' == $type && null == $find_range ) { //phpcs:ignore
				$license_active = ssgsw_is_license_valid();
				if ( ! $license_active ) {
					$sheet_count = $this->sheet_row_count( $sheet, $sheets );
					if ( $sheet_count < 101 && $sheet_count != 0 ) { //phpcs:ignore
						return $sheet->append_new_row( $get_product[1], $type2 );
					}
				} else {
					if ( is_array( $get_product ) && array_key_exists( '1', $get_product ) ) {
						return $sheet->append_new_row( $get_product[1], $type2 );
					}
				}
			} else {
				if ( $find_range != null ) { //phpcs:ignore
					if ( 'update' == $type ) { //phpcs:ignore
						return $sheet->update_single_row_values($find_range,$get_product[1]);
					} else {
						return $sheet->delete_single_row($find_range);
					}
				}
			}
		}
		/**
		 * Update product data from API without check index number
		 *
		 * @param int $find_range number of index.
		 * @param int $product_id product identifier.
		 */
		public function update_single_product_data_to_sheet( $find_range, $product_id ) {
			$get_products = $this->get_single_product([ $product_id ]);
			$sheet = new Sheet();
			$sheet->update_single_row_values($find_range, $get_products[1] );
		}
		/**
		 * Check how many rows exits in sheet
		 *
		 * @param object $sheet_ob object.
		 * @param array  $sheets information.
		 *
		 * @return int number of rows
		 */
		public function sheet_row_count( $sheet_ob, $sheets ) {
			$sheet_count = 0;
			if ( is_array($sheets) && ! empty( $sheets ) ) {
				$sheet_count = count($sheets);
			}
			if ( 0 === $sheet_count ) {
				$sheets_info = $sheet_ob->get_first_columns();
				if ( is_array( $sheets_info ) && ! empty( $sheets_info ) ) {
					$sheet_count = count($sheets_info);
				}
			}
			return $sheet_count;
		}
		/**
		 * Sync all products.
		 *
		 * @return mixed
		 */
		public function sync_all() {

			if ( ! $this->app->is_plugin_ready() ) {
				return __('Plugin is not ready to use.', 'stock-sync-with-google-sheet-for-woocommerce');
			}

			$data = $this->get_all_products();

			if ( ! $data || count($data) === 0 ) {

				return sprintf(
					'%s <a style="text-decoration:none;" href="%s">%s <i class="ssgs-arrow-right"></i></a>',
					__('No products found!', 'stock-sync-with-google-sheet-for-woocommerce'),
					esc_url(admin_url('edit.php?post_type=product')),
					__('Add New Product', 'stock-sync-with-google-sheet-for-woocommerce')
				);
			}

			$google_sheet = new Sheet();

			$updated = $google_sheet->update_values('A1', $data);

			return wp_validate_boolean( $updated );
		}

		/**
		 * Update bulk products from sheet.
		 *
		 * @param array $products Products.
		 * @return bool
		 **/
		public function bulk_update( array $products = [] ) {

			$GLOBALS['ssgs_sync_all_products'] = true;
			$column                            = new Column();
			global $wpdb;
			$column  = new Column();
			// Checks if plugin is ready to use.
			$add_products_from_sheet = wp_validate_boolean(apply_filters('ssgsw_add_products_from_sheet', ssgsw_get_option('add_products_from_sheet')));
			$get_bulk_edit_option = wp_validate_boolean(ssgsw_get_option( 'bulk_edit_option'));
			$sheet = new Sheet();
			$sheets_info = [];
			if ( is_array($products) && ! array_key_exists( 'index_number', $products['0'] ) ) {
				$sheets_info = $sheet->get_first_columns();
			}
			foreach ( $products as $data ) {
				$data = (object) $data;
				$product_id = isset($data->ID) && $data->ID > 0 ? $data->ID : null;

				if ( $product_id ) {
					$product_exists = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->posts} WHERE ID = %d", $product_id));
					if ( ! $product_exists ) {
						continue;
					}
				}
				$product_data = array(
					'post_title' => isset($data->name) ? $data->name : '',
					'post_status' => 'publish'
				);
				if ( $product_id ) {
					if (isset($data->post_excerpt) ) {
						$product_data['post_excerpt'] = $data->post_excerpt;
					}
					$wpdb->update($wpdb->posts, $product_data, array( 'ID' => $product_id ));
				} else {
					if ( ! $add_products_from_sheet ) {
						return false;
					}
					$product_data['post_type'] = 'product';
					$product_data['post_excerpt'] = 'This is a simple product';
					$product_id = ssgsw_wp_insert_post($product_data); 
					wp_set_object_terms($product_id, 'simple', 'product_type');
					$default_category = get_term_by('name', 'uncategorized', 'product_cat');
					if ( $default_category ) {
						wp_set_object_terms($product_id, $default_category->term_id, 'product_cat');
					}
					update_post_meta($product_id, '_visibility', 'visible');
				}
				if ( isset( $data->Image ) ) { //phpcs:ignore
					$this->process_google_sheets_image( $product_id, $data->Image ); //phpcs:ignore
				}
				$this->update_product_prices($product_id, $data );
				if ( isset( $data->sku ) ) {
					update_post_meta( $product_id, '_sku', $data->sku );
				}
				$variable_product = $this->is_variable_product($product_id);
				if ( isset($data->stock) ) {
					if ( is_numeric($data->stock) ) {
						update_post_meta($product_id, '_manage_stock', 'yes');
						if ( $data->stock > 0 ) {
							update_post_meta($product_id, '_stock_status', 'instock' );
							update_post_meta($product_id, '_stock', (int) $data->stock);
							if ( $variable_product ) {
								$this->variation_product_stock_update_for_numaric($product_id);
							}
						} else {
							update_post_meta($product_id, '_stock', 0 );
							update_post_meta($product_id, '_stock_status', 'outofstock');
							if ( $variable_product ) {
								$this->variation_product_stock_update_for_zero($product_id);
							}
						}
					} else {
						if ( $variable_product ) {
							update_post_meta($product_id, '_manage_stock', 'no');
							update_post_meta($product_id, '_stock', 0 );
							$stock_status = $column->get_stock_status($data->stock, true);
							if ( 'instock' == $stock_status || 'In Stock' == $stock_status ) {
								update_post_meta($product_id, '_stock_status', $stock_status );
								$this->variation_product_stock_update_for_numaric($product_id);
							}
						} else {
							update_post_meta($product_id, '_manage_stock', 'no');
							update_post_meta($product_id, '_stock', 0 );
							$stock_status = $column->get_stock_status($data->stock, true);
							update_post_meta($product_id, '_stock_status', $stock_status );
						}
					}
				}
				$columns = $column->get_all_columns();
				foreach ( $data as $data_key => $data_value ) {
					foreach ( $columns as $key => $value ) {
						if ( $value['label'] === $data_key ) {
							ssgsw_meta_field_value_save( $product_id, $key, $data_value );
						}
					}
				}
				$this->clear_woocommerce_caches($product_id);
				if ( isset($data->ID) && ! empty($data->ID) ) {
					if ( ! empty($sheets_info) ) {
						$dta = $this->batch_update_delete_and_append2($data->ID,'update','',$sheets_info);
					} else {
						$this->update_single_product_data_to_sheet($data->index_number, $data->ID );
					}
				} else {
					if ( empty($sheets_info) ) {
						$this->update_single_product_data_to_sheet($data->index_number, $product_id );
					}
				}
				if ( ! $get_bulk_edit_option ) {
					return false;
				}
			}
			return true;
		}
		/**
		 * Check if a product is a variable product.
		 *
		 * @param int $product_id The ID of the product.
		 * @return bool True if the product is a variable product, false otherwise.
		 */
		public function variation_product_stock_update_for_numaric($product_id) {
			$sheet = new Sheet();
			$sheets_info = $sheet->get_first_columns();
			$child_product_ids = $this->get_children_id($product_id);
			if ( is_array( $child_product_ids ) && ! empty( $child_product_ids ) ) {
				foreach ( $child_product_ids as $child_id ) {
					$manage_stock = get_post_meta( $child_id, '_manage_stock', true );
					if ( 'yes' !== $manage_stock ) {
						update_post_meta($child_id, '_stock_status', 'instock');
						$get_products = $this->get_single_product([ $child_id ]);
						$find_ranges = $this->find_out_range($child_id, $sheets_info, $sheet);
						$type = 'update';
						$type2 = 'test';
						$this->variable_product_formating_sync_method($type, $type2, $find_ranges, $sheet, $sheets_info, $get_products );
					}
				}
			}
		}
		/**
		 * Check if a product is a variable product.
		 *
		 * @param int $product_id The ID of the product.
		 * @return bool True if the product is a variable product, false otherwise.
		 */
		public function variation_product_stock_update_for_zero($product_id) {
			$sheet = new Sheet();
			$sheets_info = $sheet->get_first_columns();
			$child_product_ids = $this->get_children_id($product_id);
			if ( is_array( $child_product_ids ) && ! empty( $child_product_ids ) ) {
				foreach ( $child_product_ids as $child_id ) {
					$manage_stock = get_post_meta( $child_id, '_manage_stock', true );
					if ( 'yes' !== $manage_stock ) {
						update_post_meta($child_id, '_stock', 0 );
						update_post_meta($child_id, '_stock_status', 'outofstock');
						$get_products = $this->get_single_product([ $child_id ]);
						$find_ranges = $this->find_out_range($child_id, $sheets_info, $sheet);
						$type = 'update';
						$type2 = 'test';
						$this->variable_product_formating_sync_method($type, $type2, $find_ranges, $sheet, $sheets_info, $get_products );
					}
				}
			}
		}
		/**
		 * Check if a product is a variable product.
		 *
		 * @param int $product_id The ID of the product.
		 * @return bool True if the product is a variable product, false otherwise.
		 */
		public function is_variable_product($product_id) {
			$product = wc_get_product($product_id);
			
			// Check if the product is a variable product.
			return $product && $product->is_type('variable');
		}

		/**
		 * Clear transient for product view
		 */
		public function clear_woocommerce_caches($product_id) {
			global $wpdb;
			// Clear WooCommerce transients.
			$wpdb->query("
				DELETE FROM {$wpdb->options}
				WHERE option_name LIKE '_transient_wc_%'
				OR option_name LIKE '_transient_timeout_wc_%'
			");
			 $wpdb->query("
				DELETE FROM {$wpdb->options}
				WHERE option_name = 'wc_low_stock_{$product_id}'
				OR option_name = 'wc_outofstock_{$product_id}'
    		");
		}
		/**
		 * Update product price
		 *
		 * @param int    $product_id product identifier.
		 * @param object $data product data.
		 */
		public function update_product_prices( $product_id, $data ) {

			if ( isset($data->regular_price) && is_numeric($data->regular_price) ) {
				$regular_price = wc_format_decimal($data->regular_price);

				// Update regular price.
				update_post_meta($product_id, '_regular_price', $regular_price);
				update_post_meta($product_id, '_price', $regular_price);

				// Update sale price if set and less than or equal to regular price.
				if ( isset($data->sale_price) && is_numeric($data->sale_price) ) {
					$sale_price = wc_format_decimal($data->sale_price);
					// Ensure sale price is less than or equal to regular price.
					if ( $sale_price < $regular_price ) {
						update_post_meta($product_id, '_sale_price', $sale_price);
						update_post_meta($product_id, '_price', $sale_price);
					} else {
						update_post_meta($product_id, '_sale_price', '');
						update_post_meta($product_id, '_price', $regular_price);
					}
				}
			}
		}
		/**
		 * Data:image formating.
		 *
		 * @param string $base64_image base64 encoded image.
		 * @param int    $loop_index index of.
		 */
		public function save_image_as_attachment( $base64_image, $loop_index ) {
			$upload_dir = wp_upload_dir();
			$filename = md5(uniqid() . rand()) . '_' . $loop_index;
			if ( strpos($base64_image, 'data:image/png;base64,') === 0 ) {
				$filename .= '.png';
				$mime_type = 'image/png';
				$image_data = base64_decode(str_replace('data:image/png;base64,', '', $base64_image));
			} elseif ( strpos($base64_image, 'data:image/jpeg;base64,') === 0 ) {
				$filename .= '.jpg';
				$mime_type = 'image/jpeg';
				$image_data = base64_decode(str_replace('data:image/jpeg;base64,', '', $base64_image));
			} else {
				return [];
			}
			$new_image_path = $upload_dir['path'] . '/' . $filename;

			file_put_contents($new_image_path, $image_data);

			$attachment = array(
				'post_mime_type' => $mime_type,
				'post_title' => 'Product image',
				'post_content' => 'Product Image',
				'post_status' => 'inherit',
			);

			$attachment_id = wp_insert_attachment($attachment, $new_image_path);

			$attach_data = wp_generate_attachment_metadata($attachment_id, $new_image_path);
			wp_update_attachment_metadata($attachment_id, $attach_data);

			// Get the URL of the uploaded image.
			$image_url = wp_get_attachment_url($attachment_id);
			return [
				$image_url,
				$attachment_id,
			];
		}
		/**
		 * Save product image from google sheets.
		 *
		 * @param int    $product_id Product identifier.
		 * @param string $image_url product image url.
		 */
		public function set_product_image_from_url( $product_id, $image_url ) {
			$image_data = file_get_contents($image_url);
			if ( false === $image_data ) {
				return false;
			}
			$file_extension = pathinfo($image_url, PATHINFO_EXTENSION);
			$allowed_extensions = array( 'jpg', 'jpeg', 'png', 'gif', 'svg' );

			// if (!in_array(strtolower($file_extension), $allowed_extensions)) {
			// return false;
			// }.
			$upload_dir = wp_upload_dir();
			$image_path = $upload_dir['path'] . '/' . basename($image_url);
			$result = file_put_contents($image_path, $image_data);
			if ( false === $result ) {
				return false;
			}
			$attachment = array(
				'post_mime_type' => 'image/' . strtolower($file_extension),
				'post_title'     => sanitize_file_name(basename($image_url)),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
			$attachment_id = ssgsw_wp_insert_attachment($attachment, $image_path);
			if ( is_wp_error($attachment_id) ) {
				return false;
			}
			set_post_thumbnail($product_id, $attachment_id);
			update_post_meta($attachment_id, 'ssgsw_original_image_url', $image_url);
			return true;
		}
		/**
		 * Check this url already exists in wordpress shop.
		 *
		 * @param url $image_url image url.
		 */
		public function get_attachment_id_by_url( $image_url ) {
			$attachment_id = attachment_url_to_postid($image_url);
			return $attachment_id;
		}
		/**
		 * Save product attachments images
		 *
		 * @param int $product_id Product identifier.
		 * @param url $image_url Image URL.
		 */
		public function process_google_sheets_image( $product_id, $image_url ) {
			if ( ! ssgsw_is_license_valid() ) {
				return false;
			}
			if ( empty($image_url) || ! filter_var($image_url, FILTER_VALIDATE_URL) ) {
				return false;
			}

			$existing_attachment_id = $this->get_attachment_id_by_url($image_url);
			if ( $existing_attachment_id ) {
				set_post_thumbnail($product_id, $existing_attachment_id);
				return true;
			}
			$exits_url_id = $this->get_attachment_by_original_image_url($image_url);
			if ( $exits_url_id ) {
				set_post_thumbnail($product_id, $exits_url_id);
				return true;
			}

			return $this->set_product_image_from_url($product_id, $image_url);
		}
		/**
		 * Get product image id from orginal image url
		 *
		 * @param url $original_image_url image url.
		 */
		public function get_attachment_by_original_image_url( $original_image_url ) {
			$args = array(
				'post_type'      => 'attachment',
				'posts_per_page' => 1,
				'post_status'    => 'inherit',
				'meta_query'     => array(
					array(
						'key'   => 'ssgsw_original_image_url',
						'value' => $original_image_url,
					),
				),
			);

			$attachments = get_posts($args);
			if ( $attachments ) {
				return $attachments[0]->ID;
			}
			return false;
		}
	}
}
