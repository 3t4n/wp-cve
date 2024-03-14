<?php
/**
 * Image Compare widget class
 *
 * @package Skt_Addons_Elementor
 */
namespace Skt_Addons_Elementor\Elementor\Widget;

use Elementor\Core\Kits\Documents\Tabs\Global_Typography;
use Elementor\Utils;
use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

defined( 'ABSPATH' ) || die();

class Image_Compare extends Base {

    /**
     * Get widget title.
     *
     * @since 1.0
     * @access public
     *
     * @return string Widget title.
     */
    public function get_title() {
        return __( 'Image Compare', 'skt-addons-elementor' );
    }

	public function get_custom_help_url() {
		return '#';
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
        return 'skti skti-image-compare';
    }

    public function get_keywords() {
        return [ 'compare', 'image', 'before', 'after' ];
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
			'_section_images',
			[
				'label' => __( 'Images', 'skt-addons-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->start_controls_tabs( '_tab_images' );
        $this->start_controls_tab(
            '_tab_before_image',
            [
                'label' => __( 'Before', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'before_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'before_label',
            [
                'label' => __( 'Label', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'Before', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type before image label', 'skt-addons-elementor' ),
                'description' => __( 'Label will not be shown if Hide Overlay is enabled in Settings', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            '_tab_after_image',
            [
                'label' => __( 'After', 'skt-addons-elementor' ),
            ]
        );

        $this->add_control(
            'after_image',
            [
                'label' => __( 'Image', 'skt-addons-elementor' ),
                'type' => Controls_Manager::MEDIA,
                'default' => [
                    'url' => Utils::get_placeholder_image_src(),
                ],
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->add_control(
            'after_label',
            [
                'label' => __( 'Label', 'skt-addons-elementor' ),
                'type' => Controls_Manager::TEXT,
                'default' => __( 'After', 'skt-addons-elementor' ),
                'placeholder' => __( 'Type after image label', 'skt-addons-elementor' ),
                'description' => __( 'Label will not be shown if Hide Overlay is enabled in Settings', 'skt-addons-elementor' ),
                'dynamic' => [
                    'active' => true,
                ]
            ]
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'full',
                'separator' => 'before',
            ]
        );

        $this->end_controls_section();
	}

	protected function __settings_content_controls() {

        $this->start_controls_section(
            '_section_settings',
            [
                'label' => __( 'Settings', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'offset',
            [
                'label' => __( 'Visibility Ratio', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1,
                        'step' => .1,
                    ],
                ],
                'default' => [
                    'size' => .5,
                ],
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'orientation',
            [
                'label' => __( 'Orientation', 'skt-addons-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'label_block' => false,
                'options' => [
                    'horizontal' => [
                        'title' => __( 'Horizontal', 'skt-addons-elementor' ),
                        'icon' => 'eicon-h-align-stretch',
                    ],
                    'vertical' => [
                        'title' => __( 'Vertical', 'skt-addons-elementor' ),
                        'icon' => 'eicon-v-align-stretch',
                    ],
                ],
                'default' => 'horizontal',
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'hide_overlay',
            [
                'label' => __( 'Hide Overlay', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __( 'Yes', 'skt-addons-elementor' ),
                'label_off' => __( 'No', 'skt-addons-elementor' ),
                'return_value' => 'yes',
                'description' => __( 'Hide overlay with before and after label', 'skt-addons-elementor' ),
                'style_transfer' => true,
            ]
        );

        $this->add_control(
            'move_handle',
            [
                'label' => __( 'Move Handle', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'on_swipe',
                'options' => [
                    'on_hover' => __( 'On Hover', 'skt-addons-elementor' ),
                    'on_click' => __( 'On Click', 'skt-addons-elementor' ),
                    'on_swipe' => __( 'On Swipe', 'skt-addons-elementor' ),
                ],
                'description' => __( 'Select handle movement type. Note: overlay does not work with On Hover.', 'skt-addons-elementor' ),
                'style_transfer' => true,
            ]
        );

        $this->end_controls_section();
    }

	/**
     * Register widget style controls
     */
    protected function register_style_controls() {
		$this->__handle_style_controls();
		$this->__label_style_controls();
	}

    protected function __handle_style_controls() {

        $this->start_controls_section(
            '_section_style_handle',
            [
                'label' => __( 'Handle', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'handle_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-handle:before, {{WRAPPER}} .twentytwenty-handle:after' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .twentytwenty-handle' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .twentytwenty-left-arrow' => 'border-right-color: {{VALUE}}',
                    '{{WRAPPER}} .twentytwenty-right-arrow' => 'border-left-color: {{VALUE}}',
                    '{{WRAPPER}} .twentytwenty-handle:before' => 'box-shadow: 0 3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5);',
                    '{{WRAPPER}} .twentytwenty-handle:after' => 'box-shadow: 0 -3px 0 {{VALUE}}, 0px 0px 12px rgba(51, 51, 51, 0.5);',
                ],
            ]
        );

        $this->add_control(
            '_heading_bar',
            [
                'label' => __( 'Handle Bar', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'bar_size',
            [
                'label' => __( 'Size', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 50,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:before, {{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:after' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-0px - {{SIZE}}{{UNIT}} / 2);',
                    '{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:before, {{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:after' => 'height: {{SIZE}}{{UNIT}}; margin-top: calc(-0px - {{SIZE}}{{UNIT}} / 2);',
                ],
            ]
        );

        $this->add_control(
            '_heading_arrow',
            [
                'label' => __( 'Handle Arrow', 'skt-addons-elementor' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_responsive_control(
            'arrow_box_width',
            [
                'label' => __( 'Box Width', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-handle' => 'width: {{SIZE}}{{UNIT}}; margin-left: calc(-1 * ({{SIZE}}{{UNIT}} / 2));',
                    '{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:before' => 'margin-left: calc(({{SIZE}}{{UNIT}} / 2) - 1px);',
                    '{{WRAPPER}} .twentytwenty-vertical .twentytwenty-handle:after' => 'margin-right: calc(({{SIZE}}{{UNIT}} / 2) - 1px);',
                ],
            ]
        );
        $this->add_responsive_control(
            'arrow_box_height',
            [
                'label' => __( 'Box Height', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 20,
                        'max' => 250,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-handle' => 'height: {{SIZE}}{{UNIT}}; margin-top: calc(-1 * ({{SIZE}}{{UNIT}} / 2));',
                    '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:before' => 'margin-bottom: calc(({{SIZE}}{{UNIT}} / 2) + 2px);',
                    '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-handle:after' => 'margin-top: calc(({{SIZE}}{{UNIT}} / 2) + 2px);',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .twentytwenty-handle',
                'exclude' => [
                     'color'
                ]
            ]
        );

        $this->add_responsive_control(
            'box_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-handle' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
	}

    protected function __label_style_controls() {

        $this->start_controls_section(
            '_section_style_label',
            [
                'label' => __( 'Label', 'skt-addons-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'label_padding',
            [
                'label' => __( 'Padding', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'position_toggle',
            [
                'label' => __( 'Position', 'skt-addons-elementor' ),
                'type' => Controls_Manager::POPOVER_TOGGLE,
                'label_off' => __( 'None', 'skt-addons-elementor' ),
                'label_on' => __( 'Custom', 'skt-addons-elementor' ),
                'return_value' => 'yes',
            ]
        );

        $this->start_popover();

        $this->add_responsive_control(
            'label_offset_y',
            [
                'label' => __( 'Vertical', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -10,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-vertical .twentytwenty-after-label:before' => 'bottom: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .twentytwenty-vertical .twentytwenty-before-label:before' => 'top: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-horizontal .twentytwenty-after-label:before' => 'top: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'position_toggle' => 'yes',
                ]
            ]
        );

        $this->add_responsive_control(
            'label_offset_x',
            [
                'label' => __( 'Horizontal', 'skt-addons-elementor' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range' => [
                    'px' => [
                        'min' => -10,
                        'max' => 1000,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-after-label:before' => 'right: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .twentytwenty-horizontal .twentytwenty-before-label:before' => 'left: {{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .twentytwenty-vertical .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-vertical .twentytwenty-after-label:before' => 'left: {{SIZE}}{{UNIT}};'
                ],
                'condition' => [
                    'position_toggle' => 'yes',
                ]
            ]
        );

        $this->end_popover();

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'label_border',
                'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
            ]
        );

        $this->add_responsive_control(
            'label_border_radius',
            [
                'label' => __( 'Border Radius', 'skt-addons-elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'label_color',
            [
                'label' => __( 'Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'label_bg_color',
            [
                'label' => __( 'Background Color', 'skt-addons-elementor' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before' => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'label_box_shadow',
                'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before'
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'label_typography',
                'selector' => '{{WRAPPER}} .twentytwenty-before-label:before, {{WRAPPER}} .twentytwenty-after-label:before',
                'global' => [
					'default' => Global_Typography::TYPOGRAPHY_TEXT,
			],
            ]
        );

        $this->end_controls_section();
    }

    protected static function get_data_settings( $settings ) {
        $field_map = [
            'offset.size' => 'default_offset_pct.float',
            'orientation' => 'orientation.str',
            'hide_overlay' => 'no_overlay.bool',
            'move_handle' => 'move_handle.str',
            'before_label' => 'before_label.str',
            'after_label' => 'after_label.str',
        ];
        return skt_addons_elementor_prepare_data_prop_settings( $settings, $field_map );
    }

	protected function render() {
        $settings = $this->get_settings_for_display();

        $this->add_render_attribute( 'container', 'class', [
            'twentytwenty-container',
            'sktjs-image-comparison',
        ] );

        $this->add_render_attribute( 'container', 'data-skt-settings', self::get_data_settings( $settings ) );
        ?>
        <div <?php echo wp_kses_post($this->get_render_attribute_string( 'container' )); ?>>
            <?php if ( $settings['before_image']['url'] || $settings['before_image']['id'] ) :
                $this->add_render_attribute( 'before_image', 'src', $settings['before_image']['url'] );
                $this->add_render_attribute( 'before_image', 'alt', Control_Media::get_image_alt( $settings['before_image'] ) );
                $this->add_render_attribute( 'before_image', 'title', Control_Media::get_image_title( $settings['before_image'] ) );
                $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
                echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'before_image' ));
            endif;

            if ( $settings['after_image']['url'] || $settings['after_image']['id'] ) :
                $this->add_render_attribute( 'after_image', 'src', $settings['after_image']['url'] );
                $this->add_render_attribute( 'after_image', 'alt', Control_Media::get_image_alt( $settings['after_image'] ) );
                $this->add_render_attribute( 'after_image', 'title', Control_Media::get_image_title( $settings['after_image'] ) );
                $settings['hover_animation'] = 'disable-animation'; // hack to prevent image hover animation
                echo wp_kses_post(Group_Control_Image_Size::get_attachment_image_html( $settings, 'thumbnail', 'after_image' ));
            endif; ?>
        </div>
        <?php
    }


    public function content_template() {
        ?>
        <#
        view.addRenderAttribute( 'container', 'class', [
            'twentytwenty-container',
            'sktjs-image-comparison',
        ] );

        var fieldMap = {
            'offset.size': 'default_offset_pct',
            'orientation': 'orientation',
            'hide_overlay': 'no_overlay',
            'move_handle': 'move_handle',
            'before_label': 'before_label',
            'after_label': 'after_label',
        };

        var data = {};

        _.each(fieldMap, function(dKey, sKey) {
            if (sKey === 'offset.size') {
                data[dKey] = settings.offset.size;
                return;
            }
            if (_.isUndefined(settings[sKey])) {
                return;
            }
            data[dKey] = settings[sKey];
        });
        view.addRenderAttribute('container', 'data-skt-settings', JSON.stringify(data)); #>

        <div {{{ view.getRenderAttributeString( 'container' ) }}}>
            <# if ( settings.before_image.url || settings.before_image.id ) {
                var image = {
                    id: settings.before_image.id,
                    url: settings.before_image.url,
                    size: settings.thumbnail_size,
                    dimension: settings.thumbnail_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image ); #>
                <img src="{{ image_url }}">
            <# }

            if ( settings.after_image.url || settings.after_image.id ) {
                var image = {
                    id: settings.after_image.id,
                    url: settings.after_image.url,
                    size: settings.thumbnail_size,
                    dimension: settings.thumbnail_custom_dimension,
                    model: view.getEditModel()
                };

                var image_url = elementor.imagesManager.getImageUrl( image ); #>
                <img src="{{ image_url }}">
            <# } #>
        </div>
        <?php
    }
}