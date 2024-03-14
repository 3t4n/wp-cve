<?php

namespace WPPayForm\App\Services\Integrations\FluentCrm;

use WPPayForm\Framework\Foundation\App;
use WPPayForm\Framework\Support\Arr;

class FluentCrmInit
{
    public function init()
    {
        new \WPPayForm\App\Services\Integrations\FluentCrm\Bootstrap();
        add_filter('wppayform_single_entry_widgets', array($this, 'pushContactWidget'), 10, 3);
        add_filter('wppayform_customer_profile', array($this, 'getCustomerProfile'), 10, 2);
    }

    public function getCustomerProfile($profiles, $email)
    {
        return fluentcrm_get_crm_profile_html($email, false);
    }

    public function pushContactWidget($widgets, $entryData)
    {
        $userId = $entryData['submission']->user_id;

        if ($userId) {
            $maybeEmail = Arr::get($entryData['submission']->user, 'email');
            if (!$maybeEmail) {
                $maybeEmail = $userId;
            }
        } else {
            $maybeEmail = $entryData['submission']->customer_email;
        }

        if (!$maybeEmail) {
            return $widgets;
        }

        $profileHtml = fluentcrm_get_crm_profile_html($maybeEmail, false);

        if (!$profileHtml) {
            return $widgets;
        }

        $widgets['fluent_crm'] = [
            'title' => __('FluentCRM Profile', 'fluent-crm'),
            'content' => $profileHtml
        ];
        return $widgets;
    }
}
