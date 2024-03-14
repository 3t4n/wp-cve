<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlEnableBar extends \WP_Customize_Control 
  {
    public $type = 'njtEnableBar';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <input id="_customize-input-njt_nofi_enable_bar" type="hidden" value="<?php echo esc_html($this->value()); ?>" data-customize-setting-link="njt_nofi_enable_bar">
            <label class="njt-enable-bar-switch njt-nofi-button-switch" for="njt-enable-bar">
                <input id="njt-enable-bar" name="njt-enable-bar" type="checkbox" <?php if($this->value() == 1) echo('checked')?>>
                <div class="slider round"></div>
            </label>
          <?php } ?>
        </div>
      <?php
    }
  }

