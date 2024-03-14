<?php
global $wpdb;


if( ! function_exists('bp_esc_sql_order') ) {
    function bp_esc_sql_order($order = '')
    {
        $order = strtoupper(trim($order));
        return 'DESC' === $order ? 'DESC' : 'ASC';
    }
}

if( ! function_exists('bp_core_get_username') ){
    function bp_core_get_username( $user_id = 0, $user_nicename = false, $user_login = false ) {
        $username = get_the_author_meta( 'nicename', $user_id );
        return apply_filters( 'bp_core_get_username', $username );
    }
}

if( ! function_exists('bp_get_allowedtags') ){
    function bp_get_allowedtags() {
        global $allowedtags;

        return array_merge_recursive( $allowedtags, array(
            'a' => array(
                'aria-label'      => array(),
                'class'           => array(),
                'data-bp-tooltip' => array(),
                'id'              => array(),
                'rel'             => array(),
            ),
            'img' => array(
                'src'    => array(),
                'alt'    => array(),
                'width'  => array(),
                'height' => array(),
                'class'  => array(),
                'id'     => array(),
            ),
            'span'=> array(
                'class'          => array(),
                'data-livestamp' => array(),
            ),
            'ul' => array(),
            'ol' => array(),
            'li' => array(),
        ) );
    }
}

if( ! function_exists('bp_messages_filter_kses') ){
    function bp_messages_filter_kses( $content ) {
        $messages_allowedtags      = bp_get_allowedtags();
        $messages_allowedtags['p'] = array();

        /**
         * Filters the allowed HTML tags for BuddyPress Messages content.
         *
         * @since 3.0.0
         *
         * @param array $value Array of allowed HTML tags and attributes.
         */
        $messages_allowedtags = apply_filters( 'bp_messages_allowed_tags', $messages_allowedtags );
        return wp_kses( $content, $messages_allowedtags );
    }

    add_filter( 'messages_message_content_before_save', 'bp_messages_filter_kses', 1 );
}

if( ! function_exists('bp_core_get_table_prefix') ){
    function bp_core_get_table_prefix() {
        global $wpdb;

        /**
         * Filters the $wpdb base prefix.
         *
         * Intended primarily for use in multinetwork installations.
         *
         * @since 1.2.6
         *
         * @param string $base_prefix Base prefix to use.
         */
        return apply_filters( 'bp_core_get_table_prefix', $wpdb->base_prefix );
    }
}

if( ! function_exists('bp_core_get_core_userdata') ){
    function bp_core_get_core_userdata( $user_id = 0 ) {
        if ( empty( $user_id ) ) {
            return false;
        }

        // Get core user data
        $userdata = get_userdata( $user_id );

        return apply_filters( 'bp_core_get_core_userdata', $userdata );
    }
}


if( ! function_exists('bp_core_number_format') ){
    function bp_core_number_format( $number = 0, $decimals = false ) {

        // Force number to 0 if needed.
        if ( ! is_numeric( $number ) ) {
            $number = 0;
        }

        /**
         * Filters the BuddyPress formatted number.
         *
         * @since 1.2.4
         *
         * @param string $value    BuddyPress formatted value.
         * @param int    $number   The number to be formatted.
         * @param bool   $decimals Whether or not to use decimals.
         */
        return apply_filters( 'bp_core_number_format', number_format_i18n( $number, $decimals ), $number, $decimals );
    }
}

if( ! function_exists('bp_core_fetch_avatar') ){
    function bp_core_fetch_avatar( $args = '' ) {

        global $current_blog;

        // Set the default variables array and parse it against incoming $args array.
        $params = wp_parse_args( $args, array(
            'item_id'       => false,
            'object'        => 'user',
            'type'          => 'thumb',
            'avatar_dir'    => false,
            'width'         => false,
            'height'        => false,
            'class'         => 'avatar',
            'css_id'        => false,
            'alt'           => '',
            'email'         => false,
            'no_grav'       => null,
            'html'          => true,
            'title'         => '',
            'extra_attr'    => '',
            'scheme'        => null,
            'rating'        => get_option( 'avatar_rating' ),
            'force_default' => false,
        ) );

        $params['class'] .= ' bbpm-avatar';
        $params['extra_attr'] .= ' data-user-id="' . $params['item_id'] . '"';


        $size = isset( $args['width'] ) ? $args['width'] : 50;

        $removed_um_filter = false;

        if( has_filter( 'get_avatar', 'um_get_avatar' ) ){
            remove_filter( 'get_avatar', 'um_get_avatar', 99999 );
            $removed_um_filter = true;
        }

        $removed_ps_filter = false;
        if( class_exists('PeepSo') && has_filter( 'get_avatar', array( PeepSo::get_instance(), 'filter_avatar' ) ) ){
            remove_filter( 'get_avatar', array( PeepSo::get_instance(), 'filter_avatar'), 20, 5 );
            $removed_ps_filter = true;
        }

        if( $params['html'] === false ){
            if( function_exists('get_wp_user_avatar_src') ){
                $return = get_wp_user_avatar_src( $params['item_id'], ['size' => $size] );
            } else {
                $return = get_avatar_url($params['item_id'], ['size' => $size]);
            }
        } else {
            if( function_exists('get_wp_user_avatar') ){
                $return = get_wp_user_avatar($params['item_id'], $size, '', '');
            } else {
                $return = get_avatar($params['item_id'], $size, '', '', $params);
            }
        }

        if( $removed_um_filter ) {
            add_filter( 'get_avatar', 'um_get_avatar', 99999, 5 );
        }

        if( $removed_ps_filter ) {
            add_filter( 'get_avatar', array( PeepSo::get_instance(), 'filter_avatar'), 20, 5 );
        }

        return $return;
    }
}

if( ! function_exists('bp_core_get_userlink') ) {
    function bp_core_get_userlink($user_id, $no_anchor = false, $just_link = false)
    {
        $link = false;
        if( count_user_posts($user_id) > 0 ) {
            $link = get_author_posts_url($user_id);
        }

        return apply_filters( 'bp_core_get_userlink', $link, $user_id );
    }
}

if( ! function_exists('bp_core_get_user_displayname') ) {
    function bp_core_get_user_displayname( $user_id_or_username ) {
        if ( empty( $user_id_or_username ) ) {
            return false;
        }

        if ( ! is_numeric( $user_id_or_username ) ) {
            $user_id = get_user_by( 'slug', $user_id_or_username );
        } else {
            $user_id = $user_id_or_username;
        }

        if ( empty( $user_id ) ) {
            return false;
        }

        $user = get_userdata( $user_id);

        $display_name = $user->display_name;
        if( empty( $display_name ) ) $display_name = $user->user_nicename;
        return apply_filters( 'bp_better_messages_display_name', $display_name, $user_id );
    }
}

if( ! function_exists('bp_loggedin_user_id') ) {
    function bp_loggedin_user_id() {
        return Better_Messages()->functions->get_current_user_id();
    }
}

if( ! function_exists('bp_displayed_user_id') ) {
    function bp_displayed_user_id() {
        global $authordata;
        if ( isset($authordata->ID) ) {
            return $authordata->ID;
        } else {
            return get_current_user_id();
        }
    }
}

if( ! function_exists('bp_messages_is_message_starred') ) {
function bp_messages_is_message_starred( $mid = 0, $user_id = 0 ) {
    if ( empty( $user_id ) ) {
        $user_id = bp_displayed_user_id();
    }

    if ( empty( $mid ) ) {
        return false;
    }

    $starred = array_flip( (array) Better_Messages()->functions->get_message_meta( $mid, 'starred_by_user', false ) );

    if ( isset( $starred[$user_id] ) ) {
        return true;
    } else {
        return false;
    }
}
}

if( ! function_exists('bp_parse_args') ) {
function bp_parse_args( $args, $defaults = array(), $filter_key = '' ) {

    // Setup a temporary array from $args.
    if ( is_object( $args ) ) {
        $r = get_object_vars( $args );
    } elseif ( is_array( $args ) ) {
        $r =& $args;
    } else {
        wp_parse_str( $args, $r );
    }

    // Passively filter the args before the parse.
    if ( !empty( $filter_key ) ) {

        /**
         * Filters the arguments key before parsing if filter key provided.
         *
         * This is a dynamic filter dependent on the specified key.
         *
         * @since 2.0.0
         *
         * @param array $r Array of arguments to use.
         */
        $r = apply_filters( 'bp_before_' . $filter_key . '_parse_args', $r );
    }

    // Parse.
    if ( is_array( $defaults ) && !empty( $defaults ) ) {
        $r = array_merge( $defaults, $r );
    }

    // Aggressively filter the args after the parse.
    if ( !empty( $filter_key ) ) {

        /**
         * Filters the arguments key after parsing if filter key provided.
         *
         * This is a dynamic filter dependent on the specified key.
         *
         * @since 2.0.0
         *
         * @param array $r Array of parsed arguments.
         */
        $r = apply_filters( 'bp_after_' . $filter_key . '_parse_args', $r );
    }

    // Return the parsed results.
    return $r;
}
}

if( ! function_exists('bp_core_current_time') ) {
function bp_core_current_time( $gmt = true, $type = 'mysql' ) {

    /**
     * Filters the current GMT time to save into the DB.
     *
     * @since 1.2.6
     *
     * @param string $value Current GMT time.
     */
    return apply_filters( 'bp_core_current_time', current_time( $type, $gmt ) );
}
}


if( ! function_exists('bp_update_meta_cache') ) {
function bp_update_meta_cache( $args = array() ) {
    global $wpdb;

    $defaults = array(
        'object_ids' 	   => array(), // Comma-separated list or array of item ids.
        'object_type' 	   => '',      // Canonical component id: groups, members, etc.
        'cache_group'      => '',      // Cache group.
        'meta_table' 	   => '',      // Name of the table containing the metadata.
        'object_column'    => '',      // DB column for the object ids (group_id, etc).
        'cache_key_prefix' => ''       // Prefix to use when creating cache key names. Eg 'bp_groups_groupmeta'.
    );
    $r = wp_parse_args( $args, $defaults );
    extract( $r );

    if ( empty( $object_ids ) || empty( $object_type ) || empty( $meta_table ) || empty( $cache_group ) ) {
        return false;
    }

    if ( empty( $cache_key_prefix ) ) {
        $cache_key_prefix = $meta_table;
    }

    if ( empty( $object_column ) ) {
        $object_column = $object_type . '_id';
    }

    if ( ! $cache_group ) {
        return false;
    }

    $object_ids   = wp_parse_id_list( $object_ids );
    $uncached_ids = bp_get_non_cached_ids( $object_ids, $cache_group );

    $cache = array();

    // Get meta info.
    if ( ! empty( $uncached_ids ) ) {
        $id_list   = join( ',', wp_parse_id_list( $uncached_ids ) );
        $meta_list = $wpdb->get_results( esc_sql( "SELECT {$object_column}, meta_key, meta_value FROM {$meta_table} WHERE {$object_column} IN ({$id_list})" ), ARRAY_A );

        if ( ! empty( $meta_list ) ) {
            foreach ( $meta_list as $metarow ) {
                $mpid = intval( $metarow[$object_column] );
                $mkey = $metarow['meta_key'];
                $mval = $metarow['meta_value'];

                // Force subkeys to be array type.
                if ( !isset( $cache[$mpid] ) || !is_array( $cache[$mpid] ) )
                    $cache[$mpid] = array();
                if ( !isset( $cache[$mpid][$mkey] ) || !is_array( $cache[$mpid][$mkey] ) )
                    $cache[$mpid][$mkey] = array();

                // Add a value to the current pid/key.
                $cache[$mpid][$mkey][] = $mval;
            }
        }

        foreach ( $uncached_ids as $uncached_id ) {
            // Cache empty values as well.
            if ( ! isset( $cache[ $uncached_id ] ) ) {
                $cache[ $uncached_id ] = array();
            }

            wp_cache_set( $uncached_id, $cache[ $uncached_id ], $cache_group );
        }
    }

    return $cache;
}
}

if( ! function_exists('bp_get_non_cached_ids') ) {
    function bp_get_non_cached_ids($item_ids, $cache_group)
    {
        $uncached = array();

        foreach ($item_ids as $item_id) {
            $item_id = (int)$item_id;
            if (false === wp_cache_get($item_id, $cache_group)) {
                $uncached[] = $item_id;
            }
        }

        return $uncached;
    }
}

if( ! function_exists('messages_remove_callback_values') ) {
    function messages_remove_callback_values()
    {
        @setcookie('bp_messages_send_to', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl());
        @setcookie('bp_messages_subject', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl());
        @setcookie('bp_messages_content', false, time() - 1000, COOKIEPATH, COOKIE_DOMAIN, is_ssl());
    }
}

if( ! function_exists('bp_core_get_suggestions') ) {
function bp_core_get_suggestions( $args ) {
    $args = bp_parse_args( $args, array(), 'get_suggestions' );

    if ( ! $args['type'] ) {
        return new WP_Error( 'missing_parameter' );
    }

    // Members @name suggestions.
    if ( $args['type'] === 'members' ) {
        $class = 'BP_Members_Suggestions';

        // Members @name suggestions for users in a specific Group.
        if ( isset( $args['group_id'] ) ) {
            $class = 'BP_Groups_Member_Suggestions';
        }

    } else {

        /**
         * Filters the default suggestions service to use.
         *
         * Use this hook to tell BP the name of your class
         * if you've built a custom suggestions service.
         *
         * @since 2.1.0
         *
         * @param string $value Custom class to use. Default: none.
         * @param array  $args  Array of arguments for sugggestions.
         */
        $class = apply_filters( 'bp_suggestions_services', '', $args );
    }

    if ( ! $class || ! class_exists( $class ) ) {
        return new WP_Error( 'missing_parameter' );
    }


    $suggestions = new $class( $args );
    $validation  = $suggestions->validate();

    if ( is_wp_error( $validation ) ) {
        $retval = $validation;
    } else {
        $retval = $suggestions->get_suggestions();
    }

    /**
     * Filters the available type of at-mentions.
     *
     * @since 2.1.0
     *
     * @param array|WP_Error $retval Array of results or WP_Error object.
     * @param array          $args   Array of arguments for suggestions.
     */
    return apply_filters( 'bp_core_get_suggestions', $retval, $args );
}
}

if( ! function_exists('is_buddypress') ) {
    function is_buddypress() {
        return false;
    }
}

if( ! function_exists('bp_is_email_customizer') ) {
    function bp_is_email_customizer() {
        return false;
    }
}


if( ! function_exists('bp_is_user_active') ) {
function bp_is_user_active( $user_id = 0 ) {

    // Default to current user.
    if ( empty( $user_id ) && is_user_logged_in() ) {
        $user_id = Better_Messages()->functions->get_current_user_id()();
    }

    // No user to check.
    if ( empty( $user_id ) ) {
        return false;
    }

    // Assume true if not spam or deleted.
    return true;
}
}

if( ! function_exists('bp_disable_profile_sync') ) {
function bp_disable_profile_sync( $default = false ) {

    /**
     * Filters whether or not profile syncing is disabled.
     *
     * @since 1.6.0
     *
     * @param bool $value Whether or not syncing is disabled.
     */
    return (bool) apply_filters( 'bp_disable_profile_sync', false );
}}


if( ! function_exists('bp_core_get_status_sql') ) {
function bp_core_get_status_sql( $prefix = false ) {
    if ( !is_multisite() )
        return "{$prefix}user_status = 0";
    else
        return "{$prefix}spam = 0 AND {$prefix}deleted = 0 AND {$prefix}user_status = 0";
}
}

if( ! function_exists('bp_esc_like') ) {
function bp_esc_like( $text ) {
    global $wpdb;

    if ( method_exists( $wpdb, 'esc_like' ) ) {
        return $wpdb->esc_like( $text );
    } else {
        return addcslashes( $text, '_%\\' );
    }
}
}

if( ! function_exists('bp_is_username_compatibility_mode') ) {
function bp_is_username_compatibility_mode() {

    /**
     * Filters whether or not to use username compatibility mode.
     *
     * @since 1.5.0
     *
     * @param bool $value Whether or not username compatibility mode should be used.
     */
    return apply_filters( 'bp_is_username_compatibility_mode', defined( 'BP_ENABLE_USERNAME_COMPATIBILITY_MODE' ) && BP_ENABLE_USERNAME_COMPATIBILITY_MODE );
}}

if( ! function_exists('bp_core_get_userid_from_nicename') ) {
function bp_core_get_userid_from_nicename( $user_nicename = '' ) {
    if ( empty( $user_nicename ) ) {
        return false;
    }

    $user = get_user_by( 'slug', $user_nicename );

    /**
     * Filters the user ID based on user_nicename.
     *
     * @since 1.2.3
     *
     * @param int|null $value         ID of the user or null.
     * @param string   $user_nicename User nicename to check.
     */
    return apply_filters( 'bp_core_get_userid_from_nicename', ! empty( $user->ID ) ? $user->ID : NULL, $user_nicename );
}
}

if( ! function_exists('bp_filter_metaid_column_name') ) {
    function bp_filter_metaid_column_name( $q ) {
        /*
         * Replace quoted content with __QUOTE__ to avoid false positives.
         * This regular expression will match nested quotes.
         */
        $quoted_regex = "/'[^'\\\\]*(?:\\\\.[^'\\\\]*)*'/s";
        preg_match_all( $quoted_regex, $q, $quoted_matches );
        $q = preg_replace( $quoted_regex, '__QUOTE__', $q );

        $q = str_replace( 'meta_id', 'id', $q );

        // Put quoted content back into the string.
        if ( ! empty( $quoted_matches[0] ) ) {
            for ( $i = 0; $i < count( $quoted_matches[0] ); $i++ ) {
                $quote_pos = strpos( $q, '__QUOTE__' );
                $q = substr_replace( $q, $quoted_matches[0][ $i ], $quote_pos, 9 );
            }
        }

        return $q;
    }
}

    if( ! function_exists('messages_check_thread_access') ) {
    function messages_check_thread_access( $thread_id, $user_id = 0 ) {
        if ( empty( $user_id ) ) {
            $user_id = Better_Messages()->functions->get_current_user_id()();
        }

        return Better_Messages()->functions->check_access( $thread_id, $user_id );
    }
}

if( ! function_exists('messages_get_message_thread_id') ) {
function messages_get_message_thread_id( $message_id = 0 ) {
    global $wpdb;

    return (int) $wpdb->get_var( $wpdb->prepare( "SELECT thread_id FROM " . bm_get_table('messages') . " WHERE id = %d", $message_id ) );
}}

if( ! function_exists('bp_update_user_last_activity') ){
    function bp_update_user_last_activity( $user_id = 0, $time = '' ) {

        // Fall back on current user.
        if ( empty( $user_id ) ) {
            $user_id = Better_Messages()->functions->get_current_user_id()();
        }

        // Bail if the user id is 0, as there's nothing to update.
        if ( empty( $user_id ) ) {
            return false;
        }

        // Fall back on current time.
        if ( empty( $time ) ) {
            $time = bp_core_current_time();
        }

        Better_Messages()->users->update_last_activity( $user_id, $time );

        if( class_exists( 'BP_Core_User' ) ){
            return BP_Core_User::update_last_activity( $user_id, $time );
        } else {
            return true;
        }
    }
}

if( ! function_exists('_bp_core_moment_js_config_footer') ){
    function _bp_core_moment_js_config_footer() {
        if ( ! wp_script_is( 'bp-moment-locale' ) ) {
            return;
        }

        printf( '<script>%s</script>', bp_core_moment_js_config() );
    }
}

if( ! function_exists('_bp_core_moment_js_config_footer') ){
    function bp_get_member_user_id() {
        global $members_template;
        $member_id = isset( $members_template->member->id ) ? (int) $members_template->member->id : false;

        /**
         * Filters the ID of the current member in the loop.
         *
         * @since 1.2.0
         *
         * @param int $member_id ID of the member being iterated over.
         */
        return apply_filters( 'bp_get_member_user_id', $member_id );
    }
}

if( ! function_exists('bp_core_can_edit_settings') ){
    function bp_core_can_edit_settings() {
        return current_user_can( 'manage_options' );
    }
}
