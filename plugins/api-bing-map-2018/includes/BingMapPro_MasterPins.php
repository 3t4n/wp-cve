<?php
namespace BingMapPro_MasterPins;

if( ! defined('ABSPATH') ) die('No Access to this page');

class BingMapPro_Pin{
    private  $pins_table;
    private  $map_pins_table;
    private  $map_shapes_table;

    public function __construct(){
        global $wpdb;
        $this->pins_table       = $wpdb->prefix . 'bingmappro_pins';
        $this->map_pins_table   = $wpdb->prefix . 'bingmappro_map_pins';
    }
    
    public function getAllPins(){
        global $wpdb;
        $allPins = $wpdb->get_results("SELECT id, pin_name, pin_address, pin_lat, pin_long, 
                                            IFNULL(icon,'') AS icon, 
                                            IFNULL(icon_link, '') AS icon_link,
                                            IFNULL(pin_title,'') AS pin_title,
                                            IFNULL(pin_desc, '') AS pin_desc,
                                            IFNULL(pin_image_one, '') AS pin_image_one,
                                            IFNULL(pin_image_two, '') AS pin_image_two,
                                            IFNULL(data_json, '') AS data_json
                                        FROM $this->pins_table 
                                        ORDER BY created_at DESC");
                                       
        if( count( $allPins) > 0 )
            return $allPins;
        else
            return null;
    }

    public function getPinsLinkedToMap(){
        global $wpdb;
        $query = $wpdb->get_results("SELECT * FROM $this->map_pins_table  GROUP by pin_id, map_id ORDER BY pin_id ASC");
        $result = array();
        foreach( $query as $item ){
            if(isset( $result[ $item->pin_id ] )){
                if( ! is_array( $result[ $item->pin_id ] )){
                    $result[ $item->pin_id ] = array();
                }
            }else{
                $result[ $item->pin_id ] = array();
            }
            
            array_push( $result[ $item->pin_id ], $item->map_id );
        }

        return $result;        
    }

    public function saveNewPin( $pin_data ){
        global $wpdb;

        $pin_name       = sanitize_text_field( $pin_data['name']);
        $pin_address    = sanitize_text_field( $pin_data['address']);       
        $pin_lat        = sanitize_text_field( $pin_data['lat']);
        $pin_long       = sanitize_text_field( $pin_data['long']);
        $pin_icon       = sanitize_text_field( $pin_data['icon']);
        $pin_url        = strtolower( $pin_data['pin_url'] );

        $pin_info_type  = sanitize_text_field( $pin_data['info_type'] );
        $pin_info_sel   = sanitize_text_field( $pin_data['info_selected']);
        $pin_info_title = sanitize_text_field( $pin_data['info_title']);
        $pin_info_desc  = sanitize_text_field( $pin_data['info_desc']);
        $pin_info_html  = $pin_data['info_html'];


        if( strpos( $pin_url, 'http') === true ){
            $pin_url = sanitize_email( $pin_url );
        }else{
            $pin_url = sanitize_text_field( $pin_url );
        } 

        $wpdb->query( $wpdb->prepare("INSERT INTO $this->pins_table ( pin_name, pin_address, pin_lat, pin_long, icon, icon_link,
                                                                pin_title, pin_desc, pin_image_one, pin_image_two, data_json, created_at )
                                        VALUES ( %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, %s, CURRENT_TIMESTAMP);", 
                                        $pin_name, $pin_address, $pin_lat, $pin_long, $pin_icon, $pin_url, 
                                        $pin_info_title, $pin_info_desc, $pin_info_type, $pin_info_sel, $pin_info_html ) );
     
    }

    public function getLastAddedPin(){
        global $wpdb;     
        $bmp_last_created_pin = $wpdb->get_results("SELECT id, pin_name, pin_address, pin_lat, pin_long, 
                                                        IFNULL(icon,'') AS icon, 
                                                        IFNULL(icon_link, '') AS icon_link,
                                                        IFNULL(pin_title,'') AS pin_title,
                                                        IFNULL(pin_desc, '') AS pin_desc,
                                                        IFNULL(pin_image_one, '') AS pin_image_one,
                                                        IFNULL(pin_image_two, '') AS pin_image_two,
                                                        IFNULL(data_json, '') AS data_json
                                                    FROM $this->pins_table 
                                                    ORDER BY created_at DESC LIMIT 1;");

        if( count( $bmp_last_created_pin ) > 0 ){
            return $bmp_last_created_pin[0];
        }else{
            return null;
        }
    }

    public function disableEnablePin( $pin_id, $value ){
        global $wpdb;
        return $wpdb->query( $wpdb->prepare("UPDATE $this->pins_table SET active = %d WHERE id = %d;", $value == 'true', $pin_id ) );
    }

    public function deletePin( $pin_id ){
        global $wpdb;  
        $pins_map_table = $wpdb->prefix . 'bingmappro_map_pins';    
                $wpdb->query( $wpdb->prepare("DELETE FROM $pins_map_table WHERE pin_id = %d;", $pin_id ));          
        return  $wpdb->query( $wpdb->prepare("DELETE FROM $this->pins_table WHERE id = %d;",  $pin_id ));        
    }
    public function bmpGetPinById( $pin_id ){
        global $wpdb;
        return $wpdb->get_results( $wpdb->prepare("SELECT id, pin_name, pin_address, pin_lat, pin_long, 
                                                        IFNULL(icon,'') AS icon, 
                                                        IFNULL(icon_link, '') AS icon_link,
                                                        IFNULL(pin_title,'') AS pin_title,
                                                        IFNULL(pin_desc, '') AS pin_desc,
                                                        IFNULL(pin_image_one, '') AS pin_image_one,
                                                        IFNULL(pin_image_two, '') AS pin_image_two,
                                                        IFNULL(data_json, '') AS data_json
                                                    FROM $this->pins_table
                                                    WHERE id = %d", $pin_id ));
    }


    public function updatePin( $pin_data ){
        global $wpdb;

        $pin_id         = sanitize_text_field( (int)$pin_data['id'] );
        $pin_name       = sanitize_text_field( $pin_data['name']);
        $pin_address    = sanitize_text_field( $pin_data['address']);       
        $pin_lat        = sanitize_text_field( $pin_data['lat']);
        $pin_long       = sanitize_text_field( $pin_data['long']);
        $pin_icon       = sanitize_text_field( $pin_data['icon']);
        $pin_url        = $pin_data['pin_url'];

        $pin_info_type  = sanitize_text_field( $pin_data['info_type'] );
        $pin_info_sel   = sanitize_text_field( $pin_data['info_selected']);
        $pin_info_title = sanitize_text_field( $pin_data['info_title']);
        $pin_info_desc  = sanitize_text_field( $pin_data['info_desc']);
        $pin_info_html  = $pin_data['info_html'];


        if( strpos( $pin_url, 'http') === true ){
            $pin_url = esc_url_raw( $pin_url );
        }else{
            $pin_url = sanitize_text_field( $pin_url );
        } 

        $q_result =  $wpdb->query( $wpdb->prepare( "UPDATE $this->pins_table SET
                                                        pin_name = %s, 
                                                        pin_address = %s, 
                                                        pin_lat = %s, 
                                                        pin_long = %s, 
                                                        icon = %s, 
                                                        icon_link = %s,
                                                        pin_title = %s,
                                                        pin_desc = %s, 
                                                        pin_image_one = %s, 
                                                        pin_image_two = %s, 
                                                        data_json = %s
                                                    WHERE id = %d", $pin_name, $pin_address, $pin_lat, $pin_long, $pin_icon, $pin_url,
                                                                 $pin_info_title, $pin_info_desc, $pin_info_type, $pin_info_sel, $pin_info_html, $pin_id  ));    
                

        return $q_result;                                        

    }

}