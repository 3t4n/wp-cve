<?php
/**
 * Expand Divi Twitter Feed Widget
 * displays tweets
 *
 * @package  ExpandDivi/ExpandDiviTwitterFeedWidget
 */

// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once( EXPAND_DIVI_PATH . 'inc/lib/twitteroauth/autoload.php' );
use Abraham\TwitterOAuth\TwitterOAuth;

class ExpandDiviTwitterFeedWidget extends WP_Widget {

	/**
	 * Sets up the widgets name and description
	 */
	function __construct() {
		$args = array( 
			'name' => 'Expand Divi Twitter Feed',
			'description' => 'Display tweets.',
		);
		parent::__construct( 'expand_divi_twitter_feed', '', $args ); 
	}

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	function widget( $args, $instance ) {
		! empty( $instance['title'] ) ? $the_title = $instance['title'] : $the_title = esc_html__( 'Recent Tweets', 'expand-divi' );

		$connection = new TwitterOAuth( $instance['CONSUMER_KEY'], $instance['CONSUMER_SECRET'], $instance['access_token'], $instance['access_token_secret'] );
		$content = $connection->get("account/verify_credentials");

		$statuses = $connection->get( "statuses/home_timeline", ["count" => $instance['number'], "exclude_replies" => true] );

		echo $args['before_widget'];
			echo $args['before_title'] . $the_title . $args['after_title'];

			foreach ( $statuses as $tweet ) {
				$formatted_date = date( 'H:i, M d', strtotime( $tweet->created_at ) );

				// convert links, @ and # to links
				$target = true ? " target=\"_blank\" " : "";
			  	$tweet_text = $tweet->text;
			  	$tweet_text = preg_replace( "/((http:\/\/|https:\/\/)[^ )]+)/e", "'<a href=\"$1\" title=\"$1\"  $target >'. ((strlen('$1')>=250 ? substr('$1',0,250).'...':'$1')).'</a>'", $tweet_text );
			    $tweet_text = preg_replace( "/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" title=\"Follow $2\" $target >$1</a>", $tweet_text );
			    $tweet_text = preg_replace( "/(#([_a-z0-9\-]+))/i","<a href=\"http://search.twitter.com/search?q=%23$2\" title=\"Search $1\" $target >$1</a>", $tweet_text );
			    echo '<div class="expand_divi_tweet_container">';
				echo '<p class="expand_divi_tweet_text">' . $tweet_text . '</p>';
				echo '<p class="expand_divi_tweet_created_at"> - ' . $formatted_date . '</p>';
			    echo '</div>';
			}
		echo $args['after_widget'];
	}

	/**
	 * Outputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( 'Recent Tweets', 'expand-divi' );
		$number = ! empty( $instance['number'] ) ? $instance['number'] : 5;
		$CONSUMER_KEY = ! empty( $instance['CONSUMER_KEY'] ) ? $instance['CONSUMER_KEY'] : '';
		$CONSUMER_SECRET = ! empty( $instance['CONSUMER_SECRET'] ) ? $instance['CONSUMER_SECRET'] : '';
		$access_token = ! empty( $instance['access_token'] ) ? $instance['access_token'] : '';
		$access_token_secret = ! empty( $instance['access_token_secret'] ) ? $instance['access_token_secret'] : '';

		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_attr_e( 'Title:', 'expand-divi' ); ?></label> 
			<input  type="text" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>"><?php esc_attr_e( 'Number of Tweets (200 max):', 'expand-divi' ); ?></label> 
			<input  type="number" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'number' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'number' ) ); ?>" value="<?php echo esc_attr( $number ); ?>" min="1" max="200">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'CONSUMER_KEY' ) ); ?>"><?php esc_attr_e( 'Consumer Key:', 'expand-divi' ); ?></label> 
			<input  type="CONSUMER_KEY" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'CONSUMER_KEY' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'CONSUMER_KEY' ) ); ?>" value="<?php echo esc_attr( $CONSUMER_KEY ); ?>" min="1" max="20">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'CONSUMER_SECRET' ) ); ?>"><?php esc_attr_e( 'Consumer Secret:', 'expand-divi' ); ?></label> 
			<input  type="CONSUMER_SECRET" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'CONSUMER_SECRET' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'CONSUMER_SECRET' ) ); ?>" value="<?php echo esc_attr( $CONSUMER_SECRET ); ?>" min="1" max="20">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>"><?php esc_attr_e( 'Access token:', 'expand-divi' ); ?></label> 
			<input  type="access_token" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token' ) ); ?>" value="<?php echo esc_attr( $access_token ); ?>" min="1" max="20">
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>"><?php esc_attr_e( 'Access Token Secret:', 'expand-divi' ); ?></label> 
			<input  type="access_token_secret" class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'access_token_secret' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'access_token_secret' ) ); ?>" value="<?php echo esc_attr( $access_token_secret ); ?>" min="1" max="20">
		</p>
		<?php 
	}

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 *
	 * @return array
	 */
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

	    $instance['title'] = strip_tags( stripslashes( $new_instance['title'] ) );
	    $instance['number'] = $new_instance['number'];
	    $instance['CONSUMER_KEY'] = $new_instance['CONSUMER_KEY'];
	    $instance['CONSUMER_SECRET'] = $new_instance['CONSUMER_SECRET'];
	    $instance['access_token'] = $new_instance['access_token'];
	    $instance['access_token_secret'] = $new_instance['access_token_secret'];

		return $instance;
	}

}

add_action( 'widgets_init', function(){
	register_widget( 'ExpandDiviTwitterFeedWidget' );
});