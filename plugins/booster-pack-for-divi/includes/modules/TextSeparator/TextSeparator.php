<?php

class BPD_TextSeparator extends ET_Builder_Module {

	public $slug       = 'bpd_textseparator';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://webtechstreet.com',
		'author'     => 'WebTechStreet',
		'author_uri' => 'https://webtechstreet.com',
	);

	public function init() {
		$this->name = esc_html__( 'Text Separator', 'bpd-booster-pack-divi' );
		//$this->icon = '%%67%%';
		$this->settings_modal_toggles = [
			'advanced' => [
				'toggles' => [
					'title_style'       => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
                    'icon_style'        => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
                    'divider_style'     => esc_html__( 'Divider', 'bpd-booster-pack-divi' ),
				],
            ],
			'general'  => [
				'toggles' => [
					'title'             => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
					'icon'              => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
					'divider_general'   => esc_html__( 'Divider', 'bpd-booster-pack-divi' ),
				],
			],
		];
	}

  public function get_fields() {
    return array(
      'title_sep' => [
        'label'           => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
        'type'            => 'text',
        'option_category' => 'basic_option',
        'description'     => esc_html__( 'Text entered here will appear as title.', 'bpd-booster-pack-divi' ),
        'toggle_slug'     => 'title',
      ],
      'html_tag' => [
        'label'           => esc_html__( 'Title Html Tag', 'bpd-booster-pack-divi' ),
        'type'            => 'select',
        'option_category' => 'basic_option',
        'options'         => [
          'h1' => esc_html__( 'H1', 'bpd-booster-pack-divi' ),
          'h2' => esc_html__( 'H2', 'bpd-booster-pack-divi' ),
          'h3' => esc_html__( 'H3', 'bpd-booster-pack-divi' ),
          'h4' => esc_html__( 'H4', 'bpd-booster-pack-divi' ),
          'h5' => esc_html__( 'H5', 'bpd-booster-pack-divi' ),
          'h6' => esc_html__( 'H6', 'bpd-booster-pack-divi' ),
        ],
        'default'         => 'h2',
        'toggle_slug'     => 'title',
        'description'     => esc_html__( 'Choose Html Tag for title', 'bpd-booster-pack-divi' ),
      ],
      'text_align' => [
         'label'         => esc_html__( 'Text Align', 'bpd-booster-pack-divi' ),
         'type'          => 'text_align',
         'options'       => et_builder_get_text_orientation_options(),
         'toggle_slug'   => 'title',
      ],
      'sep_icon' => [
        'label'               => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
        'type'                => 'et_font_icon_select',
        'renderer'            => 'et_pb_get_font_icon_list',
        'renderer_with_field' => true,
        'toggle_slug'         => 'icon',
      ],
      'icon_position' => [
        'label'               => esc_html__( 'Icon Position', 'bpd-booster-pack-divi' ),
        'type'                => 'select',
        'option_category'     => 'basic_option',
        'options'             => [
            'aftertext'   => esc_html__( 'After Text', 'bpd-booster-pack-divi' ),
            'beforetext'  => esc_html__( 'Before Text', 'bpd-booster-pack-divi' ),
        ],
        'default'   		  => 'beforetext',
        'toggle_slug'         => 'icon',
        'description'         => esc_html__( 'Icon position', 'bpd-booster-pack-divi' ),
      ],
     'divider_style_1' => [
          'label'             => esc_html__( 'Style', 'bpd-booster-pack-divi' ),
          'type'              => 'select',
          'option_category'   => 'basic_option',
          'options'           => [
            'solid'     => esc_html__( 'Solid', 'bpd-booster-pack-divi' ),
            'double'    => esc_html__( 'Double', 'bpd-booster-pack-divi' ),
            'dotted'    => esc_html__( 'Dotted', 'bpd-booster-pack-divi' ),
            'dashed'    => esc_html__( 'Dashed', 'bpd-booster-pack-divi' ),
          ],
      'default'               => 'solid',
      'toggle_slug'           => 'divider_general',
      'description'           => esc_html__( 'Choose Divider Style', 'bpd-booster-pack-divi' ),
    ],
    'divider_weight' => [
          'label'             => esc_html__('Weight', 'bpd-booster-pack-divi'),
          'type'              => 'range',
          'default'           => '1px',
          'default_unit'      => 'px',
          'range_settings'    =>[
              'min'  => '0',
              'max'  => '20',
              'step' => '1',
          ],
          'option_category'   => 'basic_option',
          'description'       => esc_html__('Divider Weight.', 'bpd-booster-pack-divi'),
          'toggle_slug'       => 'divider_general',
    ],
    'icon_color' => [
          'label'             => esc_html__('Icon Color', 'bpd-booster-pack-divi'),
          'type'              => 'color-alpha',
          'custom_color'      => true,
          'tab_slug'          => 'advanced',
          'toggle_slug'       => 'icon_style',
    ],
    'icon_size' => [
          'label'           => esc_html__('Icon Size', 'bpd-booster-pack-divi'),
          'type'            => 'range',
          'default'         => '16px',
          'default_unit'    => 'px',
          'range_settings'  => [
              'min' => '0',
              'max' => '50',
              'step' => '1',
          ],
          'option_category' => 'basic_option',
          'description'     => esc_html__('Icon Size', 'bpd-booster-pack-divi'),
          'tab_slug'        => 'advanced',
          'toggle_slug'     => 'icon_style',
    ],
    'icon_rotate' => [
          'label'           => esc_html__('Icon Rotate', 'bpd-booster-pack-divi'),
          'type'            => 'range',
          'default'         => '0deg',
          'default_unit'    => 'deg',
          'range_settings'  => [
              'min'     => '0',
              'max'     => '360',
              'step'    => '1',
          ],
          'option_category' => 'basic_option',
          'description'     => esc_html__('Icon Rotate', 'bpd-booster-pack-divi'),
          'tab_slug'        => 'advanced',
          'toggle_slug'     => 'icon_style',
    ],
    'divider_color' => [
          'label'           => esc_html__('Divider Color', 'bpd-booster-pack-divi'),
          'type'            => 'color-alpha',
          'custom_color'    => true,
          'tab_slug'        => 'advanced',
          'toggle_slug'     => 'divider_style',
    ],
    'divider_width' => [
          'label'           => esc_html__('Divider Width', 'bpd-booster-pack-divi'),
          'type'            => 'range',
          'default'         => '100%',
          'default_unit'    => '%',
          'range_settings'  =>  [
              'min'  => '0',
              'max'  => '100%',
              'step' => '1',
          ],
          'option_category' => 'basic_option',
          'description'     => esc_html__('Divider Width', 'bpd-booster-pack-divi'),
          'tab_slug'        => 'advanced',
          'toggle_slug'     => 'divider_style',
    ],
    'divider_align' => [
      'label'               => esc_html__( 'Divider Align', 'bpd-booster-pack-divi' ),
      'type'                => 'text_align',
      'options'             => et_builder_get_text_orientation_options(),
      'tab_slug'            => 'advanced',
      'toggle_slug'         => 'divider_style',
    ],
  );
}
  public function get_advanced_fields_config() {
   $advanced_fields = array();
   $advanced_fields['fonts'] = [
		 'text'   => [
			 'label'            => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
			 'css'              => [
                 'main'         => "%%order_class%% .bpd-separator-title",
                 'important'    => 'all',
             ],
			 'hide_text_align'  =>true,
			 'toggle_slug'      => 'title_style'
		 ],
	 ];

   $advanced_fields['text']         = false;
   $advanced_fields['box_shadow']   = false;
   $advanced_fields['filters']      = false;
   return $advanced_fields;
  }
  public function get_settings_modal_toggles() {
	 return [
     'general' => [
       'toggles' => [
           'title' =>[
               'priority'   => 1,
               'title'      => 'Title',
           ],
           'icon' =>[
               'priority'   => 2,
               'title'      => 'Icon',
           ],
           'divider_general' =>[
               'priority'   => 3,
               'title'      => 'Divider',
           ],
       ]
     ],
	 'advanced' => [
		 'toggles' => [
			 'title_style'  =>[
				 'priority' => 7,
				 'title'    => 'Title',
			 ],
			 'icon_style'   => [
				 'priority' => 8,
				 'title'    => 'Icon',
			 ],
			 'divider_style'=>[
				 'priority' => 9,
				 'title'    => 'Divider',
			 ],
		 ]
     ]
     ];
	}

	function render_prop( $value = '', $field_name = '', $field_type = '', $render_slug = '') {
			$order_class = self::get_module_order_class( $render_slug );
			$output      = '';

			switch ( $field_type ) {
				case 'select_fonticon':
				$output = sprintf(
					'<span class="bpd-render-icon">%1$s</span>',
					esc_attr( et_pb_process_font_icon( $value ))
				);
				break;
			}
			return $output;
		}

  public function render( $attrs, $content = null, $render_slug ) {
		//  echo "<pre>";print_r($this->props);echo"</pre>";

		if ( '' !== $this->props['icon_color'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-render-icon',
						'declaration' => sprintf(
							'color : %1$s;',
							$this->props['icon_color']
						),
					) );
				}
		if ( '' !== $this->props['icon_size'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-render-icon',
						'declaration' => sprintf(
							'font-size : %1$s;',
							$this->props['icon_size']
						),
					) );
				}
		if ( '' !== $this->props['icon_rotate'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-separator-icon-inner',
						'declaration' => sprintf(
							'transform : rotate(%1$s);',
							$this->props['icon_rotate']
						),
					) );
				}
		if ( '' !== $this->props['divider_weight'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .wts-bpd-textseparator .bpd-sep-holder .bpd-sep-lines',
						'declaration' => sprintf(
							'border-top-width : %1$s;',
							$this->props['divider_weight']
						),
					) );
				}
		if ( '' !== $this->props['divider_style_1'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .wts-bpd-textseparator .bpd-sep-holder .bpd-sep-lines',
						'declaration' => sprintf(
							'border-top-style : %1$s;',
							$this->props['divider_style_1']
						),
					) );
				}
		$dividcolor = $this->props['divider_color'];
		if ( '' !== $dividcolor ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-sep-lines',
						'declaration' => sprintf(
							'border-top-color : %1$s;',
							$dividcolor
						),
					) );
				}
		$dividwidth =$this->props['divider_width'];
		if ( '' !== $dividwidth) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .wts-bpd-textseparator',
						'declaration' => sprintf(
							'width : %1$s;',
							$dividwidth
						),
					) );
				}
		$dividalign = $this->props['divider_align'];
		if ( '' !== $this->props['divider_align'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .wts-bpd-textseparator',
						'declaration' => sprintf(
							'float : %1$s;',
							$dividalign
						),
					) );
				}

		$iconclass="";
		if ( '' !== $this->props['sep_icon'] ) {
			$iconclass = "icon-yes";
			$iconclass .= " icon-".$this->props['icon_position'];

		}
		$titleyesno ="";
		if ( '' !== $this->props['title_sep'] ) {
			$titleyesno = "title-yes";
		}
		$output = sprintf('<div class="wts-bpd-textseparator sep-align-%1$s %2$s %3$s">',$this->props['text_align'],$iconclass,$titleyesno);
        $output .=  '<div class="bpd-sep-holder sep-left"><div class="bpd-sep-lines"></div></div>';

		$sepicon = $this->render_prop( esc_html($this->props['sep_icon']), 'sep_icon', 'select_fonticon', $render_slug );

        //$icon = sprintf('<span class="et-pb-icon">%1$s</span>',esc_attr( et_pb_process_font_icon( $this->props['sep_icon'] ) ));
		if('' !== $this->props['sep_icon'] && $this->props['icon_position'] == 'beforetext'){
		$output .= sprintf('<div class="bpd-separator-icon-wrapper"><div class="bpd-separator-icon-inner">
												%1$s</div></div>',$sepicon);
		}

		if ( '' !== $this->props['title_sep'] ) {
			$output .= sprintf('<%1$s class="bpd-separator-title">%2$s</%1$s>',$this->props['html_tag'],$this->props['title_sep']);
		}

		if('' !== $this->props['sep_icon'] && $this->props['icon_position'] == 'aftertext'){
		    $output .= sprintf('<div class="bpd-separator-icon-wrapper"><div class="bpd-separator-icon-inner">%1$s</div></div>',$sepicon);
		}
		$output .=  '<div class="bpd-sep-holder sep-right"><div class="bpd-sep-lines"></div>
				            	</div>
				        </div>';
		return $output;
  }
}

new BPD_TextSeparator;
