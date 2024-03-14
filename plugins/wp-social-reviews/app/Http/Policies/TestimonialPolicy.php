<?php

namespace WPSocialReviews\App\Http\Policies;

use WPSocialReviews\Framework\Request\Request;

class TestimonialPolicy extends BasePolicy
{
	/**
	 * Check user permission for any method
	 * @param  \WPSocialReviews\Framework\Request\Request $request
	 * @return Boolean
	 */
	public function verifyRequest(Request $request)
	{
        return $this->currentUserCan('wpsn_manage_testimonials') || $this->currentUserCan('wpsn_full_access');

    }

}
