<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	extract($settings);
	$uniqClass 	 = "htmega-block-". $blockUniqId;
	$classes 	 = [ $uniqClass, "htmega-image-grid", "htmega-image-grid-{$imageGridStyle}"	];
	$classes[] = "htmega-grid";
	$columns['desktop'] && $classes[] = "htmega-grid-col-{$columns['desktop']}";
	$columns['tablet'] && $classes[] = "htmega-grid-col-tablet-{$columns['tablet']}";
	$columns['mobile'] && $classes[] = "htmega-grid-col-mobile-{$columns['mobile']}";
	$classNames = implode(" ", $classes);


	ob_start();
?>

	<div class="<?php echo esc_attr($classNames); ?>">
	<?php
		if( is_array( $imageGridList ) ){
			foreach ( $imageGridList as $key => $item ) {

				$link = isset($item['link']) && !empty($item['link']) ? "href='" . esc_url($item['link']) ."'" : "";
				$target = isset($item['newTab']) && $item['newTab'] ? 'target=_blank' : '';
				$rel = isset($item['noFollow']) && $item['noFollow'] ? 'rel=nofollow' : '';
		
				if(empty($item['image']) || empty($item['image']['url'])) {
					$item['image'] = [
						"url" => HTMEGA_BLOCK_URL .'src/assets/images/team.jpg',
						"width" => 370,
						"height" => 370
					];
				}

				$image = !empty($item['image']['id']) ? wp_get_attachment_image($item['image']['id'], 'large', false, [
					"alt" => esc_attr($item['title'])
				]) : sprintf('<img src="%s" alt="%s" width="%s" height="%s" />',
					esc_url($item['image']['url']),
					esc_attr($item['title']),
					esc_attr($item['image']['width']),
					esc_attr($item['image']['height']),
				);

				$linkShow = isset($linkText) && !empty($linkText) && isset($item['link']) && !empty($item['link']) ? sprintf('<a class="htmega-image-grid-item-link" %s alt="%s" %s %s>%s</a>',
					$link,
					esc_attr($item['title']),
					esc_attr($target),
					esc_attr($rel),
					esc_html($linkText)
				) : '';

				$imageGridItem = "<div class='htmega-image-grid-item'>
					<a class='htmega-image-grid-item-thumbnail' {$link}" . esc_attr($target) . " " . esc_attr($rel) .">{$image}</a>
					<div class='htmega-image-grid-item-content'>
						<h2 class='htmega-image-grid-item-title'>" . esc_html($item['title']) . "</h2>
						<p class='htmega-image-grid-item-desc'>" . esc_html($item['desc']) ."</p>
						{$linkShow}
					</div>
				</div>";

				echo wp_kses_post($imageGridItem);
			}
		}
	?>
	</div>
<?php
	echo ob_get_clean();
?>