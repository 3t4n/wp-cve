<?php

/**
 * CiviCRM Caldera Forms Assets Class
 *
 * @since 0.4.4
 */
class CiviCRM_Caldera_Forms_Assets {

	/**
	 * Plugin reference.
	 *
	 * @since 0.4.4
	 */
	public $plugin;

	/**
	* Initialises this object.
	*
	* @since 0.4.4
	*/
	public function __construct( $plugin ) {
		$this->plugin = $plugin;
		$this->register_hooks();
	}

	/**
	* Register hooks.
	*
	* @since 0.4.4
	*/
	public function register_hooks() {
		// register scripts and styles
		add_action( 'wp_loaded', [ $this, 'register_scripts_and_styles' ] );
		// enqueue scripts and js in form editor
		add_action( 'caldera_forms_admin_assets_scripts_registered', [ $this, 'enqueue_civicrm_scripts' ] );
		add_action( 'caldera_forms_admin_assets_styles_registered', [ $this, 'enqueue_civicrm_styles' ] );
		// enqueue late in editor
		add_action( 'caldera_forms_editor_footer', [ $this, 'enqueue_in_editor_footer' ] );
	}

	/**
	 * Register scripts and styles.
	 * 
	 * @since 0.4.4
	 */
	public function register_scripts_and_styles() {
		// select2
		wp_register_script( 'cfc-select2', CF_CIVICRM_INTEGRATION_URL . 'assets/js/select2.js', [ 'jquery' ], CF_CIVICRM_INTEGRATION_VER );
		wp_register_style( 'cfc-select2', CF_CIVICRM_INTEGRATION_URL . 'assets/css/select2.min.css', [], CF_CIVICRM_INTEGRATION_VER );
		// admin script
		wp_register_script( 'cfc-admin', CF_CIVICRM_INTEGRATION_URL . 'assets/js/admin.js', [ 'jquery' ], CF_CIVICRM_INTEGRATION_VER );
		// auto populate price field conditional options 
		wp_register_script( 'cfc-autopop-conditionals', CF_CIVICRM_INTEGRATION_URL . 'assets/js/autopop_conditionals.js', [ 'jquery' ], CF_CIVICRM_INTEGRATION_VER );
		// frontend script
		wp_register_script( 'cfc-front', CF_CIVICRM_INTEGRATION_URL . 'assets/js/front.js', [ 'jquery' ], CF_CIVICRM_INTEGRATION_VER );
	}

	/**
	* Enqueue scripts.
	*
	* @uses 'caldera_forms_admin_assets_scripts_registered' action
	* @since 0.4.2
	*/
	public function enqueue_civicrm_scripts(){
		// dequeue if we are not in the form editor
		if( ! $this->is_caldera_forms_admin() )
			wp_dequeue_script( 'cfc-select2' );
		// select2 4.0.3 script with tiny hack to register our own name and prevent conflicts
		if( $this->is_caldera_forms_admin() ) {
			wp_enqueue_script( 'cfc-select2' );
			wp_enqueue_script( 'cfc-admin' );
		}
	}

	/**
	* Enqueue styles.
	*
	* @uses 'caldera_forms_admin_assets_scripts_registered' action
	* @since 0.4.2
	*/
	public function enqueue_civicrm_styles(){
		// dequeue if we are not in the form editor
		if( ! $this->is_caldera_forms_admin() )
			wp_dequeue_style( 'cfc-select2' );
		// select2 4.0.3 style
		if( $this->is_caldera_forms_admin() )
			wp_enqueue_style( 'cfc-select2' );

	}

	/**
	 * Enqueue conditional script in editor footer.
	 *
	 * @since 1.0
	 */
	public function enqueue_in_editor_footer() {
		wp_enqueue_script( 'cfc-autopop-conditionals' );
	}

	/**
	* Check if are in Caldera Forms admin context.
	* 
	* @since 0.4.4
	*/
	public function is_caldera_forms_admin() {
		return is_admin() && isset( $_GET['page'] ) && $_GET['page'] == 'caldera-forms';
	}
}