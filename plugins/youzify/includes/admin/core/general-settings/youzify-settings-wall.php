<?php

/**
 * Wall Settings.
 */

function youzify_wall_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'General Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_url_preview',
            'title' => __( 'URL Live Preview', 'youzify' ),
            'desc'  => __( 'Display URL preview in the wall form', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_activity_loader',
            'title' => __( 'Infinite Loader', 'youzify' ),
            'desc'  => __( 'Enable activity infinite loader', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_activity_effects',
            'title' => __( 'Activity Loading Effect', 'youzify' ),
            'desc'  => __( 'Enable activity loading effect', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Stream Layouts', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'id'    => 'youzify_activity_stream_layout',
            'type'  => 'imgSelect',
            'available_opts' => array( 'youzify-wall-left-sidebar', 'youzify-wall-right-sidebar' ),
            'opts'  => array( 'youzify-wall-left-sidebar' , 'youzify-wall-3columns', 'youzify-wall-right-sidebar' )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Check in Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'text',
            'id'    => 'youzify_google_map_api_key',
            'title' => __( 'Google Map API Key', 'youzify' ),
            'desc'  => __( 'type Google Map API key', 'youzify' ),
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Allowed Countries', 'youzify' ),
            'desc'  => __( 'keep it empty for all', 'youzify' ),
            'id'    => 'youzify_google_map_allowed_countries',
            'type'  => 'multiselect',
            'opts'  => youzify_alpha2_countries()
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Sticky Posts Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_activity_sticky_posts',
            'title' => __( 'Enable Activity Sticky Posts', 'youzify' ),
            'desc'  => __( 'Allow admins to pin or unpin posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_groups_sticky_posts',
            'title' => __( 'Enable Groups Sticky Posts', 'youzify' ),
            'desc'  => __( 'Allow admins to pin or unpin posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posting Form Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_privacy',
            'title' => __( 'Privacy', 'youzify' ),
            'desc'  => __( 'Enable activity posts privacy', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_mood',
            'title' => __( 'Feeling / Activity', 'youzify' ),
            'desc'  => __( 'Enable posts feeling and activity', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_tag_friends',
            'title' => __( 'Tag Friends', 'youzify' ),
            'desc'  => __( 'Enable tagging friends in posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_activity_checkin',
            'title' => __( 'Check in', 'youzify' ),
            'desc'  => __( 'Enable adding location', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Filters Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_filter_bar',
            'title' => __( 'Display Profile Activity Filter', 'youzify' ),
            'desc'  => __( 'Show profile activity filter bar', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_activity_directory_filter_bar',
            'title' => __( 'Display Activity Stream Filter', 'youzify' ),
            'desc'  => __( 'Show global activity stream page filter bar', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Embeds Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_embeds',
            'title' => __( 'Enable Posts Embeds', 'youzify' ),
            'desc'  => __( 'Activate Embeds inside posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_comments_embeds',
            'title' => __( 'Enable Comments Embeds', 'youzify' ),
            'desc'  => __( 'Activate Embeds inside comments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Buttons Settings', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_likes',
            'title' => __( 'Enable Likes', 'youzify' ),
            'desc'  => __( 'Allow users to like posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_comments',
            'title' => __( 'Enable Comments', 'youzify' ),
            'desc'  => __( 'Allow posts comments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'is_premium' => true,
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_shares',
            'title' => __( 'Enable Shares', 'youzify' ),
            'desc'  => __( 'Allow users to share posts', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_deletion',
            'title' => __( 'Enable Deletion', 'youzify' ),
            'desc'  => __( 'Enable posts delete button', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_enable_wall_posts_reply',
            'title' => __( 'Enable Comments Replies', 'youzify' ),
            'desc'  => __( 'Allow posts comments replies', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'is_premium' => true,
            'type'  => 'checkbox',
            'id'    => 'youzify_wall_comments_gif',
            'title' => __( 'Enable Comments GIFs', 'youzify' ),
            'desc'  => __( 'Allow comments GIFs', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Attachments Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_attachments_max_nbr',
            'title' => __( 'Max Attachments Number', 'youzify' ),
            'desc'  => __( 'Slideshow and photos max number per post', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_attachments_max_size',
            'title' => __( 'Max File Size', 'youzify' ),
            'desc'  => __( 'Attachment max size by megabytes', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_images_exts',
            'title' => __( 'Image Extensions', 'youzify' ),
            'desc'  => __( 'Allowed image extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_videos_exts',
            'title' => __( 'Video Extensions', 'youzify' ),
            'desc'  => __( 'Allowed video extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_audios_exts',
            'title' => __( 'Audio Extensions', 'youzify' ),
            'desc'  => __( 'Allowed audio extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_atts_allowed_files_exts',
            'title' => __( 'Files Extensions', 'youzify' ),
            'desc'  => __( 'Allowed files extensions', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Comments Attachments Settings', 'youzify' ),
            'type'  => 'openBox',
            'is_premium' => true
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'checkbox',
            'id'    => 'youzify_wall_comments_attachments',
            'title' => __( 'Comments Attachments', 'youzify' ),
            'desc'  => __( 'Enable comments attachments', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_wall_comments_attachments_extensions',
            'title' => __( 'Allowed Extensions', 'youzify' ),
            'desc'  => __( 'Allowed extensions list', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_wall_comments_attachments_max_size',
            'title' => __( 'Max File Size', 'youzify' ),
            'desc'  => __( 'Attachment max size by megabytes', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Activity Moderation Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'taxonomy',
            'id'    => 'youzify_moderation_keys',
            'title' => __( 'Forbidden Community Words', 'youzify' ),
            'desc'  => __( 'Add a list of forbidden words that cannot be used on the activity posts and comments.', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Posts Per Page Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_profile_wall_posts_per_page',
            'title' => __( 'Profile - Posts Per Page', 'youzify' ),
            'desc'  => __( 'Profile wall posts per page', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_groups_wall_posts_per_page',
            'title' => __( 'Groups - Posts Per Page', 'youzify' ),
            'desc'  => __( 'Groups wall posts per page', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field(
        array(
            'type'  => 'number',
            'id'    => 'youzify_activity_wall_posts_per_page',
            'title' => __( 'Activity - Posts Per Page', 'youzify' ),
            'desc'  => __( 'Global activity wall posts per page', 'youzify' ),
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Control Wall Posts Visibility', 'youzify' ),
            'class' => 'ukai-box-3cols',
            'type'  => 'openBox'
        )
    );

    $post_types = youzify_activity_post_types();

    // Get Unallowed Types.
    $unallowed_types = array_flip( get_option( 'youzify_unallowed_activities', array() ) );

    if ( isset( $unallowed_types['friendship_accepted,friendship_created'] ) ) {
        $unallowed_types['friendship_accepted'] = 'on';
        $unallowed_types['friendship_created'] = 'on';
    }

    foreach ( $post_types as $post_type => $name ) {

        $Youzify_Settings->get_field(
            array(
                'type'  => 'checkbox',
                'std'   => isset( $unallowed_types[ $post_type ] ) ? 'off' : 'on',
                'id'    => $post_type,
                'title' => $name,
                'desc'  => sprintf( __( 'Enable activity %s posts', 'youzify' ), $name ),
            ), false, 'youzify_unallowed_activities'
        );

    }

    do_action( 'youzify_wall_posts_visibility_settings' );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

    wp_enqueue_script( 'youzify-polls-admin', YOUZIFY_ADMIN_ASSETS . 'js/youzify-polls-admin.js', array( 'jquery' ) );

    // start
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Polls Form Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Multiple Options', 'youzify' ),
            'desc'  => __( 'Allow users to choose if poll should be single or multiple', 'youzify' ),
            'id'    => 'yzap_poll_multi_options',
            'type'  => 'checkbox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Poll Options Images', 'youzify' ),
            'desc'  => __( 'Allow users to attach images to voting options', 'youzify' ),
            'id'    => 'yzap_poll_options_image_enable',
            'type'  => 'checkbox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Make Options Images Required', 'youzify' ),
            'desc'  => __( 'Force users to upload an image for each option', 'youzify' ),
            'id'    => 'yzap_poll_options_image',
            'type'  => 'checkbox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Poll Options Limit', 'youzify' ),
            'desc'  => __( 'Set the limit of the poll options', 'youzify' ),
            'id'    => 'yzap_poll_options_limit',
            'type'  => 'number'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Poll Voting Mode', 'youzify' ),
            'desc'  => __( 'Set default poll voting mode', 'youzify' ),
            'id'    => 'yzap_poll_options_selection',
            'type'  => 'select',
            'opts'  => array(
                'single' => __( 'Single Options', 'youzify' ),
                'multi'  => __( 'Multiple Options', 'youzify' )
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );


    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Polls Post Settings', 'youzify' ),
            'type'  => 'openBox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Poll Result', 'youzify' ),
            'desc'  => __( 'Allow users to view poll results', 'youzify' ),
            'id'    => 'yzap_poll_options_result',
            'type'  => 'checkbox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Enable Revoting', 'youzify' ),
            'desc'  => __( 'Allow users to change their votes after voting', 'youzify' ),
            'id'    => 'yzap_poll_revote',
            'type'  => 'checkbox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Show Voters List', 'youzify' ),
            'desc'  => __( 'Allow users to view list of voters', 'youzify' ),
            'id'    => 'yzap_poll_list_voters',
            'type'  => 'checkbox'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Max Voters Number', 'youzify' ),
            'desc'  => __( 'Max voters to show', 'youzify' ),
            'id'    => 'yzap_poll_limit_voters',
            'type'  => 'number'
        )
    );

    // Checkbox Settings Example
    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Default Polls Post View', 'youzify' ),
            'desc'  => __( 'Which view should appear first in the polls post?', 'youzify' ),
            'id'    => 'yzap_poll_options_redirection',
            'type'  => 'select',
            'opts'  => array(
                'poll' => __( 'Poll Options', 'youzify' ),
                'result'  => __( 'Result Options', 'youzify' )
            )
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

}

function youzify_alpha2_countries() {
    return array
            (
            'AF' => 'Afghanistan',
            'AX' => 'Aland Islands',
            'AL' => 'Albania',
            'DZ' => 'Algeria',
            'AS' => 'American Samoa',
            'AD' => 'Andorra',
            'AO' => 'Angola',
            'AI' => 'Anguilla',
            'AQ' => 'Antarctica',
            'AG' => 'Antigua And Barbuda',
            'AR' => 'Argentina',
            'AM' => 'Armenia',
            'AW' => 'Aruba',
            'AU' => 'Australia',
            'AT' => 'Austria',
            'AZ' => 'Azerbaijan',
            'BS' => 'Bahamas',
            'BH' => 'Bahrain',
            'BD' => 'Bangladesh',
            'BB' => 'Barbados',
            'BY' => 'Belarus',
            'BE' => 'Belgium',
            'BZ' => 'Belize',
            'BJ' => 'Benin',
            'BM' => 'Bermuda',
            'BT' => 'Bhutan',
            'BO' => 'Bolivia',
            'BA' => 'Bosnia And Herzegovina',
            'BW' => 'Botswana',
            'BV' => 'Bouvet Island',
            'BR' => 'Brazil',
            'IO' => 'British Indian Ocean Territory',
            'BN' => 'Brunei Darussalam',
            'BG' => 'Bulgaria',
            'BF' => 'Burkina Faso',
            'BI' => 'Burundi',
            'KH' => 'Cambodia',
            'CM' => 'Cameroon',
            'CA' => 'Canada',
            'CV' => 'Cape Verde',
            'KY' => 'Cayman Islands',
            'CF' => 'Central African Republic',
            'TD' => 'Chad',
            'CL' => 'Chile',
            'CN' => 'China',
            'CX' => 'Christmas Island',
            'CC' => 'Cocos (Keeling) Islands',
            'CO' => 'Colombia',
            'KM' => 'Comoros',
            'CG' => 'Congo',
            'CD' => 'Congo, Democratic Republic',
            'CK' => 'Cook Islands',
            'CR' => 'Costa Rica',
            'CI' => 'Cote D\'Ivoire',
            'HR' => 'Croatia',
            'CU' => 'Cuba',
            'CY' => 'Cyprus',
            'CZ' => 'Czech Republic',
            'DK' => 'Denmark',
            'DJ' => 'Djibouti',
            'DM' => 'Dominica',
            'DO' => 'Dominican Republic',
            'EC' => 'Ecuador',
            'EG' => 'Egypt',
            'SV' => 'El Salvador',
            'GQ' => 'Equatorial Guinea',
            'ER' => 'Eritrea',
            'EE' => 'Estonia',
            'ET' => 'Ethiopia',
            'FK' => 'Falkland Islands (Malvinas)',
            'FO' => 'Faroe Islands',
            'FJ' => 'Fiji',
            'FI' => 'Finland',
            'FR' => 'France',
            'GF' => 'French Guiana',
            'PF' => 'French Polynesia',
            'TF' => 'French Southern Territories',
            'GA' => 'Gabon',
            'GM' => 'Gambia',
            'GE' => 'Georgia',
            'DE' => 'Germany',
            'GH' => 'Ghana',
            'GI' => 'Gibraltar',
            'GR' => 'Greece',
            'GL' => 'Greenland',
            'GD' => 'Grenada',
            'GP' => 'Guadeloupe',
            'GU' => 'Guam',
            'GT' => 'Guatemala',
            'GG' => 'Guernsey',
            'GN' => 'Guinea',
            'GW' => 'Guinea-Bissau',
            'GY' => 'Guyana',
            'HT' => 'Haiti',
            'HM' => 'Heard Island & Mcdonald Islands',
            'VA' => 'Holy See (Vatican City State)',
            'HN' => 'Honduras',
            'HK' => 'Hong Kong',
            'HU' => 'Hungary',
            'IS' => 'Iceland',
            'IN' => 'India',
            'ID' => 'Indonesia',
            'IR' => 'Iran, Islamic Republic Of',
            'IQ' => 'Iraq',
            'IE' => 'Ireland',
            'IM' => 'Isle Of Man',
            'IL' => 'Israel',
            'IT' => 'Italy',
            'JM' => 'Jamaica',
            'JP' => 'Japan',
            'JE' => 'Jersey',
            'JO' => 'Jordan',
            'KZ' => 'Kazakhstan',
            'KE' => 'Kenya',
            'KI' => 'Kiribati',
            'KR' => 'Korea',
            'KW' => 'Kuwait',
            'KG' => 'Kyrgyzstan',
            'LA' => 'Lao People\'s Democratic Republic',
            'LV' => 'Latvia',
            'LB' => 'Lebanon',
            'LS' => 'Lesotho',
            'LR' => 'Liberia',
            'LY' => 'Libyan Arab Jamahiriya',
            'LI' => 'Liechtenstein',
            'LT' => 'Lithuania',
            'LU' => 'Luxembourg',
            'MO' => 'Macao',
            'MK' => 'Macedonia',
            'MG' => 'Madagascar',
            'MW' => 'Malawi',
            'MY' => 'Malaysia',
            'MV' => 'Maldives',
            'ML' => 'Mali',
            'MT' => 'Malta',
            'MH' => 'Marshall Islands',
            'MQ' => 'Martinique',
            'MR' => 'Mauritania',
            'MU' => 'Mauritius',
            'YT' => 'Mayotte',
            'MX' => 'Mexico',
            'FM' => 'Micronesia, Federated States Of',
            'MD' => 'Moldova',
            'MC' => 'Monaco',
            'MN' => 'Mongolia',
            'ME' => 'Montenegro',
            'MS' => 'Montserrat',
            'MA' => 'Morocco',
            'MZ' => 'Mozambique',
            'MM' => 'Myanmar',
            'NA' => 'Namibia',
            'NR' => 'Nauru',
            'NP' => 'Nepal',
            'NL' => 'Netherlands',
            'AN' => 'Netherlands Antilles',
            'NC' => 'New Caledonia',
            'NZ' => 'New Zealand',
            'NI' => 'Nicaragua',
            'NE' => 'Niger',
            'NG' => 'Nigeria',
            'NU' => 'Niue',
            'NF' => 'Norfolk Island',
            'MP' => 'Northern Mariana Islands',
            'NO' => 'Norway',
            'OM' => 'Oman',
            'PK' => 'Pakistan',
            'PW' => 'Palau',
            'PS' => 'Palestinian Territory, Occupied',
            'PA' => 'Panama',
            'PG' => 'Papua New Guinea',
            'PY' => 'Paraguay',
            'PE' => 'Peru',
            'PH' => 'Philippines',
            'PN' => 'Pitcairn',
            'PL' => 'Poland',
            'PT' => 'Portugal',
            'PR' => 'Puerto Rico',
            'QA' => 'Qatar',
            'RE' => 'Reunion',
            'RO' => 'Romania',
            'RU' => 'Russian Federation',
            'RW' => 'Rwanda',
            'BL' => 'Saint Barthelemy',
            'SH' => 'Saint Helena',
            'KN' => 'Saint Kitts And Nevis',
            'LC' => 'Saint Lucia',
            'MF' => 'Saint Martin',
            'PM' => 'Saint Pierre And Miquelon',
            'VC' => 'Saint Vincent And Grenadines',
            'WS' => 'Samoa',
            'SM' => 'San Marino',
            'ST' => 'Sao Tome And Principe',
            'SA' => 'Saudi Arabia',
            'SN' => 'Senegal',
            'RS' => 'Serbia',
            'SC' => 'Seychelles',
            'SL' => 'Sierra Leone',
            'SG' => 'Singapore',
            'SK' => 'Slovakia',
            'SI' => 'Slovenia',
            'SB' => 'Solomon Islands',
            'SO' => 'Somalia',
            'ZA' => 'South Africa',
            'GS' => 'South Georgia And Sandwich Isl.',
            'ES' => 'Spain',
            'LK' => 'Sri Lanka',
            'SD' => 'Sudan',
            'SR' => 'Suriname',
            'SJ' => 'Svalbard And Jan Mayen',
            'SZ' => 'Swaziland',
            'SE' => 'Sweden',
            'CH' => 'Switzerland',
            'SY' => 'Syrian Arab Republic',
            'TW' => 'Taiwan',
            'TJ' => 'Tajikistan',
            'TZ' => 'Tanzania',
            'TH' => 'Thailand',
            'TL' => 'Timor-Leste',
            'TG' => 'Togo',
            'TK' => 'Tokelau',
            'TO' => 'Tonga',
            'TT' => 'Trinidad And Tobago',
            'TN' => 'Tunisia',
            'TR' => 'Turkey',
            'TM' => 'Turkmenistan',
            'TC' => 'Turks And Caicos Islands',
            'TV' => 'Tuvalu',
            'UG' => 'Uganda',
            'UA' => 'Ukraine',
            'AE' => 'United Arab Emirates',
            'GB' => 'United Kingdom',
            'US' => 'United States',
            'UM' => 'United States Outlying Islands',
            'UY' => 'Uruguay',
            'UZ' => 'Uzbekistan',
            'VU' => 'Vanuatu',
            'VE' => 'Venezuela',
            'VN' => 'Viet Nam',
            'VG' => 'Virgin Islands, British',
            'VI' => 'Virgin Islands, U.S.',
            'WF' => 'Wallis And Futuna',
            'EH' => 'Western Sahara',
            'YE' => 'Yemen',
            'ZM' => 'Zambia',
            'ZW' => 'Zimbabwe',
        );
}