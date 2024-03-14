<?php


class Elementor_Smart_Select extends \Elementor\Base_Data_Control
{
    public function enqueue() {
        // Styles
        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style( 'eod_stock_admin_css', EOD_URL. 'admin/css/eod-stock-prices-admin.css', array(), EOD_VER );

        // Scripts
        wp_enqueue_script( 'wp-color-picker');
        wp_enqueue_script( 'eod-admin', EOD_URL . 'admin/js/eod-admin.js', array('jquery','wp-color-picker'), EOD_VER );
        wp_enqueue_script( 'eod_stock_widget_js',EOD_URL . 'admin/js/eod-widget-form.js', array('eod-admin'), EOD_VER, true);

        // Add ajax vars
        wp_add_inline_script( 'eod-admin', 'let eod_ajax_nonce = "'.wp_create_nonce('eod_ajax_nonce').'", eod_ajax_url = "'.admin_url('admin-ajax.php').'";', 'before' );

        // Add display vars
        wp_localize_script( 'eod-admin', 'eod_display_settings', EOD_Stock_Prices_Plugin::get_js_display_settings());
        wp_localize_script( 'eod-admin', 'eod_service_data', EOD_Stock_Prices_Plugin::get_js_service_data());
    }

    public function get_type()
    {
        return 'smart_select';
    }

    public function get_default_value()
    {
        return '';
    }

    public function content_template()
    {
        $control_uid = $this->get_control_uid();
        ?>
        <label for="<?= $control_uid ?>" class="elementor-control-title">
            {{ data.label }}
        </label>
        <div class="elementor-control-input-wrapper">
            <div class="eod_search_box <# if (data.multiple) { #>multiple<# } #> {{ data.class }}">
                <input class="eod_search_widget_input" type="text" autocomplete="off"
                        placeholder="<# if (data.placeholder) { #> {{ data.placeholder }} <# }else{ #> Find ticker by code or company name <# } #>"
                        <# if (data.stock_type) { #> data-stock-type="{{data.stock_type}}" <# } #>>
            </div>
            <input type="hidden" id="<?= $control_uid ?>" class="<# if (data.with_settings) { #> target_list json <# }else{ #> storage <# } #>"
                   data-setting="{{ data.name }}"
                   value="{{ data.value }}" />
            <# if (data.description) { #>
            <span>{{ data.description }}</span>
            <# } #>
        </div>

        <?php
    }


}