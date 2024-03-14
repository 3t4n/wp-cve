<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Tilt_Image extends \Bricks\Element {
	public $category     = 'bricksable';
	public $name         = 'ba-tilt-image';
	public $icon         = 'ti-image';
	public $css_selector = '.ba-tilt-image-wrapper img';
	public $scripts      = array( 'bricksableTiltImage' );

	public function get_label() {
		return esc_html__( 'Tilt Image', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['image']          = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['tilt_settings']  = array(
			'title' => esc_html__( 'Tilt Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['tilt_alignment'] = array(
			'title' => esc_html__( 'Alignment', 'bricksable' ),
			'tab'   => 'style',
		);
		$this->control_groups['overlay']        = array(
			'title' => esc_html__( 'Overlay Content', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['overlay_style']  = array(
			'title' => esc_html__( 'Overlay', 'bricksable' ),
			'tab'   => 'style',
		);
		unset( $this->control_groups['_typography'] );
	}

	public function set_controls() {
		$this->controls['_gradient']['css'][0]['selector'] = '.ba-tilt-image-wrapper';
		$this->controls['_border']['css'][]                =
		array(
			'property' => 'border',
			'selector' => '.ba-tilt-image-wrapper:before',
		);
		array(
			'property' => 'border',
			'selector' => '.ba-tilt-image-wrapper img',
		);

		$this->controls['image'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'type'  => 'image',
		);
		// Link To.
		$this->controls['linkToSeparator'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'type'  => 'separator',
			'label' => esc_html__( 'Link To', 'bricksable' ),
		);

		$this->controls['link'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'type'        => 'select',
			'options'     => array(
				'lightbox'   => esc_html__( 'Lightbox', 'bricksable' ),
				'attachment' => esc_html__( 'Attachment Page', 'bricksable' ),
				'media'      => esc_html__( 'Media File', 'bricksable' ),
				'url'        => esc_html__( 'Other (URL)', 'bricksable' ),
			),
			'placeholder' => esc_html__( 'None', 'bricksable' ),
		);

		$this->controls['url'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Link type', 'bricksable' ),
			'type'     => 'link',
			'required' => array( 'link', '=', 'url' ),
		);

		// Alignment.
		$this->controls['tilt_textAlign'] = array(
			'tab'         => 'style',
			'group'       => 'tilt_alignment',
			'label'       => esc_html__( 'Text align', 'bricksable' ),
			'type'        => 'text-align',
			'css'         => array(
				array(
					'property' => 'text-align',
					'selector' => '.ba-tilt-image-wrapper .image-overlay-content',
				),
				array(
					'property' => 'text-align',
					'selector' => '.ba-tilt-image-wrapper .ba-tilt-image-overlay-caption',
				),
			),
			'default'     => 'center',
			'placeholder' => esc_html__( 'Center', 'bricksable' ),
			'inline'      => true,
		);

		$this->controls['tilt_verticalAlign'] = array(
			'tab'         => 'style',
			'group'       => 'tilt_alignment',
			'label'       => esc_html__( 'Vertical align', 'bricksable' ),
			'type'        => 'align-items',
			'exclude'     => array(
				'stretch',
			),
			'css'         => array(
				array(
					'property' => 'align-items',
					'selector' => '.ba-tilt-image-wrapper .image-overlay-wrapper',
				),
			),
			'inline'      => true,
			'default'     => 'center',
			'placeholder' => esc_html__( 'Center', 'bricksable' ),
		);

		// Overlay.
		$this->controls['imageOverlayShowHover'] = array(
			'tab'         => 'content',
			'group'       => 'overlay',
			'label'       => esc_html__( 'Only show overlay on hover', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Set your overlay in the style tab (Gradient/Overlay).', 'bricksable' ),
		);

		// Title & Caption.

		$this->controls['title_caption_separator'] = array(
			'tab'   => 'content',
			'group' => 'overlay',
			'type'  => 'separator',
			'label' => esc_html__( 'Title & Caption', 'bricksable' ),
		);

		$this->controls['overlay_title'] = array(
			'tab'         => 'content',
			'group'       => 'overlay',
			'label'       => esc_html__( 'Title Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'none'       => esc_html__( 'No title', 'bricksable' ),
				'attachment' => esc_html__( 'Attachment', 'bricksable' ),
				'custom'     => esc_html__( 'Custom', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'No title', 'bricksable' ),
		);

		$this->controls['overlay_title_custom'] = array(
			'tab'            => 'content',
			'group'          => 'overlay',
			'label'          => esc_html__( 'Overlay Title', 'bricksable' ),
			'type'           => 'text',
			'spellcheck'     => true,
			'inlineEditing'  => true,
			'hasDynamicData' => 'text',
			'required'       => array( 'overlay_title', '=', 'custom' ),
		);

		$this->controls['overlay_title_tag'] = array(
			'tab'         => 'content',
			'group'       => 'overlay',
			'label'       => esc_html__( 'Title Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1' => esc_html__( 'Heading 1 (h1)', 'bricksable' ),
				'h2' => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3' => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4' => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5' => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6' => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
			),
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'h3',
			'inline'      => true,
			'required'    => array( 'overlay_title', '!=', 'none' ),
		);

		$this->controls['overlay_caption'] = array(
			'tab'         => 'content',
			'group'       => 'overlay',
			'label'       => esc_html__( 'Caption Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'none'       => esc_html__( 'No caption', 'bricksable' ),
				'attachment' => esc_html__( 'Attachment', 'bricksable' ),
				'custom'     => esc_html__( 'Custom', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'No caption', 'bricksable' ),
		);

		$this->controls['overlay_caption_custom'] = array(
			'tab'         => 'content',
			'group'       => 'overlay',
			'label'       => esc_html__( 'Custom caption', 'bricksable' ),
			'type'        => 'text',
			'placeholder' => esc_html__( 'Here goes your caption ...', 'bricksable' ),
			'required'    => array( 'overlay_caption', '=', 'custom' ),
		);

		$this->controls['overlayShowHover'] = array(
			'tab'    => 'content',
			'group'  => 'overlay',
			'label'  => esc_html__( 'Only show on hover', 'bricksable' ),
			'type'   => 'checkbox',
			'inline' => true,
		);

		$this->controls['overlay_icon_separator'] = array(
			'tab'    => 'content',
			'group'  => 'overlay',
			'label'  => esc_html__( 'Icon', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);

		$this->controls['overlay_icon'] = array(
			'tab'    => 'content',
			'group'  => 'overlay',
			'label'  => esc_html__( 'Icon', 'bricksable' ),
			'type'   => 'icon',
			'inline' => true,
			'small'  => true,
		);

		$this->controls['overlay_translatez'] = array(
			'tab'         => 'style',
			'group'       => 'overlay_style',
			'label'       => esc_html__( 'Overlay Content Distance', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'transform:translateZ',
					'selector' => '.image-overlay-wrapper',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
			),
			'default'     => '50px',
			'description' => esc_html__( 'Adjust the distance between the image and the overlay content.', 'bricksable' ),
		);

		$this->controls['overlay_title_typography'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Title Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-tilt-image-overlay-title',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_title', '!=', '' ),
		);

		$this->controls['overlay_caption_typography'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Caption Typography', 'bricksable' ),
			'type'     => 'typography',
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-tilt-image-caption',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_title', '!=', '' ),
		);

		$this->controls['overlay_style_icon_separator'] = array(
			'tab'    => 'style',
			'group'  => 'overlay_style',
			'label'  => esc_html__( 'Icon', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);

		$this->controls['overlay_icon_border'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Icon border', 'bricksable' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.image-overlay-wrapper .overlay-icon',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_icon', '!=', '' ),
		);

		$this->controls['overlay_icon_boxshadow'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'     => 'box-shadow',
			'css'      => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.image-overlay-wrapper .overlay-icon',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_icon', '!=', '' ),
		);

		$this->controls['overlay_icon_height'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Icon height', 'bricksable' ),
			'type'     => 'number',
			'unit'     => 'px',
			'css'      => array(
				array(
					'property' => 'line-height',
					'selector' => '.image-overlay-wrapper .overlay-icon',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_icon', '!=', '' ),
		);

		$this->controls['overlay_icon_width'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Icon width', 'bricksable' ),
			'type'     => 'number',
			'unit'     => 'px',
			'css'      => array(
				array(
					'property' => 'width',
					'selector' => '.image-overlay-wrapper .overlay-icon',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_icon', '!=', '' ),
		);

		$this->controls['overlay_icon_typography'] = array(
			'tab'      => 'style',
			'group'    => 'overlay_style',
			'label'    => esc_html__( 'Icon typography', 'bricksable' ),
			'type'     => 'typography',
			'exclude'  => array(
				'font-family',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
			),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.image-overlay-wrapper .overlay-icon',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'overlay_icon', '!=', '' ),
		);

		// Tilt Settings.
		$this->controls['tilt_reverse'] = array(
			'tab'         => 'content',
			'group'       => 'tilt_settings',
			'label'       => esc_html__( 'Reverse', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Reverse the tilt direction.', 'bricksable' ),
		);
		$this->controls['tilt_max']     = array(
			'tab'      => 'content',
			'group'    => 'tilt_settings',
			'label'    => esc_html__( 'Tilt rotation', 'bricksable' ),
			'type'     => 'number',
			'unitless' => true,
			'min'      => 10,
			'max'      => 100,
			'step'     => '1',
			'default'  => 40,
		);

		$this->controls['tilt_speed'] = array(
			'tab'      => 'content',
			'group'    => 'tilt_settings',
			'label'    => esc_html__( 'Tilt Speed', 'bricksable' ),
			'type'     => 'number',
			'unitless' => true,
			'min'      => 200,
			'max'      => 2000,
			'step'     => '100',
			'default'  => 1000,
		);

		$this->controls['tilt_perspective'] = array(
			'tab'      => 'content',
			'group'    => 'tilt_settings',
			'label'    => esc_html__( 'Tilt Perspective', 'bricksable' ),
			'type'     => 'number',
			'unitless' => true,
			'min'      => 200,
			'max'      => 1200,
			'step'     => '100',
			'default'  => 1000,
		);

		$this->controls['tilt_scale'] = array(
			'tab'      => 'content',
			'group'    => 'tilt_settings',
			'label'    => esc_html__( 'Tilt Scale', 'bricksable' ),
			'type'     => 'number',
			'unitless' => true,
			'min'      => 1,
			'max'      => 2,
			'step'     => '0.1',
			'default'  => 1,
		);

		$this->controls['tilt_glare'] = array(
			'tab'         => 'content',
			'group'       => 'tilt_settings',
			'label'       => esc_html__( 'Use glare', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Setting this option will enable a glare effect.', 'bricksable' ),
		);

		$this->controls['tilt_max_glare'] = array(
			'tab'      => 'content',
			'group'    => 'tilt_settings',
			'label'    => esc_html__( 'Max Glare', 'bricksable' ),
			'type'     => 'number',
			'unitless' => true,
			'min'      => 0.1,
			'max'      => 1,
			'step'     => '0.1',
			'default'  => 0.8,
			'required' => array( 'tilt_glare', '=', true ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-tilt-image' );
		wp_enqueue_script( 'ba-tilt-image' );
		wp_localize_script(
			'ba-tilt-image',
			'bricksableTiltImageData',
			array(
				'tiltImageInstances' => array(),
			)
		);
	}

	public function get_normalized_image_settings( $settings ) {
		if ( ! isset( $settings['image'] ) ) {
			$settings['image'] = array(
				'id'  => 0,
				'url' => '',
			);
			return $settings;
		}

		$image = $settings['image'];

		if ( isset( $image['useDynamicData']['name'] ) ) {
			$images      = $this->render_dynamic_data_tag( $image['useDynamicData']['name'] );
			$image['id'] = empty( $images ) ? 0 : $images[0];
		} else {
			$image['id'] = isset( $image['id'] ) ? $image['id'] : 0;
		}

		// Image Size.
		$image['size'] = isset( $image['size'] ) ? $settings['image']['size'] : BRICKS_DEFAULT_IMAGE_SIZE;

		// Image URL.
		if ( ! isset( $image['url'] ) ) {
			$image['url'] = wp_get_attachment_image_url( $image['id'], $image['size'] );
		}

		$settings['image'] = $image;

		return $settings;
	}

	public function render() {
		$settings = $this->settings;
		$settings = $this->get_normalized_image_settings( $settings );

		$tilt_max         = isset( $settings['tilt_max'] ) ? intval( $settings['tilt_max'] ) : 40;
		$tilt_speed       = isset( $settings['tilt_speed'] ) ? intval( $settings['tilt_speed'] ) : 1000;
		$tilt_perspective = isset( $settings['tilt_perspective'] ) ? intval( $settings['tilt_perspective'] ) : 1000;
		$tilt_scale       = isset( $settings['tilt_scale'] ) ? round( $settings['tilt_scale'], 1 ) : 1;
		$tilt_glare       = isset( $settings['tilt_glare'] ) ? true : false;
		$tilt_maxglare    = isset( $settings['tilt_max_glare'] ) ? round( $settings['tilt_max_glare'], 1 ) : 1;
		$tilt_reverse     = isset( $settings['tilt_reverse'] ) ? true : false;

		$tilt_options = array(
			'max'         => $tilt_max,
			'speed'       => $tilt_speed,
			'perspective' => $tilt_perspective,
			'scale'       => $tilt_scale,
			'glare'       => $tilt_glare,
			'max-glare'   => $tilt_maxglare,
			'reverse'     => $tilt_reverse,
		);

		// Dynamic Data is empty.
		if ( isset( $settings['image']['useDynamicData']['name'] ) ) {

			if ( empty( $settings['image']['id'] ) ) {

				if ( 'ACF' === $settings['image']['useDynamicData']['group'] && ! class_exists( 'ACF' ) ) {
					$message = esc_html__( 'Can\'t render element, as the selected ACF field is not available. Please activate ACF or edit the element to select different data.', 'bricksable' );
				} elseif ( '{featured_image}' === $settings['image']['useDynamicData']['name'] ) {
					$message = esc_html__( 'No featured image set.', 'bricksable' );
				} else {
					/* translators: 1: Data 2: Dynamic Value */
					$message = esc_html__( 'Dynamic Data %1$s (%2$s) is empty.', 'bricksable' );
				}

				$this->set_attribute( 'ba-no-image-wrapper', 'class', 'ba-tilt-image-wrapper' );
				$this->set_attribute( 'ba-no-image-wrapper', 'id', esc_attr( 'ba-tilt-image-' . $this->id ) );
				$this->set_attribute( 'ba-no-image-wrapper', 'data-ba-bricks-tilt-image-options', wp_json_encode( $tilt_options ) );

				echo '<div ' . $this->render_attributes( 'ba-no-image-wrapper' ) . '>'; //phpcs:ignore

				return $this->render_element_placeholder(
					array(
						'icon-class' => 'ti-image',
						'text'       => sprintf(
							$message,
							$settings['image']['useDynamicData']['label'],
							$settings['image']['useDynamicData']['group']
						),
					)
				);
				//phpcs:ignore
				echo '</div>';

			}
		} else {
			// Image id is empty or doesn't exist.
			// No image.
			if ( empty( $settings['image']['id'] ) ) {
				$this->set_attribute( 'ba-no-image-wrapper', 'class', 'ba-tilt-image-wrapper' );
				$this->set_attribute( 'ba-no-image-wrapper', 'id', esc_attr( 'ba-tilt-image-' . $this->id ) );
				$this->set_attribute( 'ba-no-image-wrapper', 'data-ba-bricks-tilt-image-options', wp_json_encode( $tilt_options ) );

				echo '<div ' . $this->render_attributes( 'ba-no-image-wrapper' ) . '>'; //phpcs:ignore

				return $this->render_element_placeholder(
					array(
						'icon-class' => 'ti-image',
						'text'       => esc_html__( 'No image selected.', 'bricksable' ),
					)
				);
				//phpcs:ignore
				echo '</div>';
			}

			// Return if image ID does not exist.
			if ( ! wp_get_attachment_image_src( $settings['image']['id'] ) ) {
				return $this->render_element_placeholder(
					array(
						'icon-class' => 'ti-image',
						/* translators: 1: Image ID */
						'text'       => sprintf( esc_html__( 'Image ID (%s) no longer exist. Please select another image.', 'bricksable' ), $settings['image']['id'] ),
					)
				);
			}
		}

		// Render.
		$image_atts       = array();
		$image_atts['id'] = 'image-' . $settings['image']['id'];

		$image_wrapper_classes = array( 'ba-tilt-image-wrapper' );
		$img_classes           = array( 'post-thumbnail', 'css-filter' );

		if ( isset( $settings['overlayShowHover'] ) ) {
			$image_wrapper_classes[] = 'ba-tilt-image-show-hover';
		}

		if ( isset( $settings['imageOverlayShowHover'] ) ) {
			$image_wrapper_classes[] = 'ba-tilt-image-overlay-hover';
		}

		if ( isset( $settings['link'] ) && 'lightbox' === $settings['link'] ) {
			$image_wrapper_classes[] = 'bricks-lightbox';

			$image_src = $settings['image']['id'] ? wp_get_attachment_image_src( $settings['image']['id'], 'full' ) : array(
				\Bricks\Builder::get_template_placeholder_image(),
				800,
				600,
			);
			$this->set_attribute( 'image-wrapper', 'data-bricks-lightbox-source="' . $image_src[0] . '"' );
			$this->set_attribute( 'image-wrapper', 'data-bricks-lightbox-width="' . $image_src[1] . '"' );
			$this->set_attribute( 'image-wrapper', 'data-bricks-lightbox-height="' . $image_src[2] . '"' );
		}

		$img_classes[]       = 'size-' . $settings['image']['size'];
		$image_atts['class'] = join( ' ', $img_classes );

		// Title.
		$show_title = isset( $settings['overlay_title'] ) ? $settings['overlay_title'] : 'none';

		if ( 'none' === $show_title ) {
			$image_title = false;
		} elseif ( 'custom' === $show_title && isset( $settings['overlay_title_custom'] ) ) {
			$image_title = trim( $settings['overlay_title_custom'] );
		} else {
			$image_data  = get_post( $settings['image']['id'] );
			$image_title = $image_data ? $image_data->post_title : '';
		}

		// Caption.
		$show_caption = isset( $settings['overlay_caption'] ) ? $settings['overlay_caption'] : 'none';

		if ( 'none' === $show_caption ) {
			$image_caption = false;
		} elseif ( 'custom' === $show_caption && isset( $settings['overlay_caption_custom'] ) ) {
			$image_caption = trim( $settings['overlay_caption_custom'] );
		} else {
			$image_data    = get_post( $settings['image']['id'] );
			$image_caption = $image_data ? $image_data->post_excerpt : '';
		}

		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			//phpcs:ignore
			echo "<div {$this->render_attributes( '_root' )}>";
		}

		$this->set_attribute( 'image-wrapper', 'data-ba-bricks-tilt-image-options', wp_json_encode( $tilt_options ) );

		$this->set_attribute( 'image-wrapper', 'class', $image_wrapper_classes );
		$this->set_attribute( 'image-wrapper', 'id', esc_attr( 'ba-tilt-image-' . $this->id ) );
		$this->set_attribute( 'image-wrapper', 'data-tilt' );
		$this->set_attribute( 'image-wrapper', 'data-tilt-max="' . $tilt_max . '"' );
		$this->set_attribute( 'image-wrapper', 'data-tilt-speed="' . $tilt_speed . '"' );
		$this->set_attribute( 'image-wrapper', 'data-tilt-perspective="' . $tilt_perspective . '"' );
		$this->set_attribute( 'image-wrapper', 'data-tilt-scale="' . $tilt_scale . '"' );
		$this->set_attribute( 'image-wrapper', true === $tilt_glare ? ' data-tilt-glare' : '' );
		$this->set_attribute( 'image-wrapper', 'data-tilt-max-glare="' . $tilt_maxglare . '"' );
		$this->set_attribute( 'image-wrapper', 'data-tilt-reverse="' . $tilt_reverse . '"' );

		echo '<figure ' . $this->render_attributes( 'image-wrapper' ) . '>'; //phpcs:ignore

		$close_a_tag = false;

		if ( isset( $settings['link'] ) && 'media' === $settings['link'] ) {
			$close_a_tag = true;

			echo '<a href="' . esc_url( wp_get_attachment_url( $settings['image']['id'] ) ) . '" target="_blank">';
		}

		if ( isset( $settings['link'] ) && 'attachment' === $settings['link'] ) {
			echo '<a href="' . esc_url( get_permalink( $settings['image']['id'] ) ) . '" target="_blank">';
		}

		if ( isset( $settings['link'] ) && 'url' === $settings['link'] && isset( $settings['url'] ) ) {
			$close_a_tag = true;

			// Link.
			$this->set_link_attributes( 'a', $settings['url'] );

			echo '<a ' . $this->render_attributes( 'a' ) . '>'; //phpcs:ignore
		}

		// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
		if ( isset( $settings['image']['id'] ) ) {
			echo wp_get_attachment_image( $settings['image']['id'], $settings['image']['size'], false, $image_atts );
		} elseif ( ! empty( $settings['image']['url'] ) ) {
			echo '<img src="' . esc_url( $settings['image']['url'] ) . '">';
		}

		if ( 'none' !== $show_title || 'none' !== $show_caption || isset( $settings['overlay_icon']['icon'] ) ) {
			$overlay_title_tag     = isset( $settings['overlay_title_tag'] ) ? esc_html( $settings['overlay_title_tag'] ) : 'h3';
			$overlay_title_classes = array(
				'ba-tilt-image-overlay-title',
				'bricks-heading',
				'bricks-heading-' . $overlay_title_tag,
			);
			$this->set_attribute( 'overlay_title', $overlay_title_tag );
			$this->set_attribute( 'overlay_title', 'class', $overlay_title_classes );
			if ( isset( $settings['overlay_icon']['icon'] ) ) {
				$icon_classes = array( 'overlay-icon', $settings['overlay_icon']['icon'], 'icon' );
				$this->set_attribute( 'overlay-icon-wrapper', 'class', 'overlay-icon-wrapper' );
				$this->set_attribute( 'overlay-icon', 'class', $icon_classes );
			}

			$overlay_title_output = '<' . $this->render_attributes( 'overlay_title' ) . '>' . esc_html( $image_title ) . '</' . $overlay_title_tag . '>';

			$this->set_attribute( 'image-overlay-wrapper', 'class', 'image-overlay-wrapper' );
			$this->set_attribute( 'image-overlay-content', 'class', 'image-overlay-content' );

			echo '<div ' . $this->render_attributes( 'image-overlay-wrapper' ) . '>'; //phpcs:ignore
			echo '<div ' . $this->render_attributes( 'image-overlay-content' ) . '>'; //phpcs:ignore
			if ( isset( $settings['overlay_icon']['icon'] ) ) {
				echo '<div ' . $this->render_attributes( 'overlay-icon-wrapper' ) . '>'; //phpcs:ignore
				echo '<i ' . $this->render_attributes( 'overlay-icon' ) . '></i>'; //phpcs:ignore
				echo '</div>';
			}
			if ( $image_title ) {
				//phpcs:ignore
				echo $overlay_title_output;
			}
			if ( $image_caption ) {
				echo '<figcaption class="ba-tilt-image-overlay-caption">' . esc_html( $image_caption ) . '</figcaption>';
			}
			echo '</div>';
			echo '</div>';
		}

		if ( $close_a_tag ) {
			echo '</a>';
		}

		echo '</figure>';
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			echo '</div>';
		}
	}
}
