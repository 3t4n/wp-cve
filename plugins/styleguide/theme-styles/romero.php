<?php
/**
 * Theme: Romero
 * Theme Url: https://prothemedesign.com/theme/romero/
 *
 * @package: styleguide
 */

$css = <<<CSS
	.masthead .menu-toggle,
  	ol.comment-list li.comment #respond #cancel-comment-reply-link,
	ol.comment-list li.trackback #respond #cancel-comment-reply-link,
	ol.comment-list li.pingback #respond #cancel-comment-reply-link,
	.main div.pd-rating h3.jp-relatedposts-headline,
	.main div#jp-relatedposts h3.jp-relatedposts-headline,
	.main div.sharedaddy h3.jp-relatedposts-headline,
	.main div.pd-rating h3.sd-title,
	.main div#jp-relatedposts h3.sd-title,
	.main div.sharedaddy h3.sd-title,
	body {
		font-family: {{font-body}};
		font-weight: {{font-body-weight}};
	}
	h1, h2, h3, h4, h5, h6, blockquote {
		font-family: {{font-headers}};
		font-weight: {{font-headers-weight}};
	}
	::-moz-selection {
		background: {{color-key-bg-0}};
	}
	::selection {
		background: {{color-key-bg-0}};
	}
	.woocommerce #respond input#submit,
	.woocommerce a.button,
	.woocommerce button.button,
	.woocommerce input.button,
	.woocommerce #respond input#submit.alt,
	.woocommerce a.button.alt,
	.woocommerce button.button.alt,
	.woocommerce input.button.alt,
	#respond p.form-submit #submit,
	.main .pagination span.current,
	article .post-lead-category a,
	.masthead .menu-primary #sidebar-menu-toggle:before,
	.main .contributor a.contributor-posts-link {
		background: {{color-key-bg-0}};
		color: {{color-key-fg-0}};
	}
	.main .post-navigation .nav-previous a:hover,
	.main .post-navigation .nav-next a:hover,
	article.post-archive h2.entry-title a:hover,
	.menu-social-links ul li a:hover:before,
	.widget a:hover,
	a {
		color: {{color-key-bg-0}};
	}
	input[type=submit],
	.infinite-scroll #infinite-handle span,
	#sidebar-menu {
		background: {{color-key-bg-0}};
	}
	.widget.widget_flickr #flickr_badge_uber_wrapper td a,
	.widget.widget_flickr #flickr_badge_wrapper td a {
		color: {{color-key-bg-0}};
	}
	#sidebar-menu .widget a {
		color: {{color-key-fg-0}};
	}
	input[type=text]:focus,
	input[type=password]:focus,
	input[type=email]:focus,
	input[type=url]:focus,
	input[type=search]:focus,
	input.text:focus,
	textarea:focus,
	input.settings-input:focus {
		border-color: {{color-key-bg-0}};
	}
	.widget:before {
		border-top-color: {{color-key-bg-0}};
	}
	article .post-lead-category a:hover,
	.masthead .menu-primary #sidebar-menu-toggle:hover:before {
		background: {{color-key-bg-2}};
		color: {{color-key-fg-2}};
	}
	header.woocommerce-products-header,
	header.header-overlap {
		background: {{color-theme-background-bg+3}};
	}
	.sidebar-main .widget h3.widgettitle,
	.sidebar-main .widget a {
		color: {{color-theme-background-fg-0}};
	}
	.sidebar-main .widget {
		color: {{color-theme-background-bg+3}};
	}

CSS;

add_theme_support(
	'styleguide',
	array(
		'fonts' => array(
			'headers' => array(
				'label' => __( 'Header Font', 'styleguide' ),
				'default' => 'Oswald',
			),
			'body' => array(
				'label' => __( 'Body Font', 'styleguide' ),
				'default' => 'Open Sans',
			),
		),
		'colors' => array(
			'key' => array(
				'label' => __( 'Highlight', 'styleguide' ),
				'default' => '#4A90E2',
			),
		),
		'css' => $css,
	)
);
