<?php

namespace Wincher;

use WP_REST_Server;

/**
 * The Plugin class.
 */
class Plugin
{
    /**
     * @var string
     */
    public const SLUG = 'wincher';

    /**
     * @var string
     */
    public const VERSION = '3.0.6';

    /**
     * @var string
     */
    protected $slug;

    /**
     * @var WincherOAuthClient
     */
    protected $client;

    /**
     * Plugin constructor.
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'addAdminMenu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueGlobalAssets']);
        add_action('rest_api_init', [$this, 'registerApiRoutes']);

        $this->client = new WincherOAuthClient();

        if (!$this->client->hasTokens()) {
            add_action('pre_current_active_plugins', [$this, 'showActivateButton']);
        }
    }

    /**
     * Gets the client instance.
     *
     * @return WincherOAuthClient the client instance
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Fired when the plugin activates.
     *
     * @return void
     */
    public function activate()
    {
    }

    /**
     * Fires when the plugin deactivates.
     *
     * @return void
     */
    public function deactivate()
    {
        delete_option(WincherOAuthClient::TOKEN_OPTION);
    }

    public function enqueueGlobalAssets()
    {
        wp_enqueue_style(self::SLUG . 'global', WINCHER_PLUGIN_BASE_URL . 'assets/css/global.css', [], self::VERSION);
        wp_enqueue_script(self::SLUG . 'global', WINCHER_PLUGIN_BASE_URL . 'assets/js/global.js', [], self::VERSION);
        wp_localize_script(self::SLUG . 'global', 'wincherConfig', [
            'apiNonce' => wp_create_nonce('wp_rest'),
            'apiBaseUrl' => rest_url($this->getApiNamespace()),
        ]);
    }

    /**
     * Adds the admin menu.
     *
     * @return void
     */
    public function addAdminMenu()
    {
        add_menu_page(
            'Google Rank Tracker by Wincher',
            'Google Rankings',
            'manage_options',
            self::SLUG,
            [$this, 'displayOverview'],
            $this->getImageBase64(WINCHER_PLUGIN_BASE_PATH . 'assets/img/mark.svg'),
            '2.34'
        );

        add_submenu_page(
            self::SLUG,
            'Google Rank Tracker by Wincher',
            __('Dashboard', self::SLUG),
            'manage_options',
            self::SLUG,
            [$this, 'displayOverview']
        );

        if ('FREE' === get_option('wincher_account_type')) {
            global $submenu;
            $upgradeText = __('Subscribe now', self::SLUG);
            $submenu[self::SLUG][] = [
                "<span class='wincher-upgrade-link'>$upgradeText</span>",
                'manage_options',
                'https://www.wincher.com/subscribe?referer=wordpress&utm_source=wordpress&utm_medium=link&utm_content=submenu',
            ];
        }
    }

    /**
     * Shows the activate button.
     *
     * @return void
     */
    public function showActivateButton()
    {
        ?>
        <div class="wincher-activate">
            <span>Don't forget to activate Wincher to keep track of your Google rankings!</span>
            <a href="admin.php?page=<?php echo self::SLUG; ?>">Start using Wincher now</a>
        </div>
        <?php
    }

    /**
     * Displays the overview.
     *
     * @return void
     */
    public function displayOverview()
    {
        $page = new DashboardPage($this);
        $page->render();
    }

    /**
     * Registers the API routes.
     *
     * @return void
     */
    public function registerApiRoutes()
    {
        $routes = [
            [
                'route' => 'token',
                'methods' => WP_REST_Server::READABLE,
                'action' => 'AuthController::token',
            ],
            [
                'route' => 'authorization-url',
                'methods' => WP_REST_Server::READABLE,
                'action' => 'AuthController::authorization_url',
            ],
            [
                'route' => 'status',
                'methods' => WP_REST_Server::READABLE,
                'action' => 'StatusController::get',
            ],
            [
                'route' => 'search-engines',
                'methods' => WP_REST_Server::READABLE,
                'action' => 'DashboardController::getSearchEngines',
            ],
            [
                'route' => 'dashboard',
                'methods' => WP_REST_Server::CREATABLE,
                'action' => 'DashboardController::getDashboardData',
            ],
            [
                'route' => 'ranking',
                'methods' => WP_REST_Server::CREATABLE,
                'action' => 'DashboardController::getRankingHistory',
            ],
            [
                'route' => 'keywords',
                'methods' => WP_REST_Server::READABLE,
                'action' => 'DashboardController::getKeywords',
            ],
            [
                'route' => 'keywords',
                'methods' => WP_REST_Server::CREATABLE,
                'action' => 'DashboardController::createKeyword',
            ],
            [
                'route' => 'keywords',
                'methods' => WP_REST_Server::DELETABLE,
                'action' => 'DashboardController::deleteKeywords',
            ],
        ];

        $namespace = $this->getApiNamespace();

        foreach ($routes as $opts) {
            list($class, $callbackMethod) = explode('::', $opts['action']);
            $class = '\\Wincher\\Controller\\' . $class;
            $controller = new $class($this->getClient());

            register_rest_route($namespace, $opts['route'], [
                'methods' => $opts['methods'],
                'callback' => [$controller, $callbackMethod],
                'permission_callback' => [$controller, 'hasPermission'],
            ]);
        }
    }

    /**
     * Gets the API namespace.
     *
     * @return string the API namespace
     */
    private function getApiNamespace()
    {
        return self::SLUG . '/v' . self::VERSION;
    }

    /**
     * Gets the image's base64 code.
     *
     * @param string $path the path to the image
     *
     * @return string the image's base64 encoded string
     */
    private function getImageBase64($path)
    {
        $data = base64_encode(file_get_contents($path));

        return 'data:image/svg+xml;base64,' . $data;
    }
}
