<?php

use Uncanny_Automator\Recipe;

/**
 * Class Add_Better_Messages_Integration
 */
class Add_Better_Messages_Integration {
	use Recipe\Integrations;

	/**
	 * Add_Integration constructor.
	 */
	public function __construct() {
		$this->setup();
	}

	/**
	 *
	 */
	protected function setup() {
		$this->set_integration( 'BETTER_MESSAGES' );
		$this->set_name( 'Better Messages' );
		$this->set_icon( 'icon.png' );
		$this->set_icon_path( __DIR__ . '/img/' );
		$this->set_plugin_file_path( dirname( __DIR__ ) . DIRECTORY_SEPARATOR . 'uncanny-automator-better-messages.php' );
		$this->set_external_integration( true );
	}

	/**
	 * Explicitly return true because this plugin code will only run if it's active.
	 * Add your own plugin active logic here, for example, check for a specific function exists before integration is
	 * returned as active.
	 *
	 * This is an option override. By default Uncanny Automator will check $this->get_plugin_file_path() to validate
	 * if plugin is active.
	 *
	 * @return bool
	 */
	public function plugin_active() {
		return true;
	}
}
