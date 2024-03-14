<?php
namespace TXElementorAddons\Widgets;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @since 1.1.0
 */
class tx_team extends Widget_Base {

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
		return 'tx-team';
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
		return __( 'Team', 'tx' );
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
		return 'eicon-person';
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
		return [ 'elementor-tx-team' ];
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
				'label' => __( 'Team Settings', 'tx' ),
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
		$columns = intval( $settings['columns'] );	
		$tx_class = '';	
		
		$return_string .= '<div class="tx-team tx-'.$columns.'-column-team">';		
		
		wp_reset_query();
		global $post;
		
		$args = array(
			'post_type' => 'team',
			'posts_per_page' => $posts_per_page,
			'orderby' => 'date', 
			'order' => 'DESC',
			'ignore_sticky_posts' => 1,
		);
	
		$full_image_url = '';
		$large_image_url = '';
		$image_url = '';
		$width = 400;
		$height = 400;
	
		query_posts( $args );
	   
		if ( have_posts() ) : while ( have_posts() ) : the_post();
		
			$full_image_url = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
			$image_url = aq_resize( $full_image_url[0], $width, $height, true, false, true );
	
			$designation = esc_attr(rwmb_meta('tx_designation'));
			$team_email = esc_attr(rwmb_meta('tx_team_email'));
			$team_phone = esc_attr(rwmb_meta('tx_team_phone'));
			$team_twitter = esc_url(rwmb_meta('tx_team_twitter'));
			$team_facebook = esc_url(rwmb_meta('tx_team_facebook'));
			$team_gplus = esc_url(rwmb_meta('tx_team_gplus'));
			$team_skype = esc_attr(rwmb_meta('tx_team_skype'));
			$team_linkedin = esc_url(rwmb_meta('tx_team_linkedin'));								
			
			$return_string .= '<div class="tx-team-item">';
			$return_string .= '<div class="tx-team-box">';
			
			if ( has_post_thumbnail() ) { 
				$return_string .= '<div class="tx-team-img">';
				$return_string .= '<img src="'.esc_url($image_url['0']).'" alt="" class="team-image" />';
				
				/*
				if($team_email) { $return_string .= '<span class="tx-temail">'.$team_email.'</span>'; }
				if($team_phone) { $return_string .= '<span class="tx-phone">'.$team_phone.'</span>'; }
				*/
				$return_string .= '<div class="tx-team-socials">';
				if($team_twitter) { $return_string .= '<span class="tx-twitter"><a href="'.$team_twitter.'"><i class="fa fa-twitter"></i></a></span>'; }
				if($team_facebook) { $return_string .= '<span class="tx-facebook"><a href="'.$team_facebook.'"><i class="fa fa-facebook"></i></a></span>'; }
				if($team_gplus) { $return_string .= '<span class="tx-gplus"><a href="'.$team_gplus.'"><i class="fa fa-google-plus"></i></a></span>'; }
				if($team_skype) { $return_string .= '<span class="tx-skype"><a href="skype:'.$team_skype.'"><i class="fa fa-skype"></i></a></span>'; }
				if($team_linkedin) { $return_string .= '<span class="tx-linkedin"><a href="'.$team_linkedin.'"><i class="fa fa-linkedin"></i></a></span>'; }
				$return_string .= '</div>';			
				
				$return_string .= '</div>';
			} 
			/**/
			$return_string .= '<div class="tx-team-content"><div class="tx-team-content-inner" style="">';
			$return_string .= '<h3 class="">'.esc_html(get_the_title()).'</h3>';
			$return_string .= '<div class="desig">'.$designation.'</div>';		
			$return_string .= '</div></div></div>';
			$return_string .= '</div>';		
			
			
		endwhile; else :
			$return_string .= '<div class="tx-noposts"><p>'. esc_html__('Sorry, no team member matched your criteria.', 'tx') .'<br />';
			$return_string .= esc_html__('Please add few team member along with featured image using Dashboard menu "Team" &gt; "Add New".', 'tx') .'</p></div>';
		endif;
		
		$return_string .= '<div class="clear"></div>';	  
		
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
