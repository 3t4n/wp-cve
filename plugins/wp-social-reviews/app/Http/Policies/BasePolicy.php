<?php

namespace WPSocialReviews\App\Http\Policies;

use WPSocialReviews\Framework\Foundation\Policy;
use WPSocialReviews\Framework\Request\Request;
use WPSocialReviews\App\Services\PermissionManager;


/**
 *  BasePolicy - REST API Permission Policy
 *
 * @package WPSocialReviews\App\Http
 *
 * @version 1.0.0
 */
class BasePolicy extends Policy
{

    /**
     * Check user permission for any method
     * @param  \WPSocialReviews\Framework\Request\Request $request
     * @return Boolean
     */
    public function verifyRequest(Request $request)
    {
        return $this->currentUserCan('manage_options');
    }

    public function currentUserCan($permission)
    {
        return PermissionManager::currentUserCan($permission);
    }
}
