<?php

register_shutdown_function( "houzez_property_feed_export_fatal_handler" );
function houzez_property_feed_export_fatal_handler() {

    $error = error_get_last();

    if ($error !== NULL) 
    {
    	if ( ($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)|| ($error['type'] === E_USER_NOTICE) ) 
    	{
	        $errno   = $error["type"];
	        $errfile = $error["file"];
	        $errline = $error["line"];
	        $errstr  = $error["message"];

			$error_text = houzez_property_feed_export_format_error( $errno, $errstr, $errfile, $errline );

			global $wpdb, $instance_id;

			$current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
			$current_date = $current_date->format("Y-m-d H:i:s");

			$wpdb->insert(
				$wpdb->prefix . "houzez_property_feed_export_logs_instance_log",
				array(
					'instance_id' => $instance_id,
					'post_id' => 0,
					'severity' => 1,
					'entry' => $error_text,
					'log_date' => $current_date
				)
			);
		}
    }
}

// Returns a formatted version of the fatal error, showing the error message and number, filename and line number
function houzez_property_feed_export_format_error( $errno, $errstr, $errfile, $errline ) {
	$trace = print_r( debug_backtrace( false ), true );
	$file_split = explode('/', $errfile);
	$trimmed_filename = implode('/', array_slice($file_split, -2));
	$content = 'Error:' . $errstr . '|' . $errno . '|' . $trimmed_filename . '|' . $errline . '|' . $trace;
	return $content;
}

error_reporting( 0 );

if ( !defined('HPF_EXPORT') )
{
    define( "HPF_EXPORT", true );
}

$instance_id = 0;

global $wpdb, $post, $instance_id;

$keep_logs_days = (string)apply_filters( 'houzez_property_feed_keep_logs_days', '1' );

// Revert back to 1 days if anything other than numbers has been passed
// This prevent SQL injection and errors
if ( !preg_match("/^\d+$/", $keep_logs_days) )
{
    $keep_logs_days = '1';
}

// Delete logs older than 1 days
$wpdb->query( "DELETE FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance WHERE start_date < DATE_SUB(NOW(), INTERVAL " . $keep_logs_days . " DAY)" );
$wpdb->query( "DELETE FROM " . $wpdb->prefix . "houzez_property_feed_export_logs_instance_log WHERE log_date < DATE_SUB(NOW(), INTERVAL " . $keep_logs_days . " DAY)" );

$options = get_option( 'houzez_property_feed', array() );
$exports = ( isset($options['exports']) && is_array($options['exports']) && !empty($options['exports']) ) ? $options['exports'] : array();

if ( is_array($exports) && !empty($exports) )
{
	// remove any non-cron formats
	foreach ( $exports as $export_id => $export_settings  )
	{
		$format = get_format_from_export_id( $export_id );
		if ( isset($format['method']) && ( $format['method'] == 'cron' || $format['method'] == 'url' ) )
		{
            if ( isset($_GET['preview']) && $export_id != (int)$_GET['preview'] )
            {
                unset($exports[$export_id]);
            }
		}
		else
		{
            //remove real-time feeds from being processed
			unset($exports[$export_id]);
		}
	}

	if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true )
    {
    	// Sort exports into random order. If timing out is an issue this can ensure they all get executed fairly (or as fairly as random allows)
    	$shuffled_export_array = array();
    	$export_id_keys = array_keys($exports);

    	shuffle($export_id_keys);

    	foreach( $export_id_keys as $export_id_key )
    	{
	    	$shuffled_export_array[$export_id_key] = $exports[$export_id_key];
    	}

    	$exports = $shuffled_export_array;
    }
    else
    {
        // ensure only one export if pro not active
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

            $exports = array( $export_id => $export_settings );
            break;
		}
    }

    $frequencies = get_houzez_property_feed_export_frequencies();

	foreach ( $exports as $export_id => $export_settings )
	{
    	$ok_to_run_export = true;

    	if ( !isset($export_settings['running']) || ( isset($export_settings['running']) && $export_settings['running'] !== true ) )
        {
        	$ok_to_run_export = false;
        	continue;
        }

        if ( isset($export_settings['deleted']) && $export_settings['deleted'] === true )
        {
        	$ok_to_run_export = false;
        	continue;
        }

        // ensure frequency is not a PRO one if PRO not enabled
        if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true )
        {
            if ( isset($frequencies[$export_settings['frequency']]['pro']) && $frequencies[$export_settings['frequency']]['pro'] === true )
            {
                $export_settings['frequency'] = 'daily';
            }
        }

        if ( !isset($_GET['force']) && !isset($_GET['preview']) )
    	{
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
                	$ok_to_run_export = false;

                	$message = "There has been activity within the past 5 minutes on an unfinished export. Please try again in a few minutes or check the logs to see the status of the current export.";
                	
                	// if we're running it manually. Needs to be presented nicer
		            if ( isset($_GET['custom_property_export_cron']) )
		            {
		            	echo $message; die();
		            }

                	continue;
                }
            }
        }

        if ( isset($_GET['custom_property_export_cron']) )
        {

        }
        else
        {
            // Work out if we need to send this portal by looking
            // at the send frequency and the last date sent
            $last_start_date = '2000-01-01 00:00:00';
            $row = $wpdb->get_row( "
                SELECT 
                    start_date
                FROM 
                    " .$wpdb->prefix . "houzez_property_feed_export_logs_instance
                WHERE
                    export_id = '" . $export_id . "'
                ORDER BY start_date DESC LIMIT 1
            ", ARRAY_A);
            if ( null !== $row )
            {
                $last_start_date = $row['start_date'];   
            }

            $diff_secs = time() - strtotime($last_start_date);

            switch ($export_settings['frequency'])
            {
                case "hourly":
                {
                    if (($diff_secs / 60 / 60) < 1)
                    {
                        $ok_to_run_export = false;
                    }
                    break;
                }
                case "twicedaily":
                {
                    if (($diff_secs / 60 / 60) < 12)
                    {
                        $ok_to_run_export = false;
                    }
                    break;
                }
                default: // daily
                {
                    if (($diff_secs / 60 / 60) < 24)
                    {
                        $ok_to_run_export = false;
                    }
                }
            }
        }

        if ($ok_to_run_export)
        {
            $instance_id = '';

            if ( !isset($_GET['preview']) )
            {
                // log instance start
                $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
    			$current_date = $current_date->format("Y-m-d H:i:s");

                $wpdb->insert( 
                    $wpdb->prefix . "houzez_property_feed_export_logs_instance", 
                    array(
                    	'export_id' => $export_id,
                        'start_date' => $current_date
                    )
                );
                $instance_id = $wpdb->insert_id;
            }

	    	$format = $export_settings['format'];

	    	$parsed_in_class = false;

	    	switch ($format)
	    	{
	    		case "blm":
	    		{
	                // includes
                    require_once dirname( __FILE__ ) . '/includes/export-formats/class-houzez-property-feed-format-blm.php';

					$export_object = new Houzez_Property_Feed_Format_Blm( $instance_id, $export_id );

					$exported = $export_object->export();

	    			break;
	    		}
                case "facebook":
                {
                    // includes
                    require_once dirname( __FILE__ ) . '/includes/export-formats/class-houzez-property-feed-format-facebook.php';

                    $export_object = new Houzez_Property_Feed_Format_Facebook( $instance_id, $export_id );

                    $exported = $export_object->export();

                    break;
                }
                case "idealista":
                {
                    // includes
                    require_once dirname( __FILE__ ) . '/includes/export-formats/class-houzez-property-feed-format-idealista.php';

                    $export_object = new Houzez_Property_Feed_Format_Idealista( $instance_id, $export_id );

                    $exported = $export_object->export();

                    break;
                }
                case "kyero":
                {
                    // includes
                    require_once dirname( __FILE__ ) . '/includes/export-formats/class-houzez-property-feed-format-kyero.php';

                    $export_object = new Houzez_Property_Feed_Format_Kyero( $instance_id, $export_id );

                    $exported = $export_object->export();

                    break;
                }
                case "thribee":
                {
                    // includes
                    require_once dirname( __FILE__ ) . '/includes/export-formats/class-houzez-property-feed-format-thribee.php';

                    $export_object = new Houzez_Property_Feed_Format_Thribee( $instance_id, $export_id );

                    $exported = $export_object->export();

                    break;
                }
	    	}

            if ( !empty($instance_id) )
            {
                // log instance end
                $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
                $current_date = $current_date->format("Y-m-d H:i:s");

                $wpdb->update( 
                    $wpdb->prefix . "houzez_property_feed_export_logs_instance", 
                    array( 
                        'end_date' => $current_date
                    ),
                    array( 'id' => $instance_id )
                );

                do_action( 'houzez_property_feed_cron_end', $instance_id, $export_id );
                do_action( 'houzez_property_feed_export_cron_end', $instance_id, $export_id );
            }
	    }
    }
}