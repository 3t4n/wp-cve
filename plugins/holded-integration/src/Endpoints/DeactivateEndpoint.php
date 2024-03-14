<?php

declare(strict_types=1);

namespace Holded\Woocommerce\Endpoints;

use Holded\Woocommerce\Services\Settings;

final class DeactivateEndpoint extends AbstractEndpoint
{
    public function init(): void
    {
        add_action('rest_api_init', [$this, 'registerEndpoint']);
    }

    public function registerEndpoint(): void
    {
        register_rest_route($this->apiNamespace, '/deactivate', [
            'methods'             => \WP_REST_Server::EDITABLE,
            'callback'            => [$this, 'deactivate'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * @return \WP_Error|\WP_REST_Response
     */
    public function deactivate(\WP_REST_Request $request)
    {
        $authentication = $this->authentication($request);
        if (is_wp_error($authentication) && is_object($authentication)) {
            return $authentication;
        }

        Settings::getInstance()->removeApiKey();

        return new \WP_REST_Response([], 200);
    }
}
