<?php

class Pi_cefw_selection_rule_pickup_location{
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'pickup_location';
        /* this adds the condition in set of rules dropdown */
        add_filter("pi_".$this->slug."_condition", array($this, 'addRule'));
        
        /* this gives value field to store condition value either select or text box */
        add_action( 'wp_ajax_pi_'.$this->slug.'_value_field_'.$this->condition, array( $this, 'ajaxCall' ) );

        /* This gives our field with saved value */
        add_filter('pi_'.$this->slug.'_saved_values_'.$this->condition, array($this, 'savedDropdown'), 10, 3);

        /* This perform condition check */
        add_filter('pi_'.$this->slug.'_condition_check_'.$this->condition,array($this,'conditionCheck'),10,4);

        /* This gives out logic dropdown */
        add_action('pi_'.$this->slug.'_logic_'.$this->condition, array($this, 'logicDropdown'));

        /* This give saved logic dropdown */
        add_filter('pi_'.$this->slug.'_saved_logic_'.$this->condition, array($this, 'savedLogic'),10,3);
    }

    static function datePluginInstalled(){
        if(is_plugin_active( 'pi-woocommerce-order-date-time-and-type-pro/pi-woocommerce-order-date-time-and-type-pro.php') ) return true;

        return false;
    }

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Pickup location', 'conditional-fees-rule-woocommerce'),
            'group'=>'order_date_time_plugin',
            'condition'=>$this->condition
        );
        return $rules;
    }

    function logicDropdown(){
        $html = "";
        $html .= 'var pi_logic_'.$this->condition.'= "<select class=\'form-control\' name=\'pi_selection[{count}][pi_'.$this->slug.'_logic]\'>';
        
            $html .= '<option value=\'equal_to\'>Equal to (=)</option>';
            $html .= '<option value=\'not_equal_to\'>Not Equal to (!=)</option>';
           
        
        $html .= '</select>";';
        echo $html;
    }

    function savedLogic($html_in, $saved_logic, $count){
        $html = "";
        $html .= '<select class="form-control" name="pi_selection['.$count.'][pi_'.$this->slug.'_logic]">';
        
            $html .= '<option value="equal_to" '.selected($saved_logic , "equal_to",false ).'>Equal to (=)</option>';
            $html .= '<option value="not_equal_to" '.selected($saved_logic , "not_equal_to",false ).'>Not Equal to (!=)</option>';
           
        
        $html .= '</select>';
        return $html;
    }

    function ajaxCall(){
        if(!current_user_can( 'manage_options' )) {
            return;
            die;
        }
        if(self::datePluginInstalled()){
            $count = filter_input(INPUT_POST,'count',FILTER_VALIDATE_INT);
            echo Pi_cefw_selection_rule_main::createSelect($this->pickup_locations(), $count, $this->condition,  "multiple",null,'static');
        }else{
            echo self::msgNoDateTimePlugin();
        }
        die;
    }

    function savedDropdown($html, $values, $count){
        if(self::datePluginInstalled()){
            $html = Pi_cefw_selection_rule_main::createSelect($this->pickup_locations(), $count, $this->condition,  "multiple", $values,'static');
            return $html;
        }

        return self::msgNoDateTimePlugin();
    }

    static function msgNoDateTimePlugin(){
        $plugin_page = 'https://www.piwebsolution.com/product/order-delivery-date-time-and-pickup-locations-for-woocommerce/';
        return sprintf('<div class="alert alert-danger">This feature requires <a href="%s" target="_blank">Delivery date and time plugin PRO</a> installed in your website <a href="%s" target="_blank">Click to Buy</a></div>',$plugin_page, $plugin_page);
    }


    function pickup_locations(){
        $locations = $this->get_locations();
        $locations_array = array();
        foreach($locations as $location){
            $locations_array[$location->ID] = $location->post_title;
        }
        return  $locations_array;
    }

    function get_locations(){
            $arg = array(
                'post_type' => 'pisol_location',
                'post_status' => 'publish',
                'posts_per_page' => -1
            );
            $locations = get_posts($arg);
            return $locations;
    }

    function getSelectedPickupLocation(){
        if(!isset($_POST['post_data']) && !isset($_POST['pickup_location'])) return 0;

        if(isset($_POST['pickup_location'])){
            $values['pickup_location'] = $_POST['pickup_location'];
        }else{
            parse_str($_POST['post_data'], $values);
        }

        if(!empty($values['pickup_location'])){
            return $values['pickup_location'];
        }
        
        return 0;
    }

    function conditionCheck($result, $package, $logic, $values){
                    
                    $or_result = false;
                    
                        $selected_pickup_location = $this->getSelectedPickupLocation();
                        
                        $rule_delivery_type = $values;
                        if($logic == 'equal_to'){
                            if(in_array($selected_pickup_location,$rule_delivery_type)){
                                $or_result = true;
                            }else{
                                $or_result = false;
                            }
                        }else{
                            if(in_array($selected_pickup_location,$rule_delivery_type)){
                                $or_result = false;
                            }else{
                                $or_result = true;
                            }
                        }
                    
               
        return  $or_result;
    }
}

new Pi_cefw_selection_rule_pickup_location(PI_CEFW_SELECTION_RULE_SLUG);
