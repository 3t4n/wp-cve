<?php

namespace Photonic_Plugin\Options;

use Photonic_Plugin\Core\Photonic;

class Defaults {
	public static function get_options() {
		return [
			// Generic - Generic
			'alternative_shortcode'                           => '',
			'slideshow_library'                               => 'baguettebox',
			'custom_lightbox'                                 => 'fancybox2',
			'custom_lightbox_js'                              => '',
			'custom_lightbox_css'                             => '',
			'disable_photonic_lightbox_scripts'               => '',
			'disable_photonic_slider_scripts'                 => '',
			'lightbox_for_all'                                => '',
			'lightbox_for_videos'                             => '',
			'always_load_scripts'                             => '',
			'js_in_header'                                    => '',
			'disable_editor'                                  => '',
			'disable_editor_post_type'                        => '',
			'disable_flow_editor'                             => '',
			'disable_flow_editor_global'                      => '',
			'disable_on_home_page'                            => '',
			'disable_on_archives'                             => '',
			'nested_shortcodes'                               => '',
			'external_links_in_new_tab'                       => '',
			'css_in_file'                                     => '',

			// Generic - Layouts
			'thumbnail_style'                                 => 'square',
			'standard_thumbnail_effect'                       => 'none',
			'tile_spacing'                                    => '2',
			'tile_min_height'                                 => '200',
			'justified_thumbnail_effect'                      => 'none',
			'masonry_tile_spacing'                            => '2',
			'masonry_min_width'                               => '200',
			'masonry_thumbnail_effect'                        => 'none',
			'mosaic_tile_spacing'                             => '2',
			'mosaic_trigger_width'                            => '200',
			'mosaic_thumbnail_effect'                         => 'zoom',

			// Generic - Native WP Galleries
			'wp_title_caption'                                => 'title',
			'wp_thumbnail_title_display'                      => 'tooltip',
			'wp_disable_title_link'                           => '',
			'wp_layout_engine'                                => 'css',

			// Generic - Slideshow
			'slideshow_prevent_autostart'                     => '',
			'wp_slide_adjustment'                             => 'adapt-height-width',

			// Generic - Overlaid Popup Panel
			'enable_popup'                                    => '',
			'popup_panel_width'                               => '80',
			'flickr_gallery_panel_background'                 => ['color' => '#111111', 'image' => '', 'trans' => '0', 'position' => 'top left', 'repeat' => 'repeat', 'colortype' => 'custom'],
			'flickr_set_popup_thumb_border'                   => self::default_border(),

			// Generic - Photo Template
			'gallery_template_page'                           => '',
			'page_title'                                      => 'replace-if-available',
			'page_content'                                    => 'replace-if-available',

			// Generic - Advanced
			'load_mode'                                       => 'php',
			'ssl_verify_off'                                  => '',
			'curl_timeout'                                    => '30',
			'script_dev_mode'                                 => '',
			'performance_logging'                             => '',
			'debug_on'                                        => '',

			// Flickr
			'flickr_api_key'                                  => '',
			'flickr_api_secret'                               => '',
			'flickr_access_token'                             => '',
			'flickr_token_secret'                             => '',
			'flickr_default_user'                             => '',
			'flickr_media'                                    => 'photos',
			'flickr_thumb_size'                               => 's',
			'flickr_main_size'                                => 'z',
			'flickr_tile_size'                                => 'same',
			'flickr_video_size'                               => 'Video Original',
			'flickr_disable_title_link'                       => '',
			'flickr_title_caption'                            => 'title',
			'flickr_layout_engine'                            => 'css',

			// Flickr - Collections
			'flickr_hide_empty_collection_details'            => '',
			'flickr_hide_collection_thumbnail'                => '',
			'flickr_hide_collection_title'                    => '',
			'flickr_hide_collection_set_count'                => '',

			// Flickr - Multiple Photosets
			'flickr_collection_set_title_display'             => 'tooltip',
			'flickr_hide_collection_set_photos_count_display' => '',
			'flickr_collection_set_per_row_constraint'        => 'padding',
			'flickr_collection_set_constrain_by_count'        => '5',

			// Flickr - Single Photosets
			'flickr_hide_set_thumbnail'                       => '',
			'flickr_hide_set_title'                           => '',
			'flickr_hide_set_photo_count'                     => '',

			// Flickr - Multiple Galleries
			'flickr_gallery_title_display'                    => 'tooltip',
			'flickr_hide_gallery_photos_count_display'        => '',
			'flickr_galleries_per_row_constraint'             => 'padding',
			'flickr_galleries_constrain_by_count'             => '5',

			// Flickr - Single Galleries
			'flickr_hide_gallery_thumbnail'                   => '',
			'flickr_hide_gallery_title'                       => '',
			'flickr_hide_gallery_photo_count'                 => '',

			// Flickr - Photos on main page
			'flickr_photo_title_display'                      => 'tooltip',
			'flickr_photos_per_row_constraint'                => 'padding',
			'flickr_photos_constrain_by_count'                => '5',

			// Flickr - Photos in overlay
			'flickr_photo_pop_title_display'                  => 'tooltip',

			// SmugMug
			'smug_api_key'                                    => '',
			'smug_api_secret'                                 => '',
			'smug_access_token'                               => '',
			'smug_token_secret'                               => '',
			'smug_default_user'                               => '',
			'smug_media'                                      => 'photos',
			'smug_thumb_size'                                 => 'Tiny',
			'smug_main_size'                                  => 'Largest',
			'smug_tile_size'                                  => 'same',
			'smug_video_size'                                 => 'Largest',
			'smug_disable_title_link'                         => '',
			'smug_show_buy_link'                              => '',
			'smug_title_caption'                              => 'title-desc',
			'smug_layout_engine'                              => 'css',

			// SmugMug - Multiple Albums
			'smug_albums_album_title_display'                 => 'tooltip',
			'smug_hide_albums_album_photos_count_display'     => '',
			'smug_hide_password_protected_thumbnail'          => '',
			'smug_album_sort_order'                           => 'Last Updated (Descending)',
			'smug_albums_album_per_row_constraint'            => 'padding',
			'smug_albums_album_constrain_by_count'            => '5',

			// SmugMug - Multiple Photos
			'smug_hide_album_thumbnail'                       => '',
			'smug_hide_album_title'                           => '',
			'smug_hide_album_photo_count'                     => '',
			'smug_photo_title_display'                        => 'tooltip',
			'smug_photos_per_row_constraint'                  => 'padding',
			'smug_photos_constrain_by_count'                  => '5',

			// SmugMug - Multiple Photos in overlay
			'smug_photo_pop_title_display'                    => 'tooltip',

			// SmugMug - Advanced
			'smug_nesting_levels'                             => '5',

			// Google
			'google_client_id'                                => '',
			'google_client_secret'                            => '',
			'google_refresh_token'                            => '',
			'google_media'                                    => 'photos',
			'google_hide_album_photo_count_display'           => '',
			'google_title_caption'                            => 'title',
			'google_chain_queries'                            => '',
			'google_layout_engine'                            => 'css',

			// Google - Photos
			'google_photo_title_display'                      => 'tooltip',
			'google_photos_per_row_constraint'                => 'padding',
			'google_photos_constrain_by_count'                => '5',

			// Google - Photos in overlay
			'google_photo_pop_title_display'                  => 'tooltip',

			// Zenfolio
			'zenfolio_default_user'                           => '',
			'zenfolio_media'                                  => 'photos',
			'zenfolio_disable_title_link'                     => '',
			'zenfolio_photos_per_row_constraint'              => 'padding',
			'zenfolio_photos_constrain_by_count'              => '5',
			'zenfolio_thumb_size'                             => '1',
			'zenfolio_main_size'                              => '4',
			'zenfolio_tile_size'                              => 'same',
			'zenfolio_video_size'                             => '210',
			'zenfolio_photo_title_display'                    => 'tooltip',
			'zenfolio_title_caption'                          => 'title',
			'zenfolio_layout_engine'                          => 'css',

			// Zenfolio - Groups
			'zenfolio_hide_empty_groups'                      => '',
			'zenfolio_hide_group_title'                       => '',
			'zenfolio_link_group_page'                        => '',
			'zenfolio_hide_group_photo_count'                 => '',
			'zenfolio_hide_group_group_count'                 => '',
			'zenfolio_hide_group_set_count'                   => '',

			// Zenfolio - Sets
			'zenfolio_set_title_display'                      => 'tooltip',
			'zenfolio_hide_set_photos_count_display'          => '',
			'zenfolio_hide_password_protected_thumbnail'      => '',
			'zenfolio_sets_per_row_constraint'                => 'padding',
			'zenfolio_sets_constrain_by_count'                => '5',

			// Zenfolio - Single set
			'zenfolio_hide_set_thumbnail'                     => '',
			'zenfolio_hide_set_title'                         => '',
			'zenfolio_link_set_page'                          => '',
			'zenfolio_hide_set_photo_count'                   => '',

			// Instagram
			'instagram_access_token'                          => '',
			'instagram_media'                                 => 'photos',
			'instagram_disable_title_link'                    => '',
			'instagram_carousel_caption_position'             => 'none',
			'instagram_video_size'                            => 'standard_resolution',
			'instagram_tile_size'                             => 'same',
			'instagram_photo_title_display'                   => 'tooltip',

			// Lightbox - common
			'slideshow_mode'                                  => '',
			'slideshow_interval'                              => '5000',
			'lightbox_no_loop'                                => '',
			'deep_linking'                                    => 'no-history',
			'social_media'                                    => '',

			// Lightbox - Colorbox
			'cbox_theme'                                      => '1',
			'cb_transition_effect'                            => 'elastic',
			'cb_transition_speed'                             => '350',

			// Lightbox - FancyBox 1 / 2 / 3
			'fbox_title_position'                             => 'inside',
			'fb3_transition_effect'                           => 'fade',
			'fb3_transition_speed'                            => '366',
			'fb3_disable_zoom'                                => '',
			'fb3_disable_slideshow'                           => '',
			'fb3_show_fullscreen'                             => '',
			'enable_fb3_fullscreen'                           => '',
			'fb3_enable_download'                             => '',
			'fb3_hide_thumbs'                                 => '',
			'enable_fb3_thumbnail'                            => '',
			'fb3_disable_right_click'                         => '',

			// Lightbox - Lightcase
			'lc_transition_effect'                            => 'scrollHorizontal',
			'lc_transition_speed_in'                          => '350',
			'lc_transition_speed_out'                         => '250',
			'lc_enable_shrink'                                => '',

			// Lightbox - LightGallery
			'enable_lg_transitions'                           => '',
			'lg_transition_effect'                            => 'lg-slide',
			'lg_transition_speed'                             => 10,
			'enable_lg_autoplay'                              => '',
			'enable_lg_fullscreen'                            => '',
			'enable_lg_thumbnail'                             => '',
			'enable_lg_zoom'                                  => '',
			'disable_lg_download'                             => '',
			'lg_mobile_show_controls'                         => '',
			'lg_mobile_show_close'                            => '',
			'lg_mobile_show_download'                         => '',
			'lg_hide_bars_delay'                              => '6000',

			// Lightbox - PrettyPhoto
			'pphoto_theme'                                    => 'pp_default',
			'pp_animation_speed'                              => 'Fast',

			// Lightbox - Spotlight
			'sp_download'                                     => '',
			'sp_hide_bars'                                    => '',

			// Lightbox - SwipeBox
			'enable_swipebox_mobile_bars'                     => '',
			'sb_hide_mobile_close'                            => '',
			'sb_hide_bars_delay'                              => 0,

			// Lightbox - VenoBox
			'vb_display_vertical_scroll'                      => '',
			'vb_title_position'                               => 'bottom',
			'vb_title_style'                                  => 'bar'
		];
	}

	public static function get_options_pages() {
		return [
			// Generic
			'generic-how-to',
			'generic-settings',
			'layout-settings',
			'wp-settings',
			'sshow-settings',
			'photos-pop',
			'template-page',
			'advanced-settings',

			// Flickr
			'flickr-how-to',
			'flickr-settings',
			'flickr-collections',
			'flickr-sets',
			'flickr-set',
			'flickr-galleries',
			'flickr-gallery',
			'flickr-photos',
			'flickr-photos-pop',

			// SmugMug
			'smug-how-to',
			'smug-settings',
			'smug-albums',
			'smug-photos',
			'smug-photos-pop',
			'smug-advanced',

			// Google Photos
			'google-settings',
			'google-photos',
			'google-photos-pop',

			// Zenfolio
			'zenfolio-settings',
			'zenfolio-groups',
			'zenfolio-sets',
			'zenfolio-set',

			// Instagram
			'instagram-settings',

			// Lightboxes
			'lb-settings',
			'lb-cb-settings',
			'lb-fb-settings',
			'lb-lc-settings',
			'lb-lg-settings',
			'lb-pp-settings',
			'lb-sp-settings',
			'lb-sb-settings',
			'lb-vb-settings',

		];
	}

	public static function default_border() {
		$ret = [
			'top'    => ['colortype' => 'transparent', 'color' => '#c0c0c0', 'style' => 'none', 'border-width' => 0, 'border-width-type' => 'px'],
			'right'  => ['colortype' => 'transparent', 'color' => '#c0c0c0', 'style' => 'none', 'border-width' => 0, 'border-width-type' => 'px'],
			'bottom' => ['colortype' => 'custom', 'color' => '#c0c0c0', 'style' => 'none', 'border-width' => 0, 'border-width-type' => 'px'],
			'left'   => ['colortype' => 'transparent', 'color' => '#c0c0c0', 'style' => 'none', 'border-width' => 0, 'border-width-type' => 'px'],
		];
		return $ret;
	}

	public static function get_migrated_options() {
		return [
			'slideshow_library' => Photonic::$lightbox_replacements
		];
	}
}
