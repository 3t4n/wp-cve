<?php
if ( ! defined( 'WPINC' ) ) {
	die;
}
class pi_dtt_display_fields{

    public $fields_location;
    
    function __construct(){
        $this->fields_location = apply_filters('pisol_dtt_field_locations', pisol_dtt_get_setting('pi_date_time_location', 'woocommerce_checkout_before_customer_details'));

        add_action( $this->fields_location, array($this,'addingFields'));

        add_action('wp_ajax_pi_set_delivery_type',array($this, 'setDeliveryType'));
        add_action('wp_ajax_nopriv_pi_set_delivery_type',array($this, 'setDeliveryType'));
    }

    function addingFields(){

        if(did_action($this->fields_location) > 1 && apply_filters('pisol_dtt_enable_hook_count', true)) return;
        
        
        $pisol_disable_dtt_completely = apply_filters('pisol_disable_dtt_completely',false);
        if($pisol_disable_dtt_completely){
            return ;
        }
        $delivery_type = pi_dtt_delivery_type::getType();
        do_action('pisol_message_before_date_time');
        echo '<div id="pi_checkout_field">';
        $this->deliveryTypeField($delivery_type);
        $this->dateTimeField();
        $this->pickupLocationFields($delivery_type);
        do_action('pisol_dtt_custom_display_field');
        echo '</div>';
        do_action('pisol_message_after_date_time');
    }

    function deliveryTypeField($delivery_type = ""){
            
            if(empty($delivery_type)){
                $delivery_type = pi_dtt_delivery_type::getType();
            }

            $aloud_types = pisol_dtt_get_setting('pi_type', "Both");

            $types = array(
                'delivery' => pisol_dtt_get_setting('pi_delivery_label',__('Delivery','pisol-dtt')), 
                'pickup' => pisol_dtt_get_setting('pi_pickup_label',__('Pickup','pisol-dtt')) 
            );

            if($aloud_types == 'Delivery'){
                unset($types['pickup']);
            }elseif($aloud_types == 'Pickup'){
                unset($types['delivery']);
            }

            $this->deliveryTypeFieldHtml($delivery_type, $types);

    }

    function deliveryTypeFieldHtml($selected_delivery_type, $show_types){
        $single_type_class = count($show_types) < 2 ? "pi-single-type" : '';
        $show_delivery_type_label = pisol_dtt_get_setting('pi_show_delivery_type_label', 1);
        ?>
        <p class="form-row pi_delivery_type validate-required" id="pi_delivery_type_field" data-priority=""><?php if(!empty($show_delivery_type_label)): ?><label for="pi_delivery_type_delivery" class=""><?php echo __('Delivery Type','pisol-dtt'); ?>&nbsp;<abbr class="required" title="<?php  echo esc_attr__( 'required', 'woocommerce' ); ?>">*</abbr></label><?php endif; ?><span class="woocommerce-input-wrapper"><?php if(isset($show_types['delivery'])): ?><input type="radio" class="input-radio " value="delivery" name="pi_delivery_type" id="pi_delivery_type_delivery" <?php  checked($selected_delivery_type, 'delivery' ); ?>><label for="pi_delivery_type_delivery" class="radio <?php echo $single_type_class; ?>"><?php echo esc_html(pisol_dtt_get_setting('pi_delivery_label','Delivery')); ?></label><?php endif; ?><?php if(isset($show_types['pickup'])): ?><input type="radio" class="input-radio " value="pickup" name="pi_delivery_type" id="pi_delivery_type_pickup" <?php  checked($selected_delivery_type, 'pickup' ); ?>><label for="pi_delivery_type_pickup" class="radio <?php echo $single_type_class; ?>"><?php echo esc_html(pisol_dtt_get_setting('pi_pickup_label','Pickup')); ?></label>
        <?php endif; ?><?php do_action('pisol_extra_delivery_type_checkout', $selected_delivery_type); ?></span></p>
        <?php
    }

    
    function dateTimeField(){
        if(self::showDateAndTime()){
            $this->dateField();
            $this->timeField();
        }
    }

    function dateField(){
        woocommerce_form_field( 'pi_delivery_date', array(
            'type'          => 'text',
            'required'		=>	self::isDateRequired(),
            'class'         => array('pi_delivery_date'),
            'label'         => __('Date','pisol-dtt'),
            'placeholder'   => __('Date','pisol-dtt'),
            'custom_attributes' => array('readonly'=>'readonly'),
            ));
        echo '<p style="display:none;"><input type="hidden" name="pi_system_delivery_date" id="pi_system_delivery_date"></p>';
    }
    
    function timeField(){
        if(self::enableTimeField()){
            woocommerce_form_field( 'pi_delivery_time', array(
                'type'          => 'text',
                'required'		=>	self::isTimeRequired(),
                'class'         => array('pi_delivery_time'),
                'label'         => __('Time','pisol-dtt'),
                'placeholder'   => __('Time','pisol-dtt'),
                'custom_attributes' => array('readonly'=>'readonly', 'disabled'=>'disabled'),
                ));
        }
    }


    function pickupLocationFields($delivery_type = ""){
        if(empty($delivery_type)){
            $delivery_type = pi_dtt_delivery_type::getType();
        }
        
        if($delivery_type != 'pickup') return; 

        $address1 = get_option('pi_pickup_address_1', "");
        $address2 = get_option('pi_pickup_address_2', "");

        if(empty($address1) && empty($address2)){
        return false;
        }

        echo '<div id="pisol-pickup-locations" style="grid-column: span 2;">';
        echo '<label>'.__('Select a pickup location','pisol-dtt').'</label>';
        echo '<div style="display:flex; flex-wrap:wrap;">';
        if(!empty($address1)){
            echo '<div class="pisol-pickup-add"><input type="radio" name="pickup_location" class="pisol-location-radio" value="pi_pickup_address_1" id="pi_pickup_address_1" checked="checked"><label class="pisol-location" for="pi_pickup_address_1">'.wp_kses_post($address1).'</label></div>';
        }
        if(!empty($address2)){
            echo '<div class="pisol-pickup-add"><input type="radio" name="pickup_location" class="pisol-location-radio" value="pi_pickup_address_2" id="pi_pickup_address_2"><label  class="pisol-location" for="pi_pickup_address_2">'.wp_kses_post($address2).'</label></div>';
        }
        echo '</div>';
        echo '</div>';
    }

    function setDeliveryType(){
        $type = sanitize_text_field($_GET['type']);
        $obj = new  pi_dtt_delivery_type();
        $set_type = $obj->setDeliveryType($type);
        echo $set_type;
        die;
    }

    static function dateFormat(){
        $format = pisol_dtt_get_setting('date_format','F j, Y');
        return $format;
    }

    static function showDateAndTime($type = ""){
        if(empty($type)){
            $type =  pi_dtt_delivery_type::getType();
        }

        $show_date_time = pisol_dtt_get_setting('pi_datetime', 'enable-both');

        switch($show_date_time){
            case '0':
                return false;
            break;

            case '1':
                return true;
            break;

            case 'disable-both':
                return false;
            break;

            case 'enable-both':
                return true;
            break;

            case 'pickup-only':
                if($type == 'pickup'){
                    return true;
                }else{
                    return false;
                }
            break;

            case 'delivery-only':
                if($type == 'delivery'){
                    return true;
                }else{
                    return false;
                }
            break;

            default:
            return true;
            
        }
    }

    static function enableTimeField($type = ""){
        $saved_option = pisol_dtt_get_setting('pi_enable_delivery_time', 'enable-both');
        $return  = false;
        if(empty($type)){
            $delivery_type = pi_dtt_delivery_type::getType();
        }else{
            $delivery_type = $type;
        }

            if( $saved_option == "enable-both"){
            $return = true;
            }

            if( $saved_option == "disable-both"){
            $return = false;
            }

            if( ($saved_option == 'only-pickup' && $delivery_type == 'pickup')){
            $return = true;
            }

            if( ($saved_option == 'only-delivery' && $delivery_type == 'delivery')){
            $return = true;
            }
        return $return;
    }

    static function isTimeRequired($type = ""){
        return true;
    }

    static function isLocationRequired(){
        $required = pisol_dtt_get_setting('pi_location_required','0');
        $extra_type_support_pickup_location = apply_filters('pisol_dtt_type_supporting_pickup_location','');
        $type =  pi_dtt_delivery_type::getType();
        $obj = new pi_dtt_pickup_location();
        if( ($type == 'pickup' || $type == $extra_type_support_pickup_location) && !empty($required) && $required == '1' && $obj->isLocationPresent()){
            return true;
        }

        return false;
    }

    static function isDateRequired($type = ""){
        
       return true;
    }

    
}