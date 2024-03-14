<?php

function tonjoo_ecae_load_default( &$options ) {
	if ( ! isset( $options['width'] ) || ! is_numeric( $options['width'] ) ) {
		$options['width'] = 500;
	}
	if ( ! isset( $options['excerpt_method'] ) ) {
		$options['excerpt_method'] = 'paragraph';
	}
	if ( ! isset( $options['show_image'] ) ) {
		$options['show_image'] = 'yes';
	}
	if ( ! isset( $options['clickable_image'] ) ) {
		$options['clickable_image'] = 'no';
	}
	if ( ! isset( $options['image_position'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_position'] = 'none';
	}
	if ( ! isset( $options['image_width_type'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_width_type'] = 'manual';
	}
	if ( ! isset( $options['image_width'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_width'] = '300';
	}
	if ( ! isset( $options['image_padding_top'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_padding_top'] = '5';
	}
	if ( ! isset( $options['image_padding_right'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_padding_right'] = '5';
	}
	if ( ! isset( $options['image_padding_bottom'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_padding_bottom'] = '5';
	}
	if ( ! isset( $options['image_padding_left'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_padding_left'] = '5';
	}
	if ( ! isset( $options['image_thumbnail_size'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['image_thumbnail_size'] = 'post-thumbnail';
	}
	if ( ! isset( $options['home'] ) ) {
		$options['home'] = 'yes';
	}
	if ( ! isset( $options['front_page'] ) ) {
		$options['front_page'] = 'yes';
	}
	if ( ! isset( $options['search'] ) ) {
		$options['search'] = 'yes';
	}
	if ( ! isset( $options['archive'] ) ) {
		$options['archive'] = 'yes';
	}
	if ( ! isset( $options['excerpt_in_page'] ) ) {
		$options['excerpt_in_page'] = '';
	}
	if ( ! isset( $options['excerpt_in_post_type'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['excerpt_in_post_type'] = '';
	}
	if ( ! isset( $options['excerpt_in_cat'] ) || ! function_exists( 'is_ecae_premium_exist' ) ) {
		$options['excerpt_in_cat'] = '';
	}
	if ( ! isset( $options['justify'] ) ) {
		$options['justify'] = 'no';
	}
	if ( ! isset( $options['font_size'] ) ) {
		$options['font_size'] = '';
	}
	if ( ! isset( $options['read_more'] ) || '' == $options['read_more'] ) {
		$options['read_more'] = 'read more';
	}

	if ( ! isset( $options['strip_shortcode'] ) ) {
		$options['strip_shortcode'] = 'yes';
	}

	if ( ! isset( $options['strip_empty_tags'] ) ) {
		$options['strip_empty_tags'] = 'yes';
	}

	if ( ! isset( $options['disable_on_feed'] ) ) {
		$options['disable_on_feed'] = 'yes';
	}

	if ( ! isset( $options['special_method'] ) ) {
		$options['special_method'] = 'no';
	}

	if ( ! isset( $options['button_display_option'] ) ) {
		$button_display_option = 'normal';

		if ( isset( $options['always_show_read_more'] ) && 'yes' == $options['always_show_read_more'] ) {
			$button_display_option = 'always_show';
		}

		$options['button_display_option'] = $button_display_option;
	}

	if ( ! isset( $options['read_more_text_before'] ) ) {
		$options['read_more_text_before'] = '';
	}

	if ( ! isset( $options['readmore_inline'] ) ) {
		$options['readmore_inline'] = 'no';
	}

	if ( ! isset( $options['text_after_content'] ) ) {
		$options['text_after_content'] = '';
	}

	if ( ! isset( $options['read_more_align'] ) ) {
		$options['read_more_align'] = 'left';
	}

	if ( ! isset( $options['extra_html_markup'] ) ) {
		$options['extra_html_markup'] = 'span';
	}

	if ( ! isset( $options['show_image'] ) ) {
		$options['show_image'] = 'yes';
	}

	if ( ! isset( $options['custom_css'] ) ) {
		$options['custom_css'] = '';
	}

	if ( ! isset( $options['custom_html'] ) ) {
		$options['custom_html'] = '';
	}

	if ( ! isset( $options['button_skin'] ) ) {
		$options['button_skin'] = 'none';
	}

	if ( ! isset( $options['button_font'] ) ) {
		$options['button_font'] = '';
	}

	if ( ! isset( $options['button_font_size'] ) ) {
		$options['button_font_size'] = '14';
	}

	if ( ! isset( $options['location_settings_type'] ) ) {
		$options['location_settings_type'] = 'basic';
	}

	/**
	 * Home Page Excerpt
	 */
	if ( ! isset( $options['advanced_home'] ) ) {
		$options['advanced_home'] = 'all';
	}
	if ( ! isset( $options['advanced_home_width'] ) || ! is_numeric( $options['advanced_home_width'] ) ) {
		$options['advanced_home_width'] = 0;
	}
	if ( ! isset( $options['home_post_type'] ) ) {
		$options['home_post_type'] = array();
	} else {
		$options['home_post_type'] = unserialize( $options['home_post_type'] );
	}
	if ( ! isset( $options['home_category'] ) ) {
		$options['home_category'] = array();
	} else {
		$options['home_category'] = unserialize( $options['home_category'] );
	}

	/**
	 * Front Page Excerpt
	 */
	if ( ! isset( $options['advanced_frontpage'] ) ) {
		$options['advanced_frontpage'] = 'all';
	}
	if ( ! isset( $options['advanced_frontpage_width'] ) || ! is_numeric( $options['advanced_frontpage_width'] ) ) {
		$options['advanced_frontpage_width'] = 0;
	}
	if ( ! isset( $options['frontpage_post_type'] ) ) {
		$options['frontpage_post_type'] = array();
	} else {
		$options['frontpage_post_type'] = unserialize( $options['frontpage_post_type'] );
	}
	if ( ! isset( $options['frontpage_category'] ) ) {
		$options['frontpage_category'] = array();
	} else {
		$options['frontpage_category'] = unserialize( $options['frontpage_category'] );
	}

	/**
	 * Archive Page Excerpt
	 */
	if ( ! isset( $options['advanced_archive'] ) ) {
		$options['advanced_archive'] = 'all';
	}
	if ( ! isset( $options['advanced_archive_width'] ) || ! is_numeric( $options['advanced_archive_width'] ) ) {
		$options['advanced_archive_width'] = 0;
	}
	if ( ! isset( $options['archive_post_type'] ) ) {
		$options['archive_post_type'] = array();
	} else {
		$options['archive_post_type'] = unserialize( $options['archive_post_type'] );
	}
	if ( ! isset( $options['archive_category'] ) ) {
		$options['archive_category'] = array();
	} else {
		$options['archive_category'] = unserialize( $options['archive_category'] );
	}

	/**
	 * Search Page Excerpt
	 */
	if ( ! isset( $options['advanced_search'] ) ) {
		$options['advanced_search'] = 'all';
	}
	if ( ! isset( $options['advanced_search_width'] ) || ! is_numeric( $options['advanced_search_width'] ) ) {
		$options['advanced_search_width'] = 0;
	}
	if ( ! isset( $options['search_post_type'] ) ) {
		$options['search_post_type'] = array();
	} else {
		$options['search_post_type'] = unserialize( $options['search_post_type'] );
	}
	if ( ! isset( $options['search_category'] ) ) {
		$options['search_category'] = array();
	} else {
		$options['search_category'] = unserialize( $options['search_category'] );
	}

	/**
	 * Page Excerpt
	 */
	if ( ! isset( $options['advanced_page_main'] ) ) {
		$options['advanced_page_main'] = 'disable';
	}
	if ( ! isset( $options['advanced_page_main_width'] ) || ! is_numeric( $options['advanced_page_main_width'] ) ) {
		$options['advanced_page_main_width'] = 0;
	}
	if ( ! isset( $options['excerpt_in_page_advanced'] ) ) {
		$options['excerpt_in_page_advanced'] = array();
	} else {
		$options['excerpt_in_page_advanced'] = unserialize( $options['excerpt_in_page_advanced'] );
	}
	if ( ! isset( $options['advanced_page'] ) ) {
		$options['advanced_page'] = array();
	} else {
		$options['advanced_page'] = unserialize( $options['advanced_page'] );
	}
	if ( ! isset( $options['page_post_type'] ) ) {
		$options['page_post_type'] = array();
	} else {
		$page_post_type = unserialize( $options['page_post_type'] );

		if ( is_array( $page_post_type ) ) {
			$options['page_post_type'] = array();

			$i = 0;
			foreach ( $page_post_type as $key => $value ) {
				$options['page_post_type'][ $i++ ] = $value;
			}
		}
	}
	if ( ! isset( $options['page_category'] ) || empty( $options['page_category'] ) ) {
		$options['page_category'] = array();
	} else {
		$page_category = unserialize( $options['page_category'] );

		if ( is_array( $page_category ) ) {
			$options['page_category'] = array();

			$i = 0;
			foreach ( $page_category as $key => $value ) {
				$options['page_category'][ $i++ ] = $value;
			}
		}
	}

	/**
	 * License Code
	 */
	if ( ! isset( $options['license_key'] ) ) {
		$options['license_key'] = '';
	}

	return $options;
}
