<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_core_db extends d4p_wpdb_core {
    public $_prefix = 'gdmaq';
    public $_tables = array(
        'queue',
        'emails',
        'log_email',
        'log');
    public $_network_tables = array(
        'queue',
        'emails',
        'log_email',
        'log');

    /** @return gdmaq_core_db */
    public static function instance() {
        static $_gdmaq_db = false;

        if (!$_gdmaq_db) {
            $_gdmaq_db = new gdmaq_core_db();
        }

        return $_gdmaq_db;
    }

    public function add_mail_to_queue($args = array()) {
        if ( empty($args['blog_id']) || $args['blog_id'] == 0) {
            $args['blog_id'] = get_current_blog_id();
        }

        $args['queued'] = gdmaq()->datetime->mysql_date();

        $this->insert($this->queue, $args);

        return $this->get_insert_id();
    }

    public function email_get_id($email, $name = '') {
        $sql = $this->prepare("SELECT * FROM ".$this->emails." WHERE `email` = %s", $email);
        $raw = $this->get_row($sql);

        if ($raw) {
            if (!empty($name) && $name != $raw->name) {
                $this->update($this->emails, array('name' => $name), array('id' => $raw->id));
            }

            return $raw->id;
        } else {
            return $this->email_insert($email, $name);
        }
    }

    public function email_insert($email, $name = '') {
        $this->insert($this->emails, array('email' => $email, 'name' => $name));

        return $this->get_insert_id();
    }

    public function email_log_add_entry($entry = array()) {
        $defaults = array(
            'blog_id' => get_current_blog_id(),
            'logged' => gdmaq()->datetime->mysql_date(),
            'operation' => 'mail',
            'engine' => 'phpmailer',
            'status' => 'ok',
            'type' => 'mail',
            'subject' => '',
            'plain' => '',
            'html' => '',
            'headers' => '',
            'attachments' => '',
            'extras' => '',
            'mailer' => '',
            'message' => ''
        );

        $entry = shortcode_atts($defaults, $entry);

	    $entry['subject'] = wp_kses($entry['subject'], 'post');
	    $entry['plain'] = wp_kses($entry['plain'], 'post');

        if (strlen($entry['message']) > 254) {
            $entry['message'] = substr($entry['message'], 0, 254);
        }

        $this->insert($this->log, $entry);

        return $this->get_insert_id();
    }

    public function email_log_add_relation($log_id, $email_id, $rel) {
        $this->insert($this->log_email, array('log_id' => $log_id, 'email_id' => $email_id, 'rel' => $rel));
    }

    public function email_log_update_status($log_id, $status = 'fail', $message = '') {
        if (strlen($message) > 254) {
            $message = substr($message, 0, 254);
        }

        $this->update($this->log, array('status' => $status, 'message' => $message), array('id' => $log_id));
    }

    public function email_log_cleanup($blog_id, $days = 365) {
        $sql = "DELETE l, e FROM ".$this->log." l INNER JOIN ".$this->log_email." e ON e.`log_id` = l.`id` WHERE l.`logged` < DATE_SUB(CURDATE(), INTERVAL ".absint($days)." DAY) AND l.`blog_id` = ".absint($blog_id);

        $this->query($sql);
    }

    public function email_log_delete($log_ids) {
        $log_ids = (array)$log_ids;
        $log_ids = array_map('absint', $log_ids);
        $log_ids = array_filter($log_ids);

        if (!empty($log_ids)) {
            $sql = "DELETE l, e FROM ".$this->log." l INNER JOIN ".$this->log_email." e ON e.`log_id` = l.`id` WHERE l.`id` IN (".join(',', $log_ids).")";

            $this->query($sql);

            return $this->rows_affected();
        }

        return 0;
    }

    public function email_get_failed($log_ids) {
        $log_ids = (array)$log_ids;
        $log_ids = array_map('absint', $log_ids);
        $log_ids = array_filter($log_ids);

        if (!empty($log_ids)) {
            $sql = "SELECT `id` FROM ".$this->log." WHERE `id` IN (".join(',', $log_ids).") AND `status` = 'fail'";

            $results = $this->get_results($sql);

            return wp_list_pluck($results, 'id');
        }

        return array();
    }

    public function email_log_get_html($log_id) {
        $sql = $this->prepare("SELECT html FROM ".$this->log." WHERE id = %d", $log_id);
        $raw = $this->get_var($sql);

        if ($raw) {
            return $raw;
        }

        return false;
    }

    public function email_log_get_entry($log_id) {
        $sql = $this->prepare("SELECT * FROM ".$this->log." WHERE id = %d", $log_id);
        $raw = $this->get_row($sql);

        if ($raw) {
            $_emails = $this->email_log_get_emails($log_id);

            if (isset($_emails[$log_id])) {
                $raw->emails = $_emails[$log_id];
            }

            $raw->headers = json_decode($raw->headers);
            $raw->attachments = json_decode($raw->attachments);
            $raw->extras = json_decode($raw->extras);
            $raw->mailer = json_decode($raw->mailer);

            return $raw;
        }

        return false;
    }

    public function email_log_get_emails($log_ids) {
        $log_ids = (array)$log_ids;

        if (empty($log_ids)) {
            return array();
        }

        $sql = "SELECT * FROM ".$this->emails." e INNER JOIN ".$this->log_email." le ON le.email_id = e.id WHERE le.log_id IN (".join(", ", $log_ids).")";
        $raw = $this->run($sql);

        $list = array();

        foreach ($raw as $row) {
            if (!isset($list[$row->log_id])) {
                $list[$row->log_id] = array(
                    'to' => array(),
                    'from' => array(),
                    'cc' => array(),
                    'bcc' => array(),
                    'reply_to' => array()
                );
            }

            $list[$row->log_id][$row->rel][] = $row;
        }

        return $list;
    }

    public function email_log_count() {
        $sql = "SELECT COUNT(*) FROM ".$this->log;

        return $this->get_var($sql);
    }

    public function email_emails_count() {
        $sql = "SELECT COUNT(*) FROM ".$this->emails;

        return $this->get_var($sql);
    }

    public function queue_cleanup($blog_id, $scope = array(), $days = 30) {
        $sql = "DELETE FROM ".$this->queue." WHERE `blog_id` = ".absint($blog_id)." AND `status` IN ('".join("', '", $scope)."') AND `queued` < DATE_SUB(CURDATE(), INTERVAL ".absint($days)." DAY)";

        $this->query($sql);
    }

    public function queue_counts($blog_id) {
        $sql = "SELECT `status`, COUNT(*) AS items FROM ".$this->queue." WHERE `blog_id` = ".absint($blog_id)." GROUP BY `status`";

        return $this->get_results($sql);
    }

    public function requeue_waiting($blog_id, $logged_limit) {
        $sql = $this->wpdb()->prepare("UPDATE ".$this->queue." SET `status` = 'queue' WHERE `status` = 'waiting' AND `blog_id` = %d AND `queued` < %s", $blog_id, $logged_limit);

        $this->query($sql);
    }

    public function queue_get_batch($blog_id = 0, $limit = 100) {
        $sql = $this->wpdb()->prepare("SELECT * FROM ".$this->queue." WHERE `status` = 'queue' AND `blog_id` = %d LIMIT 0, %d", $blog_id, $limit);

        return $this->get_results($sql);
    }

    public function queue_batch_waiting($list) {
        $ids = wp_list_pluck($list, 'id');
        $ids = array_map('absint', $ids);

        $sql = "UPDATE ".$this->queue." SET `status` = 'waiting' WHERE `id` IN(".join(", ", $ids).")";
        $this->query($sql);
    }

    public function queue_update_item_status($item_id, $status = 'ok', $message = '') {
	    if (strlen($message) > 254) {
		    $message = substr($message, 0, 254);
	    }

        $this->update($this->queue, array('status' => $status, 'sent' => gdmaq()->datetime->mysql_date(), 'message' => $message), array('id' => $item_id));
    }

    public function dashboard_count_errors($blog_id, $since) {
        $sql = "SELECT COUNT(*) FROM ".$this->log." WHERE blog_id = ".absint($blog_id)." AND `status` = 'fail' AND `logged` > '".$since."'";
        return $this->get_var($sql);
    }

    public function dashboard_latest_errors($blog_id, $since, $limit = 10) {
        $sql = "SELECT `engine`, `message`, `logged` FROM ".$this->log." WHERE `blog_id` = ".absint($blog_id)." AND `status` = 'fail' AND `logged` > '".$since."' GROUP BY `engine`, `message` ORDER BY `id` DESC LIMIT 0, ".absint($limit);
        return $this->get_results($sql);
    }
}

/** @return gdmaq_core_db */
function gdmaq_db() {
    return gdmaq_core_db::instance();
}
