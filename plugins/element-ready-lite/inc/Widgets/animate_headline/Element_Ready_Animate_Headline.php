<?php
namespace Element_Ready\Widgets\animate_headline;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Modules\DynamicTags\Module as TagsModule;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Repeater;
use \Element_Ready\Base\Controls\Widget_Control\Element_ready_common_control as Content_Style;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Element_Ready_Animate_Headline extends Widget_Base {

    use Content_Style;
    public function get_name() {
        return 'Element_Ready_Animate_Headline';
    }
    
    public function get_title() {
        return esc_html__( 'ER Animate Headline', 'element-ready-lite' );
    }

    public function get_icon() {
        return 'eicon-animated-headline';
    }
    
	public function get_categories() {
		return [ 'element-ready-addons' ];
	}

    public function get_script_depends() {
        return [
            'animatedheadline',
            'element-ready-core',
        ];
    }

    public function get_style_depends() {
        return [
            'animatedheadline',
        ];
    }

    static function content_layout_style(){
        return apply_filters( 'element_ready_animate_headline_style_presets', [
            'rotate-1' => esc_html__('Text Rotate','element-ready-lite' ),
            'push'     => esc_html__('Text Push','element-ready-lite' ),
        ]);
    }
    
    protected function register_controls() {

        $this->start_controls_section(
            '_content_section',
            [
                'label' => esc_html__( 'Content', 'element-ready-lite' ),
            ]
        );

            $this->add_control(
                'content_animate_layout',
                [
                    'label'       => esc_html__( 'Animate Style', 'element-ready-lite' ),
                    'description' => esc_html__( 'Select a word animation type by default ( Clip Text ) is set. Note: It\'s not working if you not add ( Animated Words )', 'element-ready-lite' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => self::content_layout_style(),
                    'default'     => 'rotate-1',
                ]
            );

            $this->add_control(
                'animate_title_before',
                [
                    'label'     => esc_html__( 'Animate Title Before', 'element-ready-lite' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => '',
                    'separator' => 'before',
                ]
            );
            $this->add_control(
                'animate_title_after',
                [
                    'label'     => esc_html__( 'Animate Title After', 'element-ready-lite' ),
                    'type'      => Controls_Manager::TEXT,
                    'default'   => '',
                    'separator' => 'before',
                ]
            );
        
            $repeater = new Repeater();

            $repeater->add_control(
                'animate_title',
                [
                    'label'   => esc_html__( 'Animate Title', 'element-ready-lite' ),
                    'type'    => Controls_Manager::TEXT,
                    'default' => '',
                ]
            );

            $this->add_control(
                'animate_text_list',
                [
                    'type'    => Controls_Manager::REPEATER,
                    'fields'  =>  $repeater->get_controls() ,
                    'default' => [
                        [
                            'animate_title' => esc_html__('Title #1','element-ready-lite'),
                        ],
                    ],
                    'title_field' => '{{{ animate_title }}}',
                    'separator'   => 'before',
                ]
            );

        $this->end_controls_section();

        /*----------------------------
            HEADLINE STYLE
        -----------------------------*/
        $this->start_controls_section(
            '_heading_style_section',
            [
                'label' => esc_html__( 'Heading', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => '_heading_typography',
                    'selector' => '{{WRAPPER}} .animate__text__headline h1',
                ]
            );
            $this->add_control(
                '_heading_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .animate__text__headline h1' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => '_heading_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .animate__text__headline',
                ]
            );

            $icon_opt = apply_filters( 'element_ready_animate_headline_heading_pro_message', $this->pro_message('heading_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_animate_headline_heading_styles', $this );

        $this->end_controls_section();
        /*----------------------------
            HEADLINE STYLE END
        -----------------------------*/

        /*----------------------------
            HEADLINE ANIMATE TEXT STYLE
        -----------------------------*/
        $this->start_controls_section(
            '_animate_text_style_section',
            [
                'label' => esc_html__( 'Animate Text', 'element-ready-lite' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Typography:: get_type(),
                [
                    'name'     => '_animate_text_typography',
                    'selector' => '{{WRAPPER}} .animate__text__headline h1 .animate__main__text',
                ]
            );
            $this->add_control(
                '_animate_text_color',
                [
                    'label'     => esc_html__( 'Color', 'element-ready-lite' ),
                    'type'      => Controls_Manager::COLOR,
                    'default'   => '',
                    'selectors' => [
                        '{{WRAPPER}} .animate__text__headline h1 .animate__main__text' => 'color: {{VALUE}};',
                    ],
                ]
            );
            $this->add_group_control(
                Group_Control_Background:: get_type(),
                [
                    'name'     => '_animate_text_background',
                    'label'    => esc_html__( 'Background', 'element-ready-lite' ),
                    'types'    => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .animate__text__headline h1 .animate__main__text',
                ]
            );
            
            $icon_opt = apply_filters( 'element_ready_animate_headline_text_pro_message', $this->pro_message('text_pro_messagte'), false );
            $this->run_controls( $icon_opt );
            do_action( 'element_ready_animate_headline_text_styles', $this );

        $this->end_controls_section();
        /*----------------------------
            HEADLINE ANIMATE TEXT STYLE END
        -----------------------------*/
    }

    protected function render( $instance = [] ) {
        $settings = $this->get_settings_for_display();
        $this->add_render_attribute( '_main_wrap_attr', 'class', 'animated__headline__area' );
        $this->add_render_attribute( '_animate_headline_active_attr', 'class', 'element__ready__animate__heading__activation' );

        $random_id        = rand(2564,1245);
        $animate_settings = [
            'random_id'    => $random_id,
            'animate_type' => $settings['content_animate_layout'],
        ];
        $this->add_render_attribute( '_animate_headline_active_attr', 'data-settings', wp_json_encode( $animate_settings ) );       
        $this->add_render_attribute( '_animate_headline_active_attr', 'class', 'animate__text__headline' );
        $this->add_render_attribute( '_animate_headline_active_attr', 'class', esc_attr($settings['content_animate_layout']) );
        $this->add_render_attribute( '_animate_headline_active_attr', 'id', 'animate__text__headline__'.esc_attr($random_id) );
        ?>
        <div <?php echo $this->get_render_attribute_string('_main_wrap_attr'); ?>>
                <div <?php echo $this->get_render_attribute_string('_animate_headline_active_attr'); ?>>
                    <h1 class="ah-headline">
                        <?php if(!empty($settings['animate_title_before'])): ?>
                        <span class="animate__headline__before"><?php echo esc_html( $settings['animate_title_before'] ); ?></span>
                        <?php endif; ?>
                        <span class="ah-words-wrapper animate__main__text">
                            <?php foreach ( $settings['animate_text_list'] as $key => $single_text ): ?>
                                <?php if( $key == 0 ): ?>
                                    <b class="is-visible"><?php echo esc_html( $single_text['animate_title'] ); ?></b>
                                <?php else: ?>
                                    <b><?php echo esc_html( $single_text['animate_title'] ); ?></b>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </span>
                        <?php if(!empty($settings['animate_title_after'])): ?>
                        <span class="animate__headline__after"><?php echo esc_html( $settings['animate_title_after'] ); ?></span>
                        <?php endif; ?>
                    </h1>
                </div>
        </div>
    <?php
    }
}