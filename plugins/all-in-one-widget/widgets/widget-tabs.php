<?php
/**
 * WP Tab Widget Class
 */
if ( !defined('ABSPATH')) exit;

class Themeidol_Tab_widget extends WP_Widget {
		function __construct() {
	        
			// ajax functions
			add_action('wp_ajax_wpt_widget_content', array(&$this, 'ajax_wpt_widget_content'));
			add_action('wp_ajax_nopriv_wpt_widget_content', array(&$this, 'ajax_wpt_widget_content'));
	        
	        // css
	        add_action('wp_enqueue_scripts', array(&$this, 'wpt_register_scripts'));
	        add_action('admin_enqueue_scripts', array(&$this, 'wpt_admin_scripts'));

			$widget_ops = array('classname' => 'widget_wpt', 'description' => __('Display popular posts, recent posts, comments, and tags in tabbed format.', 'themeidol-all-widget'));
			$control_ops = array('width' => 300, 'height' => 350);
			parent::__construct('themeidoltb_widget', __('Themeidol-WP Tab Widget', 'themeidol-all-widget'), $widget_ops, $control_ops);
	    	// Refreshing the widget's cached output with each new post
	    	add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
	    	add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
	    	add_action( 'delete_attachment', array( $this, 'flush_group_cache' ) );
	    	add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );   
	    }	
	    

	    function wpt_admin_scripts($hook) {
	        if ($hook != 'widgets.php')
	            return;
	        wp_register_script('themeidol_wpt_widget_admin', THEMEIDOL_WIDGET_JS_URL.'tab-admin.js', array('jquery'));  
	        wp_enqueue_script('themeidol_wpt_widget_admin');
	    }
	    function wpt_register_scripts() { 
			// JS    
			wp_register_script('themeidol_wpt_widget', THEMEIDOL_WIDGET_JS_URL.'tab-widget.js', array('jquery'));     
			wp_localize_script( 'themeidol_wpt_widget', 'wpt',         
				array( 'ajax_url' => admin_url( 'admin-ajax.php' )) 
			);        
			// CSS  
			wp_register_style('themeidol_wpt_widget-style', THEMEIDOL_WIDGET_CSS_URL.'tabs-style.css');   
			
	    }  

	    public function flush_widget_cache() {
    		wp_cache_delete( 'themeidol-tabs', 'widget' );
  		}	
		function form( $instance ) {
			$instance = wp_parse_args( (array) $instance, array( 
				'tabs' => array('recent' => 1, 'popular' => 1, 'comments' => 0, 'tags' => 0), 
				'tab_order' => array('popular' => 1, 'recent' => 2, 'comments' => 3, 'tags' => 4), 
				'allow_pagination' => 1, 
				'post_num' => '5', 
				'comment_num' => '5', 
				'show_thumb' => 1, 
				'thumb_size' => 'small', 
				'show_date' => 1, 
				'show_excerpt' => 0, 
				'excerpt_length' => apply_filters( 'wpt_excerpt_length_default', '15' ), 
				'show_comment_num' => 0, 
				'show_avatar' => 1, 
				'title_length' => apply_filters( 'wpt_title_length_default', '15' ) ,
				'popularity'=>'visited',
				'show_love' => 0, 
			) );
			
			extract($instance);

			?>
	        <div class="wpt_options_form">
	        
	        <h4><?php _e('Tabs Order & Select Tabs', 'themeidol-all-widget'); ?></h4>
	        
			<div class="wpt_select_tabs">
				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px" for="<?php echo $this->get_field_id("tabs"); ?>_popular">
					<input id="<?php echo $this->get_field_id('tab_order'); ?>_popular" name="<?php echo $this->get_field_name('tab_order'); ?>[popular]" type="number" min="1" step="1" value="<?php echo $tab_order['popular']; ?>" style="width: 48px;" />
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_popular" name="<?php echo $this->get_field_name("tabs"); ?>[popular]" value="1" <?php if (isset($tabs['popular'])) { checked( 1, $tabs['popular'], true ); } ?> />
					<?php _e( 'Popular', 'themeidol-all-widget'); ?>
				</label>
				<label class="alignleft" style="display: block; width: 50%; margin-bottom: 5px;" for="<?php echo $this->get_field_id("tabs"); ?>_recent">
					<input id="<?php echo $this->get_field_id('tab_order'); ?>_recent" name="<?php echo $this->get_field_name('tab_order'); ?>[recent]" type="number" min="1" step="1" value="<?php echo $tab_order['recent']; ?>" style="width: 48px;" />
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_recent" name="<?php echo $this->get_field_name("tabs"); ?>[recent]" value="1" <?php if (isset($tabs['recent'])) { checked( 1, $tabs['recent'], true ); } ?> />		
					<?php _e( 'Recent', 'themeidol-all-widget'); ?>
				</label>
				<label class="alignleft" style="display: block; width: 50%;" for="<?php echo $this->get_field_id("tabs"); ?>_comments">
					<input id="<?php echo $this->get_field_id('tab_order'); ?>_comments" name="<?php echo $this->get_field_name('tab_order'); ?>[comments]" type="number" min="1" step="1" value="<?php echo $tab_order['comments']; ?>" style="width: 48px;" />
					<input type="checkbox" class="checkbox wpt_enable_comments" id="<?php echo $this->get_field_id("tabs"); ?>_comments" name="<?php echo $this->get_field_name("tabs"); ?>[comments]" value="1" <?php if (isset($tabs['comments'])) { checked( 1, $tabs['comments'], true ); } ?> />
					<?php _e( 'Comments', 'themeidol-all-widget'); ?>
				</label>
				<label class="alignleft" style="display: block; width: 50%;" for="<?php echo $this->get_field_id("tabs"); ?>_tags">
					<input id="<?php echo $this->get_field_id('tab_order'); ?>_tags" name="<?php echo $this->get_field_name('tab_order'); ?>[tags]" type="number" min="1" step="1" value="<?php echo $tab_order['tags']; ?>" style="width: 48px;" />
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("tabs"); ?>_tags" name="<?php echo $this->get_field_name("tabs"); ?>[tags]" value="1" <?php if (isset($tabs['tags'])) { checked( 1, $tabs['tags'], true ); } ?> />
					<?php _e( 'Tags', 'themeidol-all-widget'); ?>
				</label>
			</div>
	        <div class="clear"></div>

	        
	        <h4 class="wpt_advanced_options_header"><?php _e('Advanced Options', 'themeidol-all-widget'); ?></h4>
	        
	        <div class="wpt_advanced_options">
	        <p>
				<label for="<?php echo $this->get_field_id("allow_pagination"); ?>">				
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("allow_pagination"); ?>" name="<?php echo $this->get_field_name("allow_pagination"); ?>" value="1" <?php if (isset($allow_pagination)) { checked( 1, $allow_pagination, true ); } ?> />
					<?php _e( 'Allow pagination', 'themeidol-all-widget'); ?>
				</label>
			</p>
			
			<div class="wpt_post_options">

	        <p>
				<label for="<?php echo $this->get_field_id('post_num'); ?>"><?php _e('Number of posts to show:', 'themeidol-all-widget'); ?>
					<br />
					<input id="<?php echo $this->get_field_id('post_num'); ?>" name="<?php echo $this->get_field_name('post_num'); ?>" type="number" min="1" step="1" value="<?php echo $post_num; ?>" />
				</label>
			</p>

			<p>
				<label for="<?php echo $this->get_field_id('title_length'); ?>"><?php _e('Title length (words):', 'themeidol-all-widget'); ?>
					<br />
					<input id="<?php echo $this->get_field_id('title_length'); ?>" name="<?php echo $this->get_field_name('title_length'); ?>" type="number" min="1" step="1" value="<?php echo $title_length; ?>" />
				</label>
			</p>
			<p class="wpt_popularity">
				<label for="<?php echo $this->get_field_id('popularity'); ?>"><?php _e('Popularity By:', 'themeidol-all-widget'); ?></label> 
				<select id="<?php echo $this->get_field_id('popularity'); ?>" name="<?php echo $this->get_field_name('popularity'); ?>" style="margin-left: 12px;">
					<option value="visited" <?php selected($popularity, 'visited', true); ?>><?php _e('Most Visited', 'themeidol-all-widget'); ?></option>  
					<option value="comment" <?php selected($popularity, 'comment', true); ?>><?php _e('Most Commented', 'themeidol-all-widget'); ?></option>
				</select>       
			</p>
			
			<p>
				<label for="<?php echo $this->get_field_id("show_thumb"); ?>">
					<input type="checkbox" class="checkbox wpt_show_thumbnails" id="<?php echo $this->get_field_id("show_thumb"); ?>" name="<?php echo $this->get_field_name("show_thumb"); ?>" value="1" <?php if (isset($show_thumb)) { checked( 1, $show_thumb, true ); } ?> />
					<?php _e( 'Show post thumbnails', 'themeidol-all-widget'); ?>
				</label>
			</p>   
			
			<p class="wpt_thumbnail_size"<?php echo (empty($show_thumb) ? ' style="display: none;"' : ''); ?>>
				<label for="<?php echo $this->get_field_id('thumb_size'); ?>"><?php _e('Thumbnail size:', 'themeidol-all-widget'); ?></label> 
				<select id="<?php echo $this->get_field_id('thumb_size'); ?>" name="<?php echo $this->get_field_name('thumb_size'); ?>" style="margin-left: 12px;">
					<option value="small" <?php selected($thumb_size, 'small', true); ?>><?php _e('Small', 'themeidol-all-widget'); ?></option>
					<option value="large" <?php selected($thumb_size, 'large', true); ?>><?php _e('Large', 'themeidol-all-widget'); ?></option>    
				</select>       
			</p>	
			
			<p>			
				<label for="<?php echo $this->get_field_id("show_date"); ?>">	
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_date"); ?>" name="<?php echo $this->get_field_name("show_date"); ?>" value="1" <?php if (isset($show_date)) { checked( 1, $show_date, true ); } ?> />	
					<?php _e( 'Show post date', 'themeidol-all-widget'); ?>	
				</label>	
			</p>
	        
			<p>		
				<label for="<?php echo $this->get_field_id("show_comment_num"); ?>">		
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_comment_num"); ?>" name="<?php echo $this->get_field_name("show_comment_num"); ?>" value="1" <?php if (isset($show_comment_num)) { checked( 1, $show_comment_num, true ); } ?> />	
					<?php _e( 'Show number of comments', 'themeidol-all-widget'); ?>		
				</label>	
			</p>    
			
			<p>			
				<label for="<?php echo $this->get_field_id("show_excerpt"); ?>">	
					<input type="checkbox" class="checkbox wpt_show_excerpt" id="<?php echo $this->get_field_id("show_excerpt"); ?>" name="<?php echo $this->get_field_name("show_excerpt"); ?>" value="1" <?php if (isset($show_excerpt)) { checked( 1, $show_excerpt, true ); } ?> />
					<?php _e( 'Show post excerpt', 'themeidol-all-widget'); ?>
				</label>		
			</p>
			
			<p class="wpt_excerpt_length"<?php echo (empty($show_excerpt) ? ' style="display: none;"' : ''); ?>>
				<label for="<?php echo $this->get_field_id('excerpt_length'); ?>">
					<?php _e('Excerpt length (words):', 'themeidol-all-widget'); ?>   
					<br />
					<input type="number" min="1" step="1" id="<?php echo $this->get_field_id('excerpt_length'); ?>" name="<?php echo $this->get_field_name('excerpt_length'); ?>" value="<?php echo $excerpt_length; ?>" />
				</label>
			</p>	
			  
			</div>
	        <div class="clear"></div>
	        
	        <div class="wpt_comment_options"<?php echo (empty($tabs['comments']) ? ' style="display: none;"' : ''); ?>>
			
	        <p>
				<label for="<?php echo $this->get_field_id('comment_num'); ?>">
					<?php _e('Number of comments on Comments Tab:', 'themeidol-all-widget'); ?>
					<br />
					<input type="number" min="1" step="1" id="<?php echo $this->get_field_id('comment_num'); ?>" name="<?php echo $this->get_field_name('comment_num'); ?>" value="<?php echo $comment_num; ?>" />
				</label>			
			</p>      
			
			<p>			
				<label for="<?php echo $this->get_field_id("show_avatar"); ?>">			
					<input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id("show_avatar"); ?>" name="<?php echo $this->get_field_name("show_avatar"); ?>" value="1" <?php if (isset($show_avatar)) { checked( 1, $show_avatar, true ); } ?> />
					<?php _e( 'Show avatars on Comments Tab', 'themeidol-all-widget'); ?>	
				</label>	
			</p>
			</div><!-- .wpt_comment_options -->
			</div><!-- .wpt_advanced_options -->
			
			</div><!-- .wpt_options_form -->
			<?php 
		}	
		
		function update( $new_instance, $old_instance ) {	
			$instance = $old_instance;    
			$instance['tabs'] =isset( $new_instance['tabs']) ?wp_unslash( $new_instance['tabs'] ) :array(); 
	        $instance['tab_order'] = isset( $new_instance['tab_order']) ?wp_unslash( $new_instance['tab_order'] ) :array(); 
			$instance['allow_pagination'] = esc_attr($new_instance['allow_pagination']);	
			$instance['post_num'] = esc_attr($new_instance['post_num']);	
			$instance['title_length'] = esc_attr($new_instance['title_length']);
			$instance['popularity']=esc_attr($new_instance['popularity']);	
			$instance['comment_num'] =  esc_attr($new_instance['comment_num']);		
			$instance['show_thumb'] = esc_attr($new_instance['show_thumb']);     
			$instance['thumb_size'] = esc_attr($new_instance['thumb_size']);		
			$instance['show_date'] = esc_attr($new_instance['show_date']);    
			$instance['show_excerpt'] = esc_attr($new_instance['show_excerpt']);  
			$instance['excerpt_length'] = esc_attr($new_instance['excerpt_length']);	
			$instance['show_comment_num'] = esc_attr($new_instance['show_comment_num']);  
			$instance['show_avatar'] = esc_attr($new_instance['show_avatar']);	
			return $instance;	
		}	
		function widget( $args, $instance ) {
			$cache    = (array) wp_cache_get( 'themeidol-tabs', 'widget' );

         	if(!is_array($cache)) $cache = array();
      
         	if(isset($cache[$args['widget_id']])){
	            echo $cache[$args['widget_id']];
	            return;
         	}
      		ob_start();	
			extract($args);     
			extract($instance);    
			wp_enqueue_style('themeidol_wpt_widget-style');
			wp_enqueue_script('themeidol_wpt_widget');
			if (empty($tabs)) $tabs = array('recent' => 1, 'popular' => 1);    
			$tabs_count = count($tabs);     
			if ($tabs_count <= 1) {       
				$tabs_count = 1;       
			} elseif($tabs_count > 3) {   
				$tabs_count = 4;      
			}
	        
	        $available_tabs = array('popular' => __('Popular', 'themeidol-all-widget'), 
	            'recent' => __('Recent', 'themeidol-all-widget'), 
	            'comments' => __('Comments', 'themeidol-all-widget'), 
	            'tags' => __('Tags', 'themeidol-all-widget'));
	            
	        array_multisort($tab_order, $available_tabs);
	        
	        $show_love = !empty($instance['show_love']);
			?>	
			<?php
			if (strpos($before_widget, 'widget ') !== false) {
            	$before_widget = preg_replace('/widget /', "idol-widget ", $before_widget, 1);
        	}	
        	echo $before_widget; ?>	
			<div class="wpt_widget_content" id="<?php echo $widget_id; ?>_content" data-widget-number="<?php echo esc_attr( $this->number ); ?>">	
				<ul class="wpt-tabs <?php echo "has-$tabs_count-"; ?>tabs">
	                <?php foreach ($available_tabs as $tab => $label) { ?>
	                    <?php if (!empty($tabs[$tab])): ?>
	                        <li class="tab_title"><a href="#" id="<?php echo $tab; ?>-tab"><?php echo $label; ?></a></li>	
	                    <?php endif; ?>
	                <?php } ?> 
				</ul> <!--end .tabs-->	
				<div class="clear"></div>  
				<div class="inside">        
					<?php if (!empty($tabs['popular'])): ?>	
						<div id="popular-tab-content" class="tab-content">				
						</div> <!--end #popular-tab-content-->       
					<?php endif; ?>       
					<?php if (!empty($tabs['recent'])): ?>	
						<div id="recent-tab-content" class="tab-content"> 		 
						</div> <!--end #recent-tab-content-->		
					<?php endif; ?>                     
					<?php if (!empty($tabs['comments'])): ?>      
						<div id="comments-tab-content" class="tab-content"> 	
							<ul>                    		
							</ul>		
						</div> <!--end #comments-tab-content-->     
					<?php endif; ?>            
					<?php if (!empty($tabs['tags'])): ?>       
						<div id="tags-tab-content" class="tab-content"> 	
							<ul>                    	
							</ul>			 
						</div> <!--end #tags-tab-content-->  
					<?php endif; ?>
					<div class="clear"></div>
				</div> <!--end .inside -->
				
				<div class="clear"></div>
			</div><!--end #tabber -->
			<?php    
			// inline script 
			// to support multiple instances per page with different settings   
			
			unset($instance['tabs'], $instance['tab_order']); // unset unneeded  
			?>  
			<script type="text/javascript">  
				jQuery(function($) {    
					$('#<?php echo $widget_id; ?>_content').data('args', <?php echo json_encode($instance); ?>);  
				});  
			</script>  
			<?php echo $after_widget; ?>
			<?php 
			$widget_string = ob_get_flush();
			$cache[$args['widget_id']] = $widget_string;
			wp_cache_add('themeidol-tabs', $cache, 'widget');

		}  
		
		 
		function ajax_wpt_widget_content() {     
			$tab = isset( $_POST['tab']) ?wp_unslash( $_POST['tab'] ) :array(); 
			$args = isset( $_POST['args']) ?wp_unslash( $_POST['args'] ) :array();
	    	$number = intval( $_POST['widget_number']);
			$page = intval($_POST['page']);    
			if ($page < 1)        
				$page = 1;

			if ( !is_array( $args ) || empty( $args ) ) { // json_encode() failed
				$wpt_widgets = new Themeidol_Tab_widget();
				$settings = $wpt_widgets->get_settings();

				if ( isset( $settings[ $number ] ) ) {
					$args = $settings[ $number ];
				} else {
					die( __('Unable to load tab content', 'themeidol-all-widget') );
				}
			}
	    	
	        
			// sanitize args		
			$post_num = (empty($args['post_num']) ? 5 : intval($args['post_num']));    
			if ($post_num > 20 || $post_num < 1) { // max 20 posts
				$post_num = 5;   
			}      
			$comment_num = (empty($args['comment_num']) ? 5 : intval($args['comment_num']));   
			if ($comment_num > 20 || $comment_num < 1) {  
				$comment_num = 5;    
			}       
			$show_thumb = !empty($args['show_thumb']);
			$thumb_size = $args['thumb_size'];
			$popularity=$args['popularity'];
	        if ($thumb_size != 'small' && $thumb_size != 'large') {
	            $thumb_size = 'small'; // default
	        }
			$show_date = !empty($args['show_date']);     
			$show_excerpt = !empty($args['show_excerpt']);  
			$excerpt_length = intval($args['excerpt_length']);
	        if ($excerpt_length > 50 || $excerpt_length < 1) {  
				$excerpt_length = 10;   
			}   
			$show_comment_num = !empty($args['show_comment_num']);  
			$show_avatar = !empty($args['show_avatar']);   
			$allow_pagination = !empty($args['allow_pagination']);

			$title_length = ! empty($args['title_length']) ? $args['title_length'] : apply_filters( 'wpt_title_length_default', '15' );
	        
			/* ---------- Tab Contents ---------- */    
			switch ($tab) {        
			  
				/* ---------- Popular Posts ---------- */   
				case "popular":      
					?>       
					<ul>				
						<?php 
						if($popularity=='visited'):
							//echo "visited";
						$popular = new WP_Query( array('ignore_sticky_posts' => 1, 'posts_per_page' => $post_num, 'post_status' => 'publish', 'orderby' => 'meta_value_num', 'meta_key' => '_themeidol_view_count', 'order' => 'desc', 'paged' => $page));
						else:
						$popular = new WP_Query( array('ignore_sticky_posts' => 1, 'posts_per_page' => $post_num, 'post_status' => 'publish', 'orderby' => 'comment_count', 'order' => 'desc', 'paged' => $page));
						endif;         
						$last_page = $popular->max_num_pages;      
						while ($popular->have_posts()) : $popular->the_post(); ?>	
							<li>
								<?php if ( $show_thumb == 1 ) : ?>			
									<div class="wpt_thumbnail wpt_thumb_<?php echo $thumb_size; ?>">	
	                                    <a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">		
	    									<?php if(has_post_thumbnail()): ?>	
	    										<?php the_post_thumbnail('wp_review_'.$thumb_size, array('title' => '')); ?>		
	    									<?php else: ?>							
	    										<img src="<?php echo THEMEIDOL_WIDGET_IMAGES_URL.$thumb_size.'thumb.png'; ?>" alt="<?php the_title(); ?>"  class="wp-post-image" />					
	    									<?php endif; ?>
	                                    </a>
									</div>				
								<?php endif; ?>					
								<div class="entry-title"><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php echo $this->post_title( $title_length ); ?></a></div>		
								<?php if ( $show_date == 1 || $show_comment_num == 1) : ?>	
									<div class="wpt-postmeta">						
										<?php if ( $show_date == 1 ) : ?>			
											<?php the_time('F j, Y'); ?>		
										<?php endif; ?>						
										<?php if ( $show_date == 1 && $show_comment_num == 1) : ?>		
											&bull; 						
										<?php endif; ?>					
										<?php if ( $show_comment_num == 1 ) : ?>			
											<?php echo comments_number(__('No Comment','themeidol-all-widget'), __('One Comment','themeidol-all-widget'), '<span class="comments-number">%</span> '.__('Comments','themeidol-all-widget'));?>				
										<?php endif; ?>						
									</div> <!--end .entry-meta--> 				
								<?php endif; ?>
	                            
	                            <?php if ( $show_excerpt == 1 ) : ?>	
	                                <div class="wpt_excerpt">
	                                    <p><?php echo $this->excerpt($excerpt_length); ?></p>
	                                </div>
	                            <?php endif; ?>	
	                            						
								<div class="clear"></div>			
							</li>				
						<?php $post_num++; endwhile; wp_reset_query(); ?>		
					</ul>
	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>         
						<?php $this->tab_pagination($page, $last_page); ?>      
					<?php endif; ?>                      
					<?php           
				break;              
	            
				/* ---------- Recent Posts ---------- */      
				case "recent":           
					?>         
					<ul>			
						<?php              
						$recent = new WP_Query('posts_per_page='. $post_num .'&orderby=post_date&order=desc&post_status=publish&paged='. $page);       
						$last_page = $recent->max_num_pages;      
						while ($recent->have_posts()) : $recent->the_post();    
							?>						         
							<li>
								<?php if ( $show_thumb == 1 ) : ?>					
									<div class="wpt_thumbnail wpt_thumb_<?php echo $thumb_size; ?>">	
	                                    <a title="<?php the_title(); ?>" href="<?php the_permalink() ?>">		
	    									<?php if(has_post_thumbnail()): ?>	
	    										<?php the_post_thumbnail('wp_review_'.$thumb_size, array('title' => '')); ?>		
	    									<?php else: ?>							
	    										<img src="<?php echo THEMEIDOL_WIDGET_IMAGES_URL.$thumb_size.'thumb.png'; ?>" alt="<?php the_title(); ?>"  class="wp-post-image" />					
	    									<?php endif; ?>
	                                    </a>
									</div>				
								<?php endif; ?>					
								<div class="entry-title"><a title="<?php the_title(); ?>" href="<?php the_permalink() ?>"><?php echo $this->post_title( $title_length ); ?></a></div>		
								<?php if ( $show_date == 1 || $show_comment_num == 1) : ?>			
									<div class="wpt-postmeta">										
										<?php if ( $show_date == 1 ) : ?>						
											<?php the_time('F j, Y'); ?>						
										<?php endif; ?>								
										<?php if ( $show_date == 1 && $show_comment_num == 1) : ?>		
											&bull; 										
										<?php endif; ?>								
										<?php if ( $show_comment_num == 1 ) : ?>	
											<?php echo comments_number(__('No Comment','themeidol-all-widget'), __('One Comment','themeidol-all-widget'), '<span class="comments-number">%</span> '.__('Comments','themeidol-all-widget'));?>									
										<?php endif; ?>		
									</div> <!--end .entry-meta--> 		
								<?php endif; ?>
	                            
	                            <?php if ( $show_excerpt == 1 ) : ?>	
	                                <div class="wpt_excerpt">
	                                    <p><?php echo $this->excerpt($excerpt_length); ?></p>
	                                </div>
	                            <?php endif; ?>	
	                            	
								<div class="clear"></div>		
							</li>				
						<?php endwhile; wp_reset_query(); ?>		
					</ul>
	                <div class="clear"></div>
					<?php if ($allow_pagination) : ?>       
						<?php $this->tab_pagination($page, $last_page); ?>    
					<?php endif; ?>                 
					<?php       
				break;     
	            
				/* ---------- Latest Comments ---------- */        
				case "comments":         
					?>          
					<ul>            
						<?php              
						$no_comments = false;         
						$avatar_size = 65;            
						$comment_length = 90; // max length for comments   
						$comment_args = apply_filters(
							'wpt_comments_tab_args',
							array(
								'type' => 'comments',
								'status' => 'approve'
							)
						);     
						$comments_total = new WP_Comment_Query();
						$comments_total_number = $comments_total->query( array_merge( array('count' => 1 ), $comment_args ) );
						$last_page = (int) ceil($comments_total_number / $comment_num);
						$comments_query = new WP_Comment_Query();
						$offset = ($page-1) * $comment_num;
						$comments = $comments_query->query( array_merge( array( 'number' => $comment_num, 'offset' => $offset ), $comment_args ) );
						if ( $comments ) : foreach ( $comments as $comment ) : ?>       
							<li>          
								<?php if ($show_avatar) : ?>                       
									<div class="wpt_avatar">
	                                    <a href="<?php echo get_comment_link($comment->comment_ID); ?>">
											<?php echo get_avatar( $comment->comment_author_email, $avatar_size ); ?>     
	                                    </a>                               
									</div>                   
								<?php endif; ?>              
								<div class="wpt_comment_meta">
	                                <a href="<?php echo get_comment_link($comment->comment_ID); ?>">   
										<span class="wpt_comment_author"><?php echo get_comment_author( $comment->comment_ID ); ?> </span> - <span class="wpt_comment_post"><?php echo get_the_title($comment->comment_post_ID); ?></span>                   
								    </a>
	                            </div>                   
								<div class="wpt_comment_content">          
									<p><?php echo $this->truncate(strip_tags(apply_filters( 'get_comment_text', $comment->comment_content )), $comment_length);?></p>
								</div>                                   
								<div class="clear"></div>      
							</li>           
						<?php endforeach; else : ?>           
							<li>                   
								<div class="no-comments"><?php _e('No comments yet.', 'themeidol-all-widget'); ?></div>        
							</li>                             
							<?php $no_comments = true; 
						endif; ?>       
					</ul>       
					<?php if ($allow_pagination && !$no_comments) : ?>           
						<?php $this->tab_pagination($page, $last_page); ?>      
					<?php endif; ?>                     
					<?php           
				break;             
	            
				/* ---------- Tags ---------- */   
				case "tags":        
					?>           
					<ul>         
						<?php        
						$tags = get_tags(array('get'=>'all'));             
						if($tags) {               
							foreach ($tags as $tag): ?>    
								<li><a href="<?php echo get_term_link($tag); ?>"><?php echo $tag->name; ?></a></li>           
								<?php            
							endforeach;       
						} else {          
							_e('No tags created.', 'themeidol-all-widget');           
						}            
						?>           
					</ul>            
					<?php            
				break;            
			}              
			die(); // required to return a proper result  
		}    
	    function tab_pagination($page, $last_page) {  
			?>   
			<div class="wpt-pagination">     
				<?php if ($page > 1) : ?>               
					<a href="#" class="previous"><span><?php _e('&laquo; Previous', 'themeidol-all-widget'); ?></span></a>      
				<?php endif; ?>        
				<?php if ($page != $last_page) : ?>     
					<a href="#" class="next"><span><?php _e('Next &raquo;', 'themeidol-all-widget'); ?></span></a>      
				<?php endif; ?>          
			</div>                   
			<div class="clear"></div>
			<input type="hidden" class="page_num" name="page_num" value="<?php echo $page; ?>" />    
			<?php   
		}
	    
	    function excerpt($limit = 10) {
	    	  $limit++;
	          $excerpt = explode(' ', get_the_excerpt(), $limit);
	          if (count($excerpt)>=$limit) {
	            array_pop($excerpt);
	            $excerpt = implode(" ",$excerpt).'...';
	          } else {
	            $excerpt = implode(" ",$excerpt);
	          }
	          $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	          return $excerpt;
	    }
	    function post_title($limit = 10) {
	    	  $limit++;
	          $title = explode(' ', get_the_title(), $limit);
	          if (count($title)>=$limit) {
	            array_pop($title);
	            $title = implode(" ",$title).'...';
	          } else {
	            $title = implode(" ",$title);
	          }
	          return $title;
	    }
	    function truncate($str, $length = 24) {
	        if (mb_strlen($str) > $length) {
	            return mb_substr($str, 0, $length).'...';
	        } else {
	            return $str;
	        }
	    }
}
add_action( 'widgets_init', create_function( '', 'return register_widget("Themeidol_Tab_widget");' ) );