<?php

class Everest_Toolkit_Testimonial extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-testimonial';
	}

	public function get_title() {
		return esc_html__( 'Testimonial', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-testimonial-carousel';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'testimonial_content_section',
			[
				'label' 		=> esc_html__( 'Testimonial Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Testimonial Titles', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
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
				'label' 		=> esc_html__( 'Section Button Link and Text', 'everest-toolkit' ),
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

		// Heading - Team Members List
		$this->add_control(
			'team_heading',
			[
				'label' 		=> esc_html__( 'Testimonials', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);	

		$clients_list = new \Elementor\Repeater();

		// Field - Clients Name
		$clients_list->add_control(
			'client_name',
			[
				'label' 		=> esc_html__( 'Client Name', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		// Field - Client's Status
		$clients_list->add_control(
			'client_status',
			[
				'label' 		=> esc_html__( 'Client Status', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		// Field - Client's Testimony
		$clients_list->add_control(
			'client_testimony',
			[
				'label' 		=> esc_html__( 'Client Testimony', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXTAREA,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		// Field - Client's Image
		$clients_list->add_control(
			'client_image',
			[
				'label' 		=> esc_html__( 'Client Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::MEDIA,
			]
		);
		

		$this->add_control(
			'clients_list',
			[
				'label' 		=> esc_html__( 'Client List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::REPEATER,
				'fields' 		=> $clients_list->get_controls(),
				'title_field' 	=> '{{{ client_name }}}',
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$clients_list 		= $settings['clients_list'];
		$button_title 		= $settings['button_title'];
		$button_link		= $settings['button_link'];
		?>
		<section class="testimonial-section section-gap-full" id="testimonial-section">
	        <div class="container">
	            <div class="row align-items-center">
	                <div class="col-lg-4 testimonial-left">
	                	<?php
		            	if( !empty( $main_text ) ) {
		            		?>
		            		<h2><?php echo esc_html( $main_text ); ?></h2>
		            		<?php
		            	}

		            	if( !empty( $description_text ) ) {
		            		?>
		            		<p><?php echo esc_html( $description_text ); ?></p>
		            		<?php
		            	}
		            	?>
		            	<?php
			            if( !empty( $button_title ) && !empty( $button_link ) ) {
			            	?>
				            <a href="<?php echo esc_url( $button_link ); ?>" class="primary-btn"><?php echo esc_html( $button_title ); ?><span class="ti-arrow-right"></span></a>
			            	<?php
			            }
			            ?>
	                </div>
	                <?php
	                if( !empty( $clients_list ) ) {
		                ?>
		                <div class="col-lg-8 testimonial-right">
		                    <div class="testimonial-carousel  owl-carousel" id="testimonial-carousel">
		                    	<?php
		                    	foreach( $clients_list as $client ) {
			                    	?>
			                        <div class="single-testimonial item">
			                        	<?php
			                        	if( !empty( $client['client_testimony'] ) ) {
				                        	?>
				                            <p><?php echo esc_html( $client['client_testimony'] ); ?></p>
				                            <?php
				                        }
				                        ?>
			                            <div class="user-details d-flex flex-row align-items-center">
			                            	<?php
			                            	if( !empty( $client['client_image'] ) ) {
			                            		?>
			                            		<div class="img-wrap">
				                                    <?php echo wp_get_attachment_image( $client['client_image']['id'], 'arya-multipurpose-thumbnail-four' ); ?>
				                                </div>
			                            		<?php
			                            	}

			                            	if( !empty( $client['client_name'] ) || !empty( $client['client_status'] ) ) {
			                            		?>
			                            		<div class="details">
			                                    	<h4><?php echo esc_html( $client['client_name'] ); ?></h4>
				                                    <p><?php echo esc_html( $client['client_status'] ); ?></p>
				                                </div>
			                            		<?php
			                            	}
			                            	?>
			                                
			                                
			                            </div>
			                        </div>
			                        <?php
			                    }
			                    ?>
		                    </div>
		                </div>	
		                <?php
		            }
		            ?>
	            </div>
	        </div>
	    </section>
		<?php
	}

	public function _content_template() {}
}