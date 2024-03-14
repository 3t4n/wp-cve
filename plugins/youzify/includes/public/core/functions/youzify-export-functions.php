<?php

/**
 * Register Youzify Data Exporter.
 */
function youzify_register_exporter( $exporters ) {

    // User Data Export.
    $exporters['wordpress-user'] = array(
        'exporter_friendly_name' => __( 'User Information', 'youzify' ),
        'callback' => 'youzify_wp_user_data_exporter',
    );

    // User Widgets Data Export.
    $exporters['youzify-profile-widgets'] = array(
        'exporter_friendly_name' => __( 'Profile Widgets Data', 'youzify' ),
        'callback' => 'youzify_profile_widgets_exporter',
    );

    return $exporters;

}

add_filter( 'wp_privacy_personal_data_exporters', 'youzify_register_exporter', 1 );

/**
 * Get Profile Widgets Data.
 */
function youzify_profile_widgets_exporter( $email_address, $page = 1 ) {

    $export_items = array();

    // Get User.
    $user = get_user_by( 'email', $email_address );

    // Get User Data.
    $user_data = youzify_user_widgets_fields();

    foreach ( $user_data as $widget_name => $widget ) {

        $data = null;

        if ( isset( $widget['fields'] ) ) {

            foreach ( $widget['fields'] as $field_id => $field ) {

                $value = youzify_get_user_meta( $field_id, $user->ID );

                if ( empty( $value ) ) continue;

                $value = apply_filters( 'youzify_exported_field_value', $value, $field );

                $data[] = array(
                  'name' => $field['title'],
                  'value' => $value
                );

            }

            if ( empty( $data ) ) {
                continue;
            }

            $export_items[] = array(
                'group_id' => 'youzify-' . $widget_name,
                'group_label' => $widget['title'],
                'item_id' => $widget_name,
                'data' => $data
            );

        } else {

            $value = get_the_author_meta( $widget['id'], $user->ID );

            if ( empty( $value ) && $widget_name != 'social_networks' ) {
                continue;
            }


            switch ( $widget_name ) {

                case 'instagram':

                    $instagram_data = get_the_author_meta( 'youzify_wg_instagram_account_user_data', $user->ID );

                    if ( empty( $instagram_data ) ) {
                        break;
                    }

                    foreach ( $instagram_data as $key => $value ) {
                        if ( ! empty( $value ) && $key != '__PHP_Incomplete_Class_Name' ) {
                            $data[] = array( 'name' => $key, 'value' => $value );
                        }
                    }

                    $export_items[] = array(
                        'group_id' => 'youzify-' . $widget_name,
                        'group_label' => $widget['title'],
                        'item_id' => $widget_name,
                        'data' => $data
                    );

                    break;

                case 'flickr':

                    $data = array( array( 'name' => __( 'Account ID', 'youzify' ), 'value' => $value ) );

                    $export_items[] = array(
                        'group_id' => 'youzify-' . $widget_name,
                        'group_label' => $widget['title'],
                        'item_id' => $widget_name,
                        'data' => $data
                    );

                    break;

                case 'skills':
                case 'services':

                    $i = 1;

                    foreach ( $value as $key => $wg_data ) {

                        $data = null;

                        foreach ( $wg_data as $key => $val ) {
                            $data[] = array( 'name' => ucfirst( $key ), 'value' => $val );
                        }

                        $export_items[] = array(
                            'group_id' => 'youzify-' . $widget_name,
                            'group_label' => $widget['title'],
                            'item_id' => $widget_name . $i,
                            'data' => $data
                        );

                        $i++;
                    }

                    break;

                case 'portfolio':
                case 'slideshow':

                    global $Youzify_upload_url;

                    $i = 1;

                    foreach ( $value as $key => $wg_data ) {

                        $data = null;

                        foreach ( $wg_data as $img_key => $img_value) {

                            if ( empty( $img_value ) ) {
                                continue;
                            }

                            $val = ( $img_key == 'original' || $img_key == "thumbnail" ) ? $Youzify_upload_url. $img_value: $img_value;

                            $data[] = array( 'name' => ucfirst( $img_key ), 'value' => '<a href=" ' . $val . '" >' . $val . '</a>' );
                        }


                        $export_items[] = array(
                            'group_id' => 'youzify-' . $widget_name,
                            'group_label' => $widget['title'],
                            'item_id' => $widget_name . $i,
                            'data' => $data
                        );

                        $i++;
                    }

                    break;

                default:
                    $export_items[] = array(
                        'group_id' => 'youzify-' . $widget_name,
                        'group_label' => $widget['title'],
                        'item_id' => $widget_name,
                        'data' => array( array( 'name' => $widget['title'], 'value' => $value ) )
                    );
                    break;
            }

        }

    }

    return array(
        'data' => $export_items,
        'done' => true,
    );
}

/**
 * Get User Profile Data.
 */
function youzify_wp_user_data_exporter( $email_address, $page = 1 ) {

    // Get Fields
    $fields = youzify_wp_user_fields();

    // Get User.
    $user = get_user_by( 'email', $email_address );

    foreach ( $fields as $field_id => $field ) {

        $value = youzify_get_user_meta( $field_id, $user->ID );

        if ( empty( $value ) ) continue;

        $value = apply_filters( 'youzify_wp_user_data_export_value', $value, $field );

        $data[] = array(
          'name' => $field['title'],
          'value' => $value
        );

    }

    // Get Export Items
    $export_items[] = array(
        'group_id'    => 'user',
        'group_label' => __( 'User', 'youzify' ),
        'item_id'     => "user-{$user->ID}",
        'data' => $data
    );

    return array(
        'data' => $export_items,
        'done' => true,
    );
}


/**
 * Get Wordpress User Fields.
 */
function youzify_wp_user_fields() {
    $fields = array(
        'ID' => array(
            'title' => __( 'User ID', 'youzify' ),
        ),
        'user_firstname' => array(
            'title' => __( 'First Name', 'youzify' ),
        ),
        'user_lastname' => array(
            'title' => __( 'Last Name', 'youzify' ),
        ),
        'nickname' => array(
            'title' => __( 'Nickname', 'youzify' ),
        ),
        'user_nicename' => array(
            'title' => __( 'Nice Name', 'youzify' ),
        ),
        'display_name' => array(
            'title' => __( 'Display Name', 'youzify' ),
        ),
        'user_login' => array(
            'title' => __( 'User Login', 'youzify' ),
        ),
        'user_email' => array(
            'title' => __( 'Email', 'youzify' ),
        ),
        'user_url' => array(
            'title' => __( 'Website', 'youzify' ),
        ),
        'user_registered' => array(
            'title' => __( 'User Registration Date', 'youzify' ),
        ),
        'user_description' => array(
            'title' => __( 'Description', 'youzify' ),
        )
    );

    return apply_filters( 'youzify_wp_user_fields', $fields );
}

/**
 * Get User Widgets Fields.
 */
function youzify_user_widgets_fields() {

    $fields = array(
        'instagram' => array(
            'id' => 'youzify_wg_instagram_account_token',
            'title' => __( 'User Instagram Widget', 'youzify' )
        ),
        'flickr' => array(
            'id' => 'youzify_wg_flickr_account_id',
            'title' => __( 'User Flickr Widget', 'youzify' )
        ),
        'skills' => array(
            'id' => 'youzify_skills',
            'title' => __( 'User Skills Widget', 'youzify' )
        ),
        'services' => array(
            'id' => 'youzify_services',
            'title' => __( 'User Services Widget', 'youzify' )
        ),
        'slideshow' => array(
            'id' => 'youzify_slideshow',
            'title' => __( 'User Slideshow Widget', 'youzify' ),
            'type' => 'images'
        ),
        'portfolio' => array(
            'id' => 'youzify_portfolio',
            'title' => __( 'User Portfolio Widget', 'youzify' ),
            'type' => 'images'
        ),
        'post' => array(
            'title' => __( 'User Post Widget', 'youzify' ),
            'fields' => array(
                'youzify_wg_post_id' => array(
                    'title' => __( 'Post ID', 'youzify' ),
                ),
                'youzify_wg_post_type' => array(
                    'title' => __( 'Post Type', 'youzify' ),
                )
            )
        ),
        'video' => array(
            'title' => __( 'User Video Widget', 'youzify' ),
            'fields' => array(
                'youzify_wg_video_title' => array(
                    'title' => __( 'Title', 'youzify' ),
                ),
                'youzify_wg_video_desc' => array(
                    'title' => __( 'Description', 'youzify' ),
                ),
                'youzify_wg_video_url' => array(
                    'title' => __( 'URL', 'youzify' ),
                )
            )
        ),
        'about_me' => array(
            'title' => __( 'User About me Widget', 'youzify' ),
            'fields' => array(
                'youzify_wg_about_me_photo' => array(
                    'title' => __( 'Photo', 'youzify' ),
                    'type' => 'image'
                ),
                'youzify_wg_about_me_title' => array(
                    'title' => __( 'Title', 'youzify' ),
                ),
                'youzify_wg_about_me_desc' => array(
                    'title' => __( 'Description', 'youzify' ),
                ),
                'youzify_wg_about_me_bio' => array(
                    'title' => __( 'Biography', 'youzify' ),
                )
            )
        ),
        'quote' => array(
            'title' => __( 'User Quote Widget', 'youzify' ),
            'fields' => array(
                'youzify_wg_quote_owner' => array(
                    'title' => __( 'Owner', 'youzify' ),
                ),
                'youzify_wg_quote_txt' => array(
                    'title' => __( 'Text', 'youzify' ),
                ),
                'youzify_wg_quote_img' => array(
                    'title' => __( 'Cover', 'youzify' ),
                    'type'  => 'image'
                ),
                'youzify_wg_quote_use_bg' => array(
                    'title' => __( 'Use Quote Cover?', 'youzify' ),
                )
            )
        ),
        'link' => array(
            'title' => __( 'User Link Widget', 'youzify' ),
            'fields' => array(
                'youzify_wg_link_url' => array(
                    'title' => __( 'URL', 'youzify' ),
                ),
                'youzify_wg_link_txt' => array(
                    'title' => __( 'Text', 'youzify' ),
                ),
                'youzify_wg_link_img' => array(
                    'title' => __( 'Cover', 'youzify' ),
                    'type'  => 'image'
                ),
                'youzify_wg_link_use_bg' => array(
                    'title' => __( 'Use Link Cover?', 'youzify' ),
                )
            )
        ),
        'project' => array(
            'title' => __( 'User Project Widget', 'youzify' ),
            'fields' => array(
                'youzify_wg_project_title' => array(
                    'title' => __( 'Title', 'youzify' ),
                ),
                'youzify_wg_project_desc' => array(
                    'title' => __( 'Description', 'youzify' ),
                ),
                'youzify_wg_project_type' => array(
                    'title' => __( 'Type', 'youzify' ),
                ),
                'youzify_wg_project_thumbnail' => array(
                    'title' => __( 'Thumbnail', 'youzify' ),
                    'type' => 'image'
                ),
                'youzify_wg_project_link' => array(
                    'title' => __( 'Link', 'youzify' ),
                ),
                'youzify_wg_project_categories' => array(
                    'title' => __( 'Categories', 'youzify' ),
                    'type' => 'options',
                ),
                'youzify_wg_project_tags' => array(
                    'title' => __( 'Tags', 'youzify' ),
                    'type' => 'options',
                ),
            )
        ),

    );

    // Add Networks Fields
    $networks_fields = youzify_get_social_networks_fields();

    if ( ! empty( $networks_fields ) ) {
        $fields['social_networks'] = array( 'title' => __( 'Social Networks', 'youzify' ), 'fields' => $networks_fields );
    }

    return apply_filters( 'youzify_export_fields', $fields );

}

/**
 * Get Social Networks Fields.
 */
function youzify_get_social_networks_fields() {

    // Init Vars
    $networks_fields = array();

    // Get Social Networks
    $social_networks = youzify_option( 'youzify_social_networks' );

    if ( empty( $social_networks ) ) {
        return false;
    }

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Check if there's URL related to the icons.
    foreach ( $social_networks as $network => $data ) {
        $networks_fields[ $network ] = array( 'title' => $data['name'] );
    }

    return $networks_fields;
}

/**
 * Filter Exported Fields Values.
 */
function youzify_filter_exported_field_value( $value, $field ) {

    if ( ! isset( $field['type'] ) ) {
        return $value;
    }

    switch ( $field['type'] ) {

        case 'options':

            return implode( ', ', $value );

        case 'images':
        case 'image':

            global $Youzify_upload_url;

            foreach ( $value as $key => $val ) {

                if ( empty( $val ) ) continue;

                if ( $key == 'original' ) {
                    $key = __( 'Original', 'youzify' );
                } elseif ( $key == 'thumbnail' ) {
                    $key = __( 'Thumbnail', 'youzify' );
                }

                // Get Url.
                $url = $Youzify_upload_url . $val;

                // Get Content.
                $content .='<strong>' . $key . '</strong> : <a href="' . $url . '">' . $url . '</a><br>';

            }

            return $content;

        default:
            return $value;
            break;
    }

}

add_filter( 'youzify_exported_field_value', 'youzify_filter_exported_field_value', 10, 2 );