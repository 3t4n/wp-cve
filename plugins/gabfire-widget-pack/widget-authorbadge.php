<?php
if ( !defined('ABSPATH')) exit;

/* ********************
 * Add Twitter, LinkedIn and Facebook fields
 * into WordPress admin -> user field.
 ******************************************************************** */
function add_twitter_contactmethod( $contactmethods ) {
  // Add Twitter
  if ( !isset( $contactmethods['twitter'] ) )
	$contactmethods['twitter'] = 'Twitter Username';
	
  if ( !isset( $contactmethods['facebook'] ) )
	$contactmethods['facebook'] = 'Facebook URL';

  if ( !isset( $contactmethods['linkedin'] ) )
	$contactmethods['linkedin'] = 'LinkedIn URL';	

  return $contactmethods;
}
add_filter( 'user_contactmethods', 'add_twitter_contactmethod', 10, 1 );

class gabfire_authorbadge extends WP_Widget {

	function __construct() {
		$widget_ops = array( 'classname' => 'gabfire_authorbadge', 'description' => 'Display widget with a big icon' );
		$control_ops = array( 'width' => 350, 'id_base' => 'gabfire_authorbadge' );
		parent::__construct( 'gabfire_authorbadge', 'Gabfire: Author Badge', $widget_ops, $control_ops);	
	}
	
	public function widget($args, $instance) {
		extract( $args );
		$picsize     = $instance['picsize'];
		$f_twitter     = $instance['f_twitter'];
		$f_facebook     = $instance['f_facebook'];
		$f_viewwebsite     = $instance['f_viewwebsite'];
		$f_allposts     = $instance['f_allposts'];
		$social_heading = $instance['social_heading'];	
	
		if ( get_the_author_meta( 'description' ) and (is_single() or is_author()) ) {
		echo $before_widget;
				?>
					<p class="first_p"><?php printf( esc_attr__( 'About %s', 'gabfire-widget-pack' ), get_the_author() ); ?></p>
					
					<p>
						<?php echo get_avatar( get_the_author_meta('email'), $picsize ); ?>
						<?php the_author_meta( 'description' ); ?>
					</p>
					
					<h3 class="widget-innertitle"><?php echo $social_heading; ?></h3>
					
					<?php if ( get_the_author_meta( 'twitter' ) ) { ?>
						<a class="author_social t_link" href="//www.twitter.com/<?php the_author_meta('twitter'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_twitter; ?>
						</a>
					<?php } ?>
					
					<?php if ( get_the_author_meta( 'facebook' ) ) { ?>
						<a class="author_social f_link" href="<?php the_author_meta('facebook'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_facebook; ?>
						</a>
					<?php } ?>
					
					<?php if ( !is_author() ) { ?>
						<a class="author_social a_link" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
							<?php echo $f_allposts; ?>
						</a>
					<?php } ?>
					
					<?php if ( get_the_author_meta( 'user_url' ) ) { ?>
						<a class="author_social w_link" href="<?php the_author_meta('user_url'); ?>" rel="nofollow" target="_blank">
							<?php echo $f_viewwebsite; ?>
						</a>
					<?php } 
		echo "<div class='clear'></div>$after_widget"; 
		}
	}
		
	function update($new_instance, $old_instance) {  
		$instance['picsize']  = ( ! empty( $new_instance['picsize'] ) ) ? sanitize_text_field((int) $new_instance['picsize'] ) : '';
		$instance['f_twitter'] = ( ! empty( $new_instance['f_twitter'] ) ) ? sanitize_text_field( $new_instance['f_twitter'] ) : '';
		$instance['f_facebook'] = ( ! empty( $new_instance['f_facebook'] ) ) ? sanitize_text_field( $new_instance['f_facebook'] ) : '';
		$instance['f_viewwebsite'] = ( ! empty( $new_instance['f_viewwebsite'] ) ) ? sanitize_text_field( $new_instance['f_viewwebsite'] ) : '';
		$instance['f_allposts'] = ( ! empty( $new_instance['f_allposts'] ) ) ? sanitize_text_field( $new_instance['f_allposts'] ) : '';	
		$instance['social_heading'] = ( ! empty( $new_instance['social_heading'] ) ) ? sanitize_text_field( $new_instance['social_heading'] ) : '';
		return $new_instance;
	}	  
 
	function form($instance) {
		$defaults	= array(
			'social_heading' => 'Connect',
			'picsize' => '60',
			'f_twitter' => 'Follow on Twitter',
			'f_facebook' => 'Connect on Facebook',
			'f_viewwebsite' => 'Visit Website',
			'f_allposts' => 'View all Posts'
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); 
	?>
	
		<p class="gabfire_widgetinfo">
			<?php _e('Author badge shows at single post and author pages only. This badge will not shown if Author bio is left empty.','gabfire-widget-pack'); ?>
		<p>
				
		<strong><?php _e('Link Label:','gabfire-widget-pack'); ?></strong>
		<div style="background-color: #efefef;border:1px solid #ddd;padding:9px;overflow:hidden;margin-bottom:10px">
			<div style="float:left;width:163px;">
				<p>
					<label for="<?php echo $this->get_field_id('social_heading'); ?>"><?php _e('Social Links Heading','gabfire-widget-pack'); ?></label>
					<input class="widefat"  id="<?php echo $this->get_field_id('social_heading'); ?>" name="<?php echo $this->get_field_name('social_heading'); ?>" type="text" value="<?php echo esc_attr( $instance['social_heading'] ); ?>" />
				</p>			

				<p>
					<label for="<?php echo $this->get_field_id('f_facebook'); ?>"><?php _e('Facebook Link','gabfire-widget-pack'); ?></label>
					<input class="widefat"  id="<?php echo $this->get_field_id('f_facebook'); ?>" name="<?php echo $this->get_field_name('f_facebook'); ?>" type="text" value="<?php echo esc_attr( $instance['f_facebook'] ); ?>" />
				</p>	
			</div>

			<div style="float:right;width:163px;">
				<p>
					<label for="<?php echo $this->get_field_id('f_viewwebsite'); ?>"><?php _e('Author\'s Website','gabfire-widget-pack'); ?></label>
					<input class="widefat"  id="<?php echo $this->get_field_id('f_viewwebsite'); ?>" name="<?php echo $this->get_field_name('f_viewwebsite'); ?>" type="text" value="<?php echo esc_attr( $instance['f_viewwebsite'] ); ?>" />
				</p>		
				
				<p>
					<label for="<?php echo $this->get_field_id('f_allposts'); ?>"><?php _e('Author\'s Posts','gabfire-widget-pack'); ?></label>
					<input class="widefat"  id="<?php echo $this->get_field_id('f_allposts'); ?>" name="<?php echo $this->get_field_name('f_allposts'); ?>" type="text" value="<?php echo esc_attr( $instance['f_allposts'] ); ?>" />
				</p>				
				
				<p>
					<label for="<?php echo $this->get_field_id('f_twitter'); ?>"><?php _e('Twitter Link','gabfire-widget-pack'); ?></label>
					<input class="widefat"  id="<?php echo $this->get_field_id('f_twitter'); ?>" name="<?php echo $this->get_field_name('f_twitter'); ?>" type="text" value="<?php echo esc_attr( $instance['f_twitter'] ); ?>" />
				</p>	
			</div>
		</div>
		
        <p>
            <label for="<?php echo $this->get_field_id('picsize'); ?>"><?php _e('The avatar size','gabfire-widget-pack'); ?></label>
            <input class="widefat"  id="<?php echo $this->get_field_id('picsize'); ?>" name="<?php echo $this->get_field_name('picsize'); ?>" type="text" value="<?php echo esc_attr( $instance['picsize'] ); ?>" />
        </p>
		
		<div style="clear:both"></div>
		
		<p><?php _e('Go to User profile page to enter Facebook, Twitter and Author URL details','gabfire-widget-pack'); ?></p>
	
<?php
	}
}

function register_gabfire_authorbadge() {
	register_widget('gabfire_authorbadge');
}

add_action('widgets_init', 'register_gabfire_authorbadge');