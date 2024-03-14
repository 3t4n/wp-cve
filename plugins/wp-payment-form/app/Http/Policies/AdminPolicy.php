<?php

namespace WPPayForm\App\Http\Policies;

use WPPayForm\App\Services\AccessControl;
use WPPayForm\Framework\Foundation\Policy;
use WPPayForm\Framework\Request\Request;

class AdminPolicy extends Policy
{
    public function verifyRequest(Request $request)
    {
        return AccessControl::hasEndPointPermission();
    }

    public function proPolicy()
    {
        return AccessControl::hasEndPointPermission();
    }
}
