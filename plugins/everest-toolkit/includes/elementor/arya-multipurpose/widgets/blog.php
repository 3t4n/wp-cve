<?php

class Everest_Toolkit_Blog extends \Elementor\Widget_Base {

	public function get_name() {
		return 'arya-multipurpose-blog';
	}

	public function get_title() {
		return esc_html__( 'Blog', 'everest-toolkit' );
	}

	public function get_icon() {
		return 'fa fa-pencil-square-o';
	}

	public function get_categories() {
		return ['arya-multipurpose-widgets'];
	}

	public function _register_controls() {

		$this->start_controls_section(
			'services_content_section',
			[
				'label' 		=> esc_html__( 'Blog Content', 'everest-toolkit' ),
				'tab' 			=> \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);


		// Heading - Text Separator
		$this->add_control(
			'text_heading',
			[
				'label' 		=> esc_html__( 'Blog Titles', 'everest-toolkit' ),
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

		// Field - Blog Elements Separator
		$this->add_control(
			'blog_heading',
			[
				'label' 		=> esc_html__( 'Blog Elements', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::HEADING,
				'separator' 	=> 'before',
			]
		);

		$terms = get_terms( 'category' );

		$categories = array();

		foreach( $terms as $term ) {

			$categories[$term->slug] = $term->name;
		}

		// Field - Blog Categories
		$this->add_control(
			'blog_cat',
			[
				'label' 		=> esc_html__( 'Select Category', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::SELECT2,
				'multiple'		=> false,
				'options'		=> $categories,
			]
		);

		// Field - No of Blog Posts
		$this->add_control(
			'no_of_posts',
			[
				'label' 		=> esc_html__( 'No of Posts', 'everest-toolkit' ),
				'type' 			=> \Elementor\Controls_Manager::NUMBER,
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
	}

	public function render() {

		$settings 			= $this->get_settings();

		$main_text 			= $settings['main_title_text'];
		$description_text 	= $settings['description_text'];
		$blog_cat			= $settings['blog_cat'];
		$no_of_posts		= $settings['no_of_posts'];
		$button_title 		= $settings['button_title'];
		$button_link		= $settings['button_link'];

		$blog_args = array(
			'post_type' => 'post',
		);

		if( !empty( $blog_cat ) ) {
			$blog_args['category_name'] = $blog_cat;
		}

		if( absint( $no_of_posts ) > 0 ) {
			$blog_args['posts_per_page'] = absint( $no_of_posts );
		} else {
			$blog_args['posts_per_page'] = 3;
		}

		$blog_query = new WP_Query( $blog_args );
		?>
		<section class="blog-section" id="blog-section">
	        <div class="container blog-wrap section-gap-full">
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
	            <div class="row justify-content-center">
	            	<?php
	            	if( $blog_query->have_posts() ) {
	            		while( $blog_query->have_posts() ) {
	            			$blog_query->the_post();
	            			?>
	            			<div class="col-lg-4 col-md-6">
			                    <div class="single-blog">
			                        <div class="overlay overlay-bg"></div>
			                        <?php
									the_post_thumbnail( 'arya-multipurpose-thumbnail-two', array( 
										'class' => 'img-fluid',
										'alt' => the_title_attribute( array(
											'echo' => false,
										) ),
									) );
									?>
			                        <div class="blog-post-details">
			                        	<?php arya_multipurpose_categories_meta(); ?>
			                            <a href="<?php the_permalink(); ?>">
			                                <h4><?php the_title(); ?></h4>
			                            </a>
			                        </div>
			                    </div>
			                </div>
	            			<?php
	            		}
	            		wp_reset_postdata();
	            	}
	            	?>
	            </div>
	            <?php
	            if( !empty( $button_title ) && !empty( $button_link ) ) {
	            	?>
	            	<div class="btn-wrap text-center">
		                <a href="<?php echo esc_url( $button_link ); ?>" class="primary-btn"><?php echo esc_html( $button_title ); ?></a>
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