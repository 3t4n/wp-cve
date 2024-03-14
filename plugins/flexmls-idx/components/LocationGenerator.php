<?php
namespace FlexMLS\Admin;

defined( 'ABSPATH' ) or die( 'This plugin requires WordPress' );

class LocationGenerator {

  function __construct() {
  }

    static function tinymce_form() {

      /* $SparkAPI = new \SparkAPI\Account();
      $me = $SparkAPI->get_my_account();
      $me = $me[ 'UserType' ]; */
        $field_type = !empty($_POST['field_type']) ? $_POST['field_type'] : '';
    
        $settings_content = self::location_field($field_type);

        $response = array(
            'title' => 'Location Generator',
            'body' => $settings_content
        );
    
        exit( json_encode( $response ) );    
        /* echo flexmlsJSON::json_encode($response);
        exit; */
    }

    static function location_field($type){
        $location = '';

        if($type == 'multiple'){
          $label = 'Locations:';
          $multiply = 'multiple="true"';
          $name = 'location';
        } else {
          $label = 'Location:';
          $multiply = '';
          $name = 'location';
        }

        $portal_slug = 'flatin';//flexmlsConnect::get_portal_slug();
        $header = '';//flexmlsConnect::shortcode_header();
        $footer = '';//flexmlsConnect::shortcode_footer();

        $return = '';
        $return .= "
        <p>
        <label for='".self::get_field_id($name)."'>" . __($label) . "</label> 

        <select class='flexmlsAdminLocationSearch' type='hidden' style='width: 100%;' ".$multiply."
          id='" . self::get_field_id($name) . "' name='" . self::get_field_name('location_input') . "'
          data-portal-slug='" . $portal_slug . "'>
        </select>
      
        <input fmc-field='location' fmc-type='text' type='hidden' value=\"{$location}\" 
          name='" . self::get_field_name($name) . "' class='flexmls_connect__location_fields' />
      </p>
        ";

        return $header . $return . $footer;
    }

    static function get_field_id($val) {
        return "fmc_shortcode_field_{$val}";
    }

    static function get_field_name($val) {
        return $val;
    }
}

//$location_generator = new \FlexMLS\Admin\LocationGenerator;