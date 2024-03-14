<?php

class Everest_Toolkit_Services_One extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-services-one';
	}

	public function get_title() {
		return esc_html__( 'Services One', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'eicon-home-heart';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'services_content_section',
			[
				'label' 		=> esc_html__( 'Services Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Services Titles', 'everest-toolkit' ),
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

		// Heading - Services List
		$this->add_control(
			'services_heading',
			[
				'label' 		=> esc_html__( 'Services List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		

		$services_list = new \Elementor\Repeater();

		$services_list->add_control(
			'service_title',
			[
				'label' 		=> esc_html__( 'Service Title', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		$services_list->add_control(
			'service_description',
			[
				'label' 		=> esc_html__( 'Service Description', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXTAREA,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		$services_list->add_control(
			'service_icon',
			[
				'label' 		=> esc_html__( 'Service Icon', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::ICON,
			]
		);

		$this->add_control(
			'services_list',
			[
				'label' 		=> esc_html__( 'Services List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::REPEATER,
				'fields' 		=> $services_list->get_controls(),
				'title_field' 	=> '{{{ service_title }}}',
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$services_list 		= $settings['services_list'];
		?>
		<section class="service-section section-gap-full">
	        <div class="container">
	        	<?php
	        	if( !empty( $main_text ) || !empty( $description_text ) ) {
		        	?>
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
		        }

		        if( !empty( $services_list ) ) {
			        ?>
		            <div class="row">
		            	<?php
		            	foreach( $services_list as $service ) {
		            		?>
			                <div class="col-lg-4  col-md-6 pb-30">
			                    <div class="single-service">
			                    	<?php
			                    	if( !empty( $service['service_icon'] ) ) {
				                    	?>
				                        <span class="service-icon"><i class="<?php echo esc_attr( $service['service_icon'] ); ?>"></i></span>
				                        <?php
				                    }

				                    if( !empty( $service['service_title'] ) ) {
				                    	?>
			                        	<h4><?php echo esc_html( $service['service_title'] ); ?></h4>
			                        	<?php
			                        }

			                        if( !empty( $service['service_description'] ) ) {
			                        	?>
				                        <p><?php echo esc_html( $service['service_description'] ); ?></p>
				                        <?php
				                    }
				                    ?>
			                    </div>
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