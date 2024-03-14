<?php

namespace MyCustomizer\WooCommerce\Connector\Api\Interfaces;

use MyCustomizer\WooCommerce\Connector\Auth\MczrAccess;

MczrAccess::isAuthorized();

interface MczrApi {

	public function registerRestRoute();
}
