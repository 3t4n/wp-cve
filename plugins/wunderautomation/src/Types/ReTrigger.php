<?php

namespace WunderAuto\Types;

use WP_Post;
use WunderAuto\Resolver;
use WunderAuto\Types\Internal\ReTriggerSchedule;
use WunderAuto\Types\Internal\ReTriggerState;

/**
 * Class ReTrigger
 */
class ReTrigger extends BaseWorkflow
{
    /**
     * @var ReTriggerState
     */
    protected $state;

    /**
     * @var string
     */
    protected $settingsKey = 're-trigger_settings';

    /**
     * @var string
     */
    protected $settingsClass = __NAMESPACE__ . '\\Internal\\ReTriggerState';

    /**
     * @var string
     */
    protected $postType = 'automation-workflow';

    /**
     * @param int|null $postId
     */
    public function __construct($postId = null)
    {
        $this->state = new ReTriggerState();
        parent::__construct($postId);
    }

    /**
     * @param WP_Post $post
     *
     * @return void
     */
    public function parsePosted($post)
    {
        parent::parsePosted($post);

        if (isset($_POST['re-trigger'])) {
            $postedData = $this->getPostedJson('re-trigger');
            if (is_null($postedData)) {
                return;
            }

            $this->setState($postedData);
        }
    }

    /**
     * Evaluate the retrigger filters.
     *
     * @param Resolver $resolver
     *
     * @return bool
     */
    public function evaluateFilters($resolver)
    {
        $filterMissing = false;
        $ret           = false;

        if ($this->state->steps[0]->type !== 'filters') {
            return true;
        }

        if (count($this->state->steps[0]->filterGroups) === 0) {
            return true;
        }

        foreach ($this->state->steps[0]->filterGroups as $filterGroup) {
            $groupResult = true;
            foreach ($filterGroup->filters as $key => $filter) {
                $obj = wa_get_filter($filter->filter);

                if (is_null($obj)) {
                    $filterMissing = true;
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
            }

            // All it takes is one group to set total result to true
            // (group1 OR group2 OR group3 etc..)
            if ($groupResult) {
                $ret = true;
            }
        }

        if ($filterMissing) {
            $ret = false;
        }
        return $ret;
    }

    /**
     * Housekeeping when a Workflow is deleted
     *
     * @return void
     */
    public function deleted()
    {
        global $wpdb;

        $table = $wpdb->prefix . 'actionscheduler_actions';
        $args  = sprintf('{"id":%d,%%', $this->getPostId());

        $sql  = "delete from $table ";
        $sql .= "WHERE hook='wunderauto_check_retrigger' ";
        $sql .= "AND status='pending' ";
        $sql .= "AND args like '$args'";

        $wpdb->query($sql);
    }

    /**
     * Assign new ActionScheduler actions based on this retrigger
     *
     * @param ReTriggerState $newState
     *
     * @return array<string, int|string>
     */
    private function schedule($newState)
    {
        $newArgs = [
            'id'   => (int)$this->getPostId(),
            'name' => $this->getName(),
        ];

        $newSchedule = json_encode($this->state->schedule->toObject()) !== json_encode($newState->schedule->toObject());
        $newName     = json_encode($this->state->asArgs) !== json_encode($newArgs);
        $isScheduled = as_has_scheduled_action('wunderauto_check_retrigger', $this->state->asArgs);

        if (!$newSchedule && !$newName && $isScheduled) {
            return $this->state->asArgs;
        }

        as_unschedule_action('wunderauto_check_retrigger', $this->state->asArgs, 'WunderAutomation');

        if ($newState->schedule->frequency === 'manual') {
            $this->scheduleManual($newArgs);
        } else {
            $this->scheduleCron($newState->schedule, $newArgs);
        }

        return $newArgs;
    }

    /**
     * Set the schedule for a year into the future
     *
     * @param array<string, int|string> $args
     *
     * @return void
     */
    private function scheduleManual($args)
    {
        as_schedule_recurring_action(
            time() + 86400 * 365 * 100,
            86400 * 365 * 100,
            'wunderauto_check_retrigger',
            $args,
            'WunderAutomation'
        );
    }

    /**
     * Schedule an action with a fixed interval (daily or hourly)
     *
     * @param ReTriggerSchedule    $schedule
     * @param array<string, mixed> $args
     *
     * @return array<string, mixed>
     */
    private function scheduleFixed($schedule, $args)
    {
        $hour = (int)$schedule->frequencyHour * 3600;
        $min  = (int)$schedule->frequencyMinute * 60;

        if ($schedule->frequency === 'hour') {
            $timestamp = strtotime(date('H:00')) + $min;
            if ($timestamp < time()) {
                $timestamp += 3600;
            }
            $timestamps[] = [$timestamp, 3600];
        }

        if ($schedule->frequency === 'day') {
            $timestamp = strtotime(date('Y-m-d 00:00')) + $hour + $min;
            if ($timestamp < time()) {
                $timestamp += 3600 * 24;
            }
            $timestamps[] = [$timestamp, 3600 * 24];
        }

        if (!empty($timestamps)) {
            foreach ($timestamps as $timestamp) {
                as_schedule_recurring_action(
                    $timestamp[0],
                    $timestamp[1],
                    'wunderauto_check_retrigger',
                    $args,
                    'WunderAutomation'
                );
            }
            return $args;
        }

        return [];
    }

    /**
     * Schedule an action with a cron like interval (weekly or monthly)
     *
     * @param ReTriggerSchedule    $schedule
     * @param array<string, mixed> $args
     *
     * @return void
     */
    private function scheduleCron($schedule, $args)
    {
        $cron = [
            $schedule->frequencyMinute,  // 0 min
            $schedule->frequencyHour,    // 1 hour
            '*',                         // 2 day of month
            '*',                         // 3 month
            '*',                         // 4 day of week
        ];

        if ($schedule->frequency === 'hour') {
            $cron[1] = '*';
        }

        if ($schedule->frequency === 'week') {
            $days = [];
            foreach ($schedule->weekDays as $weekDay) {
                $days[] = $weekDay === 7 ? 0 : $weekDay;
            }

            if (empty($days)) {
                return;
            }
            $cron[4] = join(',', $days);
        }

        if ($schedule->frequency === 'month') {
            $days = [];
            foreach ($schedule->monthDays as $monthDay) {
                $days[] = $monthDay;
            }

            if (empty($days)) {
                return;
            }
            $cron[2] = join(',', $days);
        }

        // Figure out the next execution time
        $now           = new \DateTime();
        $strCron       = join(' ', $cron);
        $cronScheduler = new \ActionScheduler_CronSchedule($now, $strCron);
        $next          = $cronScheduler->get_next($now);

        as_schedule_cron_action(
            $next->getTimestamp(), // @phpstan-ignore-line
            $strCron,
            'wunderauto_check_retrigger',
            $args,
            'WunderAutomation'
        );

        return;
    }
}
