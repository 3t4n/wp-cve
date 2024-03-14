<?php

namespace Entity;

class Page {
	private string $slug;

	private string $page_title;
	private string $menu_title;
	private string $capability;
	private bool $show_in_admin_menu;

	/**
	 * @return string
	 */
	public function get_slug(): string {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function set_slug( string $slug ): void {
		$this->slug = $slug;
	}

	/**
	 * @return string
	 */
	public function get_page_title(): string {
		return $this->page_title;
	}

	/**
	 * @param string $page_title
	 */
	public function set_page_title( string $page_title ): void {
		$this->page_title = $page_title;
	}

	/**
	 * @return string
	 */
	public function get_menu_title(): string {
		return __($this->menu_title, 'hjqs-legal-notice');
	}

	/**
	 * @param string $menu_title
	 */
	public function set_menu_title( string $menu_title ): void {
		$this->menu_title = $menu_title;
	}

	/**
	 * @return string
	 */
	public function get_capability(): string {
		return $this->capability;
	}

	/**
	 * @param string $capability
	 */
	public function set_capability( string $capability ): void {
		$this->capability = $capability;
	}

	/**
	 * @return bool
	 */
	public function is_show_in_admin_menu(): bool {
		return $this->show_in_admin_menu;
	}

	/**
	 * @param bool $show_in_admin_menu
	 */
	public function set_show_in_admin_menu( bool $show_in_admin_menu ): void {
		$this->show_in_admin_menu = $show_in_admin_menu;
	}

}