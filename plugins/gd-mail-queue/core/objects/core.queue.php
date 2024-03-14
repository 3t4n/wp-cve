<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_queue {
    public $requeue_count = 2;

    public $limit = 100;
    public $limit_flex = false;
    public $cron = 5;
    public $timeout = 20;
    public $requeue = true;
    public $header = true;
    public $from = false;
    public $from_email = '';
    public $from_name = '';
    public $reply = false;
    public $reply_email = '';
    public $reply_name = '';
    public $sender = false;
    public $sender_email = '';

    public $sleep_batch = 50000;
    public $sleep_single = 5000;

    private $start = 0;
    private $end = 0;
    private $blog = 0;

    public function __construct() {
        foreach (array('limit', 'sleep_batch', 'sleep_single', 'limit_flex', 'cron', 'timeout', 'requeue', 'header', 'from', 'from_email', 'from_name', 'reply', 'reply_email', 'reply_name', 'sender', 'sender_email') as $key) {
            $this->$key = gdmaq_settings()->get($key, 'queue');
        }

        add_action('gdmaq_plugin_init', array($this, 'init'));
        add_action('gdmaq_run_queue', array($this, 'run'));
    }

    /** @return gdmaq_core_queue */
    public static function instance() {
        static $_gdmaq_queue = false;

        if (!$_gdmaq_queue) {
            $_gdmaq_queue = new gdmaq_core_queue();
        }

        return $_gdmaq_queue;
    }

    public function is_paused() {
        return apply_filters('gdmaq_queue_paused', false);
    }

    public function start() {
        $this->end = $this->start = time();
    }

    public function end() {
        $this->end = microtime(true);

        return $this->end - $this->start;
    }

    public function init() {
        add_filter('cron_schedules', array($this, 'cron_schedules'));

        if ($this->is_paused()) {
            d4p_remove_cron('gdmaq_run_queue');
        } else {
            if (!$this->next_queue_run()) {
                wp_schedule_event(time() + 60, 'gdmaq_queue', 'gdmaq_run_queue');
            }
        }

        $this->blog = get_current_blog_id();
    }

    public function next_queue_run() {
        return wp_next_scheduled('gdmaq_run_queue');
    }

    public function cron_schedules($schedules) {
        $schedules['gdmaq_queue'] = array(
            'interval' => 60 * $this->cron,
            'display'  => esc_html__("Mailer Queue Interval", "gd-mail-queue"),
        );

        return $schedules;
    }

    public function run() {
        set_time_limit(0);

        gdmaq_engine_sender()->prepare();

        set_transient('gdmaq_queue_status', 'run');

        $this->start();
        $this->requeue();

        $cycles = 0;
        $timeout = false;

        $sent = $failed = $attachments = 0;

        do {
            $result = $this->run_batch();

            if ($this->sleep_batch > 0) {
                usleep($this->sleep_batch);
            }

            $timer = $this->end();

            $cycles++;

            $time_cycle = $timer / $cycles;

            if ($time_cycle * ($cycles + 1) >= $this->timeout) {
                $timeout = true;
            }

            if ($result !== false) {
                $sent+= $result['sent'];
                $failed+= $result['failed'];
                $attachments+= $result['attachments'];

                foreach ($result['types'] as $type => $stats) {
                    foreach ($stats as $_key => $_val) {
                        gdmaq_settings()->update_statistics_for_type($type, 'total_queue_'.$_key, $_val);
                    }
                }

                if ($result['more'] === false) {
                    $result = false;
                }
            }
        } while ($result !== false && $timeout === false && $this->limit_flex);

        if ($sent + $failed > 0) {
            gdmaq_settings()->update_statistics('last_queue_started', $this->start, true);
            gdmaq_settings()->update_statistics('last_queue_time', $this->end(), true);
            gdmaq_settings()->update_statistics('last_queue_sent', $sent, true);
            gdmaq_settings()->update_statistics('last_queue_failed', $failed, true);
            gdmaq_settings()->update_statistics('last_queue_attachments', $attachments, true);

            gdmaq_settings()->update_statistics('total_queue_runs', 1);
            gdmaq_settings()->update_statistics('total_queue_time', $this->end());
            gdmaq_settings()->update_statistics('total_queue_sent', $sent);
            gdmaq_settings()->update_statistics('total_queue_failed', $failed);
            gdmaq_settings()->update_statistics('total_queue_attachments', $attachments);
        }

        gdmaq_settings()->update_statistics('total_queue_calls', 1);

        gdmaq_settings()->save('statistics');

        delete_transient('gdmaq_queue_status');
    }

    private function requeue() {
        if ($this->requeue) {
            $period = 60 * $this->cron * $this->requeue_count;
            $limit = gmdate('Y-m-d H:i:s', time() - $period);

            gdmaq_db()->requeue_waiting($this->blog, $limit);
        }
    }

    private function run_batch() {
        $list = gdmaq_db()->queue_get_batch($this->blog, $this->limit);

        if (empty($list)) {
            return false;
        }

        gdmaq_db()->queue_batch_waiting($list);

        $sent = $failed = $attachments = 0;
        $types = array();

        foreach ($list as $item) {
            $email = new gdmaq_core_email('queue', $item);

            $status = $this->send($email);

            if ($status['result']) {
                if (!isset($types[$item->type]['sent'])){
                    $types[$item->type]['sent'] = 0;
                };

                $types[$item->type]['sent']++;

                $sent++;
                $attachments+= $status['attachments'];
            } else {
                if (!isset($types[$item->type]['failed'])){
                    $types[$item->type]['failed'] = 0;
                };

                $types[$item->type]['failed']++;

                $failed++;
            }

            gdmaq_db()->queue_update_item_status($item->id, $status['result'] ? 'sent' : 'fail', $status['message']);

            if ($this->sleep_single > 0) {
                usleep($this->sleep_single);
            }
        }

        return array(
            'more' => count($list) == $this->limit,
            'sent' => $sent,
            'failed' => $failed,
            'types' => $types,
            'attachments' => $attachments
        );
    }

	private function send($email) {
		if ($email->is_valid()) {
			$status = gdmaq_engine_sender()->queue_send($email);
		} else {
			$status = array(
				'result' => false,
				'engine' => gdmaq_mailer()->engine,
				'operation' => 'queue',
				'attachments' => 0,
				'code' => '',
				'message' => __("This message has no recipient set.", "gd-mail-queue")
			);
		}

		return $status;
	}

    public function counts($name = false) {
        $list = array(
            'queue' => 0,
            'ok' => 0,
            'fail' => 0,
            'waiting' => 0
        );

        $counts = gdmaq_db()->queue_counts(get_current_blog_id());

        foreach ($counts as $count) {
            $list[$count->status] = absint($count->items);
        }

        return $name === false ? $list : $list[$name];
    }

    public function is_running() {
        return get_transient('gdmaq_queue_status') === 'run';
    }
}

/** @return gdmaq_core_queue */
function gdmaq_queue() {
    return gdmaq_core_queue::instance();
}
