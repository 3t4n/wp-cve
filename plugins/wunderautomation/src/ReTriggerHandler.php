<?php

namespace WunderAuto;

use WunderAuto\Types\Internal\Query;
use WunderAuto\Types\Internal\ReTriggerState;
use WunderAuto\Types\ReTrigger;
use WunderAuto\Types\Triggers\BaseReTrigger;

/**
 * Class ReTriggerHandler
 */
class ReTriggerHandler
{
    /**
     * Public method to register hooks
     *
     * @param Loader $loader Loader instance.
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('wunderauto_check_retrigger', $this, 'checkReTrigger');
        $loader->addAction('wunder_execute_retriggered_object', $this, 'executeReTriggeredObject', 2, 10);
        $loader->addFilter('wunderauto/getobjects/trigger', $this, 'addReTriggersUI');
    }

    /**
     * @handles wunderauto/getobjects/trigger
     *
     * @param array<string, mixed> $ret
     *
     * @return array<string, mixed>
     */
    public function addReTriggersUI($ret)
    {
        $reTriggerPosts = $this->getAllReTriggers();
        foreach ($reTriggerPosts as $reTriggerPost) {
            $reTrigger = new ReTrigger($reTriggerPost->ID);
            $state     = $reTrigger->getState();
            if (empty($state->query) || empty($state->query->objectType)) {
                continue;
            }

            $trigger = $this->getTrigger($state->query->objectType);
            if (empty($trigger)) {
                continue;
            }

            $trigger->title       = $state->name;
            $trigger->description = 'ReTriggers';
            $trigger->group       = 'ReTriggers';

            $ret[$this->getClassName($reTriggerPost->ID)] = $trigger;
        }

        return $ret;
    }

    /**
     * Get all active re triggers
     *
     * @return array<int, \WP_Post>
     */
    private function getAllReTriggers()
    {
        $args = [
            'post_type'  => 'automation-retrigger',
            'meta_key'   => 'active',
            'meta_value' => 'active',
        ];

        $ret = array_filter(get_posts($args), function ($el) {
            return $el instanceof \WP_Post;
        });
        return $ret;
    }

    /**
     * @param string $objectType
     *
     * @return BaseReTrigger|null
     */
    private function getTrigger($objectType)
    {
        $trigger = null;
        switch ($objectType) {
            case 'post':
                $trigger = new Types\Triggers\Post\ReTriggered();
                break;
            case 'user':
                $trigger = new Types\Triggers\User\ReTriggered();
                break;
            case 'comment':
                $trigger = new Types\Triggers\Comment\ReTriggered();
                break;
            case 'order':
                $trigger = new Types\Triggers\Order\ReTriggered();
                break;
        }

        return $trigger;
    }

    /**
     * @param int $id
     *
     * @return string
     */
    private function getClassName($id)
    {
        return '\\WunderAuto\\ReTriggerHandler\\' . $id;
    }

    /**
     * @param int $reTriggerId
     *
     * @return void
     */
    public function checkReTrigger($reTriggerId)
    {
        $wunderAuto = wa_wa();
        $reTrigger  = new ReTrigger($reTriggerId);
        $settings   = $reTrigger->getState();
        if (!($settings instanceof ReTriggerState)) {
            return;
        }

        // Check if there are any workflows actively using this re-trigger
        $class         = $this->getClassName($reTriggerId);
        $workflowPosts = $wunderAuto->getWorkflowPosts(true, $class);
        if (count($workflowPosts) === 0) {
            return;
        }

        $query   = $settings->query;
        $objects = [];

        switch ($query->objectType) {
            case 'post':
                $args    = [
                    'post_type'   => $query->objectType,
                    'post_status' => $query->postStatus,
                    'date_query'  => $this->getDateQuery($query)
                ];
                $objects = get_posts($args);
                break;
            case 'order':
                $args    = [
                    'post_type'   => 'shop_order',
                    'post_status' => $query->postStatus,
                    'date_query'  => $this->getDateQuery($query)
                ];
                $objects = get_posts($args);
                break;
            case 'user':
                $args    = [
                    'date_query' => $this->getDateQuery($query)
                ];
                $objects = get_users($args);
                break;
            case 'comment':
                $args    = [
                    'date_query' => $this->getDateQuery($query)
                ];
                $objects = get_comments($args);
                break;
        }

        if (!is_array($objects) || count($objects) === 0) {
            return;
        }

        // Get the correct ReTrigger object or bail
        $triggerObject = $this->getTrigger($query->objectType);
        if (is_null($triggerObject)) {
            return;
        }

        // Foreach object we got, we want to evaluate the re-trigger filters.
        foreach ($objects as $object) {
            $allWorkflowObjects = $triggerObject->getObjects($object);
            if ($allWorkflowObjects === false) {
                continue;
            }
            $resolver     = $wunderAuto->createResolver($allWorkflowObjects);
            $filterResult = $reTrigger->evaluateFilters($resolver);

            if ($filterResult) {
                $workflowArgs = $resolver->getObjectIdArray();

                foreach ($workflowPosts as $workflowPost) {
                    $args = [$workflowPost->ID, $workflowArgs];
                    as_enqueue_async_action('wunder_execute_retriggered_object', $args, 'WunderAutomation');
                }
            }
        }
    }

    /**
     * @param Query $triggerQuery
     *
     * @return array<int, array<string, string|int|bool>>
     */
    private function getDateQuery($triggerQuery)
    {
        $time = time();
        switch ($triggerQuery->createdTimeUnit) {
            case 'minutes':
                $time -= $triggerQuery->created * MINUTE_IN_SECONDS;
                break;
            case 'hours':
                $time -= $triggerQuery->created * HOUR_IN_SECONDS;
                break;
            case 'days':
                $time -= $triggerQuery->created * DAY_IN_SECONDS;
                break;
            case 'weeks':
                $time -= $triggerQuery->created * WEEK_IN_SECONDS;
                break;
        }

        return [
            [
                'after'     => date('Y-m-d H:i:s', $time),
                'inclusive' => true,
            ],
        ];
    }

    /**
     * @handles wunder_execute_retriggered_object
     *
     * @param int                   $workflowId
     * @param array<int, \stdClass> $args
     *
     * @return void
     */
    public function executeReTriggeredObject($workflowId, $args)
    {
        $wunderAuto = wa_wa();
        $scheduler  = $wunderAuto->getScheduler();
        $scheduler->executeDelayedWorkflow($workflowId, $args);
    }
}
