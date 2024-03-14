<?php

/**
 * Add custom cron schedule: Every 3 Minutes.
 *
 * @param array $schedules Existing cron schedules.
 *
 * @return array Modified cron schedules.
 */
function auto_post_exp_add_every_three_minutes( $schedules ) {
	$schedules['every_three_minutes'] = array(
		'interval' => 60,
		'display'  => __( 'Every 3 Minutes', 'auto_post_exp' ),
	);
	return $schedules;
}
add_filter( 'cron_schedules', 'auto_post_exp_add_every_three_minutes' );

// Schedule an action if it's not already scheduled.
if ( ! wp_next_scheduled( 'auto_post_exp_add_every_three_minutes' ) ) {
	wp_schedule_event( time(), 'every_three_minutes', 'auto_post_exp_add_every_three_minutes' );
}

/*
 * Calculate datetime difference.
 */
if ( ! function_exists( 'auto_post_exp_cal_datetime' ) ) {
	/**
	 * Post expiration calculation.
	 *
	 * @package Auto_Post_Expiration
	 * @since 1.0.0
	 *
	 * @param int $post_id The ID of the post.
	 *
	 * @return int The difference between the current time and the expiration date.
	 */
	function auto_post_exp_cal_datetime( $post_id ) {

		$tz = get_option( 'timezone_string' );
		// Change the line below to your timezone!
		date_default_timezone_set( $tz );
		$field_date  = get_post_meta( $post_id, '_expire_date', true );
		$first_date  = new DateTime( current_time( 'mysql' ) );
		$second_date = new DateTime( $field_date );
		$interval    = $first_date->diff( $second_date );
		return $interval->invert;
	}
}

/**
 * Event function for the every_three_minutes cron schedule.
 *
 * @package Auto_Post_Expiration
 * @since 1.0.0
 */
add_action( 'auto_post_exp_add_every_three_minutes', 'auto_post_exp_every_three_minutes_event_func' );
/**
 * Function to perform actions every three minutes for expired posts.
 *
 * @package Auto_Post_Expiration
 * @since 1.0.0
 */
function auto_post_exp_every_three_minutes_event_func() {
	global $post;
		// WP_Query arguments.
	$args = array(
		'post_type'      => 'post',
		'post_status'    => 'publish',
		'posts_per_page' => -1,
	);

	// The Query.
	$query = new WP_Query( $args );
	while ( $query->have_posts() ) :
		$query->the_post();

		$field_date = get_post_meta( $post->ID, '_expire_date', true );
		if ( ! empty( $field_date ) ) {
			$cal_date_time_fn_val = auto_post_exp_cal_datetime( $post->ID );
			if ( $cal_date_time_fn_val == 1 ) {

				// do something.
				$update_post = array(
					'ID'          => $post->ID,
					'post_status' => 'draft',
					'post_type'   => 'post',
				);
				wp_update_post( $update_post );
			}
		}
	endwhile;
}

/**
 * Creating & save meta box
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 * @param WP_Post $post The post object.
 */
function auto_post_exp_expiration_meta_box( $post ) {
	// $screens = array( 'post', 'page' );
	add_meta_box(
		'expiration_date',
		__( 'Expiry Date', 'auto_post_exp' ),
		'auto_post_exp_create_expiration_meta_box_callback', // $callback
		'post',
		'side', // $context
		'low'// $priority
	);
	add_action( 'admin_enqueue_scripts', 'auto_post_exp_add_datepicker_scripts' );
}
add_action( 'add_meta_boxes', 'auto_post_exp_expiration_meta_box' );

/**
 * Enqueue datepicker scripts and styles.
 *
 * @package Auto_Post_Expiration
 * @since 1.0.0
 */
function auto_post_exp_add_datepicker_scripts() {
	wp_enqueue_style(
		'vc-dtpickercss',
		plugin_dir_url( __FILE__ ) . 'admin/datetime/css/jquery.datetimepicker.min.css',
		array(),
		'2.0.0',
		'all'
	);
	wp_enqueue_script(
		'vc-dtpicker',
		plugin_dir_url( __FILE__ ) . 'admin/datetime/js/jquery.datetimepicker.js',
		array( 'jquery' ),
		'2.0.0',
		true
	);
	wp_enqueue_script(
		'vc-datepicker-custom',
		plugin_dir_url( __FILE__ ) . 'admin/js/custom.js',
		array( 'jquery' ),
		'2.0.0',
		true
	);
}

/**
 * Add Fields in meta box
 *
 * @package Auto Post Expiration
 * @since 1.0.0
 * @param WP_Post $post The post object.
 */
function auto_post_exp_create_expiration_meta_box_callback( $post ) {

	// Add a nonce field so we can check for it later.
	wp_nonce_field( 'expire_date_nonce', 'expire_date_nonce' );

	$passed_date = get_post_meta( $post->ID, '_expire_date', true );
	echo '<input type="text" name="expire_date" id="expire_date"   size="35" value="' . esc_attr( $passed_date ) . '"
	readonly>';
}

add_action( 'save_post', 'auto_post_exp_save_expiration_meta_box_data' );
/**
 * Save expiration date meta box data.
 *
 * @package Auto_Post_Expiration
 * @since 1.0.0
 *
 * @param int $post_id The ID of the post.
 */
function auto_post_exp_save_expiration_meta_box_data( $post_id ) {

	// Check if our nonce is set.
	if ( ! isset( $_POST['expire_date_nonce'] ) ) {
		return;
	}

	// Verify that the nonce is valid.
	if ( ! wp_verify_nonce( $_POST['expire_date_nonce'], 'expire_date_nonce' ) ) {
		return;
	}

	// If this is an autosave, our form has not been submitted, so we don't want to do anything.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check the user's permissions.
	if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}
	} elseif ( ! current_user_can( 'edit_post', $post_id ) ) {

			return;
	}

	/* OK, it's safe for us to save the data now. */

	// Make sure that it is set.
	if ( ! isset( $_POST['expire_date'] ) ) {
		return;
	}
	// Sanitize user input.
	$my_date = sanitize_text_field( $_POST['expire_date'] );

	// Update the meta field in the database.
	update_post_meta( $post_id, '_expire_date', $my_date );
}

	/*
	** create custom column
	 * @package Auto Post Expiration
	 * @since 1.0.0
	*/

	add_filter(
		'manage_post_posts_columns',
		function ( $columns ) {
			return array_merge( $columns, array( 'expired' => __( 'Expiry Date', 'auto_post_exp' ) ) );
		}
	);

	add_action(
		'manage_post_posts_custom_column',
		function ( $column_key, $post_id ) {
			if ( 'expired' === $column_key ) {
				$expired_date = get_post_meta( $post_id, '_expire_date', true );

				if ( $expired_date ) {
					$expire_draft_date = auto_post_exp_cal_datetime( $post_id );
					if ( 1 === $expire_draft_date ) {
						echo '<span style="color:red">';
						_e( $expired_date, 'auto_post_exp' );
						echo '</span>';
					} else {
						echo '<span  style="color:green">';
						_e( $expired_date, 'auto_post_exp' );
						echo '</span>';
					}
				}
			}
		},
		10,
		2
	);
