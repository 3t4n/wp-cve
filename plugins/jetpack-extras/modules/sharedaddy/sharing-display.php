<?php

function sharing_display_extra( $text = '' ) {
    global $post, $wp_current_filter;

    if ( is_preview() ) {
        return $text;
    }

    if ( in_array( 'get_the_excerpt', (array) $wp_current_filter ) ) {
        return $text;
    }

    if ( is_attachment() && in_array( 'the_excerpt', (array) $wp_current_filter ) ) {
        // Many themes run the_excerpt() conditionally on an attachment page, then run the_content().
        // We only want to output the sharing buttons once.  Let's stick with the_content().
        return $text;
    }

    $sharer = new Sharing_Service();
    $global = $sharer->get_global_options();

    $show = false;
    if ( !is_feed() ) {
        if ( is_singular() && in_array( get_post_type(), $global['show'] ) ) {
            $show = true;
        } elseif ( in_array( 'index', $global['show'] ) && ( is_home() || is_archive() || is_search() ) ) {
            $show = true;
        }
    }

    // Pass through a filter for final say so
    $show = apply_filters( 'sharing_show', $show, $post );

    // Disabled for this post?
    $switched_status = get_post_meta( $post->ID, 'sharing_disabled', false );

    if ( !empty( $switched_status ) )
        $show = false;

    // Allow to be used on P2 ajax requests for latest posts.
    if ( defined( 'DOING_AJAX' ) && DOING_AJAX && isset( $_REQUEST['action'] ) && 'get_latest_posts' == $_REQUEST['action'] )
        $show = true;

    $sharing_content = '';

    if ( $show ) {
        $enabled = $sharer->get_blog_services();

        if ( count( $enabled['all'] ) > 0 ) {
            global $post;

            $dir = get_option( 'text_direction' );

            // Wrapper
            $sharing_content .= '<div class="sharedaddy sd-sharing-enabled"><div class="robots-nocontent sd-block sd-social sd-social-' . $global['button_style'] . ' sd-sharing">';
            if ( $global['sharing_label'] != '' )
                $sharing_content .= '<h3 class="sd-title">' . $global['sharing_label'] . '</h3>';
            $sharing_content .= '<div class="sd-content"><ul>';

            // Visible items
            $visible = '';
            foreach ( $enabled['visible'] as $id => $service ) {
                // Individual HTML for sharing service
                $visible .= '<li class="share-' . $service->get_class() . '">' . $service->get_display( $post ) . '</li>';
            }

            $parts = array();
            $parts[] = $visible;
            if ( count( $enabled['hidden'] ) > 0 ) {
                if ( count( $enabled['visible'] ) > 0 )
                    $expand = __( 'More', 'jetpack' );
                else
                    $expand = __( 'Share', 'jetpack' );
                $parts[] = '<li><a href="#" class="sharing-anchor sd-button share-more"><span>'.$expand.'</span></a></li>';
            }

            if ( $dir == 'rtl' )
                $parts = array_reverse( $parts );

            $sharing_content .= implode( '', $parts );
            $sharing_content .= '<li class="share-end"></li></ul>';

            if ( count( $enabled['hidden'] ) > 0 ) {
                $sharing_content .= '<div class="sharing-hidden"><div class="inner" style="display: none;';

                if ( count( $enabled['hidden'] ) == 1 )
                    $sharing_content .= 'width:150px;';

                $sharing_content .= '">';

                if ( count( $enabled['hidden'] ) == 1 )
                    $sharing_content .= '<ul style="background-image:none;">';
                else
                    $sharing_content .= '<ul>';

                $count = 1;
                foreach ( $enabled['hidden'] as $id => $service ) {
                    // Individual HTML for sharing service
                    $sharing_content .= '<li class="share-'.$service->get_class().'">';
                    $sharing_content .= $service->get_display( $post );
                    $sharing_content .= '</li>';

                    if ( ( $count % 2 ) == 0 )
                        $sharing_content .= '<li class="share-end"></li>';

                    $count ++;
                }

                // End of wrapper
                $sharing_content .= '<li class="share-end"></li></ul></div></div>';
            }

            $sharing_content .= '<div class="sharing-clear"></div></div></div></div>';

            // Register our JS
            wp_register_script( 'sharing-js', WP_SHARING_PLUGIN_URL.'sharing.js', array( 'jquery' ), '20120131' );
            add_action( 'wp_footer', 'sharing_add_footer' );
        }
    }

    $options = get_option( 'jetpack_extras-options', array() );
    $option = '';
    if ( is_singular() || is_single() ) {
        $option = isset($options['placement'][get_post_type()]) ? $options['placement'][get_post_type()] : 'below';
    } else {
        $option = $options['placement']['index'];
    }

    switch($option) {
        case 'above':
            return $sharing_content.$text;
        case 'both':
            return $sharing_content.$text.$sharing_content;
        case 'below':
        default:
            return $text.$sharing_content;
    }
}
