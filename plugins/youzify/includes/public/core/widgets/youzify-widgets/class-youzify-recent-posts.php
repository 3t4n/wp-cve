<?php

class Youzify_Profile_Recent_Posts_Widget {

    /**
     * Content.
     */
    function widget() {

    	// Get Data .
        $recent_posts = get_posts(
            array(
                'orderby' => apply_filters( 'youzify_recent_posts_widget_order_by', 'date' ),
                'order'   => 'desc',
                'author'  => bp_displayed_user_id(),
                'numberposts' => youzify_option( 'youzify_wg_max_rposts', 3 )
            )
        );

        if ( empty( $recent_posts ) ) {
            return;
        }

		?>

        <div class="youzify-posts-by-author youzify-recent-posts youzify-rp-img-circle">
            <?php foreach ( $recent_posts as $post ) : ?>
            <div class="youzify-post-item">
                <?php youzify_get_post_thumbnail( array( 'attachment_id' => get_post_thumbnail_id( $post->ID ), 'size' => 'thumbnail', 'element' => 'profile-recent-posts-widget' ) );
                ?>
                <div class="youzify-post-head">
                    <h2 class="youzify-post-title">
                        <a href="<?php echo get_the_permalink( $post->ID ); ?>"><?php echo get_the_title( $post->ID ); ?></a>
                    </h2>
                    <div class="youzify-post-meta">
                        <ul><li><?php echo get_the_date( '', $post->ID ); ?></li></ul>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php

    }
}