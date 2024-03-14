<?php

class Everest_Toolkit_Features extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-features';
	}

	public function get_title() {
		return esc_html__( 'Features', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-featured-image';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'features_content_section',
			[
				'label' 		=> esc_html__( 'Features Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Features Titles', 'everest-toolkit' ),
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

		// Heading - Features List
		$this->add_control(
			'features_heading',
			[
				'label' 		=> esc_html__( 'Features List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		

		$features_list = new \Elementor\Repeater();

		$features_list->add_control(
			'feature_title',
			[
				'label' 		=> esc_html__( 'Feature Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		$features_list->add_control(
			'feature_description',
			[
				'label' 		=> esc_html__( 'Feature Description', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXTAREA,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		$features_list->add_control(
			'feature_icon',
			[
				'label' 		=> esc_html__( 'Feature Icon', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::ICON,
			]
		);

		$this->add_control(
			'features_list',
			[
				'label' 		=> esc_html__( 'Features List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::REPEATER,
				'fields' 		=> $features_list->get_controls(),
				'title_field' 	=> '{{{ feature_title }}}',
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

		$this->end_controls_section();

		$this->start_controls_section(
			'features_style_content_section',
			[
				'label' 		=> esc_html__( 'Styles', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'features_background',
				'label' => esc_html__( 'Gradient Background', 'everest-toolkit' ),
				'types' => [ 'gradient' ],
				'selector' => '.features-wrap .single-feature .overlay-bg',
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$features_list 		= $settings['features_list'];
		$button_title 		= $settings['button_title'];
		$button_link 		= $settings['button_link'];
		?>
		<section class="feature-section section-gap-full" id="feature-section">
	        <div class="container">
	            <div class="row align-items-center feature-wrap">
	                <div class="col-lg-4 header-left">
	                	<?php
	                	if( !empty( $main_text ) ) {
		                	?>
		                    <h1><?php echo esc_html( $main_text ); ?></h1>
		                    <?php
		                }

		                if( !empty( $button_title ) && !empty( $button_link ) ) {
		                	?>
		                    <a class="primary-btn" href="<?php echo esc_url( $button_link ); ?>"><?php echo esc_html( $button_title ); ?>
		                        <span class="ti-arrow-right"></span>
		                    </a>
		                    <?php
		                }
		                ?>
	                </div>
	                <?php
	                if( !empty( $features_list ) ) {
		                ?>
		                <div class="col-lg-8">
		                    <div class="row features-wrap">
		                    	<?php
		                    	foreach( $features_list as $feature ) {
		                    		?>
		                    		<div class="col-md-6">
			                            <div class="single-feature relative">
			                                <div class="overlay overlay-bg"></div>
			                                <?php 
			                                if( !empty( $feature['feature_icon'] ) ) {
			                                	?>
			                                	<span class="feature-icon"><i class="<?php echo esc_attr( $feature['feature_icon'] ); ?>"></i></span>
			                                	<?php
			                                }

			                                if( !empty( $feature['feature_title'] ) ) {
			                                	?>
			                                	<h3><?php echo esc_html( $feature['feature_title'] ); ?></h3>
			                                	<?php
			                                }

			                                if( !empty( $feature['feature_description'] ) ) {
			                                	?>
			                                	<p><?php echo esc_html( $feature['feature_description'] ); ?></p>
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