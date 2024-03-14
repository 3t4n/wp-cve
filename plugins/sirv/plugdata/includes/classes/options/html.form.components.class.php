<?php
defined('ABSPATH') or die('No script kiddies please!');

class HTML_form_components{

  protected static function render_option_title($label){
    $html =
    '<th>
        <label>' . $label . '</label>
      </th>';

    return $html;
  }


  protected static function render_checkbox_component($option){
    $html = '
        <input type="checkbox" name="'. $option['option_name'] .'" id="'. $option['option_name'] .'" value="1" '. checked('1', $option['value'], false ) .' >
        <span class="sirv-option-responsive-text">'. $option['desc'] .'</span>';

    return $html;
  }


  protected static function render_text_component($option){
    return self::render_input_component($option['attrs']);
  }


  protected static function render_hidden_component($option){
    return self::render_input_component($option['attrs']);
  }


  protected static function render_checkbox_group_component($option){
    $html = '';

    $br = isset($option['is_new_line']) ? '<br />' : '';

    foreach ($option['values'] as $checkbox_item) {
      $label = isset($checkbox_item['desc']) ? '<b>' . $checkbox_item['label'] . '</b> - ' . $checkbox_item['desc'] : $checkbox_item['label'];
      $html .= '<label>' . self::render_input_component($checkbox_item['attrs']) . $label . '</label>' . $br . PHP_EOL;
    }

    return $html;
  }


  protected static function render_radio_component($option){
    $html = '';

    $br = isset($option['is_new_line']) ? '<br />' : '';

    foreach ($option['values'] as $radio_item) {
      $label = isset($radio_item['desc']) ? '<b>' . $radio_item['label'] . '</b> - ' . $radio_item['desc'] : $radio_item['label'];
      $html .= '<label>'. self::render_input_component($radio_item['attrs']) . $label . '</label>' . $br . PHP_EOL;
    }

    return $html;
  }


  protected static function render_select_component($option){
    $is_render_empty_option = (isset($option['render_empty_option']) && $option['render_empty_option']) ? true : false;
    $select_items = '';
    if($is_render_empty_option){
      $default_selected = ($option['value'] == '') ? 'selected' : '';
      $select_items = '<option value="" ' . $default_selected . '>-</option>';
    }

    foreach ($option['select_data'] as $name => $value) {
      $select_items .= "<option value='{$value}' " . selected($value, $option['value'], false) . ">{$name}</option>" . PHP_EOL;
    }

    $html =
    '<select id="' . $option['select_id'] . '">
      <option disabled>'. $option['select_title'] .'</option>
      '. $select_items . '
    </select>' . PHP_EOL . self::render_input_component($option['attrs']);

    return $html;
  }


  protected static function render_textarea_component($option){
    $html = '<textarea ' . self::render_attributes($option['attrs']) . '>' . $option['attrs']['value'] . '</textarea>' . PHP_EOL;

    return $html;
  }


  protected static function render_above_text($above_text){
    $html = '<span class="sirv-option-responsive-text">' . $above_text . '</span>';

    return $html;
  }


  protected static function render_below_text($below_text){
    $html = '<span class="sirv-option-responsive-text">' . $below_text . '</span>';

    return $html;
  }


  protected static function render_option_status($option){
    $html = '';
    if ( (isset($option['show_status']) && $option['show_status']) && isset($option['enabled_value']) ){
      $status_class = $option['value'] == $option['enabled_value'] ? 'sirv-status--enabled' : 'sirv-status--disabled';
      $html = '
        <td>
          <span class="sirv-status ' . $status_class . '"></span>
        </td>' . PHP_EOL;
    }

    return $html;
  }


    protected static function render_tooltip($option){
    $tooltip = '';
    if ( isset($option['tooltip']) ) {
      $tooltip = '
      <td>
        <div class="sirv-tooltip">
          <i class="dashicons dashicons-editor-help sirv-tooltip-icon"></i>
          <span class="sirv-tooltip-text sirv-no-select-text">' . $option['tooltip'] . '</span>
        </div>
      </td>
      ';
    }

    return $tooltip;
  }


  protected static function render_input_component($attrs){
    return '<input '. self::render_attributes($attrs) .'>';
  }


  /*
  $excludeAttr: array of attr names
  */
  protected static function render_attributes($attrs){
    $attrs_str = '';
    foreach ($attrs as $attr_name => $attr_value) {
      $value = htmlspecialchars($attr_value, ENT_QUOTES, 'UTF-8');
      $attrs_str .= "$attr_name=\"$value\" ";
      //$attrs_str .= "$attr_name=\"$attr_value\" ";
    }

    return $attrs_str;
  }


  protected static function isAttr($attr){
    return (isset($attr) && $attr);
  }


  protected static function checked($value, $current){
    return (string) $value === (string) $current;
  }

}

?>
