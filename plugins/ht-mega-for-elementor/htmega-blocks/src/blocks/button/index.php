<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	$card_classes = [
		"htmega-block-{$settings['blockUniqId']}",
		"htmega-button",
		"htmega-button-{$settings['style']}",
		$settings['size'] && $settings['size'] !== '' ? "htmega-button-{$settings['size']}" : "",
		$settings['effect'] && $settings['effect'] !== '' ? "htmega-button-{$settings['effect']}" : "",
	];

	$icon = isset($settings['icon']) && !empty($settings['icon']) ? "<span class='" . esc_attr($settings['icon']) . "'></span>" : null;

	ob_start();
	$link = isset($settings['link']) && !empty($settings['link']) ? "href='".esc_url($settings['link'])."'" : "";
	$newTab = isset($settings['newTab']) && $settings['newTab'] ? 'target=_blank' : '';
	$noFollow = isset($settings['noFollow']) && $settings['noFollow'] ? 'rel=nofollow' : '';
	?>
		<a class="<?php echo esc_attr(trim(implode(' ', $card_classes))); ?>" <?php echo ($link); ?> <?php echo esc_attr($newTab); ?> <?php echo esc_attr($noFollow); ?> >
			<?php echo ($settings['iconPosition'] === 'left' || $settings['iconPosition'] === '') ? $icon : ''; ?>
			<?php echo esc_html($settings['label']); ?>
			<?php echo $settings['iconPosition'] === 'right' ? $icon : ''; ?>
		</a>
	<?php
	echo ob_get_clean();
?>