<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace WPSgas\Mch\WpTasks;

interface MchWpITask
{
	public function run();
	public function getTaskCronActionHookName();
}

abstract class MchWpTask implements MchWpITask
{
	private $isRecurringTask = false;
	private $runningInterval = null;

	public function __construct($runningInterval, $isRecurring)
	{
		$this->runningInterval = (int)floor(abs($runningInterval));
		$this->isRecurringTask = (bool)$isRecurring;
	}

	public function isRecurringTask()
	{
		return $this->isRecurringTask;
	}

	public function getRunningInterval()
	{
		return $this->runningInterval;
	}

	public function getTaskCronActionHookName()
	{
		return get_class($this) . '-' . $this->runningInterval . '-' . var_export($this->isRecurringTask, true);
	}
}
