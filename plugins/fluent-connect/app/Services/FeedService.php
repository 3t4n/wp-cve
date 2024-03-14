<?php

namespace FluentConnect\App\Services;

use FluentConnect\App\Models\Action;
use FluentConnect\App\Models\Feed;
use FluentConnect\App\Models\Trigger;
use FluentConnect\Framework\Support\Arr;

class FeedService
{
    public static function syncActions($feedId, $inputActions)
    {
        $syncIds = [];

        foreach ($inputActions as $index => $inputAction) {

            $actionData = Arr::only($inputAction, ['id', 'priority', 'title', 'action_name', 'action_provider', 'status', 'settings']);
            $actionData = array_filter($actionData);

            $actionData['feed_id'] = $feedId;
            $actionData['priority'] = $index + 1;

            // Check if we have $id
            if(!empty($inputTrigger['id'])) {
                unset($actionData['id']);
                $actionData['settings'] = maybe_serialize($actionData['settings']);
                $triggerId = absint($inputTrigger['id']);
                Action::where('id', $triggerId)->update($actionData);
                $syncIds[] = $triggerId;
            } else {
                // It's a new
                $newAction = Action::create($actionData);
                $syncIds[] = $newAction->id;
            }
        }

        Action::where('feed_id', $feedId)->whereNotIn('id', $syncIds)->delete();

        return true;

    }

    public static function syncTriggers($feedId, $inputTriggers)
    {
        $syncIds = [];

        foreach ($inputTriggers as $index => $inputTrigger) {

            $triggerData = Arr::only($inputTrigger, ['id', 'integration_id', 'priority', 'title', 'trigger_name', 'trigger_provider', 'trigger_scope', 'status', 'settings']);
            $triggerData = array_filter($triggerData);

            $triggerData['feed_id'] = $feedId;
            $triggerData['priority'] = $index + 1;

            // Check if we have $id
            if(!empty($inputTrigger['id'])) {
                $triggerId = absint($inputTrigger['id']);
                $triggerData['settings'] = maybe_serialize($triggerData['settings']);
                Trigger::where('id', $triggerId)->update($triggerData);
                $syncIds[] = $triggerId;
            } else {
                // It's a new
                $newTrigger = Trigger::create($triggerData);
                $syncIds[] = $newTrigger->id;
            }
        }

        Trigger::where('feed_id', $feedId)->whereNotIn('id', $syncIds)->delete();

        return true;

    }

    public static function isPublishable($feedId)
    {
        return  !! Trigger::where('feed_id', $feedId)->where('status', 'published')->first() && !! Action::where('feed_id', $feedId)->where('status', 'published')->first();
    }
}
