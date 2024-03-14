<?php
/**
 * Category Collection block layout one - php render.
 */
    if ( $postMargin ) {
        $postClass = 'cvmm-post--imagemargin column--'.esc_attr( $blockColumn );
    } else {
        $postClass = 'cvmm-post-no--imagemargin column--'.esc_attr( $blockColumn );
    }
?>
    <div class="cvmm-cats-wrapper <?php echo esc_attr( $postClass ); ?>">
        <?php
        if ( empty( $blockCategories ) ) {
            esc_html_e( 'No category selected', 'wp-magazine-modules-lite' );
            return;
        }
        $loop_count = 0;
            foreach ( $blockCategories as $catid ) :
                $cat_query = get_category( $catid );
                $cat_count = $cat_query->count;
                $cat_link = get_category_link( $catid );
                $cat_post_args = array(
                    'post_type'         => 'post',
                    'posts_per_page'    => absint( 1 ),
                    'cat'               => absint( $catid ),
                    'post_status'       => 'publish'
                );
                $cat_post_query = new WP_Query( $cat_post_args );

                    while ( $cat_post_query->have_posts() ) : $cat_post_query->the_post();
                        $post_id = get_the_ID();
                ?>
                        <div id="cat-<?php echo esc_attr( $catid ); ?>" class="cvmm-category">
                            <?php
                                if ( has_post_thumbnail() ) {
                                    $image_url = get_the_post_thumbnail_url( $post_id, $imageSize );
                                } elseif( isset( $fallbackImage ) ) {
                                    $image_url = $fallbackImage;
                                } else {
                                    $image_url = WPMAGAZINE_MODULES_LITE_DEFAULT_IMAGE;
                                }
                            ?>
                            <div class="cvmm-cat-thumb">
                                <a href="<?php echo esc_url( $cat_link ); ?>" target="<?php echo esc_attr( $permalinkTarget ); ?>"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title_attribute(); ?>"/></a>
                            </div>

                            <div class="cvmm-cat-content-all-wrapper">
                                <?php
                                    if ( $titleOption === true ) {
                                        $cat_title  = $cat_query->name;
                                        $cat_id     = $cat_query->term_id;
                                ?>
                                        <h2 class="cvmm-cat-title cvmm-cat-<?php echo absint( $cat_id ); ?>">
                                            <a href="<?php echo esc_url( $cat_link ); ?>" target="<?php echo esc_attr( $permalinkTarget ); ?>">
                                                <?php echo esc_html( $cat_title ); ?>
                                            </a>
                                        </h2>
                                <?php
                                    }

                                    if ( $catcountOption === true ) {
                                ?>
                                        <span class="cvmm-cat-count cvmm-cat-<?php echo absint( $cat_id ); ?>">
                                            <?php echo absint( $cat_count ); ?>
                                        </span>
                                <?php
                                    }
                                    
                                    if ( $descOption === true ) {
                                        $cat_desc = $cat_query->category_description;
                                        echo '<div class="cvmm-cat-content">';
                                            echo esc_html( $cat_desc );
                                        echo '</div>';
                                    }
                                ?>
                            </div><!-- .cvmm-cat-content-all-wrapper -->
                            
                        </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                    $loop_count++;
            endforeach;
        ?>
    </div>