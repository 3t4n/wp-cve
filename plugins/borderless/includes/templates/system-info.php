<?php

// don't load directly
defined( 'ABSPATH' ) || exit;


/*-----------------------------------------------------------------------------------*/
/*  *.  Borderless - System Info
/*-----------------------------------------------------------------------------------*/

function Borderless_System_Info() { ?>

<div class="wrap borderless-system-info">

<table class="widefat" cellspacing="0">
    <thead>
        <tr>
            <th colspan="2"><b><?php _e( 'WordPress Environment', 'borderless' ); ?></b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php _e( 'Home URL', 'borderless' ); ?>:</td>
            <td><?php form_option( 'home' ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Site URL', 'borderless' ); ?>:</td>
            <td><?php form_option( 'siteurl' ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'WP Version', 'borderless' ); ?>:</td>
            <td><?php bloginfo( 'version' ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'WP Multisite', 'borderless' ); ?>:</td>
            <td><?php
                if ( is_multisite() )
                    echo '&#10004;';
                else
                    echo '&ndash;';
                ?></td>
        </tr>
        <tr>
            <td><?php _e( 'WP Memory Limit', 'borderless' ); ?>:</td>
            <td><?php  //echo "kok";
                $memory_limit = wp_convert_hr_to_bytes( WP_MEMORY_LIMIT );
                if ($memory_limit < 67108864) {
                    echo '<mark>' . sprintf( __( '%1$s - We recommend setting wp memory at least 64MB.</mark> See: <a href="%2$s" target="_blank">Increasing WP Memory Limit</a>', 'borderless' ), size_format($memory_limit), 'https://visualmodo.com/fix-maximum-upload-php-memory-limit-wordpress/' );
                    } else {
                    echo size_format( $memory_limit );
                }
                ?></td>
        </tr>
        <tr>
            <td><?php _e( 'WP Path', 'borderless' ); ?>:</td>
            <td><?php echo ABSPATH; ?></td>
        </tr>


        <tr>
            <td><?php _e( 'WP Debug Mode', 'borderless' ); ?>:</td>
            <td><?php
                if ( defined( 'WP_DEBUG' ) && WP_DEBUG )
                    echo '&#10004;';
                else
                    echo '&ndash;';
                ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Language', 'borderless' ); ?>:</td>
            <td><?php echo get_locale() ?></td>
        </tr>
    </tbody>
</table>
<br>
<table class="widefat" cellspacing="0">
    <thead>
        <tr>
            <th colspan="2" data-export-label="Server Environment"><b><?php _e( 'Server Environment', 'borderless' ); ?></b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php _e( 'Server Info', 'borderless' ); ?>:</td>
            <td><?php echo esc_html( $_SERVER[ 'SERVER_SOFTWARE' ] ); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'PHP Version', 'borderless' ); ?>:</td>
            <td><?php
                // Check if phpversion function exists
                if ( function_exists( 'phpversion' ) ) {
                    $php_version = phpversion();

                    echo esc_html( $php_version );
                } else {
                    _e( "Couldn't determine PHP version because phpversion() doesn't exist.", 'borderless' );
                }
                ?></td>
        </tr>
        <?php if ( function_exists( 'ini_get' ) ) : ?>
            <tr>
                <td><?php _e( 'PHP Memory Limit', 'borderless' ); ?>:</td>
                <td><?php echo size_format( wp_convert_hr_to_bytes( ini_get( 'memory_limit' ) ) ); ?></td>
            </tr>
            <tr>
                <td><?php _e( 'PHP Post Max Size', 'borderless' ); ?>:</td>
                <td><?php echo size_format( wp_convert_hr_to_bytes( ini_get( 'post_max_size' ) ) ); ?></td>
            </tr>
            <tr>
                <td ><?php _e( 'PHP Time Limit', 'borderless' ); ?>:</td>
                <td>
                <?php
                $time_limit = ini_get('max_execution_time'); 
                if ( $time_limit < 120 && $time_limit != 0 ) {
                    echo '<mark>' . sprintf( __( '%s - We recommend setting max execution time at least 300.</mark> See: <a href="%2$s" target="_blank">Increasing WP Time Limit</a>', 'borderless' ), $time_limit, 'https://visualmodo.com/fix-maximum-upload-php-memory-limit-wordpress/' );
                } else {
                    echo $time_limit;
                }
                ?></td>
            </tr>
            <tr>
                <td><?php _e( 'PHP Max Input Vars', 'borderless' ); ?>:</td>
                <td><?php echo ini_get( 'max_input_vars' ); ?></td>
            </tr>
            <tr>
                <td ><?php _e( 'SUHOSIN Installed', 'borderless' ); ?>:</td>
                <td><?php echo extension_loaded( 'suhosin' ) ? '&#10004;' : '&ndash;'; ?></td>
            </tr>
        <?php endif; ?>
        <tr>
            <td><?php _e( 'MySQL Version', 'borderless' ); ?>:</td>
            <td>
                <?php
                /** @global wpdb $wpdb */
                global $wpdb;
                echo $wpdb->db_version();
                ?>
            </td>
        </tr>
        <tr>
            <td><?php _e( 'Max Upload Size', 'borderless' ); ?>:</td>
        <td><?php echo size_format( wp_max_upload_size() ); ?></td>
        </tr>
        </tbody>
</table>
<br>
<table class="widefat" cellspacing="0">
    <thead>
        <tr>
            <th colspan="2" data-export-label="PHP Extensions"><b><?php _e( 'PHP Extensions', 'borderless' ); ?></b></th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><?php _e( 'cURL', 'borderless' ); ?>:</td>
            <td><?php echo (function_exists('curl_init') ? 'Supported' : 'Not Supported'); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'fsockopen', 'borderless' ); ?>:</td>
            <td><?php echo (function_exists('fsockopen') ? 'Supported' : 'Not Supported'); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'SOAP Client', 'borderless' ); ?>:</td>
            <td><?php echo (class_exists('SoapClient') ? 'Installed' : 'Not Installed'); ?></td>
        </tr>
        <tr>
            <td><?php _e( 'Suhosin', 'borderless' ); ?>:</td>
            <td><?php echo (extension_loaded('suhosin') ? 'Installed' : 'Not Installed'); ?></td>
        </tr>
    </tbody>
</table>
<br>
<table class="widefat" cellspacing="0">
    <thead>
        <tr>
            <th colspan="2"><b><?php _e( 'Active Plugins', 'borderless' ); ?> (<?php echo count( (array) get_option( 'active_plugins' ) ); ?>)</b></th>
        </tr>
    </thead>
    <tbody>
        <?php

        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $network_activated_plugins = array_keys( get_site_option( 'active_sitewide_plugins', array() ) );
            $active_plugins            = array_merge( $active_plugins, $network_activated_plugins );
        }

        foreach ( $active_plugins as $plugin ) {

            $plugin_data    = @get_plugin_data( WP_PLUGIN_DIR . '/' . $plugin );
            $dirname        = dirname( $plugin );
            $version_string = '';
            $network_string = '';

            if ( ! empty( $plugin_data[ 'Name' ] ) ) {

                // link the plugin name to the plugin url if available
                $plugin_name = esc_html( $plugin_data[ 'Name' ] );

                if ( !empty( $plugin_data[ 'PluginURI' ] ) ) {
                    $plugin_name = '<a href="' . esc_url( $plugin_data[ 'PluginURI' ] ) . '" title="' . esc_attr__( 'Visit plugin homepage', 'borderless' ) . '" target="_blank">' . $plugin_name . '</a>';
                }
                ?>
                <tr>
                    <td><?php echo $plugin_name; ?></td>
                    <td><?php echo sprintf( _x( 'by %s', 'by author', 'borderless' ), $plugin_data[ 'Author' ] ) . ' &ndash; ' . esc_html( $plugin_data[ 'Version' ] ) . $version_string . $network_string; ?></td>
                </tr>
                <?php
            }
        }
        ?>
    </tbody>
</table>
</div>

<?php } ?>