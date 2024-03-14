<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Houzez Property Feed Cron Functions
 */
class Houzez_Property_Feed_Cron {

	public function __construct() {

        add_action( 'admin_init', array( $this, 'check_for_manually_run_import') );
        add_action( 'admin_init', array( $this, 'check_for_manually_run_export') );

        add_action( 'admin_init', array( $this, 'check_cron_is_scheduled'), 99 );

        add_filter( 'cron_schedules', array( $this, 'custom_cron_recurrence' ) );

        add_action( 'houzezpropertyfeedcronhook', array( $this, 'execute_import_cron' ) );
        add_action( 'houzezpropertyfeedcronhook', array( $this, 'execute_export_cron' ) );
	}

    /**
     * Check for cron being ran manually via 'Run Now' button
     */
    public function check_for_manually_run_import() 
    {
        if ( isset($_GET['custom_property_import_cron']) && sanitize_text_field($_GET['custom_property_import_cron']) == 'houzezpropertyfeedcronhook' )
        {
            if ( !isset($_GET['force']) )
            {
                global $wpdb;

                $options = get_option( 'houzez_property_feed', array() );
                $imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();

                foreach ( $imports as $import_id => $import_settings )
                {
                    if ( !isset($import_settings['running']) || ( isset($import_settings['running']) && $import_settings['running'] !== true ) )
                    {
                        continue;
                    }

                    if ( isset($import_settings['deleted']) && $import_settings['deleted'] === true )
                    {
                        continue;
                    }

                    // Make sure there's been no activity in the logs for at least 5 minutes for this feed as that indicates there's possible a feed running
                    $row = $wpdb->get_row( "
                        SELECT 
                            log_date
                        FROM 
                            " . $wpdb->prefix . "houzez_property_feed_logs_instance
                        INNER JOIN " .$wpdb->prefix . "houzez_property_feed_logs_instance_log ON " . $wpdb->prefix . "houzez_property_feed_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_logs_instance_log.instance_id
                        WHERE
                            import_id = '" . $import_id . "'
                        AND
                            end_date = '0000-00-00 00:00:00'
                        ORDER BY log_date DESC
                        LIMIT 1
                    ", ARRAY_A);

                    if ( null !== $row )
                    {
                        if ( ( ( time() - strtotime($row['log_date']) ) / 60 ) < 5 )
                        {
                            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpferrormessage=' . __( "There has been activity within the past 5 minutes on an unfinished import. To prevent multiple imports running at the same time and possible duplicate properties being created we won't currently allow manual execution. Please try again in a few minutes or check the logs to see the status of the current import.", 'houzezpropertyfeed' ) ) );
                            die();
                        }
                    }
                }
            }

            do_action(sanitize_text_field($_GET['custom_property_import_cron']));

            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-import&hpfsuccessmessage=' . __( 'Import executed successfully. You can check the logs to see what happened during the import.', 'houzezpropertyfeed' ) ) );
            die();
        }
    }

    /**
     * Check for cron being ran manually via 'Run Now' button
     */
    public function check_for_manually_run_export() 
    {
        if ( isset($_GET['custom_property_export_cron']) && sanitize_text_field($_GET['custom_property_export_cron']) == 'houzezpropertyfeedcronhook' )
        {
            if ( !isset($_GET['force']) )
            {
                global $wpdb;

                $options = get_option( 'houzez_property_feed', array() );
                $exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

                // remove any non-cron formats
                foreach ( $exports as $export_id => $export_settings  )
                {
                    $format = get_format_from_export_id( $export_id );
                    if ( isset($format['method']) && $format['method'] == 'cron' )
                    {

                    }
                    else
                    {
                        unset($exports[$export_id]);
                    }
                }

                foreach ( $exports as $export_id => $export_settings )
                {
                    if ( !isset($export_settings['running']) || ( isset($export_settings['running']) && $export_settings['running'] !== true ) )
                    {
                        continue;
                    }

                    if ( isset($export_settings['deleted']) && $export_settings['deleted'] === true )
                    {
                        continue;
                    }

                    // Make sure there's been no activity in the logs for at least 5 minutes for this feed as that indicates there's possible a feed running
                    $row = $wpdb->get_row( "
                        SELECT 
                            log_date
                        FROM 
                            " . $wpdb->prefix . "houzez_property_feed_export_logs_instance
                        INNER JOIN " .$wpdb->prefix . "houzez_property_feed_export_logs_instance_log ON " . $wpdb->prefix . "houzez_property_feed_export_logs_instance.id = " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log.instance_id
                        WHERE
                            export_id = '" . $export_id . "'
                        AND
                            end_date = '0000-00-00 00:00:00'
                        ORDER BY log_date DESC
                        LIMIT 1
                    ", ARRAY_A);

                    if ( null !== $row )
                    {
                        if ( ( ( time() - strtotime($row['log_date']) ) / 60 ) < 5 )
                        {
                            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpferrormessage=' . __( "There has been activity within the past 5 minutes on an unfinished export. Please try again in a few minutes or check the logs to see the status of the current export.", 'houzezpropertyfeed' ) ) );
                            die();
                        }
                    }
                }
            }

            do_action(sanitize_text_field($_GET['custom_property_export_cron']));

            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpfsuccessmessage=' . __( 'Export executed successfully. You can check the logs to see what happened during the export.', 'houzezpropertyfeed' ) ) );
            die();
        }

        if ( isset($_GET['custom_property_export_cron']) && sanitize_text_field($_GET['custom_property_export_cron']) == 'houzezpropertyfeedreconcilecronhook' )
        {
            do_action(sanitize_text_field($_GET['custom_property_export_cron']));

            wp_redirect( admin_url( 'admin.php?page=houzez-property-feed-export&hpfsuccessmessage=' . __( 'Reconcilliation executed successfully. You can check the logs to see what happened during the reconcilliation.', 'houzezpropertyfeed' ) ) );
            die();
        }
    }

    public function check_cron_is_scheduled()
    {
        $schedule = wp_get_schedule( 'houzezpropertyfeedcronhook' );

        if ( $schedule === FALSE )
        {
            // Hmm... cron job not found. Let's set it up
            $timestamp = wp_next_scheduled( 'houzezpropertyfeedcronhook' );
            wp_unschedule_event($timestamp, 'houzezpropertyfeedcronhook' );
            wp_clear_scheduled_hook('houzezpropertyfeedcronhook');
            
            $next_schedule = time() - 60;
            wp_schedule_event( $next_schedule, apply_filters( 'houzez_property_feed_cron_frequency', 'every_five_minutes' ), 'houzezpropertyfeedcronhook' );
        }
    }

    public function custom_cron_recurrence( $schedules ) 
    {
        $schedules['every_five_minutes'] = array(
            'interval'  => 300,
            'display'   => __( 'Every 5 Minutes', 'houzezpropertyfeed' )
        );
         
        return $schedules;
    }

    public function execute_import_cron( $args = array(), $assoc_args = array() )
    {
        require( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/cron-import.php' );
    }

    public function execute_export_cron( $args = array(), $assoc_args = array() )
    {
        require( dirname(HOUZEZ_PROPERTY_FEED_PLUGIN_FILE) . '/cron-export.php' );
    }
}

new Houzez_Property_Feed_Cron();