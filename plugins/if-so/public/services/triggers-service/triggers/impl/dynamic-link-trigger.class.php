<?php

namespace IfSo\PublicFace\Services\TriggersService\Triggers;

require_once( plugin_dir_path ( __DIR__ ) . 'trigger-base.class.php');

class DynamicLinkTrigger extends TriggerBase {
	public function __construct() {
		parent::__construct('url');
	}

	public function handle($trigger_data) {
		$rule = $trigger_data->get_rule();
		$content = $trigger_data->get_content();

		$compare = $rule['compare'];

        $ifsoParam = $trigger_data->get_HTTP_request()->getParam('ifso');

		if ( !empty($ifsoParam) && $ifsoParam == $compare )
			return $content;

		return false;
	}
}