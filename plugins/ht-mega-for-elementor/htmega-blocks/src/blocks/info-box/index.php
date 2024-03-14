<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	extract($settings);
	$uniqClass 	 = "htmega-block-". $blockUniqId;
	$classes 	 = [
		$uniqClass,
		"htmega-info-box",
		"htmega-info-box-{$boxStyle}",
		"htmega-grid",
	];
	$columns['desktop'] && $classes[] = "htmega-grid-col-{$columns['desktop']}";
	$columns['tablet'] && $classes[] = "htmega-grid-col-tablet-{$columns['tablet']}";
	$columns['mobile'] && $classes[] = "htmega-grid-col-mobile-{$columns['mobile']}";
	$classNames = implode(" ", $classes);

	$title_shape = $boxStyle === '3' ? '<span class="htmega-info-box-item-title-shape"><svg width="54" height="8" viewBox="0 0 67 10" fill="none" xmlns="http://www.w3.org/2000/svg">
	<path d="M1.5 9L5 2.5L9 8.5L12 2.5L15.5 8.5L17.25 5.5L19 2.5L23 8.5L26 2.5L30 8.5L33.5 2.5L37 8.5L40.5 2.5L44 8.5L47.5 2.5L51.5 8.5L55 2.5L58.5 8.5L62 2.5L65.5 9" stroke="currentColor" stroke-width="2"/>
	</svg></span>' : '';
	$box_shape_svg = $boxStyle === '4' ? '<div class="htmega-info-box-item-shape"><svg width="420" height="80" viewBox="0 0 420 80" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M237.5 22.0285C336.459 -13.2178 395.788 3.86408 420 12.1733V79.9996H0V12.5C33.3219 37.6207 138.541 57.2749 237.5 22.0285Z" fill="currentColor" fill-opacity="0.3"/>
		<path d="M182.5 22.0285C83.5411 -13.2178 24.2123 3.86408 0 12.1733V79.9996H420V12C386.678 37.1207 281.459 57.2749 182.5 22.0285Z" fill="currentColor" fill-opacity="0.3"/>
	</svg></div>' : '';

	ob_start();
?>

	<div class="<?php echo esc_attr($classNames); ?>">
	<?php
		if( is_array( $infoBoxList ) ){
			foreach ( $infoBoxList as $key => $item ) {

				$link = isset($item['link']) && !empty($item['link']) ? "href='" . esc_url($item['link']) . "'" : null;
				$target = isset($item['newTab']) && $item['newTab'] ? 'target=_blank' : null;
				$rel = isset($item['noFollow']) && $item['noFollow'] ? 'rel=nofollow' : null;
		
				if(empty($item['image']) || empty($item['image']['url'])) {
					$item['image'] = [
						"url" => HTMEGA_BLOCK_URL .'src/assets/images/info-box.png',
						"width" => 150,
						"height" => 150
					];
				}

				$image = !empty($item['image']['id']) ? wp_get_attachment_image($item['image']['id'], 'medium', false, [
					"alt" => esc_attr($item['title'])
				]) : sprintf('<img src="%s" alt="%s" width="%s" height="%s" />',
					esc_url($item['image']['url']),
					esc_attr($item['title']),
					esc_attr($item['image']['width']),
					esc_attr($item['image']['height']),
				);

				$title = !empty($item['title']) ? "<" . tag_escape($titleTag) ." class='htmega-info-box-item-title'>
					<a {$link} " . esc_attr($target) . " " . esc_attr($rel) . ">" . esc_html($item['title']) ."</a>
					{$title_shape}
				</" . tag_escape($titleTag) .">" : "";
				$desc = !empty($item['desc']) ? "<p class='htmega-info-box-item-desc'>" . esc_html($item['desc']) . "</p>" : "";
				$button = !$hideButton && isset($item['linkText']) && !empty($item['linkText']) ? "<div class='htmega-info-box-item-link'><a {$link} " . esc_attr($target) . " " . esc_attr($rel) . ">" . esc_html($item['linkText']) . "</a></div>" : "";

				$infoBoxItem = "<div class='htmega-info-box-item'>
					<a class='htmega-info-box-item-thumbnail' {$link} " . esc_attr($target) . " " . esc_attr($rel) . ">{$image}</a>
					<div class='htmega-info-box-item-content'>
						{$title}
						{$desc}
						{$button}
					</div>
					{$box_shape_svg}
				</div>";

				echo ($infoBoxItem);
			}
		}
	?>
	</div>
<?php
	echo ob_get_clean();
?>