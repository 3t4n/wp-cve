<?php

if( !defined('ABSPATH') ) {
	echo "Well done! Try Again";
	die();
}

function show_cats_on_frontend() {

	$cat_mod = get_theme_mod('rpfg_cat_setting');

	if ($cat_mod == 1) {

		$cat = '<div class="related-post-categories">' . do_shortcode( '[post_categories before="Categories: "]' ) . '</div>';

	}

	return $cat;

}

function show_tags_on_frontend() {

	$tag_mod = get_theme_mod( 'rpfg_tag_setting');

	if ($tag_mod == 1) {

		$tag = '<div class="related-post-tags">' . do_shortcode( '[post_tags before="Tags: "]' ) . '</div>';

	}

	return $tag;

}

function show_date_on_frontend() {

	$date_mod = get_theme_mod( 'rpfg_date_setting');

	if ($date_mod == 1) {

		$date = '<div class="related-post-date">' . do_shortcode( '[post_date]' ) . '</div>';
		
	}

	return $date;

}

