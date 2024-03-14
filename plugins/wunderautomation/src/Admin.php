<?php

namespace WunderAuto;

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 */
class Admin
{
    /**
     * @var WunderAuto
     */
    protected $wunderAuto;

    /**
     * The ID of this plugin.
     *
     * @var string
     */
    protected $pluginName;

    /**
     * The version of this plugin.
     *
     * @var string
     */
    protected $version;

    /**
     * Main menu title
     *
     * @var string
     */
    protected $menuTitle = 'Automation';

    /**
     * Keep track of used metaboxes
     *
     * @var array<int, string>
     */
    protected $usedMetaboxes;

    /**
     * @var object
     */
    protected $workflowSettings;

    /**
     * @var object
     */
    protected $reTriggerSettings;

    /**
     * @var int
     */
    private $workflowCurrentVersion = 3;

    /**
     * @var int
     */
    private $reTriggerCurrentVersion = 1;

    /**
     * Initialize the class and set its properties.
     *
     * @param WunderAuto $wunderAuto
     * @param string     $pluginName The name of this plugin.
     * @param string     $version    The version of this plugin.
     */
    public function __construct($wunderAuto, $pluginName, $version)
    {
        $this->wunderAuto    = $wunderAuto;
        $this->pluginName    = $pluginName;
        $this->version       = $version;
        $this->usedMetaboxes = [];

        $this->menuTitle = __('Automation', 'wunderauto');
    }

    /**
     * Registers filters and actions
     *
     * @param Loader $loader
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('admin_menu', $this, 'adminMenu');
        $loader->addAction('admin_init', $this, 'doAdminInitAction', 10);
        $loader->addAction('admin_init', $this, 'registerSettingTabs', 20);
        $loader->addAction('admin_init', $this, 'showWelcomePage', 9999);
        $loader->addFilter('views_edit-automation-workflow', $this, 'subsubsubUpgradeLink', 100);
        $loader->addFilter('views_edit-automation-retrigger', $this, 'subsubsubUpgradeLink', 100);

        $loader->addAction('admin_enqueue_scripts', $this, 'enqueueStyles', 1, 10);
        $loader->addAction('admin_enqueue_scripts', $this, 'enqueueScripts', 1, 10);

        $loader->addAction('wunderauto_trigger_fields', $this, 'triggerFields', 0, 10);
        $loader->addAction('wunderauto_filter_fields', $this, 'filterFields', 0, 10);
        $loader->addAction('wunderauto_action_fields', $this, 'actionFields', 0, 10);
        $loader->addAction('admin_footer', $this, 'addAdminFooter');

        $loader->addFilter('bulk_actions-edit-automation-workflow', $this, 'removeBulkEdit');
        $loader->addFilter('bulk_actions-edit-automation-email', $this, 'removeBulkEdit');
        $loader->addFilter('post_row_actions', $this, 'rowActions', 10, 2);
    }

    /**
     * Admin menu inits
     *
     * @return void
     */
    public function adminMenu()
    {
        add_menu_page(
            'WunderAutomation',
            $this->menuTitle,
            'manage_options',
            $this->pluginName
        );
        add_submenu_page(
            $this->pluginName,
            __('Categories', 'wunderauto'),
            __('Categories', 'wunderauto'),
            'manage_options',
            'edit-tags.php?taxonomy=automation-category&post_type=automation-workflow'
        );
        add_submenu_page(
            $this->pluginName,
            __('Settings', 'wunderauto'),
            __('Settings', 'wunderauto'),
            'manage_options',
            'wunderauto-settings',
            [$this, 'renderSettings']
        );
        add_submenu_page(
            $this->pluginName,
            __('WunderAutomation Log', 'wunderauto'),
            __('Log', 'wunderauto'),
            'manage_options',
            'wunderauto-log',
            [$this, 'renderLog']
        );

        add_submenu_page(
            $this->pluginName,
            __('WunderAutomation Pro', 'wunderauto'),
            __('Upgrade to Pro', 'wunderauto'),
            'manage_options',
            'wunderauto-upgrade',
            [$this, 'renderUpgradePage']
        );

        add_submenu_page(
            $this->pluginName,
            __('Parameter test', 'wunderauto'),
            __('Parameter test', 'wunderauto'),
            'manage_options',
            'wunderauto-parametertest',
            [$this, 'renderParameterTest']
        );

        add_dashboard_page(
            __('Welcome to WunderAutomation', 'wunderauto'),
            __('Welcome to WunderAutomation', 'wunderauto'),
            'manage_options',
            'wunderauto-getting-started',
            [$this, 'renderWelcome']
        );
        remove_submenu_page('index.php', 'wunderauto-getting-started');
        remove_submenu_page($this->pluginName, $this->pluginName);

        $this->addMetaBoxes();
        $this->removeMetaBoxes();
    }

    /**
     * Add the upgrade link to the subsubsub menu on our post types edit page
     *
     * @param array<string, string> $links
     *
     * @return array<string, string>
     */
    public function subsubsubUpgradeLink($links)
    {
        $links['upgrade'] = sprintf(
            '<a href="%s">%s</a>',
            admin_url('admin.php?page=wunderauto-upgrade'),
            __('Upgrade to Pro', 'wunderauto')
        );

        return $links;
    }

    /**
     * Show welcome screen to new users
     *
     * @return void
     */
    public function showWelcomePage()
    {
        if (wp_doing_ajax() || wp_doing_cron()) {
            return;
        }

        if (!get_transient('wunderauto_welcome_redirect')) {
            return;
        }
        delete_transient('wunderauto_welcome_redirect');

        // Bail if activating from network, or bulk
        if (is_network_admin() || isset($_GET['activate-multi'])) {
            return;
        }

        // Bail if we're already shown the screen for this version
        if (get_option('wunderauto_welcomescreen') === $this->version) {
            // Also disable autoshow
            delete_transient('wunderauto_welcome_wizard_autoshow');
            return;
        }
        update_option('wunderauto_welcomescreen', $this->version);

        wp_safe_redirect(admin_url('index.php?page=wunderauto-getting-started'));
    }

    /**
     * If we're on the WunderAutomation settings page, register all
     * the settings tabs
     *
     * @return void
     */
    public function registerSettingTabs()
    {
        global $wunderAuto, $plugin_page, $pagenow;

        if (wp_doing_ajax()) {
            return;
        }

        if ($plugin_page !== 'wunderauto-settings' && $pagenow !== 'options.php') {
            return;
        }

        $settings = $wunderAuto->getSettings();
        foreach ($settings as $setting) {
            $setting->register();
        }
    }

    /**
     * @return void
     */
    public function doAdminInitAction()
    {
        do_action('wunderautomation_admin_init');
    }

    /**
     * @return void
     */
    public function renderSettings()
    {
        include WUNDERAUTO_BASE . '/admin/settings/settingspage.php';
    }

    /**
     * @return void
     */
    public function renderParameterTest()
    {
        include WUNDERAUTO_BASE . '/admin/parametertest/parametertestpage.php';
    }

    /**
     * @return void
     */
    public function renderLog()
    {
        include WUNDERAUTO_BASE . '/admin/log/logpage.php';
    }

    /**
     * @return void
     */
    public function renderUpgradePage()
    {
        include WUNDERAUTO_BASE . '/admin/upgrade/upgradepage.php';
    }

    /**
     * @return void
     */
    public function renderWelcome()
    {
        include WUNDERAUTO_BASE . '/admin/welcome/welcome.php';
    }

    /**
     * @return void
     */
    public function triggerFields()
    {
        // Add our various filter fields here.
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/triggers/webhook.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/triggers/postsaved.php');
    }

    /**
     * @return void
     */
    public function filterFields()
    {
        // Add our various filter fields here.
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/filters/standard.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/filters/between.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/filters/ajaxmultiselect.php');
    }

    /**
     * @return void
     */
    public function actionFields()
    {
        // Add our various filter fields here.
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/email.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/htmlemail.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/woocommerceemail.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/restapi.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/webhook.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/createpost.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/createuser.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/changestatus.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/customfield.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/changerole.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/taxonomyterm.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/ordernote.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/addobjects.php');
        include(WUNDERAUTO_BASE . '/admin/metaboxes/components/actions/log.php');

        //include(WUNDERAUTO_BASE . '/admin/fields/actions/canceldelayedworkflows.php');
        //include(WUNDERAUTO_BASE . '/admin/fields/actions/runworkflow.php');
    }

    /**
     * @param mixed    $unset_actions
     * @param \WP_Post $post
     *
     * @return mixed
     */
    public function rowActions($unset_actions, $post)
    {
        global $current_screen;

        if (!empty($current_screen) && !in_array($current_screen->post_type, ['automation-workflow'])) {
            return $unset_actions;
        }

        return $unset_actions;
    }

    /**
     * Removes WP built in functionality for bulk edit posts
     *
     * @param array<string, mixed> $actions
     *
     * @return mixed
     */
    public function removeBulkEdit($actions)
    {
        unset($actions['edit']);
        return $actions;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @param string $hook
     *
     * @return void
     */
    public function enqueueStyles($hook)
    {
        $hook = str_replace('automation-pro', 'automation', $hook);
        if (!$this->includeScripts($hook)) {
            return;
        }

        wp_enqueue_style(
            'wunderautomation-tailwind',
            WUNDERAUTO_URLBASE . "admin/assets/wunderautomation-tailwind.css",
            [],
            $this->version,
            'all'
        );

        wp_enqueue_style(
            'multiselect',
            WUNDERAUTO_URLBASE . "admin/assets/multiselect.css",
            [],
            '1.2.5',
            'all'
        );

        wp_enqueue_style(
            'datatables-css',
            WUNDERAUTO_URLBASE . "admin/assets/datatables/datatables.min.css",
            [],
            '1.10.20',
            'all'
        );

        wp_enqueue_style(
            $this->pluginName,
            WUNDERAUTO_URLBASE . "admin/assets/wunderautomation-admin.css",
            ['wp-jquery-ui-dialog'],
            $this->version,
            'all'
        );

        if ($hook === 'dashboard_page_wunderauto-getting-started') {
            wp_enqueue_style(
                'wunder-getting-started',
                WUNDERAUTO_URLBASE . "admin/assets/wunderautomation-welcome.css",
                [],
                $this->version,
                'all'
            );
        }
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @param string $hook
     *
     * @return void
     */
    public function enqueueScripts($hook)
    {
        global $post_type;

        $hook   = str_replace('automation-pro', 'automation', $hook);
        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '.js' : '.min.js';

        if (defined('VUE_DEBUG') && VUE_DEBUG) {
            $host = defined('VUE_DEVTOOLS_HOST') ? VUE_DEVTOOLS_HOST : 'localhost';
            $port = defined('VUE_DEVTOOLS_PORT') ? VUE_DEVTOOLS_PORT : '8098';
            wp_enqueue_script(
                'vue-devtools',
                "http://$host:$port",
                [],
                null,
                false
            );
        }

        wp_register_script(
            'vue3',
            WUNDERAUTO_URLBASE . "admin/assets/vue.global$suffix",
            [],
            '3.0.5',
            true
        );

        wp_register_script(
            'multiselect',
            WUNDERAUTO_URLBASE . "admin/assets/multiselect.global$suffix",
            ['vue3'],
            '1.2.5',
            false
        );

        wp_register_script(
            'axios',
            WUNDERAUTO_URLBASE . "admin/assets/axios$suffix",
            [],
            '0.21.1',
            true
        );

        wp_register_script(
            'datatables',
            WUNDERAUTO_URLBASE . "admin/assets/datatables/datatables.min.js",
            [],
            '1.10.20',
            true
        );

        wp_register_script(
            $this->pluginName . '-workflow-editor',
            WUNDERAUTO_URLBASE . "admin/assets/workflow-editor$suffix",
            ['vue3', 'axios', 'multiselect'],
            $this->version,
            true
        );

        wp_register_script(
            $this->pluginName . '-re-trigger-editor',
            WUNDERAUTO_URLBASE . "admin/assets/re-trigger-editor$suffix",
            ['vue3', 'axios', 'multiselect'],
            $this->version,
            true
        );

        wp_register_script(
            $this->pluginName . '-welcome-screen',
            WUNDERAUTO_URLBASE . "admin/assets/welcome-screen$suffix",
            ['vue3'],
            $this->version,
            true
        );

        wp_register_script(
            $this->pluginName . '-workflow-list',
            WUNDERAUTO_URLBASE . "admin/assets/workflow-list$suffix",
            [],
            $this->version,
            true
        );

        wp_register_script(
            $this->pluginName . '-re-trigger-list',
            WUNDERAUTO_URLBASE . "admin/assets/re-trigger-list$suffix",
            [],
            $this->version,
            true
        );

        wp_register_script(
            $this->pluginName . '-lists-dt',
            WUNDERAUTO_URLBASE . "admin/assets/lists-dt$suffix",
            ['datatables'],
            $this->version,
            true
        );

        wp_register_script(
            $this->pluginName . '-admin-notices',
            WUNDERAUTO_URLBASE . "admin/assets/admin-notices.js",
            ['axios'],
            $this->version,
            true
        );

        $workflowEditor = ['post-new.php', 'post.php'];
        $workflowList   = ['edit.php'];
        $welcomeScreen  = ['dashboard_page_wunderauto-getting-started'];
        $datatables     = ['automation_page_wunderauto-log', 'automation_page_wunderauto-queue'];

        // We always need to enqueue the admin notices js so they can be dismissed from any
        // admin screen
        $handle = $this->pluginName . '-admin-notices';
        wp_enqueue_script($handle);

        if (in_array($hook, $workflowEditor) && $post_type === 'automation-workflow') {
            $handle = $this->pluginName . '-workflow-editor';
            wp_enqueue_script($handle);
        }

        if (in_array($hook, $workflowEditor) && $post_type === 'automation-retrigger') {
            $handle = $this->pluginName . '-re-trigger-editor';
            wp_enqueue_script($handle);
        }

        if (in_array($hook, $welcomeScreen)) {
            $handle = $this->pluginName . '-welcome-screen';
            wp_enqueue_script($handle);
        }

        if (in_array($hook, $workflowList) && $post_type === 'automation-workflow') {
            $handle = $this->pluginName . '-workflow-list';
            wp_enqueue_script($handle);
        }

        if (in_array($hook, $workflowList) && $post_type === 'automation-retrigger') {
            $handle = $this->pluginName . '-re-trigger-list';
            wp_enqueue_script($handle);
        }

        if (in_array($hook, $datatables)) {
            $handle = $this->pluginName . '-lists-dt';
            wp_enqueue_script($handle);
        }

        // If we have enqueued any of our scripts, add variables
        $data = [
            'workflowVersion'         => $this->workflowCurrentVersion,
            'reTriggerVersion'        => $this->reTriggerCurrentVersion,
            'search_nonce'            => wp_create_nonce('search'),
            'search_products_nonce'   => wp_create_nonce('search-products'),
            'search_users_nonce'      => wp_create_nonce('search-users'),
            'search_tax_nonce'        => wp_create_nonce('search-taxonomies'),
            'search_customers_nonce'  => wp_create_nonce('search-customers'),
            'search_categories_nonce' => wp_create_nonce('search-categories'),
            'search_logdata_nonce'    => wp_create_nonce('search-logdata'),
            'search_queuedata_nonce'  => wp_create_nonce('search-queuedata'),
            'wizard_data_nonce'       => wp_create_nonce('wizard-data'),
            'admin_notice_nonce'      => wp_create_nonce('dismiss-admin-notice'),
            'clearlog_alertmsg'       => __('Are you sure you want to delete entries from the log?', 'wunderauto'),
            'queue_cancel_alertmsg'   => __(
                'Are you sure you want to remove item [id] from the queue?',
                'wunderauto'
            ),
            'queue_runnow_alertmsg'   => __('Are you sure you want to run item [id] immediately', 'wunderauto'),
        ];
        wp_localize_script($handle, 'WunderAutoData', $data);
    }

    /**
     * Any of the metaboxes in may have a vue template file that
     * needs to be added to the footer
     *
     * @return void
     */
    public function addAdminFooter()
    {
        foreach ($this->usedMetaboxes as $metabox) {
            $file = WUNDERAUTO_BASE . "/admin/fields/{$metabox}.php";
            if (file_exists($file)) {
                include $file;
            }
        }
    }

    /**
     * Load the metabox view file
     *
     * @param \WP_Post              $post
     * @param array<string, string> $metaBox
     *
     * @return void
     */
    public function getMetaBoxView($post, $metaBox)
    {
        switch ($post->post_type) {
            case 'automation-workflow':
                if ($this->workflowSettings === null) {
                    $workflow               = $this->wunderAuto->createWorkflowObject($post->ID);
                    $this->workflowSettings = $workflow->getState();
                }
                break;
            case 'automation-retrigger':
                if ($this->reTriggerSettings === null) {
                    $reTrigger               = $this->wunderAuto->createReTriggerObject($post->ID);
                    $this->reTriggerSettings = $reTrigger->getState();
                }
                break;
        }

        $metaBox = (object)$metaBox;
        $file    = str_replace('wunderauto-', '', $metaBox->id);
        $file    = apply_filters('wunderauto/metaboxview/file', $file);

        $this->usedMetaboxes[] = $file;
        include WUNDERAUTO_BASE . '/admin/metaboxes/' . $file . '.php';
    }

    /**
     * Allow views to retrieve post type settings
     *
     * @param string $postType
     *
     * @return object
     */
    public function getSettingsForView($postType)
    {
        switch ($postType) {
            case 'automation-workflow':
                return $this->workflowSettings;
            case 'automation-retrigger':
                return $this->reTriggerSettings;
        }

        return (object)[];
    }

    /**
     * @return void
     */
    protected function addMetaBoxes()
    {
        add_meta_box(
            'wunderauto-trigger',
            __('Trigger', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-workflow',
            'normal'
        );

        add_meta_box(
            'wunderauto-steps',
            __('Steps', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-workflow',
            'normal'
        );

        add_meta_box(
            'wunderauto-save-post',
            __('Save', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-workflow',
            'side',
            'high'
        );

        add_meta_box(
            'wunderauto-schedule',
            __('Schedule', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-workflow',
            'side'
        );

        add_meta_box(
            'wunderauto-options',
            __('Options', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-workflow',
            'side'
        );

        add_meta_box(
            'wunderauto-retrigger-query',
            __('Objects query', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-retrigger',
            'normal'
        );

        add_meta_box(
            'wunderauto-retrigger-schedule',
            __('Schedule', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-retrigger',
            'normal'
        );

        add_meta_box(
            'wunderauto-re-trigger-steps',
            __('Filters', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-retrigger',
            'normal'
        );

        add_meta_box(
            'wunderauto-re-trigger-using',
            __('Workflows using this Re-Trigger', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-retrigger',
            'normal'
        );

        add_meta_box(
            'wunderauto-save-re-trigger',
            __('Save', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            'automation-retrigger',
            'side',
            'high'
        );

        add_meta_box(
            'wunderauto-pro-promotion',
            __('Upgrade to Pro', 'wunderauto'),
            [$this, 'getMetaBoxView'],
            $this->wunderAuto->postTypes,
            'side',
            'core'
        );
    }

    /**
     * Remove standard WP metaboxes from our Custom post types
     *
     * @return void
     */
    protected function removeMetaBoxes()
    {
        remove_meta_box('submitdiv', $this->wunderAuto->postTypes, 'side');
        remove_meta_box('categorydiv', $this->wunderAuto->postTypes, 'side');
        remove_meta_box('tagsdiv-post_tag', $this->wunderAuto->postTypes, 'side');
        remove_meta_box('edit-slug-box', $this->wunderAuto->postTypes, 'side');
    }

    /**
     * Determines if any of our scripts needs to be included on the current page
     *
     * @param string $hook
     *
     * @return bool
     */
    private function includeScripts($hook)
    {
        global $post_type;

        $postEditPages = ['post-new.php', 'edit.php', 'post.php'];
        $otherPages    = [
            'automation_page_wunderauto-settings',
            'dashboard_page_wunderauto-getting-started',
            'wunderauto-upgrade',
        ];

        if (in_array($hook, $otherPages)) {
            return true;
        }

        $postTypes = ['automation-workflow', 'automation-retrigger'];
        if (in_array($hook, $postEditPages) && !in_array($post_type, $postTypes)) {
            return false;
        }

        if (!in_array($hook, $postEditPages) && strpos($hook, 'automation_page_wunderauto-') !== 0) {
            return false;
        }

        return true;
    }
}
