<?php

register_shutdown_function( "houzez_property_feed_import_fatal_handler" );
function houzez_property_feed_import_fatal_handler() {

    $error = error_get_last();

    if ($error !== NULL) 
    {
    	if ( ($error['type'] === E_ERROR) || ($error['type'] === E_USER_ERROR)|| ($error['type'] === E_USER_NOTICE) ) 
    	{
	        $errno   = $error["type"];
	        $errfile = $error["file"];
	        $errline = $error["line"];
	        $errstr  = $error["message"];

			$error_text = houzez_property_feed_import_format_error( $errno, $errstr, $errfile, $errline );

			global $wpdb, $instance_id;

			$current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
			$current_date = $current_date->format("Y-m-d H:i:s");

			$wpdb->insert(
				$wpdb->prefix . "houzez_property_feed_logs_instance_log",
				array(
					'instance_id' => $instance_id,
					'post_id' => 0,
					'crm_id' => '',
					'severity' => 1,
					'entry' => $error_text,
					'log_date' => $current_date
				)
			);
		}
    }
}

// Returns a formatted version of the fatal error, showing the error message and number, filename and line number
function houzez_property_feed_import_format_error( $errno, $errstr, $errfile, $errline ) {
	$trace = print_r( debug_backtrace( false ), true );
	$file_split = explode('/', $errfile);
	$trimmed_filename = implode('/', array_slice($file_split, -2));
	$content = 'Error:' . $errstr . '|' . $errno . '|' . $trimmed_filename . '|' . $errline . '|' . $trace;
	return $content;
}

error_reporting( 0 );

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
$wpdb->query( "DELETE FROM " . $wpdb->prefix . "houzez_property_feed_logs_instance WHERE start_date < DATE_SUB(NOW(), INTERVAL " . $keep_logs_days . " DAY)" );
$wpdb->query( "DELETE FROM " . $wpdb->prefix . "houzez_property_feed_logs_instance_log WHERE log_date < DATE_SUB(NOW(), INTERVAL " . $keep_logs_days . " DAY)" );

$options = get_option( 'houzez_property_feed', array() );
$imports = ( isset($options['imports']) && is_array($options['imports']) && !empty($options['imports']) ) ? $options['imports'] : array();

if ( is_array($imports) && !empty($imports) )
{
    $wp_upload_dir = wp_upload_dir();
    $uploads_dir_ok = true;
    if ( $wp_upload_dir['error'] !== FALSE )
    {
        echo "Unable to create uploads folder. Please check permissions";
        $uploads_dir_ok = false;
    }
    else
    {
        $uploads_dir = $wp_upload_dir['basedir'] . '/houzez_property_feed_import/';

        if ( ! @file_exists($uploads_dir) )
        {
            if ( ! @mkdir($uploads_dir) )
            {
                echo "Unable to create directory " . $uploads_dir;
                $uploads_dir_ok = false;
            }
        }
        else
        {
            if ( ! @is_writeable($uploads_dir) )
            {
                echo "Directory " . $uploads_dir . " isn't writeable";
                $uploads_dir_ok = false;
            }
        }
    }

    if ( $uploads_dir_ok )
    {
    	if ( apply_filters( 'houzez_property_feed_pro_active', false ) === true )
        {
	    	// Sort imports into random order. If timing out is an issue this can ensure they all get executed fairly (or as fairly as random allows)
	    	$shuffled_import_array = array();
	    	$import_id_keys = array_keys($imports);

	    	shuffle($import_id_keys);

	    	foreach( $import_id_keys as $import_id_key )
	    	{
		    	$shuffled_import_array[$import_id_key] = $imports[$import_id_key];
	    	}

	    	$imports = $shuffled_import_array;
	    }
	    else
	    {
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

	            $imports = array( $import_id => $import_settings );
	            break;
    		}
	    }

    	$frequencies = get_houzez_property_feed_import_frequencies();

    	foreach ( $imports as $import_id => $import_settings )
    	{
	    	$ok_to_run_import = true;

	    	if ( !isset($import_settings['running']) || ( isset($import_settings['running']) && $import_settings['running'] !== true ) )
            {
            	$ok_to_run_import = false;
            	continue;
            }

            if ( isset($import_settings['deleted']) && $import_settings['deleted'] === true )
            {
            	$ok_to_run_import = false;
            	continue;
            }

            // ensure frequency is not a PRO one if PRO not enabled
            if ( apply_filters( 'houzez_property_feed_pro_active', false ) !== true )
            {
                if ( isset($frequencies[$import_settings['frequency']]['pro']) && $frequencies[$import_settings['frequency']]['pro'] === true )
                {
                    $import_settings['frequency'] = 'daily';
                }
            }

        	if ( !isset($_GET['force']) )
        	{
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
	                	$ok_to_run_import = false;

	                	$message = "There has been activity within the past 5 minutes on an unfinished import. To prevent multiple imports running at the same time and possible duplicate properties being created we won't currently allow manual execution. Please try again in a few minutes or check the logs to see the status of the current import.";
	                	
	                	// if we're running it manually. Needs to be presented nicer
			            if ( isset($_GET['custom_property_import_cron']) )
			            {
			            	echo $message; die();
			            }

	                	continue;
	                }
	            }
	        }

            if ( isset($_GET['custom_property_import_cron']) )
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
	                    " .$wpdb->prefix . "houzez_property_feed_logs_instance
	                WHERE
	                    import_id = '" . $import_id . "'
	                ORDER BY start_date DESC LIMIT 1
	            ", ARRAY_A);
	            if ( null !== $row )
	            {
	                $last_start_date = $row['start_date'];   
	            }

	            $diff_secs = time() - strtotime($last_start_date);

	            switch ($import_settings['frequency'])
	            {
	            	case "every_fifteen_minutes":
	                {
	                    if (($diff_secs / 60 / 60) < 0.25)
	                    {
	                        $ok_to_run_import = false;
	                    }
	                    break;
	                }
	                case "hourly":
	                {
	                    if (($diff_secs / 60 / 60) < 1)
	                    {
	                        $ok_to_run_import = false;
	                    }
	                    break;
	                }
	                case "twicedaily":
	                {
	                    if (($diff_secs / 60 / 60) < 12)
	                    {
	                        $ok_to_run_import = false;
	                    }
	                    break;
	                }
	                default: // daily
	                {
	                    if (($diff_secs / 60 / 60) < 24)
	                    {
	                        $ok_to_run_import = false;
	                    }
	                }
	            }
	        }

            if ($ok_to_run_import)
            {
	            // log instance start
	            $current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
				$current_date = $current_date->format("Y-m-d H:i:s");

	            $wpdb->insert( 
	                $wpdb->prefix . "houzez_property_feed_logs_instance", 
	                array(
	                	'import_id' => $import_id,
	                    'start_date' => $current_date
	                )
	            );
	            $instance_id = $wpdb->insert_id;

		    	$format = $import_settings['format'];

		    	$parsed_in_class = false;

		    	switch ($format)
		    	{
		    		case "10ninety":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-10ninety.php';

						$import_object = new Houzez_Property_Feed_Format_10ninety( $instance_id, $import_id );

		    			break;
		    		}
		    		case "acquaint":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-acquaint.php';

						$import_object = new Houzez_Property_Feed_Format_Acquaint( $instance_id, $import_id );

		    			break;
		    		}
		    		case "agentos":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-agentos.php';

						$import_object = new Houzez_Property_Feed_Format_Agentos( $instance_id, $import_id );

		    			break;
		    		}
		    		case "alto":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-alto.php';

						$import_object = new Houzez_Property_Feed_Format_Alto( $instance_id, $import_id );

		    			break;
		    		}
		    		case "apex27":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-apex27.php';

						$import_object = new Houzez_Property_Feed_Format_Apex27( $instance_id, $import_id );

		    			break;
		    		}
		    		case "bdp":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-bdp.php';

						$import_object = new Houzez_Property_Feed_Format_Bdp( $instance_id, $import_id );

		    			break;
		    		}
		    		case "blm_local":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-blm.php';

						$import_object = new Houzez_Property_Feed_Format_Blm( $instance_id, $import_id );

						$import_object->parse_and_import();

						$parsed_in_class = true;

		    			break;
		    		}
		    		case "csv":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-csv.php';

						$import_object = new Houzez_Property_Feed_Format_Csv( $instance_id, $import_id );

		    			break;
		    		}
		    		case "dezrez_rezi":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-dezrez-rezi.php';

						$import_object = new Houzez_Property_Feed_Format_Dezrez_Rezi( $instance_id, $import_id );

		    			break;
		    		}
		    		case "domus":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-domus.php';

						$import_object = new Houzez_Property_Feed_Format_Domus( $instance_id, $import_id );

		    			break;
		    		}
		    		case "expertagent":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-expertagent.php';

						$import_object = new Houzez_Property_Feed_Format_Expertagent( $instance_id, $import_id );

		    			break;
		    		}
		    		case "gnomen":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-gnomen.php';

						$import_object = new Houzez_Property_Feed_Format_Gnomen( $instance_id, $import_id );

		    			break;
		    		}
		    		case "inmobalia":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-inmobalia.php';

						$import_object = new Houzez_Property_Feed_Format_Inmobalia( $instance_id, $import_id );

		    			break;
		    		}
		    		case "jupix":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-jupix.php';

						$import_object = new Houzez_Property_Feed_Format_Jupix( $instance_id, $import_id );

		    			break;
		    		}
		    		case "kyero":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-kyero.php';

						$import_object = new Houzez_Property_Feed_Format_Kyero( $instance_id, $import_id );

		    			break;
		    		}
		    		case "loop":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-loop.php';

						$import_object = new Houzez_Property_Feed_Format_Loop( $instance_id, $import_id );

		    			break;
		    		}
		    		case "mri":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-mri.php';

						$import_object = new Houzez_Property_Feed_Format_Mri( $instance_id, $import_id );

		    			break;
		    		}
		    		case "pixxi":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-pixxi.php';

						$import_object = new Houzez_Property_Feed_Format_Pixxi( $instance_id, $import_id );

		    			break;
		    		}
		    		case "property_finder":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-property-finder.php';

						$import_object = new Houzez_Property_Feed_Format_Property_Finder( $instance_id, $import_id );

		    			break;
		    		}
		    		case "remax":
		    		{
		                // includes
		                require_once dirname( __FILE__ ) . '/includes/awsv4.php';
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-remax.php';

						$import_object = new Houzez_Property_Feed_Format_Remax( $instance_id, $import_id );

		    			break;
		    		}
		    		case "rentman":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-rentman.php';

						$import_object = new Houzez_Property_Feed_Format_Rentman( $instance_id, $import_id );

						$import_object->parse_and_import();

						$parsed_in_class = true;

		    			break;
		    		}
		    		case "resales_online":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-resales-online.php';

						$import_object = new Houzez_Property_Feed_Format_Resales_Online( $instance_id, $import_id );

		    			break;
		    		}
		    		case "rex":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-rex.php';

						$import_object = new Houzez_Property_Feed_Format_Rex( $instance_id, $import_id );

		    			break;
		    		}
		    		case "street":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-street.php';

						$import_object = new Houzez_Property_Feed_Format_Street( $instance_id, $import_id );

		    			break;
		    		}
		    		case "xml":
		    		{
		                // includes
                        require_once dirname( __FILE__ ) . '/includes/import-formats/class-houzez-property-feed-format-xml.php';

						$import_object = new Houzez_Property_Feed_Format_Xml( $instance_id, $import_id );

		    			break;
		    		}
		    		default:
		    		{
		    			$import_object = apply_filters( 'houzez_property_feed_import_object', $instance_id, $import_id );
		    		}
		    	}

		    	if ( !$parsed_in_class && isset($import_object) )
		    	{
			    	$parsed = $import_object->parse();

	                if ( $parsed !== FALSE )
	                {
	                    $import_object->import();

	                    $import_object->remove_old_properties();
	                }

	                unset($import_object);
	            }

		    	// log instance end
		    	$current_date = new DateTimeImmutable( 'now', new DateTimeZone('UTC') );
				$current_date = $current_date->format("Y-m-d H:i:s");

		    	$wpdb->update( 
		            $wpdb->prefix . "houzez_property_feed_logs_instance", 
		            array( 
		                'end_date' => $current_date
		            ),
		            array( 'id' => $instance_id )
		        );

		        do_action( 'houzez_property_feed_cron_end', $instance_id, $import_id );
		        do_action( 'houzez_property_feed_import_cron_end', $instance_id, $import_id );
	    	}
	    }
    }
}