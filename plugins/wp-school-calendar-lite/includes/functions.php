<?php
/**
 * Get the default of plugin settings
 * 
 * @since 1.0
 * 
 * @return array Default settings
 */
function wpsc_get_default_settings() {
    $default_settings = array();
    $settings = require WPSC_PLUGIN_DIR . 'config/plugin-settings.php';

    foreach ( $settings as $key => $setting ) {
        $default_settings[$key] = $setting['default_value'];
    }
    
    return $default_settings;
}

/**
 * Get the value of plugin settings
 * 
 * @since 1.0
 * 
 * @global type $wpsc_options
 * 
 * @param string $key Settings key
 * @return string Settings value or False if the setting not found
 */
function wpsc_settings_value( $key = false ) {
    global $wpsc_options;
    
    $default_settings = wpsc_get_default_settings();
            
    if ( empty( $wpsc_options ) ) {
        $wpsc_options = get_option( 'wpsc_options', $default_settings );
    }
    
    if ( isset( $wpsc_options[$key] ) ) {
        return $wpsc_options[$key];
    } elseif ( isset( $default_settings[$key] ) ) {
        return $default_settings[$key];
    } else {
        return false;
    }
}

function wpsc_get_calendar_group_list( $args ) {
    global $wpdb;

    $params = array();

    $post_types = apply_filters( 'wpsc_important_dates_query_post_types', array( 'important_date' ) );
    $placeholders = array_fill( 0, count( $post_types ), '%s' );

    foreach ( $post_types as $post_type ) {
        $params[] = $post_type;
    }

    $sql  = "SELECT tr.term_taxonomy_id ";
    $sql .= "FROM {$wpdb->posts} p ";
    $sql .= "LEFT JOIN {$wpdb->postmeta} pm1 on p.ID = pm1.post_id ";
    $sql .= "LEFT JOIN {$wpdb->postmeta} pm2 on p.ID = pm2.post_id ";
    $sql .= "LEFT JOIN $wpdb->term_relationships tr on p.ID = tr.object_id ";

    $sql .= sprintf( 'WHERE p.post_type IN (%s) ', implode( ', ', $placeholders ) );
    $sql .= "AND p.post_status = 'publish' ";
    $sql .= "AND pm1.meta_key = '_start_date' and pm2.meta_key = '_end_date' ";
    $sql .= "AND tr.term_taxonomy_id IS NOT NULL ";
    
    if ( ! empty( $args['groups'] ) ) {
        $placeholders = array_fill( 0, count( $args['groups'] ), '%d' );
        $sql .= sprintf( 'AND tr.term_taxonomy_id IN (%s) ', implode( ', ', $placeholders ) );
    }

    if ( isset( $args['start_date'] ) && isset( $args['end_date'] ) ) {
        $sql .= "AND ((CAST(pm1.meta_value AS DATE) >= %s AND CAST(pm2.meta_value AS DATE) <= %s) OR ";
        $sql .= "(CAST(pm1.meta_value AS DATE) >= %s AND CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s) OR ";
        $sql .= "(CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s AND CAST(pm2.meta_value AS DATE) <= %s) OR ";
        $sql .= "(CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s)) ";

        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
        $params[] = $args['end_date'];
        $params[] = $args['start_date'];
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
    }

    $results = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );

    $groups = array();

    foreach ( $results as $result ) {
        $groups[] = $result['term_taxonomy_id'];
    }

    return array_unique( $groups );
}

function wpsc_get_calendar_category_list( $args ) {
    global $wpdb;

    $params = array();
    
    $post_types = apply_filters( 'wpsc_important_dates_query_post_types', array( 'important_date' ) );
    $placeholders = array_fill( 0, count( $post_types ), '%s' );
    
    foreach ( $post_types as $post_type ) {
        $params[] = $post_type;
    }
    
    $sql  = "SELECT pm3.meta_value AS category_id ";
    $sql .= "FROM {$wpdb->posts} p ";
    $sql .= "LEFT JOIN {$wpdb->postmeta} pm1 on p.ID = pm1.post_id ";
    $sql .= "LEFT JOIN {$wpdb->postmeta} pm2 on p.ID = pm2.post_id ";
    $sql .= "LEFT JOIN {$wpdb->postmeta} pm3 on p.ID = pm3.post_id ";
    $sql .= "LEFT JOIN {$wpdb->postmeta} pm4 on p.ID = pm4.post_id ";
    
    if ( ! empty( $args['groups'] ) ) {
        $sql .= "LEFT JOIN $wpdb->term_relationships tr on p.ID = tr.object_id ";
    }
    
    $sql .= sprintf( 'WHERE p.post_type IN (%s) ', implode( ', ', $placeholders ) );
    $sql .= "AND p.post_status = 'publish' ";
    $sql .= "AND pm1.meta_key = '_start_date' and pm2.meta_key = '_end_date' ";
    $sql .= "AND pm3.meta_key = '_category_id' AND pm4.meta_key = '_exclude_weekend' ";
    
    if ( ! empty( $args['groups'] ) ) {
        $placeholders = array_fill( 0, count( $args['groups'] ), '%d' );
        
        if ( isset( $args['include_no_groups'] ) && 'Y' === $args['include_no_groups'] ) {
            $sql .= sprintf( 'AND (tr.term_taxonomy_id IS NULL OR tr.term_taxonomy_id IN (%s)) ', implode( ', ', $placeholders ) );
        } else {
            $sql .= sprintf( 'AND tr.term_taxonomy_id IN (%s) ', implode( ', ', $placeholders ) );
        }
        
        foreach ( $args['groups'] as $group ) {
            $params[] = $group;
        }
    }
    
    if ( ! empty( $args['categories'] ) ) {
        $placeholders = array_fill( 0, count( $args['categories'] ), '%d' );
        $sql .= sprintf( 'AND pm3.meta_value IN (%s) ', implode( ', ', $placeholders ) );
        
        foreach ( $args['categories'] as $cat ) {
            $params[] = $cat;
        }
    }
    
    if ( isset( $args['start_date'] ) && isset( $args['end_date'] ) ) {
        $sql .= "AND ((CAST(pm1.meta_value AS DATE) >= %s AND CAST(pm2.meta_value AS DATE) <= %s) OR ";
        $sql .= "(CAST(pm1.meta_value AS DATE) >= %s AND CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s) OR ";
        $sql .= "(CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s AND CAST(pm2.meta_value AS DATE) <= %s) OR ";
        $sql .= "(CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s)) ";
    
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
        $params[] = $args['end_date'];
        $params[] = $args['start_date'];
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
        $params[] = $args['start_date'];
        $params[] = $args['end_date'];
    }

    $results = $wpdb->get_results( $wpdb->prepare( $sql, $params ), ARRAY_A );
    
    $categories = array();
    
    foreach ( $results as $result ) {
        $categories[] = $result['category_id'];
    }
    
    return array_unique( $categories );
}

/**
 * Get important date category name
 * 
 * @since 1.0
 * 
 * @param int $category_id Important date category ID
 * @return string Important date category name
 */
function wpsc_get_category_name( $category_id ) {
    $category = wpsc_get_category( $category_id );
    return $category['name'];
}

/**
 * Get important date categories
 * 
 * @since 1.0
 * 
 * @global wpdb $wpdb wpdb object
 * @return array Array of important date categories
 */
function wpsc_get_categories() {
    global $wpdb, $wpsc_categories;
    
    if ( is_array( $wpsc_categories ) && count( $wpsc_categories ) > 0 ) {
        return $wpsc_categories;
    }
    
    $args = "SELECT p.ID AS category_id, p.post_title AS name, pm1.meta_value AS bgcolor ";
    $args .= "FROM $wpdb->posts p LEFT JOIN $wpdb->postmeta pm1 ON p.ID = pm1.post_id LEFT JOIN $wpdb->postmeta pm2 ON p.ID = pm2.post_id ";
    $args .= "WHERE p.post_type = 'important_date_cat' AND p.post_status = 'publish' AND pm1.meta_key = '_bgcolor' AND pm2.meta_key = '_order' ";
    $args .= "ORDER BY abs(pm2.meta_value) ASC";
    
    $wpsc_categories = $wpdb->get_results( $args, ARRAY_A );

    return $wpsc_categories;
}

function wpsc_get_category_options() {
    $categories = wpsc_get_categories();
    
    $options = array();
    
    foreach ( $categories as $category ) {
        $options[$category['category_id']] = $category['name'];
    }
    
    return $options;
}

/**
 * Get single important date category
 * 
 * @since 1.0
 * 
 * @param int $id Important date category ID
 * @return array|false Array of important date category of False if not found
 */
function wpsc_get_category( $id ) {
    $obj = get_post( $id );
    
    if ( $obj ) {
        $category = array(
            'category_id' => $obj->ID,
            'name'        => $obj->post_title,
            'bgcolor'     => get_post_meta( $obj->ID, '_bgcolor', true ),
        );
        
        return $category;
    }
    
    return false;
}

function wpsc_get_groups() {
    $objs = get_terms( array(
        'taxonomy'   => 'important_date_group',
        'hide_empty' => false,
    ) );
    
    if ( $objs ) {
        $groups = array();
        
        foreach ( $objs as $obj ) {
            $groups[] = array(
                'group_id' => $obj->term_id,
                'name' => $obj->name,
                'slug' => $obj->slug
            );
        }
        
        return $groups;
    }
    
    return false;
}

/**
 * Get important dates
 * 
 * @since 1.0
 * 
 * @global wpdb $wpdb wpdb object
 * @param array $args Array of arguments
 * @return array Array of important dates
 */
function wpsc_get_important_dates( $args ) {
    global $wpdb;
    
    $query_params = array();
    
    $query_select  = "SELECT p.ID AS post_id, p.post_title AS important_date_title, ";
    $query_select .= "p.post_type, p.post_parent, p.post_modified, ";
    $query_select .= "pm1.meta_value AS start_date, pm2.meta_value AS end_date, ";
    $query_select .= "pm3.meta_value AS category_id, pm4.meta_value AS exclude_weekend ";
    
    $query_from = "FROM {$wpdb->posts} p ";
    
    $query_join  = "LEFT JOIN {$wpdb->postmeta} pm1 on p.ID = pm1.post_id ";
    $query_join .= "LEFT JOIN {$wpdb->postmeta} pm2 on p.ID = pm2.post_id ";
    $query_join .= "LEFT JOIN {$wpdb->postmeta} pm3 on p.ID = pm3.post_id ";
    $query_join .= "LEFT JOIN {$wpdb->postmeta} pm4 on p.ID = pm4.post_id ";
    
    $post_types = apply_filters( 'wpsc_important_dates_query_post_types', array( 'important_date' ) );
    $placeholders = array_fill( 0, count( $post_types ), '%s' );
    
    foreach ( $post_types as $post_type ) {
        $query_params[] = $post_type;
    }
    
    $query_where  = sprintf( 'WHERE p.post_type IN (%s) ', implode( ', ', $placeholders ) );
    $query_where .= "AND p.post_status = 'publish' ";
    $query_where .= "AND pm1.meta_key = '_start_date' AND pm2.meta_key = '_end_date' ";
    $query_where .= "AND pm3.meta_key = '_category_id' AND pm4.meta_key = '_exclude_weekend' ";
    
    if ( ! empty( $args['groups'] ) ) {
        $query_join .= "LEFT JOIN $wpdb->term_relationships tr on p.ID = tr.object_id ";
        $query_select .= ', tr.object_id ';
        
        $placeholders = array_fill( 0, count( $args['groups'] ), '%d' );
        
        if ( isset( $args['include_no_groups'] ) && 'Y' === $args['include_no_groups'] ) {
            $query_where .= sprintf( 'AND (tr.term_taxonomy_id IS NULL OR tr.term_taxonomy_id IN (%s)) ', implode( ', ', $placeholders ) );
        } else {
            $query_where .= sprintf( 'AND tr.term_taxonomy_id IN (%s) ', implode( ', ', $placeholders ) );
        }
        
        foreach ( $args['groups'] as $group ) {
            $query_params[] = $group;
        }
    }
    
    if ( ! empty( $args['categories'] ) ) {
        $placeholders = array_fill( 0, count( $args['categories'] ), '%d' );
        $query_where .= sprintf( 'AND pm3.meta_value IN (%s) ', implode( ', ', $placeholders ) );
        
        foreach ( $args['categories'] as $cat ) {
            $query_params[] = $cat;
        }
    }
    
    if ( isset( $args['start_date'] ) && isset( $args['end_date'] ) ) {
        $query_where .= "AND ((CAST(pm1.meta_value AS DATE) >= %s AND CAST(pm2.meta_value AS DATE) <= %s) OR ";
        $query_where .= "(CAST(pm1.meta_value AS DATE) >= %s AND CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s) OR ";
        $query_where .= "(CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s AND CAST(pm2.meta_value AS DATE) <= %s) OR ";
        $query_where .= "(CAST(pm1.meta_value AS DATE) <= %s AND CAST(pm2.meta_value AS DATE) >= %s)) ";
    
        $query_params[] = $args['start_date'];
        $query_params[] = $args['end_date'];
        $query_params[] = $args['start_date'];
        $query_params[] = $args['end_date'];
        $query_params[] = $args['end_date'];
        $query_params[] = $args['start_date'];
        $query_params[] = $args['start_date'];
        $query_params[] = $args['end_date'];
        $query_params[] = $args['start_date'];
        $query_params[] = $args['end_date'];
    }
    
    if ( isset( $args['post_ids'] ) ) {
        $query_where .= sprintf( 'AND p.ID IN (%s) ', $args['post_ids'] );
    }
    
    $query_select = apply_filters( 'wpsc_important_dates_query_select', $query_select );
    $query_from   = apply_filters( 'wpsc_important_dates_query_from', $query_from );
    $query_join   = apply_filters( 'wpsc_important_dates_query_join', $query_join );
    $query_where  = apply_filters( 'wpsc_important_dates_query_where', $query_where );
    $query_params = apply_filters( 'wpsc_important_dates_query_params', $query_params );
    
    $sql = $query_select . $query_from . $query_join . $query_where;
    
    $sql .= "ORDER BY start_date ASC ";
    
    if ( isset( $args['posts_per_page'] ) ) {
        $sql .= "LIMIT 0, %d ";
        $query_params[] = $args['posts_per_page'];
    }
    
    $results = $wpdb->get_results( $wpdb->prepare( $sql, $query_params ), ARRAY_A );
    
    return $results;
}

/**
 * Get single important date
 * 
 * @since 1.0
 * 
 * @global wpdb $wpdb wpdb object
 * @param int $id Important date ID
 * @return array Array of important date
 */
function wpsc_get_important_date( $id ) {
    global $wpdb;

    $id = intval( $id );
    
    $query_params = array();
    
    $query_select  = "SELECT p.ID AS post_id, p.post_title AS important_date_title, ";
    $query_select .= "pm1.meta_value AS start_date, pm2.meta_value AS end_date, ";
    $query_select .= "pm3.meta_value AS category_id, pm4.meta_value AS exclude_weekend ";
    
    $query_from = "FROM {$wpdb->posts} p ";
    
    $query_join  = "LEFT JOIN {$wpdb->postmeta} pm1 on p.ID = pm1.post_id ";
    $query_join .= "LEFT JOIN {$wpdb->postmeta} pm2 on p.ID = pm2.post_id ";
    $query_join .= "LEFT JOIN {$wpdb->postmeta} pm3 on p.ID = pm3.post_id ";
    $query_join .= "LEFT JOIN {$wpdb->postmeta} pm4 on p.ID = pm4.post_id ";
    
    $post_types = apply_filters( 'wpsc_important_dates_query_post_types', array( 'important_date' ) );
    $placeholders = array_fill( 0, count( $post_types ), '%s' );
    
    foreach ( $post_types as $post_type ) {
        $query_params[] = $post_type;
    }
    
    $query_where  = sprintf( 'WHERE p.post_type IN (%s) ', implode( ', ', $placeholders ) );
    $query_where .= "AND p.post_status = 'publish' ";
    $query_where .= "AND pm1.meta_key = '_start_date' and pm2.meta_key = '_end_date' AND pm3.meta_key = '_category_id' AND pm4.meta_key = '_exclude_weekend' ";
    $query_where .= "AND p.ID = %d "; 
    
    $query_params[] = $id;
    
    $query_select = apply_filters( 'wpsc_single_important_date_query_select', $query_select );
    $query_from   = apply_filters( 'wpsc_single_important_date_query_from', $query_from );
    $query_join   = apply_filters( 'wpsc_single_important_date_query_join', $query_join );
    $query_where  = apply_filters( 'wpsc_single_important_date_query_where', $query_where );
    $query_params = apply_filters( 'wpsc_single_important_date_query_params', $query_params );
    
    $results = $wpdb->get_row( $wpdb->prepare( $query_select . $query_from . $query_join . $query_where, $query_params ), ARRAY_A );

    return $results;
}

function wpsc_get_important_date_groups( $id ) {
    $groups = wp_get_post_terms( $id, 'important_date_group', array( 'fields' => 'ids' ) );
    
    return $groups;
}

/**
 * Get date format options
 * 
 * @since 1.0
 * 
 * @return array Array of date format options
 */
function wpsc_get_date_format_options() {
    $options = array(
        'short'      => __( 'Short (Example: 11/30)', 'wp-school-calendar' ),
        'medium'     => __( 'Medium (Example: Nov 30)', 'wp-school-calendar' ),
        'long'       => __( 'Long (Example: November 30)', 'wp-school-calendar' ),
        'short-alt'  => __( 'Short Alt (Example: 30/11)', 'wp-school-calendar' ),
        'medium-alt' => __( 'Medium Alt (Example: 30 Nov)', 'wp-school-calendar' ),
        'long-alt'   => __( 'Long Alt (Example: 30 November)', 'wp-school-calendar' ),
    );
    
    return $options;
}

/**
 * Get weekday options
 * 
 * @since 1.0
 * 
 * @global array $wp_locale WP_Locale object
 * @return array Array of weekday options
 */
function wpsc_get_weekday_options() {
    global $wp_locale;
    
    $weekdays = array();
    
    for ( $i = 0; $i < 7; $i = $i +1 ) {
        $weekdays[] = $wp_locale->get_weekday($i);
    }
    
    return $weekdays;
}

function wpsc_get_month_options() {
    global $wp_locale;
    
    $months = array();
    
    for ( $i = 1; $i < 13; $i = $i +1 ) {
        $monthnum = zeroise($i, 2);
        $monthtext = $wp_locale->get_month( $i );
        $months[$monthnum] = $monthtext;
    }

    return $months;
}

function wpsc_get_year_options() {
    $last_year = 2037;
    $years = array();
    
    for ( $i = 2018; $i < $last_year; $i++ ) {
        $years[$i] = $i;
    }
    
    return $years;
}

function wpsc_get_week_of_month_options() {
    $options = array(
        'first'  => __( 'first', 'wp-school-calendar' ),
        'second' => __( 'second', 'wp-school-calendar' ),
        'third'  => __( 'third', 'wp-school-calendar' ),
        'fourth' => __( 'fourth', 'wp-school-calendar' ),
        'last'   => __( 'last', 'wp-school-calendar' )
    );
    
    return $options;
}

function wpsc_get_calendar_theme_options() {
    $theme_options = apply_filters( 'wpsc_calendar_theme', array( 
        'default' => array( 
            'name'   => __( 'Default', 'wp-school-year' ),
            'enable' => 'Y'
        )
    ) );
    
    return $theme_options;
}

function wpsc_get_num_month_options() {
    $options = array( 
        'twelve' => __( '12 Months', 'wp-school-year' ),
        'six'    => __( '6 Months', 'wp-school-year' ),
        'four'   => __( '4 Months', 'wp-school-year' ),
        'three'  => __( '3 Months', 'wp-school-year' ),
        'one'    => __( 'One Month', 'wp-school-year' )
    );
    
    return $options;
}

function wpsc_get_default_month_range_options() {
    $options = array( 
        'current' => __( 'Current Range', 'wp-school-year' ),
        'custom'  => __( 'Custom', 'wp-school-year' ) 
    );
    
    return $options;
}

function wpsc_get_year_range_options( $start_of_the_year, $num_months ) {
    global $wp_locale;
    
    $year_range_options = array();
    
    $month_addition  = array( 'twelve' => 11, 'six' => 5, 'four' => 3, 'three' => 2 );
    $max_month_range = array( 'twelve' => 1, 'six' => 2, 'four' => 3, 'three' => 4 );
    
    $start_key = 2018;
    $end_key   = 2037;
    
    for ( $i = $start_key - 1; $i < $end_key; $i++ ) {
        $month_range_options = array();
        
        if ( '01' === $start_of_the_year ) {
            if ( 'one' === $num_months ) {
                for ( $j = 1; $j <= 12; $j++ ) {
                    $month_range_option = array(
                        'id'   => sprintf( '%s:%s', $i, zeroise( $j, 2 ) ),
                        'name' => $wp_locale->get_month( zeroise( $j, 2 ) )
                    );
                    
                    $month_range_options[] = $month_range_option;
                }
            } else {
                for ( $j = 0; $j < $max_month_range[$num_months]; $j++ ) {
                    $start_month_number = intval( $start_of_the_year ) + ( $j * ( $month_addition[$num_months] + 1 ) );
                    $end_month_number = $start_month_number + $month_addition[$num_months];
                    
                    $month_range_option = array(
                        'id'   => sprintf( '%s:%s-%s:%s', $i, zeroise( $start_month_number, 2 ), $i, zeroise( $end_month_number, 2 ) ),
                        'name' => sprintf( '%s - %s', $wp_locale->get_month( zeroise( $start_month_number, 2 ) ), $wp_locale->get_month( zeroise( $end_month_number, 2 ) ) )
                    );
                    
                    $month_range_options[] = $month_range_option;
                }
            }
            
            $year_range_option = array(
                'id'           => $i,
                'name'         => $i,
                'month_ranges' => $month_range_options
            );
        } else {
            if ( 'one' === $num_months ) {
                for ( $j = 0; $j < 12; $j++ ) {
                    $month_number = intval( $start_of_the_year ) + $j;
                    
                    if ( $month_number > 12 ) {
                        $month_number -= 12;
                        
                        $month_range_option = array(
                            'id'   => sprintf( '%s:%s', $i + 1, zeroise( $month_number, 2 ) ),
                            'name' => $wp_locale->get_month( zeroise( $month_number, 2 ) )
                        );
                    } else {
                        $month_range_option = array(
                            'id'   => sprintf( '%s:%s', $i, zeroise( $month_number, 2 ) ),
                            'name' => $wp_locale->get_month( zeroise( $month_number, 2 ) )
                        );
                    }
                    
                    $month_range_options[] = $month_range_option;
                }
            } else {
                for ( $j = 0; $j < $max_month_range[$num_months]; $j++ ) {
                    $start_month_number = intval( $start_of_the_year ) + ( $j * ( $month_addition[$num_months] + 1 ) );
                    $start_year_number  = $i;
                    $end_year_number    = $i;
                    
                    if ( $start_month_number > 12 ) {
                        $start_month_number -= 12;
                        $start_year_number  += 1;
                        $end_year_number     = $start_year_number;
                    }
                    
                    $end_month_number = $start_month_number + $month_addition[$num_months];
                    
                    if ( $end_month_number > 12 ) {
                        $end_month_number -= 12;
                        $end_year_number  += 1;
                    }
                    
                    $month_range_option = array(
                        'id'   => sprintf( '%s:%s-%s:%s', $start_year_number, zeroise( $start_month_number, 2 ), $end_year_number, zeroise( $end_month_number, 2 ) ),
                        'name' => sprintf( '%s - %s', $wp_locale->get_month( zeroise( $start_month_number, 2 ) ), $wp_locale->get_month( zeroise( $end_month_number, 2 ) ) )
                    );
                    
                    $month_range_options[] = $month_range_option;
                }
            }
            
            $year_range_option = array(
                'id'           => sprintf( '%s-%s', $i, $i + 1 ),
                'name'         => sprintf( '%s - %s', $i, $i + 1 ),
                'month_ranges' => $month_range_options
            );
        }
        
        $year_range_options[] = $year_range_option;
    }
    
    return $year_range_options;
}

function wpsc_get_custom_default_year_options( $start_year ) {
    $options = array();
    
    if ( intval( $start_year ) > 1 ) {
        for ( $i = 2018; $i < 2037; $i++ ) {
            $key = sprintf( '%s-%s', $i, $i + 1 );
            $options[$key] = sprintf( '%s - %s', $i, $i + 1 );
        }
    } else {
        for ( $i = 2018; $i < 2037; $i++ ) {
            $key = $i;
            $options[$key] = $i;
        }
    }
    
    return $options;
}

function wpsc_get_custom_default_month_range_options( $start_year, $num_months ) {
    global $wp_locale;
    
    $options = array();
    
    $addition = array( 'twelve' => 11, 'six' => 5, 'four' => 3, 'three' => 2, 'one' => 0 );
    $max = array( 'twelve' => 1, 'six' => 2, 'four' => 3, 'three' => 4, 'one' => 12 );
    
    $start_month_number = $start_year;
    
    for ( $i = 0; $i < $max[$num_months]; $i++ ) {
        $monthtext1 = $wp_locale->get_month( zeroise( $start_month_number, 2 ) );
        
        $end_month_number = intval( $start_month_number ) + $addition[$num_months];
        $end_month_number = $end_month_number > 12 ? $end_month_number - 12 : $end_month_number;
        
        $monthtext2 = $wp_locale->get_month( zeroise( $end_month_number, 2 ) );
        
        if ( 'one' === $num_months ) {
            $key = zeroise( $start_month_number, 2 );
            $options[$key] = $monthtext1;
        } else {
            $key = sprintf( '%s-%s', zeroise( $start_month_number, 2 ), zeroise( $end_month_number, 2 ) );
            $options[$key] = sprintf( '%s - %s', $monthtext1, $monthtext2 );
        }
        
        $start_month_number = $end_month_number + 1;
        $start_month_number = $start_month_number > 12 ? $start_month_number - 12 : $start_month_number;
    }
    
    return $options;
}

function wpsc_render_calendar( $calendar, $current_year_range, $current_month_range, $return = false ) {
    global $wp_locale;
    
    $start_year  = '';
    $end_year    = '';
    $start_month = '';
    $end_month   = '';
    
    $arr_current_date = explode( '-', date( 'Y-m-d' ) );
    
    $year_range_options = wpsc_get_year_range_options( $calendar['start_year'], $calendar['num_months'] );
    
    if ( empty( $current_year_range ) && empty( $current_month_range ) ) {
        if ( 'current' === $calendar['default_month_range'] ) {
            $maybe_year_ranges = array();
            
            foreach ( $year_range_options as $year_range_option ) {
                $arr_year_range_option = explode( '-', $year_range_option['id'] );
                
                if ( count( $arr_year_range_option ) > 1 ) {
                    if ( intval( $arr_year_range_option[0] ) <= intval( $arr_current_date[0] ) && intval( $arr_current_date[0] ) <= intval( $arr_year_range_option[1] ) ) {
                        $maybe_year_ranges[] = $year_range_option;
                    }
                } else {
                    if ( intval( $arr_year_range_option[0] ) === intval( $arr_current_date[0] ) ) {
                        $maybe_year_ranges[] = $year_range_option;
                    }
                }
            }
            
            foreach ( $maybe_year_ranges as $year_range_option ) {
                foreach ( $year_range_option['month_ranges'] as $month_range_option ) {
                    $arr_month_range_option = explode( '-', $month_range_option['id'] );

                    if ( count( $arr_month_range_option ) > 1 ) {
                        $arr_start_month_range = explode( ':', $arr_month_range_option[0] );
                        $arr_end_month_range   = explode( ':', $arr_month_range_option[1] );

                        if ( intval( $arr_start_month_range[0] . $arr_start_month_range[1] ) <= intval( $arr_current_date[0] . $arr_current_date[1] ) && intval( $arr_current_date[0] . $arr_current_date[1] ) <= intval( $arr_end_month_range[0] . $arr_end_month_range[1] ) ) {
                            $current_year_range  = $year_range_option['id'];
                            $current_month_range = $month_range_option['id'];

                            $start_year  = $arr_start_month_range[0];
                            $start_month = $arr_start_month_range[1];
                            $end_year    = $arr_end_month_range[0];
                            $end_month   = $arr_end_month_range[1];

                            break;
                        }
                    } else {
                        $arr_start_month_range = explode( ':', $arr_month_range_option[0] );

                        if ( intval( $arr_start_month_range[0] . $arr_start_month_range[1] ) === intval( $arr_current_date[0] . $arr_current_date[1] ) ) {
                            $current_year_range  = $year_range_option['id'];
                            $current_month_range = $month_range_option['id'];

                            $start_year  = $arr_start_month_range[0];
                            $start_month = $arr_start_month_range[1];
                            $end_year    = $arr_start_month_range[0];
                            $end_month   = $arr_start_month_range[1];

                            break;
                        }
                    }
                }
            }
        } else {
            $current_year_range  = $calendar['custom_default_year'];
            
            foreach ( $year_range_options as $year_range_option ) {
                if ( strval( $current_year_range ) === strval( $year_range_option['id'] ) ) {
                    foreach ( $year_range_option['month_ranges'] as $month_range_option ) {
                        $arr_month_range_option = explode( '-', $month_range_option['id'] );
                        $arr_start_month_range  = explode( ':', $arr_month_range_option[0] );
                        
                        if ( count( $arr_month_range_option ) > 1 ) {
                            $arr_end_month_range = explode( ':', $arr_month_range_option[1] );
                            
                            if ( ( $arr_start_month_range[1] . '-' . $arr_end_month_range[1] ) === strval( $calendar['custom_default_month_range'] ) ) {
                                $current_month_range = $month_range_option['id'];
                                
                                $start_year  = $arr_start_month_range[0];
                                $start_month = $arr_start_month_range[1];
                                $end_year    = $arr_end_month_range[0];
                                $end_month   = $arr_end_month_range[1];
                            
                                break;
                            }
                        } else {
                            if ( strval( $arr_start_month_range[1] ) === strval( $calendar['custom_default_month_range'] ) ) {
                                $current_month_range = $month_range_option['id'];
                                
                                $start_year  = $arr_start_month_range[0];
                                $start_month = $arr_start_month_range[1];
                                $end_year    = $arr_start_month_range[0];
                                $end_month   = $arr_start_month_range[1];
                            
                                break;
                            }
                        }
                    }

                    break;
                }
            }
        }
    } else {
        foreach ( $year_range_options as $year_range_option ) {
            if ( strval( $current_year_range ) === strval( $year_range_option['id'] ) ) {
                foreach ( $year_range_option['month_ranges'] as $month_range_option ) {
                    if ( strval( $current_month_range ) === strval( $month_range_option['id'] ) ) {
                        $arr_month_range_option = explode( '-', $month_range_option['id'] );

                        if ( count( $arr_month_range_option ) > 1 ) {
                            $arr_start_month_range = explode( ':', $arr_month_range_option[0] );
                            $arr_end_month_range   = explode( ':', $arr_month_range_option[1] );

                            $start_year  = $arr_start_month_range[0];
                            $start_month = $arr_start_month_range[1];
                            $end_year    = $arr_end_month_range[0];
                            $end_month   = $arr_end_month_range[1];
                        } else {
                            $arr_start_month_range = explode( ':', $arr_month_range_option[0] );

                            $start_year  = $arr_start_month_range[0];
                            $start_month = $arr_start_month_range[1];
                            $end_year    = $arr_start_month_range[0];
                            $end_month   = $arr_start_month_range[1];
                        }
                        
                        break;
                    }
                }
                
                break;
            }
        }
    }
    
    $start_date = sprintf( '%s-%s-01', $start_year, zeroise( $start_month, 2 ) );
    $end_date   = sprintf( '%s-%s-%s', $end_year, zeroise( $end_month, 2 ), date( 't', mktime( 0, 0, 0, intval( $end_month ), 1, intval( $end_year ) ) ) );
    
    $available_categories = wpsc_get_categories();
    $available_groups = wpsc_get_groups();
    
    $cat_args = array(
        'start_date' => $start_date,
        'end_date'   => $end_date,
    );
    
    if ( isset( $calendar['groups'] ) ) {
        $cat_args['groups'] = $calendar['groups'];
    }
    
    if ( isset( $calendar['include_no_groups'] ) ) {
        $cat_args['include_no_groups'] = $calendar['include_no_groups'];
    }
    
    if ( isset( $calendar['categories'] ) ) {
        $cat_args['categories'] = $calendar['categories'];
    }
    
    $calendar_category_list = wpsc_get_calendar_category_list( $cat_args );
    $calendar_group_list    = wpsc_get_calendar_group_list( $cat_args );
    
    // List Month

    $list_month = array();

    if ( $start_year === $end_year ) {
        for ( $i = intval( $start_month ); $i <= intval( $end_month ); $i++ ) {
            $list_month[] = array(
                'year'  => $start_year,
                'month' => zeroise( $i, 2)
            );
        }
    } else {
        for ( $i = intval( $start_month ); $i <= 12; $i++ ) {
            $list_month[] = array(
                'year'  => $start_year,
                'month' => zeroise( $i, 2)
            );
        }

        if ( ( $end_year - $start_year ) > 1 ) {
            for ( $i = $start_year + 1; $i < $end_year; $i++ ) {
                for ( $j = 1; $j <= 12; $j++ ) {
                    $list_month[] = array(
                        'year'  => $i,
                        'month' => zeroise( $j, 2)
                    );
                }
            }
        }

        for ( $i = 1; $i <= intval( $end_month ); $i++ ) {
            $list_month[] = array(
                'year'  => $end_year,
                'month' => zeroise( $i, 2)
            );
        }
    }
    
    // Important dates
    
    $important_date_args = array(
        'start_date' => $start_date,
        'end_date'   => $end_date
    );
    
    if ( isset( $calendar['groups'] ) ) {
        $important_date_args['groups'] = $calendar['groups'];
    }
    
    if ( isset( $calendar['include_no_groups'] ) ) {
        $important_date_args['include_no_groups'] = $calendar['include_no_groups'];
    }
    
    if ( isset( $calendar['categories'] ) ) {
        $important_date_args['categories'] = $calendar['categories'];
    }
    
    if ( isset( $calendar['_categories'] ) ) {
        $important_date_args['categories'] = $calendar['_categories'];
    }
    
    if ( isset( $calendar['_groups'] ) ) {
        $important_date_args['groups'] = $calendar['_groups'];
        $important_date_args['include_no_groups'] = 'N';
    }
    
    if ( isset( $calendar['_nogroups'] ) && 'Y' === $calendar['_nogroups'] ) {
        $important_date_args['include_no_groups'] = 'Y';
    }
    
    $important_dates = wpsc_get_important_dates( $important_date_args );
    
    $daily_important_dates = array();
    
    if ( $start_year === $end_year ) {
        for ( $i = intval( $start_month ); $i <= intval( $end_month ); $i++ ) {
            $days_in_month = date( 't', mktime( 0, 0, 0, $i, 1, intval( $start_year ) ) );

            $current_month_daily_important_dates = array();

            for ( $current_date = 1; $current_date <= $days_in_month; $current_date++ ) {
                $current_date_important_dates = array();

                foreach ( $important_dates as $important_date ) {
                    $str_date = sprintf( '%s-%s-%s', $start_year, zeroise( $i, 2 ), zeroise( $current_date, 2 ) );

                    if ( strtotime( $important_date['start_date'] ) <= strtotime( $str_date ) && strtotime( $important_date['end_date'] ) >= strtotime( $str_date ) ) {
                        $current_date_important_dates[] = $important_date;
                    }
                }

                $current_month_daily_important_dates[$current_date] = $current_date_important_dates;
            }

            $daily_important_dates[$start_year][$i] = $current_month_daily_important_dates;
        }
    } else {
        // Start Year
        for ( $i = intval( $start_month ); $i <= 12; $i++ ) {
            $days_in_month = date( 't', mktime( 0, 0, 0, $i, 1, intval( $start_year ) ) );

            $current_month_daily_important_dates = array();

            for ( $current_date = 1; $current_date <= $days_in_month; $current_date++ ) {
                $current_date_important_dates = array();

                foreach ( $important_dates as $important_date ) {
                    $str_date = sprintf( '%s-%s-%s', $start_year, zeroise( $i, 2 ), zeroise( $current_date, 2 ) );

                    if ( strtotime( $important_date['start_date'] ) <= strtotime( $str_date ) && strtotime( $important_date['end_date'] ) >= strtotime( $str_date ) ) {
                        $current_date_important_dates[] = $important_date;
                    }
                }

                $current_month_daily_important_dates[$current_date] = $current_date_important_dates;
            }

            $daily_important_dates[$start_year][$i] = $current_month_daily_important_dates;
        }

        if ( ( $end_year - $start_year ) > 1 ) {
            for ( $i = $start_year + 1; $i < $end_year; $i++ ) {
                for ( $j = 1; $j <= 12; $j++ ) {
                    $days_in_month = date( 't', mktime( 0, 0, 0, $j, 1, $i ) );

                    $current_month_daily_important_dates = array();

                    for ( $current_date = 1; $current_date <= $days_in_month; $current_date++ ) {
                        $current_date_important_dates = array();

                        foreach ( $important_dates as $important_date ) {
                            $str_date = sprintf( '%s-%s-%s', $i, zeroise( $j, 2 ), zeroise( $current_date, 2 ) );

                            if ( strtotime( $important_date['start_date'] ) <= strtotime( $str_date ) && strtotime( $important_date['end_date'] ) >= strtotime( $str_date ) ) {
                                $current_date_important_dates[] = $important_date;
                            }
                        }

                        $current_month_daily_important_dates[$current_date] = $current_date_important_dates;
                    }

                    $daily_important_dates[$i][$j] = $current_month_daily_important_dates;
                }
            }
        }

        // End Year
        for ( $i = 1; $i <= intval( $end_month ); $i++ ) {
            $days_in_month = date( 't', mktime( 0, 0, 0, $i, 1, $end_year ) );

            $current_month_daily_important_dates = array();

            for ( $current_date = 1; $current_date <= $days_in_month; $current_date++ ) {
                $current_date_important_dates = array();

                foreach ( $important_dates as $important_date ) {
                    $str_date = sprintf( '%s-%s-%s', $end_year, zeroise( $i, 2 ), zeroise( $current_date, 2 ) );

                    if ( strtotime( $important_date['start_date'] ) <= strtotime( $str_date ) && strtotime( $important_date['end_date'] ) >= strtotime( $str_date ) ) {
                        $current_date_important_dates[] = $important_date;
                    }
                }

                $current_month_daily_important_dates[$current_date] = $current_date_important_dates;
            }

            $daily_important_dates[$end_year][$i] = $current_month_daily_important_dates;
        }
    }
    
    ob_start();
    
    $container_classes = sprintf( 'wpsc-container wpsc-calendar-theme-%s', $calendar['theme'] );
    $container_classes = apply_filters( 'wpsc_calendar_container_classes', $container_classes, $calendar );
    
    printf( '<div class="%s">', $container_classes );
    
    printf( '<input type="hidden" class="wpsc-current-year-range" value="%s">', $current_year_range );
    printf( '<input type="hidden" class="wpsc-current-month-range" value="%s">', $current_month_range );
    
    $settings_categories = isset( $calendar['_categories'] ) ? implode( ',', $calendar['_categories'] ) : '';
    $settings_groups     = isset( $calendar['_groups'] ) ? implode( ',', $calendar['_groups'] ) : '';
    $settings_nogroups   = isset( $calendar['_nogroups'] ) ? 'Y' : '';
    
    printf( '<input type="hidden" class="wpsc-categories" value="%s">', $settings_categories );
    printf( '<input type="hidden" class="wpsc-groups" value="%s">', $settings_groups );
    printf( '<input type="hidden" class="wpsc-nogroups" value="%s">', $settings_nogroups );
    
    do_action( 'wpsc_render_calendar_navigation', array(
        'current_year_range'     => $current_year_range,
        'current_month_range'    => $current_month_range,
        'start_date'             => $start_date,
        'end_date'               => $end_date,
        'calendar'               => $calendar,
        'available_categories'   => $available_categories,
        'calendar_category_list' => $calendar_category_list,
        'available_groups'       => $available_groups,
        'calendar_group_list'    => $calendar_group_list
    ) );
    
    printf( '<div class="wpsc-calendars wpsc-calendars-%s wpsc-calendars-%s-months">', $calendar['num_columns'], $calendar['num_months'] );
    
    foreach ( $list_month as $list ) {
        $single_month = array();

        $month_name = $wp_locale->get_month( $list['month'] );

        $single_month['year']       = $list['year'];
        $single_month['month']      = $list['month'];
        $single_month['month_name'] = $month_name;

        // Weekdays

        $weekdays = array();
        $weekday_ids = wpsc_get_weekday_ids( $calendar['week_start'] );

        foreach ( $weekday_ids as $weekday_id ) {
            $weekday_name = $wp_locale->get_weekday( $weekday_id );
            
            if ( 'three-letter' === $calendar['day_format'] ) {
                $weekday_name = $wp_locale->get_weekday_abbrev( $weekday_name );
            } elseif ( 'one-letter' === $calendar['day_format'] ) {
                $weekday_name = $wp_locale->get_weekday_initial( $weekday_name );
            }

            $weekdays[] = array(
                'weekday'      => $weekday_id,
                'weekday_name' => $weekday_name
            );
        }

        $single_month['weekdays'] = $weekdays;

        // Days in a Week

        $current_date   = 1;
        $weekday_number = date( 'w', mktime( 0, 0, 0, intval( $list['month'] ), $current_date, intval( $list['year'] ) ) );
        $days_in_month  = date( 't', mktime( 0, 0, 0, intval( $list['month'] ), 1, intval( $list['year'] ) ) );

        if ( $list['month'] > 1 ) {
            $days_in_before_month  = date( 't', mktime( 0, 0, 0, intval( $list['month'] ) - 1, 1, intval( $list['year'] ) ) );
        } else {
            $days_in_before_month  = date( 't', mktime( 0, 0, 0, 12, 1, intval( $list['year'] ) - 1 ) );
        }

        $start     = false;
        $prev_date = $days_in_before_month;
        $next_date = 1;

        foreach ( $weekday_ids as $weekday_id ) {
            if ( (int) $weekday_id === (int) $weekday_number ) {
                break;
            }

            $prev_date--;
        }

        for ( $i = 0; $i < 6; $i++ ) {
            $week_dates = array();

            foreach ( $weekday_ids as $weekday_id ) {
                if ( (int) $weekday_id === (int) $weekday_number ) {
                    $start = true;
                }

                if ( $start && $current_date <= $days_in_month ) {
                    $current_weekday_number = date( 'w', mktime( 0, 0, 0, intval( $list['month'] ), $current_date, intval( $list['year'] ) ) );

                    $year  = intval( $list['year'] );
                    $month = intval( $list['month'] );

                    $week_dates[] = array(
                        'content'                 => $current_date,
                        'group'                   => 'general-date',
                        'weekday_number'          => $current_weekday_number,
                        'current_important_dates' => $daily_important_dates[$year][$month][$current_date]
                    );

                    $current_date++;
                } else {
                    if ( $start ) {
                        $current_prevnext_date = $next_date++;

                        if ( $list['month'] < 12 ) {
                            $current_weekday_number = date( 'w', mktime( 0, 0, 0, intval( $list['month'] ) + 1, $current_prevnext_date, intval( $list['year'] ) ) );
                        } else {
                            $current_weekday_number  = date( 'w', mktime( 0, 0, 0, 1, $current_prevnext_date, intval( $list['year'] ) + 1 ) );
                        }

                        $week_dates[] = array(
                            'content'        => $current_prevnext_date,
                            'group'          => 'prevnext-date',
                            'weekday_number' => $current_weekday_number
                        );
                    } else {
                        $current_prevnext_date = ++$prev_date;

                        if ( $list['month'] > 1 ) {
                            $current_weekday_number = date( 'w', mktime( 0, 0, 0, intval( $list['month'] ) - 1, $current_prevnext_date, intval( $list['year'] ) ) );
                        } else {
                            $current_weekday_number  = date( 'w', mktime( 0, 0, 0, 12, $current_prevnext_date, intval( $list['year'] ) - 1 ) );
                        }

                        $week_dates[] = array(
                            'content'        => $current_prevnext_date,
                            'group'          => 'prevnext-date',
                            'weekday_number' => $current_weekday_number
                        );
                    }
                }
            }

            $single_month['week_dates'][] = $week_dates;
        }

        printf( '<div class="wpsc-calendar wpsc-calendar-%s-%s">', $list['year'], $list['month'] );
        echo '<div class="wpsc-calendar-inner">';
        printf( '<div class="wpsc-calendar-heading"><span class="wpsc-calendar-heading-month">%s</span> <span class="wpsc-calendar-heading-year">%s</span></div>', wpsc_normalize_special_character_for_html( $single_month['month_name'] ), $list['year'] );
        
        echo '<table>';
        echo '<tbody>';
        echo '<tr>';

        foreach ( $single_month['weekdays'] as $weekday ) {
            if ( ! in_array( $weekday['weekday'], $calendar['weekday'] ) ) {
                $container_class = sprintf( 'wpsc-calendar-weekday-heading-container wpsc-calendar-weekday-container wpsc-calendar-weekday-%s-container wpsc-calendar-weekend-container', $weekday['weekday'] );
                $class = sprintf( 'wpsc-calendar-weekday wpsc-calendar-weekday-%s wpsc-calendar-weekend', $weekday['weekday'] );
            } else {
                $container_class = sprintf( 'wpsc-calendar-weekday-heading-container wpsc-calendar-weekday-container wpsc-calendar-weekday-%s-container', $weekday['weekday'] );
                $class = sprintf( 'wpsc-calendar-weekday wpsc-calendar-weekday-%s', $weekday['weekday'] );
            }
                
            printf( '<td class="%s"><div class="%s">%s</div></td>', $container_class, $class, wpsc_normalize_special_character_for_html( $weekday['weekday_name'] ) );
        }

        echo '</tr>';
        
        foreach ( $single_month['week_dates'] as $data_row ) {
            echo '<tr>';

            foreach ( $data_row as $column ) {
                $date_attr = array();

                $container_class = 'wpsc-calendar-weekday-container wpsc-calendar-weekday-' . $column['weekday_number'] . '-container';
                
                if ( 'prevnext-date' === $column['group'] ) {
                    $container_class .= ' wpsc-calendar-prevnext-container';
                }
                
                $date_class = array( 
                    sprintf( 'wpsc-calendar-%s', $column['group'] ),
                    sprintf( 'wpsc-calendar-weekday-%s', $column['weekday_number'] ) 
                );

                if ( ! in_array( $column['weekday_number'], $calendar['weekday'] ) ) {
                    $date_class[] = 'wpsc-calendar-weekend';
                    $container_class .= ' wpsc-calendar-weekend-container';
                }

                if ( empty( $column['current_important_dates'] ) ) {} 
                else {
                    $categories = array();

                    foreach ( $column['current_important_dates'] as $important_date ) {
                        if ( 'Y' === $important_date['exclude_weekend'] && ! in_array( $column['weekday_number'], $calendar['weekday'] ) ) {}
                        else {
                            $date_class[] = sprintf( 'wpsc-calendar-important-date wpsc-calendar-important-date-%s', $important_date['post_id'] );
                            $categories[] = $important_date['category_id'];
                        }
                    }

                    if ( count( $categories ) > 0 ) {
                        $categories = array_unique( $categories );

                        sort( $categories );

                        $date_class[] = sprintf( 'wpsc-important-date-tooltip wpsc-important-date-category-%s', implode( '-', $categories ) );

                        $date_attr = apply_filters( 'wpsc_important_date_attributes', $date_attr, $calendar, $column );
                    }
                    
                    if ( count( $categories ) > 1 ) {
                        $date_class[] = 'wpsc-ipad-fix';
                        $date_attr[] = sprintf( 'style="%s"', wpsc_get_important_date_multi_colors( $categories ) );
                    }
                }

                $date_class = apply_filters( 'wpsc_calendar_date_class', $date_class );
                $date_attr = apply_filters( 'wpsc_calendar_date_attr', $date_attr );

                printf( '<td class="%s">', $container_class );
                printf( '<div class="%s" %s>%s</div>', implode( ' ', $date_class ), implode( ' ', $date_attr ), $column['content'] );
                echo '</td>';
            }

            echo '</tr>';
        }
        
        echo '</tbody>';
        echo '</table>';
        echo '</div>';
        echo '</div>';
    }
    
    echo '</div>';
    
    // Powered by "WP School Calendar"
    
    if ( 'Y' === wpsc_settings_value( 'credit' ) ) {
        echo '<div class="wpsc-credit">';
        printf( __( 'Powered by <a href="%s" target="_blank">WP School Calendar</a>', 'wp-school-calendar' ), 'https://sorsawo.com/en/wordpress-school-calendar/' );
        echo '</div>';
    }
    
    // Important Date Categories
        
    $important_date_categories = array();

    foreach ( $important_dates as $important_date ) {
        $important_date_categories[] = $important_date['category_id'];
    }

    $important_date_categories = array_unique( $important_date_categories );
    $display_cats = 'Y' === $calendar['show_important_date_cats'] ? '' : 'display:none';

    echo '<div class="wpsc-category-listings" style="' . $display_cats . '">';

    foreach ( $available_categories as $category ) {
        if ( in_array( $category['category_id'], $important_date_categories ) ) {
            echo '<div class="wpsc-category-listing">';
            printf( '<span class="wpsc-category-listing-color" style="background:%s;"></span>', $category['bgcolor'] );
            printf( '<span class="wpsc-category-listing-name">%s</span>', wpsc_normalize_special_character_for_html( $category['name'] ) );
            echo '</div>';
        }
    }
    
    echo '</div>';
    
    // Important Date Listings
    
    $display = 'Y' === $calendar['show_important_date_listing'] ? '' : 'display:none';
    
    echo '<div class="wpsc-important-date-listings" style="' . $display . '">';

    printf( '<h3 class="wpsc-important-date-listings-heading">%s</h3>', wpsc_normalize_special_character_for_html( $calendar['important_date_heading'] ) );

    if ( empty( $important_dates ) ) {
        printf( '<div class="wpsc-no-important-date">%s</div>', esc_html__( 'No Important Date', 'wp-school-calendar' ) );
    } else {
        $important_date_listings = apply_filters( 'wpsc_important_date_listings', $important_dates, $calendar );;

        foreach ( $important_date_listings as $important_date ) {
            $post_parent = ( 'important_date' === $important_date['post_type'] ) ? $important_date['post_id'] : $important_date['post_parent'];
            printf( '<div class="wpsc-important-date-item wpsc-important-date-category-%s" id="wpsc-important-date-parent-%s" data-category="%s">', $important_date['category_id'], $post_parent, $important_date['category_id'] );
            echo '<div class="wpsc-important-date-item-inner">';

            $date = apply_filters( 'wpsc_date_display', wpsc_format_date( $important_date['start_date'], $important_date['end_date'], $calendar['date_format'], $calendar['show_year'] ), $important_date );

            printf( '<div class="wpsc-important-date-date">%s</div>', $date );
            printf( '<div class="wpsc-important-date-title">%s</div>', wpsc_normalize_special_character_for_html( $important_date['important_date_title'] ) );
            echo apply_filters( 'wpsc_render_additional_notes', '', $important_date );

            echo '</div>';
            echo '</div>';
        }
    }

    echo '</div>';
    
    echo '</div>';
    
    if ( $return ) {
        return ob_get_clean();
    }
    
    echo ob_get_clean();
}

/**
 * Get important date single color
 * 
 * @since 1.0
 * 
 * @return string Important date color style
 */
function wpsc_get_important_date_single_color() {
    $categories = wpsc_get_categories();
    
    $css = array();
    
    foreach ( $categories as $category ) {
        $css[] = sprintf( '#wpsc-block-calendar .wpsc-important-date-category-%s, .wpsc-important-date-category-%s {background:%s;color:#fff;}' . "\n", $category['category_id'], $category['category_id'], $category['bgcolor'] );
    }
    
    return implode( '', $css );
}

/**
 * Get important date multi colors
 * 
 * @since 1.0
 * 
 * @param array $categories Array of important date categories
 * @return string Important date color style
 */
function wpsc_get_important_date_multi_colors( $categories = array() ) {
    if ( $categories === array() ) {
        return;
    }
    
    $tmp_all_categories = wpsc_get_categories();
    
    $all_categories = array();
    
    foreach ( $tmp_all_categories as $category ) {
        $id = $category['category_id'];
        $all_categories[$id] = $category;
    }
    
    $num_categories = count( $categories );
    
    $css = '';
        
    // Two colors
    if ( $num_categories === 2 ) {
        if ( $categories[0] === $categories[1] ) {}
        else {
            $background  = 'data:image/svg+xml;base64,' . base64_encode( sprintf( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" height="100" width="100"><polygon points="0,100 0,0 100,0" style="fill:%s;stroke:none;" /><polygon points="0,100 100,100 100,0" style="fill:%s;stroke:none;" /></svg>', $all_categories[$categories[0]]['bgcolor'], $all_categories[$categories[1]]['bgcolor'] ) );
            $css = sprintf( "background:url('%s') center no-repeat;background-size:%s;-ms-background-size:%s;-o-background-size:%s;-moz-background-size:%s;-webkit-background-size:%s;color:#fff;", $background, '100% 100%', '100% 100%', '100% 100%', '100% 100%', '100% 100%' );
        }
    }

    // Three Colors
    if ( $num_categories === 3 ) {
        if ( $categories[0] === $categories[1] && $categories[1] === $categories[2] ) {}
        else {
            $background  = 'data:image/svg+xml;base64,' . base64_encode( sprintf( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" height="100" width="100"><polygon points="0,100 0,0 100,0" style="fill:%s;stroke:none;" /><polygon points="0,100 100,100 50,50" style="fill:%s;stroke:none;" /><polygon points="100,0 100,100 50,50" style="fill:%s;stroke:none;" /></svg>', $all_categories[$categories[0]]['bgcolor'], $all_categories[$categories[1]]['bgcolor'], $all_categories[$categories[2]]['bgcolor'] ) );
            $css = sprintf( "background:url('%s') center no-repeat;background-size:%s;-ms-background-size:%s;-o-background-size:%s;-moz-background-size:%s;-webkit-background-size:%s;color:#fff;", $background, '100% 100%', '100% 100%', '100% 100%', '100% 100%', '100% 100%' );
        }
    }

    // Four Colors
    if ( $num_categories === 4 ) {
        if ( $categories[0] === $categories[1] && $categories[1] === $categories[2] && $categories[2] === $categories[3] ) {}
        else {
            $background  = 'data:image/svg+xml;base64,' . base64_encode( sprintf( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" height="100" width="100"><polygon points="0,0 0,100 50,50" style="fill:%s;stroke:none;" /><polygon points="0,0 100,0 50,50" style="fill:%s;stroke:none;" /><polygon points="100,0 100,100 50,50" style="fill:%s;stroke:none;" /><polygon points="0,100 100,100 50,50" style="fill:%s;stroke:none;" /></svg>', $all_categories[$categories[0]]['bgcolor'], $all_categories[$categories[1]]['bgcolor'], $all_categories[$categories[2]]['bgcolor'], $all_categories[$categories[3]]['bgcolor'] ) );
            $css = sprintf( "background:url('%s') center no-repeat;background-size:%s;-ms-background-size:%s;-o-background-size:%s;-moz-background-size:%s;-webkit-background-size:%s;color:#fff;", $background, '100% 100%', '100% 100%', '100% 100%', '100% 100%', '100% 100%' );
        }
    }

    // Five Colors
    if ( $num_categories === 5 ) {
        if ( $categories[0] === $categories[1] && $categories[1] === $categories[2] && $categories[2] === $categories[3] && $categories[3] === $categories[4] ) {}
        else {
            $background  = 'data:image/svg+xml;base64,' . base64_encode( sprintf( '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" height="100" width="100"><polygon points="0,0 25,25 25,75 0,100" style="fill:%s;stroke:none;" /><polygon points="0,0 25,25 75,25 100,0" style="fill:%s;stroke:none;" /><polygon points="100,0 75,25 75,75 100,100" style="fill:%s;stroke:none;" /><polygon points="0,100 25,75 75,75 100,100" style="fill:%s;stroke:none;" /><polygon points="25,25 75,25 75,75 25,75" style="fill:%s;stroke:none;" /></svg>', $all_categories[$categories[0]]['bgcolor'], $all_categories[$categories[1]]['bgcolor'], $all_categories[$categories[2]]['bgcolor'], $all_categories[$categories[3]]['bgcolor'], $all_categories[$categories[4]]['bgcolor'] ) );
            $css = sprintf( "background:url('%s') center no-repeat;background-size:%s;-ms-background-size:%s;-o-background-size:%s;-moz-background-size:%s;-webkit-background-size:%s;color:#fff;", $background, '100% 100%', '100% 100%', '100% 100%', '100% 100%', '100% 100%' );
        }
    }
    
    return $css;
}

/**
 * Get formatted date
 * 
 * @since 1.0
 * 
 * @global array $wp_locale
 * @param string $start_date  Start date
 * @param string $end_date    End date
 * @param string $date_format Date format
 * @param string $show_year   Show year or not
 * @return string Formatted date
 */
function wpsc_format_date( $start_date, $end_date, $date_format, $show_year ) {
    global $wp_locale;
    
    $formatted_date = '';

    if ( '' !== $start_date && '' !== $end_date ) {
        if ( $start_date === $end_date ) {
            $start_date = explode( '-', $start_date );

            if ( 'short' === $date_format ) {
                if ( 'Y' === $show_year ) {
                    $formatted_date .= sprintf( '%s/%s/%s', $start_date[1], $start_date[2], $start_date[0] );
                } else {
                    $formatted_date .= sprintf( '%s/%s', $start_date[1], $start_date[2] );
                }
            } elseif ( 'medium' === $date_format ) {
                if ( 'Y' === $show_year ) {
                    $formatted_date .= sprintf( '%s %s, %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], $start_date[0] );
                } else {
                    $formatted_date .= sprintf( '%s %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2] );
                }
            } elseif ( 'long' === $date_format ) {
                if ( 'Y' === $show_year ) {
                    $formatted_date .= sprintf( '%s %s, %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], $start_date[0] );
                } else {
                    $formatted_date .= sprintf( '%s %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2] );
                }
            } elseif ( 'short-alt' === $date_format ) {
                if ( 'Y' === $show_year ) {
                    $formatted_date .= sprintf( '%s/%s/%s', $start_date[2], $start_date[1], $start_date[0] );
                } else {
                    $formatted_date .= sprintf( '%s/%s', $start_date[2], $start_date[1] );
                }
            } elseif ( 'medium-alt' === $date_format ) {
                if ( 'Y' === $show_year ) {
                    $formatted_date .= sprintf( '%s %s %s', (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), $start_date[0] );
                } else {
                    $formatted_date .= sprintf( '%s %s', (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ) );
                }
            } elseif ( 'long-alt' === $date_format ) {
                if ( 'Y' === $show_year ) {
                    $formatted_date .= sprintf( '%s %s %s', (int) $start_date[2], $wp_locale->get_month( $start_date[1] ), $start_date[0] );
                } else {
                    $formatted_date .= sprintf( '%s %s', (int) $start_date[2], $wp_locale->get_month( $start_date[1] ) );
                }
            }
        } else {
            $start_date = explode( '-', $start_date );
            $end_date = explode( '-', $end_date );

            if ( $start_date[0] === $end_date[0] ) { // Same Year
                if ( $start_date[1] === $end_date[1] ) { // Same Month
                    if ( 'short' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s/%s/%s - %s/%s/%s', $start_date[1], $start_date[2], $start_date[0], $end_date[1], $end_date[2], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s/%s - %s/%s', $start_date[1], $start_date[2], $end_date[1], $end_date[2] );
                        }
                    } elseif ( 'medium' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s %s - %s, %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], (int) $end_date[2], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s %s - %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], (int) $end_date[2] );
                        }
                    } elseif ( 'long' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s %s - %s, %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], (int) $end_date[2], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s %s - %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], (int) $end_date[2] );
                        }
                    } elseif ( 'short-alt' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s/%s/%s - %s/%s/%s', $start_date[2], $start_date[1], $start_date[0], $end_date[2], $start_date[1], $start_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s/%s - %s/%s', $start_date[2], $start_date[1], $end_date[2], $start_date[1] );
                        }
                    } elseif ( 'medium-alt' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s - %s %s %s', (int) $start_date[2], (int) $end_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), $start_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s - %s %s', (int) $start_date[2], (int) $end_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ) );
                        }
                    } elseif ( 'long-alt' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s - %s %s %s', (int) $start_date[2], (int) $end_date[2], $wp_locale->get_month( $start_date[1] ), $start_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s - %s %s', (int) $start_date[2], (int) $end_date[2], $wp_locale->get_month( $start_date[1] ) );
                        }
                    }
                } else {
                    if ( 'short' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s/%s/%s - %s/%s/%s', $start_date[1], $start_date[2], $start_date[0], $end_date[1], $end_date[2], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s/%s - %s/%s', $start_date[1], $start_date[2], $end_date[1], $end_date[2] );
                        }
                    } elseif ( 'medium' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s %s - %s %s, %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ), (int) $end_date[2], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s %s - %s %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ), (int) $end_date[2] );
                        }
                    } elseif ( 'long' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s %s - %s %s, %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], $wp_locale->get_month( $end_date[1] ), (int) $end_date[2], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s %s - %s %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], $wp_locale->get_month( $end_date[1] ), (int) $end_date[2] );
                        }
                    } elseif ( 'short-alt' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s/%s/%s - %s/%s/%s', $start_date[2], $start_date[1], $start_date[0], $end_date[2], $end_date[1], $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s/%s - %s/%s', $start_date[2], $start_date[1], $end_date[2], $end_date[1] );
                        }
                    } elseif ( 'medium-alt' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s %s - %s %s %s', (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $end_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ), $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s %s - %s %s', (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $end_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ) );
                        }
                    } elseif ( 'long-alt' === $date_format ) {
                        if ( 'Y' === $show_year ) {
                            $formatted_date .= sprintf( '%s %s - %s %s %s', (int) $start_date[2], $wp_locale->get_month( $start_date[1] ), (int) $end_date[2], $wp_locale->get_month( $end_date[1] ), $end_date[0] );
                        } else {
                            $formatted_date .= sprintf( '%s %s - %s %s', (int) $start_date[2], $wp_locale->get_month( $start_date[1] ), (int) $end_date[2], $wp_locale->get_month( $end_date[1] ) );
                        }
                    }
                }
            } else {
                if ( 'short' === $date_format ) {
                    if ( 'Y' === $show_year ) {
                        $formatted_date .= sprintf( '%s/%s/%s - %s/%s/%s', $start_date[1], $start_date[2], $start_date[0], $end_date[1], $end_date[2], $end_date[0] );
                    } else {
                        $formatted_date .= sprintf( '%s/%s - %s/%s', $start_date[1], $start_date[2], $end_date[1], $end_date[2] );
                    }
                } elseif ( 'medium' === $date_format ) {
                    if ( 'Y' === $show_year ) {
                        $formatted_date .= sprintf( '%s %s, %s - %s %s, %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], $start_date[0], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ), (int) $end_date[2], $end_date[0] );
                    } else {
                        $formatted_date .= sprintf( '%s %s - %s %s', $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ), (int) $end_date[2] );
                    }
                } elseif ( 'long' === $date_format ) {
                    if ( 'Y' === $show_year ) {
                        $formatted_date .= sprintf( '%s %s, %s - %s %s, %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], $start_date[0], $wp_locale->get_month( $end_date[1] ), (int) $end_date[2], $end_date[0] );
                    } else {
                        $formatted_date .= sprintf( '%s %s - %s %s', $wp_locale->get_month( $start_date[1] ), (int) $start_date[2], $wp_locale->get_month( $end_date[1] ), (int) $end_date[2] );
                    }
                } elseif ( 'short-alt' === $date_format ) {
                    if ( 'Y' === $show_year ) {
                        $formatted_date .= sprintf( '%s/%s/%s - %s/%s/%s', $start_date[2], $start_date[1], $start_date[0], $end_date[2], $end_date[1], $end_date[0] );
                    } else {
                        $formatted_date .= sprintf( '%s/%s - %s/%s', $start_date[2], $start_date[1], $end_date[2], $end_date[1] );
                    }
                } elseif ( 'medium-alt' === $date_format ) {
                    if ( 'Y' === $show_year ) {
                        $formatted_date .= sprintf( '%s %s %s - %s %s %s', (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), $start_date[0], (int) $end_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ), $end_date[0] );
                    } else {
                        $formatted_date .= sprintf( '%s %s - %s %s', (int) $start_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $start_date[1] ) ), (int) $end_date[2], $wp_locale->get_month_abbrev( $wp_locale->get_month( $end_date[1] ) ) );
                    }
                } elseif ( 'long-alt' === $date_format ) {
                    if ( 'Y' === $show_year ) {
                        $formatted_date .= sprintf( '%s %s %s - %s %s %s', (int) $start_date[2], $wp_locale->get_month( $start_date[1] ), $start_date[0], (int) $end_date[2], $wp_locale->get_month( $end_date[1] ), $end_date[0] );
                    } else {
                        $formatted_date .= sprintf( '%s %s - %s %s', (int) $start_date[2], $wp_locale->get_month( $start_date[1] ), (int) $end_date[2], $wp_locale->get_month( $end_date[1] ) );
                    }
                }
            }
        }
    }
    
    return $formatted_date;
}

/**
 * Get tooltip theme options
 * 
 * @since 1.0
 * 
 * @return array Array of tooltip theme options
 */
function wpsc_get_tooltip_theme_options() {
    $options = array(
        'borderless' => __( 'Borderless', 'wp-school-calendar' ),
        'light'      => __( 'Light', 'wp-school-calendar' ),
        'noir'       => __( 'Noir', 'wp-school-calendar' ),
        'punk'       => __( 'Punk', 'wp-school-calendar' ),
        'shadow'     => __( 'Shadow', 'wp-school-calendar' ),
    );
    
    return $options;
}

function wpsc_get_tooltip_animation_options() {
    $options = array(
        'fade'  => __( 'Fade', 'wp-school-calendar' ),
        'grow'  => __( 'Grow', 'wp-school-calendar' ),
        'swing' => __( 'Swing', 'wp-school-calendar' ),
        'slide' => __( 'Slide', 'wp-school-calendar' ),
        'fall'  => __( 'Fall', 'wp-school-calendar' ),
    );
    
    return $options;
}

/**
 * Get tooltip trigger options
 * 
 * @since 1.0
 * 
 * @return array Array of tooltip trigger options
 */
function wpsc_get_tooltip_trigger_options() {
    $options = array(
        'hover' => __( 'Hover', 'wp-school-calendar' ),
        'click' => __( 'Click', 'wp-school-calendar' ),
    );
    
    return $options;
}

function wpsc_get_pdf_encoding_options() {
    $options = array(
        'utf8'        => __( 'UTF-8', 'wp-school-calendar' ),
        'windows1252' => __( 'Windows-1252', 'wp-school-calendar' ),
    );
    
    return $options;
}

function wpsc_get_range_navigation_type_options() {
    $options = array(
        'prevnext' => __( 'Prev/Next Button', 'wp-school-calendar' ),
        'dropdown' => __( 'Dropdown Menu', 'wp-school-calendar' ),
    );
    
    return $options;
}

/**
 * Get calendar display options
 * 
 * @since 1.0
 * 
 * @return array Array of calendar display options
 */
function wpsc_get_num_column_options() {
    $options = array(
        'one-column'    => __( 'One Column', 'wp-school-calendar' ),
        'two-columns'   => __( 'Two Columns', 'wp-school-calendar' ),
        'three-columns' => __( 'Three Columns', 'wp-school-calendar' ),
        'four-columns'  => __( 'Four Columns', 'wp-school-calendar' ),
    );
    
    return $options;
}

/**
 * Get day format options
 * 
 * @since 1.0
 * 
 * @return array Array of day format options
 */
function wpsc_get_day_format_options() {
    $options = array(
        'one-letter'   => __( 'One Letter', 'wp-school-calendar' ),
        'three-letter' => __( 'Three Letter', 'wp-school-calendar' ),
        'full-name'    => __( 'Full Name', 'wp-school-calendar' ),
    );
    
    return $options;
}

/**
 * Convert HEX color to RGB
 * 
 * @since 1.0
 * 
 * @param string $color HEX color to be convert
 * @return array Converted RGB color
 */
function wpsc_hex2rgb( $color ) {
	$color = trim( $color, '#' );

	if ( strlen( $color ) === 3 ) {
		$r = hexdec( substr( $color, 0, 1 ).substr( $color, 0, 1 ) );
		$g = hexdec( substr( $color, 1, 1 ).substr( $color, 1, 1 ) );
		$b = hexdec( substr( $color, 2, 1 ).substr( $color, 2, 1 ) );
	} else if ( strlen( $color ) === 6 ) {
		$r = hexdec( substr( $color, 0, 2 ) );
		$g = hexdec( substr( $color, 2, 2 ) );
		$b = hexdec( substr( $color, 4, 2 ) );
	} else {
		return array();
	}

	return array( 'red' => $r, 'green' => $g, 'blue' => $b );
}

function wpsc_get_weekday_ids( $start_of_week = null ) {
    if ( is_null( $start_of_week ) ) {
        $start_of_week = intval( get_option( 'start_of_week' ) );
    }
    
    $weekday_ids = array(
        0 => array( 0, 1, 2, 3, 4, 5, 6 ),
        1 => array( 1, 2, 3, 4, 5, 6, 0 ),
        2 => array( 2, 3, 4, 5, 6, 0, 1 ),
        3 => array( 3, 4, 5, 6, 0, 1, 2 ),
        4 => array( 4, 5, 6, 0, 1, 2, 3 ),
        5 => array( 5, 6, 0, 1, 2, 3, 4 ),
        6 => array( 6, 0, 1, 2, 3, 4, 5 )
    );
    
    return $weekday_ids[$start_of_week];
}

function wpsc_normalize_special_character_for_html( $word ) {
    $word = str_replace( "À", "&#192;", $word );
    $word = str_replace( "Á", "&#193;", $word );
    $word = str_replace( "Â", "&#194;", $word );
    $word = str_replace( "Ã", "&#195;", $word );
    $word = str_replace( "Ä", "&#196;", $word );
    $word = str_replace( "Å", "&#197;", $word );
    $word = str_replace( "Æ", "&#198;", $word );
    $word = str_replace( "Ç", "&#199;", $word );
    $word = str_replace( "È", "&#200;", $word );
    $word = str_replace( "É", "&#201;", $word );
    $word = str_replace( "Ê", "&#202;", $word );
    $word = str_replace( "Ë", "&#203;", $word );
    $word = str_replace( "Ì", "&#204;", $word );
    $word = str_replace( "Í", "&#205;", $word );
    $word = str_replace( "Î", "&#206;", $word );
    $word = str_replace( "Ï", "&#207;", $word );
    $word = str_replace( "Ð", "&#208;", $word );
    $word = str_replace( "Ñ", "&#209;", $word );
    $word = str_replace( "Ò", "&#210;", $word );
    $word = str_replace( "Ó", "&#211;", $word );
    $word = str_replace( "Ô", "&#212;", $word );
    $word = str_replace( "Õ", "&#213;", $word );
    $word = str_replace( "Ö", "&#214;", $word );
    $word = str_replace( "Ø", "&#216;", $word );
    $word = str_replace( "Œ", "&#338;", $word );
    $word = str_replace( "Š", "&#352;", $word );
    $word = str_replace( "Ù", "&#217;", $word );
    $word = str_replace( "Ú", "&#218;", $word );
    $word = str_replace( "Û", "&#219;", $word );
    $word = str_replace( "Ü", "&#220;", $word );
    $word = str_replace( "Ý", "&#221;", $word );
    $word = str_replace( "Ÿ", "&#376;", $word );
    $word = str_replace( "Ž", "&#381;", $word );
    $word = str_replace( "Þ", "&#222;", $word );
    $word = str_replace( "ß", "&#223;", $word );
    $word = str_replace( "à", "&#224;", $word );
    $word = str_replace( "á", "&#225;", $word );
    $word = str_replace( "â", "&#226;", $word );
    $word = str_replace( "ã", "&#227;", $word );
    $word = str_replace( "ä", "&#228;", $word );
    $word = str_replace( "å", "&#229;", $word );
    $word = str_replace( "æ", "&#230;", $word );
    $word = str_replace( "ç", "&#231;", $word );
    $word = str_replace( "è", "&#232;", $word );
    $word = str_replace( "é", "&#233;", $word );
    $word = str_replace( "ê", "&#234;", $word );
    $word = str_replace( "ë", "&#235;", $word );
    $word = str_replace( "ì", "&#236;", $word );
    $word = str_replace( "í", "&#237;", $word );
    $word = str_replace( "î", "&#238;", $word );
    $word = str_replace( "ï", "&#239;", $word );
    $word = str_replace( "ð", "&#240;", $word );
    $word = str_replace( "ñ", "&#241;", $word );
    $word = str_replace( "ò", "&#242;", $word );
    $word = str_replace( "ó", "&#243;", $word );
    $word = str_replace( "ô", "&#244;", $word );
    $word = str_replace( "õ", "&#245;", $word );
    $word = str_replace( "ö", "&#246;", $word );
    $word = str_replace( "ø", "&#248;", $word );
    $word = str_replace( "œ", "&#339;", $word );
    $word = str_replace( "š", "&#353;", $word );
    $word = str_replace( "ù", "&#249;", $word );
    $word = str_replace( "ú", "&#250;", $word );
    $word = str_replace( "û", "&#251;", $word );
    $word = str_replace( "ü", "&#252;", $word );
    $word = str_replace( "ý", "&#253;", $word );
    $word = str_replace( "ÿ", "&#255;", $word );
    $word = str_replace( "ž", "&#382;", $word );
    $word = str_replace( "þ", "&#254;", $word );
    
    return $word;
}

function wpsc_get_calendars() {
    global $wpdb;
    
    $sql  = "SELECT p.ID, p.post_title, p.post_date ";
    $sql .= "FROM $wpdb->posts p ";
    $sql .= "WHERE p.post_type = 'school_calendar' AND p.post_status = 'publish' ";
    $sql .= "ORDER BY p.post_title ASC";
    
    $objs = $wpdb->get_results( $sql );
    
    if ( $objs ) {
        $calendars = array();
        
        foreach ( $objs as $obj ) {
            $calendars[] = array(
                'calendar_id' => $obj->ID,
                'name'        => $obj->post_title,
                'created'     => $obj->post_date
            );
        }
        
        return $calendars;
    }

    return false;
}

function wpsc_get_calendar( $id ) {
    $obj = get_post( intval( $id ) );
    
    if ( $obj ) {
        if ( 'school_calendar' !== $obj->post_type ) {
            return false;
        }
        
        $calendar_options         = get_post_meta( $obj->ID, '_calendar_options', true );
        $default_calendar_options = wpsc_get_default_calendar_options();
        $calendar_options         = wp_parse_args( $calendar_options, $default_calendar_options );
        
        $calendar = apply_filters( 'wpsc_calendar_fields', array(
            'calendar_id'                 => $obj->ID,
            'name'                        => $obj->post_title,
            'created'                     => $obj->post_date,
            'theme'                       => $calendar_options['theme'],
            'groups'                      => $calendar_options['groups'],
            'include_no_groups'           => $calendar_options['include_no_groups'],
            'categories'                  => $calendar_options['categories'],
            'num_months'                  => $calendar_options['num_months'],
            'start_year'                  => $calendar_options['start_year'],
            'default_month_range'         => $calendar_options['default_month_range'],
            'custom_default_year'         => $calendar_options['custom_default_year'],
            'custom_default_month_range'  => $calendar_options['custom_default_month_range'],
            'num_columns'                 => $calendar_options['num_columns'],
            'week_start'                  => $calendar_options['week_start'],
            'weekday'                     => $calendar_options['weekday'],
            'day_format'                  => $calendar_options['day_format'],
            'date_format'                 => $calendar_options['date_format'],
            'show_year'                   => $calendar_options['show_year'],
            'show_important_date_cats'    => $calendar_options['show_important_date_cats'],
            'show_important_date_listing' => $calendar_options['show_important_date_listing'],
            'important_date_heading'      => $calendar_options['important_date_heading'],
        ), $calendar_options );
        
        return $calendar;
    }
    
    return false;
}

function wpsc_get_default_calendar_options() {
    $options = apply_filters( 'wpsc_default_calendar_options', array(
        'theme'                       => 'default',
        'groups'                      => array(),
        'include_no_groups'           => 'Y',
        'categories'                  => array(),
        'num_months'                  => 'twelve',
        'start_year'                  => '01',
        'default_month_range'         => 'current',
        'custom_default_year'         => '',
        'custom_default_month_range'  => '',
        'num_columns'                 => 'two-columns',
        'week_start'                  => 0,
        'weekday'                     => array( 1, 2, 3, 4, 5 ),
        'day_format'                  => 'three-letter',
        'date_format'                 => 'medium',
        'show_year'                   => 'Y',
        'show_important_date_cats'    => 'Y',
        'show_important_date_listing' => 'Y',
        'important_date_heading'      => __( 'Dates to Remember', 'wp-school-calendar' ),
    ) );
    
    return $options;
}

function wpsc_get_default_calendar_id() {
    global $wpdb;
    
    $sql  = "SELECT ID FROM $wpdb->posts ";
    $sql .= "WHERE post_type = 'school_calendar' AND post_status = 'publish' ";
    $sql .= "ORDER BY post_date ASC LIMIT 1";
    
    $obj = $wpdb->get_row( $sql );
    
    if ( $obj ) {
        return $obj->ID;
    }
    
    return false;
}