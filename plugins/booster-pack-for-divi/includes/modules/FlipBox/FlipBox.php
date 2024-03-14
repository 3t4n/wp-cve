<?php

class BPD_FlipBox extends ET_Builder_Module {

	public $slug       = 'bpd_flipbox';
	public $vb_support = 'on';

	protected $module_credits = array(
		'module_uri' => 'https://webtechstreet.com',
		'author'     => 'WebTechStreet',
		'author_uri' => 'https://webtechstreet.com',
	);

	public function init() {
		$this->name = esc_html__( 'Flip Box', 'bpd-booster-pack-divi' );

		$this->settings_modal_toggles = array(
			'advanced' => array(
				'toggles' => array(
					'fboxstyle' => esc_html__( 'Front Box', 'bpd-booster-pack-divi' ),
					'bboxstyle'=> esc_html__( 'Back Box', 'bpd-booster-pack-divi' ),
					'fcontentstyle'=> esc_html__( 'Front Content', 'bpd-booster-pack-divi' ),
					'general_style'=> esc_html__( 'General', 'bpd-booster-pack-divi' ),
					'action_button_style'=> esc_html__( 'Action Button', 'bpd-booster-pack-divi' ),
				),
			),
			'general'  => array(
				'toggles' => array(
					'front_box' => esc_html__( 'Front Box', 'bpd-booster-pack-divi' ),
					'back_box' => esc_html__( 'Back Box', 'bpd-booster-pack-divi' ),
					'action_button'=> esc_html__( 'Action Button', 'bpd-booster-pack-divi' ),
				),
			),
		);
	}

	public function get_fields() {
		return array(
			'f_icon' => array(
				'label'               => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'toggle_slug'     => 'front_box',
			),
			'f_title' => array(
				'label'           => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
				'type'            => 'text',
				'default'					=> 'Front Title',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text entered here will appear as title.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'front_box',
			),
			'f_html_tag' => array(
				'label'           => esc_html__( 'Title Html Tag', 'bpd-booster-pack-divi' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'h1' => esc_html__( 'H1', 'bpd-booster-pack-divi' ),
					'h2' => esc_html__( 'H2', 'bpd-booster-pack-divi' ),
					'h3' => esc_html__( 'H3', 'bpd-booster-pack-divi' ),
					'h4' => esc_html__( 'H4', 'bpd-booster-pack-divi' ),
					'h5' => esc_html__( 'H5', 'bpd-booster-pack-divi' ),
					'h6' => esc_html__( 'H6', 'bpd-booster-pack-divi' ),
				),
				'default'					=> 'h2',
				'toggle_slug'     => 'front_box',
				'description'     => esc_html__( 'Choose Html Tag for title', 'bpd-booster-pack-divi' ),
			),
			'f_content' => array(
				'label'           => esc_html__( 'Content', 'bpd-booster-pack-divi' ),
				'type'            => 'textarea',
				'default'					=> 'This is content Area',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'front_box',
			),

			'b_icon' => array(
				'label'               => esc_html__( 'Icon', 'bpd-booster-pack-divi' ),
				'type'                => 'et_font_icon_select',
				'renderer'            => 'et_pb_get_font_icon_list',
				'renderer_with_field' => true,
				'toggle_slug'     => 'back_box',
			),
			'b_title' => array(
				'label'           => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
				'type'            => 'text',
				'default'					=> 'Back Title',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Text entered here will appear as title.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'back_box',
			),
			'b_html_tag' => array(
				'label'           => esc_html__( 'Title Html Tag', 'bpd-booster-pack-divi' ),
				'type'            => 'select',
				'option_category' => 'basic_option',
				'options'         => array(
					'h1' => esc_html__( 'H1', 'bpd-booster-pack-divi' ),
					'h2' => esc_html__( 'H2', 'bpd-booster-pack-divi' ),
					'h3' => esc_html__( 'H3', 'bpd-booster-pack-divi' ),
					'h4' => esc_html__( 'H4', 'bpd-booster-pack-divi' ),
					'h5' => esc_html__( 'H5', 'bpd-booster-pack-divi' ),
					'h6' => esc_html__( 'H6', 'bpd-booster-pack-divi' ),
				),
				'default'					=> 'h3',
				'toggle_slug'     => 'back_box',
				'description'     => esc_html__( 'Choose HTML Titlte Tag', 'bpd-booster-pack-divi' ),
			),
			'b_content' => array(
				'label'           => esc_html__( 'Content', 'bpd-booster-pack-divi' ),
				'type'            => 'textarea',
				'default'					=> 'This is content area.',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Content entered here will appear inside the module.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'back_box',
			),
			'action_button_text' => array(
				'label'           => esc_html__( 'Button Text', 'bpd-booster-pack-divi' ),
				'type'            => 'text',
				'default'					=> 'Buy Now',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Action Button Text.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'action_button',
			),
			'action_button_url' => array(
				'label'           => esc_html__( 'Url', 'bpd-booster-pack-divi' ),
				'type'            => 'text',
				'option_category' => 'basic_option',
				'description'     => esc_html__( 'Action Button Url.', 'bpd-booster-pack-divi' ),
				'toggle_slug'     => 'action_button',
			),
			'flip_style' => array(
				'label'           => esc_html__( 'Animation Style', 'bpd-booster-pack-divi' ),
				'type'            => 'select',
				//'option_category' => 'basic_option',
				'options'         => array(
					'bpd-fb-animate-vertical' => esc_html__( 'Flip Vertical', 'bpd-booster-pack-divi' ),
					'bpd-fb-animate-horizontal' => esc_html__( 'Flip Horizontal', 'bpd-booster-pack-divi' ),
					'bpd-fb-animate-fade' => esc_html__( 'Fade', 'bpd-booster-pack-divi' ),
				),
				'default'					=> 'bpd-fb-animate-horizontal',
				'tab_slug'				=> 'advanced',
				'toggle_slug'     => 'general_style',
				'description'     => esc_html__( 'Choose Animation Style', 'bpd-booster-pack-divi' ),
			),
			'flipbox_height' => array(
						'label' => esc_html__('Box Height', 'bpd-booster-pack-divi'),
						'type' => 'number',
						'default' => '250',
						'option_category' => 'basic_option',
						'description' => esc_html__('Box Height', 'bpd-booster-pack-divi'),
						'tab_slug' => 'advanced',
						'toggle_slug' => 'general_style',
					),
			'front_bgcolor' => array(
					'label'           => esc_html__( 'Background Color', 'bpd-booster-pack-divi' ),
					'type'            => 'color',
					'tab_slug'				=> 'advanced',
					'toggle_slug'     => 'fboxstyle',
				),
			'front_icon_color' => array(
					'label'           => esc_html__( 'Icon Color', 'bpd-booster-pack-divi' ),
					'type'            => 'color',
					'tab_slug'				=> 'advanced',
					'toggle_slug'     => 'fboxstyle',
				),
			'back_bgcolor' => array(
					'label'           => esc_html__( 'Background Color', 'bpd-booster-pack-divi' ),
					'type'            => 'color',
					'tab_slug'				=> 'advanced',
					'toggle_slug'     => 'bboxstyle',
				),
			'back_icon_color' => array(
					'label'           => esc_html__( 'Icon Color', 'bpd-booster-pack-divi' ),
					'type'            => 'color',
					'tab_slug'				=> 'advanced',
					'toggle_slug'     => 'bboxstyle',
				),
			'action_bgcolor' => array(
					'label'           => esc_html__( 'Background Color', 'bpd-booster-pack-divi' ),
					'type'            => 'color',
					'tab_slug'				=> 'advanced',
					'toggle_slug'     => 'action_button_style',
				),
		);
	}

	public function get_advanced_fields_config() {
		$advanced_fields = array();

		$advanced_fields['fonts'] = array(
			'text'   => array(
				'label'    => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
				'css'      => array(
					'main' => "%%order_class%% .front-icon-title",
					'important' => 'all',
				),
				'toggle_slug' => 'fboxstyle',
			),
		);
		$advanced_fields['fonts']['content'] = array(
			'label'    => esc_html__( 'Content', 'bpd-booster-pack-divi' ),
			'css'      => array(
				'main' => "%%order_class%% .bpd-flip-box-front .flipbox-content p",
			),
			'toggle_slug' => 'fboxstyle',
		);

		$advanced_fields['fonts']['bbox'] = array(
			'label'    => esc_html__( 'Title', 'bpd-booster-pack-divi' ),
			'css'      => array(
				'main' => "%%order_class%% .back-icon-title",
			),
			'toggle_slug' => 'bboxstyle',
		);
		$advanced_fields['fonts']['content_back'] = array(
			'label'    => esc_html__( 'Content', 'bpd-booster-pack-divi' ),
			'css'      => array(
				'main' => "%%order_class%% .bpd-flip-box-back .flipbox-content p",
			),
			'toggle_slug' => 'bboxstyle',
		);
		$advanced_fields['fonts']['action_content'] = array(
			'label'    => esc_html__( 'Text', 'bpd-booster-pack-divi' ),
			'css'      => array(
				'main' => "%%order_class%% .bpd-fb-button span",
			),
			'toggle_slug' => 'action_button_style',
		);
		$advanced_fields['border']= array(
			'label'    => esc_html__( 'Flip Box', 'bpd-booster-pack-divi' ),
			'css'      => array(
				'main' => "%%order_class%% .bpd-flip-box-wrapper",
			),
		);
		$advanced_fields['background'] = false;
		$advanced_fields['margin_padding'] = false;
		$advanced_fields['filter'] = false;
		$advanced_fields['text'] = false;

		return $advanced_fields;
	}

	public function get_settings_modal_toggles() {
		return array(
		'general' => array(
			'toggles' => array(
			'front_box' =>array(
			'priority' => 1,
			'title' => 'Front Box',
			),
			'back_box' =>array(
			'priority' => 2,
			'title' => 'Back Box',
			),
			'action_button' =>array(
			'priority' => 3,
			'title' => 'Action Button',
			),

			)
		),
		'advanced' => array(
			'toggles' => array(
				'general_style' =>array(
					'priority' => 4,
					'title' => 'General',
				),
				'fboxstyle' =>array(
					'priority' => 5,
					'title' => 'Front Box',
				),
				'fcontentstyle' =>array(
					'priority' => 8,
					'title' => 'Front Box Content',
				),
				'bboxstyle' =>array(
					'priority' => 6,
					'title' => 'Back Box',
				),
				'action_button_style' =>array(
					'priority' => 7,
					'title' => 'Action Button',
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
					'<span style="font-family: ETmodules!important; font-size: 40px;">%1$s</span>',
					esc_attr( et_pb_process_font_icon( $value ))
				);
				break;
			}
			return $output;
		}

	public function render( $attrs, $content = null, $render_slug ) {
		//echo "<pre>";print_r($this->props);echo"</pre>";
		$ficon =$this->render_prop( esc_html($this->props['f_icon']), 'f_icon', 'select_fonticon', $render_slug );
		$bicon =$this->render_prop( esc_html($this->props['b_icon']), 'b_icon', 'select_fonticon', $render_slug );
		$buttontext = $this->props['action_button_text'];
		$buttonurl =$this->props['action_button_url'];


		if( '<span style="font-family: ETmodules!important; font-size: 40px;"></span>' !== $ficon)
		{
			$ficon = '<div class="icon-wrapper">'.$ficon.'</div>';
		}
		else {
			$ficon="";
		}
		if( '<span style="font-family: ETmodules!important; font-size: 40px;"></span>' !== $bicon)
		{
			$bicon = '<div class="icon-wrapper">'.$bicon.'</div>';
		}
		else {
			$bicon ="";
		}

		if('' !== $buttontext )
		{
			if('' !== $buttonurl )
			{
				$buttonurl = 'href="'.$buttonurl.'"';
			}
			$button = sprintf('<div class="bpd-fb-button-wrapper">
                                <a class="bpd-fb-button" %2$s>
                                      <span>%1$s</span>
                                </a>
                            </div>',$buttontext,$buttonurl);
		}

		if ( '' !== $this->props['front_bgcolor'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-flip-box-front',
						'declaration' => sprintf(
							'background-color : %1$s;',
							$this->props['front_bgcolor']
						),
					) );
				}
		if ( '' !== $this->props['front_icon_color'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-flip-box-front span',
						'declaration' => sprintf(
							'color : %1$s;',
							$this->props['front_icon_color']
						),
					) );
				}
		if ( '' !== $this->props['back_bgcolor'] ) {
				ET_Builder_Element::set_style( $render_slug, array(
					'selector'    => '%%order_class%% .bpd-flip-box-back',
					'declaration' => sprintf(
						'background-color : %1$s;',
						$this->props['back_bgcolor']
					),
				) );
			}
		if ( '' !== $this->props['back_icon_color'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-flip-box-back span',
						'declaration' => sprintf(
							'color : %1$s;',
							$this->props['back_icon_color']
						),
					) );
				}
		if ( '' !== $this->props['action_bgcolor'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-fb-button',
						'declaration' => sprintf(
							'background-color : %1$s;',
							$this->props['action_bgcolor']
						),
					) );
				}
		if ( '' !== $this->props['flipbox_height'] ) {
					ET_Builder_Element::set_style( $render_slug, array(
						'selector'    => '%%order_class%% .bpd-flip-box-inner',
						'declaration' => sprintf(
							'height : %1$spx;',
							$this->props['flipbox_height']
						),
					) );
				}

		$output = sprintf( '<div class="bpd-flip-box-wrapper %5$s">
				<div class="bpd-flip-box-inner">
						<div class="bpd-flip-box-front">
								<div class="flipbox-content">
										%1$s
										<%2$s class="front-icon-title">%3$s</%2$s> <p> %4$s </p> </div></div>'

								, $ficon,
							 $this->props['f_html_tag'], $this->props['f_title'], $this->props['f_content'] , $this->props['flip_style'] );

	 $output .= sprintf( '<div class="bpd-flip-box-back">
	 						<div class="flipbox-content">
	 								%1$s
	 								<%2$s class="back-icon-title">%3$s</%2$s> <p>%4$s</p> %5$s </div></div></div></div>'
	 						, $bicon,
	 					 $this->props['b_html_tag'], $this->props['b_title'], $this->props['b_content'],$button );
		return $output;
	}

}

new BPD_FlipBox;
