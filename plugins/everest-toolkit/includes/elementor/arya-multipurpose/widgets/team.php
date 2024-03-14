<?php

class Everest_Toolkit_Team extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-team';
	}

	public function get_title() {
		return esc_html__( 'Team', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'fa fa-users';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'team_content_section',
			[
				'label' 		=> esc_html__( 'Team Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Team Titles', 'everest-toolkit' ),
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

		// Heading - Team Members List
		$this->add_control(
			'team_heading',
			[
				'label' 		=> esc_html__( 'Team Members', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);		

		$members_list = new \Elementor\Repeater();

		// Field - Member Name
		$members_list->add_control(
			'member_name',
			[
				'label' 		=> esc_html__( 'Member Name', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'text',
				'label_block' 	=> true,
			]
		);

		// Field - Memeber Image
		$members_list->add_control(
			'member_image',
			[
				'label' 		=> esc_html__( 'Member Image', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::MEDIA,
			]
		);

		// Field - Member's Facebook
		$members_list->add_control(
			'member_facebook',
			[
				'label' 		=> esc_html__( 'Facebook Link', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'url',
				'label_block' 	=> true,
			]
		);

		// Field - Member's Twitter
		$members_list->add_control(
			'member_twitter',
			[
				'label' 		=> esc_html__( 'Twitter Link', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'url',
				'label_block' 	=> true,
			]
		);

		// Field - Member's Linkedin
		$members_list->add_control(
			'member_linkedin',
			[
				'label' 		=> esc_html__( 'Linkedin Link', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::TEXT,
				'input_type' 	=> 'url',
				'label_block' 	=> true,
			]
		);

		$this->add_control(
			'members_list',
			[
				'label' 		=> esc_html__( 'Members List', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::REPEATER,
				'fields' 		=> $members_list->get_controls(),
				'title_field' 	=> '{{{ member_name }}}',
			]
		);

		$this->end_controls_section();
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$members_list 		= $settings['members_list'];
		?>
		<section class="team-section section-gap-full">
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
	            if( !empty( $members_list ) ) {
		            ?>
		            <div class="row">
		                <div class="team-carousel  owl-carousel" id="team-carousel">
		                	<?php
		                	foreach( $members_list as $member ) {
			                	?>
			                    <div class="single-team item">
			                    	<?php
			                    	if( !empty( $member['member_image'] ) ) {
				                    	echo wp_get_attachment_image( $member['member_image']['id'], 'arya-multipurpose-thumbnail-five' );
				                    }
				                    ?>
			                        <div class="team-content">
			                        	<?php
			                        	if( !empty( $member['member_name'] ) ) {
			                        		?>
			                        		<h4><?php echo esc_html( $member['member_name'] ); ?></h4>
			                        		<?php
			                        	}
			                        	?>			                            
			                            <ul>
			                            	<?php
			                            	if( !empty( $member['member_facebook'] ) ) {
				                            	?>
				                                <li>
				                                    <a href="<?php echo esc_url( $member['member_facebook'] ); ?>">
				                                        <i class="fa fa-facebook"></i>
				                                    </a>
				                                </li>
				                                <?php
				                            }
				                            if( !empty( $member['member_twitter'] ) ) {
					                            ?>
				                                <li>
				                                    <a href="<?php echo esc_url( $member['member_twitter'] ); ?>">
				                                        <i class="fa fa-twitter"></i>
				                                    </a>
				                                </li>
				                                <?php
				                            }
				                            if( !empty( $member['member_linkedin'] ) ) {
				                            	?>
				                                <li>
				                                    <a href="<?php echo esc_url( $member['member_linkedin'] ); ?>">
				                                        <i class="fa fa-linkedin"></i>
				                                    </a>
				                                </li>
				                                <?php
				                            }
				                            ?>
			                            </ul>
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
	    </section>
		<?php
	}

	public function _content_template() {}
}