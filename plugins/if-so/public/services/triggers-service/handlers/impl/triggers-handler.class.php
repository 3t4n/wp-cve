<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once( plugin_dir_path ( dirname(__DIR__) ) . 'filters/trigger-filter.class.php');

use IfSo\PublicFace\Services\TriggersService\Filters;
use IfSo\PublicFace\Services\TriggersService;

class TriggersHandler extends ChainHandlerBase {
	protected $triggers;

	public function __construct($triggers) {
		$this->triggers = $triggers;
	}

	public function handle($context) {
		$data_rules = $context->get_data_rules();
        $extra_opts = $context->get_extra_opts();
        $rendering_recurrence_version = $context->get_rendering_recurrence_version();
		foreach ($data_rules as $index => $rule) {
			$trigger_data = TriggersService\TriggerData::create($context->get_trigger_id(),$rule,$index,$data_rules,$context->get_data_versions()[$index],$context->get_HTTP_request());
			$content = $this->run_triggers($trigger_data);

			if ($content !== false)
				return Filters\TriggerFilter::get_instance()->apply_filters_and_hooks($content, $trigger_data, $extra_opts);
		}
        $default_content = $context->get_default_content();
        if($rendering_recurrence_version!==null)
            return Filters\TriggerFilter::get_instance()->apply_filters_and_hooks($default_content, TriggersService\TriggerData::createFromContext($context,$rendering_recurrence_version), $extra_opts);
		return Filters\TriggerFilter::get_instance()->apply_filters( $default_content , TriggersService\TriggerData::createFromContext($context),$extra_opts );
	}

	private function run_triggers($trigger_data) {
		foreach ($this->triggers as $trigger) {
			if ($trigger->can_handle($trigger_data)) {
				return $trigger->handle($trigger_data);
			}
		}

		return false;
	}
}