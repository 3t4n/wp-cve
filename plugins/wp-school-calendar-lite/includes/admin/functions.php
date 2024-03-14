<?php
function wpsc_options_for_gutenberg_block() {
    $calendars = wpsc_get_calendars();
    
    $gutenberg_options = array(
        array(
            'value' => '',
            'label' => __( 'Select Calendar', 'wp-school-calendar' )
        )
    );
    
    if ( is_array( $calendars ) ) {
        foreach ( $calendars as $calendar ) {
            $gutenberg_options[] = array(
                'value' => $calendar['calendar_id'],
                'label' => $calendar['name']
            );
        }
    }
    
    return $gutenberg_options;
}

function wpsc_reorder_categories() {
    $categories = wpsc_get_categories();
    
    foreach ( $categories as $order => $category ) {
        wpsc_save_category_order( $category['category_id'], $order );
    }
}

/**
 * Add new important date category
 * 
 * @since 1.0
 * 
 * @param array $args Array of arguments
 * @return int Category ID
 */
function wpsc_add_new_category( $args ) {
    $category_id = wp_insert_post( array( 
        'post_type'   => 'important_date_cat',
        'post_title'  => $args['name'],
        'post_status' => 'publish'
    ) );
    
    update_post_meta( $category_id, '_bgcolor', $args['bgcolor'] );
    update_post_meta( $category_id, '_order', 0 );
    
    wpsc_reorder_categories();
    
    return $category_id;
}

/**
 * Delete important date category
 * 
 * @since 1.0
 * 
 * @global wpdb $wpdb wpdb object
 * @param int $category_id Category ID
 * @return boolean True if success
 */
function wpsc_delete_category( $category_id ) {
    global $wpdb;
    
    $default_category = wpsc_settings_value( 'default_category' );
    
    $data = array(
        'meta_value' => $default_category
    );
    
    $where = array(
        'meta_key'   => '_category_id',
        'meta_value' => $category_id
    );
    
    $wpdb->update( $wpdb->postmeta, $data, $where, array( '%d' ), array( '%s', '%d' ) );
    
    wp_delete_post( $category_id, true );
    
    return true;
}

function wpsc_save_category_order( $category_id, $order ) {
    update_post_meta( $category_id, '_order', $order );
}

/**
 * Save important date category
 * 
 * @since 1.0
 * 
 * @param array $args Array of arguments
 * @return boolean True if success
 */
function wpsc_save_category( $args ) {
    wp_update_post( array(
        'ID'         => $args['category_id'],
        'post_title' => $args['name'],
    ) );
    
    update_post_meta( $args['category_id'], '_bgcolor', $args['bgcolor'] );
    
    return true;
}

function wpsc_create_initial_options() {
    $category_id = wp_insert_post( array( 
        'post_type'   => 'important_date_cat',
        'post_title'  => __( 'General Events', 'wp-school-calendar' ),
        'post_status' => 'publish'
    ) );

    update_post_meta( $category_id, '_bgcolor', '#006680' );
    update_post_meta( $category_id, '_order', 0 );
    
    $options = wpsc_get_default_settings();
    
    $options['default_category']= $category_id;
    
    update_option( 'wpsc_options', $options );
}

function wpsc_upgrade_34() {
    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.2', '>' ) && version_compare( $wpsc_version, '3.4', '<' ) ) {
        $calendar_page_id  = wpsc_settings_value( 'calendar_page' );
        $ori_calendar_page = get_post( $calendar_page_id );

        $post_content = $ori_calendar_page->post_content . '[wp_school_calendar]';

        wp_update_post( array(
            'ID'           => $calendar_page_id,
            'post_content' => $post_content,
        ) );
        
        update_option( 'wpsc_version', '3.4.0.1' );
    }
}

function wpsc_upgrade_381() {
    //calendar - show_important_date_cats
    global $wpdb;
    
    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.8', '>=' ) && version_compare( $wpsc_version, '3.8.1', '<' ) ) {
        // update calendar options
        $sql  = "SELECT p.ID AS post_id, pm.meta_value AS calendar_options ";
        $sql .= "FROM {$wpdb->posts} p ";
        $sql .= "LEFT JOIN {$wpdb->postmeta} pm on p.ID = pm.post_id ";
        $sql .= "WHERE p.post_type = 'school_calendar' AND pm.meta_key = '_calendar_options'";

        $results = $wpdb->get_results( $sql, ARRAY_A );

        foreach ( $results as $result ) {
            $post_id          = $result['post_id'];
            $calendar_options = maybe_unserialize( $result['calendar_options'] );

            $new_calendar_options = array();
            
            foreach ( $calendar_options as $key => $value ) {
                $new_calendar_options[$key] = $value;
            }
            
            $new_calendar_options['show_important_date_cats'] = 'Y';
            
            $new_calendar_options = apply_filters( 'wpsc_upgrade_381_calendar_options', $new_calendar_options );
                
            update_post_meta( $post_id, '_calendar_options', $new_calendar_options );
        }
        
        update_option( 'wpsc_version', '3.8.1' );
    }
}

function wpsc_upgrade_38() {
    global $wpdb;
    
    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.7.1', '>' ) && version_compare( $wpsc_version, '3.8', '<' ) ) {
        // update settings - css_location_type & css_location_posts
        $wpsc_options = get_option( 'wpsc_options', array() );
        
        $wpsc_options['css_location_type']  = 'site';
        $wpsc_options['css_location_posts'] = array();
    
        update_option( 'wpsc_options', $wpsc_options );
        
        // update calendar options
        $sql  = "SELECT p.ID AS post_id, pm.meta_value AS calendar_options ";
        $sql .= "FROM {$wpdb->posts} p ";
        $sql .= "LEFT JOIN {$wpdb->postmeta} pm on p.ID = pm.post_id ";
        $sql .= "WHERE p.post_type = 'school_calendar' AND pm.meta_key = '_calendar_options'";

        $results = $wpdb->get_results( $sql, ARRAY_A );

        foreach ( $results as $result ) {
            $post_id          = $result['post_id'];
            $calendar_options = maybe_unserialize( $result['calendar_options'] );

            $new_calendar_options = array();
            
            foreach ( $calendar_options as $key => $value ) {
                $new_calendar_options[$key] = $value;
            }
            
            $new_calendar_options = apply_filters( 'wpsc_upgrade_38_calendar_options', $new_calendar_options );
                
            update_post_meta( $post_id, '_calendar_options', $new_calendar_options );
        }
        
        //upgrade additional notes
        
        $sql  = "SELECT pm.post_id, pm.meta_value AS additional_notes FROM {$wpdb->postmeta} pm WHERE pm.meta_key = '_additional_notes'";
        $results = $wpdb->get_results( $sql, ARRAY_A );

        $important_date_ids = array();
        $data_values        = array();
        $data_params        = array();

        foreach ( $results as $result ) {
            $important_date_ids[] = $result['post_id'];
            
            $data_params[] = '(%d, %s, %s)';
            $data_values[] = $result['post_id'];
            $data_values[] = '_additional_notes';
            
            if ( is_array( maybe_unserialize( $result['additional_notes'] ) ) ) {
                $data_values[] = $result['additional_notes'];
            } else {
                $new_additional_notes = array();

                $new_additional_notes['notes']           = $result['additional_notes'];
                $new_additional_notes['readmore_url']    = '';
                $new_additional_notes['readmore_title']  = '';
                $new_additional_notes['readmore_target'] = '';
                $new_additional_notes['readmore_rel']    = '';
                
                $data_values[] = maybe_serialize( $new_additional_notes );
            }
        }
        
        $_important_date_ids = implode( ',', $important_date_ids );

        $sql = "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_additional_notes' AND post_id IN ({$_important_date_ids})";
        $wpdb->query( $sql );

        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

        if ( count( $data_params ) > 0 ) {
            $sql .= implode( ',', $data_params );
            $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        }
        
        update_option( 'wpsc_version', '3.8.0' );
    }
}

function wpsc_upgrade_371() {
    global $wpdb;
    
    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.7', '>=' ) && version_compare( $wpsc_version, '3.7.1', '<' ) ) {
        // update last modified for recurring important date

        $sql  = "UPDATE {$wpdb->posts} SET post_modified = %s, post_modified_gmt = %s ";
        $sql .= "WHERE post_type IN ('important_date', 'rec_important_date')";
        $wpdb->query( $wpdb->prepare( $sql, array( current_time( 'mysql' ), current_time( 'mysql', 1 ) ) ) );
        
        update_option( 'wpsc_version', '3.7.1' );
    }
}

function wpsc_upgrade_37() {
    global $wpdb;
    
    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.6', '>' ) && version_compare( $wpsc_version, '3.7', '<' ) ) {
        // Update Calendar Options
        // ===============================
        // Add new options:
        // -------------------------------
        // - week_start
        // - num_months
        // - custom_default_month_range
        // -------------------------------
        // Change old options:
        // -------------------------------
        // - calendar_display => num_columns
        // - start_month => start_year
        // - default_school_year => default_month_range
        // - custom_school_year => custom_default_year
        // -------------------------------
        // Delete old options:
        // -------------------------------
        // - end_month

        $sql  = "SELECT p.ID AS post_id, pm.meta_value AS calendar_options ";
        $sql .= "FROM {$wpdb->posts} p ";
        $sql .= "LEFT JOIN {$wpdb->postmeta} pm on p.ID = pm.post_id ";
        $sql .= "WHERE p.post_type = 'school_calendar' AND pm.meta_key = '_calendar_options'";

        $results = $wpdb->get_results( $sql, ARRAY_A );

        foreach ( $results as $result ) {
            $post_id          = $result['post_id'];
            $calendar_options = maybe_unserialize( $result['calendar_options'] );

            $new_calendar_options = array();

            foreach ( $calendar_options as $key => $value ) {
                if ( 'calendar_display' === $key ) {
                    $new_calendar_options['num_columns'] = $value;
                } elseif ( 'start_month' === $key ) {
                    $new_calendar_options['start_year'] = $value;
                } elseif ( 'default_school_year' === $key ) {
                    $new_calendar_options['default_month_range'] = $value;
                } elseif ( 'custom_school_year' === $key ) {
                    $new_calendar_options['custom_default_year'] = $value;
                } elseif ( 'end_month' === $key ) {
                    // remove option
                } else {
                    $new_calendar_options[$key] = $value;
                }
            }

            $new_calendar_options['week_start']                 = intval( get_option( 'start_of_week' ) );
            $new_calendar_options['num_months']                 = 'twelve';
            $new_calendar_options['custom_default_month_range'] = '';

            $new_calendar_options = apply_filters( 'wpsc_upgrade_37_calendar_options', $new_calendar_options );

            update_post_meta( $post_id, '_calendar_options', $new_calendar_options );
        }

        do_action( 'wpsc_upgrade_37_important_date_options' );
        
        update_option( 'wpsc_version', '3.7' );
    }
}

function wpsc_upgrade_36() {
    global $wpdb;
    
    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.4', '>' ) && version_compare( $wpsc_version, '3.6', '<' ) ) {
        $wpsc_options = get_option( 'wpsc_options', array() );

        // create new calendar

        $args = array(
            'name'             => __( 'School Calendar', 'wp-school-calendar' ),
            'calendar_options' => wpsc_get_default_calendar_options()
        );

        if ( isset( $wpsc_options['default_school_year'] ) ) {
            $sql  = "SELECT p.ID, pm.meta_value start_date ";
            $sql .= "FROM {$wpdb->posts} p ";
            $sql .= "LEFT JOIN {$wpdb->postmeta} pm on p.ID = pm.post_id ";
            $sql .= "WHERE pm.meta_key = '_start_date' ";
            $sql .= "AND p.ID = %d";

            $result = $wpdb->get_row( $wpdb->prepare( $sql, array( $wpsc_options['default_school_year'] ) ), ARRAY_A );

            if ( $result ) {
                $start_date = explode( '-', $result['start_date'] );

                $start_month = $start_date[1];
                $end_month   = intval( $start_month ) + 11;

                if ( $end_month > 12 ) {
                    $end_month = $end_month - 12;
                }

                $args['calendar_options']['start_month'] = $start_month;
                $args['calendar_options']['end_month'] = zeroise( $end_month, 2) ;
            }
        }

        if ( isset( $wpsc_options['calendar_display'] ) ) {
            $args['calendar_options']['calendar_display'] = $wpsc_options['calendar_display'];
        }

        if ( isset( $wpsc_options['day_format'] ) ) {
            $args['calendar_options']['day_format'] = $wpsc_options['day_format'];
        }

        if ( isset( $wpsc_options['weekday'] ) ) {
            $args['calendar_options']['weekday'] = $wpsc_options['weekday'];
        }

        if ( isset( $wpsc_options['date_format'] ) ) {
            $args['calendar_options']['date_format'] = $wpsc_options['date_format'];
        }

        if ( isset( $wpsc_options['show_year'] ) ) {
            $args['calendar_options']['show_year'] = $wpsc_options['show_year'];
        }

        if ( isset( $wpsc_options['important_date_heading'] ) ) {
            $args['calendar_options']['important_date_heading'] = $wpsc_options['important_date_heading'];
        }

        $args = apply_filters( 'wpsc_upgrade_36_calendar_args', $args, $wpsc_options );

        $calendar_id = wpsc_add_new_calendar( $args );

        // update category order

        $sql  = "SELECT ID AS category_id FROM $wpdb->posts ";
        $sql .= "WHERE post_type = 'important_date_cat' AND post_status = 'publish' ";

        $categories = $wpdb->get_results( $sql, ARRAY_A );

        foreach ( $categories as $order => $category ) {
            update_post_meta( $category['category_id'], '_order', $order );
        }

        // update gutenberg calendar id

        $sql = "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '<!-- wp:wp-school-calendar/wp-school-calendar /-->', '<!-- wp:wp-school-calendar/wp-school-calendar {\"id\":\"{$calendar_id}\"} /-->') ";
        $wpdb->query( $sql );

        // update shortcode calendar id

        $sql = "UPDATE {$wpdb->posts} SET post_content = REPLACE(post_content, '[wp_school_calendar]', '[wp_school_calendar id=\"{$calendar_id}\"]') ";
        $wpdb->query( $sql );

        // update important_date postmeta

        $sql  = "SELECT pm.post_id, pm.meta_value AS additional_notes FROM {$wpdb->postmeta} pm WHERE pm.meta_key = '_additional_notes'";
        $results = $wpdb->get_results( $sql, ARRAY_A );

        $existing_additional_notes = array();

        foreach ( $results as $result ) {
            $post_id = $result['post_id'];
            $existing_additional_notes[$post_id] = $result['additional_notes'];
        }

        $sql  = "SELECT p.ID AS post_id FROM {$wpdb->posts} p WHERE p.post_type = 'important_date'";
        $results = $wpdb->get_results( $sql, ARRAY_A );

        $important_date_ids = array();

        foreach ( $results as $result ) {
            $important_date_ids[] = $result['post_id'];
        }

        $_important_date_ids = implode( ',', $important_date_ids );

        $sql = "DELETE FROM {$wpdb->postmeta} WHERE meta_key IN ('_additional_notes','_enable_recurring') AND post_id IN ({$_important_date_ids})";
        $wpdb->query( $sql );

        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

        foreach ( $important_date_ids as $important_date_id ) {
            $data_params[] = '(%d, %s, %s)';
            $data_values[] = $important_date_id;
            $data_values[] = '_enable_recurring';
            $data_values[] = 'N';

            $data_params[] = '(%d, %s, %s)';
            $data_values[] = $important_date_id;
            $data_values[] = '_additional_notes';

            if ( count( $existing_additional_notes ) > 0 && isset( $existing_additional_notes[$important_date_id] ) ) {
                $data_values[] = $existing_additional_notes[$important_date_id];
            } else {
                $data_values[] = '';
            }
        }

        $sql .= implode( ',', $data_params );
        $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        
        update_option( 'wpsc_version', '3.6.0.1' );
    }
}

/**
 * Upgrade DB
 * 
 * @since 3.2
 * 
 * @global wpdb $wpdb
 */
function wpsc_upgrade_32() {
    global $wpdb;

    $wpsc_version = get_option( 'wpsc_version', '' );
    
    if ( version_compare( $wpsc_version, '3.0', '>' ) && version_compare( $wpsc_version, '3.2', '<' ) ) {    
        $sql  = "SELECT p.ID AS post_id FROM {$wpdb->posts} p ";
        $sql .= "WHERE p.post_type = 'important_date' AND p.post_status IN ('publish', 'draft') ";

        $results = $wpdb->get_results( $sql, ARRAY_A );

        // Delete old post meta

        $post_ids = array();

        foreach ( $results as $result ) {
            $post_ids[] = $result['post_id'];
        }

        $post_ids = implode( ',', $post_ids );

        $sql = "DELETE FROM {$wpdb->postmeta} WHERE meta_key = '_exclude_weekend' AND post_id IN ({$post_ids})";
        $wpdb->query( $sql );

        // Create post meta

        $data_values = array();
        $data_params = array();

        $sql = "INSERT INTO {$wpdb->postmeta} (post_id, meta_key, meta_value) VALUES ";

        foreach ( $results as $result ) {
            $data_params[] = '(%d, %s, %s)';

            $data_values[] = $result['post_id'];
            $data_values[] = '_exclude_weekend';
            $data_values[] = 'Y';
        }

        $sql .= implode( ',', $data_params );
        $wpdb->query( $wpdb->prepare( $sql, $data_values ) );
        
        update_option( 'wpsc_version', '3.2.0.1' );
    }
}

function wpsc_check_valid_date( $date ) {
    // Date format MM/DD/YYYY
    if ( preg_match ( "/^([0-9]{2})\\/([0-9]{2})\\/([0-9]{4})$/", $date, $parts ) ) {
        if ( checkdate( $parts[1], $parts[2], $parts[3] ) ) {
            return true;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

function wpsc_add_new_calendar( $args ) {
    $calendar_id = wp_insert_post( array( 
        'post_type'   => 'school_calendar',
        'post_title'  => $args['name'],
        'post_status' => 'publish'
    ) );
    
    $calendar = array(
        'ID'        => $calendar_id,
        'post_name' => sprintf( 'school-calendar-%s', $calendar_id )
    );
    
    if ( empty( $args['name'] ) ) {
        $calendar['post_title'] = sprintf( __( 'School Calendar #%s', 'wp-school-calendar' ), $calendar_id );
    }
    
    wp_update_post( $calendar );
    
    update_post_meta( $calendar_id, '_calendar_options', $args['calendar_options'] );
    
    return $calendar_id;
}

function wpsc_duplicate_calendar( $calendar_id ) {
    $old_calendar_id = intval( $calendar_id );
    $obj = get_post( $old_calendar_id );
    
    if ( $obj ) {
        if ( 'school_calendar' !== $obj->post_type ) {
            return false;
        }
        
        $new_post_title = sprintf( __( '%s (Duplicate)', 'wp-school-calendar' ), $obj->post_title );
        
        $new_calendar_options = get_post_meta( $old_calendar_id, '_calendar_options', true );
        
        $new_calendar_id = wp_insert_post( array( 
            'post_type'   => 'school_calendar',
            'post_title'  => $new_post_title,
            'post_status' => 'publish'
        ) );
        
        update_post_meta( $new_calendar_id, '_calendar_options', $new_calendar_options );
    }
    
    return true;
}

/**
 * Delete calendar
 * 
 * @since 1.0
 * 
 * @param int $calendar_id Calendar ID
 * @return boolean True if success
 */
function wpsc_delete_calendar( $calendar_id ) {
    wp_delete_post( $calendar_id, true );
    return true;
}

/**
 * Update calendar
 * 
 * @since 1.0
 * 
 * @param array $args Array of arguments
 * @return boolean True if success
 */
function wpsc_update_calendar( $args ) {
    if ( empty( $args['name'] ) ) {
        $args['name'] = sprintf( __( 'School Calendar #%s', 'wp-school-calendar' ), $args['calendar_id'] );
    }
    
    wp_update_post( array(
        'ID'         => $args['calendar_id'],
        'post_title' => $args['name'],
    ) );
    
    update_post_meta( $args['calendar_id'], '_calendar_options', $args['calendar_options'] );
    
    return true;
}

function wpsc_sanitize_multiple_groups( $inputs ) {
    $available_groups = wpsc_get_groups();

    $group_ids = array();

    foreach ( $available_groups as $group ) {
        $group_ids[] = $group['group_id'];
    }

    $valid_groups = array();
    $inputs = explode( ',', $inputs );
    
    foreach ( $inputs as $input ) {
        if ( in_array( $input, $group_ids ) ) {
            $valid_groups[] = $input;
        }
    }

    return $valid_groups;
}

function wpsc_sanitize_multiple_categories( $inputs ) {
    $available_categories = wpsc_get_categories();

    $category_ids = array();

    foreach ( $available_categories as $category ) {
        $category_ids[] = $category['category_id'];
    }

    $valid_categories = array();
    $inputs = explode( ',', $inputs );

    foreach ( $inputs as $input ) {
        if ( in_array( $input, $category_ids ) ) {
            $valid_categories[] = $input;
        }
    }

    return $valid_categories;
}

function wpsc_sanitize_week_start( $input ) {
    $available_weekdays = wpsc_get_weekday_options();
    
    if ( in_array( $input, array_keys( $available_weekdays ) ) ) {
        return $input;
    }
    
    return 0;
}

function wpsc_sanitize_multiple_weekday( $inputs ) {
    $available_weekdays = wpsc_get_weekday_options();

    $valid_weekdays = array();
    $inputs = explode( ',', $inputs );

    foreach ( $inputs as $input ) {
        if ( in_array( $input, array_keys( $available_weekdays ) ) ) {
            $valid_weekdays[] = $input;
        }
    }

    return $valid_weekdays;
}

function wpsc_sanitize_year( $input ) {
    $options = wpsc_get_year_options();

    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return date( 'Y' );
}

function wpsc_sanitize_month( $input ) {
    $month_options = wpsc_get_month_options();

    if ( in_array( $input, array_keys( $month_options ) ) ) {
        return $input;
    }

    return '01';
}

function wpsc_sanitize_default_month_range( $input ) {
    $options = wpsc_get_default_month_range_options();
    
    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return 'current';
}

function wpsc_sanitize_custom_default_year( $input, $start_year ) {
    $options = wpsc_get_custom_default_year_options( $start_year );
    
    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return array_key_first( $options );
}

function wpsc_sanitize_custom_default_month_range( $input, $start_year, $num_months ) {
    $options = wpsc_get_custom_default_month_range_options( $start_year, $num_months );
    
    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return array_key_first( $options );
}

function wpsc_sanitize_num_months( $input ) {
    $options = wpsc_get_num_month_options();
    
    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return 'twelve';
}

function wpsc_sanitize_start_year( $input ) {
    $options = wpsc_get_start_year_options();
    
    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return 'current';
}

function wpsc_sanitize_start_month( $input ) {
    $options = wpsc_get_start_month_options();
    
    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return 'current';
}

function wpsc_sanitize_num_columns( $input ) {
    $options = wpsc_get_num_column_options();

    if ( in_array( $input, array_keys( $options ) ) ) {
        return $input;
    }

    return 'three-columns';
}

function wpsc_sanitize_calendar_theme( $input ) {
    $theme_options = wpsc_get_calendar_theme_options();

    if ( in_array( $input, array_keys( $theme_options ) ) ) {
        return $input;
    }

    return 'default';
}

function wpsc_sanitize_day_format( $input ) {
    $day_format_options = wpsc_get_day_format_options();

    if ( in_array( $input, array_keys( $day_format_options ) ) ) {
        return $input;
    }

    return 'one-letter';
}

function wpsc_sanitize_date_format( $input ) {
    $date_format_options = wpsc_get_date_format_options();

    if ( in_array( $input, array_keys( $date_format_options ) ) ) {
        return $input;
    }

    return 'medium';
}

function wpsc_sanitize_checkbox( $input ) {
    if ( isset( $input ) && 'Y' === $input ) {
        return 'Y';
    } else {
        return 'N';
    }
}