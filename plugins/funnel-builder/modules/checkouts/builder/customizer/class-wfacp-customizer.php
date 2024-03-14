<?php

defined( 'ABSPATH' ) || exit;

#[AllowDynamicProperties]

 final class WFACP_Customizer {

	private static $ins = null;
	public static $is_checkout = false;
	/**
	 * @var WFACP_Pre_Built
	 */
	private $template_ins = null;
	private $template_path = '';
	private $wfacp_id = 0;
	private $is_customizer_page = false;
	public $customizer_key_prefix = '';
	public $template_assets = '';

	protected function __construct() {
		$this->wfacp_id        = WFACP_Common::get_id();
		$this->template_path   = WFACP_PLUGIN_DIR . '/templates';
		$this->template_assets = WFACP_PLUGIN_URL . '/assets';
		add_action( 'wfacp_register_template_types', [ $this, 'register_template_type' ],990 );
		add_filter( 'wfacp_register_templates', [ $this, 'register_templates' ] );
		add_action( 'wfacp_register_template_types', [ $this, 'register_wp_editor_template_type' ], 999 );
		add_filter( 'wfacp_register_templates', [ $this, 'register_wp_editor_templates' ], 999 );
	}

	public function is_customizer_template( $type ) {
		return in_array( $type, [ 'embed_forms', 'pre_built', 'embed_form' ] );
	}

	/**
	 * @param $loader WFACP_Template_loader
	 */
	public function register_template_type( $loader ) {
		$loader->remove_template_type( 'embed_forms' );
		$loader->remove_all_templates( 'embed_forms' );

		$template = [
			'slug'    => 'pre_built',
			'title'   => __( 'Customizer', 'funnel-builder' ),
			'filters' => WFACP_Common::get_template_filter()
		];
		$loader->register_template_type( $template );


	}

	public function register_templates( $designs ) {
		$templates = WooFunnels_Dashboard::get_all_templates();

		$designs['pre_built'] = ( isset( $templates['wc_checkout'] ) && isset( $templates['wc_checkout']['pre_built'] ) ) ? $templates['wc_checkout']['pre_built'] : [];

		return $designs;
	}

	/**
	 * @param $loader WFACP_Template_loader
	 */
	public function register_wp_editor_template_type( $loader ) {
		$template = [
			'slug'    => 'embed_forms',
			'title'   => __( 'Other (Using Shortcodes)', 'funnel-builder' ),
			'filters' => WFACP_Common::get_template_filter()
		];
		$loader->register_template_type( $template );
	}

	public function register_wp_editor_templates( $designs ) {
		$templates = WooFunnels_Dashboard::get_all_templates();

		$designs['embed_forms'] = ( isset( $templates['wc_checkout'] ) && isset( $templates['wc_checkout']['embed_forms'] ) ) ? $templates['wc_checkout']['embed_forms'] : [];

		if ( is_array( $designs['embed_forms'] ) && count( $designs['embed_forms'] ) > 0 ) {
			foreach ( $designs['embed_forms'] as $key => $val ) {
				$val['path']                    = WFACP_BUILDER_DIR . '/customizer/templates/embed_forms_1/template.php';
				$designs['embed_forms'][ $key ] = $val;
			}
		}

		return $designs;
	}


	public static function get_instance() {
		if ( self::$ins == null ) {
			self::$ins = new self();
		}

		return self::$ins;
	}


	/**
	 * @return WFACP_Template_Common
	 */
	public function get_template_instance() {
		return $this->template_ins;
	}


	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * To avoid cloning of current template class
	 */
	protected function __clone() {

	}

}

if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
	WFACP_Core::register( 'customizer', 'WFACP_Customizer' );
}
