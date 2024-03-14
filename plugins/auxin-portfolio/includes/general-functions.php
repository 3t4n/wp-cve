<?php
/**
 *
 * 
 * @package    Auxin
 * @license    LICENSE.txt
 * @author     averta
 * @link       http://averta.net/phlox/
 * @copyright  (c) 2010-2023 averta
 */


/**
 * Get template part.
 *
 * @param mixed $slug
 * @param string $name (default: '')
 */
function auxpfo_get_template_part( $slug, $name = '' ) {
    auxin_get_template_part( $slug, $name, AUXPFO()->template_path() );
}


/**
 * Whether a plugin is active or not
 *
 * @param  string $plugin_basename  plugin directory name and mail file address
 * @return bool                     True if plugin is active and FALSE otherwise
 */
if( ! function_exists( 'auxin_is_plugin_active' ) ){
    function auxin_is_plugin_active( $plugin_basename ){
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        return is_plugin_active( $plugin_basename );
    }
}




if ( ! function_exists('auxpfo_get_portfolio_config') ) {

    function auxpfo_get_portfolio_config( $post, $settings ) {
        global $aux_content_width;

        $defaults = array(
            'request_from'    => 'archive',
            'content_width'   => '',
            'preloadable'     => false,
            'preload_preview' => false,
            'preload_bgcolor' => '',
            'media_size'      => '', // large, medium, thumbnail
            'aspect_ratio'    => 1,
            'add_image_hw'    => true, // whether to add with and height attrs to image
            'image_sizes'     => array(),
            'srcset_sizes'    => array(),
            'upscale_image'   => false,
            'crop'            => true
        );

        $settings = wp_parse_args( $settings, $defaults );
        extract( $settings );

        if ( empty( $media_width ) ) {
            $media_width = $aux_content_width;
        }

        $args = array(
            'show_share_btn'    => true,
            'show_like_btn'     => true,
            'show_actions'      => true,
            'show_side'         => true,
            'show_title'        => true,
            'the_media'         => '',
            'the_attach'        => '',
            'has_attach'        => false,
            'has_side_meta'     => true,
            'post_class'        => '',
            'media_parent_class'=> '',
            'media_class'       => '',
            'sticky_sidebar'    => false,
            'display_cat'       => true
        );

        if( empty( $post ) ){
            return $args;
        }


        // get the post media layout
        if( 'default' == $media_layout = auxin_get_post_meta( $post, '_media_layout', 'default' ) ){
            $media_layout = auxin_get_option( 'portfolio_single_media_layout' );
        }

        // get side position
        if( 'default' == $side_pos = auxin_get_post_meta( $post, '_side_info_pos', 'default' ) ){
            // $side_pos = is_rtl() ? 'left' : 'right';
            $side_pos = auxin_get_option( 'portfolio_single_side_pos', 'right' );
        }

        // whether to show ro hide the portfolio metadata info
        if( 'default' == $has_side_meta = auxin_get_post_meta( $post, '_show_side_info_meta', 'default' ) ){
            $has_side_meta = auxin_get_option( 'portfolio_single_display_side_info_meta', '1' );
        }
        $args['has_side_meta'] = auxin_is_true( $has_side_meta );

        $string_dash_pos = strpos( $side_pos, '-' );

        if( $args['has_side_meta'] ){
            // possible values 'reverse' (flip the side info), 'down' (metadata under overview) and 'normal' (default direction)
            $side_meta_status = false !== $string_dash_pos ? substr( $side_pos, $string_dash_pos + 1 ) : 'normal';
        } else {
            $side_meta_status = 'hide';
        }

        // remove extra suffix
        $side_pos = $string_dash_pos ? substr( $side_pos, 0, $string_dash_pos ) : $side_pos;

        // get display_cat
        if( 'default' == $display_cat = auxin_get_post_meta( $post, '_side_info_dicplay_cat', 'default' ) ){
            $display_cat = auxin_get_option( 'portfolio_single_display_category', true );
        }
        $args['display_cat'] = auxin_is_true( $display_cat );

         // get display_tag
        if( 'default' == $display_tag = auxin_get_post_meta( $post, '_side_info_dicplay_tag', 'default' ) ){
            $display_tag = auxin_get_option( 'portfolio_single_display_tag', true );
        }
        $args['display_tag'] = auxin_is_true( $display_tag );

        if( 'default' == $_alignment = auxin_get_post_meta( $post, '_overview_info_alignment', 'default' ) ){
            $_alignment = auxin_get_option( 'portfolios_overview_info_alignment', true );
        }
        $args['post_class'] .= 'center' == $_alignment ? ' aux-text-align-' . $_alignment : '';

        if ( 'bottom' != $side_pos ) {
            if( 'default' == $sticky_sidebar = auxin_get_post_meta( $post, '_sticky_sidebar', 'default' ) ){
                $sticky_sidebar = auxin_get_option( 'portfolio_single_sticky_sidebar', false );
            }
            // sticky sidebar
            $args['sticky_sidebar'] = auxin_is_true( $sticky_sidebar );
            $args['post_class']    .= $args['sticky_sidebar'] ? ' aux-sticky-side' : '';
        }

        // specify the side position
        $args['side_pos']     = $side_pos;

        // side position and metadata direction
        $args['post_class']  .= ' aux-side-' . $side_pos;
        $args['post_class']  .= ' aux-side-meta-' . $side_meta_status;

        switch ( $media_layout ) {
            case 'classic':
                $args['media_parent_class'] = 'aux-stack';
                $args['media_class']        = 'aux-media-frame aux-media-image';
                break;

            case 'grid':
                $args['post_class']        .= ' portfolio-grid';
                $args['media_parent_class'] = 'aux-portolio-grid gallery-columns-2';
                $args['media_class']        = 'aux-portolio-grid-column';
                break;

            case 'masonry':
            case 'land':
            case 'tile':
            default:

                break;
        }

        if( ! empty( $media_size ) ){
            if( is_array( $media_size ) ){
                $media_size['width']  = ! empty( $media_size['width' ] ) ? $media_size['width' ] : '';
                $media_size['height'] = ! empty( $media_size['height'] ) ? $media_size['height'] : '';

                $size = array( 'width' => $media_size['width'], 'height' => $media_size['height'] );
            } else {
                if( $size = auxin_wp_get_image_size( $media_size ) ){
                    $size = array( 'width' => $size['width'], 'height' => $size['height'] );
                }
            }
        } else {
            $size = array( 'width' => $media_width, 'height' => $media_width * $aspect_ratio );
        }


        if ( 'archive' == $request_from ) {
            $args['has_attach'] = has_post_thumbnail( $post->ID );

            if ( $args['has_attach'] ) {
                $args['the_attach'] = auxin_get_the_post_responsive_thumbnail(
                    $post->ID,
                    array(
                        'size'            => $size,
                        'crop'            => $crop,
                        'preloadable'     => $preloadable,
                        'preload_preview' => $preload_preview,
                        'preload_bgcolor' => $preload_bgcolor,
                        'add_hw'          => $add_image_hw,
                        'image_sizes'     => $image_sizes,
                        'srcset_sizes'    => $srcset_sizes,
                        'upscale'         => $upscale_image
                    )
                );
            }

            $args['the_media'] = '<div class="aux-media-frame aux-media-image">'.
                            '<a href="'.get_permalink( $post->ID ).'">'.
                                $args['the_attach'].
                            '</a>'.
                         '</div>';
        } else {
            $args['has_attach'] = ! auxin_get_post_meta( $post, '_no_feature_image_in_single', 0 );

            if( $args['has_attach'] ){
                if ( function_exists( 'auxin_maybe_create_image_size' ) ) {
                    auxin_maybe_create_image_size( get_post_thumbnail_id( $post->ID ), $media_size );
                }
                $args['the_media']  = get_the_post_thumbnail( $post->ID, $media_size );
                $args['has_attach'] = ! empty( $args['the_media'] );
            }
        }

        // Don't display post title if title bar is enable to prevent duplicated title in single page
        if( 'archive' !== $request_from && auxin_get_post_meta( $post, 'aux_title_bar_show', 0 ) ) {
           $args['show_title'] = false;
        }

        // action buttons
        $args['show_share_btn'] = ( auxin_get_option( 'show_portfolio_single_share', true ) );
        $args['show_like_btn' ] = ( auxin_get_option( 'show_portfolio_single_like', true ) );
        $args['show_actions'  ] = ( auxin_get_option( 'show_portfolio_single_share_like_section', true ) );
        if( !$args['show_share_btn']  && !$args['show_like_btn'] ) {
            $args['show_actions'  ] = false;
        }

        return $args;
    }

}
