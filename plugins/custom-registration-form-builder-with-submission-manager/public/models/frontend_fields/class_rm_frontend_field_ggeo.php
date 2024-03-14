<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class_rm_frontend_field_ggeo
 *
 * @author RegistrationMagic
 */
class RM_Frontend_Field_GGeo extends RM_Frontend_Field_Base
{

    public $api_key;

    public function __construct($id, $type, $field_name, $label, $options, $gmaps_api_key , $page_no, $is_primary = false, $extra_opts = null)
    {
        parent::__construct($id, $type, $field_name, $label, $options, $page_no, $is_primary, $extra_opts);
        $this->api_key = $gmaps_api_key;
    }

    public function get_pfbc_field()
    {  
        if ($this->pfbc_field)
            return $this->pfbc_field;
        else
        {
            $field_options= $this->field_model->field_options;
            $field_type= isset($field_options->field_address_type) ? $field_options->field_address_type : "ga";
            if($field_type=="ga"){
                $this->field_options['address_type']= 'ga';
                $this->field_options['street_label']= RM_UI_Strings::get('LABEL_ST_ADDRESS');
                $this->field_options['street_no_label']= RM_UI_Strings::get('LABEL_ST_NUMBER');
                $this->field_options['city_label']= RM_UI_Strings::get('LABEL_ADDR_CITY');
                $this->field_options['state_label']= RM_UI_Strings::get('LABEL_ADDR_STATE');
                $this->field_options['country_label']= RM_UI_Strings::get('LABEL_ADDR_COUNTRY');
                $this->field_options['zip_label']= RM_UI_Strings::get('LABEL_ADDR_ZIP'); 
  
            }else{
                $this->field_options['address_type']= $field_options->field_address_type;  

                $this->field_options['address1_en']= $field_options->field_ca_address1_en;
                $this->field_options['address1_label']= $field_options->field_ca_address1_label;
                $this->field_options['address1_req']=  $field_options->field_ca_address1_req=="1" ? 'required': '';
                $this->field_options['address2_en']= $field_options->field_ca_address2_en;
                $this->field_options['address2_label']= $field_options->field_ca_address2_label;
                $this->field_options['address2_req']=  $field_options->field_ca_address2_req=="1" ? 'required': '';
                
                $this->field_options['lmark_en']= $field_options->field_ca_lmark_en;
                $this->field_options['lmark_req']= $field_options->field_ca_lmark_req=="1" ? 'required': '';
                $this->field_options['lmark_label']= $field_options->field_ca_lmark_label;
                
                $this->field_options['city_en']= $field_options->field_ca_city_en;
                $this->field_options['city_label']= $field_options->field_ca_city_label;
                $this->field_options['city_req']=  $field_options->field_ca_city_req=="1" ? 'required': '';
                
                $this->field_options['state_en']=  $field_options->field_ca_state_en;
                $this->field_options['state_label']=  $field_options->field_ca_state_label;
                $this->field_options['state_req']=  $field_options->field_ca_state_req=="1" ? 'required': '';
                
                $this->field_options['country_en']= $field_options->field_ca_country_en;
                $this->field_options['country_label']= $field_options->field_ca_country_label;
                $this->field_options['country_req']=  $field_options->field_ca_country_req=="1" ? 'required': '';
                
                $this->field_options['zip_en']= $field_options->field_ca_zip_en;
                $this->field_options['zip_label']= $field_options->field_ca_zip_label;
                $this->field_options['zip_req']=  $field_options->field_ca_zip_req=="1" ? 'required': '';
                
                $this->field_options['state_type']= $field_options->ca_state_type;
                $this->field_options['label_as_placeholder']= $field_options->field_ca_label_as_placeholder;
                $this->field_options['countries']= explode(',',(string)$field_options->field_ca_country_limited);
                $this->field_options['state_as_code']= empty($field_options->field_ca_state_codes) ? 0 : 1;
                $this->field_options['country_search_enabled']= empty($field_options->field_ca_en_country_search) ? 0 : 1; 
            }
                
            if (true || $this->api_key)
            {
                $field_cls = 'Element_' . $this->get_field_type();
                $label = $this->get_formatted_label();
                $this->pfbc_field = new $field_cls($label, $this->field_name, $this->api_key, $this->field_options);
            }else{
                $this->pfbc_field = new Element_HTMLP('<div class="rmrow"><div class="rmfield"><label>'.$this->field_label.'</label></div><div class="rminput"><div class="field_rendor_error">'.RM_UI_Strings::get('MSG_FRONT_NO_GOOGLE_API_KEY').'</div></div></div>');
            }
            $this->set_conditional_properties();
            $this->add_custom_validations();
            return $this->pfbc_field;
        }
    }

    public function set_conditional_properties()
    {
        $cond_option=array();
        $cond_value=array();
        $cond_op= array();
        if(!empty($this->field_model->field_options->conditions['rules']) && is_array($this->field_model->field_options->conditions['rules'])){
            $conditions= $this->field_model->field_options->conditions['rules'];
            $values= array();
            foreach($conditions as $condition)
            {
                $cf_id= $condition['controlling_field'];
                $cf_field= new RM_Fields();
                if($cf_field->load_from_db($cf_id)){
                    $cType= $cf_field->get_field_type();
                    $field_name= $cType.'_'.$cf_id;
                    
                    if(is_array($condition['values'])){
                        $values= implode(',', $condition['values']);
                    } else
                        $values= $condition['values'][0];
                    $cond_option[]= $field_name;
                    $cond_value[]=  empty($values) ? "_" : $values;
                    $cond_op[]= $condition['op'];
                }
                
            }

            if(count($cond_option)>0){
                $this->pfbc_field->conditions['data-cond-option'] = implode('|', $cond_option);
                $this->pfbc_field->conditions['data-cond-value'] = implode('|', $cond_value);
                $this->pfbc_field->conditions['data-cond-operator'] = implode('|', $cond_op);
                $this->pfbc_field->conditions['data-cond-action'] = isset($this->field_model->field_options->conditions['action']) ? $this->field_model->field_options->conditions['action'] : 'show';
                $settings = $this->field_model->field_options->conditions['settings'];
                if(count($cond_option)>1)
                $this->pfbc_field->conditions['data-cond-comb'] = empty($settings['combinator']) ? 'OR': $settings['combinator'];
                $this->pfbc_field->conditions['class'] = !empty($this->field_model->field_options->field_css_class) ? $this->field_model->field_options->field_css_class . " data-conditional" : "data-conditional";
            }
        }
    }
}
