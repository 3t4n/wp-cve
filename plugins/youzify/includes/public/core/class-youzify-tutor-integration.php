<?php

class Youzify_Tutor_Integration {

    public function __construct() {


		// Register Activity Actions.
		add_action( 'bp_register_activity_actions', array( $this, 'activity_actions' ), 10 );
		add_action( 'transition_post_status', array( $this, 'add_new_course_to_activity_page' ) , 10, 3 );
		add_action( 'transition_post_status', array( $this, 'add_new_enrolled_course_to_activity_page' ) , 10, 3 );
		add_action( 'youzify_show_new_tutor_course', array( $this, 'show_course_box' ) , 10, 3 );
		add_action( 'youzify_show_new_tutor_enrolled_course', array( $this, 'show_enrolled_course_box' ) , 10, 3 );
		add_filter( 'youzify_activity_post_types', array( $this, 'add_activity_post_types' ) );
		add_filter( 'youzify_wall_show_everything_filter_actions', array( $this, 'add_activity_post_types_visibility' ) );
		add_filter( 'bp_get_activity_show_filters_options', array( $this, 'add_activity_post_types' ) );
        add_filter( 'youzify_wall_post_types_visibility', array( $this, 'enable_course_activity_posts' ) );
	
	}

	function show_course_box() {
        
		// Get Activity Type.
		$activity_type = bp_get_activity_type();

		if ( $activity_type == 'new_tutor_course' ) {
			
            require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-tutor-courses.php';

            $courses = new Youzify_Tutor_Courses_Tab();

            $args = array(
				'post_type'		 => 'courses',
				'order' 		 => 'DESC',
				'disable_pagination' => true,
				'post_status'	 => 'publish',
				'posts_per_page' => 1,
				'post__in' 		 => array( bp_get_activity_item_id() )
			);
		
			$courses->courses_core( $args );
        }
	
    }
	
	function show_enrolled_course_box() {
        
		// Get Activity Type.
		$activity_type = bp_get_activity_type();

		if ( $activity_type == 'new_tutor_enrolled_course' ) {

            require_once YOUZIFY_CORE . 'tabs/class-youzify-tab-tutor-courses.php';

            $courses = new Youzify_Tutor_Courses_Tab();

			$args = array(
				'post_type'		 => 'tutor_enrolled',
				'order' 		 => 'DESC',
				'paged' 		 => 1,
				// 'paged' 		 => get_query_var( 'page' ) ? get_query_var( 'page' ) : 1,
				'post_status'	 => 'completed',
				'posts_per_page' => 1,
				'author' 		 => $user_id,
				'fields' 		 => "id=>parent",
				'disable_pagination' => true,
				'post__in' 		 => array( bp_get_activity_item_id() )
			);

			$courses->courses_core( $args );

        }
	
    }
	
	/**
	 * Add Woocommerce Activity Actions.
	 */
	function activity_actions() {

		// Init Vars
		$bp = buddypress();

		bp_activity_set_action(
			$bp->activity->id,
			'new_tutor_course',
			__( 'added a new course', 'youzify' ),
			'youzify_activity_action_wall_posts',
			__( 'Courses', 'youzify' ),
			array( 'activity', 'member' )
		);

		bp_activity_set_action(
			$bp->activity->id,
			'new_tutor_enrolled_course',
			__( 'enrolled in a new course', 'youzify' ),
			'youzify_activity_action_wall_posts',
			__( 'Enrolled Courses', 'youzify' ),
			array( 'activity', 'member' )
		);


	}

	
/**
 * Get Activity Posts Types
 */
function add_activity_post_types_visibility( $post_types ) {

   $post_types[] = 'new_tutor_course';
   $post_types[] = 'new_tutor_enrolled_course';
    
    return $post_types;
}

	/**
	 * Enable Activity Poll Posts Visibility.
	 */
	function enable_course_activity_posts( $post_types ) {
		$post_types['new_tutor_course'] = youzify_option( 'youzify_enable_wall_new_tutor_course' , 'on' );
		$post_types['new_tutor_enrolled_course'] = youzify_option( 'youzify_enable_wall_new_tutor_enrolled_course' , 'on' );
		return $post_types;
	}

	/**
	 * Get Activity Posts Types
	 */
	function add_activity_post_types( $post_types ) {

	   $post_types['new_tutor_course'] = __( 'New Course', 'youzify' );
	   $post_types['new_tutor_enrolled_course'] = __( 'New Enrolled Course', 'youzify' );
	    
	    return $post_types;
	}

	/**
	 * Add prodcut to activity stream.
	 */
	function add_new_course_to_activity_page( $new_status, $old_status, $post ) {

	    if ( ! bp_is_active( 'activity' ) || $post->post_type !== 'courses' || 'publish' !== $new_status || 'publish' === $old_status ) return;

	    $user_link = bp_core_get_userlink( $post->post_author );

	    // Get Activity Action.
	    $action = apply_filters( 'youzify_new_wc_product_action', sprintf( __( '%s added a new course', 'youzify' ), $user_link ), $post->ID );

	    // record the activity
	    bp_activity_add( array(
	        'user_id'   => $post->post_author,
	        'action'    => $action,
	        'item_id'   => $post->ID,
	        'component' => 'activity',
	        'type'      => 'new_tutor_course',
	    ) );

	}

	/**
	 * Add prodcut to activity stream.
	 */
	function add_new_enrolled_course_to_activity_page( $new_status, $old_status, $post ) {

	    if ( ! bp_is_active( 'activity' ) || $post->post_type !== 'tutor_enrolled' || 'completed' !== $new_status || 'completed' === $old_status ) return;

	    $user_link = bp_core_get_userlink( $post->post_author );
	    

	    // Get Activity Action.
	    $action = apply_filters( 'youzify_new_wc_product_action', sprintf( __( '%s enrolled in a new course', 'youzify' ), $user_link ), $post->ID );

	    // record the activity
	    bp_activity_add( array(
	        'user_id'   => $post->post_author,
	        'action'    => $action,
	        'item_id'   => $post->ID,
	        'component' => 'activity',
	        'type'      => 'new_tutor_enrolled_course',
	    ) );

	}

}

new Youzify_Tutor_Integration();