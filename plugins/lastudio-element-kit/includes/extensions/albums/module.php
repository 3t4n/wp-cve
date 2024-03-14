<?php

namespace LaStudioKitExtensions\Albums;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

use LaStudioKitExtensions\Module_Base;

class Module extends Module_Base {

    /**
     * Module version.
     *
     * @var string
     */
    protected $version = '1.0.0';

    /**
     * Module directory path.
     *
     * @since 1.0.0
     * @access protected
     * @var string $path
     */
    protected $path;

    /**
     * Module directory URL.
     *
     * @since 1.0.0
     * @access protected
     * @var string $url.
     */
    protected $url;

    public static function is_active(){
        $available_extension = lastudio_kit_settings()->get_option('avaliable_extensions', []);
        return !empty($available_extension['album_content_type']) && filter_var($available_extension['album_content_type'], FILTER_VALIDATE_BOOLEAN);
    }

    public function __construct()
    {
        $this->path = lastudio_kit()->plugin_path('includes/extensions/events/');
        $this->url  = lastudio_kit()->plugin_url('includes/extensions/events/');
		add_action( 'init', [ $this, 'register_content_type' ] );
	    add_action( 'init', [ $this, 'add_metaboxes' ], -5 );

	    add_action( 'elementor/widgets/register', function ($widgets_manager){
		    $widgets_manager->register( new Widgets\Album_Lists() );
		    $widgets_manager->register( new Widgets\Player() );
	    } );

		add_filter('lastudio-kit/playlists/get_config', [ $this, 'get_playlist_configs' ], 10, 2);
		add_filter('lastudio-kit/playlists/first_item_end_time', [ $this, 'get_first_item_end_time' ], 10, 2);
    }

	public function register_content_type(){
		register_post_type( 'la_album', apply_filters('lastudio-kit/admin/albums/args', [
			'labels'                => [
				'name'          => __( 'Albums', 'lastudio-kit' ),
				'singular_name' => __( 'Album', 'lastudio-kit' ),
			],
			'supports'              => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields'),
			'menu_icon'             => 'dashicons-playlist-audio',
			'public'                => true,
			'menu_position'         => 8,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'rewrite'               => array( 'slug' => 'album' )
		]));
		register_taxonomy( 'la_album_label', 'la_album', apply_filters('lastudio-kit/admin/album_labels/args', [
			'hierarchical'      => true,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'          => __( 'Labels', 'lastudio-kit' ),
				'singular_name' => __( 'Label', 'lastudio-kit' )
			),
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array('slug' => 'album-label')
		]));
		register_taxonomy( 'la_album_genre', 'la_album', apply_filters('lastudio-kit/admin/album_genres/args', [
			'hierarchical'      => true,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'          => __( 'Genres', 'lastudio-kit' ),
				'singular_name' => __( 'Genre', 'lastudio-kit' )
			),
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array('slug' => 'genre')
		]));

		register_taxonomy( 'la_album_artist', 'la_album', apply_filters('lastudio-kit/admin/album_artists/args', [
			'hierarchical'      => true,
			'show_in_nav_menus' => true,
			'labels'            => array(
				'name'          => __( 'Artists', 'lastudio-kit' ),
				'singular_name' => __( 'Artist', 'lastudio-kit' )
			),
			'query_var'         => true,
			'show_admin_column' => true,
			'rewrite'           => array('slug' => 'artist')
		]));
	}

	public function add_metaboxes() {

		lastudio_kit_post_meta()->add_options( array (
			'id'            => 'lastudiokit-album-settings',
			'title'         => esc_html__( 'Album Settings', 'lastudio-kit' ),
			'page'          => array( 'la_album' ),
			'context'       => 'normal',
			'priority'      => 'high',
			'callback_args' => false,
			'admin_columns' => array(
				'lakit_thumb' => array(
					'label'    => sprintf('<span class="lakit-image">%1$s</span>', __('Images', 'lastudio-kit')),
					'callback' => array( $this, 'metabox_callback__column' ),
					'position' => 1,
				),
				'album_release_date' => array(
					'label'    => __( 'Release Date', 'lastudio-kit' ),
					'callback' => array( $this, 'metabox_callback__column' ),
				)
			),
			'fields'        => array(
				'album_release_date' => array(
					'type'        => 'text',
					'input_type'  => 'date',
					'title'       => esc_html__( 'Release Date', 'lastudio-kit' ),
					'description' => esc_html__( 'Formatted like "YYYY-MM-DD".', 'lastudio-kit' ),
					'placeholder' => 'YYYY-MM-DD'
				),
				'album_people' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'People', 'lastudio-kit' ),
					'description' => esc_html__( 'Here you can input the names of people that worked on the album.', 'lastudio-kit' ),
				),
				'album_latest_video' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Latest Video Link', 'lastudio-kit' ),
					'description' => esc_html__( 'Enter a link to your latest video.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'Enter Youtube or Vimeo URL', 'lastudio-kit' ),
				),
				'album_backtolink' => array(
					'type'        => 'text',
					'title'       => esc_html__( 'Back to Link', 'lastudio-kit' ),
					'description' => esc_html__( 'Input a "back to" link from the album\'s single page.', 'lastudio-kit' ),
					'placeholder' => esc_html__( 'https://website.com', 'lastudio-kit' ),
				),
				'album_available_on' => array(
					'type'        => 'repeater',
					'title'       => esc_html__('Available On', 'lastudio-kit'),
					'ui_kit'      => false,
					'collapsed'   => true,
					'title_field' => 'type',
					'class'       => 'lakit-metabox--repeater',
					'fields'      => array(
						'type' => array(
							'type'        => 'select',
							'name'        => 'type',
							'id'          => 'type',
							'filter'      => false,
							'label'       => esc_html__( 'Type', 'lastudio-kit' ),
							'class'       => 'lakit-metabox--field-inline',
							'options'     => array(
								''              => esc_html__('Select', 'lastudio-kit'),
								'spotify'       => esc_html__('Spotify', 'lastudio-kit'),
								'youtube'       => esc_html__('Youtube', 'lastudio-kit'),
								'itunes'        => esc_html__('iTunes', 'lastudio-kit'),
								'soundcloud'    => esc_html__('Soundcloud', 'lastudio-kit'),
								'bandcamp'      => esc_html__('Bandcamp', 'lastudio-kit'),
								'googleplay'    => esc_html__('Google Play', 'lastudio-kit'),
								'amazon'        => esc_html__('Amazon', 'lastudio-kit'),
								'custom1'       => esc_html__('Custom 1', 'lastudio-kit'),
								'custom2'       => esc_html__('Custom 2', 'lastudio-kit'),
								'custom3'       => esc_html__('Custom 3', 'lastudio-kit'),
							),
						),
						'link' => array(
							'type'        => 'text',
							'name'        => 'link',
							'id'          => 'link',
							'class'       => 'lakit-metabox--field-inline',
							'label'       => esc_html__( 'Link', 'lastudio-kit' ),
							'description' => esc_html__( 'Enter a link.', 'lastudio-kit' ),
							'placeholder' => esc_html__( 'https://website.com', 'lastudio-kit' ),
						),
					)
				),
				'album_tracks' => array(
					'type'        => 'repeater',
					'title'       => esc_html__('Tracks', 'lastudio-kit'),
					'ui_kit'      => false,
					'collapsed'   => true,
					'title_field' => 'title',
					'class'       => 'lakit-metabox--repeater lakit-metabox--tracks_repeater',
					'fields'      => array(
						'source_custom' => array(
							'type'        => 'text',
							'name'        => 'source_custom',
							'id'          => 'source_custom',
							'class'       => 'lakit-metabox--field-full',
							'label'       => esc_html__( 'Custom Source', 'lastudio-kit' ),
							'placeholder' => esc_html__( 'Enter .mp3 URL', 'lastudio-kit' ),
						),
						'source' => array(
							'type'        => 'media',
							'name'        => 'source',
							'id'          => 'source',
							'class'       => 'lakit-metabox--field-half',
							'label'       => esc_html__( 'Source', 'lastudio-kit' ),
							'multi_upload'=> false,
							'library_type'=> 'audio',
							'upload_button_text'=> esc_html__( 'Select Source', 'lastudio-kit' ),
						),
						'preview' => array(
							'type'        => 'media',
							'name'        => 'preview',
							'id'          => 'preview',
							'class'       => 'lakit-metabox--field-half',
							'label'       => esc_html__( 'Preview', 'lastudio-kit' ),
							'multi_upload'=> false,
							'library_type'=> 'image',
							'upload_button_text'=> esc_html__( 'Select Preview', 'lastudio-kit' ),
						),
						'title' => array(
							'type'        => 'text',
							'name'        => 'title',
							'id'          => 'title',
							'class'       => 'lakit-metabox--field-half',
							'label'       => esc_html__( 'Title', 'lastudio-kit' ),
						),
						'artist' => array(
							'type'        => 'text',
							'name'        => 'artist',
							'id'          => 'artist',
							'class'       => 'lakit-metabox--field-half',
							'label'       => esc_html__( 'Artist', 'lastudio-kit' ),
						),
						'length' => array(
							'type'        => 'text',
							'name'        => 'length',
							'id'          => 'length',
							'class'       => 'lakit-metabox--field-half',
							'label'       => esc_html__( 'Length', 'lastudio-kit' ),
						),
						'product' => array(
							'type'        => 'posts',
							'name'        => 'product',
							'id'          => 'product',
							'class'       => 'lakit-metabox--field-half',
							'post_type'   => 'product',
							'action'      => 'lakit_theme_search_posts',
							'label'       => esc_html__( 'WooCommerce Product', 'lastudio-kit' ),
						),
						'available_on' => array(
							'type'        => 'repeater',
							'id'          => 'available_on',
							'name'        => 'available_on',
							'label'       => esc_html__('Available On', 'lastudio-kit'),
							'ui_kit'      => false,
							'collapsed'   => true,
							'class'       => 'lakit-metabox--field-full',
							'title_field' => 'type',
							'fields'      => array(
								'type' => array(
									'type'        => 'select',
									'name'        => 'type',
									'id'          => 'type',
									'filter'      => false,
									'label'       => esc_html__( 'Type', 'lastudio-kit' ),
									'options'     => array(
										''              => esc_html__('Select', 'lastudio-kit'),
										'spotify'       => esc_html__('Spotify', 'lastudio-kit'),
										'youtube'       => esc_html__('Youtube', 'lastudio-kit'),
										'itunes'        => esc_html__('iTunes', 'lastudio-kit'),
										'soundcloud'    => esc_html__('Soundcloud', 'lastudio-kit'),
										'bandcamp'      => esc_html__('Bandcamp', 'lastudio-kit'),
										'googleplay'    => esc_html__('Google Play', 'lastudio-kit'),
										'amazon'        => esc_html__('Amazon', 'lastudio-kit'),
										'custom1'       => esc_html__('Custom 1', 'lastudio-kit'),
										'custom2'       => esc_html__('Custom 2', 'lastudio-kit'),
										'custom3'       => esc_html__('Custom 3', 'lastudio-kit'),
									),
								),
								'link' => array(
									'type'        => 'text',
									'name'        => 'link',
									'id'          => 'link',
									'label'       => esc_html__( 'Link', 'lastudio-kit' ),
									'description' => esc_html__( 'Enter a link.', 'lastudio-kit' ),
									'placeholder' => esc_html__( 'https://website.com', 'lastudio-kit' ),
								),
							)
						),
						'lyrics' => array(
							'type'        => 'wysiwyg',
							'name'        => 'lyrics',
							'id'          => 'lyrics',
							'class'       => 'lakit-metabox--field-full',
							'label'       => esc_html__( 'Lyrics', 'lastudio-kit' ),
							'rows'        => 5
						),
					)
				)
			),
		) );

		lastudio_kit_term_meta()->add_options( array (
			'tax'        => 'la_album_artist',
			'id'         => 'lastudiokit-artist-settings',
			'fields'     => [
				'_thumbnail_id' => array(
					'type'        => 'media',
					'class'       => 'lakit-metabox--field-half',
					'label'       => esc_html__( 'Thumbnail', 'lastudio-kit' ),
					'multi_upload'=> false,
					'library_type'=> 'image'
				),
			],
			'admin_columns' => [
				'lakit_thumb' => array(
					'label'    => sprintf('<span class="lakit-image">%1$s</span>', __('Images', 'lastudio-kit')),
					'callback' => array( $this, 'term_metabox_callback__column' ),
					'position' => 1,
				),
			]
		) );
	}

	public function metabox_callback__column( $column, $post_id ){
		if($column === 'lakit_thumb'){
			return printf('<a href="%2$s">%1$s</a>', get_the_post_thumbnail($post_id), get_edit_post_link($post_id) );
		}
		elseif ( $column === 'album_release_date' ){
			$_date = get_post_meta( $post_id, $column, true );
			return printf('<span>%1$s</span>', $_date);
		}
	}

	public function term_metabox_callback__column( $column, $term_id ){
		if($column === 'lakit_thumb'){
			$image_id = get_term_meta($term_id, '_thumbnail_id', true);
			return sprintf('<a href="%2$s">%1$s</a>', wp_get_attachment_image( $image_id ), get_edit_term_link($term_id, 'la_album_artist') );
		}
	}

	public function get_album_tracks( $post_id ){
		$tracks = [];
		$metadata = get_post_meta( $post_id, 'album_tracks', true );
		$album_title = get_the_title( $post_id );
		$album_description = '';
		if(!empty($metadata)){
			foreach ($metadata as $item){
				$_source = false;
				if( !empty($item['source']) ){
					$_source = wp_get_attachment_url($item['source']);
				}
				if(empty($_source) && isset($item['source_custom'])){
					$_source = $item['source_custom'];
				}
				if(!empty($_source)){
					$_lyrics = $item['lyrics'] ?? '';
					if(!empty($_lyrics)){
						$_lyrics = lastudio_kit_helper()->transfer_text($_lyrics, true);
					}
					$tracks[] = [
						'src' => $_source,
						'image' => wp_get_attachment_url($item['preview']),
						'title' => $item['title'] ?? '',
						'artist' => $item['artist'] ?? '',
						'product' => $item['product'] ?? '',
						'album' => $album_title,
						'description' => $album_description,
						'lyrics' => $_lyrics,
						'length' => $item['length'] ?? '00:00',
						'available_on' => array_values($item['available_on'] ?? []),
					];
				}
			}
		}
		return $tracks;
	}

	public function get_playlist_configs( $config, $post_id ){
		if(empty($post_id) && ( is_singular('la_album') || wp_is_json_request() )){
			$post_id = get_the_ID();
		}
		$config['album_id'] = $post_id;
		$config['sources'] = $this->get_album_tracks( $post_id );
		$available_on = get_post_meta( $post_id, 'album_available_on', true );
		if(empty($available_on)){
			$available_on = [];
		}
		$config['available_on'] = array_values($available_on);
		$config['preview']      = get_the_post_thumbnail_url( $post_id, 'full' );
		$config['title']        = get_the_title( $post_id );
		$config['description']  = lastudio_kit_helper()->transfer_text(get_post_field( 'post_content', $post_id ), true);
		return $config;
	}

	public function get_first_item_end_time( $endtime, $post_id ){
		$all_tracks = $this->get_album_tracks( $post_id );
		return $all_tracks[0]['length'] ?? $endtime;
	}
}