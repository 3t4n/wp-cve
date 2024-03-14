<?php

class CRP_Output {

    public function __construct()
    {
    }

    public function output_list( $post_id, $args, $widget = false )
    {
        $post_types = CustomRelatedPosts::setting( 'general_post_types' );
        if( !in_array( get_post_type( $post_id ), $post_types ) ) return '';

        $args = shortcode_atts(
            array(
                'title' => __( 'Related Posts', 'custom-related-posts' ),
                'order_by' => 'title',
                'order' => 'ASC',
                'none_text' => __( 'None found', 'custom-related-posts' ),
            ), $args
        );

        $relations = CustomRelatedPosts::get()->relations_to( $post_id );

        // Sort relations
        if( $args['order_by'] == 'title' ) {
            usort( $relations, array( $this, 'sortByTitle' ) );
        } elseif( $args['order_by'] == 'date' ) {
            usort( $relations, array( $this, 'sortByDate' ) );
        } elseif( $args['order_by'] == 'custom' ) {
            usort( $relations, array( $this, 'sortByOrder' ) );
        } elseif( $args['order_by'] == 'rand' ) {
            shuffle( $relations );
        }

        // echo '<pre>' . var_export( $relations, true ) . '</pre>';

        if( $args['order'] == 'DESC') {
            $relations = array_reverse( $relations, true );
        }

        // Start output
        $output = '';
        if( $widget ) {
            $output .= $widget['before_widget'];

            $title = apply_filters( 'widget_title', $args['title'] );
            if( !empty( $title ) ) {
                $output .= $widget['before_title'] . $title . $widget['after_title'];
            }
        } else {
            if( $args['title'] ) {
                $output .= apply_filters( 'crp_output_list_title', '<h3 class="crp-list-title">' . $args['title'] . '</h3>', $post_id );
            }
        }

        // Check if we can output any relations
        $relations_output = $this->output_relations( $relations, $post_id );

        if ( $relations_output ) {
            $output .= $relations_output;
        } else {
            if ( $args['none_text'] ) {
                $output .= '<p>' . $args['none_text'] . '</p>';
            } else {
                // Don't output widget if no relations and no text to show
                return '';
            }
        }

        if( $widget ) $output .= $widget['after_widget'];

        return apply_filters( 'crp_output_list', $output, $post_id );
    }

    public function sortByTitle( $a, $b )
    {
        return strcmp( $a['title'], $b['title'] );
    }

    public function sortByDate( $a, $b )
    {
        return strtotime( $a['date'] ) - strtotime( $b['date'] );
    }

    public function sortByOrder( $a, $b )
    {
        return $a['order']- $b['order'];
    }

    public function output_relations( $relations, $post_id = false ) {
        $output = '';

        foreach( $relations as $relation ) {
            if( $relation['status'] == 'publish' ) {
                $output .= apply_filters( 'crp_output_list_item', $this->output_relation( $relation ), $post_id, $relation );
            }
        }

        if ( $output ) {
            $tag = CustomRelatedPosts::setting( 'template_container' );
            $output = '<' . $tag . ' class="crp-list">' . $output . '</' . $tag . '>';
        }

        return $output;
    }

    public function output_relation( $relation ) {
        $classes = array(
            'crp-list-item',
        );

        $link_target = CustomRelatedPosts::setting( 'output_open_in_new_tab') ? ' target="_blank"' : '';
        $tag = 'div' === CustomRelatedPosts::setting( 'template_container' ) ? 'div' : 'li';

        // Image.
        $image = '';
        $image_style = CustomRelatedPosts::setting( 'template_image' );
        
        $classes[] = 'crp-list-item-image-' . $image_style;

        if ( 'none' !== $image_style ) {
            $size = CustomRelatedPosts::setting( 'template_image_size' );

            preg_match( '/^(\d+)x(\d+)$/i', $size, $match );
            if ( ! empty( $match ) ) {
                $size = array( intval( $match[1] ), intval( $match[2] ) );
            }

            $image = get_the_post_thumbnail( $relation['id'], $size );

            if ( $image ) {
                // Prevent image stretching in Gutenberg.
                $thumb = wp_get_attachment_image_src( get_post_thumbnail_id( $relation['id'] ), $size );
                if ( $thumb[1] ) {
                    $style = 'max-width: ' . $thumb[1] . 'px; height: auto;';
    
                    if ( false !== stripos( $image, ' style="' ) ) {
                        $image = str_ireplace( ' style="', ' style="' . $style, $image );
                    } else {
                        $image = str_ireplace( '<img ', '<img style="' . $style . '" ', $image );
                    }
                }

                // Disable image pinning.
                if ( CustomRelatedPosts::setting( 'template_image_nopin' ) ) {
                    $image = str_ireplace( '<img ', '<img data-pin-nopin="true" ', $image );
                }

                $classes[] = 'crp-list-item-has-image';
                $image = '<div class="crp-list-item-image"><a href="' . $relation['permalink'] . '"' . $link_target . '>' . $image . '</a></div>';
            }
        }

        $output = '<' . $tag . ' class="' . implode( ' ', $classes ) . '">';

        if ( in_array( CustomRelatedPosts::setting( 'template_image' ), array( 'above', 'left' ) ) ) {
            $output .= $image;
        }

        $output .= '<div class="crp-list-item-title"><a href="' . $relation['permalink'] . '"' . $link_target . '>';
        $output .= $relation['title'];
        $output .= '</a></div>';

        if ( in_array( CustomRelatedPosts::setting( 'template_image' ), array( 'below', 'right' ) ) ) {
            $output .= $image;
        }

        $output .= '</' . $tag . '>';
        
        return $output;
    }
}