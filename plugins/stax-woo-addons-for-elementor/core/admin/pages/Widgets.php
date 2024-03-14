<?php

namespace StaxWoocommerce;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Widgets
 * @package StaxWoocommerce
 */
class Widgets extends Base {

	/**
	 * Settings constructor.
	 */
	public function __construct() {
		$this->current_slug = 'widgets';

		if ( Plugin::instance()->is_current_page( $this->current_slug ) ) {
			add_filter( STAX_WOO_HOOK_PREFIX . 'current_slug', [ $this, 'set_page_slug' ] );
			add_filter( STAX_WOO_HOOK_PREFIX . 'welcome_wrapper_class', [ $this, 'set_wrapper_classes' ] );
			add_action( STAX_WOO_HOOK_PREFIX . $this->current_slug . '_page_content', [ $this, 'panel_content' ] );
		}

		add_filter( STAX_WOO_HOOK_PREFIX . 'admin_menu', [ $this, 'add_menu_item' ] );
		add_action( 'admin_post_stax_woo_widget_activation', [ $this, 'toggle_widget' ] );
	}

	public function toggle_widget() {

		$is_safe = true;

		if ( ! current_user_can( 'manage_options' ) ) {
			$is_safe = false;
		}

		if ( ! isset( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'stax_woo_widget_activation' ) ) {
			$is_safe = false;
		}

		if ( ! $is_safe ) {
			wp_redirect( admin_url( 'admin.php?page=' . STAX_EL_SLUG_PREFIX . $this->current_slug ) );
			exit;
		}

		$options = [];

		$widgets = StaxWidgets::instance()->get_widgets();

		foreach ( $widgets as $widget ) {
			$valid = false;

			if ( isset( $_POST[ $widget['slug'] ] ) ) {
				$valid = true;
			}

			if ( ! $valid ) {
				$options[ $widget['slug'] ] = true;
			}
		}

		update_option( '_stax_woocommerce_disabled_widgets', $options );

		wp_redirect( admin_url( 'admin.php?page=' . STAX_WOO_SLUG_PREFIX . $this->current_slug ) );
		exit();
	}

	/**
	 * Panel content
	 */
	public function panel_content() {
		Utils::load_template( 'core/admin/pages/templates/widgets', [
			'widgets' => StaxWidgets::instance()->get_widgets( false, true )
		] );
	}

	public function add_menu_item( $menu ) {
		$menu[] = [
			'name'     => __( 'Widgets', 'stax-woo-addons-for-elementor' ),
			'link'     => admin_url( 'admin.php?page=' . STAX_WOO_SLUG_PREFIX . $this->current_slug ),
			'priority' => 2
		];

		return $menu;
	}

}

Widgets::instance();
