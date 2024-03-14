<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once IFSO_PLUGIN_SERVICES_BASE_DIR . 'triggers-visited-service/triggers-visited-service.class.php';

class CookiesHandler extends ChainHandlerBase {
	public function handle($context) {
	    $triggers_visited_service = \IfSo\PublicFace\Services\TriggersVisitedService\TriggersVisitedService::get_instance();
        $triggers_visited_service->add_trigger($context->get_trigger_id());

		return $this->handle_next($context);
	}
}