<?php

namespace InspireLabs\WoocommerceInpost\shipping;

use Exception;
use InspireLabs\WoocommerceInpost\admin\EasyPack_Product_Shipping_Method_Selector;
use InspireLabs\WoocommerceInpost\EasyPack;
use InspireLabs\WoocommerceInpost\EasyPack_Helper;
use InspireLabs\WoocommerceInpost\EasyPack_API;
use InspireLabs\WoocommerceInpost\Geowidget_v5;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Dimensions_Model;
use InspireLabs\WoocommerceInpost\shipx\models\shipment\ShipX_Shipment_Parcel_Model;
use InspireLabs\WoocommerceInpost\shipx\services\shipment\ShipX_Shipment_Service;
use ReflectionException;
use WC_Eval_Math;
use WC_Shipping_Method;
use InspireLabs\WoocommerceInpost\EmailFilters\TrackingInfoEmail;
use Automattic\WooCommerce\Utilities\OrderUtil;

/**
 * EasyPack Shipping Method Parcel Machines
 *
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

if ( ! class_exists( 'EasyPack_Shippng_Parcel_Machines' ) ) {
	class EasyPack_Shippng_Parcel_Machines extends WC_Shipping_Method {

		static $logo_printed;

		static $setup_hooks_once = false;

		const SERVICE_ID = ShipX_Shipment_Model::SERVICE_INPOST_LOCKER_STANDARD;

		const NONCE_ACTION = self::SERVICE_ID;

		static $prevent_duplicate = [];

		static $review_order_after_shipping_once = false;

		static $woocommerce_checkout_after_order_review_once = false;

        public $ignore_discounts;

        protected $free_shipping_cost;
        protected $type;
        protected $flat_rate;
        protected $fee_cost;
        protected $cost_per_order;
        protected $based_on;
        protected $show_free_shipping_label;

		/**
		 * Constructor for shipping class
		 *
		 * @access public
		 * @return void
		 */
		public function __construct( $instance_id = 0 ) {

			$this->instance_id = absint( $instance_id );
			$this->supports    = [
				'shipping-zones',
				'instance-settings',
			];

			$this->id = 'easypack_parcel_machines';
			$this->method_description
			          = __( 'Allow customers to pick up orders themselves. By default, when using local pickup store base taxes will apply regardless of customer address.',
				'woocommerce' );

			$this->method_title = __( 'InPost Locker 24/7', 'woocommerce-inpost' );
			$this->init();


		}


		/**
		 * Init your settings
		 *
		 *
		 * @access public
		 * @return void
		 */
		function init() {

			$this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
			$this->init_settings(); // This is part of the settings API. Loads settings you previously init.
			$this->title          = $this->get_option( 'title' );
			$this->free_shipping_cost
			                      = $this->get_option( 'free_shipping_cost' );
            $this->show_free_shipping_label
                                  = $this->get_option( 'show_free_shipping_label' );
            $this->type           = $this->get_option('type', 'class');

			$this->flat_rate      = $this->get_option( 'flat_rate' );
			$this->cost_per_order = $this->get_option( 'cost_per_order' );
			$this->based_on       = $this->get_option( 'based_on' );

			$this->tax_status = $this->get_option( 'tax_status' );

            $this->ignore_discounts = $this->get_option( 'apply_minimum_order_rule_before_coupon' );

			$this->setup_hooks_once();
		}

		private function setup_hooks_once() {

            EasyPack_Helper()->include_inline_css();

            if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                add_filter( 'woocommerce_package_rates', [ $this, 'check_paczka_weekend_fs_settings' ], 10, 2 );
            }
			
			add_action( 'woocommerce_init', [ $this, 'add_settings_to_flexible_shipping' ] );

			add_action( 'woocommerce_update_options_shipping_' . $this->id,
				[ $this, 'process_admin_options' ] );
				
			$hook_name = get_option('easypack_button_output', 'woocommerce_review_order_after_shipping' );

			add_action( $hook_name,
				[ $this, 'woocommerce_review_order_after_shipping' ] );

			add_action( 'woocommerce_checkout_update_order_meta',
				[ $this, 'woocommerce_checkout_update_order_meta' ] );

			add_action( 'woocommerce_checkout_process', [ $this, 'woocommerce_checkout_process' ], PHP_INT_MAX );
            add_action( 'woocommerce_after_checkout_validation', [ $this, 'woocommerce_after_checkout_validation' ], 10, 2 );
          
			add_action( 'save_post', [ $this, 'save_post' ] );

			add_action( 'add_meta_boxes', [ $this, 'add_meta_boxes' ], 10, 2 );

			add_filter( 'woocommerce_cart_shipping_method_full_label',
				[ $this, 'woocommerce_cart_shipping_method_full_label' ], 10, 2 );

			add_filter( 'woocommerce_order_shipping_to_display_shipped_via',
				[ $this, 'woocommerce_order_shipping_to_display_shipped_via' ], 10, 2 );

			add_filter( 'woocommerce_my_account_my_orders_actions',
				[ $this, 'woocommerce_my_account_my_orders_actions' ], 10, 2 );


			add_filter( 'woocommerce_order_shipping_to_display',
				[ $this, 'woocommerce_order_shipping_to_display' ], 9999, 3 );

            add_action('wp_head', array( $this, 'add_styles_for_my_orders_page' ), 100 );

		}

		public function admin_options() {
			?>
            <table class="form-table">
				<?php $this->generate_settings_html(); ?>
            </table>
			<?php
		}

		public function generate_rates_html( $key, $data ) {
            $rates = EasyPack_Helper()->get_saved_method_rates($this->id, $this->instance_id);

			ob_start();
			include( 'views/html-rates.php' );

			return ob_get_clean();
		}


		public function init_form_fields() {

			$settings = [
				[
					'title'       => __( 'General settings', 'woocommerce-inpost' ),
					'type'        => 'title',
					'description' => '',
					'id'          => 'section_general_settings',
				],
				'logo_upload'        => [
					'name'  => __( 'Change logo', '' ),
					'title' => __( 'Upload custom logo', 'woocommerce-inpost' ),
					'type'  => 'logo_upload',
					'id'    => 'logo_upload',
				],
				'title'              => [
					'title'    => __( 'Method title', 'woocommerce-inpost' ),
					'type'     => 'text',
					'default'  => __( 'InPost Locker 24/7', 'woocommerce-inpost' ),
					'desc_tip' => false,
				],
                /*'delivery_terms'              => [
                    'title'    => __( 'Terms of delivery', 'woocommerce-inpost' ),
                    'type'     => 'text',
                    'default'  => __( '', 'woocommerce-inpost' ),
                    'desc_tip' => false,
                    'placeholder'       => '(2-3 dni)',
                ],*/
				'free_shipping_cost' => [
					'title'             => __( 'Free shipping', 'woocommerce-inpost' ),
					'type'              => 'number',
					'custom_attributes' => [
						'step' => 'any',
						'min'  => '0',
					],
					'default'           => '',
					'desc_tip'          => __( 'Enter the amount of the order from which the shipping will be free (does not include virtual products). ',
                        'woocommerce-inpost' ),
					'placeholder'       => '0.00',
				],
                'show_free_shipping_label' => array(
                    'title'       => __( '', 'woocommerce-inpost' ),
                    'label'       => __( 'Add label \'(free)\' to the end of title of shipping method', 'woocommerce-inpost' ),
                    'type'        => 'checkbox',
                    'description' => __( '', 'woocommerce-inpost' ),
                    'default'     => 'yes',
                    'desc_tip'    => true,
                ),
                'apply_minimum_order_rule_before_coupon' => array(
                    'title'       => __( 'Coupons discounts', 'woocommerce' ),
                    'label'       => __( 'Apply minimum order rule before coupon discount', 'woocommerce' ),
                    'type'        => 'checkbox',
                    'description' => __( 'If checked, free shipping would be available based on pre-discount order amount.', 'woocommerce' ),
                    'default'     => 'no',
                    'desc_tip'    => true,
                ),
				'flat_rate'          => [
					'title'   => __( 'Flat rate', 'woocommerce-inpost' ),
					'type'    => 'checkbox',
					'label'   => __( 'Set a flat-rate shipping fee for the entire order.', 'woocommerce-inpost' ),
					'class'   => 'easypack_flat_rate',
					'default' => 'yes',
				],
				'cost_per_order'     => [
					'title'             => __( 'Cost per order', 'woocommerce-inpost' ),
					'type'              => 'number',
					'custom_attributes' => [
						'step' => 'any',
						'min'  => '0',
					],
					'class'             => 'easypack_cost_per_order',
					'default'           => '',
					'desc_tip'          => __( 'Set a flat-rate shipping for all orders'
						, 'woocommerce-inpost' ),
					'placeholder'       => '0.00',
				],
				'tax_status'         => [
					'title'   => __( 'Tax status', 'woocommerce' ),
					'type'    => 'select',
					'class'   => 'wc-enhanced-select',
					'default' => 'none',
					'options' => [
						'none'    => _x( 'None', 'Tax status', 'woocommerce-inpost' ),
						'taxable' => __( 'Taxable', 'woocommerce-inpost' ),
					],
				],

				[
					'title'       => __( 'Rates table', 'woocommerce-inpost' ),
					'type'        => 'title',
					'description' => '',
					'id'          => 'section_general_settings',
				],
				'based_on'           => [
					'title'    => __( 'Based on', 'woocommerce-inpost' ),
					'type'     => 'select',
					'desc_tip' => __( 'Select the method of calculating shipping cost. If the cost of shipping is to be calculated based on the weight of the cart and the products do not have a defined weight, the cost will be calculated incorrectly.',
                        'woocommerce-inpost' ),
                    'description' => sprintf( '<b id="easypack_dimensions_warning" style="color:red;display:none">%1s</b> %1s',
                                        __('Attention!', 'woocommerce-inpost'),
                                        __('Set the dimension in the settings of each product. The default value is size \'A\'', 'woocommerce-inpost' )

                                    ),
					'class'    => 'wc-enhanced-select easypack_based_on',
					'options'  => [
						'price'  => __( 'Price', 'woocommerce-inpost' ),
						'weight' => __( 'Weight', 'woocommerce-inpost' ),
                        'size'   => __( 'Size (A, B, C)', 'woocommerce-inpost' ),
					],
				],
				'rates'              => [
					'title'    => '',
					'type'     => 'rates',
					'class'    => 'easypack_rates',
					'default'  => '',
					'desc_tip' => '',
				],

                'gabaryt_a'     => [
                    'title'             => __( 'Size A', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_gabaryt_a',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for size A', 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],

                'gabaryt_b'     => [
                    'title'             => __( 'Size B', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_gabaryt_b',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for size B', 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],

                'gabaryt_c'     => [
                    'title'             => __( 'Size C', 'woocommerce-inpost' ),
                    'type'              => 'number',
                    'custom_attributes' => [ 'step' => 'any', 'min' => '0' ],
                    'class'             => 'easypack_gabaryt_c',
                    'default'           => '',
                    'desc_tip'          => __( 'Set a flat-rate shipping for size C', 'woocommerce-inpost' ),
                    'placeholder'       => '0.00',
                ],
			];

            $settings = $this->add_shipping_classes_settings( $settings );

			$this->instance_form_fields = $settings;
			$this->form_fields          = $settings;


		}


		public function generate_logo_upload_html( $key, $data ) {
			$field_key = $this->get_field_key( $key );

			$defaults = [
				'title'             => 'Upload custom logo',
				'disabled'          => false,
				'class'             => '',
				'css'               => '',
				'placeholder'       => '',
				'type'              => 'text',
				'desc_tip'          => false,
				'description'       => '',
				'custom_attributes' => [],
			];

			$data = wp_parse_args( $data, $defaults );

			ob_start();
			?>
            <tr valign="top">
                <th scope="row" class="titledesc">
                    <label for="<?php echo esc_attr( $field_key ); ?>"><?php echo wp_kses_post( $data['title'] ); ?><?php echo $this->get_tooltip_html( $data ); // WPCS: XSS ok.
						?></label>
                </th>
                <td class="forminp">
                    <fieldset>
                        <legend class="screen-reader-text">
                            <span><?php echo wp_kses_post( $data['title'] ); ?></span>
                        </legend>
                        <img src='<?php echo esc_attr( $this->get_instance_option( $key ) ); ?>'
                             style='width: 60px; height: auto; background-size: cover; display: <?php echo !empty( $this->get_instance_option( $key ) ) ? 'block' : 'none'; ?>; margin-bottom: 10px;'
                             id='woo-inpost-logo-preview'>
                        <ul id="woo-inpost-logo-action" style='display: <?php echo !empty( $this->get_instance_option( $key ) ) ? 'block' : 'none'; ?>;'>
                            <li>
                                <a id="woo-inpost-logo-delete" href="#" title="Delete image">
                                    <?php echo __( 'Delete', 'woocommerce-inpost' ); ?>
                                </a>
                            </li>
                        </ul>
                        <button class='woo-inpost-logo-upload-btn'>
                            <?php echo __( 'Upload', 'woocommerce-inpost' ); ?>
                        </button>
                        <input class="input-text regular-input" type="hidden"
                               name="<?php echo esc_attr( $field_key ); ?>"
                               id="woocommerce_easypack_logo_upload"
                               style="<?php echo esc_attr( $data['css'] ); ?>"
                               value="<?php echo esc_attr( $this->get_instance_option( $key ) ); ?>"
                               placeholder="<?php echo esc_attr( $data['placeholder'] ); ?>"/>
						<?php echo $this->get_description_html( $data ); // WPCS: XSS ok.
						?>
                    </fieldset>
                </td>
            </tr>
			<?php

			return ob_get_clean();
		}


		public function add_rate( $args = [] ) {

			$args['meta_data'] = [
			        'logo' => $this->get_instance_option( 'logo_upload' ),
			        //'delivery_terms' => $this->get_instance_option( 'delivery_terms' ),
                ];


			parent::add_rate( $args );
		}

		public function process_admin_options() {
			parent::process_admin_options();

			if ( isset( $_POST['rates'] ) && is_array( $_POST['rates'] ) ) {

                $save_rates = array();

                foreach( $_POST['rates'] as $key => $rate ) {
                    $save_rates[ (int) $key ] = array_map( 'sanitize_text_field', $rate );
                }

				update_option( 'woocommerce_' . $this->id . '_' . $this->instance_id . '_rates', $save_rates, false );
			}
		}

		public function calculate_shipping_free_shipping( $package ) {

            $total = WC()->cart->get_displayed_subtotal();

            if (WC()->cart->display_prices_including_tax()) {
                $total = $total - WC()->cart->get_discount_tax();
            }

            if ('no' === $this->ignore_discounts) {
                $total = $total - WC()->cart->get_discount_total();
            }

            if ( ! empty( $this->free_shipping_cost )
                && $this->free_shipping_cost <= $total
            ) {

                $add_free_ship_label = '';
                if( 'yes' === $this->show_free_shipping_label ) {
                    $add_free_ship_label = __( ' (free)', 'woocommerce-inpost' );
                }

				$add_rate = [
					'id'    => $this->get_rate_id(),
					'label' => $this->title . ' ' .  $add_free_ship_label,
					'cost'  => 0,
				];
				$this->add_rate( $add_rate );

				return true;
			}

			return false;
		}

		public function calculate_shipping_flat( $package ) {

			if ( $this->flat_rate == 'yes' ) {

			    if( (float) $this->cost_per_order > 0 ) {

                    $add_rate = [
                        'id' => $this->get_rate_id(),
                        'label' => $this->title,
                        'cost' => $this->cost_per_order,
                    ];
                    $this->add_rate($add_rate);

                } else {

                    $add_free_ship_label = '';
                    if( 'yes' === $this->show_free_shipping_label ) {
                        $add_free_ship_label = __( ' (free)', 'woocommerce-inpost' );
                    }

                    $add_rate = [
                        'id'    => $this->get_rate_id(),
                        'label' => $this->title . ' ' .  $add_free_ship_label,
                        'cost'  => 0,
                    ];
                    $this->add_rate( $add_rate );
                }

				return true;
			}

			return false;
		}

        public function package_weight( $items ) {
            $weight = 0;
            foreach ( $items as $item ) {
                if( ! empty( $item['data']->get_weight() ) ) {
                    $weight += floatval( $item['data']->get_weight() ) * $item['quantity'];
                }
            }

            return $weight;
        }

		public function package_subtotal( $items ) {
			$subtotal = 0;
			foreach ( $items as $item ) {
				$subtotal += $item['line_subtotal']
				             + $item['line_subtotal_tax'];
			}

			return $subtotal;
		}

		/**
		 * @param unknown $package
		 *
		 */
		public function calculate_shipping_table_rate( $package ) {

            // based on gabaryt
            if ( $this->based_on == 'size' ) {

                $max_gabaryt = $this->get_max_gabaryt( $package );
                $cost = $this->instance_settings[ 'gabaryt_' . $max_gabaryt ];

                $add_rate = [
                    'id'    => $this->get_rate_id(),
                    'label' => $this->title,
                    'cost'  => $cost,
                    'package' => $package,
                ];
                $this->add_rate( $add_rate );

                return;
            }

            $rates = EasyPack_Helper()->get_saved_method_rates($this->id, $this->instance_id);

            if( is_array( $rates ) ) {
                foreach ( $rates as $key => $rate ) {
                    if ( empty( $rates[$key]['min'] ) || trim( $rates[$key]['min'] ) == '' ) {
                        $rates[$key]['min'] = 0;
                    }
                    if ( empty( $rates[$key]['max'] ) || trim( $rates[$key]['max'] ) == '' ) {
                        $rates[$key]['max'] = PHP_INT_MAX;
                    }
                }
            }
			$value = 0;
			if ( $this->based_on == 'price' ) {
				$value = $this->package_subtotal( $package['contents'] );
			}
			if ( $this->based_on == 'weight' ) {
				$value = $this->package_weight( $package['contents'] );
			}
			foreach ( $rates as $rate ) {
				if ( floatval( $rate['min'] ) <= $value && floatval( $rate['max'] ) >= $value ) {

				    $add_rate = [
						//'id'    => $this->id,
                        'id'    => $this->get_rate_id(),
						'label' => $this->title,
						'cost'  => $rate['cost'],
					];
					$this->add_rate( $add_rate );

					return;
				}
			}
		}

		/**
		 * @param array $package
		 */
        public function calculate_shipping( $package = [] ) {
            if ( EasyPack_API()->normalize_country_code_for_inpost( $package['destination']['country'] )
                == EasyPack_API()->getCountry()
            ) {
                /**
                 * order to caluclate shipping:
                 * 1) free shipping level
                 * 2) based on shipping class if exists
                 * 3) flat shipping settings (Cost per order)
                 * 4) table of costs based on weight/gabaryt
                 *
                 */

                if ( ! $this->calculate_shipping_free_shipping( $package ) ) {

                    $rate = array(
                        'id' => $this->get_rate_id(),
                        'label' => $this->title,
                        'cost' => 0,
                        'package' => $package,
                    );

                    // Calculate the costs.
                    $has_costs = false; // True when a cost is set. False if all costs are blank strings.
                    $cost = $this->get_option('cost');

                    if ('' !== $cost) {
                        $has_costs = true;
                        $rate['cost'] = $this->evaluate_cost(
                            $cost,
                            array(
                                'qty' => $this->get_package_item_qty($package),
                                'cost' => $package['contents_cost'],
                            )
                        );
                    }

                    // Add shipping class costs.
                    $shipping_classes = WC()->shipping()->get_shipping_classes();

                    if (!empty($shipping_classes)) {
                        $found_shipping_classes = $this->find_shipping_classes($package);
                        $highest_class_cost = 0;

                        foreach ($found_shipping_classes as $shipping_class => $products) {
                            // Also handles BW compatibility when slugs were used instead of ids.
                            $shipping_class_term = get_term_by('slug', $shipping_class, 'product_shipping_class');
                            $class_cost_string = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option('class_cost_' . $shipping_class_term->term_id, $this->get_option('class_cost_' . $shipping_class, '')) : $this->get_option('no_class_cost', '');

                            if ('' === $class_cost_string) {
                                continue;
                            }

                            $has_costs = true;
                            $class_cost = $this->evaluate_cost(
                                $class_cost_string,
                                array(
                                    'qty' => array_sum(wp_list_pluck($products, 'quantity')),
                                    'cost' => array_sum(wp_list_pluck($products, 'line_total')),
                                )
                            );

                            if ('class' === $this->type) {
                                $rate['cost'] += $class_cost;
                            } else {
                                $highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
                            }
                        }

                        if ('order' === $this->type && $highest_class_cost) {
                            $rate['cost'] += $highest_class_cost;
                        }
                    }

                    if ( $has_costs ) {
                        $this->add_rate( $rate );
                    }

                    /**
                     * Developers can add additional flat rates based on this one via this action since @version 2.4.
                     *
                     * Previously there were (overly complex) options to add additional rates however this was not user.
                     * friendly and goes against what Flat Rate Shipping was originally intended for.
                     */
                    do_action( 'woocommerce_' . $this->id . '_shipping_add_rate', $this, $rate );

                    if( ! $has_costs ) {
                        if ( ! $this->calculate_shipping_flat( $package ) ) {
                            $this->calculate_shipping_table_rate( $package );
                        }
                    }
                }
            }
        }


		/**
		 * Output template with Choose Parcel Locker button
		 */
        public function woocommerce_review_order_after_shipping() {

            if( get_option( 'easypack_js_map_button' ) !== 'yes') {

                $chosen_shipping_methods = [];
                $parcel_machine_id = '';
                $fs_method_name = '';

                if (is_object(WC()->session)) {
                    $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods');

                    if (EasyPack_Helper()->is_flexible_shipping_activated()) {
                        $fs_method_name = EasyPack_Helper()->get_method_linked_to_fs($chosen_shipping_methods);
                    }

                    // remove digit postfix (for example "easypack_parcel_machines:18") in method name
                    foreach ($chosen_shipping_methods as $key => $method) {
                        $chosen_shipping_methods[$key] = EasyPack_Helper()->validate_method_name($method);
                    }

                    $parcel_machine_id = WC()->session->get('parcel_machine_id');
                }

                $method_name = EasyPack_Helper()->validate_method_name($this->id);

                if (!empty($chosen_shipping_methods) && is_array($chosen_shipping_methods)) {
                    if (in_array($method_name, $chosen_shipping_methods) || $fs_method_name === $method_name) {
                        if (!self::$review_order_after_shipping_once) {
                            $args = ['parcel_machines' => []];
                            $args['parcel_machine_id'] = $parcel_machine_id;
                            $args['shipping_method_id'] = $this->id;
                            wc_get_template(
                                'checkout/easypack-review-order-after-shipping.php',
                                $args,
                                '',
                                EasyPack()->getTemplatesFullPath()
                            );

                            self::$review_order_after_shipping_once = true;
                        }
                    }
                }

            }
        }


        public function woocommerce_after_checkout_validation( $fields, $errors ) {

            $chosen_shipping_methods = [];
            $at_least_one_physical_product = false;
            $fs_method_name = '';
            static $alert_shown;

            if ( is_object( WC()->session ) ) {
                $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods' );
                if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                    $fs_method_name = EasyPack_Helper()->get_method_linked_to_fs( $chosen_shipping_methods );
                }
                $cart_contents = WC()->session->get('cart');

                $at_least_one_physical_product = EasyPack_Helper()->physical_goods_in_cart( $cart_contents );
            }

            if( ! empty( $chosen_shipping_methods ) && is_array ( $chosen_shipping_methods ) ) {

                // remove digit postfix (for example "easypack_parcel_machines:18") in method name
                foreach ( $chosen_shipping_methods as $key => $method ) {
                    $chosen_shipping_methods[$key] = EasyPack_Helper()->validate_method_name( $method );
                }

                $method_name = EasyPack_Helper()->validate_method_name( $this->id );

                if ( in_array( $method_name, $chosen_shipping_methods ) || $fs_method_name === $method_name ) {

                    if ( false === $this->is_method_courier() && $at_least_one_physical_product ) {

                        if ( empty( $_POST['parcel_machine_id'] ) ) {

                            if ( ! $alert_shown ) {

                                $alert_shown = true;
                                if( 'pl-PL' === get_bloginfo("language") ) {
                                    $errors->add( 'validation', __( 'Paczkomat jest wymaganym polem', 'woocommerce-inpost') );
                                } else {
                                    $errors->add( 'validation', __( 'Parcel locker Inpost is required field', 'woocommerce-inpost') );
                                }

                            }
                        }
                    }
                }
            }

        }


        public function woocommerce_checkout_process() {

            $chosen_shipping_methods = [];
            $at_least_one_physical_product = false;
			$fs_method_name = '';
			static $alert_shown;
            static $alert_shown_phone;

            if ( is_object( WC()->session ) ) {
                $chosen_shipping_methods = WC()->session->get('chosen_shipping_methods' );
				if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                    $fs_method_name = EasyPack_Helper()->get_method_linked_to_fs( $chosen_shipping_methods );
                }
                $cart_contents = WC()->session->get('cart');

                $at_least_one_physical_product = EasyPack_Helper()->physical_goods_in_cart( $cart_contents );
            }

            if( ! empty( $chosen_shipping_methods ) && is_array ( $chosen_shipping_methods ) ) {

                // remove digit postfix (for example "easypack_parcel_machines:18") in method name
                foreach ( $chosen_shipping_methods as $key => $method ) {
                    $chosen_shipping_methods[$key] = EasyPack_Helper()->validate_method_name( $method );
                }

                $method_name = EasyPack_Helper()->validate_method_name( $this->id );

                if ( in_array( $method_name, $chosen_shipping_methods ) || $fs_method_name === $method_name ) {                    

                    if ( false === $this->is_method_courier() && $at_least_one_physical_product ) {                        

                        if ( empty( $_POST['parcel_machine_id'] ) ) {

                            if ( ! $alert_shown ) {

                                $alert_shown = true;
                                if( 'pl-PL' === get_bloginfo("language") ) {
                                    wc_add_notice(__('Musisz wybrać paczkomat', 'woocommerce-inpost'), 'error');
									throw new Exception( "Inpost" );
									
                                } else {
                                    wc_add_notice(__('Parcel locker must be choosen', 'woocommerce-inpost'), 'error');
									throw new Exception( "Inpost" );
                                }

                            }
                        } else {
                            WC()->session->set( 'parcel_machine_id', $_POST['parcel_machine_id'] );
                        }
                    }

                    /*$billing_phone = $_POST['billing_phone'];
                    if( ! empty( $billing_phone ) ) {
                        if ( false === EasyPack_API()->is_uk() ) {
                            $validate_phone = EasyPack_API()->validate_phone( $billing_phone );
                            if( $validate_phone !== true && ! $alert_shown_phone ) {
                                $alert_shown_phone = true;
                                wc_add_notice( $validate_phone, 'error' );
                            }
                        }
                    }*/
                }
            }

        }


        public function woocommerce_checkout_update_order_meta( $order_id ) {
            if ( isset( $_POST['parcel_machine_id'] ) && ! empty( $_POST['parcel_machine_id'] ) ) {
                update_post_meta( $order_id, '_parcel_machine_id', sanitize_text_field( $_POST['parcel_machine_id'] ) );

                if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                    $order = wc_get_order( $order_id );
                    if ( $order && !is_wp_error($order) ) {
                        $order->update_meta_data('_parcel_machine_id', sanitize_text_field($_POST['parcel_machine_id']));
                        $order->save();
                    }
                }
            }

            if ( isset( $_POST['parcel_machine_desc'] ) && ! empty( $_POST['parcel_machine_desc'] ) ) {
                update_post_meta( $order_id, '_parcel_machine_desc', sanitize_text_field( $_POST['parcel_machine_desc'] ) );

                if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                    $order = wc_get_order( $order_id );
                    if ( $order && !is_wp_error($order) ) {
                        $order->update_meta_data('_parcel_machine_desc', sanitize_text_field($_POST['parcel_machine_desc']));
                        $order->save();
                    }
                }
            }
			
			// save easypack method name in metadata to show later required metabox in order details
			if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                $order = wc_get_order( $order_id );
                foreach( $order->get_shipping_methods() as $shipping_method ) {
                    $fs_instance_id = $shipping_method->get_instance_id();
                }

                $fs_method_name = EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $fs_instance_id );
                if( ! empty( $fs_method_name ) ) {
                    update_post_meta( $order_id, '_fs_easypack_method_name', $fs_method_name );

                    if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                        $order = wc_get_order( $order_id );
                        if ( $order && !is_wp_error($order) ) {
                            $order->update_meta_data( '_fs_easypack_method_name', $fs_method_name );
                            $order->save();
                        }
                    }
                }
            }

		}


		public function save_post( $post_id ) {

			// Check if our nonce is set.
			if ( ! isset( $_POST['wp_nonce'] ) ) {
				return;
			}
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $_POST['wp_nonce'], self::NONCE_ACTION ) ) {
				return;
			}
			// If this is an autosave, our form has not been submitted, so we don't want to do anything.
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}

			$status = get_post_meta( $post_id, '_easypack_status', true );
			if ( $status == '' ) {
				$status = 'new';
			}

			if ( $status == 'new' ) {

                EasyPack_Helper()->set_data_to_order_meta( $_POST, $post_id );
			}

		}

		public function get_logo() {

			$custom_logo = null;

			if ( empty( $custom_logo ) ) {
				return '<img style="height:22px; float:right;" src="'
				       . untrailingslashit( EasyPack()->getPluginImages()
				                            . 'logo/small/white.png"/>' );
			} else {
				return '<img style="height:22px; float:right;" src="'
				       . untrailingslashit( $custom_logo );
			}

		}

		public function add_meta_boxes( $post_type, $post ) {

            $order_id = null;

            //if ( OrderUtil::custom_orders_table_usage_is_enabled() ) {
            if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                // HPOS usage is enabled.
                if ( is_a( $post, 'WC_Order' ) ) {
                    $order_id = $post->get_id();
                }

            } else {
                // Traditional orders are in use.
                if ( is_object( $post ) && $post->post_type == 'shop_order' ) {
                    $order_id = $post->ID;
                }

            }

            if( $order_id ) {
                $order = wc_get_order( $order_id );
                // show metabox only for matched shipping method (plus Flexible shipping integration)
                $fs_method_name = get_post_meta( $order_id, '_fs_easypack_method_name', true);

                if ( $order->has_shipping_method($this->id) || $fs_method_name === $this->id ) {
                    add_meta_box('easypack_parcel_machines',
                        __('InPost', 'woocommerce-inpost')
                        . $this->get_logo(),
                        [$this, 'order_metabox'], null, 'side',
                        'default');
                }
            }

		}

		public function order_metabox( $post ) {
			self::order_metabox_content( $post );
		}

		/**
		 * @throws ReflectionException
		 */
		public static function ajax_create_package() {
			$ret = [ 'status' => 'ok' ];

			$shipment_model   = self::ajax_create_shipment_model();
			$order_id         = $shipment_model->getInternalData()->getOrderId();
			$shipment_service = EasyPack::EasyPack()->get_shipment_service();
			$status_service   = EasyPack::EasyPack()->get_shipment_status_service();
			$shipment_array   = $shipment_service->shipment_to_array( $shipment_model );

			$label_url = '';
            $order = null;

			try {

				update_post_meta( $order_id, '_easypack_parcel_create_args', $shipment_array );

                $order = wc_get_order( $order_id );

                if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                    if ( $order && !is_wp_error($order) ) {
                        $order->update_meta_data( '_easypack_parcel_create_args', $shipment_array );
                        $order->save();
                    }
                }
				
				$response = EasyPack_API()->customer_parcel_create( $shipment_array );

				$internal_data = $shipment_model->getInternalData();
				$internal_data->setInpostId( $response['id'] );
				$internal_data->setStatus( $response['status'] );
				$internal_data->setStatusTitle( $status_service->getStatusTitle( $response['status'] ) );
				$internal_data->setStatusDescription( $status_service->getStatusDescription( $response['status'] ) );

				$internal_data->setStatusChangedTimestamp( time() );

				$internal_data->setCreatedAt( time() );
				$internal_data->setUrl( $response['href'] );
				$shipment_model->setInternalData( $internal_data );

				$search_in_api = EasyPack_API()->customer_parcel_get_by_id( $shipment_model->getInternalData()->getInpostId() );

				$label_url = null;
				$tracking_for_email = '';

				/*for ( $i = 0; $i < 10; $i ++ ) {
					sleep( 1 );
					$label_url = self::ajax_parcel_machines_get_stickers_url( $shipment_model );
					if ( ! empty( $label_url ) ) {
						break;
					}

					 //{"status":400,"error":"invalid_action","message":"Action (get_label) can not be taken on shipment with status (created).","details":{"action":"get_label","shipment_status":"created","shipment_id":53256}}

				}*/

				for ( $i = 0; $i < 3; $i ++ ) {
					sleep( 1 );
					$search_in_api = EasyPack_API()->customer_parcel_get_by_id( $shipment_model->getInternalData()->getInpostId() );
					if ( isset( $search_in_api['items'][0]['tracking_number'] ) ) {
						$shipment_model->getInternalData()->setTrackingNumber( $search_in_api['items'][0]['tracking_number'] );
						break;
					}

                    // ?? API changed ?? key: items => parcels
                    if ( isset( $search_in_api['parcels'][0]['tracking_number'] ) ) {
                        $tracking_for_email = $search_in_api['parcels'][0]['tracking_number'];
                        $shipment_model->getInternalData()->setTrackingNumber( $search_in_api['parcels'][0]['tracking_number'] );
                        break;
                    }
				}

				$internal_data = $shipment_model->getInternalData();
				$internal_data->setLabelUrl( $label_url );
				$shipment_model->setInternalData( $internal_data );

				update_post_meta( $order_id, '_easypack_status', 'created' );

                if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                    if ( $order && !is_wp_error($order) ) {
                        $order->update_meta_data( '_easypack_status', 'created' );
                        $order->save();
                    }
                }

				if( ! empty( $tracking_for_email ) ) {
                    update_post_meta( $order_id, '_easypack_parcel_tracking', $tracking_for_email );

                    if( 'yes' === get_option('woocommerce_custom_orders_table_enabled') ) {
                        if ( $order && !is_wp_error($order) ) {
                            $order->update_meta_data( '_easypack_parcel_tracking', $tracking_for_email );
                            $order->save();
                        }
                    }
                }

				//zapisz koszt przesyłki do przesyłki
				$price_calculator = EasyPack()->get_shipment_price_calculator_service();

				$shipment_service->update_shipment_to_db( $shipment_model );

			} catch ( Exception $e ) {

                \wc_get_logger()->debug( 'INPOST create_package Exception: ', array( 'source' => 'inpost-log' ) );
                \wc_get_logger()->debug( print_r( $order_id, true), array( 'source' => 'inpost-log' ) );
                \wc_get_logger()->debug( print_r( $e->getMessage(), true), array( 'source' => 'inpost-log' ) );

				$ret['status'] = 'error';
				$ret['message']	= __( 'There are some errors. Please fix it: <br>', 'woocommerce-inpost' ) . EasyPack_API()->translate_error( $e->getMessage() );
			}

			if ( $ret['status'] == 'ok' ) {
                $order = wc_get_order( $order_id );
                $tracking_url = EasyPack_Helper()->get_tracking_url();

                $order->add_order_note(
					__( 'Shipment created', 'woocommerce-inpost' ), false
				);

                EasyPack_Helper()->set_order_status_completed( $order_id );

                if( isset( $_POST['action']) && $_POST['action'] === 'easypack_bulk_create_shipments' ) {
                    if( $tracking_for_email ) {
                        $ret['tracking_number'] = $tracking_for_email;
                    } else {
                        $ret['api_status'] = $status_service->getStatusDescription( $response['status'] );
                    }
                } else {
                    $ret['content'] = self::order_metabox_content( get_post( $order_id ), false, $shipment_model );
                }
				
				// send email to buyer with tracking details
				( new TrackingInfoEmail() )->send_tracking_info_email( $order, $tracking_url,  $tracking_for_email );
			}
			echo json_encode( $ret );
			wp_die();
		}

		/**
		 * @param $post
		 * @param bool $output
		 * @param ShipX_Shipment_Model|null $shipment
		 *
		 * @return string
		 * @throws Exception
		 */
		public static function order_metabox_content(
			$post,
			$output = true,
			$shipment = null
		) {

			if ( ! $output ) {
				ob_start();
			}
			$shipment_service = EasyPack::EasyPack()->get_shipment_service();

            if ( is_a( $post, 'WC_Order' ) ) {
                $order_id = $post->get_id();
            } else {
                $order_id = $post->ID;
            }

			$geowidget_config = ( new Geowidget_v5() )->get_pickup_delivery_configuration( 'easypack_parcel_machines' );
			if ( false === $shipment instanceof ShipX_Shipment_Model ) {
				$shipment = $shipment_service->get_shipment_by_order_id( $order_id );
			}

			if ( $shipment instanceof ShipX_Shipment_Model
			     && false === $shipment_service->is_shipment_match_to_current_api( $shipment )
			) {
				wp_nonce_field( self::NONCE_ACTION, 'wp_nonce' );
				$wrong_api_env = true;
				include( 'views/html-order-matabox-parcel-machines.php' );
				if ( ! $output ) {
					$out = ob_get_clean();

					return $out;
				}

				return '';
			}
			$wrong_api_env = false;

			$order = wc_get_order( $order_id );

			/**
			 * id, template, dimensions, weight, tracking_number, is_not_standard
			 */

			if ( null !== $shipment ) {
				$parcels      = $shipment->getParcels();
				$tracking_url = $shipment->getInternalData()->getTrackingNumber();
				$stickers_url = $shipment->getInternalData()->getLabelUrl();

				if ( true === $output ) {
					$status_srv = EasyPack()->get_shipment_status_service();
					$status_srv->refreshStatus( $shipment );
				}

				$status            = $shipment->getInternalData()->getStatus();
				$parcel_machine_id = $shipment->getCustomAttributes()->getTargetPoint();
				$send_method       = $shipment->getCustomAttributes()->getSendingMethod();
				$disabled          = true;
			} else {
				$package_sizes_display = EasyPack()->get_package_sizes_display();
				$parcels = [];
				$parcel  = new ShipX_Shipment_Parcel_Model();
				$parcel->setTemplate( get_option( 'easypack_default_package_size', 'small' ) );
				$parcels[] = $parcel;

                $parcel_machine_from_order = get_post_meta( $order_id, '_parcel_machine_id', true );
				$parcel_machine_id = ! empty( $parcel_machine_from_order )
					? $parcel_machine_from_order
					: get_option( 'easypack_default_machine_id' );

				$tracking_url = false;
				$status       = 'new';
				$send_method  = get_option( 'easypack_default_send_method', 'parcel_machine' );
				$disabled     = false;
			}
			$package_sizes = EasyPack()->get_package_sizes();

			$send_method_disabled = false;

			if ( EasyPack_API()->getCountry() === EasyPack_API::COUNTRY_PL ) {
				$send_methods = [
					'parcel_machine' => __( 'Parcel locker', 'woocommerce-inpost' ),
					'courier'        => __( 'Courier', 'woocommerce-inpost' ),
					'pop'            => __( 'POP', 'woocommerce-inpost' )
				];
			} else {
				$send_methods = [
					'parcel_machine' => __( 'Parcel locker', 'woocommerce-inpost' )
				];
			}
			$selected_service = $shipment_service->get_customer_service_name_by_id( self::SERVICE_ID );
			include( 'views/html-order-matabox-parcel-machines.php' );

			wp_nonce_field( self::NONCE_ACTION, 'wp_nonce' );
			if ( ! $output ) {
				$out = ob_get_clean();

				return $out;
			}
		}


		/**
		 * @param ShipX_Shipment_Model $shipment_model
		 *
		 * @return string
		 */
		/*
		public static function ajax_parcel_machines_get_stickers_url( $shipment_model ) {
			$order_id = $shipment_model->getInternalData()->getOrderId();

			try {
				$label = EasyPack_API()->customer_parcel_sticker( $shipment_model->getInternalData()->getInpostId() );
				$upload     = wp_upload_dir();
				$upload_dir = $upload['basedir'];
				$upload_dir = $upload_dir . DIRECTORY_SEPARATOR . 'woo_inpost_uploads' . DIRECTORY_SEPARATOR;

				if ( ! is_dir( $upload_dir ) ) {
					mkdir( $upload_dir, 0775 );
				}

				$label_name = 'label_' . $order_id . '.pdf';

				if( isset( $label['body'] ) && !empty( $label['body'] ) ) {
                    file_put_contents(
                        $upload_dir . DIRECTORY_SEPARATOR . $label_name, $label['body']
                    );
                }

				return content_url() . '/uploads/woo_inpost_uploads/' . $label_name;

			} catch ( Exception $e ) {
				$ret['status'] = 'error';
				$ret['message'] = __( 'There are some errors. Please fix it: <br>', 'woocommerce-inpost' ) . EasyPack_API()->translate_error( $e->getMessage() );

				return $ret;
			}
		}
		*/


		/**
		 * @return ShipX_Shipment_Model
		 */
		public static function ajax_create_shipment_model() {
			$shipmentService = EasyPack::EasyPack()->get_shipment_service();

            $order_id = sanitize_text_field( $_POST['order_id'] );

            $insurance_amount = '';
            $reference_number = '';
            $send_method = '';
            $parcels = [];

            $courier_parcel_data = array();
            // if Bulk create shipments
            if( isset( $_POST['action']) && $_POST['action'] === 'easypack_bulk_create_shipments' ) {

                $parcel_machine_id = get_post_meta( $order_id, '_parcel_machine_id', true );

                $parcels = get_post_meta( $order_id, '_easypack_parcels', true )
                    ? get_post_meta( $order_id, '_easypack_parcels', true )
                    : array( Easypack_Helper()->get_parcel_size_from_settings( $order_id ) );

                $insurance_amount = get_post_meta( $order_id, '_easypack_parcel_insurance', true )
                    ? get_post_meta( $order_id, '_easypack_parcel_insurance', true )
                    : floatval( get_option('easypack_insurance_amount_default') );
                $reference_number = get_post_meta( $order_id, '_reference_number', true )
                    ? get_post_meta( $order_id, '_reference_number', true )
                    : $order_id;
                $send_method = get_post_meta( $order_id, '_easypack_send_method', true )
                    ? get_post_meta( $order_id, '_easypack_send_method', true )
                    : get_option( 'easypack_default_send_method' );

            } else {

                $parcel_machine_id = isset( $_POST['parcel_machine_id'] )
                    ? sanitize_text_field( $_POST['parcel_machine_id'] ) : '';

                if ( ! isset( $_POST['insurance_amounts'] ) || $_POST['insurance_amounts'][0] === '0' ) {
                    $insurance_amounts = [ null ];
                } else {
                    if( is_array( $_POST['insurance_amounts'] ) ) {
                        $insurance_amounts = array_map('sanitize_text_field', $_POST['insurance_amounts']);
                    }
                }

                $insurance_amount = isset( $insurance_amounts[0] )
                    ? $insurance_amounts[0]
                    : get_option( 'easypack_insurance_amount_default', null );

                $send_method = isset( $_POST['send_method'] )
                    ? sanitize_text_field( $_POST['send_method'] )
                    : 'parcel_machine';

                $reference_number = isset( $_POST['reference_number'] )
                    ? sanitize_text_field( $_POST['reference_number'] )
                    : $order_id;

                $parcels = isset( $_POST['parcels'] )
                    ? array_map( 'sanitize_text_field', $_POST['parcels'] )
                    : array( get_option( 'easypack_default_package_size' ) );
            }

            $shipment = $shipmentService->create_shipment_object_by_shiping_data(
                $parcels,
                (int) $order_id,
                $send_method,
				self::SERVICE_ID,
				[],
                $parcel_machine_id,
				null,
				$insurance_amount,
                $reference_number,
                null
			);
			$shipment->getInternalData()->setOrderId( (int) $order_id );

			return $shipment;
		}

		public static function ajax_cancel_package() {

			$ret              = [ 'status' => 'ok', 'message' => '' ];
			$order_id         = sanitize_text_field( $_POST['order_id'] );
			$order            = wc_get_order( $order_id );
			$post             = get_post( $order_id );
			$shipment_service = EasyPack::EasyPack()->get_shipment_service();
			$shipment         = $shipment_service->get_shipment_by_order_id( $order_id );

			try {
				$cancelled_parcel = EasyPack_API()->customer_parcel_cancel( $shipment->getInternalData()->getInpostId() );

			} catch ( Exception $e ) {
				$ret['status']  = 'error';
				$ret['message'] .= $e->getMessage();
			}

			$status_srv = EasyPack()->get_shipment_status_service();
			$status_srv->refreshStatus( $shipment );
			if ( $ret['status'] === 'ok' ) {

				$order->add_order_note( __( 'Shipment canceled', 'woocommerce-inpost' ), false );
				$ret['content'] = self::order_metabox_content( $post, false );
			}
			echo json_encode( $ret );
			wp_die();
		}

		public static function get_stickers() {


			$nonce = $_GET['security'];
			if ( ! wp_verify_nonce( $nonce, 'easypack_nonce' ) ) {
				echo __( 'Security check - bad nonce!', 'woocommerce-inpost' );

				return;
			}

			$order_id = sanitize_text_field( $_GET['order_id'] );
			$order    = wc_get_order( $order_id );
			$post     = get_post( $order_id );

			$status = get_post_meta( $order_id, '_easypack_status', true );

			$easypack_parcels = get_post_meta( $order_id, '_easypack_parcels', true );


			$stickers = [];

			if ( $easypack_parcels ) {
				foreach ( $easypack_parcels as $key => $parcel ) {
					try {
						$easypack_data = EasyPack_API()->customer_parcel( $parcel['easypack_data']['id'] );
						if ( $parcel['easypack_data']['status'] != $easypack_data['status'] ) {
							$parcel['easypack_data']  = $easypack_data;
							$easypack_parcels[ $key ] = $parcel;
							update_post_meta( $order_id, '_easypack_parcels', $easypack_parcels );
						}
						if ( $parcel['easypack_data']['status'] == 'created' ) {
							$easypack_data = EasyPack_API()->customer_parcel_pay( $parcel['easypack_data']['id'] );
							$easypack_parcels[ $key ]['easypack_data'] = $easypack_data;
							update_post_meta( $order_id, '_easypack_parcels', $easypack_parcels );
						}
						$stickers[] = EasyPack_API()->customer_parcel_sticker( $parcel['easypack_data']['id'] );
					} catch ( Exception $e ) {
						echo $e->getMessage();

						return;
					}
				}
			}

			$file = EasyPack_Helper()->write_stickers_to_file( $stickers );
			if ( $status == 'created' ) {
				update_post_meta( $order_id, '_easypack_status', 'prepared' );
			}
			EasyPack_Helper()->get_file( $file,
				__( 'stickers', 'woocommerce-inpost' ) . '_'
				. $order->get_id()
				. '.pdf', 'application/pdf' );
		}

		function woocommerce_cart_shipping_method_full_label( $label, $method ) {

			if ( in_array( $this->id, self::$prevent_duplicate ) ) {
				return $label;
			}

			if ( $method->id === $this->id ) {

				if ( ! ( $method->cost > 0 ) ) {
					$label .= ': ' . wc_price( 0 );
				}
				self::$prevent_duplicate[] = $this->id;

				return $label;
			}


			return $label;
		}

        function woocommerce_order_shipping_to_display_shipped_via( $via, $order ) {

            if ( self::$logo_printed === 1 ) {
                return $via;
            }

            $shipping_method_id = '';

            foreach( $order->get_items( 'shipping' ) as $item_id => $item ) {
                $shipping_method_id = $item->get_method_id();
            }

            if( $shipping_method_id === 'easypack_parcel_machines_weekend' ) {
                $img = ' <span class="easypack-shipping-method-logo" 
                               style="display: inline;">
                               <img style="max-width: 100px; max-height: 40px;	display: inline; border:none;" src="'
                    . EasyPack()->getPluginImages()
                    . 'logo/inpost-paczka-w-weekend.png" />
                         <span>';
                $via .= $img;
                self::$logo_printed = 1;

            } else {

                if ( $order->has_shipping_method( $this->id ) ) {
                    $img = ' <span class="easypack-shipping-method-logo" 
                               style="display: inline;">
                               <img style="max-width: 100px; max-height: 40px;	display: inline; border:none;" src="'
                        . EasyPack()->getPluginImages()
                        . 'logo/small/white.png" />
                         <span>';
                    $via .= $img;
                    self::$logo_printed = 1;
                }
            }

            return $via;
        }

		/**
		 * @param $shipping
		 * @param $order
		 * @param $tax_display
		 *
		 * @return mixed|string
		 */
		public function woocommerce_order_shipping_to_display( $shipping, $order, $tax_display ) {
			if ( $order->has_shipping_method( $this->id ) ) {
				if ( ! ( 0 < abs( (float) $order->get_shipping_total() ) ) && $order->get_shipping_method() ) {
					if( ! stripos( $shipping, ':' ) ) {
						$shipping .= ': ' . wc_price( 0 );
					}
				}

				return $shipping;
			}

			return $shipping;
		}

		function woocommerce_my_account_my_orders_actions( $actions, $order ) {
			if ( $order->has_shipping_method( $this->id ) ) {
				$status = get_post_meta( $order->get_id(), '_easypack_status', true );

				$tracking_url = false;
				$fast_returns = get_option('easypack_fast_return');

                if ( $status != 'new' ) {
					$tracking_url = EasyPack_Helper()->get_tracking_url();
					$tracking_number = get_post_meta( $order->get_id(), '_easypack_parcel_tracking', true );
					$tracking_url = trim( $tracking_url, ',' );
				}

				if ( $tracking_number ) {
					$actions['easypack_tracking'] = [
						'url'  =>  esc_url( $tracking_url . $tracking_number ) ,
						'name' => __( 'Track shipment', 'woocommerce-inpost' ),
					];
				}

                if ( !empty( $fast_returns ) ) {
                    $actions['fast_return'] = [
                        'url' => get_option('easypack_fast_return'),
                        'name' => __('Szybkie zwroty', 'woocommerce-inpost'),
                    ];
                }
			}

			return $actions;
		}

		/**
		 * @return ShipX_Shipment_Service
		 */
		public function getShipmentService() {
			return $this->shipment_service;
		}

		/**
		 * @param ShipX_Shipment_Service $shipment_service
		 */
		public function setShipmentService( $shipment_service ) {
			$this->shipment_service = $shipment_service;
		}

		/**
		 * @return bool
		 */
		protected function is_method_courier() {
			return $this->id === 'easypack_shipping_courier'
			       || $this->id === 'easypack_cod_shipping_courier'
			       || $this->id === 'easypack_shipping_courier_c2c'
			       || $this->id === 'easypack_shipping_courier_c2c_cod'
			       || $this->id === 'easypack_shipping_courier_lse'
			       || $this->id === 'easypack_shipping_courier_local_standard'
			       || $this->id === 'easypack_shipping_courier_local_express'
			       || $this->id === 'easypack_shipping_courier_palette'
			       || $this->id === 'easypack_shipping_courier_lse_cod'
			       || $this->id === 'easypack_shipping_courier_local_standard_cod'
			       || $this->id === 'easypack_shipping_courier_local_express_cod'
			       || $this->id === 'easypack_shipping_courier_palette_cod';
		}

		/**
		 * @param int $wc_order_id
		 *
		 * @return ShipX_Shipment_Parcel_Dimensions_Model
		 */
		protected static function get_single_product_dimensions( int $wc_order_id ): ShipX_Shipment_Parcel_Dimensions_Model {
			$order = wc_get_order( $wc_order_id );

			$items = $order->get_items();

			if ( count( $items ) > 1 ) {
				return new ShipX_Shipment_Parcel_Dimensions_Model();
			}

			foreach ( $order->get_items() as $item_id => $item ) {
				$product_id = $item->get_product_id();
				$product    = wc_get_product( $product_id );

				if ( $item->get_quantity() > 1 ) {
					return new ShipX_Shipment_Parcel_Dimensions_Model();
				}

				$height = (float) $product->get_height();
				$width  = (float) $product->get_width();
				$length = (float) $product->get_length();

				if ( $height > 0 || $width > 0 || $length > 0 ) {
					$dims = new ShipX_Shipment_Parcel_Dimensions_Model();
					$dims->setHeight(
						$height * 10
					);
					$dims->setWidth(
						$width * 10
					);
					$dims->setLength(
						$length * 10
					);
					$dims->setUnit( 'mm' );

					return $dims;
				}


			}

			return new ShipX_Shipment_Parcel_Dimensions_Model();
		}

        /**
         * Get max parcel size among the products in cart
         */
        public function get_max_gabaryt( $package ) {

            if ( isset( $package['contents'] ) && ! empty( $package['contents'] ) )  {

                $possible_gabaryts = array();

                foreach ( $package['contents'] as $cart_item_key => $cart_item ) {
                    $product_id = $cart_item['product_id'];
                    $possible_gabaryts[] = get_post_meta( $product_id, EasyPack::ATTRIBUTE_PREFIX . '_parcel_dimensions', true );

                }

                if ( ! empty( $possible_gabaryts ) ) {
                    if ( in_array('large', $possible_gabaryts ) ) {
                        return 'c';
                    }
                    if ( in_array('medium', $possible_gabaryts ) ) {
                        return 'b';
                    }

                }
            }
            // by default
            return 'a';

        }
		
		
		public function settings_block_for_flexible_shipping() {
            $settings = [];
            if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                $settings['fs_inpost_pl_method'] = [
                    'title' => esc_html__("Integration with 'InPost PL' plugin", 'woocommerce-inpost'),
                    'type' => 'select',
                    'default' => 'all',
                    'options' => [
                        '0' => __('None', ''),
                        'easypack_parcel_machines' => __('InPost Locker 24/7', 'woocommerce-inpost'),
                        'easypack_parcel_machines_cod' => __('InPost Locker 24/7 COD', 'woocommerce-inpost'),
                        'easypack_shipping_courier_c2c' => __('InPost Courier C2C', 'woocommerce-inpost'),
                        'easypack_shipping_courier_c2c_cod' => __('InPost Courier C2C COD', 'woocommerce-inpost'),
                        'easypack_parcel_machines_weekend' => __('InPost Locker Weekend', 'woocommerce-inpost'),
                        'easypack_shipping_courier' => __('InPost Courier', 'woocommerce-inpost'),
                        'easypack_cod_shipping_courier' => __('InPost Courier COD', 'woocommerce-inpost'),
                        'easypack_shipping_courier_local_express' => __('InPost Courier Local Express', 'woocommerce-inpost'),
                        'easypack_shipping_courier_le_cod' => __('InPost Courier Local Express COD', 'woocommerce-inpost'),
                        'easypack_shipping_courier_local_standard' => __('InPost Courier Local Standard', 'woocommerce-inpost'),
                        'easypack_shipping_courier_local_standard_cod' => __('InPost Courier Local Standard COD', 'woocommerce-inpost'),
                        'easypack_shipping_courier_lse' => __('InPost Courier Local Super Express', 'woocommerce-inpost'),
                        'easypack_shipping_courier_lse_cod' => __('InPost Courier Local Super Express COD', 'woocommerce-inpost'),
                        'easypack_shipping_courier_palette' => __('InPost Courier Palette', 'woocommerce-inpost'),
                        'easypack_shipping_courier_palette_cod' => __('InPost Courier Palette COD', 'woocommerce-inpost'),

                    ],
                ];

                $settings['fs_inpost_pl_weekend_day_from'] = [
                    'title'   => __( 'Available from day of week', 'woocommerce' ),
                    'type'    => 'select',
                    'class'   => 'wc-enhanced-select fs-inpost-pl-weekend',
                    'default' => '4',
                    'options' => [
                        '1' => __( 'Monday', 'woocommerce-inpost' ),
                        '2' => __( 'Tuesday', 'woocommerce-inpost' ),
                        '3' => __( 'Wednesday', 'woocommerce-inpost' ),
                        '4' => __( 'Thursday', 'woocommerce-inpost' ),
                        '5' => __( 'Friday', 'woocommerce-inpost' ),
                        '6' => __( 'Saturday', 'woocommerce-inpost' ),
                    ],
                ];


                $settings['fs_inpost_pl_weekend_hour_from'] = [
                    'title'    => __( 'Available from hour', 'woocommerce-inpost' ),
                    'type'     => 'time',
                    'default'  => '',
                    'desc_tip' => false,
                    'class'   => 'fs-inpost-pl-weekend',
                ];

                $settings['fs_inpost_pl_weekend_day_to'] = [
                    'title'   => __( 'Available to day of week', 'woocommerce' ),
                    'type'    => 'select',
                    'class'   => 'wc-enhanced-select fs-inpost-pl-weekend',
                    'default' => '5',
                    'options' => [
                        '1' => __( 'Monday', 'woocommerce-inpost' ),
                        '2' => __( 'Tuesday', 'woocommerce-inpost' ),
                        '3' => __( 'Wednesday', 'woocommerce-inpost' ),
                        '4' => __( 'Thursday', 'woocommerce-inpost' ),
                        '5' => __( 'Friday', 'woocommerce-inpost' ),
                        '6' => __( 'Saturday', 'woocommerce-inpost' ),
                    ],
                ];

                $settings['fs_inpost_pl_weekend_hour_to'] = [
                    'title'    => __( 'Available to hour', 'woocommerce-inpost' ),
                    'type'     => 'time',
                    'default'  => '',
                    'desc_tip' => false,
                    'class'   => 'fs-inpost-pl-weekend',
                ];
            }

            return $settings;
        }
		
		
		/**
         * Add custom fields to each shipping method.
         */
		public function add_settings_to_flexible_shipping() {		    

            if( EasyPack_Helper()->is_flexible_shipping_activated() ) {
                $shipping_methods = WC()->shipping->get_shipping_methods();
                foreach ( $shipping_methods as $shipping_method ) {
                    if( $shipping_method->id === 'flexible_shipping_single' ) {
                        add_filter('woocommerce_shipping_instance_form_fields_' . $shipping_method->id, [$this, 'add_map_field']);
                    }
                }
            }
        }
		
		
		public function add_map_field( $settings ) {

		    $has_intergrations = false;

            $find_key = '';
            $i = 1;
            foreach( $settings as $key => $setting ) {

				// divide fs settings array and put our settings before this field
                if( $key == 'method_integration' ) {
                    $find_key = $i;
                    $has_intergrations = true;
                }
                $i++;
            }
            // show our field near with native integration field
            if( $has_intergrations ) {

                $position = (int) $find_key - 1;

                $settings_begin = array_slice( $settings, 0, $position, true );

                $addtitional_settings = $this->settings_block_for_flexible_shipping();

                $settings_end = array_slice( $settings, $position, count( $settings ) - $position, true);

                $new_settings = array_merge( $settings_begin, $addtitional_settings, $settings_end );

                return $new_settings;

            } else {

                return $settings + $this->settings_block_for_flexible_shipping();
            }


        }


        public function check_paczka_weekend_fs_settings(  $rates, $package  ) {

            foreach ( $rates as $rate_key => $rate ) {
                if ( 'easypack_parcel_machines_weekend' === EasyPack_Helper()->get_method_linked_to_fs_by_instance_id( $rate->instance_id ) ) {

                    $paczka_weekend = new EasyPack_Shipping_Parcel_Machines_Weekend();
                    if( ! $paczka_weekend->check_allowed_interval_for_weekend( $rate->instance_id ) ) {
                        unset( $rates[ $rate_key ] ); // hide Paczka w Weekend if not match into time interval
                    }
                }
            }

            return $rates;
        }

        /**
         * Inline CSS for buttons in My Orders section
         *
         * @return void
         */
        public function add_styles_for_my_orders_page() {
            if( ! empty(get_option('easypack_fast_return') ) ) {
                echo wp_kses( "<style>.woocommerce-button.wp-element-button.button.view {
                      margin-right: 5px;
                      margin-bottom: 5px;
                    }
                    </style>", [ 'style' => [] ] );
            }
        }


        /**
         * Evaluate a cost from a sum/string.
         *
         * @param  string $sum Sum of shipping.
         * @param  array  $args Args, must contain `cost` and `qty` keys. Having `array()` as default is for back compat reasons.
         * @return string
         */
        protected function evaluate_cost( $sum, $args = array() ) {
            // Add warning for subclasses.
            if ( ! is_array( $args ) || ! array_key_exists( 'qty', $args ) || ! array_key_exists( 'cost', $args ) ) {
                wc_doing_it_wrong( __FUNCTION__, '$args must contain `cost` and `qty` keys.', '4.0.1' );
            }

            include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

            // Allow 3rd parties to process shipping cost arguments.
            $args           = apply_filters( 'woocommerce_evaluate_shipping_cost_args', $args, $sum, $this );
            $locale         = localeconv();
            $decimals       = array( wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' );
            $this->fee_cost = $args['cost'];

            // Expand shortcodes.
            add_shortcode( 'fee', array( $this, 'fee' ) );

            $sum = do_shortcode(
                str_replace(
                    array(
                        '[qty]',
                        '[cost]',
                    ),
                    array(
                        $args['qty'],
                        $args['cost'],
                    ),
                    $sum
                )
            );

            remove_shortcode( 'fee', array( $this, 'fee' ) );

            // Remove whitespace from string.
            $sum = preg_replace( '/\s+/', '', $sum );

            // Remove locale from string.
            $sum = str_replace( $decimals, '.', $sum );

            // Trim invalid start/end characters.
            $sum = rtrim( ltrim( $sum, "\t\n\r\0\x0B+*/" ), "\t\n\r\0\x0B+-*/" );

            // Do the math.
            return $sum ? WC_Eval_Math::evaluate( $sum ) : 0;
        }


        /**
         * Get items in package.
         *
         * @param  array $package Package of items from cart.
         * @return int
         */
        public function get_package_item_qty( $package ) {
            $total_quantity = 0;
            foreach ( $package['contents'] as $item_id => $values ) {
                if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
                    $total_quantity += $values['quantity'];
                }
            }
            return $total_quantity;
        }
		
		/**
		 * Finds and returns shipping classes and the products with said class.
		 *
		 * @param mixed $package Package of items from cart.
		 * @return array
		 */
		public function find_shipping_classes( $package ) {
			$found_shipping_classes = array();

			foreach ( $package['contents'] as $item_id => $values ) {
				if ( $values['data']->needs_shipping() ) {
					$found_class = $values['data']->get_shipping_class();

					if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
						$found_shipping_classes[ $found_class ] = array();
					}

					$found_shipping_classes[ $found_class ][ $item_id ] = $values;
				}
			}

			return $found_shipping_classes;
		}
	

        public function add_shipping_classes_settings( $settings) {
            $cost_desc = __( 'Enter a cost (excl. tax) or sum, e.g. <code>10.00 * [qty]</code>.', 'woocommerce' ) . '<br/><br/>' . __( 'Use <code>[qty]</code> for the number of items, <br/><code>[cost]</code> for the total cost of items, and <code>[fee percent="10" min_fee="20" max_fee=""]</code> for percentage based fees.', 'woocommerce' );
            $shipping_classes = WC()->shipping()->get_shipping_classes();
            if ( ! empty( $shipping_classes ) ) {
                $settings['class_costs'] = array(
                    'title'       => __( 'Shipping class costs', 'woocommerce' ),
                    'type'        => 'title',
                    'default'     => '',
                    /* translators: %s: URL for link. */
                    'description' => sprintf( __( 'These costs can optionally be added based on the <a href="%s">product shipping class</a>.', 'woocommerce' ), admin_url( 'admin.php?page=wc-settings&tab=shipping&section=classes' ) ),
                );
                foreach ( $shipping_classes as $shipping_class ) {
                    if ( ! isset( $shipping_class->term_id ) ) {
                        continue;
                    }
                    $settings[ 'class_cost_' . $shipping_class->term_id ] = array(
                        /* translators: %s: shipping class name */
                        'title'             => sprintf( __( '"%s" shipping class cost', 'woocommerce' ), esc_html( $shipping_class->name ) ),
                        'type'              => 'text',
                        'placeholder'       => __( 'N/A', 'woocommerce' ),
                        'description'       => $cost_desc,
                        'default'           => $this->get_option( 'class_cost_' . $shipping_class->slug ), // Before 2.5.0, we used slug here which caused issues with long setting names.
                        'desc_tip'          => true,
                        'sanitize_callback' => array( $this, 'sanitize_cost' ),
                    );
                }

                $settings['no_class_cost'] = array(
                    'title'             => __( 'No shipping class cost', 'woocommerce' ),
                    'type'              => 'text',
                    'placeholder'       => __( 'N/A', 'woocommerce' ),
                    'description'       => $cost_desc,
                    'default'           => '',
                    'desc_tip'          => true,
                    'sanitize_callback' => array( $this, 'sanitize_cost' ),
                );

                $settings['type'] = array(
                    'title'   => __( 'Calculation type', 'woocommerce' ),
                    'type'    => 'select',
                    'class'   => 'wc-enhanced-select',
                    'default' => 'class',
                    'options' => array(
                        'class' => __( 'Per class: Charge shipping for each shipping class individually', 'woocommerce' ),
                        'order' => __( 'Per order: Charge shipping for the most expensive shipping class', 'woocommerce' ),
                    ),
                );
            }

            return $settings;
        }
		
	}


}
