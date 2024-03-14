<?php

namespace EazyGrid\Elementor\Widgets;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Repeater;
use EazyGrid\Elementor\Base\Photo_Grid as Photo_Grid_Base;

class Justified_Image_Grid extends Photo_Grid_Base {

	public function get_title() {
		return __( 'Justified Image Grid', 'eazygrid-elementor' );
	}

	public function get_keywords() {
		return ['eazygrid-elementor', 'eazygrid', 'eazygrid-elementor', 'eazy', 'grid'];
	}

	public function get_icon() {
		return 'ezicon ezicon-image-justified';
	}

	/**
	 * Register content controls
	 */
	public function register_content_controls() {
		$this->__content_controls();
		$this->__settings_content_controls();
	}

	public function __content_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Content', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'media_type',
			[
				'label'       => __( 'Media Type', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options'     => [
					'image' => [
						'title' => __( 'Image', 'eazygrid-elementor' ),
						'icon'  => 'eicon-image',
					],
					'video' => [
						'title' => __( 'Video', 'eazygrid-elementor' ),
						'icon'  => 'eicon-video-playlist',
					],
				],
				'toggle'      => false,
				'default'     => 'image',
			]
		);

		$this->__repeater_controls_image( $repeater );
		$this->__repeater_controls_video( $repeater );
		$this->__repeater_controls_title_subtitle( $repeater );

		$repeater_defaults = [
			[
				'title'    => __( 'Item #1', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-1.jpg',
				],
			],
			[
				'title'    => __( 'Item #2', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-2.jpg',
				],
			],
			[
				'title'    => __( 'Item #3', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-3.jpg',
				],
			],
			[
				'title'    => __( 'Item #4', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-4.jpg',
				],
			],
			[
				'title'    => __( 'Item #5', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-5.jpg',
				],
			],
			[
				'title'    => __( 'Item #6', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-6.jpg',
				],
			],
			[
				'title'    => __( 'Item #7', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-7.jpg',
				],
			],
			[
				'title'    => __( 'Item #8', 'eazygrid-elementor' ),
				'subtitle' => __( 'Place your subtitle here.', 'eazygrid-elementor' ),
				'image'    => [
					'url' => EAZYGRIDELEMENTOR_URL . 'assets/img/placeholder-8.jpg',
				],
			],
		];

		$this->add_control(
			'media_list',
			[
				'label'       => __( 'Grid List', 'eazygrid-elementor' ),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default'     => $repeater_defaults,
				'title_field' => '{{{ title }}}',
			]
		);

		$this->add_control(
			'on_click',
			[
				'label'   => __( 'On Click', 'eazygrid-elementor' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'lightbox',
				'options' => [
					'lightbox' => __( 'Display Lightbox', 'eazygrid-elementor' ),
					'link'     => __( 'Open Link', 'eazygrid-elementor' ),
				],
			]
		);

		$this->end_controls_section();
	}

	public function __settings_content_controls() {
		$this->start_controls_section(
			'section_settings',
			[
				'label' => __( 'Settings', 'eazygrid-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'enable_hover',
			[
				'label'        => __( 'Enable Hover', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'eazygrid-elementor' ),
				'label_off'    => __( 'No', 'eazygrid-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);

		$this->add_control(
			'enable_hover_tablet',
			[
				'label'        => __( 'Enable Hover For Tablet', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'eazygrid-elementor' ),
				'label_off'    => __( 'No', 'eazygrid-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'enable_hover' => 'yes',
				],
			]
		);

		$this->add_control(
			'enable_hover_mobile',
			[
				'label'        => __( 'Enable Hover For Mobile', 'eazygrid-elementor' ),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => __( 'Yes', 'eazygrid-elementor' ),
				'label_off'    => __( 'No', 'eazygrid-elementor' ),
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'enable_hover' => 'yes',
				],
			]
		);

		$this->add_control(
			'hover_style',
			[
				'label'     => __( 'Hover Style', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'slide-up',
				'options'   => $this->hover_styles(),
				'condition' => [
					'enable_hover' => 'yes',
				],
			]
		);

		$this->add_control(
			'hover_text_align',
			[
				'label'     => __( 'Alignment', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'eazygrid-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'eazygrid-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'eazygrid-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'toggle'    => true,
				'selectors' => [
					'{{WRAPPER}} .ezg-ele-grid--overlay-inner' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'enable_hover' => 'yes',
				],
			]
		);

		$this->__advance_content_controls();

		$this->end_controls_section();
	}

	protected function __advance_content_controls() {
		$this->add_control(
			'_advance_heading',
			[
				'label'     => __( 'Advance', 'eazygrid-elementor' ),
				'type'      => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->add_control(
			'row_height',
			[
				'label'              => __( 'Height', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'default'            => [
					'size' => 200,
				],
				'range'              => [
					'px' => [
						'min' => 50,
						'max' => 500,
					],
				],
				'frontend_available' => true,
				'render_type'        => 'ui',
			]
		);

		$this->add_control(
			'gutter',
			[
				'label'              => __( 'Gutter', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SLIDER,
				'size_units'         => [ 'px' ],
				'default'            => [
					'size' => 3,
				],
				'range'              => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'frontend_available' => true,
				'render_type'        => 'ui',
				'selectors'          => [
					'{{WRAPPER}} .ezg-ele-justified-image-grid-wrap' => '--ezg-ele-justified-image-grid-pull: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'randomize',
			[
				'label'              => __( 'Randomize', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'description'        => __( 'Automatically randomize the order of photos.', 'eazygrid-elementor' ),
				'style_transfer'     => true,
				'frontend_available' => true,
			]
		);

		$this->add_control(
			'last_row',
			[
				'label'              => __( 'Last Row', 'eazygrid-elementor' ),
				'type'               => Controls_Manager::SELECT,
				'default'            => 'justify',
				'options'            => [
					'nojustify' => __( 'No Justify', 'eazygrid-elementor' ),
					'justify'   => __( 'Justify', 'eazygrid-elementor' ),
					'hide'      => __( 'Hide', 'eazygrid-elementor' ),
				],
				'frontend_available' => true,
				'render_type'        => 'ui',
			]
		);
	}

	/**
	 * Register style controls
	 */
	public function register_style_controls() {
		$this->__hover_1_style_controls();
		$this->__hover_2_style_controls();
		$this->__hover_3_style_controls();
		$this->__hover_4_style_controls();
		$this->__hover_5_style_controls();
		$this->__video_style_controls();
		$this->__lightbox_style_controls();
	}

	protected function render() {
		$settings      = $this->get_settings_for_display();
		$hover_disable = '';
		if ( 'yes' == $settings['enable_hover'] && 'yes' !== $settings['enable_hover_tablet'] ) {
			$hover_disable .= 'ezg-ele-grid-tablet-hover-disable ';
		}
		if ( 'yes' == $settings['enable_hover'] && 'yes' !== $settings['enable_hover_mobile'] ) {
			$hover_disable .= 'ezg-ele-grid-mobile-hover-disable';
		}

		$hover_class = apply_filters( 'eazygridElementor/hover/class', $settings['hover_style'], $settings );
		$hover_class = 'ezg-ele-grid--hover-' . $hover_class;

		$lightbox_policy = 'yes';
		if ( ezg_ele_is_edit_mode() ) {
			$lightbox_policy = 'no';
		}

		$this->add_render_attribute('grid', [
			'class'   => [
				'ezg-ele-justified-image-grid-wrap',
				esc_attr( $hover_disable ),
			],
			'data-oc' => esc_attr( $settings['on_click'] ),
		] );
		?>
		<div <?php echo wp_kses_post( $this->get_render_attribute_string( 'grid' ) ); ?>>
				<?php
				foreach ( $settings['media_list'] as $image ) {

					if ( 'video' == $image['media_type'] ) {
						$this->get_video_item_markup( $image, $settings, $lightbox_policy, $hover_class );
					} else {
						$this->get_image_item_markup( $image, $settings, $lightbox_policy, $hover_class );
					}
					$this->remove_render_attribute( 'image-overlay' );
				}
				?>
		</div>
		<?php
	}

	public function get_video_item_markup( $image, $settings, $lightbox_policy, $hover_class ) {
				$options = $this->get_video_item_options( $image, $settings );

				$this->add_render_attribute( 'image-overlay', [
					'class'                        => [
						esc_attr( $hover_class ),
						'ezg-ele-justified-image-grid-item',
						'elementor-repeater-item-' . esc_attr( $image['_id'] ),
					],
					'data-elementor-open-lightbox' => $lightbox_policy,
					'data-elementor-lightbox'      => wp_json_encode( $options['lightbox_options'] ),
				] );
		?>
			<div <?php $this->print_render_attribute_string( 'image-overlay' ); ?>>
				<?php
				if ( $image['image_overlay'] && ( ! empty( $image['image_overlay']['url'] ) || ! empty( $image['image_overlay']['id'] ) ) ) :
					Group_Control_Image_Size::print_attachment_image_html( $image, 'image_overlay' );
				else :
					$this->get_attachment_image_html( ezg_ele_video_thumb( $image['video_type'], $options['video_url'] ) );
				endif;
				?>

				<div class="elementor-custom-embed-play" role="button">
					<?php \Elementor\Icons_Manager::render_icon( $settings['vid_play_icon'], [ 'aria-hidden' => 'true' ] ); ?>
					<span class="elementor-screen-only"><?php echo esc_html__( 'Play Video', 'eazygrid-elementor' ); ?></span>
				</div>

				<?php
				if ( $settings['enable_hover'] && $settings['vid_enable_overlay']=='yes' ) {
					$this->get_hover_overlay_markup( $image['title'], $image['subtitle'] );
				}
				?>
			</div>
		<?php
	}

	public function get_image_item_markup( $image, $settings, $lightbox_policy, $hover_class ) {
				$options = $this->get_image_item_options( $image, $settings );
		?>
			<div class="<?php echo esc_attr( $hover_class ); ?> ezg-ele-justified-image-grid-item elementor-repeater-item-<?php echo esc_attr( $image['_id'] ); ?>">
			<?php if ( $options['lightbox_image_url'] ) : ?>
				<?php
					$this->add_render_attribute( 'image-overlay', [
						'class'                        => 'ezg-ele-grid--item-inner ezg-ele-grid--trigger ' . esc_attr( $this->get_id() ),
						'target'                       => '__blank',
						'href'                         => esc_url( $options['lightbox_image_url'] ),
						'title'                        => esc_attr( $image['title'] ),
						'data-elementor-open-lightbox' => $lightbox_policy,
					] );
				?>
					<a <?php $this->print_render_attribute_string( 'image-overlay' ); ?>>
						<?php
							$this->get_attachment_image_html( $options['image_url'], $image['title'] );
						if ( $settings['enable_hover'] ) {
							$this->get_hover_overlay_markup( $image['title'], $image['subtitle'] );
						}
						?>
					</a>
				<?php endif; ?>
			</div>
		<?php
	}
}
