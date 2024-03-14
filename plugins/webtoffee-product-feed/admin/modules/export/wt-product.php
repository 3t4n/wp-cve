<?php

if (!defined('WPINC')) {
    exit;
}

if (!class_exists('Webtoffee_Product_Feed_Product')) {

    class Webtoffee_Product_Feed_Product {

        public $parent_product;
        public $current_product_id;
        public $product;

        public function __construct($product) {
            $this->parent_product = $product;
            $this->current_product_id = $product->get_id();
            $this->product = $product;
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
            if ('' === $description && $this->product->is_type('variation')) {
                $description = '';
                $parent_product = wc_get_product($this->product->get_parent_id());
                if (is_object($parent_product)) {
                    $description = $parent_product->get_description();
                }
            }

            if ('' === $description) {
                $description = $this->product->get_short_description();
            }

            // Add variations attributes after description to prevent Google error
            if ($this->product->is_type('variation') && ( '' === $description )) {
                $variationInfo = explode('-', $this->product->get_name());
                if (isset($variationInfo[1])) {
                    $extension = $variationInfo[1];
                } else {
                    $extension = $this->product->get_id();
                }
                $description .= ' ' . $extension;
            }

            //strip tags and special characters
            $description = trim( strip_tags($description) );

            return apply_filters('wt_feed_filter_product_description', $description, $this->product);
        }

        /**
         * Get product description with HTML tags.
         *
         * @return mixed|void
         */
        public function description_with_html($catalog_attr, $product_attr, $export_columns) {
            $description = $this->product->get_description();

            // Get Variation Description
            if (empty($description) && $this->product->is_type('variation')) {
                $description = '';
                if (!is_null($this->parent_product)) {
                    $description = $this->parent_product->get_description();
                }
            }

            if (empty($description)) {
                $description = $this->product->get_short_description();
            }

            //$description = CommonHelper::remove_shortcodes( $description );
            // Add variations attributes after description to prevent Facebook error
            if ($this->product->is_type('variation')) {
                $variationInfo = explode('-', $this->product->get_name());
                if (isset($variationInfo[1])) {
                    $extension = $variationInfo[1];
                } else {
                    $extension = $this->product->get_id();
                }
                $description .= ' ' . $extension;
            }

            //remove spacial characters
            $description = wp_check_invalid_utf8(wp_specialchars_decode($description), true);

            return apply_filters('wt_feed_filter_product_description_with_html', $description, $this->product);
        }

        /**
         * Get product short description.
         *
         * @return mixed|void
         */
        public function short_description($catalog_attr, $product_attr, $export_columns) {
            $short_description = $this->product->get_short_description();

            // Get Variation Short Description
            if (empty($short_description) && $this->product->is_type('variation')) {
                $short_description = $this->parent_product->get_short_description();
            }


            // Strip tags and special characters
            $short_description = strip_tags($short_description);

            return apply_filters('wt_feed_filter_product_short_description', $short_description, $this->product);
        }

        /**
         * Get product primary category.
         *
         * @return mixed|void
         */
        public function primary_category($catalog_attr, $product_attr, $export_columns) {
            $parent_category = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');

            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $parent_category = $full_category_array[0];
            }

            return apply_filters('wt_feed_filter_product_primary_category', $parent_category, $this->product);
        }

        /**
         * Get product primary category id.
         *
         * @return mixed|void
         */
        public function primary_category_id($catalog_attr, $product_attr, $export_columns) {
            $parent_category_id = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $parent_category_obj = get_term_by('name', $full_category_array[0], 'product_cat');
                $parent_category_id = isset($parent_category_obj->term_id) ? $parent_category_obj->term_id : "";
            }

            return apply_filters('wt_feed_filter_product_primary_category_id', $parent_category_id, $this->product);
        }

        /**
         * Get product child category.
         *
         * @return mixed|void
         */
        public function child_category($catalog_attr, $product_attr, $export_columns) {
            $child_category = "";
            $separator = apply_filters('wt_feed_product_type_separator', ' > ');
            $full_category = $this->product_type();
            if (!empty($full_category)) {
                $full_category_array = explode($separator, $full_category);
                $child_category = end($full_category_array);
            }

            return apply_filters('wt_feed_filter_product_child_category', $child_category, $this->product);
        }
        
        public function material($catalog_attr, $product_attr, $export_columns) {

            $material = get_post_meta($this->product->get_id(), '_wt_feed_material', true);
          
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_material", $material, $this->product);
        }

        public function pattern($catalog_attr, $product_attr, $export_columns) {

            $pattern = get_post_meta($this->product->get_id(), '_wt_feed_pattern', true);
           
            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_pattern", $pattern, $this->product);
        }    
        
        

        public function custom_label_0($catalog_attr, $product_attr, $export_columns) {

            $custom_label_0 = get_post_meta($this->product->get_id(), '_wt_feed_custom_label_0', true);

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_custom_label_0", $custom_label_0, $this->product);
        }

        public function custom_label_1($catalog_attr, $product_attr, $export_columns) {

            $custom_label_1 = get_post_meta($this->product->get_id(), '_wt_feed_custom_label_1', true);

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_custom_label_1", $custom_label_1, $this->product);
        }

        public function custom_label_2($catalog_attr, $product_attr, $export_columns) {

            $custom_label_2 = get_post_meta($this->product->get_id(), '_wt_feed_custom_label_2', true);

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_custom_label_2", $custom_label_2, $this->product);
        }

        public function custom_label_3($catalog_attr, $product_attr, $export_columns) {

            $custom_label_3 = get_post_meta($this->product->get_id(), '_wt_feed_custom_label_3', true);

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_custom_label_3", $custom_label_3, $this->product);
        }

        public function custom_label_4($catalog_attr, $product_attr, $export_columns) {

            $custom_label_4 = get_post_meta($this->product->get_id(), '_wt_feed_custom_label_4', true);

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_custom_label_4", $custom_label_4, $this->product);
        }
        
        public function parent_sku($catalog_attr, $product_attr, $export_columns) {

            $parent_sku = $this->product->get_sku();
            if ($this->product->is_type('variation')) {
                $parent_prod = wc_get_product($this->product->get_parent_id());
                if (is_object($parent_prod)) {
                    $parent_sku = $parent_prod->get_sku();
                }
            }

            return apply_filters("wt_feed_{$this->parent_module->module_base}_product_parent_sku", $parent_sku, $this->product);
        }  
        
        
        /**
         * Get additional attributes for variation other than color, size, gender and pattern.
         *
         * @return mixed|void
         */
        public function additional_variant_attribute($catalog_attr, $product_attr, $export_columns) {

            if ( !$this->product->is_type( 'variation' ) ) {
				return '';
			}

			$attributes = $this->product->get_variation_attributes();


			if ( !$attributes ) {
				return [];
			}
                        $product_data = [];
			$variant_names	 = array_keys( $attributes );
			$variant_data	 = [];

			foreach ( $variant_names as $original_variant_name ) {


				$label = wc_attribute_label( $original_variant_name, $this->product );

				$new_name = str_replace( 'custom_data:', '', self::sanitize_variant_name( $original_variant_name ) );


				if ( $options = $this->get_variant_option_name( $this->product->get_id(), $label, $attributes[ $original_variant_name ] ) ) {

					if ( is_array( $options ) ) {

						$option_values = array_values( $options );
					} else {

						$option_values = [ $options ];

						if ( count( $option_values ) === 1 && empty( $option_values[ 0 ] ) ) {
							$option_values[ 0 ]				 = 'any';
							$product_data[ 'checkout_url' ]	 = $product_data[ 'url' ];
						}
					}

					if ( 'gender' === $new_name ) {

						$product_data[ $new_name ] = $option_values[ 0 ];
					}

					switch ( $new_name ) {

						case 'size':
						case 'color':
                                                    break;
						case 'pattern':

							$variant_data[] = [
								'product_field'	 => $new_name,
								'label'			 => $label,
								'options'		 => $option_values,
							];

							$product_data[ $new_name ] = $option_values[ 0 ];

							break;

						case 'gender':

							if ( $product_data[ $new_name ] ) {

								$variant_data[] = [
									'product_field'	 => $new_name,
									'label'			 => $label,
									'options'		 => $option_values,
								];
							}

							break;

						default:

                                                        if(!isset($product_data)){
                                                            $product_data = [];
                                                        }
                                                        $variant_details = array(
                                                            'label' => ucwords( $new_name ) ,
                                                            'value' => urldecode( $option_values[ 0 ] ),
                                                        );
                                                        
                                                        array_push($product_data, $variant_details);//							
							break;
					}
                                    } else {

					continue;
				}
			}
                        if( 'xml' !== $this->form_data['advanced_form_data']['wt_pf_file_as'] ){
                            $product_data_str = '';
                            foreach($product_data as $product_data_single){
                                $product_data_str.= $product_data_single['label'].':'.$product_data_single['value'].',';
                            }
                            $product_data = rtrim(trim($product_data_str), ',');
                        }


            return apply_filters("wt_feed_{$this->parent_module->module_base}_additional_variant_attributes", $product_data, $this->product);
        }

        public function get_variant_option_name( $product_id, $label, $default_value ) {

                $meta			 = get_post_meta( $product_id, $label, true );
                $attribute_name	 = str_replace( 'attribute_', '', $label );
                $term			 = get_term_by( 'slug', $meta, $attribute_name );
                return ( $term && $term->name ) ? $term->name : $default_value;
        }   
        public static function sanitize_variant_name( $name ) {

                $name = str_replace( array( 'attribute_', 'pa_' ), '', strtolower( $name ) );

                if ( 'colour' === $name ) {
                        $name = 'color';
                }

                switch ( $name ) {
                        case 'size':
                        case 'color':
                        case 'gender':
                        case 'pattern':
                                break;
                        default:
                                $name = 'custom_data:' . strtolower( $name );
                                break;
                }

                return $name;
        }
        

    }

}

// Custom taxonomy filter for get product query

add_filter('woocommerce_product_data_store_cpt_get_products_query', 'wt_exclude_product_taxonomy_query', 10, 2);
if (!function_exists('wt_exclude_product_taxonomy_query')) {

    function wt_exclude_product_taxonomy_query($query, $query_vars) {
        if (!empty($query_vars['exclude_category'])) {

            $query['tax_query'][] = array(
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => $query_vars['exclude_category'],
                'operator' => 'NOT IN',
            );
        }
        if (!empty($query_vars['exclude_discarded'])) {
            $query['meta_query'][] = array(
                array(
                    'key' => '_wt_feed_discard',
                    'compare' => 'NOT EXISTS' // this should exclude all exclude from feed checked products
                ),
            );
        }

        if (!empty($query_vars['exclude_brands'])) {

            $query['tax_query'][] = array(
                'taxonomy' => 'pwb-brand',
                'field' => 'slug',
                'terms' => $query_vars['exclude_brands'],
                'operator' => 'NOT IN',
            );
        }
        if (!empty($query_vars['include_brands'])) {

            $query['tax_query'][] = array(
                'taxonomy' => 'pwb-brand',
                'field' => 'slug',
                'terms' => $query_vars['include_brands'],
                'operator' => 'IN',
            );
        }

        if (!empty($query_vars['exclude_tag'])) {

            $query['tax_query'][] = array(
                'taxonomy' => 'product_tag',
                'field' => 'slug',
                'terms' => $query_vars['exclude_tag'],
                'operator' => 'NOT IN',
            );
        }
        return $query;
    }

}