<?php

include_once QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/team-member/class-qiaddonsforelementor-team-member-shortcode.php';

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_SHORTCODES_PATH . '/team-member/variations/*/include.php' ) as $variation ) {
	include_once $variation;
}
