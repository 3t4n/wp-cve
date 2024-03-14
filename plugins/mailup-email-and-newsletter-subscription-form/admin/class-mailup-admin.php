<?php

declare(strict_types=1);

/**
 * The admin-specific functionality of the plugin.
 *
 * @see  https://mailup.it
 * @since 1.2.6
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Your Name <email@example.com>
 */
class Mailup_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since  1.2.6
     *
     * @var string the ID of this plugin
     */
    private $mailup;

    /**
     * The version of this plugin.
     *
     * @since  1.2.6
     *
     * @var string the current version of this plugin
     */
    private $version;

    private $api_request;

    private $options;

    private $type_fields;

    private $model;

    /**
     * Initialize the class and set its properties.
     *
     * @since 1.2.6
     *
     * @param string $mailup  the name of this plugin
     * @param string $version the version of this plugin
     */
    public function __construct($mailup, $version)
    {
        $this->mailup = $mailup;
        $this->version = $version;
        $this->load_dependencies();
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since 1.2.6
     */
    public function enqueue_styles(): void
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mailup_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mailup_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style('wp-jquery-ui-dialog');
        wp_enqueue_style($this->mailup, plugin_dir_url(__FILE__).'css/mailup-admin.css', ['wp-jquery-ui-dialog'], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.2.6
     *
     * @param mixed $hook
     */
    public function enqueue_scripts($hook): void
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Mailup_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Mailup_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        if ('toplevel_page_mailup-settings' === $hook) {
            $lang = Mailup_i18n::getLanguage();

            wp_enqueue_script($this->mailup.'_validate', plugin_dir_url(__FILE__).'js/jquery.validate.min.js', ['jquery'], '1.19.3', false);
            wp_enqueue_script($this->mailup.'_validate_am', plugin_dir_url(__FILE__).'js/jquery.validate.min.js', ['jquery', $this->mailup.'_validate'], '1.19.3', false);

            if ($lang) {
                wp_enqueue_script(sprintf('%s_validate_loc_%s', $this->mailup, $lang), sprintf('%sjs/localization/messages_%s.js', plugin_dir_url(__FILE__), $lang), [$this->mailup.'_validate'], '1.19.3', false);
            }

            wp_enqueue_script($this->mailup, plugin_dir_url(__FILE__).'js/mailup-admin.js', ['jquery', 'jquery-ui-autocomplete', $this->mailup.'_validate_am'], $this->version, false);
            wp_localize_script(
                $this->mailup,
                'mailup_params',
                [
                    'ajax_url' => admin_url('admin-ajax.php'),
                    'ajaxNonce' => wp_create_nonce('ajax-nonce'),
                    'messages' => [
                        'must_have' => __('One of "email" or "phone" field is required', 'mailup'),
                        'invalid_char' => __('A Group name can\'t contain any of the following character $ & %', 'mailup'),
                    ],
                    'fields_text' => [
                        'remove' => __('Remove', 'mailup'),
                    ],
                ]
            );
        }
        // plugin_dir_url(__FILE__) . 'js/mailup-admin.js', array( 'jquery' ), $this->version, false);
    }

    public function create_admin_page(): void
    {
        $page_title = 'MailUp';
        $menu_title = 'MailUp';
        $capability = 'read';
        $slug = 'mailup-settings';
        $callback = [$this, 'admin_load_contents'];
        $icon = 'dashicons-email';
        $position = 100;
        add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
    }

    public function admin_load_contents(): void
    {
        $this->checkPermission();

        $this->model = new Mailup_Model($this->mailup);

        if (!empty($_POST['reset'])) {
            $this->model->removeTokens();
        }

        if (!empty($_GET['code'])) {
            $code = $_GET['code'];

            try {
                $this->model->setTokensFromCode($code);
            } catch (\Exception $ex) {
                echo $ex->getMessage();

                exit;
            }
        }

        if (!$this->model->has_tokens() && empty($_GET['code'])) {
            $this->mailup_login_display();
        } else {
            $this->mailup_admin_display();
        }
    }

    public function mailup_login_display(): void
    {
        $url_logon = $this->model->getUrlLogon();

        include __DIR__.'/partials/mailup-login-platform.php';
    }

    public function mailup_admin_display(): void
    {
        try {
            $lists = $this->model->fillList();
            $api_list = null !== $lists['api-lists'] ? $lists['api-lists'] : null;
            $type_fields = $lists['type-fields'] ?? null;
            $form_mup = null !== $lists['forms'] && count($lists['forms']) > 0 ? $lists['forms'][0] : new Mailup_Form();
            $terms = $this->model->terms;
            $messages = $lists['messages'];
            $setting_mup = $this->model->settings;

            include __DIR__.'/partials/mailup-admin-display.php';
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }

    public function autocomplete_group(): void
    {
        $this->checkPermission();

        try {
            if (isset($_POST['group'], $_POST['list_id'])) {
                $this->model = new Mailup_Model($this->mailup);

                if ($this->model->has_tokens()) {
                    $args = (object) [
                        'list_id' => $_POST['list_id'],
                        'group' => $_POST['group'],
                    ];
                    $groups = $this->model->get_groups($args);
                    wp_send_json_success(array_column($groups->Items, 'Name'));
                }
            }
        } catch (\Exception $ex) {
            wp_send_json_error(__('There was an error. Please try again later.', 'mailup'), $ex->getCode());

            exit;
        }

        exit;
    }

    public function save_forms(): void
    {
        $this->checkPermission();

        try {
            $this->model = new Mailup_Model($this->mailup);

            if (isset($_POST['form'])) {
                $this->model->setForms($_POST['form']);
            }

            if (isset($_POST['terms'])) {
                $this->model->setTerms($_POST['terms']);
            }

            if (isset($_POST['messages'])) {
                $this->model->setMessages($_POST['messages']);
            }

            if (isset($_POST['settings'])) {
                $this->model->setSettings($_POST['settings']);
            }

            wp_send_json_success(__('Form saved succesfully', 'mailup'), 200);
        } catch (\Exception $ex) {
            wp_send_json_error(__('There was an error. Please try again later.', 'mailup'), $ex->getCode());
        }

        exit;
    }

    public function get_name_field_type($id_field)
    {
        if ($id_field) {
            $type_fields = $this->model->getTypeFields();
            $ix_field = array_search($id_field, array_column($type_fields, 'id'), true);

            return $type_fields[$ix_field]->name;
        }

        return null;
    }

    public function register_widgets(): void
    {
        register_widget('Mailup_Widget');
    }

    public function mup_admin_head(): void
    {
        $current_screen = get_current_screen();

        // if (( isset( $_GET['page'] ) && $_GET['page'] == 'mailup-settings')) {
        if (isset($current_screen) && 'toplevel_page_mailup-settings' === $current_screen->id) {
            $current_screen->add_help_tab(
                [
                    'id' => 'overview',
                    'title' => __('Overview', 'mailup'),
                    'content' => '<p><strong>'.__('Overview', 'mailup').'</strong></p>'.
                        '<p>'.__('The MailUp plugin for WordPress makes it easy to add a subscription form to your WordPress website and, to collect recipient for your email and sms campaigns.', 'mailup').'</p>'.
                        '<p>'.__('Connect your MailUp account to WordPress, select a list and start designing your ready-to-use sign-up form.', 'mailup').'</p>',
                ]
            );

            $current_screen->add_help_tab(
                [
                    'id' => 'inclusion',
                    'title' => __('How to use it', 'mailup'),
                    'content' => '<p><strong>'.__('How to use it', 'mailup').'</strong></p>'.
                        '<p>'.__('Once you have created and customized your form you can:', 'mailup').'</p>'.
                        '<p>'.__('<ul><li>place it wherever you want, using the shortcode: [mailup_form]. Please note that you can have only one form per page.</li><li>add it to the sidebar activating a new Widget. The form will inherit your WordPress styles.</li></ul>', 'mailup').'</p>',
                ]
            );

            $current_screen->add_help_tab(
                [
                    'id' => 'general_settings',
                    'title' => __('General Settings', 'mailup'),
                    'content' => '<p><strong>'.__('General Settings', 'mailup').'</strong></p>'.
                        '<p>'.__('In the General Settings you can set up:', 'mailup').'</p>'.
                        '<p>'.__('<ul><li>List: choose the MailUp list in which you want to collect your recipients</li><li>Group: give a name or select the group where you will find your new subscribers</li><li>Title form and description: tell your visitors why they should fill in your form</li><li>Submit button: personalise your submit button text</li></ul>', 'mailup').'</p>',
                ]
            );

            $current_screen->add_help_tab(
                [
                    'id' => 'form_fields',
                    'title' => __('Form Fields', 'mailup'),
                    'content' => '<p><strong>'.__('Form Fields', 'mailup').'</strong></p>'.
                        '<p>'.__('In the Form Fields tab you can add and remove fields to your form. For each field you can decide the corresponding recipient field in MailUp platform, what type of content it will contain, the label displayed and if the field is required or not to submit it.', 'mailup').'</p>'.
                        '<p>'.__('The Terms and Conditions section gives you the possibility to add up to three custom T&Cs. Each T&C will automatically create a dedicated group in your MailUp account, so that you can easily retrieve those who accepted them and easily create your marketing campaigns.', 'mailup').'</p>',
                ]
            );

            $current_screen->add_help_tab(
                [
                    'id' => 'advanced_settings',
                    'title' => __('Advanced Settings', 'mailup'),
                    'content' => '<p><strong>'.__('Advanced Settings', 'mailup').'</strong></p>'.
                        '<p>'.__('In the tab Advanced Settings you can decide if you what your users to receive a Confirmation email, personalize some feedback messages and decide to use placeholders instead of labels.', 'mailup').'</p>'.
                        '<p>'.__('Your form will inherit your WordPress styles. But if you want to further personalize it, you can use the Custom CSS section.', 'mailup').'</p>',
                ]
            );
            // Help Sidebar
            $current_screen->set_help_sidebar(
                '<p><strong>'.__('For more information:', 'mailup').'</strong></p>'.
                    '<p><a href="'.__('https://help.mailup.com/display/mailupUserGuide/WordPress', 'mailup').'" target="_blank">'.esc_html__('MailUp Help', 'mailup').'</a></p>'
            );
        }
    }

    public function wpml_remove_admin_bar_menu(): void
    {
        if (!class_exists('SitePress') || !is_admin()) {
            return;
        }

        $current_screen = get_current_screen();

        if (isset($current_screen) && 'toplevel_page_mailup-settings' === $current_screen->id) {
            global $wp_admin_bar;
            $wp_admin_bar->remove_menu('WPML_ALS');
        }
    }

    public function check_update_version(): void
    {
        $version = get_option('mailup_version');

        if (version_compare($version, WPMUP_PLUGIN_VERSION, '<')) {
            try {
                $this->model = new Mailup_Model($this->mailup);

                if ($this->model->has_tokens() && $this->model->has_form()) {
                    // Retrieve and Update Group
                    $this->model->update_group_name();
                }
            } catch (\Exception $ex) {
                throw $ex;
            }
            update_option('mailup_version', WPMUP_PLUGIN_VERSION);
        }
    }

    protected function checkPermission(): void
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('Sorry, you are not allowed to access this page.'));
        }
    }

    private function load_dependencies(): void
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        include_once plugin_dir_path(__DIR__).'includes/class-mailup-model.php';

        include_once plugin_dir_path(__DIR__).'widgets/class-mailup-widget.php';
    }
}
