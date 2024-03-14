<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/*
Plugin Name: Epeken All Kurir - Full Version
Plugin URI: https://wordpress.org/plugins/epeken-all-kurir 
Description: Calculated Shipping Plugin for some shipping companies (JNE, JTR, JNE Trucking, TIKI, POS, RPX, JET.CO.ID, WAHANA, SICEPAT, JMX, DAKOTA CARGO) in Indonesia. It comes with bank accounts payment method with some banks in Indonesia. This is full version plugin. Hopefully you can enjoy this plugin to build your own ecommerce for Indonesia sales. International shipping cost information is also available if your license is granted with international options.
Version: 1.4.3
Author: www.epeken.com
Author URI: http://www.epeken.com
*/
$api_end_point = 'index.php';

if(!function_exists ('epeken_get_data_server')) {
 function epeken_get_data_server() {
	$server = sanitize_text_field(get_option('epeken_data_server'));
	if(empty($server))
		$server = 'http://174.138.21.166'; //default data server..
	return $server;	
 }
}

$server = epeken_get_data_server();
$epeken_err_msg = sanitize_text_field(get_option('epeken_enable_error_message_setting'));
if ($epeken_err_msg === 'on')
	error_reporting(E_WARNING | E_PARSE | E_ERROR);

if($epeken_err_msg === false)
	update_option('epeken_enable_error_message_setting', 'on');

define('EPEKEN_SERVER_URL', $server); 
define('EPEKEN_ITEM_REFERENCE', 'epeken_courier');
include_once('includes/epeken_courier_ajax_backend.php');
include_once('includes/epeken_courier_end_points.php');
include_once('class/widget_cekresi.php');
include_once('includes/epeken_konfirmasi_pembayaran.php');
$plugin_dir = plugin_dir_path(__FILE__);
$kotakab_json = $plugin_dir.'data/kotakabupaten.json';
$kotakec_json = $plugin_dir.'data/kotakecamatan.json';
$province_json = $plugin_dir.'data/province.json';
$api_dir_url=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_calculated_shipping_extracustom/';
$api_dir_url_intl=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_calculated_shipping_intl/';
$valid_origin_url=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/validorigin/';
$api_pos_url_v3=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_ptpos_ongkirv3/';
$api_get_provinces=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/get_all_provinces/';
$api_wahana_v2=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_wahana_ongkirv2/';
$api_custom_tarif=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_custom_tarif/';
$api_jet=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_jnt_ongkir/';
$api_sicepat=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_sicepat_ongkir/';
$api_atlas=EPEKEN_SERVER_URL.'/api/atlas.php'.'/rate/';
$api_get_currency_rate=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/getcurrencytoidr/';
$tracking_end_point=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/tracks/';
$api_get_jne_trucking=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_jne_trucking_tarif/';
$api_get_dakota=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_dakota_tarif/';
$api_get_sap_express=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_sap_express_tarif/';
$api_get_ninja_express=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_ninja_express_tarif/';
$api_nss=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_nss_ongkir/';
$api_jmx=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_jmx_ongkir/';
$api_lion=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_lion_ongkir/';
$api_rpx=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/epeken_get_rpx_ongkir/';
$api_getusdamount=EPEKEN_SERVER_URL.'/api/'.$api_end_point.'/getusdamountv2/';
define('EPEKEN_TRACKING_END_POINT',$tracking_end_point);
define('EPEKEN_KOTA_KAB',$kotakab_json);
define('EPEKEN_KOTA_KEC',$kotakec_json);
define('EPEKEN_PROVINCE',$province_json);
define('EPEKEN_API_DIR_URL',$api_dir_url);
define('EPEKEN_API_DIR_URL_INTL',$api_dir_url_intl);
define('EPEKEN_VALID_ORIGIN',$valid_origin_url);
define('EPEKEN_API_POS_URL_V3',$api_pos_url_v3);
define('EPEKEN_API_WAHANA',$api_wahana_v2);
define('EPEKEN_API_CUSTOM_TARIF' , $api_custom_tarif);
define('EPEKEN_API_SICEPAT', $api_sicepat);
define('EPEKEN_API_ATLAS', $api_atlas);
define('EPEKEN_API_JET',$api_jet);
define('EPEKEN_API_GET_PRV',$api_get_provinces);
define('EPEKEN_API_GET_CURRENCY_RATE', $api_get_currency_rate);
define('EPEKEN_API_JNE_TRUCKING', $api_get_jne_trucking);
define('EPEKEN_API_DAKOTA', $api_get_dakota);
define('EPEKEN_API_SAP_EXPRESS', $api_get_sap_express);
define('EPEKEN_API_NSS', $api_nss);
define('EPEKEN_API_JMX', $api_jmx);
define('EPEKEN_API_LION', $api_lion);
define('EPEKEN_API_NINJA', $api_get_ninja_express);
define('EPEKEN_API_RPX',$api_rpx);
define('EPEKEN_GET_USDRATE_API', $api_getusdamount);

if ( !function_exists( 'is_plugin_active_for_network' ) ) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

if (in_array('woocommerce/woocommerce.php', 
	apply_filters( 'active_plugins', get_option( 'active_plugins'))) || 
        is_plugin_active_for_network ('woocommerce/woocommerce.php')
   )
{
	function epeken_all_kurir_init() {
		if(!class_exists('WC_Shipping_Tikijne'))
 		{
    		   include_once('class/shipping.php');   
		   include_once('class/companies/lion.php');
		   include_once('class/companies/jmx.php');
		   include_once('class/companies/jnt.php');
		   include_once('class/companies/sicepat.php');
		   include_once('class/companies/wahana.php');
		   include_once('class/companies/sap.php');
		   include_once('class/companies/ninja.php');
		   include_once('class/companies/jtr.php');
		   include_once('class/companies/dakota.php');
		   include_once('class/companies/nss.php');
		   include_once('class/companies/atlas.php');
		   include_once('class/companies/custom.php');
		   include_once('class/companies/pos.php');
		   include_once('class/companies/rpx.php');
 		}
	}
	add_action( 'woocommerce_shipping_init', 'epeken_all_kurir_init' );
	function epeken_add_indonesia_shipping_method( $methods ) {
			$methods[] = 'WC_Shipping_Tikijne';
			return $methods;
		}
	add_filter( 'woocommerce_shipping_methods', 'epeken_add_indonesia_shipping_method' );
	add_action( 'plugins_loaded', 'epeken_btpn_payment_method_init', 0 );
        function epeken_btpn_payment_method_init(){
                if(!class_exists('BTPN')){
                        include_once('class/btpn_payment_method.php');
                }   
        }   
        function epeken_add_btpn_payment_method( $methods ) { 
          $methods[] = 'BTPN';
          return $methods;
        }  	
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_btpn_payment_method' );
	add_action( 'plugins_loaded', 'epeken_danamon_payment_method_init', 0 );
	function epeken_danamon_payment_method_init(){
                if(!class_exists('Danamon')){
                        include_once('class/danamon_payment_method.php');
                }
        }
	function epeken_add_danamon_payment_method( $methods ) {
          $methods[] = 'Danamon';
          return $methods;
        }
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_danamon_payment_method' );
	add_action( 'plugins_loaded', 'epeken_bank_mandiri_payment_method_init', 0 );
	function epeken_bank_mandiri_payment_method_init(){
		if(!class_exists('Mandiri')){
			include_once('class/mandiri_payment_method.php');
		}
	}
	function epeken_add_bank_mandiri_payment_method( $methods ) {
          $methods[] = 'Mandiri';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_bank_mandiri_payment_method' );
	add_action( 'plugins_loaded', 'epeken_bank_bca_payment_method_init', 0 );
	function epeken_bank_bca_payment_method_init(){
		if(!class_exists('BCA')){
			include_once('class/bca_payment_method.php');
		}
	}
	function epeken_add_bank_bca_payment_method( $methods ) {
          $methods[] = 'BCA';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_bank_bca_payment_method' );
	add_action( 'plugins_loaded', 'epeken_maybank_payment_method_init', 0 );
	function epeken_maybank_payment_method_init(){
		if(!class_exists('Maybank')){
			include_once('class/maybank_payment_method.php');
		}
	}
	function epeken_add_maybank_payment_method( $methods ) {
          $methods[] = 'maybank';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_maybank_payment_method' );
	add_action( 'plugins_loaded', 'epeken_bank_muamalat_payment_method_init', 0 ); 
	function epeken_bank_muamalat_payment_method_init(){
                if(!class_exists('Muamalat')){
                        include_once('class/muamalat_payment_method.php');
                }    
        }    
	function epeken_add_bank_muamalat_payment_method( $methods ) {
          $methods[] = 'Muamalat';
          return $methods;
        }
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_bank_muamalat_payment_method' );
	add_action( 'plugins_loaded', 'epeken_bank_permata_payment_method_init', 0 ); 
        function epeken_bank_permata_payment_method_init(){
                if(!class_exists('Permata')){
                        include_once('class/permata_payment_method.php');
                }    
        }    
        function epeken_add_bank_permata_payment_method( $methods ) {
          $methods[] = 'Permata';
          return $methods;
        }    
    add_filter( 'woocommerce_payment_gateways', 'epeken_add_bank_permata_payment_method' );	
    add_action( 'plugins_loaded', 'epeken_bank_bni_payment_method_init', 0 );
	function epeken_bank_bni_payment_method_init(){
		if(!class_exists('BNI')){
			include_once('class/bni_payment_method.php');
		}
	}
	function epeken_add_bank_bni_payment_method( $methods ) {
          $methods[] = 'BNI';
          return $methods;
    	}	
	add_filter( 'woocommerce_payment_gateways', 'epeken_add_bank_bni_payment_method' );
	add_action( 'plugins_loaded', 'epeken_bank_bri_payment_method_init', 0 );
        function epeken_bank_bri_payment_method_init(){
                if(!class_exists('BRI')){
                        include_once('class/bri_payment_method.php');
                }
        }
        function epeken_add_bank_bri_payment_method( $methods ) {
          $methods[] = 'BRI';
          return $methods;
        }
    add_filter( 'woocommerce_payment_gateways', 'epeken_add_bank_bri_payment_method' );
	add_action( 'plugins_loaded', 'epeken_bsm_payment_method_init', 0 );
        function epeken_bsm_payment_method_init(){
                if(!class_exists('BSM')){
                        include_once('class/bsm_payment_method.php');
                }
        }
        function epeken_add_bsm_payment_method( $methods ) {
          $methods[] = 'BSM';
          return $methods;
        }
    add_filter( 'woocommerce_payment_gateways', 'epeken_add_bsm_payment_method' );
	add_action( 'plugins_loaded', 'epeken_niaga_payment_method_init', 0 );
        function epeken_niaga_payment_method_init(){
                if(!class_exists('Niaga')){
                        include_once('class/niaga_payment_method.php');
                }
        }
        function epeken_add_niaga_payment_method( $methods ) {
          $methods[] = 'Niaga';
          return $methods;
        }
    add_filter( 'woocommerce_payment_gateways', 'epeken_add_niaga_payment_method' );
    add_action( 'plugins_loaded', 'epeken_bii_payment_method_init', 0 );
        function epeken_bii_payment_method_init(){
                if(!class_exists('BII')){
                        include_once('class/bii_payment_method.php');
                }
        }
        function epeken_add_bii_payment_method( $methods ) {
          $methods[] = 'BII';
          return $methods;
        }
     add_filter( 'woocommerce_payment_gateways', 'epeken_add_bii_payment_method' );
	
     add_action( 'plugins_loaded', 'epeken_bri_syariah_payment_method_init', 0 );
       function epeken_bri_syariah_payment_method_init(){
                if(!class_exists('BRISyariah')){
                        include_once('class/bri_syariah_payment_method.php');
                }
        }
	function epeken_add_bri_syariah_payment_method( $methods ) {
          $methods[] = 'BRISyariah';
          return $methods;
        }
     add_filter( 'woocommerce_payment_gateways', 'epeken_add_bri_syariah_payment_method' );

	add_action( 'plugins_loaded', 'epeken_bni_syariah_payment_method_init', 0 );
       function epeken_bni_syariah_payment_method_init(){
                if(!class_exists('BNISyariah')){
                        include_once('class/bni_syariah_payment_method.php');
                }
        }
        function epeken_add_bni_syariah_payment_method( $methods ) {
          $methods[] = 'BNISyariah';
          return $methods;
        }
     add_filter( 'woocommerce_payment_gateways', 'epeken_add_bni_syariah_payment_method' );	
	// Customize order review fields when checkout 
     function override_default_address() {
		$list_of_kota_kabupaten = epeken_get_list_of_kota_kabupaten();
		$list_of_kecamatan = epeken_get_list_of_kecamatan('init');
		$fields = array(
			'first_name' => array(
					'label'        => __( 'First Name', 'epeken-all-kurir' ),
					'required'     => true,
					'class'        => array( 'form-row-first', 'col-sm-6' ),
					'input_class'  => array('form-control'),
					'autocomplete' => 'given-name',
					'autofocus'    => true,
					'priority'     => 10,
			),
			'last_name' => array(
					'label'        => __( 'Last Name', 'epeken-all-kurir' ),
					'required'     => false,
					'class'        => array( 'form-row-last', 'col-sm-6' ),
					'input_class'  => array('form-control'),
					'autocomplete' => 'family-name',
					'priority'     => 20,
			),
			'company' => array(
					'label'        => __( 'Company/Organization', 'epeken-all-kurir' ),
					'class'        => array( 'form-row-wide','col-sm-12' ),
					'input_class'  => array('form-control'),
					'required'	=> false,
					'autocomplete' => 'organization',
					'priority'     => 30,
			),
			'country' => array(
					'type'         => 'country',
					'label'        => __( 'Country', 'epeken-all-kurir' ),
					'input_class'  => array('form-control'),
					'required'     => true,
					'class'        => array( 'form-row-wide', 'address-field','col-sm-12'),
					'autocomplete' => 'country',
					'priority'     => 40,
			),   
			'address_1' => array(
					'label'        => __( 'Address', 'epeken-all-kurir' ),
					'type'	       => 'textarea',
					'placeholder'  => __( 'Street, Street Number, Apartement', 'epeken-all-kurir' ),
					'required'     => true,
					'input_class'  => array('form-control'),
					'class'        => array( 'form-row-wide', 'address-field','validate-required','woocommerce-validated', 'col-sm-12'),
					'autocomplete' => 'address-line1',
					'priority'     => 50,
			),
			'state' => array(
				'type'         => 'state',
				'label'        => __( 'State / County', 'epeken-all-kurir' ),
				'required'     => true,
				'input_class'  => array('form-control'),
				'class'        => array( 'form-row-wide', 'col-sm-12'),
				'validate'     => array( 'state' ),
				'autocomplete' => 'address-level1',
				'priority'     => 55,
            		),
			'city' => array(
				'label' 	=> __('City', 'epeken-all-kurir'),
				'placeholder'  	=> __('City', 'epeken-all-kurir'),
				'required' 	=> true,
				'type' 		=> 'select',
				#'options' 	=> $list_of_kota_kabupaten,
				'options'	=> array('' => 'Pilih Kota'),
				'priority' 	=> 60,
				'input_class'  => array('form-control'),
				'class'         => array( 'form-row-first','address-field','col-sm-6'),
			),   
       			'address_2' => array(
				'label'	       => __('District', 'epeken-all-kurir'),
				'type'		=> 'select',
				#'options'	=> $list_of_kecamatan,
				'options'	=> array('' => 'Pilih Kecamatan'),
                		'placeholder'  => esc_attr__( 'District', 'epeken-all-kurir' ),
				'input_class'  => array('form-control'),
				'class'        => array( 'form-row-last','address-field', 'col-sm-6', 'validate-required','woocommerce-validated'),
                		'required'     => true,
                		'autocomplete' => 'address-line2',
                		'priority'     => 70,
			),   
			'address_3' => array(
				'label'		=> __('Kelurahan', 'epeken-all-kurir'), 
				'type'		=> 'text',
				'placeholder'	=> 'Kelurahan',
				'input_class'  => array('form-control'),
				'class'		=> array('form-row-first', 'col-sm-6'),
				'required'	=> false,
				'priority'	=> 80,
			),
			'postcode' => array(
				'label'        => __( 'Zip Code', 'epeken-all-kurir' ),
				'required'     => false,
				'input_class'  => array('form-control'),
				'class'        => array( 'form-row-last','col-sm-6'),
				'validate'     => array( 'postcode' ),
				'autocomplete' => 'postal-code',
				'priority'     => 90,
			), 
		  );
		return $fields;
     }
    add_filter('woocommerce_billing_fields', 'epeken_email_not_mandatory', 10, 1);
    function epeken_email_not_mandatory($billing_fields){
	    if(!is_checkout()) return $billing_fields;
	    $check = get_option('epeken_email_optional');
	    if($check === 'on')
		    $billing_fields['billing_email']['required'] = false;
	    return $billing_fields;
    }
    add_filter ('woocommerce_default_address_fields', 'override_default_address',9999999);
    function epeken_checkout_insurance_field() {
                global $woocommerce;
		$shipping = WC_Shipping::instance();
		$methods = $shipping -> get_shipping_methods();
		$epeken_tikijne = $methods['epeken_courier'];
		$array_of_mdtry_ins_prod_cat = explode(",",$epeken_tikijne -> settings['prodcat_with_insurance']);
                $contents = $woocommerce->cart->cart_contents;
                $is_insurance_mandatory = false;
                foreach($contents as $content) {
                        $product_id = $content['product_id'];
                        for($i=0;$i<sizeof($array_of_mdtry_ins_prod_cat);$i++){
                          $is_insurance_mandatory = epeken_is_product_in_category($product_id,trim($array_of_mdtry_ins_prod_cat[$i]));
			  /* insurance mandatory product based */
                          if(!$is_insurance_mandatory) {
                                $product_insurance_mandatory = get_post_meta($product_id,'product_insurance_mandatory',true);    
                                if ($product_insurance_mandatory === 'on')
                                        $is_insurance_mandatory = true;
                          }   
                          /* --- */
                          if($is_insurance_mandatory == true)
                                break;
                        }
                          if($is_insurance_mandatory == true)
                                break;
                }
	        $label = __("Buy Insurance (JNE Only)", 'epeken-all-kurir');	
		if($is_insurance_mandatory == true) {
			$label = __("We recommend insurance for this package", 'epeken-all-kurir');
		}
                if($epeken_tikijne -> settings['enable_insurance'] == "yes") {
                $checkout = $woocommerce -> checkout;
                echo "<div id='checkout_insurance_field'>";
                woocommerce_form_field( 'insurance_chkbox', array(
                        'type'          => 'checkbox',
                        'class'         => array('form-row-wide','address-field','update_totals_on_change','validated-required'),
                        'label'         => $label
                ), $checkout->get_value( 'insurance_chkbox' ));
                echo "</div>";
		}else{
			return;
		}
		if($is_insurance_mandatory) {
			?> 
			<script language="javascript">
				document.getElementById("insurance_chkbox").checked = true;
				document.getElementById("checkout_insurance_field").style.display = "none";
			</script>
			<?php
		}
        }
	function epeken_is_product_in_category($productid,$product_category_name){
		$returnvalue = false;
		$terms = get_the_terms( $productid, 'product_cat' );
		if (!is_array($terms)) {
		  return false;
		}
		foreach ($terms as $term) {
    			if($product_category_name == $term->name)
			{
				$returnvalue = true;
				break;
			}
		}
		return $returnvalue;
	 }

        add_action( 'woocommerce_before_order_notes', 'epeken_checkout_insurance_field' );

	function epeken_js_change_select_class() {
			global $wp_version;
			$epeken_wp_version = substr($wp_version,0,3);
			if($epeken_wp_version < 5.6)
			   wp_enqueue_script('init_controls',plugins_url('/js/init_controls.js',__FILE__), array('jquery'),'1.3.4');
			else
			   wp_enqueue_script('init_controls',plugins_url('/js/init_controls_wp.5.6.js',__FILE__), array('jquery'),'1.3.4.2');

			?>
			<script type="text/javascript">
			jQuery(document).ready(function($) { init_control(); $('#billing_address_3').val('<?php global $current_user; echo get_user_meta($current_user -> ID, 'kelurahan', true); ?>'); $('#shipping_address_3').val('<?php global $current_user; echo get_user_meta($current_user -> ID, 'kelurahan', true); ?>');});
			</script>
			<?php
	}
	add_action ('woocommerce_after_order_notes', 'epeken_js_change_select_class');

	function epeken_js_query_kecamatan_shipping_form(){
		$kec_url = admin_url('admin-ajax.php');
		wp_enqueue_script('ajax_shipping_kec',plugins_url('/js/shipping_kecamatan.js',__FILE__), array('jquery'),'1.3.4.2');
                                 wp_localize_script( 'ajax_shipping_kec', 'PT_Ajax_Ship_Kec', array(
                                        'ajaxurl'       => $kec_url,
					'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
				));
			 $settings = get_option('woocommerce_epeken_courier_settings');
		 	 $is_auto = $settings['auto_populate_returning_user_address'];
		 	 $kecamatan_shipping_pelanggan = '';
		 	 if ($is_auto == 'yes') {
	 	  		$user_id = get_current_user_id();
		  		if($user_id > 0) {
			 	$kecamatan_shipping_pelanggan = sanitize_text_field(get_user_meta($user_id, 'shipping_address_2', true));
		  	  } 
		 	 }		 
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					shipping_kecamatan('<?php echo esc_html($kecamatan_shipping_pelanggan); ?>'); //this script set action on change to city dropdown.
				});
			</script>			
			<?php	
	}

	function epeken_js_query_kota_shipping_form() {
		if(!function_exists('is_checkout'))
			return;
		if(!is_checkout() && !is_account_page())
			return;

		$url = admin_url('admin-ajax.php');
		wp_enqueue_script('ajax_shipping_kota',plugins_url('/js/shipping_kota.js',__FILE__), array('jquery'), '1.3.4.2');
		wp_localize_script( 
		    'ajax_shipping_kota', 'PT_Ajax_Ship_Kota', array(
                    'ajaxurl'       => $url,
                    'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
	    	));
		 $settings = get_option('woocommerce_epeken_courier_settings');
		 $is_auto = $settings['auto_populate_returning_user_address'];
		 $kota_shipping_pelanggan = '';
		 if ($is_auto == 'yes') {
	 	  $user_id = get_current_user_id();
		  if($user_id > 0) {
			 $kota_shipping_pelanggan = sanitize_text_field(get_user_meta($user_id, 'shipping_city', true));
		  } 
		 }		 
		?> 
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				shipping_kota('<?php echo esc_html($kota_shipping_pelanggan); ?>'); //this script set action on change to province dropdown.
			});
		</script>	
		<?php	
	}

	function epeken_js_query_kota_billing_form() {
		$shipping = WC_Shipping::instance();
		$methods = $shipping -> get_shipping_methods();
		$epeken_tikijne = $methods['epeken_courier'];
		
		if(!function_exists('is_checkout'))
			return;
		if(!is_checkout() && !is_account_page())
			return;
		
		$epeken_tikijne -> reset_shipping();
		$url = admin_url('admin-ajax.php');
		wp_enqueue_script('ajax_billing_kota',plugins_url('/js/billing_kota.js',__FILE__), array('jquery'), '1.3.4.2');
		wp_localize_script( 
		    'ajax_billing_kota', 'PT_Ajax_Bill_Kota', array(
                    'ajaxurl'       => $url,
                    'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
	    	));
		 $settings = get_option('woocommerce_epeken_courier_settings');
		 $is_auto = $settings['auto_populate_returning_user_address'];
		 $kota_billing_pelanggan = '';
		 if ($is_auto == 'yes') {
	 	  $user_id = get_current_user_id();
		  if($user_id > 0) {
			 $kota_billing_pelanggan = sanitize_text_field(get_user_meta($user_id, 'billing_city', true));
		  } 
		 }		 
		?> 
		<script type="text/javascript">
			jQuery(document).ready(function($) {
				billing_kota('<?php echo esc_html($kota_billing_pelanggan); ?>'); //this script set action on change to province dropdown.
			});
		</script>	
		<?php	
	}

	  function epeken_js_query_kecamatan_billing_form(){
			$shipping = WC_Shipping::instance();
			$methods = $shipping -> get_shipping_methods();
			$epeken_tikijne = $methods['epeken_courier'];
			$epeken_tikijne -> reset_shipping();
			$kec_url = admin_url('admin-ajax.php');
			wp_enqueue_script('ajax_billing_kec',plugins_url('/js/billing_kecamatan.js',__FILE__), array('jquery'), '1.3.4.2');
                                 wp_localize_script( 'ajax_billing_kec', 'PT_Ajax_Bill_Kec', array(
                                        'ajaxurl'       => $kec_url,
                                        'nextNonce'     => wp_create_nonce('myajax-next-nonce'),
				));
			 $settings = get_option('woocommerce_epeken_courier_settings');
		 	 $is_auto = $settings['auto_populate_returning_user_address'];
		 	 $kecamatan_billing_pelanggan = '';
		 	 if ($is_auto == 'yes') {
	 	  		$user_id = get_current_user_id();
		  		if($user_id > 0) {
			 	$kecamatan_billing_pelanggan = get_user_meta($user_id, 'billing_address_2', true);
		  	  } 
		 	 }		 
			?>
			<script type="text/javascript">
				jQuery(document).ready(function($) {
					billing_kecamatan('<?php echo $kecamatan_billing_pelanggan; ?>'); //this script set action on change to city dropdown.
				});
			</script>			
			<?php	
	}
	add_action('woocommerce_after_edit_address_form_billing','epeken_js_change_select_class');
	add_action('woocommerce_after_edit_address_form_shipping','epeken_js_change_select_class');
	add_action('woocommerce_after_checkout_shipping_form','epeken_js_query_kecamatan_shipping_form');
	add_action('woocommerce_after_checkout_billing_form','epeken_js_query_kecamatan_billing_form');
	add_action('woocommerce_after_edit_address_form_billing','epeken_js_query_kecamatan_billing_form');
	add_action('woocommerce_after_edit_address_form_shipping','epeken_js_query_kecamatan_shipping_form');
	add_action('woocommerce_customer_save_address', 'epeken_update_kelurahan_user');
	add_action('woocommerce_after_checkout_billing_form','epeken_js_query_kota_billing_form');
	add_action('woocommerce_after_checkout_shipping_form','epeken_js_query_kota_shipping_form');
	add_action('woocommerce_after_edit_address_form_shipping','epeken_js_query_kota_shipping_form');
	add_action('woocommerce_after_edit_address_form_billing','epeken_js_query_kota_billing_form');
/**
 * Update the order meta with field value
*/

function epeken_update_kelurahan_user () {
	global $current_user;
	if( ! empty( $_POST['billing_address_3'] ) ) {
		update_user_meta( $current_user -> ID, 'kelurahan', sanitize_text_field( $_POST['billing_address_3'] ) );
	}
	if( ! empty( $_POST['shipping_address_3'] ) ) {
                update_user_meta( $current_user -> ID, 'kelurahan', sanitize_text_field( $_POST['shipping_address_3'] ) );
        }
}

add_action( 'woocommerce_checkout_update_order_meta', 'epeken_checkout_field_update_order_meta' );
 
function epeken_checkout_field_update_order_meta( $order_id ) {
    global $current_user;
    $flag = false;
    if ( ! empty( $_POST['billing_address_3'] ) ) {
        update_post_meta( $order_id, 'billing_kelurahan', sanitize_text_field( $_POST['billing_address_3'] ) );
	update_user_meta( $current_user -> ID, 'kelurahan', sanitize_text_field( $_POST['billing_address_3'] ) );
	$flag = true;
    }
    if ( ! empty( $_POST['billing_address_2'] ) ) {
        update_post_meta( $order_id, 'billing_kecamatan', sanitize_text_field( $_POST['billing_address_2'] ) );
    }
	
    if ( WC() -> session -> get('isshippedifadr') === '1') { 
        update_post_meta( $order_id, 'shipping_kelurahan', sanitize_text_field( $_POST['shipping_address_3'] ) );
	update_post_meta( $order_id, 'shipping_kecamatan', sanitize_text_field( $_POST['shipping_address_2'] ) );
	if (!$flag)
		update_user_meta( $current_user -> ID, 'kelurahan', sanitize_text_field( $_POST['shipping_address_3'] ) );
    } else {
	update_post_meta($order_id, 'shipping_kelurahan', sanitize_text_field($_POST['billing_address_3']));
    	update_post_meta($order_id, 'shipping_kecamatan', sanitize_text_field($_POST['billing_address_2']));
    }

    WC() -> session -> set('ANGKA_UNIK', null);
}

/* Set default checkout city is blank */
add_filter( 'default_checkout_billing_city', 'epeken_default_checkout_city' );
add_filter( 'default_checkout_shipping_city', 'epeken_default_checkout_city' );
function epeken_default_checkout_city() {
  return ''; 
}

/**
 * Display field value on the order edit page
 */
add_action( 'woocommerce_admin_order_data_after_billing_address', 'epeken_billing_field_display_admin_order_meta', 10, 1 );

function epeken_billing_field_display_admin_order_meta($order){
    $order_id = '';
    $wooversion = epeken_get_woo_version_number();  

    if($wooversion >= 3)
	$order_id = $order->get_id();
    else
	$order_id = $order->id;

    echo '<p><strong>'.__('Kelurahan').':</strong> ' . esc_html(sanitize_text_field(get_post_meta( $order_id, 'billing_kelurahan', true ))) . '</p>';
    echo '<p><strong>'.__('Kecamatan').':</strong> ' . esc_html(sanitize_text_field(get_post_meta( $order_id, 'billing_kecamatan', true ))) . '</p>';
}

add_action( 'woocommerce_admin_order_data_after_shipping_address', 'epeken_shipping_field_display_admin_order_meta', 10, 1 );

function epeken_shipping_field_display_admin_order_meta($order){
    $order_id = '';
    $wooversion = epeken_get_woo_version_number();      
    if($wooversion >= 3)
        $order_id = $order->get_id();
    else
        $order_id = $order->id;

    echo '<p><strong>'.__('Kelurahan').':</strong> ' . esc_html(sanitize_text_field(get_post_meta( $order_id, 'shipping_kelurahan', true ))) . '</p>';
    echo '<p><strong>'.__('Kecamatan').':</strong> ' . esc_html(sanitize_text_field(get_post_meta( $order_id, 'shipping_kecamatan', true ))) . '</p>';
}

} // End checking if woocommerce is installed.

add_action("template_redirect", 'epeken_theme_redirect');

function epeken_theme_redirect(){
  $plugindir = dirname( __FILE__ );
  if (get_the_title() == 'cekresi') {
        $templatefilename = 'cekresi.php';
        $return_template = $plugindir . '/templates/' . $templatefilename;
        epeken_do_theme_redirect($return_template);
    }
}

function epeken_do_theme_redirect($url) {
    global $post, $wp_query;
    if (have_posts()) {
        include($url);
        die();
    } else {
        $wp_query->is_404 = true;
    }
}

add_action('admin_menu', 'epeken_license_menu');

function epeken_license_menu() {
    add_options_page('License Activation Menu', 'Epeken License Management', 'manage_options', __FILE__, 'epeken_license_management_page');
}

function license_activation_error_message($error) {
	?>
	<div class="error notice">
		<p><?php echo $error;?></p>
	</div>
	<?php
}

function license_activation_success_message($msg) {
        ?>
        <div class="notice-success notice">
                <p><?php echo $msg;?></p>
        </div>
        <?php
}

if (!function_exists('epeken_activate_license')) {
function epeken_activate_license($server, $port, $api_params) {
	global $api_end_point;
	$args = array('timeout' => 10);
	$url = 'http://'.$server.':'.$port.'/api/'.$api_end_point.'/api/license/activate/'.$api_params['license_key'].'/'.urlencode($api_params['registered_domain']).'/'.$api_params['item_reference'];
	$response = wp_remote_get($url, $args);
	$result = wp_remote_retrieve_body($response);
	if(is_wp_error($response)) {
		$emergency_url = 'http://www.epeken.com/?slm_action='.$api_params['slm_action'].'&license_key='.$api_params['license_key'].'&registered_domain='.$api_params['registered_domain'].'&item_reference='.$api_params['item_reference'];
		license_activation_error_message('Activation Fatal Error (CURL): '. $response -> get_error_message() .
		'<br>Harap tekan tombol <strong>Emergency Activation</strong> berikut ini untuk aktivasi darurat !!!<br><a href="'.$emergency_url.'" class="btn button" target="_blank">Emergency Activation</a>');
		update_option('epeken_wcjne_license_key', sanitize_text_field($api_params['license_key']));
	}
	return $result;
 }
}

if (!function_exists('epeken_license_info')){
function epeken_license_info($server, $port, $license_key) {
	global $api_end_point;
	$args = array('timeout' =>  10);
	$url = 'http://'.$server.':'.$port.'/api/'.$api_end_point.'/api/license/info/'.$license_key;
	$response = wp_remote_get($url, $args);
 	$result = wp_remote_retrieve_body($response);
	if(is_wp_error($response)) {
		echo esc_html($response -> get_error_message());
	}
	return $result;
 }
}

function epeken_license_management_page() {
    echo '<div class="wrap">';
    echo '<h2>Epeken License Management</h2>';
    $server = epeken_get_data_server();
    $ipserver = str_replace('http://','',$server);
    $port = '80';
    /*** License activate button was clicked ***/
    if (isset($_REQUEST['activate_license'])) {
        $license_key = $_REQUEST['epeken_wcjne_license_key'];
        // API query parameters
        $api_params = array(
            'slm_action' => urlencode('slm_activate'),
            'license_key' => urlencode($license_key),
            'registered_domain' => str_replace('/','{slash}',home_url()),
            'item_reference' => urlencode(EPEKEN_ITEM_REFERENCE),
        );
	$result = epeken_activate_license ($ipserver, $port, $api_params);
	$result = json_decode($result, true);
	// Check for error in the response
       	if ($result['status'] === 401){
	  $error_msg = $result['message'];
	  license_activation_error_message($error_msg);
	  update_option('epeken_wcjne_license_key', trim($license_key));
	} else if($result['status'] === 200){//Success was returned for the license activation
	  license_activation_success_message($result['message']);
	  update_option('epeken_wcjne_license_key', trim($license_key));
	} else if($result['status'] === 500) {
	  license_activation_error_message("License tidak bisa diaktifkan, hubungi tim support.");
	  update_option('epeken_wcjne_license_key', trim($license_key));
	}
    }
    /*** License activate button was clicked ***/
    ?>
    <p>Masukkan license dan klik activate untuk menggunakan plugin Epeken All Kurir. 
       Belum punya license ? <a href="https://www.epeken.com/shop/epeken-all-kurir-license/" target="_blank">Beli Di Sini</a></p>
    <form action="" method="post">
        <table class="form-table">
            <tr>
                <th style="width:100px;"><label for="epeken_wcjne_license_key">License Key</label></th>
                <td ><input class="regular-text" type="text" id="epeken_wcjne_license_key" name="epeken_wcjne_license_key"  value="<?php echo get_option('epeken_wcjne_license_key'); ?>" ></td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" name="activate_license" value="Activate" class="button-primary" />
        </p>
    </form>
<?php
    $lic = sanitize_text_field(get_option('epeken_wcjne_license_key'));
    if( !empty($lic) ) {
	    $server = epeken_get_data_server();
	    $server = str_replace('http://','',$server);
	    $license_info = epeken_license_info($server,'80',$lic);
	    $license_info = json_decode($license_info, true);
	    if($license_info['status'] === 200){
		    echo "<h2>Informasi License Epeken All Kurir</h2>"
		?>
		<table class="wp-list-table widefat fixed striped table-view-list posts"> 
			<thead><tr>
			<th scope="col"><strong>License Key</strong></th>
			<th scope="col"><strong>Aktif Pada Domain</strong></th>
			<th scope="col"><strong>Tanggal Berakhir<strong></th>
			</tr></thead>
			<tbody>
			<tr>
			<td scope="col"><?php echo esc_html($license_info['license_key']);?></td>
			<td scope="col"><?php echo esc_html($license_info['website']);?></td>
			<td scope="col"><?php echo esc_html($license_info['dateexpiry']);?></td>
			</tr></tbody>
		</table>
		<?php
                echo '<p><a href="admin.php?page=wc-settings&tab=shipping&section=epeken_courier">
		      Settings Plugin Ongkos Kirim Epeken All Kurir</a></p>';
	    }
    }
    echo '</div>';

}

        add_action( 'woocommerce_product_write_panel_tabs', 'epeken_product_write_panel_tab');
        add_action( 'woocommerce_product_write_panels',     'epeken_product_write_panel');
	add_action( 'end_wcfm_products_manage', 'epeken_hide_wcfm_epeken_product_config', 1000 );

        function epeken_hide_wcfm_epeken_product_config () {
		if(epeken_is_multi_vendor_mode()) {
                ?>
                <script type="text/javascript">
                        jQuery(document).ready(function($) {
                                $('#wcfm_products_manage_form_epeken_head').hide();
                        });
                </script>
                <?php
		}
        }

        function epeken_product_write_panel_tab() {
                echo "<li class=\"product_tabs_lite_tab\"><a href=\"#woocommerce_product_tabs_lite\">" . __( 'Epeken Product Config', 'woocommerce' ) . "</a></li>";
        }

        function epeken_product_write_panel() {
		global $post;
		if(epeken_is_multi_vendor_mode()) {
			//do nothing if multi vendor mode is true
			?>
			<div id="woocommerce_product_tabs_lite" class="panel wc-metaboxes-wrapper woocommerce_options_panel" style="padding: 10px;">
                		<table>
				<tr><td>
			<p>Toko Online Anda berkonsep marketplace. Untuk menerapkan konsep marketplace, pastikan license Anda dilengkapi opsi multi origin. Anda dapat melakukan setting kota asal di level vendor/seller/pelapak sehingga produk ini dapat mengikuti kota asal vendornya. <a href="http://blog.epeken.com/plugin-epeken-support-plugin-marketplace/" target="_blank">Selengkapnya</a></p>
				</td></tr></table>
			</div>
			<?php
			return;
		}
		$epeken_product_config = array (
			"product_origin" => get_post_meta($post->ID,'product_origin',true)
		);

		$product_origin = $epeken_product_config['product_origin'];
                ?>
		
                <div id="woocommerce_product_tabs_lite" class="panel wc-metaboxes-wrapper woocommerce_options_panel" style="padding: 10px;">
		<table>
		<tr>
		<td colspan=2><strong>Note:</strong> <em>Setting Kota Asal Di level product ini tidak berlaku jika Anda menginstal plugin Marketplace yang sudah disupport oleh plugin Epeken, 
		seperti WC-Vendors, Dokan atau WC-Marketplace.</em></td>
		</tr>
               	<?php
		  $license = get_option('epeken_wcjne_license_key');		  
		  $origins = epeken_get_valid_origin($license);
		  $origins = json_decode($origins,true);
		  $origins = $origins["validorigin"];
		  ?>
			<tr>
			<td width=40% height=30px>Kota asal pengiriman produk ini </td><td><strong><?php echo epeken_code_to_city($product_origin); ?></strong></td>
			</tr>
			<tr><td width=40%>Ubah Kota Asal Pengiriman Ke</td> <td><select name="epeken_valid_origin_option" id="epeken_valid_origin_option">
		<?php
			foreach($origins as $origin) {
			  ?>
				<option value=<?php echo $origin["origin_code"]; ?> <?php if ($product_origin === $origin["origin_code"]) echo " selected";?>> <?php echo $origin["kota_kabupaten"];?></option>
			  <?php
			}
		
			if (empty($origins)) {
				?>
				<option value=<?php echo get_option('epeken_data_asal_kota');?>> <?php echo epeken_code_to_city(get_option('epeken_data_asal_kota')); ?> </option>
				<?php
			}
		?> 
			</select></td></tr>
		</table>
			<?php 
			$product_id = $post -> ID; 
                $product_insurance_mandatory = get_post_meta($product_id,'product_insurance_mandatory',true);
                $product_wood_pack_mandatory = get_post_meta($product_id,'product_wood_pack_mandatory',true);
                $product_free_ongkir = get_post_meta($product_id,'product_free_ongkir',true);
			?><div style="margin-top: 10px;">
			<table>
                        <tr><td valign="top">
                        <input type="checkbox" name="epeken_product_insurance_mandatory" id="epeken_product_insurance_mandatory" <?php if($product_insurance_mandatory === 'on') echo 'checked'; ?> /></td><td> Wajib Dikirim Menggunakan Asuransi (Khusus JNE). Jika ini dicentang, "Aktifkan Asuransi" juga harus dicentang di halaman setting plugin Epeken All Kurir.
                        </td></tr>
                        <tr><td valign="top" style="padding-top: 10px;">
                        <input type="checkbox" name="epeken_product_wood_pack_mandatory" id="epeken_product_wood_pack_mandatory" <?php if($product_wood_pack_mandatory === 'on') echo 'checked';?> /></td><td style="padding-top: 10px;"> Wajib Dikirim Menggunakan Packing Kayu. Untuk mewajibkan packing kayu pada item ini, pastikan Anda sudah melakukan Enable Packing Kayu di WooCommerce > Shipping > Epeken Courier > Packing Kayu Settings.
                        </td></tr>
                        <tr><td valign="top" style="padding-top: 10px;">
                        <input type="checkbox" name="epeken_product_free_ongkir" id="epeken_product_free_ongkir" <?php if($product_free_ongkir === 'on') echo 'checked'; ?> /> </td><td style="padding-top: 10px;">Gratiskan Ongkos Kirim Untuk Produk Ini (Bisa dikombinasikan dengan Pilihan Kota Gratis Ongkir di bawah.)
                        </td></tr>
						<tr>
						<td style="padding-left: 20px;" colspan=2>Gratis Ongkir Untuk Kota Berikut ini: <br>
						<select <?php 
							if($product_free_ongkir !== 'on')
								echo 'disabled ';
						?> multiple="multiple" class="multiselect chosen_select ajax_chosen_select_city" name="product_city_for_free_shipping[]" id="product_city_for_free_shipping" style="width: 450px;" data-placeholder="Pilih Kota&hellip; Kosongkan jika ingin gratis ongkir untuk semua kota">
							<?php
							$listkotakab = epeken_get_list_of_kota_kabupaten();
							foreach($listkotakab as $kotakab) {
								if($kotakab === 'Kota/Kabupaten (City)')
									continue;
								
								$existing_config = get_post_meta($product_id, 'epeken_product_city_for_free_shipping', true);
								$selected = '';
								if (!empty($existing_config)){
								for($x=0;$x<sizeof($existing_config);$x++){
												if($kotakab === $existing_config[$x]){
														$selected = 'selected';
														break;
												}
										 } 
								}
								?>
									<option value="<?php echo $kotakab;?>" <?php echo $selected; ?>><?php echo $kotakab;?></option>
								<?php
							}
							?>
						</select>
						</td>
						</tr>
			<?php do_action('epeken_add_product_config'); ?>
                        <tr><td colspan=2>
                        <div style='float: right;position: relative;'><input name="save" type="submit" style="margin-left: 0px !important" class="button button-primary button-large" id="publish" value="Update"></div>
                        </td></tr>
                        </table>
			</div>
                	</div>
			<script language='javascript'>
                        var chkfreeongkir = document.getElementById('epeken_product_free_ongkir');
                        var chkinsman = document.getElementById('epeken_product_insurance_mandatory');
                        var chkwoodpackman = document.getElementById('epeken_product_wood_pack_mandatory'); 
						var slctkotakabfreeongkir = document.getElementById('product_city_for_free_shipping'); 
					
                        chkfreeongkir.onclick = function() {
                                if(chkfreeongkir.checked) {
                                        chkinsman.checked = false; chkwoodpackman.checked = false;      
										slctkotakabfreeongkir.disabled = false;
                                }else{
										slctkotakabfreeongkir.disabled = true;
								}
                        }       
                        chkinsman.onclick = function() {
                                if (chkinsman.checked && chkfreeongkir.checked) {
                                        alert('Tidak bisa diset bersama dengan gratis ongkir.');
                                        chkinsman.checked = false;
                                }
                        }
                        chkwoodpackman.onclick = function() {
                                if (chkwoodpackman.checked && chkfreeongkir.checked) {
                                                alert('Tidak bisa diset bersama dengan gratis ongkir.');
                                                chkwoodpackman.checked = false;
                                        }
                        }
            </script>
                <?php
        }

    function epeken_process_epeken_product_conf( $post_id ) {
	$product_origin_selected = isset($_POST['epeken_valid_origin_option']) ? $_POST['epeken_valid_origin_option'] : '';
	$product_origin = get_post_meta($post_id,'product_origin',true);
	$data_asal_kota = get_option('epeken_data_asal_kota');
	if (empty($product_origin) && !empty($data_asal_kota)) {
		update_post_meta( $post_id, 'product_origin', $data_asal_kota);
	}else{
		update_post_meta( $post_id, 'product_origin', $product_origin_selected);
	}
		$product_insurance_mandatory = isset($_POST['epeken_product_insurance_mandatory']) ? $_POST['epeken_product_insurance_mandatory'] : '';
                 update_post_meta( $post_id, 'product_insurance_mandatory', $product_insurance_mandatory);

                $product_wood_pack_mandatory = isset($_POST['epeken_product_wood_pack_mandatory']) ? $_POST['epeken_product_wood_pack_mandatory'] : '';
                 update_post_meta( $post_id, 'product_wood_pack_mandatory', $product_wood_pack_mandatory);

                $product_free_ongkir = isset($_POST['epeken_product_free_ongkir']) ? $_POST['epeken_product_free_ongkir'] : '';
                 update_post_meta( $post_id, 'product_free_ongkir', $product_free_ongkir);
				 
				$product_city_for_free_shipping = isset($_POST['product_city_for_free_shipping']) ? $_POST['product_city_for_free_shipping'] : '';
				  update_post_meta($post_id, 'epeken_product_city_for_free_shipping', $product_city_for_free_shipping);
    }
    add_action('woocommerce_process_product_meta', 'epeken_process_epeken_product_conf');


function validate_add_cart_item( $passed, $product_id, $quantity, $variation_id = '', $variations= '' ) {
    global $woocommerce;
    $items = $woocommerce -> cart -> get_cart();
    $passed = true;
    if(sizeof($items) === 0){
		return $passed;
    }
    $first_item = reset($items); //get first element of items.

	if(epeken_is_multi_vendor_mode()) {
		
     	$vendor_in_cart = epeken_get_item_vendor ($first_item['product_id']);
     	$vendor_added_cart_item = epeken_get_item_vendor($product_id);
    	if($vendor_in_cart != $vendor_added_cart_item) {
		//$passed = false;
		//wc_add_notice(__('Mohon maaf, Item ini adalah item dengan beda pelapak, silakan lakukan pembelian item ini dengan transaksi yang terpisah.'), 'error');
     	} 
    } else {
		
     	$product_origin_in_cart = get_post_meta($first_item['product_id'],'product_origin',true) ;
     	$product_origin_added_cart_item = get_post_meta($product_id,'product_origin',true) ;
     	if(empty($product_origin_in_cart)) {
                $product_origin_in_cart = get_option('epeken_data_asal_kota')                   ;    
        }    
     	if(empty($product_origin_added_cart_item)) {
                $product_origin_added_cart_item = get_option('epeken_data_asal_kota')                   ;    
        }    
     	if ( $product_origin_in_cart != $product_origin_added_cart_item ){
        	$passed = false;
        	wc_add_notice( __( 'You can\'t buy this item in one cart with existing items in the cart that have different origin. Please finish creating order on existing items in the cart, then you can buy this item by creating new order.', 'epeken-all-kurir' ), 'error' );
     	}   
	
		$product_free_ongkir = get_post_meta($first_item['product_id'],'product_free_ongkir',true);
		$product_added_free_ongkir = get_post_meta($product_id,'product_free_ongkir',true);
		if ($product_free_ongkir === 'on' && $product_added_free_ongkir !== 'on') {
			$passed = false;
			wc_add_notice( __('You may get free shipping for existing products in the cart. Please finish creating order on existing items in the cart to get free shipping, then you can buy this item by creating new order.'), 'error');
		}
		
    }
    return $passed;
}
add_filter( 'woocommerce_add_to_cart_validation', 'validate_add_cart_item', 10, 5 );

add_action( 'show_user_profile', 'epeken_extra_user_profile_fields' );
add_action( 'edit_user_profile', 'epeken_extra_user_profile_fields' );
function epeken_extra_user_profile_fields( $user ) {
?>
  <h3><?php _e("Informasi Tambahan", "blank"); ?></h3>
  <table class="form-table">
    <tr>
      <th><label for="kelurahan"><?php _e("Kelurahan"); ?></label></th>
      <td>
        <input type="text" name="kelurahan" id="kelurahan" class="regular-text" 
            value="<?php echo esc_attr( get_the_author_meta( 'kelurahan', $user->ID ) ); ?>" /><br />
        <span class="description"><?php _e("Data Kelurahan User"); ?></span>
    </td>
    </tr>
  </table>
<?php
}


add_action( 'personal_options_update', 'epeken_save_extra_user_profile_fields' );
add_action( 'edit_user_profile_update', 'epeken_save_extra_user_profile_fields' );
function epeken_save_extra_user_profile_fields( $user_id ) {
  $saved = false;
  if ( current_user_can( 'edit_user', $user_id ) ) {
    update_user_meta( $user_id, 'kelurahan', $_POST['kelurahan'] );
    $saved = true;
  }
  return true;
}

add_action('woocommerce_after_cart_totals','epeken_disable_shipping_in_cart');
function epeken_disable_shipping_in_cart (){

 ?> <script language="javascript"> 
        var elements = document.getElementsByClassName('shipping'); 
        elements[0].style.display = 'none';
  </script><?php

}

function epeken_short_code_cekresi() {
        $plugindir =  dirname( __FILE__ );
        include($plugindir.'/templates/cekresi.php');
}

add_shortcode('epeken_cekresi','epeken_short_code_cekresi');
add_action ('woocommerce_single_product_summary', 'epeken_display_kota_asal_pengiriman');
 function epeken_display_kota_asal_pengiriman() {
                $shipping = WC_Shipping::instance();
		$methods = $shipping -> get_shipping_methods();
		$epeken_tikijne = $methods['epeken_courier'];

                if ($epeken_tikijne -> settings['is_kota_asal_in_product_details'] === 'no')
                        return;

		if (epeken_is_multi_inventory())
			return;

                $post = get_post();
                $origin_code = get_post_meta($post -> ID,'product_origin',true) ;
                if(!empty($origin_code)) {
                 $city = epeken_code_to_city($origin_code);    
                }else{
                 $city = epeken_code_to_city(get_option('epeken_data_asal_kota')); //$this -> settings['data_kota_asal']); 
                }    
				$wc_product_post = get_post($post -> ID);
				$product_author = $wc_product_post -> post_author;
				if(epeken_is_multi_vendor_mode()){
				  if(epeken_is_woo_dropshippers_active()) {
					$city = epeken_get_item_dropshipper_origin($post -> ID);
				  }else if(epeken_is_yith_multivendor_active()) {
					   $vendor = yith_get_vendor( $post->ID, 'product' );
					   $vendor_id = epeken_yith_get_user_id_from_vendor($vendor);
					   $city = get_user_meta($vendor_id,'vendor_data_kota_asal',true) ;
				  }else{
					$city = epeken_get_item_vendor_origin($post -> ID);
				  }
				 	$city = epeken_code_to_city($city);
				}
                ?>  
                <div class="summary entry-summary" style="margin-top: 20px; margin-bottom: 20px;">Kota Asal Pengiriman : <?php echo $city;?></div><div style="clear:both"></div>
                <?php
 }
  function epeken_plugin_add_settings_link( $links ) {
	  $settings_link = '<a href="admin.php?page=wc-settings&tab=shipping&section=epeken_courier">' . __( 'Settings' ) . '</a>';
	  $lic_link = '<a href="options-general.php?page=epeken-all-kurir%2Fepeken_courier.php">'.__('License').'</a>';
	  array_push( $links, $settings_link );
	  array_push($links,$lic_link);
  	return $links;
  }
  $plugin = plugin_basename( __FILE__ );
  add_filter( "plugin_action_links_$plugin", 'epeken_plugin_add_settings_link' );
  
  function is_epeken_all_kurir_setting_page() {
	return (is_admin() && $_GET['page'] === 'wc-settings' && $_GET['tab'] === 'shipping' && $_GET['section'] === 'epeken_courier');
  }
  
  function set_payment_cod ($gateways) {
	    $gateway = new WC_Gateway_COD();
	    //$gateways = array();
	    $gateways['cod'] = $gateway;
	    return $gateways;
  }

  if(!function_exists('epeken_dequeue_jquery_migrate')) {
   function epeken_dequeue_jquery_migrate( $scripts ) {
      if (! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
        $scripts->registered['jquery']->deps = array_diff(
            $scripts->registered['jquery']->deps,
            [ 'jquery-migrate' ]
      );
    }
   }
   add_action( 'wp_default_scripts', 'epeken_dequeue_jquery_migrate',999999);
  }

  #add_action('epeken_custom_tariff', '');

  add_action('wp_enqueue_scripts','epeken_register_scripts');
  function epeken_register_scripts() {
	  global $wp, $wpdb, $wp_version;
	  $table_prefix = $wpdb->prefix;
	  $query = "SELECT ID FROM ".$table_prefix."posts WHERE post_content LIKE '%epeken_konfirmasi_pembayaran%' AND post_type = 'page' AND post_parent = 0";
	  $results = $wpdb->get_results($query);
	  $page_id_konfirmasi_pembayaran = array();
	  foreach($results as $result) {
	    array_push($page_id_konfirmasi_pembayaran, $result->ID);
	  }
	  
	  $t = is_checkout();
	  $epeken_wp_ver = substr($wp_version,0,3);
  	  if($t) {
		  /* Force to use jquery migrate 1.4.1 in checkout page */
		  wp_enqueue_style('epeken_style_checkout',plugins_url('assets/epeken-style-checkout.css',__FILE__), array(), '1.3.5.2');
		  wp_enqueue_script('jquery-migrate-epeken',plugins_url('assets/jquery-migrate.min.js',__FILE__), array('jquery'),'1.4.1');
		  wp_deregister_script('jquery-migrate');
                  wp_dequeue_script('jquery-migrate');
		  $is_enable_cod_kurir = get_option('epeken_enable_cod_kurir');
		  if ($is_enable_cod_kurir === 'on') {
		      wp_enqueue_script('epeken_payment_method_chosen_action', 
		 	plugins_url('class/js/payment_method_action.js', __FILE__), 
			array('jquery'), false, true);
		  }
  	  }else{
		$jqmradio = intval(sanitize_text_field(get_option('epeken_jqmradio')));
		if($epeken_wp_ver >= 5.6) {
		 if($jqmradio == 0 || $jqmradio == 2)	
		  wp_enqueue_script('jquery-migrate',ABSPATH.'includes/js/jquery/jquery-migrate.min.js', array(), '3.3.2', true); //move jquery migrate to footer.
		 else if($jqmradio == 1) 
		  wp_enqueue_script('jquery-migrate',ABSPATH.'includes/js/jquery/jquery-migrate.min.js', array(), '3.3.2', false); //move jquery migrate to header.
		}
	  }

	  wp_enqueue_script('jquery-cookie',plugins_url('assets/jquery.cookie.js',__FILE__), array('jquery'));
	  wp_enqueue_style('epeken_plugin_styles', plugins_url('/class/assets/css/epeken-plugin-style.css',__FILE__), null, '1.1.8.6.14');
	  
	  $id = get_the_ID();
	  
	  if(is_checkout()) {
			 wp_enqueue_script('select2-js', plugins_url().'/woocommerce/assets/js/select2/select2.js');
			 wp_enqueue_style('select2', plugins_url().'/woocommerce/assets/css/select2.css');
	  }
	
	  if (in_array($id,$page_id_konfirmasi_pembayaran)) {	
	   /* enqueue scripts for konfirmasi pembayaran page */
	   wp_enqueue_script('select2-js', plugins_url().'/woocommerce/assets/js/select2/select2.js');
	   wp_enqueue_style('select2', plugins_url().'/woocommerce/assets/css/select2.css');
	   wp_enqueue_style('sepeken_style',plugins_url('assets/epeken-style.css',__FILE__));
	   wp_enqueue_style('epeken_jquery_style',plugins_url('assets/jquery-ui.min.css',__FILE__));
 	   wp_enqueue_script('jquery-ui',plugins_url('assets/jquery-ui.min.js',__FILE__), array('jquery'));
	   wp_enqueue_script('ajax_epeken_konfirmasi_pembayaran',plugins_url('assets/konfirmasi_pembayaran.js',__FILE__), array('jquery'), '1.1.8.6.10');
	   wp_localize_script( 'ajax_epeken_konfirmasi_pembayaran', 'PT_Ajax_Konfirmasi_Pembayaran', array(
		'ajaxurl'       => admin_url('admin-ajax.php'),
		'nextNonce'     => wp_create_nonce('epeken-konfirmasi-pembayaran'),
	   ));
	  }
	} 
  function epeken_get_woo_version_number() {
        // If get_plugins() isn't available, require it
        if ( ! function_exists( 'get_plugins' ) )
                require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // Create the plugins folder and file variables
        $plugin_folder = get_plugins( '/' . 'woocommerce' );
        $plugin_file = 'woocommerce.php';

        // If the plugin version number is set, return it 
        if ( isset( $plugin_folder[$plugin_file]['Version'] ) ) {
                return $plugin_folder[$plugin_file]['Version'];

        } else {
        // Otherwise return null
                return NULL;
        }
       }
	   
  function epeken_check_license() {	
		$current_screen = get_current_screen();
		if($current_screen -> base === 'settings_page_epeken-all-kurir/epeken_courier') {
			return;
		}
		$license = get_option('epeken_wcjne_license_key');
		$activation_menu = get_admin_url(null, 'options-general.php?page=epeken-all-kurir/epeken_courier.php', null);
		if(empty($license)) {
		 ?> <div class="error notice"><p><strong>Plugin Epeken All Kurir Kakak belum berlisensi. Tanpa lisensi, plugin Epeken All Kurir tidak akan berfungsi.</strong> Jika Kakak belum punya license, silakan <a href="http://www.epeken.com/shop/epeken-all-kurir-license/" target="_blank">beli di sini</a>. Jika Kakak sudah membeli license tapi belum menerimanya, silakan menunggu email pengiriman License dengan sabar. Jika Kakak sudah memiliki nomor license, silakan langsung saja mengaktifkan licensi <a href="<?php echo $activation_menu; ?>">di sini</a>.</p></div> <?php
		}
  }
  add_action('admin_notices', 'epeken_check_license');
  
  function epeken_load_textdomain() {
		load_plugin_textdomain( 'epeken-all-kurir', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
  }
  add_action( 'plugins_loaded', 'epeken_load_textdomain' );
  
  if(!function_exists('epeken_email_template_html')) {
	function epeken_email_template_html($judul, $content) {
		ob_start();
		$email_base_color = get_option('woocommerce_email_base_color');
		$img_header = get_option( 'woocommerce_email_header_image' ) ;
		?>
			<div class="rcmBody" style="font-size: 10pt; font-family: &quot;Trebuchet MS&quot;,Geneva,sans-serif">
			<div class="rcmBody" style="font-size: 10pt; font-family: 'Trebuchet MS',Geneva,sans-serif">
			<div style="background-color: #f5f5f5; margin: 0px">
			<p style="text-align: center"> </p>
			<p style="text-align: center"><img src='<?php echo esc_url($img_header); ?>' /></p>
			<table style="height: 122px; margin-left: auto; margin-right: auto; box-shadow: 0 1px 4px rgba(0,0,0,0.1) !important; background-color: <?php echo $email_base_color; ?>; border: 1px solid #dcdcdc; border-radius: 3px !important" border="0" width="600" cellspacing="0" cellpadding="20"><tbody><tr><td >
			<table border="0" cellpadding="0" cellspacing="0" width="600" id="template_header">
			 <tr>
		         <td id="header_wrapper" style='background-color: <?php echo $email_base_color; ?>'>
			     <h1 style="text-align: center">
				<span style="color: #ffffff; font-family: 'trebuchet ms', geneva, sans-serif">
				  <?php echo $judul; ?></span></h1>
			     </td>
			 </tr>
			</table>
			</td>
			</tr></tbody></table><table style="margin-left: auto; margin-right: auto" border="0" cellspacing="0" cellpadding="0"><tbody><tr><td valign="top">
			<table style="width: 600px" border="0" cellspacing="0" cellpadding="20"><tbody><tr><td style="background-color: #fff" valign="top">
			<?php echo $content; ?>
			<p style="text-align: justify" align="justify"><span style="font-family: 'trebuchet ms', geneva, sans-serif">
			<?php echo __('Best Regards,','epeken-all-kurir');?></span></p>
			</td>
			</tr>
			<tr>
			<td style='background-color: #fff;'>
				<table border="0" cellpadding="0" cellspacing="0" width=600 id="template_footer">
				<tr>
					<td valign="top">
						<table border="0" cellpadding="0" cellspacing="0" width="100%">
							<tr>
								<td valign="middle" id="credit">
									<?php echo wpautop( wp_kses_post( wptexturize( apply_filters( 'woocommerce_email_footer_text', get_option( 'woocommerce_email_footer_text' ) ) ) ) ); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				</table>
			</td>
			</tr>
			</tbody></table></td>
			</tr></tbody></table><p style="text-align: center">:)</p>
			<p><br /></p>
			</div>
			</div>
		<?php
		$email_body = ob_get_clean();
		return $email_body;
	}
  }
  
add_action('woocommerce_view_order','epeken_view_order');
function epeken_view_order($order_id)
{
 echo $order_id;
 $order=new WC_Order( $order_id );
 $bank = null;
  if ( $order->payment_method == 'maybank')  {
	$bank = new Maybank();
  } else if($order->payment_method == 'bank_bca') {
	$bank = new  BCA();
  } else if($order->payment_method == 'bank_bii') {
	$bank = new  BII();
  } else if($order->payment_method == 'bank_bni') {
	$bank = new  BNI();
  } else if ($order->payment_method == 'bank_bni_syariah') {
	$bank = new BNISyariah();  
  } else if ($order->payment_method == 'bank_bri') {
	$bank = new BRI();  
  } else if ($order->payment_method == 'bank_bri_syariah') {
	$bank = new BRISyariah();  
  } else if ($order->payment_method == 'bank_syariah_nmandiri') {
	$bank = new BSM();  
  } else if ($order->payment_method == 'btpn') {
	$bank = new BTPN();  
  } else if ($order->payment_method == 'bank_mandiri') {
	$bank = new Mandiri();  
  } else if ($order->payment_method == 'bank_muamalat') {
	$bank = new Muamalat();  
  } else if ($order->payment_method == 'bank_niaga') {
	$bank = new Niaga();  
  } else if ($order->payment_method == 'bank_permata') {
	$bank = new Permata();  
  } 
  if(!empty($bank)) {
	  $bank->thankyou_page( $order_id );
  }
}

add_action ('wp_footer', 'epeken_is_checkout_auto_select_address');
function epeken_is_checkout_auto_select_address () {

		 $settings = get_option('woocommerce_epeken_courier_settings');
		 $is_auto = $settings['auto_populate_returning_user_address'];

		 if ($is_auto !== 'yes')
			return;

		 $user_id = get_current_user_id();
		 if($user_id > 0 && function_exists('is_checkout') && is_checkout()) {
			 $province_pelanggan = get_user_meta($user_id,'billing_state', true);
			 $province_pelanggan_state = get_user_meta($user_id,'shipping_state', true);
			 $kota_billing_pelanggan = get_user_meta($user_id, 'billing_city', true);
			 $kota_shipping_pelanggan = get_user_meta($user_id, 'shipping_city', true);
		?>
		<script type="text/javascript">
			 jQuery(document).ready(function($) {
				   $("#billing_state").val('<?php echo $province_pelanggan; ?>').trigger('change');
				   $("#billing_city").val('<?php echo $kota_billing_pelanggan; ?>').trigger('change');
				   $("#shipping_state").val('<?php echo $province_pelanggan_state; ?>').trigger('change');
				   $("#shipping_city").val('<?php echo $kota_shipping_pelanggan; ?>').trigger('change');
                	});
		</script>			
		<?php
	}	
}
  
/* functions to support collaboration between epeken and wc-vendors and other marketplace plugin */
include_once('includes/epeken_multi_vendors.php');
include_once('includes/epeken_wcpv.php');

add_filter('woocommerce_no_shipping_available_html','epeken_no_shipping_available_html');
function epeken_no_shipping_available_html() {
	return __('Apologize, No shipping information for your shipping destination address. Please contact our Customer Service.','epeken-all-kurir');
}

add_filter('woocommerce_shipping_may_be_available_html', 'epeken_shipping_may_be_available_html');
function epeken_shipping_may_be_available_html() {
	return __('Please fill in your shipping destination address completely to get shipping cost options.','epeken-all-kurir');
}

add_action('admin_enqueue_scripts','epeken_admin_enqueue_scripts');
function epeken_admin_enqueue_scripts($hook) {
  wp_enqueue_script('jquery-migrate',ABSPATH.'includes/js/jquery/jquery-migrate.min.js', '3.3.2');
}

if(!function_exists('epeken_check_timed_out')) {
add_action('admin_notices','epeken_check_timed_out');
function epeken_check_timed_out() {
 $url = epeken_get_data_server();
 $response = wp_remote_get($url);
 $content = wp_remote_retrieve_body($response);
 $server_data = 'http://103.252.101.131';
 if($url === $server_data) {
  $server_data = '<STRONG>data server Indonesia</STRONG>';
 }else{
  $server_data = '<em>'.$url.'</em>';
 } 
 if(is_wp_error($response) && is_epeken_all_kurir_setting_page()) {
   echo '<div class="notice notice-error">
          <p>Terjadi kendala pada Plugin Epeken All Kurir saat membuat koneksi ke '.$server_data.'. 
   	  Troubleshoot: Silakan mencoba mengubah koneksi ke server data yang lain atau hubungi tim support kami.</p>
         </div>';
 }
 }
}

add_action('wp_head', 'epeken_wp_head', 999999);
function epeken_wp_head() {
  $theme = wp_get_theme();
  $theme_name = $theme -> Name;
  if($theme_name === 'Saudagar' ||
	  $theme_name === 'Saudagar Child')
  {
    ?>
  <style>
   /* This is to display billing_address_2 and shipping_address_2 in checkout page which was hidden by Saudagar theme */
   #billing_address_2_field, #shipping_address_2_field {
     display: block !important;
   }
  </style>
    <?php
  } 
}

add_action('epeken_custom_tariff', 'epeken_add_ori_dest_info', 9999);
add_action('epeken_custom_international_tariff', 'epeken_add_ori_dest_info', 9999);
function epeken_add_ori_dest_info($shipping) {
       if(sizeof($shipping -> array_of_tarif) > 0 && !epeken_is_multi_vendor_mode()){
            add_action('woocommerce_review_order_before_shipping', array($shipping, 'add_ori_dest_info'), 10,1);
       }
}

add_action('epeken_custom_tariff', 'epeken_additional_cost_cod_kurir', 9998);
function epeken_additional_cost_cod_kurir($shipping) {
   $enable = get_option('epeken_enable_cod_kurir');
   $pm = trim(WC() -> session -> get('chosen_payment_method'));
   if($enable === 'on' && $pm === 'cod') {
	add_action('woocommerce_cart_calculate_fees', 'epeken_add_fee_cod_kurir');
   } 
}

function epeken_add_fee_cod_kurir() {
   global $woocommerce;
   $perc = get_option('epeken_cod_kurir_perc');
   $chosen = WC() -> session -> get('chosen_shipping_methods');
   $rates = WC()->session->get('shipping_for_package_0')['rates'];
   $ongkir = 0;
        foreach($rates as $rate) {
          if ($rate -> get_id() === $chosen[0]) {
                $ongkir = $rate -> get_cost();
                break;
          }
        }
   $amount = ($perc/100)*(($woocommerce->cart->subtotal)+$ongkir);
   $amount = round($amount,2);
   $woocommerce -> cart -> add_fee(__('Biaya COD Kurir','epeken-all-kurir'),$amount,false, '');
}

//Change the 'Billing details' checkout label to 'Contact Information'
function epeken_wc_billing_field_strings( $translated_text, $text, $domain ) {
  switch ( $translated_text ) {
  case 'Billing details' :
     $translated_text = __( 'Contact Information', 'woocommerce' );
  break;
  }
  return $translated_text;
}
add_filter( 'gettext', 'epeken_wc_billing_field_strings', 20, 3 );

// Product thumbnail in checkout
add_filter( 'woocommerce_cart_item_name', 'epeken_product_thumbnail_in_checkout', 20, 3 );
function epeken_product_thumbnail_in_checkout( $product_name, $cart_item, $cart_item_key ){
    if ( is_checkout() )
    {
        $thumbnail   = $cart_item['data']->get_image(array( 80, 80));
        $image_html  = '<div class="product-item-thumbnail">'.$thumbnail.'</div> ';

        $product_name = $image_html . $product_name;
    }
    return $product_name;
}

