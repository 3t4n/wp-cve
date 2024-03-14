<?php
/**
 * This is the main class for Title Experiemnts
 */

class WPEx
{
    public $titles_tbl;
    public $stats_tbl;
    private $table_slug;
    private $now;
    public $cache_key = '2016-05-02-00';
    private $last_title = null;
    public $experiments_enabled = true;

    public function __construct($slug = "wpex")
    {
        global $wpdb;
        $this->table_slug = $slug;

        $this->titles_tbl = $wpdb->prefix . $this->table_slug . "_titles";
        $this->stats_tbl = $wpdb->prefix . $this->table_slug . "_stats";

        //Initialize
        add_action('init', array($this, 'start_session'), 1);
        add_action('add_meta_boxes', array($this, 'add_meta_box'));
        if ($this->get_option("wpex_use_js", false)) {
            add_action('wp_enqueue_scripts', array($this, 'enqueue'));
        }
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue'));

        //Save the blocks
        add_action('save_post', array($this, 'save_blocks'));

        //Admin CSS
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_filter('manage_edit-post_columns', array($this, 'edit_post_columns'));
        add_filter('manage_edit-page_columns', array($this, 'edit_post_columns'));
        add_action('manage_posts_custom_column', array($this, 'edit_post_custom_column'), 10, 2);
        add_action('manage_pages_custom_column', array($this, 'edit_post_custom_column'), 10, 2);

        add_filter('the_title', array($this, 'titles'), 10, 2);

        add_filter('wp_title', array($this, 'meta_title'), 12, 2);
        add_filter('document_title_parts', array($this, 'title_parts'), 12, 2);

        add_action('wp_ajax_wpex_stat_reset', array($this, 'reset_stats'));
        add_action('wp_ajax_wpex_titles', array($this, 'ajax_titles'));
        add_action('wp_ajax_wpex_clear_stats', array($this, 'clear_stats'));
        add_action('wp_ajax_nopriv_wpex_titles', array($this, 'ajax_titles'));

        add_action('wp_ajax_nopriv_wpex_setcookies', array($this, 'set_cookies'));

        add_action('add_meta_boxes', array($this, 'register_meta_boxes'));
        
        add_action('admin_menu', array($this, 'settings_menu'));
        $this->now = current_time("timestamp");
        if ($this->get_option("wpex_installed", false) === false) {
            $this->update_option("wpex_installed", $this->now);
        }
    }

    public function start_session()
    {
        if (!session_id()) {
            session_start();
        }
    }

    public function update_option($key, $value)
    {
        if (function_exists("apc_store")) {
            apc_store($key, $value);
        }
        update_option($key, $value);
    }

    public function get_option($key, $default = null)
    {
        // This is around for debugging purposes
        if (isset($_REQUEST['wpex_force_option'])) {
            if (isset($_REQUEST['wpex_force_option'][$key])) {
                return sanitize_text_field($_REQUEST['wpex_force_option'][$key]);
            }
        }

        if (function_exists("apcs_exists")) {
            //if apc is around, use that to cache our get_option calls
            if (apc_exists($key)) {
                $return = apc_fetch($key);
                return $return === false ? $default : $return;
            } else {
                $option = get_option($key, $default);
                apc_store($key, $option, 7200); //keep it around for 2h (probably could be persistant - but oh well)
                return $option;
            }
        } else {
            return get_option($key, $default);
        }
    }

    public function settings_menu()
    {
        add_submenu_page('options-general.php', 'Title Exp Settings', 'Title Exp Settings', 'manage_options', "wpex-settings", array($this, "general_settings"));
    }

    public function register_meta_boxes()
    {
        add_meta_box('wpex-titles-meta-box', __('Title Experiments: Alternative Titles', 'wpex' ), array($this, 'metabox_display'), 'post');
    }

    function metabox_display($post) {
        // Display code/markup goes here. Don't forget to include nonces!
        echo '<div id="wpex-titles-metabox-cont">
            <div id="wpex-titlewrap">
                <input id="wpex-main-title" type="text" value="' . addslashes($post->post_title) . '" style="display: none;" />
            </div>
        </div>';
    }

    // The general settings page
    public function general_settings()
    {
        global $titleEx;
        if (isset($_REQUEST['save'])) {
            $this->update_option("wpex_use_js", sanitize_text_field($_REQUEST['use_js']));
            $this->update_option("wpex_hide_body", sanitize_text_field($_REQUEST['hide_body']));
            $this->update_option("wpex_best_feed", sanitize_text_field($_REQUEST['best_feed']));
            $this->update_option("wpex_search_engines", sanitize_text_field($_REQUEST['search_engines']));
            $this->update_option("wpex_adjust_every", sanitize_text_field($_REQUEST['adjust_every']));
            $this->update_option("wpex_skip_pages", sanitize_text_field($_REQUEST['skip_pages']));
            $this->update_option("wpex_ignore_users", sanitize_text_field($_REQUEST['ignore_users']));
            if ($titleEx) {
                $titleEx->save_settings($_REQUEST);
            }
        }

        $use_js = $this->get_option("wpex_use_js", false);
        $hide_body = $this->get_option("wpex_hide_body", false);
        $best_feed = $this->get_option("wpex_best_feed", false);
        $search_engines = $this->get_option("wpex_search_engines", "first");
        $adjust_every = $this->get_option("wpex_adjust_every", 300);
        $skip_pages = $this->get_option("wpex_skip_pages", 300);
        $ignore_users = $this->get_option("wpex_ignore_users", false);
        include 'wpex-general-settings.php';
    }

    public function clear_stats()
    {
        global $wpdb;
        $sql = "UPDATE " . $this->titles_tbl ." SET clicks=0,impressions=0,stats=''";
        $wpdb->query($sql);
        $sql = "DELETE FROM " . $this->stats_tbl .";";
        $wpdb->query($sql);

        $sql = "SELECT post_id, COUNT(*) as count FROM " . $this->titles_tbl . " WHERE enabled GROUP BY post_id";
        $titles_result = $wpdb->get_results($sql, ARRAY_A);
        foreach ($titles_result as $row) {
            $wpdb->update($this->titles_tbl, array("probability"=>round(100/$row['count'])), array("post_id"=>$row['post_id'], "enabled"=>1));
        }
    }

    public function reset_stats($data)
    {
        global $wpdb;
        $post_id = sanitize_text_field($_POST['id']);
        $post_id = intval($post_id);
        $sql = "UPDATE " . $this->titles_tbl ." SET clicks=0,impressions=0,stats='' WHERE post_id=".$post_id;
        $wpdb->query($sql);
        $sql = "DELETE FROM " . $this->stats_tbl ." WHERE post_id=".$post_id;
        $wpdb->query($sql);
        $sql = "SELECT COUNT(*) FROM " . $this->titles_tbl . " WHERE post_id=".$post_id." AND enabled";
        $count = $wpdb->get_var($sql);
        if ($count > 0) {
            $wpdb->update($this->titles_tbl, array("probability"=>round(100/$count)), array("post_id"=>$post_id, "enabled"=>1));
        }
    }

    public function get($what, $post_id)
    {
        $d = isset($_SESSION['wpex_data']) ? $_SESSION['wpex_data'] : array();
        if (isset($d[$what.($this->cache_key).$post_id])) {
            return $d[$what.($this->cache_key).$post_id];
        } else {
            return null;
        }
    }

    public function set($what, $post_id, $id)
    {
        $d = isset($_SESSION['wpex_data']) ? $_SESSION['wpex_data'] : array();
        $d[$what.($this->cache_key).$post_id] = $id;
        $_SESSION['wpex_data'] = $d;
    }

    public function viewed($post_id, $title_id)
    {
        global $wpdb;
        if ($this->is_bot()) {
            return;
        }

        $viewed = $this->get('wpex_viewed', 'WPEX');
        if (!is_array($viewed)) {
            $viewed = array();
        }
        if (in_array($post_id, $viewed)) {
            return;
        }
        $sql = "SELECT stats FROM " . $this->titles_tbl . " WHERE id=".$title_id;

        $result = $wpdb->get_row($sql);
        if ($result) {
            $time = strtotime("midnight");
            $this->delta_stats($title_id, $post_id, $time, 0, 1);
            $sql = "UPDATE " . $this->titles_tbl ." SET clicks=clicks+1 WHERE id=".$title_id;
            $wpdb->query($sql);
        }

        $viewed[] = $post_id;
        $this->set('wpex_viewed', 'WPEX', $viewed);
    }

    public function delta_stats($title_id, $post_id, $time, $impressions, $clicks)
    {
        global $wpdb;
        if (preg_match("/^\d+$/", $title_id) && preg_match("/^\d+$/", $time) && preg_match("/^\d+$/", $impressions) && preg_match("/^\d+$/", $clicks)) {
            $sql = "SELECT * FROM " . $this->stats_tbl ." WHERE ts=$time AND title_id=".$title_id;
            $row = $wpdb->get_row($sql, ARRAY_A);
            if ($row) {
                $sql = "UPDATE " . $this->stats_tbl ." SET impressions=impressions+$impressions, clicks=clicks+$clicks WHERE ts=$time AND title_id=".$title_id;
            } else {
                $sql = "INSERT INTO " . $this->stats_tbl ."(ts, post_id, title_id, impressions, clicks) VALUES($time, $post_id, $title_id, $impressions, $clicks);";
            }
            $wpdb->query($sql);
        }
    }

    public function ajax_titles()
    {
        $titles = array();
        $images = array();
        if (isset($_POST['id'])) 
		{	
            $cur_page = isset($_POST['cur_id']) ? intval($_POST['cur_id']) : null;
            foreach ((int)$_POST['id'] as $id) {
                $images[$id] = array(
                    "old" => get_post_thumbnail_id($id)
                );
                $titles[$id] = $this->titles("", $id, true, $id == $cur_page);
                // the titles() call will update a global variable which will
                // effect the return result of get_post_thumbnail_id
                $images[$id]["new"] = wp_get_attachment_url(get_post_thumbnail_id($id));
            }
            echo json_encode(array(
                'images' => $images,
                'titles' => $titles
            ));
            die();
        }
    }

    public function edit_post_custom_column($column, $post_id = null)
    {
        global $wpdb;
        if ($post_id) {
            switch ($column) {
                case 'wpex_titles':
                    //never trust an elf
                    $post_id = intval($post_id);

                    $sql = "SELECT COUNT(*) as c FROM ".$this->titles_tbl." WHERE post_id=".$post_id;
                    $row = $wpdb->get_row($sql, ARRAY_A);

                    if (is_plugin_active("title-experiments-pro/title-experiments.php")) {
                        if ($row['c'] > 0) {
                            echo "<a href='" . admin_url('admin.php?page=title-experiments-pro%2Ftitle-experiments.php-menu&id='.$post_id) . "'>" . $row['c'] . "</a>";
                        }
                    } else {
                        echo esc_attr($row['c'] > 0 ? $row['c'] : '');
                    }
                    break;
            }
        }
    }

    public function edit_post_columns($columns)
    {
        return array_slice($columns, 0, 1, true)
            + array("wpex_titles" => "<span class='dashicons-before dashicons-editor-ul' title='Shows how many alternate titles this post has'></span>")
            + array_slice($columns, 1, count($columns)-1, true);
    }

    public function meta_title($title)
    {
        global $post;
        if (is_singular() && !is_home()) {
            get_the_title(); // load the title
            $use_js = $this->get_option("wpex_use_js", false);
            if ($use_js && substr($this->last_title, 0, 25)  == "<span data-wpex-title-id=") {
                // put three unprintable characters here to replace
                return str_replace($post->post_title, sprintf("%c%c%c", 27, 28, 29), $title);
            } else {
                return str_replace($post->post_title, $this->last_title, $title);
            }
        }
        return $title;
    }

    public function title_parts($parts)
    {
        global $post;
        if (is_singular() && !is_home()) {
            $use_js = $this->get_option("wpex_use_js", false);
            get_the_title(); // load the title
            if ($use_js && substr($this->last_title, 0, 25)  == "<span data-wpex-title-id=") {
                $parts['title'] = str_replace($post->post_title, sprintf("%c%c%c", 27, 28, 29), $parts['title']);
            } else {
                $parts['title'] = str_replace($post->post_title, $this->last_title, $parts['title']);
            }
        }
        return $parts;
    }

    public function titles($title, $id = null, $ajax = false, $viewed = false)
    {
        $this->last_title = $this->_titles($title, $id, $ajax, $viewed);
        return $this->last_title;
    }

    private function _titles($title, $id = null, $ajax = false, $viewed = false)
    {
        global $wpdb, $titleEx;

        if ($id == null) {
            return $title;
        }

        if (!$ajax && is_admin()) {
            return $title;
        }

        if (! $this->experiments_enabled) {
            return $title;
        }

        $skip_pages = $this->get_option("wpex_skip_pages", 300);
        $pages = explode("\n", $skip_pages);

        if (in_array($_SERVER['REQUEST_URI'], $pages)) {
            return $title;
        }

        // Check if we are supposed to ignore logged in users
        $ignore_users = $this->get_option("wpex_ignore_users", false);
        if ($ignore_users && is_user_logged_in() && current_user_can('edit_post', $id)) {
            return $title;
        }

        if ($titleEx && !$titleEx->should_run_experiment($title, $id, $ajax, $viewed)) {
            return $title;
        }

        // ensure consistant ordering
        $sql = "SELECT id,title,thumbnail_id,impressions,clicks,probability,last_updated FROM " . $this->titles_tbl . " WHERE enabled=1 AND post_id=".$id." ORDER BY id";
        $titles_result = $wpdb->get_results($sql, ARRAY_A);
        if (count($titles_result) === 0) {
            //No titles are here
            return $title;
        }

        $transient_key = md5(__FUNCTION__ . $this->cache_key . $id);
        if (false == ($titles_result = get_transient($transient_key))) {
            // ensure consistant ordering
            $sql = "SELECT id,title,thumbnail_id,impressions,clicks,probability,last_updated FROM " . $this->titles_tbl . " WHERE enabled=1 AND post_id=" . $id . " ORDER BY id";
            $titles_result = $wpdb->get_results($sql, ARRAY_A);
            set_transient($transient_key, $titles_result);
            if (count($titles_result) === 0) {
                //No titles are here
                return $title;
            }
        }

        $search_engines = $this->get_option("wpex_search_engines", "first");

        // search engines should see the first title
        if ($this->is_bot() && $search_engines == "first") {
            return $title;
        }

        //If this is a feed - no funny business
        // or if search engines should see the best title
        if (is_feed() || ($this->is_bot() && $search_engines == "best")) {
            //use the best title based on click percent
            if ($this->get_option("wpex_best_feed", false)) {
                $max = array(-1, null);
                foreach ($titles_result as $t) {
                    $_max = $t['clicks'] / ($t['impressions'] == 0 ? 1 : $t['impressions']);
                    if ($_max > $max[0]) {
                        $max = array($_max, $t['title']);
                    }
                }
                if ($max === null) {
                    return $title;
                } //give up
                if ($max[1] == "__WPEX_MAIN__") {
                    return $title;
                } else {
                    return stripslashes($max[1]);
                }
            } else {
                //use the standard title
                return $title;
            }
        }

        $title_id = null;
        if (!$ajax && $this->get_option("wpex_use_js", false)) {
            $extra_attrs = $titleEx ? $titleEx->extra_js_attrs($title, $id, $ajax, $viewed) : "";
            return "<span data-wpex-title-id='$id' style='min-height: 1em; display: inline-block;' data-original='".base64_encode($title)."' $extra_attrs>".htmlentities($title)."</span>";
        }

        //Check if a specific post title is in our cookie
        $result = $this->get("title", $id);
        $from_cookie = false;
        if ($result) {
            foreach ($titles_result as $t) {
                if ($t['id'] == $result) {
                    $from_cookie = true;
                    $result = array($t);
                    break;
                }
            }
        }

        // If it isn't an array, the test id in the cookie was no longer there
        if (!$result || !is_array($result)) {
            $result = $titles_result;
        }

        $startTime = microtime(true);
        if (count($result) > 1) {
            //check if we need to regen the probabilities
            $adjust_every = $this->get_option("wpex_adjust_every", 300);

            if ($adjust_every >= 0 && $result[0]['last_updated'] + $adjust_every < $this->now) {
                //Use a beta distribution random number to determine which
                //test to show. Based on:
                // http://camdp.com/blogs/multi-armed-bandits
                require_once dirname(__FILE__).'/libs/PDL/BetaDistribution.php';
                mt_srand();
                $max = 0;
                foreach ($result as &$t) {
                    $i = (0.5) * $t['impressions'];
                    $c = (0.5) * $t['clicks'];
                    $t['bd']= new BetaDistribution(1+$c, 1+max(0, $i-$c));
                }

                $this->statTests = $result;

                $total_probability = 0;
                foreach ($result as $idx => &$test) {
                    $this->statChecking = $idx;
                    $test['probability'] = round($this->simpsonsrule() * 100);
                    $total_probability += $test['probability'];
                    $sql = "UPDATE " . $this->titles_tbl ." SET probability=".$test['probability'].", last_updated=".$this->now." WHERE id=".$test['id'];
                    $wpdb->query($sql);
                }

                // for some reason, the probabiltiy is greater than 100
                // sometimes. This isn't a problem really, but let's normalize it
                if ($total_probability != 100 && $total_probability > 0) {
                    $ratio = 100/$total_probability;
                    foreach ($result as $idx=>&$test) {
                        $test['probability'] = round($test['probability'] * $ratio);
                        $sql = "UPDATE " . $this->titles_tbl ." SET probability=".$test['probability']." WHERE id=".$test['id'];
                        $wpdb->query($sql);
                    }
                }

                delete_transient($transient_key);
            }

            // We pick a random number and then loop
            // through our tests and check if the number is
            // less than the sum of all the previous probabilties
            // that we've checked so far. It works - test it. :)

            mt_srand(); // no need to do this http://php.net/manual/en/function.mt-srand.php
            $total_probability = 0;
            foreach ($result as &$t) {
                $total_probability += intval($t['probability']);
            }
            $rand = mt_rand(0, $total_probability);
            $total = 0;
            foreach ($result as &$t) {
                if ($rand < ($total + $t['probability'])) {
                    break;
                }
                $total += $t['probability'];
            }
            $result = $t;
        } elseif (count($result) == 1) {
            $result = $result[0];
        }

        if ($result) {
            if (!isset($GLOBALS['__wpex-title'])) {
                $GLOBALS['__wpex-title'] = array();
            }
            $GLOBALS['__wpex-title'][$id] = $result;
            $title_id = $result['id'];
            $title = $result['title'] == "__WPEX_MAIN__" ? $title : $result['title'];

            // If this isn't the post/page and the user hasn't seen this title before, count
            // it as an impression
            $impressions_arr = $this->get('wpex_impressed', 'WPEX');
            if (!is_array($impressions_arr)) {
                $impressions_arr = array();
            }
            if (!($viewed || is_single($id) || is_page($id)) && !in_array($title_id, $impressions_arr)) {
                $time = strtotime("midnight");
                $this->delta_stats($title_id, $id, $time, 1, 0);
                $sql = "UPDATE " . $this->titles_tbl ." SET impressions=impressions+1 WHERE id=".$title_id;
                $wpdb->query($sql);

                $impressions_arr[] = $title_id;
                $this->set('wpex_impressed', "WPEX", $impressions_arr);
            }
            $this->set("title", $id, $result['id']);

            if (in_array($title_id, $impressions_arr)) {
                // If this is "the page/post and we found the title from
                // the user's session, that means they saw the title elsewhere
                // and are now viewing the page - count it as a view
                if ($from_cookie && ($viewed || is_single($id) || is_page($id))) {
                    $this->viewed($id, $title_id);
                }
            }
        }

        return stripslashes($title);
    }

    public function enqueue()
    {
        // Enqueue jQuery Cookie
        wp_enqueue_script("jquery-cookie", plugins_url('/js/jquery.cookie.js', __FILE__), array("jquery"), '1.4.1');

        // Register the script first.
        wp_register_script('wpextitles', plugins_url('/js/titles.js', __FILE__), array("jquery"), "9.0");

        // Now we can localize the script with our data.
        $data = array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'hide_body' => $this->get_option("wpex_hide_body", false)
        );
        wp_localize_script('wpextitles', 'wpex', $data);
        // The script can be enqueued now or later.
        wp_enqueue_script('wpextitles');
    }

    public function admin_enqueue()
    {
        wp_enqueue_style('wpexcss', plugins_url('css/wpex.css', __FILE__), array(), "9.0.1");
        wp_enqueue_script('wpexjs', plugins_url('js/wpex.js', __FILE__), array('jquery'), "9.0.1");
        wp_enqueue_script('jquery.sparkline.min.js', plugins_url('js/jquery.sparkline.min.js', __FILE__), array('jquery'), "0.0.1");
        wp_enqueue_script('jquery.qtip.min.js', plugins_url('js/jquery.qtip.min.js', __FILE__), array('jquery'), "0.0.1");
        wp_enqueue_style('jquery.qtip.min.css', plugins_url('css/jquery.qtip.min.css', __FILE__));
    }

    /**
     * Add meta box when the post has a block
     */
    public function add_meta_box()
    {
        global $post;
        add_meta_box('wpex-meta-box', __('Title Experiments', 'wpex'), array($this, 'meta_box'), $post->post_type, 'normal', 'high');
    }

    /**
     * Show meta box
     */
    public function meta_box($post, $box, $reload = false)
    {
        global $wpdb;

        $sql = "SELECT * FROM ".$this->titles_tbl." WHERE enabled=1 AND post_id=".$post->ID;
        $results = $wpdb->get_results($sql, ARRAY_A);

        $so_title = str_replace("'", "\\'", $post->post_title);

        $adjust_every = $this->get_option("wpex_adjust_every", 300);

        if (!$reload && $adjust_every >= 0 && (($results[0]['last_updated'] + $adjust_every) < $this->now)) {
            //we need to fetch the titles
            $this->titles($post->post_title, $post->ID, true);
            return $this->meta_box($post, $box, true);
        }

        foreach ($results as $idx => &$test) {
            $sql = "SELECT * FROM ".$this->stats_tbl." WHERE title_id=".$test['id'];
            $stat_results = $wpdb->get_results($sql, ARRAY_A);
            $stats = array();
            foreach ($stat_results as $s) {
                $stats[$s['ts']] = array(
                    'clicks' => $s['clicks'],
                    'impressions' => $s['impressions']
                );
            }
            $data = $this->get_sl_data($stats);
            $test['stats_str'] = join(",", $data);
            $test['title'] = stripslashes($test['title']);
            if ($test['thumbnail_id']) {
                $test['thumbnail'] = wp_get_attachment_url(intval($test['thumbnail_id']));
            }
        }

        $rows = $results;

        $sql = "SELECT * FROM ".$this->titles_tbl." WHERE NOT enabled=1 AND post_id=".$post->ID;
        $results = $wpdb->get_results($sql, ARRAY_A);
        $so_title = str_replace("'", "\\'", $post->post_title);
        foreach ($results as $idx => &$test) {
            $test['probability'] = 0;
            $test['stats_str'] = join(",", $data);
            $test['title'] = stripslashes($test['title']);
        }

        $rows = array_merge($rows, $results);

        echo "<script type='text/javascript'>";
        echo "_wpex_data = " . json_encode($rows)."\n";
        echo "</script>";

        // Add an nonce field so we can check for it later.
        wp_nonce_field('titlexp_meta_box', 'titlexp_meta_box_nonce');
    }

    /**
    * Get the last seven days of sparkline data
    **/
    public function get_sl_data($data)
    {
        $arr = array(0,0,0,0,0,0,0);
        if (!is_array($data)) {
            return $arr;
        }

        $today = strtotime("today");
        for ($i=0;$i<7;$i++) {
            $d = $today-(24*60*60*$i);
            $arr[$i] = isset($data[$d]) ? $data[$d]['clicks'] : 0;
        }
        return array_reverse($arr);
    }

    /**
     * Save the blocks
     *
     * @param int $post_id
     */
    public function save_blocks($post_id)
    {
        global $wpdb;
        if ((isset($_POST['ID']) && $post_id != $_POST['ID']) ||
            (isset($_POST['id']) && $post_id != $_POST['id'])) {
            // this is the result of the 'Preview' button being clicked and will cause problems with our titles!
            return;
        }

        // Check if our nonce is set.
        if (! isset($_POST['titlexp_meta_box_nonce'])) {
            return;
        }
        // Verify that the nonce is valid.
        if (! wp_verify_nonce($_POST['titlexp_meta_box_nonce'], 'titlexp_meta_box')) {
            return;
        }

        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (isset($_POST['wpex-titles'])) {
            //Ensure the main title is in the DB
            $sql = "SELECT COUNT(*) FROM " . $this->titles_tbl . " WHERE post_id=".$post_id." AND title='__WPEX_MAIN__';";
            $count = $wpdb->get_col($sql);
            if ($count[0] == 0) {
                $wpdb->insert($this->titles_tbl, array("title"=>"__WPEX_MAIN__", "post_id"=>$post_id));
            }

            $new_titles = array();
            $existing_titles = array();
            foreach ($_POST['wpex-titles'] as $key => $val) {
                $enabled = isset($_POST['wpex-enabled']) && isset($_POST['wpex-enabled'][$key]) ? true : false;
                if ($key[0] == "_") {
                    //Update
                    $wpdb->update($this->titles_tbl, array("title"=>$val, "post_id"=>$post_id, "enabled"=>$enabled), array("id"=>substr($key, 1)));
                    $existing_titles[] = substr($key, 1);
                } else {
                    //Insert
                    $wpdb->insert($this->titles_tbl, array("title"=>$val, "post_id"=>$post_id, "enabled"=>$enabled));
                    $new_titles["_" . $key] = $wpdb->insert_id;
                }
            }
        }

        if (count($new_titles) && count($existing_titles)) {
            $total_title_count =  count($new_titles) + count($existing_titles) + 1; // the one is for the original title
            /* what all the titles would be in they were new */
            $base_probability = 1/$total_title_count;

            /* the share of the remaining probability to normalize the existing ones */
            $exisiting_probability_chunk = 1 - $base_probability * count($new_titles);

            foreach ($new_titles as $new_title_id) {
                $wpdb->update($this->titles_tbl, array("probability"=> round($base_probability*100)), array("id"=>$new_title_id));
            }
        }

        // Store this for *anyone* who may like to use it
        $GLOBALS['__wpex-new-titles'] = $new_titles;

        if (isset($_POST['wpex-removed'])) {
            if (empty($_POST['wpex-titles'])) {
                // they deleted all the titles, just delete them all
                $wpdb->delete($this->titles_tbl, array("post_id"=>$post_id));
                $wpdb->delete($this->stats_tbl, array("post_id"=>$post_id));
            } else {
                foreach (sanitize_text_field(($_POST['wpex-removed'])) as $val) {
                    $wpdb->delete($this->titles_tbl, array("id"=>$val, "post_id"=>$post_id));
                    $wpdb->delete($this->stats_tbl, array("title_id"=>$val, "post_id"=>$post_id));
                }
            }
        }

        // We are never adjusting, so we need to equalize the titles
        $adjust_every = $this->get_option("wpex_adjust_every", 300);
        if ($adjust_every == -1) {
            $sql = "SELECT COUNT(*) FROM " . $this->titles_tbl . " WHERE post_id=".$post_id." AND enabled";
            $count = $wpdb->get_var($sql);
            if ($count > 0) {
                $wpdb->update($this->titles_tbl, array("probability"=>round(100/$count)), array("post_id"=>$post_id, "enabled"=>1));
            }
        }
    }

    public function is_bot()
    {
        global $_ROBOT_USER_AGENTS;
        $is_bot = $this->get('wpex_is_bot', 'WPEX');
        if ($is_bot !== null) {
            return $is_bot;
        }

        $ua = $_SERVER['HTTP_USER_AGENT'];
        foreach ($_ROBOT_USER_AGENTS as $agent) {
            if (preg_match("/".$agent."/i", $ua)) {
                $this->set('wpex_is_bot', "WPEX", true);
                return true;
            }
        }

        $this->set('wpex_is_bot', "WPEX", false);
        return false;
    }

    public function get_winner($id)
    {
        global $wpdb;

        $sql = "SELECT clicks,impressions,id FROM " . $this->titles_tbl . " WHERE impressions > 1 AND post_id=".$id;
        $results = $wpdb->get_results($sql, ARRAY_N);

        // Sort them by the probality(zscore)
        usort($results, array($this, '_prob_sort'));

        // Compare the first one against the rest down the line to
        // find out where our statistical difference is
        for ($i = 1; $i < count($results); $i++) {
            $rA = $results[0];
            $rB = $results[$i];
            if ($this->_prob_sort($rA, $rB) != 0) {
                break;
            }
        }

        //Get the winner's id
        $winners = array();
        for ($x=0;$x<$i;$x++) {
            $winners[] = $results[$x][2];
        }

        return $winners;
    }

    // Find the winner of between two tests
    public function _winner($cA, $iA, $cB, $iB, $raw = false)
    {
        $res = $this->cumnormdist($this->zscore(array($iA, $cA), array($iB, $cB)));

        if ($raw) {
            return $res;
        }

        if ($res > 0.95) {
            return 1;
        } elseif ($res < 0.05) {
            return -1;
        } else {
            return 0;
        }
    }

    // Use the winner function to sort an array of array(click,impressions) members
    public function _prob_sort($a, $b)
    {
        return $this->_winner($a[1], $a[0], $b[1], $b[0]);
    }

    // Based on code from https://developer.amazon.com/sdk/ab-testing/reference/ab-math.html
    public function conf_int($c, $i)
    {
        if ($c == 0 || $i == 0) {
            return 100;
        }
        $sample = $i;
        $probabilty = $c/$i;
        $standard_error = $this->standard_error($probabilty, $sample);
        return round($standard_error*1.65*100, 2);
    }
    public function standard_error($prob, $sample)
    {
        if ($sample == 0) {
            return 0;
        }
        return sqrt(($prob*(1-$prob)) / $sample);
    }

    // (((((((((((((((((((((((((((((())))))))))))))))))))))))))))))
    // From http://abtester.com/calculator/
    // (((((((((((((((((((((((((((((())))))))))))))))))))))))))))))
    // Calculation of the conversion rate
    public function cr($t)
    {
        if ($t[0] == 0) {
            return 0;
        }
        return $t[1]/$t[0];
    }

    //Calculation of the z-score
    public function zscore($c, $t)
    {
        $z = $this->cr($t)-$this->cr($c);
        $s = ($this->cr($t)*(1-$this->cr($t)))/$t[0] + ($this->cr($c)*(1-$this->cr($c)))/$c[0];
        return $z/sqrt($s);
    }

    //Calculation of the cumulative normal distribution.
    public function cumnormdist($x)
    {
        $b1 =  0.319381530;
        $b2 = -0.356563782;
        $b3 =  1.781477937;
        $b4 = -1.821255978;
        $b5 =  1.330274429;
        $p  =  0.2316419;
        $c  =  0.39894228;

        if ($x >= 0.0) {
            $t = 1.0 / (1.0 + $p * $x);
            return (1.0 - $c * exp(-$x * $x / 2.0) * $t *
          ($t *($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
        } else {
            $t = 1.0 / (1.0 - $p * $x);
            return ($c * exp(-$x * $x / 2.0) * $t *
          ($t *($t * ($t * ($t * $b5 + $b4) + $b3) + $b2) + $b1));
        }
    }


    // Out simpson rule integral approximation f(x)
    public function simpsonf($x)
    {
        $prod = 1;
        foreach ($this->statTests as $id=>$test) {
            if ($id == $this->statChecking) {
                $prod *= $test['bd']->_getPDF($x);
            } else {
                $prod *= $test['bd']->_getCDF($x);
            }
        }
        // returns f(x) for integral approximation with composite Simpson's rule
        return $prod;
    }

    // Implementation of Simpsons Rule for integral approximations
    // From: http://www.php.net/manual/en/ref.math.php#61377
    public function simpsonsrule()
    {
        $a = 0;
        $b = 1;
        $n = 1000;
        // approximates integral_a_b f(x) dx with composite Simpson's rule with $n intervals
        // $n has to be an even number
        // f(x) is defined in "function simpsonf($x)"
        if ($n%2==0) {
            $h=($b-$a)/$n;
            $S=$this->simpsonf($a)+$this->simpsonf($b);
            $i=1;
            while ($i <= ($n-1)) {
                $xi=$a+$h*$i;
                if ($i%2==0) {
                    $S=$S+2*$this->simpsonf($xi);
                } else {
                    $S=$S+4*$this->simpsonf($xi);
                }
                $i++;
            }
            return($h/3*$S);
        } else {
            return('$n has to be an even number');
        }
    }
}
