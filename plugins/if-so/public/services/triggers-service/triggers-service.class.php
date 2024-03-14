<?php

namespace IfSo\PublicFace\Services\TriggersService;

require_once(IFSO_PLUGIN_BASE_DIR. 'public/models/triggers/ifso-triggers-model.class.php');

require_once('trigger-context-loader.class.php');
require_once('trigger-data.class.php');

use IfSo\PublicFace\Models\TriggersModel\TriggersModel;
use IfSo\PublicFace\Services\TriggersService\Handlers;

class TriggersService {
	private static $instance;
	
	private $root_handler;
	
	private function __construct() {
		$this->root_handler = $this->build_handlers();
	}
	
	private function build_handlers() {
		$triggers = TriggersModel::get_triggers();

		$licenseValidationHandler = new Handlers\LicenseValidationHandler();
		$licenseValidationHandler
			->set_next(new Handlers\SkipHandler())
			->set_next(new Handlers\TestingModeHandler())
			->set_next(new Handlers\EmptyDataRulesHandler())
			->set_next(new Handlers\CookiesHandler())
			->set_next(new Handlers\RecurrenceHandler())
			->set_next(new Handlers\TriggersHandler($triggers));

		return $licenseValidationHandler;
	}

	
	public static function get_instance() {
		if ( NULL == self::$instance )
			self::$instance = new TriggersService();

		return self::$instance;
	}
	
	public function handle($atts,$http_request=null) {
		if ( empty( $atts['id'] ) )
			return '';
		
		return $this->root_handler->handle(TriggerContextLoader::load_context($atts,$http_request));
	}

	public function handle_from_data($data_rules, $data_versions, $default_content, $http_request=null){
	    return $this->root_handler->handle(TriggerContextLoader::load_context_from_data(666,$data_rules,$data_versions,$default_content,$http_request));
    }
}