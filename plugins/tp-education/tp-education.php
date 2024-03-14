<?php
/**
 * Plugin Name: TP Education
 * Plugin URI: https://www.themepalace.com/plugins/tp_education
 * Description: A plugin to add custom post type ( Events, Courses, Classes, Excursions, Team, Testimonial, Affiliation ) and it's required meta fields for educational sites. This plugin is dedicated for educational themes.
 * Version: 4.5
 * Author: Theme Palace
 * Author URI: https://themepalace.com
 * Requires at least: 4.7
 * Tested up to: 6.2
 * Requires PHP : 5.6
 * Text Domain: tp-education
 * Domain Path: /languages/
 *
 * @package TP Education
 * @category Core
 * @author Theme Palace
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'TP_Education' ) ) :

	final class TP_Education {

		public function __construct()
		{
			$this->tp_education_constant();
			$this->tp_education_includes();
			$this->tp_education_hooks();
			$this->tp_education_install_uninstall_hook();
		}

		public function tp_education_constant()
		{
			define( 'TP_EDUCATION_BASE_PATH', dirname(__FILE__ ) );
			define( 'TP_EDUCATION_URL_PATH', plugin_dir_url(__FILE__ ) );
			define( 'TP_EDUCATION_PLUGIN_BASE_PATH', plugin_basename(__FILE__) );
			define( 'TP_EDUCATION_PLUGIN_FILE_PATH', (__FILE__) );
		}

		public function tp_education_install_uninstall_rewrite()
		{
	        /*
	         * flush rewrite rules 
	        */
	        register_activation_hook( TP_EDUCATION_PLUGIN_FILE_PATH, array( 'TP_Education', 'tp_education_rewrite' ) );
	        register_deactivation_hook( TP_EDUCATION_PLUGIN_FILE_PATH, array( 'TP_Education', 'tp_education_rewrite' ) );
	    }

	    private function tp_education_install_uninstall_hook()
		{
			/*
			 * Activation and Deactivation hook
			 */

			add_action( 'init', array( $this, 'tp_education_install_uninstall_rewrite' ) );

		}

	    public function tp_education_add_action_links ( $links )
		{
			/*
			 * Add Support link to plugin action
			 */
			$mylinks = array(
				'<a href="' . admin_url( 'options-general.php?page=tp-education-admin' ) . '">' . __( 'Settings', 'tp-education' ) . '</a>',
			);
			return array_merge( $links, $mylinks );
		}

	    static function tp_education_rewrite()
	    {
	    	flush_rewrite_rules( $hard = false );
	    }

		public function tp_education_includes()
		{
			$options = get_option( 'tp_education_setting_option' );
			/*
			 * Setting Page
			 */
			include_once TP_EDUCATION_BASE_PATH . '/includes/tp-education-setting-page.php';

			/*
			 * CUSTOM POST TYPE
			 */

			// Courses Post Type
			if ( isset( $options['enable_course_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-courses.php';
			endif;

			// Classes Post Type
			if ( isset( $options['enable_class_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-classes.php';
			endif;

			// Events Post Type
			if ( isset( $options['enable_event_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-events.php';
			endif;

			// Excursions Post Type
			if ( isset( $options['enable_excursion_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-excursions.php';
			endif;

			// Team Post Type
			if ( isset( $options['enable_team_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-team.php';
			endif;

			// Testimonial Post Type
			if ( isset( $options['enable_testimonial_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-testimonial.php';
			endif;

			// Affiliation Post Type
			if ( isset( $options['enable_affiliation_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-post-type/class-tp-affiliation.php';
			endif;


			/*
			 * META BOX
			 */

			// Classes Meta Box
			if ( isset( $options['enable_class_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-classes-metabox.php';
			endif;

			// Testimonial Meta Box
			if ( isset( $options['enable_testimonial_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-testimonial-metabox.php';
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-testimonial-social-metabox.php';
			endif;

			// Team Meta Box
			if ( isset( $options['enable_team_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-team-metabox.php';
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-team-social-metabox.php';
				include_once TP_EDUCATION_BASE_PATH . '/tp-widget/class-tp-team-widget.php';
			endif;

			// Events Meta Box
			if ( isset( $options['enable_event_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-events-metabox.php';
			endif;

			// Excursion Meta Box
			if ( isset( $options['enable_excursion_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-excursion-metabox.php';
			endif;

			// Course Meta Box
			if ( isset( $options['enable_course_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-course-metabox.php';
				include_once TP_EDUCATION_BASE_PATH . '/tp-widget/class-tp-featured-course-widget.php';
			endif;

			// Affiliation Meta Box
			if ( isset( $options['enable_affiliation_post_type'] ) ) :
				include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-affiliation-metabox.php';
				include_once TP_EDUCATION_BASE_PATH . '/tp-widget/class-tp-top-affiliation-widget.php';
			endif;

			// Like Meta Box
			include_once TP_EDUCATION_BASE_PATH . '/tp-metabox/class-tp-like-metabox.php';

			/*
			 * TEMPLATE PARTS
			 */

			if ( isset( $options['enable_event_post_type'] ) ) :
				// Event Search form tab
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-search-tab-event-form.php';
				// Content Event
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-event.php';
			endif;

			if ( isset( $options['enable_excursion_post_type'] ) ) :
				// Excursion Search form tab
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-search-tab-excursion-form.php';
				// Content Excursion
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-excursion.php';
			endif;

			if ( isset( $options['enable_course_post_type'] ) ) :
				// Course Search form tab
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-search-tab-course-form.php';
				// Content Course
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-course.php';
			endif;

			if ( isset( $options['enable_team_post_type'] ) ) :
				// Team Search form tab
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-search-tab-team-form.php';
				// Content Team
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-team.php';
			endif;

			if ( isset( $options['enable_class_post_type'] ) ) :
				// Class Search form tab
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-search-tab-class-form.php';
				// Content Class
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-class.php';
			endif;

			if ( isset( $options['enable_affiliation_post_type'] ) ) :
				// Affiliation Search form tab
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-search-tab-affiliation-form.php';
				// Content Affiliation
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-affiliation.php';
			endif;

			if ( isset( $options['enable_testimonial_post_type'] ) ) :
				// Content Testimonial
				include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-testimonial.php';
			endif;

			// Content Single
			include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-single.php';

			// Related Posts
			include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-related-posts.php';

			// Content Search
			include_once TP_EDUCATION_BASE_PATH . '/template-parts/tp-content-search.php';

			/*
			 * FUNCTIONS
			 */

			// Custom Post Type Date Archive Rewrite Rules
			include_once TP_EDUCATION_BASE_PATH . '/includes/tp-education-rewrite.php';

			// Core functions
			include_once TP_EDUCATION_BASE_PATH . '/includes/tp-education-functions.php';

			// Core functions
			include_once TP_EDUCATION_BASE_PATH . '/includes/tp-education-return-functions.php';

			// Hooks
			include_once TP_EDUCATION_BASE_PATH . '/includes/tp-education-hooks.php';

			// Shortcode
			include_once TP_EDUCATION_BASE_PATH . '/includes/tp-education-shortcode.php';

		}

		public function tp_education_hooks()
		{
			/*
			 * HOOKS
			 */

			// add setting action in plugin page
			add_filter( 'plugin_action_links_' .  TP_EDUCATION_PLUGIN_BASE_PATH, array( $this, 'tp_education_add_action_links' ) );

			// register widget
			add_action( 'widgets_init', array( $this, 'tp_education_register_widgets' ) );

			// enqueue admin scripts
			add_action( 'wp_enqueue_scripts', array( $this, 'tp_education_enqueue' ) );

			// enqueue admin scripts
			add_action( 'admin_enqueue_scripts', array( $this, 'tp_education_admin_enqueue' ) );

			// custom template
			add_filter( 'template_include', array( $this,'tp_education_set_template' ) );

			// custom post type search template
			add_filter( 'template_include', array( $this, 'tp_education_set_search_template' ) );

			// add search filter
			add_filter( 'pre_get_posts', array( $this, 'tp_education_filter_search_query' ) );

		}

		public function tp_education_register_widgets() {
			$options = get_option( 'tp_education_setting_option' );

			// Team Widget
			if ( isset( $options['enable_team_post_type'] ) ) :
				register_widget( 'Tp_Education_Team_Widget' );
			endif;

			// Affiliation Widget
			if ( isset( $options['enable_affiliation_post_type'] ) ) :
				register_widget( 'Tp_Education_Top_Affiliation_Widget' );
			endif;

			// Course Widget
			if ( isset( $options['enable_course_post_type'] ) ) :
				register_widget( 'Tp_Education_Featured_Course_Widget' );
			endif;
		}

		public function tp_education_enqueue()
		{
			/*
			 * Enqueue scripts
			 */

			// Load font awesome
            wp_enqueue_style( 'font-awesome', TP_EDUCATION_URL_PATH  . 'assets/css/font-awesome.min.css' );

            // Load simple date picker css
	        wp_enqueue_style( 'jquery-ui', TP_EDUCATION_URL_PATH  . 'assets/css/jquery-ui.min.css' );

            // Load tp education style
            wp_enqueue_style( 'tp-education-style', TP_EDUCATION_URL_PATH  . 'assets/css/tp-education-style.min.css' );

            // Load jquery-ui js
			wp_enqueue_script( 'jquery-ui-datepicker' );

            // Load tp education custom js
	        wp_enqueue_script( 'tp-education-custom', TP_EDUCATION_URL_PATH  . 'assets/js/tp-education-custom.min.js', array( 'jquery', 'jquery-ui-datepicker' ), '', true );

		}

		public function tp_education_admin_enqueue( $hook )
		{
			/*
			 * Enqueue admin scripts
			 */

			// Load tp education style
            wp_enqueue_style( 'tp-education-style', TP_EDUCATION_URL_PATH  . 'assets/css/tp-education-admin-style.min.css' );

	        if ( 'post.php' == $hook || 'post-new.php' == $hook ) :
	            // Load simple date picker css
	            wp_enqueue_style( 'jquery-ui', TP_EDUCATION_URL_PATH  . 'assets/css/jquery-ui.min.css' );

	            // Load time picker css
	            wp_enqueue_style( 'jquery-timepicker-css', TP_EDUCATION_URL_PATH  . 'assets/css/jquery-timepicker.min.css' );

		        // Load time picker js
	            wp_enqueue_script( 'jquery-timepicker', TP_EDUCATION_URL_PATH  . 'assets/js/jquery-timepicker.min.js', array( 'jquery' ), '', true );

	            // Load admin custom js
	            wp_enqueue_script( 'tp-education-admin-custom', TP_EDUCATION_URL_PATH  . 'assets/js/admin-custom.min.js', array( 'jquery', 'jquery-ui-datepicker' ), '', true );
            endif;

		}

		public function tp_education_set_template( $template )
		{
			if ( is_post_type_archive( 'tp-event' ) || is_tax('tp-event-category') ) :
				if ( locate_template( 'tp-education/tp-archive-event.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-event.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-event.php';
			endif;

			if ( is_post_type_archive( 'tp-class' ) || is_tax('tp-class-category') ) :
				if ( locate_template( 'tp-education/tp-archive-class.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-class.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-class.php';
			endif;

			if ( is_post_type_archive( 'tp-excursion' ) || is_tax('tp-excursion-category') ) :
				if ( locate_template( 'tp-education/tp-archive-excursion.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-excursion.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-excursion.php';
			endif;

			if ( is_post_type_archive( 'tp-team' ) || is_tax('tp-team-category') ) :
				if ( locate_template( 'tp-education/tp-archive-team.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-team.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-team.php';
			endif;

			if ( is_post_type_archive( 'tp-course' ) || is_tax('tp-course-category') ) :
				if ( locate_template( 'tp-education/tp-archive-course.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-course.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-course.php';
			endif;

			if ( is_post_type_archive( 'tp-testimonial' ) ) :
				if ( locate_template( 'tp-education/tp-archive-testimonial.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-testimonial.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-testimonial.php';
			endif;

			if ( is_post_type_archive( 'tp-affiliation' ) || is_tax('tp-affiliation-category') ) :
				if ( locate_template( 'tp-education/tp-archive-affiliation.php' ) != '' )
					$template = locate_template( 'tp-education/tp-archive-affiliation.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-affiliation.php';
			endif;

			if( is_singular( 'tp-testimonial' ) ) :
				if ( locate_template( 'tp-education/tp-single-testimonial.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-testimonial.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			if( is_singular( 'tp-team' ) ) :
				if ( locate_template( 'tp-education/tp-single-team.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-team.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			if( is_singular( 'tp-class' ) ) :
				if ( locate_template( 'tp-education/tp-single-class.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-class.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			if( is_singular( 'tp-event' ) ) :
				if ( locate_template( 'tp-education/tp-single-event.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-event.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			if( is_singular( 'tp-excursion' ) ) :
				if ( locate_template( 'tp-education/tp-single-excursion.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-excursion.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			if( is_singular( 'tp-course' ) ) :
				if ( locate_template( 'tp-education/tp-single-course.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-course.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			if( is_singular( 'tp-affiliation' ) ) :
				if ( locate_template( 'tp-education/tp-single-affiliation.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-affiliation.php' );
				elseif ( locate_template( 'tp-education/tp-single-post.php' ) != '' )
					$template = locate_template( 'tp-education/tp-single-post.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-single-post.php';
			endif;

			return $template;
		}

		public function tp_education_set_search_template( $template )
		{
			global $wp_query;
			$tp_education_post_type = array( 'tp-event', 'tp-course', 'tp-excursion', 'tp-team', 'tp-class', 'tp-affiliation' );
			$post_type = get_query_var( 'post_type' );
			if ( $wp_query->is_search && in_array( $post_type, $tp_education_post_type ) )
			{
				if ( locate_template( 'tp-education/tp-archive-search.php' ) != '' )
					$template =  locate_template( 'tp-education/tp-archive-search.php' );
				else
					$template = TP_EDUCATION_BASE_PATH . '/templates/tp-archive-search.php';
			}
			return $template;
		}

		public function tp_education_filter_search_query( $query )
		{
			global $wp_query;
			$post_type = ! empty( $_GET['post_type'] ) ? $_GET['post_type'] : get_query_var( 'post_type' );
			$term = ! empty( $_GET['tp_term'] ) ? $_GET['tp_term'] : get_query_var( 'term' );
			if ( $wp_query->is_search && !is_admin() && $query->is_main_query() ) {
				switch ( $post_type ) {
					case 'tp-event':
						$date = $_GET['date'];
						if ( ! empty( $term ) ) {
							$taxquery = array(
					            array(
					               'taxonomy' => 'tp-event-category',
					               'field' => 'slug',
					               'terms' => $term
					            )
				            );
							$query->set( 'tax_query', $taxquery );
						}
						if ( ! empty( $date ) ) {
							$metaquery = array(
						        array(
						            'key'     => 'tp_event_date_value',
						            'value'   => $date,
						            'type'	  => time(),
						            'compare' => '=',
						        )
						    );
						    $query->set( 'meta_query', $metaquery );
					    }
					break;

					case 'tp-course':
						if ( ! empty( $term ) ) {
							$taxquery = array(
					            array(
					               'taxonomy' => 'tp-course-category',
					               'field' => 'slug',
					               'terms' => $term
					            )
				            );
							$query->set( 'tax_query', $taxquery );
						}
					break;

					case 'tp-excursion':
						$date = $_GET['date'];
						if ( ! empty( $term ) ) {
							$taxquery = array(
					            array(
					               'taxonomy' => 'tp-excursion-category',
					               'field' => 'slug',
					               'terms' => $term
					            )
				            );
							$query->set( 'tax_query', $taxquery );
						}
						if ( ! empty( $date ) ) {
							$metaquery = array(
						        array(
						            'key'     => 'tp_excursion_start_date_value',
						            'value'   => $date,
						            'type'	  => time(),
						            'compare' => '=',
						        )
						    );
						    $query->set( 'meta_query', $metaquery );
					    }
					break;

					case 'tp-team':
						if ( ! empty( $term ) ) {
							$taxquery = array(
					            array(
					               'taxonomy' => 'tp-team-category',
					               'field' => 'slug',
					               'terms' => $term
					            )
				            );
							$query->set( 'tax_query', $taxquery );
						}
					break;

					case 'tp-class':
						if ( ! empty( $term ) ) {
							$taxquery = array(
					            array(
					               'taxonomy' => 'tp-class-category',
					               'field' => 'slug',
					               'terms' => $term
					            )
				            );
							$query->set( 'tax_query', $taxquery );
						}
					break;

					case 'tp-affiliation':
						if ( ! empty( $term ) ) {
							$taxquery = array(
					            array(
					               'taxonomy' => 'tp-affiliation-category',
					               'field' => 'slug',
					               'terms' => $term
					            )
				            );
							$query->set( 'tax_query', $taxquery );
						}
					break;

					default:
					break;
				}
			}
			return $query;

		}

	}

	new TP_Education();

endif;
