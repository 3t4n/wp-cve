<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');

class SkipHandler extends ChainHandlerBase {
	public function handle($context) {
		$post_status = get_post_status($context->get_trigger_id());

		if ($post_status && (($post_status == 'draft')||($post_status == 'trash')))
			return '';
		else
			return $this->handle_next($context);
	}
}