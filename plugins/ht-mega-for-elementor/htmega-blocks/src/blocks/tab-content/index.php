<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}
add_filter('safe_style_css', function( $styles ) {
    $styles[] = 'display';
    return $styles;
});
$activeClass = $settings['id'] === $settings['activeTab'] ? 'htmega-tab-pane-active': '';
$activeDisplay = $settings['id'] === $settings['activeTab'] ? 'block': 'none';
echo "<div class='" . esc_attr(trim('htmega-tab-pane ' . $activeClass)) . "' data-tab-id='" . esc_attr($settings['id']) . "' style='display: " . esc_attr($activeDisplay) . "'>";
echo wp_kses_post($content);
echo "</div>";