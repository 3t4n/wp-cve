<?php
/**
 * Theme: Carmack
 * Theme Url: https://prothemedesign.com/theme/carmack/
 *
 * @package: styleguide
 */

$css = <<<CSS
	h1, h2, h3, h4, h5, h6,
	body {
		font-family: {{font-body}};
	}
	a {
		color: {{color-link-bg-0}};
	}
	a:hover {
		color: {{color-link-bg-2}};
	}

	/* blockquote {
		border-color: {{color-key-bg-0}};
	} */

	.content-posts article.format-quote .permalink a,
	.sticky-post,
	.widget.jetpack_subscription_widget,
	.the-content .button,
	.menu-overlay .close-overlay,
	.showcase .item h2 a.entry,
	.pagination span.current,
	.woocommerce-store-notice,
	p.demo_store,
	.woocommerce #respond input#submit.alt,
	.woocommerce a.button.alt,
	.woocommerce button.button.alt,
	.woocommerce input.button.alt,
	body #eu-cookie-law.negative input,
	body #eu-cookie-law.positive input,
	a.button,
	button,
	input[type=submit]	{
		background-color: {{color-key-bg-0}};
		color: {{color-key-fg-0}};
	}

	a.post-edit-link:focus,
	a.post-edit-link:hover,
	.widget.jetpack_subscription_widget input[type=submit],
	.the-content .wp-block-button__link:focus,
	.the-content .wp-block-button__link:hover,
	.the-content .button:focus,
	.the-content .button:hover,
	.woocommerce #respond input#submit.alt:focus,
	.woocommerce #respond input#submit.alt:hover,
	.woocommerce a.button.alt:focus,
	.woocommerce a.button.alt:hover,
	.woocommerce button.button.alt:focus,
	.woocommerce button.button.alt:hover,
	.woocommerce input.button.alt:focus,
	.woocommerce input.button.alt:hover,
	.woocommerce #respond input#submit:focus,
	.woocommerce #respond input#submit:hover,
	.woocommerce a.button:focus,
	.woocommerce a.button:hover,
	.woocommerce button.button:focus,
	.woocommerce button.button:hover,
	.woocommerce input.button:focus,
	.woocommerce input.button:hover,
	body #eu-cookie-law.negative input:focus,
	body #eu-cookie-law.negative input:hover,
	body #eu-cookie-law.positive input:focus,
	body #eu-cookie-law.positive input:hover,
	a.button:focus,
	a.button:hover,
	button:focus,
	button:hover,
	.widget_blog_subscription button,
	input[type=submit]:focus,
	input[type=submit]:hover {
		background-color: {{color-key-fg-0}};
		color: {{color-key-bg-0}};
	}

	.the-content .wp-block-button__link:not(.has-background),
	.the-content .has-carmack-yellow-background-color,
	.projects-terms a.current-page,
	form.search-form button.search-submit:focus,
	.showcase nav .tab:focus,
	.showcase nav .tab:hover,
	.content-comments ol.comment-list li.comment.bypostauthor > article .fn,
	.masthead .branding .site-title {
		background-color: {{color-key-bg-0}};
	}

	.the-content .has-carmack-yellow-color,
	.sidebar-footer .widget .widget-title,
	.sidebar-overlay .widget .widget-title {
		color: {{color-key-bg-0}};
	}

	.widget.jetpack_subscription_widget .widget-title {
		color: {{color-key-fg-0}};
	}

	@media only screen and (max-width: 782px) {
		.masthead .branding {
		    background: {{color-key-bg-0}};
		}
	}
CSS;

add_theme_support(
	'styleguide',
	array(
		'colors' => array(
			'key' => array(
				'label' => __( 'Key Color', 'styleguide' ),
				'default' => '#ffee00',
			),
			'link' => array(
				'label' => __( 'Link Color', 'styleguide' ),
				'default' => '#006194',
			),
		),
		'fonts' => array(
			'body' => array(
				'label' => __( 'Font', 'styleguide' ),
				'default' => 'Merriweather+Sans',
			),
		),
		'css' => $css,
		'dequeue' => array(
			'carmack-fonts',
		),
	)
);
