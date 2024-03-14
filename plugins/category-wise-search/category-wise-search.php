<?php
/*
Plugin Name: Category Wise Search Widget
Plugin URI: http://wordpress.org/extend/plugins/category-wise-search/
Description: Category Wise Search Widget plugin.You have option search specific category content
Version: 1.3
Author: Shambhu Prasad Patnaik
Author URI:http://socialcms.wordpress.com/
*/
class Category_Wise_Search_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {

		parent::__construct(
	 		'category_wise_search', // Base ID
			'Category Wise Search', // Name
			array( 'classname' => 'widget_search','description' => __( 'A search form for your site with category', 'text_domain' ), ) // Args
		);
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
	 extract( $args );
	 $title = apply_filters( 'widget_title', $instance['title'] );
	 $default_select_text  = strip_tags($instance['default_select_text']);
	 if ( empty( $instance['default_select_text']))
 	 $default_select_text = 'Any Category';
   	 $show_count     = ! empty( $instance['count'] ) ? '1' : '0';
	 $show_hierarchy = ! empty( $instance['show_hierarchy'] ) ? '1' : '0';
 	 $default_cat    = isset( $instance['default_cat'] ) ? absint( $instance['default_cat'] ) : 0;
 	 $exclude    = isset($instance['exclude'])?esc_attr($instance['exclude']):'';
 	 $exclude_t  = isset($instance['exclude_t'])?esc_attr($instance['exclude_t']):'';
     if(isset($_GET['cat']))
     $default_cat =absint($_GET['cat']);
	 $cat_args = array('selected'=>$default_cat,'show_count' => $show_count, 'hierarchical' => $show_hierarchy,'show_option_all'=>$default_select_text,'echo'=>0,'id'=>'searchform_cat','exclude'=>$exclude,'exclude_tree'=>$exclude_t,'orderby'=>'name','order' => 'asc');
     ?>
	 <?php echo $before_widget; ?>
	 <?php if ( $title ) echo $before_title . $title . $after_title; ?>
	 
	 <?php 		 
	   $form= '<form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/' ) ) . '" >
	<div><label class="screen-reader-text" for="s">' . __('Search for:') . '</label>
	<input type="text" value="' . get_search_query() . '" name="s" id="s" />
    '.wp_dropdown_categories(apply_filters('widget_categories_dropdown_args', $cat_args)).'
	<input type="submit" id="searchsubmit" value="'. esc_attr__('Search') .'" />
	</div>
	</form>';
	echo apply_filters('get_search_form', $form);
    ?>
    
		
	 <?php echo $after_widget; ?>
     <?php
	 // Reset the global $the_post as this query will have stomped on it
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
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['default_select_text'] = strip_tags( $new_instance['default_select_text'] );
		if($instance['default_select_text']=='')
		$instance['default_select_text'] = 'Any Category';
    	$instance['default_cat'] = ($new_instance['default_cat']);
		$instance['count'] = !empty($new_instance['count']) ? 1 : 0;
		$instance['show_hierarchy'] = !empty($new_instance['show_hierarchy']) ? 1 : 0;
		$instance['exclude'] = strip_tags($new_instance['exclude']);
		$instance['exclude_t'] = strip_tags($new_instance['exclude_t']);
		return $instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
 	$title = $instance['title'];
	
	if ( isset( $instance[ 'default_select_text' ] ) ) {
	  $default_select_text = $instance[ 'default_select_text' ];
	 }
	 else {
	  $default_select_text = __( 'Any Category', 'text_domain' );
 	 }
	 $count = isset($instance['count']) ? (bool) $instance['count'] :false;
	 $show_hierarchy = isset( $instance['show_hierarchy'] ) ? (bool) $instance['show_hierarchy'] : true;
	 $default_cat    = isset($instance['default_cat'] ) ? absint($instance['default_cat']) : 0;
	 $exclude    = isset($instance['exclude'] ) ? strip_tags($instance['exclude']) : '';
	 $exclude_t  = isset($instance['exclude_t'] ) ? strip_tags($instance['exclude_t']) : '';
	?>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
	   <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
	 </p>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'default_select_text' ); ?>"><?php _e( 'Default select box text:' ); ?></label> 
	   <input class="widefat" id="<?php echo $this->get_field_id( 'default_select_text' ); ?>" name="<?php echo $this->get_field_name( 'default_select_text' ); ?>" type="text" value="<?php echo esc_attr( $default_select_text); ?>" />
	 </p>
	 <p><label for="<?php echo $this->get_field_id('exclude'); ?>"><?php _e( 'Exclude Categories :' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('exclude'); ?>" name="<?php echo $this->get_field_name('exclude'); ?>" type="text" value="<?php echo $exclude; ?>" />		
		<br>Enter a comma seperated category ID.<br>ex : <code>2,3</code> &nbsp;&nbsp;(This widget will display all of your categories except these categories).
	 </p>
	 <p><label for="<?php echo $this->get_field_id('exclude_t'); ?>"><?php _e( 'Exclude Categories With Childs :' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id('exclude_t'); ?>" name="<?php echo $this->get_field_name('exclude_t'); ?>" type="text" value="<?php echo $exclude_t; ?>" />		
		<br>Enter a comma seperated category ID.<br>ex : <code>1,4</code> &nbsp;&nbsp;(This widget will display all of your categories except these categories and its childs).
	 </p>
	 <p>
	  <label for="<?php echo $this->get_field_id( 'default_cat' ); ?>"><?php _e( 'Default select category :' ); ?></label> 
	   <?php $cat_arg = array( 'hierarchical' => 1,'selected'=>$default_cat,'show_option_all'=>($default_select_text!='')?esc_attr( $default_select_text):esc_attr( $default_select_text),'echo'=>0,'id'=>$this->get_field_id( 'default_cat' ),'name'=>$this->get_field_name( 'default_cat' ),'exclude'=>$exclude,'class'=>'widefat');
          echo wp_dropdown_categories($cat_arg);?>
	 </p>	 
	 <p>
	  <input class="checkbox" type="checkbox" <?php checked( $show_hierarchy ); ?> id="<?php echo $this->get_field_id( 'show_hierarchy' ); ?>" name="<?php echo $this->get_field_name( 'show_hierarchy' ); ?>" />
	  <label for="<?php echo $this->get_field_id( 'show_hierarchy' ); ?>"><?php _e( 'Show hierarchy' ); ?></label><br />
	  <input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>"<?php checked( $count ); ?> />
	  <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e( 'Show post counts' ); ?></label><br />
	 </p>
	 <?php 
	}
} // class Category_Wise_Search

// register Category_Wise_Search_Widget widget
add_action( 'widgets_init', create_function( '', 'register_widget( "Category_Wise_Search_Widget" );' ) );
register_deactivation_hook(__FILE__, 'shambhu_plugin_deactivate');

function shambhu_plugin_deactivate ()
{
 unregister_widget('Category_Wise_Search_Widget');
}
?>