<?php
if (! defined('ABSPATH')) exit;

abstract class Abstract_WShop_Add_Ons_Reward_Api extends Abstract_WShop_Add_Ons{
    const POST_T='wshop_reward';

    public $u;
    public $i;
    public $k;

    protected function __construct() {
        $o = $this;
        $o->u = WSHOP_URL;
        $o->i = WShop_Install::instance()->get_plugin_options();
        //$o->k = WShop::$license_id;
    }

    /**
     * 执行 on_load()
     */
    public function m1()
    {
        $o = $this;
        add_filter('wshop_admin_menu_menu_default_modal', function ($menus) {
                $menus[] = WShop_Add_On_Reward::instance();
                return $menus;
            }, 11, 1);

            //短码对应文件
            add_filter('wshop_shortcodes',function($codes){
                $codes['wshop_reward']=function($attr,$content){
                    return WShop::instance()->WP->requires(WShop_Add_On_Reward::instance()->dir, 'reward/output_reward_html.php',[]);
                };
                return $codes;
            });

            //打赏金额不进行则扣
            add_filter('wshop_membership_discount_enabled',function ($is_enabled_discount,$product){
                if($product->post->post_type==self::POST_T){
                    return false;
                }
                return $is_enabled_discount;
            },10,2);

            //打赏成功回调页面
            add_filter('wshop_order_reward_received_url', function ($url, $order){
                $location = isset($order->metas['location']) && !empty($order->metas['location']) ? esc_url_raw($order->metas['location']) : null;
                if (!empty($location)) {
                    return $location;
                }
                return $url;
            }, 10, 2);
        /*$o->m0(function ($o) {
            //配置项
            add_filter('wshop_admin_menu_menu_default_modal', function ($menus) {
                $menus[] = WShop_Add_On_Reward::instance();
                return $menus;
            }, 11, 1);

            //短码对应文件
            add_filter('wshop_shortcodes',function($codes){
                $codes['wshop_reward']=function($attr,$content){
                    return WShop::instance()->WP->requires(WShop_Add_On_Reward::instance()->dir, 'reward/output_reward_html.php',[]);
                };
                return $codes;
            });

            //打赏金额不进行则扣
            add_filter('wshop_membership_discount_enabled',function ($is_enabled_discount,$product){
                if($product->post->post_type==self::POST_T){
                    return false;
                }
                return $is_enabled_discount;
            },10,2);

            //打赏成功回调页面
            add_filter('wshop_order_reward_received_url', function ($url, $order){
                $location = isset($order->metas['location']) && !empty($order->metas['location']) ? esc_url_raw($order->metas['location']) : null;
                if (!empty($location)) {
                    return $location;
                }
                return $url;
            }, 10, 2);

        }, function ($o) {});*/
    }

    /**
     * 执行 on_init()
     */
    public function m2()
    {	
        $o = $this;
        
        $o->setting_uris=array();
            $o->setting_uris['settings']=array();
            $o->setting_uris['settings']['title']=__('Settings',WSHOP);
            $o->setting_uris['settings']['url']=admin_url('admin.php?page=wshop_page_default&section=menu_default_modal&sub=wshop_add_ons_reward');
        /*$o->m0(function ($o) {

            $o->setting_uris=array();
            $o->setting_uris['settings']=array();
            $o->setting_uris['license']=array();
            $o->setting_uris['settings']['title']=__('Settings',WSHOP);
            $o->setting_uris['settings']['url']=admin_url('admin.php?page=wshop_page_default&section=menu_default_modal&sub=wshop_add_ons_reward');

            $api = WShop_Install::instance();
            $o->setting_uris['license']['title']=__('Change license',WSHOP);
            $o->setting_uris['license']['url']=$api->get_addon_license_url($o->id);

        },function($o){
            $o->setting_uris=array();
            $o->setting_uris['license']=array();

            $api = WShop_Install::instance();
            $o->setting_uris['license']['title']=__('License',WSHOP);
            $o->setting_uris['license']['url']=$api->get_addon_license_url($o->id);
        });*/
    }

    public function m0($func_success, $func_fail)
    {
        $o = $this;
        $website = $o->u;
        $website = strtolower($website);
        $license_id = $o->id;
        if (strpos($website, 'http://') === 0) {
            $website = substr($website, 7);
        } else
            if (strpos($website, 'https://') === 0) {
                $website = substr($website, 8);
            }

        // 去掉二级目录
        if (strpos($website, '/') !== false) {
            $websites = explode('/', $website);
            $website = $websites[0];
        }
        $prewebsite = $website;
        $info = $o->i;
        $license = $info && isset($info[$o->id]) ? $info[$o->id] : null;
        $licenses = $license ? explode('=', $license) : array();
        $license = count($licenses) > 0 ? $licenses[0] : null;
        $expire = count($licenses) > 1 ? $licenses[1] : null;

        $id=$o->id;
        $bk=0;
        while (true){
            $str =$expire."|".$website."|".$id;
            $str =md5($str);
            $b =0;
            for ($i=0;$i<strlen($str);$i++){
                $b+= ord($str[$i]);
            }

            $xx=md5($str.$b)==$license;
            $o->ia=$xx;
            if($xx){
                if($func_success){
                    $func_success($o);
                }
                break;
            }

            if(substr_count($website,'.')<=1){
                if($bk<count($o->k)){
                    $website=$prewebsite;
                    $license =$info&&isset($info['license'])?$info['license']:null;
                    $licenses= $license?explode('=', $license):array();
                    $license =count($licenses)>0?$licenses[0]:null;
                    $expire=count($licenses)>1?$licenses[1]:null;
                    $id = $o->k[$bk++];
                    continue;
                }

                if($func_fail){
                    $func_fail($o);
                }
                break;
            }

            $index = strpos($website, '.');
            $website = substr($website, $index+1);
        }
    }
}