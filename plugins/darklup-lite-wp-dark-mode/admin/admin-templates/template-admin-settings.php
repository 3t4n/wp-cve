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

class Admin_Settings_Tab extends Settings_Fields_Base
{

    public function get_option_name()
    {
        return 'darkluplite_settings'; // set option name it will be same or different name
    }

    public function tab_setting_fields()
    {

        $this->start_fields_section([

            'title' => esc_html__('DASHBOARD COLORS', 'darklup-lite'),
            'class' => 'darkluplite-admin-settings darkluplite-d-hide darkluplite-settings-content',
            'icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/color.svg'),
            'dark_icon' => esc_url(DARKLUPLITE_DIR_URL . 'assets/img/color-white.svg'),
            'id' => 'darkluplite_admin_settings',
        ]);
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
            'title' => esc_html__('Dashboard Darkmode Color Preset', 'darklup-lite'),
            'sub_title' => esc_html__('Select the admin dashboard darkmode color.', 'darklup-lite'),
            'class' => 'settings-color-preset dashboard-dark--presets',
            'name' => 'admin_color_preset',
            'options_title' => $preset_image_titles,
            'options' => $preset_images,
        ]);
        ?>
<div class="darkluplite-row darkluplite-section--header">
    <h3>Preset Color Customization</h3>
    <p>Customize the preset colors whatever you want.</p>
</div>

<?php
        $this->color_field([
            'title' => esc_html__( 'Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Select the primary background color of your website when dark mode is enabled.', 'darklup-lite' ),
            'name' => 'admin_custom_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Secondary Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom background color.', 'darklup-lite' ),
            
            'name' => 'admin_custom_secondary_bg_color'
          ]);
        $this->color_field([
            'title' => esc_html__( 'Tertiary Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom background color.', 'darklup-lite' ),
            
            'name' => 'admin_custom_tertiary_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom text color.', 'darklup-lite' ),
            'name' => 'admin_custom_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link color.', 'darklup-lite' ),
            'name' => 'admin_custom_link_color',
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
          ]);
          $this->color_field([
            'title' => esc_html__( 'Link Hover Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom link hover color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_link_hover_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Border Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom border color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_border_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button background Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_btn_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Button Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_btn_text_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Background Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_input_bg_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field Placeholder Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_input_place_color'
          ]);
          $this->color_field([
            'title' => esc_html__( 'Input Field  Text Color', 'darklup-lite' ),
            'sub_title' => esc_html__( 'Set custom button text Color.', 'darklup-lite' ),
            'is_pro' => 'yes',
            'wrapper_class' => 'pro-feature',
            'name' => 'admin_custom_input_text_color'
          ]);

        $this->end_fields_section(); // End fields section

    }

}

new Admin_Settings_Tab();