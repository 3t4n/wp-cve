<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlContentMobile extends \WP_Customize_Control 
  {
    public $type = 'njtContentMobile';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <label class="njt-content-mobile-button-switch njt-nofi-button-switch" for="_customize-input-njt_nofi_content_mobile">
                <input id="_customize-input-njt_nofi_content_mobile" name="_customize-input-njt_nofi_content_mobile" type="checkbox" <?php if($this->value() == 1) echo('checked')?> data-customize-setting-link="njt_nofi_content_mobile">
                <div class="slider round"></div>
            </label>
          <?php } ?>
        </div>
      <?php
    }
  }

