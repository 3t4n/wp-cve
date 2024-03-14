<?php

/**
 * Retrieves the base filename for log files.
 */
function autoship_get_log_filename(){

  $handle = apply_filters( 'autoship_file_name_handle', strtolower( autoship_get_site_info('name') ) );
  $date_suffix = date( 'Y-m-d' );
  $hash_suffix = wp_hash( $handle );
  return sanitize_file_name( implode( '-', array( $handle, $date_suffix, $hash_suffix, 'autoship' ) ) . '.log' );

}

/**
 * Retrieves the number of days to keep.
 */
function autoship_get_logs_keep_count(){
  return apply_filters( 'autoship_keep_logger_files_count', 30 );
}

/**
 * Retrieves the current log file directory or url.
 * @uses wp_upload_dir()
 *
 * @param string $type Either the basedir or baseurl
 * @return string The base directory url or path
 */
function autoship_get_logs_directory( $type = 'basedir' ){
	$paths = wp_upload_dir( null, false );
  return apply_filters('autoship_logs_directory_path', $paths[$type] . '/autoship-logs/' );
}

/**
 * Retrieves the Download Link for a log file.
 * @param string $file The log file name.
 * @return string The url or empty string if none exists.
 */
function autoship_get_log_file_download_link( $file ){
  $path = autoship_admin_settings_tab_url( 'autoship-logs' );
  return $path . '&autoship_download_log_file=' . $file;
}

/**
 * Checks if the log directory exists and if not tries to create it.
 * @param string $file The log file name.
 * @return bool True if directory created or exists else false.
 */
function autoship_maybe_setup_log_directory(){

  $logfilename = autoship_get_log_filename();
  $path        = trailingslashit( autoship_get_logs_directory() );
  $dirname     = dirname( $path . $logfilename );

  if ( !is_dir( $dirname ) ){

    $success = wp_mkdir_p( $path );

    // Create Directory and Add Security plus bot check

    // htaccess
    if ( $success && ! file_exists( trailingslashit( $path ) . '.htaccess' ) ) {
      $file_handle = fopen( trailingslashit( $path ) . '.htaccess', 'w' );
      if ( $file_handle ) {
        fwrite( $file_handle, 'deny from all' );
        fclose( $file_handle );
      }
    }

    // empty index.html file
    if ( $success && ! file_exists( trailingslashit( $path ) . 'index.html' ) ) {
      $file_handle = fopen( trailingslashit( $path ) . 'index.html', 'w' );
      if ( $file_handle ) {
        fwrite( $file_handle, '' );
        fclose( $file_handle );
      }
    }

    // directory failed to be created
    if ( !$success ){
      error_log( 'Autoship Logger Exception: Failed to create or open the log directory.' );
      return false;
    }

  }

  return true;

}

/**
 * Retrieves the current log files sorted by last mod datetime.
 * @return array An array of filenames.
 */
function autoship_get_log_files(){

  // Scan the directory for files.
  $dir    = autoship_get_logs_directory();

  // Check for the Log Directory and Make it if it doesn't exist.
  if ( !autoship_maybe_setup_log_directory() )
  return array();

  $files  = scandir( $dir );
  $result = array();

  // If there are files then iterate through them and disregard any non files.
  if ( ! empty( $files ) ) {

    foreach ( $files as $key => $value ) {
      if ( ! in_array( $value, array( '.', '..' ), true ) ) {
        if ( ! is_dir( $value ) && strstr( $value , '.log' ) ) {
          $name = preg_replace('/\\.[^.\\s]{3}$/', '', $value );
          $result[ $name ] = filemtime( trailingslashit( $dir ) . $value );
        }
      }
    }

    // Now sort the files.
    arsort( $result );

  }

  return $result;

}

/**
 * Delete the oldest log files and only keep latest logs.
 * This will only keep the latest logs and delete the rest.
 * Default is to keep the latest 30 log files.
 *
 * @param int The number of logs to keep. Default 30
 * @return int The total number of deleted files.
 */
function autoship_keep_latest_log_files( $keep_count = 30 ) {

  // Retrieve the log files.
  $files = autoship_get_log_files();

  // If there are no logs or we haven't hit the threshold don't delete.
  if ( count( $files ) <= $keep_count )
  return 0;

  // Slice off a chunk of files to delete.
  $files_to_delete = array_slice( $files, ( count( $files ) - $keep_count ) * -1, NULL, true);

  // Delete each of the files.
  foreach ( $files_to_delete as $filename => $timestamp )
  unlink( trailingslashit( autoship_get_logs_directory() ) . $filename . '.log' );

  // Return the deleted files count
  return count( $files_to_delete );

}

/**
 * Add a log entry.
 *
 * @param string $context The source or context for the entry
 * @param string $message Log message.
 */
function autoship_log_entry( $context, $message ){

  // Allow logging to be performed separately when Autoship Logging turned off.
  do_action( 'autoship_before_log_entry', $context, $message );

  if ( !apply_filters( 'autoship_log_entry', true, $context, $message ) )
  return true;

  // Allow adjustments to when the log files are cleaned up.
  // Allows outside management like with a scheduled task ( i.e. WP Cron job )
  if ( apply_filters( 'autoship_clean_logger_files', true ) ){
    $keep_count = autoship_get_logs_keep_count();
    autoship_keep_latest_log_files( $keep_count );
  }

  $logfilename          = autoship_get_log_filename();
  $path                 = trailingslashit( autoship_get_logs_directory() );

  $time    = date_i18n( 'm-d-Y @ H:i:s' );
  $entry   = "[{$time}] {$context} - {$message}";

  // Check for the Log Directory and Make it if it doesn't exist.
  if ( !autoship_maybe_setup_log_directory() )
  return false;

  // Open / Create the Log File
  $log_file = fopen( $path . $logfilename, 'a');

  if ( !$log_file ) {
    error_log( sprintf( 'Autoship Logger Exception: Failed to create or open the log file %s.', $logfilename ) );
    return false;
  }

  fwrite( $log_file, $entry . "\n" );
  fclose( $log_file );

  // Allow for email and other actions after a log entry has been added.
  do_action( 'autoship_after_log_entry', $time, $entry, $context, $message );

  return true;

}

/**
 * Checks for Download Init and echo's the screen.
 */
function autoship_download_log_file() {

  if ( !isset( $_GET['autoship_download_log_file'] ) || empty( $_GET['autoship_download_log_file'] ) || !current_user_can( 'export' ) )
  return;

  $file = $_GET['autoship_download_log_file'] . '.log';
  $path = trailingslashit( autoship_get_logs_directory() );

  if ( !file_exists ( realpath( $path . $file ) ) )
  return;

  header('Content-Type: text/plain');
  header('Content-Disposition: attachment;filename="'. $file .'"');
  header('Cache-Control: max-age=0');
  header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
  header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
  header( 'Cache-Control: private, max-age=0, must-revalidate' );
  header ('Pragma: public'); // HTTP/1.0
  $contents = file_get_contents ( $path . $file );
  echo $contents;
  exit();

}
add_action( 'admin_init', 'autoship_download_log_file' );
