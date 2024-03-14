<?php

class pisol_dtt_option_time_slot{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'pi_time_slot';

    private $tab_name = "Time Slot";

    private $setting_key = 'pisol_time_slot';

    private $pro_version = false;

    public $tab;
    

    function __construct(){

        $this->pro_version = pi_dtt_pro_check();

        $this->tab = sanitize_text_field(filter_input( INPUT_GET, 'tab'));
        $this->active_tab = $this->tab != "" ? $this->tab : 'default';

        $this->settings = array(
               
                array('field'=>'pi_general_time_slot_delivery'),
                array('field'=>'pi_general_time_slot_pickup')
                
            );
        

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),3);
        
        $this->register_settings();

        
    }

    function register_settings(){   

        foreach($this->settings as $setting){
                register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function tab(){
        $page = sanitize_text_field(filter_input( INPUT_GET, 'page'));
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?> " href="<?php echo admin_url( 'admin.php?page='.$page.'&tab='.$this->this_tab ); ?>">
        <span class="dashicons dashicons-dashboard"></span>  <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
        </a>
        <?php
    }

    function tab_content(){
       ?>
        <form method="post" action="options.php"  class="pisol-setting-form">
        <?php settings_fields( $this->setting_key ); ?>
        <?php
            echo '<script>';
            foreach($this->settings as $setting){
                $val = pisol_dtt_get_setting($setting['field'], array());
                if(!is_array($val) || count($val) < 1){
                    $val = array();
                }
            ?>
            var <?php echo $setting['field']; ?> = <?php echo json_encode(array_values($val)); ?>;
            <?php
            }
            echo '</script>';
        ?>
        <?php
            foreach($this->settings as $setting){
                new pisol_class_form($setting, $this->setting_key);
            }

            $this->timesForm(__('General Time Slot (this will be applied for all days of the week)', 'pisol-dtt' ), 'pi_general_time_slot_delivery', 'pi_general_time_slot_pickup');

            $this->proFeatureRow(__('Monday (Set different time slot for Monday)', 'pisol-dtt' ), 'pi_monday_time_slot_delivery', 'pi_monday_time_slot_pickup','monday1');
            $this->proFeatureRow(__('Tuesday (Set different time slot for Tuesday)', 'pisol-dtt' ), 'pi_tuesday_time_slot_delivery', 'pi_tuesday_time_slot_pickup','tuesday1');
            $this->proFeatureRow(__('Wednesday (Set different time slot for Wednesday)', 'pisol-dtt' ), 'pi_wednesday_time_slot_delivery', 'pi_wednesday_time_slot_pickup','wednesday1');
            $this->proFeatureRow(__('Thursday (Set different time slot for Thursday)', 'pisol-dtt' ), 'pi_Thursday_time_slot_delivery', 'pi_Thursday_time_slot_pickup','Thursday1');
            $this->proFeatureRow(__('Friday (Set different time slot for Friday)', 'pisol-dtt' ), 'pi_Friday_time_slot_delivery', 'pi_Friday_time_slot_pickup','Friday1');
            $this->proFeatureRow(__('Saturday (Set different time slot for Saturday)', 'pisol-dtt' ), 'pi_Saturday_time_slot_delivery', 'pi_Saturday_time_slot_pickup','Saturday1');
            $this->proFeatureRow(__('Sunday (Set different time slot for Sunday)', 'pisol-dtt' ), 'pi_Sunday_time_slot_delivery', 'pi_Sunday_time_slot_pickup','Sunday1');


        ?>
        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-md my-3" value="<?php echo __('Save Changes','pisol-dtt'); ?>">
        </form>
       <?php
    }

    function timesForm($label, $field_delivery, $field_pickup, $overwrite_day = ""){
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
        <div id="row_sunday" class="row py-2 border-bottom align-items-center <?php echo $bg; ?> text-light">
            <div class="col-9">
            <?php if(empty($overwrite_day)){ ?>
                <h5 class="mt-0 mb-0 py-2 text-light font-weight-light h4"><label for="pi_different_slot_for_<?php echo $overwrite_day; ?>"><?php echo $label; ?></label></h5>
            <?php }else{ ?>
            <h5 class="mt-0 mb-0 text-light font-weight-light h5"><label for="pi_different_slot_for_<?php echo $overwrite_day; ?>"><?php echo $label; ?></label></h5>
            <?php } ?>
            </div>
            <?php if(!empty($overwrite_day)): ?>
                <div class="col-3 text-right">
                    <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" name="pi_different_slot_for_<?php echo $overwrite_day; ?>" id="pi_different_slot_for_<?php echo $overwrite_day; ?>" <?php echo $checked; ?>>
                    <label class="custom-control-label" for="pi_different_slot_for_<?php echo $overwrite_day; ?>"></label>
                    </div>
                </div>
            <?php endif; ?>
        </div>
        
        <div class="row mb-2 pt-2" id="pi_slots_for_<?php echo $overwrite_day; ?>" <?php echo $hide; ?>>
            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col-7 mt-2">
                        <strong><?php _e('Delivery Time slots', 'pisol-dtt' ); ?></strong>
                    </div>
                    <div class="col">
                        <a class="btn btn-primary btn-sm pi_add_time_slot text-light" data-slot="<?php echo $field_delivery; ?>" ><?php _e('Add Time Slot', 'pisol-dtt' ); ?></a>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 mt-2">
                        <div id="<?php echo $field_delivery; ?>_container">

                        </div>
                    </div>
                </div>
            </div>
        

            <div class="col-12 col-md-6">
                <div class="row align-items-center">
                    <div class="col-7 mt-2">
                        <strong><?php _e('Pickup Time slots', 'pisol-dtt' ); ?></strong>
                    </div>
                    <div class="col">
                        <a class="btn btn-primary btn-sm pi_add_time_slot text-light" data-slot="<?php echo $field_pickup; ?>" ><?php _e('Add Time Slot', 'pisol-dtt' ); ?></a>
                    </div>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 mt-2">
                        <div id="<?php echo $field_pickup; ?>_container">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
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
            <div class="col-9">
            <?php if(empty($overwrite_day)){ ?>
                <h5 class="mt-0 mb-0 py-2 text-light font-weight-light h4"><label for="pi_different_slot_for_<?php echo $overwrite_day; ?>"><?php echo $label; ?></label></h5>
            <?php }else{ ?>
            <h5 class="mt-0 mb-0 text-light font-weight-light h5"><label for="pi_different_slot_for_<?php echo $overwrite_day; ?>"><?php echo $label; ?></label></h5>
            <?php } ?>
            </div>
            <?php if(!empty($overwrite_day)): ?>
                <div class="col-3 text-right">
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

new pisol_dtt_option_time_slot();
