<?php
namespace WEDOS\Mon\WP\ApiServer;

/**
 * REST API endpoint - ping
 *
 * @author    Petr Stastny <petr@stastny.eu>
 * @copyright WEDOS Internet, a.s.
 * @license   GPLv3
 */
class Ping extends \PHPF\WP\Api\Endpoint
{
    /**
     * Register endpoint
     *
     * @return void
     */
    public static function registerEndpoint()
    {
        register_rest_route('wedosonline/v1', '/ping', [
            'methods' => 'GET',
            'callback' => [__CLASS__, 'executeStatic'],
            'permission_callback' => '__return_true',
        ]);
    }


    /**
     * Process REST API request
     *
     * @return void
     */
    protected function process()
    {
        $this->outputData->version = WEDOSONLINE_VERSION;
    }
}
