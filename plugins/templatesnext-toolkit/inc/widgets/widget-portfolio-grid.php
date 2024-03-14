<?php

	/*
	*	nx Portfolio Widget
	*	------------------------------------------------
	*	TemplatesNext
	* 	Copyright TemplatesNext 2014 - http://www.TemplatesNext.org
	*/

	/*
	*	Plugin Name: NX Portfolio Grid
	*	Plugin URI: http://www.TemplatesNext.org
	*	Description: NX Portfolio Grid
	*	Author: templatesNext
	*	Version: 1.0
	*	Author URI: http://www.TemplatesNext.org
	*/			
	
	// Register widget
	add_action( 'widgets_init', 'init_nx_portfolio_grid' );
	function init_nx_portfolio_grid() { return register_widget('nx_portfolio_grid'); }
	
	class nx_portfolio_grid extends WP_Widget {
		//function nx_portfolio_grid() {
		function __construct() {

			$widget_ops = array( 'classname' => 'nx-widget-portfolio-grid', 'description' => 'NX Widget for Portofolio in grid format' );
        	parent::__construct( 'nx-widget-portfolio-grid', 'NX Portfolio Grid', $widget_ops );			
		}
	
		function widget( $args, $instance ) {
			global $post;
			extract($args);
			$image = "";
	
			// Widget Options
			$title 	 = apply_filters('widget_title', $instance['title'] ); // Title		
			$number	 = $instance['number']; // Number of posts to show
			
			echo $before_widget;
			
		    if ( $title ) echo $before_title . $title . $after_title;
				
			$recent_portfolio = new WP_Query(
				array(
					'post_type' => 'portfolio',
					'posts_per_page' => $number
					)
			);
			
			$count = 0;
			
			if( $recent_portfolio->have_posts() ) : 
			
			?>
			
			<ul class="portfolio-grid clearfix">
				
				<?php while( $recent_portfolio->have_posts()) : $recent_portfolio->the_post();
				
				$post_title = get_the_title();
				$post_permalink = get_permalink();
				
				$thumb_image = get_post_thumbnail_id();
				$thumb_img_url = wp_get_attachment_url( $thumb_image, 'small' );
				if($thumb_img_url)
				{
					$image = aq_resize( $thumb_img_url, 96, 96, true, false);
				}
				?>
				<?php if ($image) { ?>
				<li class="grid-item-<?php echo esc_attr($count); ?>">
					<a href="<?php echo esc_url($post_permalink); ?>" class="grid-image tooltip2" title="<?php echo esc_html($post_title); ?>">
						<img src="<?php echo esc_url($image['0']); ?>" width="<?php echo esc_attr($image[1]); ?>" alt="<?php echo esc_html($post_title); ?>" />
					</a>
				</li>
				<?php } ?>
				
				<?php $count++; wp_reset_query(); endwhile; ?>
			</ul>
				
			<?php endif; ?>			
			
			<?php
			
			echo $after_widget;
		}
	
		/* Widget control update */
		function update( $new_instance, $old_instance ) {
			$instance    = $old_instance;
				
			$instance['title']  = strip_tags( $new_instance['title'] );
			$instance['number'] = strip_tags( $new_instance['number'] );
			return $instance;
		}
		
		/* Widget settings */
		function form( $instance ) {	
		
			    // Set defaults if instance doesn't already exist
			    if ( $instance ) {
					$title  = $instance['title'];
			        $number = $instance['number'];
			    } else {
				    // Defaults
					$title  = '';
			        $number = '6';
			    }
				
				// The widget form
				?>
                <div class="widget-content">
                    <p>
                        <label for="<?php echo $this->get_field_id('title'); ?>"><?php echo __( 'Title:', 'tx' ); ?></label>
                        <input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" class="widefat" />
                    </p>
                    <p>
                        <label for="<?php echo $this->get_field_id('number'); ?>"><?php echo __( 'Number of items to show:', 'tx' ); ?></label>
                        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
                    </p>
                </div>
		<?php 
		}
	
	}

?>