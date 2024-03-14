<?php
namespace TXElementorAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class tx_posts extends Widget_Base {

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
		return 'tx-posts';
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
		return __( 'Posts Grid', 'tx' );
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
		return 'eicon-posts-grid';
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
		return [ 'elementor-tx-posts' ];
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
				'label' => __( 'Post Grid Settings', 'tx' ),
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
			'showcat',
			[
				'label' => __( 'Show Category', 'tx' ),
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

		$this->add_control(
			'show_author',
			[
				'label' => __( 'Show Author', 'tx' ),
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
			'show_date',
			[
				'label' => __( 'Show Date Published', 'tx' ),
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
			'show_comm',
			[
				'label' => __( 'Show number of Comment Published', 'tx' ),
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

		
		$items = $settings['items'];
		$columns = $settings['columns'];
		$showcat = $settings['showcat'];
		$show_pagination = $settings['show_pagination'];

		$show_author = $settings['show_author'];
		$show_date = $settings['show_date'];
		$show_comm = $settings['show_comm'];

		$category_id = '';
		
		$blog_term = '';
		
		if ( $showcat == "yes" ) {
			$showcat = "show";
		} else {
			$showcat = "hide";
		}
		if ( $show_pagination != "yes" ) {
			$show_pagination = "no";
		}

		$width = 600;
		$height = 360;
		
		$post_in_cat = tx_shortcodes_comma_delim_to_array( $category_id );
		$post_comments = '';
	
		$posts_per_page = intval( $items );
		$total_column = intval( $columns );
		$tx_category = $showcat;
		
		$return_string = '';
		
		$return_string .= '<div class="tx-el-blog tx-blog tx-post-row tx-masonry">';
				
		wp_reset_query();
		global $post;
		
		$args = array(
			'posts_per_page' => $posts_per_page,
			'orderby' => 'date', 
			'order' => 'DESC',
			'ignore_sticky_posts' => 1,
			'category__in' => $post_in_cat, //use post ids		
		);
	
		if ( $show_pagination == 'yes' ) {	
			$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
			$args['paged'] = $paged;
			$args['prev_text'] = __('&laquo;','tx');
			$args['next_text'] = __('&raquo;','tx');
			$args['show_all'] = false;
		}
	
		
		query_posts( $args );
	   
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			$post_comments = get_comments_number();
				
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'large' );	
			$thumb_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			
			if( $thumb_image_url ) {
				$thumb_image_url = aq_resize( $thumb_image_url[0], $width, $height, true, true, true );
			}
		
			$return_string .= '<div class="tx-blog-item tx-post-col-'.esc_attr($total_column).'"><div class="tx-border-box">';
	
			if ( has_post_thumbnail() ) {
				
				$return_string .= '<div class="tx-blog-img">';
				
				$return_string .= '<div class="txpost-links"><span>';	
				$return_string .= '<a href="'.esc_url(get_permalink()).'" class="txpoxt-linkico themebgcolor"><i class="fa fa-link"></i></a>';	
				$return_string .= '<a href="'.esc_url($full_image_url[0]).'" class="txpoxt-zoomico themebgcolor"><i class="fa fa-search-plus"></i></a>';										
				$return_string .= '</span></div>';

				$return_string .= '<img src="'.esc_url($thumb_image_url).'" alt="" class="blog-image" />';

				$return_string .= '</div>';
								
			} else {
				$return_string .= '<div class="tx-blog-imgpad"></div>';
			}			
			
			
			
			$return_string .= '<div class="tx-post-content"><h3 class="tx-post-title"><a href="'.esc_url(get_permalink()).'">'.esc_html(get_the_title()).'</a></h3>';
			
			if ( $tx_category == "show" ) {
				$return_string .= '<div class="tx-category">'.get_the_category_list( ', ' ).'</div>';	
			} else {
				$return_string .= '<div style="height: 16px;"></div>';
			}
			
			$return_string .= '<div class="tx-blog-content">'.get_the_excerpt().'</div>';
			
			/*
			$return_string .= '<div class="tx-meta">';
			$return_string .= '<span class="tx-author"><a href="'.esc_url( get_author_posts_url( get_the_author_meta("ID") ) ).'">'.get_the_author().'</a></span>';
			$return_string .= '<span class="tx-date"> | '.get_the_date('M j, Y').'</span>';
			$return_string .= '</div>';
			*/
			
			$return_string .= '<div class="tx-meta">';
			if ( $show_author == "yes" ) {
				$return_string .= '<span class="tx-author"><a href="'.esc_url( get_author_posts_url( get_the_author_meta("ID") ) ).'">'.esc_html(get_the_author()).'</a></span>';
			}
			if ( $show_date == "yes" ) {
				$return_string .= '<span class="tx-date"> | '.esc_html(get_the_date('M j, Y')).'</span>';
			}
			if ( $show_comm == "yes" ) {
				$return_string .= '<span class="tx-post-comm"><span>'.esc_html($post_comments).'</span></span>';
			}
			$return_string .= '</div>';			
			
			$return_string .= '</div></div></div>';		
			
			
		endwhile; else :
			$return_string .= '<div class="tx-noposts"><p>'. esc_html__('Sorry, no posts matched your criteria. Please add some posts with featured images.', 'tx') .'</p></div>';
		endif;
	  
		$return_string .= '</div>';
	
		if ( $show_pagination == 'yes' ) {
			$return_string .= '<div class="nx-paging"><div class="nx-paging-inner">'.paginate_links( $args ).'</div></div>';
		}
	
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
