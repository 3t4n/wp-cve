<?php 
namespace Adminz\Helper;
use Adminz\Admin\Adminz;
/*
    dùng js để lấy các notice về nơi cần hiển thị là một div .message-container-fake
    đăng ký nơi cần hiển thị với function add_html

    Context: woocommerce không phân biệt notice đó là của cái gì. nên ko thể check
*/
class ADMINZ_Helper_Woocommerce_Message_Notice{

    public $debug = "false";

    function __construct($admz_woo) {
        if($admz_woo->get_option_value('adminz_woocommerce_fix_notice_position')){
            if(SCRIPT_DEBUG){
                $this->debug = "true";
            }
            $this->change_form_login_message();
        }
    }
    function change_form_login_message(){   
        add_action('init', [$this, 'add_html']);
        add_action('wp_footer', [$this, 'add_js'], 10, 2);
        add_action('wp_footer', [$this, 'add_css'], 10, 2);
    }

    function add_html(){

        add_action('woocommerce_add_payment_method_form_bottom', [$this,'add_div_error_login_form'], 10, 2);
        add_action('woocommerce_edit_account_form', [$this,'add_div_error_login_form'], 10, 2);
        add_action('woocommerce_after_edit_address_form_billing', [$this,'add_div_error_login_form']);
        add_action('woocommerce_after_edit_address_form_shipping', [$this,'add_div_error_login_form']);

        if(isset($_POST['login'])){
            add_action('woocommerce_login_form', [$this,'add_div_error_login_form'], 10, 2);
        }

        if(isset($_POST['register'])){
            add_action('woocommerce_register_form', [$this,'add_div_error_login_form'], 10, 2);
        }

        if(isset($_POST['wc_reset_password'])){
            add_action('woocommerce_lostpassword_form', [$this,'add_div_error_login_form'], 10, 2);
        }
        
        add_action('woocommerce_resetpassword_form', [$this,'add_div_error_login_form'], 10, 2);
        add_action('woocommerce_after_add_to_cart_button',[$this,'add_div_error_login_form'],10,2);
        add_action('woocommerce_cart_coupon',[$this,'add_div_error_login_form'],10,2);
        add_action('woocommerce_cart_actions',[$this,'add_div_error_login_form'],10,2);
        
        
    }

    function add_js(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                const debug = "<?php echo esc_attr($this->debug); ?>";
                const custom_change_woo_error_location = ()=> {
                    if($(".message-wrapper").length){
                        $(".message-wrapper").each(function(){
                            if($(this).closest('.woocommerce-form-coupon-toggle').length){
                                return;
                            }
                            let message_wrap = $(this);
                            let last_input_parent = '';
                            
                            // 1. Kiểm tra data-id và copy về div khác, add hidden
                            message_wrap.children().each(function(){
                                let id = $(this).attr("data-id");
                                if(id){
                                    //let html = $(this).html();
                                    var target = $("input[name="+id+"]");
                                    var parent = target.closest('.form-row');
                                    if(parent){
                                        custom_make_sure_empty_error_parent(parent);
                                        let append = $("<div></div>");
                                        append.addClass('check-added');
                                        append.attr('data-id-notice',id);
                                        //append.html(html);
                                        append.append($(this).clone());
                                        parent.append(append);                                        
                                        last_input_parent = parent;
                                        if( debug == "false"){
                                            $(this).addClass('hidden');
                                        }
                                    }
                                }

                            });

                            // 2. kiểm tra còn lại và move về div rest
                            if(message_wrap.children(':not(.hidden)').length){
                                let div_rest = $('<div></div>');
                                message_wrap.children(':not(.hidden)').each(function(){
                                    div_rest.append($(this).clone());
                                });

                                let submit_button = $(".woocommerce [type=submit]:not(.checkout_coupon):not(.checkout_coupon [type=submit])");
                                if(last_input_parent){
                                    let submit_button = last_input_parent.closest('form').find('[type=submit]');
                                }
                                
                                if(div_rest && submit_button){
                                    let append = $("<div></div>");
                                    append.addClass('check-added');
                                    append.addClass('mb-half');
                                    append.append(div_rest);

                                    if($('.message-container-fake').length){                                     
                                        $('.message-container-fake').addClass('mb-half').html(append);
                                    }else{
                                        var parent = submit_button.closest('div');
                                        custom_make_sure_empty_error_parent(parent);
                                        parent.find(submit_button).before(append);
                                    }
                                    if( debug == "false"){
                                        message_wrap.children(':not(.hidden)').addClass('hidden');
                                    }
                                }
                            }

                            // 3. Ẩn đi nếu tất cả item đều là hidden                            
                            if(message_wrap.children(':not(.hidden)').length == 0){
                                if( debug == "false"){
                                    message_wrap.addClass('hidden');
                                }
                                message_wrap.show();
                                if( debug == "true"){
                                    console.log(message_wrap);
                                }
                            }
                        });
                    }
                }

                const custom_make_sure_empty_error_parent = (parent)=> {
                    // Đảm bảo clear hết trước khi append
                    parent.find(".check-added").remove();
                }

                custom_change_woo_error_location(); 

                // AJAX FIX
                $("body").on("checkout_error",function(){
                    custom_change_woo_error_location();
                });
            });
        </script>
        <?php
    }

    function add_css(){
        if($this->debug == "true") return;
        ?>
        <style type="text/css">
            .message-wrapper{
                display: none;
            }
            .message-container-fake .wc-forward{
                display: none;
            }
            .check-added li,
            .message-container-fake li{
                list-style: none;
            }
        </style>
        <?php
    }

    function add_div_error_login_form(){
        ?>
        <div class="message-container-fake"></div>

        <?php if($this->debug == "true"): ?>
        <script type="text/javascript">
            console.log('<?php echo __FUNCTION__.json_encode($_POST) ?>');
        </script>
        <?php
        endif;
    }
    
}