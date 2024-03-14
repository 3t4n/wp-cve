<?php
/*
Plugin Name: WP Posts Widget
Plugin URI: http://www.wp-experts.in/
Description: It's a very simple plugin to display recent posts on your website sidebar widget and also there are an option to choose custom post type for display thier posts on the website sidebar.
Author: WP Experts Team
Author URI: http://www.wp-experts.in
Version: 1.8

Copyright 2018-23  wp-posts-widget  (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**************************************************************
                START CLASSS Wp_Posts_Widget 
**************************************************************/
if(!class_exists('WpPostsWidget'))
{
 /**
  * Adds Wp_Posts_Widget widget.
  */
 class WpPostsWidget extends WP_Widget {
	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'wp_posts_widget', // Base ID
			__( 'WP Post Widget', 'wp-experts' ), // Name
			array( 'description' => __( 'Display custom post type recent posts', 'wp-experts' ), ) // Args
		);
		add_action( 'widgets_init', array(&$this, 'register_wpw_post_widget' ) );
		add_action( 'wpw_style', array(&$this, 'wpw_style_func' ) );
		add_filter( "plugin_action_links_".plugin_basename( __FILE__ ), array(&$this,'wpw_add_settings_link') );
		
	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		$va_post_HTML ='<div class="wp-post-widget-div">';
		if ( ! empty( $instance['wpw_title'] ) && !$instance['wpw_hide_title']) {
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['wpw_title'] ) . $args['after_title'];
		}
		$numberofpost  		= ! empty( $instance['wpw_number_of_posts']) ? $instance['wpw_number_of_posts'] : 5;
		$wpw_content_limit  = ! empty( $instance['wpw_content_limit']) ? $instance['wpw_content_limit'] : 150;
		$postimage     		= ! empty( $instance['wpw_show_featured_image']) ? $instance['wpw_show_featured_image'] : '';
		$postorderby   		= ! empty( $instance['wpw_orderby_posts']) ? $instance['wpw_orderby_posts'] : 'ID';
		$postorder     		= ! empty( $instance['wpw_order_posts']) ? $instance['wpw_order_posts'] : 'DESC';
		$posttype      		= ! empty( $instance['wpw_post_type']) ? $instance['wpw_post_type'] : '';
		$wpw_show_hide_posts= ! empty($instance['wpw_show_hide_posts']) ? $instance['wpw_show_hide_posts'] : '';
		$wpw_action_on_posts = ! empty($instance['wpw_action_on_posts']) ? $instance['wpw_action_on_posts'] : '';
		$wpw_hide_date  	 = ! empty($instance['wpw_hide_date']) ? $instance['wpw_hide_date'] : '';
		$wpw_show_count  	 = ! empty($instance['wpw_show_count']) ? $instance['wpw_show_count'] : '';
		$wpw_category  	     = ! empty($instance['wpw_category']) ? $instance['wpw_category'] : '';
		$wpw_taxonomy  	     = ! empty($instance['wpw_taxonomy']) ? $instance['wpw_taxonomy'] : '';
		
		$wpw_hide_readmore   = ! empty($instance['wpw_hide_readmore']) ? $instance['wpw_hide_readmore'] : '';
		$wpw_hide_content  	 = ! empty($instance['wpw_hide_content']) ? $instance['wpw_hide_content'] : '';
		
		/** return category list */
		if($posttype){
			$va_post_HTML .='<div class="wp-post-widget-listing">';
				$args_val = array('post_type' => $posttype);
				/** define number or post by */		
				$args_val['posts_per_page'] = $numberofpost;
				$args_val['order'] = $postorder;
				/** define order by */
				if($postorderby!='')
				$args_val['orderby'] = $postorderby;
				/** define exclude posts */
				if($wpw_show_hide_posts && $wpw_action_on_posts!='')
				$args_val[$wpw_action_on_posts] = $wpw_show_hide_posts;
			
			   $is_selected = in_array('all', (array)$wpw_category);


			if( $wpw_taxonomy != '' && !$is_selected ) {
			$args_val['tax_query'] = array(
				array(
				  'taxonomy' => $wpw_taxonomy,
				  'field' => 'term_id', 
				  'terms' => $wpw_category, /// Where term_id of Term 1 is "1".
				  'include_children' => true
				)
			  );
			}
			//print_r( $args_val);
				
				$posts_data = get_posts($args_val );
				if ( $posts_data ) {	
			    $divid ='';
					foreach ( $posts_data as $post_data ) {
						$post_link = get_permalink($post_data->ID);
						if(isset($excludePosts) && $excludePosts!='' && in_array($post_data->ID,$excludePosts))
						{
							continue;
							}
						
						if ( is_wp_error( $post_link ) ) {
						continue;
						}
						
					$carrentActiveClass='';	
					global $post;
					if(is_singular()) {
					    $currentTermType = get_query_var( 'taxonomy' );
					    $postId= $post->ID;
						 if(is_singular($post->post_type) && $postId==$post_data->ID)
						  $carrentActiveClass='active-post';
					}
						$va_post_HTML .='<div class="post-li '.$carrentActiveClass.'">';
						if ( has_post_thumbnail( $post_data->ID ) && $postimage) {
							$va_post_HTML .='<span class="image"><a href="' . get_permalink( $post_data->ID ) . '" title="' . esc_attr( $post_data->post_title ) . '">';
							$va_post_HTML .=get_the_post_thumbnail( $post_data->ID, array('100','100'),array( 'class' => 'alignleft' ) );
							$va_post_HTML .='</a></span>';
						
						}else
						{
							
							$divid= 'id="no-image"';
						}
											
							$va_post_HTML .='<span class="title"><a class="post-title" href="' . esc_url( $post_link ) . '">'. $post_data->post_title. '</a></span>';
							
							if($wpw_hide_date !=1)
							$va_post_HTML .='<span class="post-date">'.get_the_date('',$post_data->ID).'</span>';
							
						if( $wpw_hide_content !=1 ){
							$va_post_HTML .= '<p>'.substr(strip_tags(strip_shortcodes($post_data->post_content)),0,$wpw_content_limit).'</p>';
}
						
						if( $wpw_hide_readmore != 1 ){
							$va_post_HTML .='<a class="readmore" href="' . esc_url( $post_link ) . '">Read more...</a>';
						}
							
					   if($wpw_show_count==1) {
							$va_post_HTML .='(<span><strong>'.$post_data->comment_count.'</strong> Comments)</span>';
							}
							
						$va_post_HTML .='</div>';
					}
				}
			$va_post_HTML .='</div>';
			
			}
		$va_post_HTML .='</div>';
		echo $va_post_HTML;
		echo $args['after_widget'];
		do_action('wpw_style','wpw_style_func'); // call style CSS
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$wpw_title 					= ! empty( $instance['wpw_title'] ) ? $instance['wpw_title'] : __( 'Recent posts', 'wp-experts' );
		$wpw_hide_title 			= ! empty( $instance['wpw_hide_title'] ) ? $instance['wpw_hide_title'] : __( '', 'wp-experts' );
		$wpw_number_of_posts 	= ! empty( $instance['wpw_number_of_posts'] ) ? $instance['wpw_number_of_posts'] : __( '', 'wp-experts' );
		$wpw_content_limit 	= ! empty( $instance['wpw_content_limit'] ) ? $instance['wpw_content_limit'] : __( 150, 'wp-experts' );
		$wpw_orderby_posts 		= ! empty( $instance['wpw_orderby_posts'] ) ? $instance['wpw_orderby_posts'] : __( '', 'wp-experts' );
		$wpw_order_posts 		= ! empty( $instance['wpw_order_posts'] ) ? $instance['wpw_order_posts'] : __( '', 'wp-experts' );
		$wpw_show_featured_image = ! empty( $instance['wpw_show_featured_image'] ) ? $instance['wpw_show_featured_image'] : __( '', 'wp-experts' );
		$wpw_post_type 			= ! empty( $instance['wpw_post_type'] ) ? $instance['wpw_post_type'] : __( 'post', 'wp-experts' );
		$wpw_show_hide_posts 		= (! empty( $instance['wpw_show_hide_posts'] ) && ! empty( $instance['wpw_action_on_posts'] )) ? $instance['wpw_show_hide_posts'] : __( '', 'wp-experts' );
		$wpw_action_on_posts 		= ! empty( $instance['wpw_action_on_posts'] ) ? $instance['wpw_action_on_posts'] : __( '', 'wp-experts' );
		$wpw_show_count 			= ! empty( $instance['wpw_show_count'] ) ? $instance['wpw_show_count'] : __( '', 'wp-experts' );
		$wpw_hide_date 			= ! empty( $instance['wpw_hide_date'] ) ? $instance['wpw_hide_date'] : __( '', 'wp-experts' );
		$wpw_category        = ! empty( $instance['wpw_category'] ) ? $instance['wpw_category'] : array('all');
		
		$wpw_taxonomy      = ! empty( $instance['wpw_taxonomy'] ) ? $instance['wpw_taxonomy'] : esc_html__( '', 'wp-experts' );
		
		$wpw_hide_content      = ! empty( $instance['wpw_hide_content'] ) ? $instance['wpw_hide_content'] : esc_html__( '', 'wp-experts' );
		$wpw_hide_readmore      = ! empty( $instance['wpw_hide_readmore'] ) ? $instance['wpw_hide_readmore'] : esc_html__( '', 'wp-experts' );
		
			
			
		?>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_title' ) ); ?>"><?php _e( esc_attr( 'Title:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_title' ) ); ?>" type="text" value="<?php echo esc_attr( $wpw_title ); ?>">
		</p>
		<p>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_hide_title' ) ); ?>" type="checkbox" value="1" <?php checked( $wpw_hide_title, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_title' ) ); ?>"><?php _e( esc_attr( 'Hide Title' ) ); ?> </label> 
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_post_type' ) ); ?>"><?php _e( esc_attr( 'Post Type:' ) ); ?></label> 
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_post_type' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_post_type' ) ); ?>">
					<?php 
					
					$args = array(
					   'public'   => true,
					   '_builtin' => false
					);

					$output = 'names'; // names or objects, note names is the default
					$operator = 'and'; // 'and' or 'or'

					$post_types = get_post_types( $args, $output, $operator ); 
					array_push($post_types,'post');array_push($post_types,'page');
					if ( $post_types ) {
					foreach ( $post_types  as $post_type ) {

						echo '<option value="'.$post_type.'" '.($wpw_post_type!='' ? selected($wpw_post_type,$post_type) : selected('post',$post_type)).'>'.$post_type.'</option>';
					}
					}

				?>    
		</select>
		</p>
<p>
	<?php 
			$taxonomy_names = get_object_taxonomies( $wpw_post_type );
		
		 if( is_array( $taxonomy_names ) ) {
		 $queryargs = array(
                                  'echo' => false,
                                  'taxonomy'     => $taxonomy_names[0],
                                  'hide_empty'   => true,
                                  'orderby'      => 'name',
                                  'order'        => 'ASC',
                                  'depth' => 0,
                                  'hide_title_if_empty' => true,
                                );
			 
			 $terms = get_terms( $queryargs );
			 
			 
			 if ( $terms ) { 
			$wpw_taxonomy  = !empty( $wpw_taxonomy ) ? $wpw_taxonomy : $taxonomy_names[0];
			?>
			  
					<input id="<?php echo esc_attr( $this->get_field_id( 'wpw_taxonomy' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_taxonomy' ) ); ?>" type="hidden" value="<?php echo esc_attr( $wpw_taxonomy ); ?>">

			<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_category' ) ); ?>"><?php _e( esc_attr( 'Category :' ) ); ?></label>
                <select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_category' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_category' ) ); ?>[]" multiple>
                                       <?php
				 
						foreach ( $terms as $term ) {
							
           $is_selected = ( in_array( 'all', (array)$wpw_category ) ? false : in_array( $term->term_id, (array)$wpw_category ) );

    echo '<option value="' . $term->term_id . '" ' . selected($is_selected, true, false) . '>' . $term->name . '</option>';
}
				 $is_selected = ( in_array( 'all', (array)$wpw_category ) );
			?>
					<option value="all" <?php selected( $is_selected, true, true);?> >All</option>
			</select>
			<?php 
                                        }
			 
			
		 }
 			?>
</p>
		<p>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_action_on_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_action_on_posts' ) ); ?>">
           <option value="" <?php selected($wpw_action_on_posts,'' )?> >Show All posts:</option>       
           <option value="include" <?php selected($wpw_action_on_posts,'include' )?> >Include Selected Posts:</option>       
           <option value="exclude" <?php selected($wpw_action_on_posts,'exclude' )?> >Exclude Selected Posts:</option>
		</select>
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_show_hide_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_show_hide_posts' ) ); ?>[]" multiple>
					<?php 			
					if($wpw_post_type){
						$args = array(
						'posts_per_page'   => -1,
						'post_type'        => $wpw_post_type
						);
						
				     if( $wpw_taxonomy != '' &&  !in_array( 'all' , $wpw_category ) ) {
						$args['tax_query'] = array(
							array(
							  'taxonomy' => $wpw_taxonomy,
							  'field' => 'term_id', 
							  'terms' => $wpw_category, /// Where term_id of Term 1 is "1".
							  'include_children' => true
							)
						  );
					}						
					$posts = get_posts($args);
					$i=1;
					
					if ( $posts ) {
					foreach ( $posts as $post ) {

						echo '<option value="'.$post->ID.'" '.selected(true, ($wpw_show_hide_posts!='' ? in_array($post->ID,$wpw_show_hide_posts) : ''), false).'>#'.$i.' - '.$post->post_title.'</option>';
					$i++;
					}
				    	echo '<option value="" '.selected(true, ($wpw_show_hide_posts!='' ? in_array('',$wpw_show_hide_posts) : ''), false).' >None</option>';
					}
				}

				?>    
		</select>
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_orderby_posts' ) ); ?>"><?php _e( esc_attr( 'Order By:' ) ); ?></label> 
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_orderby_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_orderby_posts' ) ); ?>">
					<?php 	
					$orderby = array('ID','date','title','menu_order','rand','modified','comment_count');
			
					if ( $orderby ) {
					foreach ( $orderby as $orderbydata ) {

						echo '<option value="'.$orderbydata.'" '.selected($wpw_orderby_posts,$orderbydata ).'>'.$orderbydata.'</option>';
					}
				    	echo '<option value="" '.selected($wpw_orderby_posts,'' ).' >Default</option>';
					}
			

				?>    
		</select>
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_order_posts' ) ); ?>"><?php _e( esc_attr( 'Order:' ) ); ?></label> 
		<select class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_order_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_order_posts' ) ); ?>">
					<?php 	
					$order = array('DESC','ASC');
			
					if ( $order ) {
					foreach ( $order as $orderdata ) {
						echo '<option value="'.$orderdata.'" '.selected($wpw_order_posts,$orderdata ).'>'.$orderdata.'</option>';
					}
					echo '<option value="" '.selected($wpw_order_posts,'' ).' >Default</option>';
					}
			

				?>    
		</select>
		</p>
		
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_number_of_posts' ) ); ?>"><?php _e( esc_attr( 'Number of posts:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_number_of_posts' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_number_of_posts' ) ); ?>" type="text" value="<?php echo esc_attr( $wpw_number_of_posts ); ?>" placeholder="5">
		</p>
		<p>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_content_limit' ) ); ?>"><?php _e( esc_attr( 'Content limit:' ) ); ?></label> 
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_content_limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_content_limit' ) ); ?>" type="text" value="<?php echo esc_attr( $wpw_content_limit ); ?>" placeholder="5">
		</p>
		<p>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_show_featured_image' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_show_featured_image' ) ); ?>" type="checkbox" value="1" <?php checked( $wpw_show_featured_image, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_show_featured_image' ) ); ?>"><?php _e( esc_attr( 'Show Featured Image' ) ); ?> </label> 
		</p>
		<p>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_show_count' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_show_count' ) ); ?>" type="checkbox" value="1" <?php checked( $wpw_show_count, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_show_count' ) ); ?>"><?php _e( esc_attr( 'Show Comment Count' ) ); ?> </label> 
		</p>
<p>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_content' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_hide_content' ) ); ?>" type="checkbox" value="1" <?php checked( $wpw_hide_content, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_content' ) ); ?>"><?php _e( esc_attr( 'Hide Content ' ) ); ?> </label> 
		</p>
<p>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_readmore' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_hide_readmore' ) ); ?>" type="checkbox" value="1" <?php checked( $wpw_hide_readmore, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_readmore' ) ); ?>"><?php _e( esc_attr( 'Hide Read more button' ) ); ?> </label> 
		</p>
		<p>
		<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_date' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'wpw_hide_date' ) ); ?>" type="checkbox" value="1" <?php checked( $wpw_hide_date, 1 ); ?>>
		<label for="<?php echo esc_attr( $this->get_field_id( 'wpw_hide_date' ) ); ?>"><?php _e( esc_attr( 'Hide Date' ) ); ?> </label> 
		</p>
		<hr>
		<h3>Need Support?</h3>
		<p><a href="mailto:raghunath.0087@gmail.com">Send your query</a> | <a href="https://wordpress.org/support/plugin/wp-posts-widget/reviews/?filter=5" target="_blank">Are you love it :) leave feedback here </a></p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['wpw_title'] 					= ( ! empty( $new_instance['wpw_title'] ) ) ? strip_tags( $new_instance['wpw_title'] ) : '';
		$instance['wpw_hide_title'] 			= ( ! empty( $new_instance['wpw_hide_title'] ) ) ? strip_tags( $new_instance['wpw_hide_title'] ) : '';
		$instance['wpw_post_type'] 			= ( ! empty( $new_instance['wpw_post_type'] ) ) ? strip_tags( $new_instance['wpw_post_type'] ) : '';
		$instance['wpw_show_hide_posts'] 		= ( ! empty( $new_instance['wpw_show_hide_posts'] ) ) ? $new_instance['wpw_show_hide_posts'] : '';
		$instance['wpw_action_on_posts'] 		= ( ! empty( $new_instance['wpw_action_on_posts'] ) ) ? $new_instance['wpw_action_on_posts'] : '';
		$instance['wpw_show_count'] 			= ( ! empty( $new_instance['wpw_show_count'] ) ) ? strip_tags( $new_instance['wpw_show_count'] ) : '';
		$instance['wpw_show_featured_image'] = ( ! empty( $new_instance['wpw_show_featured_image'] ) ) ? strip_tags( $new_instance['wpw_show_featured_image'] ) : '';
		$instance['wpw_number_of_posts'] 	= ( ! empty( $new_instance['wpw_number_of_posts'] ) ) ? strip_tags( $new_instance['wpw_number_of_posts'] ) : '';
		$instance['wpw_content_limit'] 	= ( ! empty( $new_instance['wpw_content_limit'] ) ) ? strip_tags( $new_instance['wpw_content_limit'] ) : '';
		$instance['wpw_orderby_posts'] 		= ( ! empty( $new_instance['wpw_orderby_posts'] ) ) ? strip_tags( $new_instance['wpw_orderby_posts'] ) : '';		
		$instance['wpw_order_posts'] 		= ( ! empty( $new_instance['wpw_order_posts'] ) ) ? strip_tags( $new_instance['wpw_order_posts'] ) : '';
		$instance['wpw_hide_date'] 		= ( ! empty( $new_instance['wpw_hide_date'] ) ) ? strip_tags( $new_instance['wpw_hide_date'] ) : '';
  $instance['wpw_category'] = ( ! empty( $new_instance['wpw_category'] ) ) ? $new_instance['wpw_category'] : '';
  $instance['wpw_taxonomy'] = ( ! empty( $new_instance['wpw_taxonomy'] ) ) ? $new_instance['wpw_taxonomy'] : '';	
		
		$instance['wpw_hide_content']       = ! empty( $new_instance['wpw_hide_content'] ) ? $new_instance['wpw_hide_content'] : esc_html__( '', 'wp-experts' );
		$instance['wpw_hide_readmore']       = ! empty( $new_instance['wpw_hide_readmore'] ) ? $new_instance['wpw_hide_readmore'] : esc_html__( '', 'wp-experts' );
		
		return $instance;
	}
	// register Wp_Posts_Widget widget
	static function register_wpw_post_widget() {
		register_widget( 'WpPostsWidget' );
	}
	/** plugin CSS **/
	function wpw_style_func()
	{
		$style='<style type="text/css">/* start wp posts widget CSS */.wp-post-widget-listing img.alignleft.wp-post-image {float: left; width: 75px; height:75px;margin:5px 10px  0px 0px}.wp-post-widget-listing span.title{font-size:initial;display:block;}.wp-post-widget-listing .post-li  {border-bottom:1px solid #e7e6e6;padding-bottom:10px;margin-bottom:10px;}.wp-post-widget-listing span.post-date {font-size: 12px;color: #666;}.wp-post-widget-listing a.readmore {display: block;}.wp-post-widget-listing p{font-size: 12px;padding:2px;line-height:20px;}.wp-post-widget-listing p.active-post span.title {color:#34b3e0;}/* End wp posts widget CSS*/</style>';
	echo $style;
	}
	// Add settings link to plugin list page in admin
        function wpw_add_settings_link( $links ) {
            $settings_link = '<a href="widgets.php">' . __( 'Configure Widget', 'wp-experts' ) . '</a> | <a href="mailto:raghunath.0087@gmail.com">' . __( 'Contact to Author', 'wp-experts' ) . '</a>';
            array_unshift( $links, $settings_link );
            return $links;
        }
 }// END class WpPostsWidget
}// END if
// register WpCategoriesWidget widget
function register_wp_posts_widget() {
    register_widget( 'WpPostsWidget' );
}
add_action( 'widgets_init', 'register_wp_posts_widget'); 
/**************************************************************
                END CLASSS Wp_Posts_Widget 
**************************************************************/
