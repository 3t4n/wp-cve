<?php

class Everest_Toolkit_Partners extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-partners';
	}

	public function get_title() {
		return esc_html__( 'Partners', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'fa fa-handshake-o';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'partners_content_section',
			[
				'label' 		=> esc_html__( 'Partners Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Section Titles', 'everest-toolkit' ),
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

		// Heading - Partners List
		$this->add_control(
			'partners_heading',
			[
				'label' 		=> esc_html__( 'Partners', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		

		$partners_list = new \Elementor\Repeater();

		// Field - Partner Name
		$partners_list->add_control(
			'partners_name',
			[
				'label' 		=> esc_html__( 'Partner Name', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		// Field - Memeber Image
		$partners_list->add_control(
			'partners_image',
			[
				'label' 		=> esc_html__( 'Partner Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::MEDIA,
			]
		);

		$this->add_control(
			'partners_list',
			[
				'label' 		=> esc_html__( 'Partners List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::REPEATER,
				'fields' 		=> $partners_list->get_controls(),
				'title_field' 	=> '{{{ partners_name }}}',
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$partners 			= $settings['partners_list'];
		?>
		<section class="featured-section section-gap-full">
	        <div class="container">
	            <div class="section-title">
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
	            if( !empty( $partners ) ) {
		            ?>
		            <div class="row align-item-center">
		            	<?php
		            	foreach( $partners as $partner ) {
			            	?>
			                <div class="col-lg-3 col-md-6 col-sm-6 single-logo">
			                    <?php echo wp_get_attachment_image( $partner['partners_image']['id'], 'arya-multipurpose-thumbnail-six' ); ?>
			                </div>
			                <?php
			            }
			            ?>
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