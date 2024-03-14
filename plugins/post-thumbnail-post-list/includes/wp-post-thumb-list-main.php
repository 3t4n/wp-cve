<?php
/**
 * recent post widget
 */
class wp_post_thumb_list_widget extends WP_Widget {



	// Create Widget //
	function __construct() {
		$widget_ops = array( 'classname' => 'wp_post_thumb_list_widget', 'description' => esc_html__('A widget that display show latest post with thumbnail and post list style from all categories', 'wp-post-thumb-list') );
		$control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wp_post_thumb_list_widget' );
		parent::__construct( 'wp_post_thumb_list_widget', esc_html__('WP Post Thumbnail & Post List Widget', 'wp-post-thumb-list'), $widget_ops, $control_ops );
	}
	
	//  Post List Widget Variable Settings
	function widget( $args, $instance ) {
		extract( $args );
		
		$title 			= apply_filters('widget_title', (!isset($instance['title']) ? '' : $instance['title']) );
		$categories 	= (!isset($instance['categories'])? '': $instance['categories']);
		$post_count 	= (!isset($instance['post_count'])? '': $instance['post_count']);
		$order 	= (!isset($instance['order'])? '': $instance['order']);
		$style 	= (!isset($instance['style'])? '': $instance['style']);
		$colors 	= (!isset($instance['colors'])? '': $instance['colors']);
		$post_meta 	= (!isset($instance['post_meta'])? '': $instance['post_meta']);
		$the_excerpt 	= (!isset($instance['the_excerpt'])? '': $instance['the_excerpt']);

		$query = array(
			'posts_per_page' => $post_count,
			'order' => $order,
			'nopaging' => 0,
			'post_status' => 'publish',
			"ignore_sticky_posts" => true,
			'cat' => $categories
		);

		$args = new WP_Query($query);
		if ($args->have_posts()) :

			print $before_widget;

		if ( $title )
			print $before_title . $title . $after_title;
		?>
		
		<!-- Post Markup -->
		<div class="wp-post-thum-list-main <?php echo esc_attr($style);?> <?php echo esc_attr($colors);?>">
			<div class="list-unstyled clearfix">
				<?php  while ($args->have_posts()) : $args->the_post(); ?>
					<div class="single-a-post">
						<?php if (  (function_exists('has_post_thumbnail')) && (has_post_thumbnail())  ) { ?>
							<div class="post-thumb big-img">
								<a href="<?php echo esc_url( get_permalink() ); ?>" rel="bookmark" title="<?php esc_attr( the_title() ); ?>"><?php the_post_thumbnail('full'); ?></a>
								<?php if( $style==('styleone')) : ?>
								<div class="meta-top">
									<div class="post-meta">
										<span class="author"><i class="fa fa-user"></i><a href="<?php echo esc_attr(get_the_author_link());?>"><?php echo get_the_author();?></a></span>
										<span><time class="post-date" datetime="<?php esc_attr( the_time( 'c' ) ); ?>"><i class="fa fa-clock-o"></i><?php echo get_the_date(); ?></time></span>
										<span class="comment"><i class="fa fa-comments"></i><?php echo esc_html(get_comments_number());?></span>
									</div>
								</div>
								<?php endif;?>
							</div>
							<div class="content-top with-img">
								<div class="content">
									<?php if( $style==('styletwo')) : ?>
									<div class="post-meta">
										<span><time class="post-date" datetime="<?php esc_attr( the_time( 'c' ) ); ?>"><i class="fa fa-clock-o"></i><?php echo get_the_date(); ?></time></span>
										<span class="comment"><i class="fa fa-comments"></i><?php echo esc_html(get_comments_number());?></span>
									</div>
									<?php endif;?>
									<h4 class="title"><a href="<?php echo esc_url( get_permalink()); ?>" rel="bookmark" title="<?php esc_attr( the_title() ); ?>"><?php the_title(); ?></a></h4>
								</div>
								<?php if( $the_excerpt == 1 && $style==('styleone')) : ?>
									<?php the_excerpt(); ?>
								<?php endif;?>
								<?php if( $style==('styleone')) : ?>
								<a href="<?php echo esc_url( get_permalink()); ?>" class="wp-a-btn"><?php esc_html_e('Read more');?></a>
								<?php endif;?>
							</div>
						<?php } else{?>
							<div class="content-top">
								<div class="content">
									<?php if( $post_meta == 1 ) : ?>
									<div class="post-meta">
										<span class="author"><i class="fa fa-user"></i><a href="<?php echo esc_attr(get_the_author_link());?>"><?php echo get_the_author();?></a></span>
										<span><time class="post-date" datetime="<?php esc_attr( the_time( 'c' ) ); ?>"><i class="fa fa-clock-o"></i><?php echo get_the_date(); ?></time></span>
										<?php if( $style==('styleone')) : ?>
											<span class="comment"><i class="fa fa-comments"></i><?php echo esc_html(get_comments_number());?></span>
										<?php endif;?>
									</div>
									<?php endif;?>
									<h4 class="title"><a href="<?php echo esc_url( get_permalink()); ?>" rel="bookmark" title="<?php esc_attr( the_title() ); ?>"><?php the_title(); ?></a></h4>
								</div>
								<?php if( $the_excerpt == 1 && $style==('styleone')) : ?>
									<?php the_excerpt(); ?>
								<?php endif;?>
								<?php if( $style==('styleone')) : ?>
								<a href="<?php echo esc_url( get_permalink()); ?>" class="wp-a-btn"><?php esc_html_e('Read more');?></a>
								<?php endif;?>
							</div>
						<?php } ?>
						<div class="clearfix"></div>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			</div>
		</div>
		<!--/ End Post Markup -->
	<?php endif; ?>
	<?php
	print $after_widget;
	}

	//  Widget Default Settings
	function form( $instance ) {

		$defaults = array(
			'title' => esc_html__('Latest Posts', 'wp-post-thumb-list'),
			'post_count' => 5,
			'categories' => '',
		);
		
		if(isset($instance['style'])){
		  $style = $instance['style'];
		}else{
		 $style = 'styleone';
		}  

		if(isset($instance['order'])){
		  $order = $instance['order'];
		}else{
		 $order = 'desc';
		};  
		
		if(isset($instance['post_meta'])){
		  $post_meta = $instance['post_meta'];
		}else{
		 $post_meta = 1;
		}     
		
		if(isset($instance['the_excerpt'])){
		  $the_excerpt = $instance['the_excerpt'];
		}else{
		 $the_excerpt = 1;
		}     
		
		if(isset($instance['colors'])){
		  $colors = $instance['colors'];
		}else{
		 $colors = 'green';
		};  
		
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title -->
		<p>
			<label for="<?php print $this->get_field_id( 'title' ); ?>"><?php esc_html_e('Title:', 'wp-post-thumb-list'); ?></label>
			<input  type="text" class="widefat" id="<?php print $this->get_field_id( 'title' ); ?>" name="<?php print $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $instance['title'] ); ?>"  />
		</p>
		
		<!-- WP Amazing Style -->
		<p>
		  <label for="<?php echo $this->get_field_id('style'); ?>"><?php _e("Select Design", 'wp-post-thumb-list')?></label>
		  <br/>
		  <select id="<?php echo $this->get_field_id('style'); ?>" name="<?php echo $this->get_field_name('style'); ?>"  style="width:100%;" type="text">
			<option value='styleone' <?php if($style == 'styleone'): echo "selected: selected"; endif;?>>
				<?php _e("Style1 - Post Thumbnail", 'wp-post-thumb-list')?>
			</option>
			<option value='styletwo' <?php if($style == 'styletwo'): echo "selected: selected"; endif; ?>>
				<?php _e("Style2 - Post List", 'wp-post-thumb-list')?>
			</option>
		  </select>
		</p>
		
		<!-- Post Categories -->
		<p>
			<label for="<?php print $this->get_field_id('categories'); ?>"><?php esc_html_e('Select Categories', 'wp-post-thumb-list'); ?></label>
			
			<select id="<?php print $this->get_field_id('categories'); ?>" name="<?php print $this->get_field_name('categories'); ?>" class="widefat categories" style="width:100%;">
				<option value='all' <?php if ('all' == $instance['categories']) echo 'selected="selected"'; ?>>All categories</option>
				<?php $categories = get_categories('hide_empty=0&depth=1&type=post'); ?>
				<?php foreach($categories as $category) { ?>
				<option value='<?php print $category->term_id; ?>' <?php if ($category->term_id == $instance['categories']) echo 'selected="selected"'; ?>><?php print $category->cat_name; ?></option>
				<?php } ?>
			</select>
		</p>
		
		<!-- Post Count -->
		<p>
			<label for="<?php print $this->get_field_id( 'post_count' ); ?>"><?php esc_html_e('Type Post Count', 'wp-post-thumb-list'); ?></label>
			<input  type="text" class="widefat" id="<?php print $this->get_field_id( 'post_count' ); ?>" name="<?php print $this->get_field_name( 'post_count' ); ?>" value="<?php echo esc_attr( $instance['post_count'] ); ?>" size="3" />
		</p>
		
		<p>
          <input type="checkbox" id="<?php echo $this->get_field_id('post_meta') ?>" name="<?php echo $this->get_field_name('post_meta') ?>"value="1" <?php checked($post_meta,1); ?> class="widefat">
        <label for="<?php echo $this->get_field_id('post_meta') ?>">Show Post Meta?</label>
       </p>
	   
		<p>
          <input type="checkbox" id="<?php echo $this->get_field_id('the_excerpt') ?>" name="<?php echo $this->get_field_name('the_excerpt') ?>"value="1" <?php checked($the_excerpt,1); ?> class="widefat">
        <label for="<?php echo $this->get_field_id('the_excerpt') ?>">Show Post excerpt?</label>
       </p>
		
		<!-- Post Order -->
		<p>
		  <label for="<?php echo $this->get_field_id('order'); ?>"><?php _e("Post Order", 'wp-post-thumb-list')?></label>
		  <br/>
		  <select id="<?php echo $this->get_field_id('order'); ?>" name="<?php echo $this->get_field_name('order'); ?>"  style="width:100%;" type="text">
			<option value='desc' <?php if($order == 'desc'): echo "selected: selected"; endif;?>>
				<?php _e("Descending", 'wp-post-thumb-list')?>
			</option>
			<option value='asc' <?php if($order == 'asc'): echo "selected: selected"; endif; ?>>
				<?php _e("Ascending", 'wp-post-thumb-list')?>
			</option>
		  </select>
		</p>
		
		<!-- Theme Colors  -->
		<p>
		  <label for="<?php echo $this->get_field_id('colors'); ?>"><?php _e("Choose Flat Colors", 'wp-post-thumb-list')?></label>
		  <br/>
		  <select id="<?php echo $this->get_field_id('colors'); ?>" name="<?php echo $this->get_field_name('colors'); ?>"  style="width:100%;" type="text">
			<option value='green' <?php if($colors == 'green'): echo "selected: selected"; endif;?>>
				<?php _e("Color1 - Green", 'wp-post-thumb-list')?>
			</option>
			<option value='red' <?php if($colors == 'red'): echo "selected: selected"; endif; ?>>
				<?php _e("Color2 - Red", 'wp-post-thumb-list')?>
			</option>
			<option value='yellow' <?php if($colors == 'yellow'): echo "selected: selected"; endif; ?>>
				<?php _e("Color3 - Yellow", 'wp-post-thumb-list')?>
			</option>
		  </select>
		</p>
		
		
		<?php
	}
	
	//  Widget Updates
	Public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		$instance['title'] 			= strip_tags( $new_instance['title'] );
		$instance['categories'] 	= $new_instance['categories'];
		$instance['post_count'] 	= strip_tags( $new_instance['post_count'] );
		$instance['order'] = strip_tags($new_instance['order']);
		$instance['style'] = strip_tags($new_instance['style']);
		$instance['post_meta'] = strip_tags($new_instance['post_meta']);
		$instance['the_excerpt'] = strip_tags($new_instance['the_excerpt']);
		$instance['colors'] = strip_tags($new_instance['colors']);
		return $instance;
	}
	
}