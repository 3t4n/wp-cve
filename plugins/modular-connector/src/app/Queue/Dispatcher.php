<?php

namespace Modular\Connector\Queue;

use Modular\Connector\Jobs\AbstractJob;
use Modular\ConnectorDependencies\Illuminate\Container\Container;
use Modular\ConnectorDependencies\Illuminate\Pipeline\Pipeline;
use Modular\ConnectorDependencies\Illuminate\Queue\InvalidPayloadException;
use Modular\ConnectorDependencies\Illuminate\Support\Str;
use function Modular\ConnectorDependencies\app;

class Dispatcher
{
    /**
     * Prefix
     *
     * @var string
     * @access protected
     */
    public static string $prefix = 'modular';

    /**
     * @var Dispatcher|null
     */
    protected static ?Dispatcher $instance = null;

    /**
     * The container implementation.
     *
     * @var Container
     */
    protected Container $container;

    /**
     * The pipeline instance for the bus.
     *
     * @var Pipeline
     */
    protected Pipeline $pipeline;

    /**
     * @var array
     */
    protected array $jobs = [];

    /**
     * The pipes to send commands through before dispatching.
     *
     * @var array
     */
    protected array $pipes = [];

    /**
     * Create a new command dispatcher instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->container = $container = app();
        $this->pipeline = new Pipeline($container);
    }

    /**
     * @return static
     */
    public static function getInstance()
    {
        if (static::$instance === null) {
            static::$instance = new static();
        }

        return static::$instance;
    }

    /**
     * Get identifier for queue
     *
     * @param string $queue
     * @param string|null $suffix
     * @return string
     */
    public static function getIdentifier(string $queue, string $suffix = null)
    {
        return static::$prefix . '_' . $queue . ($suffix ?: '');
    }

    /**
     * @param string|null $queue
     * @return string
     */
    public function getKey(string $queue)
    {
        $unique = md5(microtime() . wp_rand());
        $prepend = static::getIdentifier($queue, '_');

        return \substr($prepend . $unique, 0, 64);
    }

    /**
     * @param AbstractJob $command
     * @return mixed
     */
    public function dispatchSync(AbstractJob $command)
    {
        $callback = function ($command) {
            $method = method_exists($command, 'handle') ? 'handle' : '__invoke';

            return $this->container->call([$command, $method]);
        };

        return $this->pipeline->send($command)->through($this->pipes)->then($callback);
    }

    /**
     * Push a new job onto the queue.
     *
     * @param AbstractJob $job
     * @param string|null $queue
     * @return mixed
     */
    public function dispatchToQueue(AbstractJob $job, ?string $queue = null)
    {
        $job = $this->createPayload($job);

        $queue = $queue ?: 'default';

        if (!isset($this->jobs[$queue])) {
            $this->jobs[$queue] = [];
        }

        $this->jobs[$queue][] = $job;

        return $this;
    }

    /**
     * Create a payload string from the given job
     *
     * @param \Closure|string|object $job
     * @return string
     */
    protected function createPayload($job)
    {
        $payload = json_encode($value = $this->createPayloadArray($job), \JSON_UNESCAPED_UNICODE);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new InvalidPayloadException(
                'Unable to JSON encode payload. Error code: ' . json_last_error()
            );
        }

        return $payload;
    }

    /**
     * Create a payload array from the given job and data.
     *
     * @param string|object $job
     * @return array
     */
    protected function createPayloadArray($job): array
    {
        return [
            'uuid' => Str::uuid(),
            'displayName' => get_class($job),
            'data' => [
                'commandName' => get_class($job),
                'command' => serialize(clone $job),
            ],
        ];
    }

    /**
     * Save jobs in database
     *
     * @return Dispatcher
     */
    protected function save()
    {
        foreach ($this->jobs as $queue => $jobs) {
            foreach ($jobs as $payload) {
                update_site_option($this->getKey($queue), $payload);
            }
        }

        return $this;
    }

    /**
     * Is the background process currently running?
     *
     * @return bool
     */
    public static function isProcessing(string $queue)
    {
        return !empty(get_site_transient(static::getIdentifier($queue, '_process_lock')));
    }

    /**
     * Get query args.
     *
     * @param string $queue
     * @return array
     */
    protected function getQueryArgs(string $queue)
    {
        $args = [
            'action' => static::getIdentifier($queue),
            'nonce' => wp_create_nonce(static::getIdentifier($queue))
        ];

        /**
         * Filters the post arguments used during an async request.
         *
         * @param array $url
         */
        return apply_filters(static::getIdentifier($queue) . '_query_args', $args);
    }

    /**
     * Get query URL.
     *
     * @param string $queue
     * @return string
     */
    protected function getQueryUrl(string $queue)
    {
        $url = admin_url('admin-ajax.php');

        /**
         * Filters the post arguments used during an async request.
         *
         * @param string $url
         */
        return apply_filters(static::getIdentifier($queue, '_query_url'), $url);
    }

    /**
     * Get post args.
     *
     * @return array
     */
    protected function getResquestData(string $queue)
    {
        $args = [
            'timeout' => 30, // In some websites, the default value of 5 seconds is too short.
            'blocking' => false,
            'sslverify' => false,
        ];

        /**
         * Filters the post arguments used during an async request.
         *
         * @param array $args
         */
        return apply_filters(static::getIdentifier($queue, '_post_args'), $args);
    }

    /**
     * Schedule the cron healthcheck event.
     *
     * @param string $queue
     * @return void
     */
    protected function schedule(string $queue)
    {
        if (!wp_next_scheduled(static::getIdentifier($queue, '_cron'))) {
            wp_schedule_event(
                time(),
                static::getIdentifier($queue, '_cron_interval'),
                static::getIdentifier($queue, '_cron')
            );
        }
    }

    /**
     * Dispatch queue
     *
     * @param string $queue
     * @return void
     */
    public function dispatch(string $queue)
    {
        $this->schedule($queue);

        $url = add_query_arg($this->getQueryArgs($queue), $this->getQueryUrl($queue));

        $args = $this->getResquestData($queue);

        wp_remote_get(esc_url_raw($url), $args);
    }

    /**
     * Dispatch jobs
     *
     * @return $this
     */
    protected function dispatchJobs()
    {
        $queues = array_keys($this->jobs);

        foreach ($queues as $queue) {
            $this->dispatch($queue);
        }
    }

    /**
     * Force destroy the queue dispatcher to force execute __destruct() method.
     *
     * @return void
     */
    public static function forceDestroy()
    {
        if (static::$instance !== null) {
            $instance = static::$instance;
            static::$instance = null;
            unset($instance);
        }
    }

    public function __destruct()
    {
        $this->save()->dispatchJobs();
    }
}
