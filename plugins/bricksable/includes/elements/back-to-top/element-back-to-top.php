<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Back_To_Top extends \Bricks\Element {
	public $category     = 'bricksable';
	public $name         = 'ba-back-to-top';
	public $icon         = 'ion-ios-arrow-dropup-circle';
	public $css_selector = '';
	public $scripts      = array( 'bricksableBackToTop' );
	public $nestable     = true; // true || @since 1.5.

	public function get_label() {
		return esc_html__( 'Back to Top', 'bricksable' );
	}
	public function set_control_groups() {
		$this->control_groups['iconText']    = array(
			'title'    => esc_html__( 'Icon / Text', 'bricksable' ),
			'tab'      => 'content',
			'required' => array( 'nestable', '=', '' ),
		);
		$this->control_groups['sizing']      = array(
			'title' => esc_html__( 'Sizing', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['scroll']      = array(
			'title' => esc_html__( 'Scroll Behaviour & Animation', 'bricksable' ),
			'tab'   => 'content',
		);
		$this->control_groups['positioning'] = array(
			'title' => esc_html__( 'Position', 'bricksable' ),
			'tab'   => 'content',
		);
		unset( $this->control_groups['_typography'] );

	}

	public function set_controls() {
		// Positioning.
		$this->controls['_positionSeparator']['tab']   = 'content';
		$this->controls['_positionSeparator']['group'] = 'positioning';
		$this->controls['_position']['tab']            = 'content';
		$this->controls['_position']['group']          = 'positioning';
		$this->controls['_position']['placeholder']    = 'Fixed';
		$this->controls['_position']['default']        = 'fixed';
		$this->controls['_positionInfo']['tab']        = 'content';
		$this->controls['_positionInfo']['group']      = 'positioning';
		$this->controls['_top']['tab']                 = 'content';
		$this->controls['_top']['group']               = 'positioning';
		$this->controls['_right']['tab']               = 'content';
		$this->controls['_right']['group']             = 'positioning';
		$this->controls['_right']['placeholder']       = '30px';
		$this->controls['_right']['default']           = '30px';
		$this->controls['_bottom']['tab']              = 'content';
		$this->controls['_bottom']['group']            = 'positioning';
		$this->controls['_bottom']['placeholder']      = '30px';
		$this->controls['_bottom']['default']          = '30px';
		$this->controls['_left']['tab']                = 'content';
		$this->controls['_left']['group']              = 'positioning';
		$this->controls['_zIndex']['tab']              = 'content';
		$this->controls['_zIndex']['group']            = 'positioning';
		$this->controls['_zIndex']['placeholder']      = '9999';
		$this->controls['_zIndex']['default']          = '9999';
		// Misc.
		$this->controls['_miscSeparator']['tab']   = 'content';
		$this->controls['_miscSeparator']['group'] = 'positioning';
		$this->controls['_display']['tab']         = 'content';
		$this->controls['_display']['group']       = 'positioning';
		$this->controls['_display']['placeholder'] = 'Flex';
		$this->controls['_display']['default']     = 'flex';
		$this->controls['_overflow']['tab']        = 'content';
		$this->controls['_overflow']['group']      = 'positioning';
		$this->controls['_opacity']['tab']         = 'content';
		$this->controls['_opacity']['group']       = 'positioning';
		$this->controls['_cursor']['tab']          = 'content';
		$this->controls['_cursor']['group']        = 'positioning';
		// Flex.
		$this->controls['_flexSeparator']['tab']          = 'content';
		$this->controls['_flexSeparator']['group']        = 'positioning';
		$this->controls['_flexDirection']['tab']          = 'content';
		$this->controls['_flexDirection']['group']        = 'positioning';
		$this->controls['_alignSelf']['tab']              = 'content';
		$this->controls['_alignSelf']['group']            = 'positioning';
		$this->controls['_justifyContent']['tab']         = 'content';
		$this->controls['_justifyContent']['group']       = 'positioning';
		$this->controls['_justifyContent']['placeholder'] = 'Center';
		$this->controls['_justifyContent']['default']     = 'center';
		$this->controls['_alignItems']['tab']             = 'content';
		$this->controls['_alignItems']['group']           = 'positioning';
		$this->controls['_alignItems']['placeholder']     = 'Center';
		$this->controls['_alignItems']['default']         = 'center';
		$this->controls['_flexGrow']['tab']               = 'content';
		$this->controls['_flexGrow']['group']             = 'positioning';
		$this->controls['_flexShrink']['tab']             = 'content';
		$this->controls['_flexShrink']['group']           = 'positioning';
		$this->controls['_flexBasis']['tab']              = 'content';
		$this->controls['_flexBasis']['group']            = 'positioning';

		// Sizing.
		$this->controls['_width']['tab']          = 'content';
		$this->controls['_width']['group']        = 'sizing';
		$this->controls['_width']['placeholder']  = '50px';
		$this->controls['_width']['default']      = '50px';
		$this->controls['_height']['tab']         = 'content';
		$this->controls['_height']['group']       = 'sizing';
		$this->controls['_height']['placeholder'] = '50px';
		$this->controls['_height']['default']     = '50px';

		// Border.
		$this->controls['_border']['default'] =
		array(
			'radius' => array(
				'top'    => 6,
				'right'  => 6,
				'bottom' => 6,
				'left'   => 6,
			),
		);

		// BoxShadow.
		$this->controls['_boxShadow']['default'] =
		array(
			'values' => array(
				'offsetX' => 1,
				'offsetY' => 5,
				'blur'    => 15,
				'spread'  => 0,
			),
			'color'  => array(
				'rgb' => 'rgba(0,0,0,0.1)',
			),
		);

		// Background.
		$this->controls['_background']['default'] =
		array(
			'color' => array(
				'hex' => '#ffffff',
			),
		);

		$this->controls['nestable'] = array(
			'tab'     => 'content',
			'label'   => esc_html__( 'Nestable?', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'small'   => true,
			'default' => false,
		);

		$this->controls['icon'] = array(
			'tab'     => 'content',
			'group'   => 'iconText',
			'label'   => esc_html__( 'Icon', 'bricksable' ),
			'type'    => 'icon',
			'css'     => array(
				array(
					'selector' => '.icon',
				),
			),
			'default' => array(
				'library' => 'Ionicons',
				'icon'    => 'ion-ios-arrow-dropup-circle',
			),
		);

		$this->controls['iconTypography'] = array(
			'tab'     => 'content',
			'group'   => 'iconText',
			'label'   => esc_html__( 'Icon Typography', 'bricksable' ),
			'type'    => 'typography',
			'exclude' => array(
				'font-family',
				'font-style',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
				'text-decoration',
			),
			'css'     => array(
				array(
					'property' => 'font',
					'selector' => 'i',
				),
			),
			'default' => array(
				'font-size' => 30,
			),
			'inline'  => true,
			'small'   => true,
		);

		$this->controls['text'] = array(
			'tab'           => 'content',
			'group'         => 'iconText',
			'label'         => esc_html__( 'Text', 'bricksable' ),
			'type'          => 'text',
			'spellcheck'    => true,
			'inlineEditing' => true,
			'inline'        => true,
			'required'      => array( 'nestable', '=', '' ),
		);

		$this->controls['textInfo'] = array(
			'tab'      => 'content',
			'group'    => 'iconText',
			'content'  => esc_html__( 'If text is present, you may need to modify the width setting within the Sizing Group.', 'bricksable' ),
			'type'     => 'info',
			'required' => array( 'text', '!=', '' ),
		);

		$this->controls['textTypography'] = array(
			'tab'         => 'content',
			'group'       => 'iconText',
			'label'       => esc_html__( 'Text Typography', 'bricksable' ),
			'type'        => 'typography',
			'css'         => array(
				array(
					'property' => 'font',
					'selector' => '.ba-back-to-top-text',
				),
			),
			'exclude'     => array(
				'text-align',
			),
			'placeholder' => array(
				'font-size' => 15,
			),
			'required'    => array( 'text', '!=', '' ),
		);

		$this->controls['textMargin'] = array(
			'tab'      => 'content',
			'group'    => 'iconText',
			'label'    => esc_html__( 'Text Margin', 'bricksable' ),
			'type'     => 'dimensions',
			'css'      => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-back-to-top-text',
				),
			),
			'default'  => array(
				'left' => '5px',
			),
			'required' => array( 'text', '!=', '' ),

		);

		$this->controls['ariaLabel'] = array(
			'tab'            => 'content',
			'label'          => esc_html__( 'Aria label', 'bricksable' ),
			'type'           => 'text',
			'default'        => 'Back to top',
			'inline'         => true,
			'hasDynamicData' => false,
			'css'            => array(
				array(
					'selector' => '&:focus',
					'property' => 'outline',
					'value'    => 'none',
				),
			),
		);

		$this->controls['showOnScroll'] = array(
			'tab'     => 'content',
			'group'   => 'scroll',
			'label'   => esc_html__( 'Show on Scroll', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'small'   => true,
			'default' => false,
		);

		$this->controls['scrollOffset'] = array(
			'tab'         => 'content',
			'group'       => 'scroll',
			'type'        => 'number',
			'units'       => true,
			'label'       => esc_html__( 'Offset', 'bricksable' ),
			'placeholder' => '300px',
			'inline'      => true,
			'small'       => true,
			'required'    => array( 'showOnScroll', '!=', '' ),
		);

		$this->controls['animationIn'] = array(
			'tab'         => 'content',
			'group'       => 'scroll',
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
				'backInDown'        => esc_html__( 'backInDown', 'bricksable' ),
				'backInLeft'        => esc_html__( 'backInLeft', 'bricksable' ),
				'backInRight'       => esc_html__( 'backInRight', 'bricksable' ),
				'backInUp'          => esc_html__( 'backInUp', 'bricksable' ),
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
				'lightSpeedInRight' => esc_html__( 'lightSpeedInRight', 'bricksable' ),
				'lightSpeedInLeft'  => esc_html__( 'lightSpeedInLeft', 'bricksable' ),
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
			'default'     => 'fadeInUp',
			'placeholder' => esc_html__( 'fadeInUp', 'bricksable' ),
			'required'    => array( 'showOnScroll', '!=', '' ),
		);

		$this->controls['animationOut'] = array(
			'tab'         => 'content',
			'group'       => 'scroll',
			'label'       => esc_html__( 'Exit animation', 'bricksable' ),
			'type'        => 'select',
			'searchable'  => true,
			'options'     => array(
				'bounceOut'          => esc_html__( 'bounceOut', 'bricksable' ),
				'bounceOutDown'      => esc_html__( 'bounceOutDown', 'bricksable' ),
				'bounceOutLeft'      => esc_html__( 'bounceOutLeft', 'bricksable' ),
				'bounceOutRight'     => esc_html__( 'bounceOutRight', 'bricksable' ),
				'bounceOutUp'        => esc_html__( 'bounceOutUp', 'bricksable' ),
				'backOutDown'        => esc_html__( 'backOutDown', 'bricksable' ),
				'backOutLeft'        => esc_html__( 'backOutLeft', 'bricksable' ),
				'backOutRight'       => esc_html__( 'backOutRight', 'bricksable' ),
				'backOutUp'          => esc_html__( 'backOutUp', 'bricksable' ),
				'fadeOut'            => esc_html__( 'fadeOut', 'bricksable' ),
				'fadeOutDown'        => esc_html__( 'fadeOutDown', 'bricksable' ),
				'fadeOutDownBig'     => esc_html__( 'fadeOutDownBig', 'bricksable' ),
				'fadeOutLeft'        => esc_html__( 'fadeOutLeft', 'bricksable' ),
				'fadeOutLeftBig'     => esc_html__( 'fadeOutLeftBig', 'bricksable' ),
				'fadeOutRight'       => esc_html__( 'fadeOutRight', 'bricksable' ),
				'fadeOutRightBig'    => esc_html__( 'fadeOutRightBig', 'bricksable' ),
				'fadeOutUp'          => esc_html__( 'fadeOutUp', 'bricksable' ),
				'fadeOutUpBig'       => esc_html__( 'fadeOutUpBig', 'bricksable' ),
				'flipOutX'           => esc_html__( 'flipOutX', 'bricksable' ),
				'flipOutY'           => esc_html__( 'flipOutY', 'bricksable' ),
				'rotateOut'          => esc_html__( 'rotateOut', 'bricksable' ),
				'rotateOutDownLeft'  => esc_html__( 'rotateOutDownLeft', 'bricksable' ),
				'rotateOutDownRight' => esc_html__( 'rotateOutDownRight', 'bricksable' ),
				'rotateOutUpLeft'    => esc_html__( 'rotateOutUpLeft', 'bricksable' ),
				'rotateOutUpRight'   => esc_html__( 'rotateOutUpRight', 'bricksable' ),
				'zoomOut'            => esc_html__( 'zoomOut', 'bricksable' ),
				'zoomOutDown'        => esc_html__( 'zoomOutDown', 'bricksable' ),
				'zoomOutLeft'        => esc_html__( 'zoomOutLeft', 'bricksable' ),
				'zoomOutRight'       => esc_html__( 'zoomOutRight', 'bricksable' ),
				'zoomOutUp'          => esc_html__( 'zoomOutUp', 'bricksable' ),
				'slideOutUp'         => esc_html__( 'slideOutUp', 'bricksable' ),
				'slideOutDown'       => esc_html__( 'slideOutDown', 'bricksable' ),
				'slideOutLeft'       => esc_html__( 'slideOutLeft', 'bricksable' ),
				'slideOutRight'      => esc_html__( 'slideOutRight', 'bricksable' ),
				'rollOut'            => esc_html__( 'rollOut', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'fadeOutDown',
			'placeholder' => esc_html__( 'fadeOutDown', 'bricksable' ),
			'required'    => array( 'showOnScroll', '!=', '' ),
		);

		$this->controls['animationDuration'] = array(
			'tab'         => 'content',
			'group'       => 'scroll',
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
			'required'    => array( 'showOnScroll', '!=', '' ),
		);

		$this->controls['animationDurationCustom'] = array(
			'tab'         => 'content',
			'group'       => 'scroll',
			'label'       => esc_html__( 'Animation duration', 'bricksable' ) . ' (' . esc_html__( 'Custom', 'bricksable' ) . ')',
			'type'        => 'text',
			'css'         => array(
				array(
					'property' => 'animation-duration',
					'selector' => '.ba-back-to-top-visible',
				),
			),
			'description' => esc_html__( 'For example: "1s" or "500ms"', 'bricksable' ),
			'inline'      => true,
			'required'    => array( 'animationDuration', '=', 'custom' ),
		);

		$this->controls['animationDelay'] = array(
			'tab'         => 'content',
			'group'       => 'scroll',
			'label'       => esc_html__( 'Animation delay', 'bricksable' ),
			'type'        => 'text',
			'css'         => array(
				array(
					'property' => 'animation-delay',
					'selector' => '.ba-back-to-top-visible',
				),
			),
			'inline'      => true,
			'description' => esc_html__( 'For example:  "1s" or "500ms" or "-2.5s"', 'bricksable' ),
			'placeholder' => '0s',
			'required'    => array( 'showOnScroll', '!=', '' ),
		);

		$this->controls['smoothScrolling'] = array(
			'tab'     => 'content',
			'group'   => 'scroll',
			'label'   => esc_html__( 'Smooth Scrolling Behaviour', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'small'   => true,
			'default' => true,
		);

	}

	public function enqueue_scripts() {
		$settings = $this->settings;
		if ( isset( $settings['showOnScroll'] ) ) {
			wp_enqueue_style( 'bricks-animate' );
		}
		wp_enqueue_style( 'ba-back-to-top' );
		wp_enqueue_script( 'ba-back-to-top' );
	}

	public function render() {
		$settings = $this->settings;
		$output   = '';

		$show_on_scroll = isset( $settings['showOnScroll'] ) ? true : false;
		$nestable       = isset( $settings['nestable'] ) ? true : false;

		$aria_label = isset( $settings['ariaLabel'] ) ? $settings['ariaLabel'] : '';

		$this->set_attribute(
			'_root',
			'class',
			array(
				$show_on_scroll ? 'ba-back-to-top-show-on-scroll' : '',
				isset( $settings['text'] ) ? 'ba-back-to-top-have-text' : '',
				isset( $settings['animationDuration'] ) && '' !== $settings['animationDuration'] ? 'brx-animate-' . $settings['animationDuration'] : '',
			),
		);
		$this->set_attribute( '_root', 'href', esc_attr( '#' ) );
		if ( $aria_label ) {
			$this->set_attribute( '_root', 'aria-label', $aria_label );
		}
		// Back to Top Options.
		$back_to_top_options = array(
			'showOnScroll'    => isset( $settings['showOnScroll'] ) ? true : false,
			'scrollOffset'    => isset( $settings['scrollOffset'] ) ? intval( $settings['scrollOffset'] ) : 300,
			'smoothScrolling' => isset( $settings['smoothScrolling'] ) ? true : false,
			'animationIn'     => isset( $settings['animationIn'] ) ? esc_attr( $settings['animationIn'] ) : 'fadeInUp',
			'animationOut'    => isset( $settings['animationOut'] ) ? esc_attr( $settings['animationOut'] ) : 'fadeOutDown',
		);

		$this->set_attribute( '_root', 'data-ba-bricks-back-to-top-options', wp_json_encode( $back_to_top_options ) );

		$output .= "<a {$this->render_attributes( '_root' )}>";

		if ( ! $nestable ) {
			// Show icon.
			$icon = ! empty( $settings['icon'] ) ? $settings['icon'] : false;
			if ( $icon ) {
				$output .= self::render_icon( $icon, array( 'icon' ) );
			}
			// Text.
			$this->set_attribute( 'text', 'class', 'ba-back-to-top-text' );
			if ( isset( $settings['text'] ) ) {
				$output .= "<span {$this->render_attributes( 'text' )}>";
				$output .= $settings['text'];
				$output .= '</span>';
			}
		} else {
			if ( method_exists( '\Bricks\Frontend', 'render_children' ) ) {
				$output .= \Bricks\Frontend::render_children( $this );
			}
		}
		// End of root.
		$output .= '</a>';
		//phpcs:ignore
		echo $output;
	}

	/*
	public static function render_builder() { ?>
		<script type="text/x-template" id="tmpl-bricks-element-ba-back-to-top">
		<component
				is=a
				class="brx-animated"
				aria-label="Back to Top"
			>
			<icon-svg v-if="settings.icon" class="icon" :iconSettings="settings.icon"/>
			<contenteditable
			v-if="settings.text"
				tag="span"
				class="ba-back-to-top-text"
				:name="name"
				controlKey="text"
				toolbar="style"
				:settings="settings"
		/>
			<bricks-element-children
				v-if="settings.nestable"
				:element="element"
			/>
			</component>

		</script>
		<?php
	}*/
}
