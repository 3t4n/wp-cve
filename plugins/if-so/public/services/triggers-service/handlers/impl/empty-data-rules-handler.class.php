<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once( plugin_dir_path ( dirname(__DIR__) ) . 'filters/trigger-filter.class.php');

use IfSo\PublicFace\Services\TriggersService\Filters;

class EmptyDataRulesHandler extends ChainHandlerBase {
	public function handle($context) {
		$data_rules = $context->get_data_rules();

		if ( empty($data_rules) ) {
			return Filters\TriggerFilter::get_instance()->apply_filters( $context->get_default_content() );
		}

		return $this->handle_next($context);
	}
}