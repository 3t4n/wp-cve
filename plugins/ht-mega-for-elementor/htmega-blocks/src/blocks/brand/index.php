<?php
	// Exit if accessed directly.
	if ( !defined('ABSPATH') ) { exit; }

	extract($settings);
	$uniqClass 	 = "htmega-block-". $blockUniqId;
	$classes 	 = [ $uniqClass, "htmega-brand", "htmega-brand-" . $brandStyle	];
	$slider && $classes[] = "htmega-slick-slider htmega-brand-carousel";
	if (!$slider) {
		$classes[] = "htmega-grid";
		$columns['desktop'] && $classes[] = "htmega-grid-col-" . $columns['desktop'];
		$columns['tablet'] && $classes[] = "htmega-grid-col-tablet-" . $columns['tablet'];
		$columns['mobile'] && $classes[] = "htmega-grid-col-mobile-" . $columns['mobile'];
	};
	$classNames = implode(" ", $classes);

	$default_image_url = HTMEGA_BLOCK_URL .'src/assets/images/brand.svg';

	$brands = $brandList;

	// Slider Options
	$slider_settings = [];
	if( $slider ){
		$is_rtl = is_rtl();
		$direction = $is_rtl ? 'rtl' : 'ltr';
		$slider_settings = [
			'arrows' => $arrows,
			'dots' => $dots,
			'autoplay' => $autoplay,
			'autoplay_speed' => absint($autoplaySpeed),
			'animation_speed' => absint($animationSpeed),
			'pause_on_hover' => $pauseOnHover,
			'rtl' => $is_rtl,
			'slidesToShow' => $sliderItems,
			'slidesToScroll' => $scrollColumns,
			'infinite' => true,
			'fade' => false,
			'responsive' => [
				[
					'breakpoint' => $tabletWidth,
					'settings' => [
						'slidesToShow' => $tabletDisplayColumns,
						'slidesToScroll' => $tabletScrollColumns
					]
					],
				[
					'breakpoint' => $mobileWidth,
					'settings' => [
						'slidesToShow' => $mobileDisplayColumns,
						'slidesToScroll' => $mobileScrollColumns
					]
				]
			]
		];
	}
	$slider_direction = "dir='ltr'";
	$slider && $slider_direction = "dir='{$direction}'";

	ob_start();
?>
	<div
		class="<?php echo esc_attr($classNames); ?>"
		<?php echo esc_attr($slider_direction); ?>
		<?php echo ($slider) ? "data-settings='" . wp_json_encode($slider_settings) . "'" : '';?>
	>
		<?php
			if( is_array( $brandList ) ){
				foreach ( $brandList as $key => $brand ) {
					$default_img = sprintf('<img src="%s" alt="%s" width="300" height="300" />', esc_url($default_image_url), esc_attr($brand['title']) );
					$brand_image = !empty($brand['image']['id']) ? wp_get_attachment_image($brand['image']['id'], 'medium', false, [
						"alt" => esc_attr($brand['title'])
					]) : $default_img;

					$brand_link = isset($brand['link']) && !empty($brand['link']) ? "href='" . esc_url($brand['link']) ."'" : '';
					$newTab = isset($brand['newTab']) && $brand['newTab'] ? 'target=_blank' : '';
					$noFollow = isset($brand['noFollow']) && $brand['noFollow'] ? 'rel=nofollow' : '';
					echo sprintf('<div class="htmega-brand-item"><a %s %s %s>%s</a></div>',
						$brand_link,
						esc_attr($newTab),
						esc_attr($noFollow),
						$brand_image
					);
				}
			}
		?>
	</div>
<?php
	echo ob_get_clean();
?>