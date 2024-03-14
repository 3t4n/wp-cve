<?php
namespace ElpugTestimonials\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */
class ELPUG_Testimonials_Carousel extends Widget_Base {

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
		return 'testimonials_carousel';
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
		return __( 'Testimonials Carousel', 'elpug' );
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
		return [ 'elpug-elements'];
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
				'label' => __( 'Testimonials Carousel Settings', 'elpug' ),
			]
		);

		$this->add_control(
			'testimonials_carousel',
			[
				'label' => __( 'Testimonials Carousel', 'elpug' ),
				'type' => Controls_Manager::REPEATER,				
				'fields' => [
					[
						'name' => 'testimonial_content',
						'label' => __( 'Content/Text', 'elpug' ),
						'type' => Controls_Manager::TEXTAREA,												
					],
					[
						'name' => 'testimonial_name',
						'label' => __( 'Name', 'elpug' ),
						'type' => Controls_Manager::TEXT,
						'default'     => __( 'Type the Name Here', 'elpug' ),						
					],
					[
						'name' => 'testimonial_subtitle',
						'label' => __( 'Subtitle (Optional, Will be show below the name)', 'elpug' ),
						'type' => Controls_Manager::TEXT,												
					],
					
					[
						'name' => 'testimonial_image',
						'label' => __( 'Image', 'elpug' ),
						'type' => Controls_Manager::MEDIA,											
					],					
				],
			]
		);

		$this->add_control(
		  'testimonial_image_style',
		  [
		     'label'       => __( 'Image Style', 'elpug' ),
		     'type' => Controls_Manager::SELECT,
		     'default' => 'elpug-img-style1',
		     'options' => [
		     	'elpug-img-style1'  => __( 'Original', 'elpug' ),
		     	'elpug-img-style2' => __( 'Rounded', 'elpug' ),
		     	'elpug-img-style3' => __( 'Boxed', 'elpug' ),
		     ],
		  ]
		);

		$this->add_control(
		  'testimonials_style',
		  [
		     'label'       => __( 'Element Style', 'elpug' ),
		     'type' => Controls_Manager::SELECT,
		     'default' => 'elpug-testimonial-style1',
		     'options' => [
		     	'elpug-testimonial-style1'  => __( 'Classic / Clean', 'elpug' ),
		     	'elpug-testimonial-style2' => __( 'Card', 'elpug' ),
		     	'elpug-testimonial-style3' => __( 'Balloon', 'elpug' ),
		     ],
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

		$style = $settings['testimonials_style'];

		$carousellist = $this->get_settings( 'testimonials_carousel' );

		?>

		<?php if ( $carousellist ) { ?>

		<div class="elpug-testimonials-carousel-wrapper">

			<div class="owl-carousel elpug-testimonials-carousel owl-theme">


			<?php foreach ( $carousellist as $carouselitem) { ?>

			<?php 
				
				$image = $carouselitem['testimonial_image'];

				//$sociallist = $carouselitem['testimonials_carousel_social'];
			
			?>

					<div class="elpug-testimonial-item-wrapper <?php echo esc_attr($settings['testimonials_style']); ?>">

						<div class="elpug-testimonial-item ">
							
							
							<!-- Content -->
							<div class="elpug-testimonial-content">
								<?php echo wp_kses_post($carouselitem['testimonial_content']); ?>
							</div>
							<!-- /Content -->	

							<!-- Image -->
							<?php if (!empty($image)) { ?>
							<div class="elpug-testimonial-image <?php echo esc_attr($settings['testimonial_image_style']); ?>" style="background-image: url(<?php echo esc_url($image['url']); ?>);">
								<img src="<?php echo esc_url($image['url']); ?>">
							</div>
							<?php } ?>
							<!-- /Image -->			

							<!-- Footer -->
							<div class="elpug-testimonial-footer">
								<div class="elpug-testimonial-name">
									<?php echo wp_kses_post($carouselitem['testimonial_name']); ?>
								</div>
								<div class="elpug-testimonial-subtitle">
									<?php echo wp_kses_post($carouselitem['testimonial_subtitle']); ?>
								</div>
							</div>
							<!-- /Footer -->
									
						</div>
					</div>	

			<?php } ?>

			</div>

		</div>
		<?php } ?>

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