<?php

/**
 * Portfolio Settings.
 */
function youzify_portfolio_widget_settings() {

    // Call Scripts
    wp_enqueue_script( 'youzify-portfolio', YOUZIFY_ASSETS . 'js/youzify-portfolio.min.js', array( 'jquery', 'youzify-builder' ), YOUZIFY_VERSION, true );
    wp_localize_script( 'youzify-portfolio', 'Youzify_Portfolio', array(
        'upload_photo' => __( 'Upload Photo', 'youzify' ),
        'photo_title'  => __( 'Photo Title', 'youzify' ),
        'photo_link'   => __( 'Photo Link', 'youzify' ),
        'items_nbr'    => __( 'The number of items allowed is ', 'youzify' ),
        'no_items'     => __( 'No items found!', 'youzify' )
    ) );

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'portfolio' );

    $Youzify_Settings->get_field(
        array(
            'title'          => youzify_option( 'youzify_wg_portfolio_title', __( 'Portfolio', 'youzify' ) ),
            'button_id'      => 'youzify-portfolio-button',
            'button_text'    => __( 'Add New Photo', 'youzify' ),
            'id'             => $args['id'],
            'icon'           => $args['icon'],
            'widget_section' => true,
            'type'           => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'   => 'youzify-portfolio-data',
            'type' => 'hidden'
        ), false, 'youzify_data'
    );

    // Get Current User ID
    $user_id = bp_displayed_user_id();

    // Set Hidden Fields.
    echo '<input type="hidden" name="youzify_widget_user_id" value="' . $user_id . '">';
    echo '<input type="hidden" name="youzify_widget_source" value="profile_portfolio_widget">';

    $i = 0;

    $photos = get_the_author_meta( 'youzify_portfolio', $user_id );

    echo '<ul class="youzify-wg-opts youzify-wg-portfolio-options youzify-cphoto-options">';

    if ( ! empty( $photos ) ) :

        foreach ( $photos as $photo ) : $i++;

        ?>

        <li class="youzify-wg-item" data-wg="portfolio">
            <div class="youzify-wg-container">
                <div class="youzify-cphoto-content">
                    <div class="uk-option-item">
                        <div class="youzify-uploader-item">
                            <div class="youzify-photo-preview" style="background-image: url(<?php echo wp_get_attachment_image_url( $photo['image'], 'youzify-thumbnail' ); ?>);"></div>
                            <label for="youzify_portfolio_<?php echo $i; ?>" class="youzify-upload-photo" ><?php _e( 'Upload Photo', 'youzify' ); ?></label>
                            <input id="youzify_portfolio_<?php echo $i; ?>" type="file" name="youzify_portfolio_<?php echo $i; ?>" class="youzify_upload_file" accept="image/*">
                            <input type="hidden" name="youzify_portfolio[<?php echo $i; ?>][image]" value="<?php esc_attr_e( $photo['image'] ); ?>" class="youzify-photo-url">
                        </div>
                    </div>
                    <div class="uk-option-item">
                        <div class="option-content">
                            <input type="text" name="youzify_portfolio[<?php echo $i; ?>][title]" value="<?php esc_attr_e( $photo['title'] ); ?>" placeholder="<?php _e( 'Photo Title', 'youzify' ); ?>">
                        </div>
                    </div>
                    <div class="uk-option-item">
                        <div class="option-content">
                            <input type="text" name="youzify_portfolio[<?php echo $i; ?>][link]" value="<?php echo esc_url( $photo['link'] ); ?>" placeholder="<?php _e( 'Photo Link', 'youzify' ); ?>">
                        </div>
                    </div>
                </div>
            </div>
            <a class="youzify-delete-item"></a>
        </li>

        <?php endforeach; endif; ?>

        <script>
            var youzify_pf_nextCell = <?php echo $i+1; ?>,
                youzify_max_portfolio_img = <?php echo youzify_option( 'youzify_wg_max_portfolio_items', 9 ); ?>;
        </script>

        <?php

    echo '</ul>';

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}