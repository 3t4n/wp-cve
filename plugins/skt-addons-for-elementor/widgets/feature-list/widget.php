<?php
/**
 * Feature List widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Icons_Manager;

defined( 'ABSPATH' ) || die();

class Feature_List extends Base {
    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Feature List', 'skt-addons-elementor' );
    }

    /**
     * Get widget icon.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget icon.
     */
    public function get_icon() {
        return 'skti skti-list-2';
    }

    public function get_keywords() {
        return [ 'list', 'feature' ];
    }

	/**
     * Register widget content controls
     */
    protected function register_content_controls() {
		$this->__lists_content_controls();
		$this->__settings_content_controls();
	}

    protected function __lists_content_controls() {

        $this->start_controls_section(
            '_section_lists',
            [
                'label' => __( 'Feature List', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();

        $repeater->add_control(
            'icon_type',
            [
                'label' => __( 'Media Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'icon',
				'options' => [
					'icon' => [
						'title' => __( 'Icon', 'skt-addons-elementor' ),
						'icon' => 'eicon-star',
					],
					'number' => [
						'title' => __( 'Number', 'skt-addons-elementor' ),
						'icon' => 'eicon-number-field',
					],
					'image' => [
						'title' => __( 'Image', 'skt-addons-elementor' ),
						'icon' => 'eicon-image',
					],
				],
				'toggle' => false,
                'style_transfer' => true,
            ]
        );

		$repeater->add_control(
			'icon',
			[
				'label' => __( 'Icon', 'skt-addons-elementor' ),
				'type' => Controls_Manager::ICONS,
				'label_block' => true,
				'default' => [
					'value' => 'fas fa-check',
					'library' => 'regular',
				],
				'condition' => [
					'icon_type' => 'icon'
				],
			]
		);

        $repeater->add_control(
            'number',
            [
                'label' => __( 'Item Number', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => __( 'List Item Number', 'skt-addons-elementor' ),
                'default' => __( '1', 'skt-addons-elementor' ),
                'condition' => [
                    'icon_type' => 'number'
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'condition' => [
                    'icon_type' => 'image'
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $repeater->add_control(
            'text_heading',
            [
                'label' => __( 'Text & Link', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $repeater->add_control(
            'title',
            [
                'label' => __( 'Title', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'placeholder' => __( 'List Item', 'skt-addons-elementor' ),
                'default' => __( 'List Item', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $repeater->add_control(
            'link',
            [
                'label' => __( 'Link', 'skt-addons-elementor' ),
                'type' => Controls_Manager::URL,
                'placeholder' => __( 'https://example.com', 'skt-addons-elementor' ),
				'dynamic' => [
					'active' => true,
				]
            ]
        );

        $this->add_control(
            'list_item',
            [
                'show_label' => false,
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{{ title }}}',
                'default' => [
                    [
                        'title' => __( 'WordPress', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'Elementor', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                    [
                        'title' => __( 'SKT Elementor Addons', 'skt-addons-elementor' ),
						'icon' => [
							'value' => 'fas fa-check',
							'library' => 'regular',
						],
                    ],
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control(
            'list_layout',
            [
                'label' => __( 'Layout', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'column' => [
                        'title' => __( 'Default', 'skt-addons-elementor' ),
                        'icon' => 'eicon-editor-list-ul',
                    ],
                    'row' => [
                        'title' => __( 'Inline', 'skt-addons-elementor' ),
                        'icon' => 'eicon-ellipsis-h',
                    ],
                ],
                'toggle' => false,
                'default' => 'column',
                'prefix_class' => 'skt-content--',
                'selectors' => [
                    '{{WRAPPER}} .skt-feature-list-wrap' => 'flex-direction: {{VALUE}};',
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'show_separator',
            [
                'label' => __( 'Show Separator', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Show', 'skt-addons-elementor' ),
                'label_off' => __( 'Hide', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'default' => 'yes',
                'condition' => [
                    'list_layout' => 'row'
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'content_alignment',
            [
                'label' => __( 'Alignment', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-text-align-right',
					]
				],
                'toggle' => false,
                'default' => 'flex-start',
                'prefix_class' => 'skt-align--',
                'selectors' => [
                    '{{WRAPPER}}.skt-content--column .skt-list-item, {{WRAPPER}}.skt-content--column .skt-list-item' => 'align-items: {{VALUE}};',
                    '{{WRAPPER}}.skt-content--row .skt-feature-list-wrap' => 'justify-content: {{VALUE}};',
                    '{{WRAPPER}}.skt-content--column.skt-icon--column .skt-content' => 'align-items: {{VALUE}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_position',
            [
                'label' => __( 'Bullet Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'row' => [
                        'title' => __( 'Left', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'column' => [
                        'title' => __( 'Top', 'skt-addons-elementor' ),
                        'icon' => 'eicon-v-align-top',
                    ],
                    'row-reverse' => [
                        'title' => __( 'Right', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'toggle' => false,
                'default' => 'row',
                'prefix_class' => 'skt-icon--',
                'selectors' => [
                    '{{WRAPPER}} .skt-content' => 'flex-direction: {{VALUE}};',
                ],
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__common_style_controls();
		$this->__media_style_controls();
		$this->__text_style_controls();
	}

    protected function __common_style_controls() {

        $this->start_controls_section(
            '_section_common_style',
            [
                'label' => __( 'Common', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'list_separator_width',
            [
                'label' => __( 'Separator Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'px',
                    'size' => 40
                ],
                'condition' => [
                    'show_separator' => 'yes',
                    'list_layout' => 'row'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:after' => 'height: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'list_separator_color',
            [
                'label' => __( 'Separator Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'show_separator' => 'yes',
                    'list_layout' => 'row'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item:after' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ]
                ],
                'condition' => [
                    'list_layout' => 'row'
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-content--row .skt-list-item:not(:last-child)' => 'margin-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'item_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'list_border_type',
            [
                'label' => __( 'Border Type', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'none' => __( 'None', 'skt-addons-elementor' ),
                    'solid' => __( 'Solid', 'skt-addons-elementor' ),
                    'double' => __( 'Double', 'skt-addons-elementor' ),
                    'dotted' => __( 'Dotted', 'skt-addons-elementor' ),
                    'dashed' => __( 'Dashed', 'skt-addons-elementor' ),
                ],
                'default' => 'none',
                'condition' => [
                    'list_layout' => 'column'
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-content--column .skt-feature-list-wrap' => 'border-style: {{VALUE}}',
                    '{{WRAPPER}}.skt-content--column .skt-list-item:not(:last-child)' => 'border-bottom-style: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'list_border_width',
            [
                'label' => __( 'Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'default' => [
                    'size' => 1,
                ],
                'range' => [
                    'px' => [
                        'min' => 1,
                        'max' => 20,
                    ],
                ],
                'condition' => [
                    'list_border_type!' => 'none',
                    'list_layout' => 'column'
                ],
                'selectors' => [
                    '{{WRAPPER}}.skt-content--column .skt-feature-list-wrap' => 'border-width: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.skt-content--column .skt-list-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_control(
            'list_border_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'condition' => [
                    'list_border_type!' => 'none',
                    'list_layout' => 'column'
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-feature-list-wrap' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .skt-list-item' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'list_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}}.skt-content--column .skt-feature-list-wrap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}}.skt-content--row .skt-list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'item_box_shadow',
                'selector' => '{{WRAPPER}}.skt-content--row .skt-list-item, {{WRAPPER}}.skt-content--column .skt-feature-list-wrap',
            ]
        );

        $this->add_control(
            'list_background_color',
            [
                'label' => __( 'background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-list-item' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __media_style_controls() {

        $this->start_controls_section(
            '_section_icon_style',
            [
                'label' => __( 'Media Type', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'icon_size',
            [
                'label' => __( 'Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 5,
                        'max' => 250,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-icon.icon' => 'font-size: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .skt-icon.number' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-icon.image img' => 'width: {{SIZE}}{{UNIT}};'
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_spacing',
            [
                'label' => __( 'Spacing', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'selectors' => [
                    '{{WRAPPER}}.skt-icon--row .skt-icon' => 'margin-right: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.skt-icon--row-reverse .skt-icon' => 'margin-left: {{SIZE}}{{UNIT}}',
                    '{{WRAPPER}}.skt-icon--column .skt-icon' => 'margin-bottom: {{SIZE}}{{UNIT}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'icon_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 150,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .skt-icon' => 'padding: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'selector' => '{{WRAPPER}} .skt-icon',
            ]
        );

        $this->add_control(
            'icon_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .skt-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .skt-icon.image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-icon i' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .skt-icon svg' => 'fill: {{VALUE}};color: {{VALUE}}',
                    '{{WRAPPER}} .skt-icon span' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'icon_background',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-icon' => 'background: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __text_style_controls() {

        $this->start_controls_section(
            '_section_icon_text',
            [
                'label' => __( 'Text', 'skt-addons-elementor' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typography',
                'selector' => '{{WRAPPER}} .skt-text',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_ACCENT,
			],
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .skt-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_link_color',
            [
                'label' => __( 'Link Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.skt-content .skt-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'text_link_hover_color',
            [
                'label' => __( 'Hover Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} a.skt-content:hover .skt-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

		if ( empty($settings['list_item'] ) ) {
			return;
		}
        ?>

        <ul class="skt-feature-list-wrap">
            <?php foreach ( $settings['list_item'] as $index => $item ) :

				// link
				$repeater_key = 'list_item' . $index;
				if ( $item['link']['url'] ) {
					$this->add_render_attribute( $repeater_key, 'class', 'skt-content' );
					$this->add_link_attributes( $repeater_key, $item['link'] );
				}

                // title
                $title = $this->get_repeater_setting_key( 'title', 'list_item', $index );
                $this->add_inline_editing_attributes( $title, 'basic' );
                $this->add_render_attribute( $title, 'class', 'skt-text' );
                ?>
                <li class="skt-list-item">
                    <?php if ( ! empty( $item['link']['url'] ) ) : ?>
                        <a <?php $this->print_render_attribute_string( $repeater_key ); ?>>
                    <?php else: ?>
                        <div class="skt-content">
                    <?php endif; ?>

						<?php if ( ! empty( $item['icon']['value'] ) ) : ?>
                            <div class="skt-icon icon">
								<?php Icons_Manager::render_icon( $item['icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </div>
                        <?php elseif( $item['number'] ) : ?>
                            <div class="skt-icon number">
                                <span><?php echo esc_html( $item['number'] ); ?></span>
                            </div>
                        <?php elseif( $item['image'] ) :
                            $image = wp_get_attachment_image_url( $item['image']['id'], 'thumbnail', false );
                            if( ! $image ) {
                                $image = $item['image']['url'];
                            }
                            ?>
                            <div class="skt-icon image">
                                <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" />
                            </div>
                        <?php
                        endif;
                        ?>

                        <div <?php $this->print_render_attribute_string( $title ); ?>>
                            <?php echo esc_html($item['title']); ?>
                        </div>

                    <?php if ( !empty( $item['link']['url'] ) ) : ?>
                    </a>
                    <?php else: ?>
                    </div>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php
    }
}