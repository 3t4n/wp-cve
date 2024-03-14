<?php

/**
 * Custom Widgets Settings.
 */
function youzify_custom_widget_settings() {

    do_action( 'youzify_profile_custom_widgets_settings' );

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'Info', 'youzify' ),
            'id'       => 'youzify_msgbox_custom_widgets_placement',
            'msg'      => __( 'All the custom widgets created will be added automatically to the bottom of the profile sidebar to change their placement or control their visibility go to <strong>Youzify Panel > Profile Settings > Profile Structure</strong>.', 'youzify' )
        )
    );

    // Check if feature is available.
    $is_available = youzify_is_feature_available();

    // Get Widgets List.
    youzify_get_custom_widgets_list();

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'Choose how you want your custom widgets to be loaded?', 'youzify' ),
            'id'    => 'youzify_custom_widgets_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Create New Custom Widgets Form.
 */
function youzify_get_custom_widgetsform() {

    // Get Data.
    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-custom-widgets-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Widget Icon', 'youzify' ),
            'desc'         => __( 'Select widget icon', 'youzify' ),
            'id'           => 'youzify_widget_icon',
            'std'          => 'fas fa-globe',
            'type'         => 'icon',
            'icons_type'   => 'web_application',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Widget Title', 'youzify' ),
            'desc'         => __( 'Add widget title', 'youzify' ),
            'id'           => 'youzify_widget_name',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Display Widget Title', 'youzify' ),
            'desc'       => __( 'Show widget title', 'youzify' ),
            'id'         => 'youzify_widget_display_title',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Use Widget Padding', 'youzify' ),
            'desc'       => __( 'Display widget padding', 'youzify' ),
            'id'         => 'youzify_widget_display_padding',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Widget Content', 'youzify' ),
            'id'         => 'youzify_widget_content',
            'desc'       => __( 'Paste your shortcode or any html code. you can use the following tags inside the content : <br>The tag {displayed_user} will be replaced by the displayed profile user id and the tag {logged_in_user} will be replaced by the logged-in user id.', 'youzify' ),
            'type'       => 'textarea',
            'no_options' => true
        )
    );

    // Add Hidden Input
    $Youzify_Settings->get_field(
        array(
            'id'         => 'youzify_custom_widgets_form',
            'type'       => 'hidden',
            'class'      => 'youzify-keys-name',
            'std'        => 'youzify_custom_widgets',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get Widgets List
 */
function youzify_get_custom_widgets_list() {

    global $Youzify_Settings;
    // Check if feature is available.
    $is_available = youzify_is_feature_available();

    // Get Custom Widgets
    $youzify_custom_widgets = youzify_option( 'youzify_custom_widgets' );

    ?>

    <script> var youzify_nextCustomWidget = <?php echo youzify_option( 'youzify_next_custom_widget_nbr', 1 ); ?>; </script>

    <div class="youzify-custom-section <?php if ( ! $is_available ) echo 'youzify-premium-builder'; ?>">
        <div class="youzify-cs-head">
            <div class="youzify-cs-buttons">
                <button class="youzify-md-trigger youzify-custom-widget-button" data-modal="youzify-custom-widgets-form">
                    <i class="fas fa-plus"></i>
                    <?php _e( 'Add New Widget', 'youzify' ); ?>
                    <?php if ( ! $is_available ) echo youzify_get_premium_tag(); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="youzify_custom_widgets" class="youzify-cs-content <?php if ( ! $is_available ) echo 'youzify-premium-builder'; ?>">

    <?php

        // Show No Ads Found .
        if ( empty( $youzify_custom_widgets ) ) {
            echo "<p class='youzify-no-content youzify-no-custom-widgets'>" . __( 'No custom widgets found!', 'youzify' ) . "</p></ul>";
            return false;
        }

        foreach ( $youzify_custom_widgets as $widget => $data ) :

            // Get Widget Data.
            $icon = $data['icon'];
            $name = $data['name'];
            $content = $data['content'];
            $display_title = $data['display_title'];
            $display_padding = $data['display_padding'];

            // Get Field Name.
            $input_name = esc_attr( "youzify_custom_widgets[$widget]" );

            ?>

            <!-- Widget Item -->
            <li class="youzify-custom-widget-item" data-widget-name="<?php echo esc_attr( $widget );?>">
                <h2 class="youzify-custom-widget-name"><i class="youzify-custom-widget-icon <?php echo $icon; ?>"></i><span><?php echo esc_html( $name ); ?></span></h2>
                <input type="hidden" name="<?php echo $input_name; ?>[icon]" value="<?php echo esc_attr($icon); ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[display_title]" value="<?php echo esc_attr($display_title); ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[display_padding]" value="<?php echo esc_attr( $display_padding ); ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[name]" value="<?php echo esc_attr( $name ); ?>">
                <input type="hidden" name="<?php echo $input_name; ?>[content]" value="<?php echo esc_attr( $content ); ?>">
                <a class="youzify-edit-item youzify-edit-custom-widget"></a>
                <a class="youzify-delete-item youzify-delete-custom-widget"></a>
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}