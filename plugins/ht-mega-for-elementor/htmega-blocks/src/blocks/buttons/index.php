<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	$classNames = [
		"htmega-block-{$settings['blockUniqId']}",
		"htmega-buttons",
		$settings['fullWidth'] ? "htmega-buttons-full-width" : "",
		$settings['stackBreakPoint'] && !empty($settings['stackBreakPoint']) ? "htmega-buttons-stack-{$settings['stackBreakPoint']}" : "",
		$settings['alignment'] && !empty($settings['alignment']) ? "htmega-buttons-{$settings['alignment']}" : "",
	];
	$classes = implode(' ', $classNames);
	echo "<div class='" . esc_attr(trim($classes)) . "'>" . wp_kses_post($content) ."</div>";