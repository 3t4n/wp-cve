<?php

class Wsl_Cf7_Api extends Lg_Crm{
    
    
    public $cf7;
    public $submit_result;
    public $submission;
    
    public $form_fields;
    public $form_values;
    public $additional_data;
    public $meta = array();
    
    public $mapped_data;
    
    public function __construct($cf7_instance,$result) {
        $this->cf7 = $cf7_instance;
        $this->submit_result = $result;
    }
    
    public function call(){
        $this->set_data();
        $this->set_fields();
        $this->set_raw_additional_data();
        $this->set_meta();
        
        $this->map_fields_and_data();
        // echo '<pre>';
        // print_r($this->mapped_data);
        // echo '</pre>';die();
        $is_called = $this->is_already_called();
        if($is_called){
            return false;
        }
        $result = $this->send_call();
        $this->process_result($result);
    }
    
    public function is_already_called(){
        $is_called = get_transient( 'wsl_is_already_called' );
        if($is_called){
            // If email and ip matches with previous call then Abort
            if($is_called['ip'] == $_SERVER['REMOTE_ADDR']
                    && $is_called['email'] != ''
                    && $is_called['email'] == $this->mapped_data['email']
                    ){
                return true;
            }
            
            // If phone and ip matches with previous call then Abort
            if($is_called['ip'] == $_SERVER['REMOTE_ADDR']
                    && $is_called['phone'] != ''
                    && $is_called['phone'] == $this->mapped_data['phone']
                    ){
                return true;
            }
            
        }else{
            $data = array(
                'email' => $this->mapped_data['email'],
                'phone' => $this->mapped_data['phone'],
                'ip' => $_SERVER['REMOTE_ADDR']
            );
            set_transient( 'wsl_is_already_called', $data , 10 );
            return false;
        }
    }
    
    /**
     * Sets the whole form data as additional data.
     * It is then processed afterwards and unnecessary data is processed out. 
     */
    public function set_raw_additional_data(){
        $this->additional_data = $this->form_values;
        return $this;
    }
    
    public function set_fields(){
        $this->form_fields = $this->cf7->scan_form_tags();
        return $this;
    }
    
    public function map_fields_and_data(){
        $data = array();
        $data['email'] = $this->find_field('email',array('email','mail','your-email'));
        $data['phone'] = $this->find_field('tel',array('phone','your-phone'));
        $data['name'] = $this->map_name();
        $data['address'] = $this->map_address();
        $data['source'] = $this->get_source_data();
        $data['additional_data'] = $this->process_additional_data();
        $data['meta'] = $this->meta;
        
        $this->mapped_data = $data;
    }
    
    public function get_source_data(){
        
        $company_id = lgcrm_get_cf7_post_setting('send_to_company', $this->cf7->id());
        if(!$company_id){
            $company_id = lgcrm_get_setting('send_to_company');
        }
        return array(
                   'id' => $company_id,
                   'website' =>  site_url(),
                   'name' => get_bloginfo('name')
                );
    }
    
    public function set_data(){
        $this->form_values = $this->submission->get_posted_data();
        return $this;
    }
    
    public function set_submission_instance($submission){
        $this->submission = $submission;
        return $this;
    }
    
    public function get_form_data(){
        $data = $this->map_fields();
        return $data;
    }
    
    public function set_meta(){
        $contact_form = $this->cf7;
        $this->meta = array(
            'post_id' => $this->submission->get_meta('container_post_id'),
            'post_url' => $this->submission->get_meta('url'),
            'contact_form_id' => is_object($contact_form)?$contact_form->id():'',
            'unit_tag' => $this->submission->get_meta( 'unit_tag' ),
            'remote_ip' => $this->submission->get_meta( 'remote_ip' ),
            'wp_user_id' => $this->submission->get_meta( 'current_user_id' ),
            'user_agent' => $this->submission->get_meta('user_agent'),
            'plugin_name' => "contact_f7",
        );
    }
    
    public function send_call(){

        $url = $this->api_url;
        $wsl_api = new Wsl_Api();
        // echo '<pre>';
        // print_r($this->mapped_data);
        // echo '</pre>';
        // return false;
        $response = $wsl_api->call($url,$this->mapped_data);
        return $response;
    }
    
    public function find_field($type,$names = array(),$unset_additional = true){
        $value = '';
        foreach($this->form_fields as $field){
            if($field->basetype == $type){
                $value = $this->form_values[$field->name];
                if($unset_additional){
                    $this->unset_additional_data($field->name);
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
            if(in_array(strtolower($field->name),$names)){
                $value = $this->form_values[$field->name];
                if($unset_additional){
                    $this->unset_additional_data($field->name);
                }
                break;
            }
        }
        return $value;
    }
    
    public function map_name(){
        $value = '';
        foreach($this->form_fields as $field){
            if($field->name == 'your-name'){
                $value = $this->form_values[$field->name];
                $this->unset_additional_data($field->name);
                break;
            }
            if($field->basetype == 'text' && strpos(strtolower($field->name), 'name') !== false){
                $value = $this->form_values[$field->name];
                $this->unset_additional_data($field->name);
                break;
            }
        }
        return $value;
    }
    
    public function map_address(){
        $value = array();
        foreach($this->form_fields as $key => $field){
            if($field->name == 'your-address'){
                $value['address'] = $this->form_values[$field->name];
                $this->unset_additional_data($field->name);
                break;
            }
            if($field->basetype == 'text' && strpos(strtolower($field->name), 'address') !== false){
                $value['address'] = $this->form_values[$field->name];
                $this->unset_additional_data($field->name);
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
        unset($this->additional_data[$name]);
        return $this;
    }
    
    public function process_additional_data(){
        $data = array();
        foreach($this->form_fields as $field){
            if(isset($this->additional_data[$field->name])){
                $data[$field->name] = array(
                    'value' => $this->additional_data[$field->name],
                    'name' => $field->name,
                    'basetype' => $field->basetype,
                    'raw_values' => $field->values,
                    'label' => $this->generate_label($field),
                    'show' => $this->get_field_show_option($field)
                );
            }
        }
        return $data;
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
    
    public function get_field_show_option($field){
        $visibility = 'show';
        if(count($field->options) < 1){
            return $visibility;
        }
        
        foreach($field->options as $option){
            $break_option = explode(':', $option);
            if(count($break_option) == 2 && $break_option[0] == 'lead-show'){
                $visibility = str_replace('_', ' ', $break_option[1]);
                break;
            }
        }
        
        return $visibility;
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
    
}