<?php
namespace TXElementorAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
//use Elementor\Core\Schemes\Typography;
use Elementor\Scheme_Typography;




if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class tx_testimonials extends Widget_Base {

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
		return 'tx-testimonials';
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
		return __( 'Testimonials', 'tx' );
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
		return [ 'elementor-tx-testimonials' ];
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
				'label' => __( 'Testimonials Settings', 'tx' ),
			]
		);
		$this->add_control(
			'items',
			[
				'label' => __( 'Items', 'tx' ),
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
			'delay',
			[
				'label' => __( 'Delay In seconds', 'tx' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 2,
				'max' => 40,
				'step' => 1,
				'default' => 8,
				'dynamic' => [
					'active' => true,
				],				
			]
		);		

		$this->end_controls_section();
		
		$this->start_controls_section(
			'style_section',
			[
				'label' => __( 'Testimonial Typography', 'tx' ),
				'tab' => \Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'tx' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .tx-testiin2 .tx-testi2-item span.tx-testi2-text' => 'color: {{VALUE}}',
				],
			]
		);		
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Text Typography', 'tx' ),
				//'scheme' => Scheme_Typography::TYPOGRAPHY_1,
				'scheme' => \Elementor\Core\Schemes\Typography::TYPOGRAPHY_1,
				'selector' => '{{WRAPPER}} .tx-testiin2 .tx-testi2-item span.tx-testi2-text',
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

		$return_string = '';
		
		$posts_per_page = intval( $settings['items'] );
		$columns = 4;
		$delay = intval( $settings['delay'] )*1000;
		$align = $settings['align'];	
			
		$tx_class = '';	
		$posts_per_page = intval( $posts_per_page );
		$width = 200;
		$height = 200;		
		
		$return_string = '';
		$return_string .= '<div class="tx-testiin2 tx-align-'.esc_attr($align).'" data-delay="' . esc_attr($delay) . '">';
	 
	  
		wp_reset_query();
		global $post;
		
		$args = array(
			'posts_per_page' => $posts_per_page,
			'post_type' => 'testimonials',
			'fullwidth' => 0,		
			'orderby' => 'date', 
			'order' => 'DESC'
		);
	
		query_posts( $args );   
	   
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			$testi_name = esc_attr(rwmb_meta('tx_testi_name'));
			$testi_desig = esc_attr(rwmb_meta('tx_testi_desig'));
			$testi_organ = esc_attr(rwmb_meta('tx_testi_company'));
			
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			
			if ( !empty($full_image_url[0]) ) {
				$image_url = aq_resize( $full_image_url[0], $width, $height, true, false, true );
			} else {
				$image_url['0'] = plugin_dir_url( __FILE__ ) . 'no-image.png';	
			}
	
			$return_string .= '<div class="tx-testi2-item">';
			$return_string .= '<span class="tx-testi2-text" style="text-align: '.esc_attr($align).'">'.get_the_content().'</span>';
			
			$return_string .= '<span class="cite-wrap" style="text-align: '.esc_attr($align).'"><span class="cite-wrap-inner">';
			$return_string .= '<span class="tx-testi2-photo"><img src="'.esc_url($image_url['0']).'" alt="'.$testi_name.'" /></span>';
			$return_string .= '<span class="tx-testi2-name themecolor">'.$testi_name.'</span>';
			$return_string .= '<span class="tx-testi2-desig">'.$testi_desig.', </span>';
			$return_string .= '<span class="tx-testi2-org">'.$testi_organ.'</span>';
			$return_string .= '</span></span>';						
			
			$return_string .= '</div>';
		endwhile; else :
			$return_string .= '<div class="tx-noposts"><p>'. esc_html__('Sorry, no testimonial matched your criteria.', 'tx') .'<br />';
			$return_string .= esc_html__('Add few testimonials using Dashboard menu "Testimonials" &gt; "Add New"..', 'tx') .'</p></div>';			
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
