<?php
/********************************************************************
 * @plugin     ModernPolls
 * @file       app/Controllers/ScriptController.php
 * @date       11.02.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018 - 2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

namespace FelixTzWPModernPolls\Controllers;


class ScriptController
{
    public function __construct()
    {
        add_action('wp_enqueue_scripts', [$this, 'frontendScripts']);
        add_action('admin_enqueue_scripts', [$this, 'backendScripts']);
    }

    public function frontendScripts()
    {
        wp_enqueue_style('modern-polls-frontend', plugins_url('resources/assets/css/modern-polls.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');
        wp_enqueue_style('modern-polls-iconfont', plugins_url('resources/assets/css/mpp_iconfont.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');

        wp_enqueue_script(FelixTzWPModernPollsTextdomain, plugins_url('resources/assets/js/modern-polls.js', FelixTzWPModernPollsFile), ['jquery'], FelixTzWPModernPollsVersion, true);
        wp_enqueue_script('modern-polls-chart', plugins_url('resources/assets/js/Chart.min.js', FelixTzWPModernPollsFile), '', '', true);
        wp_enqueue_script('modern-polls-chart-datalabels', plugins_url('resources/assets/js/chartjs-plugin-datalabels.min.js', FelixTzWPModernPollsFile), '', '', true);
        wp_localize_script(FelixTzWPModernPollsTextdomain, 'modernpollsL10n',
            [
                'ajax_url' => admin_url('admin-ajax.php'),
                'text_wait' => __('Your last request is still being processed. Please wait a while ...', FelixTzWPModernPollsTextdomain),
                'text_valid' => __('Please choose a valid answer.', FelixTzWPModernPollsTextdomain),
                'text_multiple' => __('Maximum number of choices allowed: ', FelixTzWPModernPollsTextdomain),
                'show_loading' => 0,
                'show_fading' => 0
            ]);
    }

    public function backendScripts($hookSuffix)
    {
        //$adminPages = array('wp-polls/polls-manager.php', 'wp-polls/polls-add.php', 'wp-polls/polls-options.php', 'wp-polls/polls-templates.php', 'wp-polls/polls-uninstall.php');
        //if(in_array($hook_suffix, $poll_admin_pages, true)) {
        if (strpos($hookSuffix, 'modern-polls') !== false) {
            wp_enqueue_script('charts', 'https://www.gstatic.com/charts/loader.js', false);
            wp_enqueue_style('modern-polls-backend', plugins_url('resources/assets/css/modern-polls-backend.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');
            wp_enqueue_style('modern-polls-iconfont', plugins_url('resources/assets/css/mpp_iconfont.css', FelixTzWPModernPollsFile), false, FelixTzWPModernPollsVersion, 'all');

            wp_enqueue_script('modern-polls-admin', plugins_url('resources/assets/js/modern-polls-backend.js', FelixTzWPModernPollsFile), ['jquery'], FelixTzWPModernPollsVersion, true);
            /*            wp_localize_script('modern-polls-admin', 'modernPollsBackendL10n', array(
                            'admin_ajax_url' => admin_url('admin-ajax.php'),
                            'text_direction' => is_rtl() ? 'right' : 'left',
                            'text_delete_poll' => __('Delete Poll', FelixTzWPModernPollsTextdomain),
                            'text_no_poll_logs' => __('No poll logs available.', FelixTzWPModernPollsTextdomain),
                            'text_delete_all_logs' => __('Delete All Logs', FelixTzWPModernPollsTextdomain),
                            'text_checkbox_delete_all_logs' => __('Please check the \\\'Yes\\\' checkbox if you want to delete all logs.', FelixTzWPModernPollsTextdomain),
                            'text_delete_poll_logs' => __('Delete Logs For This Poll Only', FelixTzWPModernPollsTextdomain),
                            'text_checkbox_delete_poll_logs' => __('Please check the \\\'Yes\\\' checkbox if you want to delete all logs for this poll ONLY.', FelixTzWPModernPollsTextdomain),
                            'text_delete_poll_ans' => __('Delete Poll Answer', FelixTzWPModernPollsTextdomain),
                            'text_open_poll' => __('Open Poll', FelixTzWPModernPollsTextdomain),
                            'text_close_poll' => __('Close Poll', FelixTzWPModernPollsTextdomain),
                            'text_answer' => __('Answer', FelixTzWPModernPollsTextdomain),
                            'text_remove_poll_answer' => __('Remove', FelixTzWPModernPollsTextdomain)
                        ));*/
        }
    }
}