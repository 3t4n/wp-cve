<?php
namespace HtMegaBlocks;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Blocks Assets Manage
 */
class Scripts
{

	/**
	 * [$_instance]
	 * @var null
	 */
	private static $_instance = null;

	/**
	 * [instance] Initializes a singleton instance
	 * @return [Scripts]
	 */
	public static function instance()
	{
		if (is_null(self::$_instance)) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * The Constructor.
	 */
	public function __construct()
	{
		add_action('enqueue_block_assets', [$this, 'block_assets']);
		add_action('enqueue_block_editor_assets', [$this, 'block_editor_assets']);
	}

	/**
	 * Block assets.
	 */
	public function block_assets()
	{

		wp_enqueue_style('dashicons');
		wp_enqueue_style(
			'htmega-block-common-style',
			HTMEGA_BLOCK_URL . 'src/assets/css/common-style.css',
			[],
			HTMEGA_VERSION
		);
		wp_enqueue_style(
			'slick',
			HTMEGA_ADDONS_PL_URL . 'assets/css/slick.min.css',
			[],
			HTMEGA_VERSION
		);
		wp_enqueue_style(
			'htmega-block-fontawesome',
			HTMEGA_ADDONS_PL_URL . 'admin/assets/extensions/ht-menu/css/font-awesome.min.css',
			[],
			HTMEGA_VERSION
		);
		wp_enqueue_style(
			'htmega-block-style',
			HTMEGA_BLOCK_URL . 'build/style-blocks-htmega.css',
			[],
			HTMEGA_VERSION,
			'all'
		);

		wp_enqueue_script(
			'slick',
			HTMEGA_ADDONS_PL_URL . 'assets/js/slick.min.js',
			['jquery'],
			HTMEGA_VERSION,
			true
		);
		wp_enqueue_script(
			'htmega-block-main',
			HTMEGA_BLOCK_URL . 'src/assets/js/script.js',
			['slick'],
			HTMEGA_VERSION,
			true
		);

	}

	/**
	 * Block editor assets.
	 */
	public function block_editor_assets()
	{

		global $pagenow;

		if ($pagenow !== 'widgets.php') {

			wp_enqueue_style('slick');
			wp_enqueue_style(
				'htmega-block-editor-style',
				HTMEGA_BLOCK_URL . 'src/assets/css/editor-style.css',
				[],
				HTMEGA_VERSION,
				'all'
			);

			$dependencies = require_once(HTMEGA_BLOCK_PATH . '/build/blocks-htmega.asset.php');
			wp_enqueue_script(
				'htmega-blocks',
				HTMEGA_BLOCK_URL . 'build/blocks-htmega.js',
				$dependencies['dependencies'],
				HTMEGA_VERSION,
				true
			);

			/**
			 * Localize data
			 */
			$editor_localize_data = array(
				'url' => HTMEGA_BLOCK_URL,
				'ajax' => admin_url('admin-ajax.php'),
				'security' => wp_create_nonce('htmega-block-nonce'),
				'locale' => get_locale(),
				'options' => $this->get_block_list()['block_list']
			);

			wp_localize_script('htmega-blocks', 'htmegaData', $editor_localize_data);
		}

	}

	/**
	 * Manage block based on template type
	 */
	public function get_block_list()
	{

		$blocks_list = Blocks_init::$blocksList;

		$common_block = array_key_exists('common', $blocks_list) ? $blocks_list['common'] : [];

		return array(
			'block_list' => $common_block
		);
	}

}