<?php
function auxin_recent_portfolio( $args= array() ) {

    global $post;

    $defaults = array (
        'title'                      => '', // header title
        'cat'                        => '',
        'num'                        => '8', // max generated entry
        'only_posts__in'             => '', // display only these post IDs. array or string comma separated
        'include'                    => '', // include these post IDs in result too. array or string comma separated
        'exclude'                    => '', // exclude these post IDs from result. array or string comma separated
        'posts_per_page'             => -1,
        'offset'                     => '',
        'order_by'                   => 'date',
        'order'                      => 'DESC',
        'exclude_without_media'      => 0,
        'display_like'               => 1,
        'deeplink'                   => 0,
        'deeplink_slug'              => uniqid('portfolio-'),
        'show_filters'               => 1,
        'filter_by'                  => 'portfolio-filter',
        'filter_style'               => 'aux-slideup',
        'filter_align'               => 'aux-left',
        'reveal_transition_duration' => '600',
        'reveal_between_delay'       => '60',
        'hide_transition_duration'   => '600',
        'hide_between_delay'         => '30',
        'item_style'                 => 'classic',
        'tile_style_pattern'         => 'default',
        'tiles_item_style'           => 'overlay',
        'entry_background_color'     => '',
        'entry_border_color'         => '',
        'paginate'                   => 1,
        'perpage'                    => 10,
        'crop'                       => true,
        'display_title'              => 1,
        'display_read_more'          => 0,
        'show_info'                  => 1,
        'image_aspect_ratio'         => 0.75,
        'space'                      => 30,
        'desktop_cnum'               => 4,
        'tablet_cnum'                => 'inherit',
        'phone_cnum'                 => '1',
        'layout'                     => 'grid',
        'tag'                        => '',
        'filter'                     => '',
        'content_width'              => '',
        'skip_wrappers'              => '',
        'extra_classes'              => '',
        'extra_column_classes'       => '',
        'custom_el_id'               => '',
        'template_part_file'         => 'recent-portfolio',
        'extra_template_path'        =>  AUXPFO_PUB_DIR . '/templates/elements',
        'universal_id'               => '',
        'use_ajax'                   => 0,
        'term'                       => '',
        'query_args'                 => array(),
        'reset_query'                => true,
        'use_wp_query'               => false, // true to use the global wp_query, false to use internal custom query
        'wp_query_args'              => array(), // additional wp_query args,
        'custom_wp_query'            => '',
        'loadmore_type'              => '', // 'next' (more button), 'scroll', 'next-prev'
        'loadmore_label'             => 'text',
        'loadmore_per_page'          => '',
        'term_field'                 => 'slug',
        'base'                       => 'aux_recent_portfolios_grid',
        'base_class'                 => 'aux-widget-recent-portfolios',
        'override_global_query'      => false
    );

    $args = wp_parse_args( $args, $defaults );
    extract( $args );

    $isotope_id      = uniqid();
    $wrapper_classes = 'aux-portfolio-columns aux-ajax-view aux-isotope-animated ';
    $is_tiles        = false;
    $is_masonry      = false;
    $is_grid         = false;
    $output          = '';
    $post_counter      = 0;
    $item_classes = '';

    $tablet_cnum   = ('inherit' == $tablet_cnum  ) ? $desktop_cnum : $tablet_cnum ;
    $resp_classes  = ' aux-de-col' . $desktop_cnum;
    $resp_classes .= ' aux-tb-col' . $tablet_cnum;
    $resp_classes .= ' aux-mb-col' . $phone_cnum;

    $column_media_width = auxin_get_content_column_width( $desktop_cnum, $space, $content_width );

    if( ! empty( $called_from ) && 'elementor' == $called_from ){
        $is_boxed = false;
    } else {
        $is_boxed = in_array( $item_style, array( 'classic', 'classic-lightbox', 'classic-lightbox-boxed' ) ) && ( $entry_background_color || $entry_border_color );
    }

    switch( $layout ) {
        case 'masonry':
            $is_masonry       = true;
            $isotope_layout   = 'masonry';
            $wrapper_classes .= 'aux-isotope-layout aux-layout-grid aux-no-gutter aux-row' . $resp_classes ;
            $crop = false;
            $column_media_height = 0;
            break;
        case 'grid':
            $is_grid          = true;
            $isotope_layout   = 'fitRows';
            $wrapper_classes .= 'aux-isotope-layout aux-layout-grid aux-no-gutter aux-row aux-match-height' . $resp_classes;
            $column_media_height = $column_media_width * $image_aspect_ratio;

            break;
        case 'tiles':
            $is_tiles         = true;
            $isotope_layout   = 'packery';
            $wrapper_classes .= 'aux-tiles-layout';
            $item_style = $tiles_item_style;
            $space = 0;
            $desktop_cnum = 4;
            $column_media_height = '';
            $column_media_width = $content_width;
            break;
        default:
            $is_grid        = true;
            $isotope_layout = 'fitRows';
            break;
    }

    // check item style and define related variables
    switch ( $item_style ) {
        case 'classic-lightbox':
            $frame_effect_classes = 'aux-frame-darken aux-frame-zoom';
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $template_file = 'column';
            break;
        case 'classic-lightbox-boxed':
            $frame_effect_classes = 'aux-frame-boxed-darken aux-frame-zoom';
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $template_file = 'column';
            break;
        case 'overlay':
            $frame_effect_classes = 'aux-frame-darken aux-frame-zoom';
            $hover_classes = 'aux-hover-active';
            $show_lightbox = false;
            $template_file = 'column-overlay';
            break;
        case 'overlay-boxed':
            $frame_effect_classes = 'aux-frame-boxed-darken' . ( $is_tiles ? '' : ' aux-frame-zoom');
            $hover_classes = 'aux-hover-active';
            $show_lightbox = false;
            $template_file = 'column-overlay';
            break;
        case 'overlay-lightbox':
            $frame_effect_classes = 'aux-frame-darken' . ( $is_tiles ? '' : ' aux-frame-zoom');
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $template_file = 'column-overlay';
            break;
        case 'overlay-lightbox-boxed':
            $frame_effect_classes = 'aux-frame-boxed-darken' . ( $is_tiles ? '' : ' aux-frame-zoom');
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $template_file = 'column-overlay';
            break;
        default:
            $frame_effect_classes = '';
            $hover_classes = '';
            $show_lightbox = false;
            $template_file = 'column';
    }

    // Isotope  Attributes
    $isoxin_attrs  = 'data-lazyload="true" data-space="'.esc_attr( $space ).'" data-pagination="'. ( $paginate ? 'true' : 'false' ) . '" data-deeplink="'. ( $deeplink ? 'true' : 'false' ) . '"';
    $isoxin_attrs .= ' data-slug="'. esc_attr( $deeplink_slug ).'" data-perpage="'.esc_attr( $perpage ).'" data-layout="'.esc_attr( $isotope_layout ).'"';
    $isoxin_attrs .= ' data-reveal-transition-duration="'. esc_attr( $reveal_transition_duration ).'" data-reveal-between-delay="'.esc_attr( $reveal_between_delay ).'"';
    $isoxin_attrs .= ' data-hide-transition-duration="'. esc_attr( $hide_transition_duration ).'" data-hide-between-delay="'.esc_attr( $hide_between_delay ).'"';

    $ajaxAttrs     = ' data-num="'. $num .'" data-order="'. $order .'" data-orderby="'. $order_by .'" data-taxonomy="'. $filter_by .'" data-n="'. wp_create_nonce( 'aux_ajax_filterable_portfolio' ) .'"';
    $isoxin_attrs .= $ajaxAttrs;

    $wrapper_classes .= $show_lightbox ? ' aux-lightbox-gallery' : '';

    $filter_by = auxin_is_true( $show_filters ) || 'archive' === $called_from || 'taxonomy' === $called_from ? $filter_by : 'portfolio-cat';

    /**
     * if the Request is from ajax , $term variable will be empty
     */

    $tax_args = array();
    $cat_args = array();

    if( ! empty( $args['cat'] ) && $args['cat'] != " " && ( ! is_array( $args['cat'] ) || ! in_array( " ", $args['cat'] ) ) ) {
        $cat_args = array(
            'taxonomy' => 'portfolio-cat',
            'field'    => 'term_id',
            'terms'    => ! is_array( $args['cat'] ) ? explode( ",", $args['cat'] ) : $args['cat']
        );

        $tax_args = array( $cat_args );
    }

    if ( auxin_is_true( $args['use_ajax' ] ) ) {

        if ( ( empty( $args['term'] ) || $args['term'] === "all" ) ) {
            $tax_args = ! empty ( $cat_args ) ? array( $cat_args ) : $cat_args;

        } else if (  ! empty( $args['term'] ) ) {

            $term_args = array(
                'taxonomy' => $filter_by,
                'field'    => 'term_id',
                'terms'    => $args['term']
            );

            if ( empty ( $cat_args ) ) {
                $tax_args = array( $term_args );
            } else {
                $tax_args = array (
                    'relation' => 'AND',
                    array( $cat_args ),
                    array( $term_args )
                );
            }

        }

    }

    if ( 'taxonomy'  === $args['called_from'] ) {
        $tax_args = array(
            array(
                'taxonomy' => $args['filter_by'],
                'field'    => 'term_id'
            )
        );

        if ( $args['filter_by'] == 'portfolio-cat' ) {
            $tax_args[0]['terms'] = $args['cat'];
        } elseif ( $args['filter_by'] == 'portfolio-tag' ) {
            $tax_args[0]['terms'] = $args['tag'];
        } elseif ( $args['filter_by'] == 'portfolio-filter' ) {
            $tax_args[0]['terms'] = $args['filter'];
        }
    }

    $query_arg = array(
        'post_type'             => 'portfolio',
        'orderby'               => $order_by,
        'order'                 => $order,
        'offset'                => $offset,
        'post_status'           => 'publish',
        'posts_per_page'        => $num ? $num : -1,
        'ignore_sticky_posts'   => 1,
        'tax_query'             => $tax_args,
        'include_posts__in'     => $include, // include posts in this list
        'posts__not_in'         => $exclude, // exclude posts in this list
        'posts__in'             => $only_posts__in, // only posts in this list
        'exclude_without_media' => $exclude_without_media,
        'paged'                 => $paged
    );

    // pass the args through the auxin query parser
    if ( $custom_wp_query) {
        $wp_query = $custom_wp_query;
    } else {
        if ( $override_global_query ) {
            global $wp_query;
        }
        $wp_query = new WP_Query( auxin_parse_query_args( $query_arg ) );
    }
    $have_posts = $wp_query->have_posts();

    if( $have_posts ){

        if ( ! $skip_wrappers ) {
            // Generate Wrapper Markup
            echo sprintf( '<div id="%s" data-element-id="%s" class="%s" %s>', esc_attr( $isotope_id ), esc_attr( $universal_id ), esc_attr( $wrapper_classes ), $isoxin_attrs);
            // Loading markup for Ajax LoadMore
            echo '<div class="aux-items-loading aux-loading-hide"><div class="aux-loading-loop"><svg class="aux-circle" width="100%" height="100%" viewBox="0 0 42 42"><circle class="aux-stroke-bg" r="20" cx="21" cy="21" fill="none"></circle><circle class="aux-progress" r="20" cx="21" cy="21" fill="none" transform="rotate(-90 21 21)"></circle></svg></div></div>';
            //End of Loading markup
        }

        while ( $wp_query->have_posts() ) {

            // break the loop if it is reached to the limit
            if ( ! $num || $post_counter < $num ) {
                $post_counter ++;
            } else {
                break;
            }


            $wp_query->the_post();
            $post = $wp_query->post;

            if ( $is_tiles ) {

                $tile_pattern_info = auxin_get_tile_pattern( $tile_style_pattern, $post_counter - 1, $column_media_width );
                $post_vars = auxpfo_get_portfolio_config(
                    $post,
                    array(
                        'request_from'    => 'archive',
                        'media_width'     => 726,
                        'media_size'      => $tile_pattern_info['size'],
                        'upscale_image'   => true,
                        'preloadable'     => false, // pass null to disable lazyloading while respecting the "sizes" => "auto"
                        'crop'            => true,
                        'add_image_hw'    => true, // whether add width and height attr or not
                        'image_sizes'     => $tile_pattern_info['image_sizes'],
                        'srcset_sizes'    => $tile_pattern_info['srcset_sizes']
                    )
                );

                $item_classes = $tile_pattern_info['classname'];
                $item_inner_classes = '';

            } else  {

                $post_vars = auxpfo_get_portfolio_config(
                    $post,
                    array(
                        'request_from'    => 'archive',
                        'media_width'     => 726,
                        'media_size'      => array( 'width' => $column_media_width, 'height' =>  $column_media_height ),
                        'preloadable'     => null, // pass null to disable lazyloading while respecting the "sizes" => "auto"
                        'preload_preview' => false,
                        'crop'            => $crop,
                        'add_image_hw'    => true, // whether add width and height attr or not
                        'image_sizes'     => 'auto',
                        'srcset_sizes'    => 'auto'
                    )
                );

                $item_classes = 'aux-col';
                $item_inner_classes = '';
            }

            extract( $post_vars );

            if ( !$has_attach ) {
                $post_counter --;
                continue;
            }
            // Extra conditions
            $item_classes .= $is_boxed ? ' aux-entry-boxed' : '';
            $item_classes .= !empty( $loadmore_type ) ? ' aux-ajax-item' : '';

            if ( $show_filters && ! auxin_is_true( $use_ajax ) ) {
                $filters = wp_get_post_terms( $post->ID, $filter_by );
                foreach ( $filters as $filter ) {
                    $filter->slug = str_replace( '%', '-', $filter->slug );
                    $item_classes .= ' '. $filter->slug;
                }
            }


            // // Lightbox attributes
            if ( $show_lightbox ) {
                $attach_id          = get_post_thumbnail_id($post->ID);
                $img_caption        = auxin_attachment_caption( $attach_id );
                $image_primary_meta = wp_get_attachment_metadata( $attach_id );
                $lightbox_attrs     = sprintf( 'data-elementor-open-lightbox = "no" data-original-width = "%s" data-original-height = "%s" data-caption = "%s"',
                                            $image_primary_meta['width'], $image_primary_meta['height'], $img_caption
                                      );
            }

            if ( $paginate && $post_counter > $perpage ) {
                echo sprintf('<div class="aux-iso-item aux-iso-hidden aux-loading %s">', $item_classes );
            } else {
                echo sprintf('<div class="aux-iso-item aux-loading %s">', $item_classes );
            }

            include auxin_get_template_file( 'theme-parts/entry/portfolio', $template_file, AUXPFO()->template_path() );


            echo '</div>';

        }

        if( ! $skip_wrappers ) {

            // End of Wrapper Markup
            echo '</div>';

            // Execute load more functionality
            if( $wp_query->found_posts > $loadmore_per_page ) {
                echo auxin_get_load_more_controller( $loadmore_type, $loadmore_label );
            }

        } else {
            // Get post counter in the query
            echo '<span class="aux-post-count hidden">'.$wp_query->post_count.'</span>';
            echo '<span class="aux-all-posts-count hidden">'.$wp_query->found_posts.'</span>';
        }

        if ( $is_boxed ) {
            // Set background color style
            $entry_background_color = $entry_background_color   ? "background-color: $entry_background_color;" : '';
            // Set border color style
            $entry_border_color     = $entry_border_color       ? "border:1px solid $entry_border_color;" : '';

            if ( !empty( $entry_background_color ) || !empty( $entry_border_color ) ) {
                echo sprintf(
                    '<style>
                    .page .aux-widget-recent-portfolios .entry-main  { %s %s }
                    </style>',
                    $entry_background_color,
                    $entry_border_color
                );
            }

        }

    }

    if( $reset_query ){
        wp_reset_query();
    }

    // return false if no result found
    if( ! $have_posts ){
        ob_get_clean();
        return false;
    }

}

