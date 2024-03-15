<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

get_header();

if ( xpro_theme_builder_is_singular_enabled() ) {
	xpro_theme_builder_render_singular();
} elseif ( xpro_theme_builder_is_archive_enabled() ) {
	xpro_theme_builder_render_archive();
} else {
	the_content();
}

get_footer();
