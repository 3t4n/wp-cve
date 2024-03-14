<?php

class Send_Users_Email_cleanup
{
    public static function cleanupUserEmailProgress()
    {
        $options = get_option( 'sue_send_users_email' );
        $user_id = get_current_user_id();
        unset( $options['email_users_email_send_start_' . $user_id] );
        unset( $options['email_users_total_email_send_' . $user_id] );
        unset( $options['email_users_total_email_to_send_' . $user_id] );
        update_option( 'sue_send_users_email', $options );
    }
    
    public static function cleanupRoleEmailProgress()
    {
        $options = get_option( 'sue_send_users_email' );
        $user_id = get_current_user_id();
        unset( $options['email_roles_email_send_start_' . $user_id] );
        unset( $options['email_roles_total_email_send_' . $user_id] );
        unset( $options['email_roles_total_email_to_send_' . $user_id] );
        update_option( 'sue_send_users_email', $options );
    }
    
    public static function cleanupGroupEmailProgress()
    {
        $options = get_option( 'sue_send_users_email' );
        $user_id = get_current_user_id();
        unset( $options['email_groups_email_send_start_' . $user_id] );
        unset( $options['email_groups_total_email_send_' . $user_id] );
        unset( $options['email_groups_total_email_to_send_' . $user_id] );
        update_option( 'sue_send_users_email', $options );
    }
    
    public static function cleanErrorLogFile()
    {
        $errorLogFileName = sue_get_error_log_filename();
        
        if ( $errorLogFileName ) {
            $file = sue_log_path( $errorLogFileName );
            if ( file_exists( $file ) ) {
                unlink( $file );
            }
        }
        
        // Temporary code --- old log file cleanup
        $file = sue_log_path( 'error.log' );
        if ( file_exists( $file ) ) {
            unlink( $file );
        }
    }
    
    public static function cleanEmailLogFiles()
    {
        $errorLogFileName = sue_get_error_log_filename();
        $emailLogFiles = array_diff( scandir( sue_log_path() ), array(
            '..',
            '.',
            $errorLogFileName,
            '.htaccess'
        ) );
        $emailLogFiles = sue_remove_non_email_log_filename( $emailLogFiles );
        $emailLogFiles = array_reverse( $emailLogFiles );
        $daysToKeepEmailLogs = 15;
        $dateRangeArr = sue_get_past_dates_range_interval( $daysToKeepEmailLogs );
        $keepRangeArr = [];
        foreach ( $dateRangeArr as $dra ) {
            $keepRangeArr[] = 'email-log-' . $dra . '.log';
        }
        $filesToDelete = array_diff( $emailLogFiles, $keepRangeArr );
        $filesToDelete = sue_remove_non_email_log_filename( $filesToDelete );
        foreach ( $filesToDelete as $fileToDelete ) {
            $log = sue_log_path( $fileToDelete );
            if ( file_exists( $log ) ) {
                unlink( $log );
            }
        }
    }

}