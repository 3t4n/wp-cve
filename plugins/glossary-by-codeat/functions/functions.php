<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */
/**
 * Generate the list of terms
 *
 * @param string $order Order.
 * @param int    $num   Amount.
 * @param string $tax   Taxonomy name.
 * @param string $theme Theme.
 * @return string
 */
function get_glossary_terms_list(
    string $order,
    int $num,
    string $tax = '',
    string $theme = ''
)
{
    $orderby = 'date';
    
    if ( 'last' !== $order ) {
        $orderby = 'title';
        if ( 'asc' === $order ) {
            $order = 'ASC';
        }
    }
    
    if ( !in_array( $theme, array(
        'hyphen',
        'tilde',
        'dot',
        'arrow'
    ), true ) ) {
        $theme = '';
    }
    if ( !empty($theme) ) {
        $theme = ' theme-' . $theme;
    }
    $args = array(
        'post_type'              => 'glossary',
        'order'                  => $order,
        'orderby'                => $orderby,
        'posts_per_page'         => $num,
        'post_status'            => 'publish',
        'update_post_meta_cache' => false,
        'fields'                 => 'ids',
    );
    
    if ( !empty($tax) && 'any' !== $tax ) {
        $field = 'slug';
        
        if ( is_numeric( $tax ) ) {
            $tax = intval( $tax );
            $field = 'term_id';
        }
        
        $args['tax_query'] = array(
            // phpcs:ignore
            array(
                'taxonomy' => 'glossary-cat',
                'terms'    => array( $tax ),
                'field'    => $field,
            ),
        );
    }
    
    $glossary = new WP_Query( $args );
    return generate_glossary_list_by_wp_query( $glossary, $theme );
}

/**
 * Generate the list of by wp_query, used internally
 *
 * @param \WP_Query $glossary The WP_Query.
 * @param string    $theme Theme.
 * @return string
 */
function generate_glossary_list_by_wp_query( $glossary, $theme )
{
    
    if ( $glossary->have_posts() ) {
        $out = '<ul class="glossary-terms-list' . $theme . '">';
        while ( $glossary->have_posts() ) {
            $glossary->the_post();
            $out .= '<li><a href="' . get_glossary_term_url( (int) get_the_ID() ) . '">' . get_the_title() . '</a></li>';
        }
        $out .= '</ul>';
        wp_reset_postdata();
        return $out;
    }
    
    return '';
}

/**
 * Get the url of the term attached
 *
 * @param int|string $term_id The term ID.
 * @return string
 */
function get_glossary_term_url( $term_id = '' )
{
    if ( empty($term_id) ) {
        $term_id = get_the_ID();
    }
    $url_suffix = '';
    $type = esc_html( strval( get_post_meta( (int) $term_id, GT_SETTINGS . '_link_type', true ) ) );
    $link = esc_html( strval( get_post_meta( (int) $term_id, GT_SETTINGS . '_url', true ) ) );
    $cpt = esc_html( strval( get_post_meta( (int) $term_id, GT_SETTINGS . '_cpt', true ) ) );
    if ( empty($link) && empty($cpt) ) {
        return (string) get_the_permalink( (int) $term_id ) . $url_suffix;
    }
    if ( 'external' === $type || empty($type) ) {
        return (string) $link . $url_suffix;
    }
    if ( 'internal' === $type ) {
        return (string) get_the_permalink( (int) $cpt ) . $url_suffix;
    }
    return '';
}

/**
 * Generate a list of category terms
 *
 * @param string $order Order.
 * @param string $num   Amount.
 * @param string $theme Theme.
 * @return string
 */
function get_glossary_cats_list( string $order = 'ASC', string $num = '0', string $theme = '' )
{
    $num = (int) $num;
    if ( 0 !== $num ) {
        ++$num;
    }
    if ( !in_array( $theme, array(
        'hyphen',
        'tilde',
        'dot',
        'arrow'
    ), true ) ) {
        $theme = '';
    }
    if ( !empty($theme) ) {
        $theme = ' theme-' . $theme;
    }
    $taxs = get_terms( array(
        'hide_empty' => false,
        'taxonomy'   => 'glossary-cat',
        'order'      => $order,
        'number'     => $num,
        'orderby'    => 'title',
    ) );
    $out = '<ul class="glossary-cats-list' . $theme . '">';
    
    if ( !is_wp_error( $taxs ) && is_array( $taxs ) ) {
        foreach ( $taxs as $tax ) {
            if ( !is_object( $tax ) ) {
                continue;
            }
            $out .= get_glossary_cats_list_li( $tax, $taxs );
        }
        return $out . '</ul>';
    }
    
    return '';
}

/**
 * Generate the list for the shortcode list
 *
 * @param \WP_Term $tax The taxonomy.
 * @param array    $taxs All the taxonomies to look for the parent.
 * @return string
 */
function get_glossary_cats_list_li( WP_Term $tax, array $taxs )
{
    // phpcs:ignore
    $tax_link = get_term_link( $tax );
    $out = '';
    $subout = '';
    if ( !is_wp_error( $tax_link ) && $tax->parent === 0 ) {
        $out = '<li><a href="' . $tax_link . '">' . $tax->name . '</a>';
    }
    foreach ( $taxs as &$subcategory ) {
        //phpcs:ignore
        if ( !is_a( $subcategory, 'WP_Term' ) ) {
            continue;
        }
        if ( $subcategory->parent !== $tax->term_id ) {
            continue;
        }
        $taxsub_link = get_term_link( $subcategory );
        if ( is_wp_error( $taxsub_link ) ) {
            continue;
        }
        $subout .= '<li><a href="' . $taxsub_link . '">' . $subcategory->name . '</a></li>';
    }
    if ( !empty($subout) ) {
        $out .= '<ul>' . $subout . '</ul>';
    }
    $out .= '</li>';
    return $out;
}

/**
 * Check if text is RTL
 *
 * @param string $stringtomatch The string.
 * @return int|bool
 */
function gl_text_is_rtl( string $stringtomatch )
{
    $rtl_chars_pattern = '/[\\x{0590}-\\x{05ff}\\x{0600}-\\x{06ff}]/u';
    return preg_match( $rtl_chars_pattern, $stringtomatch );
}

/**
 * Return the cached value of terms count
 *
 * @return string
 */
function gl_get_terms_count()
{
    return strval( get_option( GT_SETTINGS . '_count_terms', true ) );
}

/**
 * Return the cached value of related terms count
 *
 * @return string
 */
function gl_get_related_terms_count()
{
    return strval( get_option( GT_SETTINGS . '_count_related_terms', true ) );
}

/**
 * Update the database with cached value for count of terms and related terms
 *
 * @return void
 */
function gl_update_counter()
{
    //phpcs:ignore SlevomatCodingStandard.Complexity.Cognitive.ComplexityTooHigh
    $args = array(
        'post_type'      => 'glossary',
        'posts_per_page' => -1,
        'order'          => 'asc',
        'post_status'    => 'publish',
    );
    $query = new WP_Query( $args );
    $count = 0;
    $count_related = 0;
    if ( $query->have_posts() ) {
        foreach ( $query->posts as $post ) {
            ++$count;
            $post_id = $post;
            if ( is_object( $post ) ) {
                $post_id = $post->ID;
            }
            if ( !is_int( $post_id ) ) {
                continue;
            }
            $related = gl_related_post_meta( $post_id );
            if ( empty($related) ) {
                continue;
            }
            $count_related += count( $related );
        }
    }
    update_option( GT_SETTINGS . '_count_terms', $count );
    update_option( GT_SETTINGS . '_count_related_terms', $count_related );
}

/**
 * Generate the queries for the A2Z index
 *
 * @param array $atts The parameters.
 * @return array The various SQL pieces.
 */
function prepare_get_a2z_queries( array $atts = array() )
{
    $sql = array(
        'count_query' => '',
        'tax_slug'    => '',
        'join_tables' => '',
        'initials'    => '',
    );
    global  $wpdb ;
    if ( $atts['show_counts'] ) {
        $sql['count_query'] = ", COUNT( substring( TRIM( UPPER( {$wpdb->posts}.post_title ) ), 1, 1) ) as counts";
    }
    
    if ( !empty($atts['taxonomy']) && $atts['taxonomy'] !== 'any' ) {
        $sql['tax_slug'] = $wpdb->prepare( " AND {$wpdb->terms}.slug = %s AND {$wpdb->term_taxonomy}.taxonomy = 'glossary-cat'", $atts['taxonomy'] );
        $sql['join_tables'] = " LEFT JOIN {$wpdb->term_relationships} ON ({$wpdb->posts}.ID = {$wpdb->term_relationships}.object_id) LEFT JOIN {$wpdb->term_taxonomy} ON ({$wpdb->term_relationships}.term_taxonomy_id = {$wpdb->term_taxonomy}.term_taxonomy_id) LEFT JOIN {$wpdb->terms} ON ({$wpdb->term_taxonomy}.term_id = {$wpdb->terms}.term_id)";
    }
    
    
    if ( !empty($atts['letters']) ) {
        $sql['initials'] = ' AND (';
        $atts['letters'] = explode( ',', $atts['letters'] );
        foreach ( $atts['letters'] as $key => $initial ) {
            $sql['initials'] .= $wpdb->prepare( " SUBSTRING({$wpdb->posts}.post_title,1,1) = %s OR", trim( $initial ) );
            if ( count( $atts['letters'] ) !== $key + 1 ) {
                continue;
            }
            $sql['initials'] = mb_substr( $sql['initials'], 0, -2 );
        }
        $sql['initials'] .= ')';
    }
    
    return $sql;
}

/**
 * Get the list of terms by A2Z index
 *
 * @param array $atts The parameters.
 * @return array The terms.
 */
function gl_get_a2z_initial( array $atts = array() )
{
    global  $wpdb ;
    $default = array(
        'show_counts' => false,
        'taxonomy'    => '',
        'letters'     => '',
    );
    $atts = array_merge( $default, $atts );
    $sanitized_sql_pieces = prepare_get_a2z_queries( $atts );
    $cache_key = 'glossary_a2z_initial_' . md5( (string) wp_json_encode( $atts ) );
    $query_output = wp_cache_get( $cache_key );
    
    if ( false === $query_output ) {
        $query_output = $wpdb->get_results( "SELECT DISTINCT SUBSTRING( TRIM( UPPER( {$wpdb->posts}.post_title ) ), 1, 1) as initial" . $sanitized_sql_pieces['count_query'] . " FROM {$wpdb->posts}" . $sanitized_sql_pieces['join_tables'] . " WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'glossary'" . $sanitized_sql_pieces['tax_slug'] . $sanitized_sql_pieces['initials'] . " GROUP BY initial ORDER BY TRIM( UPPER( {$wpdb->posts}.post_title ) );", ARRAY_A );
        //phpcs:ignore
        wp_cache_set( $cache_key, $query_output );
    }
    
    return $query_output;
}

/**
 * Return initials and ids
 *
 * @param array $atts The parameters.
 * @return array Initial and Terms.
 */
function gl_get_a2z_ids( array $atts = array() )
{
    global  $wpdb ;
    $default = array(
        'show_counts' => false,
        'taxonomy'    => '',
        'letters'     => '',
    );
    $atts = array_merge( $default, $atts );
    $sanitized_sql_pieces = prepare_get_a2z_queries( $atts );
    $cache_key = 'glossary_a2z_ids_' . md5( (string) wp_json_encode( $atts ) );
    $query_output = wp_cache_get( $cache_key );
    
    if ( false === $query_output ) {
        $query_output = $wpdb->get_results( "SELECT ID FROM {$wpdb->posts}" . $sanitized_sql_pieces['join_tables'] . " WHERE {$wpdb->posts}.post_status = 'publish' AND {$wpdb->posts}.post_type = 'glossary'" . $sanitized_sql_pieces['tax_slug'] . $sanitized_sql_pieces['initials'] . " ORDER BY TRIM( UPPER( {$wpdb->posts}.post_title ) );", ARRAY_A );
        //phpcs:ignore
        wp_cache_set( $cache_key, $query_output );
    }
    
    $id_cleaned = array();
    foreach ( $query_output as $id ) {
        $id_cleaned[] = $id['ID'];
    }
    return $id_cleaned;
}

/**
 * Length of the string based on encode
 *
 * @param string $stringtomatch The string to get the length.
 * @return int
 */
function gl_get_len( string $stringtomatch )
{
    if ( gl_text_is_rtl( $stringtomatch ) ) {
        return mb_strlen( $stringtomatch );
    }
    return mb_strlen( $stringtomatch, 'latin1' );
}

/**
 * Get a checkbox settings as boolean
 *
 * @param string $value The ID label of the settings.
 * @return bool
 */
function gl_get_bool_settings( string $value )
{
    $settings = gl_get_settings();
    return isset( $settings[$value] ) && (bool) $settings[$value];
}

/**
 * Check the settings and if is a single term page
 *
 * @param int $post_id The ID to get this setting.
 * @return array
 */
function gl_related_post_meta( int $post_id )
{
    $value = strval( get_post_meta( $post_id, GT_SETTINGS . '_tag', true ) );
    $value = array_map( 'trim', explode( ',', $value ) );
    if ( empty($value[0]) ) {
        $value = array();
    }
    return $value;
}

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 1.0.0
 * @return array
 */
function gl_get_settings()
{
    /**
     * Alter the global settings
     *
     * @param array $settings The settings.
     * @since 1.5.0
     * @return array|string $settings We need the settings.
     */
    $data = array();
    $option = get_option( GT_SETTINGS . '-settings' );
    if ( is_array( $option ) ) {
        $data = $option;
    }
    return apply_filters( 'glossary_settings', $data );
}

/**
 * Get the settings of the plugin in a filterable way
 *
 * @since 1.0.0
 * @return array
 */
function gl_get_settings_extra()
{
    /**
     * Alter the global extra settings
     *
     * @param array $settings The settings.
     * @since 1.5.0
     * @return array|string $settings We need the settings.
     */
    $data = array();
    $option = get_option( GT_SETTINGS . '-extra' );
    if ( is_array( $option ) ) {
        $data = $option;
    }
    return apply_filters( 'glossary_extra', $data );
}

/**
 * Return the base url for glossary post type
 *
 * @return string
 */
function gl_get_base_url()
{
    $base_url = get_post_type_archive_link( 'glossary' );
    
    if ( !$base_url ) {
        $base_url = esc_url( home_url( '/' ) );
        if ( 'page' === get_option( 'show_on_front' ) ) {
            $base_url = esc_url( (string) get_permalink( intval( get_option( 'page_for_posts' ) ) ) );
        }
    }
    
    return $base_url;
}

/**
 * Return the tooltip type
 *
 * @param string $type    The type of tooltip.
 * @param bool   $as_true As inverse.
 * @return bool
 */
function is_type_inject_set_as( string $type, bool $as_true = true )
{
    $settings = gl_get_settings();
    if ( !$as_true ) {
        return isset( $settings['tooltip'] ) && $settings['tooltip'] !== $type;
    }
    return isset( $settings['tooltip'] ) && $settings['tooltip'] === $type;
}
