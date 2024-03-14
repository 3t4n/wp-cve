<?php
declare(strict_types=1);

namespace WPDesk\ShopMagic\Admin;

interface AdminPage {

	/**
	 * Method for registering page to WordPress admin menu.
	 * Callback from function `add_submenu_page` or `add_menu_page` should point to
	 * another public method of the class `AdminPage::render()`
	 *
	 * @return void
	 */
	public function register(): void;

	/**
	 * Responsible for outputting content to WordPress admin page.
	 * Not meant to call directly, only as callback defined in `AdminPage::register()` method.
	 *
	 * @internal
	 * @return void
	 */
	public function render(): void;

}
