<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;
use Adminz\Admin\ADMINZ_Woocommerce;
use Adminz\Admin\ADMINZ_Flatsome;
use Adminz\Helper\ADMINZ_HELPER_ACF_THX_CHECKOUT;


class ADMINZ_Helper_ACF_THX{
	public static $data;
	public static $field_group = 'group_thx';

	
	public static $tinh_slug = 'thx625bb03e2b63d';
	public static $tinh_label = 'Tỉnh/Thành phố';
	public static $tinh_name = 'tinh';

	public static $huyen_slug = 'thx625bb0472b63e';	
	public static $huyen_label = 'Quận/Huyện';
	public static $huyen_name = 'huyen';

	public static $xa_slug = 'thx625bb0562b63f';
	public static $xa_label = 'Phường/Xã';
	public static $xa_name = 'xa';

	public static $duong_slug = 'thx625bb0662b63f';
	public static $duong_label = 'Đường/Phố/Thôn xóm';
	public static $duong_name = 'duong';


	function __construct() {
		if (!class_exists( 'WooCommerce' )) return;
		if (!in_array('Flatsome', [wp_get_theme()->name, wp_get_theme()->parent_theme])) return ; 
		if(!(isset(ADMINZ_Woocommerce::$options['enable_acf_thx']) and ADMINZ_Woocommerce::$options['enable_acf_thx'] == 'on') ){
			return ;
		}
		if(!function_exists('get_field')){
			return;
		}
		add_action('acf/init', [$this,'import_fields']);
		add_action('init',[$this,'thx_ajax_frontend']);
		add_action( 'wp_enqueue_scripts', [$this,'import_js_frontend']);

		// admin use date
		if($this->is_use_data()){
			$this->import_data();
			add_action('admin_init',[$this,'thx_ajax_backend']);	
			add_filter('acf/load_field/key=field_'.self::$tinh_slug, [$this,'custom_field_select'] );
			add_action('admin_enqueue_scripts', [$this,'import_js_admin']);
		}
		// woocommerce query
		add_action( 'woocommerce_product_query', [$this,'change_woo_query'] );
		// woocommerce checkout
		if(isset(ADMINZ_Woocommerce::$options['enable_acf_thx_checkout_field']) and ADMINZ_Woocommerce::$options['enable_acf_thx_checkout_field'] == "on"){
			new ADMINZ_HELPER_ACF_THX_CHECKOUT() ;
		}

	}
	public static function get_tinh_label(){
		return apply_filters('adminz_get_tinh_label', self::$tinh_label);
	}
	public static function get_huyen_label(){
		return apply_filters('adminz_get_huyen_label', self::$huyen_label);
	}
	public static function get_xa_label(){
		return apply_filters('adminz_get_xa_label', self::$xa_label);
	}
	public static function get_duong_label(){
		return apply_filters('adminz_get_duong_label', self::$duong_label);
	}

	public static function get_tinh_name(){
		return apply_filters('adminz_get_tinh_name', self::$tinh_name);
	}
	public static function get_huyen_name(){
		return apply_filters('adminz_get_huyen_name', self::$huyen_name);
	}
	public static function get_xa_name(){
		return apply_filters('adminz_get_xa_name', self::$xa_name);
	}
	public static function get_duong_name(){
		return apply_filters('adminz_get_duong_name', self::$duong_name);
	}

	function is_use_data(){

		return !(isset(ADMINZ_Woocommerce::$options['enable_acf_thx_disable_data']) and ADMINZ_Woocommerce::$options['enable_acf_thx_disable_data'] == 'on');
	}

	function change_woo_query( $query ) {
	    $key_arr = ADMINZ_Woocommerce::get_arr_meta_key('product');
	    if(!empty($_GET) and is_array($_GET)){
	    	$meta_query = (array)$query->get('meta_query');
	    	foreach ($_GET as $key => $value) {
	    		if(in_array($key, $key_arr)){
	    			$meta_query[] = array(
		                'key'     => $key,
		                'value'   => explode(",", $value),
		                'compare' => 'IN',
		        	);  
	    		}
	    	}
	    	$query->set('meta_query',$meta_query);
	    }
	}
	function import_data(){
		require_once( trailingslashit( ADMINZ_DIR ) . 'helper/thx/data.php' );
		self::$data = $tinh_huyen_xa;
	}

	function import_fields(){

		require_once( trailingslashit( ADMINZ_DIR ) . 'helper/thx/acf_fields.php' );
	}

	function custom_field_select($field){

		$field['choices'] = array();
	  	$tmp = [];		  
	  	foreach (self::$data as $key => $value) {
        	$tmp[] = $value['ten_tinh'];
	  	}
	  	foreach ($tmp as $ten_tinh) {
        	$field['choices'][ $ten_tinh ] = $ten_tinh;
	  	}     

	  	return $field; 
	}

	function thx_ajax_frontend(){
		require_once( trailingslashit( ADMINZ_DIR ) . 'helper/thx/ajax-frontend.php' ); 
	}
	function thx_ajax_backend(){
		require_once( trailingslashit( ADMINZ_DIR ) . 'helper/thx/ajax-backend.php' ); 
	}

	function import_js_admin(){
		wp_enqueue_script( 'acfthx', plugin_dir_url(ADMINZ_BASENAME) . 'helper/thx/admin.js', ['jquery'] );
        wp_localize_script( 
            'acfthx', 
            'pa_vars', 
            array(
	            'pa_nonce' => wp_create_nonce( 'pa_nonce' ),
	            'id_field_tinh'=> self::$tinh_slug,
	            'id_field_huyen'=> self::$huyen_slug,
	            'id_field_xa'=> self::$xa_slug,
	            'id_field_duong'=> self::$duong_slug,
	            'label_field_tinh'=> __("Select",'administrator-z'). " " .self::get_tinh_label(),
	            'label_field_huyen'=> __("Select",'administrator-z'). " " .self::get_huyen_label(),
	            'label_field_xa'=> __("Select",'administrator-z'). " " .self::get_xa_label(),
	            'label_field_duong'=> __("Select",'administrator-z'). " " .self::get_duong_label(),
            )
        );           
	}
	function import_js_frontend(){
		if(is_checkout()) return;
		wp_enqueue_script( 'acfthx', plugin_dir_url(ADMINZ_BASENAME) . 'helper/thx/frontend.js', ['jquery'] );
        wp_localize_script( 
            'acfthx', 
            'pa_vars', 
            array(
	            'pa_nonce' => wp_create_nonce( 'pa_nonce' ),
	            'ajax_url'=> admin_url( 'admin-ajax.php' ),
	            'name_field_tinh'=> self::get_tinh_name(),
	            'name_field_huyen'=> self::get_huyen_name(),
	            'name_field_xa'=> self::get_xa_name(),
	            'name_field_duong'=> self::get_duong_name(),
	            'label_field_tinh'=> __("Select",'administrator-z'). " " .self::get_tinh_label(),
	            'label_field_huyen'=> __("Select",'administrator-z'). " " .self::get_huyen_label(),
	            'label_field_xa'=> __("Select",'administrator-z'). " " .self::get_xa_label(),
	            'label_field_duong'=> __("Select",'administrator-z'). " " .self::get_duong_label(),
            )
        );
	}

}