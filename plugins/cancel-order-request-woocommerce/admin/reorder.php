<?php

class pisol_corw_reorder_option{

    public $plugin_name;

    private $setting = array();

    private $active_tab;

    private $this_tab = 'reorder';

    private $tab_name = "Repurchase";

    private $setting_key = 'pisol_corw_repurchase_setting';


    function __construct($plugin_name){
        $this->plugin_name = $plugin_name;

        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
        
            array('field'=>'pi_corw_reorder_button', 'label'=>__('Show Repeat order button on order with this status','cancel-order-request-woocommerce'),'type'=>'multiselect', 'default'=> array(), 'value'=> $this->orderStatus(),  'desc'=>__('Cancel order button will be allowed for order with this status','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_default_action1', 'label'=>__('Default action of repeat order button','cancel-order-request-woocommerce'),'type'=>'select', 'default'=> 'ask-customer', 'value'=> array('ask-customer' => __('Ask customer (to merge in cart or replace product in cart)','cancel-order-request-woocommerce'), 'merge' => __('Merge in the cart','cancel-order-request-woocommerce'), 'replace' => __('Replace cart product','cancel-order-request-woocommerce')),  'desc'=>__('Default action of the repeat order button','cancel-order-request-woocommerce'), 'pro' => true),

            array('field'=>'pi_corw_show_reorder_on_view_order_page', 'label'=>__('Show reorder button on View order and Order success page','cancel-order-request-woocommerce'),'type'=>'switch', 'default'=>1, 'desc'=>__('If enabled repeat order button will be shown on View order page and order success page','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_redirect1', 'label'=>__('What to do after repeat order products are added to the cart','cancel-order-request-woocommerce'),'type'=>'select', 'default'=> 'ask-customer', 'value'=> array('ask-customer' => __('Ask customer in popup','cancel-order-request-woocommerce'), 'redirect-cart' => __('Direct redirect to cart page','cancel-order-request-woocommerce'), 'redirect-checkout' => __('Direct redirect to checkout page','cancel-order-request-woocommerce')),  'desc'=>__('When the repeat order product are added in the cart, we can directly redirect then to cart page or checkout page or show customer a popup with link to cart and checkout page','cancel-order-request-woocommerce'), 'pro'=>true),

            array('field'=>'color-setting', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Button Labels','cancel-order-request-woocommerce'), 'type'=>'setting_category'),

            array('field'=>'pi_corw_reorder_button_text', 'label'=>__('Repeat order','cancel-order-request-woocommerce'),'type'=>'text', 'default'=>'Repeat Order', 'desc'=>__('label of the repeat order button','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_replace_cart_button_text', 'label'=>__('Replace cart','cancel-order-request-woocommerce'),'type'=>'text', 'default'=>'Replace cart', 'desc'=>__('label of the replace cart button','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_merge_cart_button_text', 'label'=>__('Merge cart','cancel-order-request-woocommerce'),'type'=>'text', 'default'=>'Merge cart', 'desc'=>__('label of the merge cart button','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_go_to_cart_button_text', 'label'=>__('Go to Cart','cancel-order-request-woocommerce'),'type'=>'text', 'default'=>'Cart', 'desc'=>__('label of the go to cart button shown after successful insertion','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_go_to_checkout_button_text', 'label'=>__('Go to Checkout','cancel-order-request-woocommerce'),'type'=>'text', 'default'=>'Checkout', 'desc'=>__('label of the go to checkout button shown after successful insertion','cancel-order-request-woocommerce')),

            array('field'=>'color-setting', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Color Settings','cancel-order-request-woocommerce'), 'type'=>'setting_category'),

            array('field'=>'pi_corw_reorder_success_background_color', 'label'=>__('Success popup background color','cancel-order-request-woocommerce'),'type'=>'color', 'default'=>'#51a564', 'desc'=>__('The toast dialog that open on success or to give options','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_error_background_color', 'label'=>__('Error popup background color','cancel-order-request-woocommerce'),'type'=>'color', 'default'=>'#ff4747', 'desc'=>__('The toast dialog that open on success or to give options','cancel-order-request-woocommerce')),

            array('field'=>'pi_corw_reorder_button_background_color', 'label'=>__('Button in popup background color','cancel-order-request-woocommerce'),'type'=>'color', 'default'=>'#cccccc', 'desc'=>__('Background color of the the button in popup','cancel-order-request-woocommerce')),
            
            array('field'=>'pi_corw_reorder_button_text_color', 'label'=>__('Button in popup text color','cancel-order-request-woocommerce'),'type'=>'color', 'default'=>'#000000', 'desc'=>__('Text color of the the button in popup','cancel-order-request-woocommerce')),
        );

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),2);

        $this->register_settings();
        
    }

    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function orderStatus(){
       
        $order_states = wc_get_order_statuses();
        $formated_states = array('all'=>__('All Order types','cancel-order-request-woocommerce'));
        foreach($order_states as $key => $val){
             $new_key = str_replace('wc-', '', $key);
             $formated_states[$new_key] = $val;
        }
        return $formated_states;
     }

    function tab(){
        $this->tab_name = __('Repurchase', 'cancel-order-request-woocommerce');
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
    if(is_admin()){
        new pisol_corw_reorder_option($this->plugin_name);
    }
});