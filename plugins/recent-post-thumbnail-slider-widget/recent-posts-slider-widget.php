<?php
/*
Plugin Name: Recent Posts Thumbnail Slider Widget
Description: This plugin provides you recent post slider widget that allows you to display featured image of any posts and pages in widgetized sidebar as slider effect. Great ability to customize slider with slider options, post management options.
Version: 1.0
Author: Kundan Yevale
Author URI: http://profiles.wordpress.org/kundanyevale/
*/

include_once( 'recent-posts-slider-widget-display.php' );

class Recent_Posts_Thumbnail_Slider_Widget extends WP_Widget {
	/** constructor */	
	function __construct() {
    	$widget_ops = array(
			'classname'   => 'recent_posts_thumbnail_slider_widget', 
			'description' => __('This plugin provides you recent post slider widget that allows you to display featured image of any posts and pages in widgetized sidebar as slider effect.')
		);
    	parent::__construct('recent-posts-thumbnail-slider', __('A Recent Posts Thumbnail Slider Widget'), $widget_ops);
	}
	
	function widget($args, $instance) {
		
		global $post;
		
		$pluginurl = plugins_url( '' , __FILE__ );
		
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title']);
			
		echo $before_widget;

		if ($title) {
			echo $before_title . $title . $after_title;
		}
				
		if( ! $number = absint( $instance['number'] ) ) $number = 5;
		if( ! $show_type = $instance["show_type"] )  $show_type='post';
		if( ! $postids = $instance["postids"] ) $postids='';		
		if( ! $catids = $instance["categoryids"] )  $catids='';		
		if( ! $sliderspeed =  absint($instance["sliderspeed"] ))  $sliderspeed = 3000;
		if( ! $slider_auto_run = $instance["slider_auto_run"]) $slider_auto_run = 'on';
		if( ! $title_position =  $instance["title_position"] )  $title_position='top';		
		if( ! $thumb_w =  absint($instance["width"] ))  $thumb_w=150;	
		if( ! $thumb_h =  absint($instance["height"] ))  $thumb_h=150;
		if( ! $slider_effect =  $instance["slider_type"] ) $slider_effect='random';	
		if( ! $button_opacity =  $instance["button_opacity"] ) $button_opacity='1';
		if( ! $title_color =  $instance["title_color"] ) $title_color='#FFFFFF';
		
		$button_from_top = ($thumb_h/2)-(25);
		
		$default_sort_orders = array('date', 'title', 'comment_count', 'rand');
		
		$autorun = $instance['slider_auto_run'] ? 'true' : 'false';
		
		if($autorun == 'false')
		$sliderspeed = 'false';

		
		$title_visibility = $instance['title_visibility'] ? 'true' : 'false';		
		$button_visibility = $instance['button_visibility'] ? 'true' : 'false';		
		$slider_effect;
			
		if ( in_array($instance['sort_by'], $default_sort_orders) ) {			
				$sort_by = $instance['sort_by'];			
				$sort_order = $instance['sort_order'];			
		  } else {
			// by default, display latest first
			$sort_by = 'DATE';			
			$sort_order = 'DESC';			
		  }
			  
			  // post info array.			
			$my_args =array(
				
				'numberposts' => $number,
				
				'offset'      => 0,
			
				'category'=> $catids,				
				
				'meta_key' => '_thumbnail_id',				
				
				'include'=> $postids,
			
				'orderby' => $sort_by,
			
				'order' => $sort_order,
				
				'post_type' => $show_type,
				
				'post_status' => 'publish', 
				
				);
				

				$myposts = get_posts( $my_args );
				
				if(count($myposts)>0)
				{
				?>
      <div id="rpswContainer_2" class="featured-posts textwidget">      
      
				<div id="rpswSliderName_2" class="rpswSliderName_2 featured-post">
				
				<?php foreach( $myposts as $post ) :	setup_postdata($post);  ?>
				<?php 
					$thumbnail = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');					
				 ?>
 					<a href="<?php the_permalink(); ?>" rel="bookmark"  title="<?php the_title_attribute(); ?>">
					<img src="<?php echo $thumbnail[0]; ?>" alt="<?php the_title(); ?>" title="<?php the_title_attribute(); ?>" />					
					</a>
					
					<div class="rpswDescription_2 post-title">
					<h4>
					<a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php the_title_attribute(); ?>">
					<?php the_title(); ?>
					</a>
					</h4>
					</div>
				<?php endforeach; ?>	
					
				</div>
				
				<div id="rpswNameNavigation_2"></div>
				
				<script type="text/javascript">					
					jQuery(document).ready(function() {
							jQuery('.post-title').find('a').css('color','<?php echo $title_color; ?>');
							jQuery('.rpswNamePrev_2').css('top','<?php echo $button_from_top; ?>px');
							jQuery('.rpswNameNext_2').css('top','<?php echo $button_from_top; ?>px');

					});					
					effectsVersion1 = '<?php echo $slider_effect; ?>';					
					var demoSlider_2 = Sliderman.slider({container: 'rpswSliderName_2', width: <?php echo $thumb_w; ?>, height: <?php echo $thumb_h; ?>, effects: effectsVersion1,
						display: {							
							pause: true, // slider pauses on mouseover
							autoplay: <?php echo $sliderspeed; ?>, // 3000 = 3 seconds slideshow
							loading: {background: '#000000', opacity: 0.5, image: '<?php echo $pluginurl; ?>/img/loading.gif'},							<?php if($button_visibility=='false'){ ?>
							buttons: {hide: <?php echo $button_visibility ?>, opacity: <?php echo $button_opacity; ?>, prev: {className: 'rpswNamePrev_2', label: ''}, next: {className: 'rpswNameNext_2', label: ''}},
							<?php } ?>
							<?php if($title_visibility=='false'){ ?>
							description: {hide: <?php echo $title_visibility; ?>, position: '<?php echo $title_position; ?>'},
							<?php } ?>
							navigation: {container: 'rpswNameNavigation_2', label: '<img src="<?php echo $pluginurl; ?>/img/clear.gif" />'}
						}
					});

				</script>
	  </div>				
	 <?php  	
	 } 
	 echo $after_widget;
	}
	
	function update( $new_instance, $old_instance ) {		
		
				$instance = $old_instance;
				$instance['title'] = strip_tags($new_instance['title']); 
				$instance['title_color'] = strip_tags($new_instance['title_color']);        
				$instance['title_position'] = esc_attr($new_instance['title_position']);
				$instance['number'] = absint($new_instance['number']);
				$instance['width'] = absint($new_instance['width']);
				$instance['height'] = absint($new_instance['height']);
				$instance['sort_by'] = esc_attr($new_instance['sort_by']);
				$instance['sort_order'] = esc_attr($new_instance['sort_order']);
				$instance['slider_type'] = esc_attr($new_instance['slider_type']);
				$instance['sliderspeed'] = absint($new_instance['sliderspeed']);
				$instance['button_opacity'] = esc_attr($new_instance['button_opacity']);								
				$instance['show_type'] = esc_attr($new_instance['show_type']);				
				$instance['slider_auto_run'] = esc_attr($new_instance['slider_auto_run']);
				$instance['title_visibility'] = esc_attr($new_instance['title_visibility']);
				$instance['button_visibility'] = esc_attr($new_instance['button_visibility']);
				$instance['skip_no_thumb'] = esc_attr($new_instance['skip_no_thumb']);
				$instance['categoryids'] = strip_tags($new_instance['categoryids']); 
				$instance['postids'] = strip_tags($new_instance['postids']);
				
        		return $instance;
	}
	
	function form( $instance ) {
		$title = isset($instance['title']) ? esc_attr($instance['title']) : 'Recent Posts Slider';
		$sliderspeed = isset($instance['sliderspeed']) ? absint($instance['sliderspeed']) : 3000;
		$number = isset($instance['number']) ? absint($instance['number']) : 5;
		$width = isset($instance['width']) ? absint($instance['width']) : 150;
		$height = isset($instance['height']) ? absint($instance['height']) : 150;
		$title_color = isset($instance['title_color']) ? esc_attr($instance['title_color']) : '#FFFFFF';
		$show_type = isset($instance['show_type']) ? esc_attr($instance['show_type']) : 'post';
		
		$slider_type = isset($instance['slider_type']) ? esc_attr($instance['slider_type']) : 'random';
		$slider_auto_run = isset($instance['slider_auto_run']) ? esc_attr($instance['slider_auto_run']) : 'on';
		$button_visibility = isset($instance['button_visibility']) ? esc_attr($instance['button_visibility']) : '';		
		$button_opacity = isset($instance['button_opacity']) ? esc_attr($instance['button_opacity']) : '1';
		$sort_by = isset($instance['sort_by']) ? esc_attr($instance['sort_by']) : 'date';
		$sort_order = isset($instance['sort_order']) ? esc_attr($instance['sort_order']) : 'DESC';
		$title_visibility = isset($instance['title_visibility']) ? esc_attr($instance['title_visibility']) : '';
		$title_position = isset($instance['title_position']) ? esc_attr($instance['title_position']) : 'top';
		$categoryids =  isset($instance['categoryids']) ? esc_attr($instance['categoryids']) : '';
		$postids =  isset($instance['postids']) ? esc_attr($instance['postids']) : '';
		?>
		<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></p>

<table style="width:100%; border-collapse:collapse; border:1px solid #CCC;">
<tr>
<td style="border:1px solid #ccc">
<div style="text-align:center;margin:5px;"><strong>Slider Options</strong></div>
		<p>
        <label for="<?php echo $this->get_field_id("slider_type"); ?>">
        <?php _e('Slider Effect');	 ?>:        
		</label>
        <select id="<?php echo $this->get_field_id("slider_type"); ?>" name="<?php echo $this->get_field_name("slider_type"); ?>">
        <option value="random"<?php selected( $slider_type, "random" ); ?>>Random</option>        
        <option value="fade"<?php selected( $slider_type, "fade" ); ?>>Fade</option>
        <option value="move"<?php selected( $slider_type, "move" ); ?>>Move</option>
        </select>
        </p>
			<p>Slider Dimensions:<br />
		  <label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Width:');?>&nbsp;<input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>" type="text" value="<?php echo $width; ?>" size ="3" /></label>px<br />
			<label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height:');?><input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo $height; ?>" size ="3" /></label>px
	  </p>	  
		
         <p>
        <label for="<?php echo $this->get_field_id('sliderspeed'); ?>"><?php _e('Slider Speed:');?>&nbsp;&nbsp;&nbsp;<input id="<?php echo $this->get_field_id('sliderspeed'); ?>" name="<?php echo $this->get_field_name('sliderspeed'); ?>" type="text" value="<?php echo $sliderspeed; ?>" size ="3" /></label>ms
        </p>
        
         <p>
             <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("slider_auto_run"); ?>" name="<?php echo $this->get_field_name("slider_auto_run"); ?>"<?php checked( (bool) $slider_auto_run, true ); ?> />
            <label for="<?php echo $this->get_field_id("slider_auto_run"); ?>">        
                <?php _e( 'Slider Auto Run' ); ?>        
            </label>        
        </p>
        
        <p>        
            	<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("button_visibility"); ?>" name="<?php echo $this->get_field_name("button_visibility"); ?>"<?php checked( (bool) $button_visibility, true ); ?> />
        	<label for="<?php echo $this->get_field_id("button_visibility"); ?>">
                <?php _e( 'Hide Next/Previous Button' ); ?>        
            </label>
        
        </p>
        
        <p>
        <label for="<?php echo $this->get_field_id("button_opacity"); ?>">
        <?php _e('Next/Previous Button Opacity');	 ?>:        
        </label>
        <select id="<?php echo $this->get_field_id("button_opacity"); ?>" name="<?php echo $this->get_field_name("button_opacity"); ?>">        
          <option value="1"<?php selected( $button_opacity, "1" ); ?>>1</option>
          <option value="0.9"<?php selected( $button_opacity, "0.9" ); ?>>0.9</option>
          <option value="0.8"<?php selected( $button_opacity, "0.8" ); ?>>0.8</option>
          <option value="0.7"<?php selected( $button_opacity, "0.7" ); ?>>0.7</option>
          <option value="0.6"<?php selected( $button_opacity, "0.6" ); ?>>0.6</option>
          <option value="0.5"<?php selected( $button_opacity, "0.5" ); ?>>0.5</option>
          <option value="0.4"<?php selected( $button_opacity, "0.4" ); ?>>0.4</option>
          <option value="0.3"<?php selected( $button_opacity, "0.3" ); ?>>0.3</option>
          <option value="0.2"<?php selected( $button_opacity, "0.2" ); ?>>0.2</option>
          <option value="0.1"<?php selected( $button_opacity, "0.1" ); ?>>0.1</option>
        </select>
        </p>
</td>
</tr>
<tr>
   	<td style="border:1px solid #ccc">
   	<div style="text-align:center;margin:5px;"><strong>Slider Post Options</strong></div>
       
        <p><label for="<?php echo $this->get_field_id('number'); ?>"><?php _e('Number of Posts to Show:'); ?></label>
        <input id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" type="text" value="<?php echo $number; ?>" size="3" />
		</p>
       
		<p>
            <label for="<?php echo $this->get_field_id("sort_by"); ?>">        
        <?php _e('Sort by');?>:
            </label>
        <select id="<?php echo $this->get_field_id("sort_by"); ?>" name="<?php echo $this->get_field_name("sort_by");?>">
        
          <option value="date"<?php selected( $sort_by, "date" ); ?>>Date</option>
        
          <option value="title"<?php selected( $sort_by, "title" ); ?>>Title</option>
        
          <option value="comment_count"<?php selected( $sort_by, "comment_count" ); ?>>Number of comments</option>
        
          <option value="rand"<?php selected( $sort_by, "rand" ); ?>>Random</option>
        
        </select>
        </p>
        
        <p>        
            <label for="<?php echo $this->get_field_id("sort_order"); ?>">        
	        <?php _e('Order by');	 ?>:
          </label>
        <select id="<?php echo $this->get_field_id("sort_order"); ?>" name="<?php echo $this->get_field_name("sort_order"); ?>">
        <option value="ASC"<?php selected( $sort_order, "ASC" ); ?>>ASC</option>
          <option value="DESC"<?php selected( $sort_order, "DESC" ); ?>>DESC</option>                
        </select>        
        </p>
        
        <p>        
                <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("title_visibility"); ?>" name="<?php echo $this->get_field_name("title_visibility"); ?>"<?php checked( (bool) $title_visibility, true ); ?> />
            <label for="<?php echo $this->get_field_id("title_visibility"); ?>">        
                <?php _e( 'Hide Post Title' ); ?>        
            </label>
        
        </p>
        
		<p>        
            <label for="<?php echo $this->get_field_id("title_position"); ?>">
        <?php _e('Post Title Position:');	 ?>
             </label>
        <select id="<?php echo $this->get_field_id("title_position"); ?>" name="<?php echo $this->get_field_name("title_position"); ?>">        
          <option value="top"<?php selected( $title_position, "top" ); ?>>Top</option>        
          <option value="bottom"<?php selected( $title_position, "bottom" ); ?>>Bottom</option>                
        </select>
        </p>
		
		<p><label for="<?php echo $this->get_field_id('title_color'); ?>"><?php _e('Post Title Color (ex: #F7FB06) :'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('title_color'); ?>" name="<?php echo $this->get_field_name('title_color'); ?>" type="text" value="<?php echo $title_color; ?>" />
		</p>
        
       
        <p>
            <label for="<?php echo $this->get_field_id('show_type'); ?>"><?php _e('Show Post Type:');?></label> 
                <select class="widefat" id="<?php echo $this->get_field_id('show_type'); ?>" name="<?php echo $this->get_field_name('show_type'); ?>">
                <?php
                    global $wp_post_types;
                    foreach($wp_post_types as $k=>$sa) {
                        if($sa->exclude_from_search) continue;
                        echo '<option value="' . $k . '"' . selected($k,$show_type,true) . '>' . $sa->labels->name . '</option>';
                    }
                ?>
                </select>
        </p>
        
        <p><label for="<?php echo $this->get_field_id('categoryids'); ?>"><?php _e('Category IDs (Only show posts from this category id OR blank to show from all category):'); ?></label>
        <input class="widefat" id="<?php echo $this->get_field_id('categoryids'); ?>" name="<?php echo $this->get_field_name('categoryids'); ?>" type="text" value="<?php echo $categoryids; ?>" />
		</p>
        
        <p><label for="<?php echo $this->get_field_id('postids'); ?>"><?php _e('Post IDs (Only show given post ids OR blank to show all posts):'); ?><strong>If you mentioned category ids then post ids must from given categories.</strong></label>
        <input class="widefat" id="<?php echo $this->get_field_id('postids'); ?>" name="<?php echo $this->get_field_name('postids'); ?>" type="text" value="<?php echo $postids; ?>" />
		</p>
		
        </td>     
 </tr>
</table>
		<?php
	}	
}
// register RecentPostsSlider widget
add_action( 'widgets_init', create_function( '', 'return register_widget("Recent_Posts_Thumbnail_Slider_Widget");' ) );

register_uninstall_hook(__FILE__, 'rpsw_delete_plugin_options');

// delete options table entries ONLY when plugin deactivated AND deleted
function rpsw_delete_plugin_options() {
    delete_option('widget_recent-posts-thumbnail-slider');
}
?>