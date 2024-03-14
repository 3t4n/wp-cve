<?php
/**
 * Post List block layout default - php render.
 */
    if( $postMargin ) {
        $postClass = 'cvmm-post--imagemargin column--'.esc_html( $blockColumn );
    } else {
        $postClass = 'cvmm-post-no--imagemargin column--'.esc_html( $blockColumn );
    }
?>
    <div class="cvmm-post-wrapper <?php echo esc_html( $postClass ); ?>">
        <?php
            $taxonomies = get_taxonomies( array( 'object_type' => array( $posttype ) ) );
            foreach( $taxonomies as $taxonomy ) {
                $taxonomy_name = $taxonomy;
                break;
            }
            
            $list_post_args = array(
                'post_type'     => esc_html( $posttype ),
                'posts_per_page' => esc_attr( $postCount ),
                'order'         => esc_html( $order ),
                'orderby'       => esc_html( $orderBy ),
                'post_status'   => 'publish'
            );
            if( !empty( $postCategory ) && ( $posttype == 'post' ) ) {
                $list_post_args['cat'] = array( implode( ',', $postCategory ) );
            } elseif( !empty( $postCategory ) ) {
                $list_post_args['tax_query'] = array(
                    array( 'taxonomy' => esc_html( $taxonomy_name ),
                            'terms' => array( implode( ',', $postCategory ) )
                ));
            }

            $list_post_query = new WP_Query( $list_post_args );
            if( !( $list_post_query->have_posts() ) ) {
                return esc_html__( 'No posts found', 'wp-magazine-modules-lite' );
            }

            while( $list_post_query->have_posts() ) : $list_post_query->the_post();
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

                if( $posttype == 'post' ) {
                    $categories = get_the_category( $post_id );
                } else {
                    if( isset( $taxonomy_name ) ) {
                        $categories = get_the_terms( $post_id, $taxonomy_name );
                    } else {
                        $categories = '';
                    }
                }
                if( is_array( $categories ) ) {
                    $categories = array_slice( $categories, 0, $categoriesCount );
                }

                $tags = get_the_tags( $post_id );
                if( $tags ) {
                    $tags = array_slice( $tags, 0, $tagsCount );
                }

                $comments_number = get_comments_number( $post_id );
        ?>
                <article post-id="post-<?php echo esc_attr( $post_id ); ?>" class="cvmm-post post-format--<?php echo esc_html( $post_format ); ?>" itemscope itemtype="<?php echo esc_url( 'http://schema.org/articleBody' ); ?>">
                    <?php
                        if( $thumbOption ) :
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
                    <?php
                        endif;
                    ?>

                <div class="cvmm-post-content-all-wrapper">
                    <?php
                        if( $titleOption ) :
                    ?>
                            <h2 class="cvmm-post-title">
                                <a href="<?php the_permalink(); ?>" target="<?php echo esc_html( $permalinkTarget ); ?>">
                                    <?php the_title(); ?>
                                </a>
                            </h2>
                    <?php
                        endif;
                    ?>

                    <div class="cvmm-post-meta<?php echo esc_html( $getmetaIcon ); ?>">
                        <?php

                            if( $categoryOption && $categories ) {
                                echo '<span class="cvmm-post-cats-wrap cvmm-post-meta-item">';
                                foreach( $categories as $category ) :
                                    echo '<span class="cvmm-post-cat cvmm-cat-'.absint( $category->term_id ).'"><a href="'.esc_url( get_term_link( $category->term_id ) ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $category->name ).'</a></span>';
                                endforeach;
                                echo '</span>';
                            }

                            if( $dateOption ) {
                                echo '<span class="cvmm-post-date cvmm-post-meta-item" itemprop="datePublished">';
                                    echo '<a href="'.esc_url( get_the_permalink() ).'" target="'.esc_html( $permalinkTarget ).'">'.get_the_date().'</a>';
                                echo '</span>';
                            }

                            if( $authorOption ) {
                                echo '<span class="cvmm-post-author-name cvmm-post-meta-item" itemprop="author">';
                                    echo '<a href="'.esc_url( $author_url ).'" target="'.esc_html( $permalinkTarget ).'">';
                                    echo esc_html( $author_display_name );
                                    echo '</a>';
                                echo '</span>';
                            }
                          

                            if( $tagsOption && $tags ) {
                                echo '<span class="cvmm-post-tags-wrap cvmm-post-meta-item">';
                                foreach( $tags as $tag ) :
                                    echo '<span class="cvmm-post-tag"><a href="'.esc_url( get_tag_link( $tag->term_id ) ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $tag->name ).'</a></span>';
                                endforeach;
                                echo '</span>';
                            }

                            if( $commentOption ) {
                                echo '<span class="cvmm-post-comments-wrap cvmm-post-meta-item">';
                                    echo '<a href="'.esc_url( get_the_permalink() ).'/#comments" target="'.esc_html( $permalinkTarget ).'">';
                                        echo esc_attr( $comments_number );
                                        echo '<span class="cvmm-comment-txt">'.esc_html__( "Comments", "wp-magazine-modules" ).'</span>';
                                    echo '</a>';
                                echo '</span>';
                            }
                        ?>
                    </div>
                    <?php
                        if( $contentOption === true ) {
                            echo '<div class="cvmm-post-content" itemprop="description">';
                                if( $contentType == 'content' ) {
                                    echo wp_trim_words( get_the_content(), $wordCount );
                                } else {
                                    echo wp_trim_words( get_the_excerpt(), $wordCount );
                                }
                            echo '</div>';
                        }

                        if( has_filter( 'codevibrant_socialshare_public_render' ) ) :
                            echo apply_filters( 'codevibrant_socialshare_public_render', $socialShareOption, $socialShareLayout );
                        endif;

                        if( $buttonOption && !empty( $buttonLabel ) ) {
                            echo '<div class="cvmm-read-more"><a href="'.esc_url( get_the_permalink() ).'" target="'.esc_html( $permalinkTarget ).'">'.esc_html( $buttonLabel );
                                if( $postButtonIcon ) {
                                    echo '<i class="fas fa-arrow-right"></i>';
                                }
                            echo '</a></div>';
                        }
                    ?>

                </div><!-- .cvmm-post-content-all-wrapper --> 
            </article>
        <?php
            endwhile;
            wp_reset_postdata();
        ?>
    </div>