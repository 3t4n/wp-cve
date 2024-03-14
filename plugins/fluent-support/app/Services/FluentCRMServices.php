<?php

namespace FluentSupport\App\Services;

use Exception;

class FluentCRMServices
{
    /**
     * This `syncCrmTags` method is used to sync tags from FluentCRM to Fluent Support.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function syncCrmTags ( $data )
    {

        $this->isCrmEnabled();
        $this->isContactAvailable( $data['contact_id'] );

        $tags = $data['tags'] ?? [];

        $tagIds = array_filter( $tags, 'absint' );
        $this->checkCrmPermission();

        $contact = \FluentCrm\App\Models\Subscriber::findOrFail( $data['contact_id'] );

        $this->addOrRemoveTag( $contact, $tagIds );

        return [
            'tags'    => $contact->tags,
            'message' => __('FluentCRM contact tags has been updated', 'fluent-support')
        ];
    }

    /**
     * This `syncCrmLists` method is used to sync lists from FluentCRM to Fluent Support.
     * @param array $data
     * @return array
     * @throws Exception
     */
    public function syncCrmLists ( $data )
    {
        $this->isCrmEnabled();
        $this->isContactAvailable( $data['contact_id'] );

        $lists = $data['lists'] ?? [];

        $listIds = array_filter( $lists, 'absint' );
        $this->checkCrmPermission();

        $contact = \FluentCrm\App\Models\Subscriber::findOrFail( $data['contact_id'] );

        $this->addOrRemoveList( $contact, $listIds );

        return [
            'lists'    => $contact->lists,
            'message' => __('FluentCRM contact lists has been updated', 'fluent-support')
        ];
    }

    /**
     * This `addOrRemoveTag` method will add or remove tags from FluentCRM contact from Fluent Support.
     * @param array $contact
     * @param array $tagIds
     * @return mixed
     */
    public function addOrRemoveTag ( $contact, $tagIds )
    {
        $existingTags = $contact->tags;
        $existingTagIds = [];
        foreach ($existingTags as $tag) {
            $existingTagIds[] = $tag->id;
        }
        $newTagIds = array_diff($tagIds, $existingTagIds);
        $removedTagIds = array_diff($existingTagIds, $tagIds);

        if ($newTagIds) {
            $contact->attachTags($newTagIds);
        }

        if ($removedTagIds) {
            $contact->detachTags($removedTagIds);
        }

        return $contact;
    }

    /**
     * This `addOrRemoveList` method will add or remove lists from FluentCRM contact from Fluent Support.
     * @param array $contact
     * @param array $listIds
     * @return mixed
     */
    public function addOrRemoveList ( $contact, $listIds )
    {
        $existingLists = $contact->lists;
        $existingListIds = [];

        foreach ($existingLists as $list) {
            $existingListIds[] = $list->id;
        }
        $newListIds = array_diff($listIds, $existingListIds);
        $removedListIds = array_diff($existingListIds, $listIds);

        if ($newListIds) {
            $contact->attachLists( $newListIds );
        }

        if ($removedListIds) {
            $contact->detachLists( $removedListIds );
        }

        return $contact;
    }

    // This `checkCrmPermission` method will check current agent has permission to access to modify customer's FluentCRM data
    private function checkCrmPermission ()
    {
        $canAddTags = \FluentCrm\App\Services\PermissionManager::currentUserCan('fcrm_manage_contacts');
        $canAddTags = apply_filters('fluent_support/can_user_add_tags_to_customer', $canAddTags);

        if (!$canAddTags) {
            throw new \Exception( 'Sorry you do not have permission to add contact tags' );
        } else {
            return true;
        }
    }

    // This `isCrmEnabled` method will check FluentCRM is enabled or not
    private function isCrmEnabled ()
    {
        if ( !defined('FLUENTCRM') ) {
           throw new \Exception('FluentCRM is not installed or Enabled');
        } else {
            return true;
        }
    }

    // This `isContactAvailable` method will check if contact is valid or not
    private function isContactAvailable ( $contactId )
    {
        if (!$contactId) {
            throw new \Exception('Contact could not be found');
        } else {
            return true;
        }
    }
}
