<?php

//START ELEMENT bookingform
class nd_spt_bookingform_element extends \Elementor\Widget_Base {

	public function get_name() { return 'bookingform'; }
	public function get_title() { return __( 'Booking Form', 'nd-sports-booking' ); }
	public function get_icon() { return 'fa fa-calendar-alt'; }
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
	      'bookingform_layout',
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
			'bookingform_action',
			[
				'label' => __( 'Action Url', 'nd-sports-booking' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'nd-sports-booking' ),
				'show_external' => false,
				'default' => [
					'url' => '',
					'is_external' => false,
					'nofollow' => false,
				],
			]
		);

		$this->end_controls_section();

	}


 
	/*START RENDER*/
	protected function render() {

		$nd_spt_settings = $this->get_settings_for_display();
		
		//options 
		$bookingform_layout = $nd_spt_settings['bookingform_layout'];
		$bookingform_action = $nd_spt_settings['bookingform_action']['url'];

		//default
		if ($bookingform_layout == '') { $bookingform_layout = "layout-1"; }

		//get variables
		$nd_spt_max_guests = get_option('nd_spt_max_players',2);
		$nd_spt_image = esc_url(plugins_url('layout/arrow-grey.png', __FILE__ ));

		//date options
		$nd_spt_date_number_from_front = date('d');
		$nd_spt_date_month_from_front = date('M');
		$nd_spt_date_month_from_front = date_i18n('M');

		//script for calendar
		wp_enqueue_script('jquery-ui-datepicker');
		wp_enqueue_style('jquery-ui-datepicker-'.$bookingform_layout.'-css', esc_url(plugins_url('css/datepicker-', __FILE__ )).''.$bookingform_layout.'.css' );


		$nd_spt_result = '';

		//check with realpath
  		$nd_spt_layout_selected = dirname( __FILE__ ).'/layout/'.$bookingform_layout.'.php';
  		include realpath($nd_spt_layout_selected);

  		$nd_spt_allowed_html = [
		    'div'      => [ 
			  'class' => [],
			  'id' => [],
			],  
			'form'      => [ 
			  'action' => [],
			  'method' => [],
			],
			'p'      => [
			  'class' => [],
			],
			'h4'      => [
			  'class' => [],
			],
			'span'      => [
			  'id' => [],
			  'class' => [],
			],
			'img'      => [
			  'style' => [],
			  'class' => [],
			  'alt' => [],
			  'width' => [],
			  'src' => [],
			],
			'input'      => [
			  'type' => [],
			  'id' => [],
			  'class' => [],
			  'value' => [],
			  'name' => [],
			  'min' => [],
			  'style' => [],
			],
		];

		echo wp_kses( $nd_spt_result, $nd_spt_allowed_html );
		//END

	}




}