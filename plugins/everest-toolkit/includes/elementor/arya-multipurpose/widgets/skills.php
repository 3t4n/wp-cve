<?php

class Everest_Toolkit_Skill extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-skill';
	}

	public function get_title() {
		return esc_html__( 'Skills', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-menu-toggle';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'skill_content_section',
			[
				'label' 		=> esc_html__( 'Skills Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Skill Titles', 'everest-toolkit' ),
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

		// Heading - FAQs List
		$this->add_control(
			'team_heading',
			[
				'label' 		=> esc_html__( 'FAQs', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		

		$skills_list = new \Elementor\Repeater();

		// Field - FAQ Title
		$skills_list->add_control(
			'skill_title',
			[
				'label' 		=> esc_html__( 'Skill Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		// Field - FAQ Detail
		$skills_list->add_control(
			'skill_evaluation',
			[
				'label' 		=> esc_html__( 'Skill In Percentage', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::NUMBER,
			]
		);
		

		$this->add_control(
			'skills_list',
			[
				'label' 		=> esc_html__( 'Skills List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::REPEATER,
				'fields' 		=> $skills_list->get_controls(),
				'title_field' 	=> '{{{ skill_title }}}',
			]
		);

		// Heading - Image Separator
		$this->add_control(
			'image_heading',
			[
				'label' 		=> esc_html__( 'Skill Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		


		// Field - About Image
		$this->add_control(
			'skill_image',
			[
				'label' 		=> esc_html__( 'Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::MEDIA,
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$skills_list 		= $settings['skills_list'];
		$skill_image		= $settings['skill_image'];
		?>
		<section class="skill-section section-gap-half">
	        <div class="container">
	            <div class="row align-items-center justify-content-center">
	                <div class="col-lg-6 skill-left">
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

		            	if( !empty( $skills_list ) ) {

		            		foreach( $skills_list as $skill ) {
				            	?>
			                    <div class="prog_container">
			                        <div class="prog_text">
			                        	<?php
			                        	if( !empty( $skill['skill_title'] ) ) {
			                        		?>
			                        		<span class="title"><?php echo esc_html( $skill['skill_title'] ); ?></span>
			                        		<?php
			                        	}

			                        	if( !empty( $skill['skill_evaluation'] ) ) {
			                        		?>
			                        		<span class="precent" style="left:<?php echo esc_attr( absint( $skill['skill_evaluation'] ) ); ?>%"><?php echo esc_html( $skill['skill_evaluation'] ); ?>%</span>
			                        		<?php
			                        	}
			                        	?>
			                        </div>
			                        <?php
			                        if( !empty( $skill['skill_evaluation'] ) ) {
			                        	?>
				                        <div class="progress">
				                            <div class="progress-bar" role="progressbar" style="width: <?php echo esc_attr( absint( $skill['skill_evaluation'] ) ); ?>%;" aria-valuenow="<?php echo esc_attr( absint( $skill['skill_evaluation'] ) ); ?>"
				                                aria-valuemin="0" aria-valuemax="100"></div>
				                        </div>
				                        <?php
				                    }
				                    ?>
			                    </div>
			                    <?php
			                }
		                }
		                ?>
	                </div>
	                <?php
	                if( !empty( $skill_image ) ) {
	                	?>
	                	<div class="col-lg-6 skill-right">
		                    <img class="img-fluid" src="<?php echo esc_url( $skill_image['url'] ); ?>">
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