<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class Xoo_Wl_Admin_Settings{

	protected static $_instance = null;

	public $capability;

	public static function get_instance(){
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct(){
		$this->capability = isset( xoo_wl_helper()->admin->capability ) ? xoo_wl_helper()->admin->capability : 'administrator';
		$this->hooks();	
	}

	public function hooks(){

		if( current_user_can( $this->capability ) ){
			add_action( 'init', array( $this, 'generate_settings' ), 0 );
			add_action( 'admin_menu', array( $this, 'add_menu_pages' ) );
		}

		add_filter( 'plugin_action_links_' . XOO_WL_PLUGIN_BASENAME, array( $this, 'plugin_action_links' ) );

		add_action( 'woocommerce_product_options_inventory_product_data', array( $this, 'wc_edit_product_custom_fields' ) );
		add_action( 'woocommerce_process_product_meta', array( $this, 'wc_edit_product_save_custom_fields' ) );

		add_action( 'admin_init', array( $this, 'preview_email' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		add_action( 'xoo_tab_page_end', array( $this, 'display_shortcodes_list' ), 10, 2 );

		add_action( 'xoo_tab_page_start', array( $this, 'display_preview_template_form' ), 10, 2 );
		add_action( 'xoo_tab_page_end', array( $this, 'display_preview_template_form' ), 10, 2 );

		add_filter( 'xoo_aff_add_fields', array( $this,'add_new_fields' ), 10, 2 );
		add_action( 'xoo_aff_field_selector', array( $this, 'customFields_addon_notice' ) );

		add_action( 'wp_loaded', array( $this, 'register_pro_tab' ), 20 );
		add_action('xoo_tab_page_start', array( $this, 'pro_html' ), 10, 2 );

	}


	public function register_pro_tab(){
		xoo_wl_helper()->admin->register_tab( 'Premium', 'pro' );
	}

	public function pro_html( $tab_id, $tab_data ){
		if( xoo_wl_helper()->admin->is_settings_page() && $tab_id === 'pro' ){
			xoo_wl_helper()->get_template( '/admin/views/settings/pro.php', array(), XOO_WL_PATH );
		}
	}



	public function customFields_addon_notice( $aff ){
		if( $aff->plugin_slug !== 'waitlist-woocommerce' ) return;
		?>
		<a class="xoo-wl-field-addon-notice" href="https://xootix.com/waitlist-for-woocommerce"><span class="dashicons dashicons-admin-links"></span> Adding custom fields is a pro feature.</a>
		<?php
	}


	public function add_new_fields( $allow, $aff ){
		if( $aff->plugin_slug === 'waitlist-woocommerce' ) return false;
		return $allow;
	}
	

	public function display_preview_template_form( $tab_id, $tab_data ){
		if( $tab_id === 'email' || $tab_id === 'email-style' ){
			$this->get_preview_template_form();
		}
		
	}

	public function display_shortcodes_list( $tab_id, $tab_data ){
		if( $tab_id !== 'email' ) return;
		include XOO_WL_PATH.'/admin/templates/xoo-wl-shortcodes-list.php';
	}

	public function generate_settings(){
		xoo_wl_helper()->admin->auto_generate_settings();
	}



	public function add_menu_pages(){

		$args = array(
			'menu_title' 	=> 'WC Waitlist',
			'icon' 			=> 'dashicons-editor-ul',
			'has_submenu' 	=> true
		);

		xoo_wl_helper()->admin->register_menu_page( $args );

		add_submenu_page(
			'waitlist-woocommerce-settings',
			'Fields',
			'Fields',
    		$this->capability,
    		'xoo-wl-fields',
    		array( $this, 'admin_fields_page' )
    	);


    	add_submenu_page(
			'waitlist-woocommerce-settings',
			'Users',
			'Users',
    		$this->capability,
    		'xoo-wl-view-waitlist',
    		array( $this, 'view_waitlist_page' )
    	);


    	add_submenu_page(
			'waitlist-woocommerce-settings',
			'Email Log',
			'Email Log',
    		$this->capability,
    		'xoo-wl-email-history',
    		array( $this, 'view_email_history_page' )
    	);

	}



	/**
	 * Show action links on the plugin screen.
	 *
	 * @param	mixed $links Plugin Action links
	 * @return	array
	 */
	public function plugin_action_links( $links ) {
		$action_links = array(
			'settings' 	=> '<a href="' . admin_url( 'admin.php?page=waitlist-woocommerce-settings' ) . '">Settings</a>',
			'support' 	=> '<a href="https://xootix.com/contact" target="__blank">Support</a>',
		);

		return array_merge( $action_links, $links );
	}



	public function wc_edit_product_custom_fields(){

		$waitlist_disable 	= get_post_meta( get_the_ID(), '_xoo_waitlist_disable', true );

    	woocommerce_wp_checkbox(
			array(
				'id'          	=> '_xoo_waitlist_disable',
				'label'       	=> __( 'Do not show waitlist button for this product', 'waitlist-woocommerce' ),
				'cbvalue' 		=> 'yes',
				'value' 		=> $waitlist_disable
			)
		);

	}

	public function wc_edit_product_save_custom_fields( $post_id ){
		update_post_meta( $post_id, '_xoo_waitlist_disable', isset( $_POST['_xoo_waitlist_disable'] ) ? 'yes' : 'no' );
	}



	public function preview_email(){
		if( isset( $_GET['page'] ) && $_GET['page'] === 'waitlist-woocommerce-settings' && isset( $_GET['preview'] ) ){
			$rows = xoo_wl_db()->get_waitlist_rows( array(
				'limit' => 1
			) );
			if( empty( $rows ) ){
				wp_die( __( 'Add at least one user to your waitlist to preview email', 'waitlist-woocommerce' ) );
			}


			switch ( $_GET['type'])  {
				case 'backInStock':
					$call = 'backInStock';
					break;

				case 'userNotify':
					$call = 'userNotify';
					break;

				case 'adminNotify':
					$call = 'adminNotify';
					break;
				
				default:
					$call = 'backInStock';
					break;
			}

			echo xoo_wl_emails()->$call->preview_email_template( $rows[0]->xoo_wl_id );

			die();
		}
	}


	public function enqueue_scripts($hook) {


		wp_enqueue_style( 'xoo-wl-admin-style', XOO_WL_URL . '/admin/assets/css/xoo-wl-admin-style.css', array(), XOO_WL_VERSION, 'all' );

		//Enqueue Styles only on plugin settings page
		if( xoo_wl_helper()->admin->is_settings_page() ){
		
			wp_enqueue_script( 'xoo-wl-admin-js', XOO_WL_URL . '/admin/assets/js/xoo-wl-admin-js.js', array( 'jquery' ), XOO_WL_VERSION, false );

			wp_localize_script('xoo-wl-admin-js','xoo_wl_admin_localize',array(
				'adminurl'  => admin_url().'admin-ajax.php',
			));


		}


		if( $hook === 'wc-waitlist_page_xoo-wl-view-waitlist' || $hook === 'wc-waitlist_page_xoo-wl-email-history' ){

			wp_enqueue_style( 'dataTables-css', XOO_WL_URL.'/admin/assets/css/datatables.css' );

			wp_enqueue_script( 'dataTables-js', XOO_WL_URL.'/admin/assets/js/datatables.js', array( 'jquery') );

			wp_enqueue_script( 'xoo-wl-admin-table-js', XOO_WL_URL . '/admin/assets/js/xoo-wl-admin-table-js.js', array( 'jquery'), XOO_WL_VERSION, false );

			wp_localize_script('xoo-wl-admin-table-js','xoo_wl_admin_table_localize',array(
				'adminurl'  => admin_url().'admin-ajax.php',
				'strings' 	=> array(
					'sending' 		=> __( 'Sending...', 'waitlist-woocommerce' ),
					'sent' 			=> __( 'Email sent successfully', 'waitlist-woocommerce' ),
					'deleting'		=> __( 'Deleting...', 'waitlist-woocommerce' ),
					'deleted' 		=> __( 'Deleted successfully', 'waitlist-woocommerce' ),
					'processing' 	=> __( 'Processing...', 'waitlist-woocommerce' ),
				)
			));
		}

	}



	public function admin_fields_page(){
		xoo_wl()->aff->admin->display_layout();
	}


	public function view_waitlist_page(){

		$args = array();
		$args['fieldsData'] = xoo_wl()->aff->fields->get_fields_data();

		$export_fields = (array) include XOO_WL_PATH.'/admin/views/export-fields.php';
		
		if( isset( $_GET['product'] ) && $_GET['product'] ){

			$product_id = (int) $_GET['product'];

			$args['count'] 			= xoo_wl_db()->get_waitlisted_count( $product_id );
			$args['rows'] 			= xoo_wl_db()->get_waitlist_rows_by_product( $product_id );
			$args['product_id'] 	= $product_id;
			$args['export_fields'] 	= $export_fields['users_table'];

			xoo_wl_helper()->get_template( "xoo-wl-table-product-users.php", $args, XOO_WL_PATH.'/admin/templates/' );
		}
		else{

			$args['count'] 			= xoo_wl_db()->get_waitlisted_count();
			$args['rows'] 			= xoo_wl_db()->get_products_waitlist();
			$args['export_fields'] 	= $export_fields['products_table'];

			xoo_wl_helper()->get_template( "xoo-wl-table-products-list.php", $args, XOO_WL_PATH.'/admin/templates/' );
		}
		
		
	}



	public function view_email_history_page(){
		$crons = xoo_wl_core()->get_email_cron_history();
		$args = array(
			'crons' => $crons,
		);
		xoo_wl_helper()->get_template( "xoo-wl-table-email-history.php", $args, XOO_WL_PATH.'/admin/templates/' );
	}


	public function get_preview_template_form(){
		$link = '<a target="__blank" href="admin.php?page=waitlist-woocommerce-settings&preview=true&type=%1$s">%2$s</a>';
		?>
		<div class="xoo-wl-pv-email-cont">
			<span>Preview Email</span>
			<div class="xoo-pv-email-links">
				<?php printf( $link, 'backInStock', 'Back in Stock' ); ?>
			</div>
		</div>
		<?php
		echo ob_get_clean();
	}


}

function xoo_wl_admin_settings(){
	return Xoo_Wl_Admin_Settings::get_instance();
}

xoo_wl_admin_settings();

?>