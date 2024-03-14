<?php
/**
 * Widget API: Display Podcast from feed url class
 *
 * @package podcast-player
 * @since 1.0.0
 */

namespace Podcast_Player\Backend\Inc;

use Podcast_Player\Frontend\Inc\Display;
use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Helper\Functions\Validation as Validation_Fn;
use Podcast_Player\Helper\Functions\Utility as Utility_Fn;
use PP_Pro\Helper\Functions\Getters as PP_Get_Fn;

/**
 * Class used to display podcast episodes from a feed url.
 *
 * @since 1.0.0
 *
 * @see WP_Widget
 */
class Widget extends \WP_Widget {

	/**
	 * Holds widget settings defaults, populated in constructor.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Is this the premium version.
	 *
	 * @since  1.2.0
	 * @access protected
	 * @var    bool
	 */
	protected $is_premium = true;

	/**
	 * Sets up a new Blank widget instance.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		// Set widget instance settings default values.
		$this->defaults = array(
			'title'                => '',
			'sortby'               => 'sort_date_desc',
			'filterby'             => '',
			'feed_url'             => '',
			'number'               => 10,
			'offset'               => 0,
			'podcast_menu'         => '',
			'main_menu_items'      => 0,
			'cover_image'          => '',
			'desc'                 => '',
			'error'                => '',
			'pp_teaser_text'       => '',
			'pp_excerpt_length'    => 18,
			'pp_excerpt_unit'      => '',
			'pp_grid_columns'      => 3,
			'pp_header_default'    => '',
			'pp_list_default'      => '',
			'pp_hide_header'       => '',
			'pp_hide_cover'        => '',
			'pp_hide_title'        => '',
			'pp_hide_description'  => '',
			'pp_hide_subscribe'    => '',
			'pp_hide_search'       => '',
			'pp_hide_author'       => '',
			'pp_hide_content'      => '',
			'pp_hide_loadmore'     => '',
			'pp_hide_download'     => '',
			'pp_hide_social'       => '',
			'pp_hide_featured'     => '',
			'pp_accent_color'      => '',
			'pp_display_style'     => '',
			'pp_aspect_ratio'      => 'squr',
			'pp_crop_method'       => 'centercrop',
			'pp_fetch_method'      => 'feed',
			'pp_post_type'         => 'post',
			'pp_taxonomy'          => '',
			'pp_terms'             => array(),
			'pp_podtitle'          => '',
			'pp_audiosrc'          => '',
			'pp_audiotitle'        => '',
			'pp_audiolink'         => '',
			'pp_ahide_download'    => '',
			'pp_ahide_social'      => '',
			'pp_audio_message'     => '',
			'pp_play_frequency'    => 0,
			'pp_start_time'        => array( 0, 0, 0 ),
			'pp_start_when'        => 'start',
			'pp_msg_text'          => esc_html__( 'Episode will play after this message.', 'podcast-player' ),
			'pp_fonts'             => '',
			'pp_bgcolor'           => '',
			'pp_txtcolor'          => '',
			'pp_apple_sub'         => '',
			'pp_google_sub'        => '',
			'pp_spotify_sub'       => '',
			'pp_breaker_sub'       => '',
			'pp_castbox_sub'       => '',
			'pp_castro_sub'        => '',
			'pp_iheart_sub'        => '',
			'pp_amazon_sub'        => '',
			'pp_overcast_sub'      => '',
			'pp_pocketcasts_sub'   => '',
			'pp_podcastaddict_sub' => '',
			'pp_podchaser_sub'     => '',
			'pp_radiopublic_sub'   => '',
			'pp_soundcloud_sub'    => '',
			'pp_stitcher_sub'      => '',
			'pp_tunein_sub'        => '',
			'pp_youtube_sub'       => '',
			'pp_bullhorn_sub'      => '',
			'pp_podbean_sub'       => '',
			'pp_playerfm_sub'      => '',
			'pp_elist'             => array( '' ),
			'pp_slist'             => array( '' ),
			'pp_catlist'           => array( '' ),
			'pp_edisplay'          => '',
			'pp_seasons'           => '',
			'pp_episodes'          => '',
		);

		$this->is_premium = apply_filters( 'podcast_player_is_premium', false );

		// Set the widget options.
		$widget_ops = array(
			'classname'                   => 'podcast_player',
			'description'                 => esc_html__( 'Create a podcast player widget.', 'podcast-player' ),
			'customize_selective_refresh' => true,
		);
		parent::__construct( 'podcast_player_widget', esc_html__( 'Podcast Player', 'podcast-player' ), $widget_ops );
	}

	/**
	 * Outputs the content for the current widget instance.
	 *
	 * @since 2.8.0
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {

		$args['widget_id'] = isset( $args['widget_id'] ) ? $args['widget_id'] : $this->id;

		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );

		$title = ! empty( $instance['title'] ) ? $instance['title'] : '';

		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );

		echo $args['before_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}

		$display_args = apply_filters(
			'podcast_player_widget_display',
			array(
				'url'               => $instance['feed_url'],
				'sortby'            => $instance['sortby'],
				'filterby'          => $instance['filterby'],
				'number'            => $instance['number'],
				'offset'            => $instance['offset'],
				'menu'              => $instance['podcast_menu'],
				'main_menu_items'   => $instance['main_menu_items'],
				'image'             => $instance['cover_image'],
				'description'       => $instance['desc'],
				'header-default'    => $instance['pp_header_default'],
				'list-default'      => $instance['pp_list_default'],
				'hide-header'       => $instance['pp_hide_header'],
				'hide-title'        => $instance['pp_hide_title'],
				'hide-cover-img'    => $instance['pp_hide_cover'],
				'hide-description'  => $instance['pp_hide_description'],
				'hide-subscribe'    => $instance['pp_hide_subscribe'],
				'hide-search'       => $instance['pp_hide_search'],
				'hide-author'       => $instance['pp_hide_author'],
				'hide-content'      => $instance['pp_hide_content'],
				'hide-loadmore'     => $instance['pp_hide_loadmore'],
				'hide-download'     => $instance['pp_hide_download'],
				'hide-social'       => $instance['pp_hide_social'],
				'hide-featured'     => $instance['pp_hide_featured'],
				'accent-color'      => $instance['pp_accent_color'],
				'display-style'     => $instance['pp_display_style'],
				'apple-sub'         => $instance['pp_apple_sub'],
				'google-sub'        => $instance['pp_google_sub'],
				'spotify-sub'       => $instance['pp_spotify_sub'],
				'breaker-sub'       => $instance['pp_breaker_sub'],
				'castbox-sub'       => $instance['pp_castbox_sub'],
				'castro-sub'        => $instance['pp_castro_sub'],
				'iheart-sub'        => $instance['pp_iheart_sub'],
				'amazon-sub'        => $instance['pp_amazon_sub'],
				'overcast-sub'      => $instance['pp_overcast_sub'],
				'pocketcasts-sub'   => $instance['pp_pocketcasts_sub'],
				'podcastaddict-sub' => $instance['pp_podcastaddict_sub'],
				'podchaser-sub'     => $instance['pp_podchaser_sub'],
				'radiopublic-sub'   => $instance['pp_radiopublic_sub'],
				'soundcloud-sub'    => $instance['pp_soundcloud_sub'],
				'stitcher-sub'      => $instance['pp_stitcher_sub'],
				'tunein-sub'        => $instance['pp_tunein_sub'],
				'youtube-sub'       => $instance['pp_youtube_sub'],
				'bullhorn-sub'      => $instance['pp_bullhorn_sub'],
				'podbean-sub'       => $instance['pp_podbean_sub'],
				'playerfm-sub'      => $instance['pp_playerfm_sub'],
				'teaser-text'       => $instance['pp_teaser_text'],
				'excerpt-length'    => $instance['pp_excerpt_length'],
				'excerpt-unit'      => $instance['pp_excerpt_unit'],
				'from'              => 'widget',
			),
			$instance
		);

		$display = Display::get_instance();
		$display->init( $display_args, false );
		echo $args['after_widget']; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	/**
	 * Get array of all widget options.
	 *
	 * @param array $settings Array of settings for current widget instance.
	 *
	 * @since 1.0.0
	 */
	public function get_widget_options( $settings ) {
		$widget        = $this;
		$menus         = wp_get_nav_menus();
		$menu_arr      = wp_list_pluck( $menus, 'name', 'term_id' );
		$menu_arr      = array( '' => esc_html__( 'None', 'podcast-player' ) ) + $menu_arr;
		$display_style = Get_Fn::get_styles();
		$sub_items     = Get_Fn::get_services_list();
		$sub_array     = array();

		foreach ( $sub_items as $key => $label ) {
			$sub_key   = 'pp_' . $key . '_sub';
			$sub_label = sprintf(
				/* translators: %s: Subcription service label */
				esc_html__( '%s Subscription Link', 'podcast-player' ),
				$label
			);
			$sub_array[ $sub_key ] = array(
				'setting'       => $sub_key,
				'desc'          => esc_html__( 'Add a Podcast Subscription link', 'podcast-player' ),
				'type'          => 'url',
				'hide_callback' => function() use ( $widget, $settings ) {
					return $settings['podcast_menu'] && $settings['main_menu_items'];
				},
				'input_attrs'   => array(
					'placeholder' => $sub_label,
				),
			);
		}

		$sub_array = array_merge(
			$sub_array,
			array(
				'podcast_menu'    => array(
					'setting' => 'podcast_menu',
					'label'   => esc_html__( 'Podcast Subscription Menu.', 'podcast-player' ),
					'type'    => 'select',
					'choices' => $menu_arr,
				),
				'main_menu_items' => array(
					'setting'       => 'main_menu_items',
					'label'         => esc_html__( 'Number of Primary Subscription Links', 'podcast-player' ),
					'type'          => 'number',
					'input_attrs'   => array(
						'step' => 1,
						'min'  => 0,
						'size' => 3,
					),
					'hide_callback' => function() use ( $widget, $settings ) {
						return ! $settings['podcast_menu'] || ! $settings['main_menu_items'];
					},
				),
			)
		);

		return apply_filters(
			'podcast_player_widget_options',
			array(
				'default'   => array(
					'title' => esc_html__( 'General Options', 'podcast-player' ),
					'items' => array(
						'title'    => array(
							'setting' => 'title',
							'label'   => esc_html__( 'Title', 'podcast-player' ),
							'type'    => 'text',
						),
						'feed_url' => array(
							'setting'       => 'feed_url',
							'label'         => esc_html__( 'Podcast Feed URL', 'podcast-player' ),
							'type'          => 'furl',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_not_equal( 'pp_fetch_method', 'feed', $settings );
							},
						),
					),
				),
				'info'      => array(
					'title' => esc_html__( 'Customize Podcast Content', 'podcast-player' ),
					'items' => array(
						'cover_image'       => array(
							'setting' => 'cover_image',
							'label'   => esc_html__( 'Podcast Cover Image', 'podcast-player' ),
							'type'    => 'image_upload',
						),
						'desc'              => array(
							'setting'     => 'desc',
							'label'       => esc_html__( 'Brief Description', 'podcast-player' ),
							'type'        => 'textarea',
							'input_attrs' => array(
								'col' => 50,
								'row' => 3,
							),
						),
						'number'            => array(
							'setting'       => 'number',
							'label'         => esc_html__( 'Number of episodes to show at a time.', 'podcast-player' ),
							'type'          => 'number',
							'input_attrs'   => array(
								'step' => 1,
								'min'  => 1,
								'size' => 3,
							),
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_fetch_method', 'link', $settings );
							},
						),
						'offset'            => array(
							'setting'       => 'offset',
							'label'         => esc_html__( 'Number of episodes to skip from the beginning.', 'podcast-player' ),
							'type'          => 'number',
							'input_attrs'   => array(
								'step' => 1,
								'min'  => 1,
								'size' => 3,
							),
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_fetch_method', 'link', $settings );
							},
						),
						'pp_teaser_text'    => array(
							'setting'       => 'pp_teaser_text',
							'label'         => esc_html__( 'Teaser Text', 'podcast-player' ),
							'type'          => 'select',
							'choices'       => array(
								''     => esc_html__( 'Show Excerpt', 'podcast-player' ),
								'full' => esc_html__( 'Show Full Content', 'podcast-player' ),
								'none' => esc_html__( 'Do not Show Teaser Text', 'podcast-player' ),
							),
							'hide_callback' => function() use ( $widget, $settings ) {
								return ! Validation_Fn::is_style_support( $settings['pp_display_style'], 'excerpt' ) || $widget->is_option_equal( 'pp_fetch_method', 'link', $settings );
							},
						),
						'pp_excerpt_length' => array(
							'setting'       => 'pp_excerpt_length',
							'label'         => esc_html__( 'Excerpt Length', 'podcast-player' ),
							'type'          => 'number',
							'input_attrs'   => array(
								'step' => 1,
								'min'  => 0,
								'max'  => 200,
								'size' => 3,
							),
							'hide_callback' => function() use ( $widget, $settings ) {
								return ! Validation_Fn::is_style_support( $settings['pp_display_style'], 'excerpt' ) || '' !== $settings['pp_teaser_text'] || $widget->is_option_equal( 'pp_fetch_method', 'link', $settings );
							},
						),
						'pp_excerpt_unit'   => array(
							'setting'       => 'pp_excerpt_unit',
							'label'         => esc_html__( 'Excerpt Length Unit', 'podcast-player' ),
							'type'          => 'select',
							'choices'       => array(
								''     => esc_html__( 'Number of words', 'podcast-player' ),
								'char' => esc_html__( 'Number of characters', 'podcast-player' ),
							),
							'hide_callback' => function() use ( $widget, $settings ) {
								return ! Validation_Fn::is_style_support( $settings['pp_display_style'], 'excerpt' ) || '' !== $settings['pp_teaser_text'] || $widget->is_option_equal( 'pp_fetch_method', 'link', $settings );
							},
						),
					),
				),
				'subscribe' => array(
					'title' => esc_html__( 'Subscription Button Links', 'podcast-player' ),
					'items' => $sub_array,
				),
				'show'      => array(
					'title'       => esc_html__( 'Show/Hide Player Items', 'podcast-player' ),
					'op_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
					'items'       => array(
						'pp_header_default'   => array(
							'setting'       => 'pp_header_default',
							'label'         => esc_html__( 'Show Podcast Header by Default.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_not_equal( 'pp_display_style', '', $settings ) && $widget->is_option_not_equal( 'pp_display_style', 'legacy', $settings ) && $widget->is_option_not_equal( 'pp_display_style', 'modern', $settings );
							},
						),
						'pp_list_default'     => array(
							'setting'       => 'pp_list_default',
							'label'         => esc_html__( 'Show episodes list by default on mini player.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_not_equal( 'pp_display_style', '', $settings ) && $widget->is_option_not_equal( 'pp_display_style', 'legacy', $settings ) && $widget->is_option_not_equal( 'pp_display_style', 'modern', $settings );
							},
						),
						'pp_hide_header'      => array(
							'setting' => 'pp_hide_header',
							'label'   => esc_html__( 'Hide Podcast Header Information.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
						'pp_hide_cover'       => array(
							'setting'       => 'pp_hide_cover',
							'label'         => esc_html__( 'Hide cover image.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						),
						'pp_hide_title'       => array(
							'setting'       => 'pp_hide_title',
							'label'         => esc_html__( 'Hide Podcast Title.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						),
						'pp_hide_description' => array(
							'setting'       => 'pp_hide_description',
							'label'         => esc_html__( 'Hide Podcast Description.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						),
						'pp_hide_subscribe'   => array(
							'setting'       => 'pp_hide_subscribe',
							'label'         => esc_html__( 'Hide Custom menu.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_option_equal( 'pp_hide_header', 'yes', $settings );
							},
						),
						'pp_hide_search'      => array(
							'setting' => 'pp_hide_search',
							'label'   => esc_html__( 'Hide Podcast Search.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
						'pp_hide_author'      => array(
							'setting' => 'pp_hide_author',
							'label'   => esc_html__( 'Hide Episode Author/Podcaster Name.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
						'pp_hide_content'     => array(
							'setting'       => 'pp_hide_content',
							'label'         => esc_html__( 'Hide Episode Text Content/Transcript.', 'podcast-player' ),
							'type'          => 'checkbox',
							'hide_callback' => function() use ( $widget, $settings ) {
								return $widget->is_premium && $widget->is_option_not_equal( 'pp_fetch_method', 'feed', $settings );
							},
						),
						'pp_hide_loadmore'    => array(
							'setting' => 'pp_hide_loadmore',
							'label'   => esc_html__( 'Hide Load More Episodes Button.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
						'pp_hide_download'    => array(
							'setting' => 'pp_hide_download',
							'label'   => esc_html__( 'Hide Episode Download Link.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
						'pp_hide_social'      => array(
							'setting' => 'pp_hide_social',
							'label'   => esc_html__( 'Hide Social Share Links.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
						'pp_hide_featured'    => array(
							'setting' => 'pp_hide_featured',
							'label'   => esc_html__( 'Hide Episodes Featured Image.', 'podcast-player' ),
							'type'    => 'checkbox',
						),
					),
				),
				'style'     => array(
					'title' => esc_html__( 'Podcast Player Styling', 'podcast-player' ),
					'items' => array(
						'pp_accent_color'  => array(
							'setting' => 'pp_accent_color',
							'label'   => esc_html__( 'Accent Color', 'podcast-player' ),
							'type'    => 'color',
						),
						'pp_display_style' => array(
							'setting' => 'pp_display_style',
							'label'   => esc_html__( 'Podcast Player Display Style', 'podcast-player' ),
							'type'    => 'select',
							'choices' => $display_style,
						),
					),
				),
				'filter'    => array(
					'title'       => esc_html__( 'Sort & Filter Options', 'podcast-player' ),
					'op_callback' => function() use ( $widget, $settings ) {
						return $widget->is_option_not_equal( 'pp_fetch_method', 'link', $settings );
					},
					'items'       => array(
						'sortby'   => array(
							'setting' => 'sortby',
							'label'   => esc_html__( 'Sort Podcast Episodes By', 'podcast-player' ),
							'type'    => 'select',
							'choices' => array(
								'sort_title_desc' => esc_html__( 'Title Descending', 'podcast-player' ),
								'sort_title_asc'  => esc_html__( 'Title Ascending', 'podcast-player' ),
								'sort_date_desc'  => esc_html__( 'Date Descending', 'podcast-player' ),
								'sort_date_asc'   => esc_html__( 'Date Ascending', 'podcast-player' ),
								'no_sort'         => esc_html__( 'Do Not Sort', 'podcast-player' ),
								'reverse_sort'    => esc_html__( 'Reverse Sort', 'podcast-player' ),
							),
						),
						'filterby' => array(
							'setting' => 'filterby',
							'label'   => esc_html__( 'Show episodes only if title contains following', 'podcast-player' ),
							'type'    => 'text',
						),
					),
				),
			),
			$widget,
			$settings
		);
	}

	/**
	 * Outputs the settings form for the widget.
	 *
	 * @since 1.0.0
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		// Merge with defaults.
		$instance = wp_parse_args( (array) $instance, $this->defaults );
		$options  = $this->get_widget_options( $instance );

		$default_markup = '';
		$options_markup = '';
		foreach ( $options as $option => $args ) {
			$items  = $args['items'];
			$showop = isset( $args['op_callback'] ) && is_callable( $args['op_callback'] ) ? call_user_func( $args['op_callback'] ) : true;
			$markup = '';
			foreach ( $items as $item => $attr ) {
				$dcall = isset( $attr['display_callback'] ) && is_callable( $attr['display_callback'] ) ? call_user_func( $attr['display_callback'] ) : true;
				if ( ! $dcall ) {
					continue;
				}

				$set   = $attr['setting'];
				$id    = esc_attr( $this->get_field_id( $set ) );
				$name  = esc_attr( $this->get_field_name( $set ) );
				$type  = $attr['type'];
				$label = isset( $attr['label'] ) ? $attr['label'] : '';
				$desc  = isset( $attr['desc'] ) ? $attr['desc'] : '';
				$iatt  = isset( $attr['input_attrs'] ) ? $attr['input_attrs'] : array();
				$hcal  = isset( $attr['hide_callback'] ) && is_callable( $attr['hide_callback'] ) ? call_user_func( $attr['hide_callback'] ) : false;

				$inputattr = '';
				foreach ( $iatt as $att => $val ) {
					$inputattr .= esc_html( $att ) . '="' . esc_attr( $val ) . '" ';
				}

				switch ( $type ) {
					case 'select':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= $this->select( $set, $attr['choices'], $instance[ $set ], array(), false );
						break;
					case 'checkbox':
						$optmar  = sprintf( '<input name="%s" id="%s" type="checkbox" value="yes" %s />', $name, $id, checked( $instance[ $set ], 'yes', false ) );
						$optmar .= $this->label( $set, $label, false );
						break;
					case 'text':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="widefat" name="%1$s" id="%2$s" type="text" value="%3$s" />', $name, $id, esc_attr( $instance[ $set ] ) );
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'url':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="widefat" name="%1$s" id="%2$s" type="url" value="%3$s" %4$s />', $name, $id, esc_attr( $instance[ $set ] ), $inputattr );
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'number':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="widefat" name="%1$s" id="%2$s" type="number" value="%3$s" %4$s />', $name, $id, absint( $instance[ $set ] ), $inputattr );
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'mmss':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<div class="mmss-time" id="%s">', $id );
						$optmar .= sprintf( '<input class="tiny-text" name="%1$s[]" type="number" value="%2$s" min="0" max="10" size="2"/> : ', $name, absint( $instance[ $set ][0] ) );
						$optmar .= sprintf( '<input class="tiny-text" name="%1$s[]" type="number" value="%2$s"  min="0" max="59" size="2" /> : ', $name, absint( $instance[ $set ][1] ) );
						$optmar .= sprintf( '<input class="tiny-text" name="%1$s[]" type="number" value="%2$s"  min="0" max="59" size="2" />', $name, absint( $instance[ $set ][2] ) );
						$optmar .= '</div>';
						$optmar .= sprintf( '<div class="pp-desc">%s</div>', $desc );
						break;
					case 'textarea':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<textarea class="widefat" name="%1$s" id="%2$s" %3$s >%4$s</textarea>', $name, $id, $inputattr, esc_attr( $instance[ $set ] ) );
						break;
					case 'image_upload':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= $this->image_upload( $id, $name, $instance[ $set ] );
						break;
					case 'color':
						$optmar  = $this->label( $set, $label, false );
						$optmar .= sprintf( '<input class="pp-color-picker" name="%1$s" id="%2$s" type="text" value="%3$s" />', $name, $id, esc_attr( sanitize_hex_color( $instance[ $set ] ) ) );
						break;
					case 'furl':
						$optmar = $this->feed_url( $name, $id, $label, $instance );
						break;
					case 'taxonomy':
						$optmar = $this->taxonomies_select( $instance['pp_post_type'], $instance['pp_taxonomy'] );
						break;
					case 'terms':
						$optmar = $this->terms_checklist( $instance['pp_taxonomy'], $instance['pp_terms'] );
						break;
					case 'elist':
						$optmar = $this->episodes_checklist( $instance );
						break;
					case 'slist':
						$optmar = $this->seasons_checklist( $instance );
						break;
					case 'catlist':
						$optmar = $this->categories_checklist( $instance );
						break;
					default:
						$optmar = apply_filters( 'podcast_player_custom_option_field', false, $item, $attr, $this, $instance );
						break;
				}
				$style   = $hcal ? 'style="display: none;"' : '';
				$markup .= $optmar ? sprintf( '<div class="%1$s pp-widget-option" %2$s>%3$s</div>', $set, $style, $optmar ) : '';
			}
			if ( 'default' === $option ) {
				$default_markup = $markup;
			} else {
				$opstyle         = $showop ? '' : 'style="display: none;"';
				$section         = sprintf( '<a class="pp-settings-toggle pp-%1$s-toggle" %2$s>%3$s</a>', $option, $opstyle, $args['title'] );
				$section        .= sprintf( '<div class="pp_settings-content pp-%1$s-content">%2$s</div>', $option, $markup );
				$options_markup .= $section;
			}
		}
		printf( '%1$s<div class="pp-options-wrapper">%2$s</div>', $default_markup, $options_markup ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		if ( 0 < strlen( $instance['error'] ) ) :
			?>
			<div style="color: red; font-weight: bold;"><?php echo esc_html( $instance['error'] ); ?></div>
			<?php
		endif;
	}

	/**
	 * Handles updating the settings for the current widget instance.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings to save.
	 */
	public function update( $new_instance, $old_instance ) {

		// Merge with defaults.
		$new_instance = wp_parse_args( (array) $new_instance, $this->defaults );

		$instance                = $old_instance;
		$img_id                  = absint( $new_instance['cover_image'] );
		$img_url                 = $img_id ? wp_get_attachment_image_src( $img_id ) : false;
		$instance['cover_image'] = $img_url ? $img_id : '';

		$sanitize_int = array(
			'number',
			'offset',
			'podcast_menu',
			'main_menu_items',
			'pp_excerpt_length',
		);
		foreach ( $sanitize_int as $setting ) {
			$instance[ $setting ] = absint( $new_instance[ $setting ] );
		}

		$sanitize_text = array(
			'title',
			'desc',
			'sortby',
			'filterby',
			'pp_display_style',
			'pp_teaser_text',
			'pp_excerpt_unit',
		);
		foreach ( $sanitize_text as $setting ) {
			$instance[ $setting ] = sanitize_text_field( $new_instance[ $setting ] );
		}

		$sanitize_url = array(
			'pp_apple_sub',
			'pp_google_sub',
			'pp_spotify_sub',
			'pp_breaker_sub',
			'pp_castbox_sub',
			'pp_castro_sub',
			'pp_iheart_sub',
			'pp_amazon_sub',
			'pp_overcast_sub',
			'pp_pocketcasts_sub',
			'pp_podcastaddict_sub',
			'pp_podchaser_sub',
			'pp_radiopublic_sub',
			'pp_soundcloud_sub',
			'pp_stitcher_sub',
			'pp_tunein_sub',
			'pp_youtube_sub',
			'pp_bullhorn_sub',
			'pp_podbean_sub',
			'pp_playerfm_sub',
		);
		foreach ( $sanitize_url as $setting ) {
			$instance[ $setting ] = esc_url_raw( $new_instance[ $setting ] );
		}

		$sanitize_bool = array(
			'pp_list_default',
			'pp_hide_title',
			'pp_hide_cover',
			'pp_hide_description',
			'pp_header_default',
			'pp_hide_header',
			'pp_hide_subscribe',
			'pp_hide_search',
			'pp_hide_author',
			'pp_hide_content',
			'pp_hide_loadmore',
			'pp_hide_download',
			'pp_hide_social',
			'pp_hide_featured',
		);
		foreach ( $sanitize_bool as $setting ) {
			$instance[ $setting ] = ( 'yes' === $new_instance[ $setting ] ) ? 'yes' : '';
		}

		$instance['pp_accent_color'] = sanitize_hex_color( $new_instance['pp_accent_color'] );

		if ( $this->is_premium && isset( $instance['pp_fetch_method'] ) && 'feed' !== $instance['pp_fetch_method'] ) {
			$instance['feed_url'] = '';
			$instance['error']    = '';
		} else {
			$error   = '';
			$feedurl = '';
			if ( isset( $old_instance['feed_url'] ) && ( $new_instance['feed_url'] === $old_instance['feed_url'] ) ) {
				$feedurl = $old_instance['feed_url'];
			} elseif ( $new_instance['feed_url'] ) {
				$feedurl  = $new_instance['feed_url'];
				$validurl = Validation_Fn::is_valid_url( $feedurl );
				if ( $validurl ) {
					$feedurl = esc_url_raw( wp_strip_all_tags( $feedurl ) );
				} else {
					$feedurl = sanitize_text_field( $feedurl );
				}
			}

			$instance['feed_url'] = $feedurl;
			$instance['error']    = sanitize_text_field( $error );
		}

		return apply_filters( 'podcast_player_widget_update', $instance, $new_instance, $this );
	}

	/**
	 * Check if widget setting contains a particular value.
	 *
	 * @param str   $setting Setting to be checked.
	 * @param str   $val Setting value to be matched.
	 * @param array $settings Array of settings for current widget instance.
	 * @return bool
	 */
	public function is_option_equal( $setting, $val, $settings ) {
		return isset( $settings[ $setting ] ) && $val === $settings[ $setting ];
	}

	/**
	 * Check if widget setting doen not contains a particular value.
	 *
	 * @param str   $setting Setting to be checked.
	 * @param str   $val Setting value to be matched.
	 * @param array $settings Array of settings for current widget instance.
	 * @return bool
	 */
	public function is_option_not_equal( $setting, $val, $settings ) {
		return ! isset( $settings[ $setting ] ) || $val !== $settings[ $setting ];
	}

	/**
	 * Markup for 'label' for widget input options.
	 *
	 * @param str  $for  Label for which ID.
	 * @param str  $text Label text.
	 * @param bool $echo Display or Return.
	 * @return void|string
	 */
	public function label( $for, $text, $echo = true ) {
		$label = '';
		if ( $for && $text ) {
			$label = sprintf( '<label for="%s">%s</label>', esc_attr( $this->get_field_id( $for ) ), esc_html( $text ) );
		}
		if ( $echo ) {
			echo $label; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $label;
		}
	}

	/**
	 * Markup for Select dropdown lists for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Options HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function select( $for, $options, $selected, $classes = array(), $echo = true ) {
		$select      = '';
		$final_class = '';
		foreach ( $options as $value => $label ) {
			if ( isset( $classes[ $value ] ) ) {
				$option_classes = (array) $classes[ $value ];
				$option_classes = array_map( 'esc_attr', $option_classes );
				$final_class    = 'class="' . join( ' ', $option_classes ) . '"';
			}
			$select .= sprintf( '<option value="%1$s" %2$s %3$s>%4$s</option>', esc_attr( $value ), $final_class, selected( $value, $selected, false ), esc_html( $label ) );
		}

		$select = sprintf(
			'<select id="%1$s" name="%2$s" class="podcast-player-%3$s widefat">%4$s</select>',
			esc_attr( $this->get_field_id( $for ) ),
			esc_attr( $this->get_field_name( $for ) ),
			esc_attr( str_replace( '_', '-', $for ) ),
			$select
		);

		if ( $echo ) {
			echo $select; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $select;
		}
	}

	/**
	 * Image upload option markup.
	 *
	 * @since 1.0.0
	 *
	 * @param str $id      Field ID.
	 * @param str $name    Field Name.
	 * @param int $value   Uploaded image id.
	 * @return str Widget form image upload markup.
	 */
	public function image_upload( $id, $name, $value ) {

		$value          = absint( $value );
		$uploader_class = '';
		$class          = 'podcast-player-hidden';

		if ( $value ) {
			$image_src = wp_get_attachment_image_src( $value, 'large' );
			if ( $image_src ) {
				$featured_markup = sprintf( '<img class="custom-widget-thumbnail" src="%s">', esc_url( $image_src[0] ) );
				$class           = '';
				$uploader_class  = 'has-image';
			} else {
				$featured_markup = esc_html__( 'Podcast Cover Image', 'podcast-player' );
			}
		} else {
			$featured_markup = esc_html__( 'Podcast Cover Image', 'podcast-player' );
		}

		$markup  = sprintf( '<a class="podcast-player-widget-img-uploader %s">%s</a>', $uploader_class, $featured_markup );
		$markup .= sprintf( '<span class="podcast-player-widget-img-instruct %s">%s</span>', $class, esc_html__( 'Click the image to edit/update', 'podcast-player' ) );
		$markup .= sprintf( '<a class="podcast-player-widget-img-remover %s">%s</a>', $class, esc_html__( 'Remove Featured Image', 'podcast-player' ) );
		$markup .= sprintf( '<input class="podcast-player-widget-img-id" name="%s" id="%s" value="%s" type="hidden" />', $name, $id, $value );
		return $markup;
	}

	/**
	 * Prints select list of all taxonomies for a post type.
	 *
	 * @param str   $post_type Selected post type.
	 * @param array $selected  Selected taxonomy in widget form.
	 */
	public function taxonomies_select( $post_type, $selected = array() ) {

		$options = PP_Get_Fn::get_taxonomies();

		// Get HTML classes for select options.
		$taxonomies = get_taxonomies( array(), 'objects' );
		$classes    = wp_list_pluck( $taxonomies, 'object_type', 'name' );
		if ( $post_type && 'page' !== $post_type ) {
			foreach ( $classes as $name => $type ) {
				$type = (array) $type;
				if ( ! in_array( $post_type, $type, true ) ) {
					$type[]           = 'podcast-player-hidden';
					$classes[ $name ] = $type;
				}
			}
		}
		$classes[''] = 'always-visible';

		$markup = '';
		// Taxonomy Select markup.
		$markup .= $this->label( 'pp_taxonomy', esc_html__( 'Get Episodes by Taxonomy', 'podcast-player' ), false );
		$markup .= $this->select( 'pp_taxonomy', $options, $selected, $classes, false );
		return $markup;
	}

	/**
	 * Prints a checkbox list of all terms for a taxonomy.
	 *
	 * @param string $name  Name of the setting.
	 * @param string $id    Setting ID.
	 * @param string $label Setting Label.
	 * @param arr    $args  Settings for current podcast player instance.
	 */
	public function feed_url( $name, $id, $label, $args ) {
		$feed_index = Get_Fn::get_feed_index();
		$selected   = isset( $args['feed_url'] ) ? $args['feed_url'] : '';
		$markup     = $this->label( 'feed_url', $label, false );
		$markup    .= '<div class="pp_feed-selection">';
		if ( $feed_index && is_array( $feed_index ) && ! empty( $feed_index ) ) {
			array_walk(
				$feed_index,
				function( &$val, $key ) {
					$val = isset( $val['title'] ) ? $val['title'] : '';
				}
			);
			$feed_index = array_filter( $feed_index );
			$feed_index = array_merge(
				array( '' => esc_html__( 'Select a Podcast', 'podcast-player' ) ),
				$feed_index
			);
			$markup    .= $this->select( 'pp_furl_select', $feed_index, $selected, array(), false );
			$markup    .= '<div class="pp-furl-or" style="text-align: center; margin: 5px 0;">OR</div>';
		}
		$markup .= sprintf( '<input class="pp_feed-url widefat" name="%1$s" id="%2$s" type="text" value="%3$s" placeholder="%4$s" />', $name, $id, esc_attr( $selected ), esc_attr__( 'Enter Your Podcast Feed URL', 'podcast-player' ) );
		$markup .= '</div>';
		return $markup;
	}

	/**
	 * Prints a checkbox list of all terms for a taxonomy.
	 *
	 * @param str   $taxonomy       Selected Taxonomy.
	 * @param array $selected_terms Selected Terms.
	 */
	public function terms_checklist( $taxonomy, $selected_terms = array() ) {

		$tax = PP_Get_Fn::get_taxonomies();
		$tax = array_filter( array_keys( $tax ) );

		// Get list of all registered terms.
		$terms = get_terms( array( 'taxonomy' => $tax ) );

		// Get 'checkbox' options as value => label.
		$options = wp_list_pluck( $terms, 'name', 'slug' );

		// Get HTML classes for checkbox options.
		$classes = wp_list_pluck( $terms, 'taxonomy', 'slug' );
		if ( $taxonomy ) {
			foreach ( $classes as $slug => $taxon ) {
				if ( $taxonomy !== $taxon ) {
					$classes[ $slug ] .= ' podcast-player-hidden';
				}
			}
		}

		$markup = '';
		// Terms Checkbox markup.
		$markup .= $this->label( 'pp_terms', esc_html__( 'Select Terms', 'podcast-player' ), false );
		$markup .= $this->mu_checkbox( 'pp_terms', $options, $selected_terms, $classes, false );
		return $markup;
	}

	/**
	 * Get podcast feed data.
	 *
	 * @param string $key  Specific key for required data.
	 * @param array  $args Settings for current podcast player instance.
	 */
	private function get_feed_data( $key, $args ) {

		// Return if feed url has not beed provided.
		if ( ! isset( $args['feed_url'] ) || ! $args['feed_url'] ) {
			return array();
		}

		$feed = $this->fetch_feed_data( $args );
		if ( is_wp_error( $feed ) ) {
			return array();
		}

		$flist = Utility_Fn::multi_array_columns( array( 'items', 'seasons', 'categories' ), $feed );

		// Properly format feed items as 'item_key => item_title'.
		$flist['items'] = array_map(
			function( $arr ) {
				return $arr['title'];
			},
			$flist['items']
		);

		// Properly format seasons list'.
		$fseasons = array();
		array_walk(
			$flist['seasons'],
			function( $val, $key ) use ( &$fseasons ) {
				$fseasons[ '0' . $val ] = esc_html__( 'Season', 'podcast-player' ) . '-' . $val;
			}
		);
		$flist['seasons'] = $fseasons;

		if ( isset( $flist[ $key ] ) ) {
			return $flist[ $key ];
		}
		return array();
	}

	/**
	 * Fetch podcast feed data.
	 *
	 * @param array $args Settings for current podcast player instance.
	 */
	public function fetch_feed_data( $args ) {
		$url     = $args['feed_url'];
		$seasons = isset( $args['pp_slist'] ) ? $args['pp_slist'] : array();
		$cats    = isset( $args['pp_catlist'] ) ? $args['pp_catlist'] : array();
		$mods    = array(
			'slist'   => array_filter( array_map( 'absint', $seasons ) ),
			'catlist' => array_filter( array_map( 'trim', $cats ) ),
			'sortby'  => 'sort_date_desc',
		);
		$fields  = array( 'title' );
		return Get_Fn::get_feed_data( $url, $mods, $fields );
	}

	/**
	 * Prints a checkbox list of all episodes of current podcast feed.
	 *
	 * @param arr $args Settings for current podcast player instance.
	 */
	public function episodes_checklist( $args ) {

		$method    = isset( $args['pp_fetch_method'] ) ? $args['pp_fetch_method'] : 'feed';
		$classes   = array();
		$selected  = isset( $args['pp_elist'] ) ? $args['pp_elist'] : array( '' );
		$is_hidden = true;
		$options   = array( '' => esc_html__( 'Show All Episodes', 'podcast-player' ) );

		if ( 'feed' === $method ) {
			$ops       = array_filter( $this->get_feed_data( 'items', $args ) );
			$is_hidden = empty( $ops ) ? true : false;
			$options   = array_merge( $options, $ops );
		} elseif ( 'post' === $method ) {
			$sets = array(
				'post-type' => isset( $args['pp_post_type'] ) ? $args['pp_post_type'] : 'post',
				'taxonomy'  => isset( $args['pp_taxonomy'] ) ? $args['pp_taxonomy'] : '',
				'terms'     => isset( $args['pp_terms'] ) ? $args['pp_terms'] : array(),
				'sortby'    => isset( $args['sortby'] ) ? $args['sortby'] : 'sort_date_desc',
				'filterby'  => isset( $args['filterby'] ) ? $args['filterby'] : '',
				'number'    => -1,
			);

			$ops       = apply_filters( 'podcast_player_posts_elist', $sets );
			$ops       = isset( $ops['episodes'] ) ? $ops['episodes'] : array();
			$is_hidden = empty( $ops ) ? true : false;
			$options   = array_merge( $options, $ops );
		}

		foreach ( $options as $id => $label ) {
			if ( '' !== $id ) {
				$classes[ $id ] = 'pp-episodes';
			} else {
				$classes[ $id ] = 'd-episode';
			}
		}

		$markup = '';
		$style  = $is_hidden ? 'style="display: none;"' : '';
		// Episodes list Checkbox markup.
		$markup .= $this->label( 'pp_elist', esc_html__( 'Select Episodes to be displayed', 'podcast-player' ), false );
		$markup .= $this->mu_checkbox( 'pp_elist', $options, $selected, $classes, false );
		return sprintf( '<div class="pp-episodes-list" %1$s>%2$s</div>', $style, $markup );
	}

	/**
	 * Prints a checkbox list of all seasons of current podcast feed.
	 *
	 * @param arr $args Settings for current podcast player instance.
	 */
	public function seasons_checklist( $args ) {

		$method    = isset( $args['pp_fetch_method'] ) ? $args['pp_fetch_method'] : 'feed';
		$classes   = array();
		$selected  = isset( $args['pp_slist'] ) ? $args['pp_slist'] : array( '' );
		$is_hidden = true;
		$options   = array( '' => esc_html__( 'Show All Seasons', 'podcast-player' ) );

		if ( 'feed' === $method ) {
			$ops       = array_filter( $this->get_feed_data( 'seasons', $args ) );
			$is_hidden = empty( $ops ) ? true : false;
			$options   = array_merge( $options, $ops );
		}

		foreach ( $options as $id => $label ) {
			if ( '' !== $id ) {
				$classes[ $id ] = 'pp-seasons';
			} else {
				$classes[ $id ] = 'd-season';
			}
		}

		$markup = '';
		$style  = $is_hidden ? 'style="display: none;"' : '';
		// Episodes list Checkbox markup.
		$markup .= $this->label( 'pp_slist', esc_html__( 'Select seasons to be displayed', 'podcast-player' ), false );
		$markup .= $this->mu_checkbox( 'pp_slist', $options, $selected, $classes, false );
		return sprintf( '<div class="pp-seasons-list" %1$s>%2$s</div>', $style, $markup );
	}

	/**
	 * Prints a checkbox list of all categories of current podcast feed.
	 *
	 * @param arr $args Settings for current podcast player instance.
	 */
	public function categories_checklist( $args ) {

		$method    = isset( $args['pp_fetch_method'] ) ? $args['pp_fetch_method'] : 'feed';
		$classes   = array();
		$selected  = isset( $args['pp_catlist'] ) ? $args['pp_catlist'] : array( '' );
		$is_hidden = true;
		$options   = array( '' => esc_html__( 'Show All Categories', 'podcast-player' ) );

		if ( 'feed' === $method ) {
			$ops       = array_filter( $this->get_feed_data( 'categories', $args ) );
			$is_hidden = empty( $ops ) ? true : false;
			$options   = array_merge( $options, $ops );
		}

		foreach ( $options as $id => $label ) {
			if ( '' !== $id ) {
				$classes[ $id ] = 'pp-cats';
			} else {
				$classes[ $id ] = 'd-cat';
			}
		}

		$markup = '';
		$style  = $is_hidden ? 'style="display: none;"' : '';
		// Episodes list Checkbox markup.
		$markup .= $this->label( 'pp_catlist', esc_html__( 'Select Categories to be displayed', 'podcast-player' ), false );
		$markup .= $this->mu_checkbox( 'pp_catlist', $options, $selected, $classes, false );
		return sprintf( '<div class="pp-categories-list" %1$s>%2$s</div>', $style, $markup );
	}

	/**
	 * Markup for multiple checkbox for widget options.
	 *
	 * @param str   $for      Select for which ID.
	 * @param array $options  Select options as 'value => label' pair.
	 * @param str   $selected selected option.
	 * @param array $classes  Checkbox input HTML classes.
	 * @param bool  $echo     Display or return.
	 * @return void|string
	 */
	public function mu_checkbox( $for, $options, $selected = array(), $classes = array(), $echo = true ) {

		$final_class = '';

		$mu_checkbox = '<div class="' . esc_attr( $for ) . '-checklist"><ul id="' . esc_attr( $this->get_field_id( $for ) ) . '">';

		$selected    = array_map( 'strval', $selected );
		$rev_options = $options;

		// Moving selected items on top of the array.
		foreach ( $options as $id => $label ) {
			if ( in_array( strval( $id ), $selected, true ) ) {
				$rev_options = array( $id => $label ) + $rev_options;
			}
		}

		// Bring default option at top.
		if ( isset( $rev_options[''] ) ) {
			$rev_options = array( '' => $rev_options[''] ) + $rev_options;
		}

		foreach ( $rev_options as $id => $label ) {
			if ( isset( $classes[ $id ] ) ) {
				$final_class = ' class="' . esc_attr( $classes[ $id ] ) . '"';
			}
			$mu_checkbox .= "\n<li$final_class>" . '<label class="selectit"><input value="' . esc_attr( $id ) . '" type="checkbox" name="' . esc_attr( $this->get_field_name( $for ) ) . '[]"' . checked( in_array( strval( $id ), $selected, true ), true, false ) . ' /><span class="cblabel">' . esc_html( $label ) . "</span></label></li>\n";
		}
		$mu_checkbox .= "</ul></div>\n";

		if ( $echo ) {
			echo $mu_checkbox; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		} else {
			return $mu_checkbox;
		}
	}
}
