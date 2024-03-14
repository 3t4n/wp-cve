<?php
// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

$card_classes = ['htmega-accordion-card'];
$settings['open'] && $card_classes[] = 'htmega-accordion-card-active';

add_filter('safe_style_css', function( $styles ) {
    $styles[] = 'display';
    return $styles;
});

ob_start();
?>
<div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
	<div class="htmega-accordion-card-header">
		<?php echo "<" . tag_escape($settings['titleTag']) ." class='htmega-accordion-card-title'>" . esc_html($settings['title']) . "</" . tag_escape($settings['titleTag']) . ">"; ?>
		<div class="htmega-accordion-card-indicator">
			<span class="inactive <?php echo esc_attr($settings['iconInActive']); ?>"></span>
			<span class="active <?php echo esc_attr($settings['iconActive']); ?>"></span>
		</div>
	</div>
	<div class="htmega-accordion-card-body" style="<?php echo !$settings['open'] ? 'display: none;' : ''; ?>">
		<div class="htmega-accordion-card-body-inner">
			<?php echo wp_kses_post($content); ?>
		</div>
	</div>
</div>
<?php
echo ob_get_clean();
?>