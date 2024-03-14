<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class that handles admin notice registration and output
 *
 */
Class WPSBC_Admin_Notices {

	/**
	 * The current instance of the object
	 *
	 * @access private
	 * @var    WPSBC_Admin_Notices
	 *
	 */
	private static $instance;

	/**
	 * List of notices that have been registered
	 *
	 * @access protected
	 * @var    array
	 *
	 */
	protected $notices = array();

	/**
	 * List of notices that will be printed in the page
	 *
	 * @access protected
	 * @var    array
	 *
	 */
	protected $display_notices = array();


	/**
	 * Constructor
	 *
	 */
	public function __construct() {

		add_action( 'admin_init', array( $this, 'catch_url_admin_notice' ), 100 );
		add_action( 'admin_notices', array( $this, 'print_notices' ) );

	}


	/**
	 * Returns an instance of the object
	 *
	 * @return WPSBC_Admin_Notices
	 *
	 */
	public static function instance() {

		if( ! isset( self::$instance ) && ! ( self::$instance instanceof WPSBC_Admin_Notices ) )
			self::$instance = new WPSBC_Admin_Notices;

		return self::$instance;

	}


	/**
	 * Adds a new admin notice to the $notices property
	 *
	 * @param string $slug
	 * @param string $message
	 * @param string $class
	 *
	 */
	public function register_notice( $slug, $message, $class = 'updated' ) {

		$this->notices[$slug] = array(
			'message' => $message,
			'class'   => $class
		);

	}


	/**
	 * Prepares a registered admin notice for printing on admin_notices action
	 *
	 * @param string $slug
	 *
	 */
	public function display_notice( $slug ) {

		$this->display_notices[] = $slug;

	}


	/**
	 * Callback function to print admin notices
	 *
	 */
	public function print_notices() {

		if( ! isset( $this->display_notices ) )
            return;
        
        foreach( $this->display_notices as $notice_slug ) {

        	if( empty( $this->notices[$notice_slug] ) )
        		continue;

            echo '<div class="' . $this->notices[$notice_slug]['class'] . ' notice">';
                echo $this->notices[$notice_slug]['message'];
            echo '</div>';

        }

	}


	/**
     * Catches messages sent through the URL
     *
     */
    public function catch_url_admin_notice() {

        if( empty( $_GET['wpsbc_message'] ) )
            return;

        $message_slug = sanitize_text_field( $_GET['wpsbc_message'] );

        if( ! empty( $this->notices[$message_slug] ) )
            $this->display_notice( $message_slug );

    }


}


/**
 * Returns the instance of WPSBC_Admin_Notices
 *
 * @return WPSBC_Admin_Notices
 *
 */
function wpsbc_admin_notices() {

	return WPSBC_Admin_Notices::instance();

}

wpsbc_admin_notices();