<?php

if ( ! function_exists( 'qi_addons_for_elementor_add_welcome_sub_page_to_list' ) ) {
	/**
	 * Function that add additional sub page item into general page list
	 *
	 * @param array $sub_pages
	 *
	 * @return array
	 */
	function qi_addons_for_elementor_add_welcome_sub_page_to_list( $sub_pages ) {
		$sub_pages[] = 'QiAddonsForElementor_Admin_Page_Welcome';

		return $sub_pages;
	}

	add_filter( 'qi_addons_for_elementor_filter_add_welcome_sub_page', 'qi_addons_for_elementor_add_welcome_sub_page_to_list' );
}

if ( class_exists( 'QiAddonsForElementor_Admin_Sub_Pages' ) ) {
	class QiAddonsForElementor_Admin_Page_Welcome extends QiAddonsForElementor_Admin_Sub_Pages {

		public function __construct() {

			parent::__construct();

			add_action( 'qi_addons_for_elementor_action_additional_scripts', array( $this, 'set_additional_scripts' ) );
		}

		function add_sub_page() {
			$this->set_base( 'welcome' );
			$this->set_menu_slug( 'qi_addons_for_elementor_welcome' );
			$this->set_title( esc_html__( 'Welcome Page', 'qi-addons-for-elementor' ) );
			$this->set_position( 1 );
			$this->set_atts( $this->set_atributtes() );
		}

		function set_atributtes() {

			$atts = array();
			return $atts;
		}

		function set_additional_scripts() {

			if ( isset( $_GET['page'] ) && $_GET['page'] === $this->get_menu_slug() ) {
				wp_enqueue_script( 'mailchimp', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-pages/assets/plugins/mailchimp/mailchimp.min.js', array( 'jquery' ), false, true );
			}
		}

	}
}
