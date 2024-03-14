<?php
namespace WEDOS\Mon\WP\ApiServer;

/**
 * REST API endpoint - validate pair token
 *
 * This API method is called from monitoring to check that this WordPress
 * instance has the correct pair token.
 *
 * @author    Petr Stastny <petr@stastny.eu>
 * @copyright WEDOS Internet, a.s.
 * @license   GPLv3
 */
class ValidatePairToken extends \PHPF\WP\Api\Endpoint
{
    /**
     * Register endpoint
     *
     * @return void
     */
    public static function registerEndpoint()
    {
        register_rest_route('wedosonline/v1', '/validatePairToken', [
            'methods' => 'POST',
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
        $this->requireInputData();

        if (empty($this->inputData->pairToken)) {
            $this->errorStop('C503', 'Missing input parameter: pairToken');
        }

        $localToken = get_option('won_pair_token');

        if ($localToken && $localToken === $this->inputData->pairToken) {
            $this->outputData->success = true;

        } else {
            $this->outputData->success = false;
        }
    }
}
