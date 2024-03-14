<?php

namespace WPPayForm\App\Http\Policies;

use WPPayForm\App\Services\AccessControl;
use WPPayForm\Framework\Foundation\Policy;
use WPPayForm\Framework\Request\Request;

class FrontendUserPolicy extends Policy
{
    public function verifyRequest(Request $request)
    {
        return AccessControl::isPaymatticUser();
    }

    public function validate(Request $request)
    {
        return AccessControl::isPaymatticUser();
    }
}
