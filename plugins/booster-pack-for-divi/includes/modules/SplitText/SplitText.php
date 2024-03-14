<?php

class BPD_SplitText extends ET_Builder_Module {

	public $slug       = 'bpd_splittext';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://webtechstreet.com',
		'author'     => 'WebTechStreet',
		'author_uri' => 'https://webtechstreet.com',
	);

	public function init() {
		$this->name = esc_html__( 'Split Text', 'bpd-booster-pack-divi' );
		//$this->icon = '%%67%%';
		$this->settings_modal_toggles = array(
			'advanced' => array(
                            'toggles' => array(
                                    'part1' => esc_html__( 'Part 1', 'bpd-booster-pack-divi' ),
                                    'part2' => esc_html__( 'Part 2', 'bpd-booster-pack-divi' ),
                                ),
                            ),
			'general'  => array(
                            'toggles' => array(
                                'general' => esc_html__( 'General', 'bpd-booster-pack-divi' ),
                            ),
                ),
		    );
	}

  public function get_fields() {
    return array(
      'text_align' => [
                'label'           => esc_html__( 'Text Align', 'bpd-booster-pack-divi' ),
                'type'            => 'text_align',
                'options'         => et_builder_get_text_orientation_options(),
                'toggle_slug'     => 'general',
      ],
      'split_mode' => [
                'label'           => esc_html__( 'Split Mode', 'bpd-booster-pack-divi' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => [
                   'letter'  => esc_html__( 'Letter', 'bpd-booster-pack-divi' ),
                   'word'    => esc_html__( 'Word', 'bpd-booster-pack-divi' ),
                ],
                'default'         => 'word',
                'toggle_slug'     => 'general',
                'description'     => esc_html__( 'Split Mode', 'bpd-booster-pack-divi' ),
      ],
      'split_count' => [
                'label'           => esc_html__( 'Split Count', 'bpd-booster-pack-divi' ),
                'type'            => 'text',
                'default'         => '2',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Split Count', 'bpd-booster-pack-divi' ),
                'toggle_slug'     => 'general',
      ],
      'html_tag' => [
                'label'           => esc_html__( 'Html Tag', 'bpd-booster-pack-divi' ),
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
                'default'         => 'h3',
                'toggle_slug'     => 'general',
                'description'     => esc_html__( 'Text Html Tag', 'bpd-booster-pack-divi' ),
      ],
      'main_text' => [
                'label'           => esc_html__( 'Text', 'bpd-booster-pack-divi' ),
                'type'            => 'textarea',
                'default'         => 'I Love Divi',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Text', 'bpd-booster-pack-divi' ),
                'toggle_slug'     => 'general',
      ],
      'input_margin_part1' => [
				'label'           => esc_html__( 'Input Margin', 'bpd-booster-pack-divi' ),
				'type'            => 'custom_margin',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'part1',
				'mobile_options'  => true,
				'responsive'      => true,
	    ],
      'input_padding_part1' => [
                'label'           => esc_html__( 'Input Padding', 'bpd-booster-pack-divi' ),
                'type'            => 'custom_padding',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'part1',
                'mobile_options'  => true,
                'responsive'      => true,
        ],
      'bgcolor_part1' => [
                'label' => esc_html__('Background Color', 'bpd-booster-pack-divi'),
                'type'            => 'color-alpha',
                'custom_color'    => true,
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'part1',
      ],
	  'input_margin_part2' => [
                'label'           => esc_html__( 'Margin', 'bpd-booster-pack-divi' ),
                'type'            => 'custom_margin',
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'part2',
                'mobile_options'  => true,
                'responsive'      => true,
      ],
      'input_padding_part2' => [
				'label'           => esc_html__( 'Padding', 'bpd-booster-pack-divi' ),
				'type'            => 'custom_padding',
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'part2',
				'mobile_options'  => true,
				'responsive'      => true,
      ],
      'bgcolor_part2' => [
                'label'           => esc_html__('Background Color', 'bpd-booster-pack-divi'),
                'type'            => 'color-alpha',
                'custom_color'    => true,
                'tab_slug'        => 'advanced',
                'toggle_slug'     => 'part2',
      ],
  );
}
  public function get_advanced_fields_config() {
   $advanced_fields = array();
   $advanced_fields['fonts']['part1'] = [
         'label'           => esc_html__( 'Part 1', 'bpd-booster-pack-divi' ),
         'css'             => [
                                 'main' => "%%order_class%% .bpd-st-split-text",
                                 'important' => 'all',
                               ],
         'hide_text_align' => true,
         'toggle_slug'     => 'part1'
	 ];
   $advanced_fields['borders']['part1'] = [
        'css'              => [
        'main'             => [
                                'border_radii' => "%%order_class%% .bpd-st-split-text",
                                'border_styles' => "%%order_class%% .bpd-st-split-text",
                            ]
        ],
        'tab_slug'         => 'advanced',
        'toggle_slug'      => 'part1',
	];
    $advanced_fields['fonts']['part2'] = [
         'label'           => esc_html__( 'Part 2', 'bpd-booster-pack-divi' ),
         'css'             => [
             'main' => "%%order_class%% .bpd-st-rest-text",
             'important' => 'all',
         ],
         'toggle_slug'     => 'part2'
 	 ];
    $advanced_fields['borders']['part2'] = [
        'css'             => [
            'main'    => [
                'border_radii' => "%%order_class%% .bpd-st-transform-text-title .bpd-st-rest-text",
                'border_styles' => "%%order_class%% .bpd-st-transform-text-title .bpd-st-rest-text",
            ],
         ],
        'hide_text_align' => true,
        'tab_slug'        => 'advanced',
        'toggle_slug'     => 'part2',
 	];

    $advanced_fields['text'] = false;
    $advanced_fields['box_shadow'] = false;
	$advanced_fields['filters'] = false;
	return $advanced_fields;
  }
  public function get_settings_modal_toggles() {
	 return array(
     'general' => [
           'toggles' => [
               'general'    =>[
                   'priority'   => 1,
                   'title'      => 'General',
               ],
           ]
     ],
	 'advanced' => [
		 'toggles' => [
                     'part1' =>[
                         'priority' => 7,
                         'title'    => 'Part 1',
                     ],
                     'part2' =>[
                         'priority' => 8,
                         'title'    => 'Part 2',
                     ],
         ]
	 ]
	);
	}
  public function render( $attrs, $content = null, $render_slug ) {

	echo "<pre>";print_r($this->props);echo"</pre>";
      if ( '' !== $this->props['text_align'] ) {
          ET_Builder_Element::set_style( $render_slug, array(
              'selector'    => '%%order_class%% .bpd-st-transform-text',
              'declaration' => sprintf(
                  'text-align : %1$s;',
                  $this->props['text_align']
              ),
          ) );
      }
      if ( '' !== $this->props['bgcolor_part1'] ) {
          ET_Builder_Element::set_style( $render_slug, array(
              'selector'    => '%%order_class%% .bpd-st-split-text',
              'declaration' => sprintf(
                  'background-color : %1$s;',
                  $this->props['bgcolor_part1']
              ),
          ) );
      }
      if ( '' !== $this->props['bgcolor_part2'] ) {
          ET_Builder_Element::set_style( $render_slug, array(
              'selector'    => '%%order_class%% .bpd-st-rest-text',
              'declaration' => sprintf(
                  'background-color : %1$s;',
                  $this->props['bgcolor_part2']
              ),
          ) );
      }
      if('' !== $this->props['border_width_all_part1'])
      {
          if('' == $this->props['border_style_all_part1']) {
              ET_Builder_Element::set_style($render_slug, array(
                  'selector' => '%%order_class%% .bpd-st-split-text',
                  'declaration' => 'border-style : solid;',
              ));
          }
      }
      if('' !== $this->props['border_width_all_part2'])
      {
          if('' == $this->props['border_style_all_part2']) {
              ET_Builder_Element::set_style($render_slug, array(
                  'selector' => '%%order_class%% .bpd-st-rest-text',
                  'declaration' => 'border-style : solid;',
              ));
          }
      }
      $marginarr = explode("|", $this->props['input_margin_part1']);
      for( $i=0;$i<4;$i++)
      {
        if($marginarr[$i]=="")
        {
            $marginarr[$i]="0px";
        }
      }

      $marginarrtablet = explode("|", $this->props['input_margin_part1_tablet']);
      for( $i=0;$i<4;$i++)
      {
          if($marginarrtablet[$i]=="")
          {
              $marginarrtablet[$i]="0px";
          }
      }

      $marginarrphone = explode("|", $this->props['input_margin_part1_phone']);
      for( $i=0;$i<4;$i++)
      {
          if($marginarrphone[$i]=="")
          {
              $marginarrphone[$i]="0px";
          }
      }

      $margin = implode(" ",$marginarr);
      $margin_tablet = implode(" ",$marginarrtablet);
      $margin_mobile = implode(" ",$marginarrphone);

      if ( '' !== $this->props['input_margin_part1'] ) {
          $part1_margin_responsive_active = et_pb_get_responsive_status( $this->props['input_margin_part1_last_edited'] );

          $part1_margin_values = array(
              'desktop' => $margin,
              'tablet'  => $part1_margin_responsive_active ? $margin_tablet : '',
              'phone'   => $part1_margin_responsive_active ? $margin_mobile : '',
          );

          et_pb_generate_responsive_css( $part1_margin_values, '%%order_class%% .bpd-st-split-text', 'margin', $render_slug );
      }

      $paddingarr = explode("|", $this->props['input_padding_part1']);
      for( $i=0;$i<4;$i++)
      {
          if($paddingarr[$i]=="")
          {
              $paddingarr[$i]="0px";
          }
      }
      //print_r($paddingarr);
      //echo "<br/>";
      $paddingarrtablet = explode("|", $this->props['input_padding_part1_tablet']);
      for( $i=0;$i<4;$i++)
      {
          if($paddingarrtablet[$i]=="")
          {
              $paddingarrtablet[$i]="0px";
          }
      }

      $paddingarrphone = explode("|", $this->props['input_padding_part1_phone']);
      for( $i=0;$i<4;$i++)
      {
          if($paddingarrphone[$i]=="")
          {
              $paddingarrphone[$i]="0px";
          }
      }

      $padding = implode(" ",$paddingarr);
      $padding_tablet = implode(" ",$paddingarrtablet);
      $padding_mobile = implode(" ",$paddingarrphone);

      if ( '' !== $this->props['input_margin_part1'] ) {
          $part1_padding_responsive_active = et_pb_get_responsive_status( $this->props['input_padding_part1_last_edited'] );

          $part1_padding_values = array(
              'desktop' => $padding,
              'tablet'  => $part1_padding_responsive_active ? $padding_tablet : '',
              'phone'   => $part1_padding_responsive_active ? $padding_mobile : '',
          );

          et_pb_generate_responsive_css( $part1_padding_values, '%%order_class%% .bpd-st-split-text', 'padding', $render_slug );
      }

      $marginarr2 = explode("|", $this->props['input_margin_part2']);
      for( $i=0;$i<4;$i++)
      {
          if($marginarr2[$i]=="")
          {
              $marginarr2[$i]="0px";
          }
      }

      $marginarrtablet2 = explode("|", $this->props['input_margin_part2_tablet']);
      for( $i=0;$i<4;$i++)
      {
          if($marginarrtablet2[$i]=="")
          {
              $marginarrtablet2[$i]="0px";
          }
      }

      $marginarrphone2 = explode("|", $this->props['input_margin_part2_phone']);
      for( $i=0;$i<4;$i++)
      {
          if($marginarrphone2[$i]=="")
          {
              $marginarrphone2[$i]="0px";
          }
      }

      $margin2 = implode(" ",$marginarr2);
      $margin_tablet2 = implode(" ",$marginarrtablet2);
      $margin_mobile2 = implode(" ",$marginarrphone2);

      if ( '' !== $this->props['input_margin_part2'] ) {
          $part2_margin_responsive_active = et_pb_get_responsive_status( $this->props['input_margin_part2_last_edited'] );

          $part2_margin_values = array(
              'desktop' => $margin2,
              'tablet'  => $part2_margin_responsive_active ? $margin_tablet2 : '',
              'phone'   => $part2_margin_responsive_active ? $margin_mobile2 : '',
          );

          et_pb_generate_responsive_css( $part2_margin_values, '%%order_class%% .bpd-st-rest-text', 'margin', $render_slug );
      }

      $paddingarr2 = explode("|", $this->props['input_padding_part2']);
      for( $i=0;$i<4;$i++)
      {
          if($paddingarr2[$i]=="")
          {
              $paddingarr2[$i]="0px";
          }
      }

      $paddingarrtablet2 = explode("|", $this->props['input_padding_part2_tablet']);
      for( $i=0;$i<4;$i++)
      {
          if($paddingarrtablet2[$i]=="")
          {
              $paddingarrtablet2[$i]="0px";
          }
      }

      $paddingarrphone2 = explode("|", $this->props['input_padding_part2_phone']);
      for( $i=0;$i<4;$i++)
      {
          if($paddingarrphone2[$i]=="")
          {
              $paddingarrphone2[$i]="0px";
          }
      }

      $padding2 = implode(" ",$paddingarr2);
      $padding_tablet2 = implode(" ",$paddingarrtablet2);
      $padding_mobile2 = implode(" ",$paddingarrphone2);

      if ( '' !== $this->props['input_margin_part2'] ) {
          $part2_padding_responsive_active = et_pb_get_responsive_status( $this->props['input_padding_part2_last_edited'] );

          $part2_padding_values = array(
              'desktop' => $padding2,
              'tablet'  => $part2_padding_responsive_active ? $padding_tablet2 : '',
              'phone'   => $part2_padding_responsive_active ? $padding_mobile2 : '',
          );

          et_pb_generate_responsive_css( $part2_padding_values, '%%order_class%% .bpd-st-rest-text', 'padding', $render_slug );
      }

	$output = sprintf('<div class="bpd-st-transform-text-wrapper waiting">');
	$output .= '<div class="bpd-st-transform-text">';
	if('word' !== $this->props['split_mode'])
	{
		$output .= sprintf('<%1$s class="bpd-st-transform-text-title">%2$s</%1$s>',	$this->props['html_tag'],'<div class="bpd-st-split-text">'.substr($this->props['main_text'], 0, $this->props['split_count']).'</div><div class="bpd-st-rest-text">'.substr($this->props['main_text'], $this->props['split_count'], strlen($this->props['main_text'])- $this->props['split_count']).'</div>');
	}
	else {
		$arr = explode(" ", $this->props['main_text']);
		if(count($arr) <= $this->props['split_count']){
			$split_text = '<div class="bpd-st-split-text bpd-st-full-text">'.$this->props['main_text'].'</div>';
			$output .= sprintf('<%1$s class="bpd-st-transform-text-title">%2$s</%1$s>',$this->props['html_tag'], $split_text) ;
		}
		else{
			$split_text = '<div class="bpd-st-split-text">'.implode(" ", array_slice($arr, 0, $this->props['split_count'])) .'&nbsp;</div>';
			$rest_text = '<div class="bpd-st-rest-text" >'. implode(" ", array_slice($arr, $this->props['split_count'], count($arr))) . '</div>';
			$output .= sprintf('<%1$s class="bpd-st-transform-text-title">%2$s</%1$s>', $this->props['html_tag'], $split_text.$rest_text);
		}
  }
	$output .= '</div></div>';
	return $output;
}
}

//new BPD_SplitText;
