<?php

class Wsl_SmartForms_Api extends Lg_Crm{
        
    public $cf7;
    public $submit_result;
    public $submission;
    
    public $form_fields;
    public $form_values;
    public $additional_data;
    public $meta = array();
    
    public $mapped_data;
    
    public function __construct($form_fields) {
        $f_fields = $this->get_field_data($form_fields);
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
        
        $strip = stripslashes($submission["formString"]);
        $decode_obj = json_decode($strip, true);
        $fields_for_sub = array();
        $form_settings = array();

        global $wpdb;
        $result = $wpdb->get_results($wpdb->prepare("select form_id,element_options,form_options,client_form_options from " . SMART_FORMS_TABLE_NAME . " where form_id=%d", $submission['form_id']));
        foreach(json_decode($result[0]->element_options,true) as $k=>$v){
            if($v["ClassName"] != "rednaosubmissionbutton"){
                if($v["ClassName"] == "rednaoname"){
                    if(!empty($v["FirstNamePlaceholder"]) && !empty($v["LastNamePlaceholder"])){
                        $name = $decode_obj[$v["Id"]]["firstName"].' '.$decode_obj[$v["Id"]]["lastName"];
                        $fields_for_sub[] = array(
                            "name"      => strtolower($v["Label"]),
                            "value"     => $name,
                            "id"        => $v["_id"],
                            "type"      => strtolower($v["Label"]),
                            "label"     => strtolower($v["Label"])
                        );
                    }
                }elseif($v["ClassName"] == "rednaoaddress"){
                    $address2 = ' '.$decode_obj[$v["Id"]]["streetAddress2"];
                    $check_add2 = (!empty($decode_obj[$v["Id"]]["streetAddress1"])) ? $address2:"";
                    $city = ' '.$decode_obj[$v["Id"]]["city"];
                    $check_city = (!empty($decode_obj[$v["Id"]]["city"])) ? $city:"";
                    $state = ' '.$decode_obj[$v["Id"]]["state"];
                    $check_state = (!empty($decode_obj[$v["Id"]]["state"])) ? $state:"";
                    $country = ' '.$decode_obj[$v["Id"]]["country"];
                    $check_country = (!empty($decode_obj[$v["Id"]]["country"])) ? $country:"";
                    $zip = ' '.$decode_obj[$v["Id"]]["zip"];
                    $check_zip = (!empty($decode_obj[$v["Id"]]["zip"])) ? $zip:"";
        
                    $form_values = $decode_obj[$v["Id"]]["streetAddress1"].$check_add2.$check_city.$check_state.$check_zip.$check_country;
        
                    $fields_for_sub[] = array(
                        "name"      => strtolower($v["Label"]),
                        "value"     => $form_values,
                        "id"        => $v["_id"],
                        "type"      => strtolower($v["Label"]),
                        "label"     => strtolower($v["Label"])
                    );
                    if(!empty($decode_obj[$v["Id"]]["streetAddress1"])){
                        $fields[] = array(
                            "name"      => "streetAddress1",
                            "value"     => $decode_obj[$v["Id"]]["streetAddress1"],
                            "id"        => $v["_id"],
                            "type"      => "streetAddress1",
                            "label"     => "streetAddress1"
                        );
                    }

                    if(!empty($decode_obj[$v["Id"]]["streetAddress2"])){
                        $fields[] = array(
                            "name"      => "streetAddress2",
                            "value"     => $decode_obj[$v["Id"]]["streetAddress2"],
                            "id"        => $v["_id"],
                            "type"      => "streetAddress2",
                            "label"     => "streetAddress2"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["city"])){
                        $fields[] = array(
                            "name"      => "city",
                            "value"     => $decode_obj[$v["Id"]]["city"],
                            "id"        => $v["_id"],
                            "type"      => "city",
                            "label"     => "city"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["state"])){
                        $fields[] = array(
                            "name"      => "state",
                            "value"     => $decode_obj[$v["Id"]]["state"],
                            "id"        => $v["_id"],
                            "type"      => "state",
                            "label"     => "state"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["zip"])){
                        $fields[] = array(
                            "name"      => "zip",
                            "value"     => $decode_obj[$v["Id"]]["zip"],
                            "id"        => $v["_id"],
                            "type"      => "zip",
                            "label"     => "zip"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["country"])){
                        $fields[] = array(
                            "name"      => "country",
                            "value"     => $decode_obj[$v["Id"]]["country"],
                            "id"        => $v["_id"],
                            "type"      => "country",
                            "label"     => "country"
                        );
                    }
                }else{
                    $fields_for_sub[] = array(
                        "name"      => strtolower($v["Label"]),
                        "value"     => $decode_obj[$v["Id"]]["value"],
                        "id"        => $v["_id"],
                        "type"      => strtolower($v["Label"]),
                        "label"     => strtolower($v["Label"])
                    );
                }
            }
        }
        // Re-structure Form Array
        $form_settings["id"] = $submission->form_id;
        $form_settings["field_id"] = $submission->form_id;
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
            'plugin_name' => "smartforms",
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

    public function get_field_data($formData){
        global $wpdb;

        $strip = stripslashes($formData['formString']);
        $decode_obj = json_decode($strip, true);
        $fields = array();
        $result = $wpdb->get_results($wpdb->prepare("select form_id,element_options,form_options,client_form_options from " . SMART_FORMS_TABLE_NAME . " where form_id=%d", $formData['form_id']));
        foreach(json_decode($result[0]->element_options,true) as $k=>$v){
            print_r($v);
            if($v["ClassName"] != "rednaosubmissionbutton"){
                if($v["ClassName"] == "rednaoname"){
                    if(!empty($v["FirstNamePlaceholder"]) && !empty($v["LastNamePlaceholder"])){
                        $name = $decode_obj[$v["Id"]]["firstName"].' '.$decode_obj[$v["Id"]]["lastName"];
                        $fields[] = array(
                            "name"      => strtolower($v["Label"]),
                            "value"     => $name,
                            "id"        => $v["_id"],
                            "type"      => strtolower($v["Label"]),
                            "label"     => strtolower($v["Label"])
                        );
                    }
                }elseif($v["ClassName"] == "rednaoaddress"){
                    $address2 = ' '.$decode_obj[$v["Id"]]["streetAddress2"];
                    $check_add2 = (!empty($decode_obj[$v["Id"]]["streetAddress1"])) ? $address2:"";
                    $city = ' '.$decode_obj[$v["Id"]]["city"];
                    $check_city = (!empty($decode_obj[$v["Id"]]["city"])) ? $city:"";
                    $state = ' '.$decode_obj[$v["Id"]]["state"];
                    $check_state = (!empty($decode_obj[$v["Id"]]["state"])) ? $state:"";
                    $country = ' '.$decode_obj[$v["Id"]]["country"];
                    $check_country = (!empty($decode_obj[$v["Id"]]["country"])) ? $country:"";
                    $zip = ' '.$decode_obj[$v["Id"]]["zip"];
                    $check_zip = (!empty($decode_obj[$v["Id"]]["zip"])) ? $zip:"";
        
                    $form_values = $decode_obj[$v["Id"]]["streetAddress1"].$check_add2.$check_city.$check_state.$check_zip.$check_country;
        
                    $fields[] = array(
                        "name"      => strtolower($v["Label"]),
                        "value"     => $form_values,
                        "id"        => $v["_id"],
                        "type"      => strtolower($v["Label"]),
                        "label"     => strtolower($v["Label"])
                    );

                    if(!empty($decode_obj[$v["Id"]]["streetAddress1"])){
                        $fields[] = array(
                            "name"      => "streetAddress1",
                            "value"     => $decode_obj[$v["Id"]]["streetAddress1"],
                            "id"        => $v["_id"],
                            "type"      => "streetAddress1",
                            "label"     => "streetAddress1"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["streetAddress2"])){
                        $fields[] = array(
                            "name"      => "streetAddress2",
                            "value"     => $decode_obj[$v["Id"]]["streetAddress2"],
                            "id"        => $v["_id"],
                            "type"      => "streetAddress2",
                            "label"     => "streetAddress2"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["city"])){
                        $fields[] = array(
                            "name"      => "city",
                            "value"     => $decode_obj[$v["Id"]]["city"],
                            "id"        => $v["_id"],
                            "type"      => "city",
                            "label"     => "city"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["state"])){
                        $fields[] = array(
                            "name"      => "state",
                            "value"     => $decode_obj[$v["Id"]]["state"],
                            "id"        => $v["_id"],
                            "type"      => "state",
                            "label"     => "state"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["zip"])){
                        $fields[] = array(
                            "name"      => "zip",
                            "value"     => $decode_obj[$v["Id"]]["zip"],
                            "id"        => $v["_id"],
                            "type"      => "zip",
                            "label"     => "zip"
                        );
                    }
                    if(!empty($decode_obj[$v["Id"]]["country"])){
                        $fields[] = array(
                            "name"      => "country",
                            "value"     => $decode_obj[$v["Id"]]["country"],
                            "id"        => $v["_id"],
                            "type"      => "country",
                            "label"     => "country"
                        );
                    }
                }else{
                    $fields[] = array(
                        "name"      => strtolower($v["Label"]),
                        "value"     => $decode_obj[$v["Id"]]["value"],
                        "id"        => $v["_id"],
                        "type"      => strtolower($v["Label"]),
                        "label"     => strtolower($v["Label"])
                    );
                }
            }
        }
        return $fields;
    }
}