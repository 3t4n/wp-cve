<?php

class pisol_dtt_option_time{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'time';

    private $tab_name = "Time Range";

    private $setting_key = 'pisol_dtt_time';

    public $tab;

    function __construct(){

        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        

        $this->settings = array(
                array('field'=>'sunday', 'class'=> 'bg-secondary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Set general time range, that will be applied for all days of the week','pisol-dtt'), 'type'=>'setting_category'), 

                array('field'=>'pi_delivery_start_time', 'label'=>__('Delivery start time','pisol-dtt'), 'type'=>'text', 'required'=>'required', 'readonly'=>'readonly'), 

                array('field'=>'pi_delivery_end_time', 'label'=>__('Delivery end time','pisol-dtt'), 'type'=>'text', 'required'=>'required', 'readonly'=>'readonly') , 

                array('field'=>'pi_pickup_start_time', 'label'=>__('Pickup start time','pisol-dtt'), 'type'=>'text', 'required'=>'required', 'readonly'=>'readonly'), 

                array('field'=>'pi_pickup_end_time', 'label'=>__('Pickup end time','pisol-dtt'), 'type'=>'text', 'required'=>'required', 'readonly'=>'readonly')
            );
        

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),2);     
        
        add_action('admin_notices',array($this,'validateTime'));

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
        <span class="dashicons dashicons-dashboard"></span> <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
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
        
        $this->proFeatureRow(__('Monday (Set different time range for Monday)', 'pisol-dtt' ), 'pi_monday_time_slot_delivery', 'pi_monday_time_slot_pickup','monday1');
        $this->proFeatureRow(__('Tuesday (Set different time range for Tuesday)', 'pisol-dtt' ), 'pi_tuesday_time_slot_delivery', 'pi_tuesday_time_slot_pickup','tuesday1');
        $this->proFeatureRow(__('Wednesday (Set different time range for Wednesday)', 'pisol-dtt' ), 'pi_wednesday_time_slot_delivery', 'pi_wednesday_time_slot_pickup','wednesday1');
        $this->proFeatureRow(__('Thursday (Set different time range for Thursday)', 'pisol-dtt' ), 'pi_Thursday_time_slot_delivery', 'pi_Thursday_time_slot_pickup','Thursday1');
        $this->proFeatureRow(__('Friday (Set different time range for Friday)', 'pisol-dtt' ), 'pi_Friday_time_slot_delivery', 'pi_Friday_time_slot_pickup','Friday1');
        $this->proFeatureRow(__('Saturday (Set different time range for Saturday)', 'pisol-dtt' ), 'pi_Saturday_time_slot_delivery', 'pi_Saturday_time_slot_pickup','Saturday1');
        $this->proFeatureRow(__('Sunday (Set different time range for Sunday)', 'pisol-dtt' ), 'pi_Sunday_time_slot_delivery', 'pi_Sunday_time_slot_pickup','Sunday1');

        ?>
        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-lg my-3" value="<?php echo __('Save Changes','pisol-dtt'); ?>">
        </form>
        
       <?php

    }

    function validateTime(){
        $delivery_start = get_option('pi_delivery_start_time', '');
        $delivery_end = get_option('pi_delivery_end_time', '');
        $pickup_start = get_option('pi_pickup_start_time', '');
        $pickup_end = get_option('pi_pickup_end_time', '');
        
        $time_enabled = get_option('pi_datetime', true);

        if(empty($time_enabled)) return;

        $delivery_types = get_option('pi_type', 'Both');

        $range_slot = get_option('pi_use_time_slot','all_range');

        $time_range_page = admin_url('admin.php?page=pisol-dtt&tab=time');
        
        if($range_slot == 'all_range' || $range_slot == 'pickup_slot_delivery_range'){
            if($delivery_types == 'Both' || $delivery_types == 'Delivery'){
                if( !empty($delivery_start) && !empty($delivery_end) ){
                    $delivery_start_time = strtotime($delivery_start);
                    $delivery_end_time = strtotime($delivery_end);

                    if($delivery_start_time > $delivery_end_time){
                        self::adminError('Delivery start time cant be grater then the end time in Time range', $time_range_page);
                    }
                }

                if( empty($delivery_start) && empty($delivery_end) ){
                    
                    self::adminError('Set Delivery timing range else default timing range will be used 9 AM to 9 PM', $time_range_page);
                    
                }
            }
        }

        if($range_slot == 'all_range' || $range_slot == 'delivery_slot_pickup_range'){
            if($delivery_types == 'Both' || $delivery_types == 'Pickup'){
                if(!empty($pickup_start) && !empty($pickup_end) ){
                    $pickup_start_time = strtotime($pickup_start);
                    $pickup_end_time = strtotime($pickup_end);

                    if($pickup_start_time > $pickup_end_time){
                        self::adminError('Pickup start time cant be grater then the end time in Time range', $time_range_page);
                    }
                }

                if( empty($delivery_start) && empty($delivery_end) ){
                    
                    self::adminError('Set Pickup timing range else default timing range will be used 9 AM to 9 PM', $time_range_page);
                    
                }
            }
        }

        $delivery_slots = get_option('pi_general_time_slot_delivery',array());
        $pickup_slots = get_option('pi_general_time_slot_pickup',array());

        $time_slot_page = admin_url('admin.php?page=pisol-dtt&tab=pi_time_slot');

        if(($range_slot == 'all_slot' || $range_slot == 'delivery_slot_pickup_range') && empty($delivery_slots)){
            self::adminError('Configure Delivery time slots in Time slot tab',$time_slot_page);
        }

        if(($range_slot == 'all_slot' || $range_slot == 'pickup_slot_delivery_range')  && empty($pickup_slots)){
            self::adminError('Configure Pickup time slots in Time slot tab',$time_slot_page);
        }
    }

    static function adminError($msg, $page){
        
        echo sprintf('<div class="notice notice-error is-dismissible"><p><strong><span style="color:#f00;">ERROR: </span> %s</strong> <a href="%s">Click here to correct this issue</a></p></div>', $msg, esc_attr($page));
    }

    function proFeatureRow($label, $field_delivery, $field_pickup, $overwrite_day = ""){
        if(empty($overwrite_day)){
            $bg = 'bg-primary';
            $hide="";
        }else{
            $bg = 'bg-dark';
            $overwrite_value = pisol_dtt_get_setting('pi_different_slot_for_'.$overwrite_day, "");
            $checked ="";
            $hide =' style="display:none;" ';
            if(!empty($overwrite_value)){
                $checked = ' checked="checked" ';
                $hide="";
            }
        }
        ?>
        <div id="row_sunday" class="row py-2 border-bottom align-items-center free-version <?php echo $bg; ?> text-light">
            <div class="col-10">
            <?php if(empty($overwrite_day)){ ?>
                <h5 class="mt-0 mb-0 py-2 text-light font-weight-light h4"><label for="pi_different_slot_for_<?php echo $overwrite_day; ?>"><?php echo $label; ?></label></h5>
            <?php }else{ ?>
            <h5 class="mt-0 mb-0 text-light font-weight-light h5"><label for="pi_different_slot_for_<?php echo $overwrite_day; ?>"><?php echo $label; ?></label></h5>
            <?php } ?>
            </div>
            <?php if(!empty($overwrite_day)): ?>
                <div class="col-2 text-right">
                    <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="pi_different_slot_for_<?php echo $overwrite_day; ?>" id="pi_different_slot_for_<?php echo $overwrite_day; ?>" <?php echo $checked; ?>>
                    <label class="custom-control-label" for="pi_different_slot_for_<?php echo $overwrite_day; ?>"></label>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        <?php
    }
   
}

/* if we have pro version setting for time tab then we can disable this */
new pisol_dtt_option_time();


