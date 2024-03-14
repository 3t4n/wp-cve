<?php

namespace FluentSupport\App\Http\Policies;

use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\Framework\Request\Request;
use FluentSupport\Framework\Foundation\Policy;

class AdminSensitivePolicy extends Policy
{
    /**
     * Check user permission for any method
     * @param  \FluentSupport\Framework\Request\Request $request
     * @return Boolean
     */
    public function verifyRequest(Request $request)
    {
        return PermissionManager::currentUserCan('fst_sensitive_data');
    }

}
