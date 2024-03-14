<?php

require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/shortcodes/class-qiaddonsforelementor-framework-shortcodes.php';
require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/shortcodes/class-qiaddonsforelementor-framework-shortcode.php';
require_once QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/shortcodes/promotion-shortcodes/helper.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_ADMIN_PATH . '/inc/shortcodes/translators/*/*-translator.php' ) as $translator ) {
	require_once $translator;
}
