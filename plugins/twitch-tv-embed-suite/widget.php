<?php
class PlumwdTwitchStatusWidget extends WP_Widget {
  function PlumwdTwitchStatusWidget() {
	$widget_ops = array('classname' => 'PlumwdTwitchStatusWidget','description' => 'Display Twitch TV status in a widget');
	$this->WP_Widget('PlumwdTwitchStatusWidget','plumwd Twitch Status Widget',$widget_ops);
  }
 
  function form($instance)
  {
    $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'channel' => '', 'twittername' => '' ) );
    $title = $instance['title'];
	$channel = $instance['channel'];
	$twittername = $instance['twittername'];
?>
<p>
  <label for="<?php echo $this->get_field_id('title'); ?>">Title:
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
  </label>
</p>
<p>
  <label for="<?php echo $this->get_field_id('channel'); ?>">Channel Name:
    <input class="widefat" id="<?php echo $this->get_field_id('channel'); ?>" name="<?php echo $this->get_field_name('channel'); ?>" type="text" value="<?php echo esc_attr($channel); ?>" />
  </label>
</p>
<p><label for="<?php echo $this->get_field_id('social'); ?>">Display Share Buttons: <input id="<?php echo $this->get_field_id('social'); ?>" name="<?php echo $this->get_field_name('social'); ?>" type="checkbox" value="1" <?php checked(isset($instance['social']) ? $instance['social'] : 0); ?> /></label></p>
<p><label for="<?php echo $this->get_field_id('twittername'); ?>">Twitter Name:
    <input class="widefat" id="<?php echo $this->get_field_id('twittername'); ?>" name="<?php echo $this->get_field_name('twittername'); ?>" type="text" value="<?php echo esc_attr($twittername); ?>" />
  </label>
</p>
<?php
  }
 
  function update($new_instance, $old_instance)
  {
    $instance = $old_instance;
    $instance['title'] = $new_instance['title'];
	$instance['channel'] = $new_instance['channel'];
	$instance['social'] = $new_instance['social'];
	$instance['twittername'] = $new_instance['twittername'];
    return $instance;
  }
  
  function widget($args, $instance) { // widget sidebar output
    $file = dirname(__FILE__) . '/index.php';
    $plugin_dir = plugin_dir_url($file);
	$social = $instance['social'];
	$twittername = $instance['twittername'];
	$channel = $instance['channel'];
	$plugin_dir_path = plugin_dir_path($file);
    extract($args, EXTR_SKIP);
		echo $before_widget;
		$title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']); 
			
		if ($title != " ")
   	      echo $before_title . $title . $after_title;
		
		$channelname = $channel;
		
		//let's get the profile image
		$kraken = "https://api.twitch.tv/kraken/users/".$channelname;	
		$ch = curl_init($kraken);
	    $fp = fopen($plugin_dir_path."kraken.json", "w");
	
	    curl_setopt($ch, CURLOPT_FILE, $fp);
	    curl_setopt($ch, CURLOPT_HEADER, 0);
	
	    curl_exec($ch);
	    curl_close($ch);
	    fclose($fp);
			
		$userfile = file_get_contents($plugin_dir_path."kraken.json");	
		$obj = json_decode($userfile);
		$profile_img = $obj->logo;
		if ($profile_img != null) {
			$sized_img = str_replace("300x300", "50x50", $profile_img);
		} else {
			$sized_img = $plugin_dir."images/404_user_150x150.png";
		}
		
		$json_file = @file_get_contents("https://api.twitch.tv/kraken/streams?channel={$channelname}", 0, null, null);
 		$json_array = json_decode($json_file, true);
		//print_r($json_array);
		if (isset($json_array['streams'][0]['_id']))  {
			//only the title appears to be used here, but we'll leave it just in case they want to use it later
			$channelTitle = $json_array[0]['channel']['title'];
			$game = $json_array['streams'][0]['game'];
		?>
		<?php $output = '<div style="margin: 10px 0px;"> 
         <img src="'.$sized_img.'" alt="profile image" style="float: left; width: 50px; height: 50px; margin-right: 20px;"/>
 		 <div style="float: left; width: 75%;">
         <h2><a href="http://twitch.tv/'.$channelname.'">'.$channelTitle.'</a></h2>
         <p><img src="'.$plugin_dir.'images/online.png" alt="online"/> Live.</p>
         <p>Playing: '.$game.'</p>
         </div>
        </div>'; ?>
 		 <?php
			  } else {
			?>
		<?php $output = '<div style="margin: 10px 0px;">
 		 <img src="'.$sized_img.'" alt="profile image" style="float: left; width: 50px; height: 50px; margin-right: 20px;"/>
         <div style="float: left; width: 75%;">
 		 <h2><a href="http://twitch.tv/'.$channelname.'">'.$channelname.'</a></h2>
		 <p><img src="'.$plugin_dir.'/images/offline.png" alt="offline"/> Offline.</p>
         </div>
        </div>'; ?>
	<?php
		}
		if ($social == 1) {  //let's display our sharing buttons
		  if (isset($twittername)) { $twitter = " data-via=\"".$twittername."\" "; } else {  $twitter = "data-via\"plumwd\" ";}

    $output.='<hr style="clear: both;"/>
    <p><a href="https://twitter.com/share" class="twitter-share-button" data-lang="en" '.$twitter.' data-count="none">Tweet</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
   &nbsp;<a href="#" onclick="window.open(\'https://www.facebook.com/sharer/sharer.php?u=\'+encodeURIComponent(location.href), \'facebook-share-dialog\', \'width=626,height=436\'); return false;">
  <img src="'.$plugin_dir.'images/facebook_share.jpg" alt="Share on Facebook" style="padding-bottom: 1px;"/>
</a>
    </p>';
		}
		echo $output;

		?>
<?php	echo $after_widget; // post-widget code from theme

  }
}
add_action('widgets_init',create_function('','return register_widget("PlumwdTwitchStatusWidget");'));
?>
