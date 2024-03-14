<?php

namespace Logeecom\Infrastructure\TaskExecution;

use Exception;
use Logeecom\Infrastructure\Configuration\Configuration;
use Logeecom\Infrastructure\Logger\Logger;
use Logeecom\Infrastructure\ServiceRegister;
use Logeecom\Infrastructure\TaskExecution\Interfaces\TaskRunnerStatusStorage;
use Logeecom\Infrastructure\TaskExecution\Interfaces\TaskRunnerWakeup;
use Logeecom\Infrastructure\Utility\TimeProvider;

/**
 * Class TaskRunner.
 *
 * @package Logeecom\Infrastructure\TaskExecution
 */
class TaskRunner
{
    /**
     * Fully qualified name of this class.
     */
    const CLASS_NAME = __CLASS__;
    /**
     * Automatic task runner wakeup delay in seconds
     */
    const WAKEUP_DELAY = 5;
    /**
     * Defines minimal time in seconds between two consecutive alive since updates.
     */
    const TASK_RUNNER_KEEP_ALIVE_PERIOD = 2;
    /**
     * Runner guid.
     *
     * @var string
     */
    protected $guid;
    /**
     * Service.
     *
     * @var QueueService
     */
    private $queueService;
    /**
     * Service.
     *
     * @var TaskRunnerStatusStorage
     */
    private $runnerStorage;
    /**
     * Service.
     *
     * @var Configuration
     */
    private $configurationService;
    /**
     * Service.
     *
     * @var TimeProvider
     */
    private $timeProvider;
    /**
     * Service.
     *
     * @var TaskRunnerWakeup
     */
    private $taskWakeup;
    /**
     * Defines when was the task runner alive since time step last updated at.
     *
     * @var int
     */
    private $aliveSinceUpdatedAt = 0;
    /**
     * Sleep time in seconds with microsecond precision.
     *
     * @var float
     */
    private $batchSleepTime = 0.0;
    /**
     * Sets task runner guid.
     *
     * @param string $guid Runner guid to set.
     */
    public function setGuid($guid)
    {
        $this->guid = $guid;
    }

    /**
     * Starts task runner lifecycle.
     */
    public function run()
    {
        try {
            $this->keepAlive();

            $this->logDebug(array('Message' => 'Task runner: lifecycle started.'));

            if ($this->isCurrentRunnerAlive()) {
                $this->failOrRequeueExpiredTasks();
                $this->startOldestQueuedItems();
            }

            $this->keepAlive();

            $this->wakeup();

            $this->logDebug(array('Message' => 'Task runner: lifecycle ended.'));
        } catch (Exception $ex) {
            $this->logDebug(
                array(
                    'Message' => 'Fail to run task runner. Unexpected error occurred.',
                    'ExceptionMessage' => $ex->getMessage(),
                    'ExceptionTrace' => $ex->getTraceAsString(),
                )
            );
        }
    }

    /**
     * Fails or re-queues expired tasks.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\QueueItemDeserializationException
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\QueueStorageUnavailableException
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException
     */
    private function failOrRequeueExpiredTasks()
    {
        $this->logDebug(array('Message' => 'Task runner: expired tasks cleanup started.'));

        $runningItems = $this->getQueue()->findRunningItems();
        if (!$this->isCurrentRunnerAlive()) {
            return;
        }

        $this->keepAlive();

        foreach ($runningItems as $runningItem) {
            if ($this->isItemExpired($runningItem) && $this->isCurrentRunnerAlive()) {
                $this->logMessageFor($runningItem, 'Task runner: Expired task detected.');
                $this->getConfigurationService()->setContext($runningItem->getContext());
                if ($runningItem->getProgressBasePoints() > $runningItem->getLastExecutionProgressBasePoints()) {
                    $this->logMessageFor($runningItem, 'Task runner: Task requeue for execution continuation.');
                    $this->getQueue()->requeue($runningItem);
                } else {
                    $runningItem->reconfigureTask();
                    $this->getQueue()->fail(
                        $runningItem,
                        sprintf(
                            'Task %s failed due to extended inactivity period.',
                            $this->getItemDescription($runningItem)
                        )
                    );
                }
            }

            $this->keepAlive();
        }
    }

    /**
     * Starts oldest queue item from all queues respecting following list of criteria:
     *      - Queue must be without already running queue items
     *      - For one queue only one (oldest queued) item should be started
     *      - Number of running tasks must NOT be greater than maximal allowed by integration configuration.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\ProcessStarterSaveException
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\QueueItemDeserializationException
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException
     */
    private function startOldestQueuedItems()
    {
        $this->keepAlive();

        $this->logDebug(array('Message' => 'Task runner: available task detection started.'));

        // Calculate how many queue items can be started
        $maxRunningTasks = $this->getConfigurationService()->getMaxStartedTasksLimit();
        $alreadyRunningItems = $this->getQueue()->findRunningItems();
        $numberOfAvailableSlots = $maxRunningTasks - count($alreadyRunningItems);
        if ($numberOfAvailableSlots <= 0) {
            $this->logDebug(array('Message' => 'Task runner: max number of active tasks reached.'));

            return;
        }

        $this->keepAlive();

        $items = $this->getQueue()->findOldestQueuedItems($numberOfAvailableSlots);

        $this->keepAlive();

        if (!$this->isCurrentRunnerAlive()) {
            return;
        }

        $asyncStarterBatchSize = $this->getConfigurationService()->getAsyncStarterBatchSize();
        $batchStarter = new AsyncBatchStarter($asyncStarterBatchSize);
        foreach ($items as $item) {
            $this->logMessageFor($item, 'Task runner: Adding task to a batch starter for async execution.');
            $batchStarter->addRunner(new QueueItemStarter($item->getId()));
        }

        $this->keepAlive();

        if (!$this->isCurrentRunnerAlive()) {
            return;
        }

        $this->logDebug(array('Message' => 'Task runner: Starting batch starter execution.'));
        $startTime = $this->getTimeProvider()->getMicroTimestamp();
        $batchStarter->run();
        $endTime = $this->getTimeProvider()->getMicroTimestamp();

        $this->keepAlive();

        $averageRequestTime = ($endTime - $startTime) / $asyncStarterBatchSize;
        $this->batchSleepTime = $batchStarter->getWaitTime($averageRequestTime);

        $this->logDebug(
            array(
                'Message' => 'Task runner: Batch starter execution finished.',
                'ExecutionTime' => ($endTime - $startTime) . 's',
                'AverageRequestTime' => $averageRequestTime . 's',
                'StartedItems' => count($items),
            )
        );
    }

    /**
     * Executes wakeup on runner.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusChangeException
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException
     */
    private function wakeup()
    {
        $this->logDebug(array('Message' => 'Task runner: starting self deactivation.'));

        for ($i = 0; $i < $this->getWakeupDelay(); $i++) {
            $this->getTimeProvider()->sleep(1);
            $this->keepAlive();
        }

        $this->getRunnerStorage()->setStatus(TaskRunnerStatus::createNullStatus());

        $this->logDebug(array('Message' => 'Task runner: sending task runner wakeup signal.'));
        $this->getTaskWakeup()->wakeup();
    }

    /**
     * Updates alive since time stamp of the task runner.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException
     */
    private function keepAlive()
    {
        $currentTime = $this->getTimeProvider()->getCurrentLocalTime()->getTimestamp();
        if (($currentTime - $this->aliveSinceUpdatedAt) < self::TASK_RUNNER_KEEP_ALIVE_PERIOD) {

            return;
        }

        $this->getConfigurationService()->setTaskRunnerStatus($this->guid, $currentTime);
        $this->aliveSinceUpdatedAt = $currentTime;
    }

    /**
     * Checks whether current runner is alive.
     *
     * @return bool TRUE if runner is alive; otherwise, FALSE.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\TaskRunnerStatusStorageUnavailableException
     */
    private function isCurrentRunnerAlive()
    {
        $runnerStatus = $this->getRunnerStorage()->getStatus();
        $runnerExpired = $runnerStatus->isExpired();
        $runnerGuidIsCorrect = $this->guid === $runnerStatus->getGuid();

        if ($runnerExpired) {
            $this->logWarning(array('Message' => 'Task runner: Task runner started but it is expired.'));
        }

        if (!$runnerGuidIsCorrect) {
            $this->logWarning(array('Message' => 'Task runner: Task runner started but it is not active anymore.'));
        }

        return !$runnerExpired && $runnerGuidIsCorrect;
    }

    /**
     * Checks whether queue item is expired.
     *
     * @param QueueItem $item Queue item for checking.
     *
     * @return bool TRUE if queue item expired; otherwise, FALSE.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\QueueItemDeserializationException
     */
    private function isItemExpired(QueueItem $item)
    {
        $currentTimestamp = $this->getTimeProvider()->getCurrentLocalTime()->getTimestamp();
        $maxTaskInactivityPeriod = $item->getTask()->getMaxInactivityPeriod();

        return ($item->getLastUpdateTimestamp() + $maxTaskInactivityPeriod) < $currentTimestamp;
    }

    /**
     * Gets queue item description.
     *
     * @param QueueItem $item Queue item.
     *
     * @return string Description of queue item.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\QueueItemDeserializationException
     */
    private function getItemDescription(QueueItem $item)
    {
        return "{$item->getId()}({$item->getTaskType()})";
    }

    /**
     * Gets @return QueueService Queue service instance.
     * @see QueueService service instance.
     *
     */
    private function getQueue()
    {
        if ($this->queueService === null) {
            $this->queueService = ServiceRegister::getService(QueueService::CLASS_NAME);
        }

        return $this->queueService;
    }

    /**
     * Gets @return TaskRunnerStatusStorage Service instance.
     * @see TaskRunnerStatusStorageInterface service instance.
     *
     */
    private function getRunnerStorage()
    {
        if ($this->runnerStorage === null) {
            $this->runnerStorage = ServiceRegister::getService(TaskRunnerStatusStorage::CLASS_NAME);
        }

        return $this->runnerStorage;
    }

    /**
     * Gets @return Configuration Service instance.
     * @see Configuration service instance.
     *
     */
    private function getConfigurationService()
    {
        if ($this->configurationService === null) {
            $this->configurationService = ServiceRegister::getService(Configuration::CLASS_NAME);
        }

        return $this->configurationService;
    }

    /**
     * Gets @return TimeProvider Service instance.
     * @see TimeProvider instance.
     *
     */
    private function getTimeProvider()
    {
        if ($this->timeProvider === null) {
            $this->timeProvider = ServiceRegister::getService(TimeProvider::CLASS_NAME);
        }

        return $this->timeProvider;
    }

    /**
     * Gets @return TaskRunnerWakeup Service instance.
     * @see TaskRunnerWakeupInterface service instance.
     *
     */
    private function getTaskWakeup()
    {
        if ($this->taskWakeup === null) {
            $this->taskWakeup = ServiceRegister::getService(TaskRunnerWakeup::CLASS_NAME);
        }

        return $this->taskWakeup;
    }

    /**
     * Returns wakeup delay in seconds
     *
     * @return int Wakeup delay in seconds.
     */
    private function getWakeupDelay()
    {
        $configurationValue = $this->getConfigurationService()->getTaskRunnerWakeupDelay();

        $minimalSleepTime  = $configurationValue !== null ? $configurationValue : self::WAKEUP_DELAY;

        return $minimalSleepTime + ceil($this->batchSleepTime);
    }

    /**
     * Logs message and queue item details.
     *
     * @param QueueItem $queueItem Queue item.
     * @param string $message Message to be logged.
     *
     * @throws \Logeecom\Infrastructure\TaskExecution\Exceptions\QueueItemDeserializationException
     */
    private function logMessageFor(QueueItem $queueItem, $message)
    {
        $this->logDebug(
            array(
                'RunnerGuid' => $this->guid,
                'Message' => $message,
                'TaskId' => $queueItem->getId(),
                'TaskType' => $queueItem->getTaskType(),
                'TaskRetries' => $queueItem->getRetries(),
                'TaskProgressBasePoints' => $queueItem->getProgressBasePoints(),
                'TaskLastExecutionProgressBasePoints' => $queueItem->getLastExecutionProgressBasePoints(),
            )
        );
    }

    /**
     * Helper methods to encapsulate debug level logging.
     *
     * @param array $debugContent Array of debug content for logging.
     */
    private function logDebug(array $debugContent)
    {
        $debugContent['RunnerGuid'] = $this->guid;
        Logger::logDebug($debugContent['Message'], 'Core', $debugContent);
    }

    /**
     * Helper methods to encapsulate warning level logging.
     *
     * @param array $debugContent Array of debug content for logging.
     */
    private function logWarning(array $debugContent)
    {
        $debugContent['RunnerGuid'] = $this->guid;
        Logger::logWarning($debugContent['Message'], 'Core', $debugContent);
    }
}
