<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	$card_classes = [
		"htmega-block-{$settings['blockUniqId']}",
		"htmega-team",
		"htmega-team-{$settings['teamStyle']}",
	];

	if(empty($settings['image']) || empty($settings['image']['url'])) {
		$settings['image'] = [
			"url" => HTMEGA_BLOCK_URL .'src/assets/images/team.jpg',
			"width" => 370,
			"height" => 450
		];
	}

	ob_start();
	?>
		<div class="<?php echo esc_attr(implode(' ', $card_classes)); ?>">
			<div class="htmega-team-thumbnail">
				<img src="<?php echo esc_url($settings['image']['url']) ?>" alt="<?php echo esc_attr($settings['name']); ?>" width="<?php echo esc_attr( $settings['image']['width'] ) ?>" height="<?php echo esc_attr( $settings['image']['height'] ) ?>" />
			</div>
			
			<div class='htmega-team-content'>
				<div class='htmega-team-content-inner'>
					<<?php echo tag_escape($settings['nameTag']); ?> class='htmega-team-name'><?php echo esc_html($settings['name']); ?></<?php echo tag_escape($settings['nameTag']); ?>>
					<span class='htmega-team-designation'><?php echo esc_html($settings['designation']) ?></span>
					<?php echo $settings['showBio'] ? "<p class='htmega-team-bio'>" . esc_html($settings['bio']) ."</p>" : ''; ?>
				</div>
				<ul class='htmega-team-social'>
				<?php 
					foreach ($settings['socials'] as $social) {
						$label = $social['label'];
						$icon = $social['icon'];
						$link = $social['link'];
						$target = $settings['newTab'] ? 'target=_blank' : '';
						$nofollow = $settings['noFollow'] ? 'rel=nofollow' : '';
						echo "<li><a href='" . esc_url($link) ."' " . esc_attr($target) . " " . esc_attr($nofollow) . " aria-label='" . esc_attr($label) . "'><span class='" . esc_attr($icon) . "'></span></a></li>";
					}
				?>
				</ul>
			</div>
		</div>
	<?php
	echo ob_get_clean();
?>