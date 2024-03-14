<?php

namespace CTXFeed\V5\Common;

class Widget {
	public function __construct() {
		add_action( 'wp_dashboard_setup', [ $this, 'add_dashboard_widget' ], 1, 10 );
	}
	
	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	public function add_dashboard_widget() {
		global $wp_meta_boxes;
		
		add_meta_box( 'aaaa_webappick_latest_news_dashboard_widget', __( 'Latest News from WebAppick Blog', 'woo-feed' ), 'dashboard_widget_render', 'dashboard', 'side', 'high' );
		
	}
	
	/**
	 * Function to get dashboard widget data.
	 */
	public function dashboard_widget_render() {
		
		// Enter the name of your blog here followed by /wp-json/wp/v2/posts and add filters like this one that limits the result to 2 posts.
		$response = wp_remote_get( 'https://webappick.com/wp-json/wp/v2/posts?per_page=5' );
		
		// Exit if error.
		if ( is_wp_error( $response ) ) {
			return;
		}
		
		// Get the body.
		$posts = json_decode( wp_remote_retrieve_body( $response ), false );
		
		// Exit if nothing is returned.
		if ( empty( $posts ) ) {
			return;
		}
		?>
        <p><a style="text-decoration: none;font-weight: bold;" href="<?php echo esc_url( 'https://webappick.com' ); ?>"
              target=_balnk><?php echo esc_html__( "WEBAPPICK.COM", 'woo-feed' ); ?></a></p>
        <hr>
		<?php
		// If there are posts.
		// For each post.
		foreach ( $posts as $post ) {
			$date = date( 'M j, Y', strtotime( $post->modified ) ); ?>
            <p class="webappick-feeds"><a style="text-decoration: none;" href="<?php echo esc_url( $post->link ); ?>"
                                          target=_balnk><?php echo esc_html( $post->title->rendered ); ?></a>
                - <?php echo $date; ?></p>
            <span><?php echo wp_trim_words( $post->content->rendered, 35, '...' ); ?></span>
			<?php
		}
		?>
        <hr>
        <p><a style="text-decoration: none;" href="<?php echo esc_url( 'https://webappick.com/blog/' ); ?>"
              target=_balnk><?php echo esc_html__( "Get more woocommerce tips & news on our blog...", 'woo-feed' ); ?></a>
        </p>
		<?php
	}
}

$CTXDashboardWidget = new Widget();