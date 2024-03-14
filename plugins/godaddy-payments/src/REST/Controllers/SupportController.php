<?php
/**
 * Poynt — a GoDaddy Brand for WooCommerce.
 *
 * @author GoDaddy
 * @copyright Copyright (c) 2021-2024 GoDaddy Operating Company, LLC. All Rights Reserved.
 * @license GPL-2.0
 */

namespace GoDaddy\WooCommerce\Poynt\REST\Controllers;

use Exception;
use GoDaddy\WooCommerce\Poynt\Support;
use GoDaddy\WooCommerce\Poynt\Support\Http\Adapters\RequestAdapter;
use GoDaddy\WooCommerce\Poynt\Support\Http\Request as SupportRequest;
use SkyVerge\WooCommerce\PluginFramework\v5_12_1\SV_WC_Plugin_Exception;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;

defined('ABSPATH') or exit;

/**
 * Support controller.
 *
 * @since 1.2.0
 */
class SupportController extends AbstractController
{
    /** @var string the base of this controller's route */
    protected $rest_base = 'support';

    /**
     * Registers the routes.
     *
     * @since 1.2.0
     */
    public function register_routes()
    {
        register_rest_route(
            $this->namespace, "/{$this->rest_base}/requests", [
                [
                    'methods'             => 'POST',
                    'callback'            => [$this, 'createItem'],
                    'permission_callback' => [$this, 'createItemPermissionsCheck'],
                    'args'                => $this->getItemSchemaProperties(),
                ],
                'schema' => [$this, 'get_item_schema'],
            ]);
    }

    /**
     * Gets the item schema properties.
     *
     * @since 1.2.0
     *
     * @return array[]
     */
    private function getItemSchemaProperties() : array
    {
        return [
            'createUser' => [
                'required'    => false,
                'description' => __('Whether to create a new support user.', 'godaddy-payments'),
                'type'        => 'boolean',
                'context'     => ['view', 'edit'],
            ],
            'emailAddresses' => [
                'required'    => true,
                'description' => __('The email address to which the request should be attributed.', 'godaddy-payments'),
                'type'        => 'array',
                'context'     => ['view', 'edit'],
            ],
            'message' => [
                'required'    => true,
                'description' => __('The submitted message.', 'godaddy-payments'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
            'reason' => [
                'required'    => true,
                'description' => __('The submitted reason slug.', 'godaddy-payments'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
            'subject' => [
                'required'    => false,
                'description' => __('The submitted subject.', 'godaddy-payments'),
                'type'        => 'string',
                'context'     => ['view', 'edit'],
            ],
        ];
    }

    /**
     * Gets the item schema.
     *
     * @since 1.2.0
     *
     * @return array
     */
    public function get_item_schema() : array
    {
        return $this->add_additional_fields_schema([
            '$schema'    => 'http://json-schema.org/draft-04/schema#',
            'title'      => 'support',
            'type'       => 'object',
            'properties' => $this->getItemSchemaProperties(),
        ]);
    }

    /**
     * Creates a support request item.
     *
     * @internal
     *
     * @since 1.2.0
     *
     * @param WP_REST_Request $request
     * @return WP_REST_Response returns a 200 response with empty payload on success, or a response with an error code and message details on failure
     */
    public function createItem(WP_REST_Request $request) : WP_REST_Response
    {
        $params = $request->get_params();

        if (! empty($params['createUser'])) {
            $this->createUser();
        }

        try {
            $rawResponse = $this->adaptNewRequest($request)->send();
            $parsedCode = wp_remote_retrieve_response_code($rawResponse);
            $responseCode = is_numeric($parsedCode) ? (int) $parsedCode : 500;

            if ($responseCode < 300) {
                $responseData = [];
            } elseif ($parsedMessage = wp_remote_retrieve_response_message($rawResponse)) {
                $responseData = ['message' => $parsedMessage];
            } else {
                $responseData = ['message' => __('An error occurred.', 'godaddy-payments')];
            }
        } catch (Exception $e) {
            $responseCode = (int) $e->getCode();
            $responseData = ['message' => $e->getMessage()];
        }

        return new WP_REST_Response($responseData, $responseCode);
    }

    /**
     * Checks the permissions for creating an item.
     *
     * @internal
     *
     * @since 1.2.0
     *
     * @return bool|WP_Error
     */
    public function createItemPermissionsCheck()
    {
        return wc_rest_check_manager_permissions('payment_gateways') ?: new WP_Error(403);
    }

    /**
     * Creates a WordPress support user.
     *
     * @since 1.2.0
     *
     * @return int|null returns the user ID or null if the user wasn't created (maybe because it already existed)
     */
    private function createUser()
    {
        $userId = wp_create_user(Support::SUPPORT_USER_HANDLE, wp_generate_password(), Support::SUPPORT_USER_EMAIL);

        if (is_wp_error($userId)) {
            return null;
        }

        if ($user = get_user_by('id', $userId)) {
            $user->set_role('administrator');
        }

        return $userId;
    }

    /**
     * Adapts a WordPress request.
     *
     * @since 1.2.0
     *
     * @param WP_REST_Request $request
     * @return SupportRequest
     * @throws SV_WC_Plugin_Exception
     */
    private function adaptNewRequest(WP_REST_Request $request) : SupportRequest
    {
        $sanitizedParams = [];

        foreach ($request->get_params() as $key => $value) {
            if (array_key_exists($key, $this->getItemSchemaProperties())) {
                switch ($key) {
                    case 'createUser':
                        $sanitizedParams[$key] = (bool) $value;
                        break;
                    case 'message':
                        $sanitizedParams[$key] = wc_sanitize_textarea($value);
                        break;
                    default:
                        $sanitizedParams[$key] = wc_clean($value);
                        break;
                }
            } else {
                $sanitizedParams[$key] = $value;
            }
        }

        return (new RequestAdapter($sanitizedParams))->convertFromSource();
    }
}
