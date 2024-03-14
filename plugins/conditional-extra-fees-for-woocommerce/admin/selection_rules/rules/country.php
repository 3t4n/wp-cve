<?php

class Pi_cefw_selection_rule_country{
    function __construct($slug){
        $this->slug = $slug;
        $this->condition = 'country';
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

    function addRule($rules){
        $rules[$this->condition] = array(
            'name'=>__('Country/Continent','conditional-extra-fees-woocommerce'),
            'group'=>'location_related',
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
        $count = filter_input(INPUT_POST,'count',FILTER_VALIDATE_INT);
        echo Pi_cefw_selection_rule_main::createSelect($this->allCountries(), $count, $this->condition,  "multiple",null,'static');
        die;
    }

    function savedDropdown($html, $values, $count){
        $html = Pi_cefw_selection_rule_main::createSelect($this->allCountries(), $count, $this->condition,  "multiple", $values,'static');
        return $html;
    }

    function allCountries(){
        $countries_obj = new WC_Countries();
       $countries =  $countries_obj->get_countries();
       $continents = $this->all_continents($countries_obj);
       $countries = array_merge($countries, $continents);
       return $countries;
    }

    function all_continents($countries_obj){
        $continents_array = array();

        if(!method_exists($countries_obj, 'get_continents')) return [];

        $continents = $countries_obj->get_continents();
       
        foreach ($continents as $key => $value) {
            $name = 'continent:'.$key;
            $val = $value['name'];
            $continents_array[$name] = $val;
        }
        return $continents_array;
    }

    function get_country_continent($country_code){
        $countries_obj = new WC_Countries();

        if(!method_exists($countries_obj, 'get_continent_code_for_country')) return '';
        
        return $countries_obj->get_continent_code_for_country($country_code);
    }

    function conditionCheck($result, $package, $logic, $values){
                    
                    $or_result = false;
                    $user_country = WC()->customer->get_shipping_country();
                    $user_continent = $this->get_country_continent($user_country);
                    $user_continent = 'continent:'.$user_continent;
                    $rule_countries = $values;
                    if($logic == 'equal_to'){
                        if(in_array($user_country, $rule_countries)){
                            $or_result = true;
                        }else{
                            $or_result = false;
                        }

                        if(in_array($user_continent, $rule_countries)){
                            $or_result = true;
                        }

                    }else{
                        if(in_array($user_country, $rule_countries)){
                            $or_result = false;
                        }else{
                            $or_result = true;
                        }

                        if(in_array($user_continent, $rule_countries)){
                            $or_result = false;
                        }
                    }
               
        return  $or_result;
    }
}

new Pi_cefw_selection_rule_country(PI_CEFW_SELECTION_RULE_SLUG);