<?php

/**
 * Card Oracle Core Functions
 *
 * General core functions available on both the front-end and admin.
 *
 * @package CardOracle\Functions
 * @version 1.1.2
 */
// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
define( 'DEFAULT_COUNTRY', 'US' );
/**
 * Create array of allowed html for wp_kses().
 *
 * @version 1.1.2
 * @return array
 */
function card_oracle_allowed_html()
{
    // All allowed tags.
    return array(
        'DOCTYPE'    => array(),
        'a'          => array(
        'class' => array(),
        'href'  => array(),
        'rel'   => array(),
        'title' => array(),
    ),
        'abbr'       => array(
        'title' => array(),
    ),
        'b'          => array(),
        'blockquote' => array(
        'cite' => array(),
    ),
        'body'       => array(),
        'br'         => array(),
        'center'     => array(),
        'cite'       => array(
        'title' => array(),
    ),
        'code'       => array(),
        'del'        => array(
        'datetime' => array(),
        'title'    => array(),
    ),
        'dd'         => array(),
        'div'        => array(
        'class' => array(),
        'title' => array(),
        'style' => array(),
    ),
        'dl'         => array(),
        'dt'         => array(),
        'em'         => array(),
        'h1'         => array(),
        'h2'         => array(),
        'h3'         => array(),
        'h4'         => array(),
        'h5'         => array(),
        'h6'         => array(),
        'head'       => array(),
        'html'       => array(
        'xmlns'   => array(),
        'xmlns:o' => array(),
        'xmlns:v' => array(),
    ),
        'i'          => array(),
        'img'        => array(
        'alt'     => array(),
        'class'   => array(),
        'height'  => array(),
        'loading' => array(),
        'sizes'   => array(),
        'src'     => array(),
        'srcset'  => array(),
        'width'   => array(),
    ),
        'input'      => array(
        'id'          => array(),
        'name'        => array(),
        'placeholder' => array(),
        'type'        => array(),
        'value'       => array(),
    ),
        'label'      => array(
        'for' => array(),
    ),
        'li'         => array(
        'class' => array(),
    ),
        'meta'       => array(
        'content'    => array(),
        'http-equiv' => array(),
        'name'       => array(),
    ),
        'ol'         => array(
        'class' => array(),
    ),
        'p'          => array(
        'class' => array(),
    ),
        'q'          => array(
        'cite'  => array(),
        'title' => array(),
    ),
        'span'       => array(
        'class' => array(),
        'title' => array(),
        'style' => array(),
    ),
        'strike'     => array(),
        'strong'     => array(),
        'style'      => array(),
        'table'      => array(
        'class' => array(),
    ),
        'tbody'      => array(),
        'td'         => array(
        'colspan' => array(),
        'rowspan' => array(),
        'style'   => array(),
        'valign'  => array(),
        'width'   => array(),
    ),
        'thead'      => array(),
        'title'      => array(),
        'tr'         => array(
        'height' => array(),
    ),
        'ul'         => array(
        'class' => array(),
    ),
    );
}

/**
 * Create the email.
 *
 * @since  0.22.0
 *
 * @param  string $title Text to display for the title.
 * @param  string $style Text to display for the title.
 * @param  string $body Text to display for the title.
 * @return string
 */
function card_oracle_create_email( $title, $style, $body )
{
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_create_email: start: Title [' . $title . ']' );
    $email_text = sprintf(
        '<!DOCTYPE html><head><title>%1$s</title><meta http-equiv="Content-Type" content="text/html; charset=utf-8"><meta name="viewport" content="width=device-width, initial-scale=1.0 "><style>%2$s</style></head><body>%3$s</body></html>',
        $title,
        $style,
        $body
    );
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_create_email: end' );
    return $email_text;
}

/**
 * Display the footer if any.
 *
 * @since  0.23.0
 *
 * @param  string $footer_text Text to display in the footer.
 * @return string
 */
function card_oracle_display_footer( $footer_text )
{
    $display_html = '';
    
    if ( !empty($footer_text) || get_option( 'card_oracle_powered_by' ) ) {
        $display_html .= '<cotd-footer>';
        if ( !empty($footer_text) ) {
            $display_html .= $footer_text;
        }
        
        if ( get_option( 'card_oracle_powered_by' ) ) {
            $powered_link = '<a href="https://chillichalli.com/card-oracle">ChilliChalli.com</a>';
            /* Translators: %s is a website URL */
            $powered = sprintf( __( 'Create your own reading using Tarot Card Oracle! Go to %s', 'card-oracle' ), $powered_link );
            if ( !empty($footer_text) ) {
                $display_html .= '<br />';
            }
            $display_html .= $powered;
        }
        
        $display_html .= '</cotd-footer>';
    }
    
    return $display_html;
}

/**
 * Get From email address.
 *
 * @since 0.25.0
 * @return string From email address.
 */
function card_oracle_get_from_email_address()
{
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_get_from_email_address: start' );
    $from_email_name = ( get_option( 'card_oracle_from_email_name' ) ? get_option( 'card_oracle_from_email_name' ) : get_bloginfo( 'name' ) );
    $from_email = '<' . (( get_option( 'card_oracle_from_email' ) ? get_option( 'card_oracle_from_email' ) : get_bloginfo( 'admin_email' ) )) . '>';
    // Create the headers. Add From name and address if options are set.
    $from = 'From: ' . $from_email_name . $from_email . "\r\n";
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_get_from_email_address: from email address [' . $from . ']' );
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_get_from_email_address: end' );
    return $from;
}

/**
 * Get PHP Arg Separator Output
 *
 * @since 0.25.0
 * @return string Arg separator output
 */
function card_oracle_get_php_arg_separator_output()
{
    return ini_get( 'arg_separator.output' );
}

/**
 * Get array of environment information. Includes thing like software
 * versions, and various server settings.
 *
 * @version 1.0.3
 * @return array
 */
function card_oracle_get_environment_info()
{
    global  $wpdb, $co_logs ;
    $md5_files = array();
    // Get Card Oracle Version and if Premium.
    $card_oracle_version = CARD_ORACLE_VERSION;
    // Files to check MD5.
    $all_md5_files = array(
        array(
        'name'     => 'Card Oracle',
        'filename' => CARD_ORACLE_DIR . 'card-oracle.php',
        'md5sum'   => 'f148345032ffb740849e32752d62ef29',
    ),
        array(
        'name'     => 'Class Card Oracle',
        'filename' => CARD_ORACLE_DIR . 'includes/class-card-oracle.php',
        'md5sum'   => '43be047fe2713de2e6e428088f03b289',
    ),
        array(
        'name'     => 'Admin CSS',
        'filename' => CARD_ORACLE_DIR . 'admin/css/min/card-oracle-admin.min.css',
        'md5sum'   => '399254e57aa8a588555dd925f870fbde',
    ),
        array(
        'name'     => 'Admin JS',
        'filename' => CARD_ORACLE_DIR . 'admin/js/min/card-oracle-admin.min.js',
        'md5sum'   => '253e83c21c9f21824e147a60ed9f94dd',
    ),
        array(
        'name'     => 'Enhanced Paypal',
        'filename' => CARD_ORACLE_DIR . 'includes/paypal/enhanced-paypal-shortcodes__premium_only.php',
        'md5sum'   => '22df502a8e4fa17946312d07c1f6fd3b',
    ),
        array(
        'name'     => 'Paypal Standard',
        'filename' => CARD_ORACLE_DIR . 'includes/paypal/paypal-standard__premium_only.php',
        'md5sum'   => '74758a95efea0b56c944cc4366f249bc',
    ),
        array(
        'name'     => 'Public CSS',
        'filename' => CARD_ORACLE_DIR . 'public/css/min/card-oracle-public.min.css',
        'md5sum'   => 'ce5fa09c1cc5f9bf8015b5e0a9a00384',
    ),
        array(
        'name'     => 'Public JS',
        'filename' => CARD_ORACLE_DIR . 'public/js/min/card-oracle-public.min.js',
        'md5sum'   => '0a47d761a2d1774ae40bacf675a0780b',
    ),
        array(
        'name'     => 'Premium Public CSS',
        'filename' => CARD_ORACLE_DIR . 'public/css/min/card-oracle-public__premium_only.min.css',
        'md5sum'   => 'c4ced2ea0d55703f36bd3fdbabb9af50',
    ),
        array(
        'name'     => 'Selection E-Cabala',
        'filename' => CARD_ORACLE_DIR . 'public/includes/card-oracle-selection-ecabala.php',
        'md5sum'   => 'a6c3b5d12f54b774b524401c4bb0d84f',
    ),
        array(
        'name'     => 'E-Cabala',
        'filename' => CARD_ORACLE_DIR . 'public/layouts/e_cabala.php',
        'md5sum'   => 'dc9ab6ce506616164da14b4679338ef5',
    )
    );
    foreach ( $all_md5_files as $md5_file ) {
        if ( file_exists( $md5_file['filename'] ) ) {
            array_push( $md5_files, $md5_file );
        }
    }
    // Figure out cURL version, if installed.
    $curl_version = '';
    
    if ( function_exists( 'curl_version' ) ) {
        $curl_version = curl_version();
        $curl_version = $curl_version['version'] . ', ' . $curl_version['ssl_version'];
    } elseif ( extension_loaded( 'curl' ) ) {
        $curl_version = __( 'cURL installed but unable to retrieve version.', 'card-oracle' );
    }
    
    // WP memory limit.
    $wp_memory_limit = card_oracle_number_convert( WP_MEMORY_LIMIT );
    
    if ( function_exists( 'memory_get_usage' ) ) {
        $wp_memory_limit = max( $wp_memory_limit, card_oracle_number_convert( @ini_get( 'memory_limit' ) ) );
        // phpcs:ignore WordPress.PHP.NoSilencedErrors.Discouraged
    }
    
    $database_version = card_oracle_get_server_database_version();
    // Return all environment info. Described by JSON Schema.
    return array(
        'active_plugins'         => get_active_plugins(),
        'api_request_counts'     => $co_logs->get_log_count( 0, 'api_request' ),
        'card_oracle_options'    => get_card_oracle_options(),
        'card_oracle_version'    => $card_oracle_version,
        'curl_version'           => $curl_version,
        'database_prefix'        => $wpdb->prefix,
        'default_timezone'       => date_default_timezone_get(),
        'domdocument_enabled'    => class_exists( 'DOMDocument' ),
        'dropins_mu_plugins'     => get_dropins_mu_plugins(),
        'error_log_counts'       => $co_logs->get_log_count( 0, 'error' ),
        'external_object_cache'  => wp_using_ext_object_cache(),
        'hide_errors'            => !(defined( 'WP_DEBUG' ) && defined( 'WP_DEBUG_DISPLAY' ) && WP_DEBUG && WP_DEBUG_DISPLAY) || 0 === intval( ini_get( 'display_errors' ) ),
        'home_url'               => get_option( 'home' ),
        'inactive_plugins'       => get_inactive_plugins(),
        'language'               => get_locale(),
        'log_directory_writable' => (bool) @fopen( CARD_ORACLE_LOG_DIR . wp_hash( home_url( '/' ) ) . '-debug.log', 'a' ),
        'log_directory'          => CARD_ORACLE_LOG_DIR,
        'log_file'               => trailingslashit( CARD_ORACLE_LOG_DIR ) . wp_hash( home_url( '/' ) ) . '-debug.log',
        'max_upload_size'        => wp_max_upload_size(),
        'mbstring_enabled'       => extension_loaded( 'mbstring' ),
        'md5_files'              => $md5_files,
        'mysql_version_string'   => $database_version['string'],
        'mysql_version'          => $database_version['number'],
        'php_max_execution_time' => (int) ini_get( 'max_execution_time' ),
        'php_max_input_vars'     => (int) ini_get( 'max_input_vars' ),
        'php_post_max_size'      => card_oracle_number_convert( ini_get( 'post_max_size' ) ),
        'php_sendmail_path'      => ini_get( 'sendmail_path' ),
        'php_version'            => phpversion(),
        'post_type_counts'       => get_post_type_counts(),
        'rss_feed'               => card_oracle_check_feed(),
        'secure_connection'      => 'https' === substr( get_option( 'siteurl' ), 0, 5 ),
        'server_info'            => ( isset( $_SERVER['SERVER_SOFTWARE'] ) ? card_oracle_sanitize_text( wp_unslash( $_SERVER['SERVER_SOFTWARE'] ) ) : '' ),
        'site_url'               => get_option( 'siteurl' ),
        'suhosin_installed'      => extension_loaded( 'suhosin' ),
        'wp_cron'                => !(defined( 'DISABLE_WP_CRON' ) && DISABLE_WP_CRON),
        'wp_debug_mode'          => defined( 'WP_DEBUG' ) && WP_DEBUG,
        'wp_memory_limit'        => $wp_memory_limit,
        'wp_multisite'           => is_multisite(),
        'wp_version'             => get_bloginfo( 'version' ),
    );
}

/**
 * Retrieves the country codes for payment providers.
 *
 * @since 0.25.0
 * @return array Country codes.
 */
function card_oracle_get_country_code()
{
    global  $co_logs ;
    $country_array = array(
        DEFAULT_COUNTRY => 'United States',
    );
    $local_file = CARD_ORACLE_DIR . 'assets/data/country-code.json';
    $local_json = file_get_contents( $local_file );
    // @codingStandardsIgnoreLine
    
    if ( false === $local_json ) {
        // Log the failure.
        co_debug_log( 'card-oracle-core-functions.php:card_oracle_get_country_code: Cannot load file /admin/assets/data/country-code.json' );
        // translators: %s is a filename.
        $error = sprintf( __( 'Unable to access file [%s].', 'card-oracle' ), $local_file );
        $co_logs->add(
            'Local Demo Data',
            $error,
            null,
            'error'
        );
    } else {
        $json = json_decode( $local_json, true );
        foreach ( $json as $country ) {
            if ( true === $country['paypal'] ) {
                $country_array[$country['alpha-2']] = $country['name'];
            }
        }
    }
    
    return $country_array;
}

/**
 * Retrieves the MySQL server version. Based on $wpdb.
 *
 * @since 1.1.3
 * @return array Version information.
 */
function card_oracle_get_server_database_version()
{
    global  $wpdb ;
    if ( empty($wpdb->is_mysql) ) {
        return array(
            'string' => '',
            'number' => '',
        );
    }
    // phpcs:disable WordPress.DB.RestrictedFunctions, PHPCompatibility.Extensions.RemovedExtensions.mysql_DeprecatedRemoved
    $server_info = ( $wpdb->use_mysqli ? mysqli_get_server_info( $wpdb->dbh ) : ($server_info = 'Unknown') );
    // phpcs:enable WordPress.DB.RestrictedFunctions, PHPCompatibility.Extensions.RemovedExtensions.mysql_DeprecatedRemoved
    return array(
        'string' => $server_info,
        'number' => preg_replace( '/([^\\d.]+).*/', '', $server_info ),
    );
}

/**
 * Checks the rss feed for daily readings exists
 *
 * @since  0.26.0
 * @return boolean
 */
function card_oracle_check_feed()
{
    $name = 'tco';
    $registered = false;
    $rules = get_option( 'rewrite_rules' );
    
    if ( is_array( $rules ) ) {
        $feeds = array_keys( $rules, 'index.php?&feed=$matches[1]', true );
        foreach ( $feeds as $feed ) {
            if ( false !== strpos( $feed, $name ) ) {
                $registered = true;
            }
        }
    }
    
    return $registered;
}

/**
 * Display a delete button with Trash icon.
 *
 * @since  1.1.3
 *
 * @param  string $title Text to display for tool tip.
 * @param  string $name Name of the button element.
 * @param  string $value value of the button element.
 * @return string
 */
function card_oracle_delete_button( $title, $name, $value )
{
    return '<button title="' . esc_attr( $title ) . '" type="submit" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '"><span class="dashicons dashicons-trash"></span></button>';
}

/**
 * Display a Card Oracle help tip.
 *
 * @since  0.14.0
 *
 * @param  string $tip        Help tip text.
 * @param  bool   $allow_html Allow sanitized HTML if true or escape.
 * @return string
 */
function card_oracle_help_tip( $tip, $allow_html = false )
{
    $tip = ( $allow_html ? card_oracle_sanitize_tooltip( $tip ) : esc_attr( $tip ) );
    return '<div class="card-oracle-help-tip"><p>' . $tip . '</p></div>';
}

/**
 * Display a Card Oracle tool tip.
 *
 * @since  1.1.3
 *
 * @param  string $tip        Help tip text.
 * @param  bool   $allow_html Allow sanitized HTML if true or escape.
 * @return string
 */
function card_oracle_tool_tip( $tip, $allow_html = false )
{
    $tip = ( $allow_html ? card_oracle_sanitize_tooltip( $tip ) : esc_attr( $tip ) );
    return '<span title="' . $tip . '" class="dashicons dashicons-editor-help"></span>';
}

/**
 * Write out failed emails to the log
 *
 * @since  0.26.0
 *
 * @param  array $wp_error The WP error message.
 */
function card_oracle_mail_error( $wp_error )
{
    global  $co_logs ;
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_mail_error: start' );
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_mail_error: ' . $wp_error );
    $co_logs->add(
        'Mail',
        $wp_error,
        null,
        'error'
    );
    co_debug_log( 'card-oracle-core-functions.php:card_oracle_mail_error: end' );
}

/**
 * Create a Card Oracle modal.
 *
 * @since  1.0.5
 *
 * @param  string $key Unique key to add to the id.
 * @param  string $content Content to display in the modal.
 * @param  string $link URL link.
 * @param  string $link_class CSS class to add to the <a> element.
 *
 * @return string $output The html to create the modal.
 */
function card_oracle_modal(
    $key,
    $content,
    $link,
    $link_class = null
)
{
    $output = '<div id="card-oracle-open-modal-' . esc_attr( $key ) . '" class="card-oracle-modal-dialog">';
    $output .= '<div class="card-oracle-modal-wide"><a href="#close" title="Close" class="card-oracle-modal-close">X</a><div id="card-oracle-modal-box">';
    $output .= $content;
    $output .= '</div></div></div>';
    $output .= '<a class="' . $link_class . '" href="#card-oracle-open-modal-' . esc_attr( $key ) . '">' . esc_html( $link ) . '</a>';
    return $output;
}

/**
 * Convert string values into ints. IE 40MB is 40 * 1024 * 1024.
 *
 * @since  1.1.6
 *
 * @param  string $value A size value 1MB/2GB/etc.
 * @return int
 */
function card_oracle_number_convert( $value )
{
    $letter = mb_substr( $value, -1 );
    $number = mb_substr( $value, 0, -1 );
    switch ( strtoupper( $letter ) ) {
        case 'P':
            $number *= 1024;
            // No break.
        // No break.
        case 'T':
            $number *= 1024;
            // No break.
        // No break.
        case 'G':
            $number *= 1024;
            // No break.
        // No break.
        case 'M':
            $number *= 1024;
            // No break.
        // No break.
        case 'K':
            $number *= 1024;
            break;
        default:
            $number = $value;
    }
    return $number;
}

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @since  1.1.3
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function card_oracle_sanitize_text( $var )
{
    if ( is_array( $var ) ) {
        return array_map( 'card_oracle_sanitize_text', $var );
    }
    return ( is_scalar( $var ) ? sanitize_text_field( $var ) : $var );
}

/**
 * Sanitize a string destined to be a tooltip.
 *
 * @since  0.14.0 Tooltips are encoded with htmlspecialchars to prevent XSS. Should not be used in conjunction with esc_attr()
 * @param  string $var Data to sanitize.
 * @return string
 */
function card_oracle_sanitize_tooltip( $var )
{
    return htmlspecialchars( wp_kses( html_entity_decode( $var ), array(
        'br'     => array(),
        'em'     => array(),
        'strong' => array(),
        'small'  => array(),
        'span'   => array(),
        'ul'     => array(),
        'li'     => array(),
        'ol'     => array(),
        'p'      => array(),
    ) ) );
}

/**
 * Get array of layouts to display the deck to the users.
 *
 * @since  0.27.0
 *
 * @return array
 */
function get_deck_layouts()
{
    $deck_layouts = array(
        'standard'    => 'Standard',
        'overlapping' => 'Overlapping',
    );
    return $deck_layouts;
}

/**
 * Get array of layouts to display the deck to the users.
 *
 * @since  1.0.4
 *
 * @return array
 */
function get_presentation_layouts()
{
    $presentation_layouts = array( array(
        'uid'       => 'standard',
        'class'     => '',
        'label'     => __( 'Standard', 'card-oracle' ),
        'positions' => 0,
        'file'      => CARD_ORACLE_DIR . 'public/layouts/standard.php',
        'image'     => CARD_ORACLE_URL . 'assets/layouts/standard_layout.png',
    ), array(
        'uid'       => STANDARD_MOBILE,
        'class'     => '',
        'label'     => __( 'Mobile', 'card-oracle' ),
        'positions' => 0,
        'file'      => CARD_ORACLE_DIR . 'public/layouts/standard.php',
        'image'     => CARD_ORACLE_URL . 'assets/layouts/mobile_layout.png',
    ), array(
        'uid'       => 'three_layout_1',
        'class'     => 'three',
        'layout'    => '1',
        'file'      => CARD_ORACLE_EXTENDED_LAYOUT,
        'label'     => __( 'Three Card Layout 1', 'card-oracle' ),
        'image'     => CARD_ORACLE_URL . 'assets/layouts/three_card_layout-1.png',
        'positions' => 3,
    ) );
    return $presentation_layouts;
}

/**
 * Get array of counts of post types. Readings, Positions, Cards, Descriptions.
 *
 * @return array
 */
function get_card_oracle_options()
{
    global  $wpdb ;
    $all_options = wp_load_alloptions();
    $card_oracle_options = array();
    foreach ( $all_options as $name => $value ) {
        if ( strpos( $name, CARD_ORACLE_OPTION_PREFIX ) === 0 ) {
            $card_oracle_options[$name] = $value;
        }
    }
    ksort( $card_oracle_options );
    return $card_oracle_options;
}

/**
 * Get all the published cards for a specific reading
 *
 * @since  0.27.0
 * @param  string $reading_id This is the ID of the Reading.
 * @return array  All the Cards IDs in a Reading.
 */
function get_cards_for_reading( $reading_id )
{
    $transient_id = 'co_cards_' . md5( __CLASS__ . __FUNCTION__ . $reading_id );
    $cards = get_transient( $transient_id );
    
    if ( empty($cards) ) {
        $args = array(
            'fields'      => 'ids',
            'numberposts' => -1,
            'order'       => 'ASC',
            'orderby'     => 'ID',
            'post_type'   => 'co_cards',
            'post_status' => 'publish',
            'meta_query'  => array(
            // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
            array(
                'key'   => CO_READING_ID,
                'value' => $reading_id,
            ),
        ),
        );
        $cards = get_posts( $args );
    }
    
    return $cards;
}

/**
 * Get all the published positions for a specific reading
 *
 * @since  1.0.3
 * @param string $reading_id This is the ID of the Reading.
 * @return array All the Position IDs in a Reading.
 */
function get_positions_for_reading( $reading_id )
{
    // The $positions is an array of all the positions in a reading, it consists of the position title and position ID.
    $args = array(
        'numberposts' => -1,
        'order'       => 'ASC',
        'orderby'     => 'card_order_clause',
        'post_type'   => 'co_positions',
        'post_status' => 'publish',
        'meta_query'  => array(
        'reading_clause'    => array(
        'key'   => CO_READING_ID,
        'value' => $reading_id,
    ),
        'card_order_clause' => array(
        'key'  => CO_CARD_ORDER,
        'type' => 'numeric',
    ),
    ),
    );
    return get_posts( $args );
}

/**
 * Get array of counts of post types. Readings, Positions, Cards, Descriptions.
 *
 * @return array
 */
function get_post_type_counts()
{
    global  $wpdb ;
    $post_type_counts = $wpdb->get_results(
        // @codingStandardsIgnoreLine
        "SELECT post_type AS 'type', count(1) AS 'count' \n\t\tFROM {$wpdb->posts} \n\t\tGROUP BY post_type;"
    );
    return ( is_array( $post_type_counts ) ? $post_type_counts : array() );
}

/**
 * Get a list of plugins active on the site.
 *
 * @return array
 */
function get_active_plugins()
{
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    if ( !function_exists( 'get_plugin_data' ) ) {
        return array();
    }
    $active_plugins = (array) get_option( 'active_plugins', array() );
    
    if ( is_multisite() ) {
        $network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
        $active_plugins = array_merge( $active_plugins, $network_activated_plugins );
    }
    
    $active_plugins_data = array();
    foreach ( $active_plugins as $plugin ) {
        $data = get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
        $active_plugins_data[] = format_plugin_data( $plugin, $data );
    }
    return $active_plugins_data;
}

/**
 * Get a list of inplugins active on the site.
 *
 * @return array
 */
function get_inactive_plugins()
{
    require_once ABSPATH . 'wp-admin/includes/plugin.php';
    if ( !function_exists( 'get_plugins' ) ) {
        return array();
    }
    $plugins = get_plugins();
    $active_plugins = (array) get_option( 'active_plugins', array() );
    
    if ( is_multisite() ) {
        $network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
        $active_plugins = array_merge( $active_plugins, $network_activated_plugins );
    }
    
    $plugins_data = array();
    foreach ( $plugins as $plugin => $data ) {
        if ( in_array( $plugin, $active_plugins, true ) ) {
            continue;
        }
        $plugins_data[] = format_plugin_data( $plugin, $data );
    }
    return $plugins_data;
}

/**
 * Format plugin data, including data on updates, into a standard format.
 *
 * @since 3.6.0
 * @param string $plugin Plugin directory/file.
 * @param array  $data Plugin data from WP.
 * @return array Formatted data.
 */
function format_plugin_data( $plugin, $data )
{
    require_once ABSPATH . 'wp-admin/includes/update.php';
    if ( !function_exists( 'get_plugin_updates' ) ) {
        return array();
    }
    $version_latest = $data['Version'];
    return array(
        'plugin'            => $plugin,
        'name'              => $data['Name'],
        'version'           => $data['Version'],
        'version_latest'    => $version_latest,
        'url'               => $data['PluginURI'],
        'author_name'       => $data['AuthorName'],
        'author_url'        => esc_url_raw( $data['AuthorURI'] ),
        'network_activated' => $data['Network'],
    );
}

/**
 * Get a list of Dropins and MU plugins.
 *
 * @since 3.6.0
 * @return array
 */
function get_dropins_mu_plugins()
{
    $dropins = get_dropins();
    $plugins = array(
        'dropins'    => array(),
        'mu_plugins' => array(),
    );
    foreach ( $dropins as $key => $dropin ) {
        $plugins['dropins'][] = array(
            'plugin' => $key,
            'name'   => $dropin['Name'],
        );
    }
    $mu_plugins = get_mu_plugins();
    foreach ( $mu_plugins as $plugin => $mu_plugin ) {
        $plugins['mu_plugins'][] = array(
            'plugin'      => $plugin,
            'name'        => $mu_plugin['Name'],
            'version'     => $mu_plugin['Version'],
            'url'         => $mu_plugin['PluginURI'],
            'author_name' => $mu_plugin['AuthorName'],
            'author_url'  => esc_url_raw( $mu_plugin['AuthorURI'] ),
        );
    }
    return $plugins;
}

/**
 * Get all the posts of a custom post type, optional orderby and order
 * TODO
 *
 * @since 1.0.4
 * @param string $card_oracle_cpt Name of CPT.
 * @param string $order_by (optional) Order by field.
 * @param string $order (optional) Order direction ASC or DESC.
 * @param array  $meta Meta key and values to search on.
 * @param string $numberposts Number of posts to get, -1 all.
 * @param string $offset Starting with the offset post.
 * @return array An array of all custom post_types requested.
 */
function get_card_oracle_posts_by_cpt(
    $card_oracle_cpt,
    $order_by = 'ID',
    $order = 'ASC',
    $meta = array(),
    $numberposts = -1,
    $offset = 0
)
{
    $args = array(
        'numberposts' => $numberposts,
        'offset'      => $offset,
        'order'       => $order,
        'orderby'     => $order_by,
        'post_status' => 'publish',
        'post_type'   => $card_oracle_cpt,
        'meta_query'  => $meta,
    );
    return get_posts( $args );
}

/**
 * Should the reading be processed by the payment provider.
 *
 * @return boolean
 */
function card_oracle_process_order()
{
    global  $payment_options ;
    $process_order = false;
    // if the price is set (not zero) verify the fields are set for the selected payment provider.
    if ( isset( $payment_options['price'] ) && 0 < $payment_options['price'] ) {
        switch ( $payment_options['provider'] ) {
            case 'paypal':
                // If the paypal email field is blank return false otherwise return true.
                $process_order = isset( $payment_options['paypal_email'] );
                break;
            case 'stripe':
                // If either of the secret or publishable keys are blank return false otherwise true.
                $process_order = isset( $payment_options['stripe_secret'] ) && isset( $payment_options['stripe_publishable'] );
                break;
            default:
                break;
        }
    }
    return $process_order;
}

/**
 * Creates html for the specific reading layout.
 *
 * @param array  $card_ids array of cards to display.
 * @param int    $card_count number of cards in array.
 * @param string $card_back_url url for back of card.
 * @param string $layout type of layout for the reading.
 *
 * @return string $output HTML output for layout.
 */
function card_oracle_layout_html(
    $card_ids,
    $card_count,
    $card_back_url,
    $layout
)
{
    switch ( $layout ) {
        case 'circular':
            $output = '<div class="container"><div class="tarot-deck-circle"><ul class="tarot-spread-circle">';
            $angle = 360 / $card_count;
            for ( $i = 0 ;  $i < $card_count ;  $i++ ) {
                $image_class = 'card-oracle-image-hidden';
                $reversed = ( 1 === $card_ids[$i]['Upright'] ? '' : 'true' );
                if ( $reversed ) {
                    $image_class .= ' card-oracle-rotate-image';
                }
                $output .= sprintf(
                    '<li style="z-index: %1$d; transform: translate(0px) rotate(%2$ddeg);"><img style="z-index: %1$d;" id="back%3$s" src="%4$s" loading="lazy" data-value="%3$s" data-reversed="%5$s" alt="back of card" ><img style="z-index: %6$d;" id="card%3$s" class="%8$s" src="%7$s" loading="lazy" alt="card"></li>',
                    esc_attr( $i + 1000 ),
                    esc_attr( $i * $angle ),
                    esc_attr( $card_ids[$i]['ID'] ),
                    esc_url( $card_back_url ),
                    esc_attr( $reversed ),
                    esc_attr( $i + 2000 ),
                    esc_url( $card_ids[$i]['Image'] ),
                    esc_attr( $image_class )
                );
            }
            $output .= '</ul></div></div>';
            break;
        case 'overlapping':
            $output = '<div class="container"><div class="tarot-deck-overlap"><ul>';
            for ( $i = 0 ;  $i < $card_count ;  $i++ ) {
                $image_class = 'card-oracle-image-hidden';
                $reversed = ( 1 === $card_ids[$i]['Upright'] ? '' : 'true' );
                if ( $reversed ) {
                    $image_class .= ' card-oracle-rotate-image';
                }
                $output .= sprintf(
                    '<li><img id="back%1$s" src="%2$s" loading="lazy" data-value="%1$s" data-reversed="%3$s" alt="back of card"><img id="card%1$s" class="%5$s" src="%4$s" loading="lazy" alt="card"></li>',
                    esc_attr( $card_ids[$i]['ID'] ),
                    esc_url( $card_back_url ),
                    esc_attr( $reversed ),
                    esc_url( $card_ids[$i]['Image'] ),
                    esc_attr( $image_class )
                );
            }
            $output .= '</ul></div></div>';
            break;
        case 'spread':
            $output = '<div class="container"><div class="tarot-deck-overlap"><ul>';
            $angle = 40 / $card_count;
            for ( $i = 0 ;  $i < $card_count ;  $i++ ) {
                $rotate = -20 + $i * $angle;
                $image_class = 'card-oracle-image-hidden';
                $reversed = ( 1 === $card_ids[$i]['Upright'] ? '' : 'true' );
                if ( $reversed ) {
                    $image_class .= ' card-oracle-rotate-image';
                }
                $output .= sprintf(
                    '<li style="transform: translate(0px) rotate(%1$ddeg);"><img id="back%2$s" src="%3$s" loading="lazy" data-value="%2$s" data-reversed="%4$s" alt="back of card"><img id="card%2$s" class="%6$s" src="%5$s" loading="lazy" alt="card"></li>',
                    esc_attr( $rotate ),
                    esc_attr( $card_ids[$i]['ID'] ),
                    esc_url( $card_back_url ),
                    esc_attr( $reversed ),
                    esc_url( $card_ids[$i]['Image'] ),
                    esc_attr( $image_class )
                );
            }
            $output .= '</ul></div></div>';
            break;
        default:
            $output = '<div class="card-oracle-cards">';
            for ( $i = 0 ;  $i < $card_count ;  $i++ ) {
                $output .= '<div class="card-oracle-card"><div class="card-oracle-card-body"><div class="card-oracle-back">';
                // Set the data-value and the class based on whether is card is right side up or upside down.
                
                if ( 1 === $card_ids[$i]['Upright'] ) {
                    $reversed = '';
                    $class = 'card-oracle-front';
                } else {
                    $reversed = $card_ids[$i]['ID'];
                    $class = 'card-oracle-front-reverse';
                }
                
                $output .= sprintf(
                    '<button type="button" value="%1$s" id="btn%1$s" data-value="%2$s" onclick="this.disabled = true;" class="btn card-oracle-btn clicked"><img src="%3$s" loading="lazy" alt="back of card"/></button></div><div class="%4$s">',
                    esc_attr( $card_ids[$i]['ID'] ),
                    esc_attr( $reversed ),
                    esc_url( $card_back_url ),
                    esc_attr( $class )
                );
                $output .= get_the_post_thumbnail( $card_ids[$i]['ID'] );
                $output .= '</div></div></div>';
            }
            $output .= '</div>';
            break;
    }
    return $output;
}
