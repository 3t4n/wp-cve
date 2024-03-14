<?php

class BSK_GFCV_Validation_Common {

	public function __construct() {
        
	}
    
    public static function bsk_gfcv_front_check_field_value_match_cv_rules( 
                                                                $list_id_to_check, 
                                                                $field, 
                                                                $field_value
                                                              ) {
        
        global $wpdb;

        $list_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name;
        $items_table = $wpdb->prefix.BSK_GFCV::$_bsk_gfcv_items_tbl_name;
		//get list data
		$list_data_sql = 'SELECT I.`id` AS item_id, L.`list_type`, I.`value` FROM `'.$list_table.'` AS L '.
                         'LEFT JOIN `'.$items_table.'` AS I ON L.`id` = I.`list_id` '.
                         'WHERE I.`list_id` = %d '.
                         'ORDER BY I.`id`';
		$list_data_sql = $wpdb->prepare( $list_data_sql, $list_id_to_check );
		$rules_array = $wpdb->get_results( $list_data_sql );
		if( !$rules_array || !is_array($rules_array) || count($rules_array) < 1 ){
			return array( 'result' => true, 'message' => '' );
		}

        foreach( $rules_array as $rule_saved_settings_obj ){
            if( $rule_saved_settings_obj->value == '' ){
                continue;
            }
            $rule_saved_settings = unserialize( $rule_saved_settings_obj->value );
            $rule_saved_settings['item_id'] = $rule_saved_settings_obj->item_id;
            $rule_slug = $rule_saved_settings['slug'];
            
            $value_to_check = $field_value;
            if( $rule_slug != 'checkbox_all' ){
                if( $value_to_check == '' ){
                    return array( 'result' => true, 'message' => '' );
                }
            }
            
            switch( $rule_slug ){
                
                case 'latitude':
                    $value_to_check = $field_value;
                    $divider = strpos( $value_to_check, ',' );
                    if( $divider !== false ){
                        $value_to_check = substr( $value_to_check, 0, $divider );
                    }
                    $value_to_check = trim( $value_to_check );
                break;
                    
                case 'longitude':
                    $value_to_check = $field_value;
                    $divider = strpos( $value_to_check, ',' );
                    if( $divider !== false ){
                        $value_to_check = substr( $value_to_check, $divider + 1 );
                    }
                    $value_to_check = trim( $value_to_check );
                break;
            }
            
            $rule_check_results = BSK_GFCV_Rules::validation_rule( $rule_saved_settings, $value_to_check );
            if( $rule_check_results['result'] == false ){
                
                $rule_check_results['list_id'] = $list_id_to_check;
                $rule_check_results['item_id'] = $rule_saved_settings['item_id'];
                
                return $rule_check_results;
            }
        }
        
        return array( 'result' => true, 'message' => '' );
    }
    
    public static function bsk_gfcv_front_check_list_status( $list_id_to_check, $list_type ) {
        global $wpdb;

        $sql = 'SELECT COUNT(*) FROM `'.$wpdb->prefix.BSK_GFCV::$_bsk_gfcv_list_tbl_name.'` WHERE `id` = %d AND `list_type` = %s';
        $sql = $wpdb->prepare( $sql, intval($list_id_to_check), $list_type );
        if( $wpdb->get_var( $sql ) < 1 ){
            return false;
        }

        return true;
    }
}
