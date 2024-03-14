<?php

namespace Modular\Connector\Services;

use Modular\Connector\Facades\Core;
use Modular\Connector\Facades\Plugin;
use Modular\Connector\Facades\Theme;
use Modular\Connector\Facades\Translation;
use Modular\Connector\Facades\WhiteLabel;
use Modular\Connector\Queue\Worker;
use Modular\Connector\Services\Helpers\Database;
use Modular\Connector\Services\Helpers\Utils;
use Modular\Connector\Services\Manager\ManagerBackup;
use Modular\Connector\Services\Manager\ManagerCore;
use Modular\Connector\Services\Manager\ManagerDatabase;
use Modular\Connector\Services\Manager\ManagerPlugin;
use Modular\Connector\Services\Manager\ManagerServer;
use Modular\Connector\Services\Manager\ManagerTheme;
use Modular\Connector\Services\Manager\ManagerTranslation;
use Modular\Connector\Services\Manager\ManagerWhiteLabel;
use Modular\ConnectorDependencies\Illuminate\Contracts\Http\Kernel;
use function Modular\ConnectorDependencies\app;
use function Modular\ConnectorDependencies\base_path;
use function Modular\ConnectorDependencies\request;

/**
 * This class receives the requests processed by the HandleController.php and delegates in to the specialized managers.
 */
class Manager
{
    /**
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function init()
    {
        if (function_exists('add_action')) {
            add_action('modular_queue_start', function () {
                new Worker();
                new Worker('backups');
            });

            add_action('modular_shutdown', function () {
                // Force destroy the queue dispatcher to force execute __destruct() method.
                if (!Utils::isModularRequest()) {
                    return;
                }

                $app = app()->make(Kernel::class);
                $app->terminate(request(), null);
            });
        }

        if (function_exists('add_filter')) {
            add_filter('plugin_action_links', [$this, 'setActionLinks'], 10, 2);

            WhiteLabel::init();
        }
    }

    /**
     * @param $links
     * @param $plugin
     * @return array|mixed|null
     */
    public function setActionLinks($links = null, $plugin = null)
    {
        // if you use this action hook inside main plugin file, use basename(__FILE__) to check
        $path = str_replace('\\', '/', realpath(base_path('../init.php')));
        $path = preg_replace('|(?<=.)/+|', '/', $path);

        $plugin = str_replace('\\', '/', $plugin);
        $plugin = preg_replace('|(?<=.)/+|', '/', $plugin);

        if (strpos($path, $plugin)) {
            $links[] = sprintf('<a href="%s">%s</a>', menu_page_url('modular-connector', false), __('Connection manager', 'modular-connector'));
        }

        return $links;
    }

    /**
     * Returns an instance of the provided driver $name if existing, or throws an Exception if not.
     *
     * @param $name
     * @return
     * @throws \Exception
     */
    public function resolve(string $name)
    {
        switch ($name) {
            case 'plugin':
                return new ManagerPlugin();
            case 'theme':
                return new ManagerTheme();
            case 'core':
                return new ManagerCore();
            case 'backup':
                return new ManagerBackup();
            case 'translation':
                return new ManagerTranslation();
            case 'database':
                return new ManagerDatabase();
            case 'server':
                return new ManagerServer();
            case 'white-label':
                return new ManagerWhiteLabel();
            default:
                throw new \Exception("{$name} driver is not registered.");
        }
    }

    /**
     * Logs into WordPress as the first available administrador user.
     *
     * @return void
     * @throws \Exception
     */
    public function login()
    {
        $databaseUtils = new Database();
        $user = $databaseUtils->getFirstAdministratorUser();

        if (!$user) {
            // TODO Make a custom exception
            throw new \Exception('No admin user detected.');
        }

        if (
            !function_exists('wp_set_current_user') ||
            !function_exists('wp_set_auth_cookie')
        ) {
            include_once ABSPATH . WPINC . '/pluggable.php';
        }

        if (
            !function_exists('wp_cookie_constants')
        ) {
            include_once ABSPATH . WPINC . '/default-constants.php';
        }

        // Authenticated user
        wp_cookie_constants();

        // Log in with the new user
        wp_set_current_user($user->ID, $user->user_login);
        wp_set_auth_cookie($user->ID);

        // Redirect to WordPress admin panel
        wp_redirect(admin_url('index.php'));
        exit;
    }

    /**
     * Returns a list with the existing plugins and themes.
     *
     * @return array
     */
    public function update()
    {
        return [
            'core' => Core::get(),
            'plugins' => Plugin::all(),
            'themes' => Theme::all(),
            'translations' => Translation::get(),
        ];
    }
}
