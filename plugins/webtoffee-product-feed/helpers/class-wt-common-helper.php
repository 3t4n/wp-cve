<?php
if(!class_exists('Webtoffee_Product_Feed_Sync_Common_Helper')){
class Webtoffee_Product_Feed_Sync_Common_Helper
{
	
    public static $min_version_msg='';

   /**
   *  Check the minimum base version required for post type modules
   *
   */
    public static function check_base_version($post_type, $post_type_title, $min_version)
    {
        $warn_icon='<span class="dashicons dashicons-warning"></span>&nbsp;';
        if(!version_compare(WEBTOFFEE_PRODUCT_FEED_SYNC_VERSION, $min_version, '>=')) /* not matching the min version */
        {
            self::$min_version_msg.=$warn_icon.sprintf(__("The %s requires a minimum version of %s %s. Please upgrade the %s accordingly."), "<b>$post_type_title</b>", "<b>".WT_P_IEW_PLUGIN_NAME."</b>", "<b>v$min_version</b>", "<b>".WT_P_IEW_PLUGIN_NAME."</b>").'<br />';
            add_action('admin_notices', array(__CLASS__, 'no_minimum_base_version') );
            return false;
        }
        return true;
    }

    /**
    *
    *   No minimum version error message
    */
    public static function no_minimum_base_version()
    {
        ?>
        <div class="notice notice-warning">
            <p>
                <?php 
                echo self::$min_version_msg;
                ?>
            </p>
        </div>
        <?php
    }

		/**
	 * Gets the product categories.
	 * 
	 * @return array
	 */
	public static function get_product_categories($slugged=false) {

                $out = array();
                $category_args = [
                            'taxonomy'		 => 'product_cat',
                            'orderby'		 => 'term_group',
                            'title_li'		 => '',
                            'hide_empty'	 => 1,
                ];
                $product_categories = get_categories( $category_args );
                if (!is_wp_error($product_categories)) {
                    foreach ($product_categories as $category) {
                            $out[$category->term_id] =  array( 
                                    'slug' => $category->slug,
                                    'name' => $category->name
                                );
                    }
                }
                return $out;
	}
	
	
	/**
	 * Local Attribute List to map product value with merchant attributes
	 *
	 * @param string $selected
	 *
	 * @return string
	 */
	public static function attribute_dropdown( $export_channel, $selected = '' ) {
		
		$attribute_dropdown = wp_cache_get( 'wt_feed_dropdown_product_attributes_v7' );

		if ( false === $attribute_dropdown ) {
			$attributes = array(
				'id'                        => esc_attr__( 'Product Id' ),
				'title'                     => esc_attr__( 'Product Title' ),
				'description'               => esc_attr__( 'Product Description' ),
				'short_description'         => esc_attr__( 'Product Short Description' ),
				'product_type'              => esc_attr__( 'Product Local Category' ),
				'link'                      => esc_attr__( 'Product URL' ),
				'ex_link'                   => esc_attr__( 'External Product URL' ),
				'condition'                 => esc_attr__( 'Condition' ),
				'item_group_id'             => esc_attr__( 'Parent Id [Group Id]' ),
				'sku'                       => esc_attr__( 'SKU' ),
				'sku_id'                    => esc_attr__( 'SKU+ID[sku_id]' ),
				'parent_sku'                => esc_attr__( 'Parent SKU' ),
				'availability'              => esc_attr__( 'Availability' ),
				'quantity'                  => esc_attr__( 'Quantity' ),
				'price'                     => esc_attr__( 'Regular Price' ),
				'current_price'             => esc_attr__( 'Price' ),
				'sale_price'                => esc_attr__( 'Sale Price' ),
				'price_with_tax'            => esc_attr__( 'Regular Price With Tax' ),
				'current_price_with_tax'    => esc_attr__( 'Price With Tax' ),
				'sale_price_with_tax'       => esc_attr__( 'Sale Price With Tax' ),
				'sale_price_sdate'          => esc_attr__( 'Sale Start Date' ),
				'sale_price_edate'          => esc_attr__( 'Sale End Date' ),
				'weight'                    => esc_attr__( 'Weight' ),
				'width'                     => esc_attr__( 'Width' ),
				'height'                    => esc_attr__( 'Height' ),
				'length'                    => esc_attr__( 'Length' ),
				'shipping_class'            => esc_attr__( 'Shipping Class' ),
				'type'                      => esc_attr__( 'Product Type' ),
				'variation_type'            => esc_attr__( 'Variation Type' ),
				'visibility'                => esc_attr__( 'Visibility' ),
				'rating_total'              => esc_attr__( 'Total Rating' ),
				'rating_average'            => esc_attr__( 'Average Rating' ),
				'tags'                      => esc_attr__( 'Tags' ),
				'sale_price_effective_date' => esc_attr__( 'Sale Price Effective Date' ),
				'is_bundle'                 => esc_attr__( 'Is Bundle' ),
				'author_name'               => esc_attr__( 'Author Name' ),
				'author_email'              => esc_attr__( 'Author Email' ),
				'date_created'              => esc_attr__( 'Date Created' ),
				'date_updated'              => esc_attr__( 'Date Updated' ),
				'identifier_exists'              => esc_attr__( 'Identifier Exists' ),
                                'promotion_id'              => esc_attr__( 'Product Id' ),
                                'long_title'                => esc_attr__( 'Product Title' ),
                                'promotion_effective_dates' => esc_attr__( 'Promotion effective dates' ), 
                            
                                // PriceRunner fields
                                'ProductId' => 'Product Id[ProductId]',
                                'ProductName' => 'Product Title[ProductName]',
                                'Description' => 'Product Description[Description]',
                                'Url' => 'Product URL[Url]',
                                'Category' => 'Product Categories[Category] ',
                                'ImageUrl' => 'Main Image[ImageUrl]',
                                'Condition' => 'Condition[condition]',
                                'Price' => 'Price[Price]',
                                'ShippingCost' => 'ShippingCost[ShippingCost]',
                                'StockStatus' => 'StockStatus[StockStatus]',
                                'LeadTime' => 'LeadTime[LeadTime]',
                                'Brand' => 'Brand[Brand]',
                                'Msku' => 'Msku[Msku]',
                                'Ean' => 'Ean[Ean]',
                                'AdultContent' => 'AdultContent[AdultContent]',
                                'AgeGroup' => 'AgeGroup[AgeGroup]',
                                'Bundled' => 'Bundled[Bundled]',
                                'Multipack' => 'Multipack[Multipack]',
                                'Pattern' => 'Pattern[Pattern]',
                                'Size' => 'Size[Size]',
                                'SizeSystem' => 'SizeSystem[SizeSystem]',
                                'Color' => 'Color[Color]',
                                'EnergyEfficiencyClass' => 'EnergyEfficiencyClass[EnergyEfficiencyClass]',
                                'Gender' => 'Gender[Gender]',
                                'Material' => 'Material[Material]',
                                'GroupId' => 'GroupId[GroupId]',
			);
			$images     = array(
				'image_link'    => esc_attr__( 'Main Image' ),
				'feature_image' => esc_attr__( 'Featured Image' ),
				'additional_image_link'        => esc_attr__( 'Images [Comma Separated]' ),
				'wtimages_1'       => esc_attr__( 'Additional Image 1' ),
				'wtimages_2'       => esc_attr__( 'Additional Image 2' ),
				'wtimages_3'       => esc_attr__( 'Additional Image 3' ),
				'wtimages_4'       => esc_attr__( 'Additional Image 4' ),
				'wtimages_5'       => esc_attr__( 'Additional Image 5' ),
				'wtimages_6'       => esc_attr__( 'Additional Image 6' ),
				'wtimages_7'       => esc_attr__( 'Additional Image 7' ),
				'wtimages_8'       => esc_attr__( 'Additional Image 8' ),
				'wtimages_9'       => esc_attr__( 'Additional Image 9' ),
				'wtimages_10'      => esc_attr__( 'Additional Image 10' ),
			);
			
			$attribute_dropdown = '<option></option>';
			$attribute_dropdown .= sprintf( '<optgroup label="%s">', esc_attr__( 'Constant' ) );
			$attribute_dropdown .= sprintf( '<option style="font-weight: bold;" value="%s">%s</option>', 'wt-static-map-vl', esc_attr__( 'Static value' ) );
			$attribute_dropdown .= '</optgroup>';			

			if ( is_array( $attributes ) && ! empty( $attributes ) ) {
				$attribute_dropdown .= sprintf( '<optgroup label="%s">', esc_attr__( 'Primary Attributes' ) );
				foreach ( $attributes as $key => $value ) {
					$attribute_dropdown .= sprintf( '<option value="%s">%s</option>', $key, $value );
				}
				$attribute_dropdown .= '</optgroup>';
			}
			
			if ( is_array( $images ) && ! empty( $images ) ) {
				$attribute_dropdown .= sprintf( '<optgroup label="%s">', esc_attr__( 'Image Attributes' ) );
				foreach ( $images as $key => $value ) {
					$attribute_dropdown .= sprintf( '<option value="%s">%s</option>', $key, $value );
				}
				$attribute_dropdown .= '</optgroup>';
			}
			
			
			$product_metas = self::get_product_metakeys();
			if ( is_array( $product_metas ) && ! empty( $product_metas ) ) {
				$attribute_dropdown .= sprintf( '<optgroup label="%s">', esc_attr__( 'Custom Fields/Post Meta' ) );
				foreach ( $product_metas as $key => $value ) {
					$attribute_dropdown .= sprintf( '<option value="%s">%s</option>', $key, $value );
				}
				$attribute_dropdown .= '</optgroup>';
			}
			
			$product_global_attrs = self::get_global_attributes();
			if ( is_array( $product_global_attrs ) && ! empty( $product_global_attrs ) ) {
				$attribute_dropdown .= sprintf( '<optgroup label="%s">', esc_attr__( 'Product Attributes' ) );
				foreach ( $product_global_attrs as $key => $value ) {
					$attribute_dropdown .= sprintf( '<option value="%s">%s</option>', $key, $value );
				}
				$attribute_dropdown .= '</optgroup>';
			}
                        
			$product_local_attrs = self::get_local_attributes();
			if ( is_array( $product_local_attrs ) && ! empty( $product_local_attrs ) ) {
				$attribute_dropdown .= sprintf( '<optgroup label="%s">', esc_attr__( 'Product Custom Attributes' ) );
				foreach ( $product_local_attrs as $key => $value ) {
					$attribute_dropdown .= sprintf( '<option value="%s">%s</option>', $key, $value );
				}
				$attribute_dropdown .= '</optgroup>';
			}                        
                        
			wp_cache_add( 'wt_feed_dropdown_product_attributes_v7', $attribute_dropdown, '', WEEK_IN_SECONDS );
		}
		
		if( $selected && strpos($selected, 'wt_static_map_vl:') !== false ){
			$selected = 'wt-static-map-vl';
		}
		if ( $selected && strpos( $attribute_dropdown, 'value="' . $selected . '"' ) !== false ) {
			$attribute_dropdown = str_replace( 'value="' . $selected . '"', 'value="' . $selected . '"' . ' selected', $attribute_dropdown );
		}
		
		return apply_filters( 'wt_feed_product_attributes_dropdown', $attribute_dropdown, $export_channel, $selected);
	}
	
	
	
	/**
	 * Get All Custom Attributes
	 *
	 * @return array
	 */
	private static function get_product_metakeys() {
		$attribute_dropdown = wp_cache_get( 'wt_feed_dropdown_product_custom_meta_v7' );
		if ( false === $attribute_dropdown ) {
			global $wpdb;
			$attribute_dropdown = [];

			$attribute_dropdown['fb_product_category'] =  __('Facebook Product Category', 'webtoffee-product-feed');
			$attribute_dropdown['google_product_category'] = __('Google Product Category', 'webtoffee-product-feed');
			$attribute_dropdown['brand'] = __( 'Brand', 'webtoffee-product-feed' );
			$attribute_dropdown['gtin'] = __( 'GTIN', 'webtoffee-product-feed' );
			$attribute_dropdown['mpn'] = __( 'MPN', 'webtoffee-product-feed' );
			$attribute_dropdown['age_group'] = __( 'Age group', 'webtoffee-product-feed' );
			$attribute_dropdown['gender'] = __( 'Gender', 'webtoffee-product-feed' );
			$attribute_dropdown['color'] = __( 'Color', 'webtoffee-product-feed' );
			$attribute_dropdown['size'] = __( 'Size', 'webtoffee-product-feed' );
			$attribute_dropdown['material'] = __( 'Material', 'webtoffee-product-feed' );
			$attribute_dropdown['pattern'] = __( 'Pattern', 'webtoffee-product-feed' );                        
			$attribute_dropdown['unit_pricing_measure'] = __( 'Unit pricing measure', 'webtoffee-product-feed' );
			$attribute_dropdown['unit_pricing_base_measure'] = __( 'Unit pricing base measure', 'webtoffee-product-feed' );
			$attribute_dropdown['energy_efficiency_class'] = __( 'Energy efficiency class', 'webtoffee-product-feed' );
			$attribute_dropdown['min_energy_efficiency_class'] = __( 'Min energy efficiencycclass', 'webtoffee-product-feed' );
			$attribute_dropdown['max_energy_efficiency_class'] = __( 'Max energy efficiency class', 'webtoffee-product-feed' );                        
			$attribute_dropdown['shipping_data'] = __('Shipping', 'webtoffee-product-feed');						
                        
			$attribute_dropdown['pickup_method'] = __( 'Pickup Method', 'webtoffee-product-feed' );
			$attribute_dropdown['pickup_sla'] = __( 'Pickup SLA', 'webtoffee-product-feed' );
			
			$attribute_dropdown['custom_label_0'] = __( 'Custom label 0', 'webtoffee-product-feed' );
			$attribute_dropdown['custom_label_1'] = __( 'Custom label 1', 'webtoffee-product-feed' );
			$attribute_dropdown['custom_label_2'] = __( 'Custom label 2', 'webtoffee-product-feed' );
			$attribute_dropdown['custom_label_3'] = __( 'Custom label 3', 'webtoffee-product-feed' );
			$attribute_dropdown['custom_label_4'] = __( 'Custom label 4', 'webtoffee-product-feed' ); 
                        $attribute_dropdown['additional_variant_attribute'] = __( 'additional_variant_attribute', 'webtoffee-product-feed' );
                        
			$default_exclude_keys = [
				// WP internals.
				'_edit_lock',
				'_wp_old_slug',
				'_edit_last',
				'_wp_old_date',
				// WC internals.
				'_downloadable_files',
				'_sku',
				'_weight',
				'_width',
				'_height',
				'_length',
				'_file_path',
				'_file_paths',
				'_default_attributes',
				'_product_attributes',
				'_children',
				'_variation_description',
				// ignore variation description, engine will get child product description from WC CRUD WC_Product::get_description().
				// Plugin Data.
				'_wpcom_is_markdown',
				// JetPack Meta.
				'_yith_wcpb_bundle_data',
				// Yith product bundle data.
				'_et_builder_version',
				// Divi builder data.
				'_vc_post_settings',
				// Visual Composer (WP Bakery) data.
				'_enable_sidebar',
				'frs_woo_product_tabs',
			];
			
			/**
			 * Exclude meta keys from dropdown
			 *
			 * @param array $exclude              meta keys to exclude.
			 * @param array $default_exclude_keys Exclude keys by default.
			 */
			$user_exclude = apply_filters( 'wt_feed_dropdown_exclude_meta_keys', null, $default_exclude_keys );
			
			if ( is_array( $user_exclude ) && ! empty( $user_exclude ) ) {
				$user_exclude         = esc_sql( $user_exclude );
				$default_exclude_keys = array_merge( $default_exclude_keys, $user_exclude );
			}
			
			$default_exclude_keys = array_map( 'esc_sql', $default_exclude_keys );
			$exclude_keys         = '\'' . implode( '\', \'', $default_exclude_keys ) . '\'';
			
			$default_exclude_key_patterns = [
				'%_et_pb_%', // Divi builder data
				'attribute_%', // Exclude product attributes from meta list
				'_yoast_wpseo_%', // Yoast SEO Data
				'_acf-%', // ACF duplicate fields
				'_aioseop_%', // All In One SEO Pack Data
				'_oembed%', // exclude oEmbed cache meta
				'_wpml_%', // wpml metas
				'_oh_add_script_%', // SOGO Add Script to Individual Pages Header Footer.
                                '_wt_facebook_%', // This plugin meta
                                '_wt_google_%', // This plugin meta
                                '_wt_feed_%', // This plugin meta
			];
			
			/**
			 * Exclude meta key patterns from dropdown
			 *
			 * @param array $exclude                      meta keys to exclude.
			 * @param array $default_exclude_key_patterns Exclude keys by default.
			 */
			$user_exclude_patterns = apply_filters( 'wt_feed_dropdown_exclude_meta_keys_pattern', null, $default_exclude_key_patterns );
			if ( is_array( $user_exclude_patterns ) && ! empty( $user_exclude_patterns ) ) {
				$default_exclude_key_patterns = array_merge( $default_exclude_key_patterns, $user_exclude_patterns );
			}
			$exclude_key_patterns = '';
			foreach ( $default_exclude_key_patterns as $pattern ) {
				$exclude_key_patterns .= $wpdb->prepare( ' AND meta_key NOT LIKE %s', $pattern );
			}
			
			$sql = sprintf( /** @lang text */ "SELECT DISTINCT( meta_key ) FROM %s WHERE 1=1 AND post_id IN ( SELECT ID FROM %s WHERE post_type = 'product' OR post_type = 'product_variation' ) AND ( meta_key NOT IN ( %s ) %s )", $wpdb->postmeta, $wpdb->posts, $exclude_keys, $exclude_key_patterns );
			
			// sql escaped, cached
			$data = $wpdb->get_results( $sql ); // phpcs:ignore
			
			if ( count( $data ) ) {
				foreach ( $data as $value ) {
					//TODO Remove ACF Fields
					$attribute_dropdown[ 'meta:' . $value->meta_key ] = $value->meta_key;
				}
			}

			wp_cache_add( 'wt_feed_dropdown_product_custom_meta_v7', $attribute_dropdown, '', WEEK_IN_SECONDS );

		}
		
		return $attribute_dropdown;
	}
	
        
	public static function get_global_attributes() {

                $global_attribute_dropdown = wp_cache_get( 'wt_feed_dropdown_product_global_attr_v4' );
		if ( false === $global_attribute_dropdown ) {
			// Load the main attributes
			$global_attributes = wc_get_attribute_taxonomy_labels();
			if ( count( $global_attributes ) ) {
				foreach ( $global_attributes as $key => $value ) {
					$global_attribute_dropdown['wt_pf_pa_' . $key ] = $value;
				}
			}
			wp_cache_set( 'wt_feed_dropdown_product_global_attr_v1', $global_attribute_dropdown, '', WEEK_IN_SECONDS );
		}
                return apply_filters( 'wt_feed_product_global_attributes_fields', $global_attribute_dropdown );
	}        
        
	public static function get_local_attributes() {
		$attributes = wp_cache_get( 'wt_feed_dropdown_product_local_attr_v4' );
		if ( false === $attributes ) {	
			$attributes = self::get_variations_attributes();
			$attributes += self::get_product_custom_attributes();

			wp_cache_set( 'wt_feed_dropdown_product_local_attr_v1', $attributes, '', WEEK_IN_SECONDS );
		}
                return apply_filters( 'wt_feed_product_local_attributes_fields', $attributes );
	}

	public static function get_variations_attributes() {

		global $wpdb;
		$attributes = array();
		$sql        = "SELECT DISTINCT( meta_key ) FROM $wpdb->postmeta
			WHERE post_id IN (
			    SELECT ID FROM $wpdb->posts WHERE post_type = 'product_variation' -- local attributes will be found on variation product meta only with attribute_ suffix
			) AND (
			    meta_key LIKE 'attribute_%' -- include only product attributes from meta list
			    AND meta_key NOT LIKE 'attribute_pa_%'
			)";
		$local_attributes = $wpdb->get_col( $sql );
		foreach ( $local_attributes as $local_attribute ) {
			$local_attribute  = str_replace( 'attribute_', '', $local_attribute );
			$attributes[ 'wt_pf_cattr_' . $local_attribute ] = ucwords( str_replace( '-', ' ', $local_attribute ) );
		}

		return $attributes;
	}


	public static function get_product_custom_attributes() {
		global $wpdb;
		$attributes       = array();
		$sql              = 'SELECT meta.meta_id, meta.meta_key as name, meta.meta_value as type FROM ' . $wpdb->postmeta . ' AS meta, ' . $wpdb->posts . " AS posts WHERE meta.post_id = posts.id AND posts.post_type LIKE '%product%' AND meta.meta_key='_product_attributes';";
		$custom_attributes = $wpdb->get_results( $sql );
		if ( ! empty( $custom_attributes ) ) {
			foreach ( $custom_attributes as $value ) {
				$product_attr = maybe_unserialize( $value->type );
				if ( is_array( $product_attr ) ) {
					foreach ( $product_attr as $key => $arr_value ) {
						if ( strpos( $key, 'pa_' ) === false ) {
							$attributes[ 'wt_pf_cattr_'. $key ] = ucwords( str_replace( '-', ' ', $arr_value['name'] ) );
						}
					}
				}
			}
		}

		return $attributes;
	}        	        
	
	public static function get_geneder_list(){
			$gender_options = array(
		'male'           => _x( 'Male', 'product gender', 'webtoffee-product-feed' ),
		'female'   => _x( 'Female', 'product gender', 'webtoffee-product-feed' ),
		'unisex'          => _x( 'Unisex', 'product gender', 'webtoffee-product-feed' ),
	);

	return apply_filters( 'wt_feed_product_gender_options', $gender_options );
	}
        
	public static function get_age_group(){
		$age_group	 = array(
			'all ages' => __( 'All ages', 'webtoffee-product-feed' ),
			'adult' => __( 'Adult', 'webtoffee-product-feed' ),
			'teen' => __( 'Teen', 'webtoffee-product-feed' ),
			'kids' => __( 'Kids', 'webtoffee-product-feed' ),
			'toddler' => __( 'Toddler', 'webtoffee-product-feed' ),
			'infant' => __( 'Infant', 'webtoffee-product-feed' ),
			'newborn' => __( 'Newborn', 'webtoffee-product-feed' )
		);

		return apply_filters( 'wt_feed_product_agegroup', $age_group );
	}        
        public static function wt_feed_get_product_conditions() {
                $conditions = array(
                        'new'           => _x( 'New', 'product condition', 'webtoffee-product-feed' ),
                        'refurbished'   => _x( 'Refurbished', 'product condition', 'webtoffee-product-feed' ),
                        'used'          => _x( 'Used', 'product condition', 'webtoffee-product-feed' ),
                        'used_like_new' => _x( 'Used like new', 'product condition', 'webtoffee-product-feed' ),
                        'used_good'     => _x( 'Used good', 'product condition', 'webtoffee-product-feed' ),
                        'used_fair'     => _x( 'Used fair', 'product condition', 'webtoffee-product-feed' ),
                );

                return apply_filters( 'wt_feed_facebook_product_conditions', $conditions );
        }	
	
    /**
    *   Decode the post data as normal array from json encoded from data.
    *   If step key is specified, then it will return the data corresponds to the form key
    *   @param array $form_data
    *   @param string $key
    */
    public static function process_formdata($form_data, $key='')
    {
        if($key!="") /* if key is given then take its data */
        {
            if(isset($form_data[$key]))
            {
                if(is_array($form_data[$key]))
                {
                    $form_data_vl=$form_data[$key];
                }else
                {
                    $form_data_vl=json_decode(stripslashes($form_data[$key]),true);
                } 
            }else
            {
                $form_data_vl=array();
            }
        }else
        {
            $form_data_vl=array();
            foreach($form_data as $form_datak=>$form_datav)
            {
                $form_data_vl[$form_datak]=self::process_formdata($form_data, $form_datak);
            }
        }
        return (is_array($form_data_vl) ? $form_data_vl : array());
    }

    /**
    *   Form field generator
    */
    public static function field_generator($form_fields, $form_data)
    {
        include plugin_dir_path( dirname( __FILE__ ) ).'admin/partials/_form_field_generator.php';
    }


    /**
    *   Save advanced settings
    *   @param  array   $settings   array of setting values
    */
    public static function set_advanced_settings($settings)
    {
        update_option('wt_pf_advanced_settings', $settings);
    }

    /**
    *
    *   Extract validation rule from form field array
    *   @param  array   $fields   form field array
    */
    public static function extract_validation_rules($fields)
    {
        $out=array_map(function ($r) { return (isset($r['validation_rule']) ? $r['validation_rule'] : ''); }, $fields);
        return array_filter($out);
    }

    /**
    *   Get advanced settings.
    *   @param      string  $key    key for specific setting (optional)
    *   @return     mixed   if key provided then the value of key otherwise array of values
    */
    public static function get_advanced_settings($key="")
    {
        $advanced_settings=get_option('wt_pf_advanced_settings');       
        $advanced_settings=($advanced_settings ? $advanced_settings : array());
        if($key!="")
        {
            $key=(substr($key,0,8)!=='wt_pf_' ? 'wt_pf_' : '').$key;
            if(isset($advanced_settings[$key]))
            {
                return $advanced_settings[$key];
            }else
            {
                $default_settings=self::get_advanced_settings_default();
                return (isset($default_settings[$key]) ? $default_settings[$key] : '');
            }
        }else
        {
            $default_settings=self::get_advanced_settings_default();            
            $advanced_settings=wp_parse_args($advanced_settings, $default_settings);
            return $advanced_settings; 
        }
    }

    /**
    *   Get default value of advanced settings
    *   @return     array   array of default values
    *
    */
    public static function get_advanced_settings_default()
    {
        $fields=self::get_advanced_settings_fields();      
        foreach ($fields as $key => $value)
        {
            if(isset($value['value']))
            {
                $key=(substr($key,0,8)!=='wt_pf_' ? 'wt_pf_' : '').$key;
                $out[$key]=$value['value'];
            }
        }
        return $out;
    }

    /**
    *   Get advanced fields
    *   @return     array   array of fields
    *
    */
    public static function get_advanced_settings_fields()
    {
        $fields=array();
        return apply_filters('wt_pf_advanced_setting_fields_basic', $fields);
    }
    
    public static function wt_allowed_screens(){
        $screens=array('webtoffee_product_feed_main_export', 'webtoffee_product_feed_main_history', 'webtoffee_product_feed', 'webtoffee-product-feed', 'wt_import_export_for_woo_basic','wt_import_export_for_woo_basic_export','wt_import_export_for_woo_basic_import','wt_import_export_for_woo_basic_history','wt_import_export_for_woo_basic_history_log');
        return apply_filters('wt_pf_allowed_screens_basic', $screens);

    }
    public static function wt_get_current_page(){        
        return (isset($_GET['page'])) ? $_GET['page'] : '';
    }
    
    public static function wt_is_screen_allowed(){
        return in_array(self::wt_get_current_page(), self::wt_allowed_screens());
    }
	
	/**
 * Returns the timestamp of the provided time string using a specific timezone as the reference
 * 
 * @param string $str
 * @param string $timezone
 * @return int number of the seconds
 */
public static function wt_strtotimetz($str)
{

	$wt_default_time_zone = self::get_advanced_settings('default_time_zone');
	if($wt_default_time_zone){
	$timezone = wp_timezone_string();
    $strtotime =  strtotime(
        $str, strtotime(
            // convert timezone to offset seconds
            (new \DateTimeZone($timezone))->getOffset(new \DateTime) - (new \DateTimeZone(date_default_timezone_get()))->getOffset(new \DateTime) . ' seconds'
        )
    );
	} else {
		$strtotime =  strtotime($str);
	}
	return $strtotime;
}
	
}
}



if(!function_exists('wt_removeBomUtf8_basic')){
function wt_removeBomUtf8_basic($s) {
    if (substr($s, 0, 3) == chr(hexdec('EF')) . chr(hexdec('BB')) . chr(hexdec('BF'))) {
        return substr($s, 3);
    } else {
        return $s;
    }
}
}
