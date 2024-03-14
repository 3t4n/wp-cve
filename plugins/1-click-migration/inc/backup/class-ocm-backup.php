<?php

namespace OCM;

use DirectoryIterator;
use phpseclib\Crypt\Rijndael;
use PhpZip\Constants\ZipCompressionMethod;
use PhpZip\Exception\ZipException;
use PhpZip\ZipFile;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Finder\SplFileInfo;
use WP_Error;
use wpdb;
use ZipArchive;

class OCM_Backup
{
    const STEP_BACKUP_CHILD_URL_GENERATED = 'urlGenerated';
    const STEP_BACKUP_CHILD_INITIATE = 'initiate';
    const STEP_BACKUP_CHILD_INITIATE_DB_BACKUP = 'initiateDbBackup';
    const STEP_BACKUP_CHILD_INITIATE_BACKUP_THEMES = 'initiateBackupThemes';
    const STEP_BACKUP_CHILD_INITIATE_BACKUP_PLUGINS = 'initiateBackupPlugins';
    const STEP_BACKUP_CHILD_INITIATE_BACKUP_UPLOADS = 'initiateBackupUploads';
    const STEP_BACKUP_CHILD_COMPRESS = 'compress';
    const STEP_BACKUP_CHILD_ENCRYPT = 'encrypt';
    const STEP_BACKUP_CHILD_UPLOAD = 'upload';
    const STEP_BACKUP_CHILD_ZIP_FILE_PATH = 'zipFilePath';

    const STEP_RESTORE_CHILD_INITIATE = 'initiate';
    const STEP_RESTORE_CHILD_DOWNLOAD = 'download';
    const STEP_RESTORE_CHILD_PRESIGNED_URLS = 'presigned_urls';
    const STEP_RESTORE_CHILD_DOWNLOAD_TMP_PATH = 'download_tmp_path';
    const STEP_RESTORE_CHILD_UNENCRYPTED_ZIP = 'unencrypted_zip';
    const STEP_RESTORE_CHILD_DECRYPT = 'decrypt';
    const STEP_RESTORE_CHILD_EXTRACT = 'extract';
    const STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW = 'deleteOldAndMoveNew';
    const STEP_RESTORE_CHILD_THEMES_UPLOADS_RESTORED = 'themesUploadsRestored';
    const STEP_RESTORE_CHILD_THEMES_RESTORED = 'themesRestored';
    const STEP_RESTORE_CHILD_UPLOADS_RESTORED = 'UploadsRestored';
    const STEP_RESTORE_CHILD_RESTORE = 'restore';

    const LOG_MESSAGE_BG_PROCESS_RESTARTING = 'Timeout approaching. Restarting';
    const LOG_MESSAGE_BG_PROCESS_RESTARTING_LOG = 'Notice: Please wait. This is taking longer than expected but we are still making progress';

    private static $skipMessages = [
        self::LOG_MESSAGE_BG_PROCESS_RESTARTING,
        self::LOG_MESSAGE_BG_PROCESS_RESTARTING_LOG
    ];

    private static $progress_data = [
        ['Error uploading file', '80%'],
        ['Error downloading file', '80%'],
        ['Time out approaching. Restarting', '0%'],

        ['Backup started', '1%'],

        ['OS:', '1%'],
        ['Restore started', '1%'],
        ['Excluding', '1%'],
        ['Email address', '1%'],
        ['Site URL', '1%'],
        ['WP Version', '1%'],
        ['PHP Version', '1%'],
        ['OCM Plugin Version', '1%'],
        ['Backup ID', '1%'],
        ['Available Space', '1%'],
        ['default max_execution_time', '1%'],
        ['max_execution_time', '1%'],
        ['timeout set:', '1%'],
        ['proc_open / proc_close', '1%'],
        ['Current IP Address', '1%'],
        ['Creating temporary directory', '1%'],


        ['URL generated: db', '5%'],
        ['URL generated: uploads', '9%'],
        ['URL generated: themes', '14%'],
        ['URL generated: plugins', '18%'],
        ['URL generated: log', '23%'],
        ['URL generated: log_download', '27%'],

        ['db added to queue', '28%'],
        ['themes added to queue', '29%'],
        ['plugins added to queue', '30%'],
        ['uploads added to queue', '31%'],

        ['db task has been started.', '38%'],
        ['DB Size', '39%'],
        ['Database backup started', '40%'],
        ['Database backup in progress. Please do not leave the plugin page', '40%'],
        ['SYSLOG: Number of updates made from replace', '40%'],
        ['SYSLOG: Finished updating URLs in the database', '40%'],
        ['SYSLOG: Table being backed up', '40%'],
        ['SYSLOG: No table structure for table', '40%'],
        ['SYSLOG: Inserting table data into table', '40%'],
        ['SYSLOG: End of data contents of table', '40%'],
        ['SYSLOG: There is no mysqldump support', '40%'],
        ['SYSLOG: Column statistics are supported', '40%'],
        ['SYSLOG: Table retrieved', '40%'],
        ['SYSLOG: Column statistics are not supported', '40%'],
        ['DB Zip file has been encrypted.', '41%'],
        ['Encrypting db Retry 1', '41%'],
        ['Encrypting db Retry 2', '41%'],
        ['Skipping DB Encrypting', '41%'],
        ['Compressing db Retry 1', '42%'],
        ['Compressing db Retry 2', '42%'],
        ['Skipping DB compression', '42%'],

        ['Database ZIP file created.', '42%'],
        ['Uploading db Retry 1', '42%'],
        ['Uploading db Retry 2', '42%'],
        ['Skipping DB upload', '42%'],
        ['db upload has been started.', '43%'],
        ['db task has been completed.', '44%'],

        ['themes task has been started.', '45%'],
        ['themes size', '45%'],
        ['Archiving themes to', '45%'],
        ['Compressing themes Retry 1', '45%'],
        ['Compressing themes Retry 2', '45%'],
        ['Skipping themes Compressing', '45%'],
        ['themes ZIP has been created.', '46%'],
        ['Encrypting themes Retry 1', '46%'],
        ['Encrypting themes Retry 2', '46%'],
        ['Skipping themes Encrypting', '46%'],
        ['themes ZIP has been encrypted.', '46%'],
        ['Uploading themes Retry 1', '47%'],
        ['Uploading themes Retry 2', '47%'],
        ['Skipping themes Upload', '46%'],
        ['themes folder upload has been started.', '47%'],
        ['themes task has been completed.', '48%'],

        ['plugins task has been started.', '49%'],
        ['plugins size:', '50%'],
        ['Archiving plugins to', '50%'],
        ['plugins ZIP has been created.', '51%'],
        ['Compressing plugins Retry 1', '50%'],
        ['Compressing plugins Retry 2', '50%'],
        ['Skipping plugins Compressing', '50%'],
        ['plugins ZIP has been encrypted.', '51%'],
        ['Encrypting plugins Retry 1', '51%'],
        ['Encrypting plugins Retry 2', '51%'],
        ['Skipping plugins Encrypting', '51%'],
        ['plugins folder upload has been started.', '52%'],
        ['Uploading plugins Retry 1', '52%'],
        ['Uploading plugins Retry 2', '52%'],
        ['Skipping plugins Upload', '52%'],
        ['plugins task has been completed.', '53%'],

        ['uploads task has been started.', '60%'],
        ['uploads size:', '61%'],
        ['Archiving uploads to', '61%'],
        ['Compressing uploads Retry 1', '61%'],
        ['Compressing uploads Retry 2', '61%'],
        ['Skipping uploads Compressing', '61%'],
        ['uploads ZIP has been created.', '62%'],
        ['uploads ZIP has been encrypted.', '63%'],
        ['Encrypting uploads Retry 1', '62%'],
        ['Encrypting uploads Retry 2', '62%'],
        ['Skipping uploads Encrypting', '62%'],
        ['uploads folder upload has been started.', '63%'],
        ['Uploading uploads Retry 1', '63%'],
        ['Uploading uploads Retry 2', '63%'],
        ['Skipping uploads Upload', '63%'],
        ['uploads task has been completed.', '65%'],

        ['Backup completed.', '100%'],
        ['Download task has been started', '6%'],

        ['Downloading restore files', '58%'],
        ['Downloading restore files. Please wait.', '58%'],

        ['Downloading file: "db.zip.crypt"', '60%'],
        ['Downloading db Retry 1', '60%'],
        ['Downloading db Retry 2', '60%'],
        ['Skipping db Download', '60%'],
        ['Decrypting file: "db.zip.crypt"', '60%'],
        ['Decrypting db Retry 1', '60%'],
        ['Decrypting db Retry 2', '60%'],
        ['Skipping db Decrypting', '60%'],
        ['Extracting file: "db.zip.crypt"', '60%'],
        ['Extracting db Retry 1', '60%'],
        ['Extracting db Retry 2', '60%'],
        ['Skipping db Extraction', '60%'],
        ['Restoring "DB"', '60%'],

        ['Downloading file: "uploads.zip.crypt"', '61%'],
        ['Downloading uploads Retry 1', '61%'],
        ['Downloading uploads Retry 2', '61%'],
        ['Skipping uploads Download', '61%'],
        ['Decrypting file: "uploads.zip.crypt"', '61%'],
        ['Decrypting uploads Retry 1', '61%'],
        ['Decrypting uploads Retry 2', '61%'],
        ['Skipping uploads Decrypting', '61%'],
        ['Extracting file: "uploads.zip.crypt"', '61%'],
        ['Extracting uploads Retry 1', '61%'],
        ['Extracting uploads Retry 2', '61%'],
        ['Skipping uploads Extraction', '61%'],
        ['Restoring "Uploads"', '61%'],

        ['Downloading file: "themes.zip.crypt"', '62%'],
        ['Downloading themes Retry 1', '62%'],
        ['Downloading themes Retry 2', '62%'],
        ['Skipping themes Download', '62%'],
        ['Decrypting file: "themes.zip.crypt"', '62%'],
        ['Decrypting themes Retry 1', '62%'],
        ['Decrypting themes Retry 2', '62%'],
        ['Skipping themes Decrypting', '62%'],
        ['Extracting file: "themes.zip.crypt"', '62%'],
        ['Extracting themes Retry 1', '62%'],
        ['Extracting themes Retry 2', '62%'],
        ['Skipping themes Extraction', '62%'],
        ['Restoring "Themes"', '62%'],

        ['Downloading file: "plugins.zip.crypt"', '63%'],
        ['Downloading plugins Retry 1', '63%'],
        ['Downloading plugins Retry 2', '63%'],
        ['Downloading file: "plugins.zip.crypt"', '63%'],
        ['Skipping plugins Download', '63%'],
        ['Decrypting file: "plugins.zip.crypt"', '63%'],
        ['Decrypting plugins Retry 1', '63%'],
        ['Decrypting plugins Retry 2', '63%'],
        ['Skipping plugins Decrypting', '63%'],
        ['Extracting file: "plugins.zip.crypt"', '63%'],
        ['Extracting plugins Retry 1', '63%'],
        ['Extracting plugins Retry 2', '63%'],
        ['Skipping plugins Extraction', '63%'],
        ['Restoring "Plugins"', '63%'],

        ['Download Error:', '60%'],
        ['Notice: file uploads.zip.crypt not found', '60%'],
        ['Notice: file themes.zip.crypt not found', '60%'],
        ['Notice: file plugins.zip.crypt not found', '60%'],
        ['Notice: file db.zip.crypt not found', '60%'],

        ['Themes have been restored', '64%'],
        ['Uploads have been restored', '64%'],
        ['Plugins have been restored', '64%'],

        ['Restore plugins', '75%'],
        ['Please make payment before the restore can complete', '77%'],
        ['Notice: Database restore in progress. Please don\'t leave the plugin page.', '80%'],
        ['Database has been restored', '83%'],
        ['Starting to update URLs in database', '84%'],
        ['Cleaning up.', '92%'],
        ['Restore completed.', '100%'],
        ['Error:', '0%'],
        ['Allowed memory size', '0%'],
        ['SYSLOG: "[PHP ERR][FATAL]', '0%'],
        ['Stop & Reset Finished', '0%'],
        ['Maximum execution time', '0%'],
        ['Notice: File themes was skipped due to timeout', '100%'],
        ['Notice: File plugins was skipped due to timeout', '100%'],
        ['Notice: File uploads was skipped due to timeout', '100%'],
        ['Notice: File db was skipped due to timeout', '100%'],
        ['Notice: File uploads.zip.crypt was not found on the remote server. Please try to back it up again.', '100%'],
        ['Notice: File db.zip.crypt was not found on the remote server. Please try to back it up again.', '100%'],
        ['Notice: File plugins.zip.crypt was not found on the remote server. Please try to back it up again.', '100%'],
        ['Notice: File themes.zip.crypt was not found on the remote server. Please try to back it up again.', '100%']

    ];

    private static  $restore_site_url = '';
    private static $restore_files = [];


    public static function cancel_actions()
    {
      wp_safe_redirect(admin_url('tools.php?page=one-click-migration'));
      update_option('ocm_is_stopped', true, true);
      One_Click_Migration::$process_backup_single->cancel_all_process();
      One_Click_Migration::$process_restore_single->cancel_all_process();
      One_Click_Migration::$process_backup_single->cancel_scheduled_event();
      One_Click_Migration::$process_restore_single->cancel_scheduled_event();
      OCM_BackgroundHelper::delete_all_batch_process();
      One_Click_Migration::cancel_all_process();
      One_Click_Migration::stop_and_reset();
      exit;
    }

    public static function start_backup()
    {

        @set_time_limit(One_Click_Migration::get_timeout());
        update_option('ocm_action_start_backup', true, true);
        update_option('ocm_is_stopped', false, true);
        update_option('ocm_upload_auto_try_nb_db', 0, true);
        update_option('ocm_upload_auto_try_nb_themes', 0, true);
        update_option('ocm_upload_auto_try_nb_plugins', 0, true);
        update_option('ocm_upload_auto_try_nb_uploads', 0, true);

        One_Click_Migration::delete_log_file();
        One_Click_Migration::cancel_all_process();

        self::reset_current_backup_steps();
        self::reset_current_restore_steps();
        self::delete_backup_options();

        if (!One_Click_Migration::$process_backup_single->is_queue_empty()) {

            One_Click_Migration::$process_backup_single->handle_cron_healthcheck();
            exit;
        }

        $username = self::get_username();
        $password = self::get_password();

        $bucket_key = self::get_bucket_key($username, $password);
        $excluded_folders = self::get_excluded_folders();
        $presigned_urls = OCM_S3::s3_create_bucket_ifnot_exists($bucket_key);
        $prev_bucket_key = get_option('ocm_bucket_key');

        if(!$prev_bucket_key){

          update_option('ocm_bucket_key', $bucket_key, true);
        }
        if(!empty($prev_bucket_key)){
          if($prev_bucket_key !== $bucket_key){
            update_option('ocm_bucket_key', $bucket_key, true);
          }
        }

        update_option('ocm_presigned_urls', $presigned_urls, true);


        if (!$presigned_urls) {
            One_Click_Migration::write_to_log('Error: Backup does not exist');
            wp_safe_redirect(admin_url('tools.php?page=one-click-migration&message=endpoint_failure'));
            exit;
        }

        if (!self::check_is_complete_backup_step('init', self::STEP_BACKUP_CHILD_INITIATE)) {

            One_Click_Migration::write_to_log('Backup started');

            $exluding = implode(", ", self::get_excluded_folders());

            One_Click_Migration::write_to_log(sprintf('Excluding "%s"', $exluding));

            self::save_system_data_to_log_file($username, $bucket_key);
            self::deleteDir(OCM_PLUGIN_WRITABLE_PATH, 'Cleaning temporary directory', $presigned_urls);

            // If tmp directory doesn't exist, create it
            if (!is_dir(OCM_PLUGIN_WRITABLE_PATH)) {
                if (!mkdir($concurrentDirectory = OCM_PLUGIN_WRITABLE_PATH, 0700) && !is_dir($concurrentDirectory)) {
                    One_Click_Migration::write_to_log(sprintf(
                        'Creating temporary directory ("%s"), Error: Directory "%s" was not created. Please check the write permission for the parent directory "%s".',
                        OCM_PLUGIN_WRITABLE_PATH, $concurrentDirectory, WP_CONTENT_DIR
                    ));
                    exit;
                }
            }

            foreach ($presigned_urls as $key => $generated_url) {

                One_Click_Migration::write_to_log("URL generated: $key");
            }

            self::set_complete_backup_step('init', self::STEP_BACKUP_CHILD_INITIATE);
        }

          if(!(in_array('db', $excluded_folders))){
            if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_INITIATE_DB_BACKUP)) {
                One_Click_Migration::write_to_log('db added to queue');
                One_Click_Migration::$process_backup_single->push_to_queue(array('db', $password));
            }
          }
          if(!(in_array('themes', $excluded_folders))){
            if (!self::check_is_complete_backup_step('themes', self::STEP_BACKUP_CHILD_INITIATE_BACKUP_THEMES)) {

                One_Click_Migration::write_to_log('themes added to queue');
                One_Click_Migration::$process_backup_single->push_to_queue(array('themes', $password));
            }
          }
          if(!(in_array('plugins', $excluded_folders))){
            if (!self::check_is_complete_backup_step('plugins', self::STEP_BACKUP_CHILD_INITIATE_BACKUP_PLUGINS)) {
                One_Click_Migration::write_to_log('plugins added to queue');
                One_Click_Migration::$process_backup_single->push_to_queue(array('plugins', $password));
            }
          }
          if(!(in_array('uploads', $excluded_folders))){
            if (!self::check_is_complete_backup_step('uploads', self::STEP_BACKUP_CHILD_INITIATE_BACKUP_UPLOADS)) {
                One_Click_Migration::write_to_log('uploads added to queue');
                One_Click_Migration::$process_backup_single->push_to_queue(array('uploads', $password));
            }
          }
        One_Click_Migration::$process_backup_single->save()->dispatch();

        exit;
    }



    public static function start_restore()
    {
        $username = self::get_username();
        $password = self::get_password();


        update_option('ocm_download_auto_try_nb_db', 0, true);
        update_option('ocm_download_auto_try_nb_themes', 0, true);
        update_option('ocm_download_auto_try_nb_plugins', 0, true);
        update_option('ocm_download_auto_try_nb_uploads', 0, true);

        update_option('ocm_user_email', $username, true);



        One_Click_Migration::cancel_all_process();

        self::reset_current_backup_steps();
        self::reset_current_restore_steps();
        self::delete_restore_options();
        One_Click_Migration::delete_log_file();
        $exluded_folders = self::get_excluded_folders();

        if (!One_Click_Migration::$process_restore_single->is_queue_empty()) {
            One_Click_Migration::$process_restore_single->handle_cron_healthcheck();
            exit;
        } else {
            One_Click_Migration::$process_restore_single->push_to_queue(array($username, $password));
            One_Click_Migration::$process_restore_single->save()->dispatch();
            exit;
        }
    }

    public static function start_restore_process($username, $password)
    {
        global $wpdb;

        @set_time_limit(One_Click_Migration::get_timeout());
        update_option('ocm_action_start_restore', true, true);
        update_option('ocm_is_stopped', false, true);



        $content_dir = WP_CONTENT_DIR . '/ocm_restore';
        $excluded_restore_files = get_option('ocm_excluded_folders', []);

        if (!self::check_is_complete_restore_step('init', self::STEP_RESTORE_CHILD_INITIATE)) {
            $bucket_key = self::get_bucket_key($username, $password);

            $presigned_urls = OCM_S3::s3_generate_download_urls($bucket_key);


            if (!$presigned_urls) {
                One_Click_Migration::write_to_log(sprintf('Error: Backup does not exist'));
                wp_safe_redirect(admin_url('tools.php?page=one-click-migration&message=endpoint_failure'));
                exit;
            }
            $prev_bucket_key = get_option('ocm_bucket_key');
            if(!$prev_bucket_key){
              update_option('ocm_bucket_key', $bucket_key, true);
            }
            if(!empty($prev_bucket_key)){
              if($prev_bucket_key !== $bucket_key){
                update_option('ocm_bucket_key', $bucket_key, true);
              }
            }

            update_option('ocm_presigned_urls', $presigned_urls, true);

            One_Click_Migration::write_to_log('Restore started');
            $excluded_restore_files = get_option('ocm_excluded_folders', []);

            $exluding = implode(", ", $excluded_restore_files);

            // One_Click_Migration::write_to_log(sprintf('Excluding "%s"', $exluding));
            self::save_system_data_to_log_file($username, $bucket_key);

            foreach ($presigned_urls as $key => $generated_url) {
              $writable_file_path = OCM_PLUGIN_WRITABLE_PATH . $key . '.zip';

              if(file_exists($writable_file_path)){
                One_Click_Migration::write_to_log("URL generated: $key");
              }

            }

            if (!is_dir($content_dir)) {
                if (!mkdir($content_dir, 0700) && !is_dir($content_dir)) {
                    One_Click_Migration::write_to_log(sprintf(
                        'Downloading restore files, Error: Directory "%s" was not created. Please check the write permission for the parent directory "%s".',
                        $content_dir, WP_CONTENT_DIR
                    ));
                    exit;
                }
            }

            One_Click_Migration::write_to_log('Downloading restore files. Please wait.');

            self::set_complete_restore_step('init', self::STEP_RESTORE_CHILD_INITIATE);
            self::set_complete_restore_step('init', self::STEP_RESTORE_CHILD_PRESIGNED_URLS, $presigned_urls);
        }

        $presigned_urls = self::get_restore_step_value('init', self::STEP_RESTORE_CHILD_PRESIGNED_URLS);
        $excluded_folders = self::get_excluded_folders();

        // Download each of the zip files
        foreach ($presigned_urls as $key => $download_url) {
            // Don't try and download the log file

            $excluded_restore_files = get_option('ocm_excluded_folders',[]);
            $keyZipCrypt = $key . '.zip.crypt';
            $file_path = $content_dir . '/' . $keyZipCrypt;
            $extract_path = $content_dir . "/$key";
            if(!in_array($key, $excluded_restore_files)){

            if (in_array($key, array('log', 'log_download'))) {
                continue;
            }

            $excluded_restore_files = get_option('ocm_excluded_folders',[]);
            $skipped_restore_files = get_option('ocm_skipped_folders', []);
            $eexcluded_restore_files = get_option('ocm_eexcluded_folders', []);



              if(is_dir($content_dir)){
                $retry_count = get_option('ocm_restore_download_retry_' . $key, 0);

                if(!$retry_count){
                  update_option('ocm_restore_download_retry_' . $key, 1, true);

                }
                if($retry_count){
                  if($retry_count <= 2){
                    if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD)) {
                    One_Click_Migration::write_to_log(sprintf('Downloading %s Retry %d', $key, $retry_count));
                    $retry_count = $retry_count + 1;
                    update_option('ocm_restore_download_retry_' . $key, $retry_count, true);
                    }
                  }else{
                      if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD)) {

                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Download'));
                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Decrypting'));
                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Extraction'));
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                        self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                        self::capture_failed_folder($skipped_restore_files, $key, 'ocm_skipped_folders');
                        continue;

                      }
                    }
                  }
                  if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD)) {

                    One_Click_Migration::write_to_log(sprintf('Downloading file: "%s.zip.crypt"', $key));


                    $downloadTmpPathFile = download_url($download_url, One_Click_Migration::$process_restore_single->remaining_time());

                    if(is_wp_error($downloadTmpPathFile)){
                      $error_message = $downloadTmpPathFile->get_error_message();
                      if($error_message === 'Not Found'){

                        array_push($excluded_restore_files, $key);
                        $excluded_restore_files = array_unique($excluded_restore_files);

                        update_option( 'ocm_excluded_folders', $excluded_restore_files);
                        self::capture_failed_folder($eexcluded_restore_files, $key, 'ocm_eexcluded_folders');
                        One_Click_Migration::write_to_log("Notice: file $keyZipCrypt not found");

                        continue;
                      }
                    }

                    if (One_Click_Migration::$process_restore_single->time_exceeded()) {
                        $next_retry_count = get_option('ocm_restore_download_retry_' . $key, 1);
                        if($next_retry_count && $next_retry_count <=2){
                          One_Click_Migration::write_to_log("Process is Restarting");
                          update_option('ocm_restore_download_retry_' . $key, $next_retry_count, true);
                          One_Click_Migration::$process_restore_single->restart_task();
                        }
                        else{
                          One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Download'));
                          One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Decrypting'));
                          One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Extraction'));
                          self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD);
                          self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT);
                          self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                          self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                          self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                          self::capture_failed_folder($skipped_restore_files, $key, 'ocm_skipped_folders');
                          continue;
                        }
                    }
                    if (!One_Click_Migration::$process_restore_single->time_exceeded()) {

                      self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD_TMP_PATH, $downloadTmpPathFile);
                      self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DOWNLOAD);
                  }
                }
              }
              if(is_dir($content_dir)){
                $retry_count = get_option('ocm_restore_decrypt_retry_' . $key, 0);
                if(!$retry_count){
                  update_option('ocm_restore_decrypt_retry_' . $key, 1, true);

                }
                if($retry_count){
                  if($retry_count <= 2){
                    if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT)) {
                    One_Click_Migration::write_to_log(sprintf('Decrypting %s Retry %d', $key, $retry_count));
                    $retry_count = $retry_count + 1;
                    update_option('ocm_restore_decrypt_retry_' . $key, $retry_count, true);
                    }
                  }else{
                      if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT)) {

                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Decypting'));
                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Extraction'));

                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                        self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                        self::capture_failed_folder($skipped_restore_files, $key, 'ocm_skipped_folders');
                        continue;
                      }
                    }
                  }
                if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT)) {

                  One_Click_Migration::write_to_log(sprintf('Decrypting file: "%s.zip.crypt"', $key));

                  $downloadTmpPathFile = self::get_restore_step_value($key, self::STEP_RESTORE_CHILD_DOWNLOAD_TMP_PATH);
                  copy($downloadTmpPathFile, $file_path);
                  $unencrypted_zip = self::decryptZipFile($file_path, $password);
                  if (!$unencrypted_zip) {
                      update_option('ocm_is_stopped', true, true);
                      exit;
                  }

                  if (One_Click_Migration::$process_restore_single->time_exceeded()) {
                      $next_retry_count = get_option('ocm_restore_decrypt_retry_' . $key, 1);
                      if($next_retry_count && $next_retry_count <=2){
                        One_Click_Migration::write_to_log("Process is Restarting");
                        update_option('ocm_restore_decrypt_retry_' . $key, $next_retry_count, true);
                        One_Click_Migration::$process_restore_single->restart_task();
                      }
                      else{
                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Decypting'));
                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Extraction'));

                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                        self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                        self::capture_failed_folder($skipped_restore_files, $key, 'ocm_skipped_folders');
                        continue;
                      }
                    }

                    if (!One_Click_Migration::$process_restore_single->time_exceeded()) {

                      self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_UNENCRYPTED_ZIP, $unencrypted_zip);
                      self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DECRYPT);
                  }
                }
              }

              if (class_exists('ZipArchive') != true) {
      			  	One_Click_Migration::write_to_log('Error: Class ZipArchive not found. Please see <a href="http://www.php.net/manual/en/zip.installation.php">this page</a> for installation instructions or ask your hosting provider to enable the zip extension');
      	        exit;
      				}

              if(is_dir($content_dir)){
                $retry_count = get_option('ocm_restore_extract_retry_' . $key, 0);
                if(!$retry_count){
                  update_option('ocm_restore_extract_retry_' . $key, 1, true);

                }
                if($retry_count){
                  if($retry_count <= 2){
                    if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT)) {
                    One_Click_Migration::write_to_log(sprintf('Extracting %s Retry %d', $key, $retry_count));
                    $retry_count = $retry_count + 1;
                    update_option('ocm_restore_extract_retry_' . $key, $retry_count, true);
                    }
                  }else{
                      if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT)) {

                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Extraction'));

                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                        self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                        self::capture_failed_folder($skipped_restore_files, $key, 'ocm_skipped_folders');
                        continue;
                      }
                    }
                  }
                }
                if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT)) {

                  One_Click_Migration::write_to_log(sprintf('Extracting file: "%s.zip.crypt"', $key));

                  $unencrypted_zip = self::get_restore_step_value($key, self::STEP_RESTORE_CHILD_UNENCRYPTED_ZIP);
                  $zip = new ZipArchive;
                  $res = $zip->open($unencrypted_zip['filename']);
                  if ($res !== true) {
                      One_Click_Migration::write_to_log("Error: Zip file $file_path could not be unencrypted/opened.");
                  }

                  $zip->extractTo($extract_path);
                  $zip->close();

                  if (!is_dir($extract_path)) {
                      One_Click_Migration::write_to_log("Error: Zip file could not be extracted at $extract_path.");
                  }

                    if (One_Click_Migration::$process_restore_single->time_exceeded()) {
                      $next_retry_count = get_option('ocm_restore_extract_retry_' . $key, 1);
                      if($next_retry_count && $next_retry_count <=2){
                        One_Click_Migration::write_to_log("Process is Restarting");
                        update_option('ocm_restore_extract_retry_' . $key, $next_retry_count, true);
                        One_Click_Migration::$process_restore_single->restart_task();
                      }
                      else{

                        One_Click_Migration::write_to_log(sprintf('Skipping ' .$key.  ' Extraction'));

                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                        self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                        self::capture_failed_folder($skipped_restore_files, $key, 'ocm_skipped_folders');
                        continue;
                      }
                    }

                    if (!One_Click_Migration::$process_restore_single->time_exceeded()) {


                    self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_EXTRACT);
                  }
                }

              }
              if(is_dir($content_dir)){
                $retry_count = get_option('ocm_restore_child_delete_retry_' . $key, 0);
                if(!$retry_count){
                  update_option('ocm_restore_child_delete_retry_' . $key, 1, true);

                }
                if($retry_count){
                  if($retry_count <= 2){
                    if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW)) {
                    One_Click_Migration::write_to_log(sprintf('Deleting old %s Retry %d', $key, $retry_count));
                    $retry_count = $retry_count + 1;
                    update_option('ocm_restore_child_delete_retry_' . $key, $retry_count, true);
                    }
                  }else{
                      if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW)) {

                        One_Click_Migration::write_to_log(sprintf('Skipping Old ' .$key.  ' Deletion'));

                        self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                        self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                        continue;
                      }
                    }
                  }
                if (!self::check_is_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW)) {
                    if (!in_array($key, array('db', 'plugins')) && is_dir($extract_path)) {

                        $directory = WP_CONTENT_DIR . '/' . $key;

                        One_Click_Migration::write_to_log(sprintf('Restoring "%s" to directory "%s"', ucfirst($key), WP_CONTENT_DIR));

                        // Delete the existing folder and move the new folder into place
                        self::deleteDir($directory, 'Downloading restore files', $presigned_urls);

                        if (file_exists($directory) && !rmdir($directory)) {
                            One_Click_Migration::write_to_log(sprintf(
                                'Downloading restore files. Error: "%s" directory could not be deleted. Please check the write permission for this directory.',
                                $directory
                            ));

                            update_option('ocm_is_stopped', true, true);
                            die();
                        }

                        if (!@rename($extract_path, $directory)) {
                            One_Click_Migration::write_to_log(sprintf(
                                'Downloading restore files. Error: "%s" directory could not be moved to "%s" directory. Please check the write permission for these directories.',
                                $extract_path, $directory
                            ));

                            update_option('ocm_is_stopped', true, true);
                            die();
                        }


                      if (One_Click_Migration::$process_restore_single->time_exceeded()) {
                        $next_retry_count = get_option('ocm_restore_child_delete_retry_' . $key, 1);
                        if($next_retry_count && $next_retry_count <=2){
                          One_Click_Migration::write_to_log("Process is Restarting");
                          update_option('ocm_restore_child_delete_retry_' . $key, $next_retry_count, true);
                          One_Click_Migration::$process_restore_single->restart_task();
                        }
                        else{

                          One_Click_Migration::write_to_log(sprintf('Skipping Old ' .$key.  ' Deletion'));

                          self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                          self::capture_failed_folder($excluded_restore_files, $key, 'ocm_excluded_folders');
                          continue;
                        }
                      }
                    }

                    self::set_complete_restore_step($key, self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW);
                }

              }
            }
        if(!is_dir($content_dir)){
          return OCM_BackgroundRestore::RETURN_TYPE_STOP_PROCESSS;
          update_option('ocm_is_stopped', true, true);
          die();

        }

        if(!in_array('themes', $excluded_restore_files)){
          if (!self::check_is_complete_restore_step('themes', self::STEP_RESTORE_CHILD_THEMES_RESTORED)) {
              One_Click_Migration::write_to_log('Themes have been restored');
              self::set_complete_restore_step('themes', self::STEP_RESTORE_CHILD_THEMES_RESTORED);
          }
        }
        if(!in_array('uploads', $excluded_restore_files)){
          if (!self::check_is_complete_restore_step('uploads', self::STEP_RESTORE_CHILD_UPLOADS_RESTORED)) {
              One_Click_Migration::write_to_log('Uploads have been restored');
              self::set_complete_restore_step('uploads', self::STEP_RESTORE_CHILD_UPLOADS_RESTORED);
          }
        }

        if(!in_array('plugins', $excluded_restore_files)){
          if (!self::check_is_complete_restore_step('plugins', self::STEP_RESTORE_CHILD_RESTORE)) {

            self::restore_plugins($presigned_urls);
          }

        }
        if(!in_array('db', $excluded_restore_files)){
          One_Click_Migration::write_to_log('Please make payment before the restore can complete');

        }else{
          self::complete_restore();
        }

        exit;
    }

    public static function complete_restore(){
      $presigned_urls = get_option('ocm_presigned_urls');

        self::post_restore_cleanup();

        One_Click_Migration::write_to_log('Cleaning up.');

        // Delete ocm_restore folder
        $ocmRestoreDir = WP_CONTENT_DIR . '/ocm_restore/';



        if(WP_DEBUG !== true){

        if (is_dir($ocmRestoreDir)) {
            self::deleteDir($ocmRestoreDir, 'Cleaning up "ocm_restore" directory', $presigned_urls);
            // Delete ocm_restore folder
              if (file_exists($ocmRestoreDir) && !rmdir($ocmRestoreDir)) {
                One_Click_Migration::write_to_log(sprintf(
                    'Cleaning up "ocm_restore" directory. Error: "%s" directory could not be deleted. Please check the write permission for this directory or the parent directory.',
                    $ocmRestoreDir
                ));

                update_option('ocm_is_stopped', true, true);
                exit;
            }
          }
        }
        self::print_folders_skipped();
        self::print_folders_not_found();

        One_Click_Migration::write_to_log('Restore completed.');
        One_Click_Migration::reset_actions_start_mark();
        self::reset_current_restore_steps();
        update_option('ocm_is_stopped', true, true);


    }


    public static function complete_restore_after_payment()
       {

        global $wpdb;
        $presigned_urls = get_option('ocm_presigned_urls');
        if ( get_option('ocm_payment_status') !== 'payment_completed' ){
            One_Click_Migration::write_to_log('Failed to complete the restore because of payment');
            die();
        }
        $excluded_folders = get_option( 'ocm_excluded_folders', []);

        $ocm_user_email = get_option('ocm_user_email');
        $urlparts = parse_url(home_url());
		    $domain = $urlparts['host'];
        wp_remote_get ( OCM_API_ENDPOINT . '?pduser=' . $ocm_user_email . '&domain=' .  $domain );

        //cater for the database restore.
        if(!in_array('db', $excluded_folders)){
          if (!self::check_is_complete_restore_step('db', self::STEP_RESTORE_CHILD_RESTORE)) {
              self::restore_db($presigned_urls, $wpdb);
          }
        }


        //search and replace all the  old urls in the database.
        $current_url = site_url();
        $old_url = self::$restore_site_url;


        if (!empty($old_url)) {
            $extra_arguments = array(
                'search_for'=> $old_url,
                'replace_with' => $current_url
            );
            $db_details = new OCM_DB();
            $tables = $db_details->get_tables();
            $result = $db_details->search_replace_db( $tables, $extra_arguments );
            $report_update = $result['updates'];
            One_Click_Migration::write_to_log(sprintf('SYSLOG: Number of updates made from replace "%s"',$report_update) );
            One_Click_Migration::write_to_log(sprintf('SYSLOG: Finished updating URLs in the database'));
        }
        self::complete_restore();
        exit;
    }

    public static function initiate_db_backup($presigned_urls, $password)
    {
        global $wpdb;

        One_Click_Migration::write_to_log('Database backup started');
		    One_Click_Migration::write_to_log('DB Size '. self::DBSize().'MB');
        $skipped_restore_files = get_option('ocm_skipped_folders', []);

        $retry_count = get_option('ocm_backup_compress_retry_db', 0);


        if($retry_count == 0){
          update_option('ocm_backup_compress_retry_db', 1, true);

        }
        if($retry_count){
          if($retry_count <= 2){
            if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_COMPRESS)) {
            One_Click_Migration::write_to_log(sprintf('Compressing %s Retry %d','db', $retry_count));
            $retry_count = $retry_count + 1;
            update_option('ocm_backup_compress_retry_db' , $retry_count, true);
          }
        }else{
            if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_COMPRESS)) {

              One_Click_Migration::write_to_log(sprintf('Skipping DB compression'));
              One_Click_Migration::write_to_log(sprintf('Skipping DB encrypting'));
              One_Click_Migration::write_to_log(sprintf('Skipping DB upload'));
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_COMPRESS);
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT);
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, 'db', 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File DB was skipped due to timeout");
            }
          }
        }


        // If file exists already, delete it
        if (file_exists(OCM_PLUGIN_WRITABLE_PATH . 'db_backup.sql')) {
            unlink(OCM_PLUGIN_WRITABLE_PATH . 'db_backup.sql');
        }

        if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_COMPRESS)) {


        $tables = $wpdb->get_results('SHOW TABLES');

        // If tmp directory doesn't exist, create it
        if (!is_dir(OCM_PLUGIN_WRITABLE_PATH . 'db/')) {
            if (!mkdir($concurrentDirectory = OCM_PLUGIN_WRITABLE_PATH . 'db/', 0700) && !is_dir($concurrentDirectory)) {
                One_Click_Migration::write_to_log(sprintf('Database backup started, Error: Directory "%s" was not created', $concurrentDirectory));

                One_Click_Migration::write_to_log(sprintf(
                    'Database backup started, Error: Directory "%s" was not created. Please check the write permission for the parent directory "%s".',
                    $presigned_urls, WP_CONTENT_DIR
                ));

                update_option('ocm_is_stopped', true, true);
                exit;
            }
        }

        foreach ($tables as $table) {
            foreach ($table as $t) {

                $file = OCM_PLUGIN_WRITABLE_PATH . "db/$t.sql";
                if(file_exists(OCM_PLUGIN_WRITABLE_PATH . "db")){
                  One_Click_Migration::write_to_log(sprintf(' SYSLOG: Table being backed up: "%s"', $t));
                  OCM_SQL_Utils::export([$file], [
                      'tables' => $t,
                      'quick' => null
                  ]);

                }else{
                  return OCM_BackgroundBackup::RETURN_TYPE_END_PROCESSS;

                }

            }
        }


          $zip_filepath = self::createZipFile(OCM_PLUGIN_WRITABLE_PATH . 'db/', $password, $presigned_urls);
          if (!$zip_filepath) {
              update_option('ocm_is_stopped', true, true);
              exit;
          }

          if (!One_Click_Migration::$process_backup_single->time_exceeded()) {
            One_Click_Migration::write_to_log('Database ZIP file created.');
            self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_COMPRESS);
            self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT);
          }


          if (One_Click_Migration::$process_backup_single->time_exceeded()) {
            $next_retry_count = get_option('ocm_backup_compress_retry_db', 1);

            if($next_retry_count && $next_retry_count <=2){
              One_Click_Migration::write_to_log("Process is Restarting");
              update_option('ocm_backup_compress_retry_db', $next_retry_count, true);
              One_Click_Migration::$process_backup_single->restart_task();

            }else{

              One_Click_Migration::write_to_log(sprintf('Skipping DB compression'));
              One_Click_Migration::write_to_log(sprintf('Skipping DB encrypting'));
              One_Click_Migration::write_to_log(sprintf('Skipping DB upload'));
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_COMPRESS);
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT);
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, 'db', 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File DB was skipped due to timeout");
            }

          }

        }

        $retry_count = get_option('ocm_backup_upload_retry_db', 0);

        if(!$retry_count){
          update_option('ocm_backup_upload_retry_db', 1, true);

        }
        if($retry_count){
          if($retry_count <= 2){

          One_Click_Migration::write_to_log(sprintf('Uploading %s Retry %d', 'db', $retry_count));
          $retry_count = $retry_count + 1;
          update_option('ocm_backup_upload_retry_db' , $retry_count, true);
        }else{

          One_Click_Migration::write_to_log(sprintf('Skipping DB upload'));
          self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
          self::capture_failed_folder($skipped_restore_files, 'db', 'ocm_skipped_folders');
          One_Click_Migration::write_to_log("Notice: File DB was skipped due to timeout");
        }

        }



        if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD)) {
          One_Click_Migration::write_to_log(sprintf('%s upload has been started.', 'db'));

          $return = OCM_S3::upload_zip($zip_filepath);
          if (in_array($return, [OCM_BackgroundBackup::RETURN_TYPE_STOP_PROCESSS, OCM_BackgroundBackup::RETURN_TYPE_END_PROCESSS], true))
          {
             return $return;
          }

          if (!One_Click_Migration::$process_backup_single->time_exceeded()) {
            self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
            return false;
          }

          if (One_Click_Migration::$process_backup_single->time_exceeded()) {
            $next_retry_count = get_option('ocm_backup_upload_retry_db', 1);
            if($next_retry_count && $next_retry_count <=2){
              One_Click_Migration::write_to_log("Process is Restarting");
              update_option('ocm_backup_upload_retry_db', $next_retry_count, true);
              One_Click_Migration::$process_backup_single->restart_task();
            }else{

              One_Click_Migration::write_to_log(sprintf('Skipping DB upload'));
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, 'db', 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File DB was skipped due to timeout");
            }

          }

        }

    }

    public static function createZipFile($filepath, $password, $presigned_urls)
    {
        $zip = new ZipFile();
        $filename = OCM_PLUGIN_WRITABLE_PATH . 'db.zip';
        $skipped_restore_files = get_option('ocm_skipped_folders', []);

        $dir = new DirectoryIterator($filepath);
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $zip->addFile($fileinfo->getPathname(), '/' . $fileinfo->getFilename(), ZipCompressionMethod::STORED);
            }
        }

        try {
            $zip->saveAsFile($filename);
        } catch (ZipException $e) {
            One_Click_Migration::write_to_log(sprintf('Error: %s', $e->getMessage()));

            update_option('ocm_is_stopped', true, true);
            exit;
        }
        $zip->close();
        $retry_count = get_option('ocm_backup_encrypt_retry_db', 0);
        if(!$retry_count){
          update_option('ocm_backup_encrypt_retry_db', 1, true);
        }
        if($retry_count){
          if($retry_count <= 2){
            if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT)) {
              One_Click_Migration::write_to_log(sprintf('Encrypting %s Retry %d', 'db', $retry_count));
              $retry_count = $retry_count + 1;
              update_option('ocm_backup_encrypt_retry_db' , $retry_count, true);
            }
          }
          else{
            if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT)) {
              One_Click_Migration::write_to_log(sprintf('Skipping DB Encrypting'));
              One_Click_Migration::write_to_log(sprintf('Skipping DB upload'));
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT);
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, 'db', 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File DB was skipped due to timeout");
            }
          }
      }

        if (!self::check_is_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT)) {

          $return = self::encryptZipFile($filename, $password);
          if (!$return) {
              update_option('ocm_is_stopped', true, true);
              exit;
          }

          if (One_Click_Migration::$process_backup_single->time_exceeded()) {
            $next_retry_count = get_option('ocm_backup_encrypt_retry_db', 1);

            if($next_retry_count && $next_retry_count <=2){
              One_Click_Migration::write_to_log("Process is Restarting");
              update_option('ocm_backup_encrypt_retry_db', $next_retry_count, true);
              One_Click_Migration::$process_backup_single->restart_task();
            }
            else{
              One_Click_Migration::write_to_log(sprintf('Skipping DB Encrypting'));
              One_Click_Migration::write_to_log(sprintf('Skipping DB upload'));
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_ENCRYPT);
              self::set_complete_backup_step('db', self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, 'db', 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File DB was skipped due to timeout");

            }

          }
          One_Click_Migration::write_to_log('DB Zip file has been encrypted.');
          return $return;
        }

    }

    public static function initiate_folder_backup($folder_name, $presigned_urls, $password)
    {
        // Initialize archive object
        $zip = new ZipFile();
        $filename = sprintf('%s%s.zip', OCM_PLUGIN_WRITABLE_PATH, $folder_name);
        $folder_path = sprintf('%s/%s', WP_CONTENT_DIR, $folder_name);
        $skipped_restore_files = get_option('ocm_skipped_folders', []);



        // compress
        $retry_count = get_option('ocm_backup_compress_retry_' . $folder_name, 0);
        if(!$retry_count){
          update_option('ocm_backup_compress_retry_' . $folder_name, 1, true);

        }

        if($retry_count){
          if($retry_count <= 2){
            if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS)) {
            One_Click_Migration::write_to_log(sprintf('Compressing %s Retry %d', $folder_name, $retry_count));
            $retry_count = $retry_count + 1;
            update_option('ocm_backup_compress_retry_' . $folder_name, $retry_count, true);
            }
          }
          else{
            if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS)) {

              One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Compressing'));
              One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Encypting'));
              One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Upload'));
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS);
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT);
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, $folder_name, 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File $folder_name was skipped due to timeout");
            }

          }

        }
        if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS)) {
            //log folder sizes
            $folder_size = self::convertToReadableSize(self::folderSize($folder_path));
            One_Click_Migration::write_to_log(sprintf('%s size: %s', $folder_name, $folder_size));



            // Create recursive directory iterator
            /** @var SplFileInfo[] $files */
            $files = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($folder_path),
                RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $name => $file) {
                // Skip directories (they would be added automatically)
                if (!$file->isDir()) {
                    // Get real and relative path for current file
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($folder_path) + 1);

                    // Add current file to archive
                    $zip->addFile($filePath, $relativePath, ZipCompressionMethod::STORED);
                }
            }

            try {
                One_Click_Migration::write_to_log(sprintf('Archiving %s to %s', $folder_name, $filename));
                $zip->saveAsFile($filename);
            } catch (ZipException $e) {



                One_Click_Migration::write_to_log(sprintf('Error: %s', $e->getMessage()));

                $next_retry_count = get_option('ocm_backup_compress_retry_' . $folder_name, 1);

                if($next_retry_count && $next_retry_count <=2){

                  One_Click_Migration::write_to_log("Process is Restarting");

                  update_option('ocm_backup_compress_retry_' . $folder_name, $next_retry_count, true);

                  One_Click_Migration::$process_backup_single->restart_task();
                }
                else{


                  One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Compressing'));
                  One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Encypting'));
                  One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Upload'));
                  self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS);
                  self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT);
                  self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
                  self::capture_failed_folder($skipped_restore_files, $folder_name, 'ocm_skipped_folders');
                  One_Click_Migration::write_to_log("Notice: File $folder_name was skipped due to timeout");
                }

            }

            $zip->close();
            if (!One_Click_Migration::$process_backup_single->time_exceeded()) {
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS);

              One_Click_Migration::write_to_log(sprintf('%s ZIP has been created.', $folder_name));

            }

            if (One_Click_Migration::$process_backup_single->time_exceeded()) {
              $next_retry_count = get_option('ocm_backup_compress_retry_' . $folder_name, 1);

              if($next_retry_count && $next_retry_count <=2){
                One_Click_Migration::write_to_log("Process is Restarting");

                update_option('ocm_backup_compress_retry_' . $folder_name, $next_retry_count, true);

                One_Click_Migration::$process_backup_single->restart_task();
              }
              else{

                One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Compressing'));
                One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Encypting'));
                One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Upload'));
                self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_COMPRESS);
                self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT);
                self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
                self::capture_failed_folder($skipped_restore_files, $folder_name, 'ocm_skipped_folders');
                One_Click_Migration::write_to_log("Notice: File $folder_name was skipped due to timeout");
              }
            }
        }

        // encrypt
        $retry_count = get_option('ocm_backup_encrypt_retry_' . $folder_name, 0);
        if(!$retry_count){
          update_option('ocm_backup_encrypt_retry_' . $folder_name, 1, true);

        }
        if($retry_count){
          if($retry_count<=2){
            if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT)) {
            One_Click_Migration::write_to_log(sprintf('Encrypting %s Retry %d', $folder_name, $retry_count));
            $retry_count = $retry_count + 1;
            update_option('ocm_backup_encrypt_retry_' . $folder_name, $retry_count, true);
            }
          }else{
            if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT)) {

              One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Encypting'));
              One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Upload'));
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT);
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
              self::capture_failed_folder($skipped_restore_files, $folder_name, 'ocm_skipped_folders');
              One_Click_Migration::write_to_log("Notice: File $folder_name was skipped due to timeout");
            }

          }

        }
        if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT)) {


            $zip_filepath = self::encryptZipFile($filename, $password);
            if (!$zip_filepath) {
                update_option('ocm_is_stopped', true, true);
                exit;
            }

            if (!One_Click_Migration::$process_backup_single->time_exceeded()) {

              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ZIP_FILE_PATH, $zip_filepath);
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT);
              One_Click_Migration::write_to_log(sprintf('%s ZIP has been encrypted.', $folder_name));
            }

            if (One_Click_Migration::$process_backup_single->time_exceeded()) {
              $next_retry_count = get_option('ocm_backup_encrypt_retry_' . $folder_name, 1);
              if($next_retry_count && $next_retry_count <=2){
                One_Click_Migration::write_to_log("Process is Restarting");
                update_option('ocm_backup_encrypt_retry_' . $folder_name, $next_retry_count, true);
                One_Click_Migration::$process_backup_single->restart_task();

              }else{

                One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Encypting'));
                One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Upload'));
                self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_ENCRYPT);
                self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
                self::capture_failed_folder($skipped_restore_files, $folder_name, 'ocm_skipped_folders');
                One_Click_Migration::write_to_log("Notice: File $folder_name was skipped due to timeout");
              }

            }

        }

        // upload
        $retry_count = get_option('ocm_backup_upload_retry_' . $folder_name, 0);
        if(!$retry_count){
          update_option('ocm_backup_upload_retry_' . $folder_name, 1, true);

        }
        if($retry_count){
          if($retry_count <= 2){
            if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD)) {
            One_Click_Migration::write_to_log(sprintf('Uploading %s Retry %d', $folder_name, $retry_count));
            $retry_count = $retry_count + 1;
            update_option('ocm_backup_upload_retry_' . $folder_name, $retry_count, true);
            }
          }else{
            if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD)) {

            One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Uploading'));
            self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
            }
          }

        }
        if (!self::check_is_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD)) {
            One_Click_Migration::write_to_log(sprintf('%s folder upload has been started.', $folder_name));

            $zip_filepath = self::get_backup_step_value($folder_name, self::STEP_BACKUP_CHILD_ZIP_FILE_PATH);
            $return = OCM_S3::upload_zip($zip_filepath);

            if (in_array($return, [OCM_BackgroundBackup::RETURN_TYPE_STOP_PROCESSS, OCM_BackgroundBackup::RETURN_TYPE_END_PROCESSS], true)) {
                return $return;
            }

            if (!One_Click_Migration::$process_backup_single->time_exceeded()) {
              self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);

              return false;
            }

            if (One_Click_Migration::$process_backup_single->time_exceeded()) {
                $next_retry_count = get_option('ocm_backup_upload_retry_' . $folder_name, 1);
                if($next_retry_count && $next_retry_count <=2){
                  One_Click_Migration::write_to_log("Process is Restarting");

                  update_option('ocm_backup_upload_retry_' . $folder_name, $next_retry_count, true);
                  One_Click_Migration::$process_backup_single->restart_task();
                }else{

                  One_Click_Migration::write_to_log(sprintf('Skipping ' .$folder_name.  ' Uploading'));
                  self::set_complete_backup_step($folder_name, self::STEP_BACKUP_CHILD_UPLOAD);
                  self::capture_failed_folder($skipped_restore_files, $folder_name, 'ocm_skipped_folders');
                  One_Click_Migration::write_to_log("File $folder_name was skipped due to timeout");
                }

            }
        }
    }

    public static function deleteDir($dir, $action_log, $presigned_urls)
    {
        if (is_dir($dir)) {
            $objects = @scandir($dir);
            if (!$objects) {
                One_Click_Migration::write_to_log(sprintf(
                    '%s. Error: "%s" directory could not be deleted. Please check the write permission for this directory or the parent directory.',
                    $action_log, $dir
                ));

                update_option('ocm_is_stopped', true, true);
                exit;
            }

            foreach ($objects as $object) {
                if ($object !== '.' && $object !== '..') {
                    if (filetype($dir . '/' . $object) === 'dir') {
                        self::deleteDir($dir . '/' . $object, $action_log, $presigned_urls);
                    } else if (!unlink($dir . '/' . $object)) {
                        One_Click_Migration::write_to_log(sprintf(
                            '%s. Error: "%s" file could not be deleted. Please check the write permission for the parent directory.',
                            $action_log, $dir
                        ));

                        update_option('ocm_is_stopped', true, true);
                        exit;
                    }
                }
            }

            reset($objects);
            if (!rmdir($dir)) {
                One_Click_Migration::write_to_log(sprintf(
                    '%s. Error: "%s" directory could not be deleted. Please check the write permission for this directory or the parent directory.',
                    $action_log, $dir
                ));

                update_option('ocm_is_stopped', true, true);
                exit;
            }
        }
    }

    private static function post_restore_cleanup()
    {
        global $wp_rewrite;
        $wp_rewrite->init();

        if (function_exists('save_mod_rewrite_rules')) {
            save_mod_rewrite_rules();
        }
        if (function_exists('iis7_save_url_rewrite_rules')) {
            iis7_save_url_rewrite_rules();
        }
    }

    public static function encryptZipFile($filename, $key)
    {
        // open file to read
        if (false === ($file_handle = fopen($filename, 'rb'))) {
            One_Click_Migration::write_to_log(sprintf('Error: Failed to open file "%s"', $filename));
            return false;
        }

        $encrypted_path = sprintf('%s/encrypted_%s', dirname($filename), basename($filename));
        $data_encrypted = 0;
        $buffer_size = 2097152;
        $file_size = filesize($filename);

        $rijndael = new Rijndael();
        $rijndael->setKey($key);
        $rijndael->disablePadding();
        $rijndael->enableContinuousBuffer();

        // open new file from new path
        if (false === ($encrypted_handle = fopen($encrypted_path, 'wb+'))) {
            One_Click_Migration::write_to_log(sprintf('Error: Failed to open file "%s"'));
            return false;
        }

        // loop around the file
        while ($data_encrypted < $file_size) {


            // read buffer-sized amount from file
            if (false === ($file_part = fread($file_handle, $buffer_size))) {
                One_Click_Migration::write_to_log(sprintf('Error: read buffer-sized amount from file "%s"'));
                return false;
            }

            // check to ensure padding is needed before encryption
            $length = strlen($file_part);
            if (0 !== $length % 16) {
                $pad = 16 - ($length % 16);
                $file_part = str_pad($file_part, $length + $pad, chr($pad));
            }

            $encrypted_data = $rijndael->encrypt($file_part);

            if (false === fwrite($encrypted_handle, $encrypted_data)) {
                One_Click_Migration::write_to_log(sprintf('Error: Encrypted data from file "%s"', $file_part));
                return false;
            }

            $data_encrypted += $buffer_size;
        }

        // close the main file handle
        fclose($encrypted_handle);
        fclose($file_handle);

        // encrypted path
        $result_path = $filename . '.crypt';

        // need to replace original file with tmp file
        if (false === @rename($encrypted_path, $result_path)) {
            One_Click_Migration::write_to_log(sprintf('Error: Failed rename failed: "%s" -> "%s"', $encrypted_path, $result_path));
            return false;
        }

        return $result_path;
    }

    public static function decryptZipFile($filename, $key)
    {
        // open file to read
        if (false === ($file_handle = fopen($filename, 'rb'))) {
            return false;
        }

        $decrypted_path = dirname($filename) . '/decrypt_' . basename($filename);
        // open new file from new path
        if (false === ($decrypted_handle = fopen($decrypted_path, 'wb+'))) {
            return false;
        }

        // setup encryption
        $rijndael = new Rijndael();
        $rijndael->setKey($key);
        $rijndael->disablePadding();
        $rijndael->enableContinuousBuffer();

        $file_size = filesize($filename);
        $bytes_decrypted = 0;
        $buffer_size = 2097152;

        // loop around the file
        while ($bytes_decrypted < $file_size) {
            // read buffer sized amount from file
            if (false === ($file_part = fread($file_handle, $buffer_size))) {
                return false;
            }
            // check to ensure padding is needed before decryption
            $length = strlen($file_part);
            if (0 !== $length % 16) {
                $pad = 16 - ($length % 16);
                $file_part = str_pad($file_part, $length + $pad, chr($pad));
            }

            $decrypted_data = $rijndael->decrypt($file_part);

            $is_last_block = ($bytes_decrypted + strlen($decrypted_data) >= $file_size);

            $write_bytes = min($file_size - $bytes_decrypted, strlen($decrypted_data));
            if ($is_last_block) {
                $is_padding = false;
                $last_byte = ord($decrypted_data[strlen($decrypted_data) - 1]);
                if ($last_byte < 16) {
                    $is_padding = true;
                    for ($j = 1; $j <= $last_byte; $j++) {
                        if ($decrypted_data[strlen($decrypted_data) - $j] !== chr($last_byte)) {
                            $is_padding = false;
                        }
                    }
                }
                if ($is_padding) {
                    $write_bytes -= $last_byte;
                }
            }

            if (false === fwrite($decrypted_handle, $decrypted_data, $write_bytes)) {
                return false;
            }
            $bytes_decrypted += $buffer_size;
        }

        // close the main file handle
        fclose($decrypted_handle);
        // close original file
        fclose($file_handle);

        // remove the crypt extension from the end as this causes issues when opening
        $filename_new = preg_replace('/\.crypt$/', '', $filename, 1);
        // //need to replace original file with tmp file

        $filename_basename = basename($filename_new);

        if (false === @rename($decrypted_path, $filename_new)) {
            return false;
        }

        // need to send back the new decrypted path
        return array(
            'filename' => $filename_new,
            'basename' => $filename_basename
        );
    }

    public static function get_progress()
    {
        $log_url = OCM_DEBUG_LOG_FILE_URL;
        $lastLog = self::get_last_log_message();
        $lastLotNoSkip = self::get_last_log_message(false);

        $uploadFileData = get_option('ocm_upload_file');
        $isStopped = get_option('ocm_is_stopped');

        if ($isStopped) { // cancel all bg process
            One_Click_Migration::cancel_all_process();
        }

        if ($lastLog && $lastLotNoSkip === self::LOG_MESSAGE_BG_PROCESS_RESTARTING_LOG) {

            return [
                'text' => $lastLog,
                'value' => self::get_previous_log_percentage(),
                'customNotice' => self::LOG_MESSAGE_BG_PROCESS_RESTARTING_LOG
            ];
        }

        if ($lastLog && $lastLotNoSkip === self::LOG_MESSAGE_BG_PROCESS_RESTARTING) {

            return [
                'text' => $lastLog,
                'value' => self::get_previous_log_percentage(),

            ];
        }

        if ($lastLog && $lastLotNoSkip === "Process is Restarting") {

            return [
                'text' => 'Process is Restarting',
                'value' => self::get_previous_log_percentage(),

            ];
        }

        if ($lastLog && strpos($lastLog, 'was skipped due to timeout') !== false) {


            return [
                'text' => $lastLog,
                'value' => self::get_previous_log_percentage(),

            ];
        }


        $default_text = array(
            'text' => 'Start a backup or a restore to see current progress here.</br></br>Entire process runs in the background, independent of your browser activity.</br> </br>If you get logged out during restore, log back in using your backup old WordPress credentials and refresh this page for progress.',
            'value' => '0%'
        );


        foreach (self::$progress_data as $item_key => $progress_item) {


            if (isset($progress_item[0]) && strpos($lastLog, $progress_item[0]) !== false) {

                if (preg_match('/Notice:/', $lastLog)) {
                    $progress_item_text = array(
                        'uploadFileData' => $uploadFileData,
                        'text' => $lastLog,
                        'value' => $progress_item[1]
                    );
                } else {
                    $progress_item_text = array(
                        'uploadFileData' => $uploadFileData,
                        'text' => $lastLog,
                        'value' => $progress_item[1]
                    );
                }

                if ($isStopped && $lastLog !== 'Restore completed.') {
                    $progress_item_text = array(
                        'isStopped' => true,
                        'text' => $lastLog,
                        'value' => $progress_item[1]
                    );
                }


                if ($lastLog === 'Downloading restore files. Please wait.') {
                    $option_name = 'ocm_payment_status';
					               update_option($option_name, 'payment_started');
                    $progress_item_text['text'] .= '</br></br>This step could take a while depending on your backup size';

                } elseif ($lastLog === 'Plugins have been restored') {
                    $progress_item_text['text'] .= '</br></br>Around this step you might get logged out. Log back in using your old WordPress credentials and refresh this page for progress updates.';
                } elseif ($lastLog === 'Failed to complete the restore because of payment') {
                    $progress_item_text['text'] .= '</br></br>Restore failed to complete because of failed payment. Please restart the restore process!';
                } elseif ($lastLog === 'Restore completed.') {
                    $progress_item_text['text'] .= '</br></br>Please check you website. If you have any issues please check our <a target="_blank" href="https://1clickmigration.com/faq/">FAQ page</a> or contact use via <a target="_blank" href="https://1clickmigration.com/contact-us/">this Contact Form</a>.</br>If you like this plugin please leave us a <a target="_blank" href="https://wordpress.org/plugins/1-click-migration/#reviews">review</a>.';
                }
                return $progress_item_text;
            }
        }

        return $default_text;
    }

    private static function get_percent_by_log_message($log)
    {

        $return = false;

        foreach (self::$progress_data as $item) {


            list($template, $percent) = $item;
            if (strpos($log, $template) !== false) {
                $return = $percent;
                break;
            }
        }

        return $return;
    }

    private static function folderSize($dir)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/') . '/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : self::folderSize($each);
        }
        return $size;
    }

    public static function convertToReadableSize($size)
    {
        $decimals = 1;
        $sizes = array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
        $factor = floor((strlen($size) - 1) / 3);
        return sprintf("%.{$decimals}f", $size / (1024 ** $factor)) . @$sizes[$factor];
    }

    private static function DBSize () {
		global $wpdb;
		$db_size_obj = $wpdb->get_results ('
		SELECT ROUND(SUM(data_length + index_length) / 1024 / 1024, 1) "db_size"
		FROM information_schema.tables
		WHERE table_schema = "'.DB_NAME.'"
		GROUP BY table_schema
		');
		foreach ($db_size_obj as $db_size)
			{
			return $db_size->db_size;
			}
	}

    private static function save_system_data_to_log_file($username, $bucket_key)
    {
        global $wp_version;

        One_Click_Migration::write_to_log("Email address: $username");
        One_Click_Migration::write_to_log('Site URL: ' . get_site_url());
        One_Click_Migration::write_to_log("WP Version: $wp_version");
        One_Click_Migration::write_to_log('PHP Version: ' . PHP_VERSION);
        One_Click_Migration::write_to_log('OCM Plugin Version: ' . One_Click_Migration::get_version());
        One_Click_Migration::write_to_log('default max_execution_time: ' . One_Click_Migration::get_default_max_execution_time());
        One_Click_Migration::write_to_log('Available Space: ' . self::convertToReadableSize (disk_free_space(".")));
        One_Click_Migration::write_to_log('max_execution_time: ' . One_Click_Migration::get_current_max_execution_time());
        One_Click_Migration::write_to_log('timeout set: ' . One_Click_Migration::get_timeout());
        One_Click_Migration::write_to_log(sprintf('proc_open / proc_close: %s', OCM_SQL_Utils::check_proc_available(true) ? 'enabled' : 'disabled'));

        One_Click_Migration::write_to_log("Backup ID: $bucket_key");

        $host = gethostname();
        $ip = gethostbyname($host);

        One_Click_Migration::write_to_log("Current IP Address: $ip");
        self::set_server_settings();

    }

    private static function set_server_settings(){
      if (!defined('MB_IN_BYTES')) { define('MB_IN_BYTES', 1024 * KB_IN_BYTES); }
      if (!defined('OCM_PHP_MAX_MEMORY')) { define('OCM_PHP_MAX_MEMORY', 4096 * MB_IN_BYTES); }
      if (!function_exists('wp_is_ini_value_changeable')) {
          /**
          * Determines whether a PHP ini value is changeable at runtime.
          *
          * @staticvar array $ini_all
          *
          * @link https://secure.php.net/manual/en/function.ini-get-all.php
          *
          * @param string $setting The name of the ini setting to check.
          * @return bool True if the value is changeable at runtime. False otherwise.
          */
          function wp_is_ini_value_changeable( $setting ) {
              static $ini_all;
              if ( ! isset( $ini_all ) ) {
                  $ini_all = false;
                  // Sometimes `ini_get_all()` is disabled via the `disable_functions` option for "security purposes".
                  if ( function_exists( 'ini_get_all' ) ) {
                      $ini_all = ini_get_all();
                  }
              }

              // Bit operator to workaround https://bugs.php.net/bug.php?id=44936 which changes access level to 63 in PHP 5.2.6 - 5.2.17.
              if ( isset( $ini_all[ $setting ]['access'] ) && ( INI_ALL === ( $ini_all[ $setting ]['access'] & 7 ) || INI_USER === ( $ini_all[ $setting ]['access'] & 7 ) ) ) {
                  return true;
              }

              // If we were unable to retrieve the details, fail gracefully to assume it's changeable.
              if ( ! is_array( $ini_all ) ) {
                  return true;
              }

              return false;
          }
      }


      if (wp_is_ini_value_changeable('memory_limit'))
          @ini_set('memory_limit', OCM_PHP_MAX_MEMORY);
      if (wp_is_ini_value_changeable('pcre.backtrack_limit'))
          @ini_set('pcre.backtrack_limit', PHP_INT_MAX);
      if (wp_is_ini_value_changeable('default_socket_timeout'))
          @ini_set('default_socket_timeout', 3600);
    }

    private static function get_excluded_folders()
    {
        $excluded_folders = '';

        if(isset($_GET['selected'])){
          $excluded = sanitize_text_field($_GET['selected']);;
          $excluded_folders = explode(",", $excluded);
          update_option( 'ocm_excluded_folders', $excluded_folders);
        }

        return $excluded_folders;
    }

    private static function get_username()
    {
        $username = '';
        if(isset($_GET['username'])){
          $username = sanitize_email($_GET['username']);

          return $username;

        }else{
          wp_safe_redirect(admin_url('tools.php?page=one-click-migration'));
          exit;
        }

    }

    private static function get_password()
    {
        $password = '';
        if(isset($_GET['password'])){
          $password = sanitize_key($_GET['password']);
          if (strlen($password) < 4) {
              wp_safe_redirect(admin_url('tools.php?page=one-click-migration'));
              exit;
          }

          return $password;
        }else{
          wp_safe_redirect(admin_url('tools.php?page=one-click-migration'));
          exit;
        }

    }

    private static function get_bucket_key($username, $password)
    {
        $hash = md5($username . $password);
        return filter_var($hash, FILTER_SANITIZE_STRING);
    }

    private static function get_restore_steps_template()
    {
        $child_steps = array(
            self::STEP_RESTORE_CHILD_INITIATE => false,
            self::STEP_RESTORE_CHILD_DOWNLOAD => false,
            self::STEP_RESTORE_CHILD_DOWNLOAD_TMP_PATH => null,
            self::STEP_RESTORE_CHILD_PRESIGNED_URLS => null,
            self::STEP_RESTORE_CHILD_DECRYPT => false,
            self::STEP_RESTORE_CHILD_EXTRACT => false,
            self::STEP_RESTORE_CHILD_DELETE_OLD_AND_MOVE_NEW => false,
            self::STEP_RESTORE_CHILD_THEMES_UPLOADS_RESTORED => false,
            self::STEP_RESTORE_CHILD_RESTORE => false,
            self::STEP_RESTORE_CHILD_THEMES_RESTORED => false,
            self::STEP_RESTORE_CHILD_UPLOADS_RESTORED => false,
        );

        return array(
            'init' => $child_steps,
            'db' => $child_steps,
            'uploads' => $child_steps,
            'themes' => $child_steps,
            'plugins' => $child_steps,
            'themesUploads' => $child_steps
        );
    }

    public static function get_current_restore_steps()
    {
        $restore_steps = get_option('restore_steps', null);
        if (null === $restore_steps) {
            $restore_steps = self::get_restore_steps_template();
        }

        return $restore_steps;
    }

    public static function reset_current_restore_steps()
    {
        $restore_steps = self::get_restore_steps_template();
        update_option('restore_steps', $restore_steps, true);
    }

    public static function check_is_complete_restore_step($step, $child)
    {
        $restore_steps = self::get_current_restore_steps();
        return $restore_steps[strtolower($step)][$child] === true;
    }

    private static function get_restore_step_value($step, $child)
    {
        $restore_steps = self::get_current_restore_steps();
        return $restore_steps[strtolower($step)][$child];
    }

    private static function set_complete_restore_step($step, $child, $value = null)
    {
        $restore_steps = self::get_current_restore_steps();
        $restore_steps[strtolower($step)][$child] = (null !== $value ? $value : true);

        update_option('restore_steps', $restore_steps, true);
    }

    private static function get_backup_steps_template()
    {
        $child_steps = array(
            self::STEP_BACKUP_CHILD_URL_GENERATED => false,
            self::STEP_BACKUP_CHILD_INITIATE => false,
            self::STEP_BACKUP_CHILD_INITIATE_DB_BACKUP => false,
            self::STEP_BACKUP_CHILD_INITIATE_BACKUP_THEMES => false,
            self::STEP_BACKUP_CHILD_INITIATE_BACKUP_PLUGINS => false,
            self::STEP_BACKUP_CHILD_INITIATE_BACKUP_UPLOADS => false,

            self::STEP_BACKUP_CHILD_COMPRESS => false,
            self::STEP_BACKUP_CHILD_ENCRYPT => false,
            self::STEP_BACKUP_CHILD_UPLOAD => false,
        );

        return array(
            'init' => $child_steps,
            'url' => $child_steps,
            'db' => $child_steps,
            'themes' => $child_steps,
            'plugins' => $child_steps,
            'uploads' => $child_steps,
        );
    }

    public static function get_current_backup_steps()
    {
        $backup_steps = get_option('backup_steps', null);
        if (null === $backup_steps) {
            $backup_steps = self::get_backup_steps_template();
        }

        return $backup_steps;
    }

    public static function reset_current_backup_steps()
    {
        $backup_steps = self::get_backup_steps_template();
        update_option('backup_steps', $backup_steps, true);
    }

    private static function check_is_complete_backup_step($step, $child)
    {
        $backup_steps = self::get_current_backup_steps();
        return $backup_steps[strtolower($step)][$child] === true;
    }

    private static function get_backup_step_value($step, $child)
    {
        $backup_steps = self::get_current_backup_steps();
        return $backup_steps[strtolower($step)][$child];
    }

    public static function set_complete_backup_step($step, $child, $value = null)
    {
        $backup_steps = self::get_current_backup_steps();
        $backup_steps[strtolower($step)][$child] = (null !== $value ? $value : true);

        update_option('backup_steps', $backup_steps, true);
    }

    public static function unset_empty_log_line($lines){
      foreach ($lines as $key => $line) {
        if(empty($line)){
          unset($lines[$key]);
        }

        $line_arr = explode("-", $line);
        $date_string = $line_arr[0];

        $is_date = OCM_Backup::validate_date($date_string);
        if(!$is_date){
          unset($lines[$key]);

        }

      }

      return $lines;
    }

    public static function validate_date($date_string){
      $timestamp = strtotime($date_string);

      return $timestamp ? true : false;
    }

    public static function get_last_log_message($skip = true)
    {
        $file = OCM_DEBUG_LOG_FILE;
        $contents = null;
        if (file_exists($file)) {
            $contents = file_get_contents($file);
        } else {
            return false;
        }

        $lastLine = '';
        $lines = array_reverse(explode(PHP_EOL, $contents));
        $lines = OCM_Backup::unset_empty_log_line($lines);

        foreach ($lines as $key => $line) {


            $line_array = explode('- ', $line);

            $line = $line_array[count($line_array) - 1];

            if (empty(trim($line))) {
                continue;
            }

            if ($skip && in_array($line, self::$skipMessages, true)) {
                continue;
            }

            if ( (strpos($line,'SYSLOG') !== false) && (strpos($line,'SYSLOG: Table being backed up') === false) ) { //Skip SYSLOG messages.
              if((strpos($line,'SYSLOG: "[PHP ERR][FATAL]') === false)){
                  continue;
              }
            }

            $lastLine = $line;
            break;
        }

        return $lastLine;
    }

    public static function get_previous_log_percentage(){
      $file = OCM_DEBUG_LOG_FILE;
      $contents = null;
      if (file_exists($file)) {
          $contents = file_get_contents($file);
      } else {
          return false;
      }

      $lastLine = '';
      $lines = array_reverse(explode(PHP_EOL, $contents));

      foreach ($lines as $key => $line) {

          $line_array = explode('- ', $line);


          $line = $line_array[count($line_array) - 1];
          if (empty(trim($line))) {
              continue;
          }

          if ($line === self::LOG_MESSAGE_BG_PROCESS_RESTARTING) {
            $line = $lines[$key+2];
          }

          if ($line === self::LOG_MESSAGE_BG_PROCESS_RESTARTING_LOG) {
            $line = $lines[$key+3];
          }

          if($line === "Process is Restarting"){

            $line = $lines[$key+1];


          }
          $lastLine = $line;
          break;

      }
      $percentage = self::get_percent_by_log_message($lastLine);

      return $percentage;

    }

    private static function restore_db($presigned_urls, wpdb $wpdb)
    {

        OCM_SQL_Utils::check_proc_available(false, $presigned_urls);

        // Get current site URL here
        $current_url = site_url();
        $dbfiles = WP_CONTENT_DIR . '/ocm_restore/db/';
        $excluded_folders = get_option( 'ocm_excluded_folders', []);



        //cater for the database restore.
        if(!in_array('db', $excluded_folders)){

          if (!is_dir($dbfiles)) {
              One_Click_Migration::write_to_log(sprintf('Error: DB directory "%s" does not exist in unzipped files.', $dbfiles));
              die();
          }
        }

        $tables = $wpdb->get_results('SHOW TABLES');
        foreach ($tables as $table) {
            foreach ($table as $table_name) {
                $sql = 'DROP TABLE ' . $table_name;
                $wpdb->get_results($sql);
            }
        }

        $dir = new DirectoryIterator($dbfiles);
        $found_files = array();
        foreach ($dir as $fileinfo) {
            if (!$fileinfo->isDot()) {
                $found_files[] = $fileinfo->getPathname();
            }
        }

        // Give priority to WP tables so they're imported first
        $priority = array(
            'options.sql',
            'users.sql',
            'links.sql',
            'commentmeta.sql',
            'term_relationships.sql',
            'postmeta.sql',
            'posts.sql',
            'term_taxonomy.sql',
            'usermeta.sql',
            'terms.sql',
            'comments.sql',
            'termmeta.sql',
        );

        $result = array();
        foreach ($priority as $p) {
            $result = array_unique(array_merge($result, preg_grep('/' . $p . '/i', $found_files)));
        }

        $sorted_table_list = array_unique(array_merge($result, $found_files));

        sleep(1);

        foreach ($sorted_table_list as $filepath) {
            OCM_SQL_Utils::swap_table_prefix($filepath);
            OCM_SQL_Utils::import($filepath);

            if (strpos($filepath, 'options.sql') !== false) {
                $db_url = $wpdb->get_results("SELECT option_value FROM {$wpdb->prefix}options WHERE option_name = 'siteurl'");

                $table_name = "{$wpdb->prefix}options";

                if ($current_url !== $db_url) {
                    $wpdb->update(
                        $table_name,
                        array('option_value' => $current_url),
                        array('option_name' => 'siteurl'),
                        array('%s'),
                        array('%s')
                    );

                    $wpdb->update(
                        $table_name,
                        array('option_value' => $current_url),
                        array('option_name' => 'home'),
                        array('%s'),
                        array('%s')
                    );

                  self::$restore_site_url = $db_url[0]->option_value;
                }
            }
        }
        One_Click_Migration::write_to_log('Database has been restored');
    }

    /**
     * @param $presigned_urls
     */
    private static function restore_plugins($presigned_urls)
    {
        // Handle plugin extraction
        $plugins = glob(WP_CONTENT_DIR . '/plugins/*');

        // Loop through the list and delete every plugin directory except 1-click-migration
        foreach ($plugins as $plugin) {
            if ($plugin === WP_CONTENT_DIR . '/plugins/1-click-migration') {
                continue;
            }

            self::deleteDir($plugin, 'Restore plugins', $presigned_urls);
        }

        // Move every plugin from the restore directory one by one in to the existing plugins directory
        $restore_plugins = glob(WP_CONTENT_DIR . '/ocm_restore/plugins/*');
        foreach ($restore_plugins as $plugin) {
            if ($plugin === WP_CONTENT_DIR . '/ocm_restore/plugins/1-click-migration') {
                continue;
            }

            $directory_path_split = explode('/', $plugin);
            $plugin_name = $directory_path_split[count($directory_path_split) - 1];
            $pluginDirectoryName = WP_CONTENT_DIR . '/plugins/' . $plugin_name;

            if (!@rename($plugin, $pluginDirectoryName)) {
                One_Click_Migration::write_to_log(sprintf(
                    'Restore plugins. Error: "%s" directory could not be moved to "%s" directory. Please check the write permission for these directories.',
                    $plugin, $pluginDirectoryName
                ));
                die();
            }
        }

        One_Click_Migration::write_to_log('Plugins have been restored');
    }

    public static function add_file_not_found_notice(){
      $file = OCM_DEBUG_LOG_FILE;
      $lines = [];

    }
    private static function substring_in_array($needle, array $haystack)
    {
      $filtered = array_filter($haystack, function ($item) use ($needle) {
          return false !== strpos($item, $needle);
      });

      return !empty($filtered);
    }

    public static function restart_failed_process(){
      if(isset($_POST['process'])){
        $process = $_POST['process'];

        One_Click_Migration::write_to_log("Process is Restarting");
        if($process == 'backup'){
          One_Click_Migration::$process_backup_single->restart_task();
        }

        if($process == 'restore'){
          One_Click_Migration::$process_restore_single->restart_task();
        }

      }
      wp_send_json_success("success");

    }


    public static function delete_restore_options(){
      delete_option('wp_force_deactivated_plugins');

      delete_option('ocm_restore_download_retry_db');
      delete_option('ocm_restore_download_retry_themes');
      delete_option('ocm_restore_download_retry_plugins');
      delete_option('ocm_restore_download_retry_uploads');

      delete_option('ocm_restore_decrypt_retry_db');
      delete_option('ocm_restore_decrypt_retry_themes');
      delete_option('ocm_restore_decrypt_retry_plugins');
      delete_option('ocm_restore_decrypt_retry_uploads');

      delete_option('ocm_restore_extract_retry_db');
      delete_option('ocm_restore_extract_retry_themes');
      delete_option('ocm_restore_extract_retry_plugins');
      delete_option('ocm_restore_extract_retry_uploads');

      delete_option('ocm_restore_child_delete_retry_db');
      delete_option('ocm_restore_child_delete_retry_themes');
      delete_option('ocm_restore_child_delete_retry_plugins');
      delete_option('ocm_restore_child_delete_retry_uploads');
      delete_option('ocm_excluded_folders');
      delete_option('ocm_skipped_folders');
      delete_option('ocm_eexcluded_folders');

    }

    public static function delete_backup_options(){
      // Clean up the setting, if there's an ocm_key in the table
      delete_option('wp_force_deactivated_plugins');

      delete_option('ocm_backup_compress_retry_db');
      delete_option('ocm_backup_compress_retry_themes');
      delete_option('ocm_backup_compress_retry_plugins');
      delete_option('ocm_backup_compress_retry_uploads');

      delete_option('ocm_backup_encrypt_retry_db');
      delete_option('ocm_backup_encrypt_retry_themes');
      delete_option('ocm_backup_encrypt_retry_plugins');
      delete_option('ocm_backup_encrypt_retry_uploads');

      delete_option('ocm_backup_upload_retry_db');
      delete_option('ocm_backup_upload_retry_themes');
      delete_option('ocm_backup_upload_retry_plugins');
      delete_option('ocm_backup_upload_retry_uploads');
      delete_option('ocm_excluded_folders');
      delete_option('ocm_skipped_folders');
      delete_option('ocm_eexcluded_folders');


    }

    public static function capture_failed_folder($excluded_restore_files, $key, $option){

      array_push($excluded_restore_files, $key);
      $excluded_restore_files = array_unique($excluded_restore_files);
      update_option( $option, $excluded_restore_files);
    }

    public static function print_folders_not_found(){
      $not_found_folders = get_option("ocm_eexcluded_folders", []);


      foreach ($not_found_folders as $key => $not_found_folder) {

        // One_Click_Migration::write_to_log("Notice: File " . $not_found_folder. ".zip.crypt was not found on the remote server. Please try to back it up again.");

      }

    }

    public static function print_folders_skipped(){
      $skipped_folders = get_option("ocm_skipped_folders", []);
      foreach ($skipped_folders as $key => $skipped_folder) {
        One_Click_Migration::write_to_log("File $skipped_folder was skipped due to timeout");

      }

      return true;

    }

}
