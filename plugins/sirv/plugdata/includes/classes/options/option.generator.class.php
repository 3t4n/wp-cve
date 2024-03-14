<?php
defined('ABSPATH') or die('No script kiddies please!');

include_once "options.components.class.php";

class Options_generator extends Options_components{
  protected static $options = array();


  public static function render_options($options_data){
    $html = '';

    foreach ($options_data as $block_data) {
      $html .= self::render_options_block($block_data);
    }

    return $html;
  }


  protected static function render_options_block($block){
    $save_button_html = '
      <div class="sirv-save-button-wrapper">
        <input type="submit" name="submit" class="button-primary sirv-save-settings" value="Save Settings" />
      </div>';

    $render_save_button = $block['show_save_button'] ? $save_button_html : '';
    $html = '
    <h2>'. $block['title'] .'</h2>
    <p class="sirv-options-desc">'. $block['description'] .'</p>
    <div class="sirv-optiontable-holder" id="'. $block['id'] .'">
      <table class="sirv-woo-settings optiontable form-table">
        <tbody>
          '. self::loop($block['options']) . '
        </tbody>
      </table>
      '. $render_save_button .'
    </div>';

    return $html;
  }


  protected static function loop($options){
    $html = '';
    $options = self::update_options_values($options);

    foreach ($options as $option) {
      if ($option['enabled_option']) {
        $html .= call_user_func(array(get_called_class(), $option['func']), $option);
      }
    }

    return $html;
  }


  protected static function update_options_values($options){
    foreach ($options as $option_name => $option_data) {
      if (stripos($option_name, 'unreg_') !== false){
        $cur_value = $options[$option_name]['value'];
      }else{
        $cur_value = !get_option($option_name) ? $option_data['default'] : get_option($option_name);
        $options[$option_name]['value'] = $cur_value;
      }

      if ( isset($options[$option_name]['attrs']) ) $options[$option_name]['attrs']['value'] = $cur_value;
      if (!isset($options[$option_name]['attrs']['name'])) $options[$option_name]['attrs']['name'] = $options[$option_name]['option_name'];

      if ( isset($options[$option_name]['values']) ){
        foreach ($options[$option_name]['values'] as $index => $sub_option) {
          //cheking if option checked, readonly, disabled etc for multiple options like radio and added param to attrs.
          $options[$option_name]['values'][$index] = self::check_option($sub_option, $cur_value);

          if ( !isset($sub_option['attrs']['name']) ) {
            $options[$option_name]['values'][$index]['attrs']['name'] = $options[$option_name]['option_name'];
          }
        }
      }
    }

    return $options;
  }


  protected static function check_option($sub_option, $cur_value){
    $tmp_val = $sub_option;

      if (isset($sub_option['check_data_type']) && isset($sub_option['attrs'])) {
        if (isset($sub_option['data_type']) && isset($sub_option['data_key'])){
          $tmp_opt_arr = json_decode($cur_value, true);
          if(self::checked($sub_option['attrs']['value'], $tmp_opt_arr[$sub_option['data_key']])){
          $type = $sub_option['check_data_type'];
          $tmp_val['attrs'][$type] = $type;
          return $tmp_val;
          }
        }else{
        if (self::checked($sub_option['attrs']['value'], $cur_value)) {
          $type = $sub_option['check_data_type'];
          $tmp_val['attrs'][$type] = $type;
          return $tmp_val;
        }
        }
      }

    return $tmp_val;
  }


}
?>
