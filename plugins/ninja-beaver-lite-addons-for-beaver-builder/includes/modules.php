<?php
$modules = [
	'modules/njba-accordion/njba-accordion.php',
	'modules/njba-alert-box/njba-alert-box.php',
	'modules/njba-advance-cta/njba-advance-cta.php',
	'modules/njba-contact-form/njba-contact-form.php',
	'modules/njba-button/njba-button.php',
	'modules/njba-flip-box/njba-flip-box.php',
	'modules/njba-gallery/njba-gallery.php',
	'modules/njba-heading/njba-heading.php',
	'modules/njba-icon-img/njba-icon-img.php',
	'modules/njba-highlight-box/njba-highlight-box.php',
	'modules/njba-image-hover/njba-image-hover.php',
	'modules/njba-image-hover-two/njba-image-hover-two.php',
	'modules/njba-image-panels/njba-image-panels.php',
	'modules/njba-img-separator/njba-img-separator.php',
	'modules/njba-infolist/njba-infolist.php',
	'modules/njba-infobox/njba-infobox.php',
	'modules/njba-infobox-two/njba-infobox-two.php',
	'modules/njba-logo-grid-carousel/njba-logo-grid-carousel.php',
	'modules/njba-opening-hours/njba-opening-hours.php',
	'modules/njba-post-grid/njba-post-grid.php',
	'modules/njba-post-list/njba-post-list.php',
	'modules/njba-price-box/njba-price-box.php',
	'modules/njba-quote-box/njba-quote-box.php',
	'modules/njba-separator/njba-separator.php',
	'modules/njba-slider/njba-slider.php',
	'modules/njba-social-share/njba-social-share.php',
	'modules/njba-spacer/njba-spacer.php',
	'modules/njba-static-map/njba-static-map.php',
	'modules/njba-subscribe-form/njba-subscribe-form.php',
	'modules/njba-tabs/njba-tabs.php',
	'modules/njba-teams/njba-teams.php',
	'modules/njba-testimonials/njba-testimonials.php',
	'modules/njba-facebook-button/njba-facebook-button.php',
	'modules/njba-facebook-comments/njba-facebook-comments.php',
	'modules/njba-facebook-embed/njba-facebook-embed.php',
	'modules/njba-facebook-page/njba-facebook-page.php',
	'modules/njba-twitter-buttons/njba-twitter-buttons.php',
	'modules/njba-twitter-grid/njba-twitter-grid.php',
	'modules/njba-twitter-timeline/njba-twitter-timeline.php',
	'modules/njba-twitter-tweet/njba-twitter-tweet.php',
];

$theme_dir = is_child_theme() ? get_stylesheet_directory() : get_template_directory();

foreach ( $modules as $module ) {
	$module_file_path = $theme_dir . '/ninja-beaver-pro/' . $module;
	if ( file_exists( $module_file_path ) ) {
		require_once $module_file_path;
	}
	require_once NJBA_MODULE_DIR . $module;
}
