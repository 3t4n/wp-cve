<?php

class Ctwg_Widget_Tweets extends WP_Widget{
	
	function __construct(){
		$args = array('classname' => 'ctwg-tweets', 'description' => __('Displays the latest tweets for a specific Twitter user.', 'ctwg'));
		parent::__construct('ctwg-twitter-stream', __('CPO - Twitter Stream', 'ctwg'), $args);
		$this->alt_option_name = 'ctwg_twitter_stream';
	}

	function widget($args, $instance){
		extract($args);
		$widget_id = str_replace('-', '_', $widget_id);
		$title = apply_filters('widget_title', $instance['title']);
		$username = strip_tags($instance['username']);
		$number = $instance['number'];
		if(!is_numeric($number)) $number = 5; elseif($number < 1) $number = 1; elseif($number > 99) $number = 99;
		$consumer_key = $instance['consumer_key'];
		$consumer_secret = $instance['consumer_secret'];
		$access_key = $instance['access_key'];
		$access_secret = $instance['access_secret'];

		if($username == '' || $consumer_key == '' || $consumer_secret == '' || $access_key == '' || $access_secret == '') 
			return;
			
		//Craft authenticated request
		require_once plugin_dir_path(__FILE__).'../classes/twitteroauth.php';
		$new_connection = new TwitterOAuth($consumer_key , $consumer_secret, $access_key, $access_secret);
		$options = array();
		$options['screen_name'] = strip_tags(sanitize_user($username));
		if($number != '') $options['count'] = intval($number);
		$response = $new_connection->get('statuses/user_timeline', $options);
		
		//Retrieve tweets from response
		$tweets = array();
		if(is_wp_error($response)){
		   $tweets = array();
		}else{
			if(isset($response->errors)){
				$tweets = array();
			}elseif(isset($response[0]->user->id)){
				$tweets = $response;
			}
		}
		
		$output='';
		//Display each tweets, if there are any
		if(is_array($tweets) && count($tweets) > 0){
			$output .= '<div class="ctwg-tweets" id="'.$widget_id.'">';
			
			foreach ($tweets as $key => $tweet_contents){
				$contents = $tweet_contents->text;

				if($tweet_contents->truncated == false){
					$contents = make_clickable($contents);

					//If there are any mentions, make them links
					if(isset($instance['link_mentions']) && $instance['link_mentions'] == 1){
						$mentions = $this->find_mentions($contents);
						if(is_array($mentions)){
							foreach($mentions as $mention_key => $mention_value){
								$contents = str_replace('@'.$mention_value, '<a href="'.esc_url('http://twitter.com/'.$mention_value).'" title="'.sprintf(esc_attr__('@%s on Twitter', 'ctwg'), $mention_value).'">'.'@'.$mention_value.'</a>', $contents);
							}
						}
					}
				}

				$output .= '<div class="ctwg-tweet">';
				$output .= '<span class="tweet-content">';
				$output .= $contents;
				$output .= '</span>';
				$output .= '<small class="tweet-date">';
				$output .= '<a href="'.esc_url('https://twitter.com/'.urlencode($username).'/status/'.$tweet_contents->id_str).'">';
				$output .= human_time_diff(strtotime($tweet_contents->created_at), current_time('timestamp'));
				$output .= '</a>';
				$output .= '</small>';
				$output .= '</div>';
			}
			$output .= '</div>';
		}
		
		echo $before_widget;
		if($title != '')
			echo $before_title.$title.$after_title;
		if($output == '')
			$output .= 'Impossible to retrieve tweets. Try again later.';
		echo $output;
		echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['username'] = strip_tags($new_instance['username']);
		$instance['number'] = (int)$new_instance['number'];
		$instance['consumer_key'] = $new_instance['consumer_key'];
		$instance['consumer_secret'] = $new_instance['consumer_secret'];
		$instance['access_key'] = $new_instance['access_key'];
		$instance['access_secret'] = $new_instance['access_secret'];
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array) $instance, array('title' => '', 'username' => '', 'number' => '', 'consumer_key' => '', 'consumer_secret' => '', 'access_key' => '', 'access_secret' => ''));
		$title = esc_attr($instance['title']);
		$username = esc_attr($instance['username']);
		if(!isset($instance['number']) || !$number = (int)$instance['number']) $number = 5; 
		$consumer_key = $instance['consumer_key'];
		$consumer_secret = $instance['consumer_secret'];
		$access_key = $instance['access_key'];
		$access_secret = $instance['access_secret']; ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('username'); ?>"><?php _e('Twitter Username', 'ctwg'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('username'); ?>" name="<?php echo $this->get_field_name('username'); ?>" type="text" value="<?php echo $username; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Tweets', 'ctwg'); ?></label><br/>
			<input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
		<p><b><?php _e('API Settings', 'ctwg'); ?></b></p>
		<p>
			<label for="<?php echo $this->get_field_id('consumer_key'); ?>"><?php _e('Consumer Key', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('consumer_key'); ?>" name="<?php echo $this->get_field_name('consumer_key'); ?>" type="text" value="<?php echo $consumer_key; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('consumer_secret'); ?>"><?php _e('Consumer Secret', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('consumer_secret'); ?>" name="<?php echo $this->get_field_name('consumer_secret'); ?>" type="text" value="<?php echo $consumer_secret; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('access_key'); ?>"><?php _e('Access Key', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('access_key'); ?>" name="<?php echo $this->get_field_name('access_key'); ?>" type="text" value="<?php echo $access_key; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('access_secret'); ?>"><?php _e('Access Secret', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('access_secret'); ?>" name="<?php echo $this->get_field_name('access_secret'); ?>" type="text" value="<?php echo $access_secret; ?>" />
		</p>
	<?php }
}

add_action('widgets_init', 'ctwg_widget_tweets');
function ctwg_widget_tweets() {
	register_widget('Ctwg_Widget_Tweets');
}