<?php

/**
 * Custom Tabs Settings.
 */
function youzify_profile_custom_tabs_settings() {

    do_action( 'youzify_profile_custom_tabs_settings' );

    // Get Custom Tabs Items
    $youzify_custom_tabs = youzify_option( 'youzify_custom_tabs' );

    // Check if feature is available.
    $is_available = youzify_is_feature_available();

    ?>

    <script> var youzify_nextTab = <?php echo youzify_option( 'youzify_next_custom_tab_nbr', 1 ); ?>; </script>

    <div class="youzify-custom-section <?php if ( ! $is_available ) echo 'youzify-premium-builder'; ?>">
        <div class="youzify-cs-head">
            <div class="youzify-cs-buttons">
                <button class="youzify-md-trigger youzify-custom-tabs-button" data-modal="youzify-custom-tabs-form">
                    <i class="fas fa-plus-circle"></i>
                    <?php _e( 'Add New Tab', 'youzify' ); ?>
                    <?php if ( ! $is_available ) echo youzify_get_premium_tag(); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="youzify_custom_tabs" class="youzify-cs-content <?php if ( ! $is_available ) echo 'youzify-premium-builder'; ?>">

    <?php

        // Show No Tabs Found .
        if ( empty( $youzify_custom_tabs ) ) {
            echo "<p class='youzify-no-content youzify-no-custom-tabs'>" . __( 'No custom tabs found!', 'youzify' ) .  "</p></ul>";
            return false;
        }

        foreach ( $youzify_custom_tabs as $tab => $data ) :

            // Get Field Name.
            $name = "youzify_custom_tabs[$tab]";

            ?>

            <!-- Tab Item -->
            <li class="youzify-custom-tab-item" data-tab-name="<?php echo $tab; ?>">
                <h2 class="youzify-custom-tab-name"><i class="youzify-custom-tab-icon fas fa-angle-right"></i><span><?php echo $data['title']; ?></span></h2>
                <input type="hidden" name="<?php echo $name; ?>[slug]" value="<?php echo $data['slug']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[link]" value="<?php echo $data['link']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $data['type']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[title]" value="<?php echo $data['title']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[content]" value="<?php echo $data['content']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[display_sidebar]" value="<?php echo $data['display_sidebar']; ?>">
                <input type="hidden" name="<?php echo $name; ?>[display_nonloggedin]" value="<?php echo $data['display_nonloggedin']; ?>">
                <a class="youzify-edit-item youzify-edit-custom-tab"></a>
                <a class="youzify-delete-item youzify-delete-custom-tab"></a>
            </li>

        <?php endforeach; ?>

    </ul>

    <?php

}

/**
 * Create New Custom Widgets Form.
 */
function youzify_profile_custom_tabs_form() {

    // Get Data.
    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-custom-tabs-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Show for Non Logged-in', 'youzify' ),
            'desc'       => __( 'Display tab for non logged-in users', 'youzify' ),
            'id'         => 'youzify_tab_display_nonloggedin',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Tab Title', 'youzify' ),
            'desc'         => __( 'Add tab title', 'youzify' ),
            'id'           => 'youzify_tab_title',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Tab Slug', 'youzify' ),
            'desc'         => __( 'Should be in english lowercase letters and without spaces you can use only underscores to link words example: new_company', 'youzify' ),
            'id'           => 'youzify_tab_slug',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Tab Type', 'youzify' ),
            'id'         => 'youzify_tab_type',
            'desc'       => __( 'Choose the tab type', 'youzify' ),
            'std'        => 'link',
            'no_options' => true,
            'type'       => 'radio',
            'opts'       => array(
                'link'    => __( 'Link', 'youzify' ),
                'shortcode' => __( 'Shortcode', 'youzify' )
            ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Tab Link', 'youzify' ),
            'id'         => 'youzify_tab_link',
            'desc'       => __( 'You can use the tag {username} in the link and it will be replaced by the displayed profile username.', 'youzify' ),
            'class'		 => 'youzify-custom-tabs-link-item',
            'type'       => 'text',
            'no_options' => true
        )
    );


    // Tabs ShortCode Options
    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-custom-tabs-shortcode-items'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Display page sidebar', 'youzify' ),
            'desc'       => __( 'Show page sidebar works only on horizontal layout', 'youzify' ),
            'id'         => 'youzify_tab_display_sidebar',
            'type'       => 'checkbox',
            'std'        => 'on',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Tab Content', 'youzify' ),
            'id'         => 'youzify_tab_content',
            'desc'       => __( 'Paste your shortcode or any html code. you can use the following tags inside the content :
the tag {displayed_username} will be replaced by the displayed profile username, the tag {displayed_user_id} will be replaced by the displayed profile user ID and the tag {logged_in_user} will be replaced by the logged-in user id.', 'youzify' ),
            'type'       => 'textarea',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

    // Add Hidden Input
    $Youzify_Settings->get_field(
        array(
            'id'         => 'youzify_custom_tabs_form',
            'type'       => 'hidden',
            'class'      => 'youzify-keys-name',
            'std'        => 'youzify_custom_tabs',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}