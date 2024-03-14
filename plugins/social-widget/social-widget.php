<?php
/*
Plugin Name: Social Widget
Description: A beautiful widget that allow you to add a stylish Facebook like box, twitter follow button and a google +1 button to your sidebar.
Author: Ismail el korchi
Version: 1.8
Author URI: 
*/

/*  Copyright 2012  Ismail El Korchi

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

class SocialWidget extends WP_Widget
{
	function __construct()
	{
		load_plugin_textdomain('social_widget', false, dirname(plugin_basename(__FILE__)) . '/languages/');
		$params = array(
			'description' => 'A beautiful widget that allow you to add a stylish Facebook like box, twitter follow button and a google +1 button to your sidebar.',
			'name' => 'Social Widget'
		);
		parent::__construct('SocialWidget','',$params);
	}
		function update( $new_instance, $old_instance )
		{
			$new_instance = (array) $new_instance;
			$instance = array( 'follower_count' => 0, 'show_username' => 0);
			foreach ( $instance as $field => $val ) {
				if ( isset($new_instance[$field]) )
					$instance[$field] = 1;
		}
		$instance['recommend'] = $new_instance['recommend'];
		$instance['page_id'] = $new_instance['page_id'];
		$instance['username'] = $new_instance['username'];

		return $instance;
	}
	
		public function form($instance)
	{
		// DEFAULT VALUES
		$instance = wp_parse_args( (array) $instance, array('page_id' => '','username' => '', 'recommend' => "Recommend", 'follower_count' => false, 'show_username' => true ) );
		
		?>
		<center><strong><?php _e('Social Profiles','social_widget'); ?></strong></center><br />
		
		<p>
			<label for="<?php echo $this->get_field_id('page_id');?>"><?php _e('Facebook Page URL :','social_widget'); ?></label>
			<input type="text" class="widefat"
			id="<?php echo $this->get_field_id('page_id');?>"
			name="<?php echo $this->get_field_name('page_id');?>"
			value="<?php echo esc_attr( $instance['page_id']); ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('username');?>"><?php _e('Twitter Username :','social_widget'); ?> </label>
			<input type="text" class="widefat"
			id="<?php echo $this->get_field_id('username');?>"
			name="<?php echo $this->get_field_name('username');?>"
			value="<?php echo esc_attr( $instance['username']); ?>" />
		</p>
		<center><strong>Settings</strong></center><br />
		<p>
			<label for="<?php echo $this->get_field_id('recommend');?>"><?php _e('Google recommend text :','social_widget'); ?></label>
			<input type="text" class="widefat"
			id="<?php echo $this->get_field_id('recommend');?>"
			name="<?php echo $this->get_field_name('recommend');?>"
			value="<?php echo esc_attr( $instance['recommend']); ?>" />
		</p>
		<p>
			<input class="checkbox" type="checkbox"
			<?php checked($instance['follower_count'], true) ?>
			id="<?php echo $this->get_field_id('follower_count');?>"
			name="<?php echo $this->get_field_name('follower_count');?>"/>
			<label for="<?php echo $this->get_field_id('follower_count');?>"><?php _e('Show the Follower count','social_widget'); ?></label> <br />


			<input class="checkbox" type="checkbox"
			<?php checked($instance['show_username'], true) ?>
			id="<?php echo $this->get_field_id('show_username');?>"
			name="<?php echo $this->get_field_name('show_username');?>"/>
			<label for="<?php echo $this->get_field_id('show_username');?>"><?php _e('Show the Twitter username','social_widget'); ?></label><br />
		</p>		
		<?php
	}
	
	public function widget($args,$instance)
	{
		extract($args);
		$showUN = isset($instance['show_username']) ? $instance['show_username'] : false;
		$showFC = isset($instance['follower_count']) ? $instance['follower_count'] : false;
		if ($showUN)
		{
			$showUN = 'true';
		}
		else
		{
			$showUN = 'false';
		}
		if ($showFC)
		{
			$showFC = 'true';
		}
		else
		{
			$showFC = 'false';
		}
		echo $before_widget;
			printf('<div id="sidesocial">
						<div class="sidefb">
							<div class="sidefb">
							<div class="facebookOuter">
							 <div class="facebookInner">
							  <div class="fb-like-box" 
							      data-width="250" data-height="200" 
							      data-href="' . $instance['page_id'] . '" 
							      data-show-border="false" data-show-faces="true"  
							      data-stream="false" data-header="false">
							  </div>          
							 </div>
							</div>
						</div>
						
						<div class="sideg">
							<div class="g-plusone" data-size="medium" data-href="'.get_bloginfo('wpurl').'"></div>
							<span>'.$instance['recommend'].'</span>
						</div>
						<div class="sidetw">
						<a href="https://twitter.com/' . $instance['username'] . '" class="twitter-follow-button" data-show-screen-name="'. $showUN .'" data-show-count="'. $showFC .'" data-lang="' . __('en','social_widget') . '">'. __('Follow @','social_widget') . $instance['username'] . '</a>
						</div>
					</div>');
		echo $after_widget;
	}
}



add_action('wp_head','addstyle');
function addstyle()
{
	echo '<link type="text/css" rel="stylesheet" href="' . get_bloginfo('wpurl') . '/wp-content/plugins/social-widget/style.css"/>' ;
}



add_action('wp_footer','addscripts');
function addscripts()
{
	?>
	<div id="fb-root">
	<script type="text/javascript">
		window.fbAsyncInit = function() {
  			FB.init({ status: true, cookie: true, xfbml: true});
		};
		(function() {
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.src = document.location.protocol + '//connect.facebook.net/<?php _e('en_US','social_widget'); ?>/all.js';
			e.async = true;
			document.getElementById('fb-root').appendChild(e);
		}());
		(function() {
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.src = 'http://platform.twitter.com/widgets.js';
			e.async = true;
			document.getElementById('fb-root').appendChild(e);
		}());
		window.___gcfg = {lang: '<?php _e('en','social_widget') ?>'};
		(function() {
			var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    		po.src = 'https://apis.google.com/js/plusone.js';
   			var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  		})();
 	</script>
</div>
<?php
}

add_action('widgets_init','register_socialwidget');
function register_socialwidget()
{
	register_widget('SocialWidget');
}
?>