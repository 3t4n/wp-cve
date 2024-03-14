<?php

/**
 * Slideshow Settings.
 */
function youzify_slideshow_widget_settings() {

    // Call Script
    wp_enqueue_script( 'youzify-slideshow', YOUZIFY_ASSETS . 'js/youzify-slideshow.min.js', array( 'jquery', 'youzify-builder' ), YOUZIFY_VERSION, true );
    wp_localize_script( 'youzify-slideshow', 'Youzify_Slideshow', array(
        'upload_photo' => __( 'Upload Photo', 'youzify' ),
        'no_items'     => __( 'No items found!', 'youzify' ),
        'items_nbr'    => __( 'The number of items allowed is ', 'youzify' )
    ));

    global $Youzify_Settings;

    // Get Current User ID
    $user_id = bp_displayed_user_id();

    // Get Args
    $args = youzify_get_profile_widget_args( 'slideshow' );

    $Youzify_Settings->get_field(
        array(
            'title'          => youzify_option( 'youzify_wg_slideshow_title', __( 'Slideshow', 'youzify' ) ),
            'button_text'    => __( 'Add New Slide', 'youzify' ),
            'button_id'      => 'youzify-slideshow-button',
            'id'             => $args['id'],
            'icon'           => $args['icon'],
            'widget_section' => true,
            'type'           => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify-slideshow-data',
            'type'  => 'hidden'
        ), false, 'youzify_data'
    );

    echo '<ul class="youzify-wg-opts youzify-wg-slideshow-options youzify-cphoto-options">';

    $i = 0;
    $slides = get_the_author_meta( 'youzify_slideshow', $user_id );

    // Set Hidden Fields.
    echo '<input type="hidden" name="youzify_widget_user_id" value="' . $user_id . '">';
    echo '<input type="hidden" name="youzify_widget_source" value="profile_slideshow_widget">';

    if ( ! empty( $slides ) ) :

    foreach ( $slides as $slide ) : $i++; ?>

    <li class="youzify-wg-item" data-wg="slideshow">
        <div class="youzify-wg-container">
            <div class="youzify-cphoto-content">
                <div class="uk-option-item">
                    <div class="youzify-uploader-item">
                        <div class="youzify-photo-preview" style="background-image: url(<?php echo wp_get_attachment_image_url( $slide['image'], 'youzify-thumbnail' ); ?>);"></div>
                        <label for="youzify_slideshow_<?php echo $i; ?>" class="youzify-upload-photo" ><?php _e( 'Upload Photo', 'youzify' ); ?></label>
                        <input id="youzify_slideshow_<?php echo $i; ?>" type="file" name="youzify_slideshow_<?php echo $i; ?>" class="youzify_upload_file" accept="image/*" />
                        <input type="hidden" name="youzify_slideshow[<?php echo $i; ?>][image]" value="<?php esc_attr_e( $slide['image'] ); ?>" class="youzify-photo-url">
                    </div>
                </div>
            </div>
        </div>
        <a class="youzify-delete-item"></a>
    </li>

    <?php endforeach; endif; ?>

    <script>
        var youzify_ss_nextCell = <?php echo $i+1; ?>,
            youzify_max_slideshow_img = <?php echo youzify_option( 'youzify_wg_max_slideshow_items', 3 ); ?>;
    </script>

    <?php

    echo '</ul>';

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}