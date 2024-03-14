<?php

/****************************************************
 * PRIME MOVE GLOBAL FUNCTIONS
 * Globally accessible by Prime Mover clases/scripts
 * **************************************************
 */
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !function_exists( 'pm_fs' ) ) {
    // Create a helper function for easy SDK access.
    function pm_fs()
    {
        global  $pm_fs ;
        
        if ( !isset( $pm_fs ) ) {
            // Activate multisite network integration.
            if ( !defined( 'WP_FS__PRODUCT_3826_MULTISITE' ) ) {
                define( 'WP_FS__PRODUCT_3826_MULTISITE', true );
            }
            // Include Freemius SDK.
            require_once PRIME_MOVER_MAINDIR . '/freemius/start.php';
            $pm_fs = fs_dynamic_init( array(
                'id'             => '3826',
                'slug'           => 'prime-mover',
                'premium_slug'   => 'prime-mover-pro',
                'type'           => 'plugin',
                'public_key'     => 'pk_a69fd5401be20bf46608b1c38165b',
                'is_premium'     => false,
                'premium_suffix' => 'Pro',
                'has_addons'     => false,
                'has_paid_plans' => true,
                'trial'          => array(
                'days'               => 14,
                'is_require_payment' => true,
            ),
                'menu'           => array(
                'slug'    => 'migration-panel-settings',
                'network' => true,
            ),
                'is_live'        => true,
            ) );
        }
        
        return $pm_fs;
    }
    
    // Init Freemius.
    pm_fs();
    // Signal that SDK was initiated.
    do_action( 'pm_fs_loaded' );
}

if ( !function_exists( 'primeMoverGetConfigurationPath' ) ) {
    function primeMoverGetConfigurationPath()
    {
        
        if ( file_exists( ABSPATH . 'wp-config.php' ) ) {
            return ABSPATH . 'wp-config.php';
        } elseif ( @file_exists( dirname( ABSPATH ) . '/wp-config.php' ) && !@file_exists( dirname( ABSPATH ) . '/wp-settings.php' ) ) {
            return dirname( ABSPATH ) . '/wp-config.php';
        } else {
            return '';
        }
    
    }

}
if ( !function_exists( 'primeMoverGetUploadsDirectoryInfo' ) ) {
    function primeMoverGetUploadsDirectoryInfo()
    {
        $main_site_blog_id = 0;
        $multisite = false;
        if ( is_multisite() ) {
            $multisite = true;
        }
        if ( $multisite ) {
            $main_site_blog_id = get_main_site_id();
        }
        if ( $multisite ) {
            switch_to_blog( $main_site_blog_id );
        }
        $upload_dir = wp_upload_dir();
        if ( $multisite ) {
            restore_current_blog();
        }
        return $upload_dir;
    }

}
if ( !function_exists( 'primeMoverIsShaString' ) ) {
    function primeMoverIsShaString( $string = '', $mode = 256 )
    {
        if ( !$string ) {
            return false;
        }
        $lengths = [
            256 => 64,
            512 => 128,
        ];
        $length = $lengths[$mode];
        return (bool) preg_match( '/^[0-9a-f]{' . $length . '}$/i', $string );
    }

}
if ( !function_exists( 'primeMoverLanguageToLocale' ) ) {
    function primeMoverLanguageToLocale()
    {
        return array(
            'af'      => 'af_ZA',
            'ar'      => 'ar',
            'az'      => 'az',
            'be'      => 'be_BY',
            'bg'      => 'bg_BG',
            'bn'      => 'bn_BD',
            'bs'      => 'bs_BA',
            'ca'      => 'ca',
            'cs'      => 'cs_CZ',
            'cy'      => 'cy_GB',
            'da'      => 'da_DK',
            'de'      => 'de_DE',
            'el'      => 'el',
            'en'      => 'en_US',
            'eo'      => 'eo_UY',
            'es'      => 'es_ES',
            'et'      => 'et',
            'eu'      => 'eu_ES',
            'fa'      => 'fa_IR',
            'fi'      => 'fi',
            'fo'      => 'fo_FO',
            'fr'      => 'fr_FR',
            'ga'      => 'ga_IE',
            'gl'      => 'gl_ES',
            'he'      => 'he_IL',
            'hi'      => 'hi_IN',
            'hr'      => 'hr',
            'hu'      => 'hu_HU',
            'hy'      => 'hy_AM',
            'id'      => 'id_ID',
            'is'      => 'is_IS',
            'it'      => 'it_IT',
            'ja'      => 'ja',
            'ka'      => 'ge_GE',
            'km'      => 'km_KH',
            'ko'      => 'ko_KR',
            'ku'      => 'ckb',
            'lt'      => 'lt_LT',
            'lv'      => 'lv_LV',
            'mg'      => 'mg_MG',
            'mk'      => 'mk_MK',
            'mn'      => 'mn_MN',
            'ms'      => 'ms_MY',
            'mt'      => 'mt_MT',
            'nb'      => 'nb_NO',
            'ne'      => 'ne',
            'no'      => 'nb_NO',
            'nn'      => 'nn_NO',
            'ni'      => 'ni_ID',
            'nl'      => 'nl_NL',
            'pa'      => 'pa_IN',
            'pl'      => 'pl_PL',
            'pt-br'   => 'pt_BR',
            'pt-pt'   => 'pt_PT',
            'qu'      => 'quz_PE',
            'ro'      => 'ro_RO',
            'ru'      => 'ru_RU',
            'si'      => 'si_LK',
            'sk'      => 'sk_SK',
            'sl'      => 'sl_SI',
            'so'      => 'so_SO',
            'sq'      => 'sq_AL',
            'sr'      => 'sr_RS',
            'su'      => 'su_ID',
            'sv'      => 'sv_SE',
            'ta'      => 'ta_IN',
            'tg'      => 'tg_TJ',
            'th'      => 'th',
            'tr'      => 'tr_TR',
            'ug'      => 'ug_CN',
            'uk'      => 'uk',
            'ur'      => 'ur',
            'uz'      => 'uz_UZ',
            'vi'      => 'vi_VN',
            'zh-hans' => 'zh_CN',
            'zh-hant' => 'zh_TW',
        );
    }

}
if ( !function_exists( 'is_php_version_compatible' ) ) {
    function is_php_version_compatible( $required )
    {
        return empty($required) || version_compare( phpversion(), $required, '>=' );
    }

}
if ( !function_exists( 'primeMoverDefaultUserAdjustments' ) ) {
    function primeMoverDefaultUserAdjustments()
    {
        $definitions = [];
        /**
         * STRUCTURE: ['hash' => ['table_name', 'primary_index', 'column_name'] ]
         */
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverEDDCompat::maybeAdjustEDDOrders
         * 'edd_orders', 'id', 'user_id'
         */
        $definitions['da44361031607ad8adde620daa77313419d8840e'] = [ 'edd_orders', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverEDDCompat::maybeAdjustEDDNotes
         * 'edd_notes', 'id', 'user_id'
         */
        $definitions['a1169afa9aec8b309370fb0ab5c67a21bf9f4678'] = [ 'edd_notes', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverEDDCompat::maybeAdjustEDDLogsApiRequests
         * 'edd_logs_api_requests', 'id', 'user_id'
         */
        $definitions['c7c76a8465929f09948858bf0e5a6c65ed4c36ab'] = [ 'edd_logs_api_requests', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverEDDCompat::maybeAdjustEDDLogs
         * 'edd_logs', 'id', 'user_id'
         */
        $definitions['cf08da33f037d95d785f05bd9e4d13d755d7a49d'] = [ 'edd_logs', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverEDDCompat::maybeAdjustEDDCustomers
         * 'edd_customers', 'id', 'user_id'
         */
        $definitions['428fa44b8faa4b046f4918f657151feb26b34e43'] = [ 'edd_customers', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverGamiPressCompat::maybeAdjustUserIdsGamiPressLogs
         * 'gamipress_logs', 'log_id', 'user_id'
         */
        $definitions['a875e76aec01eaae38ebc9f9c0bc4202a34a909b'] = [ 'gamipress_logs', 'log_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverGamiPressCompat::maybeAdjustUserIdsGamiPressEarnings
         * 'gamipress_user_earnings', 'user_earning_id', 'user_id'
         */
        $definitions['8be263bd2acab718f1fd3be3c9ad2e2fef649812'] = [ 'gamipress_user_earnings', 'user_earning_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverWpFusion::maybeAdjustUserIdsWpFusionLogs
         * 'wpf_logging', 'log_id', 'user'
         */
        $definitions['bcc3db04d1b70f3e0cc0a2cd5cf2ca388a2ca670'] = [ 'wpf_logging', 'log_id', 'user' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverLearnDash::maybeAdjustUserIdsInActivityTable
         * 'learndash_user_activity', 'activity_id', 'user_id'
         */
        $definitions['d1140f750df44c1696f89ee250534803368c3c92'] = [ 'learndash_user_activity', 'activity_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\extensions\PrimeMoverRelevanssi::maybeAdjustUserIdsLogTable
         * 'relevanssi_log', 'id', 'user_id'
         */
        $definitions['30cc4b7ec2e0968613923ec70f43f0a9de031fab'] = [ 'relevanssi_log', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsBpActivityTable
         * 'bp_activity', 'id', 'user_id'
         */
        $definitions['8750cc100edc792a7af2798fa84a246e324dc8e0'] = [ 'bp_activity', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustInitiatorUserIdsFriendsTable
         * 'bp_friends', 'id', 'initiator_user_id'
         */
        $definitions['7dec2e84f3656a2168119c0ace9181b9ce458d23'] = [ 'bp_friends', 'id', 'initiator_user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustFriendUserIdsFriendsTable
         * 'bp_friends', 'id', 'friend_user_id'
         */
        $definitions['29af3a0059ffb9f22ca528a4507218823a012dd6'] = [ 'bp_friends', 'id', 'friend_user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustCreatorIdsGroupTable
         * 'bp_groups', 'id', 'creator_id'
         */
        $definitions['dae3cf88011fabb8615512390a714a68cb66706c'] = [ 'bp_groups', 'id', 'creator_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsGroupMembers
         * 'bp_groups_members', 'id', 'user_id'
         */
        $definitions['4be36e0fc99e11f21e4bf7e05943748cb5ad3e5e'] = [ 'bp_groups_members', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustInviterIdsGroupMembers
         * 'bp_groups_members', 'id', 'inviter_id'
         */
        $definitions['1ae393a904be74380378c041f624988e046fdf27'] = [ 'bp_groups_members', 'id', 'inviter_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsInvitations
         * 'bp_invitations', 'id', 'user_id'
         */
        $definitions['c329c14e113d83792b042ded876853f2e62a45a1'] = [ 'bp_invitations', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustInviterIdsInvitations
         * 'bp_invitations', 'id', 'inviter_id'
         */
        $definitions['0fa06fa7cd97860dc8985fa207b4a98bb3b68016'] = [ 'bp_invitations', 'id', 'inviter_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustSenderIdsMessages
         * 'bp_messages_messages', 'id', 'sender_id'
         */
        $definitions['9ea43fc0422626302006df76809d23957d74667c'] = [ 'bp_messages_messages', 'id', 'sender_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsMessageRecipients
         * 'bp_messages_recipients', 'id', 'user_id'
         */
        $definitions['a795cd407006709ed817a7b6a337b31c33ce1e80'] = [ 'bp_messages_recipients', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsNotifications
         * 'bp_notifications', 'id', 'user_id'
         */
        $definitions['e5854c9dd67b1309ca92254ab7adc03b846a7bfe'] = [ 'bp_notifications', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsOptOut
         * 'bp_optouts', 'id', 'user_id'
         */
        $definitions['e75d767ebe5be97d956e12fcdc68ce74cc73f0c9'] = [ 'bp_optouts', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsUserBlogs
         * 'bp_user_blogs', 'id', 'user_id'
         */
        $definitions['ae4fd03011ff0eef135d0ac2caffa5c2e19223cf'] = [ 'bp_user_blogs', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsXprofileData
         * 'bp_xprofile_data', 'id', 'user_id'
         */
        $definitions['3680e174851541de5450b0859b4866786243700a'] = [ 'bp_xprofile_data', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdDocumentTable
         * 'bp_document', 'id', 'user_id'
         */
        $definitions['09bbfa84cc669415fe38e2ca45875136730ff55e'] = [ 'bp_document', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdDocumentFolder
         * 'bp_document_folder', 'id', 'user_id'
         */
        $definitions['fe1b49f4c98e7d5e6431d0e5f1700c6f3e2b596f'] = [ 'bp_document_folder', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsMediaTable
         * 'bp_media', 'id', 'user_id'
         */
        $definitions['656b0b93816f2aa39c98749b19f670ea4340670d'] = [ 'bp_media', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsMediaAlbum
         * 'bp_media_albums', 'id', 'user_id'
         */
        $definitions['1ff2d2cb29eb1444986f571b3bb16c9414df8f9d'] = [ 'bp_media_albums', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsModerationTable
         * 'bp_moderation', 'id', 'user_id'
         */
        $definitions['fd2a44a09005166933004e5f9e57079cd4131319'] = [ 'bp_moderation', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsSuspendDetails
         * 'bp_suspend_details', 'id', 'user_id'
         */
        $definitions['1e61730da1f24e0cf3c105c091e82eef7606293c'] = [ 'bp_suspend_details', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsZoomMeetings
         * 'bp_zoom_meetings', 'id', 'user_id'
         */
        $definitions['34979e2245a7cf46ee945130803c9a007a72a255'] = [ 'bp_zoom_meetings', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsZoomWebinars
         * 'bp_zoom_webinars', 'id', 'user_id'
         */
        $definitions['dc4739a3b5443efe250655a5db2cbc87f9bebe91'] = [ 'bp_zoom_webinars', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustUserIdsNotificationsSubscriptions
         * 'bb_notifications_subscriptions', 'id', 'user_id'
         */
        $definitions['4e92960960e5a1c5de103897b64d9c0801998bf9'] = [ 'bb_notifications_subscriptions', 'id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustNotificationsComponent
         * 'bp_notifications', 'id', 'item_id'
         */
        $definitions['a36684a15236495e518eee672d6bd45df64693c8'] = [ 'bp_notifications', 'id', 'item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustItemIdsActivityTable
         * 'bp_activity', 'id', 'item_id'
         */
        $definitions['380b02832cdeb6557e87d433bea350be172e933b'] = [ 'bp_activity', 'id', 'item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustSecondaryItemIdsActivityTable
         * 'bp_activity', 'id', 'secondary_item_id'
         */
        $definitions['45e37f84fef500c09d5227cc6ae92dce8cff3f53'] = [ 'bp_activity', 'id', 'secondary_item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustNotificationsSecondaryItem
         * 'bp_notifications', 'id', 'secondary_item_id'
         */
        $definitions['a0c8a2f9565576da85d1208b9dab5a3797db4b87'] = [ 'bp_notifications', 'id', 'secondary_item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustStarredMessages
         * 'bp_messages_meta', 'id', 'meta_value'
         */
        $definitions['3e756599e18ded32a39ec80af5e5a3bf642b4c49'] = [ 'bp_messages_meta', 'id', 'meta_value' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustSerializedIdsActivityMeta
         * 'bp_activity_meta', 'id', 'meta_value'
         */
        $definitions['524070c27d180fe3faaa9bc063adf424ab3ea82e'] = [ 'bp_activity_meta', 'id', 'meta_value' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustItemIdMembersComponent
         * 'bp_notifications', 'id', 'item_id'
         */
        $definitions['199461c50f0b24ba1235a9fcfefb4e2c75bddd3b'] = [ 'bp_notifications', 'id', 'item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustChangePasswordNotificationComponent
         * 'bp_notifications', 'id', 'secondary_item_id'
         */
        $definitions['1af04ba660d849db6c5029e416248d9e818beee3'] = [ 'bp_notifications', 'id', 'secondary_item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustSecondaryItemIdGroupsComponent
         * 'bp_notifications', 'id', 'secondary_item_id'
         */
        $definitions['c01a76068f4caf4fdf29744e6a257d8c467b98e8'] = [ 'bp_notifications', 'id', 'secondary_item_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverBuddyPressCompat::maybeAdjustBlogIdNotificationsSubscriptions
         * 'bb_notifications_subscriptions', 'id', 'blog_id'
         */
        $definitions['8e8fdf19a1d65b90c8cd55e8796bb736e413baee'] = [ 'bb_notifications_subscriptions', 'id', 'blog_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverMultilingualCompat::maybeAdjustStringTranslationTable
         * 'icl_string_translations', 'id', 'translator_id'
         */
        $definitions['9fea414fdb8934db164fb11a6267fca416b29905'] = [ 'icl_string_translations', 'id', 'translator_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverMultilingualCompat::maybeAdjustTranslationStatusTable
         * 'icl_translation_status', 'rid', 'translator_id'
         */
        $definitions['16c567548ceefc6eb6896f81f9a253d08fc1354a'] = [ 'icl_translation_status', 'rid', 'translator_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverMultilingualCompat::maybeAdjustTranslatorIdJobsTable
         * 'icl_translate_job', 'job_id', 'translator_id'
         */
        $definitions['bb37768e7f4f67c1740767c9142cdc7fb2cd456c'] = [ 'icl_translate_job', 'job_id', 'translator_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverMultilingualCompat::maybeAdjustManagerIdJobsTable
         * 'icl_translate_job', 'job_id', 'manager_id'
         */
        $definitions['293c4018e54a74996c6f18097fd04d4f4bc18e38'] = [ 'icl_translate_job', 'job_id', 'manager_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsCustomerLookup
         * 'wc_customer_lookup', 'customer_id', 'user_id'
         */
        $definitions['9b7d0f710b47e14f495851741cc0aac377501318'] = [ 'wc_customer_lookup', 'customer_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsDownloadPermissions
         * 'woocommerce_downloadable_product_permissions', 'permission_id', 'user_id'
         */
        $definitions['14312c0238fc86d10b0041a84288f7674f9346be'] = [ 'woocommerce_downloadable_product_permissions', 'permission_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsDownloadLog
         * 'wc_download_log', 'download_log_id', 'user_id'
         */
        $definitions['a83e976602504c1e7c68889311420184a4974ab1'] = [ 'wc_download_log', 'download_log_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsHposOrders
         * 'wc_orders', 'id', 'customer_id'
         */
        $definitions['c3e4cf3eb549da99b8c887576c6c3916373dddd6'] = [ 'wc_orders', 'id', 'customer_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsApiKeys
         * 'woocommerce_api_keys', 'key_id', 'user_id'
         */
        $definitions['00e4f69f55ceff6699ec844bae381af02b715276'] = [ 'woocommerce_api_keys', 'key_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsWebHooks
         * 'wc_webhooks', 'webhook_id', 'user_id'
         */
        $definitions['168952df1168def22d3120a498f3043d0c6fc4d0'] = [ 'wc_webhooks', 'webhook_id', 'user_id' ];
        /**
         * Codexonics\PrimeMoverFramework\compatibility\PrimeMoverWooCommerceCompat::maybeAdjustUserIdsPaymentTokens
         * 'woocommerce_payment_tokens', 'token_id', 'user_id'
         */
        $definitions['f32ce291bb131670f4cbc40fc893083f0c354735'] = [ 'woocommerce_payment_tokens', 'token_id', 'user_id' ];
        return $definitions;
    }

}
if ( !function_exists( 'primeMoverAutoDeactivatePlugin' ) ) {
    function primeMoverAutoDeactivatePlugin()
    {
        
        if ( defined( 'PRIME_MOVER_MAINPLUGIN_FILE' ) ) {
            $input_get = filter_input_array( INPUT_GET, array(
                'activate' => FILTER_VALIDATE_BOOLEAN,
            ) );
            
            if ( isset( $input_get['activate'] ) ) {
                unset( $_GET['activate'] );
                $_GET['deactivate'] = true;
            }
            
            $plugin_basename = plugin_basename( PRIME_MOVER_MAINPLUGIN_FILE );
            deactivate_plugins( $plugin_basename );
        }
    
    }

}