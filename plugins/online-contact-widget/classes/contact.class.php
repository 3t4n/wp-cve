<?php

/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */


class  OCW_Contact
{
    public function __construct()
    {
    }


    public static function init()
    {

        add_action('ocw_new_concat', array(__CLASS__, 'wb_new_concat'));

        if (is_admin()) {
            add_action('admin_menu', function () {
                global $wpdb;
                $t = $wpdb->prefix . 'ocw_contact';
                $val = $wpdb->get_var("select count(1) from $t where status=1 and is_new=1");

                $tips = '';
                if ($val) {
                    $tips = '<span class="awaiting-mod count-' . $val . '"><span class="pending-count" aria-hidden="true">' . $val . '</span></span>';
                }
                add_submenu_page(
                    OCW_Admin::$name,
                    '多合一在线客服插件',
                    '工单管理' . $tips,
                    'administrator',
                    OCW_Admin::$name . '#/wo-list',
                    array(__CLASS__, 'render_views')
                );
            });

            add_action('wp_ajax_ocw_contact', array(__CLASS__, 'wp_ajax_ocw_contact'));
        }
    }

    public static function contact()
    {

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
    }


    public static function wb_new_concat($pid)
    {
        global $wpdb;

        $conf = self::conf();

        if (!$conf['auto_reply_on']) {
            return;
        }

        $msg = $conf['auto_reply_msg'] ? $conf['auto_reply_msg'] : $conf['auto_reply_default'];
        $t_detail = $wpdb->prefix . 'ocw_contact_content';
        $d = array(
            'pid' => $pid,
            'content' => $msg,
            'pics' => '',
            'ip' => '0.0.0.0',
            'create_date' => current_time('mysql'),
            'uid' => 0,
        );
        $wpdb->insert($t_detail, $d);
    }


    public static function wp_ajax_ocw_contact()
    {
        if (!current_user_can('manage_options')) {
            exit();
        }
        if (!is_user_logged_in()) {
            echo 'fail';
            exit();
        }
        //ini_set('display_errors',true);
        $op = isset($_POST['op']) ? trim(sanitize_text_field($_POST['op'])) : '';
        switch ($op) {

            case 'set_close':
                self::set_close();
                break;

            case 'delete':
                self::delete();
                break;

            case 'processed':
                self::set_processed();
                break;

            case 'reply':
                self::ask_reply();
                break;

            case 'get_cnf':
                $ret = array('code' => 0, 'desc' => 'success');
                $ret['data'] = self::conf();

                header('content-type:text/json;');
                echo json_encode($ret);
                break;

            case 'get_list':
                self::get_contact_list();
                break;

            case 'get_detail':
                $ret = array('code' => 0, 'list' => array(), 'row' => array(), 'desc' => 'success');

                if (isset($_POST['id']) && $_POST['id']) {
                    $id = intval($_POST['id']);
                    $data = self::get_detail($id, true);
                    $row = $data['row'];
                    $type = $row->type;
                    $row->type = OCW_Admin::opt('items_data.msg.subject_type')[$type];

                    $ret['list'] = $data['list'];
                    $ret['row'] = $row;
                }

                $ret['cnf'] = self::conf();

                header('content-type:text/json;charset=utf-8');
                echo json_encode($ret);
                break;
        }
        exit();
    }

    public static function delete()
    {
        global $wpdb;
        $t = $wpdb->prefix . 'ocw_contact';
        if (isset($_POST['id']) && $_POST['id']) {
            $id = intval($_POST['id']);
            $wpdb->delete($t, array('id' => $id));
            $wpdb->delete($t . '_content', array('pid' => $id));
        }

    }

    public static function set_close()
    {
        global $wpdb;
        $t = $wpdb->prefix . 'ocw_contact';
        $t_detail = $wpdb->prefix . 'ocw_contact_content';
        $pid = intval(isset($_POST['id']) ? $_POST['id'] : 0);
        if (!$pid) {
            return;
        }
        $user = wp_get_current_user();
        $d = array(
            'pid' => $pid,
            'content' => '关闭工单',
            'pics' => '',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'create_date' => current_time('mysql'),
            'uid' => $user->ID,
        );

        $ret = $wpdb->insert($t_detail, $d);

        $wpdb->update($t, array('update_time' => current_time('mysql'), 'is_read' => 1, 'is_new' => 0, 'status' => 2), array('id' => $pid));
    }

    public static function set_processed()
    {
        global $wpdb;
        $t = $wpdb->prefix . 'ocw_contact';
        $pid = intval(isset($_POST['id']) ? $_POST['id'] : 0);
        if (!$pid) {
            return;
        }

        $wpdb->update($t, array('update_time' => current_time('mysql'), 'is_read' => 1, 'is_new' => 0, 'status' => 3), array('id' => $pid));
    }

    public static function ask_reply()
    {
        global $wpdb;

        $user = wp_get_current_user();
        $t = $wpdb->prefix . 'ocw_contact';
        $t_detail = $wpdb->prefix . 'ocw_contact_content';

        $pid = intval(isset($_POST['id']) ? $_POST['id'] : 0);
        $content = isset($_POST['content']) ? trim(sanitize_textarea_field($_POST['content'])) : '';

        $s_pics = array();
        if (isset($_POST['pics']) && $_POST['pics']) {

            if (is_array($_POST['pics'])) {
                $s_pics = $_POST['pics'];
            } else {
                $s_pics = explode(',', $_POST['pics']);
            }
        }

        $pics = $s_pics;


        $d = array(
            'pid' => $pid,
            'content' => substr($content, 0, 1000),
            'pics' => $pics ? json_encode($pics) : '',
            'ip' => $_SERVER['REMOTE_ADDR'],
            'create_date' => current_time('mysql'),
            'uid' => $user->ID,
        );

        $wpdb->insert($t_detail, $d);

        $d['cid'] = $wpdb->insert_id;

        if ($d['cid']) {
            $wpdb->update($t, array('update_time' => current_time('mysql'), 'is_read' => 1, 'is_new' => 0), array('id' => $pid));
        }


        do_action('ocw_contact_reply', $pid, $d);

        header('content-type:text/json;');
        echo json_encode($d);
        exit();
    }

    static function wb_is_administrator($user_id)
    {
        if (!$user_id) {
            return 1;
        }
        if ($user_id == -1) {
            return 0; //未登陆用户
        }

        $user = get_userdata($user_id);
        if (!empty($user->roles) && in_array('administrator', $user->roles)) {
            return 1;  // 是管理员
        } else {
            return 0;  // 非管理员
        }
    }

    /**
     * 工单列表
     */
    public static function get_contact_list()
    {

        if (!current_user_can('manage_options')) {
            $ret = array('code' => 0, 'desc' => '403', 'total' => 0, 'data' => [], 'num' => 0);
            header('content-type:text/json;charset=utf-8');
            echo json_encode($ret);
            exit();
        }

        /// $param = ['pagesize' => 5];
        //		$cur_page_url = admin_url().'admin.php?page='.$_REQUEST['page'];

        global $wpdb;

        $get = $_POST;
        foreach ($get as $k => $v) {
            if (is_string($v)) $get[$k] = trim(sanitize_text_field($v));
        }
        $num = 30;
        if (isset($get['num']) && $get['num']) {
            $num = intval($get['num']);
        }
        if ($num < 1) {
            $num = 30;
        }
        $page = 1;
        if (isset($get['page']) && $get['page']) {
            $page = intval($get['page']);
        }
        if ($page < 1) {
            $page = 1;
        }

        $limit = " LIMIT " . (($page - 1) * $num) . ", $num";

        $t = $wpdb->prefix . 'ocw_contact';

        //`uid`, `expired`, `status`, `blance`, `consume`
        $sql = "SELECT SQL_CALC_FOUND_ROWS a.*,IFNULL(c.display_name,'网友') display_name,IFNULL(c.user_login,'网友') user_login FROM $t a  LEFT JOIN $wpdb->users c ON a.uid=c.ID WHERE a.status<9 ";

        if (isset($get['fromdate']) && $get['fromdate']) {
            $sql .= $wpdb->prepare(" AND a.create_date >=%s", $get['fromdate'] . ' 00:00:00');
        }
        if (isset($get['todate']) && $get['todate']) {
            $sql .= $wpdb->prepare(" AND a.create_date<=%s", $get['todate'] . ' 23:59:59');
        }
        if (isset($get['is_new']) && $get['is_new']) {
            $sql .= $wpdb->prepare(" AND a.is_new = %d", ($get['is_new'] - 1));
        }

        if (isset($get['type']) && $get['type'] > -1) {

            $sql .= $wpdb->prepare(" AND a.type = %s", $get['type']);
        }

        if (isset($get['status']) && $get['status']) {
            $sql .= $wpdb->prepare(" AND a.status = %d", $get['status']);
        }

        if (isset($get['q']) && $get['q']) {
            $q = trim($get['q']);
            $sql .= $wpdb->prepare(" AND concat_ws('',c.user_login,c.user_email,c.display_name,a.title,a.name,a.email,a.sn) like %s", '%' . $q . '%');
        }

        $sort_by = 'a.update_time';
        if (isset($get['orderby']) && in_array($get['orderby'], ['create_date', 'update_time'])) {
            $sort_by = ' a.' . $get['orderby'];
        }
        if (isset($get['order']) && in_array($get['order'], ['desc', 'asc'])) {
            $sort_by .= ' ' . strtoupper($get['order']);
        } else {
            $sort_by .= ' DESC';
        }
        $sql .= " ORDER BY " . $sort_by . ' ' . $limit;

        $list = $wpdb->get_results($sql);
        $total = $wpdb->get_var("SELECT FOUND_ROWS()");

        foreach ($list as $item) {
            $item->last_update_user = self::last_name($item->id);
            $item->msg = self::get_detail($item->id);
        }

        $ret = array('code' => 0, 'desc' => 'success');

        $ret['total'] = intval($total);
        $ret['num'] = $num;
        $ret['data'] = $list;

        header('content-type:text/json;charset=utf-8');
        echo json_encode($ret);
        exit();
    }


    /**
     * 工单详情
     */
    public static function get_detail($id, $get_row = false)
    {

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        global $wpdb;
        $t = $wpdb->prefix . 'ocw_contact';
        $t_detail = $wpdb->prefix . 'ocw_contact_content';


        $row = $wpdb->get_row($wpdb->prepare("SELECT a.* FROM $t a  WHERE a.id=%d", $id));


        $sql = $wpdb->prepare("SELECT a.content,IFNULL(b.display_name,'system') display_name from $t_detail a LEFT JOIN $wpdb->users b ON a.uid=b.ID WHERE a.pid=%d ORDER BY a.cid ASC ", $id);
        //echo $sql;
        $list = $wpdb->get_results($sql);

        if ($get_row) {
            return array(
                'row' => $row,
                'list' => $list
            );
        }

        return $list;
    }

    public static function avatar_url($uid)
    {
        static $src_list = array();
        $src = wb_assets_url('img') . '/images/def_avatar.png';

        if (!$uid) {
            return $src;
        }
        if (isset($src_list[$uid])) {
            return $src_list[$uid];
        }


        $img_html = get_avatar($uid, 96, $src);

        if (preg_match('#src=([^\s]+)#i', $img_html, $match)) {
            $src = trim($match[1], "\"'");
        }
        $src_list[$uid] = $src;
        return $src;
    }

    public static function auto_close()
    {
        global $wpdb;
        $t = $wpdb->prefix . 'ocw_contact_content';
        $t2 = $wpdb->prefix . 'ocw_contact';

        $cap_key = $wpdb->prefix . 'capabilities';

        $col = $wpdb->get_col("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='$cap_key' AND meta_value REGEXP 'administrator'");

        if (empty($col)) {
            return;
        }

        $sql = "SELECT MAX(cid) AS cid FROM $t a,$t2 b WHERE a.pid=b.id AND b.status=1 GROUP BY a.pid";

        $uid = implode(',', $col);
        $list = $wpdb->get_results("SELECT * FROM $t WHERE uid IN($uid) AND DATEDIFF(NOW(),create_date) > 7 AND cid IN($sql) ");

        if ($list) foreach ($list as $r) {
            $d = array(
                'pid' => $r->pid,
                'content' => '您的工单长时间未反馈信息，系统自动关闭此工单，如需继续联系，请重新发起工单。',
                'pics' => '',
                'ip' => '0.0.0.0',
                'create_date' => current_time('mysql'),
                'uid' => 0,
            );

            $ret = $wpdb->insert($t, $d);

            if ($ret) {

                $wpdb->update($t2, array('update_time' => current_time('mysql'), 'is_read' => 1, 'is_new' => 0, 'status' => 2), array('id' => $r->pid));
            }
        }
    }

    public static function last_name($pid)
    {
        global $wpdb;


        $t = $wpdb->prefix . 'ocw_contact_content';

        $row = $wpdb->get_row($wpdb->prepare("SELECT a.*,b.display_name FROM $t a LEFT  JOIN $wpdb->users b ON a.uid=b.ID WHERE  a.pid=%d ORDER BY a.cid DESC LIMIT 1", $pid));

        if ($row && $row->display_name) {
            return $row->display_name;
        }

        return '未登录访客';
    }

    /**
     * 获取设置值
     */
    public static function conf()
    {
        return OCW_Admin::opt('items_data.msg.subject_type');
    }


    public static function limit($pagesize)
    {
        $paged = isset($_POST['paged']) ? abs($_POST['paged']) : 1;
        $_POST['paged'] = $paged = $paged ? $paged : 1;

        $pagesize = $pagesize ? abs($pagesize) : 10;

        return 'LIMIT ' . (($paged - 1) * $pagesize) . ',' . $pagesize;
    }
}
