<?php


class Elementor_EOD_Converter extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'eod_converter';
    }
    public function get_title()
    {
        return __('Currency Converter', 'eod-stock-prices');
    }
    public function get_icon() {
        return 'eicon-grow';
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

        // select currency
        $this->add_control(
            'first_currency',
            [
                'type'      => 'smart_select',
                'label'     => __('First currency:', 'eod_stock_prices'),
                'description' => 'The main currency to be converted. Will be on the left.',
                'placeholder' => 'search currency',
                'class' => 'first_currency currency',
            ]
        );

        $this->add_control(
            'amount',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label' => __('Amount of first currency:', 'eod_stock_prices'),
                'min' => 1,
                'default' => 1,
            ]
        );

        $this->add_control(
            'second_currency',
            [
                'type'      => 'smart_select',
                'label'     => __('Second currency:', 'eod_stock_prices'),
                'description' => 'The second currency, the amount of which will need to be calculated.',
                'placeholder' => 'search currency',
                'class' => 'second_currency currency',
            ]
        );

        $this->add_control(
            'changeable',
            [
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label' => __('Ability for users to change currency:', 'eod_stock_prices'),
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'whitelist',
            [
                'type'      => 'smart_select',
                'condition' => [ 'changeable' => 'yes' ],
                'label'     => __('Currency whitelist', 'eod_stock_prices').':',
                'description' => 'You can limit the list of currencies available for changing.',
                'placeholder' => 'search currency',
                'class' => 'whitelist currency multiple',
            ]
        );
    }

    public function get_script_depends() {
        return [ 'eod_stock-prices-plugin' ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_html = eod_load_template(
            "widget/template/converter-widget.php",
            array(
                'args'               => [],
                'props'              => array(
                    'target'            => $settings['first_currency'].':'.$settings['second_currency'],
                    'whitelist'         => $settings['whitelist'],
                    'amount'            => $settings['amount'],
                    'changeable'        => $settings['changeable'] === 'yes' ? '1' : '0',
                ),
            )
        );
        echo $widget_html;

        /**
         * Run JS while editing
         */
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() ){
            echo "<script>eod_display_converters();</script>";
        }
    }
}