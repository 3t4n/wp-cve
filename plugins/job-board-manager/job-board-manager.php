<?php
/*
Plugin Name: Job Board Manager
Plugin URI: https://www.pickplugins.com/item/job-board-manager-create-job-site-for-wordpress/?ref=dashboard
Description: Advance job board manager for your site.
Version: 2.1.56
Author: PickPlugins
Text Domain: job-board-manager
Domain Path: /languages
Author URI: http://pickplugins.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if (!defined('ABSPATH')) exit;  // if direct access 

class JobBoardManager
{

    public function __construct()
    {

        define('job_bm_plugin_url', plugins_url('/', __FILE__));
        define('job_bm_plugin_dir', plugin_dir_path(__FILE__));
        define('job_bm_wp_url', 'https://wordpress.org/plugins/job-board-manager/');
        define('job_bm_pro_url', 'https://www.pickplugins.com/item/job-board-manager-create-job-site-for-wordpress/?ref=dashboard');
        define('job_bm_demo_url', 'https://www.pickplugins.com/demo/job-board-manager/?ref=dashboard');
        define('job_bm_support', 'https://www.pickplugins.com/forum/?ref=dashboard');
        define('job_bm_plugin_name', __('Job Board Manager', 'job-board-manager'));
        define('job_bm_plugin_version', '2.1.56');

        // Class
        require_once(job_bm_plugin_dir . 'includes/class-settings-tabs.php');
        require_once(job_bm_plugin_dir . 'includes/class-post-types.php');
        require_once(job_bm_plugin_dir . 'includes/class-post-meta-application.php');
        require_once(job_bm_plugin_dir . 'includes/class-post-meta-job.php');
        require_once(job_bm_plugin_dir . 'includes/class-post-meta-job-hook.php');
        require_once(job_bm_plugin_dir . 'includes/class-support-help.php');
        require_once(job_bm_plugin_dir . 'includes/class-job-data.php');
        //require_once( job_bm_plugin_dir . 'includes/class-user.php');




        require_once(job_bm_plugin_dir . 'includes/class-functions.php');
        require_once(job_bm_plugin_dir . 'includes/class-settings.php');
        require_once(job_bm_plugin_dir . 'includes/class-emails.php');
        require_once(job_bm_plugin_dir . 'includes/class-error-log.php');
        require_once(job_bm_plugin_dir . 'includes/class-application.php');
        require_once(job_bm_plugin_dir . 'includes/class-import.php');

        // ShortCodes
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-job-submit.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-job-edit.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-dashboard.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-my-jobs.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-job-archive.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-my-applications.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-applications.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-registration-form.php');

        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-payment.php');
        require_once(job_bm_plugin_dir . 'includes/shortcodes/class-shortcodes-job-categories.php');


        require_once(job_bm_plugin_dir . 'templates/job-single/job-single-hook.php');
        require_once(job_bm_plugin_dir . 'templates/application-single/application-single-hook.php');

        require_once(job_bm_plugin_dir . 'templates/job-categories/job-categories-hook.php');


        //Functions
        require_once(job_bm_plugin_dir . 'includes/functions/functions-crons.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-applications.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-settings.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-count.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-welcome.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-notification-email.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-stats.php');

        require_once(job_bm_plugin_dir . 'includes/functions/functions-payments.php');
        require_once(job_bm_plugin_dir . 'includes/functions/functions-page-templates.php');

        require_once(job_bm_plugin_dir . 'includes/functions/functions-terms-edit.php');



        include(job_bm_plugin_dir . 'templates/registration-form/registration-form-hook.php');


        add_action('activated_plugin', array($this, 'redirect_welcome'));

        //add_action( 'admin_enqueue_scripts', 'wp_enqueue_media' );
        add_action('wp_enqueue_scripts', array($this, 'job_bm_front_scripts'));
        add_action('admin_enqueue_scripts', array($this, 'job_bm_admin_scripts'));
        add_action('init', array($this, 'textdomain'));

        //session_start();
        register_activation_hook(__FILE__, array($this, 'job_bm_activation'));
        register_deactivation_hook(__FILE__, array($this, 'job_bm_cron_expired_check_deactivation'));

        add_filter('widget_text', 'do_shortcode');
    }

    public function textdomain()
    {

        $locale = apply_filters('plugin_locale', get_locale(), 'job-board-manager');
        load_textdomain('job-board-manager', WP_LANG_DIR . '/job-board-manager/job-board-manager-' . $locale . '.mo');

        load_plugin_textdomain('job-board-manager', false, plugin_basename(dirname(__FILE__)) . '/languages/');
    }








    public function job_bm_activation()
    {


        $class_job_bm_post_types = new class_job_bm_post_types();
        $class_job_bm_post_types->job_bm_posttype_job();
        flush_rewrite_rules();

        // create demo job category
        $class_job_bm_post_types->job_bm_register_job_category();

        $job_category_terms = get_terms(array(
            'taxonomy' => 'job_category',
            'hide_empty' => false,
        ));

        if (empty($job_category_terms)) {

            wp_insert_term(
                __('General', 'job-board-manager'), // the term
                'job_category', // the taxonomy
                array(
                    'description' => __('General jobs.', 'job-board-manager'),
                    'slug' => 'general',
                    //'parent'=> $parent_term_id
                )
            );
        }


        $job_bm_experied_check_recurrance = get_option('job_bm_experied_check_recurrance', 'daily');

        wp_schedule_event(time(), $job_bm_experied_check_recurrance, 'job_bm_cron_expired_check');
        wp_schedule_event(time(), $job_bm_experied_check_recurrance, 'job_bm_cron_update_expire_date');


        remove_role('job_seeker');
        remove_role('job_poster');
        remove_role('job_manager');


        add_role(
            'job_seeker',
            __('Job Seeker', 'job-board-manager'),
            array(
                'read' => true,
                'create_job' => true,
                'edit_job' => true,
                'delete_job' => true,
                'upload_files' => true,
            )
        );
        add_role(
            'job_poster',
            __('Job Poster', 'job-board-manager'),
            array(
                'read' => true,
                'create_job' => true,
                'edit_job' => true,
                'delete_job' => true,
                'upload_files' => true,
            )
        );
        add_role(
            'job_manager',
            __('Job Manager', 'job-board-manager'),
            array(
                'read' => true,
                'create_job' => true,
                'edit_job' => true,
                'delete_job' => true,
                'upload_files' => true,
                'create_others_job' => true,
                'edit_others_job' => true,
                'delete_others_job' => true,
            )
        );





        do_action('job_bm_activation');
    }

    function job_bm_cron_expired_check_deactivation()
    {
        wp_clear_scheduled_hook('job_bm_cron_expired_check');
        wp_clear_scheduled_hook('job_bm_cron_update_expire_date');
    }


    public function job_bm_install()
    {

        do_action('job_bm_install');
    }

    public function job_bm_uninstall()
    {

        do_action('job_bm_uninstall');
    }

    public function job_bm_deactivation()
    {

        do_action('job_bm_deactivation');
    }

    public function redirect_welcome($plugin)
    {

        $job_bm_welcome = get_option('job_bm_welcome');


        if (empty($job_bm_welcome)) {
            if ($plugin == 'job-board-manager/job-board-manager.php') {
                wp_safe_redirect(admin_url('edit.php?post_type=job&page=job_bm_welcome'));
                exit;
            }
        }
    }


    public function job_bm_front_scripts()
    {

        wp_enqueue_script('jquery');

        // Register CSS & Style
        wp_register_style('job-bm-job-submit', job_bm_plugin_url . 'assets/front/css/job-submit-new.css');
        wp_register_style('job_bm_job_archive', job_bm_plugin_url . 'assets/front/css/job-archive.css');
        wp_register_style('font-awesome-5', job_bm_plugin_url . 'assets/global/css/font-awesome-5.css');
        wp_register_style('job_bm_job_single', job_bm_plugin_url . 'assets/front/css/job-single-new.css');
        wp_register_style('job_bm_application_single', job_bm_plugin_url . 'assets/front/css/job-bm-application.css');

        wp_register_style('job-bm-dashboard', job_bm_plugin_url . 'assets/front/css/job-bm-dashboard.css');
        wp_register_style('job-bm-my-jobs', job_bm_plugin_url . 'assets/front/css/job-bm-my-jobs.css');
        wp_register_style('job-bm-applications', job_bm_plugin_url . 'assets/front/css/job-bm-applications.css');
        wp_register_style('job-bm-my-applications', job_bm_plugin_url . 'assets/front/css/my-applications.css');

        wp_register_style('job-bm-notice', job_bm_plugin_url . 'assets/front/css/job-bm-notice.css');
        wp_register_style('job-bm-registration-form', job_bm_plugin_url . 'assets/front/css/registration-form.css');

        wp_register_style('job-bm-media-upload', job_bm_plugin_url . 'assets/front/css/media-upload.css');


        // Register CSS & Style
        wp_register_script('job-bm-job-submit', job_bm_plugin_url . 'assets/front/js/scripts-job-submit.js');
        wp_register_script('job-bm-my-jobs', job_bm_plugin_url . 'assets/front/js/scripts-my-jobs.js');
        wp_register_script('job-bm-applications', job_bm_plugin_url . 'assets/front/js/scripts-applications.js');
        wp_register_script('scripts-my-applications', job_bm_plugin_url . 'assets/front/js/scripts-my-applications.js');
        wp_register_script('job-bm-notice', job_bm_plugin_url . 'assets/front/js/scripts-notice.js');
        wp_register_script('job-bm-application-single', job_bm_plugin_url . 'assets/front/js/scripts-application-single.js');
        wp_register_script('job-bm-media-upload', job_bm_plugin_url . 'assets/front/js/scripts-media-upload.js');
        wp_register_style('jquery-ui', job_bm_plugin_url . 'assets/global/css/jquery-ui.min.css');

        wp_register_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js');
    }

    public function job_bm_admin_scripts()
    {

        $screen = get_current_screen();
        //echo '<pre>'.var_export($screen, true).'</pre>';

        wp_enqueue_script('jquery');

        wp_enqueue_script('job_bm_admin_js', job_bm_plugin_url . '/assets/admin/js/scripts.js', array('jquery'));
        wp_localize_script('job_bm_admin_js', 'job_bm_ajax', array('job_bm_ajaxurl' => admin_url('admin-ajax.php')));

        // Register Scripts
        wp_register_script('welcome-tabs', job_bm_plugin_url . 'assets/admin/js/welcome-tabs.js');
        wp_register_script('chart.js', job_bm_plugin_url . 'assets/admin/js/chart.js', array('jquery'));
        wp_register_script('select2.min', job_bm_plugin_url . 'assets/admin/js/select2.min.js', array('jquery'));


        // Register CSS & Style
        wp_register_style('font-awesome-5', job_bm_plugin_url . 'assets/global/css/font-awesome-5.css');
        wp_register_style('jquery-ui', job_bm_plugin_url . 'assets/global/css/jquery-ui.min.css');
        wp_register_style('welcome-tabs', job_bm_plugin_url . 'assets/admin/css/welcome-tabs.css');
        wp_register_style('job-bm-addons', job_bm_plugin_url . 'assets/admin/css/addons.css');
        wp_register_style('select2.min', job_bm_plugin_url . 'assets/admin/css/select2.min.css');


        wp_register_script('settings-tabs', job_bm_plugin_url . 'assets/settings-tabs/settings-tabs.js', array('jquery'));
        wp_register_style('settings-tabs', job_bm_plugin_url . 'assets/settings-tabs/settings-tabs.css');

        $settings_tabs_field = new settings_tabs_field();
        $settings_tabs_field->admin_scripts();
    }
}

new JobBoardManager();
