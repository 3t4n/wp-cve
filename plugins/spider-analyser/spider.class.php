<?php


if (!defined('ABSPATH')) {
    return;
}



class WP_Spider_Analyser
{

    public static $in_log = false;
    public static $debug = false;
    public static $blocked = false;
    public static $after_request = false;


    public static function init()
    {

        add_action('parse_request', array(__CLASS__, 'parse_request'), 1);

        add_action('admin_menu', array(__CLASS__, 'adminMenu'));
        add_action('edit_post', array(__CLASS__, 'spider_edit_post'), 500, 2);
        add_filter('plugin_action_links', array(__CLASS__, 'actionLinks'), 10, 2);
        register_shutdown_function(array(__CLASS__, 'handle'));

        add_filter('redirect_canonical', function ($redirect_url, $requested_url) {
            if (!self::$in_log && $redirect_url) {
                self::$in_log = true;
                self::log(302);
            }
            return $redirect_url;
        }, 10, 2);


        //
        add_action('wp_wb_spider_analyser_cron', array(__CLASS__, 'wp_wb_spider_analyser_cron'));

        if (!wp_next_scheduled('wp_wb_spider_analyser_cron')) {
            wp_schedule_event(strtotime(current_time('Y-m-d H:i:00', 1)), 'hourly', 'wp_wb_spider_analyser_cron');
        }

        register_activation_hook(WP_SPIDER_ANALYSER_BASE_FILE, array(__CLASS__, 'plugin_activate'));
        register_deactivation_hook(WP_SPIDER_ANALYSER_BASE_FILE, array(__CLASS__, 'plugin_deactivate'));



        WP_Spider_Analyser_Admin::init();

        add_action('wp_ajax_spider_analyser', array(__CLASS__, 'spider_analyser_ajax'));

        add_action('admin_enqueue_scripts', array(__CLASS__, 'admin_enqueue_scripts'), 1);
        add_action('admin_notices', array(__CLASS__, 'admin_notices'));
        self::upgrade();
    }


    public static function parse_request()
    {
        global $wpdb;

        if (!get_option('wb_spider_analyser_ver', 0)) {
            self::$after_request = true;
            return;
        }

        $ip = self::getIp();
        $t = $wpdb->prefix . 'wb_spider_ip';

        //self::txt_log('parse_request');


        $spider = self::spider();
        //self::txt_log('spider '.$spider);
        if (!$spider) {
            return;
        }

        $match = false;

        if ($ip) {
            $ips = explode('.', $ip);
            array_pop($ips);
            $ip3 = implode('.', $ips);
            $sql = "SELECT * FROM $t WHERE (status=4 OR status>10) AND (ip = '' OR ip LIKE %s) AND (name='' OR name = %s) GROUP BY CONCAT_WS('',ip,name) ";

            $list = $wpdb->get_results($wpdb->prepare($sql, $ip3 . '.%', $spider));

            if ($list) foreach ($list as $r) {
                $match = true;
                if ($r->name) { //match name
                    //check ip not match
                    if ($r->ip && $r->ip != $ip3 . '.*' && $ip != $r->ip) {
                        $match = false;
                    }
                } else { //only ip
                    //check ip not match
                    if ($r->ip && $r->ip != $ip3 . '.*' && $ip != $r->ip) {
                        $match = false;
                    }
                }
                if ($match) {
                    break;
                }
            }
        } else {
            $sql = "SELECT * FROM $t WHERE (status=4 OR status>10) AND name = %s GROUP BY name";

            $list = $wpdb->get_results($wpdb->prepare($sql, $spider));
            if ($list) {
                $match = true;
            }
        }

        self::$after_request = true;
        if ($match) {
            self::$blocked = true;
            wp_die('Blocked Spider Access!', 'IP Blocked', array('response' => 403));
            exit();
        }
    }


    public static function admin_notices()
    {
        global $current_screen;
        if (!current_user_can('update_plugins')) {
            return;
        }
        if (!preg_match('#spider_analyser#', $current_screen->parent_base)) {
            return;
        }
        $current         = get_site_transient('update_plugins');
        if (!$current) {
            return;
        }
        $plugin_file = plugin_basename(WP_SPIDER_ANALYSER_BASE_FILE);
        if (!isset($current->response[$plugin_file])) {
            return;
        }
        $all_plugins     = get_plugins();
        if (!$all_plugins || !isset($all_plugins[$plugin_file])) {
            return;
        }
        $plugin_data = $all_plugins[$plugin_file];
        $update = $current->response[$plugin_file];

        //print_r($update);
        $update_url = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $plugin_file, 'upgrade-plugin_' . $plugin_file);

        $html = '<div class="update-message notice inline notice-warning notice-alt"><p>' . $plugin_data['Name'] . '有新版本可用。';
        $html .= '<a href="' . $update->url . '" target="_blank" aria-label="查看' . $plugin_data['Name'] . '版本' . $update->new_version . '详情">查看版本' . $update->new_version . '详情</a>';
        $html .= '或<a href="' . $update_url . '" class="update-link" aria-label="现在更新 ' . $plugin_data['Name'] . '">现在更新</a>。</p></div>';
        echo $html;
    }

    public static function vue_assets()
    {

        $assets = include __DIR__ . '/plugins_assets.php';

        if (!$assets || !is_array($assets)) {
            return;
        }

        $wp_styles = wp_styles();
        if (isset($assets['css']) && is_array($assets['css'])) foreach ($assets['css'] as $r) {
            $wp_styles->add($r['handle'], WP_SPIDER_ANALYSER_URL . $r['src'], $r['dep'], null, $r['args']);
            $wp_styles->enqueue($r['handle']); //.'?v=1'
        }
        if (isset($assets['js']) && is_array($assets['js'])) foreach ($assets['js'] as $r) {
            if (!$r['src'] && $r['in_line']) {
                wp_register_script($r['handle'], false, $r['dep'], false, true);
                wp_enqueue_script($r['handle']);
                wp_add_inline_script($r['handle'], $r['in_line'], 'after');
            } else if ($r['src']) {
                wp_enqueue_script($r['handle'], WP_SPIDER_ANALYSER_URL . $r['src'], $r['dep'], null, true);
            }
        }
    }

    public static function admin_enqueue_scripts($hook)
    {


        if (!preg_match('#wp_spider_analyser#', $hook)) {
            return;
        }

        wp_register_script('wbs-inline-js', false, null, false);
        wp_enqueue_script('wbs-inline-js');

        $wb_cnf = array(
            'home_url' => home_url(),
            'base_url' => admin_url(),
            'ajax_url' => admin_url('admin-ajax.php'),
            'dir_url' => WP_SPIDER_ANALYSER_URL,
            'pd_code' => "spider-analyser",
            'pd_title' => 'Spider Analyser-蜘蛛分析插件',
            'pd_version' => WP_SPIDER_ANALYSER_VERSION,
            'is_pro' => intval(get_option('wb_spider_analyser_ver', 0)),
            'action' => array(
                'act' => 'spider_analyser',
                'fetch' => 'get_setting',
                'push' => 'set_setting'
            )
        );

        $options = self::cnf();

        $wb_ajax_nonce = wp_create_nonce('wp_ajax_wb_spider_analyser');

        $spider_auto = isset($options['auto_deny']) && $options['auto_deny'] ? 1 : 0;

        $inline_script = 'var _wb_spider_analyser_ajax_nonce = "' . $wb_ajax_nonce . '",wb_spider_auto = ' . $spider_auto . ',
		    wb_cnf=' . json_encode($wb_cnf) . ';window.wb_vue_path="' . WP_SPIDER_ANALYSER_URL . 'tpl/";' . "\n";

        wp_add_inline_script('wbs-inline-js', $inline_script, 'before');

        add_filter('style_loader_tag', function ($tag, $handle, $href, $media) {
            if (!preg_match('#^vue-#', $media)) {
                return $tag;
            }

            $media = htmlspecialchars_decode($media);
            $r = [];
            parse_str(str_replace('vue-', '', $media), $r);
            $rel = '';
            $attr = [];
            if ($r && is_array($r)) {
                if (isset($r['rel'])) {
                    $rel = $r['rel'];
                    unset($r['rel']);
                }
                foreach ($r as $attr_k => $attr_v) {
                    $attr[] = sprintf('%s="%s"', $attr_k, esc_attr($attr_v));
                }
            }

            $tag = sprintf(
                '<link href="%s" rel="%s" %s/>' . "\n",
                $href,
                $rel,
                implode(" ", $attr)
            );
            return $tag;
        }, 10, 4);
        add_filter('script_loader_tag', function ($tag, $handle, $src) {
            if (!preg_match('#-vue-js-#', $handle)) {
                return $tag;
            }
            $parts = explode('?', $src, 2);
            $src = $parts[0];
            $type = '';
            $attr = '';
            if (isset($parts[1])) {
                $r = [];
                parse_str(htmlspecialchars_decode($parts[1]), $r);
                //print_r($r);
                if ($r) {
                    if (isset($r['type'])) {
                        $type = sprintf(' type="%s"', esc_attr($r['type']));
                        unset($r['type']);
                    }
                    $attr_txt = '';
                    if (isset($r['attr'])) {
                        $attr_txt = $r['attr'];
                        unset($r['attr']);
                    }
                    foreach ($r as $k => $v) {
                        $attr .= sprintf(' %s="%s"', $k, esc_attr($v));
                    }
                    if ($attr_txt) {
                        $attr .= sprintf(' %s', esc_attr($attr_txt));
                    }
                }
            }
            //print_r([$handle,$src]);

            $tag = sprintf('<script%s src="%s"%s id="%s-js"></script>' . "\n", $type, $src, $attr, $handle);
            return $tag;
        }, 10, 3);

        self::vue_assets();
    }


    public static function match_type($url, &$query = null)
    {
        global $wp_filter;
        self::txt_log('match type fun');
        $cnf = self::cnf();

        self::txt_log($cnf);

        $type = null;
        $old_page = null;
        $php_self = null;
        $request_uri = null;

        $reset_url = false;

        do {
            if ($cnf['extral_rule']) foreach ($cnf['extral_rule'] as $r_type => $rule) {
                if (!$rule) {
                    continue;
                }
                $rule = str_replace(array(',', '\\*'), array('|', '.+?'), preg_quote($rule));
                if (preg_match('#(' . $rule . ')#i', $url)) {
                    $type = $r_type;
                    break;
                }
            }
            if ($type) {
                break;
            }

            //['index','post','page','category','tag','search','author','feed','sitemap','api','other'];
            if ($cnf['user_rule']) foreach ($cnf['user_rule'] as $r) {
                if (!$r['rule']) {
                    continue;
                }
                $rule = str_replace(array(',', '\\*'), array('|', '.+?'), preg_quote($r['rule']));
                if (preg_match('#' . $rule . '#i', $url)) {
                    $type = $r['name'];
                    break;
                }
            }
            if ($type) {
                break;
            }

            if (preg_match('#/wp-admin/admin-ajax\.php#', $url)) {
                $type = 'api';
                break;
            } else if (preg_match('#^/sitemap(-[a-z0-9_-]+)?\.xml#i', $url)) {
                $type = 'sitemap';
                break;
            }
            $parse = parse_url($url);
            if (isset($parse['query']) && $parse['query']) {
                parse_str($parse['query'], $param);
                if (isset($param['s'])) {
                    $type = 'search';
                    break;
                }
            }
            if (!$parse['path'] || $parse['path'] == '/') {
                $type = 'index';
                break;
            }
            //if(preg_match('#sitemap#'))
            $request_uri = $_SERVER['REQUEST_URI'];
            $php_self = $_SERVER['PHP_SELF'];
            $path = $parse['path'];
            if (preg_match('#/?$#', $parse['path'])) {
                $path = trim($parse['path'], '/') . '/index.php';
            }

            self::txt_log('new wp');



            $wp = new WP();
            $_SERVER['REQUEST_URI'] = $url;
            $_SERVER['PHP_SELF'] = '/index.php';
            $old_page = isset($_GET['page']) ? sanitize_text_field($_GET['page']) : null;
            if ($old_page === null) {
            } else {
                unset($_GET['page']);
            }
            $reset_url = true;
            self::txt_log('wp parse request');
            /*ini_set('display_errors',true);
            ini_set('error_reporting',E_ALL);
            $old_filter = isset($wp_filter['parse_request'])?$wp_filter['parse_request']:null;
            remove_all_filters('parse_request');
            $wp->parse_request($url);
            if($old_filter){
                $wp_filter['parse_request'] = $old_filter;
            }*/

            $wp->query_vars = self::url_help($url);

            self::txt_log('wp build query string');
            $wp->build_query_string();
            self::txt_log('wp query_vars');
            self::txt_log($wp->query_vars);

            $old_filter = isset($wp_filter['parse_query']) ? $wp_filter['parse_query'] : null;
            remove_all_filters('parse_query');

            $wp_query = new WP_Query();
            $wp_query->parse_query($wp->query_vars);

            if ($old_filter) {
                $wp_filter['parse_query'] = $old_filter;
            }

            self::txt_log('wp_query query_vars');
            self::txt_log($wp_query->query_vars);

            if ($wp_query->is_author) {
                $type = 'author';
                break;
            }
            if ($wp_query->is_tag) {
                $type = 'tag';
                break;
            }
            if ($wp_query->is_feed) {
                $type = 'feed';
                break;
            }
            if ($wp_query->is_archive) {
                $type = 'category';
                break;
            }


            if ($wp_query->is_singular) {
                //$wp_query->query();
                //print_r($wp_query->get_posts());
                $posts = $wp_query->get_posts();
                if ($posts) {
                    if ($posts[0] instanceof WP_Post) {
                        $query = $posts[0];
                    }
                    if ($posts[0]->post_type == 'page') {
                        $type = 'page';
                        break;
                    }
                }

                $type = 'post';
                break;
            }




            $type = 'other';
        } while (0);

        if ($reset_url) {
            if ($old_page === null) {
            } else {
                $_GET['page'] = $old_page;
            }
            //print_r($wp);
            //print_r($wp_query);
            $_SERVER['PHP_SELF'] = $php_self;
            $_SERVER['REQUEST_URI'] = $request_uri;
        }

        return $type;
    }

    public static function url_help($req_url)
    {
        global $wp_rewrite, $wp;
        $private_query_vars = $wp->private_query_vars;
        $public_query_vars = $wp->public_query_vars;
        $query_vars     = array();
        $post_type_query_vars = array();
        $extra_query_vars = array();


        if ($req_url) {
            parse_str($req_url, $extra_query_vars);
        }

        // Fetch the rewrite rules.
        $rewrite = $wp_rewrite->wp_rewrite_rules();



        if (!empty($rewrite)) {
            // If we match a rewrite rule, this will be cleared.
            $error               = '404';

            $pathinfo         = isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : '';
            list($pathinfo) = explode('?', $pathinfo);
            $pathinfo         = str_replace('%', '%25', $pathinfo);

            list($req_uri) = explode('?', $_SERVER['REQUEST_URI']);
            $self            = $_SERVER['PHP_SELF'];
            $home_path       = trim(parse_url(home_url(), PHP_URL_PATH), '/');
            $home_path_regex = sprintf('|^%s|i', preg_quote($home_path, '|'));

            /*
             * Trim path info from the end and the leading home path from the front.
             * For path info requests, this leaves us with the requesting filename, if any.
             * For 404 requests, this leaves us with the requested permalink.
             */
            $req_uri  = str_replace($pathinfo, '', $req_uri);
            $req_uri  = trim($req_uri, '/');
            $req_uri  = preg_replace($home_path_regex, '', $req_uri);
            $req_uri  = trim($req_uri, '/');
            $pathinfo = trim($pathinfo, '/');
            $pathinfo = preg_replace($home_path_regex, '', $pathinfo);
            $pathinfo = trim($pathinfo, '/');
            $self     = trim($self, '/');
            $self     = preg_replace($home_path_regex, '', $self);
            $self     = trim($self, '/');

            // The requested permalink is in $pathinfo for path info requests and
            // $req_uri for other requests.
            if (!empty($pathinfo) && !preg_match('|^.*' . $wp_rewrite->index . '$|', $pathinfo)) {
                $requested_path = $pathinfo;
            } else {
                // If the request uri is the index, blank it out so that we don't try to match it against a rule.
                if ($req_uri == $wp_rewrite->index) {
                    $req_uri = '';
                }
                $requested_path = $req_uri;
            }
            $requested_file = $req_uri;


            // Look for matches.
            $request_match = $requested_path;
            if (empty($request_match)) {
                // An empty request could only match against ^$ regex.
                if (isset($rewrite['$'])) {
                    $matched_rule = '$';
                    $query              = $rewrite['$'];
                    $matches            = array('');
                }
            } else {
                foreach ((array) $rewrite as $match => $query) {
                    // If the requested file is the anchor of the match, prepend it to the path info.
                    if (!empty($requested_file) && strpos($match, $requested_file) === 0 && $requested_file != $requested_path) {
                        $request_match = $requested_file . '/' . $requested_path;
                    }

                    if (
                        preg_match("#^$match#", $request_match, $matches) ||
                        preg_match("#^$match#", urldecode($request_match), $matches)
                    ) {

                        if ($wp_rewrite->use_verbose_page_rules && preg_match('/pagename=\$matches\[([0-9]+)\]/', $query, $varmatch)) {
                            // This is a verbose page match, let's check to be sure about it.
                            $page = get_page_by_path($matches[$varmatch[1]]);
                            if (!$page) {
                                continue;
                            }

                            $post_status_obj = get_post_status_object($page->post_status);
                            if (
                                !$post_status_obj->public && !$post_status_obj->protected
                                && !$post_status_obj->private && $post_status_obj->exclude_from_search
                            ) {
                                continue;
                            }
                        }

                        // Got a match.
                        $matched_rule = $match;
                        break;
                    }
                }
            }


            if (isset($matched_rule)) {
                // Trim the query of everything up to the '?'.
                $query = preg_replace('!^.+\?!', '', $query);

                // Substitute the substring matches into the query.
                $query = addslashes(WP_MatchesMapRegex::apply($query, $matches));



                // Parse the query.
                parse_str($query, $perma_query_vars);

                // If we're processing a 404 request, clear the error var since we found something.
                if ('404' == $error) {
                    unset($error, $_GET['error']);
                }
            }

            // If req_uri is empty or if it is a request for ourself, unset error.
            if (empty($requested_path) || $requested_file == $self || strpos($_SERVER['PHP_SELF'], 'wp-admin/') !== false) {
                unset($error, $_GET['error']);

                if (isset($perma_query_vars) && strpos($_SERVER['PHP_SELF'], 'wp-admin/') !== false) {
                    unset($perma_query_vars);
                }
            }
        }

        /**
         * Filters the query variables allowed before processing.
         *
         * Allows (publicly allowed) query vars to be added, removed, or changed prior
         * to executing the query. Needed to allow custom rewrite rules using your own arguments
         * to work, or any other custom query variables you want to be publicly available.
         *
         * @since 1.5.0
         *
         * @param string[] $public_query_vars The array of allowed query variable names.
         */
        $public_query_vars = apply_filters('query_vars', $public_query_vars);

        foreach (get_post_types(array(), 'objects') as $post_type => $t) {
            if (is_post_type_viewable($t) && $t->query_var) {
                $post_type_query_vars[$t->query_var] = $post_type;
            }
        }

        foreach ($public_query_vars as $wpvar) {
            if (isset($extra_query_vars[$wpvar])) {
                $query_vars[$wpvar] = $extra_query_vars[$wpvar];
            } elseif (isset($_GET[$wpvar]) && isset($_POST[$wpvar]) && $_GET[$wpvar] !== $_POST[$wpvar]) {
                wp_die(__('A variable mismatch has been detected.'), __('Sorry, you are not allowed to view this item.'), 400);
            } elseif (isset($_POST[$wpvar])) {
                $query_vars[$wpvar] = $_POST[$wpvar];
            } elseif (isset($_GET[$wpvar])) {
                $query_vars[$wpvar] = $_GET[$wpvar];
            } elseif (isset($perma_query_vars[$wpvar])) {
                $query_vars[$wpvar] = $perma_query_vars[$wpvar];
            }

            if (!empty($query_vars[$wpvar])) {
                if (!is_array($query_vars[$wpvar])) {
                    $query_vars[$wpvar] = (string) $query_vars[$wpvar];
                } else {
                    foreach ($query_vars[$wpvar] as $vkey => $v) {
                        if (is_scalar($v)) {
                            $query_vars[$wpvar][$vkey] = (string) $v;
                        }
                    }
                }

                if (isset($post_type_query_vars[$wpvar])) {
                    $query_vars['post_type'] = $post_type_query_vars[$wpvar];
                    $query_vars['name']      = $query_vars[$wpvar];
                }
            }
        }

        // Convert urldecoded spaces back into '+'.
        foreach (get_taxonomies(array(), 'objects') as $taxonomy => $t) {
            if ($t->query_var && isset($query_vars[$t->query_var])) {
                $query_vars[$t->query_var] = str_replace(' ', '+', $query_vars[$t->query_var]);
            }
        }

        // Don't allow non-publicly queryable taxonomies to be queried from the front end.
        if (!is_admin()) {
            foreach (get_taxonomies(array('publicly_queryable' => false), 'objects') as $taxonomy => $t) {
                /*
                 * Disallow when set to the 'taxonomy' query var.
                 * Non-publicly queryable taxonomies cannot register custom query vars. See register_taxonomy().
                 */
                if (isset($query_vars['taxonomy']) && $taxonomy === $query_vars['taxonomy']) {
                    unset($query_vars['taxonomy'], $query_vars['term']);
                }
            }
        }

        // Limit publicly queried post_types to those that are 'publicly_queryable'.
        if (isset($query_vars['post_type'])) {
            $queryable_post_types = get_post_types(array('publicly_queryable' => true));
            if (!is_array($query_vars['post_type'])) {
                if (!in_array($query_vars['post_type'], $queryable_post_types, true)) {
                    unset($query_vars['post_type']);
                }
            } else {
                $query_vars['post_type'] = array_intersect($query_vars['post_type'], $queryable_post_types);
            }
        }

        // Resolve conflicts between posts with numeric slugs and date archive queries.
        $query_vars = wp_resolve_numeric_slug_conflicts($query_vars);

        foreach ((array) $private_query_vars as $var) {
            if (isset($extra_query_vars[$var])) {
                $query_vars[$var] = $extra_query_vars[$var];
            }
        }

        if (isset($error)) {
            $query_vars['error'] = $error;
        }

        return $query_vars;
    }

    public static function chart_data($day, $type, $compare = 0, $spider = null)
    {
        global $wpdb;

        $time = strtotime(current_time('mysql'));
        if ($day) {
            $time = $time - 86400 * $day;
        }

        if ($compare) {
            $time = $time - 86400 * ($day > 0 ? $day : 1);
        }
        $ymd = date('Y-m-d', $time);
        $t = $wpdb->prefix . 'wb_spider_log';

        if ($day > 2) {
            //group by h
            $format = '%m/%d';
            $op = '>=';

            $xdata = [];
            for ($i = 0; $i < $day; $i++) {
                $xdata[] = date('m/d', $time + $i * 86400);
            }
        } else {
            $format = '%H:00-%H:59';
            $op = '=';
            $xdata = [];

            for ($i = 0; $i < 24; $i++) {
                $xdata[] = $i < 10 ? ('0' . $i . ':00-0' . $i . ':59') : ('' . $i . ':00-' . $i . ':59');
            }
        }
        $filed_more = '';
        $group_more = '';
        $where_more = '';
        if ($type == 3) {
            $filed_more = ',code';
            $group_more = ',code';
            $where_more = ' AND code IN(200,301,302,404)';
        }

        if ($spider) {
            $where_more = $wpdb->prepare(" AND spider = %s", $spider);
        }

        $sql = "SELECT COUNT(1) num,COUNT(DISTINCT spider) spider,DATE_FORMAT(visit_date,'$format') ymd $filed_more FROM (SELECT * FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd' $where_more) AS a GROUP BY ymd $group_more ORDER BY ymd";
        $list = $wpdb->get_results($sql);
        $tmp = [];
        foreach ($list as $r) {
            if ($type == 2) {
                $tmp[$r->ymd] = $r->num;
            } else if ($type == 3) {
                //$tmp[$r->ymd] = $r->spider > 0 ? ceil($r->num/$r->spider) : 0;
                if (!isset($tmp[$r->ymd])) {
                    $tmp[$r->ymd] = [];
                }
                $code = in_array($r->code, ['301', '302']) ? '301/302' : $r->code;
                $tmp[$r->ymd][$code] = isset($tmp[$r->ymd][$code]) ?  $tmp[$r->ymd][$code] + $r->num : $r->num;
            } else {
                $tmp[$r->ymd] = $r->spider;
            }
        }

        $ydata = [];
        $codes = ['200', '301/302', '404'];
        $empty = 0;
        if ($type == 3) {
            $empty = [];
            foreach ($codes as $c) {
                $ydata[$c] = [];
                $empty[$c] = 0;
            }
        }

        foreach ($xdata as $v) {
            if ($type == 3) {
                $val = isset($tmp[$v]) ? $tmp[$v] : $empty;
                foreach ($codes as $c) {
                    $ydata[$c][] = isset($val[$c]) ? $val[$c] : 0;
                }
            } else {
                $ydata[] = isset($tmp[$v]) ? $tmp[$v] : $empty;
            }
        }


        return [$xdata, $ydata];
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

    public static function spider_analyser_ajax()
    {
        global $wpdb;

        $op = isset($_POST['op']) ? sanitize_text_field($_POST['op']) : (isset($_GET['op']) ? sanitize_text_field($_GET['op']) : null);


        switch ($op) {
            case 'chk_ver':
                $http = wp_remote_get('https://www.wbolt.com/wb-api/v1/themes/checkver?code=spider-analyser&ver=' . WP_SPIDER_ANALYSER_VERSION . '&chk=1', array('sslverify' => false, 'headers' => array('referer' => home_url()),));

                if (wp_remote_retrieve_response_code($http) == 200) {
                    echo wp_remote_retrieve_body($http);
                }

                exit();
                break;
            case 'promote':
                header('content-type:text/json;charset=utf-8');
                $ret = ['code' => 0, 'desc' => 'success', 'data' => ''];
                $data = [];
                $expired = 0;
                $update_cache = false;
                do {
                    $option = get_option('wb_spider_analyser_promote', null);
                    do {
                        if (!$option || !is_array($option)) {
                            break;
                        }

                        if (!isset($option['expired']) || empty($option['expired'])) {
                            break;
                        }

                        $expired = intval($option['expired']);
                        if ($expired < current_time('U')) {
                            $expired = 0;
                            break;
                        }

                        if (!isset($option['data']) || empty($option['data'])) {
                            break;
                        }

                        $data = $option['data'];
                    } while (0);

                    if ($data) {
                        $ret['data'] = $data;
                        break;
                    }
                    if ($expired) {
                        break;
                    }

                    $update_cache = true;
                    $param = ['c' => 'spider-analyser', 'h' => $_SERVER['HTTP_HOST']];
                    $http = wp_remote_post('https://www.wbolt.com/wb-api/v1/promote', array('sslverify' => false, 'body' => $param, 'headers' => array('referer' => home_url()),));

                    if (is_wp_error($http)) {
                        $ret['error'] = $http->get_error_message();
                        break;
                    }
                    if (wp_remote_retrieve_response_code($http) !== 200) {
                        $ret['error-code'] = '201';
                        break;
                    }
                    $body = trim(wp_remote_retrieve_body($http));
                    if (!$body) {
                        $ret['empty'] = 1;
                        break;
                    }
                    $data = json_decode($body, true);
                    if (!$data) {
                        $ret['json-error'] = 1;
                        $ret['body'] = $body;
                        break;
                    }
                    //data = [title=>'',image=>'','expired'=>'2021-05-12','url=>'']
                    $ret['data'] = $data;
                    if (isset($data['expired']) && $data['expired'] && preg_match('#^\d{4}-\d{2}-\d{2}$#', $data['expired'])) {
                        $expired = strtotime($data['expired'] . ' 23:50:00');
                    }
                } while (0);
                if ($update_cache) {
                    if (!$expired) {
                        $expired = current_time('U') + 21600;
                    }
                    update_option('wb_spider_analyser_promote', ['data' => $ret['data'], 'expired' => $expired], false);
                }


                echo json_encode($ret);
                exit();
                break;
            case 'chart_data':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $spider = isset($_POST['spider']) ? trim(sanitize_text_field($_POST['spider'])) : '';
                    $day = isset($_POST['day']) ? absint($_POST['day']) : 0;
                    $type = isset($_POST['type']) ? absint($_POST['type']) : 1;

                    $cache_param = ['op' => 'chart_data', 'day' => $day, 'type' => $type, 'spider' => $spider];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $data = self::chart_data($day, $type, 0, $spider);
                    //$compare_day = $day>0?$day * 2 : 1;
                    $compare = [];
                    if ($type != 3) {
                        $compare = self::chart_data($day, $type, 1, $spider);
                    }


                    $ret = array(
                        //'sql'=>$sql,
                        'code' => 0,
                        'data' => $data,
                        'compare' => $compare,
                    );

                    self::cache($cache_param, $ret, 3600); //60*60

                } while (0);



                echo json_encode($ret);

                exit();
                break;

            case 'top_url':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $day = isset($_POST['day']) ? absint($_POST['day']) : 0;
                    $cache_param = ['op' => 'top_url', 'day' => $day];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $time = strtotime(current_time('mysql'));
                    if ($day) {
                        $time = $time - 86400 * $day;
                    }
                    $ymd = date('Y-m-d', $time);
                    $t = $wpdb->prefix . 'wb_spider_log';
                    $op = '=';
                    if ($day > 1) {
                        $op = '>=';
                    }

                    $total = $wpdb->get_var("SELECT COUNT(1) total FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd'");

                    $sql = "SELECT COUNT(1) num,url FROM (SELECT * FROM  $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd') AS a GROUP BY url_md5 ORDER BY num DESC LIMIT 10";

                    $list = $wpdb->get_results($sql);
                    $data = [];

                    foreach ($list as $r) {
                        $r->rate = round($r->num / $total * 100, 2);
                        $data[] = $r;
                    }

                    $ret = array(
                        //'sql'=>$sql,
                        'code' => 0,
                        'data' => $data,
                    );

                    self::cache($cache_param, $ret, 3600);
                } while (0);



                echo json_encode($ret);

                exit();
                break;

            case 'top_post':
                $ret = array('code' => 0, 'desc' => 'success');
                $ret['data'] = array();
                header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'top_spider':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }

                    $day = isset($_POST['day']) ? absint($_POST['day']) : 0;
                    $cache_param = ['op' => 'top_spider', 'day' => $day];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $time = strtotime(current_time('mysql'));
                    if ($day) {
                        $time = $time - 86400 * $day;
                    }
                    $ymd = date('Y-m-d', $time);
                    $t2 = $wpdb->prefix . 'wb_spider';
                    $t = $wpdb->prefix . 'wb_spider_log';
                    $op = '=';
                    if ($day > 1) {
                        $op = '>=';
                    }
                    $total = $wpdb->get_var("SELECT COUNT(1) total FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd'");

                    //LEFT JOIN $t2 b ON a.spider=b.name
                    $sql = "SELECT COUNT(1) num,a.spider,1 AS udg FROM (SELECT  * FROM $t  WHERE DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd') AS a GROUP BY a.spider ORDER BY num DESC LIMIT 10";

                    $list = $wpdb->get_results($sql);
                    $data = [];

                    foreach ($list as $r) {
                        $r->rate = round($r->num / $total * 100, 2);
                        $data[] = $r;
                    }

                    $ret = array(
                        //'sql'=>$sql,
                        'code' => 0,
                        'data' => $data,
                    );
                    self::cache($cache_param, $ret, 3600);
                } while (0);


                echo json_encode($ret);

                exit();
                break;

            case 'summary':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }

                    $cache_param = ['op' => 'summary'];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $ymd = current_time('Y-m-d');
                    $t = $wpdb->prefix . 'wb_spider_log';
                    //蜘蛛数
                    $data = [['spider' => 0, 'url' => 0, 'avg_url' => 0], ['spider' => 0, 'url' => 0, 'avg_url' => 0], ['spider' => 0, 'url' => 0, 'avg_url' => 0]];


                    $row = $wpdb->get_row("SELECT COUNT(1) url,COUNT(DISTINCT spider) spider FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d')='$ymd' ");

                    if ($row) {
                        $data[0]['spider'] = $row->spider;
                        $data[0]['url'] = $row->url;
                        $data[0]['avg_url'] = $row->spider > 0 ? ceil($row->url / $row->spider) : 0;
                    }
                    $ymd = date('Y-m-d', strtotime(current_time('mysql')) - 86400);

                    $row = $wpdb->get_row("SELECT COUNT(1) url,COUNT(DISTINCT spider) spider FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d')='$ymd' ");

                    if ($row) {
                        $data[1]['spider'] = $row->spider;
                        $data[1]['url'] = $row->url;
                        $data[1]['avg_url'] = $row->spider > 0 ? ceil($row->url / $row->spider) : 0;
                    }

                    $ymd = date('Y-m-d', strtotime(current_time('mysql')) - 86400 * 30);
                    $row = $wpdb->get_row("SELECT COUNT(1) url FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d')>='$ymd' ");
                    if ($row) {
                        $data[2]['url'] = ceil($row->url / 30);
                    }
                    $row2 = $wpdb->get_row("SELECT SUM(num) spider FROM (SELECT COUNT(DISTINCT  spider) num,DATE_FORMAT(visit_date,'%Y-%m-%d') ymd FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d')>='$ymd' GROUP BY ymd) as tmp ");
                    if ($row2) {
                        $data[2]['spider'] = ceil($row2->spider / 30);
                    }

                    $data[2]['avg_url'] = $data[2]['spider'] > 0 ? ceil($data[2]['url'] / $data[2]['spider']) : 0;



                    $ret = array(
                        //'sql'=>$sql,
                        'code' => 0,
                        'data' => $data,
                    );

                    self::cache($cache_param, $ret, 3600);
                } while (0);


                echo json_encode($ret);

                exit();

                break;

            case 'list':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){
                        $ret['desc'] = 'fail';
                        break;
                    }
                    if (!current_user_can('manage_options')) {
                        $ret['desc'] = 'fail';
                        break;
                    }
                    if (isset($_POST['skip']) && $_POST['skip']) {

                        if (is_array($_POST['skip'])) {
                            $skips = self::array_sanitize_text_field($_POST['skip']);
                            foreach ($skips as $skip) {
                                self::skip_spider($skip);
                                self::delete_log(['spider' => $skip]);
                            }
                        } else {
                            $spider = trim(sanitize_text_field($_POST['skip']));
                            self::skip_spider($spider);
                            self::delete_log(['spider' => $spider]);
                            //$cnf = self::cnf();
                            /*if(!in_array($spider,$cnf['forbid'])){
                                array_push($cnf['forbid'],$spider);
                                $key = WP_Spider_Analyser_Admin::$option;
                                update_option($key,$cnf);
                                self::delete_log(['spider'=>$spider]);
                            }*/
                        }

                        break;
                    }


                    //$spider_info = self::spider_info();

                    $q = isset($_POST['q']) && is_array($_POST['q']) ? self::array_sanitize_text_field($_POST['q']) : array();

                    $day = isset($q['day']) ? intval($q['day']) : -1;
                    $t2 = $wpdb->prefix . 'wb_spider';
                    $t = $wpdb->prefix . 'wb_spider_log';
                    $where = array();
                    $total_where = array();
                    if ($day > -1) {
                        $time = strtotime(current_time('mysql'));
                        if ($day) {
                            $time = $time - 86400 * $day;
                        }
                        $ymd = date('Y-m-d', $time);

                        $op = '=';
                        if ($day > 1) {
                            $op = '>=';
                        }

                        $where[] = "DATE_FORMAT(a.visit_date,'%Y-%m-%d') $op '$ymd'";
                        $total_where[] = "DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd'";
                    }
                    if (isset($q['code']) && $q['code']) {
                        $where[] = $wpdb->prepare("a.code=%s", $q['code']);
                    }
                    /*if(isset($q['spider']) && $q['spider']){
                        $where[] = $wpdb->prepare("spider=%s",$q['spider']);
                    }
                    */

                    /*if(isset($q['type']) && $q['type']){

                        $a = array();
                        foreach($spider_info as $sk=>$sy){
                            if($sy['bot_type'] == $q['type']){
                                $a[] = $wpdb->prepare('%s',$sk);
                            }

                        }
                        if($a){
                            $where[] = "spider IN (".implode(',',$a).")";
                        }
                    }*/
                    if (isset($q['spider']) && $q['spider']) {
                        $where[] = $wpdb->prepare("a.spider = %s", $q['spider']);
                    }
                    if (isset($q['name']) && $q['name']) {
                        $where[] = $wpdb->prepare("a.spider REGEXP %s", preg_quote($q['name']));
                    }
                    $num = 100;
                    if (isset($_POST['num'])) {
                        $num = max(10, absint($_POST['num']));
                    }
                    if (isset($_POST['page'])) {
                        $page = absint($_POST['page']);
                    }

                    $offset = max(0, ($page - 1) * $num);

                    if ($where) {
                        $where = implode(' AND ', $where);
                    } else {
                        $where = '1=1';
                    }

                    if ($total_where) {
                        $total_where = implode(' AND ', $total_where);
                    } else {
                        $total_where = '1=1';
                    }

                    $order_by = 'num';
                    if (isset($_POST['sort']) && $_POST['sort']) {
                        $sort = sanitize_text_field($_POST['sort']);
                        if ($sort == 'type') {
                            $order_by = 'a.spider';
                        } else if (in_array($sort, ['num', 'last_visit', 'spider'])) {
                            if ($sort == 'num') {
                                $order_by = $sort;
                            } else {
                                $order_by = 'a.' . $sort;
                            }
                        }
                    }
                    $sort_order = isset($_POST['order']) ? sanitize_text_field($_POST['order']) : '';
                    $order_by .=  $sort_order == 'asc' ? ' ASC' : ' DESC';

                    $cache_param = ['list', $where, $order_by, $total_where];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $total = $wpdb->get_var("SELECT COUNT(1) total FROM $t WHERE $total_where");

                    $sql = "SELECT a.spider,COUNT(1) num,MAX(a.visit_date) last_visit,b.bot_type,b.bot_url,b.status AS udg FROM $t a LEFT JOIN $t2 b ON a.spider=b.name WHERE $where GROUP BY a.spider ORDER BY $order_by ";
                    $list = $wpdb->get_results($sql);
                    $not_found = array();
                    foreach ($list as $r) {
                        $r->rate = round($r->num / $total * 100, 2);
                        /*if(isset($spider_info[$r->spider])){
                            $info = $spider_info[$r->spider];
                            $r->type = $info['bot_type'];
                            $r->url = $info['bot_url'];
                        }else{
                            $r->type = '';
                            $r->url = '';
                            $not_found[] = $r->spider;
                        }*/

                        //$data[] = $r;
                    }
                    if ($not_found) {
                        //self::update_spider($not_found);
                    }

                    $ret = array(
                        //'sql'=>$sql,
                        'num' => $num,
                        'total' => count($list),
                        'code' => 0,
                        'data' => $list,
                    );
                    self::cache($cache_param, $ret, 3600);
                } while (0);

                // header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'log':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $q = isset($_POST['q']) && is_array($_POST['q']) ? self::array_sanitize_text_field($_POST['q']) : array();
                    $day = isset($q['day']) ? intval($q['day']) : -1;
                    $t = $wpdb->prefix . 'wb_spider_log';
                    $t2 = $wpdb->prefix . 'wb_spider';
                    $where = array();
                    if ($day > -1) {
                        $time = strtotime(current_time('mysql'));
                        if ($day) {
                            $time = $time - 86400 * $day;
                        }
                        $ymd = date('Y-m-d', $time);

                        $op = '=';
                        if ($day > 1) {
                            $op = '>=';
                        }

                        $where[] = "DATE_FORMAT(a.visit_date,'%Y-%m-%d') $op '$ymd'";
                    }

                    if (isset($q['spider']) && $q['spider']) {
                        $where[] = $wpdb->prepare("a.spider=%s", $q['spider']);
                    }
                    if (isset($q['code']) && $q['code']) {
                        if ($q['code'] == '301/302') {
                            $where[] = "(a.code='301' OR a.code='302')";
                        } else {
                            $where[] = $wpdb->prepare("a.code=%s", $q['code']);
                        }
                    }
                    if (isset($q['url']) && $q['url']) {
                        $where[] = $wpdb->prepare("a.url REGEXP %s", preg_quote($q['url']));
                    }
                    if (isset($q['ip']) && $q['ip']) {
                        $where[] = $wpdb->prepare("a.visit_ip REGEXP %s", preg_quote($q['ip']));
                    }
                    $num = 50;
                    if (isset($_POST['num'])) {
                        $num = max(10, absint($_POST['num']));
                    }
                    if (isset($_POST['page'])) {
                        $page = absint($_POST['page']);
                    }

                    $offset = max(0, ($page - 1) * $num);

                    if ($where) {
                        $where = implode(' AND ', $where);
                    } else {
                        $where = '1=1';
                    }

                    $cache_param = ['log', $where, $offset, $num];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $sql = "SELECT SQL_CALC_FOUND_ROWS a.*,b.status AS udg FROM $t a left join $t2 b on a.spider=b.name WHERE $where ORDER BY a.id DESC LIMIT $offset,$num";
                    $list = $wpdb->get_results($sql);

                    $total = $wpdb->get_var("SELECT FOUND_ROWS()");
                    $ret = array(
                        //'sql'=>$sql,
                        'num' => $num,
                        'total' => $total,
                        'code' => 0,
                        'data' => $list,
                    );
                    self::cache($cache_param, $ret, 3600);
                } while (0);

                // header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'log_cnf':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $cache_param = ['log_cnf'];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $ret['data'] = self::spider_log();
                    self::cache($cache_param, $ret, 3600);
                } while (0);

                //header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'path_cnf':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success');
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $cache_param = ['path_cnf'];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }
                    $ret['data'] = self::spider_path();
                    self::cache($cache_param, $ret, 3600);
                } while (0);

                //header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'path':
                header('content-type:text/json;charset=utf-8');
                $ret = array('code' => 0, 'desc' => 'success', 'data' => []);
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $q = isset($_POST['q']) && is_array($_POST['q']) ? self::array_sanitize_text_field($_POST['q']) : array();
                    $day = isset($q['day']) ? intval($q['day']) : -1;
                    $t = $wpdb->prefix . 'wb_spider_log';
                    $where = array();
                    if ($day > -1) {
                        $time = strtotime(current_time('mysql'));
                        if ($day) {
                            $time = $time - 86400 * $day;
                        }
                        $ymd = date('Y-m-d', $time);

                        $op = '=';
                        if ($day > 1) {
                            $op = '>=';
                        }

                        $where[] = "DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd'";
                    }
                    $is_chart = isset($_POST['chart']) ? sanitize_text_field($_POST['chart']) : null;
                    if ($is_chart) {
                        if ($where) {
                            $where = implode(' AND ', $where);
                        } else {
                            $where = '1=1';
                        }
                        $cache_param = ['path', $is_chart, $where];
                        $cache_file = self::cache($cache_param);
                        if ($cache_file) {
                            include $cache_file;
                        }


                        $sql = "SELECT url_type,COUNT(1) num FROM (SELECT * FROM $t WHERE $where) AS a GROUP  BY url_type ";
                        $list = $wpdb->get_results($sql);


                        $url_types = WP_Spider_Analyser_Admin::url_types();
                        $cnf = self::cnf();
                        if ($cnf['user_rule']) foreach ($cnf['user_rule'] as $r) {
                            $url_types[$r['name']] = $r['name'];
                        }
                        $data = [];
                        foreach ($url_types as $k => $v) {
                            $data[$k] = ['value' => 0, 'name' => $v];
                        }
                        foreach ($list as $r) {
                            $data[$r->url_type]['value'] = $r->num;
                        }


                        $ret['data'] = array_values($data);
                        self::cache($cache_param, $ret, 3600);
                        break;
                    }



                    if (isset($q['spider']) && $q['spider']) {
                        $where[] = $wpdb->prepare("spider=%s", $q['spider']);
                    }
                    if (isset($q['code']) && $q['code']) {
                        $where[] = $wpdb->prepare("code=%s", $q['code']);
                    }
                    if (isset($q['url']) && $q['url']) {
                        $where[] = $wpdb->prepare("url REGEXP %s", preg_quote($q['url']));
                    }
                    if (isset($q['ip']) && $q['ip']) {
                        $where[] = $wpdb->prepare("visit_ip REGEXP %s", preg_quote($q['ip']));
                    }
                    if (isset($q['type']) && $q['type']) {
                        $where[] = $wpdb->prepare("url_type=%s", $q['type']);
                    }
                    $num = 50;
                    if (isset($_POST['num'])) {
                        $num = max(10, absint($_POST['num']));
                    }
                    if (isset($_POST['page'])) {
                        $page = absint($_POST['page']);
                    }

                    $offset = max(0, ($page - 1) * $num);

                    if ($where) {
                        $where = implode(' AND ', $where);
                    } else {
                        $where = '1=1';
                    }

                    $order_by = 'num';

                    if (isset($_POST['sort'])) {
                        $sort = sanitize_text_field($_POST['sort']);
                        if (in_array($sort, ['num', 'url_type', 'url'])) {
                            $order_by = $sort;
                        }
                    }
                    $sort_by = 'desc';
                    if (isset($_POST['order'])) {
                        $sort_by = sanitize_text_field($_POST['order']);
                    }
                    $order_by .=  $sort_by == 'asc' ? ' ASC' : ' DESC';


                    $cache_param = ['path', $where, $order_by, $offset, $num];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $sum = $wpdb->get_var("SELECT COUNT(1) num FROM $t WHERE $where");

                    $sql = "SELECT SQL_CALC_FOUND_ROWS COUNT(1) num,url,url_type,'' type,ROUND(COUNT(1)/$sum * 100,2) percent 
                                FROM (SELECT * FROM $t WHERE $where ) AS a GROUP BY url_md5 ORDER BY $order_by LIMIT $offset,$num";

                    $list = $wpdb->get_results($sql);

                    $total = $wpdb->get_var("SELECT FOUND_ROWS()");
                    $ret = array(
                        //'sql'=>$sql,
                        'num' => $num,
                        'total' => $total,
                        'code' => 0,
                        'data' => $list,
                    );
                    self::cache($cache_param, $ret, 3600);
                } while (0);

                //header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'clean_log':
                $ret = array('code' => 1, 'desc' => 'fail');
                do{
                    if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){

                        break;
                    }
                    if(!current_user_can('manage_options')){

                        break;
                    }
                    $ret = array('code' => 0, 'desc' => 'success');
                    foreach (array('wb_spider_sum', 'wb_spider_visit', 'wb_spider_log', 'wb_spider_post', 'wb_spider_post_link', 'wb_spider_ip') as $v) {
                        $t = $wpdb->prefix . $v;
                        $wpdb->query("TRUNCATE $t");
                    }
                    self::clear_cache();

                } while (0);




                header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'ip':
                $ret = array('code' => 0, 'desc' => 'success', 'data' => [], 'total' => 0);
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    if (!get_option('wb_spider_analyser_ver', 0)) {
                        break;
                    }
                    $q = isset($_POST['q']) && is_array($_POST['q']) ? self::array_sanitize_text_field($_POST['q']) : array();
                    $day = isset($q['day']) ? intval($q['day']) : -1;
                    $t2 = $wpdb->prefix . 'wb_spider';
                    $t = $wpdb->prefix . 'wb_spider_log';
                    $where = array();
                    if ($day > -1) {
                        $time = strtotime(current_time('mysql'));
                        if ($day) {
                            $time = $time - 86400 * $day;
                        }
                        $ymd = date('Y-m-d', $time);

                        $op = '=';
                        if ($day > 1) {
                            $op = '>=';
                        }

                        $where[] = "DATE_FORMAT(a.visit_date,'%Y-%m-%d') $op '$ymd'";
                    }

                    if (isset($q['spider']) && $q['spider']) {
                        $where[] = $wpdb->prepare("a.spider=%s", $q['spider']);
                    }
                    if (isset($q['name']) && $q['name']) {
                        $where[] = $wpdb->prepare("a.spider REGEXP %s", preg_quote($q['name']));
                    }

                    /*if(isset($q['url']) && $q['url']){
                        $where[] = $wpdb->prepare("url REGEXP %s",preg_quote($q['url']));
                    }

                    if(isset($q['type']) && $q['type']){
                        $where[] = $wpdb->prepare("url_type=%s",$q['type']);
                    }*/
                    $num = 50;
                    if (isset($_POST['num'])) {
                        $num = max(10, absint($_POST['num']));
                    }
                    if (isset($_POST['page'])) {
                        $page = absint($_POST['page']);
                    }

                    $offset = max(0, ($page - 1) * $num);

                    if ($where) {
                        $where = implode(' AND ', $where);
                    } else {
                        $where = '1=1';
                    }

                    $order_by = 'num';
                    if (isset($_POST['sort'])) {
                        $sort = sanitize_text_field($_POST['sort']);
                        if (in_array($sort, ['num'])) {
                            $order_by = $sort;
                        } else if (in_array($sort, ['ip_range', 'spider'])) {
                            $order_by = 'a.' . $sort;
                        }
                    }
                    $sort_by = 'desc';
                    if (isset($_POST['order'])) {
                        $sort_by = sanitize_text_field($_POST['order']);
                    }
                    $order_by .=  $sort_by == 'asc' ? ' ASC' : ' DESC';

                    $cache_param = ['ip', $where, $order_by, $offset, $num];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }
                    $sum = $wpdb->get_var("SELECT COUNT(1) num FROM $t a WHERE $where");

                    $sql = "SELECT SQL_CALC_FOUND_ROWS COUNT(1) num,a.spider,SUBSTRING_INDEX(a.visit_ip,'.',3) ip_range,ROUND(COUNT(1)/$sum * 100,2) percent,b.status AS udg 
                            FROM (SELECT * FROM $t a WHERE $where) AS a LEFT JOIN $t2 b ON a.spider=b.name 
                            GROUP BY a.spider,ip_range ORDER BY $order_by LIMIT $offset,$num";

                    //echo $sql;exit();
                    $list = $wpdb->get_results($sql);

                    $total = $wpdb->get_var("SELECT FOUND_ROWS()");
                    $ret = array(
                        //'sql'=>$sql,
                        'num' => $num,
                        'total' => $total,
                        'code' => 0,
                        'data' => $list,
                    );
                    self::cache($cache_param, $ret, 3600);
                } while (0);

                header('content-type:text/json;');
                echo json_encode($ret);
                exit();
                break;
            case 'stop':
                $t = $wpdb->prefix . 'wb_spider_ip';
                $ret = array('code' => 0, 'desc' => 'success', 'data' => [], 'total' => 0);
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    if (!get_option('wb_spider_analyser_ver', 0)) {
                        break;
                    }
                    if (isset($_POST['add']) && $_POST['add'] && is_array($_POST['add'])) {
                        if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){
                            break;
                        }
                        $add_data = self::array_sanitize_text_field($_POST['add']);
                        list($name, $ip) = $add_data;
                        $cid = 4;
                        if (isset($_POST['cid']) && in_array($_POST['cid'], [11, 12, 13, 14, 15, 16, 17])) {
                            $cid = intval($_POST['cid']);
                        }
                        $wpdb->suppress_errors();
                        if (is_array($ip)) {

                            //$ret['ips'] = $ip;

                            foreach ($ip as $v) {
                                $sql = $wpdb->prepare("INSERT INTO $t(`name`, `ip`, `status`) VALUES(%s, %s, $cid)", $name, $v);
                                if (!$wpdb->query($sql)) {
                                    $sql = $wpdb->prepare("UPDATE $t SET status=$cid WHERE name=%s AND ip=%s", $name, $v);
                                    $wpdb->query($sql);
                                }
                                self::delete_log(['spider' => $name, 'ip' => $v]);
                            }
                        } else if ($ip || $name) {
                            $sql = $wpdb->prepare("INSERT INTO $t(`name`, `ip`, `status`) VALUES(%s, %s, $cid)", $name, $ip);
                            if (!$wpdb->query($sql)) {
                                $sql = $wpdb->prepare("UPDATE $t SET status=$cid WHERE name=%s AND ip=%s", $name, $ip);
                                $wpdb->query($sql);
                            }
                            self::delete_log(['spider' => $name, 'ip' => $ip]);
                        }


                        break;
                    } else if (isset($_POST['removes']) && is_array($_POST['removes'])) {
                        if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){
                            break;
                        }
                        $removes = self::array_sanitize_text_field($_POST['removes']);
                        foreach ($removes as $r) {
                            $wpdb->query("DELETE FROM $t WHERE status=15 AND " . $wpdb->prepare("name=%s AND ip=%s", $r[0], $r[1]));
                            $sql = $wpdb->prepare("UPDATE $t SET status=1 WHERE name=%s AND ip=%s", $r[0], $r[1]);
                            $wpdb->query($sql);
                        }

                        $wpdb->query("DELETE FROM $t WHERE ip = '' AND status=1");
                        $wpdb->query("DELETE FROM $t WHERE ip LIKE '%.*' AND status=1");
                        self::clear_cache();
                        break;
                    } else if (isset($_POST['remove']) && is_array($_POST['remove'])) {
                        if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){
                            break;
                        }
                        $remove = self::array_sanitize_text_field($_POST['remove']);
                        list($name, $ip) =  $remove;
                        if ($name || $ip) {
                            $wpdb->query("DELETE FROM $t WHERE status=15 AND " . $wpdb->prepare("name=%s AND ip=%s", $name, $ip));
                            $sql = $wpdb->prepare("UPDATE $t SET status=1 WHERE name=%s AND ip=%s", $name, $ip);
                            $wpdb->query($sql);
                        }

                        $wpdb->query("DELETE FROM $t WHERE ip = '' AND status=1");
                        $wpdb->query("DELETE FROM $t WHERE ip LIKE '%.*' AND status=1");
                        self::clear_cache();

                        break;
                    } else if (isset($_POST['new']) && is_array($_POST['new'])) {
                        if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){
                            break;
                        }
                        $new_data = self::array_sanitize_text_field($_POST['new']);
                        list($name, $ip) =  $new_data;
                        $cid = 4;
                        if (isset($_POST['cid']) && in_array($_POST['cid'], [11, 12, 13, 14, 15, 16, 17])) {
                            $cid = intval($_POST['cid']);
                        }
                        $wpdb->suppress_errors();
                        if ($ip && is_array($ip)) {
                            //$ret['ips'] = $ip;
                            foreach ($ip as $v) {
                                $sql = $wpdb->prepare("INSERT INTO $t(`name`, `ip`, `status`) VALUES(%s, %s, $cid)", $name, $v);
                                if (!$wpdb->query($sql)) {
                                    $sql = $wpdb->prepare("UPDATE $t SET status=$cid WHERE name=%s AND ip=%s", $name, $v);
                                    $wpdb->query($sql);
                                }
                                self::delete_log(['spider' => $name, 'ip' => $v]);
                            }
                        } else if ($name && is_array($name)) {
                            foreach ($name as $v) {
                                $sql = $wpdb->prepare("INSERT INTO $t(`name`, `ip`, `status`) VALUES(%s, %s, $cid)", $v, $ip);
                                if (!$wpdb->query($sql)) {
                                    $sql = $wpdb->prepare("UPDATE $t SET status=$cid WHERE name=%s AND ip=%s", $v, $ip);
                                    $wpdb->query($sql);
                                }
                                self::delete_log(['spider' => $v, 'ip' => $ip]);
                            }
                        } else if ($ip || $name) {
                            $sql = $wpdb->prepare("INSERT INTO $t(`name`, `ip`, `status`) VALUES(%s, %s, $cid)", $name, $ip);
                            if (!$wpdb->query($sql)) {
                                $sql = $wpdb->prepare("UPDATE $t SET status=$cid WHERE name=%s AND ip=%s", $name, $ip);
                                $wpdb->query($sql);
                            }
                            self::delete_log(['spider' => $name, 'ip' => $ip]);
                        }

                        self::clear_cache();
                        break;
                    }


                    if (isset($_POST['status'])) {
                        $query_status = intval($_POST['status']);
                        if ($query_status == 4) {
                            $where = "(status=4 OR status>10)";
                        } else {
                            $where = "status=" . $query_status;
                        }
                    } else {
                        $where = "(status=4 OR status>10)";
                    }
                    $kw = trim(sanitize_text_field($_POST['kw'] ?? ''));
                    if($kw){
                        $where .= $wpdb->prepare(" AND (`name` LIKE %s OR `ip` LIKE %s)",'%'.$kw.'%', '%'.$kw.'%');
                    }
                    $type = intval($_POST['type'] ?? 0);
                    if($type){//['全部','名称','IP','IP段','名称及IP','自定义']
                        if($type == 5){
                            $where .= $wpdb->prepare(" AND `status`=%d", 15);
                        }else if($type == 1){
                            $where .= " AND `name` <> '' AND (`ip` = '' OR `ip` IS NULL)";
                        }else if($type == 2){
                            $where .= " AND `ip` <> '' AND (`name` = '' OR `name` IS NULL)";
                        }else if($type == 3){
                            $where .= " AND `ip` LIKE '*' AND (`name` = '' OR `name` IS NULL)";
                        }else if($type == 4){
                            $where .= " AND `ip` <> '' AND `name` <> ''";
                        }
                    }
                    $path = intval($_POST['path'] ?? 0);
                    if($path){
                        $where .= $wpdb->prepare(" AND `status`=%d", $path);
                    }


                    $num = 30;
                    if (isset($_POST['num'])) {
                        $num = max(10, absint($_POST['num']));
                    }
                    if (isset($_POST['page'])) {
                        $page = absint($_POST['page']);
                    }

                    $offset = max(0, ($page - 1) * $num);

                    $cache_param = ['stop', $where, $offset, $num];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }
                    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM $t WHERE $where LIMIT $offset,$num";

                    $list = $wpdb->get_results($sql);

                    $total = $wpdb->get_var("SELECT FOUND_ROWS()");
                    $ret = array(
                        //'sql'=>$sql,
                        'num' => $num,
                        'total' => $total,
                        'code' => 0,
                        'data' => $list,
                    );

                    self::cache($cache_param, $ret, 3600);
                } while (0);

                header('content-type:text/json;');
                echo json_encode($ret);
                exit();
                break;

            case 'post':
                $ret = array('code' => 0, 'desc' => 'success', 'data' => [], 'total' => 0);
                do {
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    if (!get_option('wb_spider_analyser_ver', 0)) {
                        break;
                    }
                    $dsb = isset($_POST['dsb']) && $_POST['dsb'] ? 1 : 0;
                    $q = isset($_POST['q']) && is_array($_POST['q']) ? self::array_sanitize_text_field($_POST['q']) : array();
                    $day = isset($q['day']) ? intval($q['day']) : -1;

                    //if($dsb){

                    //}


                    $t = $wpdb->prefix . 'wb_spider_log';
                    $t2 = $wpdb->prefix . 'wb_spider_post';
                    $where = array();
                    $where2 = array();
                    $where[] = "url_type='post'";
                    if ($day > -1) {
                        $time = strtotime(current_time('mysql'));
                        if ($day) {
                            $time = $time - 86400 * $day;
                        }
                        $ymd = date('Y-m-d', $time);

                        $op = '=';
                        if ($day > 1) {
                            $op = '>=';
                        }

                        $where[] = "DATE_FORMAT(visit_date,'%Y-%m-%d') $op '$ymd'";
                    }

                    if (isset($q['spider']) && $q['spider']) {
                        $where[] = $wpdb->prepare("spider=%s", $q['spider']);
                    }
                    if (isset($q['name']) && $q['name']) {
                        $kw = str_replace(home_url('/'), '/', $q['name']);
                        //$where[] = $wpdb->prepare("CONCAT_WS('',a.url,c.post_title) REGEXP %s",preg_quote($kw));
                        $where[] = $wpdb->prepare("url LIKE %s", "%$kw%");
                        $where2[] = $wpdb->prepare("c.post_title LIKE %s", "%$kw%");;
                    }
                    /*if(isset($q['url']) && $q['url']){
                        $where[] = $wpdb->prepare("url REGEXP %s",preg_quote($q['url']));
                    }
                    if(isset($q['ip']) && $q['ip']){
                        $where[] = $wpdb->prepare("visit_ip REGEXP %s",preg_quote($q['ip']));
                    }
                    */
                    if (isset($q['type']) && $q['type']) {
                        $type = $q['type'];
                        if ($q['type'] == 3) {
                            $type = 0;
                        }
                        $where2[] = $wpdb->prepare("b.status=%s", $type);
                    }

                    $num = 50;
                    if (isset($_POST['num'])) {
                        $num = max(10, absint($_POST['num']));
                    }
                    if (isset($_POST['page'])) {
                        $page = absint($_POST['page']);
                    }

                    $offset = max(0, ($page - 1) * $num);

                    if ($where) {
                        $where = implode(' AND ', $where);
                    } else {
                        $where = '1=1';
                    }
                    if ($where2) {
                        $where2 = ' AND ' . implode(' AND ', $where2);
                    } else {
                        $where2 = '';
                    }

                    $order_by = 'num';
                    if (isset($_POST['sort'])) {
                        $sort = $_POST['sort'];
                        if (in_array($sort, ['num', 'url_in', 'url_out', 'post_date'])) {
                            $order_by = $sort;
                        }
                    }
                    $sort_by = 'desc';
                    if (isset($_POST['order'])) {
                        $sort_by = sanitize_text_field($_POST['order']);
                    }
                    $order_by .=  $sort_by == 'asc' ? ' ASC' : ' DESC';

                    /*$oby = 'num DESC';

                    if(isset($q['sort']) && $q['sort']){
                        $oby = $q['sort'].' DESC';
                    }*/
                    $cache_param = ['post', $where, $where2, $order_by, $offset, $num];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $sql = "SELECT SQL_CALC_FOUND_ROWS COUNT(1) num,a.url_md5,a.url,b.url_in,b.url_out,b.status,b.post_id,c.post_title,c.post_date 
                                FROM (SELECT * FROM $t WHERE $where ) AS a,$t2 b,$wpdb->posts c 
                                WHERE a.url_md5=b.url_md5 AND b.post_id=c.ID $where2 ";
                    $sql .= " GROUP BY a.url_md5 ORDER BY $order_by LIMIT $offset,$num";

                    $list = $wpdb->get_results($sql);
                    $total = $wpdb->get_var("SELECT FOUND_ROWS()");
                    foreach ($list as $k => $r) {
                        $list[$k]->post_url = get_permalink($r->post_id);
                        $list[$k]->post_edit_url = get_edit_post_link($r->post_id,'url');
                    }


                    $ret = array(
                        //'sql'=>$sql,
                        'num' => $num,
                        'total' => $total,
                        'code' => 0,
                        'data' => $list,
                    );

                    //if($dsb){
                    self::cache($cache_param, $ret, 3600);
                    //}
                } while (0);




                header('content-type:text/json;');

                echo json_encode($ret);

                exit();
                break;

            case 'clean_all':
                $ret = array('code' => 0, 'desc' => 'fail');
                do {
                    if(!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')){
                        break;
                    }
                    if (!current_user_can('manage_options')) {
                        break;
                    }
                    $t = $wpdb->prefix . 'wb_spider_ip';
                    $wpdb->query("DELETE FROM $t  WHERE (status=4 OR status>10)");
                    $wpdb->query("DELETE FROM $t WHERE ip = '' AND status=1");
                    $wpdb->query("DELETE FROM $t WHERE ip LIKE '%.*' AND status=1");
                    $ret['desc'] = 'success';
                    self::clear_cache();
                } while (0);
                header('content-type:text/json;');
                echo json_encode($ret);
                exit();
                break;

            case 'verify':
                if (!wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')) {

                    echo json_encode(array('code' => 1, 'data' => '非法操作'));
                    exit(0);
                }
                if (!current_user_can('manage_options')) {
                    echo json_encode(array('code' => 1, 'data' => '没有权限'));
                    exit(0);
                }

                $param = array(
                    'code' => sanitize_text_field(trim($_POST['key'])),
                    'host' => sanitize_text_field(trim($_POST['host'])),
                    'ver' => 'spider-analyser',
                );
                $err = '';
                do {
                    $http = wp_remote_post('https://www.wbolt.com/wb-api/v1/verify', array('timeout' => 30, 'sslverify' => false, 'body' => $param, 'headers' => array('referer' => home_url()),));
                    if (is_wp_error($http)) {
                        $err = '校验失败，请稍后再试（错误代码001[' . $http->get_error_message() . '])';
                        break;
                    }

                    if ($http['response']['code'] != 200) {
                        $err = '校验失败，请稍后再试（错误代码001[' . $http['response']['code'] . '])';
                        break;
                    }

                    $body = $http['body'];

                    if (empty($body)) {
                        $err = '发生异常错误，联系<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">技术支持</a>（错误代码 010）';
                        break;
                    }

                    $data = json_decode($body, true);

                    if (empty($data)) {
                        $err = '发生异常错误，联系<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">技术支持</a>（错误代码011）';
                        break;
                    }
                    if (empty($data['data'])) {
                        $err = '校验失败，请稍后再试（错误代码004)';
                        break;
                    }
                    if ($data['code']) {
                        $err_code = $data['data'];
                        switch ($err_code) {
                            case 100:
                            case 101:
                            case 102:
                            case 103:
                                $err = '插件配置参数错误，联系<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">技术支持</a>（错误代码' . $err_code . '）';
                                break;
                            case 200:
                                $err = '输入key无效，请输入正确key（错误代码200）';
                                break;
                            case 201:
                                $err = 'key使用次数超出限制范围（错误代码201）';
                                break;
                            case 202:
                            case 203:
                            case 204:
                                $err = '校验服务器异常，联系<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">技术支持</a>（错误代码' . $err_code . '）';
                                break;
                            default:
                                $err = '发生异常错误，联系<a href="https://www.wbolt.com/?wb=member#/contact" target="_blank">技术支持</a>（错误代码' . $err_code . '）';
                        }

                        break;
                    }

                    update_option('wb_spider_analyser_ver', $data['v'], false);
                    update_option('wb_spider_analyser_cnf_' . $data['v'], $data['data'], false);


                    echo json_encode(array('code' => 0, 'data' => 'success'));
                    exit(0);
                } while (false);
                echo json_encode(array('code' => 1, 'data' => $err));
                exit(0);
                break;
            case 'options':
                if (!current_user_can('manage_options') || !wp_verify_nonce(sanitize_text_field($_GET['_ajax_nonce']), 'wp_ajax_wb_spider_analyser')) {
                    echo json_encode(array('o' => ''));
                    exit(0);
                }

                $ver = get_option('wb_spider_analyser_ver', 0);
                $cnf = '';
                if ($ver) {
                    $cnf = get_option('wb_spider_analyser_cnf_' . $ver, '');
                }
                $list = array('o' => $cnf);
                header('content-type:text/json;charset=utf-8');
                echo json_encode($list);
                exit();
                break;

            case 'update_setting':
                $data = null;
                if (wp_verify_nonce(sanitize_text_field($_POST['_ajax_nonce']), 'wp_ajax_wb_spider_analyser') && current_user_can('manage_options')) {
                    $data = WP_Spider_Analyser_Admin::update_cnf();
                }
                $ret = array('code' => 0, 'desc' => 'success', 'data' => $data);

                header('content-type:text/json;');
                echo json_encode($ret);
                exit();
                break;

            case 'get_setting':

                $ret = array('code' => 0, 'desc' => 'success','data'=>[]);
                if(current_user_can('manage_options')){
                    $ret['data'] = WP_Spider_Analyser_Admin::wp_spider_analyser_conf();
                }


                header('content-type:text/json;');
                echo json_encode($ret);
                exit();
                break;

            case 'down_log':
                if(!current_user_can('manage_options')){
                    exit();
                }
                set_time_limit(0);
                ini_set('memory_limit', '500M');
                $filename = 'spider-log.txt';
                header('Content-Type: application/application/octet-stream	');
                header('Content-Disposition: attachment;filename="' . $filename . '"');
                header('Cache-Control: max-age=0');
                header('Cache-Control: max-age=1');
                header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
                header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
                header('Pragma: public'); // HTTP/1.0
                $fileHandle = fopen('php://output', 'wb+');
                $page = -1;
                $num = 1000;
                $t = $wpdb->prefix . 'wb_spider_log';
                do {
                    $page++;
                    $offset = $num * $page;
                    $list = $wpdb->get_results("SELECT * FROM $t WHERE 1 LIMIT $offset,$num");
                    if (!$list) {
                        break;
                    }
                    foreach ($list as $r) {
                        fwrite($fileHandle, json_encode($r) . "\n");
                    }
                } while (1);

                fclose($fileHandle);
                exit();
                break;
            case 'spider_history':
                $ret = array('code' => 0, 'desc' => 'success');
                $post_id = isset($_POST['post_id']) ? absint($_POST['post_id']) : 0;
                $list = array();
                do {
                    if (!$post_id) {
                        break;
                    }
                    $url = get_permalink($post_id);
                    $url = str_replace(home_url(), '', $url);
                    $url_md5 = md5($url);
                    $limit = '';
                    $cache_param = ['spider_history', $url_md5, $limit];
                    $cache_file = self::cache($cache_param);
                    if ($cache_file) {
                        include $cache_file;
                    }

                    $sql = "SELECT `spider`, `visit_date`, `visit_ip` FROM `{$wpdb->prefix}wb_spider_log` WHERE `url_md5`=%s  ORDER BY visit_date DESC $limit";

                    $ret['data'] = $wpdb->get_results($wpdb->prepare($sql, $url_md5));
                    self::cache($cache_param, $ret, 3600);
                } while (0);


                header('content-type:text/json;');
                echo json_encode($ret);

                exit();

                break;
        }
    }

    public static function delete_log($param)
    {
        global $wpdb;

        $t = $wpdb->prefix . 'wb_spider_log';
        $where = [];
        if (isset($param['spider']) && $param['spider']) {
            $where[] = $wpdb->prepare("spider=%s", $param['spider']);
        }
        if (isset($param['ip']) && $param['ip']) {

            if (strpos($param['ip'], '*') > 0) {
                $where[] = $wpdb->prepare("visit_ip LIKE  %s", str_replace('*', '%', $param['ip']));
            } else {
                $where[] = $wpdb->prepare("visit_ip = %s", $param['ip']);
            }
            $t_ip = $wpdb->prefix . 'wb_spider_ip';
            $wpdb->query("DELETE FROM $t_ip WHERE status=2 AND " . $wpdb->prepare("ip=%s", $param['ip']));
        }
        if ($where) {
            $wpdb->query("DELETE FROM $t WHERE " . implode(' AND ', $where));
        }
        self::clear_cache();
    }

    public static function txt_log($msg, $mod = null)
    {

        if (!self::$debug) {
            return;
        }


        if (is_array($msg) || is_object($msg)) {
            $msg = json_encode($msg);
        }

        if ($mod) {
            $msg = '[' . $mod . '] ' . $msg;
        }
        error_log('[' . current_time('mysql') . '] ' . $msg . "\n", 3, __DIR__ . '/#log/running.log');
    }

    public static function plugin_activate()
    {
        if (!is_dir(__DIR__ . '/#log/')) {
            mkdir(__DIR__ . '/#log/', 0755);
        }
        self::set_up();

        self::upgrade();
    }

    public static function plugin_deactivate()
    {
        wp_clear_scheduled_hook('wb_wp_spider_trace_cron');
        wp_clear_scheduled_hook('wp_wb_spider_analyser_cron');
    }

    public static function wp_wb_spider_analyser_cron()
    {
        $cnf = self::cnf();
        self::txt_log('start do action wp_wb_spider_analyser_cron');

        self::log2db($cnf['log_update'], 0);

        self::check_404();


        if (current_time('H') == '01') {
            self::calc_log(date('Y-m-d', strtotime(current_time('Y-m-d 00:00:00') - 1)));
            self::sync_wb_spider();
        }

        self::calc_log();

        self::del_old_log();

        self::set_url_type();

        if (get_option('wb_spider_analyser_ver', 0)) {
            self::cron_set_spider_post();

            self::scan_post_inner_link();
            self::update_post_url_num();

            self::check_ip();

            self::set_auto_deny();
        }


        self::txt_log('finnish do action wp_wb_spider_analyser_cron');
    }

    public static function log2db($type, $force = 0)
    {
        if ($type == 'db') {
            return;
        }
        if ($type == 'hour') {
            $dir = glob(__DIR__ . '/#log/log-*.txt');
            $match = '#log-' . current_time('dH') . '\.txt$#';
            if ($dir) foreach ($dir as $txt) {
                if (!$force && preg_match($match, $txt)) {
                    continue;
                }
                self::read_txt($txt);
            }
        } else if ($type == 'day') {
            $dir = glob(__DIR__ . '/#log/log-*.txt');
            $match = '#log-' . current_time('d') . '[0-9]{2}\.txt$#';
            if ($dir) foreach ($dir as $txt) {
                if (!$force && preg_match($match, $txt)) {
                    continue;
                }
                self::read_txt($txt);
            }
        }
    }

    public static function read_txt($file)
    {
        global $wpdb;

        $f = fopen($file, 'r');
        if (!$f) {
            return;
        }

        while (!feof($f)) {
            $line = fgets($f);
            if (!$line) {
                break;
            }
            $d = json_decode($line, true);
            //self::txt_log($line);
            $wpdb->insert($wpdb->prefix . 'wb_spider_log', $d);
        }
        fclose($f);
        unlink($file);
    }

    public static function set_auto_deny()
    {
        global $wpdb;
        $cnf = self::cnf();
        if (!isset($cnf['auto_deny']) || !$cnf['auto_deny']) {
            return;
        }

        $t = $wpdb->prefix . 'wb_spider_ip';
        $wpdb->query("UPDATE $t SET status=16 WHERE status = 2");
    }

    public static function check_ip()
    {

        global $wpdb;

        //status[2=>可疑ip,1=>正常，3=>检测中,4=>禁止]
        $t = $wpdb->prefix . 'wb_spider_ip';
        $t_log = $wpdb->prefix . 'wb_spider_log';

        //SELECT DISTINCT visit_ip FROM `wp_wb_spider_log` WHERE
        $sql = "INSERT INTO $t(ip,name) ";
        $sql .= "SELECT DISTINCT a.visit_ip,a.spider FROM $t_log a WHERE a.visit_date > DATE_ADD(a.visit_date,INTERVAL -1 DAY)";
        $sql .= " AND NOT EXISTS(SELECT b.id FROM $t b WHERE b.ip=a.visit_ip AND b.name=a.spider)";

        $wpdb->query($sql);

        $col = $wpdb->get_col("SELECT DISTINCT ip FROM $t WHERE status = 0 LIMIT 1000 ");


        $api = 'https://www.wbolt.com/wb-api/v1/spider/ip';
        $arg = array(
            'timeout'   => 10,
            'sslverify' => false,
            'body' => array('ver' => get_option('wb_spider_analyser_ver', 0), 'host' => $_SERVER['HTTP_HOST'], 'ip' => implode(',', $col)),
            'headers' => array('referer' => home_url()),
        );
        $http = wp_remote_post($api, $arg);
        $body = wp_remote_retrieve_body($http);

        if (is_wp_error($http)) {
            self::txt_log($http->get_error_message());
        }
        self::txt_log($body);
        if ($body && preg_match('#^[0-9,]+$#', trim($body))) {
            $exp = explode(',', $body);
            foreach ($col as $k => $ip) {
                if (isset($exp[$k]) && $exp[$k]) {
                    $wpdb->query($wpdb->prepare("UPDATE $t SET status=%d WHERE ip=%s", $exp[$k], $ip));
                }
            }
        }
    }

    public static function update_post_url_num()
    {
        global $wpdb;

        $prefix = $wpdb->prefix;

        $sql = "UPDATE `{$prefix}wb_spider_post` a,(SELECT COUNT(1) num, post_id FROM `{$prefix}wb_spider_post_link` ";
        $sql .= "WHERE link_url_md5 <> 'e10adc3949ba59abbe56e057f20f883e' GROUP BY post_id ) AS b";
        $sql .= " SET a.url_out = b.num  WHERE a.post_id=b.post_id";
        $wpdb->query($sql);


        $sql = "UPDATE `{$prefix}wb_spider_post` a,(SELECT COUNT(1) num, link_url_md5 FROM `{$prefix}wb_spider_post_link` ";
        $sql .= " WHERE link_url_md5 <> 'e10adc3949ba59abbe56e057f20f883e' GROUP BY link_url_md5 ) AS b ";
        $sql .= " SET a.url_in = b.num  WHERE a.url_md5=b.link_url_md5";

        $wpdb->query($sql);
    }

    public static function scan_post_inner_link()
    {
        global $wpdb;
        $error = $wpdb->suppress_errors();
        $t = $wpdb->prefix . 'wb_spider_post_link';
        $sql = "SELECT * FROM $wpdb->posts p WHERE p.post_status='publish'";
        $sql .= " AND NOT EXISTS (SELECT post_id FROM $t a WHERE a.post_id=p.ID) LIMIT 1000";
        $list = $wpdb->get_results($sql);
        foreach ($list as $r) {
            self::post_inner_link($r);
        }
        $wpdb->suppress_errors($error);
    }

    public static function post_inner_link($post)
    {

        global $wpdb;
        $t = $wpdb->prefix . 'wb_spider_post_link';
        $wpdb->query($wpdb->prepare("DELETE FROM $t WHERE post_id=%d", $post->ID));

        $num = 0;
        if (preg_match_all("#href=('|\")(.+?)('|\")#is", $post->post_content, $match)) {
            //print_r($match[2]);
            foreach ($match[2] as $url) {
                $url = str_replace(home_url('/'), '/', $url);
                if ($url[0] != '/') {
                    continue;
                }
                $query_post = null;
                $type = self::match_type($url, $query_post);
                if ($type != 'post') {
                    continue;
                }
                self::txt_log([$type, $url]);
                $d = ['post_id' => $post->ID, 'link_url_md5' => md5($url), 'link_post_id' => 0];
                if ($query_post) {
                    $d['link_post_id'] = $query_post->ID;
                }

                if ($wpdb->insert($t, $d)) {
                    $num++;
                }
            }
        }
        if (!$num) {
            $d = ['post_id' => $post->ID, 'link_url_md5' => md5('123456'), 'link_post_id' => 0];
            $wpdb->insert($t, $d);
        }
    }

    public static function spider_edit_post($post_id, $post)
    {
        global $wpdb;
        if (!get_option('wb_spider_analyser_ver', 0)) {
            return;
        }


        if ($post->post_status != 'publish') {
            return;
        }
        $t = $wpdb->prefix . 'wb_spider_post';
        $d = array('post_id' => $post_id, 'url_md5' => md5(str_replace(home_url('/'), '/', get_permalink($post))));
        $error = $wpdb->suppress_errors();
        if (!$wpdb->insert($t, $d)) {
            $wpdb->update($t, array('url_md5' => $d['url_md5']), array('post_id' => $post_id));
        }
        //更新收录状态
        $post_id = intval($post_id);
        $wpdb->query("UPDATE $t a ,$wpdb->postmeta b SET a.status=b.meta_value WHERE a.post_id=$post_id AND a.post_id=b.post_id AND b.meta_key='url_in_baidu'");
        self::post_inner_link($post);
        $wpdb->suppress_errors($error);
    }

    public static function cron_set_spider_post()
    {
        global $wpdb;
        $error = $wpdb->suppress_errors();

        //存量文章入库
        $t = $wpdb->prefix . 'wb_spider_post';
        $sql = "INSERT INTO $t(`post_id`,`status`)  ";
        $sql .= "SELECT a.ID,IFNULL(b.meta_value,0) status FROM $wpdb->posts a LEFT JOIN $wpdb->postmeta b ON a.ID=b.post_id";
        $sql .= " AND b.meta_key='url_in_baidu' WHERE a.post_status='publish' ";
        $sql .= " AND NOT EXISTS (SELECT post_id FROM $t c WHERE c.post_id = a.ID )";
        $wpdb->query($sql);

        //更新文章URL
        $list = $wpdb->get_results("SELECT * FROM $t WHERE url_md5 IS NULL LIMIT 500");
        foreach ($list as $r) {
            $url = str_replace(home_url('/'), '/', get_permalink($r->post_id));
            $wpdb->update($t, array('url_md5' => md5($url)), array('post_id' => $r->post_id));
        }

        //更新收录状态
        $wpdb->query("UPDATE $t a ,$wpdb->postmeta b SET a.status=b.meta_value WHERE a.post_id=b.post_id AND b.meta_key='url_in_baidu'");

        $wpdb->suppress_errors($error);
    }

    public static function set_url_type()
    {
        global $wpdb;

        $t = $wpdb->prefix . 'wb_spider_log';

        $list = $wpdb->get_results("SELECT url,url_md5 FROM $t WHERE url_type IS NULL ORDER BY id DESC LIMIT 200");

        if ($list) foreach ($list as $r) {
            self::txt_log('match url ' . $r->url);
            $result = [];
            $type = self::match_type($r->url, $result);
            self::txt_log('match url type [' . $type . ']');
            if ($type) {
                self::txt_log('update url type [' . $r->url_md5 . ']');
                $wpdb->query($wpdb->prepare("UPDATE $t SET url_type=%s WHERE url_md5=%s", $type, $r->url_md5));
            }
        }
    }

    public static function check_404()
    {
        global $wpdb;
        $max_id = get_option('sp_an_max_id', 0);

        $t = $wpdb->prefix . 'wb_spider_log';
        $list = $wpdb->get_results("SELECT max(id) max_id,url,url_md5 FROM $t WHERE `code`='404' AND id>$max_id GROUP BY url_md5 ORDER BY max_id ASC LIMIT 500");


        foreach ($list as $r) {
            $url = home_url($r->url);
            $http = wp_remote_head($url);
            $code = wp_remote_retrieve_response_code($http);
            if ($code) {
                $wpdb->query($wpdb->prepare("UPDATE $t SET `code`=%s WHERE url_md5 =%s ", $code, $r->url_md5));
                $max_id = $r->max_id;
            }
        }
        update_option('sp_an_max_id', $max_id, false);
    }

    public static function del_old_log()
    {
        global $wpdb;

        $cnf = self::cnf();
        $month = intval($cnf['log_keep']);
        if (!$month) {
            $month = 2;
        }


        $time_str = '-' . $month . ' month';
        if ($month == 1) {
            $time_str = '-7 day';
        } else if ($month == 2) {
            $time_str = '-1 month';
        }

        if ($month > 12) {
            return;
        }

        $t = $wpdb->prefix . 'wb_spider_log';

        $ymd = date('Y-m-d', strtotime($time_str));

        $wpdb->query("DELETE FROM $t WHERE DATE_FORMAT(visit_date,'%Y-%m-%d') < '$ymd' ");
    }

    public static function calc_all_log()
    {
        global $wpdb;


        $t = $wpdb->prefix . 'wb_spider_log';

        $cols = $wpdb->get_col("SELECT DISTINCT DATE_FORMAT(visit_date,'%Y-%m-%d') FROM $t ");


        if ($cols) foreach ($cols as $ymd) {
            self::calc_log($ymd);
        }
    }

    public static function calc_log($ymd = null)
    {

        global $wpdb;

        $t = $wpdb->prefix . 'wb_spider';
        $t_log = $t . '_log';
        $t_sum = $t . '_sum';
        $t_visit = $t . '_visit';
        if (!$ymd) {
            $ymd = current_time('Y-m-d');
        }

        $num = $wpdb->get_var("SELECT COUNT(1) AS num FROM $t_log a WHERE NOT EXISTS(SELECT id FROM $t b WHERE a.spider=b.name)");
        if($num > 0){
            //new spider
            $wpdb->query("INSERT INTO $t(name) SELECT DISTINCT spider FROM $t_log a WHERE NOT EXISTS(SELECT id FROM $t b WHERE a.spider=b.name)");
        }


        $list = $wpdb->get_results("SELECT id,name FROM $t ");
        $spiders = [];
        foreach ($list as $r) {
            $spiders[$r->name] = $r->id;
        }

        //spider

        $sql = "SELECT COUNT(1) num,DATE_FORMAT(a.visit_date,'%Y%m%d%H') ymdh,MIN(a.visit_date) visit_date,a.spider,b.id AS spider_id FROM $t_log a,$t b WHERE a.spider=b.name AND DATE_FORMAT(a.visit_date,'%Y-%m-%d')='$ymd' GROUP BY a.spider,ymdh ";

        $list = $wpdb->get_results($sql);

        //foreach($list as $r->r);

        //删除旧数据
        $wpdb->query("DELETE FROM $t_sum WHERE FROM_UNIXTIME(created,'%Y-%m-%d')='$ymd'");

        foreach ($list as $r) {
            $d = array(
                'ymdh' => $r->ymdh,
                'created' => strtotime($r->visit_date),
                'spider' => $r->spider_id,
                'visit_times' => $r->num
            );
            $wpdb->insert($t_sum, $d);
        }



        return;

        //spider url

        $sql = "SELECT COUNT(1) num,DATE_FORMAT(visit_date,'%Y%m%d') ymdh,MIN(visit_date) visit_date,spider,url FROM $t_log WHERE DATE_FORMAT(visit_date,'%Y-%m-%d')='$ymd' GROUP BY spider,ymdh,url_md5 ";

        $list = $wpdb->get_results($sql);

        //foreach($list as $r->r);

        //删除旧数据
        $wpdb->query("DELETE FROM $t_visit WHERE FROM_UNIXTIME(created,'%Y-%m-%d')='$ymd'");

        foreach ($list as $r) {
            $d = array(
                'ymdh' => $r->ymdh,
                'created' => strtotime($r->visit_date),
                'spider' => $spiders[$r->spider],
                'visit_times' => $r->num,
                'url_md5' => $r->url_md5,
                'url' => $r->url
            );
            $wpdb->insert($t_visit, $d);
        }
    }

    public static function handle()
    {
        if (self::$in_log) {
            return;
        }

        if (self::$blocked) {
            return;
        }

        if (!self::$after_request) {
            return;
            /*$headers = headers_list();
            $is_30x = 0;
            foreach($headers as $s){
                if(preg_match('#^location#i',$s)){
                    $is_30x = 1;
                    break;
                }
            }
            if($is_30x){
                return;
            }
            */
        }

        self::$in_log = true;

        $has_error = error_get_last();

        global $wp, $wp_query;

        if ($has_error && self::should_handle_error($has_error)) {
            $code = '500';
        } else if (is_404()) {
            $code = '404';
        } else {
            $code = '200';
        }
        self::log($code);
    }

    protected static function should_handle_error($error)
    {
        $error_types_to_handle = array(
            E_ERROR,
            E_PARSE,
            E_USER_ERROR,
            E_COMPILE_ERROR,
            E_RECOVERABLE_ERROR,
        );

        if (isset($error['type']) && in_array($error['type'], $error_types_to_handle, true)) {
            return true;
        }

        return (bool) apply_filters('wp_should_handle_php_error', false, $error);
    }

    public static function cnf()
    {
        static $option = null;
        if (!$option) {
            $option = WP_Spider_Analyser_Admin::cnf();
        }

        return $option;
    }

    public static function spider()
    {
        try {
            if (!isset($_SERVER['REQUEST_METHOD']) || $_SERVER['REQUEST_METHOD'] != 'GET') {
                return null;
            }
            if (!isset($_SERVER['HTTP_USER_AGENT']) || empty($_SERVER['HTTP_USER_AGENT'])) {
                return null;
            }
            $agent = $_SERVER['HTTP_USER_AGENT'];
            $cnf = self::cnf();
            //forbid
            do {

                if (preg_match('#spider#i', $agent)) {
                    break;
                }
                if (preg_match('#bot#i', $agent)) {
                    break;
                }
                if (preg_match('#crawler#i', $agent)) {
                    break;
                }
                if (preg_match('#(Daumoa|Yahoo!|Qwantify|Seeker|Elefent|13TABS|iqdb|TinEye|Plukkie|PDFDriveCrawler)#i', $agent)) {
                    break;
                }

                $find_match = false;
                if ($cnf['user_define']) foreach ($cnf['user_define'] as $v) {
                    if (preg_match('#' . preg_quote($v) . '#i', $agent)) {
                        $find_match = true;
                        break;
                    }
                }
                if ($find_match) {
                    break;
                }

                return null;
            } while (0);

            $spider = '';

            //自定义蜘蛛
            if ($cnf['user_define']) foreach ($cnf['user_define'] as $v) {
                if (preg_match('#' . preg_quote($v) . '#i', $agent)) {
                    $spider = $v;
                    break;
                }
            }

            if ($spider) {
            } else if (preg_match('#sogou (web|inst|news|pic|wap) spider#i', $agent, $spider_match)) {
                $spider = 'sogou spider';
            } else if (preg_match('#[a-z0-9\.-]+ spider#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else if (preg_match('#[a-z0-9\.-]+ bot#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else if (preg_match('#[a-z0-9\.-]*spider[a-z0-9]*#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else if (preg_match('#[a-z0-9\.-]*bot[a-z0-9]*#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else if (preg_match('#[a-z0-9\.-]+ crawler#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else if (preg_match('#[a-z0-9\.-]*crawler[a-z0-9]*#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else if (preg_match('#(Daumoa|Yahoo!|Qwantify|Seeker|Elefent|13TABS|iqdb|TinEye|Plukkie|PDFDriveCrawler)#i', $agent, $spider_match)) {
                $spider = $spider_match[0];
            } else {
                $spider = 'other';
            }

            return $spider;
        } catch (Exception $ex) {
        }
        return null;
    }

    public static function getIp()
    {
        if (isset($_SERVER['HTTP_CLIENT_IP']) && $_SERVER['HTTP_CLIENT_IP']) {
            return $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && $_SERVER['HTTP_X_FORWARDED_FOR']) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR']) {
            return $_SERVER['REMOTE_ADDR'];
        }
        return null;
    }

    public static function log($status = '')
    {
        global $wp_the_query, $wpdb;
        try {
            $spider = self::spider();
            if (!$spider) {
                return;
            }
            $cnf = self::cnf();
            //$agent = $_SERVER['HTTP_USER_AGENT'];
            //用户禁用，不记录
            $skip_list = self::get_skip_spider();
            if ($skip_list) foreach ($skip_list as $v) {
                if ($v && preg_match('#^' . preg_quote($v) . '$#i', $spider)) {
                    return;
                }
            }

            $url = $_SERVER['REQUEST_URI'];

            $d = array(
                'spider' => $spider,
                'visit_date' => current_time('mysql'),
                'code' => $status,
                'visit_ip' => self::getIp(),
                'url' => $url,
                'url_md5' => md5($url),
            );

            $type = null;

            if ($cnf['extral_rule']) foreach ($cnf['extral_rule'] as $r_type => $rule) {
                if (!$rule) {
                    continue;
                }

                $rule = str_replace(array(',', '\\*'), array('|', '.+?'), preg_quote($rule));
                if (preg_match('#(' . $rule . ')#i', $url)) {
                    $type = $r_type;
                    break;
                }
            }
            if (!$type && $cnf['user_rule']) foreach ($cnf['user_rule'] as $r) {
                if (!$r['rule']) {
                    continue;
                }
                $rule = str_replace(array(',', '\\*'), array('|', '.+?'), preg_quote($r['rule']));
                if (preg_match('#(' . $rule . ')#i', $url)) {
                    $type = $r['name'];
                    break;
                }
            }

            //['index','post','page','category','tag','search','author','feed','sitemap','api','other'];
            if ($type) {
            } else if (preg_match('#^/sitemap(-[a-z0-9_-]+)?\.xml#i', $d['url'])) {
                $type = 'sitemap';
            } else if (preg_match('#wp-admin/admin-ajax\.php#', $d['url'])) {
                $type = 'api';
            } else if ($wp_the_query && $wp_the_query instanceof WP_Query) {
                if ($wp_the_query->is_search()) {
                    $type = 'search';
                } else if ($wp_the_query->is_feed()) {
                    $type = 'feed';
                } else if ($wp_the_query->is_tag()) {
                    $type = 'tag';
                } else if ($wp_the_query->is_author()) {
                    $type = 'author';
                } else if ($wp_the_query->is_category() || $wp_the_query->is_archive()) {
                    $type = 'category';
                } else if ($wp_the_query->is_singular(array('page'))) {
                    $type = 'page';
                } else if ($wp_the_query->is_singular()) {
                    $type = 'post';
                } else if ($wp_the_query->is_home() || $wp_the_query->is_front_page()) {
                    $type = 'index';
                }
            }

            if ($type) {
                $d['url_type'] = $type;
            }


            if ($cnf['log_update'] == 'db') {
                $wpdb->insert($wpdb->prefix . 'wb_spider_log', $d);
            } else {
                $log_file = __DIR__ . '/#log/log-' . current_time('dH') . '.txt';
                error_log(json_encode($d) . "\n", 3, $log_file);
                //error_log();
            }
        } catch (Exception $ex) {
        }
    }

    public static function adminMenu()
    {
        global $wb_settings_page_hook_theme, $submenu;
        $wb_settings_page_hook_theme = add_menu_page(
            '蜘蛛分析',
            '蜘蛛分析',
            'administrator',
            'wp_spider_analyser',
            array(__CLASS__, 'spider_views'), //
            WP_SPIDER_ANALYSER_URL . 'assets/ico.svg'
        );
        add_submenu_page('wp_spider_analyser', '蜘蛛概况', '蜘蛛概况', 'administrator', 'wp_spider_analyser#/home', array(__CLASS__, 'spider_views'));
        add_submenu_page('wp_spider_analyser', '蜘蛛日志', '蜘蛛日志', 'administrator', 'wp_spider_analyser#/log', array(__CLASS__, 'spider_views'));
        add_submenu_page('wp_spider_analyser', '蜘蛛列表', '蜘蛛列表', 'administrator', 'wp_spider_analyser#/list', array(__CLASS__, 'spider_views'));
        add_submenu_page('wp_spider_analyser', '访问路径', '访问路径', 'administrator', 'wp_spider_analyser#/path', array(__CLASS__, 'spider_views'));
        add_submenu_page('wp_spider_analyser', '文章爬取', '文章爬取', 'administrator', 'wp_spider_analyser#/post', array(__CLASS__, 'spider_views'));
        add_submenu_page('wp_spider_analyser', '插件设置', '插件设置', 'administrator', 'wp_spider_analyser#/setting', array(__CLASS__, 'spider_views'));
        if (!get_option('wb_spider_analyser_ver', 0)) {
            add_submenu_page('wp_spider_analyser', '升至Pro版', '<span style="color: #FCB214;">升至Pro版</span>', 'administrator', "https://www.wbolt.com/plugins/spider-analyser' target='_blank'");
        }

        unset($submenu['wp_spider_analyser'][0]);
    }

    public static function actionLinks($links, $file)
    {

        //print_r([$file]);
        if (!preg_match('#spider-analyser/#', $file)) {
            return $links;
        }
        if (!get_option('wb_spider_analyser_ver', 0)) {
            $a_link = '<a href="https://www.wbolt.com/plugins/spider-analyser" target="_blank"><span style="color: #FCB214;">升至Pro版</span></a>';
            array_unshift($links, $a_link);
        }
        $a_link = '<a href="' . menu_page_url('wp_spider_analyser', false) . '#/setting">设置</a>';
        array_unshift($links, $a_link);



        return $links;
    }

    public static function update_spider($spider)
    {
        $api = 'https://www.wbolt.com/wb-api/v1/spider/info';
        $arg = array(
            'timeout'   => 1,
            'blocking'  => false,
            'sslverify' => false,
            'body' => array('spider' => json_encode($spider)),
            'headers' => array('referer' => home_url()),
        );
        wp_remote_post($api, $arg);
    }

    public static function spider_views()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        global $wpdb;

        $t = $wpdb->prefix . 'wb_spider';
        $t_log = $t . '_log';

        $num = $wpdb->get_var("SELECT COUNT(1) AS num FROM $t_log a WHERE NOT EXISTS(SELECT id FROM $t b WHERE a.spider=b.name)");
        if($num > 0){
            $wpdb->query("INSERT INTO $t(name) SELECT DISTINCT spider FROM $t_log a WHERE NOT EXISTS(SELECT id FROM $t b WHERE a.spider=b.name)");
        }


        $time = get_option('sync_wb_spider', 0);

        if (time() > $time) {
            update_option('sync_wb_spider', time() + 3600);
            self::sync_wb_spider();
        }


        echo '<div class="wbs-wrap" id="optionsframework-wrap"><div id="app"></div></div>';
    }


    public static function spider_path()
    {
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }

        global $wpdb;
        $t = $wpdb->prefix . 'wb_spider_log';
        $spider = $wpdb->get_col("SELECT DISTINCT spider FROM $t");
        $code = $wpdb->get_col("SELECT DISTINCT code FROM $t");
        $url_types = WP_Spider_Analyser_Admin::url_types();
        $cnf = self::cnf();
        if ($cnf['user_rule']) foreach ($cnf['user_rule'] as $r) {
            $url_types[$r['name']] = $r['name'];
        }

        $res['spider'] = $spider;
        $res['code'] = $code;
        $res['url_types'] = $url_types;
        $res['day'] = array(
            array(
                'value' => '-1',
                'label' => '所有时间'
            ),
            array(
                'value' => '0',
                'label' => '今天'
            ),
            array(
                'value' => '7',
                'label' => '近7天'
            ),
            array(
                'value' => '30',
                'label' => '近30天'
            )
        );

        return $res;
    }

    public static function spider_log()
    {
        $res = array();
        global $wpdb;
        $t = $wpdb->prefix . 'wb_spider_log';
        $spider = $wpdb->get_col("SELECT DISTINCT spider FROM $t");
        $code = $wpdb->get_col("SELECT DISTINCT code FROM $t");
        $res['spider'] = $spider;
        $res['code'] = $code;

        $res['day'] = array(
            array(
                'value' => '-1',
                'label' => '所有时间'
            ),
            array(
                'value' => '0',
                'label' => '今天'
            ),
            array(
                'value' => '7',
                'label' => '近7天'
            ),
            array(
                'value' => '30',
                'label' => '近30天'
            )
        );

        return $res;
    }

    public static function get_skip_spider()
    {
        global $wpdb;
        $t = $wpdb->prefix . 'wb_spider';
        return $wpdb->get_col("SELECT `name` FROM $t WHERE `skip` = 1");
    }

    public static function skip_spider($spider)
    {
        global $wpdb;
        $t = $wpdb->prefix . 'wb_spider';
        $wpdb->query($wpdb->prepare("UPDATE $t SET `skip`=1 WHERE `name`=%s", $spider));
    }

    public static function sync_wb_spider()
    {
        global $wpdb;

        $t = $wpdb->prefix . 'wb_spider';
        $spiders = $wpdb->get_col("SELECT name FROM $t");
        if (empty($spiders)) {
            return;
        }

        $api = 'https://www.wbolt.com/wb-api/v1/spider/info';
        $param = [
            'timeout' => 5,
            'sslverify' => false,
            'headers' => array('referer' => home_url()),
            'body' => ['udg' => 1]
        ];
        $http = wp_remote_get($api, $param);
        do {
            if (is_wp_error($http)) {
                break;
            }
            $body = wp_remote_retrieve_body($http);
            if (!$body) {
                break;
            }
            $data = json_decode($body, true);
            if (!$data) {
                break;
            }
            if (!is_array($data)) {
                break;
            }

            $t = $wpdb->prefix . 'wb_spider';
            $wpdb->query("UPDATE $t set `status` = 1 WHERE `status` = 2");
            foreach ($data['data'] as $r) {
                if (!in_array($r['name'], $spiders)) {
                    continue;
                }
                $wpdb->query($wpdb->prepare("UPDATE $t SET `status` = 2,`bot_type` = %s,`bot_url`=%s WHERE name = %s", $r['bot_type'], $r['bot_url'], $r['name']));
            }
        } while (0);
    }

    public static function spider_info()
    {
        global $wpdb;

        $time = current_time('U', 1);
        $info = get_option('wb_spider_info', array());

        if ($info && isset($info['expired']) &&  $info['expired'] > $time && isset($info['data'])) {
            return $info['data'];
        }
        $spider_data = array();
        if ($info && $info['data']) {
            $spider_data = $info['data'];
        }

        $info = array('expired' => $time + 1 * HOUR_IN_SECONDS, 'data' => array());
        $api = 'https://www.wbolt.com/wb-api/v1/spider/info';
        $http = wp_remote_get($api, array('sslverify' => false, 'headers' => array('referer' => home_url()),));
        do {
            if (is_wp_error($http)) {
                break;
            }
            $body = wp_remote_retrieve_body($http);
            if (!$body) {
                break;
            }
            $data = json_decode($body, true);
            if (!$data) {
                break;
            }
            if (!is_array($data)) {
                break;
            }
            $t = $wpdb->prefix . 'wb_spider_log';
            $spider = $wpdb->get_col("SELECT DISTINCT spider FROM $t WHERE 1");
            //$spider_data = array();
            foreach ($data['data'] as $k => $r) {
                if (isset($spider_data[$k])) {
                    $old = $spider_data[$k];
                    if (!$old['bot_type'] && $r['bot_type'] && $r['bot_type'] != '未分类') {
                        $old['bot_type'] = $r['bot_type'];
                    }
                    if (!$old['bot_url'] && $r['bot_url']) {
                        $old['bot_url'] = $r['bot_url'];
                    }
                    $spider_data[$k] = $old;
                    continue;
                }
                if (!$r['bot_type']) continue;
                if (!in_array($k, $spider)) continue;
                $spider_data[$k] = $r;
            }
            $info['data'] = $spider_data;
        } while (0);
        update_option('wb_spider_info', $info, false);
        return $info['data'];
    }


    public static function db_ver()
    {
        return 1.5;
    }

    public static function set_up()
    {
        self::setup_db();
    }

    public static function setup_db($create_tables = null)
    {

        global $wpdb;


        $wb_tables = array(
            'wb_spider',
            'wb_spider_ip',
            'wb_spider_log',
            'wb_spider_post',
            'wb_spider_post_link',
            'wb_spider_sum',
            'wb_spider_visit',
        );
        if (!$create_tables && is_array($create_tables)) {
            $wb_tables = $create_tables;
        }

        //数据表
        $tables = $wpdb->get_col("SHOW TABLES LIKE '" . $wpdb->prefix . "wb_spider%'");


        $set_up = array();
        foreach ($wb_tables as $table) {
            if (in_array($wpdb->prefix . $table, $tables)) {
                continue;
            }
            $set_up[] = $table;
        }

        if (empty($set_up)) {
            return;
        }

        $sql = file_get_contents(__DIR__ . '/install/init.sql');

        $charset_collate = $wpdb->get_charset_collate();



        $sql = str_replace('`wp_wb_', '`' . $wpdb->prefix . 'wb_', $sql);
        $sql = str_replace('ENGINE=InnoDB', $charset_collate, $sql);



        $sql_rows = explode('-- row split --', $sql);

        foreach ($sql_rows as $row) {

            if (preg_match('#`' . $wpdb->prefix . '(wb_spider.*?)`\s+\(#', $row, $match)) {
                if (in_array($match[1], $set_up)) {
                    $wpdb->query($row);
                }
            }
            //print_r($row);exit();
        }

        update_option('wb_spider_analyser_db_ver', self::db_ver());
    }

    public static function upgrade()
    {
        global $wpdb;


        $db_ver = get_option('wb_spider_analyser_db_ver');
        if (!$db_ver) {
            return;
        }

        if (version_compare($db_ver, '1.2') < 0) {
            $t = $wpdb->prefix . 'wb_spider_log';
            $sql = $wpdb->get_var('SHOW CREATE TABLE `' . $t . '`', 1);
            if (!preg_match('#`url_type`#is', $sql)) {
                $wpdb->query("ALTER TABLE $t ADD `url_type` varchar(32) DEFAULT NULL");
                $wpdb->query("ALTER TABLE $t ADD INDEX(`url_type`)");
            }
            update_option('wb_spider_analyser_db_ver', '1.2');
        }
        if (version_compare($db_ver, '1.3') < 0) {
            self::setup_db(array('wb_spider_ip', 'wb_spider_post', 'wb_spider_post_link'));
            update_option('wb_spider_analyser_db_ver', '1.3');
        }
        if (version_compare($db_ver, '1.4') < 0) {
            $t = $wpdb->prefix . 'wb_spider';
            $sql = $wpdb->get_var('SHOW CREATE TABLE `' . $t . '`', 1);
            if (!preg_match('#`skip`#is', $sql)) {
                $wpdb->query("ALTER TABLE $t ADD `skip` tinyint(3) UNSIGNED NOT NULL DEFAULT '0'");
            }
            if (!preg_match('#`bot_type`#is', $sql)) {
                $wpdb->query("ALTER TABLE $t ADD `bot_type` varchar(32) DEFAULT NULL");
                $wpdb->query("ALTER TABLE $t ADD INDEX(`bot_type`)");
            }
            if (!preg_match('#`bot_url`#is', $sql)) {
                $wpdb->query("ALTER TABLE $t ADD `bot_url` varchar(256) DEFAULT NULL");
            }
            update_option('wb_spider_analyser_db_ver', '1.4');

            $cnf = WP_Spider_Analyser_Admin::cnf();
            if (isset($cnf['forbid']) && is_array($cnf['forbid'])) {
                $t = $wpdb->prefix . 'wb_spider';
                foreach ($cnf['forbid'] as $v) {
                    $wpdb->query($wpdb->prepare("UPDATE $t SET `skip` = 1 WHERE `name` = %s", $v));
                }
                unset($cnf['forbid']);
                update_option(WP_Spider_Analyser_Admin::$option, $cnf);
            }

            self::sync_wb_spider();
        }

        if (version_compare($db_ver, '1.5') < 0) {
            self::setup_db(array('wb_spider'));
            update_option('wb_spider_analyser_db_ver', '1.5');
        }
    }

    public static function cache($param, $data = null, $expire = 0, $code = 'json')
    {
        $key = md5(json_encode($param));
        if (!is_dir(__DIR__ . '/#log/')) {
            mkdir(__DIR__ . '/#log/', 0755);
        }
        $cache_file = __DIR__ . '/#log/' . $key . '.php';
        if (null === $data) {
            if (file_exists($cache_file)) {
                return $cache_file;
            }
            return false;
        }
        if (is_array($data)) {
            $data = json_encode($data);
        }
        $expired = time() +  $expire;
        $content = '<' . '?php if(time()>' . $expired . '){return;}';
        if ($code) {
            if ($code == 'json') {
                $code = 'header("content-type:text/json;");';
            }
            $content .= $code;
        }
        $content .= '?' . '>' . $data . '<' . '?php exit();';
        file_put_contents($cache_file, $content);
    }

    public static function clear_cache()
    {
        $cache_file = __DIR__ . '/#log/*.php';
        $files = glob($cache_file);
        if ($files && is_array($files)) {
            foreach ($files as $file) {
                unlink($file);
            }
        }
    }
}
