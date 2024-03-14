<?php

namespace XCurrency\WpMVC\Providers;

use XCurrency\WpMVC\App;
use XCurrency\WpMVC\Contracts\Migration;
use XCurrency\WpMVC\Contracts\Provider;
class MigrationServiceProvider implements Provider
{
    public function boot()
    {
        add_action('init', [$this, 'action_init'], 5);
    }
    /**
     * Fires after WordPress has finished loading but before any headers are sent.
     *
     */
    public function action_init() : void
    {
        $migrations = App::$config->get('app.migrations');
        $current_version = App::$config->get('app.version');
        $option_key = App::$config->get('app.migration_db_option_key');
        $executed_migrations = get_option($option_key, []);
        foreach ($migrations as $key => $migration_class) {
            if (\in_array($key, $executed_migrations)) {
                continue;
            }
            $migration = App::$container->get($migration_class);
            if (!$migration instanceof Migration) {
                continue;
            }
            if (1 !== \version_compare($current_version, $migration->more_than_version())) {
                continue;
            }
            if ($migration->execute()) {
                $executed_migrations[] = $key;
                update_option($option_key, $executed_migrations);
            }
            break;
        }
    }
}
