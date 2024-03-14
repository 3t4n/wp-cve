<?php
namespace DarklupLite\CustomControl;
 /**
  * 
  * @package    DarklupLite - WP Dark Mode
  * @version    1.0.0
  * @author     
  * @Websites: 
  *
  */
if( ! defined( 'ABSPATH' ) ) {
    die( DARKLUPLITE_ALERT_MSG );
}

if( ! class_exists('Registered_controls') ) {
	class Registered_controls{

	    function __construct() {

	        add_action( 'elementor/controls/controls_registered', [ $this,'controls_registered']);
	    }

	    public function controls_registered() {

	      //Include Control files
	      require_once plugin_dir_path( __FILE__ ).'/image-select.php';

	      // Register control
	      \Elementor\Plugin::$instance->controls_manager->register_control( 'image-select', new Image_Select());

	    }
	}
	
	new Registered_controls();
}