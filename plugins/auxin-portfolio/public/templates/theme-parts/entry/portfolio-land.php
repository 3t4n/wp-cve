<?php
    global $post, $more, $aux_content_width;
    $more = 0; // to enable read more tag

    // the image width is 63% of wrapper
    $media_width = $aux_content_width * 0.63;
    $image_aspect_ratio = 0.65;

    $post_vars   = auxpfo_get_portfolio_config( $post, array(
        'request_from'       => 'archive',
        'media_width'        => $media_width,
        'media_size'         => 'large',
        'upscale_image'      => true,
        'crop'               => true,
        'add_image_hw'       => true,
        'image_sizes'        => array(
            array( 'min' => '', 'max' => '1024px', 'width' => '100vw' ),
            array( 'min' => '', 'max' => '',       'width' => $media_width.'px' )
        ),
        'srcset_sizes'  => array(
            array( 'width' =>     $media_width, 'height' =>     $media_width * $image_aspect_ratio ),
            array( 'width' => 2 * $media_width, 'height' => 2 * $media_width * $image_aspect_ratio ),
            array( 'width' => 4 * $media_width, 'height' => 4 * $media_width * $image_aspect_ratio )
        )
     ) );
    extract( $post_vars );

    // Add class name for custom styles
    $land_item_class_name = auxin_get_option( 'show_portfolio_land_side_entry_box_colors' ) ? ' aux-item-land' : '';

?>
                        <article <?php post_class( 'aux-single-portfolio-wrapper aux-portfolio-land' . $land_item_class_name ); ?> >
                            <?php if ( $has_attach ) { ?>
                            <div class="entry-media">
                                <?php echo $the_media; ?>
                            </div>
                            <?php
                               }else { ?>
                                    <div class="entry-media">
                                        <div class="aux-media-frame aux-media-image">
                                            <a href="<?php echo !empty( $the_link ) ? $the_link : get_permalink(); ?>">
                                                <img src="<?php  echo AUXIN_URL . 'images/welcome/image-frame.svg'; ?>" class="auxin-attachment auxin-featured-image attachment-1024x1024" alt="portfolio default image" >
                                            </a>
                                        </div>
                                    </div>
                            <?php   }
                            ?>
                            <div class="aux-land-side">
                                <div class="entry-main">

                                    <header class="entry-header">
                                        <h3 class="entry-title">
                                            <a href="<?php echo !empty( $the_link ) ? $the_link : get_permalink(); ?>">
                                                <?php echo !empty( $the_name ) ? $the_name : get_the_title(); ?>
                                            </a>
                                        </h3>
                                    </header>

                                     <div class="entry-content">
                                        <?php

                                            // $content_listing_type   = is_category() || is_tag() ? auxin_get_option( 'post_taxonomy_archive_content_on_listing' ) : auxin_get_option( 'blog_content_on_listing' );
                                            // $content_listing_length = is_category() || is_tag() ? auxin_get_option( 'post_taxonomy_archive_on_listing_length', 255 ) : auxin_get_option( 'excerpt_len', 255 );

                                            // get overview context
                                            $_overview = auxin_get_post_meta( $post, '_overview' );

                                            if( has_excerpt() ) {
                                                the_excerpt();
                                            } elseif( ! empty( $_overview )  ){
                                                auxin_the_trimmed_string( auxin_strip_shortcodes( auxin_extract_text( $_overview ) ), 170 );
                                            }

                                        ?>
                                    </div>
                                </div>

                                <footer class="entry-meta">
                                    <div class="portfolio-tax">
                                        <span class="entry-tax">
                                        <?php // the_category(' '); we can use this template tag, but customizable way is needed! ?>
                                        <?php $tax_name = 'portfolio-cat';
                                              $cat_terms = wp_get_post_terms( $post->ID, $tax_name );
                                              if( $cat_terms = wp_get_post_terms( $post->ID, $tax_name ) ){
                                                  foreach( $cat_terms as $term ){
                                                      echo '<a href="'. get_term_link( $term->slug, $tax_name ) .'" title="'.__("View all posts in ", 'auxin-portfolio'). $term->name .'" rel="category" >'. $term->name .'</a>';
                                                  }
                                              }
                                        ?>
                                        </span>
                                        <?php if( ! empty($cat_terms) ){
                                            edit_post_link(__("Edit", 'auxin-portfolio'), '<i> | </i>', '');
                                            } else {
                                                edit_post_link(__("Edit", 'auxin-portfolio'), '', '');
                                            }
                                        ?>
                                    </div>
                                    <?php
                                    if( function_exists('wp_ulike') && auxin_get_option( 'show_portfolio_archive_like_button' ) ) { ?>
                                        <div class="comments-iconic">
                                            <?php
                                                wp_ulike( 'get', array( 'style' => 'wpulike-heart', 'button_type' => 'image', 'wrapper_class' => 'aux-wpulike aux-wpulike-portfolio-widget' ) );
                                            ?>
                                        </div>
                                    <?php  } ?>

                                </footer>

                            </div>

                        </article>
