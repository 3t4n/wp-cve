<?php
defined( 'ABSPATH' ) || exit;
class Better_Messages_Options
{
    protected  $path ;
    public  $settings ;
    public $defaults;

    public static function instance()
    {
        static  $instance = null ;

        if ( null === $instance ) {
            $instance = new Better_Messages_Options();
            $instance->setup_globals();
            $instance->setup_actions();
        }

        return $instance;
    }

    public function setup_globals()
    {
        $this->path = Better_Messages()->path . '/views/';
        $this->defaults = array(
            'mechanism'                   => 'ajax',
            'template'                    => 'modern',
            'thread_interval'             => 3,
            'site_interval'               => 10,
            'attachmentsFormats'          => array(),
            'attachmentsRetention'        => 365,
            'attachmentsEnable'           => '0',
            'attachmentsHide'             => '1',
            'attachmentsRandomName'       => '1',
            'attachmentsMaxSize'          => wp_max_upload_size() / 1024 / 1024,
            'attachmentsMaxNumber'        => 0,
            'miniChatsEnable'             => '0',
            'searchAllUsers'              => '1',
            'disableSubject'              => '0',
            'disableEnterForTouch'        => '1',
            'autoFullScreen'              => '1',
            'tapToOpenMsg'                => '1',
            'mobilePopup'                 => '0',
            'mobileFullScreen'            => '1',
            'chatPage'                    => '0',
            'messagesStatus'              => '0',
            'messagesStatusList'          => '0',
            'messagesStatusDetailed'      => '0',
            'allowDeleteMessages'         => '0',
            'deleteMethod'                => 'delete',
            'fastStart'                   => '1',
            'miniThreadsEnable'           => '0',
            'miniFriendsEnable'           => '0',
            'friendsMode'                 => '0',
            'singleThreadMode'            => '0',
            'newThreadMode'               => '0',
            'disableGroupThreads'         => '0',
            'oEmbedEnable'                => '1',
            'disableEnterForDesktop'      => '0',
            'rateLimitReply'              => [],
            'rateLimitReplyMessage'       => __( 'Your limit for replies is exceeded', 'bp-better-messages' ),
            'restrictNewThreads'          => [],
            'restrictNewThreadsMessage'   => __( 'You are not allowed to start new conversations', 'bp-better-messages' ),
            'restrictBadWordsList'        => __( 'Your message contains a word from blacklist', 'bp-better-messages' ),
            'restrictNewThreadsRemoveNewThreadButton' => '0',
            'restrictNewReplies'          => [],
            'restrictNewRepliesMessage'   => __( 'You are not allowed to continue conversation', 'bp-better-messages' ),
            'restrictCalls'               => [],
            'restrictCallsMessage'        => __( 'You are not allowed to make a call', 'bp-better-messages' ),
            'restrictViewMessages'        => [],
            'restrictViewMessagesMessage' => __( 'Message hidden', 'bp-better-messages' ),
            'restrictViewMiniThreads'     => [],
            'restrictViewMiniFriends'     => [],
            'restrictViewMiniGroups'      => [],
            'restrictMobilePopup'         => [],
            'videoCalls'                  => '0',
            'audioCalls'                  => '0',
            'userListButton'              => '0',
            'UMuserListButton'            => '1',
            'combinedView'                => '1',
            'enablePushNotifications'     => '0',
            'colorGeneral'                => '#21759b',
            'encryptionEnabled'           => '1',
            'encryptionLocal'             => '0',
            'stipopApiKey'                => '',
            'stipopLanguage'              => 'en',
            'allowMuteThreads'            => '1',
            'callsRevertIcons'            => '0',
            'callRequestTimeLimit'        => '30',
            'offlineCallsNotifications'   => '0',
            'callsLimitFriends'           => '0',
            'stopBPNotifications'         => '0',
            'restrictThreadsDeleting'     => '0',
            'disableFavoriteMessages'     => '0',
            'disableSearch'               => '0',
            'disableUserSettings'         => '0',
            'disableNewThread'            => '0',
            'profileVideoCall'            => '0',
            'profileAudioCall'            => '0',
            'miniChatAudioCall'           => '0',
            'miniChatVideoCall'           => '0',
            'disableUsersSearch'          => '0',
            'fixedHeaderHeight'           => '0',
            'mobilePopupLocationBottom'   => 20,
            'rateLimitNewThread'          => 0,
            'notificationsInterval'       => 15,
            'disableOnSiteNotification'   => '0',
            'allowSoundDisable'           => '1',
            'enableGroups'                => '0',
            'enableMiniGroups'            => '0',
            'allowGroupLeave'             => '0',
            'giphyApiKey'                 => '',
            'giphyContentRating'          => 'g',
            'giphyLanguage'               => 'en',
            'enableReplies'               => '1',
            'messagesMinHeight'           => 450,
            'messagesHeight'              => 650,
            'sideThreadsWidth'            => 320,
            'notificationSound'           => 100,
            'sentSound'                   => 50,
            'callSound'                   => 100,
            'dialingSound'                => 50,
            'modernLayout'                => 'left',
            'deletedBehaviour'            => 'ignore',
            'allowEditMessages'           => '0',
            'enableNiceLinks'             => '1',
            'userStatuses'                => '0',
            'myProfileButton'             => '1',
            'titleNotifications'          => '1',
            'enableMiniCloseButton'       => '0',
            'bpProfileSlug'               => 'bp-messages',
            'bpGroupSlug'                 => 'bp-messages',
            'mobilePopupLocation'         => 'right',
            'mobileOnsiteLocation'        => 'auto',
            'badWordsList'                => '',
            'groupCallsGroups'            => '0',
            'groupCallsThreads'           => '0',
            'groupCallsChats'             => '0',
            'groupAudioCallsGroups'       => '0',
            'groupAudioCallsThreads'      => '0',
            'groupAudioCallsChats'        => '0',
            'allowUsersRestictNewThreads' => '0',
            'enableGroupsEmails'          => '1',
            'enableGroupsPushs'           => '0',
            'desktopFullScreen'           => '1',
            'restrictRoleBlock'           => [],
            'restrictRoleType'            => 'allow',
            'restrictRoleMessage'         => esc_attr_x('You are not allowed to send messages', 'Settings page', 'bp-better-messages'),
            'friendsOnSiteNotifications'  => '0',
            'groupsOnSiteNotifications'   => '0',
            'enableUsersSuggestions'      => '1',
            'hidePossibleBreakingElements'  => '0',

            'myCredNewMessageCharge'        => [],
            'myCredNewMessageChargeMessage' => _x( 'Not enough points to send a new message.', 'Settings page', 'bp-better-messages' ),
            'myCredNewThreadCharge'         => [],
            'myCredNewThreadChargeMessage'  => _x( 'Not enough points to start a new conversation.', 'Settings page', 'bp-better-messages' ),
            'myCredCallPricing'         => [],
            'myCredCallPricingStartMessage'  => _x( 'Not enough points to start new call', 'Settings page', 'bp-better-messages' ),
            'myCredCallPricingEndMessage'    => _x( 'Not enough points to continue the call', 'Settings page', 'bp-better-messages' ),

            'GamiPressNewMessageCharge'        => [],
            'GamiPressNewMessageChargeMessage' => _x( 'Not enough points to send a new message.', 'Settings page', 'bp-better-messages' ),
            'GamiPressNewThreadCharge'         => [],
            'GamiPressNewThreadChargeMessage'  => _x( 'Not enough points to start a new conversation.', 'Settings page', 'bp-better-messages' ),
            'GamiPressCallPricing'             => [],
            'GamiPressCallPricingStartMessage' => _x( 'Not enough points to start new call', 'Settings page', 'bp-better-messages' ),
            'GamiPressCallPricingEndMessage'   => _x( 'Not enough points to continue the call', 'Settings page', 'bp-better-messages' ),

            'createEmailTemplate'           => '1',
            'notificationsOfflineDelay'     => 15,
            'bbPressAuthorDetailsLink'      => '0',
            'enableGroupsFiles'             => '0',
            'combinedFriendsEnable'         => '0',
            'mobileFriendsEnable'           => '0',
            'combinedGroupsEnable'          => '0',
            'mobileGroupsEnable'            => '0',
            'umProfilePMButton'             => '1',
            'umOnlyFriendsMode'             => '0',
            'umOnlyFollowersMode'           => '0',
            'allowUsersBlock'               => '0',
            'restrictBlockUsers'            => [],
            'restrictBlockUsersImmun'       => [],
            'messagesViewer'                => '1',
            'offlineCallsAllowed'           => '0',
            'enableReactions'               => '1',
            'enableReactionsPopup'          => '1',
            'peepsoHeader'                  => '1',
            'peepsoProfileVideoCall'        => '0',
            'peepsoProfileAudioCall'        => '0',
            'UMminiFriendsEnable'           => '0',
            'UMcombinedFriendsEnable'       => '0',
            'UMmobileFriendsEnable'         => '0',

            'PSonlyFriendsMode'             => '0',
            'PSminiFriendsEnable'           => '0',
            'PScombinedFriendsEnable'       => '0',
            'PSmobileFriendsEnable'         => '0',
            'PSenableGroups'                => '0',
            'PSenableGroupsFiles'           => '0',
            'PSenableGroupsEmails'          => '0',
            'PSenableGroupsPushs'           => '0',
            'PSminiGroupsEnable'            => '0',
            'PScombinedGroupsEnable'        => '0',
            'PSmobileGroupsEnable'          => '0',
            'UMenableGroups'                => '0',
            'UMenableGroupsFiles'           => '0',
            'UMenableGroupsEmails'          => '0',
            'UMenableGroupsPushs'          => '0',
            'UMminiGroupsEnable'            => '0',
            'UMcombinedGroupsEnable'        => '0',
            'UMmobileGroupsEnable'          => '0',
            'privateThreadInvite'           => '0',
            'reactionsEmojies'              => Better_Messages_Reactions::get_default_reactions(),
            'bpForceMiniChat'               => '0',
            'umForceMiniChat'               => '0',
            'psForceMiniChat'               => '0',
            'emojiSet'                      => 'apple',
            'attachmentsAllowPhoto'         => '0',
            'onsitePosition'                => 'right',
            'bpFallback'                    => '0',
            'miniChatDisableSync'           => '0',
            'pinnedThreads'                 => '1',
            'bpAppPush'                     => '0',
            'guestChat'                     => '0',
            'callQuality'                   => '1080',
            'deleteMessagesOnUserDelete'    => '0',
            'dokanIntegration'              => '0',
            'MultiVendorXIntegration'       => '0',
            'jetEngineAvatars'              => '0',
            'hivepressIntegration'          => '0',
            'wpJobManagerIntegration'       => '0',
            'pinnedMessages'                => '0'
        );

        $args = get_option( 'bp-better-chat-settings', array() );

        if ( ! Better_Messages()->functions->can_use_premium_code() || ! bpbm_fs()->is_premium() ) {
            $args['mechanism'] = 'ajax';
            $args['miniChatsEnable'] = '0';
            $args['messagesStatus'] = '0';
            $args['messagesStatusList'] = '0';
            $args['messagesStatusDetailed'] = '0';
            $args['miniThreadsEnable'] = '0';
            $args['videoCalls'] = '0';
            $args['audioCalls'] = '0';
            $args['encryptionEnabled'] = '0';
            $args['encryptionLocal'] = '0';
            $args['userStatuses'] = '0';
        }

        if( isset($args['callQuality']) && ! in_array($args['callQuality'], [ '360', '540', '720', '1080' ]) ){
            $args['callQuality'] = '1080';
        }

        if( Better_Messages()->functions->can_use_premium_code() && bpbm_fs()->is_premium() ){
            $args['mechanism'] = 'websocket';
            $args['encryptionEnabled'] = '1';
        }

        if( ! is_admin() && current_user_can( 'manage_options') ){
            $args['disableUsersSearch'] = '0';
        }

        if( isset($args['disableUsersSearch']) && $args['disableUsersSearch'] === '1' ){
            $args['searchAllUsers'] = '0';
            $args['enableUsersSuggestions'] = '0';
        }

        $this->settings = wp_parse_args( $args, $this->defaults );
    }

    public function setup_actions()
    {
        add_action( 'admin_menu', array( $this, 'settings_page' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'add_color_picker' ) );
    }

    /**
     * Settings page
     */
    public function settings_page()
    {
        add_menu_page(
            __( 'Better Messages' ),
            _x( 'Better Messages', 'WP Admin', 'bp-better-messages' ),
            'manage_options',
            'bp-better-messages',
            array( $this, 'settings_page_html' ),
            'dashicons-format-chat'
        );

        add_submenu_page(
            'bp-better-messages',
            _x( 'Settings', 'WP Admin', 'bp-better-messages' ),
            _x( 'Settings', 'WP Admin', 'bp-better-messages' ),
            'manage_options',
            'bp-better-messages',
            array( $this, 'settings_page_html' ),
            0
        );

        if( ! defined('BM_DISABLE_MESSAGES_VIEWER') && Better_Messages()->settings['messagesViewer'] !== '0' ) {
            add_submenu_page(
                'bp-better-messages',
                _x('Administration', 'WP Admin', 'bp-better-messages'),
                _x('Administration', 'WP Admin', 'bp-better-messages'),
                'manage_options',
                'better-messages-viewer',
                array($this, 'viewer_page_html'),
                10
            );
        }

        /*add_submenu_page(
            'bp-better-messages',
            _x('System', 'WP Admin', 'bp-better-messages'),
            _x('System', 'WP Admin', 'bp-better-messages'),
            'manage_options',
            'better-messages-system',
            array($this, 'system_page_html'),
            5
        ); */

        /*add_submenu_page(
            'bp-better-messages',
            _x('Moderation', 'Admin Menu', 'bp-better-messages'),
            _x('Moderation', 'Admin Menu', 'bp-better-messages'),
            'manage_options',
            'better-messages-moderation',
            array($this, 'moderation_page_html'),
            2
        );*/
    }

    public function add_color_picker( $hook )
    {

        if ( $hook === 'toplevel_page_bp-better-messages' && is_admin() ) {
            wp_enqueue_script( 'jquery-ui-sortable' );
        }

    }

    public function settings_page_html()
    {
        wp_enqueue_script( 'jquery-ui-sortable' );

        if ( isset( $_POST['_wpnonce'] ) && !empty($_POST['_wpnonce']) && wp_verify_nonce( $_POST['_wpnonce'], 'bp-better-messages-settings' ) ) {
            unset( $_POST['_wpnonce'], $_POST['_wp_http_referer'] );

            if ( isset( $_POST['save'] ) ) {
                unset( $_POST['save'] );
                $this->update_settings( $_POST );
            }
        }

        include $this->path . 'layout-settings.php';
    }

    public function viewer_page_html(){
        include $this->path . 'layout-viewer-v2.php';
    }

    public function system_page_html(){
        include $this->path . 'layout-system.php';
    }

    public function moderation_page_html(){
        include $this->path . 'layout-moderation.php';
    }

    public function update_settings( $settings )
    {
        if( isset( $settings['emojiSettings'] ) && ! empty( trim($settings['emojiSettings']) ) ){
            $emojies = json_decode( wp_unslash($settings['emojiSettings']), true );
            update_option( 'bm-emoji-set-2', $emojies );
            update_option( 'bm-emoji-hash', hash('md5', json_encode($emojies) ) );
            unset($settings['emojiSettings']);
        }

        if ( !isset( $settings['PSminiGroupsEnable'] ) ) {
            $settings['PSminiGroupsEnable'] = '0';
        }
        if ( !isset( $settings['PScombinedGroupsEnable'] ) ) {
            $settings['PScombinedGroupsEnable'] = '0';
        }
        if ( !isset( $settings['PSmobileGroupsEnable'] ) ) {
            $settings['PSmobileGroupsEnable'] = '0';
        }

        if ( !isset( $settings['UMminiGroupsEnable'] ) ) {
            $settings['UMminiGroupsEnable'] = '0';
        }
        if ( !isset( $settings['UMcombinedGroupsEnable'] ) ) {
            $settings['UMcombinedGroupsEnable'] = '0';
        }
        if ( !isset( $settings['UMmobileGroupsEnable'] ) ) {
            $settings['UMmobileGroupsEnable'] = '0';
        }
        if ( !isset( $settings['privateThreadInvite'] ) ) {
            $settings['privateThreadInvite'] = '0';
        }
        if ( !isset( $settings['PSonlyFriendsMode'] ) ) {
            $settings['PSonlyFriendsMode'] = '0';
        }
        if ( !isset( $settings['PSminiFriendsEnable'] ) ) {
            $settings['PSminiFriendsEnable'] = '0';
        }
        if ( !isset( $settings['PScombinedFriendsEnable'] ) ) {
            $settings['PScombinedFriendsEnable'] = '0';
        }
        if ( !isset( $settings['PSmobileFriendsEnable'] ) ) {
            $settings['PSmobileFriendsEnable'] = '0';
        }

        if ( !isset( $settings['PSenableGroups'] ) ) {
            $settings['PSenableGroups'] = '0';
        }
        if ( !isset( $settings['PSenableGroupsFiles'] ) ) {
            $settings['PSenableGroupsFiles'] = '0';
        }
        if ( !isset( $settings['PSenableGroupsEmails'] ) ) {
            $settings['PSenableGroupsEmails'] = '0';
        }

        if ( !isset( $settings['PSenableGroupsPushs'] ) ) {
            $settings['PSenableGroupsPushs'] = '0';
        }

        if ( !isset( $settings['UMenableGroups'] ) ) {
            $settings['UMenableGroups'] = '0';
        }
        if ( !isset( $settings['UMenableGroupsFiles'] ) ) {
            $settings['UMenableGroupsFiles'] = '0';
        }
        if ( !isset( $settings['UMenableGroupsEmails'] ) ) {
            $settings['UMenableGroupsEmails'] = '0';
        }

        if ( !isset( $settings['UMenableGroupsPushs'] ) ) {
            $settings['UMenableGroupsPushs'] = '0';
        }

        if ( !isset( $settings['UMminiFriendsEnable'] ) ) {
            $settings['UMminiFriendsEnable'] = '0';
        }
        if ( !isset( $settings['UMcombinedFriendsEnable'] ) ) {
            $settings['UMcombinedFriendsEnable'] = '0';
        }
        if ( !isset( $settings['UMmobileFriendsEnable'] ) ) {
            $settings['UMmobileFriendsEnable'] = '0';
        }


        if ( !isset( $settings['attachmentsEnable'] ) ) {
            $settings['attachmentsEnable'] = '0';
        }
        if ( !isset( $settings['attachmentsHide'] ) ) {
            $settings['attachmentsHide'] = '0';
        }
        if ( !isset( $settings['attachmentsRandomName'] ) ) {
            $settings['attachmentsRandomName'] = '0';
        }
        if ( !isset( $settings['miniChatsEnable'] ) ) {
            $settings['miniChatsEnable'] = '0';
        }
        if ( !isset( $settings['searchAllUsers'] ) ) {
            $settings['searchAllUsers'] = '0';
        }
        if ( !isset( $settings['disableSubject'] ) ) {
            $settings['disableSubject'] = '0';
        }
        if ( !isset( $settings['disableEnterForTouch'] ) ) {
            $settings['disableEnterForTouch'] = '0';
        }
        if ( !isset( $settings['mobileFullScreen'] ) ) {
            $settings['mobileFullScreen'] = '0';
        }
        if ( !isset( $settings['messagesStatus'] ) ) {
            $settings['messagesStatus'] = '0';
        }
        if ( !isset( $settings['messagesStatusList'] ) ) {
            $settings['messagesStatusList'] = '0';
        }
        if ( !isset( $settings['messagesStatusDetailed'] ) ) {
            $settings['messagesStatusDetailed'] = '0';
        }
        if ( !isset( $settings['allowDeleteMessages'] ) ) {
            $settings['allowDeleteMessages'] = '0';
        }
        if ( !isset( $settings['fastStart'] ) ) {
            $settings['fastStart'] = '0';
        }
        if ( !isset( $settings['miniFriendsEnable'] ) ) {
            $settings['miniFriendsEnable'] = '0';
        }
        if ( !isset( $settings['miniThreadsEnable'] ) ) {
            $settings['miniThreadsEnable'] = '0';
        }
        if ( !isset( $settings['friendsMode'] ) ) {
            $settings['friendsMode'] = '0';
        }
        if ( !isset( $settings['singleThreadMode'] ) ) {
            $settings['singleThreadMode'] = '0';
        }
        if ( !isset( $settings['newThreadMode'] ) ) {
            $settings['newThreadMode'] = '0';
        }
        if ( !isset( $settings['disableGroupThreads'] ) ) {
            $settings['disableGroupThreads'] = '0';
        }
        if ( !isset( $settings['mobilePopup'] ) ) {
            $settings['mobilePopup'] = '0';
        }
        if ( !isset( $settings['autoFullScreen'] ) ) {
            $settings['autoFullScreen'] = '0';
        }
        if ( !isset( $settings['tapToOpenMsg'] ) ) {
            $settings['tapToOpenMsg'] = '0';
        }
        if ( !isset( $settings['oEmbedEnable'] ) ) {
            $settings['oEmbedEnable'] = '0';
        }
        if ( !isset( $settings['disableEnterForDesktop'] ) ) {
            $settings['disableEnterForDesktop'] = '0';
        }
        if ( !isset( $settings['restrictNewThreads'] ) ) {
            $settings['restrictNewThreads'] = [];
        }
        if ( !isset( $settings['restrictBlockUsers'] ) ) {
            $settings['restrictBlockUsers'] = [];
        }
        if ( !isset( $settings['restrictBlockUsersImmun'] ) ) {
            $settings['restrictBlockUsersImmun'] = [];
        }
        if ( !isset( $settings['restrictNewReplies'] ) ) {
            $settings['restrictNewReplies'] = [];
        }
        if ( !isset( $settings['restrictCalls'] ) ) {
            $settings['restrictCalls'] = [];
        }
        if ( !isset( $settings['restrictViewMessages'] ) ) {
            $settings['restrictViewMessages'] = [];
        }
        if ( !isset( $settings['restrictViewMiniThreads'] ) ) {
            $settings['restrictViewMiniThreads'] = [];
        }
        if ( !isset( $settings['restrictRoleBlock'] ) ) {
            $settings['restrictRoleBlock'] = [];
        }
        if ( !isset( $settings['restrictViewMiniFriends'] ) ) {
            $settings['restrictViewMiniFriends'] = [];
        }
        if ( !isset( $settings['restrictViewMiniGroups'] ) ) {
            $settings['restrictViewMiniGroups'] = [];
        }
        if ( !isset( $settings['restrictMobilePopup'] ) ) {
            $settings['restrictMobilePopup'] = [];
        }
        if ( !isset( $settings['videoCalls'] ) ) {
            $settings['videoCalls'] = '0';
        }
        if ( !isset( $settings['audioCalls'] ) ) {
            $settings['audioCalls'] = '0';
        }
        if ( !isset( $settings['userListButton'] ) ) {
            $settings['userListButton'] = '0';
        }
        if ( !isset( $settings['UMuserListButton'] ) ) {
            $settings['UMuserListButton'] = '0';
        }
        if ( !isset( $settings['combinedView'] ) ) {
            $settings['combinedView'] = '0';
        }
        if ( !isset( $settings['enablePushNotifications'] ) ) {
            $settings['enablePushNotifications'] = '0';
        }
        if ( !isset( $settings['allowMuteThreads'] ) ) {
            $settings['allowMuteThreads'] = '0';
        }
        if ( !isset( $settings['callsRevertIcons'] ) ) {
            $settings['callsRevertIcons'] = '0';
        }
        if ( !isset( $settings['callRequestTimeLimit'] ) ) {
            $settings['callRequestTimeLimit'] = '30';
        }
        if ( !isset( $settings['fixedHeaderHeight'] ) ) {
            $settings['fixedHeaderHeight'] = '0';
        }
        if ( !isset( $settings['mobilePopupLocationBottom'] ) ) {
            $settings['mobilePopupLocationBottom'] = '0';
        }
        if ( !isset( $settings['offlineCallsNotifications'] ) ) {
            $settings['offlineCallsNotifications'] = '0';
        }
        if ( !isset( $settings['callsLimitFriends'] ) ) {
            $settings['callsLimitFriends'] = '0';
        }
        if ( !isset( $settings['stopBPNotifications'] ) ) {
            $settings['stopBPNotifications'] = '0';
        }
        if ( !isset( $settings['restrictThreadsDeleting'] ) ) {
            $settings['restrictThreadsDeleting'] = '0';
        }
        if ( !isset( $settings['disableFavoriteMessages'] ) ) {
            $settings['disableFavoriteMessages'] = '0';
        }
        if ( !isset( $settings['disableSearch'] ) ) {
            $settings['disableSearch'] = '0';
        }
        if ( !isset( $settings['disableUserSettings'] ) ) {
            $settings['disableUserSettings'] = '0';
        }
        if ( !isset( $settings['disableNewThread'] ) ) {
            $settings['disableNewThread'] = '0';
        }
        if ( !isset( $settings['profileVideoCall'] ) ) {
            $settings['profileVideoCall'] = '0';
        }
        if ( !isset( $settings['profileAudioCall'] ) ) {
            $settings['profileAudioCall'] = '0';
        }
        if ( !isset( $settings['peepsoProfileVideoCall'] ) ) {
            $settings['peepsoProfileVideoCall'] = '0';
        }
        if ( !isset( $settings['peepsoProfileAudioCall'] ) ) {
            $settings['peepsoProfileAudioCall'] = '0';
        }
        if ( !isset( $settings['miniChatAudioCall'] ) ) {
            $settings['miniChatAudioCall'] = '0';
        }
        if ( !isset( $settings['miniChatVideoCall'] ) ) {
            $settings['miniChatVideoCall'] = '0';
        }
        if ( !isset( $settings['disableUsersSearch'] ) ) {
            $settings['disableUsersSearch'] = '0';
        }
        if ( !isset( $settings['disableOnSiteNotification'] ) ) {
            $settings['disableOnSiteNotification'] = '0';
        }
        if ( !isset( $settings['allowSoundDisable'] ) ) {
            $settings['allowSoundDisable'] = '0';
        }

        if ( !isset( $settings['enableGroups'] ) ) {
            $settings['enableGroups'] = '0';
        }

        if ( !isset( $settings['enableMiniGroups'] ) ) {
            $settings['enableMiniGroups'] = '0';
        }

        if ( !isset( $settings['allowGroupLeave'] ) ) {
            $settings['allowGroupLeave'] = '0';
        }

        if ( !isset( $settings['enableReplies'] ) ) {
            $settings['enableReplies'] = '0';
        }

        if ( !isset( $settings['allowEditMessages'] ) ) {
            $settings['allowEditMessages'] = '0';
        }

        if ( !isset( $settings['enableNiceLinks'] ) ) {
            $settings['enableNiceLinks'] = '0';
        }

        if ( !isset( $settings['userStatuses'] ) ) {
            $settings['userStatuses'] = '0';
        }

        if ( !isset( $settings['myProfileButton'] ) ) {
            $settings['myProfileButton'] = '0';
        }

        if ( !isset( $settings['titleNotifications'] ) ) {
            $settings['titleNotifications'] = '0';
        }

        if ( !isset( $settings['restrictNewThreadsRemoveNewThreadButton'] ) ) {
            $settings['restrictNewThreadsRemoveNewThreadButton'] = '0';
        }

        if ( !isset( $settings['enableMiniCloseButton'] ) ) {
            $settings['enableMiniCloseButton'] = '0';
        }

        if( ! isset( $settings['groupCallsGroups'] ) ){
            $settings['groupCallsGroups'] = '0';
        }

        if( ! isset( $settings['groupCallsThreads'] ) ){
            $settings['groupCallsThreads'] = '0';
        }

        if( ! isset( $settings['groupCallsChats'] ) ){
            $settings['groupCallsChats'] = '0';
        }

        if( ! isset( $settings['groupAudioCallsGroups'] ) ){
            $settings['groupAudioCallsGroups'] = '0';
        }

        if( ! isset( $settings['groupAudioCallsThreads'] ) ){
            $settings['groupAudioCallsThreads'] = '0';
        }

        if( ! isset( $settings['groupAudioCallsChats'] ) ){
            $settings['groupAudioCallsChats'] = '0';
        }

        if( ! isset( $settings['allowUsersRestictNewThreads'] ) ){
            $settings['allowUsersRestictNewThreads'] = '0';
        }

        if( ! isset( $settings['enableGroupsEmails'] ) ){
            $settings['enableGroupsEmails'] = '0';
        }

        if( ! isset( $settings['enableGroupsPushs'] ) ){
            $settings['enableGroupsPushs'] = '0';
        }

        if( ! isset( $settings['desktopFullScreen'] ) ){
            $settings['desktopFullScreen'] = '0';
        }

        if( ! isset( $settings['friendsOnSiteNotifications'] ) ){
            $settings['friendsOnSiteNotifications'] = '0';
        }

        if( ! isset( $settings['groupsOnSiteNotifications'] ) ){
            $settings['groupsOnSiteNotifications'] = '0';
        }

        if( ! isset( $settings['enableUsersSuggestions'] ) ){
            $settings['enableUsersSuggestions'] = '0';
        }

        if( ! isset( $settings['hidePossibleBreakingElements'] ) ){
            $settings['hidePossibleBreakingElements'] = '0';
        }

        if( ! isset( $settings['createEmailTemplate'] ) ){
            $settings['createEmailTemplate'] = '0';
        }

        if( ! isset( $settings['bbPressAuthorDetailsLink'] ) ){
            $settings['bbPressAuthorDetailsLink'] = '0';
        }

        if( ! isset( $settings['enableGroupsFiles'] ) ){
            $settings['enableGroupsFiles'] = '0';
        }

        if( ! isset( $settings['combinedFriendsEnable'] ) ){
            $settings['combinedFriendsEnable'] = '0';
        }

        if( ! isset( $settings['combinedGroupsEnable'] ) ){
            $settings['combinedGroupsEnable'] = '0';
        }

        if( ! isset( $settings['mobileFriendsEnable'] ) ){
            $settings['mobileFriendsEnable'] = '0';
        }

        if( ! isset( $settings['mobileGroupsEnable'] ) ){
            $settings['mobileGroupsEnable'] = '0';
        }

        if( ! isset( $settings['umProfilePMButton'] ) ){
            $settings['umProfilePMButton'] = '0';
        }

        if( ! isset( $settings['umOnlyFriendsMode'] ) ){
            $settings['umOnlyFriendsMode'] = '0';
        }

        if( ! isset( $settings['umOnlyFollowersMode'] ) ){
            $settings['umOnlyFollowersMode'] = '0';
        }

        if( ! isset( $settings['allowUsersBlock'] ) ){
            $settings['allowUsersBlock'] = '0';
        }

        if( ! isset( $settings['messagesViewer'] ) ) {
            $settings['messagesViewer'] = '0';
        }

        if( ! isset( $settings['offlineCallsAllowed'] ) ) {
            $settings['offlineCallsAllowed'] = '0';
        }

        if( ! isset( $settings['enableReactions'] ) ) {
            $settings['enableReactions'] = '0';
        }

        if( ! isset( $settings['enableReactionsPopup'] ) ) {
            $settings['enableReactionsPopup'] = '0';
        }

        if( ! isset( $settings['peepsoHeader'] ) ) {
            $settings['peepsoHeader'] = '0';
        }

        if( ! isset( $settings['bpForceMiniChat'] ) ) {
            $settings['bpForceMiniChat'] = '0';
        }

        if( ! isset( $settings['umForceMiniChat'] ) ) {
            $settings['umForceMiniChat'] = '0';
        }

        if( ! isset( $settings['psForceMiniChat'] ) ) {
            $settings['psForceMiniChat'] = '0';
        }

        if( ! isset( $settings['attachmentsAllowPhoto'] ) ) {
            $settings['attachmentsAllowPhoto'] = '0';
        }

        if( ! isset( $settings['bpFallback'] ) ) {
            $settings['bpFallback'] = '0';
        }

        if( ! isset( $settings['miniChatDisableSync'] ) ) {
            $settings['miniChatDisableSync'] = '0';
        }

        if( ! isset( $settings['pinnedThreads'] ) ) {
            $settings['pinnedThreads'] = '0';
        }

        if( ! isset( $settings['bpAppPush'] ) ) {
            $settings['bpAppPush'] = '0';
        }

        if( ! isset( $settings['guestChat'] ) ) {
            $settings['guestChat'] = '0';
        }

        if( ! isset( $settings['dokanIntegration'] ) ) {
            $settings['dokanIntegration'] = '0';
        }

        if( ! isset( $settings['MultiVendorXIntegration'] ) ) {
            $settings['MultiVendorXIntegration'] = '0';
        }

        if( ! isset( $settings['jetEngineAvatars'] ) ) {
            $settings['jetEngineAvatars'] = '0';
        }

        if( ! isset( $settings['hivepressIntegration'] ) ) {
            $settings['hivepressIntegration'] = '0';
        }

        if( ! isset( $settings['wpJobManagerIntegration'] ) ) {
            $settings['wpJobManagerIntegration'] = '0';
        }

        if( ! isset( $settings['deleteMessagesOnUserDelete'] ) ){
            $settings['deleteMessagesOnUserDelete'] = '0';
        }

        if( ! isset( $settings['encryptionLocal'] ) ) {
            $settings['encryptionLocal'] = '0';
        }

        if( ! isset( $settings['deleteMethod'] ) || $settings['deleteMethod'] !== 'replace' ) {
            $settings['deleteMethod'] = 'delete';
        }

        if( ! isset( $settings['pinnedMessages'] ) ) {
            $settings['pinnedMessages'] = '0';
        }

        if( ! isset( $settings['callQuality'] ) || ! in_array($settings['callQuality'], [ '360', '540', '720', '1080' ]) ) {
            $settings['callQuality'] = '1080';
        }

        if( ! isset( $settings['restrictRoleType'] ) || $settings['restrictRoleType'] !== 'disallow' ) {
            $settings['restrictRoleType'] = 'allow';
        }

        $links_allowed = [
            'restrictBadWordsList',
            'restrictCallsMessage',
            'restrictNewThreadsMessage',
            'restrictNewRepliesMessage',
            'restrictViewMessagesMessage',
            'rateLimitReplyMessage',
            'myCredNewMessageChargeMessage',
            'restrictRoleMessage',
            'myCredNewThreadChargeMessage',
            'myCredCallPricingStartMessage',
            'myCredCallPricingEndMessage',
            'GamiPressNewMessageChargeMessage',
            'GamiPressThreadChargeMessage',
            'GamiPresslPricingStartMessage',
            'GamiPresslPricingEndMessage'
        ];

        $textareas = [ 'badWordsList' ];

        $int_only = [
            'thread_interval'           => 1,
            'site_interval'             => 1,
            'callRequestTimeLimit'      => 10,
            'fixedHeaderHeight'         => 0,
            'messagesHeight'            => 200,
            'messagesMinHeight'         => 100,
            'sideThreadsWidth'          => 320,
            'mobilePopupLocationBottom' => 0,
            'rateLimitNewThread'        => 0,
            'notificationsInterval'     => 0,
            'notificationsOfflineDelay' => 0,
            'notificationSound'         => 0,
            'sentSound'                 => 0,
            'callSound'                 => 0,
            'dialingSound'              => 0,
            'modernBorderRadius'        => 0,
            'attachmentsMaxNumber'      => 0
        ];

        $arrays = [
            'rateLimitReply',
            'restrictRoleBlock',
            'myCredNewMessageCharge',
            'myCredNewThreadCharge',
            'myCredCallPricing',
            'GamiPressNewMessageCharge',
            'GamiPressNewThreadCharge',
            'GamiPressCallPricing',
            'reactionsEmojies'
        ];

        foreach ( $settings as $key => $value ) {
            /** Processing checkbox groups **/

            if( in_array( $key, $arrays ) ){
                $this->settings[$key] = (array) $value;
            } else if ( is_array( $value ) ) {
                $this->settings[$key] = array();
                foreach ( $value as $val ) {
                    $this->settings[$key][] = sanitize_text_field( $val );
                }
            } else {
                if( in_array( $key, $textareas ) ){
                    $this->settings[$key] = sanitize_textarea_field( $value );
                } else if ( in_array( $key, $links_allowed ) ) {
                    $this->settings[$key] = wp_kses( $value, 'user_description' );
                } else {
                    $this->settings[$key] = sanitize_text_field( $value );

                    if ( array_key_exists( $key, $int_only ) ) {
                        $intval = intval( $value );
                        if ( $intval <= $int_only[$key] ) {
                            $intval = $int_only[$key];
                        }
                        $this->settings[$key] = $intval;
                    }

                }

            }
        }

        $this->settings['bpProfileSlug'] = preg_replace('/\s+/', '', trim( $this->settings['bpProfileSlug'] ) );
        $this->settings['bpGroupSlug'] = preg_replace('/\s+/', '', trim( $this->settings['bpGroupSlug'] ) );

        if ( ! isset( $this->settings['bpProfileSlug'] ) || empty( $this->settings['bpProfileSlug'] ) || $this->settings['bpProfileSlug'] === 'messages' ) {
            $this->settings['bpProfileSlug'] = 'bp-messages';
        }

        if ( ! isset( $this->settings['bpGroupSlug'] ) || empty( $this->settings['bpGroupSlug'] ) ) {
            $this->settings['bpGroupSlug'] = 'bp-messages';
        }

        $this->settings['updateTime'] = time();

        wp_unschedule_hook('bp_better_messages_send_notifications');

        update_option( 'bp-better-chat-settings', $this->settings );
        do_action( 'bp_better_chat_settings_updated', $this->settings );

        update_option( 'bp-better-chat-settings-updated', true );
    }

}
function Better_Messages_Options()
{
    return Better_Messages_Options::instance();
}
