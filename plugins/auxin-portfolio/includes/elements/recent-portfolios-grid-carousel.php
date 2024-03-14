<?php

/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_recent_portfolios_grid_carousel_callback( $atts, $shortcode_content = null ){

    // Defining default attributes
    $default_atts = array(
        'title'                       => '', // header title
        'cat'                         => ' ',
        'num'                         => '8', // max generated entry
        'only_posts__in'              => '', // display only these post IDs. array or string comma separated
        'include'                     => '', // include these post IDs in result too. array or string comma separated
        'exclude'                     => '', // exclude these post IDs from result. array or string comma separated
        'posts_per_page'              => -1,
        'offset'                      => '',
        'paged'                       => '',
        'order_by'                    => 'date',
        'order'                       => 'DESC',
        'exclude_without_media'       => 0,
        'display_like'                => 1,
        'deeplink'                    => 0,
        'deeplink_slug'               => uniqid('portfolio-'),
        'reveal_transition_duration'  => '600',
        'reveal_between_delay'        => '60',
        'hide_transition_duration'    => '600',
        'hide_between_delay'          => '30',
        'item_style'                  => 'classic',
        'perpage'                     => 10,
        'carousel_navigation_control_text_next' => __('Next', 'auxin-portfolio'),
        'carousel_navigation_control_text_prev' => __('Previous', 'auxin-portfolio') , 
        'preloadable'                 => false,
        'preload_preview'             => true,
        'preload_bgcolor'             => '',
        
        'show_title'                  => 1,
        'show_info'                   => 1,
        'image_aspect_ratio'          => 0.75,
        'space'                       => 30,
        'desktop_cnum'                => 4,
        'tablet_cnum'                 => 'inherit',
        'phone_cnum'                  => '1',
        'tax_args'                    => '',
        'tag'                         => '',
        'carousel_space'              => '30',
        'carousel_autoplay'           => false,
        'carousel_autoplay_delay'     => '2',
        'carousel_navigation'         => 'peritem',
        'carousel_navigation_control' => 'arrows',
        'carousel_nav_control_pos'    => 'center',
        'carousel_nav_control_skin'   => 'boxed',
        'carousel_loop'               => 1,
        'extra_classes'               => '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'universal_id'                => '',
        'reset_query'                 => true,
        'use_wp_query'                => false, // true to use the global wp_query, false to use internal custom query
        'wp_query_args'               => array(), // additional wp_query args
        'loadmore_type'               => '', // 'next' (more button), 'scroll', 'next-prev'
        'loadmore_label'              => 'text',
        'loadmore_per_page'           => '',
        'base'                        => 'aux_recent_portfolios_grid_carousel',
        'base_class'                  => 'aux-widget-recent-portfolios-carousel'
    );
    
    if ( !isset( $atts['carousel_space' ] ) && isset( $atts['space'] ) ) {
        $atts['carousel_space'] = $atts['space'];
    }
    
    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    // Validate the boolean variables
    $exclude_without_media = auxin_is_true( $exclude_without_media );
    $display_like          = auxin_is_true( $display_like );
    $deeplink              = auxin_is_true( $deeplink );
    $display_title         = auxin_is_true( $show_title );
    $show_info             = auxin_is_true( $show_info );

    ob_start();

    if( empty( $cat ) || $cat == " " || ( is_array( $cat ) && in_array( " ", $cat ) ) ) {
        $tax_args = array();
    } else {
        $tax_args = array(
            array(
                'taxonomy' => 'portfolio-cat',
                'field'    => 'term_id',
                'terms'    => ! is_array( $cat ) ? explode( ",", $cat ) : $cat
            )
        );
    }

    global $wp_query;

    if( ! $use_wp_query ) {
        // create wp_query to get latest items -----------
        $args = array(
            'post_type'             => 'portfolio',
            'orderby'               => $order_by,
            'order'                 => $order,
            'offset'                => $offset,
            'paged'                 => $paged,
            'tax_query'             => $tax_args,
            'post_status'           => 'publish',
            'posts_per_page'        => $num,
            'ignore_sticky_posts'   => 1,

            'include_posts__in'     => $include, // include posts in this list
            'posts__not_in'         => $exclude, // exclude posts in this list
            'posts__in'             => $only_posts__in, // only posts in this list

            'exclude_without_media' => $exclude_without_media
        );

        // ---------------------------------------------------------------------

        // add the additional query args if available
        if( $wp_query_args ){
            $args = wp_parse_args( $args, $wp_query_args );
        }

        // pass the args through the auxin query parser
        $wp_query = new WP_Query( auxin_parse_query_args( $args ) );
    }

    $post_counter       = 0;
    $items_classes      = '';
    $column_class       = '';
    $phone_break_point  = 767;
    $tablet_break_point = 1025;

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

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
            $frame_effect_classes = 'aux-frame-boxed-darken aux-frame-zoom';
            $hover_classes = 'aux-hover-active';
            $show_lightbox = false;
            $template_file = 'column-overlay';
            break;
        case 'overlay-lightbox':
            $frame_effect_classes = 'aux-frame-darken aux-frame-zoom';
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $template_file = 'column-overlay';
            break;
        case 'overlay-lightbox-boxed':
            $frame_effect_classes = 'aux-frame-boxed-darken aux-frame-zoom';
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

    // generate columns class
    $tablet_cnum = ('inherit' == $tablet_cnum  ) ? $desktop_cnum : $tablet_cnum ;
    $phone_cnum  = ('inherit' == $phone_cnum  )  ? $tablet_cnum : $phone_cnum;

    $items_classes      = 'aux-portfolio-carousel aux-portfolio-columns master-carousel aux-no-js aux-mc-before-init' . ' aux-' . $carousel_nav_control_pos . '-control';
    $column_media_width = auxin_get_content_column_width( $desktop_cnum, $space, $content_width );

    if ( $show_lightbox ) {
        $items_classes .= ' aux-lightbox-gallery';
    }

    // genereate the master carousel attributes
    $carousel_attrs  =  'data-columns="' . esc_attr( $desktop_cnum ) . '"';
    $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-autoplay="true"' : '';
    $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-delay="' . esc_attr( $carousel_autoplay_delay ) . '"' : '';
    $carousel_attrs .= ' data-navigation="' . esc_attr( $carousel_navigation ) . '"';
    $carousel_attrs .= ' data-space="' . esc_attr( $carousel_space ). '"';
    $carousel_attrs .= auxin_is_true( $carousel_autoplay ) ? ' data-loop="' . esc_attr( $carousel_loop ) . '"' : '';
    $carousel_attrs .= ' data-wrap-controls="true"';
    $carousel_attrs .= ' data-bullets="' . ('bullets' == $carousel_navigation_control ? 'true' : 'false') . '"';
    $carousel_attrs .= ' data-bullet-class="aux-bullets aux-small aux-mask"';
    $carousel_attrs .= ' data-arrows="' . ( in_array( $carousel_navigation_control, array( 'arrows', 'text' ) ) ? 'true' : 'false') . '"';
    $carousel_attrs .= ' data-same-height="true"';

    if ( 'inherit' != $tablet_cnum || 'inherit' != $phone_cnum ) {
        $carousel_attrs .= ' data-responsive="'. esc_attr( ( 'inherit' != $tablet_cnum  ? $tablet_break_point . ':' . $tablet_cnum . ',' : '' ).
                                                           ( 'inherit' != $phone_cnum   ? $phone_break_point  . ':' . $phone_cnum : '' ) ) . '"';
    }

    $have_posts = $wp_query->have_posts();

    if( $have_posts ){

        echo sprintf( '<div class="aux-mc-wrapper"><div data-element-id="%s" class="%s" %s>', esc_attr( $universal_id ), esc_attr( $items_classes ), $carousel_attrs );

        while ( $wp_query->have_posts() ) {

            $wp_query->the_post();
            $post         = $wp_query->post;
            $column_class = 'aux-mc-item';
            $item_inner_classes = '';

            $post_vars = auxin_get_post_type_media_args(
                $post,
                array(
                    'post_type'          => 'portfolio',
                    'request_from'       => 'archive',
                    'media_width'        => $phone_break_point,
                    'media_size'         => array( 'width' => $column_media_width, 'height' => $column_media_width * $image_aspect_ratio ),
                    'upscale_image'      => true,
                    'image_from_content' => ! $exclude_without_media, // whether to try to get image from content or not
                    'add_image_hw'       => false, // whether add width and height attr or not
                    'no_gallery'         => true,
                    'preloadable'        => $preloadable,
                    'preload_preview'    => $preload_preview,
                    'preload_bgcolor'    => $preload_bgcolor,
                    'image_sizes'        => 'auto',
                    'srcset_sizes'       => 'auto'
                ),
                $content_width
            );
            extract( $post_vars );

            $lightbox_attrs = 'data-elementor-open-lightbox="no" ';
            // Lightbox attributes
            if ( $show_lightbox ) {
                $attach_id = get_post_thumbnail_id($post->ID);
                $image_primary_meta = wp_get_attachment_metadata( $attach_id );
                $lightbox_attrs .= 'data-original-width="' . $image_primary_meta['width'] . '" data-original-height="' . $image_primary_meta['height'] . '" ' .
                                  'data-caption="' . auxin_attachment_caption( $attach_id ) . '"';
            }

            printf( '<div class="%s post-%s">', esc_attr( $column_class ), esc_attr( $post->ID ) );
            include auxin_get_template_file( 'theme-parts/entry/portfolio', $template_file, AUXPFO()->template_path() );
            echo    '</div>';

        }
        if ( 'text' === $carousel_navigation_control ) { ?>
            <div class="aux-carousel-controls">
                <div class="aux-next-arrow">
                    <span class="aux-text-arrow"> <?php echo esc_html( $carousel_navigation_control_text_next ) ;?> </span>
                </div>
                <div class="aux-prev-arrow">
                    <span class="aux-text-arrow"> <?php echo esc_html( $carousel_navigation_control_text_prev ) ;?> </span>
                </div>
            </div> <?php
        } else if ( 'arrows' === $carousel_navigation_control ) {
            if ( 'boxed' === $carousel_nav_control_skin ) :?>
                <div class="aux-carousel-controls">
                    <div class="aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                        <span class="aux-svg-arrow aux-small-right"></span>
                        <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-right"></span>
                    </div>
                    <div class="aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                        <span class="aux-svg-arrow aux-small-left"></span>
                        <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-left"></span>
                    </div>
                </div>
            <?php else : ?>
                <div class="aux-carousel-controls">
                    <div class="aux-next-arrow">
                        <span class="aux-svg-arrow aux-l-right"></span>
                    </div>
                    <div class="aux-prev-arrow">
                        <span class="aux-svg-arrow aux-l-left"></span>

                    </div>
                </div>
            <?php  endif;
        }
        
        echo '</div></div>';

    } // End if have_posts


    if( $reset_query ){
        wp_reset_query();
    }

    // return false if no result found
    if( ! $have_posts ){
        ob_get_clean();
        return false;
    }

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
