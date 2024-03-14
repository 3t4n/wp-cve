<?php
/**
 * Theme: Label
 * Theme Url: https://prothemedesign.com/theme/label/
 *
 * @package: styleguide
 */

$css = <<<CSS
	html, body {
		font-family: {{font-body}};
		font-weight: {{font-body-weight}};
		color: {{color-theme-background-fg-0}};
	}
	h1, h2, h3, h4, h5, h6 {
		font-family: {{font-headers}};
		font-weight: {{font-headers-weight}};
	}
	.showcase .item-inner .entry-text .read-more,
	a {
		color: {{color-link-bg-0}};
	}
	a:hover {
		color: {{color-link-bg-2}};
	}
	.showcase {
		border-color: {{color-theme-background-bg-0}};
	}
	.menu-page-container ul.menu-wrap a {
		color: {{color-theme-background-fg-0}};
	}
	.menu-page-container .menu li ul {
		background: {{color-theme-background-fg-0}};
	}
	.menu-page-container .menu li ul li a,
	.menu-page-container .menu li ul li a:hover {
		color: {{color-theme-background-bg-0}};
	}
	.showcase .arrow,
	.the-content,
	.content-posts article .entry,
	.showcase .skip-slider {
		background: {{color-theme-background-bg-0}};
		color: {{color-theme-background-fg-0}};
	}
	.image-navigation .post-title,
	.comment-navigation .post-title,
	.post-navigation .post-title,
	h1, h2, h3, h4, h5, h6,
	h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
		color: {{color-theme-background-fg-0}};
	}
	.showcase h2,
	.menu-drawer,
	.menu-drawer h1, .menu-drawer h2, .menu-drawer h3,
	.content-posts article.sticky .entry,
	.content-posts article.sticky h2 a {
		color: black;
	}
	.showcase .arrow.arrow-next:before {
		border-left-color: {{color-theme-background-fg-0}};
	}
	.showcase .arrow.arrow-prev:before {
		border-right-color: {{color-theme-background-fg-0}};
	}
	.showcase .skip-slider:after {
		border-top-color: {{color-theme-background-fg-0}};
	}
	blockquote {
		background: {{color-theme-background-bg+2}};
		color: {{color-theme-background-fg+2}};
	}
	.infinite-scroll #infinite-handle button,
	input[type=submit],
	a.button,
	button {
		background: {{color-link-bg-0}};
		color: {{color-link-fg-0}};
	}
	.infinite-scroll #infinite-handle button:focus,
	.infinite-scroll #infinite-handle button:hover,
	input[type=submit]:focus,
	input[type=submit]:hover,
	a.button:focus,
	a.button:hover,
	button:focus,
	button:hover {
		background: {{color-link-bg-2}};
		color: {{color-link-fg-2}};
	}
	.menu-drawer a {
		color: {{color-theme-title-bg-0}};
	}
	.menu-drawer a:hover {
		color: {{color-theme-title-bg-2}};
	}
	.taxonomies:before,
	.taxonomies:after,
	.menu-drawer .menu:before,
	.menu-drawer .menu:after,
	.menu-drawer .site-description:before,
	.menu-drawer .site-description:after,
	.infinite-scroll #infinite-handle:before,
	#footer .jetpack-social-navigation:after,
	#footer .jetpack-social-navigation:before {
		border-color: rgba( 128, 128, 128, 0.4 );
	}
	.taxonomies:before,
	#footer .jetpack-social-navigation:after {
		background: {{color-theme-background-bg-0}};
	}
	.menu-drawer .site-description:before {

	}
CSS;

add_theme_support(
	'styleguide',
	array(
		'colors' => array(
			'link' => array(
				'label' => __( 'Link Color', 'styleguide' ),
				'default' => '#626e7a',
			),
		),
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
		'dequeue' => array(),
	)
);
