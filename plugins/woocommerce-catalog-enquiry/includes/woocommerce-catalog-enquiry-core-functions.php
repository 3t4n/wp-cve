<?php

if (!function_exists('woocommerce_catalog_enquiry_alert_notice')) {
	 function woocommerce_catalog_enquiry_alert_notice() {
		?>
		<div id="message" class="error">
			<p><?php printf( __( '%sWoocommerce Catalog Enquiry is inactive.%s The %sWooCommerce plugin%s must be active for the Woocommerce Catalog Enquiry to work. Please %sinstall & activate WooCommerce%s', WOOCOMMERCE_CATALOG_ENQUIRY_TEXT_DOMAIN ), '<strong>', '</strong>', '<a target="_blank" href="http://wordpress.org/extend/plugins/woocommerce/">', '</a>', '<a href="' . admin_url( 'plugins.php' ) . '">', '&nbsp;&raquo;</a>' ); ?></p>
		</div>
		<?php
	}
}


if (!function_exists('woocommerce_catalog_enquiry_validate_color_hex_code')) {
	function woocommerce_catalog_enquiry_validate_color_hex_code($code) {
			$color = str_replace( '#', '', $code );
			return '#'.$color;
	}
}

if (!function_exists('mvx_catalog_get_settings_value')) {
    function mvx_catalog_get_settings_value($key = array(), $default = '') {
        if ($default == 'select' && is_array($key) && isset($key['value'])) {
            return $key['value'];
        }
        if ($default == 'checkbox' && is_array($key) && !empty($key)) {
            return 'Enable';
        }
        if ($default == 'multiselect' && is_array($key)) {
            return wp_list_pluck($key, 'value');
        }
        return $default;
    }
}

if (!function_exists('migration_from_previous')) {
	function migration_from_previous() {
		if (!get_option('_is_dismiss_mvxcatalog40_notice', false)) {
			$email_tpl = is_array(get_option('woocommerce_catalog_enquiry_from_settings')) ? get_option('woocommerce_catalog_enquiry_from_settings') : [];
			$general = is_array(get_option('woocommerce_catalog_enquiry_general_settings')) ? get_option('woocommerce_catalog_enquiry_general_settings') : [];
			$exclusion = is_array(get_option('woocommerce_catalog_enquiry_exclusion_settings')) ? get_option('woocommerce_catalog_enquiry_exclusion_settings') : [];
			$button = is_array(get_option('woocommerce_catalog_enquiry_button_appearence_settings')) ? get_option('woocommerce_catalog_enquiry_button_appearence_settings') : [];
			$database_value = array_merge($general, $exclusion, $button, $email_tpl);
			$current_catalog_settings = mvx_catalog_admin_tabs();

			if (!empty($current_catalog_settings['catalog-settings'])) {
	            foreach ($current_catalog_settings['catalog-settings'] as $settings_key => $settings_value) {
	            	if (isset($settings_value['modulename']) && !empty($settings_value['modulename'])) {
		                foreach ($settings_value['modulename'] as $inter_key => $inter_value) {
		                    $change_settings_key    =   str_replace("-", "_", $settings_key);
		                    $option_name = 'mvx_catalog_'.$change_settings_key.'_tab_settings';
		                    if (!empty($database_value)) {
		                        if (isset($inter_value['key']) && array_key_exists($inter_value['key'], $database_value)) {
		                            if (empty($inter_value['database_value'])) {
		                            	if ($database_value[$inter_value['key']] && $database_value[$inter_value['key']] == 'Enable') {
		                            		$optionname_value = get_option($option_name) ? get_option($option_name) : [];
		                            		$optionname_value[$inter_value['key']] = array($inter_value['key']);
	                						update_option($option_name, $optionname_value);
		                            	}
		                            	$custom_text_datas = array('text', 'textarea');
	                            		if ($database_value[$inter_value['key']] && in_array($inter_value['type'], $custom_text_datas) ) {
		                            		$optionname_value = get_option($option_name) ? get_option($option_name) : [];
		                            		$optionname_value[$inter_value['key']] = $database_value[$inter_value['key']];
		                            		if ($optionname_value) {
		                            			update_option($option_name, $optionname_value);
		                            		}
	                            		}

			                            if ($database_value[$inter_value['key']] && in_array($inter_value['type'], array('select', 'multi-select')) ) {

							                $pages_array = $all_users = $all_products = $all_product_cat = [];

											$args_cat = array( 'orderby' => 'name', 'order' => 'ASC' );
											$terms = get_terms( 'product_cat', $args_cat );
											if ($terms && empty($terms->errors)) {
												foreach ( $terms as $term) {
													if ($term) {
														if (is_array($database_value['woocommerce_category_list']) && in_array($term->term_id, $database_value['woocommerce_category_list'])) {
															$all_product_cat[] = array(
																'value'=> $term->term_id,
																'label'=> $term->name,
																'key'=> $term->term_id,
															); 
														}
													}
												}
											}

											$args = apply_filters('woocommerce_catalog_limit_backend_product', array( 'posts_per_page' => -1, 'post_type' => 'product', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' ));
											$woocommerce_product = get_posts( $args );
											foreach ( $woocommerce_product as $post => $value ) {
												if (is_array($database_value['woocommerce_product_list']) && in_array($value->ID, $database_value['woocommerce_product_list'])) {
													$all_products[] = array(
														'value'=> $value->ID,
														'label'=> $value->post_title,
														'key'=> $value->ID,
													);
												} 
											}

											$users = get_users();
											foreach($users as $user) {
												if (is_array($database_value['woocommerce_user_list']) && in_array($user->data->ID, $database_value['woocommerce_user_list'])) {
													$all_users[] = array(
														'value'=> $user->data->ID,
														'label'=> $user->data->display_name,
														'key'=> $user->data->ID,
													);
												}
											}
											
							                $pages_array['woocommerce_user_list'] = $all_users;
							                $pages_array['woocommerce_product_list'] = $all_products;
							                $pages_array['woocommerce_category_list'] = $all_product_cat;
							                update_option('mvx_catalog_exclusion_tab_settings', $pages_array);
							            }
		                            }
		                        }
		                    }
		                }
		            }
	            }
	        }
	    }
	    update_option('_is_dismiss_mvxcatalog40_notice', true);
	}
}

if (!function_exists('mvx_catalog_admin_tabs')) {
	function mvx_catalog_admin_tabs() {
		$pages_array = $role_array = $all_users = $all_products = $all_product_cat = [];
		$pages = get_pages();
		if($pages){
			foreach ($pages as $page) {
				$pages_array[] = array(
					'value'=> $page->ID,
					'label'=> $page->post_title,
					'key'=> $page->ID,
				);
			}
		}

		if (wp_roles()->roles) {
			foreach (wp_roles()->roles as $key => $element) {
				$role_array[] = array(
					'value'=> $key,
					'label'=> $element['name'],
					'key'=> $key,
				);
			}
		}

		$users = get_users();
		foreach($users as $user) {
			$all_users[] = array(
				'value'=> $user->data->ID,
				'label'=> $user->data->display_name,
				'key'=> $user->data->ID,
			);
		}

		$args = apply_filters('woocommerce_catalog_limit_backend_product', array( 'posts_per_page' => -1, 'post_type' => 'product', 'post_status' => 'publish', 'orderby' => 'title', 'order' => 'ASC' ));
		$woocommerce_product = get_posts( $args );
		foreach ( $woocommerce_product as $post => $value ) {
			$all_products[] = array(
				'value'=> $value->ID,
				'label'=> $value->post_title,
				'key'=> $value->ID,
			);   
		}

		$args_cat = array( 'orderby' => 'name', 'order' => 'ASC' );
		$terms = get_terms( 'product_cat', $args_cat );
		if ($terms && empty($terms->errors)) {
			foreach ( $terms as $term) {
				if ($term) {
					$all_product_cat[] = array(
						'value'=> $term->term_id,
						'label'=> $term->name,
						'key'=> $term->term_id,
					); 
				}
			}
		}

		$catalog_settings_page_endpoint = apply_filters('mvx_catalog_endpoint_fields_before_value', array(
			'general' => array(
				'tablabel'        =>  __('General', 'woocommerce-catalog-enquiry'),
				'apiurl'          =>  'save_enquiry',
				'description'     =>  __('Configure basic catalog settings to operate your catalog marketplace. ', 'woocommerce-catalog-enquiry'),
				'icon'            =>  'icon-general-tab',
				'submenu'         =>  'settings',
				'modulename'      =>  [
					[
	                    'key'       =>  'woocommerce_catalog_enquiry_general_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Common Settings", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
					[
						'key'    => 'is_enable',
						'label'   => __( "Catalog Mode", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_enable",
										'label'=> apply_filters( 'woocommerce_catalog_enquiry_enable_catalog_text', __('Enable this to activate catalog mode sitewide. This will remove your Add to Cart button. To keep Add to Cart button in your site, upgrade to  <a href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/" target="_blank">WooCommerce Catalog Enquiry Pro</a>.', 'woocommerce-catalog-enquiry', 'woocommerce-catalog-enquiry') ),
										'value'=> "is_enable"
								),
						),
						'database_value' => array(),
					],
					[
						'key'    => 'is_enable_enquiry',
						'label'   => __( "Product Enquiry Button", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_enable_enquiry",
										'label'=> __("Enable this to add the Enquiry button for all products. Use Exclusion settings to exclude specific product or category from enquiry.", 'woocommerce-catalog-enquiry'),
										'value'=> "is_enable_enquiry"
								),
						),
						'database_value' => array(),
					],
					[
						'key'    => 'is_enable_out_of_stock',
						'label'   => __( "Product Enquiry Button When Product is Out Of Stock", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_enable_out_of_stock",
										'label'=> __("Enable this to add the Enquiry button for the products which is out of stock. Use Exclusion settings to exclude specific product or category from enquiry.", 'woocommerce-catalog-enquiry'),
										'value'=> "is_enable_out_of_stock"
								),
						),
						'database_value' => array(),
					],
					[
						'key'       => 'for_user_type',
						'type'      => 'select',
						'label'     => __( 'Catalog Mode Applicable For', 'woocommerce-catalog-enquiry' ),
						'desc'      => __( 'Select the type users where this catalog is applicable', 'woocommerce-catalog-enquiry' ),
						'options' => array(
								array(
										'key' => "1",
										'label'=> __('Only Logged out Users', 'woocommerce-catalog-enquiry'),
										'value'=> "1",
								),
								array(
										'key'=> "2",
										'label'=> __('Only Logged in Users', 'woocommerce-catalog-enquiry'),
										'value'=> "2",
								),
								array(
										'key'=> "3",
										'label'=> __('All Users', 'woocommerce-catalog-enquiry'),
										'value'=> '3',
								)
						),
						'database_value' => '',
					],
					[
						'key'    => 'is_hide_cart_checkout',
						'label'   => __( "Disable Cart and Checkout Page?", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
							array(
								'key'=> "is_hide_cart_checkout",
								'label'=> apply_filters( 'woocommerce_catalog_enquiry_hide_cart', __('Enable this to redirect user to home page, if they click on the cart or checkout page. To set the redirection to another page kindly upgrade to <a href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/" target="_blank">WooCommerce Catalog Enquiry Pro</a>.', 'woocommerce-catalog-enquiry') ),
								'value'=> "is_hide_cart_checkout"
							),
						),
						'database_value' => array(),
					],
					[
						'key'       => 'disable_cart_page_link',
						'depend_checkbox'	=>	'is_hide_cart_checkout',
						'disable'	=> apply_filters('mvx_catalog_free_only_active', true),
						'type'      => 'select',
						'label'     => __( 'Set Redirect Page', 'woocommerce-catalog-enquiry' ),
						'desc'      => apply_filters('woocommerce_catalog_redirect_disabled_cart_page', __( 'Select page where user will be redirected for disable cart page. To use this feature kindly upgrade to <a href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/" target="_blank">WooCommerce Catalog Enquiry Pro</a>.', 'woocommerce-catalog-enquiry' )),
						'options' => $pages_array,
						'database_value' => '',
					],
					[
						'key'    => 'is_page_redirect',
						'label'   => __( "Redirect after Enquiry form Submission", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
							array(
								'key'=> "is_page_redirect",
								'label'=> __("Enable this to redirect user to another page after successful enquiry submission.", 'woocommerce-catalog-enquiry'),
								'value'=> "is_page_redirect"
							),
						),
						'database_value' => array(),
					],
					[
						'key'       => 'redirect_page_id',
						'depend_checkbox'	=>	'is_page_redirect',
						'type'      => 'select',
						'label'     => __( 'Set Redirect Page', 'woocommerce-catalog-enquiry' ),
						'desc'      => __( 'Select page where user will be redirected after successful enquiry.', 'woocommerce-catalog-enquiry' ),
						'options' => $pages_array,
						'database_value' => '',
					],

					[
	                    'key'       => 'separator1_content',
	                    'type'      => 'section',
	                    'label'     => "",
                	],
                	[
	                    'key'       =>  'woocommerce_catalog_enquiry_display_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Display Options", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
					[
						'key'    => 'is_remove_price_free',
						'label'   => __( "Remove Product Price?", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_remove_price_free",
										'label'=> __("Enable this option to remove the product price display from site.", 'woocommerce-catalog-enquiry'),
										'value'=> "is_remove_price_free"
								),
						),
						'database_value' => array(),
					],
					[
						'key'    => 'is_disable_popup',
						'label'   => __( "Disable Enquiry form via popup?", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_disable_popup",
										'label'=> __("By default the form will be displayed via popup. Enable this, if you want to display the form below the product description.", 'woocommerce-catalog-enquiry'),
										'value'=> "is_disable_popup"
								),
						),
						'database_value' => array(),
					],


					[
	                    'key'       => 'separator2_content',
	                    'type'      => 'section',
	                    'label'     => "",
	                ],
	                [
	                    'key'       =>  'woocommerce_catalog_enquiry_email_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Enquiry Email Receivers Settings", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
					[
		                'key'       => 'other_emails',
		                'type'      => 'text',
		                'label'     => __( 'Additional Recivers Emails', 'woocommerce-catalog-enquiry' ),
		                'desc'      => __('Enter email address if you want to receive enquiry mail along with admin mail. You can add multiple commma seperated emails. Default: Admin emails.','woocommerce-catalog-enquiry'),
		                'database_value' => '',
		            ],
					[
						'key'    => 'is_other_admin_mail',
						'label'   => __( "Remove admin email", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_other_admin_mail",
										'label'=> __("Enable this if you want remove admin email from reciever list.", 'woocommerce-catalog-enquiry'),
										'value'=> "is_other_admin_mail"
								),
						),
						'database_value' => array(),
					],
					
				]
			),
			'button-appearance'   => array(
				'tablabel'      =>  __('Button Appearance', 'woocommerce-catalog-enquiry'),
				'apiurl'        =>  'save_enquiry',
				'description'   =>  __("Change the appearance of the catalog button to match the theme of your marketplace.", 'woocommerce-catalog-enquiry'),
				'icon'          =>  'icon-button-appearance-tab',
				'submenu'       =>  'settings',
				'modulename'    =>  [
					[
	                    'key'       =>  'woocommerce_catalog_enquiry_button_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Button Customizer", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
					[
		                'key'       => 'enquiry_button_text',
		                'type'      => 'text',
		                'label'     => __( 'Button Text', 'woocommerce-catalog-enquiry' ),
		                'desc'      => __('Enter the text for your Enquery Button.','woocommerce-catalog-enquiry'),
		                'database_value' => '',
		            ],
      				[
						'key'       => 'button_type',
						'type'      => 'select',
						'label'     => __( 'Button Type', 'woocommerce-catalog-enquiry' ),
						'options' => array(
							array(
									'key' => "1",
									'label'=> __('Read More', 'woocommerce-catalog-enquiry'),
									'value'=> "1",
							),
							array(
									'key'=> "2",
									'label'=> __('Custom Link For All Products', 'woocommerce-catalog-enquiry'),
									'value'=> "2",
							),
							array(
									'key'=> "3",
									'label'=> __('Individual link in all products', 'woocommerce-catalog-enquiry'),
									'value'=> '3',
							),
							array(
									'key'=> "4",
									'label'=> __('No Link Just #', 'woocommerce-catalog-enquiry'),
									'value'=> '4',
							)
						),
						'desc'	=>	__('Default: Read More.', 'woocommerce-catalog-enquiry'),
						'database_value' => '',
					],
					[
						'key'    => 'is_button',
						'label'   => __( "Your own button style", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
							array(
								'key'=> "is_button",
								'label'=> __("Enable the custom design for enquiry button.", 'woocommerce-catalog-enquiry'),
								'value'=> "is_button"
							),
						),
						'database_value' => array(),
					],
		            // [
		            //     'key'       => 'custom_own_button_style',
		            //     'depend_checkbox'    => 'is_button',
		            //     'type'      => 'own_button',
		            //     'class'     =>  'mvx-setting-own-class',
		            //     'desc'      => __('', 'woocommerce-catalog-enquiry'),
		            //     'label'     => __( 'Make your own Button Style', 'woocommerce-catalog-enquiry' ),
		            //     'database_value' => '',
		            // ],
					[
						'key'       =>  'woocommerce_catalog_enquiry_button_settings',
						'type'      =>  'blocktext',
						'depend_checkbox'    => 'is_button',
						'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
						'blocktext'      =>  __( "Custom Button Customizer", 'woocommerce-catalog-enquiry' ),
						'database_value' => '',
					],

					[
						'key'       => 'custom_example_button',
						'depend_checkbox'    => 'is_button',
						'type'      => 'example_button',
						'class'     =>  'mvx-setting-own-class',
						'desc'      => __('', 'woocommerce-catalog-enquiry'),
						'label'     => __( '', 'woocommerce-catalog-enquiry' )
					],
					[
						'key'       => 'custom_button_size',
						'depend_checkbox'    => 'is_button',
						'type'      => 'slider',
						'class'     =>  'mvx-setting-slider-class',
						'desc'      => __('', 'woocommerce-catalog-enquiry'),
						'label'     => __( 'Button Size', 'woocommerce-catalog-enquiry' ),
						'database_value' => '',
					],
					[
						'key'       => 'custom_font_size',
						'depend_checkbox'    => 'is_button',
						'type'      => 'slider',
						'class'     =>  'mvx-setting-slider-class',
						'desc'      => __('', 'woocommerce-catalog-enquiry'),
						'label'     => __( 'Font Size', 'woocommerce-catalog-enquiry' ),
						'database_value' => '',
					],
					[
						'key'       => 'custom_border_radius',
						'depend_checkbox'    => 'is_button',
						'type'      => 'slider',
						'class'     =>  'mvx-setting-slider-class',
						'desc'      => __('', 'woocommerce-catalog-enquiry'),
						'label'     => __( 'Border Radius', 'woocommerce-catalog-enquiry' ),
						'database_value' => '',
					],
					[
						'key'       => 'custom_border_size',
						'depend_checkbox'    => 'is_button',
						'type'      => 'slider',
						'class'     =>  'mvx-setting-slider-class',
						'desc'      => __('', 'woocommerce-catalog-enquiry'),
						'label'     => __( 'Border Size', 'woocommerce-catalog-enquiry' ),
						'database_value' => '',
					],
					[
						'key'       => 'custom_top_gradient_color',
						'depend_checkbox'    => 'is_button',
						'type'      => 'color',
						'label'     => __( 'Top Gradient Color', 'woocommerce-product-stock-alert' ),
						'desc'      => __('This lets you choose button top gradient color.','woocommerce-product-stock-alert'),
						'database_value' => '',
					],
					[
						'key'       => 'custom_bottom_gradient_color',
						'type'      => 'color',
						'depend_checkbox'    => 'is_button',
						'label'     => __( 'Bottom Gradient Color', 'woocommerce-product-stock-alert' ),
						'desc'      => __('This lets you choose button buttom gradient color.','woocommerce-product-stock-alert'),
						'database_value' => '',
					],
					[
						'key'       => 'custom_border_color',
						'type'      => 'color',
						'depend_checkbox'    => 'is_button',
						'label'     => __( 'Border Color', 'woocommerce-product-stock-alert' ),
						'desc'      => __('This lets you choose button border color.','woocommerce-product-stock-alert'),
						'database_value' => '',
					],
					[
						'key'       => 'custom_text_color',
						'type'      => 'color',
						'depend_checkbox'    => 'is_button',
						'label'     => __( 'Text Color', 'woocommerce-product-stock-alert' ),
						'desc'      => __('This lets you choose button text color.','woocommerce-product-stock-alert'),
						'database_value' => '',
					],
					[
						'key'       => 'custom_hover_background_color',
						'type'      => 'color',
						'depend_checkbox'    => 'is_button',
						'label'     => __( 'Hover Background Color', 'woocommerce-product-stock-alert' ),
						'desc'      => __('This lets you choose button hover background color.','woocommerce-product-stock-alert'),
						'database_value' => '',
					],
					[
						'key'       => 'custom_hover_text_color',
						'type'      => 'color',
						'depend_checkbox'    => 'is_button',
						'label'     => __( 'Hover Text Color', 'woocommerce-product-stock-alert' ),
						'desc'      => __('This lets you choose button hover text color.','woocommerce-product-stock-alert'),
						'database_value' => '',
					],
					[
						'key'       => 'custom_button_font',
						'type'      => 'select',
						'depend_checkbox'    => 'is_button',
						'label'     => __( 'Select Font', 'woocommerce-catalog-enquiry' ),
						'options' => array(
							array(
								'key' => "helvetica",
								'label'=> __('Helvetica', 'woocommerce-catalog-enquiry'),
								'value'=> "Helvetica, Arial, Sans-Serif",
							),
							array(
								'key'=> "georgia",
								'label'=> __('Georgia', 'woocommerce-catalog-enquiry'),
								'value'=> "Georgia, Serif",
							),
							array(
								'key'=> "lucida_grande",
								'label'=> __('Lucida Grande', 'woocommerce-catalog-enquiry'),
								'value'=> 'Lucida Grande, Helvetica, Arial, Sans-Serif',
							),
						),
						'database_value' => '',
					],
		            [
	                    'key'       => 'separator4_content',
	                    'type'      => 'section',
	                    'label'     => "",
	                ],
					[
	                    'key'       =>  'woocommerce_catalog_enquiry_button2_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Additional Settings", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
		            [
		                'key'       => 'custom_css_product_page',
		                'type'      => 'textarea',
		                'class'     =>  'mvx-setting-wpeditor-class',
		                'desc'      => __('Put your custom css here, to customize the enquiry form.', 'woocommerce-catalog-enquiry'),
		                'label'     => __( 'Custom CSS', 'woocommerce-catalog-enquiry' ),
		                'database_value' => '',
		            ],
				]
			),
			'exclusion'       =>  array(
				'tablabel'      =>  __('Exclusion', 'woocommerce-catalog-enquiry'),
				'apiurl'        =>  'save_enquiry',
				'description'   =>  __("Enter the users, products, and categories that should not be included in the catalog settings. ", 'woocommerce-catalog-enquiry'),
				'icon'          =>  'icon-exclusion-tab',
				'submenu'       =>  'settings',
				'modulename'    =>  [
					[
	                    'key'       =>  'woocommerce_catalog_enquiry_exclution_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Exclusion Management", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
					[
		                'key'       => 'woocommerce_userroles_list',
		                'type'      => 'multi-select',
		                'label'     => __( 'User Role Specific Exclusion', 'woocommerce-catalog-enquiry' ),
		                'desc'        => __( 'Select the user roles, who won’t be able to send enquiry.', 'woocommerce-catalog-enquiry' ),
		                'options' => $role_array,
		                'database_value' => '',
	            	],
	            	[
		                'key'       => 'woocommerce_user_list',
		                'type'      => 'multi-select',
		                'label'     => __( 'User Name Specific Exclusion', 'woocommerce-catalog-enquiry' ),
		                'desc'        => __( 'Select the users, who won’t be able to send enquiry.', 'woocommerce-catalog-enquiry' ),
		                'options' => $all_users,
		                'database_value' => '',
	            	],
	            	[
		                'key'       => 'woocommerce_product_list',
		                'type'      => 'multi-select',
		                'label'     => __( 'Product Specific Exclusion', 'woocommerce-catalog-enquiry' ),
		                'desc'        => __( 'Select the products that should have the Add to cart button, instead of enquiry button.', 'woocommerce-catalog-enquiry' ),
		                'options' => $all_products,
		                'database_value' => '',
	            	],
	            	[
		                'key'       => 'woocommerce_category_list',
		                'type'      => 'multi-select',
		                'label'     => __( 'Category Specific Exclusion', 'woocommerce-catalog-enquiry' ),
		                'desc'        => __( 'Select the Category, where should have the Add to cart button, instead of enquiry button.', 'woocommerce-catalog-enquiry' ),
		                'options' => $all_product_cat,
		                'database_value' => '',
	            	]
				]
			),
			'enquiry-form'  =>  array(
				'tablabel'      =>  __('Enquiry Form', 'woocommerce-catalog-enquiry'),
				'apiurl'        =>  'save_enquiry',
				'description'   =>  __("Customise your product enquiry form", 'woocommerce-catalog-enquiry'),
				'icon'          =>  'icon-enquiry-form-tab',
				'submenu'       =>  'settings',
				'modulename'    =>  [
					[
	                    'key'       =>  'woocommerce_catalog_enquiry_form_general_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "General Settings", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
					[
		                'key'       => 'top_content_form',
		                'type'      => 'textarea',
		                'desc'      => __('This content will be displayed above your from.', 'woocommerce-catalog-enquiry'),
		                'label'     => __( 'Content Before Enquiry From', 'woocommerce-catalog-enquiry' ),
		                'database_value' => '',
		             ],
		             [
		                'key'       => 'bottom_content_form',
		                'type'      => 'textarea',
		                'desc'      => __('This content will be displayed after your from.', 'woocommerce-catalog-enquiry'),
		                'label'     => __( 'Content After Enquiry From', 'woocommerce-catalog-enquiry' ),
		                'database_value' => '',
		            ],
		            [
						'key'    => 'is_override_form_heading',
						'label'   => __( "Override Form Title?", 'woocommerce-catalog-enquiry' ),
						'class'     => 'mvx-toggle-checkbox',
						'type'    => 'checkbox',
						'options' => array(
								array(
										'key'=> "is_override_form_heading",
										'label'=> __('By default it will be "Enquiry about PRODUCT_NAME". Enable this to set your custom title.', 'woocommerce-catalog-enquiry'),
										'value'=> "is_override_form_heading"
								),
						),
						'database_value' => array(),
					],
					[
		                'key'       => 'custom_static_heading',
		                'depend_checkbox'    => 'is_override_form_heading',
		                'type'      => 'text',
		                'desc'      => __('Set custom from title. Use this specifier to replace the product name - %% PRODUCT_NAME %%.', 'woocommerce-catalog-enquiry'),
		                'label'     => __( 'Set Form Title', 'woocommerce-catalog-enquiry' ),
		                'database_value' => '',
		            ],
		            [
	                    'key'       => 'separator3_content',
	                    'type'      => 'section',
	                    'label'     => "",
	                ],
	                [
	                    'key'       =>  'woocommerce_catalog_enquiry_form_settings',
	                    'type'      =>  'blocktext',
	                    'label'     =>  __( 'no_label', 'woocommerce-catalog-enquiry' ),
	                    'blocktext'      =>  __( "Enquiry Form Fields", 'woocommerce-catalog-enquiry' ),
	                    'database_value' => '',
	                ],
		            [
	                    'key'       => 'enquiry_form_fileds',
	                    'type'      => 'table',
	                    'label'     => __( 'Enquiry Form Fileds', 'woocommerce-catalog-enquiry' ),
	                    'desc'      => __('Want to customise the form as per your need? To have a fully customizable form kindly upgrade to <a href="https://multivendorx.com/woocommerce-request-a-quote-product-catalog/" target="_blank">WooCommerce Catalog Enquiry Pro</a>', 'woocommerce-catalog-enquiry', 'woocommerce-catalog-enquiry'),
	                    'label_options' =>  array(
	                       __('Field Name', 'woocommerce-catalog-enquiry'),
	                       __('Enable / Disable', 'woocommerce-catalog-enquiry'),
	                       __('Set New Field Name', 'woocommerce-catalog-enquiry'),
	                    ),
	                    'options' => [
	                        [
	                            'variable'=> __("Name", 'woocommerce-catalog-enquiry'),
	                            'id' => 'name-label',
	                            'is_enable'=> false,
	                            'description'=> __('Enables you to create a seller dashboard ', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("Email", 'woocommerce-catalog-enquiry'),
	                            'id' => 'email-label', 
	                            'is_enable'=> false,
	                            'description'=> __('Creates a page where the vendor registration form is available', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("Phone", 'woocommerce-catalog-enquiry'),
	                            'id' => 'is-phone', 
	                            'is_enable'=> true,
	                            'description'=> __('Lets you view  a brief summary of the coupons created by the seller and number of times it has been used by the customers', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("Address", 'woocommerce-catalog-enquiry'),
	                            'id' => 'is-address', 
	                            'is_enable'=> true,
	                            'description'=> __('Allows you to glance at the recent products added by seller', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("Enquiry About", 'woocommerce-catalog-enquiry'),
	                            'id' => 'is-subject', 
	                            'is_enable'=> true,
	                            'description'=> __('Displays the products added by seller', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("Enquiry Details", 'woocommerce-catalog-enquiry'),
	                            'id' => 'is-comment', 
	                            'is_enable'=> true,
	                            'description'=> __('Exhibits featured products added by the seller', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("File Upload", 'woocommerce-catalog-enquiry'),
	                            'id' => 'is-fileupload', 
	                            'is_enable'=> true,
	                            'description'=> __('Allows you to see the products put on sale by a seller', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("File Upload Size Limit ( in MB )", 'woocommerce-catalog-enquiry'),
	                            'id' => 'filesize-limit', 
	                            'is_enable'=> true,
	                            'description'=> __('Allows you to see the products put on sale by a seller', 'woocommerce-catalog-enquiry'),
	                        ],
	                        [
	                            'variable'=> __("Captcha", 'woocommerce-catalog-enquiry'),
	                            'id' => 'is-captcha', 
	                            'is_enable'=> true,
	                            'description'=> __('Displays the top rated products of the seller', 'woocommerce-catalog-enquiry'),
	                        ],
	                   
	                    ],
	                    'database_value' => '',
	                ],
				]
			),
			'live-preview'  =>  array(
					'tablabel'      =>  __('Live Preview', 'woocommerce-catalog-enquiry'),
					'icon'          =>  'icon-live-preview-tab',
					'class'			=>	'catalog-live-preview',
					'link'          =>  'https://multivendordemo.com/product-catalog-enquiry-pro/wp-admin',
			),
			'upgrade' =>  array(
					'tablabel'      =>  __('Upgrade To Pro For More Features', 'woocommerce-catalog-enquiry'),
					'icon'          =>  'icon-upgrade-to-pro-tab',
					'class'			=>	'catalog-upgrade',
					'link'          =>  'https://multivendorx.com/woocommerce-request-a-quote-product-catalog/',
			),
		) );

		if (!empty($catalog_settings_page_endpoint)) {
            foreach ($catalog_settings_page_endpoint as $settings_key => $settings_value) {
            	if (isset($settings_value['modulename']) && !empty($settings_value['modulename'])) {
	                foreach ($settings_value['modulename'] as $inter_key => $inter_value) {
	                    $change_settings_key    =   str_replace("-", "_", $settings_key);
	                    $option_name = 'mvx_catalog_'.$change_settings_key.'_tab_settings';
	                    $database_value = get_option($option_name) ? get_option($option_name) : array();
	                    if (!empty($database_value)) {
	                        if (isset($inter_value['key']) && array_key_exists($inter_value['key'], $database_value)) {
	                            if (empty($inter_value['database_value'])) {
	                               $catalog_settings_page_endpoint[$settings_key]['modulename'][$inter_key]['database_value'] = $database_value[$inter_value['key']];
	                            }
	                        }
	                    }
	                }
	            }
            }
        }

		$mvx_catalog_backend_tab_list = apply_filters('mvx_catalog_tab_list', array(
			'catalog-settings'      => $catalog_settings_page_endpoint,
		));

		return $mvx_catalog_backend_tab_list;
	}
}
