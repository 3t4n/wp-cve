<?php
/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */

class OCW_Mail
{
    public static $mail_error = null;
    public static $mail_cnf = null;


    public function __construct()
    {

    }


    public static function use_theme_mail()
    {
        return class_exists('WB_Admin_Mail');
    }

    public static function mail()
    {
        return OCW_Admin::opt('items_data.msg.mail');
    }


    public static function init()
    {
        add_action('ocw_new_concat',array(__CLASS__,'ocw_new_concat'));
        add_action('ocw_mail_send',array(__CLASS__,'ocw_mail_send'),10,1);
    }

    public static function ocw_mail_send($pid)
    {
        global $wpdb;

        $cnf = self::mail();

        $to = isset($cnf['to']) ? $cnf['to']: '' ;
        if(!$to){
            return;
        }


        $t = $wpdb->prefix.'ocw_contact';

        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $t WHERE id=%d",$pid));
        if(!$row){
            return;
        }

        $conf = OCW_Contact::conf();
        $type = $conf['type'];

        //收到{$var1}的留言。
        $msg = [$row->name];
        if($type && isset($type[$row->type])){
            $msg[] = '关于'.$type[$row->type];
        }
        $subject = '收到'.implode('',$msg).'的留言';

        $body = '<p>'.$subject.'</p>';
        foreach (['email'=>'邮箱','qq'=>'QQ','wx'=>'微信','mobile'=>'手机'] as $k=>$v){
            if(isset($row->{$k}) && $row->{$k}){
                $body .= '<p>'.$v.'：'.$row->{$k}.'</p>';
                break;
            }
        }
        $body .= '<p>时间：'.$row->create_date.'</p>';
        $t_content = $wpdb->prefix.'ocw_contact_content';
        $content = $wpdb->get_var($wpdb->prepare("SELECT `content` FROM $t_content WHERE pid=%d ORDER BY cid ASC LIMIT 1",$pid));
        if($content){
            $body .= '<p>'.$content.'</p>';
        }
        $to_list = explode(',',$to);
        if(!self::use_theme_mail()){
            add_action('phpmailer_init', array(__CLASS__, 'bind_smtp_action'));
            add_filter('wp_mail', array(__CLASS__, 'bind_wp_mail_headers'));
        }

        foreach($to_list as $mail_to){
            wp_mail($mail_to, $subject, $body);
        }
    }


    public static function ocw_new_concat($pid)
    {

        global $wpdb;

        $notice = OCW_Admin::opt('items_data.msg.notice');
        if(!$notice || !is_array($notice) || !in_array('mail',$notice)){
            return;
        }

        $time = current_time('timestamp',1) + 5;
        wp_schedule_single_event($time,'ocw_mail_send',array($pid));
    }


    public static function do_mail_test(){
        $to = isset($_POST['to']) ? sanitize_text_field(trim($_POST['to'])) : '';

        $ret = array('code'=>1,'desc'=>'邮件发送失败');

        do{
            if(!$to || !strpos($to,'@')){
                $ret['desc'] = '不是有效的邮箱地址';
                break;
            }
            if(!isset($_POST['mail']) || empty($_POST['mail']) || !is_array($_POST['mail'])){
                $ret['desc'] = '邮箱配置无效';
                break;
            }
            add_action('wp_mail_failed', function ($error){
                self::$mail_error = $error;
            });
            $wb_mail = $_POST['mail'];
            self::$mail_cnf = $wb_mail;
            if($wb_mail['mailer']){
                if(!isset($wb_mail['smtp']) || empty($wb_mail['smtp'])){
                    $ret['desc'] = '邮箱SMTP配置参数不能为空';
                    break;
                }
                $smtp = $wb_mail['smtp'];
                if(!$smtp['host'] || !$smtp['user'] || !$smtp['password']){
                    $ret['desc'] = '邮箱SMTP配置参数不能为空';
                    break;
                }
                add_action('phpmailer_init', function ($mail){
                    $smtp = self::$mail_cnf['smtp'];
                    $mail->isSMTP();
                    $mail->Host = $smtp['host'];
                    //$mail->SMTPDebug = 4;
                    $mail->SMTPAuth = true;

                    $mail->Username = $smtp['user'];
                    $mail->Password = $smtp['password'];
                    if ($smtp['secure']) {
                        $mail->SMTPSecure = $smtp['secure'];
                    }
                    if ($smtp['port']) {
                        $mail->Port = $smtp['port'];
                    }
                });
            }
            if(isset($wb_mail['from']) && $wb_mail['from'] && isset($wb_mail['name']) && $wb_mail['name']){
                add_filter('wp_mail', function ($attr){
                    $wb_mail = self::$mail_cnf;
                    if (!isset($attr['headers']) || empty($attr['headers'])) {
                        $headers = array();
                        $headers[] = 'from:' . $wb_mail['name'] . ' <' . $wb_mail['from'] . '>';
                        $headers[] = 'content-type:text/html; charset=UTF-8';
                        $attr['headers'] = $headers;
                    }
                    return $attr;
                });
            }


            $send_ret = wp_mail( $to, "测试邮件" , '这是一封测试邮件 -- By OCW' );

            if($send_ret){
                $ret['code'] = 0;
                $ret['desc'] = '邮件发送成功';

            }else{
                if(self::$mail_error && self::$mail_error instanceof  WP_Error){
                    $ret['desc'] = '邮件发送失败,'.self::$mail_error->get_error_message();
                }else{
                    $ret['desc'] = '邮件发送失败';
                }

            }


        }while(0);

        return $ret;
    }


    public static function bind_wp_mail_headers($attr)
    {
        $wb_mail = self::mail();
        if (!isset($attr['headers']) || empty($attr['headers'])) {
            $headers = array();
            $headers[] = 'from:' . $wb_mail['name'] . ' <' . $wb_mail['from'] . '>';
            $headers[] = 'content-type:text/html; charset=UTF-8';
            $attr['headers'] = $headers;
        }
        return $attr;
    }

    public static function bind_smtp_action($mail)
    {
        $wb_mail = self::mail();
        if (!$wb_mail['mailer']) {
            return;
        }
        if(!isset($wb_mail['proc'])){
            return;
        }
        $proc = $wb_mail['proc'];
        if(!isset($proc[$wb_mail['mailer']])){
            return;
        }

        $smtp = $proc[$wb_mail['mailer']];

        if (!$smtp['host'] || !$smtp['user'] || !$smtp['password']) {
            return;
        }
        $mail->isSMTP();
        $mail->Host = $smtp['host'];
        //$mail->SMTPDebug = 4;
        $mail->SMTPAuth = true;

        $mail->Username = $smtp['user'];
        $mail->Password = $smtp['password'];
        if ($smtp['secure']) {
            $mail->SMTPSecure = $smtp['secure'];
        }
        if ($smtp['port']) {
            $mail->Port = $smtp['port'];
        }
    }


    public static function send($to, $subject, $message)
    {

        //$wb_mail = self::mail();
        $ret = array('code' => 1, 'desc' => '邮件发送失败');
        do {
            if (!$to || !strpos($to, '@')) {
                $ret['desc'] = '不是有效的邮箱地址';
                break;
            }
            //$message = self::mail_html_tpl($subject,$message);
            $send_ret = wp_mail($to, $subject, $message);
            if ($send_ret) {
                $ret['code'] = 0;
                $ret['desc'] = '邮件发送成功';
            } else {
                $ret['desc'] = '邮件发送失败';
            }

        } while (0);

        return $ret;
    }


    public static function mail_html_tpl($subject,$message){

        $url = array();

        if(preg_match_all('#<a href="([^"]+)" class="wb_mail_url">([^<]+)</a>#is',$message,$match)){
            foreach($match[0] as $k=>$a){
                $message = str_replace($a,'',$message);
                $url[] = array($match[1][$k],$match[2][$k]);
            }
        };


        $site_logo_html = wb_opt('logo_url') ? '<img height="30" style="height:30px;" src="'. wb_opt('logo_url'). '" alt="'.get_option('blogname').'"/>' : '<strong style="font-size: 20px; color: #333; line-height: 40px;">'.get_option('blogname').'</strong>';
        $html = '<style>body{padding:0;margin:0;}a{color:#06c;text-decoration: none;}</style><table width="100%" border="0" cellpadding="0" cellspacing="0" style="width:100%;mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tbody><tr><td height="30" style="height: 30px;background-color:#F0F0F0;"></td></tr><tr><td align="center" valign="middle" style="text-align:center; vertical-align: middle; background-color:#F0F0F0;">';
        $html .= $site_logo_html;
        $html .= '<table width="600" border="0" cellpadding="0" cellspacing="0" style="width:600px;border-radius: 3px;background-color:#fff;border:1px solid #EAEAEA;text-align: left; margin:20px auto;mso-table-lspace: 0pt; mso-table-rspace: 0pt;"><tbody><tr><td width="95" style="width:95px; height: 35px;"></td><td></td><td width="95" style="width:95px;"></td></tr>';
        $html .= '<tr><td></td><td style="font-size: 14px; line-height: 32px; color:#666;">';
        $html .= '<p style="text-align: center; font-size: 20px; line-height: 26px; padding-bottom: 45px;">'.$subject.'</p>';

        $html .= '<p>'.$message.'</p>';

        if($url){
            $html .= '<p style="padding-top: 60px;">';
            foreach($url as $r){
                $html .= '<a style="display: block; height: 38px; line-height: 38px; text-align: center; font-size: 14px; color:#fff; background-color:#06c; border-radius: 5px;" href="'.$r[0].'">'.$r[1].'</a>';
            }
            $html .= '</p>';
        }

        $html .= '</td><td></td></tr><tr><td style="height: 60px;"></td><td></td><td></td></tr></tbody></table></td></tr><tr><td height="30" style="height:30px;"></td></tr></tbody></table>';

        return $html;


    }

}