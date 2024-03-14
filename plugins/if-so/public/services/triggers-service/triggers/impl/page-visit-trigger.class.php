<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');
require_once( IFSO_PLUGIN_SERVICES_BASE_DIR . 'trigger-validation-service/trigger-validation-service.class.php' );

use IfSo\PublicFace\Services\PageVisitsService;

class PageVisitTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('PageVisit');
	}

	protected function is_valid($trigger_data) {
		$rule = $trigger_data->get_rule();

		if ( !isset($rule['page_visit_data']) )
			return false;
		else if ( empty( $rule['page_visit_data'] ) )
			return false;

		return true;
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

        $page_visit_data = mb_convert_encoding($rule['page_visit_data'], 'ISO-8859-1', 'UTF-8');
		$page_visit_data = explode("^^", $page_visit_data);

		foreach ($page_visit_data as $key => $value) {
			$data = explode("!!", $value);
			$symbolType = strtolower($data[0]);

			if (is_numeric($data[2])) {
				// Backwards compatibility to the version where the users chose the page title
				$page_id = $data[2];
				$is_visited = 
					PageVisitsService\PageVisitsService::get_instance()->is_visited($page_id, '');

				if ($is_visited) {
                    return $content;
				}
			} else if ($symbolType == "pageurl") {
				$page_url = $data[1];
				$operator = $data[2];

				$is_visited = 
					PageVisitsService\PageVisitsService::get_instance()->is_visited($page_url, $operator);

				if ($is_visited) {
                    return $content;
				}
			}
		}

		return false;
	}
}