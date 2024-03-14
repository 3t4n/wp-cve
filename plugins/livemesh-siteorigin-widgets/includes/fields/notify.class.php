<?php

/**
 * Class LSOW_Custom_Field_Notify
 */
class LSOW_Custom_Field_Notify extends SiteOrigin_Widget_Field_Text_Input_Base {

    /**
     * My custom property for doing custom things.
     *
     * @access protected
     * @var mixed
     */
    protected $custom_label;

    protected function render_field($value, $instance) {
        if ( empty( $this->custom_label ) ) {
            return;
        }
        ?>
        <div
                name="<?php echo esc_attr($this->element_name); ?>"
                id="<?php echo esc_attr($this->element_id); ?>"
            <?php $this->render_CSS_classes($this->get_input_classes()); ?>><?php echo wp_kses_post($this->custom_label); ?></div>
        <?php
    }

    /* Do not render label */
    protected function render_field_label( $value, $instance ) {
    }

    protected function sanitize_field_input( $value, $instance ) {
        return;
    }

    protected function get_input_classes() {
        $input_classes = parent::get_input_classes();
        $input_classes[] = 'lsow-widget-input-notify';
        return $input_classes;
    }

    public function enqueue_scripts() {
        wp_enqueue_style('lsow-notify-css', plugin_dir_url(__FILE__) . 'css/notify.css', array(), LSOW_VERSION, false);
    }
}
