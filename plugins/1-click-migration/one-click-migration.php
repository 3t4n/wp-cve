<?php

/**
 * Plugin Name: 1 Click Migration
 * Plugin URI: https://wordpress.org/plugins/1-click-migration/
 * Description: Migrate, copy, or clone your entire site with 1 click. <strong>Any host, no size limitation, no premium versions.</strong>
 * Version: 2.1
 * Author: 1ClickMigration
 * Author URI: https://1clickmigration.com/
 * Text Domain: 1-click-migration
 */

namespace OCM;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

if (!defined('WPINC')) {
    die;
}

define('OCM_PLUGIN_ROOT', plugin_dir_path(__FILE__));
define('OCM_PLUGIN_WRITABLE_PATH', WP_CONTENT_DIR . '/tmp/');
define('OCM_MAIN_FILE', __FILE__);
define('OCM_SET_TIME_LIMIT', 900);

define('OCM_DEBUG_LOG_FILENAME', 'ocm_debug.log');
define('OCM_DEBUG_LOG_FILE', OCM_PLUGIN_ROOT . OCM_DEBUG_LOG_FILENAME);
define('OCM_DEBUG_LOG_FILE_URL', plugins_url(OCM_DEBUG_LOG_FILENAME, OCM_MAIN_FILE));
define('OCM_WP_CONFIG_FILE', ABSPATH . 'wp-config.php');
define('OCM_API_ENDPOINT', 'https://1clickmigration.com/api/');

require 'vendor/autoload.php';

require_once __DIR__ . '/inc/admin/class-admin-page.php';
require_once __DIR__ . '/inc/backup/class-ocm-backup.php';
require_once __DIR__ . '/inc/s3/class-ocm-s3.php';
require_once __DIR__ . '/inc/db/class-ocm-db-import.php';
require_once __DIR__ . '/inc/db/class-ocm-search-replace-db.php';
require_once __DIR__ . '/inc/db/class-ocm-db.php';
require_once __DIR__ . '/inc/handler/class-error-handler.php';

// If the function it's not available, require it.
if ( ! function_exists( 'download_url' ) ) {
    require_once ABSPATH . 'wp-admin/includes/file.php';
}

class One_Click_Migration
{
    /** @var OCM_BackgroundBackup */
    public static $process_backup_single;

    /**@var OCM_BackgroundRestore */
    public static $process_restore_single;

    /** @var string */
    private static $version;

    /** @var int */
    private static $default_max_execution_time;

    /** @var int */
    private static $current_max_execution_time;

    private static $error_handler;

    public static $is_stop_and_reset;

    public function __construct()
    {
        $this->fire_hooks();
        $this->get_version_from_php_doc();

        register_activation_hook(__FILE__, array($this, 'install'));
        register_deactivation_hook(__FILE__, array($this, 'uninstall'));

        self::set_current_max_execution_time();

    }

    public function fire_hooks()
    {
        add_action('admin_menu', array(OCM_Admin_Page::class, 'add_admin_page'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_styles'));
        add_action('admin_post_start_backup', array(OCM_Backup::class, 'start_backup'));
        add_action('wp_ajax_ocm_restart_failed_process', array(OCM_Backup::class, 'restart_failed_process'));
        add_action('admin_post_start_restore', array(OCM_Backup::class, 'start_restore'));
        add_action('admin_post_cancel_actions', array(OCM_Backup::class, 'cancel_actions'));
        add_action('plugins_loaded', array(__CLASS__, 'init_background_jobs'));
        add_action('plugins_loaded', array(__CLASS__, 'init_handler'));
        add_action('rest_api_init', static function () {
            register_rest_route('ocm/v1', '/progress/', array(
                'methods' => 'GET',
                'callback' => array(One_Click_Migration::class, 'progress_endpoint'),
            ));
        });

        add_action('rest_api_init', static function () {
            register_rest_route('ocm/v1', '/bucket_exists/', array(
                'methods' => 'GET',
                'callback' => array(OCM_S3::class, 'bucket_exists'),
            ));
        });

        add_action('wp_ajax_ocm_make_payment', array(OCM_Admin_Page::class, 'submit_paypal_payment'));
        add_action( 'admin_notices', array(OCM_Backup::class, 'add_file_not_found_notice'));

    }

    public function get_version_from_php_doc()
    {
        if ($fp = fopen(__FILE__, 'rb')) {
            $file_data = fread($fp, 1024);
            if (preg_match("/Version: ([\d.]+)(\r|\n)/", $file_data, $matches)) {
                self::$version = $matches[1];
            }
            fclose($fp);
        }
    }

    public static function get_version()
    {
        return self::$version;
    }

    public static function init_background_jobs()
    {
        require_once __DIR__ . '/inc/background/class-background-backup.php';
        require_once __DIR__ . '/inc/background/class-background-restore.php';
        require_once __DIR__ . '/inc/background/class-background-helper.php';

        self::$process_backup_single = new OCM_BackgroundBackup();
        self::$process_restore_single = new OCM_BackgroundRestore();
    }

    public static function init_handler(){

      Error_Handler::init_error_handler();
    }

    public static function register_settings()
    {
        register_setting('one-click-migration', 'ocm_user_email');
        register_setting('one-click-migration', 'ocm_user_password');

        add_settings_section(
            'ocm_key_section',
            __('Easiest to use migration plugin', '1click-migration'),
            array(__CLASS__, 'ocm_key_section_display'),
            'one-click-migration'
        );

        add_settings_section(
            'ocm_backup_restore_section',
            __('Backup & Restore', '1click-migration'),
            array(__CLASS__, 'render_backup_restore_section'),
            'one-click-migration'
        );

    }

    public static function render_backup_restore_section(){
      add_settings_field('ocm_user_email', __('Email', '1click-migration'), array(__CLASS__, 'ocm_email_field_display'), 'one-click-migration', 'ocm_backup_restore_section', [
          'label_for' => 'ocm_user_email',
          'class' => 'ocm-user-email',
      ]);

      add_settings_field('ocm_user_password', __('Password', '1click-migration'), array(__CLASS__, 'ocm_password_field_display'), 'one-click-migration', 'ocm_backup_restore_section', [
          'label_for' => 'ocm_user_password',
          'class' => 'ocm-user-password',
      ]);

      add_settings_field('ocm_start_action', '', array(__CLASS__, 'ocm_start_action_field_display'), 'one-click-migration', 'ocm_backup_restore_section', [
          'label_for' => 'ocm_start_action',
          'class' => 'hidden',
      ]);

    }

    public static function admin_styles($hook)
    {
        if ($hook !== 'tools_page_one-click-migration') {
            return;
        }

        $paypal_args = array(
			"client-id"=>"AWSeMMPXTkPFkf_ogsDt63v0ogN0ve4QcAoqdBvxUrU_t7ZFmAl0yO1bV0-6gRfCYTxihJJeFqCrJtw4",
			"currency"=> "USD"
        );

        wp_enqueue_style( 'jquery-ui-style', plugins_url('css/jquery-ui.css', __FILE__), array(),null);
        wp_enqueue_style('multiselect-css', plugins_url('css/multiselect.css', __FILE__), array('jquery-ui-style'));
        wp_enqueue_style('custom_ocm_admin_css', plugins_url('css/admin-style.css', __FILE__), array('multiselect-css'), self::$version);

        wp_enqueue_script('ocm_admin_md5', plugins_url('js/jquery.md5.min.js', __FILE__), array('jquery'), self::$version);
        wp_enqueue_script( 'ocm_paypal_js', add_query_arg( $paypal_args, 'https://www.paypal.com/sdk/js'), array(),null);
        wp_enqueue_script('jquery-ui-js', plugins_url('js/jquery-ui.js', __FILE__), ['jquery']);
        wp_enqueue_script('custom_ocm_admin_js', plugins_url('js/admin-script.js', __FILE__), array('jquery','jquery-ui-core','ocm_paypal_js','jquery-ui-dialog','ocm_admin_md5', 'jquery-ui-js'), self::$version);
        $ocm_user_email = get_option('ocm_user_email');
        $user_email = '';
        $urlparts = parse_url(home_url());
		$domain = $urlparts['host'];

        if($ocm_user_email){
          $user_email = $ocm_user_email;
        }
        $timeout = One_Click_Migration::get_timeout();

        wp_localize_script('custom_ocm_admin_js', 'siteData', array(
            'progressUrl' => get_rest_url(null, 'ocm/v1/progress'),
            'bucketExistsUrl' => get_rest_url(null, 'ocm/v1/bucket_exists'),
            'priceAPIEndpoint' => 'https://1clickmigration.com/api/',
			      'defaultPrice' => 3.99,
            'userEmail' => $user_email,
            'domain' => $domain,
            'timeout' => $timeout,
            'ajaxurl' => admin_url('admin-ajax.php')
        ));
        wp_enqueue_script('multiselect-js', plugins_url('js/multiselect.min.js', __FILE__), ['jquery','custom_ocm_admin_js']);

    }

    public static function ocm_key_section_display($args)
    {

        require_once __DIR__ . '/templates/admin/instruction-box.php';


    }

    public static function ocm_email_field_display($args)
    {
        $label = esc_attr($args['label_for']);
        ?>
        <span class="ocm-settings-email-icon"></span>
        <input type="text" placeholder="Email" value="" id="<?php echo $label ?>" name="<?php echo $label ?>"
               style="width: 100%; max-width: 350px">
        <?php
    }

    public static function ocm_password_field_display($args)
    {
        $label = esc_attr($args['label_for']);
        ?>
        <span class="ocm-settings-pass-icon"></span>
        <input type="password" value="" placeholder="Password" id="<?php echo $label; ?>" name="<?php echo $label; ?>"
               style="width: 100%; max-width: 350px">
        <?php
    }

    public static function ocm_start_action_field_display($args)
    {
        $label = esc_attr($args['label_for']);
        $ocm_action_start_backup = get_option('ocm_action_start_backup');
        $ocm_action_start_restore = get_option('ocm_action_start_restore');

        if ($ocm_action_start_backup) {
            $value = 'backup';
        } else {
            $value = ($ocm_action_start_restore ? 'restore' : '');
        }
        ?>
        <input type="hidden" value="<?php echo esc_attr($value) ?>" id="ocmStartAction" name="<?php echo $label ?>">
        <?php
    }

    public static function install()
    {
        // Make sure our API route is added
        flush_rewrite_rules();
    }

    public static function delete_options(){
      // Clean up the setting, if there's an ocm_key in the table
      delete_option('ocm_user_email');
      delete_option('ocm_user_password');
      delete_option('ocm_is_stopped');
      delete_option('ocm_action_start_backup');
      delete_option('ocm_action_start_restore');
      delete_option('backup_steps');
      delete_option('restore_steps');
      delete_option('ocm_presigned_urls');
      delete_option('ocm_upload_file');
      delete_option('ocm_download_auto_try_nb_db');
      delete_option('ocm_download_auto_try_nb_themes');
      delete_option('ocm_download_auto_try_nb_plugins');
      delete_option('ocm_download_auto_try_nb_uploads');
      delete_option('ocm_upload_auto_try_nb_db');
      delete_option('ocm_upload_auto_try_nb_themes');
      delete_option('ocm_upload_auto_try_nb_plugins');
      delete_option('ocm_upload_auto_try_nb_uploads');
      delete_option('ocm_payment_status');
      delete_option('ocm_excluded_folders');
      delete_option('ocm_skipped_folders');
      delete_option('ocm_log_download');
      delete_option('ocm_log_url');
      delete_option('ocm_bucket_key');
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

    }


    public static function uninstall()
    {
	    self::delete_options();
        // Delete ocm_restore folder
        if (is_dir(WP_CONTENT_DIR . '/ocm_restore/')) {
            OCM_Backup::deleteDir(WP_CONTENT_DIR . '/ocm_restore/', 'Deleting ocm_restore', null);
        }

        // Clear tmp folder
        if (is_dir(OCM_PLUGIN_WRITABLE_PATH)) {
            OCM_Backup::deleteDir(OCM_PLUGIN_WRITABLE_PATH, 'Deleting TMP Folder', null);
        }
        OCM_BackgroundHelper::delete_all_batch_process();

        // Make sure we don't leave un-necessary routes laying around
        flush_rewrite_rules();

    }


    public static function write_to_log($string)
    {
      // sleep(1);
      $file = OCM_DEBUG_LOG_FILE;
      $urls = get_option('ocm_presigned_urls');

      if (preg_match('/Error:/', $string)) {
          self::reset_actions_start_mark();
      }

      if($urls){

        // If ocm_debug doesn't exist, make sure to grab the latest from S3 so we can append
        if (isset($urls->log_download) && !file_exists($file)) {
            $file_path = fopen($file, 'wb');
            $client = new Client();
            $response = $client->get($urls->log_download, ['http_errors' => false]);

            if ($response->getStatusCode() !== 404) {
                $client->get($urls->log_download, ['save_to' => $file_path]);
            }
        }

        $timestamp = date('m/d/YTH:i:sa');
        $string = $timestamp . ' - ' . $string;

        $log_file = fopen($file, 'ab');
        fwrite($log_file, $string . PHP_EOL);
        fclose($log_file);

        try {
            $request = new Request(
                'PUT',
                $urls->log,
                [],
                file_get_contents($file)
            );

            $client = new Client();
            $client->send($request);
        } catch (Exception $e) {
            if ($e->getCode() === 404) {
                self::delete_log_file();
                wp_safe_redirect(admin_url('tools.php?page=one-click-migration&message=no_bucket'));
                exit;
            }
        }catch(ClientException $e){

          }

      }

      if($string === "Stop & Reset Finished"){
        $timestamp = date('m/d/YTH:i:sa');
        $string = $timestamp . ' - ' . $string;
        $log_file = fopen($file, 'ab');
        fwrite($log_file, $string . PHP_EOL);
        fclose($log_file);

      }

    }

    public static function set_default_max_execution_time($v)
    {
        self::$default_max_execution_time = (int) $v;
    }

    public static function get_default_max_execution_time()
    {
        return self::$default_max_execution_time;
    }

    public static function set_current_max_execution_time()
    {
        self::set_default_max_execution_time(ini_get('max_execution_time'));
        $isApache = extension_loaded('apache2handler') || (strpos(getenv('SERVER_SOFTWARE'),'Apache') !== false);
        if ($isApache) {
           if ( (int) ini_get('max_execution_time') < 300 ) @set_time_limit(OCM_SET_TIME_LIMIT);
        }
        else if ( (int) ini_get('max_execution_time') < 60 ) @set_time_limit( 60 );
        self::$current_max_execution_time = (int) ini_get('max_execution_time');
    }

    public static function get_current_max_execution_time()
    {
        return self::$current_max_execution_time;
    }

    public static function get_timeout()
    {
        return (self::get_current_max_execution_time()-5) > 0 ? self::get_current_max_execution_time()-5 : OCM_SET_TIME_LIMIT;
    }

    public static function progress_endpoint()
    {
        return OCM_Backup::get_progress();
    }

    public static function reset_actions_start_mark()
    {
        update_option('ocm_action_start_backup', false);
        update_option('ocm_action_start_restore', false);
    }

    public static function cancel_all_process()
    {
        OCM_BackgroundHelper::delete_all_batch_process();
        self::$process_backup_single->cancel_all_process();
        self::$process_restore_single->cancel_all_process();

    }

    public static function stop_and_reset()
    {
        One_Click_Migration::write_to_log("stop and reset started");

        self::delete_options();

        // Delete ocm_restore folder
        if(!WP_DEBUG){

          if (is_dir(WP_CONTENT_DIR . '/ocm_restore/')) {
              OCM_Backup::deleteDir(WP_CONTENT_DIR . '/ocm_restore/', 'Deleting ocm_restore', null);
          }
        }

        // Clear tmp folder
        if (is_dir(OCM_PLUGIN_WRITABLE_PATH)) {
            OCM_Backup::deleteDir(OCM_PLUGIN_WRITABLE_PATH, 'Deleting TMP Folder', null);
        }
        OCM_BackgroundHelper::delete_all_batch_process();

        // Make sure we don't leave un-necessary routes laying around
        flush_rewrite_rules();

        One_Click_Migration::write_to_log("Stop & Reset Finished");

    }


    public static function clear_log_file(){
      $file = OCM_DEBUG_LOG_FILE;
      if (file_exists($file)) {
        $fh = fopen($file, 'w');
        $file_content = "";
        fwrite($fh, $file_content);
        fclose($fh);
      }

    }


    public static function delete_log_file()
    {
        $file = OCM_DEBUG_LOG_FILE;
        if (file_exists($file)) {
            unlink($file);
        }
    }
}

new One_Click_Migration();

add_filter('plugin_action_links_' . plugin_basename(__FILE__), static function ($links) {
    $links[] = '<a href="' .
        admin_url('tools.php?page=one-click-migration') .
        '">' . __('Settings') . '</a>';
    return $links;
});
