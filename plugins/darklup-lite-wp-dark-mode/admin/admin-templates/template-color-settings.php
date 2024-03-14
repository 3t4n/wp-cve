<?php
namespace DarklupLite\Admin;

/**
 *
 * @package    DarklupLite - WP Dark Mode
 * @version    1.0.0
 * @author
 * @Websites:
 *
 */

class Color_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([

            'title' => esc_html__('COLOR SETTINGS', 'darklup-lite'),
            'class' => 'darkluplite-color-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/color.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/color-white.svg'),
            'id' => 'darkluplite_color_settings',

        ]);


      //   $switch_cases = [
      //     'front_end_colors' => 'Front End Colors',
      //     'dashoard_colors' => 'Admin Dashboard Colors',
      // ];

      $color_modes = [
        // 'darklup_dynamic' => 'Dynamic',
        'darklup_dynamic' => 'Dynamic',
        'darklup_presets' => 'Presets',
      ];

      $this->button_radio_field([
          'title' => esc_html__( 'Choose Your Color Mode', 'darklup' ),
          // 'sub_title' => esc_html__( 'Select the front-end darkmode color.', 'darklup' ),
          'class' => 'settings-color-preset',
          'name' => 'color_modes',
          'options' => $color_modes,
          'default' => 'darklup_dynamic',
      ]);
      
      $switch_cases = [
        // 'darklup_dynamic' => 'Dynamic',
        'front_end_colors' => 'Front End Color Presets',
        'dashoard_colors' => 'Admin Dashboard Color Presets',
      ];
      $this->button_radio_field([
          'class' => 'settings-color-preset',
          'name' => 'full_color_settings',
          'options' => $switch_cases,
          'default' => 'front_end_colors',
          'condition' => ["key" => "color_modes", "value" => "darklup_presets"],
      ]);

      ?>

<div class="darkluplite-row  darkluplite-dynamic-color" data-condition='{"key":"color_modes","value":"darklup_dynamic"}'>
    <div class="darkluplite-col-lg-12 darkluplite-col-md-12 darkluplite-col-12 align-self-center">
        <div class="darkluplite-single-about">
            <div class="details">
                <h3><span class="dashicons dashicons-info-outline important-note--icon"></span><?php esc_html_e('About Dynamic Dark Mode', 'darklup-lite');?>
                </h3>
                <p class="darkluplite-welcome--notice">
                    <?php esc_html_e("With the help of the dynamic dark mode option, you can easily enable the dark mode feature on your website without doing any complex configuration. Darklup Dark Mode utilizes an intelligent, dynamic algorithm to effortlessly generate stunning dark mode color schemes for your website.", 'darklup-lite')?>
                </p>
            </div>
        </div>

    </div>

</div>


<div class="darkluplite-presets-customization-wrap darkluplite-dynamic-color-level" data-condition='{"key":"color_modes","value":"darklup_dynamic"}'>
    <div class="darkluplite-row darkluplite-section--header">
        <h3>Dark Mode Intensity (Default: 80)</h3>
        <p>Adjust the dark mode intensity for your website by selecting a desired level. The website background will
            become darker as you increase the value.<br> At 100%, the background color will be completely dark.
            Implementing this adjustment can significantly enhance the visual aesthetics of your website.<br>
            Surprisingly, you may not need to replace any existing images on your website to look good in dark mode.</p>
    </div>

    <?php

      $this->range_slider([
        'title' => esc_html__( 'Value', 'darklup' ),
        // 'sub_title' => esc_html__( 'Adjust the dark mode intensity for your website by selecting a desired level. The website background will become darker as you increase the value. At 100%, the background color will be completely dark.', 'darklup' ),
        'sub_title' => esc_html__( '', 'darklup' ),
        // 'condition' => ["key" => "full_color_settings", "value" => "darklup_dynamic"],
        'condition' => ["key" => "color_modes", "value" => "darklup_dynamic"],
        'default_value' => '85',
        'class' => 'settings-slider',
        'name'  => 'darkmode_level',
        'step'  => '1',
        'max'   => '100',
        'min'   => '65',
        'is_pro' => 'yes',
        'wrapper_class' => 'pro-feature',
      ]);
?>
</div>

<div class="darkluplite-presets-customization-wrap darkluplite-dynamic-color-level darkluplite-mt-20" data-condition='{"key":"color_modes","value":"darklup_dynamic"}'>
        <div class="darkluplite-row darkluplite-section--header">
            <h3>Branding Color Intensity (Default: 10)</h3>
            <p>Dynamic Dark mode will preserve branding colors (Ex: Colors like red, green, yellow, etc). By default, branding colors will be 10% darker.<br> You can also select a desired level to adjust the dark mode intensity for branding colors. The colors will become darker as you increase the value. At 100%, the background colors will be completely dark.</p>
        </div>

        <?php

          $this->range_slider([
            'title' => esc_html__( 'Value', 'darklup' ),
            // 'sub_title' => esc_html__( 'Adjust the dark mode intensity for your website by selecting a desired level. The website background will become darker as you increase the value. At 100%, the background color will be completely dark.', 'darklup' ),
            'sub_title' => esc_html__( '', 'darklup' ),
            'condition' => ["key" => "color_modes", "value" => "darklup_dynamic"],
            'default_value' => '10',
            'class' => 'settings-slider',
            'name'  => 'branding_darkmode_level',
            'step'  => '1',
            'max'   => '100',
            'min'   => '0',
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',    
          ]);

    ?>
    </div> 

<?php

        
        $preset_image_titles = ['1' => 'Default', '2' => 'Blue', '3' => 'Orange', '4' => 'Bird Flower', '5' => 'Dim Light',
        '6' => 'Light Green', '7' => 'Bright Ube', '8' => 'Blush Pink', '9' => 'Generic Green', '10' => 'Facebook', '11' => 'Twitter Lights', '12' => 'Twitter Dim'];
        $preset_images = [
            '1' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Default.svg',
            '2' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Blue.svg',
            '3' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Orange.svg',
            '4' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Bird-Flower.svg',
            '5' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Dim-Light.svg',
            '6' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Light-Green.svg',
            '7' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Bright-Ube.svg',
            '8' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Blush-Pink.svg',
            '9' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Generic-Green.svg',
            '10' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Facebook.svg',
            '11' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Twitter-Lights-Out.svg',
            '12' => DARKLUPLITE_DIR_ADMIN_ASSETS_URL . 'img/Twitter-Dim.svg',
        ];
        $this->color_scheme_radio_field([
            'title' => esc_html__('Front-End Darkmode Color Preset', 'darklup-lite'),
            'sub_title' => esc_html__('Select the front-end darkmode color.', 'darklup-lite'),
            'class' => 'settings-color-preset front-end-dark--presets',
            'name' => 'color_preset',
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'extra_cond' => ["key" => "color_modes", "value" => "darklup_presets"],
            'options_title' => $preset_image_titles,
            'options' => $preset_images,
        ]);


        $this->color_scheme_radio_field([
          'title' => esc_html__('Dashboard Darkmode Color Preset', 'darklup-lite'),
          'sub_title' => esc_html__('Select the admin dashboard darkmode color.', 'darklup-lite'),
          'class' => 'settings-color-preset dashboard-dark--presets',
          'name' => 'admin_color_preset',
          'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
          'extra_cond' => ["key" => "color_modes", "value" => "darklup_presets"],
          'options_title' => $preset_image_titles,
          'options' => $preset_images,
      ]);


      ?>
      
          <!-- Dynamic mode Colors -->
    <div class="darkluplite-presets-customization-wrap darkluplite-mt-20" data-condition='{"key":"color_modes","value":"darklup_dynamic"}'>
        <div class="darkluplite-row darkluplite-section--header">
            <h3>Dynamic Mode Color Customization</h3>
            <p>Customize colors whatever you want in the Dynamic mode.</p>
        </div>

        <?php

          $this->color_field([
            'title' => esc_html__( 'Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom text color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_link_color',
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Hover Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link hover color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_link_hover_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Border Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom border color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_border_color'
          ]);

          $this->color_field([
            'title' => esc_html__( 'Button Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button background Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_btn_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_btn_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'dynamic_custom_input_bg_color'
          ]);
          
          ?>
    </div>
      
      
      
      
<div class="darkluplite-presets-customization-wrap" data-condition='{"key":"full_color_settings","value":"front_end_colors"}' data-extra_condition='{"key":"color_modes","value":"darklup_presets"}'>
    <div class="darkluplite-row darkluplite-section--header">
        <h3>Preset Color Customization</h3>
        <p>Customize front-end the preset colors whatever you want.</p>
    </div>

    <?php
        $this->color_field([
            'title' => esc_html__( 'Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Select the primary background color of your website when dark mode is enabled.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'name' => 'custom_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Secondary Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom background color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            
            'name' => 'custom_secondary_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Tertiary Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom background color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            
            'name' => 'custom_tertiary_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom text color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'name' => 'custom_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link color.', 'darklup-lite' ),
            'name' => 'custom_link_color',
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Hover Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link hover color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_link_hover_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Border Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom border color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_border_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button background Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_btn_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_btn_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_input_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Placeholder Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_input_place_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field  Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "front_end_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'custom_input_text_color'
          ]);

          ?>
</div>
<?php
          
                // Admin Colors
          
                ?>
<div class="darkluplite-presets-customization-wrap" data-condition='{"key":"full_color_settings","value":"dashoard_colors"}' data-extra_condition='{"key":"color_modes","value":"darklup_presets"}'>
    <div class="darkluplite-row darkluplite-section--header">
        <h3>Preset Color Customization</h3>
        <p>Customize the admin dashoard preset colors whatever you want.</p>
    </div>
    <?php


          $this->color_field([
            'title' => esc_html__( 'Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Select the primary background color of your website when dark mode is enabled.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'name' => 'admin_custom_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Secondary Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom background color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            
            'name' => 'admin_custom_secondary_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Tertiary Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom background color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            
            'name' => 'admin_custom_tertiary_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom text color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'name' => 'admin_custom_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'name' => 'admin_custom_link_color',
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Hover Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link hover color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_link_hover_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Border Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom border color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_border_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button background Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_btn_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_btn_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_input_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Placeholder Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_input_place_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field  Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'condition' => ["key" => "full_color_settings", "value" => "dashoard_colors"],
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_input_text_color'
          ]);

          ?>
</div>
<?php
        
        $this->end_fields_section(); // End fields section

    }

}

new Color_Settings_Tab();