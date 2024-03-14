<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\Pages;

use GPLSCore\GPLS_PLUGIN_AVFSTW\Pages\PagesBase\AdminPage;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Utils\GeneralUtilsTrait;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Utils\Img\ImgUtilsTrait;

/**
 * Settings Page.
 */
class SettingsPage extends AdminPage {
	use GeneralUtilsTrait, ImgUtilsTrait;

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	protected static $instance = null;

	/**
	 * Page Hooks.
	 *
	 * @return void
	 */
	protected function hooks() {
		add_action( 'plugin_action_links_' . self::$plugin_info['basename'], array( $this, 'settings_link' ), 5, 1 );
	}

	/**
	 * Settings Link.
	 *
	 * @param array $links Plugin Row Links.
	 * @return array
	 */
	public function settings_link( $links ) {
		$links[] = '<a href="' . esc_url( admin_url( 'upload.php?page=' . self::$plugin_info['name'] . '-settings' ) ) . '">' . esc_html__( 'Settings', 'avif-support' ) . '</a>';
		return $links;
	}

	/**
	 * Prepare Page.
	 *
	 * @return void
	 */
	protected function prepare() {
		$this->page_props = array(
			'menu_title'  => esc_html__( 'AVIF Support', 'avif-support' ),
			'page_title'  => '',
			'parent_slug' => 'upload.php',
			'menu_slug'   => self::$plugin_info['name'] . '-settings',
			'tab_key'     => 'tab',
		);

		$this->tabs = array(
			'status'       => array(
				'title'    => esc_html__( 'Status', 'avif-support' ),
				'default'  => true,
				'template' => 'status-template.php',
			),
		);


		$this->assets = array(
			array(
				'type'        => 'css',
				'handle'      => self::$plugin_info['name'] . '-select2-css',
				'url'         => self::$plugin_info['url'] . 'includes/Core/assets/libs/select2.min.css',
				'conditional' => array(
					'tab' => 'bulk_convert',
				),
			),
			array(
				'type'        => 'js',
				'handle'      => self::$plugin_info['name'] . '-select2-actions',
				'url'         => self::$plugin_info['url'] . 'includes/Core/assets/libs/select2.full.min.js',
				'conditional' => array(
					'tab' => 'bulk_convert',
				),
			),
		);
	}
}
