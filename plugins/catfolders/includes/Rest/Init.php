<?php
namespace CatFolders\Rest;

class Init {
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}

	public function register_routes() {
		$controllers = array(
			'FolderController',
			'ExportController',
			'ImportController',
			'SettingController',
			'BlockController',
			'MetaController',
		);

		foreach ( $controllers as $controller ) {
			$controller_class = __NAMESPACE__ . "\\Controllers\\{$controller}";
			$controller_obj   = new $controller_class();
			$controller_obj->register_routes();
		}
	}
}
