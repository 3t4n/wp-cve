<?php
namespace TXElementorAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
//use Elementor\Core\Schemes\Typography;
use Elementor\Scheme_Typography;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class tx_slider extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tx-slider';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return __( 'Slider', 'tx' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return ' eicon-slider-3d';
	}

	/**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.1.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'templatesnext-addons' ];
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
		return [ 'elementor-tx-slider' ];
	}
	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	 
	 
	protected function register_controls() {
		$this->start_controls_section(
			'section_content',
			[
				'label' => __( 'Slider Settings', 'tx' ),
				'description' => __( 'To Add/Edit slider, go to menu &quot;itrans Slider&quot;', 'tx' ),
			]
		);
		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => tx_get_category_list_el('itrans-slider-category'),
				'default' => 'all',
				'dynamic' => [
					'active' => true,
				],				
			]
		);
		$this->add_control(
			'delay',
			[
				'label' => __( 'Delay', 'tx' ),
				'description' => __( 'Delay between slides', 'tx'),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 60,
				'step' => 1,
				'default' => 8,
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);
		$this->add_control(
			'items',
			[
				'label' => __( 'Number Of Slides', 'tx' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 20,
				'step' => 1,
				'default' => 4,
				'dynamic' => [
					'active' => true,
				],				
			]
		);
		
		$this->add_control(
			'transition',
			[
				'label' => __( 'Slide Transition', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'slide'  		=> __( 'Slide', 'tx' ),
					'fade'  		=> __( 'Fade', 'tx' ),
					'backSlide'  	=> __( 'Back Slide', 'tx' ),
					'goDown'  		=> __( 'Go Down', 'tx' ),
					'fadeUp'  		=> __( 'Fade Up', 'tx' ),					
				],
				'default' => 'slide',
				'dynamic' => [
					'active' => true,
				],				
			]
		);
				
		$this->add_control(
			'title',
			[
				'label' => __( 'Show Slide Title', 'tx' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tx' ),
				'label_off' => __( 'Hide', 'tx' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);
		$this->add_control(
			'desc',
			[
				'label' => __( 'Show Slide Description', 'tx' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tx' ),
				'label_off' => __( 'Hide', 'tx' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);
		$this->add_control(
			'link',
			[
				'label' => __( 'Show Slide Link', 'tx' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tx' ),
				'label_off' => __( 'Hide', 'tx' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);	
		$this->add_control(
			'align',
			[
				'label' => __( 'Content Alignment', 'tx' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'tx' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'tx' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'tx' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'default' => 'center',
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);			
		$this->add_control(
			'height',
			[
				'label' => __( 'Slider Height', 'tx' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 200,
				'max' => 800,
				'step' => 10,
				'default' => 420,
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);
		$this->add_control(
			'fullscreen',
			[
				'label' => __( 'Fullscreen', 'tx' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Show', 'tx' ),
				'label_off' => __( 'Hide', 'tx' ),
				'return_value' => 'yes',
				'default' => '',
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);			
		$this->add_control(
			'textbg',
			[
				'label' => __( 'Slider Image Overlay', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'shadow'  		=> __( 'Shadowed Text', 'tx' ),
					'transparent'  	=> __( 'Semi-transparent Background', 'tx' ),
					'softvignette'  => __( 'Soft Vignette', 'tx' ),
					'hardvignette'  => __( 'Hard Vignette', 'tx' ),
					'darkoverlay'  	=> __( 'Dark Overlay', 'tx' ),
					'pattern'  		=> __( 'Pixel Pattern', 'tx' ),	
					'custombg'  		=> __( 'Custom Overlay', 'tx' ),										
				],
				'default' => 'darkoverlay',
				'dynamic' => [
					'active' => true,
				],				
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => __( 'Image Overlau', 'tx' ),
				'types' => [ 'classic', 'gradient' ],
				'selector' => '{{WRAPPER}} .tx-slide-content',
				'condition' => ['textbg' => 'custombg']
			]
		);		
		
		$this->add_control(
			'parallax',
			[
				'label' => __( 'Parallax', 'tx' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => __( 'Yes', 'tx' ),
				'label_off' => __( 'no', 'tx' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'dynamic' => [
					'active' => true,
				],				
				
			]
		);		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Title', 'tx' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'title_color',
			[
				'label' => __( 'Title Color', 'tx' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tx-slider-box .tx-slide-content .tx-slide-title' => 'color: {{VALUE}}',
				],
			]
		);		
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => __( 'Title Typography', 'tx' ),
				//'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tx-slider-box .tx-slide-content .tx-slide-title',
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section_2',
			[
				'label' => __( 'Content', 'tx' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'content_color',
			[
				'label' => __( 'Content Color', 'tx' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tx-slider .tx-slider-box .tx-slide-content .tx-slide-details p' => 'color: {{VALUE}}',
				],
			]
		);		
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'desc_typography',
				'label' => __( 'Content Typography', 'tx' ),
				//'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tx-slider .tx-slider-box .tx-slide-content .tx-slide-details p',
			]
		);
				
		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section_3',
			[
				'label' => __( 'Button', 'tx' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'button_size',
			[
				'label' => __( 'Button Size', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'xs'  			=> __( 'Extra Small', 'tx' ),
					'sm'  			=> __( 'Small', 'tx' ),
					'md'  			=> __( 'Medium', 'tx' ),
					'lg'  			=> __( 'large', 'tx' ),
					'xl'  			=> __( 'Extra Large', 'tx' ),									
				],
				'default' => 'md',
				'dynamic' => [
					'active' => true,
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
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$category = $settings['category'];
		$delay = $settings['delay'];
		$items = $settings['items'];
		$transition = $settings['transition'];
		$title = $settings['title'];
		$desc = $settings['desc'];
		$link = $settings['link'];
		$align = $settings['align'];
		$height = $settings['height'];
		$fullscreen = $settings['fullscreen'];		
		$textbg = $settings['textbg'];
		$parallax = $settings['parallax'];
		$button_size = $settings['button_size'];

		$return_string = '';
		$cat_slug = '';
		
		if( !empty($category) ){
			$cat_slug = $category;
		}
		$delay = (int)$delay*1000;
		
		if( !empty($category) ){
			$textbg_class = $textbg;
		} else {
			$textbg_class = 'darkoverlay';
		}
		
		$posts_per_page = intval( $items );
		$tx_class = '';
		$tx_delay = $delay;
		$tx_parallax = $parallax;		
		$tx_transition = $transition;
		$tx_title = $title;
		$tx_desc = $desc;
		$tx_link = $link;	
		$tx_align = $align;
		$tx_height = $height;				
		
		
		$return_string .= '<div class="tx-slider tx-el-slider '.esc_attr($textbg_class).'" data-delay="'.esc_attr($tx_delay).'"';
		$return_string .= ' data-parallax="'.esc_attr($tx_parallax).'" data-transition="'.esc_attr($tx_transition).'"';
		$return_string .= ' data-fullscreen="'.esc_attr($fullscreen).'"  data-height="'.esc_attr($tx_height).'">';		
		
		
		wp_reset_query();
		global $post;
		
		if( $category == 'all' ) {
			$args = array(
				'post_type' => 'itrans-slider',
				'posts_per_page' => $posts_per_page,
				'orderby' => 'date', 
				'order' => 'DESC',
				'ignore_sticky_posts' => 1,
			);			
		} else {
			$args = array(
				'post_type' => 'itrans-slider',
				'posts_per_page' => $posts_per_page,
				'orderby' => 'date', 
				'order' => 'DESC',
				'ignore_sticky_posts' => 1,
				'itrans-slider-category' => $cat_slug, //use post ids				
			);
		}
	
		$full_image_url = '';
		$large_image_url = '';
		$image_url = '';
		$width = 1600;
		$height = (int)$tx_height;
	
		query_posts( $args );
	   
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$large_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );	
			
			$image_url = aq_resize( $full_image_url[0], $width, $height, true, false, true );
			//$image_url = tx_image_resize( $full_image_url[0], $width, $height, true, false, true );
	
			$slide_link_text = rwmb_meta('tx_slide_link_text');
			$show_link_url = rwmb_meta('tx_slide_link_url');		
			
			$return_string .= '<div class="tx-slider-item">';
			$return_string .= '<div class="tx-slider-box">';
			
			if ( has_post_thumbnail() ) { 
				$return_string .= '<div class="tx-slider-img" style="background-image: url('.esc_url($image_url['0']).');">';
				$return_string .= '</div>';
			} 

			$return_string .= '<div class="tx-slide-content"><div class="tx-slide-content-inner" style="text-align:'.esc_attr($tx_align).';">';
			if ( $tx_title == 'yes' ) {
				$return_string .= '<h3 class="tx-slide-title">'.esc_html(get_the_title()).'</h3>';
			}
			if ( $tx_desc == 'yes' ) {
				$return_string .= '<div class="tx-slide-details"><p>'.tx_custom_excerpt(32).'</p></div>';
			}
			if ( $tx_link == 'yes' && !empty($slide_link_text) ) {
				$return_string .= '<div class="tx-slide-button"><a class="themebgcolor button-'.esc_attr($button_size).'" href="'.esc_url( $show_link_url ).'">'.esc_attr( $slide_link_text ).'</a></div>';		
			}
			$return_string .= '</div></div></div></div>';		
			
			
		endwhile; else :
			$return_string .= '<div class="tx-noposts"><p>'. esc_html__('Sorry, no slider matched your criteria.', 'tx') .'<br />';
			$return_string .= esc_html__('Please add few slides via Dasboard menu "itrans slider" along with featured image.', 'tx') .'</p></div>';
		endif;
	  
		$return_string .= '</div>';
	
		wp_reset_query();
		
		echo $return_string;

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.1.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
}
