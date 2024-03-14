<?php
/**
 * The freemium panels: Free and Pro panels. (Save serial for Pro, check license, etc)
 *
 * @package Fish and Ships
 * @version 1.1.9
 */

defined( 'ABSPATH' ) || exit;

global $Fish_n_Ships;

$options = $Fish_n_Ships->get_options();

if ($Fish_n_Ships->im_pro()) {

} else {
	$html = '
	</table>
	<div class="fns-clearfix">
	<div id="wc-fns-freemium-panel" class="free-version ' . ($options['close_freemium'] < time() ? 'opened' : 'closed') . '">
		<h2>Fish and Ships (free)</h2>
		<a href="#" class="close_panel"><span class="dashicons dashicons-dismiss"></span></a>
		<a href="#" class="open_panel"><span class="dashicons dashicons-plus-alt"></span></a>
		<div class="wrap_content">';
		
		$html .= '<p>' . esc_html__('This plugin is free and updates through the standard WordPress plugins repository. This free version has some features limited.', 'fish-and-ships') . '</p>';
		
		$html .= '<div class="can_close"><p>' . wp_kses(
				__('There is a <strong>professional</strong> version with amazing extra features. It works in the same way as free version, simply giving you more options: New <strong>selection methods</strong>, the ability to set <strong>different group criteria</strong> on every selection, many more <strong>special actions</strong> and of course, upgrades and priority support.', 'fish-and-ships'),
				 array('strong'=>array())
				) . '</p>';
		
		$html .= '<p>' . esc_html__('You can get more detailed information, compare free vs pro features, license prices and conditions, etc. here:', 'fish-and-ships') . '</p>';
		
		$html .= '</div>
		</div>
		<p class="center go_button"><a href="https://www.wp-centrics.com" class="button-primary" target="_blank">WC Fish and Ships Pro</a></p>
	</div>
	<div id="wc-fns-table_envelope">
	<table class="form-table">';
}