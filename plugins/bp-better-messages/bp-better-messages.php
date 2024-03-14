<?php

/*
    @wordpress-plugin
    Plugin Name: Better Messages
    Plugin URI: https://www.wordplus.org
    Description: Realtime private messaging system for WordPress
    Version: 2.4.27
    Author: WordPlus
    Author URI: https://www.wordplus.org
    Requires PHP: 7.1
    Requires at least: 5.9.0
    License: GPLv3
    Text Domain: bp-better-messages
    Domain Path: /languages*/
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages' ) && !function_exists( 'bpbm_fs' ) ) {
    class Better_Messages
    {
        public  $version = '2.4.27' ;
        public  $db_version = '1.0.3' ;
        public  $realtime ;
        public  $path ;
        public  $url ;
        public  $settings ;
        /** @var Better_Messages_Options $options */
        public  $options ;
        /** @var Better_Messages_Functions $functions */
        public  $functions ;
        /** @var Better_Messages_Shortcodes $shortcodes */
        public  $shortcodes ;
        /** @var Better_Messages_Rest_Api $api */
        public  $api ;
        /** @var Better_Messages_Stickers $stickers */
        public  $stickers ;
        /** @var Better_Messages_Mentions $mentions */
        public  $mentions ;
        /** @var Better_Messages_Giphy $giphy */
        public  $giphy ;
        /** @var Better_Messages_Urls $urls */
        public  $urls ;
        /** @var Better_Messages_Files $files */
        public  $files ;
        /** @var Better_Messages_Mini_List $mini_list */
        public  $mini_list ;
        /** @var Better_Messages_Emojis $emoji */
        public  $emoji ;
        /** @var Better_Messages_Chats $chats */
        public  $chats ;
        /** @var Better_Messages_Notifications $notifications */
        public  $notifications ;
        /** @var Better_Messages_Component $tab */
        public  $tab ;
        /** @var Better_Messages_Hooks $hooks */
        public  $hooks ;
        /** @var Better_Messages_Group $groups */
        public  $groups ;
        /** @var Better_Messages_Customize $customize */
        public  $customize ;
        /** @var Better_Messages_User_Config $user_config */
        public  $user_config ;
        /** @var Better_Messages_Moderation $moderation */
        public  $moderation ;
        /** @var Better_Messages_Users $users */
        public  $users ;
        /** @var Better_Messages_Guests $guests */
        public  $guests ;
        /** @var Better_Messages_Cleaner $cleaner */
        public  $cleaner ;
        /** @var Better_Messages_WebSocket $functions */
        public  $websocket = false ;
        /** @var Better_Messages_Calls $calls */
        public  $calls = false ;
        /** @var Better_Messages_Calls_Group $group_calls */
        public  $group_calls = false ;
        /** @var Better_Messages_Mobile_App $mobile_app */
        public  $mobile_app = false ;
        public  $script_variables ;
        public static function instance()
        {
            // Store the instance locally to avoid private static replication
            static  $instance = null ;
            // Only run these methods if they haven't been run previously
            
            if ( null === $instance ) {
                $instance = new Better_Messages();
                $instance->load_textDomain();
                $instance->setup_vars();
                $instance->setup_actions();
                $instance->setup_classes();
            }
            
            // Always return the instance
            return $instance;
            // The last metroid is in captivity. The galaxy is at peace.
        }
        
        public function setup_vars()
        {
            global  $wpdb ;
            $wpdb->__set( 'bm_threadmeta', bm_get_table( 'threadsmeta' ) );
            $wpdb->__set( 'bm_messagemeta', bm_get_table( 'meta' ) );
            $this->realtime = false;
            $this->path = plugin_dir_path( __FILE__ );
            $this->url = plugin_dir_url( __FILE__ );
        }
        
        public function setup_actions()
        {
            $this->require_files();
            add_action( 'wp_enqueue_scripts', array( $this, 'load_scripts' ) );
            //add_filter( 'script_loader_tag',  array( $this, 'script_loader_tag' ), 10, 3);
            add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );
        }
        
        /**
         * Require necessary files
         */
        public function require_files()
        {
            require_once 'inc/classes/message.php';
            require_once 'inc/classes/thread.php';
            require_once 'inc/functions-global.php';
            require_once 'inc/functions.php';
            /**
             * Require component only if BuddyPress is active
             */
            if ( class_exists( 'BP_Component' ) ) {
                require_once 'inc/component.php';
            }
            require_once 'inc/hooks.php';
            require_once 'inc/users.php';
            require_once 'inc/options.php';
            require_once 'inc/notifications.php';
            require_once 'inc/chats.php';
            require_once 'inc/mini-list.php';
            require_once 'inc/user-config.php';
            require_once 'inc/shortcodes.php';
            require_once 'inc/rest-api.php';
            require_once 'inc/cleaner.php';
            require_once 'inc/moderation.php';
            require_once 'inc/guests.php';
            require_once 'addons/urls.php';
            require_once 'addons/files.php';
            require_once 'addons/emojis.php';
            require_once 'addons/mentions.php';
            require_once 'addons/stickers.php';
            require_once 'addons/giphy.php';
            require_once 'addons/reactions.php';
            require_once 'inc/customize.php';
            require_once Better_Messages()->path . 'vendor/AES256.php';
            require_once Better_Messages()->path . 'vendor/randomizer/randomizer-start.php';
            require_once Better_Messages()->path . 'vendor/random-name-generator/random-name-generator.php';
            bpbm_fs()->add_filter( 'plugin_icon', function () {
                return $this->path . 'assets/images/icon.png';
            } );
        }
        
        public function setup_classes()
        {
            $this->functions = Better_Messages_Functions();
            $this->options = Better_Messages_Options();
            $this->load_options();
            $this->hooks = Better_Messages_Hooks();
            $this->users = Better_Messages_Users();
            $this->guests = Better_Messages_Guests();
            if ( function_exists( 'Better_Messages_Tab' ) && apply_filters( 'better_messages_replace_buddypress', true ) ) {
                $this->tab = Better_Messages_Tab();
            }
            $this->notifications = Better_Messages_Notifications();
            $this->chats = Better_Messages_Chats();
            $this->mini_list = Better_Messages_Mini_List();
            $this->files = Better_Messages_Files();
            if ( $this->settings['searchAllUsers'] === '1' && !defined( 'BP_MESSAGES_AUTOCOMPLETE_ALL' ) ) {
                define( 'BP_MESSAGES_AUTOCOMPLETE_ALL', true );
            }
            $this->urls = Better_Messages_Urls();
            $this->emoji = Better_Messages_Emojis();
            $this->mentions = Better_Messages_Mentions();
            $this->stickers = Better_Messages_Stickers();
            $this->giphy = Better_Messages_Giphy();
            $this->user_config = Better_Messages_User_Config();
            $this->shortcodes = Better_Messages_Shortcodes();
            $this->api = Better_Messages_Rest_Api();
            $this->cleaner = Better_Messages_Cleaner();
            $this->moderation = Better_Messages_Moderation();
            
            if ( bm_bp_is_active( 'groups' ) ) {
                require_once 'inc/component-group.php';
                $this->groups = Better_Messages_Group();
            }
            
            if ( file_exists( $this->path . 'addons/calls.php' ) && Better_Messages()->functions->can_use_premium_code_premium_only() ) {
                require_once $this->path . 'addons/calls.php';
            }
            if ( file_exists( $this->path . 'addons/calls-group.php' ) && Better_Messages()->functions->can_use_premium_code_premium_only() ) {
                require_once $this->path . 'addons/calls-group.php';
            }
            if ( function_exists( 'Better_Messages_Calls' ) ) {
                if ( $this->settings['videoCalls'] === '1' || $this->settings['audioCalls'] === '1' || $this->settings['groupCallsGroups'] === '1' || $this->settings['groupCallsThreads'] === '1' || $this->settings['groupCallsChats'] === '1' ) {
                    $this->calls = Better_Messages_Calls();
                }
            }
            if ( function_exists( 'Better_Messages_Calls_Group' ) ) {
                if ( $this->settings['groupAudioCallsChats'] === '1' || $this->settings['groupAudioCallsThreads'] === '1' || $this->settings['groupAudioCallsChats'] === '1' || $this->settings['groupCallsGroups'] === '1' || $this->settings['groupCallsThreads'] === '1' || $this->settings['groupCallsChats'] === '1' ) {
                    $this->group_calls = Better_Messages_Calls_Group();
                }
            }
            $this->customize = Better_Messages_Customize();
        }
        
        public function load_options()
        {
            $this->settings = $this->options->settings;
            $this->settings = apply_filters( 'bp_better_messages_overwrite_settings', $this->settings );
        }
        
        public function load_textDomain()
        {
            load_plugin_textdomain( 'bp-better-messages', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
        }
        
        public function load_admin_scripts()
        {
            $this->enqueue_admin_js();
            $this->enqueue_admin_css();
            return true;
        }
        
        public function load_scripts()
        {
            if ( !is_user_logged_in() && !Better_Messages()->guests->guest_access_enabled() ) {
                return false;
            }
            $this->enqueue_js();
            $this->enqueue_css();
            return true;
        }
        
        public function ensure_version_included( $src, $handle )
        {
            
            if ( $handle === 'better-messages' ) {
                $parsed_vars = parse_url( $src, PHP_URL_QUERY );
                $version_included = false;
                
                if ( $parsed_vars ) {
                    parse_str( $parsed_vars, $url_vars );
                    if ( isset( $url_vars['ver'] ) ) {
                        $version_included = true;
                    }
                }
                
                if ( !$version_included ) {
                    $src = add_query_arg( 'ver', $this->version, $src );
                }
            }
            
            return $src;
        }
        
        public function enqueue_admin_css()
        {
            $current_screen = get_current_screen();
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            $version = $this->version;
            if ( empty($suffix) ) {
                $version .= filemtime( $this->path . 'assets/admin/admin' . $suffix . '.css' );
            }
            $include_css = false;
            switch ( $current_screen->id ) {
                case 'toplevel_page_bp-better-messages':
                case 'edit-bpbm-chat':
                case 'bpbm-chat':
                    $include_css = true;
                    break;
                default:
                    if ( str_ends_with( $current_screen->id, 'better-messages-viewer' ) ) {
                        $include_css = true;
                    }
                    if ( str_ends_with( $current_screen->id, 'better-messages-mobile-app' ) ) {
                        $include_css = true;
                    }
            }
            if ( $include_css ) {
                wp_enqueue_style(
                    'better-messages-admin',
                    plugins_url( 'assets/admin/admin' . $suffix . '.css', __FILE__ ),
                    false,
                    $version
                );
            }
        }
        
        public function enqueue_admin_js()
        {
            $current_screen = get_current_screen();
            $include_js = false;
            switch ( $current_screen->id ) {
                case 'toplevel_page_bp-better-messages':
                case 'edit-bpbm-chat':
                case 'bpbm-chat':
                    $include_js = true;
                    break;
                default:
                    if ( str_ends_with( $current_screen->id, 'better-messages-viewer' ) ) {
                        $include_js = true;
                    }
                    if ( str_ends_with( $current_screen->id, 'better-messages-mobile-app' ) ) {
                        $include_js = true;
                    }
            }
            if ( !$include_js ) {
                return;
            }
            $file_name = 'admin.min.js';
            $is_dev = defined( 'BM_DEV' );
            if ( $is_dev && file_exists( $this->path . 'assets/admin/admin.js' ) ) {
                $file_name = 'admin.js';
            }
            $dependencies = array( 'jquery', 'wp-i18n' );
            wp_register_script(
                'better-messages-admin',
                plugins_url( 'assets/admin/' . $file_name, __FILE__ ),
                $dependencies,
                $this->version
            );
            $script_variables = [
                'restUrl' => $this->functions->get_rest_api_url(),
                'nonce'   => wp_create_nonce( 'wp_rest' ),
            ];
            wp_set_script_translations( 'better-messages-admin', 'bp-better-messages', plugin_dir_path( __FILE__ ) . 'languages/' );
            wp_localize_script( 'better-messages-admin', 'Better_Messages_Admin', $script_variables );
            wp_enqueue_script( 'better-messages-admin' );
        }
        
        public  $css_loaded = false ;
        public function enqueue_css( $force = false )
        {
            if ( !$force && $this->css_loaded ) {
                return;
            }
            if ( !has_action( 'style_loader_src', array( $this, 'ensure_version_included' ) ) ) {
                add_action(
                    'style_loader_src',
                    array( $this, 'ensure_version_included' ),
                    999,
                    2
                );
            }
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            $version = $this->version;
            if ( empty($suffix) ) {
                $version .= filemtime( $this->path . 'assets/css/bp-messages' . $suffix . '.css' );
            }
            wp_register_style(
                'better-messages',
                plugins_url( 'assets/css/bp-messages' . $suffix . '.css', __FILE__ ),
                false,
                $version
            );
            wp_add_inline_style( 'better-messages', Better_Messages()->hooks->css_customizations() );
            wp_add_inline_style( 'better-messages', Better_Messages()->customize->header_output() );
            wp_enqueue_style( 'better-messages' );
            $this->css_loaded = true;
        }
        
        public  $js_loaded = false ;
        public function enqueue_js()
        {
            if ( $this->js_loaded ) {
                return;
            }
            if ( !has_action( 'script_loader_src', array( $this, 'ensure_version_included' ) ) ) {
                add_action(
                    'script_loader_src',
                    array( $this, 'ensure_version_included' ),
                    999,
                    2
                );
            }
            do_action( 'better_messages_register_script_dependencies' );
            $suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min' );
            $dependencies = apply_filters( 'better_messages_script_dependencies', array( 'jquery', 'wp-i18n' ) );
            $is_dev = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG;
            $file_name = 'bp-messages-free.min.js';
            if ( $is_dev && file_exists( $this->path . 'assets/js/bp-messages.js' ) ) {
                $file_name = 'bp-messages.js';
            }
            $version = $this->version;
            if ( empty($suffix) ) {
                $version .= filemtime( $this->path . 'assets/js/' . $file_name );
            }
            wp_register_script(
                'better-messages',
                plugins_url( 'assets/js/' . $file_name, __FILE__ ),
                $dependencies,
                $version,
                false
            );
            $script_variables = $this->get_script_variables();
            wp_set_script_translations( 'better-messages', 'bp-better-messages', plugin_dir_path( __FILE__ ) . 'languages/' );
            wp_localize_script( 'better-messages', 'Better_Messages', apply_filters( 'bp_better_messages_script_variables', $script_variables ) );
            wp_enqueue_script( 'better-messages' );
            $this->js_loaded = true;
        }
        
        public function script_loader_tag( $tag, $handle, $src )
        {
            if ( $handle === 'better-messages' ) {
                if ( false === stripos( $tag, 'async' ) ) {
                    $tag = str_replace( ' src', ' async="async" src', $tag );
                }
            }
            return $tag;
        }
        
        public function get_script_variables()
        {
            $enableSound = '1';
            
            if ( Better_Messages()->settings['allowSoundDisable'] === '1' ) {
                $disabled = Better_Messages()->functions->get_user_meta( get_current_user_id(), 'bpbm_disable_sound_notification', true ) === 'yes';
                if ( $disabled ) {
                    $enableSound = '0';
                }
            }
            
            
            if ( is_user_logged_in() ) {
                $unread_count = Better_Messages()->functions->get_total_threads_for_user( get_current_user_id(), 'unread' );
            } else {
                $unread_count = 0;
            }
            
            $ukey = Better_Messages()->functions->get_user_secret_key( get_current_user_id() );
            $locale = sanitize_file_name( strtolower( get_locale() ) );
            $locale = str_replace( '_', '-', $locale );
            $locale = apply_filters( 'bp_better_messages_time_locale', $locale );
            $friends = Better_Messages()->functions->is_friends_active();
            $groups = Better_Messages()->functions->is_groups_active();
            $localEncryption = $this->settings['encryptionLocal'] === '1';
            $hash = serialize( $this->settings ) . serialize( $this->functions->get_user_roles( get_current_user_id() ) ) . $this->db_version;
            if ( $localEncryption ) {
                $hash .= $ukey;
            }
            $script_variables = array(
                'hash'               => md5( $hash ),
                'user_id'            => get_current_user_id(),
                'ajaxUrl'            => admin_url( 'admin-ajax.php' ),
                'restUrl'            => esc_url_raw( get_rest_url( null, '/better-messages/v1/' ) ),
                'nonce'              => wp_create_nonce( 'wp_rest' ),
                'siteRefresh'        => ( isset( $this->settings['site_interval'] ) ? intval( $this->settings['site_interval'] ) * 1000 : 10000 ),
                'threadRefresh'      => ( isset( $this->settings['thread_interval'] ) ? intval( $this->settings['thread_interval'] ) * 1000 : 3000 ),
                'url'                => $this->functions->get_link(),
                'threadUrl'          => $this->functions->get_link( get_current_user_id() ) . '#/conversation/',
                'baseUrl'            => $this->functions->get_link( get_current_user_id() ),
                'assets'             => plugin_dir_url( __FILE__ ) . 'assets/',
                'sounds'             => apply_filters( 'bp_better_messages_sounds_assets', plugin_dir_url( __FILE__ ) . 'assets/sounds/' ),
                'soundLevels'        => array(
                'notification' => $this->settings['notificationSound'] / 100,
                'sent'         => $this->settings['sentSound'] / 100,
                'calling'      => $this->settings['callSound'] / 100,
                'dialing'      => $this->settings['dialingSound'] / 100,
            ),
                'color'              => get_theme_mod( 'main-bm-color', '#21759b' ),
                'darkColor'          => get_theme_mod( 'main-bm-color-dark', '#fff' ),
                'locale'             => $locale,
                'stickers'           => ( !empty($this->settings['stipopApiKey']) ? '1' : '0' ),
                'gifs'               => ( !empty($this->settings['giphyApiKey']) ? '1' : '0' ),
                'realtime'           => ( $this->realtime ? '1' : '0' ),
                'minHeight'          => (int) $this->settings['messagesMinHeight'],
                'maxHeight'          => (int) apply_filters( 'bp_better_messages_max_height', $this->settings['messagesHeight'] ),
                'headerHeight'       => (int) $this->settings['fixedHeaderHeight'],
                'sideWidth'          => (int) $this->settings['sideThreadsWidth'],
                'favorite'           => ( $this->settings['disableFavoriteMessages'] == '1' ? '0' : '1' ),
                'fullScreen'         => ( $this->settings['desktopFullScreen'] == '1' ? '1' : '0' ),
                'myProfile'          => ( $this->settings['myProfileButton'] == '1' ? '1' : '0' ),
                'replies'            => ( $this->settings['enableReplies'] == '1' ? '1' : '0' ),
                'template'           => $this->settings['template'],
                'layout'             => $this->settings['modernLayout'],
                'singleThread'       => ( $this->settings['singleThreadMode'] == '1' ? '1' : '0' ),
                'forceThread'        => ( $this->settings['newThreadMode'] == '1' ? '1' : '0' ),
                'groupThreads'       => ( $this->settings['disableGroupThreads'] == '1' ? '0' : '1' ),
                'subjects'           => ( $this->settings['disableSubject'] == '1' ? '0' : '1' ),
                'suggestions'        => ( $this->settings['enableUsersSuggestions'] == '1' ? '1' : '0' ),
                'friends'            => ( $friends ? '1' : '0' ),
                'groups'             => ( $groups ? '1' : '0' ),
                'userBlock'          => ( $this->settings['allowUsersBlock'] == '1' ? '1' : '0' ),
                'newThread'          => ( $this->settings['disableNewThread'] == '1' && !current_user_can( 'manage_options' ) ? '0' : '1' ),
                'mobileFullScreen'   => ( $this->settings['mobileFullScreen'] == '1' ? '1' : '0' ),
                'autoFullScreen'     => ( $this->settings['autoFullScreen'] == '1' ? '1' : '0' ),
                'tapToOpen'          => ( $this->settings['tapToOpenMsg'] == '1' ? '1' : '0' ),
                'blockUsers'         => ( $this->settings['allowUsersBlock'] == '1' ? '1' : '0' ),
                'emojiHash'          => get_option( 'bm-emoji-hash', '' ),
                'emojiSet'           => $this->settings['emojiSet'],
                'sprite'             => Better_Messages_Emojis()->getSpriteUrl(),
                'search'             => ( $this->settings['disableSearch'] == '1' ? '0' : '1' ),
                'datePosition'       => ( get_theme_mod( 'bm-date-position', 'message' ) === 'stack' ? 'stack' : 'message' ),
                'timeFormat'         => ( get_theme_mod( 'bm-time-format', '24' ) === '12' ? '12' : '24' ),
                'avatars'            => ( in_array( get_theme_mod( 'bm-avatars-list', 'show' ), [ 'hide_private', 'hide_groups', 'hide' ] ) ? get_theme_mod( 'bm-avatars-list', 'show' ) : 'show' ),
                'subName'            => ( in_array( get_theme_mod( 'bm-private-sub-name', 'show' ), [ 'online', 'subject', 'hide' ] ) ? get_theme_mod( 'bm-private-sub-name', 'show' ) : 'online' ),
                'touchEnter'         => ( $this->settings['disableEnterForTouch'] == '1' ? '0' : '1' ),
                'loginUrl'           => apply_filters( 'better_messages_login_url', wp_login_url( add_query_arg( [] ) ) ),
                'total_unread'       => (int) $unread_count,
                'enableGroups'       => ( $this->settings['enableGroups'] == '1' || $this->settings['PSenableGroups'] ? '1' : '0' ),
                'groupsSlug'         => $this->settings['bpGroupSlug'],
                'disableEnter'       => ( $this->settings['disableEnterForDesktop'] == '1' ? '1' : '0' ),
                'miniClose'          => ( $this->settings['enableMiniCloseButton'] ? '1' : '0' ),
                'miniChats'          => ( $this->realtime && $this->settings['miniChatsEnable'] ? '1' : '0' ),
                'miniMessages'       => ( $this->realtime && $this->settings['miniThreadsEnable'] ? '1' : '0' ),
                'miniAudio'          => ( $this->realtime && $this->settings['miniChatAudioCall'] ? '1' : '0' ),
                'miniVideo'          => ( $this->realtime && $this->settings['miniChatVideoCall'] ? '1' : '0' ),
                'messagesStatus'     => ( $this->realtime && $this->settings['messagesStatus'] ? '1' : '0' ),
                'listStatus'         => ( $this->realtime && $this->settings['messagesStatusList'] ? '1' : '0' ),
                'statusDetails'      => ( $this->realtime && $this->settings['messagesStatusDetailed'] ? '1' : '0' ),
                'combinedView'       => ( $this->settings['combinedView'] == '1' ? '1' : '0' ),
                'offlineCalls'       => ( $this->settings['offlineCallsAllowed'] == '1' ? '1' : '0' ),
                'onSiteNotification' => ( $this->settings['disableOnSiteNotification'] == '1' ? '0' : '1' ),
                'onsitePosition'     => ( $this->settings['onsitePosition'] === 'right' ? 'right' : 'left' ),
                'titleNotifications' => ( $this->settings['titleNotifications'] == '1' ? '1' : '0' ),
                'hPBE'               => ( $this->settings['hidePossibleBreakingElements'] == '1' ? '1' : '0' ),
                'userSettings'       => ( $this->settings['disableUserSettings'] == '1' ? '0' : '1' ),
                'miniSync'           => ( $this->settings['miniChatDisableSync'] != '1' ? '1' : '0' ),
                'pinning'            => ( $this->settings['pinnedThreads'] == '1' ? '1' : '0' ),
                'mobileOnsite'       => ( in_array( $this->settings['mobileOnsiteLocation'], [ 'top', 'bottom' ] ) ? $this->settings['mobileOnsiteLocation'] : 'auto' ),
                'enableSound'        => $enableSound,
                'guests'             => ( Better_Messages()->guests->guest_access_enabled() ? '1' : '0' ),
            );
            if ( !is_user_logged_in() && get_option( 'users_can_register' ) ) {
                $script_variables['registerUrl'] = apply_filters( 'better_messages_registration_url', wp_registration_url() );
            }
            if ( current_user_can( 'manage_options' ) ) {
                $script_variables['isAdmin'] = '1';
            }
            if ( Better_Messages_Rest_Api_Bulk_Message()->has_access() ) {
                $script_variables['canBulk'] = '1';
            }
            
            if ( $this->settings['enableReactions'] == '1' ) {
                $script_variables['reactions'] = Better_Messages_Reactions::instance()->get_reactions();
                $script_variables['reactionsList'] = ( $this->settings['enableReactionsPopup'] == '1' ? '1' : '0' );
            }
            
            
            if ( $friends ) {
                $script_variables['combinedFriends'] = ( $this->settings['combinedFriendsEnable'] == '1' ? '1' : '0' );
                $script_variables['miniFriends'] = ( $this->settings['miniFriendsEnable'] == '1' ? '1' : '0' );
                $script_variables['mobileFriends'] = ( $this->settings['mobileFriendsEnable'] == '1' ? '1' : '0' );
            }
            
            
            if ( $groups ) {
                $script_variables['combinedGroups'] = ( $this->settings['combinedGroupsEnable'] == '1' ? '1' : '0' );
                $script_variables['miniGroups'] = ( $this->settings['enableMiniGroups'] == '1' ? '1' : '0' );
                $script_variables['mobileGroups'] = ( $this->settings['mobileGroupsEnable'] == '1' ? '1' : '0' );
            }
            
            if ( $this->realtime && $this->settings['userStatuses'] ) {
                $script_variables['userStatuses'] = $this->websocket->get_all_statuses();
            }
            
            if ( is_user_logged_in() ) {
                $user_data = $this->functions->rest_user_item( get_current_user_id(), false );
                $script_variables['me'] = [
                    'name'   => base64_encode( $user_data['name'] ),
                    'url'    => base64_encode( $user_data['url'] ),
                    'avatar' => base64_encode( $user_data['avatar'] ),
                ];
            }
            
            $script_variables['ukey'] = $ukey;
            $this->script_variables = apply_filters( 'bp_better_messages_script_variable', $script_variables );
            return $this->script_variables;
        }
    
    }
    if ( !class_exists( 'BP_Better_Messages' ) ) {
        class BP_Better_Messages extends Better_Messages
        {
        }
    }
    if ( !function_exists( 'BP_Better_Messages' ) ) {
        /**
         * Fallback to old naming
         * @return Better_Messages|null
         */
        function BP_Better_Messages()
        {
            return Better_Messages();
        }
    
    }
    function Better_Messages()
    {
        return Better_Messages::instance();
    }
    
    function bm_bp_is_active( $component = '', $feature = '' )
    {
        
        if ( function_exists( 'bp_is_active' ) ) {
            return bp_is_active( $component, $feature );
        } else {
            return false;
        }
    
    }
    
    function bm_get_table( $table )
    {
        global  $wpdb ;
        $bp_prefix = apply_filters( 'bp_core_get_table_prefix', $wpdb->base_prefix );
        
        if ( function_exists( 'buddypress' ) && bm_bp_is_active( 'messages' ) ) {
            switch ( $table ) {
                case 'threads':
                    return $bp_prefix . 'bm_threads';
                case 'messages':
                    return $bp_prefix . 'bm_message_messages';
                    break;
                case 'meta':
                    return $bp_prefix . 'bm_message_meta';
                    break;
                case 'recipients':
                    return $bp_prefix . 'bm_message_recipients';
                    break;
                case 'notifications':
                    
                    if ( isset( buddypress()->notifications ) ) {
                        return buddypress()->notifications->table_name;
                    } else {
                        return false;
                    }
                    
                    break;
                case 'threadsmeta':
                    return $bp_prefix . 'bm_thread_meta';
                    break;
                case 'mentions':
                    return $bp_prefix . 'bm_mentions';
                    break;
                case 'moderation':
                    return $bp_prefix . 'bm_moderation';
                    break;
                case 'guests':
                    return $bp_prefix . 'bm_guests';
                case 'users':
                    return $bp_prefix . 'bm_user_index';
                case 'roles':
                    return $bp_prefix . 'bm_user_roles_index';
                    break;
            }
        } else {
            switch ( $table ) {
                case 'threads':
                    return $bp_prefix . 'bm_threads';
                case 'messages':
                    return $bp_prefix . 'bm_message_messages';
                    break;
                case 'meta':
                    return $bp_prefix . 'bm_message_meta';
                    break;
                case 'recipients':
                    return $bp_prefix . 'bm_message_recipients';
                    break;
                case 'threadsmeta':
                    return $bp_prefix . 'bm_thread_meta';
                    break;
                case 'mentions':
                    return $bp_prefix . 'bm_mentions';
                    break;
                case 'moderation':
                    return $bp_prefix . 'bm_moderation';
                    break;
                case 'notifications':
                    return false;
                    break;
                case 'guests':
                    return $bp_prefix . 'bm_guests';
                case 'users':
                    return $bp_prefix . 'bm_user_index';
                    break;
                case 'roles':
                    return $bp_prefix . 'bm_user_roles_index';
                    break;
            }
        }
        
        return false;
    }
    
    function Better_Messages_Incompatible_PHP()
    {
        echo  '<div class="notice notice-error">' ;
        echo  '<p><b>Better Messages</b> require at least <b>PHP 7.1</b>, currently running PHP <b>' . PHP_VERSION . '</b>.</p>' ;
        echo  '<p>Please upgrade your website PHP version to use Better Messages.</p>' ;
        echo  '<p class="button-container"><a class="button button-primary" href="https://wordpress.org/support/update-php/" target="_blank" rel="noopener" style="vertical-align: middle;">Learn more about updating PHP <span class="screen-reader-text">(opens in a new tab)</span><span aria-hidden="true" class="dashicons dashicons-external"></span></a>	</p>' ;
        echo  '</div>' ;
    }
    
    function Better_Messages_Init()
    {
        
        if ( version_compare( PHP_VERSION, '7.1' ) < 0 ) {
            add_action( 'admin_notices', 'Better_Messages_Incompatible_PHP' );
            return;
        }
        
        
        if ( class_exists( 'BuddyPress' ) && bm_bp_is_active( 'messages' ) ) {
            Better_Messages();
        } else {
            $is_activating_buddypress = isset( $_GET['plugin'] ) && isset( $_GET['action'] ) && strpos( $_GET['plugin'], 'bp-loader.php' ) !== false && $_GET['action'] == 'activate';
            $is_activating_buddypress_2 = isset( $_GET['plugin'] ) && isset( $_GET['action'] ) && strpos( $_GET['plugin'], 'bp-loader.php' ) !== false && $_GET['action'] == 'activate-plugin';
            $is_activating_buddypress_3 = isset( $_GET['plugins'] ) && isset( $_GET['action'] ) && strpos( $_GET['plugins'], 'bp-loader.php' ) !== false && $_GET['action'] == 'update-selected';
            
            if ( $is_activating_buddypress || $is_activating_buddypress_2 || $is_activating_buddypress_3 ) {
                Better_Messages();
            } else {
                require_once 'vendor/buddypress/functions.php';
                require_once 'vendor/buddypress/class-bp-messages-message.php';
                require_once 'vendor/buddypress/class-bp-user-query.php';
                require_once 'vendor/buddypress/class-bp-suggestions.php';
                require_once 'vendor/buddypress/class-bp-members-suggestions.php';
                Better_Messages();
            }
        
        }
    
    }
    
    // Uncanny Automator
    require_once trailingslashit( dirname( __FILE__ ) ) . 'addons/uncanny/uncanny-automator-better-messages.php';
    // AutomatorWP
    require_once trailingslashit( dirname( __FILE__ ) ) . 'addons/automatorwp/automatorwp.php';
    add_action( 'plugins_loaded', 'Better_Messages_Init', 20 );
    require_once trailingslashit( dirname( __FILE__ ) ) . 'inc/install.php';
    register_activation_hook( __FILE__, 'bp_better_messages_activation' );
    register_deactivation_hook( __FILE__, 'bp_better_messages_deactivation' );
    
    if ( !function_exists( 'bpbm_fs' ) ) {
        // Create a helper function for easy SDK access.
        function bpbm_fs()
        {
            global  $bbm_fs ;
            
            if ( !isset( $bbm_fs ) ) {
                if ( !defined( 'WP_FS__PRODUCT_1557_MULTISITE' ) ) {
                    define( 'WP_FS__PRODUCT_1557_MULTISITE', true );
                }
                // Include Freemius SDK.
                require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
                $bbm_fs = fs_dynamic_init( array(
                    'id'             => '1557',
                    'slug'           => 'bp-better-messages',
                    'type'           => 'plugin',
                    'public_key'     => 'pk_8af54172153e9907893f32a4706e2',
                    'is_premium'     => false,
                    'premium_suffix' => '- WebSocket Version',
                    'has_addons'     => true,
                    'has_paid_plans' => true,
                    'trial'          => array(
                    'days'               => 3,
                    'is_require_payment' => true,
                ),
                    'menu'           => array(
                    'slug'    => 'bp-better-messages',
                    'support' => false,
                ),
                    'is_live'        => true,
                ) );
            }
            
            return $bbm_fs;
        }
        
        // Init Freemius.
        bpbm_fs();
        // Signal that SDK was initiated.
        do_action( 'bbm_fs_loaded' );
    }

}
