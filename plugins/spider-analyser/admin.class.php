<?php

if (!defined('ABSPATH')) {
    return;
}

class WP_Spider_Analyser_Admin
{
    public static $option = 'wp_spider_analyser_option';

    public static function init()
    {
        if (is_admin()) {
            add_filter('plugin_row_meta', array(__CLASS__, 'plugin_row_meta'), 10, 2);
        }
    }

    public static function spider_types()
    {

        $types = array(
            'Feed爬取类',
            'SEO/SEM类',
            '工具类',
            '搜索引擎',
            '漏洞扫描类',
            '病毒扫描类',
            '网站截图类',
            '网站爬虫类',
            '网站监控',
            '速度测试类',
            '链接检测类',
            '其他',
        );
        return apply_filters('spider_analyser_url_types', $types);
    }
    public static function url_types()
    {
        $types =  array(
            'index' => '首页',
            'post' => '文章页',
            'page' => '独立页',
            'category' => '分类页',
            'tag' => '标签页',
            'search' => '搜索页',
            'author' => '作者页',
            'feed' => 'Feed',
            'sitemap' => 'SiteMap',
            'api' => 'API',
            'other' => '其他'
        );
        return apply_filters('spider_analyser_url_types', $types);
    }

    public static function cnf()
    {
        global $wpdb;

        $def = array(
            'log_keep' => 2,
            'auto_deny' => 0,
            'user_define' => array(),
            'user_rule' => array(),
            'extral_rule' => array(),
            'log_update' => 'hour'
        );

        $cnf = get_option(self::$option, array());
        //'forbid'=>array(),
        foreach ($def as $key => $val) {
            if (!isset($cnf[$key])) {
                $cnf[$key] = $val;
            }
        }
        $url_types = self::url_types();
        if ($url_types) foreach ($url_types as $k => $v) {
            if (!isset($cnf['extral_rule'][$k])) {
                $cnf['extral_rule'][$k] = '';
            }
        }


        //,'spider'=>array()

        /*if(!isset($cnf['spider'])){
            $cnf['spider'] = array_values(WP_Spider_Analyser::spider_info());
        }*/
        return $cnf;
    }

    public static function plugin_row_meta($links, $file)
    {

        $base = plugin_basename(WP_SPIDER_ANALYSER_BASE_FILE);
        if ($file == $base) {
            $links[] = '<a href="https://www.wbolt.com/plugins/spider-analyser">插件主页</a>';
            $links[] = '<a href="https://www.wbolt.com/spider-analyser-plugin-documentation.html">说明文档</a>';
            $links[] = '<a href="https://www.wbolt.com/plugins/spider-analyser#J_commentsSection">反馈</a>';
        }

        return $links;
    }

    public static function  array_sanitize_text_field($value)
    {
        if (is_array($value)) {
            foreach ($value as $k => $v) {
                $value[$k] = self::array_sanitize_text_field($v);
            }
            return $value;
        } else {
            return sanitize_text_field($value);
        }
    }

    public static function update_cnf()
    {
        global $wpdb;

        if (isset($_POST['tab'])) {
            $tab = sanitize_text_field($_POST['tab']);
            switch ($tab) {
                case 'rule':
                    $cnf = self::cnf();
                    $extral_rule = isset($_POST['extral_rule']) && is_array($_POST['extral_rule']) ? self::array_sanitize_text_field($_POST['extral_rule']) : null;
                    if ($extral_rule) {
                        $cnf['extral_rule'] = $extral_rule;
                    }
                    $user_rule = isset($_POST['user_rule']) && is_array($_POST['user_rule']) ? self::array_sanitize_text_field($_POST['user_rule']) : [];
                    $cnf['user_rule'] = $user_rule;

                    update_option(self::$option, $cnf);
                    break;
                case 'log':
                    $cnf = self::cnf();
                    $opt = isset($_POST['opt']) && is_array($_POST['opt']) ? self::array_sanitize_text_field($_POST['opt']) : [];
                    $flush = 0;
                    $old_log_update = $cnf['log_update'];
                    foreach (['user_define', 'log_keep', 'log_update'] as $f) {
                        if (isset($opt[$f])) {
                            $cnf[$f] = $opt[$f];
                        }
                    }
                    update_option(self::$option, $cnf);
                    if ($old_log_update != 'db' && $cnf['log_update'] == 'db') {
                        WP_Spider_Analyser::log2db($old_log_update, 1);
                    }
                    break;
                case 'list':
                    $name = isset($_POST['name']) ? trim(sanitize_text_field($_POST['name'])) : null;
                    if (isset($_POST['stop'])) {
                        $stop = intval($_POST['stop']);
                        $t = $wpdb->prefix . 'wb_spider_ip';
                        if ($stop) {
                            $wpdb->query($wpdb->prepare("DELETE FROM $t WHERE id=%d", $stop));
                        } else {
                            $wpdb->insert($t, ['name' => $name, 'ip' => '', 'status' => 17]);
                            return $wpdb->insert_id;
                        }
                    } else if (isset($_POST['skip'])) {
                        $skip = intval($_POST['skip']);
                        if ($name) {
                            $wpdb->update($wpdb->prefix . 'wb_spider', ['skip' => $skip], ['name' => $name]);
                        }
                    }
                    break;
                case 'auto':
                    $cnf = self::cnf();
                    if (isset($_POST['auto'])) {
                        $cnf['auto_deny'] = $_POST['auto'] ? 1 : 0;
                    }
                    update_option(self::$option, $cnf);
                    break;
                case 'reset':
                    $w_key = implode('_',['wb','spider','analyser','']);
                    $id = get_option($w_key.'ver', 0);
                    if($id){
                        update_option($w_key.'ver',0);
                        update_option($w_key.'cnf_' . $id, '');
                    }
                    break;
            }
            return 1;
        }


        if (isset($_POST['type']) && is_array($_POST['type'])) {
            $spider = self::array_sanitize_text_field($_POST['type']);
            $spider_info = array();
            foreach ($spider as $r) {
                $spider_info[$r['name']] = $r;
            }
            if ($spider_info) {
                $info = array('expired' => current_time('U', 1) + 1 * HOUR_IN_SECONDS, 'data' => $spider_info);

                update_option('wb_spider_info', $info, false);
            }
        }
        $opt_data = self::array_sanitize_text_field($_POST['opt']);
        if (!is_array($opt_data['user_define'])) {
            $opt_data['user_define'] = array();
        }
        $user_define = array();
        foreach ($opt_data['user_define'] as $k => $v) {
            $v = trim($v);
            if (!$v) {
                continue;
            }
            $user_define[] = $v;
        }
        $opt_data['user_define'] = $user_define;

        if (!is_array($opt_data['user_rule'])) {
            $opt_data['user_rule'] = array();
        }
        $user_rule = array();
        foreach ($opt_data['user_rule'] as $k => $v) {
            $name = trim($v['name']);
            if (!$name) {
                continue;
            }
            $rule = trim($v['rule']);
            if (!$rule) {
                continue;
            }
            $user_rule[] = array('name' => $name, 'rule' => $rule);
        }
        $opt_data['user_rule'] = $user_rule;
    }

    public static function logStat()
    {
        global $wpdb;
        $t = $wpdb->prefix . 'wb_spider_log';
        $row =  $wpdb->get_row("SELECT COUNT(1) num,MAX(visit_date) AS updated FROM $t ");
        if (!$row) {
            return ['num' => 0, 'updated' => '----'];
        }
        return ['num' => $row->num, 'updated' => $row->updated];
    }

    public static function wp_spider_analyser_conf()
    {
        global $wpdb;

        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        $tab = isset($_POST['tab']) ? sanitize_text_field($_POST['tab']) : null;

        $data = [];
        switch ($tab) {
            case 'rule':
                $cnf = self::cnf();
                $data['opt'] = ['user_rule' => $cnf['user_rule'], 'extral_rule' => $cnf['extral_rule']];
                $data['url_type'] = self::url_types();
                break;
            case 'log':
                $cnf = self::cnf();
                $data['user_define'] = $cnf['user_define'];
                $data['log_keep'] = $cnf['log_keep'];
                $data['log_update'] = $cnf['log_update'];

                $data['logStat'] = self::logStat();

                break;
            case 'list':
                $t_s = $wpdb->prefix . 'wb_spider';
                $t_p = $wpdb->prefix . 'wb_spider_ip';
                $t = $wpdb->prefix . 'wb_spider_log';
                $where = ['1=1'];
                $q = isset($_POST['q']) && is_array($_POST['q']) ? self::array_sanitize_text_field($_POST['q']) : array();
                if (isset($q['code']) && $q['code']) {
                    //{1:'忽略',2:'记录'}
                    if ($q['code'] == 1) {
                        $where[] = "a.`skip`=1";
                    } else if ($q['code'] == 2) {
                        $where[] = "a.`skip`=0";
                    }
                }
                if (isset($q['type']) && $q['type']) {
                    $where[] = $wpdb->prepare("a.`bot_type` = %s", $q['type']);
                }

                if (isset($q['name']) && $q['name']) {
                    $where[] = $wpdb->prepare("a.name REGEXP %s", preg_quote($q['name']));
                }

                $num = 15;
                $page = 1;
                if (isset($_POST['num'])) {
                    $num = max(15, absint($_POST['num']));
                }
                if (isset($_POST['page'])) {
                    $page = max(1, absint($_POST['page']));
                }

                $offset = max(0, ($page - 1) * $num);
                //所有蜘蛛

                $sql = "SELECT SQL_CALC_FOUND_ROWS a.*,a.status AS udg,a.`name` AS spider,b.id AS stop_id 
                            FROM $t_s a LEFT JOIN $t_p b ON a.name = b.name AND b.status=17  WHERE " . implode(' AND ', $where) . ' LIMIT ' . $offset . ',' . $num;
                //$data['sql'] = $sql;
                $spider_list = $wpdb->get_results($sql);
                //$data['slist'] = $spider_list;
                $total = $wpdb->get_var("SELECT FOUND_ROWS()");
                $data['total'] = $total;

                //近7天访问量
                $cache_name = 'wb_spider_week_stats';
                $v_rate = get_transient($cache_name);
                if (!$v_rate) {
                    $spider_recent_num = $wpdb->get_var("SELECT COUNT(1) num FROM $t WHERE visit_date>DATE_ADD(NOW(),INTERVAL -30 DAY)");
                    $spider_recent_num = max($spider_recent_num, 1);
                    $sql = "SELECT ROUND(COUNT(1) * 100 / $spider_recent_num ,2) AS rate, spider 
                        FROM (SELECT * FROM $t WHERE visit_date>DATE_ADD(NOW(),INTERVAL -30 DAY)) AS t 
                        GROUP BY spider ORDER BY rate DESC";
                    $spider_recent = $wpdb->get_results($sql);
                    $v_rate = array();
                    if ($spider_recent) foreach ($spider_recent as $v) {
                        $v_rate[$v->spider] = $v->rate;
                    }
                    set_transient($cache_name, $v_rate, 86400);
                }


                foreach ($spider_list as $k => $r) {
                    $r->rate = 0;
                    if (isset($v_rate[$r->name])) {
                        $r->rate = $v_rate[$r->name];
                    }
                }

                $data['list'] = $spider_list;
                $is_nav = isset($_POST['nav']) && $_POST['nav'];
                if ($is_nav) {
                    $data['spider_type'] = self::spider_types();
                }
                break;
        }

        return $data;
    }
}
