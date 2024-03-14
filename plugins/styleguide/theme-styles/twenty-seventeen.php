<?php
/**
 * Theme: Twenty Seventeen
 * Theme Url: https://wordpress.org/themes/twentyseventeen
 *
 * @package: styleguide
 */

$css = <<<CSS
	body,
	button,
	input,
	select,
	textarea {
		font-family: {{font-body}};
		font-weight: {{font-body-weight}};
	}
	.site-title,
	h1, h2, h3, h4, h5, h6 {
		font-family: {{font-headers}};
		font-weight: {{font-headers-weight}};
	}
CSS;

add_theme_support(
	'styleguide',
	array(
		'fonts' => array(
			'headers' => array(
				'label' => __( 'Header Font', 'styleguide' ),
				'default' => '',
			),
			'body' => array(
				'label' => __( 'Body Font', 'styleguide' ),
				'default' => '',
			),
		),
		'css' => $css,
	)
);
