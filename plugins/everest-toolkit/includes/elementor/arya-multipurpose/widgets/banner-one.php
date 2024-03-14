<?php

class Everest_Toolkit_Banner_One extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-banner-one';
	}

	public function get_title() {
		return esc_html__( 'Banner One', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-image-rollover';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'banner_content_section',
			[
				'label' 		=> esc_html__( 'Banner Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Banner Text', 'everest-toolkit' ),
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

		
		// Heading - Button Separator
		$this->add_control(
			'button_heading',
			[
				'label' 		=> esc_html__( 'Button Link and Text', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		// Field - Button Title
		$this->add_control(
			'button_title',
			[
				'label' 		=> esc_html__( 'Button Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Field - Button Link
		$this->add_control(
			'button_link',
			[
				'label' 		=> esc_html__( 'Button Link', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'url',
				'label_block' 	=> true,
			]
		);

		// Heading - Image Separator
		$this->add_control(
			'image_heading',
			[
				'label' 		=> esc_html__( 'Front Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		


		// Field - Front Image
		$this->add_control(
			'front_image',
			[
				'label' 		=> esc_html__( 'Front Image', 'everest-toolkit' ),
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
		$button_title 		= $settings['button_title'];
		$button_link		= $settings['button_link'];
		$front_image		= $settings['front_image'];
		?>
		<section class="banner-section relative section-gap-full" id="banner-section">
	        <div class="container">
	            <div class="row align-items-center">
	                <div class="col-md-6 banner-left">		                	
	                    <h1>
	                    	<?php 
	                    	if( !empty( $starting_text ) ) { 
	                    		echo esc_html( $starting_text ); 
	                    		?> 
	                    		<br>
	                    		<?php 
	                    	} 
	                    	?> 
	                    	<?php 
	                    	if( !empty( $main_text ) ) {
	                    		?>
	                    		<span><?php echo esc_html( $main_text ); ?></span>
	                    		<?php
	                    	}
	                    	?>
	                    </h1>
	                    <?php
	                    if( !empty( $description_text ) ) {
	                    	?>
	                    	<p><?php echo esc_html( $description_text ); ?></p>
	                    	<?php
	                    }

	                    if( !empty( $button_title ) && !empty( $button_link ) ) {
	                    	?>
	                    	<a class="video-btn primary-btn" href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_title ); ?></a>
	                    	<?php
	                    }
	                    ?>
	                </div>
	                <?php
	                if( !empty( $front_image ) ) {
		                ?>
		                <div class="col-md-6 banner-right text-center">
		                    <img class="img-fluid" src="<?php echo esc_url( $front_image['url'] ); ?>">
		                </div>
		                <?php
		            }
		            ?>
	            </div>
	        </div>
	    </section><!-- #banner-section.banner-section.relative.section-gap-full -->
		<?php
	}

	public function _content_template() {}
}