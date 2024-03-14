<?php
if ( !defined('ABSPATH')) exit;

class gabfire_instagramrss extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_instagram_widget', 'description' => 'Instagram Photo Stream' );
		$control_ops = array( 'width' => 330, 'height' => 350, 'id_base' => 'gabfire_instagram_widget' );
		parent::__construct( 'gabfire_instagram_widget', 'Gabfire: Instagram Photos', $widget_ops, $control_ops );
	}

	public function widget( $args, $instance ) {
		
		extract( $args );
		$title 			= apply_filters('widget_title', $instance['title'] );
		$photos_of      = $instance['photos_of'];
		$instagram_uid = $instance['instagram_uid'];
		$instagram_cid = $instance['instagram_cid'];
		$instagram_tag = $instance['instagram_tag'];
		$access_token  = $instance['access_token'];
		$photo_count   = $instance['photo_count'];

		echo $before_widget;

			if ( $title ) {
				echo $before_title . $title . $after_title;
			} 
			
				$randomSalt = "abcdef";
				$preSalt  = substr($randomSalt, 0,3); // abc
				$postSalt = substr($randomSalt, 3,3);  // def
				$widgetid = md5(md5($preSalt.$this->id.$postSalt));

				function gabfire_instagramphoto($value)
				{
					$time1         = date("d/m/y", $value["created_time"]);
					$time2         = date("F j, Y", $value['caption']['created_time']);
					$time3         = date("F j, Y", strtotime($time2 . " +1 days"));					
					$nickname      = $value["user"]["username"];
					$user_avatar   = $value["user"]["profile_picture"];
					$pic_text      = $value['caption']['text'];
					$like_count    = $value['likes']['count'];
					$comment_count = $value['comments']['count'];

					$thumb   = $value["images"]["thumbnail"]["url"];
					$link    = $value["link"];					
					
					return "<div class='gabfire_instagram_thumb'><a href='$link' target='_blank' rel='nofollow'><img itemprop='image' src='$thumb' alt=''/></a></div>";
				}

				if ($photos_of == 'hashtag')
				{
					if (!empty($instagram_tag))
					{
						
						$contents = file_get_contents("https://api.instagram.com/v1/tags/$instagram_tag/media/recent?client_id=$instagram_cid&count=$photo_count");
						$obj = json_decode($contents, true);
					}
				}
				
				else 
				{	
					if($access_token)
					{
						$contents = file_get_contents("https://api.instagram.com/v1/users/$instagram_uid/media/recent/?access_token=$access_token&count=$photo_count");
						$obj = json_decode(preg_replace('/("\w+"):(\d+)/', '\\1:"\\2"', $contents), true);
					}
				}

				foreach ($obj["data"] as $value)
				{
					echo gabfire_instagramphoto( $value );
				}
				
			
			echo '<div class="clear clearfix"></div>';
			
		echo $after_widget; 
	}
	
	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title']         = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['photos_of']     = ( ! empty( $new_instance['photos_of'] ) ) ? sanitize_text_field( $new_instance['photos_of'] ) : '';
		$instance['instagram_uid'] = ( ! empty( $new_instance['instagram_uid'] ) ) ? sanitize_text_field( $new_instance['instagram_uid'] ) : '';
		$instance['instagram_cid'] = ( ! empty( $new_instance['instagram_cid'] ) ) ? sanitize_text_field( $new_instance['instagram_cid'] ) : '';
		$instance['instagram_tag'] = ( ! empty( $new_instance['instagram_tag'] ) ) ? sanitize_text_field( $new_instance['instagram_tag'] ) : '';
		$instance['access_token']  = ( ! empty( $new_instance['access_token'] ) ) ? sanitize_text_field( $new_instance['access_token'] ) : '';
		$instance['photo_count']   = ( ! empty( $new_instance['photo_count'] ) ) ? sanitize_text_field( (int)$new_instance['photo_count'] ) : '';

		return $instance;
	}

	function form( $instance ) {
		$defaults	= array(
			'title'         => __('Instagram Feed', 'gabfire-widget-pack'),
			'photos_of'     => __('username', 'gabfire-widget-pack'),
			'instagram_uid' => '',
			'instagram_cid' => '',
			'instagram_tag' => '',
			'access_token'  => '',
			'photo_count'   => 6,
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
		
		if (isset($items)) 
			$items  = (int) $items;
		else 
			$items = 0;
			
		if (isset($items) && $items < 1 || 10 < $items )
		$items  = 10;
		?>
		
		<div class="controlpanel">
		
			<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>" class="widefat" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'photos_of' ); ?>"><?php _e('Display photos by','gabfire-widget-pack'); ?></label> 
				<select class="widefat" id="<?php echo $this->get_field_id( 'photos_of' ); ?>" name="<?php echo $this->get_field_name( 'photos_of' ); ?>">
					<option value="username" <?php if ( 'username' == $instance['photos_of'] ) echo 'selected="selected"'; ?>><?php _e('Username','gabfire-widget-pack'); ?></option>
					<option value="hashtag" <?php  if ( 'hashtag' == $instance['photos_of'] ) echo 'selected="selected"'; ?>><?php _e('Hashtag','gabfire-widget-pack'); ?></option>
				</select>
			</p>

			<p>
			<?php
			printf(__('To display user photos, an access token, User and Client ID\'s are required. To get them, first you\'ll need to <a href="%1$s" target="_blank">register an application</a> then copy Client ID. Then visit <a href="%2$s" target="_blank">this</a> site to get User ID and access token', 'gabfire-widget-pack'), 'https://instagram.com/developer/', 'https://smashballoon.com/instagram-feed/token/' );	?>				
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'instagram_uid' ); ?>"><?php _e('User ID','gabfire-widget-pack'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram_uid' ); ?>" name="<?php echo $this->get_field_name( 'instagram_uid' ); ?>" value="<?php echo esc_attr( $instance['instagram_uid'] ); ?>" class="widefat" />
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id( 'instagram_cid' ); ?>"><?php _e('Client ID','gabfire-widget-pack'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram_cid' ); ?>" name="<?php echo $this->get_field_name( 'instagram_cid' ); ?>" value="<?php echo esc_attr( $instance['instagram_cid'] ); ?>" class="widefat" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'instagram_tag' ); ?>"><?php _e('Hashtag','gabfire-widget-pack'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'instagram_tag' ); ?>" name="<?php echo $this->get_field_name( 'instagram_tag' ); ?>" value="<?php echo esc_attr( $instance['instagram_tag'] ); ?>" class="widefat" />
			</p>

			<p>
				<label for="<?php echo $this->get_field_id( 'access_token' ); ?>"><?php _e('Access Token','gabfire-widget-pack'); ?></label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'access_token' ); ?>" name="<?php echo $this->get_field_name( 'access_token' ); ?>" value="<?php echo esc_attr( $instance['access_token'] ); ?>" class="widefat" />
			</p>			

			<p>
				<label for="<?php echo $this->get_field_name( 'photo_count' ); ?>"><?php _e('Number of photos','gabfire-widget-pack'); ?></label>
				<select class="widefat" id="<?php echo $this->get_field_id( 'photo_count' ); ?>" name="<?php echo $this->get_field_name( 'photo_count' ); ?>">			
				<?php
					for ( $i = 1; $i <= 20; ++$i )
					echo "<option value='$i' " . selected( $instance['photo_count'], $i, false ) . ">$i</option>";
				?>
				</select>
			</p>
			
		</div>
		
	<?php
	}
}

function register_gabfire_instagramrss() {
	register_widget('gabfire_instagramrss');
}

add_action('widgets_init', 'register_gabfire_instagramrss');