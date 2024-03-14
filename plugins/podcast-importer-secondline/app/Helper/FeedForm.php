<?php

namespace PodcastImporterSecondLine\Helper;

class FeedForm {

  public static function get_for_render( $post_id = null ) :array {
    $field_definitions = self::field_definitions();
    $response = [];

    foreach( $field_definitions as $key => $field_definition ) {
      if( !isset( $field_definition[ 'storage' ] ) )
        continue;

      if( isset( $field_definition[ 'views' ] ) ) {
        if( !in_array( 'add', $field_definition[ 'views' ] ) && !is_numeric( $post_id ) )
          continue;

        if( !in_array( 'edit', $field_definition[ 'views' ] ) && is_numeric( $post_id ) )
          continue;

        unset( $field_definition[ 'views' ] );
      }

      $storage = $field_definition[ 'storage' ];

      unset( $field_definition[ 'storage' ] );

      if( is_numeric( $post_id ) ) {
        $field_definition[ 'value' ] = null;

        if( $storage[ 'type' ] === 'meta' )
          $field_definition[ 'value' ] = get_post_meta( $post_id, $storage[ 'meta' ], ( $storage['meta_is_single'] ?? true ) );

        if( isset( $field_definition[ 'options' ] )
            && !is_array( $field_definition[ 'value' ] )
            && !isset( $field_definition[ 'options' ][ $field_definition[ 'value' ] ] )
            && !empty( $field_definition[ 'value' ] )
        )
          $field_definition[ 'options' ] = [ $field_definition[ 'value' ] => $field_definition[ 'value' ] ] + $field_definition[ 'options' ];
      }

      if( ( !isset( $field_definition[ 'value' ] ) || $field_definition[ 'value' ] === null ) && isset( $field_definition[ 'default' ] ) )
        $field_definition[ 'value' ] = $field_definition[ 'default' ];

      unset( $field_definition[ 'default' ] );

      $response[ $key ] = $field_definition;
    }

    return apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_form_for_render', $response, $field_definitions );
  }

  public static function field_definitions() :array {
    $response = [];

    $response[ 'feed_url' ] = [
      'label'       => __( 'Podcast Feed URL', 'podcast-importer-secondline' ),
      'name'        => 'feed_url',
      'type'        => 'url',
      'required'    => 1,
      'placeholder' => 'https://dixie.secondlinethemes.com/feed/podcast',
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_rss_feed'
      ]
    ];

    $post_type_options = [];

    foreach( podcast_importer_secondline_supported_post_types() as $post_type )
      $post_type_options[ $post_type ] = get_post_type_object( $post_type )->labels->singular_name;

    $response[ 'post_type' ] = [
      'label'       => __( 'Post Type', 'podcast-importer-secondline' ),
      'name'        => 'post_type',
      'type'        => 'select',
      'options'     => $post_type_options,
      'default'     => podcast_importer_secondline_default_post_type(),
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_post_type'
      ]
    ];

    $response[ 'post_status' ] = [
      'label'       => __( 'Post Status', 'podcast-importer-secondline' ),
      'name'        => 'post_status',
      'type'        => 'select',
      'options'     => [
        'publish' => __( 'Publish', 'podcast-importer-secondline' ),
        'draft'   => __( 'Save as Draft', 'podcast-importer-secondline' )
      ],
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_publish'
      ]
    ];

    $response[ 'post_author' ] = [
      'label'       => __( 'Post Author', 'podcast-importer-secondline' ),
      'name'        => 'post_author',
      'type'        => 'wp_dropdown_users',
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_author'
      ]
    ];

    $response[ 'post_taxonomies' ] = [
      'label'       => __( 'Categories (from any taxonomy)', 'podcast-importer-secondline' ),
      'name'        => 'post_taxonomies',
      'type'        => 'multiple_select',
      'options'     => podcast_importer_secondline_get_taxonomies_select_definition( array_keys( $post_type_options ), true ),
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_category'
      ]
    ];

    if( podcast_importer_secondline_has_parent_show_support() ) {

      $response[ 'secondline_parent_show' ] = [
        'label'       => __( 'Parent Show Post', 'podcast-importer-secondline' ),
        'name'        => 'secondline_parent_show',
        'type'        => 'select',
        'options'     => [
          ''  => __( 'None','podcast-importer-secondline' )
        ] + podcast_importer_secondline_post_type_id_title_assoc( 'secondline_shows' ),
        'storage'     => [
          'type'  => 'meta',
          'meta'  => 'secondline_parent_show'
        ]
      ];

    }

    if( !podcast_importer_secondline_feed_limit_reached() ) {

      $response[ 'import_continuous' ] = [
        'label'           => __( 'Ongoing Import (Enable to continuously import future episodes)', 'podcast-importer-secondline'),
        'name'            => 'import_continuous',
        'type'            => 'checkbox',
        'value_unchecked' => 'off',
        'value_checked'   => 'on',
        'storage'         => [
          'type'  => 'meta',
          'meta'  => 'secondline_import_continuous'
        ],
        'views'       => [ 'add' ]
      ];

    }

    $response[ 'import_images' ] = [
      'label'           => __( 'Import Episode Featured Images', 'podcast-importer-secondline' ),
      'name'            => 'import_images',
      'type'            => 'checkbox',
      'value_unchecked' => 'off',
      'value_checked'   => 'on',
      'storage'         => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_images'
      ]
    ];

    $response[ 'import_embed_player' ] = [
      'label'           => __( 'Use an embed audio player instead of the default WordPress player (depending on your podcast host)', 'podcast-importer-secondline'),
      'name'            => 'import_embed_player',
      'type'            => 'checkbox',
      'value_unchecked' => 'off',
      'value_checked'   => 'on',
      'storage'         => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_embed_player'
      ]
    ];

    $response[ 'import_date_from' ] = [
      'label'           => __( 'Date Limit', 'podcast-importer-secondline' ),
      'name'            => 'import_date_from',
      'type'            => 'date',
      'placeholder'     => __( '01-01-2019', 'podcast-importer-secondline' ),
      'description'     => __( 'Optional: only import episodes after a certain date.', 'podcast-importer-secondline' ),
      'storage'         => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_date_from'
      ]
    ];

    $response[ 'import_content_tag' ] = [
      'label'       => __( 'Imported Content Tag', 'podcast-importer-secondline' ),
      'name'        => 'import_content_tag',
      'type'        => 'select',
      'options'     => [
        'content:encoded' => 'content:encoded',
        'description'     => 'description',
        'itunes:summary'  => 'itunes:summary'
      ],
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_content_tag'
      ]
    ];

    $response[ 'import_truncate_post' ] = [
      'label'       => __( 'Truncate Post Content', 'podcast-importer-secondline' ),
      'name'        => 'import_truncate_post',
      'type'        => 'number',
      'description' => __( 'Optional: Will trim the post content when imported to the character amount below.', 'podcast-importer-secondline' ) . 
                       __( 'Leave empty to skip trimming, set to 0 to skip content import.', 'podcast-importer-secondline' ),
      'storage'     => [
        'type'  => 'meta',
        'meta'  => 'secondline_truncate_post'
      ]
    ];

    $response[ 'import_episode_number' ] = [
      'label'           => __( 'Append episode number to post title', 'podcast-importer-secondline' ),
      'name'            => 'import_episode_number',
      'type'            => 'checkbox',
      'value_unchecked' => 'off',
      'value_checked'   => 'on',
      'storage'         => [
        'type'  => 'meta',
        'meta'  => 'secondline_import_episode_number'
      ]
    ];

    $response[ 'import_prepend_title' ] = [
      'label'           => __( 'Append custom text to post title', 'podcast-importer-secondline' ),
      'name'            => 'import_prepend_title',
      'type'            => 'text',
      'placeholder'     => __( 'Ex: My Podcast', 'podcast-importer-secondline' ),
      'description'     => sprintf( __( 'Optional: Add %s to display the show name.', 'podcast-importer-secondline' ), '<code>[podcast_title]</code>' ),
      'value_unchecked' => 'off',
      'value_checked'   => 'on',
      'storage'         => [
        'type'  => 'meta',
        'meta'  => 'secondline_prepend_title'
      ]
    ];

    return apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_form_definitions', $response );
  }

  public static function request_data_to_meta_map( $request_data ) :array {
    $field_definitions = self::field_definitions();
    $response = [];

    foreach( $field_definitions as $field_definition ) {
      if( !isset( $field_definition[ 'storage' ][ 'meta' ] ) )
        continue;

      if( isset( $request_data[ $field_definition[ 'name' ] ] ) ) {
        if( is_array( $request_data[ $field_definition[ 'name' ] ] ) ) {
          $response[ $field_definition[ 'storage' ][ 'meta' ] ] = array_map( 'intval', $request_data[ $field_definition[ 'name' ] ] );
          continue;
        }

        $response[ $field_definition[ 'storage' ][ 'meta' ] ] = sanitize_text_field( $request_data[ $field_definition[ 'name' ] ] );
        continue;
      }

      if( isset( $field_definition[ 'default' ] ) )
        $request_data[ $field_definition[ 'storage' ][ 'meta' ] ] = $field_definition[ 'default' ];
      else if( isset( $field_definition[ 'value_unchecked' ] ) )
        $request_data[ $field_definition[ 'storage' ][ 'meta' ] ] = $field_definition[ 'value_unchecked' ];
    }

    return apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_feed_form_request_data_to_meta_map', $response, $request_data, $field_definitions );
  }

}