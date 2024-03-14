<?php

class BPD_IconList extends ET_Builder_Module {

	public $slug       = 'bpd_iconlist';
	public $vb_support = 'on';
	public $child_slug = 'bpd_icon_list_item';

	protected $module_credits = array(
		'module_uri' => 'https://webtechstreet.com',
		'author'     => 'WebTechStreet',
		'author_uri' => 'https://webtechstreet.com',
	);

	public function init() {
		$this->name = esc_html__( 'Icon List', 'bpd-booster-pack-divi' );

		$this->settings_modal_toggles  = array(
			'advanced' => array(
									'toggles' => array(
										'icon_style' => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
										'title_style'=> esc_html__( 'Title', 'bpd-booster-pack-divi' ),
										'list_style'=> esc_html__( 'List', 'bpd-booster-pack-divi' ),
							),
						),
				);
	}

	public function get_fields() {
		return array(
			'icon_size' => array(
						'label' => esc_html__('Icon Size', 'bpd-booster-pack-divi'),
						'type' => 'range',
						'default' => '14px',
						'default_unit'    => 'px',
						'option_category' => 'basic_option',
						'description' => esc_html__('Icon Size.', 'bpd-booster-pack-divi'),
						'tab_slug' => 'advanced',
						'toggle_slug' => 'icon_style',
					),
			'icon_color' => array(
						'label' => esc_html__('Color', 'bpd-booster-pack-divi'),
						'type'              => 'color-alpha',
						'custom_color'      => true,
						'tab_slug' => 'advanced',
						'toggle_slug' => 'icon_style',
					),
			'icon_color_hover' => array(
						'label' => esc_html__('Color Hover', 'bpd-booster-pack-divi'),
						'type'              => 'color-alpha',
						'custom_color'      => true,
						'tab_slug' => 'advanced',
						'toggle_slug' => 'icon_style',
					),
			'text_color_hover' => array(
						'label' => esc_html__('Text Color Hover', 'bpd-booster-pack-divi'),
						'type'              => 'color-alpha',
						'custom_color'      => true,
						'tab_slug' => 'advanced',
						'toggle_slug' => 'title_style',
					),
			'list_layout' => array(
				'label'           => esc_html__( 'Layout', 'bpd-booster-pack-divi' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'horizontal' => esc_html__( 'Horizontal', 'bpd-booster-pack-divi' ),
					'vertical' => esc_html__( 'Vertical', 'bpd-booster-pack-divi' ),
				),
				'default'					=> 'horizontal',
				'tab_slug'				=> 'advanced',
				'toggle_slug'     => 'list_style',
				'description'     => esc_html__( 'Choose list style', 'bpd-booster-pack-divi' ),
			),
		'space_between' => array(
					'label' => esc_html__('Space Between', 'bpd-booster-pack-divi'),
					'type' => 'range',
					'default' => '14px',
					'default_unit'    => 'px',
					'option_category' => 'basic_option',
					'description' => esc_html__('Space Between', 'bpd-booster-pack-divi'),
					'tab_slug' => 'advanced',
					'toggle_slug' => 'list_style',
				),
			'list_divider' => array(
						'label'             => esc_html__( 'Divider', 'bpd-booster-pack-divi' ),
						'type'              => 'yes_no_button',
						'options'           => array(
							'on'  => esc_html__( 'On', 'bpd-booster-pack-divi' ),
							'off' => esc_html__( 'Off', 'bpd-booster-pack-divi' ),
						),
						'default' => 'off',
						'tab_slug'        => 'advanced',
						'toggle_slug'     => 'list_style',
			),
			'list_divider_style' => array(
				'label'           => esc_html__( 'Style', 'bpd-booster-pack-divi' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'solid' => esc_html__( 'Solid', 'bpd-booster-pack-divi' ),
					'double' => esc_html__( 'Double', 'bpd-booster-pack-divi' ),
					'dotted' => esc_html__( 'Dotted', 'bpd-booster-pack-divi' ),
					'dashed' => esc_html__( 'Dashed', 'bpd-booster-pack-divi' ),
				),
				'default'					=> 'solid',
				'tab_slug'				=> 'advanced',
				'toggle_slug'     => 'list_style',
				'description'     => esc_html__( 'Choose Divider Style', 'bpd-booster-pack-divi' ),
				'show_if'         => array(
															'list_divider' => 'on',
														),
			),
			'list_divider_weight' => array(
						'label' => esc_html__('Weight', 'bpd-booster-pack-divi'),
						'type' => 'range',
						'default' => '1px',
						'default_unit'    => 'px',
						'range_settings' =>array(
														'min' => '0',
														'max' => '20',
														'step' => '1',
													),
						'option_category' => 'basic_option',
						'description' => esc_html__('Divider Weight.', 'bpd-booster-pack-divi'),
						'tab_slug' => 'advanced',
						'toggle_slug' => 'list_style',
						'show_if'         => array(
																	'list_divider' => 'on',
																),
					),
			'list_divider_height' => array(
						'label' => esc_html__('Height/Width', 'bpd-booster-pack-divi'),
						'type' => 'range',
						'default' => '100%',
						'default_unit'    => '%',
						'range_settings' =>array(
														'min' => '0',
														'max' => '100',
														'step' => '1',
													),
						'option_category' => 'basic_option',
						'description' => esc_html__('Divider Weight.', 'bpd-booster-pack-divi'),
						'tab_slug' => 'advanced',
						'toggle_slug' => 'list_style',
						'show_if'         => array(
																	'list_divider' => 'on',
																),
					),
			'list_divider_color' => array(
						'label' => esc_html__('Color', 'bpd-booster-pack-divi'),
						'type'              => 'color-alpha',
						'custom_color'      => true,
						'tab_slug' => 'advanced',
						'toggle_slug' => 'list_style',
						'show_if'         => array(
																	'list_divider' => 'on',
																),
					),
					'text_indent' => array(
								'label' => esc_html__('Text Indent', 'bpd-booster-pack-divi'),
								'type' => 'range',
								'default' => '20px',
								'default_unit'    => 'px',
								'range_settings' =>array(
																'min' => '0',
																'max' => '50',
																'step' => '1',
															),
								'option_category' => 'basic_option',
								'description' => esc_html__('Text Indent.', 'bpd-booster-pack-divi'),
								'tab_slug' => 'advanced',
								'toggle_slug' => 'title_style',
							),
		);
	}

	public function get_advanced_fields_config() {
	 $advanced_fields = array();

	 $advanced_fields['fonts'] = array(
		 'text'   => array(
			 'label'    => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
			 'css'      => array(
				 'main' => "%%order_class%% .bpd-icon-list-text",
				 'important' => 'all',
			 ),
			 'toggle_slug' => 'title_style'
		 ),
	 );
	 $advanced_fields['margin_padding'] = array(
		 	 'label'    => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
			 'css'      => array(
				 'main' => "%%order_class%% .bpd-icon-list-items",
				 'important' => 'all',
		 ),
	 );
	 $advanced_fields['border'] = array(
		 	 'label'    => esc_html__( 'Icon List', 'bpd-booster-pack-divi' ),
			 'css'      => array(
				 'main' => "%%order_class%% .bpd-icon-list-items",
				 'important' => 'all',
		 ),
	 );

	 $advanced_fields['text'] = false;
	 $advanced_fields['box_shadow'] = false;
	 $advanced_fields['filters'] = false;
	 return $advanced_fields;
	}

	public function get_settings_modal_toggles() {
	 return array(
	 'advanced' => array(
		 'toggles' => array(
			 'icon_style' =>array(
				 'priority' => 2,
				 'title' => 'Icon',
			 ),
			 'title_style' =>array(
				 'priority' => 3,
				 'title' => 'Title',
			 ),
			 'list_style' =>array(
				 'priority' => 1,
				 'title' => 'List',
			 ),
		 )
	 )
	);
	}

	function render_prop( $value = '', $field_name = '', $field_type = '', $render_slug = '') {
			$order_class = self::get_module_order_class( $render_slug );
			$output      = '';

			switch ( $field_type ) {
				case 'select_fonticon':
				$output = sprintf(
					'<span class="bpd-icon-list-icon">%1$s</span>',
					esc_attr( et_pb_process_font_icon( $value ))
				);
				break;
			}
			return $output;
		}

	public function render( $attrs, $content = null, $render_slug ) {

	//	echo "<pre>";	print_r($this->props); echo "</pre>";
	$listlayout   = $this->props['list_layout'];
	$iconsize   = $this->props['icon_size'];
	$iconcolor   = $this->props['icon_color'];
	$iconcolorhover   = $this->props['icon_color_hover'];
	$textcolorhover   = $this->props['text_color_hover'];
	$listspace   = $this->props['space_between'];
	$listdivider   = $this->props['list_divider'];
	$output1 ="";
		if ( '' !== $iconsize ) {
		ET_Builder_Element::set_style( $render_slug, array(
			'selector'    => '%%order_class%% .bpd-icon-list-icon',
			'declaration' => sprintf(
				'font-size : %1$s;width:%1$s;',
				esc_html($iconsize)
			),
		) );
		}
		if ( '' !== $iconcolor ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .bpd-icon-list-icon',
				'declaration' => sprintf(
					'color : %1$s;',
					esc_html($iconcolor)
				),
			) );
		}
		if ( '' !== $iconcolorhover ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .bpd-icon-list-item:hover .bpd-icon-list-icon',
				'declaration' => sprintf(
					'color : %1$s;',
					esc_html($iconcolorhover)
				),
			) );
		}
		if('horizontal' == $listlayout)
		{
			if ( '' !== $listspace ) {
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .bpd-icon-list-items .bpd_icon_list_item',
					'declaration' => sprintf(
						'margin-bottom : %1$s !important;',
						esc_html($listspace)
					),
				) );
			}
		}
		if('vertical' == $listlayout)
		{
			if ( '' !== $listspace ) {
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-vertical .bpd_icon_list_item',
					'declaration' => sprintf(
						'margin-right : %1$s !important;',
						esc_html($listspace)
					),
				) );
			}
		}

		if('on' == $listdivider)
		{
			if('' !== $this->props['list_divider_style'])
			{
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-vertical .bpd_icon_list_item:after',
					'declaration' => sprintf(
						'border-right-style : %1$s;right : calc(-%2$s/2);top: 0;',
						esc_html($this->props['list_divider_style']),$listspace
					),
				) );
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-horizontal .bpd_icon_list_item:after',
					'declaration' => sprintf(
						'border-bottom-style : %1$s;bottom: calc(-%2$s/2);',
						esc_html($this->props['list_divider_style']),$listspace
					),
				) );

				if('' !== $this->props['list_divider_weight'])
				{
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-vertical .bpd_icon_list_item:after',
						'declaration' => sprintf(
							'border-right-width : %1$s;',
							esc_html($this->props['list_divider_weight'])
						),
					) );
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-horizontal .bpd_icon_list_item:after',
						'declaration' => sprintf(
							'border-bottom-width : %1$s;',
							esc_html($this->props['list_divider_weight'])
						),
					) );
				}
				if('' !== $this->props['list_divider_height'])
				{
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-vertical .bpd_icon_list_item:after',
						'declaration' => sprintf(
							'height : %1$s;',
							esc_html($this->props['list_divider_height'])
						),
					) );
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-icon-list-items.bpd-icon-list-horizontal .bpd_icon_list_item:after',
						'declaration' => sprintf(
							'width : %1$s;',
							esc_html($this->props['list_divider_height'])
						),
					) );
				}
				if('' !== $this->props['list_divider_color'])
				{
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-icon-list-items .bpd_icon_list_item:after',
						'declaration' => sprintf(
							'border-color : %1$s;',
							esc_html($this->props['list_divider_color'])
						),
					) );
				}
			}
		}
		if('' !== $this->props['text_indent'])
		{
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .bpd-icon-list-items .bpd_icon_list_item .bpd-icon-list-text',
				'declaration' => sprintf(
					'padding-left : %1$s !important;',
					esc_html($this->props['text_indent'])
				),
			) );
		}
		if('' !== $textcolorhover)
		{
			ET_Builder_Element::set_style( $render_slug, array(
				'selector'    => '%%order_class%% .bpd-icon-list-item:hover .bpd-icon-list-text',
				'declaration' => sprintf(
					'color : %1$s !important;',
					esc_html($this->props['text_color_hover'])
				),
			) );
		}

		if('vertical' == $listlayout)
		{
			$output1 .= sprintf('<div class="bpd-icon-list-items bpd-icon-list-vertical">%1$s</div>',$this->content);
		}
		else {
			$output1 .= sprintf('<div class="bpd-icon-list-items bpd-icon-list-horizontal">%1$s</div>',$this->content);
		}

		return $output1;
	}

}

new BPD_IconList;
