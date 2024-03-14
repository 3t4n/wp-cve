<?php

namespace WPPayForm\Framework\Foundation;

trait CronTaskSchedulerTrait
{
	/**
	 * Add a cron job on WordPress
	 * @param string $hook
	 * @param string $recurrence
	 * @param array $args
	 * @param boolean $wp_error
	 * @see https://developer.wordpress.org/reference/functions/wp_schedule_event
	 */
	public function addCronTask($hook, $recurrence, $args = [], $wp_error = false)
	{
		if (!wp_next_scheduled($hook)) {
			wp_schedule_event(time(), $recurrence, $hook, $args, $wp_error);
		}
	}

	/**
	 * Remove a cron task
	 * @param string $hook
	 * @return null
	 */
	public function removeCronTask($hook)
	{
		$timestamp = wp_next_scheduled($hook);
		wp_unschedule_event($timestamp, $hook);
	}
}
