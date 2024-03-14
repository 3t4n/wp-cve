<?php
namespace Shop_Ready\system\base\dashboard;
/*
* Dashboard widget,module form submission base class
* 
*/
class Form {
   
    public function validate_options($options = [], $all=false){
        
        if(!is_array($options)){
            return $options;
        }
        $return_options = [];
        foreach( $options as $key => $value ){

            if( $all ){
                
                if( isset( $value[ 'is_pro' ] ) && $value[ 'is_pro' ] == 1 ){
                    $return_options[$key] = 'on'; 
                }else{
                    $return_options[$key] = ''; 
                }
                 
            }else{
                $return_options[$key] = sanitize_text_field($value); 
            }
           
        }
        return $return_options;
    }

    public function validate_all_options($options = [], $all = false){
        
        if(!is_array( $options )){
            return $options;
        }
               
        foreach( $options as $key => $value ){

            if( $all ){
                
                if( isset( $value[ 'is_pro' ] ) && $value[ 'is_pro' ] == 1 ){
                    unset($options[$key]); 
                }else{
                    $options[$key] = 'on'; 
                }
                 
            }else{
                $options[$key] = 'on'; 
            }
           
        }

        return $options;
    }

    public function get_transform_options($options = [], $key = false){

        if( !is_array($options) || $key == false ){
            return $options;
        }

        $db_option      = get_option( $key );
       
        $return_options = $options;

        foreach( $options as $key => $value ){

            if( isset($db_option[$key]) ){
                $return_options[$key][ 'default' ] = 1; 
            }else{
                $return_options[$key][ 'default' ] = 0;    
            }  
        
        }

        return $return_options; 
    }

    public function get_transform_inputs_options($options = [], $key = false){

        if( !is_array($options) || $key == false ){
            return $options;
        }

        $db_option  = get_option( $key );

        $return_options = $options;
        
        foreach( $options as $key => $value ){

            if( isset($db_option[$key]) ){
                $return_options[$key][ 'default' ] = $db_option[$key]; 
            }else{
                $return_options[$key][ 'default' ] = '';    
            }

        }
        return $return_options; 
    }
}