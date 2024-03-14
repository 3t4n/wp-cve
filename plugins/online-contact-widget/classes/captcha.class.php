<?php
/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Captcha
{


    private static $result = null;


    public static function result()
    {
        return self::$result;
    }

    public static function opt($key = null)
    {
        /*[
            'type' => 'none',
            'google' => [
                'public' => '',
                'private' => '',
                'score' => 0.5,
            ],
        ]*/
        return OCW_Admin::opt('items_data.msg.captcha');
    }

    public static function init()
    {
        add_action('wp_ajax_owc_recaptcha', array(__CLASS__, 'wp_ajax_wb_recaptcha'));
        add_action('wp_ajax_nopriv_owc_recaptcha', array(__CLASS__, 'wp_ajax_wb_recaptcha'));
    }


    public static function wp_ajax_wb_recaptcha()
    {
        global $wpdb, $current_user;
        switch ($_REQUEST['op']) {
            case 'captcha':
                require_once __DIR__ . '/image_captcha.php';
                header("Content-type: image/gif");
                $font = ONLINE_CONTACT_WIDGET_PATH . '/assets/fonts/consolas-webfont.ttf';
                $imagecode = new OCW_Imagecode(90, 39, 4, '', $font);//
                $imagecode->imageout();
                break;
            case 'verify':
                $ret = ['code' => 0, 'desc' => 'success'];

                self::verify();
                if (self::$result) {
                    //ret['data']['score']
                    $ret = self::$result;
                }
                header('content-type:text/json;charset=utf-8');
                echo json_encode($ret);
                break;
        }
        exit();
    }

    public static function verify()
    {
        $cnf = self::opt('captcha');
        if (!$cnf || !is_array($cnf)) {
            return false;
        }
        if (!isset($cnf['type'])) {
            return false;
        }
        $state = false;
        switch ($cnf['type']) {
            case 'google':
                $state = self::google($cnf);
                break;
            default:
                $state = self::base($cnf);
                break;
        }
        return $state;
    }

    public static function base($cnf)
    {
        $result = ['code' => 1, 'desc' => 'fail2'];
        do {
            //验证码验证
            if (!isset($_POST['ocw_captcha']) || empty($_POST['ocw_captcha'])) {
                $result['desc'] = '验证码不能为空';
                break;
            }
            $captcha = strtolower(trim(sanitize_text_field($_POST['ocw_captcha'])));
            session_start();
            $session_captcha = strtolower($_SESSION['ocw_captcha']);
            if ($captcha != $session_captcha) {
                $result['desc'] = '验证码错误，请重新输入';
                break;
            }
            $result['code'] = 0;
            $result['desc'] = 'success';

        } while (0);

        self::$result = $result;

        $result = apply_filters('ocw_captcha_verify_result', $result);

        return !$result['code'];

    }

    public static function google($cnf)
    {
        $result = ['code' => 1, 'desc' => 'fail'];
        do {
            if (!isset($_POST['ocw_captcha']) || empty($_POST['ocw_captcha'])) {
                $result['desc'] = 'empty recaptcha token';
                break;
            }
            if (!isset($cnf['google']) || !is_array($cnf['google'])) {
                $result['desc'] = 'empty recaptcha config';
                break;
            }
            $config = $cnf['google'];
            if (!isset($config['private']) || empty($config['private'])) {
                $result['desc'] = 'empty recaptcha private key';
                break;
            }

            $body = ['secret' => $config['private'], 'response' => trim(sanitize_text_field($_POST['ocw_captcha']))];
            $api = 'https://www.recaptcha.net/recaptcha/api/siteverify';
            $param = array(
                'timeout' => 5,
                'verifyssl' => false,
                'headers' => array(
                    'user-agent' => 'Wordpress ' . get_bloginfo('version') . ' / wbolt.com'
                ),
                'body' => $body
            );
            $http = wp_remote_post($api, $param);
            if (is_wp_error($http)) {
                $result['desc'] = $http->get_error_message();
                break;
            }
            $body = wp_remote_retrieve_body($http);
            //error_log($body."\n",3,__DIR__.'/log.txt');
            $data = json_decode($body);
            $result['data'] = $data;
            if (!$data || !$data->success) {
                $result['desc'] = 'recaptcha fail';
                break;
            }
            $score = isset($config['score']) ? floatval($config['score']) : 0.5;
            if (!isset($data->score) || $data->score < $score) {
                $result['desc'] = 'reCAPTCHA验证失败，请稍后再试';
                break;
            }
            $result['code'] = 0;
            $result['desc'] = 'success';

        } while (0);

        self::$result = $result;

        $result = apply_filters('ocw_captcha_verify_result', $result);


        return !$result['code'];
    }

}