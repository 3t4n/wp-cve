<?php

namespace IfSo\PublicFace\Services\TriggersService\Handlers;

require_once( plugin_dir_path ( __DIR__ ) . 'chain-handler-base.class.php');
require_once(IFSO_PLUGIN_BASE_DIR . 'services/license-service/license-service.class.php');

use IfSo\Services\LicenseService;

class LicenseValidationHandler extends ChainHandlerBase {
	public function handle($context) {
		LicenseService\LicenseService::get_instance()->edd_ifso_is_license_valid();

		return $this->handle_next($context);
	}
}