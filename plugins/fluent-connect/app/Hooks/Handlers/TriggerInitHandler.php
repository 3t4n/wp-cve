<?php

namespace FluentConnect\App\Hooks\Handlers;

use FluentConnect\App\Models\Action;
use FluentConnect\App\Models\ActionLog;
use FluentConnect\App\Models\FeedRunner;
use FluentConnect\App\Models\Trigger;
use FluentConnect\App\Services\ConnectStores;
use FluentConnect\Framework\Support\Arr;

class TriggerInitHandler
{
    public function init()
    {
        add_action('fluent_connect_reindex_triggers', array($this, 'reIndex'));
        $this->mapTriggers();
    }

    public function reIndex()
    {
        $activeTriggers = Trigger::groupBy('trigger_name')
            ->select(['trigger_name'])
            ->where('status', 'published')
            ->get();

        $triggerNames = [];

        foreach ($activeTriggers as $activeTrigger) {
            $triggerNames[] = $activeTrigger->trigger_name;
        }

        update_option('_fluent_connect_triggers', $triggerNames);

    }

    private function mapTriggers()
    {
        $triggers = get_option('_fluent_connect_triggers', []);
        $triggers = array_unique($triggers);

        foreach ($triggers as $trigger) {
            $argNum = apply_filters('fluent_connect_trigger_' . $trigger . '_arg_num', 1);
            add_action($trigger, function () use ($trigger, $argNum) {
                $this->mapTrigger($trigger, func_get_args(), $argNum);
            }, 10, $argNum);
        }
    }

    protected function mapTrigger($triggerName, $args, $argNum)
    {
        $targetTriggers = Trigger::where('status', 'published')
            ->where('trigger_name', $triggerName)
            ->whereHas('feed', function ($q) {
                $q->where('status', 'published');
            })
            ->get();

        $syncedFeedIds = [];

        foreach ($targetTriggers as $targetTrigger) {

            if (in_array($targetTrigger->feed_id, $syncedFeedIds)) {
                continue;
            }

            $triggerClass = ConnectStores::getTriggerClass($targetTrigger->trigger_provider, $targetTrigger->trigger_name);

            if (!$triggerClass || !$formattedData = $triggerClass->getFormattedData($targetTrigger, $args)) {
                continue;
            }

            $syncedFeedIds[] = $targetTrigger->feed_id;

            $this->initTriggerActions($targetTrigger, $formattedData);
        }
    }

    protected function initTriggerActions($trigger, $formattedData)
    {
        $runner = FeedRunner::create([
            'feed_id'      => $trigger->feed_id,
            'trigger_id'   => $trigger->id,
            'trigger_data' => $formattedData,
            'status'       => 'processing',
            'runner_hash'  => Arr::get($formattedData, '__runner_hash', '')
        ]);

        $actions = Action::where('status', 'published')->where('feed_id', $trigger->feed_id)->get();

        foreach ($actions as $action) {
            $actionClass = ConnectStores::getActionClass($action->action_provider, $action->action_name);
            if (!$actionClass) {
                continue;
            }

            $runner->last_action_id = $action->id;
            $runner->save();

            $result = $actionClass->process($action, $formattedData);

            $logData = [
                'feed_id'   => $runner->feed_id,
                'action_id' => $action->id,
                'runner_id' => $runner->id,
                'status'    => 'completed'
            ];

            if (is_wp_error($result)) {
                $result = new \WP_Error('ddd', 'ddd');
                $errorCode = $result->get_error_code();
                $logData['status'] = $errorCode;
                $logData['description'] = $result->get_error_message();
            } else if ($result) {
                $logData['status'] = $result['status'];
                $logData['description'] = $result['message'];
                $logData['remote_action_id'] = Arr::get($result, 'remote_action_id');
                $logData['reference_url'] = Arr::get($result, 'reference_url');
            } else {
                $logData['status'] = 'n/a';
                $logData['description'] = 'Action status did not provide any info';
            }

            ActionLog::create($logData);
        }

        $runner->status = 'completed';
        $runner->trigger_data = [];
        $runner->save();

    }
}

