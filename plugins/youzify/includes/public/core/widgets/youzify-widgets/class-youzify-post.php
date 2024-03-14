<?php

class Youzify_Profile_Post_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get User ID
        $user_id = bp_displayed_user_id();

        // Get Post ID
        $post_id = get_the_author_meta( 'youzify_wg_post_id', $user_id );

        if ( empty( $post_id ) ) {
            return;
        }

        // Get Post Data
        $post = get_post( $post_id );

        if ( ! $post ) {
            return;
        }

        // Get Post Type
        $post_type = get_the_author_meta( 'youzify_wg_post_type', $user_id );

         // Show / Hide Post Elements
        $display_icons = youzify_option( 'youzify_display_wg_post_meta_icons', 'on' );

        $post_widget_img_size = apply_filters( 'youzify_post_widget_thumbnail_size', 'medium' );

        ?>

        <div class="youzify-post-content">

            <?php youzify_get_post_thumbnail( array( 'attachment_id' => get_post_thumbnail_id( $post_id ), 'size' => $post_widget_img_size, 'element' => 'profile-post-widget' ) ); ?>

            <div class="youzify-post-container">

                <div class="youzify-post-inner-content">

                    <div class="youzify-post-head">

                        <a class="youzify-post-type"><?php echo $post_type; ?></a>

                        <h2 class="youzify-post-title"><a href="<?php the_permalink( $post_id ); ?>"><?php echo $post->post_title; ?></a></h2>

                        <?php if ( 'on' == youzify_option( 'youzify_display_wg_post_meta', 'on' ) ) : ?>

                        <div class="youzify-post-meta">

                            <ul>

                                <?php if ( 'on' == youzify_option( 'youzify_display_wg_post_date', 'on' ) ) : ?>
                                    <li>
                                        <?php
                                            if ( 'on' == $display_icons ) {
                                                echo '<i class="far fa-calendar-alt"></i>';
                                            }
                                            // Print date.
                                            echo get_the_date( 'F j, Y', $post_id );
                                        ?>
                                    </li>
                                <?php endif; ?>

                                <?php
                                    if ( 'on' == youzify_option( 'youzify_display_wg_post_cats', 'on' ) )  {
                                        youzify_get_post_categories( $post_id, $display_icons );
                                    }
                                ?>

                                <?php if ( 'on' == youzify_option( 'youzify_display_wg_post_comments', 'on' ) ) : ?>
                                    <li>
                                        <?php

                                            if ( 'on' == $display_icons ) {
                                                echo '<i class="far fa-comments"></i>';
                                            }

                                            // Print Comments Number
                                            echo $post->comment_count;

                                        ?>
                                    </li>
                                <?php endif; ?>

                            </ul>

                        </div>

                        <?php endif; ?>

                    </div>

                    <?php if ( 'on' == youzify_option( 'youzify_display_wg_post_excerpt', 'on' ) ) : ?>
                        <div class="youzify-post-text">
                            <p><?php echo youzify_get_excerpt( $post->post_content, 35 ) ; ?></p>
                        </div>
                    <?php endif; ?>

                    <?php  if ( 'on' == youzify_option( 'youzify_display_wg_post_tags', 'on' ) ) { $this->get_post_tags( $post_id ); } ?>

                    <?php if ( 'on' == youzify_option( 'youzify_display_wg_post_readmore', 'on' ) ) : ?>
                        <a href="<?php the_permalink( $post_id ); ?>" class="youzify-read-more">
                            <div class="youzify-rm-icon">
                                <i class="fas fa-angle-double-right"></i>
                            </div>
                            <?php _e( 'Read More', 'youzify' ); ?>
                        </a>
                    <?php endif; ?>

                </div>

            </div>

        </div>

        <?php
    }


    /**
     * Get Post Tags
     */
    function get_post_tags( $post_id ) { ?>

        <ul class="youzify-post-tags"><?php

            // Get Post Tags List.
            $tags_list = get_the_tags( $post_id );

            if ( $tags_list ) {
                foreach ( $tags_list as $tag ) {

                    $tag_link = "<a href='" . get_tag_link( $tag->term_taxonomy_id ) . "'>{$tag->name}</a>";
                    echo "<li><span class='youzify-tag-symbole'>#</span>$tag_link</li>";
                }
            }

        ?></ul>

        <?php
    }

}