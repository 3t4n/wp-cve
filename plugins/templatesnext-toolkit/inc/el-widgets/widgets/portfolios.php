<?php
namespace TXElementorAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class tx_portfolio extends Widget_Base {

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
		return 'tx-portfolio';
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
		return __( 'Portfolios', 'tx' );
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
		return 'eicon-gallery-justified';
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
		return [ 'elementor-tx-portfolios' ];
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
				'label' => __( 'Portfolio Settings', 'tx' ),
			]
		);
		$this->add_control(
			'style',
			[
				'label' => __( 'Style', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => [
					'default' => __( 'Default', 'tx' ),
					'gallery' => __( 'Gallery', 'tx' ),
				],
				'default' => 'default',
				'dynamic' => [
					'active' => true,
				],				
			]
		);
		$this->add_control(
			'category',
			[
				'label' => __( 'Category', 'tx' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'options' => tx_get_category_list_el('portfolio-category'),
				'default' => 'all',
				'dynamic' => [
					'active' => true,
				],				
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
			'columns',
			[
				'label' => __( 'Columns', 'tx' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 4,
				'step' => 1,
				'default' => 4,
				'dynamic' => [
					'active' => true,
				],				
			]
		);
		$this->add_control(
			'hide_cat',
			[
				'label' => __( 'Hide Category', 'tx' ),
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
			'hide_excerpt',
			[
				'label' => __( 'Hide Excertp', 'tx' ),
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
			'show_pagination',
			[
				'label' => __( 'Show Pagination', 'tx' ),
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

		$style = $settings['style'];
		$items = $settings['items'];
		$columns = $settings['columns'];
		$hide_cat = $settings['hide_cat'];
		$hide_excerpt = $settings['hide_excerpt'];
		$show_pagination = $settings['show_pagination'];
		$category = $settings['category'];
		$carousel = '';
		
		$blog_term = '';
		
		if ( $hide_cat != "yes" ) {
			$hide_cat = "no";
		}
		if ( $hide_excerpt != "yes" ) {
			$hide_excerpt = "no";
		}
		if ( $show_pagination != "yes" ) {
			$show_pagination = "no";
		}
		if ( $carousel != "yes" ) {
			$carousel = "no";
		}						

		$style_class = '';
		$posts_per_page = intval( $items );
		$total_column = intval( $columns );
		$tx_carousel = $carousel;
		
		$width = 600;
		$height = 480;	
		
		if ( $style == 'gallery' ) {
			$style_class = 'folio-style-gallery';
		}
	
		
		$return_string = '';
		
		$return_string .= '<div class="tx-el-foliowrap">';
	
		if( $tx_carousel == 'no' ) {
			$return_string .= '<div class="tx-portfolio tx-post-row tx-masonry '.esc_attr($style_class).'">';
		} else {
			$return_string .= '<div class="tx-portfolio tx-post-row tx-carousel" data-columns="'.esc_attr($total_column).'">';		
		}
		
		$cat_slug = '';
		
		if ( !empty($category) && $category != 'all' ) {
			$cat_slug = $category;
		}
	  
		wp_reset_query();
		global $post;
		
		$args = array(
			'posts_per_page' => $posts_per_page,
			'post_type' => 'portfolio',
			'orderby' => 'date',
			'order' => 'DESC',
			'portfolio-category' => $cat_slug, //use post ids	
		);
	
		if ( $show_pagination == 'yes' && $carousel == 'no' )
		{
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args['paged'] = $paged;
			$args['prev_text'] = __('&laquo;','tx');
			$args['next_text'] = __('&raquo;','tx');
		}
	
		query_posts( $args );
	
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );			
			$thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			
			if($thumb_image_url) {
				$thumb_image_url = aq_resize( $thumb_image_url[0], $width, $height, true, true, true );		
			}
	
			$return_string .= '<div class="tx-portfolio-item tx-post-col-'.esc_attr($total_column).'"><div class="tx-border-box">';
			
	
			if ( has_post_thumbnail() ) { // check if the post has a Post Thumbnail assigned to it.
				$return_string .= '<div class="tx-folio-img">';
				$return_string .= '<div class="tx-folio-img-wrap"><img src="'.esc_url($thumb_image_url).'" alt="" class="folio-img" /></div>';
				$return_string .= '<div class="folio-links"><span>';	
				$return_string .= '<a href="'.esc_url(get_permalink()).'" class="folio-linkico"><i class="fa fa-link"></i></a>';	
				$return_string .= '<a href="'.esc_url($full_image_url[0]).'" class="folio-zoomico"><i class="fa fa-search-plus"></i></a>';										
				$return_string .= '</span></div>';			
				$return_string .= '</div>';			
			} 
	
			$return_string .= '<span class="folio-head">';
			$return_string .= '<h3 class="tx-folio-title"><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';
			if ( $hide_cat == 'no' ) { // check if the post has a Post Thumbnail assigned to it.
				$return_string .= '<div class="tx-folio-category">'.esc_html(tx_folio_term( 'portfolio-category' )).'</div>';
			} else {
				$return_string .= '<div style="display: block; clear: both; height: 16px;"></div>';
			}
			$return_string .= '</span>';
			if ( $hide_excerpt == 'no' && $style != 'gallery' ) { // check if the post has a Post Thumbnail assigned to it.
				$return_string .= '<div class="tx-folio-content">'.get_the_excerpt().'</div>';
			}
				
			$return_string .= '</div></div>';
		endwhile; else :
			$return_string .= '<div class="tx-noposts"><p>'. esc_html__('Sorry, no portfolio matched your criteria', 'tx') .'<br />';
			$return_string .= esc_html__('Please add few portfolio along with featured images using Dashboard menu "Portfolio" &gt; "Add New"..', 'tx') .'</p></div>';			
		endif;
	  
		$return_string .= '</div>';
		
		if ($show_pagination == 'yes' && $carousel == 'no' ) {	
			$return_string .= '<div class="nx-paging"><div class="nx-paging-inner">'.paginate_links( $args ).'</div></div>';
		}
		
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
