<?php

class Class_Pi_Edd_Message{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'message';

    private $tab_name = "Advance setting";

    private $setting_key = 'message_settting';

    private $pro_version = false;

    public $tab;

    public $product_location;

    public $category_location;



    function __construct($plugin_name){
        $this->pro_version = pi_edd_pro_check();

        $this->plugin_name = $plugin_name;

        
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        add_action($this->plugin_name.'_tab', array($this,'tab'),2);

        add_action('woocommerce_init', array($this,'shipping_zone_to_array'));
        
        
    }

    function shipping_zone_to_array(){
      
        $this->product_location = array('woocommerce_before_add_to_cart_button'=>__('Before add to cart button','pi-edd'), 'woocommerce_after_add_to_cart_button'=>__('After add to cart button','pi-edd') );
        $this->category_location = array('woocommerce_after_shop_loop_item_title'=>__('After title','pi-edd'), 'woocommerce_shop_loop_item_title'=>__('After Image','pi-edd'), 'woocommerce_after_shop_loop_item'=>__('After price','pi-edd'));
        
        $this->settings = array(
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Single product page setting",'pi-edd'), 'type'=>"setting_category"),
            array('field'=>'pi_show_product_page', 'label'=>__('Show estimate on single product page','pi-edd'),'type'=>'switch', 'default'=>1,   'desc'=>__('Show estimate date on product page','pi-edd'),'pro'=>!$this->pro_version),
            array('field'=>'pi_show_loop_estimate_by_ajax_1', 'label'=>__('Load estimate by Ajax (to over come page caching)','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Ajax will help you avoid product page caching, so it will show correct estimate in spite of page caching ','pi-edd'), 'pro'=>true),
            array('field'=>'pi_edd_product_page_show_estimate_days_count', 'label'=>__('Show estimate as days instead of exact date','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Instead of showing estimate as date <br>Estimate as a date E.g: Estimated delivery by 24 Nov<br>Estimate as a day count E.g: delivery in next 2 days <br> E.g Delivery in next 2 to 6 days','pi-edd'),'pro'=>true),
            array('field'=>'pi_product_page_text', 'label'=>__('Estimated date, Wording on product page','pi-edd'),'type'=>'text', 'default'=>'Estimated delivery date',  'desc'=>__('This will be shown besides the estimated date on single product page, PRO version allows you to show more custom message using short code {date}, {icon}','pi-edd')),
            array('field'=>'pi_product_page_text_range', 'label'=>__('Estimated date, Wording on product page, for date range','pi-edd'),'type'=>'text', 'default'=>'Estimated delivery between',  'desc'=>__('This will be shown besides the estimated date range on single product page when showing date range, PRO version allows you to show more custom message using short code {min_date}, {max_date}, {icon}','pi-edd')),
            array('field'=>'pi_product_page_position', 'label'=>__('Position on single product page','pi-edd'),'type'=>'select', 'default'=>'woocommerce_before_add_to_cart_button',   'desc'=>__('Estimate position on single product page','pi-edd'), 'value'=>$this->product_location),
            
            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Shop / Category page setting",'pi-edd'), 'type'=>"setting_category"),
            array('field'=>'pi_show_product_loop_page', 'label'=>__('Show estimate on product loop page','pi-edd'),'type'=>'switch', 'default'=>1, 'pro'=>!$this->pro_version,  'desc'=>__('Show estimate date on shop page or product category page','pi-edd')),
            array('field'=>'pi_show_loop_estimate_by_ajax_1', 'label'=>__('Load estimate by Ajax  (to over come page caching)','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Ajax will help you avoid archive/category page caching, so it will show correct estimate in spite of page caching','pi-edd'), 'pro'=>true),
            array('field'=>'pi_edd_loop_page_show_estimate_days_count', 'label'=>__('Show estimate as days instead of exact date','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Instead of showing estimate as date <br>Estimate as a date E.g: Estimated delivery by 24 Nov<br>Estimate as a day count E.g: delivery in next 2 days <br> E.g Delivery in next 2 to 6 days','pi-edd'),'pro'=>true),
            array('field'=>'pi_loop_page_text', 'label'=>__('Estimated date, Wording on category / shop page','pi-edd'),'type'=>'text', 'default'=>'Estimated delivery date',  'desc'=>__('This will be shown besides the estimated date on category or shop page, PRO version allows you to show more custom message using short code {date}, {icon}','pi-edd')),
            array('field'=>'pi_loop_page_text_range', 'label'=>__('Estimated date, Wording on category / shop page, for date range','pi-edd'),'type'=>'text', 'default'=>'Estimated delivery between',  'desc'=>__('This will be shown besides the estimated date range on category or shop page when showing date range, PRO version allows you to show more custom message using short code {min_date}, {max_date}, {icon}','pi-edd')),
            array('field'=>'pi_loop_page_position', 'label'=>__('Position on category / shop page','pi-edd'),'type'=>'select', 'default'=>'woocommerce_after_shop_loop_item_title',   'desc'=>__('Estimate position on single product page','pi-edd'), 'value'=>$this->category_location),

            array('field'=>'title', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Cart / Checkout page setting",'pi-edd'), 'type'=>"setting_category"),

            array('field'=>'pi_show_cart_page', 'label'=>__('Show estimate on cart page for each product','pi-edd'),'type'=>'switch', 'default'=>1,   'desc'=>__('Show estimate date on cart page for each product','pi-edd'),'pro'=>true),
            array('field'=>'pi_show_checkout_page', 'label'=>__('Show estimate on checkout page for each product','pi-edd'),'type'=>'switch', 'default'=>1,   'desc'=>__('Show estimate date on checkout page for each product','pi-edd'),'pro'=>true),
           
            array('field'=>'pi_edd_cart_page_show_estimate_days_count', 'label'=>__('Show estimate as days instead of exact date','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Instead of showing estimate as date <br>Estimate as a date E.g: Estimated delivery by 24 Nov<br>Estimate as a day count E.g: delivery in next 2 days <br> E.g Delivery in next 2 to 6 days','pi-edd'),'pro'=>true),
            
            array('field'=>'pi_edd_cart_page_show_overall_estimate', 'label'=>__('Show estimate for complete cart or order (upgrade to pro version 4.0.2)','pi-edd'),'type'=>'switch', 'default'=>1,   'desc'=>__('When you enable this it will show the estimate time for the complete cart, (it takes estimate of all the product in cart and show the largest date as the estimate,<strong>if estimate date of Product A = March 5, Product B = March 7 the the estimate of complete cart will be March 7</strong>)','pi-edd'), 'pro'=>true),
            

            array('field'=>'pi_cart_page_text', 'label'=>__('Estimated date, Wording on cart / checkout page','pi-edd'),'type'=>'text', 'default'=>'Estimated delivery date',  'desc'=>__('This will be shown besides the estimated date on cart or checkout page, PRO version allows you to show more custom message using short code {date}','pi-edd')),
            array('field'=>'pi_cart_page_text_range', 'label'=>__('Estimated date, Wording on cart / checkout page, for date range','pi-edd'),'type'=>'text', 'default'=>'Estimated delivery between',  'desc'=>__('This will be shown besides the estimated date range on cart or checkout page when showing date range, PRO version allows you to show more custom message using short code {min_date}, {max_date}','pi-edd')),

            array('field'=>'title', 'class'=> 'pro-feature bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Order detail and Order email",'pi-edd'), 'type'=>"setting_category"),

            array('field'=>'pi_edd_email_show_estimate_days_count', 'label'=>__('Show estimate as days instead of exact date in Email and order detail','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Instead of showing estimate as date <br>Estimate as a date E.g: Estimated delivery by 24 Nov<br>Estimate as a day count E.g: delivery in next 2 days <br> E.g Delivery in next 2 to 6 days','pi-edd'), 'pro'=>true),

            array('field'=>'pi_edd_cart_page_show_single_estimate', 'label'=>__('Add estimate date for each product in stored order and email','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('It will add estimate for each product in : order stored in backend, order email send to admin and customer','pi-edd'), 'pro'=>true),
           
            array('field'=>'pi_edd_show_overall_estimate_in_email', 'label'=>__('Show estimate for complete order in order email','pi-edd'),'type'=>'switch', 'default'=>1,   'desc'=>__('When you enable this it will show the estimate time for the complete order in order email, (it takes estimate of all the product in cart and show the largest date as the estimate,<strong>if estimate date of Product A = March 5, Product B = March 7 the the estimate of complete cart will be March 7</strong>)','pi-edd'), 'pro'=>true),

            array('field'=>'pi_edd_show_overall_estimate_in_order_success_page', 'label'=>__('Show estimate for complete order in order success page','pi-edd'),'type'=>'switch', 'default'=>1,   'desc'=>__('When you enable this it will show the estimate time for the complete order on order success page, (it takes estimate of all the product in cart and show the largest date as the estimate,<strong>if estimate date of Product A = March 5, Product B = March 7 the the estimate of complete cart will be March 7</strong>)','pi-edd'), 'pro'=>true),

            array('field'=>'pi_overall_estimate_text', 'label'=>__('Overall estimate wording','pi-edd'),'type'=>'text', 'default'=>'Order estimated delivery date {date}',  'desc'=>__('This will show the overall estimate of the order on checkout page and order email, and stored in the order using short code {date}, {days} to show estimate as number of days count','pi-edd'), 'pro'=>true),

            array('field'=>'pi_overall_estimate_range_text', 'label'=>__('Overall estimate wording for range','pi-edd'),'type'=>'text', 'default'=>'Order estimated delivery between {min_date} - {max_date}',  'desc'=>__('This will show the overall estimate of the order on checkout page and order email, and stored in the order, using short code {min_date}, {min_days}, {max_date}, {max_days} to show estimate as number of days count','pi-edd'), 'pro'=>true),
           
            array('field'=>'title', 'class'=> 'pro-feature bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Show estimated time below each shipping methods on cart/checkout",'pi-edd'), 'type'=>"setting_category"),
            array('field'=>'pi_edd_show_estimate_on_each_method1', 'label'=>__('Add estimate date for each of the shipping method below their name on cart/checkout page','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('This will show the estimated time for each shipping method in a zone just below their name, so the user can select the based needed method for faster delivery','pi-edd'), 'pro'=>true),
            array('field'=>'pi_edd_estimate_message_below_shipping_method_range', 'label'=>__('Message template to show range of estimated time below each shipping method','pi-edd'),'type'=>'text', 'default'=>'Delivery by {min_date} - {max_date}',  'desc'=>__('This will be shown below each shipping method name on cart/checkout page, using short code {min_date}, {min_days}, {max_date}, {max_days} to show estimate as number of days count','pi-edd'), 'pro'=>true),
            array('field'=>'pi_edd_estimate_message_below_shipping_method_single_date', 'label'=>__('This template is used when Min and Max estimate date are same','pi-edd'),'type'=>'text', 'default'=>'Delivery by {date}',  'desc'=>__('This message will be used when the min and max estimate is same date, and using the range template will be useless <strong>(E.g this will be meaning less Delivery by 3rd Mach - 3rd March) so we use this template</strong>, use short code {date} or {days}','pi-edd'), 'pro'=>true),

            array('field'=>'title', 'class'=> 'pro-feature bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__("Special working when estimate comes out to be Today/Tomorrow",'pi-edd'), 'type'=>"setting_category"),
            array('field'=>'pi_edd_estimate_message_same_day_delivery', 'label'=>__('Same day delivery message','pi-edd'),'type'=>'text', 'default'=>'Delivery by Today',  'desc'=>__('This message will be shown as estimate, when the min and max estimate date is same and that date is today itself (that is for same day delivery)','pi-edd'), 'pro'=>true),
            array('field'=>'pi_edd_estimate_message_tomorrow_delivery', 'label'=>__('Tomorrow delivery message','pi-edd'),'type'=>'text', 'default'=>'Delivery by Tomorrow',  'desc'=>__('This message will be shown as estimate, when the min and max estimate date is same and that date is today itself (that is for same day delivery)','pi-edd'), 'pro'=>true),
        );
        $this->register_settings();

        if(PISOL_EDD_DELETE_SETTING){
            $this->delete_settings();
        }
    }

    
    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }


    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $this->tab_name = __('Advance setting','pi-edd');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-admin-settings"></span> <?php echo  $this->tab_name; ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_edd($setting, $this->setting_key);
            }
        ?>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="<?php _e('Save Option','pi-edd'); ?>" />
        </form>
       <?php
    }
}

new Class_Pi_Edd_Message($this->plugin_name);
