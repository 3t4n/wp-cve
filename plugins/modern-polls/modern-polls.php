<?php
/********************************************************************
 * @plugin     ModernPolls
 * @date       11.06.2021
 * @author     Felix Tzschucke <f.tzschucke@gmail.com>
 * @copyright  2018-2021 Felix Tzschucke
 * @license    GPL2
 * @version    1.0.10
 * @link       https://felixtz.de/
 ********************************************************************/

/*
Plugin Name: Modern Polls
Plugin URI:
Description: Modern AJAX Polls, easy to use with different or your own Theme
Version: 1.0.10
Author: Felix Tz
Author URI: https://felixtz.de/
Text Domain: modern-polls
Domain Path: /resources/lang
*/

require "vendor/autoload.php";

use FelixTzWPModernPolls\Controllers\DatabaseController;
use FelixTzWPModernPolls\Controllers\TemplateController;
use FelixTzWPModernPolls\Controllers\SettingsController;
use FelixTzWPModernPolls\Controllers\ScriptController;
use FelixTzWPModernPolls\Controllers\PollController;
use FelixTzWPModernPolls\Controllers\LockController;


class FelixTzWPModernPollsApp
{
    private static $instance = null;
    public $databaseController, $pollController, $scriptController, $templateController, $lockController, $settingsController, $settings;

    public function __construct()
    {
        self::defineConstants();

        $this->scriptController = new ScriptController();
        $this->databaseController = new DatabaseController();
        $this->pollController = new PollController();
        $this->templateController = new TemplateController();
        $this->lockController = new LockController();
        $this->settingsController = new SettingsController();

        $settings = $this->settingsController->getAll();
        $this->settings = $settings[0];

        add_shortcode('mpp', [$this, 'poll_shortcode']);

        add_action('plugins_loaded', [$this, 'mp_textdomain']);

        add_action('admin_menu', [$this, 'menu']);

        add_action('admin_footer-post-new.php', [$this, 'poll_footer_admin']);
        add_action('admin_footer-post.php', [$this, 'poll_footer_admin']);
        add_action('admin_footer-page-new.php', [$this, 'poll_footer_admin']);
        add_action('admin_footer-page.php', [$this, 'poll_footer_admin']);

        add_action('init', [$this, 'poll_tinymce_addbuttons']);

        add_action('wp_ajax_mppVote', [$this, 'vote']);
        add_action('wp_ajax_nopriv_mppVote', [$this, 'vote']);
        add_action('wp_ajax_mppResult', [$this, 'showResult']);
        add_action('wp_ajax_nopriv_mppResult', [$this, 'showResult']);
    }

    public static function get_instance()
    {
        if (self::$instance === null) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function install()
    {
        if (@is_file(ABSPATH . '/wp-admin/includes/upgrade.php')) {
            include_once(ABSPATH . '/wp-admin/includes/upgrade.php');
        } elseif (@is_file(ABSPATH . '/wp-admin/upgrade-functions.php')) {
            include_once(ABSPATH . '/wp-admin/upgrade-functions.php');
        } else {
            die('We have problem finding your \'/wp-admin/upgrade-functions.php\' and \'/wp-admin/includes/upgrade.php\'');
        }

        $role = get_role('administrator');
        if (!$role->has_cap('manage_polls')) {
            $role->add_cap('manage_polls');
        }
        FelixTzWPModernPollsApp::defineConstants();
        $databaseController = new DatabaseController();
        $databaseController->init();
    }

    public static function defineConstants()
    {
        define('FelixTzWPModernPollsVersion', '1.0.6');
        define('FelixTzWPModernPollsDir', plugin_dir_path(__FILE__));
        define('FelixTzWPModernPollsView', FelixTzWPModernPollsDir . '/resources/views/');
        define('FelixTzWPModernPollsFile', __FILE__);
        define('FelixTzWPModernPollsDirName', dirname(plugin_basename(__FILE__)));
        define('FelixTzWPModernPollsTextdomain', 'modern-polls');
    }

    public function mp_textdomain()
    {
        load_plugin_textdomain(FelixTzWPModernPollsTextdomain, false, FelixTzWPModernPollsDirName . '/resources/lang/');
    }

    public function menu()
    {
        add_menu_page(__('Modern Polls', FelixTzWPModernPollsTextdomain), __('Modern Polls', FelixTzWPModernPollsTextdomain), 'manage_polls', 'modern-polls', '', '/wp-content/plugins/' . FelixTzWPModernPollsDirName . '/resources/assets/images/icon-16x16.png', 25);

        add_submenu_page('modern-polls', __('All Polls', FelixTzWPModernPollsTextdomain), __('All Polls', FelixTzWPModernPollsTextdomain), 'manage_polls', 'modern-polls', [$this, 'showList']);
        add_submenu_page('modern-polls', __('Create', FelixTzWPModernPollsTextdomain), __('Create', FelixTzWPModernPollsTextdomain), 'manage_polls', 'modern-polls.create', [$this, 'showCreate']);
        add_submenu_page('modern-polls', __('Settings', FelixTzWPModernPollsTextdomain), __('Settings', FelixTzWPModernPollsTextdomain), 'manage_polls', 'modern-polls.settings', [$this, 'showSettings']);
        add_submenu_page('modern-polls', __('Templates', FelixTzWPModernPollsTextdomain), __('Templates', FelixTzWPModernPollsTextdomain), 'manage_polls', 'modern-polls.templates', [$this, 'showTemplates']);
        add_submenu_page('modern-polls', __('Help', FelixTzWPModernPollsTextdomain), __('Help', FelixTzWPModernPollsTextdomain), 'manage_polls', 'modern-polls.help', [$this, 'showHelp']);
    }

    public function showList()
    {

        if (isset($_POST['action'])) {
            switch (sanitize_text_field($_POST['action'])) {
                case 'edit'  :
                    if (isset($_POST['do']) && !empty(sanitize_text_field($_POST['do']))) {
                        switch (sanitize_text_field($_POST['do'])) {
                            case 'open' :
                                $result = $this->pollController->open(sanitize_key($_POST['id']));
                                break;
                            case 'close':
                                $result = $this->pollController->close(sanitize_key($_POST['id']));
                                break;
                            case 'edit' :
                                /* sanitize happens in method */
                                $result = $this->pollController->edit($_POST);
                                break;
                        }
                    }

                    $poll = $this->pollController->getPoll(sanitize_key($_POST['id']));
                    $pollAnswers = $this->pollController->getPollAnswers(sanitize_key($_POST['id']));
                    $templates = $this->templateController->getAll();

                    if (isset($_SESSION['mpp_lastPost'])) {
                        unset($_SESSION['mpp_lastPost']);
                    }

                    $this->view('edit', [
                        'poll' => $poll,
                        'templates' => $templates,
                        'pollAnswers' => $pollAnswers
                    ]);

                    break;

                case 'view'  :
                    $pollAnswers = $this->pollController->getPollAnswerInfos(sanitize_key($_POST['id']));
                    $this->view('info', [
                        'pollAnswers' => $pollAnswers
                    ]);
                    break;

                case 'delete':
                    $this->pollController->delete(sanitize_key($_POST['id']));
                    $polls = $this->pollController->getPollList();
                    $this->view('list', [
                        'polls' => $polls
                    ]);

                default:
            }
        } else {
            $polls = $this->pollController->getPollList();
            $this->view('list', [
                'polls' => $polls
            ]);
        }

    }

    public function showCreate()
    {
        $created = false;
        if (!empty($_POST['do']) && $_POST['do'] == 'create') {

            /* sanitize happens in method */
            $result = $this->pollController->create($_POST);
            if ($result != -1) {
                $created = true;
                $this->view('created', [
                    'result' => $result
                ]);
            }
        }

        if (!$created) {
            $count = 0;
            $templates = $this->templateController->getAll();
            $this->view('create', [
                'templates' => $templates
            ]);
        }
    }

    public function showSettings()
    {
        if (!empty($_POST['do']) && sanitize_text_field($_POST['do']) == 'save') {
            /* sanitize happens in method */
            $result = $this->settingsController->save($_POST);
        }

        $settings = $this->settingsController->getAll();
        $settings = $settings[0];
        $this->view('settings', [
            'settings' => $settings
        ]);
    }

    public function showTemplates()
    {

        if (isset($_POST['action'])) {
            switch (sanitize_text_field($_POST['action'])) {
                case 'add':
                    if (isset($_POST['do']) && !empty(sanitize_text_field($_POST['do']))) {
                        switch (sanitize_text_field($_POST['do'])) {
                            case 'add' :
                                /* sanitize happens in method */
                                $this->templateController->add($_POST, $_FILES);
                                break;
                        }
                    }

                    if (isset($_SESSION['mpp_lastPost'])) {
                        unset($_SESSION['mpp_lastPost']);
                    }

                    $this->view('template.add');

                    break;

                case 'delete':
                    $this->templateController->delete(sanitize_key($_POST['template_id']));
                    $templates = $this->templateController->getAll();
                    $this->view('templates', [
                        'templates' => $templates
                    ]);

                default:

            }
        } else {
            $templates = $this->templateController->getAll();
            $this->view('templates', [
                'templates' => $templates
            ]);
        }
    }

    public function showHelp()
    {
        $this->view('help');
    }

    public function dateField($name = 'mpp', $timestamp = -1)
    {
        global $month;
        if ($timestamp == -1 || $timestamp == 0) {
            $now = current_time('timestamp');
        } else {
            $now = $timestamp;
        }

        $day = (int)gmdate('j', $now);

        echo '<div class="mpp-input_group spacer_bottom">';
        echo '    <div class="mpp-input_group_prepend">';
        echo '        <div class="mpp-input_group_text">' . __('Date', FelixTzWPModernPollsTextdomain) . '</div>';
        echo '    </div>';
        echo '    <select name="' . $name . '_day" class="mpp-select">';

        for ($i = 1; $i <= 31; $i++) {
            if ($day === $i) {
                echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '    </select>';
        $month2 = (int)gmdate('n', $now);
        echo '    <select name="' . $name . '_month" class="mpp-select">';

        for ($i = 1; $i <= 12; $i++) {
            if ($i < 10) {
                $ii = '0' . $i;
            } else {
                $ii = $i;
            }
            if ($month2 === $i) {
                echo '<option value="' . $i . '" selected="selected">' . $month[$ii] . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $month[$ii] . '</option>';
            }
        }

        echo '    </select>';

        $year = (int)gmdate('Y', $now);
        echo '    <select name="' . $name . '_year" class="mpp-select_last">';
        for ($i = 2000; $i <= ($year + 10); $i++) {
            if ($year === $i) {
                echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }
        echo '    </select>';
        echo '</div>';
    }

    public function timeField($name = 'pollq_timestamp', $timestamp = -1)
    {
        if ($timestamp == -1 || $timestamp == 0) {
            $now = current_time('timestamp');
        } else {
            $now = $timestamp;
        }

        $hour = (int)gmdate('H', $now);

        echo '<div class="mpp-input_group spacer_bottom">';
        echo '    <div class="mpp-input_group_prepend">';
        echo '        <div class="mpp-input_group_text">' . __('Time', FelixTzWPModernPollsTextdomain) . '</div>';
        echo '    </div>';

        echo '    <select name="' . $name . '_hour" class="mpp-select">' . "\n";

        for ($i = 0; $i < 24; $i++) {
            if ($hour === $i) {
                echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '    </select>';
        echo '    <div class="mpp-input_group_text_secondary">:</div>';

        $minute = (int)gmdate('i', $now);
        echo '<select name="' . $name . '_minute" class="mpp-select">';

        for ($i = 0; $i < 60; $i++) {
            if ($minute === $i) {
                echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '</select>';
        echo '    <div class="mpp-input_group_text_secondary">:</div>';

        $second = (int)gmdate('s', $now);

        echo '<select name="' . $name . '_second" class="mpp-select_last">';

        for ($i = 0; $i <= 60; $i++) {
            if ($second === $i) {
                echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
            } else {
                echo '<option value="' . $i . '">' . $i . '</option>';
            }
        }

        echo '</select>';
        echo '</div>';
    }

    public function poll_footer_admin()
    {
        ?>
        <script type="text/javascript">
            QTags.addButton('mpp-wp_editor', '<?php echo esc_js(__('Insert Poll', FelixTzWPModernPollsTextdomain)); ?>', function () {
                var poll_id = jQuery.trim(prompt('<?php echo esc_js(__('Enter Poll ID', FelixTzWPModernPollsTextdomain)); ?>'));
                while (isNaN(poll_id)) {
                    poll_id = jQuery.trim(prompt("<?php echo esc_js(__('Error: Poll ID must be numeric', FelixTzWPModernPollsTextdomain)); ?>\n\n<?php echo esc_js(__('Please enter Poll ID again', FelixTzWPModernPollsTextdomain)); ?>"));
                }
                if (poll_id >= -1 && poll_id != null && poll_id != "") {
                    QTags.insertContent('[mpp id="' + poll_id + '"]');
                }
            });
        </script>
        <?php
    }

    public function poll_tinymce_addbuttons()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        if (get_user_option('rich_editing') === 'true') {
            add_filter('mce_external_plugins', [$this, 'poll_tinymce_addplugin']);
            add_filter('mce_buttons', [$this, 'poll_tinymce_registerbutton']);
            add_filter('wp_mce_translation', [$this, 'poll_tinymce_translation']);
        }
    }

    public function poll_tinymce_registerbutton($buttons)
    {
        array_push($buttons, 'separator', 'mpp-wp_editor_btn');
        return $buttons;
    }

    public function poll_tinymce_addplugin($plugin_array)
    {
        $plugin_array['mpp-wp_editor_btn'] = plugins_url('vendor/tinymce/plugins/polls/plugin.js?v=' . FelixTzWPModernPollsVersion, FelixTzWPModernPollsFile);
        return $plugin_array;
    }

    public function poll_tinymce_translation($mce_translation)
    {
        $mce_translation['Enter Poll ID'] = esc_js(__('Enter Poll ID', FelixTzWPModernPollsVersion));
        $mce_translation['Error: Poll ID must be numeric'] = esc_js(__('Error: Poll ID must be numeric', FelixTzWPModernPollsVersion));
        $mce_translation['Please enter Poll ID again'] = esc_js(__('Please enter Poll ID again', FelixTzWPModernPollsVersion));
        $mce_translation['Insert Poll'] = esc_js(__('Insert Poll', FelixTzWPModernPollsVersion));
        return $mce_translation;
    }

    public function poll_shortcode($atts)
    {
        $attributes = shortcode_atts(['id' => 0, 'type' => 'vote'], $atts);
        $id = (int)$attributes['id'];
        $hash = uniqid();

        if ($this->pollController->isOpen($id)) {

            if ($attributes['type'] === 'vote') {
                if (!$this->lockController->hasVoted($id)) {
                    return $this->pollController->showPoll($id);
                } else {
                    switch ($this->pollController->getResultHandle($id)) {
                        case -1 :
                            return $this->pollController->showSuccess($id);

                        case 1:
                        case 0 :
                            return $this->pollController->showResult($id, $hash);

                    }
                }

            } elseif ($attributes['type'] === 'result') {
                return display_pollresult($id);
            }
        } else {
            switch ($this->settings->closed_poll) {
                case 1:
                case 2:
                case 3:
                    return null;
            }
        }
        return null;
    }

    public function vote()
    {
        global $user_identity, $user_ID;

        if (isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action']) == 'mppVote') {

            // Load Headers
            $this->mp_textdomain();

            header('Content-Type: text/html; charset=' . get_option('blog_charset') . '');

            // Get Poll ID
            $id = (isset($_REQUEST['mpp_id']) ? (int)sanitize_key($_REQUEST['mpp_id']) : 0);
            $hash = (isset($_REQUEST['mpp_hash']) ? sanitize_text_field($_REQUEST['mpp_hash']) : 0);
            //$nonce    = (isset($_REQUEST['mpp_'.$id.'_nonce']) ? $_REQUEST['mpp_'.$id.'_nonce'] : 0);

            /* _POST mpp_ansers is / should be an array - sanitize follows in pollController->vote method */
            $answers = (isset($_REQUEST['mpp_answers']) ? $_REQUEST['mpp_answers'] : 0);
            $answers = json_decode($answers);

            // Ensure Poll ID Is Valid
            if ($id === 0) {
                _e('Invalid Poll ID', FelixTzWPModernPollsTextdomain);
                exit();
            }

            // Verify Referer
            if (!check_ajax_referer('mpp-nonce_' . $hash, 'mpp_nonce', false)) {
                _e('Failed To Verify Referrer', FelixTzWPModernPollsTextdomain);
                exit();
            }

            if ($id > 0) {
                $this->pollController->vote($id, $hash, $answers, $user_identity, $user_ID);
            } else {
                printf(__('Invalid Poll ID. Poll ID #%s', FelixTzWPModernPollsTextdomain), $id);
            }
        }
        exit();
    }

    public function showResult()
    {
        if (isset($_REQUEST['action']) && sanitize_text_field($_REQUEST['action']) == 'mppResult') {

            // Load Headers
            $this->mp_textdomain();

            header('Content-Type: text/html; charset=' . get_option('blog_charset') . '');


            $id = (isset($_REQUEST['mpp_id']) ? (int)sanitize_key($_REQUEST['mpp_id']) : 0);
            $hash = (isset($_REQUEST['mpp_hash']) ? sanitize_text_field($_REQUEST['mpp_hash']) : 0);
            // Verify Referer
            if (!check_ajax_referer('mpp-nonce_' . $hash, 'mpp_nonce', false)) {
                _e('Failed To Verify Referrer', FelixTzWPModernPollsTextdomain);
                exit();
            }
            echo $this->pollController->showResult($id, $hash, true);
            exit();
        }
    }

    public function view($view, $data = [])
    {
        foreach ($data as $key => $val) {
            ${$key} = $val;
        }

        return include FelixTzWPModernPollsView . $view . ".php";
    }
}

register_activation_hook(__FILE__, ['FelixTzWPModernPollsApp', 'install']);
add_action('plugins_loaded', ['FelixTzWPModernPollsApp', 'get_instance']);