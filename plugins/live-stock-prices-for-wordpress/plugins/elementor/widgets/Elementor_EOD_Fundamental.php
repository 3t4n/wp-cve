<?php


class Elementor_EOD_Fundamental extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'eod_fundamental';
    }
    public function get_title()
    {
        return __('Fundamental Data', 'eod-stock-prices');
    }
    public function get_icon() {
        return 'eicon-kit-details';
    }
    public function get_custom_help_url() {
        // TODO: add guide
        return get_admin_url().'?page=eod-stock-prices';
    }
    public function get_categories() {
        return ['eodhd'];
    }
    public function get_keywords() {
        return ['eodhd'];
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__( 'Content', 'textdomain' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        // presets
        $fd_presets = get_posts([
            'post_type' => 'fundamental-data',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        $preset_options = [];
        foreach ($fd_presets as $fd_preset){
            $preset_type = str_replace('_', ' ', get_post_meta( $fd_preset->ID,'_fd_type', true ) );
            $preset_options[(string)$fd_preset->ID] = "$fd_preset->post_title ($preset_type)";
        }
        $this->add_control(
            'preset',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => __('Data preset:', 'eod_stock_prices'),
                'default' => '',
                'options' => $preset_options,
                'description' => 'The preset defines the list of data that will be displayed. You can create it on the page <a href="' .get_admin_url(). 'edit.php?post_type=fundamental-data" target="_blank">Fundamental Data presets</a>.',
            ]
        );
        $this->add_control(
            'target',
            [
                'label'     => __('Target:', 'eod_stock_prices'),
                'type'      => 'smart_select',
                'class'     => 'common_api_search',
            ]
        );
    }

    public function get_script_depends() {
        return [ 'eod_stock-prices-plugin' ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_html = eod_load_template(
            "widget/template/fundamental-widget.php",
            array(
                'args'     => [],
                'fd'       => new EOD_Fundamental_Data( $settings['preset'] ),
                'target'   => $settings['target'],
                'title'    => '',
            )
        );
        echo $widget_html;

        /**
         * Run JS while editing
         */
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() ){
            echo "<script>eod_display_fundamental_data()</script>";
        }
    }
}