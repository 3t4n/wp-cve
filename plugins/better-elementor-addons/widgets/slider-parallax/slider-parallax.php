<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 *
 * Elementor widget for slider parallax.
 *
 */
class Better_Slider_Parallax extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 */
	public function get_name() {
		return 'bea-slider-parallax';
	}

	/**
	 * Retrieve the widget title.
	 */
	public function get_title() {
		return esc_html__( 'BEA Slider Parallax', 'better-el-addons' );
	}

	/**
	 * Retrieve the widget icon.
	 */
	public function get_icon() {
		return 'eicon-slider-push';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 */
	public function get_categories() {
		return [ 'better-category' ];
	}

	/**
	 * Retrieve the list of scripts the widget depended on.
	 */

	public function get_script_depends() { return [ 'swiper','better-elementor','better-lib','better-slider','better-el-addons','slider-parallax']; }

	/**
	 * Register the widget controls.
	 */
	protected function _register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Slides', 'better-el-addons' ),
			]
		);


			$this->add_control(
				'slider_list',
				[
					'label' => __( 'Slider List', 'better-el-addons' ),
					'type' => Controls_Manager::REPEATER,
					'default' => [
						[
							'title' => __( 'Slider Heading Title', 'better-el-addons' ),
							'subtitle' => __( 'Slider subtitle', 'better-el-addons' ),
						],
						[
							'title' => __( 'Slider Heading Title', 'better-el-addons' ),
							'subtitle' => __( 'Slider subtitle', 'better-el-addons' ),
						],
						[
							'title' => __( 'Slider Heading Title', 'better-el-addons' ),
							'subtitle' => __( 'Slider subtitle', 'better-el-addons' ),
						],
					],
					'fields' => [
						[
							'name' => 'title',
							'label' => __( 'Slider Heading Title', 'better-el-addons' ),
							'type' => Controls_Manager::TEXT,
							'label_block' => true,
							'placeholder' => __( 'Insert your slider heading title here..', 'better-el-addons' ),
							'default' => __( 'Slider Heading Title' ,  'better-el-addons'  ),
						],
						[
							'name' => 'subtitle',
							'label' => __( 'Slider Subtitle', 'better-el-addons' ),
							'type' => Controls_Manager::TEXT,
							'label_block' => true,
							'placeholder' => __( 'Insert your slider subtitle here..', 'better-el-addons' ),
							'default' => __( 'Slider Subtitle' ,  'better-el-addons'  ),
						],

						[
							'name' => 'image',
							'label' => __( 'Slider Image', 'better-el-addons' ),
							'type' => Controls_Manager::MEDIA,
							'default' => [
								'url' => Utils::get_placeholder_image_src(),
							],
						],
					[
						'name' => 'link',
						'label' => __( 'Link', 'better-el-addons' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Leave it blank if you don\'t need this button', 'better-el-addons' ),
					],

					],
					'title_field' => '{{{ title }}}',
				]
			);

		$this->end_controls_section();

		// start of the Style tab section
		$this->start_controls_section(
			'subtitle_style_section',
			[
				'label' => esc_html__( 'SubTitle', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// SubTitle Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'bea_slider_subtitle_typography',
				'label' => esc_html__( 'SubTitle Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bea-slider-parallax-1 .slider .parallax-slider .swiper-slide .caption .sub-title',
			]
		);

		$this->end_controls_section();



		// start of the Style tab section
		$this->start_controls_section(
			'title_style_section',
			[
				'label' => esc_html__( 'Title', 'better-el-addons' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);

		// Title Typography 
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'bea_slider_title_typography',
				'label' => esc_html__( 'Title Typography', 'better-el-addons' ),
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .bea-slider-parallax-1 .slider .parallax-slider .swiper-slide .caption .title',
			]
		);

		$this->end_controls_section();




	}

	/**
	 * Render about us widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		// get our input from the widget settings.
		$settings = $this->get_settings_for_display();
       ?>
			<div class=" bea-slider-parallax-1">
				<div class="slider showcase-full">
		            <div class="swiper-container parallax-slider">
		                <div class="swiper-wrapper">
		                	<?php foreach ( $settings['slider_list'] as $index => $item ) : ?>
		                    <div class="swiper-slide">
		                        <div class="better-bg-img valign" data-background="<?php echo esc_url ( $item['image']['url']); ?>" data-overlay-dark="3">
		                            <div class="container">
		                                <div class="row">
		                                    <div class="col-lg-12">
		                                        <div class="caption">
		                                            <h6 class="sub-title mb-30" data-swiper-parallax="-1000"><?php echo wp_kses_post ( $item['subtitle'] ); ?></h6>
		                                            <h1 data-swiper-parallax="-1500">
	                                                	<?php if(!empty($item['link']['url'])): ?> 
	                                                	<a href="<?php echo esc_url ( $item['link']['url'] ) ; ?>">
	                                                	<?php endif; ?>
	                                                    <span class="title" ><?php echo wp_kses_post ( $item['title'] ); ?></span>


		                                                <?php if(!empty($item['link']['url'])): ?> </a> <?php endif; ?>
		                                            </h1>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                    <?php endforeach; ?>
		                </div>

                        <!-- slider setting -->
		                <div class="slider-contro">
		                    <div class="swiper-button-next swiper-nav-ctrl cursor-pointer">
		                        <div>
		                            <span class="next-ctrl"><?php echo esc_html__('Next Slide', 'better-el-addons'); ?></span>
		                        </div>
		                        <div><i class="fas fa-chevron-right"></i></div>
		                    </div>
		                    <div class="swiper-button-prev swiper-nav-ctrl cursor-pointer">
		                        <div><i class="fas fa-chevron-left"></i></div>
		                        <div>
		                            <span class="prev-ctrl"><?php echo esc_html__('Prev Slide', 'better-el-addons'); ?></span>
		                        </div>
		                    </div>
		                </div>
		                <div class="swiper-pagination dots"></div>

		            </div>
		        </div>
			</div>
       <?php
	}

	/**
	 * Render the widget output in the editor.
	 */
	protected function content_template() {
			
	}
}