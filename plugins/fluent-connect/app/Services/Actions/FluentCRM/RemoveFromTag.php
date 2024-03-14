<?php

namespace FluentConnect\App\Services\Actions\FluentCRM;

use FluentConnect\App\Services\Actions\BaseAction;
use FluentConnect\Framework\Support\Arr;
use FluentCrm\App\Models\Tag;

class RemoveFromTag extends BaseAction
{
    public function __construct()
    {
        $this->actionProvider = 'fluentcrm';
        $this->actionName = 'fconnect_fluentcrm_remove_tag';
    }

    public function getInfo()
    {
        return [
            'title'                  => 'Remove Tag From FluentCRM Contact',
            'description'            => 'This will remove tags from the contact if exist',
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
            'tag_ids'            => []
        ];
    }

    public function getSettingsFields($action)
    {
        return [
            'tag_ids'            => [
                'type'        => 'input-options',
                'label'       => 'Select Tags',
                'is_multiple' => true,
                'placeholder' => 'Select FluentCRM Tags',
                'options'     => Tag::orderBy('title', 'ASC')->select(['id', 'title'])->get()
            ]
        ];
    }


    public function process($action, $data)
    {
        $tagIds = Arr::get($action->settings, 'tag_ids', []);

        if(!$tagIds) {
            return new \WP_Error('skipped', 'No Tags has been configured in the action');
        }

        $email = Arr::get($data, 'email');

        if(!$email || !$contact = FluentCrmApi('contacts')->getContact($email)) {
            return new \WP_Error('skipped', 'Action has been skipped. No contact found on FluentCRM');
        }

        $contact->detachTags($tagIds);

        return [
            'status' => 'completed',
            'message' => 'Remove From Tag: Selected tags has been removed from the FluentCRM contact',
            'remote_action_id' => $contact->id,
            'reference_url' => Helper::getContactViewUrl($contact->id)
        ];
    }
}
