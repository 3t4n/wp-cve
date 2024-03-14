<?php
defined('ABSPATH') || exit;

use ShopEngine\Utils\Helper;

$title_tag = !empty($settings['shopengine_archive_title_header_size']['desktop']) ? $settings['shopengine_archive_title_header_size']['desktop'] : 'h2';

printf(
	'<div class="shopengine shopengine-widget">
		<div class="shopengine-archive-title"><%s class="archive-title">%s</%s></div>
	</div>',
	wp_kses($title_tag, Helper::get_kses_array()),
	esc_html(woocommerce_page_title(false)),
	wp_kses($title_tag, Helper::get_kses_array())
);
