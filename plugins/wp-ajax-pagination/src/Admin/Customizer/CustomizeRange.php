<?php
namespace AjaxPagination\Admin\Customizer;


if (class_exists('WP_Customize_Control')) {
    class CustomizeRange extends \WP_Customize_Control
    {
        public $type = 'range';

        public function __construct($manager, $id, $args = array())
        {
            parent::__construct($manager, $id, $args);
            $defaults = array(
                'min' => 0,
                'max' => 10,
                'step' => 1
            );
            $args = wp_parse_args($args, $defaults);

            $this->min = $args['min'];
            $this->max = $args['max'];
            $this->step = $args['step'];
        }

        public function render_content()
        {
            ?>
            <label class="ajax-pagination">
                <span class="customize-control-title"><?php echo esc_html($this->label); ?></span>
                <input class='wpap-range-slider' min="<?php echo $this->min; ?>" max="<?php echo $this->max; ?>"
                       step="<?php echo $this->step; ?>" type='range' <?php $this->link(); ?>
                       value="<?php echo esc_attr($this->value()); ?>"
                       oninput="jQuery(this).next('input').val( jQuery(this).val() )">
                <input class='range-slider-value' onKeyUp="jQuery(this).prev('input').val( jQuery(this).val() )"
                       step="<?php echo $this->step; ?>"
                       type='number' <?php $this->link(); ?> value='<?php echo esc_attr($this->value()); ?>'>

            </label>

            <?php
        }
    }
}
