<?php

if (!defined('WPINC')) {
	exit;
}

if (!class_exists('Webtoffee_Product_Feed_Google_LPIExport')) {

	class Webtoffee_Product_Feed_Google_LPIExport extends Webtoffee_Product_Feed_Product {

		public $parent_module = null;
		public $product;
		public $current_product_id;
		public $form_data;

		public function __construct($parent_object) {

			$this->parent_module = $parent_object;
		}

		public function prepare_header() {

			$export_columns = $this->parent_module->get_selected_column_names();

			return apply_filters('wt_pf_alter_product_feed_csv_columns', $export_columns);
		}

    /**
     * Prepare data that will be exported.
     */
    public function prepare_data_to_export($form_data, $batch_offset,$step) {

	$this->form_data = $form_data;
		
        $include_products = !empty($form_data['filter_form_data']['wt_pf_product']) ? $form_data['filter_form_data']['wt_pf_product'] : '';
        $exclude_products = !empty($form_data['filter_form_data']['wt_pf_exclude_product']) ? $form_data['filter_form_data']['wt_pf_exclude_product'] : '';
	$exp_stock_status = !empty($form_data['filter_form_data']['wt_pf_stock_status']) ? $form_data['filter_form_data']['wt_pf_stock_status'] : '';
		
        $prod_exc_categories = !empty($form_data['post_type_form_data']['item_exc_cat']) ? $form_data['post_type_form_data']['item_exc_cat'] : array();            
        $prod_inc_categories = !empty($form_data['post_type_form_data']['item_inc_cat']) ? $form_data['post_type_form_data']['item_inc_cat'] : array();

        $cat_filter_type = !empty($form_data['post_type_form_data']['cat_filter_type']) ? $form_data['post_type_form_data']['cat_filter_type'] : '';
        if( '' === $cat_filter_type ){
            $cat_filter_type = !empty($form_data['post_type_form_data']['wt_pf_export_cat_filter_type']) ? $form_data['post_type_form_data']['wt_pf_export_cat_filter_type'] : 'include_cat';
        }

        $inc_exc_category = !empty($form_data['post_type_form_data']['inc_exc_cat']) ? $form_data['post_type_form_data']['inc_exc_cat'] : array();
        if( empty($inc_exc_category) ){
            $inc_exc_category = !empty($form_data['post_type_form_data']['wt_pf_inc_exc_category']) ? $form_data['post_type_form_data']['wt_pf_inc_exc_category'] : array();
        }

        if ('include_cat' === $cat_filter_type) {
            $prod_inc_categories = $inc_exc_category;
        } else {
            $prod_exc_categories = $inc_exc_category;
        }
        
        $prod_tags = !empty($form_data['filter_form_data']['wt_pf_product_tags']) ? $form_data['filter_form_data']['wt_pf_product_tags'] : array();
        $prod_types = !empty($form_data['filter_form_data']['wt_pf_product_types']) ? $form_data['filter_form_data']['wt_pf_product_types'] : array();
        $prod_status = !empty($form_data['filter_form_data']['wt_pf_product_status']) ? $form_data['filter_form_data']['wt_pf_product_status'] : array();
        
        $export_sortby = !empty($form_data['filter_form_data']['wt_pf_sort_columns']) ? $form_data['filter_form_data']['wt_pf_sort_columns'] : 'ID';
        $export_sort_order = !empty($form_data['filter_form_data']['wt_pf_order_by']) ? $form_data['filter_form_data']['wt_pf_order_by'] : 'ASC';

        $export_limit = !empty($form_data['filter_form_data']['wt_pf_limit']) ? intval($form_data['filter_form_data']['wt_pf_limit']) : 999999999; //user limit
        $current_offset = !empty($form_data['filter_form_data']['wt_pf_offset']) ? intval($form_data['filter_form_data']['wt_pf_offset']) : 0; //user offset

        $batch_count = !empty($form_data['advanced_form_data']['wt_pf_batch_count']) ? $form_data['advanced_form_data']['wt_pf_batch_count'] : Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('default_export_batch');
        $batch_count = apply_filters('wt_woocommerce_csv_export_limit_per_request', $batch_count); //ajax batch limit

                
        $real_offset = ($current_offset + $batch_offset);

        if($batch_count<=$export_limit)
        {
            if(($batch_offset+$batch_count)>$export_limit) //last offset
            {
                $limit=$export_limit-$batch_offset;
            }else
            {
                $limit=$batch_count;
            }
        }else
        {
            $limit=$export_limit;
        }
        
        $product_array = array();
	$total_products = 0;
        if ($batch_offset < $export_limit)
        {
            $args = array(
                'status' => array('publish'),
                'type' => array('simple','grouped','external', 'variable'),
                'limit' => $limit,
                'offset' => $real_offset,
                'orderby' => $export_sortby,
                'order' => $export_sort_order,                                
                'return' => 'ids',
                'paginate' => true,
            );

            if (!empty($prod_status)) {
                $args['status'] = $prod_status;
            } 
                
            if (!empty($prod_types)) {
                $args['type'] = $prod_types;
            }

            if (!empty($prod_exc_categories)) {
                $args['exclude_category'] = $prod_exc_categories;
            }

            if (!empty($prod_tags)) {
                $args['tag'] = $prod_tags;
            }

            if (!empty($include_products)) {
                $args['include'] = $include_products;
            }
            if ( !empty( $prod_inc_categories ) ) {
                $args['category'] = $prod_inc_categories;
            }else{
                array_push($args['type'], 'variation');
            }
            
            if (!empty($exclude_products)) {
                $args['exclude'] = $exclude_products;
            }
			
			if (!empty($exp_stock_status)) {
                $args['stock_status'] = $exp_stock_status;
            }
			
            // Export all language products if WPML is active and the language selected is all.
            if ( function_exists('icl_object_id') && isset( $_SERVER["HTTP_REFERER"] ) && strpos($_SERVER["HTTP_REFERER"], 'lang=all') !== false ) {
                     $args['suppress_filters'] = true;
            }
			
            $args = apply_filters("woocommerce_csv_product_export_args", $args);
            $products = wc_get_products($args); 

            $total_products=0;
            if( 0 == $batch_offset ) //first batch
            {
                $total_item_args=$args;
                $total_item_args['limit']=$export_limit; //user given limit
                $total_item_args['offset']=$current_offset; //user given offset
                $total_products_count = wc_get_products($total_item_args);
                $total_products=count($total_products_count->products);
            }


            $products_ids = $products->products;

            // If include category is selected and variable products are under those category, the variations will not be returned by the WC query
            if (!empty($prod_inc_categories)) {
                    $temp_prod_ids = $products_ids;
                    foreach ($temp_prod_ids as $key => $product_id) {
                            $product = wc_get_product($product_id);
                            if ($product->is_type('variable')) {
                                    $variations = $product->get_available_variations();
                                    $variations_ids = wp_list_pluck($variations, 'variation_id');
                                    foreach ($variations_ids as $variations_id){
                                            $products_ids[] = $variations_id;
                                    }
                            }
                    }
            }
            foreach ($products_ids as $key => $product_id) {  
                $product = wc_get_product($product_id); 

                // Skip variations that belongs to a specific categories that is excluded in filter
                if ($product->is_type('variation') && !empty($prod_exc_categories)) {

                    $parent_id = $product->get_parent_id();
                    if( has_term( $prod_exc_categories, 'product_cat', $parent_id ) ){
                            continue;
                    }

                }

                $this->parent_product = $product;
                $this->current_product_id = $product->get_id();
                $this->product = $product;

                if ($product->is_type('variable')) {
                        continue;
                }

                $product_array[] = $this->generate_row_data_wc_lower($product);
                if (($product->is_type('variable') || $product->has_child()) ) {
                    $children_ids = $product->get_children();
                    if (!empty($children_ids)) {
                        foreach ($children_ids as $id) {                                
                            if(!in_array($id, $products_ids)){  // skipping if alredy processed in $products_ids                                                                                                                               
                                $variation = wc_get_product($id);  
                                $this->parent_product = $product;
                                $this->product = $variation;
                                                                    $this->current_product_id = $variation->get_id();
                                if(is_object($variation)){
                                    $product_array[] = $this->generate_row_data_wc_lower($variation);
                                }

                            }
                        }
                    }                        
                }


            }
            
        }         

        $return_products =  array(
            'total' => $total_products,
            'data' => $product_array,
        );
		if( 0 == $batch_offset && 0 == $total_products ){
				$return_products['no_post'] = __( 'Nothing to export under the selected criteria. Please try adjusting the filters.' );
		}
		return $return_products;

        }

		public function get_default_variation($product) {

			$variation_id = false;

			foreach ($product->get_available_variations() as $variation_values) {
				foreach ($variation_values['attributes'] as $key => $attribute_value) {
					$attribute_name = str_replace('attribute_', '', $key);
					$default_value = $product->get_variation_default_attribute($attribute_name);
					if ($default_value == $attribute_value) {
						$is_default_variation = true;
					} else {
						$is_default_variation = false;
						break; // Stop this loop to start next main lopp
					}
				}

				if ($is_default_variation) {
					$variation_id = $variation_values['variation_id'];
					break; // Stop the main loop
				}
			}
			return $variation_id;
		}

		protected function generate_row_data_wc_lower($product_object) {

			$export_columns = $this->parent_module->get_selected_column_names();

			$product_id = $product_object->get_id();
			$product = get_post($product_id);

			$csv_columns = $export_columns;

			$export_columns = !empty($csv_columns) ? $csv_columns : array();

			$row = array();

                        foreach ($export_columns as $key => $value) {
                        if (method_exists($this, $value)) {
                            $row[$key] = $this->$value($key, $value, $export_columns);
                        }elseif (strpos($value, 'meta:') !== false) {
                            $mkey = str_replace('meta:', '', $value);
                            $row[$key] = get_post_meta($product_id, $mkey, true);
                            // TODO
                            // wt_image_ function can be replaced with key exist check
                        }elseif (strpos($value, 'wt_pf_pa_') !== false) {
                            $atr_key = str_replace('wt_pf_pa_', '', $value);
                            if($product_object->is_type('variation')){
                                $product_object = wc_get_product($product_object->get_parent_id());
                            }
                            $value = '';
                            if(is_object($product_object)){
                                $value = $product_object->get_attribute( $atr_key );
                            }
                            if ( ! empty( $value ) ) {
                                        $value = trim( $value );
                                }
                            $row[$key] = $value;
                        }elseif (strpos($value, 'wt_pf_cattr_') !== false) {
                            $atr_key = str_replace('wt_pf_cattr_', '', $value);
                            if($product_object->is_type('variation')){
                                $product_object = wc_get_product($product_object->get_parent_id());
                            }
                            $value = '';
                            if(is_object($product_object)){
                                $value = $product_object->get_attribute( $atr_key );

                            }
                            if ( ! empty( $value ) ) {
                                        $value = trim( $value );
                                        $value = str_replace('|', ',', $value);
                                }
                            $row[$key] = $value;
                        } elseif (strpos($value, 'wt_static_map_vl:') !== false) {
                            $static_feed_value = str_replace('wt_static_map_vl:', '', $value);
                            $row[$key] = $static_feed_value;
                        }else {
                            $row[$key] = '';
                        }
                    }



			return apply_filters('wt_batch_product_export_row_data', $row, $product);
		}

		/**
		 * Get product store code.
		 *
		 * @return mixed|void
		 */
		public function store_code($catalog_attr, $product_attr, $export_columns) {
			
			$store_code = Webtoffee_Product_Feed_Sync_Common_Helper::get_advanced_settings('glpi_store_code');
			if( '' === $store_code ){
				$store_code = wp_strip_all_tags(self::get_store_name());
			}
			return apply_filters('wt_feed_filter_product_store_code', $store_code, $this->product);
		}
		
		/**
		 * Get Packing method.
		 *
		 * @return mixed|void
		 */
		public function pickup_method($catalog_attr, $product_attr, $export_columns) {
			

			$packing_method = get_post_meta($this->product->get_id(), '_wt_feed_glpi_pickup_method', true);
                        if( '' == $packing_method ){
                            $packing_method = get_post_meta($this->product->get_id(), '_wt_google_glpi_pickup_method', true); 
                        }                         
			return apply_filters('wt_feed_filter_product_packing_method', $packing_method, $this->product);
		}

		/**
		 * Get Packing SLA.
		 *
		 * @return mixed|void
		 */
		public function pickup_sla($catalog_attr, $product_attr, $export_columns) {
			

			$packing_sla = get_post_meta($this->product->get_id(), '_wt_feed_glpi_pickup_sla', true);
                        if( '' == $packing_sla ){
                            $packing_sla = get_post_meta($this->product->get_id(), '_wt_google_glpi_pickup_sla', true); 
                        }                              
			return apply_filters('wt_feed_filter_product_packing_sla', $packing_sla, $this->product);
		}		

		/**
		 * Get product id.
		 *
		 * @return mixed|void
		 */
		public function id($catalog_attr, $product_attr, $export_columns) {
			
			return apply_filters('wt_feed_filter_product_id', $this->product->get_id(), $this->product);
		}
		
		public function sku($catalog_attr, $product_attr, $export_columns) {

			return apply_filters( 'wt_feed_filter_product_sku', $this->product->get_sku(), $this->product );
		}		

		public static function get_store_name() {

			$url = get_bloginfo('name');
			return ( $url) ? ( $url ) : 'My Store';
		}

		public function availability($catalog_attr, $product_attr, $export_columns) {
			$status = $this->product->get_stock_status();
			if ('instock' === $status) {
				$status = 'in_stock';
			} elseif ('outofstock' === $status) {
				$status = 'out_of_stock';
			} elseif ('onbackorder' === $status) {
				$status = 'backorder';
			} elseif ('preorder' === $status) {
				$status = 'preorder';
			}


			return apply_filters('wt_feed_filter_product_availability', $status, $this->product);
		}

		public function availability_date($catalog_attr, $product_attr, $export_columns) {

			$feed_settings = get_option('wt_feed_settings');

			$availability_date_settings = isset($feed_settings['wt_feed_identifier']['availability_date']) ? $feed_settings['wt_feed_identifier']['availability_date'] : 'enable';

			if ($this->product->get_stock_status() !== 'onbackorder' || $availability_date_settings === 'disable') {
				return '';
			}

			$meta_field_name = 'wt_feed_availability_date';

			if ($this->product->is_type('variation')) {
				$meta_field_name .= '_var';
			}

			$availability_date = get_post_meta($this->product->get_id(), $meta_field_name, true);

			if ('' !== $availability_date) {
				$availability_date = gmdate('c', strtotime($availability_date));
			}

			return apply_filters('wt_feed_filter_product_availability_date', $availability_date, $this->product);
		}

		public function quantity($catalog_attr, $product_attr, $export_columns) {
			$quantity = $this->product->get_stock_quantity();
			$status = $this->product->get_stock_status();

			//when product is outofstock , and it's quantity is empty, set quantity to 0
			if ('outofstock' === $status && $quantity === null) {
				$quantity = 0;
			}

			if ($this->product->is_type('variable') && $this->product->has_child()) {
				$visible_children = $this->product->get_visible_children();
				$qty = array();
				foreach ($visible_children as $child) {
					$childQty = get_post_meta($child, '_stock', true);
					$qty[] = (int) $childQty;
				}
				
				$quantity = array_sum($qty);
			}

			return apply_filters('wt_feed_filter_product_quantity', $quantity, $this->product);
		}

		/**
		 * Get Product Sale Price start date.
		 *
		 * @return mixed|void
		 */
		public function sale_price_sdate($catalog_attr, $product_attr, $export_columns) {
			$startDate = $this->product->get_date_on_sale_from();
			if (is_object($startDate)) {
				$sale_price_sdate = $startDate->date_i18n();
			} else {
				$sale_price_sdate = '';
			}

			return apply_filters('wt_feed_filter_product_sale_price_sdate', $sale_price_sdate, $this->product);
		}

		/**
		 * Get Product Sale Price End Date.
		 *
		 * @return mixed|void
		 */
		public function sale_price_edate($catalog_attr, $product_attr, $export_columns) {
			$endDate = $this->product->get_date_on_sale_to();
			if (is_object($endDate)) {
				$sale_price_edate = $endDate->date_i18n();
			} else {
				$sale_price_edate = "";
			}

			return apply_filters('wt_feed_filter_product_sale_price_edate', $sale_price_edate, $this->product);
		}

		public function first_variation_price() {

			$children = $this->product->get_visible_children();
			$price = $this->product->get_variation_price();
			if (isset($children[0]) && !empty($children[0])) {
				$variation = wc_get_product($children[0]);
				$price = $variation->get_price();
			}

			return apply_filters('wt_feed_filter_product_first_variation_price', $price, $this->product);
		}

		public function get_converted_price($price, $selected_currency) {

			if ($selected_currency !== get_woocommerce_currency() && $price > 0) {
				$wcml_mc = new WCML_Multi_Currency();
				$currencies = $wcml_mc->get_currencies(true);

				$woo_currencies = get_woocommerce_currencies();
				
				if ( !empty( $woo_currencies[$selected_currency]) && !empty( $currencies[$selected_currency] ) ) {
					$price = $price * $currencies[$selected_currency]['rate'];
				}
			}
			return $price;
		}

		public function price($catalog_attr, $product_attr, $export_columns) {
			$price = $this->product->get_regular_price();

			if ($this->product->is_type('variable')) {
				$price = $this->first_variation_price();
			}

			$selected_currency = get_woocommerce_currency();
			if (class_exists('WCML_Multi_Currency') && !empty($this->form_data['post_type_form_data']['item_post_currency'])) {
				$selected_currency = $this->form_data['post_type_form_data']['item_post_currency'];
				$price = $this->get_converted_price($price, $selected_currency);
			}
			if ($price > 0) {
				$price = $price . ' ' . $selected_currency;
			}

			return apply_filters('wt_feed_filter_product_price', $price, $this->product);
		}

		public function sale_price($catalog_attr, $product_attr, $export_columns) {
			$price = $this->product->get_sale_price();

			$selected_currency = get_woocommerce_currency();
			if (class_exists('WCML_Multi_Currency') && !empty($this->form_data['post_type_form_data']['item_post_currency'])) {
				$selected_currency = $this->form_data['post_type_form_data']['item_post_currency'];
				$price = $this->get_converted_price($price, $selected_currency);
			}

			if ($price > 0) {
				$price = $price . ' ' . $selected_currency;
			}
			return apply_filters('wt_feed_filter_product_sale_price', $price, $this->product);
		}
		
		public function price_with_tax($catalog_attr, $product_attr, $export_columns) {
						
			$tprice = $this->product->get_regular_price();
			$price = wc_get_price_including_tax( $this->product, array('price' => $tprice) );
			if ($price > 0) {
				$price = $price . ' ' . get_woocommerce_currency();
			}
			return apply_filters( 'wt_feed_filter_product_price_with_tax', $price, $this->product );
		}

		public function current_price_with_tax($catalog_attr, $product_attr, $export_columns) {
			$cprice = $this->product->get_price();
			$price = wc_get_price_including_tax( $this->product, array('price' => $cprice) );			
			if ($price > 0) {
				$price = $price . ' ' . get_woocommerce_currency();
			}
			return apply_filters( 'wt_feed_filter_product_current_price_with_tax', $price, $this->product );
		}

		public function sale_price_with_tax($catalog_attr, $product_attr, $export_columns) {
			$sprice = $this->product->get_sale_price();
			$price = wc_get_price_including_tax( $this->product, array('price' => $sprice) );
			if ($price > 0) {
				$price = $price . ' ' . get_woocommerce_currency();
			}
			return apply_filters( 'wt_feed_filter_product_sale_price_with_tax', $price, $this->product );
		}		

		/** Get Google Sale Price effective date.
		 *
		 * @return string
		 */
		public function sale_price_effective_date($catalog_attr, $product_attr, $export_columns) {
			$effective_date = '';
			$from = $this->sale_price_sdate($catalog_attr, $product_attr, $export_columns);
			$to = $this->sale_price_edate($catalog_attr, $product_attr, $export_columns);
			if (!empty($from) && !empty($to)) {
				$from = gmdate('c', strtotime($from));
				$to = gmdate('c', strtotime($to));

				$effective_date = $from . '/' . $to;
			}

			return $effective_date;
		}

	}

	
}
