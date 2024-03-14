<?php


/**
 *
 */

/**
 * Class IMGSPY_Admin
 */

class IMGSPY_Admin
{
    public static $debug = false;

    public static function init()
    {


        register_deactivation_hook(IMGSPY_BASE_FILE, array(__CLASS__, 'plugin_deactivate'));
        add_action('init', array(__CLASS__, 'wp_init'));

        if (is_admin()) {

            //add_filter('use_block_editor_for_post_type','__return_false',10,2);
            add_action('media_buttons', array(__CLASS__, 'add_media_button'), 20);

            add_action('admin_head-post.php', array(__CLASS__, 'admin_head'));
            add_action('admin_head-post-new.php', array(__CLASS__, 'admin_head'));

            add_action('admin_head', array(__CLASS__, 'admin_head_mark'));
            add_action('save_post', array(__CLASS__, 'save_post'), 10, 3);
        }

        WB_IMGSPY_Conf::init();

        WB_IMGSPY_Ajax::init();


        add_action('wb_imgspy_auto_save_image', array(__CLASS__, 'wb_imgspy_auto_save_image'));

        if (!wp_next_scheduled('wb_imgspy_auto_save_image')) {
            wp_schedule_event(strtotime(current_time('mysql', 1)), 'hourly', 'wb_imgspy_auto_save_image');
        }

        WB_IMGSPY_Image::init_watermark();
    }

    public static function plugin_activate()
    {
    }

    public static function plugin_deactivate()
    {
        wp_clear_scheduled_hook('wb_imgspy_auto_save_image');
        wp_clear_scheduled_hook('wb_imgspy_watermark_image');
    }

    public static function txt_log($msg)
    {

        if (!self::$debug) {
            return;
        }
        $num = func_num_args();
        if ($num > 1) {
            $msg = json_encode(func_get_args());
        } else if (is_array($msg)) {
            $msg = json_encode($msg);
        } else if (is_object($msg)) {
            $msg = json_encode($msg);
        }

        error_log('[' . current_time('mysql') . ']' . $msg . "\n", 3, IMGSPY_PATH . '/#log/' . date('Ym') . '.log');
    }

    public static function open_types()
    {
        global $wp_post_types;
        $post_types = array();
        if ($wp_post_types && is_array($wp_post_types)) foreach ($wp_post_types as $type) {
            if ($type->public) {
                $post_types[] = $type->name;
            }
        }

        return $post_types;
    }

    public static function deleteImageLink($post_ID, $post)
    {
        global $wpdb;
    }

    public static function save_post($post_ID, $post, $update)
    {

        static $post_ids = array();

        if (!$update) {
            return;
        }

        if (isset($_POST['data']) && isset($_POST['data']['wp_autosave'])) {
            return;
        }

        if (!in_array($post->post_type, self::open_types())) {
            return;
        }

        if (in_array($post->post_status, array('auto-draft', 'inherit'))) {
            return;
        }

        if (isset($post_ids[$post_ID])) {
            return;
        }

        //自动保存图片
        $cnf = WB_IMGSPY_Conf::opt();
        if ($cnf['del_src_url']) {
            //del_src_url
            self::deleteImageLink($post_ID, $post);
        }

        if ($cnf['mode']) {
            return;
        }

        if ($post->post_status == 'trash') {
            self::remove_auto_save_image($post_ID);
            return;
        }

        $post_ids[$post_ID] = 1;

        //self::txt_log($post->post_type);

        $find_img_html = array();

        $img_list = WB_IMGSPY_Post::find_img_src($post, $find_img_html);

        //self::txt_log($img_list);

        if (!$img_list || empty($img_list)) {
            self::remove_auto_save_image($post_ID);
            return;
        }

        update_post_meta($post_ID, 'wb_imgspy_auto_save_image', 1);
    }

    public static function remove_auto_save_image($post_id)
    {
        delete_post_meta($post_id, 'wb_imgspy_auto_save_image');
    }

    public static function post_is_edit_lock($post_id)
    {
        $lock = get_post_meta($post_id, '_edit_lock', true);
        if (!$lock) {
            return false;
        }
        $lock = explode(':', $lock);
        $time = $lock[0];

        $time_window = apply_filters('wp_check_post_lock_window', 150);

        if ($time && $time > time() - $time_window) {
            return true;
        }
        return false;
    }

    public static function wb_imgspy_auto_save_image()
    {
        global $wpdb;

        $cnf = WB_IMGSPY_Conf::opt();
        if ($cnf['mode']) {
            return;
        }
        WB_IMGSPY_Down::set_proxy();

        add_filter('big_image_size_threshold', function ($threshold) {
            return 0;
        });

        $time_start = time();
        $page = -1;
        $num = 5;
        $save_fail = [];
        do {
            $page++;
            if ($page > 10) {
                break;
            }
            $sql = "SELECT a.ID,a.post_title,a.post_content,b.meta_id FROM $wpdb->posts a,$wpdb->postmeta b WHERE a.ID=b.post_id AND b.meta_key='wb_imgspy_auto_save_image' AND b.meta_value='1'";
            if ($save_fail) {
                $sql .= " AND a.ID NOT IN(" . implode(',', $save_fail) . ")";
            }
            $posts = $wpdb->get_results($sql . " LIMIT $num");

            if (!$posts) {
                break;
            }
            foreach ($posts as $post) {
                if (self::post_is_edit_lock($post->ID)) {
                    continue;
                }
                $find_img_html = array();
                $img_list = WB_IMGSPY_Post::find_img_src($post, $find_img_html);
                if (!$img_list || empty($img_list)) {
                    self::remove_auto_save_image($post->ID);
                    continue;
                }

                $success_list = array();
                $all_success = 1;
                $upload_errors = [];
                foreach ($img_list as $key => $img) {

                    $ret = WB_IMGSPY_Post::upload($img, $post->ID, false);
                    if ($ret && isset($ret['id'])) {
                        $success_list[$key] = $ret;
                    } else {
                        $save_fail[] = $post->ID;
                        $upload_errors[] = [$img, WB_IMGSPY_Post::$last_err];
                        $all_success = 0;
                    }
                }

                if ($success_list) {
                    WB_IMGSPY_Post::update_post($post->ID, $post, 1, $success_list, $find_img_html);
                }
                if ($all_success) {
                    self::remove_auto_save_image($post->ID);
                } else {
                    update_post_meta($post->ID, 'wb_imgspy_auto_save_image', '2');
                }
                if ($upload_errors) {
                    update_post_meta($post->ID, 'imgspy_errors', $upload_errors);
                }
            }
            $time_now = time();
        } while (1);
    }

    public static function auto_save_image($post_ID, $post, $update)
    {
    }


    public static function admin_head_mark()
    {
        echo '<meta name="wb_marker" content="' . esc_attr(get_option('wb_imgspider_ver', 0)) . '"/>';
    }

    public static function admin_head()
    {

        $inset_ext_btn = '';

        if (self::is_active_gutenberg_editor()) {

            // wp_enqueue_style(
            //     'wb_block_editor_imgscrapy',
            //     IMGSPY_URI . 'assets/wb_block_editor.css',
            //     array('wp-edit-blocks'),
            //     IMGSPY_VERSION
            // );

            wp_enqueue_script(
                'wbp-imgscrapy',
                IMGSPY_URI . 'assets/block/wb_block.js',
                array('lodash', 'wp-components', 'wp-compose', 'wp-core-data', 'wp-data', 'wp-edit-post', 'wp-element', 'wp-plugins', 'wp-polyfill'),
                IMGSPY_VERSION
            );

            $inset_ext_btn = "document.addEventListener('DOMContentLoaded', function () {document.querySelector('body').insertAdjacentHTML('beforeEnd','<button id=\"wb-wbsm-btn-spy-ext\" style=\"display:none;\" type=\"button\"></button>');})";
        }

        wp_enqueue_style('wbp-admin-style-imgspy', IMGSPY_URI . 'assets/wbp_admin_imgspy.css', array(), IMGSPY_VERSION);

        wp_register_script('wbs-imgspy-inline-js', false, null, false);
        wp_enqueue_script('wbs-imgspy-inline-js');

        $ajax_nonce = wp_create_nonce('wp_ajax_wb_imgspider');
        $imgspider_ver = get_option('wb_imgspider_ver', 0);
        $config_url = admin_url('/admin.php?page=imgspider_pack#/extension');


        wp_add_inline_script(
            'wbs-imgspy-inline-js',
            'var imgspy_cnf=' . json_encode(WB_IMGSPY_Conf::opt()) . ',
		    wb_ajaxurl=\'' . admin_url('admin-ajax.php') . '\',
		    imgspider_ver=' . $imgspider_ver . ',
		    _wb_imgspider_ajax_nonce=\'' . $ajax_nonce . '\',
		    imgspider_pro_url=\'' . $config_url . '\';'
                . "\n"
                . $inset_ext_btn,
            'before'
        );
    }

    public static function wp_init()
    {
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
            return;

        if (is_admin() && get_user_option('rich_editing') == 'true') {
            add_filter('mce_external_plugins', array(__CLASS__, 'mce_external_plugins_paste_image'), 100);
        }
    }

    public static function mce_external_plugins_paste_image($plugin_array)
    {

        $plugin_array['wb_imgspy'] = IMGSPY_URI . 'assets/wbp_admin_imgspy.js';

        return $plugin_array;
    }


    public static function add_media_button()
    {


        $html = '<button id="wb-wbsm-btn-spy" type="button" class="button wb-wbsm-btn-spy"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"><g fill="#999" fill-rule="evenodd"><path d="M6 16h4v-2H6zM6 2h4V0H6zM14 10h2V6h-2zM0 10h2V6H0zM12 8H9V4H7v4H4l4 4zM2 2h2V0H1C.4 0 0 .4 0 1v3h2V2zM15 0h-3v2h2v2h2V1c0-.6-.4-1-1-1M14 14h-2v2h3c.6 0 1-.4 1-1v-3h-2v2zM2 12H0v3c0 .6.4 1 1 1h3v-2H2v-2z"/></g></svg><span>保存站外图片</span></button>';
        $html .= '<button id="wb-wbsm-btn-spy-ext" style="display:none;" type="button" class="button wb-wbsm-btn-spy"></button>';
        echo $html;
    }


    /**
     * 是否启用古腾堡
     * @return bool
     */
    public static function is_active_gutenberg_editor()
    {
        if (function_exists('is_gutenberg_page') && is_gutenberg_page()) {
            return true;
        }

        global $current_screen;
        $current_screen = get_current_screen();
        if (method_exists($current_screen, 'is_block_editor') && $current_screen->is_block_editor()) {
            return true;
        }
        return false;
    }
}
