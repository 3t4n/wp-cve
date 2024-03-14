<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	$classNames = [
		"htmega-block-{$settings['blockUniqId']}",
		"htmega-tab",
		"htmega-tab-{$settings['style']}",
	];
	$classes = trim(implode(' ', $classNames));
	ob_start();
	?>
		<div class="<?php echo esc_attr($classes); ?>">
			<ul class='htmega-tab-nav'>
				<?php 
					foreach ($settings['tabs'] as $key => $tab) {
						printf (
							'<li data-tab-target="%1$s" class="htmega-tab-nav-item %2$s">
								%3$s
								%4$s
							</li>',
							esc_attr($key),
							$settings['activeTab'] === $key ? esc_attr('htmega-tab-nav-item-active') : '',
							$tab['icon'] && $tab['icon'] !== '' && !$settings['hideIcon'] ? "<span class='" . esc_attr($tab['icon']) . "'></span>" : null,
							esc_html($tab['label'])
						);
					}
				?>
			</ul>
			<div class='htmega-tab-content'>
				<?php echo wp_kses_post($content); ?> 
			</div>
		</div>
	<?php
	echo ob_get_clean();
?>