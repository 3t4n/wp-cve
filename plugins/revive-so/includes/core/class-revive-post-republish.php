<?php
/**
 * The Main file.
 *
 */

defined( 'ABSPATH' ) || exit;

/**
 * Republication class.
 */
class REVIVESO_PostRepublish
{
	use REVIVESO_HelperFunctions;
    use REVIVESO_Hooker;
    use REVIVESO_Scheduler;

	/**
	 * Register functions.
	 */
	public function register() {
		$this->action( 'reviveso_global_republish_single_post', 'do_republish' );
		$this->action( 'reviveso_as_action_removed', 'remove_meta' );
	}

	/**
	 * Trigger post update process.
	 * 
	 * @since 1.0.0
	 * @param int   $post_id   Post ID
	 */
	public function do_republish( $post_id ) {
		// delete data.
		$this->delete_meta( $post_id, 'reviveso_global_republish_status' );
		$this->delete_meta( $post_id, '_reviveso_global_republish_datetime' );
		$this->remove_meta( $post_id );

		// Republish.
		$this->handle( (int) $post_id );
	}

	/**
	 * Delete post meta data flags.
	 * 
	 * @since 1.0.0
	 * @param int   $post_id   Post ID
	 */
	public function remove_meta( $post_id ) {
		$this->delete_meta( $post_id, 'reviveso_republish_as_action_id' );

		$this->do_action( 'remove_post_meta', $post_id );

	}

	/**
	 * Handle Trigger post update process.
	 *
	 * Override this method to perform any actions required
	 * during the async request.
	 */
	private function handle( int $post_id ) {
		$action = $this->do_filter( 'republish_action', 'repost', false, $post_id );
		if ( $action === 'repost' ) {
			$this->update_old_post( $post_id );
		}
	}

	/**
	 * Run post update process.
	 * 
	 * @param int   $post_id        Post ID
	 * @param bool  $single         Check if it is a single republish event
	 * @param bool  $instant        Check if it is one click republish event
	 * @param bool  $only_update    Check if it is update date/time event
	 * @param bool  $external       Check if it is external custom event
	 * 
	 * @return int $post_id
	 */
	public function update_old_post( int $post_id, bool $single = false, bool $instant = false, bool $only_update = false, bool $external = false ) {
		$post = \get_post( $post_id );
		$new_time = $this->get_publish_time( $post->ID, $single );

		if ( ! $only_update ) {
			$pub_date = $this->get_meta( $post->ID, '_reviveso_original_pub_date' );
			if ( ! $pub_date && ( $post->post_status !== 'future' ) ) {
				$this->update_meta( $post->ID, '_reviveso_original_pub_date', $post->post_date );
			}
			$this->update_meta( $post->ID, '_reviveso_last_pub_date', $post->post_date );
		}

		// remove kses filters
		\kses_remove_filters();

        $args = array(
	    	'post_date'     => $new_time,
	    	'post_date_gmt' => get_gmt_from_date( $new_time ),
	    );

		$args = array_merge( array( 'ID' => $post->ID ), $args );
		$args = $this->do_filter( 'update_process_args', $args, $post->ID, $post, $new_time, $only_update );

		wp_update_post( $args );

		/**
		 * Fires after post is updated.
		 *
		 * @hook  reviveso_update_old_post
		 *
		 * @param  int   $post_id      Post ID
		 * @param  int   $new_time     New post published time
		 * @param  bool  $only_update  Check if it is update date/time event
		 * @param  bool  $post         Post object
		 *
		 * @since 1.0.0
		 *
		 * @since 1.0.4 - added the $post parameter
		 */
		$this->do_action( 'update_old_post', $post->ID, $new_time, $only_update, $post );

		// reinit kses filters
		\kses_init_filters();

		return $post_id;
	}

	/**
	 * Get new post published time.
	 * 
	 * @since 1.0.0
	 * @param int   $post_id   Post ID
	 * @param bool  $single    Check if a single republish event
	 * @param bool  $scheduled Check if scheduled republish event
	 * 
	 * @return string
	 */
	public function get_publish_time( int $post_id, bool $single = false, bool $scheduled = false ) {
		$post = \get_post( $post_id );
    	$timestamp = $this->current_timestamp();
		$interval = MINUTE_IN_SECONDS * $this->do_filter( 'second_position_interval', wp_rand( 1, 15 ) );

    	$new_time = current_time( 'mysql' );
		if ( $this->get_data( 'reviveso_republish_post_position', 'one' ) == 'one' ) {
    		$datetime = $this->get_meta( $post_id, '_reviveso_global_republish_datetime' );
			if ( ! empty( $datetime ) && ( $timestamp >= strtotime( $datetime ) ) ) {
			    $new_time = $datetime;
			}
    	} else {
			$args = ( array ) $this->do_filter( 'republish_position_args', array(
    			'post_type'   => $post->post_type,
                'numberposts' => 1,
    			'offset'      => 1,
    			'post_status' => 'publish',
				'order'       => 'DESC',
				'orderby'     => 'date',
				'fields'      => 'ids',
    		), $post );
    		$lastposts = $this->get_posts( $args );

    		if ( ! empty( $lastposts ) ) {
    		    foreach ( $lastposts as $lastpost ) {
					$post_date = get_the_date( 'U', $lastpost );
					$post_date = $post_date + $interval;
    		    	$new_time = date( 'Y-m-d H:i:s', $post_date );
    		    }
    	    }
		}

		return $this->do_filter( 'next_scheduled_timestamp', $new_time, $post_id, $single, $scheduled );
	}
}
