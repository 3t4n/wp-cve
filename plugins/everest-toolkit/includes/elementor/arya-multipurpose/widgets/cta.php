<?php

class Everest_Toolkit_CTA extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-cta';
	}

	public function get_title() {
		return esc_html__( 'CTA', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-info-box';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'cta_content_section',
			[
				'label' 		=> esc_html__( 'CTA Content', 'everest-toolkit' ),
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

		
		// Heading - Button Separator
		$this->add_control(
			'button_heading',
			[
				'label' 		=> esc_html__( 'Section Button Link and Text', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		// Field - Button One Icon
		$this->add_control(
			'button_one_icon',
			[
				'label' 		=> esc_html__( 'Button One Icon', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::ICON,
			]
		);

		// Field - Button One Title
		$this->add_control(
			'button_one_title',
			[
				'label' 		=> esc_html__( 'Button One Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Field - Button One Link
		$this->add_control(
			'button_one_link',
			[
				'label' 		=> esc_html__( 'Button One Link', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'url',
				'label_block' 	=> true,
			]
		);

		// Field - Button Two Icon
		$this->add_control(
			'button_two_icon',
			[
				'label' 		=> esc_html__( 'Button Two Icon', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::ICON,
			]
		);

		// Field - Button Two Title
		$this->add_control(
			'button_two_title',
			[
				'label' 		=> esc_html__( 'Button Two Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
			]
		);

		// Field - Button Two Link
		$this->add_control(
			'button_two_link',
			[
				'label' 		=> esc_html__( 'Button Two Link', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'url',
				'label_block' 	=> true,
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$button_one_icon	= $settings['button_one_icon'];
		$button_one_title 	= $settings['button_one_title'];
		$button_one_link	= $settings['button_one_link'];
		$button_two_icon	= $settings['button_two_icon'];
		$button_two_title 	= $settings['button_two_title'];
		$button_two_link	= $settings['button_two_link'];
		?>
		<section class="download-section section-gap-full" id="download-section">
        <div class="container">
            <div class="row download-wrap justify-items-between align-items-center">
                <div class="col-lg-6">
                	<?php
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
                </div>
                <div class="col-lg-6 dload-btn">
                    <a href="<?php echo esc_url( $button_one_link ); ?>" class="primary-btn">
                        <span><?php echo esc_html( $button_one_title ); ?></span>
                        <span class="cta-icon"><i class="<?php echo esc_attr( $button_one_icon ); ?>"></i></span>
                    </a>
                    <a href="<?php echo esc_url( $button_two_link ); ?>" class="primary-btn">
                        <span><?php echo esc_html( $button_two_title ); ?></span>
                        <span class="cta-icon"><i class="<?php echo esc_attr( $button_two_icon ); ?>"></i></span>
                    </a>
                </div>
            </div>
        </div>
    </section>
		<?php
	}

	public function _content_template() {}
}