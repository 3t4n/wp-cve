<?php
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

class TypeFieldsVCS{

  static function get_selected_types_vce($fields_types, $value) {
    $output = array();
    if ($value) {
      $ids = explode(",", $value);
      foreach ($ids as $id) {
        if(isset($fields_types[0])){
          foreach($fields_types as $data){
            if($data['value'] === $id){
              $output[$id] = $data['display_text'];  
            }
          }
        } else {
          $data = $fields_types[$id];
          $output[$id] = $data;
        }
      }
      return $output;
    } else {
      return false;
    }
  }
}


function sortable_list_tag_field ($settings, $value){
    $param_name = $settings['param_name'];
    $fields_types = $settings['fields_types'];
    $button_name = $settings['button_name'];
    $collection = TypeFieldsVCS::get_selected_types_vce($fields_types, $value);

    $sortable_list = '<ul class="flexmls_connect__sortable loaded ui-sortable">';
  
    if(is_array($collection)) {
      foreach ($collection as $id => $display_text) {
        $sortable_list .= "<li data-connect-name='" . $id . "'>";
        $sortable_list .= "<span class='remove' title='Remove this from the search'>&times;</span>";
        $sortable_list .= "<span class='ui-icon ui-icon-arrowthick-2-n-s'></span>";
        $sortable_list .= $display_text;
        $sortable_list .= "</li>";
      }
    }

    $sortable_list .= "</ul>";

    $output = '';

    $output .= '<div>';
    $output .= '<input fmc-field="'.$param_name.'" fmc-type="text" type="hidden" name="'.$param_name.'"
      class="flexmls_connect__list_values wpb_vc_param_value" value="'.$value.'">';
    
      $output .= $sortable_list;

      $output .= '<select name="available_types" class="flexmls_connect__available">';
      foreach ($fields_types as $id => $data){
          if(is_array($data)){
            $output .= '<option value="'.$data['value'].'">'.$data['display_text'].'</option>';
          } else {
              $output .= '<option value="'.$id.'">'.$data.'</option>';
          }
      }
      $output .= '</select>';
    
      $output .= '<input type="button" title="Add this to the search" class="button add-proprtytype-button button-large fmc-margin-left-small flexmls_connect__add_property_type" value="'.$button_name.'"></input>';
      $output .= '<img src="x" class="flexmls_connect__bootloader" onerror="flexmls_connect.sortable_setup(this);">';
      $output .= '</div>';

    return $output;

}



vc_add_shortcode_param( 'sortable_list_tag', 'sortable_list_tag_field' );