<?php

class EIC_Shortcode {

    public function __construct()
    {
        add_shortcode( 'easy-image-collage', array( $this, 'eic_shortcode' ) );
    }

    function eic_shortcode( $options )
    {
        $options = shortcode_atts( array(
            'id' => '0', // If no ID given, show a random recipe
        ), $options );

        $post = get_post( intval( $options['id'] ) );

        $output = '';

        if( !is_null( $post ) && $post->post_type == EIC_POST_TYPE ) {
	        $grid = new EIC_Grid( $post );

            if ( function_exists( 'is_amp_endpoint' ) && is_amp_endpoint() ) {
                foreach( $grid->images() as $id => $image ) {
                    if ( $image ) {
                        $thumb = wp_get_attachment_image( $image['attachment_id'], 'large' );

                        if ( $thumb ) {
                            $output .= $thumb;

                            if( EasyImageCollage::is_addon_active( 'captions' ) ) {
                                if( isset( $image['custom_caption'] ) && $image['custom_caption'] ) {
                                    $output .= '<div style="text-align: center; margin-bottom: 10px; font-size: 0.8em;">' . $image['custom_caption'] . '</div>';
                                }
                            }
                        }
                    }
                }
            } else {
                // Styling
                $output .= '<style>';
                $output .= '.eic-frame-' . $grid->ID() . ' { width: ' . $grid->width() . 'px; height:' . $grid->height() . 'px; background-color: ' . $grid->border_color() . '; border: ' . $grid->border_width() . 'px solid ' . $grid->border_color() . '; }';
                $output .= '.eic-frame-' . $grid->ID() . ' .eic-image { border: ' . $grid->border_width() . 'px solid ' . $grid->border_color() . '; }';

                if( EasyImageCollage::option( 'default_style_display', 'image' ) == 'background' ) {
                    foreach( $grid->images() as $id => $image ) {
                        if( $image ) {
                            $url = $image['attachment_url'];

                            $width = intval( $image['size_x'] );
                            $height = intval( $image['size_y'] );
                            $ratio = $width / $height;

                            $thumb = wp_get_attachment_image_src( $image['attachment_id'], array( $width, $height ) );

                            if( $thumb ) {
                                $full_file_name = get_attached_file( $image['attachment_id'] );
                                $path = str_ireplace( wp_basename( $full_file_name ), '', $full_file_name );
                            
                                $thumb_url = $thumb[0];
                                $thumb_file = $path . wp_basename( $thumb_url );

                                // Try path first for performance reasons, fall back on URL.
                                @list( $thumb_width, $thumb_height ) = getimagesize( $thumb_file );

                                if ( !$thumb_width || !$thumb_height ) {
                                    @list( $thumb_width, $thumb_height ) = getimagesize( $thumb_url );
                                }

                                if( $thumb_width && $thumb_height ) {
                                    $thumb_ratio = $thumb_width / $thumb_height;

                                    if( abs( $thumb_ratio - $ratio ) < 0.05 ) {
                                        $url = $thumb_url; // Only use the thumbnail if the ratios match
                                    }
                                }
                            }

                            $output .= '.eic-frame-' . $grid->ID() . ' .eic-image-' . $id . ' {';
                            $output .= 'background-image: url("' . $url . '");';
                            $output .= 'background-size: ' . $width . 'px ' . $height . 'px;';
                            $output .= 'background-position: ' . $image['pos_x'] . 'px ' . $image['pos_y'] . 'px;';
                            $output .= '}';
                        }
                    }
                }

                $output .= '</style>';

                $container_class = '';
                $container_style = '';

                switch( $grid->align() ) {
                    case 'float-left':
                        $container_class = ' eic-float-left';
                        break;
                    case 'float-right':
                        $container_class = ' eic-float-right';
                        break;
                    case 'left':
                        $container_style = ' style="text-align: left;"';
                        break;
                    case 'right':
                        $container_style = ' style="text-align: right;"';
                        break;
                }

                // Draw frame
                $output .= '<div class="eic-container' . $container_class . '"' . $container_style . '>';
                $output .= $grid->draw();
                $output .= '</div>';
            }
        }

        return $output;
    }
}