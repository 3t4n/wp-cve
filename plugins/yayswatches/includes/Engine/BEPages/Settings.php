<?php

namespace Yay_Swatches\Engine\BEPages;

use Yay_Swatches\Utils\SingletonTrait;
use Yay_Swatches\Helpers\Helper;

defined( 'ABSPATH' ) || exit;
/**
 * Settings Page
 */

use Yay_Swatches\I18n;

class Settings {


	use SingletonTrait;
	public $setting_hookfix = null;

	protected function __construct() {

		add_action( 'admin_menu', array( $this, 'admin_menu' ), YAY_SWATCHES_MENU_PRIORITY );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		add_filter( 'plugin_action_links_' . YAY_SWATCHES_BASE_NAME, array( $this, 'add_action_links' ) );
		add_filter( 'plugin_row_meta', array( $this, 'add_document_support_links' ), 10, 2 );
		add_filter( 'screen_options_show_screen', array( $this, 'remove_screen_options' ) );
	}

	public function add_action_links( $links ) {
		$links = array_merge(
			array(
				'<a href="' . esc_url( admin_url( '/admin.php?page=yay-swatches' ) ) . '">' . __( 'Settings', 'yay-swatches' ) . '</a>',
			),
			$links
		);

		return $links;
	}

	public function add_document_support_links( $links, $file ) {
		if ( strpos( $file, YAY_SWATCHES_BASE_NAME ) !== false ) {
			$new_links = array(
				'doc'     => '<a href="https://yaycommerce.gitbook.io/yayswatches/" target="_blank">' . __( 'Docs', 'yay-swatches' ) . '</a>',
				'support' => '<a href="https://yaycommerce.com/support/" target="_blank" aria-label="' . esc_attr__( 'Visit community forums', 'yay-swatches' ) . '">' . esc_html__( 'Support', 'yay-swatches' ) . '</a>',
			);
			$links     = array_merge( $links, $new_links );
		}
		return $links;
	}

	public function admin_menu() {
		$page_title            = __( 'YaySwatches', 'yay-swatches' );
		$menu_title            = __( 'YaySwatches', 'yay-swatches' );
		$this->setting_hookfix = add_submenu_page( 'yaycommerce', $page_title, $menu_title, 'manage_woocommerce', 'yay-swatches', array( $this, 'submenu_page_callback' ), 0 );
	}

	public function admin_enqueue_scripts( $hook_suffix ) {

		if ( $hook_suffix !== $this->setting_hookfix ) {
			return;
		}
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		wp_enqueue_style( 'wp-color-picker' );

		wp_register_script( 'yay-swatches', YAY_SWATCHES_PLUGIN_URL . 'assets/dist/js/main.js', array(), YAY_SWATCHES_VERSION, true );
		wp_localize_script(
			'yay-swatches',
			'yaySwatches',
			array(
				'admin_url'                    => admin_url( 'admin.php?page=wc-settings' ),
				'admin_post_url'               => admin_url( 'post.php?post=' ),
				'admin_product_attributes_url' => admin_url( 'edit.php?post_type=product&page=product_attributes' ),
				'ajaxurl'                      => admin_url( 'admin-ajax.php' ),
				'rest_url'                     => esc_url_raw( rest_url( 'yayswatches/v1' ) ),
				'single_product_url'           => site_url( '/?product=' ),
				'nonce'                        => wp_create_nonce( 'yay-swatches-nonce' ),
				'i18n'                         => I18n::getTranslation(),
			)
		);

		wp_enqueue_style(
			'yay-swatches',
			YAY_SWATCHES_PLUGIN_URL . 'assets/dist/main.css',
			array(
				'woocommerce_admin_styles',
				'wp-components',
			),
			YAY_SWATCHES_VERSION
		);

		wp_enqueue_script( 'yay-swatches' );
	}

	public function submenu_page_callback() {
		echo '<div id="yay-swatches"></div>';
	}

	public function remove_screen_options() {
		$current_screen = get_current_screen();
		if ( 'yaycommerce_page_yay-swatches' === $current_screen->id ) {
			return false;
		}
		return true;
	}
}
