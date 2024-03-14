<?php

namespace Modular\Connector\Queue;

use Modular\Connector\Services\Helpers\Utils;
use Modular\ConnectorDependencies\Illuminate\Contracts\Debug\ExceptionHandler;
use Modular\ConnectorDependencies\Illuminate\Contracts\Events\Dispatcher as EventsDispatcher;
use Modular\ConnectorDependencies\Illuminate\Queue\Events\WorkerStopping;
use Modular\ConnectorDependencies\Illuminate\Support\Collection;
use function Modular\ConnectorDependencies\app;

class Worker
{
    /**
     * The status set when process is cancelling.
     *
     * @var int
     */
    const STATUS_CANCELLED = 1;

    /**
     * The status set when process is paused or pausing.
     *
     * @var int;
     */
    const STATUS_PAUSED = 2;

    const EXIT_SUCCESS = 0;
    const EXIT_ERROR = 1;
    const EXIT_MEMORY_LIMIT = 12;

    /**
     * @var \Modular\ConnectorDependencies\Ares\Framework\Foundation\Application
     */
    protected EventsDispatcher $events;

    /**
     * @var \Modular\ConnectorDependencies\Ares\Framework\Foundation\Application
     */
    protected ExceptionHandler $exceptions;

    /**
     * @var string
     */
    protected string $queue;

    /**
     * @var string
     */
    protected string $identifier;

    /**
     * @var string
     */
    protected string $identifierCron;

    /**
     * @var string
     */
    protected string $identifierCronInterval;

    /**
     * Cron interval in seconds.
     *
     * @var int
     */
    protected int $interval = 60; // 1 minute

    /**
     * Cron timeout in seconds.
     *
     * @var int
     */
    protected int $timeout = 30; // 30 sec

    /**
     * Time limit for each queue batch in seconds.
     *
     * @var int
     */
    protected int $lockTime = 60; // 30 sec

    /**
     * Max. jobs to process per requests
     *
     * @var int|bool
     */
    protected $maxJobs = false; // false is disabled

    /**
     * Indicates if the worker should exit.
     *
     * @var bool
     */
    public bool $shouldQuit = false;

    /**
     * Indicates if the worker should stop when the queue is empty.
     *
     * @var bool
     */
    public $stopWhenEmpty = true;

    /**
     * Indicates if the worker is paused.
     *
     * @var bool
     */
    public bool $paused = false;

    /**
     * @param string|null $queue
     * @param array $options
     */
    public function __construct(?string $queue = null, array $options = [])
    {
        $this->queue = $queue ?: 'default';
        $this->identifier = Dispatcher::getIdentifier($this->queue);
        $this->identifierCron = Dispatcher::getIdentifier($this->queue, '_cron');
        $this->identifierCronInterval = Dispatcher::getIdentifier($this->queue, '_cron_interval');

        $this->interval = $options['interval'] ?? $this->interval;
        $this->timeout = $options['timeout'] ?? $this->timeout;
        $this->maxJobs = $options['max_jobs'] ?? $this->maxJobs;
        $this->stopWhenEmpty = $options['stop_when_empty'] ?? $this->stopWhenEmpty;

        $this->events = app()->make('events');
        $this->exceptions = app()->make(ExceptionHandler::class);

        add_action('wp_ajax_' . $this->identifier, [$this, 'handle']);
        add_action('wp_ajax_nopriv_' . $this->identifier, [$this, 'handle']);

        add_action($this->identifierCron, [$this, 'healthcheck']);
        add_filter('cron_schedules', [$this, 'scheduleHealthcheck']);
    }

    /**
     * Get the status key.
     *
     * @return string
     */
    protected function getStatusKey()
    {
        return $this->identifier . '_status';
    }

    /**
     * Get Jobs in Queue.
     *
     * @param int $limit Number of batches to return, defaults to all.
     * @return Collection
     */
    public function getJobs($limit = 0)
    {
        global $wpdb;

        try {
            if (empty($limit) || !is_int($limit)) {
                $limit = 0;
            }

            $table = $wpdb->options;
            $column = 'option_name';
            $keyColumn = 'option_id';
            $valueColumn = 'option_value';

            if (\is_multisite()) {
                $table = $wpdb->sitemeta;
                $column = 'meta_key';
                $keyColumn = 'meta_id';
                $valueColumn = 'meta_value';
            }

            $key = $wpdb->esc_like($this->identifier) . '%';

            $sql = '
			SELECT *
			FROM ' . $table . '
			WHERE ' . $column . ' LIKE %s
			ORDER BY ' . $keyColumn . ' ASC
			';

            $args = [$key];

            if (!empty($limit)) {
                $sql .= ' LIMIT %d';
                $args[] = $limit;
            }

            $items = $wpdb->get_results($wpdb->prepare($sql, $args));
            $items = Collection::make($items);

            return $items->map(function ($item) use ($column, $valueColumn) {
                $job = json_decode($item->{$valueColumn}, true);

                return (object)(['key' => $item->{$column}] + $job);
            });
        } catch (\Throwable $e) {
            $this->exceptions->report($e);

            return Collection::make();
        }
    }

    /**
     * Get the next job from the queue connection.
     *
     * @return \Modular\Connector\Jobs\AbstractJob|void
     */
    protected function getNextJob()
    {
        try {
            $job = $this->getJobs(1)->first();

            // Remove job from queue
            if ($job) {
                $this->delete($job->key);
            }

            return $job;
        } catch (\Throwable $e) {
            $this->exceptions->report($e);
        }
    }

    /**
     * Is queue empty?
     *
     * @return bool
     */
    protected function isQueueEmpty()
    {
        return empty($this->getJobs(1)->first());
    }

    /**
     * Is the background process currently running?
     *
     * @return bool
     */
    public function isProcessing()
    {
        return Dispatcher::isProcessing($this->queue);
    }

    /**
     * Has the process been cancelled?
     *
     * @return bool
     */
    public function isCancelled()
    {
        $status = get_site_option($this->getStatusKey(), 0);

        return absint($status) === self::STATUS_CANCELLED;
    }

    /**
     * Has the process been cancelled?
     *
     * @return bool
     */
    public function isPaused()
    {
        $status = get_site_option($this->getStatusKey(), 0);

        return absint($status) === self::STATUS_PAUSED;
    }

    /**
     * Called when background process has been cancelled.
     */
    protected function cancelled()
    {
        $this->clearScheduled();
        $this->deleteAll();

        \do_action($this->identifier . '_cancelled');
    }

    /**
     * Called when background process has been paused.
     */
    protected function paused()
    {
        $this->clearScheduled();

        do_action($this->identifier . '_paused');
    }

    /**
     * Use WP Cron to run the worker.
     *
     * On some sites a timeout of 0.01 is too low and the request is
     * never made, so we must use WP's CRON to execute the worker
     *
     * @return void
     */
    public function healthcheck()
    {
        $this->handle(false);
    }

    /**
     * Schedule the cron healthcheck job.
     *
     * @return array
     */
    public function scheduleHealthcheck()
    {
        $interval = apply_filters($this->identifierCronInterval, $this->interval);
        $intervalInMinutes = $interval / MINUTE_IN_SECONDS;

        $display = $intervalInMinutes === 1 ? __('Every Minute') : sprintf(__('Every %d Minutes'), $intervalInMinutes);

        // Adds an "Every NNN Minute(s)" schedule to the existing cron schedules.
        $schedules[$this->identifierCronInterval] = [
            'interval' => $interval,
            'display' => $display
        ];

        return $schedules;
    }

    /**
     * Clear scheduled cron healthcheck event.
     */
    protected function clearScheduled()
    {
        $timestamp = wp_next_scheduled($this->identifierCron);

        if ($timestamp) {
            wp_unschedule_event($timestamp, $this->identifierCron);
        }
    }

    /**
     * Delete a batch of queued items.
     *
     * @param string $key Key.
     *
     * @return $this
     */
    public function delete($key)
    {
        delete_site_option($key);

        return $this;
    }

    /**
     * Delete entire job queue.
     *
     * @return void
     */
    protected function deleteAll()
    {
        $jobs = $this->getJobs();

        foreach ($jobs as $job) {
            $this->delete($job->key);
        }

        delete_site_option($this->getStatusKey());
    }

    /**
     * Lock the process so that multiple instances can't run simultaneously.
     * Override if applicable, but the duration should be greater than that
     * defined in the time_exceeded() method.
     *
     * @return void
     */
    protected function lock()
    {
        // Set start time of current process.
        $lockDuration = apply_filters($this->identifier . '_queue_lock_time', $this->lockTime);

        set_site_transient($this->identifier . '_process_lock', microtime(), $lockDuration);
    }

    /**
     * Unlock the process so that other instances can spawn.
     *
     * @return void
     */
    protected function unlock()
    {
        delete_site_transient($this->identifier . '_process_lock');
    }

    /**
     * Mark the given job as failed if it should fail on timeouts.
     *
     * @param \Modular\Connector\Jobs\AbstractJob $job
     * @return void
     */
    protected function markJobAsFailedIfItShouldFailOnTimeout($job)
    {
        // TODO pending implementation
        $shouldFailOnTimeout = method_exists($job, 'shouldFailOnTimeout') ? $job->shouldFailOnTimeout() : false;

        if ($shouldFailOnTimeout) {
            $this->failJob($job);
        }
    }

    /**
     * Mark the given job as failed and raise the relevant event.
     *
     * @param \Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Job $job
     * @return void
     */
    protected function failJob($job)
    {
        // TODO pending implementation
        $job->fail();
    }

    /**
     * Get the appropriate timeout for the given job.
     *
     * @param \Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Job|null $job
     * @param \Modular\ConnectorDependencies\Illuminate\Queue\WorkerOptions $options
     * @return int
     */
    protected function timeoutForJob($job)
    {
        // TODO pending implementation
        return $job && !is_null($job->timeout()) ? $job->timeout() : $this->timeout;
    }

    /**
     * Determine if "async" signals are supported.
     *
     * @return bool
     */
    protected function supportsAsyncSignals()
    {
        // TODO pending implementation
        return false && extension_loaded('pcntl');
    }

    /**
     * Get memory limit in bytes.
     *
     * @return int
     */
    protected function getMemoryLimit()
    {
        if (property_exists($this, 'memoryLimit')) {
            return $this->memoryLimit;
        }

        if (function_exists('ini_get')) {
            $memoryLimit = ini_get('memory_limit');
        } else {
            // Sensible default.
            $memoryLimit = '128M';
        }

        if (!$memoryLimit || intval($memoryLimit) === -1) {
            // Unlimited, set to 3GB.
            $memoryLimit = '3200M';
        }

        return wp_convert_hr_to_bytes($memoryLimit);
    }

    /**
     * Determine if the memory limit has been exceeded.
     *
     * @return bool
     */
    public function memoryExceeded()
    {
        return (memory_get_usage(true) / 1024 / 1024) >= $this->getMemoryLimit() * 0.9;
    }

    /**
     * Enable async signals for the process.
     *
     * @return void
     */
    protected function listenForSignals()
    {
        // TODO pending implementation
        pcntl_async_signals(true);

        pcntl_signal(SIGQUIT, fn() => $this->shouldQuit = true);
        pcntl_signal(SIGTERM, fn() => $this->shouldQuit = true);
        pcntl_signal(SIGUSR2, fn() => $this->paused = true);
        pcntl_signal(SIGCONT, fn() => $this->paused = false);
    }

    /**
     * Kill the process.
     *
     * @param int $status
     * @param \Modular\ConnectorDependencies\Illuminate\Queue\WorkerOptions|null $options
     * @return never
     */
    public function kill($status = 0)
    {
        $this->events->dispatch(new WorkerStopping($status));

        if (extension_loaded('posix')) {
            posix_kill(getmypid(), SIGKILL);
        }

        exit($status);
    }

    /**
     * Stop listening and bail out of the script.
     *
     * @param int $status
     * @return int
     */
    public function stop(int $status = 0)
    {
        $this->unlock();

        $this->events->dispatch(new WorkerStopping($status));

        if (!$this->isQueueEmpty()) {
            // No data to process.
            Dispatcher::getInstance()->dispatch($this->queue);
        } else {
            $this->complete();
        }

        do_action('modular_shutdown');
        do_action($this->identifier . '_stop');

        return $status;
    }

    /**
     * Clean options
     *
     * @return void
     */
    public function complete()
    {
        delete_site_option($this->getStatusKey());
        $this->clearScheduled();

        do_action($this->identifier . '_completed');
    }

    /**
     * Register the worker timeout handler.
     *
     * @param \Modular\ConnectorDependencies\Illuminate\Contracts\Queue\Job|null $job
     * @param \Modular\ConnectorDependencies\Illuminate\Queue\WorkerOptions $options
     * @return void
     */
    protected function registerTimeoutHandler($job)
    {
        // We will register a signal handler for the alarm signal so that we can kill this
        // process if it is running too long because it has frozen. This uses the async
        // signals supported in recent versions of PHP to accomplish it conveniently.
        pcntl_signal(SIGALRM, function () use ($job) {
            if ($job) {
                // TODO Implements max attempts

                $this->markJobAsFailedIfItShouldFailOnTimeout($job);
            }

            $this->kill(static::EXIT_ERROR);
        }, true);

        pcntl_alarm(
            max($this->timeoutForJob($job), 0)
        );
    }

    /**
     * Reset the worker timeout handler.
     *
     * @return void
     */
    protected function resetTimeoutHandler()
    {
        pcntl_alarm(0);
    }

    /**
     * Determine the exit code to stop the process if necessary.
     *
     * @param int $startTime
     * @param int $jobsProcessed
     * @param mixed $job
     * @return int|null
     */
    protected function stopIfNecessary(int $startTime = 0, int $jobsProcessed = 0, $job = null)
    {
        switch (true) {
            case $this->stopWhenEmpty && is_null($job):
            case $this->shouldQuit:
            case $this->maxJobs && $jobsProcessed >= $this->maxJobs:
                return static::EXIT_SUCCESS;
            case $this->timeout && (hrtime(true) / 1e9 - $startTime >= $this->timeout):
            case $this->memoryExceeded():
                return static::EXIT_MEMORY_LIMIT;
            default:
                return null;
        }
    }

    /**
     * Maybe process a batch of queued items.
     *
     * Checks whether data exists within the queue and that
     * the process is not already running.
     *
     * @return false|int
     */
    public function handle(bool $verifyNonce = true)
    {
        Utils::configMaxLimit();
        Utils::forceResponse('Call to Worker handle with verify: ' . ($verifyNonce ? 1 : 0));

        if ($this->isProcessing()) {
            // Background process already running.
            return false;
        }

        if ($this->isCancelled()) {
            $this->cancelled();
            return false;
        }

        if ($this->isPaused()) {
            $this->paused();

            return false;
        }

        if ($this->isQueueEmpty()) {
            // No data to process.
            $this->clearScheduled();

            return false;
        }

        if ($verifyNonce) {
            check_ajax_referer($this->identifier, 'nonce');
        }

        $this->lock();

        if ($supportsAsyncSignals = $this->supportsAsyncSignals()) {
            $this->listenForSignals();
        }

        [$startTime, $jobsProcessed] = [hrtime(true) / 1e9, 0];

        while (true) {
            // First, we will attempt to get the next job off of the queue. We will also
            // register the timeout handler and reset the alarm for this job so it is
            // not stuck in a frozen state forever. Then, we can fire off this job.
            $job = $this->getNextJob();

            if ($job) {
                $job = maybe_unserialize($job->data['command']);
            }

            if ($supportsAsyncSignals) {
                $this->registerTimeoutHandler($job);
            }

            if ($job) {
                $jobsProcessed++;

                $this->runJob($job);
            }

            if ($supportsAsyncSignals) {
                $this->resetTimeoutHandler();
            }

            // Finally, we will check to see if we have exceeded our memory limits or if
            // the queue should restart based on other indications. If so, we'll stop
            // this worker and let whatever is "monitoring" it restart the process.
            $status = $this->stopIfNecessary($startTime, $jobsProcessed, $job);

            if (!is_null($status)) {
                return $this->stop($status);
            }
        }

        return $this->stop(static::EXIT_SUCCESS);
    }

    public function runJob($job)
    {
        try {
            return Dispatcher::getInstance()->dispatchSync($job);
        } catch (\Throwable $e) {
            $this->exceptions->report($e);
        }
    }
}
