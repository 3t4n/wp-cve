<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlPositionType extends \WP_Customize_Control 
  {
    public $type = 'njtPositionType';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
            <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
            <input id="_customize-input-njt_nofi_position_type" type="hidden" value="<?php echo esc_html($this->settings['default']->default); ?>" data-customize-setting-link="njt_nofi_position_type">
            <div class="njt-bt-position-type">
              <button type="button" class="njt-bt-position bt-fixed <?php if($this->value() == 'fixed') echo('active')?>" data-position="fixed"><?php echo esc_html('Fixed');?></button>
              <button type="button" class="njt-bt-position bt-absolute <?php if($this->value() == 'absolute') echo('active')?>" data-position="absolute"><?php echo esc_html('Absolute');?></button>
            </div>
          <?php } ?>
        </div>
      <?php
    }
  }

