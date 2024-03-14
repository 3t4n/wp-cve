<?php

declare( strict_types=1 );

use WcMipConnector\Service\SystemService;

defined('ABSPATH') || exit;

$systemService = new SystemService();
$systemService->installWebHookUrl();

return true;