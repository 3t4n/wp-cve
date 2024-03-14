<?php

/*
 * @link http://www.apoyl.com/
 * @since 1.0.0
 * @package Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/public
 * @author 凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class Apoyl_Baidupush_Public
{

    private $plugin_name;

    private $version;

    public function __construct($plugin_name, $version)
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/apoyl_baidupush_public.css', array(), $this->version, 'all');
    }

    public function enqueue_scripts()
    {
        wp_enqueue_script('jquery');
    }

    public function footer()
    {

        $arr = get_option('apoyl-baidupush-settings');
        if (isset($arr['site']) && isset($arr['secret'])) {
            $a = explode('/', home_url());
            $url = urlencode($a[0] . '//' . $a[2] . $_SERVER["REQUEST_URI"]);
            $aid = get_the_ID();
            $ajaxurl = admin_url('admin-ajax.php');
            $nonce = wp_create_nonce('ajaxpush_nonce');
            require_once plugin_dir_path(__FILE__). 'partials/apoyl-baidupush-public-display.php';
            $file=apoyl_baidupush_file('autopush');
            if($file){
            	include $file;
            }
        }
    }
    


    public function ajaxpush()
    {
        global $wpdb;
        if (! check_ajax_referer('ajaxpush_nonce'))
            exit();
        $aid = (int) $_POST['aid'];
        $subject = sanitize_text_field($_POST['subject']);
        $url = sanitize_url(urldecode($_POST['url']));
        
        if ($aid <= 0)
            exit();
        $arr = get_option('apoyl-baidupush-settings');
        
        if (! $arr['site'])
            exit();
        if (! $arr['secret'])
            exit();
        
        $row = (array) $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'apoyl_baidupush WHERE aid=' . $aid);


        require_once plugin_dir_path(__FILE__). 'api/BaiduPush.php';
        $baidupush = new BaiduPush();
        echo $baidupush->push($row, $aid, $subject, $url);
        exit();
    }

    public function push($content)
    {
        global $wpdb;
        $arr = get_option('apoyl-baidupush-settings');
        isset($_POST['aid'])?$aid = (int) $_POST['aid']:$aid=0;
        $str='';
        if (is_single() && is_user_logged_in()) {
            $aid = get_the_ID();
            $row = (array) $wpdb->get_row('SELECT * FROM ' . $wpdb->prefix . 'apoyl_baidupush WHERE aid=' . $aid);
            if ($row && $row['ispush'] == 1)
                $str = '<img src="' . plugin_dir_url(__FILE__) . 'img/baidu.png" width=20 height=20 style="vertical-align:text-bottom;" title="' . __('pushsuccess', 'apoyl-baidupush') . '"/>';
            else
                $str = '<div><a class="apoyl_baidupush_btn" style="cursor:pointer;" attraid="'.$aid.'">' . __('pushbaidu', 'apoyl-baidupush') . '</a>  <span class="apoyl_baidupush_tips" style="color:red;"></span></div>';
        }
        return $str . $content;
    }
}