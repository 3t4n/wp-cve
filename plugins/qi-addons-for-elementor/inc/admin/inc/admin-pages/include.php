<?php

require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/admin-pages/class-qiaddonsforelementor-admin-general-page.php';
require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/admin-pages/class-qiaddonsforelementor-admin-sub-pages.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/admin-pages/sub-pages/*/include.php' ) as $page ) {
	require_once $page;
}
