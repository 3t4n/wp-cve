<?php

if (!defined('ABSPATH'))
    exit;

class AWCFE_Api
{

    /**
     * @var    object
     * @access  private
     * @since    1.0.0
     */
    private static $_instance = null;

    /**
     * The version number.
     * @var     string
     * @access  public
     * @since   1.0.0
     */
    public $_version;
    private $_active = false;

    public function __construct()
    {
        add_action('rest_api_init', function () {
            register_rest_route('awcfe/v1', '/fields/', array(
                'methods' => 'GET',
                'callback' => array($this, 'get_fields'),
                'permission_callback' => array($this, 'get_permission')
            ));

            register_rest_route('awcfe/v1', '/save/', array(
                'methods' => 'POST',
                'callback' => array($this, 'post_form'),
                'permission_callback' => array($this, 'get_permission')
            ));

            register_rest_route('awcfe/v1', '/awcfe_reset_all/', array(
                'methods' => 'POST',
                'callback' => array($this, 'awcfe_reset_all'),
                'permission_callback' => array($this, 'get_permission')
            ));

            register_rest_route('awcfe/v1', '/awcfe_adv_settings/', array(
                'methods' => 'POST',
                'callback' => array($this, 'awcfe_adv_settings'),
                'permission_callback' => array($this, 'get_permission')
            ));
            register_rest_route('awcfe/v1', '/awcfe_adv_settings/(?P<id>\d+)', array(
                'methods' => 'GET',
                'callback' => array($this, 'awcfe_adv_settings'),
                'permission_callback' => array($this, 'get_permission')
            ));

            register_rest_route('awcfe/v1', '/awcfe_settings_load/', array(
                'methods' => 'GET',
                'callback' => array($this, 'awcfe_load'),
                'permission_callback' => array($this, 'get_permission')
            ));

            
        });
    }

    /**
     *
     * Ensures only one instance of AWDP is loaded or can be loaded.
     *
     * @since 1.0.0
     * @static
     * @see WordPress_Plugin_Template()
     * @return Main AWDP instance
     */
    public static function instance($file = '', $version = '1.0.0')
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self($file, $version);
        }
        return self::$_instance;
    }


    function post_form($data)
    {
        $request_body = $data->get_params();

        $fieldObj = new AWCFE_Fields();
        $response = $fieldObj->saveFields($request_body);
        return new WP_REST_Response($response, 200);
    }

    function awcfe_reset_all($data) {
      $data = $data->get_params();

      update_option(AWCFE_FIELDS_KEY, '');

      $result['url'] = admin_url('admin.php?page=awcfe_admin_ui#/');
      $result['success'] = true;
      return new WP_REST_Response($result, 200);
  }


    /**
     * @param $data
     * @return WP_REST_Response
     * @throws Exception
     */
    function get_fields($data)
    {
        wc()->frontend_includes();
        WC()->session = new WC_Session_Handler();
        WC()->session->init();
        WC()->customer = new WC_Customer(get_current_user_id(), true);

        $checkout_fields = WC()->checkout()->get_checkout_fields();
        return new WP_REST_Response($checkout_fields, 200);


    }


    /**
     * Permission Callback
     **/
    public function get_permission()
    {
        if (current_user_can('administrator') || current_user_can('manage_woocommerce')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Cloning is forbidden.
     *
     * @since 1.0.0
     */
    public function __clone()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }

    /**
     * Unserializing instances of this class is forbidden.
     *
     * @since 1.0.0
     */
    public function __wakeup()
    {
        _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?'), $this->_version);
    }


    
function awcfe_load($data) {
    $data = $data->get_params();
    $keys = ['ship_to_different_address',
     'remove_order_notes_title', 
     'order_Notes_Title', 
     'force_create_Account', 
     'privacy_text', 
     'checkout_coupon_form',
     'remove_shipping_field',
     'remove_terms_condition'];
    $result = [];

    foreach($keys as $key){
        if( isset($data[$key]) && $data[$key] == true ) {
            $result[$key] = get_option($key) ? get_option($key) : 1;
        } else {
            $result[$key] = get_option($key) ? get_option($key) : '' ;
        }
    }

        return new WP_REST_Response($result, 200);
}


    function awcfe_adv_settings($data)
    {

      if( ! $data['id'] ) {

        $data = $data->get_params();

        $shipadds = $data['ship_to_different_address'] ? $data['ship_to_different_address'] : 0;
        $remvnote = $data['remove_order_notes_title'] ? $data['remove_order_notes_title'] : 0;
        $chngtitle = $data['order_Notes_Title'] ? $data['order_Notes_Title'] : 0;
        $frcacct = $data['force_create_Account'] ? $data['force_create_Account'] : 0;
        $privacytext = $data['privacy_text']? $data['privacy_text'] :0;
        $coupon = $data['checkout_coupon_form']? $data['checkout_coupon_form'] :0;
        $rmvship = $data['remove_shipping_field'] ? $data['remove_shipping_field'] : 0;
        $terms = $data['remove_terms_condition'] ? $data['remove_terms_condition'] : 0;


        if ( false === get_option('ship_to_different_address') ){
            add_option('ship_to_different_address',  $shipadds, '', 'yes');
        }  
        else {
            update_option('ship_to_different_address',  $shipadds);
        }

        if ( false === get_option('remove_order_notes_title') ){
                add_option('remove_order_notes_title', $remvnote, '', 'yes');
        }  else {
                update_option('remove_order_notes_title',  $remvnote);
        }

        if ( false === get_option('order_Notes_Title') ){
            add_option('order_Notes_Title',  $chngtitle , '', 'yes');
        }  
        else {
            update_option('order_Notes_Title',  $chngtitle );
        }
        
        if ( false === get_option('force_create_Account' )){
            add_option('force_create_Account', $frcacct , '', 'yes');
        }  
        else {
            update_option('force_create_Account', $frcacct );
        }

        if ( false === get_option('privacy_text' )){
            add_option('privacy_text', $privacytext , '', 'yes');
        }  
        else {
            update_option('privacy_text', $privacytext );
        }

        if ( false === get_option('checkout_coupon_form' )){
            add_option('checkout_coupon_form', $coupon, '', 'yes');
        }  
        else {
            update_option('checkout_coupon_form', $coupon);
        }

        if ( false === get_option('remove_shipping_field' )){
            add_option('remove_shipping_field',$rmvship, '', 'yes');
        }  
        else {
            update_option('remove_shipping_field', $rmvship);
        }

        if ( false === get_option('remove_terms_condition' )){
            add_option('remove_terms_condition',$terms, '', 'yes');
        }  
        else {
            update_option('remove_terms_condition', $terms);
        }


          $result['ship_to_different_address'] = get_option('ship_to_different_address') ? get_option('ship_to_different_address') : '';
          $result['remove_order_notes_title'] = get_option('remove_order_notes_title') ? get_option('remove_order_notes_title') : '';
          $result['order_Notes_Title'] = get_option('order_Notes_Title') ? get_option('order_Notes_Title') : '';
          $result['privacy_text'] = get_option('privacy_text') ? get_option('privacy_text') : '';
          $result['force_create_Account'] = get_option('force_create_Account') ? get_option('force_create_Account') : '';
          $result['checkout_coupon_form'] = get_option('checkout_coupon_form') ? get_option('checkout_coupon_form') : '';
          $result['remove_shipping_field'] = get_option('remove_shipping_field') ? get_option('remove_shipping_field') : '';
          $result['remove_terms_condition'] =get_option('remove_terms_condition') ? get_option('remove_terms_condition') : '';
         
          
        }
        return new WP_REST_Response($result, 200);
    }   
}