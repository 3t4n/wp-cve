<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	$defaultSeparator = HTMEGA_BLOCK_URL . 'src/assets/images/section-heading-separator.png';

	$headingClasses = implode(' ', [
		"htmega-block-{$settings['blockUniqId']}",
		"htmega-section-heading",
		"htmega-section-heading-{$settings['style']}",
	]);

	$placeholderClasses = [
		"htmega-section-heading-placeholder"
	];
	if(!empty($settings['placeholderVPosition'])) {
		$placeholderClasses[] =  "htmega-section-heading-placeholder-{$settings['placeholderVPosition']}";
	};
	if(!empty($settings['placeholderHPosition'])) {
		$placeholderClasses[] =  "htmega-section-heading-placeholder-{$settings['placeholderHPosition']}";
	};
	$placeholderClasses = implode(' ', $placeholderClasses);

	$titleSeparatorImage = "<img src='" . esc_url($defaultSeparator) . "' />";
	if(!empty($settings['titleSeparatorImage']) && $settings['titleSeparatorImage']['url'] && !empty($settings['titleSeparatorImage']['url'])) {
		$titleSeparatorImage = "<img src=" . esc_url($settings['titleSeparatorImage']['url']) . " />";
	}
	$titleSeparator = "<span class='htmega-section-heading-separator htmega-section-heading-title-separator'>
		<span class='htmega-section-heading-separator-bar'>
			{$titleSeparatorImage}
		</span>
	</span>";

	$subTitleSeparatorImage = "<img src='" . esc_url($defaultSeparator) . "' />";
	if(!empty($settings['subTitleSeparatorImage']) && $settings['subTitleSeparatorImage']['url'] && !empty($settings['subTitleSeparatorImage']['url'])) {
		$subTitleSeparatorImage = "<img src=" . esc_url($settings['subTitleSeparatorImage']['url']) . " />";
	}
	$subTitleSeparator = "<span class='htmega-section-heading-separator htmega-section-heading-sub-title-separator'>
		<span class='htmega-section-heading-separator-bar'>
			{$subTitleSeparatorImage}
		</span>
	</span>";

	$descriptionSeparatorImage = "<img src='" . esc_url($defaultSeparator) . "' />";
	if(!empty($settings['descriptionSeparatorImage']) && $settings['descriptionSeparatorImage']['url'] && !empty($settings['descriptionSeparatorImage']['url'])) {
		$descriptionSeparatorImage = "<img src=" . esc_url($settings['descriptionSeparatorImage']['url']) . " />";
	}
	$descriptionSeparator = "<span class='htmega-section-heading-separator htmega-section-heading-description-separator'>
		<span class='htmega-section-heading-separator-bar'>
			{$descriptionSeparatorImage}
		</span>
	</span>";
	
	ob_start();
	?>
		<div class="<?php echo esc_attr(trim($headingClasses)); ?>">
			<?php
				if($settings['showPlaceholder']) {
					echo sprintf(
						'<span class="%s">%s</span>',
                        esc_attr($placeholderClasses),
                        wp_kses_post($settings['placeholder'])
					);
				}
				if($settings['showSubTitle'] && ($settings['subTitlePosition'] === 'before' || empty($settings['subTitlePosition']))) {
					if($settings['subTitleSeparator'] && $settings['subTitleSeparatorPosition'] == 'before') {
						echo $subTitleSeparator;
					}
					$subTitle = html_entity_decode($settings['subTitle']);
					echo sprintf(
						'<span class="htmega-section-heading-sub-title">
							%s
						</span>',
                        wp_kses_post($subTitle)
					);
					if($settings['subTitleSeparator'] && ($settings['subTitleSeparatorPosition'] == 'after' || empty($settings['subTitleSeparatorPosition']))) {
						echo $subTitleSeparator;
					}
				}
				if($settings['showTitle']) {
					if($settings['titleSeparator'] && $settings['titleSeparatorPosition'] == 'before') {
						echo $titleSeparator;
					}
					$title = html_entity_decode($settings['title']);
					echo sprintf(
						'<%s class="htmega-section-heading-title">
							%s
						</%s>',
                        tag_escape($settings['titleTag']),
                        wp_kses_post($title),
                        tag_escape($settings['titleTag'])
					);
					if($settings['titleSeparator'] && ($settings['titleSeparatorPosition'] == 'after' || empty($settings['titleSeparatorPosition']))) {
						echo $titleSeparator;
					}
				}
				if($settings['showSubTitle'] && $settings['subTitlePosition'] === 'after') {
					if($settings['subTitleSeparator'] && $settings['subTitleSeparatorPosition'] == 'before') {
						echo $subTitleSeparator;
					}
					$subTitle = html_entity_decode($settings['subTitle']);
					echo sprintf(
						'<span class="htmega-section-heading-sub-title">
							%s
						</span>',
                        wp_kses_post($subTitle)
					);
					if($settings['subTitleSeparator'] && ($settings['subTitleSeparatorPosition'] == 'after' || empty($settings['subTitleSeparatorPosition']))) {
						echo $subTitleSeparator;
					}
				}
				if($settings['showDescription']) {
					if($settings['descriptionSeparator'] && $settings['descriptionSeparatorPosition'] == 'before') {
						echo $descriptionSeparator;
					}
					$description = html_entity_decode($settings['description']);
					echo sprintf(
						'<span class="htmega-section-heading-description">
							%s
						</span>',
                        wp_kses_post($description)
					);
					if($settings['descriptionSeparator'] && ($settings['descriptionSeparatorPosition'] == 'after' || empty($settings['descriptionSeparatorPosition']))) {
						echo $descriptionSeparator;
					}
				}
			?>
		</div>
	<?php
	echo ob_get_clean();
?>