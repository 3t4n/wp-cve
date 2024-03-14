<?php
/**
 * Slider block layout one - php render.
 */

$carouselAttr = ' data-dots='.esc_attr( ( $carouselDots ) ? $carouselDots : '0' ).' data-loop='.esc_attr( ($carouselLoop) ? $carouselLoop : '0' ).' data-control='.esc_attr( ($carouselControls) ? $carouselControls : '0' ).' data-auto='.esc_attr( ($carouselAuto) ? $carouselAuto : '0' ).' data-type='.esc_attr( ($carouselType) ? $carouselType : '0' ).' data-speed='.esc_attr( $carouselSpeed ).' data-autoplayspeed='.esc_attr( $carouselAutoplaySpeed );
?>
<div class="cvmm-slider-block-main-content-wrap">
    <div class="cvmm-slider-post-wrapper" <?php echo esc_attr( $carouselAttr ); ?>>
        <?php
            $taxonomies = get_taxonomies( array( 'object_type' => array( $sliderposttype ) ) );
            foreach( $taxonomies as $taxonomy ) {
                $taxonomy_name = $taxonomy;
                break;
            }
            
            $slider_post_args = array(
                'post_type'     => esc_html( $sliderposttype ),
                'posts_per_page' => esc_attr( $sliderpostCount ),
                'order'         => esc_html( $sliderorder ),
                'orderby'       => esc_html( $sliderorderBy ),
                'status'        => 'publish'
            );
            if( !empty( $sliderpostCategory ) && ( $sliderposttype == 'post' ) ) {
                $slider_post_args['cat'] = array( implode( ',', $sliderpostCategory ) );
            } elseif( !empty( $sliderpostCategory ) ) {
                $slider_post_args['tax_query'] = array(
                    array( 'taxonomy' => esc_html( $taxonomy_name ),
                            'terms' => array( implode( ',', $sliderpostCategory ) )
                ));
            }

            $slider_post_query = new WP_Query( $slider_post_args );
            if( !( $slider_post_query->have_posts() ) ) {
                return esc_html__( 'No posts found', 'wp-magazine-modules-lite' );
            }

            while( $slider_post_query->have_posts() ) : $slider_post_query->the_post();
                $post_id = get_the_ID();
                $post_format = get_post_format( $post_id );
                if( empty( $post_format ) ) {
                    $post_format = 'standard';
                }
                $author_id  = get_post_field( 'post_author', $post_id );
                $author_display_name = get_the_author_meta( 'display_name', $author_id );
                $author_url = get_author_posts_url( $author_id );
                
                if( $postFormatIcon ) {
                    $post_format .= ' cvmm-icon';
                }

                if( $postMetaIcon ) {
                    $getmetaIcon = ' cvmm-meta-icon-show';
                } else {
                    $getmetaIcon = " cvmm-meta-icon-hide";
                }

                if( $sliderposttype == 'post' ) {
                    $categories = get_the_category( $post_id );
                } else {
                    if( isset( $taxonomy_name ) ) {
                        $categories = get_the_terms( $post_id, $taxonomy_name );
                    } else {
                        $categories = '';
                    }
                }
                if( is_array( $categories ) ) {
                    $categories = array_slice( $categories, 0, $slidercategoriesCount );
                }

                $tags = get_the_tags( $post_id );
                if( $tags ) {
                    $tags = array_slice( $tags, 0, $slidertagsCount );
                }

                $comments_number = get_comments_number( $post_id );
        ?>
                <article post-id="post-<?php echo esc_attr( $post_id ); ?>" class="cvmm-post post-format--<?php echo esc_html( $post_format ); ?>" itemscope itemtype="<?php echo esc_url( 'http://schema.org/articleBody' ); ?>">
                    <?php
                        if( has_post_thumbnail() ) {
                            $image_url = get_the_post_thumbnail_url( $post_id, $imageSize );
                        } elseif( isset( $fallbackImage ) ) {
                            $image_url = $fallbackImage;
                        } else {
                            $image_url = WPMAGAZINE_MODULES_LITE_DEFAULT_IMAGE;
                        }
                    ?>
                    <div class="cvmm-post-thumb">
                        <a href="<?php the_permalink(); ?>" target="<?php echo esc_html( $permalinkTarget ); ?>"><img src="<?php echo esc_url( $image_url ); ?>" alt="<?php the_title(); ?>"/></a>
                    </div>

                    <div class="cvmm-post-content-all-wrapper">
                        <div class="cvmm-post-meta<?php echo esc_html( $getmetaIcon ); ?>">
                        <?php
                            if( $sliderdateOption ) {
                                echo '<span class="cvmm-post-date cvmm-post-meta-item" itemprop="datePublished">';
                                    echo '<a href="'.esc_url( get_the_permalink() ).'" target="'.esc_html( $permalinkTarget ).'">'.get_the_date().'</a>';
                                echo '</span>';
                            }

                            if( $sliderauthorOption ) {
                                echo '<span class="cvmm-post-author-name cvmm-post-meta-item" itemprop="author">';
                                    echo '<a href="'.esc_url( $author_url ).'" target="'.esc_html( $permalinkTarget ).'">';
                                    echo esc_html( $author_display_name );
                                    echo '</a>';
                                echo '</span>';
                            }
                            
                            if( $slidercategoryOption && $categories ) {
                                echo '<span class="cvmm-post-cats-wrap cvmm-post-meta-item">';
                                foreach( $categories as $category ) :
                                    echo '<span class="cvmm-post-cat cvmm-cat-'.absint( $category->term_id ).'"><a href="'.esc_url( get_term_link( $category->term_id ) ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $category->name ).'</a></span>';
                                endforeach;
                                echo '</span>';
                            }
                            
                            if( $slidertagsOption && $tags ) {
                                echo '<span class="cvmm-post-tags-wrap cvmm-post-meta-item">';
                                foreach( $tags as $tag ) :
                                    echo '<span class="cvmm-post-tag"><a href="'.esc_url( get_tag_link( $tag->term_id ) ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $tag->name ).'</a></span>';
                                endforeach;
                                echo '</span>';
                            }

                            if( $slidercommentOption ) {
                                echo '<span class="cvmm-post-comments-wrap cvmm-post-meta-item">';
                                    echo '<a href="'.esc_url( get_the_permalink() ).'/#comments" target="'.esc_html( $permalinkTarget ).'">';
                                        echo esc_attr( $comments_number );
                                        echo '<span class="cvmm-comment-txt">'.esc_html__( "Comments", "wp-magazine-modules" ).'</span>';
                                    echo '</a>';
                                echo '</span>';
                            }
                        ?>
                        </div>

                        <h2 class="cvmm-post-title">
                            <a href="<?php the_permalink(); ?>" target="<?php echo esc_html( $permalinkTarget ); ?>">
                                <?php the_title(); ?>
                            </a>
                        </h2>

                        <?php
                            if( $slidercontentOption === true ) {
                                echo '<div class="cvmm-post-content" itemprop="description">';
                                    if( $slidercontentType == 'content' ) {
                                        echo wp_trim_words( get_the_content(), $sliderwordCount );
                                    } else {
                                        echo wp_trim_words( get_the_excerpt(), $sliderwordCount );
                                    }
                                echo '</div>';
                            }
                            
                            if( has_filter( 'codevibrant_socialshare_public_render' ) ) :
                                echo apply_filters( 'codevibrant_socialshare_public_render', $socialShareOption, $socialShareLayout );
                            endif;
                            
                            if( $sliderbuttonOption && !empty( $sliderbuttonLabel ) ) {
                                echo '<div class="cvmm-read-more"><a href="'.esc_url( get_the_permalink() ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $sliderbuttonLabel );
                                    if( $postButtonIcon ) {
                                        echo '<i class="fas fa-arrow-right"></i>';
                                    }
                                echo '</a></div>';
                            }
                        ?>
                    </div>
                </article>
        <?php
            endwhile;
            wp_reset_postdata();
        ?>
    </div><!-- .cvmm-slider-post-wrapper -->
</div><!-- .cvmm-slider-block-main-content-wrap -->