<?php
if (!defined('ABSPATH'))
    exit;

class AWCDP_Front_End
{

    private static $_instance = null;

    public $_version;

    /**
     * The token.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_token;
    /**
     * The plugin assets URL.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $assets_url;
    /**
     * The main plugin file.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $file;
	public $has_payment_plans;

    function __construct($file = '', $version = '1.0.0') {

        $this->_version = $version;
        $this->_token = AWCDP_TOKEN;

        /**
         * Check if WooCommerce is active
         * */
        if ($this->check_woocommerce_active()) {


            $this->file = $file;

            $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));
            AWCDP_Deposits::instance();

            add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_scripts'), 15);
            add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_styles'), 10, 1);

            add_action( 'init', array($this, 'awcdp_register_shortcodes') );

            add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'awcdp_get_deposit_container' ), 999 );
            add_filter('woocommerce_add_cart_item_data', array($this, 'awcdp_add_cart_item_data'), 10, 3);
            add_action('woocommerce_cart_totals_after_order_total', array($this, 'awcdp_cart_totals_after_order_total'));
            add_filter('woocommerce_get_item_data', array($this, 'awcdp_get_item_data'), 10, 2);
            add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'awcdp_display_item_subtotal' ), 10, 3 );
            add_action('woocommerce_add_to_cart',array($this,'awcdp_save_original_price'));

            add_action('woocommerce_cart_loaded_from_session', array($this, 'awcdp_cart_loaded_from_session'));
            add_filter('woocommerce_get_cart_item_from_session', array($this, 'awcdp_get_cart_item_from_session'), 10, 2);

            add_filter('woocommerce_cart_needs_payment', array($this, 'awcdp_cart_needs_payment'), 10, 2);
            add_filter('woocommerce_calculated_total', array($this, 'awcdp_calculated_total'), 99999, 2);

            add_action('woocommerce_checkout_create_order_line_item', array($this, 'awcdp_checkout_create_order_line_item'), 10, 4);
            add_action('woocommerce_checkout_update_order_meta', array($this, 'awcdp_checkout_update_order_meta'), 10, 2);
            add_action('woocommerce_review_order_after_order_total', array($this, 'awcdp_review_order_after_order_total'));

            add_filter('woocommerce_available_payment_gateways', array($this, 'awcdp_available_payment_gateways'));

            // * // * // Payment complete events

            add_action('woocommerce_order_status_completed', array($this, 'awcdp_order_status_completed'), 9);
            add_action('woocommerce_order_status_processing', array($this, 'awcdp_complete_partial_payments'));
            add_action('woocommerce_order_status_partially-paid', array($this, 'awcdp_early_update_partial_payments'), 0);
            add_filter('woocommerce_payment_complete_reduce_order_stock', array($this, 'awcdp_payment_complete_reduce_order_stock'), 10, 2);

	          add_action('woocommerce_order_status_partially-paid', 'wc_update_coupon_usage_counts');
            // * // * // Order status

            add_filter('wc_order_statuses', array($this, 'awcdp_order_statuses'));
            //add_filter('wc_order_is_editable', array($this, 'awcdp_order_is_editable'), 10, 2);
            add_filter('woocommerce_valid_order_statuses_for_payment_complete', array($this, 'awcdp_valid_order_statuses_for_payment_complete'), 10, 2);
            add_filter('woocommerce_order_has_status', array($this, 'awcdp_order_has_status'), 10, 3);
            add_action('woocommerce_order_status_changed', array($this, 'awcdp_order_status_changed'), 10, 3);
            add_filter('woocommerce_order_needs_payment', array($this, 'awcdp_needs_payment'), 10, 3);
            add_action('before_woocommerce_pay', array($this, 'awcdp_redirect_payment_links'));

            add_action('woocommerce_new_order_item', array($this, 'awcdp_add_order_item_meta'), 10, 3);
            add_filter('woocommerce_order_formatted_line_subtotal', array($this, 'awcdp_order_formatted_line_subtotal'), 10, 3);

            add_filter('woocommerce_payment_complete_order_status', array($this, 'awcdp_payment_complete_order_status'), 10, 2);

            add_filter('woocommerce_get_order_item_totals', array($this, 'awcdp_get_order_item_totals'), 10, 2);
            add_filter('woocommerce_hidden_order_itemmeta', array($this, 'awcdp_hidden_order_item_meta'));

            add_filter('woocommerce_get_checkout_payment_url', array($this, 'awcdp_checkout_payment_url'), 10, 2);

            // add_filter('woocommerce_create_order', array($this, 'awcdp_create_order'), 10, 2);
            add_action('woocommerce_create_order', array($this, 'awcdp_create_order'), 99, 2);

            add_action('woocommerce_payment_complete', array($this, 'awcdp_payment_complete'));

            add_filter('woocommerce_order_class',  array($this, 'awcdp_order_class'), 10, 3 );

            add_action('woocommerce_thankyou', array($this, 'awcdp_disable_reorder_for_partial_payments'), 0);
            add_action('woocommerce_order_details_after_order_table', array($this, 'awcdp_show_myaccount_partial_payments_summary'));
            //add_action('woocommerce_email_after_order_table', array($this, 'awcdp_show_email_partial_payments_summary'));

            add_filter('woocommerce_order_number', array($this, 'awcdp_partial_payment_number'), 10, 2);
            add_action('awcdp_deposits_thankyou', array($this, 'awcdp_show_parent_order_summary'), 10);

            add_filter('woocommerce_cod_process_payment_order_status', array($this, 'awcdp_adjust_cod_status_completed'), 10, 2);
            add_action('woocommerce_order_status_partially-paid', 'wc_maybe_reduce_stock_levels');
            add_action('woocommerce_order_status_partially-paid', array($this, 'awcdp_adjust_second_payment_status'));

            add_filter('woocommerce_order_status_on-hold', array($this, 'awcdp_set_parent_order_on_hold'));
            add_filter('woocommerce_order_status_failed', array($this, 'awcdp_set_parent_order_failed'));
			      add_filter('woocommerce_order_status_cancelled', array($this, 'awcdp_set_partial_payments_as_cancelled'));

            add_action('delete_post', array($this, 'awcdp_delete_partial_payments'), 9);
            add_action('wp_trash_post', array($this, 'awcdp_trash_partial_payments'));
            add_action('untrashed_post', array($this, 'awcdp_untrash_partial_payments'));
            add_filter('woocommerce_cancel_unpaid_order', array($this, 'awcdp_cancel_partial_payments'), 10, 2);
            add_filter('pre_trash_post', array($this, 'awcdp_prevent_user_trash_partial_payments'), 10, 2);

            add_action('woocommerce_email_order_details', array($this, 'awcdp_deposit_details'), 20, 4);

            add_filter('woocommerce_email_enabled_new_order', array($this, 'awcdp_disable_payment_emails'), 999, 3);
            add_filter('woocommerce_email_enabled_customer_on_hold_order', array($this, 'awcdp_disable_payment_emails'), 999, 3);
            add_filter('woocommerce_email_enabled_customer_completed_order', array($this, 'awcdp_disable_payment_emails'), 999, 3);

            add_filter('woocommerce_email_actions', array($this, 'awcdp_email_actions'));
            add_action('woocommerce_email', array($this, 'awcdp_register_hooks'));
            add_filter('woocommerce_email_classes', array($this, 'awcdp_email_classes'));

            add_filter( 'awcfe_deposits_check_parent_exists', array($this, 'awcdp_awcfe_check_parent'), 10, 1 );
            add_filter( 'apifw_invoice_deposit', array($this, 'awcdp_apifw_invoice_deposit'), 10, 2 );

            add_action('wc_ajax_ppc-create-order',array($this,'awcdp_modify_cart_data'),0);

            add_filter('woocommerce_add_to_cart_product_id', array($this, 'awcdp_grouped_products_handler'));

            add_filter( 'dpo_pay_order_create', array($this, 'awcdp_dpo_pay_order_create'), 10, 1  );
            add_filter( 'ywsbs_pay_order_check', array($this, 'awcdp_dpo_pay_order_create'), 10, 1  );
            add_filter( 'wt_stripe_cart_total_before_payment', array($this, 'awcdp_update_WebToffee_stripe_cart_total') );
            
            add_filter( 'learndash_woocommerce_auto_complete_order', function( $auto_complete, $order_id ) { return false; }, 10, 2 );
        

        }


    }


    function awcdp_order_class($classname, $order_type, $order_id ){
      if( $order_type == 'awcdp_payment' ) {
        return 'AWCDP_Order';
      }
      return $classname;
    }


    public function check_woocommerce_active() {
        if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return true;
        }
        if (is_multisite()) {
            $plugins = get_site_option('active_sitewide_plugins');
            if (isset($plugins['woocommerce/woocommerce.php']))
                return true;
        }
        return false;
    }

    public static function instance($parent) {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
        }
        return self::$_instance;
    }

    public function frontend_enqueue_styles($hook = '') {
        wp_register_style($this->_token . '-frontend', esc_url($this->assets_url) . 'css/frontend.css', array(), $this->_version);

        wp_enqueue_style($this->_token . '-frontend');

    }

    public function frontend_enqueue_scripts() {

        // wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/frontend.js', array('jquery'), $this->_version, true);
        wp_register_script($this->_token . '-frontend', esc_url($this->assets_url) . 'js/frontend-min.js', array('jquery'), $this->_version, true);

        wp_enqueue_script($this->_token . '-frontend');

        wp_localize_script($this->_token . '-frontend', 'AWCDPSettings', array(
            'ajaxurl' =>  admin_url('admin-ajax.php'),
            'asseturl' =>  plugin_dir_url( __DIR__ ).'/assets/',
            'strings' =>  array(
            ),
            'security' => wp_create_nonce('awcdp-deposits-partial-payments-refresh'),
        ));
    }


    function awcdp_register_shortcodes()
    {
       add_shortcode('awcdp_deposit', array($this, 'awcdp_shortcode_deposit_function'));
    }

    function awcdp_shortcode_deposit_function($atts){
      extract(shortcode_atts(array(
          'product_id' => '',
      ), $atts));
      return $this->awcdp_get_deposit_container($product_id);
    }

    function awcdp_get_deposit_container($product_id){

      if (!apply_filters('awcdp_disable_deposit_condition', true)) { return; }

      $awcdp_gs = get_option('awcdp_general_settings');
      $require_login = (isset($awcdp_gs['require_login']) && $awcdp_gs['require_login'] == 1) ? 1 : 0;

      if( !is_user_logged_in() && $require_login == 1 ){
        return;
      }

        //global $product;
        //echo $this->awcdp_deposits_form( $product->get_id() );
		       
      if( $product_id != ''){
        $product_id = $product_id;
      } else {
        global $product;
        if($product){
			$product_id = $product->get_id() ;
		}
      }
	  echo $this->awcdp_deposits_form( $product_id );

    }


    function awcdp_deposits_form($product_id, $price = false){

      if ($product_id){
        $html = '';  $amount = 0;
        $product = wc_get_product($product_id);
        $enabled = $this->awcdp_deposits_enabled( $product_id );

        if ($product && $enabled) {
          $price = $price ? $price : $product->get_price();
          $product_type = $product->get_type();
          $deposit_amount = $this->awcdp_get_deposit_amount($product_id);
          $amount_type = $this->awcdp_get_deposit_type($product_id);
          $force_deposit = '';

          if ( $deposit_amount == '' ) {
              return;
          }

          $tax = 0;
          $tax_handling = 'full';
          $tax_display = 'yes';
          $price_include_tax = get_option('woocommerce_prices_include_tax');
          $has_payment_plans = false;

         if ($tax_display && $tax_handling == 'deposit') {
           $tax = wc_get_price_including_tax($product, array('price' => $price)) - wc_get_price_excluding_tax($product, array('price' => $price));
         } elseif ($tax_display && $tax_handling == 'split') {
             $tax_total = $tax = wc_get_price_including_tax($product, array('price' => $price)) - wc_get_price_excluding_tax($product, array('price' => $price));
             $deposit_percentage = $deposit_amount * 100 / ($product->get_price());
             if ($amount_type == 'percent') {
               $deposit_percentage = $deposit_amount;
             }
             $tax = $tax_total * $deposit_percentage / 100;
         }

         //if ($price_include_tax == 'yes') {
         if (wc_prices_include_tax()) {
           $tax_diff = wc_get_price_including_tax($product, array('price' => $price)) - wc_get_price_excluding_tax($product, array('price' => $price));
           $price -= $tax_diff;
         }
         $deposit_amount = floatval($deposit_amount);

         if ($amount_type == 'fixed') {
           $amount = $deposit_amount;
           if($tax_display){
             $amount = $deposit_amount + $tax;
           }
           /*
           if ($price_include_tax == 'yes') {
               $amount = $deposit_amount;
           } else {
               $amount = $deposit_amount + $tax;
           }
           */
           $amount = round($amount, wc_get_price_decimals());
         } elseif ($amount_type == 'percent') {
			 $is_ajax = function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : defined( 'DOING_AJAX' ) && DOING_AJAX;
           if ($product->get_type() == 'variable' || $product->get_type() == 'composite' || $product->get_type() == 'booking' && !$is_ajax) {
             $amount = $deposit_amount;
           } else {
             $amount = $price * ($deposit_amount / 100.0);
             if ($tax_display) {
             //if ($price_include_tax == 'yes') {
               $amount += $tax;
             }
           }
          $amount = round($amount, wc_get_price_decimals());
         }


        $higher = array('variable', 'booking',);
        //if ( !in_array($product_type, $higher) && $amount >= $price) {
		if ( !in_array($product_type, $higher) && $amount >= $price && apply_filters('awcdp_disable_low_price_order', true) ) {	
            return;
        }

       if ($amount_type === 'fixed') {
        if (!$product->is_sold_individually()) {
           $suffix = esc_html__('per item', 'deposits-partial-payments-for-woocommerce');
         } else {
           $suffix = '';
         }
       } else {
		   $is_ajax = function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : defined( 'DOING_AJAX' ) && DOING_AJAX;
         if (!$is_ajax && $product->get_type() == 'booking' || $product->get_type() == 'composite') {
           $amount = '<span class=\'amount\'>' . round($deposit_amount, wc_get_price_decimals()) . '%' . '</span>';
         }
         if (!$product->is_sold_individually()) {
           $suffix = esc_html__('per item', 'deposits-partial-payments-for-woocommerce');
         } else {
           $suffix = '';
         }
       }

      $awcdp_gs = get_option('awcdp_general_settings');
      $default_checked = ( isset($awcdp_gs['default_selected']) ) ? $awcdp_gs['default_selected'] : 'deposit';
      $display = ( $default_checked != 'deposit' ) ? 'style="display:none;"' : '' ;

      $awcdp_ts = get_option('awcdp_text_settings');
      $deposit_text = ( isset($awcdp_ts['pay_deposit_text']) && ( $awcdp_ts['pay_deposit_text'] != '' ) ) ? $awcdp_ts['pay_deposit_text'] : esc_html__('Pay Deposit', 'deposits-partial-payments-for-woocommerce' );
      $full_text = ( isset($awcdp_ts['pay_full_text'])  && ( $awcdp_ts['pay_deposit_text'] != '') ) ? $awcdp_ts['pay_full_text'] : esc_html__('Pay full amount', 'deposits-partial-payments-for-woocommerce' );
      $deposit_option_text = ( isset($awcdp_ts['deposit_text']) && ( $awcdp_ts['deposit_text'] != '' ) ) ? $awcdp_ts['deposit_text'] : esc_html__('Pay a deposit of ', 'deposits-partial-payments-for-woocommerce' );
    // sprintf( $deposit_text, '<span class="awcdp-deposits-amount">' . $amount . '</span>' );
    ?>

      <div class="awcdp-deposits-wrapper " data-product_id="<?php echo esc_attr($product->get_id()); ?>" >
        <div class="awcdp-deposits-option">
          <div class="awcdp-radio pay-deposit">
            <div>
              <input id="awcdp-option-pay-deposit" name="awcdp_deposit_option" type="radio" value="yes" <?php checked( $default_checked, 'deposit' ); ?> >
              <label for="awcdp-option-pay-deposit" class="awcdp-radio-label"><?php echo esc_html($deposit_text); ?></label>
            </div>
            <div class="awcdp-deposits-description" <?php echo wp_kses_post( $display); ?> >
              <?php echo esc_html( $deposit_option_text); ?>
              <?php if ( $amount_type === 'percent') {
                ?><span id='awcdp-deposit-amount'><?php echo wp_kses_post( $deposit_amount) . '%'; ?></span><?php
              } else {
                ?> <span id='awcdp-deposit-amount'><?php echo wp_kses_post( wc_price($deposit_amount)); ?></span><?php
              } ?>
              <span id='deposit-suffix'><?php echo esc_html($suffix); ?></span>
            </div>
          </div>
          <div class="awcdp-radio">
            <input id="awcdp-option-pay-full" name="awcdp_deposit_option" value="no" type="radio" <?php checked( $default_checked, 'full' ); ?> >
            <label for="awcdp-option-pay-full" class="awcdp-radio-label"><?php echo esc_html($full_text); ?></label>
          </div>
        </div>
      </div>
      <?php

      }
    }
  }


  function awcdp_deposits_enabled( $product_id ){

    $product = wc_get_product( $product_id );

    if ( ! $product || $product->is_type( array( 'grouped', 'external', 'bundle', 'composite' ) ) ) {
    //if ( ! $product || $product->is_type( array( 'external', 'bundle', 'composite' ) ) ) {
      return false;
    }

    $awcdp_gs = get_option('awcdp_general_settings');
    if( isset($awcdp_gs['enable_deposits']) && $awcdp_gs['enable_deposits'] == 1){
      $enabledP = get_post_meta( $product_id, AWCDP_DEPOSITS_META_KEY, true );
      if ( $enabledP != 'no' ) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }

  }

  function awcdp_get_deposit_type($product_id){

    $type = false;
    $product = wc_get_product($product_id);

    if ($product->get_type() === 'variation') {
      $parent_id = $product->get_parent_id();
      $product_id = $parent_id;
    }

    if ($product) {
      $type = get_post_meta( $product_id, AWCDP_DEPOSITS_TYPE, true );
      if ( !$type ) {
        $awcdp_gs = get_option('awcdp_general_settings');
        if( isset($awcdp_gs['deposit_type']) ){
          $type = $awcdp_gs['deposit_type'];
        } else {
          $type = 'fixed';
        }
      }
    }
    return $type;

}

  function awcdp_get_deposit_amount($product_id){

    $amount = false;
    $product = wc_get_product($product_id);

    if ($product->get_type() === 'variation') {
      $parent_id = $product->get_parent_id();
      $product_id = $parent_id;
    }

    if ($product) {
      $amount = get_post_meta( $product_id, AWCDP_DEPOSITS_AMOUNT, true );
      if ( !$amount ) {
       $awcdp_gs = get_option('awcdp_general_settings');
       if( isset($awcdp_gs['deposit_amount']) ){
         $amount = $awcdp_gs['deposit_amount'];
       }
      }
    }
    return $amount;
  }

    function awcdp_add_cart_item_data($cart_item_meta, $product_id, $variation_id){

      $dp_enabled = $this->awcdp_deposits_enabled( $product_id );
      if ( !$dp_enabled ) {
        return $cart_item_meta;
      }

      /*if ( $dp_enabled && !isset($_REQUEST['awcdp_deposit_option']) ) { */
	  if ( $dp_enabled && (!isset($_REQUEST['awcdp_deposit_option'])  || ( isset($_REQUEST['awcdp_deposit_option']) && $_REQUEST['awcdp_deposit_option'] == '') )) {
        $awcdp_gs = get_option('awcdp_general_settings');
        $default_checked = ( isset($awcdp_gs['default_selected']) ) ? $awcdp_gs['default_selected'] : 'deposit';
		    $deposit_amount = $this->awcdp_get_deposit_amount($product_id); 
        if( $deposit_amount != '' ){
          $_REQUEST['awcdp_deposit_option'] = $default_checked == 'deposit' ? 'yes' : false;
        }
      }

      $enabled = isset( $_REQUEST['awcdp_deposit_option'] ) ? (sanitize_text_field( $_REQUEST['awcdp_deposit_option'] )) : false;

      if ( $enabled == 'yes'  ) {
        $cart_item_meta['awcdp_deposit']['enable'] = true;

			// WDP price
			if ( $this->check_aco_dynamic_pricing_active() ) {
			  $product = wc_get_product($product_id);
			  if ( $product->is_type('variable') ) {
				      $product = wc_get_product($variation_id);
			  } else {
				      $product = wc_get_product($product_id);
			  }

				$discountedPrice = apply_filters('wcpa_product_price', $product->get_price(), $product);
				if( is_array($discountedPrice) ){ $discountedPrice = $discountedPrice['price']; }
				if( $discountedPrice ){
					$cart_item_meta['awcdp_deposit']['awdp_price'] = $discountedPrice;
				}
			  }

      }

      return $cart_item_meta;

    }

    function awcdp_get_cart_item_from_session($cart_item, $values) {

        if (!empty($values['awcdp_deposit'])) {
          $cart_item['awcdp_deposit'] = $values['awcdp_deposit'];
        }
        return $cart_item;
    }

    function awcdp_cart_loaded_from_session(){
      if (WC()->cart) {
          foreach (WC()->cart->get_cart_contents() as $cart_item_key => $cart_item) {
            $this->awcdp_update_deposit_meta($cart_item['data'], $cart_item['quantity'], $cart_item, $cart_item_key);
          }
      }
    }

	function check_aco_dynamic_pricing_active() {
		if( in_array('aco-woo-dynamic-pricing/start.php', apply_filters('active_plugins', get_option('active_plugins'))) || in_array('aco-woo-dynamic-pricing-pro/start.php', apply_filters('active_plugins', get_option('active_plugins'))) ) {
            return true;
        }
	}

    function awcdp_update_deposit_meta($product, $quantity, &$cart_item_data, $cart_item_key) {

      $amount = 0; $tax_total = 0;
          if ($product) {
              if(isset($cart_item_data['bundled_by'])) $cart_item_data['awcdp_deposit']['enable']  = 'no';

              $product_type = $product->get_type();
              $override = isset($cart_item_data['awcdp_deposit'], $cart_item_data['awcdp_deposit']['override']) ? $cart_item_data['awcdp_deposit']['override'] : array();
              $deposit_enabled = isset($override['enable']) ? $override['enable'] : $this->awcdp_deposits_enabled($product->get_id());
              $amount_type = isset($override['amount_type']) ? $override['amount_type'] : $this->awcdp_get_deposit_type($product->get_id());

              if ($deposit_enabled && isset($cart_item_data['awcdp_deposit'], $cart_item_data['awcdp_deposit']['enable'] ) && $cart_item_data['awcdp_deposit']['enable'] == 1 ) {

                  switch($amount_type){

                      case 'fixed':
                      case 'percent':

                      $deposit_amount_meta = $this->awcdp_get_deposit_amount($product->get_id());
                      $amount_type = $this->awcdp_get_deposit_type($product->get_id());

          					  if ( $deposit_amount_meta == '' ) {
          						  return;
          					  }

		                 $awdp_price = isset($cart_item_data['awcdp_deposit']['awdp_price']) ? $cart_item_data['awcdp_deposit']['awdp_price'] : '';

                      if (isset($cart_item_data['line_subtotal'])) {
                        $amount = $cart_item_data['line_subtotal'];
                      }

                    // PW WooCommerce Gift Cards
                    if ( defined( 'PWGC_SESSION_KEY' ) ) {
                        $discount = $this->pw_gift_card_total_discount();
                        $amount = $amount - $discount;
                    }

		                 if ($awdp_price != '' && $this->check_aco_dynamic_pricing_active() ) {
                        $amount = $awdp_price * $quantity;
                      }

                      if ($amount_type === 'fixed') {
                          $deposit = floatval($deposit_amount_meta) * $quantity;
                      } else {
                        // my-edit 04-12-21
                        if (wc_prices_include_tax() && isset($cart_item_data['awcdp_deposit']['original_price']) ) {
                        //  $amount = $cart_item_data['awcdp_deposit']['original_price'];
                        }
                        // my-edit 04-12-21
                          $deposit = $amount * (floatval($deposit_amount_meta) / 100.0);
                      }

                      $tax_handling = 'full';
                      if (isset($cart_item_data['line_subtotal_tax'])) {
                        $tax_total = $cart_item_data['line_subtotal_tax'];
                      }
                      $cart_item_data['awcdp_deposit']['tax_total'] = $tax_total;

                      if ($tax_handling == 'deposit') {
                        $cart_item_data['awcdp_deposit']['tax'] = $tax_total;
                      } elseif ($tax_handling === 'split') {
                        $deposit_percentage = $deposit * 100 / $amount;
                        $cart_item_data['awcdp_deposit']['tax'] = $tax_total * $deposit_percentage / 100;
                      } else {
                        $cart_item_data['awcdp_deposit']['tax'] = 0;
                      }

                      if ($deposit < $amount && $deposit > 0) {

                          $discount_percentage = 0;
                          if (floatval(WC()->cart->get_cart_discount_total()) && floatval(WC()->cart->get_subtotal()) > 0) {
                              $discount_percentage = WC()->cart->get_cart_discount_total() / WC()->cart->get_subtotal() * 100;
                          }
                          unset($cart_item_data['awcdp_deposit']['percent_discount']);
                          if ($discount_percentage > 0) {
                              $discount = $deposit / 100 * $discount_percentage;
                              $cart_item_data['awcdp_deposit']['percent_discount'] = $discount;
                          }
                      }
                      if ($deposit < $amount) {
                          $cart_item_data['awcdp_deposit']['deposit'] = $deposit;
                          $cart_item_data['awcdp_deposit']['remaining'] = $amount - $deposit;
                          $cart_item_data['awcdp_deposit']['total'] = $amount;
                      } else {
                          $cart_item_data['awcdp_deposit']['enable'] = 'no';
                      }

                      break;
                  }

                  WC()->cart->cart_contents[$cart_item_key]['awcdp_deposit'] = apply_filters('awcdp_deposits_cart_item_deposit_data', $cart_item_data['awcdp_deposit'], $cart_item_data);


              }

          }

      }


      function pw_gift_card_total_discount(){
        $discount = 0;
        if ( !defined( 'PWGC_SESSION_KEY' ) ) {
            return 0;
        }
        $session_data = (array) WC()->session->get( PWGC_SESSION_KEY );
        if ( !isset( $session_data['gift_cards'] ) || empty( $session_data['gift_cards'] ) ) {
            return 0;
        }
        $gift_cards = array();
        $gift_cards_amount = array();
       foreach ( $session_data['gift_cards'] as $card_number => $amount ) {
           // Retrieve the gift card object so we make sure it has not expired.
           $gift_card = new PW_Gift_Card( $card_number );
           if ( $gift_card->get_id() && !$gift_card->has_expired() ) {
               $gift_cards[] = $gift_card;
               $gift_cards_amount[$card_number] = $amount;
           }
       }
       if( $gift_cards_amount ){
         $discount = array_sum($gift_cards_amount);
       }
       return $discount;

      }


      function awcdp_cart_totals_after_order_total(){

          if (isset(WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] === true) :

           $awcdp_ts = get_option('awcdp_text_settings');
           $to_pay_text = ( isset($awcdp_ts['to_pay_text']) && ( $awcdp_ts['to_pay_text'] != '' ) ) ? $awcdp_ts['to_pay_text'] : esc_html__('Due Today', 'deposits-partial-payments-for-woocommerce' );
           $future_payment_text = ( isset($awcdp_ts['future_payment_text']) && ( $awcdp_ts['future_payment_text'] != '' ) ) ? $awcdp_ts['future_payment_text'] : esc_html__('Future payments', 'deposits-partial-payments-for-woocommerce' );


         ?>
         <tr class="order-paid">
             <th><?php echo esc_html($to_pay_text); ?></th>
             <td data-title="<?php echo esc_html($to_pay_text); ?>">
               <strong><?php echo wp_kses_post( wc_price(WC()->cart->deposit_info['deposit_amount']) ); ?></strong>
             </td>
         </tr>
         <tr class="order-remaining">
             <th><?php echo esc_html($future_payment_text); ?></th>
             <td data-title="<?php echo esc_html($future_payment_text); ?>">
                 <strong><?php echo wp_kses_post( wc_price(WC()->cart->get_total('edit') - WC()->cart->deposit_info['deposit_amount']) ); ?></strong>
             </td>
         </tr>
       <?php
       endif;
      }

      function awcdp_get_item_data($item_data, $cart_item) {

        if (isset($cart_item['awcdp_deposit'], $cart_item['awcdp_deposit']['enable']) && $cart_item['awcdp_deposit']['enable'] == 1 && isset($cart_item['awcdp_deposit']['deposit']) ) {

            $product = $cart_item['data'];
            if (!$product) return $item_data;

            //$tax_display = get_option('wc_deposits_tax_display_cart_item', 'no') === 'yes';
            $tax_display = 'no';

            $deposit = $cart_item['awcdp_deposit']['deposit'];

            $tax = 0.0;
            $tax_total = 0.0;
            if ($tax_display) {
                $tax = $cart_item['awcdp_deposit']['tax'];
                $tax_total = $cart_item['awcdp_deposit']['tax_total'];
            }

            $display_deposit = round($deposit + $tax, wc_get_price_decimals());
            $display_remaining = round($cart_item['awcdp_deposit']['remaining'] + ($tax_total - $tax), wc_get_price_decimals());
            // my-edit 04-12-21
            if (wc_prices_include_tax() && isset($cart_item['awcdp_deposit']['remaining']) ) {
              $display_remaining = round($cart_item['awcdp_deposit']['remaining'], wc_get_price_decimals());
            }
            // my-edit 04-12-21

            $awcdp_ts = get_option('awcdp_text_settings');
            $deposit_amount_text = ( isset($awcdp_ts['deposit_amount_text']) && ( $awcdp_ts['deposit_amount_text'] != '' ) ) ? $awcdp_ts['deposit_amount_text'] : esc_html__('Deposit Amount', 'deposits-partial-payments-for-woocommerce' );

            $item_data[] = array(
                'name' => $deposit_amount_text,
                'display' => wc_price($display_deposit),
                'value' => 'wc_deposit_amount',
            );

            $awcdp_ts = get_option('awcdp_text_settings');
            $future_payment_amount_text = ( isset($awcdp_ts['future_payment_text']) && ( $awcdp_ts['future_payment_text'] != '' ) ) ? $awcdp_ts['future_payment_text'] : esc_html__('Future payments', 'deposits-partial-payments-for-woocommerce' );

            $item_data[] = array(
                'name' => $future_payment_amount_text,
                'display' => wc_price($display_remaining),
                'value' => 'wc_deposit_future_payments_amount',
            );



        }

        return $item_data;

    }


    function awcdp_display_item_subtotal( $output, $cart_item, $cart_item_key ) {

        if (isset($cart_item['awcdp_deposit'], $cart_item['awcdp_deposit']['enable']) && $cart_item['awcdp_deposit']['enable'] == 1 && isset($cart_item['awcdp_deposit']['deposit']) ) {

          $product = $cart_item['data'];
          if (!$product) return $item_data;

          //$tax_display = get_option('wc_deposits_tax_display_cart_item', 'no') === 'yes';
          $tax_display = 'no';

          $deposit = $cart_item['awcdp_deposit']['deposit'];

          $tax = 0.0;
          $tax_total = 0.0;
          if ($tax_display) {
              $tax = $cart_item['awcdp_deposit']['tax'];
              $tax_total = $cart_item['awcdp_deposit']['tax_total'];
          }

          $display_deposit = round($deposit + $tax, wc_get_price_decimals());
          $awcdp_ts = get_option('awcdp_text_settings');
          $deposit_amount_text = ( isset($awcdp_ts['deposit_amount_text']) && ( $awcdp_ts['deposit_amount_text'] != '' ) ) ? $awcdp_ts['deposit_amount_text'] : esc_html__('Deposit Amount', 'deposits-partial-payments-for-woocommerce' );

          //$output = wc_price($display_deposit);
          $output .= '<br/><small>( ' . wp_kses_post( sprintf( esc_html__( '%s payable in deposit', 'deposits-partial-payments-for-woocommerce' ), wc_price( $display_deposit ) )) . ' )</small>';

        }

      return $output;
    }

    function awcdp_save_original_price($cart_item_key){

        $cart_item = WC()->cart->get_cart_item($cart_item_key);
        if(isset($cart_item['awcdp_deposit'],$cart_item['awcdp_deposit']['enable']) && $cart_item['awcdp_deposit']['enable'] == 1){

            $product = $cart_item['data'];

            WC()->cart->cart_contents[$cart_item_key]['awcdp_deposit']['original_price'] = $product->get_price();
        }
    }

    function awcdp_cart_needs_payment($needs_payment, $cart) {

        $deposit_enabled = isset(WC()->cart->deposit_info['deposit_enabled'], WC()->cart->deposit_info['deposit_amount'])
            && WC()->cart->deposit_info['deposit_enabled'] === true && WC()->cart->deposit_info['deposit_amount'] <= 0;

        if ($deposit_enabled) {
            $needs_payment = false;
        }
        return $needs_payment;

    }

    function awcdp_calculated_total($cart_total, $cart) {

        $cart_original = $cart_total;
        $deposit_amount = 0;
        $deposit_total = 0;
        $full_amount_products = 0;
        $full_amount_taxes = 0;
        $deposit_product_taxes = 0;
        $deposit_enabled = false;
        $deposit_in_cart = false;
        $this->awcdp_cart_loaded_from_session();

            $this->has_payment_plans = false;
            foreach (WC()->cart->get_cart_contents() as $cart_item_key => &$cart_item) {

                if (isset($cart_item['awcdp_deposit'], $cart_item['awcdp_deposit']['enable']) && $cart_item['awcdp_deposit']['enable'] == 1 && isset($cart_item['awcdp_deposit']['deposit'])) {
                    $deposit_in_cart = true;
                    $product = wc_get_product($cart_item['product_id']);
                    $deposit_amount += $cart_item['awcdp_deposit']['deposit'];
                    $deposit_product_taxes += $cart_item['awcdp_deposit']['tax'];
                    $deposit_total += $cart_item['awcdp_deposit']['total'];

                    if (isset($cart_item['awcdp_deposit']['payment_plan'])) {
                        $this->has_payment_plans = true;
                    }
                } else {
                    $full_amount_products += $cart_item['line_subtotal'];
                    $full_amount_taxes += $cart_item['line_subtotal_tax'];

                }
            }


        if ($deposit_in_cart && $deposit_amount < ($deposit_total + $cart->fee_total + $cart->tax_total + $cart->shipping_total)) {

            $deposit_amount += $full_amount_products;
            $deposit_enabled = true;

        }
        $deposit_breakdown = null;

        $fees_handling = 'deposit';
        $taxes_handling = 'deposit';
        $shipping_handling = 'deposit';
        $shipping_taxes_handling = 'deposit';

        $deposit_fees = 0.0;
        $deposit_taxes = $full_amount_taxes;
        $deposit_shipping = 0.0;
        $deposit_shipping_taxes = 0.0;
        $division = WC()->cart->get_subtotal();

            $division = $division == 0 ? 1 : $division;
            $deposit_percentage = $deposit_amount * 100 / floatval($division);

        $remaining_amounts = array();

        // Fees handling

        $fee_taxes = $cart->get_fee_tax();
        switch ($fees_handling) {
            case 'deposit' :
                $deposit_fees = floatval($cart->fee_total + $fee_taxes);
                break;

            case 'split' :
                $deposit_fees = floatval($cart->fee_total + $fee_taxes) * $deposit_percentage / 100;
                break;
        }
        $remaining_amounts['fees'] = ($cart->fee_total + $fee_taxes) - $deposit_fees;

        // Taxes handling

            $deposit_taxes += $deposit_product_taxes;


        $remaining_amounts['taxes'] = $cart->get_subtotal_tax() - $deposit_taxes;

        // Shipping handling

        switch ($shipping_handling) {
            case 'deposit' :
                $deposit_shipping = $cart->shipping_total;
                break;

            case 'split' :
                $deposit_shipping = $cart->shipping_total * $deposit_percentage / 100;
                break;
        }
        $remaining_amounts['shipping'] = $cart->shipping_total - $deposit_shipping;

        // Shipping taxes handling.

        switch ($shipping_taxes_handling) {
            case 'deposit' :
                $deposit_shipping_taxes = $cart->shipping_tax_total;
                break;

            case 'split' :
                $deposit_shipping_taxes = $cart->shipping_tax_total * $deposit_percentage / 100;
                break;
        }
        $remaining_amounts['shipping_taxes'] = $cart->shipping_tax_total - $deposit_shipping_taxes;

        // Add fees, taxes, shipping and shipping taxes to the deposit amount.
        $cart_items_deposit_amount = $deposit_amount;

        $deposit_amount += $deposit_fees + $deposit_taxes + $deposit_shipping + $deposit_shipping_taxes;

        // Deposit breakdown tooltip.
        $deposit_breakdown = array(
            'cart_items' => $cart_items_deposit_amount,
            'fees' => $deposit_fees,
            'taxes' => $deposit_taxes,
            'shipping' => $deposit_shipping,
            'shipping_taxes' => $deposit_shipping_taxes,
            'discounts' => 0.0
        );


        $discount_from_deposit = 'second_payment'; // 'split' ,'deposit'
        // $discount_from_deposit = 'split';
        // $discount_from_deposit = 'deposit';
        $discount_total = WC()->cart->get_cart_discount_total() + WC()->cart->get_cart_discount_tax_total();

        if( function_exists('AWDP') ){
            $coupon             = get_option('awdp_fee_label') ? get_option('awdp_fee_label') : 'Discount';
            $coupon_code        = apply_filters('woocommerce_coupon_code', $coupon);
            $coupons_obj    = new WC_Coupon($coupon_code);
            $coupons_amount = $coupons_obj->get_amount();
            $discount_total = ($discount_total) ? ($discount_total - $coupons_amount) : 0;
        }

        $remaining_amounts['discounts'] = 0.0;
        if ($discount_from_deposit === 'deposit') {

            if ($discount_total > $deposit_amount || $discount_total == $deposit_amount) {
                $remaining_amounts['discounts'] = $discount_total - $deposit_amount;
                $deposit_amount = 0.0;
                $deposit_breakdown['discount'] = $deposit_amount;

            } else {
                //whole discount taken from deposit;
                $deposit_amount -= $discount_total;
                $deposit_breakdown['discount'] = $discount_total;
            }
        } elseif ($discount_from_deposit === 'split') {
            $discount_deposit = $discount_total / 100 * $deposit_percentage;
            $deposit_amount -= $discount_deposit;
            $deposit_breakdown['discount'] = $discount_deposit;
            $remaining_amounts['discounts'] = $discount_total - $discount_deposit;

        } else {
            //discount from future_payment
            $remaining_amounts['discounts'] = $discount_total;

        }

        //round decimals according to woocommerce
        $deposit_amount = round($deposit_amount, wc_get_price_decimals());
        $deposit_amount = apply_filters('woocommerce_deposits_cart_deposit_amount', $deposit_amount, $cart_total);

        // no point of having deposit if second payment as 0 or in negative
        if ($cart_total - $deposit_amount <= 0) {
            $deposit_enabled = false;
        }


        WC()->cart->deposit_info = array();
        WC()->cart->deposit_info['deposit_enabled'] = $deposit_enabled;
        WC()->cart->deposit_info['deposit_breakdown'] = $deposit_breakdown;
        WC()->cart->deposit_info['deposit_amount'] = $deposit_amount;
        WC()->cart->deposit_info['has_payment_plans'] = $this->has_payment_plans;

        $payment_schedule = $this->awcdp_build_payment_schedule($remaining_amounts, $deposit_amount, $cart_items_deposit_amount);
        WC()->cart->deposit_info['payment_schedule'] = $payment_schedule;

        return $cart_original;

    }

    function awcdp_build_payment_schedule($remaining_amounts, $deposit, $cart_items_deposit_amount){

        $schedule = array();
        $second_pay_due = '';
        $unlimited = array(
            'id' => '',
            'title' => esc_html__('Future payment', 'deposits-partial-payments-for-woocommerce'),
            'type' => 'second_payment',
            'total' => 0.0,
        );
        $payment_date = current_time('timestamp');

            foreach (WC()->cart->get_cart() as $key => $cart_item) {

                if (isset($cart_item['awcdp_deposit'], $cart_item['awcdp_deposit']['enable']) && $cart_item['awcdp_deposit']['enable'] == 1 && isset($cart_item['awcdp_deposit']['deposit'])) {

                    if (isset($cart_item['awcdp_deposit']['payment_schedule'])) {
                      foreach ($cart_item['awcdp_deposit']['payment_schedule'] as $timestamp => $payment) {
                        if (!isset($schedule[$timestamp])) $schedule[$timestamp] = array('type' => 'partial_payment', 'total' => 0.0);
                        $schedule[$timestamp]['total'] += $payment['amount'];
                      }
                    } else {

                      if (!empty($second_pay_due) && is_numeric($second_pay_due)) {
                        $timestamp = strtotime("+{$second_pay_due} days", current_time('timestamp'));
                        if (!isset($schedule[$timestamp])) $schedule[$timestamp] = array('total' => 0.0);
                        $schedule[$timestamp]['total'] += floatval($cart_item['awcdp_deposit']['remaining']);
                        if (!isset($schedule[$timestamp]['type'])) $schedule[$timestamp]['type'] = 'second_payment';
                      } else {
                        $unlimited['total'] += $cart_item['awcdp_deposit']['remaining'];
                        $unlimited['type'] = 'second_payment';
                      }
                    }
                }
            }

        $timestamps = array();
        foreach (array_keys($schedule) as $key => $node) {
          $timestamps[$key] = $node;
        }
        array_multisort($timestamps, SORT_ASC, array_keys($schedule));
        $sorted_schedule = array();
        foreach ($timestamps as $timestamp) {
          $sorted_schedule[$timestamp] = $schedule[$timestamp];
        }
        $schedule = $sorted_schedule;
        if ((empty($second_pay_due) || !is_numeric($second_pay_due)) && $unlimited['total'] > 0) {
          $schedule['unlimited'] = $unlimited;
        }

        $schedule_total = array_sum(array_column($schedule, 'total'));
        $count = 0;
        $remaining_amounts_record = $remaining_amounts;
        foreach ($remaining_amounts_record as $key => $remaining_amount) {
          $remaining_amounts_record[$key] = round($remaining_amount, wc_get_price_decimals());
        }

        foreach ($schedule as $payment_key => $payment) {
            if($payment['total'] <= 0) {
              continue;
            }
            $percentage = round($payment['total'] / $schedule_total * 100,2);
            $count++;
            $last = $count === count($schedule);
            foreach ($remaining_amounts as $amount_key => $remaining_amount) {
                if ($remaining_amount <= 0) continue;
                if ($last) {
                    if ($amount_key === 'discounts') {
                      $schedule[$payment_key]['total'] -= round($remaining_amounts_record[$amount_key],wc_get_price_decimals(),PHP_ROUND_HALF_DOWN);
                    } else {
          						if( !(get_option('woocommerce_prices_include_tax') == 'yes' && $amount_key == 'taxes') ){
          							$schedule[$payment_key]['total'] += round($remaining_amounts_record[$amount_key],wc_get_price_decimals(),PHP_ROUND_HALF_DOWN);
          						}
                    }
                    continue;
                }
                if ($amount_key === 'discounts') {
                  $schedule[$payment_key]['total'] -= round($remaining_amount / 100 * $percentage, wc_get_price_decimals(),PHP_ROUND_HALF_UP);
                  $remaining_amounts_record[$amount_key] -= round($remaining_amount / 100 * $percentage, wc_get_price_decimals(),PHP_ROUND_HALF_UP);
                } else {
                  $schedule[$payment_key]['total'] += round($remaining_amount / 100 * $percentage, wc_get_price_decimals(),PHP_ROUND_HALF_UP);
                  $remaining_amounts_record[$amount_key] -= round($remaining_amount / 100 * $percentage, wc_get_price_decimals(),PHP_ROUND_HALF_UP);
                }
            }
        }

        return $schedule;
    }



    function awcdp_checkout_create_order_line_item($item, $cart_item_key, $values, $order){

      if ($order->get_type() != AWCDP_POST_TYPE){
        $deposit_meta = isset($values['awcdp_deposit']) ? $values['awcdp_deposit'] : false;
        if ($deposit_meta) {
          $item->add_meta_data('awcdp_deposit_meta', $deposit_meta, true);
        }
      }
    }

    function awcdp_checkout_update_order_meta($order_id){

        $order = wc_get_order($order_id);

        if ($order->get_type() == AWCDP_POST_TYPE) {
            return;
        }

        if (isset(WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] === true) {

            $deposit = WC()->cart->deposit_info['deposit_amount'];
            $second_payment = WC()->cart->get_total('edit') - $deposit;
            $deposit_breakdown = WC()->cart->deposit_info['deposit_breakdown'];
            $sorted_schedule = WC()->cart->deposit_info['payment_schedule'];

            $deposit_data = array(
                'id' => '',
                'title' => esc_html__('Deposit', 'deposits-partial-payments-for-woocommerce'),
                'type' => 'deposit',
                'total' => $deposit,
            );
            $sorted_schedule = array('deposit' => $deposit_data) + $sorted_schedule;
            $order->add_meta_data('_awcdp_deposits_payment_schedule', $sorted_schedule, true);
            $order->add_meta_data('_awcdp_deposits_order_has_deposit', 'yes', true);
            $order->add_meta_data('_awcdp_deposits_deposit_paid', 'no', true);
            $order->add_meta_data('_awcdp_deposits_second_payment_paid', 'no', true);
            $order->add_meta_data('_awcdp_deposits_deposit_amount', $deposit, true);
            $order->add_meta_data('_awcdp_deposits_second_payment', $second_payment, true);
            $order->add_meta_data('_awcdp_deposits_deposit_breakdown', $deposit_breakdown, true);
            $order->add_meta_data('_awcdp_deposits_deposit_payment_time', ' ', true);
            $order->add_meta_data('_awcdp_deposits_second_payment_reminder_email_sent', 'no', true);
            $order->save();


        } elseif (isset(WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] !== true) {
            $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);

            if ($has_deposit == 'yes') {

                $order->delete_meta_data('_awcdp_deposits_order_has_deposit');
                $order->delete_meta_data('_awcdp_deposits_deposit_paid');
                $order->delete_meta_data('_awcdp_deposits_second_payment_paid');
                $order->delete_meta_data('_awcdp_deposits_deposit_amount');
                $order->delete_meta_data('_awcdp_deposits_second_payment');
                $order->delete_meta_data('_awcdp_deposits_deposit_breakdown');
                $order->delete_meta_data('_awcdp_deposits_deposit_payment_time');
                $order->delete_meta_data('_awcdp_deposits_second_payment_reminder_email_sent');

                foreach ($order->get_items() as $order_item) {
                    $order_item->delete_meta_data('awcdp_deposit_meta');
                    $order_item->save();
                }
                $order->save();

            }
        }
    }

    function awcdp_review_order_after_order_total(){

		$is_ajax = function_exists( 'wp_doing_ajax' ) ? wp_doing_ajax() : defined( 'DOING_AJAX' ) && DOING_AJAX;
      if(!$is_ajax) return;

       if ( (isset(WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] === true)) {

         $awcdp_ts = get_option('awcdp_text_settings');
         $to_pay_text = ( isset($awcdp_ts['to_pay_text']) && ( $awcdp_ts['to_pay_text'] != '' ) ) ? $awcdp_ts['to_pay_text'] : esc_html__('Due Today', 'deposits-partial-payments-for-woocommerce' );
         $future_payment_text = ( isset($awcdp_ts['future_payment_text']) && ( $awcdp_ts['future_payment_text'] != '' ) ) ? $awcdp_ts['future_payment_text'] : esc_html__('Future payments', 'deposits-partial-payments-for-woocommerce' );

          ?>

          <tr class="order-paid">
            <th><?php echo esc_html( $to_pay_text); ?></th>
            <td data-title="<?php echo esc_html($to_pay_text); ?>">
              <strong><?php echo wp_kses_post( wc_price(WC()->cart->deposit_info['deposit_amount']) ); ?></strong>
            </td>
          </tr>
          <tr class="order-remaining">
            <th><?php echo esc_html($future_payment_text); ?></th>
            <td data-title="<?php echo esc_html($future_payment_text); ?>">
              <strong><?php echo wp_kses_post( wc_price(WC()->cart->get_total('edit') - WC()->cart->deposit_info['deposit_amount'])); ?></strong>
            </td>
          </tr>
          <?php
      }


    }



    function awcdp_available_payment_gateways($gateways){
        $has_deposit = false;
        $is_paying_deposit = true;

        $pay_slug = get_option('woocommerce_checkout_pay_endpoint', 'order-pay');
        $order_id = absint(get_query_var($pay_slug));

        if ($order_id > 0) {
            $order = wc_get_order($order_id);
            if (!$order || $order->get_type() !== AWCDP_POST_TYPE){
              return $gateways;
            }

            $has_deposit = true;

            if ($order->get_meta('_awcdp_deposits_payment_type', true) != 'deposit') {
                $is_paying_deposit = false;
            }


        } else {
            $is_paying_deposit = true;

            if (isset(WC()->cart->deposit_info) && isset(WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] === true) {
                $has_deposit = true;
            }

        }

        if ($has_deposit) {

          $awcdp_gs = get_option('awcdp_general_settings');
    			$disallowed_gateways = ( isset($awcdp_gs['disable_gateways']) ) ? $awcdp_gs['disable_gateways'] : '';

            if (is_array($disallowed_gateways)) {
                foreach ($disallowed_gateways as $value) {
                    unset($gateways[$value]);
                }
            }

        }
        return $gateways;
    }


    function awcdp_order_status_completed($order_id) {

        $order = wc_get_order($order_id);
        if ($order){
        if ($order->get_type() == AWCDP_POST_TYPE) {
            $partial_editor = false;
			if (function_exists('get_current_screen')) {
				$screen = get_current_screen();
				if ($screen){
				  //$partial_editor = $screen->id === 'awcdp_payment';
          //$partial_editor = $screen->id === 'awcdp_payment' || $screen->id === 'edit-awcdp_payment';
          $partial_editor = $screen->id === 'awcdp_payment' || $screen->id === 'edit-awcdp_payment' || $screen->id === 'woocommerce_page_wc-orders--awcdp_payment';
				}
            }

            if ($partial_editor) {
              $parent = wc_get_order($order->get_parent_id());
              if ($parent) {
                if ($order->get_meta('_awcdp_deposits_payment_type', true) == 'deposit') {
                  $parent->update_meta_data('_awcdp_deposits_deposit_paid', 'yes');
                } elseif ($order->get_meta('_awcdp_deposits_payment_type', true) == 'second_payment') {
                  $parent->update_meta_data('_awcdp_deposits_second_payment_paid', 'yes');
                }
                $parent->save();
                $parent->payment_complete();
              }
            }

        } else {
            $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);

            if ($has_deposit == 'yes') {
              $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
              if (is_array($schedule)) {
                foreach ($schedule as $timestamp => $payment) {
                  $pay_order = wc_get_order($payment['id']);
                  if ($pay_order) {
                    $pay_order->set_status('completed');
                    $pay_order->save();
                  }
                }
              }
              $order->update_meta_data('_awcdp_deposits_deposit_paid', 'yes');
              $order->update_meta_data('_awcdp_deposits_second_payment_paid', 'yes');
              $order->save();
            }
      }
    }

    }


    function awcdp_complete_partial_payments($order_id){
      $order = wc_get_order($order_id);
      if ($order){
        if ($order->get_type() == AWCDP_POST_TYPE) {
          $order->update_status('pending');
          $order->payment_complete();
          $order->save();
        }
      }
    }

    function awcdp_early_update_partial_payments($order_id){
      $order = wc_get_order($order_id);
      if ($order) {
        $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
        if (is_array($schedule)){
          foreach ($schedule as $payment) {
            if ($payment['type'] !== 'deposit') {
              continue;
            }
            $partial_payment = wc_get_order($payment['id']);
            if ($partial_payment && $partial_payment->get_status() !== 'completed') {
              $partial_payment->set_status('completed');
              $partial_payment->save();
            }
          }
          $order->update_meta_data('_awcdp_deposits_deposit_paid', 'yes');
          $order->update_meta_data('_awcdp_deposits_second_payment_paid', 'no');
          //$order->update_meta_data('_awcdp_deposits_deposit_payment_time', time());
          $order->update_meta_data('_awcdp_deposits_deposit_payment_time', current_time('timestamp'));
          $order->update_meta_data('_awcdp_deposits_second_payment_reminder_email_sent', 'no');
		  $order->save();
        }
      }
    }

    function awcdp_payment_complete_reduce_order_stock($reduce, $order_id){
        $order = wc_get_order($order_id);
        if ($order->get_type() == AWCDP_POST_TYPE) {
          return false;
        }
        $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
        if ($has_deposit == 'yes' ) {
            $status = $order->get_status();
            $awcdp_gs = get_option('awcdp_general_settings');
            $reduce_on = ( isset($awcdp_gs['reduce_stock']) ) ? $awcdp_gs['reduce_stock'] : 'full';
            /*
            if ($status == 'partially-paid' && $reduce_on == 'full') {
                $reduce = false;
            } elseif ($status == 'processing' && $reduce_on == 'deposit') {
                $reduce = false;
            }
            */
            $valid_statuses = array('partially-paid', 'on-hold');
            if (in_array($status, $valid_statuses) && $reduce_on === 'full') {
                $reduce = false;
            } elseif ($status === 'processing' && $reduce_on === 'deposit') {
                $reduce = false;
            }

        }
        return $reduce;
    }

    function awcdp_order_statuses($order_statuses){
      $new_statuses = array();
      foreach ($order_statuses as $key => $value) {
        $new_statuses[$key] = $value;
        if ($key === 'wc-pending') {
          $new_statuses['wc-partially-paid'] = esc_html__('Partially Paid', 'deposits-partial-payments-for-woocommerce');
        }
      }
      return $new_statuses;
    }

    function awcdp_valid_order_statuses_for_payment_complete($statuses, $order) {
      $remaining_payable = 'yes';
      if ($order->get_type() != AWCDP_POST_TYPE && $remaining_payable == 'yes') {
        $statuses[] = 'partially-paid';
      }
      return $statuses;
    }

    function awcdp_order_has_status($has_status, $order, $status){
      if ($order->get_status() == 'partially-paid') {
        if (is_array($status)) {
          if (in_array('pending', $status)) {
            $has_status = true;
          }
        } else {
          if ($status == 'pending') {
            $has_status = true;
          }
        }
      }
      return $has_status;
    }

    function awcdp_order_status_changed($order_id, $old_status, $new_status){

      $order = wc_get_order($order_id);
      $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
      if ($order->get_type() != AWCDP_POST_TYPE && $has_deposit == 'yes') {
        $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
        if (!is_array($schedule) || empty($schedule)){
          return;
        }

        if ($old_status === 'trash') {
          foreach ($schedule as $payment) {
            if (isset($payment['id']) && is_numeric($payment['id'])) {
              wp_untrash_post($payment['id']);
            }
          }
        }

        $deposit_paid = $order->get_meta('_awcdp_deposits_deposit_paid', true);

        if ($deposit_paid == 'yes' && $old_status == 'partially-paid' && ($new_status == 'processing' || $new_status == 'completed') ) {
          $order->update_meta_data('_awcdp_deposits_deposit_paid', 'yes');
          $order->update_meta_data('_awcdp_deposits_second_payment_paid', 'yes');

          foreach ($schedule as $payment) {
            $partial_payment = wc_get_order($payment['id']);
            if ($partial_payment) {
              $partial_payment->set_status('completed');
              $partial_payment->save();
            }
          }
      }
      $order->Save();
    }

    if($order->get_type() == AWCDP_POST_TYPE  && $order->get_meta('_awcdp_deposits_payment_type') == 'deposit' && $old_status == 'on-hold' && $new_status == 'completed') {
       $parent = wc_get_order($order->get_parent_id());
       if (!$parent || $parent->get_status() == 'partially-paid') return;
       if ($order->get_meta('_awcdp_deposits_payment_type', true) == 'deposit') {
         $parent->update_meta_data('_awcdp_deposits_deposit_paid', 'yes');
         $parent->update_meta_data('_awcdp_deposits_deposit_payment_time', current_time('timestamp'));
         $parent->save();
         $parent->payment_complete();
       }
   }



  }

  function awcdp_needs_payment($needs_payment, $order, $valid_statuses){
    $status = $order->get_status();

    if($order->get_type() === AWCDP_POST_TYPE){
      $parent = wc_get_order($order->get_parent_id());
      if(!$parent) return false;
      if (is_checkout_pay_page()) {
        try {
          $payment_type = $order->get_meta('_awcdp_deposits_payment_type', true) ;
          if (( $payment_type == 'deposit' && !$parent->needs_payment() ) ||  ($payment_type != 'deposit' && (!$parent->needs_payment() || $parent->get_status() != 'partially-paid'))) {
            if (did_action('before_woocommerce_pay') && !did_action('after_woocommerce_pay')) {
              $needs_payment = false;
              wc_print_notice( sprintf( __( 'Main order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for.', 'woocommerce-deposits' ), wc_get_order_status_name( $parent->get_status() ) ) ,'notice');
            }
          }
        } catch (\Exception $e) {
            wc_print_notice($e->getMessage(), 'error');
        }
      }
    }


/*
    if(is_checkout_pay_page()  && $order->get_type() == AWCDP_POST_TYPE &&  $order->get_meta('_awcdp_deposits_payment_type', true) != 'deposit' ){
      try {
        $parent = wc_get_order($order->get_parent_id());
        if($parent && $parent->get_status() != 'partially-paid'){
          if(did_action('before_woocommerce_pay') && !did_action('after_woocommerce_pay')){
            $needs_payment = false;
          }
        }
      } catch ( \Exception $e ) {
        wc_print_notice( $e->getMessage(), 'error' );
      }
    }
*/

    if ($status == 'partially-paid') {
      $remaining_payable = 'yes';
      if ($remaining_payable == 'yes') {
        $needs_payment = true;
      } else {
        $needs_payment = false;
      }
    }
    return $needs_payment;
  }

    function awcdp_redirect_payment_links(){
      global $wp;
      if (!empty($wp->query_vars['order-pay'])) {
        $order_id = absint($wp->query_vars['order-pay']);
        $order = wc_get_order($order_id);
        if ($order) {
          $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);

          if($order->get_type() != AWCDP_POST_TYPE && $has_deposit == 'yes' && $order->needs_payment()) {
            $payment_schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
            if (is_array($payment_schedule) && !empty($payment_schedule)){
              wp_redirect($order->get_checkout_payment_url());
              exit;
            }
          }
          /*
          if ($order && $order->needs_payment() && $order->get_type() != AWCDP_POST_TYPE && $has_deposit == 'yes') {
            wp_redirect($order->get_checkout_payment_url());
            exit;
          }
          */

        }
      }
    }

    function awcdp_add_order_item_meta($item_id, $item, $order_id){
      if (is_array($item) && isset($item['deposit'])) {
        wc_add_order_item_meta($item_id, '_awcdp_deposit_meta', $item['deposit']);
      }
    }


    function awcdp_order_formatted_line_subtotal($subtotal, $item, $order){

        if (did_action('woocommerce_email_order_details')){
           return $subtotal;
        }

        if ($order->get_meta('_awcdp_deposits_order_has_deposit', true) === 'yes') {

            $product = $item->get_product();
            if (!$product) return $subtotal;
            if ($product->get_type() === 'bundle' || isset($item['_bundled_by'])) return $subtotal;

            if ($product && isset($item['awcdp_deposit_meta'])) {
                $deposit_meta = maybe_unserialize($item['awcdp_deposit_meta']);
            } else {
                return $subtotal;
            }

          if (is_array($deposit_meta) && isset($deposit_meta['enable']) && $deposit_meta['enable'] === 'yes') {
              $tax_display = 'no';
                $tax = ($tax_display == 'yes') ? floatval($item['line_tax']) : 0;

                if (wc_prices_include_tax()) {
                    $deposit = $deposit_meta['deposit'];
                } else {
                    $deposit = $deposit_meta['deposit'] + $tax;
                }

                return $subtotal . '<br/>(' .
                    wc_price($deposit, array('currency' => $order->get_currency())) . ' ' . esc_html__('Deposit', 'deposits-partial-payments-for-woocommerce') . ')';
            } else {
                return $subtotal;
            }
        } else {
            return $subtotal;
        }
    }

    function awcdp_payment_complete_order_status($new_status, $order_id){

        $order = wc_get_order($order_id);
        if ($order) {
          $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true) == 'yes';
          if ($has_deposit) {
              $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);

              if (!is_array($schedule) || empty($schedule)){
                return $new_status;
              }
              $payments_complt = true;
              foreach ($schedule as $payment) {
                $payment_order = wc_get_order($payment['id']);
                if ($payment_order && $payment_order->get_status() !== 'completed') {
                  $payments_complt = false;
                  break;
                }
              }

              if (!$payments_complt) {
                $new_status = 'partially-paid';
              } else{
                $awcdp_gs = get_option('awcdp_general_settings');
                $status = (isset($awcdp_gs['fully_paid_status'])) ? $awcdp_gs['fully_paid_status'] : ($order->needs_processing() ? 'processing' : 'completed');
                $new_status = apply_filters('awcdp_deposits_order_fully_paid_status',$status,$order_id);
              }

          }
        }
        return $new_status;
    }

    function awcdp_get_order_item_totals($total_rows, $order){

        $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true) === 'yes';

        if ($has_deposit){
          $awcdp_ts = get_option('awcdp_text_settings');
          $to_pay_text = ( isset($awcdp_ts['to_pay_text']) && ( $awcdp_ts['to_pay_text'] != '' ) ) ? $awcdp_ts['to_pay_text'] : esc_html__('Due Today', 'deposits-partial-payments-for-woocommerce' );
          $future_pay_text = ( isset($awcdp_ts['future_payment_text']) && ( $awcdp_ts['future_payment_text'] != '' ) ) ? $awcdp_ts['future_payment_text'] : esc_html__('Future payments', 'deposits-partial-payments-for-woocommerce' );
          $deposit_amount_text = ( isset($awcdp_ts['deposit_amount_text']) && ( $awcdp_ts['deposit_amount_text'] != '' ) ) ? $awcdp_ts['deposit_amount_text'] : esc_html__('Deposit Amount', 'deposits-partial-payments-for-woocommerce' );


            $status = $order->get_status();
            $deposit_amount = floatval($order->get_meta('_awcdp_deposits_deposit_amount', true));
            $deposit_paid = $order->get_meta('_awcdp_deposits_deposit_paid', true);
            $second_payment = floatval($order->get_meta('_awcdp_deposits_second_payment', true));
            $second_payment_paid = $order->get_meta('_awcdp_deposits_second_payment_paid', true);

            $received_slug = get_option('woocommerce_checkout_order_received_endpoint', 'order-received');
            $pay_slug = get_option('woocommerce_checkout_order_pay_endpoint', 'order-pay');

            $is_checkout = (get_query_var($received_slug) === '' && is_checkout());
            $is_email = did_action('woocommerce_email_order_details') > 0;
            $is_remaining = !!get_query_var($pay_slug) && $status === 'partially-paid';

            if (!$is_checkout || $is_email) {
                $total_rows['deposit_amount'] = array(
                  'label' => esc_html($deposit_amount_text),
                  'value' => wc_price($deposit_amount, array('currency' => $order->get_currency()))
                );
                $total_rows['second_payment'] = array(
                  'label' => esc_html($future_pay_text),
                  'value' => wc_price($second_payment, array('currency' => $order->get_currency()))
                );
            }

            if ($is_checkout && !$is_remaining && !$is_email) {
                if ($deposit_paid !== 'yes') {
                  $to_pay = $deposit_amount;
                } elseif ($deposit_paid === 'yes' && $second_payment_paid !== 'yes') {
                  $to_pay = $second_payment;
                }
                $total_rows['paid_today'] = array(
                  'label' => esc_html($to_pay_text),
                  'value' => wc_price($to_pay, array('currency' => $order->get_currency()))
                );
            }

            if ($is_checkout && $is_remaining && !$is_email ) {
                $partial_pay_id = absint(get_query_var($pay_slug));
                $partial_payment = wc_get_order($partial_pay_id);

                $total_rows['paid_today'] = array(
                  'label' => esc_html($to_pay_text),
                  'value' => wc_price($partial_payment->get_total(), array('currency' => $order->get_currency()))
                );
            }
        }
        return $total_rows;
    }

    function awcdp_hidden_order_item_meta($hidden_meta){
        $hidden_meta[] = 'awcdp_deposit_meta';
        return $hidden_meta;
    }

    function awcdp_checkout_payment_url($url, $order){

        $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
        if ($has_deposit == 'yes' && $order->get_type() != AWCDP_POST_TYPE) {
          $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
          if (is_array($schedule) && !empty($schedule)) {
            foreach ($schedule as $payment) {
              $payment_order = wc_get_order($payment['id']);
              if (!$payment_order) {
                continue;//create one
              }
              if (!$payment_order || !$payment_order->needs_payment()) {
                continue;
              }
              $url = $payment_order->get_checkout_payment_url();
              $url = add_query_arg( array( 'payment' => $payment['type'], ), $url );
              break;
            }
          }
        }
        return $url;
    }

    function awcdp_payment_complete($order_id) {

        $order = wc_get_order($order_id);
        if (!$order || $order->get_type() != AWCDP_POST_TYPE){
          return;
        }

        $parent_id = $order->get_parent_id();
        $parent = wc_get_order($parent_id);

        if (!$parent){
          return;
        }
        if ($order->get_meta('_awcdp_deposits_payment_type', true) === 'deposit') {
            $parent->update_meta_data('_awcdp_deposits_deposit_paid', 'yes');
        } elseif ($order->get_meta('_awcdp_deposits_payment_type', true) === 'second_payment') {
            $parent->update_meta_data('_awcdp_deposits_second_payment_paid', 'yes');
        }
        $parent->save();
        $parent->payment_complete();

    }

    function awcdp_create_order($order_id, $checkout){

      if (!isset(WC()->cart->deposit_info['deposit_enabled']) || WC()->cart->deposit_info['deposit_enabled'] !== true) {
        return null;
      }

      $data = $checkout->get_posted_data();

        try {
            $cart_hash = WC()->cart->get_cart_hash();
            $order_id = absint(WC()->session->get('order_awaiting_payment'));
            $order = $order_id ? wc_get_order($order_id) : null;
            $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

            if ($order && $order->has_cart_hash($cart_hash) && $order->has_status(array('pending', 'failed'))) {
              do_action('woocommerce_resume_order', $order_id);
              $order->remove_order_items();
            } else {
              $order = new WC_Order();
            }

            $fields_prefix = array(
              'shipping' => true,
              'billing' => true,
            );

            $shipping_fields = array(
              'shipping_method' => true,
              'shipping_total' => true,
              'shipping_tax' => true,
            );

            foreach ($data as $key => $value) {
                if (is_callable(array($order, "set_{$key}"))) {
                    $order->{"set_{$key}"}($value);
                } elseif (isset($fields_prefix[current(explode('_', $key))])) {
                    if (!isset($shipping_fields[$key])) {
                      $order->update_meta_data('_' . $key, $value);
                    }
                }
            }

            $user_agent = wc_get_user_agent();
            $order->set_created_via('checkout');
            $order->set_cart_hash($cart_hash);
            $order->set_customer_id(apply_filters('woocommerce_checkout_customer_id', get_current_user_id()));
            $order_vat_exempt = WC()->cart->get_customer()->get_is_vat_exempt() ? 'yes' : 'no';
            $order->add_meta_data('is_vat_exempt', $order_vat_exempt);
            $order->set_currency(get_woocommerce_currency());
            $order->set_prices_include_tax('yes' === get_option('woocommerce_prices_include_tax'));
            $order->set_customer_ip_address(WC_Geolocation::get_ip_address());
            $order->set_customer_user_agent($user_agent);
            $order->set_customer_note(isset($data['order_comments']) ? $data['order_comments'] : '');
            $order->set_payment_method('');
            $order->set_shipping_total(WC()->cart->get_shipping_total());
            $order->set_discount_total(WC()->cart->get_discount_total());
            $order->set_discount_tax(WC()->cart->get_discount_tax());
            $order->set_cart_tax(WC()->cart->get_cart_contents_tax() + WC()->cart->get_fee_tax());
            $order->set_shipping_tax(WC()->cart->get_shipping_tax());
            $order->set_total(WC()->cart->get_total('edit'));
            $checkout->create_order_line_items($order, WC()->cart);
            $checkout->create_order_fee_lines($order, WC()->cart);
            $checkout->create_order_shipping_lines($order, WC()->session->get('chosen_shipping_methods'), WC()->shipping()->get_packages());
            $checkout->create_order_tax_lines($order, WC()->cart);
            $checkout->create_order_coupon_lines($order, WC()->cart);

            do_action('woocommerce_checkout_create_order', $order, $data);

            $order_id = $order->save();

            do_action('woocommerce_checkout_update_order_meta', $order_id, $data);

            $order->read_meta_data();
            $payment_schedule = $order->get_meta('_awcdp_deposits_payment_schedule');
            $deposit_id = null;

            if ($payment_schedule) {
            foreach ($payment_schedule as $partial_key => $payment) {

                $partial_payment = new AWCDP_Order();

                $partial_payment->set_customer_id(apply_filters('woocommerce_checkout_customer_id', get_current_user_id()));

                $amount = $payment['total'];

                $name = esc_html__('Partial Payment for order %s', 'deposits-partial-payments-for-woocommerce');
                $partial_payment_name = apply_filters('awcdp_deposits_partial_payment_name', sprintf($name, $order->get_order_number()), $payment, $order->get_id());

                $item = new WC_Order_Item_Fee();

                $item->set_props(
                    array(
                        'total' => $amount
                    )
                );

                $item->set_name($partial_payment_name);
                $partial_payment->add_item($item);

				do_action('awcdp_deposits_do_partial_payment_meta', $partial_payment );

                $partial_payment->set_parent_id($order->get_id());
                $partial_payment->add_meta_data('is_vat_exempt', $order_vat_exempt);
                $partial_payment->add_meta_data('_awcdp_deposits_payment_type', $payment['type']);
                if(is_numeric($partial_key)){
                    $partial_payment->add_meta_data('_awcdp_deposits_partial_payment_date', $partial_key);
                }
                $partial_payment->set_currency(get_woocommerce_currency());
                $partial_payment->set_prices_include_tax('yes' === get_option('woocommerce_prices_include_tax'));
                $partial_payment->set_customer_ip_address(WC_Geolocation::get_ip_address());
                $partial_payment->set_customer_user_agent($user_agent);
                $partial_payment->set_total($amount);
                $partial_payment->save();
                $payment_schedule[$partial_key]['id'] = $partial_payment->get_id();

                $this->add_apifw_invoice_meta($partial_payment, $amount, $partial_payment_name);
				
                // Added for Custom Order Numbers tychesoftwares support
                // $order_number_meta = get_post_meta( $order_id, '_alg_wc_full_custom_order_number', true );
                $order_number_meta = $order->get_meta('_alg_wc_full_custom_order_number', true);
                if( $order_number_meta ){
                  $partial_payment->add_meta_data('_alg_wc_full_custom_order_number', $order_number_meta);
                }

                // Added for payable payment support
                foreach ($data as $key => $value) {
                    if (is_callable(array($order, "set_{$key}"))) {
                      //   $partial_payment->{"set_{$key}"}($value);
                    } elseif (isset($fields_prefix[current(explode('_', $key))])) {
                        if (!isset($shipping_fields[$key])) {
                          $partial_payment->update_meta_data('_' . $key, $value);
                        }
                    }
                }
                $partial_payment->save();

                //fix wpml language
                $wpml_lang = $order->get_meta('wpml_language', true);
                if ($payment['type'] === 'deposit') {
                    $partial_payment->save();
                    $deposit_id = $partial_payment->get_id();
                    $partial_payment->set_payment_method(isset($available_gateways[$data['payment_method']]) ? $available_gateways[$data['payment_method']] : $data['payment_method']);
                    if (!empty($wpml_lang)) {
                        $partial_payment->update_meta_data('wpml_language', $wpml_lang);
                    }
                    $partial_payment->save();
                }
            }
            }

            $order->update_meta_data('_awcdp_deposits_payment_schedule', $payment_schedule);
            $order->save();
            return absint($deposit_id);

        } catch (Exception $e) {
            return new WP_Error('checkout-error', $e->getMessage());
        }

    }


    function awcdp_disable_reorder_for_partial_payments($order_id){
      $order = wc_get_order($order_id);
      if ($order && $order->get_type() == AWCDP_POST_TYPE) {
        remove_action('woocommerce_thankyou', 'woocommerce_order_details_table', 10);
        if( apply_filters('awcdp_disable_orders_details_table', true) ){
          do_action('awcdp_deposits_thankyou', $order);
        }
        remove_action('woocommerce_order_details_after_order_table', 'woocommerce_order_again_button');
      }
    }

    function awcdp_show_myaccount_partial_payments_summary($order){

        $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
        if (is_account_page() && $has_deposit == 'yes' && apply_filters('awcdp_myaccount_show_partial_payments_summary', true, $order)) {
            $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
            if ( is_array($schedule)){

              $atts = array(
                'order_id' => $order->get_id(),
                'schedule' => $schedule,
              );

              $wsettings = new AWCDP_Deposits();
              echo $return_string = $wsettings->awcdp_get_template('order/awcdp-partial-payment-details.php', $atts );

            }


			$balance_text = esc_html__('Make balance payment :', 'deposits-partial-payments-for-woocommerce');
			$balance_text = apply_filters('awcdp_balance_payment_text',$balance_text);

			$actions = wc_get_account_orders_actions( $order );
			if ( ! empty( $actions ) ) {
				foreach ( $actions as $key => $action ) {
					if( $key == 'pay' ){
						echo '<div class="awcdp_balance_pay ">';
						echo '<p>' . $balance_text . '<a href="' . esc_url( $action['url'] ) . '" class="button ' . sanitize_html_class( $key ) . '">' . esc_html( $action['name'] ) . '</a> </p>';
						echo '</div>';
					}
				}
			}


        }

    }

    function awcdp_show_email_partial_payments_summary($order){

        $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
        if ( $has_deposit == 'yes' && apply_filters('awcdp_myaccount_show_partial_payments_summary', true, $order)) {
            $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
            if ( is_array($schedule)){

              $atts = array(
                'order_id' => $order->get_id(),
                'schedule' => $schedule,
              );

              $wsettings = new AWCDP_Deposits();
              echo $return_string = $wsettings->awcdp_get_template('order/awcdp-partial-payment-details.php', $atts );

            }
        }

    }
    function awcdp_show_parent_order_summary($partial_payment){
        if ($partial_payment->get_type() == AWCDP_POST_TYPE ) {

          $atts = array(
            'order_id' => $partial_payment->get_parent_id(),
            'partial_payment' => $partial_payment,
          );

          $wsettings = new AWCDP_Deposits();
          echo $return_string = $wsettings->awcdp_get_template('order/awcdp-order-details.php', $atts );

        }
    }

    function awcdp_deposit_details($order, $sent_to_admin = false, $plain_text = false, $email = ''){

		if(apply_filters('awcdp_email_show_partial_payments_summary',false,$order,$email,$sent_to_admin)){
			return;
		}

      $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
      if ($has_deposit == 'yes'){
        $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
        if (!empty($schedule)){

          $atts = array(
            'order' => $order,
            'sent_to_admin' => $sent_to_admin,
            'email' => $email,
            'plain_text' => $plain_text,
            'schedule' => $schedule,
          );

          $wsettings = new AWCDP_Deposits();
          echo $return_string = $wsettings->awcdp_get_template('emails/email-partial-payments-details.php', $atts );

        }
      }
    }


    function awcdp_partial_payment_number($number, $order){

      if (is_order_received_page() && did_action('woocommerce_before_thankyou') && !did_action('woocommerce_thankyou')){
         return $number;
      }

        if ($order && $order->get_type() == AWCDP_POST_TYPE ) {
            $parent = wc_get_order($order->get_parent_id());
            if ($parent) {
              $count = 0;
              $schedule = $parent->get_meta('_awcdp_deposits_payment_schedule', true);
              $suffix = '-';
              if (!empty($schedule) && is_array($schedule)) {
                foreach ($schedule as $payment) {
                  $count++;
                  if ($payment['id'] == $order->get_id()) {
                    $suffix .= $count;
                    break;
                  }
                }
              }
              $number = $parent->get_order_number() . $suffix;
            }
        }
        return $number;
    }


    function awcdp_adjust_cod_status_completed($status, $order){
      if ($order->get_type() == AWCDP_POST_TYPE ) {
        $status = 'on-hold';
      }
      return $status;
    }

    function awcdp_adjust_second_payment_status($order_id){

        $order = wc_get_order($order_id);
        if ($order) {
        $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);

        if ($order->get_type() != AWCDP_POST_TYPE && $has_deposit == 'yes') {
          $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
          if (!is_array($schedule) || empty($schedule)){
            return;
          }
            foreach ($schedule as $payment) {
              if (isset($payment['id']) && isset($payment['type']) && $payment['type'] !== 'deposit') {
                $second_payment = wc_get_order($payment['id']);
                if ($second_payment && !$second_payment->needs_payment()) {
                    $second_payment->set_status('pending');
                    $second_payment->save();
                }
              }
            }
        }
      }
    }

    function awcdp_set_parent_order_on_hold($order_id){
      $order = wc_get_order($order_id);
      if ($order && $order->get_type() == AWCDP_POST_TYPE) {
        $parent = wc_get_order($order->get_parent_id());
        if ($parent) {
          if ($order->get_payment_method() == 'bacs') {
            $parent->set_payment_method('bacs');
          }
          $parent->set_status('on-hold');
          $parent->save();
        }
      }
    }

    function awcdp_set_partial_payments_as_cancelled($order_id){
        $order = wc_get_order($order_id);
        if ($order && $order->get_type() !== AWCDP_POST_TYPE && $order->get_meta('_awcdp_deposits_order_has_deposit', true) === 'yes') {
            $partial_payments = $this->awcdp_get_order_partial_payments($order_id);
			if($partial_payments){
				foreach ($partial_payments as $single_payment) {
					$single_payment->update_status('cancelled');
				}
            }
        }
	}

	function awcdp_get_order_partial_payments($order_id, $args = array(), $object = true){
		$default_args = array(
			'post_parent' => $order_id,
			'post_type' => AWCDP_POST_TYPE,
			'numberposts' => -1,
			'post_status' => 'any'
		);
		$args = ($args) ? wp_parse_args($args, $default_args) : $default_args;
		$orders = array();
		// $partial_payments = get_posts($args);
    $partial_payments = wc_get_orders($args);
		foreach ($partial_payments as $partial_payment) {
			// $orders[] = ($object) ? wc_get_order($partial_payment->ID) : $partial_payment->ID;
			$orders[] = ($object) ? wc_get_order($partial_payment->get_id()) : $partial_payment->ID;
		}
		return $orders;
	}


    function awcdp_set_parent_order_failed($order_id){
      $order = wc_get_order($order_id);
      if ($order && $order->get_type() == AWCDP_POST_TYPE && $order->get_meta('_awcdp_deposits_payment_type', true) == 'deposit') {
        $parent_id = $order->get_parent_id();
        $parent = wc_get_order($parent_id);
        if ($parent) {
          $parent->update_status('failed');
          $parent->save();
        }
      }
    }

    function awcdp_delete_partial_payments($id){

        if (!current_user_can('delete_posts') || !$id) {
          return;
        }
        // $post_type = get_post_type($id);
        $post_type = false;
        if ( class_exists('OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled() ) {
          $post_type = ('shop_order' === OrderUtil::get_order_type( $id ));
        } else {
          $post_type = (get_post_type($id)==='shop_order');
        }

        // if ($post_type == 'shop_order') {
        if ($post_type) {
          $order = wc_get_order($id);
          if (!$order) {
            return;
          }
          $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
          if ($order->get_type() != AWCDP_POST_TYPE && $has_deposit == 'yes') {
            $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
            if (!is_array($schedule) || empty($schedule)) {
              return;
            }
            foreach ($schedule as $payment) {
              if (isset($payment['id']) && is_numeric($payment['id'])) {
                wp_delete_post(absint($payment['id']), true);
              }
            }
          }
        }
    }


    function awcdp_trash_partial_payments($id){

      if (!current_user_can('delete_posts') || !$id) {
        return;
      }

        // $post_type = get_post_type($id);
        $post_type = false;
        if ( class_exists('OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled() ) {
          $post_type = ('shop_order' === OrderUtil::get_order_type( $id ));
        } else {
          $post_type = (get_post_type($id)==='shop_order');
        }

        // if ($post_type == 'shop_order') {
        if ($post_type) {
          $order = wc_get_order($id);
          if (!$order) {
            return;
          }
          $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
          if ($order->get_type() != AWCDP_POST_TYPE && $has_deposit === 'yes') {
            $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
            if (!is_array($schedule) || empty($schedule)) {
              return;
            }
            remove_filter('pre_trash_post', array($this, 'awcdp_prevent_user_trash_partial_payments'), 10);
            foreach ($schedule as $payment) {
              if (isset($payment['id']) && is_numeric($payment['id'])) {
                wp_trash_post(absint($payment['id']));
              }
            }
            add_filter('pre_trash_post', array($this, 'awcdp_prevent_user_trash_partial_payments'), 10, 2);
          }
        }

    }

    function awcdp_untrash_partial_payments($id){

        if($id) {
          // $post_type = get_post_type($id);
          $post_type = false;
          if ( class_exists('OrderUtil') && OrderUtil::custom_orders_table_usage_is_enabled() ) {
            $post_type = ('shop_order' === OrderUtil::get_order_type( $id ));
          } else {
            $post_type = (get_post_type($id)==='shop_order');
          }

          // if ($post_type == 'shop_order') {
          if ($post_type) {
            $order = wc_get_order($id);
            if ($order){
              $has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true);
              if ($order->get_type() != AWCDP_POST_TYPE && $has_deposit == 'yes') {
                $schedule = $order->get_meta('_awcdp_deposits_payment_schedule', true);
                if (!is_array($schedule) || empty($schedule)){
                  return;
                }
                foreach ($schedule as $payment) {
                  if (isset($payment['id']) && is_numeric($payment['id'])) {
                    wp_untrash_post($payment['id']);
                  }
                }
              }
            }
          }
        }

    }

    function awcdp_cancel_partial_payments($cancel, $order){
      if ($order->get_type() == AWCDP_POST_TYPE ) {
        return false;
      }
      return $cancel;
    }

    function awcdp_prevent_user_trash_partial_payments($trash, $post){
      if (is_object($post) && $post->post_type == AWCDP_POST_TYPE ) {
        $order = wc_get_order($post->ID);
        if ($order) {
          $parent = wc_get_order($order->get_parent_id());
          if ($parent && $parent->get_status() != 'trash') {
            return 'forbidden';
          }
        }
      }
      return $trash;
    }



    function awcdp_disable_payment_emails($enabled, $order,$email){
      if(!is_object($order)) {
        return $enabled;
      }
	  if (apply_filters('awcdp_enable_all_payment_emails', false)) {
		return $enabled;
	  }

      $order = wc_get_order($order->get_id());
      if ($order && $order->get_type() == AWCDP_POST_TYPE ){
        $enabled = false;
      }
      return $enabled;
  }


    function awcdp_email_actions($actions){

      $email_actions = array();
      $mail_actions = array(
        array(
          'from' => array('pending', 'on-hold', 'failed', 'draft'),
          'to' => array('partially-paid')
        ),
        array(
          'from' => array('partially-paid'),
          'to' => array('processing', 'completed', 'on-hold')
        )
      );
      foreach ($mail_actions as $action) {
        foreach ($action['from'] as $from) {
          foreach ($action['to'] as $to) {
            $email_actions[] = 'woocommerce_order_status_' . $from . '_to_' . $to;
          }
        }
      }
      $email_actions[] = 'awcdp_deposits_partial_payment_reminder_email';
      $email_actions = array_unique($email_actions);

      return array_unique(array_merge($actions, $email_actions));

    }

    function awcdp_register_hooks($wc_emails){

      $class_actions = array(
        'WC_Email_New_Order' => array(
          array(
            'from' => array('pending', 'failed', 'draft'),
            'to' => array('partially-paid')
          ),
        ),
        'WC_Email_Customer_Processing_Order' => array(
          array(
            'from' => array('partially-paid'),
            'to' => array('processing')
          ),
        ),
        'WC_Email_Customer_On_Hold_Order' => array(
          array(
            'from' => array('partially-paid'),
            'to' => array( 'on-hold')
          ),
        ),
      );

      foreach ($wc_emails->emails as $class => $instance) {
        if (isset($class_actions[$class])) {
          foreach ($class_actions[$class] as $actions) {
            foreach ($actions['from'] as $from) {
              foreach ($actions['to'] as $to) {
                add_action('woocommerce_order_status_' . $from . '_to_' . $to . '_notification', array($instance, 'trigger'));
              }
            }
          }
        }
      }

    }

    function awcdp_email_classes($emails){

      $emails['AWCDP_Email_Deposit_Paid'] = include('emails/class-awcdp-email-deposit-paid.php');
      return $emails;

    }




  /* For Acowebs checkout plugin */

  function awcdp_awcfe_check_parent( $order_id ) {

  	$order = wc_get_order($order_id);
  	if ($order->get_type() == AWCDP_POST_TYPE) {
  		$parent = ($order->get_parent_id());
  		if ($parent) {
  			return $parent;
  		}
  	}
  	return $order_id;

  }

    /* For Acowebs PDF plugin */

    function awcdp_apifw_invoice_deposit( $custom_fields, $order_id ) {

      if(apply_filters('awcdp_show_in_apifw_invoice',true)){
    	$order = wc_get_order($order_id);
    	$has_deposit = $order->get_meta('_awcdp_deposits_order_has_deposit', true) === 'yes';

    	if ($has_deposit){
    		$awcdp_ts = get_option('awcdp_text_settings');
    		$to_pay_text = ( isset($awcdp_ts['to_pay_text']) && ( $awcdp_ts['to_pay_text'] != '' ) ) ? $awcdp_ts['to_pay_text'] : esc_html__('Due Today', 'deposits-partial-payments-for-woocommerce' );
    		$future_text = ( isset($awcdp_ts['future_payment_text']) && ( $awcdp_ts['future_payment_text'] != '' ) ) ? $awcdp_ts['future_payment_text'] : esc_html__('Future payments', 'deposits-partial-payments-for-woocommerce' );
    		$deposit_text = ( isset($awcdp_ts['deposit_amount_text']) && ( $awcdp_ts['deposit_amount_text'] != '' ) ) ? $awcdp_ts['deposit_amount_text'] : esc_html__('Deposit Amount', 'deposits-partial-payments-for-woocommerce' );

    			$deposit_amount = floatval($order->get_meta('_awcdp_deposits_deposit_amount', true));
    			$second_payment = floatval($order->get_meta('_awcdp_deposits_second_payment', true));

    					$deposit_amount = wc_price($deposit_amount, array('currency' => $order->get_currency()));
    					$future_amount = wc_price($second_payment, array('currency' => $order->get_currency()));

    					return '<br><span><small>('.esc_html($deposit_text).': '.$deposit_amount.'<br>'.esc_html($future_text).': '.$future_amount.')</small></span>';

    		}
      }
    }



    function awcdp_grouped_products_handler( $product_id ){

		$product = wc_get_product($product_id);
        if (!$product || $product->get_type() != 'grouped'){
			return $product_id;
		}
        $_REQUEST['awcdp_grouped_by'] = $product_id;
        add_filter('wc_deposits_add_to_cart_deposit_override', array($this, 'grouped_data_override'));
        return $product_id;

	}

	function grouped_data_override($override) {

        if (isset($_REQUEST['awcdp_grouped_by'])) {

            $grouped_product_id = $_REQUEST['awcdp_grouped_by'];
            $grouped_product = wc_get_product($grouped_product_id);
            if ($grouped_product) {
                $override['enable'] = $this->awcdp_deposits_enabled($grouped_product_id);
                $override['force'] = $this->awcdp_deposits_forced($grouped_product_id);
                $override['amount_type'] = $grouped_product->get_meta(AWCDP_DEPOSITS_TYPE, true);

				  $typ = $grouped_product->get_meta(AWCDP_DEPOSITS_TYPE, true);
				  if( $typ == 'payment_plan' ){
					$amount = $grouped_product->get_meta(AWCDP_DEPOSITS_PLAN, true);
				  } else {
					$amount = $grouped_product->get_meta(AWCDP_DEPOSITS_AMOUNT, true);
				  }
                $override['amount'] = floatval($amount);

                if (isset($_REQUEST['awcdp_deposit_option'])) {
                    if($override['force']) $override['enable']  = true;
                    $override['enable'] = $_REQUEST['awcdp_deposit_option'] == 'yes';
                }

				/*
                if ($override['amount_type'] === 'payment_plan') {
                    if ((isset($_REQUEST['awcdp-'. $grouped_product_id . '-plan']))) {
                        $override['payment_plan'] = $_REQUEST['awcdp-'. $grouped_product_id . '-plan'];
                    } else {
                        $available_plans = wc_deposits_get_product_available_plans($grouped_product_id);

                        if (is_array($available_plans)) {
                            $override['payment_plan'] = $available_plans[0];
                        }
                    }
                }
				*/
            }
        }

        return $override;
    }

	function awcdp_deposits_forced( $product_id ){
		$forced = false;
		$product = wc_get_product( $product_id );

		if($product){
		  $forced = get_post_meta( $product_id, AWCDP_DEPOSITS_FORCE, true );
		}

		return $forced;
	}


    function add_apifw_invoice_meta($partial_payment, $amount, $name){

      $data = array(
        'id' => false,
        'subtotal' => $amount,
        'subtotal_tax' => 0,
        'total' => $amount,
        'total_tax' => 0,
        'price' => $amount,
        'price_after_discount' => $amount,
        'quantity' => '',
        'weight' => '',
        'total_weight' => '',
        'weight_unit' => '',
        'tax_class' => '',
        'tax_status' => '',
        'tax_percent' => 0,
        'tax_label' => '',
        'tax_pair' => '',
        'tax_array' => '',
        'name' => $name,
        'product_id' => '',
        'variation_id' => '',
        'product_url' => '',
        'product_thumbnail_url' => '',
        'sku' => '',
        'meta' => '',
        'formatted_meta' => '',
        'raw_meta' => '',
        'category' => ''
    );

      $partial_payment->add_meta_data('_awcdp_apifw_invoice_meta', $data);
    }




    function awcdp_modify_cart_data(){

      $stream = file_get_contents('php://input');
      $json = json_decode($stream, true);

      if (isset($json['context']) && $json['context'] === 'cart'||  $json['context'] === 'checkout') {

        $this->awcdp_calculated_total(WC()->cart->total, WC()->cart);
        if (isset(WC()->cart->deposit_info, WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] !== true) {
          return;
        }
        WC()->cart->set_total(WC()->cart->deposit_info['deposit_amount']);

      }

    }

    function awcdp_dpo_pay_order_create( $order ) {
      if($order->get_type() == AWCDP_POST_TYPE ){
        $order = wc_get_order($order->get_parent_id());
      }
      return $order;
    }

    function awcdp_update_WebToffee_stripe_cart_total() {
      if (isset(WC()->cart->deposit_info, WC()->cart->deposit_info['deposit_enabled']) && WC()->cart->deposit_info['deposit_enabled'] == true) {
        $total = WC()->cart->deposit_info['deposit_amount'];
         }
      return $total;
    }



























}
