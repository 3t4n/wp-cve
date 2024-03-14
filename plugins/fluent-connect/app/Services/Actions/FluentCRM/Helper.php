<?php

namespace FluentConnect\App\Services\Actions\FluentCRM;

use FluentConnect\Framework\Support\Arr;
use FluentCrm\App\Models\Subscriber;

class Helper
{
    public static function createOrUpdateContact($data, $checkExist = false)
    {
        // check if the contact is available
        if (empty($data['email']) || !is_email($data['email'])) {
            return new \WP_Error('invalid_email', 'Email is invalid. This action can not be run');
        }

        $email = $data['email'];

        if($checkExist) {
            $exist = FluentCrmApi('contacts')->getContact($email);
            if ($exist) {
                return new \WP_Error('skipped', 'Contact already exist. Action has been skipped');
            }
        }

        $contactData = array_filter(Arr::only($data, (new Subscriber)->getFillable()));

        $contact = FluentCrmApi('contacts')->createOrUpdate($contactData);

        if ($contact->status != 'subscribed' && $contact->wasRecentlyCreated) {
            $contact->sendDoubleOptinEmail();
        }

        return $contact;
    }

    public static function getContactViewUrl($contactId)
    {
        $baseUrl = $urlBase = apply_filters(
            'fluentcrm_menu_url_base',
            admin_url('admin.php?page=fluentcrm-admin#/')
        );;

        return $baseUrl.'subscribers/'.$contactId;

    }
}
