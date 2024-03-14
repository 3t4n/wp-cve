<?php

namespace WunderAuto;

use WunderAuto\Types\Triggers\BaseTrigger;
use WunderAuto\Types\Workflow;

/**
 * Class Scheduler
 */
class Scheduler
{
    /**
     * Public method to register Scheduled hooks
     *
     * @param Loader $loader Loader instance.
     *
     * @return void
     */
    public function register($loader)
    {
        $loader->addAction('wunder_execute_delayed_workflow', $this, 'executeDelayedWorkflow', 10, 2);
        $loader->addFilter(
            'action_scheduler_list_table_column_args',
            $this,
            'asListArgsColumnContent',
            10,
            2
        );
    }

    /**
     * Handle a fired trigger. Delayed workflows gets scheduled with the ActionScheduler
     * and direct workflows are just executed
     *
     * @param string                   $className
     * @param array<string, \stdClass> $objects
     * @param array<string, mixed>     $args
     *
     * @return void
     */
    public function doTrigger($className, $objects, $args)
    {
        $wunderAuto = wa_wa();

        do_action('wunderauto_trigger_fired', $className, reset($objects), $args);

        $workflows = $wunderAuto->getWorkflows();

        foreach ($workflows as $workflow) {
            // Set up a fresh wunderAuto resolver using the
            // arguments passed in. The resolver will
            // be used later by connected actions and parameters
            $resolver = $wunderAuto->createResolver($objects);

            // If not already added, make sure the curentuser object is in context
            $resolver->maybeAddCurrentUser();

            // Is the workflow active?
            if (!$workflow->isActive()) {
                continue;
            }

            // Does this workflow use this trigger?
            if ($workflow->getTriggerClass() !== $className) {
                continue;
            }

            // Does this trigger want to run this workflow given the runtime args
            // provided?
            /** @var BaseTrigger|null $trigger */
            $trigger = $wunderAuto->getObject('trigger', $className);
            if (!is_null($trigger)) {
                if (!$trigger->runThisWorkflow($workflow, $args)) {
                    continue;
                }
            }

            // Give 3rd party plugins a chance to add their objects
            do_action('wunderauto/trigger/runtimeContext', $className, $resolver);

            // Has this workflow already been executed for this object?
            if ($workflow->getOnlyOnce()) {
                if ($workflow->getHasAlreadyRun($resolver)) {
                    continue;
                }
                $workflow->setHasAlreadyRun($resolver);
            }

            // Run delayed?
            $schedule = $workflow->getSchedule();
            if ($schedule->when !== 'direct') {
                if ($workflow->executeFirstStep($resolver)) {
                    $this->scheduleWorkflow($workflow, $resolver);
                }
                continue;
            }

            $workflow->executeSteps($resolver);
        }
    }

    /**
     * Schedule a workflow to run later
     *
     * @param Workflow $workflow
     * @param Resolver $resolver
     *
     * @return void
     */
    private function scheduleWorkflow($workflow, $resolver)
    {
        $args = [
            $workflow->getPostId(),
            $resolver->getObjectIdArray(),
        ];

        // Add a new queue item with correct scheduling
        $time     = time();
        $schedule = $workflow->getSchedule();
        switch ($schedule->delayTimeUnit) {
            case 'minutes':
                $time += $schedule->delayFor * MINUTE_IN_SECONDS;
                break;
            case 'hours':
                $time += $schedule->delayFor * HOUR_IN_SECONDS;
                break;
            case 'days':
                $time += $schedule->delayFor * DAY_IN_SECONDS;
                break;
            case 'weeks':
                $time += $schedule->delayFor * WEEK_IN_SECONDS;
                break;
        }

        if (as_has_scheduled_action('wunder_execute_delayed_workflow', $args, 'WunderAutomation')) {
            as_unschedule_action('wunder_execute_delayed_workflow', $args, 'WunderAutomation');
        }
        as_schedule_single_action($time, 'wunder_execute_delayed_workflow', $args, 'WunderAutomation');
        do_action('wunderauto_workflow_scheduled', $workflow, $resolver, $time);
    }

    /**
     * Bootsrap and run a delayed workflow
     *
     * @param int                   $workflowId
     * @param array<int, \stdClass> $args
     *
     * @return void
     */
    public function executeDelayedWorkflow($workflowId, $args)
    {
        $wunderAuto = wa_wa();

        $resolver = $wunderAuto->createResolver([]);
        foreach ($args as $object) {
            $object = (object)$object;
            $name   = isset($object->name) ? $object->name : $object->type;
            $resolver->addObjectById($object->type, $name, $object->id);
        }

        // Bootstrap the Workflow
        $workflow = $wunderAuto->createWorkflowObject($workflowId);

        // Let 3rd party implementors add objects to the context
        $className = '\\' . join('\\', explode('\\', $workflow->getTriggerClass(), -1));
        do_action('wunderauto/trigger/runtimeContext', $className, $resolver);

        // Run the workflow steps
        $workflow->executeSteps($resolver);
    }

    /**
     * Format our scheduled tasks in the action scheduler UI
     *
     * @handles action_scheduler_list_table_column_args
     *
     * @param string               $content
     * @param array<string, mixed> $row
     *
     * @return mixed|string
     */
    public function asListArgsColumnContent($content, $row)
    {
        switch ($row['hook']) {
            case 'wunderauto_check_retrigger':
                $content = sprintf(
                    'ReTrigger:<br><a href="%s">%s (%s)</a>',
                    get_edit_post_link($row['args']['id']),
                    $row['args']['name'],
                    $row['args']['id']
                );
                break;
            case 'wunder_execute_delayed_workflow':
                $content = sprintf(
                    'Trigger:<br><a href="%s">%s (%s)</a><br><br>Objects:<ul>',
                    get_edit_post_link($row['args'][0]),
                    get_the_title($row['args'][0]),
                    $row['args'][0]
                );
                foreach ($row['args'][1] as $object) {
                    $type  = $object['type'];
                    $id    = $object['id'];
                    $link  = '#';
                    $title = $object['name'];
                    $name  = $object['name'];
                    switch ($type) {
                        case 'post':
                        case 'order':
                            $link  = get_edit_post_link($id);
                            $title = get_the_title($id);
                            break;
                        case 'user':
                            $link     = get_edit_user_link($id);
                            $userInfo = get_userdata($id);
                            $title    = $userInfo instanceof \WP_User ? $userInfo->user_login : '';
                    }

                    $content .= sprintf(
                        '<li><a href="%s">%s (%s - %d)</a></li>',
                        $link,
                        $title,
                        $name,
                        $id
                    );
                }
                break;
        }
        return $content;
    }
}
