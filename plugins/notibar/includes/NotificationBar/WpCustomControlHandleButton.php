<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlHandleButton extends \WP_Customize_Control 
  {
    public $type = 'njtHandleButton';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <input id="_customize-input-njt_nofi_handle_button" type="hidden" value="<?php echo esc_html($this->settings['default']->default); ?>" data-customize-setting-link="njt_nofi_handle_button">
            <label class="njt-handle-button-switch njt-nofi-button-switch" for="njt-handle-button">
                <input id="njt-handle-button" name="njt-handle-button" type="checkbox" <?php if($this->value() == 1) echo('checked')?>>
                <div class="slider round"></div>
            </label>
          <?php } ?>
        </div>
      <?php
    }
  }

