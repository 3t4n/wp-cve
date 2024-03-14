<?php
if (!defined('ABSPATH')) exit;

$params = WShop_Temp_Helper::clear('atts', 'templates');
$data=$params['data'];
$product=$params['product'];
$postRoles=$params['postRoles'];

/*--------------------------------下载提示+立即支付弹窗--------------------------------*/
$content = $data['content'] ? $data['content'] : '立即下载';
$location = $data['atts']['location']? $data['atts']['location'] : WShop_Helper_Uri::get_location_uri();
//uuid
$context = WShop_Helper::generate_unique_id();
//model
$modal = WShop_Settings_Checkout_Options::instance()->get_option('modal', 'shopping_list');
//定义支付接口
$section = isset($data['atts']['section']) ? $data['atts']['section'] : 'download';
//移动端，不出现扫码
if ($modal == 'shopping_one_step' && WShop_Helper_Uri::is_app_client()) {
    $modal = 'shopping_list';
}
//当前用户角色，没有登录的情况默认为订阅者
global $current_user;
$userRoles = $current_user->roles?$current_user->roles:['subscriber'];
//支付方式
$payment_gateways = WShop::instance()->payment->get_payment_gateways();
do_action('wshop_footer', $payment_gateways);

?>
<div class="xunhu-downbox xunhu-ptb20 xunhu-radius xunhu-bg-color xunhu-pr xunhu-font xunhu-mr-auto xunhu-ml-auto">
    <div class="xunhu-text-center xunhu-font font-16 text-download">
        隐藏内容<?php if($product->get_single_price(false)):?>需要支付：<span class="text-warning"><span class="xunhu-text-lg"><?php echo $product->get_single_price(true); ?></span></span><?php endif;?>
    </div>
    <div class="xunhu-text-center xunhu-mt10">
        <?php
        if($product->get_single_price(false)):
            foreach ($userRoles as $v):
                if(count($postRoles['pay'])>0):
                    if($postRoles['pay'][0]=='all'||in_array($v, $postRoles['pay'])):
                        ?>
                        <a href="javascript:void(0);" class="xunhu-btn xunhu-btn-green" id="xh_now_pay_model_show_<?php echo $context; ?>">立即购买</a>
                        <script type="text/javascript">
                            (function ($) {
                                $('#xh_now_pay_model_show_<?php echo $context;?>').click(function () {
                                    var redirect_login=<?php echo (!is_user_logged_in() && !WShop::instance()->WP->is_enable_guest_purchase())?1:0;?>;
                                    if(redirect_login){
                                        location.href='<?php echo wp_login_url($location);?>';
                                        return;
                                    }

                                    var params = {
                                        modal:'<?php echo $modal;?>',
                                        url: '<?php echo esc_url_raw(WShop::instance()->ajax_url(array('action' => 'wshop_checkout_v2', 'tab' => 'purchase_modal_'.$modal, 'section' => $section), true, true))?>',
                                        type:'POST',
                                        data: {
                                            location: '<?php echo $location;?>',
                                            post_id:<?php echo $data['atts']['post_id'];?>
                                        }
                                    };
                                    XH_Plugins_Custom.xh_now_pay_model_show(params);
                                });
                            })(jQuery);
                        </script>
                        <?php
                        break;
                    endif;
                endif;
            endforeach;
        endif;
        
        if(WShop::instance()->get_available_addon('wshop_add_ons_membership')){
		    array_push($postRoles['free'],'VIP');
		}
        if (count($postRoles['free'])>0) { ?>
            <a href="javascript:void(0);" class="xunhu-btn xunhu-btn-warning" id="xh_buy_membership_model_show_<?php echo $context; ?>">升级VIP</a>
            <script type="text/javascript">
                (function ($) {
                    $('#xh_buy_membership_model_show_<?php echo $context;?>').click(function () {
                        var redirect_login=<?php echo (!is_user_logged_in() && !WShop::instance()->WP->is_enable_guest_purchase())?1:0;?>;
                        if(redirect_login){
                            location.href='<?php echo wp_login_url($location);?>';
                            return;
                        }

                        XH_Plugins_Custom.xh_buy_membership_model_show();
                    });
                })(jQuery);
            </script>
        <?php } ?>
    </div>
    <img src="<?php echo WSHOP_URL; ?>/assets/image/v2/lock.png" class="xunhu-pa xunhu-downlockicon">
</div>
<!--立即支付弹窗-->
<div id="xh_now_pay_modal" class="xunhu-modal" style="display: none;">
    <!-- 付费查看+付费下载弹窗支付 -->
    <div class="xunhu-modal-content">
        <span class="xunhu-close" onclick="XH_Plugins_Custom.close_model();"></span>
        <div class="xunhu-ptb40">
            <div class="xunhu-text-center xunhu-font font-16 text-download">
                查看隐藏内容需要支付：
                <span class="text-warning">
                            <span class="xunhu-text-lg"><?php echo $product->get_single_price(true); ?></span>
                        </span>
            </div>
            <div class="xunhu-font xunhu-text-center font-16">
                <div class="radio">
                    <?php
                    foreach ($payment_gateways as $v) {?>
                        <input id="radio-<?php echo esc_attr($v->id); ?>" name="payment" type="radio" value="<?php echo esc_attr($v->id); ?>" checked>
                        <label for="radio-<?php echo esc_attr($v->id); ?>" class="radio-label">
                            <img src="<?php echo $v->icon_small;?>" style="display: inline-block;height: 16px;width: 16px;vertical-align: middle">
                            <?php echo esc_attr($v->title); ?>
                        </label>
                    <?php } ?>
                </div>
            </div>
            <div class="xunhu-text-center xunhu-mt10">
                <a href="javascript:void(0);" class="xunhu-btn xunhu-btn-green" onclick="XH_Plugins_Custom.xh_now_pay('xh_now_pay_modal')">立即支付</a>
            </div>
        </div>
    </div>
</div>
<div id="xh_now_pay_modal_2" class="xunhu-modal" style="display: none;">
    <div class="xunhu-modal-content">
        <span class="xunhu-close" onclick="XH_Plugins_Custom.close_model();"></span>
        <div class="xunhu-ptb40">
            <div class="xunhu-text-center xunhu-font font-16 text-download">
                <span class="text-warning"><span class="xunhu-text-lg modal-price"></span></span>
            </div>
            <div class="xunhu-font xunhu-text-center">
                <img id="xh_now_pay_modal_2_qrcode" src="" width="200" height="200">
            </div>
            <div class="xunhu-text-center xunhu-mt10">
                请使用
                <?php
                $payment_str='';
                foreach ($payment_gateways as $v) {
                    $payment_str.='或'.$v->title;
                }
                $payment_str=substr($payment_str,3);
                echo $payment_str;
                ?>
                扫码支付
            </div>
        </div>
    </div>
</div>
<?php
/*--------------------------------下载提示+立即支付弹窗 END--------------------------------*/
//判断会员等级是否启用
if(!WShop::instance()->get_available_addon('wshop_add_ons_membership')){
    return;
}
/*--------------------------------会员购买弹窗--------------------------------*/
//获取会员类型
global $wpdb;
$memberships = $wpdb->get_results(
    "select *
                      from {$wpdb->prefix}wshop_membership m
                      inner join {$wpdb->posts} p on p.ID = m.post_ID
                      where p.post_type='".WShop_Membership::POST_T."'
                            and p.post_status='publish'
                      order by p.menu_order;");
if(!$memberships) return;
//会员说明
$membershipNotes=WShop_Add_On_Membership::instance()->get_option('membership_notes');
//获取会员有效时长
if(!function_exists('getMembershipValidTime')){
    function getMembershipValidTime($validTime){
        if(!$validTime){
            return '永久有效';
        }
        $year = absint($validTime/(12*30*24*60*60));
        $totalMonth = absint($validTime%(12*30*24*60*60));
        $month =  absint($totalMonth/(30*24*60*60));
        $totalDay =absint($totalMonth%(30*24*60*60));
        $day = absint($totalDay/(24*60*60));
        $totalHour =absint($totalDay%(24*60*60));
        $hour =absint( $totalHour/(60*60));
        $txt="有效期";
        if($year)$txt.=$year.'年';
        if($month)$txt.=$month.'月';
        if($day)$txt.=$day.'日';
        if($hour)$txt.=$hour.'小时';
        return $txt;
    }
}
?>
<div id="xh_buy_membership_model" class="xunhu-modal" style="display: none;">
    <div class="xunhu-modal-content">
        <span class="xunhu-close" onclick="XH_Plugins_Custom.close_model();"></span>
        <div class="xunhu-ptb40">
            <div class="xunhu-alert xunhu-alert-danger xh-w50" id="xh_buy_model_error_notice" style="display: none;">
                你还没登陆！请先<a href="<?php echo wp_login_url($location);?>" style="text-decoration: none;">登陆</a>后再操作
            </div>
            <div class="xunhu-text-center xunhu-font font-16 text-download">
                请选择会员类型
                <a href="javascript:void(0);" class="xunhu-up-icon" onclick="XH_Plugins_Custom.show_or_hide_membership_notes(this,'xh_membership_notes');"></a>
            </div>
            <div id="xh_membership_notes" class="xunhu-font xunhu-bg-color xunhu-radius xunhu-p15 text-help xunhu-member-info">
                <? echo $membershipNotes;?>
            </div>
            <div class="xunhu-flex xunhu-flex-row xunhu-justify-content-center xunhu-mt20 xunhu-font xunhu-flex-wrap">
                <?php
                $i=0;
                foreach ($memberships as $item):
                    $i++;
                    $membership=new WShop_Product($item->post_ID);
                    if(!$membership->is_load()) continue;?>
                    <div class="xunhu-member-item xh-membership-type <?php echo $i==1?'active':'';?>" data-id="<?php echo $item->post_ID;?>" onclick="XH_Plugins_Custom.xh_membership_type_sel(this);">
                        <div class="xunhu-text-lg"><?php echo $membership->get_single_price(true);?></div>
                        <div class="xunhu-mt10 multi-ellipsis"><?php echo $membership->get('post_title');?></div>
                        <div><?php echo getMembershipValidTime($item->valid_time)?></div>
                        <?php 
                        	$download_count=$item->download_count;
                        	if(substr($download_count,-1)=='d'){
                        	?>
                        		 <div>每天<?php echo substr($download_count,0,strlen($download_count)-1) ?>次免费下载</div>
                        	<?php	
                        	}elseif(substr($download_count,-1)=='w'){
                        		?>
                        		<div>每周<?php echo substr($download_count,0,strlen($download_count)-1) ?>次免费下载</div>
                        	<?php
                        	}elseif(substr($download_count,-1)=='m'){
                        		?>
                        		<div>每月<?php echo substr($download_count,0,strlen($download_count)-1) ?>次免费下载</div>
                        	<?php
                        	}
                        ?>
                    </div>
                <?php endforeach;?>
            </div>
            <div class="xunhu-flex xunhu-flex-row xunhu-justify-content-center xunhu-mt20 xunhu-font font-20 xh-member-pay">
                <a href="javascript:void(0);" id="pay_mode" class="active" onclick="XH_Plugins_Custom.xh_buy_membership_mode_sel(this);">在线支付</a>
                <a href="javascript:void(0);" id="code_mode" class="xunhu-ml10" onclick="XH_Plugins_Custom.xh_buy_membership_mode_sel(this);">激活码支付</a>
            </div>
            <div id="pay_mode_container" class="xunhu-font xunhu-text-center font-16">
                <div class="radio">
                    <?php
                    foreach ($payment_gateways as $v) {?>
                        <input id="buy_membership_radio_<?php echo esc_attr($v->id); ?>" name="buy_membership_payment" type="radio" value="<?php echo esc_attr($v->id); ?>" checked>
                        <label for="buy_membership_radio_<?php echo esc_attr($v->id); ?>" class="radio-label">
                            <img src="<?php echo $v->icon_small;?>" style="display: inline-block;height: 16px;width: 16px;vertical-align: middle">
                            <?php echo esc_attr($v->title); ?>
                        </label>
                    <?php } ?>
                </div>
            </div>
            <div id="code_mode_container" class="xunhu-text-center xunhu-mt20 xunhu-font" style="display: none;">
                <input id="buy_membership_code_<?php echo $context;?>" type="text" placeholder="请输入激活码" class="xunhu-input xh-w50">
            </div>
            <div class="xunhu-text-center xunhu-mt20"><a href="javascript:void(0);" class="xunhu-btn xunhu-btn-green" id="xh_buy_membership_<?php echo $context;?>">立即支付</a></div>
            <script type="text/javascript">
                (function ($) {
                    $('#xh_buy_membership_<?php echo $context;?>').click(function () {
                        var params={
                            url: '<?php echo esc_url_raw(WShop::instance()->ajax_url(array('action' => 'wshop_checkout_v2', 'tab' => 'membership', 'section' => 'membership'), true, true))?>',
                            type:'POST',
                            data: {
                                code: "",
                                location: "<?php echo $location;?>",
                                payment_method:"",
                                post_id:XH_Plugins_Custom.data['membership_id'],
                                scope:""
                            }
                        };
                        if(XH_Plugins_Custom.data['buy_membership']==='pay_mode'){
                            params['data']['code']='';
                            params['data']['payment_method']=$('#xh_buy_membership_model input:radio[name="buy_membership_payment"]:checked').val();
                            params['data']['scope']='pay';
                        }else if(XH_Plugins_Custom.data['buy_membership']==='code_mode') {
                            params['data']['code']=$('#buy_membership_code_<?php echo $context;?>').val();
                            params['data']['scope']='code';
                        }
                        XH_Plugins_Custom.xh_now_pay('xh_buy_membership_model',params);
                    });
                })(jQuery);
            </script>

        </div>
    </div>
</div>
<?php
/*--------------------------------会员购买弹窗 END--------------------------------*/
