<?php
namespace LaStudioKit_Dashboard\Settings;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

use LaStudioKit_Dashboard\Base\Page_Module as Page_Module_Base;
use LaStudioKit_Dashboard\Dashboard as Dashboard;

class Available_Addons extends Page_Module_Base {

	/**
	 * Returns module slug
	 *
	 * @return void
	 */
	public function get_page_slug() {
		return 'lastudio-kit-avaliable-addons';
	}

	/**
	 * [get_subpage_slug description]
	 * @return [type] [description]
	 */
	public function get_parent_slug() {
		return 'settings-page';
	}

	/**
	 * [get_page_name description]
	 * @return [type] [description]
	 */
	public function get_page_name() {
		return esc_html__( 'Widgets & Extensions', 'lastudio-kit' );
	}

	/**
	 * [get_category description]
	 * @return [type] [description]
	 */
	public function get_category() {
		return 'lastudio-kit-settings';
	}

	/**
	 * [get_page_link description]
	 * @return [type] [description]
	 */
	public function get_page_link() {
		return Dashboard::get_instance()->get_dashboard_page_url( $this->get_parent_slug(), $this->get_page_slug() );
	}

	/**
	 * Enqueue module-specific assets
	 *
	 * @return void
	 */
	public function enqueue_module_assets() {

		wp_enqueue_style(
			'lastudio-kit-admin-css',
			lastudio_kit()->plugin_url( 'assets/css/lastudio-kit-admin.css' ),
			false,
			lastudio_kit()->get_version()
		);

		wp_enqueue_script(
			'lastudio-kit-admin-script',
			lastudio_kit()->plugin_url( 'assets/js/lastudio-kit-admin-vue-components.js' ),
			array( 'cx-vue-ui' ),
			lastudio_kit()->get_version(),
			true
		);

		wp_localize_script(
			'lastudio-kit-admin-script',
			'LaStudioKitSettingsConfig',
			apply_filters( 'lastudio-kit/admin/settings-page/localized-config', lastudio_kit_settings()->generate_frontend_config_data() )
		);

	}

	/**
	 * License page config
	 *
	 * @param  array  $config  [description]
	 * @param  string $subpage [description]
	 * @return [type]          [description]
	 */
	public function page_config( $config = array(), $page = false, $subpage = false ) {

		$config['pageModule'] = $this->get_parent_slug();
		$config['subPageModule'] = $this->get_page_slug();

		return $config;
	}

	/**
	 * [page_templates description]
	 * @param  array  $templates [description]
	 * @param  string $subpage   [description]
	 * @return [type]            [description]
	 */
	public function page_templates( $templates = array(), $page = false, $subpage = false ) {

		$templates['lastudio-kit-avaliable-addons'] = lastudio_kit()->plugin_path( 'templates/admin-templates/avaliable-addons.php' );

		return $templates;
	}
}
