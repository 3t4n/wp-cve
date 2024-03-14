<?php

/**
 * Admin Settings.
 */
function youzify_social_networks_settings() {

    global $Youzify_Settings;

    wp_enqueue_script( 'youzify-networks', YOUZIFY_ADMIN_ASSETS . 'js/youzify-networks.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
    wp_localize_script( 'youzify-networks', 'Youzify_Networks', array(
            'update_network' => __( 'Update Network', 'youzify' ),
            'no_networks' => __( 'No social networks found!', 'youzify' )
        )
    );

    // Network Args
    $modal_args = array(
        'id'        => 'youzify-networks-form',
        'title'     => __( 'Add New Social Network', 'youzify' ),
        'button_id' => 'youzify-add-network'
    );

    // Get 'Create new ad' Form.
    youzify_panel_modal_form( $modal_args, 'youzify_add_new_network_form' );

    // Get Available Social Networks.
    youzify_get_social_network();

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Social Networks Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Type', 'youzify' ),
            'id'    => 'youzify_navbar_sn_bg_type',
            'desc'  => __( 'Networks background type', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'icons_colors' )
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Networks Style', 'youzify' ),
            'id'    => 'youzify_navbar_sn_bg_style',
            'desc'  => __( 'Networks background style', 'youzify' ),
            'type'  => 'select',
            'opts'  => $Youzify_Settings->get_field_options( 'border_styles' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 *  Add New Network Form
 */
function youzify_add_new_network_form() {

    // Get Data.
    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-networks-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Network Icon', 'youzify' ),
            'desc'         => __( 'Select network icon', 'youzify' ),
            'id'           => 'youzify_network_icon',
            'std'          => 'fas fa-share-alt',
            'type'         => 'icon',
            'icons_type'   => 'social-networks',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Network Color', 'youzify' ),
            'desc'         => __( 'Choose network color', 'youzify' ),
            'id'           => 'youzify_network_color',
            'type'         => 'color',
            'no_options'   => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'        => __( 'Network Name', 'youzify' ),
            'desc'         => __( 'Add network name', 'youzify' ),
            'id'           => 'youzify_network_name',
            'type'         => 'text',
            'no_options'   => true
        )
    );

    // Add Hidden Input
    $Youzify_Settings->get_field(
        array(
            'id'         => 'youzify_networks_form',
            'class'      => 'youzify-keys-name',
            'type'       => 'hidden',
            'std'        => 'youzify_networks',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 *  Get Networks
 */
function youzify_get_social_network() {

    // Get Social Networks Items
    $social_networks = youzify_option( 'youzify_social_networks' );

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    ?>

    <script> var youzify_nextSN = <?php echo youzify_option( 'youzify_next_snetwork_nbr', 5 ); ?>; </script>

    <div class="youzify-custom-section">
        <div class="youzify-cs-head">
            <div class="youzify-cs-buttons">
                <button class="youzify-md-trigger youzify-networks-button" data-modal="youzify-networks-form">
                    <i class="fas fa-share-alt"></i>
                    <?php _e( 'Add New Network', 'youzify' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="youzify_networks" class="youzify-cs-content">

    <?php

        // Show No Networks Found .
        if ( empty( $social_networks ) ) {
            echo "<p class='youzify-no-content youzify-no-networks'>" . __( 'No social networks Found!', 'youzify' ) . "</p></ul>";
            return false;
        }

        foreach ( $social_networks as $network => $data ) {

            // Get Widget Data
            $name     = $data['name'];
            $icon     = apply_filters( 'youzify_panel_networks_icon', $data['icon'] );
            $color    = $data['color'];
            $sn_name  = "youzify_networks[$network]";

            ?>

            <!-- Network Item -->
            <li class="youzify-network-item" data-network-name="<?php echo $network;?>">
                <h2 class="youzify-network-name" style="border-color: <?php echo $color; ?>;">
                    <i class="fab youzify-network-icon <?php echo $icon; ?>"></i>
                    <span><?php echo $name; ?></span>
                </h2>
                <input type="hidden" name="<?php echo $sn_name; ?>[name]" value="<?php echo $name; ?>">
                <input type="hidden" name="<?php echo $sn_name; ?>[icon]" value="<?php echo $icon; ?>">
                <input type="hidden" name="<?php echo $sn_name; ?>[color]" value="<?php echo $color; ?>">
                <a class="youzify-edit-item youzify-edit-network"></a>
                <a class="youzify-delete-item youzify-delete-network"></a>
            </li>

         <?php } ?>

    </ul>

    <?php
}