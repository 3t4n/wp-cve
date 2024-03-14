<?php

class pisol_corw_basic_option{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "Basic setting";

    private $setting_key = 'pisol_corw_basic_setting';


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
            
            array('field'=>'pi_corw_order_status_allow_cancel_request', 'label'=>__('Show cancel order request button on order with this status','cancel-order-request-woocommerce'),'type'=>'multiselect', 'default'=> array('wc-processing'), 'value'=> $this->orderStatus(),  'desc'=>__('Cancel order button will be allowed for order with this status','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_disable_for_payment_method1', 'label'=>__('Disable cancel request option for orders with this payment methods','cancel-order-request-woocommerce'),'type'=>'select', 'default'=> '', 'value'=> array(),  'desc'=>__('Cancel request option will not be given for the orders that is placed using this payment method','cancel-order-request-woocommerce'),'pro' => true),

            array('field'=>'pi_corw_disable_for_user_group1', 'label'=>__('Disable cancel request option for orders placed by this user group','cancel-order-request-woocommerce'),'type'=>'select', 'default'=> '', 'value'=> array(),  'desc'=>__('Cancel request option will not be given for the orders that is placed by the user from this group','cancel-order-request-woocommerce'), 'pro'=>true),

            array('field'=>'pi_corw_order_admin_notice', 'label'=>__('Admin message to show above the cancel order request box','cancel-order-request-woocommerce'),'type'=>'textarea', 'default'=>'', 'desc'=>__('This message will be shown above the cancel box','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_predefined_reason_for_cancellation', 'label'=>__('Predefined reason for cancellation','cancel-order-request-woocommerce'),'type'=>'textarea', 'default'=>'', 'desc'=>__('Add one reason in one line without html e.g: <br>Product not as described<br>
            Not interested any more<br>
            Other','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_hide_button_after_time', 'label'=>__('Remove cancel button after this time (minutes)','cancel-order-request-woocommerce'),'type'=>'number', 'min'=> 0, 'default'=>'', 'desc'=>__('Order cancel request will be only allowed up till this much of time after the order placement time, if this is left blank or set to 0 then button will not be hidden based on time','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_order_detail_page_link', 'label'=>__('Add order detail page link in customer email','cancel-order-request-woocommerce'),'type'=>'multiselect', 'default'=>array('guest'), 'value'=> array('registered'=> __('Registered customer', 'cancel-order-request-woocommerce'), 'guest' => __('Guest customer', 'cancel-order-request-woocommerce')), 'desc'=>__('Order detail page link is added in the customer email so customer can view there order detail on website even guest customer can use this link to view there order details','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_disable_order_completion_email', 'label'=>__('Disable order completion email when cancellation request is rejected','cancel-order-request-woocommerce'),'type'=>'switch', 'default'=>0, 'desc'=>__('When you reject order cancellation request by changing the order status to Completed, tweo email are send once is order Completion email and one is Cancellation request rejection email. Once this is enable Order completion email not not be send when you reject cancellation request','cancel-order-request-woocommerce')),


            array('field'=>'pi_corw_make_reason_required', 'label'=>__('Make order cancellation reason as required','cancel-order-request-woocommerce'),'type'=>'switch', 'default'=>0, 'desc'=>__('If enabled the user will have to give a reason or select a reason to submit the cancellation request','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_allow_file_upload1', 'label'=>__('Allow customer to upload image along with cancellation reason','cancel-order-request-woocommerce'),'type'=>'switch', 'default'=>0, 'desc'=>__('If enabled the user will be able to upload one image file along with the cancellation reason','cancel-order-request-woocommerce'), 'pro'=>true),

            array('field'=>'color-setting', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Withdraw cancellation request','cancel-order-request-woocommerce'), 'type'=>'setting_category'),

            array('field'=>'pi_corw_withdraw_cancellation_request1', 'label'=>__('Allow withdrawal of cancellation request','cancel-order-request-woocommerce'),'type'=>'switch', 'default'=>0, 'desc'=>__('If enabled the user will be able to withdraw cancellation request till the request is not accepted/declined by the admin','cancel-order-request-woocommerce'), 'pro'=>true),

            array('field'=>'pi_corw_withdraw_cancellation_request_button_text1', 'label'=>__('Withdraw cancellation request button text','cancel-order-request-woocommerce'),'type'=>'text', 'default'=>__('Withdraw cancellation','cancel-order-request-woocommerce'), 'desc'=>'', 'pro'=>true),
        );

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),1);

        $this->register_settings();
        
    }

    function orderStatus(){
       
       $order_states = wc_get_order_statuses();
       $formated_states = array();
       foreach($order_states as $key => $val){
            $new_key = str_replace('wc-', '', $key);
            $formated_states[$new_key] = $val;
       }
       return $formated_states;
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $this->tab_name = __('Basic setting', 'cancel-order-request-woocommerce');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
            <?php echo $this->tab_name; ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_corw($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="<?php _e('Save Option','cancel-order-request-woocommerce'); ?>" />
        </form>
       <?php
    }

}

add_action('wp_loaded',function(){
    new pisol_corw_basic_option($this->plugin_name);
});