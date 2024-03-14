<?php

/**
 * Services Settings.
 */
function youzify_services_widget_settings() {

    // Call Scripts
    youzify_iconpicker_scripts();
    wp_enqueue_script( 'youzify-services', YOUZIFY_ASSETS . 'js/youzify-services.min.js', array( 'jquery', 'youzify-builder' ), YOUZIFY_VERSION, true );
    wp_localize_script( 'youzify-services', 'Youzify_Services', array(
        'serv_desc_desc'  => __( 'Add service description', 'youzify' ),
        'serv_desc_icon'  => __( 'Select service icon', 'youzify' ),
        'service_desc'    => __( 'Service Description', 'youzify' ),
        'serv_desc_title' => __( 'Type service title', 'youzify' ),
        'service_title'   => __( 'Service Title', 'youzify' ),
        'service_icon'    => __( 'Service Icon', 'youzify' ),
        'items_nbr'       => __( 'The number of items allowed is ', 'youzify' ),
        'no_items'        => __( 'No items found!', 'youzify' )
    ) );

    global $Youzify_Settings;

    // Get Args
    $args = youzify_get_profile_widget_args( 'services' );

    $Youzify_Settings->get_field(
        array(
            'title'          => youzify_option( 'youzify_wg_services_title', __( 'Services', 'youzify' ) ),
            'button_text'    => __( 'Add New Service', 'youzify' ),
            'id'             => $args['id'],
            'icon'           => $args['icon'],
            'button_id'      => 'youzify-service-button',
            'widget_section' => true,
            'type'           => 'open'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify-services-data',
            'type'  => 'hidden'
        ), false, 'youzify_data'
    );

    echo '<ul class="youzify-wg-opts youzify-wg-services-options">';

    $i = 0;
    $services_options = get_the_author_meta( 'youzify_services', bp_displayed_user_id() );

    if ( ! empty( $services_options ) ) :

    // Options titles
    $label_title = __( 'Service Title', 'youzify' );
    $label_desc  = __( 'Service Description', 'youzify' );

    foreach ( $services_options as $service ) : $i++;

        // init Variables.
        $service_title = sanitize_text_field( $service['title'] );
        $service_desc  = sanitize_textarea_field( $service['description'] );
        $service_icon  = ! empty( $service['icon'] ) ? $service['icon'] : 'fas fa-globe';

    ?>

        <li class="youzify-wg-item" data-wg="services">
            <div class="youzify-wg-container">

                <div class="uk-option-item">
                    <div class="youzify-option-inner">
                        <div class="option-infos">
                            <label><?php _e( 'Service Icon', 'youzify' ); ?></label>
                            <p class="option-desc"><?php _e( 'Select service icon', 'youzify' ); ?></p>
                        </div>
                        <div class="option-content">
                            <div id="ukai_icon_<?php echo $i; ?>" class="ukai_iconPicker" data-icons-type="web-application">
                                <div class="ukai_icon_selector">
                                    <i class="<?php echo apply_filters( 'youzify_service_item_icon', $service_icon ); ?>"></i>
                                    <span class="ukai_select_icon"><i class="fas fa-sort-down"></i></span>
                                </div>
                                <input type="hidden" class="ukai-selected-icon" name="youzify_services[<?php echo $i; ?>][icon]" value="<?php echo $service_icon; ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="uk-option-item">
                    <div class="youzify-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_title; ?></label>
                            <p class="option-desc"><?php _e( 'Type service title', 'youzify' ); ?></p>
                        </div>
                        <div class="option-content">
                            <input type="text" name="youzify_services[<?php echo $i; ?>][title]" value="<?php esc_attr_e( $service_title ); ?>" placeholder="<?php esc_attr_e( $label_title ); ?>">
                        </div>
                    </div>
                </div>

                <div class="uk-option-item">
                    <div class="youzify-option-inner">
                        <div class="option-infos">
                            <label><?php echo $label_desc; ?></label>
                            <p class="option-desc"><?php _e( 'Add service description', 'youzify' ); ?></p>
                        </div>
                        <div class="option-content">
                            <textarea name="youzify_services[<?php echo $i; ?>][description]" placeholder="<?php echo $label_desc; ?>"><?php echo $service_desc; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
            <a class="youzify-delete-item"></a>
        </li>

        <?php endforeach; endif; ?>

        <script>
            var youzify_service_nextCell = <?php echo $i+1; ?>,
                youzify_max_services_nbr = <?php echo youzify_option( 'youzify_wg_max_services', 4 ); ?>;
        </script>

        <?php

    echo '</ul>';

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );

}