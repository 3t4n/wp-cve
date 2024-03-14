<?php

if (!defined('WPINC')) {
    exit;
}

if (!class_exists('Webtoffee_Product_Feed_BONGExport')) {

    class Webtoffee_Product_Feed_BONGExport extends Webtoffee_Product_Feed_Product {

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
         * Get product id.
         *
         * @return mixed|void
         */
        public function id($catalog_attr, $product_attr, $export_columns) {

            return apply_filters('wt_feed_filter_product_id', $this->product->get_id(), $this->product);
        }

        public function sku($catalog_attr, $product_attr, $export_columns) {

            return apply_filters('wt_feed_filter_product_sku', $this->product->get_sku(), $this->product);
        }

        public static function get_store_name() {

            $url = get_bloginfo('name');
            return ( $url) ? ( $url ) : 'My Store';
        }

        /**
         * Get product name.
         *
         * @return mixed|void
         */
        public function title($catalog_attr, $product_attr, $export_columns) {

            $title = $this->product->get_name();

            // Add all available variation attributes to variation title.
            if ($this->product->is_type('variation') && !empty($this->product->get_attributes())) {
                $title = $this->parent_product->get_name();
                $attributes = [];
                foreach ($this->product->get_attributes() as $slug => $value) {
                    $attribute = $this->product->get_attribute($slug);
                    if (!empty($attribute)) {
                        $attributes[$slug] = $attribute;
                    }
                }

                // set variation attributes with separator
                $separator = ',';

                $variation_attributes = implode($separator, $attributes);

                //get product title with variation attribute
                $get_with_var_attributes = apply_filters("wt_feed_get_product_title_with_variation_attribute", true, $this->product);

                if ($get_with_var_attributes) {
                    $title .= " - " . $variation_attributes;
                }
            }

            return apply_filters('wt_feed_filter_product_title', $title, $this->product);
        }

        /**
         * Get parent product title for variation.
         *
         * @return mixed|void
         */
        public function parent_title($catalog_attr, $product_attr, $export_columns) {
            if ($this->product->is_type('variation')) {
                $title = $this->parent_product->get_name();
            } else {
                $title = $this->title();
            }

            return apply_filters('wt_feed_filter_product_parent_title', $title, $this->product);
        }

        /**
         * Get product description.
         *
         * @return mixed|void
         */
        public function description($catalog_attr, $product_attr, $export_columns) {
            $description = $this->product->get_description();

            // Get Variation Description
            if (  '' === $description  && $this->product->is_type( 'variation' ) ) {
                    $description = '';
                    $parent_product = wc_get_product( $this->product->get_parent_id() );
                    if (is_object($parent_product) ) {
                            $description = $parent_product->get_description();
                    }
            }

            if (  '' === $description  ) {
                    $description = $this->product->get_short_description();
            }

            // Add variations attributes after description to prevent Facebook error
            if ( $this->product->is_type( 'variation' ) && ( '' === $description ) ) {
                    $variationInfo = explode( '-', $this->product->get_name() );

                    if ( isset( $variationInfo[1] ) ) {
                            $extension = $variationInfo[1];
                    } else {
                            $extension = $this->product->get_id();
                    }
                    $description .= ' ' . $extension;
            }

            //strip tags and special characters
            $description = strip_tags($description);

            return apply_filters('wt_feed_filter_product_description', $description, $this->product);
        }

        /**
         * Get product URL.
         *
         * @return mixed|void
         */
        public function link($catalog_attr, $product_attr, $export_columns) {
            $link = $this->product->get_permalink();

            return apply_filters('wt_feed_filter_product_link', $link, $this->product);
        }

        /**
         * Get product type.
         *
         * @return mixed|void
         */
        public function product_type($catalog_attr, $product_attr, $export_columns) {

            $id = ( $this->product->is_type('variation') ? $this->product->get_parent_id() : $this->product->get_id() );

            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $product_categories = '';
            $term_list = get_the_terms($id, 'product_cat');

            if (is_array($term_list)) {
                $col = array_column($term_list, "term_id");
                array_multisort($col, SORT_ASC, $term_list);
                $term_list = array_column($term_list, "name");                
                $product_categories = implode(' > ', $term_list);
            }


            return apply_filters('wt_feed_filter_product_local_category', $product_categories, $this->product);
        }

        public function google_product_category($catalog_attr, $product_attr, $export_columns) {


            $custom_google_category = get_post_meta($this->current_product_id, '_wt_google_google_product_category', true);

            if ('' == $custom_google_category) {

                $category_path = wp_get_post_terms($this->current_product_id, 'product_cat', array('fields' => 'all'));

                $google_product_category = [];
                foreach ($category_path as $category) {
                    $google_category_id = get_term_meta($category->term_id, 'wt_google_category', true);
                    if ($google_category_id) {

                        $google_category_list = wp_cache_get('wt_fbfeed_google_product_categories_array');

                        if (false === $google_category_list) {
                            $google_category_list = Webtoffee_Product_Feed_Sync_Google::get_category_array();
                            wp_cache_set('wt_fbfeed_google_product_categories_array', $google_category_list, '', WEEK_IN_SECONDS);
                        }


                        $google_category = isset($google_category_list[$google_category_id]) ? $google_category_list[$google_category_id] : '';
                        if ('' !== $google_category) {
                            $google_product_category[] = $google_category;
                        }
                    }
                }


                $google_product_category = empty($google_product_category) ? '' : implode(', ', $google_product_category);
            } else {

                $google_category_list = wp_cache_get('wt_fbfeed_google_product_categories_array');

                if (false === $google_category_list) {
                    $google_category_list = Webtoffee_Product_Feed_Sync_Google::get_category_array();
                    wp_cache_set('wt_fbfeed_google_product_categories_array', $google_category_list, '', WEEK_IN_SECONDS);
                }

                $google_product_category = $google_category_list[$custom_google_category];
            }

            return apply_filters('wt_feed_filter_product_google_category', $google_product_category, $this->product);
        }

        /**
         * Get product image URL.
         *
         * @return mixed|void
         */
        public function image_link($catalog_attr, $product_attr, $export_columns) {
            $image = '';
            if ($this->product->is_type('variation')) {
                // Variation product type
                if (has_post_thumbnail($this->product->get_id())) {
                    $getImage = wp_get_attachment_image_src(get_post_thumbnail_id($this->product->get_id()), 'single-post-thumbnail');
                    $image = self::wt_feed_get_formatted_url($getImage[0]);
                } elseif (has_post_thumbnail($this->product->get_parent_id())) {
                    $getImage = wp_get_attachment_image_src(get_post_thumbnail_id($this->product->get_parent_id()), 'single-post-thumbnail');
                    $image = self::wt_feed_get_formatted_url($getImage[0]);
                }
            } elseif (has_post_thumbnail($this->product->get_id())) { // All product type except variation
                $getImage = wp_get_attachment_image_src(get_post_thumbnail_id($this->product->get_id()), 'single-post-thumbnail');
                $image = isset($getImage[0]) ? self::wt_feed_get_formatted_url($getImage[0]) : '';
            }
            if ('' === $image) {
                $image = 'https://via.placeholder.com/300';
            }
            return apply_filters('wt_feed_filter_product_image', $image, $this->product);
        }

	/**
	 * Get Formatted URL
	 *
	 * @param string $url
	 *
	 * @return string
	 */
	public static function wt_feed_get_formatted_url( $url = '' ) {
		if ( ! empty( $url ) ) {
			if ( substr( trim( $url ), 0, 4 ) === 'http' || substr( trim( $url ),
					0,
					3 ) === 'ftp' || substr( trim( $url ), 0, 4 ) === 'sftp' ) {
				return rtrim( $url, '/' );
			} else {
				$base = get_site_url();
				$url  = $base . $url;

				return rtrim( $url, '/' );
			}
		}

		return '';
	}        
        
        public function condition($catalog_attr, $product_attr, $export_columns) {

            $custom_condition = get_post_meta($this->product->get_id(), '_wt_feed_condition', true);                     		

            if( '' == $custom_condition ){
                $custom_condition = get_post_meta($this->product->get_id(), '_wt_google_condition', true); 
            }  
            
            $condition = ('' == $custom_condition) ? 'new' : $custom_condition;
            return apply_filters('wt_feed_product_condition', $condition, $this->product);
        }

        public function mpn($catalog_attr, $product_attr, $export_columns) {

            $custom_mpn = get_post_meta($this->product->get_id(), '_wt_feed_mpn', true);                        		

            if( '' == $custom_mpn ){
                $custom_mpn = get_post_meta($this->product->get_id(), '_wt_google_mpn', true); 
            }     
            
            $mpn = ('' == $custom_mpn) ? '' : $custom_mpn;
            return apply_filters('wt_feed_product_mpn', $mpn, $this->product);
        }

        public function brand($catalog_attr, $product_attr, $export_columns) {
		

		$custom_brand = get_post_meta($this->current_product_id, '_wt_feed_brand', true);
                if( '' == $custom_brand ){
                    $custom_brand = get_post_meta($this->product->get_id(), '_wt_google_brand', true); 
                } 			
		if( '' == $custom_brand ){
				
		$brand	 = get_the_term_list( $this->current_product_id, 'product_brand', '', ', ' );
                
                $has_brand = true;
                if( is_wp_error($brand) || false === $brand ){
                    $has_brand = false;
                }

                if( !$has_brand && is_plugin_active('perfect-woocommerce-brands/perfect-woocommerce-brands.php')){
                    $brand	 = get_the_term_list( $this->current_product_id, 'pwb-brand', '', ', ' );
                }
                
		$string	 = is_wp_error( $brand ) || !$brand ? wp_strip_all_tags( self::get_store_name() ) : self::clean_string( $brand );
		$length = 100;
			if ( extension_loaded( 'mbstring' ) ) {

				if ( mb_strlen( $string, 'UTF-8' ) <= $length ) {
					return apply_filters( 'wt_feed_filter_product_brand', $string, $this->product );
				}

				$length -= mb_strlen( '...', 'UTF-8' );

				$brand_string = mb_substr( $string, 0, $length, 'UTF-8' ) . '...';
				return apply_filters( 'wt_feed_filter_product_brand', $brand_string, $this->product );
			} else {

				$string	 = filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_LOW );
				$string	 = filter_var( $string, FILTER_SANITIZE_STRING, FILTER_FLAG_STRIP_HIGH );

				if ( strlen( $string ) <= $length ) {
					return apply_filters( 'wt_feed_filter_product_brand', $string, $this->product );
				}

				$length -= strlen( '...' );

				$brand_string = substr( $string, 0, $length ) . '...';
				return apply_filters( 'wt_feed_filter_product_brand', $brand_string, $this->product );
			}
		}	else{
			return apply_filters( 'wt_feed_filter_product_brand', $custom_brand, $this->product );
		}	
		
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

                if (!empty($woo_currencies[$selected_currency]) && !empty($currencies[$selected_currency])) {
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
            $price = wc_get_price_including_tax($this->product, array('price' => $tprice));
            if ($price > 0) {
                $price = $price . ' ' . get_woocommerce_currency();
            }
            return apply_filters('wt_feed_filter_product_price_with_tax', $price, $this->product);
        }

        public function current_price_with_tax($catalog_attr, $product_attr, $export_columns) {
            $cprice = $this->product->get_price();
            $price = wc_get_price_including_tax($this->product, array('price' => $cprice));
            if ($price > 0) {
                $price = $price . ' ' . get_woocommerce_currency();
            }
            return apply_filters('wt_feed_filter_product_current_price_with_tax', $price, $this->product);
        }

        public function sale_price_with_tax($catalog_attr, $product_attr, $export_columns) {
            $sprice = $this->product->get_sale_price();
            $price = wc_get_price_including_tax($this->product, array('price' => $sprice));
            if ($price > 0) {
                $price = $price . ' ' . get_woocommerce_currency();
            }
            return apply_filters('wt_feed_filter_product_sale_price_with_tax', $price, $this->product);
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
		/**
		 * Clean up strings for FB Graph POSTing.
		 * This function should will:
		 * 1. Replace newlines chars/nbsp with a real space
		 * 2. strip_tags()
		 * 3. trim()
		 *
		 * @access public
		 * @param String string
		 * @return string
		 */
		public static function clean_string($string) {
			$string = do_shortcode($string);
			$string = str_replace(array('&amp%3B', '&amp;'), '&', $string);
			$string = str_replace(array("\r", '&nbsp;', "\t"), ' ', $string);
			$string = wp_strip_all_tags($string, false); // true == remove line breaks
			return $string;
		}
                
                
	public function item_group_id($catalog_attr, $product_attr, $export_columns) {

		$id = ( $this->product->is_type( 'variation' ) ? $this->product->get_parent_id() : $this->product->get_id() );
		
		return apply_filters( 'wt_feed_filter_product_item_group_id', $id, $this->product );
	}
        
                
        public function sell_on_google_quantity($catalog_attr, $product_attr, $export_columns) {
            
        }

        public function min_handling_time($catalog_attr, $product_attr, $export_columns) {
            
        }

        public function max_handling_time($catalog_attr, $product_attr, $export_columns) {
            
        }

        public function return_address_label($catalog_attr, $product_attr, $export_columns) {
            
        }

        public function return_policy_label($catalog_attr, $product_attr, $export_columns) {
            
        }

        public function google_funded_promotion_eligibility($catalog_attr, $product_attr, $export_columns) {
            
        }

    }

}
