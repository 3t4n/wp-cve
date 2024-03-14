<?php
namespace Enteraddons\CustomControl;
if( ! class_exists('Registered_controls') ) {
	class Registered_controls{

	    function __construct() {
	        add_action( 'elementor/controls/register', [ $this,'controls_registered']);
	    }

	    public function controls_registered(  $controls_manager ) {
	      //Include Control files
	      require_once plugin_dir_path( __FILE__ ).'/image-select.php';
	      // Register control
	      $controls_manager->register( new Image_Select() );
	    }
	}
	new Registered_controls();
}