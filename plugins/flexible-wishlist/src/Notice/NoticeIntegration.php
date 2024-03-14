<?php

namespace WPDesk\FlexibleWishlist\Notice;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\PluginAccess;
use FlexibleWishlistVendor\WPDesk\View\Renderer\SimplePhpRenderer;
use FlexibleWishlistVendor\WPDesk\View\Resolver\DirResolver;
use FlexibleWishlistVendor\WPDesk_Plugin_Info;

/**
 * Supports ability to display notice and its management.
 */
class NoticeIntegration implements Hookable {

	use PluginAccess;

	/**
	 * @var WPDesk_Plugin_Info
	 */
	private $plugin;

	/**
	 * @var Notice
	 */
	private $notice;

	/**
	 * @var SimplePhpRenderer
	 */
	private $renderer;

	/**
	 * Class constructor.
	 */
	public function __construct( WPDesk_Plugin_Info $plugin, Notice $notice ) {
		$this->plugin   = $plugin;
		$this->notice   = $notice;
		$this->renderer = new SimplePhpRenderer( new DirResolver( dirname( dirname( __DIR__ ) ) . '/templates' ) );
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_filter( 'admin_init', [ $this, 'init_admin_notice' ] );
		add_action( 'wp_ajax_fw_close_' . $this->notice->get_notice_name(), [ $this, 'hide_admin_notice' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function init_admin_notice() {
		if ( ! $this->notice->is_active() ) {
			return;
		}

		add_filter( 'admin_notices', [ $this, 'load_admin_notice_template' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_styles_for_notice' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'load_scripts_for_notice' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_admin_notice_template() {
		echo $this->renderer->render( // phpcs:ignore
			$this->notice->get_template_path(),
			array_merge( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				[
					'ajax_url'    => esc_attr( admin_url( 'admin-ajax.php' ) ),
					'ajax_action' => esc_attr( 'fw_close_' . $this->notice->get_notice_name() ),
				],
				$this->notice->get_vars_for_view()
			)
		);
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_styles_for_notice() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.css' : '.min.css';

		wp_register_style(
			'fw-notice',
			trailingslashit( $this->plugin->get_plugin_url() ) . 'assets/css/admin-notice' . $suffix,
			[],
			( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : $this->plugin->get_version()
		);
		wp_enqueue_style( 'fw-notice' );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function load_scripts_for_notice() {
		$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '.js' : '.min.js';

		wp_register_script(
			'fw-notice',
			trailingslashit( $this->plugin->get_plugin_url() ) . 'assets/js/admin-notice' . $suffix,
			[],
			( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? time() : $this->plugin->get_version(),
			true
		);
		wp_enqueue_script( 'fw-notice' );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function hide_admin_notice() {
		$is_permanently = ( isset( $_POST['is_permanently'] ) && $_POST['is_permanently'] ); // phpcs:ignore
		$this->notice->set_notice_as_hidden( $is_permanently );
	}
}
