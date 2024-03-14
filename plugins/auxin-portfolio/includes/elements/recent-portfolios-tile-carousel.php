<?php
/**
 * Recent Protfolio Tiles in Carousel Mode
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */

function auxin_get_recent_portfolios_tiles_carousel_master_array( $master_array ) {

    $master_array['aux_recent_portfolios_tile_carousel'] = array(
        'name'                          => __('Recent Portfolio on Tile Carousel', 'auxin-portfolio' ),
        'auxin_output_callback'         => 'auxin_widget_recent_portfolios_tiles_carousel_callback',
        'base'                          => 'aux_recent_portfolios_tile_carousel',
        'description'                   => __('It adds recent portfolio items in tile carousel', 'auxin-portfolio' ),
        'class'                         => 'aux-widget-recent-portfolios aux-carousel',
        'show_settings_on_create'       => true,
        'weight'                        => 1,
        'is_widget'                     => false,
        'is_shortcode'                  => true,
        'category'                      => THEME_NAME,
        'group'                         => '',
        'admin_enqueue_js'              => '',
        'admin_enqueue_css'             => '',
        'front_enqueue_js'              => '',
        'front_enqueue_css'             => '',
        'icon'                          => 'auxin-element aux-pb-icons-grid auxin-grid',
        'custom_markup'                 => '',
        'js_view'                       => '',
        'html_template'                 => '',
        'deprecated'                    => '',
        'content_element'               => '',
        'as_parent'                     => '',
        'as_child'                      => '',
        'params' => array(
            array(
                'heading'           => __('Title', 'auxin-portfolio' ),
                'description'       => __('Recent items title, leave it empty if you don`t need title.', 'auxin-portfolio'),
                'param_name'        => 'title',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => 'textfield',
                'class'             => 'title',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '' ,
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Categories', 'auxin-portfolio'),
                'description'       => __('Specifies a category that you want to show portfolio items from it.', 'auxin-portfolio' ),
                'param_name'        => 'cat',
                'type'              => 'aux_taxonomy',
                'taxonomy'          => 'portfolio-cat',
                'def_value'         => ' ',
                'holder'            => '',
                'class'             => 'cat',
                'value'             => ' ', // should use the taxonomy name
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Number of portfolios to show in per page', 'auxin-portfolio'),
                'description'       => __('Leave it empty to show all items', 'auxin-portfolio'),
                'param_name'        => 'num',
                'type'              => 'textfield',
                'value'             => '5',
                'holder'            => '',
                'class'             => 'num',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Portfolio tiles hover type','auxin-portfolio' ),
                'description'       => '',
                'param_name'        => 'item_style',
                'type'              => 'aux_visual_select',
                'def_value'         => 'overlay',
                'holder'            => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'layout',
                    'value'         => array( 'tiles' )
                ),
                'weight'            => '',
                'group'             => 'Style',
                'edit_field_class'  => '',
                'choices'             => array(
                    'overlay'       => array(
                        'label'     => __('Overlay title style 1', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitle1.webm webm'
                    ),
                    'overlay-boxed' => array(
                        'label'     => __('Overlay title style 2', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitle2.webm webm'
                    ),
                    'overlay-lightbox' => array(
                        'label'     => __('Overlay title with lightbox style 1', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitleLightbox1.webm webm'
                    ),
                    'overlay-lightbox-boxed' => array(
                        'label'     => __('Overlay title with lightbox style 2', 'auxin-portfolio'),
                        'video_src' => AUXPFO_ADMIN_URL . '/assets/images/preview/OverlayTitleLightbox2.webm webm'
                    )
                )
            ),
            array(
                'heading'           => __('Number of Pages', 'auxin-portfolio'),
                'description'       => '',
                'param_name'        => 'page',
                'type'              => 'textfield',
                'value'             => '2',
                'holder'            => '',
                'class'             => 'page',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude portfolios without media','auxin-portfolio' ),
                'description'       => '',
                'param_name'        => 'exclude_without_media',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
             array(
                'heading'            => __('Order by', 'auxin-portfolio'),
                'description'        => '',
                'param_name'         => 'order_by',
                'type'               => 'dropdown',
                'def_value'          => 'date',
                'holder'             => '',
                'class'              => 'order_by',
                'value'              => array (
                    'date'            => __('Date', 'auxin-portfolio'),
                    'menu_order date' => __('Menu Order', 'auxin-portfolio'),
                    'title'           => __('Title', 'auxin-portfolio'),
                    'ID'              => __('ID', 'auxin-portfolio'),
                    'rand'            => __('Random', 'auxin-portfolio'),
                    'comment_count'   => __('Comments', 'auxin-portfolio'),
                    'modified'        => __('Date Modified', 'auxin-portfolio'),
                    'author'          => __('Author', 'auxin-portfolio'),
                    'post__in'        => __('Inserted Post IDs', 'auxin-portfolio')
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Order', 'auxin-portfolio'),
                'description'       => '',
                'param_name'        => 'order',
                'type'              => 'dropdown',
                'def_value'         => 'DESC',
                'holder'            => '',
                'class'             => 'order',
                'value'             =>array (
                    'DESC'          => __('Descending', 'auxin-portfolio'),
                    'ASC'           => __('Ascending', 'auxin-portfolio'),
                ),
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Only portfolios','auxin-portfolio' ),
                'description'       => __('If you intend to display ONLY specific portfolios, you should specify them here. You have to insert the post IDs that are separated by comma (eg. 53,34,87,25).', 'auxin-portfolio' ),
                'param_name'        => 'only_posts__in',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Include portfolios','auxin-portfolio' ),
                'description'       => __('If you intend to include additional portfolios, you should specify them here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-portfolio' ),
                'param_name'        => 'include',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Exclude posts','auxin-portfolio' ),
                'description'       => __('If you intend to exclude specific posts from result, you should specify the posts here. You have to insert the Post IDs that are separated by comma (eg. 53,34,87,25)', 'auxin-portfolio' ),
                'param_name'        => 'exclude',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Start offset','auxin-portfolio' ),
                'description'       => __('Number of post to displace or pass over.', 'auxin-portfolio' ),
                'param_name'        => 'offset',
                'type'              => 'textfield',
                'value'             => '',
                'holder'            => '',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => 'Query',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Insert portfolio title','auxin-portfolio' ),
                'description'       => '',
                'param_name'        => 'display_title',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Insert portfolio meta','auxin-portfolio' ),
                'description'       => '',
                'param_name'        => 'show_info',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'admin_label'       => false,
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Post tile style','auxin-portfolio' ),
                'description'      => '',
                'param_name'       => 'tile_skin',
                'type'             => 'dropdown',
                'def_value'        => '',
                'holder'           => '',
                'class'            => 'tile_skin',
                'admin_label'      => false,
                'dependency'       => '',
                'weight'           => '',
                'group'            => 'Style',
                'edit_field_class' => '',
                'value'             => array(
                   'darken'          => __('Dark', 'auxin-portfolio'),
                   'lighten'         => __('Light', 'auxin-portfolio'),
                ),
            ),
            array(
                'heading'           => __('Navigation control', 'auxin-portfolio'),
                'description'       => '',
                'param_name'        => 'carousel_navigation_control',
                'type'              => 'dropdown',
                'def_value'         => 'arrows',
                'holder'            => '',
                'class'             => 'num',
                'value'             => array(
                   'arrows'         => __('Arrows', 'auxin-portfolio'),
                   'bullets'        => __('Bullets', 'auxin-portfolio'),
                   ''               => __('None', 'auxin-portfolio'),
                ),
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'admin_label'       => false,
                'group'             => 'Carousel',
                'edit_field_class'  => ''
            ),
            array(
                'heading'          => __('Button Navigation Style','auxin-portfolio' ),
                'description'      => '',
                'param_name'       => 'button_style',
                'type'             => 'aux_visual_select',
                'def_value'        => '',
                'holder'           => '',
                'class'            => 'button_style',
                'admin_label'      => false,
                'dependency'        => array(
                    'element'       => 'carousel_navigation_control',
                    'value'         => 'arrows'
                ),
                'weight'           => '',
                'group'            => 'Style',
                'edit_field_class' => '',
                'choices'          => array(
                    'pattern-1'             => array(
                        'label'    => __('Pattern 1', 'auxin-portfolio' ),
                        'image'    => AUXIN_URL . 'images/visual-select/button-normal.svg'
                    ),
                    'pattern-2'  => array(
                        'label'    => __('Pattern 2', 'auxin-portfolio' ),
                        'image'    => AUXIN_URL . 'images/visual-select/button-curved.svg'
                    ),
                ),
            ),
            array(
                'heading'           => __('Loop navigation','auxin-portfolio' ),
                'description'       => '',
                'param_name'        => 'carousel_loop',
                'type'              => 'aux_switch',
                'value'             => '1',
                'class'             => '',
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => 'Carousel',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Autoplay carousel','auxin-portfolio' ),
                'description'       => '',
                'param_name'        => 'carousel_autoplay',
                'type'              => 'aux_switch',
                'value'             => '0',
                'class'             => '',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => 'Carousel',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Autoplay delay','auxin-portfolio' ),
                'description'       => __('Specifies the delay between auto-forwarding in seconds.', 'auxin-portfolio' ),
                'param_name'        => 'carousel_autoplay_delay',
                'type'              => 'textfield',
                'value'             => '2',
                'holder'            => '',
                'class'             => 'excerpt_len',
                'admin_label'       => false,
                'dependency'        => array(
                    'element'       => 'preview_mode',
                    'value'         => 'carousel'
                ),
                'weight'            => '',
                'group'             => 'Carousel',
                'edit_field_class'  => ''
            ),
            array(
                'heading'           => __('Extra class name','auxin-portfolio' ),
                'description'       => __('If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'auxin-portfolio' ),
                'param_name'        => 'extra_classes',
                'type'              => 'textfield',
                'value'             => '',
                'def_value'         => '',
                'holder'            => '',
                'class'             => 'extra_classes',
                'admin_label'       => false,
                'dependency'        => '',
                'weight'            => '',
                'group'             => '',
                'edit_field_class'  => ''
            )
        )
    );

    return $master_array;
}

add_filter( 'auxin_master_array_shortcodes', 'auxin_get_recent_portfolios_tiles_carousel_master_array', 10, 1 );


/**
 * Element without loop and column
 * The front-end output of this element is returned by the following function
 *
 * @param  array  $atts              The array containing the parsed values from shortcode, it should be same as defined params above.
 * @param  string $shortcode_content The shorcode content
 * @return string                    The output of element markup
 */
function auxin_widget_recent_portfolios_tiles_carousel_callback( $atts, $shortcode_content = null ){

    global $aux_content_width;

    // Defining default attributes
    $default_atts = array(
        'title'                       => '', // header title
        'cat'                         => ' ',
        'num'                         => '5', // max generated entry
        'only_posts__in'              => '', // display only these post IDs. array or string comma separated
        'include'                     => '',  // include these post IDs in result too. array or string comma separated
        'exclude'                     => '',  // exclude these post IDs from result. array or string comma separated
        'posts_per_page'              => -1,
        'offset'                      => '',
        'item_style'                  => 'overlay',
        'paged'                       => '',
        'order_by'                    => 'date',
        'order'                       => 'DESC',
        'excerpt_len'                 => '160',
        'exclude_without_media'       => true,
        'page'                        => '2',
        'exclude_quote_link'          => true,
        'tile_skin'                   => 'darken',
        'tile_style_pattern'          => 'default',
        'button_style'                => 'pattern-1',
        'display_title'               => true,
        'show_info'                   => true,

        'extra_classes'               => '',
        'tax_args'                    => '',
        'extra_column_classes'        => '',
        'custom_el_id'                => '',
        'template_part_file'          => 'theme-parts/entry/post-tile',
        'extra_template_path'         => '',
        'universal_id'                => '',
        'reset_query'                 => true,
        'carousel_autoplay'           => false,
        'carousel_autoplay_delay'     => '',
        'carousel_navigation'         => 'peritem',
        'carousel_navigation_control' => 'arrows',
        'carousel_loop'               => 1,
        'use_wp_query'                => false, // true to use the global wp_query, false to use internal custom query
        'wp_query_args'               => array(), // additional wp_query args
        'loadmore_type'               => '', // 'next' (more button), 'scroll', 'next-prev'
        'loadmore_per_page'           => '',
        'base_class'                  => 'aux-widget-recent-portfolios aux-carousel'
    );

    $result = auxin_get_widget_scafold( $atts, $default_atts, $shortcode_content );
    extract( $result['parsed_atts'] );

    $acceptedTemplateFiles = apply_filters( 'auxin_recent_portfolios_tiles_accepted_template_files', [
        'theme-parts/entry/post-tile'
    ]);
    
    if ( ! in_array( $template_part_file, $acceptedTemplateFiles ) ) {
        return;
    }

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

    if( ! $use_wp_query ){

        // create wp_query to get latest items -----------
        $args = array(
            'post_type'               => 'portfolio',
            'orderby'                 => $order_by,
            'order'                   => $order,
            'offset'                  => $offset,
            'tax_query'               => $tax_args,
            'post_status'             => 'publish',
            'posts_per_page'          => $num ? $num * $page : -1,
            'ignore_sticky_posts'     => 1,
            'include_posts__in'       => $include, // include posts in this liat
            'posts__not_in'           => $exclude, // exclude posts in this list
            'posts__in'               => $only_posts__in, // only posts in this list

            'exclude_without_media'   => $exclude_without_media,
        );

        // ---------------------------------------------------------------------

        // add the additional query args if available
        if( $wp_query_args ){
            $args = wp_parse_args( $args, $wp_query_args );
        }

        // pass the args through the auxin query parser
        $wp_query = new WP_Query( auxin_parse_query_args( $args ) );
    }

    // widget header ------------------------------
    echo $result['widget_header'];
    echo $result['widget_title'];

    $phone_break_point  = 767;
    $tablet_break_point = 1025;

    $show_comments      = true; // shows comments icon
    $post_counter       = 0;
    $item_class         = 'aux-iso-item aux-image-box';

    if( ! empty( $loadmore_type ) ) {
        $item_class        .= ' aux-ajax-item';
    }

    // check item style and define related variables
    switch ( $item_style ) {
        case 'overlay':
            $frame_effect_classes = 'aux-frame-'. esc_attr( $tile_skin ) .' aux-frame-zoom aux-keep-aspect';
            $hover_classes = 'aux-hover-active';
            $show_lightbox = false;
            $tamplate_file = 'column-overlay';
            break;
        case 'overlay-boxed':
            $frame_effect_classes = 'aux-frame-boxed-'. esc_attr( $tile_skin ) .' aux-keep-aspect';
            $hover_classes = 'aux-hover-active';
            $show_lightbox = false;
            $tamplate_file = 'column-overlay';
            break;
        case 'overlay-lightbox':
            $frame_effect_classes = 'aux-frame-'. esc_attr( $tile_skin ) .' aux-keep-aspect';
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $tamplate_file = 'column-overlay';
            break;
        case 'overlay-lightbox-boxed':
            $frame_effect_classes = 'aux-frame-boxed-'. esc_attr( $tile_skin ) .' aux-keep-aspect';
            $hover_classes = 'aux-hover-active aux-hover-twoway';
            $show_lightbox = true;
            $tamplate_file = 'column-overlay';
            break;
        default:
            $frame_effect_classes = '';
            $hover_classes = '';
            $show_lightbox = false;
            $tamplate_file = 'portfolio-column';
    }

    $container_class    = 'aux-portfolio-columns master-carousel aux-no-js aux-mc-before-init aux-tile-' . esc_attr( $tile_skin ) . ' ';
    // genereate the master carousel attributes
    $carousel_attrs  =  'data-columns="1"';
    $carousel_attrs .=  'data-speed="10"';
    $carousel_attrs .= ' data-autoplay="'. esc_attr( $carousel_autoplay ) .'"';
    $carousel_attrs .= ' data-navigation="' . $carousel_navigation . '"';
    $carousel_attrs .= ' data-loop="' . esc_attr( $carousel_loop ) . '"';
    $carousel_attrs .= ' data-wrap-controls="true"';
    $carousel_attrs .= ' data-bullets="' . ('bullets' == $carousel_navigation_control ? 'true' : 'false') . '"';
    $carousel_attrs .= ' data-bullet-class="aux-bullets aux-small aux-mask"';
    $carousel_attrs .= ' data-arrows="' . ('arrows' == $carousel_navigation_control ? 'true' : 'false') . '"';
    $carousel_attrs .= ' data-same-height="false"';
    $carousel_attrs .= ' data-delay="' . $carousel_autoplay_delay .'"';
    $item_inner_classes = '';
    $have_posts = $wp_query->have_posts();

    if( $have_posts ){


        echo ! $skip_wrappers ? sprintf( '<div data-element-id="%s" class="%s" %s>', esc_attr( $universal_id ), esc_attr( $container_class ), $carousel_attrs ) : '';

        while ( $wp_query->have_posts() ) {
            $item_pattern_info = auxin_get_tile_pattern( $tile_style_pattern, $post_counter, $aux_content_width );

            $post_counter++;

            if ( ( $post_counter  %  $num ) == 1 ){
                echo '<div class="aux-mc-item  aux-tiles-layout">';
            }


            $wp_query->the_post();
            $post = $wp_query->post;


            $post_vars = auxin_get_post_format_media(
                $post,
                array(
                    'request_from'       => 'archive',
                    'media_width'        => $phone_break_point,
                    'media_size'         => $item_pattern_info['size'],
                    'upscale_image'      => true,
                    'image_from_content' => ! $exclude_without_media,
                    'ignore_formats'     => array( '*' ),
                    'preloadable'        => false,
                    'image_sizes'        => 'auto',
                    'srcset_sizes'       => 'auto'
                )
            );
            $item_classes = $item_pattern_info['classname'];

            extract( $post_vars );

            $post_classes = $item_class .' post '. $item_pattern_info['classname'];

            $the_format = get_post_format( $post );?>

            <div class="aux-iso-item <?php echo esc_attr( $item_classes ) ;?>">
            <?php
            include auxin_get_template_file( 'theme-parts/entry/portfolio', $tamplate_file, AUXPFO()->template_path() );?>

            </div><?php

            if ( ( $post_counter   %  $num ) == 0 ){
                $post_counter = 0;
                echo '</div>';
            }

        }
        if ( $page != 1 && 'bullets' != $carousel_navigation_control ) {
        ?>
            <div class="aux-carousel-controls">
                <?php if ( $button_style === 'pattern-1' ) { ?>
                <div class="aux-next-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-right"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-right"></span>
                </div>
                <div class="aux-prev-arrow aux-arrow-nav aux-outline aux-hover-fill">
                    <span class="aux-svg-arrow aux-small-left"></span>
                    <span class="aux-hover-arrow aux-white aux-svg-arrow aux-small-left"></span>
                </div>
                <?php } else { ?>
                <div class="aux-next-arrow aux-arrow-nav aux-hover-slide aux-round aux-outline aux-medium">
                    <span class="aux-overlay"></span>
                    <span class="aux-svg-arrow aux-medium-right"></span>
                    <span class="aux-hover-arrow aux-svg-arrow aux-medium-right aux-white"></span>
                </div>
                <div class="aux-prev-arrow aux-arrow-nav aux-hover-slide aux-round aux-outline aux-medium">
                    <span class="aux-overlay"></span>
                    <span class="aux-svg-arrow aux-medium-left"></span>
                    <span class="aux-hover-arrow aux-svg-arrow aux-medium-left aux-white"></span>
                </div>
                <?php } ?>
            </div>

        <?php
        }

        if( ! $skip_wrappers ) {
            // End tag for aux-ajax-view wrapper & Execute load more functionality
            echo '</div>';
            if( $wp_query->found_posts > $loadmore_per_page ) {
                echo auxin_get_load_more_controller( $loadmore_type );
            }

        } else {
            // Get post counter in the query
            echo '<span class="aux-post-count hidden">'.$wp_query->post_count.'</span>';
            echo '<span class="aux-all-posts-count hidden">'.$wp_query->found_posts.'</span>';
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

    // widget footer ------------------------------
    echo $result['widget_footer'];

    return ob_get_clean();
}
