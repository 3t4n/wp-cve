<?php

namespace Element_Ready\Api;

Class Open_Weather_Api {

    public static $api_key = '';

    function __construct() {
        self::$api_key = sanitize_text_field(element_ready_get_api_option('weather_api_key'));
    }

    public static function get($options = []){

        $lat      = 35;
        $lon      = 139;
        $city_id  = 'london';
        $cach     = false;
        $base_url = 'http://api.openweathermap.org/data/2.5/weather';
     
        $args = [
            'appid' => self::$api_key
        ];

        $settings = $options;
       
        if ( isset($settings['api_key']) && $settings['api_key'] !='' ) {

            $api_key       = sanitize_text_field($settings['api_key']);
            $args['appid'] = $api_key;
        }
      
        if ( isset($settings['coordinates_lat']) && $settings['coordinates_lat'] !='' ) {
            $lat = sanitize_text_field($settings['coordinates_lat']);
        }
        
        if ( isset( $settings['coordinates_lon'] ) && $settings[ 'coordinates_lon' ] !='' ) {
            $lon = sanitize_text_field($settings['coordinates_lon']);
        }

        if ( isset($settings['city_name']) && $settings['city_name'] !='' ) {
            $city_id = sanitize_text_field($settings['city_name']);
        }   
      
        if (isset($settings['weather_cache_enable']) && $settings['weather_cache_enable'] == 'yes'){
          $cach = true;
        }

        if ( $settings['weather_coordinate'] =='yes' ) {

            $args['lat'] = $lat;
            $args['lon'] = $lon;

        } else {
          
            $args['q'] = $city_id;
        }

        if ( isset($settings['units']) ) {
            $args['units'] = $settings['units'];
        }
       
        $url       = add_query_arg( $args, $base_url );
        $response  = wp_remote_get( esc_url_raw($url) );
        $data      = json_decode(wp_remote_retrieve_body( $response ));
        return $data;
    }
    public static function airpollution($options = []){
     
        $lat     = 50;
        $lon     = 50;
        $cach = false;
        $base_url = 'http://api.openweathermap.org/data/2.5/air_pollution';
    
        $args = [
            'appid' => self::$api_key
        ];

        $settings = $options;
       
        if( isset($settings['api_key']) && $settings['api_key'] !='' ){
            $api_key = sanitize_text_field($settings['api_key']);
            $args['appid'] = $api_key;
        }
      
        if( isset($settings['coordinates_lat']) && $settings['coordinates_lat'] !='' ){
            $lat = sanitize_text_field($settings['coordinates_lat']);
        }
        
        if( isset($settings['coordinates_lon']) && $settings['coordinates_lon'] !='' ){
            $lon = sanitize_text_field($settings['coordinates_lon']);
        }
      
        if(isset($settings['weather_cache_enable']) && $settings['weather_cache_enable'] == 'yes'){
          $cach = true;
        }
       
        $args['lat'] = $lat;
        $args['lon'] = $lon;
      
        $url = add_query_arg( $args, $base_url );
      
        $response  = wp_remote_get( esc_url_raw( $url ) );
        $data      = json_decode( wp_remote_retrieve_body( $response ) );
        return $data;
    }

    public static function historical($options = []) {
      
        $lat      = 50;
        $lon      = 50;
        $cach     = false;
        $base_url = 'https://api.openweathermap.org/data/2.5/onecall';
   
        $args = [
            'appid' => self::$api_key
        ];

        $settings = $options;
       
        if ( isset($settings['api_key']) && $settings['api_key'] !='' ) {
           $api_key = sanitize_text_field($settings['api_key']);
           $args['appid'] = $api_key;
        }
      
        if ( isset($settings['coordinates_lat']) && $settings['coordinates_lat'] !='' ) {
            $lat = sanitize_text_field($settings['coordinates_lat']);
        }
        
        if ( isset($settings['coordinates_lon']) && $settings['coordinates_lon'] !='' ) {
            $lon = sanitize_text_field($settings['coordinates_lon']);
        }
        
        if ( isset($settings['units']) && $settings['units'] !='' ) {
            $units = sanitize_text_field($settings['units']);
            $args['units'] = $units;
        }

        if ( isset($settings['exclude']) && $settings['exclude'] !='' ) {
          
            $args['exclude'] = sanitize_text_field($settings['exclude']);
        }
     
        if (isset($settings['weather_cache_enable']) && $settings['weather_cache_enable'] == 'yes') {
          $cach = true;
        }
       
        $args['lat'] = $lat;
        $args['lon'] = $lon;
      
        $url      = add_query_arg( $args, $base_url );
        $response = wp_remote_get( esc_url_raw($url) );
        $data     = json_decode( wp_remote_retrieve_body( $response ) );
        return $data;
    }
    
    public static function map($options = []) {

        $settings = $options;
      
        $lat   = 1;
        $lon   = 2;
        $zoom  = $settings['zoom'];
        $layer = $settings['layer'];
        $cach  = false;
        $args = [
            'appid' => self::$api_key
        ];

        if ( isset($settings['coordinates_lat']) && $settings['coordinates_lat'] !='' ) {
            $lat = sanitize_text_field($settings['coordinates_lat']);
        }
        
        if ( isset($settings['coordinates_lon']) && $settings['coordinates_lon'] !='' ) {
            $lon = sanitize_text_field($settings['coordinates_lon']);
        }
     
        $base_url = "https://tile.openweathermap.org/map/{$layer}/{$zoom}/{$lon}/{$lat}.png";
      
        $url      = add_query_arg( $args, $base_url );
        $response = wp_remote_get( esc_url_raw($url) );
        $data     = json_decode( wp_remote_retrieve_body( $response ) );
        return $data;
    }

}