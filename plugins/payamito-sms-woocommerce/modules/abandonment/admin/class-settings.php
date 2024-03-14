<?php

namespace Payamito\Woocommerce\Modules\Abandoned\Admin;
use  Payamito\Woocommerce\Modules\Abandoned\Helper;

if ( ! class_exists( "Settings" ) ) {
	class Settings
	{

		public function add_settings()
		{
			if ( ( isset( $_POST['action'] ) && $_POST['action'] == "kianfr_payamito_ajax_save" ) || ( isset( $_GET['page'] ) && $_GET['page'] == "payamito" ) ) {
				add_filter( "payamito_wp_settings", [ $this, 'settings' ] );
				add_filter( 'kianfr_' . 'payamito' . '_save', [ $this, 'add_id' ], 99, 1 );

				add_action( 'kianfr_' . 'payamito' . '_save_before', [ $this, 'option_save' ], 10, 1 );
				add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );
				$this->get_products();
			}
		}

		public function add_id( $options )
		{
			$abandonment_settings = $options['payamito_woocommerce']['abandonment_template'];
			if ( is_array( $abandonment_settings ) ) {
				foreach ( $abandonment_settings as $x => $option ) {
					if ( empty( $option['template_id'] ) ) {
						$abandonment_settings[ $x ]['template_id'] = (string) wp_rand( 1000000, 10000000 );
					}
					if ( $option['pattern_active'] == '1' ) {
						if ( isset( $option['abandonment_pattern'] ) ) {
							foreach ( $option['abandonment_pattern'] as $y => $pattern ) {
								if ( empty( $pattern['id'] ) ) {
									$abandonment_settings[ $x ]['abandonment_pattern'][ $y ]['id'] = (string) wp_rand( 1000000, 10000000 );
								}
							}
						}
					}
					if ( $option['pattern_active'] == '0' ) {
						if ( isset( $option['messege'] ) ) {
							foreach ( $option['messege'] as $y => $pattern ) {
								if ( empty( $pattern['id'] ) ) {
									$abandonment_settings[ $x ]['messege'][ $y ]['id'] = (string) wp_rand( 1000000, 10000000 );
								}
							}
						}
					}
				}
			}

			$options['payamito_woocommerce']['abandonment_template'] = $abandonment_settings;

			return $options;
		}

		public function option_save( $options )
		{
			$save    = [];
			$options = $options['payamito_woocommerce'];
			( isset( $options['abandonment_active'] ) && $options['abandonment_active'] ) == '1' ? $save['active'] = true : $save['active'] = false;
			if ( isset( $options['abandonment_template'] ) && is_array( $options['abandonment_template'] ) ) {
				$coupon_data = [];

				foreach ( $options['abandonment_template'] as $key => $option ) {
					#abandonment
					$save[ $key ]['guest_user'] = $option['guest_user'];

					$save[ $key ]['field_id'] = $option['field_id'];

					$save[ $key ]['template_id'] = $option['template_id'];

					$save[ $key ]['meta_key'] = $option['meta_key'];

					$save[ $key ]['pattern_active'] = $option['pattern_active'];
					$save[ $key ]['patterns']       = isset( $option['abandonment_pattern'] ) ? $option['abandonment_pattern'] : [];
					$save[ $key ]['messeges']       = isset( $option['messege'] ) ? $option['messege'] : [];

					$coupon_data['active'] = $option['coupon_active'];
					$coupon_data['type']   = $option['coupon_type'];

					$coupon_data['amount'] = $option['coupon_amount'];

					$coupon_data['minimum_amount'] = $option['coupon_minimum_amount'];

					$coupon_data['maximum_amount'] = $option['coupon_maximum_amount'];

					$coupon_data['products'] = isset( $option['coupon_products'] ) ? $option['coupon_products'] : [];

					$coupon_data['exclude_products'] = isset( $option['coupon_exclude_products'] ) ? $option['coupon_exclude_products'] : [];

					$coupon_data['product_categories'] = isset( $option['coupon_product_categories'] ) ? $option['coupon_product_categories'] : [];

					$coupon_data['product_exclude_categories'] = isset( $option['coupon_product_exclude_categories'] ) ? $option['coupon_product_exclude_categories'] : [];

					$coupon_data['expire']         = $option['coupon_expire'];
					$coupon_data['expire']['unit'] = Helper::set_units( $coupon_data['expire']['unit'] );

					$coupon_data['only_active'] = $option['coupon_only_active'];

					$coupon_data['usage_limit'] = $option['coupon_usage_limit'];

					$coupon_data['limit_usage_to_x_items'] = $option['coupon_limit_usage_to_x_items'];

					$coupon_data['usage_limit_per_user'] = $option['coupon_usage_limit_per_user'];

					$save[ $key ]['coupon_data'] = $coupon_data;
				}
			}

			update_option( "payamito_wc_abandonment", $save );
		}

		public function admin_enqueue_scripts()
		{
			wp_enqueue_style( 'payamito-wc-abandonment-admin-style', PAYAMITO_WC_Module_URL . '/abandonment/admin/assets/css/admin-style.css' );
		}

		public function settings( $settings )
		{
			$products        = $this->get_products();
			$taxonomies      = $this->get_categories();
			$module_settings = [
				'id'     => 'abandonment',
				'title'  => esc_html__( 'Abandonment cart (Super professional)', 'payamito-woocommerce' ),
				'fields' => [
					[
						'id'    => 'abandonment_active',
						'type'  => 'switcher',
						'title' => esc_html__( 'Active Abandonment cart', 'payamito-woocommerce' ),
						'desc'  => esc_html__( 'By activating this option, you can send SMS to customers who have reached the order payment stage but have not paid and left the site.', 'payamito-woocommerce' ),
					],
					[
						'id'           => 'abandonment_template',
						'type'         => 'repeater',
						'dependency'   => [ "abandonment_active", '==', 'true' ],
						'button_title' => esc_html__( 'Creat SMS Template', 'payamito-woocommerce' ),
						'title'        => esc_html__( 'SMS Template', 'payamito-woocommerce' ),
						'fields'       => [

							[
								'id'    => 'guest_user',
								'type'  => 'switcher',
								'title' => esc_html__( 'Enable sending SMS to guest user', 'payamito-woocommerce' ),
								'desc'  => esc_html__( 'Enabling this option will send sms to users who are not yet members of your site. Note: Be careful in choosing this option, because it is possible to abuse this method and also increase the number of sms you send.', 'payamito-woocommerce' ),
								'help'  => esc_html__( 'Note: Be careful in choosing this option, because it is possible to abuse this method and also increase the number of sms you send.', 'payamito-woocommerce' ),
							],
							[
								'id'    => 'template_id',
								'type'  => 'number',
								'class' => 'display-none',
							],
							[
								'id'         => 'field_id',
								'type'       => 'text',
								'title'      => esc_html__( 'Field id', 'payamito-woocommerce' ),
								'dependency' => [ "guest_user", '==', 'true' ],
							],
							[
								'id'         => 'meta_key',
								'type'       => 'select',
								'chosen'     => true,
								'settings'   => [ 'width' => "100%", ],
								'dependency' => [ "guest_user", '!=', 'true' ],
								'options'    => Helper::meta_keys(),
							],

							[
								'id'    => 'pattern_active',
								'type'  => 'switcher',
								'title' => esc_html__( 'Pattern', 'payamito-woocommerce' ),
							],

							[
								'id'           => 'abandonment_pattern',
								'type'         => 'repeater',
								'dependency'   => [ "pattern_active", '==', 'true' ],
								'button_title' => esc_html__( 'Creat Pattern', 'payamito-woocommerce' ),
								'fields'       => [
									[
										'id'    => 'id',
										'type'  => 'number',
										'class' => 'display-none',
									],
									[
										'id'           => 'abandonment_pattern_variable',
										'type'         => 'repeater',
										'button_title' => esc_html__( 'Add variable', 'payamito-woocommerce' ),
										'fields'       => [

											[
												'id'          => 0,
												'type'        => 'select',
												'attributes'  => [
													'style' => 'width:auto  !important',
												],
												'placeholder' => esc_html__( "Select tag", "payamito-woocommerce" ),
												'options'     => Helper::tags( true ),
											],
											[
												'id'          => 1,
												'type'        => 'number',
												'attributes'  => [
													'style' => 'width:50px !important',
												],
												'placeholder' => esc_html__( "Your tag", "payamito-woocommerce" ),
												'default'     => '0',
											],

										],
									],
									[
										'id'          => 'frequency',
										'type'        => 'dimensions',
										'title'       => esc_html__( 'Frequency', 'payamito-woocommerce' ),
										'height'      => false,
										'width'       => "1",
										'placeholder' => esc_html__( 'value', 'payamito-woocommerce' ),
										'min'         => '30',
										'width_icon'  => "",
										'units'       => Helper::units(),
									],
									[
										'id'    => 'pattern_id',
										'type'  => 'number',
										'title' => esc_html__( 'Pattern id', 'payamito-woocommerce' ),
									],
								],
							],
							[
								'id'           => 'messege',
								'type'         => 'repeater',
								'title'        => esc_html__( 'Message', 'payamito-woocommerce' ),
								'button_title' => esc_html__( 'Add message', 'payamito-woocommerce' ),
								'dependency'   => [ "pattern_active", '!=', 'true' ],
								'fields'       => [
									[
										'id'    => 'id',
										'type'  => 'number',
										'class' => 'display-none',
									],
									[
										'id'   => 'text',
										'type' => 'textarea',
									],
									[
										'id'          => 'frequency',
										'type'        => 'dimensions',
										'height'      => false,
										'width'       => "1",
										'placeholder' => esc_html__( 'value', 'payamito-woocommerce' ),
										'width_icon'  => "",
										'units'       => Helper::units(),
										'default'     => '20',
									],
								],
							],

							[
								'type'    => 'subheading',
								'content' => esc_html__( 'Coupon', 'payamito-woocommerce' ),
							],
							[
								'id'    => 'coupon_active',
								'type'  => 'switcher',
								'title' => esc_html__( 'Activation of sending discount coupons', 'payamito-woocommerce' ),
								'desc'  => esc_html__( 'By activating this option, you can specify that when sending a text message, a discount code will be created and a text message will be sent to the user.', 'payamito-woocommerce' ),
							],
							[
								'id'         => 'coupon_type',
								'type'       => 'select',
								'dependency' => [ "coupon_active", '==', 'true' ],
								'options'    => [
									'percent'    => esc_html__( "Percent", "payamito-woocommerce" ),
									'fixed_cart' => esc_html__( "Fixed", "payamito-woocommerce" ),
								],
							],
							[
								'id'         => 'coupon_amount',
								'type'       => 'number',
								'dependency' => [ "coupon_active", '==', 'true' ],
								'title'      => esc_html__( 'Coupon amount', 'payamito-woocommerce' ),
								'desc'       => esc_html__( 'If the discount is a percentage, for example, enter 20 and if it is fixed, enter 20,000 (when the discount is fixed, pay an amount depending on the type of Rials or Tomans)', 'payamito-woocommerce' ),
							],
							[
								'id'          => 'coupon_minimum_amount',
								'type'        => 'number',
								'placeholder' => esc_html__( 'No minimum', 'payamito-woocommerce' ),
								'dependency'  => [ "coupon_active", '==', 'true' ],
								'title'       => esc_html__( 'Minimum purchase amount', 'payamito-woocommerce' ),
							],
							[
								'id'          => 'coupon_maximum_amount',
								'type'        => 'number',
								'placeholder' => esc_html__( 'No maximum', 'payamito-woocommerce' ),
								'dependency'  => [ "coupon_active", '==', 'true' ],
								'title'       => esc_html__( 'Maximum purchase amount', 'payamito-woocommerce' ),
							],
							[
								'id'         => 'coupon_products',
								'type'       => 'select',
								'chosen'     => true,
								'multiple'   => true,
								'settings'   => [ 'typing_text' => esc_html__( 'Please enter 3 or more characters', 'payamito-woocommerce' ) ],
								'title'      => esc_html__( 'Products include discounts', 'payamito-woocommerce' ),
								'desc'       => esc_html__( 'Specify which products will be included in the discount', 'payamito-woocommerce' ),
								'dependency' => [ "coupon_active", '==', 'true' ],
								'options'    => $products,
							],
							[
								'id'         => 'coupon_exclude_products',
								'type'       => 'select',
								'chosen'     => true,
								'multiple'   => true,
								'settings'   => [
									'width'       => "100%",
									'typing_text' => esc_html__( 'Please enter 3 or more characters', 'payamito-woocommerce' ),
								],
								'title'      => esc_html__( 'Discounts do not include these products', 'payamito-woocommerce' ),
								'desc'       => esc_html__( 'In this section, specify products that do not include discounts', 'payamito-woocommerce' ),
								'dependency' => [ "coupon_active", '==', 'true' ],
								'options'    => $products,
							],
							[
								'id'         => 'coupon_product_categories',
								'type'       => 'select',
								'chosen'     => true,
								'multiple'   => true,
								'settings'   => [
									'width'       => "100%",
									'typing_text' => esc_html__( 'Please enter 3 or more characters', 'payamito-woocommerce' ),
								],
								'title'      => esc_html__( 'Product categories', 'payamito-woocommerce' ),
								'desc'       => esc_html__( 'Specify which product categories your discounts will include', 'payamito-woocommerce' ),
								'dependency' => [ "coupon_active", '==', 'true' ],
								'options'    => $taxonomies,
							],
							[
								'id'         => 'coupon_product_exclude_categories',
								'type'       => 'select',
								'chosen'     => true,
								'multiple'   => true,
								'settings'   => [
									'width'       => "100%",
									'typing_text' => esc_html__( 'Please enter 3 or more characters', 'payamito-woocommerce' ),
								],
								'title'      => esc_html__( 'Do not include these categories', 'payamito-woocommerce' ),
								'desc'       => esc_html__( 'In this section, select the category of products that do not include discounts', 'payamito-woocommerce' ),
								'dependency' => [ "coupon_active", '==', 'true' ],
								'options'    => $taxonomies,
							],
							[
								'id'          => 'coupon_expire',
								'type'        => 'dimensions',
								'placeholder' => esc_html__( 'value', 'payamito-woocommerce' ),
								'dependency'  => [ "coupon_active", '==', 'true' ],
								'title'       => esc_html__( 'Coupon expire', 'payamito-woocommerce' ),
								'height'      => false,
								'width'       => "1",
								'width_icon'  => "",
								'units'       => Helper::units(),
							],
							[
								'id'         => 'coupon_only_active',
								'type'       => 'switcher',
								'dependency' => [ "coupon_active", '==', 'true' ],
								'title'      => esc_html__( 'Individual use only', 'payamito-woocommerce' ),
								'desc'       => esc_html__( 'By activating this option, the user will not be able to use two discount codes at the same time.', 'payamito-woocommerce' ),
							],
							[
								'id'          => 'coupon_usage_limit',
								'type'        => 'number',
								'default'     => '1',
								'dependency'  => [ "coupon_active", '==', 'true' ],
								'title'       => esc_html__( 'Usage limit per coupon', 'payamito-woocommerce' ),
								'desc'        => esc_html__( 'How many times can use this discount code?', 'payamito-woocommerce' ),
								'placeholder' => esc_html__( 'Unlimited usage', 'payamito-woocommerce' ),
							],
							[
								'id'          => 'coupon_limit_usage_to_x_items',
								'type'        => 'number',
								'default'     => '1',
								'dependency'  => [ "coupon_active", '==', 'true' ],
								'title'       => esc_html__( 'Limit usage to X items', 'payamito-woocommerce' ),
								'desc'        => esc_html__( 'Specify whether this discount code can only be used on one product or several products', 'payamito-woocommerce' ),
								'placeholder' => esc_html__( 'Apply to all qualifying items in cart', 'payamito-woocommerce' ),
							],
							[
								'id'          => 'coupon_usage_limit_per_user',
								'type'        => 'number',
								'default'     => '1',
								'dependency'  => [ "coupon_active", '==', 'true' ],
								'title'       => esc_html__( 'Usage limit per user', 'payamito-woocommerce' ),
								'desc'        => esc_html__( 'Specify that the possibility of using this discount code includes only one user or several users?', 'payamito-woocommerce' ),
								'placeholder' => esc_html__( 'Unlimited usage', 'payamito-woocommerce' ),
							],
						],
					],

				],
			];
			array_push( $settings['fields'][0]['tabs'], $module_settings );

			return $settings;
		}

		public function get_products()
		{
			if(!class_exists(Helper::class) ){
				require_once PAYAMITO_WC_Module_DIR . '/abandonment/class-helper.php';
			}
			$products = Helper::get_products();
			if ( ! $products ) {
				return false;
			}
			$select_options = [];
			foreach ( $products as $product ) {
				$select_options[ $product->ID ] = $product->post_title;
			}

			return $select_options;
		}

		public function get_categories()
		{
			$categories     = Helper::get_categories();
			$select_options = [];

			if ( is_array( $categories ) ) {
				foreach ( $categories as $category ) {
					$select_options[ $category->term_id ] = $category->name;
				}

				return $select_options;
			} else {
				return [];
			}
		}

		public function option_set_pattern()
		{
			return [
				'id'           => 'pattern',
				'type'         => 'repeater',
				'button_title' => esc_html__( 'Creat Pattern', 'payamito-woocommerce' ),
				'title'        => esc_html__( "Pattern", "payamito-woocommerce" ),
				'max'          => '20',
				'class'        => "payamito-woocommerce-repeater",
				'fields'       => [

					[
						'id'          => 0,
						'type'        => 'select',
						'placeholder' => esc_html__( "Select tag", "payamito-woocommerce" ),
						'options'     => Helper::tags( true ),
					],
					[
						'id'          => 1,
						'type'        => 'number',
						'placeholder' => esc_html__( "Your tag", "payamito-woocommerce" ),
						'default'     => '0',
					],
				],
			];
		}
	}
}