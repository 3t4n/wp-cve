<?php

namespace FluentConnect\App\Services\Actions\FluentCRM;

use FluentConnect\App\Services\Actions\BaseAction;
use FluentConnect\Framework\Support\Arr;
use FluentCrm\App\Models\Lists;

class RemoveFromList extends BaseAction
{
    public function __construct()
    {
        $this->actionProvider = 'fluentcrm';
        $this->actionName = 'fconnect_fluentcrm_remove_from_list';
    }

    public function getInfo()
    {
        return [
            'title'                  => 'Remove Lists from FluentCRM Contact',
            'description'            => 'This task will remove selected lists from contact (if exist)',
            'has_remote_integration' => false,
            'is_enabled'             => $this->isEnabled(),
            'disabled_message'       => 'Please install FluentCRM Plugin first',
            'settings_defaults'      => $this->getSettingsDefaults(),
            'required_data'          => [
                'email'
            ]
        ];
    }

    public function getSettingsDefaults()
    {
        return [
            'list_ids' => []
        ];
    }

    public function getSettingsFields($action)
    {

        return [
            'list_ids' => [
                'type'        => 'input-options',
                'label'       => 'Select Tags',
                'is_multiple' => true,
                'placeholder' => 'Select FluentCRM Lists',
                'options'     => Lists::orderBy('title', 'ASC')->select(['id', 'title'])->get()
            ]
        ];
    }

    public function process($action, $data)
    {
        $listIds = Arr::get($action->settings, 'list_ids', []);

        if (!$listIds) {
            return new \WP_Error('skipped', 'No Lists has been configured in the action');
        }

        $email = Arr::get($data, 'email');

        if (!$email || !$contact = FluentCrmApi('contacts')->getContact($email)) {
            return new \WP_Error('skipped', 'Action has been skipped. No contact found on FluentCRM');
        }

        $contact->detachLists($listIds);

        return [
            'status'           => 'completed',
            'message'          => 'Remove From List: Selected lists has been removed from the FluentCRM contact',
            'remote_action_id' => $contact->id,
            'reference_url' => Helper::getContactViewUrl($contact->id)
        ];
    }
}
