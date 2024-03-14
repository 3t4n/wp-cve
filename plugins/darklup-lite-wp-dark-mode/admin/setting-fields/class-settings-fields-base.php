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

abstract class Settings_Fields_Base
{

    public static $optionName;

    public static $getOptionData;

    use \DarklupLite\Admin\Field\CSS_Editor;
    use \DarklupLite\Admin\Field\Image_Radio_Button;
    use \DarklupLite\Admin\Field\Color_Scheme_Button;
    use \DarklupLite\Admin\Field\Select;
    use \DarklupLite\Admin\Field\Switcher;
    use \DarklupLite\Admin\Field\Text_Area;
    use \DarklupLite\Admin\Field\Text;
    use \DarklupLite\Admin\Field\Color_Picker;
    use \DarklupLite\Admin\Field\Multiple_Selectbox;
    use \DarklupLite\Admin\Field\Number;
    use \DarklupLite\Admin\Field\Switch_Margin;
    use \DarklupLite\Admin\Field\Image_Repeater;
    use \DarklupLite\Admin\Field\Media_Upload;
    use \DarklupLite\Admin\Field\Choose_Radio_Buttons;
    use \DarklupLite\Admin\Field\Switch_Image_Effects;
    use \DarklupLite\Admin\Field\Slider;

    public function __construct()
    {

        self::$optionName = $this->get_option_name();
        self::$getOptionData = get_option(self::$optionName);

        $this->tab_setting_fields();

    }

    public function get_option_name()
    {}
    public function tab_setting_fields()
    {}

    public function start_fields_section($args)
    {

        $default = [
            'title' => esc_html__('Title goes here', 'darklup-lite'),
            'class' => '',
            'icon' => '',
            'dark_icon' => '',
            'id' => '',
            'display' => 'none',
        ];

        $args = wp_parse_args($args, $default);

        ?>
<div class="<?php echo esc_attr($args['class']); ?>" id="<?php echo esc_attr($args['id']); ?>"
    style="display:<?php echo esc_attr($args['display']); ?>;">
    <div class="darkluplite-section-title">
        <div class="darkluplite-row">
            <div class="darkluplite-col-sm-8 darkluplite-col-12 darkluplite-item-center">
                <div class="darkluplite-title-icon">
                    <img class="admin-light-icon" src="<?php echo esc_url($args['icon']); ?>"
                        alt="<?php echo esc_attr($args['title']); ?>" />
                    <img class="admin-dark-icon" src="<?php echo esc_url($args['dark_icon']); ?>"
                        alt="<?php echo esc_attr($args['title']); ?>" />
                </div>
                <h3 class="title"><?php echo esc_html($args['title']); ?></h3>
            </div>
            <?php
        // Settings save button
        require DARKLUPLITE_DIR_ADMIN . 'admin-templates/template-save-button.php';
        ?>
        </div>
    </div>
    <?php
}

    public function end_fields_section()
    {
        echo '</div>';
    }

}