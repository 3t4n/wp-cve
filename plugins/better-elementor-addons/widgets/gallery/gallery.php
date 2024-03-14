<?php
namespace BetterWidgets\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Utils;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


		
/**
 * @since 1.3.5
 */
class Better_Gallery extends Widget_Base {

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
		return 'better-gallery';
	}
		//script depend
	public function get_script_depends() { return [ 'better-animation','jquery-swiper','better-swiper-slider-script' ]; }

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
		return __( 'Better Gallery', 'better_plg' );
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
		return 'eicon-blockquote';
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
		return [ 'better-category' ];
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
				'label' => __( 'Gallery Settings', 'better_plg' ),
			]
		);


		$this->add_control(
			'gallery_col',
			[
				'label' => __( 'Columns number', 'better_plg' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '4',
			]
		);
	
		$this->add_control(
			'gallery_list',
			[
				'label' => __( 'Gallery List', 'better_plg' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => 'Main Title',
						'subtitle' => 'Sub Title',
					],
					[
						'title' => 'Main Title',
						'subtitle' => 'Sub Title',
					],

				],
				'fields' => [
					[
						'name' => 'title',
						'label' => __( 'Title', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Main Title..', 'better_plg' ),
					],
					[
						'name' => 'subtitle',
						'label' => __( 'Sub Title', 'better_plg' ),
						'type' => Controls_Manager::TEXT,
						'label_block' => true,
						'placeholder' => __( 'Sub Title..', 'better_plg' ),
					],
					[
						'name' => 'link',
						'label' => __( 'Link', 'better_plg' ),
						'type' => Controls_Manager::URL,
						'label_block' => true,
						'placeholder' => __( 'Add your link here..', 'better_plg' ),
					],
					
					[
						'name' => 'image',
						'label' => __( 'Image', 'better_plg' ),
						'type' => Controls_Manager::MEDIA,
						'default' => [
							'url' => Utils::get_placeholder_image_src(),
						],
					],

				],
				'title_field' => '{{ title }}',
			]
		);
		$this->add_control(
			'nav_prev',
			[
				'label' => __( 'Previous','better_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Prev Slide', 'better_plg' ),
				'condition' => [
					'gallery_style' => array('1','2','3','4')
				],
			]
		);
		$this->add_control(
			'nav_next',
			[
				'label' => __( 'Next','better_plg' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
				'default' => __( 'Next Slide', 'better_plg' ),
				'condition' => [
					'gallery_style' => array('1','2','3','4')
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_image',
			[
				'label' => __( 'Image style', 'better_plg' ),
				'tab' => Controls_Manager::TAB_STYLE,

			]
		);

		$this->add_control(
			'img_height',
			[
				'label' => __( 'Image height', 'better_plg' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .better-gallery.style-1 .item .img' => 'height: {{SIZE}}{{UNIT}};',
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
	    $Count = 0;
	    $col = $settings['gallery_col'];
	    ?>
	    <div class="better-gallery style-1">
	        <div class="container-fluid">
	            <?php foreach ( $settings['gallery_list'] as $index => $item ) :
	                $image_url = esc_url( $item['image']['url'] );
	                $title = esc_html( $item['title'] );
	                $subtitle = esc_html( $item['subtitle'] );
	                $link_url = esc_url( $item['link']['url'] );
	                $is_external = $item['link']['is_external'] ? 'target="_blank"' : '';
	                
	                if ( $Count % $col == 0 ) {
	                    echo '<div class="row">';
	                }
	                ?> 
	                <div class="col-md">
	                    <div class="item">
	                        <div class="img">
	                            <img src="<?php echo $image_url; ?>"> 
	                        </div>
	                        <div class="cont">
	                            <div class="title"><?php echo $title; ?></div>
	                            <div class="subtitle"><?php echo $subtitle; ?></div>
	                        </div>
	                        <a class="link" href="<?php echo $link_url; ?>" <?php echo $is_external; ?>>
	                        </a>
	                    </div>
	                </div>
	                <?php 
	                $Count++; 
	                if ( $Count % $col == 0 ) {
	                    echo '</div>';
	                }
	            endforeach;
	            if ( $Count % $col != 0 ) {
	                echo '</div>';
	            } // put closing div if loop is not exactly a multiple of 3 
	            ?>
	        </div>
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
	protected function content_template() {
		
		
	}
}


