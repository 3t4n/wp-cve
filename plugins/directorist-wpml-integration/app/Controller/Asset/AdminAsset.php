<?php

namespace Directorist_WPML_Integration\Controller\Asset;

class AdminAsset extends AssetEnqueuer {
	
	/**
	 * Constuctor
	 * 
	 */
	function __construct() {
		$this->asset_group = 'admin';
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
	}

    /**
	 * Load Admin CSS Scripts
	 *
	 * @return void
	 */
	public function load_scripts() {
        $this->add_css_scripts();
        $this->add_js_scripts();
    }

	/**
	 * Load Admin CSS Scripts
	 *
	 * @return void
	 */
	public function add_css_scripts() {
		$scripts = [];

		// $scripts['directorist-wpml-integration-admin-main-style'] = [
		// 	'file_name' => 'admin-main',
		// 	'base_path' => DIRECTORIST_WPML_INTEGRATION_CSS_PATH,
		// 	'deps'      => [],
		// 	'ver'       => $this->script_version,
		// 	'group'     => 'admin',
		// ];

		$scripts['directorist-wpml-integration-admin-main-style'] = [
			'file_name' => 'admin-main',
			'base_path' => DIRECTORIST_WPML_INTEGRATION_CSS_PATH,
			'deps'      => [],
			'ver'       => $this->script_version,
			'group'     => 'admin',
		];

		$scripts['directorist-wpml-integration-admin-directory-builder'] = [
			'file_name'     => 'admin-directory-builder',
			'base_path'     => DIRECTORIST_WPML_INTEGRATION_CSS_PATH,
			'deps'          => [],
			'ver'           => $this->script_version,
			'group'         => 'admin',
		];

		$scripts = array_merge( $this->css_scripts, $scripts);
		$this->css_scripts = $scripts;
	}

	/**
	 * Load Admin JS Scripts
	 *
	 * @return void
	 */
	public function add_js_scripts() {
		$scripts = [];

		// $scripts['directorist-wpml-integration-admin-main-script'] = [
		// 	'file_name'     => 'admin-main',
		// 	'base_path'     => DIRECTORIST_WPML_INTEGRATION_JS_PATH,
		// 	'deps'          => '',
		// 	'ver'           => $this->script_version,
		// 	'group'         => 'admin',
		// ];

		$scripts['directorist-wpml-integration-admin-main-script'] = [
			'file_name'     => 'admin-main',
			'base_path'     => DIRECTORIST_WPML_INTEGRATION_JS_PATH,
			'ver'           => $this->script_version,
			'group'         => 'admin',
		];

		$scripts['directorist-wpml-integration-admin-directory-builder'] = [
			'file_name'     => 'admin-directory-builder',
			'base_path'     => DIRECTORIST_WPML_INTEGRATION_JS_PATH,
			'ver'           => $this->script_version,
			'group'         => 'admin',
			'localize_data' => [
				'object_name' => 'directory_builder_script_data',
				'data'        => [
					'ajax_url'          => admin_url('admin-ajax.php'),
					'directorist_nonce' => wp_create_nonce( directorist_get_nonce_key() ),
				],
			],
		];

		$scripts = array_merge( $this->js_scripts, $scripts);
		$this->js_scripts = $scripts;
	}
}