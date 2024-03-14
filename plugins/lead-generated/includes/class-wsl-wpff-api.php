<?php

class Wsl_Wpff_Api extends Lg_Crm{
        
    public $cf7;
    public $submit_result;
    public $submission;
    
    public $form_fields;
    public $form_values;
    public $additional_data;
    public $meta = array();
    
    public $mapped_data;
    
    public function __construct($form_fields, $formData) {
        $f_fields = $this->get_field_data($form_fields, $formData);
        $this->form_fields = $f_fields;
    }
    public function call(){      
        $this->set_raw_additional_data();  
        $this->set_meta();
        $this->map_fields_and_data();
        $result = $this->send_call();
        $this->process_result($result);
    }
    
    public function set_raw_additional_data(){
        $data = array();
        foreach($this->form_fields as $field){
            $field['name'] = strtolower($field['name']);
            $data[$field['name']] = $field;
        }
        $this->additional_data = $data;
    }

    public function map_fields_and_data(){
        $data = array();
        $data['email'] = $this->find_field('email',array('email','mail','your-email'));
        $data['phone'] = $this->find_field('phone',array('phone'));
        $data['name'] = $this->map_name();
        $data['address'] = $this->map_address();
        $data['source'] = $this->get_source_data();
        $data['additional_data'] = $this->process_additional_data();
        $data['meta'] = $this->meta;
        
        $this->mapped_data = $data;
    }

    public function process_additional_data(){
        $data = array();
        foreach($this->form_fields as $field){
            $field['name'] = strtolower($field['name']);
            if($field['type'] != 'hidden'){
                if(isset($this->additional_data[$field['name']])){
                    $data[$field['name']] = array(
                        'value' => $field['value'],
                        'name' => $field['name'],
                        'basetype' => $field['type'],
                        'raw_values' => $field['value'],
                        'label' => $this->generate_label_by_name($field['name']),
                        'show' => $field['type'] == 'hidden' ? 'hide' : 'show'
                    );
                }
            }
        }
        return $data;
    }

    public function find_field($type,$names = array(),$unset_additional = true){
        $value = '';
        foreach($this->form_fields as $key=>$field){
            if($field['type'] == $type){
                $value = $field['value'];
                if($unset_additional){
                    $this->unset_additional_data($field['name']);
                }
                break;
            }
        }
        if($value == '' && is_array($names) && count($names) > 0){
            $value = $this->find_field_by_name($names,$unset_additional);
        }
        return $value;
    }

    public function find_field_by_name($names,$unset_additional = true){
        $value = '';
        foreach($this->form_fields as $field){
            if(in_array(strtolower($field['name']),$names)){
                $value = $field['value'];
                if($unset_additional){
                    $this->unset_additional_data($field['name']);
                }
                break;
            }
        }
        return $value;
    }
    
    public function get_source_data(){
        $company_id = lgcrm_get_setting('send_to_company');
        return array(
                   'id' => $company_id,
                   'website' =>  site_url(),
                   'name' => get_bloginfo('name')
                );
    }
    
    public function set_submission_instance($submission){
        $strip = stripslashes($form->form_fields);
        $decode_obj = json_decode($strip, true);
        $fields_for_sub = array();
        $form_settings = array();

        // Decode Data Object to Follow format Array structure
        foreach($decode_obj['fields'] as $k=>$v){
            if(!empty($v['fields'])){
                if($v['element'] == 'address'){
                    $field_type = $v['element'];
                }else{
                    $field_type = 'name';
                }
            }else{
                $field_type = ($v['element'] != "textarea" ) ? $v['attributes']['type']:"textarea";
            }
            // Re-structure Form Settings Array
            $fields_for_sub[] = array(
                "id"    => $k,
                "type"  => $field_type,
                "label" => $v['settings']['label']
            );
            
        }
        // Re-structure Form Array
        $form_settings["id"] = $submission->id;
        $form_settings["field_id"] = $submission->settings['id'];
        $form_settings["fields"] = $fields_for_sub;

        $this->submission = $form_settings;
        return $this;
    }
    
    public function set_meta(){
        global $wp;
        $this->meta = array(
            'field_id' => $this->submission['field_id'],
            'post_url' => home_url( $wp->request ),
            'contact_form_id' => isset($this->submission['form_id']) ? $this->submission['form_id'] : "",
            'remote_ip' => $_SERVER['REMOTE_ADDR'],
            'wp_user_id' => $this->submission['settings']['notifications'][1]['sender_address'],
            'plugin_name' => "fluent_forms",
        );
    }
    
    public function send_call(){
        $url = $this->api_url;
        
        $wsl_api = new Wsl_Api();
        $response = $wsl_api->call($url,$this->mapped_data);
        return $response;
    }
    
    public function map_name(){
        $value = '';
        foreach($this->form_fields as $field){
            if($field['type'] == 'name'){
                $value = $field['value'];
                $this->unset_additional_data($field['name']);
                break;
            }
            if(strpos(strtolower($field['name']), 'name') !== false){
                $value = $field['value'];
                $this->unset_additional_data($field['name']);
                break;
            }
        }
        return $value;
    }
    
    public function map_address(){
        $value = array();
        foreach($this->form_fields as $key => $field){
            if($field['type'] == 'address'){
                $value['address'] = $field['value'];
                $this->unset_additional_data($field['name']);
                break;
            }
            if(strpos(strtolower($field['name']), 'address') !== false){
                $value['address'] = $field['value'];
                $this->unset_additional_data($field['name']);
                break;
            }
        }

        $city = $this->find_field('city',array('city'));
        if($city){
            $value['city'] = $city;
            $this->unset_additional_data($key);
        }
        $state = $this->find_field('state',array('state'));
        if($state){
            $value['state'] = $state;
            $this->unset_additional_data($key);
        }
        $zip = $this->find_field('zipcode',array('zip','zipcode'));
        if($zip){
            $value['zipcode'] = $zip;
            $this->unset_additional_data($key); 
        }
        return $value;
    }
    
    public function unset_additional_data($name){
        $name = strtolower($name);
        unset($this->additional_data[$name]);
        return $this;
    }
    
    public function generate_label($field){
        $label = '';
        if(count($field->options) < 1){
            return $this->generate_label_by_name($field->name);
        }
        
        foreach($field->options as $option){
            $break_option = explode(':', $option);
            if(count($break_option) == 2 && $break_option[0] == 'lead-label'){
                $label = str_replace('_', ' ', $break_option[1]);
                break;
            }
        }
        
        if($label == ''){
            $label = $this->generate_label_by_name($field->name);
        }
        
        return $label;
    }
    
    public function generate_label_by_name($name){
        return str_replace(array('_','-'), ' ', $name);
    }
    
    public function process_result($result){
        $data = $result;
        $send_to_company = lgcrm_get_setting('send_to_company');
        if($send_to_company && $send_to_company != 0){
            //All good
        }else{
            if(isset($data->source_id)){
                lgcrm_update_setting('send_to_company',$data->source_id);
            }
        }
        if(isset($data->source_id)){
            update_option('wsl_source_id', $data->source_id, false);
        }
    }

    public function get_field_data($form, $formData){

        $strip = stripslashes($form->form_fields);
        $decode_obj = json_decode($strip, true);
        $fields = array();
        $fields_for_sub = array();

        // Decode Data Object to Follow format Array structure
        foreach($decode_obj['fields'] as $k=>$v){
            if(isset($v['element']) && $v['element'] == 'container'){
                if(isset($v['columns']) && !empty($v['columns'])){
                    foreach($v['columns'] as $col){
                        if(isset($col['fields']) && !empty($col['fields'])){
                            foreach($col['fields'] as $c_k=>$c_v){
                                $fields[] = $this->get_field_rec($c_k,$c_v,$formData);
                            }
                        }
                    }
                }
            }else{
                $fields[] = $this->get_field_rec($k,$v,$formData);
            }
            
        }
            $form_settings = array();
            // Re-structure Form Array
            $form_settings["id"] = $settings->id;
            $form_settings["field_id"] = $settings->settings['id'];
            $form_settings["fields"] = $form_settings;

        return $fields;
    }

    public function get_field_rec($k,$v,$formData){
        if(!empty($v['fields'])){
            if($v['element'] == 'address'){
                $field_names = $v['element'];
                $field_type = $v['element'];
                $add_2 = ' '.$formData[$v['attributes']['name']]['address_line_2'];
                $check_add2 = (!empty($formData[$v['attributes']['name']]['address_line_1'])) ? $add_2:"";
                
                $city = ' '.$formData[$v['attributes']['name']]['city'];
                $check_city = (!empty($formData[$v['attributes']['name']]['city'])) ? $city:"";
                
                $state = ', '.$formData[$v['attributes']['name']]['state'];
                $check_state = (!empty($formData[$v['attributes']['name']]['state'])) ? $state:"";
                
                $zip = ' '.$formData[$v['attributes']['name']]['zip'];
                $check_zip = (!empty($formData[$v['attributes']['name']]['zip'])) ? $zip:"";

                $country = ' '.$formData[$v['attributes']['name']]['country'];
                $check_country = (!empty($formData[$v['attributes']['name']]['country'])) ? $country:"";
                $form_values = $formData[$v['attributes']['name']]['address_line_1'].$check_add2.$check_city.$check_state.$check_zip.$check_country;

                // Re-structure Array Fields
                $field = array(
                    "name"      => $field_names,
                    "value"     => $form_values,
                    "id"        => $k,
                    "type"      => $field_type,
                    "address1"  => $formData[$v['attributes']['name']]['address_line_1'],
                    "address2"  => $formData[$v['attributes']['name']]['address_line_2'],
                    "city"      => $formData[$v['attributes']['name']]['city'],
                    "state"     => $formData[$v['attributes']['name']]['state'],
                    "postal"    => $formData[$v['attributes']['name']]['zip'],
                    "country"   => $formData[$v['attributes']['name']]['country']
                );

                foreach($v['fields'] as $f_key=>$f_val){
                    $field = array(
                        "name"      => $f_key,
                        "value"     => $formData[$field_names][$f_key],
                        "id"        => $k,
                        "type"      => $f_key,
                    );
                }

            }else{
                $field_names = "Name";
                $field_type = 'name';
                $mdname = ' '.$formData[$v['attributes']['name']]['middle_name'];
                $checkmidname = (!empty($formData[$v['attributes']['name']]['middle_name'])) ? $mdname:"";
                $form_values = $formData[$v['attributes']['name']]['first_name'].$checkmidname.' '.$formData[$v['attributes']['name']]['last_name'];
            
                // Re-structure Array Fields
                $field = array(
                    "name"      => $field_names,
                    "value"     => $form_values,
                    "id"        => $k,
                    "type"      => $field_type,
                    "first"     => $formData[$v['attributes']['name']]['first_name'],
                    "middle"    => $formData[$v['attributes']['name']]['middle_name'],
                    "last"      => $formData[$v['attributes']['name']]['last_name']
                );
            }
            
        }else{
            $field_names = $v['settings']['label'];
            $form_values = $formData[$v['attributes']['name']];
            $field_type = ($v['element'] != "textarea" ) ? $v['attributes']['type']:"textarea";
            $names_values = "";

            // Re-structure Array Fields
            $field = array(
                "name"  => $field_names,
                "value" => $form_values,
                "id"    => $k,
                "type"  => $field_type
            );
        }

        return $field;

        // Re-structure Form Settings Array
        $fields_for_sub[] = array(
            "id"    => $k,
            "type"  => $field_type,
            "label" => $v['settings']['label']
        );
    }
}