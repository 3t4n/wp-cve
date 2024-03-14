<?php
/**
 * Photo Stack widget class
 *
 * @package Skt_Addons
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use Elementor\Utils;

defined('ABSPATH') || die();

class Photo_Stack extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __('Photo Stack', 'skt-addons-elementor');
    }

    public function get_custom_help_url() {
    }

    /**
     * Get widget icon.
     *
     * @since 1.0.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-card';
    }

    public function get_keywords() {
        return ['photo', 'img-box', 'photo-gallery'];
    }

    /**
     * Register widget content controls
     */
    protected function register_content_controls() {
        $this->__photo_stack_content_controls();
    }

    protected function __photo_stack_content_controls() {
        $this->start_controls_section(
            '_section_photo_stack',
            [
                'label' => __('Photo Stack', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'image',
            [
                'label'   => __('Image', 'skt-addons-elementor'),
                'type'    => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );
        $repeater->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name'      => 'thumbnail',
                'default'   => 'medium',
                'separator' => 'before',
                'dynamic'   => [
                    'active' => true,
                ],
            ]
        );

        /*$repeater->add_control(
			'link',
			[
				'label' => __( 'Link', 'skt-addons-elementor' ),
				'type' => Controls_Manager::URL,
				'label_block' => true,
				'placeholder' => 'https://example.com',
				'dynamic' => [
					'active' => true,
				]
			]
		);*/
		
        $repeater->add_responsive_control(
            'image_offset_y',
            [
                'label'      => __('Offset Y', 'skt-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .skt-photo-stack-item{{CURRENT_ITEM}}' => 'top: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'image_offset_x',
            [
                'label'      => __('Offset X', 'skt-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
                'range'      => [
                    'px' => [
                        'min'  => -1000,
                        'max'  => 1000,
                        'step' => 5,
                    ],
                    '%'  => [
                        'min' => -100,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'unit' => 'px',
                    'size' => 0,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .skt-photo-stack-item{{CURRENT_ITEM}}' => 'left: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $repeater->add_responsive_control(
            'image_z_index',
            [
                'label'     => esc_html__('Z-Index', 'skt-addons-elementor'),
                'type'      => Controls_Manager::NUMBER,
                'min'       => -1000,
                'max'       => 1000,
                'step'      => 1,
                'selectors' => [
                    '{{WRAPPER}} .skt-photo-stack-item{{CURRENT_ITEM}}' => 'z-index: {{VALUE}};',
                ],
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'image_list',
            [
                'show_label'  => true,
                'label'       => __('Items', 'skt-addons-elementor'),
                'type'        => Controls_Manager::REPEATER,
                'fields'      => $repeater->get_controls(),
                'title_field' => '{{{ name }}}',
                'default'     => [
                    [
                        'image'                      => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'thumbnail_size'             => 'custom',
                        'thumbnail_custom_dimension' => [
                            'width'  => 200,
                            'height' => 200,
                        ],
                        'image_offset_y'             => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                        'image_offset_x'             => [
                            'size' => 35,
                            'unit' => 'px',
                        ],
                    ],
                    [
                        'image'                      => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'thumbnail_size'             => 'custom',
                        'thumbnail_custom_dimension' => [
                            'width'  => 300,
                            'height' => 300,
                        ],
                        'image_offset_y'             => [
                            'size' => 250,
                            'unit' => 'px',
                        ],
                        'image_offset_x'             => [
                            'size' => 0,
                            'unit' => 'px',
                        ],
                    ],
                    [
                        'image'                      => [
                            'url' => Utils::get_placeholder_image_src(),
                        ],
                        'thumbnail_size'             => 'custom',
                        'thumbnail_custom_dimension' => [
                            'width'  => 500,
                            'height' => 400,
                        ],
                        'image_offset_y'             => [
                            'size' => 100,
                            'unit' => 'px',
                        ],
                        'image_offset_x'             => [
                            'size' => 180,
                            'unit' => 'px',
                        ],
                    ],
                ],
            ]
        );

        $this->add_control(
            'image_infinite_animation',
            [
                'label'     => __('Infinite Animation', 'skt-addons-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    ''                    => __('None', 'skt-addons-elementor'),
                    'skt-bounce-sm'        => __('Bounce Small', 'skt-addons-elementor'),
                    'skt-bounce-md'        => __('Bounce Medium', 'skt-addons-elementor'),
                    'skt-bounce-lg'        => __('Bounce Large', 'skt-addons-elementor'),
                    'skt-fade'             => __('Fade', 'skt-addons-elementor'),
                    'skt-rotating'         => __('Rotating', 'skt-addons-elementor'),
                    'skt-rotating-inverse' => __('Rotating inverse', 'skt-addons-elementor'),
                    'skt-scale-sm'         => __('Scale Small', 'skt-addons-elementor'),
                    'skt-scale-md'         => __('Scale Medium', 'skt-addons-elementor'),
                    'skt-scale-lg'         => __('Scale Large', 'skt-addons-elementor'),
                ],
                'default'   => 'skt-bounce-sm',
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'hover_animation_style',
            [
                'label'     => __('Hover Animation', 'skt-addons-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'none'             => __('None', 'skt-addons-elementor'),
                    'fly-sm'           => __('Fly Small', 'skt-addons-elementor'),
                    'fly'              => __('Fly Medium', 'skt-addons-elementor'),
                    'fly-lg'           => __('Fly Large', 'skt-addons-elementor'),
                    'scale-sm'         => __('Scale Small', 'skt-addons-elementor'),
                    'scale'            => __('Scale Medium', 'skt-addons-elementor'),
                    'scale-lg'         => __('Scale Large', 'skt-addons-elementor'),
                    'scale-inverse-sm' => __('Scale Inverse Small', 'skt-addons-elementor'),
                    'scale-inverse'    => __('Scale Inverse Medium', 'skt-addons-elementor'),
                    'scale-inverse-lg' => __('Scale Inverse Large', 'skt-addons-elementor'),
                ],
                'default'   => 'scale-sm',
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'animation_speed',
            [
                'label'       => __('Animation speed', 'skt-addons-elementor'),
                'description' => __('Please set your animation speed in seconds. Default value is 6s.', 'skt-addons-elementor'),
                'type'        => Controls_Manager::NUMBER,
                'min'         => 0,
                'max'         => 100,
                'step'        => 1,
                'default'     => 6,
                'selectors'   => [
                    '{{WRAPPER}} .skt-photo-stack-wrapper' => '--animation_speed:{{SIZE}}s',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_container_align',
            [
                'label'     => __('Alignment', 'skt-addons-elementor'),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => [
                    'left'   => [
                        'title' => __('Left', 'skt-addons-elementor'),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'skt-addons-elementor'),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'  => [
                        'title' => __('Right', 'skt-addons-elementor'),
                        'icon'  => 'eicon-text-align-right',
                    ],
                ],
                'toggle'    => true,
                'default'   => 'center',
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .elementor-widget-container' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    /**
     * Register widget style controls
     */
    protected function register_style_controls() {
        $this->__photo_stack_style_controls();
    }

    protected function __photo_stack_style_controls() {
        $this->start_controls_section(
            '_section_photo_stack_style',
            [
                'label' => __('Common', 'skt-addons-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_container_width',
            [
                'label'          => esc_html__('Width', 'skt-addons-elementor'),
                'type'           => Controls_Manager::SLIDER,
                'default'        => [
                    'size' => 550,
                    'unit' => 'px',
                ],
                'tablet_default' => [
                    'unit' => '%',
                ],
                'mobile_default' => [
                    'unit' => '%',
                ],
                'size_units'     => ['px', '%', 'vw'],
                'range'          => [
                    '%'  => [
                        'min' => 1,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => 1,
                        'max' => 2000,
                    ],
                    'vw' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'selectors'      => [
                    '{{WRAPPER}} .skt-photo-stack-wrapper' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_container_height',
            [
                'label'      => __('Minimum Height', 'skt-addons-elementor'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px', 'vh', 'em'],
                'range'      => [
                    'px' => [
                        'min'  => 0,
                        'max'  => 1000,
                        'step' => 1,
                    ],
                    'em' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default'    => [
                    'size' => 550,
                    'unit' => 'px',
                ],
                'selectors'  => [
                    '{{WRAPPER}} .skt-photo-stack-wrapper' => 'min-height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_layers_overflow',
            [
                'label'     => __('Overflow', 'skt-addons-elementor'),
                'type'      => Controls_Manager::SELECT,
                'options'   => array(
                    'visible' => __('Visible', 'skt-addons-elementor'),
                    'hidden'  => __('Hidden', 'skt-addons-elementor'),
                    'scroll'  => __('Scroll', 'skt-addons-elementor'),
                ),
                'default'   => 'visible',
                'selectors' => array(
                    '{{WRAPPER}} .skt-photo-stack-wrapper' => 'overflow: {{VALUE}}',
                ),
            ]
        );
        $this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
        $this->start_controls_tabs('tabs_hover_style');

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __('Normal', 'skt-addons-elementor'),
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'img_box_shadow',
                'label'    => __('Box Shadow', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-photo-stack-item img',

            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __('Hover', 'skt-addons-elementor'),
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'img_box_shadow_hover',
                'label'    => __('Box Shadow Hover', 'skt-addons-elementor'),
                'selector' => '{{WRAPPER}} .skt-photo-stack-item img:hover',

            ]
        );
        $this->end_controls_tab();

        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'      => 'border',
                'label'     => __('Border', 'skt-addons-elementor'),
                'selector'  => '{{WRAPPER}} .skt-photo-stack-item img',
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => __('Border Radius', 'skt-addons-elementor'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top'    => 5,
                    'right'  => 5,
                    'bottom' => 5,
                    'left'   => 5,
                ],
                'selectors'  => [
                    '{{WRAPPER}} .skt-photo-stack-item'     => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-photo-stack-item img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }
    /**
     * @return null
     */
    protected function render() {
        $settings = $this->get_settings_for_display();
        if (empty($settings['image_list'])) {
            return;
        }
        ?>

        <div class="skt-photo-stack-wrapper">
			<?php foreach ($settings['image_list'] as $index => $item):
            $repeater_key  = 'skt_ps_item' . $index;
            $dynamic_class = 'elementor-repeater-item-' . $item['_id'];
            $tag           = 'div';
            $this->add_render_attribute($repeater_key, 'class', 'skt-photo-stack-item');
            $this->add_render_attribute($repeater_key, 'class', $dynamic_class);
            $this->add_render_attribute($repeater_key, 'class', $settings['image_infinite_animation']);
			$this->add_render_attribute('image', 'class', $settings['hover_animation_style']);
            $this->add_render_attribute('image', 'class', 'skt-photo-stack-img');

            if ( isset( $item['link'] ) && ! empty( $item['link']['url'] ) ) {
                $anchor_tag = 'a';
                $this->add_link_attributes( 'link_tag', $item['link'] );
            }
            ?>
				<?php printf( '<%s %s>', $tag, $this->get_render_attribute_string($repeater_key) ); ?>
					<?php !empty( $item['link']['url'] ) && printf( '<%s %s>', $anchor_tag, $this->get_render_attribute_string('link_tag') ); //start of anchor_tag ?>
						<?php if ($item['image']['id']) {
								$this->add_render_attribute('image', 'alt', $item['image']['alt'] );
								printf( '<img src="%s" %s/>',
									Group_Control_Image_Size::get_attachment_image_src($item['image']['id'], 'thumbnail', $item),
									$this->get_render_attribute_string('image')
								);
								$this->remove_render_attribute( 'image' );
							} else {
								echo $this->get_placeholder($item, $this->get_render_attribute_string('image'));
						}?>
					<?php !empty( $item['link']['url'] ) && printf( '</%s>', $anchor_tag ); // end of anchor_tag ?>
				<?php printf( '</%s>', $tag ); ?>
	            <?php endforeach;?>
        </div>


		<?php
}
	 /**
	  * Get placeholder image HTML.
	  *
	  * @param array $item
	  * @param array|null $attr
	  * @return void
	  */
    protected function get_placeholder($item, $attr = null) {
        if ('custom' !== $item['thumbnail_size']) {
            $width  = get_option($item['thumbnail_size'] . '_size_w');
            $height = get_option($item['thumbnail_size'] . '_size_h');
            $height = '0' == $height ? 'auto' : $height . 'px';
        } else {
            $width  = $item['thumbnail_custom_dimension']['width'];
            $height = $item['thumbnail_custom_dimension']['height'];
            $height = '0' == $height ? 'auto' : $height . 'px';
        }
        echo '<img src="' . $item['image']['url'] . '" style="width: ' . $width . 'px; height: ' . $height . ';" ' . $attr . '/>';
    }

}
