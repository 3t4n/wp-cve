<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Floating extends \Bricks\Element {
	public $category = 'bricksable';
	public $name     = 'ba-floating';
	public $icon     = 'ti-layers-alt';

	public function get_label() {
		return esc_html__( 'Floating', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['items']    = array(
			'title' => esc_html__( 'Float items', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['item']     = array(
			'title' => esc_html__( 'Float item / layout', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['image']    = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['icon']     = array(
			'title' => esc_html__( 'Icon', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['title']    = array(
			'title' => esc_html__( 'Title', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Floating Settings', 'bricksable' ),
			'tab'   => 'content',
		);

	}
	public function set_controls() {
		// Items.
		$this->controls['items'] = array(
			'tab'           => 'content',
			'group'         => 'items',
			'placeholder'   => esc_html__( 'Float items', 'bricksable' ),
			'type'          => 'repeater',
			'selector'      => '.ba-floating-item',
			'titleProperty' => 'title',
			'fields'        => array(
				'title'            => array(
					'label'          => esc_html__( 'Title', 'bricksable' ),
					'type'           => 'text',
					'hasDynamicData' => 'text',
					'inlineEditing'  => array(
						'selector' => '.title',
					),
				),
				'link'             => array(
					'label' => esc_html__( 'Link', 'bricksable' ),
					'type'  => 'link',
				),
				'top'              => array(
					'label' => esc_html__( 'Vertical Position', 'bricksable' ),
					'type'  => 'number',
					'css'   => array(
						array(
							'property' => 'top',
							// 'repeaterSelector' => '.ba-floating-item',
						),
					),
					'units' => array(
						'%' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 10,
						),

					),
				),
				'left'             => array(
					'label' => esc_html__( 'Horizontal Position', 'bricksable' ),
					'type'  => 'number',
					'css'   => array(
						array(
							'property' => 'left',
							// 'repeaterSelector' => '.ba-floating-item',
						),
					),
					'units' => array(
						'%' => array(
							'min'  => 0,
							'max'  => 100,
							'step' => 10,
						),

					),
				),
				'use_image'        => array(
					'label'  => esc_html__( 'Use Image', 'bricksable' ),
					'type'   => 'checkbox',
					'inline' => true,
				),
				'image'            => array(
					'label'    => esc_html__( 'Image', 'bricksable' ),
					'type'     => 'image',
					'required' => array( 'use_image', '=', true ),
				),
				'image_border'     => array(
					'label'    => esc_html__( 'Image Border', 'bricksable' ),
					'type'     => 'border',
					'css'      => array(
						array(
							'property' => 'border',
							'selector' => '.ba-floating-image',
						),
					),
					'required' => array( 'use_image', '=', true ),
				),
				'image_box_shadow' => array(
					'label'    => esc_html__( 'Image Box Shadow', 'bricksable' ),
					'type'     => 'box-shadow',
					'css'      => array(
						array(
							'property' => 'box-shadow',
							'selector' => '.ba-floating-image',
						),
					),
					'required' => array( 'use_image', '=', true ),
				),
				'use_icon'         => array(
					'label'  => esc_html__( 'Use Icon', 'bricksable' ),
					'type'   => 'checkbox',
					'inline' => true,
				),
				'icon'             => array(
					'label'    => esc_html__( 'Icon', 'bricksable' ),
					'type'     => 'icon',
					'required' => array( 'use_icon', '=', true ),
				),
				'icon_typography'  => array(
					'label'    => esc_html__( 'Icon typography', 'bricksable' ),
					'type'     => 'typography',
					'exclude'  => array(
						'font-family',
						'font-weight',
						'font-style',
						'text-align',
						'text-transform',
						'text-decoration',
						'line-height',
						'letter-spacing',
					),
					'css'      => array(
						array(
							'property' => 'font',
							'selector' => '.icon',
						),
					),
					'inline'   => true,
					'small'    => true,
					'required' => array( 'use_icon', '=', true ),
				),
				'use_gradient'     => array(
					'label'  => esc_html__( 'Use Gradient / Overlay', 'bricksable' ),
					'type'   => 'checkbox',
					'inline' => true,
				),
				'gradient'         => array(
					'label'    => esc_html__( 'Gradient', 'bricksable' ),
					'type'     => 'gradient',
					'css'      => array(
						array(
							'property' => 'background-image',
							// 'repeaterSelector' => '.ba-floating-item',
						),
					),
					'required' => array( 'use_gradient', '=', true ),
				),
				'width'            => array(
					'label'   => esc_html__( 'Item Width', 'bricksable' ),
					'type'    => 'number',
					'default' => '100%',
					'css'     => array(
						array(
							'property' => 'max-width',
						),
					),
					'units'   => array(
						'%'  => array(
							'min'  => 1,
							'max'  => 100,
							'step' => 1,
						),
						'px' => array(
							'min'  => 1,
							'max'  => 1600,
							'step' => 1,
						),
					),
				),
				'padding'          => array(
					'label' => esc_html__( 'Item Padding', 'bricksable' ),
					'type'  => 'dimensions',
					'css'   => array(
						array(
							'property' => 'padding',
						),
					),
				),
				'border'           => array(
					'label' => esc_html__( 'Item Border', 'bricksable' ),
					'type'  => 'border',
					'css'   => array(
						array(
							'property' => 'border',
						),
						array(
							'property' => 'overflow',
							'value'    => 'hidden',
						),
					),
				),
				'box_shadow'       => array(
					'label' => esc_html__( 'Item Box Shadow', 'bricksable' ),
					'type'  => 'box-shadow',
					'css'   => array(
						array(
							'property' => 'box-shadow',
						),
					),
				),
			),
			'default'       => array(
				array(
					'use_gradient' => false,
					'use_image'    => true,
					'use_icon'     => false,
					'image'        => array(
						'full' => 'https://source.unsplash.com/random/700x700?animal',
						'url'  => 'https://source.unsplash.com/random/400x400?animal',
					),
					'top'          => '0%',
					'left'         => '0%',
				),
				array(
					'use_gradient' => false,
					'use_image'    => true,
					'use_icon'     => false,
					'image'        => array(
						'full' => 'https://source.unsplash.com/random/700x700?human',
						'url'  => 'https://source.unsplash.com/random/400x400?human',
					),
					'top'          => '20%',
					'left'         => '20%',
				),
			),
		);

		// Item.
		$this->controls['item_direction'] = array(
			'tab'         => 'content',
			'group'       => 'item',
			'label'       => esc_html__( 'Content Direction (Title & Icon)', 'bricksable' ),
			'type'        => 'direction',
			'css'         => array(
				array(
					'property' => 'flex-direction',
					'selector' => '.ba-floating-item .content-wrapper',
				),
			),
			'default'     => 'column',
			'placeholder' => esc_html__( 'Stacked (Column)', 'bricksable' ),
		);

		$this->controls['item_padding'] = array(
			'tab'   => 'content',
			'group' => 'item',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-floating-item',
				),
			),
		);

		$this->controls['item_width'] = array(
			'tab'     => 'content',
			'group'   => 'item',
			'label'   => esc_html__( 'Item width', 'bricksable' ),
			'type'    => 'number',
			'default' => '100%',
			'css'     => array(
				array(
					'property' => 'max-width',
					'selector' => '.ba-floating-item',
				),
			),
			'units'   => array(
				'%'  => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
				'px' => array(
					'min'  => 1,
					'max'  => 1600,
					'step' => 1,
				),
			),
		);

		$this->controls['item_border'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Item border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-floating-item',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['item_shadow'] = array(
			'tab'    => 'content',
			'group'  => 'item',
			'label'  => esc_html__( 'Item shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-floating-item',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Title.
		$this->controls['title_margin'] = array(
			'tab'   => 'content',
			'group' => 'title',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.content-wrapper .title-wrapper .title',
				),
			),
		);

		$this->controls['title_tag'] = array(
			'tab'         => 'content',
			'group'       => 'title',
			'label'       => esc_html__( 'Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1'   => esc_html__( 'Heading 1 (h1)', 'bricksable' ),
				'h2'   => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3'   => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4'   => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5'   => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6'   => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
				'p'    => esc_html__( 'Paragraph (p)', 'bricksable' ),
				'span' => esc_html__( 'Span (span)', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => 'h4',
		);

		$this->controls['title_JustifyContent'] = array(
			'tab'     => 'content',
			'group'   => 'title',
			'label'   => esc_html__( 'Align', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => array(
				'stretch',
			),
			'css'     => array(
				array(
					'property' => 'justify-content',
					'selector' => '.content-wrapper',
				),
			),
			'inline'  => true,
		);

		$this->controls['title_verticalAlign'] = array(
			'tab'         => 'style',
			'group'       => 'title',
			'label'       => esc_html__( 'Vertical align', 'bricksable' ),
			'type'        => 'justify-content',
			'exclude'     => array(
				'space-between',
				'space-around',
				'space-evenly',
			),
			'css'         => array(
				array(
					'property' => 'align-items',
					'selector' => '.content-wrapper',
				),
			),
			'inline'      => true,
			'default'     => 'center',
			'placeholder' => esc_html__( 'Center', 'bricksable' ),
		);

		$this->controls['titleTypography'] = array(
			'tab'    => 'content',
			'group'  => 'title',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.title',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Image.
		$this->controls['imageWidth'] = array(
			'tab'     => 'content',
			'group'   => 'image',
			'label'   => esc_html__( 'Image width', 'bricksable' ),
			'type'    => 'number',
			'default' => '100%',
			'css'     => array(
				array(
					'property' => 'max-width',
					'selector' => '.ba-floating-image',
				),
			),
			'units'   => array(
				'%'  => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
				'px' => array(
					'min'  => 1,
					'max'  => 1600,
					'step' => 1,
				),
			),
		);

		$this->controls['imageBorder'] = array(
			'tab'    => 'content',
			'group'  => 'image',
			'label'  => esc_html__( 'Image border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-floating-image',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['imageShadow'] = array(
			'tab'    => 'content',
			'group'  => 'image',
			'label'  => esc_html__( 'Image shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-floating-image',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Icon.
		$this->controls['icon_typography'] = array(
			'tab'     => 'content',
			'group'   => 'icon',
			'label'   => esc_html__( 'Icon typography', 'bricksable' ),
			'type'    => 'typography',
			'exclude' => array(
				'font-family',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
			),
			'css'     => array(
				array(
					'property' => 'font',
					'selector' => '.icon',
				),
			),
			'inline'  => true,
			'small'   => true,
		);
		$this->controls['icon_border']     = array(
			'tab'    => 'content',
			'group'  => 'icon',
			'label'  => esc_html__( 'Icon border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['icon_boxshadow'] = array(
			'tab'    => 'content',
			'group'  => 'icon',
			'label'  => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.icon',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Animation.
		$this->controls['height'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Height', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'min-height',
					'selector' => '.ba-floating-wrapper',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 1,
					'max'  => 1600,
					'step' => 1,
				),
			),
			'default'     => '460px',
			'description' => esc_html__( "Adjust the floating container's height.", 'bricksable' ),
		);

		$this->controls['animation'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Animation Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'horizontal' => esc_html__( 'Horizontal', 'bricksable' ),
				'vertical'   => esc_html__( 'Vertical', 'bricksable' ),
			),
			'default'     => 'vertical',
			'inline'      => true,
			'placeholder' => esc_html__( 'Vertical', 'bricksable' ),
		);

		$this->controls['animation_speed'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Animation Speed', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'animation-duration',
					'selector' => '.ba-floating-wrapper .ba-floating-item',
				),
			),
			'units'       => array(
				's' => array(
					'min'  => 2,
					'max'  => 10,
					'step' => 1,
				),
			),
			'default'     => '6s',
			'placeholder' => esc_html__( '6', 'bricksable' ),
			'description' => esc_html__( 'Increase or decrease the speed.', 'bricksable' ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-floating' );
	}

	public function render() {
		$settings = $this->settings;

		$items = $settings['items'];
		// Wrapper.
		$wrapper_classes = array( 'ba-floating-wrapper', 'ba-floating-animation-' . $settings['animation'] );
		$this->set_attribute( 'wrapper', 'class', $wrapper_classes );
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			//phpcs:ignore
			echo "<div {$this->render_attributes( '_root' )}>";
		}
		?>
		<div <?php echo $this->render_attributes( 'wrapper' ); //phpcs:ignore ?>>
		<?php
		if ( count( $items ) ) {
			foreach ( $items as $index => $item ) {
				$content_image_classes = isset( $item['use_image'] ) && null !== $item['use_image'] || isset( $item['title'] ) && ! empty( $item['title'] ) ? 'ba-floating-image-title' : '';
				$no_image_classes      = isset( $item['use_image'] ) && null !== $item['use_image'] ? 'ba-floating-no-image' : '';
				$item_classes          = array( 'ba-floating-item', 'repeater-item', $content_image_classes, $no_image_classes );
				$temp_string           = 'calc(500ms - ';
				$delay_animation       = 'animation-delay: ' . $temp_string . $index . '000ms);';
				$this->set_attribute( "floating-item-{$index}", 'class', $item_classes );
				$this->set_attribute( "floating-item-{$index}", 'style', esc_attr( $delay_animation ) );

				// Image.
				$image_atts           = array();
				$image_atts['id']     = isset( $image_atts['id'] ) ? 'image-' . $item['image']['id'] : '';
				$item_image_classes   = array( 'ba-floating-image', 'css-filter' );
				$item_image_classes[] = isset( $item['image']['size'] ) ? 'size-' . $item['image']['size'] : '';
				$image_atts['class']  = join( ' ', $item_image_classes );

				if ( isset( $item['image'] ) ) {
					$this->set_attribute( "floating-image-{$index}", 'class', $item_image_classes );
					$this->set_attribute( "floating-image-{$index}", 'src', esc_url( $item['image']['url'] ) );
				}

				?>

				<div <?php echo $this->render_attributes( "floating-item-$index" ); //phpcs:ignore ?>> 
					<?php
					if ( isset( $item['link'] ) ) {
						$this->set_link_attributes( "a-$index", $item['link'] );
						echo '<a ' . $this->render_attributes( "a-$index" ) . '>'; //phpcs:ignore
					}
					if ( isset( $item['use_image'] ) && null !== $item['use_image'] ) {
						// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
						if ( isset( $item['image']['id'] ) ) {
							echo wp_get_attachment_image( $item['image']['id'], $item['image']['size'], false, $image_atts );
						} elseif ( ! empty( $item['image']['url'] ) ) {
							echo '<img ' . $this->render_attributes( "floating-image-{$index}" ) . '>'; //phpcs:ignore
						}
					}

					// Title.
					if ( ( isset( $item['title'] ) && ! empty( $item['title'] ) ) || isset( $item['use_icon'] ) && null !== $item['use_icon'] ) {
						$title_tag = isset( $settings['title_tag'] ) ? esc_attr( $settings['title_tag'] ) : 'h4';
						$title     = isset( $item['title'] ) && ! empty( $item['title'] ) ? esc_html( $item['title'] ) : '';
						$this->set_attribute( "title-$index", $title_tag );
						$this->set_attribute( "title-$index", 'class', array( 'title' ) );
						$this->set_attribute( "content-wrapper-$index", 'class', array( 'content-wrapper' ) );

						// Icon.
						if ( isset( $item['icon']['icon'] ) ) {
							$this->set_attribute(
								"icon-{$index}",
								'class',
								array(
									'icon',
									$item['icon']['icon'],
								)
							);
						}
						$this->set_attribute( "icon-wrapper-$index", 'class', 'icon-wrapper' );

						echo '<div ' . $this->render_attributes( "content-wrapper-{$index}" ) . '>'; //phpcs:ignore
						$icon = ! empty( $item['icon'] ) ? self::render_icon( $item['icon'] ) : false;

						if ( $icon && null !== $item['use_icon'] ) {
							echo '<div ' . $this->render_attributes( "icon-wrapper-{$index}" ) . '>'; //phpcs:ignore
							echo '<span class="icon">'; //phpcs:ignore
							echo  $icon; //phpcs:ignore
							echo '</span>';
							echo '</div>';
						}
						echo '<div class="title-wrapper">';
						echo '<' . $this->render_attributes( "title-$index" ) . '>' . esc_html( $title ) . '</' . esc_html( $title_tag ) . '>'; //phpcs:ignore
						echo '</div></div>';
					}
					if ( isset( $item['link'] ) ) {
						echo '</a>';
					}
					?>
				</div>

				<?php
			}
			?>
			<?php
		} else {
			esc_html_e( 'No items defined.', 'bricksable' );
		}

		?>
		</div>
		<?php
		if ( substr( BRICKS_VERSION, 0, 3 ) > '1.3' ) {
			echo '</div>';
		}
	}
}
