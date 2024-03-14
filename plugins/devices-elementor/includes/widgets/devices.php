<?php
namespace ElementorDevices\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Utils;
use DomDocument;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Widget_Devices
 *
 * @since 0.0.1
 */
class Widget_Devices extends Widget_Base {

	public function get_name() {
		return 'devices';
	}

	public function get_title() {
		return __( 'Devices', 'devices-elementor' );
	}

	public function get_icon() {
		return 'nicon nicon-mobile';
	}

	public function get_categories() {
		return [ 'general-elements' ];
	}

	/**
	 * A list of scripts that the widgets is depended in
	 * @since 0.0.1
	 **/
	public function get_script_depends() {
		return [ 'devices-elementor-frontend' ];
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Device', 'devices-elementor' ),
			]
		);

			$this->add_control(
				'device_type',
				[
					'label' 		=> __( 'Type', 'devices-elementor' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'phone',
					'options' 		=> [
						'phone' 		=> [
							'title' => __( 'Phone', 'devices-elementor' ),
							'icon' 	=> 'fa fa-mobile-phone',
						],
						'tablet' 	=> [
							'title' => __( 'Tablet', 'devices-elementor' ),
							'icon' 	=> 'fa fa-tablet',
						],
						'laptop' 	=> [
							'title' => __( 'Laptop', 'devices-elementor' ),
							'icon' 	=> 'fa fa-laptop',
						],
						'desktop' 	=> [
							'title' => __( 'Desktop', 'devices-elementor' ),
							'icon' 	=> 'fa fa-desktop',
						],
						'window' 	=> [
							'title' => __( 'Window', 'addons-elementor' ),
							'icon' 	=> 'nicon nicon-window',
						],
					],
				]
			);

			$this->add_control(
				'device_orientation',
				[
					'label' 		=> __( 'Orientation', 'devices-elementor' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'portrait',
					'options' 		=> [
						'portrait' 	=> [
							'title' => __( 'Portrait', 'devices-elementor' ),
							'icon' 	=> 'nicon nicon-mobile-portrait',
						],
						'landscape' => [
							'title' => __( 'Landscape', 'devices-elementor' ),
							'icon' 	=> 'nicon nicon-mobile-landscape',
						],
					],
					'prefix_class'	=> 'elementor-device-orientation-',
					'condition'		=> [
						'device_type'					=> [ 'phone', 'tablet' ]
					]
				]
			);

			$this->add_control(
				'device_orientation_control',
				[
					'label' 		=> __( 'Orientation Control', 'devices-elementor' ),
					'description'	=> __( 'Show orientation swticher ', 'devices-elementor' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'no',
					'label_on' 		=> __( 'Yes', 'devices-elementor' ),
					'label_off' 	=> __( 'No', 'devices-elementor' ),
					'return_value' 	=> 'switcher',
					'prefix_class'	=> 'elementor-device-orientation-',
					'condition'		=> [
						'device_type'					=> [ 'phone', 'tablet' ]
					]
				]
			);

			$this->add_responsive_control(
				'device_align',
				[
					'label' 		=> __( 'Alignment', 'devices-elementor' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default'		=> 'center',
					'options' 		=> [
						'left' 		=> [
							'title' => __( 'Left', 'devices-elementor' ),
							'icon' 	=> 'fa fa-align-left',
						],
						'center' 	=> [
							'title' => __( 'Center', 'devices-elementor' ),
							'icon' 	=> 'fa fa-align-center',
						],
						'right' 	=> [
							'title' => __( 'Right', 'devices-elementor' ),
							'icon' 	=> 'fa fa-align-right',
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}}' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'device_width',
				[
					'label' 		=> __( 'Maximum Width', 'devices-elementor' ),
					'type' 			=> Controls_Manager::SLIDER,
					'default' 		=> [
						'size' 		=> '',
					],
					'range' 		=> [
						'px' 		=> [
							'min' 	=> 0,
							'max' 	=> 1920,
							'step' 	=> 10,
						],
						'%' => [
							'min' 	=> 0,
							'max' 	=> 100,
						],
					],
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .elementor-device-wrapper' => 'max-width: {{SIZE}}{{UNIT}}; width: 100%;',
						'{{WRAPPER}} .elementor-device' => 'width: 100%;',
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_screenshot',
			[
				'label' => __( 'Media', 'devices-elementor' ),
			]
		);

			$this->start_controls_tabs( 'tabs_media' );

			$this->start_controls_tab(
				'tab_media_portrait',
				[
					'label' => __( 'Default', 'devices-elementor' ),
				]
			);

				$this->add_control(
					'media_portrait_screenshot',
					[
						'label' 	=> __( 'Choose Screenshot', 'devices-elementor' ),
						'type' 		=> Controls_Manager::MEDIA,
						'default' 	=> [
							'url' 	=> Utils::get_placeholder_image_src(),
						],
					]
				);

				$this->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name' 			=> 'media_portrait_screenshot',
						'label' 		=> __( 'Screenshot Size', 'devices-elementor' ),
						'default' 		=> 'large',
						'condition'		=> [
							'media_portrait_screenshot[url]!'	=> '',
						]
					]
				);

				$this->add_responsive_control(
					'media_portrait_screenshot_align',
					[
						'label' 		=> __( 'Vertical Align', 'devices-elementor' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default'		=> 'flex-start',
						'options' 		=> [
							'flex-start' 		=> [
								'title' => __( 'Top', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-top',
							],
							'center' 	=> [
								'title' => __( 'Middle', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-center',
							],
							'flex-end' 	=> [
								'title' => __( 'Bottom', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-bottom',
							],
							'initial' 	=> [
								'title' => __( 'Custom', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-custom',
							],
							'parallax' 	=> [
								'title' => __( 'Parallax (coming soon)', 'devices-elementor' ),
								'icon' 	=> 'eicon-parallax',
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen' => 'align-items: {{VALUE}};',
							'{{WRAPPER}} .elementor-device-media-screen-inner' => 'top: auto;',
						],
						'condition'		=> [
							'media_portrait_screenshot[url]!'	=> '',
							'device_type!'						=> [ 'window' ],
						]
					]
				);

				$this->add_control(
					'media_portrait_screenshot_position',
					[
						'label' 		=> __( 'Offset Top (%)', 'devices-elementor' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 		=> [
							'size' 		=> 0,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 0,
								'max' 	=> 100,
								'step' 	=> 1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen-inner' => 'transform: translateY(-{{SIZE}}%);',
						],
						'condition'		=> [
							'media_portrait_screenshot_align'	=> 'initial',
							'media_portrait_screenshot[url]!'	=> '',
							'device_type!'						=> [ 'window' ],
						]
					]
				);

				$this->add_control(
					'media_portrait_screenshot_scrollable',
					[
						'label' 		=> __( 'Scrollable', 'devices-elementor' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'no',
						'label_on' 		=> __( 'Yes', 'devices-elementor' ),
						'label_off' 	=> __( 'No', 'devices-elementor' ),
						'return_value' 	=> 'scrollable',
						'prefix_class'	=> 'elementor-device-',
						'condition'		=> [
							'media_portrait_screenshot[url]!'	=> '',
							'device_type!'						=> [ 'window' ],
						]
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_media_landscape',
				[
					'label' => __( 'Landscape', 'devices-elementor' ),
					'condition'	=> [
						'device_orientation_control' 	=> 'switcher',
						'device_type'					=> [ 'phone', 'tablet' ]
					]
				]
			);

				$this->add_control(
					'media_landscape_screenshot',
					[
						'label' 		=> __( 'Choose Screenshot', 'devices-elementor' ),
						'type' 			=> Controls_Manager::MEDIA,
						'condition'		=> [
							'device_orientation_control' 	=> 'switcher',
							'device_type'					=> [ 'phone', 'tablet' ]
						],
					]
				);

				$this->add_group_control(
					Group_Control_Image_Size::get_type(),
					[
						'name' 		=> 'media_landscape_screenshot',
						'label' 	=> __( 'Screenshot Size', 'devices-elementor' ),
						'default' 	=> 'large',
						'condition'	=> [
							'device_orientation_control' 		=> 'switcher',
							'device_type'						=> [ 'phone', 'tablet' ],
							'media_landscape_screenshot[url]!'	=> ''
						]
					]
				);

				$this->add_responsive_control(
					'media_landscape_screenshot_align',
					[
						'label' 		=> __( 'Vertical Align', 'devices-elementor' ),
						'type' 			=> Controls_Manager::CHOOSE,
						'default'		=> 'flex-start',
						'options' 		=> [
							'flex-start' 		=> [
								'title' => __( 'Top', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-top',
							],
							'center' 	=> [
								'title' => __( 'Middle', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-center',
							],
							'flex-end' 	=> [
								'title' => __( 'Bottom', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-bottom',
							],
							'initial' 	=> [
								'title' => __( 'Custom', 'devices-elementor' ),
								'icon' 	=> 'nicon nicon-mobile-screen-custom',
							],
							'parallax' 	=> [
								'title' => __( 'Parallax', 'devices-elementor' ),
								'icon' 	=> 'eicon-parallax',
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen' => 'align-items: {{VALUE}};',
							'{{WRAPPER}} .elementor-device-media-screen-inner' => 'top: auto;',
						],
						'condition'	=> [
							'device_orientation_control' 		=> 'switcher',
							'device_type'						=> [ 'phone', 'tablet' ],
							'media_landscape_screenshot[url]!'	=> ''
						]
					]
				);

				$this->add_control(
					'media_landscape_screenshot_position',
					[
						'label' 		=> __( 'Offset Top (%)', 'devices-elementor' ),
						'type' 			=> Controls_Manager::SLIDER,
						'default' 		=> [
							'size' 		=> 0,
						],
						'range' 		=> [
							'px' 		=> [
								'min' 	=> 0,
								'max' 	=> 100,
								'step' 	=> 1,
							],
						],
						'selectors' 	=> [
							'{{WRAPPER}} .elementor-device-media-screen-inner' => 'transform: translateY(-{{SIZE}}%);',
						],
						'condition'		=> [
							'media_landscape_screenshot_align'	=> 'initial',
							'device_orientation_control' 		=> 'switcher',
							'device_type'						=> [ 'phone', 'tablet' ],
							'media_landscape_screenshot[url]!'	=> ''
						]
					]
				);

				$this->add_control(
					'media_landscape_screenshot_scrollable',
					[
						'label' 		=> __( 'Scrollable', 'devices-elementor' ),
						'type' 			=> Controls_Manager::SWITCHER,
						'default' 		=> 'no',
						'label_on' 		=> __( 'Yes', 'devices-elementor' ),
						'label_off' 	=> __( 'No', 'devices-elementor' ),
						'return_value' 	=> 'scrollable',
						'prefix_class'	=> 'elementor-device-',
						'condition'	=> [
							'device_orientation_control' 		=> 'switcher',
							'device_type'						=> [ 'phone', 'tablet' ],
							'media_landscape_screenshot[url]!'	=> ''
						]
					]
				);

			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_device_style',
			[
				'label' => __( 'Device', 'devices-elementor' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'device_override_style',
				[
					'label' 		=> __( 'Override Style', 'devices-elementor' ),
					'description'	=> __( 'Override default device style', 'devices-elementor' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'no',
					'label_on' 		=> __( 'Yes', 'devices-elementor' ),
					'label_off' 	=> __( 'No', 'devices-elementor' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'device_skin',
				[
					'label' 		=> __( 'Skin', 'devices-elementor' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'jetblack',
					'options' 		=> [
						'jetblack'  => __( 'Jet black', 'devices-elementor' ),
						'black'  	=> __( 'Black', 'devices-elementor' ),
						'silver'  	=> __( 'Silver', 'devices-elementor' ),
						'gold'  	=> __( 'Gold', 'devices-elementor' ),
						'rosegold'  => __( 'Rose Gold', 'devices-elementor' ),
						],
					'prefix_class'	=> 'elementor-device-skin-',
					'condition'		=> [
						'device_override_style!'	=> 'yes',
						'device_type!'				=> [ 'laptop', 'desktop' ]
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_frame_style',
			[
				'label' => __( 'Frame', 'devices-elementor' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'device_override_style'	=> 'yes'
				],
			]
		);

			$this->add_control(
				'device_frame_background',
				[
					'label' 	=> __( 'Device Background', 'devices-elementor' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .elementor-device-wrapper svg .back-shape' => 'fill: {{VALUE}}',
						'{{WRAPPER}} .elementor-device-wrapper svg .side-shape' => 'fill: {{VALUE}}',
					],
					'condition'		=> [
						'device_override_style'	=> 'yes'
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_overlay_style',
			[
				'label' => __( 'Overlay', 'devices-elementor' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'device_override_style'	=> 'yes'
				],
			]
		);

			$this->add_control(
				'device_overlay_tone',
				[
					'label'       	=> __( 'Tone', 'devices-elementor' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> 'light',
					'options' 		=> [
						'light'  	=> __( 'Light', 'devices-elementor' ),
						'dark'  	=> __( 'Dark', 'devices-elementor' ),
						],
					'prefix_class'	=> 'elementor-device-controls-tone-',
					'condition'		=> [
						'device_override_style'	=> 'yes',
						'device_type!'			=> [ 'laptop', 'desktop' ]
					],
				]
			);

			$this->add_control(
				'device_overlay_opacity',
				[
					'label' 	=> __( 'Opacity (%)', 'devices-elementor' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default' 	=> [
						'size' 	=> 0.2,
					],
					'range' 	=> [
						'px' 	=> [
							'max' 	=> 0.4,
							'min' 	=> 0.1,
							'step' 	=> 0.01,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .elementor-device-wrapper svg .overlay-shape' => 'fill-opacity: {{SIZE}};',
					],
					'condition'		=> [
						'device_override_style'	=> 'yes'
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_screen_style',
			[
				'label' => __( 'Screen', 'addons-elementor' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition'		=> [
					'device_type'	=> [ 'window' ],
				],
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'device_screen_border',
					'label' 	=> __( 'Border', 'addons-elementor' ),
					'selector' 	=> '{{WRAPPER}} .elementor-device-wrapper .elementor-device-media-screen figure',
					'condition'		=> [
						'device_type'			=> [ 'window' ],
					],
				]
			);

			$this->add_control(
				'device_screen_radius',
				[
					'label' 			=> __( 'Border Radius', 'addons-elementor' ),
					'type' 					=> Controls_Manager::DIMENSIONS,
					'size_units' 			=> [ 'px', '%' ],
					'allowed_dimensions'	=> [ 'bottom', 'left' ],
					'selectors' 			=> [
						'{{WRAPPER}} .elementor-device-wrapper .elementor-device-media-screen figure' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'				=> [
						'device_type'			=> [ 'window' ],
					],
				]
			);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings();

		// Default to phone
		$device_type = 'phone';

		// Only assign device type if selected
		if ( ! empty( $settings['device_type'] ) ) {
			$device_type = $settings['device_type'];
		}

		$this->add_render_attribute('device-wrapper', 'data-device-type', $device_type);
		$this->add_render_attribute('device-wrapper', 'class', 'elementor-device-wrapper' );
		$this->add_render_attribute('device-wrapper', 'class', 'elementor-device-type-' . $device_type);

		$this->add_render_attribute('device', 'class', 'elementor-device');

		$this->add_render_attribute('device-orientation', 'class', 'elementor-device-orientation nicon nicon-mobile-landscape');

		$this->add_render_attribute('device-shape', 'class', 'elementor-device-shape');

		$this->add_render_attribute('device-media', 'class', 'elementor-device-media');
		$this->add_render_attribute('device-media-inner', 'class', 'elementor-device-media-inner');
		$this->add_render_attribute('device-media-screen', 'class', 'elementor-device-media-screen');
		$this->add_render_attribute('device-media-screen-landscape', 'class', 'elementor-device-media-screen');
		$this->add_render_attribute('device-media-screen-landscape', 'class', 'elementor-device-media-screen-landscape');
		$this->add_render_attribute('device-media-screen-inner', 'class', 'elementor-device-media-screen-inner');

		$output 		= '';
		$before_shape 	= '';
		$after_shape 	= '';

		$before_shape .= '<div ' . $this->get_render_attribute_string('device-wrapper') . '>';
		$before_shape .= '<div ' . $this->get_render_attribute_string('device') . '>';
		$before_shape .= '<div ' . $this->get_render_attribute_string('device-orientation') . '></div>';

		$before_shape .= '<div ' . $this->get_render_attribute_string('device-shape') . '>';

		echo $before_shape;

		include DEVICES_ELEMENTOR_PATH . 'assets/shapes/' . $device_type . '.svg';

		$after_shape .= '</div><!-- .elementor-device-shape -->';

		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-inner') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen-inner') . '>';
			
			if ( ! empty( $settings['media_portrait_screenshot']['url'] ) )
				$after_shape .= '<figure>' . Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_portrait_screenshot' ) . '</figure>';

		$after_shape .= '</div><!-- .elementor-device-media-screen-inner -->';
		$after_shape .= '</div><!-- .elementor-device-media-screen -->';

		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen-landscape') . '>';
		$after_shape .= '<div ' . $this->get_render_attribute_string('device-media-screen-inner') . '>';

		if ( ! empty( $settings['media_landscape_screenshot']['url'] ) )
				$after_shape .= '<figure>' . Group_Control_Image_Size::get_attachment_image_html( $settings, 'media_landscape_screenshot' ) . '</figure>';

		$after_shape .= '</div><!-- .elementor-device-media-screen-inner -->';
		$after_shape .= '</div><!-- .elementor-device-media-screen -->';

		$after_shape .= '</div><!-- .elementor-device-media-inner -->';
		$after_shape .= '</div><!-- .elementor-device-media -->';
		$after_shape .= '</div><!-- .elementor-device -->';
		$after_shape .= '</div><!-- .elementor-device-wrapper -->';

		echo $after_shape;
	}

	protected function _content_template() {
		?>
		<#

		var device_classes = 'elementor-device';
		var device_wrapper_classes = 'elementor-device-wrapper elementor-device-type-' + settings.device_type;

		var device_orientation_classes = 'elementor-device-orientation nicon nicon-mobile-landscape';
		var device_shape_classes = 'elementor-device-shape';
		var device_media_classes = 'elementor-device-media';
		var device_media_inner_classes = 'elementor-device-media-inner';

		var device_media_screen_classes = 'elementor-device-media-screen';
		var device_media_screen_inner_classes = 'elementor-device-media-screen-inner';

		var output = '';

		output += '<div data-device-type="' + settings.device_type + '" class="' + device_wrapper_classes + '">';
		output += '<div class="' + device_classes + '">';
		output += '<a href="#" class="' + device_orientation_classes + '"></a>';

		output += '<div class="' + device_shape_classes + '"></div>';

		output += '<div class="' + device_media_classes + '">';
		output += '<div class="' + device_media_inner_classes + '">';
		output += '<div class="' + device_media_screen_classes + ' portrait">';
		output += '<div class="' + device_media_screen_inner_classes + '">';

		if ( '' !== settings.media_portrait_screenshot.url ) {
			var portrait_screenshot = {
				id 			: settings.media_portrait_screenshot.id,
				url 		: settings.media_portrait_screenshot.url,
				size 		: settings.media_portrait_screenshot_size,
				dimension 	: settings.media_portrait_screenshot_custom_dimension,
				model 		: editModel
			};

			var portrait_screenshot_url = elementor.imagesManager.getImageUrl( portrait_screenshot );

			if ( ! portrait_screenshot_url ) {
				return;
			}

			output += '<figure><img src="' + portrait_screenshot_url + '" /></figure>';
		}

		output += '</div><!-- .elementor-device-media-screen-inner -->';
		output += '</div><!-- .elementor-device-media-screen -->';

		output += '<div class="' + device_media_screen_classes + ' elementor-device-media-screen-landscape">';
		output += '<div class="' + device_media_screen_inner_classes + '">';

		if ( '' !== settings.media_landscape_screenshot.url ) {
			var landscape_screenshot = {
				id 			: settings.media_landscape_screenshot.id,
				url 		: settings.media_landscape_screenshot.url,
				size 		: settings.media_landscape_screenshot_size,
				dimension 	: settings.media_landscape_screenshot_custom_dimension,
				model 		: editModel
			};

			var lanscape_screenshot_url = elementor.imagesManager.getImageUrl( landscape_screenshot );

			if ( ! landscape_screenshot_url ) {
				return;
			}

			output += '<figure><img src="' + landscape_screenshot_url + '" /></figure>';
		}

		output += '</div><!-- .elementor-device-media-screen-inner -->';
		output += '</div><!-- .elementor-device-media-screen -->';

		output += '</div><!-- .elementor-device-media-inner -->';
		output += '</div><!-- .elementor-device-media -->';
		output += '</div><!-- .elementor-device -->';
		output += '</div><!-- .elementor-device-wrapper -->';

		print( output );

		#>

		<?php
	}
}
