<?php

class pisol_dtt_option{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'default';

    private $tab_name = "General setting";

    private $setting_key = 'pisol_dtt_default';

    private $pro_version = false;

    public $billing_fields = array(
        'billing_first_name'=>'Billing First Name',
        'billing_last_name'=>'Billing Last Name',
        'billing_address_1'=>'Billing Address 1',
        'billing_address_2'=>'Billing Address 2',
        'billing_country'=>'Billing Country',
        'billing_city'=>'Billing City',
        'billing_state'=>'Billing State',
        'billing_postcode'=>'Billing Postcode',
        'billing_company'=>'Billing Company',
        'billing_phone'=>'Billing Phone'
    );

    public $tab;

    public $preparation_days;

    function __construct(){

        $this->pro_version = pi_dtt_pro_check();

        $this->preparation_days = range(0, 100);
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';
        
        $this->migrationScript();

        $this->settings = array(

                array('field'=>'pi_use_time_slot', 'label'=>__('Use Time slot or Time rage for timing','pisol-dtt'),'desc'=> __('You can decide when to use time slot or time range','pisol-dtt'), 'type'=>'select', 'value'=>array('all_slot'=>__('Time slot for delivery and pickup both','pisol-dtt'), 'all_range' =>__('Time range for delivery and pickup both','pisol-dtt'), 'pickup_slot_delivery_range'=>__('Time slot for pickup type and Time range for delivery type','pisol-dtt'), 'delivery_slot_pickup_range'=>__('Time slot for delivery type and Time range for pickup type','pisol-dtt')), 'default'=>"all_range"),

                array('field'=>'pi_type', 'label'=>__('Enable delivery or pickup or both','pisol-dtt'),'desc'=> __('you can have only delivery, only pickup or you can give option to select either of them','pisol-dtt'), 'type'=>'select', 'value'=>array('Both'=>__('Both','pisol-dtt'), 'Delivery' =>__('Delivery','pisol-dtt'), 'Pickup'=>__('Pickup','pisol-dtt')), 'default'=>"Both"), 

                array('field'=>'pi_datetime', 'label'=>__('Enable delivery date and time','pisol-dtt'),'desc'=>__('Enable or disable pickup delivery date and time option from front end <strong class="text-primary">E.g: you may want to show date and time option when user want to pickup, and dont want to show when user select delivery</strong>','pisol-dtt'), 'type'=>'select', 'value'=>array('disable-both'=>__('Disable for both (pickup and delivery)','pisol-dtt'), 'enable-both'=>__('Enable for both (pickup and delivery)','pisol-dtt'), 'pickup-only'=>__('Enable only for Pickup type','pisol-dtt'), 'delivery-only'=>__('Enable only for delivery type','pisol-dtt')), 'default'=>'enable-both'), 

                array('field'=>"pi_date_time_location-pro",'type'=>'select', 'label'=>__('Position of the Date and time option on checkout page','pisol-dtt'), 'desc'=>__('Using this you can change the location of the data and time option on checkout page','pisol-dtt'), 'default'=>'woocommerce_checkout_before_customer_details', 'value'=>array('woocommerce_checkout_before_customer_details'=>__('Before customer detail','pisol-dtt'), 'woocommerce_checkout_after_customer_details'=>__('After customer detail','pisol-dtt')), 'pro'=>true), 

                array('field'=>'pi_enable_delivery_time', 'label'=>__('Enable delivery / pickup time option','pisol-dtt'),'desc'=> __('you can enable delivery time option','pisol-dtt'), 'type'=>'select', 'value'=>array('enable-both'=>__('Enabled for pickup and delivery','pisol-dtt'),'disable-both' =>__('Disabled for pickup and delivery','pisol-dtt'), 'only-pickup'=>__('Enable only for pickup','pisol-dtt'),'only-delivery'=>__('Enable only for delivery','pisol-dtt') ), 'default'=>"enable-both"),

                array('field'=>'pi_preorder_days', 'label'=>__('Preorder days','pisol-dtt'), 'desc'=>__('How many days ahead a delivery or pickup date can be set','pisol-dtt'), 'type'=>'number', 'default'=>10, 'min'=> 0),

                array('field'=>'pi_enable_different_preparation_time-pro', 'class'=> 'bg-secondary text-light free-version', 'default'=>0, 'type'=>'switch_category', 'label'=>__('Set different preparation time for Pickup & Delivery order','pisol-dtt'),'desc'=>__('Allow you to set different preparation time for pickup order','pisol-dtt')),
                
                array('field'=>'pi_order_preparation_days', 'label'=>__('Order preparation days','pisol-dtt'), 'desc'=>__('How many days you need to prepare some order','pisol-dtt'), 'type'=>'select', 'default'=> 1, 'value'=>$this->preparation_days),

                array('field'=>'pi_order_preparation_hours', 'label'=>__('Order preparation minutes (only used when order preparation days is zero)','pisol-dtt'), 'desc'=>__('Order preparation time in minutes (only used when order preparation days is ZERO','pisol-dtt'), 'type'=>'number', 'min'=> 0, 'default'=> 60),

                array('field'=>'same-day-cutoff-section', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Same day Delivery/Pickup Cutoff time (Only useful when order preparation days is 0)','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_same_day_delivery_cutoff_time-pro', 'label'=>__('Same Day delivery cutoff time','pisol-dtt'), 'desc'=>__('Once this time goes by today, user cant place a delivery order for today date','pisol-dtt'), 'type'=>'text', 'pro'=>true),
                
                array('field'=>'pi_same_day_pickup_cutoff_time-pro', 'label'=>__('Same Day pickup cutoff time','pisol-dtt'), 'desc'=>__('Once this time goes by today, user cant place a pickup order for today date','pisol-dtt'), 'type'=>'text', 'pro'=>true),

                array('field'=>'next-day-cutoff-section', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h6', 'label'=>__('Next day Delivery/Pickup Cutoff time (Only useful when order preparation days is less then 2)','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_next_day_delivery_cutoff_time-pro', 'label'=>__('Next Day delivery cutoff time','pisol-dtt'), 'desc'=>__('Once this time goes by today, user cant place a delivery order for tomorrows date','pisol-dtt'), 'type'=>'text', 'pro'=>true),

                array('field'=>'pi_next_day_pickup_cutoff_time-pro', 'label'=>__('Next Day pickup cutoff time','pisol-dtt'), 'desc'=>__('Once this time goes by today, user cant place a pickup order for tomorrows date','pisol-dtt'), 'type'=>'text', 'pro'=>true),


                array('field'=>'pi_default_type', 'label'=>__('Default Delivery Type','pisol-dtt'),'desc'=>__('When user go to checkout page this will be the default selected delivery type, this only work when you have selected "BOTH" at the top option "Enable delivery or pickup option or Both"','pisol-dtt'), 'type'=>'select', 'value'=>array('delivery' =>__('Delivery','pisol-dtt'), 'pickup'=>__('Pickup','pisol-dtt')), 'default'=>'delivery'), 

                array('field'=>'pi_time_format', 'label'=>__('Time Format','pisol-dtt'),'desc'=>__('Time format to use 12 hr or 24 hr time format','pisol-dtt'), 'type'=>'select', 'value'=>array('h:mm p' =>__('12HR (1:30 PM)','pisol-dtt'), 'H:mm'=>__('24HR (13:30)','pisol-dtt'), 'H:mm p'=>__('24HR with AM PM (13:30 PM)','pisol-dtt')), 'default'=>'h:mm p'), 

                array('field'=>'pi_time_interval', 'label'=>__('Time Interval in minutes','pisol-dtt'), 'type'=>'select', 'value'=>array(5 =>__('5 minutes','pisol-dtt'), 10 =>__('10 minutes','pisol-dtt'), 15 =>__('15 minutes','pisol-dtt'), 20=>__('20 minutes','pisol-dtt'), 30=>__('30 minutes','pisol-dtt'), 40=>__('40 minutes','pisol-dtt'), 50=>__('50 minutes','pisol-dtt'), 60=>__('1 hr','pisol-dtt'), 120=>__('2 hour','pisol-dtt'), 180=>__('3 hour','pisol-dtt'), 240=>__('4 hour','pisol-dtt'), 300=>__('5 hour','pisol-dtt'), 360=>__('6 hour','pisol-dtt')), 'default'=>15,'desc'=>__('This allows you to set time gat between 2 time slot when you are using Time Range method','pisol-dtt')),
                 
                array('field'=>'pi_time_required-pro', 'default'=>1, 'type'=>'select', 'label'=>__('Make delivery/pickup time as required field in checkout','pisol-dtt'),'desc'=>__('By default this is required field, but you can make it non-required in checkout, based on delivery types, e.g: you can make time as required field if delivery method is pickup and non required if delivery method is delivery','pisol-dtt'),'value'=>array('0'=>__('Non required in pickup and delivery','pisol-dtt'), '1'=>__('Required in pickup and delivery','pisol-dtt'), 'pickup-required'=>__('Required in pickup','pisol-dtt'), 'delivery-required'=>__('Required in delivery','pisol-dtt')), 'pro'=>true),

                array('field'=>'pi_date_required-pro', 'default'=>'1', 'type'=>'select', 'label'=>__('Make delivery/pickup Date as required field in checkout','pisol-dtt'),'desc'=>__('By default this is required field, but you can make it non-required in checkout, based on delivery types, <strong>When delivery date is not required then delivery time will also be not required by default</strong>','pisol-dtt'),'value'=>array('0'=>__('Non required in pickup and delivery','pisol-dtt'), '1'=>__('Required in pickup and delivery','pisol-dtt'), 'pickup-required'=>__('Required in pickup','pisol-dtt'), 'delivery-required'=>__('Required in delivery','pisol-dtt')), 'pro'=>true),

                
                
                array('field'=>'color-setting1', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Shipping method option for pickup type','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_remove_shipping_method-pro', 'label'=>__('Remove shipping method','pisol-dtt'),'desc'=> __('If enabled it will remove all shipping method when pickup delivery type is selected, if disabled it will try to select the WooCommerce "local pickup" shipping method (provided it is available) , if local pickup is available it will be selected and other method will not be allowed to select','pisol-dtt'), 'type'=>'switch', 'default'=>1, 'pro'=>true),

                array('field'=>'pi_show_as_dropdown-pro', 'default'=>0, 'type'=>'switch', 'label'=>__('Show pickup location as Dropdown','pisol-dtt'),'desc'=>__('If you have large number of pickup location then you can show then as dropdown instead of showing them as full address as radio button','pisol-dtt'),'pro'=>true),

                array('field'=>'color-setting1', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Checkout form fields as per delivery type','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_dtt_remove_billing_when_pickup-pro','desc'=>'Select fields you want to remove from checkout When delivery type is Pickup', 'label'=>__('Remove checkout fields when Pickup','pi-dcw'),'type'=>'multiselect','default'=>array_keys($this->billing_fields), 'value'=>$this->billing_fields,'pro'=>true),

                array('field'=>'pi_dtt_remove_billing_when_delivery-pro','desc'=>'Select fields you want to remove from checkout When delivery type is Delivery', 'label'=>__('Remove checkout fields when Delivery','pi-dcw'),'type'=>'multiselect','default'=>array_keys($this->billing_fields), 'value'=>$this->billing_fields,'pro'=>true),

                array('field'=>'pi_first_day_in_calender-pro', 'label'=>__('First day in calender','pisol-dtt'), 'type'=>'select', 'value'=>array(1 =>__('Monday','pisol-dtt'), 2=>__('Tuesday','pisol-dtt'), 3=>__('Wednesday','pisol-dtt'), 4=>__('Thursday','pisol-dtt'), 5=>__('Friday','pisol-dtt'), 6=>__('Saturday','pisol-dtt'), 7=>__('Sunday','pisol-dtt')), 'default'=>1,'desc'=>__('Using this you can change the order of the Week on the front end calender, when you select "monday" calender will start from monday, when you select sunday calender will start from sunday as first day of the week','pisol-dtt'), 'pro'=>true), 

                array('field'=>'pi_enable_zone_for_location-pro', 'default'=>0, 'type'=>'switch', 'label'=>__('Enable shipping zone option for pickup location','pisol-dtt'),'desc'=>__('you can assign shipping zone to pickup location, so the buyer will only see those pickup location that you have assigned for there shipping zones, E.g: if you have pickup location in all the states in your country so instead of showing all you will assign each pickup location a shipping zone (zones will be based on city) so the buyer will only see the pickup location near to there city','pisol-dtt'),'pro'=>true),

                array('field'=>'color-setting1', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Remove plugin fields if virtual product in cart','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_virtual_product_disable_plugin-pro', 'label'=>__('Disable plugin if there is any virtual product in customer cart','pisol-dtt'),'desc'=> __('you can either do nothing and leave the plugin active, or you can deactivate plugin setting if there is even single virtual product present in the customer cart, or if there are more virtual product in cart then physical product','pisol-dtt'), 'type'=>'select', 'value'=>array('dont_disable'=>__('Don\'t disable','pisol-dtt'), 'virtual_present' =>__('If there is even single virtual product in cart','pisol-dtt'), 'large_no_virtual'=>__('Disable if there are more virtual product then normal product','pisol-dtt'), 'all_virtual'=>__('Disable if all are virtual product in cart','pisol-dtt')), 'default'=>"dont_disable", 'pro'=>true),

                array('field'=>'color-setting100', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Payment method options (control which payment method to show for which delivery type)','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_remove_payment_methods_for_delivery-pro','desc'=>__('Select payment methods you want to remove for delivery','pisol-dtt'), 'label'=>__('Remove Payment methods for Delivery order','pisol-dtt'),'type'=>'multiselect','default'=>array(),  'value'=>$this->paymentMethods(),'pro'=>true),

                array('field'=>'pi_remove_payment_methods_for_pickup-pro','desc'=>__('Select payment methods you want to remove for pickup','pisol-dtt'), 'label'=>__('Remove Payment methods for Pickup order','pisol-dtt'),'type'=>'multiselect','default'=>array(), 'value'=>$this->paymentMethods(),  'pro'=>true),

                array('field'=>'pi_enable_woocommerce_app_support', 'default'=>0, 'type'=>'switch', 'label'=>__('Enable woocommerce app support','pisol-dtt'),'desc'=>__('when enabled it will add the date, time, pickup location detail in the order note section, that you will be able to see in the WooCommerce app as well','pisol-dtt'))
            );
        

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),1);
        
        $this->register_settings();

        if(PISOL_DTT_FREE_RESET_SETTING){
            $this->delete_settings();
        }

    }

    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function paymentMethods(){
        $gateways = WC()->payment_gateways->get_available_payment_gateways();
        $enabled_gateways = [];

        if( !empty($gateways) ) {
            foreach( $gateways as $gateway ) {

                if( $gateway->enabled == 'yes' ) {

                    $enabled_gateways[$gateway->id] = $gateway->title;

                }
            }
        }
        return $enabled_gateways;
    }

    function register_settings(){   

        foreach($this->settings as $setting){
                register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = sanitize_text_field(filter_input( INPUT_GET, 'page'));
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-admin-home"></span> <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form($setting, $this->setting_key);
            }
        ?>
        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-lg my-3" value="<?php echo __('Save Changes','pisol-dtt'); ?>">
        </form>
       <?php
    }

    function migrationScript(){
        $date_time = get_option('pi_datetime', 'enable-both');

        if($date_time == '0') update_option('pi_datetime', 'disable-both');

        if($date_time == '1') update_option('pi_datetime', 'enable-both');
    }
    
}

add_action('wp_loaded',function(){
new pisol_dtt_option();
});
