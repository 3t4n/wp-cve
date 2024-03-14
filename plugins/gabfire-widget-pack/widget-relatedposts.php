<?php
if ( !defined('ABSPATH')) exit;

class gabfire_relatedposts extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_relatedposts', 'description' => 'Display related post with thumbnails' );
		$control_ops = array( 'width' => 250, 'id_base' => 'gabfire_relatedposts' );
		parent::__construct( 'gabfire_relatedposts', 'Gabfire: Related Posts Thumbs', $widget_ops, $control_ops);	
	}
	
	public function widget($args, $instance) {
		extract( $args );
		$title    = $instance['title'];
		$postnr    = $instance['postnr'];
		$liststyle = $instance['liststyle'];

		echo $before_widget;
			global $post,$page;
			$tags = wp_get_post_tags($post->ID);
			if ($tags) {
				$tag_ids = array();
				foreach($tags as $individual_tag) $tag_ids[] = $individual_tag->term_id;
				$args=array(
				'tag__in' => $tag_ids,
				'post__not_in' => array($post->ID),
				'posts_per_page'=> $postnr, // Enter the Number of posts that will be shown.
				'ignore_sticky_posts'=> 1,
				'orderby'=>'rand' // Randomize the posts
				);
				$my_query = new wp_query( $args );

				if( $my_query->have_posts() ) {	
					if ( $title ) {
						echo $before_title . $title . $after_title;
					}
					
					if ( $liststyle == "vertical" ) {
						echo '<ul>';
						while( $my_query->have_posts() ) {
						$my_query->the_post(); ?>
							<li><div class="gab_relateditem_vertical">
								<a href="<?php the_permalink()?>" rel="bookmark" class="related_posttitle" title="<?php the_title(); ?>">
									<?php the_title(); ?>
								</a>
							 </div></li>
						<?php }
						echo '</ul>';					
						
					} else {
						if ($postnr == 2) {
							$width = '46%';
						} elseif ($postnr == 3) {
							$width = '29%';
						} elseif ($postnr == 4) {
							$width = '21%';
						} elseif ($postnr == 5) {
							$width = '16%';
						}
						
						while( $my_query->have_posts() ) {
						$my_query->the_post(); ?>
							<div class="gab_relateditem" style="width:<?php echo $width; ?>;">
								<a href="<?php the_permalink()?>" rel="bookmark" class="related_postthumb" title="<?php the_title(); ?>">
									<?php the_post_thumbnail('thumbnail'); ?>
								</a>
								<a href="<?php the_permalink()?>" rel="bookmark" class="related_posttitle" title="<?php the_title(); ?>">
									<?php the_title(); ?>
								</a>
							 </div>
						<?php }
						echo '<div class="clear clearfix"></div>';
					}
				}
			}
			wp_reset_query();
		echo "<div class='clear'></div>$after_widget";
		}
		
	function update($new_instance, $old_instance) {  
		$instance['title']  = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';	
		$instance['postnr']  = ( ! empty( $new_instance['postnr'] ) ) ? sanitize_text_field( (int)$new_instance['postnr'] ) : '';
		$instance['liststyle']  = ( ! empty( $new_instance['liststyle'] ) ) ? sanitize_text_field( $new_instance['liststyle'] ) : '';
		
		return $new_instance;
	}	  

	function form($instance) {
		$defaults	= array( 'title' => 'Related Posts', 'postnr' => '5', 'liststyle' => 'horizontal');
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>

	<p>
		<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','gabfire-widget-pack'); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_name( 'postnr' ); ?>"><?php _e('Number of Posts to display?','gabfire-widget-pack'); ?></label>
		<select id="<?php echo $this->get_field_id( 'postnr' ); ?>" name="<?php echo $this->get_field_name( 'postnr' ); ?>">			
		<?php
			for ( $i = 2; $i <= 5; ++$i )
			echo "<option value='$i' " . selected( $instance['postnr'], $i, false ) . ">$i</option>";
		?>
		</select>
	</p>

	<p>
		<label for="<?php echo $this->get_field_name( 'liststyle' ); ?>"><?php _e('Horizontal or vertical list','gabfire-widget-pack'); ?></label>
		<select id="<?php echo $this->get_field_id( 'liststyle' ); ?>" name="<?php echo $this->get_field_name( 'liststyle' ); ?>">
			<option value="horizontal" <?php if ( 'horizontal' == $instance['liststyle'] ) echo 'selected="selected"'; ?>><?php _e('Horizontal','gabfire-widget-pack'); ?></option>
			<option value="vertical" <?php if ( 'vertical' == $instance['liststyle'] ) echo 'selected="selected"'; ?>><?php _e('Vertical','gabfire-widget-pack'); ?></option>    
		</select>
	</p>

	
<?php
	}
}

function register_gabfire_relatedposts() {
	register_widget('gabfire_relatedposts');
}

add_action('widgets_init', 'register_gabfire_relatedposts');