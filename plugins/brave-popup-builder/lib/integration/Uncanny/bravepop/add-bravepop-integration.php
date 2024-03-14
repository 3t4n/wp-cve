<?php

use Uncanny_Automator\Recipe;

/**
 * Class Brave_Uncanny_Integration
 */
class ADD_BRAVEPOP_INTEGRATION {
	use Recipe\Integrations;

	/**
	 * Add_Integration constructor.
	 */
	public function __construct() {
      //error_log('Brave_Uncanny_Integration setup_integration');
		$this->setup();
	}
	protected function setup() {
		$this->set_integration( 'bravepop' );
		$this->set_name( 'Brave Conversion Engine' );
		$this->set_icon( 'integration-brave-icon-32.png' );
		$this->set_icon_path( __DIR__ . '/images/' );
		$this->set_plugin_file_path( dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'Uncanny.php' );
		$this->set_external_integration( true );
	}

	public function plugin_active() {
		return true;
	}
   
}