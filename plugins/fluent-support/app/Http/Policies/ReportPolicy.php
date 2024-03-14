<?php

namespace FluentSupport\App\Http\Policies;

use FluentSupport\App\Modules\PermissionManager;
use FluentSupport\Framework\Request\Request;
use FluentSupport\Framework\Foundation\Policy;

class ReportPolicy extends Policy
{
    /**
     * Check user permission for any method
     * @param \FluentSupport\Framework\Request\Request $request
     * @return Boolean
     */
    public function verifyRequest(Request $request)
    {
        $permissions = PermissionManager::currentUserPermissions();
        $acceptedPermissions = ['fst_view_all_reports'];

        return !!array_intersect($permissions, $acceptedPermissions);
    }
}
