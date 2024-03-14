<?php

namespace Element_Ready\Widgets\posts;

use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;


if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Element_Ready_Portfolio extends Widget_Base
{
    use Content_Style;

    public function get_name()
    {
        return 'Element_Ready_Portfolio';
    }

    public function get_title()
    {
        return esc_html__('ER Portfolio', 'element-ready-lite');
    }

    public function get_icon()
    {
        return 'eicon-posts-justified';
    }

    public function get_categories()
    {
        return ['element-ready-addons'];
    }

    public function get_keywords()
    {
        return ['Portfolio', 'Portfolio Carousel', 'portfolio masonry', 'masonry', 'filter', 'portfolio grid', 'Slider'];
    }


    public function get_script_depends()
    {
        return [
            'isotope',
            'masonry',
            'slick',
            'element-ready-core',
        ];
    }

    public function get_style_depends()
    {

        wp_register_style('eready-portfolio', ELEMENT_READY_ROOT_CSS . 'widgets/portfolio.css');
        wp_register_style('eready-gallaery', ELEMENT_READY_ROOT_CSS . 'widgets/gallaery.css');

        return [
            'slick', 'eready-portfolio', 'eready-gallaery'
        ];
    }

    static function content_layout_style()
    {
        return apply_filters('element_ready_portfolio_style_presets', [
            '1'      => esc_html__('Layout One', 'element-ready-lite'),
            '2'      => esc_html__('Layout Two', 'element-ready-lite'),
            '3_pro'      => esc_html__('Layout Three - PRO', 'element-ready-lite'),
            '4_pro'      => esc_html__('Layout Four - PRO', 'element-ready-lite'),
            '5_pro'      => esc_html__('Layout Five - PRO', 'element-ready-lite'),
            '6_pro'      => esc_html__('Layout Six - PRO', 'element-ready-lite'),
            '7_pro'      => esc_html__('Layout Seven - PRO', 'element-ready-lite'),
            '8_pro'      => esc_html__('Layout Eight - PRO', 'element-ready-lite'),
            'custom_pro' => esc_html__('Layout Custom - PRO', 'element-ready-lite'),
        ]);
    }

    static function element_ready_post_layout()
    {
        return apply_filters('element_ready_portfolio_layout_style_presets', [
            'slider'             => esc_html__('Slider', 'element-ready-lite'),
            'genaral'            => esc_html__('Genaral', 'element-ready-lite'),
            'filtering_PRO'      => esc_html__('Filtering - PRO', 'element-ready-lite'),
            'masonry_PRO'        => esc_html__('Masonry - PRO', 'element-ready-lite'),
            'masonry_filter_PRO' => esc_html__('Masonry Filter - PRO', 'element-ready-lite'),
            'genaral_filter_PRO' => esc_html__('Genaral Filter - PRO', 'element-ready-lite'),
        ]);
    }

    static function element_ready_get_post_types($args = [])
    {

        $post_type_args = [
            'show_in_nav_menus' => true,
        ];
        if (!empty($args['post_type'])) {
            $post_type_args['name'] = $args['post_type'];
        }
        $_post_types = get_post_types($post_type_args, 'objects');

        $post_types  = [];
        foreach ($_post_types as $post_type => $object) {
            $post_types[$post_type] = $object->label;
        }
        return $post_types;
    }

    static function element_ready_get_taxonomies($element_ready_texonomy = 'category')
    {
        $terms = get_terms(array(
            'taxonomy'   => $element_ready_texonomy,
            'hide_empty' => true,
        ));
        if (!empty($terms) && !is_wp_error($terms)) {
            foreach ($terms as $term) {
                $options[$term->term_id] = $term->name;
            }
            return $options;
        }
    }

    public function element_ready_portfolio_category($category = 'portfolio_category', $separator = ', ', $type = 'name')
    {


        // For get all value form tearms.
        $signle_cat_value = get_the_terms(get_the_ID(), $category);

        //Check if not empty varibale
        if (!empty($signle_cat_value)) {
            $item_cats =  wp_get_post_terms(get_the_ID(), $category);
            $item_all_cats = array();
            foreach ($item_cats  as $item_cat) {
                if (isset($item_cat->$type)) {
                    $item_all_cats[] = sprintf('<a href="%s" > %s</a>', '#', $item_cat->$type);
                }
            }

            return implode($separator, $item_all_cats);
        }
    }


    protected function register_controls()
    {

        /*-----------------------------
            CONTENT SECTION
        -------------------------------*/
        $this->start_controls_section(
            'content_section',
            [
                'label' => esc_html__('Content layout Style', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'content_layout_style',
            [
                'label'   => esc_html__('Layout', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => '1',
                'options' => self::content_layout_style(),
            ]
        );
        $this->end_controls_section();
        /*-----------------------------
            CONTENT SECTION END
        -------------------------------*/


        /*-----------------------------
            MASONRY CONTROL
        -------------------------------*/
        $this->start_controls_section(
            'masonry_settings_section',
            [
                'label' => esc_html__('Layout Settings', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'content_gallery_type_heading',
            [
                'label'     => esc_html__('Gallery Layout Style', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'gallery_type',
            [
                'label'     => esc_html__('Gallery Style', 'element-ready-lite'),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'genaral',
                'options'   => self::element_ready_post_layout(),
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'content_gallery_slider_heading',
            [
                'label'     => esc_html__('Slider Settings', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'gallery_type' => 'slider',
                ]
            ]
        );
        $this->add_control(
            'slider_on',
            [
                'label'        => esc_html__('Slider On', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('On', 'element-ready-lite'),
                'label_off'    => esc_html__('Off', 'element-ready-lite'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
                'condition'    => [
                    'gallery_type' => 'slider',
                ]
            ]
        );
        $this->add_control(
            'content_gallery_filter_heading',
            [
                'label'     => esc_html__('Gallery Settings', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [
                    'gallery_type!' => 'slider',
                ]
            ]
        );
        $this->add_control(
            'layout_mode',
            [
                'label'   => esc_html__('Fitering Layout Mode', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'fitRows',
                'options' => [
                    'masonry'           => esc_html__('Masonry', 'element-ready-lite'),
                    'masonryHorizontal' => esc_html__('Masonry Horizontal', 'element-ready-lite'),
                    'fitRows'           => esc_html__('Fit Rows', 'element-ready-lite'),
                    'fitColumns'        => esc_html__('Fit Columns', 'element-ready-lite'),
                    'cellsByColumn'     => esc_html__('Cells By Column', 'element-ready-lite'),
                ],
                'separator' => 'before',
                'condition' => [
                    'gallery_type!' => 'slider',
                ]
            ]
        );

        $icon_opt = apply_filters('element_ready_portfolio_filter_options_pro_message', $this->pro_message('menu_pro_messagte'), false);
        $this->run_controls($icon_opt);
        do_action('element_ready_portfolio_filter_options', $this);

        $this->add_control(
            'show_all_menu',
            [
                'label'        => esc_html__('Show All Category', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('On', 'element-ready-lite'),
                'label_off'    => esc_html__('Off', 'element-ready-lite'),
                'return_value' => 'yes',
                'default'      => 'yes',
                'separator'    => 'before',
                'condition'    => [
                    'gallery_type!' => 'slider',
                    'gallery_menu'  => 'yes',
                ]
            ]
        );
        $this->add_control(
            'active_custom_category',
            [
                'label'        => esc_html__('Active Custom Category ?', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__('On', 'element-ready-lite'),
                'label_off'    => esc_html__('Off', 'element-ready-lite'),
                'return_value' => 'yes',
                'default'      => 'no',
                'separator'    => 'before',
                'condition'    => [
                    'gallery_type!' => 'slider',
                    'gallery_menu'  => 'yes',
                ]
            ]
        );
        $this->add_control(
            'active_menu_category',
            [
                'label'       => esc_html__('Active Category', 'element-ready-lite'),
                'type'        => Controls_Manager::SELECT,
                'options'     => self::element_ready_get_taxonomies('portfolio_category'),
                'description' => esc_html__('If you want to by default active a defferent cartegory, Please provide a category name which you provide in categoy as gallery item.', 'element-ready-lite'),
                'condition'   => [
                    'gallery_type!'          => 'slider',
                    'gallery_menu'           => 'yes',
                    'active_custom_category' => 'yes',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'gallery_columns',
            [
                'label' => esc_html__('Columns', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min'  => 1,
                        'max'  => 12,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'size' => 3
                ],
                'condition'    => [
                    'gallery_type!' => 'slider',
                ],
                'separator' => 'before',
            ]
        );


        $columns_margin  = is_rtl() ? '0 0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}};' : '0 -{{SIZE}}{{UNIT}} -{{SIZE}}{{UNIT}} 0;';
        $columns_padding = is_rtl() ? '0 0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}};' : '0 {{SIZE}}{{UNIT}} {{SIZE}}{{UNIT}} 0;';
        $this->add_responsive_control(
            'gallery_gutter',
            [
                'label'      => esc_html__('Columns Gutter', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30
                ],
                'selectors' => [
                    '(desktop){{WRAPPER}} .element__ready__portfolio__item__parent' => 'max-width:calc( 100% / {{gallery_columns.size}} ); padding: ' . $columns_padding,
                    '(tablet){{WRAPPER}} .element__ready__portfolio__item__parent'  => 'max-width:calc( 100% / {{gallery_columns_tablet.size}} ); padding: ' . $columns_padding,
                    '(mobile){{WRAPPER}} .element__ready__portfolio__item__parent'  => 'max-width:calc( 100% / {{gallery_columns_mobile.size}} ); padding: ' . $columns_padding,

                    '(desktop){{WRAPPER}} .element-ready-filter-activation' => 'margin: ' . $columns_margin,
                    '(tablet){{WRAPPER}} .element-ready-filter-activation'  => 'margin: ' . $columns_margin,
                    '(mobile){{WRAPPER}} .element-ready-filter-activation'  => 'margin: ' . $columns_margin,
                ],
                'condition'    => [
                    'gallery_type!' => 'slider',
                ],
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
        /*-----------------------------
            MASONRY CONTROL END
        -------------------------------*/

        /*------------------------------
            CONTENT OPTION START
        -------------------------------*/
        $this->start_controls_section(
            'post_content_option',
            [
                'label' => esc_html__('Post Option', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'carousel_post_type',
            [
                'label'       => esc_html__('Content Sourse', 'element-ready-lite'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'options'     => self::element_ready_get_post_types(),
            ]
        );

        $this->add_control(
            'carousel_categories',
            [
                'label'       => esc_html__('Categories', 'element-ready-lite'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => self::element_ready_get_taxonomies(),
                'condition'   => [
                    'carousel_post_type' => 'post',
                ]
            ]
        );

        $this->add_control(
            'carousel_prod_categories',
            [
                'label'       => esc_html__('Categories', 'element-ready-lite'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => self::element_ready_get_taxonomies('product_cat'),
                'condition'   => [
                    'carousel_post_type' => 'product',
                ]
            ]
        );

        $this->add_control(
            'portfolio_categorys',
            [
                'label'       => esc_html__('Categories', 'element-ready-lite'),
                'type'        => Controls_Manager::SELECT2,
                'label_block' => true,
                'multiple'    => true,
                'options'     => self::element_ready_get_taxonomies('portfolio_category'),
                'condition'   => [
                    'carousel_post_type' => 'portfolio',
                ]
            ]
        );

        $this->add_control(
            'post_limit',
            [
                'label'     => esc_html__('Limit', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 5,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'custom_order',
            [
                'label'        => esc_html__('Custom order', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'no',
            ]
        );

        $this->add_control(
            'postorder',
            [
                'label'   => esc_html__('Order', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'DESC',
                'options' => [
                    'DESC' => esc_html__('Descending', 'element-ready-lite'),
                    'ASC'  => esc_html__('Ascending', 'element-ready-lite'),
                ],
                'condition' => [
                    'custom_order!' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label'   => esc_html__('Orderby', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none'          => esc_html__('None', 'element-ready-lite'),
                    'ID'            => esc_html__('ID', 'element-ready-lite'),
                    'date'          => esc_html__('Date', 'element-ready-lite'),
                    'name'          => esc_html__('Name', 'element-ready-lite'),
                    'title'         => esc_html__('Title', 'element-ready-lite'),
                    'comment_count' => esc_html__('Comment count', 'element-ready-lite'),
                    'rand'          => esc_html__('Random', 'element-ready-lite'),
                ],
                'condition' => [
                    'custom_order' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'show_thumb',
            [
                'label'        => esc_html__('Thumbnail', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'label'        => esc_html__('Thumb Size', 'element-ready-lite'),
                'name'    => 'thumb_size',
                'default' => 'large',
                'condition' => [
                    'show_thumb' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'show_category',
            [
                'label'        => esc_html__('Category', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_author',
            [
                'label'        => esc_html__('Author', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'show_date',
            [
                'label'        => esc_html__('Date', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'date_type',
            [
                'label'   => esc_html__('Date Type', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'date',
                'options' => [
                    'date'     => esc_html__('Date', 'element-ready-lite'),
                    'time'     => esc_html__('Time', 'element-ready-lite'),
                    'time_ago' => esc_html__('Time Ago', 'element-ready-lite'),
                    'date_time' => esc_html__('Date and Time', 'element-ready-lite'),
                ],
                'condition' => [
                    'show_date' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'show_title',
            [
                'label'        => esc_html__('Title', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'title_length',
            [
                'label'     => esc_html__('Title Length', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'step'      => 1,
                'default'   => 5,
                'condition' => [
                    'show_title' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'show_content',
            [
                'label'        => esc_html__('Content', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'content_length',
            [
                'label'     => esc_html__('Content Length', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'step'      => 1,
                'default'   => 20,
                'condition' => [
                    'show_content' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'show_read_more_btn',
            [
                'label'        => esc_html__('Read More', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'default'      => 'yes',
            ]
        );

        $this->add_control(
            'read_more_txt',
            [
                'label'       => esc_html__('Read More button text', 'element-ready-lite'),
                'type'        => Controls_Manager::TEXT,
                'default'     => esc_html__('Read More', 'element-ready-lite'),
                'placeholder' => esc_html__('Read More', 'element-ready-lite'),
                'label_block' => true,
                'condition'   => [
                    'show_read_more_btn' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'readmore_icon',
            [
                'label'     => esc_html__('Readmore Icon', 'element-ready-lite'),
                'type'      => Controls_Manager::ICON,
                'label_block' => true,
                'condition' => [
                    'show_read_more_btn' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'readmore_icon_position',
            [
                'label'   => esc_html__('Icon Postion', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'right',
                'options' => [
                    'left'  => esc_html__('Left', 'element-ready-lite'),
                    'right' => esc_html__('Right', 'element-ready-lite'),
                ],
                'condition'   => [
                    'readmore_icon!' => '',
                ]
            ]
        );

        // Button Icon Margin
        $this->add_control(
            'readmore_icon_indent',
            [
                'label' => esc_html__('Icon Spacing', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50,
                    ],
                ],
                'condition' => [
                    'readmore_icon!' => '',
                ],
                'selectors' => [
                    '{{WRAPPER}} .readmore__btn .readmore_icon_right' => 'margin-left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .readmore__btn .readmore_icon_left'  => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*------------------------------
            CONTENT OPTION END
        -------------------------------*/

        /*------------------------------
            CAROUSEL OPTIONS
        -------------------------------*/
        $this->start_controls_section(
            'slider_option',
            [
                'label'     => esc_html__('Carousel Option', 'element-ready-lite'),
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slitems',
            [
                'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 20,
                'step'      => 1,
                'default'   => 3,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slrows',
            [
                'label'     => esc_html__('Slider Rows', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 5,
                'step'      => 1,
                'default'   => 0,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'slitemmargin',
            [
                'label'     => esc_html__('Slider Item Margin', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 100,
                'step'      => 1,
                'default'   => 1,
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio' => 'margin: calc( {{VALUE}}px / 2 );',
                    '{{WRAPPER}} .slick-list' => 'margin: calc( -{{VALUE}}px / 2 );',
                ],
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slarrows',
            [
                'label'        => esc_html__('Slider Arrow', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'nav_position',
            [
                'label'   => esc_html__('Arrow Position', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'outside_vertical_center_nav',
                'options' => [
                    'inside_vertical_center_nav'  => esc_html__('Inside Vertical Center', 'element-ready-lite'),
                    'outside_vertical_center_nav' => esc_html__('Outside Vertical Center', 'element-ready-lite'),
                    'inside_center_nav'           => esc_html__('Inside Center', 'element-ready-lite'),
                    'top_left_nav'                => esc_html__('Top Left', 'element-ready-lite'),
                    'top_center_nav'              => esc_html__('Top Center', 'element-ready-lite'),
                    'top_right_nav'               => esc_html__('Top Right', 'element-ready-lite'),
                    'bottom_left_nav'             => esc_html__('Bottom Left', 'element-ready-lite'),
                    'bottom_center_nav'           => esc_html__('Bottom Center', 'element-ready-lite'),
                    'bottom_right_nav'            => esc_html__('Bottom Right', 'element-ready-lite'),
                ],
                'condition' => [
                    'slarrows' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slprevicon',
            [
                'label'     => esc_html__('Previous icon', 'element-ready-lite'),
                'type'      => Controls_Manager::ICON,
                'label_block' => true,
                'default'   => 'fa fa-angle-left',
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slnexticon',
            [
                'label'     => esc_html__('Next icon', 'element-ready-lite'),
                'type'      => Controls_Manager::ICON,
                'label_block' => true,
                'default'   => 'fa fa-angle-right',
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ]
            ]
        );

        $this->add_control(
            'nav_visible',
            [
                'label'   => esc_html__('Arrow Visibility', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'visibility:visible;opacity:1;',
                'default'   => 'no',
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div' => '{{VALUE}}',
                ],
                'condition'   => [
                    'slarrows' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sldots',
            [
                'label'        => esc_html__('Slider dots', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slpause_on_hover',
            [
                'type'         => Controls_Manager::SWITCHER,
                'label_off'    => esc_html__('No', 'element-ready-lite'),
                'label_on'     => esc_html__('Yes', 'element-ready-lite'),
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'label'        => esc_html__('Pause on Hover?', 'element-ready-lite'),
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slcentermode',
            [
                'label'        => esc_html__('Center Mode', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slcenterpadding',
            [
                'label'     => esc_html__('Center padding', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 0,
                'max'       => 500,
                'step'      => 1,
                'default'   => 50,
                'condition' => [
                    'slider_on'    => 'yes',
                    'slcentermode' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slfade',
            [
                'label'        => esc_html__('Slider Fade', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slfocusonselect',
            [
                'label'        => esc_html__('Focus On Select', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slvertical',
            [
                'label'        => esc_html__('Vertical Slide', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slinfinite',
            [
                'label'        => esc_html__('Infinite', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'yes',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slrtl',
            [
                'label'        => esc_html__('RTL Slide', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slautolay',
            [
                'label'        => esc_html__('Slider auto play', 'element-ready-lite'),
                'type'         => Controls_Manager::SWITCHER,
                'return_value' => 'yes',
                'separator'    => 'before',
                'default'      => 'no',
                'condition'    => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slautoplay_speed',
            [
                'label'     => esc_html__('Autoplay speed', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 3000,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );


        $this->add_control(
            'slanimation_speed',
            [
                'label'     => esc_html__('Autoplay animation speed', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 300,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slscroll_columns',
            [
                'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 10,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'heading_tablet',
            [
                'label'     => esc_html__('Tablet', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sltablet_display_columns',
            [
                'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 8,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sltablet_scroll_columns',
            [
                'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 8,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'sltablet_width',
            [
                'label'       => esc_html__('Tablet Resolution', 'element-ready-lite'),
                'description' => esc_html__('The resolution to tablet.', 'element-ready-lite'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 750,
                'condition'   => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'heading_mobile',
            [
                'label'     => esc_html__('Mobile Phone', 'element-ready-lite'),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'after',
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slmobile_display_columns',
            [
                'label'     => esc_html__('Slider Items', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 4,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slmobile_scroll_columns',
            [
                'label'     => esc_html__('Slider item to scroll', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => 1,
                'max'       => 4,
                'step'      => 1,
                'default'   => 1,
                'condition' => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->add_control(
            'slmobile_width',
            [
                'label'       => esc_html__('Mobile Resolution', 'element-ready-lite'),
                'description' => esc_html__('The resolution to mobile.', 'element-ready-lite'),
                'type'        => Controls_Manager::NUMBER,
                'default'     => 480,
                'condition'   => [
                    'slider_on' => 'yes',
                ]
            ]
        );

        $this->end_controls_section(); // Slider Option end
        /*-----------------------
            CAROUSEL OPTIONS END
        -------------------------*/

        /*--------------------------
            COLUMNS MANAGER
        ----------------------------*/
        $this->start_controls_section(
            'items_columns_manager',
            [
                'label'     => esc_html__('Item Columns Manager', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );
        $repeater = new Repeater();
        $repeater->add_responsive_control(
            'columns_manager_columns',
            [
                'label'   => esc_html__('Display Columns', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'col-md-3 col-lg-3 col-sm-6 col-xs-12'    => esc_html__('3 Columns', 'element-ready-lite'),
                    'col-md-4 col-lg-4 col-sm-6 col-xs-12'    => esc_html__('4 Columns', 'element-ready-lite'),
                    'col-md-6 col-lg-6 col-sm-6 col-xs-12'    => esc_html__('6 Columns', 'element-ready-lite'),
                    'col-md-12 col-lg-12 col-sm-12 col-xs-12' => esc_html__('12 Columns', 'element-ready-lite'),
                ],
            ]
        );

        $repeater->add_responsive_control(
            'columns_manager_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'max-width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'columns_manager_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'max-height: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'columns_manager_position_from_left',
            [
                'label'      => esc_html__('From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'columns_manager_position_from_right',
            [
                'label'      => esc_html__('From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'right: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'columns_manager_position_from_top',
            [
                'label'      => esc_html__('From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $repeater->add_responsive_control(
            'columns_manager_position_from_bottom',
            [
                'label'      => esc_html__('From Bottom', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} {{CURRENT_ITEM}}' => 'bottom: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_control(
            'style_repeter',
            [
                'label' => esc_html__('Style List', 'element-ready-lite'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
            ]
        );
        $this->end_controls_section();
        /*--------------------------
            COLUMNS MANAGER
        ----------------------------*/

        /*-----------------------
            AREA STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_slider_content_area',
            [
                'label'     => esc_html__('Area Style', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );


        $this->add_responsive_control(
            'width',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1000,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vw' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                ],
                'selectors' => [
                    '{{WRAPPER}}' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'area_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}}' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*-----------------------
            AREA STYLE END
        -------------------------*/

        /*-------------------------
            MENU WRAP STYLE
        ---------------------------*/
        $this->start_controls_section(
            'element_ready_tab_style_area',
            [
                'label' => esc_html__('Menu Wrap', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'gallery_type!' => 'slider',
                ],
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_display',
            [
                'label'   => esc_html__('Display', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'initial'      => esc_html__('Initial', 'element-ready-lite'),
                    'block'        => esc_html__('Block', 'element-ready-lite'),
                    'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
                    'flex'         => esc_html__('Flex', 'element-ready-lite'),
                    'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
                    'none'         => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'menu_text_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu ul' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_float',
            [
                'label'   => esc_html__('Float', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'left'     => esc_html__('Left', 'element-ready-lite'),
                    'right'    => esc_html__('Right', 'element-ready-lite'),
                    'inherit ' => esc_html__('Inherit', 'element-ready-lite'),
                    'none'     => esc_html__('None', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => 'float: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .filter__menu' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'element_ready_tab_section_bg',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .filter__menu',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'element_ready_tab_section_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .filter__menu',
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'element_ready_tab_section_shadow',
                'label'    => esc_html__('Box Shadow', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .filter__menu',
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 9999,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 9999,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'element_ready_tab_section_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'custom_tab_area_css',
            [
                'label'     => esc_html__('Custom CSS', 'element-ready-lite'),
                'type'      => Controls_Manager::CODE,
                'rows'      => 20,
                'language'  => 'css',
                'selectors' => [
                    '{{WRAPPER}} .filter__menu' => '{{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_section();
        /*-------------------------
            MENU WRAP STYLE END
        ---------------------------*/

        /*-------------------------
            MENU ITEM STYLE
        ---------------------------*/
        $this->start_controls_section(
            'tab_button_style_section',
            [
                'label' => esc_html__('Menu Item', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'gallery_type!' => 'slider',
                ],
            ]
        );
        $this->start_controls_tabs('tabs_button_style');
        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );
        $this->add_responsive_control(
            'tab_button_display',
            [
                'label'   => esc_html__('Display', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'options' => [
                    'initial'      => esc_html__('Initial', 'element-ready-lite'),
                    'block'        => esc_html__('Block', 'element-ready-lite'),
                    'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
                    'flex'         => esc_html__('Flex', 'element-ready-lite'),
                    'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
                    'none'         => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => 'display: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_button_text_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => 'text-align: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'tab_button_typography',
                'selector' => '{{WRAPPER}} .filter__menu li',
            ]
        );
        $this->add_control(
            'tab_button_color',
            [
                'label'     => esc_html__('Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'tab_button_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%', 'vw'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 9999,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'tab_button_background_color',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .filter__menu li',
            ]
        );
        $this->add_responsive_control(
            'tab_button_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control(
            'tab_button_padding',
            [
                'label'   => esc_html__('Padding', 'element-ready-lite'),
                'type'    => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .filter__menu li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'tab_button_border',
                'selector' => '{{WRAPPER}} .filter__menu li',
            ]
        );
        $this->add_control(
            'tab_button_radius',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tab_button_box_shadow',
                'selector' => '{{WRAPPER}} .filter__menu li',
            ]
        );
        $this->add_responsive_control(
            'tab_button_custom_css',
            [
                'label'     => esc_html__('Custom CSS', 'element-ready-lite'),
                'type'      => Controls_Manager::CODE,
                'rows'      => 20,
                'language'  => 'css',
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => '{{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            'tab_button_hover_color',
            [
                'label'     => esc_html__('Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li:hover, {{WRAPPER}} .filter__menu li.active' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'tab_button_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .filter__menu li:hover, {{WRAPPER}} .filter__menu li.active',
            ]
        );

        $this->add_control(
            'tab_button_hover_border_color',
            [
                'label'     => esc_html__('Border Color', 'element-ready-lite'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li:hover, {{WRAPPER}} .filter__menu li.active' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'tab_button_hover_box_shadow',
                'selector' => '{{WRAPPER}} .filter__menu li:hover, {{WRAPPER}} .filter__menu li.active',
            ]
        );
        $this->add_responsive_control(
            'tab_button_hover_custom_css',
            [
                'label'     => esc_html__('Custom CSS', 'element-ready-lite'),
                'type'      => Controls_Manager::CODE,
                'rows'      => 20,
                'language'  => 'css',
                'selectors' => [
                    '{{WRAPPER}} .filter__menu li' => '{{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*-------------------------
            MENU ITEM STYLE END
        ---------------------------*/


        /*-----------------------
            BOX STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_slider_content_box',
            [
                'label'     => esc_html__('Box', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'box_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio',
            ]
        );

        $this->add_control(
            'box_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'box_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'box_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio',
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio' => 'overflow:hidden;border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'box_shadow',
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio',
            ]
        );

        $this->add_responsive_control(
            'box_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .slick-list' => 'margin: -{{TOP}}{{UNIT}} -{{RIGHT}}{{UNIT}} -{{BOTTOM}}{{UNIT}} -{{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->add_control(
            'box_item_margin_vartically',
            [
                'label'              => esc_html__('Item Margin Vartically', 'element-ready-lite'),
                'type'               => Controls_Manager::DIMENSIONS,
                'size_units'         => ['px', '%', 'em'],
                'allowed_dimensions' => ['top', 'bottom'],
                'selectors'          => [
                    '{{WRAPPER}} .element__ready__single__portfolio' => 'margin-top: {{TOP}}{{UNIT}}; margin-bottom:{{BOTTOM}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_nth_child_margin',
            [
                'label' => esc_html__('Nth Child 2 Margin Vartically', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio:nth-child(2n)' => 'margin-top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'box_item_hover_margin',
            [
                'label' => esc_html__('Item Hover Margin Vartically', 'element-ready-lite'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => -200,
                        'max' => 200,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio:hover' => 'transform: translateY({{SIZE}}{{UNIT}});',
                ],
            ]
        );

        $this->end_controls_section();
        /*-----------------------
            BOX STYLE END
        -------------------------*/

        /*-----------------------
            CONTENT STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_slider_content_style_section',
            [
                'label'     => esc_html__('Content', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_content' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'content_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'content_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content',
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'content_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'content_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content',
            ]
        );

        $this->add_responsive_control(
            'content_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content' => 'overflow:hidden;border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );
        $this->add_responsive_control(
            'content_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'content_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*-----------------------
            CONTENT STYLE END
        -------------------------*/

        /*-----------------------
            HOVER CONTENT STYLE
        -------------------------*/
        $this->start_controls_section(
            '_hover_content_style_section',
            [
                'label'     => esc_html__('Hover Content Box', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs(
            'hover_content_style_ijnerstyle_tabs'
        );


        $this->start_controls_tab(
            'hover_content_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            '_hover_content_title_color',
            [
                'label'  => esc_html__('Title Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .portfolio__title a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            '_hover_content_title_hover_color',
            [
                'label'  => esc_html__('Title Hover Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .portfolio__title a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_hover_content_title_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .portfolio__title',
            ]
        );

        $this->add_control(
            '_hover_content_readmore_color',
            [
                'label'  => esc_html__('Readmore Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .readmore__btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            '_hover_content_readmore_hover_color',
            [
                'label'  => esc_html__('Readmore Hover Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .readmore__btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_hover_content_readmore_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .readmore__btn',
            ]
        );

        $this->add_control(
            '_hover_content_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content .portfolio__category' => 'color: {{VALUE}}',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => '_hover_content_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content',
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => '_hover_content_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content,{{WRAPPER}} .element__ready__single__portfolio .portfolio__content',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => '_hover_content_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content, {{WRAPPER}} .element__ready__single__portfolio .portfolio__content',
            ]
        );

        $this->add_responsive_control(
            '_hover_content_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content' => 'overflow:hidden;border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            '_hover_content_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_hover_content_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            '_hover_content_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__hover__content' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'hover_content_layout_tab',
            [
                'label' => esc_html__('Layout', 'element-ready-lite'),
                'condition' => [
                    'content_layout_style' => ['4']
                ]
            ]
        );

        $this->add_control(
            'hover_content_layout_tab_style',
            [
                'label' => esc_html__('Display', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'flex'  => esc_html__('Flex', 'element-ready-lite'),
                    'inline-flex' => esc_html__('Inline Block', 'element-ready-litee'),
                    'block' => esc_html__('Block', 'element-ready-lite'),
                    'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
                    'none' => esc_html__('None', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'display: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'hover_content_layout_text_align',
            [
                'label' => esc_html__('Vertical Alignment', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                    'space-between' => [
                        'title' => esc_html__('Space Between', 'element-ready-lite'),
                        'icon' => ' eicon-justify-space-between-h',
                    ],
                ],
                'default' => 'left',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'hover_content_layout_direction',
            [
                'label' => esc_html__('Direction', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'row' => [
                        'title' => esc_html__('Row', 'element-ready-lite'),
                        'icon' => ' eicon-justify-space-between-h',
                    ],
                    'column' => [
                        'title' => esc_html__('Column', 'element-ready-lite'),
                        'icon' => 'eicon-justify-space-between-v',
                    ],

                ],
                'default' => 'column',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'flex-direction: {{VALUE}};',

                ],
                'condition' => [
                    'hover_content_layout_tab_style' => [
                        'flex', 'inline-flex'
                    ]
                ]
            ]
        );

        $this->add_control(
            'hover_content_layout_horit_align',
            [
                'label' => esc_html__('Horizontal Alignment', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__('Start', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__('End', 'element-ready-lite'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => true,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'align-items: {{VALUE}};',

                ],
                'condition' => [
                    'hover_content_layout_tab_style' => [
                        'flex', 'inline-flex'
                    ]
                ]
            ]
        );

        $this->add_control(
            'hover_cointent_gap_flex',
            [
                'label' => esc_html__('Gap', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 300,
                        'step' => 5,
                    ],

                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 30,
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'hover_content_layout_tab_style' => [
                        'flex', 'inline-flex'
                    ]
                ]
            ]
        );

        $this->add_responsive_control(
            'hover_cointent__width_flex',
            [
                'label' => esc_html__('Width', 'element-ready-lite'),
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 900,
                        'step' => 5,
                    ],

                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__inner' => 'width: {{SIZE}}{{UNIT}};',
                ],

            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*-----------------------
            HOVER CONTENT STYLE END
        -------------------------*/

        /*-----------------------
            TITLE STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_slider_title_style_section',
            [
                'label'     => esc_html__('Title', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_title' => 'yes',
                ]
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content .portfolio__title a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'  => esc_html__('Hover Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content .portfolio__title a:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content .portfolio__title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content .portfolio__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content .portfolio__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'title_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__content .portfolio__title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*-----------------------
            TITLE STYLE END
        -------------------------*/

        /*-----------------------
            CATEGORY STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_slider_category_style_section',
            [
                'label'     => esc_html__('Category', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_category' => 'yes',
                ]
            ]
        );

        $this->start_controls_tabs('category_style_tabs');

        $this->start_controls_tab(
            'category_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'category_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category',
            ]
        );

        $this->add_control(
            'category_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'category_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'category_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category',
            ]
        );

        $this->add_responsive_control(
            'category_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'category_shadow',
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category',
            ]
        );

        $this->add_responsive_control(
            'category_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'category_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );


        $this->end_controls_tab(); // Normal Tab end

        $this->start_controls_tab(
            'category_style_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            'category_hover_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category:hover a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'category_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'category_hover_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category:hover',
            ]
        );

        $this->add_responsive_control(
            'category_hover_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'category_hover_shadow',
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__category:hover',
            ]
        );

        $this->end_controls_tab(); // Hover Tab end

        $this->end_controls_tabs();

        $this->end_controls_section();
        /*-----------------------
            CATEGORY STYLE END
        -------------------------*/

        /*-----------------------
            META STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_meta_style_section',
            [
                'label' => esc_html__('Meta', 'element-ready-lite'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'meta_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio ul.portfolio__meta li',
            ]
        );

        $this->add_control(
            'meta_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio ul.portfolio__meta'                           => 'color: {{VALUE}}',
                    '{{WRAPPER}} .element__ready__single__portfolio ul.portfolio__meta a' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio ul.portfolio__meta li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio ul.portfolio__meta li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'meta_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'start' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'end' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio ul.portfolio__meta' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        /*-----------------------
            META STYLE END
        -------------------------*/

        /*-----------------------
            READMORE STYLE
        -------------------------*/
        $this->start_controls_section(
            'post_slider_readmore_style_section',
            [
                'label'     => esc_html__('Read More', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'show_read_more_btn' => 'yes',
                ]
            ]
        );

        $this->start_controls_tabs('readmore_style_tabs');

        $this->start_controls_tab(
            'readmore_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'readmore_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'readmore_typography',
                'label'    => esc_html__('Typography', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn',
            ]
        );

        $this->add_responsive_control(
            'readmore_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'readmore_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'readmore_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'readmore_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn',
            ]
        );

        $this->add_responsive_control(
            'readmore_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'readmore_shadow',
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn',
            ]
        );

        $this->end_controls_tab(); // Normal Tab end

        $this->start_controls_tab(
            'readmore_style_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );
        $this->add_control(
            'readmore_hover_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'readmore_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'readmore_hover_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn:hover',
            ]
        );

        $this->add_responsive_control(
            'readmore_hover_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'readmore_hover_shadow',
                'selector' => '{{WRAPPER}} .element__ready__single__portfolio .portfolio__btn a.readmore__btn:hover',
            ]
        );

        $this->end_controls_tab(); // Hover Tab end

        $this->end_controls_tabs();

        $this->end_controls_section();

        /*-----------------------
            READMORE STYLE END
        -------------------------*/

        /*----------------------------
            SLIDER NAV WARP
        -----------------------------*/
        $this->start_controls_section(
            'slider_control_warp_style_section',
            [
                'label' => esc_html__('Slider Arrow Warp', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );

        // Background
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slider_nav_warp_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
            ]
        );

        // Border
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slider_nav_warp_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
            ]
        );

        // Border Radius
        $this->add_control(
            'slider_nav_warp_radius',
            [
                'label'      => esc_html__('Border Radius', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Shadow
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_nav_warp_shadow',
                'selector' => '{{WRAPPER}} .sldier-content-area .owl-nav',
            ]
        );

        // Display;
        $this->add_responsive_control(
            'slider_nav_warp_display',
            [
                'label'   => esc_html__('Display', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'initial'      => esc_html__('Initial', 'element-ready-lite'),
                    'block'        => esc_html__('Block', 'element-ready-lite'),
                    'inline-block' => esc_html__('Inline Block', 'element-ready-lite'),
                    'flex'         => esc_html__('Flex', 'element-ready-lite'),
                    'inline-flex'  => esc_html__('Inline Flex', 'element-ready-lite'),
                    'none'         => esc_html__('none', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'display: {{VALUE}};',
                ],
            ]
        );

        // Before Postion
        $this->add_responsive_control(
            'slider_nav_warp_position',
            [
                'label'   => esc_html__('Position', 'element-ready-lite'),
                'type'    => Controls_Manager::SELECT,
                'default' => '',

                'options' => [
                    'initial'  => esc_html__('Initial', 'element-ready-lite'),
                    'absolute' => esc_html__('Absolute', 'element-ready-lite'),
                    'relative' => esc_html__('Relative', 'element-ready-lite'),
                    'static'   => esc_html__('Static', 'element-ready-lite'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'position: {{VALUE}};',
                ],
            ]
        );

        // Postion From Left
        $this->add_responsive_control(
            'slider_nav_warp_position_from_left',
            [
                'label'      => esc_html__('From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'left: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );

        // Postion From Right
        $this->add_responsive_control(
            'slider_nav_warp_position_from_right',
            [
                'label'      => esc_html__('From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'right: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );

        // Postion From Top
        $this->add_responsive_control(
            'slider_nav_warp_position_from_top',
            [
                'label'      => esc_html__('From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'top: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );

        // Postion From Bottom
        $this->add_responsive_control(
            'slider_nav_warp_position_from_bottom',
            [
                'label'      => esc_html__('From Bottom', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'bottom: {{SIZE}}{{UNIT}};',
                ],
                'condition' => [
                    'slider_nav_warp_position' => ['absolute', 'relative']
                ],
            ]
        );

        // Align
        $this->add_responsive_control(
            'slider_nav_warp_align',
            [
                'label'   => esc_html__('Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justify', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'text-align: {{VALUE}};',
                ],
                'default' => '',
            ]
        );

        // Width
        $this->add_responsive_control(
            'slider_nav_warp_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Height
        $this->add_responsive_control(
            'slider_nav_warp_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Opacity
        $this->add_control(
            'slider_nav_warp_opacity',
            [
                'label' => esc_html__('Opacity', 'element-ready-lite'),
                'type'  => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max'  => 1,
                        'min'  => 0.10,
                        'step' => 0.01,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'opacity: {{SIZE}};',
                ],
            ]
        );

        // Z-Index
        $this->add_control(
            'slider_nav_warp_zindex',
            [
                'label'     => esc_html__('Z-Index', 'element-ready-lite'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => -99,
                'max'       => 99,
                'step'      => 1,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'z-index: {{SIZE}};',
                ],
            ]
        );

        // Margin
        $this->add_responsive_control(
            'slider_nav_warp_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        // Padding
        $this->add_responsive_control(
            'slider_nav_warp_padding',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        /*----------------------------
            SLIDER NAV WARP END
        -----------------------------*/

        /*------------------------
             ARROW STYLE
        --------------------------*/
        $this->start_controls_section(
            'slider_arrow_style',
            [
                'label'     => esc_html__('Arrow', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                    'slarrows'  => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('slider_arrow_style_tabs');

        // Normal tab Start
        $this->start_controls_tab(
            'slider_arrow_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'slider_arrow_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_arrow_fontsize',
            [
                'label'      => esc_html__('Font Size', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 100,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slider_arrow_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slider_arrow_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow',
            ]
        );

        $this->add_responsive_control(
            'slider_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_arrow_shadow',
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow',
            ]
        );

        $this->add_responsive_control(
            'slider_arrow_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_arrow_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_arrow_padding',
            [
                'label'      => esc_html__('Padding', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
                'separator' => 'before',
            ]
        );

        // Postion From Left
        $this->add_responsive_control(
            'slide_button_position_from_left',
            [
                'label'      => esc_html__('Left Arrow Position From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'position:absolute;left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Postion Bottom Top
        $this->add_responsive_control(
            'slide_button_position_from_bottom',
            [
                'label'      => esc_html__('Left Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-prev' => 'position:absolute;top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Postion From Left
        $this->add_responsive_control(
            'slide_button_position_from_right',
            [
                'label'      => esc_html__('Right Arrow Position From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'position:absolute;right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Postion Bottom Top
        $this->add_responsive_control(
            'slide_button_position_from_top',
            [
                'label'      => esc_html__('Right Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .owl-nav > div.owl-next' => 'position:absolute;top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // Normal tab end

        // Hover tab Start
        $this->start_controls_tab(
            'slider_arrow_style_hover_tab',
            [
                'label' => esc_html__('Hover', 'element-ready-lite'),
            ]
        );

        $this->add_control(
            'slider_arrow_hover_color',
            [
                'label'  => esc_html__('Color', 'element-ready-lite'),
                'type'   => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow:hover' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'slider_arrow_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow:hover',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'slider_arrow_hover_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow:hover',
            ]
        );

        $this->add_responsive_control(
            'slider_arrow_hover_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-arrow:hover' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'slider_arrow_hover_shadow',
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-arrow:hover',
            ]
        );

        // Postion From Left
        $this->add_responsive_control(
            'slide_button_hover_position_from_left',
            [
                'label'      => esc_html__('Left Arrow Position From Left', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-prev' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Postion Bottom Top
        $this->add_responsive_control(
            'slide_button_hover_position_from_bottom',
            [
                'label'      => esc_html__('Left Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-prev' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );


        // Postion From Left
        $this->add_responsive_control(
            'slide_button_hover_position_from_right',
            [
                'label'      => esc_html__('Right Arrow Position From Right', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-next' => 'right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        // Postion Bottom Top
        $this->add_responsive_control(
            'slide_button_hover_position_from_top',
            [
                'label'      => esc_html__('Right Arrow Position From Top', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area:hover .owl-nav > div.owl-next' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_tab(); // Hover tab end

        $this->end_controls_tabs();

        $this->end_controls_section(); // Style Slider arrow style end
        /*------------------------
             ARROW STYLE END
        --------------------------*/

        /*------------------------
             DOTS STYLE
        --------------------------*/
        $this->start_controls_section(
            'post_slider_pagination_style_section',
            [
                'label'     => esc_html__('Pagination', 'element-ready-lite'),
                'tab'       => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'slider_on' => 'yes',
                    'sldots'  => 'yes',
                ],
            ]
        );

        $this->start_controls_tabs('pagination_style_tabs');

        $this->start_controls_tab(
            'pagination_style_normal_tab',
            [
                'label' => esc_html__('Normal', 'element-ready-lite'),
            ]
        );

        $this->add_responsive_control(
            'slider_pagination_height',
            [
                'label'      => esc_html__('Height', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'slider_pagination_width',
            [
                'label'      => esc_html__('Width', 'element-ready-lite'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 15,
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pagination_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li',
            ]
        );

        $this->add_responsive_control(
            'pagination_margin',
            [
                'label'      => esc_html__('Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li',
            ]
        );

        $this->add_responsive_control(
            'pagination_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagination_warp_margin',
            [
                'label'      => esc_html__('Pagination Warp Margin', 'element-ready-lite'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors'  => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'pagi_war_align',
            [
                'label'   => esc_html__('Pagination Warp Alignment', 'element-ready-lite'),
                'type'    => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__('Left', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__('Center', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right' => [
                        'title' => esc_html__('Right', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-right',
                    ],
                    'justify' => [
                        'title' => esc_html__('Justified', 'element-ready-lite'),
                        'icon'  => 'fa fa-align-justify',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_tab(); // Normal Tab end

        $this->start_controls_tab(
            'pagination_style_active_tab',
            [
                'label' => esc_html__('Active', 'element-ready-lite'),
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'     => 'pagination_hover_background',
                'label'    => esc_html__('Background', 'element-ready-lite'),
                'types'    => ['classic', 'gradient'],
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li:hover, {{WRAPPER}} .sldier-content-area .slick-dots li.slick-active',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'pagination_hover_border',
                'label'    => esc_html__('Border', 'element-ready-lite'),
                'selector' => '{{WRAPPER}} .sldier-content-area .slick-dots li:hover, {{WRAPPER}} .sldier-content-area .slick-dots li.slick-active',
            ]
        );

        $this->add_responsive_control(
            'pagination_hover_border_radius',
            [
                'label'     => esc_html__('Border Radius', 'element-ready-lite'),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .sldier-content-area .slick-dots li.slick-active' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                    '{{WRAPPER}} .sldier-content-area .slick-dots li:hover'        => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px;',
                ],
            ]
        );

        $this->end_controls_tab(); // Hover Tab end

        $this->end_controls_tabs();

        $this->end_controls_section();
        /*------------------------
             DOTS STYLE END
        --------------------------*/
    }

    protected function render($instance = [])
    {

        $settings = $this->get_settings_for_display();

        $custom_order_ck = $this->get_settings_for_display('custom_order');
        $orderby         = $this->get_settings_for_display('orderby');
        $postorder       = $this->get_settings_for_display('postorder');
        $gallery_id      = $this->get_id();

        $this->add_render_attribute('content_main_wrap_attr', 'class', 'sldier-content-area');
        $this->add_render_attribute('content_main_wrap_attr', 'class', 'element__ready__portfolio__content__layout__' . $settings['content_layout_style']);
        $this->add_render_attribute('content_main_wrap_attr', 'class', esc_attr($settings['nav_position']));
        $this->add_render_attribute('content_single_item_attr', 'class', 'element__ready__single__portfolio element__ready__portfolio__layout__' . esc_attr($settings['content_layout_style']));

        // Slider options
        if ($settings['slider_on'] == 'yes') {
            $this->add_render_attribute('content_items_wrap_attr', 'class', 'element-ready-carousel-activation');
            $slideid = rand(2564, 1245);
            $slider_settings = [
                'slideid'         => $slideid,
                'arrows'          => ('yes' === $settings['slarrows']),
                'arrow_prev_txt'  => $settings['slprevicon'],
                'arrow_next_txt'  => $settings['slnexticon'],
                'dots'            => ('yes' === $settings['sldots']),
                'autoplay'        => ('yes' === $settings['slautolay']),
                'autoplay_speed'  => absint($settings['slautoplay_speed']),
                'animation_speed' => absint($settings['slanimation_speed']),
                'pause_on_hover'  => ('yes' === $settings['slpause_on_hover']),
                'center_mode'     => ('yes' === $settings['slcentermode']),
                'center_padding'  => absint($settings['slcenterpadding']),
                'rows'            => absint($settings['slrows']),
                'fade'            => ('yes' === $settings['slfade']),
                'focusonselect'   => ('yes' === $settings['slfocusonselect']),
                'vertical'        => ('yes' === $settings['slvertical']),
                'rtl'             => ('yes' === $settings['slrtl']),
                'infinite'        => ('yes' === $settings['slinfinite']),
            ];

            $slider_responsive_settings = [
                'display_columns'        => $settings['slitems'],
                'scroll_columns'         => $settings['slscroll_columns'],
                'tablet_width'           => $settings['sltablet_width'],
                'tablet_display_columns' => $settings['sltablet_display_columns'],
                'tablet_scroll_columns'  => $settings['sltablet_scroll_columns'],
                'mobile_width'           => $settings['slmobile_width'],
                'mobile_display_columns' => $settings['slmobile_display_columns'],
                'mobile_scroll_columns'  => $settings['slmobile_scroll_columns'],

            ];

            $slider_settings = array_merge($slider_settings, $slider_responsive_settings);

            $this->add_render_attribute('content_items_wrap_attr', 'data-settings', wp_json_encode($slider_settings));
        } else {
            $this->add_render_attribute('content_items_wrap_attr', 'class', 'element-ready-filter-activation');
            $this->add_render_attribute('content_items_wrap_attr', 'id', 'element__ready__gallery__activation__' . esc_attr($gallery_id));

            $gallery_settings = [
                'gallery_id'           => $gallery_id,
                'gallery_type'         => $settings['gallery_type'],
                'layout_mode'          => $settings['layout_mode'],
                'active_menu_category' => $settings['active_menu_category'] ? get_term($settings['active_menu_category'], 'portfolio_category')->slug : null,
            ];
            $this->add_render_attribute('content_items_wrap_attr', 'data-settings', wp_json_encode($gallery_settings));
        }

        if ('slider' == $settings['gallery_type']) {
            $gallery_settings = [
                'gallery_id'   => $gallery_id,
                'gallery_type' => $settings['gallery_type'],
            ];
            $this->add_render_attribute('content_items_wrap_attr', 'class', 'element-ready-filter-activation');
        }


        // Query
        $args = array(
            'post_type'           => !empty($settings['carousel_post_type']) ? $settings['carousel_post_type'] : 'post',
            'post_status'         => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page'      => !empty($settings['post_limit']) ? $settings['post_limit'] : 3,
            'order'               => $postorder
        );

        // Custom Order
        if ($custom_order_ck == 'yes') {
            $args['orderby']    = $orderby;
        }

        if (!empty($settings['carousel_prod_categories'])) {
            $get_categories = $settings['carousel_prod_categories'];
        } elseif (!empty($settings['portfolio_categorys'])) {
            $get_categories = $settings['portfolio_categorys'];
        } else {
            $get_categories = $settings['carousel_categories'];
        }

        $category__array = array(
            'post'      => 'category',
            'product'   => 'product_cat',
            'portfolio' => 'portfolio_category',
        );

        $carousel_cats = str_replace(' ', '', $get_categories);

        if (!empty($get_categories)) {
            if (is_array($carousel_cats) && count($carousel_cats) > 0) {
                $field_name         = is_numeric($carousel_cats[0]) ? 'term_id' : 'slug';
                $args['tax_query']  = array(
                    array(
                        'taxonomy'         => $category__array[$settings['carousel_post_type']],
                        'terms'            => $carousel_cats,
                        'field'            => $field_name,
                        'include_children' => false
                    )
                );
            }
        }
        $carousel_post = new \WP_Query($args);
?>
        <div <?php echo $this->get_render_attribute_string('content_main_wrap_attr'); ?>>
            <?php
            /**
             *  FILTER MENU GALLERY
             */
            $this->element_ready_gallery_filter_menu();
            ?>

            <div <?php echo $this->get_render_attribute_string('content_items_wrap_attr'); ?>>
                <?php
                $style_index = $settings['style_repeter'];
                if ($carousel_post->have_posts()) :
                    while ($carousel_post->have_posts()) :
                        $carousel_post->the_post();
                ?>
                        <?php
                        $items      = get_the_terms(get_the_id(), 'portfolio_category');
                        $slug_items = '';
                        if ($items) {
                            foreach ($items as $item) {

                                if (isset($item->slug)) {
                                    $slug_items .= $item->slug . ' ';
                                }
                            }
                        }
                        ?>
                        <?php

                        if (isset($style_index[$carousel_post->current_post])) {
                            $repeter_class = "elementor-repeater-item-" . $style_index[$carousel_post->current_post]['_id'] . " " . $style_index[$carousel_post->current_post]['columns_manager_columns'];
                        } else {
                            $repeter_class = '';
                        }

                        ?>
                        <?php if ($settings['content_layout_style'] == 1) : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <?php $this->element_ready_render_loop_content(1); ?>
                                </div>
                            </div>

                        <?php elseif ($settings['content_layout_style'] == 2) : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent  <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <div class="portfolio__carousel__flex">
                                        <?php $this->element_ready_render_loop_content(1); ?>
                                    </div>
                                </div>
                            </div>

                        <?php elseif ($settings['content_layout_style'] == 5) : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <?php $this->element_ready_render_loop_content(5); ?>
                                </div>
                            </div>

                        <?php elseif ($settings['content_layout_style'] == 6) : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <?php $this->element_ready_render_loop_content(6); ?>
                                </div>
                            </div>

                        <?php elseif ($settings['content_layout_style'] == 7) : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent <?php echo esc_attr($repeter_class) ?> <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <?php $this->element_ready_render_loop_content(6); ?>
                                </div>
                            </div>

                        <?php elseif ($settings['content_layout_style'] == 8) : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <?php $this->element_ready_render_loop_content(6); ?>
                                </div>
                            </div>
                        <?php else : ?>
                            <div class="element__ready__grid__item__<?php echo esc_attr($gallery_id); ?> element__ready__portfolio__item__parent <?php echo esc_attr($slug_items); ?>">
                                <div <?php echo $this->get_render_attribute_string('content_single_item_attr'); ?>>
                                    <?php $this->element_ready_render_loop_content(1); ?>
                                </div>
                            </div>
                        <?php endif; ?>

                <?php endwhile;
                    wp_reset_postdata();
                    wp_reset_query();
                endif; ?>

            </div>

            <?php if ($settings['slarrows'] == 'yes' || $settings['sldots'] == 'yes') : ?>

                <div class="owl-controls">
                    <?php if ($settings['slarrows'] == 'yes') : ?>
                        <div class="element-ready-carousel-nav<?php echo esc_attr($slideid); ?> owl-nav"></div>
                    <?php endif; ?>

                    <?php if ($settings['sldots'] == 'yes') : ?>
                        <div class="element-ready-carousel-dots<?php echo esc_attr($slideid); ?> owl-dots"></div>
                    <?php endif; ?>
                </div>

            <?php endif; ?>

        </div>
    <?php
    }

    public function element_ready_gallery_filter_menu()
    {
        $settings   = $this->get_settings_for_display();
        $gallery_id = $this->get_id();

        if (!isset($settings['gallery_menu'])) {
            return;
        }
    ?>
        <?php if ('yes' == $settings['gallery_menu']) : ?>
            <div class="filter__menu" id="filter__menu__<?php echo esc_attr($gallery_id); ?>">
                <ul>
                    <?php if ('yes' == $settings['show_all_menu']) : ?>
                        <li class="filter active" data-filter="*"><?php esc_html_e('All', 'element-ready-lite'); ?></li>
                    <?php endif; ?>
                    <?php
                    $menu_array = $settings['portfolio_categorys'];
                    ?>
                    <?php if ($menu_array) : ?>
                        <?php foreach ($menu_array as $menu) : ?>
                            <?php $term_name = get_term($menu); ?>
                            <li class="filter" data-filter=".<?php echo esc_attr(strtolower($term_name->slug)); ?>"><?php echo esc_html($term_name->name); ?></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        <?php endif; ?>
    <?php
    }

    // Loop Content
    public function element_ready_render_loop_content($contetntstyle = NULL)
    {
        $settings   = $this->get_settings_for_display();
        $inner_display = $settings['hover_content_layout_tab_style'];
    ?>
        <?php if ($contetntstyle == 1) : ?>

            <?php $this->element_ready_post_thumbnail(); ?>
            <div class="portfolio__content">
                <div class="portfolio__inner">
                    <?php if ($inner_display == 'flex' || $inner_display == 'inline-flex') :  ?>
                        <div class="er-cat-title-wrapper">
                        <?php endif; ?>
                        <?php $this->element_ready_post_title(); ?>
                        <?php $this->element_ready_post_category(); ?>
                        <?php if ($inner_display == 'flex' || $inner_display == 'inline-flex') :  ?>
                        </div>
                    <?php endif; ?>
                    <?php $this->element_ready_post_content(); ?>
                    <?php $this->element_ready_post_readmore(); ?>
                </div>
            </div>

        <?php elseif ($contetntstyle == 5) : ?>

            <?php $this->element_ready_post_thumbnail(); ?>
            <div class="portfolio__content">
                <div class="portfolio__inner">
                    <?php $this->element_ready_post_title(); ?>
                    <?php $this->element_ready_post_category(); ?>
                </div>
            </div>

            <div class="portfolio__hover__content">
                <div class="portfolio__inner">
                    <?php $this->element_ready_post_title(); ?>
                    <?php $this->element_ready_post_category(); ?>
                    <?php $this->element_ready_post_readmore(); ?>
                </div>
            </div>

        <?php elseif ($contetntstyle == 6) : ?>
            <?php $this->element_ready_post_thumbnail(); ?>
            <div class="portfolio__content">
                <div class="portfolio__inner">
                    <?php $this->element_ready_post_title(); ?>
                    <?php $this->element_ready_post_category(); ?>
                    <?php $this->element_ready_post_readmore(); ?>
                </div>
            </div>

        <?php endif; ?>

    <?php
    }

    // Time Ago Content
    public function element_ready_time_ago()
    {
        return human_time_diff(get_the_time('U'), current_time('timestamp')) . ' ' . __('ago', 'element-ready-lite');
    }

    public function element_ready_post_thumbnail()
    {
        global $post;
        $settings   = $this->get_settings_for_display();
        $thumb_link  = Group_Control_Image_Size::get_attachment_image_src(get_post_thumbnail_id(get_the_ID()), 'thumb_size', $settings);
    ?>
        <?php if ('yes' == $settings['show_thumb'] && has_post_thumbnail()) : ?>
            <div class="portfolio__thumb">
                <a href="<?php the_permalink(); ?>"><img src="<?php echo esc_url($thumb_link) ?>" alt="<?php the_title_attribute(); ?>"></a>
            </div>
            <?php endif;
    }

    public function element_ready_post_category()
    {
        $settings   = $this->get_settings_for_display();
        if ($settings['show_category'] == 'yes') :
            $portfolio_category = get_the_terms(get_the_id(), 'portfolio_category');
            if ($portfolio_category) {
            ?>
                <div class="portfolio__category">
                    <?php echo wp_kses_post($this->element_ready_portfolio_category()); ?>
                </div>
        <?php
            }
        endif;
    }

    public function element_ready_post_title()
    {
        $settings   = $this->get_settings_for_display(); ?>
        <?php if ($settings['show_title'] == 'yes') : ?>
            <h3 class="portfolio__title"><a href="<?php the_permalink(); ?>"><?php echo wp_kses_post(wp_trim_words(get_the_title(), $settings['title_length'], '')); ?></a></h3>
        <?php endif;
    }

    public function element_ready_post_content()
    {
        $settings   = $this->get_settings_for_display();
        if ($settings['show_content'] == 'yes') {
            echo wp_kses_post(sprintf('<p>%s</p>', wp_trim_words(get_the_content(), $settings['content_length'], '')));
        }
    }

    public function element_ready_post_meta()
    {
        $settings   = $this->get_settings_for_display(); ?>
        <?php if ($settings['show_author'] == 'yes' || $settings['show_date'] == 'yes') : ?>
            <ul class="portfolio__meta">
                <?php if ($settings['show_author'] == 'yes') : ?>
                    <li><i class="fa fa-user-circle"></i><a href="<?php echo esc_url(get_author_posts_url(get_the_author_meta('ID'), get_the_author_meta('user_nicename'))); ?>"><?php the_author(); ?></a></li>
                <?php endif; ?>
                <?php if ($settings['show_date'] == 'yes') : ?>

                    <?php if ('date' == $settings['date_type']) : ?>
                        <li><i class="fa fa-clock-o"></i><?php the_time(esc_html__('d F Y', 'element-ready-lite')); ?></li>
                    <?php endif; ?>

                    <?php if ('time' == $settings['date_type']) : ?>
                        <li><i class="fa fa-clock-o"></i><?php the_time(); ?></li>
                    <?php endif; ?>

                    <?php if ('time_ago' == $settings['date_type']) : ?>
                        <li><i class="fa fa-clock-o"></i><?php echo wp_kses_post($this->element_ready_time_ago()); ?></li>
                    <?php endif; ?>

                    <?php if ('date_time' == $settings['date_type']) : ?>
                        <li><i class="fa fa-clock-o"></i><?php echo wp_kses_post(get_the_time('d F y - D g:i:a')); ?></li>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        <?php endif;
    }

    public function element_ready_post_readmore()
    {
        $settings   = $this->get_settings_for_display(); ?>
        <?php if ($settings['show_read_more_btn'] == 'yes') : ?>
            <div class="portfolio__btn">
                <?php if (!empty($settings['readmore_icon'])) : ?>
                    <?php if ('right'  == $settings['readmore_icon_position']) : ?>
                        <a class="readmore__btn" href="<?php the_permalink(); ?>"><?php echo esc_html($settings['read_more_txt']); ?> <i class="readmore_icon_right <?php echo esc_attr($settings['readmore_icon']) ?>"></i></a>
                    <?php elseif ('left'  == $settings['readmore_icon_position']) : ?>
                        <a class="readmore__btn" href="<?php the_permalink(); ?>"><i class="readmore_icon_left <?php echo esc_attr($settings['readmore_icon']) ?>"></i><?php echo esc_html($settings['read_more_txt']); ?></a>
                    <?php endif; ?>
                <?php else : ?>
                    <a class="readmore__btn" href="<?php the_permalink(); ?>"><?php echo esc_html($settings['read_more_txt']); ?></a>
                <?php endif; ?>
            </div>
<?php endif;
    }
}
