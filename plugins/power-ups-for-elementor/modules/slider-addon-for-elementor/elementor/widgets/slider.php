<?php
namespace ElpugSlider\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * @since 1.0.0
 */
class ELPUG_Slider extends Widget_Base {

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
		return 'slider';
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
		return __( 'Slider', 'elpug' );
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
		return 'eicon-slider-full-screen';
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
				'label' => __( 'Slider Settings', 'elpug' ),
			]
		);

		$this->add_control(
			'slider',
			[
				'label' => __( 'Slider Carousel', 'elpug' ),
				'type' => Controls_Manager::REPEATER,				
				'fields' => [
					[
						'name' => 'slider_title',
						'label' => __( 'Title', 'elpug' ),
						'type' => Controls_Manager::TEXT,
						'default'     => __( 'This is the Slide Title', 'elpug' ),						
					],
					[
						'name' => 'slider_content',
						'default'     => __( 'And this is the slide content', 'elpug' ),
						'label' => __( 'Content/Text', 'elpug' ),
						'type' => Controls_Manager::TEXTAREA,												
					],					
					[
						'name' => 'slider_button_text',
						'default'     => __( 'See Link', 'elpug' ),
						'label' => __( 'Text of the Button', 'elpug' ),
						'type' => Controls_Manager::TEXT,												
					],					
					[
						'name' => 'slider_button_link',
						'label' => __( 'Link of the Button', 'elpug' ),
						'type' => Controls_Manager::URL,
						'show_external' => false,									
					],		
					[
						'name' => 'slider_image',
						'label' => __( 'Background Image', 'elpug' ),
						'type' => Controls_Manager::MEDIA,							
					],	
					[
						'name' => 'slider_overlay',
						'label' => __( 'Overlay Color and Transparency', 'elpug' ),
						'type' => Controls_Manager::COLOR,
				        'selectors' => [
				            //'{{WRAPPER}} .elpug-slider-item .elpug-slider-item-overlay' => 'background: {{VALUE}}',
				        ],
					],	
					[
						'name' => 'slider_align',
						'label' => __( 'Content Aligment', 'elpug' ),
						 'type' => Controls_Manager::CHOOSE,
					     'default' => 'solid',
					     'options' => [
				            'left'    => [
				                'title' => __( 'Left', 'elpug' ),
				                'icon' => 'fa fa-align-left',
				            ],
				            'center' => [
				                'title' => __( 'Center', 'elpug' ),
				                'icon' => 'fa fa-align-center',
				            ],
				            'right' => [
				                'title' => __( 'Right', 'elpug' ),
				                'icon' => 'fa fa-align-right',
				            ],
				        ],					
					],		
				],
				'default' => [
					[
						'slider_title' => __( 'This is your First Slide!', 'elpug' ),
						'slider_content' => __( 'Edit the content field on the widget to change this text and add a background :)', 'elpug' ),
						'slider_button_text' => __( 'See More', 'elpug' ),
					],
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

		$carousellist = $this->get_settings( 'slider' );

		?>

		<?php if ( $carousellist ) { ?>

		<div class="elpug-slider-carousel-wrapper">

			<div class="owl-carousel elpug-slider-carousel owl-theme">


			<?php foreach ( $carousellist as $carouselitem) { ?>

			<?php 
				
				$image = $carouselitem['slider_image'];

				//$sociallist = $carouselitem['slider_social'];
			
			?>

					<div class="elpug-slider-item-wrapper">

						<div class="elpug-slider-item " style="background-image: url(<?php echo esc_url($image['url']); ?>">

							<div class="elpug-slider-item-overlay" style="background: <?php echo esc_attr($carouselitem['slider_overlay']); ?>;"></div>

							<div class="container">					
								
								<div class="elpug-slide-inner" style="text-align: <?php echo esc_attr($carouselitem['slider_align']); ?>">
									
									<!-- Title -->
									<h1 class="elpug-slide-title"><?php echo wp_kses_post($carouselitem['slider_title']); ?></h1>
									<!-- /Title -->

									<!-- Content -->
									<p class="elpug-slide-text"><?php echo wp_kses_post($carouselitem['slider_content']); ?></p>
									<!-- /Content -->

									<!-- button -->
									<?php 
										$website_link = $carouselitem['slider_button_link'];
										$url = $website_link['url'];
										//$target = $website_link['is_external'] ? 'target="_blank"' : '';
									?>
									<a class="elpug-slide-btn" href="<?php echo esc_url($url); ?>"><span><?php echo esc_html($carouselitem['slider_button_text']); ?></span></a>
									<!-- /button -->


								</div>	

							</div>
									
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