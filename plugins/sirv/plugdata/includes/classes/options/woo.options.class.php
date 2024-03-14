<?php
defined('ABSPATH') or die('No script kiddies please!');

include_once "option.generator.class.php";

class Woo_options extends Options_generator{

  protected static function render_sirv_content_cache($option){
    $without_content = 0;
    $with_content = 0;

    if (isset($option['data_provider']) && !empty($option['data_provider'])) {
      $cache_data = call_user_func($option['data_provider']);
      $without_content = (int) $cache_data['empty'] + (int) $cache_data['missing'];
      $with_content = (int) $cache_data['all'] - $without_content;

      $option['values'][0]['label'] = $option['values'][0]['label'] . ' (<span class="' . $option['option_name'] . '-' . $option['values'][0]['attrs']['value'] . '">' . $with_content . '</span>)';
      $option['values'][1]['label'] = $option['values'][1]['label'] . ' (<span class="' . $option['option_name'] . '-' . $option['values'][1]['attrs']['value'] . '">' . $without_content . '</span>)';
    }


    $html = '
    <tr>
      ' . self::render_option_title($option['label']) . '
      <td>
        ' . self::render_radio_component($option) . '
      </td>
    </tr>
    <tr>
      <th></th>
      <td>
        <input type="button" name="' . $option['option_name'] . '" class="button-primary ' . $option['button_class'] . '" value="' . $option['button_val'] . '">&nbsp;
        <span class="sirv-traffic-loading-ico" style="display: none;"></span>
        <span class="sirv-show-empty-view-result" style="display: none;"></span>
      </td>
    </tr>
    <tr>
    <th></th>
      <td style="color: #666666;">
          Content found in your Sirv folders is cached.
          If you see outdated content in a product
          gallery, clear the cache.
      </td>
    </tr>';

    return $html;
  }


  protected static function render_pin_gallery($option){
    $values = array(
      array(
        'label' => 'Unpinned',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'no',
        ),
      ),
      array(
        'label' => 'Left',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'left',
        ),
      ),
      array(
        'label' => 'Right',
        'check_data_type' => 'checked',
        'attrs' => array(
          'type' => 'radio',
          'value' => 'right',
        ),
      ),
    );

    $radio_data = array(
      'Pin video(s)' => array(
        'option_name' => 'sirv-woo-pin-video',
        'value' => '',
        'check_value' => 'video',
        'values' => $values,
    ),
      'Pin spin(s)' => array(
        'option_name' => 'sirv-woo-pin-spin',
        'value' => '',
        'check_value' => 'spin',
        'values' => $values,
      ),
      'Pin model(s)' => array(
        'option_name' => 'sirv-woo-pin-model',
        'value' => '',
        'check_value' => 'model',
        'values' => $values,
      ),
      'Pin images by file mask' => array(
        'option_name' => 'sirv-woo-pin-image',
        'value' => '',
        'check_value' => 'image',
        'values' => $values,
      ),
    );

    $option_data = json_decode($option['value'], true);
    //$option['attrs']['value'] = esc_attr($option['value']);

    $input_data = array(
      'attrs' => array(
        'type' => 'text',
        'placeholder' => 'e.g. *-hero.jpg ',
        'value' => $option_data['image_template'],
        'id' => 'sirv-woo-pin-input-template',
      ),
    );

    $radio_html = '<table class="sirv-woo-pin-table-radio"><tbody>';
    foreach ($radio_data as $radio_name => $radio_item) {
      foreach ($radio_item['values'] as $index => $sub_option) {
        //cheking if option checked, readonly, disabled etc for multiple options like radio and added param to attrs.
        $radio_item['values'][$index] = self::check_option($sub_option, $option_data[$radio_item['check_value']]);

        if (!isset($sub_option['attrs']['name'])) {
          $radio_item['values'][$index]['attrs']['name'] = $radio_item['option_name'];
        }
      }

      $radio_html .= "<tr><th>$radio_name</th><td>" . self::render_radio_component($radio_item) . '</td></tr>' . PHP_EOL;
    }
    $radio_html .= '</tbody></table>';

    $above_text = (isset($option['above_text']) && $option['above_text']) ? self::render_above_text($option['above_text']) : '';
    $is_img_input_hide = $option_data['image'] == 'no' ? 'sirv-block-hide ' : '';

    $html = '
      <tr>
        ' . self::render_option_title($option['label']) .'
        <td>
        ' . $above_text . '<br>
        '. $radio_html . '
        <div class="'. $is_img_input_hide .'sirv-woo-pin-input-wrapper">
          '. self::render_text_component($input_data) .'
          '. self::render_below_text('Filenames matching this pattern will be pinned. Use * as a wildcard.') .'
        </div>
        '. self::render_hidden_component($option) .'
        </td>
      </tr>';

    return $html;
  }


  protected static function render_sirv_smv_order_content($option){
    //$above_text = (isset($option['above_text']) && $option['above_text']) ? self::render_above_text($option['above_text']) : '';
    $option_data = json_decode($option['value']);
    //$option['attrs']['value'] = json_encode($option_data, JSON_HEX_APOS | JSON_HEX_QUOT);
    //$option['attrs']['value'] = htmlspecialchars(json_encode($option_data), ENT_QUOTES, 'UTF-8');
    $select_items = array('spin' => 'Spin', 'video' => 'Video', 'zoom' => 'Zoom', 'image' => 'Image', 'model' => 'Model');
    $order_html = '';

    if(!empty($option_data)){
      foreach ($option_data as $item_type) {
        $order_html .= '
          <li class="sirv-smv-order-item sirv-smv-order-item-changeble sirv-no-select-text" data-item-type="'. $item_type .'">
          <div class="sirv-smv-order-item-dots">⠿</div>
          <div class="sirv-smv-order-item-title"><span>'. $select_items[$item_type] . '</span></div>
          <div class="sirv-smv-order-item-delete"><span class="dashicons dashicons-trash"></span></div>
        </li>
        ';
      }
    }

    $html =
    '<tr>
    ' . self::render_option_title($option['label']) . '
      <td>
        <div class="sirv-smv-order-content-wrapper">
          <ul id="sirv-smv-order-items">
            '. $order_html . '
            <li class="sirv-smv-order-item sirv-smv-order-item-add sirv-no-select-text">
              <div class="sirv-smv-order-select">
                <ul class="sirv-smv-order-select-items">
                  <li class="sirv-smv-order-select-items-title">Add new:</li>
                  '. self::render_sirv_smv_order_content_select_options($select_items) . '
                </ul>
              </div>
              <div class="sirv-smv-order-item-title sirv-smv-order-title-add"><span class="dashicons dashicons-plus"></span></div>
            </li>
          </ul>
        </div>
        ' . self::render_hidden_component($option) . '
      </td>
    </tr>';

    return $html;
  }


  protected static function render_sirv_smv_order_content_select_options($items){
    $html = '';

    foreach ($items as $item_type => $item_title) {
      $html .= '<li class="sirv-smv-order-select-item" data-item-type="'. $item_type .'">'. $item_title .'</li>' . PHP_EOL;
    }

    return $html;
  }


  protected static function render_migrate_woo_additional_images($option){
    require_once(SIRV_PLUGIN_SUBDIR_PATH . 'includes/classes/woo.additional.images.migrate.class.php');

    $info = WooAdditionalImagesMigrate::get_wai_data_info();

    if($info->unsynced == 0){
      if( $info->all == 0 ){
        $html = '<p>This plugin was not detected.</p>';
      }else{
        $html = '<p>All images have been migrated. You may wish to uninstall the WooCommerce Additional Variation Images plugin.</p>';
      }
    }else{
      $html =
        '<div class="sirv-wai-container">
            <div class="sirv-wai-button">
              <button class="button-primary sirv-migrate-wai-data" type="button">Migrate</button>
            </div>
            <div class="sirv-progress">
            <div class="sirv-progress__text">
              <div class="sirv-wai-progress-text-persents">'. $info->synced_percent_text . '</div>
              <div class="sirv-progress-text-complited sirv-wai-progress-text-complited"><span>'. $info->synced .' out of '. $info->all .'</span> variations completed</div>
            </div>
            <div class="sirv-progress__bar">
              <div class="sirv-wai-bar-line-complited sirv-complited" style="width: '. $info->synced_percent_text .';"></div>
            </div>
          </div>
        </div>';
    }

    return '
      <tr>
        <th class="sirv-migrate-wai-data-messages no-padding" colspan="2">
        </th>
      </tr>
      <tr>
        <th>'. $option['label'] . '</th>
        <td colspan="2">
          <div class="migrate-woo-additional-images-wrapper">
          <span class="sirv-option-responsive-text">' . $option['description'] . '</span><br><br>
            '. $html .'
          </div>
        </td>
      </tr>';
  }
}

?>
