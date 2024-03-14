<?php

/**
 * Ads Settings.
 */
function youzify_get_ads_settings() {

    wp_enqueue_script( 'youzify-ads', YOUZIFY_ADMIN_ASSETS . 'js/youzify-ads.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
    wp_localize_script( 'youzify-ads', 'Youzify_Ads', array(
        'empty_ad' => __( 'Ad name is empty or already exists!', 'youzify' ),
        'empty_banner' => __( 'Banner field is empty !', 'youzify' ),
        'code_empty'   => __( 'Ad code is empty!', 'youzify' ),
        'update_ad'    => __( 'Update Ad', 'youzify' ),
        'no_ads'       => __( 'No ads found!', 'youzify' )
    ));

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'msg_type' => 'info',
            'type'     => 'msgBox',
            'title'    => __( 'info', 'youzify' ),
            'id'       => 'youzify_msgbox_ads_placement',
            'msg'      => __( 'All the ads created will be added automatically to the bottom of the profile sidebar to change their placement or control their visibility go to <strong>Youzify Panel > Profile Settings > Profile Structure</strong>.', 'youzify' )
        )
    );

    $modal_args = array(
        'id'        => 'youzify-ads-form',
        'title'     => __( 'Create New Ad', 'youzify' ),
        'button_id' => 'youzify-add-ad'
    );

    // Get 'Create new ad' Form.
    youzify_panel_modal_form( $modal_args, 'youzify_create_new_AD_form' );

    // Get Exists Ads.
    youzify_get_ads();

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
            'desc'  => __( 'Choose how you want your ad to be loaded?', 'youzify' ),
            'id'    => 'youzify_ads_load_effect',
            'type'  => 'select'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}

/**
 * Create New AD Form.
 */
function youzify_create_new_AD_form() {

    // Get Data.
    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-ads-form'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Is Sponsored?', 'youzify' ),
            'desc'       => __( 'Display "Sponsored" title above the ad', 'youzify' ),
            'id'         => 'youzify_ad_is_sponsored',
            'type'       => 'checkbox',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Ad Name', 'youzify' ),
            'id'         => 'youzify_ad_title',
            'desc'       => __( "You'll use it in the profile structure", 'youzify' ),
            'type'       => 'text',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Ad type', 'youzify' ),
            'id'         => 'youzify_ad_type',
            'desc'       => __( 'Choose the ad type', 'youzify' ),
            'std'        => 'banner',
            'no_options' => true,
            'type'       => 'radio',
            'opts'       => array(
                'banner'  => __( 'Banner', 'youzify' ),
                'adsense' => __( 'Adsense Code', 'youzify' )
            ),
        )
    );

    //Banner Options
    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-adbanner-items'
        )
    );

        $Youzify_Settings->get_field(
            array(
                'title'      => __( 'Ad URL', 'youzify' ),
                'id'         => 'youzify_ad_url',
                'desc'       => __( 'Ad banner link URL', 'youzify' ),
                'type'       => 'text',
                'no_options' => true
            )
        );

         $Youzify_Settings->get_field(
            array(
                'title'      => __( 'Ad Banner', 'youzify' ),
                'id'         => 'youzify_ad_banner',
                'desc'       => __( 'Uplaod ad banner image', 'youzify' ),
                'type'       => 'upload',
                'no_options' => true
            )
        );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

    // Ad Code Options
    $Youzify_Settings->get_field(
        array(
            'type'  => 'openDiv',
            'class' => 'youzify-adcode-item'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title'      => __( 'Ad Code', 'youzify' ),
            'id'         => 'youzify_ad_code',
            'desc'       => __( 'Put your adsense code here', 'youzify' ),
            'type'       => 'textarea',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

    // Add Hidden Input
    $Youzify_Settings->get_field(
        array(
            'id'         => 'youzify_ads_form',
            'type'       => 'hidden',
            'class'      => 'youzify-keys-name',
            'std'        => 'youzify_ads',
            'no_options' => true
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeDiv' ) );

}

/**
 * Get Ads List
 */
function youzify_get_ads() {

    global $Youzify_Settings;

    // Get Ads Items
    $youzify_ads = youzify_option( 'youzify_ads' );

    ?>

    <script> var youzify_nextAD = <?php echo youzify_option( 'youzify_next_ad_nbr', 1 ); ?>; </script>

    <div class="youzify-custom-section">
        <div class="youzify-cs-head">
            <div class="youzify-cs-buttons">
                <button class="youzify-md-trigger youzify-ads-button" data-modal="youzify-ads-form">
                    <i class="fas fa-plus-circle"></i>
                    <?php _e( 'Add New Ad', 'youzify' ); ?>
                </button>
            </div>
        </div>
    </div>

    <ul id="youzify_ads" class="youzify-cs-content">

    <?php

        // Show No Ads Found .
        if ( empty( $youzify_ads ) ) {
            echo "<p class='youzify-no-content youzify-no-ads'>" . __( 'No ads found!', 'youzify' ) . "</p></ul>";
            return false;
        }

        foreach ( $youzify_ads as $ad => $data ) :

            // Get Widget Data.
            $url            = $data['url'];
            $code           = $data['code'];
            $type           = $data['type'];
            $title          = $data['title'];
            $banner         = $data['banner'];
            $is_sponsored   = $data['is_sponsored'];

            // Ad photo background.
            $banner_img = ( $type == 'banner' ) ? "style='background-image:url($banner);'" : null;
            $code_icon  = ( $type == 'adsense' ) ? 'youzify_show_icon' : 'youzify_hide_icon';

            // Get Field Name.
            $name = "youzify_ads[$ad]";

            ?>

            <!-- AD Item -->
            <li class="youzify-ad-item" data-ad-name="<?php echo $ad; ?>">
                <div class="youzify-ad-img <?php echo $code_icon; ?>" <?php echo $banner_img; ?>>
                    <i class="fas fa-code"></i>
                </div>
                <div class="youzify-ad-data">
                    <h2 class="youzify-ad-title"><?php echo $title; ?></h2>
                    <div class="youzify-ad-actions">
                        <a class="youzify-edit-item youzify-edit-ad"></a>
                        <a class="youzify-delete-item youzify-delete-ad"></a>
                    </div>
                </div>
                <!-- Data Inputs -->
                <input type="hidden" name="<?php echo $name; ?>[url]" value="<?php echo $url; ?>">
                <input type="hidden" name="<?php echo $name; ?>[code]" value="<?php echo $code; ?>">
                <input type="hidden" name="<?php echo $name; ?>[type]" value="<?php echo $type; ?>">
                <input type="hidden" name="<?php echo $name; ?>[title]" value="<?php echo $title; ?>">
                <input type="hidden" name="<?php echo $name; ?>[banner]" value="<?php echo $banner; ?>">
                <input type="hidden" name="<?php echo $name; ?>[is_sponsored]" value="<?php echo $is_sponsored; ?>">
            </li>

        <?php endforeach; ?>

    </ul>

    <?php
}