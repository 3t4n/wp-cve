<?php
/**
 * Product Data - Facebook
 *
 */
defined('ABSPATH') || exit;
?>
<div id="wt_feed_data" class="panel woocommerce_options_panel">

	<div class="options_group">
		<?php
                
                global $post;
                
                $brand_val = get_post_meta($post->ID, '_wt_feed_brand', true);
                if(!$brand_val){
                    $brand_val = get_post_meta($post->ID, '_wt_facebook_brand', true);
                }
                if(!$brand_val){
                    $brand_val = get_post_meta($post->ID, '_wt_google_brand', true);
                }                  
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_brand',
					'label' => _x('Brand', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('The brand of the product.', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
                                        'value' => $brand_val,
				)
		);

                $gtin_val = get_post_meta($post->ID, '_wt_feed_gtin', true);
                if(!$gtin_val){
                    $gtin_val = get_post_meta($post->ID, '_wt_google_gtin', true);
                }                
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_gtin',
					'label' => __('GTIN', 'webtoffee-product-feed'),
					'desc_tip' => true,
					'description' => _x('The Global Trade Item Number (GTIN) is an identifier for trade items.', 'product data setting desc', 'webtoffee-product-feed'),
                                        'value' => $gtin_val,
				)
		);
                
                $mpn_val = get_post_meta($post->ID, '_wt_feed_mpn', true);
                if(!$mpn_val){
                    $mpn_val = get_post_meta($post->ID, '_wt_google_mpn', true);
                }                  
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_mpn',
					'label' => __('MPN', 'webtoffee-product-feed'),
					'desc_tip' => true,
					'description' => _x('A manufacturer part number (MPN) is a series of numbers and/or letters given to a part by its manufacturer.', 'product data setting desc', 'webtoffee-product-feed'),
                                        'value' => $mpn_val,
				)
                );
                
                $han_val = get_post_meta($post->ID, '_wt_feed_han', true);                 
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_han',
					'label' => __('HAN', 'webtoffee-product-feed'),
					'desc_tip' => true,
					'description' => _x('A Manufacturer Article Number (HAN) is a unique identification number assigned by manufacturers to identify their own products.', 'product data setting desc', 'webtoffee-product-feed'),
                                        'value' => $han_val,
				)
                );

                $ean_val = get_post_meta($post->ID, '_wt_feed_ean', true);                 
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_ean',
					'label' => __('EAN', 'webtoffee-product-feed'),
					'desc_tip' => true,
					'description' => _x('A European Article Number (EAN) is a unique identification number assigned by manufacturers to identify their own products.', 'product data setting desc', 'webtoffee-product-feed'),
                                        'value' => $ean_val,
				)
                );                 
                
		$product_conditions = Webtoffee_Product_Feed_Sync_Common_Helper::wt_feed_get_product_conditions();
                
                $condition_val = get_post_meta($post->ID, '_wt_feed_condition', true);
                if(!$condition_val){
                    $condition_val = get_post_meta($post->ID, '_wt_facebook_condition', true);
                }
                if(!$condition_val){
                    $condition_val = get_post_meta($post->ID, '_wt_google_condition', true);
                }                
                
		woocommerce_wp_select(
			array(
				'id'          => '_wt_feed_condition',
				'label'       => _x( 'Condition', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The product condition.', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
				'options'     => array( '' => _x( 'Default', 'setting option', 'webtoffee-product-feed' ) ) + $product_conditions,
                                'value'       => $condition_val,
			)
		);                
                
		$age_group = Webtoffee_Product_Feed_Sync_Common_Helper::get_age_group();
                
                $agegroup_val = get_post_meta($post->ID, '_wt_feed_agegroup', true);
                if(!$agegroup_val){
                    $agegroup_val = get_post_meta($post->ID, '_wt_facebook_agegroup', true);
                }
                if(!$agegroup_val){
                    $agegroup_val = get_post_meta($post->ID, '_wt_google_agegroup', true);
                }                  
                
		woocommerce_wp_select(
				array(
					'id' => '_wt_feed_agegroup',
					'label' => _x('Age group', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('The product age group.', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
					'options' => array('' => _x('Default', 'setting option', 'webtoffee-product-feed')) + $age_group,
                                        'value' => $agegroup_val,
				)
		);

		$product_gender = Webtoffee_Product_Feed_Sync_Common_Helper::get_geneder_list();
                
                $gender_val = get_post_meta($post->ID, '_wt_feed_gender', true);
                if(!$gender_val){
                    $gender_val = get_post_meta($post->ID, '_wt_facebook_gender', true);
                }
                if(!$gender_val){
                    $gender_val = get_post_meta($post->ID, '_wt_google_gender', true);
                }                   
                
		woocommerce_wp_select(
				array(
					'id' => '_wt_feed_gender',
					'label' => _x('Gender', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('The product gender.', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
					'options' => array('' => _x('Default', 'setting option', 'webtoffee-product-feed')) + $product_gender,
                                        'value' => $gender_val,
				)
		);
                
                $size_val = get_post_meta($post->ID, '_wt_feed_size', true);
                if(!$size_val){
                    $size_val = get_post_meta($post->ID, '_wt_facebook_size', true);
                }
                if(!$size_val){
                    $size_val = get_post_meta($post->ID, '_wt_google_size', true);
                }                  
                
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_size',
					'label' => _x('Size', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('The size of the item. Enter the size as a word, abbreviation or number, such as "small", "XL", "12" or "one size". Character limit: 200. eg:- Medium', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
                                        'value' => $size_val,
				)
		);
                
                $color_val = get_post_meta($post->ID, '_wt_feed_color', true);
                if(!$color_val){
                    $color_val = get_post_meta($post->ID, '_wt_facebook_color', true);
                }
                if(!$color_val){
                    $color_val = get_post_meta($post->ID, '_wt_google_color', true);
                }                
                
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_color',
					'label' => _x('Color', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('The main colour of the item. Describe the colour in words, not a hex code. Character limit: 200. eg:- Royal blue', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
                                        'value' => $color_val,
				)
		);
                
                
                $material_val = get_post_meta($post->ID, '_wt_feed_material', true);
                if(!$material_val){
                    $material_val = get_post_meta($post->ID, '_wt_facebook_material', true);
                }
                if(!$material_val){
                    $material_val = get_post_meta($post->ID, '_wt_google_material', true);
                }     
                
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_material',
				'label'       => _x( 'Material', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The material the item is made from, such as cotton, polyester, denim or leather. Character limit: 200. eg:- leather', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $material_val,
			)
		);	
                
                $pattern_val = get_post_meta($post->ID, '_wt_feed_pattern', true);
                if(!$pattern_val){
                    $pattern_val = get_post_meta($post->ID, '_wt_facebook_pattern', true);
                }
                if(!$pattern_val){
                    $pattern_val = get_post_meta($post->ID, '_wt_google_pattern', true);
                }                
                
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_pattern',
				'label'       => _x( 'Pattern', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The pattern or graphic print on the item. Character limit: 100. eg:- striped', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $pattern_val,
			)
		);                
                                
                
                
                $unit_pricing_measure_val = get_post_meta($post->ID, '_wt_feed_unit_pricing_measure', true);
                if(!$unit_pricing_measure_val){
                    $unit_pricing_measure_val = get_post_meta($post->ID, '_wt_google_unit_pricing_measure', true);
                }                    
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_unit_pricing_measure',
					'label' => _x('Unit pricing measure', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('Use the unit pricing measure [unit_pricing_measure] attribute to define the measure and dimension of your product. This value allows users to understand the exact cost per unit for your product.', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
                                        'value' => $unit_pricing_measure_val,
				)
		);
                
                $unit_pricing_base_measure_val = get_post_meta($post->ID, '_wt_feed_unit_pricing_base_measure', true);
                if(!$unit_pricing_base_measure_val){
                    $unit_pricing_base_measure_val = get_post_meta($post->ID, '_wt_google_unit_pricing_base_measure', true);
                }                    
		woocommerce_wp_text_input(
				array(
					'id' => '_wt_feed_unit_pricing_base_measure',
					'label' => _x('Unit pricing base measure', 'product data setting title', 'webtoffee-product-feed'),
					'description' => _x('The unit pricing base measure [unit_pricing_base_measure] attribute lets you include the denominator for your unit price. For example, you might be selling "150ml" of perfume, but customers are interested in seeing the price per "100ml".', 'product data setting desc', 'webtoffee-product-feed'),
					'desc_tip' => true,
                                        'value' => $unit_pricing_base_measure_val,
				)
		);    
                
                
                $energy_efficiency_class_val = get_post_meta($post->ID, '_wt_feed_energy_efficiency_class', true);
                if(!$energy_efficiency_class_val){
                    $energy_efficiency_class_val = get_post_meta($post->ID, '_wt_google_energy_efficiency_class', true);
                }                
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_energy_efficiency_class',
				'label'       => _x( 'Energy efficiency class', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The [energy_efficiency_class] attributes to tell customers the energy label of your product.', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $energy_efficiency_class_val,
			)
		);
                
                $min_energy_efficiency_class = get_post_meta($post->ID, '_wt_feed_min_energy_efficiency_class', true);
                if(!$min_energy_efficiency_class){
                    $min_energy_efficiency_class = get_post_meta($post->ID, '_wt_google_min_energy_efficiency_class', true);
                }                  
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_min_energy_efficiency_class',
				'label'       => _x( 'Minimum Energy efficiency class', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The [min_energy_efficiency_class] attributes to tell customers the energy label of your product.', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $min_energy_efficiency_class,
			)
		);
                
                $max_energy_efficiency_class = get_post_meta($post->ID, '_wt_feed_max_energy_efficiency_class', true);
                if(!$max_energy_efficiency_class){
                    $max_energy_efficiency_class = get_post_meta($post->ID, '_wt_google_max_energy_efficiency_class', true);
                }                  
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_max_energy_efficiency_class',
				'label'       => _x( 'Maximum Energy efficiency class', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The [max_energy_efficiency_class] attributes to tell customers the energy label of your product.', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $max_energy_efficiency_class,
			)
		);
                

                
                $glpi_pickup_methods = array(
			'buy' => __( 'Buy', 'webtoffee-product-feed' ),
			'reserve' => __( 'Reserve', 'webtoffee-product-feed' ),
			'ship to store' => __( 'Ship to store', 'webtoffee-product-feed' ),
			'not supported' => __( 'Not supported', 'webtoffee-product-feed' ),
		);

                $glpi_pickup_method_val = get_post_meta($post->ID, '_wt_feed_glpi_pickup_method', true);
                if(!$glpi_pickup_method_val){
                    $glpi_pickup_method_val = get_post_meta($post->ID, '_wt_google_glpi_pickup_method', true);
                }                
		woocommerce_wp_select(
			array(
				'id'          => '_wt_feed_glpi_pickup_method',
				'label'       => _x( 'Pickup method', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The product Pickup method, used in google local product inventory.', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
				'options'     => array( '' => _x( 'Default', 'setting option', 'webtoffee-product-feed' ) ) + $glpi_pickup_methods,
                                'value' => $glpi_pickup_method_val,
			)
		);
		
		$glpi_pickup_sla = array(
			'same day' => __( 'Same day', 'webtoffee-product-feed' ),
			'next day' => __( 'Next day', 'webtoffee-product-feed' ),
			'2-day' => __( '2 Day', 'webtoffee-product-feed' ),
			'3-day' => __( '3 Day', 'webtoffee-product-feed' ),
			'4-day' => __( '4 Day', 'webtoffee-product-feed' ),
			'5-day' => __( '5 Day', 'webtoffee-product-feed' ),
			'6-day' => __( '6 Day', 'webtoffee-product-feed' ),
			'multi-week' => __( 'Multi week', 'webtoffee-product-feed' ),
		);
                $glpi_pickup_sla_val = get_post_meta($post->ID, '_wt_feed_glpi_pickup_sla', true);
                if(!$glpi_pickup_sla_val){
                    $glpi_pickup_sla_val = get_post_meta($post->ID, '_wt_google_glpi_pickup_sla', true);
                }                     
		woocommerce_wp_select(
			array(
				'id'          => '_wt_feed_glpi_pickup_sla',
				'label'       => _x( 'Pickup SLA', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'The product Pickup SLA, used in google local product inventorr.', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
				'options'     => array( '' => _x( 'Default', 'setting option', 'webtoffee-product-feed' ) ) + $glpi_pickup_sla,
                                'value' => $glpi_pickup_sla_val,
			)
		);    
                
                $custom_label_0 = get_post_meta($post->ID, '_wt_feed_custom_label_0', true);
                if(!$custom_label_0){
                    $custom_label_0 = get_post_meta($post->ID, '_wt_facebook_custom_label_0', true);
                }
                if(!$custom_label_0){
                    $custom_label_0 = get_post_meta($post->ID, '_wt_google_custom_label_0', true);
                }                 
                woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_custom_label_0',
				'label'       => _x( 'Custom label 0', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'Additional custom label for the item. Character limit: 100. eg:- Summer Sale', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $custom_label_0,
			)
		);
                $custom_label_1 = get_post_meta($post->ID, '_wt_feed_custom_label_1', true);
                if(!$custom_label_1){
                    $custom_label_1 = get_post_meta($post->ID, '_wt_facebook_custom_label_1', true);
                }
                if(!$custom_label_1){
                    $custom_label_1 = get_post_meta($post->ID, '_wt_google_custom_label_1', true);
                }                 
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_custom_label_1',
				'label'       => _x( 'Custom label 1', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'Additional custom label for the item. Character limit: 100. eg:- Summer Sale', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $custom_label_1,
			)
		);
                $custom_label_2 = get_post_meta($post->ID, '_wt_feed_custom_label_2', true);
                if(!$custom_label_2){
                    $custom_label_2 = get_post_meta($post->ID, '_wt_facebook_custom_label_2', true);
                }
                if(!$custom_label_2){
                    $custom_label_2 = get_post_meta($post->ID, '_wt_google_custom_label_2', true);
                }               
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_custom_label_2',
				'label'       => _x( 'Custom label 2', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'Additional custom label for the item. Character limit: 100. eg:- Summer Sale', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $custom_label_2,
			)
		);
                $custom_label_3 = get_post_meta($post->ID, '_wt_feed_custom_label_3', true);
                if(!$custom_label_3){
                    $custom_label_3 = get_post_meta($post->ID, '_wt_facebook_custom_label_3', true);
                }
                if(!$custom_label_3){
                    $custom_label_3 = get_post_meta($post->ID, '_wt_google_custom_label_3', true);
                }                  
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_custom_label_3',
				'label'       => _x( 'Custom label 3', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'Additional custom label for the item. Character limit: 100. eg:- Summer Sale', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $custom_label_3,
			)
		);
                $custom_label_4 = get_post_meta($post->ID, '_wt_feed_custom_label_4', true);
                if(!$custom_label_4){
                    $custom_label_4 = get_post_meta($post->ID, '_wt_facebook_custom_label_4', true);
                }
                if(!$custom_label_4){
                    $custom_label_4 = get_post_meta($post->ID, '_wt_google_custom_label_4', true);
                }                
		woocommerce_wp_text_input(
			array(
				'id'          => '_wt_feed_custom_label_4',
				'label'       => _x( 'Custom label 4', 'product data setting title', 'webtoffee-product-feed' ),
				'description' => _x( 'Additional custom label for the item. Character limit: 100. eg:- Summer Sale', 'product data setting desc', 'webtoffee-product-feed' ),
				'desc_tip'    => true,
                                'value' => $custom_label_4,
			)
		);
                
            if(class_exists('Webtoffee_Product_Feed_Sync_Google')){
                    $google_categories = Webtoffee_Product_Feed_Sync_Google::get_category_array();
                    woocommerce_wp_select(
                            array(
                                    'id'          => '_wt_google_google_product_category',
                                    'label'       => _x( 'Google Product category', 'product data setting title', 'webtoffee-product-feed' ),
                                    'description' => sprintf( _x('A product category value provided by %1$s Google %2$s feed.', 'product data setting desc', 'webtoffee-product-feed'), '<a style="color:#93BBF9;" href="https://www.google.com/basepages/producttype/taxonomy.en-US.txt" target="_blank">', '</a>' ),
                                    'desc_tip'    => true,
                                    'options'     => array( '' => _x( 'Default', 'setting option', 'webtoffee-product-feed' ) ) + $google_categories,
                                    'class'       => 'wt-feed-google-product-cat wc-enhanced-select',
                            )
                    );   
                }
                
                if(class_exists('Webtoffee_Product_Feed_Sync_Facebook')){		
                    $fb_categories = Webtoffee_Product_Feed_Sync_Facebook::get_category_array();
                    woocommerce_wp_select(
                            array(
                                    'id'          => '_wt_facebook_fb_product_category',
                                    'label'       => _x( 'Facebook Product category', 'product data setting title', 'webtoffee-product-feed' ),
                                    'description' => sprintf( _x('A product category value provided by %1$s Facebook %2$s feed.', 'product data setting desc', 'webtoffee-product-feed'), '<a style="color:#93BBF9;" href="https://www.facebook.com/products/categories/en_US.txt" target="_blank">', '</a>' ),
                                    'desc_tip'    => true,
                                    'options'     => array( '' => _x( 'Default', 'setting option', 'webtoffee-product-feed' ) ) + $fb_categories,
                                    'class'       => 'wt-feed-facebook-product-cat wc-enhanced-select',
                            )
                    );
                }                
                
		?>
	</div>
</div>