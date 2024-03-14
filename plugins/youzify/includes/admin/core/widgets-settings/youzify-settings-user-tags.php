<?php

/**
 * User Tags Settings.
 */
function youzify_user_tags_widget_settings() {

    // Load User Tags Script.
    wp_enqueue_script( 'youzify-user-tags', YOUZIFY_ADMIN_ASSETS . 'js/youzify-user-tags.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
    wp_localize_script( 'youzify-user-tags', 'Youzify_User_Tags', array(
        'utag_name_empty' => __( 'User tag name is empty!', 'youzify' ),
        'no_user_tags'    => __( 'No user tags found!', 'youzify' ),
        'update_user_tag' => __( 'Update user tags type', 'youzify' )
    ) );

    global $Youzify_Settings;

    if ( bp_is_active( 'xprofile' ) ) {

        // Get Modal Args
        $modal_args = array(
            'button_id' => 'youzify-add-user-tag',
            'id'        => 'youzify-user-tags-form',
            'title'     => __( 'Create New Tag', 'youzify' )
        );

        // Get New User Tags Form.
        youzify_panel_modal_form( $modal_args, 'youzify_get_user_tags_form' );

        // Get User Tags List.
        youzify_get_user_tags_list();

    }

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Display Title', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_display_title',
            'desc'  => __( 'Show slideshow title', 'youzify' ),
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Widget Title', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_title',
            'desc'  => __( 'User tags widget title', 'youzify' ),
            'type'  => 'text'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Loading Effect', 'youzify' ),
            'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
            'desc'  => __( 'How you want the user tags to be loaded?', 'youzify' ),
            'id'    => 'youzify_user_tags_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Tags Icon', 'youzify' ),
            'desc'  => __( 'Display user tags icon', 'youzify' ),
            'id'    => 'youzify_enable_user_tags_icon',
            'type'  => 'checkbox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable User Tags Description', 'youzify' ),
            'desc'  => __( 'Display user tags description', 'youzify' ),
            'id'    => 'youzify_enable_user_tags_description',
            'type'  => 'checkbox'
        )
    );

$Youzify_Settings->get_field(
    array(
        'type'  => 'select',
        'id'    => 'youzify_wg_user_tags_border_style',
        'title' => __( 'Tags Border Style', 'youzify' ),
        'desc'  => __( 'Select tags border style', 'youzify' ),
        'opts'  => $Youzify_Settings->get_field_options( 'buttons_border_styles' )
    )
);

$Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'User Tags Styling Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Title', 'youzify' ),
            'desc'  => __( 'Tags type title color', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_title_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Icon', 'youzify' ),
            'desc'  => __( 'Tags type icon color', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_icon_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Description', 'youzify' ),
            'desc'  => __( 'Tags type description color', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_desc_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Background', 'youzify' ),
            'desc'  => __( 'Tags background color', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_background',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Text', 'youzify' ),
            'desc'  => __( 'Tags text color', 'youzify' ),
            'id'    => 'youzify_wg_user_tags_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

/**
 * Create New User Tags Form.
 */
function form() {

    // Get Data.
    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-user-tags-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Tags Type Icon', 'youzify' ),
            'desc'         => __( 'Select tag type icon', 'youzify' ),
            'id'           => 'youzify_user_tag_icon',
            'std'          => 'fas fa-globe',
            'type'         => 'icon',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Field', 'youzify' ),
            'desc'  => __( 'Select the tags source field name', 'youzify' ),
            'opts'  => youzify_get_user_tags_xprofile_fields(),
            'id'    => 'youzify_user_tag_field',
            'type'  => 'select',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Name', 'youzify' ),
            'desc'  => __( 'Type tag name by default is the field title', 'youzify' ),
            'id'    => 'youzify_user_tag_name',
            'type'  => 'text',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Description', 'youzify' ),
            'desc'  => __( 'Type tag description', 'youzify' ),
            'id'    => 'youzify_user_tag_description',
            'type'  => 'text',
            'no_options' => true
        )
    );

    // Add Hidden Input
    $Youzify_Settings->get_field(
        array(
            'type'       => 'hidden',
            'class'      => 'youzify-keys-name',
            'std'        => 'youzify_user_tags',
            'id'         => 'youzify_user_tags_form',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get User Tags List
 */
function youzify_get_user_tags_list() {

    // Get User Tag Items
    $youzify_user_tags = youzify_option( 'youzify_user_tags' );

    ?>

    <script> var youzify_nextUTag = <?php echo youzify_option( 'youzify_next_user_tag_nbr', 1 ); ?>; </script>

    <div class="youzify-custom-section">
        <div class="youzify-cs-head">
            <div class="youzify-cs-buttons">
                <button class="youzify-md-trigger youzify-user-tags-button" data-modal="youzify-user-tags-form">
                    <i class="fas fa-user-plus"></i>
                    <?php _e( 'Add New User Tag', 'youzify' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="youzify_user_tags" class="youzify-cs-content">

    <?php

        // Show No Tags Found .
        if ( empty( $youzify_user_tags ) ) {
            echo "<p class='youzify-no-content youzify-no-user-tags'>" . __( 'No user tags found!', 'youzify' ) . "</p></ul>";
            return false;
        }

        foreach ( $youzify_user_tags as $tag => $data ) :

            // Get Widget Data.
            $icon  = $data['icon'];
            $title = $data['name'];
            $field = $data['field'];
            $desc  = $data['description'];

            // Get Field Name.
            $name = "youzify_user_tags[$tag]";

            ?>

            <!-- Tag Item -->
            <li class="youzify-user-tag-item" data-user-tag-name="<?php echo $tag; ?>">
                <h2 class="youzify-user-tag-name">
                    <i class="youzify-user-tag-icon <?php echo apply_filters( 'youzify_user_tags_builder_icon', $icon ); ?>"></i>
                    <span><?php echo $title; ?></span>
                </h2>
                <input type="hidden" name="<?php echo $name; ?>[icon]" value="<?php echo $icon; ?>">
                <input type="hidden" name="<?php echo $name; ?>[name]" value="<?php echo $title; ?>">
                <input type="hidden" name="<?php echo $name; ?>[field]" value="<?php echo $field; ?>">
                <input type="hidden" name="<?php echo $name; ?>[description]" value="<?php echo $desc; ?>">
                <a class="youzify-edit-item youzify-edit-user-tag"></a>
                <a class="youzify-delete-item youzify-delete-user-tag"></a>
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}

/**
 * Create New User Tags Form.
 */
function youzify_get_user_tags_form() {

    // Get Data.
    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-user-tags-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Tags Type Icon', 'youzify' ),
            'desc'         => __( 'Select tag type icon', 'youzify' ),
            'id'           => 'youzify_user_tag_icon',
            'std'          => 'fas fa-globe',
            'type'         => 'icon',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Field', 'youzify' ),
            'desc'  => __( 'Select the tags source field name', 'youzify' ),
            'opts'  => youzify_get_user_tags_xprofile_fields(),
            'id'    => 'youzify_user_tag_field',
            'type'  => 'select',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Name', 'youzify' ),
            'desc'  => __( 'Type tag name by default is the field title', 'youzify' ),
            'id'    => 'youzify_user_tag_name',
            'type'  => 'text',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Tags Type Description', 'youzify' ),
            'desc'  => __( 'Type tag description', 'youzify' ),
            'id'    => 'youzify_user_tag_description',
            'type'  => 'text',
            'no_options' => true
        )
    );

    // Add Hidden Input
    $Youzify_Settings->get_field(
        array(
            'type'       => 'hidden',
            'class'      => 'youzify-keys-name',
            'std'        => 'youzify_user_tags',
            'id'         => 'youzify_user_tags_form',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}