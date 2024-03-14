<?php

class Wsl_Divi_Api extends Lg_Crm{

    public $cf7;
    public $submit_result;
    public $submission;
    
    public $form_fields;
    public $form_values;
    public $additional_data;
    public $meta = array();
    
    public $mapped_data;
    
    public function __construct($fields) {
        $f_fields = $this->get_field_data($fields);
        $data =  array();
        foreach($f_fields as $field){
            
            $data[] = array(
                    "type" => $field['type'],
                    "label" => $field['title'],
                    "value" => $field['value'],
                    "id" => $field['id'],
                    "key" => strtolower($field['title'])
                    //"record" => $field
                );
        }
        $this->form_fields = $data;
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
            $data[$field['key']] = $field;
        }
        $this->additional_data = $data;
    }
    
    public function map_fields_and_data(){
        $data = array();
        $data['email'] = $this->find_field('email',array('email','mail'));
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
            $field['label'] = strtolower($field['label']);
            if($field['type'] != 'hidden' && $field['type'] != 'submit'){
                if(isset($this->additional_data[$field['key']])){
                    $data[$field['label']] = array(
                        'value' => $field['value'],
                        'name' => $field['label'],
                        'basetype' => $field['type'],
                        'raw_values' => $field['value'],
                        'label' => $this->generate_label_by_name($field['label']),
                        'show' => $field['type'] == 'hidden' ? 'hide' : 'show'
                    );
                }
            }
        }
        return $data;
    }

    public function find_field($type,$names = array(),$unset_additional = true){
        $value = '';
        foreach($this->form_fields as $field){
            if($field['type'] == $type){
                $value = $field['value'];
                if($unset_additional){
                    $this->unset_additional_data($field['key']);
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
            if(in_array(strtolower($field['label']),$names)){
                $value = $field['value'];
                if($unset_additional){
                    $this->unset_additional_data($field['key']);
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
        $form_settings = array();
        $set_form = array();
        $strip = stripslashes($submission);
        $decode_obj = json_decode($strip, true);

        // Re-structure Form Settings Array
        $form_settings["form_name"] = "Divi Form";
        $form_settings["form_fields"] = array();
        // Decode Data Object to Follow format Array structure
        foreach($decode_obj as $k=>$v){
            if($v["field_type"] == 'input'){
                $type = "text";
            }
            if($v["field_type"] == 'text'){
                $type = "textarea";
            }
            // Re-structure Form Settings Array
            $set_form[] = array(
                "custom_id"     =>$v["original_id"],
                "field_label"   =>$v["field_label"],
                "placeholder"   =>$v["field_label"],
                "field_type"    =>$type,
                "required"      =>($v["required_mark"] == "required") ? "true":""
            );

            $form_settings["form_fields"] = $set_form;
        }

        $this->submission = $form_settings;
        return $this;
    }
    
    public function set_meta(){
        //$contact_form = $this->cf7;
        global $wp;
        $this->meta = array(
            'form_title' => $this->submission['form_name'],
            'post_url' => home_url( $wp->request ),
            'remote_ip' => $_SERVER['REMOTE_ADDR'],
            'plugin_name' => "divi",
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
            if($field['key'] == 'name'){
                $value = $field['value'];
                $this->unset_additional_data($field['key']);
                break;
            }
            if(strpos(strtolower($field['label']), 'name') !== false){
                $value = $field['value'];
                $this->unset_additional_data($field['key']);
                break;
            }
        }
        return $value;
    }
    
    public function map_address(){
        $value = array();
        foreach($this->form_fields as $field){
            if($field['key'] == 'address'){
                $value['address'] = $field['value'];
                $this->unset_additional_data($field['key']);
                break;
            }
            if(strtolower($field['label']) == 'address'){
                $value['address'] = $field['value'];
                $this->unset_additional_data($field['key']);
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

    public function get_field_data($data_fields){
        $fields_form = array();
        $strip = stripslashes($data_fields);
        $decode_obj = json_decode($strip, true);

        // Decode Data Object to Follow format Array structure
        foreach($decode_obj as $k=>$v){
            if($v["field_type"] == 'input'){
                $type = "text";
            }
            if($v["field_type"] == 'text'){
                $type = "textarea";
            }

            // Re-structure Array Fields
            $fields_form[$v["original_id"]] = array(
                "id"        =>$v["original_id"],
                "type"      =>$type,
                "title"     =>$v["field_label"],
                "value"     =>$_POST[$v["field_id"]],
                "raw_value" =>$_POST[$v["field_id"]],
                "required"  =>($v["required_mark"] == "required") ? "1":""
            ); 
        }

        return $fields_form;
    }
    
}