<?php
/**
 * Theme: Bromley
 * Theme Url: https://creativemarket.com/BinaryMoon/108643-Broadsheet-Newspaper-Theme?u=BinaryMoon
 *
 * @package: styleguide
 */

$css = <<<CSS
	body {
		font-family: {{font-body}};
		color: {{color-theme-background-fg-0}};
	}
	h1, h2, h3, h4, h5, h6 {
		font-family: {{font-headers}};
	}
	a {
		color: {{color-key-bg-0}};
	}
	a:hover {
		color: {{color-key-bg-2}};
	}
	blockquote {
		border-color: {{color-key-bg-0}};
	}
	input[type="submit"],
	button,
	#respond p.form-submit #submit {
		border-color: {{color-key-bg-2}};
		background: {{color-key-bg-0}};
		color: {{color-key-fg-0}};
	}
	input[type="submit"]:hover,
	button:hover,
	#respond p.form-submit #submit:hover {
		border-color: {{color-key-bg-4}};
		background: {{color-key-bg-2}};
		color: {{color-key-fg-2}};
	}
	.showcase .item {
		border-right-color: {{color-theme-background-bg-0}};
	}
	.main .postnav .next a:before,
	.main .postnav .prev a:before,
	.main .postnav .next a:after,
	.main .postnav .prev a:after,
	.masthead,
	.widget h3.widgettitle,
	form.searchform button.searchsubmit {
		background-color: {{color-key-bg-0}};
		color: {{color-key-fg-0}};
	}
	.masthead .menu li a {
		color: {{color-key-fg-0}};
	}
	.masthead .menu li a:hover,
	.masthead .menu li.current-menu-item a,
	.masthead .menu li.current_page_item a {
		border-color: {{color-key-bg-2}};
		color: {{color-key-fg-2}};
	}
	.masthead .menu li a.sf-with-ul:after {
		border-top-color: {{color-key-bg-2}};
	}
	#footer-widgets .widgets .widget h3.widgettitle {
		background-color: {{color-theme-background-bg-2}};
		color: {{color-theme-background-fg-2}};
	}
	#footer-widgets {
		background: {{color-theme-background-bg+1}};
		color: {{color-theme-background-fg+1}};
	}
CSS;

add_theme_support(
	'styleguide',
	array(
		'colors' => array(
			'key' => array(
				'label' => __( 'Key Color', 'styleguide' ),
				'default' => '#E74C3C',
			),
		),
		'fonts' => array(
			'headers' => array(
				'label' => __( 'Header Font', 'styleguide' ),
				'default' => 'Source+Sans+Pro',
			),
			'body' => array(
				'label' => __( 'Body Font', 'styleguide' ),
				'default' => 'Source+Sans+Pro',
			),
		),
		'css' => $css,
		'dequeue' => array(
			'bromley-fonts',
		),
	)
);
