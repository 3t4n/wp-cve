<?php
/**
 * Author: wbolt team
 * Author URI: https://www.wbolt.com
 */


class OCW_Sms
{

    public static function init()
    {

        //add_action();
        add_action('ocw_send_sms',function($mobile,$msg){
            self::send($mobile,['name'=>$msg]);
        },10,2);
        add_action('ocw_send_sms_test',function ($mobile,$cnf,$msg){
            //error_log(print_r([$mobile,$cnf,$msg],true),3,__DIR__.'/debug.txt');
            self::send($mobile,['name'=>$msg],$cnf);
        },10,3);

        //发送
        add_action('ocw_new_concat',array(__CLASS__,'ocw_new_concat'));

    }


    public static function ocw_new_concat($pid)
    {
        global $wpdb;

        $notice = OCW_Admin::opt('items_data.msg.notice');
        if(!$notice || !is_array($notice) || !in_array('sms',$notice)){
            return;
        }
        $cnf = self::opt();

        $mobile = isset($cnf['to']) ? $cnf['to']: '' ;
        if(!$mobile){
            return;
        }


        $t = $wpdb->prefix.'ocw_contact';

        $row = $wpdb->get_row($wpdb->prepare("SELECT * FROM $t WHERE id=%d",$pid));
        if(!$row){
            return;
        }

        /*$conf = OCW_Contact::conf();
        $type = $conf['type'];*/

        //收到{$var1}的留言。
        $msg = [$row->name];
        /*if($type && isset($type[$row->type])){
            $msg[] = '关于'.$type[$row->type];
        }*/

        do_action('ocw_send_sms',$mobile,implode('',$msg));

    }


    public static function opt()
    {
        return OCW_Admin::opt('items_data.msg.sms');
    }

    /**
     * @param $mobile
     * @param array $data [code=>123]
     * @return bool
     */
    public static function send($mobile,array $data, array $cnf = null)
    {
        //get config
        if(!$cnf){
            $cnf = self::opt();
        }
        $result = ['code'=>1,'desc'=>'fail'];
        do{
            if(!$cnf){
                $result['desc'] = '短信配置为空';
                break;
            }
            if(!isset($cnf['vendor']) || empty($cnf['vendor']) || !isset($cnf[$cnf['vendor']])){
                $result['desc'] = '短信接口为空';
                break;
            }
            $sms_cnf = $cnf[$cnf['vendor']];
            $is_fail = false;
            switch ($cnf['vendor']) {
                case 'upyun':
                    if(empty($sms_cnf['id']) || empty($sms_cnf['secret']) || empty($sms_cnf['tpl'])){
                        $result['desc'] = '短信接口参数不完整';
                        $is_fail = true;
                        break;
                    }

                    require_once __DIR__.'/sms/upyun.php';
                    $ret = OCW_Sms_Upyun::send($cnf['upyun'],$mobile,$data);
                    $result['code'] = 0;
                    $result['desc'] = 'success';
                    $result['data'] = $ret;
                    if(!$ret || !$ret->code){
                        break;
                    }
                    $result['code'] = 1;
                    $result['desc'] = '发送失败';
                    if($ret->error_code){
                        $result['desc'] = $ret->error_code;
                    }else if($ret->message_ids && $ret->message_ids[0]){
                        $result['desc'] = '发送失败,'.$ret->message_ids[0]->error_code;
                    }
                    break;
                case 'aliyun':
                    if(empty($sms_cnf['id']) || empty($sms_cnf['secret']) || empty($sms_cnf['tpl']) || empty($sms_cnf['sign'])){
                        $result['desc'] = '短信接口参数不完整';
                        $is_fail = true;
                        break;
                    }
                    require_once __DIR__.'/sms/aliyun.php';
                    $ret = OCW_Sms_Aliyun::send($cnf['aliyun'],$mobile,$data);
                    $result['code'] = 0;
                    $result['desc'] = 'success';
                    $result['data'] = $ret;
                    if(!$ret || $ret->Code === 'OK'){
                        break;
                    }
                    $result['code'] = 1;
                    $result['desc'] = $ret->Message;
                    break;
                case 'huawei':
                    if(empty($sms_cnf['id']) || empty($sms_cnf['secret']) || empty($sms_cnf['tpl']) || empty($sms_cnf['sign'])  || empty($sms_cnf['api'])  || empty($sms_cnf['channel'])){
                        $result['desc'] = '短信接口参数不完整';
                        $is_fail = true;
                        break;
                    }
                    require_once __DIR__.'/sms/huawei.php';
                    $ret = OCW_Sms_Huawei::send($cnf['huawei'],$mobile,$data);
                    $result['code'] = 0;
                    $result['desc'] = 'success';
                    $result['data'] = $ret;
                    if(!$ret || $ret->code === '000000'){
                        break;
                    }
                    $result['code'] = 1;
                    $result['desc'] = '发送失败[code:'.$ret->code.',desc:'.$ret->description.']';

                    break;
                default:
                    $result['desc'] = '没有找到对应短信发送接口';
                    break;
            }
            if($is_fail){
                break;
            }

        }while(0);

        return apply_filters('ocw_sms_send_result',$result);
    }

}