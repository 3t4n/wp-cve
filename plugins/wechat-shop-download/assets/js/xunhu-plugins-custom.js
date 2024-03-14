(function ($) {
    window.XH_Plugins_Custom={
        /**
         * 每次打开弹窗都会清空
         */
        data:{},
        /**
         * 打开立即购买弹窗
         * @param params ajax地址、当前文章地址、当前文章postID
         */
        xh_now_pay_model_show:function (params) {
            if(params.modal){
                switch (params.modal){
                    case 'shopping_one_step':
                        XH_Plugins_Custom.__ajax(params,XH_Plugins_Custom.show_model_qrcode);
                        break;
                    case 'shopping_cart':
                        XH_Plugins_Custom.__ajax(params,XH_Plugins_Custom.redirect_cart);
                        break;
                    default:
                        XH_Plugins_Custom.data={};
                        $('#xh_now_pay_modal').show();
                        XH_Plugins_Custom.__ajax(params,XH_Plugins_Custom.bind_url);
                }
            }
        },
        /**
         * 关闭弹窗
         */
        close_model:function () {
            $(".xunhu-modal").each(function(){
                $(this).hide();
            });
        },
        /**
         * 该url用于携带支付方式，请求支付链接
         */
        bind_url:function(_data) {
            XH_Plugins_Custom.data['url']=_data['url'];
        },
        /**
         * 弹窗+微信支付宝扫码
         */
        show_model_qrcode:function(data){
            //价格
            $("#xh_now_pay_modal_2 .modal-price").html(data.price_html);
            //二维码
            $("#xh_now_pay_modal_2_qrcode").attr("src", data.qrcode_url);
            $('#xh_now_pay_modal_2').show();
            console.log(data);
        },
        /**
         * 重定向到购物车页面
         */
        redirect_cart:function (data) {
            location.href=data;
        },
        /**
         * 打开购买会员弹窗
         */
        xh_buy_membership_model_show:function () {
            XH_Plugins_Custom.data={
                buy_membership:'pay_mode'   //打开弹窗默认的购买会员的方式为付款
            };
            //设置默认选择的会员类型
            $('.xh-membership-type').each(function () {
                if($(this).is('.active')){
                    XH_Plugins_Custom.data['membership_id']=$(this).data('id');
                }
            });
            $('#xh_buy_membership_model').show();
        },
        /**
         * 显示或隐藏会员说明
         */
        show_or_hide_membership_notes:function (obj,containerID) {
            var currentNode=$('#'+containerID);
            if($(obj).hasClass('xunhu-up-icon')){
                $(obj).removeClass('xunhu-up-icon');
                $(obj).addClass('xunhu-down-icon');
            }else {
                $(obj).removeClass('xunhu-down-icon');
                $(obj).addClass('xunhu-up-icon');
            }
            //显示 隐藏
            if(currentNode.is(':hidden')){
                currentNode.show();
            }else{
                currentNode.hide();
            }
        },
        /**
         * 会员类型选择
         */
        xh_membership_type_sel:function (currentObj) {
            $('.xh-membership-type').each(function () {
                $(this).removeClass('active');
            });
            $(currentObj).addClass('active');
            XH_Plugins_Custom.data['membership_id']=$(currentObj).data('id');
        },
        /**
         * 购买会员方式选择
         */
        xh_buy_membership_mode_sel:function (obj) {
            var showModeID=$(obj).attr('id');
            var hideModeID='code_mode';
            if(showModeID==='code_mode'){
                hideModeID='pay_mode';
            }
            var showModeContainerID=showModeID+'_container';
            var hideModeContainerID=hideModeID+'_container';
            XH_Plugins_Custom.show_buy_membership_mode(showModeID,showModeContainerID);
            XH_Plugins_Custom.hide_buy_membership_mode(hideModeID,hideModeContainerID);
            //设置当前购买会员的方式
            XH_Plugins_Custom.data['buy_membership']=showModeID;
        },
        /**
         * 显示会员购买方式
         */
        show_buy_membership_mode:function (showModeID,showModeContainerID) {
            if(!($('#'+showModeID).is('.active'))){
                $('#'+showModeID).addClass('active');
            }
            $('#'+showModeContainerID).show();
        },
        /**
         * 隐藏会员购买方式
         */
        hide_buy_membership_mode:function (hideModeID,hideModeContainerID) {
            $('#'+hideModeID).removeClass('active');
            $('#'+hideModeContainerID).hide();
        },
        /**
         * 立即支付
         * source 来源，可能来源于立即购买的弹窗，也可能来源于升级会员的弹窗
         */
        xh_now_pay:function(source,params){
            if(!source)return;
            switch (source){
                case 'xh_now_pay_modal':
                    if(!XH_Plugins_Custom.data['url']) return;
                    var payment=$('#xh_now_pay_modal input:radio[name="payment"]:checked').val();
                    var _params={
                        url:XH_Plugins_Custom.data['url'],
                        type:'POST',
                        data:{
                            payment_method:payment
                        }
                    };
                    //提交订单，进行支付
                    XH_Plugins_Custom.__ajax(_params,XH_Plugins_Custom.pay);
                    break;
                case 'xh_buy_membership_model':
                    //提交订单，进行支付
                    XH_Plugins_Custom.__ajax(params,XH_Plugins_Custom._pay);
                    break;
            }
        },
        _pay:function (_data) {
            XH_Plugins_Custom.pay(_data['redirect_url'])
        },
        /**
         * 进行支付
         */
        pay:function (_data) {
            location.href=_data;
        },
        __ajax:function (params,callback) {
            if(!params) return;
            if(!params.url) return;
            if(!params.type) params.type='POST';
            if(!params.data) params.data={};
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
                        if(res.errcode!==501){
                            $('#xh_buy_model_error_notice').html(res.errmsg);
                        }
                        $('#xh_buy_model_error_notice').show();
                        return;
                    }
                    callback(res.data);
                },error:function(e){
                    console.log(e);
                }
            });
        }
    };
})(jQuery);