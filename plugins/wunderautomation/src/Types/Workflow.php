<?php

namespace WunderAuto\Types;

use WP_Post;
use WunderAuto\Resolver;
use WunderAuto\Types\Actions\BaseAction;
use WunderAuto\Types\Filters\BaseFilter;
use WunderAuto\Types\Internal\Action;
use WunderAuto\Types\Internal\FilterGroup;
use WunderAuto\Types\Internal\Schedule;
use WunderAuto\Types\Internal\WorkflowState;
use WunderAuto\Types\Triggers\BaseTrigger;

/**
 * Class Workflow
 */
class Workflow extends BaseWorkflow
{
    /**
     * @var WorkflowState
     */
    protected $state;

    /**
     * @var string
     */
    protected $settingsKey = 'workflow_settings';

    /**
     * @var string
     */
    protected $settingsClass = __NAMESPACE__ . '\\Internal\\WorkflowState';

    /**
     * @var string
     */
    protected $postType = 'automation-workflow';

    /**
     * @return Schedule
     */
    public function getSchedule()
    {
        return $this->state->schedule;
    }

    /**
     * Return the trigger class name for this workflow
     *
     * @return string
     */
    public function getTriggerClass()
    {
        return $this->state->trigger->trigger;
    }

    /**
     * Get value true/false of the onlyOnce flag on this workflow.
     * Return false if it's not set
     *
     * @return bool
     */
    public function getOnlyOnce()
    {
        $trigger = $this->state->trigger;
        if (isset($trigger->value->onlyOnce) && (bool)$trigger->value->onlyOnce === true) {
            return true;
        }

        return false;
    }

    /**
     * Has this workflow already executed (passed filters) for the
     * object that triggered the workflow?
     *
     * @param Resolver $resolver
     *
     * @return bool
     */
    public function getHasAlreadyRun($resolver)
    {
        $objectName = $resolver->getFirstObjectType();
        if (is_null($objectName)) {
            return false;
        }

        $key       = '_wunderauto_workflow_execution_' . $this->postId;
        $timeStamp = $resolver->getMetaValue($objectName, $key);
        return (int)$timeStamp > 0;
    }

    /**
     * @param Resolver $resolver
     *
     * @return void
     */
    public function setHasAlreadyRun($resolver)
    {
        $objectName = $resolver->getFirstObjectType();
        if (is_null($objectName)) {
            return;
        }

        $key = '_wunderauto_workflow_execution_' . $this->postId;
        $resolver->setMetaValue($objectName, $key, time());
    }

    /**
     * Save workflow to posts and postmeta
     *
     * @return bool
     */
    public function save()
    {
        $result = parent::save();
        if ($result === false) {
            return $result;
        }

        update_post_meta($this->postId, 'workflow_trigger', $this->state->trigger->postMetaTrigger());
        update_post_meta($this->postId, 'sortorder', $this->state->options->sortOrder);

        // Give the trigger a chance to store meta data
        $triggerClass = $this->state->trigger->trigger;
        if (class_exists($triggerClass)) {
            /** @var BaseTrigger $triggerObj */
            $triggerObj = new $triggerClass();
            $triggerObj->saveWunderAutomationWorkflow($this->postId, $this->state);
        }

        return true;
    }

    /**
     * @param WP_Post $post
     *
     * @return void
     */
    public function parsePosted($post)
    {
        parent::parsePosted($post);

        if (isset($_POST['workflow'])) {
            $postedData = $this->getPostedJson('workflow');
            if (is_null($postedData)) {
                return;
            }

            $this->setState($postedData);
        }
    }

    /**
     * If the first step is a filter and the evalBeforeScheduling property
     * is true
     *
     * @param Resolver $resolver
     *
     * @return bool
     */
    public function executeFirstStep($resolver)
    {
        // Abort if there are no steps
        if (empty($this->state->steps)) {
            return true;
        }

        // Abort if the first step isn't a filter
        $firstStep = reset($this->state->steps);
        if ($firstStep->type !== 'filters') {
            return true;
        }

        // If the property isn't there or is false, return true
        if (!isset($firstStep->evalBeforeScheduling) || !$firstStep->evalBeforeScheduling) {
            return true;
        }

        // Otherwise, let the filter decide
        return $this->evaluateFilters($firstStep->filterGroups, $resolver);
    }

    /**
     * Evaluate each filter in each group and return true if all
     * conditions are met, false otherwise
     *
     * @param array<int, FilterGroup> $filters
     * @param Resolver                $resolver
     *
     * @return bool
     */
    private function evaluateFilters($filters, $resolver)
    {
        $filterMissing    = false;
        $filterGroupCount = 0;
        $ret              = false;

        foreach ($filters as $filterGroup) {
            if ($filterMissing) {
                break;
            }
            $groupResult = true;
            foreach ($filterGroup->filters as $key => $filter) {
                $obj = wa_get_filter($filter->filter);

                if (!($obj instanceof BaseFilter)) {
                    $filterMissing = true;
                    do_action('wunderauto_filter_missing', $this, $filter);
                    break;
                }

                // All it takes is one filter to set this group to false
                // (filter1 AND filter2 AND filter3 etc..)
                $obj->setResolver($resolver);
                $obj->setFilterConfig($filter);
                $filterResult = $obj->evaluate();
                if (!$filterResult) {
                    $groupResult = false;
                }
                do_action('wunderauto_evaluated_filter', $this, $filter, $filterResult);
            }
            $filterGroupCount++;
            do_action('wunderauto_evaluated_filter_group', $this, $filterGroupCount, $groupResult);

            // All it takes is one group to set total result to true
            // (group1 OR group2 OR group3 etc..)
            if ($groupResult) {
                $ret = true;
            }
        }

        if ($filterMissing) {
            $ret = false;
        }

        do_action('wunderauto_filter_evaluation_done', $this, $ret);
        return $ret;
    }

    /**
     * Execute all steps in this workflow
     *
     * @param Resolver $resolver
     *
     * @return void
     */
    public function executeSteps($resolver)
    {
        $actionCount = 0;
        $filterCount = 0;

        do_action('wunderauto_workflow_started', $this, $resolver);

        foreach ($this->state->steps as $step) {
            switch ($step->type) {
                case 'filters':
                    $filterCount++;
                    $result = $this->evaluateFilters($step->filterGroups, $resolver);
                    if ($result !== true) {
                        // log?
                        return;
                    }
                    break;
                case 'action':
                    $actionCount++;
                    $result = $this->executeAction($step->action, $resolver);
                    break;
            }
        }

        do_action('wunderauto_all_actions_done', $this, $actionCount);
    }

    /**
     * Perform an actions
     *
     * @param Action   $action
     * @param Resolver $resolver
     *
     * @return bool
     */
    private function executeAction($action, $resolver)
    {
        // Legacy
        do_action('wunderauto_doing_action', $this, $action, 0);

        $obj = wa_get_action($action->action);
        if (!($obj instanceof BaseAction)) {
            return false;
        }

        $obj->setResolver($resolver);
        $obj->setActionConfig($action);
        $ret = $obj->doAction();
        do_action('wunderauto_action_done', $this, $action, $ret, 0);

        return $ret;
    }

    /**
     * Housekeeping when a Workflow was deleted
     *
     * @return void
     */
    public function deleted()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'actionscheduler_actions';
        $args  = sprintf('[%d,[%%', $this->getPostId());

        $sql  = "delete from $table ";
        $sql .= "WHERE hook='wunder_execute_delayed_workflow' ";
        $sql .= "AND status='pending' ";
        $sql .= "AND args like '$args'";

        $wpdb->query($sql);
    }

    /**
     * Return internal ID
     *
     * @return int|null
     */
    public function getPostId()
    {
        return $this->postId;
    }

    /**
     * @return object
     */
    protected function defaultState()
    {
        return (object)[
            'version' => 0,
            'trigger' => null,
            'steps'   => [],
            'filters' => null,  // Legacy format
            'actions' => null,  // Legacy format
        ];
    }
}
