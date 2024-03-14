<?php
/**
 * WC_FreePay_Install class
 */

class WC_FreePay_Install 
{
	private static $updates = [
		//'1.1' => 'updates/woo-freepay-update-1.1.php'
	];

	/**
	 * Updates the version. 
	 * 
	 * @param string $version = null - The version number to update to
	 */
	private static function update_db_version( $version = null )
	{
		delete_option( 'woo_freepay_version' );
		add_option( 'woo_freepay_version', $version === null ? WCFP_VERSION : $version );
	}

	/**
	 * Get the current DB version stored in the database.
	 * 
	 * @return string - the stored version number.
	 */
	public static function get_db_version() 
	{
		return get_option( 'woo_freepay_version', true );	
	}

	/**
	 * Checks if this is the first install.
	 * 
	 * @return bool
	 */
	public static function is_first_install() 
	{
		$settings = get_option( 'woo_freepay_settings', false );	
		return $settings === false;
	}
	
	
	/**
	 * Runs on first install
	 */
	public static function install()
	{
		delete_option( 'woo_freepay_version' );
		add_option( 'woo_freepay_version', WCFP_VERSION );
	}

	/**
	 * Loops through the updates and executes them.
	 */
	public static function update() 
	{
        // Don't lock up other requests while processing
	    session_write_close();

        self::start_maintenance_mode();

		foreach ( self::$updates as $version => $updater ) {
			if ( self::is_update_required($version) ) {
				include( $updater );
				self::update_db_version( $version );
			}
		}
		
		self::update_db_version( WCFP_VERSION );

        self::stop_maintenance_mode();
	}

    /**
     * Checks if the current database version is outdated
     *
     * @param null $version
     * @return mixed
     */
	public static function is_update_required( $version = null )
    {
        if ( $version === null ) {
			$version = self::get_db_version();
		}

        foreach( self::$updates as $update_version => $update_file ) {
            if (version_compare($version, $update_version, '<')) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks if in maintenance mode
     * @return bool
     */
    public static function is_in_maintenance_mode()
    {
        return get_option( 'woo_freepay_maintenance', false );
    }

    /**
     * Enables maintenance mode
     */
    public static function start_maintenance_mode()
    {
        add_option('woo_freepay_maintenance', true, '', 'yes');
    }

    /**
     * Disables maintenance mode
     */
    public static function stop_maintenance_mode()
    {
        delete_option('woo_freepay_maintenance');
    }

    /**
     * Shows an admin notice informing about required database migrations.
     */
    public static function show_update_warning()
    {
        if (self::is_update_required()) {
            if (!self::is_in_maintenance_mode()) {
                WC_FreePay_Views::get_view('html-notice-update.php');
            } else {
                WC_FreePay_Views::get_view('html-notice-upgrading.php');
            }
        }
    }

    /**
     * Asynchronous data upgrader acction
     */
    public static function ajax_run_upgrader()
    {
        $nonce = isset($_POST['nonce']) ? $_POST['nonce'] : null;

        if (!wp_verify_nonce($nonce, 'woo-freepay-run-upgrader-nonce') && !current_user_can('administrator'))
        {
            echo json_encode( [ 'status' => 'error', 'message' => __('You are not authorized to perform this action', 'freepay-for-woocommerce') ] );
            exit;
        }

        self::update();

        echo json_encode( [ 'status' => 'success' ] );

        exit;
    }

    /**
     * Creates a nonce
     * @return string - the nonce
     */
    public static function create_run_upgrader_nonce()
    {
        return wp_create_nonce("woo-freepay-run-upgrader-nonce");
    }
}