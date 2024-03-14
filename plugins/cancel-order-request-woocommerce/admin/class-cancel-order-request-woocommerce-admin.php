<?php


class Cancel_Order_Request_Woocommerce_Admin {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		new pisol_corw_menu( $this->plugin_name, $this->version );

		add_action('admin_init', array($this,'plugin_redirect'));
	}

	function plugin_redirect(){
		if (get_option('pi_cord_do_activation_redirect', false)) {
			delete_option('pi_cord_do_activation_redirect');
			if(!isset($_GET['activate-multi']))
			{
				wp_redirect("admin.php?page=pisol-cancel-order-request");
			}
		}
	}

	
	public function enqueue_styles() {


		if(filter_input(INPUT_GET, 'page') !== 'pisol-cancel-order-request') return;

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cancel-order-request-woocommerce-admin.css', array(), $this->version, 'all' );

		wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );

		wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');
	}

	
	public function enqueue_scripts() {


		if(filter_input(INPUT_GET, 'page') !== 'pisol-cancel-order-request') return;

		wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'selectWoo' );
		
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cancel-order-request-woocommerce-admin.js', array( 'jquery', 'selectWoo' ), $this->version, false );

		
        

	}

}
