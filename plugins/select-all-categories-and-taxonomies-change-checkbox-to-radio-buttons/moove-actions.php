<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>
<?php
/**
 * Moove_Radioselect_Actions File Doc Comment
 *
 * @category  Moove_Radioselect_Actions
 * @package   moove-radio-select
 * @author    Moove Agency
 */

/**
 * Moove_Radioselect_Actions Class Doc Comment
 *
 * @category Class
 * @package  Moove_Radioselect_Actions
 * @author   Moove Agency
 */
class Moove_Radioselect_Actions {
	/**
	 * Global cariable used in localization
	 *
	 * @var array
	 */
	var $radioselect_loc_data;
	/**
	 * Construct
	 */
	function __construct() {
		$this->moove_register_scripts();
	}
	/**
	 * Register Front-end / Back-end scripts
	 *
	 * @return void
	 */
	function moove_register_scripts() {
		if ( is_admin() ) :
			add_action( 'admin_enqueue_scripts', array( &$this, 'moove_radioselect_admin_scripts' ) );
		endif;
	}

	/**
	 * Register global variables to head, AJAX, Form validation messages
	 *
	 * @param  string $ascript The registered script handle you are attaching the data for.
	 * @return void
	 */
	public function moove_localize_script( $ascript ) {
		$this->radioselect_loc_data = array();
		wp_localize_script( $ascript, 'moove_frontend_radioselect_scripts', $this->radioselect_loc_data );
	}

	/**
	 * Registe FRONT-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function moove_frontend_radioselect_scripts() {
		
	}
	/**
	 * Registe BACK-END Javascripts and Styles
	 *
	 * @return void
	 */
	public function moove_radioselect_admin_scripts() {
		wp_enqueue_script( 'moove_radioselect_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/js/moove_radioselect_backend.js', array( 'jquery' ), MOOVE_RADIOSELECT_VERSION, true );
		wp_enqueue_style( 'moove_radioselect_backend', plugins_url( basename( dirname( __FILE__ ) ) ) . '/assets/css/moove_radioselect_backend.css', '', MOOVE_RADIOSELECT_VERSION );
	}
}
$moove_radioselect_actions_provider = new Moove_Radioselect_Actions();

