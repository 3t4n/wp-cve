<?php
/*
 * @link       http://www.apoyl.com/
 * @since      1.0.0
 * @package    Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/public/api
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
class BaiduPush
{
    public function __construct()
    {
        $https=empty($arr['https'])?'':'https://';
        $arr=get_option('apoyl-baidupush-settings');
        $site=$https.$arr['site'];
        $this->apiurl = 'http://data.zz.baidu.com/urls?site='.$site.'&token='.$arr['secret'];

    }

    public function push($update,$aid,$subject,$url){
        global $wpdb;

        $re=json_decode($this->httpGet($this->apiurl, $url));
        $data=array(
            'aid'=>$aid,
            'subject'=>$subject,
            'url'=>$url,
            'modtime'=>time(),
            'ispush'=>-1
        );
        if($update) $data['ispush']=-1+$update['ispush'];
        if(isset($re->success)){
            $data['ispush']=1;
            $data['msgs']='';
        }if($re=='curlfail'){
            $data['msgs']=__('error_curl','apoyl-baidupush');
        }elseif($re->message){
            $data['msgs']=$re->message.',code:'.$re->error;
        }else {
            if($re->not_same_site)
                $data['msgs']=__('not_same_site','apoyl-baidupush');
                if($re->not_valid)
                    $data['msgs'].=__('not_valid','apoyl-baidupush');
        }
        if($update){
            $where=array('id'=>$update['id']);
            $wpdb->update($wpdb->prefix.'apoyl_baidupush', $data, $where);
        }else{
            $wpdb->insert($wpdb->prefix.'apoyl_baidupush', $data);
        }
        return $data['ispush'];
    }
    private function httpGet($url,$pushurl)
    {
   
            $res = wp_remote_retrieve_body(wp_remote_post($url, array(
                'timeout' => 30,
                'body' =>  $pushurl,
            )));
   

        return $res;
    }

}