<?php

defined('ABSPATH') || exit;

use WcMipConnector\Service\SystemService;

$systemService = new SystemService();
$systemService->installMipConnector();