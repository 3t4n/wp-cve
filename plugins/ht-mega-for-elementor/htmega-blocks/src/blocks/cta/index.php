<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }
	
	$ctaClasses = implode(' ', [
		"htmega-block-{$settings['blockUniqId']}",
		"htmega-cta",
		"htmega-cta-{$settings['style']}",
		isset($settings['align']) && $settings['align'] === 'full' ? 'alignfull' : '',
		isset($settings['align']) && $settings['align'] === 'wide' ? 'alignwide' : ''
	]);

	ob_start();
	?>
		<div class="<?php echo esc_attr($ctaClasses); ?>">
		<?php
			echo "<div class='htmega-cta-content'>";
			if($settings['showSubTitle']) {
				printf('<span class="htmega-cta-sub-title">%s</span>', wp_kses_post(html_entity_decode($settings['subTitle'])));
			}
			if($settings['showTitle']) {
				printf('<%1$s class="htmega-cta-title">%2$s</%1$s>', tag_escape($settings['titleTag']), wp_kses_post(html_entity_decode($settings['title'])));
			}
			if($settings['showDescription']) {
				printf('<p class="htmega-cta-desc">%s</p>', wp_kses_post(html_entity_decode($settings['description'])));
			}
			echo "</div>";
			if($settings['showPrimaryButton'] || $settings['showSecondaryButton']) {
				echo "<div class='htmega-cta-button-group'>";
				if($settings['showPrimaryButton']) {
					printf(
						"<a class='htmega-cta-button htmega-cta-button-primary' href='%s' %s %s >%s</a>",
						esc_url($settings['primaryButton']['link']),
						esc_attr($settings['primaryButton']['newTab'] ? 'target=_blank' : false),
						esc_attr($settings['primaryButton']['noFollow'] ? 'rel=nofollow' : false),
						esc_html($settings['primaryButton']['label'])
					);
				}
				if($settings['showSecondaryButton']) {
					printf(
						"<a class='htmega-cta-button htmega-cta-button-secondary' href='%s' %s %s >%s</a>",
						esc_url($settings['secondaryButton']['link']),
						esc_attr($settings['secondaryButton']['newTab'] ? 'target=_blank' : false),
						esc_attr($settings['secondaryButton']['noFollow'] ? 'rel=nofollow' : false),
						esc_html($settings['secondaryButton']['label'])
					);
				}
				echo "</div>";
			}
		?>
		</div>
	<?php
	echo ob_get_clean();
?>