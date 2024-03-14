<?php

namespace FluentSupport\App\Hooks\Handlers;

use FluentSupport\App\App;
use FluentSupport\App\Models\Agent;
use FluentSupport\App\Models\MailBox;
use FluentSupport\App\Models\Product;
use FluentSupport\App\Models\TicketTag;
use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\App\Services\Helper;
use FluentSupport\App\Services\TransStrings;

class Menu
{
    public function add()
    {
        $currentUserPermissions = PermissionManager::currentUserPermissions();

        if (!$currentUserPermissions) {
            return;
        }

        $permission = 'fst_view_dashboard';

        $isAdmin = false;

        if (current_user_can('manage_options')) {
            $permission = 'manage_options';
            $isAdmin = true;
        }

        $menuPosition = 25;
        if (defined('FLUENTCRM')) {
            $menuPosition = 4;
        }

        /*
         * Filter Fluent Support menu position in WordPress dashboard
         * @param integer $menuPosition
         */
        $menuPosition = apply_filters('fluent_support/admin_menu_position', $menuPosition);

        add_menu_page(
            __('Fluent Support', 'fluent-support'),
            __('Fluent Support', 'fluent-support'),
            $permission,
            'fluent-support',
            array($this, 'renderApp'),
            $this->getMenuIcon(),
            $menuPosition
        );


        add_submenu_page(
            'fluent-support',
            __('Dashboard', 'fluent-support'),
            __('Dashboard', 'fluent-support'),
            $permission,
            'fluent-support',
            array($this, 'renderApp')
        );

        add_submenu_page(
            'fluent-support',
            __('Tickets', 'fluent-support'),
            __('Tickets', 'fluent-support'),
            ($isAdmin) ? 'manage_options' : 'fst_manage_own_tickets',
            'fluent-support#/tickets',
            array($this, 'renderApp')
        );

        add_submenu_page(
            'fluent-support',
            __('Workflows', 'fluent-support'),
            __('Workflows', 'fluent-support'),
            ($isAdmin) ? 'manage_options' : 'fst_manage_workflows',
            'fluent-support#/workflows',
            array($this, 'renderApp')
        );

        add_submenu_page(
            'fluent-support',
            __('Activities', 'fluent-support'),
            __('Activities', 'fluent-support'),
            ($isAdmin) ? 'manage_options' : 'fst_view_activity_logs',
            'fluent-support#/activity-logger',
            array($this, 'renderApp')
        );

        add_submenu_page(
            'fluent-support',
            __('Settings', 'fluent-support'),
            __('Settings', 'fluent-support'),
            ($isAdmin) ? 'manage_options' : 'fst_manage_settings',
            'fluent-support#/settings',
            array($this, 'renderApp')
        );

        add_submenu_page(
            'fluent-support',
            __('Reports', 'fluent-support'),
            __('Reports', 'fluent-support'),
            ($isAdmin) ? 'manage_options' : 'fst_sensitive_data',
            'fluent-support#/reports',
            array($this, 'renderApp')
        );

    }

    public function renderApp()
    {
        $app = App::getInstance();

        $assets = $app['url.assets'];

        $baseUrl = apply_filters('fluent_support/base_url', admin_url('admin.php?page=fluent-support#/'));

        $menuItems = [
            [
                'key'       => 'dashboard',
                'label'     => __('Dashboard', 'fluent-support'),
                'permalink' => $baseUrl
            ],
            [
                'key'       => 'tickets',
                'label'     => __('Tickets', 'fluent-support'),
                'permalink' => $baseUrl . 'tickets',
            ],
            [
                'key'       => 'reports',
                'label'     => __('Reports', 'fluent-support'),
                'permalink' => $baseUrl . 'reports'
            ],
        ];

        $hasSensitiveAccess = PermissionManager::currentUserCan('fst_sensitive_data');
        if ($hasSensitiveAccess) {
            $menuItems[] = [
                'key'       => 'customers',
                'label'     => __('Customers', 'fluent-support'),
                'permalink' => $baseUrl . 'customers'
            ];
        }

        if (PermissionManager::currentUserCan('fst_manage_saved_replies')) {
            $secondayItems = [
                [
                    'key'       => 'saved_replies',
                    'label'     => __('Saved Replies', 'fluent-support'),
                    'permalink' => $baseUrl . 'saved-replies'
                ]
            ];
        }

        if (PermissionManager::currentUserCan('fst_view_activity_logs')) {
            $secondayItems[] = [
                'key'       => 'activity_logger',
                'label'     => __('Activities', 'fluent-support'),
                'permalink' => $baseUrl . 'activity-logger'
            ];
        }

        $canManageSettings = PermissionManager::currentUserCan('fst_manage_settings');

        if ($canManageSettings) {
            $secondayItems[] = [
                'key'       => 'mailboxes',
                'label'     => __('Business Inboxes', 'fluent-support'),
                'permalink' => $baseUrl . 'mailboxes'
            ];
        }

        if (PermissionManager::currentUserCan('fst_manage_workflows')) {
            $secondayItems[] = [
                'key'       => 'workflows',
                'label'     => __('Workflows', 'fluent-support'),
                'permalink' => $baseUrl . 'workflows'
            ];
        }

        if ($canManageSettings) {
            $secondayItems[] = [
                'key'       => 'settings',
                'label'     => __('Global Settings', 'fluent-support'),
                'permalink' => $baseUrl . 'settings'
            ];
        }

        /*
         * Filter Fluent Support dashboard top-left menu items
         *
         * @since v1.0.0
         *
         * @param array $menuItems
         */
        $menuItems = apply_filters('fluent_support/primary_menu_items', $menuItems);

        /*
         * Filter Fluent Support dashboard top-right menu items
         *
         * @since v1.0.0
         *
         * @param array $secondayItems
         */
        isset($secondayItems) ? $secondayItems = apply_filters('fluent_support/secondary_menu_items', $secondayItems) : [];


        if (!defined('FLUENT_SUPPORT_PRO_DIR_FILE')) {
            $secondayItems[] = [
                'key'       => 'upgrade_to_pro',
                'label'     => 'Upgrade to Pro',
                'permalink' => 'https://fluentsupport.com'
            ];
        }

        $app = App::getInstance();
        $this->enqueueAssets();

        do_action('fluent_support/admin_app_loaded', $app);
        $app->view->render('admin.menu', [
            'base_url'       => $baseUrl,
            'logo'           => $assets . 'images/logo.svg',
            'menuItems'      => $menuItems,
            'secondaryItems' => isset($secondayItems) ? $secondayItems : [],
        ]);
    }

    public function maybeEnqueueAssets()
    {
        if (isset($_GET['page']) && $_GET['page'] == 'fluent-support') {
            $this->enqueueAssets();
        }
    }

    public function enqueueAssets()
    {
        $app = App::getInstance();

        $assets = $app['url.assets'];

        wp_enqueue_script('dompurify', $assets . 'libs/purify/purify.min.js', [], '2.4.3');

        wp_enqueue_style(
            'fluent_support_admin_app', $assets . 'admin/css/alpha-admin.css', [], FLUENT_SUPPORT_VERSION
        );

        $agents = Agent::select(['id', 'first_name', 'last_name'])
            ->where('person_type', 'agent')
            ->get()->toArray();

        foreach ($agents as $index => $agent) {
            $agents[$index]['id'] = strval($agent['id']);
        }

        $me = Helper::getAgentByUserId(get_current_user_id());

        if (!$me && current_user_can('manage_options')) {
            // we should create the agent
            $user = wp_get_current_user();
            $me = Agent::create([
                'email'      => $user->user_email,
                'first_name' => $user->first_name,
                'last_name'  => $user->last_name,
                'user_id'    => $user->ID
            ]);
        }

        $me->permissions = PermissionManager::currentUserPermissions();

        do_action('fluent_support_loading_app', $app);

        // Editor default styles.
        add_filter('user_can_richedit', '__return_true');
        wp_tinymce_inline_scripts();
        wp_enqueue_editor();
        wp_enqueue_media();

        wp_enqueue_script(
            'fluent_support_admin_app_start',
            $assets . 'admin/js/start.js',
            array('jquery'),
            FLUENT_SUPPORT_VERSION,
            true
        );

        wp_enqueue_script(
            'fluent_support_global_admin',
            $assets . 'admin/js/global_admin.js',
            array('jquery'),
            FLUENT_SUPPORT_VERSION,
            true
        );

        $integrationDrivers = [];
        if (!defined('FLUENTSUPPORTPRO')) {
            $integrationDrivers = [
                [
                    'key'           => 'telegram_settings',
                    'title'         => __('Telegram', 'fluent-support'),
                    'description'   => __('Send Telegram notifications to Group, Channel or individual person inbox and reply from Telegram inbox', 'fluent-support'),
                    'promo_heading' => __('Get activity notification to Telegram Messenger and reply directly from Telegram inbox', 'fluent-support'),
                    'require_pro'   => true
                ],
                [
                    'key'           => 'slack_settings',
                    'title'         => __('Slack', 'fluent-support'),
                    'description'   => __('Send ticket activity notifications to slack', 'fluent-support'),
                    'promo_heading' => __('Get activity notification to Slack Channel and keep your support team super engaged', 'fluent-support'),
                    'require_pro'   => true
                ]
            ];
        }

        /*
         * Filter integration driver
         * @param array $integrationDrivers
         */
        $integrationDrivers = apply_filters('fluent_support/integration_drivers', $integrationDrivers);

        $tags = TicketTag::select(['id', 'title'])->get()->toArray();

        $tags = array_map(function ($tag) {
            $tag['id'] = strval($tag['id']);
            return $tag;
        }, $tags);

        $i18ns = TransStrings::getTransStrings();
        $i18ns['allowed_files_and_size'] = Helper::getFileUploadMessage();

        /*
         * Filter agent portal localize javascript data
         *
         * @since v1.0.0
         *
         * @param array $appVars
         */
        $appVars = apply_filters('fluent_support_app_vars', array(
            'slug'                       => $slug = $app->config->get('app.slug'),
            'nonce'                      => wp_create_nonce($slug),
            'rest'                       => $this->getRestInfo($app),
            'brand_logo'                 => $this->getMenuIcon(),
            'firstEntry'                 => '',
            'lastEntry'                  => '',
            'asset_url'                  => $assets,
            'support_agents'             => $agents,
            'support_products'           => Product::select(['id', 'title'])->get(),
            'client_priorities'          => Helper::customerTicketPriorities(),
            'ticket_statuses'            => Helper::ticketStatuses(),
            'ticket_statuses_group'      => Helper::ticketStatusGroups(),
            'changeable_ticket_statuses' => Helper::changeableTicketStatuses(),
            'admin_priorities'           => Helper::adminTicketPriorities(),
            'mailboxes'                  => MailBox::select(['id', 'name', 'settings'])->get(),
            'me'                         => $me,
            'pref'                       => [
                'go_back_after_reply' => 'yes'
            ],
            'notification_integrations'  => $integrationDrivers,
            'server_time'                => date('Y-m-d\TH:i:sP'),
            'has_email_parser'           => defined('FLUENTSUPPORTPRO_PLUGIN_VERSION'),
            'ticket_tags'                => $tags,
            'i18n'                       => $i18ns,
            'custom_fields'              => apply_filters('fluent_support/ticket_custom_fields', []),
            'has_file_upload'            => !!Helper::ticketAcceptedFileMiles(),
            'repost_export_options'      => Helper::getExportOptions(),
            'enable_draft_mode'          => Helper::getBusinessSettings('enable_draft_mode', 'no'),
            'max_file_upload'            => Helper::getBusinessSettings('max_file_upload', 3),
            'ajaxurl'                    => admin_url('admin-ajax.php'),
            'auth_provider'              => Helper::getAuthProvider()
        ));

        if (defined('FLUENTCRM')) {
            $appVars['fluentcrm_config'] = Helper::getFluentCRMTagConfig();
        }

        $appVars['has_pro'] = defined('FLUENTSUPPORTPRO_PLUGIN_VERSION');
        if ($appVars['has_pro']) {
            $appVars['agent_feedback_rating'] = Helper::getBusinessSettings('agent_feedback_rating', 'no');
        }

        wp_localize_script('fluent_support_admin_app_start', 'fluentSupportAdmin', $appVars);
    }

    protected function getRestInfo($app)
    {
        $ns = $app->config->get('app.rest_namespace');
        $v = $app->config->get('app.rest_version');

        return [
            'base_url'  => esc_url_raw(rest_url()),
            'url'       => rest_url($ns . '/' . $v),
            'nonce'     => wp_create_nonce('wp_rest'),
            'namespace' => $ns,
            'version'   => $v,
        ];
    }

    protected function getMenuIcon()
    {
        $app = App::getInstance();

        $assets = $app['path.assets'];

        return 'data:image/svg+xml;base64,' . base64_encode(
                '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1200 1200"><defs><style>.cls-1{fill:#fff;fill-rule:evenodd;}</style></defs><path class="cls-1" d="M1154.68,162.74v328l-.06-.05c-28.42-68.67-94.1-118-171.88-122.75a193.27,193.27,0,0,0-64.45,6.17L137.57,583.44A70.3,70.3,0,0,1,122.08,586a62.79,62.79,0,0,1-10.77.94A66.34,66.34,0,0,1,45,521.43a73.54,73.54,0,0,1-.61-9.28v-111c0-.16,0-.33,0-.5s0-.27,0-.39c0-1.27.16-2.49.22-3.77s.05-2.39.17-3.55c.16-2.06.44-4.06.72-6.06.11-.55.16-1.16.27-1.72.34-1.94.73-3.83,1.23-5.71.16-.84.33-1.56.55-2.34.45-1.55.89-3.05,1.33-4.55s1.06-3,1.61-4.55c.28-.61.5-1.28.78-1.94a92.91,92.91,0,0,1,62.12-54.85L1028.49,66.08a100.17,100.17,0,0,1,18.82-3.44,94.46,94.46,0,0,1,14.21-1.89c1.39,0,2.72-.11,4.11-.11a88.59,88.59,0,0,1,88.55,88.61c0,.55-.06,1.16-.06,1.77A102.6,102.6,0,0,1,1154.68,162.74Z"/><path class="cls-1" d="M943.32,498.86c-2.07,0-4.08.05-6.09.15-2.12-.1-4.29-.15-6.46-.15a139.56,139.56,0,0,0-35.94,4.71l-121.22,32.5L118.06,711.86A86,86,0,0,0,89.43,722c-.38.16-.69.37-1,.53a88.53,88.53,0,0,0-44,76.59v335.42a3.68,3.68,0,0,0,6.93,1.76,400,400,0,0,1,128.84-142,1.75,1.75,0,0,1,.32-.21A321.92,321.92,0,0,1,257.12,954a.22.22,0,0,1,.11,0q12.61-4.61,25.72-8.1l46.43-12.44,626.16-167.8,13-3.5a121.56,121.56,0,0,0,18.58-5,132.87,132.87,0,0,0-43.77-258.32Z"/></svg>'
            );
    }
}
