<?php
if ( !defined('ABSPATH')) exit;

class gabfire_videos extends WP_Widget {
	
	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire-videos-widget', 'description' => 'Get the videos added via Custom Fields' );
		$control_ops = array( 'width' => 400, 'height' => 350, 'id_base' => 'gabfire-videos-widget' );
		parent::__construct( 'gabfire-videos-widget', 'Gabfire: Videos', $widget_ops, $control_ops);	
	}
	
	public function widget($args, $instance) {
		
		wp_enqueue_script( 'jquery', array(), '', true);
		wp_enqueue_style('owl-carousel', GABFIRE_WIDGETS_URL .'/css/owl.carousel.css');
		wp_enqueue_script('owl-carousel', GABFIRE_WIDGETS_URL .'/js/owl.carousel.min.js', array( 'jquery' ), '', true);
		wp_enqueue_script('owl-carousel-init', GABFIRE_WIDGETS_URL .'/js/owl.carousel.init.js', array( 'jquery' ), '', true);
		
		extract( $args );
		$title      = $instance['title'];		
		$video_nr   = $instance['video_nr'];
		$video_width   = $instance['video_width'];
		$video_height   = $instance['video_height'];
		$title_font_size   = $instance['title_font_size'];		
		$title_font_family   = $instance['title_font_family'];
		$title_font_weight   = $instance['title_font_weight'];
		echo $before_widget;
		
		if ( $title ) {
			echo $before_title . $title . $after_title;
		}		
		$font = $title_font_weight . ' ' . $title_font_size . ' ' . $title_font_family;
		?>
			<div class="gabfire-videos-wrapper">
				<div class="gabfire-videos-controls">
					<span class="gabfire-videos-prev pull-left"></span>
					<span class="gabfire-videos-next pull-right"></span>
				</div>
				
				<div class="gabfire-videos">
					<?php 
					$count = 1;
					$args = array(
					  'posts_per_page' => $video_nr, 
					  'meta_key' => 'iframe'
					);
					$wp_query = new WP_Query();$wp_query->query($args); 
					while ($wp_query->have_posts()) : $wp_query->the_post();
					?>					
						<div class="carousel_item">
							<h2 class="entry-title" style="font:<?php echo $font; ?>">
								<a href="<?php the_permalink() ?>" rel="bookmark" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpnewspaper' ), the_title_attribute( 'echo=0' ) ); ?>" ><?php the_title(); ?></a>
							</h2>	
							<?php
							if ( function_exists( 'gabfire_mediaplugin' ) ) {
								gabfire_mediaplugin(array(
									'name' => 'gabfire_video', 
									'imgtag' => 1,
									'link' => 1,		
									'enable_thumb' => 0,
									'enable_video' => 1, 
									'resize_type' => 'c', 
									'media_width' => $video_width, 
									'media_height' => $video_height, 
									'thumb_align' => 'aligncenter',
									'enable_default' => 0,
								));
							}
							?>
						</div>
					<?php $count++; endwhile; wp_reset_query(); ?>
				</div>
			</div>
		<?php
		echo "<div class='clear'></div>$after_widget"; 
	}
	
	function update($new_instance, $old_instance) {  
		$instance['title']		= ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['video_nr'] 	= (int) $new_instance['video_nr'];
		$instance['video_width'] 	= (int) $new_instance['video_width'];
		$instance['video_height'] 	= (int) $new_instance['video_height'];
		$instance['title_font_size'] 	= sanitize_text_field($new_instance['title_font_size']);
		$instance['title_font_family'] 	=  sanitize_text_field($new_instance['title_font_family']);
		$instance['title_font_weight'] 	=  sanitize_text_field($new_instance['title_font_weight']);
		return $new_instance;
	}
  
	function form($instance) {
		$defaults	= array( 'title' => '', 'video_nr' => '5', 'video_width' => '300', 'video_height' => '225', 'title_font_weight' => 'Normal', 'title_font_size' => '17px','title_font_family' => 'Georgia, Times, \'Times New Roman\', serif', 'title_font_weight' => 'bold');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>

	<p>
		<label for="<?php echo $this->get_field_name( 'video_nr' ); ?>"><?php _e('Number of Videos','gabfire-widget-pack'); ?></label>
		<select id="<?php echo $this->get_field_id( 'video_nr' ); ?>" name="<?php echo $this->get_field_name( 'video_nr' ); ?>">
		<?php
			for ( $i = 0; $i <= 15; ++$i )
			echo "<option value='$i' " . selected( $instance['video_nr'], $i, false ) . ">$i</option>";
		?>
		</select>
	</p>	
	
	<p>
		<label for="<?php echo $this->get_field_id('video_width'); ?>"><?php _e('Video Width','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('video_width'); ?>" name="<?php echo $this->get_field_name('video_width'); ?>" type="text" value="<?php echo esc_attr($instance['video_width']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id('video_height'); ?>"><?php _e('Video Width','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('video_height'); ?>" name="<?php echo $this->get_field_name('video_height'); ?>" type="text" value="<?php echo esc_attr($instance['video_height']); ?>" />
	</p>	
	
	<p>
		<select id="<?php echo $this->get_field_id( 'title_font_weight' ); ?>" name="<?php echo $this->get_field_name( 'title_font_weight' ); ?>">
			<option value="bold" <?php selected( $instance['title_font_weight'], 'bold' ); ?>><?php _e('Bold','gabfire-widget-pack'); ?></option>
			<option value="normal" <?php selected( $instance['title_font_weight'], 'normal' ); ?>><?php _e('Normal','gabfire-widget-pack'); ?></option>
		</select>
		<label for="<?php echo $this->get_field_id( 'title_font_weight' ); ?>"><?php _e('Post Title Font Weight','gabfire-widget-pack'); ?></label>
	</p>	
	
	<p>
		<label for="<?php echo $this->get_field_id('title_font_family'); ?>"><?php _e('Post Title Font Family','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_font_family'); ?>" name="<?php echo $this->get_field_name('title_font_family'); ?>" type="text" value="<?php echo esc_attr($instance['title_font_family']); ?>" />
	</p>		
	
	<p>
		<label for="<?php echo $this->get_field_id('title_font_size'); ?>"><?php _e('Post Title Font Size','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title_font_size'); ?>" name="<?php echo $this->get_field_name('title_font_size'); ?>" type="text" value="<?php echo esc_attr($instance['title_font_size']); ?>" />
	</p>	
<?php
	}
}

function register_gabfire_videos() {
	register_widget('gabfire_videos');
}

add_action('widgets_init', 'register_gabfire_videos');