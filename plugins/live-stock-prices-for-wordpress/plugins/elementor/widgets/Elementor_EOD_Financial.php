<?php


class Elementor_EOD_Financial extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'eod_financial';
    }
    public function get_title()
    {
        return __('Financial Table', 'eod-stock-prices');
    }
    public function get_icon() {
        return 'eicon-table';
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
            'post_type' => 'financials',
            'post_status' => 'publish',
            'numberposts' => -1
        ]);
        $preset_options = [];
        foreach ($fd_presets as $fd_preset){
            $preset_type = str_replace('->', ' - ', get_post_meta($fd_preset->ID, '_financial_group', true));
            $preset_options[(string)$fd_preset->ID] = "$fd_preset->post_title ($preset_type)";
        }
        $this->add_control(
            'preset',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'label' => __('Data preset:', 'eod_stock_prices'),
                'default' => '',
                'options' => $preset_options,
                'description' => 'The preset defines the list of data that will be displayed. You can create it on the page <a target="_blank" href="' .get_admin_url() .'edit.php?post_type=financials">Financials Table presets</a>.',
            ]
        );
        $this->add_control(
            'target',
            [
                'label'         => __('Target:', 'eod_stock_prices'),
                'type'          => 'smart_select',
                'class'         => 'common_api_search',
            ]
        );
        $this->add_control(
            'year_from',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label' => __('Years interval:', 'eod_stock_prices'),
                'label_block' => true,
                'placeholder' => __('from', 'eod_stock_prices'),
                'default' => '',
            ]
        );
        $this->add_control(
            'year_to',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'show_label' => false,
                'label_block' => true,
                'placeholder' => __('to', 'eod_stock_prices'),
                'default' => '',
            ]
        );
    }

    public function get_script_depends() {
        return [ 'eod_stock-prices-plugin' ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $years = '';
        if( $settings['year_from'] || $settings['year_to'] )
            $years = $settings['year_from'] . '-' . $settings['year_to'];
        $widget_html = eod_load_template(
            "widget/template/financial-widget.php",
            array(
                'args'     => [],
                'fd'       => new EOD_Financial( $settings['preset'] ),
                'target'   => $settings['target'],
                'years'    => $years,
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