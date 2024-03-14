<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlHandleButtonMobile extends \WP_Customize_Control 
  {
    public $type = 'njtHandleButtonMobile';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <label class="njt-handle-button-mobile-switch njt-nofi-button-switch" for="_customize-input-njt_nofi_handle_button_mobile">
                <input id="_customize-input-njt_nofi_handle_button_mobile" name="_customize-input-njt_nofi_handle_button_mobile" type="checkbox" <?php if($this->value() == 1) echo('checked')?> data-customize-setting-link="njt_nofi_handle_button_mobile">
                <div class="slider round"></div>
            </label>
          <?php } ?>
        </div>
      <?php
    }
  }

