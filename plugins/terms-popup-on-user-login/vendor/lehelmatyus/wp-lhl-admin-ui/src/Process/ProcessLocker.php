<?php

namespace WpLHLAdminUi\Process;

/**
 * Locks a Process until it finishes execution
 * sets a cache
 * allow a process to check if cache has already been set
 */


class ProcessLocker {

    private $lock_key = '';

    public function __construct($lock_key, $max_duration = 10) {

        // Generate a unique key for the lock
        $this->lock_key = $lock_key;

        // Attempt to acquire the lock
        set_transient($this->lock_key, "Locked", '', $max_duration); // Lock expires after 10 seconds
    }

    public function is_process_running() {
        if (get_transient($this->lock_key)) {
            // Another request is already running, so wait or return an error
            return true;
        }
        return false;
    }

    public function release_lock() {
        delete_transient($this->lock_key);
    }

    public function get_error() {
        $error = new \WP_Error();
        $error->add("already_running", __('Another request is still processing.'), array('status' => 401));
        return $error;
    }
}
