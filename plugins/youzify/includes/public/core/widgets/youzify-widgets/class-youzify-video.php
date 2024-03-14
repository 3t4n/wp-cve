<?php

class Youzify_Profile_Video_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get Current User ID
        $user_id = bp_displayed_user_id();

        // Get Widget Data
        $video_url = get_the_author_meta( 'youzify_wg_video_url', $user_id );

        if ( empty( $video_url ) ) {
            return;
        }

        // Init Vars.
        $video_desc  = get_the_author_meta( 'youzify_wg_video_desc', $user_id );
        $video_title = get_the_author_meta( 'youzify_wg_video_title', $user_id );

        ?>

        <div class="youzify-video-content">

            <div class="fittobox">
                <?php
                    if ( false != filter_var( $video_url, FILTER_VALIDATE_URL )  ) {
                        $content = apply_filters( 'the_content', esc_url( $video_url ) );
                        echo apply_filters( 'youzify_profile_video_widget_url', $content, $video_url );
                    }
                ?>
            </div>

            <?php if ( ! empty( $video_title ) || ! empty( $video_desc ) ) : ?>
                <div class="youzify-video-head">
                    <h2 class="youzify-video-title"><?php echo $video_title; ?></h2>
                    <div class="youzify-video-desc"><?php echo wpautop( wp_kses_post( html_entity_decode( $video_desc ) ) ); ?></div>
                </div>
            <?php endif; ?>

        </div>

        <?php

    }

}