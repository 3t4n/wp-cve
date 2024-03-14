<?php
if ( !defined('ABSPATH')) exit;

class GabfireTweets extends WP_Widget {

	function GabfireTweets() {
		$widget_ops = array( 'classname' => 'gabfire_tweets', 'description' => 'Display a twitter feed based off username or search parameters' );
		$control_ops = array( 'width' => 250, 'height' => 350, 'id_base' => 'gabfire_tweets' );
		parent::__construct( 'gabfire_tweets', 'Gabfire: Twitter Widget', $widget_ops, $control_ops);
	}

    public function widget($args, $instance) {
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );
		$tweets_base = $instance['tweets_base'];
		$tweets_of = $instance['tweets_of'];
		$profile_photo = $instance['profile_photo'];
		$tweets_nr = $instance['tweets_nr'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			}

			if ($instance['tweets_base'] == 'username'){
				echo $this->gt_get_twitter_data($instance,'username');
			} else {
				echo $this->gt_get_twitter_data($instance,'hashtag');
			}

		echo "<div class='clear'></div>$after_widget";
    }

	function update($new_instance, $old_instance) {
		$instance['title']	= ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['tweets_base'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field($new_instance['tweets_base']) : '';
		$instance['tweets_of'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field($new_instance['tweets_of']) : '';
		$instance['profile_photo'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field($new_instance['profile_photo']) : '';
		$instance['tweets_nr'] = ( ! empty( $new_instance['title'] ) ) ? (int) sanitize_text_field($new_instance['tweets_nr']) : '';
		return $new_instance;
	}

    function form($instance) {
		$defaults	= array(
			'title' => 'Twitter Widget',
			'tweets_base' => 'username',
			'profile_photo' => 'display',
			'tweets_nr' => 5,
			'tweets_of' => ''
		);
		$instance = wp_parse_args( (array) $instance, $defaults );
		?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'tweets_base' ); ?>"><?php _e('Tweets based on:', 'gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'tweets_base' ); ?>" name="<?php echo $this->get_field_name( 'tweets_base' ); ?>">
				<option value="username" <?php if ( 'username' == $instance['tweets_base'] ) echo 'selected="selected"'; ?>><?php _e('Username','gabfire-widget-pack'); ?></option>
				<option value="hashtag" <?php if ( 'hashtag' == $instance['tweets_base'] ) echo 'selected="selected"'; ?>><?php _e('Hashtag','gabfire-widget-pack'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'profile_photo' ); ?>"><?php _e('Twitter profile photo:', 'gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'profile_photo' ); ?>" name="<?php echo $this->get_field_name( 'profile_photo' ); ?>">
				<option value="display" <?php if ( 'display' == $instance['profile_photo'] ) echo 'selected="selected"'; ?>><?php _e('Display','gabfire-widget-pack'); ?></option>
				<option value="hide" <?php if ( 'hide' == $instance['profile_photo'] ) echo 'selected="selected"'; ?>><?php _e('Hide','gabfire-widget-pack'); ?></option>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_name( 'tweets_nr' ); ?>"><?php _e('Number of Tweets?','gabfire-widget-pack'); ?></label>
			<select id="<?php echo $this->get_field_id( 'tweets_nr' ); ?>" name="<?php echo $this->get_field_name( 'tweets_nr' ); ?>">
			<?php
				for ( $i = 1; $i <= 10; ++$i )
				echo "<option value='$i' " . selected( $instance['tweets_nr'], $i, false ) . ">$i</option>";
			?>
			</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('tweets_of'); ?>"><?php _e('Enter Username or Hashtag','gabfire-widget-pack'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('tweets_of'); ?>" name="<?php echo $this->get_field_name('tweets_of'); ?>" type="text" value="<?php echo esc_attr($instance['tweets_of']); ?>" />
		</p>

		<?php
	}


	private function gt_get_twitter_data($options, $tweets_base){
		global $gabfire_options; // to get the keys defined on plugin's configuration page

		if ($gabfire_options['key'] == '' || $gabfire_options['secret'] == '' || $gabfire_options['token_key'] == '' || $gabfire_options['token_secret'] == '') {
			return __('Twitter Authentication data is incomplete','gabfire-widget-pack');
		}

		if (!class_exists('Codebird')) {
			require_once ('lib/codebird.php');
		}

		Codebird::setConsumerKey($gabfire_options['key'], $gabfire_options['secret']);
		$cb = Codebird::getInstance();
		$cb->setToken($gabfire_options['token_key'], $gabfire_options['token_secret']);
		$cb->setReturnFormat(CODEBIRD_RETURNFORMAT_ARRAY);

		$count = 0;
		$target = 'target="_blank"';

		$out = '<ul class="gabfire-tweets">';
			if ($tweets_base == 'hashtag'){
				$reply = get_transient('gabfire_socialmashup_widget_twitter_search_transient');
				if (false === $reply){
					try {
						$reply = $cb->search_tweets(array(
									'q'=>'#'.$options['tweets_of'],
									'count'=> $options['tweets_nr']
							));
					} catch (Exception $e) {
						return __('Error retrieving tweets','gabfire-widget-pack');
					}
					if (isset($reply['errors'])) {
						//error_log(serialize($reply['errors']));
					}
					set_transient('gabfire_socialmashup_widget_twitter_transient',$reply,300);
				}

				if (empty($reply) or count($reply)<1) {
					return __('No public tweets with' . $reply . ' hashtag','gabfire-widget-pack');
				}

				if (isset($reply['statuses']) && is_array($reply['statuses'])) {
					foreach($reply['statuses'] as $message) {
						if ($count>=$options['tweets_nr']) {
							break;
						}

						if (!isset($message['text'])) {
							continue;
						}

						$msg = $message['text'];

						$out .= '<li>';
						if ($options['profile_photo'] == 'display') {
							$out .= '<img class="alignright" src="'.$message['user']['profile_image_url_https'].'" alt="" />';
						}

						/* Code from really simpler twitter widget */

						$msg = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\" ".$target.">$1</a>", $msg);

						$msg = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\" ".$target.">$1</a>", $msg);

						$msg = preg_replace('/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i',"<a href=\"mailto://$1\" class=\"twitter-link\" ".$target.">$1</a>", $msg);

						$msg = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a href="//twitter.com/#!/search/%23\2" class="twitter-link" '.$target.'>#\2</a>', $msg);

						$msg = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\" ".$target.">@$2</a>$3 ", $msg);

						$out .= $msg;
						$out .= '</li>';
						$count++;
					}
				}

			} elseif ($tweets_base == 'username') {
				$reply = get_transient('gabfire_socialmashup_widget_twitter_username_transient');

				if (false === $reply){
					try {
						$twitter_data =  $cb->statuses_userTimeline(array(
									'screen_name'=>$options['tweets_of'],
									'count'=> $options['tweets_nr']
							));
					} catch (Exception $e) {
						return __('Error retrieving tweets','gabfire-widget-pack');
					}

					if (isset($reply['errors'])) {
						//error_log(serialize($reply['errors']));
					}

					set_transient('gabfire_socialmashup_widget_twitter_username_transient',$reply,300);
				}

				if (empty($twitter_data) or count($twitter_data)<1) {
					return __('No public tweets','gabfire-widget-pack');
				}

				if (isset($twitter_data) && is_array($twitter_data)) {
					foreach($twitter_data as $message) {
						if ($count>=$options['tweets_nr']) {
							break;
						}

						if (!isset($message['text'])) {
							continue;
						}

						$msg = $message['text'];

						$out .= '<li>';
						if ($options['profile_photo'] == 'display') {
							$out .= '<img class="alignright" src="'.$message['user']['profile_image_url_https'].'" alt="" />';
						}


						/* Code from really simpler twitter widget */

						$msg = preg_replace('/\b([a-zA-Z]+:\/\/[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"$1\" class=\"twitter-link\" ".$target.">$1</a>", $msg);

						$msg = preg_replace('/\b(?<!:\/\/)(www\.[\w_.\-]+\.[a-zA-Z]{2,6}[\/\w\-~.?=&%#+$*!]*)\b/i',"<a href=\"http://$1\" class=\"twitter-link\" ".$target.">$1</a>", $msg);
						$msg = preg_replace('/\b([a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]*\@[a-zA-Z][a-zA-Z0-9\_\.\-]*[a-zA-Z]{2,6})\b/i',"<a href=\"mailto://$1\" class=\"twitter-link\" ".$target.">$1</a>", $msg);

						$msg = preg_replace('/(^|\s)#(\w*[a-zA-Z_]+\w*)/', '\1<a href="//twitter.com/#!/search/%23\2" class="twitter-link" '.$target.'>#\2</a>', $msg);

						$msg = preg_replace('/([\.|\,|\:|\¡|\¿|\>|\{|\(]?)@{1}(\w*)([\.|\,|\:|\!|\?|\>|\}|\)]?)\s/i', "$1<a href=\"http://twitter.com/$2\" class=\"twitter-user\" ".$target.">@$2</a>$3 ", $msg);

						$out .= $msg;
						$out .= '</li>';
						$count++;
					}
				}
			}

		$out .= '</ul>';

		return $out;
	}
}

function register_gabfire_tweets() {
	register_widget('GabfireTweets');
}

add_action('widgets_init', 'register_gabfire_tweets');