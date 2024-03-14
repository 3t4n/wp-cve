<?php

if (!defined('ABSPATH'))
    exit;

use Automattic\WooCommerce\Utilities\OrderUtil;

class AWCFE_Front_End
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

    function __construct($file = '', $version = '1.0.0')
    {
// Load frontend JS & CSS

        $this->_version = $version;
        $this->_token = AWCFE_TOKEN;

        /**
         * Check if WooCommerce is active
         * */
        if ($this->check_woocommerce_active()) {


            $this->file = $file;

            $this->assets_url = esc_url(trailingslashit(plugins_url('/assets/', $this->file)));

            add_filter('woocommerce_checkout_fields', array($this, 'get_checkout_fields'), 99999, 1);

            add_filter('woocommerce_get_country_locale_default', array($this, 'get_country_locale_default'), 10, 1);
		        add_filter('woocommerce_get_country_locale_base', array($this, 'get_country_locale_default'), 10, 1);
            add_filter('woocommerce_get_country_locale', array($this, 'get_country_locale_country'), 20, 1);

            add_action('woocommerce_checkout_update_order_meta', array($this, 'update_order_meta'), 10, 2);
            add_action('woocommerce_form_field', array($this, 'woocommerce_form_field'), 10, 4);
            add_action('woocommerce_order_details_after_order_table', array($this, 'order_details_after_order_table'), 10, 1);
            add_action('woocommerce_email_after_order_table', array($this, 'email_after_order_table'), 10, 1);

            // add_action( 'woocommerce_admin_order_data_after_order_details' , array($this,'fields_display_order_data_custom_in_admin' ),20,1);

            add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'fields_display_order_data_billing_in_admin'), 20, 1);
            
            add_filter( 'woocommerce_form_field_checkbox', array($this, 'awcfe_checkout_fields_checkbox_field'), 10, 4 );

            add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'fields_display_order_data_shipping_in_admin'), 20, 1);
            add_action('updated_post_meta', array($this, 'updated_order_meta'), 20, 4);

            if( get_option( 'woocommerce_custom_orders_table_data_sync_enabled' ) != 'yes' ){
              add_action('save_post', array($this, 'before_order_object_save'),10, 1);
            }
            add_action('woocommerce_process_shop_order_meta', array($this, 'before_order_object_save'),10, 1);
            
            
            /* custom checkout validation */
      			if ($this->check_woocommerce_germanized_active()) {
      				add_action('woocommerce_after_checkout_validation', array($this, 'awcfe_custom_fields_validation'),0, 2);
      			} else {
      				add_action('woocommerce_after_checkout_validation', array($this, 'awcfe_custom_fields_validation'),10, 2);
      			}

            // add_action( 'wp_footer', array($this, 'awcfe_custom_footer_script'), 100 );

            add_action('wp_enqueue_scripts', array($this, 'frontend_enqueue_styles'), 20, 1);
            add_filter( 'woocommerce_is_rest_api_request', function(){} );
           
		
            add_action( 'woocommerce_before_checkout_form', array( $this, 'add_inline_scripts' ) );
            add_filter( 'woocommerce_ship_to_different_address_checked', array( $this, 'force_shipping_address' ) );
            add_filter( 'woocommerce_cart_needs_shipping_address', array( $this, 'force_shipping_address' ) );
            add_filter( 'woocommerce_order_needs_shipping_address', array( $this, 'force_shipping_address' ) );
            add_filter( 'gettext',array($this,'sb_text_strings'),  20, 3 );
            add_action('wp_head',array($this,'hide_additional_fields_css') );
            add_filter( 'woocommerce_create_account_default_checked', array( $this, 'auto_create_account' ) );
            add_action( 'init',array($this,'remove_privacy_text'), 20 );
            add_action( 'init' ,array( $this,'coupon_msg_show'),20);
            add_filter( 'option_woocommerce_ship_to_destination', array( $this, 'remove_checkout_shipping_address' ), 10, 3 );
            add_action( 'init',array($this,'remove_terms_and_condition'), 20 );

            
        }

    }


    public function check_woocommerce_active()
    {
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

	function check_woocommerce_germanized_active()
    {
        if (in_array('woocommerce-germanized/woocommerce-germanized.php', apply_filters('active_plugins', get_option('active_plugins')))) {
            return true;
        }
        return false;
    }

    public static function instance($parent)
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($parent);
        }
        return self::$_instance;
    }


    public function frontend_enqueue_styles($hook = '')
    {
        wp_register_style($this->_token . '-frontend', esc_url($this->assets_url) . 'css/frontend.css', array(), $this->_version);
        wp_enqueue_style($this->_token . '-frontend');
    }

    /*
    public function awcfe_custom_footer_script(){
      ?>
      <script>
      jQuery(window).load(function(){
        jQuery(".checkout.woocommerce-checkout .form-row").each(function() {
            if(jQuery(this).is(":hidden")){
              jQuery(this).find('.woocommerce-input-wrapper input, .woocommerce-input-wrapper select, .woocommerce-input-wrapper textarea, .woocommerce-input-wrapper .input-text ').attr('disabled', true);
            }
        });
      });
      </script>
      <?php
    }
    */

    public function getSectionDefaultTitle($section) {

        $customSections = get_option(AWCFE_FIELDS_KEY);
		if ( !$customSections ){ return $section; }
        $sectionName = $customSections['fields'][$section]['extra']['name'];
        $sectionName = str_replace("Fields","",$sectionName);
        return $sectionName;

    }

    public function email_after_order_table($order)
    {
        $order_id = $order->get_id();
        // $awcf_data = get_post_meta($order_id, AWCFE_ORDER_META_KEY, true);
        $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);
        if( is_array($awcf_data) ){
          unset($awcf_data['account']);
        }

        if ($awcf_data) {
            echo '<table cellspacing="0" cellpadding="6" style="color:#636363;border:1px solid #e5e5e5;vertical-align:middle;width:100%;margin-bottom: 40px;" >';
            foreach ($awcf_data as $section => $fields) {

                $outString = $outString1 ='';
                $sectionName = $this->getSectionDefaultTitle($section);

                if ($fields) {
                    $outString1 .= '<tr class="awcfe-' . $section . '-extra-items" ><td colspan="2" style="border: 1px solid #e5e5e5;" >' . __(ucfirst($sectionName), 'woocommerce') . ' ' . __('Extra Fields', 'checkout-field-editor-and-manager-for-woocommerce') . ' </td></tr>';
                }
                uasort($fields, 'wc_checkout_fields_uasort_comparison');
                $row_template = '<tr class="awcfe-' . $section . '-extra-items" ><th style="border: 1px solid #e5e5e5;">%1$s</th><td style="border: 1px solid #e5e5e5;">%2$s</td></tr>';
                foreach ($fields as $key => $val) {
                    if (isset($val['show_in_email']) && $val['show_in_email'] === true) {

                        if($val['type'] == 'header' || $val['type'] == 'paragraph' ){
                            $outString .= sprintf($row_template, $val['label'], $val['value']);
                        }
                        if (!empty($val['value'])) {
                            if (is_array($val['value'])) {
                                $outString .= sprintf($row_template, $val['label'], esc_attr(implode(', ', $val['value'])));
                            } else {
                                $outString .= sprintf($row_template, $val['label'], nl2br($val['value']));
                            }
                        }
                        // echo sprintf($row_template, $val['label'], $val['value']);
                    }
                }
                if( $outString ){ echo $outString1.''.$outString; }

            }
            echo '</table>';
        }

    }

    public function order_details_after_order_table($order)
    {
        $order_id = $order->get_id();
        // $awcf_data = get_post_meta($order_id, AWCFE_ORDER_META_KEY, true);
        $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);
        if( is_array($awcf_data) ){
          unset($awcf_data['account']);
          
        }

        if ($awcf_data) {
            echo '<table class="woocommerce-table shop_table order_details has-background awcfe-order-extra-details">';
            foreach ($awcf_data as $section => $fields) {

                $outString = $outString1 ='';
                $sectionName = $this->getSectionDefaultTitle($section);

                if ($fields) {
                    $outString1 .= '<tr class="awcfe-' . $section . '-extra-items" ><td colspan="2" >' . __(ucfirst($sectionName), 'woocommerce') . ' ' . __('Extra Fields', 'checkout-field-editor-and-manager-for-woocommerce') . ' </td></tr>';
                }
                uasort($fields, 'wc_checkout_fields_uasort_comparison');
                $row_template = '<tr class="awcfe-' . $section . '-extra-items" ><th>%1$s</th><td>%2$s</td></tr>';
                foreach ($fields as $key => $val) {
                    if (isset($val['show_in_order_page']) && $val['show_in_order_page'] === true) {

                        if($val['type'] == 'header' || $val['type'] == 'paragraph' ){
                            $outString .= sprintf($row_template, $val['label'], $val['value']);
                        }
                        if (!empty($val['value'])) {
                            if (is_array($val['value'])) {
                                $outString .= sprintf($row_template, $val['label'], esc_attr(implode(', ', $val['value'])));
                            } else {
								if ($val['type'] == 'url' ) {
									$outString .= sprintf($row_template, $val['label'], '<a href="' . $val['value'] . '" target="_blank">' . $val['value'] . '</a>' );
								} 
                
                else {
									$outString .= sprintf($row_template, $val['label'], nl2br($val['value']));
								}
                            }
                        }
                        // echo sprintf($row_template, $val['label'], $val['value']);
                    }
                }
                if( $outString ){ echo $outString1.''.$outString; }


            }
            echo '</table>';
        }


    }

    public function woocommerce_form_field($field, $key, $args, $value)
    {
        if ($args['type'] === 'paragraph') {
            $field .= '<p class="' . AWCFE_TOKEN . '_paragraph_field " >' . do_shortcode(nl2br($args['label'])) . '</p>';
            if (!empty($field)) {
                $field_html = '';

                $field_html .= $field;
                $container_class = esc_attr(implode(' ', $args['class']));
                $sort = $args['priority'] ? $args['priority'] : '';
                $field_container = '<div class="form-row %1$s"  data-priority="' . esc_attr($sort) . '">%2$s</div>';
                $field = sprintf($field_container, $container_class, $field_html);
            }
        }

        if ($args['type'] === 'header') {
            $field .= '<' . $args['subtype'] . ' class="' . AWCFE_TOKEN . '_paragraph_field " >' . do_shortcode(nl2br($args['label'])) . '</' . $args['subtype'] . '>';
            if (!empty($field)) {
                $field_html = '';

                $field_html .= $field;
                $container_class = esc_attr(implode(' ', $args['class']));
                $sort = $args['priority'] ? $args['priority'] : '';
                $field_container = '<div class="form-row %1$s"  data-priority="' . esc_attr($sort) . '">%2$s</div>';
                $field = sprintf($field_container, $container_class, $field_html);
            }
        }

        if( !empty($args['bindingKey'])){
            $fieldID = $args['bindingKey']."_field";
        } else if( !empty($args['name'])){
            $fieldID = $args['name']."_field";
        }

        if ($args['type'] === 'numberfield') {

            $container_class = esc_attr(implode(' ', $args['class'] ));

            $custm_class = !empty($args['custom_class']) ? $args['custom_class'] : '';
            $sort = !empty($args['priority']) ? $args['priority'] : '';
            $req = ($args['required']==true) ? 'validate-required' : '';
            $reqA = ($args['required']==true) ? '<abbr class="required" title="required">*</abbr>' : '';
            //$maxlength = !empty($args['maxlength']) ? 'maxlength="'.$args['maxlength'].'"' : '';
            $defaultVal = !empty($args['default']) ? $args['default'] : '';
            $defaultVal = apply_filters( 'woocommerce_checkout_get_value', $defaultVal, $args['name'] );
            
            $min = isset($args['min']) ? 'min="'.$args['min'].'"' : '';
            $max = isset($args['max']) ? 'max="'.$args['max'].'"' : '';
            $step = isset($args['step']) ? 'step="'.$args['step'].'"' : '';

            
              $field = '<p class="form-row ' . AWCFE_TOKEN . '_number_field '.$container_class.' '.$custm_class.'  '.$req.'" id="'.$fieldID.'" data-priority="' . esc_attr($sort) . '" >';
              $field .= '<label for="'.$args['name'].'" >'.$args['label'].'&nbsp; '.$reqA.' </label>';
              $field .= '<span class="woocommerce-input-wrapper"><input type="number" class="input-text" name="'.$args['name'].'" value="'.$defaultVal.'" id="'.$args['name'].'" placeholder="'.@$args['placeholder'].'" '. $min .' '. $max .' '. $step .'  autocomplete="off" data-type="numberfield"  />';
              $field .= '</span>';
              $field .= '</p>';
              
                     
        }                  

        if ($args['type'] === 'toggleSwitch') {

          $container_class = !empty($args['class'])  ? esc_attr(implode(' ', $args['class'])) : '';

          $custm_class = !empty($args['custom_class']) ? ' '.$args['custom_class'] : '';
          $sort = !empty($args['priority']) ? $args['priority'] : '';
          $req = ($args['required']==true) ? ' validate-required ' : '';
          $reqA = ($args['required']==true) ? '<abbr class="required" title="required">*</abbr>' : '';
          $is_checked = ( !empty($args['is_checked']) && ($args['is_checked']==true) ) ? ' checked="checked"' : '';
          $defaultVal = !empty($args['default']) ? $args['default'] : '';

          $field .= '<p class="form-row ' . AWCFE_TOKEN . '_toggleSwitch_field '.$container_class.''.$custm_class.''.$req.'" id="'.$fieldID.'" data-priority="' . esc_attr($sort) . '" >';
          $field .= '<label for="'.$args['name'].'" class="awcfe-form-label" >';
          $field .= '<span> '.$args['label'].'</span>&nbsp; '.$reqA.' </label>';
          $field .= '<input type="checkbox" class="input-checkbox" name="'.$args['name'].'" value="'.$defaultVal.'" id="'.$args['name'].'"  '.$is_checked.'  data-type="toggleSwitch"  />';
          $field .= '<label for="'.$args['name'].'" class="awcfe-formToggle" >'.$args['label'].'</label>';
          $field .= '</p>';         
        }
        return $field;
    }


  function awcfe_checkout_fields_checkbox_field( $field, $key, $args, $value ) {

    if( empty($args['custom']) || $args['custom'] != 1 ){
			return $field;
		}

    $field = ($field) ? $field : ''; //php8

		if( !empty($args['id']) && $args['id'] == 'mailpoet_woocommerce_checkout_optin' ){
			return $field;
		}

      $container_class = !empty($args['class'])  ? esc_attr(implode(' ', $args['class'])) : '';

      $custm_class = !empty($args['custom_class']) ? ' '.$args['custom_class'] : '';
      $sort = !empty($args['priority']) ? $args['priority'] : '';
      $req = ($args['required']==true) ? ' validate-required ' : '';
      $reqA = ($args['required']==true) ? '<abbr class="required" title="required">*</abbr>' : '';
      $is_checked = ( !empty($args['is_checked']) && ($args['is_checked']==true) ) ? ' checked="checked"' : '';
      $defaultVal = !empty($args['default']) ? $args['default'] : '';
      $argsName = !empty($args['name']) ? $args['name'] : '';

      $field = '<p class="form-row ' . AWCFE_TOKEN . '_check_box_field '.$container_class.''.$custm_class.''.$req.' " id="' . esc_attr( $key ) . '_field" data-priority="' . esc_attr($sort) . '" >';
      $field .= '<label for="'.$argsName.'" >';
      $field .= '<input type="checkbox" class="input-checkbox" name="'.$argsName.'" value="'.$defaultVal.'" id="'.$argsName.'"  '.$is_checked.'  data-type="check-box" />';
      $field .= '<span> '.$args['label'].'</span>&nbsp; '.$reqA.' </label>';

      $field .= '</p>';

      return $field;

    }

    public function update_order_meta($order_id, $postData)
    {

        $shipto_diff = isset($postData['ship_to_different_address']) ? $postData['ship_to_different_address'] : false;

        $checkout_fields = WC()->checkout()->get_checkout_fields();
        $fieldSchema = [];

        //$user_id = get_current_user_id();
        $order = $order_id ? wc_get_order($order_id) : null;
        foreach ($checkout_fields as $sekKey => $section) {

          if( $sekKey == 'shipping' && ( ! $shipto_diff || ! WC()->cart->needs_shipping_address() )){
            continue;
          }

            $fieldSchema[$sekKey] = [];
            foreach ($section as $key => $field) {
                if (isset($field['custom']) && $field['custom'] && isset($postData[$key])) {

                  if( $field['type'] == 'checkbox' ){

                    $value = '';
                    if( isset($_POST[$key]) ){
                      $value = wc_clean($_POST[$key]);
                    }
                  } 
                  else if($field['type'] == 'textarea'){
                    $value = sanitize_textarea_field($postData[$key]);
  
                  }
                  else {
                    $value = wc_clean($postData[$key]);
                  }

                    $meta_id = false;
                    if ($value) {
                        // $meta_id = update_post_meta($order_id, '_' . $key, $value);
                        $order = wc_get_order($order_id);
                        $order->update_meta_data('_' . $key,$value);
                        $order->save();
                    }
                    if (!in_array($field['type'], ['paragraph', 'header'])
                        || (isset($field['show_in_email']) && $field['show_in_email'] === true)
                        || (isset($field['show_in_order_page']) && $field['show_in_order_page'] === true))
                        $fieldSchema[$sekKey][] = array(
                            'type' => $field['type'],
                            'meta_id' => $meta_id,
                            'name' => $field['name'],
                            'label' => (isset($field['label'])) ? (($field['label'] == '') ? AWCFE_EMPTY_LABEL : $field['label']) : AWCFE_EMPTY_LABEL,
                            'value' => $value,
                            'priority' => $field['priority'],
                            'col' => $field['col'],
                            'show_in_email' => isset($field['show_in_email']) ? $field['show_in_email'] : false,
                            'show_in_order_page' => isset($field['show_in_order_page']) ? $field['show_in_order_page'] : false,
                        );

                }
            }
        }

        if (!empty($fieldSchema)) {
            // update_post_meta($order_id, AWCFE_ORDER_META_KEY, $fieldSchema);
            $order->update_meta_data(AWCFE_ORDER_META_KEY, $fieldSchema);
            $order->save();
        }

        //  /* usermeta */
        //  $accountMeta = $fieldSchema['account'];
        //  if($user_id && $user_id != 0){
        //    if($accountMeta){
        //      foreach($accountMeta as $accountMetaDet){
 
        //        update_user_meta($user_id, $accountMetaDet['name'], $accountMetaDet['value']);
 
        //      }
        //    }
        //  }


    }

    public function checkDefaultFieldAttr($field) {

        $customSections = get_option(AWCFE_FIELDS_KEY);
        if(!empty($customSections) && array_key_exists("billing", $customSections['fields'] ) ){
        $sectionDet = $customSections['fields']['billing']['fields'];
        if($sectionDet){
          foreach ($sectionDet as $key => $value) {
            foreach ($value as $skey => $svalue) {
              if($field == $svalue['name']){
                return @$svalue['required'];
              }
            }
          }
        }
        }

    }


    function get_country_locale_country($fields)
    {

	if(is_wc_endpoint_url('edit-address')){
		return $fields;
	}else{
        if (is_array($fields)) {
            foreach ($fields as $key => $val) {
              foreach($val as $vkey => $vval){

                if (isset($vval['priority'])) {
                    unset($fields[$key][$vkey]['priority']);
                }
                if (isset($vval['label'])) {
                    unset($fields[$key][$vkey]['label']);
                }
                if (isset($vval['required'])) {
                    //unset($fields[$key][$vkey]['required']);
		                $fields[$key][$vkey]['required'] = false;
                }
      		      if (isset($vval['class'])) {
                    unset($fields[$key][$vkey]['class']);
                }
      		      if (isset($vval['placeholder'])) {
                    unset($fields[$key][$vkey]['placeholder']);
                }
                // if (isset($vval['validate'])) {
                    // unset($fields[$key][$vkey]['validate']);
                // }

              }
            }
        }

        return $fields;
      }
    }


    function get_country_locale_default($fields)
    {
        if (is_array($fields)) {
            foreach ($fields as $key => $val) {

                $retVal = $this->checkDefaultFieldAttr('billing_'. $key);

                if (isset($val['priority'])) {
                    unset($fields[$key]['priority']);
                }

                if (isset($val['label'])) {
                    unset($fields[$key]['label']);
                }

                if (isset($val['required'])) {
                  $fields[$key]['required'] = $retVal;
                }

                if (isset($val['class'])) {
                    unset($fields[$key]['class']);
                }

                if (isset($val['placeholder'])) {
                    unset($fields[$key]['placeholder']);
                }

                // if (isset($val['validate'])) {
                  // unset($fields[$key]['validate']);
                // }

            }
        }

        return $fields;
    }


    /**
     * @param $defaultFields
     * @return array|mixed|void
     */
    public function get_checkout_fields($defaultFields)
    {
        $fields = new AWCFE_Fields();
        return $fields->getFields($defaultFields);
    }



  //   public function getSectionStatus($section) {

  //     if( $section == 'billing' || $section == 'shipping' || $section == 'order' ){
  //         $sectionStatus = 1;
  //     } else {
  //         $customSections = get_option(AWCFE_FIELDS_KEY);
  //         $sectionStatus = @$customSections['fields'][$section]['extra']['enableSec'];
  //     }
  //     return $sectionStatus;

  // }


  //   public function fields_display_order_data_custom_in_admin($order) {

  //     $result = '';
  //     $order_id = $order->get_id();
  //     $order_id = apply_filters('awcfe_deposits_check_parent_exists', $order_id);
  //     // $awcf_data = get_post_meta($order_id, AWCFE_ORDER_META_KEY, true);
  //     $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);
      

  //     if( is_array($awcf_data) ) {
  //         unset($awcf_data['billing']);
  //         unset($awcf_data['shipping']);
  //         unset($awcf_data['order']);
  //         //unset( $awcf_data['account'] );
  //     }

  //     $extrasec = 1;
  //     if ($awcf_data) {
  //         foreach ($awcf_data as $section => $fields) {
  //             $sectionStatus = $this->getSectionStatus($section);
  //              if($sectionStatus == 1){
  //                 $sectionName = $this->getSectionDefaultTitle($section);
  //                 if ($fields) {
  //                     $result .= $this->fields_display_order_data_billing_in_admin($order);
  //                     $extrasec = 0;
  //                 }
  //              }
  //         }
  //     }
  //     echo $result;
  // }


    /* 05 Aug 19 */

    public function fields_display_order_data_billing_in_admin($order)
    {
     
        // echo 'billing';
        $result = '';
        $order_id = $order->get_id();
        // $awcf_data = get_post_meta($order_id, AWCFE_ORDER_META_KEY, true);
        $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);
      
        if ($awcf_data) {
          if(array_key_exists('billing',$awcf_data)){
            $billing = $awcf_data['billing'];
            if ($billing) {
                $result .= '<div class="address" style="clear:left;" >';
                $result .= '<h3>' . __('Billing extra fields', 'checkout-field-editor-and-manager-for-woocommerce') . '</h3>';
                foreach ($billing as $billing_det) {
                  if( $billing_det['type'] !== 'header' && $billing_det['type'] !== 'paragraph' ){
                    $result .= '<p><strong>' . $billing_det['label'] . ':</strong> ' . nl2br($billing_det['value']) . '</p>';
                  }
                }
               $result .= '</div>';

               $result .= '<div class="edit_address " style="clear:left;" >';
               $result .= '<h3>' . __('Billing extra fields', 'checkout-field-editor-and-manager-for-woocommerce') . '</h3>';
               ob_start();
                 foreach ($billing as $billing_det) {
                   if( $billing_det['type'] !== 'header' && $billing_det['type'] !== 'paragraph' ){
                     if( $billing_det['type'] == 'textarea' ){
                       woocommerce_wp_textarea_input( array(
                 				'id' => '_'.$billing_det['name'],
                 				'label' => $billing_det['label'],
                 				'value' => $billing_det['value'],
                 				'wrapper_class' => 'form-field-wide'
                 			) );
                     } else {
                       woocommerce_wp_text_input( array(
                         'id' => '_'.$billing_det['name'],
                         'label' => $billing_det['label'],
                         'value' => $billing_det['value'],
                         'wrapper_class' => 'form-field'
                       ) );
                     }
                   }
                 }
                 
                 $message = ob_get_contents();
                 ob_end_clean();
                 $result .= $message.'</div>';

            }
        }
      }
        echo $result;
    }

    public function fields_display_order_data_shipping_in_admin($order)
    {
        // echo 'shipping';
        $result = '';
        $order_id = $order->get_id();
        // $awcf_data = get_post_meta($order_id, AWCFE_ORDER_META_KEY, true);
        $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);

        if ($awcf_data) {
          if(array_key_exists('order',$awcf_data)){
            $billing_order = $awcf_data['order'];
            if ($billing_order) {
              $result .= '<div class="address" style="clear:left;" >';
                $result .= '<h3>' . __('Order extra fields', 'checkout-field-editor-and-manager-for-woocommerce') . '</h3>';
                foreach ($billing_order as $billing_order_det) {
                  if( $billing_order_det['type'] !== 'header' && $billing_order_det['type'] !== 'paragraph' ){
                    if( $billing_order_det['value'] ){
                      $result .= '<p><strong>' . $billing_order_det['label'] . ':</strong> ' . nl2br($billing_order_det['value']) . '</p>';
                    }
                  }
                }
              $result .= '</div>';


              $result .= '<div class="edit_address " style="clear:left;" >';
                $result .= '<h3>' . __('Order extra fields', 'checkout-field-editor-and-manager-for-woocommerce') . '</h3>';
              ob_start();
                foreach ($billing_order as $billing_order_det) {
                  if( $billing_order_det['type'] !== 'header' && $billing_order_det['type'] !== 'paragraph' ){
                    if( $billing_order_det['type'] == 'textarea' ){
                      woocommerce_wp_textarea_input( array(
                				'id' => '_'.$billing_order_det['name'],
                				'label' => $billing_order_det['label'],
                				'value' =>$billing_order_det['value'],
                				'wrapper_class' => 'form-field-wide'
                			) );
                    } else {
                      woocommerce_wp_text_input( array(
                        'id' => '_'.$billing_order_det['name'],
                        'label' => $billing_order_det['label'],
                        'value' => $billing_order_det['value'],
                        'wrapper_class' => 'form-field'
                      ) );
                    }
                    /*woocommerce_wp_text_input( array(
                      'id' => '_'.$billing_order_det['name'],
                      'label' => $billing_order_det['label'],
                      'value' => $billing_order_det['value'],
                      'wrapper_class' => 'form-field'
                    ) );*/
                  }
                }

                $message = ob_get_contents();
                ob_end_clean();
                $result .= $message.'</div>';

            }
          }
        
        
          if(array_key_exists('shipping',$awcf_data)){
            $billing = $awcf_data['shipping'];
            if ($billing) {
              $result .= '<div class="address" style="clear:left;" >';
                $result .= '<h3>' . __('Shipping extra fields', 'checkout-field-editor-and-manager-for-woocommerce') . '</h3>';
                foreach ($billing as $billing_det) {
                  if( $billing_det['type'] !== 'header' && $billing_det['type'] !== 'paragraph' ){
                    $result .= '<p><strong>' . $billing_det['label'] . ':</strong> ' . nl2br($billing_det['value']) . '</p>';
                  }
                }
              $result .= '</div>';

             $result .= '<div class="edit_address " style="clear:left;" >';
             $result .= '<h3>' . __('Shipping extra fields', 'checkout-field-editor-and-manager-for-woocommerce') . '</h3>';
             ob_start();
               foreach ($billing as $billing_det) {
                 if( $billing_det['type'] !== 'header' && $billing_det['type'] !== 'paragraph' ){
                   if( $billing_det['type'] == 'textarea' ){
                     woocommerce_wp_textarea_input( array(
               				'id' => '_'.$billing_det['name'],
               				'label' => $billing_det['label'],
               				'value' => $billing_det['value'],
               				'wrapper_class' => 'form-field-wide'
               			) );
                   } else {
                     woocommerce_wp_text_input( array(
                       'id' => '_'.$billing_det['name'],
                       'label' => $billing_det['label'],
                       'value' => $billing_det['value'],
                       'wrapper_class' => 'form-field'
                     ) );
                   }
                 }
               }

               $message = ob_get_contents();
               ob_end_clean();
               $result .= $message.'</div>';


            }
          }

        }
        echo $result;
    }


    function updated_order_meta($meta_id, $object_id, $meta_key, $_meta_value)
    {
        $order = wc_get_order($object_id);
        if ($order === false)
            return false;

        // $awcf_data = get_post_meta($object_id, AWCFE_ORDER_META_KEY, true);
        $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);
        $fieldset = [];

        if ($awcf_data) {
            foreach ($awcf_data as $key => $field) {
                $fieldset[$key] = [];
                foreach ($field as $skey => $sfield) {
                    if ($sfield['meta_id'] == $meta_id && $sfield['name'] == $meta_key) {
                        $sfield['value'] = $_meta_value;
                        $fieldset[$key][] = $sfield;
                    } else {
                        $fieldset[$key][] = $sfield;
                    }
                }
            }
        }
        if (!empty($fieldset)) {
          $order->update_meta_data( AWCFE_ORDER_META_KEY, $fieldset );
          $order->save();
          // update_post_meta($object_id, AWCFE_ORDER_META_KEY, $fieldset);
        }
    }

    public function before_order_object_save($arg=false){
    if ($arg) {
      $typ = false;
			if ( OrderUtil::custom_orders_table_usage_is_enabled()  ) {
				$typ = ('shop_order' === OrderUtil::get_order_type( $arg ));
			} else {
				$typ = (get_post_type($arg)==='shop_order');
			}
		  
           if( $typ ){
           // if(get_post_type($arg)==='shop_order'){
            $order = wc_get_order( $arg );
              //$awcf_data = get_post_meta($arg, AWCFE_ORDER_META_KEY, true);
             $awcf_data = $order->get_meta(AWCFE_ORDER_META_KEY, true);
             $fieldset = [];
             if ($awcf_data) {
                 foreach ($awcf_data as $key => $field) {
                     $fieldset[$key] = [];
                     foreach ($field as $skey => $sfield) {
                         $fieldname = '_'.$sfield['name'];
                         if( isset( $_POST[ $fieldname ] ) ){
                           $sfield['value'] = $_POST[ $fieldname ];
                         }
                         $fieldset[$key][] = $sfield;
                     }
                 }
             }

             if (!empty($fieldset)) {

               $order->update_meta_data( AWCFE_ORDER_META_KEY, $fieldset );
               $order->save();
                // update_post_meta($arg, AWCFE_ORDER_META_KEY, $fieldset);
             }


           }
        }
    }

    public function awcfe_custom_fields_validation($data, $errors){

        if( !empty( $errors->get_error_codes() ) ) {
            foreach( $errors->get_error_codes() as $code ) {
                $errors->remove( $code );
            }
        }

        $checkout_fields = WC()->checkout()->get_checkout_fields();

        $shipto_diff = isset($data['ship_to_different_address']) ? $data['ship_to_different_address'] : false;

        foreach($checkout_fields as $fieldset_key => $fieldset){

            if( $fieldset_key == 'shipping' && ( ! $shipto_diff || ! WC()->cart->needs_shipping_address() )){
                continue;
            }

            $validate_fieldset = true;

            foreach ( $fieldset as $key => $field ) {

                if ( ! isset( $data[ $key ] ) ) {
                    continue;
                }

                $required    = ! empty( $field['required'] );
                $format      = array_filter( isset( $field['validate'] ) ? (array) $field['validate'] : array() );
                $field_label = isset( $field['label'] ) ? $field['label'] : '';

                //$Section_label = $this->getSectionDefaultTitle($fieldset_key);
        				switch ( $fieldset_key ) {
        					case 'shipping':
        						/* translators: %s: field name */
        						$field_label = sprintf( _x( 'Shipping %s', 'checkout-validation', 'woocommerce' ), $field_label );
        						break;
        					case 'billing':
        						/* translators: %s: field name */
        						$field_label = sprintf( _x( 'Billing %s', 'checkout-validation', 'woocommerce' ), $field_label );
        						break;
        				}

                if ( in_array( 'postcode', $format, true ) ) {
                    $country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : WC()->customer->{"get_{$fieldset_key}_country"}();

                    $country_locale = WC()->countries->get_country_locale();
                    if( isset($country_locale[$country]) ){
                      if( isset($country_locale[$country]['postcode']) ){
                        if( isset($country_locale[$country]['postcode']['hidden']) ){
                          if( ($country_locale[$country]['postcode']['hidden']) == 1 ){
                            continue;
                          }
                        }
                      }
                    }

                    $data[ $key ] = wc_format_postcode( $data[ $key ], $country );

                    if ( $validate_fieldset && '' !== $data[ $key ] && ! WC_Validation::is_postcode( $data[ $key ], $country ) ) {
                        switch ( $country ) {
                            case 'IE':
                                /* translators: %1$s: field name, %2$s finder.eircode.ie URL */
                                $postcode_validation_notice = sprintf( __( '%1$s is not valid. You can look up the correct Eircode <a target="_blank" href="%2$s">here</a>.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>', 'https://finder.eircode.ie' );
                                break;
                            default:
                                /* translators: %s: field name */
                                $postcode_validation_notice = sprintf( __( '%s is not a valid postcode / ZIP.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' );
                        }
                        $errors->add( 'validation', apply_filters( 'woocommerce_checkout_postcode_validation_notice', $postcode_validation_notice, $country, $data[ $key ] ) );
                    }
                }
                   
             
                if (isset($field['type']) && $field['type'] == 'numberfield' ) {                            
                  $min = isset($field['min']) ? $field['min'] : null;
                  $max = isset($field['max']) ? $field['max'] : null;
                  if ( $validate_fieldset && '' !== $data[ $key ] ) {
                    $value = $data[ $key ];
                      if (!is_numeric($value) ){
                        $errors->add( 'validation',sprintf( __( '%s is not a valid number','checkout-field-editor-and-manager-for-woocommerce')));
                      }                     
                      else if(($min !== null && $value < $min)) {
                        $errorMessage = sprintf(__(' The entered <strong>%s</strong> is less than the valid range  %s.','checkout-field-editor-and-manager-for-woocommerce'),esc_html($field_label),$min);$errors->add('validation', $errorMessage);
                      }
                      else if(($max !== null && $value > $max)) {
                        $errorMessage = sprintf(__(' The entered <strong>%s</strong> is greater than the valid range  %s.','checkout-field-editor-and-manager-for-woocommerce'),esc_html($field_label),$max);$errors->add('validation', $errorMessage);
                      }
                    //  else if (($min !== null && $value < $min) || ($max !== null && $value > $max)) {
                    //     $errors->add( 'validation',sprintf( __( 'The number is out of the valid range (%s - %s).', 'checkout-field-editor-and-manager-for-woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>', $min, $max ) );
                    //   }
                  }
                }
                    
              
                if ( in_array( 'url', $format, true ) ) {
                    if ( $validate_fieldset && '' !== $data[ $key ] && !filter_var($data[ $key ], FILTER_VALIDATE_URL) ) {
                        $errors->add( 'validation', sprintf( __( '%s is not a valid URL.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ) );
                      
                    }                 
                }

                if ( in_array( 'phone', $format, true ) ) {
                    if ( $validate_fieldset && '' !== $data[ $key ] && ! WC_Validation::is_phone( $data[ $key ] ) ) {
                        /* translators: %s: phone number */
                        $errors->add( 'validation', sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ) );
                    }
                }


               if ( in_array( 'email', $format, true ) && '' !== $data[ $key ] ) {
                    // if ( $required && in_array( 'email', $format, true )  ) {

                  // if (in_array( 'email', $format, true )  ) {
                    $email_is_valid = is_email( $data[ $key ] );
                    $data[ $key ]   = sanitize_email( $data[ $key ] );

                    if ( $validate_fieldset && ! $email_is_valid && $required ) {

                        /* translators: %s: email address */
                        $errors->add( 'validation', sprintf( __( '%s is not a valid email address.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ) );
                        continue;
                    }
                }

                if (  in_array( 'state', $format, true ) ) {

                  $country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : WC()->customer->{"get_{$fieldset_key}_country"}();
                  $valid_states = WC()->countries->get_states( $country );

                  if ( is_array( $valid_states ) && empty( $valid_states ) ) {
                    continue;
                  }
                }

                if ( '' !== $data[ $key ] && in_array( 'state', $format, true ) ) {
                    $country      = isset( $data[ $fieldset_key . '_country' ] ) ? $data[ $fieldset_key . '_country' ] : WC()->customer->{"get_{$fieldset_key}_country"}();
                    $valid_states = WC()->countries->get_states( $country );

                    if ( ! empty( $valid_states ) && is_array( $valid_states ) && count( $valid_states ) > 0 ) {
                        $valid_state_values = array_map( 'wc_strtoupper', array_flip( array_map( 'wc_strtoupper', $valid_states ) ) );
                        $data[ $key ]       = wc_strtoupper( $data[ $key ] );

                        if ( isset( $valid_state_values[ $data[ $key ] ] ) ) {
                            // With this part we consider state value to be valid as well, convert it to the state key for the valid_states check below.
                            $data[ $key ] = $valid_state_values[ $data[ $key ] ];
                        }

                        if ( $validate_fieldset && ! in_array( $data[ $key ], $valid_state_values, true ) ) {
                            /* translators: 1: state field 2: valid states */
                            $errors->add( 'validation', sprintf( __( '%1$s is not valid. Please enter one of the following: %2$s', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>', implode( ', ', $valid_states ) ) );
                        }
                    }
                }

                if ( $validate_fieldset && $required && '' === $data[ $key ] ) {

                    /* translators: %s: field name */
                    $errors->add( 'required-field', apply_filters( 'woocommerce_checkout_required_field_notice', sprintf(  __( '%s is a required field.', 'woocommerce' ), '<strong>' . esc_html( $field_label ) . '</strong>' ), $field_label ) );
                }


            }


        }
        if ( empty( $data['woocommerce_checkout_update_totals'] ) && empty( $data['terms'] ) && ! empty( $_POST['terms-field'] ) ) {
            $errors->add( 'terms', __( 'Please read and accept the terms and conditions to proceed with your order.', 'woocommerce' ) );
        }
        if ( WC()->cart->needs_shipping() ) {
          $shipping_country = WC()->customer->get_shipping_country();

          if ( empty( $shipping_country ) ) {
            $errors->add( 'shipping', __( 'Please enter an address to continue.', 'woocommerce' ) );
          } elseif ( ! in_array( WC()->customer->get_shipping_country(), array_keys( WC()->countries->get_shipping_countries() ), true ) ) {
            /* translators: %s: shipping location */
            $errors->add( 'shipping', sprintf( __( 'Unfortunately <strong>we do not ship %s</strong>. Please enter an alternative shipping address.', 'woocommerce' ), WC()->countries->shipping_to_prefix() . ' ' . WC()->customer->get_shipping_country() ) );
          } else {
            $chosen_shipping_methods = WC()->session->get( 'chosen_shipping_methods' );

            foreach ( WC()->shipping()->get_packages() as $i => $package ) {
              if ( ! isset( $chosen_shipping_methods[ $i ], $package['rates'][ $chosen_shipping_methods[ $i ] ] ) ) {
                $errors->add( 'shipping', __( 'No shipping method has been selected. Please double check your address, or contact us if you need any help.', 'woocommerce' ) );
              }
            }
          }
        }

  		if ( WC()->cart->needs_payment() ) {
  			$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

  			if ( ! isset( $available_gateways[ $data['payment_method'] ] ) ) {
  				$errors->add( 'payment', __( 'Invalid payment method.', 'woocommerce' ) );
  			} else {
  				$available_gateways[ $data['payment_method'] ]->validate_fields();
  			}
  		}
    }


  public function force_shipping_address( $value ) {
      if (!isset($value['ship_to_different_address']) ||$value['ship_to_different_address'] !== '') {
        if (get_option('ship_to_different_address') == 1 ) {
          return true;
        }  
      }
      return $value;
  }

  public function add_inline_scripts() {
		if (get_option('ship_to_different_address') == 1 )  {
			?>
				<style>
					#ship-to-different-address {
						pointer-events: none!important;
					}
					#ship-to-different-address-checkbox {
						display: none;
					}
					.woocommerce-shipping-fields .shipping_address {
					height: auto !important;
					display: block !important;
					}
				</style>
			<?php
		}
    if (get_option('force_create_Account') == 1 )  {
          ?>
        <style>
        div.create-account {
          display: block !important;
        }

        p.create-account {
          display: none !important;
        }
        </style>
        <?php
    }
  }
  
  function hide_additional_fields_css() {
    if (get_option('remove_order_notes_title') == 1 )  {
    ?>
    <style>
        .woocommerce-checkout .woocommerce-additional-fields h3 {
            display: none;
        }
    </style>
    <?php
    }
  }

  function sb_text_strings($translated_text, $text, $domain) {
    if ($text === 'Additional information' && $domain === 'woocommerce' && is_checkout()) {
        $ordertitle = get_option('order_Notes_Title');
        if ($ordertitle) {
            $translated_text = $ordertitle;
        }
    }
    return $translated_text;
  }

  public function auto_create_account( $value ) {
    if (!isset($value['force_create_Account']) ||$value['force_create_Account'] !== '') {
      if (get_option('force_create_Account') == 1 ) {
        return true;
      }  
    }
    return $value;
  }

  function remove_privacy_text() {
      if(get_option( 'privacy_text' )){
        remove_action( 'woocommerce_checkout_terms_and_conditions', 'wc_checkout_privacy_policy_text',20);   
      }
  }

  function coupon_msg_show(){
    if(get_option('checkout_coupon_form')){
      remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 ); 
    }
  }

public function  remove_checkout_shipping_address($val) {
      if (get_option('remove_shipping_field') == 1) {
        $val = 'billing_only';
      }  
      return $val;
}

function remove_terms_and_condition() {
  if (get_option( 'remove_terms_condition') == 1 ) {
    add_action( 'woocommerce_get_terms_and_conditions_checkbox_text',array($this,'remove_terms_and_condition'), 20 );
  }
  return false;
}
// End enqueue_scripts ()
// End instance()
}