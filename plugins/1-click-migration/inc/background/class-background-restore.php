<?php

namespace OCM;

use WP_Background_Process;

class OCM_BackgroundRestore extends WP_Background_Process
{
    const RETURN_TYPE_STOP_PROCESSS = 'stop_process';
    protected $action = 'ocm_background_restore';

    public function __construct()
    {
        parent::__construct();

        add_filter(
            $this->getIdentifier() . '_default_time_limit',
            static function () {
                return One_Click_Migration::get_timeout();
            }
        );
    }

    protected function getIdentifier()
    {
        return $this->identifier;
    }

    /**
     * Task
     *
     * Override this method to perform any actions required on each
     * queue item. Return the modified item for further processing
     * in the next pass through. Or, return false to remove the
     * item from the queue.
     *
     * @param mixed $item Queue item to iterate over
     *
     * @return mixed
     */
    protected function task($data)
    {
        list($username, $password) = $data;

        OCM_Backup::start_restore_process($username, $password);
        One_Click_Migration::write_to_log('Download task has been completed.');

        return false;
    }

    /**
     * Complete
     *
     * Override if applicable, but ensure that the below actions are
     * performed, or, call parent::complete().
     */
    protected function complete()
    {
        One_Click_Migration::write_to_log('Restore completed.');

        OCM_Backup::reset_current_restore_steps();
        One_Click_Migration::reset_actions_start_mark();
        One_Click_Migration::cancel_all_process();

        parent::complete();
    }

    /**
     * Handle
     *
     * Pass each queue item to the task handler, while remaining
     * within server memory and time limit constraints.
     */
    protected function handle() {
        $this->lock_process();
        $isStopProcess = false;

        do {
            $batch = $this->get_batch();

            foreach ( $batch->data as $key => $value ) {
                $task = $this->task( $value );

                if ( self::RETURN_TYPE_STOP_PROCESSS === $task ) {
                    $isStopProcess = self::RETURN_TYPE_STOP_PROCESSS;
                    break;
                } elseif ( false !== $task ) {
                    $batch->data[ $key ] = $task;
                } else {
                    unset( $batch->data[ $key ] );
                    $this->update( $batch->key, $batch->data );
                }

                if ( $this->time_exceeded() || $this->memory_exceeded() ) {
                    // Batch limits reached.
                    break;
                }
            }

            // Update or delete current batch.
            if ( ! empty( $batch->data ) ) {
                $this->update( $batch->key, $batch->data );
            } else {
                $this->delete( $batch->key );
            }
        } while (
            !$isStopProcess && $isStopProcess !== self::RETURN_TYPE_STOP_PROCESSS &&
            !$this->time_exceeded() && !$this->memory_exceeded() && !$this->is_queue_empty()
        );

        $this->unlock_process();

        if (self::RETURN_TYPE_STOP_PROCESSS === $isStopProcess) {
            wp_die();
        }

        // Start next batch or complete process.
        if ( !$this->is_queue_empty() ) {
            $this->dispatch();
        } else {
            $this->complete();
        }

        wp_die();
    }

    /**
     * Is queue empty
     *
     * @return bool
     */
    public function is_queue_empty()
    {
        global $wpdb;

        $table  = $wpdb->options;
        $column = 'option_name';

        if ( is_multisite() ) {
            $table  = $wpdb->sitemeta;
            $column = 'meta_key';
        }

        $key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

        $count = $wpdb->get_var( $wpdb->prepare( "
			SELECT COUNT(*)
			FROM {$table}
			WHERE {$column} LIKE %s
		", $key ) );

        return $count <= 0;
    }

    public function queue_size()
    {
        global $wpdb;

        $table  = $wpdb->options;
        $column = 'option_name';

        if ( is_multisite() ) {
            $table  = $wpdb->sitemeta;
            $column = 'meta_key';
        }

        $key = $wpdb->esc_like( $this->identifier . '_batch_' ) . '%';

        return $wpdb->get_var( $wpdb->prepare( "
            SELECT COUNT(*)
            FROM {$table}
            WHERE {$column} LIKE %s
        ", $key ) );
    }

    public function cancel_all_process()
    {
      $this->cancel_process();

    }

    public function cancel_scheduled_event()
    {

      $this->clear_scheduled_event();
    }

    /**
     * Time exceeded.
     *
     * Ensures the batch never exceeds a sensible time limit.
     * A timeout limit of 30s is common on shared hosting.
     *
     * @return bool
     */
    public function time_exceeded()
    {
        $finish = $this->start_time + apply_filters( $this->identifier . '_default_time_limit', 20 ); // 20 seconds
        $return = false;

        if ( time() >= $finish ) {
            $return = true;
        }

        return apply_filters( $this->identifier . '_time_exceeded', $return );
    }

    public function remaining_time()
    {
        $finish = $this->start_time + apply_filters( $this->identifier . '_default_time_limit', 20 ); // 20 seconds
        return $finish - time();
    }

    public function restart_task()
    {
        $this->unlock_process();

        if ( !$this->is_queue_empty() ) {
            One_Click_Migration::write_to_log(OCM_Backup::LOG_MESSAGE_BG_PROCESS_RESTARTING);
            One_Click_Migration::write_to_log(OCM_Backup::LOG_MESSAGE_BG_PROCESS_RESTARTING_LOG);
            sleep(1);

            // Start next batch
            $this->dispatch();
        }

        wp_die();
    }
}
