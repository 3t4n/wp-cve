<?php

/**
 * Disable Gravatars
 */
add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );

/**
 * Check Is Youzify Panel Page.
 */
function is_youzify_panel_page( $page_name ) {

    // Is Panel.
    $is_panel = isset( $_GET['page'] ) && $_GET['page'] == $page_name ? true : false;

    return apply_filters( 'is_youzify_panel_page', $is_panel, $page_name );
}

/**
 * Check Is Youzify Panel Page.
 */
function is_youzify_panel_tab( $tab_name ) {

    // Is Panel.
    $is_tab = isset( $_GET['tab'] ) && $_GET['tab'] == $tab_name ? true : false;

    return apply_filters( 'is_youzify_panel_tab', $is_tab, $tab_name );
}

/**
 * Admin Youzify Icon Css
 */
function youzify_admin_bar_icon_css() { ?>
    <style>
        #adminmenu .toplevel_page_youzify-panel img {
            padding-top: 3px !important;
        }
    </style>
    <?php
}

add_action( 'admin_head', 'youzify_admin_bar_icon_css' );

/**
 * Check if page is an admin page  tab
 */
function youzify_is_panel_tab( $page_name, $tab_name ) {

    if ( is_admin() && isset( $_GET['page'] ) && isset( $_GET['tab'] ) && $_GET['page'] == $page_name && $_GET['tab'] == $tab_name ) {
        return true;
    }

    return false;
}


/**
 * Get Panel Profile Fields.
 */
function youzify_get_user_tags_xprofile_fields() {

    // Init Panel Fields.
    $xprofile_fields = array();

    // Get xprofile Fields.
    $fields = youzify_get_bp_profile_fields();

    foreach ( $fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $xprofile_fields[ $field_id ] = $field['name'];

    }

    return $xprofile_fields;
}

/**
 * Get Activity Posts Types
 */
function youzify_activity_post_types() {

    // Get Post Types Visibility
    $post_types = array(
        'activity_status'       => __( 'Status', 'youzify' ),
        'activity_photo'        => __( 'Photo', 'youzify' ),
        'activity_slideshow'    => __( 'Slideshow', 'youzify' ),
        'activity_link'         => __( 'Link', 'youzify' ),
        'activity_quote'        => __( 'Quote', 'youzify' ),
        'activity_giphy'        => __( 'GIF', 'youzify' ),
        'activity_video'        => __( 'Video', 'youzify' ),
        'activity_audio'        => __( 'Audio', 'youzify' ),
        'activity_file'         => __( 'File', 'youzify' ),
        'activity_poll'         => __( 'Poll', 'youzify' ),
        'activity_share'        => __( 'Share', 'youzify' ),
        'new_cover'             => __( 'New Cover', 'youzify' ),
        'new_avatar'            => __( 'New Avatar', 'youzify' ),
        'new_member'            => __( 'New Member', 'youzify' ),
        'friendship_created'    => __( 'Friendship Created', 'youzify' ),
        'friendship_accepted'   => __( 'Friendship Accepted', 'youzify' ),
        'created_group'         => __( 'Group Created', 'youzify' ),
        'joined_group'          => __( 'Group Joined', 'youzify' ),
        'new_blog_post'         => __( 'New Blog Post', 'youzify' ),
        'new_blog_comment'      => __( 'New Blog Comment', 'youzify' ),
        // 'activity_comment'      => __( 'Comment Post', 'youzify' ),
        'updated_profile'       => __( 'Updates Profile', 'youzify' )
    );

    if ( class_exists( 'WooCommerce' ) ) {
        $post_types['new_wc_product'] = __( 'New Product', 'youzify' );
        $post_types['new_wc_purchase'] = __( 'New Purchase', 'youzify' );
    }

    if ( class_exists( 'bbPress' ) ) {
        $post_types['bbp_topic_create'] = __( 'Forum Topic', 'youzify' );
        $post_types['bbp_reply_create'] = __( 'Forum Reply', 'youzify' );
    }
    
    return apply_filters( 'youzify_activity_post_types', $post_types );
}

/**
 * Admin Modal Form
 */
function youzify_panel_modal_form( $args, $modal_function ) {

    $button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'Save', 'youzify' );

    ?>

    <div class="youzify-md-modal youzify-md-effect-1" id="<?php echo $args['id'] ;?>">
        <h3 class="youzify-md-title" data-title="<?php echo $args['title']; ?>"><?php echo $args['title']; ?><i class="fas fa-times youzify-md-close-icon"></i></h3>
        <div class="youzify-md-content"><?php $modal_function(); ?></div>
        <div class="youzify-md-actions">
            <button id="<?php echo $args['button_id']; ?>" data-add="<?php echo $args['button_id']; ?>" class="youzify-md-button youzify-md-save"><?php echo $button_title ?></button>
            <button class="youzify-md-button youzify-md-close"><?php _e( 'Close', 'youzify' ); ?></button>
        </div>
    </div>

    <?php
}

/**
 * Exclude Youzify Media from Wordpress Media Library.
 */
add_filter( 'parse_query', 'youzify_exclude_youzify_media_from_media_library' );

function youzify_exclude_youzify_media_from_media_library( $wp_query ) {

    if ( strpos( $_SERVER[ 'REQUEST_URI' ], '/wp-admin/upload.php' ) !== false ) {
        $term = get_term_by( 'slug', 'youzify_media', 'category' );
        if ( isset( $term->term_id ) ) {
            $wp_query->set( 'category__not_in', array( $term->term_id ) );
        }
    }

}

/**
 * Check if feature is available
 */
function youzify_is_feature_available() {
    return apply_filters( 'youzify_is_feature_available', false );
}

/**
 * Get Features Tag.
 */
function youzify_get_premium_tag() {
    return '<div class="youzify-premium-tag"><i class="fas fa-gem"></i>' . __( 'Premium', 'youzify' ) . '</div>';
}

/**
 * Get User Statistics Options.
 */
function youzify_get_user_statistics_options() {

    $statistics = array(
        'posts'     => __( 'Posts', 'youzify' ),
        'comments'  => __( 'Comments', 'youzify' ),
        'views'     => __( 'Views', 'youzify' ),
        'ratings'   => __( 'Ratings', 'youzify' ),
        'followers' => __( 'Followers', 'youzify' ),
        'following' => __( 'Following', 'youzify' ),
        'points'    => __( 'Points', 'youzify' )
    );

    return apply_filters( 'youzify_get_user_statistics_options', $statistics );

}

/**
 * Special Offer
 **/
// add_action( 'youzify_admin_before_form', 'youzify_special_offer' );

function youzify_special_offer() {

    $id = 'youzify_7day_aap_offer_4';

    if ( isset( $_GET['youzify-dismiss-offer-notice'] ) ) {
        youzify_update_option( $_GET['youzify-dismiss-offer-notice'], 1 );
    }

    // if ( strtotime( '2021/09/19') < strtotime( 'now' ) || youzify_option( $id ) ) {
    //     // return;
    // }

    $price = array(
        'currency' => '$',
        'per' => 'year',
    );

    $boxes = array(
        'first' => array(
            'theme' => 'black',
            'title' => 'All-Access Pass <span class="yzp-highlught">1 Year</span>',
            'price' => 199,
            'normally' => 299,
            'save' => array(
                'price' => '99',
                'percent' => 33
            ),
            'benefits' => array(
                '1 Site License',
                'Access all Youzify Add-Ons',
                'Free Access to all Upcoming Addons',
                'Unlimited Support and Updates',
            ),
            'bonus' => array(
                '30% OFF on Renewals',
                'Access +700 Youzify Snippets',
            ),
            'link' => 'https://youzify.com/checkout?edd_action=add_to_cart&download_id=80728&edd_options[price_id]=7&utm_campaign=youzify-' . YOUZIFY_VERSION . '-offer&utm_medium=offer&utm_source=client-site&utm_content=plan-1year',
            'cta' => 'Choose Plan'
        ),
        'second' => array(
            'theme' => 'black',
            'title' => 'All-Access Pass <span class="yzp-highlught">2 Years</span>',
            'price' => 149,
            'normally' => 299,
            'save' => array(
                'price' => 300,
                'percent' => 50
            ),
            'benefits' => array(
                '1 Site License',
                'Access all Youzify Add-Ons',
                'Free Access to all Upcoming Addons',
                'Unlimited Support and Updates',
            ),
            'bonus' => array(
                '30% OFF on Renewals',
                'Access +700 Youzify Snippets',
            ),
            'link' => 'https://youzify.com/checkout?edd_action=add_to_cart&download_id=80728&edd_options[price_id]=5&utm_campaign=youzify-' . YOUZIFY_VERSION . '-offer&utm_medium=offer&utm_source=client-site&utm_content=plan-2years',
            'cta' => 'Choose Plan'
        ),
        'third' => array(
            'theme' => 'white',
            'tag' => 'Best Value',
            'title' => 'All-Access Pass <span class="yzp-highlught">5 Years</span>',
            'price' => 99,
            'normally' => 299,
            'save' => array(
                'price' => '1000',
                'percent' => 77
            ),
            'benefits' => array(
                'Access all Youzify Add-Ons',
                'Free Access to all Upcoming Addons',
                'Unlimited Support and Updates',
            ),
            'bonus' => array(
                '5 Sites License ( Value: $399/Year )',
                '50% OFF on Renewals',
                'Access +700 Youzify Snippets ',
                '1-Year Youzify Pro FREE Support',
            ),
            'link' => 'https://youzify.com/checkout?edd_action=add_to_cart&download_id=80728&edd_options[price_id]=6&utm_campaign=youzify-' . YOUZIFY_VERSION . '-offer&utm_medium=offer&utm_source=client-site&utm_content=plan-5years',
            'cta' => 'Choose Plan'
        )
    );



    ?>
    <style type="text/css">

        .yzp-offer {
            margin: 50px 35px;
        }

        .yzp-table {
            gap: 35px;
            width: 100%;
            display: inline-flex;
        }

        .yzp-box {
            position: relative;
            display: flex;
            padding: 35px;
            width: 33.33%;
            border-radius: 8px;
            flex-direction: column;
        }

        span.yzp-currency {
            /*color: #eee;*/
            font-size: 26px;
            margin-top: -5px;
            vertical-align: top;
            display: inline-block;
        }

        .yzp-black-theme span.yzp-currency {
            color: #000;
        }

        .yzp-white-theme span.yzp-currency {
            color: #fff;
        }

        .yzp-price {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
        }

        span.yzp-separator {
            font-size: 18px;
            color: #eee;
        }

        .yzp-black-theme span.yzp-separator {
            color: #000;
        }

        .yzp-white-theme span.yzp-separator {
            color: #fff;
        }

        .yzp-black-theme span.yzp-price-label {
            color: #000;
            font-weight: 600;
        }

        .yzp-white-theme span.yzp-price-label {
            color: #eee;
        }

        span.yzp-per {
            font-size: 12px;
            text-transform: uppercase;
            font-weight: 600;
        }

        span.yzp-amount {
            font-size: 50px;
            font-weight: 600;
        }


        a.yzp-cta-button {
            background: #fff;
            display: block;
            text-align: center;
            height: 55px;
            border-radius: 5px;
            line-height: 55px;
            text-decoration: none;
            font-size: 16px;
            font-weight: 600;
            color: #555;
        }

        a.yzp-cta-button i {
            margin-left: 12px;
        }

        .yzp-box-pricing {
            display: flex;
            align-items: center;
        }

        .yzp-box-head {
            margin-bottom: 25px;
        }

        .yzp-normally {
            margin-left: auto;
        }

        .yzp-normally span.yzp-amount {
            font-weight: 400;
            text-decoration: line-through;
        }

        .yzp-price-label {
            display: block;
            margin-bottom: 12px;
        }

        .yzp-box-title {
            display: block;
            font-size: 24px;
            font-weight: 600;
            text-align: center;
            padding: 25px 0;
            line-height: 28px;
            font-family: Open sans,sans-serif;
        }

        .yzp-save {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 18px;
            padding: 5px 15px;
            border-radius: 50px;
            display: block;
            text-align: center;
            margin-top: 15px;
        }
        span.yzp-save-price {
            display: inline-block;
            background: #fff952;
            color: #000;
            padding: 14px 13px;
            border-radius: 50px;
            margin-left: 5px;
            font-size: 20px;
            line-height: 0px;
        }

        span.yzp-save-percent {
            /*color: #fff952;*/
            font-size: 30px;
        }

        .yzp-box-bonus {
            font-size: 14px;
            line-height: 24px;
            margin-bottom: 12px;
            border-radius: 5px;
            padding: 8px 15px;
        }


        .yzp-white-theme .yzp-box-bonus {
            /*background: rgb(255 255 255 / 10%);*/
            background: #fff;
            color: #000;
            font-weight: 600;
        }

        .yzp-black-theme .yzp-box-bonus {
            color: #000;
            font-weight: 600;
            background: rgb(255 255 255 / 10%);
        }

        .yzp-box-benefit {
            font-size: 14px;
            line-height: 18px;
            margin-bottom: 15px;
        }

        .yzp-box-benefit i {
            margin-right: 10px;
        }

        span.yzp-bonus-tag {
            color: #000;
            text-transform: uppercase;
            background: yellow;
            font-size: 10px;
            font-weight: 600;
            padding: 4px 8px;
            border-radius: 4px;
            margin-right: 13px;
        }

        .yzp-bonuses-title,
        .yzp-benefits-title {
            font-weight: 600;
            /*text-align: center;*/
            margin-bottom: 20px;
        }

        .yzp-box-benefits {
            margin-bottom: 25px;
        }

        .yzp-black-theme .yzp-bonuses-title {
            color: #000;
        }

        .yzp-white-theme .yzp-bonuses-title {
            color: #fff952;
        }

        .yzp-box-content {
            padding: 35px 0;
        }

        .yzp-box-footer {
            margin-top: auto;
        }

        .yzp-aap-content {
            background: #fff;
            padding: 35px;
            border-radius: 8px;
            margin-bottom: 35px;
        }

        .yzp-first-box {
            background: #fff;
        }

        .yzp-second-box {
            background: #f3eb90;
        }

        .yzp-third-box {
            background: #000;
        }

        .yzp-first-box a.yzp-cta-button {
            background: #eee;
            color: #000;
        }

        .yzp-second-box a.yzp-cta-button {
            background: #fff;
            color: #000;
        }

        .yzp-third-box a.yzp-cta-button {
            background: #ff0;
            color: #000;
        }

        .yzp-first-box .yzp-box-bonus {
            background: #f0f0f1;
        }

        .yzp-second-box .yzp-box-bonus  {
            background: #0000001a;
        }

        .yzp-white-theme {
            color: #fff;
        }

        .yzp-black-theme {
            color: #000;
        }

        .yzp-aap-title {
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 25px;
        }

        .yzp-aap-title i {
            margin-right: 8px;
        }

        .yzp-aap-addons {
            display: flex;
            flex-wrap: wrap;
        }

        .yzp-addon {
            width: 32%;
            margin-bottom: 18px;
            display: flex;
            font-weight: 600;
            margin-right: 15px;
            align-items: center;
                flex-grow: 1;
        }

        .yzp-addon i {
            margin-right: 6px;
        }

        .yzp-aap-value {
            font-weight: 600;
            color: rgb(228, 59, 44);
            margin-left: 12px;
        }

        .yzp-total-value {
            width: 300px;
            display: block;
            margin: 25px auto 0;
            background: #f44336;
            height: 65px;
            line-height: 65px;
            text-align: center;
            color: #fff;
            font-size: 24px;
            font-weight: 600;
            border-radius: 5px;
        }

        .yzp-aap-note {
            display: block;
            color: #898989;
            font-size: 13px;
            line-height: 22px;
            text-align: center;
            margin-top: 20px;
        }

        .yzp-renewal-notice {
            display: block;
            font-size: 13px;
            line-height: 22px;
            text-align: center;
            margin-top: 20px;
        }

        .yzp-black-theme .yzp-renewal-notice {
            color: #000;
        }

        .yzp-white-theme .yzp-renewal-notice {
            color: #eee;
        }

        .yzp-black-theme span.yzp-save-percent {
            color: #000;
        }

        .yzp-white-theme span.yzp-save-percent {
            color: #fff952;
        }

        .yzp-white-theme .yzp-box-bonus:last-of-type {
            margin-bottom: 0;
        }

        .yzp-box-tag {
                text-align: center;
    background: yellow;
    color: #000;
    margin: auto;
    padding: 9px 15px;
    border-radius: 50px;
    margin-bottom: 35px;
    font-weight: 600;
    position: absolute;
    top: -19px;
    left: 0;
    right: 0;
    width: 88px;
    font-size: 16px;
        }

    </style>

    <?php

        $addons = array(
            array( 'title' => 'BuddyPress Membership Restrictions', 'single_site' => 99 ),
            array( 'title' => 'BuddyPress Block Members', 'single_site' => 28 ),
            array( 'title' => 'BuddyPress Moderation', 'single_site' => 49 ),
            array( 'title' => 'BuddyPress Profile Completeness', 'single_site' => 39 ),
            array( 'title' => 'BuddyPress Edit Activity', 'single_site' => 39 ),
            array( 'title' => 'BuddyPress Activity Reactions', 'single_site' => 28 ),
            array( 'title' => 'BuddyPress Social Share', 'single_site' => 24 ),
            array( 'title' => 'BuddyPress MyCRED Integration', 'single_site' => 18 ),
            array( 'title' => 'BuddyPress Amazon S3', 'single_site' => 49 ),
            array( 'title' => 'BuddyPress Advanced Members Search', 'single_site' => 49 ),
        );

        $upcoming = array(
            array( 'title' => 'BP Frontend Submission ( Next Week )', 'single_site' => 99 ),
            array( 'title' => 'BuddyPress  Albums System ( September 2021 )', 'single_site' => 49 ),
            array( 'title' => 'BuddyPress Live Chat ( November 2021 )', 'single_site' => 99 )
        );


        $planned = array(
            array( 'title' => 'BuddyPress Stories', 'single_site' => 149 ),
            array( 'title' => 'BuddyPress Who Viewed My Profile?', 'single_site' => 49 ),
            array( 'title' => 'BuddyPress Resume Manager', 'single_site' => 49 )
        );


        $total = 0;

    ?>

    <style type="text/css">

    .yzp-contact-us {
        font-size: 16px;
        margin-top: 35px;
        display: block;
    }

    .yzp-contact-us a {
        font-weight: 600;
    }

    .yzp-contact-us {
        display: flex;
        align-items: center;
    }

    .yzp-contact-us .yzp-hide-offer {
        color: #555;
        cursor: pointer;
        font-size: 13px;
        font-weight: 600;
        margin-left: auto;
        text-decoration: none;
        vertical-align: middle;
    }

    .yzp-contact-us .yzp-hide-offer i {
        margin-right: 7px;
        width: 25px;
        height: 25px;
        line-height: 25px;
        background: #ff0000;
        text-align: center;
        color: #fff;
        border-radius: 100%;
    }

    </style>

    <div class="yzp-offer">

        <?php youzify_offer_banner(); ?>

        <div class="yzp-aap-content">

            <div class="yzp-aap-title"><i class="fas fa-gifts"></i>What's Included in Youzify All-Access Pass?</div>

            <div class="yzp-aap-addons">
                <?php foreach ( $addons as $addon ) : $total = $total + $addon['single_site']; ?>
                    <div class="yzp-addon"><i class="far fa-check-circle"></i><?php echo $addon['title'] ?> <span class="yzp-aap-value">( Price: <span class="yzp-aap-price">$<?php echo $addon['single_site']; ?>.00</span> )</span></div>
                <?php endforeach; ?>
            </div>

            <div class="yzp-aap-title" style="margin-top: 15px;"><i class="fas fa-hourglass-half"></i> Almost Completed Add-Ons</div>
            <div class="yzp-aap-addons">
                <?php foreach ( $upcoming as $addon ) : $total = $total + $addon['single_site']; ?>
                    <div class="yzp-addon"><i class="far fa-check-circle"></i><?php echo $addon['title'] ?> <span class="yzp-aap-value">( Price: <span class="yzp-aap-price">$<?php echo $addon['single_site']; ?>.00</span> )</span></div>
                <?php endforeach; ?>
            </div>

            <div class="yzp-aap-title" style="margin-top: 15px;"><i class="fas fa-clock"></i>Confirmed Upcoming Add-Ons</div>
            <div class="yzp-aap-addons">
                <?php foreach ( $planned as $addon ) : $total = $total + $addon['single_site']; ?>
                    <div class="yzp-addon"><i class="far fa-check-circle"></i><?php echo $addon['title'] ?> <span class="yzp-aap-value">( Price: <span class="yzp-aap-price">$<?php echo $addon['single_site']; ?>.00</span> )</span></div>
                <?php endforeach; ?>
            </div>

            <div class="yzp-total-value">Total Value: $<?php echo $total; ?>.00</div>

            <span class="yzp-aap-note">*Dozen of addons will be added each year.</span>
        </div>
        <div class="yzp-table">

            <?php foreach ( $boxes as $key => $box ) : ?>

            <div class="yzp-box yzp-<?php echo $box['theme'] ?>-theme yzp-<?php echo $key; ?>-box">
                <?php if( isset( $box['tag'] ) ) : ?><span class="yzp-box-tag"><?php echo $box['tag']; ?></span><?php endif; ?>
                <div class="yzp-box-head">
                    <div class="yzp-box-title"><?php echo $box['title']; ?></div>
                    <span class="yzp-save">Save <span class="yzp-save-percent"><?php echo $box['save']['percent']; ?>%</span><span class="yzp-save-price"><?php echo $box['save']['price'] .  $price['currency']; ?></span></span>
                </div>

                <div class="yzp-box-pricing">

                    <div class="yzp-price">
                        <span class="yzp-currency"><?php echo $price['currency']; ?></span>
                        <span class="yzp-amount"><?php echo $box['price']; ?></span>
                        <span class="yzp-separator">/</span>
                        <span class="yzp-per"><?php echo $price['per']; ?></span>
                    </div>

                    <div class="yzp-normally">
                        <span class="yzp-price-label">Normally</span>
                        <span class="yzp-currency"><?php echo $price['currency']; ?></span>
                        <span class="yzp-amount"><?php echo $box['normally']; ?></span>
                        <span class="yzp-separator">/</span>
                        <span class="yzp-per"><?php echo $price['per']; ?></span>
                    </div>

                </div>

                <div class="yzp-box-content">
                    <?php if ( isset( $box['benefits'] ) ) : ?>
                    <div class="yzp-benefits-title">What's Included?</div>
                    <div class="yzp-box-benefits">
                        <?php foreach ( $box['benefits'] as $benefit ) : ?>
                        <div class="yzp-box-benefit"><i class="fas fa-bullseye"></i><?php echo $benefit; ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php if ( isset( $box['bonus'] ) ) : ?>
                    <div class="yzp-bonuses-title">Plus you will Get These FREE Bonuses!</div>
                    <div class="yzp-box-bonuses">
                        <?php foreach ( $box['bonus'] as $i => $bonus ) : ?>
                        <div class="yzp-box-bonus"><span class="yzp-bonus-tag">Bonus #<?php echo $i+1; ?></span><?php echo $bonus; ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                </div>

                <div class="yzp-box-footer">
                    <a target="_blank"class="yzp-cta-button" href="<?php echo $box['link']; ?>"><?php echo $box['cta']; ?><i class="far fa-arrow-alt-circle-right"></i></a>
                </div>

                <span class="yzp-renewal-notice">*Our previous All-Access Pass clients can also benefit from this offer by renewing for more years using this offer.</span>
            </div>

            <?php endforeach; ?>

        </div>

        <div class="yzp-contact-us">
            <div class="yzp-hide-contact"><strong>Need help?</strong> <a href="https://youzify.com/contact-us">Contact us</a> or reach us at <strong>admin@kainelabs.com</strong></div>
            <a href="<?php echo esc_url( add_query_arg( 'youzify-dismiss-offer-notice', $id, youzify_get_current_page_url() ) ); ?>" class="yzp-hide-offer"><i class="fas fa-times"></i>Hide Offer</a>
        </div>
    </div>

    <?php
}

/**
 * Offer Banneer
 */
add_action( 'admin_notices', 'youzify_offer_banner' );
function youzify_offer_banner( $show_button = true ) {

    if (  ! youzify_is_feature_available() ) {
        return;
    }

    $id = 'youzify_aap_october_2021_offer';

    if ( isset( $_GET['youzify-dismiss-offer-notice'] ) ) {
        youzify_update_option( $_GET['youzify-dismiss-offer-notice'], 1 );
    }

    if ( get_option( $id ) ) {
        return;
    }

    youzify_get_offer_banner_css();

    ?>


    <div class="yzp-heading" style="background-image: linear-gradient(
130deg, #3F51B5 0%, #663BB7 100%);color:#fff;">
        <a class="yzp-cancel-offer"  href="<?php echo esc_url( add_query_arg( 'youzify-dismiss-offer-notice', $id, youzify_get_current_page_url() ) ); ?>"><span style="color:#fff;" class="dashicons dashicons-no-alt"></span></a>
        <div class="yzp-offer-head">
            <div style="margin-bottom: 25px;">
            <span class="position-relative text-nowrap" style="
    position: relative;
        padding: 12px 10px 6px;
    margin-bottom: 25px;
    white-space: nowrap!important;
">
    <img class="position-absolute" style="left: 50%;top: 50%;transform: translate(-50%, -50%);width: 110%;position: absolute;height:100%;" alt="prop image" src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/yellow-highlight.svg'; ?>"><span class="position-relative text-nowrap" style="background-color: transparent;letter-spacing: 0px;position: relative;color: #fff;"><span class="position-relative" style="padding:0 15px;color: #000;font-size: 25px;    font-weight: 600;
    ">A Trully Limited Time Offer</span></span><span style="background-color: transparent; letter-spacing: 0px; white-space: pre-wrap;"></span></span></div>

            <!-- <div class="yzp-head-tag">A TRULLY LIMITED TIME OFFER</div> -->
            <div class="yzp-head-title" style="color: #fff952;">Due to the increased value and number of our add-ons, Youzify - All-Access Pass Price Increased!</div>
            <div class="yzp-head-title">Your last chance to save BIG on <span class="position-relative text-nowrap" style="
    position: relative;
"><img class="position-absolute" style="left: 50%;top: 66%;transform: translate(-50%, -50%);width: 100%;height:100%;position: absolute;" alt="" src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/yellow-underline.svg'; ?>"><span class="position-relative">Youzify - All-Access Pass</span></span> and get it for a very cheap price.</div>
            <?php //if ( $show_button ) : ?>
            <div class="yzp-offer-buttons">
                <a target="_blank" class="yzp-view-offer-details" href="https://youzify.com/youzify-all-access-pass-offer/?utm_campaign=youzify-all-access-pass-2021-offer&utm_medium=top-bar&utm_source=client-site&utm_content=act-now">👉 View Offer Details<span class="dashicons dashicons-arrow-right-alt"></span></a>

                <?php //endif; ?>
            </div>
            <div class="yzp-head-date" style="opacity: 0.8;font-weight: 400;">*As this offer represents a huge loss for us in the long term, we reserve the right to take it down at any time without any prior notice.</div>
        </div>
        <!-- <div id="kl-countdown" class="kl-countdown">
            <ul>
              <li><span class="days">0</span>Days</li>
              <li><span class="hours">0</span>Hours</li>
              <li><span class="minutes">0</span>Minutes</li>
              <li><span class="seconds blinking">0</span>Seconds</li>
            </ul>
      </div> -->
    </div>

     <script type="text/javascript">
      /*! yscountdown v1.0.0 | Yusuf SEZER <yusufsezer@mail.com> | MIT License | https://github.com/yusufsefasezer/ysCountDown.js */
    !function(t,o){"function"==typeof define&&define.amd?define([],function(){return o(t)}):"object"==typeof exports?module.exports=o(t):t.ysCountDown=o(t)}("undefined"!=typeof global?global:"undefined"!=typeof window?window:this,function(u){"use strict";return function(t,o){var n={},r=null,a=null,e=null,l=null,i=!1;n.init=function(t,o){if(!("addEventListener"in u))throw"ysCountDown: This browser does not support the required JavaScript methods.";if(n.destroy(),r="string"==typeof t?new Date(t):t,!((e=r)instanceof Date)||isNaN(e))throw new TypeError("ysCountDown: Please enter a valid date.");var e;if("function"!=typeof o)throw new TypeError("ysCountDown: Please enter a callback function.");a=o,s()},n.destroy=function(){a=r=null,f(),l=null,i=!1};var s=function(){e||(e=setInterval(function(){var t,o;t=new Date,(o=Math.ceil((r.getTime()-t.getTime())/1e3))<=0&&(i=!0,f()),l={seconds:o%60,minutes:Math.floor(o/60)%60,hours:Math.floor(o/60/60)%24,days:Math.floor(o/60/60/24)%7,daysToWeek:Math.floor(o/60/60/24)%7,daysToMonth:Math.floor(o/60/60/24%30.4368),weeks:Math.floor(o/60/60/24/7),weeksToMonth:Math.floor(o/60/60/24/7)%4,months:Math.floor(o/60/60/24/30.4368),monthsToYear:Math.floor(o/60/60/24/30.4368)%12,years:Math.abs(r.getFullYear()-t.getFullYear()),totalDays:Math.floor(o/60/60/24),totalHours:Math.floor(o/60/60),totalMinutes:Math.floor(o/60),totalSeconds:o},a(l,i)},100))},f=function(){e&&(clearInterval(e),e=null)};return n.init(t,o),n}});


    ( function( $ ) {

    $( document ).ready( function() {

   // var endDate = "< ?php echo date('Y-m-d', strtotime(' +1 day')) ?>";
    var endDate = "2021/09/19";

    var myCountDown = new ysCountDown(endDate, function (remaining, finished) {

      if ( finished ) {
         $( '.kl-countdown' ).text( 'Offer Expired' );
      }

      $( '.days' ).text( remaining.totalDays );
      $( '.hours' ).text( remaining.hours );
      $( '.minutes' ).text( remaining.minutes );
      $( '.seconds' ).text( remaining.seconds );

    });

    });

    })( jQuery );
  </script>
    <?php

}


/**
 * Offer Banneer
 */
add_action( 'admin_notices', 'youzify_pro_version_banner', 0 );
function youzify_pro_version_banner( $show_button = true ) {

    if (  youzify_is_feature_available() ) {
        return;
    }

    $id = 'youzify_pro_version_october_2022_offer';
    // $id = 'youzify_pro_version_october_2021_offer1';

    if ( isset( $_GET['youzify-dismiss-offer-notice'] ) ) {
        youzify_update_option( $_GET['youzify-dismiss-offer-notice'], 1 );
    }

    $last_day_of_month = strtotime( "last day of" );
    $now_time = strtotime( 'now' );

    if ( $last_day_of_month < $now_time || youzify_option( $id ) ) {
        return;
    }

    youzify_get_offer_banner_css();

    // $future = strtotime('31 December 2021'); //Future date.
    $timeleft = $last_day_of_month-$now_time;
    $daysleft = round((($timeleft/24)/60)/60);
    $last_date = date( "d F Y", $last_day_of_month );
    ?>

    <div class="yzp-heading" style="background-image:linear-gradient(
    to right, #403fb5 0%, #ff00d1 100%);color:#fff;">
    <a class="yzp-cancel-offer"  href="<?php echo esc_url( add_query_arg( 'youzify-dismiss-offer-notice', $id, youzify_get_current_page_url() ) ); ?>"><span style="color:#fff;" class="dashicons dashicons-no-alt"></span></a>
    <div class="yzp-offer-head">
    <div style="margin-bottom: 25px;">
    <span class="position-relative text-nowrap" style="
    position: relative;
    padding: 12px 10px 6px;
    margin-bottom: 25px;
    white-space: nowrap!important;
    ">
    <img class="position-absolute" style="left: 50%;top: 50%;transform: translate(-50%, -50%);width: 110%;position: absolute;height:100%;" alt="prop image" src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/yellow-highlight.svg'; ?>"><span class="position-relative text-nowrap" style="background-color: transparent;letter-spacing: 0px;position: relative;color: #fff;"><span class="position-relative" style="padding:0 15px;color: #000;font-size: 25px;    font-weight: 600;
    ">ATTENTION: [<span style="color:#ff1e00;"><?php echo sprintf( _n( '%d Day Left', '%d Days Left', $daysleft, 'youzify' ), $daysleft ); ?></span>] Next Youzify Pro Price Increase is by <?php echo $last_date; ?></span></span><span style="background-color: transparent; letter-spacing: 0px; white-space: pre-wrap;"></span></span></div>

    <!-- <div class="yzp-head-tag">A TRULLY LIMITED TIME OFFER</div> -->
    <div class="yzp-head-title" style="color: #fff952;">Due to the increased value in our premium version the price will be increased by <?php echo $last_date; ?>!</div>
    <div class="yzp-head-title">Your last chance to secure <span class="position-relative text-nowrap" style="position: relative;"><img class="position-absolute" style="left: 50%;top: 66%;transform: translate(-50%, -50%);width: 100%;height:100%;position: absolute;font-weight: 600;" alt="" src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/yellow-underline.svg'; ?>"><span class="position-relative">Youzify PRO for only $62</span></span>. It's only <strong>one-time</strong> payment for <strong>lifetime updates</strong>.</div>

    <a target="_blank" style="margin: 5px 0 35px;" class="yzp-view-offer-details" href="https://1.envato.market/Rqjj9">👉 GO PRO NOW<span class="dashicons dashicons-arrow-right-alt"></span></a>
    <div class="yzo-subhead">What's included in Youzify Pro Version?</div>
    <div class="yzo-features">
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>14 Header Styles</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>03 Content Layouts ( 3 Columns, Right Sidebar )</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Hashtags, Bookmarks, Share Posts, Polls.</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Tag Friends, Sticky Posts, Comments GIF's.</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Comments, Messages Attachments.</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Real-time Notifications.</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Posts Mood & Activities.</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Posts Privacy: Public, Only Me, Friend...</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Ajax Login, Login Form Popup</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Friends & Groups Suggestions Widgets</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Unlimited Tabs, Widgets, and Links.</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Activity Stream Shortcode</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Members Directory Shortcode</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Groups Directory Shortcode</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Hashtags List Shortcode</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Community Hashtags Cloud Shortcode</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Unlock Hundreds of Youzify Restricted Options</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>And Much More and More and More...</div>
    </div>

    <div class="yzo-subhead" style="color:#fff952; margin-top: 25px;">Plus you will get these FREE Bonuses!</div>
    <div class="yzo-features yzo-yellow-icons">
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>Lifetime Updates</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>6 Month FREE World-Class Support</div>
        <div class="yzo-feature"><span class="dashicons dashicons-saved"></span>All Upcoming Pro Features for FREE</div>
    </div>

    <a target="_blank" style="margin: 15px 0 10px;" class="yzp-view-offer-details" href="https://1.envato.market/Rqjj9">👉 GO PRO NOW<span class="dashicons dashicons-arrow-right-alt"></span></a>

    <div class="yzo-subhead" style="color:#fff952; margin-top: 25px;">Look! WE ARE LIARS. Don't take our word for it. 😂</div>
    <div class="yzo-subhead" >Check +600 reviews with an overall 5-Star rating from real customers just like you at CodeCanyon.</div>
    <a target="_blank" class="yzp-view-offer-details" href="https://1.envato.market/WDd99M" style="background: #fff952;margin-top: 0;">👉 View +600 Reviews at CodeCanyon<span class="dashicons dashicons-arrow-right-alt"></span></a>

    <div class="yzo-subhead" style="margin-top: 25px;font-weight: 400;font-size: 21px;line-height: 34px;"><strong>Fun Fact:</strong> If you hired a developer to develop a custom plugin like Youzify it will cost you a 6 figures number (+$100.000 ) with at least 2 years to have the first version ready and <strong>you still won’t get the same clean optimized code, functionalities, performance, and design and continuous progress and support.</strong></div>

    <div class="yzo-letter" style=" background: #fff952; padding: 35px;  border-left: 10px solid #fff952;border-radius: 8px; ">
        <div style=" font-size: 20px; margin-bottom: 35px; font-weight: 600; color: #000000; display: block;">Would you believe us if we told you that we have almost no benefit from making you buying our PRO version?</div>

        <p>This is not something everyone will have the courage to share with you...</p>
        <p>We only earn a profit of $10-$12 after Envato fees and taxes per each sale...</p>
        <p>And that <strong>won't even cover the support you will receive for free in the first 6 months...</strong></p>
        <p>We want you to take advantage of our PRO features so <strong>you can start growing your business and make more money</strong> to be able to afford our addons in the future...</p>
        <p>Because they bring us a bit better profit which makes us able to keep the plugin alive and of course <strong>the value we provide in each of our addons is no joke.</strong></p>

        <p>As we say always, <strong>go to try everything else in the market</strong> and then try our products too and you will see ( feel ) the difference.<p>

        <p>In short terms, we don't want you to purchase our plugin <strong>we want you as a customer for LIFE.</strong></p>

        <p>We dedicated lives to this project and our main goal is to <strong>build HUSTLE-FREE plugins where our clients will focus on their business instead of wasting their time dealing with bugs.</strong></p>
        <a target="_blank" class="yzp-view-offer-details" href="https://1.envato.market/Rqjj9">👉 GO PRO NOW<span class="dashicons dashicons-arrow-right-alt"></span></a>
    </div>

    <!-- <div class="yzp-offer-buttons"> </div> -->
<!--
    <div class="yzp-head-date" style="opacity: 0.8;font-weight: 400;">*As this offer represents a huge loss for us in the long term, we reserve the right to take it down at any time without any prior notice.</div> -->
    </div>
    <!-- <div id="kl-countdown" class="kl-countdown">
    <ul>
    <li><span class="days">0</span>Days</li>
    <li><span class="hours">0</span>Hours</li>
    <li><span class="minutes">0</span>Minutes</li>
    <li><span class="seconds blinking">0</span>Seconds</li>
    </ul>
    </div> -->
    </div>

    <?php
}

function youzify_get_offer_banner_css() {

    ?>

   <style type="text/css">
    .yzo-letter p {
        color: #000;
        font-size: 17px;
        line-height: 30px;
    }

    .yzo-subhead {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 25px;
    }

    .yzp-heading {
        display: flex;
        padding: 35px;
        position: relative;
        margin: 10px 20px  35px 0;
        border-radius: 8px;
        background: #ffeb3b;
        align-items: center;
    }

    .yzo-feature {
        display: inline-flex;
        align-items: center;
        font-size: 14px;
        margin-bottom: 15px;
        margin-right: 15px;
        min-width: 30.33%;
    }

    .yzo-feature span {
        width: 25px;
        height: 25px;
        line-height: 25px;
        background: #51ff00;
        border-radius: 100%;
        margin-right: 12px;
    }

    .yzo-yellow-icons .yzo-feature span {
        background:#fff952;
        color: #000;
    }

    .kl-countdown {
        margin-left: auto;
        padding: -10px;
        border-radius: 8px;
    }

    .yzp-head-title {
        font-size: 20px;
        font-weight: 400;
        margin-bottom: 20px;
        line-height: 1.3em;
    }

    .yzp-head-date {
        font-weight: 600;
        margin-top: 15px;
    }
    .yzp-head-tag {
        display: inline-block;
        background: #000;
        padding: 8px;
        border-radius: 5px;
        color: #ffff;
        font-weight: 600;
        margin-bottom: 20px;
    }

   .allaccesspass-offer {
        background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#3F51B5,#03A9F4);f
        color: #fff;
        padding: 35px;
        border-radius: 5px;
        border-left: 8px solid #ffeb3b;
        margin-bottom: 35px;
    }

    .offer-subtitle {
        color: #ffffff;
        font-size: 19px;
        font-weight: 400;
    }

    .allaccesspass-offer.lifetime {
        background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#000000,#03A9F4);
    }

    .access-notice {
    background: rgb(0 0 0 / 0.3);
    padding: 25px;
    border-left: 8px solid #ffffff;
    border-radius: 3px;
        }
 .yz-new-addon {
   /* background: #8291e4;*/
   /*background:#673ab7;*/
   background:#f44336;
    text-align: center;
    width: 100%;
    padding: 15px 20px;
    position: relative;
    left: 0;
    line-height: 24px;
    color: #fff;
    /*background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#f44336,#E91E63);*/
    /*background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#4CAF50,#00897B);*/
    /*background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#673AB7,#E91E63);*/
    /*background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#242222,#FF5722);*/
    /*background-color: #f16c3e;*/
    /*background-image: url(https://cartflows.com/wp-content/uploads/2020/10/CF-pricing-banner.jpg);*/
    background: url(https://youzer.kainelabs.com/wp-content/uploads/2019/01/hero_decor.svg),linear-gradient(to right,#673AB7,#3b0072);
    background-size: cover;
    background-position: 0 184px;
 }

 .yz-new-addon-name {
    font-weight: 500;
 }

 .yz-view-addon {
    display: inline-block;
    background: #fff;
    color: #898989;
    padding: 10px 13px 8px;
    margin: 0;
    line-height: 14px;
    font-weight: 500;
    border-radius: 2px;
    text-transform: uppercase;
    margin-left: 10px;
    font-size: 12px;
 }

 .yz-view-addon:hover {
    color: black;
 }


 .offer-tag,
 .yz-new-addon-tag {
    padding: 15px;
    color: #3b3b3b;
    line-height:14px;
    font-weight: 500;
    border-radius: 3px;
    text-align: center;
    margin-right: 10px;
    background: #FFEB3B;
    text-transform: uppercase;
 }

.yz-new-addon-tag {
    font-size: 13px;
    padding: 7px;
        display: inline-block;
}

 .offer-tag {
    font-size: 18px;
    padding: 15px;
    display: block;
    margin-bottom: 25px;
 }
 #lifetime-aap-btn,
.offer-tag {
     transform: translate3d(0, 0, 0);
    backface-visibility: hidden;
    animation-name: shakeMe;
    animation-duration: 5s;
    animation-iteration-count: infinite;
    animation-timing-function: linear;
}

.blinking{
    animation:blinkingText 1s infinite;
}

.blinkingblue{
    animation:blinkingTextBlue 1s infinite;
}

@keyframes shakeMe {
    2%, 18% {
        transform: translate3d(-1px, 0, 0);
    }

    4%, 16% {
        transform: translate3d(2px, 0, 0);
    }

    6%, 10%, 14% {
        transform: translate3d(-4px, 0, 0);
    }

    8%, 12% {
        transform: translate3d(4px, 0, 0);
    }

    18.1% {
        transform: translate3d(0px, 0, 0);
    }
}
.sidenote strong,
.access-notice strong,
p.offer-desc strong {
    color: yellow;
}
.allaccesspass-offer ul li strong {
    color: yellow;
}

@keyframes blinkingText{
    0%{     color: #FFEB3B;    }
    49%{    color: #FFEB3B; }
    60%{    color: #000; }
    99%{    color: #000;  }
    100%{   color: #FFEB3B;    }
}
@keyframes blinkingTextBlue{
    0%{     color: #4bebf8;    }
    49%{    color: #4bebf8; }
    60%{    color: #252525; }
    99%{    color: #252525;  }
    100%{   color: #4bebf8;    }
}

    .kl-countdown {
        display: inline-block;
        vertical-align: middle;
    }

    .kl-countdown ul {
        background: #1d2327;
        border-radius: 8px;
    }

    .countdown-title,
    .kl-countdown ul {
        margin: 0;
        display: inline-block;
        vertical-align: middle;
    }

    .kl-countdown li {
      display: inline-block;
      margin: 0;
      font-size: 22px;
      list-style-type: none;
      padding: 1em;
      text-align: center;
      /*text-transform: uppercase;*/
      color: #fff;
    }

    .kl-countdown li span {
      display: block;
      font-size: 18px;
      font-weight: 600;
      margin-bottom: 15px;
    }

    .expired-notice {
        display: none;
    }

@media screen and ( max-width: 768px ) {
    .countdown-title {
        display: none;
    }

}

@media screen and ( max-width: 475px ) {
    .kl-countdown {
        display: block;
    }
}

.yzp-view-offer-details {
    background: #fff;
    height: 55px;
    line-height: 55px;
    text-align: center;
    border-radius: 5px;
    margin-top: 20px;
    padding: 0 25px;
    font-weight: 600;
    font-size: 15px;
    text-decoration: none;
    color: #1d2327;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
}
    .yzp-view-offer-details span {
        margin-left: 10px;
    }

    .yzp-cancel-offer span.dashicons-no-alt {
        position: absolute;
        top: 0;
        right: 0;
        padding: 0;
        text-decoration: none;
        color: #000;
        width: 35px;
        display: block;
        margin: 15px;
        height: 35px;
        line-height: 35px;
        font-size: 25px;
        border-radius: 100%;
        text-align: center;
    }

    .yzp-cancel-offer:hover span {
         background: #0000001c;
    }

    </style>


    <?php
}
/**
 * Affiliate Banner
 */
// add_action( 'youzify_admin_before_form', 'youzify_add_affiliate_banner' );
// add_action( 'youzify_admin_before_form', 'youzify_add_affiliate_banner' );

add_action( 'admin_notices', 'youzify_add_affiliate_banner' );
function youzify_add_affiliate_banner() {

    $id = 'youzify_hide_affiliate_banner';

    if ( isset( $_GET['youzify-hide-affiliate-banner'] ) ) {
        youzify_update_option( $id, 1 );
    }

    if ( youzify_option( $id ) ) {
        return;
    }

    ?>

    <style type="text/css">

        .youzify-affiliate-banner {
            margin: 25px 15px 25px 0;
            position: relative;
            display: flex;
            background: url(<?php echo YOUZIFY_ADMIN_ASSETS . 'images/decor.svg'; ?>),linear-gradient(to right,#3f51b5,#663bb7) !important;
            color: #fff;
            padding: 35px 35px 35px 0;
            border-radius: 6px;
            justify-content: center;
            background-size: cover !important;
        }

        .youzify-affiliate-banner .youzify-banner-image {
            padding: 0 20px;
            width: 300px;
        }

        .youzify-affiliate-banner .youzify-banner-content {
                width: calc( 100% - 300px );
        }
        .youzify-affiliate-banner .youzify-banner-tag {
            font-size: 16px;
            font-weight: 600;
            background: #0000005c;
            color: #ffffff;
            padding: 10px 20px 10px 10px;
            display: inline-block;
            border-radius: 50px;
            clear: both;
            margin-bottom: 30px;
            text-transform: uppercase;
        }

        .youzify-affiliate-banner .youzify-banner-tag svg {
            vertical-align: middle;
            padding: 5px;
            margin-right: 10px;
            width: 25px;
            height: 25px;
            border-radius: 50px;
            background: rgb(0 0 0 / 30%);
            color: #fff;
        }

        .youzify-affiliate-banner .youzify-banner-title {
            vertical-align: middle;
            position: relative;
            display: inline-flex;
            font-size: 23px;
            margin-bottom: 25px;
            font-weight: 600;
        }

        .youzify-affiliate-banner .youzify-banner-description {
            font-size: 18px;
            line-height: 30px;
            margin: 0 0 10px 0;
        }

        .youzify-affiliate-banner .youzify-banner-button {
            margin-top: 20px;
            min-width: 150px;
            border-radius: 50px;
            background: #f89043;
            text-align: center;
            text-decoration: none;
            display: inline-block;
        }

        .youzify-affiliate-banner .youzify-banner-button span {
            color: #ffffff;
            height: 50px;
            font-weight: 600;
            line-height: 50px;
            font-size: 18px;
            margin-left: 5px;
        }

        .youzify-affiliate-banner .youzify-hide-banner {
            color: #FFFF;
            position: absolute;
            top: 15px;
            cursor: pointer;
            right: 15px;
            font-size: 18px;
        }

    </style>
    <div class="youzify-affiliate-banner">

        <div class="youzify-banner-image">
            <img src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/youzify-site-logo.png'; ?>" alt="" style="display: block; margin: 0 auto 15px; ">
            <img src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/affiliate-banner.png'; ?>" alt="" style="width: 100%;">
        </div>

        <div class="youzify-banner-content">
            <div class="youzify-banner-tag"><svg aria-hidden="true" focusable="false" data-prefix="fad" data-icon="hand-holding-usd" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512" class="svg-inline--fa fa-hand-holding-usd fa-w-18 fa-2x"><g class="fa-group"><path fill="currentColor" d="M564 377L412.78 498a64 64 0 0 1-40 14H16a16 16 0 0 1-16-16v-96a16 16 0 0 1 16-16h55.4l46.5-37.71A117.65 117.65 0 0 1 192 320h160a32 32 0 0 1 32 31.94 31.2 31.2 0 0 1-.46 5.46C381 373.1 366.19 384 350.29 384H272a16 16 0 0 0 0 32h118.28a63.64 63.64 0 0 0 40-14l92.4-73.9c12.4-10 30.8-10.7 42.6 0A32 32 0 0 1 564 377z" class="fa-secondary"></path><path fill="currentColor" d="M329.28 222.4V240c0 8.8-7.77 16-17.26 16h-17.25c-9.48 0-17.25-7.2-17.25-16v-17.7a82.78 82.78 0 0 1-34.28-11.5 11.46 11.46 0 0 1-3.85-15.74 11.64 11.64 0 0 1 2.23-2.66l18.87-17.5c4-3.7 10-4.2 15.2-2a29.62 29.62 0 0 0 11.11 2.2h35.36c5 0 9.06-3.8 9.06-8.4a8.58 8.58 0 0 0-6.58-8.1l-53.91-14.3c-23.93-6.4-43.13-24.7-46.25-47.7-4.31-32 20.48-59.4 53.15-63V16c0-8.8 7.76-16 17.25-16h17.25c9.49 0 17.25 7.2 17.25 16v17.7a82.92 82.92 0 0 1 34.29 11.5 11.48 11.48 0 0 1 1.62 18.4l-18.87 17.5c-4 3.7-10 4.2-15.2 2a29.62 29.62 0 0 0-11.11-2.2h-35.36c-5 0-9.06 3.8-9.06 8.4a8.58 8.58 0 0 0 6.58 8.1l53.91 14.3c23.93 6.4 43.11 24.7 46.24 47.7 4.31 32-20.43 59.4-53.14 63z" class="fa-primary"></path></g></svg>Earn up to <span style=" color: #46e74c; ">$499</span> per referral!</div><br>
            <div class="youzify-banner-title" style="vertical-align: middle; position: relative; display: inline-flex; "><span style="color: #00ff43; display: block; margin-right: 8px; ">Woohoo!!</span> Introducing Youzify Affiliate Program.<img src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/celebrate-emoji.png'; ?>" style="width: 43px; position: absolute; top: -16px; right: -50px; "></div>
            <div class="youzify-banner-title">Earn a Generous Aggressive <span style="color: #fbf554; font-weight: 700; margin: 0 5px;"> 50% Commission </span> on Each Sale.</div>
            <div class="youzify-banner-description" style=" margin: 5px 0px 16px; "><?php _e( 'Your efforts are valuable to us! As a partner you deserve no less than half of the income per sale. Turn your trusted recommendations into income and earn 50% commission on every purchase.', 'youzify' ); ?></div>
            <div class="youzify-banner-description" style="margin-bottom: 0; color: #f89043; font-weight: 500; ">Click Button Below and Let's Start a Great Affiliate Partnership TODAY!</div>
            <a target="_blank" href="https://youzify.com/affiliate/?utm_campaign=youzify-affiliate-program&utm_medium=first-banner&utm_source=client-site&utm_content=join-now" class="youzify-banner-button"><img draggable="false" role="img" class="emoji" alt="👉" src="<?php echo YOUZIFY_ADMIN_ASSETS . 'images/point.svg'; ?>"> <span><?php _e( 'Join Today', 'youzify' ); ?></span></a>
        </div>

        <a href="<?php echo esc_url( add_query_arg( 'youzify-hide-affiliate-banner', 'true', youzify_get_current_page_url() ) ); ?>" class="youzify-hide-banner"><span style="color:#fff;text-decoration: none;" class="dashicons dashicons-no-alt"></span></a>

    </div>

    <?php

}