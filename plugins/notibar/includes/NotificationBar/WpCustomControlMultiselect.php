<?php

namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

class WpCustomControlMultiselect extends \WP_Customize_Control
{
  public $type = 'multiple-select';
  public function render_content() {
    ?>
      <label >
        <div id="njt-nofi-select2-multiple-modal-<?php echo esc_attr( $this->id ); ?>">
          <select multiple="multiple" class="njt-nofi-select2-multiple-<?php echo esc_attr( $this->id ); ?>" data-placeholder="Select an option">
          </select>
        </div>
        <input id="_customize-input-<?php echo esc_attr( $this->id ); ?>" class="njt_nofi_none" type="text" value="" data-customize-setting-link="<?php echo esc_attr( $this->id ); ?>">
      </label>
    <?php 
  }
}
