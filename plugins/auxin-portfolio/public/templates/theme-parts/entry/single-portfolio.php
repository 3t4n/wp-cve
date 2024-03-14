<?php
    global $post;

    $post_vars = auxpfo_get_portfolio_config( $post, array( 
        'request_from' => 'single' ,
        'media_size'   => auxin_get_option( 'portfolio_single_image_size', '' ),
    ) );
    extract( $post_vars );
?>

<article id="post-<?php the_ID(); ?>" <?php post_class( $post_class ); ?> role="article" >
        <?php
        $entry_main = '<div class="entry-main">';
        if( $has_attach ) {
            $entry_main .= '<div class="entry-media ' . $media_parent_class  . '">' . $the_media . '</div>';
        }
        if ( $the_content = auxin_get_the_content() ) {
            $entry_main .= '<div class="entry-content clearfix">' . $the_content . '</div>';
        }
        $entry_main .= '</div>';

        // print media on top if side position is right, left, bottom
        if( 'top' !== $side_pos ){
            echo $entry_main;
        }

        if( $show_side ) {

            $info_layout_bg         = auxin_get_post_meta( $post, '_side_info_color' );
            $info_layout_font_color = auxin_get_post_meta( $post, '_side_info_font_color' );
            $info_pos               = auxin_get_post_meta( $post, '_side_info_pos');

            if ( ! empty( $info_layout_bg ) ){
                $layout_bg        = ' background-color: ' . $info_layout_bg . ';';
                $layout_mg        = ' aux-layout-margin' ;
                $layout_bg_markup = '<div class="layout-bg" style=" ' . esc_attr ( $layout_bg ) . ' "></div>';
            }
            if ( ! empty( $info_layout_font_color ) && $info_pos != 'default' ) {
                $info_layout_font_color = 'light' == $info_layout_font_color ? ' aux-text-color-light' : 'aux-text-color-dark' ;   
            }
            $header_class           = isset ( $layout_mg ) ? esc_attr ( $info_layout_font_color )  . esc_attr ( $layout_mg ) : esc_attr ( $info_layout_font_color );

            $header_styles          = isset ( $layout_bg ) ? 'style="'. esc_attr( $layout_bg ) . '"' : '' ;
            $header_styles          = in_array( $info_pos, array('right', 'left') ) ? '' : $header_styles ;

            // Meta data options -----------------------------
            if( $display_cat ) {
                // get portfolio categories
                $tax_name = 'portfolio-cat';
                $cat_terms = wp_get_post_terms( $post->ID, $tax_name );
                if( empty( $cat_terms ) ) {
                    $display_cat = false;
                }
            }

            if( $display_tag ) {
                // get portfolio tags
                $tax_name_tag = 'portfolio-tag';
                $tag_terms = wp_get_post_terms( $post->ID, $tax_name_tag );
                if( empty( $tag_terms ) ) {
                    $display_tag = false;
                }
            }

            $lunch_btn_url = auxin_get_post_meta( $post, '_lunch_button_url', '' );
            if( !empty( $lunch_btn_url ) ) {
                $display_lunch = true;
            } else { $display_lunch = false; }

            // print the portfolio metadata
            $metafields = json_decode( auxin_get_option( 'portfolio_metadata_list_1' ), true );

            if ( is_array( $metafields ) && ! empty( $metafields ) ) {
                    $display_metafields = true;
            } else { $display_metafields = false; }

            $is_side_meta_set = $has_side_meta && ( $display_metafields || $display_cat || $display_tag || $display_lunch );

            // End meta data options -----------------------------

            $header_class .= $is_side_meta_set ? ' aux-has-meta-data' : '';

            if( $sticky_sidebar ) {
                // 45 is the space between site header and the side area
                $sticky_header_height = 45;
                if ( auxin_get_option('site_header_top_sticky') ) {
                    $header_height = auxin_get_option('site_header_container_scaled_height');
                    $sticky_header_height += empty( $header_height ) ? 0 : $header_height ;
                }
        ?>
        <div class="entry-side aux-sticky-position <?php echo esc_attr( $header_class );?>" <?php echo $header_styles ;?>  data-boundry-target=".content .portfolio.hentry" data-sticky-move-method="after" data-boundaries="true" data-use-transform="true" data-sticky-margin="<?php echo $sticky_header_height; ?>">
        <?php } else { ?>
        <div class="entry-side <?php echo esc_attr( $header_class );?>" <?php echo $header_styles ;?>  >
        <?php }
        if( $_overview_title = auxin_get_post_meta( $post, '_overview_title', '' ) ){
            echo '<div class="entry-side-title"><h1>'. do_shortcode( $_overview_title ). '</h1></div>';
        }
        ?>
        <?php
            $_overview       = auxin_get_post_meta( $post, '_overview', '' );
            if( $_overview || $show_actions ) {
                echo '<div class="entry-overview-container">';
                if( $_overview ){
                    echo '<div class="entry-side-overview">';
                    do_action('auxin_single_portfolio_overview', $_overview, $show_like_btn, $show_share_btn );
                    echo '</div>';
                }
                if( $show_actions ) {
                    echo '<div class="entry-actions">';
                    do_action('auxin_single_portfolio_actions', $show_like_btn, $show_share_btn );
                    echo '</div>';
                }
                echo '</div>';
            }
        ?>
        <?php
            if( $is_side_meta_set ){ // start of displaying condition
                echo '<div class="entry-meta-data-container">';
                echo '<div class="entry-meta-data"><dl>';

                if( $display_cat ){
                    printf( '<dt>%s</dt><dd><span class="entry-tax">', __( 'Categories', 'auxin-portfolio' ) );
                    foreach( $cat_terms as $term ){
                         echo '<a href="'. get_term_link( $term->slug, $tax_name ) .'" title="'.esc_attr__("View all posts in ", 'auxin-portfolio'). $term->name .'" rel="category" >'. $term->name .'</a>';
                    }
                    echo '</span></dd>';
                }

                if( $display_tag ) {
                    printf( '<dt>%s</dt><dd><span class="entry-tax">', __( 'Tags', 'auxin-portfolio' ) );
                    foreach( $tag_terms as $term ){
                         echo '<a href="'. get_term_link( $term->slug, $tax_name_tag ) .'" title="'. esc_attr__("View all posts in ", 'auxin-portfolio'). $term->name .'" rel="category" >'. $term->name .'</a>';
                    }
                    echo '</span></dd>';
                }

                foreach ( $metafields as $metadata_info ) {
                    if( ! empty( $metadata_info['id'] ) && $meta_value = auxin_get_post_meta( $post, '_auxin_meta_' . $metadata_info['id'] ) ){
                        echo "<dt>{$metadata_info['value']}</dt>";
                        echo "<dd>{$meta_value}</dd>";
                    }
                }

                echo '</dl>';


                if( $display_lunch ) { ?>
                    <a href="<?php echo $lunch_btn_url; ?>" class="aux-button aux-cta-button aux-black aux-medium aux-curve">
                        <span class="aux-overlay"></span>
                        <span class="aux-text"><?php echo auxin_get_option( 'portfolio_metadata_launch_label' ); ?></span>
                    </a><?php
                }

                echo '</div></div>';
            }

            echo "</div>";
        }

        // print media on bottom if side position is top
        if( 'top' == $side_pos ){
            echo $entry_main;
        }

        // clear the floated elements at the end of content
        echo '<div class="clear"></div>';

        // create pagination for page content
        wp_link_pages( array( 'before' => '<div class="page-links"><span>' . __( 'Pages:', 'auxin-portfolio') .'</span>', 'after' => '</div>' ) );

        // get next/prev portfolio buttons
        if( 'default' == $display_next_pre = auxin_get_post_meta( $post, '_show_next_prev_nav', 'default' ) ){
            $display_next_pre = auxin_get_option( 'show_portfolio_single_next_prev_nav', false );
        }

        $next_prev_navigation = '';

        if( auxin_is_true( $display_next_pre ) ) {
            if( 'default' == $next_prev_skin = auxin_get_post_meta( $post, '_next_prev_nav_skin', 'default' ) ){
                $next_prev_skin = auxin_get_option( 'portfolio_single_next_prev_nav_skin', false );
            }

            $next_label = auxin_get_option( 'portfolio_single_next_nav_label', '' );
            $prev_label = auxin_get_option( 'portfolio_single_prev_nav_label', '' );

            $args = array(
                'prev_text'      => __( esc_attr( $prev_label ), 'auxin-portfolio' ),
                'next_text'      => __( esc_attr( $next_label ), 'auxin-portfolio' ),
                'taxonomy'       => 'portfolio-cat',
                'skin'           => $next_prev_skin, // minimal, thumb-no-arrow, thumb-arrow, boxed-image
                'echo'           => false
            );

            if ( 'classic' == $next_prev_skin ) {
                $args['prev_text'] = __( 'Prev', 'auxin-portfolio' ) ;
                $args['next_text'] = __( 'Next ', 'auxin-portfolio' ) ;
            }

            $next_prev_navigation = auxin_single_page_navigation( $args );
        }

        if( $sticky_sidebar || $info_layout_bg ) {
            echo $next_prev_navigation;
        } else {
            global $post_next_prev_navigation;
            $post_next_prev_navigation = $next_prev_navigation;
        }
    ?>

</article> <!-- end article -->

<?php
echo isset( $layout_bg_markup ) ? $layout_bg_markup : '';
