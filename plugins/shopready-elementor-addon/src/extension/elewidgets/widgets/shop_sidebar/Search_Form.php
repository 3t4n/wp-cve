<?php

namespace Shop_Ready\extension\elewidgets\widgets\shop_sidebar;

use Shop_Ready\base\elementor\style_controls\common\Widget_Form;

/**
 * WooCommerce Shop Sidebar Search Form
 * @see https://docs.woocommerce.com/document/overriding-the-product-search-box-widget/
 * @see https://wordpress.org/support/article/wordpress-widgets/
 * @author quomodosoft.com
 */
class Search_Form extends \Shop_Ready\extension\elewidgets\Widget_Base
{
    use Widget_Form;
    public $wrapper_class = true;
    protected function register_controls()
    {

        $this->start_controls_section(
            'layout_contents_section',
            [
                'label' => esc_html__('Layout Options', 'shopready-elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'style',
            [
                'label' => esc_html__('Layout', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => 'style1',
                'options' => [
                    'style1' => esc_html__('Layout One', 'shopready-elementor-addon'),
                    'style2' => esc_html__('Layout Two', 'shopready-elementor-addon'),
                    'style3' => esc_html__('Layout Three', 'shopready-elementor-addon'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'wready_content_cart_section',
            [
                'label' => esc_html__('Settings', 'shopready-elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'auto_complate',
            [
                'label' => __('Auto Complate?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'shopready-elementor-addon'),
                'label_off' => __('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'search_button_label',
            [
                'label' => __('Button Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('Search', 'shopready-elementor-addon'),
                'placeholder' => __('Type Search label here', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'search_palceholder',
            [
                'label' => __('Search PlaceHolder', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => esc_attr_x('Search Products&hellip;', 'placeholder', 'shopready-elementor-addon'),
                'placeholder' => esc_attr_x('Search Products&hellip;', 'placeholder', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'search_icon',
            [
                'label' => __('Search Icon', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::ICONS,
                'default' => [
                    'value' => 'fa fa-search',
                    'library' => 'solid',
                ],
                'label_block' => true,
            ]
        );

        $this->add_control(
            'redirect_to_shop',
            [
                'label' => __('Redirect To Shop Page', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'shopready-elementor-addon'),
                'label_off' => __('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category?', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'shopready-elementor-addon'),
                'label_off' => __('No', 'shopready-elementor-addon'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );


        $this->end_controls_section();

        $this->start_controls_section(
            'category_content_section',
            [
                'label' => __('Category', 'shopready-elementor-addon'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                'condition' => [
                    'category' => ['yes'],
                ],
            ]
        );

        $this->add_control(
            'all_cats',
            [
                'label' => __('All Category Label', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => __('All Category', 'shopready-elementor-addon'),
                'placeholder' => __('Type option label here', 'shopready-elementor-addon'),
            ]
        );

        $this->add_control(
            'category_list',
            [
                'label' => __('Select Categories', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => shop_ready_get_post_cat(),

            ]
        );

        $this->add_control(
            'cats_matgin',
            [
                'label' => __('Category Container Margin', 'shopready-elementor-addon'),
                'type' => \Elementor\Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .wooready_input_wrapper .wooready_nice_select' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->box_css(
            [
                'title' => esc_html__('Box Wrapper', 'shopready-elementor-addon'),
                'slug' => 'widget_box_reapper',
                'element_name' => 'widget__box_reapperr',
                'selector' => '{{WRAPPER}} .wooready_input_wrapper',
                'disable_controls' => [
                    'position',
                    'size',
                    'alignment'
                ],
            ]
        );

        $this->text_minimum_css(
            [
                'title' => esc_html__('Button', 'shopready-elementor-addon'),
                'slug' => 'widget_search_btn',
                'element_name' => 'shop_product_btn',
                'selector' => '{{WRAPPER}} .wooready_input_box button',
                'hover_selector' => '{{WRAPPER}} .wooready_input_box button:hover',
            ]
        );

        $this->input_field(
            [
                'title' => esc_html__('Input', 'shopready-elementor-addon'),
                'slug' => 'wready_wc_default_product_input',
                'element_name' => 'sarch_input_',
                'selector' => '{{WRAPPER}} .wooready_input_box input[name=s]',
                'hover_selector' => '{{WRAPPER}} .wooready_input_box input[name=s]:focus',
            ]
        );

        $this->text_css(
            [
                'title' => esc_html__('Category Box', 'shopready-elementor-addon'),
                'slug' => 'widget_search_cat_box',
                'element_name' => 'shop_product_cat_box',
                'selector' => '{{WRAPPER}} .wooready_nice_select .nice-select',
                'hover_selector' => '{{WRAPPER}} .wooready_nice_select .nice-select:hover',
                'disable_controls' => [
                    'position',
                    'size',
                ],
            ]
        );

        $this->text_css(
            [
                'title' => esc_html__('Category List', 'shopready-elementor-addon'),
                'slug' => 'widget_search_cat_list',
                'element_name' => 'shop_product_cat_lsit',
                'selector' => '{{WRAPPER}} .wooready_nice_select .nice-select ul.list',
                'hover_selector' => '{{WRAPPER}} .wooready_nice_select .nice-select ul.list:hover',
                'disable_controls' => [
                    'position',
                    'size',
                ],
            ]
        );

        $this->element_before_psudocode(
            [
                'title' => esc_html__('Category List After', 'shopready-elementor-addon'),
                'slug' => 'widget_search_cat_list_after',
                'element_name' => 'shop_product_cat_lsit_after',
                'selector' => '{{WRAPPER}} .wooready_nice_select .nice-select::after',
            ]
        );

        $this->text_minimum_css(
            [
                'title' => esc_html__('Category Option', 'shopready-elementor-addon'),
                'slug' => 'widget_search_cat_option',
                'element_name' => 'shop_product_cat_option',
                'selector' => '{{WRAPPER}} .nice-select .option',
                'hover_selector' => '{{WRAPPER}} .nice-select .option:hover,{{WRAPPER}} .nice-select .option.focus,{{WRAPPER}} .nice-select .option.selected.focus',
                'disable_controls' => [
                    'display',
                ],
            ]
        );
    }

    protected function html()
    {

        global $woocommerce;
        if (!isset($woocommerce->query)) {
            return;
        }

        $settings = $this->get_settings_for_display();

        $search_icon = shop_ready_render_icons($settings['search_icon'], 'wready-icons');

        $this->add_render_attribute(
            'wrapper_style',
            [
                'class' => ['widget_rating_filter', $settings['style']],
            ]
        );

        echo wp_kses_post(sprintf("<div %s>", wp_kses_post($this->get_render_attribute_string('wrapper_style'))));

        if (file_exists(dirname(__FILE__) . '/template-parts/search/' . $settings['style'] . '.php')) {

            shop_ready_widget_template_part(
                'shop_sidebar/template-parts/search/' . $settings['style'] . '.php',
                [
                    'settings' => $settings,
                    'search_icon' => $search_icon,
                    'obj' => $this,
                    'selected' => isset($_GET['wr-category']) ? sanitize_text_field($_GET['wr-category']) : '',
                ]
            );
        } else {

            shop_ready_widget_template_part(
                'shop_sidebar/template-parts/search/style1.php',
                [
                    'settings' => $settings,
                    'obj' => $this,
                    'selected' => isset($_GET['wr-category']) ? sanitize_text_field($_GET['wr-category']) : '',
                ]
            );
        }

        echo wp_kses_post('</div>');
    }
}