<?php
// Exit if accessed directly
if ( ! defined('ABSPATH') ) { exit; }

class Social_Rocket_Cron {
	
	protected static $instance = null;
    
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	public function __construct() {
		
		add_action( 'social_rocket_cron', array( $this, 'handle' ) );
		
		if ( ! wp_next_scheduled( 'social_rocket_cron' ) ) {
			wp_schedule_event( time() + 10, 'hourly', 'social_rocket_cron' );
		}
		
	}
	
	
	public function handle() {
	
		global $post;
		
		$SR = Social_Rocket::get_instance();
		
		$idle = $SR->background_processor->is_idle();
		
		if ( ! $idle ) {
			return;
		}
		
		// grab some posts 20 at a time and see if we need to trigger any updates
		$count = apply_filters( 'social_rocket_cron_check_posts', 20 );
		
		if ( $count > 0 ) {
			$post_types = Social_Rocket::get_post_types();
			$args = array(
				'post_type'      => $post_types,
				'post_status'    => 'publish',
				'posts_per_page' => $count,
				'orderby'        => 'rand',
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) { 
				while ( $query->have_posts() ) {
					$query->the_post();
					$SR->maybe_update_share_counts( $post->ID, 'post', get_permalink( $post ) );
				}
			}
			wp_reset_postdata();
		}
		
	}

}
