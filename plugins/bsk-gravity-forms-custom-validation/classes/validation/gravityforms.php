<?php

class BSK_GFCV_Validation_GravityForms {

    var $_OBJ_common = false;

	public function __construct( $args ) {

        $this->_OBJ_common = $args['common_class'];
        
        if ( BSK_GFCV_Dashboard_Common::bsk_gfcv_is_form_plugin_supported( 'GF' ) ) {
		    add_filter( 'gform_validation', array($this, 'bsk_gfcv_front_form_validation'), 10, 1 );
        }
	}
	
	function bsk_gfcv_front_form_validation( $validation_result ){

        $form = $validation_result['form'];
        //form settings
        $bsk_gfcv_form_settings = rgar( $form, 'bsk_gfcv_form_settings' );

        $enable = true;
        $action_when_hit = array( 'BLOCK' );
        
        if( $bsk_gfcv_form_settings && is_array( $bsk_gfcv_form_settings ) && count( $bsk_gfcv_form_settings ) > 0 ){
            $enable = $bsk_gfcv_form_settings['enable'];
            $action_when_hit = $bsk_gfcv_form_settings['actions'];
        }
        
        if( !$enable ){
            return $validation_result;
        }
        
        if( !in_array( 'BLOCK', $action_when_hit ) ){
            return $validation_result;
        }

        $return_validation_result = $this->bsk_gfcv_front_form_validation_mapping( $validation_result );
        
		return $return_validation_result;
	}
	
	
	function bsk_gfcv_front_form_validation_mapping( $validation_result ){
		global $wpdb;
		
		$form = $validation_result['form'];

        //validation
        $fields_hit_item_array = array(); //only for blocked
        $form_data_array = array();
		$current_page = rgpost( 'gform_source_page_number_' . $form['id'] ) ? rgpost( 'gform_source_page_number_' . $form['id'] ) : 1;
		foreach( $form['fields'] as $field ){
			if ( $current_page != $field->pageNumber ) {
				continue;
			}
            
            if( $field->is_field_hidden ){
                continue;
            }
			
			$field_obj_array = json_decode( json_encode($field), true );
			if( $field->type == 'name' || 
                $field->type == 'address' || 
                $field->type == 'checkbox' ||
                $field->type == 'time' ){
                //checkbox will come to here
                //let empty value can be checked for supporting checkbox_all rule
				foreach($field['inputs'] as $gravity_form_field_input) {
					if( isset($gravity_form_field_input['isHidden']) && $gravity_form_field_input['isHidden'] ){
						continue;
					}
                    
					$field_id_str = $gravity_form_field_input['id'];
                    
					$field_value = rgpost( 'input_'.str_replace( '.', '_', $field_id_str) );
                    $field_label = $field['label'].'.'.$gravity_form_field_input['label'];
                    
                    $property_appendix = '_'.$field_id_str;
                    
                    $this->bsk_gfcv_check_field_value_againsit_list_item(
                                                                      $property_appendix,
                                                                      $fields_hit_item_array,
                                                                      $field, 
                                                                      $validation_result,
                                                                      $field_id_str, 
                                                                      $field_value, 
                                                                      $field_label, 
                                                                      $field_obj_array
                                                                    );
				}//end of foreach
			}else{
				$field_id_str = $field['id'];
				$field_value = rgpost( 'input_'.$field_id_str );
                $field_label = $field['label'];
                
                $property_appendix = '';

                $this->bsk_gfcv_check_field_value_againsit_list_item(
                                                                      $property_appendix,
                                                                      $fields_hit_item_array,
                                                                      $field,
                                                                      $validation_result,
                                                                      $field_id_str, 
                                                                      $field_value, 
                                                                      $field_label, 
                                                                      $field_obj_array
                                                                    );
                
			}//end of multiple inputs filed or single field
		}

        $validation_result['form'] = $form;
		return $validation_result;
	}
    
    function bsk_gfcv_check_field_value_againsit_list_item(  $property_appendix,
                                                              &$fields_hit_item_array,
                                                              &$field,
                                                              &$validation_result,
                                                              $field_id_str, 
                                                              $field_value, 
                                                              $field_label, 
                                                              $field_obj_array ){
        
        global $wpdb;
        
        $list_id_to_check = '';
        $validation_message = '';
        $list_type = '';
        
        if( $field->type == 'checkbox' || $field->type == 'time' ){
            $property_appendix = '';
        }

        if( isset($field_obj_array['bsk_gfcv_listperty'.$property_appendix]) &&
                  $field_obj_array['bsk_gfcv_listperty'.$property_appendix] ){

            $list_id_to_check = $field_obj_array['bsk_gfcv_listperty'.$property_appendix];
            $list_type = 'CV_LIST';
        }else if( isset($field_obj_array['bsk_gfbl_apply_cv_listperty'.$property_appendix]) &&
                  $field_obj_array['bsk_gfbl_apply_cv_listperty'.$property_appendix] ){
            
            //compatible old version

            $list_id_to_check = $field_obj_array['bsk_gfbl_apply_cv_listperty'.$property_appendix];
            $list_type = 'CV_LIST';
        }
        
        
        $invalid_validation = false;
        if( $list_id_to_check == "" || $list_type == '' ){
            return $invalid_validation;
        }
        
        //check if the list still active, as some case the list deleted but it still save in form settings
        if ( ! BSK_GFCV_Validation_Common::bsk_gfcv_front_check_list_status( $list_id_to_check, $list_type ) ) {
            return $invalid_validation;
        }
        

        $checked_return = BSK_GFCV_Validation_Common::bsk_gfcv_front_check_field_value_match_cv_rules( 
                                                                                                        $list_id_to_check, 
                                                                                                        $field, 
                                                                                                        $field_value
                                                                                                    );

        if( $checked_return && is_array($checked_return) &&
            isset($checked_return['result']) && $checked_return['result'] == false ){
            
            $invalid_validation = true;
            
            //render validation message
            $checked_return['message'] = str_replace( '#BSK_CV_FIELD_LABEL#', $field_label, $checked_return['message'] );
            
            $validation_result['is_valid'] = false;
            $field['failed_validation'] = true;
            $field['validation_message'] = $checked_return['message'];

        }
        
        return $invalid_validation;
    }
    
}