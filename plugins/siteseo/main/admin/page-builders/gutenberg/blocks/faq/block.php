<?php

if (! defined('ABSPATH')) {
	die();
}

function siteseo_register_block_faq() {
	$path = SITESEO_DIR_PATH . 'main/public/editor/blocks/faq/index.asset.php';
	if(!file_exists($path)){
		return;
	}

	$asset_file = include_once $path;
	wp_register_script(
		'siteseo-faq-block',
		SITESEO_URL_PUBLIC . '/editor/blocks/faq/index.js',
		$asset_file['dependencies'],
		$asset_file['version']
	);

	wp_register_style(
		'siteseo-faq-block',
		SITESEO_URL_PUBLIC . '/editor/blocks/faq/index.css',
		'',
		$asset_file['version']
	);

	register_block_type('siteseo/faq-block', [
		'editor_script' => 'siteseo-faq-block',
		'editor_style'  => 'siteseo-faq-block',
		'attributes' => array(
			'faqs' => array(
				'type'	=> 'array',
				'default' => array( '' ),
				'items'   => array(
					'type' => 'object',
				),
			),
			'listStyle' => array(
				'type' => 'string',
				'default' => 'none'
			),
			'titleWrapper' => array(
				'type' => 'string',
				'default' => 'p'
			),
			'imageSize' => array(
				'type' => 'string',
				'default' => 'thumbnail'
			),
			'showFAQScheme' => array(
				'type' => 'boolean',
				'default' => false
			),
			'showAccordion' => array(
				'type' => 'boolean',
				'default' => false
			),
			'isProActive' => array(
				'type'	=> 'boolean',
				'default' => is_plugin_active( 'siteseo-pro/siteseo-pro.php' )
			)
		),
		'render_callback' => 'siteseo_block_faq_render_frontend',
	]);
}

function siteseo_block_faq_render_frontend($attributes){
	
	if (is_admin() || defined('REST_REQUEST')) {
		return;
	}

	switch ($attributes['titleWrapper']) {
		case 'h2':
			$titleTag = '<h2 class="siteseo-faq-question">';
			$titleCloseTag = '</h2>';
			break;
		case 'h3':
			$titleTag = '<h3 class="siteseo-faq-question">';
			$titleCloseTag = '</h3>';
			break;
		case 'h4':
			$titleTag = '<h4 class="siteseo-faq-question">';
			$titleCloseTag = '</h4>';
			break;
		case 'h5':
			$titleTag = '<h5 class="siteseo-faq-question">';
			$titleCloseTag = '</h5>';
			break;
		case 'h6':
			$titleTag = '<h6 class="siteseo-faq-question">';
			$titleCloseTag = '</h6>';
			break;
		case 'p':
			$titleTag = '<p class="siteseo-faq-question">';
			$titleCloseTag = '</p>';
			break;
		default:
			$titleTag = '<div class="siteseo-faq-question">';
			$titleCloseTag = '</div>';
			break;
	}

	switch ($attributes['listStyle']) {
		case 'ul':
			$listStyleTag = '<ul class="siteseo-faqs">';
			$listStyleCloseTag = '</ul>';
			$listItemStyle = '<li class="siteseo-faq">';
			$listItemStyleClosingTag = '</li>';
			break;
		case 'ol':
			$listStyleTag = '<ol class="siteseo-faqs">';
			$listStyleCloseTag = '</ol>';
			$listItemStyle = '<li class="siteseo-faq">';
			$listItemStyleClosingTag = '</li>';
			break;
		default:
			$listStyleTag = '<div class="siteseo-faqs">';
			$listStyleCloseTag = '</div>';
			$listItemStyle = '<div class="siteseo-faq">';
			$listItemStyleClosingTag = '</div>';
			break;
	}

	$entities = [];

	ob_start(); ?>
	<?php echo wp_kses_post($listStyleTag); ?>
		<?php
			foreach ($attributes['faqs'] as $faq) :
				$i = rand();
				if (empty($faq['question'])) {
					continue;
				}

				$entity = [
					'@type' => 'Question',
					'name' => $faq['question'],
					'acceptedAnswer' => [
						'@type' => 'Answer',
						'text' => ! empty($faq['answer']) ? $faq['answer'] : ''
					]
				];
				$entities[] = $entity;

				$accordion = $attributes['showAccordion'];

				if ($accordion) {
					// Load our inline CSS only once
					if (!isset($css)) {
						$css = '.siteseo-hide {display: none;}.siteseo-accordion-button{width:100%}';
						$css = apply_filters( 'siteseo_faq_block_inline_css', $css );
						echo '<style>'.esc_html($css).'</style>';
					}
					// Our simple accordion JS
					wp_enqueue_script('siteseo-accordion', SITESEO_URL_PUBLIC . '/editor/blocks/faq/accordion.js', '', SITESEO_VERSION, true);
				}

				$image = '';
				$image_alt = '';
				if ( isset( $faq['image'] ) && is_int( $faq['image'] ) ) {
					$image = wp_get_attachment_image_src( $faq['image'], $attributes['imageSize'] );
					$image_alt = get_post_meta($faq['image'], '_wp_attachment_image_alt', true);
				}

				$image_url = '';
				if ( isset( $image ) && ! empty( $image ) ) {
					$image_url = $image[0];
				} ?>
				<?php echo wp_kses_post($listItemStyle); ?>
					<?php if ($accordion) { ?>
						<div id="siteseo-faq-title-<?php echo esc_attr($i); ?>" class="siteseo-wrap-faq-question">
							<button class="siteseo-accordion-button" type="button" aria-expanded="false" aria-controls="siteseo-faq-answer-<?php echo esc_attr($i); ?>">
					<?php } ?>
					<?php echo wp_kses_post($titleTag . $faq['question'] . $titleCloseTag); ?>
					<?php if ($accordion) { ?>
							</button>
						</div>
					<?php } ?>

					<?php if ($accordion) { ?>
						<div id="siteseo-faq-answer-<?php echo esc_attr($i); ?>" class="siteseo-faq-answer siteseo-hide" aria-labelledby="siteseo-faq-title-<?php echo esc_attr($i); ?>">
					<?php } else { ?>
						<div class="siteseo-faq-answer">
					<?php } ?>
					<?php if (! empty($image_url)): ?>
							<div class="siteseo-faq-answer-image">
								<img src="<?php echo esc_url($image_url); ?>" alt="<?php echo esc_attr($image_alt); ?>">
							</div>
						<?php endif; ?>
						<?php if (! empty($faq['answer'])): ?>
							<p class="siteseo-faq-answer-desc"><?php echo wp_kses_post($faq['answer']); ?></p>
						<?php endif; ?>
					</div>
				<?php echo wp_kses_post($listItemStyleClosingTag);
				?>
			<?php endforeach; ?>
	<?php echo wp_kses_post($listStyleCloseTag);

	// FAQ Schema
	if ( (bool) $attributes['isProActive'] && (int) $attributes['showFAQScheme'] ) {
		$schema = '<script type="application/ld+json">
				{
				"@context": "https://schema.org",
				"@type": "FAQPage",
				"mainEntity": '. wp_json_encode($entities) . '
				}
			</script>';

		echo wp_kses(apply_filters('siteseo_schemas_faq_html', $schema), ['script' => ['type' => true]]);
	}
	$html = apply_filters('siteseo_faq_block_html', ob_get_clean());
	return $html;
}
