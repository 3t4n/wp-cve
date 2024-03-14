<?php
namespace NjtNotificationBar\NotificationBar;

defined('ABSPATH') || exit;

  class WpCustomControlColorPreset extends \WP_Customize_Control 
  {
    public $type = 'njtColorText';
    public function render_content() {
      ?>
        <div class="simple-notice-custom-control">
          <?php if( !empty( $this->label ) ) { ?>
              <span id="nj_color_select_presets">
                  <div class="customize-control-title"><?php echo esc_html( $this->label ); ?></div>
                  <div class="nj-list-prese-color">
                    <!-- data-value: background, button, text background, text button -->
                    <div class="type-circle type-circle-1 <?php if($this->value() == 1) echo('type-circle-active')?>" data-type="1" data-value="#9af4cf,#1919cf,#1919cf,#ffffff"></div>
                    <div class="type-circle type-circle-2 <?php if($this->value() == 2) echo('type-circle-active')?>" data-type="2" data-value="#fff799,#1919cf,#e65100,#ffffff"></div>
                    <div class="type-circle type-circle-3 <?php if($this->value() == 3) echo('type-circle-active')?>" data-type="3" data-value="#212121,#dd2c00,#ffffff,#ffffff"></div>
                    <div class="type-circle type-circle-4 <?php if($this->value() == 4) echo('type-circle-active')?>" data-type="4" data-value="#ffffff,#212121,#212121,#ffffff"></div>
                    <div class="type-circle type-circle-5 <?php if($this->value() == 5) echo('type-circle-active')?>" data-type="5" data-value="#d50000,#43a047,#ffffff,#ffffff"></div>
                    <div class="type-circle type-circle-6 <?php if($this->value() == 6) echo('type-circle-active')?>" data-type="6" data-value="#2962ff,#ffffff,#ffffff,#0288D1"></div>
                    <div class="type-circle type-circle-7 <?php if($this->value() == 7) echo('type-circle-active')?>" data-type="7" data-value="#18ffff,#ffffff,#1919cf,#1976D2"></div>
                    <div class="type-circle type-circle-8 <?php if($this->value() == 8) echo('type-circle-active')?>" data-type="8" data-value="#78909c,#ff5722,#ffffff,#ffffff"></div>
                  </div>
                  <input id="_customize-input-njt_nofi_preset_color" class="njt_nofi_none" type="number" value="<?php echo esc_html($this->settings['default']->default); ?>" data-customize-setting-link="njt_nofi_preset_color">
              </span>
          <?php } ?>
          <?php if( !empty( $this->description ) ) { ?>
              <span class="customize-control-description"><?php echo wp_kses_post( $this->description ); ?></span>
          <?php } ?>
        </div>
      <?php
    }
  }

