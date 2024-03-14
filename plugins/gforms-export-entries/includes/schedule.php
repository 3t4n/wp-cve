<?php
function maybe_export_gf_entries_schedule( $args ) {
    $settings = get_option( 'gfee_settings', array() );
    $today = date( 'd' );
    gfee_log( '[===========================' );
    gfee_log( 'Maybe send monthly export?' );
    gfee_log( 'Today\'s day of the month: ' . $today );

    foreach( $settings['exports'] as $export=>$options ) {
        gfee_log( 'Maybe send monthly export for: ' . $export );
        gfee_log( 'Options: ' . print_r( $options, true ) );
        if ( 'gfee_monthly' === $options['gfee_schedule_frequency'] ) {
            $d = DateTime::createFromFormat(
                'm-d-y H:i:s',
                $options['schedule_start_date'] . '00:00:00',
                new DateTimeZone('EST')
            );
            if ( $d === false ) {
                $timestamp = false;
            } else {
                $timestamp = $d->getTimestamp();
            }
            $day_to_run = date( 'd', $timestamp );
            
            gfee_log( 'We run the monthly export for ' . $export . ' on day: ' . $day_to_run . ' - ' . $timestamp );
            if ( $day_to_run == $today ) {
                gfee_log( 'Today is the day We run the monthly export for ' . $export );
                export_gf_entries_schedule( $args );
            } else {
                gfee_log( 'Today is NOT the day We run the monthly export for ' . $export );
                gfee_log( 'Day to run: ' . $day_to_run );
            }
        } else {
            if ( ! empty( $options['gfee_schedule_frequency'] ) ) {
                gfee_log( 'We run the export for ' . $export );
                gfee_log( print_r( $args, true ) );
                export_gf_entries_schedule( $args );
            } else {
                gfee_log( 'No export scheduled' );
            }
        }//= end if ( 'gfee_monthly' === $options['gfee_schedule_frequency'] )
    } //= end foreach( $settings['exports'] as $export=>$options )
    gfee_log( '===========================]' );
}

function export_gf_entries_schedule( $args ) {
	$settings = get_option( 'gfee_settings', array() );

	if ( is_array( $args ) ) {
		$args = $args[0];
	}

	if ( $settings['exports'][$args]['gfee_schedule_frequency'] == 'daily' ) {
		$start = date( 'm-d-y', strtotime( '-1 days' ) );
	} else if ( $settings['exports'][$args]['gfee_schedule_frequency'] == 'weekly' ) {
		$start = date( 'm-d-y', strtotime( '-7 days' ) );
	} else if ( $settings['exports'][$args]['gfee_schedule_frequency'] == 'monthly' ) {
        $start = date( 'm-d-y', strtotime( '-1 month' ) );
	} else {
		$start = date( 'm-d-y', strtotime( '-1 days' ) );
	}

	$stop = date( 'm-d-y' );

	$file = gfee_generate_export( $args, $start, $stop );

	$admin_email = get_bloginfo( 'admin_email' );
    gfee_log( 'Admin email: ' . $admin_email );

	$site_name = get_bloginfo( 'name' ) . ' ' . get_bloginfo( 'url' );
    
    gfee_log( 'Blog Name: ' . $site_name );

	if ( isset( $settings['exports'][$args]['email_subject'] ) ) {
		$subject = $settings['exports'][$args]['email_subject'];
	} else {
		$subject = __( 'Form Entry Report for ', 'gforms-export-entries' ) . $site_name;
	}

	$subject = str_replace( '{site_name}', $site_name, $subject );
	$subject = do_shortcode( $subject );

	if ( isset( $settings['exports'][$args]['email_template'] ) ) {
		$body = html_entity_decode( $settings['exports'][$args]['email_template'] );
	} else {
		$body = __( 'Form Entry Report is attached for ', 'gforms-export-entries' ) . $site_name;
	}

	$body = str_replace( '{site_name}', $site_name, $body );
	$body = do_shortcode( $body );

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: multipart/mixed; charset=utf-8' . "\r\n";
	$headers .= 'From: ' . $site_name . ' <' . $admin_email . '>' . "\r\n";
    gfee_log( $headers );

	$attachments = array( $file );

	gfee_log( '++++++++++++++++++++++++++++++++++++' );
	gfee_log( 'Email attachment: ' . $file );
	if ( ! file_exists( $file ) ) {
		gfee_log( 'Email attachment does NOT exist: ' . $file );
	} else {
		gfee_log( 'Email attachment DOES exist: ' . $file );
	}

	$email_status = '';

	if ( strpos( $settings['exports'][$args]['email_to'], ',' ) !== false ) {
		$addresses = explode( ',', $settings['exports'][$args]['email_to'] );
		foreach( $addresses as $address ) {
			$address = trim( $address );
			$email_status = wp_mail( $address, $subject, $body, $headers, $attachments );
		}
	} else {
		$address = trim( $settings['exports'][$args]['email_to'] );
		$email_status = wp_mail( $address, $subject, $body, $headers, $attachments );
	}
	gfee_log( 'Email status: ' . $email_status );
	gfee_log( '++++++++++++++++++++++++++++++++++++' );
}

add_filter( 'wp_mail_content_type', 'gfee_set_content_type' );
function gfee_set_content_type( $content_type ) {
    return 'text/html';
}

add_filter( 'retrieve_password_message', 'gfee_custom_password_reset', 99, 4);
function gfee_custom_password_reset( $message, $key, $user_login, $user_data )    {
	$message = 'Someone has requested a password reset for the following account:

	' . sprintf(__('%s'), $user_data->user_email) . '

	If this was a mistake, just ignore this email and nothing will happen.

	To reset your password, visit the following address:

	<a href="' . network_site_url( 'wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login ), 'login' ) . '">
	' . network_site_url( 'wp-login.php?action=rp&key=' . $key . '&login=' . rawurlencode( $user_login ), 'login' ) . '</a>';

  return $message;
}

function gfee_set_schedule( $settings, $export ) {
	$date_parts = explode( '-', $settings['exports'][ $export ]['schedule_start_date'] );
	$date = $date_parts[2] . '-' . $date_parts[0] . '-' . $date_parts[1];
    
    $offset = get_option('gmt_offset');
	$schedule_date = $date . ' ' . $settings['exports'][ $export ]['hour'] . ':' . $settings['exports'][ $export ]['minute'] . $offset;

    $timestamp = strtotime( $schedule_date );
	wp_clear_scheduled_hook( 'export_gfee_entries' );

	wp_schedule_event( $timestamp, $settings['exports'][ $export ]['gfee_schedule_frequency'], 'export_gfee_entries', array( $export ) );
}

/**
 * Create custom schedule frequencies
*/
add_filter( 'cron_schedules','gfee_new_frequencies', 100, 1);
function gfee_new_frequencies( $schedules ) {
    $schedules['weekly'] = array(
        'interval' => 604800,
        'display'=> __( 'Weekly', 'gforms-export-entries' )
    );
    $schedules['two_days'] = array(
        'interval' => 172800,
        'display'=> __( 'Every other day', 'gforms-export-entries' )
    );
    $schedules['gfee_monthly'] = array(
        'interval' => 86400, //= Runs daily to see if it should trigger
        'display'=> __( 'GFEE Monthly', 'gforms-export-entries' )
    );
//    echo '<pre>' . print_r( $schedules, true ). '</pre>';die();
    
    return $schedules;
}

//============================
// Schedule remove old files
//============================
function gfee_set_schedule_clean_files( $settings, $export ) {
	gfee_log( 'Schedule cleaning old files.' );
	gfee_log( 'Clean files: ' . $settings['exports'][ $export ]['cleaning_days'] );
	$timestamp = time();
	wp_schedule_event( $timestamp, $settings['exports'][ $export ]['cleaning_days'], 'gfee_cleaning_days', array( $export ) );
}

function gfee_clear_schedule_clean_files( $settings, $export ) {
	gfee_log( 'Removed scheduled cleaning of old files: ' . print_r( $export, true ) );
	wp_clear_scheduled_hook( 'gfee_cleaning_days', $export );
}

function maybe_gfee_cleaning_days_schedule( $args ) {
    $settings = get_option( 'gfee_settings', array() );
    $today = date( 'd' );

    gfee_log( 'Maybe run cleaning of old files? Today\'s day of the month: ' . $today );
    if ( 'gfee_monthly' === $settings['cleaning_days'] ) {
        $d = DateTime::createFromFormat(
            'm-d-y H:i:s',
            $options['schedule_start_date'] . '00:00:00',
            new DateTimeZone('CST')
        );

        if ( $d === false ) {
            $timestamp = false;
        } else {
            $timestamp = $d->getTimestamp();
        }
        $day_to_run = date( 'd', $timestamp );

        gfee_log( 'Monthly cleaning was set to run on day: ' . $day_to_run );
        if ( $day_to_run == $today ) {
            gfee_log( 'Monthly cleaning should run today' );
            gfee_cleaning_days_schedule( $args );
        } //= if ( $day_to_run == $today )
    } else {
        if ( ! empty( $settings['cleaning_days'] ) ) {
            gfee_log( 'Cleaning set to run today: ' . print_r( $args, true ) );
            gfee_cleaning_days_schedule( $args );
        } else {
            gfee_log( 'No cleaning day scheduled' );
        }
    } //= if ( 'gfee_monthly' === $settings['cleaning_days'] )
}

function gfee_cleaning_days_schedule( $args ) {
	gfee_log( '++++++++++++++++++++++++++++++++++++' );
	gfee_log( 'START clean old files' );
	gfee_log( '++++++++++++++++++++++++++++++++++++' );

	//= Remove legacy files in root of the uploads folder
	$upload_arr = wp_upload_dir();
	$upload_dir = $upload_arr['basedir'];
	gfee_log( 'GFEE uploads root path: ' . $upload_dir );
	$files = glob( $upload_dir . '/' . __( 'Form entries for', 'gforms-export-entries' ) . '*.xls' ); // get all file names

	foreach( $files as $file ) { // iterate files
		if ( is_file( $file ) && false !== strpos( $file, __( 'Form entries for', 'gforms-export-entries' ) ) ) {
			gfee_log( 'Removed file: ' . print_r( $file, true ) );
			unlink( $file ); // delete file
		} else {
			gfee_log( 'Not a file, could not remove: ' . print_r( $file, true ) );
		}
	}

	//= Remove files from dedicated /gfee folder
	$upload_dir .= '/gfee';
	$files = glob( $upload_dir . '/' . __( 'Form entries for', 'gforms-export-entries' ) . '*.xls' ); // get all file names
	foreach( $files as $file ) { // iterate files
		if ( is_file( $file ) && false !== strpos( $file, __( 'Form entries for', 'gforms-export-entries' ) ) ) {
			gfee_log( 'Removed file: ' . print_r( $file, true ) );
			unlink( $file ); // delete file
		} else {
			gfee_log( 'Not a file, could not remove: ' . print_r( $file, true ) );
		}
	}

	gfee_log( '++++++++++++++++++++++++++++++++++++' );
	gfee_log( 'STOP clean old files' );
	gfee_log( '++++++++++++++++++++++++++++++++++++' );
}
?>