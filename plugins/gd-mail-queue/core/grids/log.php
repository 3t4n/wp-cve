<?php

if (!defined('ABSPATH')) { exit; }

class gdmaq_emails_log extends d4p_grid {
    public $_sanitize_orderby_fields = array('l.id', 'l.logged');
    public $_checkbox_field = 'id';
    public $_table_class_name = 'gdmaq-emails-log';

    public $_operation = '';
    public $_emails = array();

    function __construct($args = array()) {
        $this->_operation = isset($_GET['operation']) && !empty($_GET['operation']) ? d4p_sanitize_slug($_GET['operation']) : '';

        parent::__construct(array(
            'singular' => 'entry',
            'plural' => 'entries',
            'ajax' => false
        ));
    }

    private function _get_emails($log_id, $rel) {
        if (isset($this->_emails[$log_id])) {
            return $this->_emails[$log_id][$rel];
        }

        return array();
    }

    private function _render_tag($tag, $label, $title = '') {
        return '<span class="gdmaq-item-tag gdmaq-tag-'.$tag.'"'.(!empty($title) ? ' title="'.$title.'"' : '').'>'.$label.'</span>';
    }

    private function _render_email($email, $rel) {
        $search = urlencode($rel.' '.$email->email);
        return '<span class="gdmaq-single-email gdmaq-email-'.$rel.'"><a href="'.$this->_self("s=".$search).'">'.$email->email.'</a></span>';
    }

    private function _render_emails($emails, $rel) {
        $out = array();

        foreach ($emails as $email) {
            $out[] = $this->_render_email($email, $rel);
        }

        return $out;
    }

    private function _self($args, $getback = false, $id = false, $action = 'panel') {
        $base_url = 'admin.php?page=gd-mail-queue-log';
        $url = $base_url.'&'.$args;

        if ($this->_operation != '') {
            $url.= '&operation='.$this->_operation;
        }

        if ($getback) {
            $_nonce = 'gdmaq-log-'.$action;

            if ($id !== false) {
                $_nonce.= '-'.$id;
            }

            $url.= '&_wpnonce='.wp_create_nonce($_nonce);
            $url.= '&gdmaq_handler=getback';
            $url.= '&_wp_http_referer='.wp_unslash(self_admin_url($base_url));
        }

        return self_admin_url($url);
    }

    public function get_views() {
        $url = 'admin.php?page=gd-mail-queue-log';

        return array(
            'all' => '<a href="'.$url.'" class="'.($this->_operation == '' ? 'current' : '').'">'.__("All", "gd-mail-queue").'</a>',
            'mail' => '<a href="'.add_query_arg('operation', 'mail', $url).'" class="'.($this->_operation == 'mail' ? 'current' : '').'">'.__("From WP Mail", "gd-mail-queue").'</a>',
            'queue' => '<a href="'.add_query_arg('operation', 'queue', $url).'" class="'.($this->_operation == 'queue' ? 'current' : '').'">'.__("From Queue", "gd-mail-queue").'</a>'
        );
    }

    protected function extra_tablenav($which) {
        if ($which == 'top') {
            $all_periods = array_merge(array(
                'all' => __("All Time", "gd-mail-queue"),
                'hr-01' => __("Last hour", "gd-mail-queue"),
                'hr-04' => __("Last 4 hours", "gd-mail-queue"),
                'hr-08' => __("Last 8 hours", "gd-mail-queue"),
                'hr-12' => __("Last 12 hours", "gd-mail-queue"),
                'dy-01' => __("Last day", "gd-mail-queue"),
                'dy-02' => __("Last 2 days", "gd-mail-queue"),
                'dy-03' => __("Last 3 day", "gd-mail-queue"),
                'dy-05' => __("Last 5 day", "gd-mail-queue"),
                'dy-07' => __("Last 7 day", "gd-mail-queue"),
                'dy-30' => __("Last 30 days", "gd-mail-queue")
            ), $this->list_all_months_dropdown());

            $all_statuses = array(
                'all' => __("All Statuses", "gd-mail-queue"),
                'ok' => __("Sent OK", "gd-mail-queue"),
                'fail' => __("Sending Failed", "gd-mail-queue"),
                'queue' => __("Sent to Queue", "gd-mail-queue"),
                'retry' => __("Failed and Retried", "gd-mail-queue")
            );

            $all_types = array_merge(array(
                'all' => __("All Types", "gd-mail-queue"),
                'mail' => __("Unspecified Type", "gd-mail-queue")
            ), gdmaq_mailer()->detection()->supported_types);

            $_sel_period = isset($_GET['filter-period']) && !empty($_GET['filter-period']) ? d4p_sanitize_slug($_GET['filter-period']) : '';
            $_sel_status = isset($_GET['filter-status']) && !empty($_GET['filter-status']) ? d4p_sanitize_slug($_GET['filter-status']) : '';
            $_sel_type = isset($_GET['filter-type']) && !empty($_GET['filter-type']) ? d4p_sanitize_slug($_GET['filter-type']) : '';

            echo '<div class="alignleft actions">';
            d4p_render_select($all_periods, array('selected' => $_sel_period, 'name' => 'filter-period'));
            d4p_render_select($all_statuses, array('selected' => $_sel_status, 'name' => 'filter-status'));
            d4p_render_select($all_types, array('selected' => $_sel_type, 'name' => 'filter-type'));

            do_action('gdmaq_admin_grid_log_filter');

            submit_button(__("Filter", "gd-mail-queue"), 'button', false, false, array('id' => 'gdmaq-log-submit'));
            echo '</div>';
        }
    }

    public function list_all_months_dropdown() {
        global $wp_locale;

        $sql = "SELECT DISTINCT YEAR(`logged`) AS year, MONTH(`logged`) AS month FROM ".gdmaq_db()->log." WHERE `blog_id` = ".get_current_blog_id()." ORDER BY logged DESC";
        $months = gdmaq_db()->run($sql);

        $list = array();

        foreach ($months as $row) {
            if ($row->month > 0 && $row->year > 0) {
                $month = zeroise($row->month, 2);
                $year = $row->year;

                $list[$year.'-'.$month] = sprintf(_x("%s %s", "Month Year", "gd-mail-queue"), $wp_locale->get_month($month), $year);
            }
        }

        return $list;
    }

    public function get_row_classes($item) {
        return array('gdmaq-log-row-'.$item->status);
    }

    public function rows_per_page() {
        $per_page = get_user_option('gdmaq_rows_log_per_page');

        if (empty($per_page) || $per_page < 1) {
            $per_page = 25;
        }

        return $per_page;
    }

    public function get_columns() {
        return array(
            'cb' => '<input type="checkbox" />',
            'log_id' => __("ID", "gd-mail-queue"),
            'email' => __("Email", "gd-mail-queue"),
            'basic' => __("Basic", "gd-mail-queue"),
            'from' => __("From", "gd-mail-queue"),
            'to' => __("To", "gd-mail-queue"),
            'status' => __("Status", "gd-mail-queue"),
            'logged' => __("Logged", "gd-mail-queue")
        );
    }

    protected function get_sortable_columns() {
        return array(
            'log_id' => array('l.id', false),
            'logged' => array('l.logged', false)
        );
    }

    protected function get_bulk_actions() {
        $bulk = array(
            'delete' => __("Delete entry", "gd-mail-queue")
        );

        if (gdmaq_settings()->get('action_retry', 'log')) {
            $bulk['retry'] = __("Retry sending", "gd-mail-queue");
        }

        return $bulk;
    }

    protected function column_default($item, $column_name){
        $value = isset($item->$column_name) ? $item->$column_name : '';

        return apply_filters('gdmaq_admin_grid_log_column_value', $value, $column_name, $item);
    }

    protected function column_log_id($item) {
        return $item->id;
    }

    protected function column_email($item) {
	    $render = '<div class="gdmaq-subject">'.esc_html($item->subject).'</div>';

        if ($item->type != 'mail' && isset(gdmaq_mailer()->detection()->supported_types[$item->type])) {
            $type = gdmaq_mailer()->detection()->supported_types[$item->type];

            $render.= '<div class="gdmaq-type"><strong>'.__("Source", "gd-mail-queue").'</strong> - '.$type.'</div>';
        }

        $actions = array(
            'delete' => '<a class="gdmaq-log-delete gdmaq-action-delete-entry" href="'.$this->_self('log_id='.$item->id.'&single-action=delete', true, $item->id, 'delete').'">'.__("Delete", "gd-mail-queue").'</a>',
            'details' => '<a class="gdmaq-log-details gdmaq-action-view-entry" href="#" data-log="'.esc_attr($item->id).'" data-nonce="'.esc_attr(wp_create_nonce('gdrts-log-view-'.$item->id)).'">'.__("View Log Entry", "gd-mail-queue").'</a>',
        );

        if ($item->status == 'fail' && gdmaq_settings()->get('action_retry', 'log')) {
            $actions['retry'] = '<a class="gdmaq-log-retry gdmaq-action-retry-entry" href="'.$this->_self('log_id='.$item->id.'&single-action=retry', true, $item->id, 'retry').'">'.__("Retry", "gd-mail-queue").'</a>';
        }

        return $render.$this->row_actions($actions);
    }

    protected function column_basic($item) {
        $render = '';

        $_has_html = $_has_plain = false;

        if (isset($item->extras->ContentType)) {
            if ($item->extras->ContentType == 'multipart/alternative' || $item->extras->ContentType == 'text/html') {
                $_has_html = true;
            }
        }

        if (!$_has_html || $_has_html && !empty($item->plain)) {
            $_has_plain = true;
        }

        if ($_has_plain) {
            $render.= $this->_render_tag('plain', __("Plain Text", "gd-mail-queue"));
        }

        if ($_has_html) {
            $render.= $this->_render_tag('html', __("HTML", "gd-mail-queue"));
        }

        $render.= $this->_render_tag('attachments', sprintf(__("Attachments: %s", "gd-mail-queue"), '<strong>'.count($item->attachments).'</strong>'));

        return $render;
    }

    protected function column_from($item) {
        $from = $this->_get_emails($item->id, 'from');
        $reply_to = $this->_get_emails($item->id, 'reply_to');

        $render = join(", ", $this->_render_emails($from, 'from'));

        if (!empty($reply_to)) {
            $render.= '<h4>'.__("Reply To", "gd-mail-queue").':</h4>';
            $render.= join(", ", $this->_render_emails($reply_to, 'reply_to'));
        }

        return $render;
    }

    protected function column_to($item) {
        $to = $this->_get_emails($item->id, 'to');
        $cc = $this->_get_emails($item->id, 'cc');
        $bcc = $this->_get_emails($item->id, 'bcc');

        $render = join(", ", $this->_render_emails($to, 'to'));

        if (!empty($cc)) {
            $render.= '<h4>'.__("CC", "gd-mail-queue").':</h4>';
            $render.= join(", ", $this->_render_emails($cc, 'cc'));
        }

        if (!empty($bcc)) {
            $render.= '<h4>'.__("BCC", "gd-mail-queue").':</h4>';
            $render.= join(", ", $this->_render_emails($bcc, 'bcc'));
        }

        return $render;
    }

    protected function column_status($item) {
        $render = '';

        if ($item->status !== 'queue') {
            $engine_label = gdmaq()->get_engine_label($item->engine);
            $render.= $this->_render_tag('engine', $engine_label, __("Engine", "gd-mail-queue"));
        }

        if ($item->operation == 'queue') {
            $render.= $this->_render_tag('queue', __("Queue", "gd-mail-queue"));
        } else if ($item->operation == 'mail') {
            $render.= $this->_render_tag('mail', __("WP Mail", "gd-mail-queue"));
        }

        if ($item->status == 'ok') {
            $render.= $this->_render_tag('ok', __("Sent OK", "gd-mail-queue"));
        } else if ($item->status == 'fail') {
            $render.= $this->_render_tag('fail', __("Failed", "gd-mail-queue"), $item->message);
        } else if ($item->status == 'retry') {
            $render.= $this->_render_tag('fail', __("Retried", "gd-mail-queue"), $item->message);
        } else if ($item->status == 'queue') {
            $render.= $this->_render_tag('toqueue', __("To Queue", "gd-mail-queue"));
        }

        return $render;
    }

    protected function column_logged($item) {
        $timestamp = gdmaq()->datetime->timestamp_gmt_to_local(strtotime($item->logged));

        return date('Y-m-d', $timestamp).'<br/>@ '.date('H:i:s', $timestamp);
    }

    public function prepare_items() {
        $this->get_column_info();

        $per_page = $this->rows_per_page();

	    $last = !empty($_GET['filter-period']) ? d4p_sanitize_slug($_GET['filter-period']) : 0;
	    $status = !empty($_GET['filter-status']) ? d4p_sanitize_slug($_GET['filter-status']) : '';
	    $search = !empty($_GET['s']) ? d4p_sanitize_basic(urldecode($_GET['s'])) : '';
	    $type = !empty($_GET['filter-type']) ? d4p_sanitize_slug($_GET['filter-type']) : '';

        $select = "l.*, COUNT(*) AS emails";
        $join = gdmaq_db()->log." l INNER JOIN ".gdmaq_db()->log_email." le ON le.log_id = l.id INNER JOIN ".gdmaq_db()->emails." e ON le.email_id = e.id";
        $where = array("l.`blog_id` = ".get_current_blog_id());

        if ($this->_operation != '') {
            $where[] = "l.`operation` = '".esc_sql($this->_operation)."'";
        }

        if ($type != '' && $type != 'all') {
            $where[] = "l.`type` = '".esc_sql($type)."'";
        }

        if ($status != '' && $status != 'all') {
            $where[] = "l.`status` = '".esc_sql($status)."'";
        }

        if ($last != '' && $last != 'all') {
            if (strlen($last) == 7) {
                $date = explode('-', $last);

                if (count($date) == 2) {
                    $where[] = "YEAR(l.`logged`) = ".intval($date[0]);
                    $where[] = "MONTH(l.`logged`) = ".intval($date[1]);
                }
            } else {
                $date = explode('-', $last);

                if ($date[0] == 'dy') {
                    $last = $date[1] * 24;
                } else if ($date[0] == 'hr') {
                    $last = $date[1];
                }

                if ($last > 0) {
                    $where[] = "l.`logged` > DATE_SUB(NOW(), interval ".$last." hour)";
                }
            }
        }

        if (!empty($search)) {
            $s = array(
                "e.`email` LIKE '%".$search."%'",
                "e.`name` LIKE '%".$search."%'",
                "l.`subject` LIKE '%".$search."%'"
            );

            $where[] = '('.join(' OR ', $s).')';
        }

        $orderby = !empty($_GET['orderby']) ? $this->sanitize_field('orderby', $_GET['orderby'], 'l.id') : 'l.id';
        $order = !empty($_GET['order']) ? $this->sanitize_field('order', $_GET['order'], 'DESC') : 'DESC';

        $paged = !empty($_GET['paged']) ? esc_sql($_GET['paged']) : '';
        if (empty($paged) || !is_numeric($paged) || $paged <= 0 ){
            $paged = 1;
        }

        $offset = intval(($paged - 1) * $per_page);

        $SQL = array('select' => $select,
            'join' => $join,
            'where' => $where,
            'orderby' => $orderby,
            'order' => $order,
            'offset' => $offset,
            'per_page' => $per_page,
            'group' => ' GROUP BY l.id'
        );

        if (!empty($SQL['where'])) {
            $SQL['where'] = ' WHERE '.join(' AND ', $SQL['where']);
        } else {
            $SQL['where'] = '';
        }

        $query = "SELECT SQL_CALC_FOUND_ROWS ".$SQL['select']." FROM ".$SQL['join'].$SQL['where'].$SQL['group'];
        $query.= " ORDER BY ".$SQL['orderby']." ".$SQL['order']." LIMIT ".$SQL['offset'].", ".$SQL['per_page'];

        $this->items = gdmaq_db()->run_and_index($query, 'id');
        $total_rows = gdmaq_db()->get_found_rows();

        $this->set_pagination_args(array(
            'total_items' => $total_rows,
            'total_pages' => ceil($total_rows / $per_page),
            'per_page' => $per_page,
        ));

        foreach ($this->items as &$item) {
            $item->headers = json_decode($item->headers);
            $item->attachments = json_decode($item->attachments);
            $item->extras = json_decode($item->extras);
            $item->mailer = json_decode($item->mailer);
        }

        $log_ids = array_values(wp_list_pluck($this->items, 'id'));
        $this->_emails = gdmaq_db()->email_log_get_emails($log_ids);
    }
}
