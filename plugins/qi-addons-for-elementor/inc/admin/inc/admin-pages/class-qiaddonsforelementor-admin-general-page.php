<?php

if ( ! class_exists( 'QiAddonsForElementor_Admin_General_Page' ) ) {
	class QiAddonsForElementor_Admin_General_Page {
		private static $instance;
		private $menu_slug;
		private $title;
		private $sub_pages;
		private $transient;

		function __construct() {

			$this->menu_slug = 'qi_addons_for_elementor_welcome';
			$this->title     = esc_html__( 'Qi Addons For Elementor', 'qi-addons-for-elementor' );
			$this->transient = 'qi_addons_for_elementor_set_redirect';

			add_action( 'init', array( $this, 'register_sub_pages' ) ); // action is init because of shortcode register on init - 0
			add_action( 'admin_menu', array( $this, 'dashboard_add_page' ) );

			add_action( 'admin_init', array( $this, 'redirect' ) );

			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 20 );

			add_filter( 'admin_body_class', array( $this, 'add_admin_body_classes' ) );

		}

		/**
		 * @return QiAddonsForElementor_Admin_General_Page
		 */
		public static function get_instance() {

			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public function set_sub_pages( QiAddonsForElementor_Admin_Sub_Pages $sub_page ) {
			$this->sub_pages[ $sub_page->get_position() ] = $sub_page;
		}

		function get_sub_pages() {
			return $this->sub_pages;
		}

		function get_menu_slug() {
			return $this->menu_slug;
		}

		function get_title() {
			return $this->title;
		}

		function dashboard_add_page() {

			$page = add_menu_page(
				$this->get_title(),
				$this->get_title(),
				'edit_theme_options',
				$this->get_menu_slug(),
				null,
				QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-pages/assets/img/logo-qi.png',
				998
			);

			add_action( 'load-' . $page, array( $this, 'load_admin_css' ) );

			$subpages_array = $this->get_sub_pages();

			ksort( $subpages_array );

			foreach ( $subpages_array as $sub_page => $sub_page_value ) {
				$sub_page_instance = add_submenu_page(
					$this->get_menu_slug(),
					$sub_page_value->get_title(),
					$sub_page_value->get_title(),
					'edit_theme_options',
					$sub_page_value->get_menu_slug(),
					array( $sub_page_value, 'render' ),
					$sub_page_value->get_position()
				);

				add_action( 'load-' . $sub_page_instance, array( $this, 'load_admin_css' ) );
			}
		}

		function get_header( $object = null ) {

			$object = ! empty( $object ) ? $object : $this;

			$args = array(
				'menu_slug'  => $object->get_menu_slug(),
				'menu_title' => $object->get_title(),
				'menu_url'   => admin_url( 'admin.php?page=' . $this->get_menu_slug() ),
			);

			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'templates/header', '', $args );
		}

		function get_footer() {
			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'templates/footer' );
		}

		function get_sidebar() {
			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'templates/sidebar' );
		}

		function get_content() {

			$args = array();
			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'templates/general', '', $args );
		}

		function render_holder() {

			$args = array(
				'this_object' => $this,
			);

			qi_addons_for_elementor_framework_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'templates/holder', '', $args );
		}

		public function register_sub_pages() {
			$sub_pages = apply_filters( 'qi_addons_for_elementor_filter_add_welcome_sub_page', $sub_pages = array() );

			if ( ! empty( $sub_pages ) ) {
				foreach ( $sub_pages as $sub_page ) {
					$sub_object = new $sub_page();
					$this->set_sub_pages( $sub_object );
				}
			}
		}

		function load_admin_css() {
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		}

		function enqueue_styles() {
			wp_enqueue_style( 'qi-addons-for-elementor-dashboard-style', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-pages/assets/css/dashboard.min.css' );
		}

		function enqueue_scripts() {
			wp_enqueue_script( 'qi-addons-for-elementor-framework-script', QI_ADDONS_FOR_ELEMENTOR_ADMIN_URL_PATH . '/inc/admin-pages/assets/js/dashboard.js', array( 'jquery' ), false, true );

			do_action( 'qi_addons_for_elementor_action_additional_scripts' );

		}

		function add_admin_body_classes( $classes ) {

			$pages = $this->get_all_dashboard_slugs();

			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $pages, true ) ) {

				$classes = $classes . ' qodef-qi-addons-for-elementor';
			}

			return $classes;
		}

		function admin_footer_text( $text ) {

			$pages = $this->get_all_dashboard_slugs();

			if ( isset( $_GET['page'] ) && in_array( $_GET['page'], $pages, true ) ) {
				return qi_addons_for_elementor_framework_get_template_part( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc', 'admin-pages', 'templates/parts/footer-text' );
			}

			return $text;
		}

		function get_all_dashboard_slugs() {

			$pages = array(
				$this->get_menu_slug(),
			);

			foreach ( $this->sub_pages as $sub_page ) {
				$pages[] = $sub_page->get_menu_slug();
			}

			return $pages;
		}

		function redirect() {

			if ( wp_doing_ajax() ) {
				return;
			}

			if ( is_network_admin() || isset( $_GET['activate-multi'] ) ) {
				return;
			}

			if ( ! empty( get_transient( QI_ADDONS_FOR_ELEMENTOR_ACTIVATED_TRANSIENT ) ) && empty( get_transient( $this->transient ) ) ) {

				set_transient( $this->transient, 1 );

				wp_safe_redirect(
					admin_url( 'admin.php?page=' . $this->get_menu_slug() )
				);

				exit;
			}

		}

	}

	QiAddonsForElementor_Admin_General_Page::get_instance();
}
