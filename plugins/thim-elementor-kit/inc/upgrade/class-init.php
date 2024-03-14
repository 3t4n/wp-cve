<?php
namespace Thim_EL_Kit\Upgrade;

use Thim_EL_Kit\SingletonTrait;

class Init {

	use SingletonTrait;

	private static $background_updater;

	private static $db_updates = array(
		'1.1.0' => array(
			'update_to_110', // Function name in class DB_Updates
			'update_110_header_footer',
			'update_110_db_version',
		),
	);

	public function __construct() {
		add_action( 'init', array( $this, 'maybe_update_db_version' ), 5 );
	}

	public function maybe_update_db_version() {
		$current_db_version = get_option( 'thim_ekit_db_version' );

		if ( empty( $current_db_version ) || version_compare( $current_db_version, THIM_EKIT_VERSION, '<' ) ) {
			if ( $this->needs_db_update() ) {
				$this->update();
			} else {
				$this->update_db_version();
			}
		}
	}

	private function update() {
		if ( ! class_exists( '\Thim_EL_Kit\Upgrade\DB_Updates', false ) ) {
			include_once THIM_EKIT_PLUGIN_PATH . 'inc/upgrade/class-db-updates.php';
		}

		$current_db_version = get_option( 'thim_ekit_db_version' );

		foreach ( self::$db_updates as $version => $update_callbacks ) {
			if ( version_compare( $current_db_version, $version, '<' ) ) {
				foreach ( $update_callbacks as $update_callback ) {
					if ( method_exists( '\Thim_EL_Kit\Upgrade\DB_Updates', $update_callback ) ) {
						error_log( 'Thim Elementor Kit: Running ' . $update_callback );
						\Thim_EL_Kit\Upgrade\DB_Updates::instance()->{$update_callback}();
					}
				}
			}
		}
	}

	private function needs_db_update() {
		$current_db_version = get_option( 'thim_ekit_db_version', null );

		return is_null( $current_db_version ) || version_compare( $current_db_version, max( array_keys( self::$db_updates ) ), '<' );
	}

	public function update_db_version() {
		update_option( 'thim_ekit_db_version', THIM_EKIT_VERSION );
	}
}
Init::instance();
