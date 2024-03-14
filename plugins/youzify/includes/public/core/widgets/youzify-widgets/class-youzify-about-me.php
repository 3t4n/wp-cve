<?php

class Youzify_Profile_About_Me_Widget {

    /**
     * Profile Content.
     */
    function widget() {

        // Get Current User ID.
        $user_id = bp_displayed_user_id();

        // Get Widget Data
        $biography      = get_the_author_meta( 'youzify_wg_about_me_bio', $user_id );
        $description    = get_the_author_meta( 'youzify_wg_about_me_desc', $user_id );
        $photo          = get_the_author_meta( 'youzify_wg_about_me_photo', $user_id );
        $title          = get_the_author_meta( 'youzify_wg_about_me_title', $user_id );

        if ( empty( $biography ) && empty( $description ) && empty( $photo ) && empty( $title ) ) {
            return;
        }

    	?>

    	<div class="youzify-aboutme-content youzify-default-content">

            <?php if ( ! empty( $photo ) ) : ?>
    		<div class="youzify-user-img youzify-photo-<?php echo youzify_option( 'youzify_wg_aboutme_img_format', 'circle' ); ?>">
                <img loading="lazy" <?php echo is_numeric( $photo ) ? youzify_get_image_attributes( $photo, 'youzify-thumbnail', 'profile-about-me-widget', $user_id ) : youzify_get_image_attributes_by_link( $photo ); ?> alt=""></div>
            <?php endif; ?>

    		<div class="youzify-aboutme-container">

                <?php if ( $title || $description ) : ?>
    			<div class="youzify-aboutme-head">
    				<h2 class="youzify-aboutme-name"><?php esc_attr_e( $title ); ?></h2>
    				<h2 class="youzify-aboutme-description"><?php esc_attr_e( $description ); ?></h2>
    			</div>
                <?php endif; ?>

                <?php do_action( 'youzify_after_about_me_widget_head' ); ?>

                <?php if ( $biography ) : ?>
                    <div class="youzify-aboutme-bio"><?php echo wp_kses_post( wpautop( html_entity_decode( $biography ) ) ); ?></div>
                <?php endif; ?>

    		</div>

    	</div>

    	<?php

    }

}