<?php
namespace Skt_Addons_Elementor\Elementor;

defined( 'ABSPATH' ) || die();

class Admin_Bar {

	public static function init() {
		add_action( 'admin_bar_menu', [__CLASS__, 'add_toolbar_items'], 500 );
		add_action( 'wp_enqueue_scripts', [__CLASS__, 'enqueue_assets'] );
		add_action( 'admin_enqueue_scripts', [__CLASS__, 'enqueue_assets'] );
		add_action( 'wp_ajax_skt_addons_elementor_clear_cache', [__CLASS__, 'clear_cache' ] );
	}

	public static function clear_cache() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( ! check_ajax_referer( 'skt_addons_elementor_clear_cache', 'nonce' ) ) {
			wp_send_json_error();
		}

		$type = sanitize_text_field(isset( $_POST['type'] ) ? $_POST['type'] : '');
		$post_id = sanitize_text_field(isset( $_POST['post_id'] ) ? $_POST['post_id'] : 0);
		$assets_cache = new Assets_Cache( $post_id );
		if ( $type === 'page' ) {
			$assets_cache->delete();
		} elseif ( $type === 'all' ) {
			$assets_cache->delete_all();
		}
		wp_send_json_success();
	}

	public static function enqueue_assets() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		wp_enqueue_style(
			'skt-addons-elementor-admin',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/css/admin.min.css',
			null,
			SKT_ADDONS_ELEMENTOR_VERSION
		);

		wp_enqueue_script(
			'skt-addons-elementor-admin',
			SKT_ADDONS_ELEMENTOR_ASSETS . 'admin/js/admin.min.js',
			['jquery'],
			SKT_ADDONS_ELEMENTOR_VERSION,
			true
		);

		wp_localize_script(
			'skt-addons-elementor-admin',
			'SktAdmin',
			[
				'nonce'    => wp_create_nonce( 'skt_addons_elementor_clear_cache' ),
				'post_id'  => get_queried_object_id(),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
			]
		);
	}

	public static function add_toolbar_items( \WP_Admin_Bar $admin_bar ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$icon = '<i class="dashicons dashicons-update-alt"></i> ';

		$admin_bar->add_menu( [
			'id'    => 'skt-addons',
			'title' => sprintf( '<img src="%s">', skt_addons_elementor_get_b64_icon() ),
			'href'  => skt_addons_elementor_get_dashboard_link(),
			'meta'  => [
				'title' => __( 'SKT Addons', 'skt-addons-elementor' ),
			]
		] );

		if ( is_singular() ) {
			$admin_bar->add_menu( [
				'id'     => 'skt-clear-page-cache',
				'parent' => 'skt-addons',
				'title'  => $icon . __( 'Page: Renew On Demand Assets', 'skt-addons-elementor' ),
				'href'   => '#',
				'meta'   => [
					'class' => 'sktjs-clear-cache skt-clear-page-cache',
				]
			] );
		}

		$admin_bar->add_menu( [
			'id'     => 'skt-clear-all-cache',
			'parent' => 'skt-addons',
			'title'  => $icon . __( 'Global: Renew On Demand Assets', 'skt-addons-elementor' ),
			'href'   => '#',
			'meta'   => [
				'class' => 'sktjs-clear-cache skt-clear-all-cache',
			]
		] );
	}
}

Admin_Bar::init();