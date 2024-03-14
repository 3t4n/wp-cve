<?php

class Everest_Toolkit_Contact_One extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-contact-one';
	}

	public function get_title() {
		return esc_html__( 'Contact One', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-form-horizontal';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'cotact_content_section',
			[
				'label' 		=> esc_html__( 'Contact Content', 'everest-toolkit' ),
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

		// Heading - Contact Form
		$this->add_control(
			'form_heading',
			[
				'label' 		=> esc_html__( 'Contact Form', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		// Field - Contact Form Shortcode
		$this->add_control(
			'form_shortcode',
			[
				'label' 		=> esc_html__( 'Contact Form Shortcode', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$form_shortcode		= $settings['form_shortcode'];
		?>
	    <section class="contact-section contact-page-section padding-top-120" id="contact-section">
	        <div class="container">
	            <div class="home-contact">
	                <?php
		            	if( !empty( $main_text ) ) {
		                    ?>
		                    <h2 class="text-center"><?php echo esc_html( $main_text ); ?></h2>
		                    <?php
		                }

		                if( !empty( $description_text ) ) {
		                	?>
		                    <p class="text-center"><?php echo esc_html( $description_text ); ?></p>
		                    <?php
		                }
		                ?>
	            </div>
	            <?php
	            if( !empty( $form_shortcode ) ) {
	            	?>
		            <div class="row justify-content-center form-row">
		                <div class="col-lg-9">
		                    <?php echo do_shortcode( $form_shortcode ); ?>
		                </div>
		            </div>
		            <?php
		        }
		        ?>
	        </div>
	    </section>
		<?php
	}

	public function _content_template() {}
}