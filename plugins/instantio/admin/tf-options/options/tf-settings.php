<?php
// don't load directly
defined( 'ABSPATH' ) || exit;


if ( file_exists( INS_OPTIONS_PATH . 'options/tf-menu-icon.php' ) ) {
	require_once INS_OPTIONS_PATH . 'options/tf-menu-icon.php';
} else {
	$menu_icon = 'dashicons-cart';
}

if ( file_exists( INS_CONTROLLER_PATH . '/ins-checkout-editor.php' ) ) {
	require_once INS_CONTROLLER_PATH . '/ins-checkout-editor.php';
}

if ( current_user_can( 'manage_options' ) ) {
	$my_plugin_billing_fields = ins_defualt_billing_checkout_from();
	$my_plugin_shipping_fields = ins_defualt_shipping_checkout_from();
}

Ins_TF_Settings::option( 'wiopt', array(
	'title' => __( 'Instantio', 'instantio' ),
	'icon' => 'dashicons-cart',
	'position' => 25,
	'sections' => array(

		/**
		 * General
		 * Main menu
		 */
		'general' => array(
			'title' => esc_html__( 'General', 'instantio' ),
			'icon' => 'fa fa-cog',
			'fields' => array(
				// array(
				// 	'id'    		=> 'cart_section',
				// 	'type'    		=> 'heading',
				// 	'content' 		=> __('Welcome to the instantio Settion', 'instantio'),
				// ),
			),
		),

		'layout_option' => array(
			'title' => esc_html__( 'Layout', 'instantio' ),
			'parent' => 'general',
			'icon' => 'fas fa-layer-group',
			'fields' => array(

				array(
					'id' => 'ins-layout-options',
					'type' => 'imageselect',
					'class' => 'ins-layout-options-imageset200',
					'label' => __( 'Choose cart options', 'instantio' ),
					'multiple' => true,
					'inline' => true,
					'options' => array(
						'1' => array(
							'title' => 'Direct Checkout',
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/Directcheckout.jpg',
						),
						'2' => array(
							'title' => 'Side Cart',
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/sidecart.jpg',
						),
						'3' => array(
							'title' => 'Popup Cart',
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/Popup.jpg',
						)
					),
					'default' => '2',
					// 'dependency' 	=> array('ins-layout',  '!=', '1', '', 'visible' ),
				),


				array(
					'id' => 'ins-layout-mode',
					'type' => 'imageselect',
					'class' => 'ins-layout-options-imageset200',
					'label' => __( 'Choose mode', 'instantio' ),
					'multiple' => true,
					'inline' => true,
					'options' => array(
						'light' => array(
							'title' => __( 'Light', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/Light.png',
						),

						'dark' => array(
							'title' => __( 'Dark', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/Dark.png',
						),

						'glass-morphism' => array(
							'title' => __( 'Glass morphism', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/GlassMorphism.png',
						),
						'gradient' => array(
							'title' => __( 'Gradient', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/Gradient.png',
						)
					),
					'default' => 'light',
					'dependency' => array( 'ins-layout-options', '!=', '1' ),
				),

				array(
					'id' => 'ins-layout',
					'type' => 'imageselect',
					'class' => 'ins-layout-options-imageset200',
					'label' => __( 'Choose layout', 'instantio' ),
					'multiple' => true,
					'inline' => true,
					'options' => array(
						'cart' => array(
							'title' => __( 'Cart', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/sidecart.jpg',
						),

						'cart_and_checkout' => array(
							'title' => __( 'Cart & Checkout', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/CartCheckout.svg',
						)
					),
					'default' => 'cart',
					'dependency' => array( 'ins-layout-options', '!=', '1' ),
				),

				array(
					'id' => 'ins-layout-step',
					'type' => 'switch',
					'label' => __( 'Enabled single step', 'instantio' ),
					'label_on' => __( 'Enabled', 'instantio' ),
					'label_off' => __( 'Disabled', 'instantio' ),
					'is_pro' => true,
					'default' => false,
					'dependency' => array( 'ins-layout-options', '!=', '1' ),
				),

				array(
					'id' => 'ins-layout-animation',
					'type' => 'select',
					'class' => 'ins-layout-options-imageset200',
					'label' => __( 'Choose layout Animation', 'instantio' ),
					'options' => array(
						'ins_animate_default' => 'Default Animation',
						'ins_animate_one' => 'Fade In Animate',
						// 'ins_animate_two' 	=>  'Animate Two'
					),
					'default' => 'ins_animate_default',
					'dependency' => array( 'ins-layout-options', '!=', '1' ),
					// 'dependency' 	=> array('ins-layout',  '!=', '1', '', 'visible' ),
				),


				array(
					'id' => 'ins-layout-progressbar',
					'type' => 'imageselect',
					'label' => 'Choose progress bar',
					'class' => 'ins-layout-options-imageset300',
					'is_pro' => true,
					'multiple' => true,
					'inline' => true,
					'options' => array(
						'progress1' => array(
							'title' => __( 'Version 1', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/version1.png',
						),
						'progress2' => array(
							'title' => __( 'Version 2', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/version2.png',
						),
						'progress3' => array(
							'title' => __( 'Version 3', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/version3.png',
						),
						'progress4' => array(
							'title' => __( 'Version 4', 'instantio' ),
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/version4.png',
						),
					),
					'dependency' => array( 'ins-layout-options', '!=', '1' ),
					// 'default'   	=> 'progress2',
				),

			),
		),

		'general_toggle' => array(
			'title' => esc_html__( 'Toggle', 'instantio' ),
			'parent' => 'general',
			'icon' => 'fas fa-toggle-on',
			'fields' => array(
				array(
					'id' => 'ins-toggler',
					'type' => 'imageselect',
					'label' => __( 'Choose Icon design ', 'instantio' ),
					'class' => 'ins-layout-options-imageset',
					'multiple' => true,
					'inline' => true,
					'options' => array(
						'tog-1' => array(
							'title' => '',
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/toggler-1.svg',
						),
						'tog-2' => array(
							'title' => '',
							'url' => plugin_dir_url( __FILE__ ) . '../img/layout/toggler-2.svg',
						)
					),
					'default' => 'tog-1',
					// 'dependency' 	=> array('ins-layout',  '!=', '1', '', 'visible' ),
				),
				array(
					'id' => 'auto-tog-panel',
					'type' => 'switch',
					'label' => __( 'Auto Open Toggle Panel', 'instantio' ),
					'label_on' => __( 'Enabled', 'instantio' ),
					'label_off' => __( 'Disabled', 'instantio' ),
					// 'width' 		=> 100,
					'default' => false,
				),

				array(
					'id' => 'ins-cart-emty-hide',
					'type' => 'switch',
					'label' => __( 'Hide Toggle when No Cart Item', 'instantio' ),
					'label_on' => __( 'Yes', 'instantio' ),
					'label_off' => __( 'No', 'instantio' ),
					'default' => false
				),

				array(
					'id' => 'cart-fly',
					'type' => 'fieldset',
					'label' => __( 'Cart Fly Animation', 'instantio' ),
					'subtitle' => __( 'Enable/dsiable cart fly animation or change icon', 'instantio' ),
					'fields' => array(
						array(
							'id' => 'cart-fly-anim',
							'type' => 'switch',
							'label' => __( 'Cart Fly Animation', 'instantio' ),
							'label_on' => __( 'Enabled', 'instantio' ),
							'label_off' => __( 'Disabled', 'instantio' ),
							//   'width' 			=> 100,
							'default' => false,
						),

						array(
							'id' => 'cart-fly-icon',
							'type' => 'select',
							'label' => __( 'Cart Fly Animation Icon', 'instantio' ),
							'class' => 'tf-field-class',
							'options' => array(
								'0' => 'Cart Icon',
								'1' => 'Product Thumbnail',
							),
							'default' => 1,
							'dependency' => array( 'cart-fly-anim', '==', 1 ),
						)
					),
				),



			),
		),

		'general_cart' => array(
			'title' => esc_html__( 'Cart', 'instantio' ),
			'parent' => 'general',
			'icon' => 'fas fa-cart-flatbed',
			'fields' => array(
				array(
					'id' => 'cart-btn',
					'type' => 'fieldset',
					'label' => __( 'Cart Button', 'instantio' ),
					'subtitle' => __( 'Show/hide or change cart button\'s text & url', 'instantio' ),
					// 'dependency' 	=> array('ins-layout','!=', '1'),
					'fields' => array(
						array(
							'id' => 'on-cart-btn',
							'type' => 'switch',
							'label' => __( 'Edit Cart Button', 'instantio' ),
							'label_on' => __( 'Yes', 'instantio' ),
							'label_off' => __( 'No', 'instantio' ),
							'default' => false,
						),
						array(
							'id' => 'cart_button_text',
							'type' => 'text',
							'label' => __( 'Cart Button Text', 'instantio' ),
							'placeholder' => 'View Cart',
							'description' => __( 'Default: <code>View Cart</code>', 'instantio' ),
							'default' => 'View Cart',
							'dependency' => array( 'on-cart-btn', '==', 'true' ),
						),
						array(
							'id' => 'cart_button_url',
							'type' => 'text',
							'label' => __( 'Cart Button URL', 'instantio' ),
							'placeholder' => 'https://',
							'description' => __( 'Default: Default cart page', 'instantio' ),
							'validate' => 'tf_validate_url',
							'dependency' => array( 'on-cart-btn', '==', 'true' ),
						),
					),
				),

				array(
					'id' => 'checkout-btn',
					'type' => 'fieldset',
					'label' => __( 'Checkout Button', 'instantio' ),
					'subtitle' => __( 'Show/hide or change checkout button\'s text & url', 'instantio' ),
					// 'dependency'	=> array('ins-layout','!=', '1', ' ', 'visible'),
					'fields' => array(
						array(
							'id' => 'on-checkout-btn',
							'type' => 'switch',
							'label' => __( 'Edit Checkout Button', 'instantio' ),
							'label_on' => __( 'Yes', 'instantio' ),
							'label_off' => __( 'No', 'instantio' ),
							'default' => false,
						),
						array(
							'id' => 'checkout_button_text',
							'type' => 'text',
							'default' => 'Checkout',
							'placeholder' => 'Checkout',
							'label' => __( 'Checkout Button Text', 'instantio' ),
							'description' => __( 'Default: <code>Checkout</code>', 'instantio' ),
							'dependency' => array( 'on-checkout-btn', '==', 'true' ),
						),
						array(
							'id' => 'checkout_button_url',
							'type' => 'text',
							'label' => __( 'Checkout Button URL', 'instantio' ),
							'placeholder' => 'https://',
							'description' => __( 'Default: Default Checkout page', 'instantio' ),
							'validate' => 'csf_validate_url',
							'dependency' => array( 'on-checkout-btn', '==', 'true' ),
						),
					),
				),

				array(
					'id' => 'ins-page-selected',
					'type' => 'select2',
					'placeholder' => __( 'Disable Instantio', 'instantio' ),
					'label' => __( 'Disable Instantio', 'instantio' ),
					'default' => 'cart',
					'description' => __( 'The Instantio cart functionality will not be visible on this particular page. Note: You won\'t be able to hide it from the default shop page.', 'instantio' ),
					'is_pro' => true,
					'options' => 'posts',
					'query_args' => array(
						'post_type' => 'page',
						'posts_per_page' => -1,
					)
				),

				array(
					'id' => 'woins-quickview-disable',
					'class' => 'ins-csf-disable badge_pro',
					'type' => 'switch',
					'label' => __( 'Disable Quick View', 'instantio' ),
					'subtitle' => __( 'You can disable it if you already have quick view function in your theme (Applicable for Variable products)', 'instantio' ),
					'is_pro' => true,
					'label_on' => __( 'Yes', 'instantio' ),
					'label_off' => __( 'No', 'instantio' ),
					'default' => false,
				),

				array(
					'id' => 'wi-disable-ajax-add-cart',
					'class' => 'ins-csf-disable badge_pro',
					'type' => 'switch',
					'label' => __( 'Disable Ajax Add to Cart', 'instantio' ),
					'subtitle' => __( 'You can disable it if you already have ajax "add to cart" function in your theme (To avoid conflict)', 'instantio' ),
					'is_pro' => true,
					'label_on' => __( 'Yes', 'instantio' ),
					'label_off' => __( 'No', 'instantio' ),
					'default' => false,
				),
				array(
					'id' => 'ins-upsell',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type' => 'switch',
					'label' => __( 'Show Upsells Product in Cart', 'instantio' ),
					'subtitle' => __( 'Enable/disable upsells items in cart', 'instantio' ),
					'label_on' => __( 'Enabled', 'instantio' ),
					'label_off' => __( 'Disabled', 'instantio' ),
					// 'width' 		=> 100,
					'is_pro' => true,
					'default' => false,
				),

				array(
					'id' => 'upsell-heading',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type' => 'text',
					'label' => __( 'Upsell Heading', 'instantio' ),
					'subtitle' => __( 'The text shown before upsell items', 'instantio' ),
					'desc' => __( 'Default: Hang on! We have this offer just for you!', 'instantio' ),
					'placeholder' => __( 'Hang on! We have this offer just for you!', 'instantio' ),
					'is_pro' => true,
				),

				array(
					'id' => 'crosssell',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type' => 'switch',
					'label' => __( 'Cross Sells in Checkout', 'instantio' ),
					'subtitle' => __( 'Enable/disable cross sell items in checkout', 'instantio' ),
					'label_on' => __( 'Enabled', 'instantio' ),
					'label_off' => __( 'Disabled', 'instantio' ),
					// 'width' 		=> 100,
					'default' => false,
					'is_pro' => true,
				),

				array(
					'id' => 'crosssell-heading',
					'class' => 'ins-csf-disable ins-csf-pro',
					'type' => 'text',
					'label' => __( 'Cross Sell Heading', 'instantio' ),
					'subtitle' => __( 'The text shown before cross sell items', 'instantio' ),
					'desc' => __( 'Default: You may be interested in…', 'instantio' ),
					'placeholder' => __( 'You may be interested in…', 'instantio' ),
					'default' => 'Enter your default value',
					'is_pro' => true,
				),
			),
		),

		'design_option' => array(
			'title' => esc_html__( 'Design', 'instantio' ),
			'icon' => 'fas fa-palette',
			'fields' => array(),
		),

		'toggle_page' => array(
			'title' => esc_html__( 'Cart Icon', 'instantio' ),
			'parent' => 'design_option',
			'icon' => 'fa fa-cogs',
			'fields' => array(
				array(
					'id' => 'ins-toggle-tab',
					'type' => 'tab',
					'class' => 'toggle_page_tab_parent',
					'tabs' => array(
						array(
							'id' => 'toggle_page_general',
							'title' => 'General',
							'icon' => 'fa fa-cogs',
							'fields' => array(
								array(
									'id' => 'label_off_heading',
									'type' => 'heading',
									'label' => __( 'Global Settings for Instantio Cart icon', 'instantio' ),
									'sub_title' => __( 'These options can be overridden from defualt Settings.', 'instantio' ),
								),

								array(
									'id' => 'cart-icon-style',
									'class' => 'imageset-inline',
									'type' => 'imageselect',
									'label' => __( 'Cart Icon Style', 'instantio' ),
									'subtitle' => __( 'Select cart icon Style which will appear in cart Icon', 'instantio' ),
									'options' => array(
										'cart-style-1' => array(
											'title' => '',
											'url' => plugin_dir_url( __FILE__ ) . '../img/cart-style-1.svg',
										),
										'cart-style-2' => array(
											'title' => '',
											'url' => plugin_dir_url( __FILE__ ) . '../img/cart-2.svg',
										),
										'cart-style-3' => array(
											'title' => '',
											'url' => plugin_dir_url( __FILE__ ) . '../img/cart-3.svg',
										),
										'cart-style-4' => array(
											'title' => '',
											'url' => plugin_dir_url( __FILE__ ) . '../img/cart-4.svg',
										)
									),

									'default' => 'cart-style-1'
								),

								array(
									'id' => 'wi-icon-choice',
									'type' => 'select',
									'label' => __( 'Select Cart Icon Option', 'instantio' ),
									'subtitle' => __( 'Set custom Icon Choice as icon for the cart instead of the defaults Icon.', 'instantio' ),
									'class' => 'tf-field-class',
									'options' => array(
										'icon' => 'Select Icon',
										'image' => 'Select Image',
									),
									'default' => 'icon',
									'inline' => true,
								),
								array(
									'id' => 'cart-icon',
									'type' => 'icon',
									'label' => __( 'Cart Icon', 'instantio' ),
									'subtitle' => __( 'Select cart icon which will appear in cart Icon', 'instantio' ),
									'dependency' => array( 'wi-icon-choice', '==', 'icon' ),
								),
								array(
									'id' => 'wi-icon-choice-uploder',
									'type' => 'image',
									'is_pro' => true,
									'label' => 'Custom Toggler Icon',
									'subtitle' => __( 'Upload your cart icon. Recommended size of an icon is 26x26px', 'instantio' ),
									'description' => __( 'If Custom Image as Toggler Icon it\'s then it will work', 'instantio' ),
									'dependency' => array( 'wi-icon-choice', '==', 'image' ),
								),

								array(
									'id' => 'toggle-position',
									'type' => 'select',
									'label' => __( 'Icon Position', 'instantio' ),
									'subtitle' => __( 'Changes position of the Cart Icon', 'instantio' ),
									'class' => 'tf-field-class',
									'options' => array(
										'right-top' => 'Right Top',
										'right-middle' => 'Right Middle',
										'right-bottom' => 'Right Bottom',
										'left-top' => 'Left Top',
										'left-middle' => 'Left Middle',
										'left-bottom' => 'Left Bottom',
									),
									'default' => 'right-bottom',
								),
								array(
									'id' => 'wi-header-icon-size',
									'type' => 'number',
									'label' => __( 'Cart Icon Size', 'instantio' ),
									'subtitle' => __( 'Set width of the toggler icon', 'instantio' ),
									'placeholder' => __( 'Default: 26', 'instantio' ),
									'description' => __( 'Default: 26px', 'instantio' ),
								),

								array(
									'id' => 'wi-header-text-size',
									'type' => 'number',
									'label' => __( 'Cart Total Item Size', 'instantio' ),
									'subtitle' => __( 'Set font size & line height of cart total item number', 'instantio' ),
									'description' => __( 'Default: 14px', 'instantio' ),
								),
							),
						),
						array(
							'id' => 'toggle_page_colors',
							'title' => 'Colors',
							'icon' => 'fa fa-gear',
							'fields' => array(
								array(
									'id' => 'wi-header-bg-colors',
									'type' => 'color',
									'label' => __( 'Cart Icon Background Colors', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color', 'instantio' ),
									'default' => '#ffffff',
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									),
								),

								array(
									'id' => 'wi-header-border-colors',
									'type' => 'color',
									'label' => __( 'Cart Icon Border Colors', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color', 'instantio' ),
									'default' => '#ffffff',
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),

								array(
									'id' => 'ins-tog-icon-colors',
									'type' => 'color',
									'label' => __( 'Cart Icon Color', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color of text & icon', 'instantio' ),
									'default' => '#ffffff',
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),

								array(
									'id' => 'wi-header-text-colors',
									'type' => 'color',
									'label' => __( 'Cart Total Item Color', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color for cart items total number', 'instantio' ),
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),

								array(
									'id' => 'ins-tog-item-bg',
									'type' => 'color',
									'label' => __( 'Cart Total Item Background', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color for cart items total number background', 'instantio' ),
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),

							),
						),
					),
				),
			),
		),

		'toggle_panel' => array(
			'title' => esc_html__( 'Cart Panel', 'instantio' ),
			'parent' => 'design_option',
			'icon' => 'fa fa-toggle-off',
			'fields' => array(
				array(
					'id' => 'ins-toggle-panel-tab',
					'type' => 'tab',
					'tabs' => array(
						array(
							'id' => 'toggle_panel_general',
							'title' => esc_html__( 'General', 'instantio' ),
							'fields' => array(
								array(
									'id' => 'toggle_panel_heading',
									'type' => 'heading',
									'label' => __( 'Cart Panel Design', 'instantio' ),
								),

								// array(
								// 	'id'       		=> 'toggle-panel-position',
								// 	'type'     		=> 'imageselect',
								// 	'multiple' 		=> true,
								// 	'inline'   		=> true,
								// 	'class' 		=> 'ins-layout-options-imageset200',
								// 	'label'    		=> __('Toggle Panel Position', 'instantio'),
								// 	'subtitle' 		=> __('Changes position of the Cart Toggle Panel (Cart Panel)', 'instantio'),
								// 	'options'  		=> array(
								// 		'left'   			=> array(
								// 			'title'			=> 'Cart Panel Left',
								// 			'url' 			=> plugin_dir_url( __FILE__ ).'../img/layout/sidecartleft.png',
								// 		),
								// 		'right'  			=> array(
								// 			'title'			=> 'Cart Panel Right',
								// 			'url' 			=> plugin_dir_url( __FILE__ ).'../img/layout/sidecart.jpg',
								// 		),
								// 	), 
								// 	'default'  		=> 'right',
								// 	'inline'   		=> true,
								// ),
								array(
									'id' => 'ins_panel_Theme_color',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Panel Theme Color', 'instantio' ),
									'subtitle' => __( 'Theme color refers to a primary used throughout instantio', 'instantio' ),
								),
								array(
									'id' => 'ins_panel_border_option',
									'type' => 'switch',
									'label' => __( 'Enable Cart Panel Border', 'instantio' ),
									'subtitle' => __( 'Set Toggle Panel Border', 'instantio' ),
									'label_on' => __( 'Enable', 'instantio' ),
									'label_off' => __( 'Disable', 'instantio' ),
									// 'width'   		=> 100,
									'default' => false,
								),

								array(
									'id' => 'ins_panel_border_color',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Border Color', 'instantio' ),
									'subtitle' => __( 'Toggle Panel Border Color', 'instantio' ),
									'dependency' => array( 'ins_panel_border_option', '==', 'true' ),
								),
								array(
									'id' => 'ins-panel-border-top',
									'class' => 'tf-field-inline',
									'type' => 'number',
									'field_width' => '25',
									'label' => 'Border Top',
									'subtitle' => 'Border Top ',
									'placeholder' => '1px',
									'default' => '1px',
									'dependency' => array( 'ins_panel_border_option', '==', 'true' ),
								),
								array(
									'id' => 'ins-panel-border-right',
									'class' => 'tf-field-inline',
									'type' => 'number',
									'field_width' => '25',
									'label' => 'Border Right',
									'subtitle' => 'Border Right ',
									'placeholder' => '1px',
									'default' => '1px',
									'dependency' => array( 'ins_panel_border_option', '==', 'true' ),
								),
								array(
									'id' => 'ins-panel-border-bottom',
									'class' => 'tf-field-inline',
									'type' => 'number',
									'field_width' => '25',
									'label' => 'Border Bottom',
									'subtitle' => 'Border Bottom ',
									'placeholder' => '1px',
									'default' => '1px',
									'dependency' => array( 'ins_panel_border_option', '==', 'true' ),
								),
								array(
									'id' => 'ins-panel-border-left',
									'class' => 'tf-field-inline',
									'type' => 'number',
									'field_width' => '25',
									'label' => 'Border Left',
									'subtitle' => 'Border Left ',
									'placeholder' => '1px',
									'default' => '1px',
									'dependency' => array( 'ins_panel_border_option', '==', 'true' ),
								),

								array(
									'id' => 'wi-zindex',
									'type' => 'number',
									'field_width' => '50',
									'label' => __( 'Panel Z-index', 'instantio' ),
									'subtitle' => __( 'Control z-index from this option. More about <a target="_blank" href="https://css-tricks.com/almanac/properties/z/z-index/">z-index</a>', 'instantio' ),
									'default' => 99999,
									'attributes' => array(
										'min' => 999,
										'max' => 9999999,
									),
								),

								array(
									'id' => 'panel-width-1200',
									'type' => 'number',
									'field_width' => '50',
									'label' => __( 'Cart Panel Width (1200px-auto)', 'instantio' ),
									'subtitle' => __( 'Set the percent of width of cart panel for display dimension greater than 1199px.', 'instantio' ),
									'description' => __( 'Default 690px', 'instantio' ),
									"default" => 690,
									// 'attributes'	=> array(
									// 	"min"       		=> 1,
									// 	"max"       		=> 100,
									// ),
								),

								array(
									'id' => 'panel-width-1024',
									'type' => 'number',
									'field_width' => '50',
									'label' => __( 'Cart Panel Width (1024px-1199px)', 'instantio' ),
									'subtitle' => __( 'Set the percent of width of cart panel for display dimension greater than 1023px.', 'instantio' ),
									'description' => __( ' Default 620px', 'instantio' ),
									"default" => 620,
									// 'attributes'	=> array(
									// 	"min"       		=> 1,
									// 	"max"       		=> 100,
									// ),
								),

								array(
									'id' => 'panel-width-767',
									'type' => 'number',
									'field_width' => '50',
									'label' => __( 'Cart Panel Width (501px-1023)', 'instantio' ),
									'subtitle' => __( 'Set the percent of width of cart panel for display dimension greater than 500px.', 'instantio' ),
									'description' => __( 'Default 400 <br/> <br/> Width is 100% for devices which dimension up to 500px.', 'instantio' ),
									"default" => 420,
									// 'attributes'	=> array(
									// 	"min"       		=> 1,
									// 	"max"       		=> 100,
									// ),
								),

								array(
									'id' => 'toggle_panel_heading',
									'type' => 'heading',
									'label' => __( 'Cart Panel Colors', 'instantio' ),
								),
								array(
									'id' => 'wi-container-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Background', 'instantio' ),
									'subtitle' => __( 'Cart Panel Background Color', 'instantio' ),
								),

								array(
									'id' => 'ins-panel-text-color',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Text Color', 'instantio' ),
									'subtitle' => __( 'Openning heading "Your Cart" color', 'instantio' )
								),

								array(
									'id' => 'ins-panel-button-bg',
									'type' => 'color',
									'label' => __( 'Panel Button Colors', 'instantio' ),
									'subtitle' => __( '"Proceed to Checkout", "Back to Cart" button color', 'instantio' ),
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),

								array(
									'id' => 'ins-panel-button-border',
									'type' => 'color',
									'label' => __( 'Panel Button Border Colors', 'instantio' ),
									'subtitle' => __( '"Proceed to Checkout", "Back to Cart" button border color', 'instantio' ),
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),

								array(
									'id' => 'ins-panel-button-text',
									'type' => 'color',
									'label' => __( 'Panel Button Text Colors', 'instantio' ),
									'subtitle' => __( '"Proceed to Checkout", "Back to Cart" button text color', 'instantio' ),
									'multiple' => true,
									'inline' => true,
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									)
								),
							),
						),
						array(
							'id' => 'toggle_panel_cart',
							'title' => esc_html__( 'Cart', 'instantio' ),
							'fields' => array(
								array(
									'id' => 'cart_section',
									'type' => 'heading',
									'content' => __( 'Cart Section Colors', 'instantio' ),
								),

								array(
									'id' => 'cart-header-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Header Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart header background color', 'instantio' ),
								),

								array(
									'id' => 'cart-header-text',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Header Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart header text color', 'instantio' ),
								),

								array(
									'id' => 'cart-item-bg-wrap',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Items Wrapper Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart items Wrapper background color', 'instantio' ),
								),
								array(
									'id' => 'cart-item-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Items Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart items background color', 'instantio' ),
								),

								array(
									'id' => 'cart-item-text-color',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Item Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart item text color', 'instantio' ),
								),

								array(
									'id' => 'cart-input-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Input Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart input background color', 'instantio' ),
								),

								array(
									'id' => 'cart-input-text-color',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Input Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel cart input text color', 'instantio' ),
								),

								array(
									'id' => 'cart-pricing-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Pricing Table Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel pricing table background color', 'instantio' ),
								),

								array(
									'id' => 'cart-pricing-text',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Cart Pricing Table Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel pricing table text color', 'instantio' ),
								),

								array(
									'id' => 'cart-button-background-colors',
									'type' => 'color',
									'multiple' => true,
									'inline' => true,
									'label' => __( 'Cart Button Background Colors', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color', 'instantio' ),
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									),
								),

								array(
									'id' => 'cart-button-text-colors',
									'type' => 'color',
									'multiple' => true,
									'inline' => true,
									'label' => __( 'Cart Button Text Colors', 'instantio' ),
									'subtitle' => __( 'Set regular & hover color', 'instantio' ),
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									),
								),

							),
						),

						array(
							'id' => 'toggle_panel_checkout',
							'title' => esc_html__( 'Checkout', 'instantio' ),
							'fields' => array(
								array(
									'id' => 'Billing_section',
									'type' => 'heading',
									'content' => __( 'Billing Section', 'instantio' ),
								),

								array(
									'id' => 'ins-bill-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section background color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-bill-heading',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Heading Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section heading text color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-bill-label',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Label Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section label text color', 'instantio' ),
									'output' => array( '.ins-checkout-body form.woocommerce-checkout .form-row label' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-bill-input-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Input Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section input background color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-bill-input-border',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Input Border Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section input border color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-bill-input-text',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Input Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section input text color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-bill-input-shadow',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Panel Billing Input Shadow Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel billing section input shadow color', 'instantio' ),
									'is_pro' => true,
								),

							),
						),

						array(
							'id' => 'toggle_panel_payment',
							'title' => esc_html__( 'Payment', 'instantio' ),
							'fields' => array(
								array(
									'id' => 'payment-section',
									'type' => 'heading',
									'content' => __( 'Payment Section', 'instantio' ),
								),

								array(
									'id' => 'ins-pay-item-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Payment Methods Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel Payment methods background color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-pay-item-txt',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Payment Methods Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel Payment methods text color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-pay-item-des-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Payment Methods Description Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel Payment methods description background color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-pay-item-des-txt',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Payment Methods Description Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel Payment methods description text color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-place-order-bg',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Place Order Background', 'instantio' ),
									'subtitle' => __( 'Toggle panel place order background color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-place-order-txt',
									'type' => 'color',
									'class' => 'tf-field-color-single',
									'label' => __( 'Place Order Text Color', 'instantio' ),
									'subtitle' => __( 'Toggle panel place order text color', 'instantio' ),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-place-order-button-bg',
									'type' => 'color',
									'multiple' => true,
									'inline' => true,
									'label' => __( 'Place Order Button Background', 'instantio' ),
									'subtitle' => __( 'Place order button background regular & hover color', 'instantio' ),
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-place-order-button-border',
									'type' => 'color',
									'multiple' => true,
									'inline' => true,
									'label' => __( 'Place Order Button Border Colors', 'instantio' ),
									'subtitle' => __( 'Place order button border regular & hover color', 'instantio' ),
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									),
									'is_pro' => true,
								),

								array(
									'id' => 'ins-place-order-button-text',
									'type' => 'color',
									'multiple' => true,
									'inline' => true,
									'label' => __( 'Place Order Button Text Colors', 'instantio' ),
									'subtitle' => __( 'Place order button text regular & hover color', 'instantio' ),
									'colors' => array(
										'regular' => __( 'Regular', 'instantio' ),
										'hover' => __( 'Hover', 'instantio' ),
									),
									'is_pro' => true,
								),
							),
						),

						// array(
						// 	'id'  		=> 'toggle_panel_confirmation',
						// 	'title'		=> esc_html__( 'Checkout Confirmation', 'instantio' ),
						// 	'fields' 	=> array(
						// 		array(
						// 			'id'       		=> 'confirmation_thankyou',
						// 			'type'     		=> 'color',
						// 			'class'			=> 'tf-field-color-single',
						// 			'label'    		=> __( 'Confirmation Page ThankYou', 'instantio' ),
						// 			'subtitle' 		=> __( 'Confirmation Page Thank You message Color', 'instantio' ),
						// 			'is_pro'		=> true,
						// 		),

						// 		array(
						// 			'id'       		=> 'confirmation_contact',
						// 			'type'     		=> 'color',
						// 			'class'			=> 'tf-field-color-single',
						// 			'label'    		=> __( 'Confirmation Page Contact', 'instantio' ),
						// 			'subtitle' 		=> __( 'Confirmation Page Contact Info Color', 'instantio' ),
						// 			'is_pro'		=> true,
						// 		),

						// 		array(
						// 			'id'       		=> 'confirmation_shipping',
						// 			'type'     		=> 'color',
						// 			'class'			=> 'tf-field-color-single',
						// 			'label'    		=> __( 'Confirmation Page Shipping', 'instantio' ),
						// 			'subtitle' 		=> __( 'Confirmation Page Shipping Info Color', 'instantio' ),
						// 			'is_pro'		=> true,
						// 		),
						// 	),
						// ),


					),
				),

			),
		),

		'other_design' => array(
			'title' => esc_html__( 'Others', 'instantio' ),
			'parent' => 'design_option',
			'icon' => 'fa fa-scroll',
			'fields' => array(

				array(
					'id' => 'wi-quickview-bg',
					'type' => 'color',
					'class' => 'tf-field-color-single',
					'label' => __( 'Quick View Background', 'instantio' ),
					'subtitle' => __( 'Instantio Quick View Panel Background Color', 'instantio' ),
					// 'is_pro'		=> true,
				),

				array(
					'id' => 'ins-quickview-color',
					'type' => 'color',
					'class' => 'tf-field-color-single',
					'label' => __( 'Quick View Color', 'instantio' ),
					'subtitle' => __( 'Instantio Quick View Panel Text & Cross Color', 'instantio' ),
					// 'is_pro'		=> true, 		
				),

				array(
					'id' => 'wi-custom-css',
					'type' => 'codeeditor',
					'label' => __( 'Custom CSS', 'instantio' ),
					'subtitle' => __( 'If you want to make extra CSS then you can do it from here', 'instantio' ),
					'settings' => array(
						'theme' => 'rubyblue',
						'mode' => 'css',
					),
				),
			),
		),

		/**
		 * Chcekout Editor
		 * Main menu
		 */

		'checkout_editors' => array(
			'title' => esc_html__( 'Checkout Editor', 'instantio' ),
			'icon' => 'fas fa-palette',
			'fields' => array(
				array(
					'id' => 'checkout_editors_fields',
					'type' => 'repeater',
					// 'class'    		=> 'disable-sortable',
					'drag_only' => true,
					'label' => __( 'Checkout Billing Form', 'instantio' ),
					'subtitle' => __( 'You can able to change form field positions by Drag.', 'instantio' ),
					'fields' => array(
						array(
							'id' => 'checkout_form_field_name',
							'type' => 'text',
							'label' => __( 'Field Name', 'instantio' ),
						),
						array(
							'id' => 'checkout_form_field_place',
							'type' => 'text',
							'label' => __( 'Field Placeholder', 'instantio' ),
						),

						array(
							'id' => 'checkout_form_field_origin',
							'type' => 'text',
							'class' => 'hidden',
							'label' => __( 'Field origin', 'instantio' ),
						),

						array(
							'id' => 'checkout_form_field_status',
							'class' => 'checkout_form_field_status',
							'type' => 'switch',
							'label' => __( 'Field Status', 'instantio' ),
							'subtitle' => __( 'You can able to enable/disable this field.', 'instantio' ),
						),

						array(
							'id' => 'required',
							'class' => 'checkout_form_field_required',
							'type' => 'switch',
							'label' => __( 'Required Status', 'instantio' ),
							'subtitle' => __( 'You can able to enable/disable this field required.', 'instantio' ),
						),
					),
					'default' => ! empty( $my_plugin_billing_fields ) ? $my_plugin_billing_fields : '',
				),

				array(
					'id' => 'custom_billing_field_add',
					'type' => 'notice',
					'is_pro' => true,
					'title' => __( 'Create new fields', 'instantio' ),
					'content' => __( 'Active Pro Plugin to use this features', 'instantio' ),
				),

				array(
					'id' => 'ins_reset_blliling_fields_button',
					'class' => 'checkout_reset_btn',
					'type' => 'callback',
					'function' => 'ins_reset_blliling_fields_button',
					'is_pro' => true,
				),

				array(
					'id' => 'checkout_shiping_editors_fields',
					'type' => 'repeater',
					// 'class'    		=> 'disable-sortable',
					'drag_only' => true,
					'label' => __( 'Checkout Shipping Form', 'instantio' ),
					'subtitle' => __( 'You can able to change form field positions by Drag.', 'instantio' ),
					'fields' => array(
						array(
							'id' => 'checkout_shipping_form_field_name',
							'type' => 'text',
							'label' => __( 'Field Name', 'instantio' ),
						),
						array(
							'id' => 'checkout_shipping_form_field_place',
							'type' => 'text',
							'label' => __( 'Field Placeholder', 'instantio' ),
						),
						array(
							'id' => 'checkout_shipping_form_field_origin',
							'type' => 'text',
							'class' => 'hidden',
							'label' => __( 'Field origin', 'instantio' ),
						),
						array(
							'id' => 'required_shipping',
							'type' => 'switch',
							'label' => __( 'Required Status', 'instantio' ),
							'subtitle' => __( 'You can able to enable/disable this field required.', 'instantio' ),
						),

						array(
							'id' => 'checkout_shipping_form_field_status',
							'type' => 'switch',
							'label' => __( 'Field Status', 'instantio' ),
							'subtitle' => __( 'You can able to enable/disable this field.', 'instantio' ),
						),
					),
					'default' => ! empty( $my_plugin_shipping_fields ) ? $my_plugin_shipping_fields : '',
				),

				array(
					'id' => 'custom_billing_field_add',
					'type' => 'notice',
					'is_pro' => true,
					'title' => __( 'Create new fields', 'instantio' ),
					'content' => __( 'Active Pro Plugin to use this features', 'instantio' ),
				),

				array(
					'id' => 'ins_reset_shipping_fields_button',
					'class' => 'checkout_reset_btn',
					'type' => 'callback',
					'function' => 'ins_reset_shipping_fields_button',
					'is_pro' => true,
				),

				array(
					'id' => 'order_note_editor',
					'type' => 'fieldset',
					'label' => __( 'Order Notes', 'instantio' ),
					'subtitle' => __( 'Edit Option For Order Notes Field', 'instantio' ),
					'is_pro' => true,
					'fields' => array(
						array(
							'id' => 'order_note_field_label',
							'type' => 'text',
							'class' => 'tf-field-inline',
							'field_width' => '50',
							'label' => __( 'Label', 'instantio' ),
							'placeholder' => __( 'Order notes', 'woocommerce' ),
							'default' => __( 'Order notes', 'woocommerce' ),
						),
						array(
							'id' => 'order_note_field_placeh',
							'type' => 'text',
							'class' => 'tf-field-inline',
							'field_width' => '50',
							'label' => __( 'Placeholder', 'instantio' ),
							'placeholder' => __( 'Notes about your order, e.g. special notes for delivery.', 'woocommerce' ),
							'default' => __( 'Notes about your order, e.g. special notes for delivery.', 'woocommerce' ),
						),
					),

				),
			),
		),

		/**
		 * Mobile
		 * Main menu
		 */
		'mobile' => array(
			'title' => __( 'Mobile', 'instantio' ),
			'icon' => 'fas fa-mobile-alt',
			'fields' => array(

				array(
					'id' => 'mobile',
					'type' => 'switch',
					'label' => __( 'Dedicated Mobile Version', 'instantio' ),
					'subtitle' => __( 'Enable/disable dedicated mobile version', 'instantio' ),
					'label_on' => __( 'Enabled', 'instantio' ),
					'label_off' => __( 'Disabled', 'instantio' ),
					'is_pro' => true,
					'default' => false,
				),

				array(
					'id' => 'cart-section_mobile',
					'type' => 'heading',
					'content' => __( 'Cart Section', 'instantio' ),
					'dependency' => array( 'mobile', '==', 'true' ),
				),

				array(
					'id' => 'mobile-cart-panel',
					'type' => 'switch',
					'label' => __( 'Enable Cart Panel', 'instantio' ),
					'subtitle' => __( 'Enable/disable Bottom cart in mobile version', 'instantio' ),
					'label_on' => __( 'Yes', 'instantio' ),
					'label_off' => __( 'No', 'instantio' ),
					'default' => false,
					'dependency' => array( 'mobile', '==', 'true' ),
				),

			),
		),


		'optimization' => array(
			'title' => __( 'Optimization', 'instantio' ),
			'icon' => 'fas fa-bolt',
			'fields' => array(

				// array(
				// 	'id'       		=> 'css-min',
				// 	'type'     		=> 'switch',
				// 	'is_pro'    	=> true,
				// 	'label'    		=> __('Minify CSS', 'instantio'),
				// 	'subtitle' 		=> __('Enable/disable Instantio CSS minification', 'instantio'),
				// 	'label_on'    	=> __('Enabled', 'instantio' ),
				// 	'label_off'   	=> __('Disabled', 'instantio' ),
				// 	'width' 		=> 100,
				// 	'default'   	=> false,           
				// ),

				array(
					'id' => 'js-min',
					'type' => 'switch',
					'is_pro' => true,
					'label' => __( 'Minify JS', 'instantio' ),
					'subtitle' => __( 'Enable/disable Instantio JS minification', 'instantio' ),
					'label_on' => __( 'Enabled', 'instantio' ),
					'label_off' => __( 'Disabled', 'instantio' ),
					'default' => false,
				),
			),
		),
	),
) );

