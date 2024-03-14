<?php

if ( class_exists( '\Elementor\Widget_Base' ) ) {
	abstract class QiAddonsForElementor_Elementor_Widget_Base extends \Elementor\Widget_Base {
		public $object;
		private $shortcode_slug;

		public function __construct( $data, $args ) {
			$this->set_object( $this->get_shortcode_slug() );

			parent::__construct( $data, $args );
		}

		public function get_object() {
			return $this->object;
		}

		public function set_object( $shortcode_slug ) {
			$this->object = qi_addons_for_elementor_framework_get_framework_root()->get_shortcodes()->get_shortcode( $shortcode_slug );
		}

		public function get_shortcode_slug() {
			return $this->shortcode_slug;
		}

		public function set_shortcode_slug( $shortcode_slug ) {
			$this->shortcode_slug = $shortcode_slug;
		}

		public function get_name() {
			return $this->get_object()->get_base();
		}

		public function get_title() {
			return $this->get_object()->get_name();
		}

		public function get_script_depends() {
			return qi_addons_for_elementor_framework_get_elementor_translator()->set_scripts( $this->get_object() );
		}

		public function get_style_depends() {
			return qi_addons_for_elementor_framework_get_elementor_translator()->set_necessary_styles( $this->get_object() );
		}

		public function get_icon() {
			return 'qodef-custom-elementor-icon ' . str_replace( '_', '-', $this->get_name() );
		}

		public function get_categories() {
			return [ 'qi-addons' ];
		}

		protected function register_controls() {
			qi_addons_for_elementor_framework_get_elementor_translator()->create_controls( $this, $this->get_object() );
		}

		protected function render() {
			qi_addons_for_elementor_framework_get_elementor_translator()->create_render( $this->get_object(), $this->get_settings_for_display() );
		}
	}
}
