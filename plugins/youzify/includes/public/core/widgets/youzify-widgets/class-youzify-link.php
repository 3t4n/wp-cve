<?php

class Youzify_Profile_Link_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get Current User ID
        $user_id = bp_displayed_user_id();

        // Get Widget Data
        $link_url = get_the_author_meta( 'youzify_wg_link_url', $user_id );

        if ( empty( $link_url ) ) {
            return;
        }

        // Get link Image ID
        $image_id = get_the_author_meta( 'youzify_wg_link_img', $user_id );

        ?>

        <div class="youzify-link-content link-with-img">
            <?php if ( $image_id ) : ?><img loading="lazy" <?php echo youzify_get_image_attributes( $image_id, 'youzify-wide', 'profile-link-widget' );?> alt=""><?php endif; ?>
            <div class="youzify-link-main-content">
                <div class="youzify-link-inner-content">
                    <div class="youzify-link-icon"><i class="fas fa-link"></i></div>
                    <p><?php echo get_the_author_meta( 'youzify_wg_link_txt', $user_id ); ?></p>
                    <a href="<?php echo esc_url( $link_url ); ?>" class="youzify-link-url" target="_blank" rel="nofollow noopener"><?php echo youzify_esc_url( $link_url ); ?></a>
                </div>
            </div>
        </div>

        <?php

    }

}