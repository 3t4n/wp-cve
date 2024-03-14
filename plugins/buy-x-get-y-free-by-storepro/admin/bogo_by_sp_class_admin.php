<?php

class bogo_by_sp_admin {

	// The ID of this plugin.
	 
	private $plugin_name;

	// The version of this plugin.
	 
	private $version;

	// Initialize the class and set its properties.
	 
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		new admin_menu_bogo_by_sp($this->plugin_name, $this->version);
	}

	// Register the stylesheets for the admin area.
	 
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/spbogo.css', array(), $this->version, 'all' );

	}

	// Register the JavaScript for the admin area.
	 
	public function enqueue_scripts() {


	}

}
