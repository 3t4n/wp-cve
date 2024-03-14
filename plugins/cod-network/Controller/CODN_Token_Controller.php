<?php

namespace CODNetwork\Controller;

use CODNetwork\Repositories\CodNetworkRepository;
use CODNetwork\Services\CODN_Logger_Service;
use Throwable;
use WC_REST_Controller;
use WP_Error;
use WP_Http;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class CODN_Token_Controller extends WC_REST_Controller
{
    const MAX_TOKEN_LENGTH = 500;
    /**
     * Endpoint namespace.
     *
     * @var string
     */
    protected $namespace = 'wc/v2';

    /**
     * Route base.
     *
     * @var string
     */
    protected $rest_base = '/cod-network/v1/token';

    /** @var CODN_Logger_Service */
    private $logger;

    public function __construct()
    {
        $this->logger = new CODN_Logger_Service();
    }

    public function register_routes()
    {
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            [
                [
                    'methods' => WP_REST_Server::CREATABLE,
                    'callback' => [$this, 'store'],
                    'permission_callback' => [$this, 'get_items_permissions_check']
                ],
                'schema' => [$this, "get_public_item_schema"]
            ]
        );
        register_rest_route(
            $this->namespace,
            $this->rest_base,
            [
                [
                    'methods' => WP_REST_Server::DELETABLE,
                    'callback' => [$this, 'delete'],
                    'permission_callback' => [$this, 'get_items_permissions_check']
                ],
                'schema' => [$this, "get_public_item_schema"]
            ]
        );
    }

    public function get_items_permissions_check($request)
    {
        if (!wc_rest_check_manager_permissions('settings', 'read')) {
            $this->logger->info('permissions unauthorized to use wc rest');

            return new WP_Error(
                WP_Http::UNAUTHORIZED, esc_html__('unauthorized'),
                [
                    'status' => WP_Http::UNAUTHORIZED
                ]
            );
        }

        return true;
    }

    /**
     * @param WP_REST_Request $request
     * @return WP_REST_Response|WP_Error|mixed
     */
    public function store($request)
    {
        try {
            $parameters = $request->get_params();

            $token = $parameters['token'] ?? null;
            if (!isset($token)) {
                return new WP_Error(
                    WP_Http::BAD_REQUEST, esc_html__('missed token'),
                    [
                        'status' => WP_Http::BAD_REQUEST
                    ]
                );
            }

            $token = sanitize_textarea_field($token);
            if (strlen($token) >= self::MAX_TOKEN_LENGTH) {
                $this->logger->info(
                    sprintf(
                        'invalid token the maximum length of token is %s',
                        self::MAX_TOKEN_LENGTH
                    )
                );

                return new WP_Error(
                    WP_Http::BAD_REQUEST,
                    esc_html__('invalid token'),
                    ['status' => WP_Http::BAD_REQUEST]
                );
            }

            $codNetworkRepository = CodNetworkRepository::get_instance();
            $codNetworkRepository->create_or_update_token($token);
            $this->logger->info('saved token');

            return wp_send_json_success(['message' => 'saved token'], WP_Http::OK);
        } catch (Throwable $exception) {
            $this->logger->error(
                "something went wrong while saving token",
                [
                    'extra.message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return new WP_Error(
                WP_Http::INTERNAL_SERVER_ERROR,
                esc_html__('something went wrong while saving token'),
                [
                    'status' => WP_Http::INTERNAL_SERVER_ERROR
                ]
            );
        }
    }

    public function delete()
    {
        try {
            $codNetworkRepository = CodNetworkRepository::get_instance();
            $codNetworkRepository->delete_token();

            return wp_send_json_success(["message" => "deleted token"], WP_Http::OK);
        } catch (Throwable $exception) {
            $this->logger->error(
                "something went wrong while deleted token",
                [
                    'extra.message' => $exception->getMessage(),
                    'trace' => $exception->getTraceAsString()
                ]
            );

            return new WP_Error(
                WP_Http::INTERNAL_SERVER_ERROR,
                esc_html__('something went wrong while deleted token'),
                [
                    'status' => WP_Http::INTERNAL_SERVER_ERROR
                ]
            );
        }
    }
}
