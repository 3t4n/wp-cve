<?php

//Works with WooCommerce 3.2.6
add_action( 'woocommerce_shipping_init', 'autoship_shipping_method_init' );

function autoship_shipping_method_init() {

  class Autoship_FreeShipping_Method extends WC_Shipping_Method {

      /**
       * Constructor
       *
       * @access public
       * @return void
       */
      public function __construct( $instance_id = 0 ) {
          $this->instance_id 	      = absint( $instance_id );
          $this->id                 = 'autoship_free_shipping';
          $this->method_title       = __('Autoship Free Shipping (Not editable)', 'autoship');
          $this->method_description = __('Free shipping for Autoship orders.', 'autoship');

          //add to shipping zones list
          $this->supports = array(
            'shipping-zones',
          );

          $this->init();

          $this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'no';
          $this->title = $this->method_title ;
      }

      /**
       * Init settings
       *
       * @access public
       * @return void
       */
      function init() {

          // Load the settings API
          $this->init_form_fields();
          $this->init_settings();

          // Save settings in admin if any are defined
          // Currently non yet could be extended in future
          add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );

      }

      /**
       * Define settings field for this shipping
       * @return void
       */
      function init_form_fields() {}

      /**
       * This function is used to calculate the shipping cost. Within this function we can check for weights, dimensions and other parameters.
       *
       * @access public
       * @param mixed $package
       * @return void
       */
      public function calculate_shipping( $package = array()) {
        // Register the rate
        $this->add_rate( array(
            'id'      => $this->id,
            'label'   => __("Free Shipping", 'autoship'),
            'cost'    => 0.0,
            'package' => $package,
            'taxes'   => false,
          )
        );
      }

  }

  function add_autoship_shipping_freeshipping_method( $methods ) {
    // Only add the new class if the functionality is enabled.
    $free_shipping_option = get_option( 'autoship_free_shipping' );
    if ( ( 'checkout+autoship' == $free_shipping_option ) )
    $methods['autoship_free_shipping'] = 'Autoship_FreeShipping_Method';
    return $methods;
  }
  add_filter( 'woocommerce_shipping_methods', 'add_autoship_shipping_freeshipping_method' );

}
