<?php

class Youzify_Profile_Quote_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get User ID
        $user_id = bp_displayed_user_id();

        // Get Quote Text
        $quote_txt = get_the_author_meta( 'youzify_wg_quote_txt', $user_id );

        if ( empty( $quote_txt ) ) {
            return;
        }

        // Get Quote Image.
        $image_id = get_the_author_meta( 'youzify_wg_quote_img', $user_id );

        // Call Custom Quote Styling.
        youzify_styling()->gradient_styling(
            array(
                'selector'      => 'body .quote-with-img:before',
                'left_color'    => 'youzify_wg_quote_gradient_left_color',
                'right_color'   => 'youzify_wg_quote_gradient_right_color'
            )
        );

        ?>

        <div class="youzify-quote-content quote-with-img">
            <?php if ( ! empty( $image_id ) ) : ?><img loading="lazy" <?php echo youzify_get_image_attributes( $image_id, 'youzify-wide', 'profile-quote-widget' ); ?> alt=""><?php endif; ?>
            <div class="youzify-quote-main-content">
                <div class="youzify-quote-icon"><i class="fas fa-quote-right"></i></div>
                <blockquote><?php echo nl2br( $quote_txt ); ?></blockquote>
                <h3 class="youzify-quote-owner"><?php esc_attr_e( get_the_author_meta( 'youzify_wg_quote_owner', $user_id ) ); ?></h3>
            </div>
        </div>

        <?php

    }

}