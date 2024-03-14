<?php

class Pi_Edd_Admin {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		$this->register_menu();	
		
		add_action('admin_init', array($this,'plugin_redirect'));
	}

	function plugin_redirect(){
		if (get_option('pi_edd_do_activation_redirect', false)) {
			delete_option('pi_edd_do_activation_redirect');
			if(!isset($_GET['activate-multi']))
			{
				wp_redirect("admin.php?page=pi-edd");
			}
		}
	}
	
	public function register_menu(){
		$obj =	new Pi_Edd_Menu($this->plugin_name, $this->version);	
	}

	
	
	public function enqueue_styles() {

		wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');


	}

	
	public function enqueue_scripts() {


		wp_enqueue_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ), '1.0.4' );

	}

}
