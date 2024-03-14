<?php
/**
 * TP Education Custom Post Type Date Archive rewrite rules
 *
 * @package TP Education
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class TP_Education_Rewrite {

	public function __construct() {
		$options = get_option( 'tp_education_setting_option' );

		if ( isset( $options['enable_event_post_type'] ) ) {
			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_event_rewrite_rules' ) );
		}
		if ( isset( $options['enable_class_post_type'] ) ) {
			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_class_rewrite_rules' ) );
		}
		if ( isset( $options['enable_course_post_type'] ) ) {
			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_course_rewrite_rules' ) );
		}
		if ( isset( $options['enable_team_post_type'] ) ) {
			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_team_rewrite_rules' ) );
		}
		if ( isset( $options['enable_excursion_post_type'] ) ) {

			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_excursion_rewrite_rules' ) );
		}
		if ( isset( $options['enable_testimonial_post_type'] ) ) {
			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_testimonial_rewrite_rules' ) );
		}
		if ( isset( $options['enable_affiliation_post_type'] ) ) {
			add_action( 'generate_rewrite_rules', array( $this, 'tp_education_affiliation_rewrite_rules' ) );
		}
	}

	public function tp_education_event_rewrite_rules( $wp_rewrite )
	{
		// event rewrite rules
	    // Here we're hardcoding the post type event
	    $rules = $this->tp_education_generate_date_archives( 'tp-event', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	public function tp_education_class_rewrite_rules( $wp_rewrite )
	{
		// class rewrite rules
	    // Here we're hardcoding the post type class
	    $rules = $this->tp_education_generate_date_archives( 'tp-class', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	public function tp_education_course_rewrite_rules( $wp_rewrite )
	{
		// course rewrite rules
	    // Here we're hardcoding the post type course
	    $rules = $this->tp_education_generate_date_archives( 'tp-course', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	public function tp_education_team_rewrite_rules( $wp_rewrite )
	{
		// team rewrite rules
	    // Here we're hardcoding the post type team
	    $rules = $this->tp_education_generate_date_archives( 'tp-team', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	public function tp_education_excursion_rewrite_rules( $wp_rewrite )
	{
		// excursion rewrite rules
	    // Here we're hardcoding the post type excursion
	    $rules = $this->tp_education_generate_date_archives( 'tp-excursion', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	public function tp_education_testimonial_rewrite_rules( $wp_rewrite )
	{
		// testimonial rewrite rules
	    // Here we're hardcoding the post type testimonial
	    $rules = $this->tp_education_generate_date_archives( 'tp-testimonial', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	public function tp_education_affiliation_rewrite_rules( $wp_rewrite )
	{
		// affiliation rewrite rules
	    // Here we're hardcoding the post type affiliation
	    $rules = $this->tp_education_generate_date_archives( 'tp-affiliation', $wp_rewrite );
	    $wp_rewrite->rules = $rules + $wp_rewrite->rules;
	    return $wp_rewrite;
	}

	private function tp_education_generate_date_archives( $input, $wp_rewrite )
	{
		/**
		 * Generate date archive rewrite rules for a given custom post type
		 * @param  string $input slug of the custom post type
		 * @return rules       returns a set of rewrite rules for WordPress to handle
		 */
		
	    $rules = array();

	    $post_type = get_post_type_object( $input );
	    $slug_archive = $post_type->has_archive;
	    if ( $slug_archive === false ) {
	        return $rules;
	    }
	    if ( $slug_archive === true ) {
	        // Here's my edit to the original function, let's pick up
	        // custom slug from the post type object if user has
	        // specified one.
	        $slug_archive = $post_type->rewrite['slug'];
	    }

	    $dates = array(
	        array(
	            'rule' => "([0-9]{4})/([0-9]{1,2})/([0-9]{1,2})",
	            'vars' => array('year', 'monthnum', 'day')
	        ),
	        array(
	            'rule' => "([0-9]{4})/([0-9]{1,2})",
	            'vars' => array('year', 'monthnum')
	        ),
	        array(
	            'rule' => "([0-9]{4})",
	            'vars' => array('year')
	        )
	    );

	    foreach ( $dates as $data ) {
	        $query = 'index.php?post_type='.$input;
	        $rule = $slug_archive.'/'.$data['rule'];

	        $i = 1;
	        foreach ( $data['vars'] as $var ) {
	            $query.= '&'.$var.'='.$wp_rewrite->preg_index( $i );
	            $i++;
	        }

	        $rules[$rule."/?$"] = $query;
	        $rules[$rule."/feed/(feed|rdf|rss|rss2|atom)/?$"] = $query."&feed=".$wp_rewrite->preg_index( $i );
	        $rules[$rule."/(feed|rdf|rss|rss2|atom)/?$"] = $query."&feed=".$wp_rewrite->preg_index( $i );
	        $rules[$rule."/page/([0-9]{1,})/?$"] = $query."&paged=".$wp_rewrite->preg_index( $i );
	    }
	    return $rules;
	}

}

new TP_Education_Rewrite();