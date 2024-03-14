<?php
if (!defined('ABSPATH')) exit;

$context = WShop_Helper::generate_unique_id();
//打赏提示
$reward_btn_txt=WShop_Add_On_Reward::instance()->get_option('reward_btn_txt');
$reward_btn_txt=$reward_btn_txt?$reward_btn_txt:'我要打赏';
//支付方式
$payment_gateways = WShop::instance()->payment->get_payment_gateways();
do_action('wshop_footer', $payment_gateways);
?>
<!--打赏按钮-->
<div class="xunhu-text-center xunhu-mt10">
    <a href="javascript:void(0);" class="xunhu-btn xunhu-btn-border-green" id="xh_pay_model_show_<?php echo $context; ?>"><?php echo $reward_btn_txt;?></a>
</div>
<script type="text/javascript">
    (function ($) {
        $('#xh_pay_model_show_<?php echo $context;?>').click(function () {
            $('#xh_pay_modal_<?php echo $context; ?>').show();
        });
    })(jQuery);
</script>
<!--打赏按钮 END-->


<?php
//当前地址
$location = WShop_Helper_Uri::get_location_uri();
//当前文章post_id
$post=get_post();
if(!$post)return;
//最低打赏金额
$reward_min_price=WShop_Add_On_Reward::instance()->get_option('reward_min_price');
//打赏的推荐金额
$reward_recommend_price=WShop_Add_On_Reward::instance()->get_option('reward_recommend_price');
?>
<!--支付弹窗-->
<div id="xh_pay_modal_<?php echo $context; ?>" class="xunhu-modal" style="display: none;">
    <div class="xunhu-modal-content">
        <span class="xunhu-close" id="xh_pay_model_hide_<?php echo $context; ?>"></span>
        <div class="xunhu-ptb40">
            <div class="xunhu-alert xunhu-alert-danger xh-w50" id="xh_buy_model_error_notice" style="display: none;"></div>
            <div class="xunhu-text-center xunhu-font font-16 text-download">给作者打赏，选择打赏金额</div>
            <div class="xunhu-flex xunhu-flex-wrap xunhu-flex-fill xunhu-mt20 xh-w50">
                <?php
                if($reward_recommend_price){
                    $currency=WShop::instance()->payment->get_currency();
                    $currency_symbol=WShop_Currency::get_currency_symbol($currency);
                    $index=0;
                    WShop::instance()->payment->get_currency();
                    foreach (explode(',',$reward_recommend_price) as $v){
                        echo '<span class="xunhu-ds-pire reward-price '.($index?'':'active').'" data-price="'.$v.'" onclick="window.RewardView.sel_reward_price(this);">'.$currency_symbol.$v.'</span>';
                        $index++;
                    }
                }
                ?>
                <span class="xunhu-ds-pire reward-price" data-price="" onclick="window.RewardView.sel_reward_price(this);">自定义</span>
            </div>
            <div class="xh-w50">
                <div class="xunhu-text-center xunhu-font" >
                    <input id="reward_price_<?php echo $context;?>" type="text" placeholder="打赏金额不能低于￥<?php echo $reward_min_price;?>" class="xunhu-input" style="display: none;">
                </div>
            </div>
            <div class="xunhu-font xunhu-text-center font-16">
                <div class="radio">
                    <?php
                    $index=0;
                    foreach ($payment_gateways as $v):?>
                        <input id="radio-<?php echo esc_attr($v->id); ?>" name="payment" type="radio" value="<?php echo esc_attr($v->id); ?>" <?php echo $index?'':'checked';?>>
                        <label for="radio-<?php echo esc_attr($v->id); ?>" class="radio-label"><?php echo esc_attr($v->title); ?></label>
                        <?php $index++;
                    endforeach;?>
                </div>
            </div>
            <div class="xunhu-text-center xunhu-mt10">
                <a href="javascript:void(0);" class="xunhu-btn xunhu-btn-green" onclick="window.RewardView.now_pay();">立即支付</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function ($) {
        window.RewardView={
            set_reward_price:function () {
                $('.reward-price').each(function () {
                    if($(this).is('.active')){
                        var price=$(this).data('price');
                        $('#reward_price_<?php echo $context;?>').val(price);
                        if(price.length<=0){
                            $('#reward_price_<?php echo $context;?>').show();
                        }else {
                            $('#reward_price_<?php echo $context;?>').hide();
                        }
                    }
                });
            },
            sel_reward_price:function (obj) {
                $('.reward-price').each(function () {
                    $('.reward-price').removeClass('active');
                    $(obj).addClass('active');
                });
                window.RewardView.set_reward_price();
            },
            now_pay:function () {
                var reward_price=$('#reward_price_<?php echo $context;?>').val();
                var payment_method=$('#xh_pay_modal_<?php echo $context; ?> input:radio[name="payment"]:checked').val();
                if(reward_price<=0||!payment_method) return;
                var params={
                    url:"<?php echo esc_url_raw(WShop::instance()->ajax_url(['action'=>'wshop_wshop_add_ons_reward'],true,true))?>",
                    type:'POST',
                    data:{
                        post_id:<?php echo $post->ID;?>,
                        reward_price:reward_price,
                        payment_method:payment_method,
                        location:'<?php echo $location;?>'
                    }
                };
                window.RewardView.__ajax(params,window.RewardView.callback);
            },
            callback:function (data) {
                location.href=data;
            },
            __ajax:function (params,callback) {
                if(!params) return;
                if(!params.url||!params.type||!params.data) return;
                $.ajax({
                    url: params.url,
                    type: params.type,
                    timeout: 60*1000,
                    cache: false,
                    data: params.data,
                    dataType: 'json',
                    success: function(res) {
                        if(res.errcode!=0){
                            console.log(res);
                            $('#xh_buy_model_error_notice').html(res.errmsg);
                            $('#xh_buy_model_error_notice').show();
                            return;
                        }
                        callback&&callback(res.data);
                    },error:function(e){
                        console.log(e);
                    }
                });
            }
        };
        window.RewardView.set_reward_price();
        $('#xh_pay_model_hide_<?php echo $context;?>').click(function () {
            $('#xh_pay_modal_<?php echo $context; ?>').hide();
        });
    })(jQuery);
</script>
<!--支付弹窗 END-->