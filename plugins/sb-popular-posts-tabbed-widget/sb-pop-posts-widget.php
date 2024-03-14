<?php
/*
Plugin Name: SB Popular Posts Tabbed Widget
Plugin URI: http://scottbolinger.com/
Description: A lightweight, responsive, uncluttered widget to display popular posts, recent posts, and categories with tabs.
Version: 1.1
Author: Scott Bolinger
Author URI: http://scottbolinger.com/
License: GPL2
*/


/* Includes
--------------------------------------------------- */

include_once ('includes/scripts.php');


/* Create & Display Widget
--------------------------------------------------- */

class sb_tabbed_widget extends WP_Widget {
 
    /** constructor */
    function sb_tabbed_widget() {
        parent::WP_Widget(false, $name = 'SB Popular Posts Tabbed Widget');	
    }
 
    /** @see WP_Widget::widget */
	function widget($args, $instance) {
		extract( $args );
		$number 	= $instance['number']; // Number of posts to show
		$checkbox   = $instance['checkbox']; // Show post thumbnails
		$checkbox2  = $instance['checkbox2']; // Show post meta

		?>

			  <?php echo $before_widget; ?>
				  
				  <div class="sb_tabbed">
				  	<!-- The tabs -->
				  	<ul class="sb_tabs">
				  		<li class="t1"><a class="t1 tab" title="Tab 1"><?php _e('Popular') ?></a></li>
				  		<li class="t2"><a class="t2 tab" title="Tab 2"><?php _e('Recent') ?></a></li>
				  		<li class="t3"><a class="t3 tab" title="Tab 3"><?php _e('Categories') ?></a></li>
				  	</ul>
				  
				  	<!-- tab 1 -->
				  	<div class="tab-content t1">

				  	<ul>
				  	<?php
				  	$sb_pop_posts_query = new WP_Query( array ( 'orderby' => 'comment_count', 'order' => 'DESC', 'posts_per_page' => $number, 'post__not_in' => get_option( 'sticky_posts' ) ) );
				  	
					  	if($sb_pop_posts_query->have_posts()):
						while($sb_pop_posts_query->have_posts()):
					  	$sb_pop_posts_query->the_post();
					  	
					 ?>
					 
					 <li>

					 	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
						 	<?php if ( has_post_thumbnail() && $checkbox == true ) { the_post_thumbnail( array(50,50), array('class' => 'alignleft') ); } elseif ( !has_post_thumbnail() && $checkbox == true ) { ?> <img src="<?php echo plugin_dir_url(__FILE__); ?>includes/images/default-thumb.png" alt="<?php the_title(); ?>" class="alignleft" width="50" height="50" /> <?php } ?>
						 	<?php echo get_the_title(); ?>
					 
						 </a>
						 
						 <?php if ( $checkbox2 == true ) { ?> 
						 <span class="sb-comment-meta"><?php echo comments_number( '', '1 Comment', '% Comments' ); ?></span>
						 <?php } ?>
					 
					 </li>
				  	
				  	<?php endwhile;
				  		  endif;
				  		  /* Restore original Post Data */
				  		  wp_reset_postdata();
				    ?>
				  	</ul>
				  	
				  	</div>
				  
				  	<!-- tab 2 -->
				  	<div class="tab-content t2">
				  	
				  	<ul>
				  	<?php
				  	$sb_recent_posts_query = new WP_Query( array ( 'posts_per_page' => $number ) );
				  	
					  	if($sb_recent_posts_query->have_posts()):
						while($sb_recent_posts_query->have_posts()):
					  	$sb_recent_posts_query->the_post();
					  	
					 ?>
					 
					 <li>
					 
					 	<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
					 	
					 		<?php if ( has_post_thumbnail() && $checkbox == true ) { the_post_thumbnail( array(50,50), array('class' => 'alignleft') ); } elseif ( !has_post_thumbnail() && $checkbox == true ) { ?> <img src="<?php echo plugin_dir_url(__FILE__); ?>includes/images/default-thumb.png" alt="<?php the_title(); ?>" class="alignleft" width="50" height="50" /> <?php } ?>
					 
						 	<?php the_title(); ?>
					 
						 </a>
						 
						  <?php if ( $checkbox2 == true ) { ?> 
						  <span class="sb-date-meta"><?php the_date(); ?></span>
						  <?php } ?>
					 
					 </li>
				  	
				  	<?php endwhile;
				  		  endif; 
				  		  /* Restore original Post Data */
				  		  wp_reset_postdata();
				    ?>
				  	</ul>
				  	
				  	</div>
				  
				  	<!-- tab 3 -->
				  	<div class="tab-content t3">
				  	
				  		<ul>
		    			<?php 
		    			
		    			$cat_args=array(
						'orderby' => 'name',
						'order' => 'ASC',
						'number' => $number
						);
						$categories=get_categories($cat_args);
						
						foreach($categories as $category) { 
						  echo '<li><a href="' . get_category_link( $category->term_id ) . '" title="' . sprintf( __( "View all posts in %s" ), $category->name ) . '" ' . '>' . $category->name.'</a>';
						  if ( $checkbox2 == true ) { 
							  echo '<span class="sb-cat-meta"> (' . $category->count . ')</span></li>';
						     } else { echo '</li>'; }
						  }
						?>	
		    			</ul>
				  	</div>
				  
				  </div><!-- tabbed -->

			  <?php echo $after_widget; ?>
		<?php
	}
	

/* Update @see WP_Widget::update
--------------------------------------------------- */

	function update($new_instance, $old_instance) {
		$instance = $old_instance;

		$instance['number'] = wp_kses_data($new_instance['number']);
		$instance['checkbox'] = strip_tags($new_instance['checkbox']);
		$instance['checkbox2'] = strip_tags($new_instance['checkbox2']);
		return $instance;
	}

/* Widget Options @see WP_Widget::form
--------------------------------------------------- */ 
    
	function form($instance) {
		$defaults = array(
			'number'	=> 5,
			'checkbox'	=> true,
			'checkbox2'	=> true,
		);
		extract( wp_parse_args( $instance, $defaults ) );
		
		?>
		<p>
		  <label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of posts to display:'); ?></label>
		  <input size="2" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo esc_attr( $number ); ?>" />
		</p>
		<p>
		<input id="<?php echo $this->get_field_id('checkbox'); ?>" name="<?php echo $this->get_field_name('checkbox'); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox ); ?>/>
        <label for="<?php echo $this->get_field_id('checkbox'); ?>"><?php _e('Display thumbnails'); ?></label>
		</p>
		<p>
		<input id="<?php echo $this->get_field_id('checkbox2'); ?>" name="<?php echo $this->get_field_name('checkbox2'); ?>" type="checkbox" value="1" <?php checked( '1', $checkbox2 ); ?>/>
        <label for="<?php echo $this->get_field_id('checkbox2'); ?>"><?php _e('Display meta (date, comments)'); ?></label>
		</p>
		<?php
	}
	
}

add_action('widgets_init', create_function('', 'return register_widget("sb_tabbed_widget");'));