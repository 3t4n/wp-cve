<?php
defined('ABSPATH') or die('No script kiddies please!');

include_once "html.form.components.class.php";

class Options_components extends HTML_form_components{

  protected static function render_radio_option($option){
    $below_text = isset($option['below_text']) ? $option['below_text'] : '';
    $id_selector = isset($option['id_selector']) ? 'id="'. $option['id_selector'] .'"' : '';
    $html = '
      <tr '. $id_selector .'>
        ' . self::render_option_title($option['label']) . '
        <td>
        ' . self::render_radio_component($option) . '
        ' . self::render_below_text($below_text) . '
        </td>
      ' . self::render_option_status($option) . '
      ' . PHP_EOL . self::render_tooltip($option) . '
      </tr>';

    return $html;
  }


  protected static function render_checkbox_group_option($option){
    $below_text = isset($option['below_text']) ? $option['below_text'] : '';
    $id_selector = isset($option['id_selector']) ? 'id="' . $option['id_selector'] . '"' : '';
    $html = '
      <tr ' . $id_selector . '>
        ' . self::render_option_title($option['label']) . '
        <td>
        ' . self::render_checkbox_group_component($option) . '
        ' . self::render_below_text($below_text) . '
        '. self::render_hidden_component($option) .'
        </td>
      ' . PHP_EOL . self::render_tooltip($option) . '
      </tr>';

    return $html;
  }


  protected static function render_text_option($option){
    $above_text = isset($option['above_text']) ? $option['above_text'] : '';
    $below_text = isset($option['below_text']) ? $option['below_text'] : '';
    $id_selector = isset($option['id_selector']) ? 'id="' . $option['id_selector'] . '"' : '';
    $html = '
      <tr ' . $id_selector . '>
        ' . self::render_option_title($option['label']) . '
        <td>
        '. self::render_above_text($above_text) .'
        ' . self::render_text_component($option) . '
        '. self::render_below_text($below_text) .'
        </td>
      ' . PHP_EOL . self::render_tooltip($option) . '
      </tr>';

    return $html;
  }


  protected static function render_select_option($option){
    $below_text = isset($option['below_text']) ? $option['below_text'] : '';
    $id_selector = isset($option['id_selector']) ? 'id="' . $option['id_selector'] . '"' : '';
    $html = '
      <tr ' . $id_selector . '>
        ' . self::render_option_title($option['label']) . '
        <td>
        '. self::render_select_component($option) . '
        ' . self::render_below_text($below_text) . '
        </td>
        ' . PHP_EOL . self::render_tooltip($option) . '
    </tr>';

    return $html;
  }


  protected static function render_textarea_option($option){
    $above_text = (isset($option['above_text']) && $option['above_text']) ? self::render_above_text($option['above_text']) : '';
    $id_selector = isset($option['id_selector']) ? 'id="' . $option['id_selector'] . '"' : '';
    $html = '
      <tr ' . $id_selector . '>
        ' . self::render_option_title($option['label']) . '
        <td>
          ' . $above_text . '
          '. self::render_textarea_component($option) .'
        </td>
        ' . PHP_EOL . self::render_tooltip($option) . '
    </tr>';

    return $html;
  }


  /* protected static function get_dependence($option){
    $dep_html = array('hide' => '', 'disable' => '');
    if (
      !isset($option['dependence']) || empty($option['dependence'])
    ) return $dep_html;

    $dep_value = self::$options[$option['dependence']['name']]['value'];

    if ($dep_value == $option['dependence']['value']) {
      $dep_type = $option['dependence']['type'];
      switch ($dep_type) {
        case 'disable':
          $dep_html['disable'] = 'disabled';
          break;
        case 'hide':
          $dep_html['hide'] = 'style="display: none;"';
          break;

        default:
          # code...
          break;
      }
    }

    return $dep_html;
  } */

}

?>
