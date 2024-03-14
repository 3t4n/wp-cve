<?php
namespace MegaElementsAddonsForElementor\Widget;

// If this file is called directly, abort.
if (!defined('ABSPATH')) {
    exit;
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;
use Elementor\Group_Control_Image_Size;

class MEAFE_Category extends Widget_Base
{

    public function get_name() {
        return 'meafe-category';
    }

    public function get_title() {
        return esc_html__( 'Category', 'mega-elements-addons-for-elementor' );
    }

    public function get_icon() {
        return 'meafe-category';
    }

    public function get_categories() {
        return ['meafe-elements'];
    }

    public function get_style_depends() {
        return ['meafe-category'];
    }

    protected function query_controls()
    {
        
        /**
         * Category Query Settings
        */
        $taxonomies = get_taxonomies( [], 'objects' );

        $this->start_controls_section(
            'meafe_category_content_query_settings',
            [
                'label'     => esc_html__( 'Query Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );

        foreach ($taxonomies as $taxonomy => $object) {
            if( in_array( $taxonomy, array( 'category' ) ) ) :
                $this->add_control(
                    'bccqs_category_' . $taxonomy . '_ids',
                    [
                        'label'         => $object->label,
                        'type'          => Controls_Manager::SELECT2,
                        'label_block'   => true,
                        'multiple'      => true,
                        'object_type'   => $taxonomy,
                        'options'       => wp_list_pluck( get_terms( $taxonomy ), 'name', 'term_id' ),
                    ]
                );
            endif;
        }

        $this->end_controls_section();
    }

    protected function layout_controls()
    {
        /**
         * Category Layout Settings
        */
        $this->start_controls_section(
            'meafe_category_content_layout_settings',
            [
                'label'     => esc_html__( 'Layout Settings', 'mega-elements-addons-for-elementor' ),
            ]
        );
        
        $this->add_control(
            'bccls_category_layout',
            [
                'label'     => esc_html__( 'Select Layout', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => 'ba-category-one',
                'options'   => [
                    'ba-category-one'   => esc_html__( 'Layout One', 'mega-elements-addons-for-elementor' ),
                    'ba-category-two'   => esc_html__( 'Layout Two', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'bccls_category_per_row',
            [
                'label'     => esc_html__( 'Category Per Row', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SELECT,
                'default'   => '3',
                'options'   => [
                    '1'    => esc_html__( 'One', 'mega-elements-addons-for-elementor' ),
                    '2'    => esc_html__( 'Two', 'mega-elements-addons-for-elementor' ),
                    '3'    => esc_html__( 'Three', 'mega-elements-addons-for-elementor' ),
                    '4'    => esc_html__( 'Four', 'mega-elements-addons-for-elementor' ),
                    '5'    => esc_html__( 'Five', 'mega-elements-addons-for-elementor' ),
                    '6'    => esc_html__( 'Six', 'mega-elements-addons-for-elementor' ),
                ],
            ]
        );

        $this->add_control(
            'bccls_category_show_image',
            [
                'label'     => esc_html__( 'Show Image', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'bccls_category_image',
                'default'   => 'medium',
                'condition' => [
                    'bccls_category_show_image' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'bccls_category_show_meta',
            [
                'label'     => esc_html__( 'Show Meta', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );

        $this->add_control(
            'bccls_category_show_in_new_tab',
            [
                'label'     => esc_html__( 'Open in new tab', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::SWITCHER,
                'label_on'  => esc_html__( 'Show', 'mega-elements-addons-for-elementor' ),
                'label_off' => esc_html__( 'Hide', 'mega-elements-addons-for-elementor' ),
                'return_value' => 'yes',
                'default'   => 'yes',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {
        /**
         * Query Controls
         */
        $this->query_controls();

        /**
         * Layout Controls
         */
        $this->layout_controls();

        /**
         * General Style Controls
         */
        $this->start_controls_section(
            'meafe_category_style_general_style',
            [
                'label'     => esc_html__( 'General Style', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name'      => 'bcsgs_category_bg_color',
                'label'     => __( 'Post Background Color', 'mega-elements-addons-for-elementor' ),
                'types'     => ['gradient'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-categories-wrap .title-wrapper' => 'background: {{VALUE}}',
                ],
                'exclude'   => [
                    'image',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsgs_category_padding',
            [
                'label'     => esc_html__( 'Padding', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-categories-wrap .title-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'bcsgs_category_margin',
            [
                'label'     => esc_html__( 'Margin', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%', 'em'],
                'selectors' => [
                    '{{WRAPPER}} .meafe-categories-wrap .title-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'bcsgs_category_border',
                'label'     => esc_html__( 'Border', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-categories-meta-wrap li a',
            ]
        );

        $this->add_control(
            'bcsgs_category_border_radius',
            [
                'label'     => esc_html__( 'Border Radius', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::DIMENSIONS,
                'selectors' => [
                    '{{WRAPPER}} .meafe-categories-meta-wrap li a' => 'border-radius: {{TOP}}px {{RIGHT}}px {{BOTTOM}}px {{LEFT}}px',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'      => 'bcsgs_category_box_shadow',
                'selector'  => '{{WRAPPER}} .category-fallback-image > a',
            ]
        );

        $this->end_controls_section();

        /**
         * Color & Typography Style
         */
        $this->start_controls_section(
            'meafe_category_style_color_typography_style',
            [
                'label'     => esc_html__( 'Color & Typography', 'mega-elements-addons-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'bcscts_category_title_style',
            [
                'label'     => esc_html__( 'Title Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcscts_category_title_color',
            [
                'label'     => esc_html__( 'Title Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-title, {{WRAPPER}} .ba-category-one .meafe-entry-title' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_control(
            'bcscts_category_title_hover_color',
            [
                'label'     => esc_html__( 'Title Hover Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-title:hover, {{WRAPPER}} .ba-category-one .meafe-entry-title:hover' => 'color: {{VALUE}}',
                ],

            ]
        );

        $this->add_responsive_control(
            'bcscts_category_title_alignment',
            [
                'label'     => esc_html__( 'Title Alignment', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'      => [
                        'title' => esc_html__( 'Left', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-left',
                    ],
                    'center'    => [
                        'title' => esc_html__( 'Center', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-center',
                    ],
                    'right'     => [
                        'title' => esc_html__( 'Right', 'mega-elements-addons-for-elementor' ),
                        'icon'  => 'fa fa-align-right',
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-title' => 'text-align: {{VALUE}}',
                    '{{WRAPPER}} .meafe-entry-meta' => 'text-align: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcscts_category_title_typography',
                'label'     => esc_html__( 'Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-entry-title',
            ]
        );

        $this->add_control(
            'bcscts_category_meta_style',
            [
                'label'     => esc_html__( 'Meta Style', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'bcscts_category_meta_color',
            [
                'label'     => esc_html__( 'Meta Color', 'mega-elements-addons-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .meafe-entry-meta, {{WRAPPER}} .ba-category-one .meafe-entry-meta' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'bcscts_category_meta_typography',
                'label'     => esc_html__( 'Meta Typography', 'mega-elements-addons-for-elementor' ),
                'selector'  => '{{WRAPPER}} .meafe-entry-meta',
            ]
        );

        $this->end_controls_section();
    }

    public function render_category_template( $settings )
    {

        ob_start();
        $target = 'target="_self"';
        if( isset( $settings['bccls_category_show_in_new_tab'] ) && $settings['bccls_category_show_in_new_tab'] !='' ){
            $target = 'target="_blank"';
        }
        echo '<div class="meafe-categories-wrap">';
        echo '<ul class="meafe-categories-meta-wrap">';

        $cats[] = '1';
        if( isset( $settings['bccqs_category_category_ids'] ) &&  $settings['bccqs_category_category_ids'] !='' ){
            $cats[] = '';
            $cats = $settings['bccqs_category_category_ids'];
        }
        $ccw_img_size = $settings['bccls_category_image_size'];
        foreach ( $cats as $key => $value ) 
        {
            $img = get_term_meta( $value, 'ba_category_image_id', false );
            $category = get_category( $value );
            if( $category )
            {
                $count = $category->category_count;

                if( $settings['bccls_category_show_image'] && isset( $img ) && is_array( $img ) && isset( $img[0] ) && $img[0] !='' )
                {
                    $url1 = wp_get_attachment_image_url( $img[0], $ccw_img_size );

                    echo '<li class="category-fallback-image">';
                    echo '<a '.$target.' href="'. esc_url( get_category_link( $value ) ) .'"><img src="' . esc_url( $url1 ) . '" alt="'. esc_attr( get_cat_name( $value ) ) .'"><div class="title-wrapper"><span class="meafe-entry-title">'.esc_html( get_cat_name( $value ) ).'</span>';
                    if( $settings['bccls_category_show_meta'] && $count > 0 ) {
                        echo '<span class="meafe-entry-meta">'.esc_html( $count ).__(' Post(s)','mega-elements-addons-for-elementor').'</span>';
                    }

                    echo '</div></a></li>';
                }
                else
                {
                    $image_size = meafe_get_image_sizes( $ccw_img_size );
                    $svg_fill   = 'fill:#f2f2f2;';
                    if( $image_size ){ 
                        $url1 = ("<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 ".$image_size['width']." ".$image_size['height']."' preserveAspectRatio='none'><rect width='".$image_size['width']."' height='".$image_size['height']."' style='".$svg_fill."'></rect></svg>");
                    }
                    echo '<li class="category-fallback-svg">';
                    echo '<a '.$target.' href="'. esc_url( get_category_link( $value ) ) .'">' . $url1 . '<div class="title-wrapper"><span class="meafe-entry-title">'.esc_html( get_cat_name( $value ) ).'</span>';
                    if( $settings['bccls_category_show_meta'] && $count > 0 ) {
                        echo '<span class="meafe-entry-meta">'.esc_html( $count ).__(' Post(s)','mega-elements-addons-for-elementor').'</span>';
                    }
                    echo '</div></a></li>';
                }
            }
        }
        echo '</ul></div>';
        return ob_get_clean();
    }

    protected function render()
    {
        $settings = $this->get_settings();

        $this->add_render_attribute(
            'category_wrapper',
            [
                'id' => 'meafe-post-grid-' . esc_attr( $this->get_id() ),
                'class' => [
                    'meafe-post-grid-container',
                    $settings['bccls_category_layout'],
                    'col-' . $settings['bccls_category_per_row'],
                ],
            ]
        );

        echo '<div ' . $this->get_render_attribute_string( 'category_wrapper' ) . '>
            <div class="meafe-post-grid meafe-post-appender meafe-post-appender-' . esc_attr( $this->get_id() ) . '">
                ' . self::render_category_template( $settings ) . '
            </div>
            <div class="clearfix"></div>
        </div>';
    }

    protected function content_template() {
    }
}