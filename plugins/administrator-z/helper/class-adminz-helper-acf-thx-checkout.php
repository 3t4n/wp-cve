<?php 
namespace Adminz\Helper;
use Adminz\Admin\ADMINZ_Woocommerce;
use Adminz\Helper\ADMINZ_Helper_ACF_THX;

class ADMINZ_HELPER_ACF_THX_CHECKOUT{
	function __construct() {
		add_filter( 'woocommerce_checkout_fields' , [$this,'overwrite_field'], 100 );
		add_action( 'wp_enqueue_scripts', [$this,'import_js_checkout']);

		// ajax event chọn tỉnh/ huyện/ xã
		add_action('wp_ajax_lay_gia_tri_huyen_checkout',[$this,'lay_gia_tri_huyen_checkout']);
		add_action('wp_ajax_nopriv_lay_gia_tri_huyen_checkout',[$this,'lay_gia_tri_huyen_checkout']);

		add_action('wp_ajax_lay_gia_tri_xa_checkout',[$this,'lay_gia_tri_xa_checkout']);
		add_action('wp_ajax_nopriv_lay_gia_tri_xa_checkout',[$this,'lay_gia_tri_xa_checkout']);

        /// replace new text to billing_address_1 
        add_action( 'woocommerce_checkout_update_order_meta', [$this,'save_order_data'],99 );

	}
	function overwrite_field($fields){

		$label_tinh = ADMINZ_Helper_ACF_THX::get_tinh_label();
		$field_tinh = [
            "label" =>  $label_tinh,
            "required" => 1,
            "type" => 'select',
            'options'=> [
                ''=> $label_tinh,
            ],
            "class" => ['form-row-first', 'adminz_thx_field', 'field_tinh'],
            "autocomplete" => '',
            "priority" => 50,
            "placeholder" =>  $label_tinh
        ];
        foreach (ADMINZ_Helper_ACF_THX::$data as $key => $value) {
            $field_tinh['options'][$value['ten_tinh']] = $value['ten_tinh'];
        }


        $label_huyen = ADMINZ_Helper_ACF_THX::get_huyen_label();
        $field_huyen = [
            "label" => $label_huyen,
            "required" => 1,
            "type" => 'select',
            'options'=> [
                ''=> $label_huyen,
            ],
            "class" => ['form-row-last', 'adminz_thx_field', 'field_huyen'],
            "autocomplete" => '',
            "priority" => 50,
            "placeholder" => $label_huyen
        ];
        $label_xa = ADMINZ_Helper_ACF_THX::get_xa_label();
        $field_xa = [
            "label" => $label_xa,
            "required" => 1,
            "type" => 'select',
            'options'=> [
                ''=> $label_xa,
            ],
            "class" => ['form-row-first', 'adminz_thx_field', 'field_xa'],
            "autocomplete" => '',
            "priority" => 50,
            "placeholder" => $label_xa
        ];
        $label_duong = ADMINZ_Helper_ACF_THX::get_duong_label();
        $field_duong = [
            "label" => $label_duong,
            "required" => 1,
            "type" => 'text',
            'options'=> [
                ''=> $label_duong,
            ],
            "class" => ['form-row-last', 'adminz_thx_field', 'field_duong'],
            "autocomplete" => '',
            "priority" => 50,
            "placeholder" => $label_duong,
		];

		$fields['billing']["billing_".ADMINZ_Helper_ACF_THX::$tinh_name] = $field_tinh;
		$fields['billing']["billing_".ADMINZ_Helper_ACF_THX::$huyen_name] = $field_huyen;
		$fields['billing']["billing_".ADMINZ_Helper_ACF_THX::$xa_name] = $field_xa;
		$fields['billing']["billing_".ADMINZ_Helper_ACF_THX::$duong_name] = $field_duong;

		$fields['shipping']["shipping_".ADMINZ_Helper_ACF_THX::$tinh_name] = $field_tinh;
		$fields['shipping']["shipping_".ADMINZ_Helper_ACF_THX::$huyen_name] = $field_huyen;
		$fields['shipping']["shipping_".ADMINZ_Helper_ACF_THX::$xa_name] = $field_xa;
		$fields['shipping']["shipping_".ADMINZ_Helper_ACF_THX::$duong_name] = $field_duong;

		unset($fields['billing']['billing_city']);
        unset($fields['billing']['billing_address_1']);
        unset($fields['shipping']['shipping_city']);
        unset($fields['shipping']['shipping_address_1']);

 		return $fields;
	}
	function import_js_checkout(){
        if(!is_checkout()) return;

        $depth = ['jquery'];
        if(wp_script_is('select2')){
            $depth = ['jquery','select2'];
        }
        wp_enqueue_script( 'adminz-acfthx-checkout', plugin_dir_url(ADMINZ_BASENAME) . 'helper/thx/checkout.js', ['jquery'] );
        wp_localize_script( 
            'adminz-acfthx-checkout', 
            'thx_checkout_vars', 
            array(
                'pa_nonce' => wp_create_nonce( 'pa_nonce' ),
                'ajax_url'=> admin_url( 'admin-ajax.php' ),
                'is_select2'=> wp_script_is('select2'),
                'name_field_tinh'=> ADMINZ_Helper_ACF_THX::$tinh_name,
                'name_field_huyen'=> ADMINZ_Helper_ACF_THX::$huyen_name,
                'name_field_xa'=> ADMINZ_Helper_ACF_THX::$xa_name,
                'name_field_duong'=> ADMINZ_Helper_ACF_THX::$duong_name,
                'label_field_tinh'=> __("Select",'administrator-z'). " " .ADMINZ_Helper_ACF_THX::get_tinh_label(),
                'label_field_huyen'=> __("Select",'administrator-z'). " " .ADMINZ_Helper_ACF_THX::get_huyen_label(),
                'label_field_xa'=> __("Select",'administrator-z'). " " .ADMINZ_Helper_ACF_THX::get_xa_label(),
                'label_field_duong'=> __("Select",'administrator-z'). " " .ADMINZ_Helper_ACF_THX::get_duong_label(),
            )
        ); 
    }
	function lay_gia_tri_huyen_checkout(){
        if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
        die('Permission denied');

    	$tinh_huyen_xa = ADMINZ_Helper_ACF_THX::$data; 
        $selected_tinh = sanitize_text_field($_POST['tinh']);        
        $data = [];
        if(!empty($tinh_huyen_xa) and is_array($tinh_huyen_xa)){
            foreach ($tinh_huyen_xa as $key => $value) {
                if($selected_tinh == $value['ten_tinh']){
                    if(!empty($value['huyen']) and is_array($value['huyen'])){
                        foreach ($value['huyen'] as $key => $value) {
                            $data[] = $value['ten_huyen'];
                        }
                    }
                }
            }
        }
        return wp_send_json($data);
        die();
	}
	function lay_gia_tri_xa_checkout(){
		if( !isset( $_POST['pa_nonce'] ) || !wp_verify_nonce( $_POST['pa_nonce'], 'pa_nonce' ) )
        die('Permission denied');

		$tinh_huyen_xa = ADMINZ_Helper_ACF_THX::$data; 
        $selected_huyen = sanitize_text_field($_POST['huyen']);
        $selected_tinh = sanitize_text_field($_POST['tinh']);
        $data = [];
        if(!empty($tinh_huyen_xa) and is_array($tinh_huyen_xa)){
            foreach ($tinh_huyen_xa as $key => $value) {
                if($selected_tinh == $value['ten_tinh']){
                    if(!empty($value['huyen']) and is_array($value['huyen'])){
                        foreach ($value['huyen'] as $key => $value) {
                            if($selected_huyen == $value['ten_huyen']){
                                if(!empty($value['xa']) and is_array($value['xa'])){
                                    foreach ($value['xa'] as $key => $value) {
                                        $data[] = $value['ten_xa'];
                                    }                                                               
                                }
                            }                                               
                        }
                    }
                }
            }
        }       
        return wp_send_json($data);
        die();
	}
    function save_order_data($order_id){
        $dia_chi_billing = "";
        if ( ! empty( $_POST['billing_duong'] ) ) {
            $dia_chi_billing .= sanitize_text_field( $_POST['billing_duong'])." - ";
        }
        if ( ! empty( $_POST['billing_xa'] ) ) {
            $dia_chi_billing .= sanitize_text_field( $_POST['billing_xa'])." - ";
        }
        if ( ! empty( $_POST['billing_huyen'] ) ) {
            $dia_chi_billing .= sanitize_text_field( $_POST['billing_huyen'])." - ";
        }
        if ( ! empty( $_POST['billing_tinh'] ) ) {
            $dia_chi_billing .= sanitize_text_field( $_POST['billing_tinh']);
        }


        $dia_chi_shipping = "";
        if ( ! empty( $_POST['shipping_duong'] ) ) {
            $dia_chi_shipping .= sanitize_text_field( $_POST['shipping_duong'])." - ";
        }
        if ( ! empty( $_POST['shipping_xa'] ) ) {
            $dia_chi_shipping .= sanitize_text_field( $_POST['shipping_xa'])." - ";
        }
        if ( ! empty( $_POST['shipping_huyen'] ) ) {
            $dia_chi_shipping .= sanitize_text_field( $_POST['shipping_huyen'])." - ";
        }
        if ( ! empty( $_POST['shipping_tinh'] ) ) {
            $dia_chi_shipping .= sanitize_text_field( $_POST['shipping_tinh']);
        }


        $order = wc_get_order( $order_id );

        if ( ! empty( $_POST['shipping_phone'] ) ) {
            $order->set_shipping_phone(sanitize_text_field($_POST['shipping_phone']));
        }

        $order->set_billing_address_1($dia_chi_billing);
        $order->set_shipping_address_1($dia_chi_shipping);
        $order->save();
    }
    
}