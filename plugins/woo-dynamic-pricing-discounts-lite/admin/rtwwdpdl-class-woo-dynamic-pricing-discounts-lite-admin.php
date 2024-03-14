<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       www.redefiningtheweb.com
 * @since      1.0.0
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/admin
 * @author     RedefiningTheWeb <developer@redefiningtheweb.com>
 */
class Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rtwwdpdl_plugin_name    The ID of this plugin.
	 */
	private $rtwwdpdl_plugin_name;

	public $rtwwdpdl_set_rules;
	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $rtwwdpdl_version    The current version of this plugin.
	 */
	private $rtwwdpdl_version;

	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $rtwwdpdl_plugin_name       The name of this plugin.
	 * @param      string    $rtwwdpdl_version    The version of this plugin.
	 */
	public function __construct( $rtwwdpdl_plugin_name, $rtwwdpdl_version ) {
	
		$this->rtwwdpdl_plugin_name = $rtwwdpdl_plugin_name;
		$this->rtwwdpdl_version = $rtwwdpdl_version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the rtwwdpdl_run() function
		 * defined in Woo_Dynamic_Pricing_Discounts_With_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Dynamic_Pricing_Discounts_With_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if(get_current_screen()->id == 'users' || get_current_screen()->id == 'edit-product' || get_current_screen()->id == 'product' || get_current_screen()->id == 'woocommerce_page_rtwwdpdl')
		{
			wp_enqueue_style( "bootstrap", RTWWDPDL_URL. 'assets/BootstrapDataTable/css/bootstrap.css', array(), $this->rtwwdpdl_version, 'all' );
			// data table bootstrap css 
			wp_enqueue_style( "datatable-bootstrap", RTWWDPDL_URL. 'assets/BootstrapDataTable/css/dataTables.bootstrap4.min.css', array(), $this->rtwwdpdl_version, 'all' );
			// responsive bootstrap4 css
			wp_enqueue_style( "responsive-bootstrap4", RTWWDPDL_URL. 'assets/BootstrapDataTable/css/responsive.bootstrap4.min.css', array(), $this->rtwwdpdl_version, 'all' );
			
			wp_enqueue_style( "select2", plugins_url( 'woocommerce/assets/css/select2.css' ), array(), $this->rtwwdpdl_version, 'all' );
			wp_enqueue_style( $this->rtwwdpdl_plugin_name, plugin_dir_url( __FILE__ ) . 'css/rtwwdpdl-woo-dynamic-pricing-discounts-lite-admin.css', array(), $this->rtwwdpdl_version, 'all' );
			wp_enqueue_style( 'woocommerce_admin_styles', plugins_url( 'woocommerce/assets/css/admin.css' ), array(), $this->rtwwdpdl_version, 'all' );

		}

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the rtwwdpdl_run() function
		 * defined in Woo_Dynamic_Pricing_Discounts_With_Ai_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Dynamic_Pricing_Discounts_With_Ai_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		if(get_current_screen()->id == 'users' || get_current_screen()->id == 'edit-product' || get_current_screen()->id == 'product' || get_current_screen()->id == 'woocommerce_page_rtwwdpdl')
		{
			wp_enqueue_script( 'selectWoo', plugins_url( 'woocommerce/assets/js/selectWoo/selectWoo.full.min.js' ), array( 'jquery' ), $this->rtwwdpdl_version, false );
			wp_enqueue_script( 'tipTip', plugins_url( 'woocommerce/assets/js/jquery-tiptip/jquery.tipTip.min.js' ), array( 'jquery' ), $this->rtwwdpdl_version, false );

			wp_enqueue_script( 'wc-enhanced-select', plugins_url( 'woocommerce/assets/js/admin/wc-enhanced-select.min.js' ), array( 'jquery', 'selectWoo' ), $this->rtwwdpdl_version, false );
			
			wp_enqueue_script( "datatable", RTWWDPDL_URL. 'assets/Datatables/js/jquery.dataTables.min.js', array( 'jquery' ), $this->rtwwdpdl_version, false );
			wp_enqueue_script( "datatable-responsive", RTWWDPDL_URL. 'assets/Responsive_DT/js/dataTables.responsive.min.js', array( 'jquery' ), $this->rtwwdpdl_version, false );
			// responsive-bootstrap4-js
			wp_enqueue_script( "responsive-bootstrap4", RTWWDPDL_URL. 'assets/BootstrapDataTable/js/responsive.bootstrap4.min.js', array( 'jquery' ), $this->rtwwdpdl_version, false );
			// dataTables-bootstrap4-js
			wp_enqueue_script( "dataTables-bootstrap4", RTWWDPDL_URL. 'assets/BootstrapDataTable/js/dataTables.bootstrap4.min.js', array( 'jquery' ), $this->rtwwdpdl_version, false );
			wp_enqueue_script( "select2", plugins_url( 'woocommerce/assets/js/select2/select2.full.min.js' ), array( 'jquery' ), $this->rtwwdpdl_version, false );
			wp_enqueue_script('jquery-ui-sortable');
			wp_enqueue_script('jquery-ui-datepicker');
			wp_enqueue_script( 'wc-enhanced-select' );
			wp_register_script( $this->rtwwdpdl_plugin_name, plugin_dir_url( __FILE__ ) . 'js/rtwwdpdl-woo-dynamic-pricing-discounts-lite-admin.js', array( 'jquery', 'jquery-ui-datepicker', 'jquery-ui-sortable', 'wc-enhanced-select' ), $this->rtwwdpdl_version, false );
			wp_enqueue_script( 'woocommerce_admin' );

			$rtwwdpdl_ajax_nonce = wp_create_nonce( "rtwwdpdl-ajax-seurity" );
			wp_localize_script($this->rtwwdpdl_plugin_name, 'rtwwdpdl_ajax', array( 'ajax_url' => esc_url(admin_url('admin-ajax.php')),
				'rtwwdpdl_nonce' => $rtwwdpdl_ajax_nonce));
			wp_enqueue_script( $this->rtwwdpdl_plugin_name );
		}

	}

	/**
	 * Function to add submenu in woocommerce menu tab.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_add_submenu()
	{
		add_submenu_page( 'woocommerce', esc_attr__( 'Dynamic Pricing & Discounts Lite', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ), esc_html__( 'Dynamic Pricing & Discounts Lite', 'rtwwdpdl-woo-dynamic-pricing-discounts-lite' ), 'manage_woocommerce', 'rtwwdpdl', array( $this, 'rtwwdpdl_admin_setting' ) );
	}

	/**
	 * Function for display settings page.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_admin_setting()
	{
		include_once( RTWWDPDL_DIR.'admin/partials/rtwwdpdl-woo-dynamic-pricing-discounts-lite-admin-display.php');
	}

	/**
	 * Function to short products by price.
	 *
	 * @since    1.0.0
	 */
	public static function rtw_sort_by_price( $cart_item_a, $cart_item_b ) {
		return $cart_item_a['data']->get_price('edit') <=> $cart_item_b['data']->get_price('edit');
	}
	
	/**
	 * Function to update customer visit.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_update_customer_visit($rtwwdpdl_user_login, $rtwwdpdl_user) {
		$rtwwdpdl_user_id = $rtwwdpdl_user->ID;
		$rtwwdpdl_meta_key = 'rtwwdpdl_user_visit_count';
		$rtwwdpdl_meta_value = 2;
		$rtwwdpdl_array = get_user_meta($rtwwdpdl_user_id, $key = '', $single = false);
		$rtwwdpdl_meta_value = $rtwwdpdl_array['rtwwdpdl_user_visit_count'][0];
		$rtwwdpdl_meta_value++;
		update_user_meta($rtwwdpdl_user_id, $rtwwdpdl_meta_key, $rtwwdpdl_meta_value);
	}

	/**
	 * Function to add extra cloumn in user list table.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_new_colmn_user($rtwwdpdl_columns)
	{
		$rtwwdpdl_columns['rtw_plus_mem'] = 'Plus Member';
		return $rtwwdpdl_columns;
	}

	/**
	 * Function to add extra cloumn in user list page.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_new_column_val( $rtwwdpdl_column )
	{
		$rtwwdpdl_column['rtw_plus_mem'] = 'Plus Member';
		return $rtwwdpdl_column;
	}

	/**
	 * Function to check if a customer is plus member.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_user_data( $val, $rtwwdpdl_column_name, $rtwwdpdl_user_id )
	{
		$rtwwdpdl_user_meta = get_user_meta($rtwwdpdl_user_id, 'rtwwdpdl_plus_member');
		$rtwwdpdl_prev_opt = get_option('rtwwdpdl_add_member');
		$rtwwdpdl_user_data = get_userdata( $rtwwdpdl_user_id );
		$rtwwdpdl_today_date = current_time('Y-m-d');
		$rtwwdpdl_registered_date = $rtwwdpdl_user_data->user_registered;
		$rtwwdpdl_user = wp_get_current_user();

		if($rtwwdpdl_user_meta)
		{
			switch ($rtwwdpdl_column_name) {
				case 'rtw_plus_mem' :
				if($rtwwdpdl_user_meta[0]['check'] == 'checked')
				{
					return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" checked="checked" name="rtw_plus_mem" />';
				}
				else{
					if(is_array($rtwwdpdl_prev_opt) && !empty($rtwwdpdl_prev_opt))
					{
						foreach ($rtwwdpdl_prev_opt as $key => $value)
						{
							$rtwwdpdl_no_oforders = wc_get_customer_order_count( $rtwwdpdl_user_id);
							$rtwwdpdl_args = array(
								'customer_id' => $rtwwdpdl_user_id,
								'post_status' => 'cancelled',
								'post_type' => 'shop_order',
								'return' => 'ids',
							);
							$rtwwdpdl_numordr_cancld = 0;
							$rtwwdpdl_numordr_cancld = count( wc_get_orders( $rtwwdpdl_args ) );
							$rtwwdpdl_no_oforders = $rtwwdpdl_no_oforders - $rtwwdpdl_numordr_cancld;
							$rtwwdpdl_ordrtotal = wc_get_customer_total_spent($rtwwdpdl_user_id);
							$rtwwdpdl_user_role = $value['rtwwdpdl_roles'] ;
							if(is_array($rtwwdpdl_user_role) && !empty($rtwwdpdl_user_role))
							{
								$rtwwdpdl_role_matched = false;
								foreach ($rtwwdpdl_user_role as $rol => $role) {
									if($role == 'all'){
										$rtwwdpdl_role_matched = true;
									}
									if (in_array( $role, (array) $rtwwdpdl_user->roles ) ) {
										$rtwwdpdl_role_matched = true;
									}
								}
								if($rtwwdpdl_role_matched == false)
								{
									return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
								}
							}

							if(isset($value['rtwwdpdl_min_orders']) && $value['rtwwdpdl_min_orders'] > $rtwwdpdl_no_oforders)
							{
								return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
							}
							if(isset($value['rtwwdpdl_purchase_amt']) && $value['rtwwdpdl_purchase_amt'] > $rtwwdpdl_ordrtotal)
							{
								return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
							}
							if(isset($value['rtwwdpdl_purchase_prodt']) && $value['rtwwdpdl_purchase_prodt'])
							{
								
							}
							if(isset($value['rtw_user_regis_for']))
							{
								$rtwtremnthbfre = date("d.m.Y", strtotime("-3 Months"));
								$rtwsixmnthbfre = date("d.m.Y", strtotime("-6 Months"));
								$rtwoneyrbfre = date("d.m.Y", strtotime("-1 Year"));

								if($value['rtw_user_regis_for'] == 'less3mnth')
								{
									if($rtwwdpdl_registered_date < $rtwtremnthbfre)
									{
										return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
									}
								}
								elseif($value['rtw_user_regis_for'] == 'more3mnth')
								{
									if($rtwwdpdl_registered_date > $rtwtremnthbfre)
									{
										return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
									}
								}
								elseif($value['rtw_user_regis_for'] == 'more6mnth')
								{
									if($rtwwdpdl_registered_date > $rtwsixmnthbfre)
									{
										return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
									}
								}
								elseif ($value['rtw_user_regis_for'] == 'more1yr') 
								{
									if($rtwwdpdl_registered_date > $rtwoneyrbfre)
									{
										return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
									}
								}
							}
							return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" checked="checked" type="checkbox" name="rtw_plus_mem" />';
						}
					}
				}
				default:
			}
		}
		else{
			switch ($rtwwdpdl_column_name) {
				case 'rtw_plus_mem' :
				return '<input class="rtw_plus_mem" value="'.$rtwwdpdl_user_id.'" type="checkbox" name="rtw_plus_mem" />';
				default:
			}
		}
		return $val;
	}

	/**
	 * Function to update a customer is plus member.
	 *
	 * @since    1.0.0
	 */
	function rtwwdpdl_plus_member_callback()
	{
		if ( !wp_verify_nonce( $_POST['security_check'], 'rtwwdpdl-ajax-seurity' ) ){
			return;
		}
		$rtwwdpdl_user_id = sanitize_text_field( $_POST['user_id'] );
		$rtwwdpdl_checked = sanitize_text_field( $_POST['checked'] );
		$rtwwdpdl_meta_val = array( 'check'=> $rtwwdpdl_checked);
		update_user_meta( $rtwwdpdl_user_id, 'rtwwdpdl_plus_member', $rtwwdpdl_meta_val );
		$rtwwdpdl_response = 'success';
		echo json_encode( $rtwwdpdl_response );
		die();
	}

	/**
	 * Function to update discount tables ordering.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_category_tbl_callback()
	{
		$rtwwdpdl_tbl_nam = sanitize_text_field( $_POST['table']) ;
		if ( !wp_verify_nonce( $_POST['security_check'], 'rtwwdpdl-ajax-seurity' ) ){
			return;
		}
		
		if( $rtwwdpdl_tbl_nam == 'category_tbl' )
		{
			$rtwwdpdl_products_option = get_option( 'rtwwdpdl_single_cat_rule' );
			$rtwwdpdl_updated_array = array();
			foreach ( $_POST['rtwarray'] as $key => $value ) {
				$rtwwdpdl_updated_array[ sanitize_text_field( $key ) ] = $rtwwdpdl_products_option[ sanitize_text_field( $value ) ];
			}

			update_option('rtwwdpdl_single_cat_rule', $rtwwdpdl_updated_array);
		}
		elseif( $rtwwdpdl_tbl_nam == 'prodct_tbl' )
		{	
			$rtwwdpdl_products_option = get_option('rtwwdpdl_single_prod_rule');
			$rtwwdpdl_updated_array = array();
			foreach ( $_POST['rtwarray'] as $key => $value ) {
				$rtwwdpdl_updated_array[ sanitize_text_field( $key ) ] = $rtwwdpdl_products_option[ sanitize_text_field( $value )];
			}

			update_option('rtwwdpdl_single_prod_rule', $rtwwdpdl_updated_array);
		}
		elseif($rtwwdpdl_tbl_nam == 'tier_pro_tbl')
		{
			$rtwwdpdl_products_option = get_option('rtwwdpdl_tiered_rule');
			$rtwwdpdl_updated_array = array();
			foreach ($_POST['rtwarray'] as $key => $value) {
				$rtwwdpdl_updated_array[sanitize_text_field($key)] = $rtwwdpdl_products_option[sanitize_text_field($value)];
			}

			update_option('rtwwdpdl_tiered_rule', $rtwwdpdl_updated_array);
		}
		elseif($rtwwdpdl_tbl_nam == 'pay_tbl')
		{
			$rtwwdpdl_products_option = get_option('rtwwdpdl_pay_method');
			$rtwwdpdl_updated_array = array();
			foreach ($_POST['rtwarray'] as $key => $value) {
				$rtwwdpdl_updated_array[sanitize_text_field($key)] = $rtwwdpdl_products_option[sanitize_text_field($value)];
			}

			update_option('rtwwdpdl_pay_method', $rtwwdpdl_updated_array);
		}
		elseif($rtwwdpdl_tbl_nam == 'cart_tbl')
		{
			$rtwwdpdl_products_option = get_option('rtwwdpdl_cart_rule');
			$rtwwdpdl_updated_array = array();
			foreach ($_POST['rtwarray'] as $key => $value) {
				$rtwwdpdl_updated_array[sanitize_text_field($key)] = $rtwwdpdl_products_option[sanitize_text_field($value)];
			}

			update_option('rtwwdpdl_cart_rule', $rtwwdpdl_updated_array);
		}
		elseif($rtwwdpdl_tbl_nam == 'bogo_tbl')
		{
			$rtwwdpdl_products_option = get_option('rtwwdpdl_bogo_rule');
			$rtwwdpdl_updated_array = array();
			foreach ($_POST['rtwarray'] as $key => $value) {
				$rtwwdpdl_updated_array[sanitize_text_field($key)] = $rtwwdpdl_products_option[sanitize_text_field($value)];
			}

			update_option('rtwwdpdl_bogo_rule', $rtwwdpdl_updated_array);
		}
		die;
	}
}
