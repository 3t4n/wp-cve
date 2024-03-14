<?php


class Elementor_EOD_News extends \Elementor\Widget_Base
{
    public function get_name()
    {
        return 'eod_news';
    }
    public function get_title()
    {
        return __('Financial news', 'eod-stock-prices');
    }
    public function get_icon() {
        return 'eicon-single-post';
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
                'label'     => __('News selection:', 'eod_stock_prices'),
                'default'   => 'topic',
                'options'   => [
                    'ticker'    => __('ticker', 'eod_stock_prices'),
                    'topic'     => __('topic', 'eod_stock_prices'),
                ],
            ]
        );

        // select ticker target
        $this->add_control(
            'target',
            [
                'type'      => 'smart_select',
                'condition' => [ 'type' => 'ticker' ],
                'label'     => __('Target:', 'eod_stock_prices'),
                'class'     => 'common_api_search multiple',
            ]
        );

        // select topic
        $topics = [];
        foreach (EOD_API::get_news_topics() as $tag){
            $topics[$tag] = $tag;
        }
        $this->add_control(
            'topic',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'condition' => [ 'type' => 'topic' ],
                'label' => __('Topic', 'eod_stock_prices'),
                'default' => '',
                'options' => $topics,
                'description' => 'We have more than 50 tags to get news for a given topic, this list is expanding.',
            ]
        );

        $this->add_control(
            'limit',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label' => __('Limit:', 'eod_stock_prices'),
                'placeholder' => __('from', 'eod_stock_prices'),
                'min' => 0,
                'max' => 1000,
                'default' => 50,
            ]
        );
        $this->add_control(
            'pagination',
            [
                'type' => \Elementor\Controls_Manager::NUMBER,
                'label' => __('Pagination:', 'eod_stock_prices'),
                'placeholder' => __('from', 'eod_stock_prices'),
                'min' => 1,
                'default' => 0,
                'description' => 'The number of news items per page. Default 0 disables pagination.',
            ]
        );

        // time interval
        $this->add_control(
            'from',
            [
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'label' => __('Time interval:', 'eod_stock_prices'),
                'label_block' => true,
                'placeholder' => __('from', 'eod_stock_prices'),
                'picker_options' => ['enableTime'=>false],
            ]
        );
        $this->add_control(
            'to',
            [
                'type' => \Elementor\Controls_Manager::DATE_TIME,
                'show_label' => false,
                'label_block' => true,
                'placeholder' => __('to', 'eod_stock_prices'),
                'picker_options' => ['enableTime'=>false],
            ]
        );
    }

    public function get_script_depends() {
        return [ 'eod_stock-prices-plugin' ];
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $widget_html = eod_load_template(
            "widget/template/news-widget.php",
            array(
                'args'               => [],
                'props'              => array(
                    'target'             => $settings['target'],
                    'tag'                => $settings['topic'],
                    'limit'              => $settings['limit'],
                    'pagination'         => $settings['pagination'],
                    'from'               => $settings['from'],
                    'to'                 => $settings['to'],
                ),
            )
        );
        echo $widget_html;

        /**
         * Run JS while editing
         */
        if( \Elementor\Plugin::$instance->editor->is_edit_mode() ){
            echo "<script>eod_display_news();</script>";
        }
    }
}