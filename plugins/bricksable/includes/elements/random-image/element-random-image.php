<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Random_Image extends \Bricks\Element {

	public $category          = 'bricksable';
	public $name              = 'random-image';
	public $icon              = 'ti-image';

	public function get_label() {
		return esc_html__( 'Random Image', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['image'] = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		unset( $this->control_groups['_typography'] );
	}

	public function set_controls() {
		// Apply CSS filters only to img tag.
		$this->controls['_cssFilters']['css'] = array(
			array(
				'selector' => '&:is(img)',
				'property' => 'filter',
			),
			array(
				'selector' => 'img',
				'property' => 'filter',
			),
		);

		$this->controls['_typography']['css'][0]['selector'] = 'figcaption';

		// Images.
		$this->controls['randomImageGallery'] = array(
			'tab'   => 'content',
			'label' => esc_html__( 'Random Image gallery', 'bricksable' ),
			'type'  => 'image-gallery',
		);

		// @since 1.4.
		$this->controls['tag'] = array(
			'tab'         => 'content',
			'label'       => esc_html__( 'HTML tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'figure'  => 'figure',
				'picture' => 'picture',
				'div'     => 'div',
				'custom'  => esc_html__( 'Custom', 'bricksable' ),
			),
			'lowercase'   => true,
			'inline'      => true,
			'placeholder' => '-',
		);

		$this->controls['customTag'] = array(
			'tab'            => 'content',
			'label'          => esc_html__( 'Custom tag', 'bricksable' ),
			'type'           => 'text',
			'inline'         => true,
			'hasDynamicData' => false,
			'placeholder'    => 'div',
			'required'       => array( 'tag', '=', 'custom' ),
		);

		$this->controls['stretch'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'label' => esc_html__( 'Stretch', 'bricksable' ),
			'type'  => 'checkbox',
			'css'   => array(
				array(
					'property' => 'width',
					'value'    => '100%',
				),
			),
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
				'custom'     => esc_html__( 'Custom URL', 'bricksable' ),
			),
			'placeholder' => esc_html__( 'None', 'bricksable' ),
		);

		$this->controls['newTab'] = array(
			'tab'      => 'content',
			'group'    => 'image',

			'label'    => esc_html__( 'Open in new tab', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'link', '=', array( 'attachment', 'media' ) ),
		);

		$this->controls['url'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Link type', 'bricksable' ),
			'type'     => 'link',
			'required' => array( 'link', '=', 'url' ),
		);

		$this->controls['linkCustom'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'label'       => esc_html__( 'Custom links', 'bricksable' ),
			'type'        => 'repeater',
			'fields'      => array(
				'link' => array(
					'label'   => esc_html__( 'Link', 'bricksable' ),
					'type'    => 'link',
					'exclude' => array(
						'lightboxImage',
						'lightboxVideo',
					),
				),
			),
			'placeholder' => esc_html__( 'Custom link', 'bricksable' ),
			'required'    => array( 'link', '=', 'custom' ),
		);
	}

	public function enqueue_scripts() {
		if ( isset( $this->settings['link'] ) && 'lightbox' === $this->settings['link'] ) {
			wp_enqueue_script( 'bricks-photoswipe' );
			wp_enqueue_style( 'bricks-photoswipe' );
		}
	}

	public function get_random_image_settings( $settings ) {
		$items = isset( $settings['randomImageGallery'] ) ? $settings['randomImageGallery'] : array();

		$size = ! empty( $items['size'] ) ? $items['size'] : BRICKS_DEFAULT_IMAGE_SIZE;

		// Dynamic Data.
		if ( ! empty( $items['useDynamicData'] ) ) {
			$items['images'] = array();

			$images = $this->render_dynamic_data_tag( $items['useDynamicData'], 'image' );

			if ( is_array( $images ) ) {
				foreach ( $images as $image_id ) {
					$items['images'][] = array(
						'id'   => $image_id,
						'full' => wp_get_attachment_image_url( $image_id, 'full' ),
						'url'  => wp_get_attachment_image_url( $image_id, $size ),
					);
				}
			}
		}

		// Either empty OR old data structure used before 1.0 (images were saved as one array directly on $items).
		if ( ! isset( $items['images'] ) ) {
			$images = ! empty( $items ) ? $items : array();

			unset( $items );

			$items['images'] = $images;
		}

		// Get 'size' from first image if not set (previous to 1.4-RC).
		$first_image_size = ! empty( $items['images'][0]['size'] ) ? $items['images'][0]['size'] : false;
		$size             = empty( $items['size'] ) && $first_image_size ? $first_image_size : $size;

		// Calculate new image URL if size is not the same as from the Media Library.
		if ( $first_image_size && $first_image_size !== $size ) {
			foreach ( $items['images'] as $key => $image ) {
				$items['images'][ $key ]['size'] = $size;
				$items['images'][ $key ]['url']  = wp_get_attachment_image_url( $image['id'], $size );
			}
		}

		$settings['items'] = $items;

		$settings['items']['size'] = $size;

		return $settings;
	}

	public function render() {
		$settings       = $this->settings;
		$image_settings = $this->get_random_image_settings( $this->settings );
		$images         = ! empty( $image_settings['items']['images'] ) ? $image_settings['items']['images'] : false;
		$size           = ! empty( $image_settings['items']['size'] ) ? $image_settings['items']['size'] : BRICKS_DEFAULT_IMAGE_SIZE;
		$link_to        = ! empty( $settings['link'] ) ? $settings['link'] : false;
		$has_html_tag   = isset( $settings['tag'] );

		// Return placeholder.
		if ( ! $images ) {
			if ( ! empty( $settings['items']['useDynamicData'] ) ) {
				if ( BRICKS_DB_TEMPLATE_SLUG !== get_post_type( $this->post_id ) ) {
					return $this->render_element_placeholder(
						array(
							'title' => esc_html__( 'Dynamic data is empty.', 'bricks' ),
						)
					);
				}
			} else {
				return $this->render_element_placeholder(
					array(
						'title' => esc_html__( 'No image selected.', 'bricks' ),
					)
				);
			}
		}

		// Check: Global classes on Image element for '_gradient' setting.
		$element_global_classes = ! empty( $settings['_cssGlobalClasses'] ) ? $settings['_cssGlobalClasses'] : false;

		if ( ! $has_html_tag && is_array( $element_global_classes ) ) {
			$all_global_classes = Database::$global_data['globalClasses'];

			foreach ( $element_global_classes as $element_global_class ) {
				$index        = array_search( $element_global_class, array_column( $all_global_classes, 'id' ), true );
				$global_class = ! empty( $all_global_classes[ $index ] ) ? $all_global_classes[ $index ] : false;

				// Global class has 'gradient' setting: Add HTML tag to Image element to make ::before work.
				if ( $global_class && strpos( wp_json_encode( $global_class ), '_gradient' ) ) {
					$has_html_tag = true;
				}
			}
		}

		/**
		 * Render: Wrap 'img' HTML tag in HTML tag (if 'tag' set) or anchor tag (if 'link' set)
		 */
		$output = '';

		// Add _root attributes to outermost tag.
		if ( $has_html_tag ) {
			$this->set_attribute( '_root', 'class', 'tag brxe-image ' );

			$output .= "<{$this->tag} {$this->render_attributes( '_root' )}>";
		}

		if ( $images ) {
			foreach ( $images as $index => $item ) {
				$rand_keys        = array_rand( $images, 1 );
				$image            = $images[ $rand_keys ];
				$image_atts['id'] = 'image-' . $image['id'];
				$close_a_tag      = false;

				if ( $link_to ) {
					if ( isset( $settings['newTab'] ) ) {
						$this->set_attribute( 'link', 'target', '_blank' );
					}
					if ( 'attachment' === $link_to && isset( $image['id'] ) ) {
						$close_a_tag = true;
						$this->set_attribute( 'link', 'href', get_permalink( $image['id'] ) );
					} elseif ( 'media' === $link_to ) {
						$close_a_tag = true;
						$this->set_attribute( 'link', 'href', wp_get_attachment_url( $image['id'] ) );
					} elseif ( 'custom' === $link_to && isset( $settings['linkCustom'][ $index ]['link'] ) ) {
						$close_a_tag = true;
						$this->set_link_attributes( "a-$index", $settings['linkCustom'][ $index ]['link'] );
					}
					if ( 'custom' === $link_to ) {
						$output .= "<a {$this->render_attributes( "a-$index" )}>";
					} else {
						$output .= "<a {$this->render_attributes( 'link' )}>";
					}
				}

				// Render.
				$image_atts  = array();
				$img_classes = array( 'post-thumbnail', 'image' );
				if ( isset( $size ) ) {
					$img_classes[] = 'size-' . $size;
				}
				if ( ! $has_html_tag ) {
					$img_classes[] = $this->attributes['_root']['class'][0];
				}

				if ( isset( $settings['link'] ) && 'lightbox' === $settings['link'] ) {
					$img_classes[]                             = 'bricks-lightbox';
					$image_src                                 = $image['id'] ? wp_get_attachment_image_src( $image['id'], $size ) : array(
						\Bricks\Builder::get_template_placeholder_image(),
						800,
						600,
					);
					$image_atts['data-bricks-lightbox-source'] = $image_src[0];
					$image_atts['data-bricks-lightbox-width']  = $image_src[1];
					$image_atts['data-bricks-lightbox-height'] = $image_src[2];
					$image_atts['data-bricks-lightbox-index']  = $index;
					$image_atts['data-bricks-lightbox-id']     = $this->id;
				}
				$image_atts['class'] = join( ' ', $img_classes );

				if ( ! $has_html_tag ) {

					$this->set_attribute( 'img', 'class', 'css-filter' );

					foreach ( $this->attributes['_root'] as $key => $value ) {
						$image_attributes[ $key ] = is_array( $value ) ? join( ' ', $value ) : $value;
					}

					foreach ( $this->attributes['img'] as $key => $value ) {
						if ( isset( $image_attributes[ $key ] ) ) {
							$image_attributes[ $key ] .= ' ' . ( is_array( $value ) ? join( ' ', $value ) : $value );
						} else {
							$image_attributes[ $key ] = is_array( $value ) ? join( ' ', $value ) : $value;
						}
					}
					// Merge custom attributes with img attributes.
					$custom_attributes = $this->get_custom_attributes( $settings );
					$image_attributes  = array_merge( $image_attributes, $custom_attributes, $image_atts );
					$output           .= wp_get_attachment_image( $image['id'], $size, false, $image_attributes );
				} else {
					$output .= wp_get_attachment_image(
						$image['id'],
						$size,
						false,
						$image_atts
					);
				}
				if ( $close_a_tag ) {
					$output .= '</a>';
				}
				break;
			}
		} else {
			esc_html_e( 'No image(s) selected.', 'bricksable' );
		}

		if ( $has_html_tag ) {
			$output .= "</{$this->tag}>";
		}
		//phpcs:ignore
		echo $output;
	}
}
