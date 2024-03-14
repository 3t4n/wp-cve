<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pisol_dtt_option_label{

    private $settings = array();

    private $active_tab;

    private $this_tab = 'label';

    private $tab_name = "Labels";

    private $setting_key = 'pisol_dtt_label';

    private $calendar_themes = array(
        'black-tie', 'blitzer', 'cupertino', 'dark-hive', 'dot-luv', 'eggplant', 'excite-bike', 'flick', 'hot-sneaks', 'humanity', 'le-frog', 'mint-choc', 'overcast', 'pepper-grinder', 'redmond', 'smoothness', 'south-street', 'start', 'sunny', 'swanky-purse', 'trontastic', 'ui-darkness', 'ui-lightness', 'vader'
    );
    

    function __construct(){

        $this->active_tab = (isset($_GET['tab'])) ? sanitize_text_field($_GET['tab']) : 'default';

        $this->settings = array(

                array('field'=>'color-setting', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Checkout form labels','pisol-dtt'), 'type'=>'setting_category'),

                array('field'=>'pi_show_delivery_type_label', 'label'=>__('Show "Delivery type" label','pisol-dtt'),'desc'=>__('using this you can show or hide the "Delivery type *" shown on top of the delivery type selection option','pisol-dtt'), 'type'=>'switch', 'default'=>1),

                
                array('field'=>'color-setting', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Date and time label for email/order success page/backend (when delivery method: pickup)','pisol-dtt'), 'type'=>'setting_category'),
                array('field'=>'pi_date_field_label_pickup', 'label'=>__('Date field label','pisol-dtt'), 'desc'=>__('This label is used for email, order detail page','pisol-dtt'), 'type'=>'text', 'default'=>__('Pickup Date','pisol-dtt'), 'pro'=>true),

                array('field'=>'pi_time_field_label_pickup', 'label'=>__('Time field label','pisol-dtt'), 'desc'=>__('This label is used for email, order detail page','pisol-dtt'), 'type'=>'text', 'default'=>__('Pickup Time','pisol-dtt'), 'pro'=>true),


                array('field'=>'color-setting', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Date and time label for email/order success page/backend (when delivery method: delivery)','pisol-dtt'), 'type'=>'setting_category'),
                array('field'=>'pi_date_field_label_delivery', 'label'=>__('Date field label','pisol-dtt'), 'desc'=>__('This label is used for email, order detail page','pisol-dtt'), 'type'=>'text', 'default'=>__('Delivery Date','pisol-dtt'), 'pro'=>true),
                array('field'=>'pi_time_field_label_delivery', 'label'=>__('Time field label','pisol-dtt'), 'desc'=>__('This label is used for email, order detail page','pisol-dtt'), 'type'=>'text', 'default'=>__('Delivery Time','pisol-dtt'), 'pro'=>true),

                array('field'=>'color-setting1', 'class'=> 'bg-primary text-light hide-pro', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Extra message that you can show for Date and time field','pisol-dtt'), 'type'=>'setting_category'), 
                array('field'=>'pi_extra_message_delivery', 'label'=>__('Message shown when delivery type is selected','pisol-dtt'), 'desc'=>__('Message shown when delivery type is selected, Leave empty if you don\'t want to show message','pisol-dtt'), 'type'=>'text', 'default'=>"" , 'pro'=>true),
                array('field'=>'pi_extra_message_pickup', 'label'=>__('Message shown when pickup type is selected','pisol-dtt'), 'desc'=>__('Message shown when pickup type is selected, , Leave empty if you don\'t want to show message','pisol-dtt'), 'type'=>'text', 'default'=>"", 'pro'=>true),

                array('field'=>"pi_extra_message_position",'type'=>'select', 'label'=>__('Position of the message','pisol-dtt'), 'desc'=>__('position of the message can be above the delivery type button or below the date and time option','pisol-dtt'), 'default'=>'before', 'value'=>array('before'=>__('Before delivery type selection option','pisol-dtt'), 'between'=>__('Between delivery type and date time selection option','pisol-dtt'),'after'=>__('After date and time option','pisol-dtt')), 'pro'=>true), 

                array('field'=>'pi_extra_message_bg_color', 'default'=>'#cccccc', 'type'=>'color', 'label'=>__('Message box background color','pisol-dtt'),'desc'=>'', 'pro'=>true),
                array('field'=>'pi_extra_message_text_color', 'default'=>'#000000', 'type'=>'color', 'label'=>__('Message box text color','pisol-dtt'),'desc'=>'', 'pro'=>true),

                

                array('field'=>'color-setting', 'class'=> 'bg-primary text-light', 'class_title'=>'text-light font-weight-light h4', 'label'=>__('Delivery type button on checkout form','pisol-dtt'), 'type'=>'setting_category'),
                array('field'=>'pi_pickup_label', 'label'=>__('Pickup label','pisol-dtt'), 'desc'=>__('The text that will be shown on the pickup button on the checkout page','pisol-dtt'), 'type'=>'text', 'default'=>'Pickup'),
                array('field'=>'pi_delivery_label', 'label'=>__('Delivery label','pisol-dtt'), 'desc'=>__('The text that will be shown on the delivery button on the checkout page','pisol-dtt'), 'type'=>'text', 'default'=>'Delivery'), 
                array('field'=>'pi_button_bg_color', 'default'=>'#cccccc', 'type'=>'color', 'label'=>__('Pickup / Delivery button background color','pisol-dtt'),'desc'=>__('Background color when the delivery type is not selected','pisol-dtt')),
                array('field'=>'pi_active_button_bg_color', 'default'=>'#000000', 'type'=>'color', 'label'=>__('Pickup / Delivery active button background color','pisol-dtt'),'desc'=>__('Background color when the particular delivery type is selected','pisol-dtt')),
                
                array('field'=>'pi_button_text_color', 'default'=>'#000000', 'type'=>'color', 'label'=>__('Pickup / Delivery button text color','pisol-dtt'),'desc'=>__('Text color when the delivery type is not selected','pisol-dtt')),
                array('field'=>'pi_active_button_text_color', 'default'=>'#ffffff', 'type'=>'color', 'label'=>__('Pickup / Delivery active button text color','pisol-dtt'),'desc'=>__('Text color when the particular delivery type is selected','pisol-dtt')),


            );
        

        if($this->this_tab == $this->active_tab){
            add_action('pisol_dtt_tab_content', array($this,'tab_content'));
        }

        add_action('pisol_dtt_tab', array($this,'tab'),3);
        
        $this->register_settings();

    }

    function calendarStyles(){
        $result = array();
        foreach($this->calendar_themes as $theme){
            $result[$theme] = ucfirst($theme);
        }
        return $result;
    }

    function register_settings(){   

        foreach($this->settings as $setting){
                register_setting( $this->setting_key, $setting['field']);
        }
    
    }

    function delete_settings(){
        foreach($this->settings as $setting){
            delete_option( $setting['field'] );
        }
    }

    function tab(){
        ?>
        <a class=" pi-side-menu  <?php echo ($this->active_tab == $this->this_tab ? 'bg-primary' : 'bg-secondary'); ?>" href="<?php echo admin_url( 'admin.php?page='.esc_attr($_GET['page']).'&tab='.esc_attr($this->this_tab) ); ?>">
        <span class="dashicons dashicons-tag"></span> <?php _e( $this->tab_name, 'pisol-dtt' ); ?> 
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
        <input type="submit" name="submit" id="submit" class="btn btn-primary btn-md my-3" value="<?php echo __('Save Changes','pisol-dtt'); ?>">
        </form>
       <?php
    }

    
}

new pisol_dtt_option_label();

