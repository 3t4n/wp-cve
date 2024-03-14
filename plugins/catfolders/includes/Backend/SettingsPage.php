<?php
namespace CatFolders\Backend;

use CatFolders\Core\Base;
use CatFolders\Rest\Controllers\ImportController;
use CatFolders\Classes\Vite;

class SettingsPage extends Base {
	private $settingSuffix = null;

	public function __construct() {
		if ( ! parent::initialize() ) {
			return;
		}

		add_filter( 'plugin_row_meta', array( $this, 'pluginRowMeta' ), 10, 2 );

		add_action( 'admin_menu', array( $this, 'adminMenu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'adminEnqueueScripts' ) );
		add_filter( 'plugin_action_links_' . CATF_PLUGIN_BASE_NAME, array( $this, 'addActionLinks' ) );
	}

	public function adminMenu() {
		$this->settingSuffix = add_menu_page(
			__( 'CatFolders', 'catfolders' ),
			__( 'CatFolders', 'catfolders' ),
			'manage_options',
			'cat_folders',
			array( $this, 'page_callback' ),
			'dashicons-open-folder'
		);
	}

	public function page_callback() {
		?>
<div id="catf-setting-app"></div>
		<?php
	}

	public function adminEnqueueScripts( $hook_suffix ) {
		if ( $hook_suffix === $this->settingSuffix ) {
			remove_all_actions( 'admin_notices' );

			Vite::enqueueVite( 'admin.tsx' );

			wp_localize_script(
				'module/catfolders/admin.tsx',
				'catfSettings',
				array(
					'pluginsToImport' => ( new ImportController() )->detect_import(),
					'globalSettings'  => $this->settings,
				)
			);
		}
	}

	public function pluginRowMeta( $links, $file ) {
		if ( strpos( $file, 'catfolders.php' ) !== false ) {

			$new_links = array(
				'doc'     => '<a href="https://wpmediafolders.com/docs/catfolders/introduction/" rel="noopener noreferrer" target="_blank">' . __( 'Docs', 'catfolders' ) . '</a>',
				'support' => '<a href="https://wpmediafolders.com/contact/" rel="noopener noreferrer" target="_blank">' . __( 'Support', 'catfolders' ) . '</a>',
			);

			$links = array_merge( $links, $new_links );
		}

		return $links;
	}

	public function addActionLinks( $links ) {
		$settingLink = add_query_arg(
			array(
				'page' => 'cat_folders',
			),
			admin_url( 'admin.php' )
		);

		$settingsLinks = array(
			'<a href="' . $settingLink . '">' . __( 'Settings', 'catfolders' ) . '</a>',
		);

		return array_merge( $settingsLinks, $links );
	}
}
