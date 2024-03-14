<?php

if (class_exists( 'WC_Shopup_Venipak_Shipping_Courier_Method' )) return;

class WC_Shopup_Venipak_Shipping_Courier_Method extends WC_Shipping_Method {

  /**
   * 
   *
   * @since    1.0.0
   */
  public function __construct($instance_id = 0) {
      $this->id                 = 'shopup_venipak_shipping_courier_method';
      $this->instance_id        = absint($instance_id);
      $this->method_description = __( 'Description of venipak shipping method', 'woocommerce-shopup-venipak-shipping' ); // 
      $this->title              = __( 'Venipak Courier Shipping Method', 'woocommerce-shopup-venipak-shipping' );
      $this->method_title       = __( 'Venipak Courier Shipping Method', 'woocommerce-shopup-venipak-shipping' );
      $this->supports = [
        'shipping-zones',
        'instance-settings',
        'instance-settings-modal'
      ];
      $this->init();
  }

  /**
   * 
   *
   * @since    1.0.0
   */
  function init() {
    $this->instance_form_fields = [
      'title'                        => [
          'title'       => __('Title', 'woocommerce-shopup-venipak-shipping'),
          'type'        => 'text',
          'description' => __('This controls the title which the user sees during checkout.', 'woocommerce-shopup-venipak-shipping'),
          'default'     => __( 'Venipak Courier Shipping Method', 'woocommerce-shopup-venipak-shipping' ),
          'desc_tip'    => true
      ],
      'description'   => [
        'title'       => __('Description', 'woocommerce-shopup-venipak-shipping'),
        'type'        => 'text',
        'description' => __('This controls the description which the user sees during checkout.', 'woocommerce-shopup-venipak-shipping'),
        'default'     => __( 'Delivery in 1-2 business days', 'woocommerce-shopup-venipak-shipping' ),
        'desc_tip'    => true
      ],
      'fee'                          => [
          'title'       => __('Delivery Fee', 'woocommerce-shopup-venipak-shipping'),
          'type'        => 'price',
          'placeholder' => wc_format_localized_price(0)
      ],
      'minimum_weight'               => [
          'title'       => __('Minimum weight', 'woocommerce-shopup-venipak-shipping'),
          'type'        => 'price',
          'placeholder' => wc_format_localized_price(0),
          'description' => __('Kilograms', 'woocommerce-shopup-venipak-shipping'),
          'desc_tip'    => true
      ],
      'maximum_weight'               => [
          'title'       => __('Maximum weight', 'woocommerce-shopup-venipak-shipping'),
          'type'        => 'price',
          'placeholder' => wc_format_localized_price(0),
          'description' => __('Kilograms', 'woocommerce-shopup-venipak-shipping'),
          'desc_tip'    => true
      ],
      'min_amount_for_free_shipping' => [
          'title'       => __('Minimum Order Amount For Free Shipping', 'woocommerce-shopup-venipak-shipping'),
          'type'        => 'price',
          'placeholder' => wc_format_localized_price(0),
          'description' => __('Users will need to spend this amount to get free shipping (if enabled above).', 'woocommerce-shopup-venipak-shipping'),
          'desc_tip'    => true
      ],
    ];

    $shipping_classes = $this->get_shipping_classes();

    if ( ! empty($shipping_classes)) {
      $prepared_shipping_classes = [];

      foreach ($shipping_classes as $shipping_class) {
        $prepared_shipping_classes[$shipping_class->term_id] = $shipping_class->name;
      }

      $this->instance_form_fields = array_merge($this->instance_form_fields, [
          'ignore_shipping_classes' => [
            'title'       => __('Disable this method for these shipping classes', 'woocommerce-shopup-venipak-shipping'),
            'type'        => 'multiselect',
            'default'     => null,
            'description' => __('If at least one has product has selected shipping classes it will disable this shipping method',
                'woocommerce-shopup-venipak-shipping'),
            'desc_tip'    => true,
            'options'     => $prepared_shipping_classes
          ],
        ]
      );
    }


    // Load the settings API
    $this->init_form_fields(); // This is part of the settings API. Override the method to add your own settings
    $this->init_settings(); // This is part of the settings API. Loads settings you previously init.

    // Save settings in admin if you have any defined
    add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
    $this->title = $this->get_option('title');
    $this->method_description = $this->build_method_description();
  }

  /**
   * 
   *
   * @since    1.14.6
   */
  public function build_method_description() {
    $content = __('Description', 'woocommerce-shopup-venipak-shipping') . ': <b>' .$this->get_option('description') . '</b><br />';
    $content .= __('Delivery Fee', 'woocommerce-shopup-venipak-shipping') . ': <b>' . $this->get_option('fee') . '</b><br />';
    $content .= __('Minimum Order Amount For Free Shipping', 'woocommerce-shopup-venipak-shipping') . ': <b>' . $this->get_option('min_amount_for_free_shipping') . '</b><br />';
    $content .= __('Minimum weight', 'woocommerce-shopup-venipak-shipping') . ': <b>' . $this->get_option('minimum_weight') . '</b><br />';
    $content .= __('Maximum weight', 'woocommerce-shopup-venipak-shipping') . ': <b>' . $this->get_option('maximum_weight') . '</b><br />';

    return $content;
  }

  /**
   * 
   *
   * @since    1.0.0
   */
  public function calculate_shipping( $package = array() ) {
    $rate = [
        'id'      => $this->get_rate_id(),
        'label'   => $this->get_option('title'),
        'cost'    => $this->get_option('fee'),
        'package' => $package
    ];

    $shipping_classes = $this->get_shipping_classes();

    if ( ! empty($shipping_classes)) {
      $ignored_classes = $this->get_option('ignore_shipping_classes', []);

      if ( ! is_array($ignored_classes)) {
        $ignored_classes = [];
      }

      $found_shipping_classes = $this->find_shipping_classes_for_package($package);

      foreach ($found_shipping_classes as $found_class) {
        if (in_array($found_class, $ignored_classes)) {
          return;
        }
      }
    }

    $cart_total_weight = WC()->cart->cart_contents_weight;
    if (get_option('woocommerce_weight_unit') == 'g') {
        $cart_total_weight /= 1000;
    }

    $minimum_weight = (float)$this->get_option('minimum_weight');
    $maximum_weight = (float)$this->get_option('maximum_weight');

    if (
      ($maximum_weight > 0 && !$minimum_weight && $cart_total_weight < $maximum_weight) ||
      ($minimum_weight >= 0 && !$maximum_weight && $cart_total_weight >= $minimum_weight) ||
      ($cart_total_weight < $maximum_weight && $cart_total_weight >= $minimum_weight)
    ) {
      $this->free_shipping_check( $package, $rate );
      $this->add_rate( $rate );
    }
  }

  /**
   * 
   *
   * @since    1.0.0
   */
  public function free_shipping_check($package, &$rate) {
    $order_cost = 0;
    $min_amount_for_free_shipping = $this->get_option('min_amount_for_free_shipping');

    if ($min_amount_for_free_shipping > 0) {
      $order_cost = WC()->cart->get_cart_contents_total() + WC()->cart->get_taxes_total();

      if ($order_cost >= $min_amount_for_free_shipping) {
        $rate['cost'] = 0;
      }
    }

    $applied_coupons = WC()->cart->get_applied_coupons();
    foreach( $applied_coupons as $coupon_code ){
      $coupon = new WC_Coupon($coupon_code);
      if ($coupon->get_free_shipping()) {
        $rate['cost'] = 0;
      }
    }
  }

  /**
   * 
   *
   * @since    1.0.0
   */
  public function get_shipping_classes() {
    global $wpdb;
    return $wpdb->get_results( "
        SELECT * FROM {$wpdb->prefix}terms as t
        INNER JOIN {$wpdb->prefix}term_taxonomy as tt ON t.term_id = tt.term_id
        WHERE tt.taxonomy LIKE 'product_shipping_class'
    " );
  }

  /**
   * 
   *
   * @since    1.0.0
   */
  public function find_shipping_classes_for_package( $package ) {
    $found_shipping_classes = array();

    foreach ( $package['contents'] as $item_id => $values ) {
      if ( $values['data']->needs_shipping() ) {
          $found_class = $values['data']->get_shipping_class_id();

        if ( ! isset( $found_shipping_classes[ $found_class ] ) ) {
            $found_shipping_classes[ $found_class ] = array();
        }

        $found_shipping_classes[ $found_class ] = $found_class;
      }
    }

    return $found_shipping_classes;
  }
}