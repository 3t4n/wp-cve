<?php
use Bricksable\Classes\Bricksable_Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Content_Toggle extends \Bricks\Element {
	public $category           = 'bricksable';
	public $name               = 'ba-content-toggle';
	public $icon               = 'ti-layout-menu-separated';
	public $scripts            = array( 'bricksableContentToggle' );
	public $loop_index         = 0;
	public $loop_content_index = 0;


	public function get_label() {
		return esc_html__( 'Content Toggle', 'bricksable' );
	}

	public function set_control_groups() {
		$this->control_groups['content'] = array(
			'title' => esc_html__( 'Tab Content', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['switcher_settings'] = array(
			'title' => esc_html__( 'Switcher Settings', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['label_settings'] = array(
			'title' => esc_html__( 'Label Settings', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['content_settings'] = array(
			'title' => esc_html__( 'Content Settings', 'bricksable' ),
			'tab'   => 'content',
		);
	}
	public function set_controls() {
		// Delete control '_typography'.
		unset( $this->controls['_typography'] );

		$this->controls['content_toggle_item'] = array(
			'tab'           => 'content',
			'checkLoop'     => true,
			'label'         => esc_html__( 'Content Toggle', 'bricksable' ),
			'type'          => 'repeater',
			'titleProperty' => 'label',
			'placeholder'   => esc_html__( 'Tab Content', 'bricksable' ),
			'fields'        => array(
				'label'            => array(
					'type'           => 'text',
					'hasDynamicData' => 'text',
				),
				'content_type'     => array(
					'label'       => esc_html__( 'Content Type', 'bricksable' ),
					'type'        => 'select',
					'options'     => array(
						'content'   => esc_html__( 'Content', 'bricksable' ),
						'templates' => esc_html__( 'Templates', 'bricksable' ),
					),
					'clearable'   => false,
					'pasteStyles' => false,
					'placeholder' => esc_html__( 'Content', 'bricksable' ),
				),
				'content_template' => array(
					'label'       => esc_html__( 'Template', 'bricksable' ),
					'type'        => 'select',
					'options'     => bricks_is_builder() ? Bricksable_Helpers::get_templates_list( get_the_ID() ) : array(),
					'searchable'  => true,
					'placeholder' => esc_html__( 'Select template', 'bricksable' ),
					'required'    => array( 'content_type', '=', 'templates' ),
					'description' => esc_html__( 'Note: A reload is required in order to get the styles of the template.', 'bricksable' ),
				),
				'content'          => array(
					'label'          => esc_html__( 'Content', 'bricksable' ),
					'type'           => 'textarea',
					'hasDynamicData' => 'text',
					'required'       => array( 'content_type', '=', 'content' ),
				),
			),
			'default'       => array(
				array(
					'label'        => esc_html__( 'Monthly', 'bricksable' ),
					'content_type' => 'content',
					'content'      => 'Lorem ipsum dolor ist amte. Consectetuer adipiscing eilt. Aenean commodo ligula egget dolor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
				),
				array(
					'label'        => esc_html__( 'Yearly', 'bricksable' ),
					'content_type' => 'content',
					'content'      => 'Lorem ipsum dolor ist amte. Consectetuer adipiscing eilt. Aenean commodo ligula egget dolor. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.',
				),
			),
		);

		$this->controls = array_replace_recursive( $this->controls, $this->get_loop_builder_controls() );

		$this->controls['toggle_style'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'Toggle Style', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'animated' => esc_html__( 'Animated Tab Style', 'bricksable' ),
				'tab'      => esc_html__( 'Normal Tab Style', 'bricksable' ),
			),
			'inline'      => true,
			'reset'       => true,
			'default'     => 'animated',
			'placeholder' => esc_html__( 'Animated Tab Style', 'bricksable' ),
		);

		// Switcher.
		$this->controls['switcher_horizontal_scrollable'] = array(
			'tab'         => 'content',
			'group'       => 'switcher_settings',
			'label'       => esc_html__( 'Horizontal Scrollable', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'small'       => true,
			'default'     => false,
			'breakpoints' => true,
			'css'         => array(
				array(
					'property' => 'overflow',
					'selector' => '.ba-content-toggle-wrapper',
					'value'    => 'auto',
				),
			),
		);

		$this->controls['switcher_text_align'] = array(
			'tab'         => 'content',
			'group'       => 'switcher_settings',
			'label'       => esc_html__( 'Text align', 'bricksable' ),
			'type'        => 'text-align',
			'css'         => array(
				array(
					'property' => 'text-align',
					'selector' => '.ba-content-toggle-wrapper',
				),
			),
			'default'     => 'center',
			'inline'      => true,
			'placeholder' => esc_html__( 'Center', 'bricksable' ),
		);

		$this->controls['switcher_background_color'] = array(
			'tab'         => 'content',
			'group'       => 'switcher_settings',
			'label'       => esc_html__( 'Switcher Background Color', 'bricksable' ),
			'type'        => 'color',
			'inline'      => true,
			'small'       => true,
			'css'         => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-content-toggle-switcher',
				),
			),
			'default'     => array(
				'hex' => '#f7f7fb',
			),
			'pasteStyles' => false,
			'description' => esc_html__( 'Define the switcher background color.', 'bricksable' ),
		);

		$this->controls['switcher_bottom_gap'] = array(
			'tab'         => 'content',
			'group'       => 'switcher_settings',
			'label'       => esc_html__( 'Bottom Gap', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'margin-bottom',
					'selector' => '.ba-content-toggle-switcher',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 1,
					'max'  => 150,
					'step' => 1,
				),
			),
			'default'     => '15px',
			'description' => esc_html__( 'Set the bottom gap distance between the switcher and content container.', 'bricksable' ),
		);

		$this->controls['switcher_border']     = array(
			'tab'     => 'content',
			'group'   => 'switcher_settings',
			'label'   => esc_html__( 'Border', 'bricksable' ),
			'type'    => 'border',
			'css'     => array(
				array(
					'property' => 'border',
					'selector' => '.ba-content-toggle-switcher',
				),
			),
			'default' => array(
				'width'  => array(
					'top'    => 1,
					'right'  => 1,
					'bottom' => 1,
					'left'   => 1,
				),
				'style'  => 'solid',
				'color'  => array(
					'hex' => '#e5e5e5',
				),
				'radius' => array(
					'top'    => 50,
					'right'  => 50,
					'bottom' => 50,
					'left'   => 50,
				),
			),
			'inline'  => true,
			'small'   => true,
		);
		$this->controls['switcher_box_shadow'] = array(
			'tab'   => 'content',
			'group' => 'switcher_settings',
			'label' => esc_html__( 'Box Shadow', 'bricksable' ),
			'type'  => 'box-shadow',
			'css'   => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-content-toggle-switcher',
				),
			),
		);
		$this->controls['switcher_padding']    = array(
			'tab'     => 'content',
			'group'   => 'switcher_settings',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-content-toggle-switcher',
				),
			),
			'default' => array(
				'top'    => 5,
				'right'  => 5,
				'bottom' => 5,
				'left'   => 5,
			),
		);

		// Label.
		$this->controls['label_active_background'] = array(
			'tab'     => 'content',
			'group'   => 'label_settings',
			'label'   => esc_html__( 'Label Active Background', 'bricksable' ),
			'type'    => 'background',
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-content-toggle-slider, .ba-content-toggle-style-tab .ba-content-toggle-item.active',
				),
			),
			'exclude' => array(
				'parallax',
				'attachment',
				'videoUrl',
				'videoScale',
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'color' => array(
					'hex' => '#29b5a8',
				),
			),
		);
		$this->controls['label_active_typography'] = array(
			'tab'      => 'content',
			'group'    => 'label_settings',
			'label'    => esc_html__( 'Label Active Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-content-toggle-item.active .ba-content-label-title',
				),
			),
			'default'  => array(
				'color' => array(
					'hex' => '#ffffff',
				),
			),
			'rerender' => true,
			'inline'   => true,
		);
		$this->controls['label_typography']        = array(
			'tab'      => 'content',
			'group'    => 'label_settings',
			'label'    => esc_html__( 'Label Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-content-toggle-item .ba-content-label-title',
				),
			),
			'inline'   => true,
			'rerender' => true,
		);
		$this->controls['label_padding']           = array(
			'tab'     => 'content',
			'group'   => 'label_settings',
			'label'   => esc_html__( 'Label Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-content-toggle-item',
				),
			),
			'default' => array(
				'top'    => 7,
				'right'  => 15,
				'bottom' => 7,
				'left'   => 15,
			),
		);

		// Content Settings.
		$this->controls['content_animationSeparator'] = array(
			'type'  => 'separator',
			'tab'   => 'content',
			'group' => 'content_settings',
			'label' => esc_html__( 'Animation', 'bricksable' ),
		);

		$this->controls['content_animation'] = array(
			'tab'         => 'content',
			'group'       => 'content_settings',
			'label'       => esc_html__( 'Entry animation', 'bricksable' ),
			'type'        => 'select',
			'searchable'  => true,
			'options'     => array(
				'bounce'            => esc_html__( 'bounce', 'bricksable' ),
				'flash'             => esc_html__( 'flash', 'bricksable' ),
				'pulse'             => esc_html__( 'pulse', 'bricksable' ),
				'rubberBand'        => esc_html__( 'rubberBand', 'bricksable' ),
				'swing'             => esc_html__( 'swing', 'bricksable' ),
				'tada'              => esc_html__( 'tada', 'bricksable' ),
				'wobble'            => esc_html__( 'wobble', 'bricksable' ),
				'jello'             => esc_html__( 'jello', 'bricksable' ),
				'bounceIn'          => esc_html__( 'bounceIn', 'bricksable' ),
				'bounceInDown'      => esc_html__( 'bounceInDown', 'bricksable' ),
				'bounceInLeft'      => esc_html__( 'bounceInLeft', 'bricksable' ),
				'bounceInRight'     => esc_html__( 'bounceInRight', 'bricksable' ),
				'bounceInUp'        => esc_html__( 'bounceInUp', 'bricksable' ),
				'fadeIn'            => esc_html__( 'fadeIn', 'bricksable' ),
				'fadeInDown'        => esc_html__( 'fadeInDown', 'bricksable' ),
				'fadeInDownBig'     => esc_html__( 'fadeInDownBig', 'bricksable' ),
				'fadeInLeft'        => esc_html__( 'fadeInLeft', 'bricksable' ),
				'fadeInLeftBig'     => esc_html__( 'fadeInLeftBig', 'bricksable' ),
				'fadeInRight'       => esc_html__( 'fadeInRight', 'bricksable' ),
				'fadeInRightBig'    => esc_html__( 'fadeInRightBig', 'bricksable' ),
				'fadeInUp'          => esc_html__( 'fadeInUp', 'bricksable' ),
				'fadeInUpBig'       => esc_html__( 'fadeInUpBig', 'bricksable' ),
				'flip'              => esc_html__( 'flip', 'bricksable' ),
				'flipInX'           => esc_html__( 'flipInX', 'bricksable' ),
				'flipInY'           => esc_html__( 'flipInY', 'bricksable' ),
				'lightSpeedIn'      => esc_html__( 'lightSpeedIn', 'bricksable' ),
				'rotateIn'          => esc_html__( 'rotateIn', 'bricksable' ),
				'rotateInDownLeft'  => esc_html__( 'rotateInDownLeft', 'bricksable' ),
				'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'bricksable' ),
				'rotateInUpLeft'    => esc_html__( 'rotateInUpLeft', 'bricksable' ),
				'rotateInUpRight'   => esc_html__( 'rotateInUpRight', 'bricksable' ),
				'slideInUp'         => esc_html__( 'slideInUp', 'bricksable' ),
				'slideInDown'       => esc_html__( 'slideInDown', 'bricksable' ),
				'slideInLeft'       => esc_html__( 'slideInLeft', 'bricksable' ),
				'slideInRight'      => esc_html__( 'slideInRight', 'bricksable' ),
				'zoomIn'            => esc_html__( 'zoomIn', 'bricksable' ),
				'zoomInDown'        => esc_html__( 'zoomInDown', 'bricksable' ),
				'zoomInLeft'        => esc_html__( 'zoomInLeft', 'bricksable' ),
				'zoomInRight'       => esc_html__( 'zoomInRight', 'bricksable' ),
				'zoomInUp'          => esc_html__( 'zoomInUp', 'bricksable' ),
				'jackInTheBox'      => esc_html__( 'jackInTheBox', 'bricksable' ),
				'rollIn'            => esc_html__( 'rollIn', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'fadeIn',
			'placeholder' => esc_html__( 'fadeIn', 'bricksable' ),
		);

		$this->controls['content_animationDuration'] = array(
			'tab'         => 'content',
			'group'       => 'content_settings',
			'label'       => esc_html__( 'Animation duration', 'bricksable' ),
			'type'        => 'select',
			'searchable'  => true,
			'options'     => array(
				'slower' => esc_html__( 'Very slow', 'bricksable' ),
				'slow'   => esc_html__( 'Slow', 'bricksable' ),
				'normal' => esc_html__( 'Normal', 'bricksable' ),
				'fast'   => esc_html__( 'Fast', 'bricksable' ),
				'faster' => esc_html__( 'Very fast', 'bricksable' ),
				'custom' => esc_html__( 'Custom', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'Normal', 'bricksable' ) . ' (1s)',
			'required'    => array( 'content_animation', '!=', '' ),
		);

		$this->controls['content_animationDurationCustom'] = array(
			'tab'         => 'content',
			'group'       => 'content_settings',
			'label'       => esc_html__( 'Animation duration', 'bricksable' ) . ' (' . esc_html__( 'Custom', 'bricksable' ) . ')',
			'type'        => 'text',
			'css'         => array(
				array(
					'property' => 'animation-duration',
					'selector' => '.ba-content-toggle-tab-item',
				),
			),
			'description' => esc_html__( 'For example: "1s" or "500ms"', 'bricksable' ),
			'inline'      => true,
			'required'    => array( 'content_animationDuration', '=', 'custom' ),
		);

		$this->controls['content_animationDelay'] = array(
			'tab'         => 'content',
			'group'       => 'content_settings',
			'label'       => esc_html__( 'Animation delay', 'bricksable' ),
			'type'        => 'text',
			'css'         => array(
				array(
					'property' => 'animation-delay',
					'selector' => '.ba-content-toggle-tab-item',
				),
			),
			'inline'      => true,
			'description' => esc_html__( 'For example:  "1s" or "500ms" or "-2.5s"', 'bricksable' ),
			'placeholder' => '0s',
			'required'    => array( 'content_animation', '!=', '' ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'bricks-animate' );
		wp_enqueue_style( 'ba-content-toggle' );
		wp_enqueue_script( 'ba-content-toggle' );
	}

	public function render() {
		$settings = $this->settings;

		$wrapper_classes = array(
			'ba-content-toggle-wrapper',
		);

		$this->set_attribute( 'wrapper', 'div' );
		$this->set_attribute( 'wrapper', 'class', $wrapper_classes );

		// Render.
		$output = '';

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$output .= "<div {$this->render_attributes( '_root' )}>";
		}

		//phpcs:ignore
		$output .= '<' . $this->render_attributes( 'wrapper' ) . '>';

		$toggle_style = isset( $settings['toggle_style'] ) ? $settings['toggle_style'] : 'animated';

		$label_classes = array(
			'ba-content-toggle-switcher',
			'ba-content-toggle-style-' . $settings['toggle_style'],
		);

		$this->set_attribute( 'label-wrapper', 'div' );
		$this->set_attribute( 'label-wrapper', 'class', $label_classes );
		$content_toggle_item = ! empty( $settings['content_toggle_item'] ) ? $settings['content_toggle_item'] : false;
		$label               = isset( $settings['label'] ) ? $settings['label'] : '';

		$output .= '<' . $this->render_attributes( 'label-wrapper' ) . '>';

		// Query Loop.
		if ( isset( $settings['hasLoop'] ) ) {
			$query = new \Bricks\Query(
				array(
					'id'       => $this->id,
					'settings' => $settings,
				)
			);

			$content_toggle_items = $content_toggle_item[0];
			$output              .= $query->render( array( $this, 'render_repeater_item' ), compact( 'content_toggle_items' ) );

			// We need to destroy the Query to explicitly remove it from the global store.
			$query->destroy();
			unset( $query );
		} else {
			foreach ( $content_toggle_item as $index => $content_toggle_items ) {
				$output .= self::render_repeater_item( $content_toggle_items );
			}
		}

		// Slider.
		if ( 'animated' === $settings['toggle_style'] ) {
			$output .= '<div class="ba-content-toggle-slider"></div>';
		}

		$output .= '</div>';
		$output .= '</div>';

		// Content.
		$content_wrapper_classes = array(
			'ba-content-toggle-tab',
		);

		$this->set_attribute( 'content-wrapper', 'div' );
		$this->set_attribute( 'content-wrapper', 'class', $content_wrapper_classes );

		$output .= '<' . $this->render_attributes( 'content-wrapper' ) . '>';

		// Query Loop.
		if ( isset( $settings['hasLoop'] ) ) {
			$query = new \Bricks\Query(
				array(
					'id'       => $this->id,
					'settings' => $settings,
				)
			);

			$content_toggle_items = $content_toggle_item[0];
			$output              .= $query->render( array( $this, 'render_repeater_item_content' ), compact( 'content_toggle_items' ) );

			// We need to destroy the Query to explicitly remove it from the global store.
			$query->destroy();
			unset( $query );
		} else {
			foreach ( $content_toggle_item as $index => $content_toggle_items ) {
				$output .= self::render_repeater_item_content( $content_toggle_items );
			}
		}

		$output .= '</div>';

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$output .= '</div>';
		}

		//phpcs:ignore
		echo $output;

	}

	public function render_repeater_item( $content_toggle_item ) {
		$settings = $this->settings;
		$index    = $this->loop_index;
		$output   = '';

		$content_toggle_item_classes = array(
			'ba-content-toggle-item',
		);

		if ( 0 === $index ) {
			$content_toggle_item_classes[] = 'active';
			$content_toggle_item_aria      = 'true';
		} else {
			$content_toggle_item_aria = 'false';
		}

		$this->set_attribute( "content-toggle-item-$index", 'div' );
		$this->set_attribute( "content-toggle-item-$index", 'aria-hidden', $content_toggle_item_aria );
		$this->set_attribute( "content-toggle-item-$index", 'class', $content_toggle_item_classes );

		$content_toggle_label_title_classes = array(
			'ba-content-label-title',
		);

		$this->set_attribute( "content-toggle-label-title-$index", 'span' );
		$this->set_attribute( "content-toggle-label-title-$index", 'class', $content_toggle_label_title_classes );

		// Label.
		if ( isset( $content_toggle_item['label'] ) ) {
			$output .= '<' . $this->render_attributes( "content-toggle-item-$index" ) . '><' . $this->render_attributes( "content-toggle-label-title-$index" ) . '>' . $content_toggle_item['label'] . '</span></div>';
		}

		$this->loop_index++;

		return $output;
	}

	public function render_repeater_item_content( $content_toggle_item ) {
		$settings = $this->settings;
		$index    = $this->loop_content_index;
		$output   = '';

		$content_toggle_tab_classes = array(
			'ba-content-toggle-tab-item',
			isset( $settings['content_animationDuration'] ) && '' !== $settings['content_animationDuration'] ? 'animation-duration-' . $settings['content_animationDuration'] : '',
		);

		if ( 0 === $index ) {
			$content_toggle_tab_classes[] = 'show-content';
			$content_toggle_tab_aria      = 'true';
		} else {
			$content_toggle_tab_aria = 'false';
		}

		$this->set_attribute( "content-toggle-tab-$index", 'div' );
		$this->set_attribute( "content-toggle-tab-$index", 'class', $content_toggle_tab_classes );
		$this->set_attribute( "content-toggle-tab-$index", 'aria-hidden', $content_toggle_tab_aria );
		if ( isset( $settings['content_animation'] ) && '' !== $settings['content_animation'] ) {
			$this->set_attribute( "content-toggle-tab-$index", 'data-animation', 'brx-animate-' . $settings['content_animation'] );
		}

		$output .= '<' . $this->render_attributes( "content-toggle-tab-$index" ) . '>';

		// Template.
		if ( isset( $content_toggle_item['content_type'] ) && 'templates' === $content_toggle_item['content_type'] ) {

			$template_id = ! empty( $content_toggle_item['content_template'] ) ? intval( $content_toggle_item['content_template'] ) : false;
			if ( ! $template_id ) {
				return $this->render_element_placeholder(
					array(
						'title' => esc_html__( 'No template selected.', 'bricksable' ),
					)
				);
			}

			$output .= do_shortcode( '[bricks_template id="' . $template_id . '" ]' );
		} else {
			if ( isset( $content_toggle_item['content'] ) ) {
				$output .= $content_toggle_item['content'];
			}
		}

		$output .= '</div>';

		$this->loop_content_index++;

		return $output;
	}
}
