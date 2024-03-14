<?php
use Bricksable\Classes\Bricksable_Helpers;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Element_Flipbox extends \Bricks\Element {
	public $category     = 'bricksable';
	public $name         = 'ba-flipbox';
	public $icon         = 'ti-layers';
	public $css_selector = '.ba-flipbox-wrapper';

	public function get_label() {
		return esc_html__( 'Flipbox', 'bricksable' );
	}

	public function set_control_groups() {

		$this->control_groups['front_content'] = array(
			'title' => esc_html__( 'Front Content', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['back_content'] = array(
			'title' => esc_html__( 'Back Content', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['settings'] = array(
			'title' => esc_html__( 'Flipbox Setting', 'bricksable' ),
			'tab'   => 'content',
		);

		$this->control_groups['frontIcon'] = array(
			'title' => esc_html__( 'Flipbox Front Icon', 'bricksable' ),
			'tab'   => 'style',
		);

		$this->control_groups['backIcon'] = array(
			'title' => esc_html__( 'Flipbox Back Icon', 'bricksable' ),
			'tab'   => 'style',
		);

		$this->control_groups['frontStyle'] = array(
			'title' => esc_html__( 'Flipbox Front', 'bricksable' ),
			'tab'   => 'style',
		);

		$this->control_groups['backStyle'] = array(
			'title' => esc_html__( 'Flipbox Back', 'bricksable' ),
			'tab'   => 'style',
		);

		$this->control_groups['backButtonStyle'] = array(
			'title' => esc_html__( 'Button', 'bricksable' ),
			'tab'   => 'style',
		);

		unset( $this->control_groups['_typography'] );
		unset( $this->control_groups['_border'] );
		unset( $this->control_groups['_boxShadow'] );
	}

	public function set_controls() {
		// Front.
		$this->controls['front_content_type'] = array(
			'tab'         => 'content',
			'group'       => 'front_content',
			'label'       => esc_html__( 'Content Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'content'   => esc_html__( 'Content', 'bricksable' ),
				'templates' => esc_html__( 'Templates', 'bricksable' ),
			),
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'content',
			'placeholder' => esc_html__( 'Content', 'bricksable' ),
			'description' => esc_html__( 'Using Template will ignore all contents below. Bricks 1.3 is required when using templates type.', 'bricksable' ),
		);

		$this->controls['front_template'] = array(
			'tab'         => 'content',
			'group'       => 'front_content',
			'label'       => esc_html__( 'Template', 'bricksable' ),
			'type'        => 'select',
			'options'     => bricks_is_builder() ? Bricksable_Helpers::get_templates_list( get_the_ID() ) : array(),
			'searchable'  => true,
			'placeholder' => esc_html__( 'Select template', 'bricksable' ),
			'required'    => array( 'front_content_type', '=', 'templates' ),
			'rerender'    => true,
		);

		$this->controls['front_content_type_separator'] = array(
			'tab'   => 'content',
			'group' => 'front_content',
			'type'  => 'separator',
			'label' => esc_html__( 'Content', 'bricksable' ),
		);

		$this->controls['front_icon'] = array(
			'tab'      => 'content',
			'group'    => 'front_content',
			'label'    => esc_html__( 'Icon', 'bricksable' ),
			'type'     => 'icon',
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-wordpress',
			),
			'required' => array(
				array( 'front_content_type', '!=', 'templates' ),
				array( 'use_front_icon_image', '!=', true ),
			),
		);

		$this->controls['use_front_icon_image'] = array(
			'tab'         => 'content',
			'group'       => 'front_content',
			'label'       => esc_html__( 'Use Icon Image', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Use Icon Image instead of Icon.', 'bricksable' ),
			'required'    => array( 'front_content_type', '!=', 'templates' ),

		);

		$this->controls['front_icon_image'] = array(
			'tab'      => 'content',
			'group'    => 'front_content',
			'label'    => esc_html__( 'Icon Image', 'bricksable' ),
			'type'     => 'image',
			'required' => array(
				array( 'front_content_type', '!=', 'templates' ),
				array( 'use_front_icon_image', '=', true ),
			),
		);

		$this->controls['front_heading'] = array(
			'tab'            => 'content',
			'group'          => 'front_content',
			'label'          => esc_html__( 'Heading', 'bricksable' ),
			'type'           => 'text',
			'spellcheck'     => true,
			'inlineEditing'  => true,
			'default'        => 'Front heading',
			'hasDynamicData' => 'text',
			'required'       => array( 'front_content_type', '!=', 'templates' ),
		);

		$this->controls['front_subheading'] = array(
			'tab'            => 'content',
			'group'          => 'front_content',
			'label'          => esc_html__( 'Subheading', 'bricksable' ),
			'type'           => 'text',
			'spellcheck'     => true,
			'inlineEditing'  => true,
			'hasDynamicData' => 'text',
			'required'       => array( 'front_content_type', '!=', 'templates' ),
		);

		$this->controls['front_heading_tag'] = array(
			'tab'         => 'content',
			'group'       => 'front_content',
			'label'       => esc_html__( 'Heading Tag', 'bricksable' ),
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
			'required'    => array( 'front_content_type', '!=', 'templates' ),
		);

		$this->controls['front_content'] = array(
			'tab'      => 'content',
			'group'    => 'front_content',
			'type'     => 'editor',
			'default'  => '<p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>',
			'required' => array( 'front_content_type', '!=', 'templates' ),
		);

		$this->controls['front_background'] = array(
			'tab'     => 'content',
			'group'   => 'front_content',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'background',
			'inline'  => true,
			'small'   => true,
			'exclude' => array(
				'parallax',
				'videoUrl',
				'videoScale',
			),
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-flipbox-front',
				),
			),
			'default' => array(
				'color' => array(
					'rgb' => 'rgba(224, 224, 224, 1)',
					'hex' => '#e0e0e0',
				),
			),
		);

		// Back.
		$this->controls['back_content_type'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Content Type', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'content'   => esc_html__( 'Content', 'bricksable' ),
				'templates' => esc_html__( 'Templates', 'bricksable' ),
			),
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'content',
			'placeholder' => esc_html__( 'Content', 'bricksable' ),
			'description' => esc_html__( 'Using Template will ignore all contents below. Bricks 1.3 is required when using templates type.', 'bricksable' ),
		);

		$this->controls['back_template'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Template', 'bricksable' ),
			'type'        => 'select',
			'options'     => bricks_is_builder() ? Bricksable_Helpers::get_templates_list( get_the_ID() ) : array(),
			'searchable'  => true,
			'placeholder' => esc_html__( 'Select template', 'bricksable' ),
			'required'    => array( 'back_content_type', '=', 'templates' ),
			'rerender'    => true,
		);

		$this->controls['back_content_type_separator'] = array(
			'tab'   => 'content',
			'group' => 'back_content',
			'type'  => 'separator',
			'label' => esc_html__( 'Content', 'bricksable' ),
		);
		$this->controls['back_icon']                   = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Icon', 'bricksable' ),
			'type'     => 'icon',
			'default'  => array(
				'library' => 'themify',
				'icon'    => 'ti-wordpress',
			),
			'required' => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['use_back_icon_image'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Use Icon Image', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Use Icon Image instead of Icon.', 'bricksable' ),
			'required'    => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_icon_image'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Icon Image', 'bricksable' ),
			'type'     => 'image',
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_icon_image', '=', true ),
			),
		);

		$this->controls['back_link'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Link', 'bricksable' ),
			'type'     => 'link',
			'required' => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_heading'] = array(
			'tab'            => 'content',
			'group'          => 'back_content',
			'label'          => esc_html__( 'Heading', 'bricksable' ),
			'type'           => 'text',
			'spellcheck'     => true,
			'inlineEditing'  => true,
			'default'        => 'Back heading',
			'hasDynamicData' => 'text',
			'required'       => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_subheading'] = array(
			'tab'            => 'content',
			'group'          => 'back_content',
			'label'          => esc_html__( 'Subheading', 'bricksable' ),
			'type'           => 'text',
			'spellcheck'     => true,
			'inlineEditing'  => true,
			'hasDynamicData' => 'text',
			'required'       => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_heading_tag'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Heading Tag', 'bricksable' ),
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
			'required'    => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_content'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Content', 'bricksable' ),
			'type'     => 'editor',
			'default'  => '<p>Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.</p>',
			'required' => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_background'] = array(
			'tab'     => 'content',
			'group'   => 'back_content',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'background',
			'inline'  => true,
			'small'   => true,
			'exclude' => array(
				'parallax',
				'videoUrl',
				'videoScale',
			),
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-flipbox-back',
				),
			),
			'default' => array(
				'color' => array(
					'rgb' => 'rgba(224, 224, 224, 1)',
					'hex' => '#e0e0e0',
				),
			),
		);

		$this->controls['back_button_separator'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Button', 'bricksable' ),
			'type'     => 'separator',
			'required' => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['use_back_button'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Use Button', 'bricksable' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'required' => array( 'back_content_type', '!=', 'templates' ),
		);

		$this->controls['back_button_text'] = array(
			'tab'            => 'content',
			'group'          => 'back_content',
			'label'          => esc_html__( 'Button Text', 'bricksable' ),
			'type'           => 'text',
			'default'        => esc_html__( 'Button', 'bricksable' ),
			'hasDynamicData' => 'text',
			'required'       => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_link'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Link type', 'bricksable' ),
			'type'     => 'link',
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_size'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Size', 'bricksable' ),
			'type'        => 'select',
			'options'     => $this->control_options['buttonSizes'],
			'inline'      => true,
			'reset'       => true,
			'placeholder' => esc_html__( 'Medium', 'bricksable' ),
			'required'    => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_style'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Style', 'bricksable' ),
			'type'        => 'select',
			'options'     => $this->control_options['styles'],
			'inline'      => true,
			'reset'       => true,
			'default'     => 'primary',
			'placeholder' => esc_html__( 'None', 'bricksable' ),
			'required'    => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_circle'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Circle', 'bricksable' ),
			'type'     => 'checkbox',
			'reset'    => true,
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_block'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Stretch', 'bricksable' ),
			'type'     => 'checkbox',
			'reset'    => true,
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_outline'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Outline', 'bricksable' ),
			'type'     => 'checkbox',
			'reset'    => true,
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		// Button Icon.
		$this->controls['back_button_icon'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Icon', 'bricksable' ),
			'type'     => 'icon',
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'use_back_button', '=', true ),
			),
		);

		$this->controls['back_button_iconTypography'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Typography', 'bricksable' ),
			'type'     => 'typography',
			'exclude'  => array(
				'font-family',
				'font-style',
				'font-weight',
				'text-align',
				'text-transform',
				'line-height',
				'letter-spacing',
				'text-decoration',
			),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.bricks-button i',
				),
			),
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'back_button_icon.icon', '!=', '' ),
			),
		);

		$this->controls['back_button_iconPosition'] = array(
			'tab'         => 'content',
			'group'       => 'back_content',
			'label'       => esc_html__( 'Position', 'bricksable' ),
			'type'        => 'select',
			'options'     => $this->control_options['iconPosition'],
			'inline'      => true,
			'placeholder' => esc_html__( 'Right', 'bricksable' ),
			'required'    => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'back_button_icon', '!=', '' ),
			),
		);

		/*
		Remove in version 1.6.44
		$this->controls['back_button_iconSpace'] = array(
			'tab'      => 'content',
			'group'    => 'back_content',
			'label'    => esc_html__( 'Space between', 'bricksable' ),
			'type'     => 'checkbox',
			'css'      => array(
				array(
					'property' => 'justify-content',
					'selector' => '.bricks-button-inner',
					'value'    => 'space-between',
				),
			),
			'required' => array(
				array( 'back_content_type', '!=', 'templates' ),
				array( 'back_button_icon', '!=', '' ),
			),
		);
		*/

		$this->controls['back_button_Align'] = array(
			'tab'     => 'style',
			'group'   => 'backButtonStyle',
			'label'   => esc_html__( 'Alignment', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => 'stretch',
			'css'     => array(
				array(
					'property' => 'align-self',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
			'inline'  => true,
		);

		$this->controls['back_button_Margin'] = array(
			'tab'     => 'style',
			'group'   => 'backButtonStyle',
			'label'   => esc_html__( 'Margin', 'bricksable' ),
			'type'    => 'dimensions',
			'css'     => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
			'default' => array(
				'top'    => 20,
				'right'  => 0,
				'bottom' => 0,
				'left'   => 0,
			),
		);

		$this->controls['back_button_Padding'] = array(
			'tab'   => 'style',
			'group' => 'backButtonStyle',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
		);

		$this->controls['back_button_typography'] = array(
			'tab'    => 'style',
			'group'  => 'backButtonStyle',
			'label'  => esc_html__( 'Typography', 'bricksable' ),
			'type'   => 'typography',
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['back_button_background'] = array(
			'tab'     => 'style',
			'group'   => 'backButtonStyle',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'background',
			'inline'  => true,
			'small'   => true,
			'exclude' => array(
				'parallax',
				'videoUrl',
				'videoScale',
			),
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
		);

		$this->controls['button_iconBorder'] = array(
			'tab'    => 'style',
			'group'  => 'backButtonStyle',
			'label'  => esc_html__( 'Icon border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['button_BoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'backButtonStyle',
			'label'  => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-back .bricks-button',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Front Icon.
		$this->controls['front_iconMargin'] = array(
			'tab'   => 'style',
			'group' => 'frontIcon',
			'label' => esc_html__( 'Icon margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-front .icon-image-wrapper',
				),
			),
		);

		$this->controls['front_iconPadding'] = array(
			'tab'   => 'style',
			'group' => 'frontIcon',
			'label' => esc_html__( 'Icon padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-front .icon-image-wrapper',
				),
			),
		);

		$this->controls['front_iconPosition'] = array(
			'tab'         => 'style',
			'group'       => 'frontIcon',
			'label'       => esc_html__( 'Icon position', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'top'    => esc_html__( 'Top', 'bricksable' ),
				'right'  => esc_html__( 'Right', 'bricksable' ),
				'bottom' => esc_html__( 'Bottom', 'bricksable' ),
				'left'   => esc_html__( 'Left', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'Top', 'bricksable' ),
		);

		$this->controls['front_iconVerticalAlign'] = array(
			'tab'     => 'style',
			'group'   => 'frontIcon',
			'label'   => esc_html__( 'Icon Align', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => 'stretch',
			'css'     => array(
				array(
					'property' => 'align-self',
					'selector' => '.ba-flipbox-front .icon',
				),
			),
			'inline'  => true,
		);

		$this->controls['front_iconSize'] = array(
			'tab'      => 'style',
			'group'    => 'frontIcon',
			'label'    => esc_html__( 'Icon size', 'bricksable' ),
			'type'     => 'number',
			'default'  => '40px',
			'css'      => array(
				array(
					'property' => 'font-size',
					'selector' => '.ba-flipbox-front .icon',
				),
			),
			'required' => array( 'use_front_icon_image', '!=', true ),
		);

		$this->controls['front_iconHeight'] = array(
			'tab'      => 'style',
			'group'    => 'frontIcon',
			'label'    => esc_html__( 'Icon height', 'bricksable' ),
			'type'     => 'number',
			'units'    => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
				'%'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'vh' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			),
			'css'      => array(
				array(
					'property' => 'height',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'line-height',
					'selector' => '.ba-flipbox-front .icon',
				),
			),
			'required' => array( 'use_front_icon_image', '!=', true ),
		);

		$this->controls['front_iconWidth'] = array(
			'tab'   => 'style',
			'group' => 'frontIcon',
			'label' => esc_html__( 'Icon width', 'bricksable' ),
			'type'  => 'number',
			'units' => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
				'%'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'vw' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			),
			'css'   => array(
				array(
					'property' => 'min-width',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'max-width',
					'selector' => '.ba-flipbox-front .icon-image-wrapper',
				),
			),
		);

		$this->controls['front_iconColor'] = array(
			'tab'      => 'style',
			'group'    => 'frontIcon',
			'label'    => esc_html__( 'Icon color', 'bricksable' ),
			'type'     => 'color',
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.ba-flipbox-front .icon',
				),
			),
			'required' => array( 'use_front_icon_image', '!=', true ),
		);

		$this->controls['front_iconBackgroundColor'] = array(
			'tab'    => 'style',
			'group'  => 'frontIcon',
			'label'  => esc_html__( 'Icon background', 'bricksable' ),
			'type'   => 'color',
			'inline' => true,
			'small'  => true,
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'background-color',
					'selector' => '.ba-flipbox-front .icon-image-wrapper',
				),
			),
		);

		$this->controls['front_iconBorder'] = array(
			'tab'    => 'style',
			'group'  => 'frontIcon',
			'label'  => esc_html__( 'Icon border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-front .icon-image-wrapper img',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['front_iconBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'frontIcon',
			'label'  => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-front .icon',
				),
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-front .icon-image-wrapper img',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Back Icon.
		$this->controls['back_iconMargin'] = array(
			'tab'   => 'style',
			'group' => 'backIcon',
			'label' => esc_html__( 'Icon margin', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-back .icon-image-wrapper',
				),
			),
		);

		$this->controls['back_iconPadding'] = array(
			'tab'   => 'style',
			'group' => 'backIcon',
			'label' => esc_html__( 'Icon padding', 'bricksable' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-back .icon-image-wrapper',
				),
			),
		);

		$this->controls['back_iconPosition'] = array(
			'tab'         => 'style',
			'group'       => 'backIcon',
			'label'       => esc_html__( 'Icon position', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'top'    => esc_html__( 'Top', 'bricksable' ),
				'right'  => esc_html__( 'Right', 'bricksable' ),
				'bottom' => esc_html__( 'Bottom', 'bricksable' ),
				'left'   => esc_html__( 'Left', 'bricksable' ),
			),
			'inline'      => true,
			'placeholder' => esc_html__( 'Top', 'bricksable' ),
		);

		$this->controls['back_iconVerticalAlign'] = array(
			'tab'     => 'style',
			'group'   => 'backIcon',
			'label'   => esc_html__( 'Icon Align', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => 'stretch',
			'css'     => array(
				array(
					'property' => 'align-self',
					'selector' => '.ba-flipbox-back .icon',
				),
			),
			'inline'  => true,
		);

		$this->controls['back_iconSize'] = array(
			'tab'      => 'style',
			'group'    => 'backIcon',
			'label'    => esc_html__( 'Icon size', 'bricksable' ),
			'type'     => 'number',
			'css'      => array(
				array(
					'property' => 'font-size',
					'selector' => '.ba-flipbox-back .icon',
				),
			),
			'required' => array( 'use_back_icon_image', '!=', true ),
		);

		$this->controls['back_iconHeight'] = array(
			'tab'      => 'style',
			'group'    => 'backIcon',
			'label'    => esc_html__( 'Icon height', 'bricksable' ),
			'type'     => 'number',
			'units'    => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
				'%'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'vh' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			),
			'css'      => array(
				array(
					'property' => 'height',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'line-height',
					'selector' => '.ba-flipbox-back .icon',
				),
			),
			'required' => array( 'use_back_icon_image', '!=', true ),
		);

		$this->controls['back_iconWidth'] = array(
			'tab'   => 'style',
			'group' => 'backIcon',
			'label' => esc_html__( 'Icon width', 'bricksable' ),
			'type'  => 'number',
			'units' => array(
				'px' => array(
					'min'  => 1,
					'max'  => 200,
					'step' => 1,
				),
				'%'  => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'vw' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
			),
			'css'   => array(
				array(
					'property' => 'min-width',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'max-width',
					'selector' => '.ba-flipbox-back .icon-image-wrapper',
				),
			),
		);

		$this->controls['back_iconColor'] = array(
			'tab'      => 'style',
			'group'    => 'backIcon',
			'label'    => esc_html__( 'Icon color', 'bricksable' ),
			'type'     => 'color',
			'inline'   => true,
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.ba-flipbox-back .icon',
				),
			),
			'required' => array( 'use_back_icon_image', '!=', true ),
		);

		$this->controls['back_iconBackgroundColor'] = array(
			'tab'    => 'style',
			'group'  => 'backIcon',
			'label'  => esc_html__( 'Icon background', 'bricksable' ),
			'type'   => 'color',
			'inline' => true,
			'small'  => true,
			'css'    => array(
				array(
					'property' => 'background-color',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'background-color',
					'selector' => '.ba-flipbox-back .icon-image-wrapper',
				),
			),
		);

		$this->controls['back_iconBorder'] = array(
			'tab'    => 'style',
			'group'  => 'backIcon',
			'label'  => esc_html__( 'Icon border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-back .icon-image-wrapper img',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['back_iconBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'backIcon',
			'label'  => esc_html__( 'Icon box shadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-back .icon',
				),
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-back .icon-image-wrapper img',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		// Front Style.
		$this->controls['front_textAlign'] = array(
			'tab'    => 'style',
			'group'  => 'frontStyle',
			'label'  => esc_html__( 'Text align', 'bricksable' ),
			'type'   => 'text-align',
			'css'    => array(
				array(
					'property' => 'text-align',
					'selector' => '.ba-flipbox-front',
				),
			),
			'inline' => true,
		);

		$this->controls['front_verticalAlign'] = array(
			'tab'     => 'style',
			'group'   => 'frontStyle',
			'label'   => esc_html__( 'Vertical align', 'bricksable' ),
			'type'    => 'select',
			'type'    => 'align-items',
			'exclude' => array(
				'stretch',
			),
			'css'     => array(
				array(
					'property' => 'align-items',
					'selector' => '.ba-flipbox-front',
				),
			),
			'inline'  => true,
			'default' => 'center',
		);

		$this->controls['front_typographyHeading'] = array(
			'tab'      => 'style',
			'group'    => 'frontStyle',
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading typography', 'bricksable' ),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-front .bricks-heading',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'front_heading', '!=', '' ),
		);

		$this->controls['front_heading_bottomGap'] = array(
			'tab'         => 'style',
			'group'       => 'frontStyle',
			'type'        => 'number',
			'label'       => esc_html__( 'Heading Gap', 'bricksable' ),
			'css'         => array(
				array(
					'property' => 'margin-bottom',
					'selector' => '.ba-flipbox-front .bricks-heading',
				),
			),
			'units'       => array(
				'em' => array(
					'min'  => 0,
					'max'  => 20,
					'step' => 0.1,
				),
			),
			'default'     => '0.75em',
			'placeholder' => '0.75',
			'required'    => array( 'front_heading', '!=', '' ),
		);

		$this->controls['front_typographyBody'] = array(
			'tab'    => 'style',
			'group'  => 'frontStyle',
			'type'   => 'typography',
			'label'  => esc_html__( 'Body typography', 'bricksable' ),
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-front .ba-flipbox-content',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['front_typographySubHeading'] = array(
			'tab'      => 'style',
			'group'    => 'frontStyle',
			'type'     => 'typography',
			'label'    => esc_html__( 'Subheading typography', 'bricksable' ),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-front .ba-flipbox-subheading',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'front_subheading', '!=', '' ),
		);

		$this->controls['front_subheading_bottomGap'] = array(
			'tab'      => 'style',
			'group'    => 'frontStyle',
			'type'     => 'number',
			'label'    => esc_html__( 'Subheading Gap', 'bricksable' ),
			'css'      => array(
				array(
					'property' => 'margin-bottom',
					'selector' => '.ba-flipbox-front .ba-flipbox-subheading',
				),
			),
			'units'    => array(
				'em' => array(
					'min'  => 0,
					'max'  => 20,
					'step' => 0.1,
				),
			),
			'default'  => '0em',
			'required' => array( 'front_subheading', '!=', '' ),
		);

		$this->controls['use_front_gradient'] = array(
			'tab'         => 'style',
			'group'       => 'frontStyle',
			'label'       => esc_html__( 'Use Gradient', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Enable gradient for Front Content.', 'bricksable' ),
		);

		$this->controls['front_gradient'] = array(
			'tab'      => 'style',
			'group'    => 'frontStyle',
			'label'    => esc_html__( 'Gradient', 'bricksable' ),
			'type'     => 'gradient',
			'css'      => array(
				array(
					'property' => 'background-image',
					'selector' => '.ba-flipbox-front',
				),
			),
			'required' => array( 'use_front_gradient', '=', true ),
		);

		$this->controls['front_containerBorder'] = array(
			'tab'    => 'style',
			'group'  => 'frontStyle',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-front',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['front_containerBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'frontStyle',
			'type'   => 'box-shadow',
			'label'  => esc_html__( 'Box shadow', 'bricksable' ),
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-front',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['front_containerMargin'] = array(
			'tab'   => 'style',
			'group' => 'frontStyle',
			'type'  => 'dimensions',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-front',
				),
			),
		);

		$this->controls['front_contentPadding'] = array(
			'tab'     => 'style',
			'group'   => 'frontStyle',
			'type'    => 'dimensions',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-front',
				),
			),
			'default' => array(
				'top'    => 20,
				'right'  => 20,
				'bottom' => 20,
				'left'   => 20,
			),
		);

		// Back Style.
		$this->controls['back_textAlign'] = array(
			'tab'    => 'style',
			'group'  => 'backStyle',
			'label'  => esc_html__( 'Text align', 'bricksable' ),
			'type'   => 'text-align',
			'css'    => array(
				array(
					'property' => 'text-align',
					'selector' => '.ba-flipbox-back',
				),
			),
			'inline' => true,
		);

		$this->controls['back_verticalAlign'] = array(
			'tab'     => 'style',
			'group'   => 'backStyle',
			'label'   => esc_html__( 'Vertical align', 'bricksable' ),
			'type'    => 'align-items',
			'exclude' => array(
				'stretch',
			),
			'css'     => array(
				array(
					'property' => 'align-items',
					'selector' => '.ba-flipbox-back',
				),
			),
			'inline'  => true,
			'default' => 'center',
		);

		$this->controls['back_typographyHeading'] = array(
			'tab'      => 'style',
			'group'    => 'backStyle',
			'type'     => 'typography',
			'label'    => esc_html__( 'Heading typography', 'bricksable' ),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-back .bricks-heading',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'back_heading', '!=', '' ),
		);

		$this->controls['back_heading_bottomGap'] = array(
			'tab'         => 'style',
			'group'       => 'backStyle',
			'type'        => 'number',
			'label'       => esc_html__( 'Heading Gap', 'bricksable' ),
			'css'         => array(
				array(
					'property' => 'margin-bottom',
					'selector' => '.ba-flipbox-back .bricks-heading',
				),
			),
			'units'       => array(
				'em' => array(
					'min'  => 0,
					'max'  => 20,
					'step' => 0.1,
				),
			),
			'default'     => '0.75em',
			'placeholder' => '0.75',
			'required'    => array( 'back_heading', '!=', '' ),
		);

		$this->controls['back_typographyBody'] = array(
			'tab'    => 'style',
			'group'  => 'backStyle',
			'type'   => 'typography',
			'label'  => esc_html__( 'Body typography', 'bricksable' ),
			'css'    => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-back .ba-flipbox-content',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['back_typographySubHeading'] = array(
			'tab'      => 'style',
			'group'    => 'backStyle',
			'type'     => 'typography',
			'label'    => esc_html__( 'Subheading typography', 'bricksable' ),
			'css'      => array(
				array(
					'property' => 'font',
					'selector' => '.ba-flipbox-back .ba-flipbox-subheading',
				),
			),
			'inline'   => true,
			'small'    => true,
			'required' => array( 'back_subheading', '!=', '' ),
		);

		$this->controls['back_subheading_bottomGap'] = array(
			'tab'      => 'style',
			'group'    => 'backStyle',
			'type'     => 'number',
			'label'    => esc_html__( 'Subheading Gap', 'bricksable' ),
			'css'      => array(
				array(
					'property' => 'margin-bottom',
					'selector' => '.ba-flipbox-back .ba-flipbox-subheading',
				),
			),
			'units'    => array(
				'em' => array(
					'min'  => 0,
					'max'  => 20,
					'step' => 0.1,
				),
			),
			'default'  => '0em',
			'required' => array( 'back_subheading', '!=', '' ),
		);

		$this->controls['use_back_gradient'] = array(
			'tab'         => 'style',
			'group'       => 'backStyle',
			'label'       => esc_html__( 'Use Gradient', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Enable gradient for Back Content.', 'bricksable' ),
		);

		$this->controls['back_gradient'] = array(
			'tab'      => 'style',
			'group'    => 'backStyle',
			'label'    => esc_html__( 'Gradient', 'bricksable' ),
			'type'     => 'gradient',
			'css'      => array(
				array(
					'property' => 'background-image',
					'selector' => '.ba-flipbox-back',
				),
			),
			'required' => array( 'use_back_gradient', '=', true ),
		);

		$this->controls['back_containerBorder'] = array(
			'tab'    => 'style',
			'group'  => 'backStyle',
			'type'   => 'border',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-flipbox-back',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['back_containerBoxShadow'] = array(
			'tab'    => 'style',
			'group'  => 'backStyle',
			'type'   => 'box-shadow',
			'label'  => esc_html__( 'Box shadow', 'bricksable' ),
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-flipbox-back',
				),
			),
			'inline' => true,
			'small'  => true,
		);

		$this->controls['back_containerMargin'] = array(
			'tab'   => 'style',
			'group' => 'backStyle',
			'type'  => 'dimensions',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-flipbox-back',
				),
			),
		);

		$this->controls['back_containerPadding'] = array(
			'tab'     => 'style',
			'group'   => 'backStyle',
			'type'    => 'dimensions',
			'label'   => esc_html__( 'Padding', 'bricksable' ),
			'css'     => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-flipbox-back',
				),
			),
			'default' => array(
				'top'    => 20,
				'right'  => 20,
				'bottom' => 20,
				'left'   => 20,
			),
		);

		// Flipbox Settings.
		$this->controls['height'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Height', 'bricksable' ),
			'type'        => 'number',
			'css'         => array(
				array(
					'property' => 'min-height',
					'selector' => '.ba-flipbox-wrapper',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 1,
					'max'  => 1600,
					'step' => 1,
				),
				'vh' => array(
					'min'  => 1,
					'max'  => 100,
					'step' => 1,
				),
			),
			'default'     => '300px',
			'description' => esc_html__( "Adjust the flipbox container's height.", 'bricksable' ),
		);

		$this->controls['flipbox_animation_duration'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Animation Duration', 'bricksable' ),
			'type'        => 'number',
			'min'         => 100,
			'max'         => 2000,
			'step'        => '100',
			'unit'        => 'ms',
			'css'         => array(
				array(
					'property' => 'transition-duration',
					'selector' => '.ba-flipbox-front',
				),
				array(
					'property' => 'transition-duration',
					'selector' => '.ba-flipbox-back',
				),
			),
			'inline'      => true,
			'default'     => 650,
			'placeholder' => '650',
		);

		$this->controls['effect'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Effect', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'flip'  => esc_html__( 'Flip', 'bricksable' ),
				'zoom'  => esc_html__( 'Zoom', 'bricksable' ),
				'fade'  => esc_html__( 'Fade', 'bricksable' ),
				'slide' => esc_html__( 'Slide', 'bricksable' ),
				'swap'  => esc_html__( 'Swap', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'flip',
			'placeholder' => esc_html__( 'Flip', 'bricksable' ),
		);

		$this->controls['flip_animation'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Flip Animation', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'up'     => esc_html__( 'Flip Up', 'bricksable' ),
				'right'  => esc_html__( 'Flip Right', 'bricksable' ),
				'bottom' => esc_html__( 'Flip Bottom', 'bricksable' ),
				'left'   => esc_html__( 'Flip Left', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'left',
			'placeholder' => esc_html__( 'Flip Left', 'bricksable' ),
			'required'    => array( 'effect', '=', 'flip' ),
		);

		$this->controls['zoom_animation'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Zoom Animation', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'in'  => esc_html__( 'Zoom In', 'bricksable' ),
				'out' => esc_html__( 'Zoom Out', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'in',
			'placeholder' => esc_html__( 'Zoom In', 'bricksable' ),
			'required'    => array( 'effect', '=', 'zoom' ),
		);

		$this->controls['slide_animation'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Slide Animation', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'up'     => esc_html__( 'Slide Up', 'bricksable' ),
				'right'  => esc_html__( 'Slide Right', 'bricksable' ),
				'bottom' => esc_html__( 'Slide Bottom', 'bricksable' ),
				'left'   => esc_html__( 'Slide Left', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'left',
			'placeholder' => esc_html__( 'Slide Left', 'bricksable' ),
			'required'    => array( 'effect', '=', 'slide' ),
		);

		$this->controls['swap_animation'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Swap Animation', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'up'     => esc_html__( 'Swap Up', 'bricksable' ),
				'right'  => esc_html__( 'Swap Right', 'bricksable' ),
				'bottom' => esc_html__( 'Swap Bottom', 'bricksable' ),
				'left'   => esc_html__( 'Swap Left', 'bricksable' ),
			),
			'inline'      => true,
			'default'     => 'left',
			'placeholder' => esc_html__( 'Swap Left', 'bricksable' ),
			'required'    => array( 'effect', '=', 'swap' ),
		);

		$this->controls['flip_3d'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( '3D Effect', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Enable 3D effect on your flipbox.', 'bricksable' ),
			'required'    => array( 'effect', '=', 'flip' ),
		);

		$this->controls['flip_elastic'] = array(
			'tab'         => 'content',
			'group'       => 'settings',
			'label'       => esc_html__( 'Use Elastic Effect', 'bricksable' ),
			'type'        => 'checkbox',
			'inline'      => true,
			'description' => esc_html__( 'Enable elastic effect when you hover over your flipbox.', 'bricksable' ),
			'required'    => array( 'effect', '=', array( 'flip', 'zoom', 'slide', 'swap' ) ),
		);
	}

	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-flip-box' );
	}

	public function render() {
		$settings = $this->settings;
		// Front.
		$icon_position = isset( $settings['front_iconPosition'] ) ? $settings['front_iconPosition'] : 'top';

		if ( $icon_position ) {
			$wrapper_classes[] = isset( $settings['front_content_type'] ) && 'templates' === $settings['front_content_type'] ? 'ba-flipbox-content-templates' : 'icon-box-wrapper icon-position-' . $icon_position;
		}

		$this->set_attribute( 'ba-flipbox-front-wrapper', 'class', $wrapper_classes );

		$front_icon = ! empty( $settings['front_icon'] ) ? self::render_icon( $settings['front_icon'] ) : false;

		$this->set_attribute( 'ba-flipbox-front', 'class', array( 'ba-flipbox-front', 'ba-flipbox-container' ) );
		$this->set_attribute( 'ba-flipbox-front-content', 'class', array( 'ba-flipbox-content-wrapper' ) );
		$front_heading_tag     = isset( $settings['front_heading_tag'] ) ? esc_html( $settings['front_heading_tag'] ) : 'h3';
		$front_heading_classes = array(
			'bricks-heading',
			'bricks-heading-' . $front_heading_tag,
		);
		$this->set_attribute( 'front_heading', $front_heading_tag );
		$this->set_attribute( 'front_heading', 'class', $front_heading_classes );

		$this->set_attribute( 'front_subheading', 'class', 'ba-flipbox-subheading' );
		$this->set_attribute( 'front_content', 'class', 'ba-flipbox-content' );

		$front_heading_output    = '';
		$front_subheading_output = '';
		$front_content_output    = '';

		if ( isset( $settings['front_heading'] ) ) {
			$front_heading_output = '<' . $this->render_attributes( 'front_heading' ) . '>' . $settings['front_heading'] . '</' . $front_heading_tag . '>';
		}

		if ( isset( $settings['front_subheading'] ) ) {
			$front_subheading_output .= '<div ' . $this->render_attributes( 'front_subheading' ) . '>' . $settings['front_subheading'] . '</div>';
		}

		if ( isset( $settings['front_content'] ) ) {
			$front_subheading_output .= '<div ' . $this->render_attributes( 'front_content' ) . '>' . $settings['front_content'] . '</div>';
		}

		// Render front icon box.
		$front_box_html = '';
		// Template.
		if ( isset( $settings['front_content_type'] ) && 'templates' === $settings['front_content_type'] ) {

			$template_id = ! empty( $settings['front_template'] ) ? intval( $settings['front_template'] ) : false;
			if ( ! $template_id ) {
				return $this->render_element_placeholder(
					array(
						'title' => esc_html__( 'No template selected.', 'bricksable' ),
					)
				);
			}

			$front_box_html = do_shortcode( '[bricks_template id="' . $template_id . '" ]' );

		} else {
			if ( 'bottom' === $icon_position ) {
				$front_box_html .= '<div ' . $this->render_attributes( 'ba-flipbox-front-content' ) . '>' . $front_heading_output . $front_subheading_output . $front_content_output . '</div>';
			}

			if ( isset( $front_icon ) && ! isset( $settings['use_front_icon_image'] ) ) {
				$front_box_html .= '<div class="icon">'; //phpcs:ignore
				$front_box_html .= $front_icon; //phpcs:ignore
				$front_box_html .= '</div>';
			}

			// Icon Image Render.
			if ( isset( $settings['use_front_icon_image'] ) && isset( $settings['front_icon_image'] ) ) {
				$image_atts       = array();
				$image_atts['id'] = 'image-' . $settings['front_icon_image']['id'];

				$image_wrapper_classes = array( 'icon-image-wrapper' );
				$img_classes           = array( 'ba-flipbox-icon-img' );
				$img_classes[]         = 'size-' . $settings['front_icon_image']['size'];
				$image_atts['class']   = join( ' ', $img_classes );

				$this->set_attribute( 'front_icon_image', 'class', $image_wrapper_classes );

				$front_box_html .= '<div ' . $this->render_attributes( 'front_icon_image' ) . '>';
				$close_front_tag = false;

				// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
				if ( isset( $settings['front_icon_image']['id'] ) ) {
					$front_box_html .= wp_get_attachment_image( $settings['front_icon_image']['id'], $settings['front_icon_image']['size'], false, $image_atts );
				} elseif ( ! empty( $settings['front_icon_image']['url'] ) ) {
					$front_box_html .= '<img src="' . $settings['front_icon_image']['url'] . '">';
				}

				if ( $close_front_tag ) {
					$front_box_html .= '</div>';
				}

				$front_box_html .= '</div>';
			}

			if ( 'bottom' !== $icon_position ) {
				$front_box_html .= '<div ' . $this->render_attributes( 'ba-flipbox-front-content' ) . '>' . $front_heading_output . $front_subheading_output . $front_content_output . '</div>';
			}
		}

		$front_wrapper = sprintf(
			'<div %1$s>
				<div %2$s>%3$s</div>
			</div>',
			$this->render_attributes( 'ba-flipbox-front' ),
			$this->render_attributes( 'ba-flipbox-front-wrapper' ),
			$front_box_html
		);

		// Back.
		$back_icon_position = isset( $settings['back_iconPosition'] ) ? $settings['back_iconPosition'] : 'top';

		if ( $back_icon_position ) {
			$back_wrapper_classes[] = isset( $settings['back_content_type'] ) && 'templates' === $settings['back_content_type'] ? 'ba-flipbox-content-templates' : 'icon-box-wrapper icon-position-' . $back_icon_position;
		}

		$this->set_attribute( 'ba-flipbox-back-wrapper', 'class', $back_wrapper_classes );

		// Link.
		if ( isset( $settings['back_link'] ) ) {
			$this->set_link_attributes( 'a', $settings['back_link'] );
		}

		// Icon.
		$back_icon = ! empty( $settings['back_icon'] ) ? self::render_icon( $settings['back_icon'] ) : false;

		$this->set_attribute( 'ba-flipbox-back', 'class', array( 'ba-flipbox-back', 'ba-flipbox-container' ) );
		$this->set_attribute( 'ba-flipbox-back-content', 'class', array( 'ba-flipbox-content-wrapper' ) );
		$back_heading_tag = isset( $settings['back_heading_tag'] ) ? esc_html( $settings['back_heading_tag'] ) : 'h3';
		$heading_classes  = array(
			'bricks-heading',
			'bricks-heading-' . $back_heading_tag,
		);
		$this->set_attribute( 'back_heading', $back_heading_tag );
		$this->set_attribute( 'back_heading', 'class', $heading_classes );
		$this->set_attribute( 'back_subheading', 'class', 'ba-flipbox-subheading' );
		$this->set_attribute( 'back_content', 'class', 'ba-flipbox-content' );

		$back_heading_output    = '';
		$back_subheading_output = '';
		$back_content_output    = '';

		if ( isset( $settings['back_heading'] ) ) {
			$back_heading_output .= '<' . $this->render_attributes( 'back_heading' ) . '>' . $settings['back_heading'] . '</' . $back_heading_tag . '>';
		}

		if ( isset( $settings['back_subheading'] ) ) {
			$back_subheading_output .= '<div ' . $this->render_attributes( 'back_subheading' ) . '>' . $settings['back_subheading'] . '</div>';
		}

		if ( isset( $settings['back_content'] ) ) {
			$back_content_output .= '<div ' . $this->render_attributes( 'back_content' ) . '>' . $settings['back_content'] . '</div>';
		}
		// Render back icon box.
		$back_icon_box_html = '';
		// Template.
		if ( isset( $settings['back_content_type'] ) && 'templates' === $settings['back_content_type'] ) {

			$template_id = ! empty( $settings['back_template'] ) ? intval( $settings['back_template'] ) : false;
			if ( ! $template_id ) {
				return $this->render_element_placeholder(
					array(
						'title' => esc_html__( 'No template selected.', 'bricksable' ),
					)
				);
			}

			$back_icon_box_html = do_shortcode( '[bricks_template id="' . $template_id . '" ]' );

		} else {
			if ( 'bottom' === $icon_position ) {
				$back_icon_box_html .= '<div ' . $this->render_attributes( 'ba-flipbox-back-content' ) . '>' . $back_heading_output . $back_subheading_output . $back_content_output . '</div>';
			}

			if ( isset( $back_icon ) && ! isset( $settings['back_icon_image'] ) ) {
				$back_icon_box_html .= '<div class="icon">'; //phpcs:ignore
				if ( isset( $settings['back_link'] ) ) {
					$back_icon_box_html .= '<a ' . $this->render_attributes( 'a' ) . '>';
				}
				$back_icon_box_html .= $back_icon; //phpcs:ignore
				if ( isset( $settings['back_link'] ) ) {
					$back_icon_box_html .= '</a>';
				}
				$back_icon_box_html .= '</div>';
			}

			// Icon Image Render.
			if ( isset( $settings['use_back_icon_image'] ) && isset( $settings['back_icon_image'] ) ) {
				$image_atts       = array();
				$image_atts['id'] = 'image-' . $settings['back_icon_image']['id'];

				$image_wrapper_classes = array( 'icon-image-wrapper' );
				$img_classes           = array( 'ba-flipbox-icon-img' );
				$img_classes[]         = 'size-' . $settings['back_icon_image']['size'];
				$image_atts['class']   = join( ' ', $img_classes );

				$this->set_attribute( 'back_icon_image', 'class', $image_wrapper_classes );

				$back_icon_box_html .= '<div ' . $this->render_attributes( 'back_icon_image' ) . '>';
				$close_back_tag      = false;

				// Lazy load atts set via 'wp_get_attachment_image_attributes' filter.
				if ( isset( $settings['back_icon_image']['id'] ) ) {
					$back_icon_box_html .= wp_get_attachment_image( $settings['back_icon_image']['id'], $settings['back_icon_image']['size'], false, $image_atts );
				} elseif ( ! empty( $settings['back_icon_image']['url'] ) ) {
					$back_icon_box_html .= '<img src="' . $settings['back_icon_image']['url'] . '">';
				}

				if ( $close_back_tag ) {
					$back_icon_box_html .= '</div>';
				}

				$back_icon_box_html .= '</div>';
			}

			if ( 'bottom' !== $icon_position ) {
				$back_icon_box_html .= '<div ' . $this->render_attributes( 'ba-flipbox-back-content' ) . '>' . $back_heading_output . $back_subheading_output . $back_content_output . '</div>';
			}

			// Button.
			$button_classes[] = 'bricks-button';

			$button_classes[] = isset( $settings['back_button_size'] ) ? $settings['back_button_size'] : 'md';

			if ( isset( $settings['back_button_style'] ) ) {
				// Outline.
				if ( isset( $settings['back_button_outline'] ) ) {
					$button_classes[] = 'outline';
					$button_classes[] = 'bricks-color-' . $settings['back_button_style'];
				} else {
					// Fill.
					$button_classes[] = 'bricks-background-' . $settings['back_button_style'];
				}
			}

			// Button circle.
			if ( isset( $settings['back_button_circle'] ) ) {
				$button_classes[] = 'circle';
			}

			if ( isset( $settings['back_button_block'] ) ) {
				$button_classes[] = 'block';
			}

			$this->set_attribute( 'button-wrapper', 'class', $button_classes );

			// Link.
			if ( isset( $settings['back_button_link'] ) ) {
				$this->set_link_attributes( 'button-wrapper', $settings['back_button_link'] );
			}

			$icon_position = isset( $settings['back_button_iconPosition'] ) ? $settings['back_button_iconPosition'] : 'right';

			if ( isset( $settings['back_button_icon']['icon'] ) ) {
				$this->set_attribute( 'button_icon', 'class', $settings['back_button_icon']['icon'] );
				$this->set_attribute( 'button-wrapper', 'class', "icon-$icon_position" );
			}

			$link_tag = isset( $settings['back_button_link'] ) ? 'a' : 'span';

			// Render button.
			$button_html = '<' . esc_attr( $link_tag ) . ' ' . $this->render_attributes( 'button-wrapper' ) . '>';

			// $button_html .= '<span class="bricks-button-inner">';

			if ( isset( $settings['back_button_icon']['icon'] ) && 'left' === $icon_position ) {
				$button_html .= '<i ' . $this->render_attributes( 'button_icon' ) . '></i>';
			}

			if ( isset( $settings['back_button_text'] ) ) {
				$button_html .= '<span ' . $this->render_attributes( 'button-text' ) . '>' . trim( $settings['back_button_text'] ) . '</span>';
			}

			if ( isset( $settings['back_button_icon']['icon'] ) && 'right' === $icon_position ) {
				$button_html .= '<i ' . $this->render_attributes( 'button_icon' ) . '></i>';
			}

			// $button_html .= '</span>';

			$button_html .= '</' . esc_attr( $link_tag ) . '>';
		}
		$back_wrapper = sprintf(
			'<div %1$s>
				<div %2$s>%3$s%4$s</div>
			</div>',
			$this->render_attributes( 'ba-flipbox-back' ),
			$this->render_attributes( 'ba-flipbox-back-wrapper' ),
			$back_icon_box_html,
			isset( $settings['use_back_button'] ) ? $button_html : ''
		);

		$flip_animation = '';
		if ( 'flip' === $settings['effect'] ) {
			$flip_animation = $settings['flip_animation'];

		} elseif ( 'zoom' === $settings['effect'] ) {
			$flip_animation = $settings['zoom_animation'];
		} elseif ( 'slide' === $settings['effect'] ) {
			$flip_animation = $settings['slide_animation'];
		} elseif ( 'swap' === $settings['effect'] ) {
			$flip_animation = $settings['swap_animation'];
		} else {
			$flip_animation = 'in';
		}
		$flip_3d           = isset( $settings['flip_3d'] ) && 'flip' === $settings['effect'] ? ' ba-flipbox-3d' : '';
		$flip_elastic      = isset( $settings['flip_elastic'] ) ? ' ba-flipbox-flip-elastic' : '';
		$flipbox_animation = esc_attr( $flip_3d ) . ' ba-flipbox-' . esc_attr( $settings['effect'] ) . '-' . esc_attr( $flip_animation ) . esc_attr( $flip_elastic );
		// Render element content.

		$output = sprintf(
			'%4$s<div class="ba-flipbox-wrapper%3$s">%1$s%2$s</div>%5$s',
			$front_wrapper,
			$back_wrapper,
			esc_attr( $flipbox_animation ),
			// for Bricks 1.4.
			substr( BRICKS_VERSION, 0, 3 ) > '1.3' ? sprintf(
				'<div %1$s>',
				$this->render_attributes( '_root' )
			) : '',
			substr( BRICKS_VERSION, 0, 3 ) > '1.3' ? '</div>' : ''
		);
		//phpcs:ignore
		echo $output;
	}
}
