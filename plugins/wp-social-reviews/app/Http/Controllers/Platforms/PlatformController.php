<?php

namespace WPSocialReviews\App\Http\Controllers\Platforms;

use WPSocialReviews\App\Http\Controllers\Controller;
use WPSocialReviews\Framework\Request\Request;
use WPSocialReviews\App\Services\Platforms\Reviews\Helper;
use WPSocialReviews\App\Services\DashboardNotices;
use WPSocialReviews\App\Services\Platforms\PlatformErrorManager;
use WPSocialReviews\Framework\Support\Arr;

class PlatformController extends Controller
{
    public function index()
    {
        $platforms = Helper::validPlatforms();

        return [
            'message'   => 'success',
            'platforms' => $platforms
        ];
    }

    public function getDashboardNotices(Request $request, DashboardNotices $notices)
    {
        $hasAdminErrors = (new PlatformErrorManager())->getAdminErrors();
        $displayOptInNotice = $notices->maybeDisplayOptIn();
        $displayProUpdateNotice = $notices->maybeDisplayProUpdateNotice();


        $current_user = wp_get_current_user();
        if (!empty($current_user->user_email)) {
            $email = $current_user->user_email;
        } else {
            $email = get_option('admin_email');
        }

        $userData = [
            'name'  => $current_user->first_name . ' ' .$current_user->last_name,
            'email' => $email,
        ];

        wp_send_json_success([
            'displayNotice' => empty($hasAdminErrors) && $notices->getNoticesStatus(),
            'displayOptInNotice'  => empty($hasAdminErrors) && $displayOptInNotice,
            'displayProUpdateNotice' => $displayProUpdateNotice,
            'userData' => $notices->maybeDisplayNewsletter() ? $userData : [],
            'displayNewsletter' => empty($hasAdminErrors) && $notices->maybeDisplayNewsletter()
        ], 200);
    }

    public function updateDashboardNotices(Request $request, DashboardNotices $notices)
    {
        $args = $request->get('args');
        $value = Arr::get($args, 'value');
        $notices->updateNotices($args);

        wp_send_json_success([
            'displayNotice' => $notices->getNoticesStatus(),
            'displayOptInNotice' => $notices->maybeDisplayOptIn(),
            'displayNewsletter' => !($value === '1')
        ], 200);
    }

    public function processSubscribeQuery(Request $request, DashboardNotices $notices)
    {
        $args = $request->get('args');
        $status = $notices->updateNewsletter($args);

        return [
            'status'  => 'success',
            'message' => $status,
            'displayNewsletter' => $notices->maybeDisplayNewsletter(),
        ];
    }

    public function enabledPlatforms(Request $request)
    {
        $reviewsPlatforms   = apply_filters('wpsocialreviews/available_valid_reviews_platforms', []);
        $feedPlatforms      = apply_filters('wpsocialreviews/available_valid_feed_platforms', []);
        $platforms = $reviewsPlatforms + $feedPlatforms;

        $hasAdminErrors = (new PlatformErrorManager())->getAdminErrors();
        return [
            'notices' => $hasAdminErrors,
            'platforms'   => $platforms
        ];
    }
}
