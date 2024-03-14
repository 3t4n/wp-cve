<?php

class Class_Pi_Edd_Option{

    public $plugin_name;

    private $settings = array();

    private $active_tab;

    private $this_tab = 'basic_setting';

    private $tab_name = "Basic setting";

    private $setting_key = 'basic_setting_edd';

    private $shipping_zones =array();

    private $date_format = array(); 
    
    private $pro_version = false;

    public $tab;

    function __construct($plugin_name){
        $this->tab_name = __("Basic setting",'pi-edd');
        $this->plugin_name = $plugin_name;

        $this->date_format = $this->date_format();
        
        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        if($this->this_tab == $this->active_tab){
            add_action($this->plugin_name.'_tab_content', array($this,'tab_content'));
        }

        $this->pro_version = pi_edd_pro_check();

        add_action($this->plugin_name.'_tab', array($this,'tab'),2);

        add_action('woocommerce_init', array($this,'shipping_zone_to_array'));
        
        
    }

    function shipping_zone_to_array(){
       // WC();
        $zones = WC_Shipping_Zones::get_zones();
        $this->shipping_zones = $this->zone_to_array($zones);
        
        
        $this->settings = array(
            array('field'=>'pi_edd_enable_estimate', 'label'=>__('Enable estimate','pi-edd'),'type'=>'switch', 'default'=> 1,   'desc'=>__('Using this you can disable the estimate without disabling the plugin','pi-edd')),
            array('field'=>'pi_defaul_shipping_zone', 'label'=>__('Default shipping zone','pi-edd'),'type'=>'select', 'default'=> 0, 'value'=>$this->shipping_zones,  'desc'=>__('This shipping zone will be used as default to calculate the estimated delivery time, till user select there shipping address','pi-edd')),
            array('field'=>'pi_edd_min_max', 'label'=>__('Show the best/worst estimate delivery time','pi-edd'),'type'=>'select', 'default'=>'min', 'value'=>array('min'=>__('Best estimate','pi-edd'), 'max'=>__('Worst estimate','pi-edd')),  'desc'=>__('Gets the minimum or maximum shipping days from the different shipping methods in the selected shipping zone','pi-edd')),
            array('field'=>'pi_general_date_format', 'label'=>__('Date format','pi-edd'),'type'=>'select', 'default'=>'Y/m/d',   'desc'=>__('Date format for the estimate date','pi-edd'), 'value'=>$this->date_format, 'pro'=>!$this->pro_version ),
            array('field'=>'pi_general_range', 'label'=>__('Range of delivery date','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Show a range of delivery date if you have more then one shipping class for that shipping zone','pi-edd')),
            array('field'=>'pi_estimate_in_order_detail', 'label'=>__('Show estimated date in order detail','pi-edd'),'type'=>'switch', 'default'=>0,   'desc'=>__('Show estimated date range in order detail, order email (upgrade to pro version 3.0.3)','pi-edd'), 'pro'=>!$this->pro_version),

            array('field'=>'pi_shipping_breakup_time', 'label'=>__('Last shipping time of the day (order coming after this time will be shipped next date)','pi-edd'),'type'=>'text', 'desc'=>__('If the order is placed before this time then you can ship the product today, and so today will be counted in calculating the estimate, and if the order is placed after this time then the shipping can be done next date, so today will not be counted for estimate calculation, if you leave this blank counting will be done from next date','pi-edd'), 'pro'=>true),
            
            array('field'=>'pi_days_of_week'),
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

    function zone_to_array($zones){
        $select[0] = "Select shipping zone";
        foreach($zones as $zone){
            $zone_obj = new WC_Shipping_Zone($zone['zone_id']);
            $methods = $zone_obj->get_shipping_methods(true);
            if(count($methods) > 0){
                $select[$zone['zone_id']] =  $zone['zone_name'];
            }
        }
        return $select;
    }


    function register_settings(){   

        foreach($this->settings as $setting){
            register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $this->tab_name = __('Basic setting','pi-edd');
        ?>
        <a class=" px-3 text-light d-flex align-items-center  border-left border-right  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.sanitize_text_field($_GET['page']).'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-admin-settings"></span> <?php echo $this->tab_name; ?> 
        </a>
        <?php
    }

    function tab_content(){
        if(count($this->shipping_zones) <= 1){
            echo '<div class="alert alert-primary mt-2">You must have shipping zones to use this setting, so create shipping zone in WooCommerce</div>';
            return;
        }
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form_edd($setting, $this->setting_key);
            }
        ?>
        <div class="pro-feature row py-4 border-bottom align-items-center bg-primary text-light">
            <div class="col-12">
            <h2 class="mt-0 mb-0 text-light font-weight-light h5"><?php echo __('Select days to skip in counting of holidays <br>(most shipping don\'t work on weekends so you can select Saturday, Sunday and all the saturday and sundays will not be counted in calculating estimated shipping date)','pi-edd'); ?></h2>
            <h2 class="text-dark"><?php echo __('Only in PRO','pi-edd'); ?></h2>
            </div>
        </div>
        <div class="row py-5 border-bottom align-items-center free-version">
            <div class="col-12 col-md-5">
                <label class="h6 mb-0" for="pi_days_of_week"><?php echo __('Select days of week when your shipping is closed','pi-edd'); ?><br><small class="text-primary"><?php echo __('E.g: If your shipping company is closed on say Saturday and Sunday then add that in this field','pi-edd'); ?></small></label>
            </div>
            <div class="col-12 col-md-7">
                <select name="pi_days_of_week[]" id="pi_days_of_week" class="form-control" multiple="multiple">
                    <option value="1" >Monday</option>
                    <option value="2" >Tuesday</option>
                    <option value="3" >Wednesday</option>
                    <option value="4" >Thursday</option>
                    <option value="5" >Friday</option>
                    <option value="6" selected="selected" >Saturday</option>
                    <option value="7"  selected="selected" >Sunday</option>
                </select>
            </div>
        </div>
        <div class="row py-4 border-bottom align-items-center free-version">
            <div class="col-12 col-md-5">
                <label class="h6 mb-0" for="pi_shop_closed_days"><?php echo __('Select days of week when your Shop is closed','pi-edd'); ?> <br><small class="text-primary"><?php echo __('E.g: If your shop is closed on say Saturday and sunday then add that in this field','pi-edd'); ?></small></label>
            </div>
            <div class="col-12 col-md-7">
                <select name="pi_shop_closed_days[]" id="pi_shop_closed_days" class="form-control" multiple="multiple">
                <option value="1" >Monday</option>
                    <option value="2" >Tuesday</option>
                    <option value="3" >Wednesday</option>
                    <option value="4" >Thursday</option>
                    <option value="5" >Friday</option>
                    <option value="6" selected="selected" >Saturday</option>
                    <option value="7"  selected="selected" >Sunday</option>
                </select>
            </div>
        </div>
        <input type="submit" class="mt-3 btn btn-primary btn-sm" value="<?php _e('Save Option','pi-edd'); ?>" />
        </form>
       <?php
    }

    function date_format(){
        $date = array();
        $date['Y/m/d'] = date('Y/m/d'); 
        $date['d/m/Y'] = date('d/m/Y');
        $date['m/d/y'] = date('m/d/y');
        $date['Y-m-d'] = date('Y-m-d'); 
        $date['d-m-Y'] = date('d-m-Y');
        $date['m-d-y'] = date('m-d-y');
        $date['Y.m.d'] = date('Y.m.d'); 
        $date['d.m.Y'] = date('d.m.Y');
        $date['m.d.y'] = date('m.d.y');
        $date["M j, Y"] = date("M j, Y");
        $date["jS \of F"] = date("jS \of F");
        $date["jS F"] = date("jS F");
        $date["j. F"] = date("j. F");
        $date["l j. F"] = date("l j. F");
        $date["F jS"] = date("F jS");
        $date["jS M"] = date("jS M");
        $date["M jS"] = date("M jS");
        return $date;
    }
}

new Class_Pi_Edd_Option($this->plugin_name);