<?php
namespace ElpugBlogroll\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */




class ELPUG_Blogroll extends Widget_Base {	

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'blogroll';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Post Carousel', 'elpug' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-slides';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'elpug-elements' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'elpug' ];
	}

	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */

	protected function _register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Posts Carousel Settings', 'elpug' ),
			]
		);

		$this->add_control(
		  'postsperpage',
		  [
		     'label'   => __( 'Number of posts to show', 'elpug' ),
		     'type'    => Controls_Manager::NUMBER,
		     'default' => 12,
		     'min'     => 1,
		     'max'     => 60,
		     'step'    => 1,
		  ]
		);

		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings();
		?>

		<div class="elpug-blogroll-main-wrapper">
			
			<?php 
				$args = array(
					'post_type' => 'post',
			        //'category'          => '',
			        'posts_per_page'    => $settings['postsperpage'],
			        //'paged'             => $paged,
			        //'offset'            => $new_offset,
			    );

				$posts = get_posts($args);


    			if(count($posts)){    				
    				

				    global $post; ?>
			        

			        	<div class="owl-carousel-wrapper elpug-blogroll-carousel-wrapper">
			        		<div class="owl-carousel elpug-blogroll-carousel">

			        			<?php foreach($posts as $post){ ?>
			        				<div class="elpug-blog-item">

			        					<!-- Featured Image -->
										<a href="<?php the_permalink(); ?>" alt="<?php the_title(); ?>" class="elpug-primary-btn">													
											<?php 
											$postimgclass = null;
											$post_image = null;
											$post_image_style = null;
											if ( has_post_thumbnail() ) { 
												$postimgclass = 'elpug-blog-item-img-cover';
												$post_image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'single-post-thumbnail' );														
												$post_image_style = 'background-image: url(' .esc_url($post_image[0]) .');';
											} ?>

											<figure class="elpug-blog-item-img <?php echo esc_attr($postimgclass); ?>" style="<?php echo $post_image_style; ?>">														
											</figure>
	        							</a>
			        					<!-- /Featured Image -->

			        					<div class="elpug-blog-content">
			        						<article class="elpug-post">

			        							<h3 class="elpug-heading"><?php the_title(); ?></h3>
			        							<div>

			        								<?php 
			        									$postid = $post->ID;
			        									
			        									echo esc_html (elpug_blogroll_get_excerpt_by_id( $postid , 25) );

			        								?>
			        							</div>
			        							<a href="<?php the_permalink(); ?>" class="elpug-primary-btn"><span><?php echo __('See More', 'elpug'); ?></span></a>
			        						</article>
			        					</div>		      				

				        			</div>

			        			<?php } ?>

			        		</div>
			        	</div>


			        <?php 
				}

				wp_reset_postdata();

    		?>

			<?php //echo do_shortcode('[pug-blogroll postsperpage="'.$settings['postsperpage'].'"]'); ?>
		

		</div>

		<?php
	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	/*protected function _content_template() {
		$sliderheight = $settings['slider_height'];
		?>
		
		<div class="pando-slideshow">
			<?php echo do_shortcode('[pando-slider heightstyle="'.$sliderheight.'"]'); ?>
		</div>


		<?php
	}*/
}