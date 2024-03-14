<?php

//START ELEMENT match
class nd_spt_match_element extends \Elementor\Widget_Base {

	public function get_name() { return 'match'; }
	public function get_title() { return __( 'Match', 'nd-sports-booking' ); }
	public function get_icon() { return 'fa fa-futbol'; }
	public function get_categories() { return [ 'nd-sports-booking' ]; }

	
	/*START CONTROLS*/
	protected function _register_controls() {

		
		/*Create Tab*/
		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Options', 'nd-sports-booking' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
	      'match_layout',
	      [
	        'label' => __( 'Layout', 'nd-sports-booking' ),
	        'type' => \Elementor\Controls_Manager::SELECT,
	        'default' => 'layout-1',
	        'options' => [
	          'layout-1' => __( 'Layout 1', 'nd-elements' ),
	        ],
	      ]
	    );


		$this->add_control(
			'hr1',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);



	    $this->add_control(
			'match_player1img',
			[
				'label' => __( 'Player 1 Image', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'match_player1name',
			[
				'label' => __( 'Player 1 Name', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Player 1', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert player name', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'match_player1label',
			[
				'label' => __( 'Player 1 Label', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'PL', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert player label', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'match_player1result',
			[
				'label' => __( 'Player 1 Result', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '1', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert player result', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'hr2',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'match_player2img',
			[
				'label' => __( 'Player 2 Image', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);

		$this->add_control(
			'match_player2name',
			[
				'label' => __( 'Player 2 Name', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Player 2', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert player name', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'match_player2label',
			[
				'label' => __( 'Player 2 Label', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'PL', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert player label', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'match_player2result',
			[
				'label' => __( 'Player 2 Result', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '2', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert player result', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'hr3',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'match_finalresult',
			[
				'label' => __( 'Final Result', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( '0 : 0', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert final result', 'nd-sports-booking' ),
			]
		);

		$this->add_control(
			'match_finalresultlabel',
			[
				'label' => __( 'Final Result Label', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'NEXT MATCH', 'nd-sports-booking' ),
				'placeholder' => __( 'Insert result label', 'nd-sports-booking' ),
			]
		);

		$this->end_controls_section();


		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Style', 'nd-sports-booking' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_control(
			'match_color',
			[
				'label' => __( 'Color', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .nd_spt_match_component_col2 h6' => 'background-color: {{VALUE}} !important',
				],
			]
		);


		$this->end_controls_section();

	}


 
	/*START RENDER*/
	protected function render() {

		$nd_spt_settings = $this->get_settings_for_display();
		
		//layout
		$match_layout = $nd_spt_settings['match_layout'];
		if ($match_layout == '') { $match_layout = "layout-1"; }

		$match_player1img = $nd_spt_settings['match_player1img']['url'];
		$match_player1name = $nd_spt_settings['match_player1name'];
		$match_player1label = $nd_spt_settings['match_player1label'];
		$match_player1result = $nd_spt_settings['match_player1result'];

		$match_player2img = $nd_spt_settings['match_player2img']['url'];
		$match_player2name = $nd_spt_settings['match_player2name'];
		$match_player2label = $nd_spt_settings['match_player2label'];
		$match_player2result = $nd_spt_settings['match_player2result'];

		$match_finalresult = $nd_spt_settings['match_finalresult'];
		$match_finalresultlabel = $nd_spt_settings['match_finalresultlabel'];

		//recover color
		$nd_spt_customizer_color_1 = get_option( 'nd_spt_customizer_color_1', '#c0a58a' );

		//START 
		$nd_spt_result = '';

		//check with realpath
  		$nd_spt_layout_selected = dirname( __FILE__ ).'/layout/'.$match_layout.'.php';
  		include realpath($nd_spt_layout_selected);


  		$nd_spt_allowed_html = [
		    'div'      => [ 
			  'class' => [],
			],
			'img'      => [
			  'alt' => [],
			  'class' => [],
			  'src' => [],
			],
			'h5'      => [
			  'class' => [],
			],
			'h1'      => [
			  'class' => [],
			],
			'h6'      => [
			  'class' => [],
			  'style' => [],
			],
			'p'      => [
			  'class' => [],
			],
			'span'      => [
			  'class' => [],
			],
		];

		echo wp_kses( $nd_spt_result, $nd_spt_allowed_html );
		//END

	}




}