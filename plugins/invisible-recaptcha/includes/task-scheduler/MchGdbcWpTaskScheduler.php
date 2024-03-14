<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

class MchGdbcWpTaskScheduler
{
	const SECONDS_IN_DAY     = 86400;
	const SECONDS_IN_WEEK    = 604800;
	const SECONDS_IN_MONTH   = 2635200;
	//const SECONDS_IN_YEAR    = 31536000;


	private $arrTasks = null;
	private $arrDefaultRecurrences = null;

	protected function __construct()
	{
		$this->arrTasks = array();

		$this->arrDefaultRecurrences = wp_get_schedules();

		$this->arrDefaultRecurrences['weekly'] = array(
			'interval' => self::SECONDS_IN_WEEK,
			'display' => __('Once Weekly')
		);

		$this->arrDefaultRecurrences['monthly'] = array(
			'interval' => self::SECONDS_IN_MONTH,
			'display' => __('Once a month')
		);


		add_filter('cron_schedules', array($this, 'generateCustomCronSchedules'), 10);

	}

	public function registerTask(MchGdbcWpTask $mchTask)
	{
		$this->arrTasks[] = $mchTask;
	}

	public function scheduleRegisteredTasks()
	{

		foreach($this->arrTasks as $mchTask)
		{
			add_action($mchTask->getTaskCronActionHookName(), array($mchTask, 'run'));

			if(false !== wp_next_scheduled($mchTask->getTaskCronActionHookName()))
				continue;

			$mchTask->isRecurringTask() ? wp_schedule_event( time(), $this->getFormattedRecurrence($mchTask->getRunningInterval()), $mchTask->getTaskCronActionHookName() )
										: wp_schedule_single_event($mchTask->getRunningInterval(), $mchTask->getTaskCronActionHookName() );
		}
	}


	public function unScheduleRegisteredTask(MchGdbcWpTask $mchTask)
	{
		foreach($this->arrTasks as $registeredTask)
		{
			if($mchTask->getTaskCronActionHookName() !== $registeredTask->getTaskCronActionHookName())
				continue;

			$timestamp = wp_next_scheduled($mchTask->getTaskCronActionHookName());
			(false !== $timestamp) ? wp_unschedule_event($timestamp, $mchTask->getTaskCronActionHookName()) : null;

			break;
		}
	}

	public function unScheduleRegisteredTasks()
	{
		foreach($this->arrTasks as $mchTask)
		{
			$timestamp = wp_next_scheduled($mchTask->getTaskCronActionHookName());
			(false !== $timestamp) ? wp_unschedule_event($timestamp, $mchTask->getTaskCronActionHookName()) : null;

		}

		$this->arrTasks = array();
	}


	public function generateCustomCronSchedules($arrCronSchedules)
	{

		$arrCronSchedules = array_merge($arrCronSchedules, $this->arrDefaultRecurrences);
		foreach($this->arrTasks as $mchTask)
		{
			if(!$mchTask->isRecurringTask() || isset($this->arrDefaultRecurrences[$this->getFormattedRecurrence($mchTask->getRunningInterval())]))
				continue;

			$arrCronSchedules[$this->getFormattedRecurrence($mchTask->getRunningInterval())] = array('interval' => $mchTask->getRunningInterval(),
																					     'display'  =>  __('Every ' . $mchTask->getRunningInterval() . ' seconds'));
		}

		return $arrCronSchedules;
	}


	private function getFormattedRecurrence($interval)
	{
		static $arrFormattedRecurrence = array();
		if(isset($arrFormattedRecurrence[$interval]))
			return $arrFormattedRecurrence[$interval];

		foreach($this->arrDefaultRecurrences as $recurrence => $arrRecurrence)
			if(isset($arrRecurrence['interval']) &&((int)$arrRecurrence['interval'] === (int)$interval))
				return $arrFormattedRecurrence[$interval] = $recurrence;

		return $arrFormattedRecurrence[$interval] = "mch-wp-$interval";
	}

	public static function getInstance()
	{
		static $taskSchedulerInstance = null;
		return null !== $taskSchedulerInstance ? $taskSchedulerInstance : $taskSchedulerInstance = new self();
	}
}

