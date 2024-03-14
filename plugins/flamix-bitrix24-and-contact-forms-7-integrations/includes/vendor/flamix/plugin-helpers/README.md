![Screenshot](img/plugin_helpers.jpg)

# About

Microframework for writing CMS plugins, contains development logic and several helpers.

# Install & Usage

```bash
composer require flamix/plugin-helpers
```

```php
// Init
$plugin = \Flamix\Plugin\Init::init(__DIR__, 'FLAMIX_EXCHANGE_PLUGIN')->setLogsPath(WP_CONTENT_DIR . '/upload/flamix/');
// Show witch constant was defined
dd($plugin->defined());
```


## Queue

Example how to use Jobs.

```php
use Flamix\Plugin\Queue\SQL;
use Flamix\Plugin\Queue\JobCommands;
use Flamix\Plugin\Queue\Interfaces\ShouldQueue;

class Order extends JobCommands implements ShouldQueue
{
    protected string $success = 'SENT';
    protected string $logChannel = 'woo_orders';

    /**
     * Return SQL bridge.
     * Raw SQL commands for work with JOBs.
     *
     * @return SQL
     */
    public function sqlClosure(): SQL
    {
        global $wpdb;
        return new SQL($wpdb->prefix . 'flamix_order_jobs', function ($query, ...$var) use ($wpdb) {
            return $wpdb->prepare($query, ...$var);
        });
    }

    /**
     * Make WP Query to DB and return result.
     *
     * @param string $query
     * @return array|object|\stdClass[]|null
     */
    public function query(string $query)
    {
        global $wpdb;
        return $wpdb->get_results($query);
    }
}
```