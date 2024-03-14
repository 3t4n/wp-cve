<?php

/*
Plugin Name: SnapScan Online Payments
Plugin URI: https://www.snapscan.co.za
Description: Accept online payments from the SnapScan App and / or Card Payments.
Version: 1.5.16
Text Domain: snapscan
*/

include dirname(__FILE__) . '/common/admin-notice.php';
include dirname(__FILE__) . '/common/snap-logger.php';
include dirname(__FILE__) . '/SnapScan/woocommerce-snapscan.php';
include dirname(__FILE__) . '/EcentricGateway/card-woocommerce-gateway.php';
