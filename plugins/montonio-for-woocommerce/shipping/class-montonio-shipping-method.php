<?php
defined('ABSPATH') or exit;

abstract class Montonio_Shipping_Method extends WC_Shipping_Method {

    /**
	 * Shipping instance ID
	 *
	 * @param int
	 */
    public $instance_id;

    /**
	 * Shipping rate ID
	 *
	 * @var string
	 */
    public $id;

    /**
	 * Shipping method title
	 *
	 * @var string
	 */
    public $title;
    
    /**
	 * Shipping provider logo
	 *
	 * @var string
	 */
    public $logo;

    /**
	 * Should we add free shipping rate text?
	 *
	 * @var bool
	 */
    public $enable_free_shipping_text;

    /**
	 * Free shipping rate text to include in title
	 *
	 * @var string
	 */
    public $free_shipping_text;

    /**
	 * Shipping method cost.
	 *
	 * @var string
	 */
	public $cost;

    /**
	 * Shipping method type.
	 *
	 * @var string
	 */
	public $type;

    /**
	 * Shipping provider name
	 *
	 * @var string
	 */
    protected $provider_name;

    /**
	 * Cost passed to [fee] shortcode.
	 *
	 * @var string Cost.
	 */
	protected $fee_cost = '';

    /**
	 * Constructor
	 */
    public function __construct( $instance_id = 0 ) {
        $this->instance_id = absint( $instance_id );

        $this->enable_free_shipping_text = $this->get_option( 'enable_free_shipping_text' );
        $this->free_shipping_text = $this->get_option( 'free_shipping_text' );

        $this->init_settings();
        $this->init_form_fields();

        add_action( 'woocommerce_update_options_shipping_' . $this->id, [ $this, 'process_admin_options' ] );

        $this->init();
    }

    /**
	 * Initialise Settings.
	 */
    public function init_form_fields() {
        $this->instance_form_fields = require WC_MONTONIO_PLUGIN_PATH . '/shipping/class-montonio-shipping-method-settings.php';
    }

    /**
	 * Check if the shipping method is available for use.
	 *
	 * @return bool
	 */
    public function is_available( $package ) {
        
        if ( ! $this->is_enabled() ) {
            return false;
        }

        if ( $this->get_option( 'enablePackageMeasurementsCheck' ) === 'yes' ) {

            if ( $this->get_option( 'hideWhenNoMeasurements' ) === 'yes' && $this->check_if_measurements_missing( $package ) ) {
                return false;
            }

            if ( $this->get_cart_contents_weight( $package ) > $this->get_option( 'maximumWeight', $this->default_max_weight ) ) {
                return false;
            }

            if ( ! $this->validate_package_dimensions( $package ) ) {
                return false;
            }
        }

        return apply_filters( 'woocommerce_shipping_' . $this->id . '_is_available', true, $package );
    }

    /**
     * Get cart contents total weight in kg
     * 
     * @return float|int
     */
    public static function get_cart_contents_weight() {
        $cart_contents_weight = WC()->cart->cart_contents_weight;

        switch ( get_option( 'woocommerce_weight_unit' ) ) {
            case 'g':
                $multiplier = 0.001;
                break;
            case 'lbs':
                $multiplier = 0.45;
                break;
            case 'oz':
                $multiplier = 0.028;
                break;
            default:
                $multiplier = 1;
                break;
        }

        return $cart_contents_weight * $multiplier;
    }

    /**
     * Assemble the dimensions of the package in cm
     * 
     * @return array
     */
    protected function get_package_dimensions( $package ) {
        $package_dimensions = [0, 0, 0];

        switch ( get_option( 'woocommerce_dimension_unit' ) ) {
            case 'm':
                $multiplier = 100;
                break;
            case 'mm':
                $multiplier = 0.1;
                break;
            case 'in':
                $multiplier = 2.54;
                break;
            case 'yd':
                $multiplier = 91.44;
                break;
            default:
                $multiplier = 1;
                break;
        }

        foreach( $package['contents'] as $item ) {
            $item_dimensions = [];
            $item_dimensions[] = (float) $item['data']->get_length() * $multiplier;
            $item_dimensions[] = (float) $item['data']->get_width() * $multiplier;
            $item_dimensions[] = (float) $item['data']->get_height() * $multiplier;

            // Sort from smallest to largest dimension
            sort( $item_dimensions );

            if ( $item_dimensions[0] > $package_dimensions[0] ) {
                $package_dimensions[0] = $item_dimensions[0];
            }

            if ( $item_dimensions[1] > $package_dimensions[1] ) {
                $package_dimensions[1] = $item_dimensions[1];
            }

            if ( $item_dimensions[2] > $package_dimensions[2] ) {
                $package_dimensions[2] = $item_dimensions[2];
            }
        }

        return $package_dimensions;
    }

    /**
     * Check if all measurements added
     * 
     * @return bool
     */
    protected function check_if_measurements_missing( $package ) {
        foreach ( $package['contents'] as $item ) {
            if ( ! (float) $item['data']->get_length() || ! (float) $item['data']->get_width() || ! (float) $item['data']->get_height() || ! (float) $item['data']->get_weight() ) {
                return true;
            }
        }

        return false;
    }

    /**
     * Called when doing shipping rate calculations
     * 
     * @return void
     */
    public function calculate_shipping( $package = [] ) {
        if ( get_option( 'montonio_shipping_enabled', 'no' ) !== 'yes' ) {
            return;
        }

        $rate = [
            'id'        => $this->get_rate_id(),
            'label'     => $this->title,
            'taxes'     => $this->get_option( 'tax_status' ) == 'none' ? false : '',
            'calc_tax'  => 'per_order',
            'cost'      => 0,
            'package'   => $package,
            'meta_data' => [
                'provider_name'              => $this->provider_name,
                'type'                       => $this->type,
                'method_class_name'          => get_class( $this ),
                'instance_id'                => $this->instance_id
            ]
        ];

		// Calculate the costs
        $cost             = $this->get_option( 'price' );
        $cart_total       = WC()->cart->get_cart_contents_total() + WC()->cart->get_taxes_total() - WC()->cart->get_shipping_tax();
        $package_item_qty = $this->get_package_item_qty( $package );

        if ( '' !== $cost ) {
			$rate['cost'] = $this->evaluate_cost(
				$cost,
				[
					'qty'  => $this->get_package_item_qty( $package ),
					'cost' => $package['contents_cost'],
				]
			);
		}

        // Add shipping class costs
		$shipping_classes = WC()->shipping()->get_shipping_classes();

        if ( ! empty( $shipping_classes ) ) {
			$found_shipping_classes = $this->find_shipping_classes( $package );
			$calculation_type       = $this->get_option( 'type', 'class' );
			$highest_class_cost     = 0;

            foreach ( $found_shipping_classes as $shipping_class => $products ) {
				// Also handles BW compatibility when slugs were used instead of ids.
				$shipping_class_term = get_term_by( 'slug', $shipping_class, 'product_shipping_class' );
				$class_cost_string   = $shipping_class_term && $shipping_class_term->term_id ? $this->get_option( 'class_cost_' . $shipping_class_term->term_id, $this->get_option( 'class_cost_' . $shipping_class, '' ) ) : $this->get_option( 'no_class_cost', '' );

				if ( '' === $class_cost_string ) {
					continue;
				}

				$class_cost = $this->evaluate_cost(
					$class_cost_string,
					[
						'qty'  => array_sum( wp_list_pluck( $products, 'quantity' ) ),
					    'cost' => array_sum( wp_list_pluck( $products, 'line_total' ) ),
                    ]
				);

				if ( 'class' === $calculation_type ) {
					$rate['cost'] += $class_cost;
				} else {
					$highest_class_cost = $class_cost > $highest_class_cost ? $class_cost : $highest_class_cost;
				}
			}

			if ( 'order' === $calculation_type && $highest_class_cost ) {
				$rate['cost'] += $highest_class_cost;
			}
        }
        
        if ( $this->get_option( 'enableFreeShippingThreshold' ) === 'yes' && (float) $cart_total > (float) $this->get_option( 'freeShippingThreshold' ) ) {
            $rate['cost'] = 0;
        }
        
        if ( $this->get_option( 'enableFreeShippingQty' ) === 'yes' && (float) $package_item_qty >= (float) $this->get_option( 'freeShippingQty' ) ) {
            $rate['cost'] = 0;
        }

        // Check for free shipping coupon
        $applied_coupons = $package['applied_coupons'];
        foreach ( $applied_coupons as $applied_coupon ) {
            $coupon = new WC_Coupon( $applied_coupon );

            if ( $coupon->get_free_shipping() ) {
                $rate['cost'] = 0;
                break;
            }
        }
        
		$this->add_rate( $rate );
    }

    /**
	 * Get package total including taxes.
	 *
	 * @param  array $package Package of items from cart.
	 * @return int
	 */
    protected function get_cart_total( $package ) {
        $total = 0;
        foreach ( $package['contents'] as $item_id => $values ) {
            $total += (float) $values['line_total'] + (float) $values['line_tax'];
        }

        return $total;
    }

    /**
	 * Get items in package.
	 *
	 * @param  array $package Package of items from cart.
	 * @return int
	 */
    protected function get_package_item_qty( $package ) {
        $quantity = 0;
        foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['quantity'] > 0 && $values['data']->needs_shipping() ) {
				$quantity += $values['quantity'];
			}
		}

        return $quantity;
    }

    /**
	 * Finds and returns shipping classes and the products with said class.
	 *
	 * @param mixed $package Package of items from cart.
	 * @return array
	 */
	public function find_shipping_classes( $package ) {
		$found_shipping_classes = [];

		foreach ( $package['contents'] as $item_id => $values ) {
			if ( $values['data']->needs_shipping() ) {
				$found_class = $values['data']->get_shipping_class();

				if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
					$found_shipping_classes[ $found_class ] = [];
				}

				$found_shipping_classes[ $found_class ][ $item_id ] = $values;
			}
		}

		return $found_shipping_classes;
	}

    /**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param  string $sum Sum of shipping.
	 * @param  array  $args Args, must contain `cost` and `qty` keys. Having `array()` as default is for back compat reasons.
	 * @return string
	 */
	protected function evaluate_cost( $sum, $args = [] ) {
		// Add warning for subclasses.
		if ( ! is_array( $args ) || ! array_key_exists( 'qty', $args ) || ! array_key_exists( 'cost', $args ) ) {
			wc_doing_it_wrong( __FUNCTION__, '$args must contain `cost` and `qty` keys.', '4.0.1' );
		}

		include_once WC()->plugin_path() . '/includes/libraries/class-wc-eval-math.php';

		// Allow 3rd parties to process shipping cost arguments.
		$args           = apply_filters( 'wc_montonio_evaluate_shipping_cost_args', $args, $sum, $this );
		$locale         = localeconv();
		$decimals       = [ wc_get_price_decimal_separator(), $locale['decimal_point'], $locale['mon_decimal_point'], ',' ];
		$this->fee_cost = $args['cost'];

		// Expand shortcodes.
		add_shortcode( 'fee', [ $this, 'fee' ] );

		$sum = do_shortcode(
			str_replace(
				[
					'[qty]',
					'[cost]',
                ],
				[
					$args['qty'],
					$args['cost'],
                ],
				$sum
			)
		);

		remove_shortcode( 'fee', [ $this, 'fee' ] );

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
	 * Work out fee (shortcode).
	 *
	 * @param  array $atts Attributes.
	 * @return string
	 */
	public function fee( $atts ) {
		$atts = shortcode_atts(
			[
				'percent' => '',
				'min_fee' => '',
				'max_fee' => '',
            ],
			$atts,
			'fee'
		);

		$calculated_fee = 0;

		if ( $atts['percent'] ) {
			$calculated_fee = $this->fee_cost * ( floatval( $atts['percent'] ) / 100 );
		}

		if ( $atts['min_fee'] && $calculated_fee < $atts['min_fee'] ) {
			$calculated_fee = $atts['min_fee'];
		}

		if ( $atts['max_fee'] && $calculated_fee > $atts['max_fee'] ) {
			$calculated_fee = $atts['max_fee'];
		}

		return $calculated_fee;
	}
    
	/**
	 * Sanitize the cost field.
	 *
	 * @param string $value Unsanitized value.
	 * @throws Exception Last error triggered.
	 * @return string
	 */
	public function sanitize_cost( $value ) {
		$value = is_null( $value ) ? '' : $value;
		$value = wp_kses_post( trim( wp_unslash( $value ) ) );
		$value = str_replace( [ get_woocommerce_currency_symbol(), html_entity_decode( get_woocommerce_currency_symbol() ) ], '', $value );
		
        // Thrown an error on the front end if the evaluate_cost will fail.
		$dummy_cost = $this->evaluate_cost(
			$value,
			[
				'cost' => 1,
				'qty'  => 1,
            ]
		);

		if ( false === $dummy_cost ) {
			throw new Exception( WC_Eval_Math::$last_error );
		}

		return $value;
	}
}