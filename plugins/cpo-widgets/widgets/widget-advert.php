<?php

class Ctwg_Widget_Advert extends WP_Widget{
	
	function __construct(){
		$widget_ops = array('classname' => 'ctwg-advert', 'description' => __('This widget lets you display an advertising banner of any size.', 'ctwg'));
		parent::__construct('ctwg-advert', __('CPO - Ad Space', 'ctwg'), $widget_ops);
		$this->alt_option_name = 'ctwg-advert';
	}

	function widget($args, $instance){
		extract($args);
		$title = apply_filters('widget_title', $instance['title']);
		$image_url = $instance['image_url'];
		$link_url = $instance['link_url'];
		$ad_code = $instance['ad_code'];
		
		echo $before_widget;
		if($title != '') echo $before_title.$title.$after_title; ?>
		<div class="ctwg-advert" id="<?php echo $widget_id; ?>">
			<?php if($ad_code == ''): ?>
			<a href="<?php echo esc_url($link_url); ?>">
				<img src="<?php echo $image_url; ?>" title="<?php echo esc_attr($title); ?>" alt="<?php echo esc_attr($title); ?>"/>
			</a>
			<?php else: ?>
			<?php echo $ad_code; ?>
			<?php endif; ?>
		</div>
		<?php echo $after_widget;
	}

	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['image_url'] = $new_instance['image_url'];
		$instance['link_url'] = $new_instance['link_url'];
		$instance['ad_code'] = $new_instance['ad_code'];
		return $instance;
	}

	function form($instance){
		$instance = wp_parse_args((array)$instance, array('title' => '', 'image_url' => '', 'link_url' => '', 'ad_code' => ''));
		extract($instance, EXTR_SKIP);
		$title = esc_attr($instance['title']);
		$image_url = esc_attr($instance['image_url']);
		$link_url = esc_attr($instance['link_url']);
		$ad_code = esc_attr($instance['ad_code']); ?>
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'ctwg'); ?></label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('image_url'); ?>"><?php _e('Image URL', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('image_url'); ?>" name="<?php echo $this->get_field_name('image_url'); ?>" type="text" value="<?php echo $image_url; ?>" />
		</p>
		<p>
			<label for="<?php echo $this->get_field_id('link_url'); ?>"><?php _e('Link URL', 'ctwg'); ?></label><br/>
			<input class="widefat" id="<?php echo $this->get_field_id('link_url'); ?>" name="<?php echo $this->get_field_name('link_url'); ?>" type="text" value="<?php echo $link_url; ?>" />
		</p>
		<p><b>- <?php _e('or', 'ctwg'); ?> -</b></p>
		<p>
			<label for="<?php echo $this->get_field_id('ad_code'); ?>"><?php _e('Advertising Code', 'ctwg'); ?></label><br/>
			<textarea class="widefat" id="<?php echo $this->get_field_id('ad_code'); ?>" name="<?php echo $this->get_field_name('ad_code'); ?>"><?php echo $ad_code; ?></textarea>
		</p>
		<p><?php _e('You can add an image linked to a specific URL, or alternatively paste your advertising code.', 'ctwg'); ?></p>
	<?php }
}

add_action('widgets_init', 'ctwg_widget_advert');
function ctwg_widget_advert() {
	register_widget('Ctwg_Widget_Advert');
}
