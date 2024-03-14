<?php

class Ctwg_Widget_Social extends WP_Widget{
	
	function __construct(){
		$widget_ops = array('classname' => 'ctwg-social', 'description' => __('This widget lets you display an advertising banner of any size.', 'ctwg'));
		parent::__construct('ctwg-social', __('CPO - Social Links', 'ctwg'), $widget_ops);
		$this->alt_option_name = 'ctwg-social';
	}

	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$page_rss = esc_attr($instance['page_rss']);
		$page_facebook = esc_attr($instance['page_facebook']);
		$page_twitter = esc_attr($instance['page_twitter']);
		$page_gplus = esc_attr($instance['page_gplus']);
		$page_linkedin = esc_attr($instance['page_linkedin']);
		$page_youtube = esc_attr($instance['page_youtube']);
		$page_tumblr = esc_attr($instance['page_tumblr']);
		$page_skype = esc_attr($instance['page_skype']);
		$page_pinterest = esc_attr($instance['page_pinterest']);
		$page_instagram = esc_attr($instance['page_instagram']);
		$page_dribbble = esc_attr($instance['page_dribbble']);
		
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		<div class="ctwg-social" id="<?php echo $widget_id; ?>">
			<?php if($page_rss != ''): ?>
			<a class="ctwg-social-link ctwg-social-rss" href="<?php echo esc_url($page_rss); ?>" title="RSS">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_facebook != ''): ?>
			<a class="ctwg-social-link ctwg-social-facebook" href="<?php echo esc_url($page_facebook); ?>" title="Facebook">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_twitter != ''): ?>
			<a class="ctwg-social-link ctwg-social-twitter" href="<?php echo esc_url($page_twitter); ?>" title="Twitter">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_gplus != ''): ?>
			<a class="ctwg-social-link ctwg-social-gplus" href="<?php echo esc_url($page_gplus); ?>" title="Google+">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_linkedin != ''): ?>
			<a class="ctwg-social-link ctwg-social-linkedin" href="<?php echo esc_url($page_linkedin); ?>" title="LinkedIn">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_youtube != ''): ?>
			<a class="ctwg-social-link ctwg-social-youtube" href="<?php echo esc_url($page_youtube); ?>" title="YouTube">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_tumblr != ''): ?>
			<a class="ctwg-social-link ctwg-social-tumblr" href="<?php echo esc_url($page_tumblr); ?>" title="Tumblr">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_skype != ''): ?>
			<a class="ctwg-social-link ctwg-social-skype" href="<?php echo esc_url($page_skype); ?>" title="Skype">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_pinterest != ''): ?>
			<a class="ctwg-social-link ctwg-social-pinterest" href="<?php echo esc_url($page_pinterest); ?>" title="Pinterest">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_instagram != ''): ?>
			<a class="ctwg-social-link ctwg-social-instagram" href="<?php echo esc_url($page_instagram); ?>" title="Instagram">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
			<?php if($page_dribbble != ''): ?>
			<a class="ctwg-social-link ctwg-social-dribbble" href="<?php echo esc_url($page_dribbble); ?>" title="Dribbble">
				<span class="ctwg-social-icon"></span>
			</a>
			<?php endif; ?>
		</div>
		<?php echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['page_rss'] = $new_instance['page_rss'];
		$instance['page_facebook'] = $new_instance['page_facebook'];
		$instance['page_twitter'] = $new_instance['page_twitter'];
		$instance['page_gplus'] = $new_instance['page_gplus'];
		$instance['page_linkedin'] = $new_instance['page_linkedin'];
		$instance['page_youtube'] = $new_instance['page_youtube'];
		$instance['page_tumblr'] = $new_instance['page_tumblr'];
		$instance['page_skype'] = $new_instance['page_skype'];
		$instance['page_instagram'] = $new_instance['page_instagram'];
		$instance['page_dribbble'] = $new_instance['page_dribbble'];
		$instance['page_pinterest'] = $new_instance['page_pinterest'];
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array)$instance, 
		array('title' => '', 
		'page_rss' => '', 
		'page_facebook' => '', 
		'page_twitter' => '', 
		'page_gplus' => '', 
		'page_linkedin' => '', 
		'page_youtube' => '', 
		'page_tumblr' => '', 
		'page_skype' => '', 
		'page_pinterest' => '', 
		'page_instagram' => '', 
		'page_dribbble' => '', 
		'ad_code' => ''));
		
		extract($instance, EXTR_SKIP);
		$title = esc_attr($instance['title']);
		$page_rss = esc_attr($instance['page_rss']);
		$page_facebook = esc_attr($instance['page_facebook']);
		$page_twitter = esc_attr($instance['page_twitter']);
		$page_gplus = esc_attr($instance['page_gplus']);
		$page_linkedin = esc_attr($instance['page_linkedin']);
		$page_youtube = esc_attr($instance['page_youtube']);
		$page_tumblr = esc_attr($instance['page_tumblr']);
		$page_skype = esc_attr($instance['page_skype']);
		$page_pinterest = esc_attr($instance['page_pinterest']);
		$page_instagram = esc_attr($instance['page_instagram']);
		$page_dribbble = esc_attr($instance['page_dribbble']); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_rss'); ?>"><?php _e('RSS URL', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_rss'); ?>" name="<?php echo $this->get_field_name('page_rss'); ?>" type="text" value="<?php echo $page_rss; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_facebook'); ?>"><?php _e('Facebook Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_facebook'); ?>" name="<?php echo $this->get_field_name('page_facebook'); ?>" type="text" value="<?php echo $page_facebook; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_twitter'); ?>"><?php _e('Twitter Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_twitter'); ?>" name="<?php echo $this->get_field_name('page_twitter'); ?>" type="text" value="<?php echo $page_twitter; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_gplus'); ?>"><?php _e('Google Plus Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_gplus'); ?>" name="<?php echo $this->get_field_name('page_gplus'); ?>" type="text" value="<?php echo $page_gplus; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_linkedin'); ?>"><?php _e('LinkedIn Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_linkedin'); ?>" name="<?php echo $this->get_field_name('page_linkedin'); ?>" type="text" value="<?php echo $page_linkedin; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_youtube'); ?>"><?php _e('YouTube Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_youtube'); ?>" name="<?php echo $this->get_field_name('page_youtube'); ?>" type="text" value="<?php echo $page_youtube; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_tumblr'); ?>"><?php _e('Tumblr Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_tumblr'); ?>" name="<?php echo $this->get_field_name('page_tumblr'); ?>" type="text" value="<?php echo $page_tumblr; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_skype'); ?>"><?php _e('Skype Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_skype'); ?>" name="<?php echo $this->get_field_name('page_skype'); ?>" type="text" value="<?php echo $page_skype; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_pinterest'); ?>"><?php _e('Pinterest Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_pinterest'); ?>" name="<?php echo $this->get_field_name('page_pinterest'); ?>" type="text" value="<?php echo $page_pinterest; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_instagram'); ?>"><?php _e('Instagram Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_instagram'); ?>" name="<?php echo $this->get_field_name('page_instagram'); ?>" type="text" value="<?php echo $page_instagram; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('page_dribbble'); ?>"><?php _e('Dribbble Page', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('page_dribbble'); ?>" name="<?php echo $this->get_field_name('page_dribbble'); ?>" type="text" value="<?php echo $page_dribbble; ?>" />
		</p>
	<?php }
}

add_action('widgets_init', 'ctwg_widget_social');
function ctwg_widget_social() {
	register_widget('Ctwg_Widget_Social');
}
