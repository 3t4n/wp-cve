<?php
/**
 * EventPrime Autoloader Class.
 */
defined( 'ABSPATH' ) || exit;

class EventM_Autoloader {

    /**
     * Directory include path.
     * 
     * @var string
     */
    private $dir_path = '';

    /**
	 * Constructor.
	 */
	public function __construct() {
		if ( function_exists( '__autoload' ) ) {
			spl_autoload_register( '__autoload' );
		}

		spl_autoload_register( array( $this, 'autoload' ) );

		$this->dir_path = untrailingslashit( plugin_dir_path( EP_PLUGIN_FILE ) ) . '/includes/';
	}

    /**
	 * Take a class name and turn it into a file name.
	 *
	 * @param  string $class Class name.
	 * @return string
	 */
	private function get_file_name_from_class( $class ) {
        $class = str_replace( 'eventm', 'ep', $class );
		return 'class-' . str_replace( '_', '-', $class ) . '.php';
	}

	/**
	 * Include a class file.
	 *
	 * @param  string $path File path.
	 * @return bool Successful or not.
	 */
	private function load_file( $path ) {
		if ( $path && is_readable( $path ) ) {
			include_once $path;
			return true;
		}
		return false;
	}

    /**
	 * Auto-load WC classes on demand to reduce memory consumption.
	 *
	 * @param string $class Class name.
	 */
	public function autoload( $class ) {
		$class = strtolower( $class );

		if ( 0 !== strpos( $class, 'eventm_' ) ) {
			return;
		}

		$file = $this->get_file_name_from_class( $class );
		$path = '';
        
		if ( 0 === strpos( $class, 'eventm_performer_controller_' ) ) {
			$path = $this->dir_path . 'performers/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_admin_controller_' ) ) {
			$path = $this->dir_path . 'core/admin/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_admin_model_' ) ) {
			$path = $this->dir_path . 'core/admin/model/';
		} elseif ( 0 === strpos( $class, 'eventm_organizer_controller_' ) ) {
			$path = $this->dir_path . 'organizers/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_event_type_controller_' ) ) {
			$path = $this->dir_path . 'event_types/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_venue_controller_' ) ) {
			$path = $this->dir_path . 'venues/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_user_controller' ) ) {
			$path = $this->dir_path . 'users/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_event_controller' ) ) {
			$path = $this->dir_path . 'events/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_event_model' ) ) {
			$path = $this->dir_path . 'events/model/';
		} elseif ( 0 === strpos( $class, 'eventm_booking_controller' ) ) {
			$path = $this->dir_path . 'bookings/controllers/';
		} elseif ( 0 === strpos( $class, 'eventm_booking_model' ) ) {
			$path = $this->dir_path . 'bookings/model/';
		} elseif ( 0 === strpos( $class, 'eventm_report_controller' ) ) {
			$path = $this->dir_path . 'reports/controllers/';
		}

		if ( empty( $path ) || ! $this->load_file( $path . $file ) ) {
			$this->load_file( $this->dir_path . $file );
		}
	}

}

new EventM_Autoloader();