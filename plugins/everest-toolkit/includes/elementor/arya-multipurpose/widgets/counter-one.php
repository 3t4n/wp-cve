<?php

class Everest_Toolkit_Counter_One extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-counter-one';
	}

	public function get_title() {
		return esc_html__( 'Counter One', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-counter';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'counter_content_section',
			[
				'label' 		=> esc_html__( 'Counter Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Section Text', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);


		// Field - Starting Title Text
		$this->add_control(
			'starting_title_text',
			[
				'label' 		=> esc_html__( 'Starting Title Text', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type'	=> 'text',
				'label_block' 	=> true,
			]
		);


		// Field - Main Title Text
		$this->add_control(
			'main_title_text',
			[
				'label' 		=> esc_html__( 'Main Title Text', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type'	=> 'text',
				'label_block' 	=> true,
			]
		);


		// Field - Short Description Text
		$this->add_control(
			'description_text',
			[
				'label' 		=> esc_html__( 'Short Description Text', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXTAREA,
				'input_type'	=> 'text',
				'label_block' 	=> true,
			]
		);

		
		// Heading - First Count Separator
		$this->add_control(
			'first_count_heading',
			[
				'label' 		=> esc_html__( 'First - Count and Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		// Field - Title
		$this->add_control(
			'first_title',
			[
				'label' 		=> esc_html__( 'Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Field - Count
		$this->add_control(
			'first_count',
			[
				'label' 		=> esc_html__( 'Count', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Heading - Secound Count Separator
		$this->add_control(
			'second_count_heading',
			[
				'label' 		=> esc_html__( 'Second - Count and Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		// Field - Title
		$this->add_control(
			'second_title',
			[
				'label' 		=> esc_html__( 'Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Field - Count
		$this->add_control(
			'second_count',
			[
				'label' 		=> esc_html__( 'Count', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Heading - Third Count Separator
		$this->add_control(
			'third_count_heading',
			[
				'label' 		=> esc_html__( 'Third - Count and Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		// Field - Title
		$this->add_control(
			'third_title',
			[
				'label' 		=> esc_html__( 'Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Field - Count
		$this->add_control(
			'third_count',
			[
				'label' 		=> esc_html__( 'Count', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Heading - Image Separator
		$this->add_control(
			'image_heading',
			[
				'label' 		=> esc_html__( 'Section Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		


		// Field - Front Image
		$this->add_control(
			'section_image',
			[
				'label' 		=> esc_html__( 'Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::MEDIA,
			]
		);	

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$starting_text 		= $settings['starting_title_text'];
		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$first_title 		= $settings['first_title'];
		$first_count		= $settings['first_count'];
		$second_title 		= $settings['second_title'];
		$second_count		= $settings['second_count'];
		$third_title 		= $settings['third_title'];
		$third_count		= $settings['third_count'];
		$section_image		= $settings['section_image'];
		?>
		<section class="explore-section section-gap-full relative">
        	<div class="overlay overlay-bg"></div>
	        <div class="container">
	            <div class="row">
	                <div class="col-lg-6 explore-left">
	                	<?php
	                	if( !empty( $starting_text ) ) {
		                	?>
		                    <h3><?php echo esc_html( $starting_text ); ?></h3>
		                    <?php
		                }

		                if( !empty( $main_text ) ) {
		                	?>
	                    	<h1><?php echo esc_html( $main_text ); ?></h1>
	                    	<?php
	                    }

	                    if( !empty( $description_text ) ) {
	                    	?>
		                    <p><?php echo esc_html( $description_text ); ?></p>
		                    <?php
		                }
		                ?>
	                    <div class="d-flex counter-wrap">
	                    	<?php
	                    	if( !empty( $first_title ) && !empty( $first_count ) ) {
		                    	?>
		                        <div class="single-counter">
		                            <h2><?php echo esc_html( $first_count ); ?></h2>
		                            <p><?php echo esc_html( $first_title ); ?></p>
		                        </div>
		                        <?php
		                    }

		                    if( !empty( $second_title ) && !empty( $second_count ) ) {
		                    	?>
		                        <div class="single-counter">
		                            <h2><?php echo esc_html( $second_count ); ?></h2>
		                            <p><?php echo esc_html( $second_title ); ?></p>
		                        </div>
		                        <?php
		                    }

		                    if( !empty( $third_title ) && !empty( $third_count ) ) {
		                    	?>
		                        <div class="single-counter">
		                            <h2><?php echo esc_html( $third_count ); ?></h2>
		                            <p><?php echo esc_html( $third_title ); ?></p>
		                        </div>
		                        <?php
		                    }
		                    ?>
	                    </div>
	                </div>
	                <div class="col-lg-6 explore-right">
	                	<img src="<?php echo esc_url( $section_image['url'] ); ?>">
	                </div>
	            </div>
	        </div>
	    </section>
		<?php
	}

	public function _content_template() {}
}