<?php

namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

class WpCustomControlSelect2 extends \WP_Customize_Control
{
  public $type = 'select2';
  public function render_content() {
    ?>
      <label >
        <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
        <div id="njt-nofi-select2-modal-<?php echo esc_attr( $this->id ); ?>">
          <select class="njt-nofi-select2-<?php echo esc_attr( $this->id ); ?>">
          </select>
        </div>
        <input id="_customize-input-<?php echo esc_attr( $this->id ); ?>" class="njt_nofi_none" type="text" value="" data-customize-setting-link="<?php echo esc_attr( $this->id ); ?>">
      </label>
    <?php 
  }
}
