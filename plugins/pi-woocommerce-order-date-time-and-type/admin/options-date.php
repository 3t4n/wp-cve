<?php

class pisol_dtt_option_date{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'date';

    private $tab_name = "Date setting";

    private $setting_key = 'pisol_dtt_date';

    public $tab;

    function __construct(){

        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
                array('field'=>'pi_delivery_days', 'label'=>__('We do delivery on this days','pisol-dtt'),'desc'=>__('If you deliver on all days then don\'t select any thing, if you do on specific days then select those days','pisol-dtt'), 'type'=>'multiselect', 'required'=>'required', 'multiple'=>'multiple', 'value'=>array('0'=>__('Sunday','pisol-dtt'), '1'=>__('Monday','pisol-dtt'), '2'=>__('Tuesday','pisol-dtt'), '3'=>__('Wednesday','pisol-dtt'), '4'=>__('Thursday','pisol-dtt'), '5'=>__('Friday','pisol-dtt'), '6'=>__('Saturday','pisol-dtt') )), 

                array('field'=>'pi_pickup_days', 'label'=>__('We do pickup on this days','pisol-dtt'), 'desc'=>__('If you pickup on all days then dont select any thing, if you do on specific days then select those days','pisol-dtt'), 'type'=>'multiselect', 'required'=>'required', 'multiple'=>'multiple', 'value'=>array('0'=>__('Sunday','pisol-dtt'), '1'=>__('Monday','pisol-dtt'), '2'=>__('Tuesday','pisol-dtt'), '3'=>__('Wednesday','pisol-dtt'), '4'=>__('Thursday','pisol-dtt'), '5'=>__('Friday','pisol-dtt'), '6'=>__('Saturday','pisol-dtt') )), 

                array('field'=>'pisol_dtt_pickup_dd'),

                array('field'=>'pisol_dtt_delivery_dd'),
                
            );
        delete_option('pi_dtt_remove_billing_when_pickup-pro'); delete_option('pi_dtt_remove_billing_when_delivery-pro');

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),4);
        
        add_action('pickup_disabled_date', array($this, 'pickup_disabled_date'));
        add_action('delivery_disabled_date', array($this, 'delivery_disabled_date'));

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

    function register_settings(){   

        foreach($this->settings as $setting){
                register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = sanitize_text_field(filter_input( INPUT_GET, 'page'));
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-calendar-alt"></span> <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
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
        <?php do_action('pickup_disabled_date'); ?>
        <?php do_action('delivery_disabled_date'); ?>
        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-lg my-3" value="<?php echo __('Save Changes','pisol-dtt'); ?>">
        </form>
        <div class="free-version">
        <img src="<?php echo plugin_dir_url(__FILE__); ?>img/special-date.png" class="img-fluid">
        </div>
       <?php
    }

   function pickup_disabled_date(){
       $dates =  get_option('pisol_dtt_pickup_dd');
       //print_r($dates);
       ?>
        <hr>
        <h2><?php echo __('Disable PICKUP on this dates','pisol-dtt'); ?></h2>
        <div class='notice-info notice'>
        <p>
        <strong>BUY PRO</strong> version for <strong><?php echo PISOL_DTT_PRICE; ?></strong> and set holidays for complete year at once, as it allow unlimited date selection at once <a href="<?php echo PISOL_DTT_BUY_URL; ?>"  target="_blank">BUY NOW</a>
        </p>
        </div>
        <?php for($x= 0; $x <= 4; $x++){ ?>
        <div class="pisol_grid">
        <div>
            <label for="pi_delivery_days">Disable pickup on:</label><br>
        </div>
        <input type="text" name="pisol_dtt_pickup_dd[]" class="pisol-date-picker" readonly value="<?php echo (isset($dates[$x])? $dates[$x] : ''); ?>">      
        </div>
        <?php } ?>
        <p>
        <a class="btn btn-secondary btn-sm" href="javascript:void(0);"><span class="dashicons dashicons-plus-alt pi-icon"></span> Add More Dates</a> (Only for PRO version <a href="<?php echo PISOL_DTT_BUY_URL; ?>" target="_blank">BUY NOW</a> )
        </p>
       <?php
   }

   function delivery_disabled_date(){
    $dates =  get_option('pisol_dtt_delivery_dd');
    //print_r($dates);
    ?>
     <hr>
     <h2><?php echo __('Disable DELIVERY on this dates','pisol-dtt'); ?></h2>
     <?php for($x= 0; $x <= 4; $x++){ ?>
     <div class="pisol_grid">
     <div>
         <label for="pi_delivery_days">Disable delivery on:</label><br>
     </div>
     <input type="text" name="pisol_dtt_delivery_dd[]" class="pisol-date-picker" readonly  value="<?php echo (isset($dates[$x])? $dates[$x] : ''); ?>">      
     </div>
     <?php } ?>
     <p>
     <a class="btn btn-secondary btn-sm" href="javascript:void(0);"><span class="dashicons dashicons-plus-alt pi-icon"></span> Add More Dates</a> (Only for PRO version <a href="<?php echo PISOL_DTT_BUY_URL; ?>"  target="_blank">BUY NOW</a> )
     </p>
    <?php
}
}

/* if we have pro version setting for time tab then we can disable this */
new pisol_dtt_option_date();


