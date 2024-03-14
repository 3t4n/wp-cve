<?php
defined('ABSPATH') || exit;
$title_tag = $settings['shopengine_title_header_size']['desktop'];

printf(
	'<div class="shopengine shopengine-widget">
		<div class="shopengine-heading"><%s class="heading-title">%s</%s></div>
	</div>',
	esc_html($title_tag),
	wp_kses_post($settings['shopengine_title_heading_input']['desktop']),
	esc_html($title_tag)
);