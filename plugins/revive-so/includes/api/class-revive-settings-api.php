<?php 
/**
 * Settings API Class.
 *
 */

defined( 'ABSPATH' ) || exit;

class REVIVESO_SettingsApi
{
	use REVIVESO_Hooker;

	/**
	 * Admin pages.
	 *
	 * @var array
	 */
	public $admin_pages = array();

	/**
	 * Admin subpages.
	 *
	 * @var array
	 */
	public $admin_subpages = array();

	/**
	 * Plugin settings.
	 *
	 * @var array
	 */
	public $settings = array();

	/**
	 * Plugin sections.
	 *
	 * @var array
	 */
	public $sections = array();

	/**
	 * Plugin fields.
	 *
	 * @var array
	 */
	public $fields = array();

    /**
	 * Register functions.
	 */
	public function register() {
		if ( ! empty( $this->admin_pages ) || ! empty( $this->admin_subpages ) ) {
			$this->action( 'admin_menu', 'addAdminMenu' );
		}

	}

	/**
	 * Register menu pages.
	 */
	public function addPages( array $pages ) {
		$this->admin_pages = $pages;

		return $this;
	}

	/**
	 * Register sub menu pages.
	 */
	public function withSubPage( $title = null ) {
		if ( empty( $this->admin_pages ) ) {
			return $this;
		}

		$admin_page = $this->admin_pages[0];

		$subpage = array(
			array(
				'parent_slug' => $admin_page['menu_slug'], 
				'page_title'  => $admin_page['page_title'], 
				'menu_title'  => ( $title ) ? $title : $admin_page['menu_title'], 
				'capability'  => $admin_page['capability'], 
				'menu_slug'   => $admin_page['menu_slug'], 
				'callback'    => $admin_page['callback'],
			),
		);

		$this->admin_subpages = $subpage;

		return $this;
	}

	/**
	 * Locate sub menu pages.
	 */
	public function addSubPages( array $pages ) {
		$this->admin_subpages = array_merge( $this->admin_subpages, $pages );

		return $this;
	}

	/**
	 * Register admin menus & submenus.
	 */
	public function addAdminMenu() {
		foreach ( $this->admin_pages as $page ) {
			add_menu_page( $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], $page['icon_url'], $page['position'] );
		}

		foreach ( $this->admin_subpages as $page ) {
			add_submenu_page( $page['parent_slug'], $page['page_title'], $page['menu_title'], $page['capability'], $page['menu_slug'], $page['callback'], 0 );
		}
	}

}