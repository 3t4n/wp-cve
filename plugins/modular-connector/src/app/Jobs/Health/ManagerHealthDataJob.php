<?php

namespace Modular\Connector\Jobs\Health;

use Modular\Connector\Events\ManagerHealthUpdated;
use Modular\Connector\Jobs\AbstractJob;

class ManagerHealthDataJob extends AbstractJob
{
    /**
     * @var string
     */
    protected string $mrid;

    /**
     * @var string
     */
    protected string $type;

    /**
     * @var array
     */
    protected array $tests;

    protected array $excluded = [
        'wordpress_version'
    ];

    /**
     * @param string $mrid
     * @param string $type
     * @param array $tests
     */
    public function __construct(string $mrid, string $type, array $tests)
    {
        $this->mrid = $mrid;
        $this->type = $type;
        $this->tests = $tests;
    }

    /**
     * @return void
     */
    public function handle()
    {
        if (!class_exists('WP_Site_Health')) {
            require_once ABSPATH . 'wp-admin/includes/class-wp-site-health.php';
        }

        if (!function_exists('get_plugins')) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }

        if (!function_exists('get_plugin_updates')) {
            require_once ABSPATH . 'wp-admin/includes/update.php';
        }

        if (!function_exists('wp_check_php_version')) {
            require_once ABSPATH . 'wp-admin/includes/misc.php';
        }

        $adminLocale = get_locale();
        load_textdomain('default', WP_LANG_DIR . '/admin-' . $adminLocale . '.mo', $adminLocale);
        unset($adminLocale);

        $allTests = \WP_Site_Health::get_tests();
        $allTests = $allTests[$this->type];

        if ($this->type === 'direct') {
            $data = $this->handleDirectTests($allTests);
        } else {
            $data = $this->handleAsyncTests($allTests);
        }

        if (!empty($data)) {
            ManagerHealthUpdated::dispatch($this->mrid, $data);
        }
    }

    /**
     * @param array $allTests
     * @return array
     */
    protected function handleDirectTests(array $allTests)
    {
        $data = [];

        $health = \WP_Site_Health::get_instance();

        foreach ($this->tests as $test) {
            $testName = $allTests[$test]['test'];
            $testMethod = 'get_test_' . $testName;

            if (!method_exists($health, $testMethod)) {
                continue;
            }

            $data[$testName] = $health->{$testMethod}();
        }

        return $data;
    }

    /**
     * @param array $allTests
     * @return array
     */
    protected function handleAsyncTests(array $allTests)
    {
        $data = [];

        foreach ($this->tests as $testName) {
            $test = $allTests[$testName];

            if (!empty($test['async_direct_test']) && is_callable($test['async_direct_test'])) {
                $data[$testName] = apply_filters('site_status_test_result', call_user_func($test['async_direct_test']));
                continue;
            }

            if (is_string($test['test'])) {
                // Check if this test has a REST API endpoint.
                if (isset($test['has_rest']) && $test['has_rest']) {
                    $result_fetch = wp_remote_get(
                        $test['test'],
                        [
                            'body' => [
                                '_wpnonce' => wp_create_nonce('wp_rest'),
                            ],
                        ]
                    );
                } else {
                    $result_fetch = wp_remote_post(
                        admin_url('admin-ajax.php'),
                        [
                            'body' => [
                                'action' => $test['test'],
                                '_wpnonce' => wp_create_nonce('health-check-site-status'),
                            ],
                        ]
                    );
                }

                if (!is_wp_error($result_fetch) && wp_remote_retrieve_response_code($result_fetch) === 200) {
                    $data[$testName] = json_decode(wp_remote_retrieve_body($result_fetch), true);
                }
            }
        }

        return $data;
    }
}