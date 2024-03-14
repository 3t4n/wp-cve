<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once( plugin_dir_path ( dirname(__DIR__) ) . 'filters/trigger-filter.class.php');

use IfSo\PublicFace\Services\TriggersService\Filters;
use IfSo\PublicFace\Services\TriggersService;

class TestingModeHandler extends ChainHandlerBase {
	public function handle($context) {
		$data_rules = $context->get_data_rules();
		$data_versions = $context->get_data_versions();
		
		if (!empty($data_rules) && 
			isset($data_rules[0]['testing-mode']) &&
			$data_rules[0]['testing-mode'] != "") {

			if (!is_numeric($data_rules[0]['testing-mode'])) return '';

			//declare testing mode var
			$testingModeIndex = intval($data_rules[0]['testing-mode']);

			if (0 == $testingModeIndex) {
				return Filters\TriggerFilter::get_instance()->apply_filters( $context->get_default_content(),TriggersService\TriggerData::createFromContext($context),$context->get_extra_opts() );
			}

			$testingModeIndex -= 2; // the default content takes one and it keeps counting from 2

			// otherwise just return the index's version content if it's not in the upper limit
			if (sizeof($data_rules) >= $testingModeIndex &&
				$testingModeIndex >= 0) {
				return Filters\TriggerFilter::get_instance()->apply_filters_and_hooks( $data_versions[$testingModeIndex],TriggersService\TriggerData::createFromContext($context,$testingModeIndex),$context->get_extra_opts() );
			}
		}
		
		return $this->handle_next($context);
	}
}