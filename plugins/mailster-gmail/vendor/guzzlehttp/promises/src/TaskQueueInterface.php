<?php
/**
 * @license MIT
 *
 * Modified by __root__ on 06-December-2022 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace Mailster\Gmail\GuzzleHttp\Promise;

interface TaskQueueInterface
{
    /**
     * Returns true if the queue is empty.
     *
     * @return bool
     */
    public function isEmpty();

    /**
     * Adds a task to the queue that will be executed the next time run is
     * called.
     */
    public function add(callable $task);

    /**
     * Execute all of the pending task in the queue.
     */
    public function run();
}
