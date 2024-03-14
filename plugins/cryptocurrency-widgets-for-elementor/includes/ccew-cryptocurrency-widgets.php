<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
use Elementor\Controls_Manager;

class ccew__Widget extends \Elementor\Widget_Base

{

    public function __construct($data = array(), $args = null)
    {
        parent::__construct($data, $args);

        wp_register_style('ccew-card', CCEW_URL . 'assets/css/ccew-card.css', array(), CCEW_VERSION);
        wp_register_style('ccew-label', CCEW_URL . 'assets/css/ccew-label.min.css', array(), CCEW_VERSION);
        wp_register_style('ccew-common-styles', CCEW_URL . 'assets/css/ccew-common-styles.css', array(), CCEW_VERSION);
        wp_register_style('ccew-icons-style', CCEW_URL . 'assets/css/ccew-icons.min.css', array(), CCEW_VERSION);
        wp_register_style('ccew-list-style', CCEW_URL . 'assets/css/ccew-list.css', array(), CCEW_VERSION);
        wp_register_style('ccew-custom-datatable-style', CCEW_URL . 'assets/css/ccew-custom-datatable.css', array(), CCEW_VERSION);
        wp_register_script('ccew-anychart-core', CCEW_URL . 'assets/js/ccew-anychart-core.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-anychart-sparkline', CCEW_URL . 'assets/js/ccew-anychart-sparkline.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-anychart-area', CCEW_URL . 'assets/js/ccew-anychart-area.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('small-chart', CCEW_URL . 'assets/js/small-chart.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-call-ajax', CCEW_URL . 'assets/js/ccew-call-ajax.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-numeral', CCEW_URL . 'assets/js/numeral.min.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-table-sort', CCEW_URL . 'assets/js/tablesort.min.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-datatable', CCEW_URL . 'assets/js/jquery.dataTables.min.js', array('elementor-frontend'), CCEW_VERSION, true);
        wp_register_script('ccew-headFixer', CCEW_URL . 'assets/js/tableHeadFixer.js', array('elementor-frontend'), CCEW_VERSION, true);

    }

    public function get_script_depends()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            return array('small-chart', 'ccew-anychart-core', 'ccew-anychart-area', 'ccew-anychart-sparkline', 'ccew-chart', 'ccew-call-ajax', 'ccew-numeral', 'ccew-table-sort', 'ccew-datatable', 'ccew-headFixer');
        }
        $settings = $this->get_settings_for_display();
        $widget_type = $settings['ccew_widget_type'];
        $script = array('ccew-call-ajax');

        if ($widget_type == 'list' || $widget_type == 'card') {
            array_push($script, 'ccew-anychart-core', 'ccew-anychart-sparkline', 'ccew-chart', 'ccew-anychart-area', 'small-chart');
        } elseif ($widget_type == 'advanced_table') {
            array_push($script, 'ccew-numeral', 'ccew-table-sort', 'ccew-datatable', 'ccew-headFixer');
        }

        return $script;
    }

    public function get_style_depends()
    {
        if (\Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode()) {
            return array('ccew-card', 'ccew-label', 'ccew-icons-style', 'ccew-common-styles', 'ccew-list-style', 'ccew-custom-datatable-style');
        }
        $settings = $this->get_settings_for_display();
        $widget_type = $settings['ccew_widget_type'];
        $styles = array('ccew-icons-style', 'ccew-common-styles');

        if ($widget_type == 'label') {
            array_push($styles, 'ccew-label');
        }
        if ($widget_type == 'list' || $widget_type == 'top_gainer_loser') {
            array_push($styles, 'ccew-list-style');
        } elseif ($widget_type == 'advanced_table') {
            array_push($styles, 'ccew-custom-datatable-style');
        } else {
            array_push($styles, 'ccew-card');
        }
        return $styles;
    }

    public function get_name()
    {
        return 'cryptocurrency-elementor-widget';
    }

    public function get_title()
    {
        return __('Cryptocurrency Widget', 'ccew');
    }

    public function get_icon()
    {
        return 'eicon-price-table ccew-icon';
    }

    public function get_categories()
    {
        return array('ccew');
    }

    protected function register_controls()
    {
        $remember_message = '';

        $api_option = get_option('openexchange-api-settings');
        $api = (isset($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";

        if (empty($api_option['openexchangerate_api'])) {
            $remember_message = "<span style='color:red;'>Remember to add <a href='https://openexchangerates.org/signup/free' target='blank'>Openexchangerates.org</a> free API key for crypto to fiat price conversions.</span><br><span style='color:red;'><a href=" . get_admin_url(null, 'admin.php?page=openexchange-api-settings') . " target='blank'>Click here</a> to enter Open Exchange rates API Key </span>";
        }
        $currencies_arr = array(
            'USD' => 'USD',
            'GBP' => 'GBP',
            'EUR' => 'EUR',
            'INR' => 'INR',
            'JPY' => 'JPY',
            'CNY' => 'CNY',
            'ILS' => 'ILS',
            'KRW' => 'KRW',
            'RUB' => 'RUB',
            'DKK' => 'DKK',
            'PLN' => 'PLN',
            'AUD' => 'AUD',
            'BRL' => 'BRL',
            'MXN' => 'MXN',
            'SEK' => 'SEK',
            'CAD' => 'CAD',
            'HKD' => 'HKD',
            'MYR' => 'MYR',
            'SGD' => 'SGD',
            'CHF' => 'CHF',
            'HUF' => 'HUF',
            'NOK' => 'NOK',
            'THB' => 'THB',
            'CLP' => 'CLP',
            'IDR' => 'IDR',
            'NZD' => 'NZD',
            'TRY' => 'TRY',
            'PHP' => 'PHP',
            'TWD' => 'TWD',
            'CZK' => 'CZK',
            'PKR' => 'PKR',
            'ZAR' => 'ZAR',
        );

        $this->start_controls_section(
            'ccew_general_section',
            array(
                'label' => __('General Settings', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'ccew_widget_type',
            array(
                'label' => __('Widget Type', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'card' => 'Card',
                    'label' => 'Label',
                    'list' => 'List',
                    'top_gainer_loser' => 'Top Gainer/Loser',
                    'advanced_table' => 'Advanced Table',
                ),

                'default' => 'card',
            )
        );
        $this->add_control(
            'ccew_coin_api',
            [
                'label' => esc_html__('Select Api', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::HIDDEN,
                'default' => $api,
            ]
        );

        $this->add_control(
            'ccew_widget_style',
            array(
                'label' => __('Style', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'style-1' => 'Style 1',
                    'style-2' => 'Style 2',
                ),
                'condition' => array(
                    'ccew_widget_type' => 'card',
                ),
                'default' => 'style-1',
            )
        );
        $this->add_control(
            'ccew_gainer_description',
            array(
                'label' => __('Top gainer loser from 200 coins', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::HEADING,
                'show_label' => true,
                'condition' => array(
                    'ccew_widget_type' => 'top_gainer_loser',
                ),
            )
        );

        $this->add_control(
            'ccew_gainer_loser_sortby',
            array(
                'label' => __('List Sort By', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'gainer' => 'Gainer',
                    'loser' => 'Loser',
                ),

                'default' => 'gainer',
                'condition' => array(
                    'ccew_widget_type' => 'top_gainer_loser',
                ),
            )
        );

        $this->add_control(
            'ccew_numberof_coins',
            array(
                'label' => __('Show Coins', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '5' => 'Top 5',
                    '10' => 'Top 10',
                    '20' => 'Top 20',
                    '50' => 'Top 50',
                    '100' => 'Top 100',
                ),
                'default' => '5',
                'condition' => array(
                    'ccew_widget_type' => 'top_gainer_loser',
                ),
            )
        );
        $this->add_control(
            'ccew_all_coins',
            array(
                'label' => __('Show Coins', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    'custom_coin' => 'Custom Coin',
                    '5' => 'Top 5',
                    '10' => 'Top 10',
                    '20' => 'Top 20',
                    '50' => 'Top 50',
                    '100' => 'Top 100',
                    '200' => 'All Coin(200)',
                ),
                'default' => '5',
                'condition' => array(
                    'ccew_widget_type' => array('list', 'advanced_table'),
                ),
            )
        );

        $this->add_control(
            'ccew_pagination',
            array(
                'label' => __('Records Per Page', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => array(
                    '10' => '10',
                    '25' => '25',
                    '50' => '50',
                    '100' => '100',
                ),
                'default' => '10',
                'condition' => array(
                    'ccew_widget_type' => 'advanced_table',
                ),
            )
        );

        $this->add_control(
            'ccew_custom_coin',
            array(
                'label' => __('Select Coin ', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => ccew_get_all_coin_ids(),
                'condition' => array(
                    'ccew_widget_type' => array('list', 'advanced_table'),
                    'ccew_all_coins' => 'custom_coin',
                ),
            )
        );

        $this->add_control(
            'ccew_select_coins',
            array(
                'label' => __('Select Coins', 'ccew'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => ccew_get_all_coin_ids(),
                'default' => (count(ccew_get_all_coin_ids()) > 0) ? 'bitcoin' : 'not',
                'condition' => array(
                    'ccew_widget_type!' => array('list', 'top_gainer_loser', 'advanced_table'),
                ),
            )
        );

        $this->add_control(
            'ccew_fiat_currency',
            array(
                'label' => __('Select Fiat Currency', 'ccew'),
                'description' => $remember_message,
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => false,
                'default' => 'USD',
                'options' => $currencies_arr,
            )
        );

        $this->add_control(
            'ccew_number_formating',
            array(
                'label' => __('Number Formatting', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('On', 'ccew'),
                'label_off' => __('Off', 'ccew'),
                'return_value' => 'on',
                'default' => 'on',
            )
        );

        $this->add_control(
            'ccew_display_coin_symbol',
            array(
                'label' => __('Coin Symbol', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                ),
            )
        );

        $this->add_control(
            'ccew_display_high_low',
            array(
                'label' => __('High/Low', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                    'ccew_coin_api' => 'coin_gecko',

                ),
            )
        );

        $this->add_control(
            'ccew_display_1h_changes',
            array(
                'label' => __('1H change', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                ),
            )
        );

        $this->add_control(
            'ccew_display_24h_changes',
            array(
                'label' => __('24 Hours changes', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'ccew_widget_type',
                            'operator' => '!==',
                            'value' => 'advanced_table',
                        ],
                    ],
                ),
            )
        );

        $this->add_control(
            'ccew_display_7d_changes',
            array(
                'label' => __('7 Days changes', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                ),
            )
        );

        $this->add_control(
            'ccew_display_30d_changes',
            array(
                'label' => __('30 days changes', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                ),
            )
        );
        $this->add_control(
            'ccew_card2_changes',
            array(
                'label' => __('Display Value Changes 24H', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-2',
                ),
            )
        );
        $this->add_control(
            'ccew_display_chart_offset',
            array(
                'label' => __('Chart offset', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-2',
                ),
            )
        );

        $this->add_control(
            'ccew_display_rank',
            array(
                'label' => __('Rank', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                ),
            )
        );

        $this->add_control(
            'ccew_display_marketcap',
            array(
                'label' => __('Market Cap', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => 'card',
                    'ccew_widget_style' => 'style-1',
                ),
            )
        );

        $this->add_control(
            'ccew_display_table_head',
            array(
                'label' => __('Display Table Head', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => array('list'),
                ),
            )
        );
        $this->add_control(
            'ccew_display_graph',
            array(
                'label' => __('Display Graph', 'ccew'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'ccew'),
                'label_off' => __('Hide', 'ccew'),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => array(
                    'ccew_widget_type' => array('list'),
                ),
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ccew_Basic_styles_section',
            array(
                'label' => __('Color Settings', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'ccew_bg_color',
            array(
                'label' => __('Background Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-bg ,
						{{WRAPPER}} div[id*=ccew-wrap] table thead tr th ,
						{{WRAPPER}} div[id*=ccew-wrap] table tbody tr td,
						{{WRAPPER}} div[id*=ccew-wrap] a.paginate_button.next,
						{{WRAPPER}} div[id*=ccew-wrap] a.paginate_button.previous' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .ccew-price-list .ccew-graph-list path' => 'fill: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            'ccew_chart_color',
            array(
                'label' => __('Chart Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                ),
                'default' => "#13D31C9E",
            )
        );
        $this->add_control(
            'ccew_chart_border_color',
            array(
                'label' => __('Chart Border Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                ),
                'default' => "#13D31C9E",
            )
        );
        $this->add_control(
            'ccew_card_coin_color',
            array(
                'label' => __('Coin Name Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper.style-2 .ccew-card-content .ccew-card-coin' => 'color: {{VALUE}} !important ',
                ),
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                ),
                'default' => "#333",
            )
        );
        $this->add_control(
            'ccew_card_price_color',
            array(
                'label' => __('Coin Price Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper.style-2 .ccew-card-content .ccew-card-price' => 'color: {{VALUE}} !important ',
                ),
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                ),
                'default' => "#333",
            )
        );
        $this->add_control(
            'ccew_card_changes_color',
            array(
                'label' => __('Changes 24H Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper.style-2 .ccew-card-content .ccew-card-volume' => 'color: {{VALUE}} !important ',
                ),
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                ),
                'default' => "#333",
            )
        );
        $this->add_control(
            'ccew_card_low24_color',
            array(
                'label' => __('Low 24H Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper.style-2 .ccew-chart-card-offset .ccew-low-24 ' => 'color: {{VALUE}} !important ',
                ),
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                    'ccew_coin_api' => 'coin_gecko',
                ),
                'default' => "#333",
            )
        );
        $this->add_control(
            'ccew_card_high24_color',
            array(
                'label' => __('High 24H Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper.style-2 .ccew-chart-card-offset .ccew-high-24 ' => 'color: {{VALUE}} !important ',
                ),
                'condition' => array(
                    'ccew_widget_type' => array('card'),
                    'ccew_widget_style' => array('style-2'),
                    'ccew_coin_api' => 'coin_gecko',
                ),
                'default' => "#333",
            )
        );

        $this->add_control(
            'ccew_primary_color',
            array(
                'label' => __('Primary Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper .ccew-primary ,
						{{WRAPPER}} div[id*=ccew-wrap] table thead tr th,
						{{WRAPPER}} div[id*=ccew-wrap] a.paginate_button.next,
						{{WRAPPER}} div[id*=ccew-wrap] a.paginate_button.previous' => 'color: {{VALUE}} !important ',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'ccew_widget_type',
                            'operator' => '!==',
                            'value' => 'card',
                        ],
                        [
                            'name' => 'ccew_widget_style',
                            'operator' => '!==',
                            'value' => 'style-2',
                        ],
                    ],
                ),
            )
        );

        $this->add_control(
            'ccew_secondary_color',
            array(
                'label' => __('Secondary Color', 'ccew'),
                'type' => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .ccew-wrapper .ccew-secondary ,
						{{WRAPPER}} div[id*=ccew-wrap] table tbody tr td' => 'color: {{VALUE}} !important',
                ),
                'conditions' => array(
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'ccew_widget_type',
                            'operator' => '!==',
                            'value' => 'card',
                        ],
                        [
                            'name' => 'ccew_widget_style',
                            'operator' => '!==',
                            'value' => 'style-2',
                        ],
                    ],
                ),

            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Box_Shadow::get_type(),
            array(
                'name' => 'box_shadow',
                'label' => __('Box Shadow', 'ccew'),
                'selector' => '{{WRAPPER}} .ccew-wrapper,  div[id*=ccew-wrap]',
                'condition' => array(
                    'ccew_widget_type!' => 'advanced_table',
                ),
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'table_list_typography',
                'label' => esc_html__('Table/List Heading Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} div[id*=ccew-wrap] table thead tr th,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-list-head',
                'condition' => array(
                    'ccew_widget_type' => array('list', 'advanced_table'),
                ),
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'coin_name_typography',
                'label' => esc_html__('Coin Name Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper.ccew-price-list .ccew-coin-name,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-coin-name,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-card-coin span,
                {{WRAPPER}} div[id*=ccew-wrap] #ccew-coinslist_wrapper tbody .ccew_coin_name
                ',

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'coin_symbol_typography',
                'label' => esc_html__('Coin Symbol Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper.ccew-price-list .ccew-coin-symbol,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-coin-symbol,
                {{WRAPPER}} div[id*=ccew-wrap] #ccew-coinslist_wrapper tbody .ccew_coin_symbol
                ',

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'coin_price_typography',
                'label' => esc_html__('Coin Price Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper.ccew-price-list .ccew-coin-price,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-coin-price,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-coin-price span.ccew-price,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-card-price span

                ',
                'condition' => array(
                    'ccew_widget_type!' => array('advanced_table'),
                ),

            ]
        );
        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name' => 'coin_other_typography',
                'label' => esc_html__('Other Content Typography', 'plugin-name'),
                'selector' => '{{WRAPPER}} div[id*=ccew-wrap] .ccew-coin-info .ccew-info-item span,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-change-percent span,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-card-change span,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-card-volume span,
                 {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-changes-time,
                 {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper span.changes,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-card-offset-content .ccew-low-24 span,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-card-offset-content .ccew-high-24 span,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-card-offset-content .ccew-low-24 ,
                {{WRAPPER}} div[id*=ccew-wrap] .ccew-wrapper .ccew-card-offset-content .ccew-high-24
                ',
                'condition' => array(
                    'ccew_widget_type!' => array('advanced_table'),
                ),

            ]
        );

        $this->end_controls_section();
        $this->start_controls_section(
            'ccewd_review_section',
            array(
                'label' => __('We Would Appreciate Your Feedback', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        $this->add_control(
            'ccew_review_note',
            array(
                'label' => __('Review Notice', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<div class="ccew_cmc_demo">
        <div class="ccew_cmc_def">
       You\'ve used our widget for a while. We hope you liked it! </br> Please give us a quick rating to help us keep improving the plugin!!</div>
		<div class="ccew_link_wrap"><a class="ccew_demo_link" href="https://wordpress.org/support/plugin/cryptocurrency-widgets-for-elementor/reviews/#new-post" target="_blank"><button class="ccew-custom-primry-btn">Submit Review ★★★★★</button></a></div>
    </div>',

            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'ccewd_permotion_section',
            array(
                'label' => __('Crypto Pro Plugins Demos', 'ccew'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );
        $this->add_control(
            'ccew_important_note',
            array(
                'label' => __('Pro plugins', 'plugin-name'),
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'show_label' => false,
                'raw' => '<ul class="ccew-promotion-plugins">
							<div class="ccew_cmc_demo">
							 <div class="ccew_logo_container"><a href="'.CCEW_DEMO_URL.'/demo/coins-marketcap/'.CCEW_UTM.'" target="_blank"><img src="' . CCEW_URL . 'assets/images/coinmarketcap-logo.png" alt="Cryptocurrency widget for elementor" style="max-width:80px;"></a></div>
							 <div class="ccew_cmc_def">
							<strong>Coins MarketCap</strong>
							Coins Marketcap plugin creates a fully automatic crypto coins price listing website that dynamically generates 4500+ coins pages.
							</div><div class="ccew_link_wrap"><a class="ccew_demo_link" href="'.CCEW_DEMO_URL.'/demo/coins-marketcap/'.CCEW_UTM.'" target="_blank"><button class="ccew-custom-primry-btn">View Demos </button> </a> <a class="ccew_demo_buyk" href="https://cryptocurrencyplugins.com/wordpress-plugin/coins-marketcap/?utm_source=widget_settings&utm_medium=inside&utm_campaign=get-pro-cmc&utm_content=buy-now" target="_blank"><button class="ccew-custom-primry-btn">Buy Pro</button></a></div>
							</div>
							<hr>
							<div class="ccew_cmc_demo">
							<div class="ccew_logo_container"><a href="'.CCEW_DEMO_URL.'/demo/cryptocurrency-widgets-pro/'.CCEW_UTM.'" target="_blank"><img src="' . CCEW_URL . 'assets/images/crypto-widget-pro.png" alt="Cryptocurrency widget for elementor" style="max-width:80px;"></a></div>
							<div class="ccew_cmc_def"><strong>Cryptocurrency Widgets Pro</strong>
							Show cryptocurrency price table, historical charts, tickers and other widgets inside any page or post.</div><div class="ccew_link_wrap"><a class="ccew_demo_link" href="'.CCEW_DEMO_URL.'/demo/cryptocurrency-widgets-pro/'.CCEW_UTM.'" target="_blank"><button class="ccew-custom-primry-btn">View Demos </button> </a><a class="ccew_demo_buyk"  href="https://cryptocurrencyplugins.com/wordpress-plugin/cryptocurrency-widgets-pro/?utm_source=widget_settings&utm_medium=inside&utm_campaign=get-pro-ccpw&utm_content=buy-now" target="_blank"><button class="ccew-custom-primry-btn">Buy Pro</button></a></div>
							</div>
						  </ul>',

            )
        );

        $this->end_controls_section();

    }

    // for frontend
    protected function render()
    {

        $api_option = get_option('openexchange-api-settings');
        $api = (isset($api_option['coingecko_api'])) ? $api_option['coingecko_api'] : "";

  
            $selected_api = get_option("ccew-api-settings");
            if (isset($selected_api['select_api'])) {
                if (($selected_api['select_api'] == 'coin_gecko') && (!ccew_check_user())) {
                    echo ('Please enter Coingecko Free Api Key to get this plugin works.');
                    return;
                }
            }elseif (!$selected_api) {
                // Handle the case where $selected_api is false
                if ((!ccew_check_user())) {
                    echo ('Please enter Coingecko Free Api Key to get this plugin works.');
                    return;
                }
            }

        
        $settings = $this->get_settings_for_display();
        $random_id = rand();
        $html_setting['widget_type'] = $settings['ccew_widget_type'];
        $html_setting['fiat_currency'] = $settings['ccew_fiat_currency'];
        $html_setting['fiat_c_rate'] = ccew_usd_conversions($settings['ccew_fiat_currency']);
        $html_setting['number_formating'] = $settings['ccew_number_formating'];

        if ($settings['ccew_widget_type'] == 'list') {
            $html_setting['display_24h_changes'] = $settings['ccew_display_24h_changes'];
            $html_setting['ccew_display_table_head'] = $settings['ccew_display_table_head'];
            $html_setting['numberof_coins'] = $settings['ccew_all_coins'];
            $html_setting['display_graph'] = $settings['ccew_display_graph'];
            if ($html_setting['numberof_coins'] == 'custom_coin') {
                $html_setting['numberof_coins'] = $settings['ccew_custom_coin'];
            }
        } elseif ($settings['ccew_widget_type'] == 'card') {
            $html_setting['display_24h_changes'] = $settings['ccew_display_24h_changes'];
            $html_setting['ccew_card2_changes'] = $settings['ccew_card2_changes'];
            $html_setting['ccew_display_chart_offset'] = $settings['ccew_display_chart_offset'];
            $html_setting['ccew_chart_color'] = $settings['ccew_chart_color'];
            $html_setting['ccew_chart_border_color'] = $settings['ccew_chart_border_color'];
            $html_setting['ccew_widget_style'] = $settings['ccew_widget_style'];
            $html_setting['selected_coin'] = $settings['ccew_select_coins'];
            $html_setting['display_high_low'] = $settings['ccew_display_high_low'];
            $html_setting['display_1h_changes'] = $settings['ccew_display_1h_changes'];
            $html_setting['display_24h_changes'] = $settings['ccew_display_24h_changes'];
            $html_setting['display_7d_changes'] = $settings['ccew_display_7d_changes'];
            $html_setting['display_30d_changes'] = $settings['ccew_display_30d_changes'];
            $html_setting['display_rank'] = $settings['ccew_display_rank'];
            $html_setting['display_marketcap'] = $settings['ccew_display_marketcap'];
            $html_setting['coin_symbol_visibility'] = $settings['ccew_display_coin_symbol'];
        } elseif ($settings['ccew_widget_type'] == 'advanced_table') {
            $html_setting['numberof_coins'] = $settings['ccew_all_coins'];
            $html_setting['required_coins'] = (int) $settings['ccew_all_coins'];
            if ($html_setting['numberof_coins'] == 'custom_coin') {
                $html_setting['numberof_coins'] = $settings['ccew_custom_coin'];
                $html_setting['required_coins'] = (!empty($html_setting['numberof_coins'])) ? count($settings['ccew_custom_coin']) : '';
            }

            $html_setting['prev'] = __('Previous', 'ccew');
            $html_setting['next'] = __('Next', 'ccew');
            $html_setting['no_data'] = __('No Coin Found', 'ccew');
            $html_setting['table_id'] = $random_id;
            $html_setting['pagination'] = ($html_setting['required_coins'] < $settings['ccew_pagination']) ? $html_setting['required_coins'] : $settings['ccew_pagination'];
            $html_setting['symbol'] = ccew_currency_symbol($html_setting['fiat_currency']);
            $cls = 'ccew-coinslist_wrapper';
            $id = 'ccew-coinslist_wrapper';
            $html_setting['loading_lbl'] = __('Loading...', 'ccpw');
        } elseif ($settings['ccew_widget_type'] == 'top_gainer_loser') {
            $html_setting['display_24h_changes'] = $settings['ccew_display_24h_changes'];
            $html_setting['sortby'] = $settings['ccew_gainer_loser_sortby'];
            $html_setting['display_graph'] = $settings['ccew_display_graph'];
            $html_setting['numberof_coins'] = $settings['ccew_numberof_coins'];
        } else {
            $html_setting['display_24h_changes'] = $settings['ccew_display_24h_changes'];
            $html_setting['selected_coin'] = $settings['ccew_select_coins'];

        }

        $settings_json = json_encode($html_setting);
        $wp_nonce = wp_create_nonce('ccew-create-widget');
        echo '<script type="application/json" class="ccew_htmlContainer" data-ajax-nonce=' . esc_attr($wp_nonce) . ' data-ajax-url=' . esc_attr(admin_url('admin-ajax.php')) . '>' . wp_kses_post($settings_json) . '</script><div class="ccew_html_container" id="ccew-wrap' . esc_attr($random_id) . '">';
        if (isset($html_setting['required_coins']) && $html_setting['required_coins'] == '' && $settings['ccew_widget_type'] == 'advanced_table') {
            echo esc_html__('Please Select Coin', 'ccew');
        } else {

            if ($settings['ccew_widget_type'] == 'advanced_table') {
                echo '<div id="' . esc_attr($id) . '" class="' . esc_attr($cls) . '">
			<table id="ccew-datatable' . esc_attr($random_id) . '"
			class="display  ccew_table_widget table-striped table-bordered no-footer"
			style="border:none!important;">
			<thead>
			<th data-classes="desktop ccew_coin_rank" data-index="rank">' . esc_html__('#', 'ccew') . '</th>
			<th data-classes="desktop ccew_name" data-index="name">' . esc_html__('Name', 'ccew') . '</th>
			<th data-classes="desktop ccew_coin_price" data-index="price">' . esc_html__('Price', 'ccew') . '</th>
			<th data-classes="desktop ccew_coin_change24h" data-index="change_percentage_24h">' . esc_html__('Changes 24h', 'ccew') . '</th>
			<th data-classes="desktop ccew_coin_market_cap" data-index="market_cap">' . esc_html__('Market CAP', 'ccew') . '</th>';
                if ($api == "coin_gecko") {
                    echo '<th data-classes="ccew_coin_total_volume" data-index="total_volume">' . esc_html__('Volume', 'ccew') . '</th>';
                }
                echo '<th data-classes="ccew_coin_supply" data-index="supply">' . esc_html__('Supply', 'ccew') . '</th>
			</tr></thead><tbody>
			</tbody><tfoot>
			</tfoot></table>
			</div>
			</div>';

            } else {
                echo '<div class="ph-item">
						<div class="ph-col-12">
							<div class="ph-row">
								<div class="ph-col-6 big"></div>
								<div class="ph-col-4  big"></div>
								<div class="ph-col-2 big"></div>
								<div class="ph-col-4"></div>
								<div class="ph-col-8 "></div>
								<div class="ph-col-6"></div>
								<div class="ph-col-6 "></div>
								<div class="ph-col-12"></div>
							</div>
						</div>
					</div>
		</div>';
            }
        }
    }

    // for live editor
    // protected function _content_template() {

    // }

}

\Elementor\Plugin::instance()->widgets_manager->register(new ccew__Widget());
