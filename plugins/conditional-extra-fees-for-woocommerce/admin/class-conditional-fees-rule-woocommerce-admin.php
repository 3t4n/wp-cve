<?php
class Conditional_fees_Rule_Woocommerce_Admin {

	
	private $plugin_name;

	
	private $version;

	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		new Pi_cefw_Menu($this->plugin_name, $this->version);
	}

	
	public function enqueue_styles() {

		

		

	}

	
	public function enqueue_scripts() {


		

	}

}
