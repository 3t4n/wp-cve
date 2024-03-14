<?php

namespace WPSocialReviews\App\Http\Policies;

use WPSocialReviews\Framework\Request\Request;

class ShoppablePolicy extends BasePolicy
{
    /**
     * Check user permission for any method
     * @param  \WPSocialReviews\Framework\Request\Request $request
     * @return Boolean
     */
    public function verifyRequest(Request $request)
    {
        return $this->currentUserCan('wpsn_shoppable_settings') || $this->currentUserCan('wpsn_full_access');
    }

}
