<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlTextColorLb extends \WP_Customize_Control 
  {
    public $type = 'njtTextColorLb';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
              <span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
              <span id="nj_text_color_select_lb">
                  <div class="wp-picker-container wp-picker-active nj_color_select_picker">
                      <button type="button" class="button nj_color_button_select_bg wp-color-result wp-picker-open" aria-expanded="true" style="<?php echo esc_html('background-color:'.$this->value()); ?>"><span class="nj-color-result-text wp-color-result-text"><?php echo _e('Select Color', NJT_NOFI_DOMAIN);?></span></button>
                      <span class="wp-picker-input-wrap nj_color_display_picker njt_nofi_none">
                          <label class="nj_color_display_color">
                              <span class="screen-reader-text"><?php echo esc_html( $this->label ); ?></span>
                              <input id="_customize-input-njt_nofi_lb_text_color" class="njt_nofi_dp_none nj-color-wp-color-picker wp-color-picker" type="text"  value="<?php echo esc_html($this->value()); ?>" data-default="<?php echo esc_html($this->value()); ?>" data-customize-setting-link="njt_nofi_lb_text_color">
                            </label>
                          <input type="button" class="button button-small wp-picker-default nj_color_button_select_default" value="<?php echo _e('Default', NJT_NOFI_DOMAIN);?>" aria-label="Select default color" />
                      </span>
                  </div>
              </span>
          <?php } ?>
          <?php if( !empty( $this->description ) ) { ?>
              <span class="customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
          <?php } ?>
        </div>
      <?php
    }
  }

