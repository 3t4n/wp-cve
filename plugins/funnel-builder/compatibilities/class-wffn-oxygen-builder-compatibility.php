<?php

class WFFN_OXYGEN {
	private static $ins = null;
	private static $front_locals = [];
	private $section_slug = "woofunnels";
	private $tab_slug = "woofunnels";
	public $modules_instance = [];


	private function __construct() {

		$this->register();
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;

	}


	private function register() {
		/* show a section in +Add */
		if ( isset( $_GET['ct_template'] ) && isset( $_GET['ct_builder'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			return;
		}
		add_action( 'oxygen_add_plus_sections', [ $this, 'add_plus_sections' ], 11 );
		add_action( "oxygen_add_plus_" . $this->section_slug . "_section_content", [ $this, 'add_plus_subsections_content' ], 11 );

	}


	public static function set_locals( $name, $id ) {
		self::$front_locals[ $name ] = $id;
	}

	public static function get_locals() {
		return self::$front_locals;

	}


	public function add_plus_sections() {
		if ( did_action( "oxygen_add_plus_{$this->section_slug}_section_content" ) > 0 ) {
			return;
		}
		/* show a section in +Add dropdown menu and name it "My Custom Elements" */
		CT_Toolbar::oxygen_add_plus_accordion_section( $this->section_slug, __( "FunnelKit", 'woofunnels-aero-checkout' ) );
	}


	public function add_plus_subsections_content() {
		if ( did_action( "oxygen_add_plus_woofunnels_woofunnels" ) > 0 ) {
			return;
		}
		do_action( "oxygen_add_plus_woofunnels_woofunnels" );
	}

	public static function disable_signature_checking() {
		add_filter( "option_oxygen_vsb_enable_signature_validation", '__return_false', 99 );
	}

	public static function is_template_editor() {
		return isset( $_REQUEST['action'] ) && ( 'ct_save_components_tree' === $_REQUEST['action'] || 'ct_render_innercontent' === $_REQUEST['action'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

}

WFFN_OXYGEN::get_instance();
