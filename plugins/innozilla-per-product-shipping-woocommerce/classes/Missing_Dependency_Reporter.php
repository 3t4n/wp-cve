<?php

class IPPSW_Missing_Dependency_Reporter {

	const REQUIRED_CAPABILITY = 'activate_plugins';

	/** @var string[] */
	private $missing_plugin_names;

	/**
	 * @param string[] $missing_plugin_names
	 */
	public function __construct( $missing_plugin_names ) {
		$this->missing_plugin_names = $missing_plugin_names;
	}

	public function bind_to_admin_hooks() {
		add_action( 'admin_notices', array( $this, 'display_admin_notice' ) );
	}

	public function display_admin_notice() {
		if ( ! current_user_can( self::REQUIRED_CAPABILITY ) ) {
			// If the user does not have the "activate_plugins" capability, do nothing.
			return;
		}

		$missing_plugin_names = $this->missing_plugin_names;
		include dirname( __FILE__ ) . '/../views/missing-dependencies-admin-notice.php';
	}

}