<?php

namespace DarklupLite\Admin\Field;

/**
 *
 * @package    Darklup - WP Dark Mode
 * @version    1.1.2
 * @author
 * @Websites:
 *
 */

trait Switch_Image_Effects
{

    public static $args;

    public function image_effects_switch_field($args)
    {

        $default = [

            'title' => esc_html__('Switch Field', 'darklup'),
            'sub_title' => esc_html__('This is switch Field', 'darklup'),
            'name' => '',
            'class' => '',
            'condition' => '',
            'auto_off_by_other_switch_on' => '',

        ];

        $args = wp_parse_args($args, $default);

        self::$args = $args;

        self::image_effects_markup();
    }

    public static function image_effects_markup()
    {

        $optionName = self::$optionName;
        $args = self::$args;
        $getData = self::$getOptionData;
        $fieldName = $args['name'];
        $value = !empty($getData[$fieldName]) ? $getData[$fieldName] : '';

        $conditionData = '';
        if (!empty($args['condition'])) {
            $conditionData = json_encode($args['condition']);
        }

        $autoOffByOtherSwitchOn = '';
        if (!empty($args['auto_off_by_other_switch_on'])) {
            $autoOffByOtherSwitchOn = json_encode($args['auto_off_by_other_switch_on']);
        }

        $darkluplite_image_effects = \DarklupLite\Helper::getOptionData('darkluplite_image_effects');
        
        $image_effects = !empty($darkluplite_image_effects) ? $darkluplite_image_effects : 'no';

        ?>
<div class="darkluplite-row <?php echo esc_html($args['class']); ?>  darklup-image-effects-row"
    data-condition="<?php echo esc_html($conditionData); ?>"
    data-auto-off-by="<?php echo esc_html($autoOffByOtherSwitchOn); ?>">
    <div class="darkluplite-col-lg-6 darklup-col-md-12">
        <div class="darkluplite-single-settings-inner">
            <div class="details">
                <h5><?php echo esc_html($args['title']); ?></h5>
                <?php
if (!empty($args['sub_title'])) {
            echo '<p>' . esc_html($args['sub_title']) . '</p>';
        }
        ?>
            </div>
            <div class="on-off-toggle button-switch">
                <input class="on-off-toggle__input <?php echo esc_attr($fieldName); ?>  image-effects-on-off"
                    name="<?php echo esc_attr($optionName) . '[' . $fieldName . ']'; ?>" value="yes" type="checkbox"
                    <?php checked($value, 'yes');?> id="darkluplite_<?php echo esc_attr($fieldName); ?>" />
                <label for="darkluplite_<?php echo esc_attr($fieldName); ?>" class="on-off-toggle__slider"></label>
            </div>
        </div>

    </div>

    <div class="darkluplite-col-lg-6 darkluplite-col-md-12">
        <div class="darkluplite-image-preview-inner" data-settings="<?php echo esc_attr($image_effects); ?>">
            <div class="details">
                <h5><?php echo esc_html__('Preview Image Effects', 'darklup'); ?></h5>
                <p><?php echo esc_html__('  Slide to preview image effect .', 'darklup'); ?></p>
            </div>

            <div class="darkluplite-image-effects-preview">
                <img src="<?php echo DARKLUPLITE_DIR_URL . 'assets/img/image-effects.jpg'; ?>" alt="">
            </div>
        </div>
    </div>

</div>
<?php
}
}