<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Before_After_Image extends \Bricks\Element {
	public $category     = 'bricksable';
	public $name         = 'ba-before-after-image';
	public $icon         = 'ti-image';
	public $css_selector = '.ba-before-after-image-wrapper';
	public $scripts      = array( 'bricksableBAImage' );

	public function get_label() {
		return esc_html__( 'Before/After Image', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['image']    = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['label']    = array(
			'title' => esc_html__( 'Labels', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['control']  = array(
			'title' => esc_html__( 'Control', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		unset( $this->control_groups['_typography'] );
	}

	public function set_controls() {
		// Images.
		$this->controls['before_image'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'type'  => 'image',
		);

		$this->controls['before_image_filter'] = array(
			'tab'    => 'content',
			'group'  => 'image',
			'label'  => esc_html__( 'Before Image filters', 'bricksable' ),
			'type'   => 'filters',
			'inline' => true,
			'css'    => array(
				array(
					'property' => 'filter',
					'selector' => '.ba-before-after-img-before',
				),
			),
		);

		$this->controls['after_image'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'type'  => 'image',
		);

		$this->controls['after_image_filter'] = array(
			'tab'    => 'content',
			'group'  => 'image',
			'label'  => esc_html__( 'After Image filters', 'bricksable' ),
			'type'   => 'filters',
			'inline' => true,
			'css'    => array(
				array(
					'property' => 'filter',
					'selector' => '.ba-before-after-img-after',
				),
			),
		);

		// Labels.
		$this->controls['show_labels'] = array(
			'tab'   => 'content',
			'group' => 'label',
			'label' => esc_html__( 'Show Labels', 'bricksable' ),
			'type'  => 'checkbox',
		);

		$this->controls['labels_onHover'] = array(
			'tab'      => 'content',
			'group'    => 'label',
			'label'    => esc_html__( 'Show Labels On Hover', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'show_labels', '=', true ),
		);

		$this->controls['before_label'] = array(
			'tab'            => 'content',
			'group'          => 'label',
			'label'          => esc_html__( 'Before Label', 'bricksable' ),
			'type'           => 'text',
			'inline'         => true,
			'default'        => 'Before Label',
			'hasDynamicData' => 'text',
			'required'       => array( 'show_labels', '=', true ),
		);

		$this->controls['after_label'] = array(
			'tab'            => 'content',
			'group'          => 'label',
			'label'          => esc_html__( 'After Label', 'bricksable' ),
			'type'           => 'text',
			'inline'         => true,
			'default'        => 'After Label',
			'hasDynamicData' => 'text',
			'required'       => array( 'show_labels', '=', true ),
		);

		$this->controls['labels_alignment'] = array(
			'tab'         => 'content',
			'group'       => 'label',
			'label'       => esc_html__( 'Vertical align', 'bricksable' ),
			'type'        => 'align-items',
			'options'     => isset( $this->control_options['alignItems'] ) ? $this->control_options['alignItems'] : '',
			'exclude'     => array(
				'stretch',
			),
			'css'         => array(
				array(
					'property' => 'align-items',
					'selector' => '.ba-before-after-image-wrapper',
				),
				array(
					'property' => 'justify-content',
					'selector' => '.ba-before-after-image-wrapper',
				),
			),
			'inline'      => true,
			'default'     => 'center',
			'placeholder' => esc_html__( 'Center', 'bricksable' ),
			'required'    => array( 'show_labels', '=', true ),
		);

		$this->controls['labels_typography'] = array(
			'tab'      => 'content',
			'group'    => 'label',
			'label'    => esc_html__( 'Labels Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-before-after-image-label',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'show_labels', '=', true ),
		);

		$this->controls['labels_background_color'] = array(
			'tab'      => 'content',
			'group'    => 'label',
			'label'    => esc_html__( 'Labels background color', 'bricksable' ),
			'type'     => 'color',
			'inline'   => true,
			'default'  => 'rgba(0,0,0,0.33)',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-before-after-image-label',
				),
			),
			'required' => array( 'show_labels', '=', true ),
		);

		$this->controls['labels_border'] = array(
			'tab'      => 'content',
			'group'    => 'label',
			'label'    => esc_html__( 'Labels border', 'bricksable' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.ba-before-after-image-label',
				),
			),
			'inline'   => true,
			'small'    => true,
			'default'  => array(
				'radius' => array(
					'top'    => 3,
					'right'  => 3,
					'bottom' => 3,
					'left'   => 3,
				),
			),
			'required' => array( 'show_labels', '=', true ),
		);

		$this->controls['labels_BoxShadow'] = array(
			'tab'      => 'content',
			'group'    => 'label',
			'label'    => esc_html__( 'Labels box shadow', 'bricksable' ),
			'type'     => 'box-shadow',
			'css'      => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-before-after-image-label',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'show_labels', '=', true ),
		);

		$this->controls['labels_Padding'] = array(
			'tab'      => 'content',
			'group'    => 'label',
			'label'    => esc_html__( 'Padding', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-before-after-image-label',
				),
			),
			'required' => array( 'show_labels', '=', true ),
		);

		// Controls.
		$this->controls['control_line_color'] = array(
			'tab'     => 'content',
			'group'   => 'control',
			'label'   => esc_html__( 'Control Line Color', 'bricksable' ),
			'type'    => 'color',
			'inline'  => true,
			'default' => '#ffffff',
			'css'     => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-before-after-image-control-line',
				),
				array(
					'property' => 'border-color',
					'selector' => '.ba-before-after-image-circle',
				),
			),
		);

		$this->controls['control_arrow_color'] = array(
			'tab'     => 'content',
			'group'   => 'control',
			'label'   => esc_html__( 'Control Arrow Color', 'bricksable' ),
			'type'    => 'color',
			'inline'  => true,
			'default' => '#ffffff',
			'css'     => array(
				array(
					'property' => 'border-right-color',
					'selector' => '.ba-before-after-image-arrow-wrapper .ba-before-after-image-arrow-left',
				),
				array(
					'property' => 'border-left-color',
					'selector' => '.ba-before-after-image-arrow-wrapper .ba-before-after-image-arrow-right',
				),
			),
		);

		$this->controls['add_circle'] = array(
			'tab'         => 'content',
			'group'       => 'control',
			'label'       => esc_html__( 'Add Circle', 'bricksable' ),
			'type'        => 'checkbox',
			'description' => esc_html__( 'Add Circle type to the control handler.', 'bricksable' ),
			'default'     => true,
		);

		$this->controls['control_circle_background_color'] = array(
			'tab'      => 'content',
			'group'    => 'control',
			'label'    => esc_html__( 'Control Circle Background Color', 'bricksable' ),
			'type'     => 'color',
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-before-after-image-circle',
				),
			),
			'required' => array( 'add_circle', '=', true ),
		);

		$this->controls['control_circle_size'] = array(
			'tab'      => 'content',
			'group'    => 'control',
			'label'    => esc_html__( 'Circle Size', 'bricksable' ),
			'type'     => 'number',
			'units'    => array(
				'px' => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
			),
			'css'      => array(
				array(
					'property' => 'width',
					'selector' => '.ba-before-after-image-circle',
				),
				array(
					'property' => 'height',
					'selector' => '.ba-before-after-image-circle',
				),
			),
			'default'  => '50px',
			'required' => array( 'add_circle', '=', true ),
		);

		$this->controls['control_circle_radius'] = array(
			'tab'      => 'content',
			'group'    => 'control',
			'label'    => esc_html__( 'Circle Radius', 'bricksable' ),
			'type'     => 'number',
			'units'    => array(
				'px' => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				),
			),
			'css'      => array(
				array(
					'property' => 'border-radius',
					'selector' => '.ba-before-after-image-circle',
				),
			),
			'default'  => '50px',
			'required' => array( 'add_circle', '=', true ),
		);

		$this->controls['control_handler_width'] = array(
			'tab'         => 'content',
			'group'       => 'control',
			'label'       => esc_html__( 'Handler width', 'bricksable' ),
			'type'        => 'number',
			'min'         => 0,
			'max'         => 100,
			'step'        => '10',
			'default'     => '5px',
			'inline'      => true,
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '.ba-before-after-image-horizontal .ba-before-after-image-control-line',
				),
				array(
					'property' => 'height',
					'selector' => '.ba-before-after-image-vertical .ba-before-after-image-control-line',
				),
				array(
					'property' => 'border-width',
					'selector' => '.ba-before-after-image-circle',
				),
			),
			'description' => esc_html__( 'The width of the handler.', 'bricksable' ),
		);

		// Settings.
		$this->controls['vertical_mode'] = array(
			'tab'   => 'content',
			'group' => 'settings',
			'label' => esc_html__( 'Vertical Mode', 'bricksable' ),
			'type'  => 'checkbox',
		);

		$this->controls['slider_move_type'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Slider Move Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'mouse_click' => esc_html__( 'Move on Click', 'bricksable' ),
				'mouse_hover' => esc_html__( 'Move on Hover', 'bricksable' ),
			),
			'inline'      => true,
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'mouse_click',
		);

		$this->controls['starting_point'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Starting point', 'bricksable' ),
			'type'        => 'number',
			'min'         => 0,
			'max'         => 100,
			'step'        => '10',
			'default'     => 50,
			'inline'      => true,
			'description' => esc_html__( 'The percentage to show of the before image.', 'bricksable' ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-before-after-image' );
		wp_enqueue_script( 'ba-before-after-image' );
		wp_localize_script(
			'ba-before-after-image',
			'bricksableBeforeAfterImageData',
			array(
				'BeforeAfterImageInstances' => array(),
			)
		);
	}

	public function get_before_image_settings( $settings ) {
		if ( empty( $settings['before_image'] ) ) {
			return array(
				'id'   => 0,
				'url'  => false,
				'size' => BRICKS_DEFAULT_IMAGE_SIZE,
			);
		}

		$image = $settings['before_image'];

		// Size.
		$image['size'] = empty( $image['size'] ) ? BRICKS_DEFAULT_IMAGE_SIZE : $settings['before_image']['size'];
		// Image ID or URL from dynamic data.
		if ( ! empty( $image['useDynamicData'] ) ) {

			$images = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', array( 'size' => $image['size'] ) );

			if ( ! empty( $images[0] ) ) {
				if ( is_numeric( $images[0] ) ) {
					$image['id'] = $images[0];
				} else {
					$image['url'] = $images[0];
				}
			}

			// No dynamic data image found (@since 1.6).
			else {
				return;
			}
		}

		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// If External URL, $image['url'] is already set.
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		} else {
			// Parse dynamic data in the external URL (@since 1.5.7).
			$image['url'] = $this->render_dynamic_data( $image['url'] );
		}

		return $image;
	}

	public function get_after_image_settings( $settings ) {
		if ( empty( $settings['after_image'] ) ) {
			return array(
				'id'   => 0,
				'url'  => false,
				'size' => BRICKS_DEFAULT_IMAGE_SIZE,
			);
		}

		$image = $settings['after_image'];

		// Size.
		$image['size'] = empty( $image['size'] ) ? BRICKS_DEFAULT_IMAGE_SIZE : $settings['after_image']['size'];

		// Image ID or URL from dynamic data.
		if ( ! empty( $image['useDynamicData'] ) ) {
			$images = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', array( 'size' => $image['size'] ) );

			if ( ! empty( $images[0] ) ) {
				if ( is_numeric( $images[0] ) ) {
					$image['id'] = $images[0];
				} else {
					$image['url'] = $images[0];
				}
			}

			// No dynamic data image found (@since 1.6).
			else {
				return;
			}
		}

		$image['id'] = empty( $image['id'] ) ? 0 : $image['id'];

		// If External URL, $image['url'] is already set.
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = ! empty( $image['id'] ) ? wp_get_attachment_image_url( $image['id'], $image['size'] ) : false;
		} else {
			// Parse dynamic data in the external URL (@since 1.5.7).
			$image['url'] = $this->render_dynamic_data( $image['url'] );
		}

		return $image;
	}


	public function render() {
		$settings          = $this->settings;
		$before_settings   = $this->get_before_image_settings( $settings );
		$before_image_id   = isset( $before_settings['id'] ) ? $before_settings['id'] : '';
		$before_image_url  = isset( $before_settings['url'] ) ? $before_settings['url'] : '';
		$before_image_size = isset( $before_settings['size'] ) ? $before_settings['size'] : '';

		$after_settings   = $this->get_after_image_settings( $settings );
		$after_image_id   = isset( $after_settings['id'] ) ? $after_settings['id'] : '';
		$after_image_url  = isset( $after_settings['url'] ) ? $after_settings['url'] : '';
		$after_image_size = isset( $after_settings['size'] ) ? $after_settings['size'] : '';

		$labels_on_hover = isset( $settings['labels_onHover'] ) ? true : false;

		$image_wrapper_classes   = array( 'ba-before-after-image-wrapper' );
		$image_wrapper_classes[] = true === $labels_on_hover ? 'ba-before-after-image-label-on-hover' : '';

		$image_before_atts       = array();
		$image_before_atts['id'] = isset( $image_before_atts['id'] ) ? 'image-' . $settings['before_image']['id'] : '';
		$image_after_atts        = array();
		$image_after_atts['id']  = isset( $image_after_atts['id'] ) ? 'image-' . $settings['after_image']['id'] : '';

		// STEP: Dynamic data image not found: Show placeholder text.
		if ( ! empty( $settings['before_image']['useDynamicData'] ) && ! $before_settings ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'Dynamic data is empty.', 'bricksable' ),
				)
			);
		}

		// STEP: Dynamic data image not found: Show placeholder text.
		if ( ! empty( $settings['after_image']['useDynamicData'] ) && ! $after_settings ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'Dynamic data is empty.', 'bricksable' ),
				)
			);
		}

		$before_image_placeholder_url = \Bricks\Builder::get_template_placeholder_image();

		$after_image_placeholder_url = \Bricks\Builder::get_template_placeholder_image();

		// Check: No image selected: No image ID provided && not a placeholder URL.
		if ( ! isset( $before_settings['external'] ) && ! $before_image_id && ! $before_image_url && $before_image_url !== $before_image_placeholder_url ) {
			return $this->render_element_placeholder( array( 'title' => esc_html__( 'No image selected.', 'bricksable' ) ) );
		}

		// Check: Image with ID doesn't exist.
		if ( ! isset( $before_settings['external'] ) && ! $before_image_url ) {
			/* translators: Before Image ID*/
			return $this->render_element_placeholder( array( 'title' => sprintf( esc_html__( 'Image ID (%s) no longer exist. Please select another image.', 'bricksable' ), $before_image_id ) ) );
		}

		// Check: No image selected: No image ID provided && not a placeholder URL.
		if ( ! isset( $after_settings['external'] ) && ! $after_image_id && ! $after_image_url && $after_image_url !== $after_image_placeholder_url ) {
			return $this->render_element_placeholder( array( 'title' => esc_html__( 'No image selected.', 'bricksable' ) ) );
		}

		// Check: Image with ID doesn't exist.
		if ( ! isset( $after_settings['external'] ) && ! $after_image_url ) {
			/* translators: After Image ID*/
			return $this->render_element_placeholder( array( 'title' => sprintf( esc_html__( 'Image ID (%s) no longer exist. Please select another image.', 'bricksable' ), $before_image_id ) ) );
		}

		$before_after_images_existed = ! empty( $settings['after_image']['url'] ) && ! empty( $settings['before_image']['url'] ) ? true : false;
		$before_after_image_options  = array(
			'addCircle'     => isset( $settings['add_circle'] ) ? true : false,
			'verticalMode'  => isset( $settings['vertical_mode'] ) ? true : false,
			'onHover'       => $labels_on_hover,
			'hoverStart'    => 'mouse_hover' === $settings['slider_move_type'] ? true : false,
			'startingPoint' => isset( $settings['starting_point'] ) ? intval( $settings['starting_point'] ) : 50,
			'imageExisted'  => $before_after_images_existed,
		);

		$this->set_attribute( 'image-wrapper', 'class', $image_wrapper_classes );
		$this->set_attribute( 'image-wrapper', 'id', esc_attr( 'ba-before-after-image-' . $this->id ) );

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			$this->set_attribute( '_root', 'data-ba-bricks-before-after-image-options', wp_json_encode( $before_after_image_options ) );
			//phpcs:ignore
			echo "<div {$this->render_attributes( '_root' )}>";
		} else {
			$this->set_attribute( 'image-wrapper', 'data-ba-bricks-before-after-image-options', wp_json_encode( $before_after_image_options ) );
		}
		echo '<div ' . $this->render_attributes( 'image-wrapper' ) . '>'; //phpcs:ignore
		// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.

		if ( isset( $before_image_id ) ) {
			echo wp_get_attachment_image( $before_image_id, $before_image_size, false, $image_before_atts );
		} elseif ( ! empty( $settings['before_image']['url'] ) ) {
			echo '<img src="' . esc_url( $before_image_url ) . '">';
		}
		if ( isset( $settings['show_labels'] ) ) {
			if ( isset( $settings['before_label'] ) ) {
				echo '<span class="ba-before-after-image-label ba-before-after-image-label-before keep">';
				echo esc_html( $settings['before_label'] );
				echo '</span>';
			}
			if ( isset( $settings['after_label'] ) ) {
				echo '<span class="ba-before-after-image-label ba-before-after-image-label-after keep">';
				echo esc_html( $settings['after_label'] );
				echo '</span>';
			}
		}

		// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
		if ( isset( $after_image_id ) ) {
			echo wp_get_attachment_image( $after_image_id, $after_image_size, false, $image_after_atts );
		} elseif ( ! empty( $settings['after_image']['url'] ) ) {
			echo '<img src="' . esc_url( $after_image_url ) . '">';
		}
		echo '</div>';
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			echo '</div>';
		}
	}
}
