<?php

namespace Woo_MP;

use YeEasyAdminNotices\V1\AdminNotice;

defined( 'ABSPATH' ) || die;

require WOO_MP_PATH . '/includes/autoloader.php';

( new Autoloader( 'Woo_MP\\', WOO_MP_PATH . '/includes/' ) )->register();

if ( ! class_exists( AdminNotice::class ) ) {
    require WOO_MP_PATH . '/libraries/admin-notices-1.1/AdminNotice.php';
}

( new Woo_MP() )->init();
