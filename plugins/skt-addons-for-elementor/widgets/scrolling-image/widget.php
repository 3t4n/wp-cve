<?php
/**
 * Scrolling Image
 *
 * @package Skt_Addons_Elementor
 */

namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
use Elementor\Repeater;
use Skt_Addons_Elementor\Elementor\Controls\Group_Control_Foreground;

defined('ABSPATH') || die();

class Scrolling_Image extends Base {

	/**
	 * Get widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_title() {
		return __('Scrolling Image', 'skt-addons-elementor');
	}

	/**
	 * Get widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'skti skti-scrolling-image';
	}

	public function get_keywords() {
		return ['scrolling-image', 'carousel', 'scrolling', 'image'];
	}

	/**
     * Register widget content controls
     */
	protected function register_content_controls() {
		$this->__image_content_controls();
		$this->__settings_content_controls();
	}

	protected function __image_content_controls() {

		$this->start_controls_section(
			'_section_scrolling_image',
			[
				'label' => __('Scrolling Image', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'image_style',
			[
				'label' => __( 'Image Style', 'skt-addons-elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'default' => 'foreground',
				'options' => [
					'foreground' => [
						'title' => __( 'Foreground', 'skt-addons-elementor' ),
						'icon' => 'skti skti-forward',
					],
					'background' => [
						'title' => __( 'Background', 'skt-addons-elementor' ),
						'icon' => 'skti skti-reply',
					],
				],
				'toggle' => false,
                'style_transfer' => true,
			]
		);

		$repeater->add_control(
			'image',
			[
				'label' => __('Image', 'skt-addons-elementor'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition' => [
					'image_style' => 'foreground'
				],
                'dynamic' => [
                    'active' => true,
                ]
			]
		);

		$repeater->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}.skt-scrolling-image-item',
				'condition' => [
					'image_style' => 'background'
				],
			]
		);

		$repeater->add_control(
			'title',
			[
				'label' => __('Title', 'skt-addons-elementor'),
				'type' => Controls_Manager::TEXT,
				'default' => __('SKT Master', 'skt-addons-elementor'),
				'placeholder' => __('Brand Name', 'skt-addons-elementor'),
				'dynamic' => [
					'active' => true,
				],
				'separator' => 'before'
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => __('Link', 'skt-addons-elementor'),
				'type' => Controls_Manager::URL,
				'placeholder' => __('https://example.com/', 'skt-addons-elementor'),
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$this->add_control(
			'scroll_items',
			[
				'show_label' => false,
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ name }}}',
				'default' => [
					[
						'title' => __('WordPress', 'skt-addons-elementor'),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title' => __('Elementor', 'skt-addons-elementor'),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title' => __('SKT Addons', 'skt-addons-elementor'),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title' => __('PHP', 'skt-addons-elementor'),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title' => __('HTML', 'skt-addons-elementor'),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
					[
						'title' => __('CSS', 'skt-addons-elementor'),
						'image' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],
				]
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'label' => __('Foreground Image Size', 'skt-addons-elementor'),
				'name' => 'thumbnail',
				'default' => 'thumbnail',
				'separator' => 'before',
				'exclude' => [
					'custom'
				]
			]
		);

		$this->end_controls_section();
	}

	protected function __settings_content_controls() {

		$this->start_controls_section(
			'_section_scrolling_image_settings',
			[
				'label' => __('Settings', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'slide_style',
			[
				'label' => __('Slide Style', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'skt-vertical' => [
						'title' => __('Vertical', 'skt-addons-elementor'),
						'icon' => 'eicon-navigation-vertical',
					],
					'skt-horizontal' => [
						'title' => __('Horizontal', 'skt-addons-elementor'),
						'icon' => 'eicon-navigation-horizontal',
					],
				],
				'default' => 'skt-horizontal',
				'toggle' => false,
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'h_slide_direction',
			[
				'label' => __('Slide direction', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __('Left', 'skt-addons-elementor'),
						'icon' => 'eicon-h-align-left',
					],
					'right' => [
						'title' => __('Right', 'skt-addons-elementor'),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'left',
				'toggle' => false,
				'condition' => [
					'slide_style' => 'skt-horizontal',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'v_slide_direction',
			[
				'label' => __('Slide direction', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __('Top', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-top',
					],
					'bottom' => [
						'title' => __('Bottom', 'skt-addons-elementor'),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'top',
				'toggle' => false,
				'condition' => [
					'slide_style' => 'skt-vertical',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'item_space',
			[
				'label' => __('Space between items', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => [
						'min' => -100,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-horizontal .skt-scrolling-image-item' => 'margin-right: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-vertical .skt-scrolling-image-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'style_transfer' => true,
			]
		);

		$this->add_control(
			'speed',
			[
				'label' => __('Slide Speed', 'skt-addons-elementor'),
				'description' => __('Autoplay speed in seconds. Default 30', 'skt-addons-elementor'),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 10000,
				'default' => 200,
				'frontend_available' => true,
			]
		);

		$this->end_controls_section();
	}

	/**
     * Register widget style controls
     */
	protected function register_style_controls() {
		$this->__wrapper_style_controls();
		$this->__image_box_style_controls();
		$this->__title_style_controls();
	}

	protected function __wrapper_style_controls() {

		$this->start_controls_section(
			'_section_scrolling_image_wrapper_style',
			[
				'label' => __('Wrapper', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_wrapper_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; flex: 0 0 {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'image_wrapper_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_wrapper_padding',
			[
				'label' => __( 'Padding', 'skt-addons-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'image_wrapper_alignment',
			[
				'label' => __('Alignment', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
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
					],
				],
				'default' => 'center',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}}.skt-scrolling-image .elementor-widget-container' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => '-webkit-box-pack: start;-ms-flex-pack: start;-webkit-justify-content: flex-start;justify-content: flex-start;',
					'center' => '-webkit-box-pack: center;-ms-flex-pack: center;-webkit-justify-content: center;justify-content: center;',
					'right' => '-webkit-box-pack: end;-ms-flex-pack: end;-webkit-justify-content: flex-end;justify-content: flex-end;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __image_box_style_controls() {

		$this->start_controls_section(
			'_section_scrolling_image_box_style',
			[
				'label' => __('Image Box', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'image_box_width',
			[
				'label' => __( 'Width', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 200,
				],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-horizontal .skt-scrolling-image-item' => 'width: {{SIZE}}{{UNIT}};max-width: {{SIZE}}{{UNIT}}; flex-basis: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-vertical .skt-scrolling-image-item' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_box_height',
			[
				'label' => __( 'Height', 'skt-addons-elementor'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'image_box_background',
				'label' => __('Background', 'skt-addons-elementor'),
				'types' => ['classic', 'gradient'],
				'selector' => '{{WRAPPER}} .skt-scrolling-image-item',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_box_border',
				'label' => __('Border', 'skt-addons-elementor'),
				'selector' => '{{WRAPPER}} .skt-scrolling-image-item',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'image_box_shadow',
				'label' => __( 'Box Shadow', 'skt-addons-elementor' ),
				'selector' => '{{WRAPPER}} .skt-scrolling-image-item',
			]
		);

		$this->add_control(
			'image_box_border_radius',
			[
				'label' => __('Border Radius', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px'],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'image_box_padding',
			[
				'label' => __('Padding', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'_heading_image_box_alignment',
			[
				'label' => __( 'Alignment', 'skt-addons-elementor' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'image_box_vertical_alignment',
			[
				'label' => __('Vertical', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => __( 'Top', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-navigation-horizontal',
					],
					'bottom' => [
						'title' => __( 'Bottom', 'skt-addons-elementor' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-vertical .skt-scrolling-image-item,'
					.'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-horizontal .skt-scrolling-image-item' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'top' => '-webkit-box-pack: start;-ms-flex-pack: start;-webkit-justify-content: flex-start;justify-content: flex-start;',
					'center' => '-webkit-box-pack: center;-ms-flex-pack: center;-webkit-justify-content: center;justify-content: center;',
					'bottom' => '-webkit-box-pack: end;-ms-flex-pack: end;-webkit-justify-content: flex-end;justify-content: flex-end;',
				],
			]
		);

		$this->add_control(
			'image_box_horizontal_alignment',
			[
				'label' => __('Horizontal', 'skt-addons-elementor'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'skt-addons-elementor' ),
						'icon' => 'eicon-navigation-horizontal',
					],
					'right' => [
						'title' => __( 'Right', 'skt-addons-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => 'center',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-vertical .skt-scrolling-image-item,'
					.'{{WRAPPER}} .skt-scrolling-image-wrapper.skt-horizontal .skt-scrolling-image-item' => '{{VALUE}}',
				],
				'selectors_dictionary' => [
					'left' => '-webkit-box-align: start;-webkit-align-items: flex-start;align-items: flex-start;-ms-flex-align: start;',
					'center' => '-webkit-box-align: center;-webkit-align-items: center;align-items: center;-ms-flex-align: center;',
					'right' => '-webkit-box-align: end;-webkit-align-items: flex-end;align-items: flex-end;-ms-flex-align: end;',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function __title_style_controls() {

		$this->start_controls_section(
			'_section_scrolling_image_title_style',
			[
				'label' => __('Title', 'skt-addons-elementor'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Foreground::get_type(),
			[
				'name' => 'title_color',
				'selector' => '{{WRAPPER}} .skt-scrolling-image-title',
			]
		);

		$this->add_control(
			'title_color_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __('Typography', 'skt-addons-elementor'),
				'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
				'selector' => '{{WRAPPER}} .skt-scrolling-image-title',
			]
		);

		$this->add_responsive_control(
			'title_margin',
			[
				'label' => __('Margin', 'skt-addons-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .skt-scrolling-image-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		if (empty($settings['scroll_items'])) {
			return;
		}
		$this->add_render_attribute('wrapper', 'class', ['skt-scrolling-image-wrapper',$settings['slide_style']]);
		$this->add_render_attribute('wrapper', 'data-align', $settings['slide_style']);
		$this->add_render_attribute('wrapper', 'data-duration', $settings['speed']?($settings['speed']*'1000'):'30000');
		if('skt-horizontal'==$settings['slide_style']){
			$this->add_render_attribute('wrapper', 'data-scroll-direction', $settings['h_slide_direction']);
		}else{
			$this->add_render_attribute('wrapper', 'data-scroll-direction', $settings['v_slide_direction']);
		}
		$this->add_render_attribute('container', 'class', ['skt-scrolling-image-container']);
		?>

		<div <?php $this->print_render_attribute_string( 'wrapper' );?>>
			<div <?php $this->print_render_attribute_string( 'container' ); ?>>
			<?php
			for ( $i = 1; $i <= 10; $i++ ) :
				foreach ($settings['scroll_items'] as $index => $value) :
					
					if(isset($value['image'])){
					//image link
						$images = wp_get_attachment_image_src($value['image']['id'], $settings['thumbnail_size']);

						if( is_array( $images ) ){
							$image = $images[0];
						}else{
							$image = $value['image']['url'];
						}
					}else{
						$image = Utils::get_placeholder_image_src();
					}

					$item_tag = 'div';
					//anchor link
					$repeater_key = 'scroll_items' . $index . $i;
					$this->add_render_attribute(
						$repeater_key,
						'class',
						[
							'skt-scrolling-image-item',
							'elementor-repeater-item-'.$value['_id']
						]
					);

					if ( $value['link']['url'] ) {
						$item_tag = 'a';
						$this->add_link_attributes( $repeater_key, $value['link'] );
					}
					?>
					<<?php echo wp_kses_post(skt_addons_elementor_escape_tags($item_tag) . ' ' . $this->get_render_attribute_string( $repeater_key )); ?>>
						<?php
						if ( ! empty( $image ) ) {
							printf( '<figure class="skt-scrolling-image"><img src="%s" alt="%s" title="%s"></figure>',
								esc_url( $image ),
								esc_attr( $value['title'] ),
								esc_attr( $value['title'] )
							);
						}

						if ( ! empty( $value['title'] ) ) {
							printf('<span class="skt-scrolling-image-title">%s</span>', esc_html( $value['title'] ) );
						}
						?>
					</<?php echo wp_kses_post(skt_addons_elementor_escape_tags($item_tag)); ?>>
				<?php endforeach;
			endfor;
			?>
			</div>
		</div>
		<?php
	}
}