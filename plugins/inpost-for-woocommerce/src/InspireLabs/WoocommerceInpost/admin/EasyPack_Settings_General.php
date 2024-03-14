<?php

namespace InspireLabs\WoocommerceInpost\admin;

use InspireLabs\WoocommerceInpost\EasyPack;
use Exception;
use InspireLabs\WoocommerceInpost\EasyPack_API;
use InspireLabs\WoocommerceInpost\shipx\models\courier_pickup\ShipX_Dispatch_Order_Point_Model;
use WC_Admin_Settings;
use WC_Settings_Page;

/**
 * EasyPack General Settings
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'EasyPack_Settings_General' ) ) :

	/**
	 * EasyPack_Settings_General
	 */
	class EasyPack_Settings_General extends WC_Settings_Page {

		static $prevent_duplicate = [];

		/**
		 * Constructor.
		 */
		public function __construct() {

			$this->id    = 'easypack_general';
			$this->label = __( 'InPost', 'woocommerce-inpost' );
            parent::__construct();
			add_filter( 'woocommerce_settings_tabs_array', [ $this, 'add_settings_page' ], 20 );
			add_action( 'woocommerce_settings_' . $this->id, [ $this, 'output' ] );
			add_action( 'woocommerce_settings_save_' . $this->id, [ $this, 'save' ] );

		}


		/**
		 * Output the settings
		 */
		public function output() {
			$easypack_api_change = get_option( 'easypack_api_error_login', 0 );
			?>
            <input id="easypack_api_change" type="hidden"
                   name="easypack_api_change"
                   value="<?php echo esc_attr( $easypack_api_change ); ?>">
            <style>
                .form-table {
                    border-bottom: 1px solid #ccc;
                }
            </style>
			<?php
			$settings = $this->get_settings();
			WC_Admin_Settings::output_fields( $settings );
		}


		/**
		 * @param ShipX_Dispatch_Order_Point_Model[] $points
		 *
		 * @return array
		 */
		public function getDispathPointsOptions( $points ) {
			$return = [];

			foreach ( $points as $point ) {
				$return[ $point->getId() ] = $point->getName();
			}

			return $return;
		}

		/**
		 * Get settings array
		 *
		 * @return array
		 * @throws Exception
		 */
		public function get_settings() {
			$send_methods = [
				'parcel_machine' => __( 'Parcel Locker', 'woocommerce-inpost' ),
				'courier'        => __( 'Courier', 'woocommerce-inpost' ),
			];
			
			$default_locker_attributes = [];
            $default_locker_placeholder = '';
			if( ! empty( get_option('easypack_geowidget_production_token') )
                || ! empty( get_option('easypack_geowidget_sandbox_token') ) )
			{
                $default_locker_attributes = [
                    'data-geowidget_config' => 'parcelSend'
                ];
                $default_locker_placeholder = __( 'Click to choose the default locker point on the map', 'woocommerce-inpost' );

            } else {

                $default_locker_attributes = [
                    'disabled' => ''
                ];
                $default_locker_placeholder = __( 'This field must be filled in after the Geowiget token has been saved', 'woocommerce-inpost' );
            }


			$settings = [
			        /*
                [
                    'title' => __( 'Methods', 'woocommerce-inpost' ),
                    'type'  => 'title',
                    'desc'  => $output,
                    'id'    => 'methods_options',
                ],
			        */

                [
                    'title' => __( 'Help', 'woocommerce-inpost' ),
                    'type'  => 'title',
                    'desc'  => __( 'Documentation InPost:  ', 'woocommerce-inpost' )
                        . '<a href="https://dokumentacja-inpost.atlassian.net/wiki/spaces/PL/pages/61833233/WooCommerce" target="_blank">'
                        . __( 'How to set up plugin', 'woocommerce-inpost' ) . '</a><br></br>'
                        . '<b>' . __( 'In case of questions/problems related to the plugin, please contact us via the ', 'woocommerce-inpost' ) . '</b>'
                        . '<a href="https://inpost.pl/formularz-wsparcie" target="_blank">'
                        . __( 'InPost form', 'woocommerce-inpost' ) . '</a>',
                    'id'    => 'help_options',
                ],

				[
					'title' => __( 'Country', 'woocommerce-inpost' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'country_options',
				],

				[
					'title'    => __( 'Country', 'woocommerce-inpost' ),
					'id'       => 'easypack_api_country',
					'default'  => '--',
					'type'     => 'select',
					'css'      => 'min-width: 300px;',
					'desc_tip' => null,
					'options'  => [
						'--' => __( 'Select country', 'woocommerce-inpost' ),
						'gb' => __( 'UK', 'woocommerce-inpost' ),
						'pl' => __( 'Poland', 'woocommerce-inpost' ),
					],
				],

				[ 'type' => 'sectionend', 'id' => 'country_options' ],

				[
					'title' => __( 'Logging in', 'woocommerce-inpost' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'general_options',
				],

				[
					'title'             => __( 'API URL', 'woocommerce-inpost' ),
					'id'                => 'easypack_api_url',
					'css'               => 'min-width:300px;',
					'default'           => 'https://sandbox-api-shipx-pl.easypack24.net',
					'type'              => 'text',
					'desc_tip'          => false,
					'class'             => 'easypack-api-url',
					'custom_attributes' => [ 'required' => 'required' ],
				],
				[
					'title'             => __( 'Organization ID', 'woocommerce-inpost' ),
					'id'                => 'easypack_organization_id',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[
					'title'             => __( 'Token', 'woocommerce-inpost' ),
					'id'                => 'easypack_token',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[
					'title'    => __( 'Geowidget production token', 'woocommerce-inpost' ),
					'id'       => 'easypack_geowidget_production_token',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => false,
				],
				[
					'title'    => __( 'Geowidget sandbox token', 'woocommerce-inpost' ),
					'id'       => 'easypack_geowidget_sandbox_token',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => false,
				],

				[
					'title'   => __( 'API type', 'woocommerce-inpost' ),
					'id'      => 'easypack_api_environment',
					'default' => 'production',
					'type'    => 'select',
					'css'     => 'min-width: 300px;',
					'options' => [
						'production' => __( 'Production', 'woocommerce-inpost' ),
						'sandbox'    => __( 'Sandbox', 'woocommerce-inpost' ),
					],
				],

				[
					'title'    => __( 'Szybkie zwroty - link', 'woocommerce-inpost' ),
					'id'       => 'easypack_fast_return',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => false,
					'class'    => 'easypack-api-url',
				],

				[ 'type' => 'sectionend', 'id' => 'general_options' ],

				[
					'title' => __( 'Insurance', 'woocommerce-inpost' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'insurance_options',
				],

				[
					'title'             => __( 'Default insurance amount', 'woocommerce-inpost' ),
					'id'                => 'easypack_insurance_amount_default',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => false ],
				],


				[ 'type' => 'sectionend', 'id' => 'general_options' ],


				[
					'title' => __( 'Send options', 'woocommerce-inpost' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'send_options',
				],

				[
					'title'    => __( 'Default package size', 'woocommerce-inpost' ),
					'id'       => 'easypack_default_package_size',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width: 300px;',
					'desc_tip' => false,
					'default'  => 'small',
					'options'  => EasyPack()->get_package_sizes(),
				],

                [
                    'title'    => __( 'Default package size Courier C2C', 'woocommerce-inpost' ),
                    'id'       => 'easypack_default_package_size_c2c',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => 'small',
                    'options'  => EasyPack()->get_package_sizes_xlarge()

                ],

				[
					'title'    => __( 'Default send method', 'woocommerce-inpost' ),
					'id'       => 'easypack_default_send_method',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width: 300px;',
					'desc_tip' => false,
					'default'  => 'parcel_machine',
					'options'  => $send_methods,
				],

                [
                    'title'    => __( 'Set default dimensions for courier shipments',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_set_default_courier_dimensions',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => '',
                    'default'  => '',
                    'options'  => []
                ],

                [
                    'title'             => __( 'Box length (mm)', 'woocommerce-inpost' ),
                    'id'                => 'easypack_default_courier_dimensions[length]',
                    'css'               => 'min-width:300px;',
                    'default'           => '',
                    'type'              => 'text',
                    'desc_tip'          => false,
                    /*'custom_attributes' => [ 'required' => false ],*/
                    'class'             => 'easypack_hidden_setting'
                ],

                [
                    'title'             => __( 'Box width (mm)', 'woocommerce-inpost' ),
                    'id'                => 'easypack_default_courier_dimensions[width]',
                    'css'               => 'min-width:300px;',
                    'default'           => '',
                    'type'              => 'text',
                    'desc_tip'          => false,
                    /*'custom_attributes' => [ 'required' => false ],*/
                    'class'             => 'easypack_hidden_setting'
                ],

                [
                    'title'             => __( 'Box height (mm)', 'woocommerce-inpost' ),
                    'id'                => 'easypack_default_courier_dimensions[height]',
                    'css'               => 'min-width:300px;',
                    'default'           => '',
                    'type'              => 'text',
                    'desc_tip'          => false,
                    /*'custom_attributes' => [ 'required' => false ],*/
                    'class'             => 'easypack_hidden_setting'
                ],

                [
                    'title'             => __( 'Box weight (kg)', 'woocommerce-inpost' ),
                    'id'                => 'easypack_default_courier_dimensions[weight]',
                    'css'               => 'min-width:300px;',
                    'default'           => '',
                    'type'              => 'text',
                    'desc_tip'          => false,
                    /*'custom_attributes' => [ 'required' => false ],*/
                    'class'             => 'easypack_hidden_setting'
                ],

                [
                    'title'    => __( 'Box is non standard?', 'woocommerce-inpost' ),
                    'id'       => 'easypack_default_courier_dimensions[non_standard]',
                    'type'     => 'select',
                    'class'    => 'wc-enhanced-select',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => 'no',
                    'options'  => [
                        'yes'  => __( 'Yes', 'woocommerce-inpost' ),
                        'no'   => __( 'No', 'woocommerce-inpost' ),
                    ],
                    'class'             => 'easypack_hidden_setting'
                ],

				[
					'title'    => __( 'Parcel locker label format', 'woocommerce-inpost' ),
					'id'       => 'easypack_label_format',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width: 300px;',
					'desc_tip' => false,
					'default'  => 'A6',
					'options'  => [
						'A6' => 'A6',
						'A4' => 'A4',
					],
				],

				[
					'title'    => __( 'Default send parcel locker', 'woocommerce-inpost' ),
					'id'       => 'easypack_default_machine_id',
					'type'     => 'text',
					'class'    => 'settings-geowidget',
					'css'      => 'min-width: 300px;',
					'desc_tip' => false,
					'default'  => '',
					'options'  => [],
					'placeholder' => $default_locker_placeholder,
					'custom_attributes' => $default_locker_attributes
				],

                [
                    'title'    => __( 'Enable InPost methods for all products in your shop',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_enable_for_all_products',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => __( 'Enable this setting if you want to enable all configured Inpost shipping methods for each product in your store.',
                        'woocommerce-inpost' )
                        . ' ' . __( 'This setting ignores the individual settings in the product settings in the Inpost tab.', 'woocommerce-inpost' ),
                    'default'  => '',
                    'options'  => []

                ],

                [
                    'title'    => __( 'Do not provide InPost parcel locker service if the weight of goods exceeds 25 kg',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_over_weight',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => '',
                    'options'  => []

                ],

                [
                    'title'    => __( 'Send an email to the buyer about the start of the delivery process just after parcel created?',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_delivery_notice',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => '',
                    'options'  => []

                ],

                [
                    'title'    => __( 'Change order status to Completed after shipment created?',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_set_order_completed',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => '',
                    'options'  => []

                ],                

                // Custom CSS form map button start
                [
                    'name'    => __( 'Color of map button', 'woocommerce-inpost' ),
                    'type'    => 'text',
                    'desc'    => '',
                    'default' => '#FCC905',
                    'id'      => 'easypack_custom_button_css'
                ],
                // Custom CSS form map button end

				[ 'type' => 'sectionend', 'id' => 'send_options' ],

				[
					'title' => __( 'Dispatch point', 'woocommerce-inpost' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'dispatch_point_options',
				],


				$this->output_dispath_point_module(),
				[
					'type' => 'hidden',
					'id'   => EasyPack::ATTRIBUTE_PREFIX . '_dpoint_selected',
				],

				[ 'type' => 'sectionend', 'id' => 'dispatch_point_options' ],


				[
					'title' => __( 'Sender', 'woocommerce-inpost' ),
					'type'  => 'title',
					'desc'  => '',
					'id'    => 'sender_options',
				],

				[
					'title'             => __( 'First Name', 'woocommerce-inpost' ),
					'id'                => 'easypack_sender_first_name',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[
					'title'             => __( 'Last Name', 'woocommerce-inpost' ),
					'id'                => 'easypack_sender_last_name',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[
					'title'    => __( 'Company Name', 'woocommerce-inpost' ),
					'id'       => 'easypack_sender_company_name',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => false,
				],

				[
					'title'    => __( 'Street', 'woocommerce-inpost' ),
					'id'       => 'easypack_sender_street',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => false,
				],

				[
					'title'    => __( 'Building no', 'woocommerce-inpost' ),
					'id'       => 'easypack_sender_building_no',
					'css'      => 'min-width:300px;',
					'default'  => '',
					'type'     => 'text',
					'desc_tip' => false,
				],

				[
					'title'   => __( 'Post code', 'woocommerce-inpost' ),
					'id'      => 'easypack_sender_post_code',
					'css'     => 'min-width:300px;',
					'default' => '',
					'type'    => 'text',
				],

                [
                    'title'    => __( 'Address 1', 'woocommerce-inpost' ),
                    'id'       => 'easypack_sender_flat_no',
                    'css'      => 'min-width:300px;',
                    'default'  => '',
                    'type'     => 'text',
                    'desc_tip' => false,
                ],

                [
                    'title'    => __( 'Address 2', 'woocommerce-inpost' ),
                    'id'       => 'easypack_sender_address2',
                    'css'      => 'min-width:300px;',
                    'default'  => '',
                    'type'     => 'text',
                    'desc_tip' => false,
                ],

				[
					'title'             => __( 'City', 'woocommerce-inpost' ),
					'id'                => 'easypack_sender_city',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[
					'title'             => __( 'Email', 'woocommerce-inpost' ),
					'id'                => 'easypack_sender_email',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'email',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[
					'title'             => __( 'Phone', 'woocommerce-inpost' ),
					'id'                => 'easypack_sender_phone',
					'css'               => 'min-width:300px;',
					'default'           => '',
					'type'              => 'text',
					'desc_tip'          => false,
					'custom_attributes' => [ 'required' => 'required' ],
				],

				[ 'type' => 'sectionend', 'id' => 'sender_options' ],

                [
                    'title' => __( 'Debug mode', 'woocommerce-inpost' ),
                    'type'  => 'title',
                    'desc'  => '',
                    'id'    => 'debug_options',
                ],
								

                [
                    'title'    => __( 'Inpost shipping methods are only visible to administrators',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_debug_mode',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => '',
                    'options'  => []

                ],
				
				[
					'title'    => __( 'Map button output', 'woocommerce-inpost' ),
					'id'       => 'easypack_button_output',
					'type'     => 'select',
					'class'    => 'wc-enhanced-select',
					'css'      => 'min-width: 300px;',
					'default'  => 'woocommerce_review_order_after_shipping',
					'options'  => [
						'woocommerce_review_order_before_submit'     => __('Before the \'Place order\' button', 'woocommerce-inpost' ),
						'woocommerce_after_order_notes'              => __('After the \'Add note\' field', 'woocommerce-inpost' ),
						'woocommerce_before_order_notes'             => __('Before the \'Add note\' field', 'woocommerce-inpost' ),
						'woocommerce_review_order_before_payment'    => __('Before information about payment methods', 'woocommerce-inpost' ),
						'woocommerce_review_order_before_order_total' => __('Before information about the total amount of the order', 'woocommerce-inpost' ),
						'woocommerce_review_order_after_order_total'  => __('After information about the total amount of the order', 'woocommerce-inpost' ),
						'woocommerce_review_order_after_shipping'     => __('After describing the delivery methods', 'woocommerce-inpost' ),
						'woocommerce_review_order_before_shipping'    => __('Before describing the delivery methods', 'woocommerce-inpost' ),
						'woocommerce_after_checkout_shipping_form'    => __('After form with shipping address', 'woocommerce-inpost' ),
						'woocommerce_checkout_after_customer_details' => __('After customer details', 'woocommerce-inpost' ),
						'woocommerce_checkout_before_order_review'    => __('Before order details review', 'woocommerce-inpost' ),
						'woocommerce_review_order_after_submit'       => __('After the \'Place order\' button', 'woocommerce-inpost' ),
						'woocommerce_before_checkout_form'            => __('Before form with checkout details', 'woocommerce-inpost' )
					],
				],
				
				[
                    'title'    => __( 'Enable JS mode of the map button - if the \'Select Parcel Locker\' button does not appear in your checkout',
                        'woocommerce-inpost' ),
                    'id'       => 'easypack_js_map_button',
                    'type'     => 'checkbox',
                    'class'    => '',
                    'css'      => 'min-width: 300px;',
                    'desc_tip' => false,
                    'default'  => '',
                    'options'  => []

                ],
				
				[
                    'title' => __( '', 'woocommerce-inpost' ),
                    'type'  => 'title',
                    'desc'  => '<b>' . __( 'The settings associated with the map button do not work if you are using Checkout based on Blocks.', 'woocommerce-inpost' ) . '</b>'
                        . '<br></br>'
                        .  __( 'Please keep in mind that different templates may have a non-standard appearance ', 'woocommerce-inpost' )
                        .  __( 'design, and do not always contain in the code the entire list of standard hooks ', 'woocommerce-inpost' )
                        .  __( 'that are used to operate plugins - in particular, to display the map button.', 'woocommerce-inpost' )
                        . '<br></br>'
                        . __( 'By default, the button should be visible immediately after the block with a choice of delivery methods.', 'woocommerce-inpost' )
                        . '<br>'
                        . __( 'You can try changing where you want the map button to appear using a setting.', 'woocommerce-inpost' )
                        . '<br>'
                        . __( 'If the button is not visible after activating the plugin, it is recommended to start with option \'Before the Place order\' button.', 'woocommerce-inpost' ),
                    'id'    => 'easypack_button_output_help_desc',
                ],

                [ 'type' => 'sectionend', 'id' => 'debug_options' ],
			];


			return $settings;
		}

		/**
		 * @param string|int $key
		 * @param array $array
		 *
		 * @return mixed
		 */
		private function get_from_array( $key, $array ) {
			return isset( $array[ $key ] ) ? $array[ $key ] : null;
		}

		/**
		 * Save settings
		 *
		 * @throws Exception
		 */
		public function save() {
			$settings = $this->get_settings();

			WC_Admin_Settings::save_fields( $settings );
			EasyPack_API()->clear_cache();

            if( ! empty( $_REQUEST['easypack_organization_id'] ) && ! empty( $_REQUEST['easypack_token'] ) ) {
                delete_option('woo_inpost_organisation' ); // delete previous settings
                update_option('easypack_api_limit_connection', time() ); // set time for limit API connection retry
                $ping = EasyPack_API()->ping();
            }

			$easypack_api_change = sanitize_text_field( $_REQUEST['easypack_api_change'] );

			if ( $easypack_api_change == '0' ) {
				$args = [];
				$args['first_name'] = get_option( 'easypack_sender_first_name' );
				$args['last_name'] = get_option( 'easypack_sender_last_name' );
				$args['company_name'] = get_option( 'easypack_sender_company_name' );
				$args['phone'] = get_option( 'easypack_sender_phone' );
				$args['email'] = get_option( 'easypack_sender_email' );
				$args['default_machine_id'] = get_option( 'easypack_default_machine_id' );
				$args['address'] = [];


				if ( EasyPack_API()->api_country() !== EasyPack_API::COUNTRY_PL ) {
					unset( $args['default_machine_id'] );
				}
				if ( isset ( $args['default_machine_id'] ) && $args['default_machine_id'] == '' ) {
					unset( $args['default_machine_id'] );
				}

			}
		}

		/**
		 * @param int $type
		 *
		 * @return array[]
		 * @throws Exception
		 */
		private function output_dispath_point_module(): array {
			add_action( 'woocommerce_admin_field_manage_dispath_points_module',
				function ( array $data ) {
					if ( isset( self::$prevent_duplicate['manage_dispath_points_module'] )
					     && self::$prevent_duplicate['manage_dispath_points_module']
					) {
						return;
					}

					$saved_points = get_option( EasyPack::ATTRIBUTE_PREFIX . '_dpoint' );

					?>
                    <tr valign="top">

                        <th scope="row" class="titledesc">
                            <label for="woo_inpost_dpoint_0">
								<?php echo esc_attr( $data['name'] ); ?></label>
                        </th>
                        <td class="forminp forminp-text">

                            <table id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint-cell">
                                <thead>
                                <th><?php _e( 'Selected', 'woocommerce-inpost' ) ?></th>
                                <th><?php _e( 'Street', 'woocommerce-inpost' ) ?></th>
                                <th><?php _e( 'Building number', 'woocommerce-inpost' ) ?></th>
                                <th><?php _e( 'Postal code', 'woocommerce-inpost' ) ?></th>
                                <th><?php _e( 'City', 'woocommerce-inpost' ) ?></th>
                                </thead>

								<?php
								if ( is_array( $saved_points )
								     && ! empty( $saved_points && isset( $saved_points['street'] ) )
								) {

									$dpoints = is_array( $saved_points['street'] )
										? $saved_points['street']
										: [ 0 => $saved_points];

									// we have only single dpoint
									if( ! is_array( $saved_points['street'] ) ) {

                                        $saved_points_fix = [];
									    foreach($saved_points as $v) {
                                            $saved_points_fix['street'] = array($saved_points['street']);
                                            $saved_points_fix['building_number'] = array($saved_points['building_number']);
                                            $saved_points_fix['postal_code'] = array($saved_points['postal_code']);
                                            $saved_points_fix['city'] = array($saved_points['city']);
                                        }

                                        $saved_points = $saved_points_fix;
                                    }


									foreach ( $dpoints as $i => $v ) {

										$id = $i;
										?>

                                        <tr id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_<?php echo esc_attr( $i ); ?>"
                                            class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint-cell-wraper">
                                            <td>
                                                <input type="radio"
                                                       value="<?php echo esc_attr( $i ); ?>"
													<?php echo $i === (int) get_option( EasyPack::ATTRIBUTE_PREFIX . '_dpoint_selected' )
														? 'checked' : '' ?>
                                                       class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_selected"
                                                       name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_selected">
                                                <input type="button"
                                                       value="X"
                                                       class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint-remove"
                                                       data-remove="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_<?php echo esc_attr( $i ); ?>">

                                            </td>
                                            <td>
                                                <input
                                                        class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                        value="<?php echo ! empty( $saved_points['street'] ) ? esc_attr( $saved_points['street'][ $i ] ) : '' ?>"
                                                        name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[street][]"
                                                        id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_street">
                                            </td>
                                            <td>
                                                <input
                                                        class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                        value="<?php echo ! empty( $saved_points['building_number'] ) ? esc_attr( $saved_points['building_number'][ $i ] ) : '' ?>"
                                                        name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[building_number][]"
                                                        id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_building_number">
                                            </td>
                                            <td>
                                                <input
                                                        class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                        value="<?php echo ! empty( $saved_points['postal_code'] ) ? esc_attr( $saved_points['postal_code'][ $i ] ) : '' ?>"
                                                        name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[postal_code][]"
                                                        id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_postal_code">
                                            </td>
                                            <td>
                                                <input
                                                        class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                        value="<?php echo ! empty( $saved_points['city'] ) ? esc_attr( $saved_points['city'][ $i ] ) : '' ?>"
                                                        name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[city][]"
                                                        id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_city">
                                            </td>
                                        </tr>
										<?php
									}


								} else {
									$k = 0;
									?>
                                    <tr id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_<?php echo esc_attr( $k ); ?>"
                                        class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX); ?>_dpoint-cell-wraper">
                                        <td>
                                            <input type="radio"
                                                   value="<?php echo esc_attr( $k ); ?>"
												<?php echo $k === (int) get_option( EasyPack::ATTRIBUTE_PREFIX . '_dpoint_selected' )
													? 'checked' : '' ?>
                                                   class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_selected"
                                                   name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_selected">
                                            <input type="button"
                                                   value="X"
                                                   class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint-remove"
                                                   data-remove="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_<?php echo esc_attr( $k ); ?>">

                                        </td>
                                        <td>
                                            <input
                                                    class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                    value=""
                                                    name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[street]"
                                                    id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_street">
                                        </td>
                                        <td>
                                            <input
                                                    class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                    value=""
                                                    name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[building_number]"
                                                    id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_building_number">
                                        </td>
                                        <td>
                                            <input
                                                    class="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                    value=""
                                                    name="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[postal_code]"
                                                    id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_postal_code">
                                        </td>
                                        <td>
                                            <input
                                                    class="<?php echo esc_attr(EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint"
                                                    value=""
                                                    name="<?php echo esc_attr(EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint[city]"
                                                    id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_city">
                                        </td>
                                    </tr>
									<?php
								} ?>

                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <button id="<?php echo esc_attr( EasyPack::ATTRIBUTE_PREFIX ); ?>_dpoint_add"
                                    value="Add dispath point"><?php _e( 'Add dispath point', 'woocommerce-inpost' ) ?>
                            </button>
                        </td>
                    </tr>
					<?php

					self::$prevent_duplicate['manage_dispath_points_module'] = true;
				},
				10 );


			return
                [
                    'name'  => __( 'Manage dispath points', 'woocommerce-inpost' ),
                    'title' => __( 'Manage dispath points', 'woocommerce-inpost' ),
                    'type'  => 'manage_dispath_points_module',
                    'id'    => EasyPack::ATTRIBUTE_PREFIX . '_dpoint',

                ];
		}
	}

endif;
