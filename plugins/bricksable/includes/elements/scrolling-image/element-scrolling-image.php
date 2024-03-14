<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Scrolling_Image extends \Bricks\Element {
	public $category     = 'bricksable';
	public $name         = 'ba-scrolling-image';
	public $icon         = 'ti-image';
	public $css_selector = '.ba-scrolling-image';
	public $scripts      = array( 'bricksableScrollingImage' );

	public $nestable = false;

	public function get_label() {
		return esc_html__( 'Scrolling Image', 'bricksable' );
	}
	public function get_keywords() {
		return array(
			esc_html__( 'Bricksable', 'bricksable' ),
		);
	}

	public function set_control_groups() {
		// Content.
		$this->control_groups['image']        = array(
			'title' => esc_html__( 'Image', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['settings']     = array(
			'title' => esc_html__( 'Scroll Settings', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['badge']        = array(
			'title' => esc_html__( 'Badge', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['imageOverlay'] = array(
			'title' => esc_html__( 'Image Overlay & Icon', 'bricksable' ),
			'tab'   => 'content',
		);
		// Style.
		$this->control_groups['badge_style'] = array(
			'title' => esc_html__( 'Badge', 'bricksable' ),
			'tab'   => 'style',
		);
	}

	public function set_controls() {
		$this->controls['_boxShadow']['css'][0]['selector'] = '.ba-image-scroller-container';
		$this->controls['_gradient']['css']                 = array(
			array(
				'selector' => '.ba-image-scroller-img',
				'property' => 'background-image',
			),
		);
		$this->controls['_border']['css']                   = array(
			array(
				'selector' => '.ba-image-scroller-container',
				'property' => 'border',
			),
		);

		// Apply CSS filters only to img tag.
		$this->controls['_cssFilters']['css'] = array(
			array(
				'selector' => '&:is(.ba-image-scroller-img)',
				'property' => 'filter',
			),
			array(
				'selector' => '.ba-image-scroller-img',
				'property' => 'filter',
			),
		);
		// Content.
		$this->controls['image'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'type'  => 'image',
		);
		// @since 1.4
		$this->controls['tag'] = array(
			'tab'         => 'content',
			'group'       => 'image',
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
			'group'          => 'image',
			'label'          => esc_html__( 'Custom tag', 'bricksable' ),
			'type'           => 'text',
			'inline'         => true,
			'hasDynamicData' => false,
			'placeholder'    => 'div',
			'required'       => array( 'tag', '=', 'custom' ),
		);

		// Alt text.
		$this->controls['altText'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Custom alt text', 'bricksable' ),
			'type'     => 'text',
			'inline'   => true,
			'rerender' => false,
			'required' => array( 'image', '!=', '' ),
		);

		// 'loading' attribute (@ssince 1.6.2)
		$this->controls['loading'] = array(
			'tab'         => 'content',
			'group'       => 'image',
			'label'       => esc_html__( 'Loading', 'bricksable' ),
			'type'        => 'select',
			'inline'      => true,
			'options'     => array(
				'eager' => 'eager',
				'lazy'  => 'lazy',
			),
			'placeholder' => 'lazy',
		);

		// 'title' attribute (@since 1.6.2)
		$this->controls['showTitle'] = array(
			'tab'      => 'content',
			'group'    => 'image',
			'label'    => esc_html__( 'Show title', 'bricksable' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'image', '!=', '' ),
		);

		$this->controls['stretch'] = array(
			'tab'   => 'content',
			'group' => 'image',
			'label' => esc_html__( 'Stretch', 'bricksable' ),
			'type'  => 'checkbox',
			'css'   => array(
				array(
					'property' => 'width',
					'selector' => '',
					'value'    => '100%',
				),
				array(
					'property' => 'width',
					'selector' => '.ba-image-scroller-img',
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
				'url'        => esc_html__( 'Other (URL)', 'bricksable' ),
			),
			'rerender'    => true,
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
			'type'     => 'link',
			'required' => array( 'link', '=', 'url' ),
		);

		$this->controls['popupOverlay'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Image overlay', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '& .overlay::before',
				),
			),
			'rerender' => true,
		);

		$this->controls['popupOverlayOnHover'] = array(
			'tab'   => 'content',
			'group' => 'imageOverlay',
			'label' => esc_html__( 'Show Overlay on Hover', 'bricksable' ),
			'type'  => 'checkbox',
		);

		// Icon.
		$this->controls['popupSeparator'] = array(
			'tab'    => 'content',
			'group'  => 'imageOverlay',
			'label'  => esc_html__( 'Icon', 'bricksable' ),
			'type'   => 'separator',
			'inline' => true,
			'small'  => true,
		);

		// To hide icon for specific elements when image icon set in styles.
		$this->controls['popupIconDisable'] = array(
			'tab'   => 'content',
			'group' => 'imageOverlay',
			'label' => esc_html__( 'Disable icon', 'bricksable' ),
			'type'  => 'checkbox',
		);

		$this->controls['popupIcon'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Icon', 'bricksable' ),
			'type'     => 'icon',
			'inline'   => true,
			'small'    => true,
			'rerender' => true,
		);

		// NOTE: Set popup CSS control outside of control 'link' (CSS is not applied to nested controls).
		$this->controls['popupIconBackgroundColor'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Icon background color', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'background-color',
					'selector' => '.icon',
				),
			),
			'required' => array( 'popupIcon', '!=', '' ),
		);

		$this->controls['popupIconBorder'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Icon border', 'bricksable' ),
			'type'     => 'border',
			'css'      => array(
				array(
					'property' => 'border',
					'selector' => '.icon',
				),
			),
			'required' => array( 'popupIcon', '!=', '' ),
		);

		$this->controls['popupIconBoxShadow'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'     => 'box-shadow',
			'css'      => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.icon',
				),
			),
			'required' => array( 'popupIcon', '!=', '' ),
		);

		$this->controls['popupIconHeight'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Icon height', 'bricksable' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'line-height',
					'selector' => '.icon',
				),
			),
			'required' => array( 'popupIcon', '!=', '' ),
		);

		$this->controls['popupIconWidth'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Icon width', 'bricksable' ),
			'type'     => 'number',
			'units'    => true,
			'css'      => array(
				array(
					'property' => 'width',
					'selector' => '.icon',
				),
			),
			'required' => array( 'popupIcon', '!=', '' ),
		);

		$this->controls['popupIconTypography'] = array(
			'tab'         => 'content',
			'group'       => 'imageOverlay',
			'label'       => esc_html__( 'Icon typography', 'bricksable' ),
			'type'        => 'typography',
			'css'         => array(
				array(
					'property' => 'font',
					'selector' => '.icon',
				),
			),
			'exclude'     => array(
				'font-family',
				'font-weight',
				'font-style',
				'text-align',
				'text-decoration',
				'text-transform',
				'line-height',
				'letter-spacing',
			),
			'placeholder' => array(
				'font-size' => 60,
			),
			'required'    => array( 'popupIcon.icon', '!=', '' ),
		);

		$this->controls['popupIconAnimationSeparator'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Hover Animation', 'bricksable' ),
			'type'     => 'separator',
			'inline'   => true,
			'small'    => true,
			'required' => array( 'popupIcon', '!=', '' ),
		);

		$this->controls['popupIconOnHover'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Show Icon on Hover', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array(
				'hidePopupIconOnHover',
				'!=',
				true,
			),
		);

		$this->controls['hidePopupIconOnHover'] = array(
			'tab'      => 'content',
			'group'    => 'imageOverlay',
			'label'    => esc_html__( 'Hide Icon on Hover', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array(
				'popupIconOnHover',
				'!=',
				true,
			),
		);

		$this->controls['popupIconHover'] = array(
			'tab'         => 'content',
			'group'       => 'imageOverlay',
			'label'       => esc_html__( 'Animation Duration (ms)', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'transition-duration',
					'selector' => '.icon',
				),
				array(
					'property' => '-webkit-transition-duration',
					'selector' => '.icon',
				),
			),
			'unit'        => 'ms',
			'placeholder' => '2000',
			'description' => esc_html__( 'Adjust the animation duration speed in millisecond (ms) as per your requirement.', 'bricksable' ),
			'inline'      => true,
			'required'    => array( 'popupIcon', '!=', '' ),
		);

		// Badge.
		$this->controls['badge']        = array(
			'tab'     => 'content',
			'group'   => 'badge',
			'label'   => esc_html__( 'Show Badge', 'bricksable' ),
			'type'    => 'checkbox',
			'default' => false,
			'inline'  => true,
		);
		$this->controls['badgeOnHover'] = array(
			'tab'      => 'content',
			'group'    => 'badge',
			'label'    => esc_html__( 'Show Badge on Hover', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'badge', '=', true ),
		);
		$this->controls['badge_text']   = array(
			'tab'      => 'content',
			'group'    => 'badge',
			'label'    => esc_html__( 'Badge Text', 'bricksable' ),
			'type'     => 'text',
			'default'  => 'Badge',
			'inline'   => false,
			'required' => array( 'badge', '=', true ),
		);

		$this->controls['scrollTrigger'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Trigger', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'mouseover' => esc_html__( 'Hover', 'bricksable' ),
				'enterView' => esc_html__( 'Enter viewport', 'bricksable' ),
			),
			'default'     => 'mouseover',
			'inline'      => false,
			'placeholder' => 'Hover',
		);

		$this->controls['TriggerRunOnce'] = array(
			'tab'      => 'content',
			'group'    => 'settings',
			'label'    => esc_html__( 'Run only once', 'bricksable' ),
			'type'     => 'checkbox',
			'required' => array( 'scrollTrigger', '!=', array( 'mouseover' ) ),
		);

		$this->controls['scrollDirectionInfo'] = array(
			'tab'     => 'content',
			'group'   => 'settings',
			'content' => esc_html__( 'For optimal experience, if you are using the (Top to Bottom / Bottom to Top) direction, set the width to 100%. If you are using the (Left to Right / Right to Left) direction, set the height to 100%.', 'bricksable' ),
			'type'    => 'info',
		);

		$this->controls['scroll_direction'] = array(
			'tab'     => 'content',
			'group'   => 'settings',
			'label'   => esc_html__( 'Direction', 'bricksable' ),
			'type'    => 'select',
			'options' => array(
				'top'    => esc_html__( 'Top to Bottom', 'bricksable' ),
				'bottom' => esc_html__( 'Bottom to Top', 'bricksable' ),
				'left'   => esc_html__( 'Left to Right', 'bricksable' ),
				'right'  => esc_html__( 'Right to Left', 'bricksable' ),
			),
			'default' => 'top',
			'inline'  => true,
		);

		$this->controls['speed'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Speed', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'transition-duration',
					'selector' => '.ba-image-scroller-container .ba-image-scroller-img',
				),
				array(
					'property' => '-webkit-transition-duration',
					'selector' => '.ba-image-scroller-container .ba-image-scroller-img',
				),
			),
			'unit'        => 's',
			'default'     => '5',
			'description' => esc_html__( 'Adjust the scroll speed in seconds (s) as per your requirement. By default, the scroll speed is set to 10 seconds.', 'bricksable' ),
			'inline'      => true,
		);

		$this->controls['container_height'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Container Height', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'height',
					'selector' => '.ba-image-scroller-container',
				),
				array(
					'property' => 'margin-top',
					'selector' => '.ba-image-scroller-top:hover .ba-image-scroller-img',
				),
				array(
					'property' => 'margin-bottom',
					'selector' => '.ba-image-scroller-bottom .ba-image-scroller-img',
				),
			),
			'units'       => 'px',
			'default'     => '420px',
			'description' => esc_html__( 'Please ensure that the height of the container is less than the actual height and width of the image. If the container size is greater than the image size, the scroll feature may not work properly.', 'bricksable' ),
			'inline'      => false,
		);

		$this->controls['container_width'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Container Width', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'width',
					'selector' => '.ba-image-scroller-container',
				),
				array(
					'property' => 'margin-left',
					'selector' => '.ba-image-scroller-left:hover .ba-image-scroller-img',
				),

				array(
					'property' => 'margin-left',
					'selector' => '.ba-image-scroller-right .ba-image-scroller-img',
				),
			),
			'units'       => 'px',
			'default'     => '100%',
			'description' => esc_html__( 'Please ensure that the width of the container is less than the actual height and width of the image. If the container size is greater than the image size, the scroll feature may not work properly. Try using 100% if it does not work properly.', 'bricksable' ),
			'inline'      => false,
		);

		$this->controls['badge_offsetLeft'] = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Offset Left', 'bricksable' ),
			'type'    => 'number',
			'css'     => array(
				array(
					'property' => 'left',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'units'   => 'px',
			'default' => '20px',
			'inline'  => false,
		);
		$this->controls['badge_offsetTop']  = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Offset Top', 'bricksable' ),
			'type'    => 'number',
			'css'     => array(
				array(
					'property' => 'top',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'units'   => 'px',
			'default' => '20px',
			'inline'  => false,
		);
		$this->controls['badge_padding']    = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'default' => array(
				'top'    => '10px',
				'right'  => '20px',
				'bottom' => '10px',
				'left'   => '20px',
			),
			'inline'  => false,
		);
		$this->controls['badge_textColor']  = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Text Color', 'bricksable' ),
			'type'    => 'color',
			'css'     => array(
				array(
					'property' => 'color',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'rgb' => 'rgb(0,0,0,1.0)',
				'hex' => '#000000',
			),
		);
		$this->controls['badge_bgColor']    = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Background Color', 'bricksable' ),
			'type'    => 'background',
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'color' => array(
					'rgb' => 'rgba(255, 255, 255, 1.0)',
					'hex' => '#ffffff',
				),
			),
		);
		$this->controls['badge_border']     = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Border', 'bricksable' ),
			'type'    => 'border',
			'css'     => array(
				array(
					'property' => 'border',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'style'  => 'solid',
				'radius' => array(
					'top'    => 50,
					'right'  => 50,
					'bottom' => 50,
					'left'   => 50,
				),
			),
		);
		$this->controls['badge_boxShadow']  = array(
			'tab'     => 'style',
			'group'   => 'badge_style',
			'label'   => esc_html__( 'Box Shadow', 'bricksable' ),
			'type'    => 'border',
			'css'     => array(
				array(
					'property' => 'box-shadow',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'inline'  => true,
			'small'   => true,
			'default' => array(
				'values' => array(
					'offsetX' => 0,
					'offsetY' => 0,
					'blur'    => 2,
					'spread'  => 0,
				),
				'color'  => array(
					'rgb' => 'rgba(0, 0, 0, .1)',
				),
			),
		);
		$this->controls['badge_typography'] = array(
			'tab'    => 'style',
			'group'  => 'badge_style',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'typography',
					'selector' => 'span.ba-image-scroller-badge',
				),
			),
			'inline' => true,
		);
	}

	public function get_normalized_image_settings( $settings ) {
		if ( empty( $settings['image'] ) ) {
			return array(
				'id'   => 0,
				'url'  => false,
				'size' => BRICKS_DEFAULT_IMAGE_SIZE,
			);
		}

		$image = $settings['image'];

		// Size.
		$image['size'] = empty( $image['size'] ) ? BRICKS_DEFAULT_IMAGE_SIZE : $settings['image']['size'];

		// Image ID or URL from dynamic data.
		if ( ! empty( $image['useDynamicData'] ) ) {
			$images = $this->render_dynamic_data_tag( $image['useDynamicData'], 'image', array( 'size' => $image['size'] ) );

			if ( ! empty( $images[0] ) ) {
				if ( is_numeric( $images[0] ) ) {
					$image['id'] = $images[0];
				} else {
					$image['url'] = $images[0];
				}
			} else {
				// No dynamic data image found (@since 1.6).
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

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-scrolling-image' );
		wp_enqueue_script( 'ba-scrolling-image' );

		$link_to = ! empty( $this->settings['link'] ) ? $this->settings['link'] : false;
		if ( 'lightbox' === $link_to ) {
			wp_enqueue_script( 'bricks-photoswipe' );
			wp_enqueue_style( 'bricks-photoswipe' );
		}

	}
	public function render() {
		$settings   = $this->settings;
		$link       = ! empty( $settings['link'] ) ? $settings['link'] : false;
		$image      = $this->get_normalized_image_settings( $settings );
		$image_id   = isset( $image['id'] ) ? $image['id'] : '';
		$image_url  = isset( $image['url'] ) ? $image['url'] : '';
		$image_size = isset( $image['size'] ) ? $image['size'] : '';

		// STEP: Dynamic data image not found: Show placeholder text.
		if ( ! empty( $settings['image']['useDynamicData'] ) && ! $image ) {
			return $this->render_element_placeholder(
				array(
					'title' => esc_html__( 'Dynamic data is empty.', 'bricksable' ),
				)
			);
		}

		$image_placeholder_url = \Bricks\Builder::get_template_placeholder_image();
		// Check: No image selected: No image ID provided && not a placeholder URL.
		if ( ! isset( $image['external'] ) && ! $image_id && ! $image_url && $image_url !== $image_placeholder_url ) {
			return $this->render_element_placeholder( array( 'title' => esc_html__( 'No image selected.', 'bricksable' ) ) );
		}

			// Check: Image with ID doesn't exist.
		if ( ! isset( $image['external'] ) && ! $image_url ) {
			/* translators: image id */
			return $this->render_element_placeholder( array( 'title' => sprintf( esc_html__( 'Image ID (%s) no longer exist. Please select another image.', 'bricksable' ), $image_id ) ) );
		}

		$this->set_attribute( 'img', 'class', array( 'css-filter', 'ba-image-scroller-img' ) );

		if ( 'lightbox' === $link ) {
			$this->set_attribute( 'img', 'class', 'bricks-lightbox' );

			$image_src = $image_id ? wp_get_attachment_image_src( $image_id, 'full' ) : array( $image_placeholder_url, 800, 600 );

			$this->set_attribute( 'img', 'data-bricks-lightbox-source', $image_src[0] );
			$this->set_attribute( 'img', 'data-bricks-lightbox-width', $image_src[1] );
			$this->set_attribute( 'img', 'data-bricks-lightbox-height', $image_src[2] );
			$this->set_attribute( 'img', 'data-bricks-lightbox-id', $this->id );

		}

		// Check for alternartive "Alt Text" setting.
		if ( ! empty( $settings['altText'] ) ) {
			$this->set_attribute( 'img', 'alt', esc_attr( $settings['altText'] ) );
		}

		// Set 'loading' attribute: eager or lazy .
		if ( ! empty( $settings['loading'] ) ) {
			$this->set_attribute( 'img', 'loading', esc_attr( $settings['loading'] ) );
		}

		// Show image 'title' attribute.
		if ( isset( $settings['showTitle'] ) ) {
			$image_title = $image_id ? get_the_title( $image_id ) : false;

			if ( $image_title ) {
				$this->set_attribute( 'img', 'title', esc_attr( $image_title ) );
			}
		}

		$image_caption = false;

		$has_overlay = isset( $settings['popupOverlay'] );

		$has_html_tag = $image_caption || $has_overlay || isset( $settings['_gradient'] ) || isset( $settings['tag'] );

		// Check: Global classes on Image element for '_gradient' setting.
		$element_global_classes = ! empty( $settings['_cssGlobalClasses'] ) ? $settings['_cssGlobalClasses'] : false;

		if ( ! $has_html_tag && is_array( $element_global_classes ) ) {
			$all_global_classes = Database::$global_data['globalClasses'];

			if ( is_array( $all_global_classes ) ) {
				foreach ( $element_global_classes as $element_global_class ) {
					$index        = array_search( $element_global_class, array_column( $all_global_classes, 'id' ), true );
					$global_class = ! empty( $all_global_classes[ $index ] ) ? $all_global_classes[ $index ] : false;

					// Global class has 'gradient' setting: Add HTML tag to Image element to make ::before work.
					if ( $global_class && strpos( wp_json_encode( $global_class ), '_gradient' ) ) {
						$has_html_tag = true;
					}

					// Global class has 'popupOverlay' setting: Add .overlay class to make ::before work.
					if ( $global_class && strpos( wp_json_encode( $global_class ), 'popupOverlay' ) ) {
						$has_overlay = true;
					}
				}
			}
		}

		$output         = '';
		$scroll_trigger = isset( $settings['scrollTrigger'] ) ? $settings['scrollTrigger'] : 'hover';

		$trigger_options = array(
			'scrollTrigger' => isset( $settings['scrollTrigger'] ) ? $settings['scrollTrigger'] : 'hover',
			'runOnce'       => isset( $settings['TriggerRunOnce'] ) ? true : false,
		);

		$scroll_trigger = 'hover';

		if ( 'hover' === $scroll_trigger ) {
			$scroll_direction = isset( $settings['scroll_direction'] ) ? $settings['scroll_direction'] : '';
		} elseif ( 'scroll' === $scroll_trigger ) {
			$scroll_direction = '';
		}

		$this->set_attribute( '_root', 'data-scroll-direction', $scroll_direction );
		$this->set_attribute( '_root', 'class', array( 'ba-image-scroller-wrapper', 'ba-image-scroller-trigger-' . $scroll_trigger ) );
		$this->set_attribute( '_root', 'data-trigger-type', $scroll_trigger );
		$this->set_attribute( '_root', 'data-ba-bricks-scrolling-image-options', wp_json_encode( $trigger_options ) );

		$output .= "<{$this->tag} {$this->render_attributes( '_root' )}>";

		$this->set_attribute( 'container', 'class', array( 'ba-image-scroller-container', 'ba-image-scroller-' . $scroll_direction ) );
		// Wrap image element in 'figure' to allow for image caption, overlay, icon.
		if ( $has_overlay ) {
			$this->set_attribute( 'container', 'class', 'overlay' );
		}
		if ( isset( $settings['popupOverlayOnHover'] ) && $has_overlay ) {
			$this->set_attribute( 'container', 'class', 'ba-image-scroller-overlay-hover' );
		}
		if ( isset( $settings['popupIconOnHover'] ) ) {
			$this->set_attribute( 'container', 'class', 'ba-image-scroller-icon-hover' );
		}
		if ( isset( $settings['badgeOnHover'] ) && isset( $settings['badge'] ) ) {
			$this->set_attribute( 'container', 'class', 'ba-image-scroller-badge-hover' );
		}
		if ( isset( $settings['hidePopupIconOnHover'] ) && ! isset( $settings['popupIconOnHover'] ) ) {
			$this->set_attribute( 'container', 'class', 'ba-image-scroller-icon-hide-hover' );
		}
		if ( $link ) {
			if ( isset( $settings['newTab'] ) ) {
				$this->set_attribute( 'link', 'target', '_blank' );
			}

			if ( 'media' === $link && $image_id ) {
				$this->set_attribute( 'link', 'href', wp_get_attachment_url( $image_id ) );
			} elseif ( 'attachment' === $link && $image_id ) {
				$this->set_attribute( 'link', 'href', get_permalink( $image_id ) );
			} elseif ( 'url' === $link && ! empty( $settings['url'] ) ) {
				$this->set_link_attributes( 'link', $settings['url'] );
			}

			$this->set_attribute( 'link', 'class', 'tag' );
			// Wrap image element in 'figure' to allow for image caption, overlay, icon.
			if ( $has_overlay ) {
				$this->set_attribute( 'link', 'class', 'overlay' );
			}
			$output .= "<a {$this->render_attributes( 'link' )}>";
		}

		$output .= "<{$this->tag} {$this->render_attributes('container')}>";

		// Show popup icon if link is set.
		$icon = ! empty( $settings['popupIcon'] ) ? $settings['popupIcon'] : false;
		// Check: Theme style for video 'popupIcon' setting (@since 1.7).
		if ( ! $icon && ! empty( $this->theme_styles['popupIcon'] ) ) {
			$icon = $this->theme_styles['popupIcon'];
		}

		if ( ! isset( $settings['popupIconDisable'] ) && $icon ) {
			$output .= self::render_icon( $icon, array( 'icon' ) );
		}
		// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
		if ( $image_id ) {
			$image_attributes = array();

			foreach ( $this->attributes['img'] as $key => $value ) {
				if ( isset( $image_attributes[ $key ] ) ) {
					$image_attributes[ $key ] .= ' ' . ( is_array( $value ) ? join( ' ', $value ) : $value );
				} else {
					$image_attributes[ $key ] = is_array( $value ) ? join( ' ', $value ) : $value;
				}
			}

			// Merge custom attributes with img attributes.
			$custom_attributes = $this->get_custom_attributes( $settings );
			$image_attributes  = array_merge( $image_attributes, $custom_attributes );

			$output .= wp_get_attachment_image( $image_id, $image_size, false, $image_attributes );
		} elseif ( $image_url ) {
			if ( ! $has_html_tag && ! $link ) {
				foreach ( $this->attributes['_root'] as $key => $value ) {
					$this->attributes['img'][ $key ] = $value;
				}
			}

			$this->set_attribute( 'img', 'src', $image_url );

			$output .= "<img {$this->render_attributes( 'img', true )}>";
		}

		$badge = '';
		if ( isset( $settings['badge'] ) && true === $settings['badge'] ) {
			$this->set_attribute( 'badge', 'class', array( 'ba-image-scroller-badge', 'ba-badge' ) );
			$badge  = "<span {$this->render_attributes('badge')}>";
			$badge .= $settings['badge_text'];
			$badge .= '</span>';
		}

		$output .= $badge;
		$output .= "</{$this->tag}>";

		if ( $link ) {
			$output .= '</a>';
		}
		$output .= '</div>';
		//phpcs:ignore
		echo $output;
	}
}
