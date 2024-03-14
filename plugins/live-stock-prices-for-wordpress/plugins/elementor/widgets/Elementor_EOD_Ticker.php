<?php


use Elementor\Plugin;

class Elementor_EOD_Ticker extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'eod_ticker';
    }
    public function get_title()
    {
        return __('Stock Prices Ticker', 'eod-stock-prices');
    }
    public function get_icon() {
        return 'eicon-counter';
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
        $this->add_control(
            'type',
            [
                'type'      => \Elementor\Controls_Manager::SELECT,
                'label'     => __('Ticker type:', 'eod_stock_prices'),
                'default'   => 'historical',
                'options'   => [
                    'historical'    => __('historical', 'eod_stock_prices'),
                    'live'          => __('live', 'eod_stock_prices'),
                    'realtime'      => __('realtime', 'eod_stock_prices'),
                ],
                'description' => __('historical', 'eod_stock_prices').' - when loading the page, the user receives up-to-date data for the last day<br>'.
                                 __('live', 'eod_stock_prices').' - when loading the page, the user receives up-to-date data for the last 15 minutes<br>'.
                                 __('realtime', 'eod_stock_prices').' - user get real-time data, the element updates it on its own',
            ]
        );
        $this->add_control(
            'name',
            [
                'type'      => \Elementor\Controls_Manager::SELECT,
                'label'     => __('Display name:', 'eod_stock_prices'),
                'default'   => 'code',
                'options'   => [
                    'code'      => __('code', 'eod_stock_prices'),
                    'name'      => __('name', 'eod_stock_prices'),
                ],
                'description' => __('For each ticker, you can specify a custom name in the context settings below.', 'eod_stock_prices'),
            ]
        );
        $this->add_control(
            'target',
            [
                'label'     => __('Target(s):', 'eod_stock_prices'),
                'type'      => 'smart_select',
                'with_settings' => true,
                'class' => 'multiple',
            ]
        );
    }

    public function get_script_depends() {
        return [ 'eod_stock-prices-plugin' ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $template = $settings['type'] === 'realtime' ? 'realtime_ticker.php' : 'ticker.php';
        $widget_html = eod_load_template(
            "widget/template/ticker-widget.php",
            array(
                'args'               => [],
                'type'               => 'eod_'.$settings['type'],
                'display_name'       => $settings['name'],
                'shortcode_template' => $template,
                'title'              => '',
                'list_of_targets'    => eod_get_ticker_list_from_widget_instance($settings),
            )
        );
        echo $widget_html;

        /**
         * Run JS while editing
         */
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() ){
            $js_init_function = [
                'historical' => 'eod_display_all_historical_tickers();',
                'live' => 'eod_display_all_live_tickers();',
                'realtime' => 'eod_init_realtime_tickers(); eod_display_all_live_tickers();',
            ];
            echo "<script>{$js_init_function[$settings['type']]}</script>";
        }
    }
}