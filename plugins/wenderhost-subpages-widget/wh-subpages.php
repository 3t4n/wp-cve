<?php
/*
Plugin Name: WenderHost Subpages widget
Description: Adds a subpages menu to your sidebar if subpages are present.
Author: Michael Wender
Version: 1.5.3
Plugin URI: http://www.wenderhost.com/tools/wordpress-plugins/wenderhost-subpages-widget/
Author URI: http://michaelwender.com
*/
/**
 * WenderHostSubpages Class
 */
class WenderHostSubpages extends WP_Widget {
    /** constructor */
    function WenderHostSubpages() {
        $widget_ops = array('classname' => 'widget_wenderhost-subpages', 'description' => __( 'Adds a subpages menu to your sidebar if subpages are present.' ) );		
		parent::WP_Widget(false, $name = 'WenderHost Subpages', $widget_ops);		
    }

    /** @see WP_Widget::widget */
    function widget( $args, $instance ) {		
        if( is_search() ) return;
		global $post;
		$parent_id = $this->get_parent_id( $post->ID );
		extract( $args );
        $title = apply_filters('widget_title', $instance['title']);
		$hide_title = $instance['hide_title'];
		$list_pages_args['sort_column'] = $instance['sort'];
		$list_pages_args['depth'] = $instance['depth'];
		( !empty( $instance['depth'] ) )? $list_pages_args['depth'] = $instance['depth'] : $list_pages_args['depth'] = 0;
		$list_pages_args['title_li'] = '';
		$list_pages_args['echo'] = 0;
		$list_pages_args['child_of'] = $parent_id;	
		$subpages = wp_list_pages( $list_pages_args );
		if($subpages){
			echo "\n". $before_widget;
			if($hide_title == false){
				echo "\n".$before_title; ?><a href="<?php echo get_permalink( $parent_id ) ?>"><?php	
					if(!empty( $title )){
						echo $title;
					} else {
						echo get_the_title( $parent_id );	
					}
				?></a><?php 
				echo $after_title. "\n";
			}
			echo '<ul>'.$subpages.'</ul>';
			echo $after_widget; 
		}
    }

    /** @see WP_Widget::update */
    function update( $new_instance, $old_instance ) {				
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['hide_title'] = strip_tags( $new_instance['hide_title'] );
		$instance['sort'] = strip_tags( $new_instance['sort'] );
		$instance['depth'] = strip_tags( $new_instance['depth'] );
        return $instance;
    }

    /** @see WP_Widget::form */
    function form($instance) {				
        extract( shortcode_atts( array(
			'title'	=>	'',
			'hide_title' => false,
			'sort'	=>	'menu_order',
			'depth' => 0
			), $instance ) );
		$hide_title_chk = ''; 
		if( $instance['hide_title'] == true ) $hide_title_chk = ' checked="checked"';
		$menu_order_chk = ''; $post_title_chk = '';
		( $instance['sort'] == 'menu_order' )? $menu_order_chk = ' checked="checked"' : $post_title_chk = ' checked="checked"';
        ?>
            <p><label for="<?php echo $this->get_field_id('title') ?>"><?php _e('Title:') ?> <input class="widefat" id="<?php echo $this->get_field_id('title') ?>" name="<?php echo $this->get_field_name('title') ?>" type="text" value="<?php echo $instance['title'] ?>" />
			<div style="font-size: 11px; color: #666">Leave title blank to display the parent page as the widget title.</div>
			</label>
			<label for="<?php echo $this->get_field_id('hide_title') ?>"><input type="checkbox" id="<?php echo $this->get_field_id('hide_title') ?>" name="<?php echo $this->get_field_name('hide_title'); ?>" value="true"<?php echo $hide_title_chk ?> /> <?php _e('Hide Title') ?></label>
			</p>
			<p><label for="<?php echo $this->get_field_id('sort') ?>"><?php _e('Sort Order:') ?>
				<ul>
					<li><label for="<?php echo $this->get_field_id('sort') ?>"><input type="radio" id="<?php echo $this->get_field_id('sort') ?>" name="<?php echo $this->get_field_name('sort'); ?>" value="menu_order"<?php echo $menu_order_chk ?> /> <?php _e('Menu Order') ?></label></li>
					<li><label for="<?php echo $this->get_field_id('sort') ?>"><input type="radio" id="<?php echo $this->get_field_id('sort') ?>" name="<?php echo $this->get_field_name('sort'); ?>" value="post_title"<?php echo $post_title_chk ?> /> <?php _e('Page Title') ?></label></li>
				</ul>
			</p>
			<p><label for="<?php echo $this->get_field_id('depth') ?>"><?php _e('Depth:') ?></label>
			<select name="<?php echo $this->get_field_name('depth') ?>" id="<?php echo $this->get_field_id('depth') ?>"><?php
			$depths = array('Default (all in hierarchy)' => 0, 'Flat List (all subpages)' => -1, '1 Level' => 1, '2 Levels' => 2, '3 Levels' => 3, '4 Levels' => 4, '5 Levels' => 5);
			foreach($depths as $key => $value){
				echo ($instance['depth'] == $value)? '<option value="'.$value.'" selected="selected">' : '<option value="'.$value.'">';	
				echo $key.'</option>';				
			}
			?></select></p>
        <?php 
    }
	
	/**
	* Retrieves the post_parent for a given $post->ID
	*
	* @param int $id Post ID we are retrieving the parent for.
	*/
	function get_parent_id($id){
		wp_cache_delete( $id, 'posts' );
		$parent_id = array_pop( get_post_ancestors( $id ) );
		if( empty( $parent_id ) ){
			return $id;	
		} else {
			return $parent_id;	
		}
	}		
	
} // class WenderHostSubpages
// register WenderHostSubpages widget
add_action('widgets_init', create_function('', 'return register_widget("WenderHostSubpages");'));
?>