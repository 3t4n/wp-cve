<?php

namespace FluentConnect\App\Http\Controllers;

use FluentConnect\App\Models\Action;
use FluentConnect\App\Models\Feed;
use FluentConnect\App\Models\Integration;
use FluentConnect\App\Models\Trigger;
use FluentConnect\App\Services\ConnectStores;
use FluentConnect\App\Services\FeedService;
use FluentConnect\Framework\Request\Request;
use FluentConnect\Framework\Support\Arr;

class FeedsController extends Controller
{
    public function get(Request $request)
    {
        return [
            'feeds' => Feed::orderBy('id', 'DESC')->paginate()
        ];
    }

    public function create(Request $request)
    {
        $this->validate($request->all(), [
            'title' => 'required|unique:fcon_connector_feeds'
        ]);

        $title = sanitize_text_field($request->get('title'));

        $feed = Feed::create([
            'title' => $title,
            'status' => 'draft',
            'settings' => []
        ]);

        return [
            'feed' => $feed,
            'message' => __('connect Feed has been successfully created', 'fluent-connect')
        ];
    }

    public function updateFeed(Request $request, $id)
    {
        $feed = Feed::findOrFail($id);
        $feedData = json_decode($request->get('feed'), true);

        $feed->title = sanitize_text_field($feedData['title']);

        FeedService::syncActions($feed->id, Arr::get($feedData, 'actions', []));
        FeedService::syncTriggers($feed->id, Arr::get($feedData, 'triggers', []));

        if($feedData['status'] != $feed->status) {
            $newStatus = $feedData['status'];
            if($newStatus == 'published' && !FeedService::isPublishable($feed->id)) {
                $newStatus = 'draft';
            }

            $feed->status = $newStatus;
        } else if(!FeedService::isPublishable($feed->id)) {
            $feed->status = 'draft';
        }

        $feed->save();

        if($feed->status == 'published') {
            do_action('fluent_connect_reindex_triggers');
        }

        $data = $this->getFeed($request, $id);

        $data['message'] = 'Feed has been updated';

        return $data;
    }

    public function publishFeed(Request $request, $id)
    {
        $feed = Feed::findOrFail($id);
        $isPublished = false;
        if(FeedService::isPublishable($id)) {
            $feed->status = 'published';
            $isPublished = true;
        } else {
            $feed->status = 'draft';
        }

        $message = 'Feed has been successfully published';

        if(!$isPublished) {
            $message = 'Feed can not be published. Please check if you have published trigger and action';
        }

        return [
            'message' => $message,
            'feed' => $feed
        ];

    }

    public function deleteFeed(Request $request, $id)
    {
        Feed::where('id', $id)->delete();
        Action::where('feed_id', $id)->delete();
        Trigger::where('feed_id', $id)->delete();

        do_action('fluent_connect_reindex_triggers');

        return [
            'message' => 'Selected feed has been deleted'
        ];
    }

    public function getFeed(Request $request, $id)
    {
        $feed = Feed::with(['triggers', 'actions'])->findOrFail($id);

        $data = [
            'feed' => $feed,
            'all_triggers' => ConnectStores::getTriggerProviders(true),
            'all_actions' => ConnectStores::getActionProviders(true)
        ];

        if(in_array('integrations', $request->get('with', []))) {
            $data['integrations'] = Integration::select(['id', 'title', 'provider'])->where('status', 'published')->get();
        }

        return $data;
    }

    public function getTriggerFields(Request $request)
    {
        $triggerProvider = $request->get('trigger_provider');
        $triggerName = $request->get('trigger_name');

        $triggerClass = ConnectStores::getTriggerClass($triggerProvider, $triggerName);

        if(!$triggerClass) {
            return $this->sendError([
                'message' => 'No Trigger Class found'
            ]);
        }

        $trigger = (object) $request->all();

        return [
            'settings_fields' => $triggerClass->getSettingsFields($trigger)
        ];
    }

    public function getActionFields(Request $request)
    {
        $actionProvider = $request->get('action_provider');
        $actionName = $request->get('action_name');

        $actionClass = ConnectStores::getActionClass($actionProvider, $actionName);

        if(!$actionClass) {
            return $this->sendError([
                'message' => 'No Action Class found'
            ]);
        }

        $action = (object) $request->all();

        return [
            'settings_fields' => $actionClass->getSettingsFields($action)
        ];
    }

}


