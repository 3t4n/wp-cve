<?php
if (!function_exists('msbd_sanitization')) {
    /*
     * @ $field_type = title, text, email, number, html, no_html, custom_html, html_js default text
     */
    function msbd_sanitization($data, $field_type='text', $oArray=array()) {        
        
        if( is_array($data) ) {
            
            if( empty($data) )
                return $data;
            else {
                foreach($data as $i=>$v) {
                    $data[$i] = msbd_sanitization($v, $field_type, $oArray);
                }
                
                return $data;
            }           
        }

        $output = '';

        switch($field_type) { 
            case 'number':
                $output = sanitize_text_field($data);
                $output = intval($output);
                break;
            
            case 'boolean':
                $var_permitted_values = array('y', 'n', 'true', 'false', '1', '0', 'yes', 'no');
                $output = in_array($data, $var_permitted_values) ? $data : 0;//returned false if not valid
                break;
            
            case 'email':
                $output = sanitize_email($data);
                $output = is_email($output);//returned false if not valid
                break;
                
            case 'textarea': 
                $output = esc_textarea($data);
                break;
            
            case 'html':                                         
                $output = wp_kses_post($data);
                break;
            
            case 'custom_html':                    
                $allowedTags = isset($oArray['allowedTags']) ? $oArray['allowedTags'] : "";                                        
                $output = wp_kses($data, $allowedTags);
                break;
            
            case 'no_html':                                        
                $output = strip_tags( $data );
                break;
            
            
            case 'html_js':
                $output = $data;
                break;
            
            case 'title':
                $output = sanitize_title($data);
                break;
            
            case 'text':
            default:
                $output = sanitize_text_field($data);
                break;
        }

        return $output;
    }
}


function msbddelcom_comment_statuses() {
    return array(
		'0' => 'pending',
		'spam' => 'spam',
		'trash' => 'trash',
		'1' => 'approved',
	);
}


/*********************************************************
 * Functions collected from Website Configs plugin by Micro Solutions Bangladesh
 * *******************************************************/


/*
 * WP functions are: update_option(), 
 * 
 * */
if (!function_exists('msbd_update_option')) {
    function msbd_update_option($opt_name, $opt_val = '') {
        if (is_array($opt_name) && $opt_val == '') {
            foreach ($opt_name as $real_opt_name => $real_opt_value) {
                msbd_update_option($real_opt_name, $real_opt_value);
            }
        } else {
            update_option($opt_name, $opt_val);
        }
    }
}


/*
 * WP functions are: get_option(), 
 * 
 * */
if (!function_exists('msbd_get_option')) {
    function msbd_get_option($root_opt, $opt_name = '') {
        
        $options = get_option('msbddelcom_'.$root_opt);

        if ($options == false) {
            return false;
        }

        if ( !empty($opt_name) ) {
            return isset($options[$opt_name]) ? $options[$opt_name] : false;
        }

        return $options;
    }
}
